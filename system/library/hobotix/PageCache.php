<?php

namespace hobotix;

final class PageCache{
	private $lifetime 		= 600;
	private $gzLevel 		= 4;
	private $minify 		= false;

	private $crawlerDetect 	= null;
	private $mobileDetect 	= null;
	private $htmlMinifier 	= null;

	private $db 			= null;
	private $log 			= null;
	private $cache 			= null;

	private $cacheableRoutes = array();
	private $excludeRoutes = array();

	public function getTTL(){
		return $this->lifetime;		
	}

	public function __construct($loadLibraries = true){
		if (!is_cli()){
			header('X-DEV-ENGINE: ' . PHP_VERSION);
		}

		$this->loadSettings();

		if ($loadLibraries){
			if (version_compare(PHP_VERSION, '8.1.0')){
				if( !class_exists('Composer\\Autoload\\ClassLoader') ){
					require_once(DIR_SYSTEM . '../vendor/autoload.php');
				}
			}

			require_once(DIR_SYSTEM . 'library/hobotix/MinifyAdaptor.php');

			if (!empty($_SERVER['REMOTE_ADDR'])){
				require_once(DIR_SYSTEM . 'library/db.php');
				require_once(DIR_SYSTEM . 'library/log.php');
				require_once(DIR_SYSTEM . 'library/cache.php');
				$this->db 	= new \DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
				$this->log 	= new \Log;

				$query = $this->db->ncquery("SELECT user_id FROM user WHERE ip = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "' AND status = 1");
				if ($query->num_rows){

					define('ADMIN_SESSION_DETECTED', true);
					header('X-FPC-ADSD: ' . ADMIN_SESSION_DETECTED);

				} else {
					define('ADMIN_SESSION_DETECTED', false);
				}
			} else {
				define('ADMIN_SESSION_DETECTED', false);
			}

			$this->crawlerDetect 	= new \Jaybizzle\CrawlerDetect\CrawlerDetect; 	
			$this->mobileDetect 	= new \Detection\MobileDetect;

			if ($this->crawlerDetect->isCrawler()){
				define('CRAWLER_SESSION_DETECTED', true);
			} else {
				define('CRAWLER_SESSION_DETECTED', false);
			}

			if ($this->pagespeedBots){
				if (in_array($this->crawlerDetect->getMatches(), $this->pagespeedBots)){
					define('PAGESPEED_SESSION_DETECTED', true);	
				} else {
					define('PAGESPEED_SESSION_DETECTED', false);
				}
			} else {
				define('PAGESPEED_SESSION_DETECTED', false);
			}

			if (!CRAWLER_SESSION_DETECTED && !PAGESPEED_SESSION_DETECTED){
				define('ADD_METRICS_TO_FRONT', true);
			} else {
				define('ADD_METRICS_TO_FRONT', false);
			}

			if ($this->mobileDetect->isMobile()){
				define('IS_MOBILE_SESSION', true);
			} else {
				define('IS_MOBILE_SESSION', false);
			}

			if ($this->mobileDetect->isTablet()){
				define('IS_TABLET_SESSION', true);
			} else {
				define('IS_TABLET_SESSION', false);
			}
		}

	}

	private function loadSettings(){
		$setting = @file_get_contents(DIR_SYSTEM . 'config/pagecache.json');	
		$setting = json_decode($setting, true);

		foreach ($setting as $key => $value){
			$this->{$key} = $value;
		}
	}

	private static function formatBytes($size, $precision = 2){
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');   

		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

	private function validateTTLFile(){
		if (file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache')){
			$time = time() - filemtime(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');

			if ($time > ($this->getTTL() * 2)){
				@unlink(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
				return true;
			}

			return false;
		}

		return true;
	}

	public function validateIfToCache(){		
		if (!$this->validateTTLFile()){
			header('X-NO-FPC-REASON: validateTTLFile');
			return false;
		}

		if (defined('IS_DEBUG') && IS_DEBUG){
			header('X-NO-FPC-REASON: IS_DEBUG');
			return false;
		}

		if (defined('ADMIN_SESSION_DETECTED') && ADMIN_SESSION_DETECTED){
			header('X-NO-FPC-REASON: ADMIN_SESSION_DETECTED');
			return false;
		}

		if (defined('CRAWLER_SESSION_DETECTED') && CRAWLER_SESSION_DETECTED){
			if (!defined('PAGESPEED_SESSION_DETECTED') || !PAGESPEED_SESSION_DETECTED){
				return false;
			}
		}

		if (defined('DEBUGSQL') && DEBUGSQL){
			header('X-NO-FPC-REASON: DEBUGSQL');
			return false;
		}

		if (thisIsAjax()){
			header('X-NO-FPC-REASON: thisIsAjax');
			return false;
		}

		if (thisIsUnroutedURI()){
			header('X-NO-FPC-REASON: thisIsUnroutedURI');
			return false;
		}

		if (is_cli()){
			header('X-NO-FPC-REASON: is_cli');
			return false;
		}

		if ($_SERVER['REQUEST_METHOD'] != 'GET'){
			header('X-NO-FPC-REASON: !GET');
			return false;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'GET'){
			if (count($_REQUEST) == 0){
				return true;
			}

			if (isset($_REQUEST['_route_'])){
				foreach ($this->excludeRoutes as $excludeRoute){
					if (strpos($_REQUEST['_route_'], $excludeRoute) !== false){
						header('X-NO-FPC-REASON: excludeRoutes');
						return false;
					}
				}

				foreach ($this->cacheableRoutes as $cacheableRoute){
					if (strpos($_REQUEST['_route_'], $cacheableRoute) !== false){
						return true;
					}
				}

				$shortUrlMapConfig = loadJsonConfig('shorturlmap');
				if ($shortUrlMapConfig){
					foreach ($shortUrlMapConfig as $key => $code){
						$matches = [];
						preg_match('/^[' . $code . '][0-9]+\//', $_REQUEST['_route_'], $matches);				
						if (count($matches) == 1){
							return true;
						}
					}
				}

				if ($_REQUEST['_route_'] == ''){					
					return true;
				}
			}		
		}

		header('X-NO-FPC-REASON: !cacheableRoutes');
		return false;
	}

	public function prepareCacheDirAndGetCachePath($check = true, $return_simple_path = false){		
		$cacheRouteString = md5(json_encode($_REQUEST) . $_SERVER['HTTP_HOST'] . (int)ADD_METRICS_TO_FRONT . (int)WEBPACCEPTABLE . (int)AVIFACCEPTABLE . (int)IS_MOBILE_SESSION . (int)IS_TABLET_SESSION);

		$cacheRouteDirShort 	= '';
		$cacheRouteDirShort .= $cacheRouteString[0] . $cacheRouteString[1] . '/';  
		$cacheRouteDirShort .= $cacheRouteString[2] . $cacheRouteString[3] . '/';
		$cacheRouteDirShort .= $cacheRouteString[4] . $cacheRouteString[5] . '/';

		$cacheDir = DIR_CACHE . PAGECACHE_DIR . $cacheRouteDirShort;
		if ($check && !is_dir($cacheDir)){
			mkdir($cacheDir, 0775, true);
		}

		if ($return_simple_path){
			return $cacheRouteString;
		}

		return $cacheDir . $cacheRouteString . '.cache';
	}

	private static function checkMinifierDir($cache){
		$cacheDir = MinifyAdaptor::getCacheTime();
		
		return preg_replace('/(' . str_replace('/', '\/', DIR_MINIFIED_NAME) . ')[0-9]+(\/)/', DIR_MINIFIED_NAME . $cacheDir . '/', $cache);
	}

	public function setMinifier($htmlMinifier){
		$this->htmlMinifier = $htmlMinifier; 
	}

	public function minifyCache($cache){
		$cache = $this->htmlMinifier->minify($cache);
		return $cache;
	}

	public function writeCache($cache){		
		if ($this->validateIfToCache()){
			if ($this->minify && $this->htmlMinifier){
				$cache = $this->minifyCache($cache);
			}

			$cache = gzencode($cache, $this->gzLevel);			
			file_put_contents($this->prepareCacheDirAndGetCachePath(), $cache);			
		}				
	}

	public function getCache(){		
		if ($this->validateIfToCache()){
			if (file_exists($path = $this->prepareCacheDirAndGetCachePath(false))){
				header('X-FPC-FILEMTIME: ' . filemtime($path));
				header('X-FPC-FILEHASH: ' . $this->prepareCacheDirAndGetCachePath(false, true));

				if (filemtime($path) < (time() - $this->lifetime)){
					@unlink($path);
					return false;
				}
				$cache = gzdecode(file_get_contents($path));

				return self::checkMinifierDir($cache); 
			}
			return false;
		}
		return false;
	}

	public function validateOther(){
		if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'YandexMarket') !== false){				
			return false;						
		}	

		return true;
	}		

	public function getReFeedsCount(){
		$feeds = glob(DIR_REFEEDS . 'remarketing_full_feed*');

		return count($feeds);
	}

	public function getPageCacheInfo(){
		$total_space = disk_total_space(DIR_CACHE);
		$free_space  = disk_free_space(DIR_CACHE);
		$used_space  = ($total_space - $free_space);

		$body  = 'Used ' . ' ' . self::formatBytes($used_space, 0) . ' of ' . self::formatBytes($total_space, 0); 
		$class = 'good';
			
		if (($total_space*0.5 < $used_space)){
			$class = 'warn';
		}

		if (($total_space*0.75 < $used_space)){
			$class = 'bad';
		}

		$json = [
			'body'  	=> $body,
			'used'		=> self::formatBytes($used_space, 0),
			'total'		=> self::formatBytes($total_space, 0),
			'class' 	=> $class,
		];

		return $json;
	}

	public function getRedisInfo(){
		$redis = new \Redis();
		if ($redis->connect(REDIS_SOCKET)) {
			$ans = $redis->info();

			$body = $ans['used_memory_human'];
			$class= 'good';

		} else {

			$body = 'ERR';
			$class = 'bad';

		};	

		$json = [
			'body'  	=> $body,
			'class' 	=> $class,
		];

		return $json;		
	}

	public function pingAPI(){
		$body 	= '';
		$engine = '';
		$class  = 'good';

		try {
			$httpClient = new \GuzzleHttp\Client();
			$httpResponse = $httpClient->request('GET', HTTP_CATALOG . 'api/ping?ping=1');		

			if ($httpResponse->getStatusCode() != 200){

				$body = 'FAIL: ' . $httpResponse->getStatusCode();
				$class = 'bad';

			} else {

				foreach ($httpResponse->getHeaders() as $name => $value){

					if (mb_strtoupper($name) == 'X-NO-FPC-TIME'){
						$value = $value[0];
						$body = round($value, 2);																

						if ((float)$value > 0.2){
							$class = 'warn';
						}

						if ((float)$value > 0.5){
							$class = 'bad';
						}
					}

				}

			}
		} catch (\GuzzleHttp\Exception\ClientException $e){

			$body  = $e->getMessage();
			$class = 'bad';

		} catch (\GuzzleHttp\Exception\ServerException $e) {

			$body  = 	$e->getMessage();
			$class = 	'bad';

		}

		$json = [
			'body'  	=> $body,
			'engine'	=> $engine,
			'class' 	=> $class,
		];

		return $json;
	}

	public function getServerResponceTime(){
		$body 	= '';
		$engine = '';
		$class  = 'good';

		try {
			$httpClient 	= new \GuzzleHttp\Client();
			$httpResponse 	= $httpClient->request('GET', HTTP_CATALOG);

			if ($httpResponse->getStatusCode() != 200){
				$body = 'FAIL: ' . $httpResponse->getStatusCode();
				$class = 'bad';

			} else {

				foreach ($httpResponse->getHeaders() as $name => $value){					
					if (mb_strtoupper($name) == 'X-NO-FPC-TIME'){
						$value = $value[0];
						$body = round($value, 3);																

						if ((float)$value > 0.2){
							$class = 'warn';
						}

						if ((float)$value > 0.5){
							$class = 'bad';
						}
					} elseif (mb_strtoupper($name) == 'X-FPC-TIME') {
						$value = $value[0];
						$body = round($value, 3);																

						if ((float)$value > 0.009){
							$class = 'warn';
						}

						if ((float)$value > 0.01){
							$class = 'bad';
						}
					} elseif (mb_strtoupper($name) == 'X-DEV-ENGINE'){
						$engine = $value[0];
					} else {
						continue;
					}
				}

			}

		} catch (\GuzzleHttp\Exception\ClientException $e){

			$body  = 	$e->getMessage();
			$class = 	'bad';

		} catch (\GuzzleHttp\Exception\ServerException $e) {

			$body  = 	$e->getMessage();
			$class = 	'bad';

		}

		$json = [
			'body'  	=> $body,
			'engine'	=> $engine,
			'class' 	=> $class,
		];

		return $json;
	}

}														
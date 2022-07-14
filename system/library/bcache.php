<?
	class BCache {
		private $bfile;
		private $bfile_t;
		private $compression_level = 6;
		private $do_gzip = false;
		private $nocache_mode = false;
		
		public function __construct($registry) { 
			$this->config = $registry->get('config');
			
			//Check FirstAfterRestartRun
			$d = DIR_CACHE . BCACHE_DIR;
			if (!is_dir($d)) {
				mkdir($d, 0775);         	
			}
			
			$d = DIR_CACHE . BCACHE_DIR .'/js';
			if (!is_dir($d)) {
				mkdir($d, 0775);         	
			}
			
			$d = DIR_CACHE . BCACHE_DIR . '/css';
			if (!is_dir($d)) {
				mkdir($d, 0775);         	
			}
			
			$this->nocache_mode = file_exists(DIR_CACHE . BCACHE_DIR . '/nocache') || file_exists(DIR_CACHE . PAGECACHE_DIR . '/nopagecache');
			
		}
		
		public function minify($content){
			
			$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s'       // shorten multiple whitespace sequences
			);
			
			$replace = array(
			'>',
			'<',
			'\\1'
			);
			
			return preg_replace($search, $replace, $content);
		}
		
		private function minifyJS($content){
			return $content;
		}
		
		
		public function minifyCSSSimple($content){
			return $this->minify($content);	
		}
		
		public function optimizeCSS($content){
			$content;	
		}
		
		
		public function minifyCSS($cssFiles = array()){
			$cacheFileName = md5(implode('p', $cssFiles)).'.css';
			
			if ($this->do_gzip){
				$cacheFileName .= '.gz';
			}
			
			//	$fullCacheName = DIR_CACHE . BCACHE_DIR .'css/'. $cacheFileName;
			
			$cacheValid = true;
			if (file_exists($fullCacheName)){
				$cacheLastModified = filemtime($fullCacheName);
				
				foreach ($cssFiles as $scriptFile){
					if (filemtime(DIR_SITE . $scriptFile) > $cacheLastModified) {
						$cacheValid = false;
						@unlink($fullCacheName);
						break;
					}
				}			
				} else {
				$cacheValid = false;
			}
			
			if (!$cacheValid){
				$cCode = '';
				foreach ($cssFiles as $scriptFile) {	
					$cCode .= file_get_contents($scriptFile)."\r\n";								
				}
				
				$cCodeMin = $this->optimizeCSS($cCode);
				
				if ($this->do_gzip){
					$cCodeMin = gzencode($cCodeMin, $this->compression_level);
				}
				
				file_put_contents($fullCacheName, $cCodeMin, LOCK_EX);
			}
			
			return $cacheFileName;		
		}
		
		
		
		public function SetFile($file, $dir=''){
		
			
			if (IS_HTTPS){
				$file = 'https_' . $file;			
			}
			
			if (!WEBPACCEPTABLE){
				$file = 'nowebp_' . $file;			
			}
			
			
			if (mb_strlen($dir)>0){
				if (!is_dir(DIR_CACHE . BCACHE_DIR . $dir )){
					mkdir(DIR_CACHE . BCACHE_DIR . $dir);
				}
				$this->bfile   = DIR_CACHE . BCACHE_DIR . $dir . '/' . $file;
				$this->bfile_t = './' . BCACHE_DIR . $dir . '/' . $file;
				} else {
				$this->bfile   = DIR_CACHE . BCACHE_DIR . $file;
				$this->bfile_t = './' . BCACHE_DIR . $file;
			}	
		}
		
		public function minifyJSOne($scriptFile){
			$cacheFileName = md5($scriptFile).'.js';
			$fullCacheName = DIR_CACHE . BCACHE_DIR .'js/'. $cacheFileName;
			
			$cacheValid = true;		
		}
		
		public function MergeJS($scriptArray = array()){		
			$cacheFileName = md5(implode('p', $scriptArray)).'.js';
			
			if ($this->do_gzip){
				$cacheFileName .= '.gz';
			}
			
			$fullCacheName = DIR_CACHE . BCACHE_DIR .'js/'. $cacheFileName;
			
			$cacheValid = true;
			if (file_exists($fullCacheName)){
				$cacheLastModified = filemtime($fullCacheName);
				
				foreach ($scriptArray as $scriptFile){
					
					if (strpos($scriptFile, '?v')) {
						$scriptFile = mb_substr($scriptFile, 0, strpos($scriptFile, '?v'));
					}
					
					if (filemtime(DIR_SITE . $scriptFile) > $cacheLastModified) {
						$cacheValid = false;
						@unlink($fullCacheName);
						break;
					}
				}
				
				} else {
				$cacheValid = false;
			}
			
			if (!$cacheValid){
				$cCode = '';
				foreach ($scriptArray as $scriptFile) {	
					$cCode .= file_get_contents($scriptFile)."\r\n";								
				}
				
				$cCodeMin = $this->minifyJS($cCode);
				
				if ($this->do_gzip){
					$cCodeMin = gzencode($cCodeMin, $this->compression_level);
				}
				
				file_put_contents($fullCacheName, $cCodeMin, LOCK_EX);
			}
			
			
			return $cacheFileName;		
		}
		
		public function CheckFile(){
			
			if (IS_DEBUG) {		
				$this->DeleteFile();
				
				return false;		
			}
			
			
			if ($this->nocache_mode) {		
				$this->DeleteFile();
				
				return false;		
			}
			
			if (file_exists($this->bfile)){
				
				if (filemtime($this->bfile) + BCACHE_TTL >= time()){							
					
					return true;
					
					} else {
					
					$this->DeleteFile();
					return false;
					
				}
				} else {
				
				return false;
				
			}
		}
		
		public function DeleteFile(){
			
			if (file_exists($this->bfile)) {
				return @unlink($this->bfile);
				} else {
				return false;
			}
			
		}
		
		public function WriteFile($content, $json = false, $minify = true){
			
			if ($json){
				$content = base64_encode(serialize($content));
			}
			
			if ($minify) {
				file_put_contents($this->bfile, $this->minify($content), LOCK_EX);
				} else {
				file_put_contents($this->bfile, $content, LOCK_EX);
			}
			
		}
		
		public function ReturnFile(){
			
			return $this->bfile_t;
			
		}
		
		public function deleteCache($mask){
			
			$files = glob(DIR_CACHE . BCACHE_DIR . $mask . '.*');
			
			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						@unlink($file);
						clearstatcache();
					}
				}
			}
			
			$files = glob(DIR_CACHE . BCACHE_DIR . $mask . '/' . '*');
			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						@unlink($file);
						clearstatcache();
					}
				}
			}
		}
		
		public function ReturnFileContent($json = false){
			
			if ($json){
				//var_dump(unserialize(file_get_contents($this->bfile)));
				return unserialize(base64_decode(file_get_contents($this->bfile)));
			}
			
			return file_get_contents($this->bfile);
			
		}
		
	}	
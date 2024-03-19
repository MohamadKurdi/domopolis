<?php
	class ControllerCommonSeoPro extends Controller {
		private $cache_data 				= null;
		private $language_code 				= '';
		private $language_id 				= 2;
		private $languageSettingsCacheData 	= [];
		private $mapFrom 					= ['intersection_id'];
		private $mapTo 						= ['category_id'];
		private $allowedGetParams			= ['tracking','utm_term','utm_source','utm_medium','utm_campaign','utoken','oid','gclid','hello','search'];

		private $doNotCheckRoutes 			= [
			'error/not_found',
			'feed/robots_txt',
			'feed/google_sitemap'
		];

		private $doNotCheckRoutesPartly		= [
			'payment/',
			'kp/errorreport',
			'yamarket/api',
			'api/'
		];
		
		public function __construct($registry) {
			parent::__construct($registry);			
			$this->language_id = (int)$this->config->get('config_language_id');
			if (!empty($this->session->data) && !empty($this->session->data['language'])){				
				$this->language_code = $this->session->data['language'];				
			} else {
				if (!empty($this->registry->get('languages_id_code_mapping'))){
					$this->language_code = $this->registry->get('languages_id_code_mapping')[$this->language_id];							
				} else {				
					$this->load->model('localisation/language');
					$language = $this->model_localisation_language->getLanguage($this->language_id);
					$this->language_code = $language['code'];
				}
			}
			
			$this->rebuildAllCaches();
		}			

		private function getKeyword($query){
			$exploded_query = explode('=', $query);
			if ($this->config->get('config_seo_url_from_id') && $this->registry->has('short_uri_queries') && count($exploded_query) == '2' && !empty($this->registry->get('short_uri_queries')[$exploded_query[0]])){
				return $this->registry->get('short_uri_queries')[$exploded_query[0]] . (int)$exploded_query[1];				
			}

			if (isset($this->cache_data['queries'][$query])){
				return $this->cache_data['queries'][$query];
			}

			return false;
		}

		private function getQuery($keyword){
			if ($this->config->get('config_seo_url_from_id') && $this->registry->get('short_uri_keywords') && preg_match('/^[a-z]{1,2}[0-9]+$/', $keyword)){
				preg_match('/^[a-z]/', $keyword, $code);
				preg_match('/[0-9]+$/', $keyword, $identifier);

				if (count($code) == 1 && count($identifier) == 1 && !empty($this->registry->get('short_uri_keywords')[$code[0]])){
					return $this->registry->get('short_uri_keywords')[$code[0]] . '=' . $identifier[0];
				}
			}

			if (isset($this->cache_data['keywords'][$keyword])){
				return $this->cache_data['keywords'][$keyword];
			}

			return false;
		}
		
		private function checkIfUriISUnrouted($uri){
			if (stripos($uri, 'index.php?route=') !== false){
				return true;
			}
			
			return false;
		}
		
		private function mapQuery($query){
			return str_replace($this->mapFrom, $this->mapTo, $query);		
		}
		
		private function mapQueryRev($query){
			return str_replace($this->mapTo, $this->mapFrom, $query);		
		}
		
		private function getLanguageCodeForStoreID($store_id){
			
			if (!empty($this->languageSettingsCacheData[$store_id])){
				return $this->languageSettingsCacheData[$store_id];
			}
			
			return $this->config->get('config_language');		
		}
		
		private function getFullLanguageByCode($code){
			if (!empty($this->registry->get('languages')[$code])){
				return $this->registry->get('languages')[$code];
			}
			
			$this->load->model('localisation/language');
			return $this->model_localisation_language->getFullLanguageByCode($code);			
		}
		
		private function rebuildAllCaches(){
			if (!($this->languageSettingsCacheData = $this->cache->get('seo_pro.setting_language_data'))){
				$this->languageSettingsCacheData = [];
				$query = $this->db->query("SELECT * FROM setting WHERE `group` = 'config' AND `key` = 'config_language'");
				
				foreach ($query->rows as $row){
					$this->languageSettingsCacheData[$row['store_id']] = $row['value'];
				}
				
				$this->cache->set('seo_pro.setting_language_data', $this->languageSettingsCacheData);
			}			
			
			foreach ($this->registry->get('languages') as $language){
				$cache_data = $this->cache->get('seo_pro.structure.' . $language['language_id']);
				
				if (!$cache_data) {
					$query = $this->db->query("SELECT `keyword`, `query` FROM url_alias WHERE language_id IN (" . (int)$language['language_id'] . ", -1)");
					
					$cache_data = array();
					foreach ($query->rows as $row) {
						$cache_data['keywords'][$row['keyword']] 	= $row['query'];
						$cache_data['queries'][$row['query']] 		= $row['keyword'];
					}
					$this->cache->set('seo_pro.structure.' . $language['language_id'], $cache_data);
				}				
				
				if ($this->language_id == $language['language_id']){
					$this->cache_data = $cache_data;
				}
			}
		}
		
		private function rebuildCacheForDevMode($language_id){
			$this->cache_data = $this->cache->get('seo_pro.structure.' . $language_id);
			
			if (!$this->cache_data) {
				$query = $this->db->query("SELECT `keyword`, `query` FROM url_alias WHERE language_id IN (" . (int)$language_id . ", -1)");
				
				$this->cache_data = array();
				foreach ($query->rows as $row) {
					$this->cache_data['keywords'][$row['keyword']] 	= $row['query'];
					$this->cache_data['queries'][$row['query']] 	= $row['keyword'];
				}
				$this->cache->set('seo_pro.structure.' . $language_id, $this->cache_data);
			}
		}
		
		private function resetCache($language_id){
			if ($cache_data = $this->cache->get('seo_pro.structure.' . $language_id)){			
				$this->cache_data = $cache_data;
				} else {
				$this->rebuildCacheForDevMode($language_id);
			}
		}	
		
		public function index() {
			$shortcodes = new Shortcodes($this->registry);
			$this->registry->set('shortcodes', $shortcodes);
			
			$this->load->helper('shortcodes_default');
			
			$class         = new ShortcodesDefault($this->registry);
			$scDefaults    = get_class_methods($class);
			foreach ($scDefaults as $shortcode) {
				$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
			}
			
			$files = glob(DIR_APPLICATION . '/view/shortcodes/*.php');
			if ($files) {
				foreach ($files as $file) {
					require_once($file);
					
					$file       = basename($file, ".php");
					$extClass   = 'Shortcodes' . preg_replace('/[^a-zA-Z0-9]/', '', $file);
					
					$class      = new $extClass($this->registry);
					$scExtensions = get_class_methods($class);
					foreach ($scExtensions as $shortcode) {
						$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
					}
				}
			}
			
			$file = DIR_TEMPLATE . $this->config->get('config_template') . '/shortcodes_theme.php';
			if (file_exists($file)) {
				require_once($file);
				
				$class         = new ShortcodesTheme($this->registry);
				$scThemes      = get_class_methods($class);
				foreach ($scThemes as $shortcode) {
					$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
				}
			}
			
			$file = DIR_TEMPLATE . $this->config->get('config_template') . '/shortcodes_custom.php';
			if (file_exists($file)) {
				require_once($file);
				
				$class         = new ShortcodesCustom($this->registry);
				$scCustom      = get_class_methods($class);
				foreach ($scCustom as $shortcode) {
					$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
				}
			}
			
			if ($this->config->get('config_seo_url')) {			
				$this->url->addRewrite($this);
				} else {
				return;
			}			
						
			if (!is_null($this->registry->get('ocfilter'))) {
				$this->url->addRewrite($this->registry->get('ocfilter'));								
			}
			
			if ($this->config->get('config_second_language')){				
				if(isset($this->request->get['_route_'])){
					
					$urllanguage = explode('/', trim(utf8_strtolower($this->request->get['_route_']), '/'));
					$this->load->model('localisation/language');
					$languages = $this->registry->get('languages');
					
					$lang = array();
					foreach($languages as $language){
						if ($language['urlcode']){
							$lang[] = $language['urlcode'];
						}
					}
					
					if(isset($urllanguage[0]) && in_array($urllanguage[0], $lang)){
						
						if(count($urllanguage) > 1){
							$replace_lang = $urllanguage[0] . "/";
							}else{
							$replace_lang = $urllanguage[0];
						}
						
						$this->request->get['_route_'] = str_replace($replace_lang, '', $this->request->get['_route_']);					
						if($this->request->get['_route_'] == '' || $this->request->get['_route_'] == '/'){
							unset($this->request->get['_route_']);
						}
					}
				}	
			}
			
			if(isset($this->request->get['_route_'])){
				$account = explode('/', trim(utf8_strtolower($this->request->get['_route_']), '/'));
				if (!empty($account[0]) && !empty($account[1]) && $account[0] == 'account'){
					$this->request->get['_route_'] = str_replace('account/', '', $this->request->get['_route_']);
				}
			}
			
			if (!isset($this->request->get['_route_'])) {
				$this->validate();
				} else {
				$route_ = $route = $this->request->get['_route_'];
				unset($this->request->get['_route_']);
				$parts = explode('/', trim(utf8_strtolower($route), '/'));	
				$rows = [];
								
				foreach ($parts as $keyword) {
					if (($query_result = $this->getQuery($keyword)) !== false){
						$rows[] = ['keyword' => $keyword, 'query' => $query_result];
					}
				}								
				
				if (!empty($rows[0]) && !empty($rows[0]['query']) && $rows[0]['query'] == 'module/mega_filter/ajaxinfo'){
					$this->request->get['route'] = 'module/mega_filter/ajaxinfo';
				}
								
				if (!empty($rows[0]) && !empty($rows[0]['query'])){
					$url = explode('=', $rows[0]['query'], 2);
					
					if ($url[0] == 'category_id'){
						if ($this->checkIfCategoryCanHaveIntersections($url[1])){							
							$rowCounter = 0;
							foreach ($rows as &$row){
								if ($rowCounter > 0){
									$url = explode('=', $row['query'], 2);
									if (in_array($url[0], $this->mapTo)){
										
										$row['query'] = $this->mapQueryRev($row['query']);
									}
								}								
								$rowCounter++;
							}
						}
					}
				}		
				
				if (!empty($rows[1]) && !empty($rows[1]['query'])){
					$url = explode('=', $rows[1]['query'], 2);
					
					if ($url[0] == 'category_id'){
						if ($this->checkIfCategoryCanHaveIntersections($url[1])){							
							$rowCounter = 0;
							foreach ($rows as &$row){
								if ($rowCounter > 1){
									$url = explode('=', $row['query'], 2);
									if (in_array($url[0], $this->mapTo)){
										
										$row['query'] = $this->mapQueryRev($row['query']);
									}
								}
								
								$rowCounter++;
							}							
						}
					}
				}
				
				
				if (count($rows) == sizeof($parts)) {
					
					$queries = array();
					
					unset($row);
					foreach ($rows as $row) {
						$queries[utf8_strtolower($row['keyword'])] = $row['query'];
					}
					
					reset($parts);																																						
					foreach ($parts as $part) {
						$url = explode('=', $queries[$part], 2);									
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
								} else {
								$this->request->get['path'] .= '_' . $url[1];
							}
							} elseif($url[0] == 'ncategory_id'){
							if (!isset($this->request->get['ncat'])) {
								$this->request->get['ncat'] = $url[1];
								} else {
								$this->request->get['ncat'] .= '_' . $url[1];
							}
							} elseif (count($url) > 1) {
							$this->request->get[$url[0]] = $url[1];
						}
					}
					} else {
					$this->request->get['route'] = 'error/not_found';
				}
				
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
					if (!isset($this->request->get['path'])) {
						$path = $this->getPathByProduct($this->request->get['product_id']);
						if ($path) $this->request->get['path'] = $path;
					}
					} elseif (isset($this->request->get['path'])) {
					if( empty( $this->request->get['route'] ) || strpos( $this->request->get['route'], 'module/mega_filter' ) === false ) {
						if( isset( $queries[$parts[0]] ) && strpos( $queries[$parts[0]], '/' ) !== false ) {
							$this->request->get['route'] = $queries[$parts[0]];
							} else {
							if( ! empty( $this->request->get['mfp'] ) ) {
								preg_match( '/path\[([^]]*)\]/', $this->request->get['mfp'], $mf_matches );
								
								if( ! empty( $mf_matches[1] ) ) {
									if( isset($this->request->get['manufacturer_id']) ) {
										$this->request->get['route'] = 'product/manufacturer/info';
										} else {
										$this->request->get['route'] = 'product/category';
									}
									} else {
									$this->request->get['route'] = 'product/category';
								}
								
								unset( $mf_matches );
								} else {
								$this->request->get['route'] = 'product/category';
							}
						}
					}					
					} elseif (isset($this->request->get['collection_id'])) {
					$this->request->get['route'] = 'product/collection';
					} elseif (isset($this->request->get['countrybrand_id'])) {
					$this->request->get['route'] = 'product/countrybrand';
					} elseif (isset($this->request->get['manufacturer_id'])) {
					
					if(!empty($this->request->get['route']) && $this->request->get['route'] == 'module/mega_filter/ajaxinfo' ) {
						
						} else {
						
						$this->request->get['route'] = 'product/manufacturer/info';
						if (isset($this->request->get['brand-display']) && $this->request->get['brand-display'] == 'products'){
							$this->request->get['route'] = 'product/manufacturer/products';		
							} elseif (isset($this->request->get['brand-display']) && $this->request->get['brand-display'] == 'collections'){
							$this->request->get['route'] = 'product/manufacturer/collections';		
							}  elseif (isset($this->request->get['brand-display']) && $this->request->get['brand-display'] == 'newproducts'){
							$this->request->get['route'] = 'product/manufacturer/newproducts';		
							} elseif (isset($this->request->get['brand-display']) && $this->request->get['brand-display'] == 'categories'){
							$this->request->get['route'] = 'product/manufacturer/categories';		
							}  elseif (isset($this->request->get['brand-display']) && $this->request->get['brand-display'] == 'articles'){
							$this->request->get['route'] = 'product/manufacturer/articles';		
							}   elseif (isset($this->request->get['brand-display']) && $this->request->get['brand-display'] == 'special'){
							$this->request->get['route'] = 'product/manufacturer/special';		
						}
					}
					} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
					} elseif (isset($this->request->get['information_attribute_id'])) {
					$this->request->get['route'] = 'information/information_attribute';
					} elseif (isset($this->request->get['news_id'])) {
					$this->request->get['route'] = 'news/article';
					} elseif (isset($this->request->get['ncat']) || isset($this->request->get['author'])) {
					$this->request->get['route'] = 'news/ncategory';
					} elseif (isset($this->request->get['landingpage_id'])) {
					$this->request->get['route'] = 'information/landingpage';
					} elseif (isset($this->request->get['actiontemplate_id'])) {
					$this->request->get['route'] = 'information/actiontemplate';
					} elseif (isset($this->request->get['actions_id'])) {
						if(!empty($this->request->get['route']) && $this->request->get['route'] == 'module/mega_filter/ajaxinfo' ) {

						} else {
							$this->request->get['route'] = 'information/actions';
						}					
					} elseif (isset($this->request->get['faq_category_id'])) {
					$this->request->get['route'] = 'information/faq_system';					
					} elseif(isset($this->cache_data['queries'][$route_])) {	
					header('X-REDIRECT: SeoProLib::index');				
					header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');				
					$this->response->redirect($this->cache_data['queries'][$route_]);
					} else {
					if (isset($queries[$parts[0]])) {
						$this->request->get['route'] = $queries[$parts[0]];
					}
				}
				
				$this->validate();

				if (empty($this->request->get['route'])){
					$this->request->get['route'] = 'common/home';
				}
									
				return $this->forward($this->request->get['route']);				
			}
		}
		
		public function rewriteLanguage($link, $language_id){
			$this->resetCache($language_id);
			$link = $this->rewrite($link);
			$this->resetCache($this->config->get('config_language_id'));
			
			return $link;
		}
		
		public function rewrite($link) {
			
			if (!$this->config->get('config_seo_url')) return $link;
			
			$seo_url = '';
			
			$component = parse_url(str_replace('&amp;', '&', $link));
			
			$data = array();
			parse_str($component['query'], $data);
			
			$route = $data['route'];
			unset($data['route']);
			
			$real_query = false;
			
			switch ($route) {
				case 'product/product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['path'] = $this->getPathByProduct($tmp['product_id']);					
						if (!$data['path']) return $link;
					}
					$data['product_id'] = $tmp['product_id'];

					foreach ($this->allowedGetParams as $allowedGetParam){
						if (isset($tmp[$allowedGetParam])) {
							$data[$allowedGetParam] = $tmp[$allowedGetParam];
						}
					}					
				}
				break;				
				
				case 'product/countrybrand':				
				break;
				
				case 'product/collection':
				if (isset($data['collection_id']) && !isset($data['manufacturer_id'])) {
					$tmp = $data;					
					$data['manufacturer_id'] = $this->getPathByCollection($data['collection_id']);
					$data['brand-display'] = 'collections';
					unset($data['collection_id']);
					if (!$data['manufacturer_id']) return $link;
					$data['collection_id'] = $tmp['collection_id'];
					
					} else {
					$tmp = $data;
					$data['brand-display'] = 'collections';
					unset($data['collection_id']);
					$data['collection_id'] = $tmp['collection_id'];
				}
				break;
				
				case 'product/manufacturer/products':
				$data['brand-display'] = 'products';
				break;
				
				case 'product/manufacturer/collections':
				$data['brand-display'] = 'collections';
				break;
				
				case 'product/manufacturer/newproducts':
				$data['brand-display'] = 'newproducts';
				break;
				
				case 'product/manufacturer/categories':
				$data['brand-display'] = 'categories';
				break;
				
				case 'product/manufacturer/articles':
				$data['brand-display'] = 'articles';
				break;
				
				case 'product/manufacturer/special':
				$data['brand-display'] = 'special';
				break;
				
				case 'news/ncategory':
				if (isset($data['ncat'])) {
					$ncategory = explode('_', $data['ncat']);
					$ncategory = end($ncategory);
					$data['ncat'] = $this->getPathByNewsCategory($ncategory);
					if (!$data['ncat']) return $link;			
				}
				break;
				
				case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
					if (!$data['path']) return $link;				
				}
				break;
				
				case 'account/reward':
				case 'account/address':
				case 'account/edit':
				case 'account/simpleedit':
				case 'account/simpleaddress/update':
				case 'account/simpleaddress/insert':
				case 'account/login':
				case 'account/otp':
				case 'account/order':
				case 'account/order/info':
				case 'account/order/completedorderslist':
				case 'account/order/cancelledorderslist':
				case 'account/order/inprocessorderslist':
				case 'account/order/preorderorderslist':
				case 'account/password':
				case 'account/return':
				case 'account/tracker':
				case 'account/wishlist':
				case 'account/viewed':
				case 'account/reviews':
				case 'account/support':
				case 'account/coupons':
				case 'account/promocodes':
				case 'account/newsletter':
				case 'account/transaction':
				case 'account/unsubscribe':
				case 'account/subscribe':
				case 'product/compare':
				$data['additionalroute'] = ['account/account' => $route];
				break;
				
				case 'product/product/review':
				case 'product/quickview':
				case 'product/picview':
				case 'module/alsoviewed/getindex':
				case 'module/alsopurchased/getindex':
				case 'information/information/info':
				case 'information/information/info2':
				case 'information/information/info_block':
				return $link;
				break;							
				
				default:
				$real_query = $route;
				break;
			}
			
			if (IS_HTTPS) {
				$link = $this->config->get('config_ssl');
				} else {
				$link = $this->config->get('config_url');
			}
			
			$link .= 'index.php?route=' . $route;
			
			if (count($data)) {
				$link .= '&amp;' . urldecode(http_build_query($data, '', '&amp;'));
			}
			
			$queries = array();
			
			$has_path = false;
			$copy = $data;
			foreach ($copy as $__key => $__value){
				if ($__key == 'path'){
					$has_path = true;
				}
			}
			
			foreach ($data as $key => $value) {
				switch ($key) {				
					case 'product_id':					
					case 'category_id':					
					case 'information_attribute_id':
					case 'news_id':
					case 'ncategory_id':
					case 'nauthor_id':
					case 'landingpage_id':
					case 'actiontemplate_id':
					$queries[] = $key . '=' . $value;
					$real_query = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'additionalroute':
					$queries[] = array_key_first($value);
					$queries[] = $value[array_key_first($value)];
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'intersection_id':
					$queries[] = 'intersection_id' . '=' . $value;
					$real_query = 'intersection_id' . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'countrybrand_id':
					$queries[] = 'product/manufacturer';
					$queries[] = $key . '=' . $value;
					$real_query = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'faq_category_id':
					$queries[] = 'information/faq_system';
					$queries[] = $key . '=' . $value;
					$real_query = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'information_id':
					$queries[] = 'information/faq_system';
					$queries[] = $key . '=' . $value;
					$real_query = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'actions_id':
					$queries[] = 'information/actions';
					$queries[] = $key . '=' . $value;
					$real_query = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					case 'manufacturer_id':
					if (!$has_path){
						$queries[] = 'product/manufacturer';
					}					
					$queries[] = $key . '=' . $value;
					if (isset($data['brand-display'])){
						$queries[] = 'brand-display' . '=' . $data['brand-display'];	
						unset($data['brand-display']);
					}
					$real_query = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;					
					break;
					
					case 'path':
					$categories = explode('_', $value);
					$real_query = 'category_id=' . $value;
					foreach ($categories as $category) {
						$queries[] = 'category_id=' . $category;
					}			
					unset($data[$key]);
					break;
					
					case 'ncat':
					$ncats = explode('_', $value);
					$real_query = 'ncategory_id=' . $value;
					foreach ($ncats as $ncat) {
						$queries[] = 'ncategory_id=' . $ncat;
					}														
					unset($data[$key]);
					break;																													
					
					/*		case 'collection_path':
						$real_query = 'collection_id=' . $value;	
						$queries[] = 'product/manufacturer';					
						$queries[] = 'brand-display=collections';
						unset($data[$key]);					
						break;
					*/
					
					case 'collection_id':
					$real_query = 'collection_id=' . $value;						
					$queries[] = $key . '=' . $value;
					unset($data[$key]);
					break;		
					
					//GLOBAL FILTERS
					case 'filterinstock':
					$queries[] = $key . '=' . (int)$value;
					$real_query = $key . '=' . (int)$value;
					unset($data[$key]);
					$postfix = 1;
					break;
					
					default:
					break;
				}
			}
			
			if(empty($queries)) {
				$queries[] = $route;
			}
			
			$rows = array();
			foreach($queries as $query) {										
				$query = $this->mapQuery($query);	
				if (($keyword_result = $this->getKeyword($query)) !== false){
					$rows[] = ['query' => $query, 'keyword' => $keyword_result];
				}
			}
			
			if(count($rows) == count($queries)) {
				$aliases = array();
				foreach($rows as $row) {
					$aliases[$row['query']] = $row['keyword'];
				}
				foreach($queries as $query) {
					$query = $this->mapQuery($query);
					
					$seo_url .= '/' . rawurlencode($aliases[$query]);
				}
			}
			
			$this->load->model('setting/setting');
			$this->load->model('localisation/language');
			$config_language = $this->getLanguageCodeForStoreID($this->config->get('config_store_id'));			
			
			if ($seo_url == ''){				
				if(!empty($this->language_code) && $this->language_code != $config_language){
					$language = $this->getFullLanguageByCode($this->language_code);
					if ($language && !empty($language['urlcode'])) {											
						if (!$this->checkIfUriISUnrouted($link)){							
							$link = str_replace($this->config->get('config_ssl'), '', $link);					
							$link = $language['urlcode'] . '/' . $link;
							$link = $this->config->get('config_ssl') . $link;							
						}
					}
				}				
				return $link;
			}
			
			$seo_url = trim($seo_url, '/');	
			
			if(!empty($this->language_code) && $this->language_code != $config_language){
				$language = $this->getFullLanguageByCode($this->language_code);
				if ($language && !empty($language['urlcode'])) {
					
					if ($seo_url){
						$seo_url = $language['urlcode'] . '/' . $seo_url;
						} else {
						$seo_url = $language['urlcode'];
					}
				}
				
				} elseif ($code = $this->config->get('config_language_code_explicit')){
				if ($code != $config_language) {
					$language = $this->getFullLanguageByCode($code);
					if ($language && isset($language['urlcode']) && $language['urlcode']) {
						if ($seo_url){
							$seo_url = $language['urlcode'] . '/' . $seo_url;
							} else {
							$seo_url = $language['urlcode'];
						}
					}				
				}
			}
			
			
			$seo_url = $this->config->get('config_ssl') . $seo_url;
			
			if (isset($postfix) && $this->config->get('config_seo_url_postfix')) {
				$seo_url .= trim($this->config->get('config_seo_url_postfix'));
				} else {
				//$seo_url .= '/';
			}
			
			if(substr($seo_url, -2) == '//') {
				$seo_url = substr($seo_url, 0, -1);
			}
			
			if ($real_query){				
				$this->updateHrefLang($real_query, $seo_url);				
			}
			
			if (count($data)) {
				$seo_url .= '?' . urldecode(http_build_query($data, '', '&amp;'));
			}
			
			return $seo_url;
		}
		
		private function updateHrefLang($query, $url){			
		}
		
		private function getPathByProduct($product_id) {
			$product_id = (int)$product_id;
			if ($product_id < 1) return false;
			
			static $path = null;
			if (!is_array($path)) {
				$path = $this->cache->get('product.seopath'.$this->language_id);
				if (!is_array($path)) $path = array();
			}
			
			if (!isset($path[$product_id])) {
				$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . $product_id . "' AND category_id 
				NOT IN (". BIRTHDAY_DISCOUNT_CATEGORY .", ". (int)$this->config->get('config_special_category_id') .", ". GENERAL_MARKDOWN_CATEGORY .") 
				ORDER BY main_category DESC LIMIT 1");
				
				if ($query->num_rows){
					$path[$product_id] = $this->getPathByCategory((int)$query->row['category_id']);
					$this->cache->set('product.seopath'.$this->language_id, $path, DB_CACHED_EXPIRE, true);			
				} elseif (!empty($this->request->get['path'])) {
					$path[$product_id] = $this->request->get['path'];
				} else {
					$path[$product_id] = 0;
				}									
			}
			
			return $path[$product_id];
		}
		
		private function getPathByNewsCategory($ncategory_id){
			$ncategory_id = (int)$ncategory_id;
			if ($ncategory_id < 1) return false;
			
			static $path = null;
			if (!is_array($path)) {
				$path = $this->cache->get('ncats.seopath'.$this->language_id);
				if (!is_array($path)) $path = array();
			}
			
			if (!isset($path[$ncategory_id])) {
				$max_level = 3;
				
				$sql = "SELECT CONCAT_WS('_'";
				for ($i = $max_level-1; $i >= 0; --$i) {
					$sql .= ",t$i.ncategory_id";
				}
				$sql .= ") AS path FROM ncategory t0";
				for ($i = 1; $i < $max_level; ++$i) {
					$sql .= " LEFT JOIN ncategory t$i ON (t$i.ncategory_id = t" . ($i-1) . ".parent_id)";
				}
				$sql .= " WHERE t0.ncategory_id = '" . $ncategory_id . "'";
				
				$query = $this->db->query($sql);
				
				$path[$ncategory_id] = $query->num_rows ? $query->row['path'] : false;
				
				$this->cache->set('ncats.seopath'.$this->language_id, $path, DB_CACHED_EXPIRE, true);
			}
			
			return $path[$ncategory_id];			
		}
		
		private function getManufacturerPathByCollection($collection_id){
			$collection_id = (int)$collection_id;
			if ($collection_id < 1) return false;
			
			static $path = null;
			if (!isset($path)) {
				$path = $this->cache->get('collection.manufacturer.seopath');
				if (!isset($path)) $path = array();
			}
			
			if (!isset($path[$collection_id])) {
				$query = $this->db->query("SELECT manufacturer_id FROM collection WHERE collection_id = '" . $collection_id . "' LIMIT 1");
				
				$path[$collection_id] = $query->num_rows ? (int)$query->row['manufacturer_id'] : false;
				
				$this->cache->set('collection.manufacturer.seopath', $path);
			}
			
			return $path[$collection_id];			
		}
		
		private function getPathByCollection($collection_id){
			$collection_id = (int)$collection_id;
			if ($collection_id < 1) return false;
			
			static $path = null;
			if (!is_array($path)) {
				$path = $this->cache->get('collection.seopath'.$this->language_id);
				if (!is_array($path)) $path = array();
			}
			
			if (!isset($path[$collection_id])) {
				$query = $this->db->query("SELECT manufacturer_id FROM collection WHERE collection_id = '" . $collection_id . "' LIMIT 1");
				
				$path[$collection_id] = $query->num_rows ? (int)$query->row['manufacturer_id'] : false;
				
				$this->cache->set('collection.seopath'.$this->language_id, $path, DB_CACHED_EXPIRE, true);
			}
			
			return $path[$collection_id];
		}
		
		private function checkIfCategoryCanHaveIntersections($category_id){
			$category_id = (int)$category_id;
			if ($category_id < 1) return false;
						
			$query = $this->db->query("SELECT intersections FROM category WHERE category_id = '" . (int)$category_id . "'");
			
			if ($query->num_rows){
				return (bool)$query->row['intersections'];
			}
			
			return false;
		}
		
		private function getPathByCategory($category_id) {
			$category_id = (int)$category_id;
			if ($category_id < 1) return false;
			
			static $path = null;
			if (!is_array($path)) {
				$path = $this->cache->get('category.seopath'.$this->language_id);
				if (!is_array($path)) $path = array();
			}
			
			if (!isset($path[$category_id])) {
				$max_level = 3;
				
				$sql = "SELECT CONCAT_WS('_'";
				for ($i = $max_level-1; $i >= 0; --$i) {
					$sql .= ",t$i.category_id";
				}
				$sql .= ") AS path FROM category t0";
				for ($i = 1; $i < $max_level; ++$i) {
					$sql .= " LEFT JOIN category t$i ON (t$i.category_id = t" . ($i-1) . ".parent_id)";
				}
				$sql .= " WHERE t0.category_id = '" . $category_id . "'";
				
				$query = $this->db->query($sql);
				
				$path[$category_id] = $query->num_rows ? $query->row['path'] : false;
				
				$this->cache->set('category.seopath'.$this->language_id, $path, DB_CACHED_EXPIRE, true);
			}
			
			return $path[$category_id];
		}
		
		private function validate() {
			if( isset( $this->request->get['route'] ) && strpos( $this->request->get['route'], 'module/mega_filter' ) !== false ) {
				return;
			}
			
			if (isset($this->request->get['route']) && in_array($this->request->get['route'], $this->doNotCheckRoutes)) {
				return;
			}
			
			foreach ($this->doNotCheckRoutesPartly as $nonCheckedRoute){
				if (isset($this->request->get['route']) && strpos($this->request->get['route'], $nonCheckedRoute) !== false) {
					return;
				}
			}
			
			if(empty($this->request->get['route'])) {
				$this->request->get['route'] = 'common/home';
			}
			
			if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				return;
			}
						
			if (is_array($this->request->get) && isset($this->request->get['page']) && (int)$this->request->get['page'] == 1){
				unset($this->request->get['page']);			
			}

			$config_ssl = substr($this->config->get('config_ssl'), 0, strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);																
			$url 		= str_replace('&amp;', '&', $config_ssl . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo 		= str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), 'SSL'));
					
			if ((php_sapi_name()!=="cli") && (rawurldecode($url) != rawurldecode($seo)) && strpos($url,'mfp=') === false && (strpos($url, 'search') === false)) {
				header('X-REDIRECT: SeoProLib::validate');		 
				header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
				$this->response->redirect($seo, 301);
			}
		}
						
		private function getQueryString($exclude = array('hello', 'discount_debug', 'mfp')) {
			if (!is_array($exclude)) {
				$exclude = array();
			}
			
			return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
		}
	}																																												
<?php
	class ControllerCommonSeoUrl extends Controller {
		
		private $cache_data = null;
		
		public function __construct($registry) {
			parent::__construct($registry);
			
			$this->cache_data = $this->cache->get('seo_url.structure.'.(int)$this->config->get('config_language_id'));
			if (!$this->cache_data) {
				$query = $this->db->query("SELECT DISTINCT LOWER(`keyword`) as 'keyword', `query` FROM url_alias WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' OR language_id = '-1'");
				$this->cache_data = array();
				foreach ($query->rows as $row) {
					$this->cache_data['keywords'][$row['keyword']] = $row['query'];
					$this->cache_data['queries'][$row['query']] = $row['keyword'];
				}
				$this->cache->set('seo_url.structure.'.(int)$this->config->get('config_language_id'), $this->cache_data);
			}       
			
		}
		
		public function index() {
			
			//=========== OpenCart Shortcodes
			
			// Shortcodes
			$shortcodes = new Shortcodes($this->registry);
			$this->registry->set('shortcodes', $shortcodes);
			
			//=== Default shortcodes
			$this->load->helper('shortcodes_default');
			
			$class         = new ShortcodesDefault($this->registry);
			$scDefaults    = get_class_methods($class);
			foreach ($scDefaults as $shortcode) {
				$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
			}
			
			//=== Extensions shortcodes : for extensions developer
			$files = glob(DIR_APPLICATION . '/view/shortcodes/*.php');
			if ($files) {
				foreach ($files as $file) {
					require_once(VQMod::modCheck($file));
					
					$file       = basename($file, ".php");
					$extClass   = 'Shortcodes' . preg_replace('/[^a-zA-Z0-9]/', '', $file);
					
					$class      = new $extClass($this->registry);
					$scExtensions = get_class_methods($class);
					foreach ($scExtensions as $shortcode) {
						$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
					}
				}
			}
			
			//=== Themes shortcodes : for theme developer
			$file = DIR_TEMPLATE . $this->config->get('config_template') . '/shortcodes_theme.php';
			if (file_exists($file)) {
				require_once(VQMod::modCheck($file));
				
				$class         = new ShortcodesTheme($this->registry);
				$scThemes      = get_class_methods($class);
				foreach ($scThemes as $shortcode) {
					$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
				}
			}
			
			//=== Custom shortcodes : user power!
			$file = DIR_TEMPLATE . $this->config->get('config_template') . '/shortcodes_custom.php';
			if (file_exists($file)) {
				require_once(VQMod::modCheck($file));
				
				$class         = new ShortcodesCustom($this->registry);
				$scCustom      = get_class_methods($class);
				foreach ($scCustom as $shortcode) {
					$this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
				}
			}
			
			//=========== END :: OpenCart Shortcodes
			
			
			// Add rewrite to url class
			if ($this->config->get('config_seo_url')) {
				$this->url->addRewrite($this);
			}
			
			// Decode URL
			if (isset($this->request->get['_route_'])) {
				$parts = explode('/', $this->request->get['_route_']);
				
				foreach ($parts as $part) {
					
					if (isset($this->cache_data['keywords'][$part])) {
						$url = explode('=', $this->cache_data['keywords'][$part]);
						
						if ($url[0] == 'product_id') {
							$this->request->get['product_id'] = $url[1];
						}
						
						if ($url[0] == 'actions_id') {
							$this->request->get['actions_id'] = $url[1];
						}
						
						/* custom article urls */
						if ($url[0] == 'news_id') {
							$this->request->get['news_id'] = $url[1];
						}
						
						if ($url[0] == 'nauthor_id') {
							$this->request->get['author'] = $url[1];
						}
						
						if ($url[0] == 'ncategory_id') {
							if (!isset($this->request->get['ncat'])) {
								$this->request->get['ncat'] = $url[1];
								} else {
								$this->request->get['ncat'] .= '_' . $url[1];
							}
						}
						/* end of custom article urls */
						
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
								} else {
								$this->request->get['path'] .= '_' . $url[1];
							}
						}	
						
						if ($url[0] == 'collection_id') {
							$this->request->get['collection_id'] = $url[1];
						}
						
						if ($url[0] == 'manufacturer_id') {
							$this->request->get['manufacturer_id'] = $url[1];
						}
						
						
						if ($url[0] == 'information_id') {
							$this->request->get['information_id'] = $url[1];
						}
						
						if ($url[0] == 'landingpage_id') {
							$this->request->get['landingpage_id'] = $url[1];
						}
						
						if ($url[0] == 'information_attribute_id') {
							$this->request->get['information_attribute_id'] = $url[1];
						}
						
						if ($url[0] == 'album_id') {
							$this->request->get['album_id'] = $url[1];
						}
						} else {
						$this->request->get['route'] = 'error/not_found';
						return $this->forward($this->request->get['route']);
					}
				}
				
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
					} elseif (isset($this->request->get['path'])) {
					if( isset( $this->request->get['_route_'] ) ) {
						if( strpos( $this->request->get['_route_'], 'module/mega_filter' ) === false ) {
							$this->request->get['route'] = 'product/category';
							} else {
							$this->request->get['route'] = $this->request->get['_route_'];
						}
						} else if( isset( $this->request->get['route'] ) ) {
						if( strpos( $this->request->get['route'], 'module/mega_filter' ) === false ) {
							$this->request->get['route'] = 'product/category';
						}
						} else {
						$this->request->get['route'] = 'product/category';
					}
					} elseif (isset($this->request->get['collection_id'])) {
					$this->request->get['route'] = 'product/collection';
					} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
					} elseif (isset($this->request->get['news_id'])) {
					$this->request->get['route'] = 'news/article';
					} elseif (isset($this->request->get['ncat']) || isset($this->request->get['author'])) {
					$this->request->get['route'] = 'news/ncategory';
					} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
					} elseif (isset($this->request->get['landingpage_id'])) {
					$this->request->get['route'] = 'information/landingpage';
					} elseif (isset($this->request->get['information_attribute_id'])) {
					$this->request->get['route'] = 'information/information_attribute';
					} elseif (isset($this->request->get['actions_id'])) {
					$this->request->get['route'] = 'information/actions';
					} elseif (isset($this->request->get['album_id'])) {
					$this->request->get['route'] = 'gallery/photos';			
					} else {
					
					if (isset($this->cache_data['keywords'][$this->request->get['_route_']])) {
						$this->request->get['route'] = $this->cache_data['keywords'][$this->request->get['_route_']];
					}
				}
				
				if (isset($this->request->get['route'])) {
					return $this->forward($this->request->get['route']);
				}
			}
		}
		
		public function rewrite($link) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			$url = ''; 
			
			$data = array();
			
			parse_str($url_info['query'], $data);
			
			foreach ($data as $key => $value) {
				
				if ($key == 'actions_id'){
					$data['route'] = 'information/actions';				
					} elseif ($key == 'news_id'){
					$data['route'] = 'news/article';						
					} elseif ($key == 'ncat'){
					$data['route'] = 'news/ncategory';				
				}
				
				if (isset($data['route'])) {
					
					//Товары, производитель, коллекция, информация, новости			
					if (
					($data['route'] == 'product/product' && $key == 'product_id')	
					|| (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id')
					|| ($data['route'] == 'product/collection' || $data['route'] == 'product/product' && $key == 'collection_id') 					
					|| ($data['route'] == 'information/information' && $key == 'information_id')
					|| ($data['route'] == 'information/landingpage' && $key == 'landingpage_id')					
					|| ($data['route'] == 'information/information_attribute' && $key == 'information_attribute_id')
					|| ($data['route'] == 'product/category' && $key == 'manufacturer_id')
					) {
						$query = $key . '=' . (int)$value;				
						
						if ($data['route'] == 'information/landingpage'){
							var_dump($query);
						}
						
						if (isset($this->cache_data['queries'][$query])) {
							
							
							$url .= '/' . $this->cache_data['queries'][$query];						
							unset($data[$key]);
						}
						//АКЦИИ		
						} elseif ($data['route'] == 'information/actions' && $key == 'actions_id' && !empty($value)){					
						$query = $key . '=' . (int)$value;							
						if (isset($this->cache_data['queries'][$query])) {
							$url .= '/' .  $this->cache_data['queries'][$query];						
							unset($data[$key]);
						}					
						}  elseif ($data['route'] == 'information/actions') {					
						if (isset($this->cache_data['queries']['information/actions'])) {
							$url .= '/' . $this->cache_data['queries']['information/actions'];
							unset($data[$key]);
						}
						//Галерея - альбомы
						} elseif (($data['route'] == 'gallery/photos' && $key == 'album_id' && !empty($value))) {
						
						if (isset($this->cache_data['queries']['gallery/gallery'])) {
							$url .= '/' . $this->cache_data['queries']['gallery/gallery'];
						}
						
						$query = $key . '=' . (int)$value;			
						if (isset($this->cache_data['queries'][$query])) {
							$url .= '/' . $this->cache_data['queries'][$query];
							unset($data[$key]);
						}
						//Галерея - исключительно ссылка на страницу галерей
						} elseif ($data['route'] == 'gallery/gallery') {
						if (isset($this->cache_data['queries']['gallery/gallery'])) {
							$url .= '/' . $this->cache_data['queries']['gallery/gallery'];
							unset($data[$key]);
						}
						//Пасвей к статье с категориями
						} elseif ($data['route'] == 'news/article' && $key == 'path' && !empty($value)) {							
						$query = $key . '=' . (int)$value;							
						if (isset($this->cache_data['queries'][$query])) {
							$url .= '/' .  $this->cache_data['queries'][$query];	
							unset($data[$key]);
						}
						//Ссылка на страницу отдельной статьи					
						} elseif ($data['route'] == 'news/article' && $key == 'news_id' && !empty($value)) {							
						$query = $key . '=' . (int)$value;			
						if (isset($this->cache_data['queries'][$query])) {
							$url .= '/' . $this->cache_data['queries'][$query];
							unset($data[$key]);
						}
						//ссылка на страницу всех статей
						} elseif ($data['route'] == 'news/article') {				
						if (isset($this->cache_data['queries']['news/ncategory'])) {
							$url .= '/' . $this->cache_data['queries']['news/ncategory'];
							unset($data[$key]);
						}
						//Ссылка на страницу отдельного автора
						} elseif ($data['route'] == 'news/ncategory' && $key == 'author') {
						if (isset($this->cache_data['queries']['news/ncategory'])) {
							$url .= '/' . isset($this->cache_data['queries']['news/ncategory']);
						}
						$realkey = "nauthor_id";
						$query = $realkey . '=' . (int)$value;
						if (isset($this->cache_data['queries'][$query])) {
							$url .= '/' . $this->cache_data['queries'][$query];
							unset($data[$key]);
						}
						//Ссылка на категории блога
						} elseif ($data['route'] == 'news/ncategory' && $key == 'ncat') {					
						$ncategories = explode('_', $value);						
						foreach ($ncategories as $ncategory) {
							$realkey = "ncategory_id";
							$query = $realkey . '=' . (int)$ncategory;
							if (isset($this->cache_data['queries'][$query])) {
								$url .= '/' . $this->cache_data['queries'][$query];						
							}
						}
						unset($data[$key]);
						//Ссылка на просто блог
					} elseif (
					(isset($data['route']) && $data['route'] == 'news/ncategory' && $key != 'ncat' && $key != 'author' && $key != 'page') 
					) { 
						if (isset($this->cache_data['queries']['news/ncategory'])) {
							$url .= '/' . $this->cache_data['queries']['news/ncategory'];
							unset($data[$key]);
						}
						
						//Обычные категории
						} elseif ($key == 'path') {
						$categories = explode('_', $value);
						
						foreach ($categories as $category) {
							$query = 'category_id=' . (int)$category;			
							if (isset($this->cache_data['queries'][$query])) {
								$url .= '/' . $this->cache_data['queries'][$query];
							}							
						}
						
						unset($data[$key]);
						
						} else {
						$query = $data['route'];			
						if (isset($this->cache_data['queries'][$query])) {
							$url .= '/' . $this->cache_data['queries'][$query];
                            unset($data[$key]);
						}
					}
				}
			}		
			
			if ($url) {
				unset($data['route']);
				
				$query = '';
				
				if ($data) {
					foreach ($data as $key => $value) {
						
						if( is_array( $value ) ) continue;
					
						$query .= '&' . $key . '=' . $value;
					}
					
					if ($query) {
						$query = '?' . trim($query, '&');
					}
				}
				
				if (IS_HTTPS){
					$url_info['scheme'] = 'https';
					} else {
					$url_info['scheme'] = 'http';
				}
				
				return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
				} else {
				return $link;
			}
		}	
	}
?>
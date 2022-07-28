<?php   
	class ControllerCommonHeader extends Controller {
		private $top_block_id = 'top_covid3';
		
		
		public function setTopBlock(){
			if (isset($this->request->get[$this->top_block_id])){
				$this->session->data[$this->top_block_id] = '1';
			}	
		}
				
		
		protected function simpleheader(){								
			$this->index('header_simple');			
		}				

		private function setHrefLangsAndTryToRedirect(){
			$this->load->model('kp/urldecode');
			$hreflangs = $this->model_kp_urldecode->decodeURI();			
			$this->document->setHrefLangs($hreflangs);

			if ($this->registry->get('perform_redirect_to_second_language')){
				header('X-UA-REDIRECT: Slava Ukraini!');

				foreach ($hreflangs as $hreflang){
					if ($hreflang['code'] == $this->config->get('config_second_language')){
						header('Location: ' . str_replace('&amp;', '&', $hreflang['link']), true, 301);
					}
				}
			}
		}
		
		protected function index($template_overload = false) {
			$this->setHrefLangsAndTryToRedirect();
					
			if (IS_HTTPS) {
				$this->data['static_domain_url'] = $this->config->get('config_img_ssl');
				} else {
				$this->data['static_domain_url'] = $this->config->get('config_img_url');
			}
			
			
			$this->load->model('localisation/language');
			
			//top block logic
			$this->data['top_block_id'] = $this->top_block_id;
			
			$show_top_block = true;							
			if ($_SERVER['REQUEST_URI'] == '/'  || !isset($this->request->get['route']) || $this->request->get['route'] == 'common/home'){
				$show_top_block = true;
			}
			
			$show_top_block = ($this->config->get('config_regional_currency') == 'RUB');
			
			if (isset($this->session->data[$this->top_block_id])){
				$show_top_block = false;
			}
			
			if (isset($_SERVER['GEOIP_COUNTRY_CODE']) && $_SERVER['GEOIP_COUNTRY_CODE'] == 'DE'){
				$show_top_block = false;
			}
			
			// Notify bar
			$notifies = $this->config->get('notify_bar');
			
			if ($notifies && count($notifies) > 1) {
				usort($notifies, function($a,$b){return $a['sort_order'] <=> $b['sort_order'];});
				
				$this->data['notifies'] = [];
				foreach ($notifies as $notify){
					
					if (!empty($notify['status']) && $notify['status'] == 'on' && !empty($notify['notify_bar_description'][$this->config->get('config_language_id')]['main_text'])){
						
						$this->data['notifies'][] = array(
						'svg' 				=> html_entity_decode($notify['svg']),
						'text_near_svg'	  	=> $notify['notify_bar_description'][$this->config->get('config_language_id')]['text_near_svg'],
						'main_text'	  		=> $notify['notify_bar_description'][$this->config->get('config_language_id')]['main_text'],
						'link'	  			=> $notify['notify_bar_description'][$this->config->get('config_language_id')]['link'],
						'link_text'	  		=> $notify['notify_bar_description'][$this->config->get('config_language_id')]['link_text'],
						);
						
					}
				}
				} else {
				$this->data['notifies'] = false;
			}
			// Notify bar
			
			//	$show_top_block = false;
			
			$this->data['show_top_block'] = $show_top_block;
			
			$opt_group_array = array(8,9,10,11);
			
			//preauth
			$this->load->model('account/preauth');
			//	if (!$this->customer->isLogged()){				
			if ($email = $this->model_account_preauth->CheckPreauth()){	
				$this->customer->login($email, '', true);					
			}			
			//}
			
			
			
			//Количество просмотров ПОЛНЫХ СТРАНИЦ
			if (!isset($this->session->data['pages_viewed'])){
				$this->session->data['pages_viewed'] = 1;	
				} else {
				$this->session->data['pages_viewed']++;
			}
			
			$this->data['title'] = $this->document->getTitle();
			$this->data['config_title'] = $this->config->get('config_title');
			
			$routes = $this->config->get('metaseo_anypage_routes');
			if (is_array($routes)) {
				foreach ($routes as $route) {
					if ($route['route'] == 'common/home') {
						$language_id = $this->config->get('config_language_id');
						if (isset($route['title'][$language_id]) && $route['title'][$language_id]) {
							$this->data['config_title'] = $route['title'][$language_id];
						}
						break;
					}
				}
			}			
			
			$server = $this->config->get('config_ssl');

			if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
				$this->data['error'] = $this->session->data['error'];
				unset($this->session->data['error']);
				} else {
				$this->data['error'] = '';
			}
						
			$this->data['base'] = $server;
			$this->data['description'] = $this->document->getDescription();
			$this->data['keywords'] = $this->document->getKeywords();
			$this->data['opengraphs'] = $this->document->getOpenGraphs();		
			$this->data['styles'] = $this->document->getStyles();
			$this->data['lang'] = $this->config->get('config_language_hreflang')?$this->config->get('config_language_hreflang'):$this->language->get('code');
			
			$this->data['preload_links'] = array();
			$preload_links = explode(PHP_EOL, $this->config->get('config_preload_links'));
			
			foreach ($preload_links as $__link){
				
				
				$__link = trim(str_replace(PHP_EOL, '', $__link));
				if (mb_strlen($__link) > 10){
					$this->data['preload_links'][] = $__link;
				}
				
			}
			
			if ($this->data['lang'] == 'ru-KZ'){
				$this->data['lang'] = 'ru';
			}
			
			$this->data['pwaInstallKey'] = $this->session->data['pwaInstallKey'] = md5('pwainstall' . $this->request->server['REMOTE_ADDR'] . date('d') . $this->config->get('config_encryption'));
			$this->data['pwaSessionKey'] = $this->session->data['pwaSessionKey'] = md5('pwasession' . $this->request->server['REMOTE_ADDR'] . date('d') . $this->config->get('config_encryption'));
			
			$this->data['spsroute'] = $this->url->link('kp/pwa/sps', 'pwaSessionKey=' . $this->data['pwaSessionKey']);
			$this->data['spiroute'] = $this->url->link('kp/pwa/spi', 'pwaInstallKey=' . $this->data['pwaInstallKey']);
			
			$this->data['pwasession'] = $this->customer->getPWASession();			
			
			$this->data['direction'] = $this->language->get('direction');
			$this->data['name'] = $this->config->get('config_name');
			$this->data['o_email'] = $this->config->get('config_display_email');			
			$worktime =  explode(';', $this->config->get('config_worktime'));
			
			if ($this->customer->isLogged() && $this->customer->isOpt()){
				$this->data['worktime'] = isset($worktime[1])?$worktime[1]:$worktime[0];
				
				//первый телефон
				$this->data['phone'] = $this->config->get('config_opt_telephone');
				$this->data['phone2'] = $this->config->get('config_opt_telephone2');
				
				} else {
				$this->data['worktime'] = $worktime[0];
				$this->data['phone'] = $this->config->get('config_telephone');
				$this->data['phone2'] = $this->config->get('config_telephone2');
				$this->data['phone3'] = $this->config->get('config_telephone3');
			}
			
			$this->data['t_bt'] = $this->config->get('config_t_bt');
			$this->data['t2_bt'] = $this->config->get('config_t2_bt');
			
			$this->data['social_auth'] = $this->config->get('config_social_auth');
			
			$this->data['preconnect_domains'] = array();
			if ($this->config->get('config_static_subdomain')){
				$this->data['preconnect_domains'][] = rtrim($this->config->get('config_static_subdomain'), '/');
			}
			
			if ($this->config->get('config_img_ssl')){
				$this->data['preconnect_domains'][] = rtrim($this->config->get('config_img_ssl'), '/');
			}
			
			if ($this->config->get('config_img_ssls') && ($this->config->get('config_img_server_count') || $this->config->get('config_img_server_count') === '0')){
				for ($z = 0; $z<=$this->config->get('config_img_server_count'); $z++){
					$this->data['preconnect_domains'][] = str_replace('{N}', $z, $this->config->get('config_img_ssls'));
				}
			}
			
			//GEOLOCATION
			$this->data['customer_city'] = false;
			$this->load->model('tool/simpleapicustom');
			$this->data['customer_city'] = $this->model_tool_simpleapicustom->getAndCheckCurrentCity();
			
			//MINIFICATION ENGINE W/STATIC
			require_once DIR_SYSTEM . '../min/static/lib.php';
			$static_uri = "/min/static";
			
			/*---------------- STYLES -------------*/			
			$general_css = prepareEOLArray($this->config->get('config_header_min_styles'));
			
			$query = "f=" . implode(',', $general_css);
			$this->data['general_minified_css_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'css');
			
			if ($this->config->get('config_static_subdomain') && $this->data['general_minified_css_uri']){
				$this->data['general_minified_css_uri'] = ltrim($this->data['general_minified_css_uri'], '/');
				
				if (file_exists(DIR_SITE . $this->data['general_minified_css_uri'])){
					$this->data['general_minified_css_uri'] = trim($this->config->get('config_static_subdomain')) . $this->data['general_minified_css_uri'];
				}
			}
			
			$t = array();
			if ($this->data['styles']){							
				
				foreach ($this->data['styles'] as $style) { $t[] = $style['href']; }				
				$query = "f=" . implode(',', $t);
				
				$this->data['added_minified_css_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'css');
				} else {
				$this->data['added_minified_css_uri'] = false;
			}
			
			if ($this->config->get('config_static_subdomain') && $this->data['added_minified_css_uri']){
				$this->data['added_minified_css_uri'] = ltrim($this->data['added_minified_css_uri'], '/');
				
				if (file_exists(DIR_SITE . $this->data['added_minified_css_uri'])){
					$this->data['added_minified_css_uri'] = trim($this->config->get('config_static_subdomain')) . $this->data['added_minified_css_uri'];
				}
			}
			
			/*---------------- END STYLES -------------*/
			
			
			//BEGIN SCRIPTS			
			$general_js = prepareEOLArray($this->config->get('config_header_min_scripts'));
			
			if ($general_js) {
				$query = "f=" . implode(',', $general_js);
				$this->data['general_minified_js_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'js');
			}
			
			if ($this->config->get('config_static_subdomain') && $this->data['general_minified_js_uri']){
				$this->data['general_minified_js_uri'] = ltrim($this->data['general_minified_js_uri'], '/');
				
				if (file_exists(DIR_SITE . $this->data['general_minified_js_uri'])){
					$this->data['general_minified_js_uri'] = trim($this->config->get('config_static_subdomain')) . $this->data['general_minified_js_uri'];
				}
			}
			
			$this->data['excluded_scripts'] = prepareEOLArray($this->config->get('config_header_excluded_scripts'));
			
			/* !!!!!!!!!! */
			$this->data['incompatible_scripts'] = array(				
			);
			
			$t = array();
			$this->data['added_but_excluded_scripts'] = array();
			foreach (array_unique($this->document->getScripts()) as $script) {
				if (!in_array($script, $this->data['incompatible_scripts']) && !in_array($script, $this->data['excluded_scripts'])) {
					$t[] = $script;			
				}
				
				if (in_array($script, $this->data['excluded_scripts'])){
					$this->data['added_but_excluded_scripts'][] = $script;
				}
			}
			if ($t){
				$query = "f=" . implode(',', $t);
				$this->data['added_minified_js_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'js');
				} else {
				$this->data['added_minified_js_uri'] = false;
			}
			
			if ($this->config->get('config_static_subdomain') && $this->data['added_minified_js_uri']){
				$this->data['added_minified_js_uri'] = ltrim($this->data['added_minified_js_uri'], '/');
				
				if (file_exists(DIR_SITE . $this->data['added_minified_js_uri'])){
					$this->data['added_minified_js_uri'] = trim($this->config->get('config_static_subdomain')) . $this->data['added_minified_js_uri'];
				}
			}
			
			/*---------------- END SCRIPTS -------------*/
			//MINIFICATION ENGINE W/STATIC END
			
			//Заглушка для..
			$this->data['scripts'] = $this->document->getScripts();
			
			//END SCRIPTS		
			$this->data['noindex'] = $this->document->isNoindex();
			if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
				$this->data['icon'] = $server . DIR_IMAGE_NAME . $this->config->get('config_icon');
				} else {
				$this->data['icon'] = '';
			}									
			
			foreach ($this->language->loadRetranslate('common/header') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . DIR_IMAGE_NAME . $this->config->get('config_logo');
				} else {
				$this->data['logo'] = '';
			}		
			
			$this->data['logo_alt_title'] = $this->data['logo_alt_title_' . $this->config->get('config_country_id')];
			
			//SVG бонусов
			if ($this->config->get('config_reward_logosvg')){
				$this->data['points_svg'] = trim($this->config->get('config_static_subdomain')) . 'catalog/view/theme/' . $this->config->get('config_template') . '/img/' . $this->config->get('config_reward_logosvg');
			}
			
			
			$this->language->load('common/header');
			$this->data['store_url'] = HTTPS_SERVER;
			
			$this->data['text_home'] = $this->language->get('text_home');
			$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');				
			$this->data['text_account'] = $this->language->get('text_account');
			$this->data['text_checkout'] = $this->language->get('text_checkout');
			$this->data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
			$this->data['text_catalog'] = $this->language->get('catalog');
			$this->data['text_show_count_empty'] = $this->language->get('show_count_empty');
			$this->data['text_all_show_history'] = $this->language->get('all_show_history');
			$this->data['text_show_history'] = $this->language->get('show_history');
			$this->data['text_product'] = $this->language->get('product');
			$this->data['text_brand'] = $this->language->get('brand');
			$this->data['text_collections'] = $this->language->get('collections');
			$this->data['text_material'] = $this->language->get('material');
			$this->data['text_vendor_code'] = $this->language->get('vendor_code');
			$this->data['text_information'] = $this->language->get('information');
			$this->data['text_actions'] = $this->language->get('actions');
			$this->data['text_new_products'] = $this->language->get('new_products');
			$this->data['text_my_card'] = $this->language->get('my_card');
			$this->data['text_no_discount_card'] = $this->language->get('no_discount_card');
			$this->data['text_how_get'] = $this->language->get('how_get');
			$this->data['text_login_b2b'] = $this->language->get('login_b2b');
			$this->data['home'] = $this->url->link('common/home');
			
			$this->data['logged'] = $this->customer->isLogged();
			$this->data['account'] = $this->url->link('account/account', '', 'SSL');
			$this->data['shopping_cart'] = $this->url->link('checkout/cart');
			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
			$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
			
			$this->data['all_actions'] = $this->url->link('information/actions', '', 'SSL');
			$this->data['product_new'] = $this->url->link('product/product_new', '', 'SSL');
			$this->data['contacts'] = $this->url->link('information/contact', '', 'SSL');
			$this->data['feedback'] = $this->url->link('information/feedback', '', 'SSL');
			
			$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
			$this->data['text_logged'] = '';
			
			$this->data['loginb2b'] = $this->url->link('account/simpleregisterb2b', '', 'SSL');
			
			$this->data['compare'] = $this->url->link('product/compare', '', 'SSL');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_count_compare'] = $this->language->get('text_count_compare');
			
			$this->data['link_logout'] = $this->url->link('account/logout', '', 'SSL');
			$this->data['text_logout'] = $this->language->get('text_logout');
			
			$this->data['text_search'] = $this->language->get('text_search');
			$this->data['text_under_search'] = $this->language->get('text_under_search');
			$this->data['search_like'] = 'Кофеварка';
			
			// Daniel's robot detector
			$status = true;
			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$robots = explode("\n", trim($this->config->get('config_robots')));
				foreach ($robots as $robot) {
					if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
						$status = false;
						break;
					}
				}
			}
			// A dirty hack to try to set a cookie for the multi-store feature
			$this->load->model('setting/store');
			$this->data['stores'] = array();
			if ($this->config->get('config_shared') && $status) {
				$this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
				$stores = $this->model_setting_store->getStores();
				foreach ($stores as $store) {
					$this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
				}
			}
			// Search		
			if (isset($this->request->get['search'])) {
				$this->data['search'] = $this->request->get['search'];
				} else {
				$this->data['search'] = '';
			}
			
			
			$this->data['text_special'] = $this->language->get('text_special');	
			$this->data['special'] = $this->url->link('product/special');
			
			
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['href_manufacturer'] = $this->url->link('product/manufacturer');	
			$this->data['href_stock_1_tovary_s_ekspress_dostavkoj'] = $this->url->link('product/category', 'path=8307');
			$this->data['href_stock_2_ekspress_podarki']= $this->url->link('product/category', 'path=6475');
			$this->data['href_stock_3_podarki'] 		= $this->url->link('product/category', 'path=8228');
			$this->data['href_newproducts'] = $this->url->link('product/product_new');
			
			//$this->data['href_sale'] = $this->url->link('product/special');
			$this->data['href_sale'] = $this->url->link('product/category', 'path=6614');
			
			$this->data['href_newyear'] = $this->url->link('product/category', 'path=8227');
			
			$this->data['href_actions'] = $this->url->link('information/actions');
			$this->data['href_blog']	= $this->url->link('news/ncategory');
			
			$this->data['href_how_order'] = $this->url->link('information/information', 'information_id=29');
			$this->data['href_delivery'] = $this->url->link('information/information', 'information_id=31');
			$this->data['href_payment'] = $this->url->link('information/information', 'information_id=30');
			$this->data['href_return'] = $this->url->link('information/information', 'information_id=33');
			$this->data['href_track'] = $this->url->link('account/tracker');
			
			$this->data['href_discounts'] = $this->url->link('information/information', 'information_id=32');
			$this->data['href_present_sertificate'] = $this->url->link('information/information', 'information_id=35');
			
			$this->data['href_about'] = $this->url->link('information/information', 'information_id=4');
			$this->data['href_faq'] = $this->url->link('information/faq_system');
			$this->data['href_contact'] = $this->url->link('information/contact');
			$this->data['href_shop_rating'] = $this->url->link('information/shop_rating');
			$this->data['href_sitemap'] = $this->url->link('information/sitemap');
			
			$this->data['href_wmf']	= $this->url->link('product/manufacturer/info', 'manufacturer_id=202');
			$this->data['href_vb']	= $this->url->link('product/manufacturer/info', 'manufacturer_id=201');
			
			$this->data['google_analytics_header'] = html_entity_decode($this->config->get('config_google_analytics_header'), ENT_QUOTES, 'UTF-8');
			$this->data['config_gtm_header'] = html_entity_decode($this->config->get('config_gtm_header'), ENT_QUOTES, 'UTF-8');
			$this->data['config_gtm_body'] = html_entity_decode($this->config->get('config_gtm_body'), ENT_QUOTES, 'UTF-8');

			$this->data['config_fb_pixel_header'] = html_entity_decode($this->config->get('config_fb_pixel_header'), ENT_QUOTES, 'UTF-8');
			$this->data['config_fb_pixel_body'] = html_entity_decode($this->config->get('config_fb_pixel_body'), ENT_QUOTES, 'UTF-8');

			$this->data['config_vk_pixel_header'] = html_entity_decode($this->config->get('config_vk_pixel_header'), ENT_QUOTES, 'UTF-8');
			$this->data['config_vk_pixel_body'] = html_entity_decode($this->config->get('config_vk_pixel_body'), ENT_QUOTES, 'UTF-8');
			$this->data['config_vk_pixel_id'] = $this->config->get('config_vk_pixel_id');
			$this->data['config_vk_enable_pixel'] = $this->config->get('config_vk_enable_pixel');

			
			$this->data['google_ecommerce_enable'] = (int)$this->config->get('config_google_ecommerce_enable');
			
			//Language Switcher
			$this->data['language_switcher'] = array();
			if ($this->config->get('config_second_language')){
				foreach (($hreflangs = $this->document->getHrefLangs()) as $language_id => $link){
					if (in_array($link['code'], $this->config->get('config_supported_languages'))){						

						$text_code = $this->registry->get('languages')[$link['code']]['switch'];
						
						$this->data['language_switcher'][] = array(
						'code' 		=> $link['code'],
						'text_code' => $text_code,
						'href' 		=> $link['link'],
						'active' 	=> ($language_id == $this->config->get('config_language_id'))
						);						

						$this->data['language_switcher'] = array_reverse($this->data['language_switcher']);
					}
				}
			}
			
			if (isset($this->request->get['route'])) {
				$route = (string)$this->request->get['route'];
				} else {
				$route = 'common/home';
			}
			
			
			$this->data['links'] 		= $this->document->getLinks();	
			$this->data['extra_tags'] 	= $this->document->getExtraTags();					
			$this->data['robots'] 		= $this->document->getRobots();
			
			if (ADMIN_SESSION_DETECTED){
				if (!empty($this->request->cookie['PHPSESSIDA'])){
					if (defined('DB_SESSION_HOSTNAME') && class_exists('Hobotix\SessionHandler\SessionHandler')){
						$handler = new \Hobotix\SessionHandler\SessionHandler();
						$handler->setDbDetails(DB_SESSION_HOSTNAME, DB_SESSION_USERNAME, DB_SESSION_PASSWORD, DB_SESSION_DATABASE);
						$handler->setDbTable(DB_SESSION_TABLE);

						if ($adminSessionData = $handler->read($this->request->cookie['PHPSESSIDA'])){
							$adminSessionData = \Hobotix\SessionHandler\SessionHandler::unserialize($adminSessionData);

							if ($adminSessionData){
								$this->load->model('user/user');
								$this->data['admin_user_id'] 	= !empty($adminSessionData['user_id'])?$adminSessionData['user_id']:false; 
								$this->data['admin_token']  	= !empty($adminSessionData['token'])?$adminSessionData['token']:false;
						
								$this->data['user'] = $this->model_user_user->getUser($this->data['admin_user_id']);
							}
						}
					}
				}
			}
			
						
			$this->children = array(
				'module/language',
				'module/currency',
				'module/mmenu',
				'common/topcontent',
			);					
			
			
			$this->data['logged'] = $this->customer->isLogged();
			
			$this->load->model('design/layout');
			$layout_id = $this->model_design_layout->getLayout('common/header');				
			if ($template_overload) {
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/' . $template_overload . '.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/common/' . $template_overload . '.tpl';
					} else {
					$this->template = 'default/template/common/' . $template_overload . '.tpl';
				} 
				} elseif ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template)) {
					$this->template = $this->config->get('config_template') . '/template/' . $template;
					} else {
					$this->template = 'default/template/' . $template;
				}				
				} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
					} else {
					$this->template = 'default/template/common/header.tpl';
				}
			}
			
			$this->render();
		} 		
		
		public function customercity(){
			foreach ($this->language->loadRetranslate('common/header') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			//GEOLOCATION
			$this->data['customer_city'] = false;
			$this->load->model('tool/simpleapicustom');
			$this->data['customer_city'] = $this->model_tool_simpleapicustom->getAndCheckCurrentCity();
			
			$this->template = $this->config->get('config_template') . '/template/blocks/customer_city.tpl';
			
			
			$this->response->setOutput($this->render());
		}
		
		public function wishlistblock(){
			$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
			$this->data['count'] = (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0);
			$this->data['text_wishlist'] = $this->language->get('text_wishlist');
			
			$this->template = $this->config->get('config_template') . '/template/blocks/wishlist.tpl';
			
			
			$this->response->setOutput($this->render());
		}
		
		public function customermenu(){
			foreach ($this->language->loadRetranslate('common/header') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->language->load('common/header');
			
			$this->data['logged'] = $this->customer->isLogged();
			$this->data['account'] = $this->url->link('account/account', '', 'SSL');
			$this->data['shopping_cart'] = $this->url->link('checkout/cart');
			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
			
			$this->language->load('account/login');
			$this->data['text_forgotten'] = $this->language->get('text_forgotten');
			$this->data['text_register'] = $this->language->get('text_register');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_password'] = $this->language->get('entry_password');
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_login'] = $this->language->get('button_login');
			
			$this->load->model('account/customer');
			$this->data['action'] = $this->url->link('account/login', '', 'SSL');
			$this->data['register'] = $this->url->link('account/register', '', 'SSL');
			$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
			
			//МЕНЮ Аккаунта
			if ($this->customer->isLogged()){
				
				$this->data['user_name_short'] = explode(' ', trim($this->customer->getFirstName()))[0];
				$this->data['user_name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
				
				
				$this->language->load('module/account');
				$this->data['heading_title'] = $this->language->get('heading_title');
				$this->data['logged'] = $this->customer->isLogged();
			
				
				// Мой кабинет
				$this->data['text_account'] = $this->language->get('text_account');
				$this->data['account'] = $this->url->link('account/account', '', 'SSL');
				
				// Мои заказы
				$this->data['text_address'] = $this->language->get('text_address');
				$this->data['address'] = $this->url->link('account/address', '', 'SSL');
				
				// Мои заказы
				$this->data['text_order'] = $this->language->get('text_order');
				$this->data['order'] = $this->url->link('account/order', '', 'SSL');
				
				// Мои бонусы
				$this->data['text_reward'] = $this->language->get('text_reward');
				$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
				
				$this->data['text_transaction'] = $this->language->get('text_transaction');			
				$this->data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
				
				// Мои промокоды
				$this->data['text_coupons'] = $this->language->get('text_coupons');
				$this->data['coupons'] = $this->url->link('account/promocodes', '', 'SSL');
				
				// Просмотренные
				$this->data['text_viewed'] = $this->language->get('text_viewed');
				$this->data['viewed'] = $this->url->link('account/viewed');
				
				// Избранное
				$this->data['text_wishlist'] = $this->language->get('text_wishlist');
				$this->data['wishlist'] = $this->url->link('account/wishlist');
				
				// Сравнения
				$this->data['text_compare'] = $this->language->get('text_compare');
				$this->data['compare'] = $this->url->link('product/compare');
				
				// Мои отзывы
				$this->data['text_reviews'] = $this->language->get('text_reviews');
				$this->data['reviews'] = $this->url->link('account/reviews');
				
				// Поддержка
				$this->data['text_support'] = $this->language->get('text_support');
				$this->data['support'] = $this->url->link('account/support');
				
				// Персональные данные
				$this->data['text_edit'] = $this->language->get('text_edit');
				$this->data['text_address'] = $this->language->get('text_address');
				
				$this->data['edit'] = $this->url->link('account/edit', '', 'SSL');
				$this->data['password'] = $this->url->link('account/password', '', 'SSL');
				$this->data['address'] = $this->url->link('account/address', '', 'SSL');
				
				
				//Выход
				$this->data['text_logout'] = $this->language->get('text_logout');
				$this->data['logout'] = $this->url->link('account/logout', '', 'SSL');
				
				$this->data['points_active'] = $this->customer->getRewardPoints();
				$this->data['points_active_formatted'] = $this->currency->formatBonus($this->data['points_active']);
				$this->data['points_active_formatted_as_currency'] = $this->currency->format($this->data['points_active'], $this->config->get('config_regional_currency'), 1);
			}
			
			if (!empty($this->request->get['x']) && $this->request->get['x'] == 'm'){
				$this->template = $this->config->get('config_template') . '/template/blocks/customermenumobile.tpl';
				} else {
				$this->template = $this->config->get('config_template') . '/template/blocks/customermenu.tpl';
			}
			
			
			$this->response->setOutput($this->render());
			
			
			
			
			}
			
			
		}
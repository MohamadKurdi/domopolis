<?php
	class ControllerCommonFooter extends Controller {
		
		private function replaceMetaSeo($haystack) {
			$haystack = strip_tags($haystack);
			$pattern = '/\{(.+?)\}/';
			preg_match_all($pattern, $haystack, $matches);						
			if ($matches) {
				$array_from = array();
				$array_to = array();
				foreach ($matches[0] as $match) {
					$array_from[] = $match;
					if ($match == '{store_name}') {
						$array_to[] = $this->config->get('config_name');
						} elseif ($match == '{meta_title}') {
						$array_to[] = $this->document->getTitle();
						} elseif ($match == '{meta_description}') {
						$array_to[] = $this->document->getDescription();
						} elseif (preg_match('/^\{page/',$match)) {
						$page_text = explode('=',$match);
						if (isset($this->request->get['page']) && $this->request->get['page']>1){
							if (!empty($page_text[1])) {
								$array_to[] = str_replace('~page~',$this->request->get['page'],$page_text[1]);
								} else {
								$array_to[] = '';
							}
							} else {
							$array_to[] = '';
						}
						} else {
						$array_to[] = '';
					}
				}			
				$haystack = str_replace($array_from,$array_to,$haystack);
				$haystack = str_replace(array('}', '{'), '', $haystack);
			}
			return $haystack;
		}
		
		private function checkIfGETRouteOnly(){
			
			if (count($this->request->get) == 1 && !empty($this->request->get['route'])){
				return true;
			}
			
			foreach ($this->request->get as $get => $getvalue){
				if (mb_strpos($get, '_id')){
					return false;
				}			
			}
			
			return true;
		}
		
		protected function simplefooter(){			
			$this->index('common/footer_simple');			
		}
		
		
		protected function index($template_overload = false) {
			
			if (IS_HTTPS) {
				$this->data['static_domain_url'] = $this->config->get('config_img_ssl');
				} else {
				$this->data['static_domain_url'] = $this->config->get('config_img_url');
			}
			
			$this->data['popupcart'] = $this->url->link('common/popupcart');
			
			
			/*---------------- STYLES -------------*/		
			
			//MINIFICATION ENGINE W/STATIC
			require_once DIR_SYSTEM . '../min/static/lib.php';
			$static_uri = "/min/static";
			
			$general_css = prepareEOLArray($this->config->get('config_footer_min_styles'));
			$query = "f=" . implode(',', $general_css);
			$this->data['general_minified_css_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'css');
			
			if ($this->config->get('config_static_subdomain') && $this->data['general_minified_css_uri']){
				$this->data['general_minified_css_uri'] = ltrim($this->data['general_minified_css_uri'], '/');
				
				if (file_exists(DIR_SITE . $this->data['general_minified_css_uri'])){
					$this->data['general_minified_css_uri'] = trim($this->config->get('config_static_subdomain')) . $this->data['general_minified_css_uri'];
				}
			}
			
			/*---------------- END STYLES -------------*/
			
			/*---------------- SCRIPTS -------------*/
			$general_js = prepareEOLArray($this->config->get('config_footer_min_scripts'));
			
			$this->data['mask'] = $this->config->get('config_phonemask');
			
			if ($general_js){
				$query = "f=" . implode(',', $general_js);
				$this->data['general_minified_js_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'js');
			}
			
			if ($this->config->get('config_static_subdomain') && $this->data['general_minified_js_uri']){
				$this->data['general_minified_js_uri'] = ltrim($this->data['general_minified_js_uri'], '/');
				
				if (file_exists(DIR_SITE . $this->data['general_minified_js_uri'])){
					$this->data['general_minified_js_uri'] = trim($this->config->get('config_static_subdomain')) . $this->data['general_minified_js_uri'];
				}
			}
			
			/* !!!!!!!!!! */
			$this->data['incompatible_scripts'] = array(	
			
			);			
			
			/*---------------- END SCRIPTS -------------*/
			//MINIFICATION ENGINE W/STATIC END
			
			$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
			
			//customer info for push
			if ($this->customer->isLogged()){
				$this->load->model('account/address');
				
				$_address = $this->model_account_address->getAddress($this->customer->getAddressId());
				
				if (!$_address || is_array($_address)){
					$this->load->model('localisation/country');
					$_country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
					
					$_address = array(
					'country_id' => $this->config->get('config_country_id'),
					'country' => $_country['name']
					);
				}
				
				$this->data['push_customer_info'] = array(
				'customer_id'      	  => $this->customer->getId(),
				'customer_name'       => $this->customer->getFirstName(),
				'customer_email'      => $this->customer->getEmail(),
				'customer_phone'      => $this->customer->getTelephone(),
				'customer_country_id' => $_address['country_id'],
				'customer_country'    => $_address['country'],
				);
				
				} else {
				$this->data['push_customer_info'] = false;
			}
			
			//detecting mobile
			$this->data['is_pc'] = (!IS_MOBILE_SESSION && !IS_TABLET_SESSION);
			
			$footerBottomScripts = array();
			$this->data['footerBottomScripts'] = $footerBottomScripts;
			
			//GOOGLE CONVERSION		
			$this->data['google_conversion_id'] = $this->config->get('config_google_conversion_id');
			$this->data['config_google_merchant_id'] = $this->config->get('config_google_merchant_id');
			//END GOOGLE CONVERSION						

			$this->data['config_vk_pixel_header'] = html_entity_decode($this->config->get('config_vk_pixel_header'), ENT_QUOTES, 'UTF-8');
			$this->data['config_vk_pixel_body'] = html_entity_decode($this->config->get('config_vk_pixel_body'), ENT_QUOTES, 'UTF-8');
			$this->data['config_vk_pixel_id'] = $this->config->get('config_vk_pixel_id');
			$this->data['config_vk_enable_pixel'] = $this->config->get('config_vk_enable_pixel');
			
			$current_store = $this->config->get('config_store_id');
			$current_lang  = (int)$this->config->get('config_language_id');
			$this->data['logged'] =  $this->customer->isLogged();
			
			$this->load->model('design/layout');
			
			$layout_id = $this->model_design_layout->getLayout('common/footer');
			
			if (!$layout_id) {
				$layout_id = $this->config->get('config_layout_id');
			}
			
			$module_data = array();
			
			$this->load->model('setting/extension');
			
			$extensions = $this->model_setting_extension->getExtensions('module');		
			
			foreach ($extensions as $extension) {
				$modules = $this->config->get($extension['code'] . '_module');
				
				if ($modules) {
					foreach ($modules as $module) {
						if ($module['layout_id'] == $layout_id && $module['status']) {
							$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
							);				
						}
					}
				}
			}
			
			$sort_order = array(); 
			
			foreach ($module_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			
			array_multisort($sort_order, SORT_ASC, $module_data);
			
			$this->data['modules'] = array();
			
			foreach ($module_data as $module) {
				
				$code = $module['code'];
				
				$module = $this->getChild('module/' . $module['code'], $module['setting']);			
				
				if ($module) {
					$this->data['modules'][$code] = $module;
				}
			}	
			
			foreach ($this->language->loadRetranslate('common/footer') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			
			$this->language->load('common/footer');
			
			$this->data['text_information'] = $this->language->get('text_information');
			$this->data['text_service'] = $this->language->get('text_service');
			$this->data['text_extra'] = $this->language->get('text_extra');
			$this->data['text_contact'] = $this->language->get('text_contact');
			$this->data['text_return'] = $this->language->get('text_return');
			$this->data['text_sitemap'] = $this->language->get('text_sitemap');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_voucher'] = $this->language->get('text_voucher');
			$this->data['text_affiliate'] = $this->language->get('text_affiliate');
			$this->data['text_special'] = $this->language->get('text_special');
			$this->data['text_account'] = $this->language->get('text_account');
			$this->data['text_order'] = $this->language->get('text_order');
			$this->data['text_wishlist'] = $this->language->get('text_wishlist');
			$this->data['text_newsletter'] = $this->language->get('text_newsletter');
			
			$this->data['text_site_map'] = $this->language->get('site_map');
			$this->data['text_contacts'] = $this->language->get('contacts');
			$this->data['text_we_hav_payment'] = $this->language->get('we_hav_payment');
			$this->data['text_text_1'] = $this->language->get('text_1');
			$this->data['text_send_me_latter'] = $this->language->get('send_me_latter');
			$this->data['text_send_latter_to_director'] = $this->language->get('send_latter_to_director');
			$this->data['text_subscribe_in_social_network'] = $this->language->get('subscribe_in_social_network');
			$this->data['text_cooking'] = $this->language->get('cooking');
			
			$this->load->model('module/referrer');
			$this->model_module_referrer->checkReferrer();
			
			$this->load->model('catalog/information');
			
			$this->data['informations'] = array();
			
			foreach ($this->model_catalog_information->getInformations() as $result) {
				if ($result['bottom']) {
					$this->data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
					);
				}
			}
			
			$this->data['phone'] = $this->config->get('config_telephone');
			$this->data['phone2'] = $this->config->get('config_telephone2');	
			$this->data['worktime'] = $this->config->get('config_worktime');
			$this->data['email'] = $this->config->get('config_display_email');		
			$this->data['address'] = $this->config->get('config_address');		
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			
			if ($category_info = $this->model_catalog_category->getCategory(GENERAL_MARKDOWN_CATEGORY) && $total_markdown = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => GENERAL_MARKDOWN_CATEGORY, 'filter_sub_category' => true, 'filter_enable_markdown' => true))){
				$this->data['markdown_link'] = $this->url->link('product/category', 'category_id=' . GENERAL_MARKDOWN_CATEGORY);
				$this->data['markdown_total'] = (int)$total_markdown;
			}
			
			//END GEOIP VALUES
			
			$this->data['contact'] = $this->url->link('information/contact');
			$this->data['faq_url'] = $this->url->link('information/faq_system');
			$this->data['about_url'] = $this->url->link('information/information', 'information_id=4');
			$this->data['site_map_url'] = $this->url->link('information/sitemap');
			
			
			$this->data['return'] = $this->url->link('account/return/insert', '', 'SSL');
			$this->data['sitemap'] = $this->url->link('information/sitemap');
			$this->data['manufacturer'] = $this->url->link('product/manufacturer');
			$this->data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
			$this->data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
			$this->data['special'] = $this->url->link('product/special');
			$this->data['account'] = $this->url->link('account/account', '', 'SSL');
			$this->data['order'] = $this->url->link('account/order', '', 'SSL');
			$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
			$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');	
			$this->data['productnews'] = $this->url->link('product/product_new', '', 'SSL');							
			
			$this->data['all_actions'] = $this->url->link('information/actions', '', 'SSL');
			$this->data['product_new'] = $this->url->link('product/product_new', '', 'SSL');
			$this->data['shoprating'] = $this->url->link('information/shop_rating', '', 'SSL');
			
			$this->data['href_how_order'] = $this->url->link('information/information', 'information_id=29');
			$this->data['href_delivery'] = $this->url->link('information/information', 'information_id=31');
			$this->data['href_payment'] = $this->url->link('information/information', 'information_id=30');
			$this->data['href_return'] = $this->url->link('information/information', 'information_id=33');
			$this->data['href_vendors'] = $this->url->link('information/information', 'information_id=44');
			
			$this->data['href_cashback'] = $this->url->link('information/information', 'information_id=45');
			
			$this->data['href_discounts'] = $this->url->link('information/information', 'information_id=32');
			$this->data['href_present_sertificate'] = $this->url->link('information/information', 'information_id=35');
			
			$this->data['href_about'] = $this->url->link('information/information', 'information_id=4');
			$this->data['href_faq'] = $this->url->link('information/faq_system');
			$this->data['href_contact'] = $this->url->link('information/contact');
			$this->data['href_shop_rating'] = $this->url->link('information/shop_rating');
			$this->data['href_sitemap'] = $this->url->link('information/sitemap');
			
			$this->data['href_polzovatelskoe'] = $this->url->link('information/information', 'information_id=5');
			$this->data['href_personaldata'] = $this->url->link('information/information', 'information_id=3');						
			
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
			
			$this->data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
			
			$this->data['language_code'] = $this->language->get('code');
			
			$this->children = array(
			/*	'module/currency', */
			//	'module/mattimeotheme',
			//	'common/footer_info_buttons'
			);
			

			$hreflangs = $this->document->getHrefLangs();		
			$this->data['href_ru'] = !empty($hreflangs[2]['link'])?$hreflangs[2]['link']:'';
			$this->data['href_kz'] = !empty($hreflangs[9]['link'])?$hreflangs[9]['link']:'';
			$this->data['href_by'] = !empty($hreflangs[8]['link'])?$hreflangs[8]['link']:'';
			
			if ($this->config->get('config_language_id') == 6) {
				$this->data['href_ua'] = !empty($hreflangs[6]['link'])?$hreflangs[6]['link']:'';
				} else {
				$this->data['href_ua'] = !empty($hreflangs[5]['link'])?$hreflangs[5]['link']:'';
			}
			
			$routes = $this->config->get('metaseo_anypage_routes');
			if (is_array($routes)) {
				foreach ($routes as $route) {
					$shop_route = isset($this->request->get['route'])?$this->request->get['route']:'common/home';
					if ($this->checkIfGETRouteOnly() && $route['route'] == $shop_route) {
						$language_id = $this->config->get('config_language_id');
						if (isset($route['title'][$language_id]) && $route['title'][$language_id]) {
							$this->document->setTitle($this->replaceMetaSeo($route['title'][$language_id]));
							$this->document->addOpenGraph('og:title', $this->replaceMetaSeo($route['title'][$language_id]));
						}
						if (isset($route['meta_description'][$language_id]) && $route['meta_description'][$language_id]) {
							$this->document->setDescription($this->replaceMetaSeo($route['meta_description'][$language_id]));
							
							$this->document->addOpenGraph('og:description', $this->replaceMetaSeo($route['meta_description'][$language_id]));
						}
						break;
					}
				}
			}
			
			//REWARD TEXT
			if ($this->config->get('rewardpoints_appinstall')){
				$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
			}
			
			//TRY TO FOUND ADMIN SESSION
			if (ADMIN_SESSION_DETECTED){
				if (!empty($this->request->cookie[ini_get('session.name') . 'A'])){
					if (defined('DB_SESSION_HOSTNAME') && class_exists('Hobotix\SessionHandler\SessionHandler')){
						$handler = new \Hobotix\SessionHandler\SessionHandler();
						$handler->setDbDetails(DB_SESSION_HOSTNAME, DB_SESSION_USERNAME, DB_SESSION_PASSWORD, DB_SESSION_DATABASE);
						$handler->setDbTable(DB_SESSION_TABLE);

						if ($adminSessionData = $handler->read($this->request->cookie[ini_get('session.name') . 'A'])){
							$adminSessionData = \Hobotix\SessionHandler\SessionHandler::unserialize($adminSessionData);

							if ($adminSessionData){
								$this->load->model('user/user');

								$this->data['admin_user_id'] 	= !empty($adminSessionData['user_id'])?$adminSessionData['user_id']:false; 
								$this->data['admin_token']  	= !empty($adminSessionData['token'])?$adminSessionData['token']:false;

								$this->data['user'] = $this->model_user_user->getUser($this->data['admin_user_id']);

								switch (tryToGuessPageType($this->request->get)) {
									case 'product':
									$this->data['admin_uri'] = HTTP_ADMIN . 'index.php?route=catalog/product/update&token='. $this->data['admin_token'] .'&product_id=' . $this->request->get['product_id'];
									break;

									case 'category':
									$parts = explode('_', (string)$this->request->get['path']);
									$this->data['admin_uri'] = HTTP_ADMIN . 'index.php?route=catalog/category/update&token='. $this->data['admin_token'] .'&category_id=' . (int)array_pop($parts);
									break;

									case 'manufacturer':									
									$this->data['admin_uri'] = HTTP_ADMIN . 'index.php?route=catalog/manufacturer/update&token='. $this->data['admin_token'] .'&manufacturer_id=' . $this->request->get['manufacturer_id'];
									break;

									case 'collection':									
									$this->data['admin_uri'] = HTTP_ADMIN . 'index.php?route=catalog/collection/update&token='. $this->data['admin_token'] .'&collection_id=' . $this->request->get['collection_id'];
									break;

									default:
									break;
								}
							}
						}
					}
				}
			}
			
			
			$this->template = 'common/footer';
			
			$this->load->model('design/layout');
			$layout_id = $this->model_design_layout->getLayout('common/footer');				
			if ($template_overload) {
				$this->template = $template_overload;				
				} elseif ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
				$this->template = $template;			
				} else {
				$this->template = 'common/footer';
			}	
									
			$this->response->setOutput($this->render());			
		}
	}							
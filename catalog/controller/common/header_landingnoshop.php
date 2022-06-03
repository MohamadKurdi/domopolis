<?php   
class ControllerCommonHeaderLandingNoShop extends Controller {
	
	protected function index() {
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['static_domain_url'] = $this->config->get('config_img_ssl');
		} else {
			$this->data['static_domain_url'] = $this->config->get('config_img_url');
		}
					
		$opt_group_array = array(8,9,10,11);
		
		//preauth
		$this->load->model('account/preauth');

		if (!$this->customer->isLogged()){
			if ($email = $this->model_account_preauth->CheckPreauth()){
				$this->customer->login($email, '', true);				
			}			
		}
		
		//Количество просмотров ПОЛНЫХ СТРАНИЦ
		if (!isset($this->session->data['pages_viewed'])){
			$this->session->data['pages_viewed'] = 1;	
		} else {
			$this->session->data['pages_viewed']++;
		}
	
		//REDIRECT MANAGER
		if ($this->config->get('redirect_manager_status')) {
					
					$request_query = $this->request->server['REQUEST_URI'];
					$http_server = $this->request->server['HTTP_HOST'];
					
					if (strpos($request_query, '?')){
						$query_string = substr($request_query, (strpos($request_query, '?')+1));						
					}
					
				//удаляем query_string
					if (isset($query_string) && strlen($query_string)>0){
						$request_query = substr($request_query, 0, -1*(strlen($query_string)+1));						
					}
					
				//удаляем первый и последний слэш, если он есть	
					if (substr($request_query, -1) == '/') {
						$request_query = substr($request_query, 0, -1);
					}
					
					if (strlen($request_query)>0 && $request_query[0] == '/') {						
						$request_query = substr($request_query, 1);
					}
				/*	
					$search_query = $this->db->query(
						"SELECT * FROM `redirect` 
						WHERE (
							from_url LIKE ('" . $this->db->escape($request_query) . "') OR 
							from_url LIKE ('" . $this->db->escape($request_query) . "/') OR 
							from_url LIKE ('/" . $this->db->escape($request_query) ."') OR 
							from_url LIKE ('/" . $this->db->escape($request_query) ."/'))
						AND (active = 1)
						AND (date_start = '0000-00-00' OR date_start < '". date(MYSQL_NOW_DATE_FORMAT) ."')
						AND (date_end = '0000-00-00' OR date_end > '". date(MYSQL_NOW_DATE_FORMAT) ."')"
					);
				*/
					$search_query = $this->db->query(
						"SELECT * FROM `redirect` 
						WHERE (
							from_url LIKE ('" . $this->db->escape($request_query) . "') OR 
							from_url LIKE ('" . $this->db->escape($request_query) . "/') OR 
							from_url LIKE ('/" . $this->db->escape($request_query) ."') OR 
							from_url LIKE ('/" . $this->db->escape($request_query) ."/'))
						AND (active = 1)"
					);	
			
					if ($search_query->num_rows) {																		
						$this->db->query("UPDATE `redirect` SET times_used = times_used+1 WHERE redirect_id = " . (int)$search_query->row['redirect_id']);
						
						$to_url = $search_query->row['to_url'];
						
						if (substr($to_url, -1) == '/') {
							$to_url = substr($to_url, 0, -1);
						}
					
						if (strlen($to_url)>0 && $to_url[0] == '/') {
							$to_url = substr($to_url, 1);
						}
						
						if (isset($query_string) && strlen($query_string)>0){
							if (strpos($to_url, '?')){
								$to = '/' . $to_url . '&' . $query_string;							
							} else {
								$to = '/' . $to_url . '?' . $query_string;								
							}										
						} else {
							$to = '/' . $to_url;							
						}

						header('Location: ' . str_replace('&amp;', '&', $to), true, $redirect_query->row['response_code']);
						exit();
					}						
				}		
		//REDIRECT MANAGER END
						
		$this->data['title'] = $this->document->getTitle();
		$this->data['config_title'] = $this->config->get('config_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

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
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['name'] = $this->config->get('config_name');
		$this->data['o_email'] = $this->config->get('config_display_email');			
		$worktime =  explode(';', $this->config->get('config_worktime'));
		
		$this->data['social_auth'] = $this->config->get('config_social_auth');
		
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
		
		
//BEGIN SCRIPTS			
		$topScripts = array(
			'catalog/view/javascript/jquery/jquery.total-storage.min.js',
			$this->data['static_domain_url'] . "catalog/view/theme/".$this->config->get('config_template')."/js/owl.carousel.min.js",
			$this->data['static_domain_url'] . "catalog/view/javascript/jquery/jquery.jcarousel.min.js",
			$this->data['static_domain_url'] . "catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js",
			$this->data['static_domain_url'] . "catalog/view/theme/".$this->config->get('config_template')."/js/maintop.js"
		);
		
		$addedScripts = $this->document->getScripts();
		
		foreach ($addedScripts as $s){
			$topScripts[] = $s;
		}
		
		$this->data['topScripts'] = array_unique($topScripts);
											
	//	$this->data['topCachedScript'] = $server . 'system/cache/' . BCACHE_DIR .'js/'. $this->bcache->MergeJS($topScripts);
				
		$bottomScripts = array();
		
		$this->data['bottomScripts'] = $bottomScripts;
//END SCRIPTS		
	
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}		

		$this->language->load('common/header');

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
		
		$this->data['all_actions'] = $this->url->link('information/actions', '', 'SSL');
		$this->data['product_new'] = $this->url->link('product/product_new', '', 'SSL');
		
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), mb_substr($this->customer->getFirstName(),0,8, 'UTF-8'));
		
		$this->data['loginb2b'] = $this->url->link('account/simpleregisterb2b', '', 'SSL');
			
		

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
			
		$this->data['text_special'] = $this->language->get('text_special');	
		$this->data['special'] = $this->url->link('product/special');
			
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['href_manufacturer'] = $this->url->link('product/manufacturer');
	
		$this->data['google_analytics_header'] = html_entity_decode($this->config->get('config_google_analytics_header'), ENT_QUOTES, 'UTF-8');
		

	  $this->children = array(
		  'module/language',
		  'module/currency',
		  'module/cart',
	  );
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header_noshop.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/common/header_noshop.tpl';
			} else {
				$this->template = 'default/template/common/header_noshop.tpl';
			}
		

		$this->render();
	} 	
}
?>

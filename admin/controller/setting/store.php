<?php
	class ControllerSettingStore extends Controller {
		private $error = array(); 
		
		public function index() {
			$this->language->load('setting/store');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/store');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('setting/store');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/store');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$store_id = $this->model_setting_store->addStore($this->request->post);
				
				$this->load->model('setting/setting');
				
				$this->model_setting_setting->editSetting('config', $this->request->post, $store_id);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('setting/store');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/store');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);
				
				$this->load->model('setting/setting');
				
				$this->model_setting_setting->editSetting('config', $this->request->post, $this->request->get['store_id']);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('setting/store');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/store');
			
			$this->load->model('setting/setting');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $store_id) {
					$this->model_setting_store->deleteStore($store_id);
					
					$this->model_setting_setting->deleteSetting('config', $store_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('setting/store/insert', 'token=' . $this->session->data['token']);
			$this->data['delete'] = $this->url->link('setting/store/delete', 'token=' . $this->session->data['token']);	
			
			$this->data['stores'] = array();
			
			$action = array();
			
			$action[] = array(
			'text' => $this->language->get('text_edit'),
			'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
			);
			
			$this->data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'm'			=> 'Главный. Мультибрендовый.',
			'selected' => isset($this->request->post['selected']) && in_array(0, $this->request->post['selected']),
			'action'   => $action
			);
			
			$store_total = $this->model_setting_store->getTotalStores();
			
			$results = $this->model_setting_store->getStores();
			
			$this->load->model('setting/setting');
			$this->load->model('catalog/manufacturer');
			foreach ($results as $result) {
				
				if ($result['store_id'] == 0){
					continue;
				}
				
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('setting/store/update', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
				);
				
				$monobrand = $this->model_setting_setting->getSetting('config', $result['store_id']);
				$monobrand = $monobrand['config_monobrand'];
				
				if ($monobrand){
					$m = $this->model_catalog_manufacturer->getManufacturer($monobrand);				
					} else {
					$m = array('name'=>'Мультибрендовый');
				}
				
				
				$this->data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'm'		   => $m['name'],
				'selected' => isset($this->request->post['selected']) && in_array($result['store_id'], $this->request->post['selected']),
				'action'   => $action
				);
			}	
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_url'] = $this->language->get('column_url');
			$this->data['column_action'] = $this->language->get('column_action');		
			
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->template = 'setting/store_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function getForm() { 
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_none'] = $this->language->get('text_none');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			$this->data['text_items'] = $this->language->get('text_items');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_account'] = $this->language->get('text_account');
			$this->data['text_checkout'] = $this->language->get('text_checkout');
			$this->data['text_stock'] = $this->language->get('text_stock');				
			$this->data['text_image_manager'] = $this->language->get('text_image_manager');
			$this->data['text_browse'] = $this->language->get('text_browse');
			$this->data['text_clear'] = $this->language->get('text_clear');			
			$this->data['text_shipping'] = $this->language->get('text_shipping');	
			$this->data['text_payment'] = $this->language->get('text_payment');	
			
			$this->data['entry_url'] = $this->language->get('entry_url');
			$this->language->load_full('setting/store');
            $this->data['text_mail'] = $this->language->get('text_mail');
			$this->data['text_smtp'] = $this->language->get('text_smtp');
			$this->data['entry_mail_protocol'] = $this->language->get('entry_mail_protocol');
			$this->data['entry_mail_parameter'] = $this->language->get('entry_mail_parameter');
			$this->data['entry_smtp_host'] = $this->language->get('entry_smtp_host');
			$this->data['entry_smtp_username'] = $this->language->get('entry_smtp_username');
			$this->data['entry_smtp_password'] = $this->language->get('entry_smtp_password');
			$this->data['entry_smtp_port'] = $this->language->get('entry_smtp_port');
			$this->data['entry_smtp_timeout'] = $this->language->get('entry_smtp_timeout');
			$this->data['entry_alert_mail'] = $this->language->get('entry_alert_mail');
			$this->data['entry_account_mail'] = $this->language->get('entry_account_mail');
			$this->data['entry_alert_emails'] = $this->language->get('entry_alert_emails');
			$this->data['tab_mail'] = $this->language->get('tab_mail');
			$this->data['entry_ssl'] = $this->language->get('entry_ssl');	
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_owner'] = $this->language->get('entry_owner');
			$this->data['entry_address'] = $this->language->get('entry_address');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');				
			$this->data['entry_title'] = $this->language->get('entry_title');
			$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
			$this->data['entry_layout'] = $this->language->get('entry_layout');
			$this->data['entry_template'] = $this->language->get('entry_template');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_language'] = $this->language->get('entry_language');
			$this->data['entry_currency'] = $this->language->get('entry_currency');
			$this->data['entry_catalog_limit'] = $this->language->get('entry_catalog_limit');
			$this->data['entry_tax'] = $this->language->get('entry_tax');
			$this->data['entry_tax_default'] = $this->language->get('entry_tax_default');
			$this->data['entry_tax_customer'] = $this->language->get('entry_tax_customer');		
			$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
			$this->data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
			$this->data['entry_customer_price'] = $this->language->get('entry_customer_price');
			$this->data['entry_account'] = $this->language->get('entry_account');
			$this->data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
			$this->data['entry_guest_checkout'] = $this->language->get('entry_guest_checkout');
			$this->data['entry_checkout'] = $this->language->get('entry_checkout');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['entry_stock_display'] = $this->language->get('entry_stock_display');
			$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
			$this->data['entry_ajax_cart'] = $this->language->get('entry_ajax_cart');
			$this->data['entry_logo'] = $this->language->get('entry_logo');
			$this->data['entry_icon'] = $this->language->get('entry_icon');
			$this->data['entry_image_category'] = $this->language->get('entry_image_category');
			$this->data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
			$this->data['entry_image_popup'] = $this->language->get('entry_image_popup');
			$this->data['entry_image_product'] = $this->language->get('entry_image_product');
			$this->data['entry_image_additional'] = $this->language->get('entry_image_additional');
			$this->data['entry_image_related'] = $this->language->get('entry_image_related');
			$this->data['entry_image_compare'] = $this->language->get('entry_image_compare');
			$this->data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
			$this->data['entry_image_cart'] = $this->language->get('entry_image_cart');
			$this->data['entry_secure'] = $this->language->get('entry_secure');
			$this->data['entry_encryption'] = $this->language->get('entry_encryption');
			$this->data['entry_seo_url'] = $this->language->get('entry_seo_url');
			$this->data['entry_seo_url_type'] = $this->language->get('entry_seo_url_type');
			$this->data['entry_seo_url_include_path'] = $this->language->get('entry_seo_url_include_path');
			$this->data['entry_seo_url_postfix'] = $this->language->get('entry_seo_url_postfix');
			$this->data['entry_seo_url'] = $this->language->get('entry_seo_url');
			$this->data['entry_compression'] = $this->language->get('entry_compression');
			$this->data['entry_error_display'] = $this->language->get('entry_error_display');
			$this->data['entry_error_log'] = $this->language->get('entry_error_log');
			$this->data['entry_error_filename'] = $this->language->get('entry_error_filename');
			$this->data['entry_google_analytics'] = $this->language->get('entry_google_analytics');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['tab_store'] = $this->language->get('tab_store');
			$this->data['tab_local'] = $this->language->get('tab_local');
			$this->data['tab_option'] = $this->language->get('tab_option');
			$this->data['tab_image'] = $this->language->get('tab_image');
			$this->data['tab_server'] = $this->language->get('tab_server');

			$this->data['store_id'] = $this->request->get['store_id'];
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['url'])) {
				$this->data['error_url'] = $this->error['url'];
				} else {
				$this->data['error_url'] = '';
			}
			
			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
				} else {
				$this->data['error_name'] = '';
			}
			
			if (isset($this->error['owner'])) {
				$this->data['error_owner'] = $this->error['owner'];
				} else {
				$this->data['error_owner'] = '';
			}
			
			if (isset($this->error['address'])) {
				$this->data['error_address'] = $this->error['address'];
				} else {
				$this->data['error_address'] = '';
			}
			
			if (isset($this->error['email'])) {
				$this->data['error_email'] = $this->error['email'];
				} else {
				$this->data['error_email'] = '';
			}
			
			if (isset($this->error['telephone'])) {
				$this->data['error_telephone'] = $this->error['telephone'];
				} else {
				$this->data['error_telephone'] = '';
			}
			
			if (isset($this->error['title'])) {
				$this->data['error_title'] = $this->error['title'];
				} else {
				$this->data['error_title'] = '';
			}
			
			if (isset($this->error['customer_group_display'])) {
				$this->data['error_customer_group_display'] = $this->error['customer_group_display'];
				} else {
				$this->data['error_customer_group_display'] = '';
			}
			
			if (isset($this->error['image_category'])) {
				$this->data['error_image_category'] = $this->error['image_category'];
				} else {
				$this->data['error_image_category'] = '';
			}
			
			if (isset($this->error['image_thumb'])) {
				$this->data['error_image_thumb'] = $this->error['image_thumb'];
				} else {
				$this->data['error_image_thumb'] = '';
			}
			
			if (isset($this->error['image_popup'])) {
				$this->data['error_image_popup'] = $this->error['image_popup'];
				} else {
				$this->data['error_image_popup'] = '';
			}
			
			if (isset($this->error['image_product'])) {
				$this->data['error_image_product'] = $this->error['image_product'];
				} else {
				$this->data['error_image_product'] = '';
			}
			
			if (isset($this->error['image_additional'])) {
				$this->data['error_image_additional'] = $this->error['image_additional'];
				} else {
				$this->data['error_image_additional'] = '';
			}	
			
			if (isset($this->error['image_related'])) {
				$this->data['error_image_related'] = $this->error['image_related'];
				} else {
				$this->data['error_image_related'] = '';
			}
			
			if (isset($this->error['image_compare'])) {
				$this->data['error_image_compare'] = $this->error['image_compare'];
				} else {
				$this->data['error_image_compare'] = '';
			}
			
			if (isset($this->error['image_wishlist'])) {
				$this->data['error_image_wishlist'] = $this->error['image_wishlist'];
				} else {
				$this->data['error_image_wishlist'] = '';
			}
			
			if (isset($this->error['image_cart'])) {
				$this->data['error_image_cart'] = $this->error['image_cart'];
				} else {
				$this->data['error_image_cart'] = '';
			}
			
			if (isset($this->error['catalog_limit'])) {
				$this->data['error_catalog_limit'] = $this->error['catalog_limit'];
				} else {
				$this->data['error_catalog_limit'] = '';
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			if (!isset($this->request->get['store_id'])) {
				$this->data['action'] = $this->url->link('setting/store/insert', 'token=' . $this->session->data['token']);
				} else {
				$this->data['action'] = $this->url->link('setting/store/update', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id']);
			}
			
			$this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token']);
			
			if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$this->load->model('setting/setting');
				
				$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->post['config_url'])) {
				$this->data['config_url'] = $this->request->post['config_url'];
				} elseif (isset($store_info['config_url'])) {
				$this->data['config_url'] = $store_info['config_url'];
				} else {
				$this->data['config_url'] = '';
			}						
			
			if (isset($this->request->post['config_ssl'])) {
				$this->data['config_ssl'] = $this->request->post['config_ssl'];
				} elseif (isset($store_info['config_ssl'])) {
				$this->data['config_ssl'] = $store_info['config_ssl'];
				} else {
				$this->data['config_ssl'] = '';
			}
			
			if (isset($this->request->post['config_img_ssl'])) {
				$this->data['config_img_ssl'] = $this->request->post['config_img_ssl'];
				} elseif (isset($store_info['config_img_ssl'])) {
				$this->data['config_img_ssl'] = $store_info['config_img_ssl'];
				} else {
				$this->data['config_img_ssl'] = '';
			}
			
			if (isset($this->request->post['config_img_url'])) {
				$this->data['config_img_url'] = $this->request->post['config_img_url'];
				} elseif (isset($store_info['config_img_url'])) {
				$this->data['config_img_url'] = $store_info['config_img_url'];
				} else {
				$this->data['config_img_url'] = '';
			}
			
			if (isset($this->request->post['config_img_urls'])) {
				$this->data['config_img_urls'] = $this->request->post['config_img_urls'];
				} elseif (isset($store_info['config_img_urls'])) {
				$this->data['config_img_urls'] = $store_info['config_img_urls'];
				} else {
				$this->data['config_img_urls'] = '';
			}
			
			if (isset($this->request->post['config_img_ssls'])) {
				$this->data['config_img_ssls'] = $this->request->post['config_img_ssls'];
				} elseif (isset($store_info['config_img_ssls'])) {
				$this->data['config_img_ssls'] = $store_info['config_img_ssls'];
				} else {
				$this->data['config_img_ssls'] = '';
			}
			
			if (isset($this->request->post['config_static_subdomain'])) {
				$this->data['config_static_subdomain'] = $this->request->post['config_static_subdomain'];
				} elseif (isset($store_info['config_static_subdomain'])) {
				$this->data['config_static_subdomain'] = $store_info['config_static_subdomain'];
				} else {
				$this->data['config_static_subdomain'] = '';
			}
			
			
			if (isset($this->request->post['config_img_server_count'])) {
				$this->data['config_img_server_count'] = $this->request->post['config_img_server_count'];
				} elseif (isset($store_info['config_img_server_count'])) {
				$this->data['config_img_server_count'] = $store_info['config_img_server_count'];
				} else {
				$this->data['config_img_server_count'] = '';
			}
			
			
			if (isset($this->request->post['config_name'])) {
				$this->data['config_name'] = $this->request->post['config_name'];
				} elseif (isset($store_info['config_name'])) {
				$this->data['config_name'] = $store_info['config_name'];
				} else {
				$this->data['config_name'] = '';
			}
			
			if (isset($this->request->post['config_owner'])) {
				$this->data['config_owner'] = $this->request->post['config_owner'];
				} elseif (isset($store_info['config_owner'])) {
				$this->data['config_owner'] = $store_info['config_owner'];		
				} else {
				$this->data['config_owner'] = '';
			}
			
			if (isset($this->request->post['config_address'])) {
				$this->data['config_address'] = $this->request->post['config_address'];
				} elseif (isset($store_info['config_address'])) {
				$this->data['config_address'] = $store_info['config_address'];		
				} else {
				$this->data['config_address'] = '';
			}
			
			if (isset($this->request->post['config_popular_searches'])) {
				$this->data['config_popular_searches'] = $this->request->post['config_popular_searches'];
				} elseif (isset($store_info['config_popular_searches'])) {
				$this->data['config_popular_searches'] = $store_info['config_popular_searches'];		
				} else {
				$this->data['config_popular_searches'] = '';
			}
			
			if (isset($this->request->post['config_sms_sign'])) {
				$this->data['config_sms_sign'] = $this->request->post['config_sms_sign'];
				} elseif (isset($store_info['config_sms_sign'])) {
				$this->data['config_sms_sign'] = $store_info['config_sms_sign'];		
				} else {
				$this->data['config_sms_sign'] = '';
			}

			/* SMS SETTINGS NOW CAN BE OVERLOADED */

			if (isset($this->request->post['config_sms_tracker_leave_main_warehouse_enabled'])) {
				$this->data['config_sms_tracker_leave_main_warehouse_enabled'] = $this->request->post['config_sms_tracker_leave_main_warehouse_enabled'];
				} elseif (isset($store_info['config_sms_tracker_leave_main_warehouse_enabled'])) {
				$this->data['config_sms_tracker_leave_main_warehouse_enabled'] = $store_info['config_sms_tracker_leave_main_warehouse_enabled'];		
				} else {
				$this->data['config_sms_tracker_leave_main_warehouse_enabled'] = '';
			}

			if (isset($this->request->post['config_sms_tracker_leave_main_warehouse'])) {
				$this->data['config_sms_tracker_leave_main_warehouse'] = $this->request->post['config_sms_tracker_leave_main_warehouse'];
				} elseif (isset($store_info['config_sms_tracker_leave_main_warehouse'])) {
				$this->data['config_sms_tracker_leave_main_warehouse'] = $store_info['config_sms_tracker_leave_main_warehouse'];		
				} else {
				$this->data['config_sms_tracker_leave_main_warehouse'] = '';
			}

			if (isset($this->request->post['config_sms_payment_recieved_enabled'])) {
				$this->data['config_sms_payment_recieved_enabled'] = $this->request->post['config_sms_payment_recieved_enabled'];
				} elseif (isset($store_info['config_sms_payment_recieved_enabled'])) {
				$this->data['config_sms_payment_recieved_enabled'] = $store_info['config_sms_payment_recieved_enabled'];		
				} else {
				$this->data['config_sms_payment_recieved_enabled'] = 0;
			}

			if (isset($this->request->post['config_sms_payment_recieved'])) {
				$this->data['config_sms_payment_recieved'] = $this->request->post['config_sms_payment_recieved'];
				} elseif (isset($store_info['config_sms_payment_recieved'])) {
				$this->data['config_sms_payment_recieved'] = $store_info['config_sms_payment_recieved'];		
				} else {
				$this->data['config_sms_payment_recieved'] = '';
			}

			if (isset($this->request->post['config_sms_ttn_sent_enabled'])) {
                $this->data['config_sms_ttn_sent_enabled'] = $this->request->post['config_sms_ttn_sent_enabled'];
                } elseif (isset($store_info['config_sms_ttn_sent_enabled'])) {
                $this->data['config_sms_ttn_sent_enabled'] = $store_info['config_sms_ttn_sent_enabled'];        
                } else {
                $this->data['config_sms_ttn_sent_enabled'] = 0;
            }

            if (isset($this->request->post['config_sms_ttn_sent'])) {
                $this->data['config_sms_ttn_sent'] = $this->request->post['config_sms_ttn_sent'];
                } elseif (isset($store_info['config_sms_ttn_sent'])) {
                $this->data['config_sms_ttn_sent'] = $store_info['config_sms_ttn_sent'];        
                } else {
                $this->data['config_sms_ttn_sent'] = '';
            }

            if (isset($this->request->post['config_sms_ttn_ready_enabled'])) {
                $this->data['config_sms_ttn_ready_enabled'] = $this->request->post['config_sms_ttn_ready_enabled'];
                } elseif (isset($store_info['config_sms_ttn_ready_enabled'])) {
                $this->data['config_sms_ttn_ready_enabled'] = $store_info['config_sms_ttn_ready_enabled'];        
                } else {
                $this->data['config_sms_ttn_ready_enabled'] = 0;
            }

            if (isset($this->request->post['config_sms_ttn_ready'])) {
                $this->data['config_sms_ttn_ready'] = $this->request->post['config_sms_ttn_ready'];
                } elseif (isset($store_info['config_sms_ttn_ready'])) {
                $this->data['config_sms_ttn_ready'] = $store_info['config_sms_ttn_ready'];        
                } else {
                $this->data['config_sms_ttn_ready'] = '';
            }

			if (isset($this->request->post['config_sms_send_new_order_status'])) {
				$this->data['config_sms_send_new_order_status'] = $this->request->post['config_sms_send_new_order_status'];
				} elseif (isset($store_info['config_sms_send_new_order_status'])) {
				$this->data['config_sms_send_new_order_status'] = $store_info['config_sms_send_new_order_status'];		
				} else {
				$this->data['config_sms_send_new_order_status'] = 0;
			}

			if (isset($this->request->post['config_sms_new_order_status_message'])) {
				$this->data['config_sms_new_order_status_message'] = $this->request->post['config_sms_new_order_status_message'];
				} elseif (isset($store_info['config_sms_new_order_status_message'])) {
				$this->data['config_sms_new_order_status_message'] = (array)$store_info['config_sms_new_order_status_message'];		
				} else {
				$this->data['config_sms_new_order_status_message'] = [];
			}

			if (isset($this->request->post['config_sms_send_new_order'])) {
				$this->data['config_sms_send_new_order'] = $this->request->post['config_sms_send_new_order'];
				} elseif (isset($store_info['config_sms_send_new_order'])) {
				$this->data['config_sms_send_new_order'] = $store_info['config_sms_send_new_order'];		
				} else {
				$this->data['config_sms_send_new_order'] = 0;
			}

			if (isset($this->request->post['config_sms_new_order_message'])) {
				$this->data['config_sms_new_order_message'] = $this->request->post['config_sms_new_order_message'];
				} elseif (isset($store_info['config_sms_new_order_message'])) {
				$this->data['config_sms_new_order_message'] = $store_info['config_sms_new_order_message'];		
				} else {
				$this->data['config_sms_new_order_message'] = '';
			}

			
			if (isset($this->request->post['config_phonemask'])) {
				$this->data['config_phonemask'] = $this->request->post['config_phonemask'];
				} elseif (isset($store_info['config_phonemask'])) {
				$this->data['config_phonemask'] = $store_info['config_phonemask'];		
				} else {
				$this->data['config_phonemask'] = '';
			}

			$this->load->model('localisation/language');			
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['config_default_city'])) {
				$this->data['config_default_city'] = $this->request->post['config_default_city'];
				} elseif (isset($store_info['config_default_city'])) {
				$this->data['config_default_city'] = $store_info['config_default_city'];		
				} else {
				$this->data['config_default_city'] = '';
			}

			foreach ($this->data['languages'] as $city_language){

				if (isset($this->request->post['config_default_city_' . $city_language['code']])) {
					$this->data['config_default_city_' . $city_language['code']] = $this->request->post['config_default_city_' . $city_language['code']];
				} elseif (isset($store_info['config_default_city_' . $city_language['code']])) {				
					$this->data['config_default_city_' . $city_language['code']] = $store_info['config_default_city_' . $city_language['code']];
				} else {
					$this->data['config_default_city_' . $city_language['code']] = '';
				}

			}	
			
			if (isset($this->request->post['config_email'])) {
				$this->data['config_email'] = $this->request->post['config_email'];
				} elseif (isset($store_info['config_email'])) {
				$this->data['config_email'] = $store_info['config_email'];		
				} else {
				$this->data['config_email'] = '';
			}
			
			if (isset($this->request->post['config_display_email'])) {
				$this->data['config_display_email'] = $this->request->post['config_display_email'];
				} elseif (isset($store_info['config_display_email'])) {
				$this->data['config_display_email'] = $store_info['config_display_email'];		
				} else {
				$this->data['config_display_email'] = '';
			}
			
			if (isset($this->request->post['config_opt_email'])) {
				$this->data['config_opt_email'] = $this->request->post['config_opt_email'];
				} elseif (isset($store_info['config_opt_email'])) {
				$this->data['config_opt_email'] = $store_info['config_opt_email'];		
				} else {
				$this->data['config_opt_email'] = '';
			}
			
			if (isset($this->request->post['config_telephone'])) {
				$this->data['config_telephone'] = $this->request->post['config_telephone'];
				} elseif (isset($store_info['config_telephone'])) {
				$this->data['config_telephone'] = $store_info['config_telephone'];		
				} else {
				$this->data['config_telephone'] = '';
			}
			
			if (isset($this->request->post['config_telephone2'])) {
				$this->data['config_telephone2'] = $this->request->post['config_telephone2'];
				} elseif (isset($store_info['config_telephone2'])) {
				$this->data['config_telephone2'] = $store_info['config_telephone2'];		
				} else {
				$this->data['config_telephone2'] = '';
			}
			
			if (isset($this->request->post['config_telephone3'])) {
				$this->data['config_telephone3'] = $this->request->post['config_telephone3'];
				} elseif (isset($store_info['config_telephone3'])) {
				$this->data['config_telephone3'] = $store_info['config_telephone3'];		
				} else {
				$this->data['config_telephone3'] = '';
			}
			
			if (isset($this->request->post['config_opt_telephone'])) {
				$this->data['config_opt_telephone'] = $this->request->post['config_opt_telephone'];
				} elseif (isset($store_info['config_opt_telephone'])) {
				$this->data['config_opt_telephone'] = $store_info['config_opt_telephone'];		
				} else {
				$this->data['config_opt_telephone'] = '';
			}
			
			if (isset($this->request->post['config_opt_telephone2'])) {
				$this->data['config_opt_telephone2'] = $this->request->post['config_opt_telephone2'];
				} elseif (isset($store_info['config_opt_telephone2'])) {
				$this->data['config_opt_telephone2'] = $store_info['config_opt_telephone2'];		
				} else {
				$this->data['config_opt_telephone2'] = '';
			}
			
			if (isset($this->request->post['config_t_tt'])) {
				$this->data['config_t_tt'] = $this->request->post['config_t_tt'];
				} elseif (isset($store_info['config_t_tt'])) {
				$this->data['config_t_tt'] = $store_info['config_t_tt'];		
				} else {
				$this->data['config_t_tt'] = '';
			}
			
			if (isset($this->request->post['config_t2_tt'])) {
				$this->data['config_t2_tt'] = $this->request->post['config_t2_tt'];
				} elseif (isset($store_info['config_t2_tt'])) {
				$this->data['config_t2_tt'] = $store_info['config_t2_tt'];		
				} else {
				$this->data['config_t2_tt'] = '';
			}
			
			if (isset($this->request->post['config_t_bt'])) {
				$this->data['config_t_bt'] = $this->request->post['config_t_bt'];
				} elseif (isset($store_info['config_t_bt'])) {
				$this->data['config_t_bt'] = $store_info['config_t_bt'];		
				} else {
				$this->data['config_t_bt'] = '';
			}
			
			if (isset($this->request->post['config_t2_bt'])) {
				$this->data['config_t2_bt'] = $this->request->post['config_t2_bt'];
				} elseif (isset($store_info['config_t2_bt'])) {
				$this->data['config_t2_bt'] = $store_info['config_t2_bt'];		
				} else {
				$this->data['config_t2_bt'] = '';
			}
			
			
			if (isset($this->request->post['config_worktime'])) {
				$this->data['config_worktime'] = $this->request->post['config_worktime'];
				} elseif (isset($store_info['config_worktime'])) {
				$this->data['config_worktime'] = $store_info['config_worktime'];		
				} else {
				$this->data['config_worktime'] = '';
			}
			
			if (isset($this->request->post['config_fax'])) {
				$this->data['config_fax'] = $this->request->post['config_fax'];
				} elseif (isset($store_info['config_fax'])) {
				$this->data['config_fax'] = $store_info['config_fax'];		
				} else {
				$this->data['config_fax'] = '';
			}
			
			
			$this->load->model('catalog/manufacturer');
			$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
			
			if (isset($this->request->post['config_monobrand'])) {
				$this->data['config_monobrand'] = $this->request->post['config_monobrand'];
				} elseif (isset($store_info['config_monobrand'])) {
				$this->data['config_monobrand'] = $store_info['config_monobrand'];
				} else {
				$this->data['config_monobrand'] = '';
			}
			
			if (isset($this->request->post['config_dadata'])) {
				$this->data['config_dadata'] = $this->request->post['config_dadata'];
				} elseif (isset($store_info['config_dadata'])) {
				$this->data['config_dadata'] = $store_info['config_dadata'];
				} else {
				$this->data['config_dadata'] = 0;
			}
			
			if (isset($this->request->post['config_special_controller_logic'])) {
				$this->data['config_special_controller_logic'] = $this->request->post['config_special_controller_logic'];
				} elseif (isset($store_info['config_special_controller_logic'])) {
				$this->data['config_special_controller_logic'] = $store_info['config_special_controller_logic'];
				} else {
				$this->data['config_special_controller_logic'] = 'default';
			}

			if (isset($this->request->post['config_special_category_id'])) {
				$this->data['config_special_category_id'] = $this->request->post['config_special_category_id'];
				} elseif (isset($store_info['config_special_category_id'])) {
				$this->data['config_special_category_id'] = $store_info['config_special_category_id'];
				} else {
				$this->data['config_special_category_id'] = '';
			}

			if (isset($this->request->post['config_show_goods_overload'])) {
				$this->data['config_show_goods_overload'] = $this->request->post['config_show_goods_overload'];
				} elseif (isset($store_info['config_show_goods_overload'])) {
				$this->data['config_show_goods_overload'] = $store_info['config_show_goods_overload'];
				} else {
				$this->data['config_show_goods_overload'] = '';
			}

			if (isset($this->request->post['config_disable_fast_orders'])) {
				$this->data['config_disable_fast_orders'] = $this->request->post['config_disable_fast_orders'];
				} elseif (isset($store_info['config_disable_fast_orders'])) {
				$this->data['config_disable_fast_orders'] = $store_info['config_disable_fast_orders'];
				} else {
				$this->data['config_disable_fast_orders'] = '';
			}
			
			if (isset($this->request->post['config_title'])) {
				$this->data['config_title'] = $this->request->post['config_title'];
				} elseif (isset($store_info['config_title'])) {
				$this->data['config_title'] = $store_info['config_title'];
				} else {
				$this->data['config_title'] = '';
			}
			
			if (isset($this->request->post['config_meta_description'])) {
				$this->data['config_meta_description'] = $this->request->post['config_meta_description'];
				} elseif (isset($store_info['config_meta_description'])) {
				$this->data['config_meta_description'] = $store_info['config_meta_description'];		
				} else {
				$this->data['config_meta_description'] = '';
			}
			
			if (isset($this->request->post['config_layout_id'])) {
				$this->data['config_layout_id'] = $this->request->post['config_layout_id'];
				} elseif (isset($store_info['config_layout_id'])) {
				$this->data['config_layout_id'] = $store_info['config_layout_id'];
				} else {
				$this->data['config_layout_id'] = '';
			}
			
			$this->load->model('design/layout');
			
			$this->data['layouts'] = $this->model_design_layout->getLayouts();
			
			if (isset($this->request->post['config_template'])) {
				$this->data['config_template'] = $this->request->post['config_template'];
				} elseif (isset($store_info['config_template'])) {
				$this->data['config_template'] = $store_info['config_template'];
				} else {
				$this->data['config_template'] = '';
			}
			
			$this->data['templates'] = array();
			
			$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
			
			foreach ($directories as $directory) {
				$this->data['templates'][] = basename($directory);
			}	
			
			if (isset($this->request->post['config_country_id'])) {
				$this->data['config_country_id'] = $this->request->post['config_country_id'];
				} elseif (isset($store_info['config_country_id'])) {
				$this->data['config_country_id'] = $store_info['config_country_id'];		
				} else {
				$this->data['config_country_id'] = $this->config->get('config_country_id');
			}
			
			$this->load->model('localisation/country');
			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			if (isset($this->request->post['config_zone_id'])) {
				$this->data['config_zone_id'] = $this->request->post['config_zone_id'];
				} elseif (isset($store_info['config_zone_id'])) {
				$this->data['config_zone_id'] = $store_info['config_zone_id'];				
				} else {
				$this->data['config_zone_id'] = $this->config->get('config_zone_id');
			}

			$this->load->model('localisation/zone');		
			$this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['config_country_id']);
			
			if (isset($this->request->post['config_countryname'])) {
				$this->data['config_countryname'] = $this->request->post['config_countryname'];
				} elseif (isset($store_info['config_countryname'])) {
				$this->data['config_countryname'] = $store_info['config_countryname'];				
				} else {
				$this->data['config_countryname'] = $this->config->get('config_countryname');
			}
			
			if (isset($this->request->post['config_googlelocal_code'])) {
				$this->data['config_googlelocal_code'] = $this->request->post['config_googlelocal_code'];
				} elseif (isset($store_info['config_googlelocal_code'])) {
				$this->data['config_googlelocal_code'] = $store_info['config_googlelocal_code'];				
				} else {
				$this->data['config_googlelocal_code'] = '123456';
			}
			
			if (isset($this->request->post['config_warehouse_identifier'])) {
				$this->data['config_warehouse_identifier'] = $this->request->post['config_warehouse_identifier'];
				} elseif (isset($store_info['config_warehouse_identifier'])) {
				$this->data['config_warehouse_identifier'] = $store_info['config_warehouse_identifier'];				
				} else {
				$this->data['config_warehouse_identifier'] = $this->config->get('config_warehouse_identifier');
			}
			
			if (isset($this->request->post['config_warehouse_identifier_local'])) {
				$this->data['config_warehouse_identifier_local'] = $this->request->post['config_warehouse_identifier_local'];
				} elseif (isset($store_info['config_warehouse_identifier_local'])) {
				$this->data['config_warehouse_identifier_local'] = $store_info['config_warehouse_identifier_local'];				
				} else {
				$this->data['config_warehouse_identifier_local'] = $this->config->get('config_warehouse_identifier_local');
			}

			if (isset($this->request->post['config_warehouse_only'])) {
				$this->data['config_warehouse_only'] = $this->request->post['config_warehouse_only'];
				} elseif (isset($store_info['config_warehouse_only'])) {
				$this->data['config_warehouse_only'] = $store_info['config_warehouse_only'];				
				} else {
				$this->data['config_warehouse_only'] = $this->config->get('config_warehouse_only');
			}

				$this->load->model('localisation/stock_status');
			
			$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

			if (isset($this->request->post['config_overload_stock_status_id'])) {
				$this->data['config_overload_stock_status_id'] = $this->request->post['config_overload_stock_status_id'];
				} elseif (isset($store_info['config_overload_stock_status_id'])) {
				$this->data['config_overload_stock_status_id'] = $store_info['config_overload_stock_status_id'];				
				} else {
				$this->data['config_overload_stock_status_id'] = $this->config->get('config_overload_stock_status_id');
			}
			
			if (isset($this->request->post['config_payment_list'])) {
				$this->data['config_payment_list'] = $this->request->post['config_payment_list'];
				} elseif (isset($store_info['config_payment_list'])) {
				$this->data['config_payment_list'] = $store_info['config_payment_list'];				
				} else {
				$this->data['config_payment_list'] = $this->config->get('config_payment_list');
			}
			
			if (isset($this->request->post['config_language'])) {
				$this->data['config_language'] = $this->request->post['config_language'];
				} elseif (isset($store_info['config_language'])) {
				$this->data['config_language'] = $store_info['config_language'];			
				} else {
				$this->data['config_language'] = $this->config->get('config_language');
			}
			
			if (isset($this->request->post['config_second_language'])) {
				$this->data['config_second_language'] = $this->request->post['config_second_language'];
				} elseif (isset($store_info['config_second_language'])) {
				$this->data['config_second_language'] = $store_info['config_second_language'];			
				} else {
				$this->data['config_second_language'] = $this->config->get('config_second_language');
			}

			if (isset($this->request->post['config_do_redirection_to_second_language'])) {
				$this->data['config_do_redirection_to_second_language'] = $this->request->post['config_do_redirection_to_second_language'];
				} elseif (isset($store_info['config_do_redirection_to_second_language'])) {
				$this->data['config_do_redirection_to_second_language'] = $store_info['config_do_redirection_to_second_language'];			
				} else {
				$this->data['config_do_redirection_to_second_language'] = $this->config->get('config_do_redirection_to_second_language');
			}		
			
			if (isset($this->request->post['config_currency'])) {
				$this->data['config_currency'] = $this->request->post['config_currency'];
				} elseif (isset($store_info['config_currency'])) {
				$this->data['config_currency'] = $store_info['config_currency'];			
				} else {
				$this->data['config_currency'] = $this->config->get('config_currency');
			}
			
			if (isset($this->request->post['config_regional_currency'])) {
				$this->data['config_regional_currency'] = $this->request->post['config_regional_currency'];
				} elseif (isset($store_info['config_regional_currency'])) {
				$this->data['config_regional_currency'] = $store_info['config_regional_currency'];			
				} else {
				$this->data['config_regional_currency'] = $this->config->get('config_regional_currency');
			}
			
			
			if (isset($this->request->post['show_menu_in_left'])) {
				$this->data['show_menu_in_left'] = $this->request->post['show_menu_in_left'];
				} elseif (isset($store_info['show_menu_in_left'])) {
				$this->data['show_menu_in_left'] = $store_info['show_menu_in_left'];			
				} else {
				$this->data['show_menu_in_left'] = $this->config->get('show_menu_in_left');
			}
			
			if (isset($this->request->post['config_reward_logosvg'])) {
				$this->data['config_reward_logosvg'] = $this->request->post['config_reward_logosvg'];
				} elseif (isset($store_info['config_reward_logosvg'])) {
				$this->data['config_reward_logosvg'] = $store_info['config_reward_logosvg'];	
				} else {
				$this->data['config_reward_logosvg'] = 'money.svg';
			}
			
			if (isset($this->request->post['config_reward_lifetime'])) {
				$this->data['config_reward_lifetime'] = $this->request->post['config_reward_lifetime'];
				} elseif (isset($store_info['config_reward_lifetime'])) {
				$this->data['config_reward_lifetime'] = $store_info['config_reward_lifetime'];	
				} else {
				$this->data['config_reward_lifetime'] = '365';
			}
			
			if (isset($this->request->post['config_reward_maxsalepercent'])) {
				$this->data['config_reward_maxsalepercent'] = $this->request->post['config_reward_maxsalepercent'];
				} elseif (isset($store_info['config_reward_maxsalepercent'])) {
				$this->data['config_reward_maxsalepercent'] = $store_info['config_reward_maxsalepercent'];	
				} else {
				$this->data['config_reward_maxsalepercent'] = '50';
			}
			
			if (isset($this->request->post['rewardpoints_pointspercent'])) {
				$this->data['rewardpoints_pointspercent'] = $this->request->post['rewardpoints_pointspercent'];
				} elseif (isset($store_info['rewardpoints_pointspercent'])) {
				$this->data['rewardpoints_pointspercent'] = $store_info['rewardpoints_pointspercent'];	
				} else {
				$this->data['rewardpoints_pointspercent'] = '3';
			}
			
			if (isset($this->request->post['rewardpoints_appinstall'])) {
				$this->data['rewardpoints_appinstall'] = $this->request->post['rewardpoints_appinstall'];
				} elseif (isset($store_info['rewardpoints_appinstall'])) {
				$this->data['rewardpoints_appinstall'] = $store_info['rewardpoints_appinstall'];	
				} else {
				$this->data['rewardpoints_appinstall'] = '0';
			}
			
			if (isset($this->request->post['rewardpoints_birthday'])) {
				$this->data['rewardpoints_birthday'] = $this->request->post['rewardpoints_birthday'];
				} elseif (isset($store_info['rewardpoints_birthday'])) {
				$this->data['rewardpoints_birthday'] = $store_info['rewardpoints_birthday'];	
				} else {
				$this->data['rewardpoints_birthday'] = '0';
			}
			
			$termskeys = array(
			'config_delivery_instock_term',
			'config_delivery_central_term',
			'config_delivery_russia_term',
			'config_delivery_ukrainian_term',
			'config_delivery_outstock_term',
			'config_delivery_outstock_enable',
			'config_delivery_display_logic'			
			);
			
			foreach ($termskeys as $termkey){
				
				if (isset($this->request->post[$termkey])) {
					$this->data[$termkey] = $this->request->post[$termkey];
					} elseif (isset($store_info[$termkey])) {
					$this->data[$termkey] = $store_info[$termkey];	
					} else {
					$this->data[$termkey] = '';
				}
				
			}
			
			for ($i=1; $i<=12; $i++){
				if (isset($this->request->post['config_pickup_dayoff_' . $i])) {
					$this->data['config_pickup_dayoff_' . $i] = $this->request->post['config_pickup_dayoff_' . $i];
					} elseif (isset($store_info['config_pickup_dayoff_' . $i])) {
					$this->data['config_pickup_dayoff_' . $i] = $store_info['config_pickup_dayoff_' . $i];	
					} else {
					$this->data['config_pickup_dayoff_' . $i] = '';
				}
			}
			
			if (isset($this->request->post['config_pickup_enable'])) {
				$this->data['config_pickup_enable'] = $this->request->post['config_pickup_enable'];
				} elseif (isset($store_info['config_pickup_enable'])) {
				$this->data['config_pickup_enable'] = $store_info['config_pickup_enable'];	
				} else {
				$this->data['config_pickup_enable'] = '0';
			}
			
			if (isset($this->request->post['config_pickup_times'])) {
				$this->data['config_pickup_times'] = $this->request->post['config_pickup_times'];
				} elseif (isset($store_info['config_pickup_times'])) {
				$this->data['config_pickup_times'] = $store_info['config_pickup_times'];	
				} else {
				$this->data['config_pickup_times'] = '';
			}
			
			$this->load->model('localisation/currency');
			
			$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
			
			if (isset($this->request->post['config_group_price_enable'])) {
				$this->data['config_group_price_enable'] = $this->request->post['config_group_price_enable'];
				} elseif (isset($store_info['config_group_price_enable'])) {
				$this->data['config_group_price_enable'] = $store_info['config_group_price_enable'];	
				} else {
				$this->data['config_group_price_enable'] = '';
			}
			
			if (isset($this->request->post['config_android_playstore_enable'])) {
				$this->data['config_android_playstore_enable'] = $this->request->post['config_android_playstore_enable'];
				} elseif (isset($store_info['config_android_playstore_enable'])) {
				$this->data['config_android_playstore_enable'] = $store_info['config_android_playstore_enable'];	
				} else {
				$this->data['config_android_playstore_enable'] = '';
			}
			
			if (isset($this->request->post['config_android_playstore_code'])) {
				$this->data['config_android_playstore_code'] = $this->request->post['config_android_playstore_code'];
				} elseif (isset($store_info['config_android_playstore_code'])) {
				$this->data['config_android_playstore_code'] = $store_info['config_android_playstore_code'];	
				} else {
				$this->data['config_android_playstore_code'] = '';
			}
			
			if (isset($this->request->post['config_android_playstore_link'])) {
				$this->data['config_android_playstore_link'] = $this->request->post['config_android_playstore_link'];
				} elseif (isset($store_info['config_android_playstore_link'])) {
				$this->data['config_android_playstore_link'] = $store_info['config_android_playstore_link'];	
				} else {
				$this->data['config_android_playstore_link'] = '';
			}
			
			if (isset($this->request->post['config_firebase_code'])) {
				$this->data['config_firebase_code'] = $this->request->post['config_firebase_code'];
				} elseif (isset($store_info['config_firebase_code'])) {
				$this->data['config_firebase_code'] = $store_info['config_firebase_code'];	
				} else {
				$this->data['config_firebase_code'] = '';
			}
			
			if (isset($this->request->post['config_microsoft_store_enable'])) {
				$this->data['config_microsoft_store_enable'] = $this->request->post['config_microsoft_store_enable'];
				} elseif (isset($store_info['config_microsoft_store_enable'])) {
				$this->data['config_microsoft_store_enable'] = $store_info['config_microsoft_store_enable'];	
				} else {
				$this->data['config_microsoft_store_enable'] = '';
			}
			
			if (isset($this->request->post['config_microsoft_store_code'])) {
				$this->data['config_microsoft_store_code'] = $this->request->post['config_microsoft_store_code'];
				} elseif (isset($store_info['config_microsoft_store_code'])) {
				$this->data['config_microsoft_store_code'] = $store_info['config_microsoft_store_code'];	
				} else {
				$this->data['config_microsoft_store_code'] = '';
			}	
			
			if (isset($this->request->post['config_microsoft_store_link'])) {
				$this->data['config_microsoft_store_link'] = $this->request->post['config_microsoft_store_link'];
				} elseif (isset($store_info['config_microsoft_store_link'])) {
				$this->data['config_microsoft_store_link'] = $store_info['config_microsoft_store_link'];	
				} else {
				$this->data['config_microsoft_store_link'] = '';
			}
			
			if (isset($this->request->post['config_catalog_limit'])) {
				$this->data['config_catalog_limit'] = $this->request->post['config_catalog_limit'];
				} elseif (isset($store_info['config_catalog_limit'])) {
				$this->data['config_catalog_limit'] = $store_info['config_catalog_limit'];	
				} else {
				$this->data['config_catalog_limit'] = '12';
			}		
			
			if (isset($this->request->post['config_special_attr_id'])) {
				$this->data['config_special_attr_id'] = $this->request->post['config_special_attr_id'];
				} elseif (isset($store_info['config_special_attr_id'])) {
				$this->data['config_special_attr_id'] = $store_info['config_special_attr_id'];	
				} else {
				$this->data['config_special_attr_id'] = '5';
			}		
			
			if (isset($this->request->post['config_tax'])) {
				$this->data['config_tax'] = $this->request->post['config_tax'];
				} elseif (isset($store_info['config_tax'])) {
				$this->data['config_tax'] = $store_info['config_tax'];			
				} else {
				$this->data['config_tax'] = '';
			}
			
			if (isset($this->request->post['config_tax_default'])) {
				$this->data['config_tax_default'] = $this->request->post['config_tax_default'];
				} elseif (isset($store_info['config_tax_default'])) {
				$this->data['config_tax_default'] = $store_info['config_tax_default'];			
				} else {
				$this->data['config_tax_default'] = '';
			}
			
			if (isset($this->request->post['config_tax_customer'])) {
				$this->data['config_tax_customer'] = $this->request->post['config_tax_customer'];
				} elseif (isset($store_info['config_tax_customer'])) {
				$this->data['config_tax_customer'] = $store_info['config_tax_customer'];			
				} else {
				$this->data['config_tax_customer'] = '';
			}
			
			if (isset($this->request->post['config_customer_group_id'])) {
				$this->data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
				} elseif (isset($store_info['config_customer_group_id'])) {
				$this->data['config_customer_group_id'] = $store_info['config_customer_group_id'];			
				} else {
				$this->data['config_customer_group_id'] = '';
			}
			
			if (isset($this->request->post['config_opt_group_id'])) {
				$this->data['config_customer_group_id'] = $this->request->post['config_opt_group_id'];
				} elseif (isset($store_info['config_opt_group_id'])) {
				$this->data['config_opt_group_id'] = $store_info['config_opt_group_id'];			
				} else {
				$this->data['config_opt_group_id'] = '';
			}
			
			$this->load->model('sale/customer_group');
			
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
			
			if (isset($this->request->post['config_customer_group_display'])) {
				$this->data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
				} elseif (isset($store_info['config_customer_group_display'])) {
				$this->data['config_customer_group_display'] = $store_info['config_customer_group_display'];	
				} else {
				$this->data['config_customer_group_display'] = array();			
			}
			
			if (isset($this->request->post['config_customer_price'])) {
				$this->data['config_customer_price'] = $this->request->post['config_customer_price'];
				} elseif (isset($store_info['config_customer_price'])) {
				$this->data['config_customer_price'] = $store_info['config_customer_price'];			
				} else {
				$this->data['config_customer_price'] = '';
			}
			
			if (isset($this->request->post['config_account_id'])) {
				$this->data['config_account_id'] = $this->request->post['config_account_id'];
				} elseif (isset($store_info['config_account_id'])) {
				$this->data['config_account_id'] = $store_info['config_account_id'];			
				} else {
				$this->data['config_account_id'] = '';
			}		
			
			$this->load->model('catalog/information');
			
			$this->data['informations'] = $this->model_catalog_information->getInformations();
			
			if (isset($this->request->post['config_cart_weight'])) {
				$this->data['config_cart_weight'] = $this->request->post['config_cart_weight'];
				} elseif (isset($store_info['config_cart_weight'])) {
				$this->data['config_cart_weight'] = $store_info['config_cart_weight'];	
				} else {
				$this->data['config_cart_weight'] = '';
			}
			
			if (isset($this->request->post['config_guest_checkout'])) {
				$this->data['config_guest_checkout'] = $this->request->post['config_guest_checkout'];
				} elseif (isset($store_info['config_guest_checkout'])) {
				$this->data['config_guest_checkout'] = $store_info['config_guest_checkout'];		
				} else {
				$this->data['config_guest_checkout'] = '';
			}
			
			if (isset($this->request->post['config_checkout_id'])) {
				$this->data['config_checkout_id'] = $this->request->post['config_checkout_id'];
				} elseif (isset($store_info['config_checkout_id'])) {
				$this->data['config_checkout_id'] = $store_info['config_checkout_id'];		
				} else {
				$this->data['config_checkout_id'] = '';
			}
			
			
			if (isset($this->request->post['config_confirmed_delivery_payment_ids'])) {
				$this->data['config_confirmed_delivery_payment_ids'] = $this->request->post['config_confirmed_delivery_payment_ids'];
				} elseif (isset($store_info['config_confirmed_delivery_payment_ids'])) {
				$this->data['config_confirmed_delivery_payment_ids'] = $store_info['config_confirmed_delivery_payment_ids'];		
				} else {
				$this->data['config_confirmed_delivery_payment_ids'] = '';
			}
						
			
			$this->load->model('localisation/order_status');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
			if (isset($this->request->post['config_stock_display'])) {
				$this->data['config_stock_display'] = $this->request->post['config_stock_display'];
				} elseif (isset($store_info['config_stock_display'])) {
				$this->data['config_stock_display'] = $store_info['config_stock_display'];			
				} else {
				$this->data['config_stock_display'] = '';
			}
			
			if (isset($this->request->post['config_stock_checkout'])) {
				$this->data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
				} elseif (isset($store_info['config_stock_checkout'])) {
				$this->data['config_stock_checkout'] = $store_info['config_stock_checkout'];		
				} else {
				$this->data['config_stock_checkout'] = '';
			}

			if (isset($this->request->post['config_telephony_engine'])) {
				$this->data['config_telephony_engine'] = $this->request->post['config_telephony_engine'];
				} elseif (isset($store_info['config_telephony_engine'])) {
				$this->data['config_telephony_engine'] = $store_info['config_telephony_engine'];			
				} else {
				$this->data['config_telephony_engine'] = '';
			}

			if (isset($this->request->post['config_binotel_api_key'])) {
				$this->data['config_binotel_api_key'] = $this->request->post['config_binotel_api_key'];
				} elseif (isset($store_info['config_binotel_api_key'])) {
				$this->data['config_binotel_api_key'] = $store_info['config_binotel_api_key'];			
				} else {
				$this->data['config_binotel_api_key'] = '';
			}

			if (isset($this->request->post['config_binotel_api_secret'])) {
				$this->data['config_binotel_api_secret'] = $this->request->post['config_binotel_api_secret'];
				} elseif (isset($store_info['config_binotel_api_secret'])) {
				$this->data['config_binotel_api_secret'] = $store_info['config_binotel_api_secret'];			
				} else {
				$this->data['config_binotel_api_secret'] = '';
			}

			

			
			if (isset($this->request->post['config_default_queue'])) {
				$this->data['config_default_queue'] = $this->request->post['config_default_queue'];
				} elseif (isset($store_info['config_default_queue'])) {
				$this->data['config_default_queue'] = $store_info['config_default_queue'];			
				} else {
				$this->data['config_default_queue'] = '';
			}
			
			if (isset($this->request->post['config_default_alert_queue'])) {
				$this->data['config_default_alert_queue'] = $this->request->post['config_default_alert_queue'];
				} elseif (isset($store_info['config_default_alert_queue'])) {
				$this->data['config_default_alert_queue'] = $store_info['config_default_alert_queue'];			
				} else {
				$this->data['config_default_alert_queue'] = '';
			}
			
			if (isset($this->request->post['config_default_manager_group'])) {
				$this->data['config_default_manager_group'] = $this->request->post['config_default_manager_group'];
				} elseif (isset($store_info['config_default_manager_group'])) {
				$this->data['config_default_manager_group'] = $store_info['config_default_manager_group'];			
				} else {
				$this->data['config_default_manager_group'] = '';
			}
			
			$this->load->model('user/user_group');
			
			$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
			
			$this->load->model('tool/image');
			
			if (isset($this->request->post['config_logo'])) {
				$this->data['config_logo'] = $this->request->post['config_logo'];
				} elseif (isset($store_info['config_logo'])) {
				$this->data['config_logo'] = $store_info['config_logo'];			
				} else {
				$this->data['config_logo'] = '';
			}
			
			$this->data['logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);
			
			if (isset($this->request->post['config_icon'])) {
				$this->data['config_icon'] = $this->request->post['config_icon'];
				} elseif (isset($store_info['config_icon'])) {
				$this->data['config_icon'] = $store_info['config_icon'];			
				} else {
				$this->data['config_icon'] = '';
			}
			
			if (isset($store_info['config_icon']) && file_exists(DIR_IMAGE . $store_info['config_icon']) && is_file(DIR_IMAGE . $store_info['config_icon'])) {
				$this->data['icon'] = $this->model_tool_image->resize($store_info['config_icon'], 100, 100);
				} else {
				$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			if (isset($this->request->post['config_noimage'])) {
				$this->data['config_noimage'] = $this->request->post['config_noimage'];
				} elseif (isset($store_info['config_noimage'])) {
				$this->data['config_noimage'] = $store_info['config_noimage'];			
				} else {
				$this->data['config_noimage'] = '';
			}
			
			if (isset($store_info['config_noimage']) && file_exists(DIR_IMAGE . $store_info['config_noimage']) && is_file(DIR_IMAGE . $store_info['config_noimage'])) {
				$this->data['noimage'] = $this->model_tool_image->resize($store_info['config_noimage'], 100, 100);
				} else {
				$this->data['noimage'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			
			if (isset($this->request->post['config_image_category_height'])) {
				$this->data['config_image_category_height'] = $this->request->post['config_image_category_height'];
				} elseif (isset($store_info['config_image_category_height'])) {
				$this->data['config_image_category_height'] = $store_info['config_image_category_height'];			
				} else {
				$this->data['config_image_category_height'] = 80;
			}	
			
			if (isset($this->request->post['config_image_thumb_width'])) {
				$this->data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
				} elseif (isset($store_info['config_image_thumb_width'])) {
				$this->data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];			
				} else {
				$this->data['config_image_thumb_width'] = 228;
			}
			
			if (isset($this->request->post['config_image_thumb_height'])) {
				$this->data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
				} elseif (isset($store_info['config_image_thumb_height'])) {
				$this->data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];				
				} else {
				$this->data['config_image_thumb_height'] = 228;
			}
			
			if (isset($this->request->post['config_image_popup_width'])) {
				$this->data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
				} elseif (isset($store_info['config_image_popup_width'])) {
				$this->data['config_image_popup_width'] = $store_info['config_image_popup_width'];			
				} else {
				$this->data['config_image_popup_width'] = 500;
			}
			
			if (isset($this->request->post['config_image_popup_height'])) {
				$this->data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
				} elseif (isset($store_info['config_image_popup_height'])) {
				$this->data['config_image_popup_height'] = $store_info['config_image_popup_height'];			
				} else {
				$this->data['config_image_popup_height'] = 500;
			}
			
			if (isset($this->request->post['config_image_product_width'])) {
				$this->data['config_image_product_width'] = $this->request->post['config_image_product_width'];
				} elseif (isset($store_info['config_image_product_width'])) {
				$this->data['config_image_product_width'] = $store_info['config_image_product_width'];		
				} else {
				$this->data['config_image_product_width'] = 80;
			}
			
			if (isset($this->request->post['config_image_product_height'])) {
				$this->data['config_image_product_height'] = $this->request->post['config_image_product_height'];
				} elseif (isset($store_info['config_image_product_height'])) {
				$this->data['config_image_product_height'] = $store_info['config_image_product_height'];		
				} else {
				$this->data['config_image_product_height'] = 80;
			}
			
			if (isset($this->request->post['config_image_category_width'])) {
				$this->data['config_image_category_width'] = $this->request->post['config_image_category_width'];
				} elseif (isset($store_info['config_image_category_width'])) {
				$this->data['config_image_category_width'] = $store_info['config_image_category_width'];			
				} else {
				$this->data['config_image_category_width'] = 80;
			}
			
			if (isset($this->request->post['config_image_additional_width'])) {
				$this->data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
				} elseif (isset($store_info['config_image_additional_width'])) {
				$this->data['config_image_additional_width'] = $store_info['config_image_additional_width'];			
				} else {
				$this->data['config_image_additional_width'] = 74;
			}
			
			if (isset($this->request->post['config_image_additional_height'])) {
				$this->data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
				} elseif (isset($store_info['config_image_additional_height'])) {
				$this->data['config_image_additional_height'] = $store_info['config_image_additional_height'];				
				} else {
				$this->data['config_image_additional_height'] = 74;
			}
			
			if (isset($this->request->post['config_image_related_width'])) {
				$this->data['config_image_related_width'] = $this->request->post['config_image_related_width'];
				} elseif (isset($store_info['config_image_related_width'])) {
				$this->data['config_image_related_width'] = $store_info['config_image_related_width'];		
				} else {
				$this->data['config_image_related_width'] = 80;
			}
			
			if (isset($this->request->post['config_image_related_height'])) {
				$this->data['config_image_related_height'] = $this->request->post['config_image_related_height'];
				} elseif (isset($store_info['config_image_related_height'])) {
				$this->data['config_image_related_height'] = $store_info['config_image_related_height'];			
				} else {
				$this->data['config_image_related_height'] = 80;
			}
			
			if (isset($this->request->post['config_image_compare_width'])) {
				$this->data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
				} elseif (isset($store_info['config_image_compare_width'])) {
				$this->data['config_image_compare_width'] = $store_info['config_image_compare_width'];			
				} else {
				$this->data['config_image_compare_width'] = 90;
			}
			
			if (isset($this->request->post['config_image_compare_height'])) {
				$this->data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
				} elseif (isset($store_info['config_image_compare_height'])) {
				$this->data['config_image_compare_height'] = $store_info['config_image_compare_height'];			
				} else {
				$this->data['config_image_compare_height'] = 90;
			}
			
			if (isset($this->request->post['config_image_wishlist_width'])) {
				$this->data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
				} elseif (isset($store_info['config_image_wishlist_width'])) {
				$this->data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];			
				} else {
				$this->data['config_image_wishlist_width'] = 50;
			}
			
			if (isset($this->request->post['config_image_wishlist_height'])) {
				$this->data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
				} elseif (isset($store_info['config_image_wishlist_height'])) {
				$this->data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];			
				} else {
				$this->data['config_image_wishlist_height'] = 50;
			}
			
			if (isset($this->request->post['config_image_cart_width'])) {
				$this->data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
				} elseif (isset($store_info['config_image_cart_width'])) {
				$this->data['config_image_cart_width'] = $store_info['config_image_cart_width'];			
				} else {
				$this->data['config_image_cart_width'] = 80;
			}
			
			if (isset($this->request->post['config_image_cart_height'])) {
				$this->data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
				} elseif (isset($store_info['config_image_cart_height'])) {
				$this->data['config_image_cart_height'] = $store_info['config_image_cart_height'];			
				} else {
				$this->data['config_image_cart_height'] = 80;
			}
			
			if (isset($this->request->post['config_secure'])) {
				$this->data['config_secure'] = $this->request->post['config_secure'];
				} elseif (isset($store_info['config_secure'])) {
				$this->data['config_secure'] = $store_info['config_secure'];
				} else {
				$this->data['config_secure'] = '';
			}

			$social_auth_config = [
			'social_auth_facebook_app_id',
			'social_auth_facebook_secret_key',
			'social_auth_google_app_id',
			'social_auth_google_secret_key',
			'social_auth_insatagram_client_id',
			'social_auth_insatagram_secret_key'
		];

		foreach ($social_auth_config as $social_auth_config_key){
			if (isset($this->request->post[$social_auth_config_key])) {
				$this->data[$social_auth_config_key] = $this->request->post[$social_auth_config_key];
				} elseif (isset($store_info[$social_auth_config_key])) {
				$this->data[$social_auth_config_key] = $store_info[$social_auth_config_key];
				} else {
				$this->data[$social_auth_config_key] = '';
			} 		
		}

		 $social_links_config = [
            'social_link_facebook',
            'social_link_vkontakte',
            'social_link_instagram',
            'social_link_youtube',
            'social_link_twitter',
            'social_link_viber_bot',
            'social_link_telegram_bot',
            'social_link_messenger_bot',
            'social_link_vkontakte_bot',
            'social_link_whatsapp_bot',
        ];

        foreach ($social_links_config as $social_links_config_key){
			if (isset($this->request->post[$social_links_config_key])) {
				$this->data[$social_links_config_key] = $this->request->post[$social_links_config_key];
				} elseif (isset($store_info[$social_links_config_key])) {
				$this->data[$social_links_config_key] = $store_info[$social_links_config_key];
				} else {
				$this->data[$social_links_config_key] = '';
			} 		
		}
			
			if (isset($this->request->post['config_google_analytics'])) {
				$this->data['config_google_analytics'] = $this->request->post['config_google_analytics'];
				} elseif (isset($store_info['config_google_analytics'])) {
				$this->data['config_google_analytics'] = $store_info['config_google_analytics'];
				} else {
				$this->data['config_google_analytics'] = '';
			} 
			
			if (isset($this->request->post['config_google_analytics_header'])) {
				$this->data['config_google_analytics_header'] = $this->request->post['config_google_analytics_header'];
				} elseif (isset($store_info['config_google_analytics_header'])) {
				$this->data['config_google_analytics_header'] = $store_info['config_google_analytics_header'];
				} else {
				$this->data['config_google_analytics_header'] = '';
			} 
			
			if (isset($this->request->post['config_gtm_header'])) {
				$this->data['config_gtm_header'] = $this->request->post['config_gtm_header'];
				} elseif (isset($store_info['config_gtm_header'])) {
				$this->data['config_gtm_header'] = $store_info['config_gtm_header'];
				} else {
				$this->data['config_gtm_header'] = '';
			} 
			
			if (isset($this->request->post['config_gtm_body'])) {
				$this->data['config_gtm_body'] = $this->request->post['config_gtm_body'];
				} elseif (isset($store_info['config_gtm_body'])) {
				$this->data['config_gtm_body'] = $store_info['config_gtm_body'];
				} else {
				$this->data['config_gtm_body'] = '';
			} 

			if (isset($this->request->post['config_fb_pixel_body'])) {
				$this->data['config_fb_pixel_body'] = $this->request->post['config_fb_pixel_body'];
				} elseif (isset($store_info['config_fb_pixel_body'])) {
				$this->data['config_fb_pixel_body'] = $store_info['config_fb_pixel_body'];
				} else {
				$this->data['config_fb_pixel_body'] = '';
			} 

			if (isset($this->request->post['config_fb_pixel_header'])) {
				$this->data['config_fb_pixel_header'] = $this->request->post['config_fb_pixel_header'];
				} elseif (isset($store_info['config_fb_pixel_header'])) {
				$this->data['config_fb_pixel_header'] = $store_info['config_fb_pixel_header'];
				} else {
				$this->data['config_fb_pixel_header'] = '';
			} 

			if (isset($this->request->post['config_vk_enable_pixel'])) {
				$this->data['config_vk_enable_pixel'] = $this->request->post['config_vk_enable_pixel'];
				} elseif (isset($store_info['config_vk_enable_pixel'])) {
				$this->data['config_vk_enable_pixel'] = $store_info['config_vk_enable_pixel'];
				} else {
				$this->data['config_vk_enable_pixel'] = 0;
			} 

			if (isset($this->request->post['config_vk_pixel_id'])) {
				$this->data['config_vk_pixel_id'] = $this->request->post['config_vk_pixel_id'];
				} elseif (isset($store_info['config_vk_enable_pixel'])) {
				$this->data['config_vk_pixel_id'] = $store_info['config_vk_pixel_id'];
				} else {
				$this->data['config_vk_pixel_id'] = '';
			} 

			if (isset($this->request->post['config_vk_pricelist_id'])) {
				$this->data['config_vk_pricelist_id'] = $this->request->post['config_vk_pricelist_id'];
				} elseif (isset($store_info['config_vk_pricelist_id'])) {
				$this->data['config_vk_pricelist_id'] = $store_info['config_vk_pricelist_id'];
				} else {
				$this->data['config_vk_pricelist_id'] = '';
			} 

			if (isset($this->request->post['config_vk_pixel_header'])) {
				$this->data['config_vk_pixel_header'] = $this->request->post['config_vk_pixel_header'];
				} elseif (isset($store_info['config_vk_pixel_header'])) {
				$this->data['config_vk_pixel_header'] = $store_info['config_vk_pixel_header'];
				} else {
				$this->data['config_vk_pixel_header'] = '';
			}

			if (isset($this->request->post['config_vk_pixel_body'])) {
				$this->data['config_vk_pixel_body'] = $this->request->post['config_vk_pixel_body'];
				} elseif (isset($store_info['config_vk_pixel_body'])) {
				$this->data['config_vk_pixel_body'] = $store_info['config_vk_pixel_body'];
				} else {
				$this->data['config_vk_pixel_body'] = '';
			}

			
			if (isset($this->request->post['config_preload_links'])) {
				$this->data['config_preload_links'] = $this->request->post['config_preload_links'];
				} elseif (isset($store_info['config_preload_links'])) {
				$this->data['config_preload_links'] = $store_info['config_preload_links'];
				} else {
				$this->data['config_preload_links'] = '';
			} 
			
			if (isset($this->request->post['config_sendpulse_script'])) {
				$this->data['config_sendpulse_script'] = $this->request->post['config_sendpulse_script'];
				} elseif (isset($store_info['config_sendpulse_script'])) {
				$this->data['config_sendpulse_script'] = $store_info['config_sendpulse_script'];
				} else {
				$this->data['config_sendpulse_script'] = '';
			} 
			
			if (isset($this->request->post['config_sendpulse_id'])) {
				$this->data['config_sendpulse_id'] = $this->request->post['config_sendpulse_id'];
				} elseif (isset($store_info['config_sendpulse_id'])) {
				$this->data['config_sendpulse_id'] = $store_info['config_sendpulse_id'];
				} else {
				$this->data['config_sendpulse_id'] = '';
			}
			
			if (isset($this->request->post['config_onesignal_app_id'])) {
				$this->data['config_onesignal_app_id'] = $this->request->post['config_onesignal_app_id'];
				} elseif (isset($store_info['config_onesignal_app_id'])) {
				$this->data['config_onesignal_app_id'] = $store_info['config_onesignal_app_id'];
				} else {
				$this->data['config_onesignal_app_id'] = '';
			}
			
			if (isset($this->request->post['config_onesignal_api_key'])) {
				$this->data['config_onesignal_api_key'] = $this->request->post['config_onesignal_api_key'];
				} elseif (isset($store_info['config_onesignal_api_key'])) {
				$this->data['config_onesignal_api_key'] = $store_info['config_onesignal_api_key'];
				} else {
				$this->data['config_onesignal_api_key'] = '';
			}
			
			if (isset($this->request->post['config_onesignal_safari_web_id'])) {
				$this->data['config_onesignal_safari_web_id'] = $this->request->post['config_onesignal_safari_web_id'];
				} elseif (isset($store_info['config_onesignal_safari_web_id'])) {
				$this->data['config_onesignal_safari_web_id'] = $store_info['config_onesignal_safari_web_id'];
				} else {
				$this->data['config_onesignal_safari_web_id'] = '';
			}
			
			if (isset($this->request->post['config_google_analitycs_id'])) {
				$this->data['config_google_analitycs_id'] = $this->request->post['config_google_analitycs_id'];
				} elseif (isset($store_info['config_google_analitycs_id'])) {
				$this->data['config_google_analitycs_id'] = $store_info['config_google_analitycs_id'];
				} else {
				$this->data['config_google_analitycs_id'] = '';
			} 
			
			if (isset($this->request->post['config_google_conversion_id'])) {
				$this->data['config_google_conversion_id'] = $this->request->post['config_google_conversion_id'];
				} elseif (isset($store_info['config_google_conversion_id'])) {
				$this->data['config_google_conversion_id'] = $store_info['config_google_conversion_id'];
				} else {
				$this->data['config_google_conversion_id'] = '';
			} 
			
			if (isset($this->request->post['config_google_merchant_id'])) {
				$this->data['config_google_merchant_id'] = $this->request->post['config_google_merchant_id'];
				} elseif (isset($store_info['config_google_merchant_id'])) {
				$this->data['config_google_merchant_id'] = $store_info['config_google_merchant_id'];
				} else {
				$this->data['config_google_merchant_id'] = '';
			} 
			
			if (isset($this->request->post['config_google_remarketing_type'])) {
				$this->data['config_google_remarketing_type'] = $this->request->post['config_google_remarketing_type'];
				} elseif (isset($store_info['config_google_remarketing_type'])) {
				$this->data['config_google_remarketing_type'] = $store_info['config_google_remarketing_type'];
				} else {
				$this->data['config_google_remarketing_type'] = '';
			} 
			
			if (isset($this->request->post['config_google_ecommerce_enable'])) {
				$this->data['config_google_ecommerce_enable'] = $this->request->post['config_google_ecommerce_enable'];
				} elseif (isset($store_info['config_google_ecommerce_enable'])) {
				$this->data['config_google_ecommerce_enable'] = $store_info['config_google_ecommerce_enable'];
				} else {
				$this->data['config_google_ecommerce_enable'] = 0;
			} 
			
			if (isset($this->request->post['config_seo_url'])) {
				$this->data['config_seo_url'] = $this->request->post['config_seo_url'];
				} elseif (isset($store_info['config_seo_url'])) {
				$this->data['config_seo_url'] = $store_info['config_seo_url'];
				} else {
				$this->data['config_seo_url'] = 1;
			}
			
			if (isset($this->request->post['config_seo_url_type'])) {
				$this->data['config_seo_url_type'] = $this->request->post['config_seo_url_type'];
				} elseif (isset($store_info['config_seo_url_type'])) {
				$this->data['config_seo_url_type'] = $store_info['config_seo_url_type'];
				} else {
				$this->data['config_seo_url_type'] = 'seo_url';
			}
			
			$this->data['seo_types'] = array();
			$this->data['seo_types'][] = array('type' => 'seo_url', 'name' => $this->language->get('text_seo_url'));
			$this->data['seo_types'][] = array('type' => 'seo_pro', 'name' => $this->language->get('text_seo_pro'));
			
			if (isset($this->request->post['config_seo_url_include_path'])) {
				$this->data['config_seo_url_include_path'] = $this->request->post['config_seo_url_include_path'];
				} elseif (isset($store_info['config_seo_url_include_path'])) {
				$this->data['config_seo_url_include_path'] = $store_info['config_seo_url_include_path'];
				} else {
				$this->data['config_seo_url_include_path'] = 1;
			}
			
			if (isset($this->request->post['config_seo_url_postfix'])) {
				$this->data['config_seo_url_postfix'] = $this->request->post['config_seo_url_postfix'];
				} elseif (isset($store_info['config_seo_url_postfix'])) {
				$this->data['config_seo_url_postfix'] = $store_info['config_seo_url_postfix'];
				} else {
				$this->data['config_seo_url_postfix'] = '';
			}
			
			if (isset($this->request->post['config_metrika_counter'])) {
				$this->data['config_metrika_counter'] = $this->request->post['config_metrika_counter'];
				} elseif (isset($store_info['config_metrika_counter'])) {
				$this->data['config_metrika_counter'] = $store_info['config_metrika_counter'];
				} else {
				$this->data['config_metrika_counter'] = '';
			}
			
			if (isset($this->request->post['config_webvisor_enable'])) {
				$this->data['config_webvisor_enable'] = $this->request->post['config_webvisor_enable'];
				} elseif (isset($store_info['config_webvisor_enable'])) {
				$this->data['config_webvisor_enable'] = $store_info['config_webvisor_enable'];
				} else {
				$this->data['config_webvisor_enable'] = 0;
			}
			
			if (isset($this->request->post['config_clickmap_enable'])) {
				$this->data['config_clickmap_enable'] = $this->request->post['config_clickmap_enable'];
				} elseif (isset($store_info['config_clickmap_enable'])) {
				$this->data['config_clickmap_enable'] = $store_info['config_clickmap_enable'];
				} else {
				$this->data['config_clickmap_enable'] = 0;
			}					
			
			$this->template = 'setting/store_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'setting/store')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->request->post['config_url']) {
				$this->error['url'] = $this->language->get('error_url');
			}
			
			if (!$this->request->post['config_name']) {
				$this->error['name'] = $this->language->get('error_name');
			}	
			
			if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
				$this->error['owner'] = $this->language->get('error_owner');
			}
			
			if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
				$this->error['address'] = $this->language->get('error_address');
			}
			
			if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_email'])) {
				$this->error['email'] = $this->language->get('error_email');
			}
			
			if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 140)) {
				$this->error['telephone'] = $this->language->get('error_telephone');
			}
			
			if (!$this->request->post['config_title']) {
				$this->error['title'] = $this->language->get('error_title');
			}	
			
			if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
				$this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
			}	
			
			if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
				$this->error['image_category'] = $this->language->get('error_image_category');
			}
			
			if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
				$this->error['image_thumb'] = $this->language->get('error_image_thumb');
			}	
			
			if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
				$this->error['image_popup'] = $this->language->get('error_image_popup');
			}
			
			if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
				$this->error['image_product'] = $this->language->get('error_image_product');
			}
			
			if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
				$this->error['image_additional'] = $this->language->get('error_image_additional');
			}
			
			if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
				$this->error['image_related'] = $this->language->get('error_image_related');
			}
			
			if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
				$this->error['image_compare'] = $this->language->get('error_image_compare');
			}
			
			if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
				$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
			}
			
			if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
				$this->error['image_cart'] = $this->language->get('error_image_cart');
			}
			
			if (!$this->request->post['config_catalog_limit']) {
				$this->error['catalog_limit'] = $this->language->get('error_limit');
			}
			
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'setting/store')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$this->load->model('sale/order');
			
			foreach ($this->request->post['selected'] as $store_id) {
				if (!$store_id) {
					$this->error['warning'] = $this->language->get('error_default');
				}
				
				$store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);
				
				if ($store_total) {
					$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
				}	
			}
			
			if (!$this->error) {
				return true; 
				} else {
				return false;
			}
		}
		
		public function template() {
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = HTTPS_CATALOG;
				} else {
				$server = HTTP_CATALOG;
			}		
			
			if (file_exists(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
				$image = $server . 'image/templates/' . basename($this->request->get['template']) . '.png';
				} else {
				$image = $server . 'image/no_image.jpg';
			}
			
			$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
		}		
		
		public function country() {
			$json = array();
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
			
			if ($country_info) {
				$this->load->model('localisation/zone');
				
				$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
				);
			}
			
			$this->response->setOutput(json_encode($json));
		}	
	}
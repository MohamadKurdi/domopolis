<?php
class ControllerSettingSetting extends Controller {
	private $error = array();
	
	public function getFPCINFO(){


		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) { 
			
		} else {
			$this->load->model('setting/setting');

			$this->data['asinDeletionMode'] = $this->model_setting_setting->getKeySettingValue('config', 'config_rainforest_asin_deletion_mode');
			$this->data['setAsinDeletionMode'] = $this->url->link('setting/setting/setAsinDeletionMode' , 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['clear_memcache'] = $this->url->link('common/home/clearMemCache' , 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['noCacheModeLink'] = $this->url->link('setting/setting/setNoCacheMode', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['noCacheMode'] = file_exists(DIR_CACHE . BCACHE_DIR . 'nocache');
			
			if ($this->data['noCacheMode']){					
				$this->data['noCacheModeDuration'] = time() - filemtime(DIR_CACHE . BCACHE_DIR . 'nocache');
			}
			
			$this->data['noPageCacheModeLink'] = $this->url->link('setting/setting/setNoPageCacheMode', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['noPageCacheMode'] = file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
			
			if ($this->data['noPageCacheMode']){					
				$this->data['noPageCacheModeDuration'] = time() - filemtime(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
			}
			
			$this->data['noPageCacheModeTTL'] = $this->PageCache->getTTL();

			$this->data['panelLink'] = $this->url->link('common/panel', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['serverResponceTime'] = $this->PageCache->getServerResponceTime();
			$this->data['redisMem'] = $this->PageCache->getRedisInfo();

			
			$this->template = 'common/cachebuttons.tpl';
			
			$this->response->setOutput($this->render());
		}
	}

	public function setAsinDeletionMode(){
		$this->load->model('setting/setting');

		if ($this->user->getUserGroup() == 1) {

			if ($this->config->get('config_rainforest_asin_deletion_mode')){
				$this->model_setting_setting->editSettingValue('config', 'config_rainforest_asin_deletion_mode', 0);
				$this->config->set('config_rainforest_asin_deletion_mode', 0);
			} else {
				$this->model_setting_setting->editSettingValue('config', 'config_rainforest_asin_deletion_mode', 1);
				$this->config->set('config_rainforest_asin_deletion_mode', 1);
			}

			echo ($this->model_setting_setting->getKeySettingValue('config', 'config_rainforest_asin_deletion_mode')?'Вкл':'Выкл');
		}  else {
			echo 'Недоступно';
		}
	}
	
	public function setNoPageCacheMode(){
		
		if ($this->user->getUserGroup() == 1) {
			
			$this->load->model('kp/bitrixBot');
			
			$enableNCM = false;
			
			if (file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache')){
				@unlink(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
				$enableNCM = false;
			} else {
				@touch(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
				$enableNCM = true;
			}
			
			
			echo (file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache')?'Выкл':'Вкл');
			
		} else {
			echo 'Недоступно';
		}
	}
	
	public function setNoCacheMode(){
		
		if ($this->user->getUserGroup() == 1) {			
			$enableNCM = false;
			
			if (file_exists(DIR_CACHE . BCACHE_DIR . 'nocache')){
				@unlink(DIR_CACHE . BCACHE_DIR . 'nocache');
				$enableNCM = false;
			} else {
				@touch(DIR_CACHE . BCACHE_DIR . 'nocache');
				$enableNCM = true;
			}		
			
			echo (file_exists(DIR_CACHE . BCACHE_DIR . 'nocache')?'Выкл':'Вкл');
			
		} else {
			echo 'Недоступно';
		}
	}
	
	public function editSettingAjax(){

		$store_id 	= $this->request->get['store_id'];
		$key 		= $this->request->post['key'];
		$value 		= $this->request->post['value'];

		if ($key){
			$sql = "UPDATE setting SET `value` = '" . $this->db->escape($value) . "' WHERE `store_id` = '" . (int)$store_id . "' AND `group` = 'config' AND `key` = '" . $this->db->escape($key) . "'";
			$query = $this->db->query($sql);

			if (!$this->db->countAffected()){
				$sql = "INSERT INTO setting SET `value` = '" . $this->db->escape($value) . "', `store_id` = '" . (int)$store_id . "', `group` = 'config', `key` = '" . $this->db->escape($key) . "', serialized = 0";
				$query = $this->db->query($sql);
			}
		}

		var_dump($sql);
	}
	
	
	public function index() {
		$this->language->load('setting/setting'); 
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('config', $this->request->post);
			
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_items'] = $this->language->get('text_items');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_stock'] = $this->language->get('text_stock');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		$this->data['text_shipping'] = $this->language->get('text_shipping');	
		$this->data['text_payment'] = $this->language->get('text_payment');					
		$this->data['text_mail'] = $this->language->get('text_mail');
		$this->data['text_smtp'] = $this->language->get('text_smtp');
		
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
		$this->data['entry_admin_language'] = $this->language->get('entry_admin_language');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_currency_auto'] = $this->language->get('entry_currency_auto');
		$this->data['entry_length_class'] = $this->language->get('entry_length_class');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_catalog_limit'] = $this->language->get('entry_catalog_limit');
		$this->data['entry_admin_limit'] = $this->language->get('entry_admin_limit');
		$this->data['entry_product_count'] = $this->language->get('entry_product_count');
		$this->data['entry_review'] = $this->language->get('entry_review');
		$this->data['entry_download'] = $this->language->get('entry_download');
		$this->data['entry_voucher_min'] = $this->language->get('entry_voucher_min');
		$this->data['entry_voucher_max'] = $this->language->get('entry_voucher_max');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_vat'] = $this->language->get('entry_vat');
		$this->data['entry_tax_default'] = $this->language->get('entry_tax_default');
		$this->data['entry_tax_customer'] = $this->language->get('entry_tax_customer');
		$this->data['entry_customer_online'] = $this->language->get('entry_customer_online');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
		$this->data['entry_customer_price'] = $this->language->get('entry_customer_price');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_cart_weight'] = $this->language->get('entry_cart_weight');		
		$this->data['entry_guest_checkout'] = $this->language->get('entry_guest_checkout');
		$this->data['entry_checkout'] = $this->language->get('entry_checkout');		
		$this->data['entry_order_edit'] = $this->language->get('entry_order_edit');
		$this->data['entry_invoice_prefix'] = $this->language->get('entry_invoice_prefix');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_complete_status'] = $this->language->get('entry_complete_status');	
		$this->data['entry_stock_display'] = $this->language->get('entry_stock_display');
		$this->data['entry_stock_warning'] = $this->language->get('entry_stock_warning');
		$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$this->data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		$this->data['entry_return'] = $this->language->get('entry_return');
		$this->data['entry_return_status'] = $this->language->get('entry_return_status');
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
		$this->data['entry_ftp_host'] = $this->language->get('entry_ftp_host');
		$this->data['entry_ftp_port'] = $this->language->get('entry_ftp_port');
		$this->data['entry_ftp_username'] = $this->language->get('entry_ftp_username');
		$this->data['entry_ftp_password'] = $this->language->get('entry_ftp_password');
		$this->data['entry_ftp_root'] = $this->language->get('entry_ftp_root');
		$this->data['entry_ftp_status'] = $this->language->get('entry_ftp_status');
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
		$this->data['entry_fraud_detection'] = $this->language->get('entry_fraud_detection');
		$this->data['entry_fraud_key'] = $this->language->get('entry_fraud_key');
		$this->data['entry_fraud_score'] = $this->language->get('entry_fraud_score');
		$this->data['entry_fraud_status'] = $this->language->get('entry_fraud_status');
		$this->data['entry_secure'] = $this->language->get('entry_secure');
		$this->data['entry_shared'] = $this->language->get('entry_shared');
		$this->data['entry_robots'] = $this->language->get('entry_robots');
		$this->data['entry_file_extension_allowed'] = $this->language->get('entry_file_extension_allowed');
		$this->data['entry_file_mime_allowed'] = $this->language->get('entry_file_mime_allowed');		
		$this->data['entry_maintenance'] = $this->language->get('entry_maintenance');
		$this->data['entry_password'] = $this->language->get('entry_password');
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
		
		$this->data['entry_sms_send_new_order_status']
		= $this->language->get('entry_sms_send_new_order_status');
		$this->data['entry_sms_new_order_status_message']
		= $this->language->get('entry_sms_new_order_status_message');
		
		$this->data['entry_sms_send_new_order']
		= $this->language->get('entry_sms_send_new_order');
		$this->data['entry_sms_new_order_message']
		= $this->language->get('entry_sms_new_order_message');
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->data['entry_sms_gatename'] = $this->language->get('entry_sms_gatename');
		$this->data['entry_sms_to'] = $this->language->get('entry_sms_to');
		$this->data['entry_sms_from'] = $this->language->get('entry_sms_from');
		$this->data['entry_sms_message'] = $this->language->get('entry_sms_message');
		$this->data['entry_sms_gate_username'] = $this->language->get('entry_sms_gate_username');
		$this->data['entry_sms_gate_password'] = $this->language->get('entry_sms_gate_password');
		$this->data['entry_sms_alert'] = $this->language->get('entry_sms_alert');
		$this->data['entry_sms_copy'] = $this->language->get('entry_sms_copy');
		$this->data['entry_sms_prkey'] = $this->language->get('entry_sms_prkey');
		$this->data['entry_sms_pukey'] = $this->language->get('entry_sms_pukey');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_store'] = $this->language->get('tab_store');
		$this->data['tab_local'] = $this->language->get('tab_local');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_ftp'] = $this->language->get('tab_ftp');
		$this->data['tab_mail'] = $this->language->get('tab_mail');
		$this->data['tab_fraud'] = $this->language->get('tab_fraud');
		$this->data['tab_server'] = $this->language->get('tab_server');
		
		$this->data['tab_sms'] = $this->language->get('tab_sms');
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
		
		if (isset($this->error['voucher_min'])) {
			$this->data['error_voucher_min'] = $this->error['voucher_min'];
		} else {
			$this->data['error_voucher_min'] = '';
		}	
		
		if (isset($this->error['voucher_max'])) {
			$this->data['error_voucher_max'] = $this->error['voucher_max'];
		} else {
			$this->data['error_voucher_max'] = '';
		}
		
		if (isset($this->error['ftp_host'])) {
			$this->data['error_ftp_host'] = $this->error['ftp_host'];
		} else {
			$this->data['error_ftp_host'] = '';
		}
		
		if (isset($this->error['ftp_port'])) {
			$this->data['error_ftp_port'] = $this->error['ftp_port'];
		} else {
			$this->data['error_ftp_port'] = '';
		}
		
		if (isset($this->error['ftp_username'])) {
			$this->data['error_ftp_username'] = $this->error['ftp_username'];
		} else {
			$this->data['error_ftp_username'] = '';
		}
		
		if (isset($this->error['ftp_password'])) {
			$this->data['error_ftp_password'] = $this->error['ftp_password'];
		} else {
			$this->data['error_ftp_password'] = '';
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
		
		if (isset($this->error['error_filename'])) {
			$this->data['error_error_filename'] = $this->error['error_filename'];
		} else {
			$this->data['error_error_filename'] = '';
		}		
		
		if (isset($this->error['catalog_limit'])) {
			$this->data['error_catalog_limit'] = $this->error['catalog_limit'];
		} else {
			$this->data['error_catalog_limit'] = '';
		}
		
		if (isset($this->error['admin_limit'])) {
			$this->data['error_admin_limit'] = $this->error['admin_limit'];
		} else {
			$this->data['error_admin_limit'] = '';
		}
		
		if (isset($this->error['encryption'])) {
			$this->data['error_encryption'] = $this->error['encryption'];
		} else {
			$this->data['error_encryption'] = '';
		}		
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['config_name'])) {
			$this->data['config_name'] = $this->request->post['config_name'];
		} else {
			$this->data['config_name'] = $this->config->get('config_name');
		}

		if (isset($this->request->post['config_warmode_enable'])) {
			$this->data['config_warmode_enable'] = $this->request->post['config_warmode_enable']; 
		} else {
			$this->data['config_warmode_enable'] = $this->config->get('config_warmode_enable');
		}

		if (isset($this->request->post['config_no_access_enable'])) {
			$this->data['config_no_access_enable'] = $this->request->post['config_no_access_enable']; 
		} else {
			$this->data['config_no_access_enable'] = $this->config->get('config_no_access_enable');
		}
		
		if (isset($this->request->post['config_ssl'])) {
			$this->data['config_ssl'] = $this->request->post['config_ssl'];
		} else {
			$this->data['config_ssl'] = $this->config->get('config_ssl');
		}
		
		if (isset($this->request->post['config_owner'])) {
			$this->data['config_owner'] = $this->request->post['config_owner'];
		} else {
			$this->data['config_owner'] = $this->config->get('config_owner');
		}
		
		if (isset($this->request->post['config_address'])) {
			$this->data['config_address'] = $this->request->post['config_address'];
		} else {
			$this->data['config_address'] = $this->config->get('config_address');
		}
		
		if (isset($this->request->post['config_popular_searches'])) {
			$this->data['config_popular_searches'] = $this->request->post['config_popular_searches'];
		} else {
			$this->data['config_popular_searches'] = $this->config->get('config_popular_searches');
		}
		
		if (isset($this->request->post['config_phonemask'])) {
			$this->data['config_phonemask'] = $this->request->post['config_phonemask'];
		} else {
			$this->data['config_phonemask'] = $this->config->get('config_phonemask');
		}
		
		if (isset($this->request->post['config_default_city'])) {
			$this->data['config_default_city'] = $this->request->post['config_default_city'];
		} else {
			$this->data['config_default_city'] = $this->config->get('config_default_city');
		}
		
		if (isset($this->request->post['config_sms_sign'])) {
			$this->data['config_sms_sign'] = $this->request->post['config_sms_sign'];
		} else {
			$this->data['config_sms_sign'] = $this->config->get('config_sms_sign');
		}
		
		if (isset($this->request->post['config_email'])) {
			$this->data['config_email'] = $this->request->post['config_email'];
		} else {
			$this->data['config_email'] = $this->config->get('config_email');
		}
		
		if (isset($this->request->post['config_opt_email'])) {
			$this->data['config_opt_email'] = $this->request->post['config_opt_email'];
		} else {
			$this->data['config_opt_email'] = $this->config->get('config_opt_email');
		}
		
		if (isset($this->request->post['config_display_email'])) {
			$this->data['config_display_email'] = $this->request->post['config_display_email'];
		} else {
			$this->data['config_display_email'] = $this->config->get('config_display_email');
		}
		
		if (isset($this->request->post['config_telephone'])) {
			$this->data['config_telephone'] = $this->request->post['config_telephone'];
		} else {
			$this->data['config_telephone'] = $this->config->get('config_telephone');
		}
		
		if (isset($this->request->post['config_telephone2'])) {
			$this->data['config_telephone2'] = $this->request->post['config_telephone2'];
		} else {
			$this->data['config_telephone2'] = $this->config->get('config_telephone2');
		}
		
		if (isset($this->request->post['config_telephone3'])) {
			$this->data['config_telephone3'] = $this->request->post['config_telephone3'];
		} else {
			$this->data['config_telephone3'] = $this->config->get('config_telephone3');
		}
		
		
		if (isset($this->request->post['config_opt_telephone'])) {
			$this->data['config_opt_telephone'] = $this->request->post['config_opt_telephone'];
		} else {
			$this->data['config_opt_telephone'] = $this->config->get('config_opt_telephone');
		}
		
		if (isset($this->request->post['config_opt_telephone2'])) {
			$this->data['config_opt_telephone2'] = $this->request->post['config_opt_telephone2'];
		} else {
			$this->data['config_opt_telephone2'] = $this->config->get('config_opt_telephone2');
		}
		
		if (isset($this->request->post['config_t_tt'])) {
			$this->data['config_t_tt'] = $this->request->post['config_t_tt'];
		} else {
			$this->data['config_t_tt'] = $this->config->get('config_t_tt');
		}
		
		if (isset($this->request->post['config_t2_tt'])) {
			$this->data['config_t2_tt'] = $this->request->post['config_t2_tt'];
		} else {
			$this->data['config_t2_tt'] = $this->config->get('config_t2_tt');
		}
		
		if (isset($this->request->post['config_t_bt'])) {
			$this->data['config_t_bt'] = $this->request->post['config_t_bt'];
		} else {
			$this->data['config_t_bt'] = $this->config->get('config_t_bt');
		}
		
		if (isset($this->request->post['config_t2_bt'])) {
			$this->data['config_t2_bt'] = $this->request->post['config_t2_bt'];
		} else {
			$this->data['config_t2_bt'] = $this->config->get('config_t2_bt');
		}
		
		if (isset($this->request->post['config_worktime'])) {
			$this->data['config_worktime'] = $this->request->post['config_worktime'];
		} else {
			$this->data['config_worktime'] = $this->config->get('config_worktime');
		}
		
		if (isset($this->request->post['config_fax'])) {
			$this->data['config_fax'] = $this->request->post['config_fax'];
		} else {
			$this->data['config_fax'] = $this->config->get('config_fax');
		}
		
		if (isset($this->request->post['config_social_auth'])) {
			$this->data['config_social_auth'] = $this->request->post['config_social_auth'];
		} else {
			$this->data['config_social_auth'] = $this->config->get('config_social_auth');
		}
		
		
		if (isset($this->request->post['config_title'])) {
			$this->data['config_title'] = $this->request->post['config_title'];
		} else {
			$this->data['config_title'] = $this->config->get('config_title');
		}
		
		if (isset($this->request->post['config_monobrand'])) {
			$this->data['config_monobrand'] = $this->request->post['config_monobrand'];
		} else {
			$this->data['config_monobrand'] = $this->config->get('config_monobrand');
		}
		
		if (isset($this->request->post['config_dadata'])) {
			$this->data['config_dadata'] = $this->request->post['config_dadata'];
		} else {
			$this->data['config_dadata'] = $this->config->get('config_dadata');
		}

		if (isset($this->request->post['config_dadata_api_key'])) {
			$this->data['config_dadata_api_key'] = $this->request->post['config_dadata_api_key'];
		} else {
			$this->data['config_dadata_api_key'] = $this->config->get('config_dadata_api_key');
		}

		if (isset($this->request->post['config_dadata_secret_key'])) {
			$this->data['config_dadata_secret_key'] = $this->request->post['config_dadata_secret_key'];
		} else {
			$this->data['config_dadata_secret_key'] = $this->config->get('config_dadata_secret_key');
		}

		if (isset($this->request->post['config_ip_api_key'])) {
			$this->data['config_ip_api_key'] = $this->request->post['config_ip_api_key'];
		} else {
			$this->data['config_ip_api_key'] = $this->config->get('config_ip_api_key');
		}

		if (isset($this->request->post['config_zadarma_api_key'])) {
			$this->data['config_zadarma_api_key'] = $this->request->post['config_zadarma_api_key'];
		} else {
			$this->data['config_zadarma_api_key'] = $this->config->get('config_zadarma_api_key');
		}

		if (isset($this->request->post['config_zadarma_secret_key'])) {
			$this->data['config_zadarma_secret_key'] = $this->request->post['config_zadarma_secret_key'];
		} else {
			$this->data['config_zadarma_secret_key'] = $this->config->get('config_zadarma_secret_key');
		}

		if (isset($this->request->post['config_smsgate_api_key'])) {
			$this->data['config_smsgate_api_key'] = $this->request->post['config_smsgate_api_key'];
		} else {
			$this->data['config_smsgate_api_key'] = $this->config->get('config_smsgate_api_key');
		}

		if (isset($this->request->post['config_smsgate_secret_key'])) {
			$this->data['config_smsgate_secret_key'] = $this->request->post['config_smsgate_secret_key'];
		} else {
			$this->data['config_smsgate_secret_key'] = $this->config->get('config_smsgate_secret_key');
		}

		if (isset($this->request->post['config_smsgate_user'])) {
			$this->data['config_smsgate_user'] = $this->request->post['config_smsgate_user'];
		} else {
			$this->data['config_smsgate_user'] = $this->config->get('config_smsgate_user');
		}

		if (isset($this->request->post['config_smsgate_passwd'])) {
			$this->data['config_smsgate_passwd'] = $this->request->post['config_smsgate_passwd'];
		} else {
			$this->data['config_smsgate_passwd'] = $this->config->get('config_smsgate_passwd');
		}
		
		if (isset($this->request->post['config_show_goods_overload'])) {
			$this->data['config_show_goods_overload'] = $this->request->post['config_show_goods_overload'];
		} else {
			$this->data['config_show_goods_overload'] = $this->config->get('config_show_goods_overload');
		}
		
		if (isset($this->request->post['config_meta_description'])) {
			$this->data['config_meta_description'] = $this->request->post['config_meta_description'];
		} else {
			$this->data['config_meta_description'] = $this->config->get('config_meta_description');
		}
		
		if (isset($this->request->post['config_layout_id'])) {
			$this->data['config_layout_id'] = $this->request->post['config_layout_id'];
		} else {
			$this->data['config_layout_id'] = $this->config->get('config_layout_id');
		}
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		if (isset($this->request->post['config_template'])) {
			$this->data['config_template'] = $this->request->post['config_template'];
		} else {
			$this->data['config_template'] = $this->config->get('config_template');
		}
		
		$this->data['templates'] = array();
		
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}					
		
		if (isset($this->request->post['config_country_id'])) {
			$this->data['config_country_id'] = $this->request->post['config_country_id'];
		} else {
			$this->data['config_country_id'] = $this->config->get('config_country_id');
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['config_zone_id'])) {
			$this->data['config_zone_id'] = $this->request->post['config_zone_id'];
		} else {
			$this->data['config_zone_id'] = $this->config->get('config_zone_id');
		}
		
		if (isset($this->request->post['config_countryname'])) {
			$this->data['config_countryname'] = $this->request->post['config_countryname'];
		} else {
			$this->data['config_countryname'] = $this->config->get('config_countryname');
		}			
		
		if (isset($this->request->post['config_googlelocal_code'])) {
			$this->data['config_googlelocal_code'] = $this->request->post['config_googlelocal_code'];
		} else {
			$this->data['config_googlelocal_code'] = $this->config->get('config_googlelocal_code');
		}	
		
		if (isset($this->request->post['config_warehouse_identifier'])) {
			$this->data['config_warehouse_identifier'] = $this->request->post['config_warehouse_identifier'];
		} else {
			$this->data['config_warehouse_identifier'] = $this->config->get('config_warehouse_identifier');
		}	
		
		if (isset($this->request->post['config_warehouse_identifier_local'])) {
			$this->data['config_warehouse_identifier_local'] = $this->request->post['config_warehouse_identifier_local'];
		} else {
			$this->data['config_warehouse_identifier_local'] = $this->config->get('config_warehouse_identifier_local');
		}	
		
		if (isset($this->request->post['config_warehouse_only'])) {
			$this->data['config_warehouse_only'] = $this->request->post['config_warehouse_only'];
		} else {
			$this->data['config_warehouse_only'] = $this->config->get('config_warehouse_only');
		}

		if (isset($this->request->post['config_overload_stock_status_id'])) {
			$this->data['config_overload_stock_status_id'] = $this->request->post['config_overload_stock_status_id'];
		} else {
			$this->data['config_overload_stock_status_id'] = $this->config->get('config_overload_stock_status_id');
		}


		if (isset($this->request->post['config_payment_list'])) {
			$this->data['config_payment_list'] = $this->request->post['config_payment_list'];
		} else {
			$this->data['config_payment_list'] = $this->config->get('config_payment_list');
		}	
		
		if (isset($this->request->post['config_language'])) {
			$this->data['config_language'] = $this->request->post['config_language'];
		} else {
			$this->data['config_language'] = $this->config->get('config_language');
		}
		
		if (isset($this->request->post['config_second_language'])) {
			$this->data['config_second_language'] = $this->request->post['config_second_language'];
		} else {
			$this->data['config_second_language'] = $this->config->get('config_second_language');
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['config_admin_language'])) {
			$this->data['config_admin_language'] = $this->request->post['config_admin_language'];
		} else {
			$this->data['config_admin_language'] = $this->config->get('config_admin_language');
		}
		
		if (isset($this->request->post['config_de_language'])) {
			$this->data['config_de_language'] = $this->request->post['config_de_language'];
		} else {
			$this->data['config_de_language'] = $this->config->get('config_de_language');
		}
		
		if (isset($this->request->post['config_translate_from_ru'])) {
			$this->data['config_translate_from_ru'] = $this->request->post['config_translate_from_ru'];
		} else {
			$this->data['config_translate_from_ru'] = $this->config->get('config_translate_from_ru');
		}

		if (isset($this->request->post['config_translate_from_de'])) {
			$this->data['config_translate_from_de'] = $this->request->post['config_translate_from_de'];
		} else {
			$this->data['config_translate_from_de'] = $this->config->get('config_translate_from_de');
		}

		if (isset($this->request->post['config_translate_from_uk'])) {
			$this->data['config_translate_from_uk'] = $this->request->post['config_translate_from_uk'];
		} else {
			$this->data['config_translate_from_uk'] = $this->config->get('config_translate_from_uk');
		}
		
		if (isset($this->request->post['config_overprice'])) {
			$this->data['config_overprice'] = $this->request->post['config_overprice'];
		} else {
			$this->data['config_overprice'] = $this->config->get('config_overprice');
		}
		
		if (isset($this->request->post['config_currency'])) {
			$this->data['config_currency'] = $this->request->post['config_currency'];
		} else {
			$this->data['config_currency'] = $this->config->get('config_currency');
		}
		
		if (isset($this->request->post['config_regional_currency'])) {
			$this->data['config_regional_currency'] = $this->request->post['config_regional_currency'];
		} else {
			$this->data['config_regional_currency'] = $this->config->get('config_regional_currency');
		}
		
		if (isset($this->request->post['config_currency_auto'])) {
			$this->data['config_currency_auto'] = $this->request->post['config_currency_auto'];
		} else {
			$this->data['config_currency_auto'] = $this->config->get('config_currency_auto');
		}
			// Показывать меню слева
		if (isset($this->request->post['show_menu_in_left'])) {
			$this->data['show_menu_in_left'] = $this->request->post['show_menu_in_left'];
		} else {
			$this->data['show_menu_in_left'] = $this->config->get('show_menu_in_left');
		}
		
		$this->load->model('localisation/currency');
		
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		if (isset($this->request->post['config_length_class_id'])) {
			$this->data['config_length_class_id'] = $this->request->post['config_length_class_id'];
		} else {
			$this->data['config_length_class_id'] = $this->config->get('config_length_class_id');
		}
		
		$this->load->model('localisation/length_class');
		
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		
		if (isset($this->request->post['config_weight_class_id'])) {
			$this->data['config_weight_class_id'] = $this->request->post['config_weight_class_id'];
		} else {
			$this->data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
		}
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
		if (isset($this->request->post['config_review_good'])) {
			$this->data['config_review_good'] = $this->request->post['config_review_good'];
		} else {
			$this->data['config_review_good'] = $this->config->get('config_review_good');
		}
		if (isset($this->request->post['config_review_bad'])) {
			$this->data['config_review_bad'] = $this->request->post['config_review_bad'];
		} else {
			$this->data['config_review_bad'] = $this->config->get('config_review_bad');
		}
		if (isset($this->request->post['config_review_addimage'])) {
			$this->data['config_review_addimage'] = $this->request->post['config_review_addimage'];
		} else {
			$this->data['config_review_addimage'] = $this->config->get('config_review_addimage');
		}
		if (isset($this->request->post['config_review_captcha'])) {
			$this->data['config_review_captcha'] = $this->request->post['config_review_captcha'];
		} else {
			$this->data['config_review_captcha'] = $this->config->get('config_review_captcha');
		}
		if (isset($this->request->post['config_review_statusp'])) {
			$this->data['config_review_statusp'] = $this->request->post['config_review_statusp'];
		} else {
			$this->data['config_review_statusp'] = $this->config->get('config_review_statusp');
		}
		if (isset($this->request->post['config_review_email'])) {
			$this->data['config_review_email'] = $this->request->post['config_review_email'];
		} else {
			$this->data['config_review_email'] = $this->config->get('config_review_email');
		}
		if (isset($this->request->post['config_review_text_symbol'])) {
			$this->data['config_review_text_symbol'] = $this->request->post['config_review_text_symbol'];
		} else {
			$this->data['config_review_text_symbol'] = $this->config->get('config_review_text_symbol');
		}
		
		if (isset($this->request->post['config_reward_logosvg'])) {
			$this->data['config_reward_logosvg'] = $this->request->post['config_reward_logosvg'];
		} else {
			$this->data['config_reward_logosvg'] = $this->config->get('config_reward_logosvg');
		}	
		
		if (isset($this->request->post['config_reward_lifetime'])) {
			$this->data['config_reward_lifetime'] = $this->request->post['config_reward_lifetime'];
		} else {
			$this->data['config_reward_lifetime'] = $this->config->get('config_reward_lifetime');
		}	
		
		if (isset($this->request->post['config_reward_maxsalepercent'])) {
			$this->data['config_reward_maxsalepercent'] = $this->request->post['config_reward_maxsalepercent'];
		} else {
			$this->data['config_reward_maxsalepercent'] = $this->config->get('config_reward_maxsalepercent');
		}	
		
		if (isset($this->request->post['rewardpoints_currency_mode'])) {
			$this->data['rewardpoints_currency_mode'] = $this->request->post['rewardpoints_currency_mode'];
		} else {
			$this->data['rewardpoints_currency_mode'] = $this->config->get('rewardpoints_currency_mode');
		}	
		
		if (isset($this->request->post['rewardpoints_currency_prefix'])) {
			$this->data['rewardpoints_currency_prefix'] = $this->request->post['rewardpoints_currency_prefix'];
		} else {
			$this->data['rewardpoints_currency_prefix'] = $this->config->get('rewardpoints_currency_prefix');
		}	
		
		if (isset($this->request->post['rewardpoints_currency_suffix'])) {
			$this->data['rewardpoints_currency_suffix'] = $this->request->post['rewardpoints_currency_suffix'];
		} else {
			$this->data['rewardpoints_currency_suffix'] = $this->config->get('rewardpoints_currency_suffix');
		}
		
		if (isset($this->request->post['rewardpoints_pointspercent'])) {
			$this->data['rewardpoints_pointspercent'] = $this->request->post['rewardpoints_pointspercent'];
		} else {
			$this->data['rewardpoints_pointspercent'] = $this->config->get('rewardpoints_pointspercent');
		}
		
		if (isset($this->request->post['rewardpoints_appinstall'])) {
			$this->data['rewardpoints_appinstall'] = $this->request->post['rewardpoints_appinstall'];
		} else {
			$this->data['rewardpoints_appinstall'] = $this->config->get('rewardpoints_appinstall');
		}
		
		if (isset($this->request->post['rewardpoints_birthday'])) {
			$this->data['rewardpoints_birthday'] = $this->request->post['rewardpoints_birthday'];
		} else {
			$this->data['rewardpoints_birthday'] = $this->config->get('rewardpoints_birthday');
		}

		
		$termskeys = array(
			'config_delivery_instock_term',
			'config_delivery_central_term',
			'config_delivery_russia_term',
			'config_delivery_ukrainian_term',
			'config_delivery_outstock_term',
			'config_delivery_outstock_enable'
		);
		
		foreach ($termskeys as $termkey){
			
			if (isset($this->request->post[$termkey])) {
				$this->data[$termkey] = $this->request->post[$termkey];
			} else {
				$this->data[$termkey] = $this->config->get($termkey);
			}
			
		}
		
		if (isset($this->request->post['config_pickup_enable'])) {
			$this->data['config_pickup_enable'] = $this->request->post['config_pickup_enable']; 
		} else {
			$this->data['config_pickup_enable'] = $this->config->get('config_pickup_enable');
		}
		
		if (isset($this->request->post['config_pickup_times'])) {
			$this->data['config_pickup_times'] = $this->request->post['config_pickup_times'];
		} else {
			$this->data['config_pickup_times'] = $this->config->get('config_pickup_times');
		}
		
		for ($i=1; $i<=12; $i++){
			if (isset($this->request->post['config_pickup_dayoff_' . $i])) {
				$this->data['config_pickup_dayoff_' . $i] = $this->request->post['config_pickup_dayoff_' . $i]; 
			} else {
				$this->data['config_pickup_dayoff_' . $i] = $this->config->get('config_pickup_dayoff_' . $i);
			}
		}

			//Службы доставки
		if (isset($this->request->post['config_novaposhta_api_key'])) {
			$this->data['config_novaposhta_api_key'] = $this->request->post['config_novaposhta_api_key'];
		} else {
			$this->data['config_novaposhta_api_key'] = $this->config->get('config_novaposhta_api_key');
		}

		if (isset($this->request->post['config_novaposhta_default_city_guid'])) {
			$this->data['config_novaposhta_default_city_guid'] = $this->request->post['config_novaposhta_default_city_guid'];
		} else {
			$this->data['config_novaposhta_default_city_guid'] = $this->config->get('config_novaposhta_default_city_guid');
		}

		if (isset($this->request->post['config_novaposhta_ru_language'])) {
			$this->data['config_novaposhta_ru_language'] = $this->request->post['config_novaposhta_ru_language'];
		} else {
			$this->data['config_novaposhta_ru_language'] = $this->config->get('config_novaposhta_ru_language');
		}

		if (isset($this->request->post['config_novaposhta_ua_language'])) {
			$this->data['config_novaposhta_ua_language'] = $this->request->post['config_novaposhta_ua_language'];
		} else {
			$this->data['config_novaposhta_ua_language'] = $this->config->get('config_novaposhta_ua_language');
		}

		if (isset($this->request->post['config_justin_api_key'])) {
			$this->data['config_justin_api_key'] = $this->request->post['config_justin_api_key'];
		} else {
			$this->data['config_justin_api_key'] = $this->config->get('config_justin_api_key');
		}

		if (isset($this->request->post['config_justin_api_login'])) {
			$this->data['config_justin_api_login'] = $this->request->post['config_justin_api_login'];
		} else {
			$this->data['config_justin_api_login'] = $this->config->get('config_justin_api_login');
		}

		if (isset($this->request->post['config_justin_ru_language'])) {
			$this->data['config_justin_ru_language'] = $this->request->post['config_justin_ru_language'];
		} else {
			$this->data['config_justin_ru_language'] = $this->config->get('config_justin_ru_language');
		}

		if (isset($this->request->post['config_justin_ua_language'])) {
			$this->data['config_justin_ua_language'] = $this->request->post['config_justin_ua_language'];
		} else {
			$this->data['config_justin_ua_language'] = $this->config->get('config_justin_ua_language');
		}

		if (isset($this->request->post['config_ukrposhta_api_bearer'])) {
			$this->data['config_ukrposhta_api_bearer'] = $this->request->post['config_ukrposhta_api_bearer'];
		} else {
			$this->data['config_ukrposhta_api_bearer'] = $this->config->get('config_ukrposhta_api_bearer');
		}

		if (isset($this->request->post['config_ukrposhta_api_token'])) {
			$this->data['config_ukrposhta_api_token'] = $this->request->post['config_ukrposhta_api_token'];
		} else {
			$this->data['config_ukrposhta_api_token'] = $this->config->get('config_ukrposhta_api_token');
		}

		if (isset($this->request->post['config_ukrposhta_ru_language'])) {
			$this->data['config_ukrposhta_ru_language'] = $this->request->post['config_ukrposhta_ru_language'];
		} else {
			$this->data['config_ukrposhta_ru_language'] = $this->config->get('config_ukrposhta_ru_language');
		}

		if (isset($this->request->post['config_ukrposhta_ua_language'])) {
			$this->data['config_ukrposhta_ua_language'] = $this->request->post['config_ukrposhta_ua_language'];
		} else {
			$this->data['config_ukrposhta_ua_language'] = $this->config->get('config_ukrposhta_ua_language');
		}

		
		if (isset($this->request->post['config_group_price_enable'])) {
			$this->data['config_group_price_enable'] = $this->request->post['config_group_price_enable'];
		} else {
			$this->data['config_group_price_enable'] = $this->config->get('config_group_price_enable');
		}
		
		if (isset($this->request->post['config_android_playstore_enable'])) {
			$this->data['config_android_playstore_enable'] = $this->request->post['config_android_playstore_enable'];
		} else {
			$this->data['config_android_playstore_enable'] = $this->config->get('config_android_playstore_enable');
		}
		
		if (isset($this->request->post['config_android_playstore_code'])) {
			$this->data['config_android_playstore_code'] = $this->request->post['config_android_playstore_code'];
		} else {
			$this->data['config_android_playstore_code'] = $this->config->get('config_android_playstore_code');
		}	
		
		if (isset($this->request->post['config_android_playstore_link'])) {
			$this->data['config_android_playstore_link'] = $this->request->post['config_android_playstore_link'];
		} else {
			$this->data['config_android_playstore_link'] = $this->config->get('config_android_playstore_link');
		}

		if (isset($this->request->post['config_android_application_link'])) {
			$this->data['config_android_application_link'] = $this->request->post['config_android_application_link'];
		} else {
			$this->data['config_android_application_link'] = $this->config->get('config_android_application_link');
		}
		
		if (isset($this->request->post['config_firebase_code'])) {
			$this->data['config_firebase_code'] = $this->request->post['config_firebase_code'];
		} else {
			$this->data['config_firebase_code'] = $this->config->get('config_firebase_code');
		}
		
		if (isset($this->request->post['config_microsoft_store_enable'])) {
			$this->data['config_microsoft_store_enable'] = $this->request->post['config_microsoft_store_enable'];
		} else {
			$this->data['config_microsoft_store_enable'] = $this->config->get('config_microsoft_store_enable');
		}
		
		if (isset($this->request->post['config_microsoft_store_code'])) {
			$this->data['config_microsoft_store_code'] = $this->request->post['config_microsoft_store_code'];
		} else {
			$this->data['config_microsoft_store_code'] = $this->config->get('config_microsoft_store_code');
		}
		
		
		if (isset($this->request->post['config_microsoft_store_link'])) {
			$this->data['config_microsoft_store_link'] = $this->request->post['config_microsoft_store_link'];
		} else {
			$this->data['config_microsoft_store_link'] = $this->config->get('config_microsoft_store_link');
		}
		
		if (isset($this->request->post['config_catalog_limit'])) {
			$this->data['config_catalog_limit'] = $this->request->post['config_catalog_limit'];
		} else {
			$this->data['config_catalog_limit'] = $this->config->get('config_catalog_limit');
		}	

		$this->load->model('catalog/attribute_group');
		$this->data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups(['limit' => 100]);
		
		if (isset($this->request->post['config_special_attr_id'])) {
			$this->data['config_special_attr_id'] = $this->request->post['config_special_attr_id'];
		} else {
			$this->data['config_special_attr_id'] = $this->config->get('config_special_attr_id');
		}	

		if (isset($this->request->post['config_default_attr_id'])) {
			$this->data['config_default_attr_id'] = $this->request->post['config_default_attr_id'];
		} else {
			$this->data['config_default_attr_id'] = $this->config->get('config_default_attr_id');
		}	

		if (isset($this->request->post['config_special_attr_name'])) {
			$this->data['config_special_attr_name'] = $this->request->post['config_special_attr_name'];
		} else {
			$this->data['config_special_attr_name'] = $this->config->get('config_special_attr_name');
		}	

		if (isset($this->request->post['config_specifications_attr_id'])) {
			$this->data['config_specifications_attr_id'] = $this->request->post['config_specifications_attr_id'];
		} else {
			$this->data['config_specifications_attr_id'] = $this->config->get('config_specifications_attr_id');
		}	

		if (isset($this->request->post['config_specifications_attr_name'])) {
			$this->data['config_specifications_attr_name'] = $this->request->post['config_specifications_attr_name'];
		} else {
			$this->data['config_specifications_attr_name'] = $this->config->get('config_specifications_attr_name');
		}
		
		if (isset($this->request->post['config_admin_limit'])) {
			$this->data['config_admin_limit'] = $this->request->post['config_admin_limit'];
		} else {
			$this->data['config_admin_limit'] = $this->config->get('config_admin_limit');
		}
		
		if (isset($this->request->post['config_product_hide_sku'])) {
			$this->data['config_product_hide_sku'] = $this->request->post['config_product_hide_sku'];
		} else {
			$this->data['config_product_hide_sku'] = $this->config->get('config_product_hide_sku');
		}
		
		if (isset($this->request->post['config_product_replace_sku_with_product_id'])) {
			$this->data['config_product_replace_sku_with_product_id'] = $this->request->post['config_product_replace_sku_with_product_id'];
		} else {
			$this->data['config_product_replace_sku_with_product_id'] = $this->config->get('config_product_replace_sku_with_product_id');
		}
		
		if (isset($this->request->post['config_product_use_sku_prefix'])) {
			$this->data['config_product_use_sku_prefix'] = $this->request->post['config_product_use_sku_prefix'];
		} else {
			$this->data['config_product_use_sku_prefix'] = $this->config->get('config_product_use_sku_prefix');
		}
		
		if (isset($this->request->post['config_product_use_sku_prefix'])) {
			$this->data['config_product_use_sku_prefix'] = $this->request->post['config_product_use_sku_prefix'];
		} else {
			$this->data['config_product_use_sku_prefix'] = $this->config->get('config_product_use_sku_prefix');
		}
		
		if (isset($this->request->post['config_product_count'])) {
			$this->data['config_product_count'] = $this->request->post['config_product_count'];
		} else {
			$this->data['config_product_count'] = $this->config->get('config_product_count');
		}
		
		if (isset($this->request->post['config_review_status'])) {
			$this->data['config_review_status'] = $this->request->post['config_review_status'];
		} else {
			$this->data['config_review_status'] = $this->config->get('config_review_status');
		}
		
		if (isset($this->request->post['config_download'])) {
			$this->data['config_download'] = $this->request->post['config_download'];
		} else {
			$this->data['config_download'] = $this->config->get('config_download');
		}
		
		if (isset($this->request->post['config_voucher_min'])) {
			$this->data['config_voucher_min'] = $this->request->post['config_voucher_min'];
		} else {
			$this->data['config_voucher_min'] = $this->config->get('config_voucher_min');
		}	
		
		if (isset($this->request->post['config_voucher_max'])) {
			$this->data['config_voucher_max'] = $this->request->post['config_voucher_max'];
		} else {
			$this->data['config_voucher_max'] = $this->config->get('config_voucher_max');
		}				
		
		if (isset($this->request->post['config_tax'])) {
			$this->data['config_tax'] = $this->request->post['config_tax'];
		} else {
			$this->data['config_tax'] = $this->config->get('config_tax');			
		}
		
		if (isset($this->request->post['config_vat'])) {
			$this->data['config_vat'] = $this->request->post['config_vat'];
		} else {
			$this->data['config_vat'] = $this->config->get('config_vat');			
		}
		
		if (isset($this->request->post['config_tax_default'])) {
			$this->data['config_tax_default'] = $this->request->post['config_tax_default'];
		} else {
			$this->data['config_tax_default'] = $this->config->get('config_tax_default');			
		}	
		
		if (isset($this->request->post['config_tax_customer'])) {
			$this->data['config_tax_customer'] = $this->request->post['config_tax_customer'];
		} else {
			$this->data['config_tax_customer'] = $this->config->get('config_tax_customer');			
		}	
		
		if (isset($this->request->post['config_customer_online'])) {
			$this->data['config_customer_online'] = $this->request->post['config_customer_online'];
		} else {
			$this->data['config_customer_online'] = $this->config->get('config_customer_online');			
		}
		
		if (isset($this->request->post['config_customer_group_id'])) {
			$this->data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
		} else {
			$this->data['config_customer_group_id'] = $this->config->get('config_customer_group_id');			
		}
		
		if (isset($this->request->post['config_bad_customer_group_id'])) {
			$this->data['config_bad_customer_group_id'] = $this->request->post['config_bad_customer_group_id'];
		} else {
			$this->data['config_bad_customer_group_id'] = $this->config->get('config_bad_customer_group_id');			
		}
		
		if (isset($this->request->post['config_opt_group_id'])) {
			$this->data['config_opt_group_id'] = $this->request->post['config_opt_group_id'];
		} else {
			$this->data['config_opt_group_id'] = $this->config->get('config_opt_group_id');			
		}
		
		if (isset($this->request->post['config_myreviews_edit'])) {
			$this->data['config_myreviews_edit'] = $this->request->post['config_myreviews_edit'];
		} else {
			$this->data['config_myreviews_edit'] = $this->config->get('config_myreviews_edit');
		}
		if (isset($this->request->post['config_myreviews_moder'])) {
			$this->data['config_myreviews_moder'] = $this->request->post['config_myreviews_moder'];
		} else {
			$this->data['config_myreviews_moder'] = $this->config->get('config_myreviews_moder');
		}
		
		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['config_customer_group_display'])) {
			$this->data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
		} elseif ($this->config->get('config_customer_group_display')) {
			$this->data['config_customer_group_display'] = $this->config->get('config_customer_group_display');	
		} else {
			$this->data['config_customer_group_display'] = array();			
		}
		
		if (isset($this->request->post['config_customer_price'])) {
			$this->data['config_customer_price'] = $this->request->post['config_customer_price'];
		} else {
			$this->data['config_customer_price'] = $this->config->get('config_customer_price');			
		}
		
		if (isset($this->request->post['config_account_id'])) {
			$this->data['config_account_id'] = $this->request->post['config_account_id'];
		} else {
			$this->data['config_account_id'] = $this->config->get('config_account_id');			
		}
		
		$this->load->model('catalog/information');
		
		$this->data['informations'] = $this->model_catalog_information->getInformations();
		
		if (isset($this->request->post['config_cart_weight'])) {
			$this->data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		} else {
			$this->data['config_cart_weight'] = $this->config->get('config_cart_weight');
		}							
		
		if (isset($this->request->post['config_guest_checkout'])) {
			$this->data['config_guest_checkout'] = $this->request->post['config_guest_checkout'];
		} else {
			$this->data['config_guest_checkout'] = $this->config->get('config_guest_checkout');		
		}
		
		if (isset($this->request->post['config_checkout_id'])) {
			$this->data['config_checkout_id'] = $this->request->post['config_checkout_id'];
		} else {
			$this->data['config_checkout_id'] = $this->config->get('config_checkout_id');		
		}
		
		if (isset($this->request->post['config_order_edit'])) {
			$this->data['config_order_edit'] = $this->request->post['config_order_edit'];
		} elseif ($this->config->get('config_order_edit')) {
			$this->data['config_order_edit'] = $this->config->get('config_order_edit');			
		} else {
			$this->data['config_order_edit'] = 7;
		}
		
		if (isset($this->request->post['config_invoice_prefix'])) {
			$this->data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
		} elseif ($this->config->get('config_invoice_prefix')) {
			$this->data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');			
		} else {
			$this->data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
		}
		
		if (isset($this->request->post['config_order_status_id'])) {
			$this->data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		} else {
			$this->data['config_order_status_id'] = $this->config->get('config_order_status_id');		
		}
		
		if (isset($this->request->post['config_confirmed_order_status_id'])) {
			$this->data['config_confirmed_order_status_id'] = $this->request->post['config_confirmed_order_status_id'];
		} else {
			$this->data['config_confirmed_order_status_id'] = $this->config->get('config_confirmed_order_status_id');		
		}
		
		if (isset($this->request->post['config_confirmed_nopaid_order_status_id'])) {
			$this->data['config_order_confirmed_nopaid_status_id'] = $this->request->post['config_confirmed_nopaid_order_status_id'];
		} else {
			$this->data['config_confirmed_nopaid_order_status_id'] = $this->config->get('config_confirmed_nopaid_order_status_id');		
		}
		
		
		if (isset($this->request->post['config_confirmed_delivery_payment_ids'])) {
			$this->data['config_confirmed_delivery_payment_ids'] = $this->request->post['config_confirmed_delivery_payment_ids'];
		} else {
			$this->data['config_confirmed_delivery_payment_ids'] = $this->config->get('config_confirmed_delivery_payment_ids');		
		}
		
		if (isset($this->request->post['config_confirmed_prepay_payment_ids'])) {
			$this->data['config_confirmed_prepay_payment_ids'] = $this->request->post['config_confirmed_prepay_payment_ids'];
		} else {
			$this->data['config_confirmed_prepay_payment_ids'] = $this->config->get('config_confirmed_prepay_payment_ids');		
		}
		
		if (isset($this->request->post['config_complete_status_id'])) {
			$this->data['config_complete_status_id'] = $this->request->post['config_complete_status_id'];
		} else {
			$this->data['config_complete_status_id'] = $this->config->get('config_complete_status_id');
		}
		
		if (isset($this->request->post['config_treated_status_id'])) {
			$this->data['config_treated_status_id'] = $this->request->post['config_treated_status_id'];
		} else {
			$this->data['config_treated_status_id'] = $this->config->get('config_treated_status_id');
		}
		
		if (isset($this->request->post['config_cancelled_status_id'])) {
			$this->data['config_cancelled_status_id'] = $this->request->post['config_cancelled_status_id'];
		} else {
			$this->data['config_cancelled_status_id'] = $this->config->get('config_cancelled_status_id');
		}
		
		if (isset($this->request->post['config_partly_delivered_status_id'])) {
			$this->data['config_partly_delivered_status_id'] = $this->request->post['config_partly_delivered_status_id'];
		} else {
			$this->data['config_partly_delivered_status_id'] = $this->config->get('config_partly_delivered_status_id');
		}
		
		if (isset($this->request->post['config_cancelled_after_status_id'])) {
			$this->data['config_cancelled_after_status_id'] = $this->request->post['config_cancelled_after_status_id'];
		} else {
			$this->data['config_cancelled_after_status_id'] = $this->config->get('config_cancelled_after_status_id');
		}
		
		if (isset($this->request->post['config_ready_to_delivering_status_id'])) {
			$this->data['config_ready_to_delivering_status_id'] = $this->request->post['config_ready_to_delivering_status_id'];
		} else {
			$this->data['config_ready_to_delivering_status_id'] = $this->config->get('config_ready_to_delivering_status_id');
		}
		
		if (isset($this->request->post['config_in_pickup_status_id'])) {
			$this->data['config_in_pickup_status_id'] = $this->request->post['config_in_pickup_status_id'];
		} else {
			$this->data['config_in_pickup_status_id'] = $this->config->get('config_in_pickup_status_id');
		}
		
		
		if (isset($this->request->post['config_delivering_status_id'])) {
			$this->data['config_delivering_status_id'] = $this->request->post['config_delivering_status_id'];
		} else {
			$this->data['config_delivering_status_id'] = $this->config->get('config_delivering_status_id');
		}
		
		if (isset($this->request->post['config_prepayment_paid_order_status_id'])) {
			$this->data['config_prepayment_paid_order_status_id'] = $this->request->post['config_prepayment_paid_order_status_id'];
		} else {
			$this->data['config_prepayment_paid_order_status_id'] = $this->config->get('config_prepayment_paid_order_status_id');
		}			
		
		if (isset($this->request->post['config_total_paid_order_status_id'])) {
			$this->data['config_total_paid_order_status_id'] = $this->request->post['config_total_paid_order_status_id'];
		} else {
			$this->data['config_total_paid_order_status_id'] = $this->config->get('config_total_paid_order_status_id');
		}	
		
		if (isset($this->request->post['config_odinass_order_status_id'])) {
			$this->data['config_odinass_order_status_id'] = $this->request->post['config_odinass_order_status_id'];
		} elseif ($this->config->get('config_odinass_order_status_id')) {
			$this->data['config_odinass_order_status_id'] = $this->config->get('config_odinass_order_status_id');	
		} else {
			$this->data['config_odinass_order_status_id'] = array();			
		}
		
		if (isset($this->request->post['config_problem_order_status_id'])) {
			$this->data['config_problem_order_status_id'] = $this->request->post['config_problem_order_status_id'];
		} elseif ($this->config->get('config_problem_order_status_id')) {
			$this->data['config_problem_order_status_id'] = $this->config->get('config_problem_order_status_id');	
		} else {
			$this->data['config_problem_order_status_id'] = array();			
		}
		
		if (isset($this->request->post['config_problem_quality_order_status_id'])) {
			$this->data['config_problem_quality_order_status_id'] = $this->request->post['config_problem_quality_order_status_id'];
		} elseif ($this->config->get('config_problem_quality_order_status_id')) {
			$this->data['config_problem_quality_order_status_id'] = $this->config->get('config_problem_quality_order_status_id');	
		} else {
			$this->data['config_problem_quality_order_status_id'] = array();			
		}
		
		if (isset($this->request->post['config_toapprove_order_status_id'])) {
			$this->data['config_toapprove_order_status_id'] = $this->request->post['config_toapprove_order_status_id'];
		} elseif ($this->config->get('config_toapprove_order_status_id')) {
			$this->data['config_toapprove_order_status_id'] = $this->config->get('config_toapprove_order_status_id');	
		} else {
			$this->data['config_toapprove_order_status_id'] = array();			
		}
		
		$this->load->model('sale/reject_reason');
		$this->data['reject_reasons'] = $this->model_sale_reject_reason->getRejectReasons();
		
		if (isset($this->request->post['config_brandmanager_fail_order_status_id'])) {
			$this->data['config_brandmanager_fail_order_status_id'] = $this->request->post['config_brandmanager_fail_order_status_id'];
		} elseif ($this->config->get('config_brandmanager_fail_order_status_id')) {
			$this->data['config_brandmanager_fail_order_status_id'] = $this->config->get('config_brandmanager_fail_order_status_id');	
		} else {
			$this->data['config_brandmanager_fail_order_status_id'] = array();			
		}
		
		if (isset($this->request->post['config_manager_confirmed_order_status_id'])) {
			$this->data['config_manager_confirmed_order_status_id'] = $this->request->post['config_manager_confirmed_order_status_id'];
		} elseif ($this->config->get('config_manager_confirmed_order_status_id')) {
			$this->data['config_manager_confirmed_order_status_id'] = $this->config->get('config_manager_confirmed_order_status_id');	
		} else {
			$this->data['config_manager_confirmed_order_status_id'] = array();			
		}
		
		if (isset($this->request->post['config_nodelete_order_status_id'])) {
			$this->data['config_nodelete_order_status_id'] = $this->request->post['config_nodelete_order_status_id'];
		} elseif ($this->config->get('config_nodelete_order_status_id')) {
			$this->data['config_nodelete_order_status_id'] = $this->config->get('config_nodelete_order_status_id');	
		} else {
			$this->data['config_nodelete_order_status_id'] = array();			
		}
		
		if (isset($this->request->post['config_amazonlist_order_status_id'])) {
			$this->data['config_amazonlist_order_status_id'] = $this->request->post['config_amazonlist_order_status_id'];
		} elseif ($this->config->get('config_amazonlist_order_status_id')) {
			$this->data['config_amazonlist_order_status_id'] = $this->config->get('config_amazonlist_order_status_id');	
		} else {
			$this->data['config_amazonlist_order_status_id'] = array();			
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	
		
		if (isset($this->request->post['config_stock_display'])) {
			$this->data['config_stock_display'] = $this->request->post['config_stock_display'];
		} else {
			$this->data['config_stock_display'] = $this->config->get('config_stock_display');			
		}
		
		if (isset($this->request->post['config_stock_warning'])) {
			$this->data['config_stock_warning'] = $this->request->post['config_stock_warning'];
		} else {
			$this->data['config_stock_warning'] = $this->config->get('config_stock_warning');		
		}
		
		if (isset($this->request->post['config_stock_checkout'])) {
			$this->data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		} else {
			$this->data['config_stock_checkout'] = $this->config->get('config_stock_checkout');		
		}
		
		if (isset($this->request->post['config_default_queue'])) {
			$this->data['config_default_queue'] = $this->request->post['config_default_queue'];
		} else {
			$this->data['config_default_queue'] = $this->config->get('config_default_queue');		
		}
		
		if (isset($this->request->post['config_default_queue'])) {
			$this->data['config_default_queue'] = $this->request->post['config_default_queue'];
		} else {
			$this->data['config_default_queue'] = $this->config->get('config_default_queue');		
		}
		
		if (isset($this->request->post['config_default_alert_queue'])) {
			$this->data['config_default_alert_queue'] = $this->request->post['config_default_alert_queue'];
		} else {
			$this->data['config_default_alert_queue'] = $this->config->get('config_default_alert_queue');		
		}
		
		if (isset($this->request->post['config_default_manager_group'])) {
			$this->data['config_default_manager_group'] = $this->request->post['config_default_manager_group'];
		} else {
			$this->data['config_default_manager_group'] = $this->config->get('config_default_manager_group');		
		}
		
		$this->load->model('user/user_group');
		
		$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
		
		if (isset($this->request->post['config_stock_status_id'])) {
			$this->data['config_stock_status_id'] = $this->request->post['config_stock_status_id'];
		} else {
			$this->data['config_stock_status_id'] = $this->config->get('config_stock_status_id');			
		}
		
		if (isset($this->request->post['config_not_in_stock_status_id'])) {
			$this->data['config_not_in_stock_status_id'] = $this->request->post['config_not_in_stock_status_id'];
		} else {
			$this->data['config_not_in_stock_status_id'] = $this->config->get('config_not_in_stock_status_id');			
		}
		
		if (isset($this->request->post['config_in_stock_status_id'])) {
			$this->data['config_in_stock_status_id'] = $this->request->post['config_in_stock_status_id'];
		} else {
			$this->data['config_in_stock_status_id'] = $this->config->get('config_in_stock_status_id');			
		}
		
		if (isset($this->request->post['config_partly_in_stock_status_id'])) {
			$this->data['config_partly_in_stock_status_id'] = $this->request->post['config_partly_in_stock_status_id'];
		} else {
			$this->data['config_partly_in_stock_status_id'] = $this->config->get('config_partly_in_stock_status_id');			
		}
		
		$this->load->model('localisation/stock_status');
		
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		if (isset($this->request->post['config_affiliate_id'])) {
			$this->data['config_affiliate_id'] = $this->request->post['config_affiliate_id'];
		} else {
			$this->data['config_affiliate_id'] = $this->config->get('config_affiliate_id');		
		}
		
		if (isset($this->request->post['config_commission'])) {
			$this->data['config_commission'] = $this->request->post['config_commission'];
		} elseif ($this->config->has('config_commission')) {
			$this->data['config_commission'] = $this->config->get('config_commission');		
		} else {
			$this->data['config_commission'] = '5.00';
		}
		
		if (isset($this->request->post['config_return_id'])) {
			$this->data['config_return_id'] = $this->request->post['config_return_id'];
		} else {
			$this->data['config_return_id'] = $this->config->get('config_return_id');		
		}
		
		if (isset($this->request->post['config_return_status_id'])) {
			$this->data['config_return_status_id'] = $this->request->post['config_return_status_id'];
		} else {
			$this->data['config_return_status_id'] = $this->config->get('config_return_status_id');		
		}
		
		$this->load->model('localisation/return_status');
		
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();	
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['config_logo'])) {
			$this->data['config_logo'] = $this->request->post['config_logo'];
		} else {
			$this->data['config_logo'] = $this->config->get('config_logo');			
		}
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo')) && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);		
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['config_icon'])) {
			$this->data['config_icon'] = $this->request->post['config_icon'];
		} else {
			$this->data['config_icon'] = $this->config->get('config_icon');			
		}
		
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon')) && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);		
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['config_noimage'])) {
			$this->data['config_noimage'] = $this->request->post['config_noimage'];
		} else {
			$this->data['config_noimage'] = $this->config->get('config_noimage');			
		}
		
		if ($this->config->get('config_noimage') && file_exists(DIR_IMAGE . $this->config->get('config_noimage')) && is_file(DIR_IMAGE . $this->config->get('config_noimage'))) {
			$this->data['noimage'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 100, 100);		
		} else {
			$this->data['noimage'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if (isset($this->request->post['config_image_category_width'])) {
			$this->data['config_image_category_width'] = $this->request->post['config_image_category_width'];
		} else {
			$this->data['config_image_category_width'] = $this->config->get('config_image_category_width');
		}
		
		if (isset($this->request->post['config_image_category_height'])) {
			$this->data['config_image_category_height'] = $this->request->post['config_image_category_height'];
		} else {
			$this->data['config_image_category_height'] = $this->config->get('config_image_category_height');
		}
		
		if (isset($this->request->post['config_image_thumb_width'])) {
			$this->data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
		} else {
			$this->data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
		}
		
		if (isset($this->request->post['config_image_thumb_height'])) {
			$this->data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
		} else {
			$this->data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
		}
		
		if (isset($this->request->post['config_image_popup_width'])) {
			$this->data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
		} else {
			$this->data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
		}
		
		if (isset($this->request->post['config_image_popup_height'])) {
			$this->data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
		} else {
			$this->data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
		}
		
		if (isset($this->request->post['config_image_product_width'])) {
			$this->data['config_image_product_width'] = $this->request->post['config_image_product_width'];
		} else {
			$this->data['config_image_product_width'] = $this->config->get('config_image_product_width');
		}
		
		if (isset($this->request->post['config_image_product_height'])) {
			$this->data['config_image_product_height'] = $this->request->post['config_image_product_height'];
		} else {
			$this->data['config_image_product_height'] = $this->config->get('config_image_product_height');
		}
		
		if (isset($this->request->post['config_image_additional_width'])) {
			$this->data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
		} else {
			$this->data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
		}
		
		if (isset($this->request->post['config_image_additional_height'])) {
			$this->data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
		} else {
			$this->data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
		}
		
		if (isset($this->request->post['config_image_related_width'])) {
			$this->data['config_image_related_width'] = $this->request->post['config_image_related_width'];
		} else {
			$this->data['config_image_related_width'] = $this->config->get('config_image_related_width');
		}
		
		if (isset($this->request->post['config_image_related_height'])) {
			$this->data['config_image_related_height'] = $this->request->post['config_image_related_height'];
		} else {
			$this->data['config_image_related_height'] = $this->config->get('config_image_related_height');
		}
		
		if (isset($this->request->post['config_image_compare_width'])) {
			$this->data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		} else {
			$this->data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
		}
		
		if (isset($this->request->post['config_image_compare_height'])) {
			$this->data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		} else {
			$this->data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
		}	
		
		if (isset($this->request->post['config_image_wishlist_width'])) {
			$this->data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		} else {
			$this->data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
		}
		
		if (isset($this->request->post['config_image_wishlist_height'])) {
			$this->data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		} else {
			$this->data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
		}	
		
		if (isset($this->request->post['config_image_cart_width'])) {
			$this->data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		} else {
			$this->data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
		}
		
		if (isset($this->request->post['config_image_cart_height'])) {
			$this->data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		} else {
			$this->data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
		}		
		
		if (isset($this->request->post['config_ftp_host'])) {
			$this->data['config_ftp_host'] = $this->request->post['config_ftp_host'];
		} elseif ($this->config->get('config_ftp_host')) {
			$this->data['config_ftp_host'] = $this->config->get('config_ftp_host');		
		} else {
			$this->data['config_ftp_host'] = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		}
		
		if (isset($this->request->post['config_ftp_port'])) {
			$this->data['config_ftp_port'] = $this->request->post['config_ftp_port'];
		} elseif ($this->config->get('config_ftp_port')) {
			$this->data['config_ftp_port'] = $this->config->get('config_ftp_port');
		} else {
			$this->data['config_ftp_port'] = 21;
		}
		
		if (isset($this->request->post['config_ftp_username'])) {
			$this->data['config_ftp_username'] = $this->request->post['config_ftp_username'];
		} else {
			$this->data['config_ftp_username'] = $this->config->get('config_ftp_username');
		}
		
		if (isset($this->request->post['config_ftp_password'])) {
			$this->data['config_ftp_password'] = $this->request->post['config_ftp_password'];
		} else {
			$this->data['config_ftp_password'] = $this->config->get('config_ftp_password');
		}
		
		if (isset($this->request->post['config_ftp_root'])) {
			$this->data['config_ftp_root'] = $this->request->post['config_ftp_root'];
		} else {
			$this->data['config_ftp_root'] = $this->config->get('config_ftp_root');
		}
		
		if (isset($this->request->post['config_ftp_status'])) {
			$this->data['config_ftp_status'] = $this->request->post['config_ftp_status'];
		} else {
			$this->data['config_ftp_status'] = $this->config->get('config_ftp_status');
		}
		
		if (isset($this->request->post['config_mail_protocol'])) {
			$this->data['config_mail_protocol'] = $this->request->post['config_mail_protocol'];
		} else {
			$this->data['config_mail_protocol'] = $this->config->get('config_mail_protocol');
		}
		
		if (isset($this->request->post['config_mail_parameter'])) {
			$this->data['config_mail_parameter'] = $this->request->post['config_mail_parameter'];
		} else {
			$this->data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
		}
		
			//TRIGGER MAILS
		if (isset($this->request->post['config_mail_trigger_protocol'])) {
			$this->data['config_mail_trigger_protocol'] = $this->request->post['config_mail_trigger_protocol'];
		} else {
			$this->data['config_mail_trigger_protocol'] = $this->config->get('config_mail_trigger_protocol');
		}

		if (isset($this->request->post['config_mail_trigger_name_from'])) {
			$this->data['config_mail_trigger_name_from'] = $this->request->post['config_mail_trigger_name_from'];
		} else {
			$this->data['config_mail_trigger_name_from'] = $this->config->get('config_mail_trigger_name_from');
		}

		if (isset($this->request->post['config_mail_trigger_name_from'])) {
			$this->data['config_mail_trigger_name_from'] = $this->request->post['config_mail_trigger_name_from'];
		} else {
			$this->data['config_mail_trigger_name_from'] = $this->config->get('config_mail_trigger_name_from');
		}

		if (isset($this->request->post['config_mail_trigger_mail_from'])) {
			$this->data['config_mail_trigger_mail_from'] = $this->request->post['config_mail_trigger_mail_from'];
		} else {
			$this->data['config_mail_trigger_mail_from'] = $this->config->get('config_mail_trigger_mail_from');
		}

			//PAYMENT
		if (isset($this->request->post['config_payment_mail_from'])) {
			$this->data['config_payment_mail_from'] = $this->request->post['config_payment_mail_from'];
		} else {
			$this->data['config_payment_mail_from'] = $this->config->get('config_payment_mail_from');
		}

		if (isset($this->request->post['config_payment_mail_to'])) {
			$this->data['config_payment_mail_to'] = $this->request->post['config_payment_mail_to'];
		} else {
			$this->data['config_payment_mail_to'] = $this->config->get('config_payment_mail_to');
		}

		if (isset($this->request->post['config_main_redirect_domain'])) {
			$this->data['config_main_redirect_domain'] = $this->request->post['config_main_redirect_domain'];
		} else {
			$this->data['config_main_redirect_domain'] = $this->config->get('config_main_redirect_domain');
		}

		if (isset($this->request->post['config_main_wp_blog_domain'])) {
			$this->data['config_main_wp_blog_domain'] = $this->request->post['config_main_wp_blog_domain'];
		} else {
			$this->data['config_main_wp_blog_domain'] = $this->config->get('config_main_wp_blog_domain');
		}

		if (isset($this->request->post['config_courier_mail_to'])) {
			$this->data['config_courier_mail_to'] = $this->request->post['config_courier_mail_to'];
		} else {
			$this->data['config_courier_mail_to'] = $this->config->get('config_courier_mail_to');
		}


			//SPARKPOST
		if (isset($this->request->post['config_sparkpost_bounce_enable'])) {
			$this->data['config_sparkpost_bounce_enable'] = $this->request->post['config_sparkpost_bounce_enable'];
		} else {
			$this->data['config_sparkpost_bounce_enable'] = $this->config->get('config_sparkpost_bounce_enable');
		}

		if (isset($this->request->post['config_sparkpost_api_url'])) {
			$this->data['config_sparkpost_api_url'] = $this->request->post['config_sparkpost_api_url'];
		} else {
			$this->data['config_sparkpost_api_url'] = $this->config->get('config_sparkpost_api_url');
		}
		
		if (isset($this->request->post['config_sparkpost_api_key'])) {
			$this->data['config_sparkpost_api_key'] = $this->request->post['config_sparkpost_api_key'];
		} else {
			$this->data['config_sparkpost_api_key'] = $this->config->get('config_sparkpost_api_key');
		}

		if (isset($this->request->post['config_sparkpost_api_user'])) {
			$this->data['config_sparkpost_api_user'] = $this->request->post['config_sparkpost_api_user'];
		} else {
			$this->data['config_sparkpost_api_user'] = $this->config->get('config_sparkpost_api_user');
		}

			//MAILGUN
		if (isset($this->request->post['config_mailgun_bounce_enable'])) {
			$this->data['config_mailgun_bounce_enable'] = $this->request->post['config_mailgun_bounce_enable'];
		} else {
			$this->data['config_mailgun_bounce_enable'] = $this->config->get('config_mailgun_bounce_enable');
		}

		if (isset($this->request->post['config_mailgun_api_url'])) {
			$this->data['config_mailgun_api_url'] = $this->request->post['config_mailgun_api_url'];
		} else {
			$this->data['config_mailgun_api_url'] = $this->config->get('config_mailgun_api_url');
		}

		if (isset($this->request->post['config_mailgun_api_public_key'])) {
			$this->data['config_mailgun_api_public_key'] = $this->request->post['config_mailgun_api_public_key'];
		} else {
			$this->data['config_mailgun_api_public_key'] = $this->config->get('config_mailgun_api_public_key');
		}

		if (isset($this->request->post['config_mailgun_api_private_key'])) {
			$this->data['config_mailgun_api_private_key'] = $this->request->post['config_mailgun_api_private_key'];
		} else {
			$this->data['config_mailgun_api_private_key'] = $this->config->get('config_mailgun_api_private_key');
		}


		if (isset($this->request->post['config_mailgun_api_signing_key'])) {
			$this->data['config_mailgun_api_signing_key'] = $this->request->post['config_mailgun_api_signing_key'];
		} else {
			$this->data['config_mailgun_api_signing_key'] = $this->config->get('config_mailgun_api_signing_key');
		}

		if (isset($this->request->post['config_mailgun_mail_limit'])) {
			$this->data['config_mailgun_mail_limit'] = $this->request->post['config_mailgun_mail_limit'];
		} else {
			$this->data['config_mailgun_mail_limit'] = $this->config->get('config_mailgun_mail_limit');
		}

		
		if (isset($this->request->post['config_mailwizz_enable'])) {
			$this->data['config_mailwizz_enable'] = $this->request->post['config_mailwizz_enable'];
		} else {
			$this->data['config_mailwizz_enable'] = $this->config->get('config_mailwizz_enable');
		}
		
		if (isset($this->request->post['config_mailwizz_api_uri'])) {
			$this->data['config_mailwizz_api_uri'] = $this->request->post['config_mailwizz_api_uri'];
		} else {
			$this->data['config_mailwizz_api_uri'] = $this->config->get('config_mailwizz_api_uri');
		}
		
		if (isset($this->request->post['config_mailwizz_api_key'])) {
			$this->data['config_mailwizz_api_key'] = $this->request->post['config_mailwizz_api_key'];
		} else {
			$this->data['config_mailwizz_api_key'] = $this->config->get('config_mailwizz_api_key');
		}
		
		if (isset($this->request->post['config_mailwizz_mapping_newsletter'])) {
			$this->data['config_mailwizz_mapping_newsletter'] = $this->request->post['config_mailwizz_mapping_newsletter'];
		} else {
			$this->data['config_mailwizz_mapping_newsletter'] = $this->config->get('config_mailwizz_mapping_newsletter');
		}
		
		if (isset($this->request->post['config_mailwizz_mapping_newsletter_news'])) {
			$this->data['config_mailwizz_mapping_newsletter_news'] = $this->request->post['config_mailwizz_mapping_newsletter_news'];
		} else {
			$this->data['config_mailwizz_mapping_newsletter_news'] = $this->config->get('config_mailwizz_mapping_newsletter_news');
		}
		
		if (isset($this->request->post['config_mailwizz_mapping_newsletter_personal'])) {
			$this->data['config_mailwizz_mapping_newsletter_personal'] = $this->request->post['config_mailwizz_mapping_newsletter_personal'];
		} else {
			$this->data['config_mailwizz_mapping_newsletter_personal'] = $this->config->get('config_mailwizz_mapping_newsletter_personal');
		}

		if (isset($this->request->post['config_mailwizz_noorder_days'])) {
			$this->data['config_mailwizz_noorder_days'] = $this->request->post['config_mailwizz_noorder_days'];
		} else {
			$this->data['config_mailwizz_noorder_days'] = $this->config->get('config_mailwizz_noorder_days');
		}
		
		if (isset($this->request->post['config_smtp_host'])) {
			$this->data['config_smtp_host'] = $this->request->post['config_smtp_host'];
		} else {
			$this->data['config_smtp_host'] = $this->config->get('config_smtp_host');
		}		
		
		if (isset($this->request->post['config_smtp_username'])) {
			$this->data['config_smtp_username'] = $this->request->post['config_smtp_username'];
		} else {
			$this->data['config_smtp_username'] = $this->config->get('config_smtp_username');
		}	
		
		if (isset($this->request->post['config_smtp_password'])) {
			$this->data['config_smtp_password'] = $this->request->post['config_smtp_password'];
		} else {
			$this->data['config_smtp_password'] = $this->config->get('config_smtp_password');
		}	
		
		if (isset($this->request->post['config_smtp_port'])) {
			$this->data['config_smtp_port'] = $this->request->post['config_smtp_port'];
		} elseif ($this->config->get('config_smtp_port')) {
			$this->data['config_smtp_port'] = $this->config->get('config_smtp_port');
		} else {
			$this->data['config_smtp_port'] = 25;
		}	
		
		if (isset($this->request->post['config_smtp_timeout'])) {
			$this->data['config_smtp_timeout'] = $this->request->post['config_smtp_timeout'];
		} elseif ($this->config->get('config_smtp_timeout')) {
			$this->data['config_smtp_timeout'] = $this->config->get('config_smtp_timeout');
		} else {
			$this->data['config_smtp_timeout'] = 5;	
		}	
		
		if (isset($this->request->post['config_alert_mail'])) {
			$this->data['config_alert_mail'] = $this->request->post['config_alert_mail'];
		} else {
			$this->data['config_alert_mail'] = $this->config->get('config_alert_mail');
		}
		
		if (isset($this->request->post['config_account_mail'])) {
			$this->data['config_account_mail'] = $this->request->post['config_account_mail'];
		} else {
			$this->data['config_account_mail'] = $this->config->get('config_account_mail');
		}
		
		if (isset($this->request->post['config_alert_emails'])) {
			$this->data['config_alert_emails'] = $this->request->post['config_alert_emails'];
		} else {
			$this->data['config_alert_emails'] = $this->config->get('config_alert_emails');
		}
		
		if (isset($this->request->post['config_fraud_detection'])) {
			$this->data['config_fraud_detection'] = $this->request->post['config_fraud_detection']; 
		} else {
			$this->data['config_fraud_detection'] = $this->config->get('config_fraud_detection');
		}	
		
		if (isset($this->request->post['config_fraud_key'])) {
			$this->data['config_fraud_key'] = $this->request->post['config_fraud_key']; 
		} else {
			$this->data['config_fraud_key'] = $this->config->get('config_fraud_key');
		}		
		
		if (isset($this->request->post['config_fraud_score'])) {
			$this->data['config_fraud_score'] = $this->request->post['config_fraud_score']; 
		} else {
			$this->data['config_fraud_score'] = $this->config->get('config_fraud_score');
		}	
		
		if (isset($this->request->post['config_fraud_status_id'])) {
			$this->data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id']; 
		} else {
			$this->data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
		}		
		
		if (isset($this->request->post['config_secure'])) {
			$this->data['config_secure'] = $this->request->post['config_secure'];
		} else {
			$this->data['config_secure'] = $this->config->get('config_secure');
		}
		
		if (isset($this->request->post['config_shared'])) {
			$this->data['config_shared'] = $this->request->post['config_shared'];
		} else {
			$this->data['config_shared'] = $this->config->get('config_shared');
		}
		
		if (isset($this->request->post['config_robots'])) {
			$this->data['config_robots'] = $this->request->post['config_robots'];
		} else {
			$this->data['config_robots'] = $this->config->get('config_robots');
		}
		
		if (isset($this->request->post['config_seo_url'])) {
			$this->data['config_seo_url'] = $this->request->post['config_seo_url'];
		} else {
			$this->data['config_seo_url'] = $this->config->get('config_seo_url');
		}
		
		if (isset($this->request->post['config_seo_url_type'])) {
			$this->data['config_seo_url_type'] = $this->request->post['config_seo_url_type'];
		} elseif ($this->config->get('config_seo_url_type')) {
			$this->data['config_seo_url_type'] = $this->config->get('config_seo_url_type');
		} else {
			$this->data['config_seo_url_type'] = 'seo_url';
		}
		
		$this->data['seo_types'] = array();
		$this->data['seo_types'][] = array('type' => 'seo_url', 'name' => $this->language->get('text_seo_url'));
		$this->data['seo_types'][] = array('type' => 'seo_pro', 'name' => $this->language->get('text_seo_pro'));
		
		if (isset($this->request->post['config_seo_url_include_path'])) {
			$this->data['config_seo_url_include_path'] = $this->request->post['config_seo_url_include_path'];
		} else {
			$this->data['config_seo_url_include_path'] = $this->config->get('config_seo_url_include_path');
		}
		
		if (isset($this->request->post['config_seo_url_postfix'])) {
			$this->data['config_seo_url_postfix'] = $this->request->post['config_seo_url_postfix'];
		} else {
			$this->data['config_seo_url_postfix'] = $this->config->get('config_seo_url_postfix');
		}
		
		if (isset($this->request->post['config_file_extension_allowed'])) {
			$this->data['config_file_extension_allowed'] = $this->request->post['config_file_extension_allowed'];
		} else {
			$this->data['config_file_extension_allowed'] = $this->config->get('config_file_extension_allowed');
		}
		
		if (isset($this->request->post['config_file_mime_allowed'])) {
			$this->data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
		} else {
			$this->data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}		
		
		if (isset($this->request->post['config_maintenance'])) {
			$this->data['config_maintenance'] = $this->request->post['config_maintenance'];
		} else {
			$this->data['config_maintenance'] = $this->config->get('config_maintenance');
		}
		
		if (isset($this->request->post['config_password'])) {
			$this->data['config_password'] = $this->request->post['config_password'];
		} else {
			$this->data['config_password'] = $this->config->get('config_password');
		}
		
		if (isset($this->request->post['config_encryption'])) {
			$this->data['config_encryption'] = $this->request->post['config_encryption'];
		} else {
			$this->data['config_encryption'] = $this->config->get('config_encryption');
		}
		
		if (isset($this->request->post['config_compression'])) {
			$this->data['config_compression'] = $this->request->post['config_compression']; 
		} else {
			$this->data['config_compression'] = $this->config->get('config_compression');
		}
		
		if (isset($this->request->post['config_error_display'])) {
			$this->data['config_error_display'] = $this->request->post['config_error_display']; 
		} else {
			$this->data['config_error_display'] = $this->config->get('config_error_display');
		}
		
		if (isset($this->request->post['config_error_log'])) {
			$this->data['config_error_log'] = $this->request->post['config_error_log']; 
		} else {
			$this->data['config_error_log'] = $this->config->get('config_error_log');
		}
		
		if (isset($this->request->post['config_error_filename'])) {
			$this->data['config_error_filename'] = $this->request->post['config_error_filename']; 
		} else {
			$this->data['config_error_filename'] = $this->config->get('config_error_filename');
		}
		
		if (isset($this->request->post['config_sms_to'])) {
			$this->data['config_sms_to'] = $this->request->post['config_sms_to'];
		} else {
			$this->data['config_sms_to'] = $this->config->get('config_sms_to');
		}
		
		/*tracker*/
		if (isset($this->request->post['config_sms_tracker_leave_main_warehouse_enabled'])) {
			$this->data['config_sms_tracker_leave_main_warehouse_enabled'] = $this->request->post['config_sms_tracker_leave_main_warehouse_enabled'];
		} else {
			$this->data['config_sms_tracker_leave_main_warehouse_enabled'] = $this->config->get('config_sms_tracker_leave_main_warehouse_enabled');
		}
		
		if (isset($this->request->post['config_sms_tracker_leave_main_warehouse'])) {
			$this->data['config_sms_tracker_leave_main_warehouse'] = $this->request->post['config_sms_tracker_leave_main_warehouse'];
		} else {
			$this->data['config_sms_tracker_leave_main_warehouse'] = $this->config->get('config_sms_tracker_leave_main_warehouse');
		}
		
		if (isset($this->request->post['config_sms_from'])) {
			$this->data['config_sms_from'] = $this->request->post['config_sms_from'];
		} else {
			$this->data['config_sms_from'] = $this->config->get('config_sms_from');
		}
		
		if (isset($this->request->post['config_sms_message'])) {
			$this->data['config_sms_message'] = $this->request->post['config_sms_message'];
		} else {
			$this->data['config_sms_message'] = $this->config->get('config_sms_message');
		}
		
		if (isset($this->request->post['config_sms_send_new_order_status'])) {
			$this->data['config_sms_send_new_order_status']
			= $this->request->post['config_sms_send_new_order_status'];
		} else {
			$this->data['config_sms_send_new_order_status']
			= $this->config->get('config_sms_send_new_order_status');
		}
		
		if (isset($this->request->post['config_sms_new_order_status_message'])) {
			$this->data['config_sms_new_order_status_message']
			= $this->request->post['config_sms_new_order_status_message'];
		} else {
			$this->data['config_sms_new_order_status_message']
			= (array)$this->config->get('config_sms_new_order_status_message');
		}
		
		if (isset($this->request->post['config_sms_send_new_order'])) {
			$this->data['config_sms_send_new_order']
			= $this->request->post['config_sms_send_new_order'];
		} else {
			$this->data['config_sms_send_new_order']
			= $this->config->get('config_sms_send_new_order');
		}
		
		if (isset($this->request->post['config_sms_new_order_message'])) {
			$this->data['config_sms_new_order_message']
			= $this->request->post['config_sms_new_order_message'];
		} else {
			$this->data['config_sms_new_order_message']
			= $this->config->get('config_sms_new_order_message');
		}
		
		if (isset($this->request->post['config_sms_alert'])) {
			$this->data['config_sms_alert'] = $this->request->post['config_sms_alert'];
		} else {
			$this->data['config_sms_alert'] = $this->config->get('config_sms_alert');
		}
		
		if (isset($this->request->post['config_sms_copy'])) {
			$this->data['config_sms_copy'] = $this->request->post['config_sms_copy'];
		} else {
			$this->data['config_sms_copy'] = $this->config->get('config_sms_copy');
		}
		
		if (isset($this->request->post['config_google_analytics'])) {
			$this->data['config_google_analytics'] = $this->request->post['config_google_analytics']; 
		} else {
			$this->data['config_google_analytics'] = $this->config->get('config_google_analytics');
		}
		
		if (isset($this->request->post['config_google_analytics_header'])) {
			$this->data['config_google_analytics_header'] = $this->request->post['config_google_analytics_header']; 
		} else {
			$this->data['config_google_analytics_header'] = $this->config->get('config_google_analytics_header');
		}

		if (isset($this->request->post['config_fb_pixel_header'])) {
			$this->data['config_fb_pixel_header'] = $this->request->post['config_fb_pixel_header']; 
		} else {
			$this->data['config_fb_pixel_header'] = $this->config->get('config_fb_pixel_header');
		}

		if (isset($this->request->post['config_fb_pixel_body'])) {
			$this->data['config_fb_pixel_body'] = $this->request->post['config_fb_pixel_body']; 
		} else {
			$this->data['config_fb_pixel_body'] = $this->config->get('config_fb_pixel_body');
		}

		if (isset($this->request->post['config_vk_enable_pixel'])) {
			$this->data['config_vk_enable_pixel'] = $this->request->post['config_vk_enable_pixel']; 
		} else {
			$this->data['config_vk_enable_pixel'] = $this->config->get('config_vk_enable_pixel');
		}

		if (isset($this->request->post['config_vk_pixel_id'])) {
			$this->data['config_vk_pixel_id'] = $this->request->post['config_vk_pixel_id']; 
		} else {
			$this->data['config_vk_pixel_id'] = $this->config->get('config_vk_pixel_id');
		}

		if (isset($this->request->post['config_vk_pricelist_id'])) {
			$this->data['config_vk_pricelist_id'] = $this->request->post['config_vk_pricelist_id']; 
		} else {
			$this->data['config_vk_pricelist_id'] = $this->config->get('config_vk_pricelist_id');
		}

		if (isset($this->request->post['config_vk_pixel_header'])) {
			$this->data['config_vk_pixel_header'] = $this->request->post['config_vk_pixel_header']; 
		} else {
			$this->data['config_vk_pixel_header'] = $this->config->get('config_vk_pixel_header');
		}

		if (isset($this->request->post['config_vk_pixel_body'])) {
			$this->data['config_vk_pixel_body'] = $this->request->post['config_vk_pixel_body']; 
		} else {
			$this->data['config_vk_pixel_body'] = $this->config->get('config_vk_pixel_body');
		}
		
		if (isset($this->request->post['config_gtm_header'])) {
			$this->data['config_gtm_header'] = $this->request->post['config_gtm_header']; 
		} else {
			$this->data['config_gtm_header'] = $this->config->get('config_gtm_header');
		}
		
		if (isset($this->request->post['config_gtm_body'])) {
			$this->data['config_gtm_body'] = $this->request->post['config_gtm_body']; 
		} else {
			$this->data['config_gtm_body'] = $this->config->get('config_gtm_body');
		}
		
		if (isset($this->request->post['config_preload_links'])) {
			$this->data['config_preload_links'] = $this->request->post['config_preload_links']; 
		} else {
			$this->data['config_preload_links'] = $this->config->get('config_preload_links');
		}

		if (isset($this->request->post['config_header_min_scripts'])) {
			$this->data['config_header_min_scripts'] = $this->request->post['config_header_min_scripts']; 
		} else {
			$this->data['config_header_min_scripts'] = $this->config->get('config_header_min_scripts');
		}


		if (isset($this->request->post['config_header_excluded_scripts'])) {
			$this->data['config_header_excluded_scripts'] = $this->request->post['config_header_excluded_scripts']; 
		} else {
			$this->data['config_header_excluded_scripts'] = $this->config->get('config_header_excluded_scripts');
		}

		if (isset($this->request->post['config_header_min_styles'])) {
			$this->data['config_header_min_styles'] = $this->request->post['config_header_min_styles']; 
		} else {
			$this->data['config_header_min_styles'] = $this->config->get('config_header_min_styles');
		}

		if (isset($this->request->post['config_header_excluded_styles'])) {
			$this->data['config_header_excluded_styles'] = $this->request->post['config_header_excluded_styles']; 
		} else {
			$this->data['config_header_excluded_styles'] = $this->config->get('config_header_excluded_styles');
		}

		if (isset($this->request->post['config_footer_min_scripts'])) {
			$this->data['config_footer_min_scripts'] = $this->request->post['config_footer_min_scripts']; 
		} else {
			$this->data['config_footer_min_scripts'] = $this->config->get('config_footer_min_scripts');
		}
		
		if (isset($this->request->post['config_footer_min_styles'])) {
			$this->data['config_footer_min_styles'] = $this->request->post['config_footer_min_styles']; 
		} else {
			$this->data['config_footer_min_styles'] = $this->config->get('config_footer_min_styles');
		}

		if (isset($this->request->post['config_footer_excluded_scripts'])) {
			$this->data['config_footer_excluded_scripts'] = $this->request->post['config_footer_excluded_scripts']; 
		} else {
			$this->data['config_footer_excluded_scripts'] = $this->config->get('config_footer_excluded_scripts');
		}
		
		if (isset($this->request->post['config_footer_excluded_styles'])) {
			$this->data['config_footer_excluded_styles'] = $this->request->post['config_footer_excluded_styles']; 
		} else {
			$this->data['config_footer_excluded_styles'] = $this->config->get('config_footer_excluded_styles');
		}
		
		if (isset($this->request->post['config_sendpulse_script'])) {
			$this->data['config_sendpulse_script'] = $this->request->post['config_sendpulse_script']; 
		} else {
			$this->data['config_sendpulse_script'] = $this->config->get('config_sendpulse_script');
		}
		
		
		if (isset($this->request->post['config_sendpulse_id'])) {
			$this->data['config_sendpulse_id'] = $this->request->post['config_sendpulse_id']; 
		} else {
			$this->data['config_sendpulse_id'] = $this->config->get('config_sendpulse_id');
		}
		
		if (isset($this->request->post['config_onesignal_app_id'])) {
			$this->data['config_onesignal_app_id'] = $this->request->post['config_onesignal_app_id']; 
		} else {
			$this->data['config_onesignal_app_id'] = $this->config->get('config_onesignal_app_id');
		}
		
		if (isset($this->request->post['config_onesignal_api_key'])) {
			$this->data['config_onesignal_api_key'] = $this->request->post['config_onesignal_api_key']; 
		} else {
			$this->data['config_onesignal_api_key'] = $this->config->get('config_onesignal_api_key');
		}
		
		if (isset($this->request->post['config_onesignal_safari_web_id'])) {
			$this->data['config_onesignal_safari_web_id'] = $this->request->post['config_onesignal_safari_web_id']; 
		} else {
			$this->data['config_onesignal_safari_web_id'] = $this->config->get('config_onesignal_safari_web_id');
		}		
		
		if (isset($this->request->post['config_google_analitycs_id'])) {
			$this->data['config_google_analitycs_id'] = $this->request->post['config_google_analitycs_id']; 
		} else {
			$this->data['config_google_analitycs_id'] = $this->config->get('config_google_analitycs_id');
		}
		
		if (isset($this->request->post['config_google_conversion_id'])) {
			$this->data['config_google_conversion_id'] = $this->request->post['config_google_conversion_id']; 
		} else {
			$this->data['config_google_conversion_id'] = $this->config->get('config_google_conversion_id');
		}
		
		if (isset($this->request->post['config_google_merchant_id'])) {
			$this->data['config_google_merchant_id'] = $this->request->post['config_google_merchant_id']; 
		} else {
			$this->data['config_google_merchant_id'] = $this->config->get('config_google_merchant_id');
		}
		
		if (isset($this->request->post['config_google_remarketing_type'])) {
			$this->data['config_google_remarketing_type'] = $this->request->post['config_google_remarketing_type']; 
		} else {
			$this->data['config_google_remarketing_type'] = $this->config->get('config_google_remarketing_type');
		}
		
		if (isset($this->request->post['config_google_ecommerce_enable'])) {
			$this->data['config_google_ecommerce_enable'] = $this->request->post['config_google_ecommerce_enable']; 
		} else {
			$this->data['config_google_ecommerce_enable'] = $this->config->get('config_google_ecommerce_enable');
		}
		
		if (isset($this->request->post['config_metrika_counter'])) {
			$this->data['config_metrika_counter'] = $this->request->post['config_metrika_counter']; 
		} else {
			$this->data['config_metrika_counter'] = $this->config->get('config_metrika_counter');
		}

			//SOAP
		if (isset($this->request->post['config_odinass_soap_uri'])) {
			$this->data['config_odinass_soap_uri'] = $this->request->post['config_odinass_soap_uri']; 
		} else {
			$this->data['config_odinass_soap_uri'] = $this->config->get('config_odinass_soap_uri');
		}

		if (isset($this->request->post['config_odinass_soap_user'])) {
			$this->data['config_odinass_soap_user'] = $this->request->post['config_odinass_soap_user']; 
		} else {
			$this->data['config_odinass_soap_user'] = $this->config->get('config_odinass_soap_user');
		}

		if (isset($this->request->post['config_odinass_soap_passwd'])) {
			$this->data['config_odinass_soap_passwd'] = $this->request->post['config_odinass_soap_passwd']; 
		} else {
			$this->data['config_odinass_soap_passwd'] = $this->config->get('config_odinass_soap_passwd');
		}

		if (isset($this->request->post['config_odinass_update_local_prices'])) {
			$this->data['config_odinass_update_local_prices'] = $this->request->post['config_odinass_update_local_prices']; 
		} else {
			$this->data['config_odinass_update_local_prices'] = $this->config->get('config_odinass_update_local_prices');
		}

			//Bitrix24 Bot
		if (isset($this->request->post['config_bitrix_bot_enable'])) {
			$this->data['config_bitrix_bot_enable'] = $this->request->post['config_bitrix_bot_enable']; 
		} else {
			$this->data['config_bitrix_bot_enable'] = $this->config->get('config_bitrix_bot_enable');
		}

		if (isset($this->request->post['config_bitrix_bot_domain'])) {
			$this->data['config_bitrix_bot_domain'] = $this->request->post['config_bitrix_bot_domain']; 
		} else {
			$this->data['config_bitrix_bot_domain'] = $this->config->get('config_bitrix_bot_domain');
		}

		if (isset($this->request->post['config_bitrix_bot_scope'])) {
			$this->data['config_bitrix_bot_scope'] = $this->request->post['config_bitrix_bot_scope']; 
		} else {
			$this->data['config_bitrix_bot_scope'] = $this->config->get('config_bitrix_bot_scope');
		}

		if (isset($this->request->post['config_bitrix_bot_client_id'])) {
			$this->data['config_bitrix_bot_client_id'] = $this->request->post['config_bitrix_bot_client_id']; 
		} else {
			$this->data['config_bitrix_bot_client_id'] = $this->config->get('config_bitrix_bot_client_id');
		}

		if (isset($this->request->post['config_bitrix_bot_client_secret'])) {
			$this->data['config_bitrix_bot_client_secret'] = $this->request->post['config_bitrix_bot_client_secret']; 
		} else {
			$this->data['config_bitrix_bot_client_secret'] = $this->config->get('config_bitrix_bot_client_secret');
		}

			//TELEGRAM
		if (isset($this->request->post['config_telegram_bot_enable_alerts'])) {
			$this->data['config_telegram_bot_enable_alerts'] = $this->request->post['config_telegram_bot_enable_alerts']; 
		} else {
			$this->data['config_telegram_bot_enable_alerts'] = $this->config->get('config_telegram_bot_enable_alerts');
		}

		if (isset($this->request->post['config_telegram_bot_token'])) {
			$this->data['config_telegram_bot_token'] = $this->request->post['config_telegram_bot_token']; 
		} else {
			$this->data['config_telegram_bot_token'] = $this->config->get('config_telegram_bot_token');
		}

		if (isset($this->request->post['config_telegram_bot_name'])) {
			$this->data['config_telegram_bot_name'] = $this->request->post['config_telegram_bot_name']; 
		} else {
			$this->data['config_telegram_bot_name'] = $this->config->get('config_telegram_bot_name');
		}
		

			//RNF
		if (isset($this->request->post['config_rainforest_enable_api'])) {
			$this->data['config_rainforest_enable_api'] = $this->request->post['config_rainforest_enable_api']; 
		} else {
			$this->data['config_rainforest_enable_api'] = $this->config->get('config_rainforest_enable_api');
		}

		if (isset($this->request->post['config_rainforest_asin_deletion_mode'])) {
			$this->data['config_rainforest_asin_deletion_mode'] = $this->request->post['config_rainforest_asin_deletion_mode']; 
		} else {
			$this->data['config_rainforest_asin_deletion_mode'] = $this->config->get('config_rainforest_asin_deletion_mode');
		}
		
		if (isset($this->request->post['config_rainforest_api_key'])) {
			$this->data['config_rainforest_api_key'] = $this->request->post['config_rainforest_api_key']; 
		} else {
			$this->data['config_rainforest_api_key'] = $this->config->get('config_rainforest_api_key');
		}
		
		if (isset($this->request->post['config_rainforest_api_domain_1'])) {
			$this->data['config_rainforest_api_domain_1'] = $this->request->post['config_rainforest_api_domain_1']; 
		} else {
			$this->data['config_rainforest_api_domain_1'] = $this->config->get('config_rainforest_api_domain_1');
		}

		if (isset($this->request->post['config_rainforest_enable_translation'])) {
			$this->data['config_rainforest_enable_translation'] = $this->request->post['config_rainforest_enable_translation']; 
		} else {
			$this->data['config_rainforest_enable_translation'] = $this->config->get('config_rainforest_enable_translation');
		}

		if (isset($this->request->post['config_rainforest_category_model'])) {
			$this->data['config_rainforest_category_model'] = $this->request->post['config_rainforest_category_model']; 
		} else {
			$this->data['config_rainforest_category_model'] = $this->config->get('config_rainforest_category_model');
		}

		if (isset($this->request->post['config_rainforest_enable_recursive_adding'])) {
			$this->data['config_rainforest_enable_recursive_adding'] = $this->request->post['config_rainforest_enable_recursive_adding']; 
		} else {
			$this->data['config_rainforest_enable_recursive_adding'] = $this->config->get('config_rainforest_enable_recursive_adding');
		}
		

		if (isset($this->request->post['config_rainforest_default_technical_category_id'])) {
			$this->data['config_rainforest_default_technical_category_id'] = $this->request->post['config_rainforest_default_technical_category_id']; 
		} else {
			$this->data['config_rainforest_default_technical_category_id'] = $this->config->get('config_rainforest_default_technical_category_id');
		}

		if (isset($this->request->post['config_rainforest_enable_auto_tree'])) {
			$this->data['config_rainforest_enable_auto_tree'] = $this->request->post['config_rainforest_enable_auto_tree']; 
		} else {
			$this->data['config_rainforest_enable_auto_tree'] = $this->config->get('config_rainforest_enable_auto_tree');
		}

		if (isset($this->request->post['config_rainforest_root_categories'])) {
			$this->data['config_rainforest_root_categories'] = $this->request->post['config_rainforest_root_categories']; 
		} else {
			$this->data['config_rainforest_root_categories'] = $this->config->get('config_rainforest_root_categories');
		}

		if (isset($this->request->post['config_rainforest_source_language'])) {
			$this->data['config_rainforest_source_language'] = $this->request->post['config_rainforest_source_language']; 
		} else {
			$this->data['config_rainforest_source_language'] = $this->config->get('config_rainforest_source_language');
		}

		foreach ($this->data['languages'] as $rnf_language){

			if ($rnf_language['code'] != $this->data['config_rainforest_source_language']){

				if (isset($this->request->post['config_rainforest_enable_language_' . $rnf_language['code']])) {
					$this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->request->post['config_rainforest_enable_language_' . $rnf_language['code']]; 
				} else {
					$this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_enable_language_' . $rnf_language['code']);
				}
			}

		}

		if (isset($this->request->post['config_rainforest_update_period'])) {
			$this->data['config_rainforest_update_period'] = $this->request->post['config_rainforest_update_period']; 
		} else {
			$this->data['config_rainforest_update_period'] = $this->config->get('config_rainforest_update_period');
		}

		if (isset($this->request->post['config_rainforest_category_update_period'])) {
			$this->data['config_rainforest_category_update_period'] = $this->request->post['config_rainforest_category_update_period']; 
		} else {
			$this->data['config_rainforest_category_update_period'] = $this->config->get('config_rainforest_category_update_period');
		}

		if (isset($this->request->post['config_rainforest_tg_alert_group_id'])) {
			$this->data['config_rainforest_tg_alert_group_id'] = $this->request->post['config_rainforest_tg_alert_group_id']; 
		} else {
			$this->data['config_rainforest_tg_alert_group_id'] = $this->config->get('config_rainforest_tg_alert_group_id');
		}

		if (isset($this->request->post['config_rainforest_enable_pricing'])) {
			$this->data['config_rainforest_enable_pricing'] = $this->request->post['config_rainforest_enable_pricing']; 
		} else {
			$this->data['config_rainforest_enable_pricing'] = $this->config->get('config_rainforest_enable_pricing');
		}


		if (isset($this->request->post['config_rainforest_main_formula'])) {
			$this->data['config_rainforest_main_formula'] = $this->request->post['config_rainforest_main_formula']; 
		} else {
			$this->data['config_rainforest_main_formula'] = $this->config->get('config_rainforest_main_formula');
		}

		if (isset($this->request->post['config_rainforest_supplierminrating'])) {
			$this->data['config_rainforest_supplierminrating'] = $this->request->post['config_rainforest_supplierminrating']; 
		} else {
			$this->data['config_rainforest_supplierminrating'] = $this->config->get('config_rainforest_supplierminrating');
		}

		if (isset($this->request->post['config_rainforest_supplierminrating_inner'])) {
			$this->data['config_rainforest_supplierminrating_inner'] = $this->request->post['config_rainforest_supplierminrating_inner']; 
		} else {
			$this->data['config_rainforest_supplierminrating_inner'] = $this->config->get('config_rainforest_supplierminrating_inner');
		}

		if (isset($this->request->post['config_rainforest_default_store_id'])) {
			$this->data['config_rainforest_default_store_id'] = $this->request->post['config_rainforest_default_store_id']; 
		} else {
			$this->data['config_rainforest_default_store_id'] = $this->config->get('config_rainforest_default_store_id');
		}

		if (isset($this->request->post['config_rainforest_nooffers_action'])) {
			$this->data['config_rainforest_nooffers_action'] = $this->request->post['config_rainforest_nooffers_action']; 
		} else {
			$this->data['config_rainforest_nooffers_action'] = $this->config->get('config_rainforest_nooffers_action');
		}

		if (isset($this->request->post['config_rainforest_nooffers_status_id'])) {
			$this->data['config_rainforest_nooffers_status_id'] = $this->request->post['config_rainforest_nooffers_status_id']; 
		} else {
			$this->data['config_rainforest_nooffers_status_id'] = $this->config->get('config_rainforest_nooffers_status_id');
		}
		

		$this->load->model('setting/store');
		$stores = $this->data['stores'] = $this->model_setting_store->getStores();

		foreach ($stores as $store){
			if (isset($this->request->post['config_rainforest_kg_price_' . $store['store_id']])) {
				$this->data['config_rainforest_kg_price_' . $store['store_id']] = $this->request->post['config_rainforest_kg_price_' . $store['store_id']]; 
			} else {
				$this->data['config_rainforest_kg_price_' . $store['store_id']] = $this->config->get('config_rainforest_kg_price_' . $store['store_id']);
			}
		}

		
		if (isset($this->request->post['config_priceva_enable_api'])) {
			$this->data['config_priceva_enable_api'] = $this->request->post['config_priceva_enable_api']; 
		} else {
			$this->data['config_priceva_enable_api'] = $this->config->get('config_priceva_enable_api');
		}

		
		foreach ($stores as $store){
			
			if (isset($this->request->post['config_priceva_api_key_' . $store['store_id']])) {
				$this->data['config_priceva_api_key_' . $store['store_id']] = $this->request->post['config_priceva_api_key_' . $store['store_id']]; 
			} else {
				$this->data['config_priceva_api_key_' . $store['store_id']] = $this->config->get('config_priceva_api_key_' . $store['store_id']);
			}
			
		}
		
		
		$this->data['amazon_domains'] = $this->rainforestAmazon->getValidAmazonSitesArray();	
		$this->data['amazon_filters'] = \CaponicaAmazonRainforest\Request\OfferRequest::getStaticFilterKeys();
		
		if (isset($this->request->post['config_rainforest_amazon_filters_1'])) {
			$this->data['config_rainforest_amazon_filters_1'] = $this->request->post['config_rainforest_amazon_filters_1']; 
		} else {
			$this->data['config_rainforest_amazon_filters_1'] = $this->config->get('config_rainforest_amazon_filters_1');
		}
		
		if (isset($this->request->post['config_rainforest_api_zipcode_1'])) {
			$this->data['config_rainforest_api_zipcode_1'] = $this->request->post['config_rainforest_api_zipcode_1']; 
		} else {
			$this->data['config_rainforest_api_zipcode_1'] = $this->config->get('config_rainforest_api_zipcode_1');
		}

			//Yandex Translate
		if (isset($this->request->post['config_yandex_translate_api_enable'])) {
			$this->data['config_yandex_translate_api_enable'] = $this->request->post['config_yandex_translate_api_enable']; 
		} else {
			$this->data['config_yandex_translate_api_enable'] = $this->config->get('config_yandex_translate_api_enable');
		}

		if (isset($this->request->post['config_yandex_translate_api_key'])) {
			$this->data['config_yandex_translate_api_key'] = $this->request->post['config_yandex_translate_api_key']; 
		} else {
			$this->data['config_yandex_translate_api_key'] = $this->config->get('config_yandex_translate_api_key');
		}

		if (isset($this->request->post['config_yandex_translate_api_id'])) {
			$this->data['config_yandex_translate_api_id'] = $this->request->post['config_yandex_translate_api_id']; 
		} else {
			$this->data['config_yandex_translate_api_id'] = $this->config->get('config_yandex_translate_api_id');
		}


			//LDAP AUTH
		if (isset($this->request->post['config_ldap_auth_enable'])) {
			$this->data['config_ldap_auth_enable'] = $this->request->post['config_ldap_auth_enable']; 
		} else {
			$this->data['config_ldap_auth_enable'] = $this->config->get('config_ldap_auth_enable');
		}

		if (isset($this->request->post['config_ldap_dn'])) {
			$this->data['config_ldap_dn'] = $this->request->post['config_ldap_dn']; 
		} else {
			$this->data['config_ldap_dn'] = $this->config->get('config_ldap_dn');
		}

		if (isset($this->request->post['config_ldap_host'])) {
			$this->data['config_ldap_host'] = $this->request->post['config_ldap_host']; 
		} else {
			$this->data['config_ldap_host'] = $this->config->get('config_ldap_host');
		}

		if (isset($this->request->post['config_ldap_domain'])) {
			$this->data['config_ldap_domain'] = $this->request->post['config_ldap_domain']; 
		} else {
			$this->data['config_ldap_domain'] = $this->config->get('config_ldap_domain');
		}

		if (isset($this->request->post['config_ldap_group'])) {
			$this->data['config_ldap_domain'] = $this->request->post['config_ldap_group']; 
		} else {
			$this->data['config_ldap_group'] = $this->config->get('config_ldap_group');
		}


			//GOIP4
		if (isset($this->request->post['config_goip4_user'])) {
			$this->data['config_goip4_user'] = $this->request->post['config_goip4_user']; 
		} else {
			$this->data['config_goip4_user'] = $this->config->get('config_goip4_user');
		}

		if (isset($this->request->post['config_goip4_passwd'])) {
			$this->data['config_goip4_passwd'] = $this->request->post['config_goip4_passwd']; 
		} else {
			$this->data['config_goip4_passwd'] = $this->config->get('config_goip4_passwd');
		}

		if (isset($this->request->post['config_goip4_uri'])) {
			$this->data['config_goip4_uri'] = $this->request->post['config_goip4_uri']; 
		} else {
			$this->data['config_goip4_uri'] = $this->config->get('config_goip4_uri');
		}

		if (isset($this->request->post['config_goip4_simnumber'])) {
			$this->data['config_goip4_simnumber'] = $this->request->post['config_goip4_simnumber']; 
		} else {
			$this->data['config_goip4_simnumber'] = $this->config->get('config_goip4_simnumber');
		}

		if (!$this->data['config_goip4_simnumber']){
			$this->data['config_goip4_simnumber'] = 4;
		}

		$config_goip4_simnumber = $this->data['config_goip4_simnumber'];
		for ($i = 1; $i <= $config_goip4_simnumber; $i++){

			if (isset($this->request->post['config_goip4_simnumber_' . $i])) {
				$this->data['config_goip4_simnumber_' . $i] = $this->request->post['config_goip4_simnumber_' . $i]; 
			} else {
				$this->data['config_goip4_simnumber_' . $i] = $this->config->get('config_goip4_simnumber_' . $i);
			}

			if (isset($this->request->post['config_goip4_simnumber_checkfunction_' . $i])) {
				$this->data['config_goip4_simnumber_checkfunction_' . $i] = $this->request->post['config_goip4_simnumber_checkfunction_' . $i]; 
			} else {
				$this->data['config_goip4_simnumber_checkfunction_' . $i] = $this->config->get('config_goip4_simnumber_checkfunction_' . $i);
			}

		}

			//ASTERISK AMI
		if (isset($this->request->post['config_asterisk_ami_user'])) {
			$this->data['config_asterisk_ami_user'] = $this->request->post['config_asterisk_ami_user']; 
		} else {
			$this->data['config_asterisk_ami_user'] = $this->config->get('config_asterisk_ami_user');
		}

		if (isset($this->request->post['config_asterisk_ami_pass'])) {
			$this->data['config_asterisk_ami_pass'] = $this->request->post['config_asterisk_ami_pass']; 
		} else {
			$this->data['config_asterisk_ami_pass'] = $this->config->get('config_asterisk_ami_pass');
		}

		if (isset($this->request->post['config_asterisk_ami_host'])) {
			$this->data['config_asterisk_ami_host'] = $this->request->post['config_asterisk_ami_host']; 
		} else {
			$this->data['config_asterisk_ami_host'] = $this->config->get('config_asterisk_ami_host');
		}

		if (isset($this->request->post['config_asterisk_ami_worktime'])) {
			$this->data['config_asterisk_ami_worktime'] = $this->request->post['config_asterisk_ami_worktime']; 
		} else {
			$this->data['config_asterisk_ami_worktime'] = $this->config->get('config_asterisk_ami_worktime');
		}

			//REACHER
		if (isset($this->request->post['config_reacher_enable'])) {
			$this->data['config_reacher_enable'] = $this->request->post['config_reacher_enable']; 
		} else {
			$this->data['config_reacher_enable'] = $this->config->get('config_reacher_enable');
		}

		if (isset($this->request->post['config_reacher_uri'])) {
			$this->data['config_reacher_uri'] = $this->request->post['config_reacher_uri']; 
		} else {
			$this->data['config_reacher_uri'] = $this->config->get('config_reacher_uri');
		}

		if (isset($this->request->post['config_reacher_auth'])) {
			$this->data['config_reacher_auth'] = $this->request->post['config_reacher_auth']; 
		} else {
			$this->data['config_reacher_auth'] = $this->config->get('config_reacher_auth');
		}

		if (isset($this->request->post['config_reacher_key'])) {
			$this->data['config_reacher_key'] = $this->request->post['config_reacher_key']; 
		} else {
			$this->data['config_reacher_key'] = $this->config->get('config_reacher_key');
		}

		if (isset($this->request->post['config_reacher_from'])) {
			$this->data['config_reacher_from'] = $this->request->post['config_reacher_from']; 
		} else {
			$this->data['config_reacher_from'] = $this->config->get('config_reacher_from');
		}

		if (isset($this->request->post['config_reacher_helo'])) {
			$this->data['config_reacher_helo'] = $this->request->post['config_reacher_helo']; 
		} else {
			$this->data['config_reacher_helo'] = $this->config->get('config_reacher_helo');
		}
		
		
			//ELASTICSEARCH
		if (isset($this->request->post['config_elasticsearch_fuzziness_product'])) {
			$this->data['config_elasticsearch_fuzziness_product'] = $this->request->post['config_elasticsearch_fuzziness_product']; 
		} else {
			$this->data['config_elasticsearch_fuzziness_product'] = $this->config->get('config_elasticsearch_fuzziness_product');
		}
		
		if (isset($this->request->post['config_elasticsearch_fuzziness_category'])) {
			$this->data['config_elasticsearch_fuzziness_category'] = $this->request->post['config_elasticsearch_fuzziness_category']; 
		} else {
			$this->data['config_elasticsearch_fuzziness_category'] = $this->config->get('config_elasticsearch_fuzziness_category');
		}
		
		if (isset($this->request->post['config_elasticsearch_fuzziness_autcocomplete'])) {
			$this->data['config_elasticsearch_fuzziness_autcocomplete'] = $this->request->post['config_elasticsearch_fuzziness_autcocomplete']; 
		} else {
			$this->data['config_elasticsearch_fuzziness_autcocomplete'] = $this->config->get('config_elasticsearch_fuzziness_autcocomplete');
		}

		if (isset($this->request->post['config_ozon_enable_price_yam'])) {
			$this->data['config_ozon_enable_price_yam'] = $this->request->post['config_ozon_enable_price_yam']; 
		} else {
			$this->data['config_ozon_enable_price_yam'] = $this->config->get('config_ozon_enable_price_yam');
		}

		if (isset($this->request->post['config_ozon_warehouse_0'])) {
			$this->data['config_ozon_warehouse_0'] = $this->request->post['config_ozon_warehouse_0']; 
		} else {
			$this->data['config_ozon_warehouse_0'] = $this->config->get('config_ozon_warehouse_0');
		}

		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		if (isset($this->request->post['config_ozon_exclude_manufacturers'])) {
			$this->data['config_ozon_exclude_manufacturers'] = $this->request->post['config_ozon_exclude_manufacturers'];
		} elseif ($this->config->get('config_ozon_exclude_manufacturers')) {
			$this->data['config_ozon_exclude_manufacturers'] = $this->config->get('config_ozon_exclude_manufacturers');	
		} else {
			$this->data['config_ozon_exclude_manufacturers'] = array();			
		}
		
		if (isset($this->request->post['config_yam_enable_category_tree'])) {
			$this->data['config_yam_enable_category_tree'] = $this->request->post['config_yam_enable_category_tree']; 
		} else {
			$this->data['config_yam_enable_category_tree'] = $this->config->get('config_yam_enable_category_tree');
		}
		
		if (isset($this->request->post['config_yam_enable_sync_from_1c'])) {
			$this->data['config_yam_enable_sync_from_1c'] = $this->request->post['config_yam_enable_sync_from_1c']; 
		} else {
			$this->data['config_yam_enable_sync_from_1c'] = $this->config->get('config_yam_enable_sync_from_1c');
		}
		
		if (isset($this->request->post['config_yam_default_comission'])) {
			$this->data['config_yam_default_comission'] = $this->request->post['config_yam_default_comission']; 
		} else {
			$this->data['config_yam_default_comission'] = $this->config->get('config_yam_default_comission');
		}

		if (isset($this->request->post['config_yam_fuck_specials'])) {
			$this->data['config_yam_fuck_specials'] = $this->request->post['config_yam_fuck_specials']; 
		} else {
			$this->data['config_yam_fuck_specials'] = $this->config->get('config_yam_fuck_specials');
		}
		
		if (isset($this->request->post['config_yam_enable_plus_percent'])) {
			$this->data['config_yam_enable_plus_percent'] = $this->request->post['config_yam_enable_plus_percent']; 
		} else {
			$this->data['config_yam_enable_plus_percent'] = $this->config->get('config_yam_enable_plus_percent');
		}
		
		if (isset($this->request->post['config_yam_plus_percent'])) {
			$this->data['config_yam_plus_percent'] = $this->request->post['config_yam_plus_percent']; 
		} else {
			$this->data['config_yam_plus_percent'] = $this->config->get('config_yam_plus_percent');
		}
		
		if (isset($this->request->post['config_yam_enable_plus_for_main_price'])) {
			$this->data['config_yam_enable_plus_for_main_price'] = $this->request->post['config_yam_enable_plus_for_main_price']; 
		} else {
			$this->data['config_yam_enable_plus_for_main_price'] = $this->config->get('config_yam_enable_plus_for_main_price');
		}
		
		if (isset($this->request->post['config_yam_plus_for_main_price'])) {
			$this->data['config_yam_plus_for_main_price'] = $this->request->post['config_yam_plus_for_main_price']; 
		} else {
			$this->data['config_yam_plus_for_main_price'] = $this->config->get('config_yam_plus_for_main_price');
		}
		
		
		if (isset($this->request->post['config_yam_fbs_campaign_id'])) {
			$this->data['config_yam_fbs_campaign_id'] = $this->request->post['config_yam_fbs_campaign_id']; 
		} else {
			$this->data['config_yam_fbs_campaign_id'] = $this->config->get('config_yam_fbs_campaign_id');
		}
		
		if (isset($this->request->post['config_yam_fbs_warehouse_id'])) {
			$this->data['config_yam_fbs_warehouse_id'] = $this->request->post['config_yam_fbs_warehouse_id']; 
		} else {
			$this->data['config_yam_fbs_warehouse_id'] = $this->config->get('config_yam_fbs_warehouse_id');
		}
		
		if (isset($this->request->post['config_yam_stock_field'])) {
			$this->data['config_yam_stock_field'] = $this->request->post['config_yam_stock_field']; 
		} else {
			$this->data['config_yam_stock_field'] = $this->config->get('config_yam_stock_field');
		}
		
		if (isset($this->request->post['config_yam_offer_id_prefix'])) {
			$this->data['config_yam_offer_id_prefix'] = $this->request->post['config_yam_offer_id_prefix']; 
		} else {
			$this->data['config_yam_offer_id_prefix'] = $this->config->get('config_yam_offer_id_prefix');
		}
		
		if (isset($this->request->post['config_yam_offer_id_price_enable'])) {
			$this->data['config_yam_offer_id_price_enable'] = $this->request->post['config_yam_offer_id_price_enable']; 
		} else {
			$this->data['config_yam_offer_id_price_enable'] = $this->config->get('config_yam_offer_id_price_enable');
		}
		
		if (isset($this->request->post['config_yam_offer_id_prefix_enable'])) {
			$this->data['config_yam_offer_id_prefix_enable'] = $this->request->post['config_yam_offer_id_prefix_enable']; 
		} else {
			$this->data['config_yam_offer_id_prefix_enable'] = $this->config->get('config_yam_offer_id_prefix_enable');
		}
		
		if (isset($this->request->post['config_yam_offer_id_link_disable'])) {
			$this->data['config_yam_offer_id_link_disable'] = $this->request->post['config_yam_offer_id_link_disable']; 
		} else {
			$this->data['config_yam_offer_id_link_disable'] = $this->config->get('config_yam_offer_id_link_disable');
		}
		
		if (isset($this->request->post['config_yam_offer_feed_template'])) {
			$this->data['config_yam_offer_feed_template'] = $this->request->post['config_yam_offer_feed_template']; 
		} else {
			$this->data['config_yam_offer_feed_template'] = $this->config->get('config_yam_offer_feed_template');
		}
		
		if (isset($this->request->post['config_yam_excludewords'])) {
			$this->data['config_yam_excludewords'] = $this->request->post['config_yam_excludewords']; 
		} else {
			$this->data['config_yam_excludewords'] = $this->config->get('config_yam_excludewords');
		}
		
		if (isset($this->request->post['config_yam_yaMarketToken'])) {
			$this->data['config_yam_yaMarketToken'] = $this->request->post['config_yam_yaMarketToken']; 
		} else {
			$this->data['config_yam_yaMarketToken'] = $this->config->get('config_yam_yaMarketToken');
		}
		
		if (isset($this->request->post['config_yam_yandexOauthID'])) {
			$this->data['config_yam_yandexOauthID'] = $this->request->post['config_yam_yandexOauthID']; 
		} else {
			$this->data['config_yam_yandexOauthID'] = $this->config->get('config_yam_yandexOauthID');
		}
		
		if (isset($this->request->post['config_yam_yandexOauthSecret'])) {
			$this->data['config_yam_yandexOauthSecret'] = $this->request->post['config_yam_yandexOauthSecret']; 
		} else {
			$this->data['config_yam_yandexOauthSecret'] = $this->config->get('config_yam_yandexOauthSecret');
		}
		
		if (isset($this->request->post['config_yam_yandexAccessToken'])) {
			$this->data['config_yam_yandexAccessToken'] = $this->request->post['config_yam_yandexAccessToken']; 
		} else {
			$this->data['config_yam_yandexAccessToken'] = $this->config->get('config_yam_yandexAccessToken');
		}
		
		
		if (isset($this->request->post['config_google_recaptcha_contact_enable'])) {
			$this->data['config_google_recaptcha_contact_enable'] = $this->request->post['config_google_recaptcha_contact_enable']; 
		} else {
			$this->data['config_google_recaptcha_contact_enable'] = $this->config->get('config_google_recaptcha_contact_enable');
		}
		
		if (isset($this->request->post['config_google_recaptcha_contact_key'])) {
			$this->data['config_google_recaptcha_contact_key'] = $this->request->post['config_google_recaptcha_contact_key']; 
		} else {
			$this->data['config_google_recaptcha_contact_key'] = $this->config->get('config_google_recaptcha_contact_key');
		}
		
		if (isset($this->request->post['config_google_recaptcha_contact_secret'])) {
			$this->data['config_google_recaptcha_contact_secret'] = $this->request->post['config_google_recaptcha_contact_secret']; 
		} else {
			$this->data['config_google_recaptcha_contact_secret'] = $this->config->get('config_google_recaptcha_contact_secret');
		}
		
		if (isset($this->request->post['config_webvisor_enable'])) {
			$this->data['config_webvisor_enable'] = $this->request->post['config_webvisor_enable']; 
		} else {
			$this->data['config_webvisor_enable'] = $this->config->get('config_webvisor_enable');
		}
		
		if (isset($this->request->post['config_webvisor_enable'])) {
			$this->data['config_webvisor_enable'] = $this->request->post['config_webvisor_enable']; 
		} else {
			$this->data['config_webvisor_enable'] = $this->config->get('config_webvisor_enable');
		}
		
		if (isset($this->request->post['config_clickmap_enable'])) {
			$this->data['config_clickmap_enable'] = $this->request->post['config_clickmap_enable']; 
		} else {
			$this->data['config_clickmap_enable'] = $this->config->get('config_clickmap_enable');
		}
		
		
		
		$this->template = 'setting/setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
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
		
		if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		
		if (!$this->request->post['config_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}	
		
		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
		}	
		
		if (!$this->request->post['config_voucher_min']) {
			$this->error['voucher_min'] = $this->language->get('error_voucher_min');
		}	
		
		if (!$this->request->post['config_voucher_max']) {
			$this->error['voucher_max'] = $this->language->get('error_voucher_max');
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
		
		if ($this->request->post['config_ftp_status']) {
			if (!$this->request->post['config_ftp_host']) {
				$this->error['ftp_host'] = $this->language->get('error_ftp_host');
			}
			
			if (!$this->request->post['config_ftp_port']) {
				$this->error['ftp_port'] = $this->language->get('error_ftp_port');
			}
			
			if (!$this->request->post['config_ftp_username']) {
				$this->error['ftp_username'] = $this->language->get('error_ftp_username');
			}	
			
			if (!$this->request->post['config_ftp_password']) {
				$this->error['ftp_password'] = $this->language->get('error_ftp_password');
			}											
		}
		
		if (!$this->request->post['config_error_filename']) {
			$this->error['error_filename'] = $this->language->get('error_error_filename');
		}
		
		if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['config_admin_limit']) {
			$this->error['admin_limit'] = $this->language->get('error_limit');
		}
		
		if ((utf8_strlen($this->request->post['config_encryption']) < 3) || (utf8_strlen($this->request->post['config_encryption']) > 32)) {
			$this->error['encryption'] = $this->language->get('error_encryption');
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
<?php
class ControllerModuleExchange1c extends Controller {
	private $error = array(); 

	public function index() {

		$this->load->language('module/exchange1c');
		$this->load->model('tool/image');

		//$this->document->title = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['exchange1c_order_date'] = $this->config->get('exchange1c_order_date');
			$this->model_setting_setting->editSetting('exchange1c', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['version'] = 'Version 1.5.1';

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_allow_ip'] = $this->language->get('entry_allow_ip');
		$this->data['text_price_default'] = $this->language->get('text_price_default');
		$this->data['entry_config_price_type'] = $this->language->get('entry_config_price_type');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_flush_product'] = $this->language->get('entry_flush_product');
		$this->data['entry_flush_category'] = $this->language->get('entry_flush_category');
		$this->data['entry_flush_manufacturer'] = $this->language->get('entry_flush_manufacturer');
		$this->data['entry_flush_quantity'] = $this->language->get('entry_flush_quantity');
		$this->data['entry_flush_attribute'] = $this->language->get('entry_flush_attribute');
		$this->data['entry_fill_parent_cats'] = $this->language->get('entry_fill_parent_cats');
		$this->data['entry_seo_url'] = $this->language->get('entry_seo_url');
		$this->data['entry_full_log'] = $this->language->get('entry_full_log');
		$this->data['entry_apply_watermark'] = $this->language->get('entry_apply_watermark');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_image'] = $this->language->get('entry_image');

		$this->data['entry_relatedoptions'] = $this->language->get('entry_relatedoptions');
		$this->data['entry_relatedoptions_help'] = $this->language->get('entry_relatedoptions_help');
		$this->data['entry_order_status_to_exchange'] = $this->language->get('entry_order_status_to_exchange');
		$this->data['entry_order_status_to_exchange_not'] = $this->language->get('entry_order_status_to_exchange_not');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_tab_general'] = $this->language->get('text_tab_general');
		$this->data['text_tab_product'] = $this->language->get('text_tab_product');
		$this->data['text_tab_order'] = $this->language->get('text_tab_order');
		$this->data['text_tab_manual'] = $this->language->get('text_tab_manual');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_max_filesize'] = sprintf($this->language->get('text_max_filesize'), @ini_get('max_file_uploads'));
		$this->data['text_homepage'] = $this->language->get('text_homepage');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_order_currency'] = $this->language->get('entry_order_currency');
		$this->data['entry_order_notify'] = $this->language->get('entry_order_notify');
		$this->data['entry_upload'] = $this->language->get('entry_upload');
		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = '';
		}

		if (isset($this->error['exchange1c_username'])) {
			$this->data['error_exchange1c_username'] = $this->error['exchange1c_username'];
		}
		else {
			$this->data['error_exchange1c_username'] = '';
		}

		if (isset($this->error['exchange1c_password'])) {
			$this->data['error_exchange1c_password'] = $this->error['exchange1c_password'];
		}
		else {
			$this->data['error_exchange1c_password'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('text_home'),
			'href'		=> $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator'	=> false
		);

		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('text_module'),
			'href'		=> $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator'	=> ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/exchange1c', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['token'] = $this->session->data['token'];

		//$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/exchange1c&token=' . $this->session->data['token'];
		$this->data['action'] = $this->url->link('module/exchange1c', 'token=' . $this->session->data['token'], 'SSL');

		//$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/exchange1c&token=' . $this->session->data['token'];
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['exchange1c_username'])) {
			$this->data['exchange1c_username'] = $this->request->post['exchange1c_username'];
		}
		else {
			$this->data['exchange1c_username'] = $this->config->get('exchange1c_username');
		}

		if (isset($this->request->post['exchange1c_password'])) {
			$this->data['exchange1c_password'] = $this->request->post['exchange1c_password'];
		}
		else {
			$this->data['exchange1c_password'] = $this->config->get('exchange1c_password'); 
		}

		if (isset($this->request->post['exchange1c_allow_ip'])) {
			$this->data['exchange1c_allow_ip'] = $this->request->post['exchange1c_allow_ip'];
		}
		else {
			$this->data['exchange1c_allow_ip'] = $this->config->get('exchange1c_allow_ip'); 
		} 
		
		if (isset($this->request->post['exchange1c_status'])) {
			$this->data['exchange1c_status'] = $this->request->post['exchange1c_status'];
		}
		else {
			$this->data['exchange1c_status'] = $this->config->get('exchange1c_status');
		}

		if (isset($this->request->post['exchange1c_price_type'])) {
			$this->data['exchange1c_price_type'] = $this->request->post['exchange1c_price_type'];
		}
		else {
			$this->data['exchange1c_price_type'] = $this->config->get('exchange1c_price_type');
			if(empty($this->data['exchange1c_price_type'])) {
				$this->data['exchange1c_price_type'][] = array(
					'keyword'			=> '',
					'customer_group_id'		=> 0,
					'quantity'			=> 0,
					'priority'			=> 0
				);
			}
		}

		if (isset($this->request->post['exchange1c_flush_product'])) {
			$this->data['exchange1c_flush_product'] = $this->request->post['exchange1c_flush_product'];
		}
		else {
			$this->data['exchange1c_flush_product'] = $this->config->get('exchange1c_flush_product');
		}

		if (isset($this->request->post['exchange1c_flush_category'])) {
			$this->data['exchange1c_flush_category'] = $this->request->post['exchange1c_flush_category'];
		}
		else {
			$this->data['exchange1c_flush_category'] = $this->config->get('exchange1c_flush_category');
		}

		if (isset($this->request->post['exchange1c_flush_manufacturer'])) {
			$this->data['exchange1c_flush_manufacturer'] = $this->request->post['exchange1c_flush_manufacturer'];
		}
		else {
			$this->data['exchange1c_flush_manufacturer'] = $this->config->get('exchange1c_flush_manufacturer');
		}
        
		if (isset($this->request->post['exchange1c_flush_quantity'])) {
			$this->data['exchange1c_flush_quantity'] = $this->request->post['exchange1c_flush_quantity'];
		}
		else {
			$this->data['exchange1c_flush_quantity'] = $this->config->get('exchange1c_flush_quantity');
		}

		if (isset($this->request->post['exchange1c_flush_attribute'])) {
			$this->data['exchange1c_flush_attribute'] = $this->request->post['exchange1c_flush_attribute'];
		}
		else {
			$this->data['exchange1c_flush_attribute'] = $this->config->get('exchange1c_flush_attribute');
		}

		if (isset($this->request->post['exchange1c_fill_parent_cats'])) {
			$this->data['exchange1c_fill_parent_cats'] = $this->request->post['exchange1c_fill_parent_cats'];
		}
		else {
			$this->data['exchange1c_fill_parent_cats'] = $this->config->get('exchange1c_fill_parent_cats');
		}
		
		if (isset($this->request->post['exchange1c_relatedoptions'])) {
			$this->data['exchange1c_relatedoptions'] = $this->request->post['exchange1c_relatedoptions'];
		} else {
			$this->data['exchange1c_relatedoptions'] = $this->config->get('exchange1c_relatedoptions');
		}
		if (isset($this->request->post['exchange1c_order_status_to_exchange'])) {
			$this->data['exchange1c_order_status_to_exchange'] = $this->request->post['exchange1c_order_status_to_exchange'];
		} else {
			$this->data['exchange1c_order_status_to_exchange'] = $this->config->get('exchange1c_order_status_to_exchange');
		}

		if (isset($this->request->post['exchange1c_seo_url'])) {
			$this->data['exchange1c_seo_url'] = $this->request->post['exchange1c_seo_url'];
		}
		else {
			$this->data['exchange1c_seo_url'] = $this->config->get('exchange1c_seo_url');
		}

		if (isset($this->request->post['exchange1c_full_log'])) {
			$this->data['exchange1c_full_log'] = $this->request->post['exchange1c_full_log'];
		}
		else {
			$this->data['exchange1c_full_log'] = $this->config->get('exchange1c_full_log');
		}

		if (isset($this->request->post['exchange1c_apply_watermark'])) {
			$this->data['exchange1c_apply_watermark'] = $this->request->post['exchange1c_apply_watermark'];
		}
		else {
			$this->data['exchange1c_apply_watermark'] = $this->config->get('exchange1c_apply_watermark');
		}

		if (isset($this->request->post['exchange1c_watermark'])) {
			$this->data['exchange1c_watermark'] = $this->request->post['exchange1c_watermark'];
		}
		else {
			$this->data['exchange1c_watermark'] = $this->config->get('exchange1c_watermark');
		}

		if (isset($this->data['exchange1c_watermark'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->data['exchange1c_watermark'], 100, 100);
		}
		else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($this->request->post['exchange1c_order_status'])) {
			$this->data['exchange1c_order_status'] = $this->request->post['exchange1c_order_status'];
		}
		else {
			$this->data['exchange1c_order_status'] = $this->config->get('exchange1c_order_status');
		}

		if (isset($this->request->post['exchange1c_order_currency'])) {
			$this->data['exchange1c_order_currency'] = $this->request->post['exchange1c_order_currency'];
		}
		else {
			$this->data['exchange1c_order_currency'] = $this->config->get('exchange1c_order_currency');
		}

		if (isset($this->request->post['exchange1c_order_notify'])) {
			$this->data['exchange1c_order_notify'] = $this->request->post['exchange1c_order_notify'];
		}
		else {
			$this->data['exchange1c_order_notify'] = $this->config->get('exchange1c_order_notify');
		}

		// Группы
		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('localisation/order_status');

		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();

		foreach ($order_statuses as $order_status) {
			$this->data['order_statuses'][] = array(
				'order_status_id' => $order_status['order_status_id'],
				'name'			  => $order_status['name']
			);
		}

		$this->template = 'module/exchange1c.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);

		$this->response->setOutput($this->render(), $this->config->get('config_compression'));
	}

	private function validate() {

		if (!$this->user->hasPermission('modify', 'module/exchange1c')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		}
		else {
			return false;
		}
	}

	public function install() {}

	public function uninstall() {}

	// ---
	public function modeCheckauth() {

	}

	public function manualImport() {
		
	}
	
	public function modeCatalogInit($echo = true) {
		
	
		if ($echo) {
			echo "zip=no\n";
			echo "file_limit=".$limit."\n";
		}
	
	}

	public function modeSaleInit() {
		$limit = 100000 * 1024;
	
		echo "zip=no\n";
		echo "file_limit=".$limit."\n";
	}
	
	public function modeFile() {



	}

	public function modeImport($manual = false) {

		
	}

	public function modeQueryOrders() {

	}


	// -- Системные процедуры
	private function cleanCacheDir() {

	}

	private function checkUploadFileTree($path, $curDir = null) {

	}


	private function cleanDir($root, $self = false) {

	}

}
?>

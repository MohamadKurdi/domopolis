<?php
class ControllerModuleReceipt extends Controller {
	private $error = array();	
	private $limits = array(15, 30, 50, 75, 100);
	private $verion = '10.01.2022';

    public function install() {
		$this->addDataTable();
	}

	public function index() {
		$this->language->load('module/receipt');
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('receipt', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->cache->delete('access_token');
			$this->cache->delete('current_shift');
			$this->cache->delete('checkbox_taxes');
			$this->cache->delete('checkbox_current_organization');
			$this->redirect($this->url->link('sale/receipt', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title').' (beta) <span class="help-inline" style="font-size: 11px; background: #000;  padding: 4px 5px; color: #fff;    top: -20px;    border-radius: 5px;    position: relative;"> Версія від '.$this->verion.' </span>';

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['limits'] = $this->limits;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/receipt', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('module/receipt', 'token=' . $this->session->data['token'], 'SSL');
		$data['receipt_list'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['cron_path'] = DIR_SYSTEM . 'library/checkbox_api/cron.php';
				
        $data_fields = [
            'receipt_login',
            'receipt_password',
            'receipt_x_license_key', 
            'receipt_is_dev_mode', 
            'receipt_config_language', 
            'receipt_codes_for_discounts', 
			'receipt_codes_for_extrapayment',
            'receipt_payment_type', 
            'receipt_cash_payments', 
			'receipt_cash_shippings',
            'receipt_price_format', 
            'receipt_order_status_id',
			'receipt_cash_payment_condition',
            'receipt_type_of_extrapayment',
            'receipt_text_for_extrapayment',
            'receipt_cron_order_payment_code',
            ###
            'receipt_payment_label_text',
            'receipt_payment_system_text',
            'receipt_footer_text',
			###
            'receipt_is_customer_send_email',
            'receipt_cron_auto_shifts_open',
            'receipt_cron_auto_shifts_open_hour',
            'receipt_limit',

        ];

        #integration_link = 
        $data['integration_link'] = 'https://nazarkachurak.com/checkbox-integration?utm_from='.HTTP_CATALOG;
		$data['video_link'] = 'https://nazarkachurak.com/checkbox-opencart-manual/?utm_from='.HTTP_CATALOG;
        $data['token'] = $this->session->data['token'];
        
		
		$this->load->model('sale/receipt');
       	$data['total_extensions'] = $this->model_sale_receipt->getAllSystemTotals(); 
		$data['all_payments'] = $this->model_sale_receipt->getAllSystemPayments();
		$data['all_shippings'] = $this->model_sale_receipt->getAllSystemShippings();


        foreach($data_fields as $field){
            if (isset($this->request->post[$field])) {
                $data[$field] = $this->request->post[$field];
            } else {
                $data[$field] = $this->config->get($field);
            }
        }
		
		# default config
        if(!isset($data['receipt_price_format'])){
        	$data['receipt_price_format'] = 1;
        }

        $this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['payment_types'] = array(
			array('type'=>'CASHLESS', 'name'=> 'Безготівкова форма оплати (CASHLESS)')
		);


        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $data['column_left'] = ''; 

        $this->data = $data; 

		$this->template = 'module/receipt.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function addDataTable() {
        
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "order_receipt");        
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "order_receipt (
				  `order_receipt_id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_id` int(11) NOT NULL,
				  `receipt_id` varchar(60) NOT NULL,
				  `serial` int(11) NOT NULL,
				  `status` varchar(32) NOT NULL,
				  `fiscal_code` varchar(32) NOT NULL,
				  `fiscal_date` varchar(32) NOT NULL,
				  `is_created_offline` tinyint(1) NOT NULL,
				  `is_sent_dps` tinyint(1) NOT NULL,
				  `sent_dps_at` tinyint(1) NOT NULL,
				  `all_json_data` mediumtext NOT NULL,
				  `type` varchar(64) NOT NULL,
				  PRIMARY KEY (`order_receipt_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "shift");
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "shift (
				    `id` int(11) NOT NULL AUTO_INCREMENT,
					  `shift_id` varchar(64) NOT NULL,
					  `serial` int(11) NOT NULL,
					  `status` varchar(32) NOT NULL,
					  `z_report_id` varchar(64) NOT NULL,
					  `all_json_data` mediumtext NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8; ");

	  	$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'sale/receipt');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'sale/receipt');

		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'tool/receipt_log');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'tool/receipt_log');
        
    }
    
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/receipt')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function check_connect(){
		$check_data = $this->request->post; 

		if(isset($check_data['receipt_login']) && isset($check_data['receipt_password']) && isset($check_data['receipt_x_license_key']) && isset($check_data['receipt_is_dev_mode'])){
			$this->load->library('hobotix/CheckBoxUA');
			$this->checkbox_api = new hobotix\CheckBoxUA($this->registry);
			
			$this->cache->delete('checkbox_current_organization');
			$json = $this->checkbox_api->checkAuth($check_data['receipt_login'], $check_data['receipt_password'], $check_data['receipt_x_license_key'], $check_data['receipt_is_dev_mode']);
			
			$this->response->addHeader('Content-Type: application/json');
        	$this->response->setOutput(json_encode($json));
		}

		 
	}
}

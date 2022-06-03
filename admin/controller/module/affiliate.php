<?php
	class ControllerModuleAffiliate extends Controller {
		private $error = array(); 
		
		public function index() {   
			$this->language->load('module/affiliate');
			
			$this->load->model('localisation/order_status');
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			$this->load->model('module/affiliate');
			if ($this->request->server['REQUEST_METHOD'] != 'POST') {
				$this->model_module_affiliate->createAffiliate();
			}
			$this->data['entry_add'] = $this->language->get('entry_add');
			$this->data['entry_category_visible'] = $this->language->get('entry_category_visible');
			$this->data['entry_total'] = $this->language->get('entry_total');
			$this->data['entry_days'] = $this->language->get('entry_days');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['entry_payment'] = $this->language->get('entry_payment');
			
			$this->data['text_bonus'] = $this->language->get('text_bonus');
			$this->data['text_cheque'] = $this->language->get('text_cheque');
			$this->data['text_paypal'] = $this->language->get('text_paypal');
			$this->data['text_bank'] = $this->language->get('text_bank');
			$this->data['text_qiwi'] = $this->language->get('text_qiwi');
			$this->data['text_card'] = $this->language->get('text_card');
			$this->data['text_yandex'] = $this->language->get('text_yandex');
			$this->data['text_webmoney'] = $this->language->get('text_webmoney');
			$this->data['text_webmoneyWMZ'] = $this->language->get('text_webmoneyWMZ');
			$this->data['text_webmoneyWMU'] = $this->language->get('text_webmoneyWMU');
			$this->data['text_webmoneyWME'] = $this->language->get('text_webmoneyWME');
			$this->data['text_webmoneyWMY'] = $this->language->get('text_webmoneyWMY');
			$this->data['text_webmoneyWMB'] = $this->language->get('text_webmoneyWMB');
			$this->data['text_webmoneyWMG'] = $this->language->get('text_webmoneyWMG');
			$this->data['text_AlertPay'] = $this->language->get('text_AlertPay');
			$this->data['text_Moneybookers'] = $this->language->get('text_Moneybookers');
			$this->data['text_LIQPAY'] = $this->language->get('text_LIQPAY');
			$this->data['text_SagePay'] = $this->language->get('text_SagePay');
			$this->data['text_twoCheckout'] = $this->language->get('text_twoCheckout');
			$this->data['text_GoogleWallet'] = $this->language->get('text_GoogleWallet');
			$this->data['qiwi'] = $this->affiliate_payment('qiwi');
			$this->data['card'] = $this->affiliate_payment('card');
			$this->data['yandex'] = $this->affiliate_payment('yandex');
			$this->data['webmoney'] = $this->affiliate_payment('webmoney');    
			$this->data['webmoneyWMZ'] = $this->affiliate_payment('webmoneyWMZ');
			$this->data['webmoneyWMU'] = $this->affiliate_payment('webmoneyWMU');
			$this->data['webmoneyWME'] = $this->affiliate_payment('webmoneyWME');
			$this->data['webmoneyWMY'] = $this->affiliate_payment('webmoneyWMY');
			$this->data['webmoneyWMB'] = $this->affiliate_payment('webmoneyWMB');
			$this->data['webmoneyWMG'] = $this->affiliate_payment('webmoneyWMG');
			$this->data['AlertPay'] = $this->affiliate_payment('AlertPay');
			$this->data['Moneybookers'] = $this->affiliate_payment('Moneybookers');
			$this->data['LIQPAY'] = $this->affiliate_payment('LIQPAY');
			$this->data['SagePay'] = $this->affiliate_payment('SagePay');
			$this->data['twoCheckout'] = $this->affiliate_payment('twoCheckout');
			$this->data['GoogleWallet'] = $this->affiliate_payment('GoogleWallet');
			
			$this->data['config_bonus_visible'] = $this->affiliate_payment('config_bonus_visible');
			$this->data['category_visible'] = (bool)$this->config->get('category_visible');
			
			if((int)$this->affiliate_setting('affiliate_order_status_id')!=0){
				$this->data['affiliate_order_status_id'] = (int)$this->affiliate_setting('affiliate_order_status_id');
				$this->data['cheque'] = $this->affiliate_payment('cheque');
				$this->data['paypal'] = $this->affiliate_payment('paypal');
				$this->data['bank'] = $this->affiliate_payment('bank');
				$this->data['affiliate_days'] = (int)$this->affiliate_setting('affiliate_days');
				$this->data['affiliate_total'] = (float)$this->affiliate_setting('affiliate_total');
			}
			else{
				$this->data['affiliate_order_status_id'] = (int)$this->affiliate_setting('config_complete_status_id');
				$this->data['cheque'] = true;
				$this->data['paypal'] = true;
				$this->data['bank'] = true;
				$this->data['affiliate_days'] = 7;
				$this->data['affiliate_total'] = 100;
			}
			$this->data['entry_affiliate_sumbol'] = $this->language->get('entry_affiliate_sumbol');
			$this->data['affiliate_sumbol'] = $this->affiliate_setting('config_affiliate_sumbol');
			if (!$this->affiliate_setting('config_affiliate_sumbol')) {
				$this->data['affiliate_sumbol'] = '1';
			}
			$this->data['affiliate_add'] = $this->affiliate_payment('affiliate_add');
			$this->data['config_affiliate_number_tracking'] = $this->affiliate_payment('config_affiliate_number_tracking');
			$this->data['entry_number_tracking'] = $this->language->get('entry_number_tracking');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/setting');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_setting_setting->editSetting('affiliate', $this->request->post);		
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_content_top'] = $this->language->get('text_content_top');
			$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
			$this->data['text_column_left'] = $this->language->get('text_column_left');
			$this->data['text_column_right'] = $this->language->get('text_column_right');
			
			$this->data['entry_layout'] = $this->language->get('entry_layout');
			$this->data['entry_position'] = $this->language->get('entry_position');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_module'] = $this->language->get('button_add_module');
			$this->data['button_remove'] = $this->language->get('button_remove');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/affiliate', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('module/affiliate', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['modules'] = array();
			
			if (isset($this->request->post['affiliate_module'])) {
				$this->data['modules'] = $this->request->post['affiliate_module'];
				} elseif ($this->config->get('affiliate_module')) { 
				$this->data['modules'] = $this->config->get('affiliate_module');
			}	
			
			$this->load->model('design/layout');
			
			$this->data['layouts'] = $this->model_design_layout->getLayouts();
			
			$this->template = 'module/affiliate.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validate() {
			if (!$this->user->hasPermission('modify', 'module/affiliate')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}	
		}
		
		protected function affiliate_payment($payment) {
			if (isset($this->request->post[$payment])) {
				return true;
				} else {
				return (bool)$this->config->get($payment);
			}
		}
		
		protected function affiliate_setting($setting) {
			if (isset($this->request->post[$setting])) {
				return $this->request->post[$setting];
				} else {
				return $this->config->get($setting);
			}
		}
	}
?>
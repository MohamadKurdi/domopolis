<?php 
class ControllerTotalPrepaymentDetailed extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/prepayment_detailed');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('prepayment_detailed', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_hint'] = $this->language->get('text_hint');
		$this->data['text_module_help'] = $this->language->get('text_module_help');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_condition'] = $this->language->get('entry_condition');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_turn_on_prepayment_when'] = $this->language->get('entry_turn_on_prepayment_when');
		$this->data['entry_turn_on_prepayment_for_shipping'] = $this->language->get('entry_turn_on_prepayment_for_shipping');
		$this->data['entry_turn_on_prepayment_for_payment_method'] = $this->language->get('entry_turn_on_prepayment_for_payment_method');
		$this->data['entry_for_total_items_price'] = $this->language->get('entry_for_total_items_price');
		$this->data['entry_count_as'] = $this->language->get('entry_count_as');
		$this->data['entry_prepayment_comment'] = $this->language->get('entry_prepayment_comment');
		
		$this->data['entry_from'] = $this->language->get('entry_from');
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_prepayment_amount_fixed_selection'] = $this->language->get('entry_prepayment_amount_fixed_selection');
		$this->data['entry_prepayment_by_shipping'] = $this->language->get('entry_prepayment_by_shipping');
		$this->data['entry_prepayment_by_total_price'] = $this->language->get('entry_prepayment_by_total_price');
		
		$this->data['entry_prepayment_percent_part'] = $this->language->get('entry_prepayment_percent_part');
		$this->data['entry_prepayment_percent_part_shipping'] = $this->language->get('entry_prepayment_percent_part_shipping');
		$this->data['entry_prepayment_percent_part_total_price'] = $this->language->get('entry_prepayment_percent_part_total_price');
		$this->data['entry_confirm_remove_filter'] = $this->language->get('entry_confirm_remove_filter');
		
		$this->data['entry_shipping_validation_tip'] = $this->language->get('entry_shipping_validation_tip');
		$this->data['entry_payment_method_validation_tip'] = $this->language->get('entry_payment_method_validation_tip');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_filter'] = $this->language->get('button_add_filter');
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
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/prepayment_detailed', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/prepayment_detailed', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		
		$this->data['filters'] = array();
		
		if (isset($this->request->post['prepayment_detailed_filter'])) {
			$this->data['filters'] = $this->request->post['prepayment_detailed_filter'];
		} elseif ($this->config->get('prepayment_detailed_filter')) { 
			$this->data['filters'] = $this->config->get('prepayment_detailed_filter');
		}	
		
		$this->data['totals'] = $this->getTotalExtensions();

		$this->processModuleSetting("prepayment_detailed_sort_order");
		$this->processModuleSetting("prepayment_detailed_status");

		
// installed shipping methods
		$this->load->model('setting/extension');
		$shippings = $this->model_setting_extension->getInstalled('shipping');
		$shippings_files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		
		if ($shippings_files) {
			foreach ($shippings_files as $file) {
				$shipping = basename($file, '.php');
				$this->load->language('shipping/' . $shipping);
				if (in_array($shipping, $shippings)) {
					$this->data['shippings'][] = array(
						'hname' => $this->language->get('heading_title'),
						'fname' => $shipping
					);
					$this->data['shipping_codes'][] = $shipping;
				}
			}
		}
//

// installed payment methods
		$this->load->model('setting/extension');
		$payment_methods = $this->model_setting_extension->getInstalled('payment');
		$payment_methods_files = glob(DIR_APPLICATION . 'controller/payment/*.php');
		
		if ($payment_methods_files) {
			foreach ($payment_methods_files as $file) {
				$payment_method = basename($file, '.php');
				$this->load->language('payment/' . $payment_method);
				if (in_array($payment_method, $payment_methods)) {
					$this->data['payment_methods'][] = array(
						'hname' => $this->language->get('heading_title'),
						'fname' => $payment_method
					);
					$this->data['payment_method_codes'][] = $payment_method;
				}
			}
		}
//
	
		
		$this->template = 'total/prepayment_detailed.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function processModuleSetting($settingName) {
		if (isset($this->request->post[$settingName])) {
			$this->data[$settingName] = $this->request->post[$settingName];
		} else {
			$this->data[$settingName] = $this->config->get($settingName);
		}
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/prepayment_detailed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	private function getTotalExtensions() {
		$this->load->model('setting/extension');
		
		$extensions = $this->model_setting_extension->getInstalled('total');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/total/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('total', $value);
				
				unset($extensions[$key]);
			}
		}
		
		$totals = array();
				
		$files = glob(DIR_APPLICATION . 'controller/total/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->language->load('total/' . $extension);
						
				if ($this->config->get($extension . '_status') && $extension != "prepayment_detailed") {
					$totals[] = array(
						'name' => $this->language->get('heading_title'),
						'code' => $extension
					);
				}
			}
		}
			
		return $totals;
	}
}
?>
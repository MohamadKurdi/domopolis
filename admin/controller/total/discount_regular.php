<?php 
	class ControllerTotalDiscountRegular extends Controller { 
		private $error = array(); 
		
		public function index() { 
			$this->load->language('total/discount_regular');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/discount');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
				$this->model_setting_discount->editSetting('discount_regular', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_all_status'] = $this->language->get('text_all_status');
			$this->data['text_all'] = $this->language->get('text_all');
			
			$this->data['entry_total'] = $this->language->get('entry_total');
			$this->data['entry_sum_status'] = $this->language->get('entry_sum_status');
			$this->data['entry_group'] = $this->language->get('entry_group');
			$this->data['entry_proc'] = $this->language->get('entry_proc');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_products'] = $this->language->get('entry_products');
			$this->data['entry_product1'] = $this->language->get('entry_product1');
			$this->data['entry_product2'] = $this->language->get('entry_product2');
			$this->data['entry_product3'] = $this->language->get('entry_product3');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_edit'] = $this->language->get('button_edit');
			
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
			'href'      => $this->url->link('total/discount_regular', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('total/discount_regular', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['order_statuses'] = $this->model_setting_discount->getOrderStatuses();
			
			if (isset($this->request->post['discount_regular_order_status'])) {
				$this->data['discount_regular_order_status'] = $this->request->post['discount_regular_order_status'];
				} else {
				$this->data['discount_regular_order_status'] = $this->config->get('discount_regular_order_status');
			}
			
			$results = $this->model_setting_discount->getCustomerGroups();
			foreach ($results as $result) {
				$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name']
				);
			}
			if (isset($this->request->post['discount_regular_group'])) {
				$this->data['discount_regular_group'] = $this->request->post['discount_regular_group'];
				} else {
				$this->data['discount_regular_group'] = $this->config->get('discount_regular_group');
			}
			
			
			if (isset($this->request->post['discount_regular_total'])) {
				$this->data['discount_regular_total'] = $this->request->post['discount_regular_total'];
				} else {
				$this->data['discount_regular_total'] = $this->config->get('discount_regular_total');
			}
			
			if (isset($this->request->post['discount_regular_proc'])) {
				$this->data['discount_regular_proc'] = $this->request->post['discount_regular_proc'];
				} else {
				$this->data['discount_regular_proc'] = $this->config->get('discount_regular_proc');
			}
			
			if (isset($this->request->post['discount_regular_status'])) {
				$this->data['discount_regular_status'] = $this->request->post['discount_regular_status'];
				} else {
				$this->data['discount_regular_status'] = $this->config->get('discount_regular_status');
			}
			
			if (isset($this->request->post['discount_regular_sort_order'])) {
				$this->data['discount_regular_sort_order'] = $this->request->post['discount_regular_sort_order'];
				} else {
				$this->data['discount_regular_sort_order'] = $this->config->get('discount_regular_sort_order');
			}
			
			if (isset($this->request->post['discount_regular_product1'])) {
				$this->data['discount_regular_product1'] = $this->request->post['discount_regular_product1'];
				} else {
				$this->data['discount_regular_product1'] = $this->config->get('discount_regular_product1');
			}
			
			if (isset($this->request->post['discount_regular_product2'])) {
				$this->data['discount_regular_product2'] = $this->request->post['discount_regular_product2'];
				} else {
				$this->data['discount_regular_product2'] = $this->config->get('discount_regular_product2');
			}
			
			if (isset($this->request->post['discount_regular_product3'])) {
				$this->data['discount_regular_product3'] = $this->request->post['discount_regular_product3'];
				} else {
				$this->data['discount_regular_product3'] = $this->config->get('discount_regular_product3');
			}
			
			$this->data['href_edit_pro'] = $this->url->link('total/discount_product', 'token=' . $this->session->data['token'] . '&discount_id=discount_regular', 'SSL');
			
			$this->template = 'total/discount_regular.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function validate() {
			if (!$this->user->hasPermission('modify', 'total/discount_regular')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}	
		}
	}
?>
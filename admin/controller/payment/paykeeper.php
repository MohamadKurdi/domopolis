<?php 
class ControllerPaymentPayKeeper extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/paykeeper');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paykeeper', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_pay'] = $this->language->get('text_pay');
		$this->data['text_card'] = $this->language->get('text_card');

		$this->data['entry_server_url'] = $this->language->get('entry_server_url');
		$this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['server_url'])) { 
			$this->data['error_server_url'] = $this->error['server_url'];
		} else {
			$this->data['error_server_url'] = '';
		}

		if (isset($this->error['secret_key'])) { 
			$this->data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$this->data['error_secret_key'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/paykeeper', 'token=' . $this->session->data['token'], 'SSL'),      		
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/paykeeper', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['paykeeper_server_url'])) {
			$this->data['paykeeper_server_url'] = $this->request->post['paykeeper_server_url'];
		} else {
			$this->data['paykeeper_server_url'] = $this->config->get('paykeeper_server_url');
		}

		if (isset($this->request->post['paykeeper_secret_key'])) {
			$this->data['paykeeper_secret_key'] = $this->request->post['paykeeper_secret_key'];
		} else {
			$this->data['paykeeper_secret_key'] = $this->config->get('paykeeper_secret_key');
		}

		if (isset($this->request->post['paykeeper_order_status_id'])) {
			$this->data['paykeeper_order_status_id'] = $this->request->post['paykeeper_order_status_id'];
		} else {
			$this->data['paykeeper_order_status_id'] = $this->config->get('paykeeper_order_status_id'); 
		} 

		if (isset($this->request->post['paykeeper_order_fail_status_id'])) {
			$this->data['paykeeper_order_fail_status_id'] = $this->request->post['paykeeper_order_fail_status_id'];
		} else {
			$this->data['paykeeper_order_fail_status_id'] = $this->config->get('paykeeper_order_fail_status_id'); 
		} 

		if (isset($this->request->post['paykeeper_order_later_status_id'])) {
			$this->data['paykeeper_order_later_status_id'] = $this->request->post['paykeeper_order_later_status_id'];
		} else {
			$this->data['paykeeper_order_later_status_id'] = $this->config->get('paykeeper_order_later_status_id'); 
		} 

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		if (isset($this->request->post['paykeeper_status'])) {
			$this->data['paykeeper_status'] = $this->request->post['paykeeper_status'];
		} else {
			$this->data['paykeeper_status'] = $this->config->get('paykeeper_status');
		}

		if (isset($this->request->post['paykeeper_pay_on_checkout'])) {
			$this->data['paykeeper_pay_on_checkout'] = $this->request->post['paykeeper_pay_on_checkout'];
		} else {
			$this->data['paykeeper_pay_on_checkout'] = $this->config->get('paykeeper_pay_on_checkout');
		}

		if (isset($this->request->post['paykeeper_status_fake'])) {
			$this->data['paykeeper_status_fake'] = $this->request->post['paykeeper_status_fake'];
		} else {
			$this->data['paykeeper_status_fake'] = $this->config->get('paykeeper_status_fake');
		}

		if (isset($this->request->post['paykeeper_ismethod'])) {
			$this->data['paykeeper_ismethod'] = $this->request->post['paykeeper_ismethod'];
		} else {
			$this->data['paykeeper_ismethod'] = $this->config->get('paykeeper_ismethod');
		}

		if (isset($this->request->post['paykeeper_sort_order'])) {
			$this->data['paykeeper_sort_order'] = $this->request->post['paykeeper_sort_order'];
		} else {
			$this->data['paykeeper_sort_order'] = $this->config->get('paykeeper_sort_order');
		}

		$this->template = 'payment/paykeeper.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paykeeper')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paykeeper_server_url']) {
			$this->error['server_url'] = $this->language->get('error_server_url');
		}

		if (!$this->request->post['paykeeper_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
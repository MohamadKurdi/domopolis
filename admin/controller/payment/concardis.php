<?php
class ControllerPaymentConcardis extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/concardis');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('concardis', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');	
		$this->data['entry_canceled_status'] = $this->language->get('entry_canceled_status');	
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');	
		$this->data['entry_chargeback_status'] = $this->language->get('entry_chargeback_status');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_mb_id'] = $this->language->get('entry_mb_id');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_custnote'] = $this->language->get('entry_custnote');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
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
			'href'      => $this->url->link('payment/concardis', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/concardis', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['concardis_email'])) {
			$this->data['concardis_email'] = $this->request->post['concardis_email'];
		} else {
			$this->data['concardis_email'] = $this->config->get('concardis_email');
		}

		if (isset($this->request->post['concardis_secret'])) {
			$this->data['concardis_secret'] = $this->request->post['concardis_secret'];
		} else {
			$this->data['concardis_secret'] = $this->config->get('concardis_secret');
		}
		
		if (isset($this->request->post['concardis_ismethod'])) {
			$this->data['concardis_ismethod'] = $this->request->post['concardis_ismethod'];
		} else {
			$this->data['concardis_ismethod'] = $this->config->get('liqpay_ismethod');
		}

		if (isset($this->request->post['concardis_total'])) {
			$this->data['concardis_total'] = $this->request->post['concardis_total'];
		} else {
			$this->data['concardis_total'] = $this->config->get('concardis_total'); 
		} 

		if (isset($this->request->post['concardis_order_status_id'])) {
			$this->data['concardis_order_status_id'] = $this->request->post['concardis_order_status_id'];
		} else {
			$this->data['concardis_order_status_id'] = $this->config->get('concardis_order_status_id'); 
		} 

		if (isset($this->request->post['concardis_pending_status_id'])) {
			$this->data['concardis_pending_status_id'] = $this->request->post['concardis_pending_status_id'];
		} else {
			$this->data['concardis_pending_status_id'] = $this->config->get('concardis_pending_status_id');
		}

		if (isset($this->request->post['concardis_canceled_status_id'])) {
			$this->data['concardis_canceled_status_id'] = $this->request->post['concardis_canceled_status_id'];
		} else {
			$this->data['concardis_canceled_status_id'] = $this->config->get('concardis_canceled_status_id');
		}

		if (isset($this->request->post['concardis_failed_status_id'])) {
			$this->data['concardis_failed_status_id'] = $this->request->post['concardis_failed_status_id'];
		} else {
			$this->data['concardis_failed_status_id'] = $this->config->get('concardis_failed_status_id');
		}

		if (isset($this->request->post['concardis_chargeback_status_id'])) {
			$this->data['concardis_chargeback_status_id'] = $this->request->post['concardis_chargeback_status_id'];
		} else {
			$this->data['concardis_chargeback_status_id'] = $this->config->get('concardis_chargeback_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('setting/store');			
		$this->data['stores'] = $this->model_setting_store->getStores();
			
		if (isset($this->request->post['concardis_store'])) {
			$this->data['concardis_store'] = $this->request->post['concardis_sort_order'];
		} else { 
			$this->data['concardis_store'] = $this->config->get('concardis_store'); 
		}	

		if (isset($this->request->post['concardis_geo_zone_id'])) {
			$this->data['concardis_geo_zone_id'] = $this->request->post['concardis_geo_zone_id'];
		} else {
			$this->data['concardis_geo_zone_id'] = $this->config->get('concardis_geo_zone_id'); 
		} 			

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['concardis_status'])) {
			$this->data['concardis_status'] = $this->request->post['concardis_status'];
		} else {
			$this->data['concardis_status'] = $this->config->get('concardis_status');
		}

		if (isset($this->request->post['concardis_status_fake'])) {
			$this->data['concardis_status_fake'] = $this->request->post['concardis_status_fake'];
		} else {
			$this->data['concardis_status_fake'] = $this->config->get('concardis_status_fake');
		}

		if (isset($this->request->post['concardis_sort_order'])) {
			$this->data['concardis_sort_order'] = $this->request->post['concardis_sort_order'];
		} else {
			$this->data['concardis_sort_order'] = $this->config->get('concardis_sort_order');
		}

		if (isset($this->request->post['concardis_rid'])) {
			$this->data['concardis_rid'] = $this->request->post['concardis_rid'];
		} else {
			$this->data['concardis_rid'] = $this->config->get('concardis_rid');
		}

		if (isset($this->request->post['concardis_custnote'])) {
			$this->data['concardis_custnote'] = $this->request->post['concardis_custnote'];
		} else {
			$this->data['concardis_custnote'] = $this->config->get('concardis_custnote');
		}

		$this->template = 'payment/concardis.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/concardis')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['concardis_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
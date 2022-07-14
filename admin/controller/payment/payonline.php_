<?php
/**
 * e-mail: s-m-o-k@list.ru
 */
class ControllerPaymentPayonline extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('payment/payonline');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payonline', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_help'] = $this->language->get('text_help');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_security'] = $this->language->get('entry_security');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

		if (isset($this->error['security'])) {
			$this->data['error_security'] = $this->error['security'];
		} else {
			$this->data['error_security'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_payment'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('payment/payonline', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		$this->data['action'] =$this->url->link('payment/payonline', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] =$this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['payonline_merchant'])) {
			$this->data['payonline_merchant'] = $this->request->post['payonline_merchant'];
		} else {
			$this->data['payonline_merchant'] = $this->config->get('payonline_merchant');
		}

		if (isset($this->request->post['payonline_add_params'])) {
			$this->data['payonline_add_params'] = $this->request->post['payonline_add_params'];
		} else {
			$this->data['payonline_add_params'] = $this->config->get('payonline_add_params');
		}

		if (isset($this->request->post['payonline_security'])) {
			$this->data['payonline_security'] = $this->request->post['payonline_security'];
		} else {
			$this->data['payonline_security'] = $this->config->get('payonline_security');
		}

		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/payonline/callback';

		if (isset($this->request->post['payonline_order_status_id'])) {
			$this->data['payonline_order_status_id'] = $this->request->post['payonline_order_status_id'];
		} else {
			$this->data['payonline_order_status_id'] = $this->config->get('payonline_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payonline_geo_zone_id'])) {
			$this->data['payonline_geo_zone_id'] = $this->request->post['payonline_geo_zone_id'];
		} else {
			$this->data['payonline_geo_zone_id'] = $this->config->get('payonline_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payonline_status'])) {
			$this->data['payonline_status'] = $this->request->post['payonline_status'];
		} else {
			$this->data['payonline_status'] = $this->config->get('payonline_status');
		}

		if (isset($this->request->post['payonline_sort_order'])) {
			$this->data['payonline_sort_order'] = $this->request->post['payonline_sort_order'];
		} else {
			$this->data['payonline_sort_order'] = $this->config->get('payonline_sort_order');
		}

		$this->template = 'payment/payonline.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payonline')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payonline_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['payonline_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

?>
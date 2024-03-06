<?php
class ControllerTotalTotalukrcredits extends Controller {
	private $error = array();

	public function index() {			
		$data['type'] = $type;
			
		$this->load->language('total/totalukrcredits');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('totalukrcredits', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/total', $token.'token=' . $this->session->data['token'] . '&type=total', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token.'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_total'),
			'href' => $this->url->link('extension/total', $token.'token=' . $this->session->data['token'] . '&type=total', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('total/totalukrcredits', $token.'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('total/totalukrcredits', $token.'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/total', $token.'token=' . $this->session->data['token'] . '&type=total', true);

		if (isset($this->request->post['totalukrcredits_status'])) {
			$data['totalukrcredits_status'] = $this->request->post['totalukrcredits_status'];
		} else {
			$data['totalukrcredits_status'] = $this->config->get('totalukrcredits_status');
		}

		if (isset($this->request->post['totalukrcredits_sort_order'])) {
			$data['totalukrcredits_sort_order'] = $this->request->post['totalukrcredits_sort_order'];
		} else {
			$data['totalukrcredits_sort_order'] = $this->config->get('totalukrcredits_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('total/totalukrcredits.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/totalukrcredits')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
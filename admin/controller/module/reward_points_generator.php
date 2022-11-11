<?php 
class ControllerModuleRewardPointsGenerator extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('module/reward_points_generator');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/reward_points_generator');
		
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
			'href'      => $this->url->link('module/reward_points_generator', 'token=' . $this->session->data['token'], 'SSL'),
	        'separator' => ' :: '
	    );

		$languages = array(
			'heading_title',
			'entry_customer_group',
			'entry_reward',
			'entry_price_points',
			'entry_points_received',
			'entry_unit',
			'entry_auto_add',
			'entry_auto_generate',
			'entry_use_tax_class',
			'entry_generate_for_specials',
			'help_auto_generate',
			'help_auto_add',
			'help_price_points',
			'help_points_received',
			'help_points',
			'help_use_tax_class',
			'help_generate_for_specials',
			'button_generate',
			'button_save',
			'button_cancel',
			'text_auto_add',
			'text_per',
			'text_yes',
			'text_no'
		);

		foreach ($languages as $language) {
			$this->data[$language] = $this->language->get($language);
		}

		$this->data['action'] = $this->url->link('module/reward_points_generator/generate', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['save'] = $this->url->link('module/reward_points_generator/save', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		array_unshift($this->data['tax_classes'], array('tax_class_id' => '*','title' => ''));

		$this->data['units'] = array(
			1,
			10,
			100
		);

		if (isset($this->request->post['reward_points_generator'])) {
			$this->data['reward_points_generator'] = $this->request->post['reward_points_generator'];
		} elseif ($this->config->get('reward_points_generator')) {
			$this->data['reward_points_generator'] = $this->config->get('reward_points_generator');
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'module/reward_points_generator.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	
	}

	public function save() {
		$this->load->language('module/reward_points_generator');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/reward_points_generator');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('reward_points_generator', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_save');

			$this->redirect($this->url->link('module/reward_points_generator', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->index();
	}

	public function generate() {
		$this->load->language('module/reward_points_generator');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/reward_points_generator');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('reward_points_generator', $this->request->post);

			$total = $this->model_module_reward_points_generator->generateRewardPoints($this->request->post['reward_points_generator']);

			$this->session->data['success'] = sprintf($this->language->get('text_success_generate'),$total);
	
			$this->redirect($this->url->link('module/reward_points_generator', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->index();
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/reward_points_generator')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
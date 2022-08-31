<?php
class ControllerSettingRnf extends Controller {
	private $error = array();

	private $cron_settings = [
		'config_rainforest_enable_new_parser',
		'config_rainforest_enable_data_parser',
		'config_rainforest_enable_tech_category_parser',
		'config_rainforest_enable_category_tree_parser',
		'config_rainforest_enable_data_l2_parser',
		'config_rainforest_enable_offers_parser',
		'config_rainforest_enable_asins_parser',
		'config_rainforest_enable_eans_parser',
		'config_rainforest_enable_offersqueue_parser'
	];


	public function index() {
		$this->language->load('setting/setting'); 

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		
		$this->data['heading_title'] = 'Управление фреймворком Rainforest';
		$this->document->setTitle($this->data['heading_title']);


		foreach ($this->cron_settings as $cron_setting){
			$this->data[$cron_setting] = $this->config->get($cron_setting);		
		}		










		$this->data['token'] = $this->session->data['token'];

		$this->template = 'setting/rnf.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());

	}
}
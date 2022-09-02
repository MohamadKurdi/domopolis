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

	private $other_settings = [
		'config_rainforest_category_update_period',
		'config_rainforest_max_variants',
		'config_rainforest_skip_variants',
		'config_rainforest_update_period',

		'config_rainforest_enable_review_adding',
		'config_rainforest_max_review_per_product',
		'config_rainforest_min_review_rating',
		'config_rainforest_max_review_length',
	];


	public function index() {
		$this->language->load('setting/setting'); 

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		
		$this->data['heading_title'] = 'Управление фреймворком Rainforest / Amazon';
		$this->document->setTitle($this->data['heading_title']);


		foreach ($this->cron_settings as $cron_setting){
			$this->data[$cron_setting] = $this->config->get($cron_setting);		
		}		


		foreach ($this->other_settings as $other_setting){
			$this->data[$other_setting] = $this->config->get($other_setting);		
		}







		$this->data['token'] = $this->session->data['token'];

		$this->template = 'setting/rnf.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());

	}

	public function getRainForestStats(){

		$result = $this->rainforestAmazon->checkIfPossibleToMakeRequest(true);

		if ($result['status'] == true){
			$this->data['success'] 	= true;
			$this->data['answer'] 	= $result['answer'];
		} else {
			$this->data['success'] 	= false;
			$this->data['message'] 	= $result['message'];
			$this->data['answer']  	= $result['answer'];
		}

		$this->template = 'setting/rnfhdr.tpl';

		$this->response->setOutput($this->render());

	}
}
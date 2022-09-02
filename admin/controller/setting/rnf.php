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

		'config_enable_amazon_specific_modes',
		'config_brands_in_mmenu',
		'config_second_level_subcategory_in_categories',
		'config_display_subcategory_in_all_categories',
		'config_rainforest_show_only_filled_products_in_catalog',

		'config_no_zeroprice',
		'config_product_hide_sku',
		'config_product_replace_sku_with_product_id',

		'config_sort_default',
		'config_order_default',

		'config_delivery_outstock_enable',

		'config_rainforest_enable_recursive_adding',
		'config_rainforest_enable_compare_with_similar_parsing',
		'config_rainforest_enable_related_parsing',
		'config_rainforest_enable_sponsored_parsing',
		'config_rainforest_enable_similar_to_consider_parsing',
		'config_rainforest_enable_view_to_purchase_parsing',
		'config_rainforest_enable_also_viewed_parsing',
		'config_rainforest_enable_also_bought_parsing',
		'config_rainforest_enable_shop_by_look_parsing',

		'config_rainforest_enable_compare_with_similar_adding',
		'config_rainforest_enable_related_adding',
		'config_rainforest_enable_sponsored_adding',
		'config_rainforest_enable_similar_to_consider_adding',
		'config_rainforest_enable_view_to_purchase_adding',
		'config_rainforest_enable_also_viewed_adding',
		'config_rainforest_enable_also_bought_adding',
		'config_rainforest_enable_shop_by_look_adding',

		'config_rainforest_source_language'
	];


	public function index() {
		$this->language->load('setting/setting'); 

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->model('localisation/language');		


		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->data['heading_title'] = 'Управление фреймворком Rainforest / Amazon';
		$this->document->setTitle($this->data['heading_title']);


		foreach ($this->cron_settings as $cron_setting){
			$this->data[$cron_setting] = $this->config->get($cron_setting);		
		}		


		foreach ($this->other_settings as $other_setting){
			$this->data[$other_setting] = $this->config->get($other_setting);		
		}

		foreach ($this->data['languages'] as $rnf_language){
			if ($rnf_language['code'] != $this->data['config_rainforest_source_language']){
				$this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_enable_language_' . $rnf_language['code']);
			}
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
<?php
class ControllerModuleSpecial extends Controller {
	protected function index($setting) {

		$current_store = (int)$this->config->get('config_store_id');
		$current_lang  = (int)$this->config->get('config_language_id');
		$current_curr  = (int)$this->currency->getId();

		$product_id = 0;
		$path_id = 0;
		$category_id = false;
		if (isset($this->request->get['product_id'])){
			$product_id = $this->request->get['product_id'];
			$category_id = false;
		} else {
			if (isset($this->request->get['path'])){
				$path_id = $this->request->get['path'];
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
				$category_id = $category_id;
			}
		}
		if (isset($this->request->get['manufacturer_id'])){
			$manufacturer_id = $this->request->get['manufacturer_id'];			
		} else {
			$manufacturer_id = 0;
		}

		$this->language->load('module/special');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_cart'] = $this->language->get('button_cart');

		$this->load->model('catalog/product');					
		$this->load->model('tool/image');					

		$this->data['products'] = array();

		$data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit'],
			'filter_category_id' => $category_id,					
			'filter_sub_category' => true
		);

		if ($manufacturer_id){
			$data['filter_manufacturer_id'] = $manufacturer_id;
		}		

		$this->data['position'] = $setting['position'];
		$this->data['dimensions'] = array(
			'w' => $setting['image_width'],
			'h' => $setting['image_height']
		);

		$results = $this->model_catalog_product->getProductSpecials($data);

		if (!$results){						
			$data = array(
				'sort'  => 'pd.name',
				'order' => 'ASC',
				'start' => 0,
				'limit' => $setting['limit'],
				'filter_category_id' => $category_id,
				'filter_sub_category' => true
			);

			if ($manufacturer_id){
				$data['filter_manufacturer_id'] = $manufacturer_id;
			}	

			$results = $this->model_catalog_product->getProductSpecials($data);
		}

		$this->data['dimensions'] = array(
			'w' => $setting['image_width'],
			'h' => $setting['image_height']
		);

		$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);

		$this->template = 'module/special';

		$this->render();	
	}
}
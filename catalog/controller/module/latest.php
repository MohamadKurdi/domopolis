<?php
class ControllerModuleLatest extends Controller {
	protected function index($setting) {
		
		$product_id = 0;
		$category_id = false;
		if (isset($this->request->get['path'])){
			$path_id = $this->request->get['path'];
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);				
		}

		if (isset($this->request->get['manufacturer_id'])){
			$manufacturer_id = $this->request->get['manufacturer_id'];			
		} else {
			$manufacturer_id = 0;
		}
		
		$limit = $setting['limit'];

		
		$this->language->load('module/latest');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		if ($manufacturer_id) {
			$data = array(			
				'sort'  => 'p.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $setting['limit'],
				'filter_manufacturer_id' => $manufacturer_id
			);
		} elseif ($category_id)	{
			$data = array(			
				'sort'  => 'p.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $setting['limit'],
				'filter_category_id' => $category_id
			);
		} else {
			$data = array(			
				'sort'  => 'p.date_added',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $setting['limit']
			);
			
		}
		
		$this->data['position'] = $setting['position'];
		$this->data['dimensions'] = array(
			'w' => $setting['image_width'],
			'h' => $setting['image_height']
		);
		
		$results = $this->model_catalog_product->getProducts($data);
		
		$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
		
		$this->template = 'module/latest';
		
		$this->render();
	}
}
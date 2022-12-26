<?php
class ControllerModuleBestSeller extends Controller {
	protected function index($setting) {
		$this->language->load('module/bestseller');
		$this->load->model('catalog/product');		
		$this->load->model('tool/image');

		foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}

		$product_id = (!empty($this->request->get['product_id']))?$this->request->get['product_id']:0;
		$manufacturer_id = (!empty($this->request->get['manufacturer_id']))?$this->request->get['manufacturer_id']:0;


		$category_id = 0;	
		if (isset($this->request->get['path'])){
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
		}

		if (!empty($product_id)){
			$product_info 	= $this->model_catalog_product->getProduct($product_id);

			if ($product_info){
				if ($product_info['is_markdown'] && $product_info['markdown_product_id']){
					$product_info 	= $this->model_catalog_product->getProduct($product_info['markdown_product_id']);
					$category_id 	= $product_info['main_category_id'];
				} elseif ($product_info['stock_product_id']){
					$product_info 	= $this->model_catalog_product->getProduct($product_info['stock_product_id']);
					$category_id 	= $product_info['main_category_id'];
				}
			}
		}

		$out = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, $setting, [$product_id, $category_id, $manufacturer_id]));

		if ($out) {		

			$this->setCachedOutput($out);

		} else {

			$this->data['heading_title'] = $this->language->get('heading_title');	

			if (isset($this->request->get['product_id'])){
				$this->data['heading_title'] = $this->language->get('heading_title_product');	
			}

			$results = $results_full = $results_full2 = [];

			if ($category_id) {
				$results = $this->model_catalog_product->getBestSellerProductsForCategory($setting['limit'], $category_id);
			} elseif ($manufacturer_id) {
				$results = $this->model_catalog_product->getBestSellerProductsForManufacturer($setting['limit'], $manufacturer_id);
			} else {	
				$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
			}

			if (count($results) < $setting['limit']){						
				$filter_data = array(
					'sort'  				=> 'p.viewed',
					'order' 				=> 'DESC',
					'start' 				=> 0,
					'limit' 				=> ($setting['limit'] - count($results)),
					'filter_category_id' 	=> $category_id,
					'filter_manufacturer_id'=> $manufacturer_id,
					'filter_not_bad'		=> true,		
					'filter_with_variants'	=> true,
					'filter_quantity'		=> true
				);
				$results_full = $this->model_catalog_product->getProducts($filter_data);
			}

			if ((count($results) + count($results_full)) < $setting['limit']){
				$filter_data = [
					'sort'						=> 'rand-10',
					'order'               		=> 'DESC',	
					'start' 					=> 0,
					'limit' 					=> ($setting['limit'] - (count($results) + count($results_full))),	
					'filter_category_id'		=> $category_id,
					'filter_manufacturer_id'	=> $manufacturer_id,						
					'filter_not_bad'			=> true,		
					'filter_with_variants'		=> true,
					'filter_quantity'			=> true
				];

				$results_full2 = $this->model_catalog_product->getProducts($filter_data);							
			}

			$merged = [];
			foreach ($results as $key => $result){
				$merged[$key] = $result;
			}

			foreach ($results_full as $key => $result){
				$merged[$key] = $result;
			}

			foreach ($results_full2 as $key => $result){
				$merged[$key] = $result;
			}

			$this->data['dimensions'] = array(
				'w' => $setting['image_width'],
				'h' => $setting['image_height']
			);

			$this->data['position'] = $setting['position'];			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($merged);

			$this->template = 'module/bestseller';

			$out = $this->render();
			$this->cache->set($this->registry->createCacheQueryString(__METHOD__, $setting, [$product_id, $category_id, $manufacturer_id]), $out);
		}

		$this->response->setOutput($out);		
	}
}
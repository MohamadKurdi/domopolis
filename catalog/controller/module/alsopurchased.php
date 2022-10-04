<?php

class ControllerModuleAlsoPurchased extends Controller
{
	public function index($setting)
	{
		$this->language->load( 'module/alsopurchased' );			

		foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$product_id = (isset($this->request->get['product_id'])) ? $this->request->get['product_id'] : 0;

		$out = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, $setting, [$product_id]));

		if ($out) {		

			$this->setCachedOutput($out);

		} else {	

			$this->load->model('module/alsopurchased');			
			$this->load->model('catalog/category');	
			$this->load->model('catalog/product');
			$this->load->model('tool/image');


			if ($setting['categories']){
				$this->data['categories'] 	= [];	

				$product = $this->model_catalog_product->getProduct($product_id);

				if ($product && $product['main_category_id']){
					$categories = $this->model_catalog_category->getRelatedCategories($product['main_category_id'], $setting['category_limit']);

					foreach ($categories as $category){
						$results = $results_full = $results_full2 = [];
						$filter_data = [
							 'start' 					=> 0,
							 'limit' 					=> $setting['limit'],	
							 'filter_category_id'		=> $category['category_id'],
							 'sort'						=> 'p.name',						
							 'order'               		=> 'DESC',	
							 'filter_not_bad'			=> true,		
							 'filter_with_variants'		=> true,
							 'filter_also_bought_with' 	=> $product_id
						];

						$results = $this->model_catalog_product->getProducts($filter_data);		

						if (count($results) < $setting['limit']){
							$filter_data = [
								'start' 					=> 0,
								'limit' 					=> ($setting['limit'] - count($results)),	
								'filter_category_id'		=> $category['category_id'],						
								'sort'						=> 'p.viewed',
								'order'               		=> 'DESC',	
								'filter_not_bad'			=> true,		
								'filter_with_variants'		=> true
							];

							$results_full = $this->model_catalog_product->getProducts($filter_data);							
						}

						if ((count($results) + count($results_full)) < $setting['limit']){
							$filter_data = [
								'start' 					=> 0,
								'limit' 					=> ($setting['limit'] - (count($results) + count($results_full))),	
								'filter_category_id'		=> $category['category_id'],						
								'sort'						=> 'rand-10',
								'order'               		=> 'DESC',	
								'filter_not_bad'			=> true,		
								'filter_with_variants'		=> true
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

						$products = $this->model_catalog_product->prepareProductToArray($merged);

						if ($products){
							$this->data['categories'][] = [
								'name' 		=> $category['name'],
								'products' 	=> $products
							];
						}	
					}					
				}

			} else {	
				$results = $this->model_module_alsopurchased->getAlsoPurchasedSimple($product_id, $setting['limit']);
				$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);		
			}

			if ($setting['categories']){
				$this->template = 'module/alsopurchased_categories';
			} else {
				$this->template = 'module/alsopurchased';
			}

			$out = $this->render();
			$this->cache->set($this->registry->createCacheQueryString(__METHOD__, $setting, [$product_id]), $out);
		}

		$this->response->setOutput($out);		
	}
}		
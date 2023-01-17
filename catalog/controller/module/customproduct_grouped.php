<?php
class ControllerModuleCustomproductgrouped extends Controller {
	protected function index($setting) {
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
		}else{
			$category_id = 0;
		}
		
		$this->data['unique_id'] = md5(serialize($setting));

		$this->data['customproduct_blocks'] = array();
		$this->language->load('module/customproduct'); 
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('catalog/product'); 					
		$this->load->model('tool/image');
		
		
		foreach ($setting as $setting_part){
			
			$customproduct_block = array();
			
			if ($setting_part['category']==0 || $setting_part['category']==$category_id) {
				
				$customproduct_block['name'] = $setting_part[(int)$this->config->get('config_language_id')]['name'];
				$customproduct_block['description'] = $setting_part[(int)$this->config->get('config_language_id')]['description'];
				
				if ($setting_part[(int)$this->config->get('config_language_id')]['href']){
					$_href = explode(';', $setting_part[(int)$this->config->get('config_language_id')]['href']);
					
					if (isset($_href[0]) && $_href[0]){
						$customproduct_block['href1'] = $_href[0];
					} else {
						$customproduct_block['href1'] = $this->url->link('common/home');
					}
					
					if (isset($_href[1]) && $_href[1]){
						$customproduct_block['href2'] = $_href[1];
					} else {
						$customproduct_block['href2'] = $this->url->link('common/home');
					}
					
				} else {
					$customproduct_block['href1'] = $this->url->link('common/home');
					$customproduct_block['href2'] = $this->url->link('common/home');
				}
				
				$customproduct_block['href'] = ($setting_part[(int)$this->config->get('config_language_id')]['href'])?$setting_part[(int)$this->config->get('config_language_id')]['href']:$this->url->link('common/home');
				
				
				if ($setting_part['image']){
					$customproduct_block['image'] = $this->model_tool_image->resize($setting_part['image'], $setting_part['big_image_width'], $setting_part['big_image_height'], '', 80);
				} else {
					$customproduct_block['image'] = false;
				}
				
				
				if ($setting_part['image2']){
					$customproduct_block['image2'] = $this->model_tool_image->resize($setting_part['image2'], $setting_part['big_image2_width'], $setting_part['big_image2_height'], '', 80);
				} else {
					$customproduct_block['image2'] = false;
				}
				
				$customproduct_block['dimensions'] = array(
					'w' => $setting_part['image_width'],
					'h' => $setting_part['image_height']
				);
				$customproduct_block['position'] = $setting_part['position'];
				
				$customproduct_block['products'] = array();
				$products = explode(',', $setting_part['products']);
				
				if ($setting_part['random'] && $setting_part['random_limit']){
					if ($setting_part['random'] == 'specials'){							
						$special_data = array(
							'start' => 0,
							'limit' => (int)$setting_part['random_limit'],
							'sort' => 'RAND()',
							'return_just_ids' => true
						);
						
						$products = $this->model_catalog_product->getProductSpecials($special_data);
					}					
				}	
				
				$results = [];
				foreach ($products as $product_id) {
					$results[] = $this->model_catalog_product->getProduct($product_id);
				}
				
				$customproduct_block['products'] = $this->model_catalog_product->prepareProductToArray($results);
				
				$this->data['customproduct_blocks'][] = $customproduct_block;
			}
		}
		
		$this->template = 'module/customproduct_grouped_image';
		
		$this->render();
		
	}								
}
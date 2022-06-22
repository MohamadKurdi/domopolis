<?php
	class ControllerModuleSpecial extends Controller {
		protected function index($setting) {
			
			$current_store = (int)$this->config->get('config_store_id');
			$current_lang  = (int)$this->config->get('config_language_id');
			$current_curr  = (int)$this->currency->getId();
			
			$product_id = 0;
			$path_id = 0;
			$cid = 0;
			$category_id = false;
			if (isset($this->request->get['product_id'])){
				$product_id = $this->request->get['product_id'];
				//	$category_id = $this->model_catalog_product->getOnlyProductPath($product_id);
				$category_id = false;
				$cid = 0;
				} else {
				if (isset($this->request->get['path'])){
					$path_id = $this->request->get['path'];
					$parts = explode('_', (string)$this->request->get['path']);
					$category_id = (int)array_pop($parts);
					$cid = $category_id;
				}
			}
			if (isset($this->request->get['manufacturer_id'])){
				$mid = $this->request->get['manufacturer_id'];			
				} else {
				$mid = 0;
			}
			
			$this->bcache->SetFile('module_' . $current_lang . $current_curr . $cid . $mid . md5(serialize($setting)) . '.tpl', 'special_module'.$current_store);
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();	
				$this->setBlockCachedOutput($out);
				} else {
				
				
				$do_not_continue = false;
				if ($mid && !$cid){
					
					$this->load->model('catalog/manufacturer');
					$ao = $this->model_catalog_manufacturer->getManufacturerContentByManufacturerId($mid);
					
					if ($ao) {
						$out = '';
						$do_not_continue = true;
						$this->bcache->WriteFile($out);			
					} 
				}
				
				
				if (!$do_not_continue)	{	
					
					
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
					
					if ($mid){
						$data['filter_manufacturer_id'] = $mid;
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
						
						if ($mid){
							$data['filter_manufacturer_id'] = $mid;
						}	
						
						$results = $this->model_catalog_product->getProductSpecials($data);
					}
					
					$this->data['dimensions'] = array(
					'w' => $setting['image_width'],
					'h' => $setting['image_height']
					);
					
					foreach ($results as &$result) {
						
						if ($result['is_option_for_product_id'] > 0 && $tmp = $this->model_catalog_product->getProduct($result['is_option_for_product_id'])){
							$result = $tmp;
						}
						
						
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
							} else {
							$image = false;
						}
						
						
						if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
							} else {
							$price = false;
						}
						
						if ((float)$result['special']) { 
							$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
							} else {
							$special = false;
						}
						
						if ($option_prices = $this->model_catalog_product->getProductOptionPrices($result['product_id'])){
								if (isset($option_prices['special']) && $option_prices['special']){
									$special = $option_prices['special'];
									} else {
									$special = false;
								}
								
								if (isset($option_prices['price']) && $option_prices['price']){
									$price = $option_prices['price'];
								}
								
								if ($option_prices['result']){
									$result['price'] = $option_prices['result']['price'];
									$result['special'] = $option_prices['result']['special'];
								}
						}
						
						if ($this->config->get('config_review_status')) {
							$rating = $result['rating'];
							} else {
							$rating = false;
						}
						
						$this->data['products'][] = array(
						'product_id' => $result['product_id'],
						'thumb'   	 => $image,
						'name'    	 => $result['name'],
						'price'   	 => $price,
						'special' 	 => $special,
						'saving'	 => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
						'rating'     => $rating,
						'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
						);
					}
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
						} else {
						$this->template = 'default/template/module/special.tpl';
					}
					$out = $this->render();
					$this->bcache->WriteFile($out);
				}
			}
			
		}
	}
?>
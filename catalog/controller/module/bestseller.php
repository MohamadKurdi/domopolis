<?php
	class ControllerModuleBestSeller extends Controller {
		protected function index($setting) {
			$this->language->load('module/bestseller');
			$this->load->model('catalog/product');
			$this->load->model('export/yandex_yml');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$product_id = 0;
			$path_id = 0;
			$category_id = 0;
			$category_id = false;
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();
			if (isset($this->request->get['path'])){
				$path_id = $this->request->get['path'];
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
				$category_id = $category_id;
				} else {
				$category_id = 0;			
			}
			
			if ($category_id == 0 && isset($this->request->get['product_id'])){
				$this->load->model('export/yandex_yml');
				$category_id = $this->model_export_yandex_yml->getProductCategory($this->request->get['product_id']);
			}
			
			if (isset($this->request->get['manufacturer_id'])){
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];			
				} else {
				$manufacturer_id = 0;
			}
			
			//MARKDOWN OVERLOAD
			if (!empty($this->request->get['product_id'])){
				$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
				if ($product_info && $product_info['is_markdown'] && $product_info['markdown_product_id']){
					$category_id = $this->model_export_yandex_yml->getProductCategory($product_info['markdown_product_id']);
				}
			}
			
			
			$this->bcache->SetFile('module_'. $currency_id . $language_id . $category_id . $manufacturer_id . md5(serialize($setting)). '.tpl', 'bestseller_module'. $store_id);
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
				$do_not_continue = false;
				if ($manufacturer_id && !$category_id){
					
					$ignore_brands = explode(',',$setting['ignore_brands']);
					if (is_array($ignore_brands) && in_array($manufacturer_id, $ignore_brands)){
						$do_not_continue = false;
						} else {
						
						$this->load->model('catalog/manufacturer');
						$ao = $this->model_catalog_manufacturer->getManufacturerContentByManufacturerId($manufacturer_id);
						
						if ($ao) {
							$out = '';
							$do_not_continue = true;
							$this->bcache->WriteFile($out);			
						} 
					}
				}
				
				
				if (!$do_not_continue)	{
									
					
					$this->data['heading_title'] = $this->language->get('heading_title');	
					
					if (isset($this->request->get['product_id'])){
						$this->data['heading_title'] = $this->language->get('heading_title_product');	
					}
					
					$this->data['button_cart'] = $this->language->get('button_cart');		
					$this->load->model('catalog/product');		
					$this->load->model('tool/image');
					$this->data['products'] = array();
					
					if ($category_id) {
						$results = $this->model_catalog_product->getBestSellerProductsForCategory($setting['limit'], $category_id);
						} elseif($manufacturer_id) {
						$results = $this->model_catalog_product->getBestSellerProductsForManufacturer($setting['limit'], $manufacturer_id);
						} else {	
						$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
					}
					
					if (!$results){						
						$data = array(
						'sort'  => 'pd.name',
						'order' => 'ASC',
						'start' => 0,
						'limit' => $setting['limit'],
						'filter_category_id' => $category_id,
						'filter_sub_category' => true
						);
						$results = $this->model_catalog_product->getBestSellerProductsForCategory($setting['limit'], $category_id);
					}
					
					$this->data['dimensions'] = array(
					'w' => $setting['image_width'],
					'h' => $setting['image_height']
					);
					$this->data['position'] = $setting['position'];
					
					$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/bestseller.tpl';
						} else {
						$this->template = 'default/template/module/bestseller.tpl';
					}
					
					$out = $this->render();
					$this->bcache->WriteFile($out);
				}
			}
		}
	}
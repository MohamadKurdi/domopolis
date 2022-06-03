<?php
	class ControllerModuleFeatured extends Controller {
		protected function index($setting) {
			$this->language->load('module/featured'); 
			
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();
			
			$this->bcache->SetFile('module.'.$store_id.$language_id.$currency_id.md5(serialize($setting)).'.tpl', 'featured');
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
				$this->data['heading_title'] = $this->language->get('heading_title');
				
				$this->data['button_cart'] = $this->language->get('button_cart');
				
				$this->load->model('catalog/product'); 
				
				$this->load->model('tool/image');
				
				$this->data['products'] = array();
				
				if (isset($setting['product_block'])){
					$indexes = array_map('intval', explode(',', $setting['product_block']));
					} else {
					$indexes = array(1);
				}
				
				$this->data['tabs'] = array();
				foreach ($indexes as $idx) {
					
					if (!$this->config->get('featured_titles_' . $idx)){
						continue;
					}
					
					$products = explode(',', $this->config->get('featured_product_' . $idx));
					
					if (!$products){
						continue;
					}
					
					$this->data['tabs'][$idx] = array(
					'title' => $this->config->get('featured_titles_' . $idx),
					'href' => $this->config->get('featured_hrefs_' . $idx),
					'products' => array()
					);				
					
					if ($banner = $this->config->get('featured_images_' . $idx)){
						$this->data['tabs'][$idx]['banner'] = $this->model_tool_image->resize($banner, 280 , 491);					
						} else {
						$this->data['tabs'][$idx]['banner'] = false;
					}
					
					if (empty($setting['limit'])) {
						$setting['limit'] = 10;
					}
					
					$this->data['position'] = $setting['position'];
					$this->data['dimensions'] = array(
					'w' => $setting['image_width'],
					'h' => $setting['image_height']
					);
					
					$products = array_slice($products, 0, (int)$setting['limit']);
					
					$results = array();
					
					foreach ($products as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						
						if ($product_info) {
							$results[$product_id] = $product_info;
						}
					}
					
					$this->data['tabs'][$idx]['products'] = $this->model_catalog_product->prepareProductToArray($results);
					
				}
				
				if (isset($setting['image']) && $setting['image'] && isset($setting['big_image_width'])){
					$this->data['image'] = $this->model_tool_image->resize($setting['image'], $setting['big_image_width'], $setting['big_image_height']);
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured_with_image.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/featured_with_image.tpl';
						} else {
						$this->template = 'default/template/module/featured_with_image.tpl';
					}
					
					} else {
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/featured.tpl';
						} else {
						$this->template = 'default/template/module/featured.tpl';
					}
					
				}
				
				$out = $this->render();
				$this->bcache->WriteFile($out);
			}
		}
	}		
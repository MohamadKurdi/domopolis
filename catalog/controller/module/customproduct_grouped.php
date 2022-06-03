<?php
	class ControllerModuleCustomproductgrouped extends Controller {
		protected function index($setting) {
			
			if (isset($this->request->get['path'])) {
				$parts = explode('_', (string)$this->request->get['path']);
				$category_id = (int)array_pop($parts);
				}else{
				$category_id = 0;
			}
			
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();
			
			$this->data['unique_id'] = md5(serialize($setting));
			
			$this->bcache->SetFile('module.'.$store_id. $language_id . $currency_id . $this->data['unique_id'] . '.tpl', 'customproductgrouped');
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
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
							$customproduct_block['image_webp'] = $this->model_tool_image->resize($setting_part['image'], $setting_part['big_image_width'], $setting_part['big_image_height'], '', 80);
							$customproduct_block['image_mime'] = $this->model_tool_image->getMime($setting_part['image']);
							} else {
							$customproduct_block['image'] = false;
						}
						
						
						if ($setting_part['image2']){
							$customproduct_block['image2'] = $this->model_tool_image->resize($setting_part['image2'], $setting_part['big_image2_width'], $setting_part['big_image2_height'], '', 80);
							$customproduct_block['image2_webp'] = $this->model_tool_image->resize($setting_part['image2'], $setting_part['big_image_width'], $setting_part['big_image_height'], '', 80);
							$customproduct_block['image2_mime'] = $this->model_tool_image->getMime($setting_part['image2']);
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
						
						foreach ($products as $product_id) {
							$product_info = $this->model_catalog_product->getProduct($product_id);
							
							if ($product_info) {
								if ($product_info['image']) {
									$image = $this->model_tool_image->resize($product_info['image'], $setting_part['image_width'], $setting_part['image_height']);
									} else {
									$image = false;
								}
								
								if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
									$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
									} else {
									$price = false;
								}
								
								if ((float)$product_info['special']) {
									$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
									} else {
									$special = false;
								}
								
								if ($this->config->get('config_review_status')) {
									$rating = $product_info['rating'];
									} else {
									$rating = false;
								}
								
								$_description = '';
								$is_not_certificate = (strpos($product_info['location'], 'certificate') === false);
								if ($is_not_certificate){
									
									if (mb_strlen($product_info['description']) > 10){
										$_description = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 300) . '..';
									}
									
									} else {
									$_description = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
								}
								
								
								if (SITE_NAMESPACE == 'HAUSGARTEN'){
									
									if ($option_prices = $this->model_catalog_product->getProductOptionPrices($product_info['product_id'])){
										if (isset($option_prices['special']) && $option_prices['special']){
											$special = $option_prices['special'];
											} else {
											$special = false;
										}
										
										if (isset($option_prices['price']) && $option_prices['price']){
											$price = $option_prices['price'];
										}
										
										if ($option_prices['result']){
											$product_info['price'] = $option_prices['result']['price'];
											$product_info['special'] = $option_prices['result']['special'];
										}
									}
									
								}
								
								
								$customproduct_block['products'][] = array(
								'new'         => $product_info['new'],
								'show_action' => $product_info['additional_offer_count'],
								'product_id'  => $product_info['product_id'],
								'thumb'       => $image,
								'is_set' 	  => $product_info['set_id'],
								'name'        => $product_info['name'],
								'description' => $_description,
								'price'       => $price,
								'special'     => $special,
								'rating'      => $product_info['rating'],					
								'count_reviews' => $product_info['reviews'],
								'minimum' 		=> $product_info['minimum'],
								'colors'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductColorsByGroup($product_info['product_id'], $product_info['color_group']):false,
								'options'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductOptionsForCatalog($product_info['product_id']):false,
								'saving'      => round((($product_info['price'] - $product_info['special'])/($product_info['price'] + 0.01))*100, 0),
								'rating'      => $product_info['rating'],
								'sort_order'  => $product_info['sort_order'],
								'can_not_buy' => ($product_info['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
								'has_child'  => $product_info['has_child'],
								'stock_status'  => $product_info['stock_status'],
								'location'      => $product_info['location'],
								'reviews'     => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
								'quickview'        => $this->url->link('product/quickview', 'product_id=' . $product_info['product_id']),
								'href'        => $this->url->link('product/product','product_id=' . $product_info['product_id'])
								);
							}
						}
						
						$this->data['customproduct_blocks'][] = $customproduct_block;
					}
				}
				
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/customproduct_grouped_image.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/customproduct_grouped_image.tpl';
					} else {
					$this->template = 'default/template/module/customproduct_grouped_image.tpl';
				}	
				
				$out = $this->render();
				$this->bcache->WriteFile($out);
				
			}							
			
		}
		
		
	}
?>
<?php
	class ControllerModuleCustomproduct extends Controller {
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
			
			if($setting['category']==0 || $setting['category']==$category_id){
				
				$this->bcache->SetFile('module.'.$store_id. $language_id . $currency_id . $this->data['unique_id'] . '.tpl', 'customproduct');
				
				if ($this->bcache->CheckFile()) {		
					
					$out = $this->bcache->ReturnFileContent();
					$this->setBlockCachedOutput($out);
					
					} else {
					
					$this->load->model('catalog/product'); 					
					$this->load->model('tool/image');
					$this->language->load('module/customproduct'); 
					
					$this->data['heading_title'] = $setting[(int)$this->config->get('config_language_id')]['name'];
					
					$this->data['description'] = $setting[(int)$this->config->get('config_language_id')]['description'];
					
					$this->data['button_cart'] = $this->language->get('button_cart');
					
					$this->data['products'] = array();
					
					$this->data['dimensions'] = array(
					'w' => $setting['image_width'],
					'h' => $setting['image_height']
					);
					$this->data['position'] = $setting['position'];
					
					$products = explode(',', $setting['products']);       
					
					if ($setting['random'] && $setting['random_limit']){
						if ($setting['random'] == 'specials'){							
							
							$special_data = array(
							'start' => 0,
							'limit' => (int)$setting['random_limit'],
							'sort' => 'RAND()',
							'return_just_ids' => true
							);		
							
							if ($category_id && $setting['category'] == $category_id) {
								$special_data['filter_category_id'] = $category_id;
							}
							
							$products = $this->model_catalog_product->getProductSpecials($special_data);
						}					
					}	
					
					if ($setting['random'] && $setting['random_limit']){
						if ($setting['random'] == 'list'){			
							
							$random_keys = array_rand ( $products, $setting['random_limit'] );
							$tmp_products = $products;
							$products = array();
							foreach ($random_keys as $__key){
								$products[] = $tmp_products[$__key];
							}													
						}
					}
					
					foreach ($products as $product_id) {
						
						$product_info = $this->model_catalog_product->getProduct($product_id);
						
						if ($product_info) {
							if ($product_info['image']) {
								$image = $this->model_tool_image->resize($product_info['image'], $setting['image_width'], $setting['image_height']);
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
							
							$_description = '';

							$stock_data = $this->model_catalog_product->parseProductStockData($product_info);
							
							$this->data['products'][] = array(
							'product_id' => $product_info['product_id'],
							'new'         => $product_info['new'],
							'stock_type'  => $stock_data['stock_type'],						
							'show_delivery_terms' => $stock_data['show_delivery_terms'],
							'stock_status'  => $product_info['stock_status'],
							'location'      => $product_info['location'],
							'show_action' => $product_info['additional_offer_count'],
							'thumb'        => $image,						
							'name'         => $product_info['name'],
							'price'        => $price,
							'special'      => $special,
							'rating'     	=> $product_info['rating'],
							'minimum' 		=> $product_info['minimum'],
							'count_reviews' => $product_info['reviews'],
							'colors'	  	=> $this->model_catalog_product->getProductColorsByGroup($product_info['product_id'], $product_info['color_group']),
							'options'	  	=> $this->model_catalog_product->getProductOptionsForCatalog($product_info['product_id']),
							'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
							'saving'      => round((($product_info['price'] - $product_info['special'])/($product_info['price'] + 0.01))*100, 0),
							'can_not_buy' => ($product_info['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
							'has_child'  => $product_info['has_child'],
							'quickview'        => $this->url->link('product/quickview', 'product_id=' . $product_info['product_id']),
							'href'         => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
							);
						}
					}
					
					$this->data['href'] = ($setting[(int)$this->config->get('config_language_id')]['href'])?$setting[(int)$this->config->get('config_language_id')]['href']:$this->url->link('common/home');
					
					if ($setting['image'] && mb_strlen($setting['image']) > 2){
						$this->data['image'] = $this->model_tool_image->resize($setting['image'], $setting['big_image_width'], $setting['big_image_height'], '', 80);
						$this->data['image_mime'] = $this->model_tool_image->getMime($setting['image']);
						$this->data['image_webp'] = $this->model_tool_image->resize_webp($setting['image'], $setting['big_image_width'], $setting['big_image_height'], '', 80);
						
						if ($setting['sort_order']%2 == 0){
							$this->data['class'] = 'layout-left';				
							} else {
							$this->data['class'] = 'layout-right';
						}
						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/customproduct_with_image.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/module/customproduct_with_image.tpl';
							} else {
							$this->template = 'default/template/module/customproduct_with_image.tpl';
						}
						
						
						} else {
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/customproduct.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/module/customproduct.tpl';
							} else {
							$this->template = 'default/template/module/customproduct.tpl';
						}
					}
					
					
					$out = $this->render();
					$this->bcache->WriteFile($out);
				}
			}
		}
	}
?>
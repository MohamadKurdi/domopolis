<?php
class ControllerModuleProductList extends Controller {
	protected function index($args) {
		
		$title = $args['title'];
		$list = $args['list'];
		
		$module_id = md5(serialize($args));
			
		$current_store = (int)$this->config->get('config_store_id');
		$current_lang  = (int)$this->config->get('config_language_id');
		$current_curr  = (int)$this->currency->getId();
		
		$this->bcache->SetFile('products_list.' . $current_lang. $current_curr . $module_id  . '.tpl', 'products_list'.$current_store);
				
		if ($this->bcache->CheckFile()) {		
		
			$out = $this->bcache->ReturnFileContent();						
			$this->setBlockCachedOutput($out);
		
		} else {
		
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('tool/image');
		
			$this->data['title'] = $title;
			$this->data['module_id'] = $module_id;
			$this->data['products'] = array();
			
			foreach ($list as $pid) {
																					
				$real_product = $this->model_catalog_product->getProduct($pid);

				$this->data['product_dimensions'] = array(
						'w' => $this->config->get('config_image_product_width'),
						'h' => $this->config->get('config_image_product_height')
				);
				
				if ($real_product['image']) {
						$image = $this->model_tool_image->resize($real_product['image'], $this->data['product_dimensions']['w'], $this->data['product_dimensions']['h']);
				} else {
						$image = $this->model_tool_image->resize('no_image.jpg', $this->data['product_dimensions']['w'], $this->data['product_dimensions']['h']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$_price = $this->currency->format($this->tax->calculate($real_product['price'], $real_product['tax_class_id'], $this->config->get('config_tax')));
				} else {
						$_price = false;
				}

				if ((float)$real_product['special']) {
						$_special = $this->currency->format($this->tax->calculate($real_product['special'], $real_product['tax_class_id'], $this->config->get('config_tax')));
				} else {
						$_special = false;
				}	

				if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$real_product['special'] ? $real_product['special'] : $real_product['price']);
				} else {
						$tax = false;
				}		

				if (isset($real_product['display_price_national']) && $real_product['display_price_national'] && $real_product['display_price_national'] > 0 && $real_product['currency'] == $this->currency->getCode()){
						$_price = $this->currency->format($this->tax->calculate($real_product['display_price_national'], $real_product['tax_class_id'], $this->config->get('config_tax')), $real_product['currency'], 1);
				}
				
				if ($this->config->get('config_review_status')) {
						$rating = (int)$real_product['rating'];
				} else {
						$rating = false;
				}
							
				$is_not_certificate = (strpos($real_product['location'], 'certificate') === false);
							
				$this->data['products'][] = array(
							'new'         => $real_product['new'],
							'show_action' => $real_product['additional_offer_count'],
							'product_id'  => $real_product['product_id'],
							'thumb'       => $image,
							'is_set'	  => $real_product['set_id'],
							'name'        => $real_product['name'],
							'description' => $is_not_certificate?(utf8_substr(strip_tags(html_entity_decode($real_product['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..'):html_entity_decode($real_product['description'], ENT_QUOTES, 'UTF-8'),
							'price'       => $_price,
							'special'     => $_special,
							'saving'      => round((($real_product['price'] - $real_product['special'])/($real_product['price'] + 0.01))*100, 0),
							'tax'         => $tax,
							'rating'      => $real_product['rating'],					
							'count_reviews' => $real_product['reviews'],
							'minimum' 		=> $real_product['minimum'],
							'sort_order'  => $real_product['sort_order'],
							'can_not_buy' => ($real_product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
							'stock_status'  => $real_product['stock_status'],
							'location'      => $real_product['location'],
							'reviews'     => sprintf($this->language->get('text_reviews'), (int)$real_product['reviews']),
							'href'        => $this->url->link('product/product', 'product_id=' . $real_product['product_id'])
					);
							
						}
		
			$this->language->load('product/manufacturer');
			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');			
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');
			$this->data['text_show_all_products'] = $this->language->get('show_all_products');
			$this->data['text_all_collection'] = $this->language->get('all_collection');
			$this->data['text_open_collection'] = $this->language->get('open_collection');

			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['compare'] = $this->url->link('product/compare');
				
			$this->template = $this->config->get('config_template') . '/template/product/product_list.tpl';
		
			$out = $this->render();
			$this->bcache->WriteFile($out);
		}
	}
	
}
<?php  
	class ControllerCommonHome extends Controller {
		public function index() {
			$this->load->model('design/layout');
			$this->load->model('catalog/shop_rating');

			$this->document->setTitle($this->config->get('config_title'));
			$this->document->setDescription($this->config->get('config_meta_description'));
			
			if ($this->config->get('hb_snippets_og_enable') == '1'){
				$this->document->addOpenGraph('og:title', $this->config->get('config_title'));
				$this->document->addOpenGraph('og:type', 'website');
				
				$this->document->addOpenGraph('og:image', $this->config->get('config_ssl') . DIR_IMAGE_NAME . $this->config->get('config_logo'));
				$this->document->addOpenGraph('og:url', $this->config->get('config_ssl'));
				$this->document->addOpenGraph('og:description', $this->config->get('config_meta_description'));
			}
			
			$this->data['heading_title'] = $this->config->get('config_title');

			$this->data['store_name'] 	= $store_name 	= $this->config->get('config_name');
			$this->data['store_url'] 	= $store_url 	= $this->config->get('config_ssl');
			
			$hb_snippets_kg_data = $this->config->get('hb_snippets_kg_data');
			
			if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
				$logo = $this->config->get('config_ssl') . DIR_IMAGE_NAME . $this->config->get('config_logo');
			} else {
				$logo = '';
			}
			
			$hb_snippets_kg_data = str_replace('{store_name}',	$store_name, $hb_snippets_kg_data);
			$hb_snippets_kg_data = str_replace('{store_logo}',	$logo, $hb_snippets_kg_data);
			$hb_snippets_kg_data = str_replace('{store_url}',	$store_url, $hb_snippets_kg_data);
			
			$this->data['hb_snippets_kg_data'] 		= $hb_snippets_kg_data ;
			$this->data['hb_snippets_kg_enable'] 	= $this->config->get('hb_snippets_kg_enable');				
			$this->data['hb_snippets_local_enable'] 	= $this->config->get('hb_snippets_local_enable');
			$this->data['hb_snippets_local_snippet'] 	= $this->config->get('hb_snippets_local_snippet');
			
			$layout_id 		= $this->model_design_layout->getLayout('common/home');
			$store_id 		= $this->config->get('config_store_id');
			$language_id 	= $this->config->get('config_language_id');
			
			foreach ($this->language->loadRetranslate('common/home') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}			

			if ($this->config->get('config_brands_on_homepage')){
				$this->data['brands'] = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, [], ['manufacturers_home']));

				if (!$this->data['brands']){
					$this->load->model('catalog/manufacturer');
					$this->load->model('tool/image');

					$this->data['brands'] = $this->model_catalog_manufacturer->getManufacturers(['sort' => 'm.sort_order', 'order' => 'ASC', 'menu_brand' => 1, 'limit' => 30]);

					foreach ($this->data['brands'] as $k => $b) {				
						$this->data['brands'][$k]['thumb'] = $this->model_tool_image->resize($b['image'], 101, 101);	
						$this->data['brands'][$k]['url'] = $this->url->link('catalog/manufacturer/info', 'manufacturer_id=' . $b['manufacturer_id']);
					}

					$this->cache->set($this->registry->createCacheQueryString(__METHOD__, [], ['manufacturers_home']), $this->data['brands']);
				}				
			} else {
				$this->data['brands'] = [];
			}			

			$this->data['shop_rating_struct'] = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, [], ['shop_rating_struct']));
			
			if (!$this->data['shop_rating_struct']){				
				$this->data['shop_rating_struct'] = [
					'shop_rating' 			=> $this->model_catalog_shop_rating->getStoreRatingScore(),
					'shop_rating_count' 	=> $this->model_catalog_shop_rating->getStoreRatingsTotal()
				];

				$this->cache->set($this->registry->createCacheQueryString(__METHOD__, [], ['shop_rating_struct']), $this->data['shop_rating_struct']);
			}

			$this->data['shop_rating'] 			= $this->data['shop_rating_struct']['shop_rating'];
			$this->data['shop_rating_count'] 	= $this->data['shop_rating_struct']['shop_rating_count'];
			
			$this->data['name'] 	= $this->config->get('config_name');
			$this->data['o_email'] 	= $this->config->get('config_display_email');	
			$this->data['base'] = $this->config->get('config_ssl');

			$this->document->addLink(rtrim($this->data['base'],'/'), 'canonical');
			
			if ($this->config->get('config_google_remarketing_type') == 'ecomm') {				
				$this->data['google_tag_params'] = array(
				'ecomm_prodid' => '',				
				'ecomm_pagetype' => 'home',
				'ecomm_totalvalue' => 0
				);
				
				} else {	
				$this->data['google_tag_params'] = array(
				'dynx_itemid' => '',
				'dynx_itemid2' => '',
				'dynx_pagetype' => 'home',
				'dynx_totalvalue' => 0
				);		
			}
			
			if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
					$this->template = 'template/' . $template;				
				} else {
					$this->template = 'common/home.tpl';
			}	
			
			$this->children = [
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header',
			];

			if ($this->config->get('config_mmenu_on_homepage')){
				$this->children[] = 'module/mmenu';
			}
			
			$this->response->setOutput($this->render());
		}
		
		public function homecart(){						
			$this->language->load('common/home');
			
			foreach ($this->language->loadRetranslate('common/home') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			if ($this->cart->hasProducts()){
				$this->load->model('tool/image');
				$this->data['home_cart_products'] 		= [];			
				$this->data['home_cart_total_quantity'] = 0;
				
				$this->data['home_cart_total'] = $this->cart->getSubTotal();
				$this->data['home_cart_count'] = $this->cart->countProducts();								

				if ($this->config->get('config_language') == 'uk'){
					$this->data['home_cart_line_1'] = $this->language->get('text_general_in_da_cart') . ' ' . $this->cart->countProducts() . ' ' . getUkrainianPluralWord($this->cart->countProducts(), $this->language->get('text_products_pluralized'));			
				} else {
					$this->data['home_cart_line_1'] = $this->language->get('text_general_in_da_cart') . ' ' . $this->cart->countProducts() . ' ' . morphos\Russian\NounPluralization::pluralize($this->cart->countProducts(), $this->language->get('text_general_product'));
				}

				$this->data['home_cart_line_2'] = $this->language->get('text_general_for_sum') . ' <b>' . $this->currency->format($this->cart->getSubTotal()) . '</b>';
				
				$this->data['home_cart_text_go_to_checkout'] 	= $this->language->get('text_general_go_to_checkout');
				$this->data['home_cart_text_go_to_cart'] 		= $this->language->get('text_general_go_to_cart');
				$this->data['home_cart_href_go_to_checkout'] 	= $this->url->link('checkout/checkout');
				
				foreach ($this->cart->getProducts() as $product) {
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
						} else {
						$image = '';
					}
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
						} else {
						$price = false;
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price_old = $this->currency->format($this->tax->calculate($product['price_old'], $product['tax_class_id'], $this->config->get('config_tax')));
						} else {
						$price_old = false;
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price_national = $product['price_national'];

						$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);												
						$total_national = $this->currency->format($price_national * $product['quantity'], $this->config->get('config_regional_currency'), '1');

						if ($product['price_old']){
							$total_old_national = $this->currency->format($this->tax->calculate($product['price_old'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
							} else {
							$total_old_national = false;
						}

						} else {
						$total 				= false;
						$price_national 	= false;
						$total_national 	= false;
						$total_old_national = false;
					}
					
					$this->data['home_cart_total_quantity'] += $product['quantity'];
					
					$this->data['home_cart_products'][] = [
					'key'       			=> $product['key'],
					'thumb'     			=> $image,
					'name'      			=> $product['name'],					
					'model'     			=> $product['model'], 					
					'quantity'  			=> $product['quantity'],
					'price'     			=> $price,
					'price_national'     	=> $price_national,				
					'total'     			=> $total,
					'saving' 				=> $product['saving'],
					'total_old_national'  	=> $total_old_national,	
					'total_national'  		=> $total_national,	
					'href'      			=> $this->url->link('product/product', 'product_id=' . $product['product_id'])					
					];					
				}				
			}
			
			$this->data['cart_has_one_product'] = false;
			if ($this->cart->countProductsNoQuantity() == 1){
				$this->data['cart_has_one_product'] = true;				
				unset($product);

				foreach ($this->cart->getProducts() as $product) {
					$this->data['cart_one_product_name'] = $product['name'];
				}
			}
			
			
			$this->template = 'blocks/homecart.tpl';						
			$this->response->setOutput($this->render());			
		}		
	}		
	
	
	

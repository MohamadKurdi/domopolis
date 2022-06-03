<?php
	class ControllerCheckoutSuccess extends Controller { 
		
		
		public function getOrderDeliveryCost($order_id){
			$this->load->model('checkout/order');
			$totals = $this->model_checkout_order->getOrderTotals($order_id);
			
			foreach ($totals as $total){
				if ($total['code'] == 'shipping'){
					return $total['value_national'];
				}
			}
			
		}
		
		public function getOrderTotal($order_id){
			$this->load->model('checkout/order');
			$totals = $this->model_checkout_order->getOrderTotals($order_id);
			
			foreach ($totals as $total){
				if ($total['code'] == 'total'){
					return $total['value_national'];
				}
			}
			
		}
		
		public function index() { 	
			
			if (!isset($this->session->data['order_id']) && IS_DEBUG){
				if (SITE_NAMESPACE == 'KITCHEN') {
					$this->session->data['order_id'] = 213196;	
					} elseif (SITE_NAMESPACE == 'HAUSGARTEN') {
					$this->session->data['order_id'] = 587158;	
				}
			}
			
			//detecting mobile
			$this->data['is_pc'] = (!IS_MOBILE_SESSION && !IS_TABLET_SESSION);
			
			$this->load->model('tool/image');
			$this->language->load('information/actions');
			
			$this->load->model('catalog/actions');
			$this->load->model('setting/setting');
			$actions_setting = $this->config->get('actions_setting');
			$this->load->model('catalog/product');
			
			$this->load->model('localisation/country');
			
			foreach ($this->language->loadRetranslate('checkout/success') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->data['config_google_merchant_id'] = $this->config->get('config_google_merchant_id');
			$date_format = $this->language->get('date_long_format');
			
			$results = $this->model_catalog_actions->getActionsRAND(3);
			
			$this->data['actions_all'] = array();
			foreach ($results as $result) {
				
				$date_start = date( 'j', $result['date_start'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $result['date_start'] ));
				$date_end = date( 'j', $result['date_end'] ) . ' ' . $this->model_catalog_actions->getMonthName(date( 'n', $result['date_end'] ));
				
				if ($result['image'] AND $actions_setting['show_image']) {
					$image = $this->model_tool_image->resize($result['image'], $actions_setting['image_width'], $actions_setting['image_height']);
					} else {
					$image = FALSE;
				}
				
				if ($actions_setting['show_date']) {
					$date = sprintf($this->language->get('date_actions_format'), $date_start, $date_end);
					} else {
					$date = FALSE;
				}
				
				$discount = false;
				if ($result['ao_group']){
					
					// Получаем скиду (-%, подарок, цена)
					$query = $this->db->query("SELECT `percent`, `price` FROM `product_additional_offer` WHERE `ao_group` = '".$this->db->escape($result['ao_group'])."' LIMIT 1");
					if ($query->num_rows) {
						$percent = $query->row['percent'];
						if ($percent && $percent < 100) {
							$discount = "-".$percent."%";
                            } elseif ($percent == 100) {
							$discount = "free";
                            } else {
							// print $query->row['price'];
							$discount = $this->currency->format($this->tax->calculate($query->row['price'], 0, $this->config->get('config_tax')));
						}
					}
					
				}
				
				
				// $this->data['discount'] = $discount;
				
				$this->data['actions_all'][] = array(
				'caption'		=> $result['caption'],
				'date_start'	=> $date_start, //date( $date_format, $result['date_start'] ),
				'date_end'		=> $date_end, //date( $date_format, $result['date_end'] ),			
				'date'			=> $date,
				'thumb'			=> $image,
				'description'	=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href'			=> $this->url->link('information/actions', 'actions_id=' . $result['actions_id']),
				'discount'      => $discount
				);					
			}
			
			if (isset($this->session->data['order_id'])) {
				
				$this->session->data['last_order_id'] = $this->session->data['order_id'];
				$this->load->model('checkout/order');
				$this->load->model('catalog/product');
				
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				$products = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
				
				if ($this->config->get('config_google_remarketing_type') == 'ecomm') {
					
					$this->data['google_tag_params'] = array(
					'ecomm_itemid' => array(),
					'ecomm_pagetype' => 'purchase',
					'ecomm_totalvalue' => array()
					);	
					
					$ecomm_itemid = array();
					$ecomm_itemid2 = array();
					$ecomm_totalvalue = array();
					
					foreach ($products as $product){
						$ecomm_itemid[] = $product['product_id'];
						$ecomm_totalvalue[] = $product['price_national'];				
					}
					
					$this->data['google_tag_params']['ecomm_itemid'] = implode("','", $ecomm_itemid);
					$this->data['google_tag_params']['ecomm_totalvalue'] = implode(",", $ecomm_totalvalue);
					
					} else {	
					
					$this->data['google_tag_params'] = array(
					'dynx_itemid' => array(),
					'dynx_itemid2' => array(),
					'dynx_pagetype' => 'conversion',
					'dynx_totalvalue' => array()
					);	
					
					$dynx_itemid = array();
					$dynx_itemid2 = array();
					$dynx_totalvalue = array();
					
					foreach ($products as $product){
						$dynx_itemid[] = $product['product_id'];
						$dynx_itemid2[] = $product['model'];
						$dynx_totalvalue[] = $product['price_national'];				
					}
					
					$this->data['google_tag_params']['dynx_itemid'] = implode("','", $dynx_itemid);
					$this->data['google_tag_params']['dynx_itemid2'] = implode("','", $dynx_itemid2);
					$this->data['google_tag_params']['dynx_totalvalue'] = implode(",", $dynx_totalvalue);
					
				}			
				
				$transactionProducts = array();
				$transactionGTINS = array();
				$hasAllProductsOnStock = false;
				$hasSomeProductsOnStock = false;
				$productsOnStock = 0;
				
				foreach ($products as $product){
					$realProduct = $this->model_catalog_product->getProduct($product['product_id'], false);
					
					$gtin = false;
					if (\BarcodeValidator::IsValidEAN13($realProduct['ean']) || \BarcodeValidator::IsValidEAN8($realProduct['ean'])){
						$gtin = $realProduct['ean'];
					}
					
					if ($realProduct['current_in_stock']){
						$productsOnStock++;
					}
					
					$transactionProduct = array(
					'id'  			=> $product['product_id'],
					'sku' 			=> $realProduct['sku']?$realProduct['sku']:$realProduct['model'],
					'name' 			=> $realProduct['name'],
					'manufacturer' 	=> $realProduct['manufacturer'],
					'category' 		=> $this->model_catalog_product->getGoogleCategoryPath($realProduct['product_id']),
					'price' 		=> $product['price_national'],
					'total' 		=> $product['total_national'],
					'quantity' 		=> $product['quantity'],
					);										
					
					if ($gtin){
						$transactionGTINS[] = '{"gtin":"' . prepareEcommString($gtin) . '"}';
					}
					
					$transactionProduct = array_map('prepareEcommString', $transactionProduct);					
					$transactionProducts[] = $transactionProduct;			
				}
				
				
				if ($productsOnStock > 0 && count($products) <= $productsOnStock){
					$hasAllProductsOnStock = true;
					$hasSomeProductsOnStock = true;
				}
				
				if ($productsOnStock > 0 && count($products) > $productsOnStock){
					$hasAllProductsOnStock = false;
					$hasSomeProductsOnStock = true;
				}
				
				$transactionEstimatedDelivery = date('Y-m-d', strtotime('+2 week'));
				if ($hasAllProductsOnStock){					
					$transactionEstimatedDelivery = date('Y-m-d', strtotime('+2 day'));
					} elseif (!$hasAllProductsOnStock && $hasSomeProductsOnStock){
					$transactionEstimatedDelivery = date('Y-m-d', strtotime('+2 week'));
				}
				
				$this->data['google_ecommerce_info'] = array(
				'transactionId' 			=> $order_info['order_id'],	
				'transactionEmail' 			=> $order_info['email'],	
				'transactionCurrency'		=> $this->config->get('config_regional_currency'),
				'transactionAffiliation'    => $this->config->get('config_url'),
				'transactionTax'			=> 0.00,
				'transactionTotal'			=> $this->getOrderTotal($order_info['order_id']),
				'transactionShipping'		=> $this->getOrderDeliveryCost($order_info['order_id']),
				'transactionEstimatedDelivery'	=> $transactionEstimatedDelivery,
				'transactionCountryCode'	=> $this->model_localisation_country->getCountryISO2($this->config->get('config_country_id')),
				);	
				
				
				if (!empty($this->session->data['coupon'])){
					$this->data['google_ecommerce_info']['transactionCoupon'] = $this->session->data['coupon'];
				}								
				
				$this->data['google_ecommerce_info'] = array_map('prepareEcommString', $this->data['google_ecommerce_info']);
				$this->data['google_ecommerce_info']['transactionProducts'] = $transactionProducts;
				$this->data['google_ecommerce_info']['transactionGTINS'] = $transactionGTINS;
				
				$this->data['google_ecommerce_info']['customerData'] = [
					'email' 	=> $order_info['email'],
					'telephone' => $order_info['telephone'],
					'name' 		=> $order_info['firstname'],
					'city'		=> $order_info['shipping_city'],
					'postcode'	=> $order_info['shipping_postcode'],
					'address'	=> $order_info['shipping_address_1'],
					'country'	=> $order_info['shipping_country']
				];
				
				$this->data['google_ecommerce_info']['customerData'] = array_map('prepareEcommString', $this->data['google_ecommerce_info']['customerData']);
				
				
				$this->data['google_ecommerce_info']['display_survey'] = ($this->config->get('config_google_merchant_id') && $order_info['email'] && filter_var($order_info['email'] , FILTER_VALIDATE_EMAIL) && !strpos($order_info['email'], $_SERVER['HTTP_HOST']));
				
				$this->cart->clear();
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['guest']);
				unset($this->session->data['comment']);
				unset($this->session->data['order_id']);	
				unset($this->session->data['coupon']);
				unset($this->session->data['reward']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);
				unset($this->session->data['totals']);
				
				} else {
				$this->redirect($this->url->link('checkout/simplecheckout'));
			}
			
			$this->language->load('checkout/success');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->data['breadcrumbs'] = array(); 
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
			);	
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/success'),
			'text'      => $this->language->get('text_success'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['href_account'] = $this->url->link('account/account', '', 'SSL');
			
			if ($this->customer->isLogged()) {
				$this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
				} else {
				$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
			}
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->link('common/home');
			
			$this->load->model('checkout/order');
			
			if (!empty($this->session->data['last_order_id'])) {
				$order_info = $this->model_checkout_order->getOrder($this->session->data['last_order_id']);
			}
			
			if (isset($order_info) && !empty($order_info)) {
				$order_id = $order_info['order_id'];
				$email = $order_info['email'];
				$date_order = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
				$shipping_method = $order_info['shipping_method'];
				$facebook = '';
				$is_logged = '';
				
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
					} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}',
				'{store_telephone}',
				'{store_telephone2}'
				);
				
				$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country'],
				'store_telephone'   => $this->config->get('config_telephone'),
				'store_telephone2'   => $this->config->get('config_telephone2'),
				);
				
				$delivery_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$find = array(
				'{order_id}',
				'{email}',
				'{delivery_address}',
				'{date_order}',
				'{shipping_method}',
				'{facebook}',
				'{is_logged}',
				'{store_telephone}',
				'{store_telephone2}'
				);
				
				$is_logged = $this->config->get('success_page_is_logged');
				
				$replace = array(
				'order_id'         => $order_info['order_id'],
				'email'            => $order_info['email'],
				'delivery_address' => $delivery_address,
				'date_order'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
				'shipping_method'  => $order_info['shipping_method'],
				'payment_method'  => $order_info['payment_method'],
				'facebook'         => ($this->config->get('success_page_facebook_status')) ? '<div class="fb-like-box" data-href="http' . ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) ? 's' : '') . '://www.facebook.com/' . $this->config->get('success_page_facebook_profile') . '" data-width="' . $this->config->get('success_page_facebook_width') . '" data-height="' . $this->config->get('success_page_facebook_height') . '" border-color="' . $this->config->get('success_page_facebook_border') . '" data-show-faces="true" data-stream="false" data-header="false"></div>' : '',
				'is_logged'        => ($this->customer->isLogged() && isset($is_logged[$this->config->get('config_language_id')])) ? html_entity_decode($is_logged[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8') : '',
				'store_telephone'   => $this->config->get('config_telephone'),
				'store_telephone2'   => $this->config->get('config_telephone2'),
				);
				
				$description = $this->config->get('success_page_description');
				
				
				$this->data['order_data'] = $replace;
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
				} else {
				$this->template = 'default/template/common/success.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
			);
			
			$this->response->setOutput($this->render());
		}
	}						
<?php 
	class ControllerCheckoutCart extends Controller {
		private $error = array();
		
		public function index() {
			
			//$this->response->setOutput($this->render());			
		}
		
		protected function validateCoupon() {
			$this->load->model('checkout/coupon');
			
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);			
			
			$this->load->model('module/affiliate');
			$this->model_module_affiliate->isTrackingCoupon($coupon_info['coupon_id']);
			
			if (!$coupon_info) {			
				$this->error['warning'] = $this->language->get('error_coupon');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}		
		}
		
		protected function validateVoucher() {
			$this->load->model('checkout/voucher');
			
			$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);			
			
			if (!$voucher_info) {			
				$this->error['warning'] = $this->language->get('error_voucher');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}		
		}
		
		protected function validateReward() {
			$points = $this->customer->getRewardPoints();
			
			$points_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}	
			
			if (empty($this->request->post['reward'])) {
				$this->error['warning'] = $this->language->get('error_reward');
			}
			
			if ($this->request->post['reward'] > $points) {
				$this->error['warning'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
			}
			
			if ($this->request->post['reward'] > $points_total) {
				$this->error['warning'] = sprintf($this->language->get('error_maximum'), $points_total);
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}		
		}
		
		protected function validateShipping() {
			if (!empty($this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);
				
				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$this->error['warning'] = $this->language->get('error_shipping');
				}
				} else {
				$this->error['warning'] = $this->language->get('error_shipping');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}		
		}
		
		//Это прослойка между библиотекой корзины и внешним миром. Мы не можем сделать AJAX-запрос прямо в библиотеку. Поэтому используется контроллер
		public function add() {
			/*print '<pre>';
				print_r($_POST);
			exit();*/
			$this->language->load('checkout/cart');
			
			$json = array();
			
			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			
			if (isset($this->request->post['set_id'])) {
				$set_id = (int)$this->request->post['set_id'];
				// Проверяем есть ли действительно такой набор
				$query = $this->db->query("SELECT * FROM `set` WHERE `product_id` = '".(int)$product_id."' AND `set_id` = '".(int)$set_id."'");
				if (!$query->num_rows) {
					$set_id = false;
				}
				
				} else {
				$set_id = false;
			}
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			// Если мы получили информаю о товаре (В Корзину добавили существующий товар)
			if ($product_info) {
				
				// Сколько товаров мы добавим
				$quantity = 1;
				if (isset($this->request->post['quantity']) && $this->request->post['quantity'] > 0) {
					$quantity = (int)$this->request->post['quantity'];
					
					if ($product_info['minimum'] > 1 && $this->request->post['quantity'] < $product_info['minimum']){
						$quantity = $product_info['minimum'];
					}
				}

				$do_not_add_main = false;
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
					
					foreach ($option as $key => $value){
						//Надо понять, товар ли это
						if ($this_is_a_product = $this->model_catalog_product->getIfOptionIsProduct($key, $value)){
							$this->cart->add($this_is_a_product['product_id'], $quantity, array(), false, false);
							
							
							$do_not_add_main = $do_not_add_main || true;
						}
					}
					
					} else {
					$option = array();	
				}
				
				
				if ($do_not_add_main){
					
					$product_info_small = $this->model_catalog_product->getProduct($this_is_a_product['product_id']);
					
					$json['total'] = (int)($this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));				
					$json['name'] = $product_info['name'];
					$json['image'] = $this->model_tool_image->resize($product_info['image'], 200, 200);
					$json['model'] = $product_info['model'];
					
					
					$this->response->setOutput(json_encode($json));				
				}
				
				// Кто положил товар в корзину
				if (isset($this->request->post['profile_id'])) {
					$profile_id = $this->request->post['profile_id'];
					} else {
					$profile_id = 0;
				}
				
				$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
				
				foreach ($product_options as $product_option) {
					
					if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
						$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					}
				}
				
				//			
				if ((int)$product_info['minimum'] > $quantity){
					
					if ($quantity == 1){
						$quantity = (int)$product_info['minimum'];
						} else {			
						$json['error']['message'] = 'Минимальное количество товара к заказу - ' . $product_info['minimum'] . ' шт.';
					}
				}

				$profiles = $this->model_catalog_product->getProfiles($product_info['product_id']);
				
				if ($profiles) {
					$profile_ids = array();
					
					foreach ($profiles as $profile) {
						$profile_ids[] = $profile['profile_id'];
					}
					
					if (!in_array($profile_id, $profile_ids)) {
						$json['error']['profile'] = $this->language->get('error_profile_required');
					}
				}
				
				//ФУНКЦИЯ ИЗ БИБЛИОТЕКИ: public function add($product_id, $qty = 1, $option, $profile_id = '', $set_id = false, $ao_id = false)
				
				//Обработка добавления в корзину набора (!). Дима, его нужно написать здесь. Пока что я ставлю 0
				//проверить, существует ли НАБОР с кодом товара product_id и кодом набора set_id
				// $set_id = false;
				
				if (isset($this->request->post['set_id'])) {
					$set_id = (int)$this->request->post['set_id'];
					// Проверяем есть ли действительно такой набор
					$query = $this->db->query("SELECT * FROM `set` WHERE `product_id` = '".(int)$product_id."' AND `set_id` = '".(int)$set_id."'");
					if (!$query->num_rows) {
						$json['error']['message'] = 'Набора не существует. ';
						
					}
				}
				
				
				//Обработка спецпредложения
				//Основной товар добавляется без проблем. Нам нужно добавить все выделенные спецпредложения.
				if (isset($this->request->post['additional_offer'])) {				
					$additional_offer = array_filter($this->request->post['additional_offer']);
					
					foreach ($additional_offer as $key => $value){
						
						$ao_result = $this->model_catalog_product->getProductAdditionalOfferById((int)$key);
						
						//и если существует и такой идентификатор есть, то мы таки добавим спецпредложение
						if (isset($ao_result['product_id']) && $ao_result['product_id'] == $this->request->post['product_id']){
							$this->cart->add($ao_result['ao_product_id'], $quantity * $ao_result['quantity'], array(), '', false, $key);
						}
					}
					
					} else {
					$ao_id = false;
				}
				
				//Проверка наличия единственного подарка у товара
				if (empty($this->request->post['additional_offer'])){
					$additional_offers = $this->model_catalog_product->getProductAdditionalOffer($this->request->post['product_id']);
					
					if (count($additional_offers) == 1 && $additional_offers[0]['percent'] == 100){
						$this->cart->add($additional_offers[0]['ao_product_id'], $quantity * $additional_offers[0]['quantity'], array(), '', false, $additional_offers[0]['product_additional_offer_id']);
					}
					
				}
				
				
				//Добавление товаров - опций
				if (isset($this->request->post['product-option'])) {
					$product_option = array_filter($this->request->post['product-option']);
					} else {
					$product_option = array();	
				}
				
				$product_product_options = $this->model_catalog_product->getProductProductOptions($this->request->post['product_id']);
				
				foreach ($product_product_options as $product_product_option) {
					if ($product_product_option['required'] && empty($product_option[$product_product_option['product_product_option_id']])) {
						$json['error']['product-option'][$product_product_option['product_product_option_id']] = sprintf($this->language->get('error_required'), $product_product_option['name']);
					}
				}
				
				
				// Если нет ошибок, то добавляем товар в корзину
				if (!$json) {				
					$this->cart->add($this->request->post['product_id'], $quantity, $option, $profile_id, $set_id);				
					
					if (isset($this->request->post['product-option'])) {
						$product_options = array_filter($this->request->post['product-option']);
						$pq = array(1);
						if (isset($this->request->post['product-quantity']) && $this->request->post['product-quantity']) {
							$pq = $this->request->post['product-quantity'];
						}
						$product_quantity = array_filter($pq);
						foreach ($product_options as $product_option => $product_id) {
							if (is_array($product_id)) {
								foreach ($product_id as $prod => $prod_id) {
									if (isset($product_quantity[$prod_id])) {
										$this->cart->add($prod_id, $product_quantity[$prod_id], '', $profile_id);
										} else {
										$this->cart->add($prod_id, $quantity, '');
									}
									
								}
								} else {
								if (isset($product_quantity[$product_id])) {
									$this->cart->add($product_id, $product_quantity[$product_id], '', $profile_id);
									} else {
									$this->cart->add($product_id, $quantity, '', $profile_id);
								}
							}
						}
					}
					
					$json['name'] = $product_info['name'];
					$json['quantity'] = $quantity;
					$json['image'] = $this->model_tool_image->resize($product_info['image'], 200, 200);
					$json['model'] = $product_info['model'];
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
					
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					
					// Totals
					$this->load->model('setting/extension');
					
					$total_data = array();					
					$total = 0;
					$taxes = $this->cart->getTaxes();
					
					// Display prices
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$sort_order = array(); 
						
						$results = $this->model_setting_extension->getExtensions('total');
						
						foreach ($results as $key => $value) {
							$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
						}
						
						array_multisort($sort_order, SORT_ASC, $results);
						
						foreach ($results as $result) {
							if ($this->config->get($result['code'] . '_status')) {
								$this->load->model('total/' . $result['code']);
								
								$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
							}
							
							$sort_order = array(); 
							
							foreach ($total_data as $key => $value) {
								$sort_order[$key] = $value['sort_order'];
							}
							
							array_multisort($sort_order, SORT_ASC, $total_data);			
						}
					}
					
					/*	$1json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));		*/
					$json['total'] = (int)($this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
					} else {
					$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
				}
			}
			
			$this->response->setOutput(json_encode($json));		
		}
		
		public function quote() {
			$this->language->load('checkout/cart');
			
			$json = array();	
			
			if (!$this->cart->hasProducts()) {
				$json['error']['warning'] = $this->language->get('error_product');				
			}				
			
			if (!$this->cart->hasShipping()) {
				$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));				
			}				
			
			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}
			
			if (!$json) {		
				$this->tax->setShippingAddress($this->request->post['country_id'], $this->request->post['zone_id']);
				
				// Default Shipping Address
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
				
				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
					} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';	
					$address_format = '';
				}
				
				$this->load->model('localisation/zone');
				
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
				
				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
					} else {
					$zone = '';
					$zone_code = '';
				}	
				
				$address_data = array(
				'firstname'      => '',
				'lastname'       => '',
				'company'        => '',
				'address_1'      => '',
				'address_2'      => '',
				'postcode'       => $this->request->post['postcode'],
				'city'           => '',
				'zone_id'        => $this->request->post['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $this->request->post['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
				);
				
				$quote_data = array();
				
				$this->load->model('setting/extension');
				
				$results = $this->model_setting_extension->getExtensions('shipping');
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('shipping/' . $result['code']);
						
						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
						
						if ($quote) {
							$quote_data[$result['code']] = array( 
							'title'      => $quote['title'],
							'quote'      => $quote['quote'], 
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
							);
						}
					}
				}
				
				$sort_order = array();
				
				foreach ($quote_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
				
				array_multisort($sort_order, SORT_ASC, $quote_data);
				
				$this->session->data['shipping_methods'] = $quote_data;
				
				if ($this->session->data['shipping_methods']) {
					$json['shipping_method'] = $this->session->data['shipping_methods']; 
					} else {
					$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
				}				
			}	
			
			$this->response->setOutput(json_encode($json));						
		}
		
		public function country() {
			$json = array();
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
			
			if ($country_info) {
				$this->load->model('localisation/zone');
				
				$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
				);
			}
			
			$this->response->setOutput(json_encode($json));
		}
	}				
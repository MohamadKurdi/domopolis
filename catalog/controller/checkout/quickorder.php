<?
class ControllerCheckoutQuickorder extends Controller {
	private $error;

	public function index(){						
	}

	public function renderHtml(){			
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

	public function loadtemplate() {
		$this->data['mask'] = $this->config->get('config_phonemask');
		$this->data['country'] = $this->config->get('config_countryname');
		if ($this->customer->isLogged()) {      
			$this->data['email'] = $this->customer->getEmail();
			$this->data['telephone'] = $this->customer->getTelephone();
		} else {      
			$this->data['email'] = '';
			$this->data['telephone'] = '';
		}

		foreach ($this->language->loadRetranslate('checkout/quickorder') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}


		$this->data['config_google_merchant_id'] = $this->config->get('config_google_merchant_id');			

		$key = md5(time());
		$this->data['quickorder_key'] = $key;
		$this->session->data['quickorder_key'] = $key;		

		$this->template = 'checkout/quickorder.tpl';

		$this->response->setOutput($this->render());					
	}

	public function checkoptions() {
		$this->language->load('checkout/cart');

		$json = [];

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = [];	
			}

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

			$profiles = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($profiles) {
				$profile_ids = [];

				foreach ($profiles as $profile) {
					$profile_ids[] = $profile['profile_id'];
				}

				if (!in_array($profile_id, $profile_ids)) {
					$json['error']['profile'] = $this->language->get('error_profile_required');
				}
			}

			if (!$json){
				$json['success'] = true;
			}			
		}

		$this->response->setOutput(json_encode($json));					
	}

	public function createpreorder(){
		$json = [];
		$this->language->load('checkout/cart');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['product_id'])) {
			if ($this->validateWaitListRequest()) {
				$product_id = $this->request->post['product_id'];
				$phone = $this->request->post['waitlist-phone'];					

				if (!$this->error){
					if (isset($this->request->post['product_id'])) {
						$product_id = $this->request->post['product_id'];
					} else {
						$product_id = 0;
					}

					$this->load->model('catalog/product');

					$product_info = $this->model_catalog_product->getProduct($product_id);

					if ($product_info) {
						if (!$this->customer->isLogged()) {
							if (!$this->customer->login($phone, false, true)) {
								$this->load->model('account/customer');

								$email = generateRandomEmail($this->config->get('config_ssl'));

								$this->model_account_customer->addCustomer(array(
									'firstname' 		=> $this->language->get('text_client'),
									'lastname' 			=> $phone,
									'customer_group_id' => $this->config->get('config_customer_group_id'),
									'email' 			=> $email,
									'telephone' 		=> $phone,
									'fax' 				=> '',
									'password' 			=> rand(9999, 999999),
									'company' 			=> '',
									'company_id'		=> 0,
									'tax_id' 			=> 0,
									'address_1' 		=> '',
									'address_2' 		=> '',
									'city' 				=> '',
									'postcode' 			=> '',
									'country_id' 		=> $this->config->get('config_country_id'),
									'zone_id' 			=> '',
									'company' 			=> '',
								));

								$this->customer->login($email, false, true);
							}
						}

						if (isset($this->request->post['quantity'])) {
							$quantity = $this->request->post['quantity'];
						} else {
							$quantity = 1;
						}

						if (isset($this->request->post['option'])) {
							$option = array_filter($this->request->post['option']);
						} else {
							$option = [];
						}

						if (isset($this->request->post['profile_id'])) {
							$profile_id = $this->request->post['profile_id'];
						} else {
							$profile_id = 0;
						}

						$temp_array = $this->session->data['cart'];					
						unset($this->session->data['cart']);
						$this->cart->add($this->request->post['product_id'], $quantity, $option, $profile_id);

						$cart_key = $this->cart->makeCartKey($product_id, $option, $profile_id, false, false);										

						$total_data = [];
						$total = 0;
						$taxes = $this->cart->getTaxes();

						$this->load->model('setting/extension');

						$sort_order = []; 

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
						}

						$sort_order = []; 

						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}

						array_multisort($sort_order, SORT_ASC, $total_data);	

						foreach ($total_data as &$total_line){
							$total_line['text'] = 0;
							$total_line['value'] = 0;
							$total_line['value_national'] = 0;
						}

						$this->language->load('checkout/checkout');

						$data = [];

						$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
						$data['store_id'] 		= $this->config->get('config_store_id');
						$data['store_name'] 	= $this->config->get('config_name');

						if ($data['store_id']) {
							$data['store_url'] = $this->config->get('config_url');		
						} else {
							$data['store_url'] = HTTP_SERVER;	
						}

						if ($this->customer->isLogged()) {
							$data['customer_id'] 		= $this->customer->getId();
							$data['customer_group_id'] 	= $this->customer->getCustomerGroupId();
							$data['firstname'] 			= $this->customer->getFirstName();
							$data['lastname'] 			= $this->customer->getLastName();
							$data['email'] 				= $this->customer->getEmail();
							$data['telephone'] 			= $this->customer->getTelephone();
							$data['fax'] 				= $this->customer->getFax();

							$this->load->model('account/address');

							$payment_address = '';
						} else {
							$data['customer_id'] 	= 0;
							$data['lastname'] 		= '';
							$data['firstname'] 		= $this->language->get('text_client');
							$data['email'] 			= $email;
							$data['fax']  			= '';
							$data['customer_group_id'] = '';

							$payment_address = [];

							$payment_address['lastname'] = '';	
							$payment_address['company'] = '';	
							$payment_address['company_id'] = '';	
							$payment_address['tax_id'] = '';	
							$payment_address['address_1'] = '';
							$payment_address['address_2'] = '';
							$payment_address['city'] = '';
							$payment_address['postcode'] = '';
							$payment_address['zone'] = '';
							$payment_address['zone_id'] = '';
							$payment_address['country'] = '';
							$payment_address['country_id'] = $this->config->get('config_country_id');
							$payment_address['address_format'] = '';					
						}

						$data['telephone'] 	= $phone;

						$data['payment_firstname'] 		= (isset($payment_address['firstname']))?$payment_address['firstname']:'';
						$data['payment_lastname'] 		= (isset($payment_address['lastname']))?$payment_address['lastname']:'';
						$data['payment_company'] 		= (isset($payment_address['company']))?$payment_address['company']:'';	
						$data['payment_company_id'] 	= (isset($payment_address['company_id']))?$payment_address['company_id']:'';	
						$data['payment_tax_id'] 		= (isset($payment_address['tax_id']))?$payment_address['tax_id']:'';
						$data['payment_address_1'] 		= (isset($payment_address['address_1']))?$payment_address['address_1']:'';
						$data['payment_address_2'] 		= (isset($payment_address['address_2']))?$payment_address['address_2']:'';
						$data['payment_city'] 			= (isset($payment_address['city']))?$payment_address['city']:'';
						$data['payment_postcode'] 		= '';
						$data['payment_zone'] 			= (isset($payment_address['zone']))?$payment_address['zone']:'';
						$data['payment_zone_id'] 		= (isset($payment_address['zone_id']))?$payment_address['zone_id']:'';
						$data['payment_country'] 		= (isset($payment_address['country']))?$payment_address['country']:'';	
						$data['payment_country_id'] 	= (isset($payment_address['country_id']))?$payment_address['country_id']:'';	
						$data['payment_address_format'] = (isset($payment_address['address_format']))?$payment_address['address_format']:'';	

						if (isset($this->session->data['payment_method']['title'])) {
							$data['payment_method'] = $this->session->data['payment_method']['title'];
						} else {
							$data['payment_method'] = '';
						}

						if (isset($this->session->data['payment_method']['code'])) {
							$data['payment_code'] = $this->session->data['payment_method']['code'];
						} else {
							$data['payment_code'] = '';
						}

						$data['shipping_firstname'] = '';
						$data['shipping_lastname'] = '';
						$data['shipping_address_1'] = '';
						$data['shipping_address_2'] = '';
						$data['shipping_city'] = '';
						$data['shipping_postcode'] = '';
						$data['shipping_country'] = '';
						$data['shipping_country_id'] = $this->config->get('config_country_id');
						$data['shipping_zone'] = '';
						$data['shipping_zone_id'] = '';
						$data['shipping_address_format'] = '';
						$data['shipping_method'] = '';
						$data['shipping_code'] = '';
						$data['comment'] = '';
						$data['total'] = '';
						$data['total_national'] = '';
						$data['affiliate_id'] = '';
						$data['commission'] = '';
						$data['language_id'] = $this->config->get('config_language_id');
						$data['currency_id'] = '';
						$data['currency_code'] = '';
						$data['currency_value'] = '';
						$data['ip'] = '';
						$data['user_agent'] = '';
						$data['accept_language'] = '';
						$data['products'] = [];
						$data['shipping_company'] = '';
						$data['forwarded_ip'] = '';
						$data['vouchers'] = [];
						$data['totals'] = [];
						$data['supplied'] = [];
						$data['preorder'] = true;
						$data['customer_group_id'] = $this->config->get('config_customer_group_id');

						$shipping_address = [];

						if ($this->cart->hasShipping()) {
							if ($this->customer->isLogged()) {
								$this->load->model('account/address');
							} else {
								$shipping_address['lastname'] = '';	
								$shipping_address['company'] = '';	
								$shipping_address['address_1'] = '';
								$shipping_address['address_2'] = '';
								$shipping_address['city'] = '';
								$shipping_address['postcode'] = '';
								$shipping_address['zone'] = '';
								$shipping_address['zone_id'] = '';
								$shipping_address['country'] = '';
								$shipping_address['country_id'] = $this->config->get('config_country_id');
								$shipping_address['address_format'] = '';						
							}

							$data['shipping_lastname'] 			= (isset($shipping_address['lastname']))?$shipping_address['lastname']:'';	
							$data['shipping_company'] 			= (isset($shipping_address['company']))?$shipping_address['company']:'';
							$data['shipping_address_1'] 		= (isset($shipping_address['address_1']))?$shipping_address['address_1']:'';
							$data['shipping_address_2'] 		= (isset($shipping_address['address_2']))?$shipping_address['address_2']:'';
							$data['shipping_city'] 				= (isset($shipping_address['city']))?$shipping_address['city']:'';
							$data['shipping_postcode'] 			= (isset($shipping_address['postcode']))?$shipping_address['postcode']:'';
							$data['shipping_zone'] 				= (isset($shipping_address['zone']))?$shipping_address['zone']:'';
							$data['shipping_zone_id'] 			= (isset($shipping_address['zone_id']))?$shipping_address['zone_id']:'';
							$data['shipping_country'] 			= (isset($shipping_address['country']))?$shipping_address['country']:'';
							$data['shipping_country_id'] 		= (isset($shipping_address['country_id']))?$shipping_address['country_id']:'';
							$data['shipping_address_format'] 	= (isset($shipping_address['address_format']))?$shipping_address['address_format']:'';

							if (isset($this->session->data['shipping_method']['title'])) {
								$data['shipping_method'] = $this->session->data['shipping_method']['title'];
							} else {
								$data['shipping_method'] = '';
							}

							if (isset($this->session->data['shipping_method']['code'])) {
								$data['shipping_code'] = $this->session->data['shipping_method']['code'];
							} else {
								$data['shipping_code'] = '';
							}				
						}

						$product_data = [];

						foreach ($this->cart->getProducts() as $product) {
							$option_data = [];

							foreach ($product['option'] as $option) {
								if ($option['type'] != 'file') {
									$value = $option['option_value'];	
								} else {
									$value = $this->encryption->decrypt($option['option_value']);
								}	

								$option_data[] = array(
									'product_option_id'       => $option['product_option_id'],
									'product_option_value_id' => $option['product_option_value_id'],
									'option_id'               => $option['option_id'],
									'option_value_id'         => $option['option_value_id'],								   
									'name'                    => $option['name'],
									'value'                   => $value,
									'type'                    => $option['type']
								);					
							}

							$price_national = 0;

							$product_data[] = array(
								'product_id'        => $product['product_id'],
								'name'              => $product['name'],
								'model'             => $product['model'],
								'option'            => $option_data,
								'download'          => $product['download'],
								'quantity'          => $product['quantity'],
								'subtract'          => $product['subtract'],
								'cost'              => 0,
								'price'             => 0,
								'total'             => 0,
								'tax'               => 0,
								'reward'            => $product['reward'],
								'ao_id'             => 0,
								'price_national'    => 0,
								'total_national'    => 0,
								'set'               => 0
							); 
						}

						$voucher_data = [];

						if (!empty($this->session->data['vouchers'])) {
							foreach ($this->session->data['vouchers'] as $voucher) {
								$voucher_data[] = array(
									'description'      => $voucher['description'],
									'code'             => substr(md5(mt_rand()), 0, 10),
									'to_name'          => $voucher['to_name'],
									'to_email'         => $voucher['to_email'],
									'from_name'        => $voucher['from_name'],
									'from_email'       => $voucher['from_email'],
									'voucher_theme_id' => $voucher['voucher_theme_id'],
									'message'          => $voucher['message'],						
									'amount'           => $voucher['amount']
								);
							}
						}  

						$data['products'] 	= $product_data;
						$data['vouchers'] 	= $voucher_data;
						$data['totals'] 	= $total_data;
						$data['comment'] 	= isset($this->session->data['comment'])?$this->session->data['comment']:'';
						$data['total'] 		= 0;

						if (isset($this->request->cookie['tracking'])) {
							$this->load->model('affiliate/affiliate');

							$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
							$subtotal = $this->cart->getSubTotal();

							if ($affiliate_info) {
								$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
								$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
							} else {
								$data['affiliate_id'] = 0;
								$data['commission'] = 0;
							}
						} else {
							$data['affiliate_id'] = 0;
							$data['commission'] = 0;
						}

						$data['language_id'] 	= $this->config->get('config_language_id');
						$data['currency_id'] 	= $this->currency->getId();
						$data['currency_code'] 	= $this->currency->getCode();
						$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
						$data['ip'] 			= $this->request->server['REMOTE_ADDR'];

						if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
							$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
						} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
							$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
						} else {
							$data['forwarded_ip'] = '';
						}

						if (!isset($this->request->cookie['first_referrer'])) {
							$data['first_referrer'] = 'Not set';
						} else {
							$data['first_referrer'] = $this->request->cookie['first_referrer'];
						}

						if (!isset($this->request->cookie['last_referrer'])) {
							$data['last_referrer'] = 'Not set';
						} else {
							$data['last_referrer'] = $this->request->cookie['last_referrer'];
						}

						if (isset($this->request->server['HTTP_USER_AGENT'])) {
							$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
						} else {
							$data['user_agent'] = '';
						}

						if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
							$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
						} else {
							$data['accept_language'] = '';
						}

						$this->load->model('checkout/order');

						$json['order_id'] = $this->model_checkout_order->addOrder($data);
						$this->model_checkout_order->confirm($json['order_id'], $this->config->get('config_order_status_id'), $this->language->get('text_preorder_comment'), true);

						$this->session->data['order_id'] = $json['order_id'];

						$json['total'] = 0;
						$json['currency'] =  $this->currency->getCode();

						$json['success'] = $json['html'] = "<span>" . $this->language->get('text_preorder_success') . "</span>";
						$this->cart->remove($cart_key);
						$this->session->data['cart'] = $temp_array;

						unset($this->session->data['quickorder_key']);

					} else {
						$json['error']['product_id'] = 'system error';
					}
				}
			}  else {
				$json['error'] = $this->error;
			}

			$this->response->setOutput(json_encode($json));
		}
	}	

	public function createorderfast(){	
		if ($this->config->get('config_disable_fast_orders')){
			return;
		}

		$this->load->model('setting/extension');

		$this->language->load('checkout/cart');	
		$this->language->load('checkout/checkout');

		$json = [];
		if (isset($this->request->post['quickfastorder-phone']) && mb_strlen($this->request->post['quickfastorder-phone'],'UTF-8')>=7 && $this->registry->get('phoneValidator')->validate($this->request->post['quickfastorder-phone'])) {
			$phone = $this->request->post['quickfastorder-phone'];
		} else {
			$json['error']['phone'] = sprintf($this->language->get('error_required'), 'Телефон');
		}

		if (!$this->cart->getProducts()){
			$json['error']['cart'] = 'CART IS EMPTY';
		}

		if (!$json) {				
			if (!$this->customer->isLogged()) {
				if (!$this->customer->login($phone, false, true)) {
					$this->load->model('account/customer');

					$email = generateRandomEmail($this->config->get('config_ssl'));

					$this->model_account_customer->addCustomer(array(
						'firstname' 	=> $this->language->get('text_client'),
						'lastname' 		=> '',
						'email' 		=> $email,
						'telephone' 	=> $phone,
						'fax' 			=> '',
						'password' 		=> rand(9999, 999999),
						'company' 		=> '',
						'company_id' 	=> 0,
						'tax_id' 		=> 0,
						'address_1' 	=> '',
						'address_2' 	=> '',
						'city' 			=> '',
						'postcode' 		=> '',
						'country_id' 	=> $this->config->get('config_country_id'),
						'zone_id' 		=> '',
						'company' 		=> '',
					));
					$this->customer->login($email, false, true);
				}
			}

			$total_data = [];
			$total 		= 0;
			$taxes 		= $this->cart->getTaxes();

			$sort_order = []; 

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
			}

			$sort_order = []; 

			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);						

			$data = [];
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');

			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}

			if ($this->customer->isLogged()) {
				$data['customer_id'] 		= $this->customer->getId();
				$data['customer_group_id'] 	= $this->customer->getCustomerGroupId();
				$data['firstname'] 			= $this->customer->getFirstName();
				$data['lastname'] 			= $this->customer->getLastName();
				$data['email'] 				= $this->customer->getEmail();
				$data['telephone'] 			= $this->customer->getTelephone();
				$data['fax'] 				= $this->customer->getFax();

				$this->load->model('account/address');
				$payment_address = '';
			} else {
				$data['customer_id'] = 0;
				$data['lastname'] = '';					
				$data['firstname'] = $this->language->get('text_client');
				$data['email'] = $phone;
				$data['fax']  = '';
				$data['customer_group_id'] = '';

				$payment_address = [];

				$payment_address['lastname'] = '';	
				$payment_address['company'] = '';	
				$payment_address['company_id'] = '';	
				$payment_address['tax_id'] = '';	
				$payment_address['address_1'] = '';
				$payment_address['address_2'] = '';
				$payment_address['city'] = '';
				$payment_address['postcode'] = '';
				$payment_address['zone'] = '';
				$payment_address['zone_id'] = '';
				$payment_address['country'] = '';
				$payment_address['country_id'] = '';
				$payment_address['address_format'] = '';					
			}

			$data['telephone'] = $phone;

			$data['payment_firstname'] 		= '';
			$data['payment_lastname'] 		= (isset($payment_address['lastname']))?$payment_address['lastname']:'';
			$data['payment_company'] 		= (isset($payment_address['company']))?$payment_address['company']:'';	
			$data['payment_company_id'] 	= (isset($payment_address['company_id']))?$payment_address['company_id']:'';	
			$data['payment_tax_id'] 		= (isset($payment_address['tax_id']))?$payment_address['tax_id']:'';
			$data['payment_address_1'] 		= (isset($payment_address['address_1']))?$payment_address['address_1']:'';
			$data['payment_address_2'] 		= (isset($payment_address['address_2']))?$payment_address['address_2']:'';
			$data['payment_city'] 			= (isset($payment_address['city']))?$payment_address['city']:'';			
			$data['payment_postcode'] 		= '';
			$data['payment_zone'] 			= (isset($payment_address['zone']))?$payment_address['zone']:'';
			$data['payment_zone_id'] 		= (isset($payment_address['zone_id']))?$payment_address['zone_id']:'';
			$data['payment_country'] 		= (isset($payment_address['country']))?$payment_address['country']:'';	
			$data['payment_country_id'] 	= (isset($payment_address['country_id']))?$payment_address['country_id']:'';	
			$data['payment_address_format'] = (isset($payment_address['address_format']))?$payment_address['address_format']:'';	

			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$data['payment_code'] = '';
			}

			$data['payment_secondary_method'] 	= '';
			$data['payment_secondary_code'] 	= '';
			$data['postcode'] 					= '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] 	= '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] 		= '';
			$data['shipping_postcode'] 	= '';
			$data['shipping_country'] 	= '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone'] 		= '';
			$data['shipping_zone_id'] 	= '';
			$data['shipping_address_format'] = '';
			$data['shipping_method'] 	= '';
			$data['shipping_code'] 		= '';				
			$data['comment'] 			= '';
			$data['total'] 				= '';
			$data['total_national'] 	= '';
			$data['affiliate_id'] 		= '';
			$data['commission'] 		= '';
			$data['language_id'] 		= $this->config->get('config_language_id');
			$data['currency_id'] 		= '';
			$data['currency_code'] 		= '';
			$data['currency_value'] 	= '';
			$data['ip'] 				= '';
			$data['user_agent'] 		= '';
			$data['accept_language'] 	= '';
			$data['products'] 			= [];
			$data['shipping_company'] 	= '';
			$data['forwarded_ip'] 		= '';
			$data['vouchers'] 			= [];
			$data['totals'] 			= [];
			$data['supplied'] 			= [];

			$shipping_address = [];

			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');
				} else {
					$shipping_address['lastname']		= '';	
					$shipping_address['company'] 		= '';	
					$shipping_address['address_1'] 		= '';
					$shipping_address['address_2'] 		= '';
					$shipping_address['city'] 			= '';
					$shipping_address['postcode'] 		= '';
					$shipping_address['zone'] 			= '';
					$shipping_address['zone_id'] 		= '';
					$shipping_address['country'] 		= '';
					$shipping_address['country_id'] 	= '';
					$shipping_address['address_format'] = '';						
				}

				$data['shipping_lastname'] 		= (isset($shipping_address['lastname']))?$shipping_address['lastname']:'';	
				$data['shipping_company'] 		= (isset($shipping_address['company']))?$shipping_address['company']:'';
				$data['shipping_address_1'] 	= (isset($shipping_address['address_1']))?$shipping_address['address_1']:'';
				$data['shipping_address_2'] 	= (isset($shipping_address['address_2']))?$shipping_address['address_2']:'';
				$data['shipping_city'] 			= (isset($shipping_address['city']))?$shipping_address['city']:'';
				$data['shipping_postcode'] 		= (isset($shipping_address['postcode']))?$shipping_address['postcode']:'';
				$data['shipping_zone'] 			= (isset($shipping_address['zone']))?$shipping_address['zone']:'';
				$data['shipping_zone_id'] 		= (isset($shipping_address['zone_id']))?$shipping_address['zone_id']:'';
				$data['shipping_country'] 		=(isset($shipping_address['country']))?$shipping_address['country']:'';
				$data['shipping_country_id'] 	= (isset($shipping_address['country_id']))?$shipping_address['country_id']:'';
				$data['shipping_address_format'] = (isset($shipping_address['address_format']))?$shipping_address['address_format']:'';

				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$data['shipping_code'] = '';
				}				
			}

			$product_data = [];
			foreach ($this->cart->getProducts() as $product) {
				$option_data = [];

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$value = $this->encryption->decrypt($option['option_value']);
					}	

					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],								   
						'name'                    => $option['name'],
						'value'                   => $value,
						'type'                    => $option['type']
					);					
				}

				$price_national = $this->currency->convert($product['price'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));

				$product_data[] = array(
					'product_id'        => $product['product_id'],
					'name'              => $product['name'],
					'model'             => $product['model'],
					'option'            => $option_data,
					'download'          => $product['download'],
					'quantity'          => $product['quantity'],
					'subtract'          => $product['subtract'],
					'cost'              => isset($product['cost']) ? $product['cost'] : false,
					'price'             => $product['price'],
					'total'             => $product['total'],
					'tax'               => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'            => $product['reward'],
					'ao_id'             => 0,
					'ao_product_id'		=> 0,
					'from_stock'		=> false,
					'price_national'    => $price_national,
					'total_national'    => $price_national * $product['quantity'],
					'set'               => 0
				); 
			}

			$voucher_data = [];

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],						
						'amount'           => $voucher['amount']
					);
				}
			}  

			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $total_data;
			$data['comment'] = isset($this->session->data['comment'])?$this->session->data['comment']:'';
			$data['total'] = $total;
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');

			if (isset($this->request->cookie['tracking'])) {
				$this->load->model('affiliate/affiliate');

				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
				$subtotal = $this->cart->getSubTotal();

				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}

			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
			} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
			} else {
				$data['forwarded_ip'] = '';
			}

			if (!isset($this->request->cookie['first_referrer'])) {
				$data['first_referrer'] = 'Not set';
			} else {
				$data['first_referrer'] = $this->request->cookie['first_referrer'];
			}

			if (!isset($this->request->cookie['last_referrer'])) {
				$data['last_referrer'] = 'Not set';
			} else {
				$data['last_referrer'] = $this->request->cookie['last_referrer'];
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
			} else {
				$data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
			} else {
				$data['accept_language'] = '';
			}

			$this->load->model('checkout/order');

			$json['order_id'] = $this->model_checkout_order->addOrder($data);
			$this->model_checkout_order->confirm($json['order_id'], $this->config->get('config_order_status_id'), $this->language->get('text_fastorder'), true);

			$this->session->data['order_id'] = $json['order_id'];

			$json['total'] = $this->currency->format($data['total'], '', '', false);
			$json['currency'] =  $this->currency->getCode();

			$json['success'] = 'true';
			$json['redirect'] = $this->url->link('checkout/success');
			$this->response->setOutput(json_encode($json));	
		} else {
			$this->response->setOutput(json_encode($json));	
		}			
	}

	public function createorder() {
		if ($this->config->get('config_disable_fast_orders')){
			return;
		}
		
		$json = [];
		$this->language->load('checkout/cart');		
		$this->language->load('checkout/checkout');

		$this->load->model('catalog/product');
		$this->load->model('setting/extension');


		if (!empty($this->request->post['quickorder-dialog-phone']) && $this->phoneValidator->validate($this->request->post['quickorder-dialog-phone'])) {
			$phone = $this->phoneValidator->format($this->request->post['quickorder-dialog-phone']);
		} else {
			$json['error']['phone'] = $this->language->get('error_telephone');
		}

		if (!$json) {	
			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
			} else {
				$product_id = 0;
			}

			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				if (!$this->customer->isLogged()) {
					if (!$this->customer->login($phone, false, true)) {
						$this->load->model('account/customer');

						$email = generateRandomEmail($this->config->get('config_ssl'));

						$this->model_account_customer->addCustomer(array(
							'firstname' 	=> $this->language->get('text_client'),
							'lastname' 		=> $this->language->get('text_fastorder_default'),
							'email' 		=> $email,
							'telephone' 	=> $phone,
							'fax' 			=> '',
							'password' 		=> rand(9999, 999999),
							'company' 		=> '',
							'company_id' 	=> 0,
							'tax_id' 		=> 0,
							'address_1' 	=> '',
							'address_2' 	=> '',
							'city' 			=> '',
							'postcode' 		=> '',
							'country_id' 	=> $this->config->get('config_country_id'),
							'zone_id' 		=> '',
							'company' 		=> '',
						));
						$this->customer->login($email, false, true);
					}
				}

				if (isset($this->request->post['quantity'])) {
					$quantity = $this->request->post['quantity'];
				} else {
					$quantity = 1;
				}

				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
				} else {
					$option = [];
				}

				if (isset($this->request->post['profile_id'])) {
					$profile_id = $this->request->post['profile_id'];
				} else {
					$profile_id = 0;
				}

				$temp_array = $this->session->data['cart'];					
				unset($this->session->data['cart']);
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $profile_id);					
				$cart_key = $this->cart->makeCartKey($product_id, $option, $profile_id, false, false);																																					

				$total_data = [];
				$total = 0;
				$taxes = $this->cart->getTaxes();
			
				$sort_order = []; 
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
				}

				$sort_order = []; 

				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);			
			
				$data = [];

				$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
				$data['store_id'] 		= $this->config->get('config_store_id');
				$data['store_name'] 	= $this->config->get('config_name');

				if ($data['store_id']) {
					$data['store_url'] = $this->config->get('config_url');		
				} else {
					$data['store_url'] = HTTP_SERVER;	
				}

				if ($this->customer->isLogged()) {
					$data['customer_id'] 		= $this->customer->getId();
					$data['customer_group_id'] 	= $this->customer->getCustomerGroupId();
					$data['firstname'] 			= $this->customer->getFirstName();
					$data['lastname'] 			= $this->customer->getLastName();
					$data['email'] 				= $this->customer->getEmail();
					$data['telephone'] 			= $this->customer->getTelephone();
					$data['fax'] 				= $this->customer->getFax();

					$this->load->model('account/address');

						// $payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
					$payment_address = '';
				} else {
					$data['customer_id'] = 0;
					$data['lastname'] = '';
						// TODO
					$data['firstname'] 	= $this->language->get('text_client');
					$data['email'] 		= $email;
					$data['fax']  		= '';
					$data['customer_group_id'] = '';

					$payment_address = [];

					$payment_address['lastname'] = '';	
					$payment_address['company'] = '';	
					$payment_address['company_id'] = '';	
					$payment_address['tax_id'] = '';	
					$payment_address['address_1'] = '';
					$payment_address['address_2'] = '';
					$payment_address['city'] = '';
					$payment_address['postcode'] = '';
					$payment_address['zone'] = '';
					$payment_address['zone_id'] = '';
					$payment_address['country'] = '';
					$payment_address['country_id'] = '';
					$payment_address['address_format'] = '';					
				}

				$data['telephone'] 	= $phone;

					//$data['payment_firstname'] = $this->request->post['quickorder-dialog-name'];
				$data['payment_firstname'] 	= '';
				$data['payment_lastname'] 	= (isset($payment_address['lastname']))?$payment_address['lastname']:'';
				$data['payment_company'] 	= (isset($payment_address['company']))?$payment_address['company']:'';	
				$data['payment_company_id'] = (isset($payment_address['company_id']))?$payment_address['company_id']:'';	
				$data['payment_tax_id'] 	= (isset($payment_address['tax_id']))?$payment_address['tax_id']:'';
				$data['payment_address_1'] 	= (isset($payment_address['address_1']))?$payment_address['address_1']:'';
				$data['payment_address_2'] 	= (isset($payment_address['address_2']))?$payment_address['address_2']:'';
				$data['payment_city'] 		= (isset($payment_address['city']))?$payment_address['city']:'';				
				$data['payment_postcode'] 	= '';
				$data['payment_zone'] 		= (isset($payment_address['zone']))?$payment_address['zone']:'';
				$data['payment_zone_id'] 	= (isset($payment_address['zone_id']))?$payment_address['zone_id']:'';
				$data['payment_country'] 	= (isset($payment_address['country']))?$payment_address['country']:'';	
				$data['payment_country_id'] = (isset($payment_address['country_id']))?$payment_address['country_id']:'';	
				$data['payment_address_format'] = (isset($payment_address['address_format']))?$payment_address['address_format']:'';	

				if (isset($this->session->data['payment_method']['title'])) {
					$data['payment_method'] = $this->session->data['payment_method']['title'];
				} else {
					$data['payment_method'] = '';
				}

				if (isset($this->session->data['payment_method']['code'])) {
					$data['payment_code'] = $this->session->data['payment_method']['code'];
				} else {
					$data['payment_code'] = '';
				}

				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
				$data['comment'] = '';
				$data['total'] = '';
				$data['total_national'] = '';
				$data['affiliate_id'] = '';
				$data['commission'] = '';
				$data['language_id'] = $this->config->get('config_language_id');
				$data['currency_id'] = '';
				$data['currency_code'] = '';
				$data['currency_value'] = '';
				$data['ip'] = '';
				$data['user_agent'] = '';
				$data['accept_language'] = '';
				$data['products'] = [];
				$data['shipping_company'] = '';
				$data['forwarded_ip'] = '';
				$data['vouchers'] = [];
				$data['totals'] = [];
				$data['supplied'] = [];

				$shipping_address = [];

				if ($this->cart->hasShipping()) {
					if ($this->customer->isLogged()) {
						$this->load->model('account/address');
					} else {
						$shipping_address['lastname'] = '';	
						$shipping_address['company'] = '';	
						$shipping_address['address_1'] = '';
						$shipping_address['address_2'] = '';
						$shipping_address['city'] = '';
						$shipping_address['postcode'] = '';
						$shipping_address['zone'] = '';
						$shipping_address['zone_id'] = '';
						$shipping_address['country'] = '';
						$shipping_address['country_id'] = '';
						$shipping_address['address_format'] = '';						
					}

					$data['shipping_lastname'] = (isset($shipping_address['lastname']))?$shipping_address['lastname']:'';	
					$data['shipping_company'] = (isset($shipping_address['company']))?$shipping_address['company']:'';
					$data['shipping_address_1'] = (isset($shipping_address['address_1']))?$shipping_address['address_1']:'';
					$data['shipping_address_2'] = (isset($shipping_address['address_2']))?$shipping_address['address_2']:'';
					$data['shipping_city'] = (isset($shipping_address['city']))?$shipping_address['city']:'';
					$data['shipping_postcode'] = (isset($shipping_address['postcode']))?$shipping_address['postcode']:'';
					$data['shipping_zone'] = (isset($shipping_address['zone']))?$shipping_address['zone']:'';
					$data['shipping_zone_id'] = (isset($shipping_address['zone_id']))?$shipping_address['zone_id']:'';
					$data['shipping_country'] =(isset($shipping_address['country']))?$shipping_address['country']:'';
					$data['shipping_country_id'] = (isset($shipping_address['country_id']))?$shipping_address['country_id']:'';
					$data['shipping_address_format'] = (isset($shipping_address['address_format']))?$shipping_address['address_format']:'';

					if (isset($this->session->data['shipping_method']['title'])) {
						$data['shipping_method'] = $this->session->data['shipping_method']['title'];
					} else {
						$data['shipping_method'] = '';
					}

					if (isset($this->session->data['shipping_method']['code'])) {
						$data['shipping_code'] = $this->session->data['shipping_method']['code'];
					} else {
						$data['shipping_code'] = '';
					}				
				}

				$product_data = [];

				foreach ($this->cart->getProducts() as $product) {
					$option_data = [];

					foreach ($product['option'] as $option) {
						if ($option['type'] != 'file') {
							$value = $option['option_value'];	
						} else {
							$value = $this->encryption->decrypt($option['option_value']);
						}	

						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],								   
							'name'                    => $option['name'],
							'value'                   => $value,
							'type'                    => $option['type']
						);					
					}

					$price_national = $this->currency->convert($product['price'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));

					$product_data[] = array(
						'product_id'        => $product['product_id'],
						'name'              => $product['name'],
						'model'             => $product['model'],
						'option'            => $option_data,
						'download'          => $product['download'],
						'quantity'          => $product['quantity'],
						'subtract'          => $product['subtract'],
						'cost'              => isset($product['cost']) ? $product['cost'] : false,
						'price'             => $product['price'],
						'total'             => $product['total'],
						'tax'               => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'            => $product['reward'],
						'ao_id'             => 0,
						'price_national'    => $price_national,
						'total_national'    => $price_national * $product['quantity'],
						'set'               => 0
					); 
				}

				$voucher_data = [];

				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$voucher_data[] = array(
							'description'      => $voucher['description'],
							'code'             => substr(md5(mt_rand()), 0, 10),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],						
							'amount'           => $voucher['amount']
						);
					}
				}  

				$data['products'] = $product_data;
				$data['vouchers'] = $voucher_data;
				$data['totals'] = $total_data;
				$data['comment'] = isset($this->session->data['comment'])?$this->session->data['comment']:'';
				$data['total'] = $total;
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');

				if (isset($this->request->cookie['tracking'])) {
					$this->load->model('affiliate/affiliate');

					$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
					$subtotal = $this->cart->getSubTotal();

					if ($affiliate_info) {
						$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
						$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
					} else {
						$data['affiliate_id'] = 0;
						$data['commission'] = 0;
					}
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}

				$data['language_id'] = $this->config->get('config_language_id');
				$data['currency_id'] = $this->currency->getId();
				$data['currency_code'] = $this->currency->getCode();
				$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
				$data['ip'] = $this->request->server['REMOTE_ADDR'];

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
				} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
				} else {
					$data['forwarded_ip'] = '';
				}

				if (!isset($this->request->cookie['first_referrer'])) {
					$data['first_referrer'] = 'Not set';
				} else {
					$data['first_referrer'] = $this->request->cookie['first_referrer'];
				}

				if (!isset($this->request->cookie['last_referrer'])) {
					$data['last_referrer'] = 'Not set';
				} else {
					$data['last_referrer'] = $this->request->cookie['last_referrer'];
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
				} else {
					$data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
				} else {
					$data['accept_language'] = '';
				}

				$this->load->model('checkout/order');
				$this->load->model('localisation/country');

				$json['order_id'] = $this->model_checkout_order->addOrder($data);
				$this->model_checkout_order->confirm($json['order_id'], $this->config->get('config_order_status_id'), $this->language->get('text_fastorder'), true);

				$this->session->data['order_id'] = $json['order_id'];

				$json['total'] = $this->currency->format($data['total'], '', '', false);
				$json['currency'] =  $this->currency->getCode();

				$json['html'] = sprintf($this->language->get('text_quickorder_success'), $json['order_id']);
				$json['success'] = true;

				$order_info = $this->model_checkout_order->getOrder($json['order_id']);
				$products = $this->model_checkout_order->getOrderProducts($json['order_id']);				
				$transactionProducts = [];
				$this->load->model('catalog/product');

				$transactionProducts = [];
				$transactionGTINS = [];
				$hasAllProductsOnStock = false;
				$hasSomeProductsOnStock = false;
				$productsOnStock = 0;

				foreach ($products as $product){
					$realProduct = $this->model_catalog_product->getProduct($product['product_id']);

					$gtin = false;
					if (BarcodeValidator::IsValidEAN13($realProduct['ean']) || BarcodeValidator::IsValidEAN8($realProduct['ean'])){
						$gtin = $realProduct['ean'];
					}

					if ($realProduct['current_in_stock']){
						$productsOnStock++;
					}

					$transactionProduct = array(
						'id'  			=> $product['product_id'],
						'sku' 			=> $realProduct['sku']?$realProduct['sku']:$realProduct['model'],
						'name' 			=> $realProduct['name'],
						'brand' 		=> $realProduct['manufacturer'],
						'category' 		=> $this->model_catalog_product->getGoogleCategoryPath($realProduct['product_id']),
						'price' 		=> $product['price_national'],
						'total' 		=> $product['total_national'],
						'quantity' 		=> $product['quantity'],
					);


					if ($gtin){
						$transactionGTINS[] = array('gtin' => $gtin);
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

				$json['google_ecommerce_info'] = array(
					'transactionId' 			=> $order_info['order_id'],					
					'transactionCurrency'		=> $this->config->get('config_regional_currency'),
					'transactionAffiliation'    => $this->config->get('config_url'),
					'transactionTax'			=> 0.00,
					'transactionTotal'			=> $this->getOrderTotal($order_info['order_id']),
					'transactionShipping'		=> 0.00,	
					'transactionEstimatedDelivery'	=> $transactionEstimatedDelivery,
					'transactionCountryCode'	=> $this->model_localisation_country->getCountryISO2($this->config->get('config_country_id')),
				);

				$json['google_ecommerce_info'] = array_map('prepareEcommString', $json['google_ecommerce_info']);
				$json['google_ecommerce_info']['transactionProducts'] 		= $transactionProducts;
				$json['google_ecommerce_info']['transactionGTINS'] 			= $transactionGTINS;
				$json['google_ecommerce_info']['transactionEmail'] 			= $this->customer->getEmail();
				$json['google_ecommerce_info']['config_google_merchant_id'] = $this->config->get('config_google_merchant_id');	
				$json['google_ecommerce_info']['display_survey'] 			= ($this->config->get('config_google_merchant_id') && $this->customer->getEmail() && filter_var($this->customer->getEmail() , FILTER_VALIDATE_EMAIL) && !strpos($order_info['email'], $_SERVER['HTTP_HOST']));
					
				$this->cart->remove($cart_key);
				$this->session->data['cart'] = $temp_array;

				unset($this->session->data['quickorder_key']);

			} else {
				$json['error']['product_id'] = 'system error';
			}
		}

		$this->response->setOutput(json_encode($json));		
	}

	private function validatePreOrderRequest(){
		$this->language->load('module/callback');
		
		if ((strlen(utf8_decode($this->request->post['waitlist-phone'])) < 3) || (strlen(utf8_decode($this->request->post['waitlist-phone'])) > 32)) {
			$this->error['phone'] = $this->language->get('wrongnumber');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	private function validateWaitListRequest(){
		$this->language->load('module/callback');
		
		if ((strlen(utf8_decode($this->request->post['waitlist-phone'])) < 3) || (strlen(utf8_decode($this->request->post['waitlist-phone'])) > 32)) {
			$this->error['phone'] = $this->language->get('wrongnumber');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}	
}																		
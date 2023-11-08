<?php 
	class ControllerAccountSuccess extends Controller {  
		public function index() {
			if (isset($this->session->data['order_id'])) {
				
				$this->session->data['last_order_id'] = $this->session->data['order_id'];
				$this->load->model('checkout/order');
				
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
				$this->redirect($this->url->link('common/home'));
			}
			
			$this->language->load('account/success');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_success'),
			'href'      => $this->url->link('account/success'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->load->model('account/customer_group');
			
			$customer_group = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
			
			if ($customer_group && !$customer_group['approval']) {
				$this->data['text_message'] = sprintf($this->language->get('text_message'), $this->url->link('information/contact'));
				} else {
				$this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
			}
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->link('common/home');
			
			if ($this->config->get('success_page_redirect_status')) {
				$this->redirect($this->config->get('success_page_redirect_url'));
				} elseif ($this->config->get('success_page_status')) {
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
					'facebook'         => ($this->config->get('success_page_facebook_status')) ? '<div class="fb-like-box" data-href="http' . ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) ? 's' : '') . '://www.facebook.com/' . $this->config->get('success_page_facebook_profile') . '" data-width="' . $this->config->get('success_page_facebook_width') . '" data-height="' . $this->config->get('success_page_facebook_height') . '" border-color="' . $this->config->get('success_page_facebook_border') . '" data-show-faces="true" data-stream="false" data-header="false"></div>' : '',
					'is_logged'        => ($this->customer->isLogged() && isset($is_logged[$this->config->get('config_language_id')])) ? html_entity_decode($is_logged[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8') : '',
					'store_telephone'   => $this->config->get('config_telephone'),
					'store_telephone2'   => $this->config->get('config_telephone2'),
					);
					
					$description = $this->config->get('success_page_description');
					
					if (isset($description[$this->config->get('config_language_id')])) {
						if ($this->config->get('success_page_to_default')) {
							$this->data['text_message'] .= str_replace($find, $replace, html_entity_decode($description[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'));
							} else {
							$this->data['text_message'] = str_replace($find, $replace, html_entity_decode($description[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'));
						}
					}
				}
			}
			
			if ($this->cart->hasProducts()) {
				$this->data['continue'] = $this->url->link('checkout/cart', '');
				} else {
				$this->data['continue'] = $this->url->link('account/account', '');
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
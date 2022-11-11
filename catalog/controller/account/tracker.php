<?php 
	class ControllerAccountTracker extends Controller {
		private $error = array();
		
		public function index(){
			$this->language->load('account/account');
			
			if ($this->customer->isLogged()) {
				$this->redirect($this->url->link('account/order', '', 'SSL'));
			}
			
			$this->document->setTitle('Отследить заказ');
			
			if (($this->request->server['REQUEST_METHOD'] == 'GET') && $order_id = $this->validateTrackerByID()){
				
					$this->info((int)$order_id);

			} elseif (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateTracker()) {
				
				//order parsing
					$this->info((int)$this->request->post['order_id']);
				
				
				} else {
				
				$this->document->setTitle('Отследить заказ');
				
				$this->data['breadcrumbs'] = array();
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => 'Отследить заказ',
				'href'      => $this->url->link('account/tracker', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['heading_title'] = 'Отследить заказ';
				
				if (isset($this->request->post['order_id'])) {
					$this->data['order_id'] = $this->request->post['order_id'];
					} elseif(isset($order_id)) {
					$this->data['order_id'] = $order_id;
					} else {
					$this->data['order_id'] = '';
				}
				
				if (isset($this->request->post['auth'])) {
					$this->data['auth'] = $this->request->post['auth'];
					} else {
					$this->data['auth'] = '';
				}
				
				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
					} else {
					$this->data['error_warning'] = '';
				}
				
				$this->data['action'] = $this->url->link('account/tracker', '', 'SSL');
				
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/tracker_login.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/account/tracker_login.tpl';
					} else {
					$this->template = 'default/template/account/tracker_login.tpl';
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
		
		private function validateTrackerByID(){
		
			if (isset($this->request->get['tracking_id'])){
				
				$query = $this->db->query("SELECT order_id FROM `order` WHERE tracking_id = '" . $this->db->escape($this->request->get['tracking_id']) . "'");
				
				if ($query->num_rows && isset($query->row['order_id'])){
					return $query->row['order_id'];
				}
				
			}
		
			return false;
		}
		
		private function makeOrderLink($order_id){
			
			$key = md5($order_id . 'tracking-code-order');
			
			$this->db->query("UPDATE `order` SET tracking_id = '" . $key . "' WHERE order_id = '" . (int)$order_id . "'");
			
			$url = $this->url->link('account/tracker', 'tracking_id=' . $key);
			
			return $url;
		}
		
		
		private function info($order_id){
			
			$this->language->load('account/order');
			$this->load->model('payment/shoputils_psb');
			$this->load->model('payment/paykeeper');
			$this->load->model('account/transaction');
			$this->load->model('account/order');
			$this->load->model('tool/image');
			$this->load->model('tool/user');
			
			$this->language->load('account/transaction');					
			
			$order_info = $this->model_account_order->getOrder($order_id, true);
			
			if ($order_info) {
				
				
				$this->data['success_permalink'] = 'Вы всегда сможете получить доступ к отслеживанию этого заказа по ссылке: <br />' . $this->makeOrderLink($order_id);
			
				$this->document->setTitle($this->language->get('text_order'));
				
				$this->data['full_order_info'] = $order_info;
				
				$this->document->setTitle('Отследить заказ');
				
				$this->data['breadcrumbs'] = array();
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => 'Отследить заказ',
				'href'      => $this->url->link('account/tracker', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => 'Заказ ' . (int)$order_id,
				'href'      => $this->url->link('account/tracker', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['heading_title'] = 'Отследить заказ ' . (int)$order_id;				
				
				$this->data['text_order_detail'] = $this->language->get('text_order_detail');
				$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
				$this->data['text_order_id'] = $this->language->get('text_order_id');
				$this->data['text_date_added'] = $this->language->get('text_date_added');
				$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
				$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
				$this->data['text_payment_method'] = $this->language->get('text_payment_method');
				$this->data['text_payment_address'] = $this->language->get('text_payment_address');
				$this->data['text_history'] = $this->language->get('text_history');
				$this->data['text_comment'] = $this->language->get('text_comment');
				
				$this->data['column_name'] = $this->language->get('column_name');
				$this->data['column_model'] = $this->language->get('column_model');
				$this->data['column_quantity'] = $this->language->get('column_quantity');
				$this->data['column_price'] = $this->language->get('column_price');
				$this->data['column_total'] = $this->language->get('column_total');
				$this->data['column_action'] = $this->language->get('column_action');
				$this->data['column_date_added'] = $this->language->get('column_date_added');
				$this->data['column_status'] = $this->language->get('column_status');
				$this->data['column_comment'] = $this->language->get('column_comment');
				
				$this->data['button_return'] = $this->language->get('button_return');
				$this->data['button_continue'] = $this->language->get('button_continue');
				$this->data['button_invoice'] = $this->language->get('button_invoice');
				
				if ($order_info['invoice_no']) {
					$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
					} else {
					$this->data['invoice_no'] = '';
				}
				
				$this->data['order_id'] = $order_id;
				$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
				
				$this->data['manager'] = $this->model_tool_user->getRealUserNameByID($order_info['manager_id']);
				$this->data['manager_set'] = $order_info['manager_id'];
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
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
				'{country}'
				);
				
				$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
				);
				
				$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$this->data['pay_equire'] = $order_info['pay_equire'];
				$this->data['payment_method'] = $order_info['payment_method'];
				
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
				'{country}'
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
				'country'   => $order_info['shipping_country']
				);
				
				$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$this->data['shipping_method'] = $order_info['shipping_method'];
				
				$this->data['products'] = array();
				
				$products = $this->model_account_order->getOrderProducts($order_id);	
				
				$this->load->model('kp/info1c');
				$order1c = $this->model_kp_info1c->getOrderTrackerXML($order_id);
							
				$products1c = array();
				
				if (isset($order1c["Документ"])) {
					$order1c = $order1c["Документ"];
					if (isset($order1c['ОбщееСостояниеЗаказа']['Товар']['Наименование'])){
						$_tmp = $order1c['ОбщееСостояниеЗаказа']['Товар'];
						unset($order1c['ОбщееСостояниеЗаказа']['Товар']);
						$order1c['ОбщееСостояниеЗаказа']['Товар'] = array($_tmp);
					}
					$products1c = $order1c['ОбщееСостояниеЗаказа']['Товар'];
				}
				
				$general_tracker_status = array();
				
				if ($order_info['order_status_id'] == $this->config->get('config_treated_status_id')){					
					$general_tracker_status[] = 'first_step';
				}
				
				if (in_array($order_info['order_status_id'], array(2, $this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id'), $this->config->get('config_total_paid_order_status_id')))){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
				}
				
				if ($order1c){				
					$is_on_third_step = false;				
					$is_on_fourth_step = true;
					$is_on_fifth_step = true;
					$is_on_sixth_step = true;
					$is_on_seventh_step = true;
					} else {
					$is_on_third_step = false;				
					$is_on_fourth_step = false;
					$is_on_fifth_step = false;
					$is_on_sixth_step = false;
					$is_on_seventh_step = false;				
				}
				foreach ($products as $product) {
					
					$tracker_status = array();					
					
					foreach ($products1c as $p1c){
						if ($p1c['Код'] == $product['product_id']){
							
							// по заказу полностью	
							//если хоть один товар уже купили - то весь заказ считаем на комплектации
							if (!$is_on_third_step) {
								if ($p1c['ЗаказаноУПоставщика'] > 0 && $p1c['ЗаказаноУПоставщика'] == $product['quantity']){
									$is_on_third_step = true;									
								}
							}
							
							//транзит заказа
							if ($p1c['ВПути'] != $product['quantity']) {
								//а если что-то лежит на С.О, а что-то уже едет, то это не неправда, а все оке
								if (((int)$p1c['ВПути'] + (int)$p1c['ОжидаютОтгрузкиПокупателю']) != (int)$product['quantity']) {
									$is_on_fourth_step = false;
								}
							}
							
							//если все товары лежат на складе в городе - получателе
							if ($p1c['ОжидаютОтгрузкиПокупателю'] != $product['quantity']) {								
								$is_on_fifth_step = false;
							}
							
							
							//все уже доставлено
							if ($p1c['ДоставленоПокупателю'] != $p1c['ЗаказаноПокупателем'] || $p1c['ДоставленоПокупателю']  != $product['quantity']) {								
								$is_on_seventh_step = false;
							}
							
							
							//по товару
							
							//заказан у поставщика
							if ($p1c['ЗаказаноУПоставщика']) {
								$tracker_status[] = 'third_step';
							}
							
							//едет из германии + лежит на
							if ($p1c['ВПути'] > 0 && ($p1c['ВПути'] == $product['quantity'] || (((int)$p1c['ВПути'] + (int)$p1c['ОжидаютОтгрузкиПокупателю']) != (int)$product['quantity']))) {
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
							}
							
							//ждет на складе
							if ($p1c['ОжидаютОтгрузкиПокупателю'] == $product['quantity']) {
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
								$tracker_status[] = 'fifth_step';													
							}
							
							//уже доставлено
							if ($p1c['ДоставленоПокупателю'] == $product['quantity']) {
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
								$tracker_status[] = 'fifth_step';
								$tracker_status[] = 'sixth_step';
								$tracker_status[] = 'seventh_step';
							}
							
							//общий статус заказа
							if ($order_info['order_status_id'] == $this->config->get('config_complete_status_id')){								
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
								$tracker_status[] = 'fifth_step';
								$tracker_status[] = 'sixth_step';
								$tracker_status[] = 'seventh_step';
								$tracker_status[] = 'eighth_step';								
							}
							
						}
					}
					unset($p1c);
					
					if (in_array($order_info['order_status_id'], array($this->config->get('config_cancelled_status_id'), 23, 24))){
						$tracker_status = false;
					}
					
					$option_data = array();
					
					$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);
					
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
							} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);					
					}
					
					if ($product['price_national'] > 0){
						$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');
						} else {
						$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
					}
					
					if ($product['total_national'] > 0){
						$total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');					
						} elseif ($product['price_national'] > 0) {
						$total = $this->currency->format($product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
						} else {
						$total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
					}
					
					
					if ($_image = $this->model_account_order->getProductImage($product['product_id'])) {
						$image = $this->model_tool_image->resize($_image, 80, 80);
						} else {
						$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 80, 80);
					}
					
					$this->data['products'][] = array(
					'name'     => $product['name'],
					'product_id' => $product['product_id'],
					'image'    => $image,
					'model'    => $product['model'],
					'option'   => $option_data,
					'link'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'quantity' => $product['quantity'],
					'price'    => $price,
					'tracker_status'   => $tracker_status,
					'total'    => $total,
					'return'   => $this->url->link('account/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
					);
					
					
				}
				
				//Если рубли	
				if ($order_info['currency_code'] == 'RUB') {
					
					$this->data['shoputils_psb_onpay'] = $this->model_payment_shoputils_psb->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/shoputils_psb/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['paykeeper_onpay'] = $this->model_payment_paykeeper->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/paykeeper/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['order_to_pay'] = $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1);
					//Если другая валюта		
					} elseif ($order_info['currency_code'] != 'UAH') {
					
					$this->data['shoputils_psb_onpay'] = $this->model_payment_shoputils_psb->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/shoputils_psb/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['paykeeper_onpay'] = $this->model_payment_paykeeper->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/paykeeper/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['order_to_pay'] = $this->currency->format($this->currency->convert($order_info['total_national'], $order_info['currency_code'], 'RUB'), 'RUB', 1);				
				}
				//Shoputils.psb 1.5.x Laterpay Extension End
				
				$this->data['currency_code'] = $order_info['currency_code'];
				if ($order_info['currency_code'] == 'KZT') {
					$this->data['my_currency'] = $this->currency->format(10, $order_info['currency_code'], 1);
					$this->data['currency_cource'] = number_format($this->currency->real_convert(10, $order_info['currency_code'], 'RUB'), 2, '.', ''). ' руб.';
					} elseif($order_info['currency_code'] == 'BYN'){
					$this->data['my_currency'] = $this->currency->format(10000, $order_info['currency_code'], 1);
					$this->data['currency_cource'] = number_format($this->currency->real_convert(10000, $order_info['currency_code'], 'RUB'), 2, '.', ''). ' руб.';				
					} else {
					$this->data['my_currency'] = $this->currency->format(1, $order_info['currency_code'], 1);
					$this->data['currency_cource'] = number_format($this->currency->real_convert(1, $order_info['currency_code'], 'RUB'), 2, '.', ''). ' руб.';
				}
				
				// Voucher
				$this->data['vouchers'] = array();
				
				$vouchers = $this->model_account_order->getOrderVouchers($order_id);
				
				foreach ($vouchers as $voucher) {
					$this->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}		
				
				$this->data['totals'] = $this->model_account_order->getOrderTotals($order_id);
				
				foreach ($this->data['totals'] as $total){
					if ($total['code'] == 'transfer_plus_prepayment'){
						
						if ($order_info['currency_code'] == 'RUB') {
							
							$this->data['shoputils_psb_onpay_prepay'] = $this->model_payment_shoputils_psb->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/shoputils_psb/laterprepay', sprintf('prepay=1&order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $total['value_national'], md5($order_info['firstname'].$order_info['lastname'])), 'SSL') : '';
							
							$this->data['paykeeper_onpay_prepay'] = $this->model_payment_paykeeper->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/paykeeper/laterprepay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $total['value_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
							
							$this->data['order_to_pay_prepay'] = $this->currency->format($total['value_national'], $order_info['currency_code']);
							//Если другая валюта		
							} elseif ($order_info['currency_code'] != 'UAH') {
							$this->data['shoputils_psb_onpay_prepay'] = $this->model_payment_shoputils_psb->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/shoputils_psb/laterprepay', sprintf('prepay=1&order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $total['value_national'], md5($order_info['firstname'].$order_info['lastname'])), 'SSL') : '';
							
							$this->data['paykeeper_onpay_prepay'] = $this->model_payment_paykeeper->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/paykeeper/laterprepay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $total['value_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
							
							$this->data['order_to_pay_prepay'] = $this->currency->format($this->currency->convert($total['value_national'], $order_info['currency_code'], 'RUB'), 'RUB', 1);				
						}										
						
					}								
				}
				
				if ($is_on_third_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';						
				}
				
				if ($is_on_fourth_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
				}
				
				if ($is_on_fifth_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
				}				
				
				if ($is_on_seventh_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$general_tracker_status[] = 'seventh_step';
				}
				
				$hstrs = $this->model_account_order->getOrderHistoriesFull($order_id);
				$this->data['is_full_paid'] = false;
				foreach ($hstrs as $hstr) {		
					if ($hstr['order_status_id'] == $this->config->get('config_total_paid_order_status_id')){
						$this->data['is_full_paid'] = true;
						break;
					}
				}
				
				if ($order_info['order_status_id'] == $this->config->get('config_complete_status_id')){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$general_tracker_status[] = 'seventh_step';
					$general_tracker_status[] = 'eighth_step';
					
				}
				
				
				if ($order_info['order_status_id'] == 26){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					
				}
				
				$this->data['is_on_pickpoint'] = (mb_stripos($order_info['shipping_code'], 'pickup_advanced') !== false);
				if ($order_info['order_status_id'] == 25){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$this->data['is_on_pickpoint'] = true;
					
				}				
				
				if (in_array($order_info['order_status_id'], array($this->config->get('config_cancelled_status_id'), 23, 24))){
					$general_tracker_status = false;
				}
				
				$this->data['general_tracker_status'] = $general_tracker_status;
				
				$this->data['comment'] = nl2br($order_info['comment']);
				
				$this->data['histories'] = array();
				
				$results = $this->model_account_order->getOrderHistories($order_id);
				
				foreach ($results as $result) {									
					
					$this->data['histories'][] = array(
					'date_added' => date( $this->language->get('date_format_short'), strtotime($result['date_added']) ),
					'status'     => $result['status'],
					'comment'    => (EmailTemplate::isHTML($result['comment'])) ? html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8') : nl2br($result['comment'], true)
					);
				}
				
				$this->data['continue'] = $this->url->link('account/order', '', 'SSL');
				
				$this->data['button_invoice'] = $this->language->get('button_invoice');
				
				$this->load->model('module/emailtemplate');
				
				# Extension settings - load main template
				$config = $this->model_module_emailtemplate->getConfig(array(
				'store_id' 	  => $order_info['store_id'],
				'language_id' => $order_info['language_id']
				), true, true);
				
				if ($config['invoice_download'] && $this->config->get('config_complete_status_id') == $order_info['order_status_id']) {
					$this->data['download_invoice'] = $this->url->link('account/order/invoice', 'order_id='.$order_id, 'SSL');
				}
				
				//payments: liqpay
				if ($order_info['order_status_id'] == $this->config->get('config_confirmed_order_status_id')){
					$this->data['liqpay_payment_form'] = $this->getChild('payment/liqpay');
					} else {
					$this->data['liqpay_payment_form'] = false;
				}
				//$this->data['robokassa_payment_form'] = $this->getChild('payment/robokassa');
				
				//ttns
				$results = $this->model_account_order->getOrderTtnHistory($order_id);
				
				$this->data['ttns'] = array();
				foreach ($results as $result) {
					
					$this->data['ttns'][] = array(
					'order_ttn_id' => $result['order_ttn_id'],
					'delivery_company' => $this->getDeliveryCompany($result['delivery_code']),
					'delivery_code' => $result['delivery_code'],
					'date_ttn' => date('d.m.Y', strtotime($result['date_ttn'])),
					'ttn'    => $result['ttn'],				
					);
					
				}
				
				$this->data['column_date_added'] = $this->language->get('column_date_added');
				$this->data['column_description'] = $this->language->get('column_description');
				$this->data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_regional_currency'));
				
				$this->data['transactions'] = array();
				
				$data = array(				  
				'sort'  => 'date_added',
				'order_id' => $order_id,
				'order' => 'DESC',			
				);
				
				$results = $this->model_account_transaction->getTransactions($data);
				
			
				foreach ($results as $result) {
					
					$_ofd = '';
					if ($result['paykeeper_id'] && $result['f3_id']){
						
					}
					
					
					$this->data['transactions'][] = array(
					'amount'      => $this->currency->format($result['amount_national'], $this->config->get('config_regional_currency'), 1),
					'description' => $result['description'],
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'ofd_cheque'  => $_ofd
					);
				}	
				
				$this->data['transactions'] = array();
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/order_info.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/account/order_info.tpl';
					} else {
					$this->template = 'default/template/account/order_info.tpl';
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
				
				} else {
				
				$this->document->setTitle('Отследить заказ');
				
				$this->data['breadcrumbs'] = array();
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => 'Отследить заказ',
				'href'      => $this->url->link('account/tracker', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['heading_title'] = 'Отследить заказ';
				
				if (isset($this->request->post['order_id'])) {
					$this->data['order_id'] = $this->request->post['order_id'];
					} elseif(isset($order_id)) {
					$this->data['order_id'] = $order_id;
					} else {
					$this->data['order_id'] = '';
				}
				
				if (isset($this->request->post['auth'])) {
					$this->data['auth'] = $this->request->post['auth'];
					} else {
					$this->data['auth'] = '';
				}
				
				$this->data['error_warning'] = 'Что-то пошло не так. Скорее всего мы не смогли найти ваш заказ в трек-системе. Он появится там после обработки и подтверждения.';
				
				$this->data['action'] = $this->url->link('account/tracker', '', 'SSL');
				
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/tracker_login.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/account/tracker_login.tpl';
					} else {
					$this->template = 'default/template/account/tracker_login.tpl';
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
		
		
		private function validateTracker(){
			$this->load->model('account/order');
			$this->load->model('account/customer');
			
			//check order
			if (empty($this->request->post['order_id']) || empty($this->request->post['auth'])){
				$this->error['warning'] = 'Пожалуйста, заполните оба поля для проверки.';
				} else {
				
				$order = $this->model_account_order->getOrder($this->request->post['order_id'], true);
				
				if (!$order){
					
					$this->error['warning'] = 'К сожалению, такого заказа не существует';					
					
					} else {
					
					
					
					$logged = false;					
					//validate auth
					
					if ($order['email'] && mb_strlen($order['email']) > 4 && strpos($order['email'], '@') !== false && trim($order['email']) == trim($this->request->post['auth'])){
						$logged = true;
					}			
					
					//phone
					if (!$logged && $order['telephone'] && mb_strlen($order['telephone']) > 5 && trim(preg_replace('([^0-9])', '', $order['telephone'])) &&
					(trim($order['telephone']) == trim($this->request->post['auth']) || trim(preg_replace('([^0-9])', '', $order['telephone'])) == trim(preg_replace('([^0-9])', '', $this->request->post['auth'])))){
						
						$logged = true;
						
					}
					
					//fax
					if (!$logged && $order['fax'] && mb_strlen($order['fax']) > 5 && trim(preg_replace('([^0-9])', '', $order['fax'])) &&
					(trim($order['fax']) == trim($this->request->post['auth']) || trim(preg_replace('([^0-9])', '', $order['fax'])) == trim(preg_replace('([^0-9])', '', $this->request->post['auth'])))){
						
						$logged = true;
						
					}
					
					//dk
					if (!$logged){
						$customer = $this->model_account_customer->getCustomer($order['customer_id']);
						$discount_card = $customer['discount_card'];
						
						if (!$logged && $discount_card && mb_strlen($discount_card) > 5 && trim(preg_replace('([^0-9])', '', $discount_card)) &&
						(trim($discount_card) == trim($this->request->post['auth']) || trim(preg_replace('([^0-9])', '', $discount_card)) == trim(preg_replace('([^0-9])', '', $this->request->post['auth'])))){
							
							$logged = true;
							
						}
						
					}
					
					
					if (!$logged){
						$this->error['warning'] = 'К сожалению, авторизационные данные не совпадают.';
					}
				}			
				
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
			
		}
		
	}													
<?php
	class ControllerSaleOrder extends Controller {
		private $error 				= [];								
		
		public function getCityByIpAddrAjax($ip = false){
			if ($this->config->get('config_ip_api_key')){
				if (!empty($this->request->get['ip'])){
					$ip = $this->request->get['ip'];
				}

				$ipapi 		= new maciejkrol\ipapicom\ipapi ($this->config->get('config_ip_api_key'));
				$ipResult 	= $ipapi->locate($ip);

				$this->response->setOutput(json_encode($ipResult));					
			} else {
				$this->response->setOutput(json_encode(['error' => 'ERROR: IpApi key is not defined!']));	
			}				
		}
			
		public function customAddressFields($data) {
			$this->load->model('tool/simplecustom');
			
			$this->data['simple_action'] = $this->url->link('kp/customer/dadataCustomFields', 'token=' . $this->session->data['token'] . '&set=' . $data['set'] . '&type=' . $data['type'] . '&id=' . $data['id'], 'SSL');
			
			$this->data['button_save'] = $this->language->get('button_save');
			
			$this->data['custom_' . $data['set']] = $this->model_tool_simplecustom->loadData($data['type'], $data['id'], $data['set']);					
			
			if (!$this->data['custom_' . $data['set']] && $data['type'] == 'order' && $data['dadata_config'] == 'address'){
				$this->model_tool_simplecustom->prepareDaDataEmptyFields($data);
				$this->data['custom_' . $data['set']] = $this->model_tool_simplecustom->loadData($data['type'], $data['id'], $data['set']);
			}
			
			$this->data['custom_type'] = $data['set'];
			$this->data['form_id'] = $data['type'].'_'.$data['set'].'_'.$data['id'];
			
			$this->template = 'module/simple_custom_static.tpl';
			return $this->render();
		}
		
		public function customAddressFieldsOne($data){
			
			if ($data['dadata_config'] == 'address'){
				
				$this->load->model('tool/simplecustom');
				$dataset = $this->model_tool_simplecustom->loadData($data['type'], $data['id'], $data['set']);
				$field = $data['set_short'] . '_custom_' . $data['field'];			
				
				if (!empty($dataset[$field]['value'])){
					return $dataset[$field]['value'];
				}
			}
			
			return false;
		}
		
		private function percentCurrency($order_currency_info, $price){			
			$_price = $price;
			
			if ($order_currency_info['plus_percent'][0] == '+'){
				$_plus = true;
				} elseif ($order_currency_info['plus_percent'][0] == '-') {
				$_plus = false;
				} else {
				$_plus = true;
			}
			
			if ($_plus){
				$_price = $price + ($price/100*(int)$order_currency_info['plus_percent']);				
				} else {
				$_price = $price - ($product['price']/100*(int)$order_currency_info['plus_percent']);	
			}						
			return $_price;			
		}				
		
		private function dateDiff($date1, $date2){
			
			$d = (strtotime($date2)-strtotime($date1))/(60*60*24);
			if (!round($d)){
				$d = 1;
				} else {
				$d = round($d);
			}
			
			return $d;
		}				
				
		public function processTimes($order_id){
			$this->load->model('sale/order');
			$order = $this->model_sale_order->getOrder($order_id);
			$order_histories = $this->model_sale_order->getOrderHistories($order_id, 0, 200);
			
			$date_accepted = false;
			$date_closed = false;
			foreach ($order_histories as $history){
				if (in_array($history['order_status_id'], array($this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id')))){
					$date_accepted = $history['date_added'];
				}
				
				if ($history['order_status_id'] == $this->config->get('config_complete_status_id')){
					$date_closed = $history['date_added'];
				}
				
				if ($history['order_status_id'] == $this->config->get('config_cancelled_status_id')){
					$date_closed = $history['date_added'];
				}
			}
			
			return array(
			'date_added'    => $order['date_added'],
			'date_accepted' => $date_accepted,
			'date_closed'   => $date_closed
			);			
		}		
				
		public function customerNeedSign($shipping_code){
			return (bool_real_stripos($shipping_code, 'pickup_advanced'));
		}
		
		public function getPickupName($shipping_code){
			if (!empty($this->registry->get('shippingmethods')[$shipping_code]) && !empty($this->registry->get('shippingmethods')[$shipping_code]['name'])){
				return $this->registry->get('shippingmethods')[$shipping_code]['name'];
			}

			return false;			
		}
		
		public function getPickupName2($shipping_code){
			if (!empty($this->registry->get('shippingmethods')[$shipping_code]) && !empty($this->registry->get('shippingmethods')[$shipping_code]['address'])){
				return $this->registry->get('shippingmethods')[$shipping_code]['address'];
			}

			return false;				
		}
		
		public function getPickupPhone($shipping_code){
			if (!empty($this->registry->get('shippingmethods')[$shipping_code]) && !empty($this->registry->get('shippingmethods')[$shipping_code]['phone'])){				
				return $this->registry->get('shippingmethods')[$shipping_code]['phone'];
			}

			return false;	
		}				
			
		public function orderHasPrepay($order_id){
			$this->load->model('sale/order');
			$totals = $this->model_sale_order->getOrderTotals($order_id);
			
			$has_prepay = false;
			
			foreach ($totals as $total){
				if (bool_real_stripos($total['title'], 'предоплата') || bool_real_stripos($total['title'], 'передплата') || ($total['code'] == 'cod')){
					$has_prepay = true;
					break;
				}
				
			}
			
			return $has_prepay;
		}
		
		public function getOrderTotalTotal($order_id){
			$this->load->model('sale/order');
			$totals = $this->model_sale_order->getOrderTotals($order_id);
						
			foreach ($totals as $total){
				if ($total['code'] == 'total'){
					return $total['value_national'];
				}
				
			}
			
			return false;
		}
		
		public function getOrderSubTotal($order_id){
			$this->load->model('sale/order');
			$totals = $this->model_sale_order->getOrderTotals($order_id);
			
			foreach ($totals as $total){
				if ($total['code'] == 'sub_total'){
					return $total['value_national'];
				}
				
			}
			
			return false;
		}
				
		public function index() {
			$this->language->load('sale/order');			
			$this->document->setTitle($this->language->get('heading_title'));			
			$this->load->model('sale/order');
			
			$this->getList();
		}
		
		public function if_order_exists(){
			$order_id = (int)$this->request->get['order_id'];
			$this->load->model('sale/order');
			
			if ($this->model_sale_order->getOrder($order_id)){
				$this->response->setOutput($order_id);
				} else {
				$this->response->setOutput(0);
			}		
		}
		
		public function insert() {
			$this->language->load('sale/order');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/order');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_order->addOrder($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_order_id'])) {
					$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
				}
				
				if (isset($this->request->get['filter_customer'])) {
					$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_referrer'])) {
					$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_discount_card'])) {
					$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
				}
				
				if (isset($this->request->get['filter_order_status_id'])) {
					$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
				}
				
				if (isset($this->request->get['filter_total'])) {
					$url .= '&filter_total=' . $this->request->get['filter_total'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_date_added_to'])) {
					$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
				}
				
				if (isset($this->request->get['filter_date_modified'])) {
					$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
				}
				
				if (isset($this->request->get['filter_product_id'])) {
					$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
				}
				
				if (isset($this->request->get['filter_ttn'])) {
					$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
				}
				
				if (isset($this->request->get['filter_courier_status'])) {
					$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
				}
				
				if (isset($this->request->get['filter_shipping_method'])) {
					$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
				}
				
				if (isset($this->request->get['filter_payment_method'])) {
					$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
				}
				
				if (isset($this->request->get['filter_pwa'])) {
					$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
				}

				if (isset($this->request->get['filter_is_credit_order'])) {
					$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
				}
				
				if (isset($this->request->get['filter_yam'])) {
					$url .= '&filter_yam=' . $this->request->get['filter_yam'];
				}
				
				if (isset($this->request->get['filter_yam_id'])) {
					$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
				}
				
				if (isset($this->request->get['filter_urgent'])) {
					$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
				}
				
				
				if (isset($this->request->get['filter_preorder'])) {
					$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
				}
				
				if (isset($this->request->get['filter_urgent_buy'])) {
					$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
				}
				
				if (isset($this->request->get['filter_wait_full'])) {
					$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
				}
				
				if (isset($this->request->get['filter_ua_logistics'])) {
					$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
				}

				if (isset($this->request->get['filter_amazon_offers_type'])) {
					$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			
			$this->language->load('sale/order');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/order');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				
				if (isset($this->request->post)){
					//	$this->model_sale_order->separateOrder((int)$this->request->get['order_id'], $this->request->post);
				}
				
				if ($this->model_sale_order->getIfOrderClosed((int)$this->request->get['order_id'])){
					$this->session->data['error_error'] = 'Заказ закрыт, редактирование невозможно!';
					} else {				
					$this->model_sale_order->editOrder((int)$this->request->get['order_id'], $this->request->post);	
					
					//считаем количество поставок, на которые разделен заказ
					$_count_deliveries = 1;
					foreach ($this->request->post['order_product'] as $_op){
						$_count_deliveries = max($_count_deliveries, $_op['delivery_num']);				
					}
					
					
					$_histories = $this->model_sale_order->getOrderHistories((int)$this->request->get['order_id']);
					$_dcount = 0;
					foreach ($_histories as $_h){				
						if ($_h['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
							$_dcount++;
						}
					}
					
					
					if ($_dcount > 0){
						//количество поставок, которое осталось
						$deliveries_left = $_count_deliveries - $_dcount;				
						//Номер поставки - это "полное количество, минус количество, которое осталось - 1"
						$_delivery_num = ($_count_deliveries - ($deliveries_left-1));
						//вдруг много лишних статусов
						if ($_delivery_num > $_count_deliveries){
							$_delivery_num = $_count_deliveries;
						}
						} else {
						$_delivery_num = 1;
					}
					
					if ($_count_deliveries == 1){
						$_delivery_num = 1;
					}
					
					$_part_num = '';
					foreach ($this->request->post['order_product'] as $_op){
						
						if ($_op['delivery_num'] == $_delivery_num){								
							if (mb_strlen($_op['part_num'])>1){
								$_part_num = $_op['part_num'];						
								break;
							}
						}
					}
					
					
					if ($_part_num){
						$_cheque_num = $_part_num . ' - ' . (int)$this->request->get['order_id'];
						} else {
						$_cheque_num = (int)$this->request->get['order_id'];
					}
					
					//prepare for invoice print
					$_data_to_invoice = array(
					
					'order_id'      => (int)$this->request->get['order_id'],
					'cheque_num'    => $_cheque_num,
					'delivery_num'  => $_delivery_num,
					'cheque_type'   => $this->request->post['pay_type'],
					'cheque_date'   => '',
					'cheque_prim'   => false,
					'cheque_return' => false,
					
					);
					
					if ($_delivery_num > 0){
						$this->save_and_print_invoice(true, $_data_to_invoice);
					}
					
					$this->session->data['success'] = $this->language->get('text_success');
					
					$url = '';
					
					if (isset($this->request->get['filter_order_id'])) {
						$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
					}
					
					if (isset($this->request->get['filter_customer'])) {
						$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
					}
					
					if (isset($this->request->get['filter_referrer'])) {
						$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
					}
					
					if (isset($this->request->get['filter_discount_card'])) {
						$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
					}
					
					if (isset($this->request->get['filter_order_status_id'])) {
						$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
					}
					
					if (isset($this->request->get['filter_total'])) {
						$url .= '&filter_total=' . $this->request->get['filter_total'];
					}
					
					if (isset($this->request->get['filter_date_added'])) {
						$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
					}
					
					if (isset($this->request->get['filter_date_added_to'])) {
						$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
					}
					
					if (isset($this->request->get['filter_date_modified'])) {
						$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
					}
					
					if (isset($this->request->get['filter_product_id'])) {
						$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
					}
					
					if (isset($this->request->get['filter_ttn'])) {
						$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
					}
					
					if (isset($this->request->get['filter_courier_status'])) {
						$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
					}
					
					if (isset($this->request->get['filter_shipping_method'])) {
						$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
					}
					
					if (isset($this->request->get['filter_payment_method'])) {
						$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
					}
					
					if (isset($this->request->get['filter_urgent'])) {
						$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
					}
					
					if (isset($this->request->get['filter_pwa'])) {
						$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
					}

					if (isset($this->request->get['filter_is_credit_order'])) {
						$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
					}
					
					if (isset($this->request->get['filter_yam'])) {
						$url .= '&filter_yam=' . $this->request->get['filter_yam'];
					}
					
					if (isset($this->request->get['filter_yam_id'])) {
						$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
					}
					
					if (isset($this->request->get['filter_preorder'])) {
						$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
					}
					
					if (isset($this->request->get['filter_urgent_buy'])) {
						$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
					}
					
					if (isset($this->request->get['filter_wait_full'])) {
						$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
					}
					
					if (isset($this->request->get['filter_ua_logistics'])) {
						$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
					}

					if (isset($this->request->get['filter_amazon_offers_type'])) {
						$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
					}
					
					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}
					
					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}
					
					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}
				}
				
				$this->redirect($this->url->link('sale/order/update', 'order_id='.$this->request->get['order_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->load->model('sale/order');
			if (!is_numeric($this->request->get['order_id']) || !$this->model_sale_order->getOrder($this->request->get['order_id'])){
				$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function neworder() {
			
			$this->language->load('sale/order');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/order');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				
				unset($this->request->post['order_product']);
				$this->request->post['comment'] = '';
				$this->request->post['order_id2'] = '';
				$this->request->post['order_status_id'] = $this->config->get('config_order_status_id');
				$new_order_id = $this->model_sale_order->addOrder($this->request->post);
				
				if ($new_order_id){
					$this->session->data['success'] = "Создан новый заказ: <a href='".$this->url->link('sale/order/update', 'order_id='.$new_order_id.'&token=' . $this->session->data['token'] . $url, 'SSL')."'>" . $new_order_id . "</a>";
					} else {
					$this->session->data['error'] = 'Ошибка!';
				}
				
				$url = '';
				
				if (isset($this->request->get['filter_order_id'])) {
					$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
				}
				
				if (isset($this->request->get['filter_customer'])) {
					$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_referrer'])) {
					$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_discount_card'])) {
					$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
				}
				
				if (isset($this->request->get['filter_order_status_id'])) {
					$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
				}
				
				if (isset($this->request->get['filter_total'])) {
					$url .= '&filter_total=' . $this->request->get['filter_total'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_date_added_to'])) {
					$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
				}
				
				if (isset($this->request->get['filter_date_modified'])) {
					$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
				}
				
				if (isset($this->request->get['filter_product_id'])) {
					$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
				}
				
				if (isset($this->request->get['filter_ttn'])) {
					$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
				}
				
				if (isset($this->request->get['filter_courier_status'])) {
					$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
				}
				
				if (isset($this->request->get['filter_shipping_method'])) {
					$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
				}
				
				if (isset($this->request->get['filter_payment_method'])) {
					$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
				}
				
				if (isset($this->request->get['filter_urgent'])) {
					$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
				}
				
				if (isset($this->request->get['filter_pwa'])) {
					$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
				}

				if (isset($this->request->get['filter_is_credit_order'])) {
					$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
				}
				
				if (isset($this->request->get['filter_yam'])) {
					$url .= '&filter_yam=' . $this->request->get['filter_yam'];
				}
				
				if (isset($this->request->get['filter_yam_id'])) {
					$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
				}
				
				if (isset($this->request->get['filter_preorder'])) {
					$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
				}
				
				if (isset($this->request->get['filter_urgent_buy'])) {
					$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
				}
				
				if (isset($this->request->get['filter_wait_full'])) {
					$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
				}
				
				if (isset($this->request->get['filter_ua_logistics'])) {
					$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
				}

				if (isset($this->request->get['filter_amazon_offers_type'])) {
					$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('sale/order/update', 'order_id='.$this->request->get['order_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
				
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('sale/order');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/order');
			
			if (isset($this->request->post['selected']) && ($this->validateDelete())) {
				foreach ($this->request->post['selected'] as $order_id) {
					
					if ($this->user->getIsAV()){						
						$this->model_sale_order->deleteOrder($order_id);												
					}
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_order_id'])) {
					$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
				}
				
				if (isset($this->request->get['filter_customer'])) {
					$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_referrer'])) {
					$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_discount_card'])) {
					$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
				}
				
				if (isset($this->request->get['filter_order_status_id'])) {
					$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
				}
				
				if (isset($this->request->get['filter_total'])) {
					$url .= '&filter_total=' . $this->request->get['filter_total'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['filter_date_added_to'])) {
					$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
				}
				
				if (isset($this->request->get['filter_date_modified'])) {
					$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
				}
				
				if (isset($this->request->get['filter_product_id'])) {
					$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
				}
				
				if (isset($this->request->get['filter_ttn'])) {
					$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
				}
				
				if (isset($this->request->get['filter_courier_status'])) {
					$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
				}
				
				if (isset($this->request->get['filter_shipping_method'])) {
					$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
				}
				
				if (isset($this->request->get['filter_payment_method'])) {
					$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
				}
				
				if (isset($this->request->get['filter_urgent'])) {
					$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
				}
				
				if (isset($this->request->get['filter_pwa'])) {
					$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
				}

				if (isset($this->request->get['filter_is_credit_order'])) {
					$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
				}
				
				if (isset($this->request->get['filter_yam'])) {
					$url .= '&filter_yam=' . $this->request->get['filter_yam'];
				}
				
				if (isset($this->request->get['filter_yam_id'])) {
					$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
				}
				
				if (isset($this->request->get['filter_preorder'])) {
					$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
				}
				
				if (isset($this->request->get['filter_urgent_buy'])) {
					$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
				}
				
				if (isset($this->request->get['filter_wait_full'])) {
					$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
				}
				
				if (isset($this->request->get['filter_ua_logistics'])) {
					$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
				}

				if (isset($this->request->get['filter_amazon_offers_type'])) {
					$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			$this->load->model('user/user');
			$this->load->model('sale/customer');
			$this->load->model('tool/image');
			$this->load->model('sale/reject_reason');
			$this->load->model('catalog/product');
			$this->load->model('localisation/country');
			$this->load->model('kp/geoip');
			$this->load->model('kp/csi');
			$this->load->model('kp/order');
			$this->load->model('kp/price');
			$this->load->model('sale/affiliate');
			$this->load->model('tool/simplecustom');
			$this->load->model('setting/setting');
			
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
				} else {
				$filter_order_id = null;
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$filter_customer = $this->request->get['filter_customer'];
				} else {
				$filter_customer = null;
			}
			
			if (isset($this->request->get['filter_referrer'])) {
				$filter_referrer = $this->request->get['filter_referrer'];
				} else {
				$filter_referrer = null;
			}
			
			if (isset($this->request->get['filter_discount_card'])) {
				$filter_discount_card = $this->request->get['filter_discount_card'];
				} else {
				$filter_discount_card = null;
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
				} else {
				$filter_order_status_id = null;
			}
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$filter_reject_reason_id = $this->request->get['filter_reject_reason_id'];
				} else {
				$filter_reject_reason_id = null;
			}		
			
			if (isset($this->request->get['filter_order_store_id'])) {
				$filter_order_store_id = $this->request->get['filter_order_store_id'];
				} else {
				$filter_order_store_id = null;
			}
			
			if (isset($this->request->get['filter_total'])) {
				$filter_total = $this->request->get['filter_total'];
				} else {
				$filter_total = null;
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
				} else {
				$filter_date_added = null;
			}
			
			if (isset($this->request->get['filter_date_added_to'])) {
				$filter_date_added_to = $this->request->get['filter_date_added_to'];
				} else {
				$filter_date_added_to = null;
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$filter_date_modified = $this->request->get['filter_date_modified'];
				} else {
				$filter_date_modified = null;
			}
			
			if (isset($this->request->get['filter_date_delivery'])) {
				$filter_date_delivery = $this->request->get['filter_date_delivery'];
				} else {
				$filter_date_delivery = null;
			}
			
			if (isset($this->request->get['filter_manager_id'])) {
				$filter_manager_id = $this->request->get['filter_manager_id'];
				} else {
				$filter_manager_id = null;
			}
			
			if (isset($this->request->get['filter_courier_id'])) {
				$filter_courier_id = $this->request->get['filter_courier_id'];
				} else {
				$filter_courier_id = null;
			}
			
			if (isset($this->request->get['filter_affiliate_id'])) {
				$filter_affiliate_id = $this->request->get['filter_affiliate_id'];
				} else {
				$filter_affiliate_id = null;
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$filter_product_id = $this->request->get['filter_product_id'];			
				$filtered_product = $this->model_catalog_product->getProduct($filter_product_id);
				$this->data['filtered_product'] = array(
				'name' => $filtered_product['name'],
				'product_id' => $filtered_product['product_id'],
				'model' => $filtered_product['model'],
				'ean' => $filtered_product['ean']
				);
				
				} else {
				$filter_product_id = null;
				$this->data['filtered_product'] = array(
				'name' => '',
				'product_id' => '',
				'model' => '',
				'ean' => ''
				);
			}
			
			if (isset($this->request->get['filter_ttn'])) {
				$filter_ttn = $this->request->get['filter_ttn'];
				} else {
				$filter_ttn = null;
			}
			
			if (isset($this->request->get['filter_courier_status'])) {
				$filter_courier_status = $this->request->get['filter_courier_status'];
				} else {
				$filter_courier_status = null;
			}
			
			if (isset($this->request->get['filter_shipping_method'])) {
				$filter_shipping_method = $this->request->get['filter_shipping_method'];
				} else {
				$filter_shipping_method = null;
			}
			
			if (isset($this->request->get['filter_payment_method'])) {
				$filter_payment_method = $this->request->get['filter_payment_method'];
				} else {
				$filter_payment_method = null;
			}
			
			if (isset($this->request->get['filter_urgent'])) {
				$filter_urgent = $this->request->get['filter_urgent'];
				} else {
				$filter_urgent = null;
			}
			
			if (isset($this->request->get['filter_pwa'])) {
				$filter_pwa = $this->request->get['filter_pwa'];
				} else {
				$filter_pwa = null;
			}

			if (isset($this->request->get['filter_is_credit_order'])) {
				$filter_is_credit_order = $this->request->get['filter_is_credit_order'];
				} else {
				$filter_is_credit_order = null;
			}
			
			if (isset($this->request->get['filter_yam'])) {
				$filter_yam = $this->request->get['filter_yam'];
				} else {
				$filter_yam = null;
			}
			
			if (isset($this->request->get['filter_yam_id'])) {
				$filter_yam_id = $this->request->get['filter_yam_id'];
				} else {
				$filter_yam_id = null;
			}
			
			if (isset($this->request->get['filter_preorder'])) {
				$filter_preorder = $this->request->get['filter_preorder'];
				} else {
				$filter_preorder = null;
			}
			
			if (isset($this->request->get['filter_urgent_buy'])) {
				$filter_urgent_buy = $this->request->get['filter_urgent_buy'];
				} else {
				$filter_urgent_buy = null;
			}
			
			if (isset($this->request->get['filter_wait_full'])) {
				$filter_wait_full = $this->request->get['filter_wait_full'];
				} else {
				$filter_wait_full = null;
			}
			
			if (isset($this->request->get['filter_ua_logistics'])) {
				$filter_ua_logistics = $this->request->get['filter_ua_logistics'];
				} else {
				$filter_ua_logistics = null;
			}

			if (isset($this->request->get['filter_amazon_offers_type'])) {
				$filter_amazon_offers_type = $this->request->get['filter_amazon_offers_type'];
				} else {
				$filter_amazon_offers_type = null;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'o.date_added';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_referrer'])) {
				$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_discount_card'])) {
				$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$url .= '&filter_reject_reason_id=' . $this->request->get['filter_reject_reason_id'];
			}						
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_added_to'])) {
				$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
			}
			
			if (isset($this->request->get['filter_manager_id'])) {
				$url .= '&filter_manager_id=' . $this->request->get['filter_manager_id'];
			}
			
			if (isset($this->request->get['filter_courier_id'])) {
				$url .= '&filter_courier_id=' . $this->request->get['filter_courier_id'];
			}
			
			if (isset($this->request->get['filter_affiliate_id'])) {
				$url .= '&filter_affiliate_id=' . $this->request->get['filter_affiliate_id'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
			
			if (isset($this->request->get['filter_date_delivery'])) {
				$url .= '&filter_date_delivery=' . $this->request->get['filter_date_delivery'];
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			
			if (isset($this->request->get['filter_ttn'])) {
				$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
			}
			
			if (isset($this->request->get['filter_courier_status'])) {
				$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
			}
			
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			
			if (isset($this->request->get['filter_payment_method'])) {
				$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
			}
			
			if (isset($this->request->get['filter_urgent'])) {
				$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
			}
			
			if (isset($this->request->get['filter_pwa'])) {
				$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
			}

			if (isset($this->request->get['filter_is_credit_order'])) {
				$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
			}
			
			if (isset($this->request->get['filter_yam'])) {
				$url .= '&filter_yam=' . $this->request->get['filter_yam'];
			}
			
			if (isset($this->request->get['filter_yam_id'])) {
				$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
			}
			
			if (isset($this->request->get['filter_preorder'])) {
				$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
			}
			
			if (isset($this->request->get['filter_urgent_buy'])) {
				$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
			}
			
			if (isset($this->request->get['filter_wait_full'])) {
				$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
			}
			
			if (isset($this->request->get['filter_ua_logistics'])) {
				$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
			}

			if (isset($this->request->get['filter_amazon_offers_type'])) {
					$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			$this->data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['insert'] = $this->url->link('sale/order/insert', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['delete'] = $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			
			$data = array('filter_order_status_id' => 0);
			$fucked_order_total = $this->model_sale_order->getTotalOrders($data);
			$this->data['fucked_order_total'] = $fucked_order_total;
			$this->data['fucked_link'] = $this->url->link('sale/order', 'filter_order_status_id=0&token=' . $this->session->data['token'] . $url, 'SSL');
			
			$this->data['orders'] = [];
			
			if (isset($this->request->get['filter_do_csv']) && ($this->request->get['filter_do_csv'] == 1)){
				$limit = 10000;
				} else {
				$limit = $this->config->get('config_admin_limit');
			}
			
			$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'	     => $filter_customer,
			'filter_discount_card'	 => $filter_discount_card,
			'filter_referrer'	     => $filter_referrer,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_reject_reason_id' => $filter_reject_reason_id,
			'filter_order_store_id'  => $filter_order_store_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_added_to'   => $filter_date_added_to,
			'filter_date_modified'   => $filter_date_modified,
			'filter_date_delivery'   => $filter_date_delivery,
			'filter_manager_id'      => $filter_manager_id,
			'filter_courier_id'      => $filter_courier_id,
			'filter_affiliate_id'    => $filter_affiliate_id,
			'filter_product_id'      => $filter_product_id,
			'filter_ttn'    		 => $filter_ttn,
			'filter_courier_status'  => $filter_courier_status,
			'filter_shipping_method'  => $filter_shipping_method,
			'filter_payment_method'  => $filter_payment_method,
			'filter_urgent'    		 => $filter_urgent,
			'filter_pwa'    		 => $filter_pwa,
			'filter_is_credit_order' => $filter_is_credit_order,
			'filter_yam'    		 => $filter_yam,
			'filter_yam_id'    		 => $filter_yam_id,
			'filter_preorder'    	 => $filter_preorder,
			'filter_urgent_buy'      => $filter_urgent_buy,
			'filter_wait_full'       => $filter_wait_full,
			'filter_ua_logistics'    => $filter_ua_logistics,
			'filter_amazon_offers_type' => $filter_amazon_offers_type,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $limit,
			'limit'                  => $limit
			);
			
			
			$order_total = $this->model_sale_order->getTotalOrders($data);
			$this->data['order_total'] = $order_total;
			
			$results = $this->model_sale_order->getOrders($data);
			
			$result_sums = [];//$this->model_sale_order->getOrdersSums($data);
			$this->data['result_sums'] = [];
			
			foreach ($result_sums as $result_sum){
				$this->data['result_sums'][] = $this->currency->format($result_sum['total'], $result_sum['currency_code'], $result_sum['currency_value']);			
			}
			
			$this->data['courier_statuses'] = $this->model_sale_order->getOrderCourierAllStatuses();
			$this->data['shipping_methods'] = $this->model_sale_order->getOrderCourierAllShippingMethods();			
			$this->data['payment_methods']  = $this->model_sale_order->getOrderCourierAllPaymentMethods();			
			
			$this->load->model('module/referrer');
			
			$this->data['csi_filters'] = $this->model_kp_csi->getCSIOrderStatuses();
			
			$cities = [];
			$countries = $this->model_localisation_country->getCountriesStructuredByID();
			foreach ($results as $result) {
				$action = [];
				
				$action[] = array(
				'text' => '<i class="fa fa-file-excel-o"></i>',
				'href' => $this->url->link('report/export_xls/createProductsInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
				);
				
				if (strtotime($result['date_added']) > strtotime('-' . (int)$this->config->get('config_order_edit') . ' day')) {
					$action[] = array(
					'text' => '<i class="fa fa-edit"></i>',
					'href' => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
					);
				}
				
				if ($this->config->get('config_amazonlist_order_status_id') && in_array($result['order_status_id'], $this->config->get('config_amazonlist_order_status_id'))){
					$action[] = array(
					'text' => '<i class="fa fa-amazon"></i>',
					'target' => '_blank',
					'href' => $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . '&filter_order_id=' . $result['order_id'] . $url, 'SSL')
					);
				}
				
				
				if ($result['manager_id'] > 0) {	
					$manager = array(							
					'name'     => $this->model_user_user->getUserNameById($result['manager_id']), 
					'realname' => $this->model_user_user->getRealUserLastNameById($result['manager_id']), 
					'id'       => $result['manager_id'],
					'url'      => $this->url->link('sale/order/getManagerInfoAjax', 'token=' . $this->session->data['token'] . '&manager_id=' . $result['manager_id'], 'SSL')
					);
					} else {
					$manager = false;
				}
				
				if ($result['courier_id'] > 0) {	
					$courier = array(							
					'name'     => $this->model_user_user->getUserNameById($result['courier_id']), 
					'realname' => $this->model_user_user->getRealUserLastNameById($result['courier_id']), 
					'id'       => $result['courier_id'],
					
					);
					} else {
					$courier = false;
				}
				
				$products = $this->model_sale_order->getOrderProductsList($result['order_id']);
				
				if (!$products && $result['order_status_id'] == $this->config->get('config_cancelled_status_id')){
					$products = $this->model_sale_order->getOrderProductsListNoGood($result['order_id']);
				}
				
				$order_products 	= [];							
				$order_parties_tmp 	= [];
				
				$zero_price_products = [];
				foreach ($products as $product) {
					$option_data = [];
					
					$options = $this->model_sale_order->getOrderOptions($result['order_id'], $product['order_product_id']);
					
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
							);
							} else {
							$option_data[] = array(
							'name'  => $option['name'],
							'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
							'type'  => $option['type'],
							'href'  => $this->url->link('sale/order/download', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . '&order_option_id=' . $option['order_option_id'], 'SSL')
							);						
						}
					}
					
					if (!empty($product['part_num']) && mb_strlen($product['part_num']) > 1 && !in_array($product['part_num'], $order_parties_tmp)){
						$order_parties_tmp[]= $product['part_num'];
					}
					
					if ($product['price'] == 0){
						$zero_price_products[$product['product_id']] = $product['quantity'];
					}
					
					$order_products[] = array(
					'order_product_id' 		=> $product['order_product_id'],
					'order_id'         		=> $result['order_id'],
					'product_id'       		=> $product['product_id'],
					'amazon_offers_type'	=> !empty($product['amazon_offers_type'])?$product['amazon_offers_type']:false,
					'from_stock'       		=> !empty($product['from_stock'])?$product['from_stock']:false,
					'from_bd_gift'     		=> !empty($product['from_bd_gift'])?$product['from_bd_gift']:false,
					'name'    	 	   		=> $product['name'],
					'part_num'		   		=> !empty($product['part_num'])?$product['part_num']:false,
					'lthumb'    	   		=> $this->model_tool_image->resize($product['image'], 150, 150),
					'thumb'    	 	   		=> $this->model_tool_image->resize($product['image'], 25, 25),
					'model'    		  		=> $product['model'],
					'option'   		   		=> $option_data,
					'quantity'		   		=> $product['quantity'],					
					'price'    		   		=> $this->currency->format($product['price_national'], $result['currency_code'], 1),
					'href'     		   		=> $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
					);
				}
				
				$order_parties = [];
				foreach ($order_parties_tmp as $_partie){					
					$order_parties []= array(
					'part_num' => $_partie,
					'href'     => $this->url->link('sale/order', 'filter_order_id=' . $_partie . '&token=' . $this->session->data['token'], 'SSL')				
					);				
				}
				
				if ($result['total_national'] > 0){
					$total = $this->currency->format($result['total_national'], $result['currency_code'],'1');			
					} else {
					$total = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);
				}
				
				
				$histories = $this->model_sale_order->getOrderHistories($result['order_id'], 0, 100);
				$last_comment = '';
				foreach ($histories as $oh){
					if (mb_strlen($oh['comment'], 'UTF-8') > 2) {
						$last_comment =	$oh['comment'];
					}
				}
				
				$guessed_shipping_city = $this->model_kp_geoip->guessCity($result['shipping_city']);
				
				if ($result['shipping_city'] && !array_key_exists($guessed_shipping_city, $cities)){
					if (isset($countries[$result['shipping_country_id']])){
						$_country_iso2 = $countries[$result['shipping_country_id']]['iso_code_2'];
						} else {
						$_country_iso2 = false;
					}
					$cities[$guessed_shipping_city] = $this->model_kp_geoip->getCurrentTimeInCity($guessed_shipping_city, $_country_iso2);
				}
				
				$times = $this->processTimes($result['order_id']);	
				
				$courier_color = false;
				
				if (strpos($result['courier_status'], 'отказ') !== false){
					$courier_color = 'red';
				}
				
				$_fr = $result['first_referrer']?trim(str_replace('www.', '', parse_url($this->model_module_referrer->simple_decode( $result['first_referrer'] ),  PHP_URL_HOST))):'Прямой';
				
				$use_custom_dadata = $this->model_setting_setting->getKeySettingValue('config', 'config_dadata', (int)$result['store_id']);
				$addressOk = true;				
				if ($use_custom_dadata == 'address' && $result['shipping_address_1'] && (!$result['shipping_address_struct'] || !json_decode(html_entity_decode($result['shipping_address_struct'])))){
					$addressOk = false;	
				}
				
				$total_orders = $this->model_sale_order->getTotalOrdersByCustomerId($result['customer_id']);
				$totals = $this->model_sale_order->getOrderTotals($result['order_id']);							
				
				$totals2 = [];
				$total_discount = 0;
				$sub_total = 0;
				foreach ($totals as $tmp_total){
					$tmp_total['value_national_formatted'] = $this->currency->format($tmp_total['value_national'], $result['currency_code'], '1');
					$totals2[] = $tmp_total;
					
					if ($tmp_total['value_national'] < 0){
						$total_discount += $tmp_total['value_national'];
					}
					
					if ($tmp_total['code'] == 'sub_total'){
						$sub_total = $tmp_total['value_national'];
					}
				}
				
				if ($zero_price_products){
					foreach ($zero_price_products as $zero_price_product_id => $zero_price_product_quantity){
						$zero_product_current_price = $this->model_kp_price->getProductResultPriceByStore($zero_price_product_id, $result['store_id']);
						
						if ($zero_product_current_price['special']){
							$zero_product_current_price['price'] = $zero_product_current_price['special'];
						}
						
						$zero_product_current_total = -1 * ($zero_product_current_price['price'] * $zero_price_product_quantity);
						$zero_product_current_total_national = $zero_product_current_total; 
						//Maybe a bug, but not sure
						//$this->currency->convert($zero_product_current_total, $this->config->get('config_currency'), $result['currency_code']);
						
						$total_discount += $zero_product_current_total_national;
						$sub_total += -1 * $zero_product_current_total_national;
						
						array_insert2($totals2, 1, [
						'value_national'			=> $zero_product_current_total_national,
						'value_national_formatted'  => $this->currency->format($zero_product_current_total_national, $result['currency_code'], 1),
						'code'						=> 'additionaloffer'
						]);
					}	
				}	
				
				if ($result['preorder']){
					$totals2 = [];
					$total_discount = false;
				}				
				
				$this->data['orders'][] = array(
				'order_id'      			=> $result['order_id'],
				'pwa'      					=> $result['pwa'],
				'monocheckout'      		=> $result['monocheckout'],
				'yam'      					=> $result['yam'],
				'yam_id'      				=> $result['yam_id'],
				'yam_shipment_date'      	=> $result['yam_shipment_date'],
				'yam_shipment_id'      		=> $result['yam_shipment_id'],
				'yam_box_id'      			=> $result['yam_box_id'],
				'yam_status'              	=> $result['yam_status'],
				'yam_substatus'           	=> $result['yam_substatus'],
				'yam_fake'           	  	=> $result['yam_fake'],
				'amazon_offers_type'           => $result['amazon_offers_type'],
				'ukrcredits_order_status'      => !empty($result['ukrcredits_order_status'])?$result['ukrcredits_order_status']:false,
				'ukrcredits_order_substatus'   => !empty($result['ukrcredits_order_substatus'])?$result['ukrcredits_order_substatus']:false,
				'customer'      			=> $result['customer'],
				'email'      				=> $result['email'],
				'email_bad'					=> !$this->emailBlackList->check($result['email']),
				'template'      			=> $result['template'],
				'general_tracker_status' 	=> ($result['order_status_id'] == 2 && $result['tracker_xml'])?$this->model_kp_order->parseTracker($result['order_id'], $result['tracker_xml']):false,
				'is_on_pickpoint' 			=> (mb_stripos($result['shipping_code'], 'pickup_advanced') !== false),
				'customer_href' 			=> $this->url->link('sale/customer/update', 'customer_id='.$result['customer_id'].'&token=' . $this->session->data['token'], 'SSL'),
				'customer_pl' 				=> $this->url->link('sale/customer/printlist', 'customer_id='.$result['customer_id'].'&token=' . $this->session->data['token'], 'SSL'),
				'customer_id'   			=> $result['customer_id'],
				'customer_info' 			=> $this->model_sale_customer->getCustomer($result['customer_id']),
				'customer_has_birthday'  	=> $this->model_sale_customer->customerHasBirthday($result['customer_id']),
				'is_mudak'					=> $this->model_sale_customer->getIsMudak($result['customer_id']),
				'is_opt'        			=> $this->model_sale_customer->getIsOpt($result['customer_id']),
				//'customer_segments' 		=> $this->model_sale_customer->getCustomerSegments($result['customer_id']),
				'first_referrer'   			=> $result['first_referrer']?str_replace('www.', '', parse_url($this->model_module_referrer->simple_decode( $result['first_referrer'] ),  PHP_URL_HOST)):'Прямой',
				'last_referrer'   			=> $result['last_referrer']?str_replace('www.', '',parse_url($this->model_module_referrer->simple_decode( $result['last_referrer'] ),  PHP_URL_HOST)):'Прямой',
				'affiliate' 				=> $this->model_sale_affiliate->getAffiliate($result['affiliate_id']),
				'is_marketplace'            => in_array($_fr, $this->model_sale_order->getMarketplaces()),
				'telephone'      			=> $result['telephone'],
				'fax'     			 		=> $result['fax'],
				'faxname'     			 	=> $result['faxname'],
				'store_url'     			=> $result['store_url'],
				'part_num'         			=> $result['part_num'],
				'pay_type'         			=> $result['pay_type'],
				'parties'         			=> $order_parties,
				'related_orders'         	=> $this->model_sale_order->getRelatedOrders($result['order_id']),
				'comment'         			=> strip_tags($result['comment']),
				'last_comment'   			=> $last_comment,
				'delivery_code'     		=> $result['delivery_code'],
				'shipping_country'     		=> $result['shipping_country'],
				'shipping_address'     		=> $result['shipping_address_1']?$result['shipping_address_1']:$result['payment_address_1'],
				'address_ok'				=> $addressOk,
				'shipping_country_id'     	=> $result['shipping_country_id'],
				'shipping_country_info'     => isset($countries[$result['shipping_country_id']])?$countries[$result['shipping_country_id']]:false,
				'shipping_city'     		=> $result['shipping_city'],
				'current_time'     			=> isset($cities[$guessed_shipping_city])?$cities[$guessed_shipping_city]:false,
				'can_call_now'    			=> isset($cities[$guessed_shipping_city])?$this->model_kp_geoip->canCallNow($cities[$guessed_shipping_city]):false,
				'payment_method'     		=> $result['payment_method'],
				'payment_code'     			=> $result['payment_code'],
				'payment_secondary_method'  => $result['payment_secondary_method'],
				'shipping_method'    		=> $result['shipping_method'],
				'products'           		=> $order_products,
				'manager'	    			=> $manager,
				'courier'	    			=> $courier,
				'urgent'        			=> $result['urgent'],
				'preorder'        			=> $result['preorder'],
				'is_reorder'        		=> $this->model_sale_order->thisIsReorder($result['order_id']),
				'urgent_buy'        		=> $result['urgent_buy'],
				'wait_full'       			=> $result['wait_full'],
				'do_not_call'       		=> $result['do_not_call'],
				'ua_logistics'        		=> $result['ua_logistics'],
				'closed'        			=> $result['closed'],
				'salary_paid'        		=> $result['salary_paid'],
				'status'        			=> $result['status'],
				'status_id'     			=> $result['order_status_id'],
				'status_txt_color' 			=> isset($result['status_txt_color']) ? $result['status_txt_color'] : '',
				'status_bg_color' 			=> isset($result['status_bg_color']) ? $result['status_bg_color'] : '',
				'status_fa_icon' 			=> isset($result['status_fa_icon']) ? $result['status_fa_icon'] : '',
				'order_status_id'   		=> $result['order_status_id'],
				'from_waitlist'   			=> $result['from_waitlist'],
				'changed'        			=> $result['changed'],
				'histories'    				=> $this->model_sale_order->getTotalOrderHistoriesByOrderStatusId($result['order_id']),
				'total'         			=> $total,
				'yam_comission'			  	=> ($result['yam'])?$this->currency->format(-1 * ($sub_total/100*12), $result['currency_code'], '1'):false,
				'costprice'				  	=> $this->currency->format($result['costprice'], $this->config->get('config_currency'), 1),
				'costprice_national'	  	=> $this->currency->format($this->currency->convert($result['costprice'], $this->config->get('config_currency'), $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $result['store_id'])), $this->config->get('config_regional_currency'), 1),
				'profitability'			  	=> $result['profitability'],
				'amazon_offers_type'		=> $result['amazon_offers_type'],
				'totals'         			=> $totals2,
				'total_discount'         	=> ($total_discount<0)?$this->currency->format($total_discount, $result['currency_code'], '1'):false,
				'total_discount_percent'    => ($total_discount<0)?round(($total_discount/$sub_total) * 100, 2):false,
				'total_isnull'         		=> max($result['total_national'], $result['total']) == 0,
				'date_added'    			=> date('d.m.Y', strtotime($result['date_added'])),
				'time_added'    			=> date('H:i', strtotime($result['date_added'])),
				'date_modified' 			=> date('d.m.Y', strtotime($result['date_modified'])),
				'date_accepted'             => $times['date_accepted']?date('d.m.Y', strtotime($times['date_accepted'])):false,
				'date_delivery_actual' 		=> ($result['date_delivery_actual']!='0000-00-00')?date('Y-m-d',  strtotime($result['date_delivery_actual'])):'0000-00-00',
				'days_from_accept'          => $result['closed']?$this->dateDiff($times['date_accepted'], $times['date_closed']):$this->dateDiff($times['date_accepted'], date('Y-m-d H:i:s')),
				'selected'      			=> isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'ttn'     					=> $result['ttn'],
				'ttn_info'     				=> $this->courierServices->getTrackingCodeInfo($result['ttn']),
				'courier_status'     		=> $result['courier_status'],
				'courier_comment'     		=> $result['courier_comment'],
				'courier_color'             => $courier_color,
				'can_get_csi' 				=> $this->model_kp_csi->canGetCSI($result['order_status_id']),
				'orders_without_csi'    	=> $this->model_kp_csi->getCompletedOrdersWithoutCSI($result['customer_id']),
				'orders_without_csi_fltr' 	=> $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_customer=' . $result['customer_id'], 'SSL'),
				'csi_reject'      		 	=> $result['csi_reject'],
				'csi_average'      		 	=> $result['csi_average'],
				'nbt_csi'      		 	 	=> $result['nbt_csi'],
				'probably_cancel'        	=> $result['probably_cancel'],
				'probably_cancel_reason' 	=> $result['probably_cancel_reason'],
				'probably_close'       	 	=> $result['probably_close'],
				'probably_close_reason'	 	=> $result['probably_close_reason'],
				'probably_problem'		 	=> $result['probably_problem'],
				'probably_problem_reason'	=> $result['probably_problem_reason'],
				'needs_checkboxua'			=> $result['needs_checkboxua'],
				'paid_by'					=> $result['paid_by'],
				'fiscal_code'      			=> $result['fiscal_code'],
				'receipt_id'      			=> $result['receipt_id'], 
				'is_sent_dps'      			=> $result['is_sent_dps'], 
				'sent_dps_at'      			=> date('d.m.Y', strtotime($result['sent_dps_at'])), 
				'html_link'      			=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'html'),
				'pdf_link'      			=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'pdf'),
				'text_link'      			=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'text'),
				'png_link'      			=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'png'),
				'qrcode_link'      			=> $this->checkBoxUA->getReceiptLink($result['receipt_id'],'qrcode'),
				'newversion'				=> ($result['template'] == 'kp'),
				'reward'     				=> $this->currency->formatBonus($result['reward'], true),
				'reward_used'   			=> $result['reward_used']?$this->currency->formatNegativeBonus($result['reward_used'], true):false,
				'total_customer_orders' 	=> $total_orders,
				'total_customer_orders_txt' => morphos\Russian\NounPluralization::pluralize($total_orders, 'заказ'),
				'total_customer_orders_a' 	=> $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_customer=' . $result['customer_id'], 'SSL'),
				'total_customer_orders_n_a' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_customer=' . $result['customer'], 'SSL'),
				'reject_reason' 			=> ($result['reject_reason_id'] > 0)?$this->model_sale_reject_reason->getRejectReasonName($result['reject_reason_id']):false,
				'action'        			=> $action
				);
			}
			
			$this->data['heading_title'] 		= $this->language->get('heading_title');
			
			$this->data['text_no_results'] 		= $this->language->get('text_no_results');
			$this->data['text_missing'] 		= $this->language->get('text_missing');
			
			$this->data['column_order_id'] 		= $this->language->get('column_order_id');
			$this->data['column_customer'] 		= $this->language->get('column_customer');
			$this->data['column_status'] 		= $this->language->get('column_status');
			$this->data['column_total'] 		= $this->language->get('column_total');
			$this->data['column_date_added'] 	= $this->language->get('column_date_added');
			$this->data['column_date_modified'] = $this->language->get('column_date_modified');
			$this->data['column_action'] 		= $this->language->get('column_action');
			
			$this->data['button_invoice'] 		= $this->language->get('button_invoice');
			$this->data['button_insert'] 		= $this->language->get('button_insert');
			$this->data['button_delete'] 		= $this->language->get('button_delete');
			$this->data['button_filter'] 		= $this->language->get('button_filter');
			$this->data['button_remove'] 		= $this->language->get('button_remove');
			$this->data['button_upload'] 		= $this->language->get('button_upload');
			
			$this->data['entry_option'] = $this->language->get('entry_option');
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_discount_card'])) {
				$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$url .= '&filter_reject_reason_id=' . $this->request->get['filter_reject_reason_id'];
			}
			
			if (isset($this->request->get['filter_order_store_id'])) {
				$url .= '&filter_order_store_id=' . $this->request->get['filter_order_store_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_added_to'])) {
				$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
			
			if (isset($this->request->get['filter_date_delivery'])) {
				$url .= '&filter_date_delivery=' . $this->request->get['filter_date_delivery'];
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			
			if (isset($this->request->get['filter_ttn'])) {
				$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
			}
			
			if (isset($this->request->get['filter_courier_status'])) {
				$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
			}
			
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			
			if (isset($this->request->get['filter_payment_method'])) {
				$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
			}
			
			if (isset($this->request->get['filter_urgent'])) {
				$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
			}
			
			if (isset($this->request->get['filter_pwa'])) {
				$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
			}

			if (isset($this->request->get['filter_is_credit_order'])) {
				$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
			}
			
			if (isset($this->request->get['filter_yam'])) {
				$url .= '&filter_yam=' . $this->request->get['filter_yam'];
			}
			
			if (isset($this->request->get['filter_yam_id'])) {
				$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
			}
			
			if (isset($this->request->get['filter_preorder'])) {
				$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
			}
			
			if (isset($this->request->get['filter_urgent_buy'])) {
				$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
			}
			
			if (isset($this->request->get['filter_wait_full'])) {
				$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
			}
			
			if (isset($this->request->get['filter_ua_logistics'])) {
				$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
			}

			if (isset($this->request->get['filter_amazon_offers_type'])) {
				$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
			}
			
			if (isset($this->request->get['filter_manager_id'])) {
				$url .= '&filter_manager_id=' . $this->request->get['filter_manager_id'];
			}
			
			if (isset($this->request->get['filter_courier_id'])) {
				$url .= '&filter_courier_id=' . $this->request->get['filter_courier_id'];
			}
			
			if (isset($this->request->get['filter_affiliate_id'])) {
				$url .= '&filter_affiliate_id=' . $this->request->get['filter_affiliate_id'];
			}
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
			$this->data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
			$this->data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
			$this->data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
			$this->data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');
			$this->data['sort_manager_id'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.manager_id' . $url, 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_discount_card'])) {
				$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
			}
			
			if (isset($this->request->get['filter_referrer'])) {
				$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_order_store_id'])) {
				$url .= '&filter_order_store_id=' . $this->request->get['filter_order_store_id'];
			}
			
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$url .= '&filter_reject_reason_id=' . $this->request->get['filter_reject_reason_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_added_to'])) {
				$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
			
			if (isset($this->request->get['filter_date_delivery'])) {
				$url .= '&filter_date_delivery=' . $this->request->get['filter_date_delivery'];
			}
			
			if (isset($this->request->get['filter_manager_id'])) {
				$url .= '&filter_manager_id=' . $this->request->get['filter_manager_id'];
			}
			
			if (isset($this->request->get['filter_courier_id'])) {
				$url .= '&filter_courier_id=' . $this->request->get['filter_courier_id'];
			}
			
			if (isset($this->request->get['filter_affiliate_id'])) {
				$url .= '&filter_affiliate_id=' . $this->request->get['filter_affiliate_id'];
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			
			if (isset($this->request->get['filter_ttn'])) {
				$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
			}
			
			if (isset($this->request->get['filter_courier_status'])) {
				$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
			}
			
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			
			if (isset($this->request->get['filter_payment_method'])) {
				$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
			}
			
			if (isset($this->request->get['filter_urgent'])) {
				$url .= '&filter_urgent=' . $this->request->get['filter_urgent'];
			}
			
			if (isset($this->request->get['filter_pwa'])) {
				$url .= '&filter_pwa=' . $this->request->get['filter_pwa'];
			}

			if (isset($this->request->get['filter_is_credit_order'])) {
				$url .= '&filter_is_credit_order=' . $this->request->get['filter_is_credit_order'];
			}
			
			if (isset($this->request->get['filter_yam'])) {
				$url .= '&filter_yam=' . $this->request->get['filter_yam'];
			}
			
			if (isset($this->request->get['filter_yam_id'])) {
				$url .= '&filter_yam_id=' . $this->request->get['filter_yam_id'];
			}
			
			if (isset($this->request->get['filter_preorder'])) {
				$url .= '&filter_preorder=' . $this->request->get['filter_preorder'];
			}
			
			if (isset($this->request->get['filter_urgent_buy'])) {
				$url .= '&filter_urgent_buy=' . $this->request->get['filter_urgent_buy'];
			}
			
			if (isset($this->request->get['filter_wait_full'])) {
				$url .= '&filter_wait_full=' . $this->request->get['filter_wait_full'];
			}
			
			if (isset($this->request->get['filter_ua_logistics'])) {
				$url .= '&filter_ua_logistics=' . $this->request->get['filter_ua_logistics'];
			}

			if (isset($this->request->get['filter_amazon_offers_type'])) {
					$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
				}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_order_id'] 			= $filter_order_id;
			$this->data['filter_customer'] 			= $filter_customer;
			$this->data['filter_referrer'] 			= $filter_referrer;
			$this->data['filter_discount_card'] 	= $filter_discount_card;
			$this->data['filter_order_status_id'] 	= $filter_order_status_id;
			$this->data['filter_reject_reason_id'] 	= $filter_reject_reason_id;
			$this->data['filter_order_store_id'] 	= $filter_order_store_id;
			$this->data['filter_total'] 			= $filter_total;
			$this->data['filter_date_added'] 		= $filter_date_added;
			$this->data['filter_date_added_to'] 	= $filter_date_added_to;
			$this->data['filter_date_modified'] 	= $filter_date_modified;
			$this->data['filter_date_delivery'] 	= $filter_date_delivery;
			$this->data['filter_manager_id'] 		= $filter_manager_id;
			$this->data['filter_courier_id'] 		= $filter_courier_id;
			$this->data['filter_affiliate_id'] 		= $filter_affiliate_id;
			$this->data['filter_product_id'] 		= $filter_product_id;
			$this->data['filter_ttn'] 				= $filter_ttn;
			$this->data['filter_courier_status'] 	= $filter_courier_status;
			$this->data['filter_shipping_method'] 	= $filter_shipping_method;
			$this->data['filter_payment_method'] 	= $filter_payment_method;
			$this->data['filter_urgent'] 			= $filter_urgent;
			$this->data['filter_pwa']				= $filter_pwa;
			$this->data['filter_is_credit_order'] 	= $filter_is_credit_order;
			$this->data['filter_yam'] 				= $filter_yam;
			$this->data['filter_yam_id'] 			= $filter_yam_id;
			$this->data['filter_preorder'] 			= $filter_preorder;
			$this->data['filter_urgent_buy'] 		= $filter_urgent_buy;
			$this->data['filter_wait_full'] 		= $filter_wait_full;
			$this->data['filter_ua_logistics'] 		= $filter_ua_logistics;
			$this->data['filter_amazon_offers_type']= $filter_amazon_offers_type;
			
			$this->data['all_managers'] = $this->getAllManagersWhoCanOwnOrders();			
			$this->data['all_couriers'] = $this->getAllCouriers();
			
			$this->load->model('sale/affiliate');
			$this->data['all_affiliates'] = $this->model_sale_affiliate->getAffiliates(array('start' => 0, 'limit' => 100, 'sort' => 'name', 'order' => 'DESC'));
			
			$this->load->model('localisation/order_status');
			$this->load->model('setting/store');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	
			$this->data['reject_reasons'] = $this->model_sale_reject_reason->getRejectReasons();
			$this->data['order_stores']   = $this->model_setting_store->getStores();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			if (isset($this->request->get['filter_do_csv']) && ($this->request->get['filter_do_csv'] == 1)){
				set_time_limit(1000);
				ini_set('memory_limit','2G');
				header( 'Content-Type: text/csv charset=utf-8' );
				header("Content-Disposition: attachment; filename=order_export_".date('Y_m_d').".csv");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				$file = fopen('php://output', 'w');
				$header = array(
				'Заказ', 'Статус', 'Дата добавления', 'Страна', 'Покупатель', 'EMAIL', 'Телефон');
				
				fputcsv($file, $header);
				
				foreach ($this->data['orders'] as $fe_order){
					
					$line = array($fe_order['order_id'], $fe_order['status'], $fe_order['date_added'], $fe_order['shipping_country'], $fe_order['customer'], $fe_order['email'], $fe_order['telephone']);
					fputcsv($file, $line);
					
				}
				
				fclose($file);
				die();
			}
			
			if (isset($this->request->get['ajax']) && ($this->request->get['ajax'] == 1)){
				$this->template = 'sale/order_list_ajax.tpl';
				} else {		
				$this->template = 'sale/order_list.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);
			}
			
			$this->response->setOutput($this->render());
		}
		
		public function getForm($return_data = false, $return_order_id = false) {
			$this->load->model('sale/customer');
			$this->load->model('module/emailtemplate');
			$this->load->model('localisation/language');
			$this->load->model('localisation/order_status');
			$this->load->model('setting/extension');
			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$this->load->model('kp/price');
			$this->load->model('user/user');
			$this->load->model('sale/reject_reason');
			$this->load->model('total/scd_recount');
			$this->load->model('sale/supplier');
			$this->load->model('tool/image');
			$this->load->model('setting/setting');
			$this->load->model('kp/geoip');
			$this->load->model('kp/csi');
			
			
			//$this->data['heading_title'] = $this->language->get('heading_title');						
			if ($return_order_id && $return_data){
				$this->request->get['order_id'] = (int)$return_order_id;
			}
			
			if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST' || $return_data)) {				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);	
				$this->data['heading_title'] = sprintf($this->language->get('heading_title_single'), $this->request->get['order_id']);
				$order_id = (int)$this->request->get['order_id'];
			}
			
			$this->data['is_buyer'] = (in_array($this->user->getUserGroup(), array(1, 12, 15, 19, 23)) or $this->user->getIsAV());
			$this->data['is_super_buyer'] = (in_array($this->user->getUserGroup(), array(1, 12, 15, 19, 23)) or $this->user->getIsAV());
			$this->data['is_client_manager'] = (in_array($this->user->getUserGroup(), array(14)) or $this->user->getIsAV());
			$this->data['can_use_cheques'] = (in_array($this->user->getUserGroup(), array(1, 23, 22, 13)) or $this->user->getIsAV());
			$this->data['is_buch'] = in_array($this->user->getUserGroup(), array(22,23));		
			$this->data['is_just_buch'] = (in_array($this->user->getUserGroup(), array(22)) && ($order_info['order_status_id'] == $this->config->get('complete_order_status_id')));
			$this->data['is_doTransactions'] = $this->user->canDoTransactions();
			$this->data['is_superAV'] = $this->user->getIsAV();
			$this->data['is_superManager'] = $this->user->getIsMM();
			//	$this->data['products_can_not_be_deleted'] = in_array($order_info['order_status_id'], $this->config->get('config_nodelete_order_status_id'));
			$this->data['products_can_not_be_deleted'] = false;
			
			$this->document->setTitle($this->data['heading_title']);
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_default'] = $this->language->get('text_default');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_none'] = $this->language->get('text_none');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_product'] = $this->language->get('text_product');
			$this->data['text_voucher'] = $this->language->get('text_voucher');
			$this->data['text_order'] = $this->language->get('text_order');
			
			$this->data['entry_store'] = $this->language->get('entry_store');
			$this->data['entry_customer'] = $this->language->get('entry_customer');
			$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			$this->data['entry_affiliate'] = $this->language->get('entry_affiliate');
			$this->data['entry_address'] = $this->language->get('entry_address');
			$this->data['entry_company'] = $this->language->get('entry_company');
			$this->data['entry_company_id'] = $this->language->get('entry_company_id');
			$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
			$this->data['entry_address_1'] = $this->language->get('entry_address_1');
			$this->data['entry_address_2'] = $this->language->get('entry_address_2');
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_zone_code'] = $this->language->get('entry_zone_code');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_product'] = $this->language->get('entry_product');
			$this->data['entry_option'] = $this->language->get('entry_option');
			$this->data['entry_quantity'] = $this->language->get('entry_quantity');
			$this->data['entry_to_name'] = $this->language->get('entry_to_name');
			$this->data['entry_to_email'] = $this->language->get('entry_to_email');
			$this->data['entry_from_name'] = $this->language->get('entry_from_name');
			$this->data['entry_from_email'] = $this->language->get('entry_from_email');
			$this->data['entry_theme'] = $this->language->get('entry_theme');
			$this->data['entry_message'] = $this->language->get('entry_message');
			$this->data['entry_amount'] = $this->language->get('entry_amount');
			$this->data['entry_shipping'] = $this->language->get('entry_shipping');
			$this->data['entry_payment'] = $this->language->get('entry_payment');
			$this->data['entry_voucher'] = $this->language->get('entry_voucher');
			$this->data['entry_coupon'] = $this->language->get('entry_coupon');
			$this->data['entry_reward'] = $this->language->get('entry_reward');
			
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['entry_notify'] = $this->language->get('entry_notify');
			
			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_product'] = $this->language->get('button_add_product');
			$this->data['button_add_voucher'] = $this->language->get('button_add_voucher');
			$this->data['button_update_total'] = $this->language->get('button_update_total');
			$this->data['button_remove'] = $this->language->get('button_remove');
			$this->data['button_upload'] = $this->language->get('button_upload');
			
			$this->data['tab_order'] = $this->language->get('tab_order');
			$this->data['tab_customer'] = $this->language->get('tab_customer');
			$this->data['tab_payment'] = $this->language->get('tab_payment');
			$this->data['tab_shipping'] = $this->language->get('tab_shipping');
			$this->data['tab_product'] = $this->language->get('tab_product');
			$this->data['tab_voucher'] = $this->language->get('tab_voucher');
			$this->data['tab_total'] = $this->language->get('tab_total');
			
			$this->data['text_amazon_order_id'] = $this->language->get('text_amazon_order_id');
			$this->data['text_name'] = $this->language->get('text_name');
			$this->data['text_order_id'] = $this->language->get('text_order_id');
			$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
			$this->data['text_store_name'] = $this->language->get('text_store_name');
			$this->data['text_store_url'] = $this->language->get('text_store_url');
			$this->data['text_customer'] = $this->language->get('text_customer');
			$this->data['text_customer_group'] = $this->language->get('text_customer_group');
			$this->data['text_email'] = $this->language->get('text_email');
			$this->data['text_telephone'] = $this->language->get('text_telephone');
			$this->data['text_fax'] = $this->language->get('text_fax');
			$this->data['text_total'] = $this->language->get('text_total');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_order_status'] = $this->language->get('text_order_status');
			$this->data['text_comment'] = $this->language->get('text_comment');
			$this->data['text_affiliate'] = $this->language->get('text_affiliate');
			$this->data['text_commission'] = $this->language->get('text_commission');
			$this->data['text_ip'] = $this->language->get('text_ip');
			
			/* SOFORP Order Referer - begin */
			$this->data['text_first_referrer'] = $this->language->get('text_first_referrer');
			$this->data['text_last_referrer'] = $this->language->get('text_last_referrer');
			/* SOFORP Order Referer - end */
            
			$this->data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$this->data['text_user_agent'] = $this->language->get('text_user_agent');
			$this->data['text_accept_language'] = $this->language->get('text_accept_language');
			$this->data['text_date_added'] = $this->language->get('text_date_added');
			$this->data['text_date_modified'] = $this->language->get('text_date_modified');
			$this->data['text_firstname'] = $this->language->get('text_firstname');
			$this->data['text_lastname'] = $this->language->get('text_lastname');
			$this->data['text_company'] = $this->language->get('text_company');
			$this->data['text_company_id'] = $this->language->get('text_company_id');
			$this->data['text_tax_id'] = $this->language->get('text_tax_id');
			$this->data['text_address_1'] = $this->language->get('text_address_1');
			$this->data['text_address_2'] = $this->language->get('text_address_2');
			$this->data['text_city'] = $this->language->get('text_city');
			$this->data['text_postcode'] = $this->language->get('text_postcode');
			$this->data['text_zone'] = $this->language->get('text_zone');
			$this->data['text_zone_code'] = $this->language->get('text_zone_code');
			$this->data['text_country'] = $this->language->get('text_country');
			$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$this->data['text_payment_method'] = $this->language->get('text_payment_method');
			$this->data['text_download'] = $this->language->get('text_download');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_generate'] = $this->language->get('text_generate');
			$this->data['text_reward_add'] = $this->language->get('text_reward_add');
			$this->data['text_reward_remove'] = $this->language->get('text_reward_remove');
			$this->data['text_commission_add'] = $this->language->get('text_commission_add');
			$this->data['text_commission_remove'] = $this->language->get('text_commission_remove');
			$this->data['text_credit_add'] = $this->language->get('text_credit_add');
			$this->data['text_credit_remove'] = $this->language->get('text_credit_remove');
			$this->data['text_country_match'] = $this->language->get('text_country_match');
			$this->data['text_country_code'] = $this->language->get('text_country_code');
			$this->data['text_high_risk_country'] = $this->language->get('text_high_risk_country');
			$this->data['text_distance'] = $this->language->get('text_distance');
			$this->data['text_ip_region'] = $this->language->get('text_ip_region');
			$this->data['text_ip_city'] = $this->language->get('text_ip_city');
			$this->data['text_ip_latitude'] = $this->language->get('text_ip_latitude');
			$this->data['text_ip_longitude'] = $this->language->get('text_ip_longitude');
			$this->data['text_ip_isp'] = $this->language->get('text_ip_isp');
			$this->data['text_ip_org'] = $this->language->get('text_ip_org');
			$this->data['text_ip_asnum'] = $this->language->get('text_ip_asnum');
			$this->data['text_ip_user_type'] = $this->language->get('text_ip_user_type');
			$this->data['text_ip_country_confidence'] = $this->language->get('text_ip_country_confidence');
			$this->data['text_ip_region_confidence'] = $this->language->get('text_ip_region_confidence');
			$this->data['text_ip_city_confidence'] = $this->language->get('text_ip_city_confidence');
			$this->data['text_ip_postal_confidence'] = $this->language->get('text_ip_postal_confidence');
			$this->data['text_ip_postal_code'] = $this->language->get('text_ip_postal_code');
			$this->data['text_ip_accuracy_radius'] = $this->language->get('text_ip_accuracy_radius');
			$this->data['text_ip_net_speed_cell'] = $this->language->get('text_ip_net_speed_cell');
			$this->data['text_ip_metro_code'] = $this->language->get('text_ip_metro_code');
			$this->data['text_ip_area_code'] = $this->language->get('text_ip_area_code');
			$this->data['text_ip_time_zone'] = $this->language->get('text_ip_time_zone');
			$this->data['text_ip_region_name'] = $this->language->get('text_ip_region_name');
			$this->data['text_ip_domain'] = $this->language->get('text_ip_domain');
			$this->data['text_ip_country_name'] = $this->language->get('text_ip_country_name');
			$this->data['text_ip_continent_code'] = $this->language->get('text_ip_continent_code');
			$this->data['text_ip_corporate_proxy'] = $this->language->get('text_ip_corporate_proxy');
			$this->data['text_anonymous_proxy'] = $this->language->get('text_anonymous_proxy');
			$this->data['text_proxy_score'] = $this->language->get('text_proxy_score');
			$this->data['text_is_trans_proxy'] = $this->language->get('text_is_trans_proxy');
			$this->data['text_free_mail'] = $this->language->get('text_free_mail');
			$this->data['text_carder_email'] = $this->language->get('text_carder_email');
			$this->data['text_high_risk_username'] = $this->language->get('text_high_risk_username');
			$this->data['text_high_risk_password'] = $this->language->get('text_high_risk_password');
			$this->data['text_bin_match'] = $this->language->get('text_bin_match');
			$this->data['text_bin_country'] = $this->language->get('text_bin_country');
			$this->data['text_bin_name_match'] = $this->language->get('text_bin_name_match');
			$this->data['text_bin_name'] = $this->language->get('text_bin_name');
			$this->data['text_bin_phone_match'] = $this->language->get('text_bin_phone_match');
			$this->data['text_bin_phone'] = $this->language->get('text_bin_phone');
			$this->data['text_customer_phone_in_billing_location'] = $this->language->get('text_customer_phone_in_billing_location');
			$this->data['text_ship_forward'] = $this->language->get('text_ship_forward');
			$this->data['text_city_postal_match'] = $this->language->get('text_city_postal_match');
			$this->data['text_ship_city_postal_match'] = $this->language->get('text_ship_city_postal_match');
			$this->data['text_score'] = $this->language->get('text_score');
			$this->data['text_explanation'] = $this->language->get('text_explanation');
			$this->data['text_risk_score'] = $this->language->get('text_risk_score');
			$this->data['text_queries_remaining'] = $this->language->get('text_queries_remaining');
			$this->data['text_maxmind_id'] = $this->language->get('text_maxmind_id');
			$this->data['text_error'] = $this->language->get('text_error');
			
			$this->data['button_invoice'] = $this->language->get('button_invoice');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_history'] = $this->language->get('button_add_history');
			
			//EMAILTEMPLATE 			
			$this->data['entry_summary'] = $this->language->get('entry_summary');
            $this->data['entry_show_products'] = $this->language->get('entry_show_products');
            $this->data['entry_show_totals'] = $this->language->get('entry_show_totals');
            $this->data['entry_show_downloads'] = $this->language->get('entry_show_downloads');
            $this->data['entry_show_vouchers'] = $this->language->get('entry_show_vouchers');
            $this->data['entry_template'] = $this->language->get('entry_template');
            $this->data['entry_pdf_attach'] = $this->language->get('entry_pdf_attach');
            $this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_preview'] = $this->language->get('text_preview');
			$this->data['warning_template_content'] = $this->language->get('warning_template_content');
			
			$this->data['language_id'] = $order_info['language_id'];
			$this->data['store_id'] = $order_info['store_id'];
			
			$this->data['from_waitlist'] = $order_info['from_waitlist'];
			
			
            $templates = $this->model_module_emailtemplate->getTemplates(array(
			'emailtemplate_key' => 'admin.order_update'
			));
			
			$this->data['templates_options'] = [];
			
			foreach($templates as $row){
				$label = $row['emailtemplate_label'];
				
				if($row['emailtemplate_default']){
					$label = $this->language->get('text_default') . ' - ' . $label;
				}
				
				$this->data['templates_options'][] = array(
				'value' => $row['emailtemplate_id'],
				'label' => $label
				);
			}
			
			$this->data['last_invoice'] = $this->model_sale_order->getOrderLastInvoiceHistory($order_id);
			
			if (!empty($this->data['last_invoice']['user_id'])){
				$this->data['last_invoice']['realname'] = $this->model_user_user->getRealUserNameById($this->data['last_invoice']['user_id']);
				} else {
				$this->data['last_invoice']['realname'] = '';
			}
			
			if (!empty($this->data['last_invoice']['datetime'])){
				$this->data['last_invoice']['datetime'] =  date('d.m.Y H:i:s', strtotime($this->data['last_invoice']['datetime']));
				} else {
				$this->data['last_invoice']['realname'] = '';
			}
			
            $this->data['templates_action'] = $this->url->link('module/emailtemplate/fetch_template', 'output=comment&token='.$this->session->data['token'], 'SSL');
			
			$this->data['pdf_download'] = $this->url->link('module/emailtemplate/preview_invoice', 'token='.$this->session->data['token'].'&order_id='.$order_id, 'SSL');
			
			$this->data['xls1_download'] = $this->url->link('report/export_xls/createProductsInvoiceWithImage', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');
			$this->data['xls2_download'] = $this->url->link('report/export_xls/createProductsInvoice', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL');
			//EMAILTEMPLATE
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['firstname'])) {
				$this->data['error_firstname'] = $this->error['firstname'];
				} else {
				$this->data['error_firstname'] = '';
			}
			
			if (isset($this->error['lastname'])) {
				$this->data['error_lastname'] = $this->error['lastname'];
				} else {
				$this->data['error_lastname'] = '';
			}
			
			if (isset($this->error['email'])) {
				$this->data['error_email'] = $this->error['email'];
				} else {
				$this->data['error_email'] = '';
			}
			
			if (isset($this->error['telephone'])) {
				$this->data['error_telephone'] = $this->error['telephone'];
				} else {
				$this->data['error_telephone'] = '';
			}
			
			if (isset($this->error['payment_firstname'])) {
				$this->data['error_payment_firstname'] = $this->error['payment_firstname'];
				} else {
				$this->data['error_payment_firstname'] = '';
			}
			
			if (isset($this->error['payment_lastname'])) {
				$this->data['error_payment_lastname'] = $this->error['payment_lastname'];
				} else {
				$this->data['error_payment_lastname'] = '';
			}
			
			if (isset($this->error['payment_address_1'])) {
				$this->data['error_payment_address_1'] = $this->error['payment_address_1'];
				} else {
				$this->data['error_payment_address_1'] = '';
			}
			
			if (isset($this->error['payment_city'])) {
				$this->data['error_payment_city'] = $this->error['payment_city'];
				} else {
				$this->data['error_payment_city'] = '';
			}
			
			if (isset($this->error['payment_postcode'])) {
				$this->data['error_payment_postcode'] = $this->error['payment_postcode'];
				} else {
				$this->data['error_payment_postcode'] = '';
			}
			
			if (isset($this->error['payment_tax_id'])) {
				$this->data['error_payment_tax_id'] = $this->error['payment_tax_id'];
				} else {
				$this->data['error_payment_tax_id'] = '';
			}
			
			if (isset($this->error['payment_country'])) {
				$this->data['error_payment_country'] = $this->error['payment_country'];
				} else {
				$this->data['error_payment_country'] = '';
			}
			
			if (isset($this->error['payment_zone'])) {
				$this->data['error_payment_zone'] = $this->error['payment_zone'];
				} else {
				$this->data['error_payment_zone'] = '';
			}
			
			if (isset($this->error['payment_method'])) {
				$this->data['error_payment_method'] = $this->error['payment_method'];
				} else {
				$this->data['error_payment_method'] = '';
			}
			
			if (isset($this->error['shipping_firstname'])) {
				$this->data['error_shipping_firstname'] = $this->error['shipping_firstname'];
				} else {
				$this->data['error_shipping_firstname'] = '';
			}
			
			if (isset($this->error['shipping_lastname'])) {
				$this->data['error_shipping_lastname'] = $this->error['shipping_lastname'];
				} else {
				$this->data['error_shipping_lastname'] = '';
			}
			
			if (isset($this->error['shipping_address_1'])) {
				$this->data['error_shipping_address_1'] = $this->error['shipping_address_1'];
				} else {
				$this->data['error_shipping_address_1'] = '';
			}
			
			if (isset($this->error['shipping_city'])) {
				$this->data['error_shipping_city'] = $this->error['shipping_city'];
				} else {
				$this->data['error_shipping_city'] = '';
			}
			
			if (isset($this->error['shipping_postcode'])) {
				$this->data['error_shipping_postcode'] = $this->error['shipping_postcode'];
				} else {
				$this->data['error_shipping_postcode'] = '';
			}
			
			if (isset($this->error['shipping_country'])) {
				$this->data['error_shipping_country'] = $this->error['shipping_country'];
				} else {
				$this->data['error_shipping_country'] = '';
			}
			
			if (isset($this->error['shipping_zone'])) {
				$this->data['error_shipping_zone'] = $this->error['shipping_zone'];
				} else {
				$this->data['error_shipping_zone'] = '';
			}
			
			if (isset($this->error['shipping_method'])) {
				$this->data['error_shipping_method'] = $this->error['shipping_method'];
				} else {
				$this->data['error_shipping_method'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_referrer'])) {
				$url .= '&filter_referrer=' . urlencode(html_entity_decode($this->request->get['filter_referrer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_discount_card'])) {
				$url .= '&filter_discount_card=' . $this->request->get['filter_discount_card'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_added_to'])) {
				$url .= '&filter_date_added_to=' . $this->request->get['filter_date_added_to'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}
			
			if (isset($this->request->get['filter_manager_id'])) {
				$url .= '&filter_manager_id=' . $this->request->get['filter_manager_id'];
			}
			
			if (isset($this->request->get['filter_courier_id'])) {
				$url .= '&filter_courier_id=' . $this->request->get['filter_courier_id'];
			}
			
			if (isset($this->request->get['filter_affiliate_id'])) {
				$url .= '&filter_affiliate_id=' . $this->request->get['filter_affiliate_id'];
			}
			
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}
			
			if (isset($this->request->get['filter_ttn'])) {
				$url .= '&filter_ttn=' . $this->request->get['filter_ttn'];
			}
			
			if (isset($this->request->get['filter_courier_status'])) {
				$url .= '&filter_courier_status=' . $this->request->get['filter_courier_status'];
			}
			
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			
			if (isset($this->request->get['filter_payment_method'])) {
				$url .= '&filter_payment_method=' . $this->request->get['filter_payment_method'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['order_id'])) {
				$this->data['action'] = $this->url->link('sale/order/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
				} else {
				$this->data['action'] = $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL');
			}
			
			$this->data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
			$this->data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');	
			
			$this->data['token'] = $this->session->data['token'];
			
			if(isset($this->request->get['order_id'])){
				$this->data['url_resend'] = $this->url->link('sale/order/resend', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
				$this->data['button_resend'] = $this->language->get('button_resend');
			}
			
			if(isset($this->request->get['order_id'])){
				$this->data['url_resendadmin'] = $this->url->link('sale/order/resendadmin', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
				$this->data['button_resendadmin'] = $this->language->get('button_resendadmin');
			}
			
			if(isset($this->request->get['order_id'])){
				$this->data['url_neworder'] = $this->url->link('sale/order/neworder', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');				
			}
			
			if(isset($this->request->get['order_id'])){
				$this->data['url_mailpreview'] = $this->url->link('sale/order/showMailPreview', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');			
			}
			
			if ($order_info['total_national'] > 0){
				$this->data['total'] 			= $this->currency->format($order_info['total_national'], $order_info['currency_code'], '1');				
				$this->data['tip_total'] 		= $order_info['total_national'];
				$this->data['tip_total_txt'] 	=  $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1);
				} else {
				$this->data['total'] 			= $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
				$this->data['tip_total'] 		= round($this->data['total']);
				$this->data['tip_total_txt'] 	=  $this->currency->format(round($this->data['total']), $order_info['currency_code'], 1);
			}

			$this->data['total_num'] 		= $order_info['total'];	
			$this->data['currency_code'] 	= $order_info['currency_code'];
			$this->data['payment_code'] 	= $order_info['payment_code'];
			$this->data['ukrcredits_payment_type'] 		= $order_info['ukrcredits_payment_type']; 
			$this->data['ukrcredits_order_substatus'] 	= $order_info['ukrcredits_order_substatus']; 
			$this->data['ukrcredits_order_status'] 		= $order_info['ukrcredits_order_status']; 
			$this->data['ukrcredits_order_id'] 			= $order_info['ukrcredits_order_id']; 

			$this->data['text_confirm'] 				= $this->language->get('text_confirm');
			$this->data['text_wait'] 					= $this->language->get('text_wait');
			
			$this->data['bill_file'] 		= $order_info['bill_file'];
			$this->data['bill_file2'] 		= $order_info['bill_file2'];			
			$this->data['order_id2'] 		= $order_info['order_id2'];
			
			if ($order_info['order_id2']){
				$_oid2 = explode('-', $order_info['order_id2']);
				$_oid2 = (int)$_oid2[0];
				$this->data['main_order_href'] = $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $_oid2, 'SSL');			
				$this->data['main_order_num'] = $_oid2;
				$this->data['linked_orders'] = false;
				} else {
				$this->data['main_order_href'] = false;
				$this->data['main_order_num'] = false;
				$linked_orders = $this->model_sale_order->getLinkedOrders((int)$this->request->get['order_id']);
				
				$this->data['linked_orders'] = [];
				if (count($linked_orders) > 0){
					foreach ($linked_orders as $_linked_order){
						$this->data['linked_orders'][] = array(
						'href' => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$_linked_order['order_id'], 'SSL'),
						'order_id' => (int)$_linked_order['order_id'],
						'order_id2' => $_linked_order['order_id2']
						);
					}
				}
			}
			
			$this->data['related_orders'] = $this->model_sale_order->getRelatedOrders((int)$this->request->get['order_id']);						
			$this->data['possible_related_orders'] = $this->model_sale_order->getPossibleRelatedOrders($order_info['customer_id'], (int)$this->request->get['order_id']);
			
			$this->data['prepayment'] = $this->currency->format($order_info['prepayment_national'],$order_info['currency_code'], 1) ;
			
			//eq
			$this->data['total_paid'] = $order_info['total_paid'];	
			$this->data['total_paid_date'] = date($this->language->get('date_format_short'), strtotime($order_info['total_paid_date']));
			
			$this->data['prepayment_paid'] = $order_info['prepayment_paid'];	
			$this->data['prepayment_paid_date'] = date($this->language->get('date_format_short'), strtotime($order_info['prepayment_paid_date']));	
			
			if ($order_info['total'] < 0) {
				$this->data['credit'] = $order_info['total'];
				} else {
				$this->data['credit'] = 0;
			}
			
			if (isset($this->request->get['order_id'])) {
				$this->data['order_id'] = $this->request->get['order_id'];
				} else {
				$this->data['order_id'] = 0;
			}
			
			if (isset($this->request->post['store_id'])) {
				$this->data['store_id'] = $this->request->post['store_id'];
				} elseif (!empty($order_info)) {
				$this->data['store_id'] = $order_info['store_id'];
				} else {
				$this->data['store_id'] = '';
			}
			
			$this->data['ip'] = $order_info['ip'];
			//	$this->data['ip_geoip_full_info'] = $this->model_kp_geoip->getCityByIpAddr(trim($order_info['ip']));
			
			$this->data['forwarded_ip'] = [];
			$fw_ips = explode(',', $order_info['forwarded_ip']);			
			
			foreach ($fw_ips as $_fip){
				$this->data['forwarded_ip'][] = array(
				'ip' => $_fip,
				'ip_geoip_full_info' => $this->model_kp_geoip->getCityByIpAddr(trim($_fip))
				);
			}
			
			
			
			/* SOFORP Order Referer - begin */
			$this->load->model('module/referrer');
			if( isset($order_info['first_referrer'])) {
				$this->data['first_referrer'] =  $this->model_module_referrer->decode( $order_info['first_referrer'] );
				} else {
				$this->data['first_referrer'] = "";
			}
			if( isset($order_info['last_referrer'])) {
				$this->data['last_referrer'] =  $this->model_module_referrer->decode( $order_info['last_referrer'] );
				} else {
				$this->data['last_referrer'] = "";
			}
			/* SOFORP Order Referer - end */
			if ($order_info['invoice_no']) {
				$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				$this->data['invoice_no_noprefix'] = $order_info['invoice_no'];
				} else {
				$this->data['invoice_no'] = '';
				$this->data['invoice_no_noprefix'] = '';
			}
			
			if ($order_info['invoice_no'] && $order_info['invoice_date']) {
				$this->data['invoice_date'] = $order_info['invoice_date'];
				} else {
				$this->data['invoice_date'] = '';
			}
			
			$this->data['amazon_order_id'] = $order_info['amazon_order_id'];
			$this->data['user_agent'] = $order_info['user_agent'];
			$this->data['accept_language'] = $order_info['accept_language'];
			$this->data['store_name'] = $order_info['store_name'];
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added'])).' в '.date('H:i:s', strtotime($order_info['date_added']));
			$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified'])).' в '.date('H:i:s', strtotime($order_info['date_modified']));
			//	$this->data['date_sent'] = date($this->language->get('date_format_short'), strtotime($order_info['date_sent'])).' в '.date('H:i:s', strtotime($order_info['date_sent']));
			
			$this->load->model('setting/store');
			
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$this->data['store_url_main'] = HTTPS_CATALOG;
				} else {
				$this->data['store_url_main'] = HTTP_CATALOG;
			}
			
			$this->data['store_url'] = $order_info['store_url'];
			
			if (isset($this->request->post['part_num'])) {
				$this->data['part_num'] = $this->request->post['part_num'];
				} elseif (!empty($order_info)) {
				$this->data['part_num'] = $order_info['part_num'];
				} else {
				$this->data['part_num'] = '';
			}
			
			if (isset($this->request->post['customer'])) {
				$this->data['customer'] = $this->request->post['customer'];
				} elseif (!empty($order_info)) {
				$this->data['customer'] = $order_info['customer'];
				} else {
				$this->data['customer'] = '';
			}
			
			if (isset($this->request->post['customer_id'])) {
				$this->data['customer_id'] = $this->request->post['customer_id'];
				} elseif (!empty($order_info)) {
				$this->data['customer_id'] = $order_info['customer_id'];
				} else {
				$this->data['customer_id'] = '';
			}
			
			if (isset($this->request->post['pay_equire'])) {
				$this->data['pay_equire'] = $this->request->post['pay_equire'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equire'] = $order_info['pay_equire'];
				} else {
				$this->data['pay_equire'] = false;
			}
			
			//разрешение платить пейкипером
			$this->data['pay_paykeeper'] = false;
			
			if (in_array($order_info['currency_code'], array('RUB', 'BYN', 'KZT'))){
				$this->data['pay_paykeeper'] = true;
			}
			
			if (in_array($order_info['currency_code'], array('RUB'))){
				$this->data['pay_paykeeper_alert'] = true;
			}
			
			$this->data['TRANSACTION_DEBIT_PENDING'] = false;
			if ($order_info['concardis_id']){
				$this->load->model('kp/order');
				$_cc_json = $this->model_kp_order->getConcardisOrder($order_info['concardis_id']);
				
				if (isset($_cc_json['lastOperation']) && $_cc_json['lastOperation'] == 'TRANSACTION_DEBIT_PENDING'){
					$this->data['TRANSACTION_DEBIT_PENDING'] = true;
					$this->data['lastOperation'] = $_cc_json['lastOperation'];
					$this->data['pay_paykeeper'] = true;
				}
			}
			
			//Оплата по конкардису внесена
			$this->data['CONCARDIS_IS_PAID_AND_CHECKED_AUTO'] = false;
			$this->data['CONCARDIS_IS_PAID_AND_CHECKED_MANUAL'] = false;
			
			//Нужно проверить статус оплаты
			$this->data['CHECK_CONCARDIS_STATUS_STAGE_1'] = false;
			if ($order_info['concardis_id']){
				//Проверяем, внесена ли оплата автоматически
				if ($this->model_kp_order->checkIfOrderHasPayments($order_info['order_id'], 'concardis')){
					$this->data['CONCARDIS_IS_PAID_AND_CHECKED_AUTO'] = true;
				}
				
				if ($this->model_kp_order->checkIfOrderHasPayments($order_info['order_id'], 'manual_admin', round($this->getOrderTotalTotal($order_info['order_id'])), 'concardis')){
					$this->data['CONCARDIS_IS_PAID_AND_CHECKED_MANUAL'] = true;
				}
				
				if (!$this->data['CONCARDIS_IS_PAID_AND_CHECKED_AUTO'] && !$this->data['CONCARDIS_IS_PAID_AND_CHECKED_MANUAL']){
					$this->data['CHECK_CONCARDIS_STATUS_STAGE_1'] = true;
				}
			}


			if (isset($this->request->post['needs_checkboxua'])) {
				$this->data['needs_checkboxua'] = $this->request->post['needs_checkboxua'];
				} elseif (!empty($order_info)) {
				$this->data['needs_checkboxua'] = $order_info['needs_checkboxua'];
				} else {
				$this->data['needs_checkboxua'] = false;
			}

			if (!empty($order_info)) {
				$this->data['paid_by'] = $order_info['paid_by'];				
			}

			$this->load->model('sale/receipt');



			$this->data['receipts'] = [];
			$receipts = $this->model_sale_receipt->getOrders(['filter_order_id' => $this->request->get['order_id']]);
			
			$this->data['create_receipt_checkbox'] = $this->url->link('sale/receipt/createOne', 'order_id=' . $this->request->get['order_id'] . '&token=' . $this->session->data['token']);

			if ($receipts){
				foreach($receipts as $receipt){
					if ($receipt['receipt_id']){						
						$this->data['receipts'][] = [
							'fiscal_code'      			=> $receipt['fiscal_code'],
							'receipt_id'      			=> $receipt['receipt_id'], 
							'is_sent_dps'      			=> $receipt['is_sent_dps'], 
							'sent_dps_at'      			=> date('d.m.Y', strtotime($receipt['sent_dps_at'])), 
							'html_link'      			=> $this->checkBoxUA->getReceiptLink($receipt['receipt_id'],'html'),
							'pdf_link'      			=> $this->checkBoxUA->getReceiptLink($receipt['receipt_id'],'pdf'),
							'text_link'      			=> $this->checkBoxUA->getReceiptLink($receipt['receipt_id'],'text'),
							'png_link'      			=> $this->checkBoxUA->getReceiptLink($receipt['receipt_id'],'png'),
							'qrcode_link'      			=> $this->checkBoxUA->getReceiptLink($receipt['receipt_id'],'qrcode'),
						];
					}
				}
			}

			
			if (isset($this->request->post['pay_equire2'])) {
				$this->data['pay_equire2'] = $this->request->post['pay_equire2'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equire2'] = $order_info['pay_equire2'];
				} else {
				$this->data['pay_equire2'] = false;
			}
			
			if (isset($this->request->post['pay_equirePP'])) {
				$this->data['pay_equirePP'] = $this->request->post['pay_equirePP'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equirePP'] = $order_info['pay_equirePP'];
				} else {
				$this->data['pay_equirePP'] = false;
			}
			
			if (isset($this->request->post['pay_equireLQP'])) {
				$this->data['pay_equireLQP'] = $this->request->post['pay_equireLQP'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equireLQP'] = $order_info['pay_equireLQP'];
				} else {
				$this->data['pay_equireLQP'] = false;
			}
			
			if (isset($this->request->post['pay_equireWPP'])) {
				$this->data['pay_equireWPP'] = $this->request->post['pay_equireWPP'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equireWPP'] = $order_info['pay_equireWPP'];
				} else {
				$this->data['pay_equireWPP'] = false;
			}

			if (isset($this->request->post['pay_equireMono'])) {
				$this->data['pay_equireMono'] = $this->request->post['pay_equireMono'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equireMono'] = $order_info['pay_equireMono'];
				} else {
				$this->data['pay_equireMono'] = false;
			}
						
			if (isset($this->request->post['pay_equireCP'])) {
				$this->data['pay_equireCP'] = $this->request->post['pay_equireCP'];
				} elseif (!empty($order_info)) {
				$this->data['pay_equireCP'] = $order_info['pay_equireCP'];
				} else {
				$this->data['pay_equireCP'] = false;
			}
			
			if (isset($this->request->post['concardis_id'])) {
				$this->data['concardis_id'] = $this->request->post['concardis_id'];
				} elseif (!empty($order_info)) {
				$this->data['concardis_id'] = $order_info['concardis_id'];
				} else {
				$this->data['concardis_id'] = false;
			}
			
			$store_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$this->data['store_id']);
			
			if ($store_currency == 'EUR'){
				$this->data['EUR_FOR_CONCARDIS'] = round($this->currency->real_convert($this->getOrderTotalTotal($order_info['order_id']), $order_info['currency_code'], 'EUR', true), 2);
				} else {
				$this->data['EUR_FOR_CONCARDIS'] = round($this->currency->makeDiscountOnNumber($this->currency->real_convert($this->getOrderTotalTotal($order_info['order_id']), $order_info['currency_code'], 'EUR', true), 3), 2);
			}
			
			if (isset($this->request->post['pay_type'])) {
				$this->data['pay_type'] = $this->request->post['pay_type'];
				} elseif (!empty($order_info)) {
				$this->data['pay_type'] = $order_info['pay_type'];
				} else {
				$this->data['pay_type'] = false;
			}
			
			
			if (isset($this->request->post['customer_group_id'])) {
				$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
				} elseif (!empty($order_info)) {
				$this->data['customer_group_id'] = $order_info['customer_group_id'];
				} else {
				$this->data['customer_group_id'] = '';
			}
			
			$this->data['pwa'] 					= $order_info['pwa'];
			$this->data['yam'] 					= $order_info['yam'];
			$this->data['yam_id'] 				= $order_info['yam_id'];
			$this->data['yam_shipment_date'] 	= $order_info['yam_shipment_date'];
			$this->data['yam_shipment_id'] 		= $order_info['yam_shipment_id'];
			$this->data['yam_box_id'] 			= $order_info['yam_box_id'];
			$this->data['yam_status'] 			= $order_info['yam_status'];
			$this->data['yam_substatus'] 		= $order_info['yam_substatus'];
			$this->data['yam_fake'] 			= $order_info['yam_fake'];
			
			if ($this->data['customer_id']){
				$this->data['customer_info'] = $this->model_sale_customer->getCustomer($this->data['customer_id']);	
				$this->data['customer_info']['customer_segments'] = $this->model_sale_customer->getCustomerSegments($this->data['customer_id']);			
				$this->data['customer_info']['is_mudak'] = $this->model_sale_customer->getIsMudak($this->data['customer_id']);		
				
				$this->data['points_customer_has'] = $points_customer_has = $this->customer->getRewardTotal($this->data['customer_id']);
				$this->data['points_customer_has_txt'] = $this->currency->formatBonus($this->data['points_customer_has']);
				
				$this->data['points_used_in_current_order'] = $points_used_in_current_order = $this->customer->getRewardReservedByOrder($this->data['customer_id'], $order_info['order_id']);
				$this->data['points_used_in_current_order_txt'] = $this->currency->formatBonus($this->data['points_used_in_current_order']);
				
				$sub_total_for_points = $this->getOrderSubTotal($order_info['order_id']);
				$max_points_to_use = (int)($sub_total_for_points * ($this->model_setting_setting->getKeySettingValue('config', 'config_reward_maxsalepercent', (int)$this->data['store_id']) / 100));
				
				
				$this->data['points_can_be_used'] = ($points_customer_has > $max_points_to_use) ? $max_points_to_use : $points_customer_has;
				$this->data['points_can_be_used_txt'] = $this->currency->formatBonus($this->data['points_can_be_used']);
				
				
			}
			
			//
			$customer_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$this->data['customer_info']['store_id']);
			
			if (!$this->data['customer_id'] || $this->data['customer_info']['store_id'] != $this->data['store_id']){
				
				$store_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$this->data['store_id']);
				
				if ($customer_currency != $store_currency) {
					$this->data['error_warning'] = '<span style="font-size:14px;font-weight:700;">Валюта расчетов покупателя (' . $customer_currency . ') отличается от валюты заказа (' . $store_currency . ').
					<br />В случае несовпадения валют гарантировать корректный подсчет итогов, истории финансовых операций НЕВОЗМОЖНО.
					<br />Также невозможно корректно считать бонусную систему.
					<br />Пожалуйста, переоформите заказ в корректном магазине.
					</span>';			
				}
			}
			
			$errors = $this->getOrderErrors($this->request->get['order_id']);
			if ($errors){				
				$this->session->data['error_error'] = 'В заказе есть ошибки! '.implode('<br />', $errors);
			}
			
			$_fr = $order_info['first_referrer']?trim(str_replace('www.', '', parse_url($this->model_module_referrer->simple_decode( $order_info['first_referrer'] ),  PHP_URL_HOST))):'Прямой';
			if (in_array($_fr, $this->model_sale_order->getMarketplaces()) || $order_info['yam']){		
				
				if ($order_info['yam']){
					$_fr = 'Яндекс.Маркет';
				}
				
				$this->data['warning_marketplace'] = '<span style="font-size:20px;font-weight:700;">ВНИМАНИЕ! Этот заказ оформлен на маркетплейсе ('.$_fr.'). и должен быть выполнен в любом случае! При каком-либо отклонении от корректного выполнения заказа виновный понесет персональную финансовую ответственность.</span>';		
				} else {
				$this->data['warning_marketplace'] = false;
			}
			
			if ($order_info['yam'] && $order_info['yam_fake']){	
				$this->data['warning_yam_fake'] = '<span style="font-size:20px;font-weight:700;">ВНИМАНИЕ! Это тестовый заказ Яндекс.Маркета, его не нужно обрабатывать</span>';
				$this->data['warning_marketplace'] = false;
				} else {
				$this->data['warning_yam_fake'] = false;
			}
			
			if (!empty($order_info['preorder'])){
				$this->data['warning_preorder'] = '<span style="font-size:20px;font-weight:700;">ВНИМАНИЕ! Это не заказ, а всего-лишь заявка на уточнение цены и наличия. Необходимо изначально уточнить цену, задать ее товару, и потом уже перезванивать клиенту.</span>';
				}  else {
				$this->data['warning_preorder'] = false;
			}
			
			if ($this->data['customer_id']){
				$this->data['customer_info']['total_cheque'] = $this->currency->format($this->data['customer_info']['total_cheque'], $customer_currency, '1');
				$this->data['customer_info']['avg_cheque'] = $this->currency->format($this->data['customer_info']['avg_cheque'], $customer_currency, '1');
				
				$this->data['customer_info']['customer_has_birthday'] = $this->model_sale_customer->customerHasBirthday($this->data['customer_id']);								
				
				if ($this->data['customer_info']['customer_has_birthday']){
					$this->data['warning_birthday'] = '<span style="font-size:20px;font-weight:400;">День рождения клиента: ' . $this->data['customer_info']['birthday_date'] . '.' . $this->data['customer_info']['birthday_month'] . '. Не забудь поздравить.</span>';
					} else {
					$this->data['warning_birthday'] = false;
				}
			}
			
			
			
			//алерт в случае несовпадение количества доставок и статусов "частично доставки"
			$_histories = $this->model_sale_order->getOrderHistories((int)$this->request->get['order_id']);
			$_dcount = 0;
			foreach ($_histories as $_h){
				if ($_h['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
					$_dcount++;
				}
			}
			if ($_dcount){
				$_dcproducts = $this->model_sale_order->getOrderProducts((int)$this->request->get['order_id']);
				$_count_deliveries = 1;
				foreach ($_dcproducts as $_op){
					$_count_deliveries = max($_count_deliveries, $_op['delivery_num']);
				}
				
				if ($_dcount > $_count_deliveries){
					$this->data['error_warning'] .= '<span style="font-size:14px;font-weight:700;">ВНИМАНИЕ! Количество партий, на которое разбит заказ ('.$_count_deliveries.'), меньше чем количество историй частичной доставки ('.$_dcount.').<br /> Такое невозможно!</span>';
				}
			}
			
			if (!empty($order_info)) {
				$this->data['closed'] = $order_info['closed'];
				} else {
				$this->data['closed'] = '';
			}
			
			if (!empty($order_info)) {
				$this->data['salary_paid'] = $order_info['salary_paid'];
				} else {
				$this->data['salary_paid'] = '';
			}
			
			
			//разрешение на печать чеков
			$this->data['allowed_to_make_cheque'] = true;
			$this->data['disallow_cheque_comment'] = '';
			if ($this->orderHasPrepay($this->request->get['order_id'])){
				
				$_count_transactions = $this->model_sale_customer->getTotalTransactions($this->data['customer_id'], $this->request->get['order_id']);
				
				if ($_count_transactions == 0){
					$this->data['allowed_to_make_cheque'] = false;
					$this->data['disallow_cheque_comment'] .= 'По заказу с предоплатой не внесено ни одной предоплаты!';
				}
				
				//полная предоплата не внесена полностью
				if ($order_info['payment_code'] == 'cod'){
					$_transactions_sum = (int)$this->model_sale_customer->getTransactionTotalNational($this->data['customer_id'], $this->request->get['order_id']);
					$_order_total_total = (int)$this->getOrderTotalTotal($this->request->get['order_id']);
					
					if ($_transactions_sum != $_order_total_total) {
						$this->data['allowed_to_make_cheque'] = false;
						$this->data['disallow_cheque_comment'] .= ' Сумма транзакций полной предоплаты не совпадает с итоговой суммой заказа!';
					}
				}
				
				//частичная предоплата не внесена полностью
				if ($order_info['payment_code'] != 'cod'){
					$_transactions_sum = (int)$this->model_sale_customer->getTransactionTotalNational($this->data['customer_id'], $this->request->get['order_id']);
					$_order_to_prepay = (int)$this->getOrderPrepayNational($this->request->get['order_id']);
					
					if ($_transactions_sum != $_order_to_prepay){
						$this->data['allowed_to_make_cheque'] = false;
						$this->data['disallow_cheque_comment'] .= ' Сумма транзакций частичной предоплаты не совпадает с суммой предоплаты в итогах!';
					}					
				}				
			}
			
			if ($this->data['closed']){
				$this->data['allowed_to_make_cheque'] = false;
				$this->data['disallow_cheque_comment'] .= ' Заказ закрыт для редактирования!';				
			}
			
			$this->load->model('sale/customer_group');
			
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
			
			if ($customer_group_info) {
				$this->data['customer_group'] = $customer_group_info['name'];
				} else {
				$this->data['customer_group'] = '';
			}
			
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
			
			if ($order_info['customer_id']) {
				$this->data['customer_link'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . (int)$order_info['customer_id'], 'SSL');
				} else {
				$this->data['customer_link'] = false;
			}
			
			
			if (isset($this->request->post['firstname'])) {
				$this->data['firstname'] = $this->request->post['firstname'];
				} elseif (!empty($order_info)) {
				$this->data['firstname'] = $order_info['firstname'];
				} else {
				$this->data['firstname'] = '';
			}
			
			if (isset($this->request->post['lastname'])) {
				$this->data['lastname'] = $this->request->post['lastname'];
				} elseif (!empty($order_info)) {
				$this->data['lastname'] = $order_info['lastname'];
				} else {
				$this->data['lastname'] = '';
			}
			
			if (isset($this->request->post['email'])) {
				$this->data['email'] = $this->request->post['email'];
				} elseif (!empty($order_info)) {
				$this->data['email'] = $order_info['email'];
				} else {
				$this->data['email'] = '';
			}
			
			if (isset($this->request->post['telephone'])) {
				$this->data['telephone'] = $this->request->post['telephone'];
				} elseif (!empty($order_info)) {
				$this->data['telephone'] = $order_info['telephone'];
				} else {
				$this->data['telephone'] = '';
			}
			
			if (isset($this->request->post['urgent'])) {
				$this->data['urgent'] = $this->request->post['urgent'];
				} elseif (!empty($order_info)) {
				$this->data['urgent'] = $order_info['urgent'];
				} else {
				$this->data['urgent'] = 0;
			}
			
			if (isset($this->request->post['preorder'])) {
				$this->data['preorder'] = $this->request->post['preorder'];
				} elseif (!empty($order_info)) {
				$this->data['preorder'] = $order_info['preorder'];
				} else {
				$this->data['preorder'] = 0;
			}
			
			if (isset($this->request->post['urgent_buy'])) {
				$this->data['urgent_buy'] = $this->request->post['urgent_buy'];
				} elseif (!empty($order_info)) {
				$this->data['urgent_buy'] = $order_info['urgent_buy'];
				} else {
				$this->data['urgent_buy'] = 0;
			}
			
			if (isset($this->request->post['wait_full'])) {
				$this->data['wait_full'] = $this->request->post['wait_full'];
				} elseif (!empty($order_info)) {
				$this->data['wait_full'] = $order_info['wait_full'];
				} else {
				$this->data['wait_full'] = 0;
			}

			if (isset($this->request->post['do_not_call'])) {
				$this->data['do_not_call'] = $this->request->post['do_not_call'];
				} elseif (!empty($order_info)) {
				$this->data['do_not_call'] = $order_info['do_not_call'];
				} else {
				$this->data['do_not_call'] = 0;
			}
			
			if (isset($this->request->post['ua_logistics'])) {
				$this->data['ua_logistics'] = $this->request->post['ua_logistics'];
				} elseif (!empty($order_info)) {
				$this->data['ua_logistics'] = $order_info['ua_logistics'];
				} else {
				$this->data['ua_logistics'] = 0;
			}
			
			if (isset($this->request->post['fax'])) {
				$this->data['fax'] = $this->request->post['fax'];
				} elseif (!empty($order_info)) {
				$this->data['fax'] = $order_info['fax'];
				} else {
				$this->data['fax'] = '';
			}
			
			if (isset($this->request->post['faxname'])) {
				$this->data['faxname'] = $this->request->post['faxname'];
				} elseif (!empty($order_info)) {
				$this->data['faxname'] = $order_info['faxname'];
				} else {
				$this->data['faxname'] = '';
			}
			
			if (isset($this->request->post['date_buy'])) {
				$this->data['date_buy'] = $this->request->post['date_buy'];
				} elseif (!empty($order_info)) {
				$this->data['date_buy'] = $order_info['date_buy'];
				} else {
				$this->data['date_buy'] = '';
			}
			
			if (isset($this->request->post['date_country'])) {
				$this->data['date_country'] = $this->request->post['date_country'];
				} elseif (!empty($order_info)) {
				$this->data['date_country'] = $order_info['date_country'];
				} else {
				$this->data['date_country'] = '';
			}
			
			if (isset($this->request->post['date_delivery'])) {
				$this->data['date_delivery'] = $this->request->post['date_delivery'];
				} elseif (!empty($order_info)) {
				$this->data['date_delivery'] = $order_info['date_delivery'];
				} else {
				$this->data['date_delivery'] = '';
			}
			
			if (isset($this->request->post['date_delivery_to'])) {
				$this->data['date_delivery_to'] = $this->request->post['date_delivery_to'];
				} elseif (!empty($order_info)) {
				$this->data['date_delivery_to'] = $order_info['date_delivery_to'];
				} else {
				$this->data['date_delivery_to'] = '';
			}
			
			if (isset($this->request->post['date_delivery_actual'])) {
				$this->data['date_delivery_actual'] = $this->request->post['date_delivery_actual'];
				} elseif (!empty($order_info)) {
				$this->data['date_delivery_actual'] = $order_info['date_delivery_actual'];
				} else {
				$this->data['date_delivery_actual'] = '';
			}
			
			if (isset($this->request->post['display_date_in_account'])) {
				$this->data['display_date_in_account'] = $this->request->post['display_date_in_account'];
				} elseif (!empty($order_info)) {
				$this->data['display_date_in_account'] = $order_info['display_date_in_account'];
				} else {
				$this->data['display_date_in_account'] = false;
			}
			
			
			if (isset($this->request->post['date_maxpay'])) {
				$this->data['date_maxpay'] = $this->request->post['date_maxpay'];
				} elseif (!empty($order_info)) {
				$this->data['date_maxpay'] = $order_info['date_maxpay'];
				} else {
				$this->data['date_maxpay'] = '';
			}
			
			
			if (isset($this->request->post['date_sent'])) {
				$this->data['date_sent'] = $this->request->post['date_sent'];
				} elseif (!empty($order_info)) {
				$this->data['date_sent'] = $order_info['date_sent'];
				} else {
				$this->data['date_sent'] = '';
			}
			
			$this->data['reward'] = $order_info['reward'];
			$this->data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);
			$this->data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$this->data['affiliate_lastname'] = $order_info['affiliate_lastname'];
			
			if (isset($this->request->post['affiliate_id'])) {
				$this->data['affiliate_id'] = $this->request->post['affiliate_id'];
				} elseif (!empty($order_info)) {
				$this->data['affiliate_id'] = $order_info['affiliate_id'];
				} else {
				$this->data['affiliate_id'] = '';
			}
			
			if ($this->data['affiliate_id']){
				$this->data['affiliate_url'] = $this->url->link('sale/affiliate/update', 'affiliate_id='.(int)$this->data['affiliate_id'].'&token='.$this->session->data['token']);
				} else {
				$this->data['affiliate_url'] = '';
			}
			
			if (isset($this->request->post['affiliate'])) {
				$this->data['affiliate'] = $this->request->post['affiliate'];
				} elseif (!empty($order_info)) {
				$this->data['affiliate'] = ($order_info['affiliate_id'] ? $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'] : '');
				} else {
				$this->data['affiliate'] = '';
			}
			
			$this->data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
			$this->load->model('sale/affiliate');
			$this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);
			
			//reject reasons
			$this->data['reject_reasons'] = $this->model_sale_reject_reason->getRejectReasons();
			
			if (isset($this->request->post['reject_reason_id'])) {
				$this->data['reject_reason_id'] = $this->request->post['reject_reason_id'];
				} elseif (!empty($order_info)) {
				$this->data['reject_reason_id'] = $order_info['reject_reason_id'];
				} else {
				$this->data['reject_reason_id'] = 0;
			}
			
			if (isset($this->request->post['order_status_id'])) {
				$this->data['order_status_id'] = $this->request->post['order_status_id'];
				} elseif (!empty($order_info)) {
				$this->data['order_status_id'] = $order_info['order_status_id'];
				} else {
				$this->data['order_status_id'] = '';
			}
			
			if ($this->data['order_status_id'] != $this->config->get('config_cancelled_status_id'))
			{
				$this->data['reject_reason_id'] = 0;
			}
			
			
			$this->data['orders_for_csi'] = [];
			foreach ($orders_for_csi = $this->model_kp_csi->getCompletedOrdersWithoutCSI($order_info['customer_id']) as $ofc){
				$ofco = $this->model_sale_order->getOrder($ofc);
				
				$this->data['orders_for_csi'][] = array(
				'order_id' => $ofco['order_id'],
				'order_status' => $this->model_localisation_order_status->getOrderStatus($ofco['order_status_id']),
				'href'     => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $ofco['order_id']),
				'last_modified' => date('d.m.Y', strtotime($ofco['date_modified']))
				);
			}
			
			$this->data['can_get_csi']  = $this->model_kp_csi->canGetCSI($this->data['order_status_id']);
			$this->data['can_edit_csi'] = $this->user->canEditCSI();
			
			$this->data['customer_csi_reject'] = 0;
			if (!empty($this->data['customer_info']['csi_reject'])){
				$this->data['customer_csi_reject'] = $this->data['customer_info']['csi_reject'];
				$this->db->query("UPDATE `order` SET csi_reject = '" . (int)$this->data['customer_csi_reject'] . "' WHERE order_id = '" . (int)$this->request->get['order_id'] . "'");
			}
			
			if (empty($order_info)){
				$this->data['csi_average'] = $this->model_kp_csi->countOrderCSI($this->request->get['order_id']);
				} else {
				$this->data['csi_average'] = $order_info['csi_average'];
			}
			
			$csi_int_fields = array(
			'csi_reject',
			'csi_mark',
			'speed_mark',
			'manager_mark',
			'quality_mark',
			'courier_mark',
			'nbt_csi',
			);
			
			$csi_text_fields = array(
			'csi_comment',
			'speed_comment',
			'manager_comment',
			'quality_comment',
			'courier_comment',
			);
			
			foreach ($csi_int_fields as $_cif) {
				if (isset($this->request->post[$_cif])) {
					$this->data[$_cif] = $this->request->post[$_cif];
					} elseif (!empty($order_info)) {
					$this->data[$_cif] = $order_info[$_cif];
					} else {
					$this->data[$_cif] = 0;
				}
			}
			
			foreach ($csi_text_fields as $_ctf) {
				if (isset($this->request->post[$_ctf])) {
					$this->data[$_ctf] = $this->request->post[$_ctf];
					} elseif (!empty($order_info)) {
					$this->data[$_ctf] = $order_info[$_ctf];
					} else {
					$this->data[$_ctf] = 0;
				}
			}
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getAllowedOrderStatuses($order_info['order_status_id']);
			//	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);
			
			$this->data['complete_order_status_id'] = $this->config->get('config_complete_status_id');
			
			if ($order_status_info) {
				$this->data['order_status'] = $order_status_info['name'];
				} else {
				$this->data['order_status'] = '';
			}
			
			if (isset($this->request->post['comment'])) {
				$this->data['comment'] = $this->request->post['comment'];
				} elseif (!empty($order_info)) {
				$this->data['comment'] = $order_info['comment'];
				} else {
				$this->data['comment'] = '';
			}
			
			//Необходимость получить данные паспорта получателя
			$need_to_have_passport_data_methods = explode(',', $this->config->get('config_confirmed_delivery_payment_ids'));
			$this->data['need_to_have_passport_data'] = (isset($order_info['shipping_code']) && !in_array($order_info['shipping_code'], $need_to_have_passport_data_methods) && $order_info['shipping_country_id'] == 176);
			
			//CUSTOM DATA
			$this->data['use_custom_dadata'] = $this->model_setting_setting->getKeySettingValue('config', 'config_dadata', (int)$order_info['store_id']);						
			$this->data['payment_custom_data'] = $this->customAddressFields(array('set' => 'payment_address', 'type' => 'order', 'id' => (int)$order_info['order_id'], 'customer_id' => $order_info['customer_id'], 'dadata_config' => $this->data['use_custom_dadata']));
			$this->data['shipping_custom_data'] = $this->customAddressFields(array('set' => 'shipping_address', 'type' => 'order', 'id' => (int)$order_info['order_id'], 'customer_id' => $order_info['customer_id'], 'dadata_config' => $this->data['use_custom_dadata']));
			
			
			$this->load->model('sale/customer');
			
			if (isset($this->request->post['customer_id'])) {
				$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
				} elseif (!empty($order_info)) {
				$this->data['addresses'] = $this->model_sale_customer->getAddresses($order_info['customer_id']);
				} else {
				$this->data['addresses'] = [];
			}
			
			if (isset($this->request->post['payment_firstname'])) {
				$this->data['payment_firstname'] = $this->request->post['payment_firstname'];
				} elseif (!empty($order_info)) {
				$this->data['payment_firstname'] = $order_info['payment_firstname'];
				} else {
				$this->data['payment_firstname'] = '';
			}
			
			if (isset($this->request->post['payment_lastname'])) {
				$this->data['payment_lastname'] = $this->request->post['payment_lastname'];
				} elseif (!empty($order_info)) {
				$this->data['payment_lastname'] = $order_info['payment_lastname'];
				} else {
				$this->data['payment_lastname'] = '';
			}
			
			if (isset($this->request->post['payment_company'])) {
				$this->data['payment_company'] = $this->request->post['payment_company'];
				} elseif (!empty($order_info)) {
				$this->data['payment_company'] = $order_info['payment_company'];
				} else {
				$this->data['payment_company'] = '';
			}
			
			if (isset($this->request->post['payment_company_id'])) {
				$this->data['payment_company_id'] = $this->request->post['payment_company_id'];
				} elseif (!empty($order_info)) {
				$this->data['payment_company_id'] = $order_info['payment_company_id'];
				} else {
				$this->data['payment_company_id'] = '';
			}
			
			if (isset($this->request->post['payment_tax_id'])) {
				$this->data['payment_tax_id'] = $this->request->post['payment_tax_id'];
				} elseif (!empty($order_info)) {
				$this->data['payment_tax_id'] = $order_info['payment_tax_id'];
				} else {
				$this->data['payment_tax_id'] = '';
			}
			
			if (isset($this->request->post['payment_address_1'])) {
				$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
				} elseif (!empty($order_info)) {
				$this->data['payment_address_1'] = $order_info['payment_address_1'];
				} else {
				$this->data['payment_address_1'] = '';
			}
			
			if (isset($this->request->post['payment_address_struct'])) {
				$this->data['payment_address_struct'] = $this->request->post['payment_address_struct'];
				} elseif (!empty($order_info)) {
				$this->data['payment_address_struct'] = $order_info['payment_address_struct'];
				} else {
				$this->data['payment_address_struct'] = '';
			}
			
			$this->data['valid_payment_address_struct'] = true;
			$this->data['suggest_get_payment_address_struct_stage_1'] = false;
			$this->data['suggest_get_payment_address_struct_stage_2'] = false;
			if ($this->data['use_custom_dadata'] == 'address'){
				if ($this->data['payment_address_1'] && (!$this->data['payment_address_struct'] || !json_decode(html_entity_decode($this->data['payment_address_struct'])))){
					$this->data['valid_payment_address_struct'] = false;
					
					$filter_custom_data = array(
					'set' 			=> 'payment_address',
					'set_short' 	=> 'payment',
					'field' 		=> 'unrestricted_value',
					'type' 			=> 'order', 
					'id' 			=> (int)$order_info['order_id'], 
					'customer_id' 	=> $order_info['customer_id'], 
					'dadata_config' => $this->data['use_custom_dadata']					
					);
					
					if ($this->customAddressFieldsOne($filter_custom_data)){
						$this->data['suggest_get_payment_address_struct_stage_1'] = true;
					}
					
					if (!$this->data['suggest_get_payment_address_struct_stage_1'] && $order_info['shipping_code'] == 'dostavkaplus.sh1'){
						$this->data['suggest_get_payment_address_struct_stage_2'] = true;
					}
				}
			}					
			
			if (isset($this->request->post['payment_address_2'])) {
				$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
				} elseif (!empty($order_info)) {
				$this->data['payment_address_2'] = $order_info['payment_address_2'];
				} else {
				$this->data['payment_address_2'] = '';
			}
			
			if (isset($this->request->post['payment_city'])) {
				$this->data['payment_city'] = $this->request->post['payment_city'];
				} elseif (!empty($order_info)) {
				$this->data['payment_city'] = $order_info['payment_city'];
				} else {
				$this->data['payment_city'] = '';
			}
			
			if (isset($this->request->post['payment_postcode'])) {
				$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
				} elseif (!empty($order_info)) {
				$this->data['payment_postcode'] = $order_info['payment_postcode'];
				} else {
				$this->data['payment_postcode'] = '';
			}
			
			if (isset($this->request->post['payment_country_id'])) {
				$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
				} elseif (!empty($order_info)) {
				$this->data['payment_country_id'] = $order_info['payment_country_id'];
				} else {
				$this->data['payment_country_id'] = '';
			}
			
			if (isset($this->request->post['payment_zone_id'])) {
				$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
				} elseif (!empty($order_info)) {
				$this->data['payment_zone_id'] = $order_info['payment_zone_id'];
				} else {
				$this->data['payment_zone_id'] = '';
			}
			
			if (isset($this->request->post['payment_method'])) {
				$this->data['payment_method'] = $this->request->post['payment_method'];
				} elseif (!empty($order_info)) {
				$this->data['payment_method'] = $order_info['payment_method'];
				} else {
				$this->data['payment_method'] = '';
			}
			
			if (isset($this->request->post['payment_code'])) {
				$this->data['payment_code'] = $this->request->post['payment_code'];
				} elseif (!empty($order_info)) {
				$this->data['payment_code'] = $order_info['payment_code'];
				} else {
				$this->data['payment_code'] = '';
			}
			
			if (isset($this->request->post['payment_secondary_method'])) {
				$this->data['payment_secondary_method'] = $this->request->post['payment_secondary_method'];
				} elseif (!empty($order_info)) {
				$this->data['payment_secondary_method'] = $order_info['payment_secondary_method'];
				} else {
				$this->data['payment_secondary_method'] = '';
			}
			
			if (isset($this->request->post['payment_secondary_code'])) {
				$this->data['payment_secondary_code'] = $this->request->post['payment_secondary_code'];
				} elseif (!empty($order_info)) {
				$this->data['payment_secondary_code'] = $order_info['payment_secondary_code'];
				} else {
				$this->data['payment_secondary_code'] = '';
			}
			
			if (isset($this->request->post['shipping_firstname'])) {
				$this->data['shipping_firstname'] = $this->request->post['shipping_firstname'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
				} else {
				$this->data['shipping_firstname'] = '';
			}
			
			if (isset($this->request->post['shipping_lastname'])) {
				$this->data['shipping_lastname'] = $this->request->post['shipping_lastname'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
				} else {
				$this->data['shipping_lastname'] = '';
			}
			
			if (isset($this->request->post['shipping_passport_serie'])) {
				$this->data['shipping_passport_serie'] = $this->request->post['shipping_passport_serie'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_passport_serie'] = $order_info['shipping_passport_serie'];
				} else {
				$this->data['shipping_passport_serie'] = '';
			}
			
			if (isset($this->request->post['shipping_passport_given'])) {
				$this->data['shipping_passport_given'] = $this->request->post['shipping_passport_given'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_passport_given'] = $order_info['shipping_passport_given'];
				} else {
				$this->data['shipping_passport_given'] = '';
			}
			
			if (isset($this->request->post['shipping_company'])) {
				$this->data['shipping_company'] = $this->request->post['shipping_company'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_company'] = $order_info['shipping_company'];
				} else {
				$this->data['shipping_company'] = '';
			}
			
			if (isset($this->request->post['shipping_address_1'])) {
				$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
				} else {
				$this->data['shipping_address_1'] = '';
			}
			
			if (isset($this->request->post['shipping_address_struct'])) {
				$this->data['shipping_address_struct'] = $this->request->post['shipping_address_struct'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_address_struct'] = $order_info['shipping_address_struct'];
				} else {
				$this->data['shipping_address_struct'] = '';
			}
			
			$this->data['valid_shipping_address_struct'] = true;
			$this->data['suggest_get_shipping_address_struct_stage_1'] = false;
			$this->data['suggest_get_shipping_address_struct_stage_2'] = false;
			if ($this->data['use_custom_dadata'] == 'address'){					
				if ($this->data['shipping_address_1'] && (!$this->data['shipping_address_struct'] || !json_decode(html_entity_decode($this->data['shipping_address_struct'])))){
					$this->data['valid_shipping_address_struct'] = false;
					
					$filter_custom_data = array(
					'set' 			=> 'shipping_address',
					'set_short' 	=> 'shipping',
					'field' 		=> 'unrestricted_value',
					'type' 			=> 'order', 
					'id' 			=> (int)$order_info['order_id'], 
					'customer_id' 	=> $order_info['customer_id'], 
					'dadata_config' => $this->data['use_custom_dadata']					
					);
					
					if ($this->customAddressFieldsOne($filter_custom_data)){
						$this->data['suggest_get_shipping_address_struct_stage_1'] = true;
					}
					
					if (!$this->data['suggest_get_shipping_address_struct_stage_1'] && $order_info['shipping_code'] == 'dostavkaplus.sh1'){
						$this->data['suggest_get_shipping_address_struct_stage_2'] = true;
					}
				}
			}
			
			
			if (isset($this->request->post['shipping_address_2'])) {
				$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
				} else {
				$this->data['shipping_address_2'] = '';
			}
			
			if (isset($this->request->post['shipping_city'])) {
				$this->data['shipping_city'] = $this->request->post['shipping_city'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_city'] = $order_info['shipping_city'];
				} else {
				$this->data['shipping_city'] = '';
			}
			
			$this->data['current_shipping_time'] = false;
			$this->data['current_payment_time'] = false;
			if ($this->data['shipping_city'] || $this->data['payment_city']){
				if ($this->data['shipping_city'] == $this->data['payment_city']) {
					$this->data['current_shipping_time'] = $this->data['current_payment_time'] = $this->model_kp_geoip->getCurrentTimeInCity($this->data['shipping_city']);				
					} else { 
					if ($this->data['shipping_city']){
						$this->data['current_shipping_time'] = $this->model_kp_geoip->getCurrentTimeInCity($this->data['shipping_city']);	
					}
					
					if ($this->data['payment_city']){
						$this->data['current_payment_time'] = $this->model_kp_geoip->getCurrentTimeInCity($this->data['payment_city']);	
					}
				}
			}
			
			if ($this->data['current_shipping_time']){
				$this->data['can_call_now'] = $this->model_kp_geoip->canCallNow($this->data['current_shipping_time']);
				} else {
				$this->data['can_call_now'] = false;
			}
			
			
			
			//var_dump($this->data['custom_payment_address']);
			
			if (isset($this->request->post['shipping_postcode'])) {
				$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
				} else {
				$this->data['shipping_postcode'] = '';
			}
			
			if (isset($this->request->post['shipping_country_id'])) {
				$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
				} else {
				$this->data['shipping_country_id'] = '';
			}
			
			if (isset($this->request->post['shipping_zone_id'])) {
				$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
				} else {
				$this->data['shipping_zone_id'] = '';
			}
			
			if (isset($this->request->post['ttn'])) {
				$this->data['ttn'] = $this->request->post['ttn'];
				} elseif (!empty($order_info)) {
				$this->data['ttn'] = $order_info['ttn'];
				} else {
				$this->data['ttn'] = '';
			}
			
			if (isset($this->request->post['bottom_text'])) {
				$this->data['bottom_text'] = $this->request->post['bottom_text'];
				} elseif (!empty($order_info)) {
				$this->data['bottom_text'] = $order_info['bottom_text'];
				} else {
				$this->data['bottom_text'] = '';
			}
			
			$this->load->model('localisation/country');			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			$this->load->model('localisation/legalperson');	
			$this->data['legalpersons'] = [];
			if (!$this->data['payment_country_id'] && $this->data['shipping_country_id']) {
				$this->data['legalpersons'] 	= $this->model_localisation_legalperson->getLegalPersonsByCountryID($this->data['shipping_country_id']);
				$this->data['cards'] 			= $this->model_localisation_legalperson->getLegalPersonsByCountryID($this->data['shipping_country_id'], true);
				$this->data['all_legalpersons'] = $this->model_localisation_legalperson->getAllLegalPersonsByCountryID($this->data['shipping_country_id']);
				} elseif ($this->data['payment_country_id']) {
				$this->data['legalpersons'] 	= $this->model_localisation_legalperson->getLegalPersonsByCountryID($this->data['payment_country_id']);
				$this->data['cards'] 			= $this->model_localisation_legalperson->getLegalPersonsByCountryID($this->data['payment_country_id'], true);
				$this->data['all_legalpersons'] = $this->model_localisation_legalperson->getAllLegalPersonsByCountryID($this->data['payment_country_id']);
				} else {
				$this->data['legalpersons'] 	= $this->model_localisation_legalperson->getLegalPersonsSimpleOrNot();
				$this->data['cards'] 			= $this->model_localisation_legalperson->getLegalPersonsSimpleOrNot(true);
				$this->data['all_legalpersons'] = $this->model_localisation_legalperson->getLegalPersons();
			}											
			
			foreach ($this->data['legalpersons'] as &$_lp){
				$_lp['info'] = $this->model_localisation_legalperson->getLegalPersonMonthlyTotals($_lp['legalperson_id']);
				
				$_lp['info']['total_already_paid'] = $this->currency->format($_lp['info']['total_already_paid'], $order_info['currency_code'], 1);
				$_lp['info']['total_need_to_pay']  = $this->currency->format($_lp['info']['total_need_to_pay'], $order_info['currency_code'], 1);
				$_lp['info']['sum']  			   = $this->currency->format($_lp['info']['sum'], $order_info['currency_code'], 1);
			}
			
			foreach ($this->data['legalpersons'] as &$_lp2){
				$_unsdata = isset($_lp2['account_info'])?unserialize($_lp2['account_info']):'';	
				
				if (isset($_unsdata['EndingBalance'])){
					$_lp2['info']['at_this_moment'] = $this->currency->format($_unsdata['EndingBalance'], $order_info['currency_code'], 1);
					} else {
					$_lp2['info']['at_this_moment'] = false;
				}
			}
			unset($_lp2);			
			
			if (isset($this->request->post['legalperson_id'])) {
				$this->data['legalperson_id'] = $this->request->post['legalperson_id'];
				} elseif (!empty($order_info)) {
				$this->data['legalperson_id'] = $order_info['legalperson_id'];
				} else {
				$this->data['legalperson_id'] = '';
			}
			
			$this->data['current_legalperson'] = false;
			foreach ($this->data['legalpersons'] as $_lp3){
				if ($_lp3['legalperson_id'] == $this->data['legalperson_id']){
					$this->data['current_legalperson'] = $_lp3;
				}			
			}		
			
			if (isset($this->request->post['card_id'])) {
				$this->data['card_id'] = $this->request->post['card_id'];
				} elseif (!empty($order_info)) {
				$this->data['card_id'] = $order_info['card_id'];
				} else {
				$this->data['card_id'] = '';
			}
						
			//попытка угадать кассу для внесения средств, чтоб поставить ее по умолчанию
			$this->data['guessed_transaction_legalperson_id'] = 0;
			if ($this->data['card_id']){
				$this->data['guessed_transaction_legalperson_id'] = $this->data['card_id'];
			}
			
			if ($this->data['legalperson_id']){
				$this->data['guessed_transaction_legalperson_id'] = $this->data['legalperson_id'];
			}
			
			if (isset($this->request->post['shipping_method'])) {
				$this->data['shipping_method'] = $this->request->post['shipping_method'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_method'] = $order_info['shipping_method'];
				} else {
				$this->data['shipping_method'] = '';
			}
			
			if (isset($this->request->post['shipping_code'])) {
				$this->data['shipping_code'] = $this->request->post['shipping_code'];
				} elseif (!empty($order_info)) {
				$this->data['shipping_code'] = $order_info['shipping_code'];
				} else {
				$this->data['shipping_code'] = '';
			}
			
			$this->data['current_shipping_time'] = false;
			$this->data['current_payment_time'] = false;
			if ($this->data['shipping_city'] || $this->data['payment_city']){
				if ($this->data['shipping_city'] == $this->data['payment_city']) {
					$this->data['current_shipping_time'] = $this->data['current_payment_time'] = $this->model_kp_geoip->getCurrentTimeInCity($this->data['shipping_city'], $this->model_localisation_country->getCountryISO2($this->data['shipping_country_id']));				
				} else { 
					if ($this->data['shipping_city']){
						$this->data['current_shipping_time'] = $this->model_kp_geoip->getCurrentTimeInCity($this->data['shipping_city'], $this->model_localisation_country->getCountryISO2($this->data['shipping_country_id']));	
					}
					
					if ($this->data['payment_city']){
						$this->data['current_payment_time'] = $this->model_kp_geoip->getCurrentTimeInCity($this->data['payment_city'], $this->model_localisation_country->getCountryISO2($this->data['payment_country_id']));	
					}
				}
			}
			
			
			//SMS HISTORY
			$sms_date = $this->model_sale_order->getOrderDeliverySMS($order_id);
			if ($sms_date){
				$this->data['delivery_sms_sent'] = '<span style="color:green;font-weight:700;">Отправлено в '.date('d.m.Y в H:i', strtotime($sms_date)).'</span>';
				} else {
				$this->data['delivery_sms_sent'] = '<span style="color:red;font-weight:700;">Еще не отправляли</span>';
			}
			
			
			//Пытаемся подобрать необходимый шаблон
			$bottom_text_template_try = '';
			
			$array_msk = array('Москва','г.Москва','г Москва','город Москва','Moscow','москва');
			$array_ky = array('Киев', 'г. Киев', 'г.Киев', 'Kyiv', 'Kiev', 'kyiv', 'kiev', 'киев', 'київ', 'Київ', 'м.Київ', 'м. Київ', 'Києв', 'м. Києв', 'г. Київ');
			
			$this->data['is_bad_good_try'] = false;			
			if ($this->data['shipping_country_id'] == 176){
				if (in_array($this->data['shipping_city'], $array_msk)){
					if ($this->data['shipping_code'] == 'dostavkaplus.sh12') {
						$bottom_text_template_try = 'MOSCOW_PICKUP';
						} elseif (strpos($this->data['shipping_code'], 'point_') > 0) {
						if ($this->data['payment_code'] == 'cod') {
							//полная предоплата
							$bottom_text_template_try = 'MOSCOW_PICKUP_FULL_PREPAY';
							} elseif ($this->orderHasPrepay($this->request->get['order_id'])) {
							//частичная предоплата
							$bottom_text_template_try = 'MOSCOW_PICKUP_PARTIAL_PREPAY';
							} else {
							//просто самовывоз
							$bottom_text_template_try = 'MOSCOW_PICKUP';
						}
						} else {
						if ($this->data['payment_code'] == 'cod') {
							//полная предоплата курьер
							$bottom_text_template_try = 'MOSCOW_COURIER_FULL_PREPAY';
							} elseif ($this->orderHasPrepay($this->request->get['order_id'])) {
							//частичная предоплата курьер
							$bottom_text_template_try = 'MOSCOW_COURIER_PARTIAL_PREPAY';
							} else {
							//просто курьер
							$bottom_text_template_try = 'MOSCOW_COURIER_CASH';
						}
					}
					} else {
					if ($this->data['payment_code'] == 'cod') {
						$bottom_text_template_try = 'RU_TRANSPORTER_FULL_PREPAY';	
						} else {
						$bottom_text_template_try = 'RU_TRANSPORTER_PARTIAL_PREPAY';	
					}		
				}
				
				
				if ($order_info['total'] <= 5000){
					$this->data['is_bad_good_try'] = true;			
					} else {
					$this->data['is_bad_good_try'] = false;			
				}
				
				} elseif ($this->data['shipping_country_id'] == 220) {
				if (in_array($this->data['shipping_city'], $array_ky)){
					if ($this->data['payment_code'] == 'cod') {
						//полная предоплата курьер
						$bottom_text_template_try = 'KYIV_COURIER_FULL_PREPAY';
						} elseif ($this->orderHasPrepay($this->request->get['order_id'])) {
						//частичная предоплата курьер
						$bottom_text_template_try = 'KYIV_COURIER_PARTIAL_PREPAY';
						} else {
						//просто курьер, наличка
						$bottom_text_template_try = 'KYIV_COURIER_CASH';
					}				
					} else {
					if ($this->data['payment_code'] == 'cod') {
						$bottom_text_template_try = 'UA_TRANSPORTER_FULL_PREPAY';	
						} else {
						$bottom_text_template_try = 'UA_TRANSPORTER_PARTIAL_PREPAY';	
					}	
				}	
				
				} elseif ($this->data['shipping_country_id'] == 20) {				
				} elseif ($this->data['shipping_country_id'] == 109) {				
				} elseif ($this->data['shipping_country_id'] == 81) {
			}
			
			$this->data['bottom_text_template_try'] = $bottom_text_template_try;


			$this->data['payment_links'] = [];
			foreach ($this->model_sale_order->getEquiringMethods() as $equiring_method){
				if ($this->config->get($equiring_method . '_status') || $this->config->get($equiring_method . '_status_fake')){
					$this->data['payment_links'][$equiring_method] = [
						'qr_code' => $this->model_sale_order->generatePaymentQR($order_id, $equiring_method),
						'qr_link' => $this->model_sale_order->generatePaymentLink($order_id, $equiring_method),
						'qr_sms'  => $this->smsAdaptor->getPaymentLinkText($order_info, ['payment_link' => $this->model_sale_order->generatePaymentLink($order_id, $equiring_method), 'amount' => $this->getOrderTotalTotal($this->request->get['order_id'])])
					];
				}				
			}
			
			if (isset($this->request->post['order_product'])) {
				$order_products = $this->request->post['order_product'];
			} elseif (isset($this->request->get['order_id'])) {
				$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);			
			} else {
				$order_products = [];
			}
						
			$this->load->model('catalog/product');
			$this->load->model('sale/return');
			
			$this->document->addScript('view/javascript/jquery/ajaxupload.js');
			
			$order_products_nogood = $this->model_sale_order->getOrderProductsNoGood($this->request->get['order_id']);
			$order_products_untaken = $this->model_sale_order->getOrderUntakenProducts($this->request->get['order_id']);
			
			$return_filter_data = array(
			'filter_order_id' => (int)$this->request->get['order_id']
			);
			$order_products_return = $this->model_sale_return->getReturns($return_filter_data);
			
			
			$this->data['order_products'] = [];
			$this->data['order_products_nogood'] = [];
			$this->data['order_products_untaken'] = [];
			$this->data['order_products_return'] = [];			
			
			
			foreach ($order_products_return as $order_product_return){
				
				$real_product = $this->model_catalog_product->getProduct($order_product_return['product_id']);
				
				$this->data['order_products_return'][] = array(
				'return_id' 	=> $order_product_return['return_id'],
				'reorder_id' 	=> $order_product_return['reorder_id']?$order_product_return['reorder_id']:'',
				'product_id'	=> $order_product_return['product_id'],
				'to_supplier'	=> $order_product_return['to_supplier'],
				'product_adminlink' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $order_product_return['product_id'], 'SSL'),
				'name'  			=> $order_product_return['product'],
				'model'  			=> $order_product_return['model'],
				'ean'  				=> $real_product['ean'],
				'image'    	    	=> $this->model_tool_image->resize($real_product['image'], 40, 40),
				'quantity'			=> $order_product_return['quantity'],
				'price' 			=> $this->currency->format($order_product_return['price_national'], $order_info['currency_code'], 1),
				'pricewd_national' 	=> $this->currency->format($order_product_return['pricewd_national'], $order_info['currency_code'], 1),
				'total' 			=> $this->currency->format($order_product_return['total_national'], $order_info['currency_code'], 1),
				'totalwd_national' 	=> $this->currency->format($order_product_return['totalwd_national'], $order_info['currency_code'], 1),
				'status' 			=> $order_product_return['status'],
				'reason' 			=> $order_product_return['reason'],
				'date_added' 		=> date('d.m.Y', strtotime($order_product_return['date_added'])),
				'edit' 				=> $this->url->link('sale/return/info', 'token=' . $this->session->data['token'] . '&return_id=' . $order_product_return['return_id'], 'SSL')
				);
			}
			
			$just_product_ids = [];
			foreach ($order_products as $order_product) {
				$just_product_ids[] = $order_product['product_id'];
			}
			
			
			//НЕВЗЯТЫЕ ТОВАРЫ, НА КОТОРЫЕ НУЖНО ОФОРМИТЬ ВОЗВРАТЫ
			foreach ($order_products_untaken as $order_product) {
				if (isset($order_product['order_option'])) {
					$order_option = $order_product['order_option'];
					} elseif (isset($this->request->get['order_id'])) {
					$order_option = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
					} else {
					$order_option = [];
				}
				
				if (isset($order_product['order_download'])) {
					$order_download = $order_product['order_download'];
					} elseif (isset($this->request->get['order_id'])) {
					$order_download = $this->model_sale_order->getOrderDownloads($this->request->get['order_id'], $order_product['order_product_id']);
					} else {
					$order_download = [];
				}
				
				if ($order_product['price_national'] > 0){
					$price_txt = $this->currency->format($order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1');				
					} else {
					$order_product['price_national'] = $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value'], false);
					
					$price_txt = $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);				
				}
				
				if ($order_product['total_national'] > 0){
					$total_txt =  $this->currency->format($order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], '1');				
					} else {
					$order_product['total_national'] = $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'], false);		
					
					$total_txt = $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);				
				}
				
				if ($order_product['new_order_id']){
					if ($new_order_info = $this->model_sale_order->getOrder($order_product['new_order_id'])){
						$new_order_href = $this->url->link('sale/order/update', 'order_id='.$order_product['new_order_id'].'&token='.$this->session->data['token']);
						} else {
						$order_product['new_order_id'] = false;
						$new_order_href = false;
					}
					} else {
					$new_order_href = false;
				}
				
				//child products for set
				$is_set = $this->model_catalog_product->getThisProductIsSet($order_product['product_id']);
				if ($is_set){
					$set_id = (int)$is_set['set_id'];
					$set_products_results = $this->model_sale_order->getOrderProductsBySet($order_id, $set_id);
					
					$set_products = [];
					
					foreach ($set_products_results as $set_product_result){
						$set_product_info = $this->model_catalog_product->getProduct($set_product_result['product_id']);
						
						$set_products[] = array(
						'product_id'       => $set_product_result['product_id'],
						'name'             => $set_product_result['name'],
						'short_name' 	   => $set_product_info['short_name'],
						'image'    	 	   => $this->model_tool_image->resize($set_product_result['image'], 40, 40),
						'model'            => $set_product_result['model'],
						'ean'  			   => $set_product_info['ean'],
						'quantity'         => $set_product_result['quantity'],
						'price_national'   => $set_product_result['price_national'],
						'total_national'   => $set_product_result['total_national'],
						'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $set_product_result['product_id'], 'SSL'),
						);
					}
					
					} else {
					$set_products = false;
				}
				
				//additional_offer
				if ($order_product['ao_id']){
					$ao_info = $this->model_catalog_product->getAdditionalOfferById($order_product['ao_id'], $order_product['product_id']);							
					
					if (!$ao_info || count($ao_info) == 0) {
						$ao_text = '<span style="color:red">Не могу найти условия! Возможно, спецпредложение изменилось!</span>';
						$ao_text = false;
						} else {
						
						$ao_main_product = $this->model_catalog_product->getProduct($ao_info['product_id']);
						
						$ao_text = 'Спецпредложение к товару <b>'.$ao_main_product['model'].'</b>, ';
						if ($ao_info['price'] > 0){
							$ao_text .= 'цена: <b>'.$this->currency->format($ao_info['price'],$order_info['currency_code'], 1).'</b>';
							} else {
							$ao_text .= 'скидка: <b>'.$ao_info['percent'].'%</b>';
						}			
						
						if (in_array($ao_info['product_id'], $just_product_ids)){
							$ao_text .= "<br /><span style='color:green'>Основной товар есть в заказе!</span>";
							} else {
							$ao_text .= "<br /><span style='color:red'>Основного товара нет в заказе!</span>";
						}
						
					}
					} else {
					$ao_text = false;
				}
				
				$real_untaken_product = $this->model_catalog_product->getProduct($order_product['product_id']);
				
				$this->data['order_products_untaken'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'name'             => $order_product['name'],
				'image'    	 	   => $this->model_tool_image->resize($order_product['image'], 40, 40),
				'model'            => $order_product['model'],
				'ean' 			   => isset($real_untaken_product['ean'])?$real_untaken_product['ean']:'',
				'good'             => $order_product['good'],
				'onsite'           => ($order_product['stock_status_id'] == $this->config->get('config_stock_status_id'))?true:false,
				'new_order_id'     => $order_product['new_order_id'],
				'new_order_href'   => $new_order_href,
				'product_waitlist_href' => $this->url->link('catalog/waitlist', 'filter_product_id='.$order_product['product_id'].'&token='.$this->session->data['token']),
				'order_waitlist_href' => $this->url->link('catalog/waitlist', 'filter_order_id='.$order_product['order_id'].'&token='.$this->session->data['token']),
				'taken'            => $order_product['taken'],
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'price'            => $order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0),
				'price_national'   => $order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0),
				'total'            => $order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0),
				'total_national'   => $order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0),
				'price_txt'        => $price_txt,
				'total_txt'        => $total_txt,
				'tax'              => $order_product['tax'],
				'reward'           => $order_product['reward'],
				'waitlist'		   => $order_product['waitlist'],
				'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $order_product['product_id'], 'SSL'),
				'set' 			   => $set_products,
				'ao_id' 			=> $order_product['ao_id'],
				'ao_product_id' 	=> $order_product['ao_product_id'],
				'ao_text' 			=> $ao_text,
				
				);
			}
			
			
			//ОТМЕНЕННЫЕ ТОВАРЫ
			unset($order_product);
			foreach ($order_products_nogood as $order_product) {
				if (isset($order_product['order_option'])) {
					$order_option = $order_product['order_option'];
					} elseif (isset($this->request->get['order_id'])) {
					$order_option = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
					} else {
					$order_option = [];
				}
				
				if (isset($order_product['order_download'])) {
					$order_download = $order_product['order_download'];
					} elseif (isset($this->request->get['order_id'])) {
					$order_download = $this->model_sale_order->getOrderDownloads($this->request->get['order_id'], $order_product['order_product_id']);
					} else {
					$order_download = [];
				}
				
				if ($order_product['price_national'] > 0){
					$price_txt = $this->currency->format($order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1');				
					} else {
					$order_product['price_national'] = $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value'], false);
					
					$price_txt = $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);				
				}
				
				if ($order_product['total_national'] > 0){
					$total_txt =  $this->currency->format($order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], '1');				
					} else {
					$order_product['total_national'] = $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'], false);		
					
					$total_txt = $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);				
				}
				
				if ($order_product['new_order_id']){
					if ($new_order_info = $this->model_sale_order->getOrder($order_product['new_order_id'])){
						$new_order_href = $this->url->link('sale/order/update', 'order_id='.$order_product['new_order_id'].'&token='.$this->session->data['token']);
						} else {
						$order_product['new_order_id'] = false;
						$new_order_href = false;
					}
					} else {
					$new_order_href = false;
				}
				
				//child products for set
				$is_set = $this->model_catalog_product->getThisProductIsSet($order_product['product_id']);
				if ($is_set){
					$set_id = (int)$is_set['set_id'];
					$set_products_results = $this->model_sale_order->getOrderProductsBySet($order_id, $set_id);
					
					$set_products = [];
					
					foreach ($set_products_results as $set_product_result){
						$set_product_info = $this->model_catalog_product->getProduct($set_product_result['product_id']);
						
						$set_products[] = array(
						'product_id'       => $set_product_result['product_id'],
						'name'             => $set_product_result['name'],
						'short_name' 	   => $set_product_info['short_name'],
						'image'    	 	   => $this->model_tool_image->resize($set_product_result['image'], 40, 40),
						'model'            => $set_product_result['model'],
						'ean'  			   => $set_product_info['ean'],
						'quantity'         => $set_product_result['quantity'],
						'price_national'   => $set_product_result['price_national'],
						'total_national'   => $set_product_result['total_national'],
						'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $set_product_result['product_id'], 'SSL'),
						);
					}
					
					} else {
					$set_products = false;
				}
				
				//additional_offer
				if ($order_product['ao_id']){
					$ao_info = $this->model_catalog_product->getAdditionalOfferById($order_product['ao_id'], $order_product['product_id']);							
					
					if (!$ao_info || count($ao_info) == 0) {
						$ao_text = '<span style="color:red">Не могу найти условия! Возможно, спецпредложение изменилось!</span>';
						$ao_text = false;
						} else {
						
						$ao_main_product = $this->model_catalog_product->getProduct($ao_info['product_id']);
						
						$ao_text = 'Спецпредложение к товару <b>'.$ao_main_product['model'].'</b>, ';
						if ($ao_info['price'] > 0){
							$ao_text .= 'цена: <b>'.$this->currency->format($ao_info['price'],$order_info['currency_code'], 1).'</b>';
							} else {
							$ao_text .= 'скидка: <b>'.$ao_info['percent'].'%</b>';
						}			
						
						if (in_array($ao_info['product_id'], $just_product_ids)){
							$ao_text .= "<br /><span style='color:green'>Основной товар есть в заказе!</span>";
							} else {
							$ao_text .= "<br /><span style='color:red'>Основного товара нет в заказе!</span>";
						}
						
					}
					} else {
					$ao_text = false;
				}
				
				$real_nogood_product = $this->model_catalog_product->getProduct($order_product['product_id']);
				
				$this->data['order_products_nogood'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'name'             => $order_product['name'],
				'image'    	 	   => $this->model_tool_image->resize($order_product['image'], 40, 40),
				'model'            => $order_product['model'],
				'ean' 			   => isset($real_nogood_product['ean'])?$real_nogood_product['ean']:'',
				'good'             => $order_product['good'],
				'onsite'           => ($order_product['stock_status_id'] == $this->config->get('config_stock_status_id'))?true:false,
				'new_order_id'     => $order_product['new_order_id'],
				'new_order_href'   => $new_order_href,
				'product_waitlist_href' => $this->url->link('catalog/waitlist', 'filter_product_id='.$order_product['product_id'].'&token='.$this->session->data['token']),
				'order_waitlist_href' => $this->url->link('catalog/waitlist', 'filter_order_id='.$order_product['order_id'].'&token='.$this->session->data['token']),
				'taken'            => $order_product['taken'],
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'price'            => $order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0),
				'price_national'   => $order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0),
				'total'            => $order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0),
				'total_national'   => $order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0),
				'price_txt'        => $price_txt,
				'total_txt'        => $total_txt,
				'tax'              => $order_product['tax'],
				'reward'           => $order_product['reward'],
				'waitlist'		   => $order_product['waitlist'],
				'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $order_product['product_id'], 'SSL'),
				'set' 			   => $set_products,
				'ao_id' 			=> $order_product['ao_id'],
				'ao_product_id' 	=> $order_product['ao_product_id'],
				'ao_text' 			=> $ao_text,
				
				);
			}
			
			
			if (isset($this->request->post['order_total'])) {
				$this->data['order_totals'] = $this->request->post['order_total'];
				} elseif (isset($this->request->get['order_id'])) {
				$this->data['order_totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
				} else {
				$this->data['order_totals'] = [];
			}
			
			foreach ($this->data['order_totals'] as &$order_total_tmp){
				$order_total_tmp['value_national'] = (float)str_replace('.0000', '', $order_total_tmp['value_national']);
			}
			
			$order_has_birthday_discount = false;
			$__birthday_discount_percent = false;
			foreach ($this->data['order_totals'] as $o_total){
				if ($o_total['code'] == 'discount_regular' || bool_real_stripos($o_total['title'], 'рождения')){
					$order_has_birthday_discount = true;
					$__birthday_discount_percent = (int)preg_replace('~[^0-9]+~','', $o_total['title']);
					break;
				}							
			}
			$this->data['order_has_birthday_discount'] = $order_has_birthday_discount;
			$this->data['birthday_discount_percent'] = $__birthday_discount_percent;
			unset($o_total);
			
			$order_has_reward = false;		
			foreach ($this->data['order_totals'] as $o_total){
				if ($o_total['code'] == 'reward'){
					$reward_amount = explode('(', $o_total['title']);
					$reward_amount = isset($reward_amount[1])?$reward_amount[1]:'';
					$reward_amount = trim(str_replace(')', '', $reward_amount));
					$order_has_reward = $reward_amount;
					break;
				}
			}
			
			$this->data['order_has_reward'] = $order_has_reward;
			
			unset($o_total);
			$order_has_coupon = false;		
			foreach ($this->data['order_totals'] as $o_total){
				if ($o_total['code'] == 'coupon'){
					$coupon_name = explode('(', $o_total['title']);
					$coupon_name = isset($coupon_name[1])?$coupon_name[1]:'';
					$coupon_name = trim(str_replace(')', '', $coupon_name));
					$order_has_coupon = $coupon_name;
					
					
					break;
				}
			}
			
			if ($order_has_coupon){									
				$coupon_active_products = $this->model_sale_order->getCouponProducts($order_has_coupon, $just_product_ids, $this->request->get['order_id']);	
				
				$coupon_query = $this->db->query("SELECT * FROM `coupon` WHERE code = '" . $this->db->escape($order_has_coupon) . "'");
				
				$this->data['order_has_coupon'] = $coupon_query->row;
				
				
				$coupon_active_query = $this->db->query("SELECT * FROM `coupon` WHERE code = '" . $this->db->escape($order_has_coupon) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");			
				$this->data['order_has_coupon_is_active'] = ($coupon_active_query->num_rows);
				
				} else {
				$coupon_active_products = [];
				
				$this->data['order_has_coupon'] = false;
				$this->data['order_has_coupon_is_active'] = false;
			}
			
			$order_has_cumulative_discount = false;
			foreach ($this->data['order_totals'] as $o_total){
				if ($o_total['code'] == 'shoputils_cumulative_discounts' || bool_real_stripos($o_total['title'], 'накопительная')){
					$scd_discount = $this->load->model_total_scd_recount->getCustomerDiscount($order_info['store_id'], $order_info['customer_group_id'], $order_info['language_id'], $order_info['customer_id']);
					
					$_scd_discount_percent = (int)preg_replace('~[^0-9]+~','', $o_total['title']);
					
					if (is_numeric($_scd_discount_percent) && ($scd_discount['percent'] != $_scd_discount_percent)) {
						$this->data['overload_scd_discount_percent'] = $scd_discount['percent'];
						$scd_discount['percent'] = $_scd_discount_percent;
						$this->data['now_scd_discount_percent'] = $scd_discount['percent'];
					}
					
					$order_has_cumulative_discount = true;				
					break;
				}			
			}
			
			if ($order_has_cumulative_discount){
				if (!isset($scd_discount['excluded_manufacturers'])){
					$excluded_manufacturers = [];
					} else {
					$excluded_manufacturers = explode(',', str_replace(',,',',',mb_substr($scd_discount['excluded_manufacturers'],0,-1)));
				}
				$this->data['cumulative_discount_percent'] = (int)$scd_discount['percent'];
				$this->data['order_has_cumulative_discount'] = true;
				
				} else {
				$excluded_manufacturers = [];
				$this->data['cumulative_discount_percent'] = false;
				$this->data['order_has_cumulative_discount'] = false;
				
			}
			
			$total_pricewd_total_national = 0;
			
			$tmp_order_products = $order_products;
			
			$array_colors = array(
			'#ecafa9',
			'#d4ffaa',
			'#abce8e',
			'#dbd5a2',
			'#e0edd5',
			'#cccccc',
			'#aaff56',
			'#ff5656',
			'#007fff',
			'#999999',
			'#ffff00',
			'#7fff00',
			'#ffaa56',
			'#f990c3',
			'#ff7f00',
			'#ffd4aa',
			'#a2daf2'
			);
			$color_counter = 0;
			
			foreach ($order_products as $order_product) {
				if (isset($order_product['order_option'])) {
					$order_option = $order_product['order_option'];
					} elseif (isset($this->request->get['order_id'])) {
					$order_option = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
					} else {
					$order_option = [];
				}
				
				if (isset($order_product['order_download'])) {
					$order_download = $order_product['order_download'];
					} elseif (isset($this->request->get['order_id'])) {
					$order_download = $this->model_sale_order->getOrderDownloads($this->request->get['order_id'], $order_product['order_product_id']);
					} else {
					$order_download = [];
				}
				
				$_colored_similar = false;
				foreach ($tmp_order_products as $tmp_order_product){
					if ($order_product['product_id'] == $tmp_order_product['product_id'] && $order_product['order_product_id'] != $tmp_order_product['order_product_id']){
						$_colored_similar = $array_colors[$order_product['product_id'] % count($array_colors)];		
					}
				}
				
				if ($order_product['price_national'] > 0){
					$price_txt = $this->currency->format($order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1');					
					} else {
					$order_product['price_national'] = $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value'], false);
					
					$price_txt = $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);				
				}
				
				if ($order_product['total_national'] > 0){
					$total_txt =  $this->currency->format($order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], '1');				
					} else {
					$order_product['total_national'] = $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'], false);		
					
					$total_txt = $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);				
				}
				
				
				
				//child products for set
				$is_set = $this->model_catalog_product->getThisProductIsSet($order_product['product_id']);
				if ($is_set){
					$set_id = (int)$is_set['set_id'];
					$set_products_results = $this->model_sale_order->getOrderProductsBySet($order_id, $set_id);
					
					$set_products = [];
					
					foreach ($set_products_results as $set_product_result){
						$real_product = $this->model_catalog_product->getProduct($set_product_result['product_id']);
						
						
						//prices for SET products
						$price_in_base_real = $this->currency->format($this->model_catalog_product->getProductPrice($set_product_result['product_id']), $this->config->get('config_currency'), 1);
						
						$price_in_base_real_num = $this->model_catalog_product->getProductPrice($set_product_result['product_id']);
						
						$price_in_eur = $this->currency->format($this->currency->convert($set_product_result['price_national'], $order_info['currency_code'], $this->config->get('config_currency')),  $this->config->get('config_currency'), 1);
						$price_in_eur_num = $this->currency->convert($set_product_result['price_national'], $order_info['currency_code'], $this->config->get('config_currency'));
						
						$price_in_eur_by_real_cource = $this->currency->format($this->currency->convert($set_product_result['price_national'], $order_info['currency_code'], $this->config->get('config_currency'), true),  $this->config->get('config_currency'), 1);
						$price_in_eur_by_real_cource_num = $this->currency->convert($set_product_result['price_national'], $order_info['currency_code'], $this->config->get('config_currency'), true);			
						
						$total_in_eur = $this->currency->format($this->currency->convert($set_product_result['total_national'], $order_info['currency_code'], $this->config->get('config_currency')),  $this->config->get('config_currency'), 1);
						
						$buy_max_coef = $this->model_kp_price->guessCostByPrice($price_in_eur_by_real_cource_num, true);
						$buy_max_price =  $this->currency->format($this->model_kp_price->guessCostByPrice($price_in_eur_by_real_cource_num, false), $this->config->get('config_currency'), 1);
						$buy_max_price_num = $this->model_kp_price->guessCostByPrice($price_in_eur_by_real_cource_num, false);
						
						$real_difference = $this->currency->format($price_in_eur_by_real_cource_num - $buy_max_price_num, $this->config->get('config_currency'), 1);
						
						if ($this->model_catalog_product->getProductSpecialOne($set_product_result['product_id'])) {
							$special_in_base_real = $this->currency->format($this->model_catalog_product->getProductSpecialOne($set_product_result['product_id']), $this->config->get('config_currency'), 1);
							$special_in_base_real_num = $this->model_catalog_product->getProductSpecialOne($set_product_result['product_id']);
							} else {
							$special_in_base_real = false;				
						}
						
						if (!$special_in_base_real){
							
							if (abs($price_in_eur - round($price_in_base_real_num)) >= 3){				
								$price_changed_upon_order_start = true;									
								} else {								
								$price_changed_upon_order_start = false;
							}
							
							
							
							} else {
							
							if (abs($price_in_eur - round($special_in_base_real_num)) >= 3){				
								$price_changed_upon_order_start = true;									
								} else {								
								$price_changed_upon_order_start = false;
							}																
						}
						
						$is_in_stock = ($real_product['quantity_stock'] + $real_product['quantity_stockK'] + $real_product['quantity_stockM'] + $real_product['quantity_stockMN'] + $real_product['quantity_stockAS']); 
						
						$is_in_stock_in_country = false; 
						
						if ($order_info['shipping_country_id'] == 176) {
							
							$is_in_stock_in_country = ($real_product['quantity_stockM'] > 0 && $real_product['quantity_stockM'] <= $order_product['quantity']);
							
							} elseif ($order_info['shipping_country_id'] == 220) {
							
							$is_in_stock_in_country = ($real_product['quantity_stockK'] > 0 && $real_product['quantity_stockK'] <= $order_product['quantity']);
							
							} elseif ($order_info['shipping_country_id'] == 109) {
							
							$is_in_stock_in_country = ($real_product['quantity_stockAS'] > 0 && $real_product['quantity_stockAS'] <= $order_product['quantity']);
							
							} elseif ($order_info['shipping_country_id'] == 20) {
							
							$is_in_stock_in_country = ($real_product['quantity_stockAS'] > 0 && $real_product['quantity_stockAS'] <= $order_product['quantity']);
							
							} elseif ($order_info['shipping_country_id'] == 81) {
							
							$is_in_stock_in_country = ($real_product['quantity_stock'] > 0 && $real_product['quantity_stock'] <= $order_product['quantity']);
							
						}
						
						//для закупки
						$supplies = [];
						$supplies = $this->model_sale_supplier->getOPSupplyForSet($set_product_result['order_set_id']);
						
						foreach ($supplies as &$_supply){
							$_sn = $this->model_sale_supplier->getSupplier($_supply['supplier_id']);
							$_supply['supplier_name'] = $_sn['supplier_name'] . ' : ' . $_sn['supplier_type'];
							
							$_supply['total'] = $_supply['price'] * $_supply['amount'];
							
							if ($this->data['currency_code'] == $_supply['currency']){					
								$_supply['total_in_order_currency'] = $_supply['total'];
								} else {
								if ($_supply['total'] > 0) {
									$_supply['total_in_order_currency'] = $this->currency->convert($_supply['total'], $_supply['currency'], $this->data['currency_code']);
									} else {
									$_supply['total_in_order_currency'] = 0;
								}
							}
						}
						
						$set_products[] = array(
						'product_id'       => $set_product_result['product_id'],
						'order_set_id'     => $set_product_result['order_set_id'],
						'set_id'       	   => $set_product_result['set_id'],
						'name'             => $set_product_result['name'],
						'supplies'         => $supplies,
						'is_in_stock'      => $is_in_stock,						
						'is_in_stock_in_country' => $is_in_stock_in_country,
						'image'    	 	   => $this->model_tool_image->resize($set_product_result['image'], 40, 40),
						'model'            => $set_product_result['model'],
						'ean'  				=> $set_product_result['ean'],
						'quantity'         => $set_product_result['quantity'],						
						'price_national'   => str_replace('.00','',$set_product_result['price_national']),
						'total_national'   => str_replace('.00','',$set_product_result['total_national']),
						'real_product'     => $real_product,
						'quantity_stock'   => $real_product['quantity_stock'],
						'quantity_stockM'   => $real_product['quantity_stockM'],
						'quantity_stockK'   => $real_product['quantity_stockK'],
						'quantity_stockMN'   => $real_product['quantity_stockMN'],
						'quantity_stockAS'   => $real_product['quantity_stockAS'],
						'price_in_eur'        => $price_in_eur,
						'total_in_eur'        => $total_in_eur,
						'pricewd_national_txt'	=> $this->currency->format($set_product_result['pricewd_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
						'totalwd_national_txt'	=> $this->currency->format($set_product_result['totalwd_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
						'price_in_base_real' => $price_in_base_real,
						'special_in_base_real' => $special_in_base_real,
						'price_in_eur_by_real_cource' => $price_in_eur_by_real_cource,
						'price_changed_upon_order_start' => $price_changed_upon_order_start,
						'buy_max_coef'		=> $buy_max_coef,
						'buy_max_price'		=> $buy_max_price,
						'real_difference'	=> $real_difference,
						'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $set_product_result['product_id'], 'SSL'),
						);
					}
					
					} else {
					$set_products = false;
				}
				
				//guessing prices
				
				
				$price_in_base_real = $this->currency->format($this->model_catalog_product->getProductPrice($order_product['product_id']), $this->config->get('config_currency'), 1);
				$price_in_base_real_num = $this->model_catalog_product->getProductPrice($order_product['product_id']);
				
				$price_in_eur_numeric = $this->currency->convert($order_product['price_national'], $order_info['currency_code'], $this->config->get('config_currency'));
				$price_in_eur = $this->currency->format($this->currency->convert($order_product['price_national'], $order_info['currency_code'], $this->config->get('config_currency')),  $this->config->get('config_currency'), 1);
				$price_in_eur_num = $this->currency->convert($order_product['price_national'], $order_info['currency_code'], $this->config->get('config_currency'));
				
				$pricewd_in_eur = $this->currency->format($this->currency->convert($order_product['pricewd_national'], $order_info['currency_code'], $this->config->get('config_currency')),  $this->config->get('config_currency'), 1);
				$pricewd_in_eur_num = $this->currency->convert($order_product['pricewd_national'], $order_info['currency_code'], $this->config->get('config_currency'));
				
				$price_in_eur_by_real_cource = $this->currency->format($this->currency->convert($order_product['price_national'], $order_info['currency_code'], $this->config->get('config_currency'), true),  $this->config->get('config_currency'), 1);
				$price_in_eur_by_real_cource_num = $this->currency->convert($order_product['price_national'], $order_info['currency_code'], $this->config->get('config_currency'), true);
				
				$pricewd_in_eur_by_real_cource = $this->currency->format($this->currency->convert($order_product['pricewd_national'], $order_info['currency_code'], $this->config->get('config_currency'), true),  $this->config->get('config_currency'), 1);
				$pricewd_in_eur_by_real_cource_num = $this->currency->convert($order_product['pricewd_national'], $order_info['currency_code'], $this->config->get('config_currency'), true);	
				
				$total_in_eur = $this->currency->format($this->currency->convert($order_product['total_national'], $order_info['currency_code'], $this->config->get('config_currency')),  $this->config->get('config_currency'), 1);
				$total_in_eur_by_real_cource = $this->currency->format($this->currency->convert($order_product['total_national'], $order_info['currency_code'], $this->config->get('config_currency'), true),  $this->config->get('config_currency'), 1);
				
				
				$totalwd_in_eur = $this->currency->format($this->currency->convert($order_product['totalwd_national'], $order_info['currency_code'], $this->config->get('config_currency')),  $this->config->get('config_currency'), 1);
				
				$totalwd_in_eur_by_real_cource = $this->currency->format($this->currency->convert($order_product['totalwd_national'], $order_info['currency_code'], $this->config->get('config_currency'), true),  $this->config->get('config_currency'), 1);					
				$buy_max_coef = $this->model_kp_price->guessCostByPrice($price_in_eur_by_real_cource_num, true);
				$buy_max_price =  $this->currency->format($this->model_kp_price->guessCostByPrice($price_in_eur_by_real_cource_num, false), $this->config->get('config_currency'), 1);
				$buy_max_price_num = $this->model_kp_price->guessCostByPrice($price_in_eur_by_real_cource_num, false);
				
				$real_difference = $this->currency->format($price_in_eur_by_real_cource_num - $buy_max_price_num, $this->config->get('config_currency'), 1);
				
				if ($this->model_catalog_product->getProductSpecialOne($order_product['product_id'])) {
					$special_in_base_real = $this->currency->format($this->model_catalog_product->getProductSpecialOne($order_product['product_id']), $this->config->get('config_currency'), 1);
					$special_in_base_real_num = $this->model_catalog_product->getProductSpecialOne($order_product['product_id']);
					} else {
					$special_in_base_real = false;				
				}
				
				if (!$special_in_base_real){
					
					
					if (abs((float)$price_in_eur_numeric - round((float)$price_in_base_real_num)) >= 3){				
						$price_changed_upon_order_start = true;									
						} else {								
						$price_changed_upon_order_start = false;
					}
					
					} else {
					
					if (abs((float)$price_in_eur - round((float)$special_in_base_real_num)) >= 3){				
						$price_changed_upon_order_start = true;									
						} else {								
						$price_changed_upon_order_start = false;
					}																
				}
				
				//additional_offer
				if ($order_product['ao_id']){
					$ao_info = $this->model_catalog_product->getAdditionalOfferById($order_product['ao_id'], $order_product['product_id']);
					
					if (!$ao_info || count($ao_info) == 0) {
						$ao_text = '<span style="color:red">Не могу найти условия! Возможно, спецпредложение изменилось!</span>';
						$ao_text = false;
						} else {
						
						$ao_main_product = $this->model_catalog_product->getProduct($ao_info['product_id']);
						
						$ao_text = 'Спецпредложение к товару <b>'.$ao_main_product['model'].'</b>, ';
						if ($ao_info['price'] > 0){
							$ao_text .= 'цена: <b>'.$this->currency->format($ao_info['price'],$order_info['currency_code'], 1).'</b>';
							} else {
							$ao_text .= 'скидка: <b>'.$ao_info['percent'].'%</b>';
						}			
						
						if (in_array($ao_info['product_id'], $just_product_ids)){
							$ao_text .= "<br /><span style='color:green'>Основной товар есть в заказе!</span>";
							} else {
							$ao_text .= "<br /><span style='color:red'>Основного товара нет в заказе!</span>";
						}
						
					}
					} else {
					$ao_text = false;
				}
				
				
				$real_product = $this->model_catalog_product->getProduct($order_product['product_id']);
				
				if (!$real_product){
					$real_product['does_not_exist'] = true;
					$real_product['quantity_stock'] = 0;
					$real_product['quantity_stockK'] = 0;
					$real_product['quantity_stockM'] = 0;
					$real_product['quantity_stockMN'] = 0;
					$real_product['quantity_stockAS'] = 0;
					$real_product['manufacturer_id'] = 0;
					$real_product['asin'] = '';
					$real_product['source'] = '';
					$real_product['ean'] = '';
					} else {
					$real_product['does_not_exist'] = false;
				}
				
				$is_in_stock = ($real_product['quantity_stock'] + $real_product['quantity_stockK'] + $real_product['quantity_stockM'] + $real_product['quantity_stockMN'] + $real_product['quantity_stockAS']); 
				
				$is_in_stock_in_country = false; 
				
				if ($order_info['shipping_country_id'] == 176) {
					
					$is_in_stock_in_country = ($real_product['quantity_stockM'] > 0 && $real_product['quantity_stockM'] <= $order_product['quantity']);
					
					} elseif ($order_info['shipping_country_id'] == 220) {
					
					$is_in_stock_in_country = ($real_product['quantity_stockK'] > 0 && $real_product['quantity_stockK'] <= $order_product['quantity']);
					
					} elseif ($order_info['shipping_country_id'] == 109) {
					
					$is_in_stock_in_country = ($real_product['quantity_stockM'] > 0 && $real_product['quantity_stockM'] <= $order_product['quantity']);
					
					} elseif ($order_info['shipping_country_id'] == 20) {
					
					$is_in_stock_in_country = ($real_product['quantity_stockM'] > 0 && $real_product['quantity_stockM'] <= $order_product['quantity']);
					
					} elseif ($order_info['shipping_country_id'] == 81) {
					
					$is_in_stock_in_country = ($real_product['quantity_stock'] > 0 && $real_product['quantity_stock'] <= $order_product['quantity']);
					
				}
				
				if ($order_product['from_stock']){
					$child_stock_product_id = $this->model_catalog_product->getChildStockProductId($order_product['product_id']);
					} else {
					$child_stock_product_id = false;
				}
				
				$local_suppliers = $this->model_sale_supplier->getSupplierStocks($order_product['product_id']);
				
				$local_stock = 0;
				if ($local_suppliers) {
					foreach ($local_suppliers as $_ls){
						$local_stock += $_ls['stock'];				
					}
				}
				
				
				//для закупки
				$supplies = [];
				$supplies = $this->model_sale_supplier->getOPSupply($order_product['order_product_id']);
				
				
				foreach ($supplies as &$_supply){
					$_sn = $this->model_sale_supplier->getSupplier($_supply['supplier_id']);
					$_supply['supplier_name'] = $_sn['supplier_name'] . ' : ' . $_sn['supplier_type'];
					
					$_supply['total'] = $_supply['price'] * $_supply['amount'];
					
					if ($this->data['currency_code'] == $_supply['currency']){					
						$_supply['total_in_order_currency'] = $_supply['total'];
						} else {
						if ($_supply['total'] > 0) {
							$_supply['total_in_order_currency'] = $this->currency->convert($_supply['total'], $_supply['currency'], $this->data['currency_code']);
							} else {
							$_supply['total_in_order_currency'] = 0;
						}
					}
				}		
				
				//recount pricewd's
				$total_pricewd_total_national += $order_product['totalwd_national'];
				
				//do this small shit				
				$price_has_been_changed_by_buyer = abs((int)(($order_product['original_price_national'] - (int)$order_product['price_national']))) && $order_product['original_price_national'] > 0;
				
				//KS
				if ($order_info['shipping_country_id'] == 176){
					$is_in_stock_category = $this->model_sale_order->getIfProductIsFromStockCategoryRU($order_product['product_id']);
					} else {
					$is_in_stock_category = false;
				}
				
				$birthday_active_product = false;
				if ($order_has_birthday_discount){
					$birthday_active_product = $this->model_sale_order->checkIfProductHasBirthdayDiscount($order_product['product_id'], $order_id, $order_product['order_product_id']);
				}
				
				$totals_json = json_decode($order_product['totals_json'], true);
				$points_used_one = false;
				$points_used_total = false;
				$points_used_one_txt = false;
				$points_used_total_txt = false;
				
				if ($totals_json){
					foreach ($totals_json as $totals_json_line){
						if ($totals_json_line['code'] == 'reward'){
							$points_used_one = $totals_json_line['discount'];
							$points_used_total = $totals_json_line['discount_total'];
							
							$points_used_one_txt = $this->currency->format($totals_json_line['discount'], $order_info['currency_code'], '1');
							$points_used_total_txt = $this->currency->format($totals_json_line['discount_total'], $order_info['currency_code'], '1');
							
							break;
						}					
					}
				}
				
				$this->data['order_products'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'supplies'		   => $supplies,
				'local_suppliers'  => $local_suppliers,
				'local_stock'      => ($local_stock >= $order_product['quantity']),
				'is_returned'      => $order_product['is_returned'],
				'is_in_stock'      => $is_in_stock,
				'onsite'           => (isset($real_product['stock_status_id']) && $real_product['stock_status_id'] == $this->config->get('config_stock_status_id'))?true:false,
				'reserves'         =>  $this->model_sale_order->getOrderProductReserves($order_product['order_product_id']),
				'from_stock'       => $order_product['from_stock'],
				'from_bd_gift'       => $order_product['from_bd_gift'],
				'is_in_stock_category' => $is_in_stock_category,
				'child_stock_product_id'       => $child_stock_product_id,
				'is_in_stock_in_country' => $is_in_stock_in_country,
				'name'             => $order_product['name'],
				'image'    	 	   => $this->model_tool_image->resize($order_product['image'], 40, 40),
				'de_name'          => $this->model_catalog_product->getProductDeName($order_product['product_id']),
				'model'            => $order_product['model'],
				'source'           => $order_product['source'],
				'ean'              => $real_product['ean'],
				'asin'             => $real_product['asin'],
				'amzn_no_offers'   => !empty($real_product['amzn_no_offers'])?$real_product['amzn_no_offers']:false,
				'amzn_last_offers' => !empty($real_product['amzn_last_offers'])?$real_product['amzn_last_offers']:false,
				'amzn_ignore'      => !empty($real_product['amzn_ignore'])?$real_product['amzn_ignore']:false,
				'amazon_best_price' 	=> !empty($real_product['amazon_best_price'])?$this->currency->format($real_product['amazon_best_price'], 'EUR', 1):0,
				'amazon_lowest_price'  	=> !empty($real_product['amazon_lowest_price'])?$this->currency->format($real_product['amazon_lowest_price'], 'EUR', 1):0,
				'good'             => $order_product['good'],
				'taken'            => $order_product['taken'],
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'amazon_offers_type' => $order_product['amazon_offers_type'],
				'real_product'     => $real_product,
				'colored_similar'     => $_colored_similar,
				'quantity_stock'   		=> !empty($real_product['quantity_stock'])?$real_product['quantity_stock']:0,
				'quantity_stockM'   	=> !empty($real_product['quantity_stockM'])?$real_product['quantity_stockM']:0,
				'quantity_stockK'   	=> !empty($real_product['quantity_stockK'])?$real_product['quantity_stockK']:0,
				'quantity_stockMN'   	=> !empty($real_product['quantity_stockMN'])?$real_product['quantity_stockMN']:0,
				'quantity_stockAS'   	=> !empty($real_product['quantity_stockAS'])?$real_product['quantity_stockAS']:0,
				'quantity_stock_onway'   		=> !empty($real_product['quantity_stock_onway'])?$real_product['quantity_stock_onway']:0,
				'quantity_stockM_onway'   		=> !empty($real_product['quantity_stockM_onway'])?$real_product['quantity_stockM_onway']:0,
				'quantity_stockK_onway'   		=> !empty($real_product['quantity_stockK_onway'])?$real_product['quantity_stockK_onway']:0,
				'quantity_stockMN_onway'   		=> !empty($real_product['quantity_stockMN_onway'])?$real_product['quantity_stockMN_onway']:0,
				'quantity_stockAS_onway'   		=> !empty($real_product['quantity_stockAS_onway'])?$real_product['quantity_stockAS_onway']:0,
				'delivery_num'     => $order_product['delivery_num'],
				'part_num'         => $order_product['part_num'],
				'price'            => $order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0),
				'price_national'   => $order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0),
				'price_national_txt'   => $this->currency->format($order_product['price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
				'original_price_national'   => $this->currency->format($order_product['original_price_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
				'price_has_been_changed_by_buyer' => $price_has_been_changed_by_buyer,
				'price_has_been_changed_by_buyer_up' => ((int)$order_product['original_price_national'] > (int)$order_product['price_national']),
				'total'            => $order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0),
				'total_national'   => $order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0),
				'total_national_txt'   => $this->currency->format($order_product['total_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
				'price_txt'        => $price_txt,
				'total_txt'        => $total_txt,				
				'price_in_eur'        => $price_in_eur,
				'pricewd_in_eur'        => $pricewd_in_eur,
				'pricewd_in_eur_by_real_cource' => $pricewd_in_eur_by_real_cource,
				'total_in_eur'        => $total_in_eur,
				'totalwd_in_eur'        => $totalwd_in_eur,
				'totalwd_in_eur_by_real_cource'        => $totalwd_in_eur_by_real_cource,
				'price_in_base_real' => $price_in_base_real,
				'special_in_base_real' => $special_in_base_real,
				'price_in_eur_by_real_cource' => $price_in_eur_by_real_cource,
				'total_in_eur_by_real_cource' => $total_in_eur_by_real_cource,
				'price_changed_upon_order_start' => $price_changed_upon_order_start,
				'pricewd_national'	        	=> $order_product['pricewd_national'],
				'pricewd_national_txt'	        => $this->currency->format($order_product['pricewd_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
				'totalwd_national'	        => $order_product['totalwd_national'],
				'totalwd_national_txt'	        => $this->currency->format($order_product['totalwd_national'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], '1'),
				'buy_max_coef'		=> $buy_max_coef,
				'buy_max_price'		=> $buy_max_price,
				'real_difference'	=> $real_difference,
				'tax'              	=> $order_product['tax'],
				'reward'           		=> $order_product['reward'],
				'reward_one'           	=>  $order_product['reward_one']?$order_product['reward_one']:((int)($order_product['reward']/$order_product['quantity'])),
				'points'           		=>  $order_product['reward'],
				'points_one'           	=>  $order_product['reward_one']?$order_product['reward_one']:((int)($order_product['reward']/$order_product['quantity'])),
				'birthday_active_product' => $birthday_active_product,
				'coupon_active_product'    => (in_array($order_product['product_id'], $coupon_active_products)),
				'cumulative_discount_active_product'    => (!in_array($real_product['manufacturer_id'], $excluded_manufacturers)),
				'totals_json' 			=> $totals_json,				
				'points_used_one' 		=> $points_used_one,	
				'points_used_total' 	=> $points_used_total,	
				'points_used_one_txt' 	=> $points_used_one_txt,	
				'points_used_total_txt' => $points_used_total_txt,	
 				'adminlink'        	=> $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $order_product['product_id'], 'SSL'),
				'product_waitlist_href' => $this->url->link('catalog/waitlist', 'filter_product_id='.$order_product['product_id'].'&token='.$this->session->data['token']),
				'set' => $set_products,						
				'ao_id' => $order_product['ao_id'],	
				'ao_product_id' => $order_product['ao_product_id'],
				'ao_text' => $ao_text,
				);
			}
			
			unset($order_product);
			$this->data['order_has_additional_offer'] = false;
			$this->data['order_has_zeropriceproduct'] = false;
			foreach ($order_products as $order_product) {
				
				if ($order_product['price_national'] == 0){
					$this->data['order_has_zeropriceproduct'] = true;
					break;
				}
				
				if ($order_product['ao_id']){
					$this->data['order_has_additional_offer'] = true;
					break;
				}
				
			}
			
			$this->load->model('localisation/currency');
			$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
			
			if (isset($this->request->post['order_voucher'])) {
				$this->data['order_vouchers'] = $this->request->post['order_voucher'];
				} elseif (isset($this->request->get['order_id'])) {
				$this->data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);
				} else {
				$this->data['order_vouchers'] = [];
			}
			
			$this->load->model('sale/voucher_theme');
			
			$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
			
			
			$this->data['total_pricewd_total_national'] = $this->currency->format($total_pricewd_total_national, $order_info['currency_code'], 1);
			
			//and now control
			$shipping_cost = 0;
			foreach ($this->data['order_totals'] as $order_total){				
				if ($order_total['code'] == 'shipping'){
					$shipping_cost = $order_total['value_national'];
					break;
				}				
			}
			
			$total_cost = 0;
			foreach ($this->data['order_totals'] as $order_total){
				if ($order_total['code'] == 'total'){
					$total_cost = $order_total['value_national'];
					break;
				}
			}
			
			$sub_total = 0;
			foreach ($this->data['order_totals'] as $order_total){
				if ($order_total['code'] == 'sub_total'){
					$sub_total = $order_total['value_national'];
					break;
				}
			}
			
			$fixed_discounts = [];
			foreach ($this->data['order_totals'] as &$order_total){
				if (bool_real_stripos($order_total['title'], 'скидка') && $order_total['value_national'] < 0 && !bool_real_stripos($order_total['title'], '%')){												
					$_percent = (abs($order_total['value_national']) / $sub_total) * 100;					
					$order_total['discount_alert'] = "<span style='color:#cf4a61'><i class='fa fa-warning'></i>&nbsp;Внимание! Это целочисленная скидка! При пересчете итогов возможна погрешность! Значение скидки в процентах: $_percent%.</span>";										
				}		
			}
			unset($order_total);
			
			//попытаемся пересчитать скидку на процентную
			
			
			$this->data['total_cost'] = $total_cost;
			$this->data['shipping_cost'] = $shipping_cost;
			
			if ($total_pricewd_total_national > 0 && (round($total_pricewd_total_national) != round($total_cost - $shipping_cost))){
				if (abs($total_cost - $shipping_cost - $total_pricewd_total_national) > 1) {
					$this->data['pricewd_error'] = true;
					$this->data['pricewd_diff'] = $this->currency->format(abs($total_cost - $shipping_cost - $total_pricewd_total_national), $order_info['currency_code'], 1);
				}
			}
			
			//totals для скуби-ду
			$this->data['tip_prepay'] = $this->getOrderPrepayNational($this->request->get['order_id']);
			$this->data['tip_prepay_txt'] = $this->currency->format($this->data['tip_prepay'], $order_info['currency_code'], 1);
			
			$this->data['tip_full'] = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $this->request->get['order_id']);
			if ($this->data['tip_full'] < 0){
				$this->data['tip_full'] = -1 * $this->data['tip_full'];
			}
			$this->data['tip_full_txt'] = $this->currency->format($this->data['tip_full'], $order_info['currency_code'], 1);
			
			$this->data['tip_left'] = $order_info['total_national'] - $this->data['tip_full'];
			$this->data['tip_left_txt'] = $this->currency->format($this->data['tip_left'], $order_info['currency_code'], 1);
			
			$this->data['changemanager_url'] = $this->url->link('sale/order/changeManagerAjax', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
			
			$this->data['goodproduct_link'] =$this->url->link('sale/order/setGoodProduct', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['takenproduct_link'] =$this->url->link('sale/order/setTakenProduct', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->load->model('user/user');
			$this->data['managers'] = $this->model_user_user->getUsersByGroup(12, true);
			//manager
			if ($order_info['manager_id']){
				
				$this->data['manager'] = array(
				'name'     => $this->model_user_user->getUserNameById($order_info['manager_id']), 
				'realname' => $this->model_user_user->getRealUserNameById($order_info['manager_id']), 
				'id'       => $order_info['manager_id']
				);
				} else {
				
				if ($this->user->isLogged() && (in_array($this->user->getUserGroup(), array(12, 27))) && $this->user->getOwnOrders()){							
					$this->db->query("UPDATE `order` SET manager_id = '" . (int)$this->user->getId() . "' WHERE order_id = '" . (int)$order_id . "'");
					$this->data['manager'] = array(
					'name'     => $this->model_user_user->getUserNameById($this->user->getId()), 
					'realname' => $this->model_user_user->getRealUserNameById($this->user->getId()), 
					'id'       => $this->user->getId()
					);
				}
			}
			
			$this->data['courier'] = [];
			if ($order_info['courier_id']){
				$this->data['courier'] = array(
				'name'     => $this->model_user_user->getUserNameById($order_info['courier_id']), 
				'realname' => $this->model_user_user->getRealUserNameById($order_info['courier_id']), 
				'id'       => $order_info['courier_id']
				);
			}
			
			$this->data['all_managers'] = $this->getAllManagersWhoCanOwnOrders();	
			
			
			if (isset($this->session->data['success'])){
				$this->data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
			}
			
			$this->data['error_error'] = false;
			if (isset($this->session->data['error_error'])){
				$this->data['error_error'] = $this->session->data['error_error'];
				unset($this->session->data['error_error']);
			}
			
			
			if ($template_prefix = $this->user->getTemplatePrefix()){
				if (file_exists(DIR_TEMPLATE . 'sale/order_forms/order_form'.$template_prefix.'.tpl')){
					$this->template = 'sale/order_forms/order_form'.$template_prefix.'.tpl';			
					} else {
					$this->template = 'sale/order_form.tpl';
				}			
				} else {
				$this->template = 'sale/order_form.tpl';
			}
			
			if ($order_info['order_id'] == 587127 || $order_info['order_id'] == 23038){
				//	$this->template = 'sale/new_order_form.tpl';
			}
			
			if ($return_data && $order_id){
				$this->data['order_product'] = $this->data['order_products'];
				$this->data['order_total'] = $this->data['order_totals'];
				$this->data = array_merge ( $this->data, $this->data['customer_info']);				
				if ($this->data['related_orders']){
					$this->data['related_order_id'] = implode(',',$this->data['related_orders']);
					} else {
					$this->data['related_order_id'] = [];
				}
				
				return $this->data;
			}
			
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function resaveOrder($order_id = false){
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
			$this->load->model('sale/order');
			
			if (isset($this->request->get['order_id']) && $this->request->get['order_id']){
				$order = $this->request->get['order_id'];
			}
			
			if ($order_id){
				$order = (int)$order_id;
			}			
			
			$this->model_sale_order->editOrder($order, $this->getForm(true, $order));	
			
			if (isset($this->request->get['order_id'])){
				$this->response->setOutput(json_encode(['operation' => 'complete']));
			}		
		}
		
		public function add_return(){
			$this->load->model('sale/return');
			$this->load->model('sale/order');
			$data = $this->request->post;
			
			//удаляем количество товара из заказа, если не совпадает, то ставим К минус Эн, если совпадает, то удаляем вообще
			$data['quantity'] = min($data['quantity'], $data['max_quantity']);
			
			//В случае если действие = "возврат на счет", и статуса "выполнен", проводим + транзакцию						
			
			//Добавляем возврат
			$return_id = $this->model_sale_return->addReturn($data);
			
			$order_product_query = $this->db->query("SELECT * FROM `order_product` WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
			$order_product = $order_product_query->row;
			$order_product['quantity'] = $data['quantity'];
			
			//количество на возврат МЕНЬШЕ чем количество в заказе
			if ($data['quantity'] < $data['max_quantity']){
				//обновляем таблицу товаров заказа
				$quantity_to_left = ($data['max_quantity'] - $data['quantity']);
				
				$this->db->query("UPDATE `order_product` SET quantity = '" . (int)$quantity_to_left . "' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
				
				//количество РАВНО
				} elseif ($data['quantity'] == $data['max_quantity']) {
				//var_dump("UPDATE `order_product` SET quantity = '0', is_returned = '1' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
				//$this->db->query("UPDATE `order_product` SET quantity = '0', is_returned = '1' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
				$this->db->query("DELETE FROM `order_product` WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
			}
			
			$this->model_sale_order->editOrder($data['order_id'], $this->getForm(true, $data['order_id']), true);
			
			//если надо оформить перезаказ
			if (isset($data['return_reorder']) && $data['return_reorder']){
				$order_data = $this->getForm(true, $data['order_id']);
				
				unset($order_data['order_product']);
				$order_data['order_product'] = array(
				$order_product				
				);
				$order_data['comment'] = '';
				$order_data['closed'] = 0;
				$order_data['order_id2'] = '';
				$order_data['order_status_id'] = $this->config->get('config_order_status_id');
				
				$totals = [];
				
				foreach ($order_data['order_total'] as &$_total){
					
					if ($_total['code']  == 'transfer_plus_prepayment'){
						continue;
					}	
					
					if ($_total['code']  == 'coupon'){
						continue;
					}
					
					if (($_total['value_national'] < 0) && $this->model_sale_order->bool_real_stripos($_total['title'], 'Скидка') && !bool_real_stripos($_total['title'], '%')){
						continue;
					}
					
					if ($_total['code']  == 'total' || $_total['code']  == 'sub_total'){
						$_total['value_national'] = 0;
						$_total['text'] = '';
						$_total['value'] = 0;
					}
					
					$totals[] = $_total;
				}
				
				$order_data['order_total'] = $totals;
				
				$new_order_id = $this->model_sale_order->addOrder($order_data);		
				
				if ($new_order_id){
					$this->session->data['success'] = "Создан новый заказ: <a href='".$this->url->link('sale/order/update', 'order_id='.$new_order_id.'&token=' . $this->session->data['token'], 'SSL')."'>" . $new_order_id . "</a>";
					$this->db->query("UPDATE `return` SET reorder_id = '" . (int)$new_order_id . "' WHERE return_id = '" . (int)$return_id . "'");
					
					$_ohdata = array(
					'order_status_id' => $this->config->get('config_order_status_id'),
					'order_id' => $new_order_id,
					'notify' => 0,
					'comment' => 'Переоформлена допоставка / перезаказ'
					);
					
					$this->model_sale_order->addOrderHistory($new_order_id, $_ohdata);					
					$this->model_sale_order->setOrderNoProblem($new_order_id);										
					
					} else {
					$this->session->data['error'] = 'Ошибка! при создании нового заказа';
				}
			}
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {				
			if (!$this->user->hasPermission('modify', 'sale/order') || !$this->user->canUnlockOrders()) {
				$this->error['warning'] = 'У вас нет прав для удаления заказов!';
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		public function country() {
			$json = [];
			
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
		
		public function info() {			
		}
		
		public function divideProduct(){
			$order_product_id = (int)$this->request->post['order_product_id'];
			$quantity = (int)$this->request->post['quantity'];			
			
			$order_product = $this->db->query("SELECT * FROM order_product WHERE order_product_id = '" . (int)$order_product_id . "' LIMIT 1");
			
			if (!$order_product->num_rows){
				echo 'error';
				} else {

				$this->db->query("UPDATE order_product SET quantity = quantity - " . (int)$quantity . " WHERE order_product_id = '" . (int)$order_product_id . "'");
				$this->db->query("UPDATE order_product SET total = price * " . (int)($order_product->row['quantity'] - $quantity) . ", total_national = price_national * " . (int)($order_product->row['quantity'] - $quantity) . " WHERE order_product_id = '" . (int)$order_product_id . "'");

				$this->db->query("INSERT INTO order_product SET
				order_id 		= '" . (int)$order_product->row['order_id'] . "', 
				product_id 		= '" . (int)$order_product->row['product_id'] . "', 
				name 			= '" . $this->db->escape($order_product->row['name']) . "', 
				model 			= '" . $this->db->escape($order_product->row['model']) . "', 
				quantity 		= '" . (int)$quantity  . "',
				ao_id 			= '" . (int)$order_product->row['ao_id'] . "', 		
				delivery_num 	= '" . (int)$order_product->row['delivery_num'] . "', 
				part_num 		= '" . $this->db->escape($order_product->row['part_num']) . "', 
				price 			= '" . (float)$order_product->row['price'] . "', 
				price_national 			= '" . (float)$order_product->row['price_national'] . "',
				original_price_national = '" . (float)$order_product->row['original_price_national'] . "',
				pricewd_national 		= '0',
				total 			= '" .(float)((int)$quantity * $order_product->row['price']). "',
				total_national 	= '" . (float)((int)$quantity * $order_product->row['price_national']) . "',
				totalwd_national 		= '0',
				tax 		= '0', 
				reward 		= '0', 
				good 		= '1', 
				taken 		= '0',
				from_stock 	= '0',
				from_bd_gift 	= '0',
				is_returned 	= '0'");
				
				$this->resaveOrder($order_product->row['order_id']);
				
				$this->response->setOutput('ok');
			}			
		}
		
		public function addProduct(){
			$this->load->model('sale/order');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');			
			$this->load->model('localisation/currency');
			
			$product_id = (int)$this->request->post['product_id'];
			$order_id = (int)$this->request->post['order_id'];
			$quantity = (int)$this->request->post['quantity'];
			$ao_id = (int)$this->request->post['ao_id'];
			$ajax = isset($his->request->post['ao_id']);
			$override_price = (isset($this->request->post['override_price']) && $this->request->post['override_price']!='')?$this->request->post['override_price']:false;
			
			$order = $this->model_sale_order->getOrder((int)$order_id);
			$order_products = $this->model_sale_order->getOrderProducts((int)$order_id);
			$product = $this->model_catalog_product->getProduct((int)$product_id);		
			
			//get main shop currency
			$this->load->model('setting/setting');
			$main_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', (int)$order['store_id']);
			$order_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$order['store_id']);
			$order_currency_info = $this->model_localisation_currency->getCurrencyByCode($order_currency);
			$main_cid = $this->model_setting_setting->getKeySettingValue('config', 'config_customer_group_id', (int)$order['store_id']);
			
			//overloading main price, if exists
			if ($overload_price = $this->model_catalog_product->getProductStorePrice($product_id, (int)$order['store_id'])){
				$product['price'] = $overload_price;
			}
			
			//overloading main price, if exists
			$do_percent = true;
			if ($overload_price_national = $this->model_catalog_product->getProductStorePriceNational($product_id, (int)$order['store_id'])){
				$product['price'] = $this->currency->convert($overload_price_national, $order_currency, $main_currency, false, false);
				$do_percent = false;
				
			}
			
			if (!isset($this->request->post['customer_group_id']) OR $this->request->post['customer_group_id'] == 0){
				$customer_group_id = $main_cid;			
				} else {
				$customer_group_id = (int)$this->request->post['customer_group_id'];
			}
			
			
			$special = $this->model_catalog_product->getProductSpecialsForCustomer($product_id, $customer_group_id, $order_currency, (int)$order['store_id']);				
			
			if ($overload_price_national) {
				
				if (($product['price'] > $this->percentCurrency($order_currency_info, $special)) && $special && $special > 0){
					$product['price'] = $special;			
				}
				
				} else {
				
				if (($product['price'] > $special) && $special && $special > 0){
					$product['price'] = $special;			
				}
				
			}
			
			if ($do_percent){
				$product['price'] = $this->percentCurrency($order_currency_info, $product['price']);
			}
			
			//additional offer
			if (!($override_price === false)){
				$product['price'] = $override_price;
			}
			
			if ($ao_id){
				$product['ao_id'] = $ao_id;
				} else {
				$product['ao_id'] = 0;
			}
			
			//check if this_is_set
			
			$product_set = $this->model_catalog_product->getThisProductIsSet($product_id);
			if ($product_set){
				$set_products = $this->model_catalog_product->getSet($product_set['set_id']);
				
				foreach ($set_products as $set_product){
					$real_set_product = $this->model_catalog_product->getProduct($set_product['product_id']);
					
					$set_product['name'] = $real_set_product['name'];
					$set_product['model'] = $real_set_product['model'];
					$set_product['set_product_id'] = $product_id;
					
					$set_product['price_national'] = $this->currency->convert($set_product['price_in_set'], $main_currency, $order['currency_code']);
					$set_product['total_national'] = $set_product['price_national'] * $set_product['quantity'];		
					$set_product['total'] = $set_product['price_in_set'] * $set_product['quantity'];	
					
					$this->model_sale_order->addSetProduct($set_product, $order);		
				}
			}
			
			$connectorURI = $order['store_url'] . 'index.php?route=kp/connector/getProductReward&entity_id=' . $product_id;
			$json = json_decode(file_get_contents($connectorURI), true);
			
			if (!empty($json['success'])){
				$product['reward'] = $json['reward'];
				} else {
				$product['reward'] = 0;
			}
			
			$product['reward'] = $product['reward'] * $quantity;
			
			$product['price_national'] = $this->currency->convert($product['price'], $main_currency, $order['currency_code']);
			$product['total_national'] = $product['price_national'] * $quantity;		
			$product['total'] = $product['price'] * $quantity;		
			
			$product['quantity'] = $quantity;
			
			$result = $this->model_sale_order->addProduct($product, $order);
			$this->resaveOrder($order_id);
			
			$this->response->setOutput(json_encode(['add' => $result]));			
		}
		
		public function createInvoiceNo() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$invoice_no = $this->model_sale_order->createInvoiceNo($this->request->get['order_id']);
				
				if ($invoice_no) {
					$json['invoice_no'] = $invoice_no;
					} else {
					$json['error'] = $this->language->get('error_action');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function addCredit() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
				
				if ($order_info && $order_info['customer_id']) {
					$this->load->model('sale/customer');
					
					$credit_total = $this->model_sale_customer->getTotalTransactionsByOrderId($this->request->get['order_id']);
					
					if (!$credit_total) {
						$this->model_sale_customer->addTransaction($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['total'], $this->request->get['order_id']);
						
						$json['success'] = $this->language->get('text_credit_added');
						} else {
						$json['error'] = $this->language->get('error_action');
					}
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function removeCredit() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
				
				if ($order_info && $order_info['customer_id']) {
					$this->load->model('sale/customer');
					
					$this->model_sale_customer->deleteTransaction($this->request->get['order_id']);
					
					$json['success'] = $this->language->get('text_credit_removed');
					} else {
					$json['error'] = $this->language->get('error_action');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function addReward() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
				
				if ($order_info && $order_info['customer_id']) {
					$this->load->model('sale/customer');
					
					$reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);
					
					if (!$reward_total) {
						$this->model_sale_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['reward'], $this->request->get['order_id']);
						
						$json['success'] = $this->language->get('text_reward_added');
						} else {
						$json['error'] = $this->language->get('error_action');
					}
					} else {
					$json['error'] = $this->language->get('error_action');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function removeReward() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
				
				if ($order_info && $order_info['customer_id']) {
					$this->load->model('sale/customer');
					
					$this->model_sale_customer->deleteReward($this->request->get['order_id']);
					
					$json['success'] = $this->language->get('text_reward_removed');
					} else {
					$json['error'] = $this->language->get('error_action');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function addCommission() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
				
				if ($order_info && $order_info['affiliate_id']) {
					$this->load->model('sale/affiliate');
					
					$affiliate_total = $this->model_sale_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);
					
					if (!$affiliate_total) {
						$this->model_sale_affiliate->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['commission'], $this->request->get['order_id']);
						
						$json['success'] = $this->language->get('text_commission_added');
						} else {
						$json['error'] = $this->language->get('error_action');
					}
					} else {
					$json['error'] = $this->language->get('error_action');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function removeCommission() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$json['error'] = $this->language->get('error_permission');
				} elseif (isset($this->request->get['order_id'])) {
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
				
				if ($order_info && $order_info['affiliate_id']) {
					$this->load->model('sale/affiliate');
					
					$this->model_sale_affiliate->deleteTransaction($this->request->get['order_id']);
					
					$json['success'] = $this->language->get('text_commission_removed');
					} else {
					$json['error'] = $this->language->get('error_action');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}		

		public function sendPaymentSMSLinkAjax(){
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
			
			$response = $this->smsAdaptor->sendPaymentLink($order_info, ['amount' => $order_info['total_national'], 'payment_link' => $this->request->post['payment_link']]);

			$this->response->setOutput($response);
		}
		
		public function getDeliverySMSTextAjax(){
			$this->load->model('sale/order');
			$date 			= $this->request->post['senddate'];
			$ttn 			= $this->request->post['ttn'];
			$shipping_code 	= $this->request->post['shipping_code'];

			$order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);

			$this->response->setOutput($this->smsAdaptor->getDeliveryNoteText($order_info, ['ttn' => $this->request->post['ttn'], 'shipping_code' => $this->request->post['shipping_code'], 'senddate' => $this->request->post['senddate']]));			
		}
		
		public function getTransactionSMSTextAjax(){
			$this->load->model('sale/order');
			$order_info 		= $this->model_sale_order->getOrder($this->request->post['order_id']);		
						
			$this->response->setOutput($this->smsAdaptor->getTransactionSMSText($order_info, ['amount' => $this->request->post['sum'], 'type' => (int)$this->request->post['type']]));
		}
		
		public function reloadManagerAjax(){
			$order_id = (int)$this->request->get['order_id'];
			$this->load->model('user/user');
			$this->load->model('sale/order');
			
			$order_info = $this->model_sale_order->getOrder($order_id);	
			
			$json = array(
			'id' 	=> $order_info['manager_id'],
			'name' 	=> $this->model_user_user->getRealUserNameById($order_info['manager_id'])
			);
			
			echo json_encode($json);
		}
		
		public function getCompleteOrderTextAjax(){			
			$order_id = (int)$this->request->post['order_id'];
			$status_id = (int)$this->request->post['order_status_id'];
			
			$str = "";
			
			if ($status_id == $this->config->get('config_complete_status_id')){
				$this->load->model('sale/order');
				$this->load->model('sale/customer');
				
				$order_info = $this->model_sale_order->getOrder($order_id);			
				$order_total = $order_info['total_national'];					
				$customer_balance_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id']);			
				$balance_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
				
				$str .= " При установке статуса ВЫПОЛНЕН с счета покупателя будет снята сумма в размере " . $this->currency->format($order_total, $order_info['currency_code'], 1) . ' и заказ будет заблокирован для редактирования в дальнейшем!';			
				
				} elseif ($status_id == $this->config->get('config_partly_delivered_status_id')) {
				$this->load->model('sale/order');
				$order_info = $this->model_sale_order->getOrder($order_id);			
				$sum_to_take = $this->model_sale_order->getSumForCurrentDelivery($order_id);
				
				$str .= " При установке статуса ЧАСТИЧНО ДОСТАВЛЕН с счета покупателя будет снята сумма в размере " . $this->currency->format($sum_to_take, $order_info['currency_code'], 1);	
				
				} elseif($status_id == $this->config->get('config_cancelled_status_id')) {
				
				$str .= " При установке статуса ОТМЕНЕН Заказ будет заблокирован для редактирования в дальнейшем!";
				
				} elseif (in_array($status_id, array($this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id')))) {
				
				if ($this->user->isLogged() && $this->user->getOwnOrders()) {
					
					$this->load->model('user/user');
					$this->load->model('sale/order');
					$order_info = $this->model_sale_order->getOrder($order_id);	
					
					if ($order_info['manager_id'] != $this->user->getId()){
						
						$count_query = $this->db->query("SELECT count(*) as total FROM `order_history` WHERE 
						order_status_id IN (". $this->config->get('config_confirmed_order_status_id') . "," . $this->config->get('config_confirmed_nopaid_order_status_id') .") AND order_id = '" . (int)$order_id . "'");
						
						if ($count_query->row['total'] == 0){	
							$str .= 'Внимание! Заказ будет присвоен вам вместо менеджера ' . $this->model_user_user->getRealUserNameById($order_info['manager_id']);
						}
					}
					
				}								
				} else {
				$str .= '';
			}
			
			$this->response->setOutput(htmlentities($str));		
		}
		
		public function getStatusSMSTextAjax(){
			$this->load->model('sale/order');
			$this->load->model('localisation/legalperson');
			$this->load->model('setting/setting');
							
			$order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
									
			if (($this->request->post['order_status_id'] == $this->config->get('config_confirmed_nopaid_order_status_id')) && $order_info['pay_type'] == 'Банковской картой' && !$order_info['card_id']){
				$error['error'][] = 'Выбрана оплата банковской картой, однако не выбрана карта для оплаты';
			}
			
			if (!empty($error)) {
				$error['message'] = '';
				$this->response->setOutput(json_encode($error));
				return;
			}

			$data = [
				'order_status_id' 	=> $this->request->post['order_status_id'],
				'comment' 			=> trim($this->request->post['comment']),
				'payment_info' 		=> $this->model_localisation_legalperson->getLegalPersonInfo($order_info['card_id']),
				'order_status_name' => $this->model_sale_order->getStatusName($this->request->post['order_status_id']),
				'pickup_name' 		=> trim($order_info['shipping_method']),
			];

			if ($this->getOrderPrepayNational($this->request->post['order_id'])){
				$data['partly'] = $this->language->get('text_payment_partly');
				$data['amount'] = $this->getOrderPrepayNational($this->request->post['order_id']);
			} else {
				$data['partly'] = '';
				$data['amount'] = $order_info['total_national'];
			}

			if (in_array($order_info['payment_code'], $this->model_sale_order->getEquiringMethods())){
				$data['payment_link'] = $this->model_sale_order->generatePaymentLink($this->request->post['order_id'], $order_info['payment_code']);
			} else {
				$data['payment_link'] = '';
			}

			if (bool_real_stripos($order_info['shipping_code'], 'pickup_advanced')){	
				if ($order_info['store_id'] == 0){
					$data['pickup_url'] = HTTP_CATALOG . 'pick' . ((int)str_replace('pickup_advanced.point_', '', $order_info['shipping_code']) + 1);
				} else {
					$data['pickup_url']  = $this->model_setting_setting->getKeySettingValue('config', 'config_url', $order_info['store_id']) . 'pick' . ((int)str_replace('pickup_advanced.point_', '', $order_info['shipping_code']) + 1);
				}
			} else {
				$data['pickup_url']  = '';
			}
			
			$this->response->setOutput(json_encode($this->smsAdaptor->getStatusSMSText($order_info, $data)));
		}
		
		public function reject_reason_ajax(){
			$this->load->model('sale/reject_reason');
			
			$this->data['token'] 			= $this->session->data['token'];			
			$this->data['reject_reasons'] 	= $this->model_reject_reason->getRejectReasons();
			
			$this->template = 'sale/order_reject_reason.tpl';			
			$this->response->setOutput($this->render());			
		}
		
		public function setOrderLockAjax(){
			$order_id = $this->request->get['order_id'];
			
			if ($this->user->canUnlockOrders()){
				$this->db->query("UPDATE `order` SET closed = (1 - closed) WHERE order_id = '" . (int)$order_id . "'");						
			}
			
			$check = $this->db->query("SELECT closed FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			echo (int)$check->row['closed'];			
		}
		
		public function setOrderSalaryPaidAjax(){
			$order_id = $this->request->get['order_id'];
			
			if ($this->user->canUnlockOrders() && $this->user->getUserGroup() == 1){
				$this->db->query("UPDATE `order` SET salary_paid = (1 - salary_paid) WHERE order_id = '" . (int)$order_id . "'");				
			}
			
			$check = $this->db->query("SELECT salary_paid FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			echo (int)$check->row['salary_paid'];	
		}
		
		public function ttnhistory(){
			$this->load->model('sale/order');
			
			$this->data['ttns'] = [];		
						
			$results 				= $this->model_sale_order->getOrderTtnHistory($this->request->get['order_id']);
			$this->data['order'] 	= $this->model_sale_order->getOrder($this->request->get['order_id']);
			foreach ($results as $result) {
				
				if ($result['sms_sent']!='0000-00-00 00:00:00'){
					$sms_sent = '<span style="color:green;font-weight:700;">'.date('d.m.Y в H:i', strtotime($result['sms_sent'])).'</span>';
					$this->data['sms_sent'] = true;
					} else {
					$sms_sent = '<span style="color:red;font-weight:700;">Не отправлено</span>';
					$this->data['sms_sent'] = false;
				}
				
				$shippingMethod = '';
				if (!empty($this->registry->get('shippingmethods')[$result['delivery_code']])){
					$shippingMethod = $this->registry->get('shippingmethods')[$result['delivery_code']]['name'];
				}
				
				$this->data['ttns'][] = array(
				'order_ttn_id' 			=> $result['order_ttn_id'],
				'delivery_company' 		=> $shippingMethod,
				'delivery_code' 		=> $result['delivery_code'],
				'delivery_code_err' 	=> ($result['delivery_code'] != $this->data['order']['shipping_code']),
				'date_ttn' 				=> date('d.m.Y', strtotime($result['date_ttn'])),
				'ttn'    				=> $result['ttn'],	
				'sms_sent' 				=> $sms_sent,						
				);
			}
			
			$this->data['has_rights'] = in_array($this->user->getUserGroup(), array(1, 23, 13)) || $this->user->getIsAV() || $this->data['order']['manager_id'] == $this->user->getID();
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'sale/order_ttns.tpl';
			
			$this->response->setOutput($this->render());		
		}
		
		public function ttnupdate(){
			$ttn_id = $this->request->post['ttn_id'];
			$delivery_code = $this->request->post['delivery_code'];
			$ttn = $this->request->post['ttn'];
			
			$this->db->query("UPDATE `order_ttns` SET delivery_code = '" . $this->db->escape($delivery_code) . "', ttn = '" . $this->db->escape($ttn) . "' WHERE order_ttn_id = '" . (int)$ttn_id . "'");
			$order_query = $this->db->query("SELECT order_id FROM order_ttns WHERE order_ttn_id = '" . $this->db->escape($ttn_id) . "'");
			
			$this->load->model('user/user');					
			$user_name = $this->model_user_user->getRealUserNameById($this->user->getID());
			
			$data = [
			'type' 			=> 'warning',
			'text' 			=> $user_name.' изменил ТТН. <br />Заказ',
			'entity_type' 	=> 'order', 
			'entity_id' 	=> $order_query->row['order_id']
			];
			
			$this->mAlert->insertAlertForGroup('sales', $data);
			$this->mAlert->insertAlertForGroup('admins', $data);
		}
		
		public function ttninfoajax(){
			$ttn 			= $this->request->post['ttn'];
			$delivery_code 	= $this->request->post['delivery_code'];
			$phone 			= $this->request->post['phone'];
			
			$this->response->setOutput($this->courierServices->getInfo($ttn, $delivery_code, $phone));					
		}
		
		public function ttninfoajax2(){
			$ttn 	= $this->request->post['ttn'];
			$phone 	= $this->request->post['phone'];
			
			$dc_query = $this->db->query("SELECT delivery_code FROM order_ttns WHERE ttn = '" . $this->db->escape($ttn) ."' LIMIT 1");
			$delivery_code = $dc_query->row['delivery_code'];
			
			$this->response->setOutput($this->courierServices->getInfo($ttn, $delivery_code, $phone));
		}
		
		public function addTTNAjax(){
			$this->load->model('sale/order');

			if (!empty($this->request->post['date_sent'])){
				$date_sent = $this->request->post['date_sent'];
			} else {
				$date_sent = date('Y-m-d');
			}
			
			$order_info = $this->model_sale_order->getOrder((int)$this->request->post['order_id']);
			
			if (trim($this->request->post['ttn'])){				
				$this->db->query("INSERT INTO `order_ttns` SET
					order_id 		= '" . (int)$this->request->post['order_id'] . "',
					ttn 			= '" .$this->db->escape($this->request->post['ttn']) . "',
					delivery_code 	= '" .$this->db->escape($order_info['shipping_code']). "',
					date_ttn 		= '" .$this->db->escape($date_sent). "'"
				);	

				$this->db->query("UPDATE `order` SET ttn 		= '" .$this->db->escape($this->request->post['ttn']) . "' WHERE order_id = '" . (int)$this->request->post['order_id'] . "'");
				$this->db->query("UPDATE `order` SET date_sent 	= '" .$this->db->escape($date_sent). "' WHERE order_id = '" . (int)(int)$this->request->post['order_id'] . "'");

				if ($this->config->get('config_sms_status_use_only_settings')){
					$this->smsAdaptor->sendDeliveryNote($order_info, ['ttn' => $this->request->post['ttn'], 'order_status_id' => $order_info['order_status_id']]);
				} else {
					if (!empty($this->request->post['send_delivery_sms']) && !empty(trim($this->request->post['delivery_sms_text']))){
						$this->smsAdaptor->sendSMS(['to' => $order_info['telephone'], 'message' => trim($this->request->post['delivery_sms_text'])]);
					}
				}
			}
		}
		
		public function smshistory(){
			$this->language->load('sale/order');
			$this->load->model('sale/order');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_notify'] = $this->language->get('column_notify');
			$this->data['column_comment'] = $this->language->get('column_comment');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['histories'] = [];		
			
			$results = $this->model_sale_order->getOrderSmsHistories((int)$this->request->get['order_id'], 0, 40);
			
			foreach ($results as $result) {
				$this->data['histories'][] = array(
				'date_added' => date('d.m.Y', strtotime($result['date_added'])).'<br />'.date('H:i:s', strtotime($result['date_added'])),		
				'comment'    =>  (EmailTemplate::isHTML($result['comment'])) ? html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8') : nl2br($result['comment'], true),	
				);
			}	
			
			$this->template = 'sale/order_sms_history.tpl';
			
			$this->response->setOutput($this->render());		
		}
		
		public function courierhistory(){
			$this->language->load('sale/order');
			$this->load->model('sale/order');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_notify'] = $this->language->get('column_notify');
			$this->data['column_comment'] = $this->language->get('column_comment');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['histories'] = [];		
			
			$results = $this->model_sale_order->getOrderCourierHistories((int)$this->request->get['order_id'], 0, 40);
			
			foreach ($results as $result) {
				$this->data['histories'][] = array(
				'date_status' => date('d.m.Y', strtotime($result['date_status'])),
				'courier_id'      => $result['courier_id'],
				'status'      => $result['status'],
				'comment'     => $result['comment'],
				);
			}	
			
			$this->template = 'sale/order_courier_sale_history.tpl';
			
			$this->response->setOutput($this->render());		
		}		
		
		public function emailhistory(){
			$this->language->load('sale/order');
			$this->load->model('sale/order');
			$this->load->model('module/emailtemplate');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_notify'] = $this->language->get('column_notify');
			$this->data['column_comment'] = $this->language->get('column_comment');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$filter = [];
			$filter['order_id'] = (int)$this->request->get['order_id'];
			$filter['limit'] = 100;
			$filter['order'] = 'DESC';
			
			$this->data['histories'] = [];		
			$results = $this->model_module_emailtemplate->getTemplateLogs($filter, true, true);
			
			$this->data['token'] = $this->session->data['token'];				
			$this->data['order_id'] = (int)$this->request->get['order_id'];
			
			foreach ($results as $result){
				
				if ($result['mail_status']){
					if ($result['mail_status'] == 'delivery'){
						$mail_status = "<span style='color:white; padding:3px; background:#4ea24e;'>".$result['mail_status']."</span>";					
						} else {
						$mail_status = "<span style='color:white; padding:3px; background:red;'>".$result['mail_status']."</span>";
					}
					} else {
					$mail_status = "<span style='color:white; padding:3px; background:#e4c25a;'>unknown</span>";
				}
				
				if ($result['mail_opened']){
					$mail_opened = "<span style='color:white; padding:3px; background:#4ea24e;'>".$result['mail_opened']."</span>";
					} else {
					$mail_opened = "<span style='color:white; padding:3px; background:#e4c25a;'>".$result['mail_opened']."</span>";
				}
				
				if ($result['mail_clicked']){
					$mail_clicked = "<span style='color:white; padding:3px; background:#4ea24e;'>".$result['mail_clicked']."</span>";
					} else {
					$mail_clicked = "<span style='color:white; padding:3px; background:#e4c25a;'>".$result['mail_clicked']."</span>";
				}
				
				$this->data['histories'][] = array(
				'sent' => date('d.m.Y', strtotime($result['sent'])).'<br />'.date('H:i:s', strtotime($result['sent'])),
				'subject' => $result['subject'],
				'to' => $result['to'],
				'emailtemplate_log_id' => $result['emailtemplate_log_id'],
				'mail_status'    => $mail_status,				
				'mail_opened'    => $mail_opened,
				'mail_clicked'   => $mail_clicked
				);
				
			}
			
			$this->template = 'sale/order_email_history.tpl';
			
			$this->response->setOutput($this->render());		
		}
		
		public function invoicehistory(){
			$this->load->model('sale/order');
			$this->load->model('user/user');
			$order_id = (int)$this->request->get['order_id'];
			
			$this->data['can_use_cheques'] = (in_array($this->user->getUserGroup(), array(1, 23, 22, 13)) or $this->user->getIsAV());
			
			$this->data['token'] = $this->session->data['token'];				
			$this->data['order_id'] = (int)$this->request->get['order_id'];
			
			$results = $this->model_sale_order->getOrderInvoiceHistory($order_id);
			
			foreach ($results as $result){
				
				if (!$this->data['can_use_cheques'] && $result['auto_gen']){
					
					$this->data['histories'][] = array(
					'order_invoice_id' => $result['order_invoice_id'],
					'invoice_name' => $result['invoice_name'],
					'user_id' => $result['user_id'],
					'name'     => $this->model_user_user->getUserNameById($result['user_id']), 
					'realname' => $this->model_user_user->getRealUserNameById($result['user_id']),
					'datetime' => date('d.m.Y H:i:s', strtotime($result['datetime'])),
					'forbidden' => true
					);
					
					} else {
					
					$this->data['histories'][] = array(
					'order_invoice_id' => $result['order_invoice_id'],
					'invoice_name' => $result['invoice_name'],
					'user_id' => $result['user_id'],
					'name'     => $this->model_user_user->getUserNameById($result['user_id']), 
					'realname' => $this->model_user_user->getRealUserNameById($result['user_id']),
					'datetime' => date('d.m.Y H:i:s', strtotime($result['datetime'])),
					'forbidden' => false
					);
				}								
			}
			
			$this->template = 'sale/order_invoice_history.tpl';
			
			$this->response->setOutput($this->render());			
		}
		
		public function savehistory(){
			$this->load->model('sale/order');
			$this->load->model('user/user');
			$order_id = (int)$this->request->get['order_id'];
			
			$results = $this->model_sale_order->getOrderSaveHistory($order_id);
			
			foreach ($results as $result){
				
				$this->data['histories'][] = array(
				'order_save_id' => $result['order_save_id'],
				'user_id' => $result['user_id'],
				'order_id' => $result['order_id'],
				'length' => mb_strlen($result['data']),
				'name'     => $this->model_user_user->getUserNameById($result['user_id']), 
				'realname' => $this->model_user_user->getRealUserNameById($result['user_id']),
				'datetime' => date('d.m.Y H:i:s', strtotime($result['datetime']))
				);
				
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'sale/order_save_history.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function callhistory(){
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('user/user');
			$order_id = (int)$this->request->get['order_id'];
			
			$order = $this->model_sale_order->getOrder($order_id);
			
			$data = array(
			'filter_date_end' => $order['date_added']
			);
			
			$results = $this->model_sale_customer->getCustomerCalls($order['customer_id'], $data);
			
			$this->data['user_calls'] = [];
			
			foreach ($results as $result){
				
				$this->data['user_calls'][] = array(
				'customer_call_id' => $result['customer_call_id'],
				'date_end'         => date('d.m.Y', strtotime($result['date_end'])).'<br />'.date('H:i:s', strtotime($result['date_end'])),
				'inbound'		   => $result['inbound'],
				'length'		   => $result['length'],
				'customer_id'      => $result['customer_id'],
				'manager_id'       => $result['manager_id'],
				'manager_sip_link' => $this->url->link('user/user_sip/history', 'user_id='.(int)$result['manager_id'].'&token='.$this->session->data['token']),
				'manager'          => $this->model_user_user->getRealUserLastNameById($result['manager_id']),
				'customer_name'    => $result['customer_name'],					
				'customer_phone'   => $result['customer_phone'],									
				'internal_pbx_num' => $result['internal_pbx_num'],
				'filename'         => str_replace(SIP_REMOTE_PATH, SIP_REPLACE_PATH, $result['filelink'])
				);			
			}
			
			$this->template = 'sale/order_call_history.tpl';
			
			$this->response->setOutput($this->render());
		}
				
		public function emailhistory2pdf(){
			$id = (int)$this->request->get['id'];
			$order_id = (int)$this->request->get['order_id'];
			$this->load->model('module/emailtemplate');
			
			$result = $this->model_module_emailtemplate->getTemplateLog($id, true);
			$html = $result['html'];
			
			$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4'
			]);		
			
			$html = str_replace(' !important', '', $html);
			
			$mpdf->WriteHTML($html);
			
			$filename = prepareFileName($result['subject']."_".$order_id."_".$id.".pdf");		
			
			$mpdf->Output($filename, 'D');
		}
		
		public function invoicehistory2pdf(){
			$id = (int)$this->request->get['id'];
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('sale/order');
			
			$result = $this->model_sale_order->getInvoiceLog($id);
			$html = $result['html'];
						
			$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4'
			]);					
			
			$html = str_replace(' !important', '', $html);
			$html = html_entity_decode($html);
			
			$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; }</style>\n\n".$html);
			
			$filename = prepareFileName('Товарный чек '. $result['invoice_name'] .".pdf");
			
			$mpdf->Output($filename, 'D');
		}
		
		public function invoicehistory2pdf_a5(){
			$id = (int)$this->request->get['id'];
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('sale/order');
			
			$result = $this->model_sale_order->getInvoiceLog($id);
			$html = $result['html'];
			
			$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A5-L'
			]);		
			
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->setAutoBottomMargin = 'stretch';				
			
			$html = str_replace(' !important', '', $html);
			$html = html_entity_decode($html);
			
			$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; margin-bottom:0px; margin-top:0px;} @page{ margin-footer:0pt; }</style>\n\n".$html);
			
			$filename = prepareFileName('Товарный чек '.$result['invoice_name'].".pdf");
			
			$mpdf->Output($filename, 'D');
		}
		
		public function invoicehistory2pdf_auto ($format = 'small') {
			$pdfFormat = 'A4';
			if ($format == 'small') {
				$pdfFormat = 'A5-L';
			}
			$id = (int)$this->request->get['id'];
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('sale/order');
			
			$result = $this->model_sale_order->getInvoiceLog($id);
			$html = $result['html'];
			
			$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => $pdfFormat
			]);	
			
			$html = str_replace(' !important', '', $html);
			$html = html_entity_decode($html);
			
			$style = '';
			if ($format == 'small') {
				$style = "<style>*, html, body { font-size:9pt; font-family: Arial, Helvetica; }</style>\n\n";
				} elseif ($format == 'big') {
				$style = "<style>*, html, body { font-size:10pt; font-family: Arial, Helvetica; }</style>\n\n";
			}
			$mpdf->WriteHTML($style.$html);
			
			$filename = prepareFileName('Товарный чек '.$result['invoice_name'].".pdf");
			
			if ($format == 'small') {
				$f = DIR_SYSTEM.'temp/'.time().'.pdf';
				$mpdf->Output($f);
				
				if ($this->getNumPagesPdf($f) == 1) {
					unlink($f);
					$mpdf->Output($filename, 'D');
					} else {
					$this->invoicehistory2pdf_auto('big');
				}
				} else {
				$mpdf->Output($filename, 'D');
			}
		}
		
		public function getNumPagesPdf($filepath) {
			$fp = @fopen(preg_replace("/\[(.*?)\]/i", "", $filepath), "r");
			$max = 0;
			if (!$fp) {
				return "Could not open file: $filepath";
				} else {
				while (!@feof($fp)) {
					$line = @fgets($fp, 255);
					if (preg_match('/\/Count [0-9]+/', $line, $matches)) {
						preg_match('/[0-9]+/', $matches[0], $matches2);
						if ($max < $matches2[0]) {
							$max = trim($matches2[0]);
							break;
						}
					}
				}
				@fclose($fp);
			}
			
			return $max;
		}		
		
		public function singleinvoice_log(){
			$id = (int)$this->request->get['id'];	
			
			$this->load->model('sale/order');
			
			$result = $this->model_sale_order->getInvoiceLog($id);
			$html = $result['html'];		
			$html = str_replace(' !important', '', $html);
			$html = html_entity_decode($html);
			
			$top = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head></head><body style="font-family:Calibri, Verdana, Arial">';
			$bottom = "</body></html>";
			
			$html = $top . $html . $bottom;
			
			$this->response->setOutput($html);
		}
		
		public function reload_statuses(){
			$this->load->model('sale/order');
			$this->load->model('localisation/order_status');
			$order_id = (int)$this->request->get['order_id'];
			
			$current_status = $this->model_sale_order->getOrderLastHistory($order_id);
			$order_statuses = $this->model_localisation_order_status->getAllowedOrderStatuses($current_status);
			
			$html = '';
			$html .= "<select name=\"order_status_id_tc\" onchange=\"$('#order_status_id').val($(this).val()); getCompleteOrderTextAjax(); getStatusSMSTextAjax();\">";
			foreach ($order_statuses as $order_status){
				
				if ($order_status['order_status_id'] == $current_status){
					$html .= "<option value=" . $order_status['order_status_id'] . " selected='selected'>" . $order_status['name'] . "</option>";
					} else {
					$html .= "<option value=" . $order_status['order_status_id'] . ">" . $order_status['name'] . "</option>";
				}
				
			}		
			$html .= "</select>";
			
			echo $html;
		}
		
		public function history() {
			$this->language->load('sale/order');
			
			$this->data['error'] 	= '';
			$this->data['success'] 	= '';
			
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('localisation/order_status');
			
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				if (!$this->user->hasPermission('modify', 'sale/order')) {
					$this->data['error'] = $this->language->get('error_permission');
				}
				
				$order_id 	= (int)$this->request->get['order_id'];
				$status_id 	= (int)$this->request->post['order_status_id'];
				
				if ($this->model_sale_order->getIfOrderClosed($order_id)){
					$this->data['error'] = 'Заказ закрыт от редактирования!';
				}
				
				if (!$this->data['error']) {
					if (isset($this->request->post['history_sms_text']) && mb_strlen($this->request->post['history_sms_text']) > 210){
						if (!$this->config->get('config_smsgate_library_enable_viber')){
							$this->data['error'] = 'Смска слишком длинная, более 210 символов. Укороти смску.';
						}						
					}
				}
				
				if (!$this->data['error']) {					
					$current_status_id = $this->model_sale_order->getOrderLastHistory($order_id);
					
					$can_not_change_twice = [$this->config->get('config_partly_delivered_status_id'), $this->config->get('config_complete_status_id')];
					
					if ($status_id == $current_status_id && in_array($status_id, $can_not_change_twice)){
						$this->data['error'] = 'Нельзя два раза подряд установить статус Выполнен либо Частично Отгружен!';
					}
				}
				
				if (!$this->data['error']) {					
					$order_id = (int)$this->request->get['order_id'];
					$status_id = (int)$this->request->post['order_status_id'];
					
					$order_info = $this->model_sale_order->getOrder($order_id);
					$_order_totals = $this->model_sale_order->getOrderTotals($order_id);					
					
					$_total_national = 0;
					foreach ($_order_totals as $_ttotal){
						if ($_ttotal['code'] == 'total'){
							$_total_national = $_ttotal['value_national'];
							break;
						}
					}
					//проверка на наличие оплаты
					$has_total_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
					
					//полная оплата
					if ($status_id == $this->config->get('config_total_paid_order_status_id')){
						
						if (round((int)$_total_national) > round((int)$has_total_national)){
							$this->data['error'] = 'Заказ не является оплаченным полностью! Внесите сначала оплату!';
							
							$customer_comment_text = 'Попытка установки некорректного статуса: <b>'.$this->model_localisation_order_status->getOrderStatusName($status_id).'</b>';					
							$_data = array(
							'customer_id' 		      => $order_info['customer_id'], 
							'comment'     		      => $customer_comment_text,
							'order_id'    		      => $order_id,
							'call_id'     		      => 0,
							'manager_id'    	      => 0,
							'need_call'    			  => 0,
							'segment_id'    		  => 0,
							'order_status_id'    	  => $status_id,
							'prev_order_status_id'    => $current_status_id,
							'is_error'                => true
							);
							$this->model_sale_customer->addHistoryExtended($_data);
							
						}
						
						} elseif ($status_id == $this->config->get('config_prepayment_paid_order_status_id')){
						
						if (!$has_total_national){
							$this->data['error'] = 'По заказу нет информации об оплатах! Внесите сначала оплату!';
							
							$customer_comment_text = 'Попытка установки некорректного статуса: <b>'.$this->model_localisation_order_status->getOrderStatusName($status_id).'</b>';					
							
							$_data = array(
							'customer_id' 		      => $order_info['customer_id'], 
							'comment'     		      => $customer_comment_text,
							'order_id'    		      => $order_id,
							'call_id'     		      => 0,
							'manager_id'    	      => 0,
							'need_call'    			  => 0,
							'segment_id'    		  => 0,
							'order_status_id'    	  => $status_id,
							'prev_order_status_id'    => $current_status_id,
							'is_error'                => true
							);
							$this->model_sale_customer->addHistoryExtended($_data);
						}
						
					}
				}						
				
				if (!$this->data['error']) {					
					if ($status_id == $this->config->get('config_complete_status_id')){
						
						$this->load->model('sale/order');
						$this->load->model('sale/customer');											
						$order_total 				= $order_info['total_national'];					
						$customer_balance_national 	= $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id']);			
						$balance_national 			= $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
						$total_transaction_count 	= $this->model_sale_customer->getTotalTransactions($order_info['customer_id'], $order_id);
						
						
						$amount_national = ($order_total);
						$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
						
						if ($balance_national == 0 && $total_transaction_count == 0){
							$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.': внесение суммы на счет', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');																		
						}
						
						$amount_national = -1*($order_total);
						$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
						
						$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.': списание со счета по статусу Выполнен', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');	
						
						} elseif ($status_id == $this->config->get('config_cancelled_status_id')){				
						
							$reject_reason_id = (int)$this->request->post['reject_reason_id'];						
							$this->db->query("UPDATE `order` SET reject_reason_id = '" . (int)$this->request->post['reject_reason_id'] . "' WHERE `order_id` = '" . $order_id . "'");	
						
						} elseif ($status_id == $this->config->get('config_partly_delivered_status_id')){
						
						$sum_to_take = $this->model_sale_order->getSumForCurrentDelivery($order_id);
						$current_delivery = $this->model_sale_order->getOrderCurrentDelivery($order_id);
						if ($sum_to_take){
							$customer_balance_national 	= $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id']);
							$balance_national 			= $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
							$total_transaction_count 	= $this->model_sale_customer->getTotalTransactions($order_info['customer_id'], $order_id);
							
							$amount_national = ($sum_to_take);
							$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
							
							if ($balance_national == 0 && $total_transaction_count == 0){
								$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.', поставка ' . $current_delivery . ': внесение суммы на счет', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');	
							}
							
							$amount_national = -1*($sum_to_take);
							$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
							
							$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.', поставка ' . $current_delivery . ': списание со счета по статусу Частично Отгружен', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');	
							
						}						
					}
					
					if (in_array((int)$status_id, $this->config->get('config_odinass_order_status_id'))){
						$check_query = $this->db->query("SELECT pricewd_national FROM order_product WHERE order_id = '" . (int)$order_id . "' AND pricewd_national > 0");
						if ($check_query->num_rows == 0){
							$this->resaveOrder((int)$order_id);
						}
					}
					
					$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);
					
					if ($this->request->post['comment'] && mb_strlen($this->request->post['comment']) > 2){
						$customer_comment_text = 'изменен статус на: <b>'.$this->model_localisation_order_status->getOrderStatusName($status_id).'</b> ('.$this->request->post['comment'].')';
						} else {
						$customer_comment_text = 'изменен статус на: <b>'.$this->model_localisation_order_status->getOrderStatusName($status_id).'</b>';
					}
					
					$_data = [
						'customer_id' 		      => $order_info['customer_id'], 
						'comment'     		      => $customer_comment_text,
						'order_id'    		      => $order_id,
						'call_id'     		      => 0,
						'manager_id'    	      => 0,
						'need_call'    			  => 0,
						'segment_id'    		  => 0,
						'order_status_id'    	  => $status_id,
						'prev_order_status_id'    => $current_status_id					
					];
					$this->model_sale_customer->addHistoryExtended($_data);
					
					$this->load->model('user/user');					
					$user_name = $this->model_user_user->getRealUserNameById($this->user->getID());
					
					//алерт сейлзам, если заказ ОБРАБОТАН
					if ($status_id == 21){						
						$data = array(
						'type' => 'warning',
						'text' => $user_name.' изменил статус на <b>ОБРАБОТАН</b>. <br />Заказ', 
						'entity_type' => 'order', 
						'entity_id'=>$order_id
						);
						
						$this->mAlert->insertAlertForGroup('sales', $data);
					}
					
					//алерт сейлзам, админам если заказ Выполнен
					if ($status_id == $this->config->get('config_complete_status_id')){
						
						$data = array(
						'type' => 'success',
						'text' => $user_name.' изменил статус на <b>ВЫПОЛНЕН</b>. <br />Заказ',
						'entity_type' => 'order', 
						'entity_id'=>$order_id
						);
						
						//	$this->mAlert->insertAlertForGroup('sales', $data);
						$this->mAlert->insertAlertForGroup('admins', $data);
					}
					
					//алерт админам, если заказ ОТМЕНЕН
					if ($status_id == $this->config->get('config_cancelled_status_id')){
						
						$data = array(
						'type' => 'error',
						'text' => $user_name.' изменил статус на <b>ОТМЕНЕН</b>. <br />Заказ',
						'entity_type' => 'order', 
						'entity_id'=>$order_id
						);
						
						$this->mAlert->insertAlertForGroup('admins', $data);										
					}
															
					$this->data['success'] = $this->language->get('text_success');
				}
			}
									
			$this->data['text_no_results'] 		= $this->language->get('text_no_results');			
			$this->data['column_date_added'] 	= $this->language->get('column_date_added');
			$this->data['column_status'] 		= $this->language->get('column_status');
			$this->data['column_notify'] 		= $this->language->get('column_notify');
			$this->data['column_comment'] 		= $this->language->get('column_comment');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$this->data['histories'] = [];
			
			$this->data['button_remove'] = $this->language->get('button_remove');			
			$results = $this->model_sale_order->getOrderHistories2($this->request->get['order_id'], ($page - 1) * 20, 20);
			
			$this->load->model('user/user');
			
			foreach ($results as $result) {
				$this->data['histories'][] = array(
				'notify'     			=> $result['notify'],
				'courier'     			=> $result['courier'],
				'status'     			=> $result['status'],
				'status_bg_color'     	=> $result['status_bg_color'],
				'status_fa_icon'     	=> $result['status_fa_icon'],
				'status_txt_color'     	=> $result['status_txt_color'],
				'order_history_id'     	=> $result['order_history_id'],
				'user'     				=> $result['user_id']?$this->model_user_user->getRealUserNameById($result['user_id']):'',	
				'comment'    			=> $result['comment'],
				'yam_substatus'    		=> $result['yam_substatus'],
				'yam_status'    		=> $result['yam_status'],
				'date_added' 			=> date('d.m.Y', strtotime($result['date_added'])). '<br />' . date('H:i:s', strtotime($result['date_added']))
				);
			}	
			
			$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);
			
			$pagination = new Pagination();
			$pagination->total = $history_total;
			$pagination->page = $page;
			$pagination->limit = 20;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/order/history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'sale/order_history.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function getOrderHistoryLastStatus() {
			$this->load->model('sale/order');
			
			$this->response->setOutput(json_encode($this->model_sale_order->getOrderHistoryLastStatus($this->request->get['order_id'],
			$this->config->get('config_language_id'))));
		}
		
		public function delete_order_transaction(){					
			$this->data['error'] = false;
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($this->request->post['order_id'])){
				//	$this->data['error'] = "Заказ закрыт. Удаление транзакции невозможно!";
			}
			
			if (!$this->user->canDoTransactions()){
				$this->data['error'] = "Нет прав на изменение транзакций!";
			}
			
			
			if (!$this->data['error']) { 
				$this->db->query("DELETE FROM customer_transaction WHERE customer_transaction_id = '". (int)$this->request->post['transaction_id'] ."'");			
				echo 'ok';		
			}
		}
				
		public function delete_order_history() {
			$this->language->load('sale/order');
			$this->load->model('sale/order');
			
			$this->data['error'] = '';
			$this->data['success'] = '';
			
			$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				if ($this->model_sale_order->getIfOrderClosed($this->request->get['order_id'])){
					$this->data['error'] = "Заказ закрыт. Удаление истории невозможно!";
				}
				
				if (!$this->user->hasPermission('modify', 'sale/order')) { 
					$this->data['error'] = $this->language->get('error_permission');
				}
				
				if (!$this->user->getIsAV() && !$this->user->canUnlockOrders() && !$this->user->getIsMM()){
					$this->data['error'] = "Недостаточно прав для удаления истории!";
				}
				
				if (!$this->data['error']) { 
					if ($history_total>1){
						$this->model_sale_order->deleteOrderHistory($this->request->get['order_id'], $this->request->get['order_history_id']);												
						$this->data['success'] = $this->language->get('text_success');
					}
					else $this->data['error'] = $this->language->get('error_last_entry');
					
				}
			}
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_notify'] = $this->language->get('column_notify');
			$this->data['column_comment'] = $this->language->get('column_comment');
			$this->data['button_remove'] = $this->language->get('button_remove');	
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}  
			
			$this->data['histories'] = [];
			
			$results = $this->model_sale_order->getOrderHistories2($this->request->get['order_id'], ($page - 1) * 20, 20);
			
			$this->load->model('user/user');
			
			foreach ($results as $result) {
				$this->data['histories'][] = array(
				'notify'     => $result['notify'],
				'courier'     => $result['courier'],
				'status'     => $result['status'],
				'order_history_id'     => $result['order_history_id'],
				'user'     => $result['user_id']?$this->model_user_user->getRealUserNameById($result['user_id']):'',	
				'comment'    => nl2br($result['comment']),
				'date_added' => date('d.m.Y', strtotime($result['date_added'])). '<br />' . date('H:i:s', strtotime($result['date_added']))
				);
			}	
			
			$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);
			
			$pagination = new Pagination();
			$pagination->total = $history_total;
			$pagination->page = $page;
			$pagination->limit = 20; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/order/history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'sale/order_history.tpl';		
			
			$this->response->setOutput($this->render());				
		}
		
		public function download() {
			$this->load->model('sale/order');
			
			if (isset($this->request->get['order_option_id'])) {
				$order_option_id = $this->request->get['order_option_id'];
				} else {
				$order_option_id = 0;
			}
			
			$option_info = $this->model_sale_order->getOrderOption($this->request->get['order_id'], $order_option_id);
			
			if ($option_info && $option_info['type'] == 'file') {
				$file = DIR_DOWNLOAD . $option_info['value'];
				$mask = basename(utf8_substr($option_info['value'], 0, utf8_strrpos($option_info['value'], '.')));
				
				if (!headers_sent()) {
					if (file_exists($file)) {
						header('Content-Type: application/octet-stream');
						header('Content-Description: File Transfer');
						header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
						header('Content-Transfer-Encoding: binary');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Content-Length: ' . filesize($file));
						
						readfile($file, 'rb');
						exit;
						} else {
						exit('Error: Could not find file ' . $file . '!');
					}
					} else {
					exit('Error: Headers already sent out!');
				}
				} else {
				$this->language->load('error/not_found');
				
				$this->document->setTitle($this->language->get('heading_title'));
				
				$this->data['heading_title'] = $this->language->get('heading_title');
				
				$this->data['text_not_found'] = $this->language->get('text_not_found');
				
				$this->data['breadcrumbs'] = [];
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
				);
				
				$this->template = 'error/not_found.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);
				
				$this->response->setOutput($this->render());
			}
		}
		
		public function upload() {
			$this->language->load('sale/order');
			
			$json = [];
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				if (!empty($this->request->files['file']['name'])) {
					$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');
					
					if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
						$json['error'] = $this->language->get('error_filename');
					}
					
					// Allowed file extension types
					$allowed = [];
					
					$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));
					
					foreach ($filetypes as $filetype) {
						$allowed[] = trim($filetype);
					}
					
					if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
						$json['error'] = $this->language->get('error_filetype');
					}
					
					// Allowed file mime types
					$allowed = [];
					
					$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));
					
					foreach ($filetypes as $filetype) {
						$allowed[] = trim($filetype);
					}
					
					if (!in_array($this->request->files['file']['type'], $allowed)) {
						$json['error'] = $this->language->get('error_filetype');
					}
					
					// Check to see if any PHP files are trying to be uploaded
					$content = file_get_contents($this->request->files['file']['tmp_name']);
					
					if (preg_match('/\<\?php/i', $content)) {
						$json['error'] = $this->language->get('error_filetype');
					}
					
					if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
						$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
					}
					} else {
					$json['error'] = $this->language->get('error_upload');
				}
				
				if (!isset($json['error'])) {
					if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
						$file = basename($filename) . '.' . md5(mt_rand());
						
						$json['file'] = $file;
						
						move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
					}
					
					$json['success'] = $this->language->get('text_upload');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function order_return_ajax(){
			$order_id 				= (int)$this->request->post['order_id'];
			$from 					= !empty($this->request->post['from'])?$this->request->post['from']:false;
			$order_product_id 		= (int)$this->request->post['order_product_id'];
			$this->data['index'] 	= (int)$this->request->post['index'];
			
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('catalog/product');
			$this->load->model('localisation/return_reason');
			$this->load->model('localisation/return_action');
			$this->load->model('localisation/return_status');
			
			$this->data['order'] = $this->model_sale_order->getOrder($order_id);
			
			if ($from == 'order_product_untaken'){
				$this->data['order_product'] = $this->model_sale_order->getOrderProductUntakenByID($order_product_id);
				} else {
				$this->data['order_product'] = $this->model_sale_order->getOrderProductByID($order_product_id);
			}
			$this->data['real_product'] = $this->model_catalog_product->getProduct($this->data['order_product']['product_id']);
			$this->data['real_product']['name'] = $this->db->escape($this->data['real_product']['name']);
			$this->data['real_product']['model'] = $this->db->escape($this->data['real_product']['model']);	
			$this->data['order_product_id'] = $order_product_id;
			
			$this->data['quantity'] = $this->data['order_product']['quantity'];
			
			$this->data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();
			$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions();
			$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'sale/order_return_form.tpl';
			
			$this->response->setOutput($this->render());			
		}		
		
		public function single_invoice($return = false, $data = array()){
			$this->language->load('sale/order');
			
			if ($return){
				
				$order_id 		= (int)$data['order_id'];
				$delivery_num 	= (int)$data['delivery_num'];
				$cheque_date 	= ($data['cheque_date'])?date('d.m.Y', strtotime($data['cheque_date'])):date('d.m.Y');
				$cheque_num 	= $data['cheque_num'];
				$cheque_type 	= $data['cheque_type'];
				$prim 			= (int)$data['cheque_prim'];
				$no_return 		= (int)$data['cheque_return'];
				
				} else {
				
				$order_id 		= (int)$this->request->post['order_id'];
				$delivery_num 	= (int)$this->request->post['delivery_num'];
				$cheque_date 	= ($this->request->post['cheque_date'])?date('d.m.Y', strtotime($this->request->post['cheque_date'])):date('d.m.Y');
				$cheque_num 	= $this->request->post['cheque_num'];
				$cheque_type 	= $this->request->post['cheque_type'];
				$prim 			= (int)$this->request->post['cheque_prim'];
				$no_return 		= (int)$this->request->post['cheque_return'];
				
			}
			
			$this->data['cheque_num'] = $this->db->escape($cheque_num);		
			$this->data['cheque_type'] = $this->db->escape($cheque_type);
			
			$this->data['title'] = $this->language->get('heading_title');
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$this->data['base'] = HTTPS_SERVER;
				} else {
				$this->data['base'] = HTTP_SERVER;
			}
			
			$this->data['direction'] = $this->language->get('direction');
			$this->data['language'] = $this->language->get('code');
			
			$this->load->model('sale/order');
			$this->load->model('catalog/product');
			$this->load->model('setting/setting');
			$this->load->model('sale/customer');
			
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			if ($return){
				$incoming_date = $data['cheque_date'];
				} else {
				$incoming_date = $this->request->post['cheque_date'];			
			}
			
			if (!$incoming_date){				
				$cheque_date = date('d.m.Y');
				
				if ($order_info['date_delivery_to'] && $order_info['date_delivery_to'] != '0000-00-00'){
					$cheque_date = date('d.m.Y', strtotime($order_info['date_delivery_to']));
				}
				
				if ($order_info['date_delivery_actual'] && $order_info['date_delivery_actual'] != '0000-00-00'){
					$cheque_date = date('d.m.Y', strtotime($order_info['date_delivery_actual']));
				}
				
				} else {
				
				$cheque_date = date('d.m.Y', strtotime($incoming_date));				
			}
			
			$this->data['cheque_date'] = $this->db->escape($cheque_date);
			
			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				
				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_telephone2 = $store_info['config_telephone2'];
					} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_telephone2 = $this->config->get('config_telephone2');					
				}
				
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
					} else {
					$invoice_no = '';
				}
				
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
					} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$format = '' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{zone}' . "\n" . '{city} {postcode}';
				
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
				
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), ', ', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), ', ', trim(str_replace($find, $replace, $format))));

				if (strpos($order_info['shipping_code'], 'pickup') !== false && $this->getPickupName($order_info['shipping_code'])){
					$order_info['shipping_address'] = 'Самовывоз';
					$shipping_address = $this->getPickupName2($order_info['shipping_code']);														
					$short_shipping_method = 'Самовывоз';
					$do_not_replace_shipping_method = true;
				}

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
					} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$format = '' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{zone}' . "\n" . '{city} {postcode}';
				
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
				
				$payment_address = str_replace(array("\r\n", "\r", "\n"), ', ', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), ', ', trim(str_replace($find, $replace, $format))));
				
				$product_data = [];
				
				$products = $this->model_sale_order->getOrderProducts($order_id, $with_returns = false);
				
				
				$sub_total = 0;
				$full_sub_total = 0;
				$next_sub_total = 0;
				$sku_lengths = [];
				$just_product_ids = [];
				foreach ($products as $product) {
					
					$just_product_ids[] = $product['product_id'];
					
					$option_data = [];
					
					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
					
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
							} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
						'name'  => $option['name'],
						'value' => $value
						);
					}
					
					if ($product['price_national'] > 0){
						//$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');
						$price = $product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0);
						} else {
						//$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
						$price = $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0);
					}
					
					//тоталы	
					
					if ($product['total_national'] > 0){
						//$total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');	
						$total = $product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);												
						
						//только если товар из этой поставки
						if ($product['delivery_num'] == $delivery_num){				
							$sub_total += $product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
						}
						
						//сумма товаров из следующих поставок
						if ($product['delivery_num'] > $delivery_num){									
							//$next_sub_total += $product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
							//БАГФИКС
							$next_sub_total += $product['totalwd_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
						}
						
						} elseif ($product['price_national'] > 0) {
						//$total = $this->currency->format($product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
						$total = $product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
						
						//только если товар из этой поставки
						if ($product['delivery_num'] == $delivery_num){				
							$sub_total += $product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
						}
						
						//сумма товаров из следующих поставок
						if ($product['delivery_num'] > $delivery_num){									
							//$next_sub_total += $product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
							//БАГФИКС
							$next_sub_total += $product['totalwd_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
						}
						
						} else {
						//$total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
						$total = $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
						
						//только если товар из этой поставки
						if ($product['delivery_num'] == $delivery_num){		
							$sub_total += $this->currency->convert($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $this->config->get('config_currency'));
						}
						
						//сумма товаров из следующих поставок
						if ($product['delivery_num'] > $delivery_num){									
							$next_sub_total += $this->currency->convert($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $this->config->get('config_currency'));
						}
					}
					
					$full_sub_total += $product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
					
					if ($product['delivery_num'] != $delivery_num){									
						//$total = $this->currency->format(0, $order_info['currency_code'], '1');
						$total = 0;
						//	$product['quantity'] = 0;
					}
					
					if ($product['delivery_num'] < $delivery_num){	
						$product['symbol'] = "&#x2714;";
						$this->data['has_prev_deliveries'] = true;
					} 
					
					if ($product['delivery_num'] == $delivery_num){	
						$product['symbol'] = "&#x27A4;";
						$this->data['has_current_deliveries'] = true;
					} 
					
					if ($product['delivery_num'] > $delivery_num){	
						$product['symbol'] = "&#x2609;";
						$this->data['has_next_deliveries'] = true;
					} 
					
					$is_set = $this->model_catalog_product->getThisProductIsSet($product['product_id']);
					if ($is_set){
						$set_id = (int)$is_set['set_id'];
						$set_products_results = $this->model_sale_order->getOrderProductsBySet($order_id, $set_id);					
						
						$set_products = [];
						
						foreach ($set_products_results as $set_product_result){
							$set_product_info = $this->model_catalog_product->getProduct($set_product_result['product_id']);
							
							$sku_lengths[] = mb_strlen($set_product_result['model'], 'UTF-8');
							
							$set_products[] = array(
							'product_id'       => $set_product_result['product_id'],
							'name'             => $set_product_result['name'],
							'short_name' 		=> $set_product_info['short_name'],
							'model'            => $set_product_result['model'],
							'quantity'         => $set_product_result['quantity'],
							'price_national'   => $set_product_result['price_national'],
							'total_national'   => $set_product_result['total_national'],								
							);
						}
						
						} else {
						$set_products = false;
					}
					
					if ($product['ao_id']){
						$ao_info = $this->model_catalog_product->getAdditionalOfferById($product['ao_id'], $product['product_id']);
						
						if (!$ao_info || count($ao_info) == 0) {
							
							$ao_text = '<span style="color:red">Не могу найти условия! Возможно, спецпредложение изменилось!</span>';
							$ao_text = false;
							} else {
							
							$ao_main_product = $this->model_catalog_product->getProduct($ao_info['product_id']);
							
							$ao_text = 'Спецпредложение к позиции <b>'.$ao_main_product['model'].'</b>, ';
							if ($ao_info['price'] > 0){
								$ao_text .= 'цена: '.$this->currency->format($ao_info['price'], $order_info['currency_code'], 1);
								} else {
								$ao_text .= 'скидка: '.$ao_info['percent'].'%';
							}			
							
						}
						} else {
						$ao_text = false;
					}
					
					
					$sku_lengths[] = mb_strlen($product['model'], 'UTF-8');
					
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					
					if (!$product_info){
						$product_info['manufacturer_id'] = 0;
						$product_info['short_name'] = '';
						$product_info['sku'] = $product['model'];
					}
					
					$totals_json = json_decode($product['totals_json'], true);
					$points_used_one = false;
					$points_used_total = false;
					$points_used_one_txt = false;
					$points_used_total_txt = false;
					$total_pay_with_cash = false;
					
					foreach ($totals_json as $totals_json_line){
						if ($totals_json_line['code'] == 'reward'){
							$points_used_one = $totals_json_line['discount'];
							$points_used_total = $totals_json_line['discount_total'];
							
							$points_used_one_txt = $this->currency->format($totals_json_line['discount'], $order_info['currency_code'], '1');
							$points_used_total_txt = $this->currency->format($totals_json_line['discount_total'], $order_info['currency_code'], '1');
							
							$total_pay_with_cash = $this->currency->format($total - $totals_json_line['discount_total'], $order_info['currency_code'], '1');
							
							break;
						}					
					}
					
					$product_data[] = array(
					'name'     			=> str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$product['name']),
					'product_id' 		=> $product['product_id'],
					'order_product_id' 	=> $product['order_product_id'],	
					'short_name'     	=> str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$product_info['short_name']),
					'model'    			=> $product['model'],
					'sku'    			=> $product_info['sku'],
					'option'   			=> $option_data,
					'quantity' 			=> $product['quantity'],
					'points' 			=> $this->currency->formatBonus($product['reward'], true),
					'set_products' 		=> $set_products,
					'ao_text' 			=> $ao_text,
					'symbol' 			=> $product['symbol'],
					'manufacturer_id'   => $product_info['manufacturer_id'],
					'cumulative_is_active' 	=> false,
					'coupon_is_active' 		=> false,
					'coupon_presentlogic_is_active' => false,
					'birthday_is_active' 			=> false,
					'totals_json' 			=> $totals_json,				
					'points_used_one' 		=> $points_used_one,	
					'points_used_total' 	=> $points_used_total,	
					'points_used_one_txt' 	=> $points_used_one_txt,	
					'points_used_total_txt' => $points_used_total_txt,
					'total_pay_with_cash'  	=> $total_pay_with_cash,
					'price'    => number_format($price, 2, ',', ' '),
					'total'    => number_format($total, 2, ',', ' ')
					);
					
					/*	
						if ($delivery_num > 1) {
						if ($product['quantity'] != 0) {
						$product_data[] = array(
						'name'     => str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$product['name']),	
						'product_id' => $product['product_id'],	
						'short_name' => str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$product_info['short_name']),
						'model'    => $product['model'],
						'sku'      => $product_info['sku'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'set_products' => $set_products,
						'ao_text' => $ao_text,
						'manufacturer_id'   => $product_info['manufacturer_id'],
						'cumulative_is_active' => false,
						'coupon_is_active' => false,
						'price'    => number_format($price, 2, ',', ' '),
						'total'    => number_format($total, 2, ',', ' ')
						);
						}
						} else {
						$product_data[] = array(
						'name'     => str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$product['name']),
						'product_id' => $product['product_id'],	
						'short_name'     => str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$product_info['short_name']),
						'model'    => $product['model'],
						'sku'    => $product_info['sku'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'set_products' => $set_products,
						'ao_text' => $ao_text,
						'manufacturer_id'   => $product_info['manufacturer_id'],
						'cumulative_is_active' => false,
						'coupon_is_active' => false,
						'price'    => number_format($price, 2, ',', ' '),
						'total'    => number_format($total, 2, ',', ' ')
						);
						}
					*/
				}
				
				
				$voucher_data = [];
				$vouchers = $this->model_sale_order->getOrderVouchers($order_id);
				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}
				
				$total_data = $this->model_sale_order->getOrderTotals($order_id);
				$real_total_data = [];
				
				//Оплачено на данный момент по заказу
				$paid_on_current_moment = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
				
				//Если сумма на счету покупателя БОЛЬШЕ, чем остающиеся в !следующих поставках товары!, то мы берем только кусочек предоплаты, и частично оплачиваем данную поставку				
				//если есть следующие поставки (сумма > 0)				
				if ($next_sub_total > 0 && $next_sub_total < $paid_on_current_moment){
					
					$to_remove_from_paid_on_current_moment = $paid_on_current_moment - $next_sub_total;	
					
					} elseif ($next_sub_total > 0 && $next_sub_total > $paid_on_current_moment) {
					
					$to_remove_from_paid_on_current_moment = 0;
					
					} else {
					
					$to_remove_from_paid_on_current_moment = $paid_on_current_moment;
					
				}							
				
				$total_total = 0;
				$has_formatted_prepay = false;
				foreach ($total_data as $total){
					
					if ($total['code'] != 'total'){
						
						$add_this_to_real_total = true;
						$add_this_value_to_real_total = true;
						
						//доставка, если по тарифу
						$please_do_not_change_text = false;
						if ((int)$total['value_national'] == 0 && bool_real_stripos($total['text'], 'тариф')){
							//	$total['title'] = $total['title'] .': '. $total['text'];
							$total['title'] = 'Доставка по тарифам курьерской службы';
							$total['text'] = '0';
							//	$total['text'] = 'nop';
							$please_do_not_change_text = true;
						}
						
						if ((int)$total['value_national'] == 0 && bool_real_stripos($total['title'], 'Самовывоз')){						
							$total['title'] = 'Самовывоз';
							$total['text'] = '0';
							$please_do_not_change_text = true;
						}
						
						
						//
						if (((int)$total['value_national'] == 0 || bool_real_stripos($total['text'], 'Бесплатно')) && !$please_do_not_change_text){
							//$total['text'] = $this->currency->format(0, $order_info['currency_code'], 1);
							$total['text'] = '0';
						}
						
						//Сумма товаров на данный чек			
						if ($total['code']  ==  'sub_total'){
							//$total['text'] = $this->currency->format($sub_total, $order_info['currency_code'], 1);
							$total['text'] = $sub_total;
							$total['value_national'] = $sub_total;
						}	
						
						
						//Скидка на день рождения - самая первая
						if (($total['value_national'] < 0) && (bool_real_stripos($total['title'], 'рождения') && !bool_real_stripos($total['title'], '%') || $total['code'] == 'discount_regular')){
							
							$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $total['title']);
							
							if ($percent_of_discount && is_numeric($percent_of_discount)){
								$this->data['birthday_discount_percent'] = $percent_of_discount;
							}
							
							if ($total['for_delivery']){
								$d_sub_total = 0;
								$d_sub_total_national = 0;
								
								foreach ($products as $order_product) {	
									
									if ($order_product['delivery_num'] == $delivery_num) {
										if ($this->model_sale_order->checkIfProductHasBirthdayDiscount($order_product['product_id'], $order_id, $order_product['order_product_id'])){
											if ($order_product['delivery_num'] == $total['for_delivery']){									
												$d_sub_total += $order_product['total'];
												$d_sub_total_national += $order_product['total_national'];							
											}		
										}
									}
									
								}
								
								if ($percent_of_discount) {
									$amount_of_discount = round((-1 * $d_sub_total_national/100*$percent_of_discount));
									} else {
									$amount_of_discount = 0;
								}
								
								} else {
								
								$d_sub_total = 0;
								$d_sub_total_national = 0;
								
								foreach ($products as $order_product) {	
									
									if ($order_product['delivery_num'] == $delivery_num) {
										
										if ($this->model_sale_order->checkIfProductHasBirthdayDiscount($order_product['product_id'], $order_id, $order_product['order_product_id'])){												
											$d_sub_total += $order_product['total'];
											$d_sub_total_national += $order_product['total_national'];								
										}
										
									}
									
								}
								
								if ($percent_of_discount) {
									$amount_of_discount = round((-1 * $d_sub_total_national/100*$percent_of_discount));
									} else {
									$amount_of_discount = 0;
								}
								
								if (abs($total['value_national'] - $amount_of_discount) <= 2){
									$total['value_national'] = $total['value_national'];					
									} else {
									$total['value_national'] = $amount_of_discount;
								}
								
								
							}
							
							foreach ($product_data as &$product_data_product){
								if ($this->model_sale_order->checkIfProductHasBirthdayDiscount($product_data_product['product_id'], $order_id, $product_data_product['order_product_id'])){
									$product_data_product['birthday_is_active'] = true;
									} else {
									$product_data_product['birthday_is_active'] = false;
								}
							}							
							
							if ($total['for_delivery'] && ($total['for_delivery'] != $delivery_num)){
								$add_this_to_real_total = false;
								$add_this_value_to_real_total = false;
							}
						}
						
						//Скидка на товарный чек (выбираем, если где-то в тайтле есть '%'), и это ниразу не день рождения
						elseif (($total['value_national'] < 0) && bool_real_stripos($total['title'], 'Скидка') && bool_real_stripos($total['title'], '%'))
						{				
							
							$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $total['title']);
							
							if ($total['for_delivery']){
								
								$d_sub_total = 0;
								$d_sub_total_national = 0;
								
								
								foreach ($products as $order_product) {								
									if ($order_product['delivery_num'] == $total['for_delivery']){									
										$d_sub_total += $order_product['total'];
										$d_sub_total_national += $order_product['total_national'];							
									}								
								}
								
								if ($percent_of_discount) {
									$amount_of_discount = round((-1 * $d_sub_total_national/100*$percent_of_discount));
									} else {
									$amount_of_discount = 0;
								}
								
								} else {
								
								//считаем скидку только для товаров (саб_тотал)
								if ($percent_of_discount) {
									$amount_of_discount = round((-1 * $sub_total/100*$percent_of_discount));
									} else {
									$amount_of_discount = 0;
								}
								
							}
							
							
							if (abs($total['value_national'] - $amount_of_discount) <= 2){
								$total['value_national'] = $total['value_national'];					
								} else {
								$total['value_national'] = $amount_of_discount;
							}
							
							$total['text'] = $total['value_national'];	
							
							if ($total['for_delivery'] && ($total['for_delivery'] != $delivery_num)){
								$add_this_to_real_total = false;
								$add_this_value_to_real_total = false;
							}
						}
						
						
						//фиксированная скидка, не процентная
						elseif (($total['value_national'] < 0) && bool_real_stripos($total['title'], 'Скидка') && bool_real_stripos($total['code'], 'custom_discount') && !bool_real_stripos($total['title'], '%')){
							
							if ($total['for_delivery'] && ($total['for_delivery'] != $delivery_num)){
								$add_this_to_real_total = false;
								$add_this_value_to_real_total = false;
							}
						}
						
						elseif (($total['value_national'] < 0) && bool_real_stripos($total['title'], 'Бонус') && bool_real_stripos($total['code'], 'reward')){
							
							if ($total['for_delivery'] && ($total['for_delivery'] != $delivery_num)){
								$add_this_to_real_total = false;
								$add_this_value_to_real_total = false;
							}
						}
						
						//накопительная скидка	
						elseif ($total['code']  ==  'shoputils_cumulative_discounts' || bool_real_stripos($total['title'], 'накопительная')){
							$this->load->model('total/scd_recount');	
							$this->load->model('catalog/product');	
							
							$scd_discount = $this->load->model_total_scd_recount->getCustomerDiscount( $order_info['store_id'],  $order_info['customer_group_id'],  $order_info['language_id'],  $order_info['customer_id']);
							
							$this->data['scd_discount_percent'] = $scd_discount['percent'];
							
							$_scd_discount_percent = (int)preg_replace('~[^0-9]+~','', $total['title']);
							
							if (($this->data['scd_discount_percent'] != $_scd_discount_percent) && is_numeric($_scd_discount_percent)){
								$this->data['scd_discount_percent'] = $_scd_discount_percent;
								$scd_discount['percent'] = $_scd_discount_percent;
							}
							
							if (!isset($scd_discount['excluded_manufacturers'])){
								$scd_discount['excluded_manufacturers'] = '';
							}
							
							$excluded_manufacturers = explode(',', str_replace(',,',',',mb_substr($scd_discount['excluded_manufacturers'],0,-1)));
							
							$p_sub_total = 0;
							$p_sub_total_national = 0;
							
							foreach ($products as $order_product) {
								
								if ($order_product['delivery_num'] == $delivery_num) {
									
									$real_product = $this->model_catalog_product->getProduct($order_product['product_id']);	
									$manufacturer_id = isset($real_product['manufacturer_id'])?$real_product['manufacturer_id']:0;							
									
									if (!in_array($manufacturer_id, $excluded_manufacturers)){																
										
										//если задано что скидка считается только для конкретной поставки и не равна нулю
										if ($total['for_delivery']){
											
											if ($order_product['delivery_num'] == $total['for_delivery']){
												$p_sub_total += $order_product['total'];
												$p_sub_total_national += $order_product['total_national'];
											}
											
											} else {
											//безусловное добавление	
											$p_sub_total += $order_product['total'];
											$p_sub_total_national += $order_product['total_national'];
										}
										
									}
									
								}
								
								$total['value_national'] = (float)round(-1 * $p_sub_total_national * ($scd_discount['percent'] / 100));	
								
								$total['text'] = $this->currency->format($total['value_national'], $order_info['currency_code'], '1');
								$total['code']  = $total['code'];							
								
							}
							
							foreach ($product_data as &$product_data_product){
								if (!in_array($product_data_product['manufacturer_id'], $excluded_manufacturers)){
									$product_data_product['cumulative_is_active'] = true;
									} else {
									$product_data_product['cumulative_is_active'] = false;
								}
								
							}
							
							if ($total['for_delivery'] && ($total['for_delivery'] != $delivery_num)){
								$add_this_to_real_total = false;
								$add_this_value_to_real_total = false;
							}
							
						}
						
						//пересчет купона, если он есть
						elseif (($total['value_national'] <= 0) && bool_real_stripos($total['title'], 'Промокод') && $total['code']  ==  'coupon'){
							
							$coupon_name = $this->model_sale_order->getCouponName($total['title']);
							$coupon_query = $this->db->query("SELECT * FROM `coupon` WHERE code = '" . $this->db->escape($coupon_name) . "'");
							
							if ($coupon_query->num_rows && ($coupon_query->row['type'] == "P")){
								//needs to be recounted
								$active_coupon_products = $this->model_sale_order->getCouponProducts($coupon_name, $just_product_ids, $order_id);
								
								
								$coupon_discount = 0;
								foreach ($products as $o_order_product){
									
									if (in_array($o_order_product['product_id'], $active_coupon_products) && $o_order_product['delivery_num'] == $delivery_num){
										$coupon_discount += ($o_order_product['total_national'] / 100 * $coupon_query->row['discount']);								
									}
									
								}
								
								$this->data['coupon_name'] = $coupon_name;
								$this->data['coupon_percent'] = $coupon_query->row['discount'];
								
								foreach ($product_data as &$product_data_product){
									if (in_array($product_data_product['product_id'], $active_coupon_products)){
										$product_data_product['coupon_is_active'] = true;
										} else {
										$product_data_product['coupon_is_active'] = false;
									}
								}
								
								$coupon_discount = -1 * round($coupon_discount);
								
								$total['value_national'] = $coupon_discount;						
								$total['text'] = $this->currency->format($coupon_discount, $order_info['currency_code'], '1');
								
								
								}	elseif ($coupon_query->num_rows && ($coupon_query->row['type'] == "F")){
								
								$this->data['coupon_name'] = $coupon_name;
								//$this->data['coupon_percent'] = $coupon_query->row['discount'];
								
								$percent_of_discount = abs($total['value_national']/$full_sub_total*100);
								$coupon_discount = 0;
								
								//recount for each product								
								foreach ($products as $o_order_product) {
									if ($o_order_product['delivery_num'] == $delivery_num){																											
										$coupon_discount += ($o_order_product['total_national'] / 100 * $percent_of_discount);																		
									}
								}
								
								$coupon_discount = -1 * round($coupon_discount);
								
								$total['value_national'] = $coupon_discount;						
								$total['text'] = $this->currency->format($coupon_discount, $order_info['currency_code'], '1');
								
								} elseif ($coupon_query->num_rows && ($coupon_query->row['type'] == "3" || $coupon_query->row['type'] == "4" || $coupon_query->row['type'] == "5")) { 
								
								$active_coupon_products = $this->model_sale_order->getCouponProducts($coupon_name, $just_product_ids, $order_id);
								
								$coupon_discount = 0;
								$active_order_product_id = 0;
								foreach ($product_data as $product_data_product){
									if (in_array($product_data_product['product_id'], $active_coupon_products)){
										if ($product_data_product['price'] <= $coupon_discount){
											$coupon_discount 		 = $product_data_product['price'];
											$active_order_product_id = $product_data_product['order_product_id'];
										}
										
										if ($coupon_discount == 0){
											$coupon_discount = $product_data_product['price'];
											$active_order_product_id = $product_data_product['order_product_id'];
										}
									}
								}
								
								$this->data['coupon_name'] = $coupon_name;
								
								foreach ($product_data as &$product_data_product){
									if ($product_data_product['order_product_id'] == $active_order_product_id){
										$product_data_product['coupon_presentlogic_is_active'] = true;
										} else {
										$product_data_product['coupon_presentlogic_is_active'] = false;
									}
								}
							}
							
						}
						
						//У заказа была предоплата. Это текущий "+" счет заказа
						if (bool_real_stripos($total['title'], 'предоплата') || $total['code'] == 'transfer_plus_prepayment'){
							$total['title'] = 'Предоплата';
							
							//$total['text'] = $this->currency->format(-1 * $paid_on_current_moment, $order_info['currency_code'], '1');
							$total['text'] = -1 * $paid_on_current_moment;
							$total['value_national'] = -1 * $paid_on_current_moment;
							$has_formatted_prepay = true;
							
						}
						
						//учет доставки только на первую поставку
						if ((bool_real_stripos($total['title'], 'Доставка') || $total['code'] == 'shipping') && $total['value_national'] > 0){
							
							if ($delivery_num > 1){
								$add_this_to_real_total = false;
								$add_this_value_to_real_total = false;
							}
							
						}
						
						
						
						//если накопительная скидка какого-то хера равна нулю
						if ( ($total['code']  ==  'shoputils_cumulative_discounts' || bool_real_stripos($total['title'], 'накопительная')) && $total['value_national'] == 0){
							$add_this_to_real_total = false;
							$add_this_value_to_real_total = false;
						}
						
					}
					
					if (!(bool_real_stripos($total['title'], 'предоплата') || $total['code'] == 'transfer_plus_prepayment')) {
						
						if ($total['code'] != 'total'){
							if ($add_this_value_to_real_total){
								$total['text'] = $total['value_national'];			
							}
						}
						
						if ($total['code'] != 'total'){
							if ($add_this_value_to_real_total){
								$total_total += $total['value_national'];						
							}
						}														
						
						if ($total['code'] != 'total'){
							
							if ($add_this_to_real_total){
								$real_total_data[] = $total;
							}								
						}
					}
					
				}
				
				if (($to_remove_from_paid_on_current_moment > 0)) {						
					//предоплата не вписана в итоги заказа, но на счету покупателя есть положительный баланс и мы можем его использовать. to_remove_from_paid_on_current_moment > 0. Если баланс пуст, то это условие и не будет выполнено
					//мы вставим ее перед окончательным тоталом
					$total = [];
					
					$total['title'] = 'Предоплата';
					$total['code'] = 'transfer_plus_prepayment';
					//$total['text'] = $this->currency->format(-1 * $paid_on_current_moment, $order_info['currency_code'], '1');
					$total['text'] = -1 * $to_remove_from_paid_on_current_moment;
					$total['value_national'] = -1 * $to_remove_from_paid_on_current_moment;					
					$total_total += $total['value_national'];
					
					$real_total_data[] = $total;
				}
				
				if (abs($total_total) <= 2){										
					$total_total = 0;																
				}
				
				foreach ($total_data as $total){
					if ($total['code'] == 'total'){															
						$total['title'] = 'Всего к оплате';
						//$total['text'] = $this->currency->format($total_total, $order_info['currency_code'], '1');
						$total['text'] = $total_total;
						$real_total_data[] = $total;
					}
				}
				
				$temp_real_total = $real_total_data;
				$real_total_data = [];
				foreach ($temp_real_total as $total){
					if ($total['code'] == 'total' || $total['text'] != 'nop'){						
						$total['text'] = number_format(round($total['text']), 2, ',', ' ');
						} else {
						$total['text'] = false;
					}
					$real_total_data[] = $total;
				}
				
				//recount totals
				
				$this->load->model('setting/setting');
				$this->load->model('tool/image');
				$logo = $this->model_setting_setting->getKeySettingValue('config', 'config_logo', (int)$order_info['store_id']);
				
				
				//Метод доставки короткий
				if (!isset($do_not_replace_shipping_method)){
					$short_shipping_method = trim(mb_ucfirst(trim(str_ireplace('Доставка', '', $order_info['shipping_method']))));
					} else {
					$short_shipping_method = 'Самовывоз';
				}
				
				$comments = [];
				if ($order_info['comment']){
					$comments[] = trim((EmailTemplate::isHTML($order_info['comment'])) ? html_entity_decode($order_info['comment'], ENT_QUOTES, 'UTF-8') : nl2br($order_info['comment'], true));
				}
				
				if ($manager_comments = $this->model_sale_order->getOrderHistoriesCommentsForCourier($order_id)){
					foreach ($manager_comments as $_maco){
						$comments[] = nl2br($_maco['comment']);
					}
					
				}				
				
				$this->data['order'] = array(
				'order_id'	         => $order_id,
				'order_info'	     => $order_info,
				'store_logo'		 => $this->model_tool_image->greyscale($logo, 80, 40, true),
				'invoice_no'         => $invoice_no,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
				'store_name'         => $order_info['store_name'],
				'store_url'          => rtrim($order_info['store_url'], '/'),
				'store_address'      => nl2br($store_address),
				'store_email'        => $store_email,
				'store_telephone'    => $store_telephone,
				'store_telephone2'   => $store_telephone2,
				'email'              => $order_info['email'],
				'telephone'          => $order_info['telephone'],
				'related_orders'     => $this->model_sale_order->getRelatedOrders($order_id),
				'shipping_address'   => $shipping_address,
				'shipping_method'    => $order_info['shipping_method'],
				'short_shipping_method' => $short_shipping_method,
				'payment_address'    => $payment_address,
				'payment_company_id' => $order_info['payment_company_id'],
				'payment_tax_id'     => $order_info['payment_tax_id'],
				'payment_method'     => $order_info['payment_method'],
				'product'            => $product_data,
				'max_sku_length'     => max($sku_lengths),
				'voucher'            => $voucher_data,
				'total'              => $real_total_data,
				'need_sign'          => $this->customerNeedSign($order_info['shipping_code']),
				'comment'            => $comments,
				'customer'			 => $this->model_sale_customer->getCustomer($order_info['customer_id']),
				'prim'				 => $prim,
				'no_return'			 => $no_return,
				);
			}
			
			if ($return){
				$this->template = 'sale/order_preinvoice_auto.tpl';
				} else {
				$this->template = 'sale/order_preinvoice.tpl';
			}
			
			$out = $this->render();
			
			if ($return){
				return $out;	
				} else {
				$this->response->setOutput($out);
			}			
		}
		
		public function count_sum_cheque(){
			$sums = $this->request->post['sums'];
			$ccode = $this->request->post['currency_code'];
			
			$total = 0;
			foreach ($sums as $sum){
				$sum = str_replace(' ', '', $sum);
				$sum = trim($sum);
				
				preg_match_all('/\-?\d+/', $sum, $matches);
				
				if (is_array($matches) && isset($matches[0]) && isset($matches[0][0])){		
					$total += (float)$matches[0][0];
				}
			}				
			
			echo number_format($total, 2, ',', ' ');
		}
				
		public function save_and_print_invoice($auto_gen = false, $data = array()){						
			if ($auto_gen) {
				if ($data['delivery_num'] == 1){
					$_cheque_num = $data['cheque_num'];
					} else {
					$_cheque_num = $data['cheque_num'] . '-' . ($data['delivery_num']-1);
				}
				
				$_data_to_invoice = array(
				
				'order_id'      => (int)$data['order_id'],
				'cheque_num'    => $_cheque_num,
				'delivery_num'  => $data['delivery_num'],
				'cheque_type'   => $data['cheque_type'],
				'cheque_date'   => '',
				'cheque_prim'   => false,
				'cheque_return' => false,				
				);
				
				$html = $this->single_invoice(true, $_data_to_invoice);
				$order_id = (int)$data['order_id'];
				$invoice_name = $_cheque_num;
				
				} else {
				
				$html = $this->request->post['html'];
				$order_id = (int)$this->request->post['order_id'];
				$invoice_name = $this->request->post['invoice_name'];
				$delivery_num = $this->request->post['delivery_num'];				
			}
			
			$real_dir = date('Y') . '/' . date('m') . '/' . date('d') . '/';
			$dir = DIR_EXPORT . 'odinass/cheques/' . $real_dir;
			$filename = $order_id . '_' . md5($invoice_name . time());
			if ($auto_gen){
				$filename .= '_autogen';
			}
			$filename .= '.pdf';
			
			if (!is_dir($dir)){
				mkdir($dir, 0775, $recursive = true);
			}
			
			$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4'
			]);		
			
			$html = str_replace(' !important', '', $html);
			$html = str_replace('totals_cheque_row_wide_to_remove', '', $html);
			$html = html_entity_decode($html);
			
			$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; }</style>\n\n".$html);
			$mpdf->Output($dir . $filename, 'F');
			
			if ($auto_gen){
				$this->db->query("DELETE FROM order_invoice_history WHERE order_id = '" . (int)$order_id . "' AND invoice_name LIKE ('" . $invoice_name . "') AND auto_gen = 1");
				$_user_id = 54;
				} else {
				$_user_id = (int)$this->user->getId();
			}		
			
			$this->db->query("INSERT INTO order_invoice_history SET				
			order_id = '" . (int)$order_id . "', 
			invoice_name = '" . $this->db->escape($invoice_name) . "',
			html = '" . $this->db->escape($html) . "',
			datetime = NOW(),
			user_id = '" . $_user_id . "',
			filename = '" . $this->db->escape($real_dir . $filename) . "',
			auto_gen = '" .(int)$auto_gen. "'
			");
			
			$invoice_id = (int)$this->db->getLastId();
			
			if ($auto_gen){
				return $invoice_id;
				} else {
				echo $invoice_id;
			}
		}
				
		public function invoice() {
			$this->language->load('sale/order');
			
			$this->data['title'] = $this->language->get('heading_title');
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$this->data['base'] = HTTPS_SERVER;
				} else {
				$this->data['base'] = HTTP_SERVER;
			}
			
			$this->data['direction'] = $this->language->get('direction');
			$this->data['language'] = $this->language->get('code');
			
			$this->data['text_invoice'] = $this->language->get('text_invoice');
			
			$this->data['text_order_id'] = $this->language->get('text_order_id');
			$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
			$this->data['text_date_added'] = $this->language->get('text_date_added');
			$this->data['text_telephone'] = $this->language->get('text_telephone');
			$this->data['text_fax'] = $this->language->get('text_fax');
			$this->data['text_to'] = $this->language->get('text_to');
			$this->data['text_company_id'] = $this->language->get('text_company_id');
			$this->data['text_tax_id'] = $this->language->get('text_tax_id');
			$this->data['text_ship_to'] = $this->language->get('text_ship_to');
			$this->data['text_payment_method'] = $this->language->get('text_payment_method');
			$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
			
			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_comment'] = $this->language->get('column_comment');
			
			$this->load->model('sale/order');
			
			$this->load->model('setting/setting');
			
			$this->data['orders'] = [];
			
			$orders = [];
			
			if (isset($this->request->post['selected'])) {
				$orders = $this->request->post['selected'];
				} elseif (isset($this->request->get['order_id'])) {
				$orders[] = $this->request->get['order_id'];
			}
			
			foreach ($orders as $order_id) {
				$order_info = $this->model_sale_order->getOrder($order_id);
				
				if ($order_info) {
					$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
					
					if ($store_info) {
						$store_address = $store_info['config_address'];
						$store_email = $store_info['config_email'];
						$store_telephone = $store_info['config_telephone'];
						$store_fax = $store_info['config_fax'];
						} else {
						$store_address = $this->config->get('config_address');
						$store_email = $this->config->get('config_email');
						$store_telephone = $this->config->get('config_telephone');
						$store_fax = $this->config->get('config_fax');
					}
					
					if ($order_info['invoice_no']) {
						$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
						} else {
						$invoice_no = '';
					}
					
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
					
					$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
					
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
					
					$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
					
					$product_data = [];
					
					$products = $this->model_sale_order->getOrderProducts($order_id);
					
					foreach ($products as $product) {
						$option_data = [];
						
						$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
						
						foreach ($options as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
								} else {
								$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
							}
							
							$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
							);
						}
						
						$product_data[] = array(
						'name'     => $product['name'],
						'order_product_id' => $product['order_product_id'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
						);
					}
					
					$voucher_data = [];
					
					$vouchers = $this->model_sale_order->getOrderVouchers($order_id);
					
					foreach ($vouchers as $voucher) {
						$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
						);
					}
					
					$total_data = $this->model_sale_order->getOrderTotals($order_id);
					
					$this->data['orders'][] = array(
					'order_id'	         => $order_id,
					'invoice_no'         => $invoice_no,
					'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $order_info['email'],
					'telephone'          => $order_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $order_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_company_id' => $order_info['payment_company_id'],
					'payment_tax_id'     => $order_info['payment_tax_id'],
					'payment_method'     => $order_info['payment_method'],
					'product'            => $product_data,
					'voucher'            => $voucher_data,
					'total'              => $total_data,
					'comment'            => nl2br($order_info['comment'])
					);
				}
			}
			
			$this->template = 'sale/order_invoice.tpl';
			
			$this->response->setOutput($this->render());
		}
				
		public function getManagerInfoAjax(){
			$this->load->model('sale/order');
			
			$manager_id = $this->request->get['manager_id'];
			
			$info = $this->model_sale_order->getOrdersByManager($manager_id);
			
			$out = 'Всего заказов: '.$info['total'].'<br /><br />На:<br />';
			
			foreach ($info['total_info'] as $ttl){
				$out .= $this->currency->format($ttl['total'], $ttl['currency_code'], $ttl['currency_value']).'<br />';		
			}
			
			echo $out;			
		}
		
		public function setNoGoodProductWaitList(){
			$order_product_id = $this->request->get['order_product_id'];			
			$waitlist = $this->request->get['waitlist'];	
			
			$this->db->query("UPDATE `order_product_nogood` SET waitlist = '" . (int)$waitlist . "' WHERE `order_product_id` = '". (int)$order_product_id ."'");
			$check_query = $this->db->query("SELECT waitlist FROM order_product_nogood WHERE `order_product_id` = '". (int)$order_product_id ."' LIMIT 1");
			
			echo $check_query->row['waitlist'];
		}		
		
		public function setProductIsAvailableOnSite(){
			$this->load->model('user/user');
			
			$order_product_id = $this->request->get['order_product_id'];			
			$onsite = $this->request->get['onsite'];
			$isgood = $this->request->get['isgood'];
			
			$splog = new Log('product_stock_from_order.txt');
			
			if ($isgood) {
				$get_product_id_query = $this->db->query("SELECT product_id,order_id FROM order_product WHERE `order_product_id` = '". (int)$order_product_id ."' LIMIT 1");
				} else {
				$get_product_id_query = $this->db->query("SELECT product_id,order_id FROM order_product_nogood WHERE `order_product_id` = '". (int)$order_product_id ."' LIMIT 1");
			}
			
			$product_id = (isset($get_product_id_query->row['product_id']))?$get_product_id_query->row['product_id']:0;
			$order_id = (isset($get_product_id_query->row['order_id']))?$get_product_id_query->row['order_id']:0;
			
			if ($onsite){
				$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_stock_status_id') . "' WHERE product_id = '" . (int)$product_id . "'");
				} else {
				$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_not_in_stock_status_id') . "' WHERE product_id = '" . (int)$product_id . "'");
			}
			
			$check_query = $this->db->query("SELECT stock_status_id FROM product WHERE `product_id` = '". (int)$product_id ."' LIMIT 1");
			
			$_text = "Заказ $order_id: ";
			
			
			if ($check_query->row['stock_status_id'] == $this->config->get('config_stock_status_id')){
				echo '1';
				$_text .= $this->model_user_user->getRealUserNameById($this->user->getId()) . " поставил товар $product_id в наличии на сайте.";
				} elseif ($check_query->row['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')){
				echo '0';
				$_text .= $this->model_user_user->getRealUserNameById($this->user->getId()) . " снял товар $product_id с наличия на сайте.";
			}
			
			
			$splog->write($_text);
		}
		
		public function setGoodProduct(){
			
			if (in_array($this->user->getUserGroup(), array(1, 15, 12, 19, 13, 23)) or $this->user->getIsAV()) {
				$this->load->model('sale/customer');
				
				$order_product_id = $this->request->get['order_product_id'];
				
				$isgood_query = $this->db->query("SELECT good, product_id FROM `order_product` WHERE `order_product_id` = '". (int)$order_product_id ."' LIMIT 1");		
				if ($isgood_query->row){
					$order_product_query 	= $this->db->query("SELECT * FROM `order_product` WHERE `order_product_id` = '". (int)$order_product_id ."' LIMIT 1");
					$order_product 			= $order_product_query->row;

					$this->db->query("DELETE FROM `order_product_nogood` WHERE `order_product_id` = '". (int)$order_product_id ."'");
					
					$this->db->query("INSERT INTO order_product_nogood SET
					order_product_id 	= '" . (int)$order_product['order_product_id'] . "', 
					order_id 			= '" . (int)$order_product['order_id'] . "', 
					product_id 			= '" . (int)$order_product['product_id'] . "',
					ao_id 				= '" . (int)$order_product['ao_id'] . "', 	
					ao_product_id 		= '" . (int)$order_product['ao_product_id'] . "',
					name 				= '" . $this->db->escape($order_product['name']) . "', 
					model 				= '" . $this->db->escape($order_product['model']) . "', 
					quantity 			= '" . (int)$order_product['quantity'] . "', 
					price 				= '" . (float)$order_product['price'] . "', 
					price_national 		= '" . (float)$order_product['price_national'] . "',
					original_price_national = '" . (float)$order_product['original_price_national'] . "',
					total 				= '" . (float)$order_product['total'] . "',
					total_national 		= '" . (float)$order_product['total_national'] . "',
					tax					= '" . (float)$order_product['tax'] . "', 
					reward 				= '" . (int)$order_product['reward'] . "', 
					good 				= '0',
					waitlist 			= '0',
					taken 				= '" . (int)$order_product['taken'] . "'");
					$this->db->query("DELETE FROM `order_product` WHERE `order_product_id` = '". (int)$order_product_id ."'");
					$r = false;
					
					$customer_comment_text = "Товар " . $order_product['model'] . " помечен как нет в наличии";
					$customer_query = $this->db->query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_product['order_id'] . "'");
					
					$_data = array(
					'customer_id' 		      => $customer_query->row['customer_id'], 
					'comment'     		      => $customer_comment_text,
					'order_id'    		      => (int)$order_product['order_id'],
					'call_id'     		      => 0,
					'manager_id'    	      => 0,
					'need_call'    			  => 0,
					'segment_id'    		  => 0,
					'order_status_id'    	  => 0,
					'prev_order_status_id'    => 0,
					'is_error'                => true
					);
					
					$this->model_sale_customer->addHistoryExtended($_data);
					
					} else {
						
					$order_product_query = $this->db->query("SELECT * FROM `order_product_nogood` WHERE `order_product_id` = '". (int)$order_product_id ."' LIMIT 1");
					$order_product = $order_product_query->row;

					$this->db->query("DELETE FROM `order_product` WHERE `order_product_id` = '". (int)$order_product_id ."'");
					
					$this->db->query("INSERT INTO order_product SET
					order_product_id 		= '" . (int)$order_product['order_product_id'] . "', 
					order_id 				= '" . (int)$order_product['order_id'] . "', 
					product_id 				= '" . (int)$order_product['product_id'] . "',
					ao_id 					= '" . (int)$order_product['ao_id'] . "',
					ao_product_id 			= '" . (int)$order_product['ao_product_id'] . "',
					name 					= '" . $this->db->escape($order_product['name']) . "', 
					model 					= '" . $this->db->escape($order_product['model']) . "', 
					quantity 				= '" . (int)$order_product['quantity'] . "', 
					price 					= '" . (float)$order_product['price'] . "', 
					price_national 			= '" . (float)$order_product['price_national'] . "',
					original_price_national = '" . (float)$order_product['original_price_national'] . "',
					total 					= '" . (float)$order_product['total'] . "',
					total_national 			= '" . (float)$order_product['total_national'] . "',
					tax 					= '" . (float)$order_product['tax'] . "', 
					reward 					= '" . (int)$order_product['reward'] . "', 
					good 					= '1', 
					taken 					= '" . (int)$order_product['taken'] . "'");

					$this->db->query("DELETE FROM `order_product_nogood` WHERE `order_product_id` = '". (int)$order_product_id ."'");
					$r = true;	
					
					$customer_comment_text = "Товар " . $order_product['model'] . " помечен как есть в наличии";
					$customer_query = $this->db->query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_product['order_id'] . "'");
					
					$_data = array(
					'customer_id' 		      => $customer_query->row['customer_id'], 
					'comment'     		      => $customer_comment_text,
					'order_id'    		      => (int)$order_product['order_id'],
					'call_id'     		      => 0,
					'manager_id'    	      => 0,
					'need_call'    			  => 0,
					'segment_id'    		  => 0,
					'order_status_id'    	  => 0,
					'prev_order_status_id'    => 0,
					'is_error'                => true
					);
					
					$this->model_sale_customer->addHistoryExtended($_data);
				}

				
				if ($this->config->get('config_show_profitability_in_order_list')){
					$this->registry->get('rainforestAmazon')->offersParser->PriceLogic->countOrderProfitablility($order_product['order_id']);
				}
				
				echo $r?'Есть':'Нет';		
				} else {
				return false;
			}
		}
		
		public function setTakenProduct(){			
			if (in_array($this->user->getUserGroup(), array(1, 15, 12, 19, 27, 13, 23)) or $this->user->getIsAV()) {
				$order_product_id = $this->request->get['order_product_id'];
				$this->db->query("UPDATE `order_product` SET `taken` = NOT(`taken`) WHERE `order_product_id` = '". (int)$order_product_id ."'");	
				
				$q = $this->db->query("SELECT taken FROM `order_product` WHERE `order_product_id` = '". (int)$order_product_id ."'");	
				$r = $q->row['taken'];
				
				echo $r?'Да':'Нет';		
				} else {
				return false;
			}
		}
		
		public function getAllManagers(){
			$this->load->model('user/user');
			
			if ($this->user->getIsManager()){
				$filter_groups = array($this->user->getUserGroup());
				} else {
				$filter_groups = MANAGER_GROUPS;
			}
			
			$managers = $this->model_user_user->getUsersByGroups($filter_groups, true);		
			
			return $managers;
		}
		
		public function getAllCouriers(){
			$this->load->model('user/user');
			$couriers = $this->model_user_user->getUsersByGroups(array(13, 26), true);		
			
			return $couriers;
		}
		
		public function getAllManagersWhoCanOwnOrders(){
			$this->load->model('user/user');
			
			if ($this->user->getIsManager()){
				$filter_groups = array($this->user->getUserGroup());
				} else {
				$filter_groups = MANAGER_GROUPS;
				$filter_groups[] = 23;
			}
									
			$managers = $this->model_user_user->getUsersByGroupsOwnOrders($filter_groups, true);
			
			return $managers;
		}
		
		public function changePrepayPayAjax(){
			$this->load->model('sale/order');
			
			$order_id = $this->request->post['order_id'];
			$order = $this->model_sale_order->getOrder($order_id);
			$prepayment_paid_date = isset($this->request->post['prepayment_paid_date'])?$this->request->post['prepayment_paid_date']:'0000-00-00 00:00:00';
			
			if ($this->request->post['prepayment_paid']){
				$this->db->query("UPDATE `order` SET prepayment_paid = 1, prepayment_paid_date = '" .$this->db->escape($prepayment_paid_date). "' WHERE `order_id` = '". (int)$order_id ."'");	
				
				$this->load->model('setting/setting');
				$order_status_id = $this->model_setting_setting->getKeySettingValue('config', 'config_prepayment_paid_order_status_id', (int)$order['store_id']);
				
				//фиксируем факт частичной оплаты
				$data = array(
				'order_status_id' => $order_status_id,
				'order_id' => $order_id,
				'notify' => 1,
				'comment' => 'Зафиксирована оплата'
				);
				
				$this->model_sale_order->addOrderHistory($order_id, $data);
				
				$json = array(
				'html' => 'Ок, факт оплаты зафиксирован!',
				'status_id' => $order_status_id
				);
				
				echo json_encode($json);		
				} else {
				$this->db->query("UPDATE `order` SET prepayment_paid = 0, prepayment_paid_date = '" .$this->db->escape('0000-00-00 00:00:00'). "' WHERE `order_id` = '". (int)$order_id ."'");
				
				$this->load->model('setting/setting');
				$order_status_id = $this->model_setting_setting->getKeySettingValue('config', 'config_confirmed_order_status_id', (int)$order['store_id']);
				
				$data = array(
				'order_status_id' => $order_status_id,
				'order_id' => $order_id,
				'notify' => 1,
				'comment' => 'Менеджером отменен факт оплаты'
				);
				
				$this->model_sale_order->addOrderHistory($order_id, $data);
				
				$json = array(
				'html' => 'Ок, факт оплаты отменен!',
				'status_id' => $order_status_id
				);
				
				echo json_encode($json);			
			}			
		}
		
		public function changeTotalPayAjax(){
			$order_id = $this->request->post['order_id'];
			$total_paid_date = isset($this->request->post['total_paid_date'])?$this->request->post['total_paid_date']:'0000-00-00 00:00:00';;
			
			if ($this->request->post['total_paid']){
				$this->db->query("UPDATE `order` SET total_paid = 1, total_paid_date = '" .$this->db->escape($total_paid_date). "' WHERE `order_id` = '". (int)$order_id ."'");	
				
				echo 'Ок, факт оплаты зафиксирован!. Оплачено '.$this->db->escape($total_paid_date);
				
				} else {
				$this->db->query("UPDATE `order` SET total_paid = 0, total_paid_date = '" .$this->db->escape('0000-00-00 00:00:00'). "' WHERE `order_id` = '". (int)$order_id ."'");	
				
				echo 'Ок, факт оплаты отменен!';	
			}			
		}		
		
		public function changeManagerAjax(){
			$this->load->model('user/user');
			$order_id = $this->request->get['order_id'];
			$manager_id = $this->request->get['manager_id'];
			if ($this->user->getIsAV() || $this->user->getIsMM()){
				$this->db->query("UPDATE `order` SET `manager_id` = ".(int)$manager_id ." WHERE `order_id` = '". (int)$order_id ."'");	
				if ($manager_id > 0) {	
					$manager = array(							
					'name'     => $this->model_user_user->getUserNameById($manager_id), 
					'realname' => $this->model_user_user->getRealUserNameById($manager_id), 
					'id'       => $manager_id,
					'url'      => $this->url->link('sale/order/getManagerInfoAjax', 'token=' . $this->session->data['token'] . '&manager_id=' . $manager_id, 'SSL')
					);
					} else {
					$manager = array(
					'name'     => 'nobody', 
					'realname' => 'nobody', 
					'id'       => 0,
					'url'      => $this->url->link('sale/order/getManagerInfoAjax', 'token=' . $this->session->data['token'] . '&manager_id=0', 'SSL')
					);				
				}
				
				echo ''.$manager['realname'].'';
				} else {
				echo 'Это возможно только для пользователя с админ. правами!';				
			}						
		}
		
		public function removeBillFileAjax(){			
			$order_id = $this->request->get['order_id'];
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($order_id)){
				die();
			}
						
			$this->db->query("UPDATE `order` SET bill_file='' WHERE order_id = '". (int)$order_id ."'");
			echo '';		
		}
		
		public function removeBillFile2Ajax(){
			$order_id = $this->request->get['order_id'];
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($order_id)){
				die();
			}
			
			$this->db->query("UPDATE `order` SET bill_file2='' WHERE order_id = '". (int)$order_id ."'");
			echo '';		
		}
		
		public function getOrderErrors($order_id){										
			return false;
		}
		
		public function resend($order_id = '') {
			
			if(!isset($this->request->get['order_id'])){
				$this->request->get['order_id'] = $order_id;
				//$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
			}							
			
			$errors = $this->getOrderErrors($this->request->get['order_id']);
			if ($errors){				
				$this->session->data['error_error'] = 'Письмо не отправлено! Ошибка! '.implode('<br />', $errors);				
				$this->redirect($this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
				return;
			}
			
			$this->load->model('sale/order');
			$this->load->model('module/emailtemplate');
			$this->load->model('setting/setting');
			
			$this->load->model('kp/work');
			$this->model_kp_work->updateFieldPlusOne('sent_mail_count');
			
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			$data = array(
			'store_id' 			=> $order_info['store_id'],
			'language_id' 		=> $order_info['language_id'],
			'customer_id' 		=> $order_info['customer_id'],
			'emailtemplate_key' => 'order.admin',
			);
			
			$attachments = [];
			if ($order_info['bill_file']){
				$attachments[] = DIR_BILLS . $order_info['bill_file'];
			}
			
			if ($order_info['bill_file2']){
				$attachments[] = DIR_BILLS . $order_info['bill_file2'];
			}
						

			$template = $this->model_module_emailtemplate->getCompleteOrderEmail($this->request->get['order_id'], $data);
			
			$mail = new Mail($this->registry);
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$mail->setSubject($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$template->load('order.admin');
			$template->build();
			$mail = $template->hook($mail);
			foreach($attachments as $attachment){
				$mail->addAttachment($attachment);
			} 	
			$mail->setText($template->getPlainText());				
			
			$mail->setTo($order_info['email']);

			$mail->send();
			$template->sent();
			
			$this->session->data['success'] = 'Письмо-подтверждение отправлено покупателю на почту ' . $order_info['email'];
			
			$this->redirect($this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
		}
		
		public function resendadmin($order_id = '') {
			if(!isset($this->request->get['order_id'])){
				$this->request->get['order_id'] = $order_id;
				//$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
			}			
			
			$this->load->model('sale/order');
			$this->load->model('module/emailtemplate');
			$this->load->model('setting/setting');
			
			$this->load->model('kp/work');
			$this->model_kp_work->updateFieldPlusOne('sent_mail_count');
			
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			$data = array(
			'store_id' => $order_info['store_id'],
			'language_id' => $order_info['language_id'],
			'emailtemplate_key' => 'order.admin',
			);
			
			$attachments = [];
			if ($order_info['bill_file']){
				$attachments[] = DIR_BILLS . $order_info['bill_file'];
			}
			
			if ($order_info['bill_file2']){
				$attachments[] = DIR_BILLS . $order_info['bill_file2'];
			}
			
			$template = $this->model_module_emailtemplate->getCompleteOrderEmail($this->request->get['order_id'], $data);				
			
			$mail = new Mail($this->registry); 
			$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$mail->setSubject($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$template->load('order.admin');
			$template->build();
			$mail = $template->hook($mail);
			foreach($attachments as $attachment){
				$mail->addAttachment($attachment);
			} 				
			$mail->setText($template->getPlainText());			
			$mail->setTo($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));			
			$mail->send();
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
		}
				
		public function showMailPreview(){
			if(!isset($this->request->get['order_id'])){
				$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
			}			
			
			$this->load->model('sale/order');
			$this->load->model('module/emailtemplate');
			$this->load->model('setting/setting');
			
			
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			$data = array(
			'store_id' => $order_info['store_id'],
			'language_id' => $order_info['language_id'],
			'emailtemplate_key' => 'order.admin',
			);
			
			$template = $this->model_module_emailtemplate->getCompleteOrderEmail($this->request->get['order_id'], $data);	
			
			$mail = new Mail($this->registry); 
			$mail->setTo($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$mail->setSubject($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$template->store_id = $order_info['store_id'];
			$template->load('order.admin');
			$template->build();
			$mail = $template->hook($mail);
			$mail->setText($template->getPlainText());			
								
			$this->response->setOutput($mail->getHtml());
		}
		
		public function showPdfPreview(){
			if(!isset($this->request->get['order_id'])){
				$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
			}			
			
			$this->load->model('sale/order');
			$this->load->model('module/emailtemplate');
			$this->load->model('setting/setting');
			
			
			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
			$data = array(
			'store_id' => $order_info['store_id'],
			'language_id' => $order_info['language_id'],
			'emailtemplate_key' => 'order.admin',
			);
			
			$template = $this->model_module_emailtemplate->getCompleteOrderEmail($this->request->get['order_id'], $data);	
			
			$mail = new Mail($this->registry);
			$mail->setTo($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$mail->setSubject($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$template->load('order.admin');
			$template->build();
			$mail = $template->hook($mail);
			$mail->setText($template->getPlainText());									
		}
		
		public function getOrderPrepayNational($order_id){
			$this->load->model('sale/order');
			return $this->model_sale_order->getOrderPrepayNational($order_id);
		}
		
		public function getOrderPrepayNationalPercent($order_id){
			$this->load->model('sale/order');
			return $this->model_sale_order->getOrderPrepayNationalPercent($order_id);
		}
		
		public function loadbottomtext() {
			$this->language->load('sale/order');
			$this->load->model('sale/order');
			
			$template_real_file = DIR_TEMPLATE . 'sale/bottom_texts/' . $this->request->post['tpl_file'] . '.TPL';						
			$order_id = (int)$this->request->post['order_id'];
			$is_bad_good = (int)$this->request->post['is_bad_good'];
			
			$order_info = $this->model_sale_order->getOrder($order_id);
			$template_text = file_get_contents($template_real_file);
			
			$this->data['error'] = $this->getOrderErrors($order_id);						
			
			if ($this->data['error']) {
				$this->template = 'sale/bottom_texts/ERROR.TPL';			
				$this->response->setOutput($this->render());
				return;
			}
			
			$payment_type = '';
			if ($order_info['shipping_country_id'] == 176){
				
				if ($order_info['pay_equire'] || $order_info['pay_equire2'] || $order_info['pay_equirePP']){
					$payment_type = file_get_contents(DIR_TEMPLATE . 'sale/bottom_texts/PAY_TYPE_BY_EQUIRE_RU.TPL');
					} elseif ($order_info['pay_type'] == 'Безналичный расчет') {
					$payment_type = file_get_contents(DIR_TEMPLATE . 'sale/bottom_texts/PAY_TYPE_BY_INVOICE_RU.TPL');
					} else {
					$payment_type = file_get_contents(DIR_TEMPLATE . 'sale/bottom_texts/PAY_TYPE_BY_SBERBANK_RU.TPL');
				}
				
			}
			
			$this->load->model('localisation/legalperson');
			if ($order_info['card_id']){
				$card_info = str_replace(PHP_EOL, '<br />', $this->model_localisation_legalperson->getLegalPersonAdditional($order_info['card_id']));
				} else {
				$card_info = '';
			}
			
			$array_info = array(

			date('d.m.Y', strtotime($order_info['date_added'])),
			$order_info['order_id'],
			date('d.m.Y', strtotime($order_info['date_buy'])),
			date('d.m.Y', strtotime($order_info['date_country'])) . '-' . date('d.m.Y', strtotime($order_info['date_country'])+(60*60*24)),
			date('d.m.Y', strtotime($order_info['date_delivery'])),
			date('d.m.Y', strtotime($order_info['date_delivery_to'])),
			date('d.m.Y', strtotime($order_info['date_maxpay'])),
			
			//Сумма предоплаты, если есть
			$this->currency->format($this->getOrderPrepayNational($order_id), $order_info['currency_code'],'1'),
			
			//Процент предоплаты (если указан)
			$this->getOrderPrepayNationalPercent($order_id),
			
			//Полная сумма
			$this->currency->format($order_info['total_national'], $order_info['currency_code'], '1'),
			
			trim(str_replace('Самовывоз, ', '', $order_info['shipping_method'])),
			
			$this->getPickupPhone($order_info['shipping_code']),
			
			//шаблонизация способа оплаты
			$payment_type,
			
			//номер карты
			$card_info
			);
			
			$array_shortcuts = array(
			'{DATE_ORDER}',
			'{ORDER_ID}',
			'{DATE_BUY}',
			'{DATE_COUNTRY}',
			'{DATE_DELIVERY}',
			'{DATE_DELIVERY_TO}',
			'{DATE_MAXPAY}',
			'{PREPAY_SUM}',
			'{PREPAY_PERC}',
			'{TOTAL_SUM}',
			'{PICKUP_POINT}',
			'{PICKUP_PHONE}',
			'{PAY_TYPE_INSTRUCTION}',
			'{CARD_INSTRUCTION}',
			);
			
			$template = str_replace($array_shortcuts, $array_info, $template_text);
			
			if ($is_bad_good){
				$addon = file_get_contents(DIR_TEMPLATE . 'sale/bottom_texts/ALL_ADD_BAD_GOOD.TPL');
				$template .= $addon;
			}
			
			echo $template;		
		}
		
		public function getStockQuantitiesAjax(){
			$product_id = (int)$this->request->get['product_id'];		
			$this->load->model('catalog/product');
			
			$quantites = $this->model_catalog_product->getStockQuantities($product_id);
			
			$result = '';
			$result .= "Германия: ".			$quantites['quantity_stock'];
			$result .= ",&nbsp;&nbsp;Москва: ".	$quantites['quantity_stockM'];
			$result .= ",&nbsp;&nbsp;Киев: ".	$quantites['quantity_stockK'];
			$result .= ",&nbsp;&nbsp;Минск: ".	$quantites['quantity_stockM'];
			$result .= ",&nbsp;&nbsp;Астана: ".	$quantites['quantity_stockM'];
						
			$this->response->setOutput($result);			
		}
		
		public function getRelatedProductsAjax(){
			$product_id = (int)$this->request->get['product_id'];
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('catalog/product');
			$this->load->model('sale/order');
			$this->load->model('tool/image');
			
			$this->data['products'] = [];
			$this->data['ao_products'] = [];
			
			$this->data['token'] = $this->session->data['token'];
			
			$order = $this->model_sale_order->getOrder($order_id);
			$language_id = $order['language_id'];
			
			//get_product_categories
			$categories = $this->model_catalog_product->getProductCategories($product_id);
			$attributes = $this->model_catalog_product->getProductAttributesByLanguage($product_id, $language_id);
			
			$this->data['main_attributes'] = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product_id, $language_id);
			
			$this->data['special_attributes'] = [];
			
			foreach ($this->data['main_attributes'] as $key => $value){
				if ($value['group_id'] == $this->config->get('config_special_attr_id')){
					$this->data['special_attributes'][] = $value;				
					unset($this->data['main_attributes'][$key]);	
				}
			}
						
			$products_related = $this->model_catalog_product->getProductRelatedWithoutNotIsStock($product_id);
						
			foreach ($products_related as $related_product_id){
				$result = $this->model_catalog_product->getProduct($related_product_id);
				$special = $this->model_catalog_product->getProductSpecialOne($related_product_id);
				
				$main_attributes = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($related_product_id, $language_id);
				$special_attributes = [];
				
				foreach ($main_attributes  as $key => $value){
					if ($value['group_id'] == $this->config->get('config_special_attr_id')){
						$special_attributes[] = $value;				
						unset($main_attributes[$key]);	
					}
				}	
				
				$this->data['products'][] = array(
				'product_id' 		=> $result['product_id'],
				'image'   			=> $this->model_tool_image->resize($result['image'], 100, 100),
				'name' 				=> $result['name'],
				'model' 			=> $result['model'],
				'main_attributes' 	=> $main_attributes,
				'special_attributes' 	=> $special_attributes,
				'price' 				=> $this->currency->format($this->currency->convert($result['price'], $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'),
				'special' 				=> ($special)?$this->currency->format($this->currency->convert($special, $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'):false,
				
				);
			}
			
			$products_ao = $this->model_catalog_product->getProductAdditionalOffer($product_id, true);
			
			foreach ($products_ao as $ao_product){
				$result = $this->model_catalog_product->getProduct($ao_product['ao_product_id']);	
				$special = $this->model_catalog_product->getProductSpecialOne($ao_product['ao_product_id']);
				$price = $this->model_catalog_product->getProductPrice($ao_product['ao_product_id']);
				
				$this_is_special_offer_present = ($ao_product['percent'] == 100);	
				
				if (isset($ao_product['price']) && $ao_product['price'] && $ao_product['price'] > 0){
					$ao_price = $ao_product['price'];
					} else {
					if ($this_is_special_offer_present){
						$ao_price = 0;
						} else {
						$ao_price = (($special)?$special:$price) * $ao_product['percent'] / 100;
					}
				}
				
				$main_attributes = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($ao_product['ao_product_id'], $language_id);
				$special_attributes = [];
				
				foreach ($main_attributes  as $key => $value){
					if ($value['group_id'] == $this->config->get('config_special_attr_id')){
						$special_attributes[] = $value;				
						unset($main_attributes[$key]);	
					}
				}	
				
				$this->data['ao_products'][] = array(
				'product_id' => $result['product_id'],
				'image'   => $this->model_tool_image->resize($result['image'], 100, 100),
				'name' => $result['name'],
				'model' => $result['model'],
				'quantity' => $ao_product['quantity'],
				'price' => $this->currency->format($this->currency->convert($result['price'], $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'),
				'main_attributes' => $main_attributes,
				'special_attributes' => $special_attributes,
				'special' => ($special)?$this->currency->format($this->currency->convert($special, $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'):false,
				'ao_price' => ($ao_price == 0)?'Подарок!':$this->currency->format($this->currency->convert($ao_price, $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'),
				'ao_price_num' => $ao_price,
				'ao_id' => $ao_product['product_additional_offer_id']
				);
			}
			
			
			$this->template = 'sale/order_products_related.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function getReplaceProductsAjax($data = array(), $return = true){
			$product_id = (int)$this->request->get['product_id'];
			$order_id = (int)$this->request->get['order_id'];				
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/category');
			$this->load->model('sale/order');
			$this->load->model('tool/image');
			
			$this->data['categories'] = [];
			$this->data['products'] = [];
			
			$this->data['token'] = $this->session->data['token'];
			
			$order = $this->model_sale_order->getOrder($order_id);
			$language_id = $order['language_id'];
			
			//get_product_categories
			$categories = $this->model_catalog_product->getProductCategories($product_id);
			$attributes = $this->model_catalog_product->getProductAttributesByLanguage($product_id, $language_id);
			
			$this->data['main_attributes'] = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product_id, $language_id);
			
			$this->data['special_attributes'] = [];
			
			foreach ($this->data['main_attributes'] as $key => $value){
				if ($value['group_id'] == $this->config->get('config_special_attr_id')){
					$this->data['special_attributes'][] = $value;				
					unset($this->data['main_attributes'][$key]);	
				}
			}
			
			//got categories, getting categorie attributes
			foreach ($categories as $category){
				$valid_attributes = [];
				
				$category_attributes = $this->model_catalog_category->getAttributesByCategory($category);
				
				//уберем из атрибутов категории те, которых нет в товаре, а наоборот у нас ситуация и невозможна. в результате поимеем массив атрибутов товара с значениями, похожие надо будет искать
				foreach ($category_attributes as $ca_id){
					
					if (isset($attributes[$ca_id])){
						$valid_attributes[$ca_id] = $attributes[$ca_id];
						} else {
						//пропускаем
					}								
					
				}
				
				if (count($valid_attributes) > 0){
					//ищем товары, которые совпадают по таким же критериям, в той же категории
					$result_product_ids = $this->model_catalog_product->getSimilarProductsByAttributes($product_id, $category, $language_id, $valid_attributes);
					
					//таки есть товары
					if ($result_product_ids){
						$real_category = $this->model_catalog_category->getCategory($category);
						
						$products = [];										
						
						foreach ($result_product_ids as $result_id){
							$result = $this->model_catalog_product->getProduct($result_id);
							$special = $this->model_catalog_product->getProductSpecialOne($result_id);
							
							$main_attributes = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($result_id, $language_id);
							$special_attributes = [];
							
							foreach ($main_attributes  as $key => $value){
								if ($value['group_id'] == $this->config->get('config_special_attr_id')){
									$special_attributes[] = $value;				
									unset($main_attributes[$key]);	
								}
							}						
							
							$products[] = array(
							'product_id' => $result['product_id'],
							'image'   => $this->model_tool_image->resize($result['image'], 100, 100),
							'name' => $result['name'],
							'model' => $result['model'],
							'main_attributes' => $main_attributes,
							'special_attributes' => $special_attributes,
							'price' => $this->currency->format($this->currency->convert($result['price'], $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'),
							'special' => ($special)?$this->currency->format($this->currency->convert($special, $this->config->get('config_currency'), $order['currency_code']), $order['currency_code'], '1'):false,							
							);
						}
						
						$this->data['categories'][] = array(
						'name' => $real_category['name'],
						'products' => $products
						);
						
					}
				}
				
			}
			
			
			$this->template = 'sale/order_products_replace.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function updateOrderDeliveryInfoAjax(){
			
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
						
			$date = $this->request->get['date_delivery_actual'];
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($order_id)){
				//die('0');
			}
			
			$this->db->query("UPDATE `order` SET date_delivery_actual = '" . $this->db->escape($date) . "' WHERE order_id = '" . (int)$order_id . "'");
			$query = $this->db->query("SELECT date_delivery_actual FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			if ($query->row['date_delivery_actual'] == '0000-00-00'){
				echo '0';
				} else {
				echo '1';
			}
		}
		
		public function makeOrderXMLForOdinass(){
			$order_id = (int)$this->request->get['order_id'];
			
			$this->load->model('sale/order');
			$order = $this->model_sale_order->getOrder($order_id);
			
			if (in_array((int)$order['order_status_id'], $this->config->get('config_odinass_order_status_id'))){
				$this->load->model('feed/exchange1c');
				$this->model_feed_exchange1c->getOrderXML($order_id);
				
				echo 'ok';
				return false;
			}
			
			echo 'no';
		}
		
		public function updateProductSourceFromOrderAjax(){
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
			
			
			$data = $this->request->post;
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($data['order_id'])){
				die();
			}
			
			if (isset($data['order_product_id']) && isset($data['product_id']) && isset($data['source'])){
				
				$sources = [];
				$source_query = $this->db->query("SELECT source FROM product WHERE product_id = '" . (int)$data['product_id'] . "' LIMIT 1");
				$sources = explode(PHP_EOL, $source_query->row['source']);
				$sources[] = $data['source'];
				$new_sources = [];
				foreach ($sources as $_src){
					$new_src = trim($_src);
					if (mb_strlen($new_src) > 2){
						$new_sources[] = $new_src;
					}
				}
				$new_sources = array_unique($new_sources, SORT_STRING);
				$sources_string = implode(PHP_EOL, $new_sources);
				$this->db->query("UPDATE product SET source = '" . $this->db->escape($sources_string) . "' WHERE product_id = '" . (int)$data['product_id'] . "'");
				$this->db->query("UPDATE `order_product` SET `source` = '" . $this->db->escape($data['source']) . "' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
				
				$this->data = [];
				$this->data['token'] = $this->session->data['token'];
				$this->data['order_product']['real_product']['source'] = $sources_string;
				$this->data['order_product']['real_product']['product_id'] = (int)$data['product_id'];
				$this->data['product_row'] = (int)$data['row'];				
				
				$this->template = 'sale/order_form.sources.tpl';
				$this->response->setOutput($this->render());
				} else {
				echo 'error';
			}
		}		
		
		public function updateOrderProductFieldsAjax(){
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
			
			
			$data = $this->request->post;
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($data['order_id'])){
				die();
			}
			
			if (isset($data['order_product_id']) && isset($data['field']) && isset($data['value'])){
				$check_field_query = $this->db->query("SHOW COLUMNS FROM `order_product` WHERE Field = '" . $this->db->escape($data['field']) . "'");
				if ($check_field_query->num_rows){
					$this->db->query("UPDATE `order_product` SET `" . $this->db->escape($data['field']) . "` = '" .  $this->db->escape($data['value']) . "' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
				}
				
				echo $this->db->escape($data['value']);				
			}			
		}
		
		public function updateOrderHistoryFieldsAjax(){
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
			
			
			$data = $this->request->post;
			
			$this->load->model('sale/order');
			if ($this->model_sale_order->getIfOrderClosed($data['order_id'])){
				//die();
			}
			
			if (isset($data['order_history_id']) && isset($data['field']) && isset($data['value'])){
				$check_field_query = $this->db->query("SHOW COLUMNS FROM `order_history` WHERE Field = '" . $this->db->escape($data['field']) . "'");
				if ($check_field_query->num_rows){
					$this->db->query("UPDATE `order_history` SET `" . $this->db->escape($data['field']) . "` = '" .  $this->db->escape($data['value']) . "' WHERE order_history_id = '" . (int)$data['order_history_id'] . "'");
				}
				
				echo $this->db->escape($data['value']);				
			}			
		}
		
		public function sendNoAccessoriesToContent(){			
		}
		
		public function updateOrderFieldsAjax(){
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				die('no_permission');
			}
			
			$csi_fields = array(	
			'csi_reject',
			'csi_mark',
			'speed_mark',
			'manager_mark',
			'quality_mark',
			'courier_mark',
			'csi_comment',
			'speed_comment',
			'manager_comment',
			'quality_comment',
			'courier_comment',
			'date_delivery_actual'
			);
			
			$data = $this->request->post;
			
			
			if (isset($data['order_id']) && isset($data['field']) && isset($data['value'])){
				
				$this->load->model('sale/order');
				if ($this->model_sale_order->getIfOrderClosed($data['order_id'])){
					if (!in_array($data['field'], $csi_fields) || !$this->user->canEditCSI()){
						die();
					}
				}
				
				$check_field_query = $this->db->query("SHOW COLUMNS FROM `order` WHERE Field = '" . $this->db->escape($data['field']) . "'");
				if ($check_field_query->num_rows){
					
					if (in_array($data['field'], $csi_fields)){
						if ($this->user->canEditCSI()){
							$this->db->query("UPDATE `order` SET `" . $this->db->escape($data['field']) . "` = '" . $this->db->escape($data['value']) . "' WHERE order_id = '" . (int)$data['order_id'] . "'");
							} else {
							echo 'shit';
						}
						} else {
						$this->db->query("UPDATE `order` SET `" . $this->db->escape($data['field']) . "` = '" . $this->db->escape($data['value']) . "' WHERE order_id = '" . (int)$data['order_id'] . "'");
					}
					
					if ($data['field'] == 'csi_reject' && isset($data['customer_id']) && $this->user->canEditCSI()){																	
						$this->db->query("UPDATE `customer` SET `" . $this->db->escape($data['field']) . "` = '" . $this->db->escape($data['value']) . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");
					}
				}
				
				if (!empty($data['update_customer']) && $data['update_customer'] == 'true'){
					$check_field_query = $this->db->query("SHOW COLUMNS FROM `customer` WHERE Field = '" . $this->db->escape($data['field']) . "'");
					
					if ($check_field_query->num_rows){
						
					}
				}
				} else {
				echo 'shit';
			}			
		}
				
		public function updatePartiesFrom1CAjax($order_id = false){
			$this->load->model('kp/info1c');	
			$this->load->model('sale/order');
			$this->load->model('catalog/product');
			$this->load->model('localisation/order_status');
			
			$order_id = false;
			
			if (!$order_id && isset($this->request->get['order_id'])){
				$order_id = $this->request->get['order_id'];
			}	
			
			if (!$order_id){
				echo 'no_order_id';
				die();
				//	return false;
			}		
			
			$result = [];
			
			$order1c = $this->model_kp_info1c->getOrderTrackerXML($order_id);
			$order = $this->model_sale_order->getOrder($order_id);
			
			if (isset($this->request->get['d'])){
				
				print '<pre>';
				print_r($order1c);
				print '</pre>';
				
			}
			
			if (!isset($order1c["Документ"])){
				die(json_encode($result));
			}
			
			$order1c = $order1c["Документ"];
			if (isset($order1c['ОбщееСостояниеЗаказа']['Товар']['Наименование'])){
				$_tmp = $order1c['ОбщееСостояниеЗаказа']['Товар'];
				unset($order1c['ОбщееСостояниеЗаказа']['Товар']);
				$order1c['ОбщееСостояниеЗаказа']['Товар'] = array($_tmp);
			}
			
			
			
			foreach ($order1c['ОбщееСостояниеЗаказа']['Товар'] as $product){
				
				if (!$product['Наименование']){
					$real_product = $this->model_catalog_product->getProduct($product['Код']);
					if ($real_product){
						$product['Наименование'] = $real_product['name'];
					}
				}
				
				$total_product_quantity = 0;
				$maybe_order_product_id = $this->model_sale_order->tryToFindProductLine(array('order_id' => $order_id, 'product_id' => $product['Код']));
				
				$just_names = [];		
				if (isset($product['ВПутиДетально'])){
					
					if (!isset($product['ВПутиДетально']['Партия']['Номер'])){
						$product['ВПутиДетально'] = $product['ВПутиДетально']['Партия'];
					}
					
					foreach ($product['ВПутиДетально'] as $partie){
						if (!isset($partie["ЭтоСвободныеОстатки"]) || !$partie["ЭтоСвободныеОстатки"]) {
							$just_names[] = $partie['Номер'];
						}
					}														
					array_unique($just_names);
					
					unset($partie);
					foreach ($product['ВПутиДетально'] as $partie){
						
						$order_product_id = $this->model_sale_order->tryToFindProductLineExact(	array('order_id' => $order_id, 'product_id' => $product['Код'], 'quantity' => $partie['Количество']) );
						$total_product_quantity += $partie['Количество'];
						
						//нашли точное вхождение и количество совпадает
						if ($order_product_id){
							
							$part_num = $partie['Номер'];
							if (isset($partie["ЭтоСвободныеОстатки"]) && $partie["ЭтоСвободныеОстатки"]){
								$part_num = $just_names?max($just_names):'';
								
								if (!$part_num){
									$part_num = $this->model_sale_order->getMaxExistentPartie($order['shipping_country_id']);
								}
							}
							
							$result[] = array(
							'order_product_id' => $order_product_id,
							'part_num' 	       => str_replace('-', '', $part_num)
							);
						}
					}									
				}
				
				if (isset($product['ОжидаютОтгрузкиПокупателюДетально'])){
					
					if (!isset($product['ОжидаютОтгрузкиПокупателюДетально']['Партия']['Номер'])){
						$product['ОжидаютОтгрузкиПокупателюДетально'] = $product['ОжидаютОтгрузкиПокупателюДетально']['Партия'];
					}
					
					foreach ($product['ОжидаютОтгрузкиПокупателюДетально'] as $partie){
						if (!isset($partie["ЭтоСвободныеОстатки"]) || !$partie["ЭтоСвободныеОстатки"]) {
							$just_names[] = $partie['Номер'];
						}
					}						
					array_unique($just_names);
					
					unset($partie);
					foreach ($product['ОжидаютОтгрузкиПокупателюДетально'] as $partie){
						
						$order_product_id = $this->model_sale_order->tryToFindProductLineExact(	array('order_id' => $order_id, 'product_id' => $product['Код'], 'quantity' => $partie['Количество']) );						
						$total_product_quantity += $partie['Количество'];
						
						//нашли точное вхождение и количество совпадает
						if ($order_product_id){
							
							$part_num = $partie['Номер'];
							if (isset($partie["ЭтоСвободныеОстатки"]) && $partie["ЭтоСвободныеОстатки"]){
								$part_num = $just_names?max($just_names):'';
								
								if (!$part_num){
									$part_num = $this->model_sale_order->getMaxExistentPartie($order['shipping_country_id']);
								}
							}
							
							//На случай если продажа с С.О., но перемещение не создано или закупка в России
							if (isset($partie["ЭтоСвободныеОстатки"]) && !$partie["ЭтоСвободныеОстатки"] && $partie['Номер'] == '-'){
								$part_num = $this->model_sale_order->getMaxExistentPartie($order['shipping_country_id']);								
							}
							
							$result[] = array(
							'order_product_id' => $order_product_id,
							'part_num' 	       => str_replace('-', '', $part_num)
							);
						}
					}					
				}
				
				$maybe_product = $this->model_sale_order->getOrderProductLine($maybe_order_product_id);
				if ($maybe_product){
					if ($maybe_product['quantity'] == $total_product_quantity){
						$part_num = $just_names?max($just_names):'';
						
						if (!$part_num){
							$part_num = $this->model_sale_order->getMaxExistentPartie($order['shipping_country_id']);
						}
						
						$result[] = array(
						'order_product_id' => $maybe_order_product_id,
						'part_num' 	       => str_replace('-', '', $part_num)
						);
					}
				}
				
				/*
					if ($order['wait_full']){			
					$products = $this->model_sale_order->getOrderProducts($order_id);
					unset($product);					
					foreach ($products as $product){
					
					$part_num = $this->model_sale_order->getMaxExistentPartie($order['shipping_country_id']);
					
					$result[] = array(
					'order_product_id' => $product['order_product_id'],
					'part_num' 	       => str_replace('-', '', $part_num)
					);
					}
					}
				*/	
				
			}									
			
			$this->response->setOutput(json_encode($result));			
		}
		
		public function getConcardisOrder(){
			if (isset($this->request->get['cc_order_id'])){
				$cc_order_id = trim($this->request->get['cc_order_id']);
				
				$this->load->model('kp/order');
				$response = $this->model_kp_order->getConcardisOrder($cc_order_id, true);						
				
				$this->response->setOutput($response);
				
				} else {
				$this->response->setOutput(json_encode(array('ERROR' => 'ERROR')));
			}	
		}
		
		public function parseConcardisOrderAjax(){
			if (isset($this->request->get['cc_order_id'])){
				$cc_order_id = trim($this->request->get['cc_order_id']);
				
				$this->load->model('kp/order');
				$response = $this->model_kp_order->getConcardisOrder($cc_order_id, false);
				
				if ($response && !empty($response['capturedAmount'])){
					if (!empty($response['transactions']) && !empty($response['transactions'][0]) && $response['transactions'][0]['type'] == 'DEBIT' && $response['transactions'][0]['status'] == 'SUCCESS'){
						$this->response->setOutput(json_encode(array('STATUS' => 'SUCCESS', 'lastOperation' => $response['lastOperation'])));
						} elseif (!empty($response['lastOperation'])) {
						$this->response->setOutput(json_encode(array('STATUS' => 'NOTPAID', 'lastOperation' => $response['lastOperation'])));
						} else {
						$this->response->setOutput(json_encode(array('STATUS' => 'NOTPAID', 'lastOperation' => 'UNKNOWN')));
					}			
					} elseif ($response && !empty($response['lastOperation'])) {
					$this->response->setOutput(json_encode(array('STATUS' => 'NOTPAID', 'lastOperation' => $response['lastOperation'])));
					
					} else {
					$this->response->setOutput(json_encode(array('STATUS' => 'ERROR')));
				}
				
				} else {
				$this->response->setOutput(json_encode(array('STATUS' => 'ERROR')));
			}
		}
		
		public function getOrderCSIAjax(){
			$order_id = $this->request->get['order_id'];
			
			$this->load->model('kp/csi');
			
			echo (float)$this->model_kp_csi->countOrderCSI($order_id);
		}
		
		public function updateUserCSIWork(){
			
			$this->load->model('kp/work');
			
			if ($this->user->canEditCSI()){
				$this->model_kp_work->updateFieldPlusOne('edit_csi_count');
			}		
		}
		
		public function restoreOrderSave(){
			if ($this->user->getIsAV() || $this->user->getISMM()){
				$this->load->model('sale/customer');
				
				$order_save_id = (int)$this->request->post['order_save_id'];
				$order_id = (int)$this->request->post['order_id'];
				
				$history = $this->db->query("SELECT data, order_id, datetime FROM order_save_history WHERE order_save_id = '" . $order_save_id . "'");						
				
				$customer_query = $this->db->query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
				
				if ($history->row && isset($history->row['data']) && $order_id == $history->row['order_id'] && mb_strlen($history->row['data']) > 0 && @unserialize(base64_decode($history->row['data']))){
					$this->load->model('sale/order');
					$this->model_sale_order->editOrder($order_id, unserialize(base64_decode($history->row['data'])));
					
					$customer_comment_text = "Удачное восстановление заказа на состояние ". $history->row['datetime'];
					
					$_data = array(
					'customer_id' 		      => $customer_query->row['customer_id'], 
					'comment'     		      => $customer_comment_text,
					'order_id'    		      => $order_id,
					'call_id'     		      => 0,
					'manager_id'    	      => 0,
					'need_call'    			  => 0,
					'segment_id'    		  => 0,
					'order_status_id'    	  => 0,
					'prev_order_status_id'    => 0,
					'is_error'                => true
					);
					$this->model_sale_customer->addHistoryExtended($_data);
					
					echo 'restored';
					} else {
					
					$customer_comment_text = "Неудачная попытка восстановления заказа на состояние ". $history->row['datetime'];
					
					$_data = array(
					'customer_id' 		      => $customer_query->row['customer_id'], 
					'comment'     		      => $customer_comment_text,
					'order_id'    		      => $order_id,
					'call_id'     		      => 0,
					'manager_id'    	      => 0,
					'need_call'    			  => 0,
					'segment_id'    		  => 0,
					'order_status_id'    	  => 0,
					'prev_order_status_id'    => 0,
					'is_error'                => true
					);
					$this->model_sale_customer->addHistoryExtended($_data);
					
					echo 'error';
				}
				
				} else {
				echo 'error';
			}						
		}
	}																			
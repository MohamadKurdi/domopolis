<?php
class ControllerSaleCourier2 extends Controller {
	private $error = array();

	public function getDeliverySMSTextAjax(){
		$this->load->model('sale/order');
        $this->load->model('setting/setting');

		$order_id 		= $this->request->post['order_id'];
		$date 			= $this->request->post['senddate'];
		$ttn 			= $this->request->post['ttn'];
		$shipping_code 	= $this->request->post['shipping_code'];

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($this->model_setting_setting->getKeySettingValue('config', 'config_sms_ttn_sent_enabled', (int)$order_info['store_id'])){
			$smsTEXT = str_replace(['{ID}', '{TTN}'], [$order_id, $ttn], $this->model_setting_setting->getKeySettingValue('config', 'config_sms_ttn_sent', (int)$order_info['store_id']));
		}

		$this->response->setOutput($smsTEXT);			
	}

	public function moveToUnTaken(){
		$order_product_id = (int)$this->request->post['order_product_id'];
		$order_id = (int)$this->request->post['order_id'];

		if ($order_product_id && $order_id){
			$this->db->query("INSERT INTO order_product_untaken SELECT * FROM order_product WHERE order_product_id = '" . $order_product_id . "' AND order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM order_product WHERE order_product_id = '" . $order_product_id . "' AND order_id = '" . (int)$order_id . "'");
		}
	}

	public function moveToTaken(){
		$order_product_id = (int)$this->request->post['order_product_id'];
		$order_id = (int)$this->request->post['order_id'];

		if ($order_product_id && $order_id){
			$this->db->query("INSERT INTO order_product SELECT * FROM order_product_untaken WHERE order_product_id = '" . $order_product_id . "' AND order_id = '" . (int)$order_id . "'");

			$this->db->query("DELETE FROM order_product_untaken WHERE order_product_id = '" . $order_product_id . "' AND order_id = '" . (int)$order_id . "'");
		}
	}

	public function index() {
		$this->load->model('sale/order');
		$this->load->model('user/user');
		
		$this->language->load('sale/order');

		$this->document->setTitle('Курьер');
		$this->data['_title'] = 'Курьер';;

		$this->data['my_orders'] = array();
		$filter_data = array(
			'filter_courier_id' => $this->user->getID(),
			'filter_order_status_id' => 26
			
		);
		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result){

			$this->data['my_orders'][] = array(
				'order_id' => $result['order_id'],
				'address'  => $result['shipping_address_1']
			);

		}

		$this->data['just_courier'] = ($this->user->getUserGroup() == 26);

		$this->data['username'] = $this->model_user_user->getRealUserNameById($this->user->getId());

		$this->data['getOrderAjaxUrl'] = $this->url->link('sale/courier2/getOrderAjax', 'token=' . $this->session->data['token']);
		$this->data['getOrderAjaxUrl'] = str_replace('&amp;', '&', $this->data['getOrderAjaxUrl']);
		$this->data['fullUrl'] = $this->url->link('sale/order', 'token=' . $this->session->data['token']);

		$this->template="kp/courier2.tpl";
		$this->response->setOutput($this->render());
	}

	public function sendPaymentLinkToCustomer(){
		$this->load->model('sale/order');
		$this->load->model('setting/setting');

		$order_id = (int)$this->request->post['order_id'];			
		$payment_code = $this->request->post['code'];	
		$telephone = $this->request->post['telephone'];

		if ($payment_code && $order_info = $this->model_sale_order->getOrder($order_id)){
			$params = [		
				'sender'		=> $this->model_setting_setting->getKeySettingValue('config', 'config_sms_sign', (int)$order_info['store_id']),
				'text'			=> $this->model_sale_order->generatePaymentLink($order_id, $payment_code),
				'phone'			=> preparePhone($telephone),
				'type' 			=> '2',
				'datetime' 		=> '',
				'sms_lifetime'  => '0',
			];

			$ePochta = new \Enniel\Epochta\SMS(['public_key' => $this->config->get('config_smsgate_secret_key'), 'private_key' => $this->config->get('config_smsgate_api_key')]);
			$response = $ePochta->sendSMS($params)->getBody()->getContents();
			$result = json_decode($response, true);

			if (!empty($result["result"]["id"]) && $result["result"]["id"]){
				$this->response->setOutput($result["result"]["id"]); 
			} else {
				$this->response->setOutput('FAIL');
			}								

		} else {
			$this->response->setOutput('FAIL');
		}
	}

	public function updateOnlyDataAjax($data = array()){
		$data = $this->request->post;
		$order_id = (int)trim($data['order_id']);
		$status_id = (int)trim($data['order_status_id']);
		$this->load->model('sale/order');
		$this->load->model('sale/customer');
		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($data['taken_products']){
			$gp = explode(',', $data['taken_products']);
			$this->db->query("UPDATE order_product SET `taken` = '0' WHERE order_id = ".(int)$data['order_id']);
			foreach ($gp as $gp_id){
				if ($gp_id > 0) {
					$this->db->query("UPDATE order_product SET `taken` = '1' WHERE order_product_id = ".(int)$gp_id);				
				}
			}
		}

		if (isset($data['ttn']) && mb_strlen($data['ttn']) > 1){
			$this->db->query("INSERT INTO `order_ttns` SET	
				order_id = '" . (int)$data['order_id'] . "', 
				ttn = '" .$this->db->escape($data['ttn']) . "',
				delivery_code = '" .$this->db->escape($data['shipping_code']) . "', 						
				date_ttn = '" .$this->db->escape($data['date_sent']). "'
				");	
			$this->db->query("UPDATE `order` SET `ttn` = '".$this->db->escape($data['ttn'])."' WHERE order_id = ".(int)$data['order_id']);			
		}

		echo '<span style="color:green;">Обновлено: ТТН, товары, дисконтная карта</span><br />';
		return;
	}

	public function updateOrderAjax(){
		$data = $this->request->post;
		$order_id = (int)trim($data['order_id']);
		$status_id = (int)trim($data['order_status_id']);

		$this->load->model('sale/order');
		$this->load->model('sale/customer');
		$this->load->model('localisation/order_status');
		$this->load->model('user/user');

		$order_info = $this->model_sale_order->getOrder($data['order_id']);


		if ($this->model_sale_order->getIfOrderClosed($data['order_id'])){			
			echo '<span style="color:green;">Заказ уже закрыт и не подлежит редактированию!</span><br />';
			die();
		}

		$current_status_id = $this->model_sale_order->getOrderLastHistory((int)$data['order_id']);

		if ($data['order_status_id'] == $current_status_id){

			$this->updateOnlyDataAjax($data);
			$out = '<span style="color:red;">Данные обновлены</span><br />';
			$out = '<span style="color:red;">Нельзя поставить два одинаковых статуса подряд!</span>';

			echo $out;
			return;
		}

		if ($data['taken_products']){
			$gp = explode(',', $data['taken_products']);
			$this->db->query("UPDATE order_product SET `taken` = '0' WHERE order_id = ".(int)$data['order_id']);
			foreach ($gp as $gp_id){
				if ($gp_id > 0) {
					$this->db->query("UPDATE order_product SET `taken` = '1' WHERE order_product_id = ".(int)$gp_id);				
				}
			}
		}

		if (!isset($data['notify'])){
			$data['notify'] = 0;
		}				

		if ($data['notify'] &&  isset($data['sms']) && $data['sms'] && (mb_strlen($data['sms'], 'UTF-8') > 2)){
			$data['history_sms_text'] = $data['sms'];
		} else {
			$data['history_sms_text'] = false;
		}								

		$customer_comment_text = 'изменен статус на: <b>'.$this->model_localisation_order_status->getOrderStatusName($status_id).'</b>';
		$_data = array(
			'customer_id' 		      => $order_info['customer_id'], 
			'comment'     		      => $customer_comment_text,
			'order_id'    		      => $data['order_id'],
			'call_id'     		      => 0,
			'manager_id'    	      => 0,
			'need_call'    			  => 0,
			'segment_id'    		  => 0,
			'order_status_id'    	  => $data['order_status_id'],
			'prev_order_status_id'    => $current_status_id					
		);
		$this->model_sale_customer->addHistoryExtended($_data);

		if (isset($data['ttn']) && mb_strlen($data['ttn']) > 1){
			$this->db->query("INSERT INTO `order_ttns` SET	
				order_id 		= '" . (int)$data['order_id'] . "', 
				ttn 			= '" .$this->db->escape($data['ttn']) . "',
				delivery_code 	= '" .$this->db->escape($data['shipping_code']) . "', 						
				date_ttn 		= '" .$this->db->escape($data['date_sent']). "'");	
			$this->db->query("UPDATE `order` SET `ttn` = '".$this->db->escape($data['ttn'])."' WHERE order_id = ".(int)$data['order_id']);			
		}

		if (isset($data['date_sent']) && mb_strlen($data['date_sent']) > 1){
			$this->db->query("UPDATE `order` SET `date_sent` = '".$this->db->escape($data['date_sent'])."' WHERE order_id = ".(int)$data['order_id']);
		}

		if (isset($data['payment']) && $data['payment'] && ((int)$data['payment_amount'] > 0)){

			if ($status_id == $this->config->get('config_complete_status_id') || $status_id == $this->config->get('config_partly_delivered_status_id')){
				$amount_national = (int)$data['payment_amount'];

				$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
				$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #' . $order_id . ': наличные курьеру', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'courier_cash');
			}
		}	

		$transaction = false;
		if ($status_id == $this->config->get('config_complete_status_id')){																	
			$order_total = $order_info['total_national'];					
			$customer_balance_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id']);			
			$balance_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
			$total_transaction_count = $this->model_sale_customer->getTotalTransactions($order_info['customer_id'], $order_id);

			$amount_national = ($order_total);
			$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));

			if ($balance_national == 0 && $total_transaction_count == 0){
				$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #' . $order_id.': внесение суммы на счет', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');																		
			}

			$amount_national = -1*($order_total);
			$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));

			$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #' . $order_id . ': списание со счета по статусу Выполнен', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');	
								
			$alert = array(
				'type' 			=> 'warning',
				'text' 			=> $this->model_user_user->getRealUserNameById($this->user->getID()) . ' изменил статус на <b>ВЫПОЛНЕН</b>. <br />Заказ',
				'entity_type' 	=> 'order', 
				'entity_id' 	=> $order_id
			);

			$this->mAlert->insertAlertForGroup('sales', $alert);
			$this->mAlert->insertAlertForGroup('admins', $alert);
		}	

		if (isset($data['notify']) && $data['notify'] &&  isset($data['sms']) && $data['sms'] && (mb_strlen($data['sms'], 'UTF-8') > 2)){
			$this->load->model('setting/setting');

			$options = array(
				'to'       => $data['telephone'],
				'from'     => $this->model_setting_setting->getKeySettingValue('config', 'config_sms_sign', $data['store_id']),				
				'message'  => $data['sms']
			);

			$this->db->query("UPDATE `order_ttns` SET sms_sent = NOW() WHERE ttn = '" . $this->db->escape($data['ttn']) . "'");				
		}

		$this->model_sale_order->addOrderHistory((int)$data['order_id'], $data);

		$out = 'Обновлен заказ <b>'.$data['order_id'].'</b>';		
		$out .= '<br />Новый статус: <b>'.$data['order_status_id'].'</b>';
		if ($transaction) {
			$out .= '<br />Лицевой счет: <b>'.$transaction.'</b>';
		}
		if (isset($data['ttn']) && mb_strlen($data['ttn']) > 1){
			$out .= '<br />ТТН: <b>'.$data['ttn'].'</b>';
		}


		$out .= '<br />------------------------------------------';			

		$this->response->setOutput($out);
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


	public function getOrderAjax(){
		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		$this->load->model('sale/customer');
		$this->load->model('setting/setting');
		$this->load->model('localisation/order_status');

		$this->data['_dnamearray'] = [
			'первая','вторая','третья','четвертая','пятая','шестая','седьмая','восьмая','девятая'
		];	

		$this->data['getOrderAjaxUrl'] = $this->url->link('sale/courier2/getOrderAjax', 'token=' . $this->session->data['token']);
		$this->data['getOrderAjaxUrl'] = str_replace('&amp;', '&', $this->data['getOrderAjaxUrl']);

		$this->data['sendPaymentLinkToCustomer'] = $this->url->link('sale/courier2/sendPaymentLinkToCustomer', 'token=' . $this->session->data['token']);
		$this->data['sendPaymentLinkToCustomer'] = str_replace('&amp;', '&', $this->data['sendPaymentLinkToCustomer']);



		$order_id = (int)trim($this->request->get['order_id']);

		if ($order_id > 0 && $this->data['order_info'] = $order_info = $this->model_sale_order->getOrder($order_id)){

			if ($order_info['closed']){

				$this->template="kp/courier_data.tpl";
				$this->response->setOutput('<div style="color:red; font-weight:700; text-align:center;"><i class="fa fa-exclamation-triangle"></i> Заказ ' . $order_id . ' закрыт для редактирования!</div>');

			} else {

				$this->data['totals'] = $totals = $this->model_sale_order->getOrderTotals($order_id);

				$this->data['order_products'] = $order_products = $this->model_sale_order->getOrderProducts($order_id);
				$this->data['order_products_untaken'] = $order_products_untaken = $this->model_sale_order->getOrderUntakenProducts($order_id);

				$this->data['order_statuses'] = $order_statuses = $this->model_localisation_order_status->getAllowedOrderStatuses($order_info['order_status_id']);							

				$this->data['customer'] = $customer = $this->model_sale_customer->getCustomer($order_info['customer_id']);
				$this->data['order_transactions_total'] = $order_transactions_total = (int)$this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
				$this->data['order_total_national'] = $order_total_national = (int)$this->model_sale_order->getOrderTotals($order_id, 'total')['value_national'];
				$this->data['current_delivery_num'] = $current_delivery_num = (int)$this->model_sale_order->getCurrentDelivery($order_id) + 1;

				$this->data['use_custom_dadata'] = $this->model_setting_setting->getKeySettingValue('config', 'config_dadata', (int)$order_info['store_id']);

				$this->data['order_is_paid'] = ($order_transactions_total >= $order_total_national);
				$this->data['is_moscow_courier'] = ($order_info['shipping_code'] == 'dostavkaplus.sh1');

				$this->data['geolocation'] = false;
				if ($this->data['use_custom_dadata'] == 'address' && $order_info['shipping_code'] == 'dostavkaplus.sh1'){
					$filter_custom_data = array(
						'set' 			=> 'shipping_address',
						'set_short' 	=> 'shipping',
						'field' 		=> 'geolocation',
						'type' 			=> 'order', 
						'id' 			=> (int)$order_info['order_id'], 
						'customer_id' 	=> (int)$order_info['customer_id'], 
						'dadata_config' => $this->data['use_custom_dadata']					
					);

					$this->data['geolocation'] = str_replace(' ', '', $this->customAddressFieldsOne($filter_custom_data));

					if (!$this->data['geolocation']){
						$filter_custom_data = array(
							'set' 			=> 'payment_address',
							'set_short' 	=> 'payment',
							'field' 		=> 'geolocation',
							'type' 			=> 'order', 
							'id' 			=> (int)$order_info['order_id'], 
							'customer_id' 	=> (int)$order_info['customer_id'], 
							'dadata_config' => $this->data['use_custom_dadata']					
						);

						$this->data['geolocation'] = str_replace(' ', '', $this->customAddressFieldsOne($filter_custom_data));
					}
				}

					//Оплата картой
				$this->data['payments'] = array();

				if ($order_info['currency_code'] != 'UAH' && $this->config->get('concardis_status_fake')){
					$this->data['payments'][] = array(
						'title'    => '<i class="fa fa-cc-visa"></i> Concardis (Visa / MasterCard)',
						'code'     => 'concardis',
						'collapse' => false,
						'qr_image' => $this->model_sale_order->generatePaymentQR($order_id, 'concardis'),
						'qr_link'  => $this->model_sale_order->generatePaymentLink($order_id, 'concardis')
					);
				}

				if ($order_info['currency_code'] == 'UAH' && $this->config->get('liqpay_status_fake')){
					$this->data['payments'][] = array(
						'title'    => '<i class="fa fa-cc-visa"></i> LiqPay UAH (Visa / MasterCard)',
						'code'     => 'liqpay',
						'collapse' => false,
						'qr_image' => $this->model_sale_order->generatePaymentQR($order_id, 'liqpay'),
						'qr_link'  => $this->model_sale_order->generatePaymentLink($order_id, 'liqpay')
					);
				}					

				if ($order_info['currency_code'] == 'UAH' && $this->config->get('wayforpay_status_fake')){
					$this->data['payments'][] = array(
						'title'    => '<i class="fa fa-cc-visa"></i> WayForPay UAH (Visa / MasterCard)',
						'code'     => 'wayforpay',
						'collapse' => false,
						'qr_image' => $this->model_sale_order->generatePaymentQR($order_id, 'wayforpay'),
						'qr_link'  => $this->model_sale_order->generatePaymentLink($order_id, 'wayforpay')
					);
				}

				if ($order_info['currency_code'] == 'UAH' && $this->config->get('mono_status_fake')){
					$this->data['payments'][] = array(
						'title'    => '<i class="fa fa-cc-visa"></i> Monobank UAH (Visa / MasterCard)',
						'code'     => 'mono',
						'collapse' => false,
						'qr_image' => $this->model_sale_order->generatePaymentQR($order_id, 'mono'),
						'qr_link'  => $this->model_sale_order->generatePaymentLink($order_id, 'mono')
					);
				}

				if ($order_info['currency_code'] == 'RUB' && $this->config->get('paykeeper_status_fake')){
					$this->data['payments'][] = array(
						'title'    => '<i class="fa fa-exclamation-triangle"></i> PayKeeper (ТОЛЬКО КАРТЫ МИР)',
						'code'     => 'paykeeper',
						'collapse' => true,
						'qr_image' => $this->model_sale_order->generatePaymentQR($order_id, 'paykeeper'),
						'qr_link'  => $this->model_sale_order->generatePaymentLink($order_id, 'paykeeper')
					);
				}

				$just_product_ids = array();
				foreach ($order_products as $order_product){
					$just_product_ids[] = $order_product['product_id'];
				}
				unset($order_product);

				$this->data['order_products'] = array();							
				foreach ($order_products as &$order_product){

					$order_product['points']  =  $order_product['reward'];

					$totals_json = json_decode($order_product['totals_json'], true);
					$order_product['points_used_one'] = false;
					$order_product['points_used_total'] = false;
					$order_product['points_used_one_txt'] = false;
					$order_product['points_used_total_txt'] = false;

					if ($totals_json){
						foreach ($totals_json as $totals_json_line){
							if ($totals_json_line['code'] == 'reward'){
								$order_product['points_used_one'] = $totals_json_line['discount'];
								$order_product['points_used_total'] = $totals_json_line['discount_total'];

								$order_product['points_used_one_txt'] = $this->currency->format($totals_json_line['discount'], $order_info['currency_code'], '1');
								$order_product['points_used_total_txt'] = $this->currency->format($totals_json_line['discount_total'], $order_info['currency_code'], '1');

								break;
							}					
						}
					}

					$ao_text = false;
					$order_has_main_ao = false;
					if ((int)$order_product['ao_id']){
						$ao_main_product = $this->model_catalog_product->getProduct($order_product['ao_product_id']);

						$ao_text = 'Спецпредложение к товару <b>'. $ao_main_product['model'] . '</b>';

						if (in_array($order_product['ao_product_id'], $just_product_ids)){
							$order_has_main_ao = true;
						}
					}


					$order_product['ao_text'] = $ao_text;
					$order_product['order_has_main_ao'] = $order_has_main_ao;

					if ($order_product['total_national'] > 0){
						$order_product['totalp'] = $this->currency->format($order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], '1');	

						$order_product['total_pay_with_cash'] = $this->currency->format( ($order_product['total_national'] - $order_product['points_used_total']), $order_info['currency_code'], '1');

					} else {

						$order_product['total_national'] = $this->currency->convert($order_product['total_national'],'EUR',$order_info['currency_code']);
						$order_product['total_pay_with_cash'] = $this->currency->format( ($order_product['total_national'] - $order_product['points_used_total']), $order_info['currency_code'], '1');


						$order_product['totalp'] = number_format($this->currency->convert($order_product['total_national'],'EUR',$order_info['currency_code']),0,',',' ');
					}

					$this->data['order_products'][] = $order_product;
				}
					
				$this->data['order_products_untaken'] = array();
				foreach ($order_products_untaken as &$order_product_untaken){

					$order_product_untaken['points']  =  $order_product_untaken['reward'];	

					if ($order_product_untaken['total_national'] > 0){
						$order_product_untaken['totalp'] = $this->currency->format($order_product_untaken['total_national'] + ($this->config->get('config_tax') ? ($order_product_untaken['tax'] * $order_product_untaken['quantity']) : 0), $order_info['currency_code'], '1');	

					} else {

						$order_product_untaken['total_national'] = $this->currency->convert($order_product_untaken['total_national'],'EUR',$order_info['currency_code']);
						$order_product_untaken['totalp'] = number_format($this->currency->convert($order_product_untaken['total_national'],'EUR',$order_info['currency_code']),0,',',' ');
					}

					$this->data['order_products_untaken'][] = $order_product_untaken;
				}

				foreach ($order_statuses as $order_status) {
					if ($order_status["order_status_id"] == $order_info["order_status_id"]) {
						$this->data['current_status'] = $order_status["name"];
					}
				}

				if ($order_info['date_sent'] && $order_info['date_sent'] != '0000-00-00'){
					$this->data['sent'] = $order_info['date_sent'];
				} else {
					$this->data['sent'] = date("Y-m-d");
				}

				$this->data['updateOrderAjaxUrl'] = $this->url->link('sale/courier2/updateOrderAjax', 'token=' . $this->session->data['token']);
				$this->data['updateOrderAjaxUrl'] = str_replace('&amp;', '&', $this->data['updateOrderAjaxUrl']);

				$this->data['moveToUnTaken'] = $this->url->link('sale/courier2/moveToUnTaken', 'token=' . $this->session->data['token']);
				$this->data['moveToUnTaken'] = str_replace('&amp;', '&', $this->data['moveToUnTaken']);

				$this->data['moveToTaken'] = $this->url->link('sale/courier2/moveToTaken', 'token=' . $this->session->data['token']);
				$this->data['moveToTaken'] = str_replace('&amp;', '&', $this->data['moveToTaken']);

				$this->data['resaveOrderAjax'] = $this->url->link('sale/order/resaveOrder', 'order_id=' . $order_info['order_id'] . '&token=' . $this->session->data['token']);
				$this->data['resaveOrderAjax'] = str_replace('&amp;', '&', $this->data['resaveOrderAjax']);

				$this->data['getDeliverySMSTextAjaxUrl'] = $this->url->link('sale/order/getDeliverySMSTextAjax', 'token=' . $this->session->data['token']);
				$this->data['getDeliverySMSTextAjaxUrl'] = str_replace('&amp;', '&', $this->data['getDeliverySMSTextAjaxUrl']);

				$this->data['getStatusSMSTextAjaxUrl'] = $this->url->link('sale/order/getStatusSMSTextAjax', 'token=' . $this->session->data['token']);
				$this->data['getStatusSMSTextAjaxUrl'] = str_replace('&amp;', '&', $this->data['getStatusSMSTextAjaxUrl']);

				$this->data['payment_must'] = $payment_must = mb_stripos($order_info['payment_method'], 'при получении') || mb_stripos($order_info['payment_method'], 'при доставке');

				$this->template="kp/courier_data.tpl";
				$this->response->setOutput($this->render());

			}

		} else {

			$this->template="kp/courier_data.tpl";
			$this->response->setOutput('<div style="color:red; font-weight:700; text-align:center;"><i class="fa fa-exclamation-triangle"></i> Нет заказа, либо произошла ошибка!</div>');

		}
	}




}																							
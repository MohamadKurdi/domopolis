<?
	
	class ControllerPaymentConcardisLaterpay extends Controller
	{
		
		protected function index(){
		}
		
		public function laterpay_index(){
			
		}
		
		
		public function laterpay(){
			
			if (!$this->validateLaterpay()){
				$this->redirect('account/order');
			}
			
			$this->load->model('checkout/order');
			
			$order_id 	= (int)$this->request->get['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$currency_code = $this->config->get('config_regional_currency');
			$currency_settled_explicit = '';
			if (isset($this->request->get['cc_code']) && in_array($this->request->get['cc_code'], array('EUR', 'RUB', 'UAH'))){
				$currency_code = $this->request->get['cc_code'];
				
				if ($currency_code != $this->config->get('config_regional_currency')){
					$currency_settled_explicit = '-' . $currency_code;
				}
			}						
			
			if ($currency_code == 'EUR') {				
				if ($this->config->get('config_regional_currency') == 'EUR'){
					$order_to_pay_converted = round($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 2) * 100;
				} else {
					$order_to_pay_converted = round($this->currency->makeDiscountOnNumber($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 3), 2) * 100;
				}
				$currency_code = 'EUR';
			} elseif ($currency_code == 'RUB'){				
				$order_to_pay_converted = round($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'RUB', true), 2) * 100;
				$currency_code = 'RUB';				
			} elseif ($currency_code == 'UAH'){				
				$order_to_pay_converted = round($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'UAH', true), 2) * 100;
				$currency_code = 'UAH';
			} else {
				$order_to_pay_converted = round($this->currency->makeDiscountOnNumber($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 3), 2) * 100;
				$currency_code = 'EUR';
			}
			
			
			if ($cc_order_id = $this->getOrderConcardisID($order_id)){
				
				$cc_order = $this->getConcardisOrder($cc_order_id);	
				
				$this->data['TRANSACTION_DEBIT_PENDING'] = false;
				if (isset($cc_order['lastOperation']) && $cc_order['lastOperation'] == 'TRANSACTION_DEBIT_PENDING'){
					$this->data['TRANSACTION_DEBIT_PENDING'] 	= true;
					$this->data['lastOperation'] 				= $cc_order['lastOperation'];
				}
				
				if (($cc_order['status'] == 'CLOSED')  && !$cc_order['capturedAmount']){
					$data = [
						"initialAmount" 	=> (int)$order_to_pay_converted,
						"currency" 			=> $currency_code,
						"transactionType" 	=> "DEBIT",
						"merchantOrderId" 	=> "#$order_id-" . mb_substr(md5(time()), 0, 4),
						"description" 		=> "Оплата по заказу #$order_id",
						"async" 			=> [
							"successUrl" 	=> 	$this->config->get('config_ssl') . 'index.php?route=payment/concardis/success&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
							"failureUrl" 	=>	$this->config->get('config_ssl') . 'index.php?route=payment/concardis/fail&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
							"cancelUrl" 	=> 	$this->config->get('config_ssl') . 'index.php?route=payment/concardis/cancel&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
							"notifications" => [
								[
									"notificationUrn" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/callback',
									"notificationState" => ["CREATED", "UPDATED"]
								]
							]
						]
					];
					
					$cc_order_id = $this->createConcardisOrder($data);				
					
					} elseif ((($cc_order['status'] == 'CREATED')  && !$cc_order['capturedAmount']) || ((int)$order_to_pay_converted != $cc_order['initialAmount'])) {

						$data = [
							"initialAmount" 	=> (int)$order_to_pay_converted,
							"currency" 			=> $currency_code,
							"transactionType" 	=> "DEBIT",
							"merchantOrderId" 	=> "#$order_id",
							"description" 		=> "Оплата по заказу #$order_id",
							"async" => [
								"successUrl" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/success&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
								"failureUrl" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/fail&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
								"cancelUrl" 	=> 	$this->config->get('config_ssl') . 'index.php?route=payment/concardis/cancel&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
								"notifications" => [
									[
										"notificationUrn" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/callback',
										"notificationState" => ["CREATED", "UPDATED"]
									]
								]
							]
						];
					
					$cc_order_id = $this->patchConcardisOrder($cc_order_id, $data);				
				}
				
				
				} else {

					$data = [
						"initialAmount" 	=> (int)$order_to_pay_converted,
						"currency" 			=> $currency_code,
						"transactionType" 	=> "DEBIT",
						"merchantOrderId" 	=> "#$order_id",
						"description" 		=> "Оплата по заказу #$order_id",
						"async" => [
							"successUrl" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/success&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
							"failureUrl" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/fail&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
							"cancelUrl" 	=> 	$this->config->get('config_ssl') . 'index.php?route=payment/concardis/cancel&order_id=' . $order_id . '&token=' . $this->createOrderToken($order_id),
							"notifications" => [
								[
									"notificationUrn" 	=> $this->config->get('config_ssl') . 'index.php?route=payment/concardis/callback',
									"notificationState" => array("CREATED", "UPDATED")
								]
							]
						]
					];
				
					$cc_order_id = $this->createConcardisOrder($data);				
			}
			
			$this->setOrderConcardisID($order_id, $cc_order_id);			
			
			$this->data['merchant'] 	= $this->config->get('concardis_email');
			$this->data['order_id'] 	= $order_id;
			$this->data['cc_order_id']  = $cc_order_id;
			
			$this->children = array(
			'common/header/simpleheader',
			'common/footer/simplefooter',
			);		
			
			$this->document->setTitle('Оплата Concardis PayEngine');			
			$this->template = 'payment/concardis_laterpay.tpl';			
			$this->response->setOutput($this->render());
			
		}	
		
		private function createOrderToken($order_id){			
			return md5($order_id . $this->config->get('config_encryption'));			
		}
		
		private function patchConcardisOrder($cc_order_id, $data){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.payengine.de/v1/orders/" . $cc_order_id);
			curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('concardis_email') . ":" . $this->config->get('concardis_secret'));  
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
			curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: application/json')); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2);			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br");			
			$response = curl_exec($ch);
			curl_close($ch);
			
			$decoded_responce = json_decode($response, true);		
					
			if (isset($decoded_responce['orderId']) && $decoded_responce['orderId']){				
				$cc_order_id = $decoded_responce['orderId'];
			} else {				
				$cc_order_id = false;
			}
			
			return $cc_order_id;
		}
		
		private function createConcardisOrder($data, $once = false){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.payengine.de/v1/orders/");
			curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('concardis_email') . ":" . $this->config->get('concardis_secret'));  
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
			curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: application/json')); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2);			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br");			
			$response = curl_exec($ch);
			curl_close($ch);
			
			$decoded_responce = json_decode($response, true);					
			
			if (isset($decoded_responce['orderId']) && $decoded_responce['orderId']){				
				$cc_order_id = $decoded_responce['orderId'];
			} elseif (isset($decoded_responce['errors']) && isset($decoded_responce['errors'][0]) && isset($decoded_responce['errors'][0]['code']) && $decoded_responce['errors'][0]['code'] == 12255 && $decoded_responce['errors'][0]['field'] == 'merchantOrderId'){								
				if (!$once){
					$_data = $data;
					$_data['merchantOrderId'] .= "-" . mb_substr(md5(time()), 0, 4);
					$cc_order_id = $this->createConcardisOrder($_data, true);
				}
				
			} else {				
				$cc_order_id = false;				
			}
			
			return $cc_order_id;
			
		}
		
		private function getConcardisOrder($cc_order_id){						
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.payengine.de/v1/orders/" . $cc_order_id . "");
			curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('concardis_email') . ":" . $this->config->get('concardis_secret'));  
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: application/json')); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2);			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br");			
			$response = curl_exec($ch);
			curl_close($ch);
			
			$this->log->debug('GETTING ORDER ' . $response);
			$this->log->debug(json_decode($response, true));
			
			return json_decode($response, true);			
		}
		
		private function getOrderConcardisID($order_id){			
			$query = $this->db->non_cached_query("SELECT concardis_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			if ($query->num_rows && $query->row['concardis_id']){							
				return $query->row['concardis_id'];
				} else {							
				return false;
			}			
		}
		
		private function setOrderConcardisID($order_id, $cc_order_id){
			
			$this->db->non_cached_query("UPDATE `order` SET concardis_id = '" . $this->db->escape($cc_order_id) . "' WHERE order_id = '" . (int)$order_id . "'");
			
		}
		
		protected function validateLaterpay() {
			if ((!isset($this->request->get['order_id'])) || (!isset($this->request->get['order_tt'])) || (!isset($this->request->get['order_fl']))) {
				return false;
				} else {
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
				if ((!$order_info) || ($this->request->get['order_id'] != $order_info['order_id']) || ($this->request->get['order_tt'] != $order_info['total_national']) || ($this->request->get['order_fl']) != md5($order_info['firstname'] . $order_info['lastname'])) {
					return false;
				}
			}
			return true;
		}															
	}																				
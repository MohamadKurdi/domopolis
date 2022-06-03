<?
	
	class ControllerPaymentConcardis extends Controller
	{
		
		private $order;
		
		
		protected function index()
		{
			$this->load->model('checkout/order');
			$this->language->load('payment/bank_transfer');
			
			$order_id = (int)$this->session->data['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$this->data['continue'] = $this->url->link('checkout/success');
			$this->data['button_confirm'] = $this->language->get('button_confirm');
			
			$this->data['continue'] = str_replace('&amp;', '&', $this->url->link('payment/concardis_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));
			
			if ($this->config->get('config_regional_currency') == 'EUR'){
				
				$this->template = $this->config->get('config_template') . '/template/payment/concardis.tpl';
				
				} else {
				
				$this->template = $this->config->get('config_template') . '/template/payment/concardis_order.tpl';
				
			}			
			
			$this->response->setOutput($this->render());
		}	
		
		public function cron(){
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			$this->load->model('kp/order');
			$this->load->model('account/transaction');
			
			echo '[CCRON] ---------------------- STAGE 1 ----------------------' . PHP_EOL;
			echo '[CCRON] Пробуем отобрать заказы' . PHP_EOL;
			
			$excludedStatuses = array(
			$this->config->get('config_complete_status_id'),
			$this->config->get('config_cancelled_status_id'),
			$this->config->get('concardis_order_status_id'),
			23,
			);
			
			$query = $this->db->non_cached_query("SELECT order_id, concardis_id FROM `order` WHERE order_status_id > 0 
			AND order_status_id NOT IN (" . implode(',', $excludedStatuses) . ")
			AND LENGTH(concardis_id) > 2 			
			AND	order_id NOT IN (SELECT DISTINCT order_id FROM customer_transaction WHERE added_from = 'concardis') 
			AND order_id NOT IN (SELECT DISTINCT order_id FROM customer_transaction WHERE LOWER(description) LIKE ('%concardis%'))");					
			
			$successOrders = array();
			foreach ($query->rows as $row){
				$ORDER_IS_PAID = FALSE;
				
				echo '[CCRON] Заказ #' . $row['order_id'] . ', заявка ' . $row['concardis_id']  . PHP_EOL;
				
				$concardisInfo = $this->model_kp_order->getConcardisOrder($row['concardis_id']);
				
				if ($concardisInfo && !empty($concardisInfo['capturedAmount'])){
					if (!empty($concardisInfo['transactions']) && !empty($concardisInfo['transactions'][0]) && $concardisInfo['transactions'][0]['type'] == 'DEBIT' && $concardisInfo['transactions'][0]['status'] == 'SUCCESS' && $concardisInfo['transactions'][0]['initialAmount'] > 0 && $concardisInfo['lastOperation'] == 'TRANSACTION_DEBIT_SUCCESS'){
						
						echo '[CCRON API] Заявка ' . $row['concardis_id'] . ' оплачена, сумма ' . $concardisInfo['transactions'][0]['initialAmount'] . ' (' . $concardisInfo['capturedAmount'] . '), валюта '. $concardisInfo['transactions'][0]['currency'] .' последняя операция ' . $concardisInfo['lastOperation'] . PHP_EOL;
						$ORDER_IS_PAID = TRUE;
						$successOrders[$row['order_id']] = array(
							'concardis_id' => $row['concardis_id'], 
							'currency' => $concardisInfo['currency'], 
							'capturedAmount' => $concardisInfo['capturedAmount']
							);
						
						} else {
						
						echo '[CCRON API] Заявка ' . $row['concardis_id'] . ' непонятная ситуация, надо проверить!' . PHP_EOL;
						
					}
					
					} elseif ($concardisInfo && !empty($concardisInfo['lastOperation'])) {
					echo '[CCRON API] Заявка ' . $row['concardis_id'] . ' не оплачена, последняя операция ' . $concardisInfo['lastOperation'] . PHP_EOL;
					} else {
					echo '[CCRON API] Заявка ' . $row['concardis_id'] . ' не оплачена, неизвестный ответ сервера CC' . PHP_EOL;
				}			
			}
			
			echo '[CCRON] ---------------------- STAGE 2 ----------------------' . PHP_EOL;
			
			foreach ($successOrders as $order_id => $concardisInfo){
				echo '[CCRON API] Шас мы добавим оплату заказу ' . $order_id . PHP_EOL;
				
				$sms_template = "Заказ #{ID}. Оплату получили, спасибо. Заказ выполняем";
				$this->model_checkout_order->addOrderToQueue($order_id);
				
				//Для совместимост
				$this->order = $this->model_checkout_order->getOrder($order_id);
				
				if ($this->order['order_status_id'] != $this->config->get('concardis_order_status_id')) {
					
					$this->model_checkout_order->update($this->order['order_id'],
					$this->config->get('concardis_order_status_id'),
					'Оплата через Concardis Payengine',
					true,
					158);
					
					$paidByConcardis = $concardisInfo['capturedAmount']/100;
					
					echo '[CCRON API] Валюта ' . $concardisInfo['currency'] . ', Сумма по API: ' . $paidByConcardis . ', итог заказа ' . $this->model_account_order->getOrderTotalNational($this->order['order_id']) . PHP_EOL;
					
					if ($concardisInfo['currency'] == 'EUR'){
						$paidByConcardisInEUR =  $paidByConcardis;
						$paidByConcardis = $this->currency->convert($paidByConcardisInEUR, 'EUR', $this->order['currency_code']);
					} else {
						$paidByConcardisInEUR = $this->currency->convert($paidByConcardis, $this->order['currency_code'], 'EUR');
					}
					
					echo '[CCRON API] Добавляем транзакцию ' . $order_id . PHP_EOL;
					
					//добавляем транзакцию полной оплаты								  
					$this->model_account_transaction->addTransaction(
					'Concardis Payengine: Оплата по заказу # '.$this->order['order_id'], 
					$paidByConcardisInEUR,
					$paidByConcardis,
					$this->order['currency_code'],
					$this->order['order_id'],
					true,
					'concardis',
					'',
					'',
					$this->order['concardis_id']
					);
				
					
					//SMS
					$options = array(
					'to'       => $this->order['telephone'],						
					'from'     => $this->config->get('config_sms_sign'),						
					'message'  => str_replace('{ID}', $this->order['order_id'], $sms_template)					
					);
					
					$sms_id = $this->smsQueue->queue($options);
					
					if ($sms_id){
						$sms_status = 'В очереди';					
						} else {
						$sms_status = 'Неудача';
					}
					
					$sms_data = array(
					'order_status_id' => $this->config->get('concardis_order_status_id'),
					'sms' => $options['message']
					);
					
					$this->model_checkout_order->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);
					
				}
				
			}
		}
		
		
		private function validateToken(){
			
			if (isset($this->request->get['token']) && isset($this->request->get['order_id'])){
				
				if (md5(md5(sha1($this->request->get['order_id'] . 'hmIQeCXDQVFOBDcAkE2gFWM0am4CK5Z4'))) == $this->request->get['token']){
					return true;
				}
				
			}
			
			return false;
			
		}
		
		public function callback(){
			
			$cLog = new Log('concardis_callback.txt');
			
			$cLog->write(serialize($this->request->post));
			
			
		}
		
		
		public function cancel(){	
			
			$cLog = new Log('concardis_callback.txt');
			$cLog->write('CANCEL PAYMENT');
			$cLog->write(serialize($this->request->post));
			
			if ($this->validateToken()){				
				$cLog->write('Отмена оплаты ' . $this->request->get['order_id']);
				
				$this->redirect($this->url->link('account/order', '', 'SSL'));			
				
				} else {
				$cLog->write('Невалидный токен ' . serialize($this->request->get));
				$this->redirect($this->url->link('account/order', '', 'SSL'));	
			}
		}
		
		public function fail(){	
			
			$cLog = new Log('concardis_callback.txt');
			$cLog->write('FAIL PAYMENT');
			$cLog->write(serialize($this->request->post));
			
			if ($this->validateToken()){			
				$cLog->write('Ошибка оплаты (возможно, неудачно) ' . $this->request->get['order_id']);
				$this->redirect($this->url->link('account/order', '', 'SSL'));		
				
				} else {
				$cLog->write('Невалидный токен ' . serialize($this->request->get));
				$this->redirect($this->url->link('account/order', '', 'SSL'));	
			}
		}
		
		public function success(){
			$this->load->model('account/transaction');
			$this->load->model('account/order');
			$this->load->model('checkout/order');
			
			$cLog = new Log('concardis_callback.txt');
			$cLog->write('SUCCESS PAYMENT');
			$cLog->write(serialize($this->request->post));
			
		
			if ($this->validateToken()){
			
				$this->model_checkout_order->addOrderToQueue($this->request->get['order_id']);
				
				if (!isset($this->session->data['order_id'])) {
					$this->session->data['order_id'] = $this->request->get['order_id'];
				}
				
				$this->redirect($this->url->link('checkout/success', '', 'SSL'));	
			
			/*	
				
				$sms_template = "Заказ # {ID}. Оплату получили, спасибо. Заказ выполняем";
				$this->model_checkout_order->addOrderToQueue($this->request->get['order_id']);
				
				if (!isset($this->session->data['order_id'])) {
					$this->session->data['order_id'] = $this->request->get['order_id'];
				}
				
				$this->order = $this->model_checkout_order->getOrder($this->request->get['order_id']);
				
				//для совместимости
				$order_id = $this->order['order_id'];
				
				if ($this->order['order_status_id'] != $this->config->get('concardis_order_status_id')) {
					
					$this->model_checkout_order->update($this->order['order_id'],
					$this->config->get('concardis_order_status_id'),
					'Оплата через Concardis Payengine',
					true,
					158);
					
					//добавляем транзакцию полной оплаты								  
					$this->model_account_transaction->addTransaction(
					'Concardis Payengine: Оплата по заказу # '.$this->order['order_id'], 
					$this->model_account_order->getOrderTotal($this->order['order_id']),
					$this->model_account_order->getOrderTotalNational($this->order['order_id']),
					$this->config->get('config_regional_currency'),
					$this->order['order_id'],
					true,
					'concardis',
					'',
					'',
					$this->order['concardis_id']
					);
					
					$this->session->data['success'] = 'Оплата прошла успешно. Спасибо.';
					
					//SMS
					$options = array(
					'to'       => $this->order['telephone'],						
					'from'     => $this->config->get('config_sms_sign'),						
					'message'  => str_replace(
					array(	'{ID}', 												
					'{SUM}', 
					), 
					array(
					$this->order['order_id'],  
					$this->currency->format($this->model_account_order->getOrderTotalNational($this->order['order_id']), $order_info['currency_code'], 1),
					), 
					$sms_template)
					);
					
					$sms_id = $this->smsQueue->queue($options);
					
					if ($sms_id){
						$sms_status = 'В очереди';					
						} else {
						$sms_status = 'Неудача';
					}
					
					$sms_data = array(
					'order_status_id' => $this->config->get('concardis_order_status_id'),
					'sms' => $options['message']
					);
					
					$this->model_checkout_order->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);
					
					$this->redirect($this->url->link('account/order', '', 'SSL'));	
					
					} else {
					$this->redirect($this->url->link('account/order', '', 'SSL'));	
				}
				*/
				
				
				} else {
				
				$this->redirect($this->url->link('account/order', '', 'SSL'));	
				
			}
			
			
		}
	}															
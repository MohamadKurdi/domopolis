<?

class ControllerPaymentConcardis extends Controller
{
	
	private $order;
	
	
	protected function index(){
		$this->load->model('checkout/order');
		$this->language->load('payment/bank_transfer');
		
		$order_id = (int)$this->session->data['order_id'];
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		$this->data['continue'] = $this->url->link('checkout/success');
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['continue'] = str_replace('&amp;', '&', $this->url->link('payment/concardis_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));
		
		if ($this->config->get('config_regional_currency') == 'EUR'){				
			$this->template = 'payment/concardis';				
		} else {				
			$this->template = 'payment/concardis_order';				
		}			
		
		$this->response->setOutput($this->render());
	}	
	
	public function cron(){
		$this->load->model('checkout/order');
		$this->load->model('account/order');
		$this->load->model('kp/order');
		$this->load->model('account/transaction');
		
		echo '[ControllerPaymentConcardis::cron] ---------------------- STAGE 1 ----------------------' . PHP_EOL;
		echo '[ControllerPaymentConcardis::cron] Пробуем отобрать заказы' . PHP_EOL;
		
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
			
			echo '[ControllerPaymentConcardis::cron] Заказ #' . $row['order_id'] . ', заявка ' . $row['concardis_id']  . PHP_EOL;
			
			$concardisInfo = $this->model_kp_order->getConcardisOrder($row['concardis_id']);
			
			if ($concardisInfo && !empty($concardisInfo['capturedAmount'])){
				if (!empty($concardisInfo['transactions']) && !empty($concardisInfo['transactions'][0]) && $concardisInfo['transactions'][0]['type'] == 'DEBIT' && $concardisInfo['transactions'][0]['status'] == 'SUCCESS' && $concardisInfo['transactions'][0]['initialAmount'] > 0 && $concardisInfo['lastOperation'] == 'TRANSACTION_DEBIT_SUCCESS'){
					
					echo '[ControllerPaymentConcardis::cron] Заявка ' . $row['concardis_id'] . ' оплачена, сумма ' . $concardisInfo['transactions'][0]['initialAmount'] . ' (' . $concardisInfo['capturedAmount'] . '), валюта '. $concardisInfo['transactions'][0]['currency'] .' последняя операция ' . $concardisInfo['lastOperation'] . PHP_EOL;
					$ORDER_IS_PAID = TRUE;
					$successOrders[$row['order_id']] = array(
						'concardis_id' => $row['concardis_id'], 
						'currency' => $concardisInfo['currency'], 
						'capturedAmount' => $concardisInfo['capturedAmount']
					);
					
				} else {
					
					echo '[ControllerPaymentConcardis::cron] Заявка ' . $row['concardis_id'] . ' непонятная ситуация, надо проверить!' . PHP_EOL;
					
				}
				
			} elseif ($concardisInfo && !empty($concardisInfo['lastOperation'])) {
				echo '[ControllerPaymentConcardis::cron] Заявка ' . $row['concardis_id'] . ' не оплачена, последняя операция ' . $concardisInfo['lastOperation'] . PHP_EOL;
			} else {
				echo '[ControllerPaymentConcardis::cron] Заявка ' . $row['concardis_id'] . ' не оплачена, неизвестный ответ сервера CC' . PHP_EOL;
			}			
		}
		
		echo '[ControllerPaymentConcardis::cron] ---------------------- STAGE 2 ----------------------' . PHP_EOL;
		
		foreach ($successOrders as $order_id => $concardisInfo){
			echoLine('[ControllerPaymentConcardis::cron] Adding payment for order: ' . $order_id, 's');								
			$this->Fiscalisation->setOrderPaidBy($order_id, 'concardis');   
			
				//Для совместимост
			$this->order = $this->model_checkout_order->getOrder($order_id);
			
			if ($this->order['order_status_id'] != $this->config->get('concardis_order_status_id')) {
				
				$this->model_checkout_order->update($this->order['order_id'],
					$this->config->get('concardis_order_status_id'),
					'Оплата через Concardis Payengine',
					true,
					158);
				
				$paidByConcardis = $concardisInfo['capturedAmount']/100;
				
				echo '[ControllerPaymentConcardis::cron] Валюта ' . $concardisInfo['currency'] . ', Сумма по API: ' . $paidByConcardis . ', итог заказа ' . $this->model_account_order->getOrderTotalNational($this->order['order_id']) . PHP_EOL;
				
				if ($concardisInfo['currency'] == 'EUR'){
					$paidByConcardisInEUR =  $paidByConcardis;
					$paidByConcardis = $this->currency->convert($paidByConcardisInEUR, 'EUR', $this->order['currency_code']);
				} else {
					$paidByConcardisInEUR = $this->currency->convert($paidByConcardis, $this->order['currency_code'], 'EUR');
				}
				
				echo '[ControllerPaymentConcardis::cron] Добавляем транзакцию ' . $order_id . PHP_EOL;
				
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
				
				$this->smsAdaptor->sendPayment($this->order, ['amount' => $paidByConcardis, 'order_status_id' => $this->config->get('concardis_order_status_id')]);					
			}
			
		}
	}
	
	
	private function validateToken(){			
		if (isset($this->request->get['token']) && isset($this->request->get['order_id'])){				
			if (md5($this->request->get['order_id'] . $this->config->get('config_encryption')) == $this->request->get['token']){
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
			$this->Fiscalisation->setOrderPaidBy($this->request->get['order_id'], 'concardis');
			
			if (!isset($this->session->data['order_id'])) {
				$this->session->data['order_id'] = $this->request->get['order_id'];
			}				
			$this->redirect($this->url->link('checkout/success', '', 'SSL'));									
		} else {				
			$this->redirect($this->url->link('account/order', '', 'SSL'));	
		}						
	}
}															
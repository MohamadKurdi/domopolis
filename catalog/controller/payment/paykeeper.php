<?php
class ControllerPaymentPayKeeper extends Controller {
	private $order;
	private $order_id;
	private $log;
	private $message = '';
	private $shoputils = 0;
	private $version;
	private static $LOG_OFF = 0;
	private static $LOG_SHORT = 1;
	private static $LOG_FULL = 2;
	private static $valid_currencies = array(
		'RUB', 'RUR'
	);
	
	public function __construct($registry) {
		parent::__construct($registry);		
	}
	
	private function SetIsPayingPrepay($order_id, $paying_prepay = 0){
		$this->db->query("UPDATE `order` SET paying_prepay = '". (int)$paying_prepay ."' WHERE order_id = '" .(int)$order_id. "'");		
	}
	
	private function isPayingPrepay($order_id){
		$result = $this->db->non_cached_query("SELECT paying_prepay FROM `order` WHERE order_id = '" .(int)$order_id. "' LIMIT 1");
		
		return $result->row['paying_prepay'];		
	}
	
	public function laterpay($order_id = false) {
		if ($this->validateLaterpay()) {
			
			$order_id = isset($this->request->get['order_id'])?(int)$this->request->get['order_id']:false;
			
			if (!$order_id ) {
				if (isset($this->session->data['order_id'])) {
					$order_id  = $this->session->data['order_id'];
				} else {
					$this->logWrite('Error: Unsupported Checkout Extension', self::$LOG_SHORT);
					die($this->language->get('error_fail_checkout_extension'));
				}
			} else {
				$this->session->data['order_id'] = $order_id;
			}
			
			$this->SetIsPayingPrepay($order_id, 0);
			
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			if ($order_info['currency_code'] == 'RUB'){
				$sAmount = number_format($order_info['total_national'], 2, '.', '');			
			} else {
				$sAmount = number_format($this->currency->convert($order_info['total_national'], $order_info['currency_code'], 'RUB'), 2, '.', '');		
			}
			
			$request_data = array();    
			$request_data['clientid'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
			$request_data['orderid'] = $order_info['order_id'];
			$request_data['sum'] = $sAmount;
			$request_data['userip'] = $order_info['ip'];
			$request_data['phone'] = $order_info['telephone'];
			$request_data['client_email'] = $order_info['email'];
			
			$options = array (
				'http' => array (
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($request_data)
				)
			);
			$context = stream_context_create($options);
			
			$this->logWrite('Make payment form: ', self::$LOG_FULL);
			$this->logWrite('  DATA: ' . var_export($request_data, true), self::$LOG_FULL);
			
			$this->data['paykeeper_form'] = file_get_contents($this->config->get('paykeeper_server_url'), false, $context);
			
			$this->data['noindex'] = true;

			if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
				$this->data['icon'] = $server . DIR_IMAGE_NAME . $this->config->get('config_icon');
			} else {
				$this->data['icon'] = '';
			}
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . DIR_IMAGE_NAME . $this->config->get('config_logo');
			} else {
				$this->data['logo'] = '';
			}		
			
			$this->template = 'payment/paykeeper.tpl';
			
			$this->response->setOutput($this->render());
		} else {
			$this->logWrite('Fail Validate Laterpay:', self::$LOG_FULL);
			$this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
			$this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
			$this->redirect($this->url->link('account/order', '', 'SSL'));
		}
	}
	
	public function laterprepay($order_id = false) {
		if ($this->validateLaterprepay()) {
			
			$order_id = isset($this->request->get['order_id'])?(int)$this->request->get['order_id']:false;
			
			if (!$order_id ) {
				if (isset($this->session->data['order_id'])) {
					$order_id  = $this->session->data['order_id'];
				} else {
					$this->logWrite('Error: Unsupported Checkout Extension', self::$LOG_SHORT);
					die($this->language->get('error_fail_checkout_extension'));
				}
			} else {
				$this->session->data['order_id'] = $order_id;
			}
			
			$this->SetIsPayingPrepay($order_id, 1);
			
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$totals = $this->model_account_order->getOrderTotals($order_id);
			
			foreach ($totals as $total){
				if ($total['code'] == 'transfer_plus_prepayment'){
					
					if ($order_info['currency_code'] == 'RUB'){
						$sAmount = number_format($total['value_national'], 2, '.', '');			
					} else {
						$sAmount = number_format($this->currency->convert($total['value_national'], $order_info['currency_code'], 'RUB'), 2, '.', '');		
					}	
				}
			}	
			
			$request_data = array();    
			$request_data['clientid'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
			$request_data['orderid'] = $order_info['order_id'];
			$request_data['sum'] = $sAmount;
			$request_data['userip'] = $order_info['ip'];
			$request_data['phone'] = $order_info['telephone'];
			$request_data['client_email'] = $order_info['email'];
			
			$options = array (
				'http' => array (
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($request_data)
				)
			);
			$context = stream_context_create($options);
			
			$this->logWrite('Make prepay form: ', self::$LOG_FULL);
			$this->logWrite('  DATA: ' . var_export($request_data, true), self::$LOG_FULL);
			
			$this->data['paykeeper_form'] = file_get_contents($this->config->get('paykeeper_server_url'), false, $context);
			
			$this->data['noindex'] = true;
			$this->load->model('tool/image');

			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] =  $this->model_tool_image->resize($this->config->get('config_logo'), 60, 60);;
			} else {
				$this->data['logo'] = '';
			}		
			
			$this->template = 'payment/paykeeper.tpl';
			
			$this->response->setOutput($this->render());
		} else {
			$this->logWrite('Fail Validate Laterpay:', self::$LOG_FULL);
			$this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
			$this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
			$this->redirect($this->url->link('account/order', '', 'SSL'));
		}
	}
	
	protected function validateLaterprepay() {
		if ((!isset($this->request->get['order_id'])) || (!isset($this->request->get['order_tt'])) || (!isset($this->request->get['order_fl']))) {
			return false;
		} else {
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			$order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
			
			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
			
			foreach ($totals as $total){
				if ($total['code'] == 'transfer_plus_prepayment'){
					$order_info['total_national'] = $total['value_national'];							
				}	
			}
			
			if ((!$order_info) || ($this->request->get['order_id'] != $order_info['order_id']) || ($this->request->get['order_tt'] != $order_info['total_national']) || ($this->request->get['order_fl']) != md5($order_info['firstname'] . $order_info['lastname'])) {
				return false;
			}
		}
		return true;
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

	public function confirm(){
		$this->load->model('checkout/order');			
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);	

		if ($order_info){
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paykeeper_order_fail_status_id'), 'PayKeeper payment process started');			
		}		
	}

	public function pay(){
		$this->load->model('checkout/order');			
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);					
		
		$request_data = array();    
		$request_data['clientid'] 	= $order_info['customer_id'];
		$request_data['orderid'] 	= $order_info['order_id'];
		$request_data['sum'] 		= $order_info['total_national'];
		$request_data['userip'] 	= $order_info['ip'];
		$request_data['phone'] 			= $order_info['telephone'];
		$request_data['client_email'] 	= $order_info['email'];
		
		$options = array (
			'http' => array (
				'method' => 'POST',
				'header' => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($request_data)
			)
		);
		$context = stream_context_create($options);
		
		$this->data['paykeeper_form'] = file_get_contents($this->config->get('paykeeper_server_url'), false, $context);
		$this->load->model('tool/image');

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] =  $this->model_tool_image->resize($this->config->get('config_logo'), 60, 60);;
		} else {
			$this->data['logo'] = '';
		}	

		$this->template = 'payment/paykeeper.tpl';		

		$this->response->setOutput($this->render());	
	}
	
	
	protected function index() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['continue'] = $this->url->link('payment/paykeeper/pay');

		$this->load->model('checkout/order');			
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);								
		
		if ($this->config->get('paykeeper_pay_on_checkout')) {
			$this->template = 'payment/paykeeper_laterpay.tpl';
		} else {
			$this->template = 'payment/paykeeper_order.tpl';
		}
		
		$this->render();
	}
	
	public function callback() {
		$this->logWrite('CallbackURL: ', self::$LOG_SHORT);
		$this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_SHORT);
		$this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_SHORT);
		
		if (!$this->request->post) {
			if ($this->validateSession()) {
				$this->redirect($this->url->link('account/order', '', 'SSL'));
			} else {
				$this->redirect($this->url->link('account/order', '', 'SSL'));
			}
		}
		
		if (!$this->validateRequest()) {
			$this->fail();
			return;
		}
		
		$this->success($this->request->post);
		return;
	}
	
	public function validateRequest(){
		$this->load->model('checkout/order');
		
		$secret_key = $this->config->get('paykeeper_secret_key');
		
		if (count($this->request->post) == 0) {
			$this->sendForbidden('No POST DATA');
			return false;
		}
		
		$order_id = isset($this->request->post['orderid']) ? $this->request->post['orderid'] : 0;
		$this->order = $this->model_checkout_order->getOrder($order_id );
		if (!$this->order) {
			$this->sendForbidden(sprintf('Order ID: %s', $order_id ));
			die('Order ID mismatch');
			return false;
		}
		
		$allowed_params = array(
			'id',
			'sum',
			'orderid',
			'clientid',
			'key'
		);
		
		foreach ($allowed_params as $key) {
			if (!isset($this->request->post[$key])){
				$this->sendForbidden('Not enough POST params');
				die('Params Mismatch');
				return false;
			}
		}			
		
		$pkid = $this->request->post['id'];
		$sum = $this->request->post['sum'];
		$orderid = $this->request->post['orderid'];
		$clientid = $this->request->post['clientid'];
		$sign = $this->request->post['key'];
		
			//
		$check_transaction = $this->db->non_cached_query("SELECT * FROM customer_transaction WHERE order_id = '" . (int)$orderid . "' AND added_from = 'paykeeper' AND paykeeper_id = '" . (int)$pkid . "'");
		if ($check_transaction->num_rows){				
			$this->sendRepeatError(var_export($this->request->post, true));
			$gs = "OK ".md5($pkid.$secret_key);
			die($gs);
			return false;
		}
		
		$hash = md5($pkid.sprintf ("%.2lf", $sum).$clientid.$orderid.$secret_key);
		if ($hash != $sign ) {				
			$this->sendForbidden('Hash mismatch');
			die('Hash mismatch');
			return false;			
		}
		
		
		echo "OK ".md5($pkid.$secret_key);
		return true;			
	}		
	
	public function callback_original() {
		$secret_key = $this->config->get('paykeeper_secret_key');
		
		if (count($this->request->post) == 0)
			die('No post data');
		
		$pkid = $this->request->post['id'];
		$sum = $this->request->post['sum'];
		$orderid = $this->request->post['orderid'];
		$clientid = $this->request->post['clientid'];
		$sign = $this->request->post['key'];
		
		$hash = md5($pkid.$sum.$clientid.$orderid.$secret_key);
		
		if ($hash != $sign )
			die('Hash mismatch');
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($orderid);
		
		if ((float)$sum != (float)$order_info['total'])
			die('Sum mismatch');
		
		if( $order_info['order_status_id'] == 0) 
			$this->model_checkout_order->confirm($orderid, $this->config->get('paykeeper_order_status_id'), 'Payment received by PayKeeper');
		
		if( $order_info['order_status_id'] != $this->config->get('paykeeper_order_status_id')) 
			$this->model_checkout_order->update($orderid, $this->config->get('paykeeper_order_status_id'),'Payment received by PayKeeper',TRUE);			               
		
		echo "OK ".md5($pkid.$secret_key);
	}
	
	protected function logWrite($message, $type = 2) {
		
		if (!$this->log) {
			$this->log = new Log('paykeeper_payments.log');
		}
		
		$this->log->Write($message);
	}
	
	public function fail() {
		if (isset($this->order['order_status_id']) && $this->order['order_status_id']) {				
			$notify = !$this->config->get('shoputils_psb_notify_customer_fail');
			
			$this->model_checkout_order->update($this->order['order_id'],
				$this->config->get('shoputils_psb_order_fail_status_id'),
				'PayKeeper: оплата отклонена. Причины, пожалуйста, уточняйте у своего банка.',
				true);
			
			if (!$notify) {
				$langdata = $this->config->get('shoputils_psb_langdata');
				$langdata = $langdata[(int)$this->config->get('config_language_id')];
				$this->sendMail($langdata['mail_customer_fail_subject'],
					$langdata['mail_customer_fail_content'],
					$this->config->get('shoputils_psb_order_fail_status_id'),
					'customer',
					'Mail to Customer Sent Successfully: Payment Fail ' . $this->message);
			}
			
			if (true) {
				$this->sendMail($this->config->get('shoputils_psb_mail_admin_fail_subject'),
					$this->config->get('shoputils_psb_mail_admin_fail_content'),
					$this->config->get('shoputils_psb_order_fail_status_id'),
					'admin',
					'Mail to Admin Sent Successfully: Payment Fail ' . $this->message);
			}
			
			$this->SetIsPayingPrepay($this->order['order_id'], 0);
		}
		
		$this->logWrite('FailURL: Payment Fail', self::$LOG_SHORT);
		$this->redirect($this->url->link('account/order', '', 'SSL'));
	}
	
	protected function sendForbidden($error) {
		$this->logWrite('ERROR: ' . $error, self::$LOG_SHORT);
	}
	
	protected function sendRepeatError($error) {
		$this->logWrite('REPEAT TRANSACTION: ' . $error, self::$LOG_SHORT);
	}
	
	protected function sendOk() {
		$this->logWrite('OK: ' . http_build_query($this->request->post, '', ','), self::$LOG_SHORT);
	}
	
	protected function sendMail($subject, $content, $order_status_id, $type = 'admin', $log_result = '') {
		$this->load->model('payment/shoputils_psb');
		
		$order_info = $this->model_payment_shoputils_psb->getOrder($this->order['order_id']);
		
		$input = array(
			'{order_id}',
			'{store_name}',
			'{logo}',
			'{products}',
			'{total}',
			'{customer_firstname}',
			'{customer_lastname}',
			'{customer_group}',
			'{customer_email}',
			'{customer_telephone}',
			'{order_status}',
			'{comment}',
			'{ip}',
			'{date_added}',
			'{date_modified}'
		);
		
		$output = array(
			'order_id'            => $order_info['order_id'],
			'store_name'          => $this->config->get('config_name'),
			'logo'                => '<a href="' . HTTP_SERVER . '"><img src="' . HTTP_SERVER . 'image/' . $this->config->get('config_logo') . '" / ></a>',
			'products'            => $this->model_payment_shoputils_psb->getProducts($order_info['order_id']),
			'total'               => $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1),
			'customer_firstname'  => $order_info['payment_firstname'],
			'customer_lastname'   => $order_info['payment_lastname'],
			'customer_group'      => $this->model_payment_shoputils_psb->getCustomerGroup($order_info['customer_group_id']),
			'customer_email'      => $order_info['email'],
			'customer_telephone'  => $order_info['telephone'],
			'order_status'        => $this->model_payment_shoputils_psb->getOrderStatusById($order_status_id, $order_info['language_id']),
			'comment'             => $order_info['comment'],
			'ip'                  => $order_info['ip'],
			'date_added'          => $order_info['date_added'],
			'date_modified'       => $order_info['date_modified']
		);
		
		$subject = html_entity_decode(trim(str_replace($input, $output, $subject)), ENT_QUOTES, 'UTF-8');
		$content = html_entity_decode(str_replace($input, $output, $content), ENT_QUOTES, 'UTF-8');
		
		$to = $type == 'admin' ? $this->config->get('config_email') : $order_info['email'];
		$this->_sendMail($subject, $content, $type, $to);
		$this->logWrite($log_result, self::$LOG_FULL);
	}
	
	protected function _sendMail($subject, $content, $type, $to) {
		$message  = '<html dir="ltr" lang="en">' . "\n";
		$message .= '  <head>' . "\n";
		$message .= '    <title>' . $subject . '</title>' . "\n";
		$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
		$message .= '  </head>' . "\n";
		$message .= '  <body>' . $content . '</body>' . "\n";
		$message .= '</html>' . "\n";
		
		$mail = new Mail($this->registry); 			
		$mail->setTo($to);
		$mail->setSubject($subject);
		$mail->setHtml($message);		
		$mail->send();				
		
		if ($type == 'admin') {
			$emails = array($this->config->get('config_payment_mail_to'));
			foreach ($emails as $email) {							
				$mail->setTo($email);
				$mail->send();							
			}
		}
	}
	
	public function success($data = array()) {
		$this->load->model('account/transaction');
		$this->load->model('account/order');
		$this->load->model('checkout/order');
		$this->load->model('payment/shoputils_psb');
		$this->load->model('payment/paykeeper');
		
		$this->model_checkout_order->addOrderToQueue($this->order['order_id']);
		
		if (!isset($this->session->data['order_id'])) {
				$this->session->data['order_id'] = $this->order['order_id']; //Добавляем в сессию номер заказа на случай, если в checkout/success на экран пользователю выводится номер заказа
			}
			
			if ($this->order['order_status_id'] != $this->config->get('shoputils_psb_order_status_id')) {
				if ($this->order['order_status_id']){
					//Reverse $this->config->get('shoputils_psb_notify_customer_success')
					$notify = !$this->config->get('shoputils_psb_notify_customer_success');
					
					if ($this->isPayingPrepay($this->order['order_id']) == 1) {
						$this->model_checkout_order->update($this->order['order_id'],
							$this->config->get('config_prepayment_paid_order_status_id'),
							'Внесение предоплаты через ПромСвязьБанк Эквайринг / PayKeeper',
							$notify);
						//добавляем транзакцию
						$this->model_account_transaction->addTransaction(
							'ПромСвязьБанк / PayKeeper: Предоплата по заказу # '.$this->order['order_id'], 
							$this->model_account_order->getOrderPrepay($this->order['order_id']),
							$this->model_account_order->getOrderPrepayNational($this->order['order_id']),
							$this->config->get('config_regional_currency'),
							$this->order['order_id'],
							true,
							'paykeeper',
							$data['id'],
							$data['fop_receipt_key']
						);
						
						$this->SetIsPayingPrepay($this->order['order_id'], 0);	
						
						if (!$notify && false) {
							$langdata = $this->config->get('shoputils_psb_langdata');
							$langdata = $langdata[(int)$this->config->get('config_language_id')];
							$this->sendMail($langdata['mail_customer_success_subject'],
								$langdata['mail_customer_success_content'],
								$this->config->get('config_prepayment_paid_order_status_id'),
								'customer',
								'Mail to Customer Sent Successfully: Payment Success ' . $this->message);
						}
						
						if ($this->order['currency_code'] == 'RUB'){
							$actual_amount = number_format($this->model_account_order->getOrderPrepayNational($this->order['order_id']), 2, '.', '');			
						} else {
							$actual_amount = number_format($this->currency->convert($this->model_account_order->getOrderPrepayNational($this->order['order_id']), $this->order['currency_code'], 'RUB'), 2, '.', '');		
						}
						
						//SMS
						if ($this->config->get('config_sms_payment_recieved_enabled')){
							$sms_template = $this->config->get('config_sms_payment_recieved');  
							$options = array(
								'to'       => $this->order['telephone'],						
								'from'     => $this->config->get('config_sms_sign'),						
								'message'  => str_replace(
									array(	'{ID}', 												
										'{SUM}', 
									), 
									array(
										$this->order['order_id'],  
										$this->currency->format($this->model_account_order->getOrderPrepayNational($this->order['order_id']), $this->order['currency_code'], 1),
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
								'order_status_id' => $this->config->get('config_prepayment_paid_order_status_id'),
								'sms' => $options['message']
							);
						}
						
						//$log = new Log('order_send_sms.txt');
						//$log->write(serialize($sms_data) . ', returning: ' . $sms_id);				
						$this->smsAdaptor->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);
						
						$title = 'Предоплата по заказу # ' . $this->order['order_id'];
						$html =  'Заказ: # '.$this->order['order_id'] . 
						'<br />Сумма: ' . 
						$this->model_account_order->getOrderPrepayNational($this->order['order_id']) . ' ' . 
						$this->config->get('config_regional_currency') . 
						'<br />Фактически было запрошено: ' . $actual_amount .' '. $this->config->get('config_regional_currency') .
						'<br />Фактически было получено: ' . $data['sum'] . ' RUB'.
						'<br />Время: '.date("d:m:Y H:i:s") . 
						'<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('config_prepayment_paid_order_status_id'), $this->order['language_id']);
						
						$xlog = new Log('psb_alert.txt');
						$xlog->write($title . ' - '. $html);				
						
						$mail = new Mail($this->registry); 
						$mail->setFrom($this->config->get('config_payment_mail_from'));
						$mail->setSender($this->config->get('config_payment_mail_from'));
						$mail->setSubject(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
						$mail->setHtml($html);
						
						$mail->setTo($this->config->get('config_payment_mail_to'));						
						$mail->send();	
						
					} else {
						$this->model_checkout_order->update($this->order['order_id'],
							$this->config->get('shoputils_psb_order_status_id'),
							'Оплата через ПромСвязьБанк Эквайринг / PayKeeper',
							$notify);
						//добавляем транзакцию полной оплаты								  
						$this->model_account_transaction->addTransaction(
							'ПромСвязьБанк / PayKeeper: Оплата по заказу # '.$this->order['order_id'], 
							$this->model_account_order->getOrderTotal($this->order['order_id']),
							$this->model_account_order->getOrderTotalNational($this->order['order_id']),
							$this->config->get('config_regional_currency'),
							$this->order['order_id'],
							true,
							'paykeeper',
							$data['id'],
							$data['fop_receipt_key']
						);
						
						if ($this->order['currency_code'] == 'RUB'){
							$actual_amount = number_format($this->model_account_order->getOrderTotalNational($this->order['order_id']), 2, '.', '');			
						} else {
							$actual_amount = number_format($this->currency->convert($this->model_account_order->getOrderTotalNational($this->order['order_id']), $this->order['currency_code'], 'RUB'), 2, '.', '');		
						}
						
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
									$this->currency->format($this->model_account_order->getOrderTotalNational($this->order['order_id']), $this->order['currency_code'], 1),
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
							'order_status_id' => $this->config->get('config_prepayment_paid_order_status_id'),
							'sms' => $options['message']
						);
						
						//$log = new Log('order_send_sms.txt');
						//$log->write(serialize($sms_data) . ', returning: ' . $sms_id);				
						$this->smsAdaptor->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);					
						
						
						if (!$notify && false) {
							$langdata = $this->config->get('shoputils_psb_langdata');
							$langdata = $langdata[(int)$this->config->get('config_language_id')];
							$this->sendMail($langdata['mail_customer_success_subject'],
								$langdata['mail_customer_success_content'],
								$this->config->get('shoputils_psb_order_status_id'),
								'customer',
								'Mail to Customer Sent Successfully: Payment Success ' . $this->message);
						}
						
						$title = 'Полная оплата по заказу # ' . $this->order['order_id'];
						$html =   'Заказ: # '.$this->order['order_id'] . 
						'<br />Сумма: ' . 									
						$this->model_account_order->getOrderTotalNational($this->order['order_id']) . ' ' . 
						$this->config->get('config_regional_currency') . 
						'<br />Фактически было запрошено: ' . $actual_amount .' '. $this->config->get('config_regional_currency') .
						'<br />Фактически было получено: ' . $data['sum'] . ' RUB'.
						'<br />Время: '.date("d:m:Y H:i:s") . 
						'<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('shoputils_psb_order_status_id'), $this->order['language_id']);
						
						$xlog = new Log('paykeeper_alert.txt');
						$xlog->write($title . ' - '. $html);
						
						
						$mail = new Mail($this->registry); 						
						$mail->setFrom($this->config->get('config_payment_mail_from'));
						$mail->setSender($this->config->get('config_payment_mail_from'));
						$mail->setSubject(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
						$mail->setHtml($html);
						
						$mail->setTo($this->config->get('config_payment_mail_to'));						
						$mail->send();					
						
					}
					$this->logWrite('SuccessURL: Payment Success', self::$LOG_SHORT);
					//$this->redirect($this->url->link('checkout/success', '', 'SSL'));
				} else {
					$this->logWrite('SuccessURL: Payment Fail', self::$LOG_SHORT);
					//$this->redirect($this->url->link('common/home', '', 'SSL'));
				}
			}
		}
		
	}
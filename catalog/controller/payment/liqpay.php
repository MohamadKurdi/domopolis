<?php
	/**
		* Liqpay Payment Module
		*
		* NOTICE OF LICENSE
		*
		* This source file is subject to the Open Software License (OSL 3.0)
		* that is available through the world-wide-web at this URL:
		* http://opensource.org/licenses/osl-3.0.php
		*
		* @category        Liqpay
		* @package         Payment
		* @version         3.0
		* @author          Liqpay
		* @copyright       Copyright (c) 2014 Liqpay
		* @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
		*
		* EXTENSION INFORMATION
		*
		* OpenCart         1.5.6
		* LiqPay API       https://www.liqpay.com/ru/doc
		*
	*/
	
	/**
		* Payment method liqpay controller (catalog)
		*
		* @author      Liqpay <support@liqpay.com>
	*/
	class ControllerPaymentLiqpay extends Controller
	{
		
		private $order;
		
		/**
			* Index action
			*
			* @return void
		*/
		public function index()
		{			

			$this->load->model('checkout/order');
			
			$order_id = (int)$this->session->data['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$description = 'Заказ #'.$order_id;
			
			$order_id .= '#'.time();
			$result_url = $this->url->link('checkout/success', '', 'SSL');
			$server_url = $this->url->link('payment/liqpay/server', '', 'SSL');
			
			$private_key = $this->config->get('liqpay_private_key');
			$public_key = $this->config->get('liqpay_public_key');
			$type = 'buy';
			$currency = $order_info['currency_code'];
			if ($currency == 'RUR') { $currency = 'RUB'; }
			
			$amount = $sAmount = number_format($order_info['total_national'], 2, '.', '');		
			
			$version  = '3';
			//$language = $this->language->get('code');
			
			//$language = $language == 'ru' ? 'ru' : 'en';
			$pay_way  = $this->config->get('liqpay_pay_way');
			$language = $this->config->get('liqpay_language');
			
			$send_data = array('version'    => $version,
			'public_key'  => $public_key,
			'amount'      => $amount,
			'currency'    => $currency,
			'description' => $description,
			'order_id'    => $order_id,
			'type'        => $type,
			'language'    => $language,
			'server_url'  => $server_url,
			'result_url'  => $result_url);
			if(isset($pay_way)){
				$send_data['pay_way'] = $pay_way;
			}
			
			$data = base64_encode(json_encode($send_data));
			
			$signature = base64_encode(sha1($private_key.$data.$private_key, 1));
			
			$this->data['action']         = $this->config->get('liqpay_action');
			$this->data['signature']      = $signature;
			$this->data['data']           = $data;
			$this->data['button_confirm'] = 'Оформить заказ и оплатить с помощью LiqPay';
			$this->data['url_confirm']    = $this->url->link('payment/liqpay/confirm');
			
			$this->template = $this->config->get('config_template').'/template/payment/liqpay.tpl';
			
			if (!file_exists(DIR_TEMPLATE.$this->template)) {
				$this->template = 'default/template/payment/liqpay.tpl';
			}
			
			$this->response->setOutput($this->render());
		}
		/**
			* Confirm action
			*
			* @return void
		*/
		public function confirm()
		{
			$this->load->model('checkout/order');
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'), 'Начат процесс оплаты LiqPay');     
		}
		
		
		/**
			* Check and return posts data
			*
			* @return array
		*/
		private function getPosts()
		{
			$success =
            isset($_POST['data']) &&
            isset($_POST['signature']);
			
			if ($success) {
				return array(
                $_POST['data'],
                $_POST['signature'],
				);
			}
			return array();
		}
		
		
		/**
			* get real order ID
			*
			* @return string
		*/
		public function getRealOrderID($order_id)
		{
			$real_order_id = explode('#', $order_id);
			return $real_order_id[0];
		}
		
		
		public function success($data = array()){
			$this->load->model('account/transaction');
			$this->load->model('account/order');
			$this->load->model('checkout/order');
			$this->load->model('payment/shoputils_psb');
			$this->load->model('payment/paykeeper');
			
			$sms_template = "Заказ # {ID}. Оплату в сумме {SUM}, получили, спасибо. Заказ выполняем";
			
			$this->model_checkout_order->addOrderToQueue($this->order['order_id']);
			
			if (!isset($this->session->data['order_id'])) {
				$this->session->data['order_id'] = $this->order['order_id'];
			}
			
			$this->model_checkout_order->update($this->order['order_id'], $this->config->get('liqpay_order_status_id'), 'Оплата через LiqPay', true);
			

			//добавляем транзакцию полной оплаты								  
			$this->model_account_transaction->addTransaction(
			'LiqPay: Оплата по заказу # '.$this->order['order_id'], 
			$this->model_account_order->getOrderTotal($this->order['order_id']),
			$this->model_account_order->getOrderTotalNational($this->order['order_id']),
			$this->config->get('config_regional_currency'),
			$this->order['order_id'],
			true,
			'liqpay',
			'',
			'',
			'',
			$data
			);
			
			//SMS
			$options = array(
			'to'       => $this->order['telephone'],						
			'from'     => $this->config->get('config_sms_sign'),						
			'message'  => str_replace(
			array(	'{ID}', '{SUM}' ), 
			array( $this->order['order_id'], $this->currency->format($data['amount'], $this->order['currency_code'], 1),
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
			'order_status_id' => $this->config->get('liqpay_order_status_id'),
			'sms' => $options['message']
			);
			
			$this->model_checkout_order->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);	
			
			if ($this->order['currency_code'] == 'UAH'){
				$actual_amount = number_format($this->model_account_order->getOrderTotalNational($this->order['order_id']), 2, '.', '');			
				} else {
				$actual_amount = number_format($this->currency->convert($this->model_account_order->getOrderTotalNational($this->order['order_id']), $this->order['currency_code'], 'RUB'), 2, '.', '');		
			}
			
			$title = 'Полная оплата по заказу # ' . $this->order['order_id'];
			$html =   'Заказ: # '.$this->order['order_id'] . 
			'<br />Сумма: ' . 									
			$this->model_account_order->getOrderTotalNational($this->order['order_id']) . ' ' . 
			$this->config->get('config_regional_currency') . 
			'<br />Фактически было запрошено: ' . $actual_amount .' '. $this->config->get('config_regional_currency') .
			'<br />Фактически было получено: ' . $data['amount'] . ' RUB'.
			'<br />Время: '.date("d:m:Y H:i:s") . 
			'<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('liqpay_order_status_id'), $this->order['language_id']);
			
			$xlog = new Log('liqpay_mails.txt');
			$xlog->write($title . ' - '. $html);
			
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');			
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setFrom($this->config->get('config_payment_mail_from'));
			$mail->setSender($this->config->get('config_payment_mail_from'));
			$mail->setSubject(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($html);
			
			$mail->setTo($this->config->get('config_payment_mail_to'));						
			//	$mail->send();	
			
		}
		
		/**
			* Server action
			*
			* @return void
		*/
		public function server()
		{
		
		/*
			$this->load->model('account/order');
			$this->load->model('checkout/order');
			
			if ($_SERVER['REMOTE_ADDR'] == '37.57.184.228') {
				$real_order_id = '124319';
				
				if (!($order_info = $this->model_checkout_order->getOrder($real_order_id))) { die("Order_id fail");}
				
				$this->order = $order_info;
				$parsed_data = array(
					'amount' => 1
				);
				$this->success($parsed_data);
			}
		*/	
			
			$log = new Log('liqpay_payment_server.txt');
			
			if (!$posts = $this->getPosts()) { die('Posts error'); }
			
			$log->write(serialize($this->request->post));
			
			list(
            $data,
            $signature
			) = $posts;
			
			if(!$data || !$signature) {die("No data or signature");}
			
			$parsed_data = json_decode(base64_decode($data), true);
			
			$log->write(serialize($parsed_data));
			
			$received_public_key = $parsed_data['public_key'];
			$order_id            = $parsed_data['order_id'];
			$status              = $parsed_data['status'];
			$sender_phone        = $parsed_data['sender_phone'];
			$amount              = $parsed_data['amount'];
			$currency            = $parsed_data['currency'];
			$transaction_id      = $parsed_data['transaction_id'];
			
			$real_order_id = $this->getRealOrderID($order_id);
			
			if ($real_order_id <= 0) { die("Order_id real_order_id < 0"); }
			
			$this->load->model('checkout/order');
			if (!($order_info = $this->model_checkout_order->getOrder($real_order_id))) { die("Order_id fail");}
			
			$private_key = $this->config->get('liqpay_private_key');
			$public_key  = $this->config->get('liqpay_public_key');
			
			$generated_signature = base64_encode(sha1($private_key.$data.$private_key, 1));
			
			if ($signature  != $generated_signature) { die("Signature secure fail"); }
			if ($public_key != $received_public_key) { die("public_key secure fail"); }
			
			if ($status == 'success' || $status == 'wait_accept') {
				$this->order = $order_info;
				
				$this->session->data['success'] = 'Оплата прошла успешно. Благодарим за сотрудничество.';
				$this->success($parsed_data);
				
				} else {
				$this->confirm();
			} 
			
			
		}
	}

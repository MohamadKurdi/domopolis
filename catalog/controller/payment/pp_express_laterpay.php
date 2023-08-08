<?php
	class ControllerPaymentPPExpressLaterPay extends Controller {
		protected function index() {
			
		}
		
		
		public function checkout() {
			
			if (!$this->validateLaterpay()){
				$this->redirect($this->url->link('account/order', '', 'SSL'));
			}
			
			$order_id = isset($this->request->get['order_id'])?(int)$this->request->get['order_id']:false;
			
			if (!$order_id){
				$this->redirect($this->url->link('account/order', '', 'SSL'));				
			}
			
			$this->session->data['order_id'] = $order_id;
			
			$this->load->model('payment/pp_express');
			$this->load->model('payment/pp_express_laterpay');
			$this->load->model('tool/image');
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			$max_amount = $this->currency->convert($order_info['total'], $this->config->get('config_currency'), 'USD');
			$max_amount = min($max_amount * 1.25, 10000);
			$max_amount = $this->currency->format($max_amount, $this->currency->getCode(), '', false);
			
			$data = array(
			'METHOD' => 'SetExpressCheckout',
			'MAXAMT' => $max_amount,
			'RETURNURL' => $this->url->link('payment/pp_express_laterpay/checkoutReturn', '', 'SSL'),
			'CANCELURL' => $this->url->link('account/order', '', 'SSL'),
			'REQCONFIRMSHIPPING' => 0,
			'NOSHIPPING' => 1,
			'LOCALECODE' => 'RU',
			'LANDINGPAGE' => 'Login',
			'HDRIMG' => $this->model_tool_image->resize($this->config->get('pp_express_logo'), 790, 90),
			'HDRBORDERCOLOR' => $this->config->get('pp_express_border_colour'),
			'HDRBACKCOLOR' => $this->config->get('pp_express_header_colour'),
			'PAYFLOWCOLOR' => $this->config->get('pp_express_page_colour'),
			'CHANNELTYPE' => 'Merchant',
			'ALLOWNOTE' => $this->config->get('pp_express_allow_note'),
			);
			
			$data = array_merge($data, $this->model_payment_pp_express_laterpay->paymentRequestInfo($order_id));
			
			$result = $this->model_payment_pp_express->call($data);
			
			//	$this->log->debug($data);
			//	$this->log->debug($result);
			//	die();
			
			/**
				* If a failed PayPal setup happens, handle it.
			*/
			if(!isset($result['TOKEN'])) {
				$this->session->data['warning'] = $result['L_LONGMESSAGE0'];
				$this->session->data['error'] = $result['L_LONGMESSAGE0'];
				/**
					* Unable to add error message to user as the session errors/success are not
					* used on the cart or checkout pages - need to be added?
					* If PayPal debug log is off then still log error to normal error log.
				*/
				if($this->config->get('pp_express_debug') == 0) {
					$this->log->write(serialize($result));
				}
				
				
				$this->redirect($this->url->link('account/order', '', 'SSL'));
			}
			
			$this->session->data['paypal'] = array();
			$this->session->data['paypal']['token'] = $result['TOKEN'];
			
			if ($this->config->get('pp_express_test') == 1) {
				header('Location: https://www.sandbox.paypal.com/cgi/bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'].'&useraction=commit');
				} else {
				header('Location: https://www.paypal.com/cgi/bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN'].'&useraction=commit');
			}
		}
		
		public function checkoutReturn() {
			$this->language->load('payment/pp_express');
			
			/**
				* Get the details
			*/
			$this->load->model('payment/pp_express');
			$this->load->model('payment/pp_express_laterpay');
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			$this->load->model('account/transaction');
			
			if (!isset($this->session->data['order_id']) || !isset($this->session->data['paypal']['token'])){				
				$this->redirect($this->url->link('account/order', '', 'SSL'));	
			}
			
			$order_id = (int)$this->session->data['order_id'];		
			$order_info = $this->model_account_order->getOrder($order_id);
			
			$data = array(
			'METHOD' => 'GetExpressCheckoutDetails',
			'TOKEN' => $this->session->data['paypal']['token'],
			);
			
			$result = $this->model_payment_pp_express->call($data);
			
			$this->session->data['paypal']['payerid'] = $result['PAYERID'];
			$this->session->data['paypal']['result'] = $result;
			
			$paypal_data = array(
			'TOKEN' => $this->session->data['paypal']['token'],
			'PAYERID' => $this->session->data['paypal']['payerid'],
			'METHOD' => 'DoExpressCheckoutPayment',
			'PAYMENTREQUEST_0_NOTIFYURL' => $this->url->link('payment/pp_express/ipn', '', 'SSL'),
			'RETURNFMFDETAILS' => 1,
			);
			
		//	var_dump($order_id);
			
			$paypal_data = array_merge($paypal_data, $this->model_payment_pp_express_laterpay->paymentRequestInfo($order_id));
			
		//	var_dump($paypal_data);
			
			$result = $this->model_payment_pp_express->call($paypal_data);
			
			if($result['ACK'] == 'Success') {
				//handle order status
				switch($result['PAYMENTINFO_0_PAYMENTSTATUS']) {
					case 'Canceled_Reversal':
					$order_status_id = $this->config->get('pp_express_canceled_reversal_status_id');
					break;
					case 'Completed':
					$order_status_id = $this->config->get('pp_express_completed_status_id');
					break;
					case 'Denied':
					$order_status_id = $this->config->get('pp_express_denied_status_id');
					break;
					case 'Expired':
					$order_status_id = $this->config->get('pp_express_expired_status_id');
					break;
					case 'Failed':
					$order_status_id = $this->config->get('pp_express_failed_status_id');
					break;
					case 'Pending':
					$order_status_id = $this->config->get('pp_express_pending_status_id');
					break;
					case 'Processed':
					$order_status_id = $this->config->get('pp_express_processed_status_id');
					break;
					case 'Refunded':
					$order_status_id = $this->config->get('pp_express_refunded_status_id');
					break;
					case 'Reversed':
					$order_status_id = $this->config->get('pp_express_reversed_status_id');
					break;
					case 'Voided':
					$order_status_id = $this->config->get('pp_express_voided_status_id');
					break;
				}
				
				//$this->model_checkout_order->confirm($order_id, $order_status_id);
				if ($result['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed'){
					
					$sms_template = "Заказ # {ID}. Оплату в сумме {SUM}, получили, спасибо. Заказ выполняем";	
					
					$this->session->data['success'] = 'Оплата прошла успешно. Благодарим за сотрудничество.';
					
					//Действия с заказом после получения оплаты
					$this->model_checkout_order->update($order_id,
					$this->config->get('pp_express_completed_status_id'),
					'Оплата через PayPal Express',
					$notify);
					
					//транзакция
					$this->model_account_transaction->addTransaction(
					'PayPal Express: Оплата по заказу # '.$order_id, 
					$this->model_account_order->getOrderTotal($order_id),
					$this->model_account_order->getOrderTotalNational($order_id),
					$this->config->get('config_regional_currency'),
					$order_id,
					true,
					'paypal',
					'',
					''
					);
					
					$this->model_checkout_order->addOrderToQueue($order_id);
					
					//SMS
					$options = array(
					'to'       => $order_info['telephone'],						
					'from'     => $this->config->get('config_sms_sign'),						
					'message'  => str_replace(
					array(	'{ID}', 												
					'{SUM}', 
					), 
					array(
					$order_id,  
					$this->currency->format($this->model_account_order->getOrderTotalNational($order_id), $order_info['currency_code'], 1),
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
					$this->smsAdaptor->addOrderSmsHistory($order_id, $sms_data, $sms_status, $sms_id);		
					
					} else {
					
					$this->session->data['warning'] = 'Ошибка или задержка оплаты PayPal: ' . $result['PAYMENTINFO_0_PAYMENTSTATUS'] . ': ' . $result['L_ERRORCODE0'] . " " . $result['L_LONGMESSAGE0'];
					
					//Действия с заказом после получения оплаты
					$this->model_checkout_order->update($order_id,
					$this->config->get('pp_express_failed_status_id'),
					'Ошибка или задержка оплаты PayPal: ' . $result['PAYMENTINFO_0_PAYMENTSTATUS'] . ': ' . $result['L_ERRORCODE0'] . " " . $result['L_LONGMESSAGE0'],
					$notify);
					
				}
				
				//add order to paypal table
				$paypal_order_data = array(
				'order_id' => $order_id,
				'capture_status' => ($this->config->get('pp_express_method') == 'Sale' ? 'Complete' : 'NotComplete'),
				'currency_code' => $result['PAYMENTINFO_0_CURRENCYCODE'],
				'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
				'total' => $result['PAYMENTINFO_0_AMT'],
				);
				$paypal_order_id = $this->model_payment_pp_express->addOrder($paypal_order_data);
				
				//add transaction to paypal transaction table
				$paypal_transaction_data = array(
				'paypal_order_id' => $paypal_order_id,
				'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
				'parent_transaction_id' => '',
				'note' => '',
				'msgsubid' => '',
				'receipt_id' => (isset($result['PAYMENTINFO_0_RECEIPTID']) ? $result['PAYMENTINFO_0_RECEIPTID'] : ''),
				'payment_type' => $result['PAYMENTINFO_0_PAYMENTTYPE'],
				'payment_status' => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
				'pending_reason' => $result['PAYMENTINFO_0_PENDINGREASON'],
				'transaction_entity' => ($this->config->get('pp_express_method') == 'Sale' ? 'payment' : 'auth'),
				'amount' => $result['PAYMENTINFO_0_AMT'],
				'debug_data' => json_encode($result),
				);
				$this->model_payment_pp_express->addTransaction($paypal_transaction_data);						
				
				if(isset($result['REDIRECTREQUIRED']) && $result['REDIRECTREQUIRED'] == true) {
					$this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_complete-express-checkout&token='.$this->session->data['paypal']['token']);
					} else {
					$this->redirect($this->url->link('account/order'));
				}
				} else {
				
				if ($result['L_ERRORCODE0'] == '10486') {
					if (isset($this->session->data['paypal_redirect_count'])) {
						
						if ($this->session->data['paypal_redirect_count'] == 2) {
							$this->session->data['paypal_redirect_count'] = 0;
							$this->session->data['error'] = $this->language->get('error_too_many_failures');
							$this->redirect($this->url->link('account/order', '', 'SSL'));
							} else {
							$this->session->data['paypal_redirect_count']++;
						}
						} else {
						$this->session->data['paypal_redirect_count'] = 1;
					}
					
					if ($this->config->get('pp_express_test') == 1) {
						$this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
						} else {
						$this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
					}
				}
				
				$this->language->load('payment/pp_express');
				
				$this->data['breadcrumbs'] = array();
				
				$this->data['breadcrumbs'][] = array(
				'href' => $this->url->link('common/home'),
				'text' => $this->language->get('text_home'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'href' => $this->url->link('checkout/cart'),
				'text' => $this->language->get('text_cart'),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['heading_title'] = $this->language->get('error_heading_title');
				
				$this->data['text_error'] = '<div class="warning">'.$result['L_ERRORCODE0'].' : '.$result['L_LONGMESSAGE0'].'</div>';
				
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['continue'] = $this->url->link('account/order');
				
				unset($this->session->data['success']);
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
					} else {
					$this->template = 'default/template/error/not_found.tpl';
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
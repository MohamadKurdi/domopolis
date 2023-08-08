<?php

class ControllerPaymentShoputilsPsb extends Controller {
    private $order;
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
        $this->language->load('payment/shoputils_psb');
    }
	
	private function SetIsPayingPrepay($order_id, $paying_prepay = 0){
		$this->db->query("UPDATE `order` SET paying_prepay = '". (int)$paying_prepay ."' WHERE order_id = '" .(int)$order_id. "'");		
	}
	
	private function isPayingPrepay($order_id){
		$result = $this->db->non_cached_query("SELECT paying_prepay FROM `order` WHERE order_id = '" .(int)$order_id. "' LIMIT 1");
		
		return $result->row['paying_prepay'];		
	}

    protected function index() {
		
		 $langdata = $this->config->get('shoputils_psb_langdata');
		
		if (isset($this->session->data['order_id'])){
			$this->order_id = (int)$this->session->data['order_id'];	
		} else {
			$this->order_id = false;
		}
		
		if (!$this->order_id){
			return '';
		}
		
		$prepayment=false;
				
		$payment_method = isset($this->session->data['payment_method']['code']) ? $this->session->data['payment_method']['code'] : '';
		$payment_methods = explode(',', $this->config->get('config_confirmed_prepay_payment_ids'));
				
		if (isset($this->session->data['payment_method']) && in_array($payment_method, $payment_methods) && isset($this->session->data['prepay_sum']) && isset($this->session->data['prepay_perc']))
			{
					$prepayment = $this->session->data['prepay_sum'];
					$perc = $this->session->data['prepay_perc'];
					
					$value = $prepayment;
					$value_national = $this->currency->convert($value, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
				
					$this->SetIsPayingPrepay($this->order_id, 1);
				
				//paying prepayment
				 $this->_setData(
						array(
							'button_confirm',
							'instruction'  => nl2br($langdata[$this->config->get('config_language_id')]['instruction']),
							'continue'     => $this->url->link('checkout/success', '', 'SSL'),
							'pay_status'   => ((!$this->config->get('shoputils_psb_laterpay_mode')) || ($this->config->get('shoputils_psb_order_later_status_id') == $this->config->get('shoputils_psb_order_confirm_status_id'))),
							'action'       => $this->getUrl(),
							'parameters'   => $this->makeFormPrepay($this->order_id)
						)
					);
			} else {
				//paying full
				$this->SetIsPayingPrepay($this->order_id, 0);
				
				$this->_setData(
						array(
							'button_confirm',
							'instruction'  => nl2br($langdata[$this->config->get('config_language_id')]['instruction']),
							'continue'     => $this->url->link('checkout/success', '', 'SSL'),
							'pay_status'   => ((!$this->config->get('shoputils_psb_laterpay_mode')) || ($this->config->get('shoputils_psb_order_later_status_id') == $this->config->get('shoputils_psb_order_confirm_status_id'))),
							'action'       => $this->getUrl(),
							'parameters'   => $this->makeForm()
						)
					);
			}
			

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/shoputils_psb.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/shoputils_psb.tpl';
        } else {
            $this->template = 'default/template/payment/shoputils_psb.tpl';
        }

        $this->render();
    }
	
	 public function laterprepay() {
        if ($this->validateLaterprepay()) {
        $this->_setData(
            array(
                 'action'     => $this->getUrl(),
                 'parameters' => $this->makeFormPrepay($this->request->get['order_id'])
            )
        );
		
		$this->SetIsPayingPrepay($this->request->get['order_id'], 1);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/shoputils_psb_laterpay.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/shoputils_psb_laterpay.tpl';
        } else {
            $this->template = 'default/template/payment/shoputils_psb_laterpay.tpl';
        }

        $this->response->setOutput($this->render());
        } else {
            $this->logWrite('Fail Validate Laterpay:', self::$LOG_FULL);
            $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
            $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
            $this->redirect($this->url->link('account/order', '', 'SSL'));
        }
    }


    public function laterpay() {
        if ($this->validateLaterpay()) {
        $this->_setData(
            array(
                 'action'     => $this->getUrl(),
                 'parameters' => $this->makeForm($this->request->get['order_id'])
            )
        );
		
		$this->SetIsPayingPrepay($this->request->get['order_id'], 0);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/shoputils_psb_laterpay.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/shoputils_psb_laterpay.tpl';
        } else {
            $this->template = 'default/template/payment/shoputils_psb_laterpay.tpl';
        }

        $this->response->setOutput($this->render());
        } else {
            $this->logWrite('Fail Validate Laterpay:', self::$LOG_FULL);
            $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
            $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
            $this->redirect($this->url->link('account/order', '', 'SSL'));
        }
    }

    public function callback() {
        $this->logWrite('CallbackURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_SHORT);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_SHORT);

        //Когда покупатель возвращается после попытки оплаты - ПСБ всегда возвращает пустой post-запрос
        //Проверяем сессию и по результатам проверки либо посылаем покупателя на checkout/success, либо просто посылаем ;)
        if (!$this->request->post) {
            if ($this->validateSession()) {
                $this->redirect($this->url->link('account/order', '', 'SSL'));
            } else {
                $this->redirect($this->url->link('account/order', '', 'SSL'));
            }
        }
		
		//Это возврат
		if ($this->request->post && isset($this->request->post['TRTYPE']) && 
			($this->request->post['TRTYPE'] == 22 || $this->request->post['TRTYPE'] == 14 || strpos($this->request->post['TRTYPE'], 'psbank.ru'))){
			
			$this->logWrite('MAKING RETURN');
			
			$this->validateReturn();
			
		} else {

			if (!$this->validate()) {
				$this->fail();
				return;
			}

			$this->success();
			return;
		
		}
    }

    public function success() {
		$this->load->model('account/transaction');
		$this->load->model('account/order');
		$this->load->model('checkout/order');
		$this->load->model('payment/shoputils_psb');
		
		
		$sms_template = "Заказ # {ID}. Оплату в сумме {SUM}, получили, спасибо. Заказ выполняем";
			
		
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
                                                     'Внесение предоплаты через ПромСвязьБанк Эквайринг',
                                                      $notify);
					//добавляем транзакцию
					$this->model_account_transaction->addTransaction(
																'ПромСвязьБанк: Предоплата по заказу # '.$this->order['order_id'], 
																$this->model_account_order->getOrderPrepay($this->order['order_id']),
																$this->model_account_order->getOrderPrepayNational($this->order['order_id']),
																$this->config->get('config_regional_currency'),
																$this->order['order_id'],
																true,
																'psb_bank'
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
				
					//$log = new Log('order_send_sms.txt');
					//$log->write(serialize($sms_data) . ', returning: ' . $sms_id);				
					$this->smsAdaptor->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);

					$title = 'Предоплата по заказу # ' . $this->order['order_id'];
					$html =  'Заказ: # '.$this->order['order_id'] . 
									'<br />Сумма: ' . 
									$this->model_account_order->getOrderPrepayNational($this->order['order_id']) . ' ' . 
									$this->config->get('config_regional_currency') . 
									'<br />Фактически было запрошено: ' . $actual_amount . ' RUB'.
									'<br />Время: '.date("d:m:Y H:i:s") . 
									'<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('config_prepayment_paid_order_status_id'), $this->order['language_id']);
									
					$xlog = new Log('psb_alert.txt');
						$xlog->write($title . ' - '. $html);				

					$mail = new Mail($this->registry); 
					$mail->setTo($this->config->get('config_payment_mail_to'));	
					$mail->setSubject(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);						
											
					$mail->send();					

				} else {
					$this->model_checkout_order->update($this->order['order_id'],
                                                     $this->config->get('shoputils_psb_order_status_id'),
                                                     'Оплата через ПромСвязьБанк Эквайринг',
                                                      $notify);
					//добавляем транзакцию полной оплаты								  
					$this->model_account_transaction->addTransaction(
																'ПромСвязьБанк: Оплата по заказу # '.$this->order['order_id'], 
																$this->model_account_order->getOrderTotal($this->order['order_id']),
																$this->model_account_order->getOrderTotalNational($this->order['order_id']),
																$this->config->get('config_regional_currency'),
																$this->order['order_id'],
																true,
																'psb_bank'																
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
									'<br />Фактически было запрошено: ' . $actual_amount . ' RUB'.
									'<br />Время: '.date("d:m:Y H:i:s") . 
									'<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('shoputils_psb_order_status_id'), $this->order['language_id']);

						$xlog = new Log('psb_alert.txt');
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

    public function fail() {
        if (isset($this->order['order_status_id']) && $this->order['order_status_id']) {
              //Reverse $this->config->get('shoputils_psb_notify_customer_fail')
              $notify = !$this->config->get('shoputils_psb_notify_customer_fail');

              $this->model_checkout_order->update($this->order['order_id'],
                                                  $this->config->get('shoputils_psb_order_fail_status_id'),
                                                  'ПромСвязьБанк: оплата отклонена. Причины, пожалуйста, уточняйте у своего банка.',
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

    public function psb() {
        $oc = VERSION;
        if (file_exists(DIR_SYSTEM . 'config/svn/svn.ver')) {
          $oc .= '.r' . trim(file_get_contents(DIR_SYSTEM . 'config/svn/svn.ver'));
        }
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        echo sprintf('%s - %s v%s<br />', $oc, $this->shoputils, $this->config->get('shoputils_psb_version'));
        //info
        if ((isset($this->request->get['info'])) && (md5($this->request->get['info']) == 'caf9b6b99962bf5c2264824231d7a40c')) {
          $this->load->model('setting/setting');
          $psb = $this->model_setting_setting->getSetting('shoputils_psb');
          $disallowed = array(
              'shoputils_psb_terminal',
              'shoputils_psb_merchant_id',
              'shoputils_psb_merchant_key'
          );
          foreach ($psb as $key => $value) {
            if (!in_array($key, $disallowed)) {
              if (is_array($value)) {
              echo sprintf('%s:<br />', $key);
                foreach ($value as $k => $val) {
                  if (is_array($val)) {
                    echo sprintf('............%s:<br />', $k);
                    foreach ($val as $k2 => $v) {
                      if (is_array($v)) {
                        echo sprintf('........................%s:<br />', $k2);
                        foreach ($v as $k3 => $v2) {
                          echo sprintf('........................%s: %s<br /><br />', $k3, $v2);
                        }
                      } else{
                        echo sprintf('....................................%s: %s<br /><br />', $k2, $v);
                      }
                    }                
                  } else {
                    echo sprintf('............%s: %s<br /><br />', $k, $val);
                  }
                }
              } else {
                echo sprintf('%s: %s<br /><br />', $key, $value);
              }
            }
          }
        }
    }

    public function confirm() {
        if (!empty($this->session->data['order_id'])) {			
            $this->load->model('checkout/order');
			
			if ($this->isPayingPrepay($this->order['order_id'] == 1)) {
			
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_prepayment_paid_order_status_id'));
				$this->session->data['blyatskij_psb_ne_vozvrashaet_massiv_dannyh_pri_vozvrate_pokupatelya']['order_id'] = $this->session->data['order_id'];
			
				$this->SetIsPayingPrepay($this->order['order_id'], 0);
			
			} else {
				 $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('shoputils_psb_order_confirm_status_id'));
				$this->session->data['blyatskij_psb_ne_vozvrashaet_massiv_dannyh_pri_vozvrate_pokupatelya']['order_id'] = $this->session->data['order_id'];
				
			}
        }
    }
	
	protected function makeFormPrepay($order_id = false) {
        $this->load->model('checkout/order');
		$this->load->model('account/order');
        if (!$order_id ) {
          if (isset($this->session->data['order_id'])) {
            $order_id  = $this->session->data['order_id'];
          } else {
            $this->logWrite('Error: Unsupported Checkout Extension', self::$LOG_SHORT);
            die($this->language->get('error_fail_checkout_extension'));
          }
        }
        $order_info = $this->model_checkout_order->getOrder($order_id);

	/*	
        $found = false;
        foreach (self::$valid_currencies as $code) {
            if ($this->currency->has($code)) {
                $sCurrencyCode = $code;
                $found = true;
                break;
            }
        }
	
        if (!$found) {
            die(sprintf('Currency code (%s) not found', implode(', ', self::$valid_currencies)));
        }
	*/	
		//total in national currency
		//Если Рашка и рубли
		
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
		
	//	$sAmount = number_format($this->currency->convert($order_info['total'], $this->config->get('config_currency'), $sCurrencyCode), 2, '.', '');
        $sPaymentId = (strlen($order_info['order_id']) < 6 ? str_repeat('0', 6 - strlen($order_info['order_id'])) : '') . $order_info['order_id'];
		$sCurrencyCode = 'RUB';
		
        $timeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Dublin');
        $params = array(
            'AMOUNT'      => $sAmount,
            'CURRENCY'    => $sCurrencyCode,
            'ORDER'       => $sPaymentId,
            'MERCH_NAME'  => $this->config->get('config_name'),
            'MERCHANT'    => $this->getMerchant(),
            'TERMINAL'    => $this->getTerminal(),
            'EMAIL'       => $this->config->get('config_email'),
            'TRTYPE'      => 1,
            'TIMESTAMP'   => date("YmdHis", time()),
            'NONCE'       => strtoupper(substr(md5(rand(1,9999).microtime()), 0, 16)), //Строка из случайного hex-числа, длиной 16 символов 
            'BACKREF'     => $this->url->link('payment/shoputils_psb/callback', '', 'SSL')
       );
        date_default_timezone_set($timeZone);

        $params['P_SIGN'] = $this->calculateHash($params);
        $params['DESC']   = mb_substr('Предоплата заказа №'. $order_info['order_id'], 0, 50, 'utf-8');

        $this->logWrite('Make payment form: ', self::$LOG_FULL);
        $this->logWrite('  DATA: ' . var_export($params, true), self::$LOG_FULL);

        return $params;
    }

    protected function makeForm($order_id = false) {
        $this->load->model('checkout/order');
        if (!$order_id ) {
          if (isset($this->session->data['order_id'])) {
            $order_id  = $this->session->data['order_id'];
          } else {
            $this->logWrite('Error: Unsupported Checkout Extension', self::$LOG_SHORT);
            die($this->language->get('error_fail_checkout_extension'));
          }
        }
        $order_info = $this->model_checkout_order->getOrder($order_id);
	/*	
        $found = false;
        foreach (self::$valid_currencies as $code) {
            if ($this->currency->has($code)) {
                $sCurrencyCode = $code;
                $found = true;
                break;
            }
        }

        if (!$found) {
            die(sprintf('Currency code (%s) not found', implode(', ', self::$valid_currencies)));
        }
	*/	
		//total in national currency
		//Если Рашка и рубли
		
		if ($order_info['currency_code'] == 'RUB'){
			$sAmount = number_format($order_info['total_national'], 2, '.', '');			
		} else {
			$sAmount = number_format($this->currency->convert($order_info['total_national'], $order_info['currency_code'], 'RUB'), 2, '.', '');		
		}
		
	//	$sAmount = number_format($this->currency->convert($order_info['total'], $this->config->get('config_currency'), $sCurrencyCode), 2, '.', '');
        $sPaymentId = (strlen($order_info['order_id']) < 6 ? str_repeat('0', 6 - strlen($order_info['order_id'])) : '') . $order_info['order_id'];
		$sCurrencyCode = 'RUB';
		
        $timeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Dublin');
        $params = array(
            'AMOUNT'      => $sAmount,
            'CURRENCY'    => $sCurrencyCode,
            'ORDER'       => $sPaymentId,
            'MERCH_NAME'  => $this->config->get('config_name'),
            'MERCHANT'    => $this->getMerchant(),
            'TERMINAL'    => $this->getTerminal(),
            'EMAIL'       => $this->config->get('config_email'),
            'TRTYPE'      => 1,
            'TIMESTAMP'   => date("YmdHis", time()),
            'NONCE'       => strtoupper(substr(md5(rand(1,9999).microtime()), 0, 16)), //Строка из случайного hex-числа, длиной 16 символов 
            'BACKREF'     => $this->url->link('payment/shoputils_psb/callback', '', 'SSL')
       );
        date_default_timezone_set($timeZone);

        $params['P_SIGN'] = $this->calculateHash($params);
        $params['DESC']   = mb_substr('Оплата заказа №'. $order_info['order_id'], 0, 50, 'utf-8');

        $this->logWrite('Make payment form: ', self::$LOG_FULL);
        $this->logWrite('  DATA: ' . var_export($params, true), self::$LOG_FULL);

        return $params;
    }

    protected function validateLaterpay() {
        if ((!isset($this->request->get['order_id'])) || (!isset($this->request->get['order_tt'])) || (!isset($this->request->get['order_fl']))) {
          return false;
        } else {
          $this->load->model('checkout/order');
          $order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
          if ((!$order_info) || ($this->request->get['order_id'] != $order_info['order_id']) || ((int)$this->request->get['order_tt'] != (int)$order_info['total_national']) || ($this->request->get['order_fl'] != md5($order_info['firstname'] . $order_info['lastname']))) {
		//	var_dump($this->request->get['order_fl']);
            return false;
          }
        }
        return true;
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
		  
          if ((!$order_info) || ($this->request->get['order_id'] != $order_info['order_id']) || ((int)$this->request->get['order_tt'] != (int)$order_info['total_national']) || ($this->request->get['order_fl'] != md5($order_info['firstname'] . $order_info['lastname']))) {
            return false;
          }
        }
        return true;
    }

    protected function validateSession() {
        if (isset($this->session->data['blyatskij_psb_ne_vozvrashaet_massiv_dannyh_pri_vozvrate_pokupatelya'])) {
            $this->load->model('checkout/order');
            $success_order_info = $this->model_checkout_order->getOrder($this->session->data['blyatskij_psb_ne_vozvrashaet_massiv_dannyh_pri_vozvrate_pokupatelya']['order_id']);
            unset($this->session->data['blyatskij_psb_ne_vozvrashaet_massiv_dannyh_pri_vozvrate_pokupatelya']);
            if (!$success_order_info) {
                return false;
            }
            return $success_order_info['order_status_id'] == $this->config->get('shoputils_psb_order_status_id');
        } else {
            return false;
        }
    }
	
	protected function validateReturn($check_sign_hash = true) {
		$this->load->model('account/transaction');
		$this->load->model('account/order');
		$this->load->model('checkout/order');
		$this->load->model('payment/shoputils_psb');

        $order_id = isset($this->request->post['ORDER']) ? $this->request->post['ORDER'] : 0;
        $order = $this->model_checkout_order->getOrder( (int)$order_id );
		
		 if (!$order && isset($this->request->post['TERMINAL']) && $this->request->post['TERMINAL'] == $this->getTerminal()) {
            $this->sendForbidden(sprintf('Order ID: %s', $order_id ));
            return false;
        } else {
			
			if (isset($this->request->post['RESULT']) && ($this->request->post['RESULT'] != 0)){
				$this->sendForbidden('This is not a completed return');
				return false;
			}	
			
			$sms_template = "Заказ # {ID}. / Возврат оплаты  {SUM}.  Проводка транзакции банком до 7 дней";
			
			if ($order['currency_code'] == 'RUB') {
				$total_return = $this->request->post['AMOUNT'];
			} else {
				$total_return = $this->currency->convert($this->request->post['AMOUNT'], 'RUB', $order['currency_code']);
			}
			
			$total_eur_return = $this->currency->convert($this->request->post['AMOUNT'], 'RUB', $this->config->get('config_currency'));
			
			$this->model_account_transaction->addTransaction(
																'Возврат оплаты / # ' . $order['order_id'], 
																-1 * $total_eur_return,
																-1 * $total_return,
																$order['currency_code'],
																$order['order_id'],
																true,																
																'psb_bank'
															);
															
			$title = 'Возврат оплаты по заказу # ' . $order['order_id'];
			$html =   'Заказ: # '.$order['order_id'] . 
						'<br />Полная сумма заказа: ' . 									
						$this->model_account_order->getOrderTotalNational($order['order_id']) . ' ' . 
						$order['currency_code'] . 
						'<br />Было возвращено: ' . $this->request->post['AMOUNT'] . ' RUB'.
						'<br />Полная оплаченная сумма: ' . $this->request->post['ORG_AMOUNT'] . ' RUB'.
						'<br />Время: '.date("d:m:Y H:i:s");											
			
			
			$options = array(
					'to'       => $order['telephone'],						
					'from'     => $this->config->get('config_sms_sign'),						
					'message'  => str_replace(
										array(	'{ID}', 												
												'{SUM}', 
												), 
										array(
												$order['order_id'],  
												$this->currency->format($this->request->post['AMOUNT'], $this->order['currency_code'], 1),
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
						'order_status_id' => $order['order_status_id'],
						'sms' => $options['message']
					);
				
					//$log = new Log('order_send_sms.txt');
					//$log->write(serialize($sms_data) . ', returning: ' . $sms_id);				
					$this->smsAdaptor->addOrderSmsHistory($order['order_id'], $sms_data, $sms_status, $sms_id);	
					
					
					$mail = new Mail($this->registry); 					
					$mail->setFrom($this->config->get('config_payment_mail_from'));
					$mail->setSender($this->config->get('config_payment_mail_from'));
					$mail->setSubject(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);
						
					$mail->setTo($this->config->get('config_payment_mail_to'));						
					$mail->send();
						
			
		}	
		
		die();
		return false;
	}

    protected function validate($check_sign_hash = true) {
        $this->load->model('checkout/order');

        $order_id = isset($this->request->post['ORDER']) ? $this->request->post['ORDER'] : 0;
        $this->order = $this->model_checkout_order->getOrder($order_id );

        if (!$this->order) {
            $this->sendForbidden(sprintf('Order ID: %s', $order_id ));
            return false;
        }

        if ($check_sign_hash) {
          if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->sendForbidden($this->language->get('text_error_post'));
            return false;
          } else {
            $this->message = isset($this->request->post['RCTEXT']) ? $this->request->post['RCTEXT'] : '';
            			
            $allowed_params = array(
                'AMOUNT',
                'CURRENCY',
                'ORDER',
                'MERCH_NAME',
                'MERCHANT',
                'TERMINAL',
                'EMAIL',
                'TRTYPE',
                'TIMESTAMP',
                'NONCE',
                'BACKREF',
                'RESULT',
                'RC',
                'RCTEXT',
                'AUTHCODE',
                'RRN',
                'INT_REF'
            );
			
			foreach ($allowed_params as $key) {
				if (!isset($this->request->post[$key])){
					$this->sendForbidden($this->language->get('text_error_post'));
					return false;
				}
			}
            
            $params = array();
            foreach ($allowed_params as $key) {
                $params[$key] = isset($this->request->post[$key]) ? $this->request->post[$key] : '';
            }
            $signature = $this->calculateHash($params);

            if (strtoupper($this->request->post['P_SIGN']) != strtoupper($signature)) {
                $this->sendForbidden(sprintf('Wrong signature: %s', $signature));
                return false;
            }
          }
        }

        switch ($this->request->post['RESULT']) {
            case '0':
                return true;
            case '1':
                $this->sendForbidden($this->language->get('text_error_result1'));
                break;
            case '2':
                $this->sendForbidden($this->language->get('text_error_result2'));
                break;
            case '3':
                $this->sendForbidden($this->language->get('text_error_result3'));
                break;
            default:
                $this->sendForbidden($this->language->get('text_error_unknown_answer'));
        }

        return false;
    }

    protected function calculateHash($params) {
        $paramValues = '';

        foreach ($params as $value) {
            $value = (!empty($value) || is_numeric($value)) ? strlen($value ) . $value : '-';
            $paramValues .= $value;
        }

        return hash_hmac('sha1', $paramValues, pack('H*', $this->getKey()));
    }

    protected function sendForbidden($error) {
        $this->logWrite('ERROR: ' . $error, self::$LOG_SHORT);
    }

    protected function sendOk() {
        $this->logWrite('OK: ' . http_build_query($this->request->post, '', ','), self::$LOG_SHORT);
    }

    //type = 'admin' - mail send to admin; type = 'customer' - mail send to customer
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

    protected function _setData($values) {
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $this->data[$value] = $this->language->get($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    protected function logWrite($message, $type = 2) {
        switch ($this->config->get('shoputils_psb_log')) {
            case self::$LOG_OFF:
                return;
            case self::$LOG_SHORT:
                if ($type == self::$LOG_FULL) {
                    return;
                }
        }

        if (!$this->log) {
            $this->log = new Log($this->config->get('shoputils_psb_log_filename'));
        }
        $this->log->Write($message);
    }

    protected function getTerminal() {
        return $this->config->get('shoputils_psb_test_mode') ? $this->config->get('shoputils_psb_terminal_test'): $this->config->get('shoputils_psb_terminal');
    }

    protected function getMerchant() {
        return $this->config->get('shoputils_psb_test_mode') ? '790367686219999': $this->config->get('shoputils_psb_merchant_id');
    }

    protected function getKey() {
        return $this->config->get('shoputils_psb_test_mode') ? 'C50E41160302E0F5D6D59F1AA3925C45': $this->config->get('shoputils_psb_merchant_key');
    }

    protected function getUrl() {
        return $this->config->get('shoputils_psb_test_mode') ? 'http://193.200.10.117:8080/cgi-bin/cgi_link': 'https://3ds.payment.ru/cgi-bin/cgi_link';
    }
}
?>
<?php
    
    class ControllerPaymentWayforpay extends Controller    
    {
        private $order;
    
        public function index()
        {
            $w4p = new WayForPay();
            $key = $this->config->get('wayforpay_secretkey');
            $w4p->setSecretKey($key);
            
            $order_id = $this->session->data['order_id'];
            
            $this->load->model('checkout/order');
            $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
            
            $serviceUrl = $this->url->link('payment/wayforpay/callback'); //$this->config->get('wayforpay_serviceUrl');
            $returnUrl = $this->url->link('payment/wayforpay/response'); //$this->config->get('wayforpay_returnUrl');
            
            /*    $amount = $this->currency->format(
                $order['total'],
                $order['currency_code'],
                $order['currency_value'],
                false
            ); */
            
            $amount = number_format($order['total_national'], 2, '.', '');	
            
            $wayforpay_language = $this->config->get('wayforpay_language');
            if ($this->config->get('config_language') == 'uk'){
                $wayforpay_language = 'UA';
            }
            
            $fields = array(
            'orderReference'                => $order_id . WayForPay::ORDER_SEPARATOR . time(),
            'merchantAccount'               => $this->config->get('wayforpay_merchant'),
            'orderDate'                     => strtotime($order['date_added']),
            'merchantAuthType'              => 'simpleSignature',
            'merchantDomainName'            => $_SERVER['HTTP_HOST'],
            'merchantTransactionSecureType' => 'AUTO',
            'amount'                        => round($amount, 2),
            'currency'                      => $order['currency_code'],
            'serviceUrl'                    => $serviceUrl,
            'returnUrl'                     => $returnUrl,
            'language'                      => $wayforpay_language
            );
            
            $productNames = array();
            $productQty = array();
            $productPrices = array();
            $this->load->model('account/order');
            $products = $this->model_account_order->getOrderProducts($order_id);
            foreach ($products as $product) {
                $productNames[] = str_replace(array("'", '"', '&#39;', '&'), '', htmlspecialchars_decode($product['name']));
                $productPrices[] = round($product['price_national'], 2);
                $productQty[] = intval($product['quantity']);
            }
            
            $fields['productName'] = $productNames;
            $fields['productPrice'] = $productPrices;
            $fields['productCount'] = $productQty;
            
            /**
                * Check phone
            */
            $phone = str_replace(array('+', ' ', '(', ')', '-'), array('', '', '', ''), $order['telephone']);
            if (strlen($phone) == 10) {
                $phone = '38' . $phone;
                } elseif (strlen($phone) == 11) {
                $phone = '3' . $phone;
            }
            
            $fields['clientFirstName']  = $order['firstname'];
            $fields['clientLastName']   = $order['lastname'];
            $fields['clientEmail']      = $order['email'];
            $fields['clientPhone']      = $phone;
            $fields['clientCity']       = $order['shipping_city'];
            $fields['clientAddress']    = $order['shipping_address_1'] . ' ' . $order['shipping_address_2'];
            $fields['clientCountry']    = $order['payment_iso_code_3'];
            
            $fields['merchantSignature'] = $w4p->getRequestSignature($fields);
            
            $this->data['fields'] = $fields;
            $this->data['action'] = WayForPay::URL;
            $this->data['button_confirm'] = $this->language->get('button_confirm');
            $this->data['continue'] = $this->url->link('checkout/success');
            
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wayforpay.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/wayforpay.tpl';
                } else {
                $this->template = 'default/template/payment/wayforpay.tpl';
            }
            
            $this->render();
            
        }
        
        public function confirm()
        {
            $this->load->model('checkout/order');
            
            $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
            if (!$order_info) return;
            
            $order_id = $this->session->data['order_id'];
            
            if ($order_info['order_status_id'] == 0) {
                $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), 'Начат процесс оплаты WayForPay');
                
                return;
            }
            
            if ($order_info['order_status_id'] != $this->config->get('config_order_status_id')) {
                $this->model_checkout_order->update($order_id, $this->config->get('config_order_status_id'), 'Начат процесс оплаты WayForPay', true);
            }
        }
        
        private function fail(){
            
            $this->load->model('checkout/order');
            
            $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
            if (!$order_info) return;
            
            $order_id = $this->session->data['order_id'];
            
            if ($order_info['order_status_id'] == 0) {
                $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), 'Неудачная оплата WayForPay');
                
                return;
            }
            
            if ($order_info['order_status_id'] != $this->config->get('config_order_status_id')) {
                $this->model_checkout_order->update($order_id, $this->config->get('config_order_status_id'), 'Неудачная оплата WayForPay', true);
            }
        }
        
        public function responsetoaccount()
        {
            
            $w4p = new WayForPay();
            $key = $this->config->get('wayforpay_secretkey');
            $w4p->setSecretKey($key);
            
            $paymentInfo = $w4p->isPaymentValid($this->request->post);
            
            
            if (!empty($this->request->post) && !empty($this->request->post['email'])){
                $this->customer->login($this->request->post['email'], '', true);                              
            }
            
            if ($paymentInfo === true) {
                list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $this->request->post['orderReference']);                              
                
                $this->load->model('checkout/order');
                
                /**
                    * check current order status if no eq wayforpay_order_status_id then confirm
                */
                $orderInfo = $this->model_checkout_order->getOrder($order_id);                                
                
                if (
                $orderInfo &&
                $orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')
                ) {
                    //nothing
                    } elseif ($orderInfo) {
                    
                    $this->order = $orderInfo;
                    
                //  $this->model_checkout_order->update($order_id, $this->config->get('wayforpay_order_status_id'), 'Оплата через WayForPay');                   
                    $this->success($this->request->post);
                }
                
                $this->redirect($this->url->link('account/order'));
                
                } else {
                
                $this->fail();
                $this->redirect($this->url->link('account/order'));
            }
        }
        
        public function response()
        {
            
            $w4p = new WayForPay();
            $key = $this->config->get('wayforpay_secretkey');
            $w4p->setSecretKey($key);
            
            $paymentInfo = $w4p->isPaymentValid($this->request->post);
            
            if ($paymentInfo === true) {
                list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $this->request->post['orderReference']);
                
                $message = '';
                
                $this->load->model('checkout/order');
                
                /**
                    * check current order status if no eq wayforpay_order_status_id then confirm
                */
                $orderInfo = $this->model_checkout_order->getOrder($order_id);
                if (
                $orderInfo &&
                $orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')
                ) {
                    //nothing
                    } else {
                    
                    $this->order = $orderInfo;
                    
                    $this->model_checkout_order->confirm($order_id, $this->config->get('wayforpay_order_status_id'), 'Оплата через WayForPay');
                    $this->session->data['success'] = 'Оплата прошла успешно. Благодарим за сотрудничество.';
                    $this->success($this->request->post);
                }
                
                $this->redirect($this->url->link('checkout/success'));
                
                } else {
                
                $this->fail();
                $this->redirect($this->url->link('checkout/success'));
            }
        }
        
        public function callback()
        {
            
            $data = json_decode(file_get_contents("php://input"), true);
            
            $w4p = new WayForPay();
            $key = $this->config->get('wayforpay_secretkey');
            $w4p->setSecretKey($key);
            
            $paymentInfo = $w4p->isPaymentValid($data);
            
            if ($paymentInfo === true) {
                list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $data['orderReference']);
                
                $message = '';
                
                $this->load->model('checkout/order');
                
                /**
                    * check current order status if no eq wayforpay_order_status_id then confirm
                */
                $orderInfo = $this->model_checkout_order->getOrder($order_id);
                if (
                $orderInfo &&
                $orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')
                ) {
                    } elseif ($orderInfo) {
                    
                    $this->model_checkout_order->confirm($order_id, $this->config->get('wayforpay_order_status_id'), 'Оплата через WayForPay');
                    $this->model_checkout_order->update($order_id, $this->config->get('wayforpay_order_status_id'), 'Оплата через WayForPay');
                    
                    $this->order = $orderInfo;
                    
                    $this->session->data['success'] = 'Оплата прошла успешно. Благодарим за сотрудничество.';
                    $this->success($data);
                    
                }
                
                
                echo $w4p->getAnswerToGateWay($data);
                } else {
                echo $paymentInfo;
            }
            exit();
        }
        
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
                        
            $this->model_checkout_order->addOrderToQueue($this->order['order_id']);
            
            if (!isset($this->session->data['order_id'])) {
                $this->session->data['order_id'] = $this->order['order_id'];
            }
            
            $this->model_checkout_order->update($this->order['order_id'], $this->config->get('wayforpay_order_status_id'), 'Оплата через WayForPay', true);
								  
            $this->model_account_transaction->addTransaction(
            'WayForPay: Оплата по заказу # '.$this->order['order_id'], 
            $this->model_account_order->getOrderTotal($this->order['order_id']),
            $this->model_account_order->getOrderTotalNational($this->order['order_id']),
            $this->config->get('config_regional_currency'),
            $this->order['order_id'],
            true,
            'wayforpay',
            '',
            ''
            );
            
            //SMS
            if ($this->config->get('config_sms_payment_recieved_enabled')){
                $sms_template = $this->config->get('config_sms_payment_recieved');            

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
                    'order_status_id' => $this->config->get('wayforpay_order_status_id'),
                    'sms' => $options['message']
                );
                
                $this->model_checkout_order->addOrderSmsHistory($this->order['order_id'], $sms_data, $sms_status, $sms_id);	
            }
            
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
			'<br />Фактически было получено: ' . $data['amount'] . ' UAH'.
			'<br />Время: '.date("d:m:Y H:i:s") . 
			'<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('liqpay_order_status_id'), $this->order['language_id']);
			
			$xlog = new Log('wayforpay_mails.txt');
			$xlog->write($title . ' - '. $html);
            
        }
    }
    
    class WayForPay
    {
        const ORDER_APPROVED = 'Approved';
        const ORDER_HOLD_APPROVED = 'WaitingAuthComplete';
        
        const ORDER_SEPARATOR = '#';
        
        const SIGNATURE_SEPARATOR = ';';
        
        const URL = "https://secure.wayforpay.com/pay/";
        
        protected $secret_key = '';
        protected $keysForResponseSignature = array(
        'merchantAccount',
        'orderReference',
        'amount',
        'currency',
        'authCode',
        'cardPan',
        'transactionStatus',
        'reasonCode'
        );
        
        /** @var array */
        protected $keysForSignature = array(
        'merchantAccount',
        'merchantDomainName',
        'orderReference',
        'orderDate',
        'amount',
        'currency',
        'productName',
        'productCount',
        'productPrice'
        );
        
        
        /**
            * @param $option
            * @param $keys
            *
            * @return string
        */
        public function getSignature($option, $keys)
        {
            $hash = array();
            foreach ($keys as $dataKey) {
                if (!isset($option[$dataKey])) {
                    $option[$dataKey] = '';
                }
                if (is_array($option[$dataKey])) {
                    foreach ($option[$dataKey] as $v) {
                        $hash[] = $v;
                    }
                    } else {
                    $hash [] = $option[$dataKey];
                }
            }
            
            $hash = implode(self::SIGNATURE_SEPARATOR, $hash);
            
            return hash_hmac('md5', $hash, $this->getSecretKey());
        }
        
        
        /**
            * @param $options
            *
            * @return string
        */
        public function getRequestSignature($options)
        {
            return $this->getSignature($options, $this->keysForSignature);
        }
        
        /**
            * @param $options
            *
            * @return string
        */
        public function getResponseSignature($options)
        {
            return $this->getSignature($options, $this->keysForResponseSignature);
        }
        
        
        /**
            * @param array $data
            *
            * @return string
        */
        public function getAnswerToGateWay($data)
        {
            $time = time();
            $responseToGateway = array(
            'orderReference' => $data['orderReference'],
            'status'         => 'accept',
            'time'           => $time
            );
            $sign = array();
            foreach ($responseToGateway as $dataKey => $dataValue) {
                $sign [] = $dataValue;
            }
            $sign = implode(self::SIGNATURE_SEPARATOR, $sign);
            $sign = hash_hmac('md5', $sign, $this->getSecretKey());
            $responseToGateway['signature'] = $sign;
            
            return json_encode($responseToGateway);
        }
        
        /**
            * @param $response
            *
            * @return bool|string
        */
        public function isPaymentValid($response)
        {
            
            if (!isset($response['merchantSignature']) && isset($response['reason'])) {
                return $response['reason'];
            }
            $sign = $this->getResponseSignature($response);
            if (
            isset($response['merchantSignature']) &&
            $sign != $response['merchantSignature']
            ) {
                return 'An error has occurred during payment';
            }
            
            if (
            $response['transactionStatus'] == self::ORDER_APPROVED ||
            $response['transactionStatus'] == self::ORDER_HOLD_APPROVED
            ) {
                return true;
            }
            
            if ($response['transactionStatus'] == 'InProcessing') {
                return 'Transaction in processing';
            }
            
            return false;
        }
        
        public function setSecretKey($key)
        {
            $this->secret_key = $key;
        }
        
        public function getSecretKey()
        {
            return $this->secret_key;
        }
    }

<?
	
	class ControllerPaymentWayForPayLaterpay extends Controller
	{
		
		protected function index(){
		}
		
		public function laterpay(){			
			if (!$this->validateLaterpay()){
				$this->redirect('account/order');
			}			
			
			$this->load->model('checkout/order');
			$this->load->model('account/order');            
			
			$order_id    = (int)$this->request->get['order_id'];
			$order       = $this->model_checkout_order->getOrder($order_id);
			
			$w4p = new WayForPay();
            $key = $this->config->get('wayforpay_secretkey');
            $w4p->setSecretKey($key);
			
			$serviceUrl = $this->url->link('payment/wayforpay/callback');
            $returnUrl  = $this->url->link('payment/wayforpay/responsetoaccount');
            
            $amount = number_format($order['total_national'], 2, '.', '');	
            
            $wayforpay_language = $this->config->get('wayforpay_language');
            if ($this->config->get('config_language') == 'uk'){
                $wayforpay_language = 'UA';
            }
            
            $wayforpay_id = $order_id . WayForPay::ORDER_SEPARATOR . time();
            $this->insertOrder([
                'order_id'      => $order_id,
                'wayforpay_id'  => $wayforpay_id
            ]);

            $fields = [
                'orderReference'                => $wayforpay_id,
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
            ];
            
            $productNames = [];
            $productQty = [];
            $productPrices = [];

            $products = $this->model_account_order->getOrderProducts($order_id);
            foreach ($products as $product) {
                $productNames[] = str_replace(array("'", '"', '&#39;', '&'), '', htmlspecialchars_decode($product['name']));
                $productPrices[] = round($product['price_national'], 2);
                $productQty[] = intval($product['quantity']);
            }
            
            $fields['productName']  = $productNames;
            $fields['productPrice'] = $productPrices;
            $fields['productCount'] = $productQty;
            
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
            
            $this->data['fields']           = $fields;
            $this->data['action']           = WayForPay::URL;
            $this->data['button_confirm']   = $this->language->get('button_confirm');
            $this->data['continue']         = $this->url->link('checkout/success');
			
			
			$this->template = 'payment/wayforpay_laterpay.tpl';
			
			$this->response->setOutput($this->render());
		}

        public function insertOrder($data){
            $this->db->query("INSERT INTO wayforpay_orders SET 
                order_id        = '" . (int)$data['order_id'] . "',
                wayforpay_id    = '" . $this->db->escape($data['wayforpay_id']) . "',
                status          = '',
                callback        = '',
                full_info       = ''");
        }

        public function updateOrder($wayforpay_id, $data){
            foreach ($data as $key => $value){
                $this->db->query("UPDATE wayforpay_orders SET 
                    `" . $this->db->escape($key) . "` = '" . $this->db->escape($value) . "'
                    WHERE wayforpay_id    = '" . $this->db->escape($wayforpay_id) . "'");
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
	
	 class WayForPay
    {
        const ORDER_APPROVED = 'Approved';
        const ORDER_HOLD_APPROVED = 'WaitingAuthComplete';
        
        const ORDER_SEPARATOR = '#';
        
        const SIGNATURE_SEPARATOR = ';';
        
        const URL = "https://secure.wayforpay.com/pay/";
        
        protected $secret_key = '';
        protected $keysForResponseSignature = [
            'merchantAccount',
            'orderReference',
            'amount',
            'currency',
            'authCode',
            'cardPan',
            'transactionStatus',
            'reasonCode'
        ];
        
        /** @var array */
        protected $keysForSignature = [
            'merchantAccount',
            'merchantDomainName',
            'orderReference',
            'orderDate',
            'amount',
            'currency',
            'productName',
            'productCount',
            'productPrice'
        ];
        
        
        /**
            * @param $option
            * @param $keys
            *
            * @return string
        */
        public function getSignature($option, $keys)
        {
            $hash = [];
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
            $responseToGateway = [
                'orderReference' => $data['orderReference'],
                'status'         => 'accept',
                'time'           => $time
            ];

            $sign = [];
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
            
            if (isset($response['merchantSignature']) && $sign != $response['merchantSignature']) {
                return 'An error has occurred during payment';
            }
            
            if ($response['transactionStatus'] == self::ORDER_APPROVED || $response['transactionStatus'] == self::ORDER_HOLD_APPROVED) {
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
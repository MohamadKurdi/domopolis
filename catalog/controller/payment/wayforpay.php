<?php

class ControllerPaymentWayforpay extends Controller    
{
    private $order;
    
    public function index(){
        $this->load->model('checkout/order');
        $this->load->model('account/order');

        $w4p = new WayForPay();
        $key = $this->config->get('wayforpay_secretkey');
        $w4p->setSecretKey($key);

        $order_id = $this->session->data['order_id'];        
        $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $serviceUrl = $this->url->link('payment/wayforpay/callback'); 
        $returnUrl  = $this->url->link('payment/wayforpay/response');

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

        $productNames   = [];
        $productQty     = [];
        $productPrices  = [];

        $products = $this->model_account_order->getOrderProducts($order_id);
        foreach ($products as $product) {
            $productNames[]     = str_replace(array("'", '"', '&#39;', '&'), '', htmlspecialchars_decode($product['name']));
            $productPrices[]    = round($product['price_national'], 2);
            $productQty[]       = intval($product['quantity']);
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

        $this->template = 'payment/wayforpay';

        $this->render();
    }

    public function insertOrder($data){
        $this->db->ncquery("INSERT INTO wayforpay_orders SET 
            order_id        = '" . (int)$data['order_id'] . "',
            wayforpay_id    = '" . $this->db->escape($data['wayforpay_id']) . "',
            status          = '',
            callback        = '',
            full_info       = ''");
    }

    public function updateOrder($wayforpay_id, $data){
        foreach ($data as $key => $value){
            $sql = "UPDATE wayforpay_orders SET `" . $this->db->escape($key) . "` = '" . $this->db->escape($value) . "' WHERE wayforpay_id    = '" . $this->db->escape($wayforpay_id) . "'";

            $this->db->ncquery($sql);
        }                    
    }

    public function confirm(){
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        if (!$order_info) return;

        $order_id = $this->session->data['order_id'];

        if ($order_info['order_status_id'] == 0) {
            $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), 'Розпочато процес оплати WayForPay');

            return;
        }

        if ($order_info['order_status_id'] != $this->config->get('config_order_status_id')) {
            $this->model_checkout_order->update($order_id, $this->config->get('config_order_status_id'), 'Розпочато процес оплати WayForPay', true);
        }
    }

    private function fail(){            
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        if (!$order_info) return;

        $order_id = $this->session->data['order_id'];

        if ($order_info['order_status_id'] == 0) {
            $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), 'Невдала оплата WayForPay');

            return;
        }

        if ($order_info['order_status_id'] != $this->config->get('config_order_status_id')) {
            $this->model_checkout_order->update($order_id, $this->config->get('config_order_status_id'), 'Невдала оплата WayForPay', true);
        }
    }

    public function responsetoaccount(){
        $this->load->model('checkout/order');

        $w4p = new WayForPay();
        $key = $this->config->get('wayforpay_secretkey');
        $w4p->setSecretKey($key);

        $paymentInfo = $w4p->isPaymentValid($this->request->post);

        if (!empty($this->request->post) && !empty($this->request->post['email'])){ $this->customer->login($this->request->post['email'], '', true); }

        if ($paymentInfo === true) {
            list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $this->request->post['orderReference']);                              
            $orderInfo = $this->model_checkout_order->getOrder($order_id); 

            $this->updateOrder($data['orderReference'], ['callback' => json_encode($data), 'status' => $this->request->post['transactionStatus']]);                               

            if ($orderInfo && $orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')) {} elseif ($orderInfo) {                    
                $this->order = $orderInfo;                 
                $this->success($this->request->post);
            }

            $this->redirect($this->url->link('account/order'));

        } else {
            $this->fail();
            $this->redirect($this->url->link('account/order'));
        }
    }

    public function response(){
        $this->load->model('checkout/order');

        $w4p = new WayForPay();
        $key = $this->config->get('wayforpay_secretkey');
        $w4p->setSecretKey($key);

        $paymentInfo = $w4p->isPaymentValid($this->request->post);

        if ($paymentInfo === true) {
            list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $this->request->post['orderReference']);

            $this->updateOrder($data['orderReference'], ['callback' => json_encode($this->request->post), 'status' => $this->request->post['transactionStatus']]);
          
            $orderInfo = $this->model_checkout_order->getOrder($order_id);
            if ($orderInfo && $orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')) {} else {
                $this->order = $orderInfo;

                $this->model_checkout_order->confirm($order_id, $this->config->get('wayforpay_order_status_id'), 'WayForPay: Оплата пройшла успішно.');
                $this->session->data['success'] = 'Оплата пройшла успішно. Дякуємо за співпрацю.';
                $this->success($this->request->post);
            }

            $this->redirect($this->url->link('checkout/success'));

        } else {

            $this->fail();
            $this->redirect($this->url->link('checkout/success'));
        }
    }

    public function callback(){
        $this->load->model('checkout/order');

        $data = json_decode(file_get_contents("php://input"), true);

        $w4p = new WayForPay();
        $key = $this->config->get('wayforpay_secretkey');
        $w4p->setSecretKey($key);

        $paymentInfo = $w4p->isPaymentValid($data);

        if ($paymentInfo === true) {
            list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $data['orderReference']);

            $this->updateOrder($data['orderReference'], ['callback' => json_encode($data), 'status' => $data['transactionStatus']]);

            $orderInfo = $this->model_checkout_order->getOrder($order_id);
            if ($orderInfo && $orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')) {
            } elseif ($orderInfo) {

                $this->model_checkout_order->confirm($order_id, $this->config->get('wayforpay_order_status_id'), 'WayForPay: Оплата пройшла успішно.');
                $this->model_checkout_order->update($order_id, $this->config->get('wayforpay_order_status_id'), 'WayForPay: Оплата пройшла успішно.');

                $this->order = $orderInfo;

                $this->session->data['success'] = 'Оплата пройшла успішно. Дякуємо за співпрацю.';
                $this->success($data);

            }

            echo $w4p->getAnswerToGateWay($data);
        } else {
            echo $paymentInfo;
        }
        exit();
    }

    public function getRealOrderID($order_id){
        $real_order_id = explode('#', $order_id);
        return $real_order_id[0];
    }

    public function success($data = []){
        $this->load->model('account/transaction');
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        $this->load->model('payment/shoputils_psb');                            

        $this->model_checkout_order->addOrderToQueue($this->order['order_id']);

        if (!isset($this->session->data['order_id'])) {
            $this->session->data['order_id'] = $this->order['order_id'];
        }

        $this->model_checkout_order->update($this->order['order_id'], $this->config->get('wayforpay_order_status_id'), 'WayForPay: Оплата пройшла успішно.', true);

        $this->model_account_transaction->addTransaction(
            'WayForPay: Оплата по замовленню # '.$this->order['order_id'], 
            $this->model_account_order->getOrderTotal($this->order['order_id']),
            $this->model_account_order->getOrderTotalNational($this->order['order_id']),
            $this->config->get('config_regional_currency'),
            $this->order['order_id'],
            true,
            'wayforpay',
            '',
            ''
        );

        $this->smsAdaptor->sendPayment($this->order, ['amount' => $data['amount'], 'order_status_id' => $this->config->get('wayforpay_order_status_id')]);
        $this->Fiscalisation->setOrderPaidBy($this->order['order_id'], 'wayforpay');

        if ($this->order['currency_code'] == 'UAH'){
            $actual_amount = number_format($this->model_account_order->getOrderTotalNational($this->order['order_id']), 2, '.', '');			
        } else {
            $actual_amount = number_format($this->currency->convert($this->model_account_order->getOrderTotalNational($this->order['order_id']), $this->order['currency_code'], 'RUB'), 2, '.', '');		
        }

        $title = 'Полная оплата по замовленню # ' . $this->order['order_id'];
        $html =     'Заказ: # '.$this->order['order_id'] . 
        $html .=    '<br />Сумма: ';
        $html .=    $this->model_account_order->getOrderTotalNational($this->order['order_id']) . ' ';
        $html .=    $this->config->get('config_regional_currency');
        $html .=    '<br />Фактически было запрошено: ' . $actual_amount .' '. $this->config->get('config_regional_currency');
        $html .=    '<br />Фактически было получено: ' . $data['amount'] . ' UAH';
        $html .=    '<br />Время: '.date("d:m:Y H:i:s");
        $html .=    '<br />Новый статус: ' . $this->model_payment_shoputils_psb->getOrderStatusById($this->config->get('wayforpay_order_status_id'), $this->order['language_id']);

        $xlog = new Log('wayforpay_mails.txt');
        $xlog->write($title . ' - '. $html);

    }

    public function updatedata(){
        if (php_sapi_name() != 'cli'){
            die();
        }

        if (!$this->config->get('wayforpay_status')){
            echoLine('[ControllerPaymentWayforpay::updatedata] Payment method WayForPay is disabled in admin!', 'e');
            die();
        }

        $w4p = new WayForPay();
        $key = $this->config->get('wayforpay_secretkey');
        $w4p->setSecretKey($key);


        $orders = $this->Fiscalisation->getOrdersPaidByNonFiscalised('wayforpay');

        foreach ($orders as $order){
            echoLine('[ControllerPaymentWayforpay::updatedata] Working with order: ' . $order['order_id'], 'i');

            $query = $this->db->ncquery("SELECT * FROM wayforpay_orders WHERE order_id = '" . (int)$order['order_id'] . "' AND status = '" . $this->db->escape(WayForPay::ORDER_APPROVED) . "' LIMIT 1");

            if ($query->num_rows){
                echoLine('[ControllerPaymentWayforpay::updatedata] Found approved transactions!','s');
                echoLine('[ControllerPaymentWayforpay::updatedata] Working with orderReference: ' . $query->row['wayforpay_id'], 'i');
                    try{
                        $request_data = [
                            'transactionType'   => 'CHECK_STATUS',
                            'merchantAccount'   => $this->config->get('wayforpay_merchant'),
                            'orderReference'    => $query->row['wayforpay_id'],
                            'merchantSignature' => $w4p->getRequestCheckStatusSignature(['merchantAccount' => $this->config->get('wayforpay_merchant'), 'orderReference' => $query->row['wayforpay_id']]),
                            'apiVersion'        => 2
                        ];

                        $httpClient     = new \GuzzleHttp\Client();
                        $httpResponse   = $httpClient->request('POST', WayForPay::API_URL, ['body' => json_encode($request_data)]); 
                        $httpResponseBodyContent        = $httpResponse->getBody()->getContents();
                        $httpResponseBodyContentDecoded = json_decode($httpResponseBodyContent, true); 


                        if ($httpResponseBodyContentDecoded){
                         //  echoLine('[ControllerPaymentWayforpay::updatedata] Got some data: ' . $httpResponseBodyContent,'s');
                           $this->updateOrder($query->row['wayforpay_id'], ['full_info' => $httpResponseBodyContent]);

                           if (!empty($httpResponseBodyContentDecoded['prroLink'])){
                                $receipt_id = str_replace(['https://api.cashdesk.com.ua/check/', '/html'], '', $httpResponseBodyContentDecoded['prroLink']);

                                if (!$this->Fiscalisation->checkIfReceiptAlreadyExits($receipt_id)){
                                    echoLine('[ControllerPaymentWayforpay::updatedata] Order has no fiscal code, adding: ' . $receipt_id, 's');

                                    $this->Fiscalisation->addReceipt([
                                        'order_id'              => $order['order_id'],
                                        'receipt_id'            => $receipt_id,
                                        'serial'                => $order['order_id'],
                                        'status'                => 'DONE',
                                        'fiscal_code'           => $httpResponseBodyContentDecoded['prroNumber'],
                                        'fiscal_date'           => date('Y-m-d'),
                                        'is_created_offline'    => false,
                                        'is_sent_dps'           => true,
                                        'sent_dps_at'           => date('Y-m-d'),
                                        'type'                  => 'SELL',
                                        'api'                   => 'cashdesk',
                                        'all_json_data'         => ['prroLink' => $httpResponseBodyContentDecoded['prroLink'], 'prroNumber' => $httpResponseBodyContentDecoded['prroNumber']]
                                    ]);

                                } else {
                                    echoLine('[ControllerPaymentWayforpay::updatedata] Order has fiscal code already: ' . $receipt_id, 'e');
                                }

                            } else {
                                echoLine('[ControllerPaymentWayforpay::updatedata] No prroLink in answer','e');
                            }
                        } else {
                            echoLine('[ControllerPaymentWayforpay::updatedata] Bad Data!' . $httpResponseBodyContent,'e');
                        }



                } catch (\GuzzleHttp\Exception\ClientException $e){
                    echoLine('[ControllerPaymentWayforpay::updatedata] ClientException: ' . $e->getMessage(), 'e');

                } catch (\GuzzleHttp\Exception\ServerException $e) {
                    echoLine('[ControllerPaymentWayforpay::updatedata] ServerException: ' . $e->getMessage(), 'e');
                }

        } else {
            echoLine('[ControllerPaymentWayforpay::updatedata] Did not found approved transactions!','e');
        }         
    }
}
}
    
class WayForPay
{
    const ORDER_APPROVED        = 'Approved';
    const ORDER_HOLD_APPROVED   = 'WaitingAuthComplete';
    const ORDER_SEPARATOR       = '#';
    const SIGNATURE_SEPARATOR   = ';';
    const URL                   = "https://secure.wayforpay.com/pay/";
    const API_URL               = "https://api.wayforpay.com/api";


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

    protected $keysForCheckStatusSignature = [
        'merchantAccount',
        'orderReference'
    ];


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

    public function getRequestSignature($options)
    {
        return $this->getSignature($options, $this->keysForSignature);
    }

    public function getRequestCheckStatusSignature($options)
    {
        return $this->getSignature($options, $this->keysForCheckStatusSignature);
    }

    public function getResponseSignature($options)
    {
        return $this->getSignature($options, $this->keysForResponseSignature);
    }

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

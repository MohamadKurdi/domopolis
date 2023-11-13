<?php
class ControllerPaymentMono extends Controller
{
    private $order = null;

    const CURRENCY_CODE = ['UAH','EUR','USD'];

    public function index($order_id = false, $redirect = false){
        $monolog = new Log('monopay.txt');

        $data = $this->language->load('payment/mono');
        $this->load->model('checkout/order');
        $this->load->model('payment/mono');

        if ($order_id){
             $orderID = $order_id;
             $this->session->data['order_id'] = $order_id;
        } else {
             $orderID = $this->session->data['order_id'];
        }
       
        $orderInfo = $this->model_checkout_order->getOrder($orderID);

        $currencyCode = $this->session->data['currency'];

        $currencyDecode = 980;

        if($currencyCode == 'UAH'){
            $currencyDecode = 980;
        }

        if($currencyCode == 'USD'){
            $currencyDecode = 840;
        }

        if($currencyCode == 'EUR'){
            $currencyDecode = 978;
        }
        
        try {
            if($orderInfo['currency_code'] !== self::CURRENCY_CODE[0] && $orderInfo['currency_code'] !== self::CURRENCY_CODE[1] && $orderInfo['currency_code'] !== self::CURRENCY_CODE[2]) {
                throw new \Exception($this->language->get('text_currency_error'));
            }

            $randKey = generateRandomString();
            $Params = [
                'order_id'              => $orderID,
                'merchant_id'           => $this->config->get('mono_merchant'),
                'amount'                => number_format($orderInfo['total_national'], 2, '.', '') * 100,
                'currency'              => $currencyCode,
                'response_url'          => $this->url->link('payment/mono/response', '', true),
                'server_callback_url'   => $this->url->link('payment/mono/callback', 'key=' . $randKey, true),
                'lang'                  => substr($this->session->data['language'], 0, 2),
                'randKey'               => $randKey,
                'InvoiceId'             => '',
            ];

            $this->data['checkout_url'] = $this->model_payment_mono->getCheckoutUrl($Params);

            if ($redirect){
                $this->redirect($this->data['checkout_url']);
            }

            $monolog->write('Started checkout: ' . $orderID);

        } catch (Exception $e){
            $this->data['error_message'] = $this->language->get('text_general_error');
            $monolog->write('[ControllerPaymentMono::index] Error: ' . $e->getMessage());
        }

        $this->template = 'payment/mono.tpl';

        $this->response->setOutput($this->render());
    }

    public function laterpay(){
        if (!$this->validateLaterpay()){
            $this->redirect('account/order');
        }

        $this->index($this->request->get['order_id'], true);
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

    public function response(){
        $this->language->load('payment/mono');
        $this->load->model('payment/mono');
        $this->load->model('checkout/order');

        $orderID = $this->session->data['order_id'];
        $orderInfo = $this->model_checkout_order->getOrder($orderID);

        if ($orderInfo['order_status_id'] == 0){
            $this->model_checkout_order->confirm($orderID, $this->config->get('config_order_status_id'), '', $notify = true);
        }

        switch($orderInfo['order_status_id']){
            case $this->config->get('mono_order_success_status_id'):{
                $this->response->redirect($this->url->link('checkout/success', '', true));
                break;
            }
            case $this->config->get('mono_order_cancelled_status_id'):{
                $this->response->redirect($this->url->link('checkout/success', '', true));
                break;
            }
            case 'created':
            case $this->config->get('mono_order_process_status_id'):{
                break;
            }

            case '0':{
                $invoiceId  = $this->model_payment_mono->getInvoiceId($orderID);
                $status     = $this->getStatus($invoiceId['InvoiceId']);
                switch ($status) {
                    case 'success':{
                        if($order['order_status_id'] != $this->config->get('mono_order_success_status_id')) {
                            $this->model_checkout_order->confirm($orderID, $this->config->get('mono_order_success_status_id'), $this->language->get('text_status_success'), $notify = true);

                            if ($this->config->get('mono_checkbox_enable')){
                                $this->load->library('hobotix/CheckBoxUA');
                                $checkBoxAPI = new hobotix\CheckBoxUA($this->registry);
                                $checkBoxAPI->setOrderNeedCheckbox($orderID);
                                $checkBoxAPI->setOrderPaidBy($orderID, 'mono');
                            }

                            $this->response->redirect($this->url->link('checkout/success', '', true));
                            break;
                        }
                    }
                    case 'failure':{
                        if($order['order_status_id'] != $this->config->get('mono_order_cancelled_status_id')) {
                            $this->model_checkout_order->confirm($orderID, $this->config->get('mono_order_cancelled_status_id'), $this->language->get('text_status_failure'));
                            $this->response->redirect($this->url->link('checkout/success', '', true));
                            break;
                        }
                    }
                    case 'processing':{
                        if($order['order_status_id'] != $this->config->get('mono_order_process_status_id')) {
                            $this->model_checkout_order->confirm($orderID, $this->config->get('mono_order_process_status_id'), $this->language->get('text_status_processing'));
                            break;
                        }
                    }
                    default:
                       exit('Undefined order status');
                }
                break;
            }
            default:{
                exit('Undefined order status');
            }
        }    
    }

    public function getStatus($InvoiceId) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.monobank.ua/api/merchant/invoice/status?invoiceId='.$InvoiceId.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Token: '.$this->config->get('mono_merchant').''
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response)->status;
    }

    public function callback() {
        $this->language->load('payment/mono');
        $this->load->model('checkout/order');
        $this->load->model('payment/mono');

        $json = $clear_json = file_get_contents("php://input");         
        $json = json_decode($json);

        $status     = $json->status;
        $InvoiceID  = $json->invoiceId;

        $monolog = new Log('monopay.txt');        
        $monolog->write('[ControllerPaymentMono::callback] ' . $clear_json);

        switch ($status){
            case 'success':
                sleep(2); break;
        }

        $OrderInfo      = $this->model_payment_mono->getOrderInfo($InvoiceID);
        $order          = $this->model_checkout_order->getOrder($OrderInfo['OrderId']);
        $this->order    = $order;

        $monolog->write('[ControllerPaymentMono::callback] ORDER STATUS: ' . $order['order_status_id']);

        if(isset($this->request->get['key']) && trim($this->request->get['key']) === trim($OrderInfo['SecretKey']))
        {
            switch ($status){
                case 'success':
                    if($order['order_status_id'] != $this->config->get('mono_order_success_status_id')) {
                        if (!$order['order_status_id']){
                             $this->model_checkout_order->confirm($OrderInfo['OrderId'], $this->config->get('config_order_status_id'), $this->language->get('text_status_started'), $notify = true);                            
                        }

                        $this->model_checkout_order->update($OrderInfo['OrderId'], $this->config->get('mono_order_success_status_id'), $this->language->get('text_status_success'), $notify = true);

                        if ($this->config->get('mono_checkbox_enable')){
                            $this->load->library('hobotix/CheckBoxUA');
                            $checkBoxAPI = new hobotix\CheckBoxUA($this->registry);
                            $checkBoxAPI->setOrderNeedCheckbox($OrderInfo['OrderId']);
                            $checkBoxAPI->setOrderPaidBy($OrderInfo['OrderId'], 'mono');
                        }
                        
                        $this->success($OrderInfo['OrderId']);
                    }
                    break;
                case 'processing':
                    if($order['order_status_id'] != $this->config->get('mono_order_process_status_id')) {
                        if (!$order['order_status_id']){
                            $this->model_checkout_order->confirm($OrderInfo['OrderId'], $this->config->get('config_order_status_id'), $this->language->get('text_status_processing'), $notify = true);
                        }

                        $this->model_checkout_order->update($OrderInfo['OrderId'], $this->config->get('mono_order_process_status_id'), $this->language->get('text_status_processing'));
                    }
                    break;
                case 'failure':
                    if($order['order_status_id'] != $this->config->get('mono_order_cancelled_status_id')) {
                        $this->model_checkout_order->confirm($OrderInfo['OrderId'], $this->config->get('mono_order_cancelled_status_id'), $this->language->get('text_status_failure'));
                    }
                    break;
                default: exit('undefined order status');
            }
        }
    }

    public function checkoutorder(){
        $this->load->model('payment/mono');
        $this->load->model('payment/mono_checkout');
        $this->load->model('catalog/product');
        $this->load->model('checkout/order');

        $monolog = new Log('monocheckout.txt');

        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->config->get('mono_monocheckout_enable') && isset($this->request->post['product_id'])) {

            if (isset($this->request->post['product_id'])) {
                $product_id = $this->request->post['product_id'];
            } else {
                $product_id = 0;
            }

            $product_info = $this->model_catalog_product->getProduct($product_id);
            if ($product_info) {

                if (isset($this->request->post['quantity'])) {
                    $quantity = $this->request->post['quantity'];
                } else {
                    $quantity = 1;
                }

                if (isset($this->request->post['option'])) {
                    $option = array_filter($this->request->post['option']);
                } else {
                    $option = array();
                }

                if (isset($this->request->post['profile_id'])) {
                    $profile_id = $this->request->post['profile_id'];
                } else {
                    $profile_id = 0;
                }

                if (!empty($this->session->data['cart'])){
                    $temp_cart = $this->session->data['cart'];
                    unset($this->session->data['cart']);
                }
                
                 if (!empty($this->session->data['shipping_method'])){
                    $temp_shipping_method = $this->session->data['shipping_method'];
                    unset($this->session->data['shipping_method']);
                }
                

                $this->cart->add($product_id, $quantity, $option, $profile_id);
                $cart_key = $this->cart->makeCartKey($product_id, $option, $profile_id, false, false);      

                $order_id = $this->model_checkout_order->addOrderSimple(['monocheckout' => 1]);
                $this->session->data['order_id'] = $order_id;

                $secretKey = $this->model_payment_mono_checkout->addOrderKey($order_id);

                $monolog->write('[ControllerPaymentMono::checkoutorder] Added Order: ' . $order_id);                

                $this->cart->remove($cart_key);

                if (!empty($temp_cart)){
                    $this->session->data['cart'] = $temp_cart;    
                }

                if (!empty($temp_shipping_method)){
                    $this->session->data['shipping_method'] = $temp_shipping_method;    
                }                

                try {
                    $result = $this->model_payment_mono_checkout->sendToAPI(['order_id' => $order_id, 'order_key' => $secretKey]);

                    $json = [
                        'success'       => true,
                        'order_id'      => $order_id,
                        'mono_order_id' => $result['result']['order_id'],
                        'mono_redirect' => $result['result']['redirect_url'],
                    ];

                } catch (\Exception $e){

                    $json = [
                        'success'  => false,
                        'message'  => $e->getMessage(),
                    ];
                }               
            }
        } else {
            $json['error'] = true;
        }

        $this->response->setOutput(json_encode($json));
    }

    public function checkoutcallback(){
        $this->language->load('payment/mono');
        $this->load->model('checkout/order');
        $this->load->model('payment/mono');
        $this->load->model('payment/mono_checkout');

        $json = $clear_json = file_get_contents("php://input");         
        $json = json_decode($json, true);

        $monolog = new Log('monocheckout.txt');
        $monolog->write('[ControllerPaymentMono::checkoutcallback] GOT JSON:' . $clear_json);

        if (!$json){
            header("HTTP/1.1 400 Bad Request");
            die();
        }

        $order      = $this->model_payment_mono_checkout->getOrder($json['basket_id'], $json['orderId']);
        if ($order){
            $order_info = $this->model_checkout_order->getOrder($order['OrderId']);
        }

        if (!$order || !$order_info){
            header("HTTP/1.1 404 Order Not Found");
            die();
        }

        $result = $this->model_payment_mono_checkout->updateOrder($order_info, $json);       

        if ($result){
            $this->response->setOutput(json_encode($result));
        } else {
            header("HTTP/1.1 500 Server Error");
            die();
        }

    }

    private function success(){
        $this->load->model('account/transaction');
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        $this->load->model('payment/shoputils_psb');
        $this->load->model('payment/mono');
        $this->language->load('payment/mono');

        $this->model_account_transaction->addTransaction(
            'Mono: Оплата по замовленню # '. $this->order['order_id'], 
            $this->model_account_order->getOrderTotal($this->order['order_id']),
            $this->model_account_order->getOrderTotalNational($this->order['order_id']),
            $this->config->get('config_regional_currency'),
            $this->order['order_id'],
            true,
            'mono',
            '',
            ''
        );

        $this->smsAdaptor->sendPayment($this->order, ['amount' => $this->model_account_order->getOrderTotalNational($this->order['order_id']), 'order_status_id' => $this->config->get('mono_order_success_status_id')]);
    }
}
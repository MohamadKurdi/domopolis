<?php
class ControllerPaymentMono extends Controller
{
    const CURRENCY_CODE = ['UAH','EUR','USD'];

    public function index()
    {
        $data = $this->language->load('payment/mono');
        $this->load->model('checkout/order');
        $this->load->model('payment/mono');

        $orderID = $this->session->data['order_id'];
        $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

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

            $randKey = $this->generateRandomString();
            $Params = [
                'order_id' => $orderID,
                'merchant_id' => $this->config->get('mono_merchant'),
                'amount' => $this->currency->format($orderInfo['total'], $orderInfo['currency_code'], $orderInfo['currency_value'], false) * 100,
                'currency' => $currencyCode,
                'response_url' => $this->url->link('payment/mono/response', '', true),
                'server_callback_url' => $this->url->link('payment/mono/callback', 'key=' . $randKey, true),
                'lang' => substr($this->session->data['language'], 0, 2),
                'randKey' => $randKey,
                'InvoiceId' => '',
            ];

            $data['checkout_url'] = $this->model_payment_mono->getCheckoutUrl($Params);

        }catch (Exception $e){
            $data['error_message'] = $this->language->get('text_general_error');
            $this->log->write('Error: ' . $e->getMessage());
        }

        if(VERSION < '2.2.0.0') {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mono.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/payment/mono.tpl', $data);
            } else {
                return $this->load->view('default/template/payment/mono.tpl', $data);
            }
        } else {
            return $this->load->view('payment/mono', $data);
        }
    }
    public function generateRandomString($length = 50) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    public function response()
    {
        $this->language->load('payment/mono');
        $this->load->model('payment/mono');
        $this->load->model('checkout/order');

        $orderID = $this->session->data['order_id'];
        $orderInfo = $this->model_checkout_order->getOrder($orderID);

        switch($orderInfo['order_status_id']){
            case $this->config->get('mono_order_success_status_id'):{
                $this->response->redirect($this->url->link('checkout/success', '', true));
                break;
            }
            case $this->config->get('mono_order_cancelled_status_id'):{
                $this->response->redirect($this->url->link('checkout/failure', '', true));
                break;
            }
            case 'created':
            case $this->config->get('mono_order_process_status_id'):{
                break;
            }

            case '0':{
                $invoiceId = $this->model_payment_mono->getInvoiceId($orderID);
                $status = $this->getStatus($invoiceId['InvoiceId']);
                switch ($status) {
                    case 'success':{
                        $this->model_checkout_order->addOrderHistory($orderID, $this->config->get('mono_order_success_status_id'), $this->language->get('text_status_success'), $notify = true);
                        $this->response->redirect($this->url->link('checkout/success', '', true));
                        break;
                    }
                    case 'failure':{
                        $this->model_checkout_order->addOrderHistory($orderID, $this->config->get('mono_order_cancelled_status_id'), $this->language->get('text_status_failure'));
                        $this->response->redirect($this->url->link('checkout/failure', '', true));
                        break;
                    }
                    case 'processing':{
                        $this->model_checkout_order->addOrderHistory($orderID, $this->config->get('mono_order_process_status_id'), $this->language->get('text_status_processing'));
                        break;
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

        $status = json_decode(file_get_contents("php://input"))->status;
        $InvoiceID = json_decode(file_get_contents("php://input"))->invoiceId;

        $OrderInfo = $this->model_payment_mono->getOrderInfo($InvoiceID);
        $order = $this->model_checkout_order->getOrder($OrderInfo['OrderId']);

        if(isset($this->request->get['key']) && $this->request->get['key'] === $OrderInfo['SecretKey'])
        {
            switch ($status){
                case 'success':
                    if($order['order_status_id'] != $this->config->get('mono_order_success_status_id')) {
                        $this->model_checkout_order->addOrderHistory($OrderInfo['OrderId'], $this->config->get('mono_order_success_status_id'), $this->language->get('text_status_success'), $notify = true);
                    }
                    break;
                case 'processing':
                    if($order['order_status_id'] != $this->config->get('mono_order_process_status_id')) {
                        $this->model_checkout_order->addOrderHistory($OrderInfo['OrderId'], $this->config->get('mono_order_process_status_id'), $this->language->get('text_status_processing'));
                    }
                    break;
                case 'failure':
                    if($order['order_status_id'] != $this->config->get('mono_order_cancelled_status_id')) {
                        $this->model_checkout_order->addOrderHistory($OrderInfo['OrderId'], $this->config->get('mono_order_cancelled_status_id'), $this->language->get('text_status_failure'));
                    }
                    break;
                default: exit('undefined order status');
            }
        }
    }
}

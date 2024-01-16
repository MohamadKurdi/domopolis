<?php

class ControllerPaymentMono extends Controller
{
    private $error = [];

    const MONOBANK_PAYMENT_VERSION = '1.0.8';
    
    public function install() {
        $this->load->model('payment/mono');
        $this->model_payment_mono->install();
    }

    public function uninstall() {
        $this->load->model('payment/mono');
        $this->model_payment_mono->uninstall();
    }

    public function index()
    {
        $this->data = $this->load->language('payment/mono');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['version'] = self::MONOBANK_PAYMENT_VERSION;

        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');

        if (($this->request->server['REQUEST_METHOD'] === 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('mono', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $errorMessageValues = ["warning", "merchant"];
        foreach ($errorMessageValues as $errorMessageValue)
            $this->data['error_' . $errorMessageValue] = (isset($this->error[$errorMessageValue])) ? $this->error[$errorMessageValue] : "";

        $this->data['action'] = $this->url->link('payment/mono', 'token=' . $this->session->data['token'], true);
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $formInputs = [
            "mono_status",
            "mono_status_fake",
            'mono_ismethod',
            "mono_merchant",
            "mono_monocheckout_merchant",

            "mono_person",
            "mono_monocheckout_person",

            'mono_geo_zone_id',
            "mono_sort_order",
            "mono_order_success_status_id",
            "mono_order_cancelled_status_id",
            "mono_order_process_status_id",
            "mono_destination",
            "mono_redirect_url",
            "mono_hold_mode",

            "mono_monocheckout_enable",

            "mono_checkbox_enable",
            "mono_checkbox_kassir_login",
            "mono_checkbox_kassir_password",
            "mono_checkbox_licence_key"
        ];

        foreach ($formInputs as $formInput) {
            $this->data[$formInput] = (isset($this->request->post[$formInput])) ? $this->request->post[$formInput] : $this->config->get($formInput);
        }


        $this->template = 'payment/mono.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/mono')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['mono_merchant']) {
            $this->error['merchant'] = $this->language->get('error_merchant');
        }

        return !$this->error;
    }


    public function order_info(&$route, &$data) {
        
         /* The below block to add hitpay refund tab to the order page */
        $order_id = $this->request->get['order_id'];
        $this->load->model('payment/mono');
        $order = $this->model_payment_mono->getOrder($order_id);

        if ($order) {
            $metaData = $order['InvoiceId'];
            if (!empty($metaData)) {

                $this->load->model('sale/order');
                    $order_info = $this->model_sale_order->getOrder($order_id);
                    $params = $order;
                    
                    /* The below block to add hitpay refund tab to the order page */
                   

                    if(isset($order['is_refunded']) && $order['is_refunded'] == 1){
                        $params['amount_refunded'] = $this->currency->format($order['amount_refunded'], $order_info['currency_code'], $order_info['currency_value']);
                        $params['total_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
                    }
                    else{
                        $params['is_refunded'] = 0;
                        $params['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
                    }
                    $params['user_token'] = $this->session->data['token'];
                    $params['order_id'] = $order_id;

                    $params['payment_id'] = $order['InvoiceId'];

                	// $this->load->model('extension/extension');
                    $extensions = $this->model_extension_extension->getInstalled('fraud');

                    if(VERSION < '2.2.0.0') {
                        $content = $this->load->view('payment/mono_refund.tpl', $params);
                    } else {
                        $content = $this->load->view('payment/mono_refund', $params);
                    }

                

                   $title = 'mono_refund';
                   $code = 'Mono Refund';
                    $data['tabs'][] = array(
                        'code'    => $title,
                        'title'   => $code,
                        'content' => $content
                    );
                }
        }
    
     /* The below block to add hitpay refund tab to the order page end */

     $holdStatus = $this->config->get('mono_hold_mode');
     if($holdStatus == 1){
        $params['token'] = $this->session->data['token'];
        $params['order_id'] = $order_id;
        $params['amount'] = round($order_info['total']*100);

        $params['payment_id'] = $order['InvoiceId'];

      
        if(VERSION < '2.2.0.0') {
            $content = $this->load->view('payment/mono_hold.tpl', $params);
        } else {
            $content = $this->load->view('payment/mono_hold', $params);
        }

        if($order['is_hold'] == 0){


            $title = 'Hold';
            $code = 'Hold';
     
             $data['tabs'][] = array(
                 'code'    => $title,
                 'title'   => $code,
                 'content' => $content
             );
        }
       
     }
    }

    public function refund()
    {
        
        $data = [
            'invoiceId' => $this->request->post['payment_id'],
            'extRef' => (string)$this->request->post['order_id'],
            'amount' => round((int)$this->request->post['mono_amount']*100),
        ];

        
        
        $token = $this->config->get('mono_merchant');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.monobank.ua/api/merchant/invoice/cancel',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-Token: '.$token.''
            ),
        ));

       
        $response = curl_exec($curl);
        curl_close($curl);

        if(!$response) {
            throw new \Exception('No response');
        }

       // $requestData['InvoiceId'] = $response['invoiceId'];
 
        $datainv = $this->request->post['payment_id'];
        $amount_refunded =$this->request->post['mono_amount'];

        $orderId = $this->request->post['order_id'];


        $this->load->model('payment/mono');
        $this->model_payment_mono->addRefund($datainv, $amount_refunded, $response);

       

        $this->model_payment_mono->addRefundHistory($orderId);

        return $response;

    }

    public function hold()
    {
      
        $data = [
            'invoiceId' => $this->request->post['payment_id'],
            'amount' => (int)$this->request->post['mono_amount'],
        ];

        $token = $this->config->get('mono_merchant');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.monobank.ua/api/merchant/invoice/finalize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-Token: '.$token.''
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if(!$response) {
            throw new \Exception('No response');
        }
 
        $orderId = $this->request->post['order_id'];


        $this->load->model('payment/mono');

        $this->model_payment_mono->addHoldHistory($orderId);

        return $response;

    }

    

}

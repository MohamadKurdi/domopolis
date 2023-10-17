<?php

class ModelPaymentMono extends Model
{
    const CURRENCY_CODE = ['UAH','EUR','USD'];

    public function getMethod($address, $total){
        $this->language->load('payment/mono');

        $query = $this->db->ncquery("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mono_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->session->data['currency'] != self::CURRENCY_CODE[0] && $this->session->data['currency'] != self::CURRENCY_CODE[1] && $this->session->data['currency'] != self::CURRENCY_CODE[2]) {
            $status = false;
        } elseif (!$this->config->get('mono_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = [];

        if ($status) {
            $method_data = [
                'code'          => 'mono',
                'terms'         => '',
                'description'   => $this->language->get('text_mono_description'),
                'title'         => $this->language->get('text_title'),
                'sort_order'    => $this->config->get('mono_sort_order')
            ];
        }
        return $method_data;
    }

    public function addOrder($args){
        $query = $this->db->ncquery("SELECT * FROM `mono_orders` WHERE OrderId = '" . (int)$args['order_id'] . "'");

        if($query->num_rows) {
            $this->db->ncquery("UPDATE `mono_orders` SET SecretKey = '" . $this->db->escape($args['randKey']) . "', InvoiceId = '" . $this->db->escape($args['InvoiceId']) . "' WHERE OrderId = '" . (int)$args['order_id'] . "'");
        } else {
            $this->db->ncquery("INSERT INTO `mono_orders` (InvoiceId, OrderId, SecretKey) VALUES('".$args['InvoiceId']."',".$args['order_id'].",'".$args['randKey']."')");
        }
    }

    public function getInvoiceId($OrderId){
        $q = $this->db->ncquery("SELECT * FROM `mono_orders` WHERE OrderId = '". (int)$OrderId . "'");

        return $q->num_rows ? $q->row : false;
    }

    public function getOrderInfo($InvoiceId){
        $q = $this->db->ncquery("SELECT * FROM `mono_orders` WHERE InvoiceId = '". $this->db->escape($InvoiceId) . "'");

        return $q->num_rows ? $q->row : false;
    }

    public function getCheckoutUrl($requestData){
        $request = $this->sendToAPI($requestData);
        return $request['pageUrl'];
    }

    public function getImageUrl($product_id){
        $q = $this->db->ncquery("SELECT * FROM `product` WHERE product_id = ". (int)$product_id . "");

        return $q->num_rows ? $q->row : false;
    }

    public function getEncodedProducts($order_id){
        $orderProducts = $this->db->ncquery("SELECT op.*, p.image FROM order_product op LEFT JOIN product p ON (op.product_id = p.product_id) WHERE order_id = '" . (int)$order_id . "'");

        $this->load->model('checkout/order');
        $this->load->model('tool/image');
        $orderInfo = $this->model_checkout_order->getOrder($order_id);

        foreach ($orderProducts->rows as $orderProduct){
            $products[] = [
                'name'  => $orderProduct['name'],
                'code'  => $orderProduct['model'],
                'sum'   => number_format($orderProduct['price_national'], 2, '.', '') * 100,
                'qty'   => (int)$orderProduct['quantity'],
                'icon'  => $this->model_tool_image->resize($orderProduct['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'))
            ];
        }

        $orderTotals = $this->db->ncquery("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "'");
        foreach ($orderTotals->rows as $orderTotal){

            if ($orderTotal['code'] == 'shipping' && (float)$orderTotal['value_national'] > 0){
                $products[] = [
                    'name'  => $orderTotal['title'],
                    'code'  => $orderTotal['code'],
                    'sum'   => number_format($orderTotal['value_national'], 2, '.', '') * 100,
                    'qty'   => 1,
                    'icon'  => HTTPS_SERVER . 'catalog/view/image/monoshipping.jpg'
                ];
            }

            if ($orderTotal['code'] == 'coupon' && (float)$orderTotal['value_national'] < 0){
                $products[] = [
                    'name'  => $orderTotal['title'],
                    'code'  => $orderTotal['code'],
                    'sum'   => number_format($orderTotal['value_national'], 2, '.', '') * 100,
                    'qty'   => 1,
                    'icon'  => HTTPS_SERVER . 'catalog/view/image/monodiscount.jpg'
                ];
            }

            if ($orderTotal['code'] == 'paymentmethoddiscounts' && (float)$orderTotal['value_national'] < 0){
                $products[] = [
                    'name'  => $orderTotal['title'],
                    'code'  => $orderTotal['code'],
                    'sum'   => number_format($orderTotal['value_national'], 2, '.', '') * 100,
                    'qty'   => 1,
                    'icon'  => HTTPS_SERVER . 'catalog/view/image/monodiscount.jpg'
                ];
            }

            if ($orderTotal['code'] == 'reward' && (float)$orderTotal['value_national'] < 0){
                $products[] = [
                    'name'  => $orderTotal['title'],
                    'code'  => $orderTotal['code'],
                    'sum'   => number_format($orderTotal['value_national'], 2, '.', '') * 100,
                    'qty'   => 1,
                    'icon'  => HTTPS_SERVER . 'catalog/view/image/monodiscount.jpg'
                ];
            }


            if ($orderTotal['code'] == 'voucher' && (float)$orderTotal['value_national'] < 0){
                $products[] = [
                    'name'  => $orderTotal['title'],
                    'code'  => $orderTotal['code'],
                    'sum'   => number_format($orderTotal['value_national'], 2, '.', '') * 100,
                    'qty'   => 1,
                    'icon'  => HTTPS_SERVER . 'catalog/view/image/monodiscount.jpg'
                ];
            }

            if ((float)$orderTotal['value_national'] < 0 && !in_array($orderTotal['code'], ['coupon', 'paymentmethoddiscounts', 'reward', 'voucher'])){
                $products[] = [
                    'name'  => $orderTotal['title'],
                    'code'  => $orderTotal['code'],
                    'sum'   => number_format($orderTotal['value_national'], 2, '.', '') * 100,
                    'qty'   => 1,
                    'icon'  => HTTPS_SERVER . 'catalog/view/image/monodiscount.jpg'
                ];
            }
        }

        return $products;
    }

    public function sendToAPI($requestData){
        $basketOrder    =  $this->getEncodedProducts($requestData['order_id']);

        $redirect_url   = $this->config->get('mono_redirect_url');
        $destination    = $this->config->get('mono_destination');

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

        $holdStatus = $this->config->get('mono_hold_mode');
        if($holdStatus == 1){
            $data = [
                'amount' => $requestData['amount'],
                'ccy' => $currencyDecode,
                'merchantPaymInfo' => [
                    'reference' => (string)$requestData['order_id'],
                    'destination' => $destination,
                    'basketOrder' => $basketOrder,
                ],
                'redirectUrl' => HTTPS_SERVER . $redirect_url,
                'webHookUrl' => str_replace('&amp;', '&', $requestData['server_callback_url']),
                'paymentType' => 'hold',

            ];
        }else{
            $data = [
                'amount' => $requestData['amount'],
                'ccy' => $currencyDecode,
                'merchantPaymInfo' => [
                    'reference' => (string)$requestData['order_id'],
                    'destination' => $destination,
                    'basketOrder' => $basketOrder,
                ],
                'redirectUrl' => HTTPS_SERVER . $redirect_url,
                'webHookUrl' => str_replace('&amp;', '&', $requestData['server_callback_url']),
            ];
        }

        $monolog = new Log('monopay.txt');
        $monolog->write('[ModelPaymentMono::sendToAPI] Creating invoice: ' . json_encode($data));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => 'https://api.monobank.ua/api/merchant/invoice/create',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_POSTFIELDS      => json_encode($data),
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json',
                'X-Token: '.$requestData['merchant_id'].''
            ],
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if(!$response) {
            throw new \Exception('No response');
        }

        $response = json_decode($response, true);

        if(empty($response['invoiceId'])) {
            throw new \Exception('Invalid response: ' . json_encode($response));
        }

        $requestData['InvoiceId'] = $response['invoiceId'];

        $this->addOrder($requestData);
        return $response;
    }
}
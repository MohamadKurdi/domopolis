<?php

class ModelPaymentMonoCheckout extends Model
{    
    const CURRENCY_CODE = ['UAH','EUR','USD'];

    private $api_uri = 'https://api.monobank.ua/personal/checkout/order/';

    public $shipping_mappings = [
        'pickup'    => 'pickup_advanced.point_1',
        'courier'   => 'dostavkaplus.sh2',
        'np_brnm'  => 'dostavkaplus.sh3'
    ];

    public $payment_mappings = [
        'card'                  => 'mono',
        'payment_on_delivery'   => 'transfer_plus.6',
        'part_purchase'         => 'ukrcredits_mb'
    ];

    public function getMethod($address, $total){
        return [];
    }

    public function addOrderKey($order_id){
        $secretKey = generateRandomString(20);
        $query = $this->db->ncquery("SELECT * FROM `mono_orders` WHERE OrderId = '" . (int)$order_id . "'");

        if($query->num_rows) {
            $this->db->ncquery("UPDATE `mono_orders` SET SecretKey = '" . $this->db->escape($secretKey) . "', WHERE OrderId = '" . (int)$order_id . "'");
        } else {
            $this->db->ncquery("INSERT INTO `mono_orders` SET SecretKey = '" . $this->db->escape($secretKey) . "', OrderId = '" . (int)$order_id . "'");
        }

        return $secretKey;
    }

    public function addOrder($order_id, $mono_order_id){
       $this->db->ncquery("UPDATE `mono_orders` SET InvoiceId = '" . $this->db->escape($mono_order_id) . "' WHERE OrderId = '" . (int)$order_id . "'");
    }

    public function getOrder($order_id, $mono_order_id){
        $query = $this->db->ncquery("SELECT * FROM `mono_orders` WHERE InvoiceId = '". $this->db->escape($mono_order_id) . "' AND SecretKey = '" . $this->db->escape($order_id) . "'");

        if($query->num_rows) {
            return $query->row;
        } else {
            return false;
        }
    }

    private function createCustomer($json){
        $customer_id        = $this->model_account_customer->addCustomer([
            'firstname'         => $json['mainClientInfo']['first_name'],
            'lastname'          => $json['mainClientInfo']['last_name'],
            'customer_group_id' => $this->config->get('config_customer_group_id'),
            'email'             => $json['mainClientInfo']['email']?$json['mainClientInfo']['email']:$json['mainClientInfo']['phoneNumber'],
            'telephone'         => $json['mainClientInfo']['phoneNumber'],
            'newsletter'                => 1,
            'newsletter_news'           => 1,
            'newsletter_personal'       => 1,
            'viber_news'        => 1,
            'fax'               => '',
            'passport_serie'    => '', 
            'passport_given'    => '',
            'birthday'           => '0000-00-00',
            'password'          => rand(9999, 999999),
            'company'           => '',
            'company_id'        => 0,
            'tax_id'            => 0,
            'address_1'         => '',
            'address_2'         => '',
            'city'              => '',
            'postcode'          => '',
            'country_id'        => $this->config->get('config_country_id'),
            'zone_id'           => '',
            'company'           => '',
        ]);

        return $customer_id;
    }

    public function updateOrder($order_info, $json){
        $this->language->load('payment/mono');
        $this->load->model('account/customer');
        $this->load->model('tool/simpleapicustom');

        $this->db->query("UPDATE `order` SET
                firstname           = '" . $this->db->escape($json['mainClientInfo']['first_name']) . "',
                lastname            = '". $this->db->escape($json['mainClientInfo']['last_name']) ."',
                payment_firstname   = '" . $this->db->escape($json['mainClientInfo']['first_name']) . "',
                payment_lastname    = '" . $this->db->escape($json['mainClientInfo']['last_name']) . "',                
                shipping_firstname     = '" . $this->db->escape($json['deliveryRecipientInfo']['first_name']) . "',
                shipping_lastname      = '" . $this->db->escape($json['deliveryRecipientInfo']['last_name']) . "',
                lastname    = '". $this->db->escape($json['mainClientInfo']['last_name']) ."',
                telephone   = '". $this->db->escape($json['mainClientInfo']['phoneNumber']) ."',
                email       = '". $this->db->escape($json['mainClientInfo']['email']) ."'
            WHERE order_id = '" . $order_info['order_id'] . "'");


        if (empty($order_info['customer_id'])){
            $customer_id = false;

            if (trim($json['mainClientInfo']['email'])){
                $json['mainClientInfo']['email'] = str_replace($this->config->get('simple_empty_email'), '', $json['mainClientInfo']['email']);     
            }

            if (trim($json['mainClientInfo']['phoneNumber']) && !$customer_id){
                $customer_id = $this->model_account_customer->getCustomerByPhone($json['mainClientInfo']['phoneNumber']);
            }

            if (trim($json['mainClientInfo']['email']) && !$customer_id){
                $customer_id = $this->model_account_customer->getCustomerIDByEmail($json['mainClientInfo']['email']);
            }

            if (!$customer_id){
                $customer_id = $this->createCustomer($json);
            }

            $this->model_checkout_order->updateCustomerIdInOrder($customer_id, $order_info['order_id']);
        }

        $this->db->query("UPDATE `order` SET shipping_code      = '" . $this->db->escape($this->shipping_mappings[$json['delivery_method']]) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
        $this->db->query("UPDATE `order` SET shipping_method    = '" . $this->db->escape($json['delivery_method_desc']) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");

        $this->db->query("UPDATE `order` SET payment_code       = '" . $this->db->escape($this->payment_mappings[$json['payment_method']]) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
        $this->db->query("UPDATE `order` SET payment_method     = '" . $this->db->escape($json['payment_method_desc']) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");

        if ($json['delivery_method'] == 'np_brnm'){
            $query = $this->db->ncquery("SELECT * FROM novaposhta_warehouses WHERE Ref = '" . $this->db->escape($json['delivery_branch_id']) . "' LIMIT 1");

            if ($query->num_rows){
                $this->db->query("UPDATE `order` SET shipping_address_1     = '" . $this->db->escape(str_replace('"', '', $query->row['Description'])) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
                $this->db->query("UPDATE `order` SET payment_address_1      = '" . $this->db->escape(str_replace('"', '', $query->row['Description'])) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
                $this->db->query("UPDATE `order` SET shipping_city          = '" . $this->db->escape(str_replace('"', '', $query->row['CityDescription'])) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
                $this->db->query("UPDATE `order` SET payment_city           = '" . $this->db->escape(str_replace('"', '', $query->row['CityDescription'])) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
            } else {
                $this->db->query("UPDATE `order` SET shipping_address_1     = '" . $this->db->escape(str_replace('"', '', $json['delivery_branch_address'])) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
                $this->db->query("UPDATE `order` SET payment_address_1      = '" . $this->db->escape(str_replace('"', '', $json['delivery_branch_address'])) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
            }
        }

        if ($json['delivery_method'] == 'courier'){
            $this->db->query("UPDATE `order` SET shipping_address_1     = '" . $this->db->escape($json['delivery_branch_address']) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");
            $this->db->query("UPDATE `order` SET payment_address_1      = '" . $this->db->escape($json['delivery_branch_address']) . "' WHERE order_id = '" . (int)$order_info['order_id'] . "'");            
        }

        switch($json['generalStatus']){
            case 'not_authorized':
            break;

            case 'not_confirmed':
            break;

            case 'in_process':
            if($order_info['order_status_id'] != $this->config->get('mono_order_process_status_id')) {
                if (!$order['order_status_id']){
                    $this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('config_order_status_id'), $this->language->get('text_status_processing'), $notify = true);
                }

                $this->model_checkout_order->update($order_info['order_id'], $this->config->get('mono_order_process_status_id'), $this->language->get('text_status_processing'));
            }
            break;            

            case 'payment_on_delivery':
            if($order_info['order_status_id'] != $this->config->get('config_order_status_id')) {            
                if (!$order_info['order_status_id']){
                    $this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('config_order_status_id'), 'Замовлення оформлене через Mono Checkout', $notify = true);                            
                }

                $this->model_checkout_order->update($order_info['order_id'], $this->config->get('config_order_status_id'), 'Замовлення оформлене через Mono Checkout', $notify = true); 
            }
            break;

            case 'success':
            if($order_info['order_status_id'] != $this->config->get('mono_order_success_status_id')) {
                if (!$order_info['order_status_id']){
                    $this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('config_order_status_id'), 'Замовлення оформлене через Mono Checkout', $notify = true);                            
                }

                if ($json['payment_method'] != 'payment_on_delivery' && $json['payment_status'] == 'success'){
                    $this->model_checkout_order->update($order_info['order_id'], $this->config->get('mono_order_success_status_id'), $this->language->get('text_status_success'), $notify = true);
                }
                
            }
            break;

            case 'fail':
            if($order_info['order_status_id'] != $this->config->get('mono_order_cancelled_status_id')) {
                $this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('mono_order_cancelled_status_id'), $this->language->get('text_status_failure'));
            }
            break;

            default: exit('undefined order status');
        }

        $result = $this->model_checkout_order->getOrder($order_info['order_id']);

        return [
            'order_id'          => $order_info['order_id'],
            'order_status_id'   => $result['order_status_id']
        ];
    }

    public function sendToAPI($requestData){
        $this->load->model('checkout/order');
        $this->load->model('tool/simpleapicustom');

        $monolog = new Log('monocheckout.txt');

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

        $order_info     = $this->model_checkout_order->getOrder($requestData['order_id']);
        $products_info  = $this->model_checkout_order->getOrderProducts($requestData['order_id']);

        $products = [];
        foreach ($products_info as $product){
            $products[] = [
                'name'          => $product['name'],
                'code_product'  => $product['product_id'],
                'cnt'           => $product['quantity'],
                'price'         => $product['price_national']
            ];            
        }

        if ($order_info){
            $data = [
                'order_ref'             => (string)$requestData['order_key'],
                'amount'                => (float)$order_info['total_national'],
                'ccy'                   => (int)$currencyDecode,
                'count'                 => (int)count($products_info),
                'products'              => $products,
                'payment_method_list'   => [
                    'card','payment_on_delivery'
                ],
                'dlv_method_list'       => [
                    'np_brnm'
                ]
            ];

            $customer_city = $this->model_tool_simpleapicustom->getAndCheckCurrentCity();

            if ($customer_city && !empty($customer_city['city']) && $customer_city['city'] == $this->language->get('default_city_' . $this->config->get('config_country_id'))){
                $data['dlv_method_list'][] = 'courier';
            }            

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL             => $this->api_uri,
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
                    'X-Token: '.    $this->config->get('mono_merchant') .''
                ],
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            if(!$response) {
                throw new \Exception('No response');
            }

            $monolog->write('[ModelPaymentMonoCheckout::sendToAPI] Got Result: ' . $response);

            $response = json_decode($response, true);

            if(empty($response['result']) || empty($response['result']['order_id'])) {
                throw new \Exception('Invalid response: ' . json_encode($response));
            }
        
            $this->addOrder($requestData['order_id'], $response['result']['order_id']);

            return $response;            

        } else {
            throw new \Exception('No order id provided!');
        }
    }
}
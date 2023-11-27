<?php

namespace hobotix;

class CheckBoxUA {
    protected $base_url         = 'https://api.checkbox.in.ua/api/v1/';
    protected $x_client_name    = 'Hobotix OC 1.5';

    protected $login            = null;
    protected $password         = null;
    protected $access_token     = null;
    protected $x_license_key    = null;
    protected $date             = null;
    protected $timeout          = 25;
    protected $taxes            = null;
    protected $is_dev_mode      = false;
    protected $original_product_sum = 0;

    private $db         = null;
    private $cache      = null;
    private $config     = null;
    private $currency   = null;
    private $registry   = null;

    private $model_payment_mono = null;

    public $method = "POST";
    private $error = [];    


    public function __construct($registry) {
        $this->cache    = $registry->get('cache');
        $this->config   = $registry->get('config');
        $this->db       = $registry->get('db');
        $this->currency = $registry->get('currency');
        $this->registry = $registry;
    }

    /**
     * Authentication
     *
     * @param string $login логин
     * @param string $password пароль
     */

    public function setAuth($login, $password, $x_license_key, $receipt_is_dev_mode=0){
        $this->login            = $login;
        $this->password         = $password; 
        $this->x_license_key    = $x_license_key; 

        if($receipt_is_dev_mode){
            $this->base_url = 'https://dev-api.checkbox.in.ua/api/v1/';
            $this->is_dev_mode = true;
        }

        $this->signin();
    }

	public function checkAuth($login, $password, $x_license_key, $receipt_is_dev_mode=0){        
        $this->x_license_key = $x_license_key; 

        if($receipt_is_dev_mode){
            $this->base_url = 'https://dev-api.checkbox.in.ua/api/v1/';
			$this->is_dev_mode = true;
        }

        $url                = $this->base_url . 'cashier/signin';
        $request_body_data  = array('login'=> $login, 'password'=> $password);
        $request            = $this->getCurl($url, $request_body_data);		        
		
		if(isset($request['access_token'])){
			$this->access_token = $request['access_token'];
			
			$organization_title = $this->getCurrentOrganization(); 
			$message = "Доступи коректні!<br>" . '<span style="font-size: 14px;color: #000;">Організація:<b> '.$organization_title.' </b></span>';
			 			 
			return array('success'=>$message); 
		}

		if(isset($request['message'])){  
			return array('error'=>$request['message']);
		} 
    }

    ######## Касир

    /**signin
    Sign In Cashier
    Вхід користувача (касира) за допомогою логіна та паролю
    */

    public function signin(){        
        $access_token = $this->cache->get('access_token');

        if(!$access_token){
            $url = $this->base_url . 'cashier/signin'; 
            $request_body_data  =  array('login'=> $this->login, 'password'=> $this->password);
            $request            = $this->getCurl($url, $request_body_data);

            if(isset($request['access_token'])){
                $access_token = $request['access_token'];
                $this->cache->set('access_token', $access_token);
                $this->nLog('Отримали і записали токен');
            }
        }

        $this->access_token = $access_token; 
    }


    #   Ще не відомо, в яких випадках необхідно вилогінювати Касира по API
    #   Sign Out Cashier //Завершення сесії користувача (касира) з поточним токеном доступу
    #   https://dev-api.checkbox.in.ua/api/redoc#operation/sign_out_cashier_api_v1_cashier_signout_post



    /*
     * Зміни Shifts
     */
    public function createShifts(){
        $url = $this->base_url .'shifts';
        $this->method = "POST";
        $request = $this->getCurl($url);
        
        $this->saveShiftRequest($request);        
        if(isset($request['id'])){
            $this->cache->set('current_shift', $request);
        }
        return $request;
    }

    public function getShifts(){		
		if(!$this->x_license_key){
			return array();
		}

        echoLine('[CheckBoxUA::getShifts] Trying to get opened shifts...', 'i');

        $return_data = [];
        $url = $this->base_url .'shifts';
        $request_body['meta'] = array(      
            'statuses' => 'OPENED'            
        );
        if(!$this->error){
            $this->method = "GET";
            $request = $this->getCurl($url, $request_body); 

            if(isset($request['results'][0]['id'])){                
                $this->cache->set('current_shift', $request['results'][0]);
                return $request['results'][0];
            } else {
                $return_data['error'] = "Немає відкритих змін";
                return $return_data;
            }    

        } else {
            $return_data['error'] = $this->error;
        }        

         return $return_data;
    }

    public function closeShifts(){ 
        $this->cache->set('current_shift', '');
        $this->cache->delete('current_shift');
        $return_data = [];       
        $url = $this->base_url . 'shifts/close';
 
        $request = $this->getCurl($url);  
        $this->saveShiftRequest($request);

        return $request;
    }

    public function getReport($report_id){
        $url = $this->base_url .'reports/'.$report_id.'/text';
        return $url;
    }
  

    #########
    ### RECEIPT
    ###
    public function receiptsSell($data = []){
        $return_data = $this->error = [];
        $this->checkIfOrederReceiptAlreadyExit($data);
         
        $url = $this->base_url . 'receipts/sell';
      
        $request_body = array(      
            'goods'         => $this->getProductsForReceipt($data),
            'delivery'      => $this->getDeliveryEmail($data),
            'payments'      => $this->getPayments($data),
            'discounts'     => $this->getDiscounts($data),           
        );

        if($this->config->get('receipt_footer_text')){
            $request_body['footer'] = $this->config->get('receipt_footer_text');
        }
 
        if(!$this->error){
            $this->method = "POST";
            $request = $this->getCurl($url, $request_body);
            sleep(1);

            if(isset($request['message']) ){
				$this->nLog('[CheckBoxUA::receiptsSell] Answer for request: '.json_encode($request)); 
                echoLine('[CheckBoxUA::receiptsSell] Answer for request: '.json_encode($request));

                $return_data['error']['message'] = $request['message'];
            } else {
                $this->saveReceiptsRequest($request, $data);

				$this->nLog('[CheckBoxUA::receiptsSell] Request for creating cheques: '.json_encode($request_body));    

                if (is_cli()){
                    $return_data['success'] = '[CheckBoxUA::receiptsSell] Cheque created successfully: ' . $request['fiscal_code'];
                } else {
                    $return_data['success']  = ' Фіскальний чек ' . $request['fiscal_code'] . ' Успішно створено'; 
                    $return_data['success'] .= ' Переглянути чек: <a target="_blank" href="'.$this->getReceiptLink($request['id'], 'text') . '"> -== Text ==- </a> ';
                }
               

                $this->getReceipt($request['id']);
            }
        } else {
            $return_data['error'] = $this->error;
        }

        $this->cache->set('current_shift','');
        $this->cache->delete('current_shift');
        return $return_data;
    }

    public function getReceipt($receipt_id){        
        $return_data = [];

        $url            = $this->base_url . 'receipts/' . $receipt_id . '';
        $this->method   = "GET";
        $request        = $this->getCurl($url);

        echoLine('[CheckBoxUA::getReceipt] Started getting ' . $receipt_id . ' from CheckBox', 'i');
        echoLine('[CheckBoxUA::getReceipt] Got Data: ' . json_encode($request), 'i');

        if(isset($request['status']) && $request['status'] == 'DONE'){
            $this->updateReceiptsRequest($request, $receipt_id);

            echoLine('[CheckBoxUA::getReceipt] Data about receipt is updated!', 's');
            echoLine('[CheckBoxUA::getReceipt] fiscal_code = ' . $request['fiscal_code'], 'i');

            return array ('success'=> 'Data updated ' . $receipt_id);
        }

		if(isset($request['message']) ){
             echoLine('[CheckBoxUA::getReceipt] Data about receipt is not updated! ' . $message, 'e');

            $this->nLog('⬅️ Відповідь на getReceipt: '.json_encode($request));
            return array ('error'=> array('message'=>$request['message'])  );
        } 
    }

    public function getReceiptLink($receipt_id, $type='html'){
        switch ($type) {
            case 'pdf':
                $url = $this->base_url .'receipts/'.$receipt_id .'/pdf';
                break;
            case 'text':
                $url = $this->base_url .'receipts/'.$receipt_id .'/text';
                break;
            case 'png':
                $url = $this->base_url .'receipts/'.$receipt_id .'/png';
                break;
            case 'qrcode':
                $url = $this->base_url .'receipts/'.$receipt_id .'/qrcode';
                break;
            
            default:
                $url = $this->base_url .'receipts/'.$receipt_id .'/html';
                break;
        }
        return $url;
    }

    public function receiptsService($data = []){
        $return_data = [];
        $url = $this->base_url .'receipts/service';

        $service_operation = isset($data['service_operation']) ? (string)$data['service_operation']: '';
        $service_value = isset($data['service_value']) ? (float)str_replace(',', '.', $data['service_value']): 0;
        $service_type = isset($data['service_type']) ? (string)$data['service_type']: '';

        if(!$service_operation) return array('error'=>array('message' => 'Неможу розпізнати тип перемінню service_type'));

        if($service_operation == 'OUT'){
            $service_value = $service_value * -1;
        }
      
        $request_body = array(            
            'payment' => array(
                'type'=> $service_type,
                'value'=> (int) round(($service_value*100), 0), 
            )        
        );
        #de($request_body,1);
    
        $this->method = "POST";
        $request = $this->getCurl($url, $request_body);
            if(isset($request['message']) ){
                $this->nLog($request);
                $return_data['error']['message'] = $request['message'];
            }else{
                $this->saveReceiptsRequest($request,$data);
                $return_data['success'] = "Службове внесення\винесення успішно створено! <br> Фіскальний чек <b>".$request['fiscal_code']."</b> <br>"; 
                $return_data['success'] .= 'Переглянути чек: <a target="_blank" href="'.$this->getReceiptLink($request['id'],'text').'">-== Text==- </a> ';
            }

         return $return_data;
    }

    public function checkReceipt($data = []){

        $request_body = array(      
            'goods'     => $this->getProductsForReceipt($data),
            'delivery'  => $this->getDeliveryEmail($data),          
            'payments'  => $this->getPayments($data),
            'discounts' => $this->getDiscounts($data) ,              
        );

        if($this->config->get('receipt_footer_text')){
            $request_body['footer'] = $this->config->get('receipt_footer_text');
        }

        return $request_body;
    }

    public function getTaxSymbolByCode($code){
        # використовуємо тільки для превю чека!!!
        switch ($code) {
            case '1':
                return 'А';
                break;
            case '2':
                return 'Б';
                break;
            case '7':
                return 'Ж';
                break;	
			case '8':
                return 'З';
                break;
            case '9':
                return 'А';
                break;
             
        }
        return $code;
    }
    
    public function getTax(){
		# if not auth
		if(!$this->x_license_key){
			return array();
		}


        if($this->is_dev_mode){
           return array(1); #ПДВ А
        }

        ### Зберігаю в кеш активний податок підприємся (ПДВ, Акциз, інше)
        $checkbox_taxes = $this->cache->get('checkbox_taxes'); 
        
        if(!$checkbox_taxes){
            $taxes = [];
            $url = $this->base_url .'tax';   
            $this->method = "GET";
            $request = $this->getCurl($url);
            if($request){ 
                foreach ($request as $tax) {
                    if(isset($tax['code']) && count($taxes) < 2 ){
                        $taxes[] = $tax['code']; 
                    }                
                }
            } 

            $checkbox_taxes = $taxes;
            $this->cache->set('checkbox_taxes', $checkbox_taxes);
        }
        return $checkbox_taxes;
    }
	
	public function getCurrentOrganization(){
        # if not auth
        if(!$this->x_license_key){
            return '';
        }

        ### Зберігаю в кеш інформацію про поточну організацію
        $checkbox_current_organization = $this->cache->get('checkbox_current_organization'); 
        if(!$checkbox_current_organization){
            $organization_title = '---';
            $url = $this->base_url .'organization/receipt-config';   
            $this->method = "GET";
            $request = $this->getCurl($url);
            
            if($request && isset($request['organization']['title']) ){
                 $organization_title = $request['organization']['title'];
            }
            $checkbox_current_organization = $organization_title;
            $this->cache->set('checkbox_current_organization', $organization_title);
        }
        return $checkbox_current_organization;
    }

    public function getExtendedPaymentInfo($order_info, $payment_type = ''){
        $result = [];

        if ($order_info['paid_by'] == 'mono'){
            loadAndRenameCatalogModels('model/payment/mono.php', 'ModelPaymentMono', 'ModelPaymentMono');
            $this->model_payment_mono = new \ModelPaymentMono($this->registry);

            $monoPaymentInfo = $this->model_payment_mono->getPaymentDataByOrderId($order_info['order_id']);

            if ($monoPaymentInfo){
                if (empty($monoPaymentInfo['payment_data'])){
                    $payment_data = $this->model_payment_mono->getInvoicePaymentInfo($json['invoiceId']);

                    if ($payment_data){
                        $this->model_payment_mono->updatePaymentData($json['invoiceId'], $payment_data);               
                    }
                } else {
                    $payment_data = json_decode($monoPaymentInfo['payment_data'], true);
                }
            }

            if ($payment_data){
                $result['card_mask']        = $payment_data['maskedPan'];
                $result['auth_code']        = $payment_data['approvalCode'];
                $result['rrn']              = $payment_data['rrn'];
                $result['terminal']         = $payment_data['terminal'];
                $result['payment_system']   = $payment_data['paymentMethod'];
            }
        }

        if (!$order_info['paid_by'] && (in_array($order_info['shipping_code'], ['dostavkaplus.sh13', 'dostavkaplus.sh3']) || mb_stripos($order_info['shipping_method'], 'Новою Поштою'))){
            $result['label'] = 'Післяплата';
        }

        return $result;
    }

    private function getFullBankName($sender_card_bank){
        switch ($sender_card_bank) {
            case 'pb':
                $bank_name = "ПриватБанк";
                break;
            case 'JSC UNIVERSAL BANK':
                $bank_name = "МоноБанк";
                break;
            case 'JSC ALFA-BANK':
                $bank_name = "Альфа-банк";
                break;
            
            default:
                $bank_name = $sender_card_bank;
                break;
        }
        return $bank_name;
    }


    ####
    ### HELPER
    ###

    public function setOrderNeedCheckbox($order_id){
        $this->db->ncquery("UPDATE `order` SET needs_checkboxua = '1' WHERE order_id = '" . (int)$order_id . "'");
    }

    public function setOrderNotNeedCheckbox($order_id){
        $this->db->ncquery("UPDATE `order` SET needs_checkboxua = '0' WHERE order_id = '" . (int)$order_id . "'");
    } 

    private function getProductsForReceipt($data = []){
        if(!isset($data['products'])){
            $this->error['message'] = "Не можу отримати товари";
        }
        
        # якщо є декілька тоталів, які необхідно сумувати           
        $extra_product_sum_array = $this->getExtraProductSum($data);

        $products = [];
        
        foreach ($data['products'] as $product) {           
            $products[] = array(
                'good' => array(
                    'code'  => $product['product_id'],
                    'name'  => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
                    'tax'   => $this->getTax(),
                    'price' => $this->convert2intX100($product['price_national'], $data),

                ),
                'quantity'=> ($product['quantity'] * 1000),
            );
        }
        #2. Робимо додаткову позицію під доставку:
        #приклад за доставка Упаковка + Термобок + новапошта
        if($extra_product_sum_array && $this->config->get('receipt_type_of_extrapayment') == 2){        
            
            $name = $this->config->get('receipt_text_for_extrapayment') ? $this->config->get('receipt_text_for_extrapayment') : 'Організація перевезень';
                $products[] = array(
                    'good' => array(
                        'code'  => 'addition',
                        'name'  => $name,
                        'tax'   => $this->getTax(),
                        'price' => $this->convert2intX100($extra_product_sum_array['extra_sum'],$data),

                    ),
                    'quantity'=> (1 * 1000),
                );
        } 
        
        return $products;
    }

    private function getDeliveryEmail($data = array()){
        $delivery = []; 

        if(isset($data['email']) && $data['email'] && $this->config->get('receipt_is_customer_send_email') && filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            $delivery['email'] = $data['email'];
            return $delivery;
        } 

        
        if($this->config->get('config_email')){
            $delivery['email'] = $this->config->get('config_email');
            return $delivery;
        }
        
        return array();        
    }

    private function getPayments($data = []){     
        $payments       = [];
        $payment_item   = [];
        $product_sum    = 0;
        
        foreach ($data['products'] as $product) {
            $product_sum += $product['total_national'];
        } 

        foreach ($data['totals'] as $total) {
            $codes_for_discounts = $this->config->get('receipt_codes_for_discounts');
            if ($codes_for_discounts && in_array($total['code'], $codes_for_discounts)  ){
                $product_sum += $total['value_national'];
            } elseif ((float)$total['value_national'] < 0) {
                $product_sum += $total['value_national'];
            }              
        }
        
        $extra_product_sum_array = $this->getExtraProductSum($data);
        if($extra_product_sum_array && $this->config->get('receipt_type_of_extrapayment') != 0){
             $product_sum += $extra_product_sum_array['extra_sum'];
        }

        $product_sum = round($product_sum,2);
        
        $this->original_product_sum = $product_sum;
        if($this->config->get('receipt_price_round')){
            $this->original_product_sum = $product_sum;
            $product_sum = round($product_sum);
        }
		
        $payment_item['value'] = $this->convert2intX100($product_sum ,$data);

        $payment_item['type'] = $this->config->get('receipt_payment_type');
        
        if($this->config->get('receipt_cash_payment_condition') && $this->config->get('receipt_cash_payment_condition') == 'AND'){
                
            if($this->config->get('receipt_cash_payments') && $this->config->get('receipt_cash_shippings')) {
                $shipping_data = explode(".", $data['shipping_code']);
                if(isset($shipping_data[0]) && in_array($shipping_data[0], $this->config->get('receipt_cash_shippings')) && in_array($data['payment_code'], $this->config->get('receipt_cash_payments'))){
                    $payment_item['type'] = 'CASH';
                    
                }
            }elseif($this->config->get('receipt_cash_payments')){
                if($this->config->get('receipt_cash_payments') && in_array($data['payment_code'], $this->config->get('receipt_cash_payments')) ) {
                     $payment_item['type'] = 'CASH';
                }
            }
            
        } elseif ($this->config->get('receipt_cash_payment_condition') && $this->config->get('receipt_cash_payment_condition') == 'OR'){
            
            if($this->config->get('receipt_cash_payments') && in_array($data['payment_code'], $this->config->get('receipt_cash_payments')) ) {
                 $payment_item['type'] = 'CASH';
            }
            
            $shipping_data = explode(".", $data['shipping_code']);
            if($this->config->get('receipt_cash_shippings') && isset($shipping_data[0]) && in_array($shipping_data[0], $this->config->get('receipt_cash_shippings')) ) {
                 $payment_item['type'] = 'CASH';
            }
            
        }

        $addition_payment_info = $this->getExtendedPaymentInfo($data, $payment_item['type']);
        if($addition_payment_info){ 
            if(!empty($addition_payment_info['card_mask'])){
                $payment_item['card_mask'] = $addition_payment_info['card_mask'];
            }

            if(!empty($addition_payment_info['sender_card_bank'])){
                $payment_item['bank_name'] = $this->getFullBankName($addition_payment_info['sender_card_bank']);
            }

            if(!empty($addition_payment_info['auth_code'])){
                $payment_item['auth_code'] = $addition_payment_info['auth_code'];
            }

            if(!empty($addition_payment_info['rrn'])){
                $payment_item['rrn'] = $addition_payment_info['rrn'];
            }

            if(!empty($addition_payment_info['terminal'])){
                $payment_item['terminal'] = $addition_payment_info['terminal'];
            }

            if(!empty($addition_payment_info['payment_system'])){
                $payment_item['payment_system'] = $addition_payment_info['payment_system'];
            }

            if(!empty($addition_payment_info['label'])){
                $payment_item['label'] = $addition_payment_info['label'];
            } elseif ($this->config->get('receipt_payment_label_text')){
                $payment_item['label'] = $this->config->get('receipt_payment_label_text');
            }
            
            if($this->config->get('receipt_payment_system_text')){
                $payment_item['payment_system'] = $this->config->get('receipt_payment_system_text');
            }
        } 

        $payments[] = $payment_item;
        return $payments;
    }

    private function getDiscounts($data = []){      
        $discounts = [];
        $discount_item = [];

        $discount_sum = 0;
        $codes_for_discounts = $this->config->get('receipt_codes_for_discounts');

        foreach ($data['totals'] as $total) {           
            if ($codes_for_discounts && in_array($total['code'], $codes_for_discounts)) {
                $discount_sum += $total['value_national']*(-1);
            } elseif ((float)$total['value_national'] < 0) {
                $discount_sum += $total['value_national']*(-1);
            }       
        } 

        $discount_sum = $this->convert2intX100($discount_sum, $data);      

        $discount_item['value'] = $discount_sum;
        $discount_item['type']  = 'DISCOUNT';
        $discount_item['mode']  = 'VALUE';
        $discount_item['name']  = 'Знижка '; 

        if($discount_sum){
           $discounts[] = $discount_item;
        }        

        return $discounts;
    }
    
    private function getExtraProductSum($data = []){      
        $extra_sum = 0;
        $extra_total = 0;
        $quantity = 0;
        foreach ($data['totals'] as $total) { 
            $codes_for_extrapayment = $this->config->get('receipt_codes_for_extrapayment');         
            if($codes_for_extrapayment && in_array($total['code'], $codes_for_extrapayment)){                
                $extra_sum += $total['value'];
            }
        }
         
        foreach ($data['products'] as $key => $product) {
            if($extra_sum){             
                $product_total_price = ($product['price'] + ($extra_sum/3))*100* ($product['quantity']);    
                $extra_total += $product_total_price;   
                $quantity += $product['quantity'];               
            }          
        }

        if($extra_sum && $extra_total){
                return array('extra_sum'=>$extra_sum, 'extra_total'=>$extra_total, 'quantity'=>$quantity );
        }
        return array();        
    }

    private function checkIfOrederReceiptAlreadyExit($data = []){
        $sql = " SELECT * FROM order_receipt WHERE order_id = ".(int)$data['order_id'] ;
        $query = $this->db->ncquery( $sql );       
        if($query->num_rows){
            $this->error['message'] = "Для замовлення <b>".$data['order_id']."</b> вже створено чек. Код чеку: ".$query->row['fiscal_code'];
        }
    }

    private function saveReceiptsRequest($request, $data){
       
        $data['order_id']   = isset($data['order_id']) ? $data['order_id'] : 0;
        $data['type']       = isset($data['type']) ? $data['type'] : '';

        $sql = "INSERT INTO order_receipt SET         
            order_id            = '" . (int)$data['order_id'] . "', 
            receipt_id          = '" . $this->db->escape($request['id']) . "',  
            serial              = '" . (int)$request['serial'] . "',  
            status              = '" . $this->db->escape($request['status']) . "',  
            fiscal_code         = '" . $this->db->escape($request['fiscal_code']) . "',  
            fiscal_date         = '" . $this->db->escape($request['fiscal_date']) . "',  
            is_created_offline  = '" . (int)$request['is_created_offline'] . "',  
            is_sent_dps         = '" . (int)$request['is_sent_dps'] . "',  
            sent_dps_at         = '" . $this->db->escape($request['sent_dps_at']) . "',  
            type                = '" . $this->db->escape($request['type']) . "',  
            api                 = 'checkbox',
            all_json_data       = '" . $this->db->escape(json_encode($request)) . "'";

        $this->db->ncquery( $sql ); 
    }

    private function updateReceiptsRequest($request,$receipt_id){
        if (!empty($request['fiscal_code'])){
            $request['is_sent_dps'] = true;
        }

        if (!empty($request['fiscal_date'])){
            $request['sent_dps_at'] = $request['fiscal_date'];
        }

        $sql = "UPDATE order_receipt SET  
            serial                  = '" . (int)$request['serial'] . "',  
            status                  = '" . $this->db->escape($request['status']) . "',  
            fiscal_code             = '" . $this->db->escape($request['fiscal_code']) . "',  
            fiscal_date             = '" . $this->db->escape($request['fiscal_date']) . "',  
            is_created_offline      = '" . (int)$request['is_created_offline'] . "',  
            is_sent_dps             = '" . (int)$request['is_sent_dps'] . "',  
            sent_dps_at             = '" . $this->db->escape($request['sent_dps_at']) . "',
            api                     = 'checkbox',               
            all_json_data           = '" . $this->db->escape(json_encode($request)) . "' 
            WHERE receipt_id       = '".$receipt_id."' ";
        $this->db->ncquery( $sql );
    }

    private function saveShiftRequest($request){       
        $request['id']             = isset($request['id']) ? $request['id'] : '-';
        $request['serial']         = isset($request['serial']) ? $request['serial'] : 0;
        $request['status']         = isset($request['status']) ? $request['status'] : 'error';
        $request['z_report']['id'] = isset($request['z_report']['id']) ? $request['z_report']['id'] : '';

        $sql = "INSERT INTO shift SET     
            shift_id        = '" . $this->db->escape($request['id']) . "',  
            serial          = '" . (int)$request['serial'] . "',  
            status          = '" . $this->db->escape($request['status']) . "',  
            z_report_id     = '" . $this->db->escape($request['z_report']['id']) . "',
            all_json_data   = '" . $this->db->escape(json_encode($request)) . "'";
        $this->db->ncquery( $sql ); 
    }

    private function get_request_headers(){
        $request_headers = array(
            "Content-Type: application/json",
            "Accept: application/json;charset=UTF-8",
        );

        if(!$this->access_token && $this->cache->get('access_token')){
            $this->access_token = $this->cache->get('access_token');
        }

        if($this->access_token){
            $request_headers[] = "Authorization: Bearer " . $this->access_token;
            if($this->x_license_key){
                $request_headers[] = "X-License-Key: {$this->x_license_key}";
            }

            if($this->x_client_name){
                $request_headers[] = "X-Client-Name: {$this->x_client_name}";
            }
        }
        return $request_headers;
    }

    private function getCurl($url, $request_body = []){   

        if(isset($request_body['meta']) && http_build_query($request_body['meta'])){
            $url = $url . '?' .http_build_query($request_body['meta']);
            unset($request_body['meta']);
        }

        $this->nLog('Робимо запит ['.$this->method.'] url:'.$url);
        echoLine('[CheckBoxUA::getCurl] Running ' . $this->method . ' on ' . $url);      

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST,  $this->method);
        curl_setopt($c, CURLOPT_HTTPHEADER,     $this->get_request_headers());
        
        if ($request_body) {         
            echoLine('[CheckBoxUA::getCurl] Set data for request: ' . json_encode($request_body), 'i');
            curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($request_body));
        }

        if (strstr($url, 'https://')) {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        }

        curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
        $res = curl_exec($c);
        $curl_getinfo = curl_getinfo($c);
        curl_close($c);  
        return $this->readResult($res,  $url, $request_body,  $curl_getinfo); 
    }

    private function readResult($res, $url, $request_body = [], $curl_getinfo = []){
        if (!$res) {
            return false;
        } 
        $result_data = json_decode( $res,1);
        if(isset($result_data['message'])){          
           $this->error['message'] = $result_data['message'];
           $this->error['sending_data']['url'] =$url;
           $this->error['sending_data']['request_body'] = json_encode($request_body);
           $this->nLog('❌ Результат запиту з помилками');
           $this->nLog(($this->error));
        }
        if($curl_getinfo['content_type'] == 'text/html; charset=utf-8'
            || $curl_getinfo['content_type'] == 'text/plain; charset=utf-8'){
            return $res;
        }
        return json_decode( $res,1);
    }

    public function nLog($message = "Empty message"){
        $log = fopen(DIR_LOGS . 'checkbox.log', 'a+');

        if (!empty($_SERVER['REMOTE_ADDR'])){
            fwrite($log, date('Y-m-d G:i:s') . " [".$_SERVER['REMOTE_ADDR']."] : ");
        }    

        fwrite($log, print_r($message, true) ."\n");
        fclose($log); 
    }


    public function parse_date($date_str, $template = "Y-m-d H:i:s"){
        return date($template, strtotime($date_str) );
    } 
	
    private function convert2intX100($number = 0, $data = []){
        if($number){		
            if($this->config->get('receipt_price_format')){
                $number = $this->currency->format($number, $data['currency_code'], $data['currency_value'], false);
            }				   
            $number = (int)round(($number*100), 0);
        }
        
        return $number;
    }
}


if(!function_exists("de")){
    function de($var,$exit=false) {
        $my_print = '<div style="font-family: cursive;font-size: 14;border: 2px solid green;display: inline-block;white-space: pre;margin: 10px;">';
        $my_print .= '<div style="border: 2px solid blue;">';
        $my_print .= 'type - <b>'. gettype($var).'</b>';
        $my_print .= '<hr>count element - <b>'. count($var).'</b>';
        $my_print .= '</div>';
        @header('Content-Type: text/html; charset=utf-8');
        $my_print .= print_r($var, true);
        $my_print .= '</div>';
        echo $my_print;
        if($exit) exit();
    }
}
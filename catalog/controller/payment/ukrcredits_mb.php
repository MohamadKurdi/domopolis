<?php
class ControllerPaymentUkrcreditsmb extends Controller {
	
    public function index() {
		$this->language->load('module/ukrcredits');    	
		$this->load->model('module/ukrcredits');				
		$this->load->model('setting/extension');

		$setting = $this->config->get('ukrcredits_settings');
		$data['ukrcredits_setting'] = $this->config->get('ukrcredits_settings');

		$data['currency_left'] 	= $this->currency->getSymbolLeft($this->session->data['currency']);
		$data['currency_right'] = $this->currency->getSymbolRight($this->session->data['currency']);

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_mounth'] 	= $this->language->get('text_mounth');
		$data['text_loading'] 	= $this->language->get('text_loading');
		$data['text_payments'] 	= $this->language->get('text_payments');
		$data['text_per'] 		= $this->language->get('text_per');
		$data['text_total'] 	= $this->language->get('text_total');
		
		$data['text_success'] 	= $this->language->get('text_success');
		$data['success'] 		= $this->url->link('checkout/success', '');	
		
        $partsCount = 24;
		foreach ($this->cart->getProducts() as $cart) {
			$privat_query = $this->db->query("SELECT * FROM product_ukrcredits WHERE product_id = '" . (int)$cart['product_id'] . "'");
			if ($privat_query->row) {
				if ($privat_query->row['partscount_mb'] <= $partsCount && $privat_query->row['partscount_mb'] !=0) {
					$partsCount = (int)$privat_query->row['partscount_mb'];
				}
			}
		}

		if ($partsCount == 24) {
			$partsCount = $setting['mb_pq'];
		}
		
		$total_data = [];					
		$total 		= 0;
		$taxes 		= $this->cart->getTaxes();

		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = []; 

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}

				$sort_order = []; 

				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);
			}		
		}

		$data['total'] = $total;
		foreach ($total_data as $total_line){
			if ($total_line['code'] == 'total'){
				$data['total'] = $total_line['value_national'];
				break;
			}
		}

        $data['action'] = $this->url->link('payment/ukrcredits_mb/sendDataDeal', '');	
		
		$data['credit'] = array(
			'type' 			=> $setting['mb_merchantType'],
			'name' 			=> $this->language->get('text_title_'.mb_strtolower($setting['mb_merchantType'])),
			'partsCount' 	=> $partsCount,
			'price' 		=> $data['total']
		);
		
		if (isset($this->session->data['ukrcredits_mb_sel'])) {
			$data['credit']['partsCountSel'] = $this->session->data['ukrcredits_mb_sel'];
		} else {
			$data['credit']['partsCountSel'] = '';
		}
		
		$data['oc15'] 	= true;
		$this->data 	= $data;
		$this->template = 'payment/ukrcredits.tpl';		
		$this->render();
    }
    
	public function setUkrcreditsType(){
		$json = [];

		$this->language->load('module/ukrcredits');

		$this->session->data['payment_method']['title'] = $this->language->get('text_title_mb');
		$this->session->data['payment_method']['code'] = 'ukrcredits_mb';
		setcookie('payment_method', 'ukrcredits_mb', time() + 60 * 60 * 24 * 30);
		$this->session->data['ukrcredits_mb_sel'] = $this->request->post['partsCount'];
        $json['success'] = TRUE;
 
		if ($this->request->get['route'] != 'checkout/checkout') {
           $json['redirect'] = $this->url->link('checkout/checkout', '');
		}

		$this->response->setOutput(json_encode($json));
	}
	
    public function sendDataDeal(){		
        $setting = $this->config->get('ukrcredits_settings');
        $this->load->model('checkout/order');
		$this->load->model('module/ukrcredits');
		$this->load->model('setting/extension');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
            $data_deal['store_order_id'] = $order_info['order_id'];
			$data_deal['client_phone'] = str_replace(['(', ')', '-', ' '], '', $order_info['telephone']);

			$data_deal['invoice'] = array(
				'date' 		=> date($this->language->get('yy-m-d'), strtotime($order_info['date_added'])),
				'number' 	=> $order_info['order_id'],
				'point_id'	=> $order_info['store_id'],
				'source'	=> 'INTERNET'
			);
			
			$data_deal['available_programs'][] = array(
				'available_parts_count' => range(3, $this->request->post['partsCount']),
				'type'					=> 'payment_installments'
			);
			
			$data_deal['products'] = array();

			$this->load->model('setting/extension');

			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$sort_order = array(); 

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);

						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}

					$sort_order = array(); 

					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $total_data);
				}		
			}
			$totals = $total_data;


			$sumtotal = 0;
			$discount = 0;

			foreach ($totals as $total) {
				if (empty($total['value_national'])){
					$total['value_national'] = 0;
				}

				if (($total['code'] != 'sub_total') && ($total['code'] != 'total')) {
					if ($total['value'] > 0) {
						$data_deal['products'][] = [
							'name' 	=> htmlspecialchars_decode(trim($total['title'])),
							'count' => 1,
							'sum'   => $total['value_national']
						];						
						$sumtotal += $total['value_national'];
					} else {
						$discount += abs($total['value_national']);
					}
				}
			}

			$productquantity = $this->cart->countProducts();
			$minus = $discount / $productquantity;

            foreach ($this->cart->getProducts() as $product) {
				if (($product['price_national'] - $minus) <= 0) {
					$productquantity = $productquantity - $product['quantity'];
					$data_deal['products'][] = array(
						'name'     	=> htmlspecialchars(trim($product['name'])),
						'count' 	=> $product['quantity'],
						'sum'    	=> $product['price_national']
					);
					$sumtotal += $product['price_national'] * $product['quantity'];
				}
            }

			$minus = $discount / $productquantity;
            foreach ($this->cart->getProducts() as $product) {
				if (($product['price_national'] - $minus) > 0) {
					$data_deal['products'][] = array(
						'name'     	=> htmlspecialchars(trim($product['name'])),
						'count' 	=> $product['quantity'],
						'sum'    	=> $product['price_national'] - $minus
					);
					$sumtotal += ($product['price_national'] - $minus) * $product['quantity'];
				}
            }		
			
			$data_deal['total_sum'] = $sumtotal;						

			$data_deal['result_callback'] = $this->url->link('payment/ukrcredits_mb/callback', '');;

			$requestDial = json_encode($data_deal);
			
			$signature = base64_encode(hash_hmac("sha256", $requestDial, $setting['mb_shop_password'], true));

			$url = 'https://u2.monobank.com.ua/api/order/create';

			$responseResDeal = $this->model_module_ukrcredits->curlPostWithDataMB($url, $requestDial, $signature);
			
			if(is_array($responseResDeal)){
				if(isset($responseResDeal['order_id']) && $responseResDeal['order_id']) {
					$this->log->write('ukrcredits_mb получен ответ ' . $responseResDeal['order_id']);

					$this->language->load('extension/module/ukrcredits');

					$paymenttype 	= 'MB';
					$monostatus 	= 'IN_PROCESS';
					$monosubstatus 	= 'WAITING_FOR_CLIENT';
					$comment = $this->language->get('text_substatus_WAITING_FOR_CLIENT');

					if (!$this->model_checkout_order->getOrderMb($responseResDeal['order_id'])) {
						$this->model_checkout_order->setUkrcreditsOrderId($order_info['order_id'], $paymenttype, $responseResDeal['order_id'], $monostatus, $monosubstatus);
						$this->model_checkout_order->confirm($order_info['order_id'], $setting['clientwait_status_id'], $comment, $notify = true);
					}
					
				} elseif (isset($responseResDeal['message']) && $responseResDeal['message']) {
					$this->log->write('ukrcredits_mb получен отказ ' . $responseResDeal['message']);
				}
			}

			echo json_encode($responseResDeal);
        }
    }

    public function callback() {		
    	$this->language->load('module/ukrcredits');
        $setting = $this->config->get('ukrcredits_settings');	

        $log = new \Log('ukrcredits_mb.txt');	 
        
        $requestPostRaw = file_get_contents('php://input');        
        $requestArr = json_decode(trim($requestPostRaw),true);

        $log->write($requestPostRaw);

        $this->load->model('checkout/order');
		
		$order_mono = $this->model_checkout_order->getOrderMb($requestArr['order_id']);
		$order_info = $this->model_checkout_order->getOrder($order_mono['order_id']);

        if ($order_info) {        
            if ($requestArr['state']) {
                switch($requestArr['state']) {
                  case 'SUCCESS':
					switch($requestArr['order_sub_state']) {
						case 'ACTIVE':
							$order_status_id = $setting['completed_status_id'];
							$comment = $this->language->get('text_substatus_ACTIVE');
							break;
						case 'DONE':
							$order_status_id = $setting['completed_status_id'];
							$comment = $this->language->get('text_substatus_DONE');
							break;
						case 'SUCCESS':
							$order_status_id = $setting['completed_status_id'];
							$comment = $this->language->get('text_substatus_SUCCESS');
							break;
					}
					$this->log->write('ukrcredits_mb :: PAYMENT SUCCESS!  ORDER_ID:'. $order_info['order_id'] .' MESSAGE:'. $requestArr['message']);
					break;
                  case 'FAIL':
					switch($requestArr['order_sub_state']) {
						case 'CLIENT_NOT_FOUND':
							$order_status_id = $setting['rejected_status_id'];
							$comment = $this->language->get('text_substatus_CLIENT_NOT_FOUND');
							break;						
						case 'EXCEEDED_SUM_LIMIT':
							$order_status_id = $setting['rejected_status_id'];
							$comment = $this->language->get('text_substatus_EXCEEDED_SUM_LIMIT');
							break;
						case 'EXISTS_OTHER_OPEN_ORDER':
							$order_status_id = $setting['rejected_status_id'];
							$comment = $this->language->get('text_substatus_EXISTS_OTHER_OPEN_ORDER');
							break;
						case 'FAIL':
							$order_status_id = $setting['failed_status_id'];
							$comment = $this->language->get('text_substatus_FAIL');
							break;
						case 'NOT_ENOUGH_MONEY_FOR_INIT_DEBIT':
							$order_status_id = $setting['rejected_status_id'];
							$comment = $this->language->get('text_substatus_NOT_ENOUGH_MONEY_FOR_INIT_DEBIT');
							break;
						case 'REJECTED_BY_CLIENT':
							$order_status_id = $setting['rejected_status_id'];
							$comment = $this->language->get('text_substatus_REJECTED_BY_CLIENT');
							break;
						case 'CLIENT_PUSH_TIMEOUT':
							$order_status_id = $setting['rejected_status_id'];
							$comment = $this->language->get('text_substatus_CLIENT_PUSH_TIMEOUT');
							break;
						case 'REJECTED_BY_STORE':
							$order_status_id = $setting['canceled_status_id'];
							$comment = $this->language->get('text_substatus_REJECTED_BY_STORE');
							break;							
					}
					$this->log->write('ukrcredits_mb :: PAYMENT FAIL!  ORDER_ID:'. $order_info['order_id'] .' MESSAGE:'. $requestArr['message']);
					break;  
                  case 'IN_PROCESS':
					switch($requestArr['order_sub_state']) {
						case 'WAITING_FOR_CLIENT':
							$order_status_id = $setting['clientwait_status_id'];
							$comment = $this->language->get('text_substatus_WAITING_FOR_CLIENT');
							break;
						case 'WAITING_FOR_STORE_CONFIRM':
							$order_status_id = $setting['completed_status_id'];
							$comment = $this->language->get('text_substatus_WAITING_FOR_STORE_CONFIRM');
							break;
					}
					$this->log->write('ukrcredits_mb :: PAYMENT IN_PROCESS!  ORDER_ID:'. $order_info['order_id'] .' MESSAGE:'. $requestArr['message']);
					break;
                }
				
				$this->model_checkout_order->updateUkrcreditsOrderMono($order_info['order_id'], $requestArr['state'], $requestArr['order_sub_state']);
				$this->model_checkout_order->update($order_info['order_id'], $order_status_id, $comment, $notify = true);

				if ($order_status_id == $setting['completed_status_id']){
					$this->model_checkout_order->addOrderToQueue($order_id);            
				}	              
                
            } else {
                $this->log->write('ukrcredits_mb :: Статус не получен!  ORDER_ID:'.$order_id .' RECEIVED:'. $requestArr['signature']);
            } 
        }
    }
}
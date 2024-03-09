<?

class ControllerSaleUkrCredits extends Controller {

	public function askstatus_mb (){
		$setting = $this->config->get('ukrcredits_settings');
		
		if (isset($this->request->get['ukrcredits_order_id'])) {
			$ukrcredits_order_id = $this->request->get['ukrcredits_order_id'];
		} else {
			$ukrcredits_order_id = 0;
		}
		
		$data_deal['order_id'] = $ukrcredits_order_id;
		$requestDial = json_encode($data_deal);
		$signature = base64_encode(hash_hmac("sha256", $requestDial, $setting['mb_shop_password'], true));
		$url = 'https://u2.monobank.com.ua/api/order/state';
		$responseResDeal = $this->curlPostWithDataMB($url, $requestDial, $signature);
		
		if(is_array($responseResDeal)){
			if(isset($responseResDeal['order_id']) && $responseResDeal['order_id']) {
				$this->log->write('ukrcredits_mb успешный запрос состояния заказа ' . $responseResDeal['order_id']);
				$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = '" . $this->db->escape($responseResDeal['state']) . "', ukrcredits_order_substatus = '" . $this->db->escape($responseResDeal['order_sub_state']) . "' WHERE ukrcredits_order_id = '" . $this->db->escape($responseResDeal['order_id']) . "'");
			} elseif (isset($responseResDeal['message']) && $responseResDeal['message']) {
				$this->log->write('ukrcredits_mb по время запроса произошла ошибка ' . $responseResDeal['message']);
			}
			echo  json_encode($responseResDeal);         
		} else {
			echo json_encode(array('state'=>'sys_error','message'=>$responseResDeal));
		}
	}
	
	public function cancelhold_mb (){
		$setting = $this->config->get('ukrcredits_settings');
		$this->language->load('sale/ukrcreditsorder');
		
		if (isset($this->request->get['ukrcredits_order_id'])) {
			$ukrcredits_order_id = $this->request->get['ukrcredits_order_id'];
		} else {
			$ukrcredits_order_id = 0;
		}
		
		$data_deal['order_id'] = $ukrcredits_order_id;
		$requestDial = json_encode($data_deal);
		$signature = base64_encode(hash_hmac("sha256", $requestDial, $setting['mb_shop_password'], true));
		$url = 'https://u2.monobank.com.ua/api/order/reject';
		$responseResDeal = $this->curlPostWithDataMB($url, $requestDial, $signature);
		
		if(is_array($responseResDeal)){
			if(isset($responseResDeal['order_id']) && $responseResDeal['order_id']) {
				$this->log->write('ukrcredits_mb успешная отмена заказа ' . $responseResDeal['order_id']);
				$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = '" . $this->db->escape($responseResDeal['state']) . "', ukrcredits_order_substatus = '" . $this->db->escape($responseResDeal['order_sub_state']) . "' WHERE ukrcredits_order_id = '" . $this->db->escape($responseResDeal['order_id']) . "'");
				$responseResDeal['order_status_id'] = $setting['canceled_status_id'];
				$responseResDeal['comment'] = $this->language->get('text_order_canceled');
				
			} elseif (isset($responseResDeal['message']) && $responseResDeal['message']) {
				$this->log->write('ukrcredits_mb по время запроса произошла ошибка ' . $responseResDeal['message']);
			}
			echo  json_encode($responseResDeal);
		} else {
			echo json_encode(array('state'=>'sys_error','message'=>$responseResDeal));
		}
	}
	
	public function confirmhold_mb (){
		$setting = $this->config->get('ukrcredits_settings');
		$this->language->load('sale/ukrcreditsorder');
		
		if (isset($this->request->get['ukrcredits_order_id'])) {
			$ukrcredits_order_id = $this->request->get['ukrcredits_order_id'];
		} else {
			$ukrcredits_order_id = 0;
		}
		
		$data_deal['order_id'] = $ukrcredits_order_id;
		$requestDial = json_encode($data_deal);
		$signature = base64_encode(hash_hmac("sha256", $requestDial, $setting['mb_shop_password'], true));
		$url = 'https://u2.monobank.com.ua/api/order/confirm';
		$responseResDeal = $this->curlPostWithDataMB($url, $requestDial, $signature);
		
		if(is_array($responseResDeal)){
			if(isset($responseResDeal['order_id']) && $responseResDeal['order_id']) {
				$this->log->write('ukrcredits_mb успешное подтверждение заказа ' . $responseResDeal['order_id']);
				$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = '" . $this->db->escape($responseResDeal['state']) . "', ukrcredits_order_substatus = '" . $this->db->escape($responseResDeal['order_sub_state']) . "' WHERE ukrcredits_order_id = '" . $this->db->escape($responseResDeal['order_id']) . "'");
				$responseResDeal['order_status_id'] = $setting['completed_status_id'];
				$responseResDeal['comment'] = $this->language->get('text_order_confirmed');
				
			} elseif (isset($responseResDeal['message']) && $responseResDeal['message']) {
				$this->log->write('ukrcredits_mb по время запроса произошла ошибка ' . $responseResDeal['message']);
			}
			echo  json_encode($responseResDeal);
		} else {
			echo json_encode(array('state'=>'sys_error','message'=>$responseResDeal));
		}
	}	
	
	public function askstatus (){
		$setting = $this->config->get('ukrcredits_settings');
		
		if (isset($this->request->get['ukrcredits_order_id'])) {
			$ukrcredits_order_id = $this->request->get['ukrcredits_order_id'];
			$payment_code = $this->request->get['payment_code'];
		} else {
			$ukrcredits_order_id = 0;
			$payment_code = 0;
		}
		
		$data_deal['storeId'] = $setting[substr($payment_code, -2) . '_shop_id'];
		$data_deal['orderId'] = $ukrcredits_order_id;
		$data_deal['showRefund'] = true;
		$data_deal['showAmount'] = true;
		$data_deal['signature'] = $this->generateSignature($payment_code, $data_deal);

		$requestDial = json_encode($data_deal);       
		$url = 'https://payparts2.privatbank.ua/ipp/v2/payment/state';     
		
		$responseResDeal = $this->curlPostWithData($url,$requestDial);

		if(is_array($responseResDeal)){
			if(strcmp($responseResDeal['state'], 'FAIL') == 0){
				$this->log->write('PRIVATBANK_PAYMENTPARTS :: REFRESH STATUS failed: ' . json_encode($responseResDeal));                
			}            
			if(strcmp($responseResDeal['state'], 'SUCCESS') == 0){
				$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = '" . $this->db->escape($responseResDeal['paymentState']) . "' WHERE ukrcredits_order_id = '" . $this->db->escape($ukrcredits_order_id) . "'");				
			}   
			echo  json_encode($responseResDeal);         
		} else {
			echo json_encode(array('state'=>'sys_error','message'=>$responseResDeal));
		}
	}	
	
	public function confirmhold (){
		$setting = $this->config->get('ukrcredits_settings');
		
		$this->language->load('sale/ukrcreditsorder');
		
		if (isset($this->request->get['ukrcredits_order_id'])) {
			$ukrcredits_order_id = $this->request->get['ukrcredits_order_id'];
			$payment_code = $this->request->get['payment_code'];
		} else {
			$ukrcredits_order_id = 0;
			$payment_code = 0;
		}
		
		$data_deal['storeId'] = $setting[substr($payment_code, -2) . '_shop_id'];
		$data_deal['orderId'] = $ukrcredits_order_id;
		$data_deal['signature'] = $this->generateSignature($payment_code, $data_deal);

		$requestDial = json_encode($data_deal);       
		$url = 'https://payparts2.privatbank.ua/ipp/v2/payment/confirm';     
		
		$responseResDeal = $this->curlPostWithData($url,$requestDial);

		if(is_array($responseResDeal)){
			if(strcmp($responseResDeal['state'], 'FAIL') == 0){
				$this->log->write('PRIVATBANK_PAYMENTPARTS :: CONFIRM failed: ' . json_encode($responseResDeal));                
			}            
			if(strcmp($responseResDeal['state'], 'SUCCESS') == 0){
				$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = 'SUCCESS' WHERE ukrcredits_order_id = '" . $this->db->escape($ukrcredits_order_id) . "'");				
				$responseResDeal['order_status_id'] = $setting['completed_status_id'];
				$responseResDeal['comment'] = $this->language->get('text_order_confirmed');
			}   
			echo  json_encode($responseResDeal);         
		} else {
			echo json_encode(array('state'=>'sys_error','message'=>$responseResDeal));
		}
	}
	
	public function cancelhold (){
		$setting = $this->config->get('ukrcredits_settings');
		
		$this->language->load('sale/ukrcreditsorder');
		
		if (isset($this->request->get['ukrcredits_order_id'])) {
			$ukrcredits_order_id = $this->request->get['ukrcredits_order_id'];
			$payment_code = $this->request->get['payment_code'];
		} else {
			$ukrcredits_order_id = 0;
			$payment_code = 0;
		}
		
		$data_deal['storeId'] = $setting[substr($payment_code, -2) . '_shop_id'];
		$data_deal['orderId'] = $ukrcredits_order_id;
		$data_deal['signature'] = $this->generateSignature($payment_code, $data_deal);

		$requestDial = json_encode($data_deal);       
		$url = 'https://payparts2.privatbank.ua/ipp/v2/payment/cancel';     
		
		$responseResDeal = $this->curlPostWithData($url,$requestDial);

		if(is_array($responseResDeal)){
			if(strcmp($responseResDeal['state'], 'FAIL') == 0){
				$this->log->write('PRIVATBANK_PAYMENTPARTS :: CANCEL failed: ' . json_encode($responseResDeal));                
			}            
			if(strcmp($responseResDeal['state'], 'SUCCESS') == 0){
				$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = 'CANCELED' WHERE ukrcredits_order_id = '" . $this->db->escape($ukrcredits_order_id) . "'");				
				$responseResDeal['order_status_id'] = $setting['canceled_status_id'];
				$responseResDeal['comment'] = $this->language->get('text_order_canceled');
			}   

			echo  json_encode($responseResDeal);         
		} else {
			echo json_encode(array('state'=>'sys_error','message'=>$responseResDeal));
		}
	}
	
	private function generateSignature ($payment_code, $dataAnsweArr){
		$setting = $this->config->get('ukrcredits_settings');
		$passwordStore = $setting[substr($payment_code, -2) . '_shop_password'];
		
		$signatureAnswerStr = $passwordStore.
		$dataAnsweArr['storeId'].
		$dataAnsweArr['orderId'].
		$passwordStore;
		
		$signatureAnswer = base64_encode(SHA1($signatureAnswerStr, true));
		
		return $signatureAnswer;
		
	}
	
	private function curlPostWithData($url, $request) {
		try {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Accept-Encoding: UTF-8','Content-Type: application/json; charset=UTF-8'));
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($curl);
			$curl_errno = curl_errno($curl);
			$curl_error = curl_error($curl);
			$aInfo = @curl_getinfo($curl);            
			//close curl
			curl_close($curl);          
			//analysis of the information received
			
			if($curl_errno!=0){
				$this->log->write('PRIVATBANK_PAYMENTPARTS :: CURL failed ' . $curl_error . '(' . $curl_errno . ')');  
				return 'error';
			} 
			if($aInfo["http_code"]!='200'){
				$this->log->write('PRIVATBANK_PAYMENTPARTS :: HTTP failed ' . $aInfo["http_code"] . '(' . $response . ')');  
				return 'error';
			} 
			return json_decode($response,true);
			
		} catch(Exception $e){
			return false;
		}
	}
	
	private function curlPostWithDataMB($url, $requestDial, $signature) {
		$setting = $this->config->get('ukrcredits_settings');
		try{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestDial);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Accept-Encoding: UTF-8',
				'Content-Type: application/json; charset=UTF-8',
				'store-id: '.$setting['mb_shop_id'],
				'signature: '.$signature
			));

			//execute curl
			$response = curl_exec($ch);

			//get execute result
			$curl_errno = curl_errno($ch);
			$curl_error = curl_error($ch);
			$aInfo = @curl_getinfo($ch);            
			//close curl
			curl_close($ch);          
			//analysis of the information received
			$dir = version_compare(VERSION,'2.3','>=') ? 'extension/module' : 'module';
			$this->language->load($dir.'/ukrcredits');   
			if($curl_errno!=0){
				$this->log->write('ukrcredits_mb :: CURL failed ' . $curl_error . '(' . $curl_errno . ')');  
				return $this->language->get('error_curl');
			} 
			return json_decode($response,true);
			
		} catch(Exception $e){
			return false;
		}
	}

}
<?
class ModelKpInfo1C extends Model {		
	private $SoapClient;
	
	private function SoapConnectTo1C($wsdl = true){
		
		ini_set("soap.wsdl_cache_enabled", "0" ); 
		ini_set('default_socket_timeout', '1480');
		libxml_disable_entity_loader(false);
		
		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			]
		]);

		$SoapURI = $this->config->get('config_odinass_soap_uri');
		
		if ($wsdl){
			$SoapURI .= '?wsdl';
		}

		$this->SoapClient = new SoapClient($SoapURI,
			
			array(
				'login' => $this->config->get('config_odinass_soap_user'),
				'password' => $this->config->get('config_odinass_soap_passwd'),
				'soap_version' => SOAP_1_2,
				'connection_timeout' => 1480,
				'verifypeer' => false,
				'verifyhost' => false,
				'cache_wsdl' => WSDL_CACHE_NONE,
				'exceptions' => 1,
				'trace' => true,	
				'stream_context' => $context
			)
		);
		
	}

	public function ping1CToUpdateProducts($product_ids){

		$this->SoapConnectTo1C();

		try {
			
			$data = array(
				'products'         => $product_ids				
			);								

			$data = json_encode($data);

			//	echoLine('Отправляем JSON:' . $data);
			$result = $this->SoapClient->updateproduct(['id' => $data]); 
			return $result;

			
		} catch (Exception $e){												
			//	print_r($e->getMessage());
			//	var_dump($this->SoapClient->__getLastRequest());
			//	return ('Что-то пошло не так. ' . $e->getMessage());
		}

	}
	
	public function passWebServiceProxy($function, $param_name, $string_data){
		
		$this->SoapConnectTo1C();
		
		try {
			
			$data = array(
				$param_name => (string)$string_data,				
			);	
			
			
			$result = $this->SoapClient->{$function}($data); 
			
		} catch (Exception $e){															
			return('Что-то пошло не так. ' . $e->getMessage());
		}
		
		return $result->return;
	}
	
	public function getLegalPersonAccountFrom1C($legalperson_id){
		
		$this->SoapConnectTo1C();
		
		try {
			
			$data = array(
				'id'         => $legalperson_id,				
			);								
			
			$result = $this->SoapClient->bankbalance($data); 
			
		} catch (Exception $e){												
			print_r($e->getMessage());
			return('Что-то пошло не так. ' . $e->getMessage());
		}
		
		$jsResult = $result->return;
		
		$constants = get_defined_constants(true);
		$json_errors = array();
		foreach ($constants["json"] as $name => $value) {
			if (!strncmp($name, "JSON_ERROR_", 11)) {
				$json_errors[$value] = $name;
			}
		}			
		
		if ($json = json_decode($jsResult, true)){
			return $json;
		} else {
			echo $json_errors[json_last_error()];
			return false;
		}
		
		return $json;
		
	}
	
	public function getLocalPricesXML(){
		
		$this->SoapConnectTo1C();
		
		echo 'TRYING pricelocal' . PHP_EOL;
		try {
			
			$result = $this->SoapClient->pricelocal(); 
			
		} catch (Exception $e){												
			print_r($e->getMessage());
			die();
			return('Что-то пошло не так. ' . $e->getMessage());
		}
		
		
		$jsResult = $result->return;				
		
		$constants = get_defined_constants(true);
		$json_errors = array();
		foreach ($constants["json"] as $name => $value) {
			if (!strncmp($name, "JSON_ERROR_", 11)) {
				$json_errors[$value] = $name;
			}
		}			
		
		if ($json = json_decode($jsResult, true)){
			return $json;
		} else {
			echo $json_errors[json_last_error()];
			return false;
		}
		
		return $json;
		
	}
	
	public function getOrderTrackerXML($order_id){
		
		$this->SoapConnectTo1C();
		
		try {
			
			$result2 = $this->SoapClient->tracker(array('order_id' => $order_id)); 
			
		} catch (Exception $e){
			print_r($e->getMessage());
			return('Что-то пошло не так. ' . $e->getMessage());
		}
		
		$xml = $result2->return;
		
		if ($xml == 'NonOrder'){
			return false;
		}
		
		
		require_once(DIR_SYSTEM . 'library/XML2Array.php');
		$xml2array = new XML2Array();
		
			//	$xml = htmlspecialchars_decode($xml);
		
		try {
			$input = $xml2array->createArray($xml);				
		} catch (Exception $e){												
			return ('Ошибка разбора XML. ' . $e->getMessage());
		}			
		
		return $input;	
		
	}
	
	public function getStockWaitsFrom1C(){
		
		$this->SoapConnectTo1C();
		
		try {
			
			$result = $this->SoapClient->stockwait(); 
			
		} catch (Exception $e){
			print_r($e->getMessage());
			die();
		}
		
		$jsResult = $result->return;
		
		return $jsResult;
	}
	
	
	public function getStocksFrom1C(){
		
		$this->SoapConnectTo1C();
		
		try {
			
			$result = $this->SoapClient->stock(array('organisation' => SITE_NAMESPACE)); 
			
		} catch (Exception $e){
			print_r($e->getMessage());
			die();
		}
		
		$jsResult = $result->return;					
		
		return $jsResult;
		
	}
	
	public function getActualCostFrom1C(){
		
		$this->SoapConnectTo1C();
		
		try {
			
			$result = $this->SoapClient->price(); 
			
		} catch (Exception $e){
			print_r($e->getMessage());
			die();
		}
		
		$jsResult = $result->return;
		
		$constants = get_defined_constants(true);
		$json_errors = array();
		foreach ($constants["json"] as $name => $value) {
			if (!strncmp($name, "JSON_ERROR_", 11)) {
				$json_errors[$value] = $name;
			}
		}			
		
		if ($json = json_decode($jsResult, true)){
			return $json;
		} else {
			echo $json_errors[json_last_error()];
			return false;
		}
		
		return $json;
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}				
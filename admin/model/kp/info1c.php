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
			
			$data = [
				'id' => [
					'products'         => $product_ids,			
				],
				'organisation' => SITE_NAMESPACE
			];								

			$data = json_encode($data);
			$result = $this->SoapClient->updateproduct($data); 
			return $result;
			
		} catch (Exception $e){												
			echoLine($e->getMessage());
			return('SOAP ERROR: ' . $e->getMessage());
		}
	}
	
	public function passWebServiceProxy($function, $param_name, $string_data){		
		$this->SoapConnectTo1C();
		
		try {
			
			$data = [
				$param_name => (string)$string_data,				
			];	
						
			$result = $this->SoapClient->{$function}($data); 
			
		} catch (Exception $e){															
			return('SOAP ERROR: ' . $e->getMessage());
		}
		
		return $result->return;
	}
	
	public function getLegalPersonAccountFrom1C($legalperson_id){		
		$this->SoapConnectTo1C();
		
		try {
			
			$data = [
				'id'         => $legalperson_id,	
				'organisation' => SITE_NAMESPACE			
			];								
			
			$result = $this->SoapClient->bankbalance($data); 
			
		} catch (Exception $e){												
			echoLine($e->getMessage());
			return('SOAP ERROR: ' . $e->getMessage());
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

		try {	
			$data = [
				'organisation' => SITE_NAMESPACE
			];

			$result = $this->SoapClient->pricelocal($data); 
		} catch (Exception $e){												
			echoLine($e->getMessage(), 'e');
			return('SOAP ERROR: ' . $e->getMessage());
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

			$data = [
				'organisation' 	=> SITE_NAMESPACE,
				'order_id' 		=> $order_id
			];
			
			$result2 = $this->SoapClient->tracker($data); 
			
		} catch (Exception $e){
			echoLine($e->getMessage());
			return('SOAP ERROR: ' . $e->getMessage());
		}
		
		$xml = $result2->return;
		
		if ($xml == 'NonOrder'){
			return false;
		}
					
		try {
			$xtoa  = new \AlexTartan\Array2Xml\XmlToArray(['version'=>'1.0','encoding'=>'UTF-8','attributesKey' => '@attributes','cdataKey'=>'@cdata','valueKey'=>'@value','formatOutput'  => false]);
            $input = $xtoa->buildArrayFromString($xml);			
		} catch (\AlexTartan\Array2Xml\Exception\ConversionException $e){												
			return ('Ошибка разбора XML. ' . $e->getMessage());
		}			
		
		return $input;			
	}
	
	public function getStockWaitsFrom1C(){		
		$this->SoapConnectTo1C();
		
		try {	
			$data = [
				'organisation' => SITE_NAMESPACE
			];

			$result = $this->SoapClient->stockwait($data); 
		} catch (Exception $e){
			echoLine($e->getMessage(), 'e');
			return false;
		}
		
		$jsResult = $result->return;
		
		return $jsResult;
	}
	
	public function getStocksFrom1C(){		
		$this->SoapConnectTo1C();

		echoLine('Calling getStocksFrom1C, soap function stock', 'i');
		
		try {		
			$data = [
				'organisation' => SITE_NAMESPACE
			];

			echoLine('Passing data as array: ' . json_encode($data), 'i');

			$result = $this->SoapClient->stock($data); 			
		} catch (Exception $e){
			echoLine($e->getMessage());
			return false;
		}
		
		$jsResult = $result->return;					
		
		return $jsResult;		
	}
	
	public function getActualCostFrom1C(){		
		$this->SoapConnectTo1C();
		
		try {			
			$data = [
				'organisation' => SITE_NAMESPACE
			];

			$result = $this->SoapClient->price($data); 			
		} catch (Exception $e){
			echoLine($e->getMessage(), 'e');
			return('SOAP ERROR: ' . $e->getMessage());
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
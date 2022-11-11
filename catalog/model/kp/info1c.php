<?
	class ModelKpInfo1C extends Model {		
		private $SoapClient;
		
		private function SoapConnectTo1C(){
			
			ini_set("soap.wsdl_cache_enabled", "0" ); 
			
			$this->SoapClient = new SoapClient("https://1capi.ims-group.de/api/ws/exchange?wsdl",
			array(
			'login' => "SOAPUSER", //логин пользователя к базе 1С
			'password' => "ceRqcNfoZL5CC0Wa", //пароль пользователя к базе 1С
			'soap_version' => SOAP_1_2, //версия SOAP
			'cache_wsdl' => WSDL_CACHE_NONE,
			'exceptions' => 1,
			'trace' => true,			
			)
			);
			
		}
		
		public function getOrderTrackerXML($order_id){
			
			
			
			try {
				$this->SoapConnectTo1C();
				
				$result2 = $this->SoapClient->tracker(array('order_id' => $order_id)); 
				
				} catch (Exception $e){
				
				print_r($e->getMessage());
				return false;
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
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}	
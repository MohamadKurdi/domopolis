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
			[
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
			]);
			
		}
		
		public function getOrderTrackerXML($order_id){
			try {
				$this->SoapConnectTo1C();
				
				$result2 = $this->SoapClient->tracker(array('order_id' => $order_id)); 
				
				} catch (Exception $e){
				return false;
			}
			
			$xml = $result2->return;
			
			if ($xml == 'NonOrder'){
				return false;
			}
			
			require_once(DIR_SYSTEM . 'library/XML2Array.php');
			$xml2array = new XML2Array();
			
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
				die();
			}
			
			$jsResult = $result->return;
			
			return $jsResult;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}	
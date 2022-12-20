<?

class ControllerApiDeliveryServices extends Controller { 

	private function removeBrackets($line){
		if (strpos($line, '(')){
			$exploded = explode('(', $line);
			$line = $exploded[0];
		}
		$line = trim(str_replace("'", "`", $line));

		return trim($line);
	}

	public function index(){

	}

	public function cron(){
		require_once(DIR_SYSTEM . 'library/deliveryapis/NovaPoshta.php');	


		if ($this->config->get('config_justin_api_key')){
			try {
				require_once(DIR_SYSTEM . 'library/deliveryapis/Justin.php');	

				$justin = new Justin($this->registry, 'RU');
				$justin->updateZones()->updateZonesRegions()->updateCities()->updateCitiesRegions()->updateStreets()->updateWarehouses();
				
				$justin = new Justin($this->registry, 'UA');
				$justin->updateZones()->updateZonesRegions()->updateCities()->updateCitiesRegions()->updateStreets()->updateWarehouses();
				
			} catch (\Justin\Exceptions\JustinHttpException $e){
				echoLine('JustinHttpException:' . $e->getMessage());
			}
		}

		if ($this->config->get('cdek_login')){
			require_once(DIR_SYSTEM . 'library/deliveryapis/Cdek.php');	
			$cdek = new Cdek($this->registry);			
			$cdek->updateZones('RU')->updateZones('KZ')->updateZones('BY')->updateCities()->updateDeliveryPoints()->updateBeltWayHits();
		}

		if ($this->config->get('config_novaposhta_api_key')){
			$novaPoshta = new NovaPoshta($this->registry);		
			$novaPoshta->updateStreets();							
			//$novaPoshta->updateZones()->updateCities()->updateCitiesWW()->updateWareHouses()->updateStreets();
		}


	}		


}				
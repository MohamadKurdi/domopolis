<?

class ControllerApiDeliveryServices extends Controller { 
	public function index(){
		if (!is_cli()){
			die('CLI ONLY');
		}
	}

	public function cron(){
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
			$this->courierServices->updateReferences('NovaPoshta');
		}
	}		
}				
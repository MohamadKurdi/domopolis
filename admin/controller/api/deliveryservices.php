<?

class ControllerApiDeliveryServices extends Controller { 
	public function index(){
		if (!is_cli()){
			die('CLI ONLY');
		}
	}

	public function cron(){
		if ($this->config->get('config_justin_api_key')){
			$this->courierServices->updateReferences('Justin');					
		}

		if ($this->config->get('cdek_login')){
			$this->courierServices->updateReferences('Cdek');
		}

		if ($this->config->get('config_novaposhta_api_key')){
			$this->courierServices->updateReferences('NovaPoshta');
		}
	}		
}				
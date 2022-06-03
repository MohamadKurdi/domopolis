<?
	
	class ControllerKPSwitchVersion extends Controller {
		
		private function checkIfNowIsNewVersion(){
			
			return ($this->config->get('config_template') == 'kp');
			
		}
		
		public function index(){
		
		
		
		}

	}
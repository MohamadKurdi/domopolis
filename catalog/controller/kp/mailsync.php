<?

class ControllerKPMailSync extends Controller {	
	public function sync(){
		if(!is_cli()){
			die('cli only');	
		}

		ini_set('memory_limit', '5G');

		$this->mailAdaptor->sync();
	}
}
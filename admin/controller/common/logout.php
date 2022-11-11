<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
		$this->user->logout();

		unset($this->session->data['token']);
		
		if ( $this->config->get('secure_status') &&  $this->config->get('secure_status') == 1 ) {			
			$db_skey    =  $this->config->get('secure_key');
			$db_sval    =  $this->config->get('secure_value');
			unset($this->session->data[$db_skey]);
			unset($this->session->data[$db_sval]);
		}

		$this->redirect($this->url->link('common/login', '', 'SSL'));
	}
}  
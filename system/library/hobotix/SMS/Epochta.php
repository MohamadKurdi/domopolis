<?

namespace hobotix\SMS;

class Epochta {

	private $ePochta = null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

		$this->ePochta = new \Enniel\Epochta\SMS(['public_key' => $this->config->get('config_smsgate_secret_key'), 'private_key' => $this->config->get('config_smsgate_api_key')]);
	}

	public function check($answer){
		$answer = json_decode($answer, true);

		if (!$answer){
			echoLine('[Epochta::check] Error decoding JSON!', 'e');
			return false;
		}

		if (!empty($answer['error'])){
			echoLine('[Epochta::check] Error! ' . $answer['error'], 'e');
			return false;
		}

		return true;
	}


	public function getBalance(){
		$balance = $this->ePochta->getUserBalance()->getBody()->getContents();	    
	
		if ($this->check($balance)){
			$balance = json_decode($balance, true);
			return (float)$balance['result']['balance_currency'];
		}

		return false;
	}


	public function sendSMS($sms){
		$answer = $this->ePochta->sendSMS([
			'sender'		=> $this->config->get('config_sms_from'),
			'text'			=> $sms['message'],
			'phone'			=> preparePhone($sms['to']),
			'type' 			=> '2',
			'datetime' 		=> '',
			'sms_lifetime'  => '0',
		])->getBody()->getContents();		

		if ($this->check($answer)){
			$answer = json_decode($answer, true);
			return (float)$answer['result']['id'];
		}

		return false;
	}
}
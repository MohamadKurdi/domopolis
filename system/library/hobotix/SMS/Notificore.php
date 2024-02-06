<?

namespace hobotix\SMS;

class Notificore {

	private $Notificore = null;
	private $ePochta 	= null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

		$this->Notificore = new \Notificore\Notificore($this->config->get('config_smsgate_api_key'), $this->config->get('config_sms_from'));		
	}

	private function request($endpoint, $data){


	}

	public function check($answer){
		$answer = json_encode($answer);
		$answer = json_decode($answer, true);

		if (!empty($answer['error'])){
			echoLine('[Notificore::check] Error! ' . $answer['errorDescription'], 'e');
			return false;
		}

		return true;
	}


	public function getBalance(){
		$balance = $this->Notificore->getSmsClient()->getBalance();
	
		if ($this->check($balance)){
			return (float)$balance['amount'];
		}

		return false;
	}


	public function sendSMS($sms){
		$answer = $this->Notificore->getSmsClient()->sendSmsMulti([[
    		'msisdn' 		=> preparePhone($sms['to']),
    		'body' 			=> $sms['message'], 
    		'reference' 	=> $this->config->get('config_sms_from') . preg_replace('/[^0-9]/', '',microtime(true)),
    		'originator' 	=> $this->config->get('config_sms_from')
    	]]);

		if ($this->check($answer['result'][0])){
			return (float)$answer['result'][0]['id'];
		}

		return false;
	}
}
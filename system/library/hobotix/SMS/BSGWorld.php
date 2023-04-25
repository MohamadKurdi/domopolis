<?

namespace hobotix\SMS;

class BSGWorld {

	private $BSG = null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

		$this->BSG = new \BSG($this->config->get('config_smsgate_api_key'));
	}

	public function check($answer){
		if (!empty($answer['error'])){
			var_dump($answer);

			echoLine('[BSGWorld::check] Error! ' . $answer['errorDescription'], 'e');
			return false;
		}

		return true;
	}


	public function getBalance(){
		$balance = $this->BSG->getSmsClient()->getBalance();

		if ($this->check($balance)){
			return (float)$balance['amount'];
		}

		return false;
	}


	public function sendSMS($sms){
		$answer = $this->BSG->getSmsClient()->sendSmsMulti([[
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
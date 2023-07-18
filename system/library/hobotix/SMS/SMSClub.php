<?

namespace hobotix\SMS;

class SMSClub {

	private $smsClub = null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

	//	$this->smsClub = new \SmsSender\SmsSender($this->config->get('config_smsgate_api_key'));
		$this->smsClub = new \SmsSender\SmsSender('R00ZxLaDM6mEIlw');
	}

	public function check($answer){
		$answer = json_encode($answer);
		$answer = json_decode($answer, true);

		if (!$answer){
			echoLine('[SMSClub::check] Error decoding JSON!', 'e');
			return false;
		}		

		if (!empty($answer['error'])){
			echoLine('[SMSClub::check] Error! ' . $answer['error'], 'e');
			return false;
		}

		return true;
	}


	public function getBalance(){
		try {
			$balance = $this->smsClub->getBalance();	

			if ($this->check($balance)){
				$balance 	= json_encode($balance);
				$balance 	= json_decode($balance, true);
				return (float)$balance['money'];
			}

		} catch (\SmsSender\Exceptions\SmsSenderException $e) {
			echoLine('[SMSClub::sendSMS] Exception ' . $e->getMessage(), 'e');
			return $e->getMessage();
		}

		return false;
	}


	public function sendSMS($sms){
		echoLine('[SMSClub::sendSMS] Sending text to number ' . preparePhone($sms['to']), 'i');
		try {
			$signatures = $this->smsClub->getSignatures();

			if (empty($signatures)){
				echoLine('[SMSClub::sendSMS] Empty signatures list!', 'e');
				return false;
			}

			$answer = $this->smsClub->smsSend(
				$signatures[0], 
				$sms['message'], 
				[preparePhone($sms['to'])]
			);

			if ($this->check($answer)){
				$answer = array_keys($answer);
				return (int)$answer[0];
			}

		} catch (\SmsSender\Exceptions\SmsSenderException $e) {
			echoLine('[SMSClub::sendSMS] Exception ' . $e->getMessage(), 'e');
			return false;
		}

		return false;
	}
}
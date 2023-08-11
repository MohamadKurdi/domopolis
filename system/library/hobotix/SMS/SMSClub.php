<?

namespace hobotix\SMS;

class SMSClub {

	private $smsClub 		= null;
	private $viberEndpoint 	= 'https://im.smsclub.mobi/vibers/send';

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

		$this->smsClub = new \SmsSender\SmsSender($this->config->get('config_smsgate_api_key'));	
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

	public function checkViber($answer){
		$answer = json_encode($answer);
		$answer = json_decode($answer, true);

		if (!empty($answer['errorRequest'])){
			echoLine('[SMSClub::checkViber] Error! ' . serialize($answer['errorRequest']['errors']), 'e');
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


	public function sendViber($viber){
		echoLine('[SMSClub::sendViber] Sending Viber to number ' . preparePhone($viber['to']), 'i');

		$data = [
			'sender' 		=> $this->config->get('config_viber_from'),
			'phones' 		=> [ $viber['to'] ],
			'message' 		=> $viber['message'],
			'lifetime' 		=> 60			
		];

		if (!empty($viber['picture_url'])){
			$data['picture_url'] = $viber['picture_url'];
		}

		if (!empty($viber['button_txt'])){
			$data['button_txt'] = $viber['button_txt'];
			$data['button_url'] = $viber['button_url'];
		}

		if (!empty($viber['messageSms'])){
			$data['senderSms'] 	= $this->config->get('config_sms_from');
			$data['messageSms'] = $viber['messageSms'];
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Content-Length: ' . strlen(json_encode($data)),
			'X-Requested-With: XMLHttpRequest',
			'Accept: application/json, text/javascript, */*; q=0.01'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('config_smsgate_viber_auth_login') . ':' . $this->config->get('config_smsgate_viber_auth_pwd'));
		curl_setopt($ch, CURLOPT_URL, $this->viberEndpoint);
		
		$result = curl_exec($ch);
		$info 	= curl_getinfo($ch);
		curl_close($ch);
		
		if ($info['http_code'] != '200'){
			echoLine('[SMSClub::sendViber] SMSClub returned non-200 status!', 'e');	
			print_r($result);
			return false;
		}

		if (!json_decode($result, true)){
			echoLine('[SMSClub::sendViber] Can not decode result JSON!', 'e');	
			print_r($result);
			return false;
		} else {
			$result = json_decode($result, true);
		}

		if (!$this->checkViber($result)){
			echoLine('[SMSClub::sendViber] Check got errors!', 'e');
			return false;	
		}

		if (!empty($result['successfulRequest']) && !empty($result['successfulRequest']['requestData']) && !empty($result['successfulRequest']['requestData']['messages'])){
			if (!empty($result['successfulRequest']['requestData']['messages'][0])){
				return $result['successfulRequest']['requestData']['messages'][0]['id'];
			}
		}

		return false;
	}


	public function sendSMS($sms){
		echoLine('[SMSClub::sendSMS] Sending text to number ' . preparePhone($sms['to']), 'i');

		if ($this->config->get('config_smsgate_library_enable_viber_fallback')){
			return $this->sendViber(
				[
					'to' 			=> $sms['to'],
					'message' 		=> $sms['message'],
					'messageSms' 	=> $sms['message'],
				]
			);
		}

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
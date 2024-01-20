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
		$answer = json_encode($answer);
		$answer = json_decode($answer, true);

		if (!empty($answer['error'])){
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

	public function sendViber($viber){
		echoLine('[BSGWorld::sendViber] Sending Viber to number ' . preparePhone($viber['to']), 'i');

		$viberClient = $bsg->getViberClient();

		$viberOptions = ['viber' => []];

		if (!empty($viber['picture_url'])){
			$viberOptions['viber']['img'] = $viber['picture_url'];
		}

		if (!empty($viber['button_txt'])){
			$viberOptions['viber']['caption'] 	= $viber['button_txt'];
			$viberOptions['viber']['action'] 	= $viber['button_url'];
		}

		if (!empty($viber['messageSms'])){
			$viberOptions['viber']['alt_route'] = [
				'originator' 	=> $this->config->get('config_sms_from'),
				'text' 			=> $viber['messageSms']
			];
		}

		$viberClient->addMessage(
			$to 			= 	preparePhone($viber['to']), 
			$text 			= 	$viber['message'], 
			$viber_options	=	$viberOptions, 
			$alpha_name 	= 	$this->config->get('config_viber_from'), 
			$is_promotional = 	false, 
			$callback_url 	= 	''
		);

		$answer = $viberClient->sendMessages(
			$validity	=	3600,
			$tariff		=	NULL, 
			$only_price	=	false
		);

		if ($this->check($answer['result'][0])){
			$viberClient->clearMessages();
			return (float)$answer['result'][0]['id'];
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
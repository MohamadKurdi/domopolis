<?

namespace hobotix;

class SmsAdaptor {	
	private $registry	= null;
	private $config		= null;
	private $db  		= null;
	private $smsObject 	= null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

		$smsClass = $this->config->get('config_smsgate_library');

		if (file_exists(DIR_SYSTEM . '/library/hobotix/SMS/' . $smsClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/SMS/' . $smsClass . '.php');
			$smsClass = "hobotix" . "\\" . "sms" . "\\" . $smsClass;
			$this->smsObject = new $smsClass($this->registry);			
		} 
	}


	public function getBalance(){
		if (method_exists($this->smsObject, 'getBalance')){
			try {
				$result = $this->smsObject->getBalance();
			} catch (\Exception $e){
				$result = $e->getMessage();
			}
		}

		if (empty($result)){
			echoLine('[SmsAdaptor::getBalance] Could not anything, maybe general api fail! ' . $result, 'e');
			return false;
		}			

		echoLine('[SmsAdaptor::getBalance] Got balance: ' . $result);

		return $result;
	}


	public function sendSMS($sms){
		if (method_exists($this->smsObject, 'sendSMS')){
			try {
				$result = $this->smsObject->sendSMS($sms);
			} catch (\Exception $e){
				$result = $e->getMessage();
				return false;
			}
		}

		if (empty($result)){
			echoLine('[SmsAdaptor::getBalance] Could not send SMS!', 'e');
			return false;
		}			

		echoLine('[SmsAdaptor::getBalance] Sent SMS, got ID: ' . $result, 's');

		return $result;
	}

}
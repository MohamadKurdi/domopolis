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


	public function sendViber($viber){
		if ($this->config->get('config_smsgate_library_enable_viber') && method_exists($this->smsObject, 'sendViber')){
			try {
				$result = $this->smsObject->sendViber($viber);
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


	//SMS SERVICE FUNCTIONS

	public function addOrderSmsHistory($order_id, $data, $sms_status, $sms_id, $customer_id = 0) { 		
		$this->db->ncquery("INSERT INTO `order_sms_history` SET 
			order_id = '" . (int)$order_id . "', 
			customer_id = '" . (int)$customer_id . "',
			order_status_id = '" . (int)$data['order_status_id'] . "', 
			comment = '" . $this->db->escape(strip_tags($data['sms'])) . "', 
			date_added = NOW(), 
			sms_status = '" . $this->db->escape(strip_tags($sms_status)) . "', 
			sms_id = '".$this->db->escape($sms_id)."'");


		if ($customer_id){
			$this->db->ncquery("INSERT INTO `customer_history` SET
				customer_id = '" . (int)$customer_id . "',
				order_id = '" . (int)$order_id . "', 
				comment = 'Отправлено SMS',
				sms_id = '".$this->db->escape($sms_id)."',
				date_added = NOW()");

		}
	}   
}
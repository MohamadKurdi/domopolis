<?

namespace hobotix;

class MailAdaptor {	
	private $registry	= null;
	private $config		= null;
	private $db  		= null;
	private $currency  	= null;

	private $mail_TransactionObject 	= null;
	private $mail_MarketingObject 		= null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->currency = $registry->get('currency');

		$mailClass_Transaction 	= $this->config->get('config_mailgate_transaction_library');
		$mailClass_Marketing 	= $this->config->get('config_mailgate_marketing_library');

		if ($mailClass_Transaction){
			if (file_exists(DIR_SYSTEM . '/library/hobotix/Mail/' . $mailClass_Transaction . '.php')){
				require_once (DIR_SYSTEM . '/library/hobotix/Mail/' . $mailClass_Transaction . '.php');
				$mailClass_Transaction = "hobotix" . "\\" . "Mail" . "\\" . $mailClass_Transaction;
				$this->mail_TransactionObject = new $mailClass_Transaction($this->registry);			
			} else {
				throw new \Exception('[MailAdaptor::__construct] Can not load mailClass_Transaction library!');
			}
		}

		if ($mailClass_Marketing){
			if (file_exists(DIR_SYSTEM . '/library/hobotix/Mail/' . $mailClass_Marketing . '.php')){
				require_once (DIR_SYSTEM . '/library/hobotix/Mail/' . $mailClass_Marketing . '.php');
				$mailClass_Marketing = "hobotix" . "\\" . "Mail" . "\\" . $mailClass_Marketing;
				$this->mail_MarketingObject = new $mailClass_Marketing($this->registry);			
			} else {
				throw new \Exception('[MailAdaptor::__construct] Can not load mailClass_Marketing library!');
			}
		}
	}

	public function getMailGates(){
		$results = [];

		$smsgates = glob(dirname(__FILE__) . '/Mail/*');        
        foreach ($smsgates as $smsgate) {
            $results[] = pathinfo($smsgate,  PATHINFO_FILENAME);
        }

        return $results;
	}

	public function sync(){
		if (method_exists($this->mail_MarketingObject, 'sync')){
			try {
				$result = $this->mail_MarketingObject->sync();
			} catch (\Exception $e){
				$result = $e->getMessage();
				echoLine('[MailAdaptor::sync] Error happened: ' . $e->getMessage(), 'e');
				return false;
			}
		} else {
			echoLine('[MailAdaptor::sync] No sync function in adaptor!', 'e');
		}	
	}

	public function send($email){
		if (method_exists($this->smsObject, 'send')){
			try {
				$result = $this->smsObject->sendSMS($sms);
			} catch (\Exception $e){
				$result = $e->getMessage();
				return false;
			}
		}

		if (empty($result)){
			echoLine('[SmsAdaptor::sendSMS] Could not send SMS!', 'e');
			return false;
		}			

		echoLine('[SmsAdaptor::sendSMS] Sent SMS, got ID: ' . $result, 's');

		return $result;
	}	
}
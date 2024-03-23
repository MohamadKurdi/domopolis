<?php

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

		$mailgates = glob(dirname(__FILE__) . '/Mail/*');
        foreach ($mailgates as $mailgate) {
            $results[] = pathinfo($mailgate,  PATHINFO_FILENAME);
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
		if (method_exists($this->mail_TransactionObject, 'send')){
			try {
				$result = $this->mail_TransactionObject->send($email);
			} catch (\Exception $e){
				$result = $e->getMessage();
				return false;
			}
		}

		if (empty($result)){
			echoLine('[MailAdaptor::send] Could not send Email!', 'e');
			return false;
		}			

		echoLine('[MailAdaptor::send] Sent Email, got ID: ' . $result, 's');

		return $result;
	}	
}
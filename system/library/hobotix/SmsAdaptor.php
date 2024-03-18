<?

namespace hobotix;

class SmsAdaptor {	
	private $registry	= null;
	private $config		= null;
	private $db  		= null;
	private $currency  	= null;
	private $smsObject 	= null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->currency = $registry->get('currency');

		$smsClass = $this->config->get('config_smsgate_library');

		if (file_exists(DIR_SYSTEM . '/library/hobotix/SMS/' . $smsClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/SMS/' . $smsClass . '.php');
			$smsClass = "hobotix" . "\\" . "sms" . "\\" . $smsClass;
			$this->smsObject = new $smsClass($this->registry);			
		} else {
			throw new \Exception('[SmsAdaptor::__construct] Can not load SMS library!');
		}
	}

	public function getSmsGates(){
		$results = [];

		$smsgates = glob(dirname(__FILE__) . '/SMS/*');        
        foreach ($smsgates as $smsgate) {
            $results[] = pathinfo($smsgate,  PATHINFO_FILENAME);
        }

        return $results;
	}

	private function editOTPSetting($setting){
		if ($this->config->get('config_otp_auto_enable')){
			echoLine('[SmsAdaptor::editOTPSetting] OTP auto enabled, setting it to: ' . (int)$setting, 'w');

			$this->db->query("UPDATE setting SET `value` = '" . (int)$setting . "' WHERE `key` = 'config_otp_enable'");
			$this->db->query("UPDATE setting SET `value` = '" . (int)$setting . "' WHERE `key` = 'config_otp_enable_sms'");
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

		$result = number_format((float)$result, 0, '', '');

		if ((int)$result > 5){
			$this->editOTPSetting(1);
		} else {
			$this->editOTPSetting(0);
		}

		echoLine('[SmsAdaptor::getBalance] Got balance: ' . $result);

		return $result;
	}

	public function sendSMS($sms){
		$result = false;

		if ($this->config->get('config_smsgate_library_enable_viber_fallback')){
			echoLine('[SmsAdaptor::sendSMS] Viber priority setting is on, setting viber', 'w');

			if (empty($sms['messageSms'])){
				$sms['messageSms'] = $sms['message'];
			}

			$result = $this->sendViber($sms);
		} 

		if (!$result){
			if (method_exists($this->smsObject, 'sendSMS')){
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

		}

		return $result;
	}

	public function sendViber($viber){
		$result = false;

		if (($this->config->get('config_smsgate_library_enable_viber') || !empty($viber['test_mode'])) && method_exists($this->smsObject, 'sendViber')){
			try {
				$result = $this->smsObject->sendViber($viber);
			} catch (\Exception $e){
				$result = $e->getMessage();
				return false;
			}
		}

		if (empty($result)){
			echoLine('[SmsAdaptor::sendViber] Could not send Viber!', 'e');
			return false;
		}			

		echoLine('[SmsAdaptor::sendViber] Sent Viber, got ID: ' . $result, 's');

		return $result;
	}

	//Get Texts
	public function getPaymentLinkText($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{PAYMENT_LINK}' 	=> $data['payment_link'],
			'{SUM}' 			=> $this->currency->format($data['amount'], $order_info['currency_code'], '1')
		];

		if ($this->config->get('config_smsgate_library_enable_viber')){			
			return reTemplate($template, $this->config->get('config_viber_payment_link'));
		} else {			
			return reTemplate($template, $this->config->get('config_sms_payment_link'));
		}		

		return '';
	}

	public function getDeliveryNoteText($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{TTN}'				=> $data['ttn'],
			'{SHIPPING_CODE}' 	=> $data['shipping_code'],
			'{SENDING_DATE}' 	=> $data['senddate'],
		];

		if ($this->config->get('config_smsgate_library_enable_viber')){			
			return reTemplate($template, $this->config->get('config_viber_ttn_sent'));
		} else {			
			return reTemplate($template, $this->config->get('config_sms_ttn_sent'));
		}		

		return '';
	}

	public function getTransactionSMSText($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{SUM}' 		=> $this->currency->format($data['amount'], $order_info['currency_code'], '1')
		];

		if ($this->config->get('config_smsgate_library_enable_viber')){
			switch ($data['type']){
			case 0:
				return reTemplate($template, $this->config->get('config_viber_transaction_text_type_1'));
				break;
			case 1:
				return reTemplate($template, $this->config->get('config_viber_transaction_text_type_2'));
				break;
			case 2:
				return reTemplate($template, $this->config->get('config_viber_transaction_text_type_3'));
				break;
			}

			return reTemplate($template, $this->config->get('config_viber_transaction_text_type_1'));
		} else {
			switch ($data['type']){
			case 0:
				return reTemplate($template, $this->config->get('config_sms_transaction_text_type_1'));
				break;
			case 1:
				return reTemplate($template, $this->config->get('config_sms_transaction_text_type_2'));
				break;
			case 2:
				return reTemplate($template, $this->config->get('config_sms_transaction_text_type_3'));
				break;
			}

			return reTemplate($template, $this->config->get('config_sms_transaction_text_type_1'));
		}		

		return '';
	}

	public function getStatusSMSText($order_info, $data){
		$sms_settings 		= $this->config->get('config_sms_new_order_status_message');
		$viber_settings 	= $this->config->get('config_viber_order_status_message');

		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{STATUS_NAME}' => $data['order_status_name'],
			'{COMMENT}' 	=> $data['comment'],						
			'{PARTLY}'		=> $data['partly'],
			'{PICKUP_NAME}' => $data['pickup_name'],
			'{PICKUP_URL}'  => $data['pickup_url'],
			'{PAYMENT_INFO}'=> $data['payment_info'],
			'{PAYMENT_LINK}'=> $data['payment_link'],
			'{SUM}' 		=> $this->currency->format($data['amount'], $order_info['currency_code'], 1),
			'{SUM_TO_PAY}'  => $this->currency->format($data['amount'], $order_info['currency_code'], 1)	
		];

		if (!empty($viber_settings[$data['order_status_id']]['enabled']) && in_array($viber_settings[$data['order_status_id']]['enabled'], ['on', 1])){	
			if (!empty($viber_settings[$data['order_status_id']]['image']) && file_exists(DIR_IMAGE . $viber_settings[$data['order_status_id']]['image'])){
				$image = HTTPS_CATALOG . DIR_IMAGE_NAME . $viber_settings[$data['order_status_id']]['image'];
			} else {
				$image = '';
			}

			return [
				'message' => reTemplate($template, $viber_settings[$data['order_status_id']]['message']),
				'image'   => $image	
			];
		}											

		if (!empty($sms_settings[$data['order_status_id']]['enabled']) && in_array($sms_settings[$data['order_status_id']]['enabled'], ['on', 1])){	
			return [
				'message' => reTemplate($template, $sms_settings[$data['order_status_id']]['message'])
			];
		}		

		return '';
	}


	//SMS+Viber SERVICE FUNCTIONS
	public function sendSMSPasswordRecover($info, $data){
		$template = [
			'{NEW_PASSWORD}' => $data['password']
		];

		if ($this->config->get('config_restore_password_enable_viber')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_restore_password_text')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_restore_password_text')),				
			];

			$viberID = $this->sendViber($viber);

			return $viberID;
		}

		if ($this->config->get('config_restore_password_enable_sms')){
			$sms = [
				'to' 		=> $info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_restore_password_text'))
			];

			$smsID = $this->sendSMS($sms);

			return $smsID;
		}
	}


	public function sendSMSOTP($info, $data){
		$template = [
			'{OTP_CODE}' => $data['otp_code']
		];

		if ($this->config->get('config_otp_enable_viber')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_otp_text')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_otp_text')),				
			];

			$viberID = $this->sendViber($viber);

			return $viberID;
		}

		if ($this->config->get('config_otp_enable_sms')){
			$sms = [
				'to' 		=> $info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_otp_text'))
			];

			$smsID = $this->sendSMS($sms);

			return $smsID;
		}
	}

	public function sendSMSForgottenCartPromo($forgotten_cart, $data){
		$iteration = (int)$data['iteration'];

		$template = [
			'{ID}' 				=> $forgotten_cart['order_id'], 
			'{SNAME}'			=> $this->config->get('config_name'), 
			'{DATE}'			=> date('d.m.Y'), 
			'{TIME}'			=> date('H:i:s'), 
			'{PHONE}'			=> $forgotten_cart['telephone'], 
			'{FIRSTNAME}'		=> $forgotten_cart['firstname'], 
			'{LASTNAME}' 		=> $forgotten_cart['lastname'],
			'{PRODUCT}' 		=> $forgotten_cart['product'],
			'{PROMOCODE}'   	=> $forgotten_cart['promocode']
		];

		if ($this->config->get('config_viber_forgottencart_enabled_' . $iteration)){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $forgotten_cart['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_forgottencart_' . $iteration)),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_forgottencart_sms_text_' . $iteration)),

				'button_txt' 	=> $this->config->get('config_viber_forgottencart_button_text_' . $iteration),
				'button_url' 	=> $this->config->get('config_viber_forgottencart_button_url_' . $iteration), 				
			];

			if (!empty($this->config->get('config_viber_forgottencart_' . $iteration . '_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_forgottencart_' . $iteration . '_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_forgottencart_' . $iteration . '_image');
			}

			if (!empty($forgotten_cart['product_image'])){
				$viber['picture_url'] = $forgotten_cart['product_image'];
			}

			$viberID = $this->sendViber($viber);
			$this->addOrderSmsHistory(false, ['sms' => $viber['message']], 'Queued', $viberID, (int)$forgotten_cart['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_forgottencart_sms_enable_' . $iteration)){
			$sms = [
				'to' 		=> $forgotten_cart['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_forgottencart_sms_text_' . $iteration))
			];

			$smsID = $this->sendSMS($sms);
			$this->addOrderSmsHistory(false, ['sms' => $sms['message']], 'Queued', $smsID, (int)$forgotten_cart['customer_id']);

			return $smsID;
		}
	}

	public function sendSMSFirstOrderPromo($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{PROMOCODE}'   => $this->config->get('config_firstorder_promocode')
		];

		if ($this->config->get('config_viber_firstorder_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_firstorder')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_firstorder_sms_text')),

				'button_txt' 	=> $this->config->get('config_viber_firstorder_button_text'),
				'button_url' 	=> $this->config->get('config_viber_firstorder_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_firstorder_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_firstorder_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_firstorder_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory(false, ['sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_firstorder_sms_enable')){
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_firstorder_sms_text'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory(false, ['sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function sendBirthdayGreeting($customer_info, $data){
		$template = [
			'{SNAME}'			=> $this->config->get('config_name'), 
			'{DATE}'			=> date('d.m.Y'), 
			'{TIME}'			=> date('H:i:s'), 
			'{PHONE}'			=> $customer_info['telephone'], 
			'{FIRSTNAME}'		=> $customer_info['firstname'], 
			'{LASTNAME}' 		=> $customer_info['lastname']
		];

		if ($this->config->get('config_viber_birthday_greeting_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $customer_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_birthday_greeting')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_birthday_greeting')),

				'button_txt' 	=> $this->config->get('config_viber_birthday_greeting_button_text'),
				'button_url' 	=> $this->config->get('config_viber_birthday_greeting_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_birthday_greeting_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_birthday_greeting_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_birthday_greeting_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory(false, ['sms' => $viber['message']], 'Queued', $viberID, (int)$customer_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_birthday_greeting_enabled')){
			$sms = [
				'to' 		=> $customer_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_birthday_greeting'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory(false, ['sms' => $sms['message']], 'Queued', $smsID, (int)$customer_info['customer_id']);

			return $smsID;
		}
	}

	public function sendRewardAdded($customer_info, $data){
		$template = [
			'{SNAME}'			=> $this->config->get('config_name'), 
			'{DATE}'			=> date('d.m.Y'), 
			'{TIME}'			=> date('H:i:s'), 
			'{PHONE}'			=> $customer_info['telephone'], 
			'{FIRSTNAME}'		=> $customer_info['firstname'], 
			'{LASTNAME}' 		=> $customer_info['lastname'],
			'{POINTS_TOTAL}'	=> $data['points_total'],
			'{POINTS_ADDED}' 	=> $data['points_added'],
			'{POINTS_ACTIVE_TO}'=> $data['points_active_to'],
		];

		if ($this->config->get('config_viber_rewardpoints_added_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $customer_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_rewardpoints_added')),
				'messageSms' 	=> reTemplate($template, $this->config->get('rewardpoints_added_sms_text')),

				'button_txt' 	=> $this->config->get('config_viber_rewardpoints_added_button_text'),
				'button_url' 	=> $this->config->get('config_viber_rewardpoints_added_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_rewardpoints_added_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_rewardpoints_added_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_rewardpoints_added_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory(false, ['sms' => $viber['message']], 'Queued', $viberID, (int)$customer_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('rewardpoints_added_sms_enable')){
			$sms = [
				'to' 		=> $customer_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('rewardpoints_added_sms_text'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory(false, ['sms' => $sms['message']], 'Queued', $smsID, (int)$customer_info['customer_id']);

			return $smsID;
		}
	}

	public function sendRewardReminder($customer_info, $data){
		$template = [
			'{SNAME}'			=> $this->config->get('config_name'), 
			'{DATE}'			=> date('d.m.Y'), 
			'{TIME}'			=> date('H:i:s'), 
			'{PHONE}'			=> $customer_info['telephone'], 
			'{FIRSTNAME}'		=> $customer_info['firstname'], 
			'{LASTNAME}' 		=> $customer_info['lastname'],
			'{POINTS_AMOUNT}'		=> $data['points_amount'],
			'{POINTS_DAYS_LEFT}' 	=> $data['points_days_left'],
		];

		if ($this->config->get('config_viber_rewardpoints_reminder_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $customer_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_rewardpoints_reminder')),
				'messageSms' 	=> reTemplate($template, $this->config->get('rewardpoints_reminder_sms_text')),

				'button_txt' 	=> $this->config->get('config_viber_rewardpoints_reminder_button_text'),
				'button_url' 	=> $this->config->get('config_viber_rewardpoints_reminder_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_rewardpoints_reminder_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_rewardpoints_reminder_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_rewardpoints_reminder_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory(false, ['sms' => $viber['message']], 'Queued', $viberID, (int)$customer_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('rewardpoints_reminder_enable')){
			$sms = [
				'to' 		=> $customer_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('rewardpoints_reminder_sms_text'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory(false, ['sms' => $sms['message']], 'Queued', $smsID, (int)$customer_info['customer_id']);

			return $smsID;
		}
	}

	public function sendPaymentLink($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{PAYMENT_LINK}' 	=> $data['payment_link'],
			'{SUM}' 			=> $this->currency->format($data['amount'], $order_info['currency_code'], '1')
		];

		if ($this->config->get('config_viber_payment_link_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_payment_link')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_payment_link')),

				'button_txt' 	=> $this->config->get('config_viber_payment_link_button_text'),
				'button_url' 	=> $this->config->get('config_viber_payment_link_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_payment_link_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_payment_link_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_payment_link_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_payment_link_enabled')){
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_payment_link'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function sendStatusSMSText($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{STATUS_NAME}' => $data['order_status_name'],
			'{COMMENT}' 	=> $data['comment'],						
			'{PARTLY}'		=> $data['partly'],
			'{PICKUP_NAME}' => $data['pickup_name'],
			'{PICKUP_URL}'  => $data['pickup_url'],
			'{PAYMENT_INFO}'=> $data['payment_info'],
			'{PAYMENT_LINK}'=> $data['payment_link'],
			'{SUM}' 		=> $this->currency->format($data['amount'], $order_info['currency_code'], 1),
			'{SUM_TO_PAY}'  => $this->currency->format($data['amount'], $order_info['currency_code'], 1)	
		];

		$sms_settings 		= $this->config->get('config_sms_new_order_status_message');
		$viber_settings 	= $this->config->get('config_viber_order_status_message');

		if (!empty($viber_settings[$data['order_status_id']]['enabled']) && in_array($viber_settings[$data['order_status_id']]['enabled'], ['on', 1])){	
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $viber_settings[$data['order_status_id']]['message']),
				'messageSms' 	=> reTemplate($template, $sms_settings[$data['order_status_id']]['message']),

				'button_txt' 	=> $viber_settings[$data['order_status_id']]['button_text'],
				'button_url' 	=> $viber_settings[$data['order_status_id']]['button_url'], 				
			];

			if (!empty($viber_settings[$data['order_status_id']]['image']) && file_exists(DIR_IMAGE . $viber_settings[$data['order_status_id']]['image'])){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $viber_settings[$data['order_status_id']]['image'];
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if (!empty($sms_settings[$data['order_status_id']]['enabled']) && in_array($sms_settings[$data['order_status_id']]['enabled'], ['on', 1])){	
			
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $sms_settings[$data['order_status_id']]['message'])
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);
			return $smsID;
		}	
	}

	public function sendInWarehouse($order_info, $data){
		$template = [
				'{ID}' 			=> $order_info['order_id'], 
				'{SNAME}'		=> $this->config->get('config_name'), 
				'{DATE}'		=> date('d.m.Y'), 
				'{TIME}'		=> date('H:i:s'), 
				'{PHONE}'		=> $order_info['telephone'], 
				'{FIRSTNAME}'	=> $order_info['firstname'], 
				'{LASTNAME}' 	=> $order_info['lastname']
			];

		if ($this->config->get('config_viber_tracker_leave_main_warehouse_enabled')){			
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_tracker_leave_main_warehouse')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_tracker_leave_main_warehouse')),

				'button_txt' 	=> $this->config->get('config_viber_tracker_leave_main_warehouse_button_text'),
				'button_url' 	=> $this->config->get('config_viber_tracker_leave_main_warehouse_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_tracker_leave_main_warehouse_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_tracker_leave_main_warehouse_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_tracker_leave_main_warehouse_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_tracker_leave_main_warehouse_enabled')) {	
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_tracker_leave_main_warehouse'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function sendDeliveryNote($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname'],
			'{TTN}'			=> $data['ttn']
		];


		if ($this->config->get('config_viber_ttn_sent_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_ttn_sent')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_ttn_sent')),

				'button_txt' 	=> $this->config->get('config_viber_ttn_sent_button_text'),
				'button_url' 	=> $this->config->get('config_viber_ttn_sent_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_ttn_sent_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_ttn_sent_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_ttn_sent_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);
			$this->setDeliveryDateSent($data['ttn']);

			return $viberID;
		}

		if ($this->config->get('config_sms_ttn_sent_enabled')) {
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_ttn_sent'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);
			$this->setDeliveryDateSent($data['ttn']);

			return $smsID;
		}
	}

	public function sendPayment($order_info, $data){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y'), 
			'{TIME}'		=> date('H:i:s'), 
			'{SUM}'			=> $this->currency->format($data['amount'], $order_info['currency_code'], 1), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname']				
		];

		if ($this->config->get('config_viber_payment_recieved_enabled')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_payment_recieved')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_payment_recieved')),

				'button_txt' 	=> $this->config->get('config_viber_payment_recieved_button_text'),
				'button_url' 	=> $this->config->get('config_viber_payment_recieved_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_payment_recieved_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_payment_recieved_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_payment_recieved_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}


		if ($this->config->get('config_sms_payment_recieved_enabled')) {
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_payment_recieved'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $this->config->get('config_order_status_id'), 'sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function sendNewOrder($order_info){
		$template = [
			'{ID}' 			=> $order_info['order_id'], 
			'{SNAME}'		=> $this->config->get('config_name'), 
			'{DATE}'		=> date('d.m.Y', strtotime($order_info['date_added'])), 
			'{TIME}'		=> date('H:i:s', strtotime($order_info['date_added'])), 
			'{SUM}'			=> $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']), 
			'{PHONE}'		=> $order_info['telephone'], 
			'{FIRSTNAME}'	=> $order_info['firstname'], 
			'{LASTNAME}' 	=> $order_info['lastname']				
		];

		if ($this->config->get('config_viber_send_new_order')){
			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_new_order_message')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_new_order_message')),

				'button_txt' 	=> $this->config->get('config_viber_new_order_button_text'),
				'button_url' 	=> $this->config->get('config_viber_new_order_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_new_order_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_new_order_image'))){
				$viber['picture_url'] = HTTPS_CATALOG . DIR_IMAGE_NAME . $this->config->get('config_viber_new_order_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $this->config->get('config_order_status_id'), 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_send_new_order')) {
			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_new_order_message'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $this->config->get('config_order_status_id'), 'sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function setDeliveryDateSent($ttn) {
		$this->db->query("UPDATE `order_ttns` SET sms_sent = NOW() WHERE ttn = '" . $this->db->escape($ttn) . "'");
	}

	public function addOrderSmsHistory($order_id, $data, $sms_status, $sms_id, $customer_id = 0) {
		if ($order_id){

			if (empty($data['order_status_id'])){
				$query = $this->db->query("SELECT order_status_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
				$data['order_status_id'] = $query->row['order_status_id'];
			}

			$this->db->ncquery("INSERT INTO `order_sms_history` SET 
			order_id 			= '" . (int)$order_id . "', 
			customer_id 		= '" . (int)$customer_id . "',
			order_status_id		= '" . (int)$data['order_status_id'] . "', 
			comment 			= '" . $this->db->escape(strip_tags($data['sms'])) . "', 
			date_added 			= NOW(), 
			sms_status 			= '" . $this->db->escape(strip_tags($sms_status)) . "', 
			sms_id 				= '".$this->db->escape($sms_id)."'");
		} 		
		
		if ($customer_id){
			$this->db->ncquery("INSERT INTO `customer_history` SET
				customer_id 	= '" . (int)$customer_id . "',
				order_id 		= '" . (int)$order_id . "', 
				comment 		= 'SMS Sent',
				sms_id 			= '".$this->db->escape($sms_id)."',
				date_added 		= NOW()");
		}
	}   
}
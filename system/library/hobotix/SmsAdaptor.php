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
			echoLine('[SmsAdaptor::sendSMS] Could not send SMS!', 'e');
			return false;
		}			

		echoLine('[SmsAdaptor::sendSMS] Sent SMS, got ID: ' . $result, 's');

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
			echoLine('[SmsAdaptor::sendViber] Could not send Viber!', 'e');
			return false;
		}			

		echoLine('[SmsAdaptor::sendViber] Sent Viber, got ID: ' . $result, 's');

		return $result;
	}


	//SMS+Viber SERVICE FUNCTIONS
	public function sendInWarehouse($order_info, $data){
		if ($this->config->get('config_viber_tracker_leave_main_warehouse_enabled')){
			$template = [
				'{ID}' 			=> $order_info['order_id'], 
				'{SNAME}'		=> $this->config->get('config_name'), 
				'{DATE}'		=> date('d.m.Y'), 
				'{TIME}'		=> date('H:i:s'), 
				'{PHONE}'		=> $order_info['telephone'], 
				'{FIRSTNAME}'	=> $order_info['firstname'], 
				'{LASTNAME}' 	=> $order_info['lastname']
			];

			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_tracker_leave_main_warehouse')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_tracker_leave_main_warehouse')),

				'button_txt' 	=> $this->config->get('config_viber_tracker_leave_main_warehouse_button_text'),
				'button_url' 	=> $this->config->get('config_viber_tracker_leave_main_warehouse_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_tracker_leave_main_warehouse_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_tracker_leave_main_warehouse_image'))){
				$viber['picture_url'] = HTTPS_SERVER . DIR_IMAGE_NAME . $this->config->get('config_viber_tracker_leave_main_warehouse_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_tracker_leave_main_warehouse_enabled')) {
			$template = [
				'{ID}' 			=> $order_info['order_id'], 
				'{SNAME}'		=> $this->config->get('config_name'), 
				'{DATE}'		=> date('d.m.Y'), 
				'{TIME}'		=> date('H:i:s'), 
				'{PHONE}'		=> $order_info['telephone'], 
				'{FIRSTNAME}'	=> $order_info['firstname'], 
				'{LASTNAME}' 	=> $order_info['lastname']
			];

			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_tracker_leave_main_warehouse'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $sms['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function sendDeliveryNote($order_info, $data){
		if ($this->config->get('config_viber_ttn_sent_enabled')){
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

			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_ttn_sent')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_ttn_sent')),

				'button_txt' 	=> $this->config->get('config_viber_ttn_sent_button_text'),
				'button_url' 	=> $this->config->get('config_viber_ttn_sent_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_ttn_sent_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_ttn_sent_image'))){
				$viber['picture_url'] = HTTPS_SERVER . DIR_IMAGE_NAME . $this->config->get('config_viber_ttn_sent_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_ttn_sent_enabled')) {
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

			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_ttn_sent'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $sms['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function sendPayment($order_info, $data){
		if ($this->config->get('config_viber_payment_recieved_enabled')){
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

			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_payment_recieved')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_payment_recieved')),

				'button_txt' 	=> $this->config->get('config_viber_payment_recieved_button_text'),
				'button_url' 	=> $this->config->get('config_viber_payment_recieved_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_payment_recieved_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_payment_recieved_image'))){
				$viber['picture_url'] = HTTPS_SERVER . DIR_IMAGE_NAME . $this->config->get('config_viber_payment_recieved_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $data['order_status_id'], 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}


		if ($this->config->get('config_sms_payment_recieved_enabled')) {
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
		if ($this->config->get('config_viber_send_new_order')){
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

			$viber = [
				'viber' 		=> true,
				'to' 			=> $order_info['telephone'],
				'message' 		=> reTemplate($template, $this->config->get('config_viber_new_order_message')),
				'messageSms' 	=> reTemplate($template, $this->config->get('config_sms_new_order_message')),

				'button_txt' 	=> $this->config->get('config_viber_new_order_button_text'),
				'button_url' 	=> $this->config->get('config_viber_new_order_button_url'), 				
			];

			if (!empty($this->config->get('config_viber_new_order_image')) && file_exists(DIR_IMAGE . $this->config->get('config_viber_new_order_image'))){
				$viber['picture_url'] = HTTPS_SERVER . DIR_IMAGE_NAME . $this->config->get('config_viber_new_order_image');
			}

			$viberID = $this->registry->get('smsQueue')->queue($viber);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $this->config->get('config_order_status_id'), 'sms' => $viber['message']], 'Queued', $viberID, (int)$order_info['customer_id']);

			return $viberID;
		}

		if ($this->config->get('config_sms_send_new_order')) {
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

			$sms = [
				'to' 		=> $order_info['telephone'],
				'message' 	=> reTemplate($template, $this->config->get('config_sms_new_order_message'))
			];

			$smsID = $this->registry->get('smsQueue')->queue($sms);
			$this->addOrderSmsHistory($order_info['order_id'], ['order_status_id' => $this->config->get('config_order_status_id'), 'sms' => $sms['message']], 'Queued', $smsID, (int)$order_info['customer_id']);

			return $smsID;
		}
	}

	public function addOrderSmsHistory($order_id, $data, $sms_status, $sms_id, $customer_id = 0) { 		
		$this->db->ncquery("INSERT INTO `order_sms_history` SET 
			order_id 			= '" . (int)$order_id . "', 
			customer_id 		= '" . (int)$customer_id . "',
			order_status_id		= '" . (int)$data['order_status_id'] . "', 
			comment 			= '" . $this->db->escape(strip_tags($data['sms'])) . "', 
			date_added 			= NOW(), 
			sms_status 			= '" . $this->db->escape(strip_tags($sms_status)) . "', 
			sms_id 				= '".$this->db->escape($sms_id)."'");


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
<?php

class ControllerAPIBinotel extends Controller {

	private $listOfBinotelServers = [
		'194.88.218.116' => 'my.binotel.ua',
		'194.88.218.114' => 'sip1.binotel.com',
		'194.88.218.117' => 'sip2.binotel.com',
		'194.88.218.118' => 'sip3.binotel.com',
		'194.88.219.67' => 'sip4.binotel.com',
		'194.88.219.78' => 'sip5.binotel.com',
		'194.88.219.70' => 'sip6.binotel.com',
		'194.88.219.71' => 'sip7.binotel.com',
		'194.88.219.72' => 'sip8.binotel.com',
		'194.88.219.79' => 'sip9.binotel.com',
		'194.88.219.80' => 'sip10.binotel.com',
		'194.88.219.81' => 'sip11.binotel.com',
		'194.88.219.82' => 'sip12.binotel.com',
		'194.88.219.83' => 'sip13.binotel.com',
		'194.88.219.84' => 'sip14.binotel.com',
		'194.88.219.85' => 'sip15.binotel.com',
		'194.88.219.86' => 'sip16.binotel.com',
		'194.88.219.87' => 'sip17.binotel.com',
		'194.88.219.88' => 'sip18.binotel.com',
		'194.88.219.89' => 'sip19.binotel.com',
		'194.88.219.92' => 'sip20.binotel.com',
		'194.88.218.119' => 'sip21.binotel.com',
		'194.88.218.120' => 'sip22.binotel.com',
		'185.100.66.145' => 'sip50.binotel.com',
		'185.100.66.146' => 'sip51.binotel.com',
		'185.100.66.147' => 'sip52.binotel.com',
		"31.43.104.37" 	 => 'dev' 
	];

	public function index(){
		if (!isset($this->listOfBinotelServers[$this->request->server['REMOTE_ADDR']])) {
			header('HTTP/1.1 403 Forbidden');
  			die('Access to binotel api denied!');
		}

		$log = new Log('binotel.txt');
		$log->write($this->request->post);
		
		$this->response->setOutput(json_encode(['status' => 'success']));
	}

	private function checkMissed($telephone, $date_added){
		$query = $this->db->ncquery("SELECT * FROM callback WHERE telephone = '" . $this->db->escape($telephone) . "' AND date_added = '" . $this->db->escape($date_added) . "'");

		return $query->num_rows;
	}

	public function missed(){
		if (php_sapi_name() != 'cli'){
			die();
		}

		if ($this->config->get('config_telephony_engine') != 'binotel'){
			die('BINOTEL API NOT ENABLED');
		}

		try {
			$binotelClient = new \denostr\Binotel\Client($this->config->get('config_binotel_api_key'), $this->config->get('config_binotel_api_secret'));
			$result = $binotelClient->stats->listOfLostCallsToday();

			foreach ($result as $key => $missed){

				if ($this->checkMissed($missed['externalNumber'], date('Y-m-d H:i:s', $missed['startTime']))){
					echoLine('[ControllerAPIBinotel:missed] ' . $missed['externalNumber'] . ' already exists!', 'w');
				} else {				
					echoLine('[ControllerAPIBinotel:missed] ' . $missed['externalNumber'] . ' not exists, alerting!', 'e');

					if (!$customer = $this->customer->getCustomerByTelephone($missed['externalNumber'])){
						$customer = [
							'firstname' 	=> $missed['externalNumber'],
							'lastname' 		=> '',
							'customer_id' 	=> 0
						];
					}

					$this->db->ncquery("INSERT INTO callback SET 
						name 				= '" . $this->db->escape($customer['firstname'])  . "', 
						comment_buyer 		= '" . $this->db->escape('Пропущенный звонок')  . "',
						email_buyer 		= '" . $this->db->escape('')  . "', 
						telephone 			= '" . $this->db->escape($missed['externalNumber']) . "',
						customer_id 		= '". (int)$customer['customer_id'] ."',
						sip_queue 			= '" . $this->config->get('config_default_queue') . "',
						date_added 			= '" . date('Y-m-d H:i:s', $missed['startTime']) . "', 
						date_modified 		= '" . date('Y-m-d H:i:s', $missed['startTime']) . "',
						status_id 			= '0',
						is_missed 			= '1',
						comment 			= ''"
					);

					$data = array(
							'type' 			=> 'warning',
							'text' 			=> "Пропущенный звонок: <br /><br /><span style='font-size:16px;'>". $customer['firstname'] . ' ' . $customer['lastname'].'</span>', 
							'entity_type' 	=> 'missedcall', 
							'entity_id' 	=> $customer['customer_id']
					);
						
					$this->mAlert->insertAlertForGroup('sales', $data);	
				}


							
			}

		} catch (\denostr\Binotel\Exception $e) {
			echo (sprintf('Error (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage()));
		}	






	}

}
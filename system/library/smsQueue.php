<?

class smsQueue {
	
	private $db = null;
	private $config = null;
	
	public function __construct($registry) {
		$this->db 		= $registry->get('db');
		$this->config 	= $registry->get('config');
	}
	
	public function queue($data){					
		$queue_array = array(		
			'to' 	=> $data['to'],
			'from' 	=> $data['from'],
			'message' => $data['message']
		);
		
		if (mb_strlen($data['message']) > 210){
			return false;
		}
		
		$this->db->query("INSERT INTO `queue_sms` SET `body`='". base64_encode(json_encode($queue_array)) ."'");		
		
		return $this->db->getLastId();	
		
	}

	private function deleteSMSFromQueue($queue_sms_id){
		$this->db->query("DELETE FROM queue_sms WHERE queue_sms_id = '" . (int)$queue_sms_id . "'");
	}

	public function cron(){
		$log = new Log('sms_epochta.txt');

		$query = $this->db->non_cached_query("SELECT * FROM queue_sms ORDER BY RAND() LIMIT 7");

		$ePochta = new \Enniel\Epochta\SMS(['public_key' => $this->config->get('config_smsgate_secret_key'), 'private_key' => $this->config->get('config_smsgate_api_key')]);

		$result = $ePochta->getUserBalance()->getBody()->getContents();	    
		$result = json_decode($result, true);

		$balance = 0;
		if (!empty($result['result']['balance_currency'])){
			$balance = (float)$result['result']['balance_currency'];
		}

		echoLine('[smsQueue] Баланс: ' . $balance);
		if ($balance && ($balance > 5)) {
			foreach ($query->rows as $sms){
				$body 		= json_decode(base64_decode($sms['body']), true);

				if (!trim($body['message'])){
					echoLine('[smsQueue] Пустой текст!');
					$this->deleteSMSFromQueue($sms['queue_sms_id']);
				}

				$params = [		
					'sender'		=> $body['from'],
					'text'			=> $body['message'],
					'phone'			=> preparePhone($body['to']),
					'type' 			=> '2',
					'datetime' 		=> '',
					'sms_lifetime'  => '0',
				];

				$log->write(json_encode($params));

				$response = $ePochta->sendSMS($params)->getBody()->getContents();	
				$result = json_decode($response, true);

				$log->write(json_encode($result));

				$success = false;
				if (isset($result["error"]) && $result["error"] == 'not_enough_money'){
					continue;
				}

				if (!empty($result["error"])){
					echoLine('[smsQueue] Ошибка. ' . $result["error"]);
					echoLine('[smsQueue] ' . $response);
					
					if ($result["error"] == 'no_good_recipients'){
						$this->deleteSMSFromQueue($sms['queue_sms_id']);
					}
				}

				if (!empty($result["result"]["id"]) && $result["result"]["id"]){
					echoLine('[smsQueue] ' . $params['phone'] . ' -> ' . $result["result"]["id"]);			
					$this->deleteSMSFromQueue($sms['queue_sms_id']);
				}
			}

		}
	}
}
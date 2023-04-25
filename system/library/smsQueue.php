<?

class smsQueue {
	
	private $db 		= null;
	private $config 	= null;
	private $SmsAdaptor = null;
	
	public function __construct($registry) {
		$this->registry = $registry;
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
		$query = $this->db->non_cached_query("SELECT DISTINCT queue_sms_id, body FROM queue_sms ORDER BY queue_sms_id DESC LIMIT 7");
		$balance = $this->registry->get('smsAdaptor')->getBalance();
	
		if ($balance){
			echoLine('[LibrarySmsQueue::cron] Got balance: ' . $balance, 'i');
		}		
		
		if ((int)$balance > 5){
			echoLine('[LibrarySmsQueue::cron] Balance is: ' . $balance, 's');

			foreach ($query->rows as $row){
				$body 		= json_decode(base64_decode($row['body']), true);

				if (!trim($body['message'])){
					echoLine('[LibrarySmsQueue::cron] Empty text!', 'e');
					$this->deleteSMSFromQueue($row['queue_sms_id']);
				}

				$sms = [		
					'to' 		=>	$body['to'],
					'message' 	=> 	$body['message']
				];

				$id = $this->registry->get('smsAdaptor')->sendSMS($sms);

				if ($id){
					echoLine('[LibrarySmsQueue::cron] Success send to ' . $body['to'], ', returned id ' . $id, 's');
				} else {
					echoLine('[LibrarySmsQueue::cron] Fail send to ' . $body['to'], 'e');
				}

				$this->deleteSMSFromQueue($row['queue_sms_id']);
			}


		} else {
			echoLine('[LibrarySmsQueue::cron] Quitting, balance is: ' . $balance, 'e');
		}
	}
}
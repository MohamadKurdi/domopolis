<?

class pushQueue {
	
	private $db;
	
	public function __construct($registry) {
		$this->db = $registry->get('db');
	}
	
	public function queue($data){			
		//all_parameters_to_array
		$queue_array = array(		
			'customer_id' 	=> $data['customer_id'],
			'body' 			=> $data['body'],
			'title' 		=> $data['title'],
			'link'			=> $data['link']
		);
		
		$this->db->query("INSERT INTO `queue_push` SET customer_id = '" . (int)$data['customer_id'] . "', `body`='". base64_encode(json_encode($queue_array)) ."'");		
		
		return $this->db->getLastId();	
		
	}

	
}
?>
<?php
class mAlert {
	private $prefix;
	private $queue;
	
	//alert stucture: type:text:entity_type:entity_id
	//example: sales:information:'Оформлен новый заказ':order:11816
	//example: 
	
	
	public function __construct($registry) {
		$this->cache= $registry->get('cache');
		$this->db = $registry->get('db');		
		$this->prefix = ALERT_QUEUE;
		
		if (!($this->queue = $this->cache->get($this->prefix . '_queue'))){
			
			$this->queue = array();
			
			$query = $this->db->query("SELECT ug.alert_namespace, GROUP_CONCAT(u.user_id SEPARATOR ':') as user_list FROM user u LEFT JOIN user_group ug ON u.user_group_id = ug.user_group_id  WHERE LENGTH(ug.alert_namespace)>0 AND u.status=1 GROUP BY ug.alert_namespace");
			
			foreach ($query->rows as $row){
				$this->queue[$row['alert_namespace']] = explode(':', $row['user_list']);
			}
			
			$query = $this->db->query("SELECT GROUP_CONCAT(u.user_id SEPARATOR ':') as user_list FROM user u WHERE u.status=1 GROUP BY u.status");
			$this->queue['everyone'] = $query->row['user_list'];		
			
			
			$this->cache->set($this->prefix . '_queue', $this->queue);
			
		}
	}
	
	
	public function insertAlertForGroup($for_who, $data = array()){
		
		//pushing alert for every member of queue
		if (isset($this->queue[$for_who])){
		
			foreach ($this->queue[$for_who] as $user_id) {
				if ($user_id != 16 && $user_id != 17) {
					$this->cache->addtolist($this->prefix . '_user' . (int)$user_id, serialize($data), 600);								
				}
			}
			
			return true;
		}
		
		return false;						
	}
	
	public function insertAlertForOne($user_id, $data = array()){		
	
		$this->cache->addtolist($this->prefix . '_user' . (int)$user_id, serialize($data), 600);
		
	}
	
	public function getAlert($user_id){
		
		return $this->cache->rpoplist($this->prefix . '_user' . (int)$user_id);
				
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
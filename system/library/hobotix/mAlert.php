<?php

namespace hobotix;

class mAlert {
	private $prefix = 'ALERT_QUEUE_';
	private $queue = null;
	
	private $db 	= null;
	private $cache 	= null;
	private $config = null;
	
	//alert stucture: type:text:entity_type:entity_id
	//example: sales:information:'Оформлен новый заказ':order:11816
	//example: 
		
	public function __construct($registry) {
		$this->cache 	= $registry->get('cache');
		$this->db 		= $registry->get('db');	
		$this->config 	= $registry->get('config');		
		$this->prefix 	= ALERT_QUEUE;

		if ($this->config->get('config_enable_malert_in_admin')){			
			if (!($this->queue = $this->cache->get($this->prefix . '_queue'))){			
				$this->queue = [];

				$query = $this->db->query("SELECT ug.alert_namespace, GROUP_CONCAT(u.user_id SEPARATOR ':') as user_list FROM user u LEFT JOIN user_group ug ON u.user_group_id = ug.user_group_id  WHERE LENGTH(ug.alert_namespace)>0 AND u.status=1 GROUP BY ug.alert_namespace");

				foreach ($query->rows as $row){
					$this->queue[$row['alert_namespace']] = explode(':', $row['user_list']);
				}

				$query = $this->db->query("SELECT GROUP_CONCAT(u.user_id SEPARATOR ':') as user_list FROM user u WHERE u.status = '1' GROUP BY u.status");
				$this->queue['everyone'] = $query->row['user_list'];		

				$this->cache->set($this->prefix . '_queue', $this->queue);			
			}
		}
	}
		
	public function insertAlertForGroup($for_who, $data = array()){
		if ($this->config->get('config_enable_malert_in_admin')){
			if (isset($this->queue[$for_who])){

				foreach ($this->queue[$for_who] as $user_id) {
					$this->cache->addtolist($this->prefix . '_user' . (int)$user_id, serialize($data), 600);								
				}

				return true;
			}
		}
		
		return false;						
	}
	
	public function insertAlertForOne($user_id, $data = array()){	
		if ($this->config->get('config_enable_malert_in_admin')){		
			$this->cache->addtolist($this->prefix . '_user' . (int)$user_id, serialize($data), 600);		
		}

		return false;
	}
	
	public function getAlert($user_id){	
		if ($this->config->get('config_enable_malert_in_admin')){		
			return $this->cache->rpoplist($this->prefix . '_user' . (int)$user_id);
		}

		return false;				
	}
}
<?php
	class ModelSaleCallback extends Model {
		
		public function editCallback($callback_id,$data) {
			$this->db->query("UPDATE " . DB_PREFIX . "callback SET 
			comment = '" . $this->db->escape($data['comment']) . "', 		
			status_id = '" . (int)$data['status_id'] . "', 
			date_modified = NOW() 
			WHERE call_id = '" . (int)$callback_id . "'");	
			
			
			if ($this->user->isLogged() && (in_array($this->user->getUserGroup(), array(12, 19, 14, 27)))){
				$manager_query = $this->db->query("SELECT manager_id FROM `" . DB_PREFIX . "callback` WHERE call_id = '" . (int)$callback_id . "' AND manager_id > 0");
				if (!($manager_query->rows)){						
					$this->db->query("UPDATE `" . DB_PREFIX . "callback` SET manager_id = '" . (int)$this->user->getId() . "' WHERE call_id = '" . (int)$callback_id . "'");		
				}
			}
		}
		
		
		public function editCallbacks($callback_id) {
			$this->db->query("UPDATE " . DB_PREFIX . "callback SET status_id = '1', date_modified = NOW() WHERE call_id = '" . (int)$callback_id . "'");
			
			if ($this->user->isLogged() && (in_array($this->user->getUserGroup(), array(12, 19, 14, 27)))){
				$manager_query = $this->db->query("SELECT manager_id FROM `" . DB_PREFIX . "callback` WHERE call_id = '" . (int)$callback_id . "' AND manager_id > 0");
				if (!($manager_query->rows)){						
					$this->db->query("UPDATE `" . DB_PREFIX . "callback` SET manager_id = '" . (int)$this->user->getId() . "' WHERE call_id = '" . (int)$callback_id . "'");		
				}
			}
		}
		
		public function deleteCallback($callback_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "callback WHERE call_id = '" . (int)$callback_id . "'");
			
			
			$this->cache->delete('callback');
		}	
		
		public function getСallback($callback_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "callback WHERE call_id = '" . (int)$callback_id . "'");
			
			return $query->row;
		}
		
		public function getCallbacks($data = array()) {
			
			if ($this->user->getIsAV()){
				
				$sql = "SELECT c.* FROM " . DB_PREFIX . "callback c";
				
				} else {
				
				$sql = "SELECT c.* FROM " . DB_PREFIX . "callback c
				LEFT JOIN user_group ug ON c.sip_queue = ug.sip_queue
				WHERE ug.user_group_id = '" . $this->user->getUserGroup() . "'			
				";
				
			}
			
			//	$sql = "SELECT c.* FROM " . DB_PREFIX . "callback c";
			
			$sort_data = array(
			'call_id',
			'name',
			'telephone',
			'manager_id',
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY call_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
				} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}				
			
			$query = $this->db->query($sql);
			
			return $query->rows;		
		}
		
		public function getOpenedCallBacksForGroupID($group_id){
			
			$query = $this->db->query("SELECT COUNT(c.status_id) AS countcallback FROM `callback` c
			LEFT JOIN user_group ug ON c.sip_queue = ug.sip_queue
			WHERE ug.user_group_id = '" . (int)$group_id . "' AND c.status_id = '0'");
			
			
			return $query->row['countcallback'];
		}
		
		public function getOpenedCallBacks(){
			
			if ($this->user->getIsAV()){
				
				$query = $this->db->query("SELECT COUNT(c.status_id) AS countcallback FROM `".DB_PREFIX."callback` c  WHERE c.status_id = '0'");
				
				} else {
				
				$query = $this->db->query("SELECT COUNT(c.status_id) AS countcallback FROM `".DB_PREFIX."callback` c 
				LEFT JOIN user_group ug ON c.sip_queue = ug.sip_queue
				WHERE ug.user_group_id = '" . $this->user->getUserGroup() . "' AND c.status_id = '0'");
				
				
			}
			
			return $query->row['countcallback'];
		}
		
		public function getTotalCallbacks() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "callback c"); 
			
			return $query->row['total'];
		}	
	}							
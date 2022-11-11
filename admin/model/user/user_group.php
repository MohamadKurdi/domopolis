<?php
	class ModelUserUserGroup extends Model {
		public function addUserGroup($data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', template_prefix = '" . $this->db->escape($data['template_prefix']) . "', alert_namespace = '" . $this->db->escape($data['alert_namespace']) . "', ticket = '" . $this->db->escape($data['ticket']) . "', sip_queue = '" . $this->db->escape($data['sip_queue']) . "', bitrix_id = '" . $this->db->escape($data['bitrix_id']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "'");
			
			$user_group_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "user_group_to_store WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			if (isset($data['user_group_store'])) {
				foreach ($data['user_group_store'] as $store_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "user_group_to_store SET user_group_id = '" . (int)$user_group_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
		}
		
		public function editUserGroup($user_group_id, $data) {
			$this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', template_prefix = '" . $this->db->escape($data['template_prefix']) . "', alert_namespace = '" . $this->db->escape($data['alert_namespace']) . "', ticket = '" . $this->db->escape($data['ticket']) . "', sip_queue = '" . $this->db->escape($data['sip_queue']) . "', bitrix_id = '" . $this->db->escape($data['bitrix_id']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "user_group_to_store WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			if (isset($data['user_group_store'])) {
				foreach ($data['user_group_store'] as $store_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "user_group_to_store SET user_group_id = '" . (int)$user_group_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
		}
		
		public function deleteUserGroup($user_group_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
		
		public function addPermission($user_id, $type, $page) {
			$user_query = $this->db->query("SELECT DISTINCT user_group_id FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
			
			if ($user_query->num_rows) {
				$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
				
				if ($user_group_query->num_rows) {
					$data = unserialize($user_group_query->row['permission']);
					
					$data[$type][] = $page;
					
					$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . serialize($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
				}
			}
		}
		
		public function getUserGroupStores($user_group_id) {
			$user_group_store_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group_to_store WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			foreach ($query->rows as $result) {
				$user_group_store_data[] = $result['store_id'];
			}
			
			return $user_group_store_data;
		}
		
		public function getUserGroup($user_group_id, $field = false) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			$user_group = array(
			'name'       			=> $query->row['name'],
			'user_group_id'  		=> $query->row['user_group_id'],
			'template_prefix'       => $query->row['template_prefix'],
			'alert_namespace'       => $query->row['alert_namespace'],
			'ticket'      		    => $query->row['ticket'],
			'sip_queue'      		=> $query->row['sip_queue'],
			'bitrix_id'      		=> $query->row['bitrix_id'],	
			'permission' => unserialize($query->row['permission'])
			);
			
			if ($field && isset($user_group[$field])){
				return $user_group[$field];
				} else {		
				return $user_group;
			}
		}
		
		public function getUserGroupName($user_group_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			
			if (isset($query->row['name'])){
				return $query->row['name'];
				} else {
				return false;
			}
		}
		
		public function getUserGroupNameByQueue($sip_queue) {
			$query = $this->db->query("SELECT * FROM user_group WHERE sip_queue = '" . $this->db->escape($sip_queue) . "' LIMIT 1");
			
			return $query->row['name'];
			
		}
		
		public function getUserGroupBySipQueue($sip_queue, $field = 'name') {
			$query = $this->db->query("SELECT * FROM user_group WHERE sip_queue = '" . $this->db->escape($sip_queue) . "' LIMIT 1");
			
			return isset($query->row[$field])?$query->row[$field]:false;
			
		}
		
		public function getUserGroups($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "user_group WHERE 1";
			
			if (!empty($data['filter_ticket'])){
				$sql .= " AND ticket = '" . (int)$data['filter_ticket'] . "'";
			}
			
			$sql .= " ORDER BY name";	
			
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
		
		public function getTotalUserGroups() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");
			
			return $query->row['total'];
		}	
	}
?>
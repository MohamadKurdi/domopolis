<?php
	class ModelUserUser extends Model {
		public function addUser($data) {
			$this->db->query("INSERT INTO `user` SET 
			username 				= '" . $this->db->escape($data['username']) . "', 
			salt 					= '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password 				= '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			firstname 				= '" . $this->db->escape($data['firstname']) . "', 
			is_av 					= '" . (int)($data['is_av']) . "', 
			dev_template 			= '" . (int)($data['dev_template']) . "', 
			unlock_orders 			= '" . (int)($data['unlock_orders']) . "',
			do_transactions 		= '" . (int)($data['do_transactions']) . "',
			is_mainmanager  		= '" . (int)($data['is_mainmanager']) . "',  
			is_headsales  			= '" . (int)($data['is_headsales']) . "',
			lastname 				= '" . $this->db->escape($data['lastname']) . "', 
			email 					= '" . $this->db->escape($data['email']) . "',
			ticket 					= '" . (int)$data['ticket'] . "', 
			count_worktime 			= '" . (int)$data['count_worktime'] . "', 
			count_content 			= '" . (int)$data['count_content'] . "',
			edit_csi 				= '" . (int)$data['edit_csi'] . "', bitrix_id = '" . (int)$data['bitrix_id'] . "', 
			user_group_id 			= '" . (int)$data['user_group_id'] . "', status = '" . (int)$data['status'] . "', 
			internal_pbx_num 		= '" .  $this->db->escape($data['internal_pbx_num']) . "',
			internal_auth_pbx_num 	= '" .  $this->db->escape($data['internal_auth_pbx_num']) . "', 
			outbound_pbx_num 		= '" .  $this->db->escape($data['outbound_pbx_num']) . "', 
			date_added 				= NOW()");
		}
		
		public function editUser($user_id, $data) {
			$this->db->query("UPDATE `user` SET 
			username 				= '" . $this->db->escape($data['username']) . "',
			firstname 				= '" . $this->db->escape($data['firstname']) . "', 
			is_av 					= '" . (int)($data['is_av']) . "',
			dev_template 			= '" . (int)($data['dev_template']) . "', 
			unlock_orders 			= '" . (int)($data['unlock_orders']) . "',
			do_transactions 		= '" . (int)($data['do_transactions']) . "',
			is_mainmanager  		= '" . (int)($data['is_mainmanager']) . "',
			is_headsales  			= '" . (int)($data['is_headsales']) . "',
			own_orders 				= '" . (int)($data['own_orders']) . "', 
			lastname 				= '" . $this->db->escape($data['lastname']) . "', 
			email 					= '" . $this->db->escape($data['email']) . "', 
			user_group_id 			= '" . (int)$data['user_group_id'] . "', 
			status 					= '" . (int)$data['status'] . "',
			ticket 					= '" . (int)$data['ticket'] . "',
			count_worktime 			= '" . (int)$data['count_worktime'] . "',
			count_content 			= '" . (int)$data['count_content'] . "',
			edit_csi 				= '" . (int)$data['edit_csi'] . "',
			bitrix_id 				= '" . (int)$data['bitrix_id'] . "',
			internal_pbx_num 		= '" .  $this->db->escape($data['internal_pbx_num']) . "',
			internal_auth_pbx_num 	= '" . $this->db->escape($data['internal_auth_pbx_num']) . "',
			outbound_pbx_num 		= '" . $this->db->escape($data['outbound_pbx_num']) . "'
			WHERE user_id 			= '" . (int)$user_id . "'");
			
			if ($data['password']) {
				$this->db->query("UPDATE `user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
			}
		}
		
		public function editPassword($user_id, $password) {
			$this->db->query("UPDATE `user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
		}
		
		public function editCode($email, $code) {
			$this->db->query("UPDATE `user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}
		
		public function deleteUser($user_id) {
			$this->db->query("DELETE FROM `user` WHERE user_id = '" . (int)$user_id . "'");
		}
		
		public function getUser($user_id) {
			$query = $this->db->query("SELECT *, CONCAT(firstname, ' ', lastname) as realname  FROM `user` WHERE user_id = '" . (int)$user_id . "'");
			
			return $query->row;
		}
		
		public function getUserISHeadSales($user_id) {
			$query = $this->db->query("SELECT is_headsales FROM `user` WHERE user_id = '" . (int)$user_id . "'");
			
			return $query->row['is_headsales'];
		}
		
		public function getHeadSalesUserID() {
			$query = $this->db->query("SELECT user_id FROM `user` WHERE is_headsales = 1 LIMIT 1");
			
			if ($query->num_rows && isset($query->row['user_id'])){
				return $query->row['user_id'];
			}
			
			return false;
		}
		
		public function getHeadSalesUser() {
			$query = $this->db->query("SELECT user_id FROM `user` WHERE is_headsales = 1 LIMIT 1");
			
			if ($query->num_rows && isset($query->row['user_id'])){
				return $this->getUser($query->row['user_id']);
			}
			
			return false;
		}
		
		public function getUserGroupID($user_id) {
			$query = $this->db->query("SELECT user_group_id FROM `user` WHERE user_id = '" . (int)$user_id . "'");
			
			return $query->row['user_group_id'];
		}
		
		public function getUserByUsername($username) {
			$query = $this->db->query("SELECT * FROM `user` WHERE username = '" . $this->db->escape($username) . "'");
			
			return $query->row;
		}
		
		public function getUserIDByUsername($username) {
			$query = $this->db->query("SELECT * FROM `user` WHERE username = '" . $this->db->escape($username) . "'");
			
			if ($query->num_rows){
				return $query->row['user_id'];
				} else {
				return false;
			}
		}
		
		public function getUserBitrixIDByID($user_id) {
			$query = $this->db->query("SELECT bitrix_id FROM `user` WHERE user_id = '" . (int)$user_id . "'");
			
			if ($query->row){
				return $query->row['bitrix_id'];
				} else {
				return false;
			}
		}
		
		public function getUserBitrixIDByUserName($username) {
			$query = $this->db->query("SELECT bitrix_id FROM `user` WHERE username = '" . $this->db->escape($username) . "'");
			
			if ($query->row){
				return $query->row['bitrix_id'];
				} else {
				return false;
			}
		}	
		
		public function getUserByCode($code) {
			$query = $this->db->query("SELECT * FROM `user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");
			
			return $query->row;
		}
		
		public function getUserNameById($id) {
			$query = $this->db->query("SELECT username FROM `user` WHERE user_id = '" . $this->db->escape($id) . "' LIMIT 1");
			
			if ($query->row){
				return $query->row['username'];
				} else {
				return 'Unknown';
			}
		}
		
		public function getRealUserNameById($id) {
			$query = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) as realname FROM `user` WHERE user_id = '" . $this->db->escape($id) . "' LIMIT 1");
			
			if ($query->row){
				return $query->row['realname'];
				} else {
				return 'Auto';
			}
		}
		
		public function getRealUserFirstNameByUsername($username) {
			$query = $this->db->query("SELECT firstname as realname FROM `user` WHERE username = '" . $this->db->escape($username) . "' LIMIT 1");
			
			if ($query->row){
				return $query->row['realname'];
				} else {
				return 'Unknown';
			}
		}
		
		public function getRealUserLastNameById($id) {
			$query = $this->db->query("SELECT lastname as realname FROM `user` WHERE user_id = '" . $this->db->escape($id) . "' LIMIT 1");
			
			if ($query->row){
				return $query->row['realname'];
				} else {
				return 'Unknown';
			}
		}
		
		public function getUsers($data = array()) {
			$sql = "SELECT * FROM `user` WHERE 1";
			
			if (isset($data['internal_pbx_num']) && ($data['internal_pbx_num'])) {
				$sql .= " AND `internal_pbx_num` <> ''";
			}
			
			if (!empty($data['filter_ticket'])) {
				$sql .= " AND `ticket` = '" . (int)$data['filter_ticket'] . "' AND status > 0";
			}
			
			$sort_data = array(
			'username',
			'status',
			'date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY username";	
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
		
		public function getTotalUsers() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `user`");
			
			return $query->row['total'];
		}
		
		public function getTotalUsersByGroupId($user_group_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `user` WHERE user_group_id = '" . (int)$user_group_id . "'");
			
			return $query->row['total'];
		}
		
		public function getUsersByGroup($user_group_id, $enabled = false){
			if (!$enabled) {
				$query = $this->db->query("SELECT user_id, CONCAT(firstname, ' ', lastname) as realname, internal_pbx_num FROM `user` WHERE user_group_id = '" . (int)$user_group_id . "'");
				} else {
				$query = $this->db->query("SELECT user_id, CONCAT(firstname, ' ', lastname) as realname, internal_pbx_num FROM `user` WHERE user_group_id = '" . (int)$user_group_id . "' AND status = 1");
			}
			
			return $query->rows;
		}
		
		public function getUsersByGroups(array $user_group_id, $enabled = false){
			$user_group_id = implode(', ', $user_group_id);
			
			if (!$enabled) {
				$query = $this->db->query("SELECT user_id, CONCAT(firstname, ' ', lastname) as realname, internal_pbx_num FROM `user` WHERE user_group_id IN (" . $user_group_id . ")");
				} else {
				$query = $this->db->query("SELECT user_id, CONCAT(firstname, ' ', lastname) as realname, internal_pbx_num FROM `user` WHERE user_group_id IN (" . $user_group_id . ") AND status = 1");
			}			
			
			return $query->rows;
		}
		
		public function getUsersByGroupsOwnOrders(array $user_group_id, $enabled = false){
			$user_group_id = implode(', ', $user_group_id);
			
			if (!$enabled) {
				$query = $this->db->query("SELECT user_id, CONCAT(firstname, ' ', lastname) as realname, internal_pbx_num FROM `user` WHERE own_orders = 1 AND user_group_id IN (" . $user_group_id . ")");
				} else {
				$query = $this->db->query("SELECT user_id, CONCAT(firstname, ' ', lastname) as realname, internal_pbx_num FROM `user` WHERE own_orders = 1 AND user_group_id IN (" . $user_group_id . ") AND status = 1");
			}			
			
			return $query->rows;
		}
		
		public function getUsersTelephonyQueues(){
			
			$query = $this->db->query("SELECT * FROM `user_group` WHERE LENGTH(sip_queue) > 0");
			
			$groups = array();
			foreach ($query->rows as $row){
				
				$groups[] = array(
				'sip_queue' => $row['sip_queue'],
				'name'      => $row['name'],
				'users'     => $this->getUsersByGroup($row['user_group_id'], true)
				);
				
			}
			
			return $groups;			
		}
		
		public function getTotalUsersByEmail($email) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row['total'];
		}		
		
		public function getUserCalls($user_id, $data = array()){
			
			$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) as customer_name FROM customer_calls cc LEFT JOIN customer c ON cc.customer_id = c.customer_id WHERE cc.manager_id = '". (int)$user_id ."'";
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(cc.date_end) = DATE('" .$this->db->escape($data['filter_date_end']). "')";			
			}		
			
			if (!empty($data['filter_customer'])) {
				if (is_numeric($data['filter_customer'])) {
					$sql .= " AND cc.customer_id = '" .(int)$data['filter_customer']. "'";		
					} else {
					$sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
				}		
			}
			
			if (!empty($data['filter_telephone'])) {
				$sql .= " AND cc.customer_phone LIKE ('%" .$this->db->escape($data['filter_telephone']). "%')";		
			}
			
			$sort_data = array(
			'date_end'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY date_end";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
				} else {
				$sql .= " DESC";
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
			
			$result = $this->db->query($sql);
			
			return $result->rows;		
		}
		
		public function getTotalUserCallsAzazaOlolo($user_id){
			$sql = "SELECT COUNT(*) as total FROM customer_calls cc WHERE cc.manager_id = '". (int)$user_id ."' AND cc.length > 0";
			
			$result = $this->db->query($sql);
			
			return $result->row['total'];		
		}
		
		public function getTotalUserCalls($user_id, $data = array()){
			$sql = "SELECT COUNT(*) as total FROM customer_calls cc LEFT JOIN customer c ON cc.customer_id = c.customer_id WHERE cc.manager_id = '". (int)$user_id ."'";
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(cc.date_end) = DATE('" .$this->db->escape($data['filter_date_end']). "')";			
			}		
			
			if (!empty($data['filter_customer'])) {
				if (is_numeric($data['filter_customer'])) {
					$sql .= " AND cc.customer_id = '" .(int)$data['filter_customer']. "'";		
					} else {
					$sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
				}	
			}
			
			
			if (!empty($data['filter_telephone'])) {
				$sql .= " AND cc.customer_phone LIKE ('%" .$this->db->escape($data['filter_telephone']). "%')";		
			}
			
			
			$result = $this->db->query($sql);
			
			return $result->row['total'];			
		}
		
		public function getUserAlerts(){
			
			$query = $this->db->query("SELECT * FROM alertlog WHERE user_id = '" . (int)$this->user->getID() . "' ORDER BY datetime DESC LIMIT 150");
			
			return $query->rows;		
		}
		
	}	
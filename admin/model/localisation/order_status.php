<?php 
	class ModelLocalisationOrderStatus extends Model {
		public function addOrderStatus($data) {
		
			$status_txt_color = $status_bg_color = $status_fa_icon = $front_bg_color =  '';
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['status_txt_color'])){
				$status_txt_color = $data['order_status'][$this->config->get('config_language_id')]['status_txt_color'];
			}
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['status_bg_color'])){
				$status_bg_color = $data['order_status'][$this->config->get('config_language_id')]['status_bg_color'];
			}
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['status_fa_icon'])){
				$status_fa_icon = $data['order_status'][$this->config->get('config_language_id')]['status_fa_icon'];
			}
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['front_bg_color'])){
				$front_bg_color = $data['order_status'][$this->config->get('config_language_id')]['front_bg_color'];
			}
		
		
		
			foreach ($data['order_status'] as $language_id => $value) {

				if (isset($order_status_id)) {
					
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', status_bg_color = '" . $this->db->escape($value['status_bg_color']) . "', status_txt_color = '" . $this->db->escape($value['status_txt_color']) . "', status_fa_icon = '" . $this->db->escape($value['status_fa_icon']) . "', front_bg_color = '" . $this->db->escape($front_bg_color) . "'");
					
					
					} else {
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET  language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', status_bg_color = '" . $this->db->escape($value['status_bg_color']) . "', status_txt_color = '" . $this->db->escape($value['status_txt_color']) . "', status_fa_icon = '" . $this->db->escape($value['status_fa_icon']) . "', front_bg_color = '" . $this->db->escape($front_bg_color) . "'");
					
					$order_status_id = $this->db->getLastId();
				}
			}
			
			$this->cache->delete('order_status');
		}
		
		public function editOrderStatus($order_status_id, $data) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
			
			$status_txt_color = $status_bg_color = $status_fa_icon = $front_bg_color =  '';
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['status_txt_color'])){
				$status_txt_color = $data['order_status'][$this->config->get('config_language_id')]['status_txt_color'];
			}
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['status_bg_color'])){
				$status_bg_color = $data['order_status'][$this->config->get('config_language_id')]['status_bg_color'];
			}
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['status_fa_icon'])){
				$status_fa_icon = $data['order_status'][$this->config->get('config_language_id')]['status_fa_icon'];
			}
			
			if (!empty($data['order_status'][$this->config->get('config_language_id')]['front_bg_color'])){
				$front_bg_color = $data['order_status'][$this->config->get('config_language_id')]['front_bg_color'];
			}
			
			foreach ($data['order_status'] as $language_id => $value) {
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', status_bg_color = '" . $this->db->escape($status_bg_color) . "', status_txt_color = '" . $this->db->escape($status_txt_color) . "', status_fa_icon = '" . $this->db->escape($status_fa_icon) . "', front_bg_color = '" . $this->db->escape($front_bg_color) . "'");
				
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "order_status_linked WHERE order_status_id = '" . (int)$order_status_id . "'");
			
			if (isset($data['linked_order_status_ids'])){
				
				foreach ($data['linked_order_status_ids'] as $_losid){
					$this->db->query("INSERT INTO order_status_linked SET order_status_id = '" . (int)$order_status_id . "', linked_order_status_id = '" . (int)$_losid . "'");
				}
				
			}
			
			$this->cache->delete('order_status');
		}
		
		public function deleteOrderStatus($order_status_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
			
			$this->cache->delete('order_status');
		}
		
		public function getOrderStatus($order_status_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->row;
		}
		
		public function getOrderStatusName($order_status_id) {
			
			if ($order_status_id == 0){
				return 'Недооформленный';
			}
			
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			if ($query->row) {
				return $query->row['name'];
			} else return false;
		}
		
		public function getLinkedOrderStatusIds($order_status_id) {
			
			if ($order_status_id == 0){
				return array($this->config->get('config_order_status_id'));
			}
			
			$query = $this->db->query("SELECT linked_order_status_id FROM " . DB_PREFIX . "order_status_linked WHERE order_status_id = '" . (int)$order_status_id . "'");
			
			$statuses = array();
			foreach ($query->rows as $row){
				
				$statuses[] = $row['linked_order_status_id'];
				
			}
			
			return $statuses;
			}
			
			public function getOrderStatuses($data = array()) {
				if ($data) {
					$sql = "SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
					
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
					} else {
					$order_status_data = $this->cache->get('order_status.' . (int)$this->config->get('config_language_id'));
					
					if (!$order_status_data) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
						
						$order_status_data = $query->rows;
						
						$this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $order_status_data);
					}	
					
					return $order_status_data;				
				}
			}
			
			public function getAllowedOrderStatuses($order_status_id) {
				
				$allowed_statuses_query = $this->db->query("SELECT * FROM order_status_linked WHERE order_status_id = '" . (int)$order_status_id . "'");		
				$allowed_statuses_ids = array($order_status_id);
				
				foreach ($allowed_statuses_query->rows as $_row){
					$allowed_statuses_ids[] = $_row['linked_order_status_id'];
				}
				
				$allowed_statuses_ids = array_unique($allowed_statuses_ids);
				$allowed_statuses = array();
				
				foreach ($allowed_statuses_ids as $_id){
					$allowed_statuses[] = array(
					'order_status_id' => $_id,
					'name' => $this->getOrderStatusDescription($_id)
					);
				}
				
				if (!$order_status_id){
					$allowed_statuses[] = array(
					'order_status_id' => 1,
					'name' => $this->getOrderStatusDescription(1)
					);
					
					$allowed_statuses[] = array(
					'order_status_id' => 21,
					'name' => $this->getOrderStatusDescription(21)
					);
				}
				
				return $allowed_statuses;
				
			}
			
			public function getOrderStatusDescription($order_status_id) {
				
				if ($order_status_id == 0){
					return 'Недооформленный';
				}
				
				$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
				
				return $query->row['name'];
			}
			
			public function getOrderStatusDescriptions($order_status_id) {
				$order_status_data = array();
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
				
				foreach ($query->rows as $result) {
					$order_status_data[$result['language_id']] = array('name' => $result['name'], 'status_bg_color' => $result['status_bg_color'], 'status_txt_color' => $result['status_txt_color'], 'status_fa_icon' => $result['status_fa_icon'], 'front_bg_color' => $result['front_bg_color']);
				}
				
				return $order_status_data;
			}
			
			public function getTotalOrderStatuses() {
				$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
				return $query->row['total'];
			}	
		}
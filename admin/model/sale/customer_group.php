<?php
	class ModelSaleCustomerGroup extends Model {
		public function addCustomerGroup($data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', company_id_display = '" . (int)$data['company_id_display'] . "', company_id_required = '" . (int)$data['company_id_required'] . "', tax_id_display = '" . (int)$data['tax_id_display'] . "', tax_id_required = '" . (int)$data['tax_id_required'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
			
			$customer_group_id = $this->db->getLastId();
			
			foreach ($data['customer_group_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}	
		}
		
		public function editCustomerGroup($customer_group_id, $data) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', company_id_display = '" . (int)$data['company_id_display'] . "', company_id_required = '" . (int)$data['company_id_required'] . "', tax_id_display = '" . (int)$data['tax_id_display'] . "', tax_id_required = '" . (int)$data['tax_id_required'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			
			foreach ($data['customer_group_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
		}
		
		public function deleteCustomerGroup($customer_group_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		}
		
		public function getCustomerGroup($customer_group_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->row;
		}
		
		public function getCustomerGroups($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
			'cgd.name',
			'cg.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY cg.sort_order";	
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
		
		public function getCustomerGroupDescriptions($customer_group_id) {
			$customer_group_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			
			foreach ($query->rows as $result) {
				$customer_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
				);
			}
			
			return $customer_group_data;
		}
		
		public function getTotalCustomerGroups() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");
			
			return $query->row['total'];
		}
		
		
		public function getTotalCustomerSegments() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "segments");
			
			return $query->row['total'];
		}
		
		
		public function getCustomerSegments($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "segments ";
			
			$sort_data = array(
			'name',
			'sort_order',
			'sort_order ASC, name'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY sort_order ASC, name";	
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
		
		public function getCustomerSegment($segment_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "segments WHERE segment_id = '" . (int)$segment_id . "'");
			
			return $query->row;
		}
		
		
		public function addCustomerSegment($data) {
			
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "segments SET 
			name = '" . $this->db->escape($data['name']) . "', 
			description = '" . $this->db->escape($data['description']) . "', 
			txt_color = '" . $this->db->escape($data['txt_color']) . "', 
			bg_color = '" . $this->db->escape($data['bg_color']) . "',
			fa_icon = '" . $this->db->escape($data['fa_icon']) . "',
			`group` = '" . $this->db->escape($data['group']) . "',
			determination  = '" . $this->db->escape(serialize($data)) . "', 
			enabled = '" . (int)$data['enabled'] . "',
			new_days = '" . (int)$data['new_days'] . "',
			sort_order = '" . (int)$data['sort_order'] . "'");
			
			$segment_id = $this->db->getLastId();
			
			return $segment_id;
		}
		
		public function editCustomerSegment($segment_id, $data) {
			
			
			$this->db->query("UPDATE " . DB_PREFIX . "segments SET 
			name = '" . $this->db->escape($data['name']) . "', 
			description = '" . $this->db->escape($data['description']) . "', 
			txt_color = '" . $this->db->escape($data['txt_color']) . "', 
			bg_color = '" . $this->db->escape($data['bg_color']) . "',
			fa_icon = '" . $this->db->escape($data['fa_icon']) . "',
			`group` = '" . $this->db->escape($data['group']) . "',
			determination  = '" . $this->db->escape(serialize($data)) . "', 
			enabled = '" . (int)$data['enabled'] . "',
			new_days = '" . (int)$data['new_days'] . "',
			sort_order = '" . (int)$data['sort_order'] . "'
			WHERE segment_id = '" . (int)$segment_id . "'
			");
			
			$segment_id = $this->db->getLastId();
			
			return $segment_id;
		}
		
		public function deleteCustomerSegment($segment_id) {
			if ($segment_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "segments WHERE segment_id = '" . (int)$segment_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "customer_segments WHERE segment_id = '" . (int)$segment_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "segments_dynamics WHERE segment_id = '" . (int)$segment_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "customer_history WHERE segment_id = '" . (int)$segment_id . "'");
			}
		}
		
		public function countCustomerSegmentDynamics($segment_id)
		{
			
			$query = $this->db->query("SELECT count(*) as total FROM segments_dynamics WHERE segment_id = '" . (int)$segment_id . "'");
			
			
			return $query->row['total'];
		}
		
		public function clearCustomerSegmentDynamics($segment_id)
		{			
			$query = $this->db->query("DELETE FROM segments_dynamics WHERE segment_id = '" . (int)$segment_id . "'");
		}
		
		public function  getTotalCustomersBySegmentId($segment_id){
			
			$query = $this->db->query("SELECT count(*) as total FROM customer_segments WHERE segment_id = '" . (int)$segment_id . "'");
			
			
			return $query->row['total'];
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		}
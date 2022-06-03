<?php
	class ModelLocalisationCountry extends Model {
		public function addCountry($data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', warehouse_identifier = '" . $this->db->escape($data['warehouse_identifier']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "'");
			
			$this->cache->delete('country');
		}
		
		public function editCountry($country_id, $data) {
			$this->db->query("UPDATE " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', warehouse_identifier = '" . $this->db->escape($data['warehouse_identifier']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "' WHERE country_id = '" . (int)$country_id . "'");
			
			$this->cache->delete('country');
		}
		
		public function deleteCountry($country_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
			
			$this->cache->delete('country');
		}
		
		public function getCountry($country_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
			
			return $query->row;
		}
		
		public function getCountryISO2($country_id) {
			
			if (!$country_id) return false;
			
			$query = $this->db->query("SELECT iso_code_2 FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
			
			return $query->row['iso_code_2'];
		}
		
		public function getCountryName($country_id) {
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
			
			return $query->row['name'];
		}
		
		
		public function getCountriesStructuredByID($data = array()) {		
			$sql = "SELECT * FROM " . DB_PREFIX . "country";
			
			$sort_data = array(
			'name',
			'iso_code_2',
			'iso_code_3'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY name";	
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
			
			$country_data = array();
			
			foreach ($query->rows as $row){
				$country_data[$row['country_id']] = $row;
			}
			
			return $country_data;		
	}
	
	public function getCountries($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "country";
			
			$sort_data = array(
			'name',
			'iso_code_2',
			'iso_code_3'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY name";	
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
			} else {
			$country_data = $this->cache->get('country');
			
			if (!$country_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");
				
				$country_data = $query->rows;
				
				$this->cache->set('country', $country_data);
			}
			
			return $country_data;			
		}	
		}
		
		public function getWarehouses() {
			$query = $this->db->query("SELECT warehouse_identifier, name FROM " . DB_PREFIX . "country WHERE LENGTH(warehouse_identifier) > 0");
			
			$result = array();
			foreach ($query->rows as $row){
				$result[$row['warehouse_identifier']] = $row['name'];
			}
			
			return $result;
		}	
		
		public function getTotalCountries() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");
			
			return $query->row['total'];
		}	
	}
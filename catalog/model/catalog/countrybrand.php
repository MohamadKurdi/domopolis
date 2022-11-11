<?php
	class ModelCatalogCountrybrand extends Model {
		public function getCountrybrand($countrybrand_id) {
			$query = $this->db->query("SELECT * FROM countrybrand m LEFT JOIN countrybrand_description md ON (m.countrybrand_id = md.countrybrand_id) LEFT JOIN countrybrand_to_store m2s ON (m.countrybrand_id = m2s.countrybrand_id) WHERE m.countrybrand_id = '" . (int)$countrybrand_id . "' AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "' OR md.language_id is null) AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if (!empty($query->row['name_overload'])){
				$query->row['seo_title'] = str_replace($query->row['name'], $query->row['name_overload'], $query->row['seo_title']);
				$query->row['seo_h1'] = str_replace($query->row['name'], $query->row['name_overload'], $query->row['seo_h1']);
				$query->row['meta_description'] = str_replace($query->row['name'], $query->row['name_overload'], $query->row['meta_description']);
				
				$query->row['name'] = $query->row['name_overload'];
			}
			
			return $query->row;	
		}
		
		
		
		public function getCountrybrandImages($countrybrand_id) {
		/*	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "countrybrand_image WHERE countrybrand_id = '" . (int)$countrybrand_id . "'");
			
			return $query->rows;
		*/
			return [];
		}
		
		
		public function getCountrybrands($data = array()) {
			if ($data) {
				$sql = "SELECT * FROM countrybrand m LEFT JOIN countrybrand_description md ON (m.countrybrand_id = md.countrybrand_id) LEFT JOIN countrybrand_to_store m2s ON (m.countrybrand_id = m2s.countrybrand_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				
				if (isset($data['filter_parent_id']) and $data['filter_parent_id'] > 0) {
					$sql .=" AND m.parent_id = '". (int)$data['filter_parent_id'] ."'";				
				}
				
				$sort_data = array(
				'name',
				'sort_order'
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
				$countrybrand_data = $this->cache->get('countrybrand.' . (int)$this->config->get('config_store_id') . (int)$this->config->get('config_language_id'));
				
				if (!$countrybrand_data) {
					$query = $this->db->query("SELECT * FROM countrybrand m LEFT JOIN countrybrand_description md ON (m.countrybrand_id = md.countrybrand_id) LEFT JOIN countrybrand_to_store m2s ON (m.countrybrand_id = m2s.countrybrand_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");
					
					foreach ($query->rows as &$row){
						if (!empty($row['name_overload'])){
							$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
							$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
							$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
							
							$row['name'] = $row['name_overload'];
						}
					}
					
					$countrybrand_data = $query->rows;
					
					$this->cache->set('countrybrand.' . (int)$this->config->get('config_store_id') . (int)$this->config->get('config_language_id'), $countrybrand_data);
				}
				
				return $countrybrand_data;
			}	
		} 
		
		public function getTotalCountrybrands() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM countrybrand c LEFT JOIN countrybrand_to_store c2s ON (c.countrybrand_id = c2s.countrybrand_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			return $query->row['total'];
		}
	}
<?php
	class ModelCatalogCollection extends Model {
		public function getCollection($collection_id) {
			$query = $this->db->query("SELECT * FROM collection m LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) WHERE m.collection_id = '" . (int)$collection_id . "' AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "' OR md.language_id is null) AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if (!empty($query->row['name_overload'])){
				$query->row['seo_title'] = str_replace($query->row['name'], $query->row['name_overload'], $query->row['seo_title']);
				$query->row['seo_h1'] = str_replace($query->row['name'], $query->row['name_overload'], $query->row['seo_h1']);
				$query->row['meta_description'] = str_replace($query->row['name'], $query->row['name_overload'], $query->row['meta_description']);
				
				$query->row['name'] = $query->row['name_overload'];
			}
			
			return $query->row;	
		}
		
		public function getCollectionsByManufacturer($manufacturer_id, $limit = 50){
			$query = $this->db->query("
			SELECT * FROM collection m		
			LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) 
			LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) 
			WHERE m.collection_id IN (SELECT DISTINCT collection_id FROM collection WHERE manufacturer_id='" . (int)$manufacturer_id . "') 
			AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'			
			AND m.virtual = 0			
			ORDER BY m.name, m.sort_order ASC
			LIMIT ".$limit."
			");
			
			foreach ($query->rows as &$row){
				if (!empty($row['name_overload'])){
					$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
					$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
					$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
					
					$row['name'] = $row['name_overload'];
				}
			}
			
			return $query->rows;		
		}
		
		public function getCollectionsByManufacturerNoVirtualNoParent($manufacturer_id, $limit = 150){
			$query = $this->db->query("
			SELECT * FROM collection m		
			LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) 
			LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) 
			WHERE m.collection_id IN (SELECT DISTINCT collection_id FROM collection WHERE manufacturer_id='" . (int)$manufacturer_id . "') 
			AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND m.virtual = 0
			AND m.parent_id = 0
			ORDER BY m.name, m.sort_order ASC
			LIMIT ".$limit."
			");
			
			foreach ($query->rows as &$row){
				if (!empty($row['name_overload'])){
					$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
					$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
					$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
					
					$row['name'] = $row['name_overload'];
				}
			}
			
			return $query->rows;		
		}	
		
		public function getCollectionsByManufacturerNoVirtualForShowAll($manufacturer_id, $limit = 150){
			$query = $this->db->query("
			SELECT * FROM collection m		
			LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) 
			LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) 
			WHERE m.collection_id IN (SELECT DISTINCT collection_id FROM collection WHERE manufacturer_id='" . (int)$manufacturer_id . "') 
			AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND m.virtual = 0 
			AND m.parent_id NOT IN (SELECT DISTINCT c2.collection_id FROM collection c2 WHERE c2.virtual = 0)						
			ORDER BY m.name, m.sort_order ASC
			LIMIT ".$limit."
			");
			
			foreach ($query->rows as &$row){
				if (!empty($row['name_overload'])){
					$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
					$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
					$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
					
					$row['name'] = $row['name_overload'];
				}
			}
			
			return $query->rows;		
		}	
		
		
		public function getCollectionsByManufacturerNoVirtual($manufacturer_id, $limit = 150){
			$query = $this->db->query("
			SELECT * FROM collection m		
			LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) 
			LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) 
			WHERE m.collection_id IN (SELECT DISTINCT collection_id FROM collection WHERE manufacturer_id='" . (int)$manufacturer_id . "') 
			AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND m.virtual = 0
			ORDER BY m.name, m.sort_order ASC
			LIMIT ".$limit."
			");
			
			foreach ($query->rows as &$row){
				if (!empty($row['name_overload'])){
					$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
					$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
					$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
					
					$row['name'] = $row['name_overload'];
				}
			}
			
			return $query->rows;		
		}	
		
		
		public function getCollectionsByManufacturerOnlyVirtual($manufacturer_id, $limit = 150){
			$query = $this->db->query("
			SELECT * FROM collection m		
			LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) 
			LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) 
			WHERE m.collection_id IN (SELECT DISTINCT collection_id FROM collection WHERE manufacturer_id='" . (int)$manufacturer_id . "') 
			AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND m.virtual = 1
			ORDER BY m.name, m.sort_order ASC
			LIMIT ".$limit."
			");
			
			foreach ($query->rows as &$row){
				if (!empty($row['name_overload'])){
					$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
					$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
					$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
					
					$row['name'] = $row['name_overload'];
				}
			}
			
			return $query->rows;		
		}	
		
		public function getCollectionImages($collection_id) {
			$query = $this->db->query("SELECT * FROM collection_image WHERE collection_id = '" . (int)$collection_id . "'");
			
			return $query->rows;
		}
		
		
		public function getCollections($data = array()) {
			if ($data) {
				$sql = "SELECT * FROM collection m LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				
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
				$collection_data = $this->cache->get('collection.' . (int)$this->config->get('config_store_id') . (int)$this->config->get('config_language_id'));
				
				if (!$collection_data) {
					$query = $this->db->query("SELECT * FROM collection m LEFT JOIN collection_description md ON (m.collection_id = md.collection_id) LEFT JOIN collection_to_store m2s ON (m.collection_id = m2s.collection_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");
					
					foreach ($query->rows as &$row){
						if (!empty($row['name_overload'])){
							$row['seo_title'] = str_replace($row['name'], $row['name_overload'], $row['seo_title']);
							$row['seo_h1'] = str_replace($row['name'], $row['name_overload'], $row['seo_h1']);
							$row['meta_description'] = str_replace($row['name'], $row['name_overload'], $row['meta_description']);
							
							$row['name'] = $row['name_overload'];
						}
					}
					
					$collection_data = $query->rows;
					
					$this->cache->set('collection.' . (int)$this->config->get('config_store_id') . (int)$this->config->get('config_language_id'), $collection_data);
				}
				
				return $collection_data;
			}	
		} 
		
		public function getTotalCollections() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM collection c LEFT JOIN collection_to_store c2s ON (c.collection_id = c2s.collection_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			return $query->row['total'];
		}
	}
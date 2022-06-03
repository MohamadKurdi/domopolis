<?php
	class ModelCatalogManufacturer extends Model {
		public function getManufacturer($manufacturer_id) {
			$query = $this->db->query("SELECT * FROM manufacturer m LEFT JOIN manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND (md.language_id = '" . (int)$this->config->get('config_language_id') . "' OR md.language_id is null) AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			return $query->row;	
		}
		
		public function getAllCountryBrands(){
			$query = $this->db->query("SELECT * FROM countrybrand oc
			LEFT JOIN countrybrand_description ocd ON (oc.countrybrand_id = ocd.countrybrand_id)  
			LEFT JOIN countrybrand_to_store oc2s ON (oc.countrybrand_id = oc2s.countrybrand_id)  
			WHERE ocd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND oc2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			ORDER BY name ASC");
			
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
		
		public function getTreeCategoriesByManufacturer($manufacturer_id, $parent_id = 0) {
			
			$query = $this->db->query("
			SELECT DISTINCT c.category_id, сd.name, menu_name, c.image 
			FROM category_path cp	
			LEFT JOIN category c ON (c.category_id = cp.path_id)
			LEFT JOIN category_description сd ON (сd.category_id = c.category_id) 
			LEFT JOIN category_to_store с2s ON (c.category_id = с2s.category_id)
			LEFT JOIN keyworder k ON (k.category_id = c.category_id)
			LEFT JOIN keyworder_description kd ON (k.keyworder_id = kd.keyworder_id)
			WHERE k.manufacturer_id = '" . (int)$manufacturer_id . "'
			AND kd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND с2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND сd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND c.parent_id = '" . (int)$parent_id . "'			
			AND c.status = 1
			AND (c.category_id NOT IN (6614, 6474))
			AND kd.keyworder_status = 1
			ORDER BY name ASC");		
			
			return $query->rows;
		}
		
		public function getAllCategoriesByManufacturer($manufacturer_id) {
			
			$query = $this->db->query("
			SELECT DISTINCT c.category_id, сd.name, menu_name, c.image 
			FROM category c
			LEFT JOIN category_description сd ON (сd.category_id = c.category_id) 
			LEFT JOIN category_to_store с2s ON (c.category_id = с2s.category_id)
			LEFT JOIN keyworder k ON (k.category_id = c.category_id)
			LEFT JOIN keyworder_description kd ON (k.keyworder_id = kd.keyworder_id)
			WHERE k.manufacturer_id = '" . (int)$manufacturer_id . "'
			AND kd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND с2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND сd.language_id = '" . (int)$this->config->get('config_language_id') . "' 		
			AND c.status = 1
			AND kd.keyworder_status = 1
			ORDER BY name ASC");		
			
			return $query->rows;
		}
		
		public function getPopularManufacturersByCategories($category_id, $limit = 10){
			
			
			
			
			
			
			
			
			
			
			
		}
		
		public function getManufacturerDescriptions($manufacturer_id) {
			$manufacturer_description_data = array();
			
			$query = $this->db->query("SELECT * FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				$manufacturer_description_data[$result['language_id']] = array(
				'meta_keyword'     => isset($result['meta_keyword']) ? $result['meta_keyword'] : '',
				'meta_description' => isset($result['meta_description']) ? $result['meta_description'] : '',
				'short_description' => isset($result['short_description']) ? $result['short_description'] : '',
				'description'      => isset($result['description']) ? $result['description'] : '',
				'location'      => isset($result['location']) ? $result['location'] : '',
				'seo_title'     => isset($result['seo_title']) ? $result['seo_title'] : ''
				);
			}
			
			return $manufacturer_description_data;
		}
		
		public function getTotalManufacturers() {
			$query = $this->db->query("SELECT COUNT(*) as total FROM manufacturer m LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			return $query->row['total'];				
		}		
		
		
		public function getManufacturers($data = array()) {
			if ($data) {
				$sql = "SELECT * FROM manufacturer m LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)
				LEFT JOIN manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) 
				WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "'";
				
				if (isset($data['menu_brand'])) {
					$sql .= " AND menu_brand = '" .(int)$data['menu_brand']. "'";
				}
				
				if (isset($data['filter_country'])) {
					$sql .= " AND md.location = '" . $this->db->escape($data['filter_country']) . "'";
				}
				
				
				$sort_data = array(
				'name',
				'm.sort_order'
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
				
				if (empty($data['start'])){
					$data['start'] = 0;
				}
				
				if (empty($data['limit'])){
					$data['limit'] = 20;
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
				$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));
				
				if (!$manufacturer_data) {
					$query = $this->db->query("SELECT * FROM manufacturer m LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");
					
					$manufacturer_data = $query->rows;
					
					$this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
				}
				
				return $manufacturer_data;
			}	
		} 
		
		public function getManufacturersBlocks($manufacturer_id){
			$query = $this->db->query("SELECT * FROM manufacturer_page_content WHERE manufacturer_id = '" .(int)$manufacturer_id. "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order");
			
			return false;		
			
		}
		
		public function getmanufacturerLayoutId($manufacturer_id) {
			$query = $this->db->query("SELECT * FROM manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if ($query->num_rows) {
				return $query->row['layout_id'];
				} else {
				return $this->config->get('config_layout_manufacturer');
			}
		}
		
		public function getManufacturerContentByManufacturerId ($manufacturer_id) {
			$sql = "SELECT * FROM manufacturer_page_content WHERE `language_id` = '".(int)$this->config->get('config_language_id')."'
			AND `manufacturer_id` = '".(int)$manufacturer_id ."' ORDER BY sort_order ASC";
			
			$result = $this->db->query($sql);				
			
			return $result->rows;
		}
	}		
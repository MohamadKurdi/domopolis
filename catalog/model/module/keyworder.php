<?php
	class ModelModulekeyworder extends Model {
		public function getManufacturersByCategory($category_id) {
			
			$keyworder_data = $this->cache->get('keyworder.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.c.' . (int)$category_id);
			
			if (!$keyworder_data) {
				
				$keyworder_data = array();
				
				$query = $this->db->query("
				SELECT DISTINCT m.manufacturer_id, m.name, m.image, m.sort_order FROM category_path cp 
				LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id) 
				LEFT JOIN product p ON (p.product_id = p2c.product_id) 
				LEFT JOIN manufacturer m ON (m.manufacturer_id = p.manufacturer_id) 
				LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 			
				WHERE 
				cp.path_id = '" . (int)$category_id . "' AND 
				m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND
				p.status = 1
				ORDER BY m.name ASC
				");
				
				$keyworder_data = $query->rows;
				$this->cache->set('keyworder.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.c.' . (int)$category_id, $keyworder_data);
			}
			
			return $keyworder_data;
		}
		
		public function getPopularManufacturersByCategories($category_id, $limit = 5){
			
			$sql = "SELECT 
			DISTINCT m.manufacturer_id, m.name, m.image, m.sort_order,
			COUNT(DISTINCT order_id) as order_total
			FROM order_product op
			LEFT JOIN product p ON (p.product_id = op.product_id)
			LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
			LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
			WHERE op.product_id IN (
			SELECT DISTINCT p2c.product_id FROM category_path cp 
			LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id)
			WHERE cp.path_id = '" . (int)$category_id . "'       
			)
			AND p.status = 1
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			GROUP BY p.manufacturer_id
			ORDER BY order_total DESC
			LIMIT " . (int)$limit . ""; 
			
			
		//	$this->log->debugsql($sql);
			
			$query = $this->db->query($sql);
			
			return $query->rows;
			
			
			
		}
		
		public function getCategoriesByManufacturer($manufacturer_id) {
			
			$keyworder_data = $this->cache->get('keyworder.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.m.' . (int)$manufacturer_id);
			
			if (!$keyworder_data) {
			$keyworder_data = array();
			
			/*	$query = $this->db->query("
				SELECT DISTINCT c.category_id, сd.name, c.image 
				FROM product_to_category p2c
				LEFT JOIN product p ON (p.product_id = p2c.product_id) 
				LEFT JOIN category c ON (c.category_id = p2c.category_id)
				LEFT JOIN category_description сd ON (сd.category_id = c.category_id) 
				LEFT JOIN category_to_store с2s ON (c.category_id = с2s.category_id) 
				LEFT JOIN keyworder k ON (k.category_id = c.category_id) 
				LEFT JOIN keyworder_description kd ON (kd.keyworder_id = k.keyworder_id)
				WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' 
				AND с2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
				AND сd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND p.status = 1 
				AND c.status = 1 
				AND k.manufacturer_id = '" . (int)$manufacturer_id . "' AND 
				kd.category_status = 1 AND 
				kd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND kd.keyworder_status = 1
				ORDER BY name ASC");
			*/
			$query = $this->db->query("
			SELECT DISTINCT c.category_id, сd.name, c.image 
			FROM product_to_category p2c
			LEFT JOIN product p ON (p.product_id = p2c.product_id) 
			LEFT JOIN category c ON (c.category_id = p2c.category_id)
			LEFT JOIN category_description сd ON (сd.category_id = c.category_id) 
			LEFT JOIN category_to_store с2s ON (c.category_id = с2s.category_id) 
			LEFT JOIN keyworder k ON (k.category_id = c.category_id) 
			LEFT JOIN keyworder_description kd ON (kd.keyworder_id = k.keyworder_id)
			WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' 
			AND с2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND сd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.status = 1 
			AND c.status = 1 
			AND k.manufacturer_id = '" . (int)$manufacturer_id . "' AND 
			kd.category_status = 1 			
			AND kd.keyworder_status = 1
			ORDER BY name ASC");
			
			$keyworder_data = $query->rows;
			
			$this->cache->set('keyworder.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.m.' . (int)$manufacturer_id, $keyworder_data);
		}
		
		return $keyworder_data;
		}
		
		public function getKeyworderExists($category_id, $manufacturer_id){
			
			$query = $this->db->query("
			SELECT kd.keyworder_id FROM keyworder k 
			LEFT JOIN keyworder_description kd ON k.keyworder_id = kd.keyworder_id
			WHERE 
			k.manufacturer_id = '" . (int)$manufacturer_id . "' AND
			k.category_id = '" . (int)$category_id . "' AND			
			kd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			LIMIT 1			
			");
			
			if ($query->row) {
				return true;
				} else {
				return false;
			}
			
		}
		
		public function getKeyworderImage($category_id, $manufacturer_id){
			$query = $this->db->query("
			SELECT kd.image FROM keyworder k 
			LEFT JOIN keyworder_description kd ON k.keyworder_id = kd.keyworder_id
			WHERE 
			k.manufacturer_id = '" . (int)$manufacturer_id . "' AND
			k.category_id = '" . (int)$category_id . "' AND			
			kd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			LIMIT 1			
			");
			
			if ($query->row) {
				return $query->row['image'];
				} else {
				return false;
			}
		}
		
		public function getKeyworderName($category_id, $manufacturer_id){
			$query = $this->db->query("
			SELECT kd.seo_h1 as h1 FROM keyworder k 
			LEFT JOIN keyworder_description kd ON k.keyworder_id = kd.keyworder_id
			WHERE 
			k.manufacturer_id = '" . (int)$manufacturer_id . "' AND
			k.category_id = '" . (int)$category_id . "' AND			
			kd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			LIMIT 1			
			");
			
			if ($query->row) {
				return $query->row['h1'];
				} else {
				return false;
			}
		}
		
		public function getCategory($category_id, $manufacturer_id) {
			$arr = array();
			
			$query = $this->db->query("
			SELECT DISTINCT c.category_id, c.image, cd.name, cd.description, cd.seo_h1, cd.seo_title, cd.alt_image, cd.title_image, cd.meta_keyword, cd.meta_description, kd.description AS new_description, kd.seo_h1 AS new_h1, kd.seo_title AS new_title, kd.meta_keyword AS new_meta_keyword, kd.meta_description AS new_meta_description, m.name AS manufacturer_name, md.description AS manufacturer_description, md.seo_h1 AS manufacturer_seo_h1, md.seo_title AS manufacturer_seo_title, md.meta_keyword AS manufacturer_meta_keyword, md.meta_description AS manufacturer_meta_description, m.image AS manufacturer_image, kd.keyworder_status 
			FROM category c
			LEFT JOIN category_description cd ON (c.category_id = cd.category_id) 
			LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) 
			LEFT JOIN keyworder k ON (k.manufacturer_id = '" . (int)$manufacturer_id . "') 
			LEFT JOIN keyworder_description kd ON (kd.keyworder_id = k.keyworder_id) 
			LEFT JOIN manufacturer m ON (m.manufacturer_id = '" . (int)$manufacturer_id . "') 
			LEFT JOIN manufacturer_description md ON (md.manufacturer_id = m.manufacturer_id) 
			LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
			WHERE c.category_id = '" . (int)$category_id . "'
			AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND m.manufacturer_id = '" . (int)$manufacturer_id . "' 
			AND md.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND k.category_id = '" . (int)$category_id . "' 
			AND kd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND c.status = 1
			LIMIT 1");
			
			$arr['data'] = $query->row;
			
			$arr['template'] = $this->config->get('keyworder_template');
			
			return $arr;
		}
	}
?>
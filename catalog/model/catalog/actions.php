<?php
	class ModelCatalogActions extends Model {
		public function getActions($actions_id) {
			$query = $this->db->query("SELECT DISTINCT *, (SELECT code FROM coupon WHERE action_id = n.actions_id LIMIT 1) AS coupon FROM `actions` n 
			LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id) 
			LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id) 
			WHERE n.actions_id = '" . (int)$actions_id . "' 
			AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");
			
			if (!empty($query->row['image_overload'])){
				$query->row['image'] = $query->row['image_overload'];
			}
			
			if (!empty($query->row['image_to_cat_overload'])){
				$query->row['image_to_cat'] = $query->row['image_to_cat_overload'];
			}
			
			return $query->row;
		}
		
		public function getActiveAction($actions_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM `actions` n LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id) LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id) WHERE n.actions_id = '" . (int)$actions_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' AND n.date_end > UNIX_TIMESTAMP()");
			
			if (!empty($query->row['image_overload'])){
				$query->row['image'] = $query->row['image_overload'];
			}
			
			if (!empty($query->row['image_to_cat_overload'])){
				$query->row['image_to_cat'] = $query->row['image_to_cat_overload'];
			}
			
			return $query->row;
		}
		
		public function getActionsRAND($limit = 3) {
			$query = $this->db->query("SELECT DISTINCT * FROM `actions` n LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id) LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' AND n.date_end > UNIX_TIMESTAMP() ORDER BY RAND()  LIMIT 0," . (int)$limit);
			
			foreach ($query->rows as &$row){
				if (!empty($row['image_overload'])){
					$row['image'] = $row['image_overload'];
				}
				
				if (!empty($query->row['image_to_cat_overload'])){
					$row['image_to_cat'] = $row['image_to_cat_overload'];
				}
				
			}
			
			return $query->rows;
		}
		
		public function getAllActions() {
			$query = $this->db->query("SELECT DISTINCT * FROM `actions` n LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id) LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1'");
			
			foreach ($query->rows as &$row){
				if (!empty($row['image_overload'])){
					$row['image'] = $row['image_overload'];
				}
				
				if (!empty($query->row['image_to_cat_overload'])){
					$row['image_to_cat'] = $row['image_to_cat_overload'];
				}
				
			}
			
			return $query->rows;
		}
		
		public function getCategoryActions($category_id) {
			$actions_category_data = array();
			
			$query = $this->db->query("SELECT * FROM actions_to_category WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				if ($action = $this->getActiveAction($result['actions_id'])){				
					$actions_category_data[$result['actions_id']] = $action;
				}
			}			
			
			return $actions_category_data;
		}
		
		public function getManufacturerActions($manufacturer_id) {
			$actions_manufacturer_data = array();
			
			$query = $this->db->query("SELECT * FROM actions WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				if ($action = $this->getActiveAction($result['actions_id'])){				
					$actions_manufacturer_data[$result['actions_id']] = $action;
				}
			}			
			
			return $actions_manufacturer_data;
		}
		
		
		public function getProductIdsByAdditionalOfferGroup($ao_group){
			$query = $this->db->query("SELECT DISTINCT product_id FROM `product_additional_offer` WHERE `ao_group` LIKE '" .$this->db->escape($ao_group). "'");
			
			$results = array();
			
			foreach ($query->rows as $row){
				$results[] = $row['product_id'];
			}
			
			return $results; 
		}
		
		public function getActionsArchive ($start, $limit) {
			return $this->getActionsAll($start, $limit, true);
		}
		
		public function getActionsAll($start = 0, $limit = 5, $archive = false) {
			$actionStatus = 1;
			$znak = ">";
			if ($archive) {
				$actionStatus = 0;
				$znak = "<";
			}
			$query = $this->db->query("SELECT
			n.actions_id,
			n.image,			
			n.date_start,
			n.date_end,
			n.ao_group,
			nd.caption,
			nd.description,
			nd.image_overload,
			nd.anonnce,
			IF (n.date_end < UNIX_TIMESTAMP(), 1, 0) as archive
			FROM `actions` n 
			LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id)
			LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id)
			WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'  
			AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "'			
			ORDER BY (n.date_end >= NOW()), n.date_end DESC, n.date_start DESC LIMIT " . (int)$start . "," . (int)$limit);
			
			foreach ($query->rows as &$row){
				if (!empty($row['image_overload'])){
					$row['image'] = $row['image_overload'];
				}	
			}	
			
			return $query->rows;
		}
		
		public function getAllActiveActions($currentActionId) {
			$query = $this->db->query("SELECT
			n.actions_id,
			n.image,
			n.date_start,
			n.date_end,
			n.ao_group,
			nd.caption,
			nd.description,
			nd.image_overload,
			nd.anonnce
			FROM `actions` n
			LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id)
			LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id)
			WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND n.date_end > '".time()."'
			AND n.actions_id <> '".(int)$currentActionId."'");
			
			foreach ($query->rows as &$row){
				if (!empty($row['image_overload'])){
					$row['image'] = $row['image_overload'];
				}	
			}	
			
			return $query->rows;
		}
		
		
		public function getAdditionalActions($currentActionId) {
			$query = $this->db->query("SELECT
			n.actions_id,
			n.image,
			n.date_start,
			n.date_end,
			n.ao_group,
			nd.caption,
			nd.description,
			nd.image_overload,
			nd.anonnce
			FROM `actions` n
			LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id)
			LEFT JOIN `actions_to_store` n2s ON (n.actions_id = n2s.actions_id)
			WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND n.date_end > '".time()."'
			AND n.actions_id <> '".(int)$currentActionId."'
			ORDER BY RAND() LIMIT 0, 3");
			
			foreach ($query->rows as &$row){
				if (!empty($row['image_overload'])){
					$row['image'] = $row['image_overload'];
				}	
			}	
			
			return $query->rows;
		}		
		
		public function getActionsLayoutId($actions_id) {
			$query = $this->db->query("SELECT * FROM actions_to_layout WHERE actions_id = '" . (int)$actions_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if ($query->num_rows) {
				return $query->row['layout_id'];
				} else {
				return $this->config->get('config_layout_actions');
			}
		}
		public function getActionsTotal() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `actions` n WHERE 1");
			return $query->row['total'];
		}
		
		public function getActionsArchiveTotal () {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `actions` n WHERE n.date_end < '".time()."'");
			return $query->row['total'];
		}
		
		public function getMonthName($m) {			
			$month['ru'] = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
			$month['uk'] = array('січня', 'лютого', 'березня', 'квітня', 'травня', 'червня', 'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня');
			
			if (!empty($month[$this->language->get('code')])){
				return $month[$this->language->get('code')][$m - 1];
				} else {
				return $month['ru'][$m - 1];
			}
		}
	}				
<?php
	class ModelCatalogActions extends Model {
		
		public function addActions($data) {
			/* Format date & time */
			$data['date_start'] = strtotime( preg_replace('|^([0-9]{2})-([0-9]{2})-([0-9]{4})|', "\\3-\\2-\\1", $data['date_start']) . ':00' );
			$data['date_end'] = strtotime( preg_replace('|^([0-9]{2})-([0-9]{2})-([0-9]{4})|', "\\3-\\2-\\1", $data['date_end']). ':00' );
			
			if( isset($data['product_related']) && $data['product_related'] != '' ) {
				$product_related = implode(',', $data['product_related']);
				} else {
				$product_related = '';
			}
			
			$this->db->query("INSERT INTO `actions` SET 
			`image` = '" . $this->db->escape($data['image']) . "', 
			`image_to_cat` = '" . $this->db->escape($data['image_to_cat']) . "', 
			`status` = '" . (int)$data['status'] . "',
			`fancybox` = '".(int)$data['fancybox']."',
			`date_start` = '".(int)$data['date_start']."',
			`date_end` = '".(int)$data['date_end']."',
			`only_in_stock` = '" . (int)$data['only_in_stock'] . "',
			`product_related` = '" . $product_related . "',
			`manufacturer_id` = '" . (int)$data['manufacturer_id'] . "',
			`deletenotinstock` = '" . (int)$data['deletenotinstock'] . "',
			`display_all_active` = '" . (int)$data['display_all_active'] . "',
			`ao_group` = '" . $this->db->escape($data['ao_group']) . "'
			");
			
			
			
			$actions_id = $this->db->getLastId(); 
			$this->updateActionsDescription($actions_id, $data['actions_description']);
			
			
			$this->db->query("DELETE FROM `actions_to_product` WHERE actions_id = '" . $actions_id . "'");
			foreach ($data['product_related'] as $product_id) {
				$this->db->query("INSERT INTO `actions_to_product` SET `actions_id` = '" . (int)$actions_id . "', `product_id` = '" . (int)$product_id . "'");
			}
			
			if (isset($data['actions_store'])) {
				foreach ($data['actions_store'] as $store_id) {
					$this->db->query("INSERT INTO `actions_to_store` SET `actions_id` = '" . (int)$actions_id . "', `store_id` = '" . (int)$store_id . "'");
				}
			}
			
			if (isset($data['actions_layout'])) {
				foreach ($data['actions_layout'] as $store_id => $layout) {
					if ($layout) {
						$this->db->query("INSERT INTO `actions_to_layout` SET `actions_id` = '" . (int)$actions_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
			
			$this->db->query("DELETE FROM actions_to_category WHERE actions_id = '" . (int)$actions_id . "'");
			
			if (isset($data['actions_category'])) {
				foreach ($data['actions_category'] as $category_id) {
					$this->db->query("INSERT INTO actions_to_category SET actions_id = '" . (int)$actions_id . "', category_id = '" . (int)$category_id . "'");
				}		
			}
			
			$this->db->query("DELETE FROM actions_to_category_in WHERE actions_id = '" . (int)$actions_id . "'");
			
			if (isset($data['actions_category_in'])) {
				foreach ($data['actions_category_in'] as $category_id) {
					$this->db->query("INSERT INTO actions_to_category_in SET actions_id = '" . (int)$actions_id . "', category_id = '" . (int)$category_id . "'");
				}		
			}
			
			if ($data['keyword']) {
				
				$this->db->query("DELETE FROM `url_alias` WHERE `query` = 'actions_id=" . (int)$actions_id. "'");
				
				foreach ($data['keyword'] as $language_id => $keyword) {
					if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'actions_id=" . (int)$actions_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
				}
			}
			
			$this->cache->delete('actions_content');
		}
		
		public function getActionCategories($actions_id) {
			$actions_category_data = array();
			
			$query = $this->db->query("SELECT * FROM actions_to_category WHERE actions_id = '" . (int)$actions_id . "'");
			
			foreach ($query->rows as $result) {
				$actions_category_data[] = $result['category_id'];
			}
			
			return $actions_category_data;
		}
		
		public function getActionCategoriesIn($actions_id) {
			$actions_category_data = array();
			
			$query = $this->db->query("SELECT * FROM actions_to_category_in WHERE actions_id = '" . (int)$actions_id . "'");
			
			foreach ($query->rows as $result) {
				$actions_category_data[] = $result['category_id'];
			}
			
			return $actions_category_data;
		}
		
		private function updateActionsKeyword($actions_id, $keyword) {
			
		}
		
		private function updateActionsDescription($actions_id, $data) {
			$this->db->query("DELETE FROM `actions_description` WHERE `actions_id` = '" . (int)$actions_id . "'");
			foreach ($data as $language_id => $value) {
				$this->db->query("INSERT INTO `actions_description` SET 
				`actions_id` = '" . (int)$actions_id . "', 
				`language_id` = '" . (int)$language_id . "', 
				`title` = '" . $this->db->escape($value['title']) . "',
				`h1` = '" . $this->db->escape($value['h1']) . "',
				`caption` = '" . $this->db->escape($value['caption']) . "',
				`label` = '" . $this->db->escape($value['label']) . "',
				`label_background` = '" . $this->db->escape($value['label_background']) . "',
				`label_color` = '" . $this->db->escape($value['label_color']) . "',
				`label_text` = '" . $this->db->escape($value['label_text']) . "',
				`meta_keywords` = '" . $this->db->escape($value['meta_keywords']) . "',
				`meta_description` = '" . $this->db->escape($value['meta_description']) . "',
				`anonnce` = '" . $this->db->escape($value['anonnce']) . "',
				`description` = '" . $this->db->escape($value['description']) . "',
				`image_overload` = '" . $this->db->escape($value['image_overload']) . "', 
				`image_to_cat_overload` = '" . $this->db->escape($value['image_to_cat_overload']) . "', 
				`content` = '" . $this->db->escape($value['content']) . "'");
			}
		}
		public function editActions($actions_id, $data) {
			/* Format date & time */
			$data['date_start'] = strtotime( preg_replace('|^([0-9]{2})-([0-9]{2})-([0-9]{4})|', "\\3-\\2-\\1", $data['date_start']) . ':00' );
			$data['date_end'] = strtotime( preg_replace('|^([0-9]{2})-([0-9]{2})-([0-9]{4})|', "\\3-\\2-\\1", $data['date_end']) . ':00' );
			
			if( isset($data['product_related']) && $data['product_related'] != '' ) {
				$product_related = implode(',', $data['product_related']);
				} else {
				$product_related = '';
			}
			
			$this->db->query("UPDATE `actions` SET
			`image` = '" . $this->db->escape($data['image']) . "', 
			`image_to_cat` = '" . $this->db->escape($data['image_to_cat']) . "', 
			`date_start` = '" . (int)$data['date_start'] . "',
			`date_end` = '" . (int)$data['date_end'] . "',
			`status` = '" . (int)$data['status'] . "',
			`fancybox` = '" . (int)$data['fancybox'] . "',
			`manufacturer_id` = '" . (int)$data['manufacturer_id'] . "',
			`only_in_stock` = '" . (int)$data['only_in_stock'] . "',
			`deletenotinstock` = '" . (int)$data['deletenotinstock'] . "',
			`product_related` = '" . $product_related . "',
			`ao_group` = '" . $this->db->escape($data['ao_group']) . "',
			`display_all_active` = '" . (int)$data['display_all_active'] . "'
			WHERE `actions_id` = '" . (int)$actions_id . "'");
			
			$this->updateActionsDescription($actions_id, $data['actions_description']);
			
			$this->db->query("DELETE FROM `actions_to_product` WHERE actions_id = '" . $actions_id . "'");
			foreach ($data['product_related'] as $product_id) {
				$this->db->query("INSERT INTO `actions_to_product` SET `actions_id` = '" . (int)$actions_id . "', `product_id` = '" . (int)$product_id . "'");
			}
			
			$this->db->query("DELETE FROM `actions_to_store` WHERE `actions_id` = '" . (int)$actions_id . "'");
			
			if (isset($data['actions_store'])) {
				foreach ($data['actions_store'] as $store_id) {
					$this->db->query("INSERT INTO `actions_to_store` SET `actions_id` = '" . (int)$actions_id . "', `store_id` = '" . (int)$store_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM `actions_to_layout` WHERE `actions_id` = '" . (int)$actions_id . "'");
			
			if (isset($data['actions_layout'])) {
				foreach ($data['actions_layout'] as $store_id => $layout) {
					if ($layout['layout_id']) {
						$this->db->query("INSERT INTO `actions_to_layout` SET actions_id = '" . (int)$actions_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
			
			$this->db->query("DELETE FROM actions_to_category WHERE actions_id = '" . (int)$actions_id . "'");
			
			if (isset($data['actions_category'])) {
				foreach ($data['actions_category'] as $category_id) {
					$this->db->query("INSERT INTO actions_to_category SET actions_id = '" . (int)$actions_id . "', category_id = '" . (int)$category_id . "'");
				}		
			}
			
			$this->db->query("DELETE FROM actions_to_category_in WHERE actions_id = '" . (int)$actions_id . "'");
			
			if (isset($data['actions_category_in'])) {
				foreach ($data['actions_category_in'] as $category_id) {
					$this->db->query("INSERT INTO actions_to_category_in SET actions_id = '" . (int)$actions_id . "', category_id = '" . (int)$category_id . "'");
				}		
			}
			
			if ($data['keyword']) {
				
				$this->db->query("DELETE FROM `url_alias` WHERE `query` = 'actions_id=" . (int)$actions_id. "'");
				
				foreach ($data['keyword'] as $language_id => $keyword) {
					if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'actions_id=" . (int)$actions_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
				}
			}
			
			$this->cache->delete('actions_content');
		}
		
		public function getKeyWords($actions_id) {
			$keywords = array();
			
			$query = $this->db->query("SELECT * FROM url_alias WHERE query = 'actions_id=" . (int)$actions_id . "'");
			
			foreach ($query->rows as $result) {
				$keywords[$result['language_id']] = $result['keyword'];					
			}
			
			return $keywords;
		}
		
		public function deleteActions($actions_id) {
			$this->db->query("DELETE FROM `actions` WHERE `actions_id` = '" . (int)$actions_id . "'");
			$this->db->query("DELETE FROM `actions_description` WHERE `actions_id` = '" . (int)$actions_id . "'");
			$this->db->query("DELETE FROM `actions_to_store` WHERE `actions_id` = '" . (int)$actions_id . "'");
			$this->db->query("DELETE FROM `url_alias` WHERE `query` = 'actions_id=" . (int)$actions_id . "'");
			
			$this->cache->delete('actions_content');
		}	
		
		public function getActions($actions_id) {
			$query = $this->db->query("SELECT a.actions_id, a.*, ad.* FROM `actions` a LEFT JOIN `actions_description` ad ON (a.actions_id = ad.actions_id) WHERE a.`actions_id` = '" . (int)$actions_id . "'");
			
			return $query->row;
		}
		
		public function getActionsName($actions_id) {
			$query = $this->db->query("SELECT a.actions_id, ad.caption FROM `actions` a LEFT JOIN `actions_description` ad ON (a.actions_id = ad.actions_id) WHERE a.`actions_id` = '" . (int)$actions_id . "' AND ad.language_id = '" . $this->config->get('config_language_id') . "'");
			
			return ($query->num_rows)?$query->row['caption']:false;
		}
		
		public function getAllActions() {
			$query = $this->db->query("SELECT a.actions_id, a.*, ad.* FROM `actions` a LEFT JOIN `actions_description` ad ON (a.actions_id = ad.actions_id) WHERE status = 1 AND ad.language_id = '" . $this->config->get('config_language_id') . "' ORDER BY date_end DESC");
			
			return $query->rows;
		}
		
		public function getActionss($data = array()) {
			
			$sql = "SELECT * FROM `actions` n 
			LEFT JOIN `actions_description` nd ON (n.actions_id = nd.actions_id) 
			WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
			'n.date_start', 
			'nd.caption'
			);		
			
			if (!empty($data['filter_name'])){
				$sql .= " AND LOWER(nd.caption) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";	
			}
			
			if (!empty($data['filter_status'])){
				$sql .= " AND status = 1"	;
			}
			
			if (!empty($data['filter_active'])){
				$sql .= " AND date_end >= UNIX_TIMESTAMP() AND date_start <= UNIX_TIMESTAMP()"	;
			}
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY n.date_start";
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
			
			$query = $this->db->query($sql);
			
			$actions_data = $query->rows;

			return $actions_data;
		}
		
		public function getActionsDescriptions($actions_id) {
			$actions_description_data = array();
			
			$query = $this->db->query("SELECT * FROM `actions_description` WHERE actions_id = '" . (int)$actions_id . "'");
			
			foreach ($query->rows as $result) {
				$actions_description_data[$result['language_id']] = array(
				'title'				=> $result['title'],
				'h1'				=> $result['h1'],
				'caption'			=> $result['caption'],
				'label'				=> $result['label'],
				'label_background'	=> $result['label_background'],
				'label_color'		=> $result['label_color'],
				'label_text'		=> $result['label_text'],
				'meta_keywords'		=> $result['meta_keywords'],
				'meta_description'  => $result['meta_description'],
				'anonnce'      		=> $result['anonnce'],
				'description'		=> $result['description'],
				'image_overload'			=> $result['image_overload'],
				'image_to_cat_overload'		=> $result['image_to_cat_overload'],
				'content'			=> $result['content']
				);
			}
			
			return $actions_description_data;
		}
		
		public function getActionsStores($actions_id) {
			$actions_store_data = array();
			
			$query = $this->db->query("SELECT * FROM `actions_to_store` WHERE actions_id = '" . (int)$actions_id . "'");
			
			foreach ($query->rows as $result) {
				$actions_store_data[] = $result['store_id'];
			}
			
			return $actions_store_data;
		}
		
		public function getTotalActionss() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `actions`");
			return $query->row['total'];
		}	
		
		public function getActionsLayouts($actions_id) {
			$actions_layout_data = array();
			
			$query = $this->db->query("SELECT * FROM actions_to_layout WHERE actions_id = '" . (int)$actions_id . "'");
			
			foreach ($query->rows as $result) {
				$actions_layout_data[$result['store_id']] = $result['layout_id'];
			}
			
			return $actions_layout_data;
		}
		
		public function getTotalActionssByLayoutId($layout_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM actions_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
			
			return $query->row['total'];
		}
		
		// Get Special Products
		
		public function getSpecialProductsByCategoryId($data = array()) {
			
			if(isset($data['category_id']) AND $data['category_id'] > 0) {
				
				$this->load->model('catalog/product');
				
				if(!empty($data['filter_special'])) {
					$query = $this->db->query("SELECT DISTINCT ps.product_id, pd.name FROM product_special ps
					LEFT JOIN product_description pd ON (ps.product_id = pd.product_id) 
					LEFT JOIN product_to_category p2c ON (ps.product_id = p2c.product_id) 
					WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
					AND p2c.category_id = '" . (int)$data['category_id'] . "' 
					ORDER BY priority, price");
					
					return $query->rows;
					
					} else {
					
					$products = $this->model_catalog_product->getProductsByCategoryId($data['category_id']);
					
					return $products;
				}
			}
			return NULL;
			
		}
		
		public function getProducts($data = array()) {
			
			if ($data && count($data) > 0 ) {
				
				foreach ($data as $d){
					if ($d){
						$_data[] = (int)$d;
					}
				}
				
				if (mb_strlen(implode(',', $_data), 'UTF-8') > 1) {					
					
					$sql = "SELECT p.product_id, pd.name
					FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
					WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
					AND p.product_id IN (" . implode(',', $_data) . ")";
					
					$query = $this->db->query($sql);
					
					return $query->rows;
					} else {
					return null;
				}
				} else {
				return null;
			}
		}
		
		public function install() {
			$sql_actions = "CREATE TABLE IF NOT EXISTS `actions` (
			`actions_id` int(11) NOT NULL AUTO_INCREMENT,
			`image` varchar(255) NULL, 
			`image_size` int(1) NOT NULL default '0', 
			`date_start` int(11) NOT NULL DEFAULT '0',
			`date_end` int(11) NOT NULL DEFAULT '0',
			`status` int(1) NOT NULL DEFAULT '0',
			`fancybox` int(1) NOT NULL DEFAULT '0',
			`product_related` text NULL,
			PRIMARY KEY (`actions_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
			
			$sql_actions_description = "CREATE TABLE IF NOT EXISTS `actions_description` (
			`actions_id` int(11) NOT NULL,
			`language_id` int(11) NOT NULL,
			`title` varchar(255) NOT NULL DEFAULT '',
			`meta_keywords` varchar(255) NOT NULL DEFAULT '',
			`meta_description` varchar(255) NOT NULL DEFAULT '',
			`h1` varchar(255) NOT NULL DEFAULT '',
			`caption` varchar(255) NOT NULL DEFAULT '',
			`anonnce` text NOT NULL DEFAULT '',
			`description` text NOT NULL DEFAULT '',
			`content` text NOT NULL DEFAULT '',
			PRIMARY KEY (`actions_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
			
			$sql_actions_to_layout = "CREATE TABLE IF NOT EXISTS `actions_to_layout` (
			`actions_id` int(11) NOT NULL,
			`store_id` int(11) NOT NULL,
			`layout_id` int(11) NOT NULL,
			PRIMARY KEY (`actions_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
			
			$sql_actions_to_store = "CREATE TABLE IF NOT EXISTS `actions_to_store` (
			`actions_id` int(11) NOT NULL,
			`store_id` int(11) NOT NULL,
			PRIMARY KEY (`actions_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
			
			$this->db->query($sql_actions);
			$this->db->query($sql_actions_description);
			$this->db->query($sql_actions_to_layout);
			$this->db->query($sql_actions_to_store);
			$this->db->query("DELETE FROM `url_alias` WHERE `query` LIKE 'actions_id=%';");
			
			$this->load->model('setting/setting');
			$config['actions_setting'] = array(
			'actions_limit' => 5, 
			'image_width' => 120, 
			'image_height' => 120, 
			'image_module_width' => 80, 
			'image_module_height' => 80,
			'module_maxlen' => 400,
			'show_module_image' => 1,
			'show_module_date' => 1,
			'show_image' => 1,
			'show_date' => 1,
			'image_relproduct_height' => 80,
			'image_relproduct_width' => 80,
			'show_actions_date' => 1
			);
			
			$this->model_setting_setting->editSetting('actions_setting', $config);
			return TRUE;
		}
        public function uninstall() {
			
		}
	}
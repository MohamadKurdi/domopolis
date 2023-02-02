<?php
	class ModelReportProduct extends Model {
		private $good_warehouses = array(
				'quantity_stock' => '18',
				'quantity_stockK' => '1',
				'quantity_stockM' => '0',
				);


		public function getProductsWithNoShortNames($data = []){			
			$sql = "SELECT DISTINCT op.product_id, 
				p.asin, 
				p.image, 
				p.status, 
				pd_native_language.name as native_name, 
				pd_amazon_language.name as amazon_name,
				pd_native_language.short_name_d as native_short_name, 
				pd_amazon_language.short_name_d as amazon_short_name
				FROM `order_product` op
				LEFT JOIN product p ON (p.product_id = op.product_id)
				LEFT JOIN `order` o ON (o.order_id = op.order_id) 
				LEFT JOIN product_description pd_native_language ON (pd_native_language.product_id = op.product_id AND pd_native_language.language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_language')]['language_id'] . "')
				LEFT JOIN product_description pd_amazon_language ON (pd_amazon_language.product_id = op.product_id AND pd_amazon_language.language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "')	
				WHERE 
				o.order_status_id > 0
				AND p.status = 1				
				AND (pd_native_language.short_name_d = '' 
						OR pd_amazon_language.short_name_d = '' 
						OR CHAR_LENGTH(pd_native_language.short_name_d) > '". $this->config->get('config_openai_exportnames_length') ."'
						OR CHAR_LENGTH(pd_amazon_language.short_name_d) > '". $this->config->get('config_openai_exportnames_length') ."'
					)
				AND (pd_native_language.name <> '' AND pd_amazon_language.name <> '')";

			$sql .= " ORDER BY op.product_id DESC";

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

		public function getTotalProductsWithNoShortNames($data = []){
			$this->load->model('localisation/language');

			$sql = "SELECT COUNT(DISTINCT op.product_id) as total FROM `order_product` op 
				LEFT JOIN `order` o ON (o.order_id = op.order_id)
				LEFT JOIN product p ON (p.product_id = op.product_id)
				LEFT JOIN product_description pd_native_language ON (pd_native_language.product_id = op.product_id AND pd_native_language.language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_language')]['language_id'] . "')
				LEFT JOIN product_description pd_amazon_language ON (pd_amazon_language.product_id = op.product_id AND pd_amazon_language.language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "')
				WHERE 
				o.order_status_id > 0
				AND p.status = 1				
				AND (pd_native_language.short_name_d = '' 
						OR pd_amazon_language.short_name_d = '' 
						OR CHAR_LENGTH(pd_native_language.short_name_d) > '". $this->config->get('config_openai_exportnames_length') ."'
						OR CHAR_LENGTH(pd_amazon_language.short_name_d) > '". $this->config->get('config_openai_exportnames_length') ."'
					)
				AND (pd_native_language.name <> '' AND pd_amazon_language.name <> '')";

			$sql .= " ORDER BY o.date_added DESC";
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}

		public function deleteASINFromQueue($asin) {			
			if (trim($asin)){
				$this->db->query("DELETE FROM amzn_add_queue WHERE asin = '" . $this->db->escape($asin) . "'");
			}		
		}

		public function insertAsinToQueue($data) {			
			if (trim($data['asin'])){
				$asins = explode(',', $data['asin']);

				if (empty($data['category_id'])){
					$data['category_id'] = 0;
				}

				foreach ($asins as $asin){
					$this->db->query("INSERT IGNORE INTO amzn_add_queue SET asin = '" . $this->db->escape(trim($asin)) . "', category_id = '" . (int)$data['category_id'] . "', user_id = '" . (int)$this->user->getID() . "', date_added = NOW()");
				}
			}		
		}

		public function getProductsInASINQueue($data = []) {
			$sql = "SELECT adq.*, pd.name, p.image, p.status, p.date_added as date_created FROM amzn_add_queue adq LEFT JOIN product p ON (p.product_id = adq.product_id) LEFT JOIN product_description pd ON (adq.product_id = pd.product_id AND language_id = '" . $this->config->get('config_language_id') . "') WHERE 1";
			
			if (isset($data['filter_asin'])){
				$sql .= " AND asin LIKE ('%" . $this->db->escape($data['filter_asin']) . "%')";
			}
			
			if (isset($data['filter_name'])){
				$sql .= " AND LOWER(name) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";
			}

			if (isset($data['filter_problems'])){
				$sql .= " AND (adq.category_id IN (" . (int)$this->config->get('config_rainforest_default_technical_category_id') . ", " . (int)$this->config->get('config_rainforest_default_unknown_category_id') . ")  OR ISNULL(p.date_added) OR (ISNULL(p.status) OR p.status = 0))";
			}
			
			$sql .= " ORDER BY date_added DESC";
			
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

		public function getTotalProductsInASINQueue($data) {
			$sql = "SELECT COUNT(*) AS total FROM amzn_add_queue adq LEFT JOIN product p ON (p.product_id = adq.product_id) LEFT JOIN product_description pd ON (adq.product_id = pd.product_id AND language_id = '" . $this->config->get('config_language_id') . "') WHERE 1 ";
			
			if (isset($data['filter_asin'])){
				$sql .= " AND adq.asin LIKE ('%" . $this->db->escape($data['filter_asin']) . "%')";
			}
			
			if (isset($data['filter_name'])){
				$sql .= " AND LOWER(pd.name) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";
			}

			if (isset($data['filter_problems'])){
				$sql .= " AND (adq.category_id IN (" . (int)$this->config->get('config_rainforest_default_technical_category_id') . ", " . (int)$this->config->get('config_rainforest_default_unknown_category_id') . ")  OR ISNULL(p.date_added) OR (ISNULL(p.status) OR p.status = 0))";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}

		public function deleteExcludedText($text) {			
			if (trim($text)){
				$this->db->query("DELETE FROM excluded_asins WHERE `text` = '" . $this->db->escape($text) . "'");
			}
		}

		public function insertExcludedText($data) {			
			if (trim($data['text'])){
				$this->db->query("INSERT IGNORE INTO excluded_asins SET 
				`text` 				= '" . $this->db->escape(trim($data['text'])) . "', 
				`category_id` 		= '" . (int)$data['category_id'] . "', 
				`times` 			= '0',
				`date_added`		= NOW(),
				`user_id` 			= '" . $this->user->getID() . "'");
			}			
		}

		public function getProductsExcludedTexts($data = []) {
			$sql = "SELECT * FROM excluded_asins WHERE 1 ";
			
			if (isset($data['filter_text'])){
				$sql .= " AND LOWER(text) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_text'])) . "%')";
			}

			if (isset($data['filter_category_id'])){
				$sql .= " AND category_id = '" . (int)$data['filter_category_id'] . "'";
			}
			
			$sql .= " ORDER BY date_added DESC";
			
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

		public function getTotalProductsExcludedTexts($data) {
			$sql = "SELECT COUNT(*) AS total FROM excluded_asins WHERE 1 ";		
			
			if (isset($data['filter_category_id'])){
				$sql .= " AND category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (isset($data['filter_text'])){
				$sql .= " AND LOWER(text) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_text'])) . "%')";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}


		public function deleteDeletedASIN($asin) {			
			if (trim($asin)){
				$this->db->query("DELETE FROM deleted_asins WHERE asin = '" . $this->db->escape($asin) . "'");
			}
		}
		
		public function insertDeletedASIN($data) {			
			if (trim($data['asin'])){
				$this->db->query("INSERT IGNORE INTO deleted_asins SET asin = '" . $this->db->escape($data['asin']) . "', `name` = '" . $this->db->escape($data['name']) . "'");
			}			
		}

		public function getProductsDeletedASIN($data = []) {
			$sql = "SELECT * FROM deleted_asins WHERE 1 ";
			
			if (isset($data['filter_asin'])){
				$sql .= " AND asin LIKE ('%" . $this->db->escape($data['filter_asin']) . "%')";
			}
			
			if (isset($data['filter_name'])){
				$sql .= " AND LOWER(name) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";
			}
			
			$sql .= " ORDER BY date_added DESC";
			
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
		
		public function getTotalProductsDeletedASIN($data) {
			$sql = "SELECT COUNT(*) AS total FROM deleted_asins WHERE 1 ";
			
			if (isset($data['filter_asin'])){
				$sql .= " AND asin LIKE ('%" . $this->db->escape($data['filter_asin']) . "%')";
			}
			
			if (isset($data['filter_name'])){
				$sql .= " AND LOWER(name) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
				
		public function getProductsViewed($data = []) {
			$sql = "SELECT sv.entity_id as product_id, pd.name, p.model, p.ean, p.price, p.actual_cost, p.actual_cost_date, p.image, m.name as manufacturer, SUM(sv.times) as viewed, ";
			
			$sql .= " (SELECT price FROM product_special ps WHERE ps.product_id = sv.entity_id 
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
			AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND (ps.store_id IN (" . (int)$data['filter_store_id'] . ") OR ps.store_id = -1)";
				} else {
				$sql .= " AND ps.store_id = -1";
			}
			
			$sql .=	" ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special, ";
			
			$sql .= "(SELECT SUM(op.quantity) FROM `order_product` op
			LEFT JOIN `order` o ON (o.order_id = op.order_id) 
			WHERE op.product_id = sv.entity_id";
			
			if (!empty($data['filter_date_from'])){
				$sql .= " AND o.date_added >= '" . $this->db->escape($data['filter_date_from']) . "'";
			}
			
			if (!empty($data['filter_date_to'])){
				$sql .= " AND o.date_added <= '" . $this->db->escape($data['filter_date_to']) . "'";
			}
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . (int)$data['filter_store_id'] . ")";
			}
			
			$sql .= ") as cart, ";
			
			$sql .= "(SELECT SUM(op.quantity) FROM `order_product` op
			LEFT JOIN `order` o ON (o.order_id = op.order_id) 
			WHERE op.product_id = sv.entity_id AND o.order_status_id > 0";
			
			if (!empty($data['filter_date_from'])){
				$sql .= " AND o.date_added >= '" . $this->db->escape($data['filter_date_from']) . "'";
			}
			
			if (!empty($data['filter_date_to'])){
				$sql .= " AND o.date_added <= '" . $this->db->escape($data['filter_date_to']) . "'";
			}
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . (int)$data['filter_store_id'] . ")";
			}
			
			$sql .= ") as bought";
			
			
			$sql .= " FROM superstat_viewed sv
			LEFT JOIN product p ON (p.product_id = sv.entity_id) 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
			LEFT JOIN manufacturer m ON (m.manufacturer_id = p.manufacturer_id)";
			
			$sql .= " WHERE (entity_type = 'p'";
			
			if (!empty($data['filter_date_from'])){
				$sql .= " AND sv.date >= '" . $this->db->escape($data['filter_date_from']) . "'";
			}
			
			if (!empty($data['filter_date_to'])){
				$sql .= " AND sv.date <= '" . $this->db->escape($data['filter_date_to']) . "'";
			}
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND sv.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_manufacturer_id'])){
				$sql .= " AND p.manufacturer_id IN (" . $this->db->escape($data['filter_manufacturer_id']) . ")";
			}
			
			if (!empty($data['filter_category_id'])){
				$sql .= " AND sv.entity_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (" . $this->db->escape($data['filter_category_id']) . "))";
			}
			
			$sql .= ") GROUP BY sv.entity_id";
			$sql .= " HAVING SUM(sv.times) > 0";
			
			$sort_data = array(
			'SUM(sv.times)',
			'cart',
			'bought'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY SUM(sv.times)";
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
			
			//	$this->log->debugsql($sql, true);
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}	
		
		public function getProductBoughtByDays($data = []){
			$sql = "SELECT SUM(op.quantity) as cart FROM `order` o
			LEFT JOIN order_product op ON (o.order_id = op.order_id) WHERE 
			o.order_status_id > 0 AND op.product_id > 0 AND
			op.product_id = '" . $this->db->escape($data['product_id']) . "'";
			
			if (!empty($data['date_from'])){
				$sql .= " AND o.date_added >= '" . $this->db->escape($data['date_from']) . "'";
			}
			
			if (!empty($data['date_to'])){
				$sql .= " AND o.date_added >= '" . $this->db->escape($data['date_to']) . "'";
			}
			
			if (!empty($data['store_id'])){
				$sql .= " AND o.store_id IN (" . (int)$data['store_id'] . ")";
			}
			
			if (!empty($data['bought'])){
				$sql .= " AND o.order_status_id > 0";
			}
			
			$sql .= " GROUP BY op.product_id";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return $query->row['cart'];
				} else {
				return 0;
			}			
		}				
		
		public function getProductViewedByDays($data = []){
			$sql = "SELECT SUM(sv.times) as viewed FROM superstat_viewed sv
			WHERE entity_type = 'p'
			AND entity_id = '" . $this->db->escape($data['product_id']) . "'
			AND date >= '" . $this->db->escape($data['date_from']) . "'
			AND date <= '" . $this->db->escape($data['date_to']) . "'
			GROUP BY sv.entity_id";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return $query->row['viewed'];
				} else {
				return false;
			}			
		}
		
		public function getTotalProductsViewed($data = []) {
			$sql = "SELECT COUNT(DISTINCT entity_id) as total FROM superstat_viewed sv
			LEFT JOIN product p ON (p.product_id = sv.entity_id) 						
			";
			
			$sql .= " WHERE entity_type = 'p'";
			
			if (!empty($data['filter_date_from'])){
				$sql .= " AND sv.date >= '" . $this->db->escape($data['filter_date_from']) . "'";
			}
			
			if (!empty($data['filter_date_to'])){
				$sql .= " AND sv.date <= '" . $this->db->escape($data['filter_date_to']) . "'";
			}
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND sv.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_manufacturer_id'])){
				$sql .= " AND p.manufacturer_id IN (" . $this->db->escape($data['filter_manufacturer_id']) . ")";
			}
			
			if (!empty($data['filter_category_id'])){
				$sql .= " AND sv.entity_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (" . $this->db->escape($data['filter_category_id']) . "))";
			}		
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getTotalProductViews() {
			$query = $this->db->query("SELECT SUM(viewed) AS total FROM product");
			
			return $query->row['total'];
		}
		
		public function reset() {
			$this->db->query("UPDATE product SET viewed = '0'");
		}
		
		public function getPurchased($data = []) {
			$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + op.total * op.tax / 100) AS total FROM order_product op LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			if (!empty($data['filter_order_status_id'])) {
				$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$sql .= " GROUP BY op.model ORDER BY total DESC";
			
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
		
		public function getTotalPurchased($data) {
			$sql = "SELECT COUNT(DISTINCT op.model) AS total FROM `order_product` op LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			if (!empty($data['filter_order_status_id'])) {
				$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getBought($data = []) {
			$sql = "SELECT DISTINCT(op.product_id),
			op.model,
			op.name,
			SUM(op.quantity) as total_bought,
			m.name as manufacturer,
			p.price, 
			p.actual_cost, 
			p.actual_cost_date, 
			p.image, 
			p.*, ";
			
			$sql .= " (SELECT price FROM product_special ps WHERE ps.product_id = op.product_id 
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
			AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";						
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND (ps.store_id IN (" . $this->db->escape($data['filter_store_id']) . "))";
				} else {
				$sql .= " AND ps.store_id = -1";
			}
			
			$sql .=	" ORDER BY ps.store_id DESC, ps.priority ASC LIMIT 1) AS special";
			
			$sql .= " FROM order_product op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			$sql .= " LEFT JOIN product p ON op.product_id = p.product_id";			
			$sql .= " LEFT JOIN manufacturer m ON p.manufacturer_id = m.manufacturer_id";
			
			$sql .= " WHERE o.order_status_id > 0 AND op.product_id > 0";					
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_problems'])){
				$data['filter_set'] = 1;
			}
			
			if (!empty($data['filter_set'])){
				if (isset($data['filter_store_id'])){
					$sql .= " AND (SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ") GROUP BY psl.product_id) > 0";
					} else {
					$sql .= " AND (SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id GROUP BY psl.product_id) > 0";
				}
			}
			
			if (!empty($data['filter_not_set'])){
				if (isset($data['filter_store_id'])){
					$sql .= " AND (
					((SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ") GROUP BY psl.product_id) = 0)
					OR ((SELECT SUM(psl.rec_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ") GROUP BY psl.product_id) = 0)
					OR ((SELECT COUNT(*) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ")) = 0)
					)";
					} else {
					$sql .= " AND (
					((SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id GROUP BY psl.product_id) = 0)
					OR ((SELECT SUM(psl.rec_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id GROUP BY psl.product_id) = 0)
					OR ((SELECT COUNT(*) FROM product_stock_limits psl WHERE psl.product_id = op.product_id) = 0)
					)";
				}
			}
			
			if (!empty($data['filter_problems'])){
				$sql .= ' AND (';
				$tsql = [];
				foreach ($this->good_warehouses as $wh => $store_id){
					$tsql []= "((p.".$wh." <=  (SELECT min_stock FROM product_stock_limits psl WHERE min_stock > 0 AND psl.product_id = p.product_id AND store_id = '" . $store_id . "')) OR 
						(p.".$wh." >  (SELECT min_stock + min_stock * 0.3 FROM product_stock_limits psl WHERE min_stock > 0 AND psl.product_id = p.product_id AND store_id = '" . $store_id . "')
						AND p.".$wh." <=  (SELECT rec_stock FROM product_stock_limits psl WHERE rec_stock > 0 AND psl.product_id = p.product_id AND store_id = '" . $store_id . "')
						)		
					)";
				}
				$sql .= implode(' OR ', $tsql);
				$sql .= ')';
			}
			
			if (!empty($data['filter_manufacturer_id'])){
				$sql .= " AND p.manufacturer_id IN (" . $this->db->escape($data['filter_manufacturer_id']) . ")";
			}
			
			
			if (!empty($data['filter_category_id'])){
				$sql .= " AND op.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (" . $this->db->escape($data['filter_category_id']) . "))";
			}		
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$sql .= " GROUP BY op.product_id";
			
			$sql .= " ORDER BY SUM(op.quantity) DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}			
			
			if (!empty($data['filter_debugsql'])) {
				$this->log->debugsql($sql, true);
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;		
		}
		
		public function getTotalBought($data = []) {
			
			$this->db->query("UPDATE order_product op SET date_added_fo = (SELECT date_added FROM `order` o WHERE o.order_id = op.order_id LIMIT 1) WHERE ISNULL(op.date_added_fo)");
			
			$sql = "SELECT COUNT(DISTINCT op.product_id) AS total FROM `order_product` op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			$sql .= " LEFT JOIN product p ON op.product_id = p.product_id";		
			$sql .= " LEFT JOIN manufacturer m ON p.manufacturer_id = m.manufacturer_id";
			
			$sql .= " WHERE o.order_status_id > 0 AND op.product_id > 0";					
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_problems'])){
				$data['filter_set'] = 1;
			}
			
			if (!empty($data['filter_set'])){
				if (isset($data['filter_store_id'])){
					$sql .= " AND (SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ") GROUP BY psl.product_id) > 0";
					} else {
					$sql .= " AND (SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id GROUP BY psl.product_id) > 0";
				}
			}
			
			if (!empty($data['filter_not_set'])){
				if (isset($data['filter_store_id'])){
					$sql .= " AND (
					((SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ") GROUP BY psl.product_id) = 0)
					OR ((SELECT SUM(psl.rec_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ") GROUP BY psl.product_id) = 0)
					OR ((SELECT COUNT(*) FROM product_stock_limits psl WHERE psl.product_id = op.product_id AND store_id IN (" . $this->db->escape($data['filter_store_id']) . ")) = 0)
					)";
					} else {
					$sql .= " AND (
					((SELECT SUM(psl.min_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id GROUP BY psl.product_id) = 0)
					OR ((SELECT SUM(psl.rec_stock) FROM product_stock_limits psl WHERE psl.product_id = op.product_id GROUP BY psl.product_id) = 0)
					OR ((SELECT COUNT(*) FROM product_stock_limits psl WHERE psl.product_id = op.product_id) = 0)
					)";
				}
			}
			
			if (!empty($data['filter_problems'])){
				$sql .= ' AND (';
				$tsql = [];
				foreach ($this->good_warehouses as $wh => $store_id){
					$tsql []= "((p.".$wh." <=  (SELECT min_stock FROM product_stock_limits psl WHERE min_stock > 0 AND psl.product_id = p.product_id AND store_id = '" . $store_id . "')) OR 
						(p.".$wh." >  (SELECT min_stock + min_stock * 0.3 FROM product_stock_limits psl WHERE min_stock > 0 AND psl.product_id = p.product_id AND store_id = '" . $store_id . "')
						AND p.".$wh." <=  (SELECT rec_stock FROM product_stock_limits psl WHERE rec_stock > 0 AND psl.product_id = p.product_id AND store_id = '" . $store_id . "')
						)		
					)";
				}
				$sql .= implode(' OR ', $tsql);
				$sql .= ')';
			}
			
			if (!empty($data['filter_manufacturer_id'])){
				$sql .= " AND p.manufacturer_id IN (" . $this->db->escape($data['filter_manufacturer_id']) . ")";
			}
			
			if (!empty($data['filter_category_id'])){
				$sql .= " AND op.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (" . $this->db->escape($data['filter_category_id']) . "))";
			}		
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}			
			
			$query = $this->db->query($sql);
			
			//	$this->log->debugsql($sql, true);
			
			if ($query->num_rows) {
				return $query->row['total'];
				} else {
				return 0;
			}			
		}
		
		public function getTotalBoughtByProductID($data = []) {
			
			$sql = "SELECT SUM(op.quantity) AS total FROM `order_product` op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			$sql .= " WHERE o.order_status_id > 0 AND op.product_id = '" . $data['product_id'] . "'";					
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$sql .= " GROUP BY op.product_id";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return $query->row['total'];
				} else {
				return 0;
			}			
		}
			
		public function getAverageBoughtByProductID($data = []) {
			
			$sql = "SELECT AVG(total_by_period) as average FROM ( 
			SELECT SUM(op.quantity) AS total_by_period FROM `order_product` op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			$sql .= " WHERE o.order_status_id > 0 AND op.product_id = '" . $data['product_id'] . "'";					
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			if ($data['period'] == 'week') {
				$sql .= " GROUP BY WEEK(op.date_added_fo)";
				} elseif ($data['period'] == 'month'){
				$sql .= " GROUP BY MONTH(op.date_added_fo)";
			} 
			
			$sql .= ') as avg1';
			
			//	$this->log->debugsql($sql, true);
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return (int)$query->row['average'];
				} else {
				return 0;
			}			
		}
				
		public function getAverageInOrderByProductID($data = []) {
			
			$sql = "SELECT AVG(op.quantity) AS average FROM `order_product` op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			$sql .= " WHERE o.order_status_id > 0 AND op.product_id = '" . $data['product_id'] . "'";					
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			
			//	$this->log->debugsql($sql, true);
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return (int)$query->row['average'];
				} else {
				return 0;
			}			
		}
		
		public function getLastDateOrderByProductID($data = []) {
			
			$sql = "SELECT MAX(op.date_added_fo) as maxdate FROM `order_product` op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id)";
			
			$sql .= " WHERE o.order_status_id > 0 AND op.product_id = '" . (int)$data['product_id'] . "'";					
			
			if (isset($data['filter_store_id'])){
				$sql .= " AND o.store_id IN (" . $this->db->escape($data['filter_store_id']) . ")";
			}		
			
			//	$this->log->debugsql($sql, true);
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows) {
				return $query->row['maxdate'];
				} else {
				return 'Никогда';
			}			
		}
				
		public function getProductStockWaits($product_id){
			
			$query = $this->db->query("SELECT * FROM product_stock_waits WHERE product_id = '" . (int)$product_id . "'");
			
			
			if ($query->num_rows) {
				return $query->row;
			} else {
				return false;
			}			
		}
	
	}
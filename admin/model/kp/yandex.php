<?
	class ModelKpYandex extends Model {
		
		
		
		public function getYandexMarketRecommendedPrices($product_id){
			
			
			$query = $this->db->query("SELECT * FROM product_yam_recommended_prices WHERE product_id = '" . $product_id . "' AND store_id = '0' LIMIT 1");
			
			return $query->row;
		}
		
		public function getYandexMarketAdditionalData($product_id){
			
			$query = $this->db->query("SELECT * FROM product_yam_data WHERE product_id = '" . $product_id . "' LIMIT 1");
			
			return $query->row;
		
		}
		
		public function getYandexMarketCategories(){
			
			$query = $this->db->query("SELECT yam_category_id, count(product_id) as total_products, yam_category_name FROM product_yam_data GROUP BY yam_category_id ORDER BY count(product_id) DESC");
			
			return $query->rows;
		
		}
		
		
		public function getProducts($data = array()) {
			$sql = "SELECT p.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN product_yam_data pyd ON (p.product_id = pyd.product_id) LEFT JOIN product_yam_recommended_prices pyrp ON (p.product_id = pyrp.product_id AND store_id = 0)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			if (!empty($data['filter_name'])) {						
				$art = preg_replace("([^0-9])", "", $data['filter_name']);
				
				
				$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
				$sql .= " OR (p.yam_product_id = '" . $this->db->escape($data['filter_name']) . "')";
				$sql .= " OR (p.model LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.model)>1 )";
				$sql .= " OR (p.ean LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.ean)>1 )";
				$sql .= " OR (p.sku LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.sku)>1 )";
				$sql .= " OR (p.asin LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.asin)>1 )";
				
				
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.model)>1 )";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.sku)>1 ))";
				
			}				
			
			if (isset($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (isset($data['filter_yam_category_id'])) {
				$sql .= " AND pyd.yam_category_id = '" . (int)$data['filter_yam_category_id'] . "'";
			}
			
			if (isset($data['filter_quantity_stockM']) && !is_null($data['filter_quantity_stockM'])) {
				$sql .= " AND p.quantity_stockM > 0";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			if (!empty($data['filter_quantity_not_null'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_not_virtual']) && !is_null($data['filter_not_virtual'])) {
				$sql .= " AND p.is_virtual = 0";
			}
			
			if (!empty($data['filter_not_markdown']) && !is_null($data['filter_not_markdown'])) {
				$sql .= " AND p.is_markdown = 0";
			}
			
			if (!empty($data['filter_yam_hidden'])) {
				$sql .= " AND p.yam_hidden = 1";
			}
			
			if (!empty($data['filter_is_illiquid'])) {
				$sql .= " AND p.is_illiquid = 1";
			}
			
			if (!empty($data['filter_buybox_failed'])) {
				$sql .= " AND (pyrp.BUYBOX > 0 AND (pyrp.BUYBOX <> p.yam_price OR (p.yam_special > 0 AND pyrp.BUYBOX <> p.yam_special)))";
			}
			
			if (!empty($data['filter_defaultoffer_failed'])) {
				$sql .= " AND (pyrp.DEFAULT_OFFER > 0 AND (pyrp.DEFAULT_OFFER <> p.yam_price OR (p.yam_special > 0 AND pyrp.DEFAULT_OFFER <> p.yam_special)))";
			}
			
			if (!empty($data['filter_minpricemarket_failed'])) {
				$sql .= " AND (pyrp.MIN_PRICE_MARKET > 0 AND (pyrp.MIN_PRICE_MARKET <> p.yam_price OR (p.yam_special > 0 AND pyrp.MIN_PRICE_MARKET <> p.yam_special)))";
			}
			
			if (!empty($data['filter_notinfeed'])) {
				$sql .= " AND p.yam_in_feed = 0";
			}
			
			if (!empty($data['filter_yam_not_created'])) {
				$sql .= " AND p.yam_not_created = 1";
			}
			
			$sql .= " GROUP BY p.product_id";
			
			$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.quantity_stockM',
			'p.status',
			'p.sort_order',
			'p.buybox'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY pd.name";	
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
		
		public function getTotalProducts($data = array()) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN product_yam_data pyd ON (p.product_id = pyd.product_id) LEFT JOIN product_yam_recommended_prices pyrp ON (p.product_id = pyrp.product_id)";
			
			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (!empty($data['filter_name'])) {						
				$art = preg_replace("([^0-9])", "", $data['filter_name']);
				
				if (!empty($data['filter_name'])) {						
					$art = preg_replace("([^0-9])", "", $data['filter_name']);
					
					
					$sql .= " AND (pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					$sql .= " OR (pd.product_id = '" . (int)($data['filter_name']) . "')";
					$sql .= " OR (p.yam_product_id = '" . $this->db->escape($data['filter_name']) . "')";
					$sql .= " OR (p.model LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.model)>1 )";
					$sql .= " OR (p.ean LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.ean)>1 )";
					$sql .= " OR (p.sku LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.sku)>1 )";
					$sql .= " OR (p.asin LIKE '%" .$this->db->escape($data['filter_name']). "%' AND LENGTH(p.asin)>1 )";
					
					
					$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
					AND LENGTH(p.model)>1 )";
					$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
					AND LENGTH(p.sku)>1 ))";
					
				}
				
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.model)>1 )";
				$sql .= " OR (REPLACE(REPLACE(REPLACE(REPLACE(p.sku,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'
				AND LENGTH(p.sku)>1 ))";
				
			}
			
			if (!empty($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
			}
			
			if (isset($data['filter_quantity_stockM']) && !is_null($data['filter_quantity_stockM'])) {
				$sql .= " AND p.quantity_stockM > 0";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
			
			if (isset($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (isset($data['filter_yam_category_id'])) {
				$sql .= " AND pyd.yam_category_id = '" . (int)$data['filter_yam_category_id'] . "'";
			}
			
			if (!empty($data['filter_quantity_not_null'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_not_virtual']) && !is_null($data['filter_not_virtual'])) {
				$sql .= " AND p.is_virtual = 0";
			}
			
			if (!empty($data['filter_not_markdown']) && !is_null($data['filter_not_markdown'])) {
				$sql .= " AND p.is_markdown = 0";
			}
			
			if (!empty($data['filter_yam_hidden'])) {
				$sql .= " AND p.yam_hidden = 1";
			}
			
			if (!empty($data['filter_is_illiquid'])) {
				$sql .= " AND p.is_illiquid = 1";
			}
			
			if (!empty($data['filter_buybox_failed'])) {
				$sql .= " AND (pyrp.BUYBOX > 0 AND (pyrp.BUYBOX <> p.yam_price OR (p.yam_special > 0 AND pyrp.BUYBOX <> p.yam_special)))";
			}
			
			if (!empty($data['filter_defaultoffer_failed'])) {
				$sql .= " AND (pyrp.DEFAULT_OFFER > 0 AND (pyrp.DEFAULT_OFFER <> p.yam_price OR (p.yam_special > 0 AND pyrp.DEFAULT_OFFER <> p.yam_special)))";
			}
			
			if (!empty($data['filter_minpricemarket_failed'])) {
				$sql .= " AND (pyrp.MIN_PRICE_MARKET > 0 AND (pyrp.MIN_PRICE_MARKET <> p.yam_price OR (p.yam_special > 0 AND pyrp.MIN_PRICE_MARKET <> p.yam_special)))";
			}
			
			if (!empty($data['filter_notinfeed'])) {
				$sql .= " AND p.yam_in_feed = 0";
			}
			
			if (!empty($data['filter_yam_not_created'])) {
				$sql .= " AND p.yam_not_created = 1";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}	
		
	}								
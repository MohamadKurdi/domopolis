<?php
	class ModelSaleCoupon extends Model {
		public function addCoupon($data) {
			$this->db->query("INSERT INTO coupon SET 
				name 			= '" . $this->db->escape($data['name']) . "', 
				code 			= '" . $this->db->escape($data['code']) . "', 
				promo_type 		= '" . $this->db->escape($data['promo_type']) . "', 
				discount 		= '" . (float)$data['discount'] . "', 
				discount_sum 	= '" . $this->db->escape($data['discount_sum']) . "', 
				currency 		= '" . $this->db->escape($data['currency']) . "', 
				min_currency 	= '" . $this->db->escape($data['min_currency']) . "', 
				type 			= '" . $this->db->escape($data['type']) . "', 
				total 			= '" . (float)$data['total'] . "', 
				logged 			= '" . (int)$data['logged'] . "', 
				shipping 		= '" . (int)$data['shipping'] . "', 
				date_start 		= '" . $this->db->escape($data['date_start']) . "', 
				date_end 		= '" . $this->db->escape($data['date_end']) . "', 
				uses_total 		= '" . (int)$data['uses_total'] . "', 
				uses_customer 	= '" . (int)$data['uses_customer'] . "', 
				status 			= '" . (int)$data['status'] . "', 
				show_in_segments 	= '" . (int)$data['show_in_segments'] . "', 
				manager_id 			= '" . (int)$data['manager_id'] . "', 
				birthday 			= '" . (int)$data['birthday'] . "', 
				display_list 		= '" . (int)$data['display_list'] . "', 
				display_in_account 	= '" . (int)$data['display_in_account'] . "', 
				days_from_send 		= '" . (int)$data['days_from_send'] . "', 
				actiontemplate_id 	= '" . (int)$data['actiontemplate_id'] . "', 
				action_id 			= '" . (int)$data['action_id'] . "', 
				only_in_stock 		= '" . (int)$data['only_in_stock'] . "',
				random 				= '" . (int)$data['random'] . "',
				random_string 		= '" . $this->db->escape($data['random_string']) . "',
				date_added 			= NOW()");
			
			$coupon_id = $this->db->getLastId();
			
			if (isset($data['coupon_product'])) {
				foreach ($data['coupon_product'] as $product_id) {
					$this->db->query("INSERT INTO coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($data['coupon_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO coupon_description SET coupon_id = '" . (int)$coupon_id . "', language_id = '" . (int)$language_id . "', full_name = '" . $this->db->escape($value['full_name']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
			}
			
			if (isset($data['coupon_category'])) {
				foreach ($data['coupon_category'] as $category_id) {
					$this->db->query("INSERT INTO coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
				}
			}	
			
			if (isset($data['coupon_collection'])) {
				foreach ($data['coupon_collection'] as $collection_id) {
					$this->db->query("INSERT INTO coupon_collection SET coupon_id = '" . (int)$coupon_id . "', collection_id = '" . (int)$collection_id . "'");
				}
			}	
			
			if (isset($data['coupon_manufacturer'])) {
				foreach ($data['coupon_manufacturer'] as $manufacturer_id) {
					$this->db->query("INSERT INTO coupon_manufacturer SET coupon_id = '" . (int)$coupon_id . "', manufacturer_id = '" . (int)$manufacturer_id . "'");
				}
			}	
		}
		
		public function editCoupon($coupon_id, $data) {
			$this->db->query("UPDATE coupon SET 
				name 			= '" . $this->db->escape($data['name']) . "', 
				code 			= '" . $this->db->escape($data['code']) . "', 
				promo_type 		= '" . $this->db->escape($data['promo_type']) . "', 
				discount 		= '" . (float)$data['discount'] . "', 
				discount_sum 	= '" . $this->db->escape($data['discount_sum']) . "', 
				currency 		= '" . $this->db->escape($data['currency']) . "', 
				min_currency 	= '" . $this->db->escape($data['min_currency']) . "', 
				type 			= '" . $this->db->escape($data['type']) . "', 
				total 			= '" . (float)$data['total'] . "', 
				logged 			= '" . (int)$data['logged'] . "', 
				shipping 		= '" . (int)$data['shipping'] . "', 
				date_start 		= '" . $this->db->escape($data['date_start']) . "', 
				date_end 		= '" . $this->db->escape($data['date_end']) . "', 
				uses_total 		= '" . (int)$data['uses_total'] . "', 
				uses_customer 	= '" . (int)$data['uses_customer'] . "', 
				status 			= '" . (int)$data['status'] . "', 
				birthday 		= '" . (int)$data['birthday'] . "', 
				days_from_send 	= '" . (int)$data['days_from_send'] . "', 
				actiontemplate_id 	= '" . (int)$data['actiontemplate_id'] . "', 
				action_id 			= '" . (int)$data['action_id'] . "', 
				display_list 		= '" . (int)$data['display_list'] . "', 
				display_in_account 	= '" . (int)$data['display_in_account'] . "', 
				manager_id 			= '" . (int)$data['manager_id'] . "', 
				only_in_stock 		= '" . (int)$data['only_in_stock'] . "', 
				show_in_segments 	= '" . (int)$data['show_in_segments'] . "',
				random 				= '" . (int)$data['random'] . "',
				random_string 		= '" . $this->db->escape($data['random_string']) . "'
				WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			$this->db->query("DELETE FROM coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			if (isset($data['coupon_product'])) {
				foreach ($data['coupon_product'] as $product_id) {
					$this->db->query("INSERT INTO coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
				}
			}	
			
			$this->db->query("DELETE FROM coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			if (isset($data['coupon_category'])) {
				foreach ($data['coupon_category'] as $category_id) {
					$this->db->query("INSERT INTO coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
				}
			}					
			
			
			$this->db->query("DELETE FROM coupon_collection WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			if (isset($data['coupon_collection'])) {
				foreach ($data['coupon_collection'] as $collection_id) {
					$this->db->query("INSERT INTO coupon_collection SET coupon_id = '" . (int)$coupon_id . "', collection_id = '" . (int)$collection_id . "'");
				}
			}	
			
			$this->db->query("DELETE FROM coupon_manufacturer WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			if (isset($data['coupon_manufacturer'])) {
				foreach ($data['coupon_manufacturer'] as $manufacturer_id) {
					$this->db->query("INSERT INTO coupon_manufacturer SET coupon_id = '" . (int)$coupon_id . "', manufacturer_id = '" . (int)$manufacturer_id . "'");
				}
			}
			
			$this->db->query("DELETE FROM coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($data['coupon_description'] as $language_id => $value) {
			
				$this->db->query("INSERT INTO coupon_description SET coupon_id = '" . (int)$coupon_id . "', language_id = '" . (int)$language_id . "', full_name = '" . $this->db->escape($value['full_name']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
			}
		}
		
		public function deleteCoupon($coupon_id) {
			$this->db->query("DELETE FROM coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
			$this->db->query("DELETE FROM coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
			$this->db->query("DELETE FROM coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
			$this->db->query("DELETE FROM coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");
			$this->db->query("DELETE FROM coupon_collection WHERE coupon_id = '" . (int)$coupon_id . "'");
			$this->db->query("DELETE FROM coupon_manufacturer WHERE coupon_id = '" . (int)$coupon_id . "'");
		}
		
		public function getCoupon($coupon_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			return $query->row;
		}
		
		public function getCouponByCode($code) {
			$query = $this->db->query("SELECT DISTINCT * FROM coupon WHERE code = '" . $this->db->escape($code) . "'");
			
			return $query->row;
		}
		
		public function getCouponLinkedToAction($action_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM coupon WHERE action_id = '" . (int)$action_id . "'");
			
			return $query->row;
		}

		public function getGoodCouponUsage($code){
			$sql = "SELECT count(ot.order_id) as total FROM `order_total` ot 
				LEFT JOIN `order` o ON (o.order_id = ot.order_id) 
				WHERE code = 'coupon'";

			if ($coupon_random_struct = $this->couponRandom->getCouponRandomStruct($code)){
				$sql .= " AND ( ";
				$sql .= " TRIM(ot.title) LIKE ('%" . $this->db->escape(trim($coupon_random_struct[0])) . "%') ";
				$sql .= " AND TRIM(ot.title) LIKE ('%" . $this->db->escape(trim($coupon_random_struct[1])) . "%') ";
				$sql .= " ) ";
			} else {
				$sql .= " AND TRIM(ot.title) LIKE ('%" . $this->db->escape(trim($code)) . "%') ";
			}

			$sql .= " AND order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";

			$query = $this->db->query($sql);
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getBadCouponUsage($code){
			$sql = "SELECT count(ot.order_id) as total FROM `order_total` ot 
				LEFT JOIN `order` o ON (o.order_id = ot.order_id) 
				WHERE code = 'coupon'";

			if ($coupon_random_struct = $this->couponRandom->getCouponRandomStruct($code)){
				$sql .= " AND ( ";
				$sql .= " TRIM(ot.title) LIKE ('%" . $this->db->escape(trim($coupon_random_struct[0])) . "%') ";
				$sql .= " AND TRIM(ot.title) LIKE ('%" . $this->db->escape(trim($coupon_random_struct[1])) . "%') ";
				$sql .= " ) ";
			} else {
				$sql .= " AND TRIM(ot.title) LIKE ('%" . $this->db->escape(trim($code)) . "%') ";
			}

			$sql .= " AND order_status_id > 0 ";

			$query = $this->db->query($sql);
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getGoodCouponUsageForMonth($code, $month, $year){
			$query = $this->db->query("SELECT count(DISTINCT order_id) as total FROM `order_history` 
			WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
			AND order_id IN (SELECT order_id FROM order_total WHERE TRIM(title) LIKE ('%" . $this->db->escape(trim($code)) . "%'))");
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getGoodCouponOrdersForMonth($code, $month, $year){
			$query = $this->db->query("SELECT DISTINCT order_id, date_added FROM `order_history` 
			WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
			AND order_id IN (SELECT order_id FROM order_total WHERE TRIM(title) LIKE ('%" . $this->db->escape(trim($code)) . "%')) ORDER BY order_id ASC");
			
			if ($query->num_rows){
				return $query->rows;
				} else {
				return false;
			}
		}
		
		public function getBadCouponUsageForMonth($code, $month, $year){
			$query = $this->db->query("SELECT count(DISTINCT order_id) as total FROM `order` 
			WHERE order_status_id > 0
			AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "'
			AND order_id IN (SELECT order_id FROM order_total WHERE title LIKE ('%" . $this->db->escape($code) . "%'))");			
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		
		
		public function getCoupons($data = array()) {
			$sql = "SELECT * FROM coupon WHERE 1 ";
			
			if (!empty($data['filter_show_in_segments'])){
				$sql .= " AND show_in_segments = " . (int)$data['filter_show_in_segments'];
			}
			
			if (!empty($data['filter_status'])){
				$sql .= " AND status = " . (int)$data['filter_status'];
			}
			
			$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status',
			'promo_type'
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
		}
		
		public function getCouponDescriptions($coupon_id) {
			$coupon_description_data = array();
			
			$query = $this->db->query("SELECT * FROM coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($query->rows as $result) {
				$coupon_description_data[$result['language_id']] = array(				
				'full_name'      		=> $result['full_name'],
				'description'      		=> $result['description'],				
				'short_description'     => $result['short_description'],
				);
			}
			
			return $coupon_description_data;
		}
		
		public function getCouponProducts($coupon_id) {
			$coupon_product_data = array();
			
			$query = $this->db->query("SELECT * FROM coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($query->rows as $result) {
				$coupon_product_data[] = $result['product_id'];
			}
			
			return $coupon_product_data;
		}
		
		public function getCouponCategories($coupon_id) {
			$coupon_category_data = array();
			
			$query = $this->db->query("SELECT * FROM coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($query->rows as $result) {
				$coupon_category_data[] = $result['category_id'];
			}
			
			return $coupon_category_data;
		}
		
		public function getCouponCollections($coupon_id) {
			$coupon_collection_data = array();
			
			$query = $this->db->query("SELECT * FROM coupon_collection WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($query->rows as $result) {
				$coupon_collection_data[] = $result['collection_id'];
			}
			
			return $coupon_collection_data;
		}
		
		public function getCouponManufacturers($coupon_id) {
			$coupon_manufacturer_data = array();
			
			$query = $this->db->query("SELECT * FROM coupon_manufacturer WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			foreach ($query->rows as $result) {
				$coupon_manufacturer_data[] = $result['manufacturer_id'];
			}
			
			return $coupon_manufacturer_data;
		}
		
		public function getTotalCoupons() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM coupon");
			
			return $query->row['total'];
		}	
		
		public function getCouponHistories($coupon_id, $start = 0, $limit = 10) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 10;
			}	
			
			$query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM coupon_history ch LEFT JOIN customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getTotalCouponHistories($coupon_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");
			
			return $query->row['total'];
		}			
	}		
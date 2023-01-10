<?php
	class ModelAccountPromocodes extends Model {
		
		public function getActivePromocodes(){
			
			
			$query = $this->db->ncquery("SELECT c.coupon_id, c.*, cd.* 
			FROM coupon c 
			LEFT JOIN coupon_description cd ON (c.coupon_id = cd.coupon_id AND language_id = '" . (int)$this->config->get('config_language_id') . "') 
			WHERE
			(DATE(date_start) <= NOW() OR DATE(date_start) = '0000-00-00')
			AND (DATE(date_end) >= NOW() OR DATE(date_end) = '0000-00-00')
			AND display_in_account = 1
			AND status = 1
			ORDER BY date_start DESC
			");
			
			return $query->rows;
			
		}
		
		public function countTotalPromocodeUsage($coupon_code){
		
			//coupon_history
			$query = $this->db->query("SELECT COUNT(*) as total FROM order_total WHERE title LIKE ('%" . $this->db->escape($coupon_code) . "%') AND order_id IN (SELECT order_id FROM `order` WHERE order_status_id > 0)");
			
			return $query->row['total'];
		}
		
		public function countCustomerPromocodeUsage($coupon_id, $coupon_code){
		
			//coupon_history
			$query1 = $this->db->query("SELECT COUNT(*) as total FROM coupon_history WHERE coupon_id = '" . $coupon_id . "' AND customer_id = '" . $this->customer->getID() . "'");
			
			if ($query1->row['total'] == 0){
				$query2 = $this->db->query("SELECT COUNT(*) as total FROM order_total WHERE title LIKE ('%" . $this->db->escape($coupon_code) . "%') AND order_id IN (SELECT order_id FROM `order` WHERE customer_id = '" . $this->customer->getID() . "')");
				
				return $query2->row['total'];
				
			} else {
				return $query1->row['total'];
			}
			
			return false;
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
		
	}	
<?php
class ModelAccountReview extends Model {
		 
	public function getReviews($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 1;
		}	
		
		$query = $this->db->non_cached_query("SELECT review_id, product_id, author, text, date_added, status, rating FROM `review` WHERE customer_id = '" . (int)$this->customer->getId() . "' ORDER BY review_id DESC LIMIT " . (int)$start . "," . (int)$limit);	
	
		return $query->rows;
	}
	
	public function updateReview($review_id, $data) {
		$this->db->non_cached_query("UPDATE review SET text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$this->config->get('config_myreviews_moder')  . "', date_added = NOW() WHERE review_id = '" . (int)$review_id . "'");
	
		$this->cache->delete('product');
	}
	
	public function getTotalReviews() {
		$query = $this->db->non_cached_query("SELECT COUNT(DISTINCT review_id) AS total FROM review WHERE customer_id = '" . (int)$this->customer->getId() . "' ");
	
		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}	

	
	public function getPurchasedProducts() {
		$orders = array();
		$get_orders = $this->db->non_cached_query("SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND customer_id = '" . (int)$this->customer->getId() . "' ");	
		if($get_orders->num_rows > 0) {
			$orders = $get_orders->rows;
		}
		
		$get_products_reviews = array();
		$get_p_w_r = $query = $this->db->non_cached_query("SELECT product_id FROM `review` WHERE customer_id = '" . (int)$this->customer->getId() . "' ");
		if($get_p_w_r->num_rows > 0) {
			$get_products_reviews = $get_p_w_r->rows;
		}

		$products = array();
		if(isset($orders)){
			foreach($orders as $order_id){
				$query = $this->db->non_cached_query("SELECT product_id FROM order_product WHERE order_id = '" . (int)$order_id['order_id'] . "'");
				$products += $query->rows;
			}		
		}	
		
		$tmp_1 = array();
		foreach($get_products_reviews as $one){
			$tmp_1[] = $one['product_id'];
		}

		$tmp_2 = array();
		foreach($products as $one){
			$tmp_2[] = $one['product_id'];
		}
		
		$result = array();
		$result = array_diff($tmp_2, $tmp_1);
		
		if(isset($result)){
			return $result;		
		} else {
			return $tmp_2;	
		}	
	}	
}
?>
<?php 
	class ModelCatalogGroupPrice extends Model {	
		
		public function updatePrice($product_id, $price) {
			
			if (!$this->config->get('config_group_price_enable')){
				return $price;
			}
			
			$min_price = $this->getMinPrice($product_id);
			
			// currency price
			if ($min_price['type'] == 2) {
				$price = $price + $min_price['price'];
				if ($price < 0 ) {
					$price = 0;
				}      
			}
			// % price
			else {
				$price = $price + $price * $min_price['price'] / 100;
			}
			
			return $price;
		}
		
		private function getMinPrice($product_id) {
			
			$price = array('price' => 0, 'type' => 1);
			
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$categories = array();
			$this->load->model('catalog/product');		
			$categories_arr = $this->model_catalog_product->getCategories($product_id);
			foreach ($categories_arr as $category_arr) {
				$categories[] = $category_arr['category_id'];
			}    
			
			if (count($categories)>0){
				
				// try to get category group price
				$query = "SELECT MIN(price) as price, type FROM customer_group_price 
				WHERE category_id IN (" .  implode(',', $categories) . ")" . " AND customer_group_id = " . (int)$customer_group_id . "";    
				$query = $this->db->query($query);
				
				if (isset($query->row['price'])) {
					$price['price'] = $query->row['price'];    
					$price['type']  = $query->row['type'];
				}
				// try to get base group prise 0 - all categories
				else {
					
					$query = "SELECT MIN(price) as price, type FROM customer_group_price 
					WHERE category_id = 0 " . " AND customer_group_id = " . (int)$customer_group_id . "";    
					$query = $this->db->query($query);
					
					if (isset($query->row['price'])) {
						$price['price'] = $query->row['price'];    
						$price['type']  = $query->row['type'];
					}
					
				}
				} else {
				$query = "SELECT MIN(price) as price, type FROM customer_group_price 
				WHERE category_id = 0 " . " AND customer_group_id = " . (int)$customer_group_id . "";    
				$query = $this->db->query($query);
				
				if (isset($query->row['price'])) {
					$price['price'] = $query->row['price'];    
					$price['type']  = $query->row['type'];
				}
			}
			
			return $price;    
		}
		
	}

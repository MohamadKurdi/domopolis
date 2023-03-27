<?php
class ModelCatalogViewed extends Model {

	public function updateProductViewed($product_id){
		$this->db->non_cached_query("UPDATE product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateCategoryViewed($category_id){
		$this->db->non_cached_query("UPDATE category SET viewed = (viewed + 1) WHERE category_id = '" . (int)$category_id . "'");
	}

	public function addToCustomerViewed($type, $entity_id){
		
		if ($this->customer->isLogged()){
			$customer_id = $this->customer->isLogged();
		} else {
			$customer_id  = 0;
		}
		
		$this->db->query("INSERT INTO `customer_viewed` 
			(customer_id, type, entity_id)  
			VALUES ('" . (int)$customer_id  . "', '" . $this->db->escape($type) . "', '" . (int)$entity_id . "')
			ON DUPLICATE KEY UPDATE times = times + 1");
		
		/*
		$this->db->query("INSERT INTO `superstat_viewed` 
			(entity_type, entity_id, store_id, date, times)  
			VALUES ('" . $this->db->escape($type) . "', '" . (int)$entity_id . "', '" . (int)$this->config->get('config_store_id') . "', DATE(NOW()), 1)
			ON DUPLICATE KEY UPDATE times = times + 1");	
		*/
	}

	public function catchAlsoViewed($product_id){

		if (!$this->config->get('config_product_alsoviewed_enable')){
			if (!empty($this->session->data['alsoViewed'])) {
				unset($this->session->data['alsoViewed']);
			}
			return;
		}

		if (empty($this->session->data['alsoViewed'])) {
			$this->session->data['alsoViewed'] = $product_id;
		} else {
			if (strstr($this->session->data['alsoViewed'], $product_id) == false) {
				$this->session->data['alsoViewed'] .= ',' . $product_id;
			}
		}

		$alsoViewed = explode(',', $this->session->data['alsoViewed']);

		sort($alsoViewed);

		$groupedalsoViewed = [];
		foreach ($alsoViewed as $k => $b) {
			for ($i = 1; $i < count($alsoViewed); $i++) {
				if (!empty($alsoViewed[$k + $i])) {
					$groupedalsoViewed[] = array('low' => $b, 'high' => $alsoViewed[$k + $i]);
				}
			}
		}

		if (empty($this->session->data['alsoViewed'])) {
			$this->session->data['alsoViewed'] = $product_id;
		}

		$alsoViewed = explode(',', $this->session->data['alsoViewed']);

		$groupedalsoViewed = array_slice($groupedalsoViewed, -3);

		foreach ($groupedalsoViewed as $p) {
			if (mt_rand(0, 1) == 1) {
				$this->db->non_cached_query("INSERT INTO `alsoviewed` (low, high, number, date_added) VALUES ('" . (int)$p['low'] . "', '" . (int)$p['high'] . "', '1', NOW()) ON DUPLICATE KEY UPDATE number = number+1");
			}
		}
	}		

	public function getTotalViewed(){
		if(!empty($_COOKIE['viewed_products']))
		{			
			return count(explode(',', $_COOKIE['viewed_products']));					
			
		} else return 0;
	}
	
	public function addToViewed($product_id){		
		$expire = time()+60*60*24*30;
		
		if(!empty($_COOKIE['viewed_products']))
		{
			$viewed_products = explode(',', $_COOKIE['viewed_products']);

			if (count($viewed_products) > 8){
				unset($_COOKIE['viewed_products']);
				setcookie('viewed_products', '', time()-3600,'/');
				setcookie("viewed_products", '', $expire, "/");
			}
			
			if(($exists = array_search($product_id, $viewed_products)) !== false){			
				unset($viewed_products[$exists]);
			}
			
			array_unshift($viewed_products, $product_id);
			if (count($viewed_products) >= 7){
				array_pop($viewed_products);
			}		
			
			$cookie_val = implode(',', $viewed_products);
			setcookie("viewed_products", $cookie_val, $expire, "/");			
		} else {
			setcookie("viewed_products", $product_id, $expire, "/");				
		}
		
	}
	
	public function getListViewed($limit = 24){
		if(!empty($_COOKIE['viewed_products']))
		{			
			$a =  explode(',', $_COOKIE['viewed_products']);	
			return	array_slice($a, 0, $limit);			
			
		} else return false; 
	}
}
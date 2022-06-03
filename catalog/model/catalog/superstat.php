<?

class ModelCatalogSuperStat extends Model {
	
	public function addToSuperStat($type, $entity_id){
		
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

}
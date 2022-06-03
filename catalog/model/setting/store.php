<?php
class ModelSettingStore extends Model {
	public function getStores($data = array()) {		
		$query = $this->db->query("SELECT * FROM store ORDER BY url");		

		$store_data = $query->rows;
		


		return $store_data;
	}	
}
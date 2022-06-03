<?php
class ModelSaleCompetitors extends Model {
	public function getCompetitors() {
		
		
		
	}
	
	
	public function getProducts($data){
		
		$sql = "SELECT product_id, sku, ean, asin FROM product p 
				LEFT JOIN product_description pd ON p.product_id = pd.product_id WHERE 1 ";
		
		if (!empty($data['manufacturer_id'])){
			$sql .= " AND p.manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
		}
		
		$sql .= "LIMIT 0, 50";
		
	}
	
	public function getTotalProducts($data){
		
		$sql = "SELECT COUNT(*) FROM product p 
				LEFT JOIN product_description pd ON p.product_id = pd.product_id WHERE 1 ";
		
		if (!empty($data['filter_manufacturer_id'])){
			$sql .= " AND p.manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
		}
		
	}
	
	
	public function getCompetitorPricesForProducts($data){
		
		$sql = "SELECT * competitor_prices cp			
				LEFT JOIN competitor_urls cu ON (cp.competitor_id = cu.competitor_id AND cp.sku = cu.sku)
				WHERE sku = '" . $this->db->escape($data['sku']) . "' AND cp.competitor_id = '" . (int)$data['competitor_id'] . "'
				";
			
		$sql .= "LIMIT 0, 40";
		
		
		
	}
}
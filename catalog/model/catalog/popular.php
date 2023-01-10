<?php
class ModelCatalogPopular extends Model {
		
	public function getPopularProducts($limit) {
		$this->load->model('catalog/product');
		$product_data = array();
		
		$query = $this->db->query("SELECT p.product_id FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC LIMIT " . (int)$limit);
		
		foreach ($query->rows as $result) { 		
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}
					 	 		
		return $product_data;
	}
}
<?php
class ModelCatalogFAproduct extends Model {

	public function getFAproducts($id)  {
	
		if (!$id){
				return false;
		}
		
		$query = $this->db->query("SELECT `product_id` FROM `faproduct_to_facategory` WHERE `facategory_id` = (SELECT DISTINCT `facategory_id` FROM `facategory_to_faproduct` WHERE 
		`product_id` = ".(int)$id.") AND `product_id` != '". (int)$id."'");
	
		return $query->rows;
	}
}
?>
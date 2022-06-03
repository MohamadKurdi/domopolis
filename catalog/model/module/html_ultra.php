<?php
class ModelModuleHtmlUltra extends Model {	
	public function getHtmlUltra($modul_setting_id) {
		$query = $this->db->query("SELECT * FROM setting  WHERE `key` ='". $this->db->escape($modul_setting_id) ."'");

		return $query->rows;
	}
	public function getProduktCategori($product_id,$categori_id) {
		$query = $this->db->query("SELECT * FROM product_to_category  WHERE `product_id` ='". $this->db->escape($product_id) ."' and `category_id` ='". $this->db->escape($categori_id) ."'");
		if ($query->num_rows){
			return true;
		}else {
			return false;
		}
		
	}
	public function getProduktManufacturer($product_id,$manufacturer_id) {
		$query = $this->db->query("SELECT * FROM product  WHERE `product_id` ='". $this->db->escape($product_id) ."' and `manufacturer_id` ='". $this->db->escape($manufacturer_id) ."'");
		if ($query->num_rows){
			return true;
		}else {
			return false;
		}
		
	}
}
?>
<?php
/* 
	Исходный код оставил в открытом виде, для внесения любым желающим своих правок. 
*/
class ModelModuleHtmlUltra extends Model {	
	//получаем настройки модулей
	public function getModule($group) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");

		if ($query->rows) {
			return $query->rows;
			//return json_decode($query->rows['setting'], true);
		} else {
			return array();	
		}
	}
	
	//Доп функции
	//получаем список магазинов
	public function getStores() {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store  ORDER BY store_id");
			if ($query->rows){
				$store_data = $query->rows;
			}else {
				$query_store = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting  WHERE `store_id` ='0' and `key` ='config_name'");
				$store_data = $query_store->row;
				
			}
			
		return $store_data;
		
	}	

	//Производители
	public function getManufacturer() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer  ORDER BY manufacturer_id");
		$store_data = $query->rows;
		
		return $store_data;
	}	
	//Категории
	public function getCategory() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description  ORDER BY category_id");
		$store_data = $query->rows;
		
		return $store_data;
	} 
	//Группы клиентов 
	public function getGroupCustomer() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description  ORDER BY customer_group_id");
		$store_data = $query->rows;
		
		return $store_data;
	}
	//Группы клиентов 
	public function getCustomer() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer  ORDER BY customer_id");
		$store_data = $query->rows;
		
		return $store_data;
	}
	//Товары
	public function getProduct() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description  ORDER BY product_id");
		$store_data = $query->rows;
		
		return $store_data;
	}
	//Языки
	public function getLanguage() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language  ORDER BY language_id");
		$store_data = $query->rows;
		
		return $store_data;
	}
	//Страницы
	public function getInformation() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description  ORDER BY information_id");  
		$store_data = $query->rows;
		
		return $store_data;
	}
}
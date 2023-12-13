<?

namespace hobotix\Supplier;

class PriceLogic extends SupplierFrameworkClass {


	public function updateProductPriceInDatabase($product_id, $price){
		$field = 'price';
		if ($this->config->get('config_rainforest_delay_price_setting')){
			$field = 'price_delayed';
		}

		$this->db->query("UPDATE product SET 
			" . $field . " 		= '" . (float)$price . "' 
			WHERE product_id 	= '" . (int)$product_id . "' 
			AND is_markdown 	= 0");

		$this->db->query("UPDATE product SET 
			price 				= '" . (float)$price . "' 
			WHERE product_id 	= '" . (int)$product_id . "' 
			AND price = 0
			AND is_markdown 	= 0");
	}

	public function updateProductPriceNationalToStoreInDatabase($product_id, $price, $store_id){
		$field = 'price';
		if ($this->config->get('config_rainforest_delay_price_setting')){
			$field = 'price_delayed';
		}

		$this->db->query("INSERT INTO product_price_national_to_store SET 
			store_id 			= '" . (int)$store_id . "',
			product_id 			= '" . (int)$product_id . "',
			" . $field . " 		= '" . (float)$price . "',
			special 			= '0',
			settled_from_1c 	= '0',
			dot_not_overload_1c = '0'
			ON DUPLICATE KEY UPDATE
			" . $field . " 		= '" . (float)$price . "',
			settled_from_1c 	= '0',
			dot_not_overload_1c = '0'");
	}


	public function setProductIsOnStock($product_id, $product){
		$query = $this->db->query("SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "'");
		
		if ((int)$query->row[$this->config->get('config_warehouse_identifier')] > 0){
			echoLine('[PriceLogic::setProductIsOnStock] Product is in warehouse, setting status config_in_stock_status_id', 's');
			$stock_status_id = (int)$this->config->get('config_in_stock_status_id');						
		} else {
			echoLine('[PriceLogic::setProductIsOnStock] Product is in warehouse, setting status config_stock_status_id', 's');
			$this->db->query("UPDATE product SET quantity = '" . (int)$product['quantity'] . "' WHERE product_id = '" . (int)$product_id . "'");

			$stock_status_id = (int)$this->config->get('config_stock_status_id');

		}

		$sql = "INSERT INTO product_stock_status SET ";
		$sql .= " store_id = '0', ";
		$sql .= " product_id = '" . (int)$product_id . "', ";
		$sql .= " stock_status_id = '" . $stock_status_id . "' ";
		$sql .= " ON DUPLICATE KEY UPDATE stock_status_id = '" . $stock_status_id . "'";
		$this->db->query($sql);				

		if ($this->config->get('config_single_store_enable')){
			$this->db->query("UPDATE product SET stock_status_id = '" . $stock_status_id . "' WHERE product_id = '" . (int)$product_id . "'");
		}
	}


	public function setProductIsNotOnStock($product_id, $product){
		$query = $this->db->query("SELECT `" . $this->config->get('config_warehouse_identifier') . "` FROM product WHERE product_id = '" . (int)$product_id . "'");
		
		if ((int)$query->row[$this->config->get('config_warehouse_identifier')] > 0){
			echoLine('[PriceLogic::setProductIsNotOnStock] Product is in warehouse, setting status config_in_stock_status_id', 's');
			$stock_status_id = (int)$this->config->get('config_in_stock_status_id');
		} else {
			echoLine('[PriceLogic::setProductIsNotOnStock] Product is not in warehouse, setting status config_not_in_stock_status_id', 'e');
			$this->db->query("UPDATE product SET quantity = '0' WHERE product_id = '" . (int)$product_id . "'");

			$stock_status_id = (int)$this->config->get('config_not_in_stock_status_id');
		}

		$sql = "INSERT INTO product_stock_status SET ";
		$sql .= " store_id = '0', ";
		$sql .= " product_id = '" . (int)$product_id . "', ";
		$sql .= " stock_status_id = '" . $stock_status_id . "' ";
		$sql .= " ON DUPLICATE KEY UPDATE stock_status_id = '" . $stock_status_id . "'";
		$this->db->query($sql);

		if ($this->config->get('config_single_store_enable')){
			$this->db->query("UPDATE product SET stock_status_id = '" . $stock_status_id . "' WHERE product_id = '" . (int)$product_id . "'");				
		}
	}
}
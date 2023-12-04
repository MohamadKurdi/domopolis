<?

namespace hobotix\Supplier;

class SupplierProduct extends SupplierClass {
	public function addProduct($data){

	}

	public function addProductToSupplierTable($data, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}
		
		if (empty($data['product_id'])){
			$data['product_id'] = 0;
		}

		$this->db->query("INSERT INTO supplier_products SET
			supplier_id 		= '" . (int)$supplier_id . "',
			supplier_product_id = '" . (int)$data['supplier_product_id'] . "',
			sku 				= '" . $this->db->escape($data['sku']) . "',
			product_id 			= '" . (int)$data['product_id'] . "',
			price 				= '" . (float)$data['price'] . "',
			price_old 			= '" . (float)$data['price_old'] . "',
			stock 				= '" . (int)$data['stock'] . "',
			raw 				= '" . $this->db->escape($data['raw']) . "'
			ON DUPLICATE KEY UPDATE
			sku 				= '" . $this->db->escape($data['sku']) . "',
			price 				= '" . (float)$data['price'] . "',
			price_old 			= '" . (float)$data['price_old'] . "',
			stock 				= '" . (int)$data['stock'] . "',
			raw 				= '" . $this->db->escape($data['raw']) . "'");
	}

	public function editProduct($data){

	}

	public function findProduct($data){

	}
}
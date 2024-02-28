<?

namespace hobotix\Supplier;

class SupplierAttribute extends SupplierFrameworkClass {
	private $attributes 				= [];


	public function setAttributes($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		if (!$this->attributes){
			$query = $this->db->query("SELECT * FROM supplier_attributes WHERE supplier_id = '" . (int)$supplier_id . "'");			

			foreach ($query->rows as $row){
				$this->attributes[$row['supplier_attribute']] = $row['attribute_id'];
			}
		}
	}

	public function unsetAttributes(){
		$this->attributes = [];
	}

	public function getAttributes($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}
		
		$this->setAttributes($supplier_id);

		return $this->attributes;
	}

	public function clearAttributes($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		$this->db->query("DELETE FROM supplier_attributes WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function updateAttributes($attributes, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		foreach ($attributes as $supplier_attribute){
			echoLine('[SupplierAdaptor::updateAttributes] Got attribute ' . $supplier_attribute . ' for supplier ' . $supplier_id, 's');

			$this->db->query("INSERT IGNORE INTO supplier_attributes SET 
				supplier_id 			= '" . (int)$supplier_id . "',
				supplier_attribute 		= '" . $this->db->escape($supplier_attribute) . "'");
		}
	}


	public function getAttributeMatch($supplier_attribute, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		$this->setAttributes($supplier_id);

		if (!empty($this->attributes[$supplier_attribute])){
			return $this->attributes[$supplier_attribute];
		}

		return false;
	}
}
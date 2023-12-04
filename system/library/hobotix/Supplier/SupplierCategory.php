<?

namespace hobotix\Supplier;

class SupplierCategory extends SupplierClass {
	private $categories 		= [];
	private $categories_full 	= [];


	public function setCategories($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		if (!$this->categories){
			$query = $this->db->query("SELECT * FROM supplier_categories WHERE supplier_id = '" . (int)$supplier_id . "'");			

			foreach ($query->rows as $row){
				$this->categories[$row['supplier_category']] 		= $row['category_id'];
				$this->categories_full[$row['supplier_category']]	= $row;
			}
		}
	}

	public function getCategories($supplier_id = null){
		$this->setCategories($supplier_id);

		return $this->categories;
	}

	public function updateCategories($categories, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		foreach ($categories as $category){
			echoLine('[SupplierAdaptor::updateCategories] Got category ' . $category . ' for supplier ' . $supplier_id, 's');

			$this->db->query("INSERT IGNORE INTO supplier_categories SET 
				supplier_id 		= '" . (int)$supplier_id . "',
				supplier_category 	= '" . $this->db->escape($category) . "'");
		}
	}


	public function getCategoryMatch($supplier_category, $supplier_id = null){
		$this->setCategories($supplier_id);

		if (!empty($this->categories[$name])){
			return $this->categories[$name];
		}

		return false;
	}
}
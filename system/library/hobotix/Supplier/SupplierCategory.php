<?

namespace hobotix\Supplier;

class SupplierCategory extends SupplierFrameworkClass {
	private $categories 				= [];
	private $categories_full 			= [];
	private $categories_extended 		= [];
	private $categories_extended_full 	= [];


	public function setCategories($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		if (!$this->categories){
			$query = $this->db->query("SELECT * FROM supplier_categories WHERE supplier_id = '" . (int)$supplier_id . "'");			

			foreach ($query->rows as $row){
				$this->categories[$row['supplier_category']] 				= $row['category_id'];
				$this->categories_full[$row['supplier_category']]			= $row;
				$this->categories_ids[$row['supplier_infeed_id']]			= $row;

				if ($row['supplier_category_full']){
					$this->categories_extended[$row['supplier_category_full']] 	= $row['category_id'];
					$this->categories_extended_full[$row['supplier_category']]	= $row;
				}
			}
		}
	}

	public function getCategories($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}
		
		$this->setCategories($supplier_id);

		return $this->categories;
	}

	public function clearCategories($supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		$this->db->query("DELETE FROM supplier_categories WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function updateCategories($categories, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		foreach ($categories as $category_id => $category){
			echoLine('[SupplierAdaptor::updateCategories] Got category ' . $category['category_name'] . ' for supplier ' . $supplier_id, 's');

			$this->db->query("INSERT IGNORE INTO supplier_categories SET 
				supplier_id 			= '" . (int)$supplier_id . "',
				supplier_infeed_id 		= '" . $this->db->escape($category['category_id']) . "',
				supplier_infeed_parent	= '" . $this->db->escape($category['parent_id']) . "',
				supplier_category 		= '" . $this->db->escape($category['category_name']) . "',
				supplier_category_full	= '" . $this->db->escape($category['category_name_full']) . "'
				ON DUPLICATE KEY UPDATE
				supplier_infeed_id 		= '" . $this->db->escape($category['category_id']) . "',				
				supplier_infeed_parent	= '" . $this->db->escape($category['parent_id']) . "',
				supplier_category 		= '" . $this->db->escape($category['category_name']) . "',
				supplier_category_full	= '" . $this->db->escape($category['category_name_full']) . "'");
		}
	}

	public function getCategoryMatchFull($supplier_category, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}
		
		$this->setCategories($supplier_id);

		if (is_numeric($supplier_category) && !empty($this->categories_ids[$supplier_category])){
			return $this->categories_ids[$supplier_category];
		}

		if (!empty($this->categories_full[$supplier_category])){
			return $this->categories_full[$supplier_category];
		}

		return false;
	}

	public function getCategoryMatch($supplier_category, $supplier_id = null){
		if (!$supplier_id){
			$supplier_id = $this->supplier_id;
		}

		$this->setCategories($supplier_id);

		if (is_numeric($supplier_category) && !empty($this->categories_ids[$supplier_category])){
			return $this->categories_ids[$supplier_category];
		}

		if (!empty($this->categories[$supplier_category])){
			return $this->categories[$supplier_category];
		}

		if (!empty($this->categories_extended[$supplier_category])){
			return $this->categories_extended[$supplier_category];
		}

		return false;
	}
}
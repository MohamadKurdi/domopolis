<?php 
class ModelLocalisationProductGroups extends Model {


	public function addProductGroup($data) {
		$this->db->query("INSERT INTO product_groups SET 
			product_group_name 					= '" . $this->db->escape($data['product_group_name']) . "',
			product_group_feed 					= '" . (int)$data['product_group_feed'] . "',
			product_group_exclude_remarketing  	= '" . (int)$data['product_group_exclude_remarketing'] . "',
			product_group_feed_file 			= '" . $this->db->escape($data['product_group_feed_file']) . "',
			product_group_text_color 			= '" . $this->db->escape($data['product_group_text_color']) . "',
			product_group_bg_color 				= '" . $this->db->escape($data['product_group_bg_color']) . "',
			product_group_fa_icon 				= '" . $this->db->escape($data['product_group_fa_icon']) . "'");

		$product_group_id = $this->db->getLastId();

		return $product_group_id;
	}

	public function editProductGroup($product_group_id, $data) {
		$this->db->query("UPDATE product_groups SET  
			product_group_name 					= '" . $this->db->escape($data['product_group_name']) . "',
			product_group_feed 					= '" . (int)$data['product_group_feed'] . "',
			product_group_exclude_remarketing  	= '" . (int)$data['product_group_exclude_remarketing'] . "',
			product_group_feed_file 			= '" . $this->db->escape($data['product_group_feed_file']) . "',
			product_group_text_color 			= '" . $this->db->escape($data['product_group_text_color']) . "',
			product_group_bg_color 				= '" . $this->db->escape($data['product_group_bg_color']) . "',
			product_group_fa_icon 				= '" . $this->db->escape($data['product_group_fa_icon']) . "'
			WHERE product_group_id 				= '" . (int)$product_group_id . "'");
	}

	public function deleteProductGroup($product_group_id) {
		$this->db->query("DELETE FROM product_groups WHERE product_group_id = '" . (int)$product_group_id . "'");		
	}

	public function getProductGroup($product_group_id) {
		$query = $this->db->query("SELECT * FROM product_groups WHERE product_group_id = '" . (int)$product_group_id . "'");

		return $query->row;
	}

	public function getProductGroupProducts($product_group_id) {
		$query = $this->db->query("SELECT COUNT(product_id) as total FROM product WHERE product_group_id = '" . (int)$product_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM product_groups"); 
		return $query->row['total'];
	}

	public function getProductGroups($data = []) {
		$sql = "SELECT * FROM product_groups ORDER BY product_group_id ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;  
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}


}
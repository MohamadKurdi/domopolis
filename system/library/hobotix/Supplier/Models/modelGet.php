<?php

namespace hobotix\Supplier\Model;

class modelGet extends hoboModel{

	public function getProductFromSupplierMatchTable($data, $field = 'sku'){
		$product_id = false;

		if (!empty($data[$field])){
			$query = $this->db->query("SELECT * FROM supplier_products WHERE `" . $this->db->escape($field) . "` = '" . $this->db->escape($data[$field]) . "'");

			if ($query->num_rows){
				$product_id = $query->row['product_id'];
			}
		}

		if ($product_id){
			$query = $this->db->query("SELECT * FROM product WHERE product_id = '" . (int)$product_id . "'");

			if (!$query->num_rows){
				$product_id = false;
			}
		}

		return $product_id;
	}

	public function getProductBySKU($data){
		if (!empty($data['manufacturer_id'])){
			$query = $this->db->query("SELECT product_id FROM product WHERE sku = '" . $this->db->escape($data['sku']) . "' AND manufacturer_id = '" . (int)$data['manufacturer_id'] . "' LIMIT 1");
		} elseif (!empty($data['vendor'])) {
			$query = $this->db->query("SELECT product_id FROM product WHERE sku = '" . $this->db->escape($data['sku']) . "' AND manufacturer_name = '" . $this->db->escape($data['vendor']) . "' LIMIT 1");
		} else {
			$query = $this->db->query("SELECT product_id FROM product WHERE sku = '" . $this->db->escape($data['sku']) . "' LIMIT 1");
		}
		
		if ($query->num_rows){
			return $query->row['product_id'];
		}

		return false;
	}

	public function getCurrentProductCategory($product_id){
		$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 1 LIMIT 1");

		if ($query->num_rows){
			return $query->row['category_id'];
		} else {
			return $this->config->get('config_rainforest_default_technical_category_id');				
		}
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();
			
		$query = $this->db->query("SELECT * FROM product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'description'      	=> $result['description'],
				'translated'		=> $result['translated']									
			);
		}
			
		return $product_description_data;
	}

	public function getProductPriceStatus($product_id){
		$query = $this->db->query("SELECT price, status FROM product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row;
	}

	public function getProductIdByAsin($asin){
		$query = $this->db->ncquery("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "') LIMIT 1");
			
		if ($query->num_rows){
			return $query->row['product_id'];
		}

		return false;
	}

	public function getProductPriceByAsin($asin){
		$query = $this->db->ncquery("SELECT amazon_best_price FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "') LIMIT 1");
			
		if ($query->num_rows){
			return $query->row['amazon_best_price'];
		}

		return false;
	}		
}
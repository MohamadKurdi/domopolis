<?php

namespace hobotix\Amazon;

class productModelGet extends hoboModel{

	private $asinsArray = [];


	public function getProducts(){

		$result = [];
		$sql = "SELECT 
		p.*, pd.name 
		FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
		WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 
		AND p.added_from_amazon = 1 
		AND p.product_id NOT IN (SELECT product_id FROM product_amzn_data) AND (NOT ISNULL(p.asin) OR p.asin <> '') 
		AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE amazon_can_get_full = 1)) 
		AND asin IN ('B09KCK82NF')";

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'asin' 					=> $row['asin']								
			];
		}

		return $result;

	}


	public function getProductsWithNoImages(){
		$result = [];
		$query = $this->db->ncquery("SELECT product_id, amazon_product_image FROM product WHERE LENGTH(amazon_product_image) > 0 AND LENGTH(image) = 0");

		foreach ($query->rows as $row){
			$result[$row['product_id']] = $row['amazon_product_image'];					
		}

		return $result;
	}


	public function getProductsByAsin($asin){
		$results = [];
		$query = $this->db->ncquery("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "')");
			
			foreach ($query->rows as $row){
				$results[] = $row['product_id'];
			}
			
			return $results;
		}	

		
		public function getCurrentProductCategory($product_id){

			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 1 LIMIT 1");

			if ($query->num_rows){
				return $query->row['category_id'];
			} else {
				return $this->config->get('config_rainforest_default_technical_category_id');				
			}
		}


		public function getIfProductIsFullFilled($product_id){
			$query = $this->db->ncquery("SELECT product_id FROM product_amzn_data WHERE product_id = '" . (int)$product_id . "'");

			return $query->num_rows;
		}


		public function checkIfAsinIsDeleted($asin){

			$query = $this->db->query("SELECT asin FROM deleted_asins WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

				return $query->num_rows;

			}
		}
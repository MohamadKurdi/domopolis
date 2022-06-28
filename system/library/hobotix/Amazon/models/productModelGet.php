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
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))
		ORDER BY RAND() LIMIT " . (int)\hobotix\RainforestAmazon::fullProductParserLimit;

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

	public function getProductsWithNoTranslation(){
		$result = [];
		$query = $this->db->ncquery("SELECT pd.product_id, pd.language_id,
			(SELECT pd2.name FROM product_description pd2 WHERE pd2.product_id = pd.product_id AND language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "') as source_name
		FROM product_description pd WHERE pd.translated = 0 AND pd.language_id <> '" . (int)$this->config->get('config_rainforest_source_language_id') . "'");

		foreach ($query->rows as $row){
			if ($row['source_name']){
				$result[] = [
					'product_id' 			=> $row['product_id'],
					'language_id'			=> $row['language_id'],
					'source_name' 			=> $row['source_name']
				];
			}
		}

		return $result;
	}

	public function getProductFeatureBullets($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->ncquery("SELECT attribute_id FROM product_attribute WHERE product_id = '" . (int)$product_id . "'  AND attribute_id IN (SELECT attribute_id FROM attribute WHERE attribute_group_id = '" . $this->config->get('config_special_attr_id') . "') GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->ncquery("SELECT * FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
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

	public function checkIfProductIsVariant($product_id){
		$query = $this->db->query("SELECT main_variant_id FROM product WHERE product_id = '" . (int)$product_id . "'");

		return (int)$query->row['main_variant_id'];
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
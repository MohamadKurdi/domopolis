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
		AND p.product_id NOT IN (SELECT product_id FROM product_amzn_data) 
		AND (NOT ISNULL(p.asin) OR p.asin <> '') 
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

	public function getProductsFromTechCategory(){
		$result = [];
		$sql = "SELECT 
		p.*, pd.name 
		FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
		WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 
		AND p.added_from_amazon = 1 		
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id = '" . (int)$this->config->get('config_rainforest_default_technical_category_id') . "')
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

	public function getProductsWithFullDataButNotFullfilled(){

		$result = [];
		$sql = "SELECT 
		p.product_id, pd.name, pad.asin, pad.json
		FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN product_amzn_data pad ON (p.product_id = pad.product_id)
		WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 		
		AND p.added_from_amazon = 1 
		AND p.filled_from_amazon = 0
		AND p.product_id IN (SELECT product_id FROM product_amzn_data) 
		AND (NOT ISNULL(p.asin) OR p.asin <> '') 
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))
		ORDER BY RAND() LIMIT " . (int)\hobotix\RainforestAmazon::fullProductParserLimit;

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'asin' 					=> $row['asin'],
				'json'					=> $row['json']							
			];
		}

		return $result;
	}

	public function checkIfProductIsVariant($product_id){
		$query = $this->db->query("SELECT main_variant_id FROM product WHERE product_id = '" . (int)$product_id . "'");

		return (int)$query->row['main_variant_id'];
	}

	public function getIfProductIsFullFilled($product_id){
		return $this->db->ncquery("SELECT product_id FROM product_amzn_data WHERE product_id = '" . (int)$product_id . "'")->num_rows;
	}

	public function getCurrentProductCategory($product_id){

		$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 1 LIMIT 1");

		if ($query->num_rows){
			return $query->row['category_id'];
		} else {
			return $this->config->get('config_rainforest_default_technical_category_id');				
		}
	}

	public function checkIfAsinIsDeleted($asin){
		return $this->db->query("SELECT asin FROM deleted_asins WHERE asin LIKE ('" . $this->db->escape($asin) . "')")->num_rows;
	}

	public function getProductsWithNoImages(){
		$result = [];
		$query = $this->db->ncquery("SELECT product_id, amazon_product_image FROM product WHERE LENGTH(amazon_product_image) > 0 AND LENGTH(image) = 0");

		foreach ($query->rows as $row){
			$result[$row['product_id']] = $row['amazon_product_image'];					
		}

		return $result;
	}

	public function getProductsWithNoFieldTranslation($field){
		$result = [];

		foreach ($this->registry->get('languages') as $language_code => $language) {

			if ($this->config->get('config_rainforest_enable_language_' . $language_code)){

				$query = $this->db->ncquery("SELECT pd.product_id, pd.language_id,
					(SELECT pd2." . $field . " FROM product_description pd2 WHERE pd2.product_id = pd.product_id AND language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "') as source_" . $field . "
						FROM product_description pd WHERE 
						LENGTH(" . $field . ") = 0 
						AND 
						LENGTH((SELECT pd2." . $field . " FROM product_description pd2 WHERE pd2.product_id = pd.product_id AND language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "')) > 0 
						AND
						pd.language_id = '" . (int)$language['language_id'] . "'");

					foreach ($query->rows as $row){
						if ($row['source_' . $field]){
							$result[] = [
								'product_id' 			=> $row['product_id'],
								'language_id'			=> $row['language_id'],
								'source_' . $field 		=> $row['source_' . $field]
							];
						}
					}
				}
			}

		return $result;
	}

	public function getProductsWithNoVideoTitleTranslation(){
		$result = [];

		foreach ($this->registry->get('languages') as $language_code => $language) {		
			if ($this->config->get('config_rainforest_enable_language_' . $language_code)){
				$sql = "SELECT pvd.product_id, pvd.product_video_id, pvd.language_id,
					(SELECT pvd2.title FROM product_video_description pvd2 WHERE pvd2.product_video_id = pvd.product_video_id AND pvd2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "') as source_title
						FROM product_video_description pvd 
						WHERE 
						LENGTH(pvd.title) = 0 AND 
						LENGTH((SELECT pvd2.title FROM product_video_description pvd2 WHERE pvd2.product_video_id = pvd.product_video_id AND pvd2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "')) > 0 
						AND pvd.language_id = '" . (int)$language['language_id'] . "'";
						
					$query = $this->db->ncquery($sql);

					foreach ($query->rows as $row){
						if ($row['source_title']){
							$result[] = [
								'product_video_id' 		=> $row['product_video_id'],
								'product_id'			=> $row['product_id'],
								'language_id'			=> $row['language_id'],								
								'source_title' 			=> $row['source_title']
							];
						}
					}
				}
			}

		return $result;
	}

	public function getProductsWithNoAttributeTranslation(){
		$result = [];

		foreach ($this->registry->get('languages') as $language_code => $language) {		
			if ($this->config->get('config_rainforest_enable_language_' . $language_code)){
				$sql = "SELECT pa.product_id, pa.attribute_id, pa.language_id,
					(SELECT pa2.text FROM product_attribute pa2 WHERE pa2.product_id = pa.product_id AND pa2.attribute_id = pa.attribute_id AND pa2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "') as source_text
						FROM product_attribute pa 
						WHERE 
						LENGTH(pa.text) = 0 AND 
						LENGTH((SELECT pa2.text FROM product_attribute pa2 WHERE pa2.product_id = pa.product_id AND pa2.attribute_id = pa.attribute_id AND pa2.language_id = '" . (int)$this->config->get('config_rainforest_source_language_id') . "')) > 0 
						AND pa.language_id = '" . (int)$language['language_id'] . "'";

					$query = $this->db->ncquery($sql);

					foreach ($query->rows as $row){
						if ($row['source_text']){
							$result[] = [
								'product_id' 			=> $row['product_id'],
								'language_id'			=> $row['language_id'],
								'attribute_id'			=> $row['attribute_id'],
								'source_text' 			=> $row['source_text']
							];
						}
					}
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


	public function getProductsByAsin($asin){
		$results = [];
		$query = $this->db->ncquery("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "')");
			
		foreach ($query->rows as $row){
			$results[] = $row['product_id'];
		}
			
		return $results;
	}	

}
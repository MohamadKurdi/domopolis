<?php

namespace hobotix\Supplier\Model;

class modelEdit extends hoboModel{

	public const descriptionFields = [
		'name',
		'description',
		'variant_name',
		'variant_name_1',
		'variant_name_2',
		'variant_value_1',
		'variant_value_2',
		'color',
		'material'
	];

	public function editProductFields($product_id, $fields){
		$sql = "UPDATE `product` SET ";

		foreach ($fields as $field){

			if ($field['type'] == 'int'){
				$implode[] = " `" . $field['name'] . "` = '" . (int)$field['value'] . "'";
			}

			if ($field['type'] == 'decimal'){
				$implode[] = " `" . $field['name'] . "` = '" . (float)$field['value'] . "'";
			}

			if ($field['type'] == 'varchar'){
				$implode[] = " `" . $field['name'] . "` = '" . $this->db->escape($field['value']) . "'";
			}

			if ($field['type'] == 'date'){
				$implode[] = " `" . $field['name'] . "` = '" . date('Y-m-d', strtotime($field['value'])) . "'";
			}

			if ($field['type'] == 'datetime'){
				$implode[] = " `" . $field['name'] . "` = '" . date('Y-m-d H:i:s', strtotime($field['value'])) . "'";
			}				
		}

		$implode[] = " `date_modified` = NOW() ";


		$sql .= implode(',', $implode);

		$sql .= " WHERE product_id = '" . (int)$product_id . "'";

		$this->db->query($sql);
	}		

	public function disableProduct($product_id){
		$this->db->query("UPDATE product SET `status` = 0 WHERE product_id = '" . (int)$product_id . "'");

		return $this;
	}

	public function enableProduct($product_id){
		$this->db->query("UPDATE product SET `status` = 1 WHERE product_id = '" . (int)$product_id . "'");

		return $this;
	}	

	public function changeProductAttributes($attribute_id_from, $attribute_id_to) {
		$sql = "UPDATE product_attribute SET attribute_id = '" . (int)$attribute_id_to . "' WHERE attribute_id = '" . (int)$attribute_id_from . "'";
		echoLine($sql);

		//$query = $this->db->query($sql);		
	}

	public function getAttributeDescriptions($attribute_id) {
		$query = $this->db->query("SELECT * FROM attribute_description ad WHERE ad.attribute_id = '" . (int)$attribute_id . "'");

		return $query->rows;
	}

	public function deleteAttribute($attribute_id) {
		$this->db->query("DELETE FROM attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM product_feature WHERE feature_id = '" . (int)$attribute_id . "'");
        $this->db->query("DELETE FROM attribute_value_image WHERE attribute_id = '" . (int)$attribute_id . "'");
    }

	public function addProductNames($product_id, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("INSERT INTO product_description SET 
				name 				= '" . $this->db->escape($value['name']) . "',				
				product_id 			= '" . (int)$product_id . "',
				language_id 		= '" . (int)$language_id . "'
				ON DUPLICATE KEY UPDATE
				name 				= '" . $this->db->escape($value['name']) . "'");
		}
	}

	public function updateProductName($product_id, $data){
		$this->db->query("UPDATE product_description SET name = '" . $this->db->escape($data['name']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
	}

	public function updateProductShortName($product_id, $data){
		$this->db->query("UPDATE product_description SET short_name_d = '" . $this->db->escape($data['short_name_d']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
	}

	public function updateProductAttribute($product_id, $data){
		if ($this->checkIfAttributeIsAFeature($data['attribute_id'])){
			$this->db->query("UPDATE product_feature SET `text` = '" . $this->db->escape($data['text']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$data['language_id'] . "' AND feature_id = '" . (int)$data['attribute_id'] . "'");
		} else {
			$this->db->query("UPDATE product_attribute SET `text` = '" . $this->db->escape($data['text']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$data['language_id'] . "' AND attribute_id = '" . (int)$data['attribute_id'] . "'");
		}
	}

	public function editProductImages($product_id, $data){
		$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data as $product_image) {
			if ($product_image['image']){
				$this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
	}

	public function editProductVideos($product_id, $data){
		$this->db->query("DELETE FROM product_video WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_video_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data as $product_video) {
			if ($product_video['video']){
				$this->db->query("INSERT INTO product_video SET product_id = '" . (int)$product_id . "', video = '" . $this->db->escape(html_entity_decode($product_video['video'], ENT_QUOTES, 'UTF-8')) . "', image = '" . $this->db->escape(html_entity_decode($product_video['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_video['sort_order'] . "'");

				$product_video_id = $this->db->getLastId();

				foreach ($product_video['product_video_description'] as $language_id => $value) {
					$this->db->query("INSERT INTO product_video_description SET 
						product_video_id 	= '" . (int)$product_video_id . "',
						language_id 		= '" . (int)$language_id . "',
						product_id 			= '" . (int)$product_id . "',
						title 				= '" . $this->db->escape($value['title']) . "'");
				}
			}
		}			
	}

	public function editProductColor($product_id, $data){
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				color 	= '" . $this->db->escape($value['color']) . "'				
				WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}			
	}

	public function editProductMaterial($product_id, $data){
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				material 	= '" . $this->db->escape($value['material']) . "'				
				WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}			
	}

	public function editProductCategory($product_id, $data){
		$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");

		$i = 1;
		foreach ($data as $category_id) {
			if ($i == 1){
				$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "', main_category = 1");								
			} else {
				$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
			$i++;
		}		
	}


	public function editProductDescriptions($product_id, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				description 		= '" . $this->db->escape($value['description']) . "',				
				translated 			= '" . (int)$value['translated'] . "'
				WHERE product_id 	= '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}

	public function editProductNames($product_id, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				name 				= '" . $this->db->escape($value['name']) . "',				
				translated 			= '" . (int)$value['translated'] . "'
				WHERE product_id 	= '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}

	public function editProductAttributes($product_id, $data){
		$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_feature WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data as $product_attribute) {
			if ($product_attribute['attribute_id']) {
				if ($this->checkIfAttributeIsAFeature($product_attribute['attribute_id'])){
					$this->db->query("DELETE FROM product_feature WHERE product_id = '" . (int)$product_id . "' AND feature_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO product_feature SET product_id = '" . (int)$product_id . "', feature_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				} else {
					$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}			
			}
		}			
	}	

	public function editProductDescriptionField($product_id, $field, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				`" . $field . "` 				= '" . $this->db->escape($value[$field]) . "'								
				WHERE 
				product_id 	= '" . (int)$product_id . "' 
				AND language_id = '" . (int)$language_id . "'");
		}
	}

	public function editProductDescriptionFieldExplicit($product_id, $field, $value){			
			$this->db->query("UPDATE product_description SET 
				`" . $field . "` 				= '" . $this->db->escape($value) . "'								
				WHERE 
				product_id 	= '" . (int)$product_id . "'");		
	}

	public function editProductAttributeText($product_id, $attribute_id, $data){
		if ($this->checkIfAttributeIsAFeature($attribute_id)){
			foreach ($data as $language_id => $value) {						
				$this->db->query("UPDATE product_feature SET 
					`text` 				= '" . $this->db->escape($value['text']) . "'								
					WHERE product_id 	= '" . (int)$product_id . "' AND feature_id = '" . (int)$attribute_id . "' AND language_id = '" . (int)$language_id . "'");			
			}
		} else {
			foreach ($data as $language_id => $value) {						
				$this->db->query("UPDATE product_attribute SET 
					`text` 				= '" . $this->db->escape($value['text']) . "'								
					WHERE product_id 	= '" . (int)$product_id . "' AND attribute_id = '" . (int)$attribute_id . "' AND language_id = '" . (int)$language_id . "'");			
			}
		}
	}

	public function editProductVideoTitle($product_video_id, $data){
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_video_description SET 
				title 				= '" . $this->db->escape($value['title']) . "'								
				WHERE product_video_id 	= '" . (int)$product_video_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}
	
	public function addAttribute($data){
		$this->db->query("INSERT INTO attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "'");

		$attribute_id = $this->db->getLastId();

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

			if ($language_id == $this->config->get('config_rainforest_source_language_id')){
				$name = $value['name'];
			}

		}	
		
		return (int)$attribute_id;
	}

	private function checkIfAttributeIsAFeature($attribute_id){
		if (!$this->config->get('config_use_separate_table_for_features')){
			return false;
		}

		if (!$this->config->get('config_special_attr_id')){
			return false;
		}

		if (!$this->featureAttributes){
			$query = $this->db->query("SELECT DISTINCT attribute_id FROM attribute WHERE attribute_group_id = '" . (int)$this->config->get('config_special_attr_id') . "'");

			foreach ($query->rows as $row){
				$this->featureAttributes[] = $row['attribute_id'];
			}
		}

		if (in_array($attribute_id, $this->featureAttributes)){
			echoLine('[modelEdit::checkIfAttributeIsAFeature] Attribute ' . $attribute_id . ' is a feature!', 'i');
			return true;
		}

		return false;
	}

	public function deleteProduct($product_id, $product){
		$this->db->query("INSERT IGNORE INTO deleted_asins SET asin = '" . $this->db->escape($product['asin']) . "', name = '" . $this->db->escape($product['title']) . "', date_added = NOW(), user_id = '195'");

		foreach ((array)\hobotix\RainforestAmazon::productRelatedTables as $table){
			$sql = "DELETE FROM `" . $table . "` WHERE product_id = '" . (int)$product_id . "'";
			$this->db->query($sql);
		}

		$this->db->query("DELETE FROM product_sponsored WHERE sponsored_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_similar WHERE similar_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");
	}
}
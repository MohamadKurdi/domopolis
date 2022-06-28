<?php

namespace hobotix\Amazon;

class productModelEdit extends hoboModel{


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

	public function updateProductMainVariantId($product_id, $main_variant_id){
		$this->db->query("UPDATE product SET main_variant_id = 	'" . (int)$main_variant_id . "'	WHERE product_id = '" . (int)$product_id . "'");
	}

	public function resetUnexsistentVariants(){
		$this->db->query("UPDATE product SET main_variant_id = 	0 WHERE main_variant_id > 0 AND (NOT ISNULL(main_variant_id)) AND main_variant_id NOT IN (SELECT product_id FROM product)");
		$this->db->query("UPDATE product SET main_variant_id = 	0 WHERE main_variant_id = product_id");
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


	public function addProductNames($product_id, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("INSERT INTO product_description SET 
				name 				= '" . $this->db->escape($value['name']) . "',				
				translated 			= '" . (int)$value['translated'] . "',
				product_id 			= '" . (int)$product_id . "',
				language_id 		= '" . (int)$language_id . "'
				ON DUPLICATE KEY UPDATE
				name 				= '" . $this->db->escape($value['name']) . "',				
				translated 			= '" . (int)$value['translated'] . "'");
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

	public function editProductVariantsDescriptions($product_id, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				variant_name = '" . $this->db->escape($value['variant_name']) . "', 
				variant_name_1  = '" . $this->db->escape($value['variant_name_1']) . "', 
				variant_name_2 = '" . $this->db->escape($value['variant_name_2']) . "', 
				variant_value_1  = '" . $this->db->escape($value['variant_value_1']) . "', 
				variant_value_2 = '" . $this->db->escape($value['variant_value_2']) . "'
				WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}			
	}

	public function editProductAttributes($product_id, $data){
		$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data as $product_attribute) {
			if ($product_attribute['attribute_id']) {
				$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

				foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
					$this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
				}
			}
		}			
	}

	public function editProductRelated($product_id, $data){

		$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");

		foreach ($data as $related_id) {
			$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
			$this->db->query("INSERT IGNORE INTO product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
			$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
		}

	}

	public function editProductSponsored($product_id, $data){

		$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data as $sponsored_id) {			
			$this->db->query("INSERT IGNORE INTO product_sponsored SET product_id = '" . (int)$product_id . "', sponsored_id = '" . (int)$sponsored_id . "'");			
		}

	}

	public function editProductSimilar($product_id, $data){

		$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "'");			

		foreach ($data as $similar_id) {			
			$this->db->query("INSERT IGNORE INTO product_similar SET product_id = '" . (int)$product_id . "', similar_id = '" . (int)$similar_id . "'");
			$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$similar_id . "' AND similar_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_similar SET product_id = '" . (int)$similar_id . "', similar_id = '" . (int)$product_id . "'");
		}
	}












}

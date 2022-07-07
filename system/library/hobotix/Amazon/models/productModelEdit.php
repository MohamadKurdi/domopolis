<?php

namespace hobotix\Amazon;

class productModelEdit extends hoboModel{

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

	public function updateProductMainVariantIdByAsins($main_variant_id, $asins){
		$this->db->query("UPDATE product SET main_variant_id = 	'" . (int)$main_variant_id . "'	WHERE asin IN ('" . implode("','", $asins) . "')");
	}

	public function updateProductMainVariantIdByParentAsin($product_id, $asin){		
		$this->db->query("UPDATE product p SET main_variant_id = (SELECT product_id FROM product p2 WHERE p2.asin = '" . $this->db->escape($asin) . "' LIMIT 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateProductMainVariantIdByParentAsinByAsin($product_asin, $main_variant_asin){		
		$this->db->query("UPDATE product p SET main_variant_id = (SELECT product_id FROM product p2 WHERE p2.asin = '" . $this->db->escape($main_variant_asin) . "' LIMIT 1) WHERE asin LIKE '" . $this->db->escape($product_asin) . "'");
	}

	public function updateProductMainVariantIdByAsin($asin, $main_variant_id){
		$this->db->query("UPDATE product SET main_variant_id = 	'" . (int)$main_variant_id . "'	WHERE  asin LIKE '" . $this->db->escape($asin) . "'");
	}

	public function updateProductMainVariantId($product_id, $main_variant_id){
		$this->db->query("UPDATE product SET main_variant_id = 	'" . (int)$main_variant_id . "'	WHERE product_id = '" . (int)$product_id . "'");
	}

	public function setProductVariants($product){
		if (!empty($product['variants'])){

			//Проверяем, возможно этот товар уже добавлен как вариант, в таком случае мы его пропускаем
			$query = $this->db->ncquery("SELECT main_asin FROM product_variants WHERE variant_asin = '" . $this->db->escape($product['asin']) . "'");
			
			if ($query->num_rows){
				echoLine('[updateProductVariants] ' . $product['asin'] . ' уже вариант у ' . $query->row['main_asin']);
			} else {

			$this->db->query("INSERT IGNORE INTO product_variants SET main_asin = '" . $this->db->escape($product['asin']) . "', variant_asin = '" . $this->db->escape($product['asin']) . "'");
			foreach ($product['variants'] as $variant){										
					$this->db->query("INSERT IGNORE INTO product_variants SET main_asin = '" . $this->db->escape($product['asin']) . "', variant_asin = '" . $this->db->escape($variant['asin']) . "'");
				}
			}
		}
	}

	public function clearAsinVariantsTable(){
		$this->db->query("TRUNCATE product_variants");
	}

	public function clearIdsVariantsTable(){
		$this->db->query("TRUNCATE product_variants_ids");
	}


	public function insertVariantToVariantsTable($product_id, $asins){		
		$this->db->query("INSERT IGNORE INTO product_variants_ids (product_id, variant_id) SELECT '" . (int)$product_id . "', product_id FROM product WHERE asin IN ('" . implode("','", $asins) . "')");
	}

	public function deNormalizeVariantsTable(){
		$this->db->query("UPDATE product p SET main_variant_id = (SELECT product_id FROM product_variants_ids WHERE variant_id = p.product_id)");
	}

	public function resetUnexsistentVariants(){
		$this->db->query("UPDATE product SET main_variant_id = 	0 WHERE main_variant_id > 0 AND main_variant_id NOT IN (SELECT product_id FROM product)");
		$this->db->query("UPDATE product SET main_variant_id = 	0 WHERE asin IN (SELECT main_asin FROM product_variants)");
		$this->db->query("UPDATE product SET main_variant_id = 	0 WHERE main_variant_id = product_id");
	}

	public function cleanFailedTranslations(){
		foreach (self::descriptionFields as $field){
			$this->db->query("UPDATE product_description SET `". $field ."` = '' WHERE `". $field ."` LIKE('%limit on units was exceeded%')");		
		}

		$this->db->query("UPDATE product_attribute SET text = '' WHERE text LIKE('%limit on units was exceeded%')");
		$this->db->query("UPDATE product_video_description SET title = '' WHERE title LIKE('%limit on units was exceeded%')");
	} 

	public function setProductTranslated($product_id, $language_id){			
		$this->db->query("UPDATE product_description SET translated = 1	WHERE product_id 	= '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
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

	public function editProductDescriptionField($product_id, $field, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_description SET 
				`" . $field . "` 				= '" . $this->db->escape($value[$field]) . "'								
				WHERE product_id 	= '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}

	public function editProductAttributeText($product_id, $attribute_id, $data){
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_attribute SET 
				text 				= '" . $this->db->escape($value['text']) . "'								
				WHERE product_id 	= '" . (int)$product_id . "' AND attribute_id = '" . (int)$attribute_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}

	public function editProductVideoTitle($product_video_id, $data){
		foreach ($data as $language_id => $value) {
			$this->db->query("UPDATE product_video_description SET 
				title 				= '" . $this->db->escape($value['title']) . "'								
				WHERE product_video_id 	= '" . (int)$product_video_id . "' AND language_id = '" . (int)$language_id . "'");
		}
	}

	public function addManufacturer($name){		
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($name) . "'");
		$manufacturer_id = $this->db->getLastId();

		$this->db->query("DELETE FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("INSERT INTO manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		foreach ($this->registry->get('languages') as $language_code => $language) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language['language_id'] . "', seo_title = '" . $this->db->escape($name) . "'");
		}

		return (int)$manufacturer_id;
	}

	public function addAttribute($data){
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "'");

		$attribute_id = $this->db->getLastId();

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

			if ($language_id == $this->config->get('config_rainforest_source_language_id')){
				$name = $value['name'];
			}

		}	
		
		return (int)$attribute_id;
	}

	public function deleteProduct($product_id, $product){

		$this->db->query("INSERT IGNORE INTO deleted_asins SET asin = '" . $this->db->escape($product['asin']) . "', name = '" . $this->db->escape($product['title']) . "', date_added = NOW(), user_id = '195'");

		$this->db->query("DELETE FROM product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM ocfilter_option_value_to_product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_sponsored WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_sponsored WHERE sponsored_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_similar WHERE similar_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_amzn_data WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
	}








}

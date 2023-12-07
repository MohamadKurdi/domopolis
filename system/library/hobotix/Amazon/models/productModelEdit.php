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

	private $featureAttributes = [];


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

	/*
		ASIN queue Functions
	*/
	public function deleteASINFromQueue($asin) {			
		if (trim($asin)){
			$this->db->query("DELETE FROM amzn_add_queue WHERE asin = '" . $this->db->escape($asin) . "'");
		}

		return $this;		
	}

	public function setProductIDInQueue($asin, $product_id){
		$this->db->query("UPDATE amzn_add_queue SET product_id = '" . (int)$product_id . "' WHERE asin LIKE '" . $this->db->escape($asin) . "'");

		return $this;
	}

	public function setCategoryIDInQueue($asin, $category_id){
		$this->db->query("UPDATE amzn_add_queue SET category_id = '" . (int)$category_id . "' WHERE asin LIKE '" . $this->db->escape($asin) . "'");

		return $this;
	}

	/*
		Variants queue Functions
	*/
	public function addProductToAmazonVariantsQueue($product_id, $asin){
		if ($product_id && $asin){
			$this->db->query("INSERT IGNORE INTO amzn_add_variants_queue SET product_id = '" . (int)$product_id . "', asin = '" . $this->db->escape($asin) . "', date_added = NOW()");	
		}	

		return $this;
	}

	public function deleteFromVariantsAddQueue($asin){
		$this->db->query("DELETE FROM amzn_add_variants_queue WHERE asin = '" . $this->db->escape($asin) . "'");	
	}

	/*
		ASIN Ignore Functions
	*/
	public function addAsinToIgnored($asin, $name = ''){
		if (!$name){
			$name = 'UNKNOWN_PRODUCT';
		}

		$this->db->query("INSERT IGNORE INTO deleted_asins SET asin = '" . $this->db->escape($asin) . "', name = '" . $this->db->escape($name) . "', date_added = NOW(), user_id = '195'");

		return $this;
	}

	public function removeAsinFromIgnored($asin){
		$this->db->query("DELETE FROM deleted_asins WHERE asin = '" . $this->db->escape($asin) . "'");

		return $this;
	}

	public function disableProduct($product_id){
		$this->db->query("UPDATE product SET `status` = 0 WHERE product_id = '" . (int)$product_id . "'");

		return $this;
	}

	public function enableProduct($product_id){
		$this->db->query("UPDATE product SET `status` = 1 WHERE product_id = '" . (int)$product_id . "'");

		return $this;
	}	

	public function updateProductMainVariantIdByAsins($main_variant_id, $asins){
		$this->db->query("UPDATE product SET main_variant_id = 	'" . (int)$main_variant_id . "'	WHERE asin IN ('" . implode("','", $asins) . "')");
	}

	public function updateProductMainVariantIdByParentAsin($product_id, $asin){		
		$query = $this->db->query("SELECT product_id FROM product p2 WHERE p2.asin = '" . $this->db->escape($asin) . "' LIMIT 1");

		if ($query->num_rows && !empty($query->row['product_id'])){
			$this->db->query("UPDATE product p SET main_variant_id = '" . (int)$query->row['product_id'] . "' WHERE product_id = '" . (int)$product_id . "'");
		}		
	}

	public function updateProductMainVariantIdByParentAsinByAsin($product_asin, $main_variant_asin){	
		$query = $this->db->query("SELECT product_id FROM product p2 WHERE p2.asin = '" . $this->db->escape($main_variant_asin) . "' LIMIT 1");

		if ($query->num_rows && !empty($query->row['product_id'])){
			$this->db->query("UPDATE product p SET main_variant_id = '" . (int)$query->row['product_id'] . "' WHERE asin LIKE '" . $this->db->escape($product_asin) . "'");
		}		
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
				echoLine('[setProductVariants] ' . $product['asin'] . ' is already variant of ' . $query->row['main_asin'], 'i');
			} else {
				$this->db->query("INSERT IGNORE INTO product_variants SET main_asin = '" . $this->db->escape($product['asin']) . "', variant_asin = '" . $this->db->escape($product['asin']) . "'");
				foreach ($product['variants'] as $variant){										
					$this->db->query("INSERT IGNORE INTO product_variants SET main_asin = '" . $this->db->escape($product['asin']) . "', variant_asin = '" . $this->db->escape($variant['asin']) . "'");
				}
			}
		}
	}

	public function clearAsinVariantsTable(){
		$this->db->query("DELETE FROM product_variants WHERE 1");
	}

	public function clearIdsVariantsTable(){
		$this->db->query("DELETE FROM product_variants_ids WHERE 1");
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
		$this->db->query("UPDATE product_feature SET text = '' WHERE text LIKE('%limit on units was exceeded%')");
		$this->db->query("UPDATE product_video_description SET title = '' WHERE title LIKE('%limit on units was exceeded%')");
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

	public function setProductTranslated($product_id, $language_id){			
		$this->db->query("UPDATE product_description SET translated = 1	WHERE product_id 	= '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
	}

	public function setProductReviewsParsed($product_id){			
		$this->db->query("UPDATE product SET reviews_parsed = 1	WHERE product_id 	= '" . (int)$product_id . "'");
	}

	public function addProductNames($product_id, $data){			
		foreach ($data as $language_id => $value) {
			$this->db->query("INSERT INTO product_description SET 
				name 				= '" . $this->db->escape($value['name']) . "',				
				short_name_d		= '" . $this->db->escape($value['short_name_d']) . "',
				translated 			= '" . (int)$value['translated'] . "',
				product_id 			= '" . (int)$product_id . "',
				language_id 		= '" . (int)$language_id . "'
				ON DUPLICATE KEY UPDATE
				name 				= '" . $this->db->escape($value['name']) . "',
				short_name_d		= '" . $this->db->escape($value['short_name_d']) . "',				
				translated 			= '" . (int)$value['translated'] . "'");
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

	public function backupFullDescription($product_id, $data, $language_id){			
		$this->db->query("UPDATE product_description SET 
				description_full 	= '" . $this->db->escape($data['description_full']) . "'				
				WHERE product_id 	= '" . (int)$product_id . "'
				AND language_id 	= '" . (int)$language_id . "'");
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
			echoLine('[productModelEdit::checkIfAttributeIsAFeature] Attribute ' . $attribute_id . ' is a feature!', 'i');
			return true;
		}

		return false;
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

	public function editProductRelated($product_id, $data){

		$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");

		foreach ($data as $related_id) {
			$this->db->query("DELETE FROM product_related WHERE product_id 		= '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
			$this->db->query("INSERT IGNORE INTO product_related SET product_id = '" . (int)$product_id . "', related_id 	= '" . (int)$related_id . "'");
			$this->db->query("DELETE FROM product_related WHERE product_id 		= '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_related SET product_id = '" . (int)$related_id . "', related_id 	= '" . (int)$product_id . "'");
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
			$this->db->query("INSERT IGNORE INTO product_similar SET product_id = '" . (int)$product_id . "', similar_id 	= '" . (int)$similar_id . "'");
			$this->db->query("DELETE FROM product_similar WHERE product_id 		= '" . (int)$similar_id . "' AND similar_id 		= '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_similar SET product_id = '" . (int)$similar_id . "', similar_id 	= '" . (int)$product_id . "'");
		}
	}

	public function editProductSimilarToConsider($product_id, $data){
		$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_similar_to_consider WHERE similar_to_consider_id = '" . (int)$product_id . "'");

		foreach ($data as $similar_to_consider_id) {
			$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$product_id . "' AND similar_to_consider_id 					= '" . (int)$similar_to_consider_id . "'");
			$this->db->query("INSERT IGNORE INTO product_similar_to_consider SET product_id = '" . (int)$product_id . "', similar_to_consider_id 				= '" . (int)$similar_to_consider_id . "'");
			$this->db->query("DELETE FROM product_similar_to_consider WHERE product_id = '" . (int)$similar_to_consider_id . "' AND similar_to_consider_id 		= '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_similar_to_consider SET product_id = '" . (int)$similar_to_consider_id . "', similar_to_consider_id 	= '" . (int)$product_id . "'");
		}
	}

	public function editProductViewToPurchase($product_id, $data){
		$this->db->query("DELETE FROM product_view_to_purchase WHERE product_id = '" . (int)$product_id . "'");		

		foreach ($data as $view_to_purchase_id) {
			$this->db->query("DELETE FROM product_view_to_purchase WHERE product_id 		= '" . (int)$product_id . "' AND view_to_purchase_id 	= '" . (int)$view_to_purchase_id . "'");
			$this->db->query("INSERT IGNORE INTO product_view_to_purchase SET product_id 	= '" . (int)$product_id . "', view_to_purchase_id 		= '" . (int)$view_to_purchase_id . "'");			
		}
	}

	public function editProductAlsoViewed($product_id, $data){
		$this->db->query("DELETE FROM product_also_viewed WHERE product_id 		= '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_also_viewed WHERE also_viewed_id 	= '" . (int)$product_id . "'");
		
		foreach ($data as $also_viewed_id) {
			$this->db->query("DELETE FROM product_also_viewed WHERE product_id 		= '" . (int)$product_id . "' AND also_viewed_id 	= '" . (int)$also_viewed_id . "'");
			$this->db->query("INSERT IGNORE INTO product_also_viewed SET product_id = '" . (int)$product_id . "', also_viewed_id 		= '" . (int)$also_viewed_id . "'");
			$this->db->query("DELETE FROM product_also_viewed WHERE product_id 		= '" . (int)$also_viewed_id . "' AND also_viewed_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_also_viewed SET product_id = '" . (int)$also_viewed_id . "', also_viewed_id 	= '" . (int)$product_id . "'");
		}		
	}

	public function editProductAlsoBought($product_id, $data){
		$this->db->query("DELETE FROM product_also_bought WHERE product_id 		= '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_also_bought WHERE also_bought_id 	= '" . (int)$product_id . "'");

		foreach ($data as $also_bought_id) {
			$this->db->query("DELETE FROM product_also_bought WHERE product_id 		= '" . (int)$product_id . "' AND also_bought_id 	= '" . (int)$also_bought_id . "'");
			$this->db->query("INSERT IGNORE INTO product_also_bought SET product_id = '" . (int)$product_id . "', also_bought_id 		= '" . (int)$also_bought_id . "'");
			$this->db->query("DELETE FROM product_also_bought WHERE product_id 		= '" . (int)$also_bought_id . "' AND also_bought_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT IGNORE INTO product_also_bought SET product_id = '" . (int)$also_bought_id . "', also_bought_id 	= '" . (int)$product_id . "'");
		}
	}

	public function editProductShopByLook($product_id, $data){
		$this->db->query("DELETE FROM product_shop_by_look WHERE product_id 		= '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_shop_by_look WHERE shop_by_look_id 	= '" . (int)$product_id . "'");

		foreach ($data as $shop_by_look_id) {
			$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$product_id . "' AND shop_by_look_id 		= '" . (int)$shop_by_look_id . "'");
			$this->db->query("INSERT INTO product_shop_by_look SET product_id 	= '" . (int)$product_id . "', shop_by_look_id 			= '" . (int)$shop_by_look_id . "'");
			$this->db->query("DELETE FROM product_shop_by_look WHERE product_id = '" . (int)$shop_by_look_id . "' AND shop_by_look_id 	= '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_shop_by_look SET product_id 	= '" . (int)$shop_by_look_id . "', shop_by_look_id 		= '" . (int)$product_id . "'");
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

	public function addManufacturer($name){		
		$this->db->query("INSERT INTO manufacturer SET name = '" . $this->db->escape($name) . "'");
		$manufacturer_id = $this->db->getLastId();

		$this->db->query("DELETE FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("INSERT INTO manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");

		$this->db->query("DELETE FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		foreach ($this->registry->get('languages') as $language_code => $language) {
			$this->db->query("INSERT INTO manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language['language_id'] . "', seo_title = '" . $this->db->escape($name) . "'");
		}

		return (int)$manufacturer_id;
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

	public function deleteProductSimple($product_id){
		echoLine('[productModelEdit::deleteProductSimple] Deleting product: ' . $product_id, 'e');

		foreach ((array)\hobotix\RainforestAmazon::productRelatedTables as $table){
			$sql = "DELETE FROM `" . $table . "` WHERE product_id = '" . (int)$product_id . "'";
			$this->db->query($sql);
		}

		$this->db->query("DELETE FROM product_sponsored WHERE sponsored_id 	= '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_similar WHERE similar_id 		= '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_related WHERE related_id 		= '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product WHERE product_id 				= '" . (int)$product_id . "'");

		return $this;
	}

	public function addReview($data){
		$this->db->query("INSERT INTO review SET 
				addimage 	= '', 
				answer 		= '', 
				good 		= '', 
				bads 		= '', 
				html_status = '0', 
				purchased 	= '1', 
				author 		= '" . $this->db->escape($data['author']) . "', 
				product_id 	= '" . (int)$data['product_id'] . "', 
				text 		= '" . $this->db->escape(strip_tags($data['text'])) . "', 
				rating 		= '" . (int)$data['rating'] . "', 
				status 		= '1', 
				date_added 	= '" . $this->db->escape($data['date_added']) . "'");
			
			$review_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM review_description WHERE review_id = '" . (int)$review_id . "'");
			foreach ($data['review_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO review_description SET 
				review_id 	= '" . (int)$review_id . "', 
				language_id = '" . (int)$language_id . "',
				answer 		= '',
				good 		= '',
				bads 		= '',
				text 		= '" . $this->db->escape(strip_tags($value['text'])) . "'");
			}
	}

	public function addCategoryToTechSimple($data){
		$this->db->query("INSERT INTO category SET 
			parent_id 				= '" . (int)$this->config->get('config_rainforest_default_technical_category_id') . "', 
			virtual_parent_id 		= '0', 
			`top` 					= '0', 
			`column` 				= '0', 
			sort_order 				= '-200', 
			status 					= '0', 
			tnved 					= '', 
			menu_icon 				= '', 
			overprice 				= '', 
			google_category_id 		= '0', 
			separate_feeds 			= '0', 
			no_general_feed 		= '0', 
			deletenotinstock 		= '0', 
			intersections 				= '0', 
			exclude_from_intersections 	= '0', 
			default_weight 			= '0', 
			default_weight_class_id = '0', 
			default_length 			= '0', 
			default_width 			= '0', 
			default_height 			= '0', 
			default_length_class_id = '0', 
			priceva_enable 			= '0', 
			submenu_in_children 	= '0', 
			amazon_sync_enable 		= '0', 
			amazon_last_sync 		= '', 
			amazon_synced 			= '0', 
			amazon_category_id 		= '', 
			amazon_category_name 	= '', 
			amazon_parent_category_id 		= '', 
			amazon_parent_category_name 	= '',
			amazon_final_category 			= '',
			amazon_can_get_full 			= '',
			yandex_category_name 	= '',
			amazon_overprice_rules 	= '', 
			date_modified 			= NOW(), 
			date_added 				= NOW()");
		
		$category_id = $this->db->getLastId();

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO category_description SET 
				category_id 	= '" . (int)$category_id . "', 
				language_id 	= '" . (int)$language_id . "', 
				name 			= '" . $this->db->escape($value['name']) . "', 
				tagline 		= '', 
				alternate_name 	= '', 
				menu_name 		= '', 
				all_prefix 		= '', 
				meta_keyword 	= '', 
				seo_title 		= '', 
				seo_h1 			= '', 
				meta_description 	= '', 
				description 		= '', 
				google_tree 		= ''");
		}

		$level = 0;		
		$query = $this->db->query("SELECT * FROM `category_path` WHERE category_id = '" . (int)$this->config->get('config_rainforest_default_technical_category_id') . "' ORDER BY `level` ASC");
		
		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
			
			$level++;
		}
		
		$this->db->query("INSERT INTO `category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		return $category_id;
	}
}
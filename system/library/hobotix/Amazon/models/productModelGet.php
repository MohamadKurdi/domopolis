<?php

namespace hobotix\Amazon;

class productModelGet extends hoboModel{

	private $asinsArray = [];
	//private $testAsin = 'B07MJG8JPY';
	private $testAsin = false;

	public function getExcludedTexts($category_id){
		$excluded_texts = [];
		$query = $this->db->ncquery("SELECT `text`, `category_id` FROM excluded_asins WHERE category_id = '" . (int)$category_id . "'");

		if ($query->num_rows){			
			foreach($query->rows as $row){				
				$excluded_texts[] = $row['text'];
			}
		}
	
		return $excluded_texts;
	}

	public function updateExludedTextUsage($text, $category_id = 0){
		$this->db->ncquery("UPDATE excluded_asins SET times = times+1 WHERE `text` = '" . $this->db->escape($text) . "' AND category_id = '" . (int)$category_id . "'");
	}

	public function checkIfManufacturerIsExcluded($name, $category_id = 0){
		$query = $this->db->ncquery("SELECT `text`, `category_id` FROM excluded_asins WHERE LOWER(text) = '" . $this->db->escape(mb_strtolower($name)) . "' AND category_id = '" . (int)$category_id . "'");	

		if ($query->num_rows){
			echoLine('[checkIfManufacturerIsExcluded] FOUND exclusion: ' . $name . ' in list!', 'w');
			$this->updateExludedTextUsage($name, 0);

			return true;
		}

		return false;
	}

	public function checkIfNameIsExcluded($name, $category_id = 0){
		$excluded_texts = $this->getExcludedTexts(0);

		if (!empty($excluded_texts)){
			foreach ($excluded_texts as $excluded_text){
				if (strpos($name, ' ' . $excluded_text . ' ') !== false){
					echoLine('[checkIfNameIsExcluded] FOUND exclusion: ' . $excluded_text . ' in name ' . $name, 'w');
					$this->updateExludedTextUsage($excluded_text, 0);
					return true;
				}
			}
		}

		if ($category_id){
			$excluded_texts = $this->getExcludedTexts($category_id);

			if (!empty($excluded_texts)){
				foreach ($excluded_texts as $excluded_text){
					if (strpos($name, ' ' . $excluded_text . ' ') !== false){
						echoLine('[checkIfNameIsExcluded] FOUND exclusion: ' . $excluded_text . ' in name ' . $name, 'w');
						$this->updateExludedTextUsage($excluded_text, $category_id);
						return true;
					}
				}
			}
		}

		return false;
	}

	public function checkIfAsinIsDeleted($asin){
		$query = $this->db->ncquery("SELECT da.asin, da.date_added, da.name, u.firstname, u.lastname FROM deleted_asins da LEFT JOIN user u ON (da.user_id = u.user_id) WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

		if ($query->num_rows){
			echoLine('[checkIfAsinIsDeleted] '  . $asin . ': ' . $query->row['name'], 'w');
			echoLine('[checkIfAsinIsDeleted] ' . $asin . ' deleted ' . $query->row['date_added'] . ', ' . $query->row['firstname'] . ' ' . $query->row['lastname'], 'w');
			return true;
		} else {
			return false;
		}
	}

	public function getIfAsinIsInQueue($asin){
		$sql = "SELECT asin FROM amzn_add_queue WHERE asin = '" . $this->db->escape($asin) . "'";

		$query = $this->db->ncquery($sql);

		if ($query->num_rows){
			return $query->rows;
		}

		return false;
	}

	public function getAsinAddQueue(){
		// return [[
		// 	'asin' 			=> 'B08M8P8RNQ',
		// 	'brand_logic' 	=> 1,
		// 	'category_id' 	=> 0
		// ]];

		$result = [];

		$sql = "SELECT DISTINCT asin, category_id, brand_logic FROM amzn_add_queue WHERE product_id = '0' ORDER BY date_added ASC LIMIT " . (int)\hobotix\RainforestAmazon::productRequestLimits;

		$query = $this->db->ncquery($sql);

		if ($query->num_rows){
			return $query->rows;
		}

		return false;
	}

	public function getVariantsAddQueue(){
		$result = [];

		$sql = "SELECT advq.* FROM amzn_add_variants_queue advq ORDER BY advq.date_added ASC LIMIT " . (int)\hobotix\RainforestAmazon::variantQueueLimit;

		$query = $this->db->ncquery($sql);

		if ($query->num_rows){
			foreach ($query->rows as $row){
				$data_query = $this->db->ncquery("SELECT pad.json, pad.file FROM product_amzn_data pad WHERE asin = '" . $row['asin'] . "' LIMIT 1");

				if ($data_query->num_rows){
					$row['file'] = $data_query->row['file'];
					$row['json'] = $data_query->row['json'];
				} else {
					$row['file'] = null;
					$row['json'] = null;
				}

				if ($this->config->get('config_enable_amazon_asin_file_cache')){
					if (!empty($row['file']) && file_exists(DIR_CACHE . $row['file'])){
						$row['json'] = file_get_contents(DIR_CACHE . $row['file']);
					}
				}

				$result[]= [
					'product_id' 			=> $row['product_id'],
					'asin' 					=> $row['asin'],
					'json' 					=> $row['json'] 	
				];
			}

			return $result;
		}

		return false;
	}

	public function getProducts(){
		$result = [];

		if ($this->testAsin){
			$this->db->query("DELETE FROM product_amzn_data WHERE asin LIKE '" . $this->db->escape($this->testAsin) . "'");
			$this->db->query("UPDATE product SET filled_from_amazon = 0, fill_from_amazon = 1 WHERE asin LIKE '" . $this->db->escape($this->testAsin) . "'");
		}

		$sql = "SELECT 
		p.*, pd.name 
		FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
		WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 
		AND p.added_from_amazon = 1 
		AND p.product_id NOT IN (SELECT product_id FROM product_amzn_data)
		AND p.fill_from_amazon = 1
		AND p.filled_from_amazon = 0
		AND (NOT ISNULL(p.asin) OR p.asin <> '')
		AND p.asin <> 'INVALID'
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))";

		if ($this->testAsin){
			$sql .= " AND p.asin = '" . $this->testAsin . "'";
		}

		$sql .= " ORDER BY p.date_added DESC LIMIT " . (int)\hobotix\RainforestAmazon::fullProductParserLimit;
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
		AND p.asin <> 'INVALID'	
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id = '" . (int)$this->config->get('config_rainforest_default_technical_category_id') . "')
		ORDER BY p.date_added DESC LIMIT " . (int)\hobotix\RainforestAmazon::fullProductParserLimit;

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
		p.product_id, pd.name, pad.asin, pad.json, pad.file
		FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN product_amzn_data pad ON (p.product_id = pad.product_id)
		WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 		
		AND p.added_from_amazon = 1
		AND p.fill_from_amazon = 1	
		AND p.filled_from_amazon = 0
		AND p.product_id IN (SELECT product_id FROM product_amzn_data) 
		AND (NOT ISNULL(p.asin) OR p.asin <> '')
		AND p.asin <> 'INVALID' 
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))
		ORDER BY p.date_added DESC LIMIT " . (int)\hobotix\RainforestAmazon::fullProductParserLimit;

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){

			if ($this->config->get('config_enable_amazon_asin_file_cache')){
				if (!empty($row['file']) && file_exists(DIR_CACHE . $row['file'])){
					$row['json'] = file_get_contents(DIR_CACHE . $row['file']);
				}
			}

			if ($row['json']){
				$result[] = [
					'product_id' 			=> $row['product_id'],
					'asin' 					=> $row['asin'],
					'json'					=> $row['json']							
				];
			}
		}

		return $result;
	}

	public function getTotalProductsWithFullData($filter_data = []){
		$sql = "SELECT COUNT(pad.product_id) as total FROM product_amzn_data pad LEFT JOIN product p ON (p.product_id = pad.product_id) WHERE pad.product_id IN (SELECT product_id FROM product) ";

		$sql .= " AND p.asin <> 'INVALID' "; 
		//$sql .= " AND pad.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))";

		if (!empty($filter_data)){
			foreach ($filter_data as $filter => $value){
				$sql .= " AND p.`" . $this->db->escape($filter) . "` = '" . $this->db->escape($value) . "' ";
			}
		}

		return $this->db->ncquery($sql)->row['total'];
	}

	public function getProductsWithChangedAsins($start){
		$result = [];

		$sql = "SELECT p.product_id, p.asin as p_asin, pad.asin as pad_asin, pd.name FROM `product` p 
		LEFT JOIN product_amzn_data pad ON (p.product_id = pad.product_id) 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND language_id = '" . $this->config->get('config_rainforest_source_language_id') . "') 
		WHERE 
		p.asin <> pad.asin 
		AND p.asin <> ''
		AND p.asin <> 'INVALID'
		AND NOT ISNULL(pad.asin)
		AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1)) 
		ORDER BY p.date_added DESC
		limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::productRequestLimits;

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[$row['product_id']] = [
					'product_id' 			=> $row['product_id'],
					'name' 					=> $row['name'], 		
					'asin_in_product_table'	=> $row['p_asin'],
					'asin'					=> $row['pad_asin']							
				];
		}

		return $result;
	}

	public function getTotalProductsWithChangedAsins(){
		$sql = "SELECT COUNT(*) as total FROM product p 
			LEFT JOIN product_amzn_data pad ON (p.product_id = pad.product_id) 
			WHERE 			
			p.asin <> pad.asin 
			AND p.asin <> '' 
			AND p.asin <> 'INVALID'
			AND NOT ISNULL(pad.asin)
			AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))";

		return $this->db->ncquery($sql)->row['total'];
	}

	public function getProductsWithFullDataWithLostJSON($start, $filter_data = []){
		$result = [];

		$sql = "SELECT pad.* FROM product_amzn_data pad 
				LEFT JOIN product p ON (p.product_id = pad.product_id) 
				WHERE pad.product_id IN (SELECT product_id FROM product) ";

		if (!empty($filter_data)){
			foreach ($filter_data as $filter => $value){
				$sql .= " AND p.`" . $this->db->escape($filter) . "` = '" . $this->db->escape($value) . "' ";
			}
		}
		$sql .= " AND p.asin <> 'INVALID'";		
	//	$sql .= " AND pad.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))";
		$sql .= " ORDER BY p.date_added DESC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::productRequestLimits;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			if ($this->config->get('config_enable_amazon_asin_file_cache')){
				if (!empty($row['file']) && file_exists(DIR_CACHE . $row['file'])){
					$row['json'] = file_get_contents(DIR_CACHE . $row['file']);
				}
			}

			if (empty($row['json'])){
				$result[] = [
					'product_id' 			=> $row['product_id'],
					'asin' 					=> $row['asin']					
				];
			}
		}

		return $result;
	}

	public function getProductsWithFullData($start, $filter_data = []){
		$result = [];

		$sql = "SELECT pad.* FROM product_amzn_data pad LEFT JOIN product p ON (p.product_id = pad.product_id) WHERE pad.product_id IN (SELECT product_id FROM product) ";

		if (!empty($filter_data)){
			foreach ($filter_data as $filter => $value){
				$sql .= " AND p.`" . $this->db->escape($filter) . "` = '" . $this->db->escape($value) . "' ";
			}
		}

		$sql .= " AND p.asin <> 'INVALID' 
		AND pad.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1 AND amazon_can_get_full = 1))";
		$sql .= " ORDER BY pad.product_id ASC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			if ($this->config->get('config_enable_amazon_asin_file_cache')){
				if (!empty($row['file']) && file_exists(DIR_CACHE . $row['file'])){
					$row['json'] = file_get_contents(DIR_CACHE . $row['file']);
				}
			}

			if ($row['json']){
				$result[] = [
					'product_id' 			=> $row['product_id'],
					'asin' 					=> $row['asin'],
					'json'					=> $row['json']							
				];
			}
		}

		return $result;
	}

	public function getProductsWithInvalidASIN(){
		$sql = "SELECT product_id FROM product WHERE asin = 'INVALID'";

		return $this->db->ncquery($sql)->row['total'];
	}

	public function getTotalProductsWithFullDataInDB(){
		$sql = "SELECT COUNT(product_id) as total FROM product_amzn_data WHERE product_id IN (SELECT product_id FROM product) AND file = '' AND NOT ISNULL(json)";

		return $this->db->ncquery($sql)->row['total'];
	}

	public function getProductsWithFullDataInDB($start){
		$result = [];

		$sql = "SELECT * FROM product_amzn_data WHERE product_id IN (SELECT product_id FROM product) AND file = '' AND NOT ISNULL(json) ORDER BY product_id ASC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;	

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'asin' 					=> $row['asin'],
				'json'					=> $row['json'],					
			];
		}

		return $result;
	}

	public function getCategoriesToReprice(){
		$results = [];

		$sql 	= "SELECT category_id FROM category WHERE need_reprice = 1";
		$query 	= $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$results[] = $row['category_id'];
		}

		return $results;
	}

	public function setCategoryWasRepriced($category_id){
		$this->db->ncquery("UPDATE category SET need_reprice = 0, last_reprice = NOW() WHERE category_id = '" . (int)$category_id . "'");
	}


	public function getTotalProductsWithFastPriceFullForCategory($category_id){
		$result = [];

		$sql = "SELECT COUNT(DISTINCT p.product_id) as total FROM category_path cp 
			LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id) 
			LEFT JOIN product p ON (p2c.product_id = p.product_id)
			WHERE amazon_best_price > 0
			AND asin <> 'INVALID' 
			AND cp.path_id = '" . (int)$category_id . "'";		


		if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')) {
			$sql .= " AND added_from_amazon = 1";
		}

		return $this->db->ncquery($sql)->row['total'];		
	}	

	public function getProductsWithFastPriceFullForCategory($category_id, $start){
		$result = [];

		$sql = "SELECT DISTINCT p.* FROM category_path cp 
			LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id) 
			LEFT JOIN product p ON (p2c.product_id = p.product_id)
			WHERE amazon_best_price > 0 
			AND asin <> 'INVALID' 
			AND cp.path_id = '" . (int)$category_id . "'";
		

		if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')) {
			$sql .= " AND added_from_amazon = 1";
		}	

		$sql .= " ORDER BY p.product_id ASC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;	

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'amazon_best_price'		=> $row['amazon_best_price'],
				'asin' 					=> $row['asin']									
			];
		}

		return $result;
	}


	public function getTotalProductsWithFastPriceFull(){
		$result = [];

		$sql = "SELECT COUNT(product_id) as total 
			FROM product 
			WHERE amazon_best_price > 0 AND asin <> 'INVALID'";		

		if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')) {
			$sql .= " AND added_from_amazon = 1";
		}

		return $this->db->ncquery($sql)->row['total'];		
	}	

	public function getProductsWithFastPriceFull($start){
		$result = [];

		$sql = "SELECT * FROM product WHERE amazon_best_price > 0 AND asin <> 'INVALID' ";

		if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')) {
			$sql .= " AND added_from_amazon = 1";
		}	

		$sql .= " ORDER BY product_id ASC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'amazon_best_price'		=> $row['amazon_best_price'],
				'asin' 					=> $row['asin']									
			];
		}

		return $result;
	}

	public function getTotalProductsWithAsin(){
		$result = [];

		$sql = "SELECT COUNT(product_id) as total FROM product WHERE asin <> 'INVALID' AND asin <> ''";		

		return $this->db->ncquery($sql)->row['total'];		
	}	

	public function getProductsWithAsin($start){
		$result = [];

		$sql = "SELECT * FROM product WHERE asin <> '' AND asin <> 'INVALID' ORDER BY product_id ASC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'amazon_best_price'		=> $row['amazon_best_price'],
				'asin' 					=> $row['asin']									
			];
		}

		return $result;
	}

	public function getTotalProductsWithFastPrice(){
		$result = [];

		$sql = "SELECT COUNT(product_id) as total FROM product WHERE amazon_best_price > 0 AND asin <> 'INVALID' AND price = 0";	

		if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')) {
			$sql .= " AND added_from_amazon = 1";
		}		

		return $this->db->ncquery($sql)->row['total'];		
	}	

	public function getProductsWithFastPrice($start){
		$result = [];

		$sql = "SELECT * FROM product WHERE amazon_best_price > 0 AND price = 0 AND asin <> 'INVALID' ";

		if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')) {
			$sql .= " AND added_from_amazon = 1";
		}	

		$sql .= " ORDER BY product_id ASC limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			$result[] = [
				'product_id' 			=> $row['product_id'],
				'amazon_best_price'		=> $row['amazon_best_price'],
				'asin' 					=> $row['asin']									
			];
		}

		return $result;
	}

	public function getTotalProductsWithVariantsSet(){
		$sql = "SELECT COUNT(main_asin) as total FROM product_variants WHERE 1";

		return $this->db->ncquery($sql)->row['total'];
	}

	public function getProductsWithVariantsSet($start){

		$result = [];

		$sql = "SELECT * FROM product_variants WHERE 1 limit " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){			
			$result[] = [
				'main_asin' 	=> $row['main_asin'],
				'variant_asin'	=> $row['variant_asin']						
			];
		}

		return $result;
	}

	public function getIfProductHasFullData($product_id){
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

	public function getProductsWithNoImages(){
		$result = [];
		$query = $this->db->ncquery("SELECT product_id, amazon_product_image FROM product WHERE LENGTH(amazon_product_image) > 0 AND LENGTH(image) = 0");

		foreach ($query->rows as $row){
			$result[$row['product_id']] = $row['amazon_product_image'];					
		}

		return $result;
	}

	public function getProductsWhichWeteAddedFrom($date_added){
		$result = [];		
		$sql = "SELECT pad.* FROM product_amzn_data pad LEFT JOIN product p ON (p.product_id = pad.product_id) WHERE pad.product_id IN (SELECT product_id FROM product) ";

		$sql .= " AND p.date_added > '" . date('Y-m-d', strtotime($date_added)) . "'";	
		$sql .= " ORDER BY pad.product_id ASC";	

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			if ($this->config->get('config_enable_amazon_asin_file_cache')){
				if (!empty($row['file']) && file_exists(DIR_CACHE . $row['file'])){
					$row['json'] = file_get_contents(DIR_CACHE . $row['file']);
				}
			}

			if ($row['json']){
				$result[] = [
					'product_id' 			=> $row['product_id'],
					'asin' 					=> $row['asin'],
					'json'					=> $row['json']							
				];
			}
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

		if ($this->config->get('config_use_separate_table_for_features')){
			$product_attribute_query = $this->db->ncquery("SELECT feature_id AS 'attribute_id' FROM product_feature WHERE product_id = '" . (int)$product_id . "'  AND feature_id IN (SELECT attribute_id FROM attribute WHERE attribute_group_id = '" . $this->config->get('config_special_attr_id') . "') GROUP BY attribute_id");
		} else {
			$product_attribute_query = $this->db->ncquery("SELECT attribute_id FROM product_attribute WHERE product_id = '" . (int)$product_id . "'  AND attribute_id IN (SELECT attribute_id FROM attribute WHERE attribute_group_id = '" . $this->config->get('config_special_attr_id') . "') GROUP BY attribute_id");
		}

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();
			
			if ($this->config->get('config_use_separate_table_for_features')){
				$product_attribute_description_query = $this->db->ncquery("SELECT * FROM product_feature WHERE product_id = '" . (int)$product_id . "' AND feature_id = '" . (int)$product_attribute['attribute_id'] . "'");
			} else {
				$product_attribute_description_query = $this->db->ncquery("SELECT * FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			}

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

	public function checkIfProductIsVariantWithFilledParent($asin){
		$query = $this->db->ncquery("SELECT p.product_id, p.asin, p.description_filled_from_amazon FROM product p WHERE p.asin = (SELECT main_asin FROM product_variants WHERE main_asin <> '" . $this->db->escape($asin) . "' AND variant_asin = '" . $this->db->escape($asin) . "' LIMIT 1)");	

		if ($query->num_rows && !empty($query->row['description_filled_from_amazon'])){
			return [
				'main_variant_id' 					=> (int)$query->row['product_id'],
				'main_variant_asin'					=> $query->row['asin'],
				'description_filled_from_amazon'	=> (!empty($row['description_filled_from_amazon']))?$row['description_filled_from_amazon']:false,
			];
		} else {
			return false;
		}
	}

	public function getOtherProductVariantsByAsin($asin){
		$results = [];

		$sql = "SELECT pv.variant_asin, pv.main_asin, p.product_id, p.filled_from_amazon 
			FROM product_variants pv 
			LEFT JOIN product p ON (pv.variant_asin = p.asin) 
			WHERE main_asin = (SELECT main_asin FROM product_variants pv2 WHERE variant_asin = '" . $this->db->escape($asin) . "' LIMIT 1) 
			ORDER BY variant_asin DESC LIMIT 0, " . ((int)$this->config->get('config_rainforest_max_variants') + 1);
		$query = $this->db->ncquery($sql);

		if ($query->num_rows){
			foreach ($query->rows as $row){
				if ($row['variant_asin'] <> $asin){
					$results[] = [
						'asin'		 			=> $row['variant_asin'],
						'main_asin'				=> $row['main_asin'],
						'product_id'			=> (!empty($row['product_id']))?$row['product_id']:false,
						'filled_from_amazon'	=> (!empty($row['filled_from_amazon']))?$row['filled_from_amazon']:false,
					];
				}
			}
		}

		return $results;	
	}

	public function getProductName($product_id){		
		$query = $this->db->query("SELECT name FROM product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND product_id = '" . (int)$product_id . "' LIMIT 1");
			
		if ($query->num_rows){
			return $query->row['name'];
		}

		return '';
	}	

	public function getProductsWithNoShortNames(){			
		$sql = "SELECT p.product_id, pd.language_id, pd.name, pd.short_name_d FROM `order_product` op
		LEFT JOIN product p ON (p.product_id = op.product_id)
		LEFT JOIN `order` o ON (o.order_id = op.order_id) 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
		WHERE 
		o.order_status_id > 0
		AND p.status = 1
		AND pd.name <> ''
		AND pd.short_name_d = ''
		AND (
			pd.language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_language')]['language_id'] . "'
			OR pd.language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "')
		ORDER BY p.product_id DESC";

		$query = $this->db->query($sql);
			
		return $query->rows;
	}

	public function getProductShortNamesByAsin($asin){
		$results = [];
		$query = $this->db->ncquery("SELECT p.product_id, pd.language_id, pd.name, pd.short_name_d FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE asin LIKE ('" . $this->db->escape($asin) . "')");
			
		if ($query->num_rows){
			return $query->rows;
		}

		return false;
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

	public function checkIfProductWasAddedFromAmazon($product_id){
		$query = $this->db->ncquery("SELECT added_from_amazon FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");

		return (bool)$query->row['added_from_amazon'];
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
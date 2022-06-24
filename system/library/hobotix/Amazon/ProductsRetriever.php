<?
	
	namespace hobotix\Amazon;
	
	class ProductsRetriever extends RainforestRetriever
	{			

		private $productsPartAdder = null;
		private $productsPartParser = null;
		private $productRequestLimits = 30;

		private $attributesArray = [];
		private $manufacturersArray = [];

		private $mapLanguages = [
			'ua' => 'ru',
			'kz' => 'ru',
			'by' => 'ru'
		];

		private $passTranslateAttributes = [
			'Marke'
		];

		private $dimensionIsColor = [
			'farbe', 			
			'цвет', 
			'колір', 
			'color'
		];

		private $mapAmazonToStoreFieldsSpecifications = [
			'Modellnummer' 			=> ['sku'],
			'Artikelnummer' 		=> ['sku'],
			'Herstellerreferenz' 	=> ['sku']
		];

		private $mapAmazonToStoreFieldsSpecificationsRev = [
			'Modellnummer' 			=> ['model'],
			'Artikelnummer' 		=> ['model'],
			'Herstellerreferenz' 	=> ['model']
		];

		private $passAttributestAndSpecifications = [
			'Produktabmessungen',
			'Im Angebot von Amazon.de seit',
			'Marke',
			'Amazon Bestseller-Rang',
			'Auslaufartikel (Produktion durch Hersteller eingestellt)',
			'Durchschnittliche Kundenbewertung',
			'ASIN'			
		];

		
		const CLASS_NAME = 'hobotix\\Amazon\\ProductsRetriever';
		
		public function getProducts(){
			
			$result = [];
			$sql = "SELECT p.*, pd.name FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)  WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' AND added_from_amazon = 1 AND p.product_id NOT IN (SELECT product_id FROM product_amzn_data) AND (NOT ISNULL(p.asin) OR p.asin <> '') AND asin IN ('B07ZBRMW89')";
			
			$query = $this->db->ncquery($sql);
			
			foreach ($query->rows as $row){
				$result[] = [
				'product_id' 			=> $row['product_id'],
				'asin' 					=> $row['asin']								
				];
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
		
		public function getManufacturer($name, $recursive = false){

			if ($this->manufacturersArray || $recursive){
				if (!empty($this->manufacturersArray[$name])){
					return $this->manufacturersArray[$name];
				} else {
					return false;
				}
			} else {

				$query = $this->db->query("SELECT * FROM manufacturer WHERE 1");

					foreach ($query->rows as $row){
						$this->manufacturersArray[$row['name']] = $row['manufacturer_id'];
					}

					return $this->getManufacturer($name, true);

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

			$this->manufacturersArray[$name] = (int)$manufacturer_id;
			
			return (int)$manufacturer_id;
		}
		
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
		
		public function editProductDescriptions($product_id, $data){			
			foreach ($data as $language_id => $value) {
				$this->db->query("UPDATE product_description SET 
				description 	= '" . $this->db->escape($value['description']) . "',				
				translated 		= '" . (int)$value['translated'] . "'
				WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
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

		public function guessIfDimensionIsColor($product_id, $data){
			foreach ($data as $language_id => $value) {
				if ($language_id == $this->config->get('config_rainforest_source_language_id')){
					if (in_array($value['variant_name_1'], $this->dimensionIsColor)){
						$this->db->query("UPDATE product SET variant_1_is_color = 1 WHERE product_id = '" . (int)$product_id . "'");
					} elseif (in_array($value['variant_name_2'], $this->dimensionIsColor)) {
						$this->db->query("UPDATE product SET variant_2_is_color = 1 WHERE product_id = '" . (int)$product_id . "'");
					}
				}
			}
		}
		
		public function getAttribute($name, $recursive = false){
			if ($this->attributesArray || $recursive){
				if (!empty($this->attributesArray[$name])){
					return $this->attributesArray[$name];
				} else {
					return false;
				}
			} else {
				$query = $this->db->query("SELECT * FROM attribute_description WHERE language_id = '" . $this->config->get('config_rainforest_source_language_id') . "'");

				foreach ($query->rows as $row){
					$this->attributesArray[$row['name']] = $row['attribute_id'];
				}

				return $this->getAttribute($name, true);
			}	
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

			$this->attributesArray[$name] = (int)$attribute_id;
		
			return (int)$attribute_id;
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
				$this->db->query("INSERT INTO product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}

		}

		public function editProductSimilar($product_id, $data){

			$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "'");			
			
			foreach ($data as $similar_id) {
				$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$product_id . "' AND similar_id = '" . (int)$similar_id . "'");
				$this->db->query("INSERT INTO product_similar SET product_id = '" . (int)$product_id . "', similar_id = '" . (int)$similar_id . "'");
				$this->db->query("DELETE FROM product_similar WHERE product_id = '" . (int)$similar_id . "' AND similar_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO product_similar SET product_id = '" . (int)$similar_id . "', similar_id = '" . (int)$product_id . "'");
			}
		}
		
		public function getCurrentProductCategory($product_id){

			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 1 LIMIT 1");

			if ($query->num_rows){
				return $query->row['category_id'];
			} else {
				return $this->config->get('config_rainforest_default_technical_category_id');				
			}
		}

		public function parseProductVideos($product_id, $product){
			if (!empty($product['videos'])){
				$videos = [];
				$sort_order = 0;


				$product_video_description = [];
				foreach ($product['videos'] as $video){
					foreach ($this->registry->get('languages') as $language_code => $language) {
						$video['title'] = atrim($video['title']);

						$real_language_code = $language_code;
						if (!empty($this->mapLanguages[$language_code])){							
							$real_language_code = $this->mapLanguages[$language_code];
						}

						if ($language_code == $this->config->get('config_rainforest_source_language')){
							$title = $video['title'];	
							$translated = true;
						} else {
							if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
								$title = $this->yandexTranslator->translate($video['title'], $this->config->get('config_rainforest_source_language'), $real_language_code, true);
							} else {
								$title = $video['title'];
								$translated = false;
							}
						}


						$product_video_description[$language['language_id']] = [
							'title' => $title,
						];
					}

					if ($video['link']){
						$videos[] = [
							'video' 					=> $this->getImage($video['link']),
							'image'						=> $video['thumbnail']?$this->getImage($video['thumbnail']):'',
							'sort_order'				=> $sort_order,
							'product_video_description' => $product_video_description
						];						
					}

					$this->editProductVideos($product_id, $videos);

					$sort_order++;

				}
			}
		}

		public function parseProductColor($product_id, $product){

			if (!empty($product['color'])){
				$product_color = [];			
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$product_color[$language['language_id']] = [
						'color' => $this->translateWithCheck($product['color'], $language_code)
					];
				}
				
				$this->editProductColor($product_id, $product_color);
			}


		}

		public function parseProductMaterial($product_id, $product){
			if (!empty($product['material'])){
				$product_material = [];			
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$product_material[$language['language_id']] = [
						'material' => $this->translateWithCheck($product['material'], $language_code)
					];
				}
				
				$this->editProductMaterial($product_id, $product_material);
			}
		}

		public function parseProductVariants($product_id, $product){
			if (!empty($product['variants'])){
				foreach ($product['variants'] as $variant){
					if ($variant['is_current_product']){
						$product_variants_description = [];
						foreach ($this->registry->get('languages') as $language_code => $language) {						
							$variant_name_1 = $variant_name_2 = $variant_value_1 = $variant_value_2 = '';

							if (!empty($variant['dimensions'][0])){
								$variant_name_1  = $this->translateWithCheck($variant['dimensions'][0]['name'], $language_code);
								$variant_value_1 = $this->translateWithCheck($variant['dimensions'][0]['value'], $language_code);
							}

							if (!empty($variant['dimensions'][1])){
								$variant_name_2  = $this->translateWithCheck($variant['dimensions'][1]['name'], $language_code);
								$variant_value_2 = $this->translateWithCheck($variant['dimensions'][1]['value'], $language_code);
							}

							if ($language_code == $this->config->get('config_rainforest_source_language')){
								$current_dimensions   = $variant['dimensions'];
							}

							$product_variants_description[$language['language_id']] = [
								'variant_name' 		=> $this->translateWithCheck($variant['title'], $language_code), 
								'variant_name_1'  	=> mb_strtolower($variant_name_1), 
								'variant_name_2' 	=> mb_strtolower($variant_name_2), 
								'variant_value_1'  	=> mb_strtolower($variant_value_1), 
								'variant_value_2' 	=> mb_strtolower($variant_value_2)
							];
						}

						$this->editProductVariantsDescriptions($product_id, $product_variants_description);
						$this->guessIfDimensionIsColor($product_id, $product_variants_description);
					}
				}

				//
				unset($variant);
				$new_product_ids = [];
				foreach ($product['variants'] as $variant){
					if (!$this->getProductsByAsin($variant['asin'])){

					//Убираем из названия основного товара названия вариантов
						$new_product_name = atrim($product['title']);
						foreach ($current_dimensions as $current_dimension){
							$new_product_name = str_ireplace($current_dimension['value'] . ',' , '', $new_product_name);
							$new_product_name = str_ireplace($current_dimension['value'], '', $new_product_name);
							$new_product_name = str_ireplace(['()', '(,)', '(, )'], '', $new_product_name);
							$new_product_name = atrim($new_product_name);
						}

						if (!empty($variant['dimensions'])){
							$dimensions_name_array = [];
							foreach ($variant['dimensions'] as $dimension){
								$dimensions_name_array[] = $dimension['value'];
							}
						}

						if ($dimensions_name_array){
							$new_product_name .= (' (' . implode(', ', $dimensions_name_array) . ') ');
						}

						echoLine('[editFullProduct] Новый вариант: ' . $new_product_name);

						$new_product_ids[] = [
							'product_id' => $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $variant['asin'], 
								'category_id' 		=> $this->getCurrentProductCategory($product_id), 
								'main_variant_id'	=> $product_id,
								'name' 				=> $new_product_name,
								'image' 			=> $this->getImage($variant['main_image']), 
								'added_from_amazon' => 1
							]),
							'asin' => $variant['asin']
						];
					}
				}


				if ($new_product_ids){

					$total = count($new_product_ids);
					$iterations = ceil($total/$this->productRequestLimits);

					for ($i = 1; $i <= $iterations; $i++){
						$slice = array_slice($new_product_ids, $this->productRequestLimits * ($i-1), $this->productRequestLimits);
						$results = $this->registry->get('rainforestAmazon')->simpleProductParser->getProductByASINS($slice);

						foreach ($results as $product_id => $result){
							$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);

							if ($result){
								echoLine('[EditFullProducts] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);				

								$this->registry->get('rainforestAmazon')->productsRetriever->editFullProduct($product_id, $result);

							} else {
								echoLine('[EditFullProducts] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);
							}
						}

					}
				}
			}
		}


		public function translateWithCheck($text, $language_code){
			$text = atrim($text);

			if ($language_code == $this->config->get('config_rainforest_source_language')){
				$text = $text;	
			} else {
				if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language_code)){
					$text = $this->yandexTranslator->translate($text, $this->config->get('config_rainforest_source_language'), $language_code, true);
				} else {
					$text = $text;
				}
			}

			return $text;
		}
		
		
		public function editFullProduct($product_id, $product, $recursive_add_similar = true){
			//Load Library model/catalog/product
			require_once(DIR_APPLICATION . '../admin/model/catalog/product.php');
			$this->model_catalog_product = new \ModelCatalogProduct($this->registry);
			
			//Product Link
			$this->editProductFields($product_id, [['name' => 'amazon_product_link', 'type' => 'varchar', 'value' => $product['link']]]);
			//Product Link End

		
			//Бренд
			if (!empty($product['brand'])){
				$product['brand'] = atrim($product['brand']);

				echoLine('[editFullProduct] Бренд: ' . $product['brand']);
				$manufacturer_id = $this->getManufacturer($product['brand']);			
				if (!$manufacturer_id){
					echoLine('[editFullProduct] Бренд не существует, добавляем:' . $product['brand']);
					$manufacturer_id = $this->addManufacturer($product['brand']);
				}
				$this->editProductFields($product_id, [['name' => 'manufacturer_id', 'type' => 'int', 'value' => $manufacturer_id]]);
			}	
			//Бренд END
			
			//Описание
			if (!empty($product['description'])){
				$product_description = [];			
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$product['description'] = atrim($product['description']);

					if ($language_code == $this->config->get('config_rainforest_source_language')){
						$description = $product['description'];	
						$translated = true;
						} else {
						if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
							$description = $this->yandexTranslator->translate($product['description'], $this->config->get('config_rainforest_source_language'), $language_code, true);
							$translated = true;
							} else {
							$description = $product['description'];
							$translated = false;
						}
					}
					
					
					$product_description[$language['language_id']] = [
					'description' => $description,
					'translated'  => $translated
					];
				}
				
				$this->editProductDescriptions($product_id, $product_description);
			}
			//Описание END


			//Цвет, отдельным полем
			$this->parseProductColor($product_id, $product);

			//Материал, отдельным полем
			$this->parseProductMaterial($product_id, $product);
			

			//Картинки
			if (!empty($product['images'])){
				$images = [];
				$sort_order = 0;
				foreach ($product['images'] as $image){
					if ($image['variant'] == 'MAIN'){
						$this->editProductFields($product_id, [['name' => 'image', 'type' => 'varchar', 'value' => $this->getImage($image['link'])]]);
					} else {				
						$images[] = [
							'image' 		=> $this->getImage($image['link']),
							'sort_order' 	=> $sort_order
						];

						$sort_order++;
					}
				}
				if ($images){
					$this->editProductImages($product_id, $images);
				}
			}
			//Картинки END

			//Видео
			$this->parseProductVideos($product_id, $product);			

			//Особенности, специальная группа атрибутов
			$product_attribute = [];
			if (!empty($product['feature_bullets'])){				
				$feature_bullets_counter = 1;
				foreach ($product['feature_bullets'] as $feature_bullet){
					echoLine('[editFullProduct] Особенности: ' . $feature_bullets_counter);

					$attribute_id = $this->getAttribute($this->config->get('config_special_attr_name') . ' ' . $feature_bullets_counter);
					if (!$attribute_id){
						$attribute_description = [];
						foreach ($this->registry->get('languages') as $language_code => $language) {
							$attribute_description[$language['language_id']] = [
								'name' => $this->config->get('config_special_attr_name') . ' ' . $feature_bullets_counter
							];
						}
						$attribute = [
							'attribute_group_id' 	=> $this->config->get('config_special_attr_id'),
							'attribute_description' => $attribute_description
						];
						$attribute_id = $this->addAttribute($attribute);
					}

					$product_attribute_description = [];
					foreach ($this->registry->get('languages') as $language_code => $language) {
						$product_attribute_description[$language['language_id']] = [
							'text' => $this->translateWithCheck($feature_bullet, $language_code)
						];
					}

					$product_attribute[] = [
						'attribute_id' 					=> $attribute_id,
						'product_attribute_description' => $product_attribute_description
					];

					$feature_bullets_counter++;
				}
				
			}


			//Атрибуты и спецификации
			if (!empty($product['attributes']) || !empty($product['specifications'])){

				//Репарсим массивы атрибутов и спецификаций
				$mergedProductAttributes = [];

				if (!empty($product['attributes'])){
					foreach ($product['attributes'] as $temp){
						$temp['name'] = atrim($temp['name']);
						$temp['value'] = atrim($temp['value']);

						$mergedProductAttributes[clean_string($temp['name'])] = [
							'name' 	=> $temp['name'],
							'value' => $temp['value']
						];
					}
				}

				unset($temp);
				if (!empty($product['specifications'])){
					foreach ($product['specifications'] as $temp){
						$temp['name'] = atrim($temp['name']);
						$temp['value'] = atrim($temp['value']);

						$mergedProductAttributes[clean_string($temp['name'])] = [
							'name' 	=> $temp['name'],
							'value' => $temp['value']
						];
					}
				}

				//И удаляем те, которые нам вообще не вперлись
				foreach ($this->passAttributestAndSpecifications as $pass){
					if (!empty($mergedProductAttributes[clean_string($pass)])){
						unset($mergedProductAttributes[clean_string($pass)]);
					}
				}

				foreach ($mergedProductAttributes as $attribute){
					echoLine('[editFullProduct] Атрибуты: ' . $attribute['name']);
					$attribute['name'] = atrim($attribute['name']);
					$attribute['value'] = atrim($attribute['value']);	

					if (!empty($this->mapAmazonToStoreFieldsSpecifications[clean_string($attribute['name'])])){

						foreach ($this->mapAmazonToStoreFieldsSpecifications[clean_string($attribute['name'])] as $fieldToChange){
							echoLine('[editFullProduct] Атрибут ' . $attribute['name'] . ' -> ' . $fieldToChange);
							$this->editProductFields($product_id, [['name' => $fieldToChange, 'type' => 'varchar', 'value' => $attribute['value']]]);
						}
					} elseif (!empty($this->mapAmazonToStoreFieldsSpecificationsRev[clean_string($attribute['name'])])){

						foreach ($this->mapAmazonToStoreFieldsSpecificationsRev[clean_string($attribute['name'])] as $fieldToChange){
							echoLine('[editFullProduct] Атрибут ' . $attribute['name'] . ' -> ' . $fieldToChange);
							$this->editProductFields($product_id, [['name' => $fieldToChange, 'type' => 'varchar', 'value' => strrev($attribute['value'])]]);
						}
					} else {


						$attribute_id = $this->getAttribute($attribute['name']);

						if (!$attribute_id){
							$attribute_description = [];
							foreach ($this->registry->get('languages') as $language_code => $language) {						
								$attribute_description[$language['language_id']] = [
									'name' => $this->translateWithCheck($attribute['name'], $language_code)
								];
							}
							$attribute_data = [
								'attribute_group_id' 	=> $this->config->get('config_default_attr_id'),
								'attribute_description' => $attribute_description
							];

							$attribute_id = $this->addAttribute($attribute_data);
						}

						$product_attribute_description = [];
						foreach ($this->registry->get('languages') as $language_code => $language) {						
							$product_attribute_description[$language['language_id']] = [
								'text' => $this->translateWithCheck($attribute['value'], $language_code)
							];
						}

						$product_attribute[] = [
							'attribute_id' 					=> $attribute_id,
							'product_attribute_description' => $product_attribute_description
						];
					}
				}
			}

			if ($product_attribute){
				$this->editProductAttributes($product_id, $product_attribute);
			}

			//Размеры, готовая функция из InfoUpdater
			$this->registry->get('rainforestAmazon')->infoUpdater->parseAndUpdateProductDimensions($product);

			//Related Products
			if (!empty($product['frequently_bought_together']) && !empty($product['frequently_bought_together']['products'])){
				$product_related = [];
				foreach ($product['frequently_bought_together']['products'] as $bought_together){
					if ($related = $this->getProductsByAsin($bought_together['asin'])){

						foreach ($related as $related_id){
							echoLine('[editFullProduct] Покупают вместе: ' . $related_id);

							$product_related[] = $related_id;
						}

					} else {

						if ($this->config->get('config_rainforest_enable_recursive_adding')){

							echoLine('[editFullProduct] Новый покупают вместе товар: ' . $bought_together['asin'] . ' ' . $bought_together['title']);

							$new_related_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $bought_together['asin'], 
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $bought_together['title'], 
								'image' 			=> $this->getImage($bought_together['image']), 
								'added_from_amazon' => 1
							]);							

							$product_similar[] = $new_related_id;

						}

					}
				}

				if ($product_related){
					$this->editProductRelated($product_id, $product_related);
				}
			}
						

			//Similar Products
			if (!empty($product['compare_with_similar'])){
				$product_similar = [];
				foreach ($product['compare_with_similar'] as $compare_with_similar){
					if ($similar = $this->getProductsByAsin($compare_with_similar['asin'])){

						foreach ($similar as $similar_id){
							echoLine('[editFullProduct] Похожий товар: ' . $similar_id);

							$product_similar[] = $similar_id;
						}

					} else {
						if ($this->config->get('config_rainforest_enable_recursive_adding')){

							echoLine('[editFullProduct] Новый похожий товар: ' . $compare_with_similar['asin'] . ' ' . $compare_with_similar['title']);

							$new_similar_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $compare_with_similar['asin'], 
								'category_id' 		=> $this->getCurrentProductCategory($product_id), 
								'name' 				=> $compare_with_similar['title'], 
								'image' 			=> $this->getImage($compare_with_similar['image']), 
								'added_from_amazon' => 1
							]);							

							$product_similar[] = $new_similar_id;

						}
					}
				}

				if ($product_similar){
					$this->editProductSimilar($product_id, $product_similar);
				}
			}

			//Варианты
			$this->parseProductVariants($product_id, $product);			
			
			$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);
			$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmznData([
						'product_id' 	=> $product_id, 
						'asin' 			=> $product['asin'], 
						'json' 			=> json_encode($product)
			]);
		}
		

		private function checkIfAsinIsDeleted($asin){

			$query = $this->db->query("SELECT asin FROM deleted_asins WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

			return $query->num_rows;

		}
		
		public function addSimpleProductWithOnlyAsin($data) {			

			if ($this->checkIfAsinIsDeleted($data['asin'])){
				echoLine('[RainforestRetriever] ASIN удален, пропускаем!');				
				return 0;
			}


			$this->db->query("INSERT INTO product SET 
			model 				= '" . $this->db->escape($data['asin']) . "', 
			asin 				= '" . $this->db->escape($data['asin']) . "', 
			image           	= '" . $this->db->escape($data['image']) . "', 
			added_from_amazon 	= '1', 
			main_variant_id     = '" . (!empty($data['main_variant_id'])?(int)$data['main_variant_id']:0) . "',
			stock_status_id 	= '" . $this->config->get('config_stock_status_id') . "',
			quantity 			= '0',
			status 				= '0',
			date_added 			= NOW()");
			
			$product_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
			
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['category_id'] . "', main_category = 1");
			
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($this->registry->get('languages') as $language_code => $language) {
				
				if ($language_code == $this->config->get('config_rainforest_source_language')){
					$name = $data['name'];	
					$translated = true;				
					} else {
					if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
						$name = $this->yandexTranslator->translate($data['name'], $this->config->get('config_rainforest_source_language'), $language_code, true);
						$translated = true;
						} else {
						$name = $data['name'];
						$translated = false;
					}
				}
				
				$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product_id . "', translated = '" . (int)$translated . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($name) . "'");
			}
			
			return $product_id;
		}
		
	}	
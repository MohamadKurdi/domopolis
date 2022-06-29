<?
	
	namespace hobotix\Amazon;
	
	class ProductsRetriever extends RainforestRetriever
	{			

		private $productRequestLimits = 30;

		private $attributesArray = [];
		private $manufacturersArray = [];
		private $categoriesArray = [];

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
			'ASIN' 					=> ['model']
		];

		private $passAttributesAndSpecifications = [
			'Produktabmessungen',
			'Hersteller',
			'Im Angebot von Amazon.de seit',
			'Marke',
			'Amazon Bestseller-Rang',
			'Auslaufartikel (Produktion durch Hersteller eingestellt)',
			'Durchschnittliche Kundenbewertung'			
		];

		
		const CLASS_NAME = 'hobotix\\Amazon\\ProductsRetriever';
		
		//TODO: refactor
		public function getProducts(){
			return $this->model_product_get->getProducts();
		}


		public function getProductsFromTechCategory(){
			return $this->model_product_get->getProductsFromTechCategory();
		}	
		
		public function getProductsWithFullDataButNotFullfilled(){
			return $this->model_product_get->getProductsWithFullDataButNotFullfilled();
		}

		//TODO: refactor
		public function getProductsByAsin($asin){
			return $this->model_product_get->getProductsByAsin($asin);
		}
		
		public function translateWithCheck($text, $language_code){
			$text = atrim($text);

			$real_language_code = $language_code;
			if (!empty($this->mapLanguages[$language_code])){							
				$real_language_code = $this->mapLanguages[$language_code];
			}

			if ($language_code == $this->config->get('config_rainforest_source_language')){
				$text = $text;	
			} else {
				if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $real_language_code)){
					$text = $this->yandexTranslator->translate($text, $this->config->get('config_rainforest_source_language'), $real_language_code, true);
				} else {
					$text = '';
				}
			}

			return $text;
		}		

		public function getCategory($name, $recursive = false){
			$name = atrim($name);

			if ($this->categoriesArray || $recursive){
				if (!empty($this->categoriesArray[$name])){
					return $this->categoriesArray[$name];
				} else {
					return false;
				}
			} else {
				$query = $this->db->ncquery("SELECT cd.category_id, cd.name FROM category_description cd LEFT JOIN category c ON (cd.category_id = c.category_id) WHERE c.amazon_final_category = 1 AND cd.language_id = '" . $this->config->get('config_rainforest_source_language_id') . "'");
					foreach ($query->rows as $row){
						$this->categoriesArray[$row['name']] = $row['category_id'];
					}
					return $this->getCategory($name, true);
				}
		}
		
		public function getManufacturer($name, $recursive = false){
			if ($this->manufacturersArray || $recursive){
				if (!empty($this->manufacturersArray[$name])){
					return $this->manufacturersArray[$name];
				} else {
					return false;
				}
			} else {
				$query = $this->db->ncquery("SELECT * FROM manufacturer WHERE 1");
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
						
		public function getAttribute($name, $recursive = false){
			if ($this->attributesArray || $recursive){
				if (!empty($this->attributesArray[$name])){
					return $this->attributesArray[$name];
				} else {
					return false;
				}
			} else {
				$query = $this->db->ncquery("SELECT * FROM attribute_description WHERE language_id = '" . $this->config->get('config_rainforest_source_language_id') . "'");

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

		public function parseProductManufacturer($product_id, $product){
			if (!empty($product['brand'])){
				$product['brand'] = atrim($product['brand']);

				echoLine('[editFullProduct] Бренд: ' . $product['brand']);
				$manufacturer_id = $this->getManufacturer($product['brand']);			
				if (!$manufacturer_id){
					echoLine('[editFullProduct] Бренд не существует, добавляем:' . $product['brand']);
					$manufacturer_id = $this->addManufacturer($product['brand']);
				}
				$this->model_product_edit->editProductFields($product_id, [['name' => 'manufacturer_id', 'type' => 'int', 'value' => $manufacturer_id]]);
			}				
		}

		public function parseProductDescriptions($product_id, $product){			
			if (!empty($product['description'])){
				$product_description = [];			
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$product['description'] = atrim($product['description']);

					$description = $this->translateWithCheck($product['description'], $language_code);				
					
					$product_description[$language['language_id']] = [
					'description' => $description,
					'translated'  => (int)(mb_strlen($description)>0)					
					];
				}
				
				$this->model_product_edit->editProductDescriptions($product_id, $product_description);
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

						$title = $this->translateWithCheck($video['title'], $language_code);

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

					$this->model_product_edit->editProductVideos($product_id, $videos);

					$sort_order++;

				}
			}
		}

		public function parseProductImages($product_id, $product){
			if (!empty($product['images'])){
				$images = [];
				$sort_order = 0;
				foreach ($product['images'] as $image){
					if ($image['variant'] == 'MAIN'){
						$this->model_product_edit->editProductFields($product_id, [['name' => 'image', 'type' => 'varchar', 'value' => $this->getImage($image['link'])]]);
					} else {				
						$images[] = [
							'image' 		=> $this->getImage($image['link']),
							'sort_order' 	=> $sort_order
						];

						$sort_order++;
					}
				}
				if ($images){
					$this->model_product_edit->editProductImages($product_id, $images);
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
				
				$this->model_product_edit->editProductColor($product_id, $product_color);
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
				
				$this->model_product_edit->editProductMaterial($product_id, $product_material);
			}
		}

		public function parseProductAttributes($product_id, $product, $main_variant_id = false){
			$product_attribute = [];			
			if (!empty($product['feature_bullets']) && ($main_variant_id === false || $main_variant_id == $product_id)){				
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
				
			} elseif ($main_variant_id && $main_variant_id != $product_id){
				echoLine('[editFullProduct] Копируем feature_bullets с основного товара: ' . $main_variant_id);
				$product_attribute = $this->model_product_get->getProductFeatureBullets($main_variant_id);
			}


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
				foreach ($this->passAttributesAndSpecifications as $pass){
					if (!empty($mergedProductAttributes[clean_string($pass)])){
						unset($mergedProductAttributes[clean_string($pass)]);
					}
				}

				foreach ($mergedProductAttributes as $attribute){
					echoLine('[editFullProduct] Атрибуты: ' . $attribute['name']);
					$attribute['name'] = atrim($attribute['name']);
					$attribute['value'] = atrim($attribute['value']);	

					$mappedAttribute = false;
					if (!empty($this->mapAmazonToStoreFieldsSpecifications[clean_string($attribute['name'])])){
						$mappedAttribute = true;

						foreach ($this->mapAmazonToStoreFieldsSpecifications[clean_string($attribute['name'])] as $fieldToChange){
							echoLine('[editFullProduct] Атрибут ' . $attribute['name'] . ' -> ' . $fieldToChange);
							$this->model_product_edit->editProductFields($product_id, [['name' => $fieldToChange, 'type' => 'varchar', 'value' => $attribute['value']]]);
						}
					} 

					if (!empty($this->mapAmazonToStoreFieldsSpecificationsRev[clean_string($attribute['name'])])){
						$mappedAttribute = true;

						foreach ($this->mapAmazonToStoreFieldsSpecificationsRev[clean_string($attribute['name'])] as $fieldToChange){
							echoLine('[editFullProduct] Атрибут ' . $attribute['name'] . ' -> ' . $fieldToChange);
							$this->model_product_edit->editProductFields($product_id, [['name' => $fieldToChange, 'type' => 'varchar', 'value' => strrev($attribute['value'])]]);
						}
					} 

					if (!$mappedAttribute) {

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
				$this->model_product_edit->editProductAttributes($product_id, $product_attribute);
			}			

			if ($main_variant_id && !$main_variant_id == $product_id){
				var_dump($product_attribute);
				die();
			}
		}

		public function parseProductRelatedProducts($product_id, $product){
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
					$this->model_product_edit->editProductRelated($product_id, $product_related);
				}
			}
		}

		public function parseProductSponsoredProducts($product_id, $product){	
			if (!empty($product['sponsored_products'])){
				$product_sponsored = [];
				foreach ($product['sponsored_products'] as $sponsored_product){
					if ($sponsored = $this->getProductsByAsin($sponsored_product['asin'])){

						foreach ($sponsored as $sponsored_id){
							echoLine('[editFullProduct] Sponsored: ' . $sponsored_id);

							$product_sponsored[] = $sponsored_id;
						}

					} else {

						if ($this->config->get('config_rainforest_enable_recursive_adding')){

							echoLine('[editFullProduct] Новый sponsored товар: ' . $sponsored_product['asin'] . ' ' . $sponsored_product['title']);

							$new_sponsored_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $sponsored_product['asin'], 
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $sponsored_product['title'], 
								'image' 			=> $this->getImage($sponsored_product['image']), 
								'added_from_amazon' => 1
							]);							

							$product_sponsored[] = $new_sponsored_id;

						}

					}
				}

				if ($product_sponsored){
					$this->model_product_edit->editProductSponsored($product_id, $product_sponsored);
				}
			}
		}

		public function parseProductSimilarProducts($product_id, $product){
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
								'category_id' 		=> $this->model_product_get->getCurrentProductCategory($product_id), 
								'name' 				=> $compare_with_similar['title'], 
								'image' 			=> $this->getImage($compare_with_similar['image']), 
								'added_from_amazon' => 1
							]);							

							$product_similar[] = $new_similar_id;

						}
					}
				}

				if ($product_similar){
					$this->model_product_edit->editProductSimilar($product_id, $product_similar);
				}
			}
		}

		public function parseProductCategories($product_id, $product){
			if (!empty($product['categories'])){
				if ($this->model_product_get->getCurrentProductCategory($product_id) == $this->config->get('config_rainforest_default_technical_category_id')){
					$name = $product['categories'][count($product['categories']) - 1]['name'];

					if ($category_id = $this->getCategory(atrim($name))){
						echoLine('[editFullProduct] Нашли категорию: ' . $name . ': ' . $category_id);
						$this->model_product_edit->editProductCategory($product_id, [$category_id]);
					} else {

						echoLine('[editFullProduct] Не нашли категорию: ' . $name . ', уходит в неизвестную');
						$this->model_product_edit->editProductCategory($product_id, [$this->config->get('config_rainforest_default_unknown_category_id')]);

					}
				}
			}
		}

		public function parseProductVariantDimensions($product_id, $product){
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

							$product_variants_description[$language['language_id']] = [
								'variant_name' 		=> $this->translateWithCheck($variant['title'], $language_code), 
								'variant_name_1'  	=> mb_strtolower($variant_name_1), 
								'variant_name_2' 	=> mb_strtolower($variant_name_2), 
								'variant_value_1'  	=> mb_strtolower($variant_value_1), 
								'variant_value_2' 	=> mb_strtolower($variant_value_2)
							];
						}

						$this->model_product_edit->editProductVariantsDescriptions($product_id, $product_variants_description);
						$this->guessIfDimensionIsColor($product_id, $product_variants_description);
					}
				}
			}
		}

		public function getCurrentVariantDimensions($variants){
			if (!empty($variants)){
				foreach ($variants as $variant){
					if ($variant['is_current_product']){
						foreach ($this->registry->get('languages') as $language_code => $language) {	
							if ($language_code == $this->config->get('config_rainforest_source_language')){
								return $variant['dimensions'];
							}
						}
					}
				}
			}
		}

		public function trimProductNameWithoutVariant($name, $main_variant_dimensions, $current_variant_dimensions){
			$new_product_name = atrim($name);
			foreach ($main_variant_dimensions as $main_variant_dimensions){
				$new_product_name = str_ireplace($main_variant_dimensions['value'] . ',' , '', $new_product_name);
				$new_product_name = str_ireplace($main_variant_dimensions['value'], '', $new_product_name);
				$new_product_name = str_ireplace(['()', '(,)', '(, )', '( )'], '', $new_product_name);
				$new_product_name = atrim($new_product_name);
			}

			if (!empty($current_variant_dimensions)){
				$dimensions_name_array = [];
				foreach ($current_variant_dimensions as $dimension){
					$dimensions_name_array[] = $dimension['value'];
				}
			}

			if (!empty($dimensions_name_array)){
				$new_product_name .= (' (' . implode(', ', $dimensions_name_array) . ') ');
			}

			return $new_product_name;
		}

		public function parseProductVariants($product_id, $product, $variants = true){
			$this->parseProductVariantDimensions($product_id, $product);

			if (!empty($product['variants'])){										
				$new_product_data = [];
				foreach ($product['variants'] as $variant){
					
					//Проверим, существует ли такой вариант как товар в базе
					$existentVariants = $this->getProductsByAsin($variant['asin']);
					
					//Если мы обрабатываем варианты, и такой вариант не существует, то мы его добавляем, с привязкой к текущему родителю
					if ($variants && !$existentVariants){

						$new_product_name = $this->trimProductNameWithoutVariant($product['title'], $this->getCurrentVariantDimensions($product['variants']), $variant['dimensions']);
						echoLine('[editFullProduct] Новый вариант: ' . $new_product_name);

						$new_product_data[] = [
							'product_id' => $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $variant['asin'], 
								'category_id' 		=> $this->model_product_get->getCurrentProductCategory($product_id), 
								'main_variant_id'	=> $product_id,
								'name' 				=> $new_product_name,
								'image' 			=> $this->getImage($variant['main_image']), 
								'added_from_amazon' => 1
							]),
							'asin' => $variant['asin']
						];

					//Если мы обрабатываем варианты, и такой вариант существует, он мог быть добавлен хрен пойми откуда, например из похожих
					} elseif ($variants && $existentVariants) {						
						foreach ($existentVariants as $existent_variant_id){
							//Обновляем привязку родителя
							$this->model_product_edit->updateProductMainVariantId($existent_variant_id, $product_id);

							//Проверяем, заполнен ли вариант, и если не, то отправляем его на полную загрузку
							if (!$this->model_product_get->getIfProductIsFullFilled($existent_variant_id)){
								$new_product_data[] = [
									'product_id' => $existent_variant_id,
									'asin'		 => $variant['asin']
								];
							}
						}
					}
				}

				if ($new_product_data){
					$this->editFullProductsAsyncWithNoVariantParser($new_product_data);
				}
			}
		}

		public function editJustProductCategory($product_id, $product){			
			$this->parseProductCategories($product_id, $product);

			$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);			
			$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmznData([
						'product_id' 	=> $product_id, 
						'asin' 			=> $product['asin'], 
						'json' 			=> json_encode($product)
			], false);
		}
		
		public function editFullProduct($product_id, $product, $variants = true){	
			$this->yandexTranslator->setDebug(false);

			//Product Link
			$this->model_product_edit->editProductFields($product_id, [['name' => 'amazon_product_link', 'type' => 'varchar', 'value' => $product['link']]]);			

			//Бренд
			$this->parseProductManufacturer($product_id, $product);
			
			//Описание
			//Если этот товар - вариант другого товара, то описание мы копируем тупо с родительского
			$main_variant_id = $this->model_product_get->checkIfProductIsVariant($product_id);
			if ($main_variant_id && $main_variant_id != $product_id){
				echoLine('[editFullProduct] Копируем описание с основного товара: ' . $main_variant_id);
				$this->model_product_edit->editProductDescriptions($product_id, $this->model_product_get->getProductDescriptions($main_variant_id));					
			} else {
				$this->parseProductDescriptions($product_id, $product);
			}

			//Цвет, отдельным полем
			$this->parseProductColor($product_id, $product);

			//Материал, отдельным полем
			$this->parseProductMaterial($product_id, $product);
			
			//Картинки
			$this->parseProductImages($product_id, $product);			

			//Видео
			$this->parseProductVideos($product_id, $product);			

			//Атрибуты, фичер баллетс и спецификации
			$this->parseProductAttributes($product_id, $product, $main_variant_id);
			
			//Размеры, готовая функция из InfoUpdater
			$this->registry->get('rainforestAmazon')->infoUpdater->parseAndUpdateProductDimensions($product);

			//Related Products
			$this->parseProductRelatedProducts($product_id, $product);
						
			//Similar Products
			$this->parseProductSimilarProducts($product_id, $product);

			//Sponsored Products
			$this->parseProductSponsoredProducts($product_id, $product);

			//Parse product categories if not set
			$this->parseProductCategories($product_id, $product);

			//Варианты
			$this->model_product_edit->resetUnexsistentVariants();
			$this->parseProductVariants($product_id, $product, $variants);			
			
			$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);
			$this->registry->get('rainforestAmazon')->infoUpdater->setProductIsFilledFromAmazon($product_id);
			$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmznData([
						'product_id' 	=> $product_id, 
						'asin' 			=> $product['asin'], 
						'json' 			=> json_encode($product)
			], false);
		}
		
		public function editFullProductsAsyncWithNoVariantParser($product_data){
			if ($product_data){
				$total = count($product_data);
				$iterations = ceil($total/\hobotix\RainforestAmazon::productRequestLimits);

				for ($i = 1; $i <= $iterations; $i++){
					$slice = array_slice($product_data, \hobotix\RainforestAmazon::productRequestLimits * ($i-1), \hobotix\RainforestAmazon::productRequestLimits);
					$results = $this->registry->get('rainforestAmazon')->simpleProductParser->getProductByASINS($slice);

					foreach ($results as $product_id => $result){
						$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);

						if ($result){
							echoLine('[EditFullProducts] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);				

							$this->editFullProduct($product_id, $result, false);

						} else {
							echoLine('[EditFullProducts] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);
						}
					}
				}
			}
		}
			
		public function addSimpleProductWithOnlyAsin($data) {			

			if ($this->model_product_get->checkIfAsinIsDeleted($data['asin'])){
				echoLine('[RainforestRetriever] ASIN удален, пропускаем!');				
				return 0;
			}			

			$this->db->query("INSERT INTO product SET 
			model 					= '" . $this->db->escape($data['asin']) . "', 
			asin 					= '" . $this->db->escape($data['asin']) . "', 
			image           		= '" . (!empty($data['image'])?$this->db->escape($data['image']):'') . "', 			
			added_from_amazon 		= '1', 
			main_variant_id     	= '" . (!empty($data['main_variant_id'])?(int)$data['main_variant_id']:0) . "',
			amazon_product_link  	= '" . (!empty($data['amazon_product_link'])?$this->db->escape($data['amazon_product_link']):'') . "',
			amazon_product_image 	= '" . (!empty($data['amazon_product_image'])?$this->db->escape($data['amazon_product_image']):'') . "',
			stock_status_id 		= '" . $this->config->get('config_stock_status_id') . "',
			quantity 				= '0',
			status 					= '0',
			date_added 				= NOW()");
			
			$product_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
			
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['category_id'] . "', main_category = 1");
			
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");

			$product_name_data = [];		
			foreach ($this->registry->get('languages') as $language_code => $language) {

				$name = $this->translateWithCheck($data['name'], $language['code']);

				$translated = false;
				if ($name && $name != atrim($data['name'])){
					$translated = true;
				}

				$product_name_data[$language['language_id']] = [
					'name' 			=> $name,
					'translated' 	=> $translated
				];
				
				$this->model_product_edit->addProductNames($product_id, $product_name_data);
			}
			
			return $product_id;
		}
		
	}	
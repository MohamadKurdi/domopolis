<?php

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
		'Modellnummer' 							=> ['sku'],
		'Artikelnummer' 						=> ['sku'],
		'Herstellerreferenz' 					=> ['sku'],
		'UPC'									=> ['upc'],
		'UWeltweite Artikelidentnummer (GTIN)'	=> ['ean'],
		'GTIN'									=> ['ean'],
		'EAN'									=> ['ean'],
		'Weltweite Artikelidentnummer (GTIN)'	=> ['ean'],				
		'ISBN-10'								=> ['ean'],
		'ISBN-13'								=> ['ean']
	];

	private $mapAmazonToStoreFieldsSpecificationsRev = [
		'ASIN' 					=> ['model']
	];

	private $passAttributesAndSpecifications = [
		'Produktabmessungen',
		'Hersteller',
		'Im Angebot von Amazon.de seit',
		'Marke',
		'Brand',
		'Amazon Bestseller-Rang',
		'Best Sellers Rank',
		'Date First Available',
		'Customer Reviews',
		'Manufacturer contact',
		'Auslaufartikel (Produktion durch Hersteller eingestellt)',
		'Durchschnittliche Kundenbewertung'			
	];

	
	const CLASS_NAME = 'hobotix\\Amazon\\ProductsRetriever';

		//TODO: refactor
	public function getAsinAddQueue(){
		return $this->model_product_get->getAsinAddQueue();
	}	
	
		//TODO: refactor
	public function getProducts(){
		return $this->model_product_get->getProducts();
	}		

		//TODO: refactor
	public function getProductsFromTechCategory(){
		return $this->model_product_get->getProductsFromTechCategory();
	}	
	
		//TODO: refactor
	public function getProductsWithFullDataButNotFullfilled(){
		return $this->model_product_get->getProductsWithFullDataButNotFullfilled();
	}

		//TODO: refactor
	public function getProductsWithFullData($start, $filter_data = []){
		return $this->model_product_get->getProductsWithFullData($start, $filter_data);
	}

		//TODO: refactor
	public function getProductsByAsin($asin){
		return $this->model_product_get->getProductsByAsin($asin);
	}
	
	public function translateWithCheck($text, $language_code, $detect = false){
		$text = atrim($text);

		$real_language_code = $language_code;
		if (!empty($this->mapLanguages[$language_code])){							
			$real_language_code = $this->mapLanguages[$language_code];
		}		

		if ($language_code == $this->config->get('config_rainforest_source_language')){
			$text = $text;	
		} else {
			if ($this->config->get('config_rainforest_enable_translation') && ($this->config->get('config_rainforest_enable_language_' . $real_language_code) || $this->config->get('config_rainforest_enable_language_' . $language_code))){
				$text = $this->translateAdaptor->translate($text, $detect?false:$this->config->get('config_rainforest_source_language'), $real_language_code, true);
			} else {
				$text = '';
			}
		}

		return $text;
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

	public function checkProductManufacturerForExclusion($product_id, $product){
		if (!empty($product['brand'])){
			$product['brand'] = atrim($product['brand']);
			echoLine('[checkProductManufacturerForExclusion] Checking brand for exclusions: ' . $product['brand'], 'i');		

			if ($this->model_product_get->checkIfManufacturerIsExcluded($product['brand'], 0)){
				return true;
			}
		}

		return false;
	}

	public function parseProductManufacturer($product_id, $product){
		if (!empty($product['brand'])){
			$product['brand'] = atrim($product['brand']);

			echoLine('[parseProductManufacturer] Brand: ' . $product['brand'], 'i');			

			$manufacturer_id = $this->model_product_cached_get->getManufacturer($product['brand']);			
			if (!$manufacturer_id){

				if ($this->config->get('config_rainforest_auto_create_manufacturers')){
					echoLine('[editFullProduct] Setting config_rainforest_auto_create_manufacturers = ON, creating brand: ' . $product['brand'], 'w');					
					$manufacturer_id = $this->model_product_edit->addManufacturer($product['brand']);					
				} else {
					echoLine('[editFullProduct] Setting config_rainforest_auto_create_manufacturers = OFF, editing just description', 'w');					
					$manufacturer_id = 0;
				}
			}

			$this->model_product_edit->editProductDescriptionFieldExplicit($product_id, 'manufacturer_name', $product['brand']);
			$this->model_product_edit->editProductFields($product_id, [['name' => 'manufacturer_id', 'type' => 'int', 'value' => $manufacturer_id]]);
		}				
	}

	public function parseProductDescriptions($product_id, $product, $return = false){			
		if (!empty($product['description'])){
			$product_description = [];			

			$product['description'] = atrim($product['description']);

			if ((int)$this->config->get('config_rainforest_description_symbol_limit') > 0){				
				$this->model_product_edit->backupFullDescription($product_id, ['description_full' => $product['description']], $this->config->get('config_rainforest_source_language_id'));

				if (mb_strlen($product['description']) >= ((int)$this->config->get('config_rainforest_description_symbol_limit') + ((int)$this->config->get('config_rainforest_description_symbol_limit')/10))){			
					$product['description'] = limit_text_by_sentences($product['description'], (int)$this->config->get('config_rainforest_description_symbol_limit'));
					echoLine('[parseProductDescriptions] Truncated description to ' . $this->config->get('config_rainforest_description_symbol_limit') . ' symbols!', 'w');
				}
			}

			foreach ($this->registry->get('languages') as $language_code => $language) {
				$description = $this->translateWithCheck($product['description'], $language_code);				
				
				$product_description[$language['language_id']] = [
					'description' => $description,
					'translated'  => (int)(mb_strlen($description)>0)					
				];
			}

			if ($return){
				return $product_description;
			}
			
			$this->model_product_edit->editProductDescriptions($product_id, $product_description);
		}
	}

	public function parseProductNames($product_id, $product, $return = false){
		if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_enable_shorten_names') && $this->config->get('config_rainforest_short_names_with_openai') && $this->config->get('config_openai_enable_shorten_names_before_translation')){
			$data['name'] = $this->registry->get('openaiAdaptor')->shortenName($product['name'], $this->config->get('config_rainforest_source_language'));
		}

		$product_name_data = [];		
		foreach ($this->registry->get('languages') as $language_code => $language) {
			$name = $this->translateWithCheck($product['name'], $language['code']);

			$translated = false;
			if ($name && $name != atrim($product['name'])){
				$translated = true;
			}

			$product_name_data[$language['language_id']] = [
				'name' 			=> $name,
				'short_name_d'  => '',
				'translated' 	=> $translated
			];

			if ($language['code'] != $this->config->get('config_rainforest_source_language') && $name){
				$product_name_data[$language['language_id']]['name'] = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductName($name);
			}

			if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_enable_shorten_names') && $this->config->get('config_rainforest_short_names_with_openai') && $this->config->get('config_openai_enable_shorten_names_after_translation')){
				$product_name_data[$language['language_id']]['name'] = $this->registry->get('openaiAdaptor')->shortenName($product_name_data[$language['language_id']]['name'], $language['code']);
			}

			if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_enable_export_names') && $this->config->get('config_rainforest_export_names_with_openai')){
				$product_name_data[$language['language_id']]['short_name_d'] = $this->registry->get('openaiAdaptor')->exportName($product_name_data[$language['language_id']]['name'], $language['code']);
			}
		}

		if ($return){
			return $product_name_data;
		}

		$this->model_product_edit->addProductNames($product_id, $product_name_data);
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

				$sort_order++;
			}

			if ($videos){
				$this->model_product_edit->editProductVideos($product_id, $videos);
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

	public function parseProductColor($product_id, $product, $return = false){

		if (!empty($product['color'])){
			$product_color = [];			
			foreach ($this->registry->get('languages') as $language_code => $language) {
				$product_color[$language['language_id']] = [
					'color' => $this->translateWithCheck($product['color'], $language_code)
				];
			}

			if ($return){
				return $product_color;
			}
			
			$this->model_product_edit->editProductColor($product_id, $product_color);
		}
	}

	public function parseProductMaterial($product_id, $product, $return = false){
		if (!empty($product['material'])){
			$product_material = [];			
			foreach ($this->registry->get('languages') as $language_code => $language) {
				$product_material[$language['language_id']] = [
					'material' => $this->translateWithCheck($product['material'], $language_code)
				];
			}

			if ($return){
				return $product_material;
			}
			
			$this->model_product_edit->editProductMaterial($product_id, $product_material);
		}
	}

	public function passProductFeatureBullets($product_id, $product, $return = false){
		$product_feature_bullets = [];

		if (!empty($product['feature_bullets'])){
			foreach ($product['feature_bullets'] as $feature_bullet){
				$product_feature_bullet_description = [];
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$text = $this->translateWithCheck($feature_bullet, $language_code);
					$text = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductAttributeText($text);

					$product_feature_bullet_description[$language['language_id']] = [
						'text' => $text
					];
				}

				$product_feature_bullets[] = $product_feature_bullet_description;
			}

			if ($return){
				return $product_feature_bullets;
			}
		}
	}

	public function passProductAttributes($product_id, $product, $return = false){
		$product_attributes = [];

		if (!empty($product['attributes']) || !empty($product['specifications'])){
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

			foreach ($this->passAttributesAndSpecifications as $pass){
				if (!empty($mergedProductAttributes[clean_string($pass)])){
					unset($mergedProductAttributes[clean_string($pass)]);
				}
			}

			foreach ($mergedProductAttributes as $attribute){
				echoLine('[ProductsRetriever::parseProductAttributes] Attributes: ' . $attribute['name'], 'i');
				$attribute['name'] = atrim($attribute['name']);
				$attribute['value'] = atrim($attribute['value']);	

				$names = [];
				foreach ($this->registry->get('languages') as $language_code => $language) {						
					$names[$language['language_id']] = $this->translateWithCheck($attribute['name'], $language_code);					
				}

				$values = [];

				foreach ($this->registry->get('languages') as $language_code => $language) {	
					$text = $this->translateWithCheck($attribute['value'], $language_code);
					$text = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductAttributeText($text);

					$values[$language['language_id']] = $text;					
				}

				$product_attributes[] = [
					'names' 	=> $names,
					'values' 	=> $values
				];
			}

			if ($return){
				return $product_attributes;
			}
		}
	}

	public function parseProductAttributes($product_id, $product, $main_variant_id = false){
		$product_attribute = [];			
		if (!empty($product['feature_bullets']) && ($main_variant_id === false || $main_variant_id == $product_id)){				
			$feature_bullets_counter = 1;
			foreach ($product['feature_bullets'] as $feature_bullet){
				echoLine('[ProductsRetriever::parseProductAttributes] Features: ' . $feature_bullets_counter, 'i');

				$attribute_id = $this->model_product_cached_get->getAttribute($this->config->get('config_special_attr_name') . ' ' . $feature_bullets_counter);
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
					$attribute_id = $this->model_product_edit->addAttribute($attribute);
				}

				$product_attribute_description = [];
				foreach ($this->registry->get('languages') as $language_code => $language) {
					$text = $this->translateWithCheck($feature_bullet, $language_code);
					$text = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductAttributeText($text);

					$product_attribute_description[$language['language_id']] = [
						'text' => $text
					];
				}

				$product_attribute[] = [
					'attribute_id' 					=> $attribute_id,
					'product_attribute_description' => $product_attribute_description
				];

				$feature_bullets_counter++;
			}
			
		} elseif ($main_variant_id && $main_variant_id != $product_id){
			echoLine('[ProductsRetriever::parseProductAttributes] Copying feature_bullets from main product: ' . $main_variant_id, 'i');
			$product_attribute = $this->model_product_get->getProductFeatureBullets($main_variant_id);
		}


		if (!empty($product['attributes']) || !empty($product['specifications'])){
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

			foreach ($this->passAttributesAndSpecifications as $pass){
				if (!empty($mergedProductAttributes[clean_string($pass)])){
					unset($mergedProductAttributes[clean_string($pass)]);
				}
			}

			foreach ($mergedProductAttributes as $attribute){
				echoLine('[ProductsRetriever::parseProductAttributes] Attributes: ' . $attribute['name'], 'i');
				$attribute['name'] = atrim($attribute['name']);
				$attribute['value'] = atrim($attribute['value']);

                if (!preg_match('/[a-zA-Zа-яА-Я0-9]/u', $attribute['name'])){
                    echoLine('[ProductsRetriever::parseProductAttributes] Found bad name: ' . $attribute['name'] . ', skipping', 'e');
                }

                if (!preg_match('/[a-zA-Zа-яА-Я0-9]/u', $attribute['value'])){
                    echoLine('[ProductsRetriever::parseProductAttributes] Found bad value: ' . $attribute['value'] . ', skipping', 'e');
                }

				$mappedAttribute = false;
				if (!empty($this->mapAmazonToStoreFieldsSpecifications[clean_string($attribute['name'])])){
					$mappedAttribute = true;

					foreach ($this->mapAmazonToStoreFieldsSpecifications[clean_string($attribute['name'])] as $fieldToChange){
						echoLine('[ProductsRetriever::parseProductAttributes] Attribute ' . $attribute['name'] . ' -> ' . $fieldToChange, 'w');
						$this->model_product_edit->editProductFields($product_id, [['name' => $fieldToChange, 'type' => 'varchar', 'value' => $attribute['value']]]);
					}
				} 

				if (!empty($this->mapAmazonToStoreFieldsSpecificationsRev[clean_string($attribute['name'])])){
					$mappedAttribute = true;

					foreach ($this->mapAmazonToStoreFieldsSpecificationsRev[clean_string($attribute['name'])] as $fieldToChange){
						echoLine('[ProductsRetriever::parseProductAttributes] Attribute ' . $attribute['name'] . ' -> ' . $fieldToChange, 'w');
						$this->model_product_edit->editProductFields($product_id, [['name' => $fieldToChange, 'type' => 'varchar', 'value' => strrev($attribute['value'])]]);
					}
				} 

				if (!$mappedAttribute) {
					echoLine('[ProductsRetriever::parseProductAttributes] Search for attribute: ' . $attribute['name'], 'i');

					$attribute_id = $this->model_product_cached_get->getAttribute($attribute['name']);

                    if (!$attribute_id){
                        $translated_attribute_name = $this->translateWithCheck($attribute['name'], $this->config->get('config_language'));

                        if ($translated_attribute_name){
                            echoLine('[ProductsRetriever::parseProductAttributes] Attribute not found, trying to find translated: ' . $translated_attribute_name, 'w');
                            $attribute_id = $this->model_product_cached_get->getAttributeTranslated($translated_attribute_name);
                        }

                        if ($attribute_id){
                            echoLine('[ProductsRetriever::parseProductAttributes] Found translated attribute ' . $translated_attribute_name .  ' with id ' . $attribute_id, 'w');
                        }
                    } else {
                        echoLine('[ProductsRetriever::parseProductAttributes] Found attribute by native name ' . $attribute['name'] .  ' with id ' . $attribute_id, 'w');
                    }

					if (!$attribute_id){
						echoLine('[ProductsRetriever::parseProductAttributes] Attribute not found: ' . $attribute['name'], 'e');

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

						$attribute_id = $this->model_product_edit->addAttribute($attribute_data);
					}

					$product_attribute_description = [];

					foreach ($this->registry->get('languages') as $language_code => $language) {	
						$text = $this->translateWithCheck($attribute['value'], $language_code);
						$text = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductAttributeText($text);

						$product_attribute_description[$language['language_id']] = [
							'text' => $text
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
			
		}
	}

	public function parseDimensionAttributes($product_id, $product){		
	}

	public function parseProductRelatedProducts($product_id, $product){
		if ($this->config->get('config_rainforest_enable_related_parsing')){
			if (!empty($product['frequently_bought_together']) && !empty($product['frequently_bought_together']['products'])){
				$product_related = [];
				foreach ($product['frequently_bought_together']['products'] as $bought_together){
					if ($related = $this->getProductsByAsin($bought_together['asin'])){

						foreach ($related as $related_id){
							echoLine('[ProductsRetriever::parseProductRelatedProducts] Related product: ' . $related_id, 'i');

							$product_related[] = $related_id;
						}

					} else {

						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_related_adding')){

							echoLine('[ProductsRetriever::parseProductRelatedProducts] New Related product: ' . $bought_together['asin'] . ' ' . $bought_together['title'], 's');

							$new_related_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $bought_together['asin'],
								'amazon_best_price' => (!empty($bought_together['price']))?$bought_together['price']['value']:'0', 
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $bought_together['title'], 
								'image' 			=> $this->getImage($bought_together['image']), 
								'added_from_amazon' => 1
							]);							

							$product_related[] = $new_related_id;

						}

					}
				}

				if ($product_related){
					$this->model_product_edit->editProductRelated($product_id, $product_related);
				}
			}
		}
	}

	public function parseProductSponsoredProducts($product_id, $product){			
		if ($this->config->get('config_rainforest_enable_sponsored_parsing')){
			if (!empty($product['sponsored_products'])){
				$product_sponsored = [];
				foreach ($product['sponsored_products'] as $sponsored_product){
					if ($sponsored = $this->getProductsByAsin($sponsored_product['asin'])){

						foreach ($sponsored as $sponsored_id){
							echoLine('[parseProductSponsoredProducts] Sponsored: ' . $sponsored_id);

							$product_sponsored[] = $sponsored_id;
						}

					} else {

						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_sponsored_adding')){

							echoLine('[parseProductSponsoredProducts] Новый Sponsored товар: ' . $sponsored_product['asin'] . ' ' . $sponsored_product['title']);

							$new_sponsored_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $sponsored_product['asin'],
								'amazon_best_price' => (!empty($sponsored_product['price']))?$sponsored_product['price']['value']:'0',
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
	}

	public function parseProductSimilarProducts($product_id, $product){		
		if ($this->config->get('config_rainforest_enable_compare_with_similar_parsing')){	
			if (!empty($product['compare_with_similar'])){
				$product_similar = [];
				foreach ($product['compare_with_similar'] as $compare_with_similar){
					if ($similar = $this->getProductsByAsin($compare_with_similar['asin'])){

						foreach ($similar as $similar_id){
							echoLine('[parseProductSimilarProducts] Similar товар: ' . $similar_id);

							$product_similar[] = $similar_id;
						}

					} else {
						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_compare_with_similar_adding')){
							echoLine('[parseProductSimilarProducts] Новый Similar товар: ' . $compare_with_similar['asin'] . ' ' . $compare_with_similar['title']);

							$new_similar_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $compare_with_similar['asin'],
								'amazon_best_price' => (!empty($compare_with_similar['price']))?$compare_with_similar['price']['value']:'0',
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
	}

	public function parseProductSimilarToConsiderProducts($product_id, $product){
		if ($this->config->get('config_rainforest_enable_similar_to_consider_parsing')){
			if (!empty($product['similar_to_consider'])){
				$product_similar = [];
				foreach ($product['similar_to_consider'] as $similar_to_consider){
					if ($similar = $this->getProductsByAsin($similar_to_consider['asin'])){

						foreach ($similar as $similar_id){
							echoLine('[parseProductSimilarToConsiderProducts] SimilarToConsider товар: ' . $similar_id);

							$product_similar[] = $similar_id;
						}

					} else {
						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_similar_to_consider_adding')){
							echoLine('[parseProductSimilarToConsiderProducts] Новый SimilarToConsider товар: ' . $similar_to_consider['asin'] . ' ' . $similar_to_consider['title']);

							$new_similar_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $similar_to_consider['asin'],
								'amazon_best_price' => (!empty($similar_to_consider['price']))?$similar_to_consider['price']['value']:'0',
								'category_id' 		=> $this->model_product_get->getCurrentProductCategory($product_id), 
								'name' 				=> $similar_to_consider['title'], 
								'image' 			=> $this->getImage($similar_to_consider['image']), 
								'added_from_amazon' => 1
							]);							

							$product_similar[] = $new_similar_id;

						}
					}
				}

				if ($product_similar){
					$this->model_product_edit->editProductSimilarToConsider($product_id, $product_similar);
				}
			}
		}
	}

	public function parseProductViewToPurchaseProducts($product_id, $product){
		if ($this->config->get('config_rainforest_enable_view_to_purchase_parsing')){
			if (!empty($product['view_to_purchase'])){
				$product_view_to_purchase = [];
				foreach ($product['view_to_purchase'] as $view_to_purchase_product){
					if ($view_to_purchase = $this->getProductsByAsin($view_to_purchase_product['asin'])){

						foreach ($view_to_purchase as $view_to_purchase_id){
							echoLine('[parseProductViewToPurchaseProducts] ViewToPurchase товар: ' . $view_to_purchase_id);

							$product_view_to_purchase[] = $view_to_purchase_id;
						}

					} else {
						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_view_to_purchase_adding')){
							echoLine('[parseProductViewToPurchaseProducts] Новый ViewToPurchase товар: ' . $view_to_purchase_product['asin'] . ' ' . $view_to_purchase_product['title']);

							$new_view_to_purchase_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $view_to_purchase_product['asin'],
								'amazon_best_price' => (!empty($view_to_purchase_product['price']))?$view_to_purchase_product['price']['value']:'0',
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $view_to_purchase_product['title'], 
								'image' 			=> $this->getImage($view_to_purchase_product['image']), 
								'added_from_amazon' => 1
							]);							

							$product_view_to_purchase[] = $new_view_to_purchase_id;

						}
					}
				}

				if ($product_view_to_purchase){
					$this->model_product_edit->editProductViewToPurchase($product_id, $product_view_to_purchase);
				}
			}
		}
	}

	public function parseProductAlsoViewedProducts($product_id, $product){
		if ($this->config->get('config_rainforest_enable_also_viewed_parsing')){
			if (!empty($product['also_viewed'])){
				$product_also_viewed = [];
				foreach ($product['also_viewed'] as $also_viewed_product){
					if ($also_viewed = $this->getProductsByAsin($also_viewed_product['asin'])){

						foreach ($also_viewed as $also_viewed_id){
							echoLine('[parseProductAlsoViewedProducts] AlsoViewed товар: ' . $also_viewed_id);

							$product_also_viewed[] = $also_viewed_id;
						}

					} else {
						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_also_viewed_adding')){
							echoLine('[parseProductAlsoViewedProducts] Новый AlsoViewed товар: ' . $also_viewed_product['asin'] . ' ' . $also_viewed_product['title']);

							$new_also_viewed_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $also_viewed_product['asin'],
								'amazon_best_price' => (!empty($also_viewed_product['price']))?$also_viewed_product['price']['value']:'0',
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $also_viewed_product['title'], 
								'image' 			=> $this->getImage($also_viewed_product['image']), 
								'added_from_amazon' => 1
							]);							

							$product_also_viewed[] = $new_also_viewed_id;

						}
					}
				}

				if ($product_also_viewed){
					$this->model_product_edit->editProductAlsoViewed($product_id, $product_also_viewed);
				}
			}
		}
	}

	public function parseProductAlsoBoughtProducts($product_id, $product){
		if ($this->config->get('config_rainforest_enable_also_bought_parsing')){
			if (!empty($product['also_bought'])){
				$product_also_bought = [];
				foreach ($product['also_bought'] as $also_bought_product){
					if ($also_bought = $this->getProductsByAsin($also_bought_product['asin'])){

						foreach ($also_bought as $also_bought_id){
							echoLine('[parseProductAlsoBoughtProducts] AlsoBought товар: ' . $also_bought_id);

							$product_also_bought[] = $also_bought_id;
						}

					} else {
						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_also_bought_adding')){
							echoLine('[parseProductAlsoBoughtProducts] Новый AlsoBought товар: ' . $also_bought_product['asin'] . ' ' . $also_bought_product['title']);

							$new_also_bought_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $also_bought_product['asin'],
								'amazon_best_price' => (!empty($also_bought_product['price']))?$also_bought_product['price']['value']:'0',
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $also_bought_product['title'], 
								'image' 			=> $this->getImage($also_bought_product['image']), 
								'added_from_amazon' => 1
							]);							

							$product_also_bought[] = $new_also_bought_id;

						}
					}
				}

				if ($product_also_bought){
					$this->model_product_edit->editProductAlsoBought($product_id, $product_also_bought);
				}
			}
		}
	}

	public function parseProductShopByLookProducts($product_id, $product){
		if ($this->config->get('config_rainforest_enable_shop_by_look_parsing')){
			if (!empty($product['shop_by_look']) && !empty($product['shop_by_look']['items'])){
				$product_shop_by_look = [];
				foreach ($product['shop_by_look']['items'] as $shop_by_look_product){
					if ($shop_by_look = $this->getProductsByAsin($shop_by_look_product['asin'])){

						foreach ($shop_by_look as $shop_by_look_id){
							echoLine('[parseProductShopByLookProducts] ShopByLook товар: ' . $shop_by_look_id);

							$product_shop_by_look[] = $shop_by_look_id;
						}

					} else {

						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_shop_by_look_adding')){

							echoLine('[parseProductShopByLookProducts] Новый ShopByLook товар: ' . $shop_by_look_product['asin'] . ' ' . $shop_by_look_product['title']);

							$new_shop_by_look_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $shop_by_look_product['asin'],
								'amazon_best_price' => (!empty($shop_by_look_product['price']))?$shop_by_look_product['price']['value']:'0', 
								'category_id' 		=> $this->config->get('config_rainforest_default_technical_category_id'), 
								'name' 				=> $shop_by_look_product['title'], 
								'image' 			=> $this->getImage($shop_by_look_product['image']), 
								'added_from_amazon' => 1
							]);							

							$product_shop_by_look[] = $new_shop_by_look_id;

						}

					}
				}

				if ($product_shop_by_look){
					$this->model_product_edit->editProductShopByLook($product_id, $product_shop_by_look);
				}
			}
		}
	}

	public function parseProductCategories($product_id, $product){
		if (!empty($product['categories'])){
			$current_category_id = $this->model_product_get->getCurrentProductCategory($product_id);
			echoLine('[ProductsRetriever::parseProductCategories] Current product category is: ' . $current_category_id, 's');

			$check_category = false;
			if ($this->config->get('config_rainforest_check_technical_category_id') && ($current_category_id == $this->config->get('config_rainforest_default_technical_category_id'))){
				$check_category = true;
			}

			if ($this->config->get('config_rainforest_check_unknown_category_id') && ($current_category_id == $this->config->get('config_rainforest_default_unknown_category_id'))){
				$check_category = true;
			}

			if ($check_category){
				echoLine('[ProductsRetriever::parseProductCategories] Product in Technical or Unknown category, continuing', 's');

				$name 				= $product['categories'][count($product['categories']) - 1]['name'];
				$amazon_category_id = $product['categories'][count($product['categories']) - 1]['category_id'];
				$path 				= $product['categories_flat'];

				if ($category_id = $this->model_product_cached_get->getCategory(atrim($name), atrim($path), atrim($amazon_category_id))){

					echoLine('[ProductsRetriever::parseProductCategories] Found category: ' . $path . ': ' . $category_id, 's');
					$this->model_product_edit->editProductCategory($product_id, [$category_id]);

				} else {

					if (!empty($product['brand']) && !empty($product['brand_logic'])){
						$temprorary_category = $product['brand'] . ' TEMPRORARY';

						echoLine('[ProductsRetriever::parseProductCategories] Brand Logic is ON: ' . $temprorary_category, 'i');

						if ($category_id = $this->model_product_cached_get->checkIfTempCategoryExists(atrim($temprorary_category))){						
							echoLine('[ProductsRetriever::parseProductCategories] Found BL category: ' . $temprorary_category . ': ' . $category_id, 's');							
							$this->model_product_edit->editProductCategory($product_id, [$category_id]);
						} else {
							echoLine('[ProductsRetriever::parseProductCategories] Not Found BL category: ' . $temprorary_category, 'e');

							$category_data = [
								'category_description' => []
							];

							foreach ($this->registry->get('languages') as $language){
								$category_data['category_description'][$language['language_id']]['name'] = $temprorary_category;
							}	

							$category_id = $this->model_product_edit->addCategoryToTechSimple($category_data);

							echoLine('[ProductsRetriever::parseProductCategories] Created BL category: ' . $temprorary_category . ': ' . $category_id, 'i');
							$this->model_product_edit->editProductCategory($product_id, [$category_id]);

						}					
					} else {
						echoLine('[ProductsRetriever::parseProductCategories] Could not found category: ' . $name . ', setting as unknown', 'e');
						$this->model_product_edit->editProductCategory($product_id, [$this->config->get('config_rainforest_default_unknown_category_id')]);
					}
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
					return $variant['dimensions'];
				}
			}
		}
	}

	public function getVariantDimensionsByAsin($variants, $asin){
		if (!empty($variants)){
			foreach ($variants as $variant){
				if ($variant['asin'] == $asin){
					return $variant['dimensions'];
				}
			}
		}
	}

	public function getVariantImageByAsin($variants, $asin){
		if (!empty($variants)){
			foreach ($variants as $variant){
				if ($variant['asin'] == $asin){
					if (!empty($variant['main_image'])){
						return $variant['main_image'];
					}					
				}
			}
		}

		return false;
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

	public function checkProductsVariants($product_id, $product){
		if ($this->config->get('config_rainforest_skip_variants') && !empty($product['variants'])){
			return (count($product['variants']) <= (int)$this->config->get('config_rainforest_skip_variants'));
		}

		return true;
	}

	public function parseProductVariants($product_id, $product, $do_adding_new_variants = true, $this_is_queue = false){
		$this->parseProductVariantDimensions($product_id, $product);

		if ($this->config->get('config_rainforest_delay_queue_variants') && !$this_is_queue){
			if (!empty($product['variants']) && $do_adding_new_variants){
				$this->model_product_edit->addProductToAmazonVariantsQueue($product_id, $product['asin']);
				echoLine('[ProductsRetriever::parseProductVariants] Skipped adding variants, added to variants queue!', 's');
			}
		} else {			
			if (!empty($product['variants']) && $do_adding_new_variants){	
				echoLine('[ProductsRetriever::parseProductVariants] Now parsing variants!', 's');

				//У нас 2 варианта, либо текущий товар - основной, либо текущий - не основной
				//В любом случае в табличке вариантов уже есть записи, они добавлены либо из текущего товара, либо из не_текущего, в любом случае мы отбираем все варианты
				//И да, список всегда будет одинаковый, потому что записан, сука
				$variants = $this->model_product_get->getOtherProductVariantsByAsin($product['asin']);
				echoLine('[ProductsRetriever::parseProductVariants] Got ' . count($variants) . ' for asin ' . $product['asin'], 'i');
				$new_product_data = [];
				foreach ($variants as $variant){
					//Товар уже существует
					if ($variant['product_id']){
						//Товар существует и уже полностью обновлен, записан, и ваще у него всё хорошо
						if ($variant['filled_from_amazon']){
							//Просто обновляем привязку родителя по асину
							echoLine('[ProductsRetriever::parseProductVariants] Updating parent by asin:' . $variant['asin'] . ':' . $variant['main_asin'], 'i');
							$this->model_product_edit->updateProductMainVariantIdByParentAsin($variant['product_id'], $variant['main_asin']);
						} else {
							//Отправляем товар на полную загрузку
							echoLine('[ProductsRetriever::parseProductVariants] Filling variant product:' . $variant['asin'], 'i');
							$new_product_data[] = [
								'product_id' => $variant['product_id'],
								'asin'		 => $variant['asin']
							];

							echoLine('[ProductsRetriever::parseProductVariants] Updating parent by asin:' . $variant['asin'] . ':' . $variant['main_asin'], 'i');
							$this->model_product_edit->updateProductMainVariantIdByParentAsin($variant['product_id'], $variant['main_asin']);
						}
					} else {

						if (!$this->getProductsByAsin($variant['asin'])){
							echoLine('[ProductsRetriever::parseProductVariants] New variant:' . $variant['asin'], 'w');

							$new_product_name = $this->trimProductNameWithoutVariant($product['title'], $this->getCurrentVariantDimensions($product['variants']), $this->getVariantDimensionsByAsin($product['variants'], $variant['asin']));
							echoLine('[ProductsRetriever::parseProductVariants] New variant name: ' . $new_product_name, 'i');	

							$new_product_id = $this->addSimpleProductWithOnlyAsin([
								'asin' 				=> $variant['asin'], 
								'category_id' 		=> $this->model_product_get->getCurrentProductCategory($product_id), 
								'main_variant_id'	=> $product_id,
								'amazon_best_price' => (float)$this->model_product_get->getProductPriceByAsin($product['asin']), 
								'status'  			=> 1,
								'name' 				=> $new_product_name,
								'image' 			=> $this->getImage($this->getVariantImageByAsin($product['variants'], $variant['asin'])), 
								'added_from_amazon' => 1
							]);
						}	

						echoLine('[ProductsRetriever::parseProductVariants] Updating parent by asin: ' . $variant['asin'] . ':' . $variant['main_asin'], 'i');
						$this->model_product_edit->updateProductMainVariantIdByParentAsin($new_product_id, $variant['main_asin']);					


						$new_product_data[] = [
							'product_id' 	=> $new_product_id,
							'asin' 			=> $variant['asin']
						];						
					}
				}

				if ($new_product_data){
					$this->editFullProductsAsyncWithNoVariantParser($new_product_data);
				}
			}
		}
	}

    private static function parseBadPrice($price){
        if (empty($price['currency']) && empty($price['value'])){
            if (!empty($price['raw'])){
                /* €21,9921,99€ */
                if (preg_match('/€{1}(\d+,?\d+)(\d+,?\d+)€{1}/', $price['raw'], $matches) === 1){
                    $price['value'] = str_replace(['€', ','], ['', '.'], $price['raw']);
                    $price['value'] = (float)substr($price['value'], 0, floor(mb_strlen($price['value']) / 2));
                    $price['currency'] = 'EUR';

                    echoLine('[ProductRetriever::parseBadPrice] Found bad formatted price: ' . $price['raw'] . ', parsed it to ' . $price['value'], 'd');
                }
            }
        }

        return $price;
    }

	public function parseProductBuyBoxWinner($product_id, $product){
		if (!empty($product['buybox_winner'])){

            $product['buybox_winner']['price'] = self::parseBadPrice($product['buybox_winner']['price']);

			if (!empty($product['buybox_winner']['price']) && !empty($product['buybox_winner']['price']['currency']) && !empty($product['buybox_winner']['price']['value']) && $product['buybox_winner']['price']['currency'] == 'EUR'){
				$this->model_product_edit->editProductFields($product_id, [['name' => 'amazon_best_price', 'type' => 'decimal', 'value' => $product['buybox_winner']['price']['value']]]);
				$this->registry->get('rainforestAmazon')->offersParser->PriceLogic->updateProductPrices($product['asin'], $product['buybox_winner']['price']['value'], true);
			}
		}
	}

	public function parseProductLink($product_id, $product){
		if (!empty($product['link'])){
			$this->model_product_edit->editProductFields($product_id, [['name' => 'amazon_product_link', 'type' => 'varchar', 'value' => $product['link']]]);
		}
	}

	public function parseProductRating($product_id, $product){
		if (!empty($product['rating'])){
			echoLine('[parseProductRating] Рейтинг ' . $product['rating']);
			$this->model_product_edit->editProductFields($product_id, [['name' => 'amzn_rating', 'type' => 'decimal', 'value' => $product['rating']]]);
		}
	}

	public function parseProductTopReviews($product_id, $product){
		if ($this->config->get('config_rainforest_enable_review_adding')){
			if (!empty($product['top_reviews'])){
				$counter = 0;
				foreach ($product['top_reviews'] as $review){						

					if (mb_strlen($review['body']) > (int)$this->config->get('config_rainforest_max_review_length')){
						echoLine('[ProductsRetriever::parseProductTopReviews] length exceeded ' . mb_strlen($review['body']) . ', skip', 'e');
						continue;
					}

					if ($review['rating'] < (int)$this->config->get('config_rainforest_min_review_rating')){
						echoLine('[ProductsRetriever::parseProductTopReviews] rating exceeded ' . $review['rating'] . ', skip', 'e');
						continue;
					}

					$counter++;
					if ($counter >= (int)$this->config->get('config_rainforest_max_review_per_product')){
						break;
					}

					$review_description = [];					
					$author = $this->db->ncquery("SELECT firstname FROM customer WHERE firstname <> '' ORDER BY RAND() LIMIT 1")->row['firstname'];
					echoLine('[ProductsRetriever::parseProductTopReviews] Author: ' . $author, 'i');

					foreach ($this->registry->get('languages') as $language_code => $language) {
						$review['body'] = atrim($review['body']);						

						$text = $this->translateWithCheck($review['body'], $language_code, true);
						$text = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductReview($text);

						$review_description[$language['language_id']] = [
							'text' => $text,
						];
					}

					$review_data = [
						'author'				=> $author,
						'product_id'			=> $product_id,	
						'text'					=> $review_description[$this->config->get('config_language_id')]['text'],	
						'rating'				=> $review['rating'],
						'date_added'			=> date('Y-m-d H:i:s', strtotime($review['date']['utc'])),
						'review_description' 	=> $review_description
					];

					$this->model_product_edit->addReview($review_data);
				}										
			}

			$this->model_product_edit->setProductReviewsParsed($product_id);
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

	public function fixEmptySKU($product_id){
		$this->db->query("UPDATE product SET sku = model WHERE sku = '' AND product_id = '" . (int)$product_id . "'");			
	}

	public function editFullProductVariants($product_id, $product){
		$this->parseProductVariants($product_id, $product, true, true);
	}

	public function passFullProduct($asin, $rfProduct){
		$result = [];

		$rfProduct['name'] 	= $rfProduct['title'];

		if ($this->config->get('config_rainforest_external_enable_names')){
			$result['names']		= $this->parseProductNames(false, $rfProduct, true);
		} else {
			$result['names']		= ['config_rainforest_external_enable_names' => false];
		}

		if ($this->config->get('config_rainforest_external_enable_dimensions')){
			$result['dimensions']  	= $this->registry->get('rainforestAmazon')->infoUpdater->parseAndReturnProductDimensions($rfProduct);
		} else {
			$result['dimensions'] 	= ['config_rainforest_external_enable_dimensions' => false];
		}

		if ($this->config->get('config_rainforest_external_enable_color')){
			$result['color'] 		= $this->parseProductColor(false, $rfProduct, true);
		} else {
			$result['color'] 		= ['config_rainforest_external_enable_color' => false];
		}

		if ($this->config->get('config_rainforest_external_enable_material')){
			$result['material'] 	= $this->parseProductMaterial(false, $rfProduct, true);
		} else {
			$result['material'] 	= ['config_rainforest_external_enable_material' => false];
		}

		if ($this->config->get('config_rainforest_external_enable_descriptions')){
			$result['descriptions']	= $this->parseProductDescriptions(false, $rfProduct, true);
		} else {
			$result['descriptions'] 	= ['config_rainforest_external_enable_descriptions' => false];
		}

		if ($this->config->get('config_rainforest_external_enable_features')){
			$result['feature_bullets'] 	= $this->passProductFeatureBullets(false, $rfProduct, true);
		} else {
			$result['feature_bullets'] 	= ['config_rainforest_external_enable_features' => false];
		}
		
		if ($this->config->get('config_rainforest_external_enable_attributes')){						
			$result['attributes'] 	= $this->passProductAttributes(false, $rfProduct, true);
		} else {
			$result['attributes'] 	= ['config_rainforest_external_enable_attributes' => false];
		}
	
		
		return $result;
	}
	
	public function editFullProduct($product_id, $product, $do_adding_new_variants = true){	
		$this->translateAdaptor->setDebug(false);

		if ($this->checkProductManufacturerForExclusion($product_id, $product)){
			echoLine('[editFullProduct] Manufacturer is excluded, deleting product!', 'e');
			$this->model_product_edit->deleteASINFromQueue($product['asin']);	
			$this->model_product_edit->deleteProduct($product_id, $product);
		}

		if (!$this->checkProductsVariants($product_id, $product)){
			echoLine('[editFullProduct] Product has ' . count($product['variants']) . ' variants, skipping and deleting product!', 'e');
			$this->model_product_edit->deleteASINFromQueue($product['asin']);	
			$this->model_product_edit->deleteProduct($product_id, $product);
			return;
		} else {
			$this->model_product_edit->setProductVariants($product);
		}			

			//Ссылка
		$this->parseProductLink($product_id, $product);

			//Рейтинг
		$this->parseProductRating($product_id, $product);			

			//Бренд
		$this->parseProductManufacturer($product_id, $product);
		
			//Описание
			//Если этот товар - вариант другого товара, то описание мы копируем тупо с родительского
			//Рефактор логики 02.07.2022, теперь мы всегда смотрим на заполненную табличку асинов
			//UPD! Это делается только в том случае, если основной товар уже заполнен! Мы можем по какой-то причине начать с товара - варианта
		$main_variant = $this->model_product_get->checkIfProductIsVariantWithFilledParent($product['asin']);					
		if ($main_variant){
			if ($main_variant['main_variant_id'] && $main_variant['main_variant_id'] != $product_id){
				echoLine('[parseProductVariants] Updating parent by asin:' . $product_id . ':' . $main_variant['main_variant_id'], 'i');
				$this->model_product_edit->updateProductMainVariantId($product_id, $main_variant['main_variant_id']);

				if ($main_variant['description_filled_from_amazon']){
					echoLine('[editFullProduct] Copy description from main: ' . $main_variant['main_variant_id'], 'i');
					$this->model_product_edit->editProductDescriptions($product_id, $this->model_product_get->getProductDescriptions($main_variant['main_variant_id']));
					$this->registry->get('rainforestAmazon')->infoUpdater->setDescriptionIsFilledFromAmazon($product_id);
				}
			}			
		} else {
			$this->parseProductDescriptions($product_id, $product);
			echoLine('[editFullProduct] Setting description marker: ' . $product_id, 's');
			$this->registry->get('rainforestAmazon')->infoUpdater->setDescriptionIsFilledFromAmazon($product_id);
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
		$this->parseProductAttributes($product_id, $product, $main_variant?$main_variant['main_variant_id']:false);

			//Fixes SKU if it's empty
		$this->fixEmptySKU($product_id);	
		
			//Размеры, готовая функция из InfoUpdater
		$this->registry->get('rainforestAmazon')->infoUpdater->parseAndUpdateProductDimensions($product);

			//Related Products
		$this->parseProductRelatedProducts($product_id, $product);
		
			//Similar Products
		$this->parseProductSimilarProducts($product_id, $product);

			//Sponsored Products
		$this->parseProductSponsoredProducts($product_id, $product);

			//SimilarToConsider Products
		$this->parseProductSimilarToConsiderProducts($product_id, $product);

			//ViewToPurchase Products
		$this->parseProductViewToPurchaseProducts($product_id, $product);

			//AlsoViewed Products
		$this->parseProductAlsoViewedProducts($product_id, $product);

			//AlsoBought Products
		$this->parseProductAlsoBoughtProducts($product_id, $product);

			//ShopByLook Products
		$this->parseProductShopByLookProducts($product_id, $product);

			//Parse product categories if not set
		$this->parseProductCategories($product_id, $product);			

			//Parse product buybox winner to amazon best price
		$this->parseProductBuyBoxWinner($product_id, $product);

			//Parse product top reviews
		$this->parseProductTopReviews($product_id, $product);

			//Варианты		
		$this->parseProductVariants($product_id, $product, $do_adding_new_variants);				

		$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);
		$this->registry->get('rainforestAmazon')->infoUpdater->setProductIsFilledFromAmazon($product_id)->enableProduct($product_id);
		$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmznData([
			'product_id' 	=> $product_id, 
			'asin' 			=> $product['asin'], 
			'json' 			=> json_encode($product)
		], false);
	}
	
	public function editFullProductsAsyncWithNoVariantParser($products){
		if ($products){
			$total = count($products);
			$iterations = ceil($total/\hobotix\RainforestAmazon::productRequestLimits);

			for ($i = 1; $i <= $iterations; $i++){
				$slice = array_slice($products, \hobotix\RainforestAmazon::productRequestLimits * ($i-1), \hobotix\RainforestAmazon::productRequestLimits);
				$results = $this->registry->get('rainforestAmazon')->simpleProductParser->getProductByASINS($slice);

				foreach ($results as $product_id => $result){
					$this->registry->get('rainforestAmazon')->infoUpdater->updateProductAmazonLastSearch($product_id);

					if ($result){
						echoLine('[ProductsRetriever::editFullProductsWNP] Product ' . $product_id . ', found, ASIN ' . $result['asin'], 's');				

						$this->editFullProduct($product_id, $result, false);
					} else {
						echoLine('[ProductsRetriever::editFullProductsWNP] Product ' . $product_id . ', not found', 'e');
					}
				}
			}
		}
	}	
	
	public function addSimpleProductWithOnlyAsin($data) {			

		if ($this->model_product_get->checkIfAsinIsDeleted($data['asin'])){
			echoLine('[RainforestRetriever::addSimpleProductWithOnlyAsin] ASIN . ' . $data['asin'] . ' . deleted, skipping!', 'w');				
			return 0;
		}	

		if ($this->getProductsByAsin($data['asin'])){
			echoLine('[RainforestRetriever::addSimpleProductWithOnlyAsin] ASIN ' . $data['asin'] . ' exists, skipping!', 'w');
			return 0;
		}

		if ($this->model_product_get->checkIfNameIsExcluded($data['name'], $data['category_id'])){		
			$this->model_product_edit->deleteASINFromQueue($data['asin']);				
			$this->model_product_edit->addAsinToIgnored($data['asin'], $data['name']);

			echoLine('[RainforestRetriever::addSimpleProductWithOnlyAsin] NAME ' . $data['name'] . ' is excluded, skipping!', 'w');
			return 0;
		}

		$low_price_to_add = (float)$this->config->get('config_rainforest_skip_low_price_products');
		if (!empty($data['explicit_min_price_to_add']) && (float)$data['explicit_min_price_to_add']){
			$low_price_to_add = (float)$data['explicit_min_price_to_add'];
		}

		if (!empty($data['amazon_best_price'])){
			if ((float)$low_price_to_add){
				if ((float)$data['amazon_best_price'] < (float)$low_price_to_add){
					echoLine('[RainforestRetriever::addSimpleProductWithOnlyAsin] Price '. $data['amazon_best_price'] .' too low, skipping!', 'w');				
					return 0;
				}
			}
		}	

		$high_price_to_add = (float)$this->config->get('config_rainforest_skip_high_price_products');
		if (!empty($data['explicit_max_price_to_add']) && (float)$data['explicit_min_price_to_add']){
			$high_price_to_add = (float)$data['explicit_max_price_to_add'];
		}

		if (!empty($data['amazon_best_price'])){
			if ((float)$high_price_to_add){
				if ((float)$data['amazon_best_price'] > (float)$high_price_to_add){
					echoLine('[RainforestRetriever::addSimpleProductWithOnlyAsin] Price '. $data['amazon_best_price'] .' too high, skipping!', 'w');				
					return 0;
				}
			}
		}		

		if (!empty($data['status'])){
			$status = $data['status'];
		} else {
			$status = 0;
		}

		$this->db->query("INSERT INTO product SET 
			model 					= '" . $this->db->escape($data['asin']) . "', 
			asin 					= '" . $this->db->escape($data['asin']) . "', 
			image           		= '" . (!empty($data['image'])?$this->db->escape($data['image']):'') . "', 			
			added_from_amazon 		= '1', 
			amzn_no_offers			= '0',
			amzn_no_offers_counter	= '0',
			amazon_best_price     	= '" . (!empty($data['amazon_best_price'])?(float)$data['amazon_best_price']:0) . "',
			main_variant_id     	= '" . (!empty($data['main_variant_id'])?(int)$data['main_variant_id']:0) . "',
			amazon_product_link  	= '" . (!empty($data['amazon_product_link'])?$this->db->escape($data['amazon_product_link']):'') . "',
			amazon_product_image 	= '" . (!empty($data['amazon_product_image'])?$this->db->escape($data['amazon_product_image']):'') . "',
			stock_status_id 		= '" . (int)$this->config->get('config_stock_status_id') . "',
			quantity 				= '9999',
			status 					= '" . (int)$status . "',
			date_added 				= NOW()");
		
		$product_id = $this->db->getLastId();
			
		if (!empty($data['amazon_best_price'])){
			$this->registry->get('rainforestAmazon')->offersParser->PriceLogic->updateProductPrices($data['asin'], $data['amazon_best_price'], true);
		}
		
		$this->db->query("DELETE FROM product_to_store WHERE product_id 	= '" . (int)$product_id . "'");		
		$this->db->query("INSERT INTO product_to_store SET product_id 		= '" . (int)$product_id . "', store_id = '0'");

		if (!empty($this->config->get('config_rainforest_add_to_stores'))){
			foreach ($this->config->get('config_rainforest_add_to_stores') as $store_id){
				if ($store_id > 0){
					$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}				
			}
		}

		$this->db->query("DELETE FROM product_to_category WHERE product_id 	= '" . (int)$product_id . "'");
		$this->db->query("INSERT INTO product_to_category SET product_id 	= '" . (int)$product_id . "', category_id = '" . (int)$data['category_id'] . "', main_category = 1");
		echoLine('[RainforestRetriever::addSimpleProductWithOnlyAsin] Adding product to category ' . $data['category_id'], 'i');

		$this->db->query("DELETE FROM product_description WHERE product_id 	= '" . (int)$product_id . "'");
		$this->parseProductNames($product_id, $data);

		if ($this->config->get('config_seo_url_from_id') && $this->registry->get('url')->checkIfGenerate('product_id')){
			$this->db->query("DELETE FROM url_alias WHERE query = '" . $this->db->escape('product_id=' . $product_id) . "'");

			foreach ($this->registry->get('languages') as $language_code => $language) {
				$this->db->query("INSERT INTO url_alias SET query = 'product_id=". (int)$product_id ."', keyword = '" . $this->db->escape('p' . $product_id) . "', language_id = '" . (int)$language['language_id'] 	 ."'");	
			}
		}			
		
		return $product_id;
	}	
}	
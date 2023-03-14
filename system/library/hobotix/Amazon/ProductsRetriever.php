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
	public function getProductsWithFullData(){
		return $this->model_product_get->getProductsWithFullData();
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
			if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $real_language_code)){
				$text = $this->yandexTranslator->translate($text, $detect?false:$this->config->get('config_rainforest_source_language'), $real_language_code, true);
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

	public function parseProductManufacturer($product_id, $product){
		if (!empty($product['brand'])){
			$product['brand'] = atrim($product['brand']);

			echoLine('[editFullProduct] Бренд: ' . $product['brand']);
			$manufacturer_id = $this->model_product_cached_get->getManufacturer($product['brand']);			
			if (!$manufacturer_id){
				echoLine('[editFullProduct] Бренд не существует, добавляем:' . $product['brand']);
				$manufacturer_id = $this->model_product_edit->addManufacturer($product['brand']);
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
				echoLine('[editFullProduct] Features: ' . $feature_bullets_counter);

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
			echoLine('[editFullProduct] Copying feature_bullets from main product: ' . $main_variant_id);
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

					echoLine('[parseProductAttributes] Ищем атрибут: ' . $attribute['name']);

					$attribute_id = $this->model_product_cached_get->getAttribute($attribute['name']);

					if (!$attribute_id){
						echoLine('[parseProductAttributes] Атрибут не найден: ' . $attribute['name']);

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
							echoLine('[parseProductRelatedProducts] Related товар: ' . $related_id);

							$product_related[] = $related_id;
						}

					} else {

						if ($this->config->get('config_rainforest_enable_recursive_adding') && $this->config->get('config_rainforest_enable_related_adding')){

							echoLine('[parseProductRelatedProducts] Новый Related товар: ' . $bought_together['asin'] . ' ' . $bought_together['title']);

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
			if ($this->model_product_get->getCurrentProductCategory($product_id) == $this->config->get('config_rainforest_default_technical_category_id')){
				$name = $product['categories'][count($product['categories']) - 1]['name'];

				if ($category_id = $this->model_product_cached_get->getCategory(atrim($name))){
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
					return $variant['main_image'];
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

	public function checkProductsVariants($product_id, $product){
		if ($this->config->get('config_rainforest_skip_variants') && !empty($product['variants'])){
			return (count($product['variants']) <= (int)$this->config->get('config_rainforest_skip_variants'));
		}

		return true;
	}

	public function parseProductVariants($product_id, $product, $do_adding_new_variants = true){
		$this->parseProductVariantDimensions($product_id, $product);		

		if (!empty($product['variants']) && $do_adding_new_variants){	
				//У нас 2 варианта, либо текущий товар - основной, либо текущий - не основной
				//В любом случае в табличке вариантов уже есть записи, они добавлены либо из текущего товара, либо из не_текущего, в любом случае мы отбираем все варианты
				//И да, список всегда будет одинаковый, потому что записан, сука
			$variants = $this->model_product_get->getOtherProductVariantsByAsin($product['asin']);				
			$new_product_data = [];
			foreach ($variants as $variant){
					//Товар уже существует
				if ($variant['product_id']){
						//Товар существует и уже полностью обновлен, записан, и ваще у него всё хорошо
					if ($variant['filled_from_amazon']){
							//Просто обновляем привязку родителя по асину
						echoLine('[parseProductVariants] Обновляем привязку родителя по асину:' . $variant['asin'] . ':' . $variant['main_asin']);
						$this->model_product_edit->updateProductMainVariantIdByParentAsin($variant['product_id'], $variant['main_asin']);
					} else {
							//Отправляем товар на полную загрузку
						echoLine('[parseProductVariants] Заполняем товар-вариант:' . $variant['asin']);
						$new_product_data[] = [
							'product_id' => $variant['product_id'],
							'asin'		 => $variant['asin']
						];

						echoLine('[parseProductVariants] Обновляем привязку родителя по асину:' . $variant['asin'] . ':' . $variant['main_asin']);
						$this->model_product_edit->updateProductMainVariantIdByParentAsin($variant['product_id'], $variant['main_asin']);
					}
				} else {

					if (!$this->getProductsByAsin($variant['asin'])){

						//Товара не существует вообще
						echoLine('[parseProductVariants] Новый товар-вариант:' . $variant['asin']);

						$new_product_name = $this->trimProductNameWithoutVariant($product['title'], $this->getCurrentVariantDimensions($product['variants']), $this->getVariantDimensionsByAsin($product['variants'], $variant['asin']));
						echoLine('[editFullProduct] Новый вариант: ' . $new_product_name);	

						$new_product_id = $this->addSimpleProductWithOnlyAsin([
							'asin' 				=> $variant['asin'], 
							'category_id' 		=> $this->model_product_get->getCurrentProductCategory($product_id), 
							'main_variant_id'	=> $product_id,
							'name' 				=> $new_product_name,
							'image' 			=> $this->getImage($this->getVariantImageByAsin($product['variants'], $variant['asin'])), 
							'added_from_amazon' => 1
						]);
					}	

					echoLine('[parseProductVariants] Обновляем привязку родителя по асину:' . $variant['asin'] . ':' . $variant['main_asin']);
					$this->model_product_edit->updateProductMainVariantIdByParentAsin($new_product_id, $variant['main_asin']);					


					$new_product_data[] = [
						'product_id' => $new_product_id,
						'asin' => $variant['asin']
					];						
				}
			}

			if ($new_product_data){
				$this->editFullProductsAsyncWithNoVariantParser($new_product_data);
			}
		}
	}

	public function parseProductBuyBoxWinner($product_id, $product){
		if (!empty($product['buybox_winner'])){
			if (!empty($product['buybox_winner']['price']) && $product['buybox_winner']['price']['currency'] == 'EUR'){					
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
						echoLine('[parseProductTopReviews] длина отзыва ' . mb_strlen($review['body']) . ', пропускаем');
						continue;
					}

					if ($review['rating'] < (int)$this->config->get('config_rainforest_min_review_rating')){
						echoLine('[parseProductTopReviews] рейтинг отзыва ' . $review['rating'] . ', пропускаем');
						continue;
					}

					$counter++;
					if ($counter >= (int)$this->config->get('config_rainforest_max_review_per_product')){
						break;
					}

					$review_description = [];					
					$author = $this->db->ncquery("SELECT firstname FROM customer WHERE firstname <> '' ORDER BY RAND() LIMIT 1")->row['firstname'];
					echoLine('[parseProductTopReviews] Автор ' . $author);

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
	
	public function editFullProduct($product_id, $product, $do_adding_new_variants = true){	
		$this->yandexTranslator->setDebug(false);

		if (!$this->checkProductsVariants($product_id, $product)){
			echoLine('[editFullProduct] У товара ' . count($product['variants']) . ' вариантов, удаляем');
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
				echoLine('[parseProductVariants] Обновляем привязку родителя по product_id:' . $product_id . ':' . $main_variant['main_variant_id']);
				$this->model_product_edit->updateProductMainVariantId($product_id, $main_variant['main_variant_id']);

				if ($main_variant['description_filled_from_amazon']){
					echoLine('[editFullProduct] Копируем описание с основного товара: ' . $main_variant['main_variant_id']);
					$this->model_product_edit->editProductDescriptions($product_id, $this->model_product_get->getProductDescriptions($main_variant['main_variant_id']));
					$this->registry->get('rainforestAmazon')->infoUpdater->setDescriptionIsFilledFromAmazon($product_id);
				}
			}

			
		} else {
			$this->parseProductDescriptions($product_id, $product);
			echoLine('[editFullProduct] Ставим маркер получения описаний: ' . $product_id);
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
						echoLine('[editFullProductsWNP] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);				

						$this->editFullProduct($product_id, $result, false);

					} else {

						echoLine('[editFullProductsWNP] Товар ' . $product_id . ', не найден');
					}
				}
			}
		}
	}
	
	public function addSimpleProductWithOnlyAsin($data) {			

		if ($this->model_product_get->checkIfAsinIsDeleted($data['asin'])){
			echoLine('[RainforestRetriever] ASIN . ' . $data['asin'] . ' . deleted, skipping!', 'e');				
			return 0;
		}	

		if ($this->model_product_get->checkIfNameIsExcluded($data['name'], $data['category_id'])){		
			$this->model_product_edit->deleteASINFromQueue($data['asin']);				
			$this->model_product_edit->addAsinToIgnored($data['asin'], $data['name']);

			echoLine('[RainforestRetriever] NAME ' . $data['name'] . ' is excluded, skipping!', 'e');
			return 0;
		}

		if (!empty($data['amazon_best_price'])){
			if ($this->config->get('config_rainforest_skip_low_price_products')){
				if ((float)$data['amazon_best_price'] < (float)$this->config->get('config_rainforest_skip_low_price_products')){
					echoLine('[RainforestRetriever] Price too low, skipping!');				
					return 0;
				}
			}
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
			status 					= '0',
			date_added 				= NOW()");
		
		$product_id = $this->db->getLastId();
			
		if (!empty($data['amazon_best_price'])){
			$this->registry->get('rainforestAmazon')->offersParser->PriceLogic->updateProductPrices($data['asin'], $data['amazon_best_price'], true);
		}
		
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
				'short_name_d'  => '',
				'translated' 	=> $translated
			];

			if ($language['code'] != $this->config->get('config_rainforest_source_language') && $name){
				$product_name_data[$language['language_id']]['name'] = $this->registry->get('rainforestAmazon')->infoUpdater->normalizeProductName($name);
			}

			if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_enable_shorten_names') && $this->config->get('config_rainforest_short_names_with_openai')){
				$product_name_data[$language['language_id']]['name'] = $this->registry->get('openaiAdaptor')->shortenName($product_name_data[$language['language_id']]['name'], $language['code']);
			}

			if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_enable_export_names') && $this->config->get('config_rainforest_export_names_with_openai')){
				$product_name_data[$language['language_id']]['short_name_d'] = $this->registry->get('openaiAdaptor')->exportName($product_name_data[$language['language_id']]['name'], $language['code']);
			}
			
			$this->model_product_edit->addProductNames($product_id, $product_name_data);
		}

		if ($this->config->get('config_seo_url_from_id') && $this->registry->get('url')->checkIfGenerate('product_id')){
			$this->db->query("DELETE FROM url_alias WHERE query = '" . $this->db->escape('product_id=' . $product_id) . "'");

			foreach ($this->registry->get('languages') as $language_code => $language) {
				$this->db->query("INSERT INTO url_alias SET query = 'product_id=". (int)$product_id ."', keyword = '" . $this->db->escape('p' . $product_id) . "', language_id = '" . (int)$language['language_id'] 	 ."'");	
			}
		}			
		
		return $product_id;
	}
	
}	
<?php

class ControllerFeedYandexKP extends Controller {

	private $full_path 	= ['yandex_fast_full_feed_{store_id}.xml'];
	private $stock_path = ['yandex_market_feed_{store_id}{yam_prefix}.xml'];
	private $direct_stock_path = ['yandex_direct_stock_feed_{store_id}.xml'];

	private $vk_full_path 			= ['vk_feed_{store_id}.xml'];	
	private $ozon_full_path 		= ['ozon_feed_full_{store_id}.xml'];
	private $ozon_path 				= ['ozon_feed_{store_id}.xml'];
	private $priceva_path 			= ['priceva_{brand}_{store_id}.xml'];		

	private $supported_currencies 	= array('RUR', 'RUB', 'USD', /* 'BYN', 'KZT', */ 'EUR', 'UAH');	
	private $local_deliveries_cost 	= ['0' => 400, '1' => 80, '2' => false, '5' => false];
	private $pickup_available 		= ['0', '1'];
		
	private $yandexWeightClassID = 1;
	private $yandexLengthClassID = 1;
	private $ozonWeightClassID 	= 2;
	private $ozonLengthClassID 	= 2;
		
	private $excludeOzonManufacturers 	= [];
	private $excludeYandexManufacturers = [];	

	private $defaultYandexCategory 	= null;
	private $currentWeightClassID 	= 1;
	private $currentLengthClassID 	= 1;

	private $type = 'yandex';

	private $products 					= [];
	private $images 					= [];
	private $attributes 				= [];
	private $defaultDimensions 			= [];
	private $yandexCategories 			= [];
	private $yandexCategoriesMapping 	= [];
	private $oldLogicCompetitorFieldMapping = [];
	private $yml = '';		

	private $excluded_names = ['пепельниц', 'зажигалк'];
	private $excluded_categories = [8307, 6475, 6474, 6614, BIRTHDAY_DISCOUNT_CATEGORY, GENERAL_MARKDOWN_CATEGORY];

	private function setFeedType($type){
		$this->type = $type;

		$this->currentWeightClassID = $this->yandexWeightClassID;
		$this->currentLengthClassID = $this->yandexLengthClassID;
		$this->excludeYandexManufacturers 	= $this->config->get('config_yandex_exclude_manufacturers');

		if ($this->type == 'ozon'){
			echoLine('Тип фида: Озон');

			$this->currentWeightClassID = $this->ozonWeightClassID;
			$this->currentLengthClassID = $this->ozonLengthClassID;

			$this->excludeOzonManufacturers 	= $this->config->get('config_ozon_exclude_manufacturers');
		}

		echoLine('Единица веса:' . $this->currentWeightClassID);
		echoLine('Единица длины:' . $this->currentLengthClassID);

		return $this;
	}

	protected function prepareField($field) {
		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field);
		$from = array('"', '&', '>', '<', '\'', '&nbsp;');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;', ' ');
		$field = str_replace($from, $to, $field);
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}

	protected function detectUnits($attribute) {
		$attribute['name'] = trim(strip_tags($attribute['name']));
		$attribute['text'] = trim(strip_tags($attribute['text']));

		if (preg_match('/\(([^\)]+)\)$/mi', $attribute['name'], $matches)) {
			$attribute['name'] = trim(str_replace('('.$matches[1].')', '', $attribute['name']));
			$attribute['unit'] = trim($matches[1]);
		}

		return $attribute;
	}				

	private function loadSettings($store_id){
		$this->load->model('setting/setting');	
		$this->model_setting_setting->loadSettings($store_id);

		if ($this->config->get('config_yam_offer_feed_template')){
			$this->stock_path = [$this->config->get('config_yam_offer_feed_template')];
		}

		if ($this->config->get('config_yam_excludewords')){
			$this->excluded_names = explode(PHP_EOL, $this->config->get('config_yam_excludewords'));
		}

		if ($this->config->get('config_yam_default_category_id')){
			$this->defaultYandexCategory = $this->config->get('config_yam_default_category_id');
		}

		if (!is_null($this->pricevaAdaptor)){
			$this->oldLogicCompetitorFieldMapping = $this->pricevaAdaptor->getOldLogicCompetitorFieldMapping();
		}

		$query = $this->db->query("SELECT MAX(category_id) as max_category_id FROM category WHERE 1");
		$max_category_id = ($query->row['max_category_id'] + 10);

		$query = $this->db->query("SELECT * FROM category_yam_tree WHERE 1");
		foreach ($query->rows as $row){
			$this->yandexCategories[$row['full_name']] = ($max_category_id + $row['category_id']);
		}

		$query = $this->db->query("SELECT * FROM category WHERE LENGTH(yandex_category_name) > 2");
		foreach ($query->rows as $row){
			if (!empty($this->yandexCategories[$row['yandex_category_name']])){				
				$this->yandexCategoriesMapping[$row['category_id']] = $this->yandexCategories[$row['yandex_category_name']];				
			}
		}

		return $this;
	}

	private function openYML(){
		$this->yml 	= '';
		$this->yml .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
		$this->yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . PHP_EOL;
		$this->yml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . PHP_EOL;
		$this->yml .= '<shop>' . PHP_EOL;

		return $this;
	}

	private function closeYML(){
		$this->yml .= '</shop>' . PHP_EOL;
		$this->yml .= '</yml_catalog>' . PHP_EOL;

		return $this;
	}

	private function addShop(){

		$this->yml .= '<name>' . $this->config->get('config_name') . '</name>' . PHP_EOL;
		$this->yml .= '<company>' . $this->config->get('config_owner') . '</company>' . PHP_EOL;
		$this->yml .= '<url>' . $this->config->get('config_ssl') . '</url>' . PHP_EOL;
		$this->yml .= '<phone>' . $this->config->get('config_telephone') . '</phone>' . PHP_EOL;
		$this->yml .= '<platform>FastYML 2</platform>' . PHP_EOL;
		$this->yml .= '<version>1.1</version>' . PHP_EOL;

		return $this;
	}

	private function addCurrency(){
		$this->load->model('localisation/currency');

		$this->yml .= '<currencies>' . PHP_EOL;

		$currencies 	= $this->model_localisation_currency->getCurrencies();
		$currencies 	= array_intersect_key($currencies, array_flip($this->supported_currencies));

		$this->yml .= '<currency id="' . $this->config->get('config_regional_currency') . '" rate="1"/>' . PHP_EOL;

		foreach ($currencies as $currency) {											
			if ($currency['code'] != $this->config->get('config_regional_currency') && $currency['status'] == 1) {				
				$this->yml .= '<currency id="' . $currency['code'] . '" rate="' . number_format($this->currency->real_convert(1, $currency['code'],  $this->config->get('config_regional_currency')), 2, '.', '') . '"/>' . PHP_EOL;								
			}
		}

		$this->yml .= '</currencies>' . PHP_EOL;

		echoLine('[YML] Добавили валюты');

		return $this;
	}

	private function addCategoriesPriceva(){				
		$this->yml .= '<categories>' . PHP_EOL;

		$query = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1' AND c.priceva_enable = 1");

		foreach ($query->rows as $row){
			if (!empty($row['parent_id'])){
				$this->yml .= '<category id="' . $row['category_id'] . '" parentId="' . $row['parent_id'] . '" >' . $this->prepareField($row['name']) . '</category>' . PHP_EOL;
			} else {
				$this->yml .= '<category id="' . $row['category_id'] . '">' . $this->prepareField($row['name']) . '</category>' . PHP_EOL;
			}
		}

		$this->yml .= '</categories>' . PHP_EOL;

		echoLine('[YML] Добавили категории');

		return $this;
	}

	private function addCategories(){				
		$this->yml .= '<categories>' . PHP_EOL;

		$query = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1'");

		foreach ($query->rows as $row){
			if (!empty($row['parent_id'])){
				$this->yml .= '<category id="' . $row['category_id'] . '" parentId="' . $row['parent_id'] . '" >' . $this->prepareField($row['name']) . '</category>' . PHP_EOL;
			} else {
				$this->yml .= '<category id="' . $row['category_id'] . '">' . $this->prepareField($row['name']) . '</category>' . PHP_EOL;
			}
		}

		if ($this->config->get('config_yam_enable_category_tree')){
			$query = $this->db->query("SELECT MAX(category_id) as max_category_id FROM category WHERE 1");
			$max_category_id = ($query->row['max_category_id'] + 10);

			$query = $this->db->query("SELECT * FROM category_yam_tree WHERE 1");

			foreach ($query->rows as $row){

				if (!empty($row['parent_id'])){
					$this->yml .= '<category id="' . ($max_category_id + $row['category_id']) . '" parentId="' . ($max_category_id + $row['parent_id']) . '" >' . $this->prepareField($row['name']) . '</category>' . PHP_EOL;
				} else {
					$this->yml .= '<category id="' . ($max_category_id + $row['category_id']) . '">' . $this->prepareField($row['name']) . '</category>' . PHP_EOL;
				}
			}
		}

		$this->yml .= '</categories>' . PHP_EOL;

		echoLine('[YML] Добавили категории');

		return $this;
	}

	private function format($price){
		return $this->currency->format($price, '', '', false);			
	}

	private function format_national($price){
		return $this->currency->format($price, '', 1, false);			
	}

	private function getCategory($product_id){
		$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id NOT IN (" . implode(',', $this->excluded_categories) . ") ORDER BY main_category DESC LIMIT 1");

		if (!$query->num_rows){
			return $this->defaultYandexCategory;
		}

		return $query->row['category_id'];
	}

	private function getCategoryDefaultDimensions($category_id){
		$query = $this->db->query("SELECT default_length, default_width, default_height, default_weight, default_length_class_id, default_weight_class_id FROM category WHERE category_id = '" . (int)$category_id . "'");

		return $query->row;
	}

	private function cacheCategoryDimensions(){
		$query = $this->db->query("SELECT category_id, default_length, default_width, default_height, default_weight, default_length_class_id, default_weight_class_id FROM category WHERE 1");

		foreach ($query->rows as $row){

			$this->defaultDimensions[$row['category_id']] = array(
				'default_length' 			=> $row['default_length'],
				'default_width' 			=> $row['default_width'],
				'default_height' 			=> $row['default_height'],
				'default_weight' 			=> $row['default_weight'],
				'default_length_class_id' 	=> $row['default_length_class_id'],
				'default_weight_class_id' 	=> $row['default_weight_class_id'],
				
			);

		}
		return $this;
	}

	private function getProductDimensions($product){

		$weight = (float)$product['weight'];
		$weight_class_id = (int)$product['weight_class_id'];
		if ($product['pack_weight']){
			$weight = (float)$product['pack_weight'];
			$weight_class_id = (int)$product['pack_weight_class_id'];
		}

		$length = (float)$product['length'];
		$length_class_id = (int)$product['length_class_id'];
		if ($product['pack_length']){
			$length = (float)$product['pack_length'];
			$length_class_id = (int)$product['pack_length_class_id'];
		}

		$width = (float)$product['width'];
		if ($product['pack_width']){
			$width = (float)$product['pack_width'];
		}

		$height = (float)$product['height'];
		if ($product['pack_height']){
			$height = (float)$product['pack_height'];
		}			

		if (!$weight && !empty($this->defaultDimensions[$product['main_category_id']]['default_weight'])){
			$weight = (float)$this->defaultDimensions[$product['main_category_id']]['default_weight'];
			$weight_class_id = (int)$this->defaultDimensions[$product['main_category_id']]['default_weight_class_id'];
		}

		if (!$length && !empty($this->defaultDimensions[$product['main_category_id']]['default_length'])){
			$length = (float)$this->defaultDimensions[$product['main_category_id']]['default_length'];
			$length_class_id = (int)$this->defaultDimensions[$product['main_category_id']]['default_length_class_id'];
		}

		if (!$width && !empty($this->defaultDimensions[$product['main_category_id']]['default_width'])){
			$width = (float)$this->defaultDimensions[$product['main_category_id']]['default_width'];
		}

		if (!$height && !empty($this->defaultDimensions[$product['main_category_id']]['default_height'])){
			$height = (float)$this->defaultDimensions[$product['main_category_id']]['default_height'];
		}

		$dimensions = array(
			'weight' 			=> $weight,
			'weight_class_id' 	=> $weight_class_id,
			'length' 			=> $length,
			'width' 			=> $width,
			'height' 			=> $height,
			'length_class_id' 	=> $length_class_id
		);

		return $dimensions;
	}

	private function addOffersOzonTruncated(){
		$this->yml .= '<offers>' . PHP_EOL;

		foreach ($this->products as $product){
			$product_id = $product['product_id'];

			if ($this->type == 'ozon'){
				$this->updateOzonInFeed($product['product_id']);
			}

			$this->yml .= '<offer id="' . $product['product_id'] . '">' . PHP_EOL;

			if ($this->type == 'ozon' && $this->config->get('config_store_id') == 0){
				$this->yml .= '	<outlets>' . PHP_EOL;
				$this->yml .= '		<outlet instock="' . (int)$product[$this->config->get('config_warehouse_identifier')] . '" warehouse_name="' . $this->config->get('config_ozon_warehouse_0') . '" ></outlet>' . PHP_EOL;
				$this->yml .= '	</outlets>' . PHP_EOL;
			}

			if (($this->type == 'market' && $this->config->get('config_yam_offer_id_price_enable') && (float)$product['yam_price_national']) || 
			($this->type == 'ozon' && $this->config->get('config_ozon_enable_price_yam') && $this->config->get('config_yam_offer_id_price_enable') && (float)$product['yam_price_national'])
			){

				if ($product['yam_special_national'] > 0 && $product['yam_special_national'] < $product['yam_price_national']) {
					$this->yml .= '	<price>' 	. $this->format_national($product['yam_special_national']) . '</price>' . PHP_EOL;
					$this->yml .= '	<oldprice>' 	. $this->format_national($product['yam_price_national']) . '</oldprice>' . PHP_EOL;
				} else {
					$this->yml .= '	<price>' 	. $this->format_national($product['yam_price_national']) . '</price>' . PHP_EOL;
				}

			} else {

				if ($product['special'] > 0 && $product['special'] < $product['price']){
					$this->yml .= '	<price>' 	. $this->format($product['special']) . '</price>' . PHP_EOL;
					$this->yml .= '	<oldprice>' 	. $this->format($product['price']) . '</oldprice>' . PHP_EOL;
				} else {
					$this->yml .= '	<price>' 	. $this->format($product['price']) . '</price>' . PHP_EOL;
				}

			}

			$this->yml .= '</offer>' . PHP_EOL;
		}

		$this->yml .= '</offers>' . PHP_EOL;

		echoLine('[YML] Добавили товары');

		return $this;
	}

	private function addOffers(){

		$local_delivery_cost = false;
		if (!empty($this->local_deliveries_cost[$this->config->get('config_store_id')]) && $this->local_deliveries_cost[$this->config->get('config_store_id')]){
			$local_delivery_cost = $this->local_deliveries_cost[$this->config->get('config_store_id')];
		}

		$this->yml .= '<offers>' . PHP_EOL;

		foreach ($this->products as $product){
			$product_id = $product['product_id'];

			if ($this->type == 'market'){
				$this->updateYandexMarketInFeed($product['product_id']);
			}

			if ($this->type == 'ozon'){
				$this->updateOzonInFeed($product['product_id']);
			}

			if ($this->type == 'vkontakte'){
				$this->updateVkontakteInFeed($product['product_id']);
			}

			if ($this->type == 'market' && $this->config->get('config_yam_offer_id_prefix_enable') && $this->config->get('config_yam_offer_id_prefix')){
				$this->yml .= '<offer id="' . $product['yam_product_id'] . '" available="' . $product['available'] . '" type="vendor.model">' . PHP_EOL;
			} else {
				$this->yml .= '<offer id="' . $product['product_id'] . '" available="' . $product['available'] . '" type="vendor.model">' . PHP_EOL;
			}

			if ($this->type == 'ozon' && $this->config->get('config_store_id') == 0){
				$this->yml .= '	<outlets>' . PHP_EOL;
				$this->yml .= '		<outlet instock="' . (int)$product[$this->config->get('config_warehouse_identifier')] . '" warehouse_name="' . $this->config->get('config_ozon_warehouse_0') . '" ></outlet>' . PHP_EOL;
				$this->yml .= '	</outlets>' . PHP_EOL;
			}

			if ($this->type == 'market' && $this->config->get('config_yam_offer_id_link_disable')){
					//Если маркет и включена настройка "не подавать URI", во всех остальных случаях мы ее подаем
			} else {
				$this->yml .= '	<url>' . $this->prepareField($this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</url>' . PHP_EOL;
			}

			$this->yml .= '	<pickup>' . (bool)$product['pickup'] . '</pickup>' . PHP_EOL;
			$this->yml .= '	<delivery>true</delivery>' . PHP_EOL;

			if ($local_delivery_cost){
					//	$this->yml .= '	<local_delivery_cost>' 	. $local_delivery_cost . '</local_delivery_cost>' . PHP_EOL;
			}

			if ($this->config->get('config_store_id') == 0){
				$this->yml .= '	<count>' . (int)$product[$this->config->get('config_warehouse_identifier')] . '</count>' . PHP_EOL;
			}

			if (($this->type == 'market' && $this->config->get('config_yam_offer_id_price_enable') && (float)$product['yam_price_national']) || 
			($this->type == 'ozon' && $this->config->get('config_ozon_enable_price_yam') && $this->config->get('config_yam_offer_id_price_enable') && (float)$product['yam_price_national'])
			){

					//Теперь цены в фиде берутся из карточек товара, включа
				if ($product['yam_special_national'] > 0 && $product['yam_special_national'] < $product['yam_price_national']) {

					$this->yml .= '	<price>' 	. $this->format_national($product['yam_special_national']) . '</price>' . PHP_EOL;
					$this->yml .= '	<oldprice>' 	. $this->format_national($product['yam_price_national']) . '</oldprice>' . PHP_EOL;

				} else {

					$this->yml .= '	<price>' 	. $this->format_national($product['yam_price_national']) . '</price>' . PHP_EOL;

				}

			} else {

				if ($product['special'] > 0 && $product['special'] < $product['price']){
					$this->yml .= '	<price>' 	. $this->format($product['special']) . '</price>' . PHP_EOL;
					$this->yml .= '	<oldprice>' 	. $this->format($product['price']) . '</oldprice>' . PHP_EOL;
				} else {
					$this->yml .= '	<price>' 	. $this->format($product['price']) . '</price>' . PHP_EOL;
				}

			}
			$this->yml .= '	<currencyId>' 	. $this->config->get('config_regional_currency') . '</currencyId>' . PHP_EOL;				

			$this->yml .= '	<vendor><![CDATA[' 		. $this->prepareField($product['manufacturer']) . ']]></vendor>' . PHP_EOL;
			$this->yml .= '	<vendorCode><![CDATA[' 	. $this->prepareField($product['model']) . ']]></vendorCode>' . PHP_EOL;
			$this->yml .= '	<model><![CDATA[' 		. $this->prepareField($product['name']) . ']]></model>' . PHP_EOL;
			$this->yml .= '	<description><![CDATA[' . $this->prepareField($product['description']) . ']]></description>' . PHP_EOL;

			$this->yml .= '	<available>' . $product['available'] . '</available>' . PHP_EOL;

			if (!empty($product['resized_images'])){
				foreach ($product['resized_images'] as $image){
					$this->yml .= '	<picture><![CDATA[' . $this->prepareField($image) . ']]></picture>' . PHP_EOL;
				}
			}

			$this->yml .= '	<categoryId>' . $product['main_category_id'] . '</categoryId>' . PHP_EOL;

			if (!empty($product['ean'])){
				$this->yml .= '	<barcode><![CDATA[' . $this->prepareField($product['ean']) . ']]></barcode>' . PHP_EOL;
			}

			if ($product['location']){
				$this->yml .= '	<country_of_origin><![CDATA[' . $this->prepareField($product['location']) . ']]></country_of_origin>' . PHP_EOL;
			}

			if ($product['minimum'] > 1) {					
				$this->yml .= '	<sales_notes><![CDATA[Минимальное кол-во заказа: ' . $product['minimum'] . ' шт.]]></sales_notes>' . PHP_EOL;				
			}

			if ($product['attributes']){
				foreach ($product['attributes'] as $attribute){
					$attribute = $this->detectUnits($attribute);

					if (!empty($attribute['unit'])){
						$this->yml .= '	<param name="' . $attribute['name'] . '" unit="' . $attribute['unit'] . '"><![CDATA[' . $attribute['text'] . ']]></param>' . PHP_EOL;
					} else {
						$this->yml .= '	<param name="' . $attribute['name'] . '"><![CDATA[' . $attribute['text'] . ']]></param>' . PHP_EOL;
					}
				}
			}				

			$weight = $this->weight->convert($product['dimensions']['weight'], $product['dimensions']['weight_class_id'], $this->currentWeightClassID);			
			if ($weight) {
				$this->yml .= '	<weight>' . (float)$weight . '</weight>' . PHP_EOL;													
			}

			$length = $this->length->convert($product['dimensions']['length'], $product['dimensions']['length_class_id'], $this->currentLengthClassID);
			$width = $this->length->convert($product['dimensions']['width'], $product['dimensions']['length_class_id'], $this->currentLengthClassID);
			$height = $this->length->convert($product['dimensions']['height'], $product['dimensions']['length_class_id'], $this->currentLengthClassID);

			if ($length > 0 && $width > 0 && $height > 0) {					
				$this->yml .= '	<dimensions>' . (float)$length . '/' . (float)$width . '/' . (float)$height . '</dimensions>' . PHP_EOL;	
			}

			$this->yml .= '</offer>' . PHP_EOL;
		}

		$this->yml .= '</offers>' . PHP_EOL;

		echoLine('[YML] Добавили товары');

		return $this;
	}

	private function addPricevaOffers(){

		$this->yml .= '<offers>' . PHP_EOL;

		foreach ($this->products as $product){
			$product_id = $product['product_id'];				
			$this->yml .= '<offer id="' . $product_id . '" available="' . $product['available'] . '">' . PHP_EOL;

			$this->yml .= '	<url>' . $this->prepareField($this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</url>' . PHP_EOL;

			$this->yml .= '	<name><![CDATA[' 		. $this->prepareField($product['name']) . ']]></name>' . PHP_EOL;
			$this->yml .= '	<vendor><![CDATA[' 		. $this->prepareField($product['manufacturer']) . ']]></vendor>' . PHP_EOL;
			$this->yml .= '	<vendorCode><![CDATA[' 	. $this->prepareField($product['sku']) . ']]></vendorCode>' . PHP_EOL;
			$this->yml .= '	<model><![CDATA[' 		. $this->prepareField($product['model']) . ']]></model>' . PHP_EOL;				
			$this->yml .= '	<categoryId>' . $product['main_category_id'] . '</categoryId>' . PHP_EOL;

			$this->yml .= '	<currencyId>' 	. $this->config->get('config_regional_currency') . '</currencyId>' . PHP_EOL;								

			if ($product['special'] && $product['special'] < $product['price']){
				$this->yml .= '	<price>' 	. $this->format($product['special']) . '</price>' . PHP_EOL;
				$this->yml .= '	<oldprice>' 	. $this->format($product['price']) . '</oldprice>' . PHP_EOL;
			} else {
				$this->yml .= '	<price>' 	. $this->format($product['price']) . '</price>' . PHP_EOL;
			}

			if ($product['mpp_price'] > 0){
				$this->yml .= '<repricingMin><![CDATA[' . $this->currency->format($product['mpp_price'], '', '', false) . ']]></repricingMin>' . PHP_EOL;
			}

			$this->yml .= '<hasRRP>' . (int)$product['has_rrp'] . '</hasRRP>' . PHP_EOL;				

			$competitorUrls = explode(PHP_EOL, $product['competitors']);

			foreach ($competitorUrls as $competitorUrl){
				if (trim($competitorUrl)){
					$this->yml .= '<competitorUrl><![CDATA[' . atrim($competitorUrl) . ']]></competitorUrl>' . PHP_EOL;	
				}
			}	

			$this->yml .= '<stockQuantity>' . (int)$product[$this->config->get('config_warehouse_identifier')] . '</stockQuantity>' . PHP_EOL;

			//	$this->yml .= '<tags>' . PHP_EOL;
			$this->yml .= 	'<stock>' . (int)($product[$this->config->get('config_warehouse_identifier')] > 0) . '</stock>' . PHP_EOL;				
			//	$this->yml .= '</tags>'. PHP_EOL;

			if ($product['attributes']){
				foreach ($product['attributes'] as $attribute){
					$attribute = $this->detectUnits($attribute);

					if (!empty($attribute['unit'])){
						$this->yml .= '	<param name="' . $attribute['name'] . '" unit="' . $attribute['unit'] . '"><![CDATA[' . $attribute['text'] . ']]></param>' . PHP_EOL;
					} else {
						$this->yml .= '	<param name="' . $attribute['name'] . '"><![CDATA[' . $attribute['text'] . ']]></param>' . PHP_EOL;
					}
				}
			}				

			$this->yml .= '</offer>' . PHP_EOL;
		}

		$this->yml .= '</offers>' . PHP_EOL;

		echoLine('[YML] Добавили товары');

		return $this;
	}

	private function writeFeed($path){
		$path = str_replace('{store_id}', $this->config->get('config_store_id'), $path);

		if ($this->type == 'market' && $this->config->get('config_yam_offer_id_prefix_enable') && $this->config->get('config_yam_offer_id_prefix')){
			$path = str_replace('{yam_prefix}', mb_strtolower($this->config->get('config_yam_offer_id_prefix')), $path);
		} else {
			$path = str_replace('_{yam_prefix}', '', $path);
			$path = str_replace('{yam_prefix}', '', $path);
		}

		$path = DIR_REFEEDS . $path;
		$dir = dirname($path);

		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}

		$file = fopen($path, 'w+');
		fwrite($file, $this->yml);
		fclose($file);

		echoLine('[YML] Записали файл ' . $path);
	}

	private function setProducts($products){
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$this->products = array();

		echoLine('[YML] Получаем товары из БД');

		$counter = 0;
		$total = count($products);

		echoLine('[YML] Всего ' . $total);

		$invalidCategories = [];

		foreach ($products as $product){										
			$counter++;
			if ($counter % 100 == 0){
				echo($counter.'..');
			}

			$product_id = $product['product_id'];		

			$this->products[$product_id] = $this->model_catalog_product->getProduct($product['product_id']);

			if ($this->type == 'market' && $this->config->get('config_yam_offer_id_prefix_enable') && $this->config->get('config_yam_offer_id_prefix')){
				if (!$this->products[$product_id]['yam_product_id']){
					$this->products[$product_id]['yam_product_id'] = $this->config->get('config_yam_offer_id_prefix') . $product_id;
				}
			}

			if ($this->type == 'priceva'){
				$this->products[$product_id]['available']	= ($this->products[$product_id]['quantity'] > 0)?'true':'false';
			} else {
				$this->products[$product_id]['available']	= ($this->products[$product_id]['quantity'] > 0)?'true':'false';
			}

			$this->products[$product_id]['attributes'] 	= $this->getProductAttributes($product['product_id']);

			if (in_array($this->products[$product_id]['main_category_id'], $this->excluded_categories)){
				$this->products[$product_id]['main_category_id'] = $this->getCategory($product['product_id']);
			}

			if ($this->config->get('config_yam_enable_category_tree')){
				if (!empty($this->yandexCategoriesMapping[$this->products[$product_id]['main_category_id']])){
					$this->products[$product_id]['main_category_id'] = $this->yandexCategoriesMapping[$this->products[$product_id]['main_category_id']];
				} else {				
				//	echoLine('[YML] No yandex category mapping: ' . $this->products[$product_id]['main_category_id'], 'e');
					$invalidCategories[$this->products[$product_id]['main_category_id']] = $this->products[$product_id]['main_category_id'];
				}
			}				

			//Fallback
			if (empty($this->products[$product_id]['main_category_id']) || $this->products[$product_id]['main_category_id'] == '0'){
				$this->products[$product_id]['main_category_id'] = $this->defaultYandexCategory;
			}

			if (!empty($this->oldLogicCompetitorFieldMapping[$this->config->get('config_store_id')])){
				$this->products[$product_id]['competitors'] = $this->products[$product_id][$this->oldLogicCompetitorFieldMapping[$this->config->get('config_store_id')]];
			}

			$this->products[$product_id]['dimensions'] 	= $this->getProductDimensions($this->products[$product_id]);
			$this->products[$product_id]['pickup']		= (in_array($this->config->get('config_store_id'), $this->pickup_available));				
			$this->products[$product_id]['images'] 		= [$this->products[$product_id]['image']];

			if (!empty($this->images[$product_id])){
				$this->products[$product_id]['images'] = array_merge($this->products[$product_id]['images'], $this->images[$product_id]);
			}

			$this->products[$product_id]['resized_images'] = [];
			foreach ($this->products[$product_id]['images'] as $image){
				$this->products[$product_id]['resized_images'][] = $this->model_tool_image->resize($image, 700, 700);
			}
		}

		echoLine('[YML] INVALID CATEGORIES WITHOUT MAPPING: ' . implode(',' , $invalidCategories), 'e');

		echoLine('');
		echoLine('[YML] Получили товары');

		return $this;
	}

	private function getProductAttributes($product_id) {

		$query = $this->db->query("SELECT pa.attribute_id, pa.text, ad.name
			FROM product_attribute pa
			LEFT JOIN attribute_description ad ON (pa.attribute_id = ad.attribute_id)
			WHERE pa.product_id = '" . (int)$product_id . "'
			AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY pa.attribute_id");

		return $query->rows;
	}

	private function setProductImages() {
		$query = $this->db->query("SELECT product_id, image FROM product_image ORDER BY product_id");
		$this->images = array();

		foreach($query->rows as $row) {
			if (!isset($this->images[$row['product_id']])) {
				$this->images[$row['product_id']] = array();
			}
			if (count($this->images[$row['product_id']]) < 9)
				$this->images[$row['product_id']][] = $row['image'];
		}

		echoLine('[YML] Записали массив картинок');

		return $this;
	}

	public function flushCache(){			
		echoLine('[YML] Сбрасываем кэш');
		$this->cache->flush();	

		return $this;
	}

	public function updateOzonSetAllNotInFeed(){
		$this->db->query("UPDATE product SET ozon_in_feed = '0' WHERE 1");

		return $this;
	}

	public function updateOzonInFeed($product_id){
		$this->db->query("UPDATE product SET ozon_in_feed = '1' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateYandexMarketSetAllNotInFeed(){
		$this->db->query("UPDATE product SET yam_in_feed = '0' WHERE 1");

		return $this;
	}

	public function updateYandexMarketInFeed($product_id){
		$this->db->query("UPDATE product SET yam_in_feed = '1' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateVkontakteSetAllNotInFeed(){
		$this->db->query("UPDATE product SET vk_in_feed = '0' WHERE 1");

		return $this;
	}

	public function updateVkontakteInFeed($product_id){
		$this->db->query("UPDATE product SET vk_in_feed = '1' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function updateYandexMarketPriceNational($product_id, $price, $special){

		$this->db->query("UPDATE product SET  
			yam_price 			= '" . $price . "',
			yam_currency		= '" . $this->config->get('config_regional_currency') . "'
			WHERE product_id 	= '" . (int)$product_id . "'"								
		);

		$this->db->query("UPDATE product SET  
			yam_special 			= '" . (float)$special . "'
			WHERE product_id 	= '" . (int)$product_id . "'"								
		);

		$this->db->query("DELETE FROM product_price_national_to_yam WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . $this->config->get('config_store_id') . "'");

		$this->db->query("INSERT IGNORE INTO product_price_national_to_yam SET 
			product_id 			= '" . (int)$product_id . "',
			store_id 			= '" . $this->config->get('config_store_id') . "',
			price 				= '" . (float)$price . "',
			currency 			= '" . $this->config->get('config_regional_currency') . "',
			dot_not_overload_1c 	= '0',
			settled_from_1c 		= '0'");	
	}

	public function recalculatePrice($price, $percent){

		return round(($price + (($price/100) * $percent)));
	}

	public function updateYandexMarketPrices($products){
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$this->products = array();

		echoLine('[YML] Сейчас мы будем обновлять цены на Маркете');

		echoLine('[YML] Общий процент наценки: ' . $this->config->get('config_yam_plus_percent'));

		$counter = 0;
		$total = count($products);

		echoLine('[YML] Всего ' . $total);

		foreach ($products as $product){										
			$counter++;
			if ($counter % 100 == 0){
					//	echo($counter.'..');
			}

			$product = $this->model_catalog_product->getProduct($product['product_id']);

			$price = $product['price'];
			if (!$this->config->get('config_yam_fuck_specials') && $product['special']){
				$price = $product['special'];
			}

				//$value, $from, $to, $real = false, $round = true
			$price = $this->currency->convert($price, $this->config->get('config_currency'), $this->config->get('config_regional_currency'), false, true);
			$percent = $this->config->get('config_yam_plus_percent');
			if ((float)$product['yam_percent'] <> 0){
				$percent = $product['yam_percent'];
			}

			$newPrice = $this->recalculatePrice($price, $percent);

			$newSpecial = false;

			$special_percent = false;
			if ($this->config->get('config_yam_enable_plus_for_main_price')){					
				$special_percent = $this->config->get('config_yam_plus_for_main_price');								
			}

			if ((float)$product['yam_special_percent'] <> 0){
				$special_percent = $product['yam_special_percent'];
			}	

			if ((float)$special_percent > 0){
				$newSpecial = $this->recalculatePrice($newPrice, (-1) * $special_percent);
			}

			if ((float)$special_percent < 0){
				$newSpecial = $this->recalculatePrice($newPrice, $special_percent);
			}

			echoLine($product['product_id'] . ':' . $price . ' -> ' . $newPrice . ' -> ' . $newSpecial);

			$this->updateYandexMarketPriceNational($product['product_id'], $newPrice, $newSpecial);
		}

		return $this;
	}

	public function makeStockFeedQuery(){			
		$sql = "SELECT DISTINCT(p.product_id) FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
		LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE 
		p.`". $this->config->get('config_warehouse_identifier') ."` > 0 
		AND p.status = 1 
		AND p.quantity > 0
		AND p.is_virtual = 0
		AND p.is_markdown = 0
		AND p.yam_disable = 0
		AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND p2s.store_id = '" . $this->config->get('config_store_id') . "'";

		if ($this->excluded_names){
			$sql .= " AND NOT (";

			$_OR = [];
			foreach ($this->excluded_names as $excluded_name){
				$_OR[] = "LOWER(pd.name) LIKE '%" . $excluded_name . "%'";
			}										

			$sql .= implode(' OR ' , $_OR);					
			$sql .= ")";

		}

		$products = $this->db->query($sql);	

		return $products;
	}

	public function makeStockFeedQueryWithManufacturerExclusion(){			
		$sql = "SELECT DISTINCT(p.product_id) FROM product p 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
		LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE 
		p.`". $this->config->get('config_warehouse_identifier') ."` > 0 
		AND p.status = 1 
		AND p.quantity > 0
		AND p.is_virtual = 0
		AND p.is_markdown = 0
		AND p.yam_disable = 0
		AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND p2s.store_id = '" . $this->config->get('config_store_id') . "'";

		if ($this->excluded_names){
			$sql .= " AND NOT (";

			$_OR = [];
			foreach ($this->excluded_names as $excluded_name){
				$_OR[] = "LOWER(pd.name) LIKE '%" . $excluded_name . "%'";
			}										

			$sql .= implode(' OR ' , $_OR);					
			$sql .= ")";

		}

		if ($this->excludeYandexManufacturers){
			$sql .= " AND p.manufacturer_id NOT IN (";
			$sql .= implode(',', $this->excludeYandexManufacturers);
			$sql .= ")";
		}

		$products = $this->db->query($sql);	

		return $products;
	}

	public function makeStockFeed(){
		ini_set('memory_limit', '2G');
		$stores = [0,2];

		foreach ($stores as $store_id){

			if ($store_id == 0){
				$this->updateYandexMarketSetAllNotInFeed();				
			}

			echoLine('[YML] ' . $store_id);

			$this->setProductImages()->cacheCategoryDimensions();
			$this->loadSettings($store_id)->openYML()->addShop()->addCategories()->setFeedType('market');

			$products = $this->makeStockFeedQueryWithManufacturerExclusion();

			if (!$this->config->get('config_yam_enable_sync_from_1c') && $this->config->get('config_yam_enable_plus_percent') && (float)$this->config->get('config_yam_plus_percent') <> 0){
				$this->updateYandexMarketPrices($products->rows)->flushCache();
			}

			$this->setProducts($products->rows)->addOffers();
			$this->closeYML()->writeFeed($this->stock_path[0]);

			echoLine('[DIRECT YML] ' . $store_id);
			$products = $this->makeStockFeedQuery();
			$this->loadSettings($store_id)->openYML()->addShop()->addCategories()->setFeedType('yandex');										
			$this->setProducts($products->rows)->addOffers();
			$this->closeYML()->writeFeed($this->direct_stock_path[0]);


			if ($store_id == 0){
				try {
					$baseClient = new \Yandex\Market\Partner\Clients\BaseClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
					$campaignID = $baseClient->getCampaigns(['page' => 0])->getCampaigns()->current()->getId();			

					$assortmentClient = new \Yandex\Market\Partner\Clients\AssortmentClient($this->config->get('config_yam_yandexOauthID'), $this->config->get('config_yam_yandexAccessToken'));
					$feedID = $assortmentClient->getFeeds($campaignID)->current()->getId();

					echoLine('[YML] Обновление фида ' . $feedID . ' в ' . $campaignID, 'i');

					$assortmentClient->refreshFeed($campaignID, $feedID);
				} catch (\Yandex\Market\Partner\Exception\PartnerRequestException $e){
					echoLine('[YML] YAM API ERROR: ' . $e->getMessage(), 'e');
				}
			}

		}
	}

	public function makeFullFeed(){
		ini_set('memory_limit', '2G');

		if ($this->config->get('config_single_store_enable')){
			$stores = [0];
		} else {
			$stores = [0,2];
		}
		
		foreach ($stores as $store_id){
			echoLine('[ControllerFeedYandexKP::makeFullFeed] Working in store:' . $store_id, 'i');

			$this->setProductImages()->cacheCategoryDimensions();
			$this->loadSettings($store_id)->openYML()->addShop()->addCategories();

			$sql = "SELECT DISTINCT(p.product_id) FROM product p 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE 
			p.status = 1 
			AND p.stock_status_id NOT IN (" . $this->config->get('config_not_in_stock_status_id') . ")
			AND p.quantity > 0
			AND p.is_virtual = 0
			AND p.is_markdown = 0
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND p2s.store_id = '" . $this->config->get('config_store_id') . "'";

			$products = $this->db->query($sql);	
			$this->setProducts($products->rows)->addOffers();

			$this->closeYML()->writeFeed($this->full_path[0]);				
		}
	}

	public function makePricevaFeeds(){
		if ($this->config->get('config_priceva_enable_api') || $this->config->get('config_pricecontrol_enable_api')){
			ini_set('memory_limit', '2G');
			$stores = [0];
			$this->setProductImages();

			$feedsQuery = $this->db->query("SELECT manufacturer_id, priceva_feed FROM manufacturer WHERE priceva_enable = 1 AND TRIM(priceva_feed) <> ''");
			$feedsArray = array();

			foreach ($feedsQuery->rows as $feed){
				if (empty($feedsArray[$feed['priceva_feed']])){
					$feedsArray[$feed['priceva_feed']] = array($feed['manufacturer_id']);
				} else {
					$feedsArray[$feed['priceva_feed']][] = $feed['manufacturer_id'];
				}
			}

			foreach ($feedsArray as $pricevaFeed => $pricevaManufacturers){	

				foreach ($stores as $store_id){
					echoLine('[PRICEVA] ' . implode(',', $pricevaManufacturers) . ' ' .  $store_id);
					$this->loadSettings($store_id)->openYML()->addShop()->addCategories()->setFeedType('priceva');

					$sql = "SELECT DISTINCT(p.product_id) FROM product p 
					LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
					LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
					WHERE 
					p.status = 1 
					AND p.is_virtual = 0
					AND p.is_markdown = 0
					AND p.priceva_disable <> 1
					AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
					AND p2s.store_id = '" . $this->config->get('config_store_id') . "'";
					$sql .= " AND p.manufacturer_id IN (". implode(',', $pricevaManufacturers) .")";
					$sql .= " AND (p.product_id IN (SELECT product_id FROM category_path cp LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id) WHERE cp.path_id IN (SELECT category_id FROM category WHERE priceva_enable = 1)) OR p.priceva_enable = 1)";

					$products = $this->db->query($sql);	

					$this->setProducts($products->rows)->addPricevaOffers();

					if ($this->config->get('config_priceva_directory_name')){
						$this->closeYML()->writeFeed(str_replace(['{brand}', '{dir}'], [$pricevaFeed, $this->config->get('config_priceva_directory_name')], $this->priceva_path[0]));
					} else {
						$this->closeYML()->writeFeed(str_replace(['{brand}'], [$pricevaFeed], $this->priceva_path[0]));
					}					
				}
			}
		}
	}

	public function makeOzonFeed(){
		ini_set('memory_limit', '2G');
		$stores = [0];

		foreach ($stores as $store_id){
			echoLine('[ControllerFeedYandexKP::makeOzonFeed] Working in store:' . $store_id, 'i');

			if ($store_id == 0){
				$this->updateOzonSetAllNotInFeed();
			}			

			$this->setProductImages()->cacheCategoryDimensions();
			$this->loadSettings($store_id)->openYML()->addShop()->addCategories()->setFeedType('ozon');

			$sql = "SELECT DISTINCT(p.product_id) FROM product p 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE 
			p.`". $this->config->get('config_warehouse_identifier') ."` > 0 
			AND p.status = 1 
			AND p.quantity > 0
			AND p.is_virtual = 0
			AND p.is_markdown = 0
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND p2s.store_id = '" . $this->config->get('config_store_id') . "'";
			
			if ($this->excludeOzonManufacturers){
				$sql .= " AND p.manufacturer_id NOT IN (";
				$sql .= implode(',', $this->excludeOzonManufacturers);
				$sql .= ")";
			}
			
			$products = $this->db->query($sql);	

			if ($this->config->get('config_ozon_enable_price_yam') && !$this->config->get('config_yam_enable_sync_from_1c') && $this->config->get('config_yam_enable_plus_percent') && (float)$this->config->get('config_yam_plus_percent') <> 0){
				echoLine('[ControllerFeedYandexKP::makeOzonFeed] Включена настройка использования логики Маркета. Запускаем формирование.', 'w');
				$this->updateYandexMarketPrices($products->rows)->flushCache();
			}
			
			$this->setProducts($products->rows)->addOffers();			
			$this->closeYML()->writeFeed($this->ozon_full_path[0]);

			$this->openYML()->addShop()->setProducts($products->rows)->addOffersOzonTruncated();
			$this->closeYML()->writeFeed($this->ozon_path[0]);			
		}
	}		

	public function makeVkontakteFeed(){
		if (!$this->config->get('config_vk_enable_pixel')){
			echoLine('[ControllerFeedYandexKP::makeVkontakteFeed] VK Pixel is disabled in settings!');
			exit();
		}		

		ini_set('memory_limit', '2G');

		if ($this->config->get('config_single_store_enable')){
			$stores = [0];
		} else {
			$stores = [0];
		}
		
		foreach ($stores as $store_id){
			echoLine('[ControllerFeedYandexKP::makeVkontakteFeed] Working in store:' . $store_id, 'i');

			if ($store_id == 0){
				$this->updateVkontakteSetAllNotInFeed();
			}

			$this->setProductImages()->cacheCategoryDimensions();
			$this->loadSettings($store_id)->openYML()->addShop()->addCategories()->setFeedType('vkontakte');

			$sql = "SELECT DISTINCT(p.product_id) FROM product p 
				LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
				LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
				WHERE 
				p.status = 1 ";
			
			if ($this->config->get('config_vk_feed_only_in_stock')){
				$sql .= "AND p.stock_status_id NOT IN (" . $this->config->get('config_not_in_stock_status_id') . ", " . $this->config->get('config_partly_in_stock_status_id') . ")";
			}			

			$sql .= "AND p.is_virtual = 0
			AND p.is_markdown = 0
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND p2s.store_id = '" . $this->config->get('config_store_id') . "'";

			if (!empty($this->config->get('config_vk_feed_include_manufacturers')) && is_array($this->config->get('config_vk_feed_include_manufacturers'))){
				$sql .= " AND p.manufacturer_id IN (" . implode(',', $this->config->get('config_vk_feed_include_manufacturers')) . ")";
			}		

			$products = $this->db->query($sql);	
			$this->setProducts($products->rows)->addOffers();

			$this->closeYML()->writeFeed($this->vk_full_path[0]);				
		}
	}
}																																														
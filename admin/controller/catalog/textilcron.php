<?php
	class ControllerCatalogTextilCron extends Controller {
		private $_log;
		
		function smallEditProduct($product_id, $data){
			
			
			
			
		}
		
		function copyReviews(){
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			
		}
		
		
		function editProductOptions($product_id, $data){
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_option'])) {
				
				//	print_r($data['product_option']);
				
				foreach ($data['product_option'] as $product_option) {
					
					if (isset($product_option['type']) && ($product_option['type'] == 'select' || $product_option['type'] == 'block' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image')) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
						
						$product_option_id = $this->db->getLastId();
						
						echo '      >> добавлена опция в товар: '. $product_option_id . $end_of_line;
						$this->_log->write('      >> добавлена опция в товар: '. $product_option_id);
						
						$i = 0;
						if (isset($product_option['product_option_value'])  && count($product_option['product_option_value']) > 0 ) {
							foreach ($product_option['product_option_value'] as $product_option_value) {
								
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (($this->config->get('rewardpoints_currency_mode')) ? $product_option_value['points'] : (int)$product_option_value['points']) . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "', this_is_product_id = '" . (int)$product_option_value['this_is_product_id'] . "'");														
								
								//Q: Options Boost
								$ob_pov_id = $this->db->getLastId();
								
								if(isset($product_option_value['ob_sku'])) { $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET ob_sku = '" . $this->db->escape($product_option_value['ob_sku']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_info'])) { $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET ob_info = '" . $this->db->escape($product_option_value['ob_info']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_image'])) { $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET ob_image = '" . $this->db->escape($product_option_value['ob_image']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								if(isset($product_option_value['ob_sku_override'])) { $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET ob_sku_override = '" . $this->db->escape($product_option_value['ob_sku_override']) . "' WHERE product_option_value_id = '" . (int)$ob_pov_id . "'"); }
								unset($ob_pov_id);
								//
								
								$i++;
								
							}
							}else{
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
						}
						} else { 
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
					}
					
					echo '      >> добавленo '. $i .' значений опции: '. $product_option_id . $end_of_line;
					$this->_log->write('      >> добавленo '. $i .' значений опции: '. $product_option_id);
				}
			}
			
			
		}
		
		function copyProductAttributes($child_product_id, $main_product_id,  $excluded_attributes){
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			$this->load->model('catalog/product');
			
			$data['product_attribute'] = $this->model_catalog_product->getProductAttributes($child_product_id);
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$main_product_id . "'");
			
			if (!empty($data['product_attribute'])) {
				foreach ($data['product_attribute'] as $product_attribute) {
					if ($product_attribute['attribute_id']) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$main_product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
						
						foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {	
							
							if (!in_array($product_attribute['attribute_id'], $excluded_attributes)){					
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$main_product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
								
								if ($language_id == 2){
									echo '      >> добавлен атрибут: '. $product_attribute['attribute_id'] .' : ' . $product_attribute_description['text'] . $end_of_line;
								}
							}
						}
					}
				}
			}
			
		}
		
		public function copyCategories($child_product_id, $main_product_id){
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			$this->load->model('catalog/product');
			
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" .(int)$main_product_id. "'");
			
			$data['product_category'] = $this->model_catalog_product->getProductCategories($child_product_id);
			
			$out = array();
			if (isset($data['product_category'])) {
				foreach ($data['product_category'] as $category_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$main_product_id . "', category_id = '" . (int)$category_id . "'");
					$out[] = (int)$category_id;
				}
				echo '       >> категории:'.implode(', ',$out).$end_of_line;
			}
		}
		
		public function copyImages($child_product_id, $main_product_id){
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			$this->load->model('catalog/product');
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$main_product_id . "'");
			
			$data['product_image'] = $this->model_catalog_product->getProductImages($child_product_id);
			
			$out = array();
			$i = 0;
			if (isset($data['product_image'])) {
				foreach ($data['product_image'] as $product_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$main_product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
					$i++;
				}
			}
			echo '       >> картинки: '.$i.' штук.'.$end_of_line;
		}
		
		public function copyPrices($child_product_id, $main_product_id){				
			$this->load->model('catalog/product');
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			
			
			//ЦЕНА НА МАГАЗИН
			$this->db->query("DELETE FROM product_price_to_store WHERE product_id = '" .(int)$main_product_id. "'");
			
			$data['product_price_to_store'] = $this->model_catalog_product->getProductStorePricesAll($child_product_id);
			
			$out = array();
			if (isset($data['product_price_to_store'])) {
				foreach ($data['product_price_to_store'] as $price_to_store) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_price_to_store SET product_id = '" . (int)$main_product_id . "', store_id = '" . (int)$price_to_store['store_id'] . "' price = '" . (int)$price_to_store['price'] . "'");
					$out[] = (int)$price_to_store['store_id'];
				}
				echo '       >> цены на магазины:'.implode(', ',$out).$end_of_line;
			}
			
			
			//СКИДКИ
			$this->db->query("DELETE FROM product_special WHERE product_id = '" .(int)$main_product_id. "'");
			
			$data['product_special'] = $this->model_catalog_product->getProductSpecials($child_product_id);
			
			$out = array();
			if (isset($data['product_special'])) {
				foreach ($data['product_special'] as $product_special) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$main_product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', store_id = '" . (int)$product_special['store_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
					$out[] = (int)$product_special['customer_group_id'];
				}
				echo '       >> скидки на группы:'.implode(', ',$out).$end_of_line;
			}
			
			
			//ДИСКАУНТ
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$main_product_id . "'");
			
			$data['product_discount'] = $this->model_catalog_product->getProductDiscounts($child_product_id);
			
			$out = array();
			if (isset($data['product_discount'])) {
				foreach ($data['product_discount'] as $product_discount) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$main_product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
					$out[] = (int)$product_discount['customer_group_id'];
				}
				echo '       >> дискаунт на группы:'.implode(', ',$out).$end_of_line;
			}
			
		}
		
		
		public function copyPricesForOptions ($main_product_id){				
			$this->load->model('catalog/product');
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			//Цена
			$min_price = $this->db->query("SELECT MIN(price) as price FROM product WHERE product_id IN (SELECT DISTINCT this_is_product_id FROM product_option_value WHERE product_id = '" . (int)$main_product_id . "')");
			if ($min_price->num_rows && $min_price->row['price'] && $min_price->row['price'] > 0){
				$this->db->query("UPDATE product SET price = '" . $min_price->row['price'] . "' WHERE product_id = '" . $main_product_id . "'");
				echo '       >> обновлена цена: ' . $min_price->row['price'].$end_of_line;;
			}
			
			
			//Выбираем все связки группы
			$all_specials = $this->db->query("SELECT product_special_id, customer_group_id, store_id, MIN(price) as price FROM product_special WHERE product_id IN (SELECT DISTINCT this_is_product_id FROM product_option_value WHERE product_id = '" . (int)$main_product_id . "') GROUP BY store_id");
						
			if ($all_specials->num_rows){
									
				$this->db->query("DELETE FROM product_special WHERE product_id = '" .(int)$main_product_id. "'");			
				
				foreach ($all_specials->rows as $group){
					$special = $this->db->query("SELECT * FROM product_special WHERE product_special_id = '" . $group['product_special_id'] . "' LIMIT 1");									
					
					if ($special->num_rows){
						$product_special = $special->row;
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$main_product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', store_id = '" . (int)$product_special['store_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
						echo '       >> обновлена скидка для магазина '. $product_special['store_id'] .': ' . $product_special['price'].$end_of_line;;
					}
				}
				
			}
		
						
			
		}
		
		function editProduct($product_id, $data){
			
			$sql = "UPDATE " . DB_PREFIX . "product SET
			model = '" . $this->db->escape($data['model']) . "',
			image = '" . $this->db->escape($data['image']) . "', 
			short_name = '" . $this->db->escape($data['short_name']) . "',
			sku = '" . $this->db->escape($data['sku']) . "', 
			upc = '" . $this->db->escape($data['upc']) . "', 
			ean = '" . $this->db->escape($data['ean']) . "', 
			jan = '" . $this->db->escape($data['jan']) . "', 
			isbn = '" . $this->db->escape($data['isbn']) . "', 
			mpn = '" . $this->db->escape($data['mpn']) . "', 
			asin = '" . $this->db->escape($data['asin']) . "',";
			
			if ($this->db->escape($data['color_group'] && mb_strlen($this->db->escape($data['color_group'])) > 0)){
				$sql .= "color_group = '" .  $this->db->escape($data['color_group']) . "',";
				} else {
				$sql .= "color_group = color_group,";
			}
			
			$sql .= "location = '" . $this->db->escape($data['location']) . "', 
			source = '" . $this->db->escape($data['source']) . "', 
			quantity = '" . (int)$data['quantity'] . "', 
			minimum = '" . (int)$data['minimum'] . "', 
			subtract = '" . (int)$data['subtract'] . "', 
			stock_status_id = '" . (int)$data['stock_status_id'] . "', 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			date_added = '" . $this->db->escape($data['date_added']) . "', 
			manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
			collection_id = '" . (int)$data['collection_id'] . "', 
			shipping = '" . (int)$data['shipping'] . "', 
			price = '" . (float)$data['price'] . "', 
			cost = '" . (float)$data['cost'] . "', 
			special_cost = '" . (float)$data['special_cost'] . "', 
			price_national = '" . (float)$data['price_national'] . "', 
			currency = '" . $this->db->escape($data['currency']) . "',  
			points = '" . (int)$data['points'] . "', 
			weight = '" . (float)$data['weight'] . "', 
			weight_class_id = '" . (int)$data['weight_class_id'] . "', 
			length = '" . (float)$data['length'] . "', 
			width = '" . (float)$data['width'] . "', 
			height = '" . (float)$data['height'] . "', 
			length_class_id = '" . (int)$data['length_class_id'] . "', 
			status = '" . (int)$data['status'] . "', 
			tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			quantity_stock = '" . (int)$data['quantity_stock'] . "', 
			quantity_stockM = '" . (int)$data['quantity_stockM'] . "', 
			quantity_stockK = '" . (int)$data['quantity_stockK'] . "', 
			quantity_stockMN = '" . (int)$data['quantity_stockMN'] . "', 
			quantity_stockAS = '" . (int)$data['quantity_stockAS'] . "',
			tnved = '" . $data['tnved'] . "', 
			ignore_parse = '" . (int)$data['ignore_parse'] . "', 
			new = '" . (int)$data['new'] . "',
			is_virtual = '" .(int)$data['is_virtual']. "',
			is_option_with_id = '" . (int)$data['is_option_with_id'] . "', 
			date_modified = NOW() 
			WHERE product_id = '" . (int)$product_id . "'";
			
			$this->db->query($sql);
			
			foreach ($data['product_description'] as $language_id => $value) {
			
				$this->db->query("UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($value['name']) . "', seo_h1 = '" . $this->db->escape($value['name']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id  . "'");				
							
			}
			
			return $product_id;
			
		}
		
		function createOrEditProduct($isbn, $child_product, $product_id = false){
			$this->load->model('catalog/product');
			$this->load->model('localisation/language');
			$this->load->model('setting/store');
			
			$_stores = $this->model_setting_store->getStores();
			
			$_product_stores = array();
			$_product_stores[] = 0;
			foreach ($_stores as $_store){
				$_product_stores[] = $_store['store_id'];
			}
			
			$_languages = $this->model_localisation_language->getLanguages();
			
			$_product_descriptions = array();
			foreach ($_languages as $_language){
				$_product_descriptions[$_language['language_id']] = array(
				'name'  			=> $isbn,
				'name_of_option' 	=> '',
				'meta_keyword' 		=> '',
				'seo_title'    		=> '',
				'meta_description' 	=> '',
				'description'    	=> '',
				'tag'				=> ''
				);						
			}
			
			$_model = preg_replace("/[^a-z]/i", "", $isbn);
			
			$_data = array(
			'product_id'		  	=> $product_id,
			'model' 			  	=> $_model,
			'short_name'          	=> $isbn,
			'sku'				  	=> $_model,
			'upc'                 	=> '',
			'ean'				  	=> '',
			'jan'				  	=> '',
			'isbn'					=> '',
			'mpn'					=> $child_product['mpn'],
			'asin'					=> '',
			'tnved'					=> '',
			'color_group'			=> $child_product['color_group'],
			'location'				=> $child_product['location'],
			'source'				=> '',
			'quantity'				=> 9999,
			'minimum'				=> $child_product['minimum'],
			'subtract'				=> 1,
			'stock_status_id'		=> $this->config->get('config_stock_status_id'),
			'date_available'		=> $child_product['date_available'],
			'date_added'			=> $child_product['date_added'],
			'manufacturer_id'       => $child_product['manufacturer_id'],
			'collection_id'			=> $child_product['collection_id'],
			'image'					=> $child_product['image'],
			'shipping'				=> 1,
			'price'					=> $child_product['price'],
			'price_national'		=> $child_product['price_national'],
			'cost'					=> $child_product['cost'],
			'special_cost'			=> $child_product['special_cost'],
			'currency'				=> $child_product['currency'],
			'points'				=> $child_product['points'],
			'points_only_purchase'	=> $child_product['points_only_purchase'],
			'weight'				=> $child_product['weight'],
			'weight_class_id'		=> $child_product['weight_class_id'],
			'length'				=> $child_product['length'],
			'width'					=> $child_product['width'],
			'height'				=> $child_product['height'],
			'length_class_id'		=> $child_product['length_class_id'],
			'status'				=> 1,
			'tax_class_id'			=> 0,
			'sort_order'			=> 0,
			'quantity_stock'		=> 0,
			'quantity_stockM'		=> 0,
			'quantity_stockK'		=> 0,
			'quantity_stockMN'		=> 0,
			'quantity_stockAS'		=> 0,
			'ignore_parse'			=> 1,
			'new'					=> '',
			'is_option_with_id'		=> 0,
			'is_virtual'			=> 1,
			'keyword'				=> false,
			'product_description' 	=> $_product_descriptions,
			'product_store'      	=> $_product_stores
			);
			
			
			if ($product_id){
				return $this->editProduct($product_id, $_data);			
				} else {
				return $this->model_catalog_product->addProduct($_data);
			}
			
		}
		
		function createBigTextiles(){	
			
			if (php_sapi_name() == "cli") {
				$end_of_line = PHP_EOL;
				} else {
				$end_of_line = '<br />';
			}
			
			//Текстовые заглушки опций
			$_options_query = $this->db->query("SELECT option_value_id, name, option_id FROM option_value_description WHERE language_id = 2");
			$lang_array = array(5, 8, 9, 26);
			
			foreach ($_options_query->rows as $_option){			
				echo $_option['name'] . ':';
				foreach ($lang_array as $lang_code){								
					$this->db->query("INSERT INTO option_value_description SET option_value_id = '" . (int)$_option['option_value_id'] . "', option_id = '" . (int)$_option['option_id'] . "', name = '" . $this->db->escape($_option['name']) . "', language_id = '" . $lang_code . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($_option['name']) . "'");
					
					echo $lang_code . ' ';					
				}				
				echo PHP_EOL;
			}

			$this->db->query("UPDATE option_value_description SET name = REPLACE(name, 'х', 'x') WHERE 1");
			
			$this->load->model('catalog/product');
			$this->load->model('localisation/language');
			
			$this->_log = new Log('create_options.txt');
			
			//дозаполняем model, нормализуем ISBN
			$this->db->query("UPDATE product SET model = sku WHERE LENGTH(model) = 0");
			$this->db->query("UPDATE product SET isbn = TRIM(isbn) WHERE 1");
			$this->db->query("UPDATE product SET color_group = TRIM(color_group) WHERE 1");
			
		//	$test_isbn = '%HOME FLOW KNIT GANT';//'Комплект постельного белья антрацит Colours fleuresse';
			
			//Выбираем все ISBN
			$isbn_query = $this->db->query("SELECT DISTINCT isbn FROM product WHERE LENGTH(isbn) > 2");
			
			if ($test_isbn){
				$isbn_query = $this->db->query("SELECT DISTINCT isbn FROM product WHERE LENGTH(isbn) > 2 AND isbn LIKE ('" . $test_isbn . "')");	
			}
			
			if (isset($isbn_query->rows) && $isbn_query->rows){
				
				$full_counter = count($isbn_query->rows);
				$i = 1;
				
				foreach ($isbn_query->rows as $isbn){
					
					echo $i .' из '. $full_counter . ' : Начинаем идент: ' . $isbn['isbn'] . $end_of_line;
					$this->_log->write($i .' из '. $full_counter . ' : Начинаем идент: ' . $isbn['isbn']);
					$i++;
					
					
					//Пытаемся найти хотя бы один товар с исбн и привязкой к родителю
					$isbn_main_product_query = $this->db->query("SELECT is_option_for_product_id FROM product WHERE isbn LIKE ('" . $this->db->escape($isbn['isbn']) . "') AND is_option_for_product_id > 0");
					if ($isbn_main_product_query->row && isset($isbn_main_product_query->row['is_option_for_product_id']) && $isbn_main_product_query->row['is_option_for_product_id']) {
						
						//Товар уже создавался, нужно проверить его существование
						echo '> у идента должен быть главный: ' . $isbn_main_product_query->row['is_option_for_product_id'] . $end_of_line;
						$this->_log->write('> у идента должен быть главный: ' . $isbn_main_product_query->row['is_option_for_product_id']);
						
						$check_main_product_query = $this->db->query("SELECT product_id FROM product WHERE product_id  = '". (int)$isbn_main_product_query->row['is_option_for_product_id'] ."'");
						
						if ($check_main_product_query->row){
							//Основной товар существует, все ок, фиксируем
							echo '> у идента есть существующий главный: ' . $check_main_product_query->row['product_id'] . $end_of_line;
							$this->_log->write('> у идента есть существующий главный: ' . $check_main_product_query->row['product_id']);
							$main_product_id = (int)$check_main_product_query->row['product_id'];
							} else {
							echo '> у идента есть привязка, но главный не существует по id' . $end_of_line;
							$this->_log->write('> у идента есть привязка, но главный не существует по id');
							
							$main_product_id = false;
							
							//пытаемся найти по имени
							
							$check_main_product_query_by_name = $this->db->query("SELECT product_id FROM product_description WHERE name LIKE ('" . $this->db->escape($isbn['isbn']) . "') LIMIT 1");				
							if ($check_main_product_query_by_name->row){
								
								echo '> у идента нет привязки, главный найден по имени ' . $main_product_id . $end_of_line;
								$this->_log->write('> у идента нет привязки, главный найден по имени ' . $main_product_id);
								$main_product_id = $check_main_product_query_by_name->row['product_id'];					
								
								
								} else {
								
								echo '> у идента нет привязки, главный не найден по имени' . $end_of_line;
								$this->_log->write('> у идента нет привязки, главный не найден по имени');
								
								$main_product_id = false;
								
							}
							
						}
						
						} else {
						//Нет ни единой привязки идентификатора к родительскому товару. Можно попробовать найти по имени, а вдруг
						
						$check_main_product_query_by_name = $this->db->query("SELECT product_id FROM product_description WHERE name LIKE ('" . $this->db->escape($isbn['isbn']) . "') LIMIT 1");				
						if ($check_main_product_query_by_name->row){
							
							echo '> у идента нет привязки, главный найден по имени ' . $check_main_product_query_by_name->row['product_id'] . $end_of_line;
							$this->_log->write('> у идента нет привязки, главный найден по имени ' . $check_main_product_query_by_name->row['product_id']);
							$main_product_id = $check_main_product_query_by_name->row['product_id'];					
							
							} else {
							
							echo '> у идента нет привязки, главный не найден по имени'. $end_of_line;
							$this->_log->write('> у идента нет привязки, главный не найден по имени');
							$main_product_id = false;
							
						}
					}
					
					//Создаем либо редактируем товар
					$child_product_query = $this->db->query("SELECT product_id FROM product WHERE isbn LIKE ('" . $this->db->escape($isbn['isbn']) . "') LIMIT 1");
					$child_product = $this->model_catalog_product->getProduct($child_product_query->row['product_id']);
					
					$main_product_id = $this->createOrEditProduct($isbn['isbn'], $child_product, $main_product_id);				
					echo '> главный товар: ' . $main_product_id . $end_of_line;
					$this->_log->write('> главный товар: ' . $main_product_id);
					
					
					//Создание опций
					//Получаем все товары по isbn
					$isbn_product_list_query = $this->db->query("SELECT product_id, is_option_with_id FROM product WHERE isbn LIKE ('" . $this->db->escape($isbn['isbn']) . "')");		
					
					$option_data = array();
					$option_data['product_option'] = array();			
					$option_value_data = array();
					
					foreach ($isbn_product_list_query->rows as $real_product_id){
						echo '     > pid: ' . $real_product_id['product_id'] . $end_of_line;
						$this->_log->write('     > pid: ' . $real_product_id['product_id']);
						if ($real_product_id['is_option_with_id']) {
							
							$real_small_product = $this->model_catalog_product->getProduct($real_product_id['product_id']);
							$real_small_product['descriptions']	= $this->model_catalog_product->getProductDescriptions($real_product_id['product_id']);
							
							//Обновление привязки к главному товару
							$this->db->query("UPDATE product SET is_option_for_product_id = '" . (int)$main_product_id . "' WHERE product_id = '" . (int)$real_product_id['product_id'] . "'");
							
							//Проверим, существует ли такая опция
							$check_option_exist_query = $this->db->query("SELECT * FROM option WHERE option_id = '" . (int)$real_product_id['is_option_with_id'] . "'");
							if ($check_option_exist_query->row && isset($check_option_exist_query->row['option_id'])){
								
								$option_id = (int)$real_product_id['is_option_with_id'];
								
								if (count($option_data['product_option']) == 0) {
									//безусловно добавляем опцию в массив
									$option_data['product_option'][(int)$option_id] = array(
									'product_option_id' 	=> '',
									'option_id' 			=> (int)$option_id,
									'product_id' 			=> (int)$main_product_id,											
									'type' 					=> $check_option_exist_query->row['type'],
									'required' 				=> 1
									);
									
									} else {
									
									foreach ($option_data['product_option'] as $already_exsistent_option){
										
										if (isset($option_data['product_option'][(int)$option_id])){
											//в массиве уже существует обозначение такой вот опции
											} else {
											
											$option_data['product_option'][(int)$option_id] = array(
											'product_option_id' 	=> '',
											'option_id' 			=> (int)$option_id,
											'product_id' 			=> (int)$main_product_id,
											'type' 					=> $check_option_exist_query->row['type'],
											'required' 				=> 1
											);
											
										}
										
									}
								}
								
								//проверим, существует ли значение опции
								$check_option_value_exist_query = $this->db->query("SELECT * FROM option_value_description WHERE option_id = '" . (int)$option_id . "' AND name LIKE '" . $real_small_product['descriptions'][$this->config->get('config_language_id')]['name_of_option'] . "' AND language_id = '" . $this->config->get('config_language_id') . "' LIMIT 1");							
								if ($check_option_value_exist_query->row){
									
									echo '      >> значение опции '. $real_small_product['descriptions'][$this->config->get('config_language_id')]['name_of_option'] .' существует:' . $check_option_value_exist_query->row['option_value_id'] . $end_of_line;
									
									$this->_log->write('      >> значение опции '. $real_small_product['descriptions'][$this->config->get('config_language_id')]['name_of_option'] .' существует:' . $check_option_value_exist_query->row['option_value_id']);
									
									$option_value_id = $check_option_value_exist_query->row['option_value_id'];
									
									} else {
									echo '      >> значение опции не существует, добавляю' . $end_of_line;
									$this->_log->write('      >> значение опции не существует, добавляю');
									
									$this->db->query("INSERT INTO option_value (option_id, image, sort_order) VALUES ('" . (int)$option_id . "', '', '')");
									$option_value_id = $this->db->getLastId();
									
									foreach ($this->model_localisation_language->getLanguages() as $_language){
										if (isset($real_small_product['descriptions'][$_language['language_id']]['name_of_option'])){
											$this->db->query("INSERT INTO option_value_description (option_value_id, language_id, option_id, name) VALUES 
											('" . (int)$option_value_id . "',
											'" . (int)$_language['language_id'] . "',
											'" . (int)$option_id . "',
											'" . $this->db->escape($real_small_product['descriptions'][$_language['language_id']]['name_of_option']) . "')");
											} else {
											echo '      >> значение опции для языка ' . $_language['code'] . ' не указано' . $end_of_line;
											$this->_log->write('      >> значение опции для языка ' . $_language['code'] . ' не указано');
										}								
									}
								}
								
								//формирование массива значений для товара
								$option_data['product_option'][(int)$option_id]['product_option_value'][] = array(
								'product_id' 			  	=> (int)$main_product_id,												
								'option_value_id'			=> (int)$option_value_id,
								'quantity'					=> (int)$real_small_product['quantity'],
								'subtract'					=> '1',
								'price'						=> $real_small_product['price'],
								'price_prefix'				=> '=',
								'points'					=> 0,
								'points_prefix'				=> '=',
								'weight'					=> $real_small_product['weight'],
								'weight_prefix'				=> '+',
								'this_is_product_id'		=> (int)$real_product_id['product_id'],
								//ob options
								'ob_sku'					=> $real_small_product['model'],
								'ob_info'					=> $real_small_product['descriptions'][$this->config->get('config_language_id')]['name'],
								'ob_image'					=> $real_small_product['image'],
								'ob_sku_override'			=> '1',
								);
								
								
								} else {
								
								echo '      >> опция не существует' . $end_of_line;
								$this->_log->write('      >> опция не существует');
							}
							
							
							} else {
							
							echo '      >> нет идентификатора опции' . $end_of_line;
							$this->_log->write('      >> нет идентификатора опции');
							
						}
						
					}
					
					//проверка массива product_option, создание опций товара
					$this->editProductOptions($main_product_id, $option_data);
					//	print_r($option_data);

					//поиск неизменных атрибутов по isbn
					$excluded_attributes = array();
					
					//Фиксированные атрибуты, которые точно изменяются
					$excluded_attributes[] = 2;
					
					$check_excluded_attributes_query = $this->db->query("SELECT attribute_id, COUNT(DISTINCT text) as total FROM `product_attribute` WHERE product_id IN (SELECT DISTINCT product_id FROM product WHERE isbn LIKE ('" . $this->db->escape($isbn['isbn']) . "')) AND language_id = 2 GROUP BY attribute_id");
					
					foreach ($check_excluded_attributes_query->rows as $row){
						if ($row['total'] > 1){
							$excluded_attributes[] = $row['attribute_id'];
						}
					}
					
					echo '   >> работа с атрибутами: исключаем атр.: ' . implode(', ', $excluded_attributes) . $end_of_line;				
					$this->copyProductAttributes($child_product['product_id'], $main_product_id, $excluded_attributes);
					
					echo '   >> работа с категориями' . $end_of_line;	
					$this->copyCategories($child_product['product_id'], $main_product_id);
					
					echo '   >> работа с картинками' . $end_of_line;	
					$this->copyImages($child_product['product_id'], $main_product_id);
					
					echo '   >> работа с ценами' . $end_of_line;	
					$this->copyPrices($child_product['product_id'], $main_product_id);
					
					echo '   >> обновляем минимальную и максимальную цену для главного товара' . $end_of_line;	
					$this->copyPricesForOptions($main_product_id);
				}
				
				
				
			}
			
			//Проверка обновления виртуальных товаров
			echo '' . $end_of_line;
			echo '> Проверка и выравнивание виртуальных товаров' . $end_of_line;
			$this->_log->write('> Проверка и выравнивание виртуальных товаров');
			$check_all_virtual_query = $this->db->query("SELECT DISTINCT product_id FROM product_option_value WHERE this_is_product_id AND this_is_product_id <> 0");
			if ($check_all_virtual_query->rows){
				
				foreach ($check_all_virtual_query->rows as $cavq){
					if ($cavq['product_id'] > 0){
						$this->db->query("UPDATE product SET is_virtual = 1 WHERE product_id = '" . $cavq['product_id'] . "'");
						echo ' >> '. $cavq['product_id'] .' - виртуальный, отметили' . $end_of_line;
						$this->_log->write(' >> '. $cavq['product_id'] .' - виртуальный, отметили');
					}
				}			
			}
			
			//Очистка color_group у дочерних товаров
			echo '' . $end_of_line;
			echo '> Очистка color_group у дочерних товаров' . $end_of_line;
			$this->_log->write('> Очистка color_group у дочерних товаров');
			$check_all_child_products = $this->db->query("SELECT DISTINCT product_id FROM product WHERE is_option_with_id > 0 AND (NOT (color_group LIKE('%_auto')))");
			if ($check_all_child_products->rows){
				
				foreach ($check_all_child_products->rows as $cacp){
					if ($cacp['product_id'] > 0){
						$this->db->query("UPDATE product SET color_group = '' WHERE product_id = '" . $cacp['product_id'] . "'");
						echo ' >> '. $cacp['product_id'] .' - дочерний, очистили color_group' . $end_of_line;
						$this->_log->write(' >> '. $cacp['product_id'] .' - дочерний, очистили color_group');
					}
				}			
			}
			
			//Подмена color_group на адские значения
			echo '' . $end_of_line;
			echo '> Подмена color_group на адские значения' . $end_of_line;
			$this->_log->write('> Подмена color_group на адские значения');
			$rename_all_color_group_query = $this->db->query("SELECT DISTINCT color_group FROM product WHERE (NOT (color_group LIKE('%_auto')) AND (LENGTH(color_group) > 1))");
			if ($rename_all_color_group_query->rows){
				
				foreach ($rename_all_color_group_query->rows as $racgq){
					
					$unique_name = substr(md5($racgq['color_group'].(string)time(). (string)mt_rand(0,1000)), 0,20) . '_auto';				
					$this->db->query("UPDATE product SET color_group = '" . $unique_name . "' WHERE color_group = '" . $racgq['color_group'] . "'");
					echo ' >> Группа '. $racgq['color_group'] .' -> ' . $unique_name . $end_of_line;
					$this->_log->write(' >> Группа '. $racgq['color_group'] .' -> ' . $unique_name);
				}
				
			}
			
			//сбросить кэш
			$this->cache->flush();
		
			
		}
	
		
	}
?>
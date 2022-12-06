<?php
class ModelKPProduct extends Model {
	private $error = array(); 



	public function parseVariantNames($product_id, $language_id, $name){
		$this->load->model('catalog/product');
		$descriptions = $this->model_catalog_product->getProductDescriptions($product_id);
		$currentDescription = $descriptions[$language_id];

		$result = [];

		$new_general_name = $name;

		if (!empty($currentDescription['variant_name'])){
			$new_general_name = str_ireplace($currentDescription['variant_name'] . ',' , '', $new_general_name);
			$new_general_name = str_ireplace($currentDescription['variant_name'], '', $new_general_name);
		}

		if (!empty($currentDescription['variant_name_1'])){
			$new_general_name = str_ireplace($currentDescription['variant_name_1'], '', $new_general_name);
		}

		if (!empty($currentDescription['variant_name_2'])){
			$new_general_name = str_ireplace($currentDescription['variant_name_2'], '', $new_general_name);
		}

		if (!empty($currentDescription['variant_value_1'])){
			$new_general_name = str_ireplace($currentDescription['variant_value_1'], '', $new_general_name);
		}

		if (!empty($currentDescription['variant_value_2'])){
			$new_general_name = str_ireplace($currentDescription['variant_value_2'], '', $new_general_name);
		}

		$new_general_name = str_ireplace(['()', '(,)', '(, )', '( )', '(: , : )', '(:,', ':)', '  '], '', $new_general_name);
		$new_general_name = atrim($new_general_name);

		if (!empty($currentDescription['variant_name_1']) && !empty($currentDescription['variant_value_1'])){
			$new_main_name = $new_general_name . ' (' . $currentDescription['variant_name_1'] . ': ' . $currentDescription['variant_value_1'];
		}

		if (!empty($currentDescription['variant_name_2']) && !empty($currentDescription['variant_value_2'])){
			$new_main_name = $new_main_name . ', ' . $currentDescription['variant_name_2'] . ': ' . $currentDescription['variant_value_2'] . ')';
		} else {
			$new_main_name = $new_main_name . ')';
		}

		if (empty($new_main_name)){
			$new_main_name = $new_general_name;
		}

		$result['main_name'] = atrim($new_main_name);
		$result['variants']	 = [];

		$variants = $this->model_catalog_product->getVariantProducts($product_id, $language_id);

		foreach ($variants as $variant){
			$variant_name = $new_general_name;

			if (!empty($variant['variant_name_1']) && !empty($variant['variant_value_1'])){
				$variant_name = $variant_name . ' (' . $variant['variant_name_1'] . ': ' . $variant['variant_value_1'];
			}

			if (!empty($currentDescription['variant_name_2']) && !empty($currentDescription['variant_value_2'])){
				$variant_name = $variant_name . ', ' . $variant['variant_name_2'] . ': ' . $variant['variant_value_2'] . ')';
			} else {
				$variant_name = $variant_name . ')';
			}

			$result['variants'][] = [
				'product_id' => $variant['product_id'],
				'name'		 => atrim($variant_name)
			];
		}
		
		return $result;

	}


	public function getProductAmazonFullData($asin){
		
		$sql = "SELECT * FROM product_amzn_data WHERE asin = '" . $this->db->escape($asin) . "' LIMIT 1";
		$query = $this->db->query($sql);
		
		if ($query->num_rows){						
			return $query->row;			
		} else {
			return false;
		}		
	}

	public function getProductAmazonOffers($asin){
		$sql = "SELECT * FROM product_amzn_offers WHERE asin = '" . $this->db->escape($asin) . "'";
		$query = $this->db->query($sql);

		return $query->rows;	
	}


	public function reindexElastic($product_ids){
		$this->load->library('ElasticSearch');
		$elasticSearch = new ElasticSearch($this->registry);

		foreach ($product_ids as $product_id){
			$elasticSearch->reindexproduct($product_id);
		}
	}

	public function deleteElastic($product_id){
		$this->load->library('ElasticSearch');
		$elasticSearch = new ElasticSearch($this->registry);
		$elasticSearch->deleteproduct($product_id);
	}

	public function getProductRelated($product_id, $limit, $store_id, $exclude) {
		$product_related_data = array();

		$sql = "SELECT * FROM product_related pr 
		LEFT JOIN product p ON (p.product_id = pr.product_id)
		LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
		$sql .= " WHERE pr.product_id = '" . (int)$product_id . "' 
		AND p.status = '1'
		AND p.date_available <= NOW() 
		AND p2s.store_id = '" . (int)$store_id . "'
		AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'";

		if ($exclude && count($exclude) > 0){
			$sql .= " AND p.product_id NOT IN (" . implode(',', $exclude) . ")";
		}

		$sql .= " ORDER BY (quantity_stock + quantity_stockM + quantity_stockK + quantity_stockMN + quantity_stockAS) DESC, p.quantity > 0 DESC LIMIT " . (int)$limit;

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}				

	public function getProductCostsForStore($product_id, $store_id) {			

		$sql = "SELECT * FROM product_costs WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$store_id . "' LIMIT 1";
		$query = $this->db->query($sql);
		
		return $query->row;
	}

	public function getProductCosts($product_id) {							
		
		$sql = "SELECT * FROM product_costs WHERE product_id = '" . (int)$product_id . "'";
		$query = $this->db->query($sql);
		
		$results = [];

		if ($query->num_rows){
			foreach ($query->rows as $row){
				$results[$row['store_id']] = $row;
			}
		}
		
		return $results;
	}

	public function getSimilarProductsByName($product_name, $product_id, $manufacturer_id, $limit, $in_stock, $recursive_results, $exclude, $language_id, $store_id){
		$this->load->model('setting/setting');

		$product_data = array();					

		$sql = "SELECT DISTINCT pd.product_id FROM product_description pd 
		LEFT JOIN product p ON (pd.product_id = p.product_id) 
		LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE pd.language_id = '" . $language_id . "' 
		AND TRIM(LCASE(pd.name)) LIKE ('" . $this->db->escape(trim(mb_strtolower($product_name))) . "%')
		AND pd.product_id <> '" . (int)$product_id . "'			
		AND p.status = '1'
		AND p.quantity > 0
		AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'
		AND p.date_available <= NOW() 
		AND p2s.store_id = '" . (int)$store_id . "'";

		if ($recursive_results){				
			if ($recursive_results && count($recursive_results) > 0){
				$sql .= " AND pd.product_id NOT IN (" . implode(',', $recursive_results) . ")";
			}
		}

		if ($exclude){				
			if ($exclude && count($exclude) > 0){
				$sql .= " AND pd.product_id NOT IN (" . implode(',', $exclude) . ")";
			}
		}

			//проверяем категории товара
		$_cssql = $this->db->non_cached_query("SELECT DISTINCT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		if ($_cssql->num_rows){
			$sql .= " AND pd.product_id IN (SELECT DISTINCT product_id FROM product_to_category WHERE category_id IN (SELECT DISTINCT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "'))";
		}

		$_qfield = 'p.quantity';

		if ($in_stock){
			$_cqwuery = $this->db->query("SHOW COLUMNS FROM product LIKE '" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id)) . "'");

			if ($_cqwuery->num_rows){
				$_qfield = 'p.`' . $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id).'`';
			} else {
				$_qfield = 'p.quantity';
			}
		}

		$sql .= " AND " . $_qfield . " > 0 AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'";

		$sql .= " ORDER BY p.manufacturer_id = '" . (int)$manufacturer_id . "' DESC, " . $_qfield . " DESC LIMIT " . (int)$limit . "";

		$query = $this->db->non_cached_query($sql);

		foreach ($query->rows as $result) {
			$product_data[] = $result['product_id'];
		}

		return $product_data;
	}

	public function guessSameProducts($product_name, $product_id, $manufacturer_id, $limit, $in_stock, $language_id, $store_id, $exclude){							
		$exploded = explode(' ', $product_name);

		$results = array();

			//Попытка получить по четырем словам
		if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2]) && isset($exploded[3])){
			$results = $this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2] . ' ' . $exploded[3], $product_id, $manufacturer_id, $limit, $in_stock, $exclude, $exclude, $language_id, $store_id);					
		}

			//Попытка получить по трем словам
		if (count($results) < $limit){
			if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2])){	
				$results = array_merge($this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2], $product_id, $manufacturer_id, ($limit - count($results)),  $in_stock, $results, $exclude, $language_id, $store_id), $results);						
			}
		}			

			//Попытка получить по двум словам
		if (count($results) < $limit){				
			if (isset($exploded[0]) && isset($exploded[1])){
				$results = array_merge($this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1], $product_id, $manufacturer_id, ($limit - count($results)),  $in_stock, $results, $exclude, $language_id, $store_id), $results);	
			}
		}

			//Попытка получить по одному слову
		if (count($results) < $limit){			
			if (isset($exploded[0])){
				$results = array_merge($this->getSimilarProductsByName($exploded[0], $product_id, $manufacturer_id, ($limit - count($results)), $in_stock, $results, $exclude, $language_id, $store_id), $results);	
			}				
		}

		return $results;
	}

	public function getReplaceProducts($product_id, $order_id, $exclude = array()){
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('sale/order');
		$this->load->model('tool/image');

		$result = array();

		$order = $this->model_sale_order->getOrder($order_id);
		$product = $this->model_catalog_product->getProduct($product_id);
		$language_id = $order['language_id'];
		$store_id = $order['store_id'];

			//get_product_categories
		$categories = $this->model_catalog_product->getProductCategories($product_id);
		$attributes = $this->model_catalog_product->getProductAttributesByLanguage($product_id, $language_id);

		$data['main_attributes'] = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product_id, $language_id);

		$data['special_attributes'] = array();

		foreach ($data['main_attributes'] as $key => $value){
			if ($value['group_id'] == $this->config->get('config_special_attr_id')){
				$data['special_attributes'][] = $value;				
				unset($data['main_attributes'][$key]);	
			}
		}

			//got categories, getting categorie attributes
		foreach ($categories as $category){
			$valid_attributes = array();

			$category_attributes = $this->model_catalog_category->getAttributesByCategory($category);

				//уберем из атрибутов категории те, которых нет в товаре, а наоборот у нас ситуация и невозможна. в результате поимеем массив атрибутов товара с значениями, похожие надо будет искать
			foreach ($category_attributes as $ca_id){

				if (isset($attributes[$ca_id])){
					$valid_attributes[$ca_id] = $attributes[$ca_id];
				} else {
						//пропускаем
				}								

			}

			if (count($valid_attributes) > 0){
					//ищем товары, которые совпадают по таким же критериям, в той же категории
				$result_product_ids = $this->model_catalog_product->getSimilarProductsByAttributes($product_id, $category, $language_id, $valid_attributes, 3 - count($result), $stock = 1);

					//таки есть товары
				if ($result_product_ids){					
					$products = array();										

					foreach ($result_product_ids as $result_id){
						$result[$result_id] = $this->model_catalog_product->getProduct($result_id);
					}										
				}

				if (count($result) <= 3){
					$result_product_ids = $this->model_catalog_product->getSimilarProductsByAttributes($product_id, $category, $language_id, $valid_attributes, 3 - count($result), $stock = 2);

					if ($result_product_ids){					
						$products = array();										

						foreach ($result_product_ids as $result_id){
							$result[$result_id] = $this->model_catalog_product->getProduct($result_id);
						}										
					}
				}
			}

		}						

		if (count($result) <= 3){
			$result_product_ids = $this->guessSameProducts($product['name'], $product_id, $product['manufacturer_id'], 3 - count($result), true, $language_id, $store_id, $exclude);
			foreach ($result_product_ids as $result_id){
				$result[$result_id] = $this->model_catalog_product->getProduct($result_id);
			}
		}

		if (count($result) <= 3){
			$result_product_ids = $this->guessSameProducts($product['name'], $product_id, $product['manufacturer_id'], 3 - count($result), false, $language_id, $store_id,$exclude);
			foreach ($result_product_ids as $result_id){
				$result[$result_id] = $this->model_catalog_product->getProduct($result_id);
			}
		}

		if (count($result) <= 3){
			$result_product_ids = $this->getProductRelated($product_id, 3 - count($result), $store_id, $exclude);
			foreach ($result_product_ids as $result_id){
				$result[$result_id] = $this->model_catalog_product->getProduct($result_id);
			}


		}

		return $result;
	}

	public function getLastUpdate(){
		$query = $this->db->query("SELECT date_modified FROM temp WHERE `key` LIKE('stock_last_sync') LIMIT 1");

		return $query->row['date_modified'];
	}

	public function setLastUpdate(){
		$query = $this->db->query("UPDATE temp SET date_modified = NOW() WHERE `key` LIKE('stock_last_sync')");
	}

	public function addToYobanyiChaliukActionProduct($product_id, $percent = 80) {

		$this->load->model('catalog/product');			
		$log = new Log('copy_to_special_coupon.txt');

		$price = $this->model_catalog_product->getProductSpecialOne($product_id);

		if (!$price){
			$price = $this->model_catalog_product->getProductPrice($product_id);
		}

		$cost = $this->model_catalog_product->getProductActualCost($product_id);

		if ((int)$price > 0 && (int)$cost > 0 && ($cost + ($cost / 100 * $percent) <= $price)){
			$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = 8308");		
			$log->write('Добавлен  '.$product_id.' в спецкупонкатегорию');
			echo '>> Добавлен  '. $product_id . ' в спецкупонкатегорию' . PHP_EOL;
		} else {
			echo '>> Добавлен  ' . $product_id . ', процент наценки меньше 80 ' . PHP_EOL;
		}
	}


	public function copyProductToPresent($product_id, $percent = 20, $count_orders = 2, $add_to_present = false){
		$this->load->model('catalog/product');

		$log = new Log('copy_to_stock.txt');

		$check_ord_query = $this->db->query("SELECT COUNT(*) as total FROM order_product op
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			WHERE op.product_id = '" . (int)$product_id . "'
			AND o.date_added >= DATE_SUB(NOW(),INTERVAL 1 YEAR)
			AND o.order_status_id > 0
			");

		$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = 6475");				
		$log->write('Добавлен  '.$product_id.' в подарочный');
		echo '>> Добавлен  '.$product_id.' в подарочный' . PHP_EOL;		
	}

	public function copyProductToPresentUA($product_id, $percent = 20, $count_orders = 2, $add_to_present = false){
		$this->load->model('catalog/product');

		$log = new Log('copy_to_stock.txt');

		$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = 8307");				
		$log->write('Добавлен  '.$product_id.' в подарочный');
	}

	public function copyProductToStock($product_id, $percent = 20, $count_orders = 2, $add_to_present = false){
		$this->load->model('catalog/product');

		$log = new Log('copy_to_stock.txt');

		$check_in_pa_query = $this->db->query("SELECT product_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = 6475");			
		$check_ord_query = $this->db->query("SELECT COUNT(*) as total FROM order_product op
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			WHERE op.product_id = '" . (int)$product_id . "'
			AND o.date_added >= DATE_SUB(NOW(),INTERVAL 1 YEAR)
			AND o.order_status_id > 0");

		if ($check_in_pa_query->num_rows == 0 && $check_ord_query->row['total'] <= $count_orders){

			$check_query = $this->db->query("SELECT product_id FROM product WHERE stock_product_id = '" . (int)$product_id . "'");
			if ($check_query->num_rows){
					//включаем товар на стоке
				$this->db->query("UPDATE `product` SET 
					status = 1, 
					stock_status_id = '" . $this->config->get('config_stock_status_id') . "', 
					ean = '', 
					asin = '',
					isbn = ''
					WHERE product_id = '" . (int)$check_query->row['product_id'] . "'");

				$new_product_id = (int)$check_query->row['product_id'];

				$log->write('Включен  '.$product_id.' в стоке');
				echo '>> Включен  '.$product_id.' в стоке' . PHP_EOL;

				if ($add_to_present) {
					echo '>> Включен  '.$product_id.' в подарочный:)' . PHP_EOL;
					$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$new_product_id . "', category_id = 6475");
				}

			} else {					
				$new_product_id = $this->model_catalog_product->copyProduct($product_id);

				$this->db->query("DELETE FROM product_to_category WHERE product_id = '" .(int)$new_product_id. "'");
				$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$new_product_id . "', category_id = 6474");

				if ($add_to_present) {
					echo '>> Включен  '.$product_id.' в подарочный:)' . PHP_EOL;
					$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$new_product_id . "', category_id = 6475");		
				}

				$this->db->query("UPDATE product SET 
					stock_product_id = '" . (int)$product_id . "',
					stock_status_id = '" . $this->config->get('config_stock_status_id') . "', 
					ean = '', 
					asin = '',
					isbn = '',
					status = 1 WHERE product_id = '" .(int)$new_product_id. "'");

				$log->write('Скопирован  '.$product_id.' в '.$new_product_id . ', включен в стоке');
				echo '>> Скопирован  '.$product_id.' в '.$new_product_id . ', включен в стоке' . PHP_EOL;
			}


			$_query = $this->db->query("SELECT price FROM product WHERE product_id = '" . (int)$product_id  . "' LIMIT 1");

			if ($_query->row && isset($_query->row['price']) && $_query->row['price']){
				$this->db->query("UPDATE product p SET p.price = '" . $_query->row['price'] . "' WHERE p.product_id = '" . (int)$new_product_id . "'");
			}


			$this->db->query("DELETE FROM product_special WHERE product_id = '" .(int)$new_product_id. "'");

			$special = $this->model_catalog_product->getProductSpecialOne($product_id);


			if ($special){

				$this->db->query("INSERT INTO product_special SET product_id = '" . (int)$new_product_id . "', customer_group_id = '1', priority = '1', price = '" . (float)($special * (100 - $percent) / 100) . "', date_start = '" . $this->db->escape('2005-12-12') . "', date_end = '" . $this->db->escape('2020-12-12') . "'");

			} else {

				$price = $this->model_catalog_product->getProductPrice($product_id);

				$this->db->query("INSERT INTO product_special SET product_id = '" . (int)$new_product_id . "', customer_group_id = '1', priority = '1', price = '" . (float)($price * (100 - $percent) / 100) . "', date_start = '" . $this->db->escape('2005-12-12') . "', date_end = '" . $this->db->escape('2020-12-12') . "'");

			}

		} else {
			$log->write('Пропущен  '.$product_id.' - есть в ПА или заказов больше ' . $count_orders);
			echo '>> Пропущен  '.$product_id.' - есть в ПА или заказов больше ' . $count_orders . PHP_EOL;
		}
	}

}										
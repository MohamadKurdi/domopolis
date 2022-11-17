<?php
	class ModelKPProduct extends Model {
		private $error = array();

		private $order_products = array(); // Лимит на кол. нахождений, если будет достигнуто, то товар удаляем из поиска по БД
		private $product_limit = NULL; // Лимит на товары (максимум ищем 3)
		private $quantity_field = NULL;
		private $exclude = array();
		private $order_id = 0;

		public function setOrderID($order_id = 0) {
			$this->order_id = $order_id;
		}
		
		public function getProductMainCategory($product_id){
			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1");
			
			if (!empty($query->row['category_id'])){
				return $query->row['category_id'];	
				} else {
				return 0;
			}
		}
		
		public function checkIfProductIsInWarehouseInCurrentCountry($product_id, $store_id){
			$this->load->model('setting/setting');
			$quantity = 0;

			$stock_query = $this->db->query("SELECT `" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id)) . "` FROM product WHERE product_id = '" . (int)$product_id . "'");

			if ($stock_query->num_rows) {
				$quantity = $stock_query->row[$this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id)];
			}

			return ($quantity) ? true : false;
		}
		
		public function getBestSellerProductsForCategory($product_info, $limit, $store_id, $stock_in_current, $exclude, $use_manufacturer = true, $only_warehouse_stock = false) {
			$product_data = array();
						
			$this->load->model('setting/setting');
			$category_id = $this->getProductMainCategory($product_info['product_id']);
			
			$sql = "SELECT op.product_id, COUNT(*) AS total
			FROM order_product op 
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			LEFT JOIN `product` p ON (op.product_id = p.product_id) 
			LEFT JOIN `product_to_store` p2s ON (p.product_id = p2s.product_id) 
			LEFT JOIN `product_to_category` p2c ON (p2c.product_id = p.product_id)
			LEFT JOIN `category_path` cp ON (p2c.category_id = cp.category_id)
			WHERE o.order_status_id > '0'";
			if ($product_info['manufacturer_id'] && $use_manufacturer){
				$sql .= " AND p.manufacturer_id = " . (int)$product_info['manufacturer_id'];
			}
			if ($product_info['product_id']){
				$sql .= " AND p.product_id <> " . (int)$product_info['product_id'];
			}
			
			if ($exclude && count($exclude) > 0){
				$sql .= " AND p.product_id NOT IN (" . implode(',', $exclude) . ")";
			}
			
			$_cqwuery = $this->db->non_cached_query("SHOW COLUMNS FROM product LIKE '" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id)) . "'");

			
			if ($_cqwuery->num_rows && $stock_in_current){
				$_qfield = 'p.`' . $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id).'`';
			} else {
				$_qfield = 'p.quantity';
			}

			if ($only_warehouse_stock) {
				$_qfield = 'p.`' . $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id).'`';
			}
			
			$sql .= " AND p.status = '1'
			AND " . $_qfield . " > 0 
			AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
			AND p.date_available <= NOW() AND 
			cp.path_id = '" . (int)$category_id . "'
			AND p2s.store_id = '" . (int)$store_id . "' 
			GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;

			$query = $this->db->non_cached_query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[] = $result['product_id'];
			}

			return $product_data;
		}
		
		public function getSimilarProductsByName($product_name, $product_id, $manufacturer_id, $limit, $in_stock, $recursive_results, $exclude, $language_id, $store_id, $only_warehouse_stock = false){
			$this->load->model('setting/setting');
			
			$product_name = trim(trim($product_name, ',.'));
			
			$product_data = array();					
			
			$sql = "SELECT DISTINCT pd.product_id FROM " . DB_PREFIX . "product_description pd 
			LEFT JOIN " . DB_PREFIX . "product p ON (pd.product_id = p.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE pd.language_id = '" . $language_id . "' 
			AND TRIM(LCASE(REPLACE(pd.name, ',', ''))) LIKE ('" . $this->db->escape(trim(mb_strtolower($product_name))) . "%')
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
				$_cqwuery = $this->db->non_cached_query("SHOW COLUMNS FROM product LIKE '" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id)) . "'");
				
				if ($_cqwuery->num_rows){
					$_qfield = 'p.`' . $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id).'`';
					} else {
					$_qfield = 'p.quantity';
				}
			}

			if ($only_warehouse_stock) {
				$_qfield = 'p.`' . $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id).'`';
			}
			
			$sql .= " AND " . $_qfield . " > 0 AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "'";
			
			$sql .= " ORDER BY p.manufacturer_id = '" . (int)$manufacturer_id . "' DESC, " . $_qfield . " DESC LIMIT " . (int)$limit . "";
			
			$query = $this->db->non_cached_query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[] = $result['product_id'];
			}
			
			return $product_data;
		}
		
		
		public function guessSameProducts($product_name, $product_id, $manufacturer_id, $limit, $in_stock, $language_id, $store_id, $exclude, $only_warehouse_stock = false){							
			$exploded = explode(' ', $product_name);
			
			$results = array();
			
			//Попытка получить по четырем словам
			if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2]) && isset($exploded[3])){
				$results = $this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2] . ' ' . $exploded[3], $product_id, $manufacturer_id, $limit, $in_stock, $exclude, $exclude, $language_id, $store_id, $only_warehouse_stock);					
			}
			
			//Попытка получить по трем словам
			if (count($results) < $limit){
				if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2])){	
					$results = array_merge($this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2], $product_id, $manufacturer_id, ($limit - count($results)),  $in_stock, $results, $exclude, $language_id, $store_id, $only_warehouse_stock), $results);						
				}
			}			
			
			//Попытка получить по двум словам
			/*	if (count($results) < $limit){				
				if (isset($exploded[0]) && isset($exploded[1])){
				$results = array_merge($this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1], $product_id, $manufacturer_id, ($limit - count($results)),  $in_stock, $results, $exclude, $language_id, $store_id), $results);	
				}
				}
			*/	
			/*	//Попытка получить по одному слову
				if (count($results) < $limit){			
				if (isset($exploded[0])){
				$results = array_merge($this->getSimilarProductsByName($exploded[0], $product_id, $manufacturer_id, ($limit - count($results)), $in_stock, $results, $exclude, $language_id, $store_id), $results);	
				}				
				}
			*/
			
			return $results;
		}
		
		/**
		  * @param - $only_warehouse_stock = Искать только на складах, не искать у поставщика
		  * @param - Нужно для trigger.cancelledbyterms
		  */
		public function getReplaceProducts($product_id, $order_id, $exclude = array(), $only_warehouse_stock = false){
			$this->load->model('catalog/product');
			$this->load->model('catalog/category');
			$this->load->model('account/order');
			$this->load->model('tool/image');

			$result = array();
			
			$order = $this->model_account_order->getOrder($order_id, true);
			$product = $this->model_catalog_product->getProduct($product_id);
			$language_id = $order['language_id'];
			$store_id = $order['store_id'];		
			
			//Такие же названию + наличие + бренд
			if (count($result) <= 3){
				$result_product_ids = $this->guessSameProducts($product['name'], $product_id, $product['manufacturer_id'], 3 - count($result), true, $language_id, $store_id, $exclude, $only_warehouse_stock);
				foreach ($result_product_ids as $result_id){
					$product = $this->model_catalog_product->getProduct($result_id);
					if ($product){
						$result[$result_id] = $product;
					}
				}
			}
			
			//Наличие + категория + бренд
			if (count($result) <= 3){
				$result_product_ids = $this->getBestSellerProductsForCategory($product, 3 - count($result), $store_id, true, $exclude, true, $only_warehouse_stock);
				foreach ($result_product_ids as $result_id){
					$product = $this->model_catalog_product->getProduct($result_id);
					if ($product){
						$result[$result_id] = $product;
					}
				}
			}	
			
			//Наличие + категория минус бренд
			if (count($result) <= 3){
				$result_product_ids = $this->getBestSellerProductsForCategory($product, 3 - count($result), $store_id, true, $exclude, false, $only_warehouse_stock);
				foreach ($result_product_ids as $result_id){
					$product = $this->model_catalog_product->getProduct($result_id);
					if ($product){
						$result[$result_id] = $product;
					}
				}
			}			
			
			//Наличие + категория минус бренд			
			if (count($result) <= 3){
				$result_product_ids = $this->guessSameProducts($product['name'], $product_id, $product['manufacturer_id'], 3 - count($result), false, $language_id, $store_id,$exclude, $only_warehouse_stock);
				foreach ($result_product_ids as $result_id){
					$product = $this->model_catalog_product->getProduct($result_id);
					if ($product){
						$result[$result_id] = $product;
					}
				}
			}									
			
			if (count($result) <= 3){
				$result_product_ids = $this->getBestSellerProductsForCategory($product, 3 - count($result), $store_id, false, $exclude, true, $only_warehouse_stock);
				foreach ($result_product_ids as $result_id){
					$product = $this->model_catalog_product->getProduct($result_id);
					if ($product){
						$result[$result_id] = $product;
					}
				}
			}			
			
			
			if (count($result) <= 3){
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
						$result_product_ids = $this->model_catalog_product->getSimilarProductsByAttributes($product_id, $category, $language_id, $store_id, $valid_attributes, 3 - count($result), $stock = 1);
						
						//таки есть товары
						if ($result_product_ids){					
							$products = array();										
							
							foreach ($result_product_ids as $result_id){
								$product = $this->model_catalog_product->getProduct($result_id);
								if ($product){
									$result[$result_id] = $product;
								}
							}										
						}
						
						if (count($result) <= 3){
							$result_product_ids = $this->model_catalog_product->getSimilarProductsByAttributes($product_id, $category, $language_id, $store_id, $valid_attributes, 3 - count($result), $stock = 2);
							
							if ($result_product_ids){					
								$products = array();										
								
								foreach ($result_product_ids as $result_id){
									$product = $this->model_catalog_product->getProduct($result_id);
									if ($product){
										$result[$result_id] = $product;
									}
								}										
							}
						}
					}
					
				}	
			}

			return $result;

		}

		/**

		===== COMPLETE ======

		 */

		public function getProductRelated($order_products, $limit, $categories, $store_id, $use_not_stock = false, $order_id = 0) {

			if ($this->order_id && ($this->order_id !== $order_id)) {
				$this->exclude = array();
			}

			if (!isset($this->product_limit)) $this->product_limit = (count($order_products) >= 6) ? 2 : 3;
			if (!isset($this->quantity_field)) {
				$_cqwuery = $this->db->non_cached_query("SHOW COLUMNS FROM product LIKE '" . $this->db->escape($this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id)) . "'");

				if ($_cqwuery->num_rows){
					$this->quantity_field = 'p.' . $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$store_id).'';
				} else {
					$this->quantity_field = 'p.quantity';
				}
			}
			
			$products = array();

			// Для $use_not_stock
			if ($this->order_products) {
				$order_products = array();

				foreach ($this->order_products as $key => $value) {
					if ($value) $order_products[] = $key;
				}
			}

			foreach ($order_products as $product) {
				$sql ="SELECT pr.related_id as product FROM product_related pr
				LEFT JOIN product p ON (pr.product_id = p.product_id)
				LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)
				WHERE pr.product_id = '" . (int)$product . "' AND p2s.store_id = '" . (int)$store_id . "'";

				if ($order_products && count($order_products) > 0){
					$sql .= " AND pr.related_id NOT IN (" . implode(',', $order_products) . ")";
				}

				if ($this->exclude && count($this->exclude) > 0) {
					$sql .= " AND pr.related_id NOT IN (" . implode(',', $this->exclude) . ")";
				}

				$sql .= " GROUP BY pr.related_id LIMIT " . (int)$this->product_limit;

				$query = $this->db->non_cached_query($sql);

				$this->order_products[$product] = ($query->num_rows) ? $this->product_limit - $query->num_rows : $this->product_limit;

				foreach ($query->rows as $result) {
					$this->exclude[] = $result['product'];
					// Пока не набрали лимит записываем в массив новые товары.

					if (count($products) < $limit) {
						$product = $this->model_catalog_product->getProduct($result['product']);
						if ($product){
							$products[] = $product;	
						}
					}
				}
			}

			// Отдаем чисто готовый набор продуктов, чтоб в контроллере еще раз не запИсЬкать модель!
			return $products;
		}

		public function getProductRelatedByCategory($order_products, $limit, $exclude, $store_id, $use_manufacturer = false, $use_not_stock = false) {
			$products = array();
		
			// Исключаем товар у которого уже лимит исчерпан! Разделяй и властвуй! Разделяем по продуктам а не все вместе чтоб знать лимит
			foreach ($this->order_products as $product_id => $product_limit) {

				$categories = array();

				if (($product_limit) && ($product_limit <= $this->product_limit)) {
					$categories = $this->getCategoriesByProduct($product_id);

					if ($use_manufacturer) {
						$manufacturers = $this->getManufacturersByProduct($product_id);
					}

					$related_categories = $this->getRelatedCategories($categories);
					
					if ($related_categories && count($related_categories) > 0) {

						$sql = "SELECT DISTINCT p.product_id as product
						FROM product p 
						LEFT JOIN `product_to_store` p2s ON (p.product_id = p2s.product_id) 
						LEFT JOIN `product_to_category` p2c ON (p2c.product_id = p.product_id)
						WHERE p. status = '1'";

						if ((isset($manufacturers) && count($manufacturers)) > 0 && $use_manufacturer) {
							$sql .= " AND p.manufacturer_id IN (" . implode(',', $manufacturers) . ")";
						}
						
						if ($exclude && count($exclude) > 0){
							$sql .= " AND p.product_id NOT IN (" . implode(',', $exclude) . ")";
							if ($this->exclude) $sql .= " AND p.product_id NOT IN (" . implode(',', $this->exclude) . ")";
						}

						// Если есть связанные категории с категорией продукта
						$sql .= " AND p2c.category_id IN (" . implode(',', $related_categories) . ")";
					
						if ($this->quantity_field && !$use_not_stock) $sql .= " AND " . $this->quantity_field . " > 0 ";

						$sql .= " AND p.status = '1'
						AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
						AND p.date_available <= NOW()
						AND p2s.store_id = '" . (int)$store_id . "'
						ORDER BY p.product_id ASC LIMIT " . (int)$product_limit;

						$query = $this->db->non_cached_query($sql);

						if ($this->order_products[$product_id]) {
							$this->order_products[$product_id] = ($query->num_rows) ? $product_limit - $query->num_rows : $this->product_limit;
						}

						foreach ($query->rows as $result) {
							$this->exclude[] = $result['product'];

							if (count($products) < $limit) {
								$product = $this->model_catalog_product->getProduct($result['product']);
								if ($product){
									$products[] = $product;	
								}
							}
						}
					}
				}
			}

			// Отдаем чисто готовый набор продуктов, чтоб в контроллере еще раз не запИсЬкать модель!
			return $products;
		}

		/* Если есть коллекция и связанная категория то должно выбирать из связанной категории этой коллекции */
		public function getProductRelatedByCollection($order_products, $limit, $exclude, $store_id, $use_category = false, $use_not_stock = false) {
			$products = array();

			foreach ($this->order_products as $product_id => $product_limit) {
				if (($product_limit) && ($product_limit <= $this->product_limit)) {
					$collection_id = $this->getCollectionsByProduct($product_id);

					if (!$collection_id) continue; // Нет колекций, значит нет что искать

					if ($use_category) {
						/* Узнаем связанные категории */
						$category = $this->getProductMainCategory($product_id);

						// Если не указана главная категория
						if (!$category) {
							$categories = $this->model_catalog_product->getProductCategories($product_id);
						}

						$related_categories = array();

						// Если указана главная категория
						if ($category) {
							$query = $this->db->non_cached_query("SELECT related_category_id FROM category_related WHERE category_id = '" . $category . "'");
						} else {
							$query = $this->db->non_cached_query("SELECT related_category_id FROM category_related WHERE category_id IN (" . implode(',', $categories) . ")");
						}

						foreach ($query->rows as $result) {
							$related_categories[] = $result['related_category_id'];
						}
					}

					/* Основной запрос */
					$sql = "SELECT DISTINCT p.product_id
					FROM product p 
					LEFT JOIN `product_to_store` p2s ON (p.product_id = p2s.product_id)
					LEFT JOIN `product_to_category` p2c ON (p2c.product_id = p.product_id)
					WHERE p. status = '1'";
					
					if ($exclude && count($exclude) > 0){
						$sql .= " AND p.product_id NOT IN (" . implode(',', $exclude) . ")";
						if ($this->exclude) $sql .= " AND p.product_id NOT IN (" . implode(',', $this->exclude) . ")";
					}

					if ($use_category && ($related_categories && count($related_categories) > 0)) {
						$sql .= " AND p2c.category_id IN (" . implode(',', $related_categories) . ")";
					}

					if ($this->quantity_field && !$use_not_stock) $sql .= " AND " . $this->quantity_field . " > 0 ";

					$sql .= " AND p.status = '1'
					AND stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') . "' 
					AND p.date_available <= NOW()
					AND p2s.store_id = '" . (int)$store_id . "'
					AND p.collection_id = '" . (int)$collection_id . "'
					GROUP BY p.product_id ORDER BY p.product_id DESC LIMIT " . (int)$product_limit;

					$query = $this->db->non_cached_query($sql);

					if ($this->order_products[$product_id]) {
						$this->order_products[$product_id] = ($query->num_rows) ? $product_limit - $query->num_rows : $this->product_limit;
					}

					foreach ($query->rows as $result) {
						$this->exclude[] = $result['product_id'];

						$product = $this->model_catalog_product->getProduct($result['product_id']);
						if (count($products) < $limit) {
							$product = $this->model_catalog_product->getProduct($result['product_id']);
							if ($product){
								$products[] = $product;	
							}
						}
					}
				}
			}

			return $products;
		}

		// Производители товаров
		public function getManufacturersByProduct($product_id = 0) {
			$manufacturers = array();

			if ($product_id) {
				$query = $this->db->non_cached_query("SELECT DISTINCT manufacturer_id FROM product WHERE product_id = '" . (int)$product_id . "'");

				foreach ($query->rows as $result) {
					$manufacturers[] = $result['manufacturer_id'];
				}
			}

			return $manufacturers;
		}

		// Связанные категории
		public function getRelatedCategories($category = array()) {
			$categories = array();

			if ($category) {
				$query = $this->db->non_cached_query("SELECT DISTINCT related_category_id FROM category_related WHERE category_id IN (" . implode(',', $category) . ")");

				foreach ($query->rows as $result) {
					$categories[] = $result['related_category_id'];
				}
			}

			return $categories;
		}

		// Коллекции
		public function getCollectionsByProduct($product_id = 0) {

			if ($product_id) {
				$query = $this->db->non_cached_query("SELECT `collection_id` FROM product WHERE product_id = '" . $product_id ."'");

				return $query->row['collection_id'];
			}

		}

		public function getCategoriesByProducts($products = array()) {
			$categories = array();

			if ($products) {
				foreach ($products as $product) {
				
					$cat = $this->model_catalog_product->getProductCategories($product);
					
					foreach ($cat as $category) {
						$available = $this->model_catalog_category->getCategory($category);
						// $available = проверяем доступна ли категория в странах, и отсекаем дубли
						if ($available) {
							if (!in_array($available['category_id'], $categories)) {
								$categories[] = $available['category_id'];
							}
						}
					}
				}
			}

			return $categories;
		}

		public function getCategoriesByProduct($product = false) {
			$categories = array();

			if ($product) {
				$cat = $this->model_catalog_product->getProductCategories($product);
					
				foreach ($cat as $category) {
					$available = $this->model_catalog_category->getCategory($category);
					// $available = проверяем доступна ли категория в странах, и отсекаем дубли
					if ($available) {
						if (!in_array($available['category_id'], $categories)) {
							$categories[] = $available['category_id'];
						}
					}
				}
			}

			return $categories;
		}

		/**

		===== END: COMPLETE ======

		 */

		public function getLastUpdate(){
			$query = $this->db->non_cached_query("SELECT date_modified FROM temp WHERE `key` LIKE('stock_last_sync') LIMIT 1");
			
			return $query->row['date_modified'];
		}
		
		public function setLastUpdate(){
			$query = $this->db->non_cached_query("UPDATE temp SET date_modified = NOW() WHERE `key` LIKE('stock_last_sync')");
			
		}
		
	}																									
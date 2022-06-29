<?

namespace hobotix\Amazon;

class PriceLogic
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\PriceLogic';
	
	private $db;	
	private $config;
	private $weight;

	private $excluded_categories = [8307, 6475, 6474, BIRTHDAY_DISCOUNT_CATEGORY, GENERAL_DISCOUNT_CATEGORY, GENERAL_MARKDOWN_CATEGORY];

	private $defaultWeightClassID = 1;	
	private $defaultDimensions = [];
	private $storesWarehouses = [];
	private $warehousesStores = [];

	private $storesSettingsFields = ['config_warehouse_identifier_local', 'config_warehouse_identifier'];

	public function __construct($registry){

		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->weight 	= $registry->get('weight');

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/PriceUpdaterQueue.php');
		$this->priceUpdaterQueue = new PriceUpdaterQueue($registry);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/PriceHistory.php');
		$this->priceHistory = new PriceHistory($registry);

		$this->cacheCategoryDimensions()->setStoreSettings();

		$this->log = new \Log('amazon_offers_priceupdate.txt');

	}

	private function setStoreSettings(){		

		$implode = [];
		foreach ($this->storesSettingsFields as $field){
			$implode[] = "`key` = '" . $field . "'";
		}

		$query = $this->db->query("SELECT store_id, `key`, value FROM setting WHERE " . implode(' OR ', $implode) . "");

		foreach ($query->rows as $row){
			$this->storesWarehouses[$row['store_id']] = [];
		}

		unset($row);
		foreach ($query->rows as $row){

			if ($row['key'] == 'config_warehouse_identifier_local'){
				$this->warehousesStores[$row['value']] = $row['store_id'];
			}

			foreach ($this->storesSettingsFields as $field){
				if ($row['key'] == $field){
					$this->storesWarehouses[$row['store_id']][$field] = $row['value'];
				}
			}	 
		}	

		return $this;
	}

	public function getStoreSettings(){
		return $this->storesWarehouses;		
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

	public function getProductWeight($product){
		$productDimensions = $this->getProductDimensions($product);

		if ($productDimensions['weight_class_id'] == (int)$this->config->get('config_weight_class_id')){
			return $productDimensions['weight'];
		} else {
			return $this->weight->convert($productDimensions['weight'], $productDimensions['weight_class_id'], $this->config->get('config_weight_class_id'));
		}
	}


	private function getProductsByAsin($asin){
		$query = $this->db->query("SELECT *, (SELECT category_id FROM product_to_category p2cm WHERE p2cm.product_id = p.product_id AND category_id NOT IN (" . implode(',', $this->excluded_categories) . ") ORDER BY main_category DESC LIMIT 1) as main_category_id FROM product p WHERE asin = '" . $this->db->escape($asin) . "' AND is_markdown = 0 AND status = 1");

			$results = [];
			if ($query->num_rows){
				foreach($query->rows as $row){
					$results[$row['product_id']] = $row;
				}

				return $results;
			}

			return false;
	}

	public function checkIfProductIsOnWarehouse($product_id, $warehouse_identifier){
		$query = $this->db->query("SELECT * FROM product WHERE product_id = '" . $product_id . "' AND (`" . $warehouse_identifier . "` + `" . $warehouse_identifier . '_onway' . "` > 0)");

		return $query->num_rows;
	}

	//Это прямо самая важная функция)))
	public function mainFormula($amazonBestPrice, $productWeight, $weightCoefficient){
		$mainFormula = str_replace(['PRICE','WEIGHT','KG_LOGISTIC','PLUS', 'MULTIPLY', 'DIVIDE'], [$amazonBestPrice, $productWeight, $weightCoefficient, '+', '*', '/'], $this->config->get('config_rainforest_main_formula'));
		$resultPrice = eval('return ' . $mainFormula . ';');

		return $resultPrice;
	}

	public function updateProductPriceInDatabase($product_id, $price){
		$this->db->query("UPDATE product SET price = '" . (float)$price . "' WHERE product_id = '" . (int)$product_id . "' AND is_markdown = 0 AND status = 1");

		$this->priceUpdaterQueue->addToQueue($product_id);
	}

	public function updateProductPriceToStoreInDatabase($product_id, $price, $store_id){
		$this->db->query("INSERT INTO product_price_to_store SET 
			store_id 			= '" . (int)$store_id . "',
			product_id 			= '" . (int)$product_id . "',
			price 				= '" . (float)$price . "',
			special 			= '0',
			settled_from_1c 	= '0',
			dot_not_overload_1c = '0'
			ON DUPLICATE KEY UPDATE
			price 				= '" . (float)$price . "',
			settled_from_1c 	= '0',
			dot_not_overload_1c = '0'");

		$this->priceUpdaterQueue->addToQueue($product_id);
	}

	public function updateProductPrices($asin, $amazonBestPrice){
			//Если включена настройка "ценообразование из рейнфорест"
		if ($this->config->get('config_rainforest_enable_pricing')){

				//Если найдены по ASIN товары
			if ($products = $this->getProductsByAsin($asin)){
				foreach ($products as $product_id => $product){
					echoLine('Код: ' . $product_id);

						//Если у товара есть вес, классификатор единицы веса
					$productWeight = $this->getProductWeight($product);
					if ($productWeight && !$product['amzn_ignore']){
						//Для всех настроек магазинов проверяем наличие на складе
						foreach ($this->storesWarehouses as $store_id => $storeWarehouses){
							$warehouse_identifier = $storeWarehouses['config_warehouse_identifier_local'];

							//Если товара нет на текущем складе или он не едет
							if ($weightCoefficient = $this->config->get('config_rainforest_kg_price_' . $store_id)){
								if (!$this->checkIfProductIsOnWarehouse($product_id, $warehouse_identifier)){

									$newPrice = $this->mainFormula($amazonBestPrice, $productWeight, $weightCoefficient);

									$logString = 'Товар: ' . $product_id . ', ' . $asin . ', вес: ' . $productWeight . ', цена для магазина ' . $store_id . ' = ' . $newPrice . ' EUR';
									$this->log->write($logString);
									echoLine($logString);

									if ($this->config->get('config_rainforest_default_store_id') != -1 && $store_id == $this->config->get('config_rainforest_default_store_id')){
										$this->updateProductPriceInDatabase($product_id, $newPrice);
									} else {
										$this->updateProductPriceToStoreInDatabase($product_id, $newPrice, $store_id);
									}

								}
							}
						}
					} 
				}
			}
		}
	}




	//Логика работы со статусами складов
		public function buildStockQueryField(){
			$implode = [];
			foreach ($this->storesWarehouses as $store_id => $storesWarehouse){
				$implode[] = $storesWarehouse['config_warehouse_identifier'];
				$implode[] = $storesWarehouse['config_warehouse_identifier'] . '_onway';
			}

			return implode(' + ', $implode);
		}


		public function setProductNoOffers($asin){

			if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_status_id')){

			//Если нет офферов, и товара нет в наличии нигде, и он никуда не едет, то 
				$this->db->query("UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' WHERE asin = '" . $this->db->escape($asin) . "'  AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (" . $this->buildStockQueryField() . " = 0)");
				//	echoLine($asin . ', если товара нет в наличии, установлен статус уточняйте');

			//И очистим статусы для этих товаров
				$this->db->query("DELETE FROM product_stock_status WHERE product_id IN (SELECT product_id FROM product WHERE asin = '" . $this->db->escape($asin) . "'  AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (" . $this->buildStockQueryField() . " = 0))");
				//	echoLine($asin . ', если товара нет в наличии, очищены переназначения статусов');
				}

			}

			public function setProductOffers($asin){

				if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_status_id')){

				//Мы убираем "наличие уточняйте, если оно стоит"
					$this->db->query("UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_stock_status_id') . "' WHERE asin = '" . $this->db->escape($asin) . "'  AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' OR stock_status_id = '" . (int)$this->config->get('config_not_in_stock_status_id') ."')");
				//	echoLine($asin . ', если статус был переназначен, то мы его возвращаем на в наличии');

				//И очищаем табличку переназначенных статусов, в случае если там стоит статус "уточняйте" или "нет в наличии"
					$this->db->query("DELETE FROM product_stock_status WHERE product_id IN (SELECT product_id FROM product WHERE asin = '" . $this->db->escape($asin) . "'  AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' OR stock_status_id = '" . (int)$this->config->get('config_not_in_stock_status_id') ."'))");
				//		echoLine($asin . ', очистили табличку переназначеных статусов, если они были заданы как уточняйте или нету');
					}

				}


				public function setProductStockInWarehouse($product_id, $warehouse_identifier){
			//принудительно ставим статус "есть в наличии на складе для той страны, в которой он есть"

					if (isset($this->warehousesStores[$warehouse_identifier])){
						$this->db->query("INSERT INTO product_stock_status SET store_id = '" . (int)$this->warehousesStores[$warehouse_identifier] . "', product_id = '" . (int)$product_id . "', stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "' ON DUPLICATE KEY UPDATE stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "'");

			//			echoLine('Установлен статус в наличии на складе для товара ' . $product_id . ' и страны ' . $this->warehousesStores[$warehouse_identifier]);
					}
				}



				public function setProductStockStatusesGlobal(){
			//Для товаров, которых нет в наличии на определенном складе, при этом статус установлен как "есть на складе", мы должны поставить его в 
			//1. Наличие уточняйте, если для товара нет предложений на амазон и включена настройка "менять статус"
			//2. Есть в наличии, если для товара есть предложения на амазон, либо выключена настройка "менять статус"



					foreach ($this->storesWarehouses as $store_id => $storesWarehouse) {
						$warehouse_identifier = $storesWarehouse['config_warehouse_identifier_local'];

						if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_status_id')){ 
					//Статус для товара которого нет в наличии в стране и нет на амазоне
							$new_order_status_id_not_in_amazon = $this->config->get('config_rainforest_nooffers_status_id');
					//Статус для товара которого нет в наличии в стране и есть на амазоне
							$new_order_status_id_in_amazon = $this->config->get('config_stock_status_id');
						} else {
							$new_order_status_id_not_in_amazon = $this->config->get('config_stock_status_id');
							$new_order_status_id_in_amazon = $this->config->get('config_stock_status_id');
						}

				//Глобальное обновление где больше нуля
						$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_stock_status_id') . "' WHERE (" . $this->buildStockQueryField() . " > 0) AND status = 1");

				//Товара нет в наличии в стране, при этом и нет на амазоне
						$sql = "INSERT INTO product_stock_status(product_id, store_id, stock_status_id) 
						SELECT product_id, '" . (int)$store_id . "', '" . (int)$new_order_status_id_not_in_amazon . "' FROM product 
						WHERE `" . $warehouse_identifier . "` = 0
						AND status = 1 
						AND amzn_ignore = 0 
						AND is_virtual = 0 
						AND asin <> '' 
						AND amzn_no_offers = 1 
						AND product_id IN (SELECT product_id FROM product_stock_status pss2 WHERE store_id = '" . (int)$store_id . "' AND stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "')
						ON DUPLICATE KEY UPDATE stock_status_id = '" . (int)$new_order_status_id_not_in_amazon . "'";

						$this->db->query($sql);

				//Товара нет в наличии в стране, при этом он есть на амазоне
						$sql = "INSERT INTO product_stock_status(product_id, store_id, stock_status_id) 
						SELECT product_id, '" . (int)$store_id . "', '" . (int)$new_order_status_id_in_amazon . "' FROM product 
						WHERE `" . $warehouse_identifier . "` = 0
						AND status = 1 
						AND amzn_ignore = 0 
						AND is_virtual = 0 
						AND asin <> '' 
						AND amzn_no_offers = 0 
						AND product_id IN (SELECT product_id FROM product_stock_status pss2 WHERE store_id = '" . (int)$store_id . "' AND stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "')
						ON DUPLICATE KEY UPDATE stock_status_id = '" . (int)$new_order_status_id_in_amazon . "'";

						$this->db->query($sql);

					}				


				}



			}
<?

namespace hobotix\Amazon;

class PriceLogic
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\PriceLogic';
	
	private $db 		= null;	
	private $config 	= null;
	private $weight 	= null;
	private $length 	= null;
	private $cache 		= null;
	private $log 		= null;

	private $excluded_categories = [8307, 6475, 6474, BIRTHDAY_DISCOUNT_CATEGORY, GENERAL_DISCOUNT_CATEGORY, GENERAL_MARKDOWN_CATEGORY];

	private $defaultWeightClassID 			= 1;	
	private $defaultDimensions 				= [];
	private $storesWarehouses 				= [];
	private $storesVolumetricWeightSettings = [];
	private $warehousesStores 				= [];
	private $formulaOverloadData 			= [];
	private $categoriesOverPriceRules		= [];
	private $categoriesOverloadRules		= [];

	private $costDividerSymbol 				= ':COSTDVDR:';

	private $storesSettingsFields 	= ['config_warehouse_identifier_local', 'config_warehouse_identifier'];
	private $storesSettingsFields2 	= ['config_rainforest_use_volumetric_weight', 'config_rainforest_volumetric_weight_coefficient'];

	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->weight 	= $registry->get('weight');
		$this->length 	= $registry->get('length');
		$this->currency = $registry->get('currency');
		$this->cache 	= $registry->get('cache');
		$this->log 		= $registry->get('log');

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/PriceUpdaterQueue.php');
		$this->priceUpdaterQueue = new PriceUpdaterQueue($registry);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/PriceHistory.php');
		$this->priceHistory = new PriceHistory($registry);

		if (!$registry->get('bypass_rainforest_caches_and_settings')){
			$this->cacheCategoryDimensions()->cacheCategoryOverloadRules()->cacheCategoryOverPriceRules()->setStoreSettings()->cacheFormulaOverloadData();
		}				
	}

	private function cacheFormulaOverloadData(){
		for ($crmfc = 1; $crmfc <= (int)$this->config->get('config_rainforest_main_formula_count'); $crmfc++){
			if (!empty($this->config->get('config_rainforest_main_formula_min_' . $crmfc))
				&& !empty($this->config->get('config_rainforest_main_formula_max_' . $crmfc))
				&& !empty($this->config->get('config_rainforest_main_formula_costprice_' . $crmfc))
				&& !empty($this->config->get('config_rainforest_main_formula_default_' . $crmfc))
				&& !empty($this->config->get('config_rainforest_main_formula_overload_' . $crmfc))
			){
				$this->formulaOverloadData[$crmfc] = [					
					'min' 		=> (float)$this->config->get('config_rainforest_main_formula_min_' . $crmfc),
					'max' 		=> (float)$this->config->get('config_rainforest_main_formula_max_' . $crmfc),
					'costprice' => (float)$this->config->get('config_rainforest_main_formula_costprice_' . $crmfc),
					'default' 	=> (float)$this->config->get('config_rainforest_main_formula_default_' . $crmfc),
					'formula' 	=> trim($this->config->get('config_rainforest_main_formula_overload_' . $crmfc))
				];
			}
		}

		return $this;
	}

	private function cacheCategoryOverloadRules(){
		$cachestring = 'amazonrainforest.pricelogic.categoriesOverloadRules';

		$categoriesOverloadRules = $this->cache->get($cachestring);

		if ($categoriesOverloadRules){
			$this->categoriesOverloadRules = $categoriesOverloadRules;
		} else {
			$query = $this->db->query("SELECT category_id, overload_max_multiplier, overload_max_wc_multiplier, overload_ignore_volumetric_weight FROM category 
				WHERE (overload_max_multiplier + overload_max_wc_multiplier + overload_ignore_volumetric_weight) > 0 ");

			foreach ($query->rows as $row){
				$this->categoriesOverloadRules[$row['category_id']] = array(
					'overload_max_multiplier' 				=> $row['overload_max_multiplier'],
					'overload_max_wc_multiplier' 			=> $row['overload_max_wc_multiplier'],
					'overload_ignore_volumetric_weight' 	=> $row['overload_ignore_volumetric_weight']
				);
			}

			$this->cache->set($cachestring, $this->categoriesOverloadRules);
		}

		return $this;
	}

	private function cacheCategoryDimensions(){
		$cachestring = 'amazonrainforest.pricelogic.defaultDimensions';

		$defaultDimensions = $this->cache->get($cachestring);

		if ($defaultDimensions){
			$this->defaultDimensions = $defaultDimensions;
		} else {
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

			$this->cache->set($cachestring, $this->defaultDimensions);
		}

		return $this;
	}

	private function cacheCategoryOverPriceRules(){
		$cachestring = 'amazonrainforest.pricelogic.categoriesOverPriceRules';

		$categoriesOverPriceRules = $this->cache->get($cachestring);

		if ($categoriesOverPriceRules){
			$this->categoriesOverPriceRules = $categoriesOverPriceRules;
		} else {
			$query = $this->db->query("SELECT * FROM category_overprice_rules WHERE 1");

			foreach ($query->rows as $row){
				if (empty($this->categoriesOverPriceRules[$row['category_id']])){
					$this->categoriesOverPriceRules[$row['category_id']] = [$row];
				} else {
					$this->categoriesOverPriceRules[$row['category_id']][] = $row;
				}		
			}
			$this->cache->set($cachestring, $this->categoriesOverPriceRules);
		}

		return $this;
	}

	private function setStoreSettings(){		

		$implode = [];
		foreach ($this->storesSettingsFields as $field){
			$implode[] = "`key` = '" . $field . "'";
		}

		$sql = "SELECT store_id, `key`, value FROM setting WHERE " . implode(' OR ', $implode) . "";
		$query = $this->db->query($sql);

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

		$implode = [];
		foreach ($this->storesSettingsFields2 as $field){
			$implode[] = "`key` LIKE ('" . $field . "%')";
		}

		$sql = "SELECT `key`, value FROM setting WHERE " . implode(' OR ', $implode) . "";
		$query = $this->db->query($sql);

		unset($row);
		foreach ($query->rows as $row){

			foreach ($this->storesSettingsFields2 as $field){
				if (strpos($row['key'], $field) !== false){
					$store_id = (int)str_replace($field . '_', '', $row['key']);
					$this->storesVolumetricWeightSettings[$store_id][$field] = $row['value'];
				}
			}

		}

		return $this;
	}

	public function getStoreSettings(){
		return $this->storesWarehouses;		
	}

	public function countOrderProfitablility($order_id){
		$order_query	= $this->db->ncquery("SELECT total_national, currency_code FROM `order` WHERE order_id = '" . (int)$order_id . "'");
		$products_query = $this->db->ncquery("SELECT op.*, p.costprice FROM order_product op LEFT JOIN product p ON (p.product_id = op.product_id) WHERE op.order_id = '" . (int)$order_id . "'");
		$totals_query 	= $this->db->ncquery("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "'");

		$costprice = 0;

		if ($products_query->num_rows && $totals_query->num_rows){
			foreach ($products_query->rows as $product){
				if ($product['costprice']){
					$costprice += ($product['costprice'] * $product['quantity']);
				} else {
					if ((float)$this->config->get('config_rainforest_default_costprice_multiplier_' . $this->config->get('config_store_id')) > 0){
						$costprice += (($product['price'] / $this->config->get('config_rainforest_default_costprice_multiplier_' . $this->config->get('config_store_id'))) * $product['quantity']);
					}					
				}
			}
			
			$this->db->query("UPDATE `order` SET costprice = '" . (float)$costprice . "' WHERE order_id = '" . (int)$order_id . "'");		

			$total = $order_query->row['total_national'];
			foreach ($totals_query->rows as $total_line){
				if ($total_line['code'] == 'total'){
					$total = $total_line['value_national'];
					break;
				}	
			}

			$total = round($this->currency->real_convert($total, $order_query->row['currency_code'], $this->config->get('config_currency')), 2);

			$diff = $total - $costprice;
			$profitability = round(($diff / $total), 3) * 100;

			echoLine('[PriceLogic::countOrderProfitablility] Order ' . $order_id . ' has costprice ' . $costprice . ' EUR, and total '. $total . ' EUR', 'i');	
			echoLine('[PriceLogic::countOrderProfitablility] Order ' . $order_id . ' has profitability ' . $profitability . '%', 'w');

			$this->db->query("UPDATE `order` SET profitability = '" . (float)$profitability . "' WHERE order_id = '" . (int)$order_id . "'");		

		} else {
			$this->db->query("UPDATE `order` SET profitability = '0' WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("UPDATE `order` SET costprice = '0' WHERE order_id = '" . (int)$order_id . "'");
			echoLine('[PriceLogic::countOrderProfitablility] Order ' . $order_id . ' has no products', 'e');			
		}	
	}	

	public function getProductDimensions($product){

		if (empty($this->defaultDimensions)){
			$this->cacheCategoryDimensions();
		}

		$weight = (float)$product['weight'];
		$weight_class_id = (int)$product['weight_class_id'];
		if ($product['pack_weight']){
			$weight 			= (float)$product['pack_weight'];
			$weight_class_id 	= (int)$product['pack_weight_class_id'];
		}

		$length = (float)$product['length'];
		$length_class_id = (int)$product['length_class_id'];
		if ($product['pack_length']){
			$length 			= (float)$product['pack_length'];
			$length_class_id 	= (int)$product['pack_length_class_id'];
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
			$weight 			= (float)$this->defaultDimensions[$product['main_category_id']]['default_weight'];
			$weight_class_id 	= (int)$this->defaultDimensions[$product['main_category_id']]['default_weight_class_id'];
		}

		if (!$length && !empty($this->defaultDimensions[$product['main_category_id']]['default_length'])){
			$length 			= (float)$this->defaultDimensions[$product['main_category_id']]['default_length'];
			$length_class_id 	= (int)$this->defaultDimensions[$product['main_category_id']]['default_length_class_id'];
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

	public function getProductVolumetricWeight($product, $store_id, $return_volumetric_weight = false, $volumetricWeightCoefficient = false){
		$productDimensions = $this->getProductDimensions($product);

		if (!$volumetricWeightCoefficient && !empty($this->storesVolumetricWeightSettings[$store_id]) && !empty($this->storesVolumetricWeightSettings[$store_id]['config_rainforest_volumetric_weight_coefficient'])){
			$volumetricWeightCoefficient = (float)$this->storesVolumetricWeightSettings[$store_id]['config_rainforest_volumetric_weight_coefficient'];
		}

		if ($productDimensions['weight_class_id'] == (int)$this->config->get('config_weight_class_id')){
			$weight = $productDimensions['weight'];
		} else {
			$weight = $this->weight->convert($productDimensions['weight'], $productDimensions['weight_class_id'], $this->config->get('config_weight_class_id'));
		}

		if ($productDimensions['length_class_id'] == (int)$this->config->get('config_length_class_id')){
			$length = $productDimensions['length'];
			$width 	= $productDimensions['width'];
			$height = $productDimensions['height'];
		} else {
			$length = $this->length->convert($productDimensions['length'], $productDimensions['length_class_id'], $this->config->get('config_length_class_id'));
			$width 	= $this->length->convert($productDimensions['width'], $productDimensions['length_class_id'], $this->config->get('config_length_class_id'));
			$height = $this->length->convert($productDimensions['height'], $productDimensions['length_class_id'], $this->config->get('config_length_class_id'));
		}

		if ($volumetricWeightCoefficient){
			$volumetricWeight = ($length * $width * $height) / $volumetricWeightCoefficient;
		} else {
			$volumetricWeight = $weight;
		}

		if ($return_volumetric_weight){
			return $volumetricWeight;
		}

		if ($volumetricWeight > $weight){
			return $volumetricWeight;
		}
		
		return $weight;			
	}

	public function getProductWeight($product){
		$productDimensions = $this->getProductDimensions($product);

		if ($productDimensions['weight_class_id'] == (int)$this->config->get('config_weight_class_id')){
			return $productDimensions['weight'];
		} else {
			return $this->weight->convert($productDimensions['weight'], $productDimensions['weight_class_id'], $this->config->get('config_weight_class_id'));
		}
	}

	public function getProductsByAsinExplicit($asin){
		$query = $this->db->query("SELECT *, (SELECT category_id FROM product_to_category p2cm WHERE p2cm.product_id = p.product_id AND category_id NOT IN (" . implode(',', $this->excluded_categories) . ") ORDER BY main_category DESC LIMIT 1) as main_category_id FROM product p WHERE asin = '" . $this->db->escape($asin) . "' AND is_markdown = 0");

			$results = [];
			if ($query->num_rows){
				foreach($query->rows as $row){
					$results[$row['product_id']] = $row;
				}

				return $results;
			}

			return false;
	}

	public function getProductsByAsin($asin){
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

	public function getProductMainCategoryID($product_id){
		$query = $this->db->query("SELECT category_id FROM product_to_category p2cm WHERE p2cm.product_id = '" . (int)$product_id . "' AND category_id NOT IN (" . implode(',', $this->excluded_categories) . ") ORDER BY main_category DESC LIMIT 1");

		if ($query->num_rows){
			return $query->row['category_id'];		
		}

		return false;
	}

	public function getCategoryOverpriceRules($category_id) {
		$category_overprice_rules = [];

		if (!empty($this->categoriesOverPriceRules[$category_id])){
			return $this->categoriesOverPriceRules[$category_id];
		}
		
		$query = $this->db->query("SELECT * FROM category_overprice_rules WHERE category_id = '" . (int)$category_id . "'");
		
		if ($query->num_rows){
			$this->categoriesOverPriceRules[$category_id] = $query->rows;
			return $query->rows;	
		}
		
		return false;
	}	

	public function getCategoryOverloadRules($category_id) {
		$category_overload_rules = [];

		if (!empty($this->categoriesOverloadRules[$category_id])){
			return $this->categoriesOverloadRules[$category_id];
		}
		
		$query = $this->db->query("SELECT category_id, overload_max_multiplier, overload_max_wc_multiplier, overload_ignore_volumetric_weight FROM category 
			WHERE category_id = '" . (int)$category_id . "' 
			AND (overload_max_multiplier + overload_max_wc_multiplier + overload_ignore_volumetric_weight) > 0 LIMIT 1");
		
		if ($query->num_rows){
			$this->categoriesOverloadRules[$category_id] = $query->row;
			return $query->row;	
		}
		
		return false;
	}	

	public function getProductOverPriceRules($product_id){
		$category_id = $this->getProductMainCategoryID($product_id);
		if (!$category_id){
			return false;
		}		

		$category_overprice_rules = false;
		while ($category_id && !$category_overprice_rules){
			$category_overprice_rules = $this->getCategoryOverpriceRules($category_id);
			
			if (!$category_overprice_rules){
				$category_id = $this->db->query("SELECT parent_id FROM category WHERE category_id = '" . (int)$category_id . "' LIMIT 1")->row['parent_id'];
			}			
		}

		return $category_overprice_rules;
	}

	public function getProductOverloadRules($product_id){
		$category_id = $this->getProductMainCategoryID($product_id);
		if (!$category_id){
			return false;
		}		

		$category_overload_rules = false;
		while ($category_id && !$category_overload_rules){
			$category_overload_rules = $this->getCategoryOverloadRules($category_id);
			
			if (!$category_overload_rules){
				$category_id = $this->db->query("SELECT parent_id FROM category WHERE category_id = '" . (int)$category_id . "' LIMIT 1")->row['parent_id'];
			}			
		}

		return $category_overload_rules;
	}	

	public function getProductOverPriceFormula($product_id, $amazonBestPrice = false, $store_id = 0){
		if (!$amazonBestPrice){
			$amazonBestPrice = (float)$this->db->query("SELECT amazon_best_price FROM product WHERE product_id = '" . (int)$product_id . "'")->row['amazon_best_price'];
		}

		if ($productOverPriceRules = $this->getProductOverPriceRules($product_id)){
			if ($overloadedFormula = $this->checkOverloadFormula($amazonBestPrice, $productOverPriceRules)){
				if ($costPriceOverloadedFormula = $this->checkOverloadFormula($amazonBestPrice)){
					$costprice = $costPriceOverloadedFormula['costprice'];
				} else {
					$costprice = (float)$this->config->get('config_rainforest_default_costprice_multiplier_' . $store_id);
				}

				if ($multiplierPriceOverloadedFormula = $this->checkOverloadFormula($amazonBestPrice)){
					$formula = $this->replaceMainMultiplier($multiplierPriceOverloadedFormula['formula'], $overloadedFormula['multiplier']);
				} else {
					$formula = $this->replaceMainMultiplier($this->config->get('config_rainforest_main_formula'), $overloadedFormula['multiplier']);
				}

				return [
					'min' 		=> $overloadedFormula['min'],
					'max' 		=> $overloadedFormula['max'],
					'default' 	=> $overloadedFormula['default_multiplier'],
					'costprice' => $costprice,
					'formula' 	=> $formula
				];
			}					
		}

		return false;
	}

	public function checkIfWeCanUpdateProductOffers($product_id, $warehouse_identifier = 'current', &$skip_reason = ''){		
		if (!$this->config->get('config_rainforest_enable_pricing')){
			return false;
		}

		$query = $this->db->ncquery("SELECT * FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
		
		$proceedWithPrice = false;
		if ($query->num_rows && $query->row['asin']){
			$proceedWithPrice = true;
			
			if ($this->config->get('config_rainforest_enable_offers_for_stock')){			
				$proceedWithPrice = true;
			} else {				
				if ($warehouse_identifier == 'current'){
					$warehouse_identifier = $this->config->get('config_warehouse_identifier');
				}

				if (($query->row[$warehouse_identifier] + $query->row[$warehouse_identifier . '_onway']) <= 0){			
					$proceedWithPrice = true;
				} else {
					echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product is in real stock, or is on way, skipping', 'e');
					$skip_reason = 'IS_ON_STOCK';						
					$proceedWithPrice = false;
				}
			}

			if ($proceedWithPrice && $this->config->get('config_rainforest_pass_offers_for_ordered')){
				if ($query->row['actual_cost_date'] == '0000-00-00'){			
					$proceedWithPrice = true;
				} else {

					if ($this->config->get('config_rainforest_pass_offers_for_ordered_days')){
						$dateWhenWeCanToUpdate = date('Y-m-d', strtotime('+' . (int)$this->config->get('config_rainforest_pass_offers_for_ordered_days') . ' days', strtotime($query->row['actual_cost_date'])));

						if ($dateWhenWeCanToUpdate >= date('Y-m-d')){
							echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Last buy ' . $dateWhenWeCanToUpdate . ', skipping', 'e');
							$skip_reason = 'LAST_PURCHASE_DEADLINE';
							$proceedWithPrice = false;
						} else {							
							$proceedWithPrice = true;
						}

					} else {
						echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] No purchase deadline, skipping', 'e');
						$skip_reason = 'LAST_PURCHASE_DEADLINE';
						$proceedWithPrice = false;
					}					
				}
			}
			
			if ($proceedWithPrice && $query->row['amzn_ignore']){
				echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product must be ignored due of amzn_ignore set, skipping', 'e');
				$skip_reason = 'AMZN_IGNORE_SET';
				$proceedWithPrice = false;
			}

			if ($this->config->get('config_rainforest_enable_offers_for_added_from_amazon')){
				if ($proceedWithPrice && !$query->row['added_from_amazon']){
					echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product must be ignored due of config_rainforest_enable_offers_for_added_from_amazon = ON and is not added from amazon, skipping', 'e');
					$skip_reason = 'NOT_ADDED_FROM_AMAZON';
					$proceedWithPrice = false;
				}
			}

			if ($proceedWithPrice && $query->row['is_markdown']){
				echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product is markdown, skipping', 'e');
				$skip_reason = 'IS_MARKDOWN';
				$proceedWithPrice = false;
			}

			if ($proceedWithPrice && $this->config->get('config_rainforest_disable_offers_use_field_ignore_parse')){
				if ($query->row['ignore_parse'] && $query->row['ignore_parse_date_to'] == '0000-00-00'){
					echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product must be ignored because of ignore_parse set, skipping', 'e');
					$skip_reason = 'IGNORE_PARSE_SET';
					$proceedWithPrice = false;
				} elseif ($query->row['ignore_parse'] && !empty($query->row['ignore_parse_date_to'])){					
					if (date('Y-m-d') <= $query->row['ignore_parse_date_to']){
						echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product must be ignored due of ignore_parse and date set, skipping', 'e');
						$skip_reason = 'IGNORE_PARSE_SET';
						$proceedWithPrice = false;
					}
				}
			}

			if ($proceedWithPrice && $this->config->get('config_rainforest_disable_offers_if_has_special')){
				$special_query = $this->db->query("SELECT * FROM product_special ps WHERE ps.product_id = '" . (int)$product_id . "' AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

				if ($special_query->num_rows){
					echoLine('[PriceLogic::checkIfWeCanUpdateProductOffers] Product has actual special, skipping', 'e');
					$skip_reason = 'HAS_ACTUAL_SPECIAL';
					$proceedWithPrice = false;
				}
			}
		}

		return $proceedWithPrice;
	}

	//Проверяет, есть ли товар сейчас в каком-либо заказе
	public function checkIfProductIsInOrders($product_id){
		$query = $this->db->query("SELECT * FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.order_status_id > 0 AND op.product_id = '" . (int)$product_id . "'");

		return $query->num_rows;
	}

	//Проверяет, есть ли товар сейчас на складе
	public function checkIfProductIsOnAnyWarehouse($product_id){
		$query = $this->db->query("SELECT * FROM product WHERE product_id = '" . $product_id . "' AND (" . $this->buildStockQueryField() . " > 0)");

		return $query->num_rows;
	}

	//Проверяет, есть ли товар сейчас на складе
	public function checkIfProductIsOnWarehouse($product_id, $warehouse_identifier){
		$query = $this->db->query("SELECT * FROM product WHERE product_id = '" . $product_id . "' AND (`" . $warehouse_identifier . "` + `" . $warehouse_identifier . '_onway' . "` > 0)");

		return $query->num_rows;
	}

	public function updateProfitability($product_id = false){
		if ($product_id){
			$this->db->query("UPDATE product SET profitability = ((price - costprice)/price*100) WHERE product_id = '" . (int)$product_id . "' AND price > 0");
		} else {
			$this->db->query("UPDATE product SET profitability = ((price - costprice)/price*100) WHERE price > 0");
		}
	}
	
	public function updatePricesFromDelayed(){		
		if ($this->config->get('config_rainforest_delay_price_setting')){
			echoLine('[PriceLogic::updatePricesFromDelayed] DELAYED PRICES IS ON!', 'w');

			$this->db->query("UPDATE product SET price = price_delayed WHERE price_delayed > 0");			
			$this->db->query("UPDATE product SET price_delayed = 0 WHERE price_delayed > 0");		

			$this->updateProfitability();	

			$this->db->query("UPDATE product_price_to_store SET price = price_delayed WHERE price_delayed > 0");	
			$this->db->query("UPDATE product_price_to_store SET price_delayed = 0 WHERE price_delayed > 0");					
		} else {
			echoLine('[PriceLogic::updatePricesFromDelayed] DELAYED PRICES IS OFF, UPDATING IN LIVE!', 'w');
		}
	}

	public function updateProductPriceInDatabase($product_id, $price){
		$field = 'price';
		if ($this->config->get('config_rainforest_delay_price_setting')){
			$field = 'price_delayed';
		}

		$this->db->query("UPDATE product SET 
			" . $field . " 		= '" . (float)$price . "' 
			WHERE product_id 	= '" . (int)$product_id . "' 
			AND is_markdown 	= 0");

		$this->db->query("UPDATE product SET 
			price 				= '" . (float)$price . "' 
			WHERE product_id 	= '" . (int)$product_id . "' 
			AND price = 0
			AND is_markdown 	= 0");

		$this->updateProfitability($product_id);

		$this->priceUpdaterQueue->addToQueue($product_id);
	}

	public function updateProductCostPriceInDatabase($product_id, $costprice){
		$this->db->query("UPDATE product SET 
			costprice 			= '" . (float)$costprice . "' 
			WHERE product_id 	= '" . (int)$product_id . "'
			AND is_markdown 	= 0");		

		$this->updateProfitability($product_id);

		$this->priceUpdaterQueue->addToQueue($product_id);
	}

	public function updateProductPriceToStoreInDatabase($product_id, $price, $store_id){

		$field = 'price';
		if ($this->config->get('config_rainforest_delay_price_setting')){
			$field = 'price_delayed';
		}

		$this->db->query("INSERT INTO product_price_to_store SET 
			store_id 			= '" . (int)$store_id . "',
			product_id 			= '" . (int)$product_id . "',
			" . $field . " 		= '" . (float)$price . "',
			special 			= '0',
			settled_from_1c 	= '0',
			dot_not_overload_1c = '0'
			ON DUPLICATE KEY UPDATE
			" . $field . " 		= '" . (float)$price . "',
			settled_from_1c 	= '0',
			dot_not_overload_1c = '0'");

		$this->priceUpdaterQueue->addToQueue($product_id);
	}

	public function getMainMultiplier($formula){
		$multiplier = $this->config->get('config_rainforest_default_multiplier_' . $this->config->get('config_store_id'));
		$exploded = explode($this->costDividerSymbol, $formula);

		if (count($exploded) == 2 && !empty($exploded[1])){
			$guess = trim(str_replace('MULTIPLY', '', $exploded[1]));

			if (is_numeric($guess)){
				$multiplier = (float)$guess;
			}
		}

		return $multiplier;
	}

	public function replaceMainMultiplier($formula, $multiplier){
		$exploded = explode($this->costDividerSymbol, $formula);

		if (count($exploded) == 2 && !empty($exploded[0])){
			$mainFormula = $exploded[0];
			$mainFormula .= ' ' . $this->costDividerSymbol . ' ';
			$mainFormula .= ' MULTIPLY ' . $multiplier;

			return $mainFormula;
		}

		return $formula;
	}

	//Проверяет вхождение товара в ценовые диапазоны перезагружающих основную формулу
	public function checkOverloadFormula($amazonBestPrice, $formulaOverloadData = []){
		if (!$formulaOverloadData){
			$formulaOverloadData = $this->formulaOverloadData;
		}

		foreach ($formulaOverloadData as $key => $formula){
			if ($amazonBestPrice >= $formula['min'] && $amazonBestPrice < $formula['max']){
				return $formula;
			}
		}

		return false;
	}

	public function compileFormula($amazonBestPrice, $data, $overloadMainFormula = false){
		return $this->mainFormula($amazonBestPrice, $data, $overloadMainFormula, true);
	}

	public function mainCostPriceFormula($amazonBestPrice, $data, $overloadMainFormula = false){
		return $this->mainFormula($amazonBestPrice, $data, $overloadMainFormula, false, true);
	}

	public function compileCostPriceFormula($amazonBestPrice, $data, $overloadMainFormula = false){
		return $this->mainFormula($amazonBestPrice, $data, $overloadMainFormula, true, true);
	}

	//This is main function to compile and eval main price formula
	public function mainFormula($amazonBestPrice, $data, $overloadMainFormula = false, $justCompile = false, $countCostPrice = false){

		if ($overloadMainFormula){
			$mainFormula = $overloadMainFormula;
		} else {
			$mainFormula = $this->config->get('config_rainforest_main_formula');
		}		

		if ($data['WEIGHT']){
			$from = [
				'PRICE',
				'WEIGHT',
				'KG_LOGISTIC',
				'VAT_SRC',
				'VAT_DST',
				'TAX',
				'SUPPLIER',
				'INVOICE',				
				'PLUS', 
				'MINUS',
				'MULTIPLY', 
				'DIVIDE'
			];

			$to = [
				$amazonBestPrice, 
				$data['WEIGHT'], 
				$data['KG_LOGISTIC'],
				$data['VAT_SRC'],
				$data['VAT_DST'],
				$data['TAX'],
				$data['SUPPLIER'],
				$data['INVOICE'],
				'+', 
				'-',
				'*', 
				'/'
			];

			if ($countCostPrice){
				$exploded = explode($this->costDividerSymbol, $mainFormula);

				if (count($exploded) == 2 && !empty($exploded[0])){
					$mainFormula = $exploded[0];
				}
			} else {
				$from[] 	= $this->costDividerSymbol;
				$to[] 		= '';
			}			

			$mainFormula = str_replace($from, $to, $mainFormula);

			$resultPrice = eval('return ' . $mainFormula . ';');

			if (!empty($data['PRODUCT_ID'])){
				$productOverloadRules = $this->getProductOverloadRules($data['PRODUCT_ID']);

				if (!empty($productOverloadRules['overload_max_multiplier'])){
					$data['MAX_MULTIPLIER'] = (float)$productOverloadRules['overload_max_multiplier'];
				}
			}

			if ($resultPrice > $amazonBestPrice * $data['MAX_MULTIPLIER']){
				if ($countCostPrice){
					$resultPrice = $amazonBestPrice * $data['DEFAULT_COSTPRICE_MULTIPLIER'];
				} else {
					$resultPrice = $amazonBestPrice * $data['DEFAULT_MULTIPLIER'];
				}

				if ($justCompile){
					if ($countCostPrice){
						return 'MAX_MULTIPLIER x' . $data['MAX_MULTIPLIER'] . ' TRIGGER: ' . $amazonBestPrice . ' * ' . $data['DEFAULT_COSTPRICE_MULTIPLIER'];
					} else {
						return 'MAX_MULTIPLIER x' . $data['MAX_MULTIPLIER'] . ' TRIGGER: ' . $amazonBestPrice . ' * ' . $data['DEFAULT_MULTIPLIER'];
					}					
				}
			}

			if ($justCompile){
				return $mainFormula;
			}

		} else {
			if ($countCostPrice){
				$resultPrice = $amazonBestPrice * $data['DEFAULT_COSTPRICE_MULTIPLIER'];
			} else {
				$resultPrice = $amazonBestPrice * $data['DEFAULT_MULTIPLIER'];
			}

			if ($justCompile){
				if ($countCostPrice){
					return 'NO_WEIGHT TRIGGER: ' . $amazonBestPrice . ' * ' . $data['DEFAULT_COSTPRICE_MULTIPLIER'];
				} else {
					return 'NO_WEIGHT TRIGGER: ' . $amazonBestPrice . ' * ' . $data['DEFAULT_MULTIPLIER'];
				}				
			}
		}		

		return $resultPrice;
	}

	public function recalculateProductWeight($product, $store_id, $justCompile = false){
		$data = [];
		$productOverloadRules = $this->getProductOverloadRules($product['product_id']);		

		$productWeight 		= $this->getProductVolumetricWeight($product, $store_id);
		$productWeightReal 	= $this->getProductWeight($product);

		$data['Product Weight'] 		= $this->weight->format($productWeight, $this->config->get('config_weight_class_id'));
		$data['Product Weight Real'] 	= $this->weight->format($productWeightReal, $this->config->get('config_weight_class_id'));

		$config_rainforest_use_volumetric_weight = $this->storesVolumetricWeightSettings[$store_id]['config_rainforest_use_volumetric_weight'];
		if (!empty($productOverloadRules['overload_ignore_volumetric_weight'])){
			$config_rainforest_use_volumetric_weight = false;
			$data['USE_VOLUMETRIC'] = 'OFF, OVERLOAD FROM CATEGORY';
		}

		$config_rainforest_volumetric_max_wc_multiplier = $this->config->get('config_rainforest_volumetric_max_wc_multiplier');
		if (!empty($productOverloadRules['overload_max_wc_multiplier'])){
			$config_rainforest_volumetric_max_wc_multiplier = (float)$productOverloadRules['overload_max_wc_multiplier'];
			$data['MAX_WC_MULTIPLIER'] = (float)$productOverloadRules['overload_max_wc_multiplier'] . ', OVERLOAD FROM CATEGORY';
		}

		if ($config_rainforest_use_volumetric_weight){
			if ($productWeight > ($productWeightReal * (float)$config_rainforest_volumetric_max_wc_multiplier)){
				$productWeight = $productWeightReal;
			}
		} else {
			$productWeight = $productWeightReal;
		}

		$data['Compiled Weight'] = $this->weight->format($productWeight, $this->config->get('config_weight_class_id'));

		if ($justCompile){
			return $data;
		}

		return $productWeight;
	}

	public function updateProductPrices($asin, $amazonBestPrice, $explicit = false, $bestOfferOriginal = [], $bestOffer = []){	
		$historyData = [
			'asin' 					=> $asin,
			'store_id' 				=> 0,
			'amazon_best_price' 	=> $amazonBestPrice,
			'original_offer_data' 	=> $bestOfferOriginal,
			'offer_data' 			=> $bestOffer,
			'weight' 				=> 0,
			'price' 				=> 0,
			'costprice' 			=> 0,
			'profitability' 		=> 0,
			'skipped' 				=> ''
		];

		if ($this->config->get('config_rainforest_enable_pricing')){

			if ($explicit){
				$products = $this->getProductsByAsinExplicit($asin);
			} else {
				$products = $this->getProductsByAsin($asin);
			}

			if ($products){
				foreach ($products as $product_id => $product){							
					if (!$product['amzn_ignore']){						
						foreach ($this->storesWarehouses as $store_id => $storeWarehouses){
							$warehouse_identifier = $storeWarehouses['config_warehouse_identifier_local'];

							$productWeight = $this->recalculateProductWeight($product, $store_id);
							$historyData['weight'] = $productWeight;

							if ($this->config->get('config_rainforest_kg_price_' . $store_id) || $this->config->get('config_rainforest_default_multiplier_' . $store_id)){
								if ($this->checkIfWeCanUpdateProductOffers($product_id, $warehouse_identifier, $skip_reason)){
									$params = [
										'PRODUCT_ID'					=> (float)$product['product_id'],
										'WEIGHT' 						=> (float)$productWeight,
										'KG_LOGISTIC' 					=> (float)$this->config->get('config_rainforest_kg_price_' . $store_id),
										'DEFAULT_MULTIPLIER' 			=> (float)$this->config->get('config_rainforest_default_multiplier_' . $store_id),
										'DEFAULT_COSTPRICE_MULTIPLIER' 	=> (float)$this->config->get('config_rainforest_default_costprice_multiplier_' . $store_id),
										'MAX_MULTIPLIER' 				=> (float)$this->config->get('config_rainforest_max_multiplier_' . $store_id),
										'VAT_SRC' 						=> (float)$this->config->get('config_rainforest_formula_vat_src_' . $store_id),
										'VAT_DST' 						=> (float)$this->config->get('config_rainforest_formula_vat_dst_' . $store_id),
										'TAX' 							=> (float)$this->config->get('config_rainforest_formula_tax_' . $store_id),
										'SUPPLIER' 						=> (float)$this->config->get('config_rainforest_formula_supplier_' . $store_id),
										'INVOICE' 						=> (float)$this->config->get('config_rainforest_formula_invoice_' . $store_id),										
									];

									if ($overloadMainFormulaForProduct = $this->getProductOverPriceFormula($product_id, $amazonBestPrice, $store_id)){
										echoLine('[PriceLogic::updateProductPrices] Using overloaded by category formula, multiplier is ' . $overloadMainFormulaForProduct['default'], 'w');

										$params['DEFAULT_MULTIPLIER'] 			= $overloadMainFormulaForProduct['default'];
										$params['DEFAULT_COSTPRICE_MULTIPLIER'] = $overloadMainFormulaForProduct['costprice'];

										$newPrice 	= $this->mainFormula($amazonBestPrice, $params, $overloadMainFormulaForProduct['formula']);
										$newCost 	= $this->mainCostPriceFormula($amazonBestPrice, $params, $overloadMainFormulaForProduct['formula']);
									} elseif ($overloadMainFormula = $this->checkOverloadFormula($amazonBestPrice)){
										echoLine('[PriceLogic::updateProductPrices] Using overloaded by price main formula, multiplier is ' . $overloadMainFormula['default'], 'w');

										$params['DEFAULT_MULTIPLIER'] 			= $overloadMainFormula['default'];
										$params['DEFAULT_COSTPRICE_MULTIPLIER'] = $overloadMainFormula['costprice'];

										$newPrice 	= $this->mainFormula($amazonBestPrice, $params, $overloadMainFormula['formula']);
										$newCost 	= $this->mainCostPriceFormula($amazonBestPrice, $params, $overloadMainFormula['formula']);
									} else {
										echoLine('[PriceLogic::updateProductPrices] Using main formula', 'w');

										$newPrice 	= $this->mainFormula($amazonBestPrice, $params);
										$newCost 	= $this->mainCostPriceFormula($amazonBestPrice, $params);
									}									

									echoLine('[PriceLogic::updateProductPrices]' . ' Product ' . $product_id . ', ' . $asin . ', price ' . $amazonBestPrice . ', wght: ' . $productWeight, 'i');
									echoLine('[PriceLogic::updateProductPrices] PRICE: store ' . $store_id . ' is ' . $newPrice . ' EUR', 'w');							
									echoLine('[PriceLogic::updateProductPrices] COST: store ' . $store_id . ' is ' . $newCost . ' EUR', 'w');	

									$historyData['price'] 			= $newPrice;
									$historyData['costprice'] 		= $newCost;
									if ($newPrice > 0){
										$historyData['profitability'] 	= ($newPrice - $newCost)/$newPrice*100;
									} else {
										$historyData['profitability'] 	= 0;
									}
									
									if ($this->config->get('config_rainforest_default_store_id') != -1 && $store_id == $this->config->get('config_rainforest_default_store_id')){
										$this->updateProductPriceInDatabase($product_id, $newPrice);
										$this->updateProductCostPriceInDatabase($product_id, $newCost);
									} else {
										$this->updateProductPriceToStoreInDatabase($product_id, $newPrice, $store_id);
										$this->updateProductCostPriceInDatabase($product_id, $newCost);

										$historyData['store_id'] = $store_id;
									}
								} else {
									$historyData['skipped'] = $skip_reason;
								}

								$this->priceHistory->addBestOfferHistory($historyData);
							}
						}
					} 
				}
			}
		}
	}

	/*
	Логика работы со статусами складов
	*/
	public function buildStockQueryField(){
		if (!$this->storesWarehouses){
			$this->setStoreSettings();
		}

		$implode = [];
		foreach ($this->storesWarehouses as $store_id => $storesWarehouse){
			$implode[] = $storesWarehouse['config_warehouse_identifier'];
			$implode[] = $storesWarehouse['config_warehouse_identifier'] . '_onway';
		}

		return implode(' + ', $implode);
	}

	/*
		Логика обработки товара, которого нет в наличии на Amazon с учётом его наличия на локальных складах
	*/
	public function setProductNoOffers($asin){
		if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_status_id')){

			echoLine('[PriceLogic::setProductNoOffers] ' . $asin  . ', setting status marker config_rainforest_nooffers_status_id', 'e');
			$sql = "UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' ";
			$sql .= " WHERE asin = '" . $this->db->escape($asin) . "' AND added_from_amazon = 1 AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (" . $this->buildStockQueryField() . " = 0)";
			$this->db->query($sql);		

			echoLine('[PriceLogic::setProductNoOffers] ' . $asin  . ', clearing status overload table', 'e');
			$sql = "DELETE FROM product_stock_status ";
			$sql .= " WHERE product_id IN ";
			$sql .= " (SELECT product_id FROM product WHERE asin = '" . $this->db->escape($asin) . "' AND added_from_amazon = 1 AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (" . $this->buildStockQueryField() . " = 0))";
			$this->db->query($sql);			
		}

		if ($this->config->get('config_rainforest_nooffers_action_for_manual') && $this->config->get('config_rainforest_nooffers_status_id_for_manual')){

			echoLine('[PriceLogic::setProductNoOffers] ' . $asin  . ', setting status marker config_rainforest_nooffers_status_id_for_manual', 'e');
			$sql = "UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id_for_manual') . "' ";
			$sql .= " WHERE asin = '" . $this->db->escape($asin) . "' AND added_from_amazon = 0 AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (" . $this->buildStockQueryField() . " = 0)";
			$this->db->query($sql);						

			echoLine('[PriceLogic::setProductNoOffers] ' . $asin  . ', clearing status overload table', 'e');
			$sql = "DELETE FROM product_stock_status ";
			$sql .= " WHERE product_id IN ";
			$sql .= " (SELECT product_id FROM product WHERE asin = '" . $this->db->escape($asin) . "' AND added_from_amazon = 0 AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 AND (" . $this->buildStockQueryField() . " = 0))";
			$this->db->query($sql);			
		}
	}

	/*
		Логика обработки товара, который есть в наличии на Amazon с учётом его наличия на локальных складах
	*/
	public function setProductOffers($asin){
		if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_status_id')){
			echoLine('[PriceLogic::setProductOffers] ' . $asin . ', setting status marker config_stock_status_id', 's');
			$sql = "UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_stock_status_id') . "' ";
			$sql .= " WHERE asin = '" . $this->db->escape($asin) . "'  AND added_from_amazon = 1 AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 ";
			$sql .= " AND (stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' OR stock_status_id = '" . (int)$this->config->get('config_not_in_stock_status_id') ."')";

			$this->db->query($sql);

			echoLine('[PriceLogic::setProductOffers] ' . $asin . ', clearing status overload table', 's');
			$sql = "DELETE FROM product_stock_status ";
			$sql .= " WHERE product_id IN ";
			$sql .= " (SELECT product_id FROM product WHERE asin = '" . $this->db->escape($asin) . "' AND added_from_amazon = 1  AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 ";
			$sql .= "AND (stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' OR stock_status_id = '" . (int)$this->config->get('config_not_in_stock_status_id') ."'))";
			$this->db->query($sql);
		}

		if ($this->config->get('config_rainforest_nooffers_action_for_manual') && $this->config->get('config_rainforest_nooffers_status_id_for_manual', 's')){
			echoLine('[PriceLogic::setProductOffers] ' . $asin . ', setting status marker config_stock_status_id');
			$sql = "UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_stock_status_id') . "' ";
			$sql .= " WHERE asin = '" . $this->db->escape($asin) . "'  AND added_from_amazon = 1 AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 ";
			$sql .= " AND (stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' OR stock_status_id = '" . (int)$this->config->get('config_not_in_stock_status_id') ."')";

			$this->db->query($sql);

			echoLine('[PriceLogic::setProductOffers] ' . $asin . ', clearing status overload table', 's');
			$sql = "DELETE FROM product_stock_status ";
			$sql .= " WHERE product_id IN ";
			$sql .= " (SELECT product_id FROM product WHERE asin = '" . $this->db->escape($asin) . "' AND added_from_amazon = 1  AND is_markdown = 0 AND status = 1 AND amzn_ignore = 0 ";
			$sql .= "AND (stock_status_id = '" . (int)$this->config->get('config_rainforest_nooffers_status_id') . "' OR stock_status_id = '" . (int)$this->config->get('config_not_in_stock_status_id') ."'))";
			$this->db->query($sql);
		}
	}


	/*
		Принудительно ставим статус "есть в наличии на складе для той страны, в которой он есть"
	*/
	public function setProductStockInWarehouse($product_id, $warehouse_identifier){			
		if (isset($this->warehousesStores[$warehouse_identifier])){
			echoLine('[PriceLogic::setProductStockInWarehouse] Setting status config_in_stock_status_id for ' . $product_id . ' and warehouse ' . $this->warehousesStores[$warehouse_identifier]);

			$sql = "INSERT INTO product_stock_status SET ";
			$sql .= " store_id = '" . (int)$this->warehousesStores[$warehouse_identifier] . "', ";
			$sql .= " product_id = '" . (int)$product_id . "', ";
			$sql .= " stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "' ";
			$sql .= " ON DUPLICATE KEY UPDATE stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "'";
			$this->db->query($sql);		

			if ($this->config->get('config_single_store_enable')){
				$this->db->query("UPDATE product SET stock_status_id = '" . (int)$this->config->get('config_in_stock_status_id') . "' WHERE product_id = '" . (int)$product_id . "'");
			}		
		}
	}

	private function stockStatusQuery($data){
		$sql = "INSERT INTO product_stock_status(product_id, store_id, stock_status_id) 
				SELECT product_id, '" . (int)$data['store_id'] . "', '" . (int)$data['stock_status_id'] . "' FROM product 
				WHERE `" . $data['warehouse_identifier'] . "` = 0
				AND status = 1 
				AND amzn_ignore = 0 
				AND is_virtual = 0 
				AND asin <> '' 
				AND amzn_no_offers = " . (int)$data['amzn_no_offers'] . "
				AND added_from_amazon = " . (int)$data['added_from_amazon'] . "
				AND product_id IN 
				(SELECT product_id FROM product_stock_status pss2 WHERE store_id = '" . (int)$data['store_id'] . "' 
				AND stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "')
				ON DUPLICATE KEY UPDATE stock_status_id = '" . (int)$data['stock_status_id'] . "'";
		
		$this->db->query($sql);
	}

	/*
		Для товаров, которых нет в наличии на определенном складе, при этом статус установлен как "есть на складе", мы должны поставить его в 
		1. Наличие уточняйте, если для товара нет предложений на амазон и включена настройка "менять статус"
		2. Есть в наличии, если для товара есть предложения на амазон, либо выключена настройка "менять статус"
	*/

	public function setProductStockStatusesGlobal(){			
		foreach ($this->storesWarehouses as $store_id => $storesWarehouse) {
			$warehouse_identifier = $storesWarehouse['config_warehouse_identifier_local'];

			echoLine('[PriceLogic::setProductStockStatusesGlobal] Working with warehouse ' . $warehouse_identifier);

			#Глобальное обновление где больше нуля
			echoLine('[PriceLogic::setProductStockStatusesGlobal] setting config_stock_status_id for products in stock on Amazon: ' . $this->config->get('config_stock_status_id'), 's');
			$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_stock_status_id') . "' WHERE status = 1 AND stock_status_id <> '" . $this->config->get('config_stock_status_id') . "' AND (added_from_amazon = 1 AND amzn_no_offers = 0)");

			echoLine('[PriceLogic::setProductStockStatusesGlobal] setting config_stock_status_id for products in stock on warehouses: ' . $this->config->get('config_stock_status_id'), 's');
			$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_stock_status_id') . "' WHERE status = 1 AND stock_status_id <> '" . $this->config->get('config_stock_status_id') . "' AND (" . $this->buildStockQueryField() . " > 0)");		

			if ($this->config->get('config_single_store_enable')){
				echoLine('[PriceLogic::setProductStockStatusesGlobal] setting config_in_stock_status_id for products in stock on warehouses ' . $this->config->get('config_in_stock_status_id'), 's');
				$this->db->query("UPDATE product SET stock_status_id = '" . $this->config->get('config_in_stock_status_id') . "' WHERE status = 1 AND stock_status_id <> '" . $this->config->get('config_in_stock_status_id') . "' AND (`" . $warehouse_identifier . "` > 0)");	
			}	

			$data = [
					'store_id' 				=> $store_id,
					'warehouse_identifier' 	=> $warehouse_identifier,
					'added_from_amazon' 	=> 1,
					'amzn_no_offers' 		=> 1					
			];
			if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_status_id')){ 
				echoLine('[PriceLogic::setProductStockStatusesGlobal] config_rainforest_nooffers_action = 1, no_offers = 1, status: ' . $this->config->get('config_rainforest_nooffers_status_id'));

				$data['stock_status_id'] = $this->config->get('config_rainforest_nooffers_status_id');
				$this->stockStatusQuery($data);
			} else {
				echoLine('[PriceLogic::setProductStockStatusesGlobal] config_rainforest_nooffers_action = 0, no_offers = 1, status: ' . $this->config->get('config_stock_status_id'));

				$data['stock_status_id'] = $this->config->get('config_stock_status_id');
				$this->stockStatusQuery($data);
			}


			$data = [
					'store_id' 				=> $store_id,
					'warehouse_identifier' 	=> $warehouse_identifier,
					'added_from_amazon' 	=> 0,
					'amzn_no_offers' 		=> 1					
			];
			if ($this->config->get('config_rainforest_nooffers_action_for_manual') && $this->config->get('config_rainforest_nooffers_status_id_for_manual')){ 
				echoLine('[PriceLogic::setProductStockStatusesGlobal] config_rainforest_nooffers_action_for_manual = 1, no_offers = 1, status: ' . $this->config->get('config_rainforest_nooffers_status_id_for_manual'));

				$data['stock_status_id'] = $this->config->get('config_rainforest_nooffers_status_id_for_manual');
				$this->stockStatusQuery($data);
			} else {
				echoLine('[PriceLogic::setProductStockStatusesGlobal] config_rainforest_nooffers_action_for_manual = 0, no_offers = 1, status: ' . $this->config->get('config_stock_status_id'));

				$data['stock_status_id'] = $this->config->get('config_stock_status_id');
				$this->stockStatusQuery($data);
			}
		}
	}
}
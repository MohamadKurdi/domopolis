<?

namespace hobotix\Amazon;

class OffersParser
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\OffersParser';
	
	private $db;	
	private $config;
	private $log;

	public $Suppliers;
	public $PriceLogic;


	
	public function __construct($registry){

		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->log = $registry->get('log');

		require_once(dirname(__FILE__) . '/Suppliers.php');
		$this->Suppliers = new Suppliers($registry);

		require_once(dirname(__FILE__) . '/PriceLogic.php');
		$this->PriceLogic = new PriceLogic($registry);
	}

	public function getTotalProductsToGetOffers(){
		$result = [];
				
		$sql = " FROM product p
			WHERE status = 1 
			AND amzn_ignore = 0		
			AND is_virtual = 0
			AND is_markdown = 0			
			AND stock_status_id <> '" . $this->config->get('config_not_in_stock_status_id') . "'			
			AND (" . $this->PriceLogic->buildStockQueryField() . " = 0)
			AND (NOT ISNULL(p.asin) OR p.asin <> '')";

		if ($this->config->get('config_rainforest_enable_offers_only_for_filled')){
			$sql .= "AND p.filled_from_amazon = 1";
		}


		$sql .= " AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1))";
		$sql .= " AND (amzn_last_offers = '0000-00-00 00:00:00' OR DATE(amzn_last_offers) <= DATE(DATE_ADD(NOW(), INTERVAL -'" . $this->config->get('config_rainforest_update_period') . "' DAY)))";

		$query = $this->db->ncquery("SELECT COUNT(DISTINCT(asin)) as total " . $sql . "");

		return $query->row['total'];
	}	
	
	public function getProductsToGetOffers(){
		$result = [];		

		$sql = " FROM product p
			WHERE status = 1 
			AND amzn_ignore = 0		
			AND is_virtual = 0
			AND is_markdown = 0		
			AND stock_status_id <> '" . $this->config->get('config_not_in_stock_status_id') . "'			
			AND (" . $this->PriceLogic->buildStockQueryField() . " = 0)
			AND (NOT ISNULL(p.asin) OR p.asin <> '')";

		if ($this->config->get('config_rainforest_enable_offers_only_for_filled')){
			$sql .= "AND p.filled_from_amazon = 1";
		}

		$sql .= " AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1))";
		$sql .= " AND (amzn_last_offers = '0000-00-00 00:00:00' OR DATE(amzn_last_offers) <= DATE(DATE_ADD(NOW(), INTERVAL -'" . $this->config->get('config_rainforest_update_period') . "' DAY)))";

		$query = $this->db->ncquery("SELECT DISTINCT(asin) " . $sql . " ORDER BY amzn_last_offers ASC LIMIT " . (int)\hobotix\RainforestAmazon::offerParserLimit);

		foreach ($query->rows as $row){
			if (trim($row['asin'])){
				$result[] = $row['asin'];
			}
		}

		return $result;
	}	

	public function getProductsAmazonQueue(){
		$result = [];
		$query = $this->db->ncquery("SELECT DISTINCT(asin) FROM amzn_product_queue");

		foreach ($query->rows as $row){
			if (trim($row['asin'])){
				$result[] = $row['asin'];
			}
		}

		return $result;
	}

	public function clearProductsAmazonQueue($asin = false){
		if ($asin){
			$this->db->query("DELETE FROM amzn_product_queue WHERE asin = '" . $this->db->escape($asin) . "'");
		} else {
			$this->db->ncquery("TRUNCATE amzn_product_queue");		
		}
	}

	public function clearOffersForASIN($asin){
		$this->db->query("DELETE FROM product_amzn_offers WHERE asin LIKE '" . $this->db->escape($asin) . "'");
		return $this;
	}

	public function setProductNoOffers($asin){
		$this->db->query("UPDATE product SET amzn_no_offers = 1, quantity = 0 WHERE asin LIKE '" . $this->db->escape($asin) . "'");

		$this->clearOffersForASIN($asin);
		$this->PriceLogic->setProductNoOffers($asin);

		return $this;		
	}

	public function setProductOffers($asin){
		$this->db->query("UPDATE product SET amzn_no_offers = 0, quantity = 9999 WHERE asin LIKE '" . $this->db->escape($asin) . "'");	

		$this->PriceLogic->setProductOffers($asin);

		return $this;
	}

	public function setLastOffersDate($asin){
		$this->db->query("UPDATE product SET amzn_last_offers = NOW() WHERE asin LIKE '" . $this->db->escape($asin) . "'");		

		return $this;
	}

	//Обработка не обязательно новых, которые рнф видит как новые
	public static function checkIfOfferReallyIsNew($rfOffer){

		if (stripos($rfOffer->getConditionTitle(), 'wie neu') !== false){
			return false;
		}

		return true;
	}

	public function reparseOffersToSkip($rfOffers){
		$rfOffersTMP = [];

		foreach ($rfOffers as $key => $rfOffer){		
			$addThisOffer = true;

			$this->Suppliers->addSupplier($rfOffer->getSellerName());

			if ($supplier = $this->Suppliers->getSupplier($rfOffer->getSellerName())){
				//Не обрабатывать офферы от поставщиков, если ручной рейтинг ниже 500
				if (!empty($supplier['amzn_coefficient']) && (int)$supplier['amzn_coefficient'] < $this->Suppliers->supplierMinInnerRatingForUse){
					$addThisOffer = false;
				}
			}

			if ($rfOffer->getSellerRating50()>0 && $rfOffer->getSellerRating50() < $this->Suppliers->supplierMinRatingForUse){
				$addThisOffer = false;
			}

			if ($addThisOffer){
				$rfOffersTMP[] = $rfOffer;
			}
		}

		return $rfOffersTMP;
	}

	public function addOffersForASIN($asin, $rfOffers){
		$this->clearOffersForASIN($asin)->setLastOffersDate($asin);

		$rfOffers = $this->reparseOffersToSkip($rfOffers);

		$minKey  	= $this->getMinPriceOffer($rfOffers);
		$ratingKeys = $this->calculateOffersRatings($rfOffers); 		


		if (!$rfOffers){
			$this->setProductNoOffers($asin);
		} else {
			$this->setProductOffers($asin);
		}

		foreach ($rfOffers as $key => $rfOffer){			

			//Best Amazon Price
			if ($key == $ratingKeys['maxRatingKey']){
				$amazonBestPrice = (float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount();
				$this->db->query("UPDATE product SET amazon_best_price = '" . (float)$amazonBestPrice . "' WHERE asin = '" . $this->db->escape($asin) . "' AND is_markdown = 0");

				$this->PriceLogic->updateProductPrices($asin, $amazonBestPrice);

			}

			//Lowest Amazon Price
			if ($key == $minKey){
				$amazonLowestPrice = (float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount();
				$this->db->query("UPDATE product SET amazon_lowest_price = '" . (float)$amazonLowestPrice . "' WHERE asin = '" . $this->db->escape($asin) . "'  AND is_markdown = 0");
			}

			$conditionIsNew = $rfOffer->getConditionIsNew() && self::checkIfOfferReallyIsNew($rfOffer);			
			

			$this->db->query("INSERT INTO product_amzn_offers SET
				asin 							= '" . $this->db->escape($asin) . "', 			
				priceCurrency 					= '" . $this->db->escape($rfOffer->getPriceCurrency()) . "',
				priceAmount 					= '" . (float)$rfOffer->getPriceAmount() . "',
				importFeeCurrency 				= '" . $this->db->escape($rfOffer->getImportFeeCurrency()) . "',
				importFeeAmount 				= '" . (float)$rfOffer->getImportFeeAmount() . "',
				deliveryCurrency 				= '" . $this->db->escape($rfOffer->getDeliveryCurrency()) . "',
				deliveryAmount 					= '" . (float)$rfOffer->getDeliveryAmount() . "',
				deliveryIsFree 					= '" . (int)$rfOffer->getDeliveryIsFree() . "',
				deliveryIsFba 					= '" . (int)$rfOffer->getDeliveryIsFba() . "',
				deliveryIsShippedCrossBorder 	= '" . (int)$rfOffer->getDeliveryIsShippedCrossBorder() . "',
				deliveryComments 				= '" . $this->db->escape($rfOffer->getDeliveryComments()) . "',
				conditionIsNew 					= '" . (int)$conditionIsNew . "',
				conditionTitle 					= '" . $this->db->escape($rfOffer->getConditionTitle()) . "',
				conditionComments 				= '" . $this->db->escape($rfOffer->getConditionComments()) . "',
				sellerName 						= '" . $this->db->escape($rfOffer->getSellerName()) . "',
				sellerLink 						= '" . $this->db->escape($rfOffer->getSellerLink()) . "',
				sellerRating50 					= '" . (int)$rfOffer->getSellerRating50() . "',
				sellerRatingsTotal 				= '" . (int)$rfOffer->getSellerRatingsTotal() . "',
				sellerPositiveRatings100 		= '" . (int)$rfOffer->getSellerPositiveRatings100() . "',
				is_min_price					= '" . (int)($key == $minKey) . "',
				isBestOffer						= '" . (int)($key == $ratingKeys['maxRatingKey']) . "',
				offerRating						= '" . (float)$ratingKeys['ratingKeys'][$key] . "',
				isPrime							= '" . (int)$rfOffer->getIsPrime() . "',
				date_added						= NOW()");
		}
	}

	public function calculateOffersRatings($rfOfferList){
		$minPriceKey = $this->getMinPriceOffer($rfOfferList);
		$maxPrice    = $this->getMaxPriceOffer($rfOfferList);

		$ratingKeys 	= [];	
		$maxRating		= 0;
		$maxRatingKey	= 0;

		foreach ($rfOfferList as $key => $rfOffer){
			$rfOfferWeight = 0;

			if (!$rfOffer->getConditionIsNew() || !self::checkIfOfferReallyIsNew($rfOffer)){
				$rfOfferWeight -= 80;
			}

			if ($rfOffer->getSellerName() == 'Amazon'){
				$rfOfferWeight += 30;
			}

			if ($supplier = $this->Suppliers->getSupplier($rfOffer->getSellerName())){

				if (!empty($supplier['amzn_good']) && $supplier['amzn_good']){
					$rfOfferWeight += 10;
				}

				if (!empty($supplier['amzn_bad']) && $supplier['amzn_bad']){
					$rfOfferWeight -= 20;
				}

				if (isset($supplier['amzn_coefficient'])){
					$rfOfferWeight += (int)($supplier['amzn_coefficient']);
				}

			}

			if ($rfOffer->getIsPrime()){
				$rfOfferWeight += 10;
			}

			if ($rfOffer->getDeliveryIsFba()){
				$rfOfferWeight += 10;
			}

			if ($key == $minPriceKey){
				$rfOfferWeight += 5;
			}

			if ($rfOffer->getSellerRating50() == 5){
				$rfOfferWeight += 10;
			}

			$rfOfferWeight += (($maxPrice - ((float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount())));			

			if ($rfOffer->getSellerRating50()){				
				$rfOfferWeight += ($rfOffer->getSellerRating50()/10);
				$rfOfferWeight += ($rfOffer->getSellerRatingsTotal()/100000);
			}

			$ratingKeys[$key] = $rfOfferWeight;

			if ($rfOfferWeight > $maxRating){
				$maxRating 		= $rfOfferWeight;
				$maxRatingKey	= $key;
			}
		}		

		return ['ratingKeys' => $ratingKeys, 'maxRatingKey' => $maxRatingKey];
	}

	
	public function getMinPriceOffer($rfOfferList, $excludeKey = -1){
		
		$minPrice = PHP_INT_MAX;
		$minKey = false;

		foreach ($rfOfferList as $key => $rfOffer){
			if ($rfOffer->getConditionIsNew() && self::checkIfOfferReallyIsNew($rfOffer) && ((float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount()) < $minPrice){
				$minKey = $key;
				$minPrice = ((float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount());
			}
		}
		
		return $minKey;
	}

	public function getMaxPriceOffer($rfOfferList, $excludeKey = -1){
		
		$maxPrice = 0;

		foreach ($rfOfferList as $key => $rfOffer){
			if ($rfOffer->getConditionIsNew() && self::checkIfOfferReallyIsNew($rfOffer) && ((float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount()) > $maxPrice){
				$maxPrice = ((float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount());
			}
		}
		
		return $maxPrice;
	}
}
<?

namespace hobotix\Amazon;

class OffersParser
{
	
	const CLASS_NAME = 'hobotix\\Amazon\\OffersParser';
	
	private $db;	
	private $config;
	private $log;

	//private $testAsin = 'B07V5FWPT1';
	private $testAsin = false;

	private $no_offers_logic = false;

	public $Suppliers;
	public $PriceLogic;	
	
	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->log 		= $registry->get('log');

		require_once(dirname(__FILE__) . '/Suppliers.php');
		$this->Suppliers = new Suppliers($registry);

		require_once(dirname(__FILE__) . '/PriceLogic.php');
		$this->PriceLogic = new PriceLogic($registry);

		//models
		require_once(dirname(__FILE__) . '/models/hoboModel.php');	
		require_once(dirname(__FILE__) . '/models/productModelEdit.php');
		require_once(dirname(__FILE__) . '/models/productModelGet.php');

		$this->model_product_edit 		= new productModelEdit($registry);		
		$this->model_product_get 		= new productModelGet($registry);	
	}

	public function setNoOffersLogic($no_offers_logic){
		$this->no_offers_logic = $no_offers_logic;

		return $this;
	}

	public function getTotalProductsToGetOffers(){
		$result = [];

		$sql = " FROM product p
		WHERE p.status = 1 
		AND p.amzn_ignore = 0		
		AND p.is_virtual = 0
		AND p.is_markdown = 0 ";		

		if (!$this->config->get('config_rainforest_enable_offers_for_stock')){
			$sql .= " AND (" . $this->PriceLogic->buildStockQueryField() . " = 0)";
		}

		if ($this->config->get('config_rainforest_pass_offers_for_ordered')){
			$sql .= " AND ( p.actual_cost_date = '0000-00-00' ";

			if ($this->config->get('config_rainforest_pass_offers_for_ordered_days')){
				$sql .= " OR DATE_ADD(p.actual_cost_date, INTERVAL " . (int)$this->config->get('config_rainforest_pass_offers_for_ordered_days') . " DAY) < DATE(NOW())";
			}

			$sql .= ")";
		}

		if ($this->config->get('config_rainforest_disable_offers_use_field_ignore_parse')){
			$sql .= " AND NOT (p.ignore_parse = 1 AND (p.ignore_parse_date_to = '0000-00-00' OR DATE(p.ignore_parse_date_to) > DATE(NOW())))";
		}

		if ($this->config->get('config_rainforest_disable_offers_if_has_special')){
			$sql .= " AND p.product_id NOT IN (SELECT product_id FROM product_special ps WHERE ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())))";
		}

		$sql .= "
		AND (NOT ISNULL(p.asin))
		AND p.asin <> ''
		AND p.asin <> 'INVALID'";

		if ($this->config->get('config_rainforest_enable_offers_only_for_filled')){
			$sql .= "AND p.filled_from_amazon = 1";
		}

		$sql .= " AND (p.asin NOT IN (SELECT asin FROM amzn_product_queue)) ";

		$sql .= " AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1)) ";

		if ($this->no_offers_logic){
			$sql .= " AND p.amzn_no_offers = 1";
		} else {
			$sql .= " AND (p.amzn_last_offers = '0000-00-00 00:00:00' OR DATE(p.amzn_last_offers) <= DATE(DATE_ADD(NOW(), INTERVAL -" . $this->config->get('config_rainforest_update_period') . " DAY)))";
		}

		$query = $this->db->ncquery("SELECT COUNT(DISTINCT(asin)) as total " . $sql . "");

		return $query->row['total'];
	}		
	
	public function getProductsToGetOffers(){
		$result = [];		

		$sql = " FROM product p
		WHERE p.status = 1 
		AND p.amzn_ignore = 0		
		AND p.is_virtual = 0
		AND p.is_markdown = 0 ";

		if (!$this->config->get('config_rainforest_enable_offers_for_stock')){
			$sql .= " AND (" . $this->PriceLogic->buildStockQueryField() . " = 0)";
		}

		if ($this->config->get('config_rainforest_pass_offers_for_ordered')){
			$sql .= " AND ( p.actual_cost_date = '0000-00-00' ";

			if ($this->config->get('config_rainforest_pass_offers_for_ordered_days')){
				$sql .= " OR DATE_ADD(p.actual_cost_date, INTERVAL " . (int)$this->config->get('config_rainforest_pass_offers_for_ordered_days') . " DAY) < DATE(NOW())";
			}

			$sql .= ")";
		}

		if ($this->config->get('config_rainforest_disable_offers_use_field_ignore_parse')){
			$sql .= " AND NOT (p.ignore_parse = 1 AND (p.ignore_parse_date_to = '0000-00-00' OR DATE(p.ignore_parse_date_to) > DATE(NOW())))";
		}

		if ($this->config->get('config_rainforest_disable_offers_if_has_special')){
			$sql .= " AND p.product_id NOT IN (SELECT product_id FROM product_special ps WHERE ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())))";
		}

		$sql .= "
		AND (NOT ISNULL(p.asin))
		AND p.asin <> ''
		AND p.asin <> 'INVALID'";

		if ($this->config->get('config_rainforest_enable_offers_only_for_filled')){
			$sql .= "AND p.filled_from_amazon = 1";
		}

		$sql .= " AND (p.asin NOT IN (SELECT asin FROM amzn_product_queue)) ";

		$sql .= " AND p.product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1))";

		if ($this->no_offers_logic){
			$sql .= " AND p.amzn_no_offers = 1";
		} else {
			$sql .= " AND (p.amzn_last_offers = '0000-00-00 00:00:00' OR DATE(p.amzn_last_offers) <= DATE(DATE_ADD(NOW(), INTERVAL -" . $this->config->get('config_rainforest_update_period') . " DAY)))";
		}		


		$sql = "SELECT DISTINCT(asin), product_id " . $sql . " ORDER BY amzn_last_offers ASC LIMIT " . (int)\hobotix\RainforestAmazon::offerParserLimit;		

		$query = $this->db->ncquery($sql);

		foreach ($query->rows as $row){
			if (trim($row['asin'])){
				$result[] = $row['asin'];
			}
		}

		if ($this->testAsin){
			return [$this->testAsin];
		}

		return $result;
	}		

	public function addAsinToProductsAmazonOffersQueue($asin){
		if ($asin){
			$this->db->query("INSERT IGNORE INTO amzn_product_queue SET asin = '" . $this->db->escape($asin) . "', date_added = NOW()");	
		}	
	}

	public function getProductsAmazonOffersQueue(){
		$result = [];
		$query = $this->db->ncquery("SELECT DISTINCT(asin) FROM amzn_product_queue ORDER BY date_added ASC LIMIT " . (int)\hobotix\RainforestAmazon::offerRequestLimits . "");

		foreach ($query->rows as $row){
			if (trim($row['asin'])){
				$result[] = $row['asin'];
			}
		}

		return $result;
	}

	public function clearProductsAmazonQueueStep(){
		$this->db->query("DELETE FROM amzn_product_queue ORDER BY date_added ASC LIMIT " . (int)\hobotix\RainforestAmazon::offerRequestLimits . "");
	}

	public function getAllProductsAmazonOffersQueue(){
		$result = [];
		$query = $this->db->ncquery("SELECT DISTINCT(asin) FROM amzn_product_queue");

		foreach ($query->rows as $row){
			if (trim($row['asin'])){
				$result[] = $row['asin'];
			}
		}

		return $result;
	}

	public function getTotalProductsAmazonOffersInQueue(){
		return $this->db->query("SELECT COUNT(*) AS total FROM amzn_product_queue")->row['total'];
	}

	public function clearProductsAmazonQueue($asin = false){
		if ($asin){
			$this->db->query("DELETE FROM amzn_product_queue WHERE asin = '" . $this->db->escape($asin) . "'");
		} else {
			$this->db->ncquery("TRUNCATE amzn_product_queue");		
		}
	}

	public function getTotalAmazonOffers(){
		return $this->db->query("SELECT COUNT(*) AS total FROM product_amzn_offers")->row['total'];
	}

	public function getAmazonOffers($start){
		$query = $this->db->query("SELECT * FROM product_amzn_offers WHERE deliveryComments <> '' LIMIT " . (int)$start . ", " . (int)\hobotix\RainforestAmazon::generalDBQueryLimit);

		return $query->rows;
	}

	public function setAmazonOfferDates($amazon_offer_id, $data){
		$this->db->query("UPDATE product_amzn_offers SET 
			minDays 		= '" . (int)$data['minDays'] . "', 
			deliveryFrom 	= '" . $this->db->escape(date('Y-m-d', strtotime($data['deliveryFrom']))) . "', 
			deliveryTo 		= '" . $this->db->escape(date('Y-m-d', strtotime($data['deliveryTo']))) . "'
			WHERE amazon_offer_id = '" . (int)$amazon_offer_id . "'");
	}

	public function clearOffersForASIN($asin){
		$this->db->query("DELETE FROM product_amzn_offers WHERE asin LIKE '" . $this->db->escape($asin) . "'");
		return $this;
	}

	public function setProductNoOffers($asin){
		$sql = "UPDATE product SET amzn_no_offers = 1, amzn_offers_count  = 0, amzn_no_offers_counter = (amzn_no_offers_counter + 1) WHERE asin LIKE '" . $this->db->escape($asin) . "'";
		$this->db->query($sql);

		if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_quantity')){
			$sql = "UPDATE product SET quantity = 0 WHERE asin LIKE '" . $this->db->escape($asin) . "' AND added_from_amazon = 1";
			$this->db->query($sql);
		}

		if ($this->config->get('config_rainforest_nooffers_action_for_manual') && $this->config->get('config_rainforest_nooffers_quantity_for_manual')){
			$sql = "UPDATE product SET quantity = 0 WHERE asin LIKE '" . $this->db->escape($asin) . "' AND added_from_amazon = 0";
			$this->db->query($sql);
		}		

		$this->clearOffersForASIN($asin);
		$this->PriceLogic->setProductNoOffers($asin);

		return $this;		
	}

	public function setProductOffers($asin){
		$sql = "UPDATE product SET amzn_no_offers = '0', amzn_no_offers_counter = '0' WHERE asin LIKE '" . $this->db->escape($asin) . "'";
		$this->db->query($sql);
		
		if ($this->config->get('config_rainforest_nooffers_action') && $this->config->get('config_rainforest_nooffers_quantity')){
			$sql = "UPDATE product SET quantity = 9999 WHERE asin LIKE '" . $this->db->escape($asin) . "' AND added_from_amazon = 1";
			$this->db->query($sql);
		}

		if ($this->config->get('config_rainforest_nooffers_action_for_manual') && $this->config->get('config_rainforest_nooffers_quantity_for_manual')){
			$sql = "UPDATE product SET quantity = 9999 WHERE asin LIKE '" . $this->db->escape($asin) . "' AND added_from_amazon = 0";
			$this->db->query($sql);
		}			

		$this->PriceLogic->setProductOffers($asin);

		return $this;
	}	

	public function setProductOffersCount($asin, $count){
		if ($count == 0){		
			$this->db->query("UPDATE product SET amzn_no_offers = '1', amzn_offers_count = '" . (int)$count . "' WHERE asin LIKE '" . $this->db->escape($asin) . "'");
		} else {
			$this->db->query("UPDATE product SET amzn_no_offers = '0', amzn_no_offers_counter = '0', amzn_offers_count = '" . (int)$count . "' WHERE asin LIKE '" . $this->db->escape($asin) . "'");
		}

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

	//Пропуск офферов Amazon, которые не являются байбокс офферами и не имеют информации о доставке
	public static function checkIfAmazonOfferIsReallyInStock($rfOffer){
		$amazonSupplierNames = ['Amazon Media EU S.à r.l.', 'Amazon'];

		//это оффер Амазона
		if (in_array($rfOffer->getSellerName(), $amazonSupplierNames) && $rfOffer->getDeliveryIsFba()){

			//Оффер Амазона не является байбокс-виннером и у него нет информации о доставке
			if (empty($rfOffer->getOriginalDataArray()['buybox_winner']) && !$rfOffer->getDeliveryComments()){
				return false;
			}
		}

		return true;
	}

	public function parseAmazonDeliveryComment($deliveryComment){
		$deliveryComment = parseAmazonDeliveryDateToEnglish($deliveryComment);

		//1. October
		if (preg_match('/^[0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $matches) == 1){
			preg_match('/[0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $date);						
			$date = (date_parse_from_format('j. F', $date[0]));
			$date['year'] = guessYear($date);		

			$dateFrom = $dateTo = $date;	
		}

		//Sa., 1. October
		if (preg_match('/^[a-zA-Z]{2,10}\., [0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $matches) == 1){
			preg_match('/[0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $date);						
			$date = (date_parse_from_format('j. F', $date[0]));
			$date['year'] = guessYear($date);

			$dateFrom = $dateTo = $date;
		}

		//Montag, 9. Januar
		if (preg_match('/^[a-zA-Z]{2,10}, [0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $matches) == 1){
			preg_match('/[0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $date);			
			$date = (date_parse_from_format('j. F', $date[0]));
			$date['year'] = guessYear($date);

			$dateFrom = $dateTo = $date;
		}

		//23. - 25. Januar
		if (preg_match('/^[0-9]{1,2}\. - [0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $matches) == 1){
			preg_match_all('/[0-9]{1,2}\./', $deliveryComment, $days);	
			preg_match_all('/[äa-zA-Z]{2,9}$/', $deliveryComment, $month);	

			$dateFrom 	= (date_parse_from_format('j. F', $days[0][0] . ' ' . $month[0][0]));
			$dateTo 	= (date_parse_from_format('j. F', $days[0][1] . ' ' . $month[0][0]));

			$dateFrom['year'] 	= guessYear($dateFrom);
			$dateTo['year'] 	= guessYear($dateTo);			
		}

		//21. - 22. Februar, 3021
		if (preg_match('/^[0-9]{1,2}\. - [0-9]{1,2}\. [äa-zA-Z]{2,9}, [0-9]{1,4}$/', $deliveryComment, $matches) == 1){
			preg_match_all('/[0-9]{1,2}\./', $deliveryComment, $days);	
			preg_match_all('/[äa-zA-Z]{2,9}/', $deliveryComment, $month);	
			
			$dateFrom 	= (date_parse_from_format('j. F', $days[0][0] . ' ' . $month[0][0]));
			$dateTo 	= (date_parse_from_format('j. F', $days[0][1] . ' ' . $month[0][0]));

			$dateFrom['year'] 	= guessYear($dateFrom);
			$dateTo['year'] 	= guessYear($dateTo);			
		}


		//2. Dezember - 4. Januar
		if (preg_match('/^[0-9]{1,2}\. [äa-zA-Z]{2,9} - [0-9]{1,2}\. [äa-zA-Z]{2,9}$/', $deliveryComment, $matches) == 1){
			preg_match_all('/[0-9]{1,2}\./', $deliveryComment, $days);	
			preg_match_all('/[äa-zA-Z]{2,9}/', $deliveryComment, $months);	
			
			$dateFrom 	= (date_parse_from_format('j. F', $days[0][0] . ' ' . $months[0][0]));
			$dateTo 	= (date_parse_from_format('j. F', $days[0][1] . ' ' . $months[0][1]));

			$dateFrom['year'] 	= guessYear($dateFrom);
			$dateTo['year'] 	= guessYear($dateTo);			
		}

		//frühestens Do., 22. September, 13:00 - 18:00 -> 22. September, 13:00 - 18:00
		if (preg_match('/^^[0-9]{1,2}\. [äa-zA-Z]{2,9}, [0-9]{1,2}:[0-9]{1,2} - [0-9]{1,2}:[0-9]{1,2}$/', $deliveryComment, $matches) == 1){
			preg_match('/[0-9]{1,2}\. [äa-zA-Z]{2,9}/', $deliveryComment, $date);			
			$date = (date_parse_from_format('j. F', $date[0]));
			$date['year'] = guessYear($date);

			$dateFrom = $dateTo = $date;		
		}

		if (empty($dateFrom) || !$dateFrom || !$dateFrom['month'] || ($dateFrom['year'] + $dateFrom['month'] + $dateFrom['day']) <= 0){
			return false;
		}

		return [
			'deliveryFrom' 	=> reformatDate($dateFrom),
			'deliveryTo' 	=> reformatDate($dateTo),
			'minDays' 		=> days_diff(reformatDate($dateFrom)),
		];
	}

	public function reparseOffersToSkip($rfOffers){
		$rfOffersTMP = [];

		foreach ($rfOffers as $key => $rfOffer){		
			$addThisOffer = true;

			$this->Suppliers->addSupplier($rfOffer->getSellerName());

			if ($supplier = $this->Suppliers->getSupplier($rfOffer->getSellerName())){
				if (!empty($supplier['amzn_coefficient']) && (int)$supplier['amzn_coefficient'] < $this->Suppliers->supplierMinInnerRatingForUse){
					$addThisOffer = false;
				}
			}

			if ((float)$this->config->get('config_rainforest_max_delivery_price') && (float)$rfOffer->getDeliveryAmount() > 0){
				if ((float)$rfOffer->getDeliveryAmount() > $this->config->get('config_rainforest_max_delivery_price')){
					$addThisOffer = false;
				}
			}

			//Bad delivery price
			if ((float)$this->config->get('config_rainforest_max_delivery_price_multiplier') && (float)$rfOffer->getDeliveryAmount() > 0){
				if ((float)$rfOffer->getDeliveryAmount() > (float)$rfOffer->getPriceAmount() * (float)$this->config->get('config_rainforest_max_delivery_price_multiplier')){
					$addThisOffer = false;
				}
			}

			if ($rfOffer->getDeliveryComments() && $offerDates = $this->parseAmazonDeliveryComment($rfOffer->getDeliveryComments())){
				if ($this->config->get('config_rainforest_max_delivery_days_for_offer') > 0){
					if (!empty($offerDates['minDays']) && (int)$offerDates['minDays'] > (int)$this->config->get('config_rainforest_max_delivery_days_for_offer')){
						$addThisOffer = false;
					}
				}
			}

			if ($rfOffer->getSellerRating50()>0 && $rfOffer->getSellerRating50() < $this->Suppliers->supplierMinRatingForUse){
				$addThisOffer = false;
			}

			if (!$rfOffer->getConditionIsNew() || !self::checkIfOfferReallyIsNew($rfOffer) || !self::checkIfAmazonOfferIsReallyInStock($rfOffer)){
				$addThisOffer = false;
			}

			if ($addThisOffer){
				$rfOffersTMP[] = $rfOffer;
			}
		}

		return $rfOffersTMP;
	}

	public function validateProductLowPrice($asin, $amazonBestPrice){

		//There's no setting for skipping products, it's a minimal price
		if (!$this->config->get('config_rainforest_skip_low_price_products') || is_null($this->config->get('config_rainforest_skip_low_price_products')) || (int)$this->config->get('config_rainforest_skip_low_price_products') <= 0){
			return false;
		}

		//Setting for deleting or disabling is not set or is off
		if (!$this->config->get('config_rainforest_drop_low_price_products')){
			return false;
		}

		if (!$amazonBestPrice){
			return false;
		}

		if (!$asin){
			return false;
		}

		if (!$products = $this->model_product_get->getProductsByAsin($asin)){
			return false;
		}

		if ((int)$this->config->get('config_rainforest_skip_low_price_products') > 0){
			if ((int)$amazonBestPrice > 0 && (int)$amazonBestPrice < (int)$this->config->get('config_rainforest_skip_low_price_products')){


				foreach ($products as $product_id){
					$is_added_from_amazon = $this->model_product_get->getProductsByAsin($product_id);

					if ($is_added_from_amazon && $this->config->get('config_rainforest_drop_low_price_products')){
						if ($this->PriceLogic->checkIfProductIsInOrders($product_id)){
							$this->model_product_edit->disableProduct($product_id)->addAsinToIgnored($asin, $this->model_product_get->getProductName($product_id));
							return 'disabled';
						} else {
							$this->model_product_edit->deleteProductSimple($product_id)->addAsinToIgnored($asin, $this->model_product_get->getProductName($product_id));
							return 'deleted';
						}
					}

					if (!$is_added_from_amazon && $this->config->get('config_rainforest_drop_low_price_products_for_manual')){
						if ($this->PriceLogic->checkIfProductIsInOrders($product_id)){
							$this->model_product_edit->disableProduct($product_id)->addAsinToIgnored($asin, $this->model_product_get->getProductName($product_id));
							return 'disabled';
						} else {
							$this->model_product_edit->deleteProductSimple($product_id)->addAsinToIgnored($asin, $this->model_product_get->getProductName($product_id));
							return 'deleted';
						}
					}

				}

			}
		}

		return false;
	}

	public function addOffersForASIN($asin, $rfOffers){
		$this->clearOffersForASIN($asin)->setLastOffersDate($asin);

		$rfOffers 	= $this->reparseOffersToSkip($rfOffers);

		$minKey  	= $this->getMinPriceOffer($rfOffers);
		$ratingKeys = $this->calculateOffersRatings($rfOffers);

		if (!$rfOffers){
			$this->setProductNoOffers($asin);
		} else {
			$this->setProductOffers($asin)->setProductOffersCount($asin, count($rfOffers));
		}

		foreach ($rfOffers as $key => $rfOffer){			

			//Best Amazon Price
			if ($key == $ratingKeys['maxRatingKey']){
				$amazonBestPrice = (float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount();
				$this->db->query("UPDATE product SET amazon_best_price = '" . (float)$amazonBestPrice . "' WHERE asin = '" . $this->db->escape($asin) . "' AND is_markdown = 0");

				//New function, used for checking if best price is below range
				if ($this->validateProductLowPrice($asin, $amazonBestPrice)){
					echoLine('[OffersParser] Disabling logic worked, product is disabled or deleted');
					break;
				}

				$this->PriceLogic->updateProductPrices($asin, $amazonBestPrice);
			}

			//Lowest Amazon Price
			if ($key == $minKey){
				$amazonLowestPrice = (float)$rfOffer->getPriceAmount() + (float)$rfOffer->getDeliveryAmount();
				$this->db->query("UPDATE product SET amazon_lowest_price = '" . (float)$amazonLowestPrice . "' WHERE asin = '" . $this->db->escape($asin) . "'  AND is_markdown = 0");
			}

			//Мы пропускаем офферы, с не новым товаром теперь всегда
			$conditionIsNew = true;

			if (!empty($rfOffer->getOriginalDataArray()['offer_id'])){
				$offer_id = $rfOffer->getOriginalDataArray()['offer_id'];
			} else {
				$offer_id = md5(serialize($rfOffer->getOriginalDataArray()));
			}

			//BuyBoxWinner
			$buyBoxWinner = false;
			if (!empty($rfOffer->getOriginalDataArray()['buybox_winner'])){
				$buyBoxWinner = true;
			}

			$offerDates = false;
			if ($rfOffer->getDeliveryComments()){
				$offerDates = $this->parseAmazonDeliveryComment($rfOffer->getDeliveryComments());
			}

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
				minDays 						= '" . ($offerDates?(int)$offerDates['minDays']:'0') . "',
				deliveryFrom 					= '" . ($offerDates?$this->db->escape($offerDates['deliveryFrom']):'0') . "',
				deliveryTo  					= '" . ($offerDates?$this->db->escape($offerDates['deliveryTo']):'0') . "',
				conditionIsNew 					= '" . (int)$conditionIsNew . "',
				conditionTitle 					= '" . $this->db->escape($rfOffer->getConditionTitle()) . "',
				conditionComments 				= '" . $this->db->escape($rfOffer->getConditionComments()) . "',
				sellerName 						= '" . $this->db->escape($rfOffer->getSellerName()) . "',
				sellerLink 						= '" . $this->db->escape($rfOffer->getSellerLink()) . "',
				sellerRating50 					= '" . (int)$rfOffer->getSellerRating50() . "',
				sellerRatingsTotal 				= '" . (int)$rfOffer->getSellerRatingsTotal() . "',
				sellerPositiveRatings100 		= '" . (int)$rfOffer->getSellerPositiveRatings100() . "',
				is_min_price					= '" . (int)($key == $minKey) . "',
				isBuyBoxWinner					= '" . (int)($buyBoxWinner) . "',
				isBestOffer						= '" . (int)($key == $ratingKeys['maxRatingKey']) . "',
				offerRating						= '" . (float)$ratingKeys['ratingKeys'][$key] . "',
				isPrime							= '" . (int)$rfOffer->getIsPrime() . "',
				offer_id 						= '" . $this->db->escape($offer_id) . "',
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

			if (!$rfOffer->getConditionIsNew() || !self::checkIfOfferReallyIsNew($rfOffer) || !self::checkIfAmazonOfferIsReallyInStock($rfOffer)){
				$rfOfferWeight -= 80;
			}

			if ($rfOffer->getSellerName() == 'Amazon' && self::checkIfAmazonOfferIsReallyInStock($rfOffer)){
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

			if (!empty($rfOffer->getOriginalDataArray()['buybox_winner'])){
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
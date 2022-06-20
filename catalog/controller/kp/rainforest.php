<?

class ControllerKPRainForest extends Controller {	
	private $maxSteps = 10;

	private $productRequestLimits = 30;
	private $offerRequestLimits = 30;
	private $rainforestAmazon;


	public function __construct($registry){
		ini_set('memory_limit', '2G');

		parent::__construct($registry);

		if (php_sapi_name() != 'cli'){
			die();
		}

		if (!$this->config->get('config_rainforest_enable_api')){
			die('RNF API NOT ENABLED');
		}

	}
		
	public function updateproductdimensions(){


		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');

		$query = $this->db->query("SELECT * FROM product_amzn_data");

		foreach ($query->rows as $row){
			if ($this->rainforestAmazon->infoUpdater->parseAndUpdateProductDimensions($row['json'])){
				echoLine('ОК: Товар ' . $row['product_id'] . ', ASIN: ' );
			} else {
				echoLine('FAIL: Товар ' . $row['product_id'] . ', ASIN: ' );
			}

		}
	}

	public function parseeanscron(){
		
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');

			//Второй шаг, мы проверим товары, у которых нету ASIN, но есть EAN, попробуем получить их с амазона, запишем асин и собственно товар, если не найдем,
			//то ставим метку "товар не найден на амазон"
		$query = $this->db->ncquery("SELECT product_id, ean as gtin FROM product WHERE status = 1 AND asin = '' AND ean <> '' AND amzn_not_found = 0");

		$products = [];
		foreach ($query->rows as $row){
			$products[$row['product_id']] = $row;
		}

		$total = count($products);
		$iterations = ceil($total/$this->productRequestLimits);
		echoLine('[PARSEASINSCRON] Всего ' . $total . ' товаров!');

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			$jsonArray = [];

			echoLine('[PARSEEANSCRON] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ($this->productRequestLimits * ($i-1)) . ' по ' . $this->productRequestLimits * $i);

			$slice = array_slice($products, $this->productRequestLimits * ($i-1), $this->productRequestLimits);

			$results = $this->rainforestAmazon->simpleProductParser->getProductByGTIN($slice);


			foreach ($results as $product_id => $result){
				$this->rainforestAmazon->infoUpdater->updateProductAmazonLastSearch($product_id);

				if ($result){
					echoLine('[PARSEASINSCRON] Товар ' . $product_id . ', найден, ASIN ' . $result['asin']);

					$this->rainforestAmazon->infoUpdater->updateASINInDatabase(['product_id' => $product_id, 'asin' => $result['asin']]);

					$this->rainforestAmazon->infoUpdater->updateProductAmznData([
						'product_id' => $product_id, 
						'asin' => $result['asin'], 
						'json' => json_encode($result)
					]
				);

				} else {

					echoLine('[PARSEASINSCRON] Товар ' . $product_id . ', не найден, EAN ' . $products[$product_id]['gtin']);
					$this->rainforestAmazon->infoUpdater->updateProductNotFoundOnAmazon($product_id);

				}
			}

			echoLine('[PARSEASINSCRON] Времени на итерацию: ' . $timer->getTime() . ' сек.');
			unset($timer);

		}

	}


	
	public function parseasinscron(){
		
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');


		//Первый шаг, мы проверим все текущие асины и запишем их в базу, плохие асины мы удалим
		$query = $this->db->ncquery("SELECT product_id, asin, ean FROM product WHERE status = 1 AND asin <> '' AND ISNULL(amzn_last_search)");

		$products = [];
		foreach ($query->rows as $row){
			$products[$row['product_id']] = $row;
		}

		$total = count($products);
		$iterations = ceil($total/$this->productRequestLimits);
		echoLine('[PARSEASINSCRON] Всего ' . $total . ' товаров!');

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			$jsonArray = [];

			echoLine('[PARSEASINSCRON] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ($this->productRequestLimits * ($i-1)) . ' по ' . $this->productRequestLimits * $i);

			$slice = array_slice($products, $this->productRequestLimits * ($i-1), $this->productRequestLimits);

			$results = $this->rainforestAmazon->simpleProductParser->getProductByASINS($slice);

			foreach ($results as $product_id => $result){
				$this->rainforestAmazon->infoUpdater->updateProductAmazonLastSearch($product_id);

				if ($result){
					echoLine('[PARSEASINSCRON] Товар ' . $product_id . ', найден, ASIN ' . $products[$product_id]['asin']);

					$this->rainforestAmazon->infoUpdater->updateProductAmznData([
						'product_id' => $product_id, 
						'asin' => $products[$product_id]['asin'], 
						'json' => json_encode($result)
					]
				);

				} else {
					echoLine('[PARSEASINSCRON] Товар ' . $product_id . ', не найден, ASIN ' . $products[$product_id]['asin']);
					$this->rainforestAmazon->infoUpdater->updateASINInDatabase(['product_id' => $product_id, 'asin' => '']);

				}
			}

			echoLine('[PARSEASINSCRON] Времени на итерацию: ' . $timer->getTime() . ' сек.');
			unset($timer);
		}


	}

	public function parseoffersorderscron(){
	}



	public function parseofferscron($immediately = false){

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
	
		if ($immediately){

			echoLine('Работаем с очередью');
			$query = $this->db->ncquery("SELECT DISTINCT(asin) FROM amzn_product_queue");
			$this->db->ncquery("TRUNCATE amzn_product_queue");

		} else {

			$this->db->query("UPDATE product SET asin = TRIM(asin) WHERE 1");

			$sql = " FROM product 
			WHERE status = 1 
			AND amzn_ignore = 0		
			AND is_virtual = 0
			AND stock_status_id <> '" . $this->config->get('config_not_in_stock_status_id') . "'
			AND (" . $this->rainforestAmazon->offersParser->PriceLogic->buildStockQueryField() . " = 0)
			AND asin <> ''";
			$sql .= " AND (amzn_last_offers = '0000-00-00 00:00:00' OR DATE(amzn_last_offers) <= DATE(DATE_ADD(NOW(), INTERVAL -'" . $this->config->get('config_rainforest_update_period') . "' DAY)))";

			$total = $this->db->ncquery("SELECT COUNT(DISTINCT(asin)) as total " . $sql . "")->row['total'];
			echoLine('[OFFERS] Всего товаров которые надо обновлять: ' . $total);

			$query = $this->db->ncquery("SELECT DISTINCT(asin) " . $sql . " ORDER BY amzn_last_offers ASC LIMIT 2000");

		}

		$products = [];
		foreach ($query->rows as $row){
			if (trim($row['asin'])){
				$products[] = $row['asin'];
			}
		}

		$total = count($products);
		$iterations = ceil($total/$this->offerRequestLimits);
		echoLine('[OFFERS] Всего ' . $total . ' товаров!');

		$i=1;
		$timer = new FPCTimer();
		

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();

			$slice = array_slice($products, $this->offerRequestLimits * ($i-1), $this->offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){				
					$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);
					$this->db->query("DELETE FROM amzn_product_queue WHERE asin = '" . $this->db->escape($asin) . "'");
				}

			}

			echoLine('[OFFERS] Времени на итерацию: ' . $i . ' из ' . $iterations .': ' . $timer->getTime() . ' сек.');
			unset($timer);

		}


		if ($immediately){
			$query = $this->db->ncquery("TRUNCATE amzn_product_queue");
		}




	}

}

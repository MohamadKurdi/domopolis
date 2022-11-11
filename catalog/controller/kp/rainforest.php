<?

class ControllerKPRainForest extends Controller {	
	private $maxSteps = 10;	
	private $rainforestAmazon;


	public function __construct($registry){
		ini_set('memory_limit', '2G');

		parent::__construct($registry);

		if (php_sapi_name() != 'cli'){
			die();
		}

		if (!$this->config->get('config_rainforest_enable_api')){
			echoLine('[ControllerKPRainForest] RNF API NOT ENABLED');
			return;
		}
	}

	public function updateproductvideos(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');

		$query = $this->db->query("SELECT * FROM product_amzn_data WHERE 1");		

		foreach ($query->rows as $row){
			$this->rainforestAmazon->productsRetriever->parseProductVideos($row['product_id'], json_decode($row['json'],true));
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

		if (!$this->config->get('config_rainforest_enable_eans_parser')){
			echoLine('[ControllerKPRainForest::parseeanscron] CRON IS DISABLED IN ADMIN');
			return;
		}
		
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
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::productRequestLimits);
		echoLine('[PARSEASINSCRON] Всего ' . $total . ' товаров!');

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			$jsonArray = [];

			echoLine('[PARSEEANSCRON] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ((int)\hobotix\RainforestAmazon::productRequestLimits * ($i-1)) . ' по ' . (int)\hobotix\RainforestAmazon::productRequestLimits * $i);

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::productRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::productRequestLimits);

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

		if (!$this->config->get('config_rainforest_enable_asins_parser')){
			echoLine('[ControllerKPRainForest::parseasinscron] CRON IS DISABLED IN ADMIN');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');
		$this->load->model('catalog/product');


		//Первый шаг, мы проверим все текущие асины и запишем их в базу, плохие асины мы удалим
		$query = $this->db->ncquery("SELECT product_id, asin, ean FROM product WHERE status = 1 AND asin <> 'INVALID' AND asin <> '' AND ISNULL(amzn_last_search)");

		$products = [];
		foreach ($query->rows as $row){
			$products[$row['product_id']] = $row;
		}

		$total = count($products);
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::productRequestLimits);
		echoLine('[PARSEASINSCRON] Всего ' . $total . ' товаров!');

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();
			$jsonArray = [];

			echoLine('[PARSEASINSCRON] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ((int)\hobotix\RainforestAmazon::productRequestLimits * ($i-1)) . ' по ' . (int)\hobotix\RainforestAmazon::productRequestLimits * $i);

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::productRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::productRequestLimits);

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


				if (!$this->model_catalog_product->getProductVideos($product_id)){
					$this->rainforestAmazon->productsRetriever->parseProductVideos($product_id, $result);	
				}

				} else {
					echoLine('[PARSEASINSCRON] Товар ' . $product_id . ', не найден, ASIN ' . $products[$product_id]['asin']);
					$this->rainforestAmazon->infoUpdater->updateASINInDatabase(['product_id' => $product_id, 'asin' => 'INVALID']);

				}
			}

			echoLine('[PARSEASINSCRON] Времени на итерацию: ' . $timer->getTime() . ' сек.');
			unset($timer);
		}
	}

	public function parseoffersqueuecron(){

		if (!$this->config->get('config_rainforest_enable_offersqueue_parser')){
			echoLine('[ControllerKPRainForest::parseoffersqueuecron] CRON IS DISABLED IN ADMIN');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');

		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[parseofferscron] RNF AMAZON PRICING NOT ENABLED');
			return;
		}

		echoLine('Работаем с очередью');		
		$products = $this->rainforestAmazon->offersParser->getProductsAmazonQueue();
		$this->rainforestAmazon->offersParser->clearProductsAmazonQueue();

		$total = count($products);
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);
		echoLine('[parseofferscron] Всего ' . $total . ' товаров!');

		$i=1;
		$timer = new FPCTimer();		
		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){				
					$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);					
				}
			}

			echoLine('[parseofferscron] Времени на итерацию: ' . $i . ' из ' . $iterations .': ' . $timer->getTime() . ' сек.');
			unset($timer);
		}

		$this->rainforestAmazon->offersParser->clearProductsAmazonQueue();
	}

	public function parsenoofferscron(){

		if (!$this->config->get('config_rainforest_enable_offers_parser')){
			echoLine('[ControllerKPRainForest::parsenoofferscron] CRON IS DISABLED IN ADMIN');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('Timer');

		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[parseofferscron] RNF AMAZON PRICING NOT ENABLED');
			return;
		}

		$this->rainforestAmazon->checkIfPossibleToMakeRequest();
		
		$this->rainforestAmazon->offersParser->setNoOffersLogic(true);

		$products = $this->rainforestAmazon->offersParser->getProductsToGetOffers();			

		$total = count($products);
		echoLine('[parseofferscron] Всего товаров которые надо обновлять: ' . $total);
		
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);
		echoLine('[parseofferscron] Всего ' . $total . ' товаров!');

		$i=1;
		$timer = new FPCTimer();		

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){				
					$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);
					$this->rainforestAmazon->offersParser->clearProductsAmazonQueue($asin);
				}

			}

			echoLine('[parseofferscron] Времени на итерацию: ' . $i . ' из ' . $iterations .': ' . $timer->getTime() . ' сек.');
			unset($timer);
		}
	}

	public function parseofferscron(){

		if (!$this->config->get('config_rainforest_enable_offers_parser')){
			echoLine('[ControllerKPRainForest::parseofferscron] CRON IS DISABLED IN ADMIN');
			return;
		}

		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[parseofferscron] RNF AMAZON PRICING NOT ENABLED');
			return;
		}

		$this->load->library('Timer');
		if ($this->config->has('config_rainforest_offers_parser_time_start') && $this->config->has('config_rainforest_offers_parser_time_end')){
			$interval = new Interval($this->config->get('config_rainforest_offers_parser_time_start') . '-' . $this->config->get('config_rainforest_offers_parser_time_end'));

			if (!$interval->isNow()){
				echoLine('[ControllerKPRainForest::editfullproductscron] NOT ALLOWED TIME');
				return;
			} else {
				echoLine('[ControllerKPRainForest::editfullproductscron] ALLOWED TIME');				
			}
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->rainforestAmazon->checkIfPossibleToMakeRequest();
							
		$products = $this->rainforestAmazon->offersParser->getProductsToGetOffers();			

		$total = count($products);
		echoLine('[parseofferscron] Всего товаров которые надо обновлять: ' . $total);
		
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);
		echoLine('[parseofferscron] Всего ' . $total . ' товаров!');

		$i=1;
		$timer = new FPCTimer();		

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new FPCTimer();

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){				
					$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);
					$this->rainforestAmazon->offersParser->clearProductsAmazonQueue($asin);
				}

			}

			echoLine('[parseofferscron] Времени на итерацию: ' . $i . ' из ' . $iterations .': ' . $timer->getTime() . ' сек.');
			unset($timer);
		}
	}
}

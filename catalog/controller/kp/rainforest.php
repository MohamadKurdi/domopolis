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

	public function profitability(){
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');

		$query = $this->db->query("SELECT * FROM `order` WHERE order_status_id > 0 ORDER BY date_added DESC");

		foreach ($query->rows as $row){
			$this->rainforestAmazon->offersParser->PriceLogic->countOrderProfitablility($row['order_id']);
		}		
	}

	public function testProductOverPriceRules(){
		$d = $this->registry->get('rainforestAmazon')->offersParser->PriceLogic->updateProductPrices('B0BDQPNMQT', 25.99);

		var_dump($d);
	}

	public function parseeanscron(){

		if (!$this->config->get('config_rainforest_enable_eans_parser')){
			echoLine('[ControllerKPRainForest::parseeanscron] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}
		
		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('hobotix/FPCTimer');

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
			$timer = new \hobotix\FPCTimer();
			$jsonArray = [];

			echoLine('[PARSEEANSCRON] Итерация ' . $i . ' from ' . $iterations . ', товары с ' . ((int)\hobotix\RainforestAmazon::productRequestLimits * ($i-1)) . ' по ' . (int)\hobotix\RainforestAmazon::productRequestLimits * $i);

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

			echoLine('[PARSEASINSCRON] Time for iteration: ' . $timer->getTime() . ' s.', 'i');
			unset($timer);

		}
	}
	
	public function parseasinscron(){		

		if (!$this->config->get('config_rainforest_enable_asins_parser')){
			echoLine('[ControllerKPRainForest::parseasinscron] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('hobotix/FPCTimer');
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
			$timer = new \hobotix\FPCTimer();
			$jsonArray = [];

			echoLine('[PARSEASINSCRON] Итерация ' . $i . ' from ' . $iterations . ', товары с ' . ((int)\hobotix\RainforestAmazon::productRequestLimits * ($i-1)) . ' по ' . (int)\hobotix\RainforestAmazon::productRequestLimits * $i);

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

			echoLine('[PARSEASINSCRON] Time for iteration: ' . $timer->getTime() . ' s.', 'i');
			unset($timer);
		}
	}

	/*
		Parses queue by parts, more convenient when queue is large
	*/
	public function parseoffersqueuecron(){
		if (!$this->config->get('config_rainforest_enable_offersqueue_parser')){
			echoLine('[ControllerKPRainForest::parseoffersqueuecron] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}

		$this->load->library('hobotix/FPCTimer');

		if ($this->config->has('config_rainforest_offersqueue_parser_time_start') && $this->config->has('config_rainforest_offersqueue_parser_time_end')){
			$interval = new \hobotix\Interval($this->config->get('config_rainforest_offersqueue_parser_time_start') . '-' . $this->config->get('config_rainforest_offersqueue_parser_time_end'));

			if (!$interval->isNow()){
				echoLine('[ControllerDPRainForest::parseoffersqueuecron] NOT ALLOWED TIME', 'e');
				return;
			} else {
				echoLine('[ControllerDPRainForest::parseoffersqueuecron] ALLOWED TIME', 's');				
			}
		}


		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[parseoffersqueuecron] RNF AMAZON PRICING NOT ENABLED', 'e');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');

		echoLine('[ControllerDPRainForest::parseoffersqueuecron], Working with queue', 'i');

		$total = $this->rainforestAmazon->offersParser->getTotalProductsAmazonOffersInQueue();
		echoLine('[ControllerDPRainForest::parseoffersqueuecron], Total in queue: ' . $total, 'i');

		if ($total > 0){
			$timer = new \hobotix\FPCTimer();

			$products = $this->rainforestAmazon->offersParser->getProductsAmazonOffersQueue();

			if ($products){
				$results = $this->rainforestAmazon->getProductsOffersASYNC($products);

				if ($results){
					foreach ($results as $asin => $offers){				
						$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);					
					}
				}

				$results = $this->rainforestAmazon->offersParser->clearProductsAmazonQueueStep();
			}

			echoLine('[parseoffersqueuecron] Time for iteration: ' . $timer->getTime() . ' s.', 'i');
			unset($timer);

			if ($this->config->get('config_openai_enable') && $this->config->get('config_rainforest_export_names_with_openai') && $this->config->get('config_openai_enable_export_names')){
				foreach ($products as $asin){
					$names = $this->rainforestAmazon->productsRetriever->model_product_get->getProductShortNamesByAsin($asin);

					foreach ($names as $row){
						if ($row['name'] && !trim($row['short_name_d'])){

							if (mb_strlen($row['name']) < $this->config->get('config_openai_exportnames_length')){
								$export_name = $row['name'];
							} else {
								$export_name = $this->openaiAdaptor->exportName($row['name'], $this->registry->get('languages_all_id_code_mapping')[(int)$row['language_id']]);
							}

							if ($export_name){
								$this->rainforestAmazon->productsRetriever->model_product_edit->updateProductShortName($row['product_id'],
									[
										'language_id' 	=> $row['language_id'],
										'short_name_d' 	=> $export_name
									]
								);
							}
						}
					}
				}
			}
		}
	}

	/*
		Parses the whole queue by pieces
	*/
	public function parseoffersqueuecron_full(){
		if (!$this->config->get('config_rainforest_enable_offersqueue_parser')){
			echoLine('[ControllerKPRainForest::parseoffersqueuecron] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}

		$this->load->library('hobotix/FPCTimer');

		if ($this->config->has('config_rainforest_offersqueue_parser_time_start') && $this->config->has('config_rainforest_offersqueue_parser_time_end')){
			$interval = new \hobotix\Interval($this->config->get('config_rainforest_offersqueue_parser_time_start') . '-' . $this->config->get('config_rainforest_offersqueue_parser_time_end'));

			if (!$interval->isNow()){
				echoLine('[ControllerDPRainForest::parseoffersqueuecron] NOT ALLOWED TIME', 'e');
				return;
			} else {
				echoLine('[ControllerDPRainForest::parseoffersqueuecron] ALLOWED TIME', 's');				
			}
		}


		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[ControllerKPRainForest::parseofferscron] RNF AMAZON PRICING NOT ENABLED', 'e');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');

		echoLine('[ControllerDPRainForest::parseofferscron], Working with queue', 'i');		
		$products = $this->rainforestAmazon->offersParser->getAllProductsAmazonOffersQueue();		
		$this->rainforestAmazon->offersParser->clearProductsAmazonQueue();

		echoLine('[ControllerDPRainForest::parseofferscron], Total products in queue: ' . count($products), 'i');

		$total = count($products);
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);
		echoLine('[ControllerKPRainForest::parseofferscron] Total ' . $total . ' products!', 'i');

		$i=1;
		$timer = new \hobotix\FPCTimer();		
		for ($i = 1; $i <= $iterations; $i++){
			$timer = new \hobotix\FPCTimer();

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){				
					$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);					
				}
			}

			echoLine('[ControllerKPRainForest::parseofferscron] Time for iteration: ' . $i . ' from ' . $iterations .': ' . $timer->getTime() . ' s.', 'i');
			unset($timer);
		}
		
		if ($this->config->get('config_openai_enable') && $this->config->get('config_rainforest_export_names_with_openai') && $this->config->get('config_openai_enable_export_names')){
			foreach ($products as $asin){
				$names = $this->rainforestAmazon->productsRetriever->model_product_get->getProductShortNamesByAsin($asin);

				foreach ($names as $row){
					if ($row['name'] && !trim($row['short_name_d'])){

						if (mb_strlen($row['name']) < $this->config->get('config_openai_exportnames_length')){
							$export_name = $row['name'];
						} else {
							$export_name = $this->openaiAdaptor->exportName($row['name'], $this->registry->get('languages_all_id_code_mapping')[(int)$row['language_id']]);
						}

						if ($export_name){
							$this->rainforestAmazon->productsRetriever->model_product_edit->updateProductShortName($row['product_id'],
								[
									'language_id' 	=> $row['language_id'],
									'short_name_d' 	=> $export_name
								]
							);
						}
					}
				}
			}
		}		
	}

	public function parsenoofferscron(){
		if (!$this->config->get('config_rainforest_enable_nooffers_parser')){
			echoLine('[ControllerKPRainForest::parsenoofferscron] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->load->library('hobotix/FPCTimer');

		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[parsenoofferscron] RNF AMAZON PRICING NOT ENABLED', 'e');
			return;
		}

		$this->rainforestAmazon->checkIfPossibleToMakeRequest();		
		$this->rainforestAmazon->offersParser->setNoOffersLogic(true);
		$products = $this->rainforestAmazon->offersParser->getProductsToGetOffers();			

		$total = count($products);
		echoLine('[parsenoofferscron] Total products to update: ' . $total);
		
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);
		echoLine('[parsenoofferscron] Total ' . $total . ' products!', 'i');

		$i=1;
		$timer = new \hobotix\FPCTimer();		

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new \hobotix\FPCTimer();

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){			
					try {
						$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);
						$this->rainforestAmazon->offersParser->clearProductsAmazonQueue($asin);
					} catch (\Exception $e){
						echoLine('[ControllerKPRainForest::parseofferscron] Caught Exception, exiting.' . $e->getMessage());
						return;
					}
				}
			}

			echoLine('[parsenoofferscron] Time for iteration: ' . $i . ' from ' . $iterations .': ' . $timer->getTime() . ' s.', 'i');
			unset($timer);
		}
	}

	public function parseofferscron(){

		if (!$this->config->get('config_rainforest_enable_offers_parser')){
			echoLine('[ControllerKPRainForest::parseofferscron] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}

		if (!$this->config->get('config_rainforest_enable_pricing')){
			echoLine('[ControllerKPRainForest::parseofferscron] RNF AMAZON PRICING NOT ENABLED', 'e');
			return;
		}

		$this->load->library('hobotix/FPCTimer');
		if ($this->config->has('config_rainforest_offers_parser_time_start') && $this->config->has('config_rainforest_offers_parser_time_end')){
			$interval = new \hobotix\Interval($this->config->get('config_rainforest_offers_parser_time_start') . '-' . $this->config->get('config_rainforest_offers_parser_time_end'));

			if (!$interval->isNow()){
				echoLine('[ControllerKPRainForest::parseofferscron] NOT ALLOWED TIME');
				return;
			} else {
				echoLine('[ControllerKPRainForest::parseofferscron] ALLOWED TIME');				
			}
		}

		$this->rainforestAmazon = $this->registry->get('rainforestAmazon');
		$this->rainforestAmazon->checkIfPossibleToMakeRequest();
							
		$products = $this->rainforestAmazon->offersParser->getProductsToGetOffers();			

		$total = count($products);
		echoLine('[ControllerKPRainForest::parseofferscron] Total products to update: ' . $total, 'i');
		
		$iterations = ceil($total/(int)\hobotix\RainforestAmazon::offerRequestLimits);
		echoLine('[ControllerKPRainForest::parseofferscron] Total ' . $total . ' products!', 'i');

		$i=1;
		$timer = new \hobotix\FPCTimer();		

		for ($i = 1; $i <= $iterations; $i++){
			$timer = new \hobotix\FPCTimer();

			$slice = array_slice($products, (int)\hobotix\RainforestAmazon::offerRequestLimits * ($i-1), (int)\hobotix\RainforestAmazon::offerRequestLimits);
			$results = $this->rainforestAmazon->getProductsOffersASYNC($slice);

			if ($results){
				foreach ($results as $asin => $offers){			
					try {
						$this->rainforestAmazon->offersParser->addOffersForASIN($asin, $offers);
						$this->rainforestAmazon->offersParser->clearProductsAmazonQueue($asin);
					} catch (\Exception $e){
						echoLine('[ControllerKPRainForest::parseofferscron] Caught Exception, exiting.' . $e->getMessage());
						return;
					}
				}
			}

			echoLine('[ControllerKPRainForest::parseofferscron] Time for iteration: ' . $i . ' from ' . $iterations .': ' . $timer->getTime() . ' s.', 'i');
			unset($timer);
		}
	}
}

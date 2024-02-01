<?

namespace hobotix;


class RainforestAmazon
{

	private $db 	= null;	
	private $config = null;			
	private $log 	= null;	
	
	private $rfRequests = [];
	public $zipCodes 	= [];

	private $rfClient 			= null;	
	public $offersParser 		= null;
	public $infoUpdater 		= null;
	public $simpleProductParser = null;
	public $paramsTranslator 	= null;			
	public $categoryParser 		= null;

	private $telegramBot 	= null;
	private $tgAlertChatID 	= null;

	public const amazonOffersType 		= ['AP', 'A', 'P', 'O', 'N'];
	public const amazonOffersTypeSorted = ['AP' => 5, 'A' => 4, 'P' => 3, 'O' => 2, 'N' => 1];

	public const amazonSellerQualities 			= ['TE', 'T', 'E'];
	public const amazonSellerQualitiesSorted 	= ['TE' => 3, 'T' => 2, 'E' => 1];

	/*
		categoryParserLimit  - сколько отбирать категорий за один запуск
		categoryRequestLimits - Лимит для парсера категорий, сколько в параллели
	*/
	public const categoryParserLimit 	= 100;
	public const categoryRequestLimits 	= 20;
	
	/*
		Лимит для отбора пост-обработок
	*/
	public const generalDBQueryLimit	= 3000;

	/*
		offerParserLimit = Сколько отбирать товаров для получения офферов за один запуск
		offerRequestLimits = Сколько запросов в параллели
	*/
	public const offerParserLimit 		= 2000;
	public const noOfferParserLimit 	= 10000;
	public const offerRequestLimits 	= 20;

	/*
		fullProductParserLimit = Сколько отбирать товаров для получения полной информации за один запуск
		productRequestLimits   = Сколько запросов в параллели
	*/
	public const fullProductParserLimit 	= 200;
	public const productRequestLimits 		= 10;
	public const externalAPIRequestLimits 	= 5;

	/*
		categoryParserLimit = Сколько отбирать категорий для поиска по словам/ссылкам за один запуск
	*/
	public const categoryWordsParserLimit 	= 10;

	/*
		Лимит для отбора из очереди обработки вариантов
	*/
	public const variantQueueLimit 		= 30;

	public const categoryModeTables 		= ['standard' => 'category_amazon_tree', 	'bestsellers' => 'category_amazon_bestseller_tree'];
	public const categoryModeResultIndexes 	= ['standard' => 'category_results', 		'bestsellers' => 'bestsellers'];
	public const categoryModeInfoIndexes 	= ['standard' => 'category_info', 			'bestsellers' => 'bestsellers_info'];
	public const rainforestTypeMapping 		= ['standard' => 'category', 				'bestsellers' => 'bestsellers'];
	public const rainforestPrefixMapping 	= ['standard' => '', 						'bestsellers' => 'bestsellers_'];

	/*
		Таблички, имеющие отношение к товару. Из них нужно удалять данные при удалении товара
	*/
	public const productRelatedTables		= [
			'product_reward','product_attribute','product_feature','product_sponsored','product_to_store','product_to_category','product_also_bought','product_tmp','product_yam_recommended_prices','product_view_to_purchase','product_sticker','product_special_backup','product_costs','product_price_to_store','product_video','product_to_set','product_variants_ids','product_special','product_similar_to_consider','product_discount','product_sources','product_stock_waits','product_master','product_anyrelated','product_stock_limits','product_to_download','product_related_set','product_also_viewed','product_child','product_price_history','product_recurring','product_status','product_price_national_to_store','product_profile','product_to_tab','product_option_value','product_description','product_product_option','product_shop_by_look','product_stock_status','product_additional_offer','product_filter','product_similar','product','product_image','product_related','product_tab_content','product_to_layout','product_option','product_special_attribute','product_price_national_to_store1','product_front_price','product_video_description','product_product_option_value','product_price_national_to_yam','product_yam_data', 'review','ocfilter_option_value_to_product', 'product_ukrcredits'	
		];

	public const searchPageTypes = [
		'search' 			=> ['name' => 'Search Amazon', 			'default' => false],
		'standard' 			=> ['name' => 'Simple Category', 		'default' => true],
		'bestsellers' 		=> ['name' => 'Bestseller Category', 	'default' => false],	
		'store' 			=> ['name' => 'Brand Store', 			'default' => false],
		'seller_products' 	=> ['name' => 'Seller Products', 		'default' => false],
		'deals' 			=> ['name' => 'Deals Category', 		'default' => false]		
	];

	public const searchSorts = [
		'amazon' 			=> ['name' => 'Amazon', 				'default' => true],
		'price_low_to_high' => ['name' => 'Price Low to High', 		'default' => false],
		'price_high_to_low' => ['name' => 'Price High to Low',		'default' => false],
		'featured' 			=> ['name' => 'Featured',				'default' => false],
		'average_review' 	=> ['name' => 'Best Review First',		'default' => false],
		'most_recent' 		=> ['name' => 'Recent Added First',		'default' => false]
	];

	public function __construct($registry){

		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/RainforestLogger.php');
		$rainforestLogger = new Amazon\RainforestLogger($registry);

		if ($this->config->get('config_rainforest_enable_api') && $this->config->get('config_rainforest_api_key')){
			$this->rfClient = new \CaponicaAmazonRainforest\Client\RainforestClient(['api_key' => trim($this->config->get('config_rainforest_api_key'))], $rainforestLogger);
		} else {
			$this->rfClient = null;
		}		

		for ($i = 1; $i <= 5; $i++){
			if ($this->config->get('config_rainforest_api_zipcode_' . $i)){
				$this->zipCodes[] = $this->config->get('config_rainforest_api_zipcode_' . $i);
			}
		}

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/OffersParser.php');		
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/RainforestRetriever.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/InfoUpdater.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/ParamsTranslator.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/CategoryParser.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/CategoryRetriever.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/ProductsRetriever.php');
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/SimpleProductParser.php');

		$this->offersParser 		= new Amazon\OffersParser($registry);
		$this->infoUpdater 			= new Amazon\InfoUpdater($registry);		
		$this->paramsTranslator 	= new Amazon\ParamsTranslator();			
		$this->categoryParser 		= new Amazon\CategoryParser($registry, $this->rfClient);		
		$this->categoryRetriever 	= new Amazon\CategoryRetriever($registry);		
		$this->productsRetriever 	= new Amazon\ProductsRetriever($registry);		
		$this->simpleProductParser 	= new Amazon\SimpleProductParser($registry, $this->rfClient);	

		if ($this->config->get('config_telegram_bot_enable_alerts') && $this->config->get('config_telegram_bot_token') && $this->config->get('config_rainforest_tg_alert_group_id')){
			$this->telegramBot 		= new \Longman\TelegramBot\Telegram($this->config->get('config_telegram_bot_token'), $this->config->get('config_telegram_bot_name'));
			$this->tgAlertChatID 	= $this->config->get('config_rainforest_tg_alert_group_id');
		}
	}

	public function  getValidAmazonSitesArray(){
		if ($this->rfClient){
			return $this->rfClient->getValidAmazonSitesArray();
		} else {
			return [];
		}
	}

	public static function getAsinFromRequest($key, $rfOfferList){
		if (!$rfOfferList->getASIN()){
			preg_match('/(?<=~)([\s\S]+?)(?=~)/u', $key, $asin);
			$asin = $asin[0];
		} else {
			$asin = $rfOfferList->getASIN();
		}

		return $asin;
	}

	public function getRandomZipCode(){
		$zipCode = $this->zipCodes[array_rand($this->zipCodes)];
		echoLine('[RainforestAmazon] Using zipCode: ' . $zipCode, 'i');

		return $zipCode;
	}

	public function createLinkToAmazonSearchPage($asin){			
		return 'https://' . $this->config->get('config_rainforest_api_domain_1') . '/s?k=' . $asin;						
	}

	public function sendAlertToTelegram($message){
		try {
			$result = \Longman\TelegramBot\Request::sendMessage([
				'chat_id' => $this->tgAlertChatID,
				'text'    => $message,
				'parse_mode' => 'HTML',
			]);

		} catch (\Longman\TelegramBot\Exception\TelegramException $e) {
			echoLine($e->getMessage(), 'e');
		}
	}

	public function checkZipCodes(){
		$queryString = http_build_query([
			'api_key' => $this->config->get('config_rainforest_api_key'),
			'domain'  => $this->config->get('config_rainforest_api_domain_1')
		]);

		$error 		= false;	
		$warning 	= false;

		$ch = curl_init(sprintf('%s?%s', 'https://api.rainforestapi.com/zipcodes', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$answer 	= curl_exec($ch);
		$httpcode 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpcode != 200){
			$error = 'CODE_NOT_200_MAYBE_PAYMENT_FAIL';
			$answer .= ' HTTPCODE: ' . $httpcode;
			$answer = trim($answer);
		}

		if (!$error && !json_decode($answer)){
			$error = 'JSON_DECODE';
			$answer .= '<br />HTTPCODE: ' . $httpcode;
		}

		if ($answer_decoded = json_decode($answer, true)){
			$answer = $answer_decoded;
		}

		return $answer;
	}

	public function checkIfPossibleToMakeRequest($return = false, $debug = false){
		$queryString = http_build_query([
			'api_key' => $this->config->get('config_rainforest_api_key')
		]);

		$ch = curl_init(sprintf('%s?%s', 'https://api.rainforestapi.com/account', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if ($debug){
			$tmpfile = tmpfile();
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_STDERR, $tmpfile);
		}

		$answer 	= curl_exec($ch);
		$httpcode 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($debug){
			rewind($tmpfile);
			$debug_info = stream_get_contents($tmpfile);
			fclose($tmpfile);
		} else {
			$debug_info = '';
		}

		$error 		= false;	
		$warning 	= false;	

		if ($httpcode != 200){
			$error = 'CODE_NOT_200_MAYBE_PAYMENT_FAIL';
			$answer .= ' HTTPCODE: ' . $httpcode;
			$answer = trim($answer);
		}

		if (!$error && !json_decode($answer)){
			$error = 'JSON_DECODE';
			$answer .= '<br />HTTPCODE: ' . $httpcode;
		}

		if ($answer_decoded = json_decode($answer, true)){
			$answer = $answer_decoded;
		}

		if (!$error && empty($answer['account_info'])){
			$error = 'NO_ACCOUNT_INFO';
		}

		if (!$error && $answer && !empty($answer['account_info']) && $answer['account_info']['credits_remaining'] > 0){
			if ($answer['account_info']['credits_remaining'] <= $answer['account_info']['credits_limit']/20){
				$warning = 'CREDITS_LESS_THEN_5_PERCENT';
			}
		}

		if (!$error && $answer && !empty($answer['account_info']) && $answer['account_info']['credits_remaining'] == 0){
			if ($answer['account_info']['overage_enabled'] && $answer['account_info']['overage_used'] >= $answer['account_info']['overage_limit']){
				$error = 'ZERO_CREDITS_AND_OVERAGE_OVERLIMIT';
			} elseif ($answer['account_info']['overage_enabled'] && $answer['account_info']['overage_used'] < $answer['account_info']['overage_limit']){								
				$warning = 'ZERO_CREDITS_AND_OVERAGE_IS_USED_NOW';
			} elseif (!$answer['account_info']['overage_enabled']) {
				$error = 'ZERO_CREDITS_AND_OVERAGE_NOT_ENABLED';
			}
		}

		if (strpos($debug_info, 'SSL certificate verify result: unable to get local issuer certificate')){
			$debug_info = 'SSL certificate verify result: unable to get local issuer certificate!';
		}

		if ($error){
			if ($this->config->get('config_telegram_bot_enable_alerts')){
				$text = '😂 <b>Хьюстон, у нас проблема!</b>' . PHP_EOL . PHP_EOL;
				$text .= 'Проблемы с Rainforest API. Скорее всего не оплачен тариф.' . PHP_EOL . PHP_EOL;
				$text .= 'Сервер отвечает: ' . json_encode($answer) . PHP_EOL;
				$this->sendAlertToTelegram($text);
			}

			if ($return){
				return ['status' => false, 'message' => $error, 'answer' => json_encode($answer), 'debug' => $debug_info];
			} else {
				throw new \Exception($error);			
				die($error);			
			}
		}

		if ($warning){
			return ['status' => false, 'message' => $warning, 'answer' => $answer, 'debug' => $debug_info];
		}

		return ['status' => true, 'message' => '', 'answer' => $answer, 'debug' => $debug_info];
	}	

	public function searchCategories($filter_name){			
	}

	public function processAmazonRainforestPageRequestPaginationResults($response){
		$pagination = [];

		if (!is_array($response)){
			$response = json_decode($response, true);
		}

		if (!empty($response['pagination'])){
			$pagination = $response['pagination'];
		}

		return $pagination;
	}

	public function processAmazonRainforestPageRequestProductResults($response){
		$products = [];

		$type = 'unknown';
		if (!empty($response['request_parameters'])){
			$type = $response['request_parameters']['type'];
		}

		if ($type == 'category'){
			$products = $response['category_results'];
		} elseif ($type == 'bestsellers'){
			$products = $response['bestsellers'];
		} elseif ($type == 'deals'){
			$products = $response['deals'];
		} elseif ($type == 'search'){
			$products = $response['search_results'];
		} elseif ($type == 'store'){
			$products = $response['store_results'];
		} elseif ($type == 'store'){
			$products = $response['store_results'];
		} elseif ($type == 'seller_products'){
			$products = $response['seller_products'];
		}

		return $products;
	}

	public function prepareAmazonRainforestPageRequest($options){
		$request = [];

		if ($options['type'] == 'standard'){
			$options['type'] = 'category';
		}

		if (empty($options['page'])){
			$options['page'] = 1;
		}

		if ($options['type'] == 'search'){
			$request = [
				'type' 			=> $options['type'], 		
				'search_term' 	=> $options['word_or_uri'],
				'page' 			=> $options['page'],
				'sort_by' 		=> $options['sort'],
			];
		} elseif (!empty($options['category_id'])) {
			$request = [
				'type' 			=> $options['type'], 		
				'category_id' 	=> $options['category_id'],
				'page' 			=> $options['page'],
				'sort_by' 		=> $options['sort']
			];
		} elseif (!empty($options['word_or_uri'])){
			$request = [
				'type' 			=> $options['type'], 		
				'url' 			=> str_replace('&amp;', '&', $options['word_or_uri'])
			];
		}

		if ($options['sort'] == 'amazon'){
			unset($request['sort_by']);
		}

		return $request;
	}

	public function getProductByASIN($asins){

		$this->checkIfPossibleToMakeRequest();

		$rfRequests = [];

		foreach ($asins as $asin){
			$rfRequests[] = new \CaponicaAmazonRainforest\Request\ProductRequest($this->config->get('config_rainforest_api_domain_1'), $asin, ['customer_zipcode' => $this->getRandomZipCode()]);
		}
		$apiEntities = $this->rfClient->retrieveProducts($rfRequests);


		if (!$apiEntities){									
			echoLine('[RainforestAmazon::getProductByASIN] ASIN not found ' . $row['asin'], 'e');			
		} else {
			echoLine('[RainforestAmazon::getProductByASIN] ASIN found ' . $row['asin'], 'i');
		}

		return $apiEntities;			
	}

	public function getProductsOffersASYNC($products){

		$this->checkIfPossibleToMakeRequest();

		$rfRequests = [];

		$options = ['customer_zipcode' => $this->getRandomZipCode()];

		if ($this->config->get('config_rainforest_amazon_filters_1')){
			foreach ($this->config->get('config_rainforest_amazon_filters_1') as $amazon_filter_1){		
				$options[$amazon_filter_1] = true;
			}
		}

		foreach ($products as $asin){
			$rfRequests[] = new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $asin, $options);
		}

		$apiEntities = $this->rfClient->retrieveOffers($rfRequests);	
		
		$results = [];
		$retrievedProducts = [];
		unset($rfOfferList);					

		foreach ($apiEntities as $key => $rfOfferList){	
			$rfAsin = self::getAsinFromRequest($key, $rfOfferList);
			$retrievedProducts[] = $rfAsin;

			if ($rfOfferList->getOfferCount()){
				$this->offersParser->setLastOffersDate($rfAsin)->setProductOffers($rfAsin);
				echoLine('[RainforestAmazon::getProductsOffersASYNC] ' . $rfAsin . ': ' . $rfOfferList->getOfferCount() . ' offers', 's');					
			}
		}

		foreach (array_diff($products, $retrievedProducts) as $notRetrievedASIN){
			echoLine('[RainforestAmazon::getProductsOffersASYNC] No offers for ' . $notRetrievedASIN);				
			$this->offersParser->setLastOffersDate($notRetrievedASIN)->setProductNoOffers($notRetrievedASIN);
		}

		foreach ($apiEntities as $key => $rfOfferList){
			$rfAsin = self::getAsinFromRequest($key, $rfOfferList);

			$results[$rfAsin] = [];

			foreach ($rfOfferList->getOffers() as $apiOffer){
				$results[$rfAsin][] = $apiOffer;
			}

			if ($rfOfferList->hasMorePages() && count($results[$rfAsin]) == 10){
				echoLine('[RainforestAmazon::getProductsOffersASYNC] Page 2 ' . $rfOfferList->hasMorePages());

				$options['page'] = $rfOfferList->getCurrentPage() + 1;
				$rfRequests = [new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $asin, $options)];
				$apiEntitiesPage = $this->rfClient->retrieveOffers($rfRequests);

				foreach ($apiEntitiesPage as $key => $rfOfferListPage) {
					foreach ($rfOfferListPage->getOffers() as $apiOffer){
						$results[$rfAsin][] = $apiOffer;
					}
				}

				while(!empty($rfOfferListPage) && is_object($rfOfferListPage) && $rfOfferListPage->hasMorePages()){

					$options['page'] = $rfOfferListPage->getCurrentPage() + 1;
					$rfRequests = [new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $asin, $options)];
					$apiEntitiesPage = $this->rfClient->retrieveOffers($rfRequests);

					foreach ($apiEntitiesPage as $key => $rfOfferListPage) {
						foreach ($rfOfferListPage->getOffers() as $apiOffer){
							$results[$rfAsin][] = $apiOffer;
						}
					}
				}	
			}	
		}

		return $results;
	}

	public function getProductOffers($product, $validateASIN = true){

		$this->checkIfPossibleToMakeRequest();

		$rfRequests = []; 

		if ($validateASIN){
			$product = $this->infoUpdater->validateASINAndUpdateIfNeeded($product);						
		}

		if (!empty($product['asin'])){

			$options = ['customer_zipcode' => $this->getRandomZipCode()];

			if ($this->config->get('config_rainforest_amazon_filters_1')){
				foreach ($this->config->get('config_rainforest_amazon_filters_1') as $amazon_filter_1){		
					$options[$amazon_filter_1] = true;
				}
			}

			$rfRequests[] = new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $product['asin'], $options);

		} else {
			return false;
		}

		$apiEntitiesTMP = $this->rfClient->retrieveOffers($rfRequests);						

		$apiOffers = [];
		foreach ($apiEntitiesTMP as $key => $rfOfferList) {

			foreach ($rfOfferList->getOffers() as $apiOffer){								
				$apiOffers[] = $apiOffer;
			}		

			if ($rfOfferList->hasMorePages()){
				$options['page'] = $rfOfferList->getCurrentPage() + 1;
				$rfRequests = [new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $product['asin'], $options)];
				$apiEntitiesPage = $this->rfClient->retrieveOffers($rfRequests);

				foreach ($apiEntitiesPage as $key => $rfOfferListPage) {
					foreach ($rfOfferListPage->getOffers() as $apiOffer){
						$apiOffers[] = $apiOffer;
					}
				}

				while(!empty($rfOfferListPage) && is_object($rfOfferListPage) && $rfOfferListPage->hasMorePages()){

					$options['page'] = $rfOfferListPage->getCurrentPage() + 1;
					$rfRequests = [new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $product['asin'], $options)];
					$apiEntitiesPage = $this->rfClient->retrieveOffers($rfRequests);

					foreach ($apiEntitiesPage as $key => $rfOfferListPage) {
						foreach ($rfOfferListPage->getOffers() as $apiOffer){
							$apiOffers[] = $apiOffer;
						}
					}
				}	
			}								
		}
		return $apiOffers;
	}

	public function getOffers($data){}

}												

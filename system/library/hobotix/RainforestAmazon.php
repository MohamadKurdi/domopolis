<?

namespace hobotix;


class RainforestAmazon
{

	private $db;	
	private $config;		
	private $rfRequests = [];

	private $rfClient;	
	public $offersParser;
	public $infoUpdater;
	public $simpleProductParser;
	public $paramsTranslator;	

	private $telegramBot;
	private $tgAlertChatID = null;

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
	public const offerParserLimit 		= 3000;
	public const offerRequestLimits 	= 30;

	/*
		fullProductParserLimit = Сколько отбирать товаров для получения полной информации за один запуск
		productRequestLimits   = Сколько запросов в параллели
	*/
	public const fullProductParserLimit = 300;
	public const productRequestLimits 	= 30;

	public const categoryModeTables 		= ['standard' => 'category_amazon_tree', 	'bestsellers' => 'category_amazon_bestseller_tree'];
	public const categoryModeResultIndexes 	= ['standard' => 'category_results', 		'bestsellers' => 'bestsellers'];
	public const categoryModeInfoIndexes 	= ['standard' => 'category_info', 			'bestsellers' => 'bestsellers_info'];
	public const rainforestTypeMapping 		= ['standard' => 'category', 				'bestsellers' => 'bestsellers'];
	public const rainforestPrefixMapping 	= ['standard' => '', 						'bestsellers' => 'bestsellers_'];

	/*
		Таблички, имеющие отношение к товару. Из них нужно удалять данные при удалении товара
	*/
	public const productRelatedTables		= [
			'product_reward','product_attribute','product_sponsored','product_to_store','product_to_category','product_also_bought','product_tmp','product_yam_recommended_prices','product_view_to_purchase','product_sticker','product_special_backup','product_costs','product_price_to_store','product_video','product_to_set','product_variants_ids','product_special','product_similar_to_consider','product_discount','product_sources','product_stock_waits','product_master','product_anyrelated','product_stock_limits','product_to_download','product_related_set','product_also_viewed','product_child','product_price_history','product_recurring','product_status','product_price_national_to_store','product_profile','product_to_tab','product_option_value','product_description','product_product_option','product_shop_by_look','product_stock_status','product_additional_offer','product_filter','product_similar','product','product_image','product_related','product_tab_content','product_to_layout','product_option','product_special_attribute','product_price_national_to_store1','product_front_price','product_video_description','product_product_option_value','product_price_national_to_yam','product_yam_data', 'review','ocfilter_option_value_to_product'	
		];
		
	public $categoryParser;

	public function __construct($registry){

		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/RainforestLogger.php');
		$rainforestLogger = new Amazon\RainforestLogger($registry);

		$this->rfClient = new \CaponicaAmazonRainforest\Client\RainforestClient(['api_key' => trim($this->config->get('config_rainforest_api_key'))], $rainforestLogger);

		//Loading Classes
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/OffersParser.php');
		$this->offersParser = new Amazon\OffersParser($registry);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/InfoUpdater.php');
		$this->infoUpdater = new Amazon\InfoUpdater($registry, $this->rfClient);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/ParamsTranslator.php');
		$this->paramsTranslator = new Amazon\ParamsTranslator();
				
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/CategoryParser.php');
		$this->categoryParser = new Amazon\CategoryParser($registry, $this->rfClient);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/RainforestRetriever.php');

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/CategoryRetriever.php');
		$this->categoryRetriever = new Amazon\CategoryRetriever($registry);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/ProductsRetriever.php');
		$this->productsRetriever = new Amazon\ProductsRetriever($registry);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/SimpleProductParser.php');
		$this->simpleProductParser = new Amazon\SimpleProductParser($registry, $this->rfClient);	

		if ($this->config->get('config_telegram_bot_enable_alerts') && $this->config->get('config_telegram_bot_token') && $this->config->get('config_rainforest_tg_alert_group_id')){
			$this->telegramBot = new \Longman\TelegramBot\Telegram($this->config->get('config_telegram_bot_token'), $this->config->get('config_telegram_bot_name'));
			$this->tgAlertChatID = $this->config->get('config_rainforest_tg_alert_group_id');
		}
	}

	public function  getValidAmazonSitesArray(){
		return $this->rfClient->getValidAmazonSitesArray();
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

			var_dump($result);
		} catch (\Longman\TelegramBot\Exception\TelegramException $e) {
			echoLine($e->getMessage());
		}
	}

	public function checkIfPossibleToMakeRequest($return = false){
		$queryString = http_build_query([
			'api_key' => $this->config->get('config_rainforest_api_key')
		]);

		$ch = curl_init(sprintf('%s?%s', 'https://api.rainforestapi.com/account', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$answer 	= curl_exec($ch);
		$httpcode 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$error 		= false;	
		$warning 	= false;		

		//В том случае, если не 200, то всё плохо и хуево
		if ($httpcode != 200){
			$error = 'PAYMENT_FAIL';
		}

		if (!$error && !json_decode($answer)){
			$error = 'JSON_DECODE';
		}

		$answer = json_decode($answer, true);
		if (!$error && empty($answer['account_info'])){
			$error = 'NO_ACCOUNT_INFO';
		}

		if (!$error && $answer && !empty($answer['account_info']) && $answer['account_info']['credits_remaining'] > 0){
			if ($answer['account_info']['credits_remaining'] <= $answer['account_info']['credits_limit']/10){
				$warning = 'CREDITS_LESS_THEN_10_PERCENT';
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

		if ($error){
			if ($this->config->get('config_telegram_bot_enable_alerts')){
				$text = '😂 <b>Хьюстон, у нас проблема!</b>' . PHP_EOL . PHP_EOL;
				$text .= '@lexdanelia, тут это, с оплатой Rainforest что-то, мы цены не обновляем!' . PHP_EOL . PHP_EOL;
				$text .= 'Сервер отвечает: ' . $api_result . PHP_EOL;
				$this->sendAlertToTelegram($text);
			}

			if ($return){
				return ['status' => false, 'message' => $error, 'answer' => $answer];
			} else {
				throw new \Exception($error);			
				die($error);			
			}
		}

		if ($warning){
			return ['status' => false, 'message' => $warning, 'answer' => $answer];
		}

		return ['status' => true, 'message' => '', 'answer' => $answer];
	}	

	public function searchCategories($filter_name){			
	}

	public function getProductByASIN($asins){

		$this->checkIfPossibleToMakeRequest();

		$rfRequests = [];

		foreach ($asins as $asin){
			$rfRequests[] = new \CaponicaAmazonRainforest\Request\ProductRequest($this->config->get('config_rainforest_api_domain_1'), $asin, ['customer_zipcode' => $this->config->get('config_rainforest_api_zipcode_1')]);
		}
		$apiEntities = $this->rfClient->retrieveProducts($rfRequests);


		if (!$apiEntities){									
			echoLine('[RNF API] ASIN не найден ' . $row['asin']);			
		} else {
			echoLine('[RNF API] ASIN найден ' . $row['asin']);
		}

		return $apiEntities;			
	}

	public function getProductsOffersASYNC($products){

		$this->checkIfPossibleToMakeRequest();

		$rfRequests = [];

		$options = ['customer_zipcode' => $this->config->get('config_rainforest_api_zipcode_1')];

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
				echoLine('[RainforestAmazon] ' . $rfAsin . ': ' . $rfOfferList->getOfferCount() . ' предложений');					
			}
		}

		foreach (array_diff($products, $retrievedProducts) as $notRetrievedASIN){
			echoLine('[RainforestAmazon] Не получили предложения по ' . $notRetrievedASIN);				
			$this->offersParser->setLastOffersDate($notRetrievedASIN)->setProductNoOffers($notRetrievedASIN);
		}

		foreach ($apiEntities as $key => $rfOfferList){
			$rfAsin = self::getAsinFromRequest($key, $rfOfferList);

			$results[$rfAsin] = [];

			foreach ($rfOfferList->getOffers() as $apiOffer){
				$results[$rfAsin][] = $apiOffer;
			}

			if ($rfOfferList->hasMorePages() && count($results[$rfAsin]) == 10){
				echoLine('[RainforestAmazon] Страница 2 ' . $rfOfferList->hasMorePages());

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

			$options = ['customer_zipcode' => $this->config->get('config_rainforest_api_zipcode_1')];

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
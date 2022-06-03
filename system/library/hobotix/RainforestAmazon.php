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
	private $tgAlertChatID = '-698960050';

		//Эта шляпа может использовать в быстром получении категорий
	public $categoryParser;

	public function __construct($registry){

		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->log = $registry->get('log');

		$this->rfClient = new \CaponicaAmazonRainforest\Client\RainforestClient(['api_key' => trim($this->config->get('config_rainforest_api_key'))]);

			//Loading Classes
		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/OffersParser.php');
		$this->offersParser = new Amazon\OffersParser($registry);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/InfoUpdater.php');
		$this->infoUpdater = new Amazon\InfoUpdater($registry, $this->rfClient);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/ParamsTranslator.php');
		$this->paramsTranslator = new Amazon\ParamsTranslator();

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/CategoryParser.php');
		$this->categoryParser = new Amazon\CategoryParser($registry, $this->rfClient);

		require_once(DIR_SYSTEM . 'library/hobotix/Amazon/SimpleProductParser.php');
		$this->simpleProductParser = new Amazon\SimpleProductParser($registry, $this->rfClient);	

		if ($this->config->get('config_telegram_bot_enable_alerts') && $this->config->get('config_telegram_bot_token')){
			$this->telegramBot = new \Longman\TelegramBot\Telegram($this->config->get('config_telegram_bot_token'), $this->config->get('config_telegram_bot_name'));
		}

	}

	public function  getValidAmazonSitesArray(){
		return $this->rfClient->getValidAmazonSitesArray();
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

	public function checkIfPossibleToMakeRequest(){
		$queryString = http_build_query([
			'api_key' => $this->config->get('config_rainforest_api_key')
		]);

		$ch = curl_init(sprintf('%s?%s', 'https://api.rainforestapi.com/account', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$api_result = curl_exec($ch);
		$httpcode 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);		

		if ($httpcode == 200){
			return true;
		}

		if ($this->config->get('config_telegram_bot_enable_alerts')){
			$text = '😂 <b>Хьюстон, у нас проблема!</b>' . PHP_EOL . PHP_EOL;
			$text .= '@lexdanelia, тут это, с оплатой Rainforest что-то, мы цены не обновляем!' . PHP_EOL . PHP_EOL;
			$text .= 'Сервер отвечает: ' . $api_result . PHP_EOL;
			$this->sendAlertToTelegram($text);
		}

		throw new \Exception('Please update your payment details to continue using Rainforest API.');
		die();

		return false;
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
				//Это значит, что ASIN товара не валидный, удалим его
			echoLine('[RNF API] ASIN не найден ' . $row['asin']);
			$this->infoUpdater->updateASINInDatabase(['product_id' => $row['product_id'], 'asin' => '']);

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
			$retrievedProducts[] = $rfOfferList->getASIN();

			if ($rfOfferList->getOfferCount()){
				$this->offersParser->setLastOffersDate($rfOfferList->getASIN())->setProductOffers($rfOfferList->getASIN());
				echoLine('[RainforestAmazon] ' . $rfOfferList->getASIN() . ': ' . $rfOfferList->getOfferCount() . ' предложений');					
			}
		}

		foreach (array_diff($products, $retrievedProducts) as $notRetrievedASIN){
			echoLine('[RainforestAmazon] Не получили предложения по ' . $notRetrievedASIN);				
			$this->offersParser->setLastOffersDate($notRetrievedASIN)->setProductNoOffers($notRetrievedASIN);
		}

		foreach ($apiEntities as $key => $rfOfferList){
			$results[$rfOfferList->getASIN()] = [];

			foreach ($rfOfferList->getOffers() as $apiOffer){
				$results[$rfOfferList->getASIN()][] = $apiOffer;
			}

			if ($rfOfferList->hasMorePages()){

				$options['page'] = $rfOfferList->getCurrentPage() + 1;
				$rfRequests = [new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $asin, $options)];
				$apiEntitiesPage = $this->rfClient->retrieveOffers($rfRequests);

				foreach ($apiEntitiesPage as $key => $rfOfferListPage) {
					foreach ($rfOfferListPage->getOffers() as $apiOffer){
						$results[$rfOfferList->getASIN()][] = $apiOffer;
					}
				}

				while(!empty($rfOfferListPage) && is_object($rfOfferListPage) && $rfOfferListPage->hasMorePages()){

					$options['page'] = $rfOfferListPage->getCurrentPage() + 1;
					$rfRequests = [new \CaponicaAmazonRainforest\Request\OfferRequest($this->config->get('config_rainforest_api_domain_1'), $asin, $options)];
					$apiEntitiesPage = $this->rfClient->retrieveOffers($rfRequests);

					foreach ($apiEntitiesPage as $key => $rfOfferListPage) {
						foreach ($rfOfferListPage->getOffers() as $apiOffer){
							$results[$rfOfferList->getASIN()][] = $apiOffer;
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
					//$this->log->debug($apiOffer);

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
<?php
	
	namespace hobotix\Amazon;
	
	class RainforestRetriever
	{

		public $db 			= null;	
		public $log 		= null;	
		public $config 		= null;
		public $endpoint 	= null;
		public $registry 	= null;
		
		public $translateAdaptor 		= null;
		public $model_catalog_category 	= null;
		public $model_catalog_product 	= null;
		public $model_product_add 		= null;
		
		public $jsonResult = null;

		private $requestTimeOut = 100;
		private $connectTimeOut = 10;

		public function __construct($registry){			
			$this->registry 		= $registry;
			$this->config 			= $registry->get('config');
			$this->db 				= $registry->get('db');
			$this->log 				= $registry->get('log');	

			if ($this->config->get('config_rainforest_enable_translation')){
				$this->translateAdaptor = $registry->get('translateAdaptor');
			}	

			if ($this->config->get('config_rainforest_debug_curl_request_timeout')){
				if (get_class($this) == 'RainforestRetriever'){
					echoLine('[RainforestRetriever::__construct] Request timeout from settings: ' . $this->config->get('config_rainforest_debug_curl_request_timeout'), 'd');
				}
				$this->requestTimeOut = (int)$this->config->get('config_rainforest_debug_curl_request_timeout');
			}	

			if ($this->config->get('config_rainforest_debug_curl_connect_timeout')){
				if (get_class($this) == 'RainforestRetriever'){
					echoLine('[RainforestRetriever::__construct] Connect timeout from settings: ' . $this->config->get('config_rainforest_debug_curl_connect_timeout'), 'd');
				}
				$this->connectTimeOut = (int)$this->config->get('config_rainforest_debug_curl_connect_timeout');
			}	

			require_once(DIR_SYSTEM . 'library/hobotix/Amazon/models/hoboModel.php');	
			require_once(DIR_SYSTEM . 'library/hobotix/Amazon/models/productModelEdit.php');
			require_once(DIR_SYSTEM . 'library/hobotix/Amazon/models/productModelGet.php');
			require_once(DIR_SYSTEM . 'library/hobotix/Amazon/models/productModelCachedGet.php');

			$this->model_product_edit 		= new productModelEdit($registry);
			$this->model_product_get 		= new productModelGet($registry);
			$this->model_product_cached_get = new productModelCachedGet($registry);
		}

		const CLASS_NAME = 'hobotix\\Amazon\\RainforestRetriever';

		public function checkIfPossibleToMakeRequest(){
			$this->registry->get('rainforestAmazon')->checkIfPossibleToMakeRequest();
		}

		public function setJsonResult($json){
			$this->jsonResult = $json;			
		}

		public function getJsonResult(){
			return $this->jsonResult;			
		}

		public function getTotalPages(){
			if (!empty($this->jsonResult['pagination'])){
				return $this->jsonResult['pagination']['total_pages'];				
			}

			return false;
		}

		public function getCurrentPages(){
			if (!empty($this->jsonResult['pagination'])){
				return $this->jsonResult['pagination']['current_page'];				
			}

			return false;
		}

		public function getNextPage(){
			if (!empty($this->jsonResult['pagination'])){

				if ($this->jsonResult['pagination']['current_page'] < $this->jsonResult['pagination']['total_pages']){
					return ((int)$this->jsonResult['pagination']['current_page'] + 1);
				}
			}

			return false;
		}

		public function getImage($amazonImage, $secondAttempt = false){
			if (!trim($amazonImage)){
				return '';
			}

			$localImageName 		= md5($amazonImage) . '.' . pathinfo($amazonImage,  PATHINFO_EXTENSION);
			$localImageDir  		= 'data/source/' . mb_substr($localImageName, 0, 3) . '/' . mb_substr($localImageName, 4, 6) . '/';
			$localImagePath 		= DIR_IMAGE . $localImageDir;
			$fullLocalImagePath 	= $localImagePath . $localImageName;
			$relativeLocalImagePath = $localImageDir . $localImageName;

			if (!file_exists($fullLocalImagePath)){

				try{
					$httpClient = new \GuzzleHttp\Client(['timeout' => 30]);
					$httpResponse = $httpClient->request('GET', $amazonImage, ['stream' => true]);	

					if (!is_dir($localImagePath)){
						mkdir($localImagePath, 0775, true);
					}

					file_put_contents($fullLocalImagePath, $httpResponse->getBody()->getContents());					

				} catch (GuzzleHttp\Exception\ValueError|GuzzleHttp\Exception\ClientException $e){
					echoLine('[RainforestRetriever::getImage]: Could not get picture: ' . $e->getMessage(), 'e');
					return '';
				} catch (\Exception $re){

					if (!$secondAttempt){
						echoLine('[RainforestRetriever::getImage]: Could not get picture, maybe timeout ' . $re->getMessage(), 'e');
						sleep(mt_rand(3, 5));
						$this->getImage($amazonImage, true);

					} else {
						return '';
					}
				}
			}

			echoLine('[RainforestRetriever::getImage] Picture: ' . $amazonImage . ' -> ' . $localImageName, 's');

			return $localImageDir . $localImageName;
		}	

		public function parseResponse($response, $request = null){
			$raw 		= $response;		
			$response 	= json_decode($response, true);	

			$this->registry->get('rainforestAmazon')->checkResponseForZipcode($response, $request);
			
			if (!isset($response['request_info']['success'])){
				throw new \Exception((string)$raw);
				echoLine('[RainforestRetriever::parseResponse] Something bad happened, stopping!', 'e');
				die();				
			}
			
			if (!$response['request_info']['success']){
				throw new \Exception((string)$raw);
				return false;
			}

			return $response;			
		}

		public function doRequest($params = []){
			$data = [
			'api_key' 			=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' 	=> $this->config->get('config_rainforest_api_domain_1'),
			'customer_zipcode' 	=> $this->registry->get('rainforestAmazon')->zipcodesManager->getRandomZipCode()
			];
			
			$data = array_merge($data, $params);
			$queryString =  http_build_query($data);
				
			$channel = curl_init('https://api.rainforestapi.com/request?' . $queryString);
			curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($channel, CURLOPT_FOLLOWLOCATION, true);
			
			curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, $this->connectTimeOut); 
			curl_setopt($channel, CURLOPT_TIMEOUT, $this->requestTimeOut);
			
			if ($this->config->get('config_rainforest_debug_products')){
				curl_setopt($channel, CURLOPT_VERBOSE, true);
			} else {
				curl_setopt($channel, CURLOPT_VERBOSE, false);	
			}
			
			$response = curl_exec($channel);		
			curl_close($channel);

			if ($this->parseResponse($response, $channel)){
				$this->setJsonResult(json_decode($response, true));
			}
					
			return $this;
		}	

		public function doMultiRequest($requests = []){
			$multi 		= curl_multi_init();
			$channels 	= [];
			$results 	= [];

			foreach ($requests as $request_id => $params){
				$channels[$request_id] 	= $this->createRequest($params);	
				$results[$request_id] 	= [];
				curl_multi_add_handle($multi, $channels[$request_id]);
			}
				
			$running = null;
			do {
				curl_multi_exec($multi, $running);
			} while ($running);

			foreach ($channels as $channel) {
				curl_multi_remove_handle($multi, $channel);
			}
			curl_multi_close($multi);

			foreach ($channels as $request_id => $channel) {
				$results[$request_id] = $this->parseResponse(curl_multi_getcontent($channel), $channel);				
			}			

			return $results;	
		}

		public function createRequest($params = []){
			$data = [
			'api_key' 			=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' 	=> $this->config->get('config_rainforest_api_domain_1'),
			'customer_zipcode' 	=> $this->registry->get('rainforestAmazon')->zipcodesManager->getRandomZipCode()
			];
			
			$data = array_merge($data, $params);

			if (!empty($data['url'])){
				unset($data['amazon_domain']);
				unset($data['customer_zipcode']);
			}

			if (!empty($data['type']) && $data['type'] == 'deals'){
				unset($data['customer_zipcode']);
			}

			$queryString =  http_build_query($data);
						
			$channel = curl_init('https://api.rainforestapi.com/request?' . $queryString);
			
			curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($channel, CURLOPT_FOLLOWLOCATION, true);

			curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, $this->connectTimeOut); 
			curl_setopt($channel, CURLOPT_TIMEOUT, $this->requestTimeOut);
			
			if ($this->config->get('config_rainforest_debug_products')){
				curl_setopt($channel, CURLOPT_VERBOSE, true);
			} else {
				curl_setopt($channel, CURLOPT_VERBOSE, false);	
			}	
			
			return $channel;			
		}				
	}
		
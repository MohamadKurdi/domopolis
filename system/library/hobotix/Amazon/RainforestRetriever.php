<?
	
	namespace hobotix\Amazon;
	
	class RainforestRetriever
	{

		public $db;	
		public $config;		
		public $endpoint;	

		public $model_catalog_category = null;
		public $model_catalog_product = null;

		public $model_product_add = null;

		public $yandexTranslator = null;
		
		public $jsonResult = null;

		public function __construct($registry){
			
			$this->registry 		= $registry;
			$this->config 			= $registry->get('config');
			$this->db 				= $registry->get('db');
			$this->log 				= $registry->get('log');	

			if ($this->config->get('config_rainforest_enable_translation')){
				$this->yandexTranslator = $registry->get('yandexTranslator');
			}	

			//models
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

			$localImageName 		= md5($amazonImage) . '.' . pathinfo($amazonImage,  PATHINFO_EXTENSION);
			$localImageDir  		= 'data/source/' . mb_substr($localImageName, 0, 3) . '/' . mb_substr($localImageName, 4, 6) . '/';
			$localImagePath 		= DIR_IMAGE . $localImageDir;
			$fullLocalImagePath 	= $localImagePath . $localImageName;
			$relativeLocalImagePath = $localImageDir . $localImageName;

			if (!file_exists($fullLocalImagePath)){

				try{
					$httpClient = new \GuzzleHttp\Client();
					$httpResponse = $httpClient->request('GET', $amazonImage, ['stream' => true]);	

					if (!is_dir($localImagePath)){
					//	echoLine('RainforestRetriever: директория ' . $localImagePath);
						mkdir($localImagePath, 0775, true);
					}

					file_put_contents($fullLocalImagePath, $httpResponse->getBody()->getContents());					

				} catch (GuzzleHttp\Exception\ClientException $e){
					echoLine('[RainforestRetriever]: Не могу получить картинку ' . $e->getMessage());
					return '';
				} catch (\Exception $re){

					if (!$secondAttempt){
						echoLine('[RainforestRetriever]: Не могу получить картинку, скорее всего таймаут ' . $re->getMessage());
						sleep(mt_rand(3, 5));
						$this->getImage($amazonImage, true);

					} else {
						return '';
					}
				}
			}

			echoLine('[RainforestRetriever] Картинки: ' . $amazonImage . ' -> ' . $localImageName);

			return $localImageDir . $localImageName;
		}	

		public function parseResponse($response){
			$response = json_decode($response, true);	
			
			if (!isset($response['request_info']['success'])){
				throw new \Exception($response);
				die('Что-то пошло не так!');				
			}
			
			if ($response['request_info']['success'] == false){
				throw new \Exception($response);
				return false;
			}

			return $response;			
		}

		public function doRequest($params = []){
		
			$data = [
			'api_key' 			=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' 	=> $this->config->get('config_rainforest_api_domain_1'),
			'customer_zipcode' 	=> $this->config->get('config_rainforest_api_zipcode_1')
			];
			
			$data = array_merge($data, $params);
			$queryString =  http_build_query($data);
		
		
			$ch = curl_init('https://api.rainforestapi.com/request?' . $queryString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			
			$json = curl_exec($ch);		
			curl_close($ch);
			
			$this->setJsonResult(json_decode($json, true));

			return $this;
		}		


		public function createRequest($params = []){
			
			$data = [
			'api_key' 			=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' 	=> $this->config->get('config_rainforest_api_domain_1'),
			'customer_zipcode' 	=> $this->config->get('config_rainforest_api_zipcode_1')
			];
			
			$data = array_merge($data, $params);
			$queryString =  http_build_query($data);
			
			
			$ch = curl_init('https://api.rainforestapi.com/request?' . $queryString);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_VERBOSE, false);	
			
			return $ch;
			
		}				

	}
		
<?

	/*
		Class for work only with products asyncronyously		
	*/
	
	namespace hobotix\Amazon;
	
	class SimpleProductParser
	{
		
		private $db;	
		private $config;
		
		private $rfClient;
		
		public function __construct($registry, $rfClient){
			
			$this->config = $registry->get('config');
			$this->db = $registry->get('db');
			$this->log = $registry->get('log');
			$this->rfClient = $rfClient;
			
		}
		
		const CLASS_NAME = 'hobotix\\Amazon\\SimpleProductParser';
		
		private function createRequest($params = []){
			
			$data = [
			'api_key' 			=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' 	=> $this->config->get('config_rainforest_api_domain_1'),
			'customer_zipcode' 	=> $this->config->get('config_rainforest_api_zipcode_1'),
			'type' 				=> 'product',
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

		private static function reparseRnfBuggyArray($array, $index = false){
			if ($index){
				if (!empty($array[$index])){

					if (!empty($array[$index]['asin'])){
						$array[$index] = [$array[$index]];
						return $array;
					}

				}
			} else {
				if (!empty($array['asin'])){
					return [$array];
				}
			}

			return $array;
		}	
		
		private function parseResponse($response){
			$response = json_decode($response, true);	
			
			if (!isset($response['request_info']['success'])){
				var_dump($response);
				echoLine('[SimpleProductParser::parseResponse] Could not parse response, no success marker in it!', 'e');
				die();
				
			}
			
			if ($response['request_info']['success'] == false){
				echoLine('[SimpleProductParser::parseResponse] Success marker is false in response, returning false!', 'e');
				return false;
			}

			if (!empty($response['view_to_purchase'])){
				$response['product']['view_to_purchase'] = self::reparseRnfBuggyArray($response['view_to_purchase']);
			}

			if (!empty($response['also_viewed'])){
				$response['product']['also_viewed'] = self::reparseRnfBuggyArray($response['also_viewed']);
			}

			if (!empty($response['similar_to_consider'])){
				$response['product']['similar_to_consider'] = self::reparseRnfBuggyArray($response['similar_to_consider']);
			}

			if (!empty($response['compare_with_similar'])){
				$response['product']['compare_with_similar'] = self::reparseRnfBuggyArray($response['compare_with_similar']);
			}

			if (!empty($response['also_bought'])){
				$response['product']['also_bought'] = self::reparseRnfBuggyArray($response['also_bought']);
			}

			if (!empty($response['sponsored_products'])){
				$response['product']['sponsored_products'] = self::reparseRnfBuggyArray($response['sponsored_products']);
			}

			if (!empty($response['frequently_bought_together'])){
				$response['product']['frequently_bought_together'] = self::reparseRnfBuggyArray($response['frequently_bought_together'], 'products');
			}

			if (!empty($response['shop_by_look'])){
				$response['product']['shop_by_look'] = self::reparseRnfBuggyArray($response['shop_by_look'], 'items');
			}
			
			return $response['product'];			
		}
		
		public function getProductByGTIN($gtins){
		
			$multi = curl_multi_init();
			$channels 	= [];
			$results 	= [];
			
			foreach ($gtins as $gtin){								
				$channels[$gtin['product_id']] = $this->createRequest(['gtin' => $gtin['gtin']]);	
				$results[$gtin['product_id']] = [];
				curl_multi_add_handle($multi, $channels[$gtin['product_id']]);									
			}			
			
			$running = null;
			do {
				curl_multi_exec($multi, $running);
			} while ($running);
			
			foreach ($channels as $channel) {
				curl_multi_remove_handle($multi, $channel);
			}
			curl_multi_close($multi);
			
			foreach ($channels as $product_id => $channel) {
				$results[$product_id] = $this->parseResponse(curl_multi_getcontent($channel));				
			}
			
			
			return $results;			
		}
		
		public function getProductByASINS($asins){
			
			$multi = curl_multi_init();
			$channels 	= [];
			$results 	= [];
			
			foreach ($asins as $asin){								
				$channels[$asin['product_id']] = $this->createRequest(['asin' => $asin['asin']]);	
				$results[$asin['product_id']] = [];
				curl_multi_add_handle($multi, $channels[$asin['product_id']]);									
			}			
			
			$running = null;
			do {
				curl_multi_exec($multi, $running);
			} while ($running);
			
			foreach ($channels as $channel) {
				curl_multi_remove_handle($multi, $channel);
			}
			curl_multi_close($multi);
			
			foreach ($channels as $product_id => $channel) {
				$results[$product_id] = $this->parseResponse(curl_multi_getcontent($channel));				
			}
			
			
			return $results;		
		}
	}				
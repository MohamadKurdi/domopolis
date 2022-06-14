<?
	
	namespace hobotix\Amazon;
	
	class CategoryParser
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
		
		const CLASS_NAME = 'hobotix\\Amazon\\CategoryParser';
		
		private function doRequest($params = []){
		
			$data = [
			'api_key' => $this->config->get('config_rainforest_api_key'),
			'amazon_domain' => $this->config->get('config_rainforest_api_domain_1'),
			];
			
			$data = array_merge($data, $params);
			$queryString =  http_build_query($data);
		
		
			$ch = curl_init('https://api.rainforestapi.com/categories?' . $queryString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			
			$json = curl_exec($ch);		
			curl_close($ch);
			
			return json_decode($json, true);

		}
		
		public function getTopCategories(){
			
			return $this->doRequest([]);

		}

		
		public function getCategoryChildren($category_id){
			
			return $this->doRequest(['parent_id' => $category_id]);
	
		}

	}	
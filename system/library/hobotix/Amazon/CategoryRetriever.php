<?
	
	namespace hobotix\Amazon;
	
	class CategoryRetriever
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
		
		const CLASS_NAME = 'hobotix\\Amazon\\CategoryRetriever';


		public function getCategories(){
			$result = [];

			$query = $this->db->query("SELECT c.*, cd.name FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id)  WHERE cd.language_id = '" . $this->config->get('config_language_id') . "' AND c.amazon_sync_enable = 1 AND c.amazon_category_id > 0");

			foreach ($query->rows as $row){
				$result[] = [
					'category_id' 			=> $row['category_id'],
					'amazon_category_id' 	=> $row['amazon_category_id'],
					'name'					=> $row['name'],					
				];
			}

			return $result;
		}

		

















	}
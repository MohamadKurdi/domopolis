<?

/*

This class is one of the first classes and is used only for building category tree

*/

namespace hobotix\Amazon;

class CategoryParser
{
	private $type   = 'standard';
	private $table  = null;	

	const CLASS_NAME = 'hobotix\\Amazon\\CategoryParser';

	public function __construct($registry, $rfClient){
			
			$this->registry = $registry;
			$this->config = $registry->get('config');			
			$this->db = $registry->get('db');
			$this->log = $registry->get('log');
			$this->rfClient = $rfClient;
			
		}

	public function doRequest($params = [], $endpoint = 'categories'){
		
			$data = [
			'api_key' 			=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' 	=> $this->config->get('config_rainforest_api_domain_1'),
			'customer_zipcode' 	=> $this->config->get('config_rainforest_api_zipcode_1'),
			'type'				=> $this->type
			];
			
			$data = array_merge($data, $params);
			$queryString =  http_build_query($data);						
			
			$ch = curl_init('https://api.rainforestapi.com/' . $endpoint . '?' . $queryString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			
			$json = curl_exec($ch);		
			curl_close($ch);
			
			return json_decode($json, true);
		}

	public function setType($type){
		$this->type = $type;	
		$this->table = \hobotix\RainforestAmazon::categoryModeTables[$this->type];
		return $this;
	}

	public function getTopCategories(){
		$result = $this->doRequest();

		return $result;
	}


	public function getCategoryChildren($category_id){
		$result = $this->doRequest(['parent_id' => $category_id]);

		if (empty($result['categories'])){
			return false;
		}

		return $result['categories'];

	}

	public function clearTable(){

		$this->db->query("TRUNCATE " . $this->table);

	}

	public function updateFinalCategories(){
		$query = $this->db->query("SELECT DISTINCT(parent_id) as parent_id FROM " . $this->table);

		$parentIDList = [];
		foreach ($query->rows as $row){
			$parentIDList[] = $row['parent_id'];
		}

		$this->db->query("UPDATE " . $this->table . " SET final_category = 1 WHERE parent_id > 0 AND category_id NOT IN (SELECT DISTINCT(parent_id) as parent_id FROM " . $this->table . ")");
			
	}

	public function createCategory($data){
		if (empty($data['parent_id'])){
			$data['parent_id'] = 0;
		}

		$this->db->ncquery("INSERT IGNORE INTO " . $this->table . " SET 
			category_id 	= '" . $this->db->escape($data['id']) . "', 
			parent_id		= '" . $this->db->escape($data['parent_id']) . "',
			name 			= '" . $this->db->escape($data['name']) . "', 
			full_name 		= '" . $this->db->escape($data['path']) . "',
			link			= '" . $this->db->escape($data['link']) . "'
		");

		if ($this->config->get('config_rainforest_enable_auto_tree')){
						
		}
	}

	public function createTopCategoryFromSettings($categories){

		foreach ($categories as $category){
			$result = $this->doRequest(['type' => \hobotix\RainforestAmazon::rainforestTypeMapping[$this->type], 'category_id' => $category], 'request');

			var_dump($result);

			if (!empty($result[\hobotix\RainforestAmazon::categoryModeInfoIndexes[$this->type]]) && !empty($result[\hobotix\RainforestAmazon::categoryModeInfoIndexes[$this->type]]['current_category'])){
				$this->createCategory(
					[
						'id' 			=> $category,
						'parent_id'		=> 0,
						'name'			=> $result[\hobotix\RainforestAmazon::categoryModeInfoIndexes[$this->type]]['current_category']['name'],						
						'path'			=> $result[\hobotix\RainforestAmazon::categoryModeInfoIndexes[$this->type]]['current_category']['name'],
						'link'			=> $result[\hobotix\RainforestAmazon::categoryModeInfoIndexes[$this->type]]['current_category']['link']
					]
				);
			}				
		}


	}

	public function getTopCategoriesFromDataBase(){

		$query = $this->db->ncquery("SELECT * FROM " . $this->table . " WHERE parent_id = 0");

		return $query->rows;

	}

}	
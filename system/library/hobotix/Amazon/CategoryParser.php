<?

namespace hobotix\Amazon;

class CategoryParser
{

	private $db;	
	private $config;
	private $rfClient;

	private $type   = 'standard';
	private $table  = null;	

	public function __construct($registry, $rfClient){

		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->log = $registry->get('log');
		$this->rfClient = $rfClient;

		$this->table = \hobotix\RainforestAmazon::categoryModeTables[$this->type];
	}

	const CLASS_NAME = 'hobotix\\Amazon\\CategoryParser';

	private function doRequest($params = []){
		
		$data = [
			'api_key' 		=> $this->config->get('config_rainforest_api_key'),
			'amazon_domain' => $this->config->get('config_rainforest_api_domain_1'),
			'type'			=> $this->type
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

	public function setType($type){
		$this->type = $type;	
		$this->table = \hobotix\RainforestAmazon::categoryModeTables[$this->type];
		return $this;
	}

	public function getTopCategories(){

		return $this->doRequest([]);

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

		$this->db->query("UPDATE " . $this->table . " SET final_category = 1 WHERE category_id NOT IN (SELECT DISTINCT(parent_id) as parent_id FROM " . $this->table . ")");
			
	}

	public function createCategory($data){

		if (empty($data['parent_id'])){
			$data['parent_id'] = 0;
		}

		$this->db->ncquery("INSERT IGNORE INTO " . $this->table . " SET 
			category_id = '" . $this->db->escape($data['id']) . "', 
			parent_id	= '" . $this->db->escape($data['parent_id']) . "',
			name = '" . $this->db->escape($data['name']) . "', 
			full_name = '" . $this->db->escape($data['path']) . "'");

	}

	public function getTopCategoriesFromDataBase(){

		$query = $this->db->ncquery("SELECT * FROM " . $this->table . " WHERE parent_id = 0");

		return $query->rows;

	}

}	
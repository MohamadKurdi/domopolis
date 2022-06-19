<?

/*

This class is one of the first classes and is used only for building category tree

*/

namespace hobotix\Amazon;

class CategoryParser
{
	private $type   = 'standard';
	private $table  = null;	

	public $model_catalog_category 		= null;
	public $model_localisation_language = null;
	public $yandexTranslator			= null;

	const CLASS_NAME = 'hobotix\\Amazon\\CategoryParser';

	public function __construct($registry, $rfClient){
			
			$this->registry = $registry;
			$this->config 	= $registry->get('config');			
			$this->db 		= $registry->get('db');
			$this->log 		= $registry->get('log');
			$this->rfClient = $rfClient;

			require_once(DIR_APPLICATION . '../admin/model/catalog/category.php');
			$this->model_catalog_category = new \ModelCatalogCategory($this->registry);

			if ($this->config->get('config_rainforest_enable_translation')){
				require_once(DIR_SYSTEM . 'library/hobotix/YandexTranslator.php');
				$this->yandexTranslator = new \hobotix\YandexTranslator($this->registry);
			}

			
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

	private function getLanguages(){
		$query = $this->db->query("SELECT * FROM language ORDER BY sort_order, name");
		return $query->rows;
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
		$this->db->query("UPDATE " . $this->table . " SET final_category = 1 WHERE parent_id != '0' AND category_id NOT IN (SELECT DISTINCT(parent_id) as parent_id FROM " . $this->table . ")");			
	}

	public function checkIfCategoryExists($amazon_category_id){
		$query = $this->db->ncquery("SELECT * FROM category WHERE amazon_category_id = '" . $this->db->escape($amazon_category_id) . "'");

		if ($query->num_rows){
			return $query->row['category_id'];
		} else {
			return false;
		}
	}

	public function rebuildAmazonTreeToStoreTree(){

		//Дерево
		$this->db->query("UPDATE category c1 SET c1.parent_id = (SELECT category_id FROM category c2 WHERE NOT ISNULL(c2.amazon_category_id) AND c2.amazon_category_id <> '' AND c2.amazon_category_id = c1.amazon_parent_category_id) WHERE parent_id = 0");

		//Фикс null-родителя
		$this->db->query("UPDATE category SET parent_id = 0 WHERE ISNULL(parent_id)");

		//Синхронизация финальных категорий
		$this->db->query("UPDATE category SET amazon_final_category = 1 WHERE amazon_category_id IN (SELECT category_id FROM " . $this->table . " WHERE final_category = 1)");

		//Синхронизация названий родительских категорий
		$this->db->query("UPDATE category SET amazon_parent_category_name = (SELECT name FROM " . $this->table . " WHERE category_id = category.amazon_parent_category_id LIMIT 1)");

		//Синхронизация ссылок категорий
		$this->db->query("UPDATE category SET amazon_category_link = (SELECT link FROM " . $this->table . " WHERE category_id = category.amazon_category_id LIMIT 1)");

	
	}

	public function addSimpleCategory($data) {
		$this->db->query("INSERT INTO category SET 

			parent_id 				= 0, 			
			top 					= 0, 
			status 					= 1, 		
			submenu_in_children 	= 1, 
			amazon_sync_enable 		= 0,		
			amazon_category_id 		= '" . $this->db->escape($data['amazon_category_id']) . "', 
			amazon_category_name 	= '" . $this->db->escape($data['amazon_category_name']) . "', 
			amazon_parent_category_id 		= '" . $this->db->escape($data['amazon_parent_category_id']) . "',
			date_modified 			= NOW(), 
			date_added 				= NOW()");
		
		$category_id = $this->db->getLastId();

		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
	
		$level = 0;
		$this->db->query("INSERT INTO `category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '0'");
		
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}	
		
		return $category_id;
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
			ON DUPLICATE KEY UPDATE
			link			= '" . $this->db->escape($data['link']) . "'
		");

		if ($this->config->get('config_rainforest_enable_auto_tree')){

			if (!$this->checkIfCategoryExists($data['id'])){
				echoLine('[CategoryParser] Категория ' . $data['id'] . ' не существует');

				if (in_array($data['id'], prepareEOLArray($this->config->get('config_rainforest_root_categories')))){
					echoLine('[CategoryParser] Это корневая категория, ее не добавляем: ' . $data['id']);
				} else {
					if (in_array($data['parent_id'], prepareEOLArray($this->config->get('config_rainforest_root_categories')))){
						echoLine('[CategoryParser] Это категория первого уровня на Amazon, у нас - корневая, ' . $data['id']);
						$data['parent_id'] = 0;						
					}

					$category_data = [ 				
						'amazon_category_id' 		=> $this->db->escape($data['id']), 
						'amazon_category_name' 		=> $this->db->escape($data['path']),
						'amazon_parent_category_id' => $data['parent_id'],
						'category_store'			=> ['0']
					];

					$category_description = [];
					foreach ($this->getLanguages() as $language){

						//Дефолтный язык всегда в приоритете
						if ($language['code'] == $this->config->get('config_rainforest_source_language')){
							$name = $data['name'];
						} else {
							if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
								$name = $this->yandexTranslator->translate($data['name'], $this->config->get('config_rainforest_source_language'), $language['code'], true);
								echoLine('[CategoryParser] Переводчик: ' . $data['name'] . ' -> ' . $name);
							} else {
								$name = $data['name'];
							}
						}

						$category_description[ $language['language_id'] ] = [
							'name' => $name
						];
					}

					$category_data['category_description'] = $category_description;

					$this->addSimpleCategory($category_data);					
				}

			} else {
				echoLine('[CategoryParser] Категория ' . $data['id'] . ' существует');				
			}
		}
	}

	public function createTopCategoryFromSettings($categories){

		foreach ($categories as $category){
			$result = $this->doRequest(['type' => \hobotix\RainforestAmazon::rainforestTypeMapping[$this->type], 'category_id' => $category], 'request');		

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
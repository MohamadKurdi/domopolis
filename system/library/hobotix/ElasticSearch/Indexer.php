<?php
namespace hobotix\ElasticSearch;

class Indexer{
	private $elasticConnection     	= null;

	private $db         = null;
	private $log        = null;
	private $cache      = null;
	private $config     = null;
	private $registry 	= null;


	public function __construct($registry, $elasticConnection){
		$this->registry	= $registry;
		
		$this->db 		= $registry->get('db');
		$this->cache 	= $registry->get('cache');
		$this->config 	= $registry->get('config');
		$this->log 		= $registry->get('log');		
		$this->request 	= $registry->get('request');

		$this->elasticConnection = $elasticConnection;
	}


		public function makeTextNumbers(&$params){
			$tmp = $params;
			
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){				
				if (!empty($tmp['body']['names_' . $language_code])){
					foreach ($tmp['body']['names_' . $language_code] as $tmp_name){
						for ($i=100; $i>=1; $i--){
							if (strpos($tmp_name, ' ' . $i . ' ') !== false){								
								foreach (ElasticSearch\StaticFunctions::transformNumber($i, $language_code) as $numberCases){		
									$new_name = str_replace(' ' . $i . ' ', ' ' . $numberCases . ' ', $tmp_name);

									$params['body']['names_' . $language_code][] = $new_name;
									echoLine($tmp_name . ' -> ' . $new_name, 'i');
									if ($language_code == 'uk'){
										$params['body']['names_' . $language_code][] = str_replace("'", '', $new_name);
										echoLine($tmp_name . ' -> ' . str_replace("'", '', $new_name));
									}
								}
							}					
						}
					}
				}
			}			
		}
		
		public function prepareProductManufacturerIndex($namequery, $manquery, &$params){
			$mapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
			
			$manmapping = ElasticSearch\StaticFunctions::createMappingToIDS($manquery, 'language_id', 'name');
			$altmapping = ElasticSearch\StaticFunctions::createMappingToIDS($manquery, 'language_id', 'alternate_name');			
			ElasticSearch\StaticFunctions::remapAlternateMapping($altmapping);
			
			
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){
				if (!empty($mapping[$language_id]) && !empty($manmapping[$language_id]) && !empty($altmapping[$language_id])){
					foreach ($altmapping[$language_id] as $altManufacturer){
						
						$reparsedName = $mapping[$language_id];
						
						if (strpos($mapping[$language_id], $manmapping[$language_id]) !== false){
							$reparsedName = str_replace($manmapping[$language_id], $altManufacturer, $mapping[$language_id]);			
							} else {
							$reparsedName = $mapping[$language_id] . ' ' . $altManufacturer;
						}
						
						if ($language_id == 5) {
							$params['body']['names_' . $language_code][] = $reparsedName;
							$params['body']['names_' . 'uk'][] = $reparsedName;
							} else {
							$params['body']['names_' . $language_code][] = $reparsedName;
						}						
					}
				}
			}
		}
		
		public function prepareProductManufacturerCollectionIndex($namequery, $manquery, $colquery, &$params){
			$mapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
			
			$manmapping = ElasticSearch\StaticFunctions::createMappingToIDS($manquery, 'language_id', 'name');
			$altmapping = ElasticSearch\StaticFunctions::createMappingToIDS($manquery, 'language_id', 'alternate_name');			
			ElasticSearch\StaticFunctions::remapAlternateMapping($altmapping);
			
			$colmapping = ElasticSearch\StaticFunctions::createMappingToIDS($colquery, 'language_id', 'name');
			$altcolmapping = ElasticSearch\StaticFunctions::createMappingToIDS($colquery, 'language_id', 'alternate_name');			
			ElasticSearch\StaticFunctions::remapAlternateMapping($altcolmapping);
			
			
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){
				if (!empty($mapping[$language_id]) && !empty($manmapping[$language_id]) && !empty($altmapping[$language_id]) && !empty($colmapping[$language_id]) && !empty($altcolmapping[$language_id])){
					foreach ($altmapping[$language_id] as $altManufacturer){
						
						foreach ($altcolmapping[$language_id] as $altCollection){
							
							$reparsedName = $mapping[$language_id];
							
							if (strpos($mapping[$language_id], $manmapping[$language_id]) !== false){
								$reparsedName = str_replace($manmapping[$language_id], $altManufacturer, $mapping[$language_id]);			
								} else {
								$reparsedName = $mapping[$language_id] . ' ' . $altManufacturer;
							}
							
							if (strpos($mapping[$language_id], $colmapping[$language_id]) !== false){
								$reparsedName = str_replace($colmapping[$language_id], $altCollection, $reparsedName);			
								} else {
								$reparsedName = $mapping[$language_id] . ' ' . $reparsedName;
							}
							
							if ($language_id == 5) {
								$params['body']['names_' . $language_code][] = $reparsedName;
								$params['body']['names_' . 'uk'][] = $reparsedName;
								} else {
								$params['body']['names_' . $language_code][] = $reparsedName;
							}
						}
					}
				}
			}
		}
		
		public function prepareProductIndex($namequery, &$params){
			$mapping 	= ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
				
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){
				if (!empty($mapping[$language_id])){
					if ($language_id == 6) {
						if (empty($mapping[5])){
							$mapping[5] = $mapping[6];
						}					
						
						$params['body']['name_' . $language_code] = array_values(array_unique(array_merge(array($mapping[$language_id]), array($mapping[5]))));	
						$params['body']['names_' . $language_code] = array_values(array_unique(array_merge(array($mapping[$language_id]), array($mapping[5]))));					
						
						} else {

						$params['body']['name_' . $language_code] = $mapping[$language_id];
						$params['body']['names_' . $language_code] = [$mapping[$language_id]];
					}
				}
			}	
		}
		
		public function prepareCategoryManufacturerIndex($namequery, $idc, $idm, $idcc, &$params){
			$mapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
			$altmapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'alternate_name');			
			ElasticSearch\StaticFunctions::remapAlternateMapping($altmapping);
			
			$params['body']['category_id'] = $idc;
			$params['body']['manufacturer_id'] = $idm;
			$params['body']['collection_id'] = $idcc;
			
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){
				if (!empty($mapping[$language_id])){
					$params['body']['name_' . $language_code] = $mapping[$language_id];
					
					if ($language_id == 6) {
						if (empty($mapping[5])){
							$mapping[5] = $mapping[6];
						}
						
						if (empty($altmapping[5])){
							$altmapping[5] = $altmapping[6];
						}
						
						$params['body']['names_' . $language_code] = array_values(array_unique(array_merge(array($mapping[$language_id]), array($mapping[5]), $altmapping[$language_id], $altmapping[5])));
						} else {
						$params['body']['names_' . $language_code] = array_values(array_unique(array_merge(array($mapping[$language_id]), $altmapping[$language_id])));
					}
					
					if (!empty($params['body']['name_' . $language_code])){
						$params['body']['suggest_' . $language_code] = $params['body']['name_' . $language_code];
					}
				}
			}	
		}
		
		public function prepareCategoryManufacturerInterSectionIndex($namequery, $namequery2, &$params, $collectionLogic = false){
			$manufacturerMapping 	= ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
			$manufacturerAltMapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery, 'language_id', 'alternate_name');
			ElasticSearch\StaticFunctions::remapAlternateMapping($manufacturerAltMapping);
			
			$categoryMapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery2, 'language_id', 'name');
			$categoryAltMapping = ElasticSearch\StaticFunctions::createMappingToIDS($namequery2, 'language_id', 'alternate_name');
			ElasticSearch\StaticFunctions::remapAlternateMapping($categoryAltMapping);		
			
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){
				if (!empty($manufacturerMapping[$language_id]) && !empty($categoryMapping[$language_id])){									
					
					$categoryMapping[$language_id] = trim(str_replace($manufacturerMapping[$language_id], '', $categoryMapping[$language_id]));
					
					echoLine('>> Категория ' . $categoryMapping[$language_id]);
					
					if (!$collectionLogic && $language_id == 6){
						
						if (empty($categoryMapping[5])){
							$categoryMapping[5] = !empty($categoryMapping[2])?$categoryMapping[2]:$categoryMapping[6];
						}
						
						if (empty($manufacturerMapping[5])){
							$manufacturerMapping[2] = !empty($manufacturerMapping[2])?$manufacturerMapping[2]:$manufacturerMapping[6];
						}
						
						$categoryMapping[5] = trim(str_replace($manufacturerMapping[5], '', $categoryMapping[5]));
						
						$params['body']['name_' . $language_code] = array_values(array_unique([trim($categoryMapping[$language_id] . ' ' . $manufacturerMapping[$language_id]), trim($categoryMapping[5] . ' ' . $manufacturerMapping[5])]));
						
						} else {
						$params['body']['name_' . $language_code] = trim($categoryMapping[$language_id] . ' ' . $manufacturerMapping[$language_id]);
					}
				}															
				
				if (!empty($categoryAltMapping[$language_id])){
					foreach ($categoryAltMapping[$language_id] as $categoryAlt){														
						
						if (!empty($manufacturerMapping[$language_id]) && trim($categoryAlt)){								
							if ($language_id == 5){									
								$params['body']['names_' . $language_code][] = trim($categoryAlt . ' ' . $manufacturerMapping[$language_id]);
								$params['body']['names_' . 'uk'][] = trim($categoryAlt . ' ' . $manufacturerMapping[$language_id]);									
								} else {
								$params['body']['names_' . $language_code][] = trim($categoryAlt . ' ' . $manufacturerMapping[$language_id]);								
							}
						}
					}
					unset($categoryAlt);
				}	
				
				if (!empty($manufacturerAltMapping[$language_id]) && !empty($categoryMapping[$language_id])){
					
					foreach ($manufacturerAltMapping[$language_id] as $manufacturerAlt){
						if (trim($manufacturerAlt)){						
							if (!$collectionLogic && $language_id == 5){									
								$params['body']['names_' . $language_code][] = trim($categoryMapping[$language_id] . ' ' . $manufacturerAlt);
								$params['body']['names_' . 'uk'][] = trim($categoryMapping[$language_id] . ' ' . $manufacturerAlt);								
								} else {
								$params['body']['names_' . $language_code][] = trim($categoryMapping[$language_id] . ' ' . $manufacturerAlt);								
							}
							
							if (!empty($categoryAltMapping[$language_id])){
								foreach ($categoryAltMapping[$language_id] as $categoryAlt){																			
									if (trim($categoryAlt)){											
										if ($language_id == 5){									
											$params['body']['names_' . $language_code][] = trim($categoryAlt . ' ' . $manufacturerAlt);
											$params['body']['names_' . 'uk'][] = trim($categoryAlt . ' ' . $manufacturerAlt);									
											} else {
											$params['body']['names_' . $language_code][] = trim($categoryAlt . ' ' . $manufacturerAlt);								
										}
									}
								}
							}	
						}
					}												
				}
				
				if (!empty($params['body']['name_' . $language_code])){
					$params['body']['suggest_' . $language_code] = $params['body']['name_' . $language_code];
				}
			}
		}
		

		
		
		public function createStoreIndexArray($mapping, $index, &$params){			
			$params['body'][$index] = [];
			
			foreach ($this->registry->get('stores_to_main_language_mapping') as $store_id => $language_code){
				if (!empty($mapping[$store_id])){
					$params['body'][$index][] = $store_id;
				}
			}
		}			


		public function deleteIndexes(){
			$deleteParams = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix')
			];
			$response = $this->check()->indices()->delete($deleteParams);

			$deleteParams = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix')
			];
			$response = $this->check()->indices()->delete($deleteParams);
		}			
		
		public function recreateIndices(){			
			if(php_sapi_name()!=="cli"){
				die('cli only');	
			}	
			
			try{
				$deleteParams = [
				'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix')
				];
				$response = $this->check()->indices()->delete($deleteParams);
			} catch (\Exception $e){
				echoLine($e->getMessage());
			}
			
			$createParams = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
			'body'  => [
			'settings' => [ 'analysis' => [ 'filter' => [
			'ru_stop' => [ 'type' => 'stop', 'stopwords' => '_russian_' ],
			'en_stop' => [ 'type' => 'stop', 'stopwords' => '_english_' ],
			'ua_stop' => [ 'type' => 'stop', 'stopwords' => '_ukrainian_' ],
			'ru_stemmer' => [ 'type' => 'stemmer', 'language' => 'russian' ],
			'en_stemmer' => [ 'type' => 'stemmer', 'language' => 'english' ],
			'ua_stemmer' => [ 'type' => 'stemmer', 'language' => 'russian' ],
			'phonemas'   => [ 'type' => 'phonetic', 'replace' => 'false', 'encoder' => 'refined_soundex']
			],
			'char_filter' => [ 'ru_en_key' => [
			'type' => 'mapping', 'mappings' => [
			'a => ф','b => и','c => с','d => в','e => у','f => а','g => п','h => р','i => ш','j => о','k => л','l => д','m => ь','n => т','o => щ','p => з','r => к','s => ы','t => е','u => г','v => м','w => ц','x => ч','y => н','z => я','A => Ф','B => И','C => С','D => В','E => У','F => А','G => П','H => Р','I => Ш','J => О','K => Л','L => Д','M => Ь','N => Т','O => Щ','P => З','R => К','S => Ы','T => Е','U => Г','V => М','W => Ц','X => Ч','Y => Н','Z => Я','[ => х','] => ъ','; => ж','< => б','> => ю'
            ] ] ],
			'analyzer' => [
			'russian' 	=> [ 'char_filter' => [ 'html_strip', 'ru_en_key' ], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ru_stop', 'en_stop', 'ru_stemmer', 'en_stemmer', 'phonemas'] ],
			'ukrainian' => [ 'char_filter' => [ 'html_strip', 'ru_en_key' ], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ua_stop', 'en_stop', 'ua_stemmer', 'en_stemmer', 'phonemas'] ],
			'integer' 	=> [ 'char_filter' => [ 'html_strip' ], 'tokenizer' => 'standard']
			] ] ],
			'mappings' 	=> [ 'properties' => [
			'category_id' 		=> [ 'type' => 'integer', 'index' => 'true' ],
			'manufacturer_id' 	=> [ 'type' => 'integer', 'index' => 'true' ],
			'collection_id' 	=> [ 'type' => 'integer', 'index' => 'true' ],
			'priority'			=> [ 'type' => 'integer', 'index' => 'true' ],
			'type'		=> [ 'type' => 'text', 'index' => 'true' ],
			'name_ru' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ], 
			'name_kz' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'name_by' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'name_ua' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'name_uk' 	=> [ 'type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true' ],
			'suggest_ru' 	=> [ 'type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'russian', 'index' => 'true', 'contexts' => [[ 'name' => 'suggest-priority', 'type' => 'category', 'path' => 'type'  ]] ],
			'suggest_kz' 	=> [ 'type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'russian', 'index' => 'true', 'contexts' => [[ 'name' => 'suggest-priority', 'type' => 'category', 'path' => 'type'  ]] ],
			'suggest_by' 	=> [ 'type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'russian', 'index' => 'true', 'contexts' => [[ 'name' => 'suggest-priority', 'type' => 'category', 'path' => 'type'  ]] ],
			'suggest_ua' 	=> [ 'type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'russian', 'index' => 'true', 'contexts' => [[ 'name' => 'suggest-priority', 'type' => 'category', 'path' => 'type'  ]] ],
			'suggest_uk' 	=> [ 'type' => 'completion', 'preserve_separators' => 'false', 'preserve_position_increments' => 'false', 'analyzer' => 'ukrainian', 'index' => 'true', 'contexts' => [[ 'name' => 'suggest-priority', 'type' => 'category', 'path' => 'type'  ]] ],
			'names_ru' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ], 
			'names_kz' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'names_by' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'names_ua' 	=> [ 'type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true' ],  
			'names_uk' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ], 
			'stores'  	=> [ 'type' => 'integer', 'index' => 'true' ]
			] ]
			] ];
			
			try{
				$response =  $this->check()->indices()->create($createParams);	
			} catch (\Exception $e){
				echoLine($e->getMessage());
			}	
			
			try{
				$deleteParams = [
					'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix')
				];
			//	$response = $this->check()->indices()->delete($deleteParams);
			} catch (\Exception $e){
				echoLine($e->getMessage());
			}
			
			$createParams = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
			'body'  => [
			'settings' => [ 'analysis' => [ 'filter' => [
			'ru_stop' => [ 'type' => 'stop', 'stopwords' => '_russian_' ],
			'en_stop' => [ 'type' => 'stop', 'stopwords' => '_english_' ],
			'de_stop' => [ 'type' => 'stop', 'stopwords' => '_german_' ],
			'ua_stop' => [ 'type' => 'stop', 'stopwords' => '_ukrainian_' ],
			'ru_stemmer' => [ 'type' => 'stemmer', 'language' => 'russian' ],
			'en_stemmer' => [ 'type' => 'stemmer', 'language' => 'english' ],
			'de_stemmer' => [ 'type' => 'stemmer', 'language' => 'german' ],
			'ua_stemmer' => [ 'type' => 'stemmer', 'language' => 'russian' ],
			'phonemas'   => [ 'type' => 'phonetic', 'replace' => 'false', 'encoder' => 'refined_soundex']
			],
			'char_filter' => [ 'ru_en_key' => [
			'type' => 'mapping', 'mappings' => [
			'a => ф','b => и','c => с','d => в','e => у','f => а','g => п','h => р','i => ш','j => о','k => л','l => д','m => ь','n => т','o => щ','p => з','r => к','s => ы','t => е','u => г','v => м','w => ц','x => ч','y => н','z => я','A => Ф','B => И','C => С','D => В','E => У','F => А','G => П','H => Р','I => Ш','J => О','K => Л','L => Д','M => Ь','N => Т','O => Щ','P => З','R => К','S => Ы','T => Е','U => Г','V => М','W => Ц','X => Ч','Y => Н','Z => Я','[ => х','] => ъ','; => ж','< => б','> => ю'
            ] ] ],
			'analyzer' => [
			'russian' 		=> [ 'char_filter' => [ 'html_strip', 'ru_en_key' ], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ru_stop', 'en_stop', 'ru_stemmer', 'en_stemmer', 'phonemas'] ],
			'ukrainian' 	=> [ 'char_filter' => [ 'html_strip', 'ru_en_key' ], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'ua_stop', 'en_stop', 'ua_stemmer', 'en_stemmer', 'phonemas'] ],
			'deutch' 		=> [ 'char_filter' => [ 'html_strip' ], 'tokenizer' => 'standard', 'filter' => ['lowercase', 'de_stop', 'de_stemmer', 'phonemas'] ],
			'integer' 		=> [ 'char_filter' => [ 'html_strip' ], 'tokenizer' => 'standard'],
			'identifier' 	=> [ 'char_filter' => [ 'html_strip' ], 'tokenizer' => 'standard', 'filter' => ['lowercase']]
			] ] ],
			'mappings' 	=> [ 'properties' => [
			'category_id' 		=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'manufacturer_id' 	=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'product_id' 		=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'collection_id' 	=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'priority'			=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'sort_order'		=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'viewed'			=> [ 'type' => 'integer', 'index' => 'true', 'fielddata' => 'true' ],
			'price'				=> [ 'type' => 'float', 'index' => 'true' ],
			'bought_for_week'	=> [ 'type' => 'integer', 'index' => 'true' ],
			'bought_for_month'	=> [ 'type' => 'integer', 'index' => 'true' ],
			'name_ru' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ], 
			'name_kz' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'name_by' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'name_ua' 			=> [ 'type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true' ],  
			'name_uk' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],
			'names_ru' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ], 
			'names_kz' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'names_by' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'names_ua' 			=> [ 'type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true' ],  
			'names_uk' 			=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],
			'names_de' 			=> [ 'type' => 'text', 'analyzer' => 'deutch', 'index' => 'true' ],			
			'identifier'		=> [ 'type' => 'text', 'analyzer' => 'identifier', 'index' => 'true' ],
			'stock_status_id'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'status'			=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity'			=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity_stock'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			$this->config->get('config_warehouse_identifier')		=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			$this->config->get('config_warehouse_identifier_local')	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'stores'  			=> [ 'type' => 'integer', 'index' => 'true' ],
			'categories'  		=> [ 'type' => 'integer', 'index' => 'true' ],
			] ]
			] ];
			
			try {
				$response = $this->check()->indices()->create($createParams);		
			} catch (\Exception $e){
				echoLine($e->getMessage());
			}
			
			return $this;			
		}
		
		public function indexer(){
			$query = $this->db->query("SELECT category_id FROM category WHERE status = 1");
			$i = 1;
			foreach ($query->rows as $category_id){							
				$params = [];
				$params['index'] 			= 'categories' . $this->config->get('config_elasticsearch_index_suffix');
				$params['id'] 				= $i;$i++;				
				$params['body'] 			= [];
				$params['body']['priority'] = ElasticSearch::CATEGORY_PRIORITY;
				$params['body']['type']		= 'category';

				$namequery = $this->db->query("SELECT name as name, alternate_name as alternate_name, language_id FROM category_description WHERE category_id = '" . (int)$category_id['category_id'] . "'");						
				$this->prepareCategoryManufacturerIndex($namequery, $category_id['category_id'], 0, 0, $params);		

				echoLine('[elastic] Категория ' . $category_id['category_id']);

				$storequery = $this->db->query("SELECT store_id FROM category_to_store WHERE category_id = '" . (int)$category_id['category_id'] . "'");
				$mapping = ElasticSearch\StaticFunctions::createMappingToNonEmpty($storequery, 'store_id');
				$this->createStoreIndexArray($mapping, 'stores', $params);
				
				$response = $this->check()->index($params);
			}
						
			$query = $this->db->query("SELECT manufacturer_id FROM manufacturer WHERE 1");
			foreach ($query->rows as $manufacturer_id){					
				
				$params = [];
				$params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
				$params['id'] = $i;$i++;			
				$params['body'] = [];
				$params['body']['priority'] = ElasticSearch::MANUFACTURER_PRIORITY;
				$params['body']['type']	= 'manufacturer';

				$namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");						
				$this->prepareCategoryManufacturerIndex($namequery, 0, $manufacturer_id['manufacturer_id'], 0, $params);	

				echoLine('[elastic] Бренд ' . $manufacturer_id['manufacturer_id']);			
				
				$storequery = $this->db->query("SELECT store_id FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");
				$mapping = ElasticSearch\StaticFunctions::createMappingToNonEmpty($storequery, 'store_id');
				$this->createStoreIndexArray($mapping, 'stores', $params);								
				
				$response = $this->check()->index($params);
			}
			
			unset($manufacturer_id);			
			foreach ($query->rows as $manufacturer_id){	
				
				echoLine('Категории производитель ' . $manufacturer_id['manufacturer_id']);		
				
				$namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");
				
				$namequery2 = $this->db->query("SELECT DISTINCT c.category_id FROM category c LEFT JOIN keyworder k ON (k.category_id = c.category_id) LEFT JOIN keyworder_description kd ON (k.keyworder_id = kd.keyworder_id) WHERE k.manufacturer_id = '" . $manufacturer_id['manufacturer_id'] . "' AND c.status = 1 AND kd.keyworder_status = 1");
				
				if ($namequery2->num_rows){	
					
					foreach ($namequery2->rows as $row){
						$params = [];
						$params['index'] 					= 'categories' . $this->config->get('config_elasticsearch_index_suffix');
						$params['id'] 						= $i;$i++;
						$params['body'] 					= [];
						$params['body']['priority'] 		= ElasticSearch::KEYWORDER_PRIORITY;
						$params['body']['category_id'] 		= $row['category_id'];
						$params['body']['manufacturer_id'] 	= $manufacturer_id['manufacturer_id'];
						$params['body']['collection_id'] 	= 0;
						$params['body']['type']				= 'keyworder';
						
						
						$namequery3 = $this->db->query("SELECT name as name, alternate_name as alternate_name, language_id FROM category_description WHERE category_id = '" . (int)$row['category_id'] . "'");
						
						$this->prepareCategoryManufacturerInterSectionIndex($namequery, $namequery3, $params);					
						$storequery = $this->db->query("SELECT store_id FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "' AND store_id IN (SELECT store_id FROM category_to_store WHERE category_id = '" . $namequery2->row['category_id'] . "')");
						$mapping = ElasticSearch\StaticFunctions::createMappingToNonEmpty($storequery, 'store_id');
						$this->createStoreIndexArray($mapping, 'stores', $params);	
						
						$response = $this->check()->index($params);
					}
				}
			}
			
			unset($manufacturer_id);			
			foreach ($query->rows as $manufacturer_id){				
				
				echoLine('Коллекции производитель ' . $manufacturer_id['manufacturer_id']);		
				
				$namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");
				
				$namequery2 = $this->db->query("SELECT DISTINCT c.collection_id FROM collection c WHERE c.manufacturer_id = '" . $manufacturer_id['manufacturer_id'] . "'");
				
				if ($namequery2->num_rows){	
					foreach ($namequery2->rows as $row){
						$params = [];
						$params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
						$params['id'] = $i;$i++;
						$params['body'] = [];
						$params['body']['priority'] = ElasticSearch::COLLECTION_PRIORITY;
						$params['body']['category_id'] = 0;
						$params['body']['manufacturer_id'] = $manufacturer_id['manufacturer_id'];
						$params['body']['collection_id'] = $row['collection_id'];
						$params['body']['type']	= 'collection';
						
						
						$namequery3 = $this->db->query("SELECT c.name as name, cd.alternate_name as alternate_name, cd.language_id FROM collection_description cd LEFT JOIN collection c ON (cd.collection_id = c.collection_id) WHERE cd.collection_id = '" . (int)$row['collection_id'] . "'");
						
						ElasticSearch::prepareCategoryManufacturerInterSectionIndex($namequery, $namequery3, $params, true);					
						
						//Привязка к магазинам
						$storequery = $this->db->query("SELECT store_id FROM collection_to_store WHERE collection_id = '" . $namequery2->row['collection_id'] . "'");
						$mapping = ElasticSearch\StaticFunctions::createMappingToNonEmpty($storequery, 'store_id');
						$this->createStoreIndexArray($mapping, 'stores', $params);	
						
						$response = $this->check()->index($params);
					}
				}
			}
			
			
			$params = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
			'id'    => $i-1
			];
			
			
			$response = $this->check()->get($params);
			print_r($response);
			
			return $this;			
		}

		public function deleteproduct($product_id){
			$params = [
				'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
				'id'    => $product_id
			];	

			if ($this->check()){				
				try {
					$response = $this->check()->delete($params);
				} catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e){
					echoLine('[Elasticsearch::deleteproduct] Product ' . $product['product_id'] . ' does not exist', 'w');
				}
			}
		}
		
		private function indexproduct($product){
			$params = [];
			$params['index'] 			= 'products' . $this->config->get('config_elasticsearch_index_suffix');
			$params['id'] 				= $product['product_id'];				
			$params['body'] 			= [];
			$params['body']['priority'] = self::CATEGORY_PRIORITY;

			$params['body']['manufacturer_id'] 	= $product['manufacturer_id'];
			$params['body']['product_id'] 		= $product['product_id'];
			$params['body']['collection_id'] 	= $product['collection_id'];
			$params['body']['stock_status_id'] 	= $product['stock_status_id'];
			$params['body']['status']  			= $product['status'];
			$params['body']['sort_order']  		= $product['sort_order'];
			$params['body']['viewed']  			= $product['viewed'];
			$params['body']['price']  			= $product['price'];
			$params['body']['bought_for_week'] 	= $product['bought_for_week'];
			$params['body']['bought_for_month'] = $product['bought_for_month'];
			$params['body']['quantity'] 		= $product['quantity'];
			$params['body']['quantity_stock'] 	= $product['quantity_stock'];

			$params['body'][$this->config->get('config_warehouse_identifier')]  		= $product[$this->config->get('config_warehouse_identifier')];
			$params['body'][$this->config->get('config_warehouse_identifier_local')]  	= $product[$this->config->get('config_warehouse_identifier_local')];

			if ($this->config->get('config_elasticseach_many_models')){
				$models = ElasticSearch\StaticFunctions::makeIdentifiersArray($product['model']);
			} else {
				$models = [$product['model']];
			}

			if ($this->config->get('config_elasticseach_many_skus')){
				$skus = ElasticSearch\StaticFunctions::makeIdentifiersArray($product['sku']);
			} else {
				$skus = [$product['sku']];
			}

			$params['body']['identifier'] = array_values(array_unique(array_merge([$product['product_id'], $product['asin'], $product['ean']], $models, $skus)));

			$namequery = $this->db->query("SELECT name as name, language_id FROM product_description WHERE product_id = '" . (int)$product['product_id'] . "'");		
			$this->prepareProductIndex($namequery, $params);	

			$manquery = $this->db->query("SELECT m.name, md.alternate_name as alternate_name, md.language_id FROM manufacturer_description md LEFT JOIN manufacturer m ON (m.manufacturer_id = md.manufacturer_id) WHERE md.manufacturer_id = '" . (int)$product['manufacturer_id'] . "'");
			$this->prepareProductManufacturerIndex($namequery, $manquery, $params);

			$colquery = $this->db->query("SELECT c.name as name, cd.alternate_name as alternate_name, cd.language_id FROM collection_description cd LEFT JOIN collection c ON (c.collection_id = cd.collection_id) WHERE cd.collection_id = '" . (int)$product['collection_id'] . "'");
			$this->prepareProductManufacturerIndex($namequery, $colquery, $params);
			$this->prepareProductManufacturerCollectionIndex($namequery, $manquery, $colquery, $params);

			if ($this->config->get('config_elasticseach_many_textnumbers')){
				$this->makeTextNumbers($params);
			}

			echoLine('[Elasticsearch::indexproduct] Indexing product ' . $product['product_id'], 'i');

			$category_query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product['product_id'] . "'");
			$mapping = ElasticSearch\StaticFunctions::createMappingToNonEmpty($category_query, 'category_id');
			StaticFunctions::createIndexArray($mapping, 'categories', $params);		

			$store_query = $this->db->query("SELECT store_id FROM product_to_store WHERE product_id = '" . (int)$product['product_id'] . "'");
			$mapping = ElasticSearch\StaticFunctions::createMappingToNonEmpty($store_query, 'store_id');
			self::createStoreIndexArray($mapping, 'stores', $params);		


			$response = $this->check()->index($params);
		}

		public function reindexproduct($product_id){
			$query = $this->db->query("SELECT * FROM product WHERE product_id = '" . (int)$product_id . "'");

			if ($query->num_rows){
				$this->indexproduct($query->row);
				$this->getProductByID($product_id);
			}
		}
		
		public function productsindexer(){			
			$query = $this->db->query("SELECT * FROM product WHERE status = 1 AND (price > 0 OR product_id IN (SELECT DISTINCT(product_id) FROM product_price_national_to_store WHERE price > 0 ) OR product_id IN (SELECT DISTINCT(product_id) FROM product_price_to_store WHERE price > 0 ))");

			$i = 1;
			foreach ($query->rows as $product){
				
				echoLine($i . ' Товар ' . $product['product_id']);
				$i++;
				
				$this->indexproduct($product);
			}
			
			$params = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
			'id'    => $product['product_id']
			];
			
			
			$response = $this->check()->get($params);
			print_r($response);
			
			return $this;			
		}













	
}
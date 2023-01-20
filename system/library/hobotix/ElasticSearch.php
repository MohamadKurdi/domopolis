<?
	
	namespace hobotix;
	
	use Elasticsearch\ClientBuilder;
	
	class ElasticSearch{				
		public $elastic;
		private $db;
		private $log;
		private $cache;
		private $config;
		private $registry;
		private $settings = [];
		private $routes = [
			'kp/search',
			'kp/search/test',
			'catalog/product_ext/delete'
		];
		
		const CATEGORY_PRIORITY = 20;
		const MANUFACTURER_PRIORITY = 200;
		const KEYWORDER_PRIORITY = 300;
		const COLLECTION_PRIORITY = 400;
		
		const IN_STOCK_PRIORITY = 100;
		
		
		public function __construct($registry, $explicit = false){
			$this->registry	= $registry;

			$this->db 		= $registry->get('db');
			$this->cache 	= $registry->get('cache');
			$this->config 	= $registry->get('config');
			$this->log 		= $registry->get('log');		
			$this->request 	= $registry->get('request');	

			if (is_cli() || $explicit || (!empty($this->request->get['route']) && in_array($this->request->get['route'], $this->routes)) || !empty($this->request->get['search'])){ 
				$this->elastic = ClientBuilder::create()->setHosts(['http://127.0.0.1:9200'])->build();
			} else {
				$this->elastic = null;
			}
			
			$this->settings = loadJsonConfig('search');							
		}
		
		
		///////////////	 СЕРВИСНЫЕ ФУНКЦИИ //////////////////////////////////
		
		
		public function buildField($field){
			
			$result = $field . '_ru';
			if (!empty($this->registry->get('languages_id_code_mapping')[$this->config->get('config_language_id')])){
				$result = $field . '_' . $this->registry->get('languages_id_code_mapping')[$this->config->get('config_language_id')];
			}
			
			return trim($result);
		}
		
		public function checkUAName($name){
			
			if ($this->config->get('config_language_id') == 6){
				if (is_array($name)){
					$name = $name[0];
				}
			}
			
			return $name;
		}
		
		function is_stopword($word){
			$word = trim($word);
			
			$array = array('как','так','когда','где','куда','почему','зачем','сколько','откуда','только','еще','ещё','уже','да','нет','не','я','меня','мне','мной','мною','мой','мое','моё','моего','моему','моем','моя','мою','моей','моею','мои','моих','моим','моими','ты','тебя','тебе','тобой','тобою','он','оно','его','него','ему','нему','им','ним','нем','она','ее','её','нее','ей','ней','ею','нею','вы','вас','вам','вами','мы','нас','нам','нами','наш','наше','нашего','нашему','нашим','нашем','наша','нашу','нашей','нашею','наши','наших','нашим','нашими','они','их','них','им','ими','ними','кто','кого','кому','кем','ком','что','чего','чему','чем','этот','это','этого','этому','этим','этом','эта','эту','этой','этою','эти','этих','этим','этими','тот','то','того','тому','том','та','ту','той','те','тех','тем','теми','такой','такое','такого','такому','таком','такая','такую','такой','такою','такие','таких','таким','такими','свой','свое','своего','своему','своим','своем','своя','свою','своей','своею','свои','своих','своим','своими','который','которое','которого','которому','которым','котором','которая','которую','которой','которою','которые','которых','которым','которыми','сам','само','самого','самому','самим','самом','сама','саму','самой','сами','самих','самим','самими','себя','себе','собой','собою','один','одно','одного','одному','одним','одном','одна','одну','одной','одною','одни','одних','одним','одними','весь','всё','всего','всему','всем','вся','всю','всей','всею','все','всех','всем','всеми','твой','твоё','твое','твоего','твоему ','твоим','твоем','твоём','твоя','твою','твоей','твоею','твои','твоих','твоим','твоими','ваш','ваше','вашего','вашему','вашим','вашем','ваша','вашу','вашей','вашей','вашею','ваши','ваших','вашим','вашими','какой','какое','какого','какому','каким','каком','какая','какую','какой','какие','каких','каким','какими','чей','чье','чьё','чьего','чьему','чьим','чьем','чья','чьей','чьею','чьи','чьих','чьим','чьими','а','бы','в','во','вот','до','если','же','за','и','из','или','к','ко','между','на','над','но','о','об','около','от','по','под','при','про','с','среди','то','у','чтобы');
			
			
			return in_array($word, $array);
		}
		
		public static function prepareWords($query){
			
			$exploded = explode(' ',$query);
			$words = array();
			foreach ($exploded as $word){
				if (count($exploded) > 1){
					
					if (mb_strlen($word) > 2 && !self::is_stopword($word)){
						$words[] = trim($word);
					}
					
					} else {					
					$words[] = trim($word);
				}
			}
			
			return $words;
		}			
		
		public function prepareQueryExceptions($query){
			foreach ($this->settings['exceptions'] as $key => $value){
				$query = mb_strtolower($query);
				$key = mb_strtolower($key);
				
				if (trim($query) == $key){
					$query = $value;
				}
				
				$query = str_replace(' ' . $key, ' ' . $value, $query);
				$query = str_replace($key . ' ', $value . ' ', $query);
				$query = str_replace(' ' . $key . ' ', ' ' . $value . ' ', $query);
			}
			
			return $query;
		}
		
		public static function makeIdentifiersArray($sku){
			$results = array();
			
			$sku = trim($sku);
			
			$results[] = str_replace(' ', '', $sku);
			$results[] = str_replace('.', '', $sku);
			$results[] = str_replace('-', '', $sku);
			$results[] = str_replace('/', '', $sku);		
			$results[] = str_replace(array(' ', '.', '-', '/'), '', $sku);
			$results[] = str_replace(array(' ', '-'), '', $sku);
			$results[] = str_replace(array(' ', '.'), '', $sku);
			$results[] = str_replace(array('-', '.'), '', $sku);
			$results[] = str_replace(array('/', '.'), '', $sku);
			$results[] = str_replace(array('/', ' '), '', $sku);
			$results[] = str_replace(array('/', '-'), '', $sku);
			
			if (substr($sku, 0, 1) == '0'){				
				$sku = trim(substr($sku, 1));
				
				$results[] = str_replace(' ', '', $sku);
				$results[] = str_replace('.', '', $sku);
				$results[] = str_replace('-', '', $sku);
				$results[] = str_replace(array(' ', '.', '-', '/'), '', $sku);
				$results[] = str_replace(array(' ', '-'), '', $sku);
				$results[] = str_replace(array(' ', '.'), '', $sku);
				$results[] = str_replace(array('-', '.'), '', $sku);
				$results[] = str_replace(array('/', '.'), '', $sku);
				$results[] = str_replace(array('/', ' '), '', $sku);
				$results[] = str_replace(array('/', '-'), '', $sku);
			}
			
			return array_filter(array_values(array_unique($results)));
			
		}		
		
		public static function validateResult($results, $checkSuggestion = false){
			
			if (is_array($results['hits']) && (int)$results['hits']['total']['value'] > 0){
				return (int)$results['hits']['total']['value'];
			}
			
			if ($checkSuggestion && is_array($results['suggest']) && (int)$results['suggest']['completition-suggestion'][0]['length'] > 0){
				return (int)$results['suggest']['completition-suggestion'][0]['length'];
			}
			
			return false;
		}
		
		public static function validateCountResult($results){
			
			if (!empty($results['count'])){
				return $results['count'];
			}
			
			return 0;
		}
		
		
		public static function validateAggregationResult($results, $index){
			
			
			if (!empty($results['aggregations']) && !empty($results['aggregations'][$index]) && !empty($results['aggregations'][$index]['buckets']) ){
				return $results['aggregations'][$index]['buckets'];
			}
			
			return false;
		}
		
		public static function getFirstSuggestion($result, $query){
			
			if (is_array($result['suggest']) && is_array($result['suggest']['phrase-suggestion']) && !empty($result['suggest']['phrase-suggestion'][0])){
				
				if (!empty($result['suggest']['phrase-suggestion'][0]['options']) && $result['suggest']['phrase-suggestion'][0]['options'][0]['text'] != $query){				
					return 	$result['suggest']['phrase-suggestion'][0]['options'][0]['text'];		
				}
			}
			
			if (is_array($result['suggest']) && is_array($result['suggest']['term-suggestion']) && !empty($result['suggest']['term-suggestion'][0])){
				
				$text = ' ';
				foreach ($result['suggest']['term-suggestion'] as $suggestion_word){
					
					if (!empty($suggestion_word['options'][0])){
						$text .= $suggestion_word['options'][0]['text'];
						} else {
						$text .= $suggestion_word['text'] . ' ';
					}
				}
				
				$text = trim($text);
				if ($text){
					return $text;
				}
				
				return 	$result['suggest']['term-suggestion'][0]['options'][0]['text'];							
			}
			
			return false;			
		}
		
		public static function createMappingToIDS($query, $indexOne, $indexTwo){
			
			$mapping = array();
			if ($query->num_rows){										
				foreach ($query->rows as $row){
					$mapping[$row[$indexOne]] = $row[$indexTwo];
				}
				unset($row);			
			}
			
			return $mapping;
		}	
		
		public static function createMappingToNonEmpty($query, $indexOne){
			
			$mapping = array();
			if ($query->num_rows){										
				foreach ($query->rows as $row){
					$mapping[$row[$indexOne]] = true;
				}
				unset($row);			
			}
			
			return $mapping;
		}
		
		
		public static function parseAlternateName($alternate_name){
			$results = array();
			
			if (trim($alternate_name) && mb_strlen(trim(str_replace(PHP_EOL, '', $alternate_name))) > 2){
				$exploded = explode(PHP_EOL, $alternate_name);
				
				foreach ($exploded as $line){
					if (trim($line) && mb_strlen(trim(str_replace(PHP_EOL, '', $line))) > 2){
						
						$results[] = trim(str_replace(PHP_EOL, '', $line));
						
					}				
				}
			}
			return $results;
		}
		
		public static function remapAlternateMapping(&$mapping){
			
			foreach ($mapping as &$mapline){
				if (is_array($remapped = self::parseAlternateName($mapline))){
					$mapline = $remapped;
				}			
			}
		}
		
		private static function transformNumber($number, $language_code){
			$numberToWords 			= new \NumberToWords\NumberToWords();
			
			if ($language_code == 'uk'){
				$numberTransformer = $numberToWords->getNumberTransformer('ua');
				return array($numberTransformer->toWords($number));
			}
			
			$m1 = array_values(\morphos\Russian\CardinalNumeralGenerator::getCases($number, $gender = \morphos\Gender::MALE));
			$m2 = array_values(\morphos\Russian\CardinalNumeralGenerator::getCases($number, $gender = \morphos\Gender::FEMALE));
			$m = (array_values(array_unique(array_merge($m1, $m2))));						
			
			return $m;		
		}
		
		public function makeTextNumbers(&$params){
			$tmp = $params;
			
			foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code){				
				if (!empty($tmp['body']['names_' . $language_code])){
					foreach ($tmp['body']['names_' . $language_code] as $tmp_name){
						for ($i=100; $i>=1; $i--){
							if (strpos($tmp_name, ' ' . $i . ' ') !== false){								
								foreach (self::transformNumber($i, $language_code) as $numberCases){		
									$new_name = str_replace(' ' . $i . ' ', ' ' . $numberCases . ' ', $tmp_name);

									$params['body']['names_' . $language_code][] = $new_name;
									echoLine($tmp_name . ' -> ' . $new_name);

									//Убрать апостроф из цифр на Украинском
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
			$mapping = self::createMappingToIDS($namequery, 'language_id', 'name');
			
			$manmapping = self::createMappingToIDS($manquery, 'language_id', 'name');
			$altmapping = self::createMappingToIDS($manquery, 'language_id', 'alternate_name');			
			self::remapAlternateMapping($altmapping);
			
			
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
			$mapping = self::createMappingToIDS($namequery, 'language_id', 'name');
			
			$manmapping = self::createMappingToIDS($manquery, 'language_id', 'name');
			$altmapping = self::createMappingToIDS($manquery, 'language_id', 'alternate_name');			
			self::remapAlternateMapping($altmapping);
			
			$colmapping = self::createMappingToIDS($colquery, 'language_id', 'name');
			$altcolmapping = self::createMappingToIDS($colquery, 'language_id', 'alternate_name');			
			self::remapAlternateMapping($altcolmapping);
			
			
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
			$mapping = self::createMappingToIDS($namequery, 'language_id', 'name');
			$desmapping = self::createMappingToIDS($namequery, 'language_id', 'description');		
			
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
				
				if (!empty($desmapping[$language_id])){
					if ($language_id == 6) {
						if (empty($desmapping[5])){
							$desmapping[5] = $desmapping[6];
						}					
						
						//	$params['body']['description_' . $language_code] = array_values(array_unique(array_merge(array($desmapping[$language_id]), array($desmapping[5]))));						
						} else {
						//	$params['body']['description_' . $language_code] = $desmapping[$language_id];
					}
				}
			}	
		}
		
		public function prepareCategoryManufacturerIndex($namequery, $idc, $idm, $idcc, &$params){
			$mapping = self::createMappingToIDS($namequery, 'language_id', 'name');
			$altmapping = self::createMappingToIDS($namequery, 'language_id', 'alternate_name');			
			self::remapAlternateMapping($altmapping);
			
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
			$manufacturerMapping 	= self::createMappingToIDS($namequery, 'language_id', 'name');
			$manufacturerAltMapping = self::createMappingToIDS($namequery, 'language_id', 'alternate_name');
			self::remapAlternateMapping($manufacturerAltMapping);
			
			$categoryMapping = self::createMappingToIDS($namequery2, 'language_id', 'name');
			$categoryAltMapping = self::createMappingToIDS($namequery2, 'language_id', 'alternate_name');
			self::remapAlternateMapping($categoryAltMapping);		
			
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
		
		public static function createIndexArray($mapping, $index, &$params){
			
			$params['body'][$index] = array();
			
			foreach ($mapping as $key => $value){
				$params['body'][$index][] = $key;
			}						
		}
		
		
		public function createStoreIndexArray($mapping, $index, &$params){
			
			$params['body'][$index] = array();
			
			foreach ($this->registry->get('stores_to_main_language_mapping') as $store_id => $language_code){
				if (!empty($mapping[$store_id])){
					$params['body'][$index][] = $store_id;
				}
			}
		}		
		
		
		//ФУНКЦИИ ПОИСКА
		public function fuzzyP($index, $query, $field1, $field2, $field3, $data = array()){

			$store_id = $this->config->get('config_store_id');
			if (!$store_id){
				$store_id = 0;
			}
			
			$fuzziness = (float)$this->config->get('config_elasticsearch_fuzziness_product');
			if (!empty($data['fuzziness'])){
				$fuzziness = (int)$data['fuzziness'];
			}		
			
			if (empty($data['limit'])){
				$data['limit'] = 6;
			}
			
			if (empty($data['start'])){
				$data['start'] = 0;
			}
			
			
			$params = [
			'index' => $index,
			'body'  	=> [
			'from' 		=> $data['start'],
			'size'		=> $data['limit'],			
			'query' 	=> [
			'bool' 		=>  [
			'must' 		=>  [ 'multi_match' => [ 'fields' => [$field1.'^10', $field2.'^8', 'identifier^5'], 'query' => $query, 'type' => 'best_fields', 'fuzziness' => $fuzziness, 'max_expansions' => 100, 'prefix_length' => 2, 'operator' => 'AND' ]	],
			'should'	=> [
			//	[ 'multi_match' => [ 'fields' => [$field1], 'query' => $query, 'fuzziness' => $fuzziness, 'prefix_length' => 2, 'operator' => 'AND' ] ]
			],
			'filter' 	=> [ 
			[ 'term'  => [ 'stores' => $store_id ] ], 
			[ 'term'  => [ 'status' => 1 ] ],		
			[ 'range' => [ 'stock_status_id' => [ 'lte' => 9 ] ] ]
			],
			'minimum_should_match' => 0	],
			],
			'highlight' => [ 
			'pre_tags' => [ '<b>' ], 'post_tags' => [ '</b>' ], 
			'fields' => [ 
			$field1 => [ 'require_field_match' => 'false', 'fragment_size' => 400,  'number_of_fragments' => 1 ],  
			$field2 => [ 'require_field_match' => 'false', 'fragment_size' => 400,  'number_of_fragments' => 1 ]			
			] ],
			] ];		

			if ($this->config->get('config_elasticsearch_use_local_stock')){
				$params['body']['sort'] = [
					[ '_script' => ['order' => 'desc', 'type' => 'number',
					'script' => [
						'lang'   => 'painless',
						'source' => "if(doc['" . $this->config->get('config_warehouse_identifier') . ".keyword'].value =='0'){-1}else{ _score}",
					] ],					
					],						
				];
			} else {
				$params['body']['sort'] = [
					[ 'stock_status_id'.'.keyword' => 'asc' ],
					[ $this->config->get('config_warehouse_identifier').'.keyword' => 'desc' ],										
					[ '_score' => 'desc' ],
				];
			}		
			
			if (!empty($data['sort'])){
				if ($data['sort'] == 'p.price'){
					if ($data['order'] == 'DESC'){
						$params['body']['sort'] = [
						[ 'stock_status_id'.'.keyword' => 'asc' ],							
						[ $this->config->get('config_warehouse_identifier').'.keyword' => 'desc' ],										
						[ 'price' => 'desc' ],
						[ '_score' => 'desc' ],
						];
					}
					
					if ($data['order'] == 'ASC'){
						$params['body']['sort'] = [
						[ 'stock_status_id'.'.keyword' => 'asc' ],
						[ $this->config->get('config_warehouse_identifier').'.keyword' => 'desc' ],										
						[ 'price' => 'asc' ],
						[ '_score' => 'desc' ],
						];
					}
				}
			}
			
			if (!empty($data['filter_manufacturer_id'])){
				$params['body']['query']['bool']['filter'][] = [ 'term'  => [ 'manufacturer_id' => $data['filter_manufacturer_id'] ] ];
			}
			
			if (!empty($data['filter_category_id'])){
				$params['body']['query']['bool']['filter'][] = [ 'term'  => [ 'categories' => $data['filter_category_id'] ] ];
			}
			
			if (!empty($data['getTotal'])){
				unset($params['body']['from']);
				unset($params['body']['size']);
				unset($params['body']['sort']);
				unset($params['body']['suggest']);
				unset($params['body']['highlight']);
				return self::validateCountResult($this->elastic->count($params));
			}
			
			if (!empty($data['count'])){
				$params['body']['aggs'] = [
				'manufacturers' => [ 'terms' => [ 'field' => 'manufacturer_id' ]],
				'categories' 	=> [ 'terms' => [ 'field' => 'categories' ]]
				];
				$params['body']['from'] = 0;
				$params['body']['size'] = 5000;
				unset($params['body']['sort']);
				unset($params['body']['suggest']);
				unset($params['body']['highlight']);
				return $this->elastic->search($params);
			}

			$results = $this->elastic->search($params);
			
		//	$this->log->debug($results);
			
			return $results;			
		}
		
		public function sku($query){
			
			$query = trim(str_replace(array('-', '.', '/', ' '), '', $query));

			$params = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
			'body'  	=> [
			'from' 		=> '0',
			'size'		=> '10',
			'sort' => [				
			'_score'
			],
			'query' 	=> [
					'bool' 		=>  [
						'must'  => [ 
							'match' => [
				 				'identifier' => $query,							
							]				
			 			],
			 			'filter' 	=> [ 'term' => [ 'stores' => $this->config->get('config_store_id') ], 'term' => [ 'status' => 1 ] ],			 
						],			
			] ] ];				
			return $this->elastic->search($params);
		}
		
		public function completition($index, $query, $field, $suggest, $data = array()){
		
			$fuzziness = $this->config->get('config_elasticsearch_fuzziness_autcocomplete');
			if (!empty($data['fuzziness'])){
				$fuzziness = (int)$data['fuzziness'];
			}
			
			$limit = 10;
			if (!empty($data['limit'])){
				$limit = $data['limit'];
			}
			
			$params = [
			'index' => $index,
			'body'  	=> [
			'from' 		=> '0',
			'size'		=> $limit,
			'suggest' 	=> [ 
			'completition-suggestion' => ['prefix' => $query, 
				'completion' => ['field' => $suggest, 'size' => $limit, 'skip_duplicates' => true, 
				'contexts' => ['suggest-priority' => [ ['context' => 'category', 'boost' => 5], ['context' => 'manufacturer', 'boost' => 4], ['context' => 'keyworder', 'boost' => 3], ['context' => 'collection', 'boost' => 2] ]]			
			]]
			],
			] ];
			
			
			return $this->elastic->search($params);
		}
		
		public function fuzzy($index, $query, $field, $suggest, $data = array()){
			
			$fuzziness = (float)$this->config->get('config_elasticsearch_fuzziness_category');			
			if (!empty($data['fuzziness'])){
				$fuzziness = (int)$data['fuzziness'];
			}
			
			/*	$words = $this->prepareWords($query);
				
				foreach ($words as $word){
				$must[] = array(
				'match' => [ $field => [ 'query' => $word, 'fuzziness' => $fuzziness, 'prefix_length' => 2, 'operator' => 'AND' ] ]
				);							
				}		
			*/
			
			$limit = 10;
			if (!empty($data['limit'])){
				$limit = $data['limit'];
			}
			
			$params = [
			'index' => $index,
			'body'  	=> [
			'from' 		=> '0',
			'size'		=> $limit,
			'query' 	=> [
			'bool' 		=>  [
			'must' 		=>  [ 'match' => [ $field => [ 'query' => $query, 'fuzziness' => $fuzziness, 'prefix_length' => 1, 'operator' => 'AND' ] ] ],
			'should'	=> [ 
			[ 'range' => [ 'priority' => [ 'lte' => self::CATEGORY_PRIORITY+10, 'boost' => 5.0 ] ] ], 
			[ 'range' => [ 'priority' => [ 'lte' => self::MANUFACTURER_PRIORITY+10, 'boost' => 4.0 ] ] ],
			[ 'range' => [ 'priority' => [ 'lte' => self::KEYWORDER_PRIORITY+10, 'boost' => 3.0 ] ] ],
			[ 'range' => [ 'priority' => [ 'lte' => self::COLLECTION_PRIORITY+10, 'boost' => 2.0 ] ] ]
			],
			'filter' 	=> [ 'term' => [ 'stores' => $this->config->get('config_store_id') ] ],
			'minimum_should_match' => 1
			] ],
			'suggest' 	=> [ 
			'completition-suggestion' => ['prefix' => $query, 
				'completion' => ['field' => $suggest, 'size' => 6, 'skip_duplicates' => true, 
				'contexts' => ['suggest-priority' => [ ['context' => 'category', 'boost' => 5], ['context' => 'manufacturer', 'boost' => 4], ['context' => 'keyworder', 'boost' => 3], ['context' => 'collection', 'boost' => 2] ]]			
			]]
			],
			'highlight' => [ 'pre_tags' => [ '<b>' ], 'post_tags' => [ '</b>' ], 'fields' => [ $field => [ 'require_field_match' => 'false', 'fragment_size' => 400,  'number_of_fragments' => 1 ] ] ]
			] ];
			
			
			return $this->elastic->search($params);
		}
		
		public function getManufacturer($manufacturer_id){
			$params = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
			'from' 	=> '0',
			'size'	=> '1',
			'body'  => [
			'query' => [
			'bool'  => [
			'filter' => [ [ 'term' => ['manufacturer_id' => $manufacturer_id] ], [ 'term' => ['category_id' => 0] ], [ 'term' => ['collection_id' => 0] ]  ]			 
			] ] ] ];
			
			return $this->elastic->search($params);
		}
		
		public function getEntity($manufacturer_id = 0, $category_id = 0){
			$params = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
			'from' 	=> '0',
			'size'	=> '1',
			'body'  => [
			'query' => [
			'bool'  => [
			'filter' => [ [ 'term' => ['manufacturer_id' => $manufacturer_id] ], [ 'term' => ['category_id' => $category_id] ], [ 'term' => ['collection_id' => 0] ]  ]			 
			] ] ] ];
			
			return $this->elastic->search($params);
		}

		public function deleteIndexes(){

			$deleteParams = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix')
			];
			$response = $this->elastic->indices()->delete($deleteParams);

			$deleteParams = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix')
			];
			$response = $this->elastic->indices()->delete($deleteParams);
		}
		
		////////////// ФУНКЦИИ ИНДЕКСИРОВАНИЯ ///////////////////////////
		
		public function recreateIndices(){
			
			if(php_sapi_name()!=="cli"){
				die('cli only');	
			}	
			
			try{
				$deleteParams = [
				'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix')
				];
				$response = $this->elastic->indices()->delete($deleteParams);
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
				$response =  $this->elastic->indices()->create($createParams);	
			} catch (\Exception $e){
				echoLine($e->getMessage());
			}	
			
			try{
				$deleteParams = [
					'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix')
				];
				//$response = $this->elastic->indices()->delete($deleteParams);
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
			'category_id' 		=> [ 'type' => 'integer', 'index' => 'true' ],
			'manufacturer_id' 	=> [ 'type' => 'integer', 'index' => 'true' ],
			'product_id' 		=> [ 'type' => 'integer', 'index' => 'true' ],
			'collection_id' 	=> [ 'type' => 'integer', 'index' => 'true' ],
			'priority'			=> [ 'type' => 'integer', 'index' => 'true' ],
			'sort_order'		=> [ 'type' => 'integer', 'index' => 'true' ],
			'viewed'			=> [ 'type' => 'integer', 'index' => 'true' ],
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
			'description_ru' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ], 
			'description_kz' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'description_by' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],  
			'description_ua' 	=> [ 'type' => 'text', 'analyzer' => 'ukrainian', 'index' => 'true' ],  
			'description_uk' 	=> [ 'type' => 'text', 'analyzer' => 'russian', 'index' => 'true' ],
			'description_de' 	=> [ 'type' => 'text', 'analyzer' => 'deutch', 'index' => 'true' ],
			'model'				=> [ 'type' => 'text', 'analyzer' => 'identifier', 'index' => 'true' ],
			'asin'				=> [ 'type' => 'text', 'analyzer' => 'identifier', 'index' => 'true' ],
			'sku'				=> [ 'type' => 'text', 'analyzer' => 'identifier', 'index' => 'true' ],
			'ean'				=> [ 'type' => 'text', 'analyzer' => 'identifier', 'index' => 'true' ],
			'identifier'		=> [ 'type' => 'text', 'analyzer' => 'identifier', 'index' => 'true' ],
			'stock_status_id'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'status'			=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity'			=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity_stock'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity_stockM'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity_stockK'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity_stockMN'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'quantity_stockAS'	=> [ 'type' => 'integer', 'index' => 'true', 'fields' => ['keyword' => ['type' => 'keyword', 'index' => 'true']] ],
			'stores'  			=> [ 'type' => 'integer', 'index' => 'true' ],
			'categories'  		=> [ 'type' => 'integer', 'index' => 'true' ],
			] ]
			] ];
			
			try {
				$response = $this->elastic->indices()->create($createParams);		
			} catch (\Exception $e){
				echoLine($e->getMessage());
			}
			
			return $this;			
		}
		
		public function indexer(){
			
			if(php_sapi_name()!=="cli"){
				die('cli only');	
			}						
			
			
			//Индексация категорий
			$query = $this->db->query("SELECT category_id FROM category WHERE status = 1");
			$i = 1;
			foreach ($query->rows as $category_id){							
				$params = [];
				$params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
				$params['id'] = $i;$i++;				
				$params['body'] = array();
				$params['body']['priority'] = ElasticSearch::CATEGORY_PRIORITY;
				$params['body']['type']	= 'category';
				
				//Индексация названий
				$namequery = $this->db->query("SELECT name as name, alternate_name as alternate_name, language_id FROM category_description WHERE category_id = '" . (int)$category_id['category_id'] . "'");						
				$this->prepareCategoryManufacturerIndex($namequery, $category_id['category_id'], 0, 0, $params);		

				echoLine('[elastic] Категория ' . $category_id['category_id']);
				
				//Привязка к магазинам
				$storequery = $this->db->query("SELECT store_id FROM category_to_store WHERE category_id = '" . (int)$category_id['category_id'] . "'");
				$mapping = ElasticSearch::createMappingToNonEmpty($storequery, 'store_id');
				$this->createStoreIndexArray($mapping, 'stores', $params);
				
				$response = $this->elastic->index($params);
				//	print_r($response);
				
			}
			
			
			//Индексация брендов
			$query = $this->db->query("SELECT manufacturer_id FROM manufacturer WHERE 1");
			foreach ($query->rows as $manufacturer_id){					
				//	echoLine('Только производитель ' . $manufacturer_id['manufacturer_id']);
				
				$params = [];
				$params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
				$params['id'] = $i;$i++;			
				$params['body'] = array();
				$params['body']['priority'] = ElasticSearch::MANUFACTURER_PRIORITY;
				$params['body']['type']	= 'manufacturer';
				
				//Индексация названий
				$namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");						
				$this->prepareCategoryManufacturerIndex($namequery, 0, $manufacturer_id['manufacturer_id'], 0, $params);	

				echoLine('[elastic] Бренд ' . $manufacturer_id['manufacturer_id']);			
				
				//Привязка к магазинам
				$storequery = $this->db->query("SELECT store_id FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");
				$mapping = ElasticSearch::createMappingToNonEmpty($storequery, 'store_id');
				$this->createStoreIndexArray($mapping, 'stores', $params);								
				
				$response = $this->elastic->index($params);
				//	print_r($response);
				
			}
			
			unset($manufacturer_id);			
			foreach ($query->rows as $manufacturer_id){	
				
				echoLine('Категории производитель ' . $manufacturer_id['manufacturer_id']);		
				
				$namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");
				
				$namequery2 = $this->db->query("SELECT DISTINCT c.category_id FROM category c LEFT JOIN keyworder k ON (k.category_id = c.category_id) LEFT JOIN keyworder_description kd ON (k.keyworder_id = kd.keyworder_id) WHERE k.manufacturer_id = '" . $manufacturer_id['manufacturer_id'] . "' AND c.status = 1 AND kd.keyworder_status = 1");
				
				if ($namequery2->num_rows){	
					
					foreach ($namequery2->rows as $row){
						$params = [];
						$params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
						$params['id'] = $i;$i++;
						$params['body'] = array();
						$params['body']['priority'] = ElasticSearch::KEYWORDER_PRIORITY;
						$params['body']['category_id'] = $row['category_id'];
						$params['body']['manufacturer_id'] = $manufacturer_id['manufacturer_id'];
						$params['body']['collection_id'] = 0;
						$params['body']['type']	= 'keyworder';
						
						
						$namequery3 = $this->db->query("SELECT name as name, alternate_name as alternate_name, language_id FROM category_description WHERE category_id = '" . (int)$row['category_id'] . "'");
						
						$this->prepareCategoryManufacturerInterSectionIndex($namequery, $namequery3, $params);					
						
						//Привязка к магазинам
						$storequery = $this->db->query("SELECT store_id FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "' AND store_id IN (SELECT store_id FROM category_to_store WHERE category_id = '" . $namequery2->row['category_id'] . "')");
						$mapping = ElasticSearch::createMappingToNonEmpty($storequery, 'store_id');
						$this->createStoreIndexArray($mapping, 'stores', $params);	
						
						$response = $this->elastic->index($params);
						//	print_r($response);
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
						$params['body'] = array();
						$params['body']['priority'] = ElasticSearch::COLLECTION_PRIORITY;
						$params['body']['category_id'] = 0;
						$params['body']['manufacturer_id'] = $manufacturer_id['manufacturer_id'];
						$params['body']['collection_id'] = $row['collection_id'];
						$params['body']['type']	= 'collection';
						
						
						$namequery3 = $this->db->query("SELECT c.name as name, cd.alternate_name as alternate_name, cd.language_id FROM collection_description cd LEFT JOIN collection c ON (cd.collection_id = c.collection_id) WHERE cd.collection_id = '" . (int)$row['collection_id'] . "'");
						
						ElasticSearch::prepareCategoryManufacturerInterSectionIndex($namequery, $namequery3, $params, true);					
						
						//Привязка к магазинам
						$storequery = $this->db->query("SELECT store_id FROM collection_to_store WHERE collection_id = '" . $namequery2->row['collection_id'] . "'");
						$mapping = ElasticSearch::createMappingToNonEmpty($storequery, 'store_id');
						$this->createStoreIndexArray($mapping, 'stores', $params);	
						
						$response = $this->elastic->index($params);
					}
				}
			}
			
			
			$params = [
			'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
			'id'    => $i-1
			];
			
			
			$response = $this->elastic->get($params);
			print_r($response);
			
			return $this;			
		}

		public function deleteproduct($product_id){
			$params = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
			'id'    => $product_id
			];	

			if ($this->elastic){
				
				try {
					$response = $this->elastic->delete($params);
				} catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e){
				//	$this->log->debug($e->getMessage());
				}

			}

		}
		
		private function indexproduct($product){

			$params = [];
			$params['index'] 	= 'products' . $this->config->get('config_elasticsearch_index_suffix');
			$params['id'] 		= $product['product_id'];				
			$params['body'] 	= array();
			$params['body']['priority'] = self::CATEGORY_PRIORITY;

			$params['body']['manufacturer_id'] 	= $product['manufacturer_id'];
			$params['body']['product_id'] 		= $product['product_id'];
			$params['body']['collection_id'] 	= $product['collection_id'];
			$params['body']['stock_status_id'] 	= $product['stock_status_id'];
			$params['body']['quantity'] 		= $product['quantity'];
			$params['body']['quantity_stock'] 	= $product['quantity_stock'];
			$params['body']['quantity_stockM']  = $product['quantity_stockM'];
			$params['body']['quantity_stockK']  = $product['quantity_stockK'];
			$params['body']['quantity_stockMN'] = $product['quantity_stockMN'];
			$params['body']['quantity_stockAS'] = $product['quantity_stockAS'];

			$params['body']['status']  			= $product['status'];

			$params['body']['sort_order']  		= $product['sort_order'];
			$params['body']['viewed']  			= $product['viewed'];
			$params['body']['price']  			= $product['price'];
			$params['body']['bought_for_week'] 	= $product['bought_for_week'];
			$params['body']['bought_for_month'] = $product['bought_for_month'];

			$params['body']['model'] = self::makeIdentifiersArray($product['model']);
			$params['body']['asin'] = self::makeIdentifiersArray($product['asin']);
			$params['body']['ean'] = self::makeIdentifiersArray($product['ean']);
			$params['body']['sku'] = self::makeIdentifiersArray($product['sku']);

			$params['body']['identifier'] = array_values(array_unique(array_merge(array($params['body']['product_id']), $params['body']['model'], $params['body']['asin'], $params['body']['ean'], $params['body']['sku'])));

				//Индексация названий
			$namequery = $this->db->query("SELECT name as name, description as description, language_id FROM product_description WHERE product_id = '" . (int)$product['product_id'] . "'");		
			$this->prepareProductIndex($namequery, $params);	

			$manquery = $this->db->query("SELECT m.name, md.alternate_name as alternate_name, md.language_id FROM manufacturer_description md LEFT JOIN manufacturer m ON (m.manufacturer_id = md.manufacturer_id) WHERE md.manufacturer_id = '" . (int)$product['manufacturer_id'] . "'");
			$this->prepareProductManufacturerIndex($namequery, $manquery, $params);

			$colquery = $this->db->query("SELECT c.name as name, cd.alternate_name as alternate_name, cd.language_id FROM collection_description cd LEFT JOIN collection c ON (c.collection_id = cd.collection_id) WHERE cd.collection_id = '" . (int)$product['collection_id'] . "'");
			$this->prepareProductManufacturerIndex($namequery, $colquery, $params);
			$this->prepareProductManufacturerCollectionIndex($namequery, $manquery, $colquery, $params);

			$this->makeTextNumbers($params);

			echoLine('[elastic] Товар ' . $product['product_id']);

				//Привязка к категориям
			$catquery = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product['product_id'] . "'");
			$mapping = self::createMappingToNonEmpty($catquery, 'category_id');
			self::createIndexArray($mapping, 'categories', $params);		

				//Привязка к магазинам
			$storequery = $this->db->query("SELECT store_id FROM product_to_store WHERE product_id = '" . (int)$product['product_id'] . "'");
			$mapping = self::createMappingToNonEmpty($storequery, 'store_id');
			self::createStoreIndexArray($mapping, 'stores', $params);		


			$response = $this->elastic->index($params);
		}

		public function reindexproduct($product_id){

			$query = $this->db->query("SELECT product_id, status, manufacturer_id, collection_id, sku, mpn, ean, model, asin, quantity, quantity_stock, quantity_stockM, quantity_stockK, quantity_stockAS, quantity_stockMN, stock_status_id, sort_order, viewed, bought_for_week, bought_for_month, price FROM product WHERE product_id = '" . (int)$product_id . "'");

			if ($query->num_rows){
				$this->indexproduct($query->row);
				$this->getProductByID($product_id);
			}

		}

		
		public function productsindexer(){
			
			if(php_sapi_name()!=="cli"){
				die('cli only');	
			}							
			
			$query = $this->db->query("SELECT product_id, status, manufacturer_id, collection_id, sku, mpn, ean, model, asin, quantity, quantity_stock, quantity_stockM, quantity_stockK, quantity_stockAS, quantity_stockMN, stock_status_id, sort_order, viewed, bought_for_week, bought_for_month, price FROM product WHERE status = 1 AND (price > 0 OR product_id IN (SELECT DISTINCT(product_id) FROM product_price_national_to_store WHERE price > 0 ) OR product_id IN (SELECT DISTINCT(product_id) FROM product_price_to_store WHERE price > 0 ))");
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
			
			
			$response = $this->elastic->get($params);
			print_r($response);
			
			return $this;
			
		}
		
		public function getProductByID($product_id){
		
			$params = [
			'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
			'id'    => $product_id
			];
			
			
			$response = $this->elastic->get($params);
			print_r($response);
		
		}
		
	}																					
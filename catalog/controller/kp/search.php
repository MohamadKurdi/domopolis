<?php
	use Elasticsearch\ClientBuilder;
	
	class ControllerKPSearch extends Controller {
		
		
		public function clear(){
			if (!empty($this->request->post['id'])){
				$this->load->model('kp/search');
				$this->model_kp_search->clearSearchHistory($this->request->post['id']);
			}
		}			
		
		public function test(){
			$product_id = 2608078;
			
			$result = $this->elasticSearch->getProductByID($product_id);
			
			$this->log->debug($result);
			
		}
		
		private function createData($hit, $field, $exact, $suggestLogic, $query, &$data){
			$href 	= '';					
			$id 	= '';
			$idtype = '';
			$type 	= '';
			
			
			$name = $hit['_source'][$field];
			
			if ($exact && !empty($hit['highlight'][$field])){
				$name = $hit['highlight'][$field][0];
			}
			
			if (!empty($hit['_source']['product_id'])){
				
				if ($hit['_source']['manufacturer_id']){
					$manufacturers[$hit['_source']['manufacturer_id']] = true;
				}
				
				$href 	= $this->url->link('product/product', 'product_id=' . $hit['_source']['product_id']);					
				$id 	= $hit['_source']['product_id'];
				$idtype = 'p' . $hit['_source']['product_id'];
				$type 	= 'p';
				
				
				} elseif ($hit['_source']['category_id'] && $hit['_source']['manufacturer_id']){									
				
				$manufacturers[$hit['_source']['manufacturer_id']] = true;
				
				$href 	= $this->url->link('product/category', 'path=' . $hit['_source']['category_id'] . '&manufacturer_id=' . $hit['_source']['manufacturer_id']);
				$id 	= $hit['_source']['category_id'] . '.' . $hit['_source']['manufacturer_id'];
				$idtype = 'mc' . $hit['_source']['category_id'] . '.' . $hit['_source']['manufacturer_id'];
				$type 	= 'mc';
				
				} elseif ($hit['_source']['collection_id'] && $hit['_source']['manufacturer_id']) {
				
				$manufacturers[$hit['_source']['manufacturer_id']] = true;
				
				$href 	= $this->url->link('product/collection', 'collection_id=' . $hit['_source']['collection_id']);
				$id 	= $hit['_source']['collection_id'] . '.' . $hit['_source']['manufacturer_id'];
				$idtype = 'cc' . $hit['_source']['collection_id'] . '.' . $hit['_source']['manufacturer_id'];
				$type 	= 'cc';
				
				} else {
				
				if ($hit['_source']['category_id']){
					$href 	= $this->url->link('product/category', 'path=' . $hit['_source']['category_id']);
					$id 	= $hit['_source']['category_id'];
					$idtype = 'c' . $hit['_source']['category_id'];
					$type 	= 'c';
				}
				
				if ($hit['_source']['manufacturer_id']){
					
					$manufacturers[$hit['_source']['manufacturer_id']] = true;
					
					$href 	= $this->url->link('product/manufacturer', 'manufacturer_id=' . $hit['_source']['manufacturer_id']);						
					$id 	= $hit['_source']['manufacturer_id'];
					$idtype = 'm' . $hit['_source']['manufacturer_id'];
					$type 	= 'm';
				}
				
			}	
			
			$name = $this->elasticSearch->checkUAName($name);
			
			if ($suggestLogic){
				$name 	= mb_strtolower($this->elasticSearch->checkUAName($hit['_source'][$field]));
				
				if ($query){
					$name = str_ireplace($query, '<b>' . $query . '</b>', $name);
				}
				
				$type 	= 's';
				$id 	= $hit['_id'];
				$idtype = 's' . $hit['_id'];
			}
			
			$data[$idtype] = array(
			'name' 		=> $name,
			'href' 		=> $href,
			'id'   		=> $id,
			'idtype'   	=> $idtype,	
			'type' 		=> $type
			);			
		}
		
		private function prepareResults($results, $field, $exact, $query = false){
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$data = array();
			$manufacturers = array();
			
			if (!empty($results['suggest']['completition-suggestion']) && count($results['suggest']['completition-suggestion'][0]['options'])){
				
				foreach ($results['suggest']['completition-suggestion'][0]['options'] as $option){					
					$this->createData($option, $field, $exact, true, $query, $data);	
				}
				
			}
			
			foreach ($results['hits']['hits'] as $hit){				
				$this->createData($hit, $field, $exact, false, $query, $data);	
			}	
			
			//Если в выдаче есть брендовые запросы, но их нет в выдаче, то добавляем
			foreach ($manufacturers as $manufacturer_id => $manufacturer){
				if (empty($data['m' . $manufacturer_id])){
					
					if (ElasticSearch::validateResult($resultManufacturer = $this->elasticSearch->getManufacturer($manufacturer_id))){
						$name 	= $resultManufacturer['hits']['hits'][0]['_source'][$field];
						$href 	= $this->url->link('catalog/manufacturer', 'manufacturer_id=' . $manufacturer_id);						
						$id 	= $manufacturer_id;
						$idtype = 'm' . $manufacturer_id;
						$type 	= 'm';
						
						
						
						$data[$idtype] = array(
						'name' 		=> $this->elasticSearch->checkUAName($name),
						'href' 		=> $href,
						'id'   		=> $id,
						'idtype'   	=> $idtype,	
						'type' 		=> $type
						);		
					}
				}				
			}
			
			$parsedData = ['p' => [], 'm' => [], 's' => [], 'c' => [], 'cc' => [], 'mc' => []];
			
			foreach ($data as $result){
				
				if ($result['type'] == 'p'){					
					$product = $this->model_catalog_product->getProduct($result['id']);
					if ($product){
						$parsedData['p'][$result['id']] = array(
						'id' 		=> $result['id'],
						'href' 		=> $result['href'],
						'name' 		=> $result['name'],
						'price' 	=> $this->currency->format($product['price']),
						'saving'    => $product['special']?round((($product['price'] - $product['special'])/($product['price'] + 0.01))*100, 0):false,
						'special' 	=> $product['special']?$this->currency->format($product['special']):false,
						'thumb'		=> $this->model_tool_image->resize($product['image'], 150, 150)
						);
					}
				}
				
				if ($result['type'] == 'c'){
					$parsedData['c'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);					
				}
				
				if ($result['type'] == 'm'){
					
					$parsedData['m'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);					
				}
				
				if ($result['type'] == 'mc'){
					
					$parsedData['mc'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);					
				}
				
				if ($result['type'] == 'cc'){
					
					$parsedData['cc'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);
					
				}
				
				if ($result['type'] == 's'){
					
					$parsedData['s'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);
					
				}
				
				
			}
			
			$data['results'] = $parsedData;
			return $data;			
		}				
		
		public function index(){
			
			foreach ($this->language->loadRetranslate('common/header') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
						
			$query = trim(mb_strtolower($this->request->get['query']));			
			if (!mb_strlen($query)){				
				$this->load->model('kp/search');
				$this->data['histories'] = array();
				
				$histories = $this->model_kp_search->getSearchHistory();
				
				if ($histories){
					foreach ($histories as $history){
						$this->data['histories'][] = $history;
					}
				}
				
				$this->data['populars'] = array();
				$populars = $this->model_kp_search->getPopularSearches();
				
				
				foreach ($populars as $popular){
					if (trim($popular['text'])){
						$this->data['populars'][] = array(
						'href' 		=> $this->url->link('product/search', 'search=' . trim($popular['text'])),
						'results'	=> $popular['results']?($popular['results'] . ' ' . morphos\Russian\NounPluralization::pluralize($popular['results'], $this->data['text_result_total_search'])):false,
						'text' 		=> trim($popular['text'])
						);				
					}
				}
			}
			
			try {
				
				$query = $this->request->get['query'];
				$query = $this->elasticSearch->prepareQueryExceptions($query);
				$query = trim(mb_strtolower($query));	
				$length = mb_strlen($query);
				
				$field = $this->elasticSearch->buildField('name');
				$field2 = $this->elasticSearch->buildField('names');
				$field3 = $this->elasticSearch->buildField('description');
				$field4 = $this->elasticSearch->buildField('suggest');
				
				
				if ($length <= 3){
					
					$results = $this->elasticSearch->completition('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field4);
					$r1 = $this->prepareResults($results, $field, true, $query);
					
					
					$this->log->debug(json_encode($r1));
					
					} else {										
					
					//Самый первый запрос, просто поиск по названию, надеюсь в большинстве случаев его достаточно
					$exact = true;
					$results = $this->elasticSearch->fuzzy('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field4);	
					
					//	$this->log->debug(json_encode($results));
					//die();
					
					//Запрос к альтернативному набору
					if (!ElasticSearch::validateResult($results, true)){
						$exact = false;
						$results = $this->elasticSearch->fuzzy('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field2, $field4);
					}		
					
					//TRY TO FIND BY SKU										
					if (Elasticsearch::validateResult($resultsP = $this->elasticSearch->sku($query)) == 1){				
						} else {
						
						$resultsP = $this->elasticSearch->fuzzyP('products' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field2, $field3);		
						
						if (!ElasticSearch::validateResult($resultsP)){
							$resultsP = $this->elasticSearch->sku($query);
						}
					}

					$r1 = $this->prepareResults($results, $field, $exact, $query);									
					$r2 = $this->prepareResults($resultsP, $field, $exact);
				}
				
				
				if (empty($r1['results'])){
					$r1['results'] = [];
				}
				
				if (empty($r2['results'])){
					$r2['results'] = [];
				}
				} catch ( Exception $e ) {
				$this->log->debug($e->getMessage());
				$this->data['results'][0] = array(				
				'name' => 'Something is broken, we are fixing it just now ' . $e->getMessage()
				);
			}			
			
			$this->data['results']['s'] = $this->data['results']['m'] = $this->data['results']['mc'] = $this->data['results']['p'] = $this->data['results']['c'] = $this->data['results']['cc'] = array();
			
			foreach (['m', 'p', 'c', 'cc', 'mc', 's'] as $idx){
				if (empty($r1['results'][$idx])){
					$r1['results'][$idx] = array();
				}
				
				foreach ($r1['results'][$idx] as $itr){					
					$this->data['results'][$idx][$itr['id']] = $itr;
				}
				
				unset($itr);
				
				if (empty($r2['results'][$idx])){
					$r2['results'][$idx] = array();
				}
				
				foreach ($r2['results'][$idx] as $itr){
					$this->data['results'][$idx][$itr['id']] = $itr;
				}
				
				unset($itr);
			}
			
			$this->data['results_count'] = count($this->data['results']['s']) + count($this->data['results']['m']) + count($this->data['results']['mc']) + count($this->data['results']['p']) + count($this->data['results']['c']) + count($this->data['results']['cc']);
			
			$this->template = $this->config->get('config_template') . '/template/structured/kpsearch.tpl';
			$this->response->setOutput($this->render());			
		}
		
		public function fullindexer(){	
			$this->elasticSearch->recreateIndices()->indexer()->productsindexer();		
		}

		public function productindexer($product_id){			
			$this->elasticSearch->reindexproduct($product_id);
		}
	}
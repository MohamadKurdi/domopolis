<?php

namespace hobotix;

class ElasticSearch{				
	public $elasticConnection     	= null;
	public $Indexer 				= null;
	public $Query 					= null;

	private $request 	= null;
	private $routes 	= ['kp/search',	'kp/search/test', 'catalog/product_ext/delete'];
	private $params 	= ['query', 'tag', 'search'];

	const CATEGORY_PRIORITY 		= 20;
	const MANUFACTURER_PRIORITY 	= 200;
	const KEYWORDER_PRIORITY 		= 300;
	const COLLECTION_PRIORITY 		= 400;		
	const IN_STOCK_PRIORITY 		= 100;


	public function __construct($registry, $explicit = false){		
		$this->request 	= $registry->get('request');	

		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ElasticSearch' . DIRECTORY_SEPARATOR . 'StaticFunctions.php');
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ElasticSearch' . DIRECTORY_SEPARATOR . 'Indexer.php');
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ElasticSearch' . DIRECTORY_SEPARATOR . 'Query.php');

		if (is_cli() || $explicit || (!empty($this->request->get['route']) && in_array($this->request->get['route'], $this->routes)) || isset($this->request->get['search'])){
			try{
				$this->elasticConnection = \Elasticsearch\ClientBuilder::create()->setHosts([ELASTICSEARCH_HOSTPORT])->build();
			} catch (\Exception $e){				
			}				
		}

		$this->Indexer 	= new ElasticSearch\Indexer($registry, $this->elasticConnection);
		$this->Query 	= new ElasticSearch\Query($registry, $this->elasticConnection);
	}
}																					
<?php

namespace hobotix\Search;

/*
 *
 * This logic was written for this engine and others, even before AI appeared in our life, maybe somewhen in 2019 year
 * Considered moving to newer search engines, because of high memory usage and lack of features
 * Okay, maybe there are many features in Elastic nowadays, but TypeSearch or Algolia offer the same, but in two clicks and fewer code
 *
 */

class Elastic{
	public $elasticConnection     	= null;
	public $Indexer 				= null;
	public $Query 					= null;

	private $request 	= null;
	private $routes 	= ['kp/search',	'kp/search/test', 'catalog/product_ext/delete', 'legacy/search'];
	private $params 	= ['query', 'tag', 'search'];

	const CATEGORY_PRIORITY 		= 20;
	const MANUFACTURER_PRIORITY 	= 200;
	const KEYWORDER_PRIORITY 		= 300;
	const COLLECTION_PRIORITY 		= 400;		
	const IN_STOCK_PRIORITY 		= 100;


	public function __construct($registry, $explicit = false){		
		$this->request 	= $registry->get('request');	

		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ElasticFramework' . DIRECTORY_SEPARATOR . 'StaticFunctions.php');
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ElasticFramework' . DIRECTORY_SEPARATOR . 'Indexer.php');
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ElasticFramework' . DIRECTORY_SEPARATOR . 'Query.php');

		if (is_cli() || $explicit || (!empty($this->request->get['route']) && in_array($this->request->get['route'], $this->routes)) || isset($this->request->get['search'])){
			try{
				$this->elasticConnection = \Elasticsearch\ClientBuilder::create()->setHosts([ELASTICSEARCH_HOSTPORT])->build();
			} catch (\Exception $e){				
			}				
		}

		$this->Indexer 	= new ElasticFramework\Indexer($registry);
		$this->Query 	= new ElasticFramework\Query($registry);
	}

    public function connection()
    {
        if (!$this->elasticConnection) {
            try {
                $this->elasticConnection = \Elasticsearch\ClientBuilder::create()->setHosts([ELASTICSEARCH_HOSTPORT])->build();
            } catch (\Exception $e) {
                print_r($e->getMessage());
            }
        }

        return $this->elasticConnection;
    }

    public function createIndices($type){
        if ($type == 'products'){
            $this->Indexer->recreateProductsIndex();
        }

        if ($type == 'entities'){
            $this->Indexer->recreateEntitiesIndex();
        }
    }

    public function fillIndices($type){
        if ($type == 'products'){
            $this->Indexer->fillProductsIndex();
        }

        if ($type == 'entities'){
            $this->Indexer->fillEntitiesIndex();
        }
    }

    public function deleteProduct($product_id){
        return $this->Indexer->deleteProduct($product_id);
    }

    public function reindexProduct($product_id){
        return $this->Indexer->reindexProduct($product_id);
    }

    public function getProduct($product_id){
        return $this->Query->getProductByID($product_id);
    }

    public function getEntity($entity_type, $entity_id){
        return $this->Query->getEntityById($entity_type, $entity_id);
    }
}																					
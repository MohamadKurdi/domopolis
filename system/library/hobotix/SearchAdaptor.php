<?php

namespace hobotix;

class SearchAdaptor
{
    private $registry = null;
    private $config = null;
    private $db = null;

    private $searchObject = null;
    private $searchHistory = null;

    public function __construct(\Registry $registry)
    {
        $this->registry = $registry;
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');

        require_once (dirname(__FILE__) . '/Search/SearchFramework/History.php');
        $this->searchHistory = new Search\SearchFramework\History($registry);

        if ($searchClass = $this->config->get('config_search_library')) {
            if (file_exists(DIR_SYSTEM . '/library/hobotix/Search/' . $searchClass . '.php')) {
                require_once(DIR_SYSTEM . '/library/hobotix/Search/' . $searchClass . '.php');
                $searchClass = "hobotix" . "\\" . "Search" . "\\" . $searchClass;
                $this->searchObject = new $searchClass($this->registry);

                echoLine('[SearchAdaptor::__construct] Using ' . $searchClass . ' search library', 'd');
            } else {
                throw new \Exception('[searchObject::__construct] Can not load Search library!');
            }
        }
    }

    public function connection() {
        if (method_exists($this->searchObject, 'connection')){
            try {
                return $this->searchObject->connection();
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::connection] Got error ' . $e->getMessage(), 'e');
            }
        }
    }

    public function getSearchAdaptors(): array
    {
        $results = [];

        $adaptors = glob(dirname(__FILE__) . '/Search/*');
        foreach ($adaptors as $adaptor) {
            if (is_file($adaptor)) {
                $results[] = pathinfo($adaptor, PATHINFO_FILENAME);
            }
        }

        return $results;
    }

    public function createIndices(string|bool $index = false): void
    {
        if (method_exists($this->searchObject, 'createIndices')){
            try {
                $this->searchObject->createIndices($index);
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::createIndices] Got error ' . $e->getMessage(), 'e');
            }
        }
    }

    public function fillIndices(string|bool $index = false): void
    {
        if (method_exists($this->searchObject, 'fillIndices')){
            try {
                echoLine('[SearchAdaptor::fillIndices] Calling fillIndices() function', 's');
                $this->searchObject->fillIndices($index);
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::fillIndices] Got error ' . $e->getMessage(), 'e');
            }
        }
    }

    public function getProduct(int $product_id): array
    {
        $result = [];

        if (method_exists($this->searchObject, 'getProduct')){
            try {
                $result = $this->searchObject->getProduct($product_id);
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::getProduct] Got error ' . $e->getMessage(), 'e');
                $result['error'] = $e->getMessage();
            }
        }

        return $result;
    }

    public function getEntity(string $entity_type, int $entity_id): array
    {
        $result = [];

        if (method_exists($this->searchObject, 'getEntity')){
            try {
                $result = $this->searchObject->getEntity($entity_type, $entity_id);
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::getEntity] Got error ' . $e->getMessage(), 'e');
                $result['error'] = $e->getMessage();
            }
        }

        return $result;
    }

    public function deleteProduct(int $product_id): void
    {
        if (method_exists($this->searchObject, 'deleteProduct')){
            try {
                $this->searchObject->deleteProduct($product_id);
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::deleteProduct] Got error ' . $e->getMessage(), 'e');
            }
        }
    }

    public function reindexProducts(array $products_id): void
    {
        foreach ($products_id as $product_id){
            $this->reindexProduct((int)$product_id);
        }
    }

    public function reindexProduct(int $product_id): void
    {
        if (method_exists($this->searchObject, 'reindexProduct')){
            try {
                $this->searchObject->reindexProduct($product_id);
            } catch (\Exception $e){
                echoLine('[SearchAdaptor::reindexProduct] Got error ' . $e->getMessage(), 'e');
            }
        }
    }

    public function searchQuery(string $query):string
    {
        $result = [];

        if (method_exists($this->searchObject, 'searchQuery')){
            try {
                $result = $this->searchObject->searchQuery($query);
            } catch (\Exception $e){
                $result['error'] = $e->getMessage();
                echoLine('[SearchAdaptor::searchQuery] Got error ' . $e->getMessage(), 'e');
            }
        }

        return json_encode($result);
    }
}
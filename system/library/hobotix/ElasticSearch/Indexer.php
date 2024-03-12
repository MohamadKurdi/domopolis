<?php

namespace hobotix\ElasticSearch;

class Indexer
{

    private $db = null;
    private $log = null;
    private $cache = null;
    private $config = null;
    private $registry = null;


    public function __construct($registry)
    {
        $this->registry = $registry;

        $this->db = $registry->get('db');
        $this->cache = $registry->get('cache');
        $this->config = $registry->get('config');
        $this->log = $registry->get('log');
        $this->request = $registry->get('request');
    }

    #
    # Data preparation functions
    #
    public function prepareProductManufacturerIndex($namequery, $manquery, &$params)
    {
        $mapping = StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');

        $manmapping = StaticFunctions::createMappingToIDS($manquery, 'language_id', 'name');
        $altmapping = StaticFunctions::createMappingToIDS($manquery, 'language_id', 'alternate_name');
        StaticFunctions::remapAlternateMapping($altmapping);


        foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code) {
            if (!empty($mapping[$language_id]) && !empty($manmapping[$language_id]) && !empty($altmapping[$language_id])) {
                foreach ($altmapping[$language_id] as $altManufacturer) {

                    $reparsedName = $mapping[$language_id];

                    if (strpos($mapping[$language_id], $manmapping[$language_id]) !== false) {
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

    public function prepareCategoryManufacturerIndex($namequery, $idc, $idm, $idcc, &$params)
    {
        $mapping = StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
        $altmapping = StaticFunctions::createMappingToIDS($namequery, 'language_id', 'alternate_name');
        StaticFunctions::remapAlternateMapping($altmapping);

        $params['body']['category_id'] = $idc;
        $params['body']['manufacturer_id'] = $idm;
        $params['body']['collection_id'] = $idcc;

        foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code) {
            if (!empty($mapping[$language_id])) {
                $params['body']['name_' . $language_code] = $mapping[$language_id];

                if ($language_id == 6) {
                    if (empty($mapping[5])) {
                        $mapping[5] = $mapping[6];
                    }

                    if (empty($altmapping[5])) {
                        $altmapping[5] = $altmapping[6];
                    }

                    $params['body']['names_' . $language_code] = array_values(array_unique(array_merge(array($mapping[$language_id]), array($mapping[5]), $altmapping[$language_id], $altmapping[5])));
                } else {
                    $params['body']['names_' . $language_code] = array_values(array_unique(array_merge(array($mapping[$language_id]), $altmapping[$language_id])));
                }

                if (!empty($params['body']['name_' . $language_code])) {
                    $params['body']['suggest_' . $language_code] = $params['body']['name_' . $language_code];
                }
            }
        }
    }

    public function prepareProductManufacturerCollectionIndex($namequery, $manquery, $colquery, &$params)
    {
        $mapping = StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');

        $manmapping = StaticFunctions::createMappingToIDS($manquery, 'language_id', 'name');
        $altmapping = StaticFunctions::createMappingToIDS($manquery, 'language_id', 'alternate_name');
        StaticFunctions::remapAlternateMapping($altmapping);

        $colmapping = StaticFunctions::createMappingToIDS($colquery, 'language_id', 'name');
        $altcolmapping = StaticFunctions::createMappingToIDS($colquery, 'language_id', 'alternate_name');
        StaticFunctions::remapAlternateMapping($altcolmapping);


        foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code) {
            if (!empty($mapping[$language_id]) && !empty($manmapping[$language_id]) && !empty($altmapping[$language_id]) && !empty($colmapping[$language_id]) && !empty($altcolmapping[$language_id])) {
                foreach ($altmapping[$language_id] as $altManufacturer) {

                    foreach ($altcolmapping[$language_id] as $altCollection) {

                        $reparsedName = $mapping[$language_id];

                        if (strpos($mapping[$language_id], $manmapping[$language_id]) !== false) {
                            $reparsedName = str_replace($manmapping[$language_id], $altManufacturer, $mapping[$language_id]);
                        } else {
                            $reparsedName = $mapping[$language_id] . ' ' . $altManufacturer;
                        }

                        if (strpos($mapping[$language_id], $colmapping[$language_id]) !== false) {
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

    public function prepareCategoryManufacturerInterSectionIndex($namequery, $namequery2, &$params, $collectionLogic = false)
    {
        $manufacturerMapping = StaticFunctions::createMappingToIDS($namequery, 'language_id', 'name');
        $manufacturerAltMapping = StaticFunctions::createMappingToIDS($namequery, 'language_id', 'alternate_name');
        StaticFunctions::remapAlternateMapping($manufacturerAltMapping);

        $categoryMapping = StaticFunctions::createMappingToIDS($namequery2, 'language_id', 'name');
        $categoryAltMapping = StaticFunctions::createMappingToIDS($namequery2, 'language_id', 'alternate_name');
        StaticFunctions::remapAlternateMapping($categoryAltMapping);

        foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code) {
            if (!empty($manufacturerMapping[$language_id]) && !empty($categoryMapping[$language_id])) {

                $categoryMapping[$language_id] = trim(str_replace($manufacturerMapping[$language_id], '', $categoryMapping[$language_id]));

                echoLine('>> Категория ' . $categoryMapping[$language_id]);

                if (!$collectionLogic && $language_id == 6) {

                    if (empty($categoryMapping[5])) {
                        $categoryMapping[5] = !empty($categoryMapping[2]) ? $categoryMapping[2] : $categoryMapping[6];
                    }

                    if (empty($manufacturerMapping[5])) {
                        $manufacturerMapping[2] = !empty($manufacturerMapping[2]) ? $manufacturerMapping[2] : $manufacturerMapping[6];
                    }

                    $categoryMapping[5] = trim(str_replace($manufacturerMapping[5], '', $categoryMapping[5]));

                    $params['body']['name_' . $language_code] = array_values(array_unique([trim($categoryMapping[$language_id] . ' ' . $manufacturerMapping[$language_id]), trim($categoryMapping[5] . ' ' . $manufacturerMapping[5])]));

                } else {
                    $params['body']['name_' . $language_code] = trim($categoryMapping[$language_id] . ' ' . $manufacturerMapping[$language_id]);
                }
            }

            if (!empty($categoryAltMapping[$language_id])) {
                foreach ($categoryAltMapping[$language_id] as $categoryAlt) {

                    if (!empty($manufacturerMapping[$language_id]) && trim($categoryAlt)) {
                        if ($language_id == 5) {
                            $params['body']['names_' . $language_code][] = trim($categoryAlt . ' ' . $manufacturerMapping[$language_id]);
                            $params['body']['names_' . 'uk'][] = trim($categoryAlt . ' ' . $manufacturerMapping[$language_id]);
                        } else {
                            $params['body']['names_' . $language_code][] = trim($categoryAlt . ' ' . $manufacturerMapping[$language_id]);
                        }
                    }
                }
                unset($categoryAlt);
            }

            if (!empty($manufacturerAltMapping[$language_id]) && !empty($categoryMapping[$language_id])) {

                foreach ($manufacturerAltMapping[$language_id] as $manufacturerAlt) {
                    if (trim($manufacturerAlt)) {
                        if (!$collectionLogic && $language_id == 5) {
                            $params['body']['names_' . $language_code][] = trim($categoryMapping[$language_id] . ' ' . $manufacturerAlt);
                            $params['body']['names_' . 'uk'][] = trim($categoryMapping[$language_id] . ' ' . $manufacturerAlt);
                        } else {
                            $params['body']['names_' . $language_code][] = trim($categoryMapping[$language_id] . ' ' . $manufacturerAlt);
                        }

                        if (!empty($categoryAltMapping[$language_id])) {
                            foreach ($categoryAltMapping[$language_id] as $categoryAlt) {
                                if (trim($categoryAlt)) {
                                    if ($language_id == 5) {
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

            if (!empty($params['body']['name_' . $language_code])) {
                $params['body']['suggest_' . $language_code] = $params['body']['name_' . $language_code];
            }
        }
    }

    public function prepareProductNamesIndex($product_names, &$params)
    {
        $mapping = StaticFunctions::createMappingToIDS($product_names, 'language_id', 'name');

        foreach ($this->registry->get('languages_id_code_mapping') as $language_id => $language_code) {
            if (!empty($mapping[$language_id])) {
                if ($language_id == 6) {
                    if (empty($mapping[5])) {
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

    #
    # Indexes preparation functions
    #
    public function deleteIndexes()
    {
        $deleteParams = [
            'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix')
        ];

        $response = $this->registry->get('elasticSearch')->connection()->indices()->delete($deleteParams);

        $deleteParams = [
            'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix')
        ];

        $response = $this->registry->get('elasticSearch')->connection()->indices()->delete($deleteParams);
    }

    public function refillEntitiesIndex()
    {
        try {
            $deleteParams = [
                'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix')
            ];
            $response = $this->registry->get('elasticSearch')->connection()->indices()->delete($deleteParams);
        } catch (\Exception $e) {
            echoLine($e->getMessage());
        }

        include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'indexes' . DIRECTORY_SEPARATOR . 'entities.php');

        try {
            $response = $this->registry->get('elasticSearch')->connection()->indices()->create($entitiesIndex);
        } catch (\Exception $e) {
            echoLine('[Indexer:exception]' . $e->getMessage(), 'e');
        }

        return $this;
    }

    public function recreateProductsIndex(){
        try {
            $deleteParams = [
                'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix')
            ];
            $response = $this->registry->get('elasticSearch')->connection()->indices()->delete($deleteParams);
        } catch (\Exception $e) {
            echoLine('[Indexer:exception]' . $e->getMessage(), 'e');
        }

        include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'indexes' . DIRECTORY_SEPARATOR . 'products.php');

        try {
            $response = $this->registry->get('elasticSearch')->connection()->indices()->create($productsIndex);
        } catch (\Exception $e) {
            echoLine('[Indexer:exception]' . $e->getMessage(), 'e');
        }

        return $this;
    }

    public function fillEntitiesIndex()
    {
        $query = $this->db->query("SELECT category_id FROM category WHERE status = 1");
        $i = 1;
        foreach ($query->rows as $category) {
            $params = [];
            $params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
            $params['id'] = 'category-' . $category['category_id'];
            $params['body'] = [];
            $params['body']['priority'] = \hobotix\ElasticSearch::CATEGORY_PRIORITY;
            $params['body']['type'] = 'category';

            $category_name_query = $this->db->query("SELECT name as name, alternate_name as alternate_name, language_id FROM category_description WHERE category_id = '" . (int)$category['category_id'] . "'");
            $this->prepareCategoryManufacturerIndex($category_name_query, $category['category_id'], 0, 0, $params);

            echoLine('[Indexer:fillEntitiesIndex] Indexing category ' . $category['category_id'], 'i');

            $stores_query = $this->db->query("SELECT store_id FROM category_to_store WHERE category_id = '" . (int)$category['category_id'] . "'");
            $mapping = StaticFunctions::createMappingToNonEmpty($stores_query, 'store_id');
            StaticFunctions::createStoreIndexArray($mapping, 'stores', $this->registry->get('stores_to_main_language_mapping'), $params);

            $response = $this->registry->get('elasticSearch')->connection()->index($params);
        }

        if ($this->config->get('config_elasticseach_index_manufacturers')){
            $query = $this->db->query("SELECT manufacturer_id FROM manufacturer WHERE 1");
            foreach ($query->rows as $manufacturer) {
                $params = [];
                $params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
                $params['id'] = 'manufacturer-' . $manufacturer['manufacturer_id'];
                $params['body'] = [];
                $params['body']['priority'] = \hobotix\ElasticSearch::MANUFACTURER_PRIORITY;
                $params['body']['type'] = 'manufacturer';

                $namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "'");
                $this->prepareCategoryManufacturerIndex($namequery, 0, $manufacturer['manufacturer_id'], 0, $params);

                 echoLine('[Indexer:fillEntitiesIndex] Indexing manufacturer ' . $manufacturer['manufacturer_id'], 'i');

                $stores_query = $this->db->query("SELECT store_id FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "'");
                $mapping = StaticFunctions::createMappingToNonEmpty($stores_query, 'store_id');
                StaticFunctions::createStoreIndexArray($mapping, 'stores', $this->registry->get('stores_to_main_language_mapping'), $params);

                $response = $this->registry->get('elasticSearch')->connection()->index($params);
            }
        }

        if ($this->config->get('config_elasticseach_index_keyworder')){
            unset($manufacturer_id);
            foreach ($query->rows as $manufacturer) {
               
                echoLine('[Indexer::fillEntitiesIndex ] Indexing categories for brand ' . $manufacturer['manufacturer_id'], 'i');

                $namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "'");

                $namequery2 = $this->db->query("SELECT DISTINCT c.category_id FROM category c LEFT JOIN keyworder k ON (k.category_id = c.category_id) LEFT JOIN keyworder_description kd ON (k.keyworder_id = kd.keyworder_id) WHERE k.manufacturer_id = '" . $manufacturer['manufacturer_id'] . "' AND c.status = 1 AND kd.keyworder_status = 1");

                if ($namequery2->num_rows) {
                    foreach ($namequery2->rows as $row) {
                        $params = [];
                        $params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
                        $params['id'] = 'keyworder-' . $row['category_id'] . '-' . $manufacturer['manufacturer_id'];
                        $params['body'] = [];
                        $params['body']['priority'] = \hobotix\ElasticSearch::KEYWORDER_PRIORITY;
                        $params['body']['category_id'] = $row['category_id'];
                        $params['body']['manufacturer_id'] = $manufacturer['manufacturer_id'];
                        $params['body']['collection_id'] = 0;
                        $params['body']['type'] = 'keyworder';


                        $namequery3 = $this->db->query("SELECT name as name, alternate_name as alternate_name, language_id FROM category_description WHERE category_id = '" . (int)$row['category_id'] . "'");

                        $this->prepareCategoryManufacturerInterSectionIndex($namequery, $namequery3, $params);
                        $stores_query = $this->db->query("SELECT store_id FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "' AND store_id IN (SELECT store_id FROM category_to_store WHERE category_id = '" . $namequery2->row['category_id'] . "')");
                        $mapping = StaticFunctions::createMappingToNonEmpty($stores_query, 'store_id');
                        StaticFunctions::createStoreIndexArray($mapping, 'stores', $this->registry->get('stores_to_main_language_mapping'), $params);

                        $response = $this->registry->get('elasticSearch')->connection()->index($params);
                    }
                }
            }

            unset($manufacturer_id);
            foreach ($query->rows as $manufacturer_id) {
                echoLine('[Indexer::fillEntitiesIndex ] Indexing collections for brand ' . $manufacturer_id['manufacturer_id'], 'i');

                $namequery = $this->db->query("SELECT m.manufacturer_id, m.name as name, md.alternate_name as alternate_name, md.language_id FROM manufacturer m LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id WHERE m.manufacturer_id = '" . (int)$manufacturer_id['manufacturer_id'] . "'");

                $namequery2 = $this->db->query("SELECT DISTINCT c.collection_id FROM collection c WHERE c.manufacturer_id = '" . $manufacturer_id['manufacturer_id'] . "'");

                if ($namequery2->num_rows) {
                    foreach ($namequery2->rows as $row) {
                        $params = [];
                        $params['index'] = 'categories' . $this->config->get('config_elasticsearch_index_suffix');
                        $params['id'] = 'collection-' . $row['collection_id'] . '-' . $manufacturer_id['manufacturer_id'];
                        $params['body'] = [];
                        $params['body']['priority'] = \hobotix\ElasticSearch::COLLECTION_PRIORITY;
                        $params['body']['category_id'] = 0;
                        $params['body']['manufacturer_id'] = $manufacturer_id['manufacturer_id'];
                        $params['body']['collection_id'] = $row['collection_id'];
                        $params['body']['type'] = 'collection';


                        $namequery3 = $this->db->query("SELECT c.name as name, cd.alternate_name as alternate_name, cd.language_id FROM collection_description cd LEFT JOIN collection c ON (cd.collection_id = c.collection_id) WHERE cd.collection_id = '" . (int)$row['collection_id'] . "'");

                        $this->prepareCategoryManufacturerInterSectionIndex($namequery, $namequery3, $params, true);

                        $stores_query = $this->db->query("SELECT store_id FROM collection_to_store WHERE collection_id = '" . $namequery2->row['collection_id'] . "'");
                        $mapping = StaticFunctions::createMappingToNonEmpty($stores_query, 'store_id');
                        StaticFunctions::createStoreIndexArray($mapping, 'stores', $this->registry->get('stores_to_main_language_mapping'), $params);

                        $response = $this->registry->get('elasticSearch')->connection()->index($params);
                    }
                }
            }
        }

        return $this;
    }

    public function fillProductsIndex()
    {
        $query = $this->db->query("SELECT * FROM product WHERE status = 1 AND (price > 0 OR product_id IN (SELECT DISTINCT(product_id) FROM product_price_national_to_store WHERE price > 0 ) OR product_id IN (SELECT DISTINCT(product_id) FROM product_price_to_store WHERE price > 0 )) GROUP BY product_id");

        $i = 1;        
        foreach ($query->rows as $product) {
            echoLine('[Indexer::fillProductsIndex] Creating index for product ' . $i . ' of ' . $query->num_rows . ', ' . $product['product_id'], 'i');
            $i++;

            $this->fillSingleProductIndex($product);
        }

        return $this;
    }

    private function fillSingleProductIndex($product)
    {
        $params = [];
        $params['index'] = 'products' . $this->config->get('config_elasticsearch_index_suffix');
        $params['id'] = $product['product_id'];
        $params['body'] = [];
        $params['body']['priority'] = \hobotix\ElasticSearch::CATEGORY_PRIORITY;

        $params['body']['manufacturer_id']  = $product['manufacturer_id'];
        $params['body']['product_id']       = $product['product_id'];
        $params['body']['collection_id']    = $product['collection_id'];
        $params['body']['stock_status_id']  = $product['stock_status_id'];
        $params['body']['status']           = $product['status'];
        $params['body']['sort_order']       = $product['sort_order'];
        $params['body']['viewed']           = $product['viewed'];
        $params['body']['price']            = $product['price'];
        $params['body']['bought_for_week']  = $product['bought_for_week'];
        $params['body']['bought_for_month'] = $product['bought_for_month'];
        $params['body']['quantity']         = $product['quantity'];
        $params['body']['quantity_stock']   = $product['quantity_stock'];

        $params['body'][$this->config->get('config_warehouse_identifier')] = $product[$this->config->get('config_warehouse_identifier')];
        $params['body'][$this->config->get('config_warehouse_identifier_local')] = $product[$this->config->get('config_warehouse_identifier_local')];

        if ($this->config->get('config_elasticseach_many_models')) {
            $models = StaticFunctions::makeIdentifiersArray($product['model']);
        } else {
            $models = [$product['model']];
        }

        if ($this->config->get('config_elasticseach_many_skus')) {
            $skus = StaticFunctions::makeIdentifiersArray($product['sku']);
        } else {
            $skus = [$product['sku']];
        }

        $params['body']['identifier'] = array_values(array_filter(array_unique(array_merge([$product['product_id'], $product['asin'], $product['ean']], $models, $skus))));

        $product_names_query = $this->db->query("SELECT name as name, language_id FROM product_description WHERE product_id = '" . (int)$product['product_id'] . "'");
        $this->prepareProductNamesIndex($product_names_query, $params);

        if ($this->config->get('config_elasticseach_index_manufacturers')){
            $manufacturer_query = $this->db->query("SELECT m.name, md.alternate_name as alternate_name, md.language_id FROM manufacturer_description md LEFT JOIN manufacturer m ON (m.manufacturer_id = md.manufacturer_id) WHERE md.manufacturer_id = '" . (int)$product['manufacturer_id'] . "'");
            $this->prepareProductManufacturerIndex($product_names_query, $manufacturer_query, $params);
        }

        if ($this->config->get('config_elasticseach_index_keyworder')){
            $collection_query = $this->db->query("SELECT c.name as name, cd.alternate_name as alternate_name, cd.language_id FROM collection_description cd LEFT JOIN collection c ON (c.collection_id = cd.collection_id) WHERE cd.collection_id = '" . (int)$product['collection_id'] . "'");
            $this->prepareProductManufacturerIndex($product_names_query, $collection_query, $params);
            $this->prepareProductManufacturerCollectionIndex($product_names_query, $manufacturer_query, $collection_query, $params);
        }

        if ($this->config->get('config_elasticseach_many_textnumbers')) {
            StaticFunctions::makeTextNumbers($this->registry->get('languages_id_code_mapping'), $params);
        }

        $category_query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product['product_id'] . "'");
        $mapping = StaticFunctions::createMappingToNonEmpty($category_query, 'category_id');
        StaticFunctions::createIndexArray($mapping, 'categories', $params);

        $store_query = $this->db->query("SELECT store_id FROM product_to_store WHERE product_id = '" . (int)$product['product_id'] . "'");
        $mapping = StaticFunctions::createMappingToNonEmpty($store_query, 'store_id');
        StaticFunctions::createStoreIndexArray($mapping, 'stores', $this->registry->get('stores_to_main_language_mapping'), $params);


        $response = $this->registry->get('elasticSearch')->connection()->index($params);
    }

    public function reindexProduct($product_id)
    {
        $query = $this->db->query("SELECT * FROM product WHERE product_id = '" . (int)$product_id . "'");

        if ($query->num_rows) {
            $this->fillSingleProductIndex($query->row);
            $this->getProductByID($product_id);
        }
    }

    public function reindexProducts($products)
    {
        foreach ($products as $product_id) {
            $this->reindexProduct($product_id, true);
        }
    }

    public function deleteProduct($product_id)
    {
        $params = [
            'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
            'id' => $product_id
        ];

        if ($this->registry->get('elasticSearch')->connection()) {
            try {
                echoLine('[Indexer::deleteProduct] Deleting product ' . $product_id . 'from index', 'w');
                $response = $this->registry->get('elasticSearch')->connection()->delete($params);
            } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
                echoLine('[Indexer::deleteProduct] Product ' . $product_id . ' does not exist', 'w');
            }
        }
    }
}
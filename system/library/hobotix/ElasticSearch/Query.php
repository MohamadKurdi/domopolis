<?php

namespace hobotix\ElasticSearch;

class Query
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

    public function checkUAName($name)
    {
        if ($this->config->get('config_language') == 'uk') {
            if (is_array($name)) {
                $name = $name[0];
            }
        }

        return $name;
    }

    public function buildField($field)
    {
        $result = $field . '_' . $this->config->get('config_language');
        if (!empty($this->registry->get('languages_id_code_mapping')[$this->config->get('config_language_id')])) {
            $result = $field . '_' . $this->registry->get('languages_id_code_mapping')[$this->config->get('config_language_id')];
        }

        return trim($result);
    }

    public function getProductByID($product_id)
    {
        $params = [
            'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
            'id' => $product_id
        ];


        return $response = $this->registry->get('elasticSearch')->connection()->get($params);        
    }

    public function getEntityByID($type, $entity_id)
    {
        $params = [
            'index' => 'categories' . $this->config->get('config_elasticsearch_index_suffix'),
            'id'    => $type . '-' . $entity_id
        ];


        return $response = $this->registry->get('elasticSearch')->connection()->get($params);       
    }

    public function completitionQuery($index, $query, $field, $suggest, $data = [])
    {
        $fuzziness = $this->config->get('config_elasticsearch_fuzziness_autcocomplete');
        if (!empty($data['fuzziness'])) {
            $fuzziness = (int)$data['fuzziness'];
        }

        $limit = 10;
        if (!empty($data['limit'])) {
            $limit = $data['limit'];
        }

        $params = [
            'index' => $index,
            'body' => [
                'from' => '0',
                'size' => $limit,
                'suggest' => [
                    'completition-suggestion' => ['prefix' => $query,
                        'completion' => ['field' => $suggest, 'size' => $limit, 'skip_duplicates' => true,
                            'contexts' => ['suggest-priority' => [['context' => 'category', 'boost' => 5], ['context' => 'manufacturer', 'boost' => 4], ['context' => 'keyworder', 'boost' => 3], ['context' => 'collection', 'boost' => 2]]]
                        ]
                    ]
                ],
            ]
        ];


        return $this->registry->get('elasticSearch')->connection()->search($params);
    }

    public function fuzzyCategoriesQuery($index, $query, $field, $suggest, $data = [])
    {

        $fuzziness = (float)$this->config->get('config_elasticsearch_fuzziness_category');
        if (!empty($data['fuzziness'])) {
            $fuzziness = (int)$data['fuzziness'];
        }

        $limit = 10;
        if (!empty($data['limit'])) {
            $limit = $data['limit'];
        }

        $params = [
            'index' => $index,
            'body' => [
                'from' => '0',
                'size' => $limit,
                'query' => [
                    'bool' => [
                        'must' => ['match' => [$field => ['query' => $query, 'fuzziness' => $fuzziness, 'prefix_length' => 1, 'operator' => 'AND']]],
                        'should' => [
                            ['range' => ['priority' => ['lte' => \hobotix\ElasticSearch::CATEGORY_PRIORITY + 10, 'boost' => 5.0]]],
                            ['range' => ['priority' => ['lte' => \hobotix\ElasticSearch::MANUFACTURER_PRIORITY + 10, 'boost' => 4.0]]],
                            ['range' => ['priority' => ['lte' => \hobotix\ElasticSearch::KEYWORDER_PRIORITY + 10, 'boost' => 3.0]]],
                            ['range' => ['priority' => ['lte' => \hobotix\ElasticSearch::COLLECTION_PRIORITY + 10, 'boost' => 2.0]]]
                        ],
                        'filter' => ['term' => ['stores' => $this->config->get('config_store_id')]],
                        'minimum_should_match' => 1
                    ]],
                'suggest' => [
                    'completition-suggestion' => ['prefix' => $query,
                        'completion' => ['field' => $suggest, 'size' => 6, 'skip_duplicates' => true,
                            'contexts' => ['suggest-priority' => [['context' => 'category', 'boost' => 5], ['context' => 'manufacturer', 'boost' => 4], ['context' => 'keyworder', 'boost' => 3], ['context' => 'collection', 'boost' => 2]]]
                        ]
                    ]
                ],
                'highlight' => ['pre_tags' => ['<b>'], 'post_tags' => ['</b>'], 'fields' => [$field => ['require_field_match' => 'false', 'fragment_size' => 400, 'number_of_fragments' => 1]
                ]
                ]
            ]
        ];


        return $this->registry->get('elasticSearch')->connection()->search($params);
    }


    public function fuzzyProductsQuery($index, $query, $field1, $field2, $field3, $data = [])
    {
        $store_id = $this->config->get('config_store_id');
        if (!$store_id) {
            $store_id = 0;
        }

        $fuzziness = (float)$this->config->get('config_elasticsearch_fuzziness_product');
        if (!empty($data['fuzziness'])) {
            $fuzziness = (int)$data['fuzziness'];
        }

        if (empty($data['limit'])) {
            $data['limit'] = 6;
        }

        if (empty($data['start'])) {
            $data['start'] = 0;
        }

        $params = [
            'index' => $index,
            'body' => [
                'from' => $data['start'],
                'size' => $data['limit'],
                'query' => [
                    'bool' => [
                        'must' => ['multi_match' => ['fields' => [$field1 . '^10', $field2 . '^8', 'identifier^5'], 'query' => $query, 'type' => 'best_fields', 'fuzziness' => $fuzziness, 'max_expansions' => 100, 'prefix_length' => 2, 'operator' => 'AND']],
                        'should' => [
                            //	[ 'multi_match' => [ 'fields' => [$field1], 'query' => $query, 'fuzziness' => $fuzziness, 'prefix_length' => 2, 'operator' => 'AND' ] ]
                        ],
                        'filter' => [
                            ['term' => ['stores' => $store_id]],
                            ['term' => ['status' => 1]],
                            ['range' => ['stock_status_id' => ['lte' => 9]]]
                        ],
                        'minimum_should_match' => 0],
                ],
                'highlight' => [
                    'pre_tags' => ['<b>'], 'post_tags' => ['</b>'],
                    'fields' => [
                        $field1 => ['require_field_match' => 'false', 'fragment_size' => 400, 'number_of_fragments' => 1],
                        $field2 => ['require_field_match' => 'false', 'fragment_size' => 400, 'number_of_fragments' => 1]
                    ]
                ],
            ]
        ];

        if ($this->config->get('config_elasticsearch_use_local_stock')) {
            $params['body']['sort'] = [
                ['_script' => ['order' => 'desc', 'type' => 'number',
                    'script' => [
                        'lang' => 'painless',
                        'source' => "if(doc['" . $this->config->get('config_warehouse_identifier') . ".keyword'].value =='0'){-1}else{ _score}",
                    ]
                ],
                ],
            ];
        } else {
            $params['body']['sort'] = [
                ['stock_status_id' . '.keyword' => 'asc'],
                [$this->config->get('config_warehouse_identifier') . '.keyword' => 'desc'],
                ['_score' => 'desc'],
            ];
        }

        if (!empty($data['sort'])) {
            if ($data['sort'] == 'p.price') {
                if ($data['order'] == 'DESC') {
                    $params['body']['sort'] = [
                        ['stock_status_id' . '.keyword' => 'asc'],
                        [$this->config->get('config_warehouse_identifier') . '.keyword' => 'desc'],
                        ['price' => 'desc'],
                        ['_score' => 'desc'],
                    ];
                }

                if ($data['order'] == 'ASC') {
                    $params['body']['sort'] = [
                        ['stock_status_id' . '.keyword' => 'asc'],
                        [$this->config->get('config_warehouse_identifier') . '.keyword' => 'desc'],
                        ['price' => 'asc'],
                        ['_score' => 'desc'],
                    ];
                }
            }
        }

        if (!empty($data['filter_manufacturer_id'])) {
            $params['body']['query']['bool']['filter'][] = ['term' => ['manufacturer_id' => $data['filter_manufacturer_id']]];
        }

        if (!empty($data['filter_category_id'])) {
            $params['body']['query']['bool']['filter'][] = ['term' => ['categories' => $data['filter_category_id']]];
        }

        if (!empty($data['getTotal'])) {
            unset($params['body']['from']);
            unset($params['body']['size']);
            unset($params['body']['sort']);
            unset($params['body']['suggest']);
            unset($params['body']['highlight']);

            $result = $this->registry->get('elasticSearch')->connection()->count($params);

            return StaticFunctions::validateCountResult($result);
        }

        if (!empty($data['count'])) {
            $params['body']['aggs'] = [
                'manufacturers' => ['terms' => ['field' => 'manufacturer_id.keyword']],
                'categories' => ['terms' => ['field' => 'categories']]
            ];
            $params['body']['from'] = 0;
            $params['body']['size'] = 5000;
            unset($params['body']['sort']);
            unset($params['body']['suggest']);
            unset($params['body']['highlight']);
            return $this->registry->get('elasticSearch')->connection()->search($params);
        }

        $results = $this->registry->get('elasticSearch')->connection()->search($params);

        return $results;
    }

    public function nonFuzzySkuQuery($query)
    {
        $query = trim(str_replace(array('-', '.', '/', ' '), '', $query));

        $params = [
            'index' => 'products' . $this->config->get('config_elasticsearch_index_suffix'),
            'body' => [
                'from' => '0',
                'size' => '10',
                'sort' => [
                    '_score'
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => [
                                'identifier' => $query,
                            ]
                        ],
                        'filter' => ['term' => ['stores' => $this->config->get('config_store_id')], 'term' => ['status' => 1]],
                    ],
                ]
            ]
        ];
        return $this->registry->get('elasticSearch')->connection()->search($params);
    }


}
<?php
class ControllerLegacySearch extends Controller {
    private $search 				= '';
    private $filter_category_id 	= 0;
    private $filter_manufacturer_id = 0;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->library('hobotix/Search/Elastic');
        $this->elasticSearch = new \hobotix\Search\Elastic($registry);
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

        if ($suggestLogic){
            if (is_array($hit['_source'][$field])){
                $name 	= mb_strtolower($hit['_source'][$field][0]);
            } else {
                $name 	= mb_strtolower($hit['_source'][$field]);
            }

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

        foreach ($manufacturers as $manufacturer_id => $manufacturer){
            if (empty($data['m' . $manufacturer_id])){

                if (\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($resultManufacturer = $this->elasticSearch->Query->getEntityByID('manufacturer', $manufacturer_id))){
                    $name 	= $resultManufacturer['hits']['hits'][0]['_source'][$field];
                    $href 	= $this->url->link('catalog/manufacturer', 'manufacturer_id=' . $manufacturer_id);
                    $id 	= $manufacturer_id;
                    $idtype = 'm' . $manufacturer_id;
                    $type 	= 'm';



                    $data[$idtype] = array(
                        'name' 		=> $name,
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

    public function ajax(){
        foreach ($this->language->loadRetranslate('common/header') as $translationСode => $translationText){
            $this->data[$translationСode] = $translationText;
        }

        if (empty($this->request->get['query']) && !empty($this->request->get['tag'])){
            $this->request->get['query'] = $this->request->get['tag'];
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
            $query = \hobotix\Search\ElasticFramework\StaticFunctions::prepareQueryExceptions($query);
            $query = trim(mb_strtolower($query));
            $length = mb_strlen($query);

            $field = $this->elasticSearch->Query->buildField('name');
            $field2 = $this->elasticSearch->Query->buildField('names');
            $field3 = $this->elasticSearch->Query->buildField('description');
            $field4 = $this->elasticSearch->Query->buildField('suggest');


            if ($length <= (int)$this->config->get('config_elasticseach_index_autocomplete_symbols')){

                $results = $this->elasticSearch->Query->completitionQuery('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field4);
                $r1 = $this->prepareResults($results, $field, true, $query);
                //$this->log->debug(json_encode($r1));

            } else {

                $exact = true;
                $results = $this->elasticSearch->Query->fuzzyCategoriesQuery('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field4);

                if (!\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($results, true)){
                    $exact = false;
                    $results = $this->elasticSearch->Query->fuzzyCategoriesQuery('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field2, $field4);
                }

                if (\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($resultsP = $this->elasticSearch->Query->nonFuzzySkuQuery($query)) == 1){
                } else {

                    $resultsP = $this->elasticSearch->Query->fuzzyProductsQuery('products' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field2, $field3);

                    if (!\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($resultsP)){
                        $resultsP = $this->elasticSearch->Query->nonFuzzySkuQuery($query);
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

    private function elasticSingleProductResult($results){
        $this->load->model('catalog/product');

        if (!empty($results['hits']['hits'][0]) && !empty($results['hits']['hits'][0]['_source']) && !empty($results['hits']['hits'][0]['_source']['product_id'])){
            if ($product = $this->model_catalog_product->getProduct($results['hits']['hits'][0]['_source']['product_id'])){

                return $product;
            }
        }

        return false;
    }

    private function elasticResults($results, $field){
        $this->load->model('catalog/product');

        $product_data = [];
        $manufacturer_data = [];

        foreach ($results['hits']['hits'] as $hit){

            $name = $hit['_source'][$field];

            if (!empty($hit['highlight'][$field])){
                $name = $hit['highlight'][$field][0];
            }

            if ($product = $this->model_catalog_product->getProduct($hit['_source']['product_id'])){

                if (is_array($name)){
                    $name = $product['name'];
                }

                $product_data[$hit['_source']['product_id']] 			= $product;
                $product_data[$hit['_source']['product_id']]['name'] 	= $name;
            }
        }

        return ['product_data' => $product_data, 'manufacturer_data' => $manufacturer_data];
    }

    private function elasticResultsCMA($results, $field, $exact){
        $data = [];
        $manufacturers = [];

        foreach ($results['hits']['hits'] as $hit){
            $href 		= '';
            $id 		= '';
            $idtype 	= '';
            $type 		= '';

            $name = $hit['_source'][$field];

            if ($exact && !empty($hit['highlight'][$field])){
                $name = $hit['highlight'][$field][0];
            }

            if ($hit['_source']['category_id'] && $hit['_source']['manufacturer_id']){

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

            $data[$idtype] = array(
                'name' 		=> $name,
                'href' 		=> $href,
                'id'   		=> $id,
                'idtype'   	=> $idtype,
                'type' 		=> $type
            );

            foreach ($manufacturers as $manufacturer_id => $manufacturer){
                if (empty($data['m' . $manufacturer_id])){

                    if (\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($resultManufacturer = $this->elasticSearch->Query->getEntityByID('manufacturer', $manufacturer_id))){
                        $name 	= $resultManufacturer['hits']['hits'][0]['_source'][$field];
                        $href 	= $this->url->link('catalog/manufacturer', 'manufacturer_id=' . $manufacturer_id);
                        $id 	= $manufacturer_id;
                        $idtype = 'm' . $manufacturer_id;
                        $type 	= 'm';


                        $data[$idtype] = array(
                            'name' 		=> $name,
                            'href' 		=> $href,
                            'id'   		=> $id,
                            'idtype'   	=> $idtype,
                            'type' 		=> $type
                        );
                    }
                }
            }
        }

        return $data;
    }

    private function prepareCategories($results){
        $this->load->model('catalog/category');
        $data = [];

        if ($results){
            foreach ($results as $result){
                $category = $this->model_catalog_category->getCategory($result['key']);

                if ($category){

                    $href = $this->url->link('product/search', 'search=' . $this->search . '&filter_category_id=' . $category['category_id']);

                    $data[] = array(
                        'name' 		=> $category['name'],
                        'active' 	=> ($category['category_id'] == $this->filter_category_id),
                        'href' 		=> $href,
                        'count' 	=> $result['doc_count']
                    );
                }
            }
        }

        return $data;
    }

    private function prepareManufacturers($results){
        $this->load->model('catalog/manufacturer');

        $data = [];

        if ($results){
            foreach ($results as $result){
                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['key']);

                if ($manufacturer){

                    $data[] = array(
                        'name' 		=> $manufacturer['name'],
                        'active' 	=> ($manufacturer['manufacturer_id'] == $this->filter_manufacturer_id),
                        'href' 		=> $this->url->link('product/search', 'search=' . $this->search . '&filter_manufacturer_id=' . $manufacturer['manufacturer_id']),
                        'count' 	=> $result['doc_count']
                    );

                }
            }
        }

        return $data;
    }

    public function index() {
        $this->language->load('product/search');

        foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
            $this->data[$translationСode] = $translationText;
        }

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');
        $this->load->model('catalog/set');

        $this->load->model('tool/image');

        $this->data['page_type'] = 'search_page';

        if (isset($this->request->get['search'])) {
            $search = trim($this->request->get['search']);
        } else {
            $search = '';
        }

        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
        } else {
            $description = '';
        }

        if (isset($this->request->get['filter_category_id'])) {
            $filter_category_id = $this->request->get['filter_category_id'];
        } else {
            $filter_category_id = 0;
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
        } else {
            $filter_manufacturer_id = 0;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = $this->config->get('sort_default');
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = $this->config->get('order_default');
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_catalog_limit');
        }

        if (isset($this->request->get['search'])) {
            $this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
        } else {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

        $this->data['breadcrumbs'] = [];

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $url = '';

        if( ! empty( $this->request->get['mfp'] ) ) {
            $url .= '&mfp=' . $this->request->get['mfp'];
        }

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('product/search', $url),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['text_empty'] 			= $this->language->get('text_empty');
        $this->data['text_critea'] 			= $this->language->get('text_critea');
        $this->data['text_search'] 			= $this->language->get('text_search');
        $this->data['text_keyword'] 		= $this->language->get('text_keyword');
        $this->data['text_category'] 		= $this->language->get('text_category');
        $this->data['text_sub_category'] 	= $this->language->get('text_sub_category');
        $this->data['text_quantity'] 		= $this->language->get('text_quantity');
        $this->data['text_manufacturer'] 	= $this->language->get('text_manufacturer');
        $this->data['text_model'] 			= $this->language->get('text_model');
        $this->data['text_price'] 			= $this->language->get('text_price');
        $this->data['text_tax'] 			= $this->language->get('text_tax');
        $this->data['text_points'] 			= $this->language->get('text_points');
        $this->data['text_compare'] 		= sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $this->data['text_display'] 		= $this->language->get('text_display');
        $this->data['text_list'] 			= $this->language->get('text_list');
        $this->data['text_grid'] 			= $this->language->get('text_grid');
        $this->data['text_sort'] 			= $this->language->get('text_sort');
        $this->data['text_limit'] 			= $this->language->get('text_limit');
        $this->data['entry_search'] 		= $this->language->get('entry_search');
        $this->data['entry_description'] 	= $this->language->get('entry_description');
        $this->data['entry_suggestion'] 	= $this->language->get('entry_suggestion');
        $this->data['button_search'] 		= $this->language->get('button_search');
        $this->data['button_cart'] 			= $this->language->get('button_cart');
        $this->data['button_wishlist'] 		= $this->language->get('button_wishlist');
        $this->data['button_compare'] 		= $this->language->get('button_compare');

        $this->data['compare'] = $this->url->link('product/compare');

        $this->data['products'] = [];
        $this->data['sorts'] 	= [];

        foreach ($this->registry->get('sorts') as $sortConfig){
            if ($sortConfig['visible']){
                $this->data['sorts'][] = array(
                    'text'  => $this->language->get($sortConfig['text_variable']),
                    'value' => ($sortConfig['field'] . '-' . $sortConfig['order']),
                    'href'  => $this->url->link('product/search', '&sort=' . $sortConfig['field'] . '&order='. $sortConfig['order'])
                );
            }
        }

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $data = array(
                'search'         			=> $search,
                'filter_category_id'  		=> $filter_category_id,
                'filter_manufacturer_id'  	=> $filter_manufacturer_id,
                'sort'                => $sort,
                'order'               => $order,
                'start'               => ($page - 1) * $limit,
                'limit'               => $limit
            );

            $product_total = 0;
            $total_results = 0;

            try {
                $query = !empty($this->request->get['search'])?$this->request->get['search']:'';
                $query = \hobotix\Search\ElasticFramework\StaticFunctions::prepareQueryExceptions($query);
                $query = trim(mb_strtolower($query));
                $this->search = $query;
                $this->filter_category_id = $filter_category_id;
                $this->filter_manufacturer_id = $filter_manufacturer_id;

                if (\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($resultSKU = $this->elasticSearch->Query->nonFuzzySkuQuery($query)) == 1){
                    if ($productFoundBySKU = $this->elasticSingleProductResult($resultSKU)){
                        $this->response->redirect($this->url->link('product/product', 'product_id=' . $productFoundBySKU['product_id'] . '&search=' .  urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'))));
                    }
                }

                $field = $this->elasticSearch->Query->buildField('name');
                $field2 = $this->elasticSearch->Query->buildField('names');
                $field3 = $this->elasticSearch->Query->buildField('description');
                $field4 = $this->elasticSearch->Query->buildField('suggest');

                $product_total = $this->elasticSearch->Query->fuzzyProductsQuery('products' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field2, $field3, ['getTotal' => true]);

                $resultsE = $this->elasticSearch->Query->fuzzyProductsQuery('products' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field2, $field3, ['start' => (($page - 1) * $limit), 'limit' => $limit, 'filter_manufacturer_id' => $filter_manufacturer_id, 'filter_category_id' => $filter_category_id, 'sort' => $sort, 'order' => $order]);

                $results = $this->elasticResults($resultsE, $field);

                $resultAggregations = $this->elasticSearch->Query->fuzzyProductsQuery('products' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field2, $field3, ['count' => true]);
                $this->data['intersections'] = $this->prepareManufacturers(\hobotix\Search\ElasticFramework\StaticFunctions::validateAggregationResult($resultAggregations, 'manufacturers'));
                $this->data['intersections2'] = $this->prepareCategories(\hobotix\Search\ElasticFramework\StaticFunctions::validateAggregationResult($resultAggregations, 'categories'));

                $exact = true;
                $resultsCMA = $this->elasticSearch->Query->fuzzyCategoriesQuery('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field, $field4, ['limit' => 30]);

                if (!\hobotix\Search\ElasticFramework\StaticFunctions::validateResult($resultsCMA)){
                    $exact = false;
                    $resultsCMA = $this->elasticSearch->Query->fuzzyCategoriesQuery('categories' . $this->config->get('config_elasticsearch_index_suffix'), $query, $field2, $field4, ['limit' => 30]);
                }

                $this->data['top_found_cmas'] = $this->elasticResultsCMA($resultsCMA, $field, $exact);


            } catch ( Exception $e ) {
                $this->data['elastic_failed_error'] 		= 'Our smart search is temproraly broken! We are working on it';
                $this->data['elastic_failed_error_message'] = $e->getMessage();
            };

            $this->data['dimensions'] = array(
                'w' => $this->config->get('config_image_product_width'),
                'h' => $this->config->get('config_image_product_height')
            );

            $bestsellers = [];

            $this->data['products'] = $this->model_catalog_product->prepareProductToArray($results['product_data'], $bestsellers);

            if (!empty($this->data['intersections']) && !empty($this->data['intersections2']) && !empty($this->data['top_found_cmas']) && ($total_results = ($product_total + count($this->data['intersections']) + count($this->data['intersections2']) + count($this->data['top_found_cmas']))) == 0){
                $this->data['nothing_found'] = true;

            } else {
                $this->load->model('kp/search');
                $this->model_kp_search->writeSearchHistory($query, $total_results);
            }

            $url = '';

            if( ! empty( $this->request->get['mfp'] ) ) {
                $url .= '&mfp=' . $this->request->get['mfp'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $url = '';

            if( ! empty( $this->request->get['mfp'] ) ) {
                $url .= '&mfp=' . $this->request->get['mfp'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->data['limits'] = [];

            $limits = array_unique(array($this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit') * 2, $this->config->get('config_catalog_limit') * 3));

            sort($limits);

            foreach($limits as $value){
                $this->data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
                );
            }

            $url = '';

            if( ! empty( $this->request->get['mfp'] ) ) {
                $url .= '&mfp=' . $this->request->get['mfp'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->text = $this->language->get('text_pagination');
            $pagination->url = $this->url->link('product/search', $url . '&page={page}');

            $this->data['text_show_more'] = $this->language->get('text_show_more');
            $this->data['pagination'] = $pagination->render();
            $this->data['pagination_text'] = $pagination->render_text();

            $url = '';

            if( ! empty( $this->request->get['mfp'] ) ) {
                $url .= '&mfp=' . $this->request->get['mfp'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            $this->data['clear_url'] = $this->url->link('product/search', $url);

            if ($this->config->get('config_google_remarketing_type') == 'ecomm') {
                $this->data['google_tag_params'] = array(
                    'ecomm_prodid' => '',
                    'ecomm_pagetype' => 'searchresults',
                    'ecomm_totalvalue' => 0
                );

            } else {

                $this->data['google_tag_params'] = array(
                    'dynx_itemid' => '',
                    'dynx_itemid2' => '',
                    'dynx_pagetype' => 'searchresults',
                    'dynx_totalvalue' => 0
                );
            }
        }

        $this->data['search'] 					= $search;
        $this->data['pagetype'] 				= 'view_search_results';
        $this->data['description'] 				= $description;
        $this->data['filter_category_id'] 		= $filter_category_id;
        $this->data['filter_manufacturer_id'] 	= $filter_manufacturer_id;

        $this->data['sort'] 	= $sort;
        $this->data['order'] 	= $order;
        $this->data['limit'] 	= $limit;

        $_pre = '';
        if ($search){
            $_pre = '&search=' . $search;
        }

        $num_pages = !empty($product_total)?ceil($product_total / $limit):1;
        if ($page == 1) {
            $this->document->addLink($this->url->link('product/search', $_pre), 'canonical');
            $this->document->addRobotsMeta("noindex, follow");
        } else {
            $this->document->addLink($this->url->link('product/search', $_pre . '&page=' . $page), 'canonical');
            $this->document->addRobotsMeta("noindex, follow");
        }
        if ($page < $num_pages) {
            $this->document->addLink($this->url->link('product/search', $_pre . '&page=' . ($page + 1)), 'next');
        }
        if ($page > 1) {
            if ($page == 2) {
                $this->document->addLink($this->url->link('product/search', $_pre), 'prev');
            } else {
                $this->document->addLink($this->url->link('product/search', $_pre . '&page=' . ($page - 1)), 'prev');
            }
        }

        $this->data['current_sort'] = $this->language->get('text_default');

        foreach ($this->data['sorts'] as $_sort){
            if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
                $this->data['current_sort'] = $_sort['text'];
            }
        }

        if ($this->config->get('rewardpoints_appinstall')){
            $this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
        }

        $this->template = 'product/search.tpl';

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        if( isset( $this->request->get['mfilterAjax'] ) ) {
            $settings	= $this->config->get('mega_filter_settings');
            $baseTypes	= array( 'stock_status', 'manufacturers', 'rating', 'attributes', 'price', 'options', 'filters' );

            if( isset( $this->request->get['mfilterBTypes'] ) ) {
                $baseTypes = explode( ',', $this->request->get['mfilterBTypes'] );
            }

            if( ! empty( $settings['calculate_number_of_products'] ) || in_array( 'categories:tree', $baseTypes ) ) {
                if( empty( $settings['calculate_number_of_products'] ) ) {
                    $baseTypes = array( 'categories:tree' );
                }

                $this->load->model( 'module/mega_filter' );

                $idx = 0;

                if( isset( $this->request->get['mfilterIdx'] ) )
                    $idx = (int) $this->request->get['mfilterIdx'];

                $this->data['mfilter_json'] = json_encode( MegaFilterCore::newInstance( $this, NULL )->getJsonData($baseTypes, $idx) );
            }

            foreach( $this->children as $mf_child ) {
                $mf_child = explode( '/', $mf_child );
                $mf_child = array_pop( $mf_child );
                $this->data[$mf_child] = '';
            }

            $this->children=array();
            $this->data['header'] = $this->data['column_left'] = $this->data['column_right'] = $this->data['content_top'] = $this->data['content_bottom'] = $this->data['footer'] = '';
        }

        if( ! empty( $this->data['breadcrumbs'] ) && ! empty( $this->request->get['mfp'] ) ) {

            foreach( $this->data['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
                $mfReplace = preg_replace( '/path\[[^\]]+\],?/', '', $this->request->get['mfp'] );
                $mfFind = ( mb_strpos( $mfBreadcrumb['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=' );

                $this->data['breadcrumbs'][$mfK]['href'] = str_replace(array(
                    $mfFind . $this->request->get['mfp'],
                    '&amp;mfp=' . $this->request->get['mfp'],
                    $mfFind . urlencode( $this->request->get['mfp'] ),
                    '&amp;mfp=' . urlencode( $this->request->get['mfp'] )
                ), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb['href'] );
            }
        }

        $this->response->setOutput($this->render());
    }

}
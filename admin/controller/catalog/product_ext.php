<?php
function column_sort($a, $b) {
    if ($a['index'] == $b['index']) {
        return 0;
    }
    return ($a['index'] < $b['index']) ? -1 : 1;
}

class ControllerCatalogProductExt extends Controller {
    protected $error = [];

    public function index() {
        $this->data = array_merge($this->data, $this->language->load('catalog/product'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('catalog/product_ext');

        $this->getList();
    }

    public function setpicsize(){
        if (!empty($this->session->data['aqe_list_view_image_width'])){
            $this->config->set('aqe_list_view_image_width', $this->session->data['aqe_list_view_image_width']);
        }

        if (!empty($this->session->data['aqe_list_view_image_height'])){
            $this->config->set('aqe_list_view_image_height', $this->session->data['aqe_list_view_image_height']);
        }

        if ($this->request->get['resize'] == 'plus'){
            $this->session->data['aqe_list_view_image_width'] = $this->config->get('aqe_list_view_image_width') + 50;
            $this->session->data['aqe_list_view_image_height'] = $this->config->get('aqe_list_view_image_height') + 50;
        }

        if ($this->request->get['resize'] == 'minus'){
            $this->session->data['aqe_list_view_image_width'] = $this->config->get('aqe_list_view_image_width') - 50;
            $this->session->data['aqe_list_view_image_height'] = $this->config->get('aqe_list_view_image_height') - 50;
        }

        $url = '';

        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
            }
        }

        if (isset($this->request->get['filter_sub_category'])) {
            $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price_special'])) {
            $url .= '&filter_price_special=' . urlencode(html_entity_decode($this->request->get['filter_price_special'], ENT_QUOTES, 'UTF-8'));
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

        $this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function delete() {
        $this->data = array_merge($this->data, $this->language->load('catalog/product'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('kp/product');
        $this->load->model('catalog/product_ext');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            $asins = [];
                
                foreach ($this->request->post['selected'] as $product_id) {
                    if ($this->rainforestAmazon->offersParser->PriceLogic->checkIfProductIsOnAnyWarehouse($product_id)){
                        continue;
                    } else {
                     if ($asin = $this->model_catalog_product->getAsinByProductID($product_id)){
                        $asins[] = $asin;
                     }

                    $this->model_catalog_product->deleteProduct($product_id);
                    $this->registry->get('elasticSearch')->Indexer->deleteProduct($product_id, true);

                }
            }                       

            if ($this->config->get('config_enable_amazon_specific_modes') && $this->registry->hasDBCS()){
                $this->registry->setSyncDB();

                foreach ($asins as $asin) {
                    if ($product_id = $this->model_catalog_product->getProductIdByASIN($asin)){
                        if ($this->rainforestAmazon->offersParser->PriceLogic->checkIfProductIsOnAnyWarehouse($product_id)){
                            continue;
                        } else {
                            $this->model_catalog_product->deleteProduct($product_id);
                        }                       
                    }
                }

                $this->registry->setMainDB();
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
                if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                    $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
                }
            }

            if (isset($this->request->get['filter_sub_category'])) {
                $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price_special'])) {
                $url .= '&filter_price_special=' . urlencode(html_entity_decode($this->request->get['filter_price_special'], ENT_QUOTES, 'UTF-8'));
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

           $this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

       $this->getList();
    }

    public function copy() {
        $this->data = array_merge($this->data, $this->language->load('catalog/product'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('catalog/product_ext');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->copyProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
                if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                    $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
                }
            }

            if (isset($this->request->get['filter_sub_category'])) {
                $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price_special'])) {
                $url .= '&filter_price_special=' . urlencode(html_entity_decode($this->request->get['filter_price_special'], ENT_QUOTES, 'UTF-8'));
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

            $this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }
	
	 public function copynostock() {
        $this->data = array_merge($this->data, $this->language->load('catalog/product'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('catalog/product_ext');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->copyProductNoStock($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
                if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                    $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
                }
            }

            if (isset($this->request->get['filter_sub_category'])) {
                $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price_special'])) {
                $url .= '&filter_price_special=' . urlencode(html_entity_decode($this->request->get['filter_price_special'], ENT_QUOTES, 'UTF-8'));
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

            $this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList() {
		error_reporting(0);	
        $this->document->addStyle('view/stylesheet/aqe_style.css');

        $filters = [];

        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            $filters[$column] = (isset($this->request->get['filter_' . $column])) ? $this->request->get['filter_' . $column] : null;
        }
        $filters['sub_category']    = (isset($this->request->get['filter_sub_category'])) ? $this->request->get['filter_sub_category'] : $this->config->get('aqe_catalog_products_filter_sub_category');
        $filters['price_special']   = (isset($this->request->get['filter_price_special'])) ? $this->request->get['filter_price_special'] : '';

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.product_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
            }
        }

        if (isset($this->request->get['filter_sub_category'])) {
            $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price_special'])) {
            $url .= '&filter_price_special=' . urlencode(html_entity_decode($this->request->get['filter_price_special'], ENT_QUOTES, 'UTF-8'));
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

        $this->data['breadcrumbs'] = [];

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url),
            'separator' => ' :: '
        );

        $this->data['insert']       = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url);
        $this->data['copy']         = $this->url->link('catalog/product_ext/copy', 'token=' . $this->session->data['token'] . $url);
		$this->data['copynostock']  = $this->url->link('catalog/product_ext/copynostock', 'token=' . $this->session->data['token'] . $url);
        $this->data['delete']       = $this->url->link('catalog/product_ext/delete', 'token=' . $this->session->data['token'] . $url);
        $this->data['resize']       = $this->url->link('catalog/product_ext/setpicsize', 'token=' . $this->session->data['token'] . $url);

        $this->load->model('setting/store');
        $stores = $this->model_setting_store->getStores();

        $multistore = count($stores);

        $this->data['stores'] = [];

        $this->data['stores'][0] = array(
                'name' => $this->config->get('config_name'),
                'href' => HTTP_CATALOG
            );

        foreach ($stores as $store) {
            $this->data['stores'][$store['store_id']] = array(
                'name' => $store['name'],
                'href' => $store['url']
                );
        }

        $this->data['products'] = [];

        $data = array(
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit'           => $this->config->get('config_admin_limit')
        );

        foreach($filters as $filter => $value) {
            $data['filter_' . $filter] = $value;
        }

        $this->load->model('tool/image');

        if (!empty($this->session->data['aqe_list_view_image_width'])){
            $this->config->set('aqe_list_view_image_width', $this->session->data['aqe_list_view_image_width']);
        }

        if (!empty($this->session->data['aqe_list_view_image_height'])){
            $this->config->set('aqe_list_view_image_height', $this->session->data['aqe_list_view_image_height']);
        }

        $this->data['aqe_list_view_image_width'] = $this->config->get('aqe_list_view_image_width');
        $this->data['aqe_list_view_image_height'] = $this->config->get('aqe_list_view_image_height');

        $results        = $this->model_catalog_product_ext->getProducts($data);
        $product_total  = $this->model_catalog_product_ext->getTotalProducts($data);

        $actions = $this->config->get('aqe_catalog_products_actions');
        uasort($actions, 'column_sort');

        foreach ($results as $result) {
            $action = [];

            foreach ($actions as $act => $attr) {
                if ($attr['display']) {
                    $a = [];

                    switch ($act) {
                        case 'edit':
                            $a['href'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url);
                            break;
                        case 'view':
                            $a['click'] = HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id'];
                            break;
                        default:
                            break;
                    }

                    $a['text']  = $this->language->get('txt_' . $attr['short']);
                    $a['title'] = ($act=='edit')?'Открыть':$this->language->get('text_' . $act);
                    $a['edit']  = $attr['qe_type'];
                    $a['name']  = $act;
                    $a['btn']   = $attr['btn'];
                    $a['hide']  = $attr['hide'];
                    $a['ref']   = $attr['ref'];
                    $action[]   = $a;
                }
            }

            $image = $this->model_tool_image->resize($result['image'], 40, 40);

            $special = false;
            $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
            foreach ($product_specials  as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                    $special = round($product_special['price'], 2);

                    break;
                }
            }

            if ($this->config->get('config_single_special_price')){
                if (!$special && (float)$result['price_special']){
                    $special = $result['price_special'];
                }
            }

            $cp_cols = $this->config->get('aqe_catalog_products');
            $columns = array(
                'product_id' => $result['product_id'],
                'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
                'action'     => $action
            );
            if (!is_array($cp_cols)) {
                $columns['name']                = $result['name'];
                $columns['model']               = $result['model'];
                $columns['price']               = round($result['price'], 2);
                $columns['special']             = $special;
                $columns['image']               = $result['image'];
                $columns['status']              = ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'));
                $columns['filled_from_amazon']  = ($result['filled_from_amazon'] ? $this->language->get('text_yes') : $this->language->get('text_no'));
                $columns['fill_from_amazon']    = ($result['fill_from_amazon'] ? $this->language->get('text_yes') : $this->language->get('text_no'));
                $columns['quantity']            = $result['quantity'];
            } else {
                foreach($cp_cols as $column => $attr) {
                    if ($attr['display']) {
                        if ($column == 'image') {
                            $columns[$column] = $result['image'];
                            $columns['thumb'] = $this->model_tool_image->resize($result['image'], $this->config->get('aqe_list_view_image_width'), $this->config->get('aqe_list_view_image_height'));
                            $columns['name'] = $result['name'];
                        } else if ($column == 'category') {
                            $this->load->model('catalog/category');
                            $categories = $this->model_catalog_product->getProductCategories($result['product_id']);
                            $category_paths = [];
                            foreach($categories as $cat) {
                                $category = $this->model_catalog_category->getCategory($cat);
								if (isset($category['path']) && isset($category['name'])){
									$category_paths[] = (($category['path']) ? $category['path'] . ' &gt; ' : '') . $category['name'];
								} elseif (isset($category['name'])){
                                    $category_paths[] = $category['name'];
                                }
                            }
                            $columns[$column] = implode("<br />", $category_paths);
                        } else if ($column == 'store') {
                            $stores = $this->model_catalog_product->getProductStores($result['product_id']);
                            $product_stores = [];
                            foreach($stores as $store) {
                                $product_stores[] = $this->data['stores'][$store]['name'];
                            }
                            $columns[$column] = implode("<br />", $product_stores);
                        } else if ($column == 'filter') {
                            $this->load->model('catalog/filter');
                            $fs = $this->model_catalog_product->getProductFilters($result['product_id']);
                            $product_filters = [];
                            foreach($fs as $filter_id) {
                                $f = $this->model_catalog_filter->getFilter($filter_id);
                                $product_filters[] = strip_tags(html_entity_decode($f['group'] . ' &gt; ' . $f['name'], ENT_QUOTES, 'UTF-8'));
                            }
                            $columns[$column] = implode("<br />", $product_filters);
                        } else if ($column == 'download') {
                            $this->load->model('catalog/download');
                            $downloads = $this->model_catalog_product->getProductDownloads($result['product_id']);
                            $product_downloads = [];
                            foreach($downloads as $download_id) {
                                $dd = $this->model_catalog_download->getDownloadDescriptions($download_id);
                                $product_downloads[] = $dd[$this->config->get('config_language_id')]['name'];
                            }
                            $columns[$column] = implode("<br />", $product_downloads);
                        } else if ($column == 'status') {
                            if ((int)$result['status'] || !$this->config->get('aqe_highlight_status')) {
                                $columns[$column] = ((int)$result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'));
                            } else {
                                $columns[$column] = ((int)$result['status'] ? $this->language->get('text_enabled') : '<span style="color:#cf4a61;">' . $this->language->get('text_disabled') . '</span>');
                            }
                        } else if ($column == 'product_group') {
                            if ((int)$result['product_group_id']) {
                                $this->load->model('localisation/product_groups');
                                $product_group = $this->model_localisation_product_groups->getProductGroup($result['product_group_id']);

                                $columns[$column] = '<small style="padding:5px 7px; border-radius:4px; color:#' . $product_group['product_group_text_color'] . '; background-color:#' . $product_group['product_group_bg_color'] . '"><i class="fa ' . $product_group['product_group_fa_icon'] . '" ></i></small>';
                            } else {
                                $columns[$column] = '<small style="padding:5px 7px; border-radius:4px;; color:#FFF; background-color:#000"><i class="fa fa-times-circle" ></i> </small>';
                            }                        
                        } elseif ($column == 'filled_from_amazon') {
                            if ((int)$result['filled_from_amazon'] || !$this->config->get('aqe_highlight_status')) {
                                $columns[$column] = ((int)$result['filled_from_amazon'] ? $this->language->get('text_yes') : $this->language->get('text_no'));
                            } else {
                                $columns[$column] = ((int)$result['filled_from_amazon'] ? $this->language->get('text_yes') : '<span style="color:#cf4a61;">' . $this->language->get('text_no') . '</span>');
                            } 
                        } elseif ($column == 'fill_from_amazon') {
                            if ((int)$result['fill_from_amazon'] || !$this->config->get('aqe_highlight_status')) {
                                $columns[$column] = ((int)$result['fill_from_amazon'] ? $this->language->get('text_yes') : $this->language->get('text_no'));
                            } else {
                                $columns[$column] = ((int)$result['fill_from_amazon'] ? $this->language->get('text_yes') : '<span style="color:#cf4a61;">' . $this->language->get('text_no') . '</span>');
                            }                         
                        } else if ($column == 'quantity') {
                            if ((int)$result['quantity'] < 0) {
                                $columns[$column] = '<span style="color:#cf4a61;">' . $result['quantity'] . '</span>';
                            } else if ((int)$result['quantity'] <= 5) {
                                $columns[$column] = '<span style="color:#FFA500;">' . $result['quantity'] . '</span>';
                            } else {
                                $columns[$column] = '<span style="color:#008000;">' . $result['quantity'] . '</span>';
                            }
                        } else if ($column == 'product_offers_count') {
                            if ((int)$result['product_offers_count'] > 0) {
                                $columns[$column] = '<span style="color:#008000;">' . $result['product_offers_count'] . '</span>';
                                $columns[$column] .= ' <a target="_blank" href="' . $this->url->link('sale/order', 'filter_product_id=' . $result['product_id'] . '&token=' . $this->session->data['token'], 'SSL') . '"><i class="fa fa-filter"></i></a>';
                            } else if ((int)$result['product_offers_count'] <= 0) {
                                $columns[$column] = '<span style="color:#cf4a61;">' . $this->language->get('text_no') . '</span>';
                            }
                        } else if ($column == 'product_warehouse_count') {
                            if ((int)$result['product_warehouse_count'] > 0) {
                                $columns[$column] = '<span style="color:#008000;">' . $result['product_warehouse_count'] . '</span>';
                            } else if ((int)$result['product_warehouse_count'] <= 0) {
                                $columns[$column] = '<span style="color:#cf4a61;">' . $this->language->get('text_no') . '</span>';
                            }
                        } else if ($column == 'requires_shipping') {
                            $columns['requires_shipping'] = ($result['shipping'] ? $this->language->get('text_yes') : $this->language->get('text_no'));
                        } else if ($column == 'subtract') {
                            $columns[$column] = ($result['subtract'] ? $this->language->get('text_yes') : $this->language->get('text_no'));
                        } else if (in_array($column, array('weight', 'length', 'width', 'height'))) {
                            $columns[$column] = sprintf("%.4f",round((float)$result[$column], 4));
                        } else if ($column == 'date_available') {
                            $date = new DateTime($result['date_available']);
                            $columns[$column] = $date->format("Y-m-d");
                        } else if ($column == 'id') {
                            $columns[$column] = $result['product_id'];
                        } else if ($column == 'action') {
                            $columns[$column] = $action;
                        } else if ($column == 'view_in_store') {
                            $product_stores = $this->model_catalog_product->getProductStores($result['product_id']);
                            $columns[$column] = [];

                            foreach ($product_stores as $store) {
                                if (!in_array($store, array_keys($this->data['stores'])))
                                    continue;
                                $columns[$column][] = array(
                                    'name' => $this->data['stores'][$store]['name'],
                                    'href' => $this->data['stores'][$store]['href'] . 'index.php?route=product/product&product_id=' . $result['product_id']
                                    );
                            }
                        } else {
                            $columns[$column] = $result[$column];
                            if ($column == 'price' && $special) {
                                $columns['special'] = $special;
                                $columns[$column] = '<span style="text-decoration:line-through;">' . $result['price'] . '</span><br/><span style="color: #b00;">' . $special . '</span>';
                            }
                        }
                    }
                }
            }

            $columns['variants']                = $this->model_catalog_product->countVariantProducts($result['product_id']);
            $columns['amzn_no_offers_counter']  = $result['amzn_no_offers_counter'];
            $columns['amzn_last_offers']        = ($result['amzn_last_offers'] == '0000-00-00 00:00:00')?false:date('d-m', strtotime($result['amzn_last_offers']));

            $this->data['products'][] = $columns;
        }

        $this->data['language_id'] = $this->config->get('config_language_id');

        $this->data['columns'] = [];
        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            $this->data['columns'][$column] = $this->language->get('column_' . $column);
        }

        $cp_cols = $this->config->get('aqe_catalog_products');
        if (!is_array($cp_cols)) {
            $column_order = array('image', 'name', 'model', 'price', 'quantity', 'status');
            $cp_cols = [];
        } else {
            $column_order = [];
            uasort($cp_cols, 'column_sort');

            foreach($cp_cols as $column => $attr) {
                if ($attr['display'])
                    $column_order[] = $column;
            }
        }
        $this->data['column_order'] = $column_order;
        $this->data['column_info'] = $cp_cols;

        $this->data['update_url'] = html_entity_decode($this->url->link('catalog/product_ext/quick_update', 'token=' . $this->session->data['token'], 'SSL'));
        $this->data['refresh_url'] = html_entity_decode($this->url->link('catalog/product_ext/refresh_data', 'token=' . $this->session->data['token'], 'SSL'));
        $this->data['status_select'] = addslashes(json_encode(array("0" => $this->language->get('text_disabled'), "1" => $this->language->get('text_enabled'))));
        $this->data['yes_no_select'] = addslashes(json_encode(array("0" => $this->language->get('text_no'), "1" => $this->language->get('text_yes'))));

        $this->data['load_data_url']    = html_entity_decode($this->url->link('catalog/product_ext/load_data', 'token=' . $this->session->data['token'], 'SSL'));
        $this->data['load_popup_url']   = html_entity_decode($this->url->link('catalog/product_ext/load_popup', 'token=' . $this->session->data['token'], 'SSL'));

        $this->load->model('localisation/language');
        $lang_count = $this->model_localisation_language->getTotalLanguages();
        $this->data['single_lang_editing'] = $this->config->get('aqe_single_language_editing') || ((int)$lang_count == 1);

        if (!empty($data['filter_manufacturer'])){
            $this->load->model('catalog/manufacturer');
            $this->data['manufacturer'] = $this->model_catalog_manufacturer->getManufacturer($data['filter_manufacturer'])['name'];
        }

        if (in_array("tax_class", $column_order)) {
            $this->load->model('localisation/tax_class');
            $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
            $tc_select = array("0" => $this->language->get('text_none'));
            foreach ($this->data['tax_classes'] as $tc) {
                $tc_select[$tc['tax_class_id']] = $tc['title'];
            }
            $this->data['tax_class_select'] = addslashes(json_encode($tc_select));
        } else {
            $this->data['tax_class_select'] = addslashes(json_encode(array()));
        }

        if (in_array("stock_status", $column_order)) {
            $this->load->model('localisation/stock_status');
            $this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
            $ss_select = [];
            foreach ($this->data['stock_statuses'] as $ss) {
                $ss_select[$ss['stock_status_id']] = $ss['name'];
            }
            $this->data['stock_status_select'] = addslashes(json_encode($ss_select));
        } else {
            $this->data['stock_status_select'] = addslashes(json_encode(array()));
        }

         if (in_array("product_group", $column_order)) {
            $this->load->model('localisation/product_groups');
            $this->data['product_groups'] = $this->model_localisation_product_groups->getProductGroups();
            $pg_select = [];
            $pg_select['0'] = 'Выбери группу';
            foreach ($this->data['product_groups'] as $pg) {
                $pg_select[$pg['product_group_id']] = $pg['product_group_name'];
            }
            $pg_select['-1'] = 'Не в группе';
            $this->data['product_groups_select'] = addslashes(json_encode($pg_select));
        } else {
            $this->data['product_groups_select'] = addslashes(json_encode(array()));
        }

        if (in_array("length_class", $column_order)) {
            $this->load->model('localisation/length_class');
            $this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
            $lc_select = [];
            foreach ($this->data['length_classes'] as $lc) {
                $lc_select[$lc['length_class_id']] = $lc['title'];
            }
            $this->data['length_class_select'] = addslashes(json_encode($lc_select));
        } else {
            $this->data['length_class_select'] = addslashes(json_encode(array()));
        }

        if (in_array("added_from_supplier", $column_order)) {
            $this->load->model('sale/supplier');
            $this->data['parser_suppliers']  = $this->model_sale_supplier->getSuppliers(['filter_admin_enabled' => 1]);
            $ps_select = [];
            foreach ($this->data['parser_suppliers'] as $ps) {
                $ps_select[$ps['supplier_id']] = $ps['supplier_name'];
            }
            $this->data['parser_suppliers_select'] = addslashes(json_encode($ps_select));
        } else {
            $this->data['parser_suppliers_select'] = addslashes(json_encode(array()));
        }        

        if (in_array("weight_class", $column_order)) {
            $this->load->model('localisation/weight_class');
            $this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
            $wc_select = [];
            foreach ($this->data['weight_classes'] as $wc) {
                $wc_select[$wc['weight_class_id']] = $wc['title'];
            }
            $this->data['weight_class_select'] = addslashes(json_encode($wc_select));
        } else {
            $this->data['weight_class_select'] = addslashes(json_encode(array()));
        }

        if (!empty($data['filter_category'])){
            if ($data['filter_category'] == '*'){
                $this->data['category'] = '*';
            } else {

                $this->load->model('catalog/category');
                $category = $this->model_catalog_category->getCategory($data['filter_category']);

                if (!empty($category['path'])){
                    $this->data['category'] = $category['path'];
                } else {
                    $this->data['category'] = $category['name'];
                }
            }            
        }

        if (in_array("download", $column_order)) {
            $this->load->model('catalog/download');
            $this->data['downloads'] = $this->model_catalog_download->getDownloads();
        }

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $url = '';

        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
            }
        }
        if (isset($this->request->get['filter_sub_category'])) {
            $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sorts'] = [];
        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            $this->data['sorts'][$column] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=' . $attr['sort'] . $url);
        }

        $url = '';

        foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
            if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
                $url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
            }
        }
        if (isset($this->request->get['filter_sub_category'])) {
            $url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price_special'])) {
            $url .= '&filter_price_special=' . urlencode(html_entity_decode($this->request->get['filter_price_special'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        $this->data['filters'] = $filters;

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'catalog/product_list_ext.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function filter() {
        $this->load->model('catalog/filter');

        if (isset($this->request->get['filter_group_id'])) {
            $filter_group_id = $this->request->get['filter_group_id'];
        } else {
            $filter_group_id = 0;
        }

        $filter_data = [];

        $results = $this->model_catalog_filter->getFiltersByFilterGroupId($filter_group_id);

        foreach ($results as $result) {
            $filter_data[] = array(
                'filter_id'  => $result['filter_id'],
                'name'       => $result['name'],
                'group'      => $result['group']
            );
        }

        $this->response->setOutput(json_encode($filter_data));
    }

    public function category() {
        $this->load->model('catalog/product');

        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        $product_data = [];

        $results = $this->model_catalog_product->getProductsByCategoryId($category_id);

        foreach ($results as $result) {
            $product_data[] = array(
                'product_id' => $result['product_id'],
                'name'       => $result['name'],
                'model'      => $result['model']
            );
        }

        $this->response->setOutput(json_encode($product_data));
    }

    public function autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name']) ||
            isset($this->request->get['filter_model']) ||
            isset($this->request->get['filter_category']) ||
            isset($this->request->get['filter_seo']) ||
            isset($this->request->get['filter_location']) ||
            isset($this->request->get['filter_sku']) ||
            isset($this->request->get['filter_upc']) ||
            isset($this->request->get['filter_ean']) ||
            isset($this->request->get['filter_jan']) ||
            isset($this->request->get['filter_isbn']) ||
            isset($this->request->get['filter_mpn'])) {

            $this->load->model('catalog/product');
            $this->load->model('catalog/product_ext');
            $this->load->model('catalog/option');

            $filter_types = array('name', 'model', 'category', 'seo', 'location', 'sku', 'upc', 'ean', 'jan', 'isbn' ,'mpn');
            $filters = [];

            foreach($filter_types as $filter) {
                $filters[$filter] = (isset($this->request->get['filter_' . $filter])) ? $this->request->get['filter_' . $filter] : null;
            }
            $filters['sub_category'] = (isset($this->request->get['filter_sub_category'])) ? $this->request->get['filter_sub_category'] : $this->config->get('aqe_catalog_products_filter_sub_category');

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'start'               => 0,
                'limit'               => $limit
            );

            foreach($filters as $filter => $value) {
                $data['filter_' . $filter] = $value;
            }

            $results = $this->model_catalog_product_ext->getProducts($data);

            foreach ($results as $result) {
                $option_data = [];

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                foreach ($product_options as $product_option) {
                    $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                    if ($option_info) {
                        if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
                            $option_value_data = [];

                            foreach ($product_option['product_option_value'] as $product_option_value) {
                                $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

                                if ($option_value_info) {
                                    $option_value_data[] = array(
                                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                                        'option_value_id'         => $product_option_value['option_value_id'],
                                        'name'                    => $option_value_info['name'],
                                        'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                                        'price_prefix'            => $product_option_value['price_prefix']
                                    );
                                }
                            }

                            $option_data[] = array(
                                'product_option_id' => $product_option['product_option_id'],
                                'option_id'         => $product_option['option_id'],
                                'name'              => $option_info['name'],
                                'type'              => $option_info['type'],
                                'option_value'      => $option_value_data,
                                'required'          => $product_option['required']
                            );
                        } else {
                            $option_data[] = array(
                                'product_option_id' => $product_option['product_option_id'],
                                'option_id'         => $product_option['option_id'],
                                'name'              => $option_info['name'],
                                'type'              => $option_info['type'],
                                'option_value'      => $product_option['option_value'],
                                'required'          => $product_option['required']
                            );
                        }
                    }
                }

                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'seo'        => (isset($result['seo'])) ? $result['seo'] : '',
                    'sku'        => $result['sku'],
                    'upc'        => $result['upc'],
                    'ean'        => $result['ean'],
                    'jan'        => $result['jan'],
                    'isbn'       => $result['isbn'],
                    'mpn'        => $result['mpn'],
                    'location'   => $result['location'],
                    'model'      => $result['model'],
                    'option'     => $option_data,
                    'price'      => $result['price'],
                );
            }
        } else if (isset($this->request->get['filter_download'])) {
        }

        $this->response->setOutput(json_encode($json));
    }

    public function load_popup() {
        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateLoadPopup($this->request->post)) {
            $this->data = array_merge($this->data, $this->language->load('catalog/product'));

            $this->data['error_warning'] = '';
            list($this->data['parameter'], $this->data['product_id']) = explode("-", $this->request->post['id']);

            $this->data['token'] = $this->session->data['token'];

            $json["success"] = 1;

            switch ($this->data['parameter']) {
                case "category":
                    $this->load->model('catalog/category');
                    $this->data['categories'] = $this->model_catalog_category->getCategories();

                    foreach ($this->data['categories'] as &$category){
                        if (!$category['status']){
                            $category['name'] .= ' [выключена]';
                        }
                    }

                    $this->load->model('catalog/product');
                    $this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->data['product_id']);
                    $json['title'] = $this->language->get('entry_category');
                    break;
                case "store":
                    $this->load->model('setting/store');
                    $this->data['stores'] = $this->model_setting_store->getStores();
                    array_unshift($this->data['stores'], array("store_id" => 0, "name" => $this->config->get('config_name')));
                    $this->load->model('catalog/product');
                    $this->data['product_store'] = $this->model_catalog_product->getProductStores($this->data['product_id']);
                    $json['title'] = $this->language->get('entry_store');
                    break;
                case "filter":
                    $this->load->model('catalog/filter');
                    $data = array(
                        "sort" => "fgd.name"
                    );
                    $filter_groups = $this->model_catalog_filter->getFilterGroups($data);
                    $this->data['filters'] = [];
                    foreach ($filter_groups as $filter_group) {
                        $this->data['filters'] = array_merge($this->data['filters'], $this->model_catalog_filter->getFiltersByFilterGroupId($filter_group['filter_group_id']));
                    }
                    $this->load->model('catalog/product');
                    $this->data['product_filter'] = $this->model_catalog_product->getProductFilters($this->data['product_id']);
                    $json['title'] = $this->language->get('entry_filter');
                    break;
                case "download":
                    $this->load->model('catalog/download');
                    $this->data['downloads'] = $this->model_catalog_download->getDownloads();
                    $this->load->model('catalog/product');
                    $this->data['product_download'] = $this->model_catalog_product->getProductDownloads($this->data['product_id']);
                    $json['title'] = $this->language->get('entry_download');
                    break;
                case "attributes":
                    $this->load->model('localisation/language');
                    $this->load->model('catalog/attribute');

                    $this->data['languages'] = $this->model_localisation_language->getLanguages();
                    $this->load->model('catalog/product');
                    $product_attributes = $this->model_catalog_product->getProductAttributes($this->data['product_id']);

                    $this->data['product_attributes'] = [];

                    foreach ($product_attributes as $product_attribute) {
                        $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

                        if ($attribute_info) {
                            $this->data['product_attributes'][] = array(
                                'attribute_id'                  => $product_attribute['attribute_id'],
                                'name'                          => $attribute_info['name'],
                                'product_attribute_description' => $product_attribute['product_attribute_description']
                            );
                        }
                    }
                    break;
                case "discounts":
                    $this->load->model('sale/customer_group');
                    $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
                    $this->load->model('catalog/product');
                    $this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->data['product_id']);
                    break;
                case "images":
                    $this->load->model('catalog/product');
                    $product_images = $this->model_catalog_product->getProductImages($this->data['product_id']);
                    $this->data['product_images'] = [];
                    $this->load->model('tool/image');

                    foreach ($product_images as $product_image) {
                        $this->data['product_images'][] = array(
                            'image'      => $product_image,
                            'thumb'      => $this->model_tool_image->resize($product_image['image'], 100, 100),
                            'sort_order' => $product_image['sort_order']
                        );
                    }

                    $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
                    break;
                case "options":
                    $this->load->model('catalog/product');
                    $product_options = $this->model_catalog_product->getProductOptions($this->data['product_id']);
                    $this->data['product_options'] = [];

                    foreach ($product_options as $product_option) {
                        if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                            $product_option_value_data = [];

                            foreach ($product_option['product_option_value'] as $product_option_value) {
                                $product_option_value_data[] = array(
                                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                                    'option_value_id'         => $product_option_value['option_value_id'],
                                    'quantity'                => $product_option_value['quantity'],
                                    'subtract'                => $product_option_value['subtract'],
                                    'price'                   => $product_option_value['price'],
                                    'price_prefix'            => $product_option_value['price_prefix'],
                                    'points'                  => $product_option_value['points'],
                                    'points_prefix'           => $product_option_value['points_prefix'],
                                    'weight'                  => $product_option_value['weight'],
                                    'weight_prefix'           => $product_option_value['weight_prefix']
                                );
                            }

                            $this->data['product_options'][] = array(
                                'product_option_id'    => $product_option['product_option_id'],
                                'product_option_value' => $product_option_value_data,
                                'option_id'            => $product_option['option_id'],
                                'name'                 => $product_option['name'],
                                'type'                 => $product_option['type'],
                                'required'             => $product_option['required']
                            );
                        } else {
                            $this->data['product_options'][] = array(
                                'product_option_id' => $product_option['product_option_id'],
                                'option_id'         => $product_option['option_id'],
                                'name'              => $product_option['name'],
                                'type'              => $product_option['type'],
                                'option_value'      => $product_option['option_value'],
                                'required'          => $product_option['required']
                            );
                        }
                    }

                    $this->load->model('catalog/option');
                    $this->data['option_values'] = [];

                    foreach ($product_options as $product_option) {
                        if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                            if (!isset($this->data['option_values'][$product_option['option_id']])) {
                                $this->data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
                            }
                        }
                    }
                    break;
                case "profiles":
                    $this->load->model('catalog/profile');
                    $this->data['profiles'] = $this->model_catalog_profile->getProfiles();
                    $this->load->model('sale/customer_group');
                    $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
                    $this->load->model('catalog/product');
                    $this->data['product_profiles'] = $this->model_catalog_product->getProfiles($this->data['product_id']);
                    break;
                case "specials":
                    $this->load->model('sale/customer_group');
                    $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
                    $this->load->model('catalog/product');
                    $this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->data['product_id']);
                    break;
                case "filters":
                    $this->load->model('catalog/filter');
                    $this->data['filter_groups'] = $this->model_catalog_filter->getFilterGroups(array());
                    $this->load->model('catalog/product');
                    $filters = $this->model_catalog_product->getProductFilters($this->data['product_id']);
                    $this->data['product_filters'] = [];

                    foreach ($filters as $filter_id) {
                        $filter_info = $this->model_catalog_filter->getFilter($filter_id);

                        if ($filter_info) {
                            $this->data['product_filters'][] = array(
                                'filter_id' => $filter_info['filter_id'],
                                'name'      => $filter_info['name'],
                                'group'     => $filter_info['group']
                            );
                        }
                    }
                    break;
                case "related":
                    $this->load->model('catalog/category');
                    $this->data['categories'] = $this->model_catalog_category->getCategories(['filter_status' => 1]);
                    $this->load->model('catalog/product');
                    $products = $this->model_catalog_product->getProductRelated($this->data['product_id']);
                    $this->data['product_related'] = [];

                    foreach ($products as $product_id) {
                        $related_info = $this->model_catalog_product->getProduct($product_id);

                        if ($related_info) {
                            $this->data['product_related'][] = array(
                                'product_id' => $related_info['product_id'],
                                'name'       => $related_info['name'],
                                'model'      => $related_info['model']
                            );
                        }
                    }
                    break;
                case "descriptions":
                    $this->load->model('localisation/language');
                    $this->data['languages'] = $this->model_localisation_language->getLanguages();
                    $this->load->model('catalog/product');
                    $this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->data['product_id']);
                    break;
                default:
                    $json["success"] = 0;
                    $json['error'] = $this->language->get('error_load_popup');
                    break;
            }
            $json['title'] = $this->language->get('text_' . $this->data['parameter']);
        } else {
            $json['error'] = $this->language->get('error_load_popup');
        }

        $this->template = 'catalog/product_quick_form.tpl';

        $json['popup'] = $this->render();

        $this->response->setOutput(json_encode($json));
    }

    public function load_data() {
        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateLoadData($this->request->post)) {
            $this->load->model('localisation/language');
            $languages = $this->model_localisation_language->getLanguages();
            foreach($languages as $lang) {
                $json['languages'][$lang['language_id']] = $lang['name'];
            }
            $json['languages']['selected'] = $this->request->post['lang_id'];
            list($column, $id) = explode("-", $this->request->post['id']);

            $this->load->model('catalog/product');
            $result = $this->model_catalog_product->getProductDescriptions($id);
            $json['data'] = [];
            switch ($column) {
                case 'name':
                    foreach($result as $lang => $desc) {
                        $json['data'][$lang] = html_entity_decode($desc['name']);
                    }
                    break;
                case 'tag':
                    foreach($result as $lang => $desc) {
                        $json['data'][$lang] = html_entity_decode($desc['tag']);
                    }
                    break;
                default:
                    $this->language->load('catalog/product');
                    $json['error'] = $this->language->get('error_load_data');
                    break;
            }
        } else {
            $this->language->load('catalog/product');
            $json['error'] = $this->language->get('error_load_data');
        }

        $this->response->setOutput(json_encode($json));
    }

    public function refresh_data() {
        $this->language->load('catalog/product');

        $this->load->model('catalog/product');
        $this->load->model('catalog/product_ext');

        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateRefreshData($this->request->post)) {
            list($column, $id) = explode("-", $this->request->post['id']);
            switch ($column) {
                case 'filter':
                    $this->load->model('catalog/product');
                    $this->load->model('catalog/filter');
                    $filters = $this->model_catalog_product->getProductFilters($id);

                    $product_filters = [];

                    foreach ($filters as $filter_id) {
                        $f = $this->model_catalog_filter->getFilter($filter_id);
                        $product_filters[] = strip_tags(html_entity_decode($f['group'] . ' &gt; ' . $f['name'], ENT_QUOTES, 'UTF-8'));
                    }
                    $json['value'] = implode("<br/>", $product_filters);
                    break;
                case 'price':
                    $this->load->model('catalog/product');
                    $special = false;

                    $product = $this->model_catalog_product->getProduct($id);
                    $product_specials = $this->model_catalog_product->getProductSpecials($id);

                    foreach ($product_specials  as $product_special) {
                        if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                            $special = $product_special['price'];
                            break;
                        }
                    }
                    if ($special)
                        $ret = '<span style="text-decoration:line-through">' . sprintf("%.4f",round((float)$product['price'], 4)) . '</span><br/><span style="color:#b00;">' . $special . '</span>';
                    else
                        $ret = sprintf("%.4f",round((float)$product['price'], 4));
                    $json['value'] = $ret;
                    break;
                default:
                    $json['value'] = "";
                    break;
            }
            $json['success'] = 1;
        } else {
            $json['error'] = $this->error['warning'];
        }

        $this->response->setOutput(json_encode($json));
    }

    public function quick_update() {
        $this->language->load('catalog/product');

        $this->load->model('catalog/product');
        $this->load->model('catalog/product_ext');

        $json = [];

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateUpdateData($this->request->post)) {
            list($column, $id) = explode("-", $this->request->post['id']);
            $value = $this->request->post['new'];
            if (in_array($column, array('name', 'tag'))) {
                $lang_id = $this->request->post['lang_id'];
            } else {
                $lang_id = null;
            }
            $alt = isset($this->request->post['alt']) ? $this->request->post['alt'] : "";
            if ($column == "requires_shipping") {
                $column = "shipping";
            }
            $result = $this->model_catalog_product_ext->quickEditProduct($id, $column, $value, $lang_id, $this->request->post);

            if ($result) {
                $json['success'] = $this->language->get('text_success');
                if (in_array($column, array('model', 'sku', 'upc', 'asin', 'location', 'seo', 'attributes', 'discounts', 'images', 'options', 'profiles', 'related', 'specials', 'descriptions')))
                    $json['value'] = $value;
                else if (in_array($column, array('sort_order', 'points', 'minimum')))
                    $json['value'] = (int)$value;
                else if (in_array($column, array('subtract', 'shipping')))
                    $json['value'] = ((int)$value) ? $this->language->get('text_yes') : $this->language->get('text_no');
                else if ($column == 'status') {
                    if ((int)$value || !$this->config->get('aqe_highlight_status')) {
                        $json['value'] = ((int)$value) ? $this->language->get('text_enabled') : $this->language->get('text_disabled');
                    } else {
                        $json['value'] = ((int)$value) ? $this->language->get('text_enabled') : '<span style="color:#cf4a61;">' . $this->language->get('text_disabled') . '</span>';
                    }
                } else if ($column == 'fill_from_amazon') {
                    if ((int)$value || !$this->config->get('aqe_highlight_status')) {
                        $json['value'] = ((int)$value) ? $this->language->get('text_yes') : $this->language->get('text_no');
                    } else {
                        $json['value'] = ((int)$value) ? $this->language->get('text_yes') : '<span style="color:#cf4a61;">' . $this->language->get('text_no') . '</span>';
                    }
                } else if ($column == 'image') {
                    $this->load->model('tool/image');
                    $image = $this->model_tool_image->resize($value, $this->config->get('aqe_list_view_image_width'), $this->config->get('aqe_list_view_image_height'));
                    $json['value'] = '<img src="' . $image . '" data-id="' . $id . '" data-image="' . $value . '" alt="' . $alt . '" style="padding: 1px; border: 1px solid #DDDDDD;" />';
                } else if ($column == 'tax_class') {
                    $this->load->model('localisation/tax_class');
                    $tax_class = $this->model_localisation_tax_class->getTaxClass((int)$value);
                    if ($tax_class)
                        $json['value'] = $tax_class['title'];
                    else
                        $json['value'] = '';
                } else if ($column == 'stock_status') {
                    $this->load->model('localisation/stock_status');
                    $stock_status = $this->model_localisation_stock_status->getStockStatus((int)$value);
                    if ($stock_status)
                        $json['value'] = $stock_status['name'];
                    else
                        $json['value'] = '';
                } else if ($column == 'product_group') {
                    $this->load->model('localisation/product_groups');
                    $product_group = $this->model_localisation_product_groups->getProductGroup((int)$value);
                    if ($product_group)
                        $json['value'] = '<small style="padding:5px 7px; border-radius:4px; color:#' . $product_group['product_group_text_color'] . '; background-color:#' . $product_group['product_group_bg_color'] . '"><i class="fa ' . $product_group['product_group_fa_icon'] . '" ></i></small>';
                    else
                        $json['value'] = '<small style="padding:5px 7px; border-radius:4px; color:#FFF; background-color:#000"><i class="fa fa-times-circle" ></i> </small>';
                } else if ($column == 'length_class') {
                    $this->load->model('localisation/length_class');
                    $length_class = $this->model_localisation_length_class->getLengthClass((int)$value);
                    if ($length_class)
                        $json['value'] = $length_class['title'];
                    else
                        $json['value'] = '';
                } else if ($column == 'weight_class') {
                    $this->load->model('localisation/weight_class');
                    $weight_class = $this->model_localisation_weight_class->getWeightClass((int)$value);
                    if ($weight_class)
                        $json['value'] = $weight_class['title'];
                    else
                        $json['value'] = '';
                } else if ($column == 'manufacturer') {
                    $this->load->model('catalog/manufacturer');
                    $manufacturer = $this->model_catalog_manufacturer->getManufacturer((int)$value);
                    if ($manufacturer)
                        $json['value'] = $manufacturer['name'];
                    else
                        $json['value'] = '';
                } else if ($column == 'quantity') {                    
                    $value = (int)$value;
                    if ($value <= 0)
                        $ret = '<span style="color: #cf4a61;">' . (int)$value . '</span>';
                    elseif ($value <= 5)
                        $ret = '<span style="color: #FFA500;">' . (int)$value . '</span>';
                    else
                        $ret = '<span style="color: #008000;">' . (int)$value . '</span>';
                    $json['value'] = $ret;
                } else if (in_array($column, array('weight', 'length', 'width', 'height')))
                    $json['value'] = sprintf("%.4f",round((float)$value, 4));
                else if(in_array($column, array('name', 'tag'))) {
                    if ($lang_id == $this->config->get('config_language_id'))
                        $json['value'] = $value;
                    else
                        $json['value'] = $this->request->post['old'];
                } else if($column == 'category') {
                    if (isset($this->request->post['p_c'])) {
                        $this->request->post['p_c'] = (array)$this->request->post['p_c'];

                        $this->load->model('catalog/category');
                        $categories = $this->model_catalog_category->getCategories(['filter_status' => 1]);

                        $category_names = [];

                        foreach ($categories as $category) {
                            if (in_array($category['category_id'], $this->request->post['p_c']))
                                $category_names[] = $category['name'];
                        }
                        $json['value'] = implode("<br>", $category_names);
                    } else {
                        $json['value'] = "";
                    }
                } else if($column == 'store') {
                    if (isset($this->request->post['p_s'])) {
                        $this->request->post['p_s'] = (array)$this->request->post['p_s'];

                        $this->load->model('setting/store');
                        $stores = $this->model_setting_store->getStores();
                        array_unshift($stores, array("store_id" => 0, "name" => $this->config->get('config_name')));

                        $product_stores = [];

                        foreach ($stores as $store) {
                            if (in_array($store['store_id'], $this->request->post['p_s']))
                                $product_stores[] = $store['name'];
                        }
                        $json['value'] = implode("<br>", $product_stores);
                    } else {
                        $json['value'] = "";
                    }
                } else if($column == 'filter') {
                    if (isset($this->request->post['p_f'])) {
                        $this->request->post['p_f'] = (array)$this->request->post['p_f'];

                        $this->load->model('catalog/filter');
                        $filters = $this->model_catalog_filter->getFilters(array());

                        $product_filters = [];

                        foreach ($filters as $filter) {
                            if (in_array($filter['filter_id'], $this->request->post['p_f']))
                                $product_filters[] = strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'));
                        }
                        $json['value'] = implode("<br>", $product_filters);
                    } else {
                        $json['value'] = "";
                    }
                } else if($column == 'download') {
                    if (isset($this->request->post['p_d'])) {
                        $this->request->post['p_d'] = (array)$this->request->post['p_d'];

                        $this->load->model('catalog/download');
                        $downloads = $this->model_catalog_download->getDownloads();

                        $product_downloads = [];

                        foreach ($downloads as $download) {
                            if (in_array($download['download_id'], $this->request->post['p_d']))
                                $product_downloads[] = $download['name'];
                        }
                        $json['value'] = implode("<br>", $product_downloads);
                    } else {
                        $json['value'] = "";
                    }
                } else if($column == 'price') {
                    $special = false;

                    $product_specials = $this->model_catalog_product->getProductSpecials($id);

                    foreach ($product_specials  as $product_special) {
                        if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                            $special = $product_special['price'];
                            break;
                        }
                    }
                    if ($special)
                        $ret = '<span style="text-decoration:line-through">' . sprintf("%.4f",round((float)$value, 4)) . '</span><br/><span style="color:#b00;">' . $special . '</span>';
                    else
                        $ret = sprintf("%.4f",round((float)$value, 4));
                    $json['value'] = $ret;
                } else
                    $json['value'] = $value;
            } else
                $json['error'] = $this->language->get('error_update');
        } else {
            $json['error'] = $this->error['warning'];
        }

        $this->response->setOutput(json_encode($json));
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateLoadPopup($data) {
        if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($data['id']) || strpos($data['id'], "-") === false) {
            $this->error['warning'] = $this->language->get('error_update');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateLoadData($data) {
        if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($data['id']) || strpos($data['id'], "-") === false) {
            $this->error['warning'] = $this->language->get('error_update');
        }

        list($column, $id) = explode("-", $data['id']);

        if (!isset($data['lang_id'])) {
            $this->error['warning'] = $this->language->get('error_update');
        }

        if ($column != "name" && $column != "tag") {
            $this->error['warning'] = $this->language->get('error_update');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateUpdateData($data) {
        if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($data['id']) || strpos($data['id'], "-") === false) {
            $this->error['warning'] = $this->language->get('error_update');
            return false;
        }

        list($column, $id) = explode("-", $data['id']);

        if (!isset($data['old'])) {
            $this->error['warning'] = $this->language->get('error_update');
        }

        if (!isset($data['new'])) {
            $this->error['warning'] = $this->language->get('error_update');
        }

        if ($column == "model" && ((strlen(utf8_decode($data['new'])) < 1) || (strlen(utf8_decode($data['new'])) > 64))) {
            $this->error['warning'] = $this->language->get('error_model');
        }

        if ($column == "name" && !isset($data['lang_id'])) {
            $this->error['warning'] = $this->language->get('error_update');
        }

        if ($column == "seo") {
            $keyword = utf8_decode($data['new']);
            if ($this->model_catalog_product_ext->urlAliasExists($id, $keyword)) {
                $this->error['warning'] = $this->language->get('error_duplicate_seo_keyword');
            }
        }

        if (in_array($column, array("category"))) {
            switch ($column) {
                case 'category':
                    break;
                default:
                    $this->error['warning'] = $this->language->get('error_update');
                    break;
            }

            if (!isset($data['p_id'])) {
                $this->error['warning'] = $this->language->get('error_update');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateRefreshData($data) {
        if (!$this->user->hasPermission('modify', 'catalog/product_ext')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($data['id']) || strpos($data['id'], "-") === false) {
            $this->error['warning'] = $this->language->get('error_update');
            return false;
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>

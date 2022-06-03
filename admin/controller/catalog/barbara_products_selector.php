<?php
class ControllerCatalogbarbaraProductsSelector extends Controller {
    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('catalog/barbara_products_selector');
    }

    public function index() {
        $this->load->model('catalog/product');

        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

        $data['title'] = $this->language->get('heading_title');

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $url = '';

/*        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
*/
        $data = array_merge($data, $this->_setData(array(
            'button_cancel',
            'button_select',
            'button_filter',
            'entry_filter_name',
            'action_filter' => $this->makeUrl('catalog/barbara_products_selector', $url),
            'column_id',
            'column_sku',
            'column_name',
            'column_price',
            'text_no_products',
            'text_products_selected',
            'dialog_id' => isset($this->request->cookie['dialogID']) ? $this->request->cookie['dialogID'] : '',
            'field_id'  => isset($this->request->cookie['fieldID']) ? $this->request->cookie['fieldID'] : ''
        )));

        $parameters = array();
        $data = array_merge($data, $this->_updateData(array(
            'filter_name',
        ), $parameters));
        $filter = trim(isset($this->request->cookie['filter']) ? $this->request->cookie['filter'] : '');
        $data['filter'] = $filter;

        $limit = $this->config->get('config_limit_admin');
        $result = array();
        $products_data = array(
            'sort' => 'pd.name',
            'order' => 'desc',
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );
        if ($filter != '') {
            $products_data['filter_name'] = $filter;
        }

        $products = $this->model_catalog_product->getProducts($products_data);
        foreach ($products as $product) {
            $result[] = array(
                'product_id' => $product['product_id'],
                'sku' => $product['sku'],
                'name' => $product['name'],
                'price' => $product['price']
            );
        }
        $data['products'] = $result;

        $url = '';

        $pagination = new Pagination();
        $pagination->total = $this->model_catalog_product->getTotalProducts($products_data);
        $pagination->page = $page;
        $pagination->limit = $limit;
        //$pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->makeUrl('catalog/barbara_products_selector', $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $this->response->setOutput($this->load->view('catalog/barbara_products_selector.tpl', $data));
    }

    public function table() {
        $data = $this->_setData(array(
            'column_id',
            'column_sku',
            'column_name',
            'column_price',
            'button_delete',
            'text_no_products',
            'text_delete',
            'text_edit'
        ));
        $this->load->model('catalog/barbara_product');

        $selected = explode(',', trim(isset($this->request->get['selected']) ? $this->request->get['selected'] : '', ','));
        $products = $this->model_catalog_barbara_product->getProducts($selected);

        $result = array();
        foreach ($products as $product) {
            $result[] = array(
                'product_id'	=> $product['product_id'],
                'sku' 				=> $product['sku'],
                'name' 				=> $product['name'],
                'price' 			=> $product['price'],
                'href' 				=> $this->makeUrl('catalog/product/edit', '&product_id=' . $product['product_id'])
            );
        }
        $data['selected'] = $result;
        $data['field'] = isset($this->request->get['field']) ? $this->request->get['field'] : null;

        $this->response->setOutput($this->load->view('catalog/barbara_products_selector_table.tpl', $data));
    }

    public function table_by_category() {
        $data = $this->_setData(array(
            'column_id',
            'column_sku',
            'column_name',
            'column_price',
            'text_no_products',
            'text_delete',
            'text_edit',
            'button_delete'
        ));
        $this->load->model('catalog/product');

        $category_id = isset($this->request->get['category_id']) ? $this->request->get['category_id'] : -1;
        $products = $this->model_catalog_product->getProductsByCategoryId($category_id);

        $result = array();
        foreach ($products as $product) {
            $result[] = array(
                'product_id'	=> $product['product_id'],
                'sku' 				=> $product['sku'],
                'name' 				=> $product['name'],
                'price' 			=> $product['price'],
                'href' 				=> $this->makeUrl('catalog/product/update', '&product_id=' . $product['product_id'])
            );
        }
        $data['selected'] = $result;
        $data['field'] = isset($this->request->get['field']) ? $this->request->get['field'] : null;

        $this->response->setOutput($this->load->view('catalog/barbara_products_selector_table.tpl', $data));
    }

    protected function _setData($values) {
        $data = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $data[$value] = $this->language->get($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    protected function _updateData($keys, $info = array()) {
        $data = array();
        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } elseif (isset($info[$key])) {
                $data[$key] = $info[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }
        return $data;
    }

    protected function makeUrl($route, $url = ''){
        if (isset($this->session->data['token'])){
            return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
        } else {
            return str_replace('&amp;', '&', $this->url->link($route, $url, 'SSL'));
        }
    }
}
?>
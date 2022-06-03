<?php
class ControllerCatalogbarbaraCategoriesSelector extends Controller {
    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('catalog/category');
        $this->load->language('catalog/barbara_categories_selector');
    }

    public function index() {
        $data['title'] = $this->language->get('heading_title');

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data = array_merge($data, $this->_setData(array(
            'button_select',
            'button_cancel',
            'button_filter',
            'entry_filter_name',
            'action_filter' => $this->makeUrl('catalog/barbara_categories_selector/index'),
            'column_name',
            'text_no_categories',
            'text_categories_selected',
            'dialog_id' => isset($this->request->cookie['dialogID']) ? $this->request->cookie['dialogID'] : '',
            'field_id'  => isset($this->request->cookie['fieldID']) ? $this->request->cookie['fieldID'] : ''
        )));

        $parameters = array();
        $data = array_merge($data, $this->_updateData(array(
            'filter_name',
        ), $parameters));
        $filter = trim(isset($_COOKIE['filter']) ? $this->request->cookie['filter'] : '');
        $data['filter'] = $filter;
        $data['selected'] = explode(',', isset($this->request->cookie['selected']) ? $this->request->cookie['selected'] : '');

        if ($filter != '') {
            $filter = mb_strtolower($filter, 'utf-8');
            $categories = $this->model_catalog_category->getCategories(0);
            $result = array();
            foreach ($categories as $category) {
                if (mb_strpos(mb_strtolower($category['name'], 'utf-8'), $filter, 0, 'utf-8') !== false) {
                    $result[] = $category;
                }
            }
            $data['categories'] = $result;
        } else {
            $filter_data = array(
              'sort'        => 'name',
              'order'       => 'ASC'
            );

            $data['categories'] = $this->model_catalog_category->getCategories($filter_data);
        }

        $this->response->setOutput($this->load->view('catalog/barbara_categories_selector.tpl', $data));
    }

    public function table() {
        $data = $this->_setData(array(
            'column_name',
            'button_delete',
            'text_no_categories',
            'text_delete',
            'text_edit'
        ));

        $selected = explode(',', trim(isset($this->request->get['selected']) ? $this->request->get['selected'] : '', ','));

        $filter_data = array(
          'sort'        => 'name',
          'order'       => 'ASC'
        );

        $categories = $this->model_catalog_category->getCategories($filter_data);
        $result = array();
        foreach ($categories as $category) {
            if (in_array($category['category_id'], $selected)) {
                $result[] = array_merge($category, array(
                    'href' => $this->makeUrl('catalog/category/edit', '&category_id=' . $category['category_id'])
                ));
            }
        }
        $data['selected'] = $result;
        $data['field'] = isset($this->request->get['field']) ? $this->request->get['field'] : null;

        $this->response->setOutput($this->load->view('catalog/barbara_categories_selector_table.tpl', $data));
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
        return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
    }
}
?>
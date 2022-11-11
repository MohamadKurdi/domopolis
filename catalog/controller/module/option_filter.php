<?php

class ControllerModuleOptionFilter extends Controller {

    protected function index($setting) {
        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        $category_id = end($parts);

        $this->load->model('catalog/category');


        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $this->load->model('tool/option_filter');
            $this->language->load('module/option_filter');

            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['button_action'] = $this->language->get('button_action');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $this->data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

            if (isset($this->request->get['option_filter'])) {
                $this->data['option_filter_category'] = explode(',', $this->request->get['option_filter']);
            } else {
                $this->data['option_filter_category'] = array();
            }


            $this->load->model('catalog/product');

            $this->data['option_filter_groups'] = array();


            $option_filter_data = $this->model_tool_option_filter->getOptionFilter($category_id);

          
            if ($option_filter_data) {
                foreach ($option_filter_data as $key => $filter_group) {
                    $option_filter_data = array();

                    $this->data['option_filter_groups'][] = array(
                        'option_name' => $key,
                        'option_filter' => $filter_group
                           
                    );
                }

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/option_filter.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/module/option_filter.tpl';
                } else {
                    $this->template = 'default/template/module/option_filter.tpl';
                }
                $this->render();
            }
        }
    }

}

?>

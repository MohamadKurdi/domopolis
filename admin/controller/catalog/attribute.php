<?php

class ControllerCatalogAttribute extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('catalog/attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/attribute');

        $this->getList();
    }

    public function insert()
    {
        $this->language->load('catalog/attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/attribute');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_attribute->addAttribute($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function update()
    {
        $this->language->load('catalog/attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/attribute');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_attribute->editAttribute($this->request->get['attribute_id'], $this->request->post);
            $this->model_catalog_attribute->updateAttributeImageValues($this->request->get['attribute_id'], $this->request->post['image'], $this->request->post['information_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['attribute_id'])) {
                $url .= '&attribute_id=' . $this->request->get['attribute_id'];
            }

            $this->redirect($this->url->link('catalog/attribute/update', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->language->load('catalog/attribute');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/attribute');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $attribute_id) {
                $this->model_catalog_attribute->deleteAttribute($attribute_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function synonymsbyai(){
        $request = $this->request->post['request'];

        if ($result = $this->openaiAdaptor->translateAndSynonym($request)){
            $this->response->setOutput($result);
        } else {
            $this->response->setOutput('');
        }
    }

    public function value_replace(){
        $this->load->model('catalog/attribute');

        if ($this->user->hasPermission('modify', 'catalog/attribute')) {
            $this->model_catalog_attribute->replaceAttributeValue($this->request->post['value_from'], $this->request->post['value_to']);
        }
    }

    public function value_delete(){
        $this->load->model('catalog/attribute');

        if ($this->user->hasPermission('modify', 'catalog/attribute')) {
            $this->model_catalog_attribute->deleteAttributeValue($this->request->post['value_from']);
        }
    }

    public function values(){
        $this->language->load('catalog/attribute');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model(['catalog/attribute', 'catalog/product']);

        $this->data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $filter_data = array(
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit'),
            'language_id' => $this->request->get['language_id']
        );

        $this->data['attribute_description'] = $this->model_catalog_attribute->getAttributeDescriptions($this->request->get['attribute_id']);
        if (!empty($this->request->get['attribute_id'])){
            $this->data['heading_title'] = $this->data['heading_title'] . ' / ' . $this->data['attribute_description'][$this->config->get('config_language_id')]['name'];
        }

        $values_total = $this->model_catalog_attribute->getTotalAttributeValues($this->request->get['attribute_id'], $filter_data);
        $results = $this->model_catalog_attribute->getAttributeValues($this->request->get['attribute_id'], $filter_data);

        $this->data['attribute_values'] = [];
        foreach ($results as $result){
            $products = [];
            $product_count = 0;
            $exploded = explode(',', $result['products']);
            if ($exploded){
                $product_count = count($exploded);
                $exploded = array_slice($exploded, 0 , 3);

                foreach ($exploded as $product_id){
                    $product = $this->model_catalog_product->getProduct($product_id);

                    if ($product){
                        $products[] = [
                            'name' => $product['name'],
                            'asin' => $product['asin'],
                            'product_id' => $product['product_id'],
                            'view' => HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $product['product_id'],
                            'edit' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
                        ];
                    }
                }
            }

            $this->data['attribute_values'][] = [
                'text' => $result['text'],
                'product_count' => $product_count,
                'products' => $products
            ];
        }

        $pagination = new Pagination();
        $pagination->total = $values_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/attribute/values', 'token=' . $this->session->data['token'] . '&attribute_id=' . $this->request->get['attribute_id'] . '&language_id=' . $this->request->get['language_id'] . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        $this->data['token'] = $this->session->data['token'];

        $this->template = 'catalog/attribute_values.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());

    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'ad.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url),
            'separator' => ' :: '
        );

        $this->data['insert'] = $this->url->link('catalog/attribute/insert', 'token=' . $this->session->data['token'] . $url);
        $this->data['delete'] = $this->url->link('catalog/attribute/delete', 'token=' . $this->session->data['token'] . $url);

        $this->data['attributes'] = array();

        $data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' => $this->config->get('config_admin_limit')
        );

        $attribute_total = $this->model_catalog_attribute->getTotalAttributes();
        $results = $this->model_catalog_attribute->getAttributes($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('catalog/attribute/update', 'token=' . $this->session->data['token'] . '&attribute_id=' . $result['attribute_id'] . $url, 'SSL')
            );

            $values_editor = [];
            foreach ($this->registry->get('languages') as $language_id => $language){
                $values_editor[$language_id] = $this->url->link('catalog/attribute/values', 'token=' . $this->session->data['token'] . '&attribute_id=' . $result['attribute_id'] . '&language_id=' . $language['language_id'] , 'SSL');
            }

            $this->data['attributes'][] = array(
                'attribute_id' => $result['attribute_id'],
                'name' => $result['name'],
                'descriptions' => $this->config->get('config_enable_amazon_specific_modes')?$this->model_catalog_attribute->getAttributeDescriptions($result['attribute_id']):[],
                'dimension_type' => $result['dimension_type'],
                'attribute_group' => $result['attribute_group'],
                'count' => $this->model_catalog_attribute->getTotalAttributeValues($result['attribute_id'], ['language_id' => $this->config->get('config_language_id')]),
                'values' => $this->model_catalog_attribute->getSomeAttributesValues($result['attribute_id']),
                'values_native' => $this->config->get('config_enable_amazon_specific_modes')?$this->model_catalog_attribute->getSomeAttributesValues($result['attribute_id'], $this->config->get('config_rainforest_source_language_id')):[],
                'sort_order' => $result['sort_order'],
                'selected' => isset($this->request->post['selected']) && in_array($result['attribute_id'], $this->request->post['selected']),
                'values_editor' => $values_editor,
                'action' => $action
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_attribute_group'] = $this->language->get('column_attribute_group');
        $this->data['column_sort_order'] = $this->language->get('column_sort_order');
        $this->data['column_action'] = $this->language->get('column_action');

        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');

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

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['sort_name'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . '&sort=ad.name' . $url);
        $this->data['sort_attribute_group'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . '&sort=attribute_group' . $url);
        $this->data['sort_sort_order'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . '&sort=a.sort_order' . $url);
        $this->data['sort_dimension_type'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . '&sort=a.dimension_type' . $url);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $attribute_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'catalog/attribute_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function getForm()
    {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_attribute_group'] = $this->language->get('entry_attribute_group');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = array();
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['attribute_id'])) {
            $this->data['action'] = $this->url->link('catalog/attribute/insert', 'token=' . $this->session->data['token'] . $url);
        } else {
            $this->data['action'] = $this->url->link('catalog/attribute/update', 'token=' . $this->session->data['token'] . '&attribute_id=' . $this->request->get['attribute_id'] . $url);
        }

        $this->data['cancel'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'] . $url);

        if (isset($this->request->get['attribute_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($this->request->get['attribute_id']);
        }

        $this->load->model('localisation/language');
        $this->data['languages'] = $this->registry->get('languages');

        if (isset($this->request->post['attribute_description'])) {
            $this->data['attribute_description'] = $this->request->post['attribute_description'];
        } elseif (isset($this->request->get['attribute_id'])) {
            $this->data['attribute_description'] = $this->model_catalog_attribute->getAttributeDescriptions($this->request->get['attribute_id']);
        } else {
            $this->data['attribute_description'] = array();
        }

        if (isset($this->request->post['attribute_variants'])) {
            $this->data['attribute_variants'] = $this->request->post['attribute_variants'];
        } elseif (isset($this->request->get['attribute_id'])) {
            $this->data['attribute_variants'] = $this->model_catalog_attribute->getAttributeVariants($this->request->get['attribute_id']);
        } else {
            $this->data['attribute_variants'] = [];
        }

        if (!empty($this->request->get['attribute_id'])){
            $this->data['heading_title'] = $this->data['heading_title'] . ' / ' . $this->data['attribute_description'][$this->config->get('config_language_id')]['name'];
        }

        $this->data['openai_attribute_synonyms_requests'] = [];

        foreach ($this->data['languages'] as $language){
            if (isset($this->data['attribute_description'][$language['language_id']])){
                if ($this->config->get('config_openai_translate_and_synonym_query_' . $language['code'])){
                    ${'config_openai_translate_and_synonym_query_' . $language['code']} = prepareEOLArray($this->config->get('config_openai_translate_and_synonym_query_' . $language['code']));
                    $this->data['openai_attribute_synonyms_requests'][$language['code']] = [];

                    foreach (${'config_openai_translate_and_synonym_query_' . $language['code']} as $line){
                        $this->data['openai_attribute_synonyms_requests'][$language['code']][] = sprintf($line, $this->data['attribute_description'][$language['language_id']]['name'], mb_strtoupper($language['code']));
                    }
                }
            }
        }

        if (isset($this->request->post['attribute_group_id'])) {
            $this->data['attribute_group_id'] = $this->request->post['attribute_group_id'];
        } elseif (!empty($attribute_info)) {
            $this->data['attribute_group_id'] = $attribute_info['attribute_group_id'];
        } else {
            $this->data['attribute_group_id'] = '';
        }

        $this->load->model('catalog/attribute_group');

        $this->data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

        if (isset($this->request->post['dimension_type'])) {
            $this->data['dimension_type'] = $this->request->post['dimension_type'];
        } elseif (!empty($attribute_info)) {
            $this->data['dimension_type'] = $attribute_info['dimension_type'];
        } else {
            $this->data['dimension_type'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $this->data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($attribute_info)) {
            $this->data['sort_order'] = $attribute_info['sort_order'];
        } else {
            $this->data['sort_order'] = '';
        }

        $this->load->model('tool/image');
        $this->data['token'] = $this->session->data['token'];
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

        if ($this->config->get('config_enable_attributes_values_logic')) {
            $attributeValueArray = $this->model_catalog_attribute->getAttributeValues($this->request->get['attribute_id']);
            $attributeImages = $this->model_catalog_attribute->getAttributeImagesByAttributeId($this->request->get['attribute_id']);
            $attributeInformations = $this->model_catalog_attribute->getAttributeInformationByAttributeId($this->request->get['attribute_id']);

            foreach ($attributeValueArray as $k => $v) {
                $image = 'no_image.jpg';
                $imageData = false;
                if (array_key_exists($v['text'], $attributeImages)) {
                    $image = $attributeImages[$v['text']];
                    $imageData = true;
                }
                $attributeValueArray[$k]['thumb'] = $this->model_tool_image->resize($image, 100, 100);
                $attributeValueArray[$k]['image'] = $imageData ? $image : '';
            }

            foreach ($attributeValueArray as $k => $v) {
                if (array_key_exists($v['text'], $attributeInformations)) {
                    $attributeValueArray[$k]['information_id'] = $attributeInformations[$v['text']];
                } else {
                    $attributeValueArray[$k]['information_id'] = 0;
                }
            }

            $this->data['attribute_values'] = $attributeValueArray;
        }

        $this->data['rand_attribute_values'] = [];
        $this->data['rand_attribute_values2'] = [];

        foreach ($this->model_catalog_attribute->getRandAttributesValues($this->request->get['attribute_id']) as $k => $v) {
            $this->data['rand_attribute_values'][] = $v['text'];
        }

        foreach ($this->model_catalog_attribute->getRandOriginalAttributesValues($this->request->get['attribute_id']) as $k => $v) {
            $this->data['rand_attribute_values2'][] = $v['text'];
        }

        $this->load->model('catalog/information');
        $this->data['informations'] = $this->model_catalog_information->getInformations();

        $this->template = 'catalog/attribute_form';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['attribute_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('catalog/product');

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/attribute');

            $data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 20
            );

            $json = array();

            $results = $this->model_catalog_attribute->getAttributes($data);

            foreach ($results as $result) {
                $json[] = array(
                    'attribute_id' => $result['attribute_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'attribute_group' => $result['attribute_group']
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->setOutput(json_encode($json));
    }
}
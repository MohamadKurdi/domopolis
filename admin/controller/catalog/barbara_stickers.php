<?php
class ControllerCatalogbarbaraStickers extends Controller {
    private $_types = array();
		private $error = array(); 
    
    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('catalog/barbara_stickers');
        $this->load->model('catalog/barbara_stickers');
        $this->load->model('localisation/language');
        $this->load->model('tool/image');
        $this->document->addStyle('view/stylesheet/adminbarbara.css');
        $this->_types = array(
            '0' => $this->language->get('text_type_basic'),
            '1' => $this->language->get('text_type_special'),
            '2' => $this->language->get('text_type_discount')
        );
   }

    public function settings() {
        $this->getList();
    }

    protected function getList() {
        $this->load->model('catalog/category');

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('common/home'),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('catalog/barbara_stickers/settings'),
            'text' => $this->language->get('heading_title')
        );

        $this->document->setTitle($this->language->get('heading_title'));

				$this->load->language('module/barbaratheme');
				$data['text_copyright'] = sprintf($this->language->get('text_copyright'), '%s', date('Y', time()));
				$this->load->language('catalog/barbara_stickers');

        $data = array_merge($data, $this->_setData(array(
            'heading_title'   => $this->language->get('heading_title'),
            'insert'          => $this->makeUrl('catalog/barbara_stickers/insert'),
            'delete'          => $this->makeUrl('catalog/barbara_stickers/delete'),
            'apply'           => $this->makeUrl('catalog/barbara_stickers/apply'),
            'button_insert',
            'button_delete',
            'button_apply',
            'question_apply',
            'column_name',
            'column_image',
            'column_foncolor',
            'column_enabled',
            'column_objects',
            'column_available',
            'column_type',
            'column_sort_order',
            'column_action',
            'text_confirm',
            'text_no_results',
            'text_info'
        )));

				$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

        $data['stickers'] = array();

        $results = $this->model_catalog_barbara_stickers->getList();

        foreach ($results as $result) {
            $action = array();
            $langdata = unserialize($result['langdata']);

            if ($result['image'] && is_file(DIR_IMAGE . $result['image'])) {
              $image = $this->model_tool_image->resize($result['image'], 80, 80);
            } else {
              $image = $this->model_tool_image->resize('no_image.jpg', 80, 80);
            }

            $action[] = array(
                'text' => $this->language->get('button_edit'),
                'href' => $this->makeUrl('catalog/barbara_stickers/update', '&stickers_id=' . $result['stickers_id'])
            );

            $data['stickers'][] = array(
                'stickers_id'   => $result['stickers_id'],
                'name'          => $langdata[$this->config->get('config_language_id')]['name'],
                'image'         => $image,
                'enabled'       => (int)$result['enabled'] == 1 ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'objects_type'  => $this->getObjectTypeText($result['objects_type']),
                'available'     => $result['available'] ? $result['available'] : '',
                'foncolor'      => $result['foncolor'] ? $result['foncolor'] : '',
                'type'          => $this->_types[$result['type']],
                'sort_order'    => $result['sort_order'],
                'action'        => $action
            );
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
						$data['success'] = '';
        }

        $data = array_merge($data, $this->_setData(
            array(
                 'header'       => $this->load->controller('common/header'),
                 'topmenu'  		=> $this->load->controller('module/barbaratheme/topmenu'),
                 'column_left'  => $this->load->controller('common/column_left'),
                 'footer'       => $this->load->controller('common/footer')
            )
        ));

        $this->response->setOutput($this->load->view('catalog/barbara_stickers_settings.tpl', $data));
    }

    protected function getForm() {
        $this->document->addScript('view/javascript/shoputils/jquery.cookie.min.js');
        $this->document->addScript('view/javascript/jscolor/jscolor.js');

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('common/home'),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('catalog/barbara_stickers/settings'),
            'text' => $this->language->get('heading_title')
        );

        if (!isset($this->request->get['stickers_id'])) {
            $data['action'] = $this->makeUrl('catalog/barbara_stickers/insert');

            $this->document->setTitle($this->language->get('heading_title_insert'));

            $data['breadcrumbs'][] = array(
                'href' => $this->makeUrl('catalog/barbara_stickers/insert'),
                'text' => $this->language->get('heading_title_insert')
            );

            $barbara_stickers = array();
        } else {
            $this->document->setTitle($this->language->get('heading_title_update'));

            $data['action'] = $this->makeUrl('catalog/barbara_stickers/update', '&stickers_id=' . $this->request->get['stickers_id']);

            $data['breadcrumbs'][] = array(
                'href' => $this->makeUrl('catalog/barbara_stickers/update'),
                'text' => $this->language->get('heading_title_update')
            );

            $barbara_stickers = $this->model_catalog_barbara_stickers->getGroupStickers($this->request->get['stickers_id']);
        }

        $data['heading_title'] = $this->language->get('heading_title');

				$this->load->language('module/barbaratheme');
				$data['text_copyright'] = sprintf($this->language->get('text_copyright'), '%s', date('Y', time()));
				$this->load->language('catalog/barbara_stickers');

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $data['error_name'] = isset($this->error['error_name']) ? $this->error['error_name'] : '';

        if (isset($barbara_stickers['langdata'])) {
            $barbara_stickers['langdata'] = unserialize($barbara_stickers['langdata']);
        } else {
            $barbara_stickers['langdata'] = array();
        }

        if (isset($barbara_stickers['customer_group_ids'])) {
            $barbara_stickers['customer_group_ids'] = explode(',', $barbara_stickers['customer_group_ids']);
        } else {
            $barbara_stickers['customer_group_ids'] = array();
        }

        if (isset($barbara_stickers['products'])) {
            $barbara_stickers['products_array'] = explode(',', $barbara_stickers['products']);
        } else {
            $barbara_stickers['products'] = '';
            $barbara_stickers['products_array'] = array();
        }

        if (isset($barbara_stickers['manufacturers'])) {
            $barbara_stickers['manufacturers_array'] = explode(',', $barbara_stickers['manufacturers']);
        } else {
            $barbara_stickers['manufacturers_array'] = array();
        }
        $this->load->model('catalog/manufacturer');

        $barbara_stickers['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

        if (isset($barbara_stickers['categories'])) {
            $barbara_stickers['categories_array'] = explode(',', $barbara_stickers['categories']);
        } else {
            $barbara_stickers['categories'] = '';
            $barbara_stickers['categories_array'] = array();
        }

        if (!isset($barbara_stickers['image'])) {
            $barbara_stickers['image'] = 'no_image.png';
        }

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
          $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (isset($barbara_stickers['image']) && is_file(DIR_IMAGE . $barbara_stickers['image'])) {
          $data['thumb'] = $this->model_tool_image->resize($barbara_stickers['image'], 100, 100);
        } else {
          $data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        $data = array_merge($data, $this->_setData(array(
            'categories_dialog' => $this->makeUrl('catalog/barbara_categories_selector/index'),
            'products_dialog'   => $this->makeUrl('catalog/barbara_products_selector/index'),
            'products_table'    => $this->makeUrl('catalog/barbara_products_selector/table', '&field=products_input&selected={selected}'),
            'categories_table'  => $this->makeUrl('catalog/barbara_categories_selector/table', '&field=categories_input&selected={selected}'),
            'cancel'            => $this->makeUrl('catalog/barbara_stickers/settings'),
            'oc_languages'      => $this->model_localisation_language->getLanguages(),
            'types'             => $this->_types,
            'placeholder'       => $this->model_tool_image->resize('no_image.jpg', 100, 100),
            'token'             => isset($this->session->data['token']) ? $this->session->data['token'] : '',
            'entry_name',
            'entry_image',
            'entry_priority',
            'entry_foncolor',
            'entry_available',
            'entry_type',
            'entry_status',
            'entry_sort_order',
            'entry_objects_type',
            'entry_objects_id_0',
            'entry_objects_id_1',
            'entry_objects_id_2',
            'entry_objects_id_3',
            'help_name',
            'help_image',
            'help_priority',
            'help_available',
            'help_type',
            'help_status',
            'help_sort_order',
            'help_objects_type',
            'help_objects_categories',
            'help_objects_products',
            'help_objects_manufacturers',
            'column_objects_product_sku',
            'column_objects_product_name',
            'column_objects_product_price',
            'column_objects_category_name',
            'button_save',
            'button_cancel',
            'button_change',
            'text_enabled',
            'text_disabled',
            'text_browse',
            'text_clear',
            'text_select_all',
            'text_unselect_all',
            'text_image_manager',
            'text_no_categories',
            'text_no_products',
            'text_select_categories',
            'text_select_products'
        )));

        $data = array_merge($data, $this->_updateData(array(
            'langdata',
            'image',
            'priority',
            'foncolor',
            'available',
            'type',
            'enabled',
            'sort_order',
            'objects_type',
            'products',
            'categories',
            'manufacturers',
            'products_array',
            'categories_array',
            'manufacturers_array'
        ), $barbara_stickers));

        if (version_compare(VERSION, '2.1.0.0', '<')) {
            $this->load->model('sale/customer_group');
            $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        } else {
            $this->load->model('customer/customer_group');
            $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
        }

        $data = array_merge($data, $this->_setData(
            array(
                 'header'       => $this->load->controller('common/header'),
                 'topmenu'  		=> $this->load->controller('module/barbaratheme/topmenu'),
                 'column_left'  => $this->load->controller('common/column_left'),
                 'footer'       => $this->load->controller('common/footer')
            )
        ));

        $this->response->setOutput($this->load->view('catalog/barbara_stickers_form.tpl', $data));
    }

    public function insert() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_barbara_stickers->addStickers($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success_insert') . '<br />' .
                sprintf($this->language->get('text_success_apply'), $this->model_catalog_barbara_stickers->applyStickers());

            $this->response->redirect($this->makeUrl('catalog/barbara_stickers/settings'));
        }

        $this->getForm();
    }

    public function delete() {
        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $stickers_id) {
                $this->model_catalog_barbara_stickers->deleteStickers($stickers_id);
            }

            $this->session->data['success'] = $this->language->get('text_success_delete') . '<br />' .
                sprintf($this->language->get('text_success_apply'), $this->model_catalog_barbara_stickers->applyStickers());

            $this->response->redirect($this->makeUrl('catalog/barbara_stickers/settings'));
        }

        $this->getList();
    }

    public function update() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_barbara_stickers->editStickers($this->request->get['stickers_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success_update') . '<br />' .
                sprintf($this->language->get('text_success_apply'), $this->model_catalog_barbara_stickers->applyStickers());

            $this->response->redirect($this->makeUrl('catalog/barbara_stickers/settings'));
        }

        $this->getForm();
    }

    public function apply() {
        if ($this->validateApply()){
            $this->session->data['success'] = sprintf($this->language->get('text_success_apply'), $this->model_catalog_barbara_stickers->applyStickers());

            $this->response->redirect($this->makeUrl('catalog/barbara_stickers/settings'));
        }

        $this->getList();
    }

    public function install() {
        $this->model_catalog_barbara_stickers->makeInstall();
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/barbara_stickers')) {
						$this->error['warning'] = $this->language->get('error_permission');
        }
        foreach ($this->model_localisation_language->getLanguages() as $language) {
            if (!isset($this->request->post['langdata'][$language['language_id']]['name']) || !trim($this->request->post['langdata'][$language['language_id']]['name'])) {
								$this->error['warning'] = $this->error['error_name'][$language['language_id']] = $this->language->get('error_name');
            }
        }
        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/barbara_stickers')) {
						$this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    protected function validateApply() {
        if (!$this->user->hasPermission('modify', 'catalog/barbara_stickers')) {
						$this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
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

    protected function getObjectTypeText($object_type_id) {
        return $this->language->get('entry_objects_id_' . $object_type_id);
    }

    protected function makeUrl($route, $url = ''){
        if (isset($this->session->data['token'])){
            return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
        } else {
            return str_replace('&amp;', '&', $this->url->link($route, $url, 'SSL'));
        }
    }
}
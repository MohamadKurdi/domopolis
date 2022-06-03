<?php
class ControllerShippingShoputilsCitycourier extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('shipping/shoputils_citycourier');

        $this->load->model('localisation/language');
        $this->load->model('localisation/geo_zone');
        $this->load->model('install/shoputils_citycourier');
        $this->load->model('shipping/shoputils_citycourier');
        $this->load->model('setting/setting');

        $this->model_install_shoputils_citycourier->install();

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $values = array(
                'shoputils_citycourier_status',
                'shoputils_citycourier_free_sipping',
                'shoputils_citycourier_sort_order',
                'shoputils_citycourier_free_shipping',
                'shoputils_citycourier_percent',
                'shoputils_citycourier_minimal_price',
                'shoputils_citycourier_minimal_order',
                'shoputils_citycourier_geo_zone_id',
            );

            $values = $this->_key_values_intersect($this->request->post, $values);

            $this->model_setting_setting->editSetting('shoputils_citycourier', $values);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->model_shipping_shoputils_citycourier->editDescriptions($this->request->post);

            $this->redirect($this->makeUrl('extension/shipping'));
        }

        $this->_setData(array(
                             'heading_title',
                             'text_enabled',
                             'text_disabled',
                             'text_none',
                             'text_all_zones',
                             'text_select_categories',
                             'entry_status',
                             'entry_sort_order',
                             'entry_sort_order',
                             'entry_geo_zone',
                             'entry_name',
                             'entry_name_help',
                             'entry_free_shipping',
                             'entry_free_shipping_help',
                             'entry_free_shipping_categories',
                             'entry_free_shipping_categories_help',
                             'entry_free_shipping_categories_percent',
                             'entry_free_shipping_categories_percent_help',
                             'entry_percent',
                             'entry_percent_help',
                             'entry_minimal_price',
                             'entry_minimal_price_help',
                             'entry_minimal_order',
                             'entry_minimal_order_help',
                             'entry_description',
                             'entry_description_help',
                             'button_save',
                             'button_cancel',
                             'button_change',
                        ));

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

         $this->data['breadcrumbs'] = array();

         $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

         $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('extension/shipping'),
            'text' => $this->language->get('text_shipping'),
            'separator' => ' :: '
        );

         $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('shipping/shoputils_citycourier'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->makeUrl('shipping/shoputils_citycourier');

        $this->data['cancel'] = $this->makeUrl('extension/shipping');

        $this->load->model('catalog/category');
        $this->data['categories'] = $this->model_catalog_category->getCategories(0);

        $this->_updateData(
            array(
                 'shoputils_citycourier_status',
                 'shoputils_citycourier_free_sipping',
                 'shoputils_citycourier_sort_order',
                 'shoputils_citycourier_free_shipping',
                 'shoputils_citycourier_percent',
                 'shoputils_citycourier_minimal_price',
                 'shoputils_citycourier_minimal_order',
                 'shoputils_citycourier_geo_zone_id',
            )
        );

        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        $this->data['langdata'] = $this->model_shipping_shoputils_citycourier->getDescriptions();

        $this->template = 'shipping/shoputils_citycourier.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/shoputils_citycourier')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function _setData($keys)
    {
        foreach ($keys as $key => $value) {
            if (is_int($key)) {
                $this->data[$value] = $this->language->get($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    private function _updateData($keys)
    {
        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $this->data[$key] = $this->request->post[$key];
            } else {
                $this->data[$key] = $this->config->get($key);
            }
        }
    }

    private function _key_values_intersect($values, $keys)
    {
        $key_val_int = array();
        foreach ($keys AS $key) {
            if (array_key_exists($key, $values))
                $key_val_int[$key] = $values[$key];
        }
        return $key_val_int;
    }
    
    function makeUrl($route, $url = ''){
        return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
    }
    
}
?>
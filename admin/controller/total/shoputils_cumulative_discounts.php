<?php
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

class ControllerTotalShoputilsCumulativeDiscounts extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('total/shoputils_cumulative_discounts');
        $this->load->model('total/shoputils_cumulative_discounts');
        $this->load->model('setting/store');
		$this->load->model('setting/setting');
        $this->load->model('localisation/language');
        $this->load->model('localisation/order_status');
        $this->load->model('sale/customer_group');
		$this->load->model('catalog/manufacturer');
      //  $this->load->model('install/shoputils_cumulative_discounts');

       // $this->model_install_shoputils_cumulative_discounts->install();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->request->post['shoputils_cumulative_discounts_statuses'] = implode(',', $this->request->post['shoputils_cumulative_discounts_statuses']);

            $values = $this->_key_values_intersect($this->request->post, array('shoputils_cumulative_discounts_statuses', 'shoputils_cumulative_discounts_status', 'shoputils_cumulative_discounts_sort_order'));
            $this->model_setting_setting->editSetting('shoputils_cumulative_discounts', $values);

            $this->model_total_shoputils_cumulative_discounts->editDiscounts($this->request->post);

            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
            $this->redirect($this->makeUrl('total/shoputils_cumulative_discounts'));
        }

        $this->document->setTitle($this->language->get('heading_title'));
        $this->_setData(array(
            'heading_title',
            'button_save',
            'button_cancel',
            'button_add_discount',
            'button_remove_discount',
            'tab_general',
            'tab_cumulative_discounts',
            'text_enabled',
            'text_disabled',
            'text_default',
            'entry_status',
            'entry_sort_order',
            'entry_discount_order_statuses',
            'entry_discount_params',
            'entry_discount_customer_groups',
            'entry_discount_days',
            'entry_discount_description',
            'entry_discount_description_default',
            'entry_discount_percent',
            'entry_discount_products_special',
            'entry_discount_first_order',
            'entry_discount_stores',
            'entry_description_before',
            'entry_description_after',
            'help_description_before',
            'help_description_after',
            'help_discount_order_statuses',
            'help_discount_params',
            'help_discount_customer_groups',
            'help_discount_days',
            'help_discount_description',
            'help_discount_summ',
            'help_discount_percent',
            'help_discount_products_special',
            'help_discount_first_order',
            'help_discount_stores',
            'help_discount',
            'entry_discount_summ' => sprintf($this->language->get('entry_discount_summ'), $this->config->get('config_currency')),
            'text_copyright'      => sprintf($this->language->get('text_copyright'), $this->language->get('heading_title'), date('Y', time())),
            'error_warning'       => isset($this->error['warning']) ? $this->error['warning'] : '',
            'breadcrumbs'         => array(),
            'action'              => $this->makeUrl('total/shoputils_cumulative_discounts'),
            'cancel'              => $this->makeUrl('extension/total'),
            'discounts'           => $this->model_total_shoputils_cumulative_discounts->getAllDiscounts(),
            'cmsdata'             => $this->model_total_shoputils_cumulative_discounts->getDiscountsCMSData(),
            'stores'              => $this->model_setting_store->getStores(),
            'order_statuses'      => $this->model_localisation_order_status->getOrderStatuses(),
            'customer_groups'     => $this->model_sale_customer_group->getCustomerGroups(),
			'manufacturers'		  => $this->model_catalog_manufacturer->getManufacturers(0),
            'languages'           => $this->model_localisation_language->getLanguages()
        ));

        if ($this->config->get('default_config_name')){
            $this->data['text_default'] = $this->config->get('default_config_name');
        }

        $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('extension/total'),
            'text' => $this->language->get('text_total'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('total/shoputils_cumulative_discounts'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        if (isset($this->request->post['shoputils_cumulative_discounts_status'])) {
            $this->data['shoputils_cumulative_discounts_status'] = $this->request->post['shoputils_cumulative_discounts_status'];
        } else {
            $this->data['shoputils_cumulative_discounts_status'] = $this->config->get('shoputils_cumulative_discounts_status');
        }

        if (isset($this->request->post['shoputils_cumulative_discounts_sort_order'])) {
            $this->data['shoputils_cumulative_discounts_sort_order'] = $this->request->post['shoputils_cumulative_discounts_sort_order'];
        } else {
            $this->data['shoputils_cumulative_discounts_sort_order'] = $this->config->get('shoputils_cumulative_discounts_sort_order');
        }
		
        if (isset($this->request->post['shoputils_cumulative_discounts_statuses'])) {
            $this->data['shoputils_cumulative_discounts_statuses'] = explode(',', $this->request->post['shoputils_cumulative_discounts_statuses']);
        } else {
            $this->data['shoputils_cumulative_discounts_statuses'] = explode(',', $this->config->get('shoputils_cumulative_discounts_statuses'));
        }
		
		$this->load->model('localisation/currency');
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
				

        $this->template = 'total/shoputils_cumulative_discounts.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/shoputils_cumulative_discounts')) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else if (!isset($this->request->post['shoputils_cumulative_discounts_statuses']) || !$this->request->post['shoputils_cumulative_discounts_statuses']) {
            $this->error['warning'] = $this->language->get('error_need_select_order_status');
        }

        return !$this->error;
    }

    protected function _key_values_intersect($values, $keys) {
        $key_val_int = array();
        foreach ($keys AS $key) {
            if (array_key_exists($key, $values))
                $key_val_int[$key] = $values[$key];
        }
        return $key_val_int;
    }

    protected function _setData($values) {
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $this->data[$value] = $this->language->get($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    protected function makeUrl($route, $url = ''){
        return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
    }
}
?>
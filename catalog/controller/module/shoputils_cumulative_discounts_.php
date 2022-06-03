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

class ControllerModuleShoputilscumulativediscounts extends Controller {

    public function discounts() {
        $this->load->model('total/shoputils_cumulative_discounts');
        $this->language->load('module/shoputils_cumulative_discounts_');

        $this->data['breadcrumbs'] = array();

        $this->document->setTitle($this->language->get('heading_full_title'));
        $this->data['heading_title'] = $this->language->get('heading_full_title');

         $this->data['breadcrumbs'][] = array(
            'href'      => HTTP_SERVER . 'index.php?route=common/home',
            'text'      => $this->language->get('text_home'),
            'separator' => $this->language->get('text_separator')
        );

         $this->data['breadcrumbs'][] = array(
           'href'      => HTTP_SERVER . 'index.php?route=module/shoputils_cumulative_discounts_/discounts',
           'text'      => $this->language->get('heading_title'),
           'separator' => FALSE
        );


        $cmsdata = $this->data['discounts'] = $this->model_total_shoputils_cumulative_discounts->getDiscountsCMSData(
            (int)$this->config->get('config_language_id')
        );

        $this->data['description_before'] = htmlspecialchars_decode($cmsdata['description_before']);
        $this->data['description_after'] = htmlspecialchars_decode($cmsdata['description_after']);

        $this->data['discounts'] =$this->model_total_shoputils_cumulative_discounts->getDiscounts(
            (int)$this->config->get('config_store_id'),
            (int)$this->config->get('config_customer_group_id'),
            (int)$this->config->get('config_language_id')
        );

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/shoputils_cumulative_discounts_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/shoputils_cumulative_discounts_list.tpl';
        } else {
            $this->template = 'default/template/module/shoputils_cumulative_discounts_list.tpl';
        }

        $this->children = array(
            'common/header',
            'common/footer',
            'common/column_left',
            'common/column_right'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    protected function index() {
        $this->id = 'shoputils_cumulative_discounts_';

        if ($this->customer->isLogged()){
            $this->load->model('total/shoputils_cumulative_discounts');
            $this->language->load('module/shoputils_cumulative_discounts_');
            $this->data['heading_title'] = $this->language->get('heading_title');
            $this->data['text_customer'] = $this->customer->getFirstname() . ' ' . $this->customer->getLastname();
            $this->data['href_discounts'] = HTTP_SERVER.'index.php?route=module/shoputils_cumulative_discounts_/discounts';

            if ($discount = $this->model_total_shoputils_cumulative_discounts->getLoggedCustomerDiscount() ){
                if ($discount['description']){
                    $this->data['text_description'] = $discount['description'];
                } else {
                    $this->data['text_description'] =  $this->language->get('text_description_empty');
                }
                $this->data['text_href_discounts'] = $this->language->get('text_href_discounts_logged');
                $this->data['text_cumulative_summ'] = sprintf($this->language->get('text_cumulative_summ'), $this->currency->format($discount['cumulative_summ']));
            } else {
                $this->data['text_description'] = $this->language->get('text_description_none');;
                $this->data['text_href_discounts'] = $this->language->get('text_href_discounts_not_logged');
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/shoputils_cumulative_discounts_.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/shoputils_cumulative_discounts_.tpl';
            } else {
                $this->template = 'default/template/module/shoputils_cumulative_discounts_.tpl';
            }

            $this->render();
        }
	}
}


?>
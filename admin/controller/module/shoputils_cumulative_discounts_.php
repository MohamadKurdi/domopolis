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
    private $error = array();

    public function index() {
      $this->load->language('module/shoputils_cumulative_discounts_');

      $this->document->setTitle($this->language->get('heading_title'));

      $this->load->model('setting/setting');

      if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
        $this->model_setting_setting->editSetting('shoputils_cumulative_discounts_', $this->request->post);

        $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));

        $this->redirect($this->makeUrl('extension/module'));
      }

      $this->data['heading_title'] = $this->language->get('heading_title');

      $this->data['button_save'] = $this->language->get('button_save');
      $this->data['button_cancel'] = $this->language->get('button_cancel');
      $this->data['text_copyright'] = sprintf($this->language->get('text_copyright'), $this->language->get('heading_title'), date('Y', time()));

      if (isset($this->error['warning'])) {
        $this->data['error_warning'] = $this->error['warning'];
      } else {
        $this->data['error_warning'] = '';
      }

         $this->data['breadcrumbs'] = array();

         $this->data['breadcrumbs'][] = array(
            'href'      => $this->makeUrl('common/home'),
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

         $this->data['breadcrumbs'][] = array(
            'href'      => $this->makeUrl('extension/module'),
            'text'      => $this->language->get('text_module'),
            'separator' => ' :: '
        );

         $this->data['breadcrumbs'][] = array(
            'href'      => $this->makeUrl('module/shoputils_cumulative_discounts_'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

      $this->data['action'] = $this->makeUrl('module/shoputils_cumulative_discounts_');

      $this->data['cancel'] = $this->makeUrl('extension/module');

      if (isset($this->request->post['shoputils_cumulative_discounts__limit'])) {
        $this->data['shoputils_cumulative_discounts__limit'] = $this->request->post['shoputils_cumulative_discounts__limit'];
      } else {
        $this->data['shoputils_cumulative_discounts__limit'] = $this->config->get('shoputils_cumulative_discounts__limit');
      }

      if (isset($this->request->post['shoputils_cumulative_discounts__category'])) {
        $this->data['shoputils_cumulative_discounts__category'] = $this->request->post['shoputils_cumulative_discounts__category'];
      } else {
        $this->data['shoputils_cumulative_discounts__category'] = $this->config->get('shoputils_cumulative_discounts__category');
      }

          if (isset($this->request->post['shoputils_cumulative_discounts__truncate'])) {
              $this->data['shoputils_cumulative_discounts__truncate'] = $this->request->post['shoputils_cumulative_discounts__truncate'];
          } else {
              $this->data['shoputils_cumulative_discounts__truncate'] = $this->config->get('shoputils_cumulative_discounts__truncate');
          }

          $this->load->model('shoputils/design');
          $this->model_shoputils_design->init('shoputils_cumulative_discounts_');

          $this->data['design_tab'] = $this->model_shoputils_design->getDesignTab();
          $this->data['design_values'] = $this->model_shoputils_design->getDesignValues();
          $this->data['design_script'] = $this->model_shoputils_design->getDesignScript();

      $this->template = 'module/shoputils_cumulative_discounts_.tpl';
      $this->children = array(
        'common/header',
        'common/footer'
      );

      $this->response->setOutput($this->render());
    }

    protected function validate() {
      if (!$this->user->hasPermission('modify', 'module/shoputils_cumulative_discounts_')) {
        $this->error['warning'] = $this->language->get('error_permission');
      }

      return !$this->error;
    }

    protected function makeUrl($route, $url = ''){
        return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
    }
}
?>
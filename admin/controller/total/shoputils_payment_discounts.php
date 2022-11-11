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
class ControllerTotalShoputilsPaymentDiscounts extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/shoputils_payment_discounts');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->request->post['shoputils_payment_discounts_payment_methods'] = serialize($this->request->post['shoputils_payment_discounts_payment_methods']);

            $this->model_setting_setting->editSetting('shoputils_payment_discounts', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['entry_percent'] = $this->language->get('entry_percent');

        $this->data['entry_payment_methods'] = $this->language->get('entry_payment_methods');
        $this->data['entry_payment_methods_help'] = $this->language->get('entry_payment_methods_help');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/shoputils_payment_discounts', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/shoputils_payment_discounts', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['shoputils_payment_discounts_status'])) {
			$this->data['shoputils_payment_discounts_status'] = $this->request->post['shoputils_payment_discounts_status'];
		} else {
			$this->data['shoputils_payment_discounts_status'] = $this->config->get('shoputils_payment_discounts_status');
		}

		if (isset($this->request->post['shoputils_payment_discounts_sort_order'])) {
			$this->data['shoputils_payment_discounts_sort_order'] = $this->request->post['shoputils_payment_discounts_sort_order'];
		} else {
			$this->data['shoputils_payment_discounts_sort_order'] = $this->config->get('shoputils_payment_discounts_sort_order');
		}

		if (isset($this->request->post['shoputils_payment_discounts_percent'])) {
			$this->data['shoputils_payment_discounts_percent'] = $this->request->post['shoputils_payment_discounts_percent'];
		} else {
			$this->data['shoputils_payment_discounts_percent'] = $this->config->get('shoputils_payment_discounts_percent');
		}

		if (isset($this->request->post['shoputils_payment_discounts_payment_methods'])) {
			$this->data['shoputils_payment_discounts_payment_methods'] = $this->request->post['shoputils_payment_discounts_payment_methods'];
		} else {
			$this->data['shoputils_payment_discounts_payment_methods'] = unserialize($this->config->get('shoputils_payment_discounts_payment_methods'));
		}
        if (!$this->data['shoputils_payment_discounts_payment_methods']){
            $this->data['shoputils_payment_discounts_payment_methods'] = array();
        }

        $this->load->model('shoputils/payments');

        $this->data['payment_methods'] = $this->model_shoputils_payments->getMethods();

		$this->template = 'total/shoputils_payment_discounts.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/shoputils_payment_discounts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
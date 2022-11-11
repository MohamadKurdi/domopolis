<?php
class ControllerModuleOcjoyajaxcart extends Controller {
	private $error = array(); 
	public function index() {   

		$this->load->language('module/ocjoyajaxcart');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
			$this->model_setting_setting->editSetting('ocjoyajaxcart', $this->request->post);	
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('module/ocjoyajaxcart', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_main_tab_setting'] = $this->language->get('text_main_tab_setting');
	    $this->data['text_ocjoyajaxcart_makeachoice'] = $this->language->get('text_ocjoyajaxcart_makeachoice');
	    $this->data['text_ocjoyajaxcart_yes'] = $this->language->get('text_ocjoyajaxcart_yes');
    	$this->data['text_ocjoyajaxcart_no'] = $this->language->get('text_ocjoyajaxcart_no');
	    $this->data['text_activationtext'] = $this->language->get('text_activationtext');
		$this->data['entry_select_categories'] = $this->language->get('entry_select_categories');
		$this->data['entry_type_productsincart'] = $this->language->get('entry_type_productsincart');
		$this->data['entry_heading_forproducts'] = $this->language->get('entry_heading_forproducts');
		$this->data['text_ocjoyajaxcart_special'] = $this->language->get('text_ocjoyajaxcart_special');
		$this->data['text_ocjoyajaxcart_viewed'] = $this->language->get('text_ocjoyajaxcart_viewed');	
		$this->data['text_ocjoyajaxcart_bestsellers'] = $this->language->get('text_ocjoyajaxcart_bestsellers');	
		$this->data['text_ocjoyajaxcart_bycategory'] = $this->language->get('text_ocjoyajaxcart_bycategory');					
		$this->data['text_ocjoyajaxcart_latest'] = $this->language->get('text_ocjoyajaxcart_latest');
		$this->data['text_ocjoyajaxcart_countname'] = $this->language->get('text_ocjoyajaxcart_countname');					
		$this->data['text_ocjoyajaxcart_countdesc'] = $this->language->get('text_ocjoyajaxcart_countdesc');
		$this->data['text_ocjoyajaxcart_showsku'] = $this->language->get('text_ocjoyajaxcart_showsku');
		$this->data['text_ocjoyajaxcart_showmodel'] = $this->language->get('text_ocjoyajaxcart_showmodel');

 		$this->data['text_copyright'] = $this->language->get('text_copyright');
    	$this->data['text_licence'] = $this->language->get('text_licence');

		$this->data['text_activationcode'] = $this->language->get('text_activationcode');
    	$this->data['text_youruidcode'] = $this->language->get('text_youruidcode');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->data['token'] = $this->session->data['token'];

		$this->data['breadcrumbs'] = array();
 		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
 		);
 		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
 		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/ocjoyajaxcart', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
		
		$this->data['action'] = $this->url->link('module/ocjoyajaxcart', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['config_type_ap'])) {
			$this->data['config_type_ap'] = $this->request->post['config_type_ap'];
		} else {
			$this->data['config_type_ap'] = $this->config->get('config_type_ap');
		}
		if (isset($this->request->post['config_path'])) {
			$this->data['config_path'] = $this->request->post['config_path'];
		} else {
			$this->data['config_path'] = $this->config->get('config_path');
		}
		if (isset($this->request->post['config_parent_id'])) {
			$this->data['config_parent_id'] = $this->request->post['config_parent_id'];
		} else {
			$this->data['config_parent_id'] = $this->config->get('config_parent_id');
		}
		if (isset($this->request->post['config_textforproducts'])) {
			$this->data['config_textforproducts'] = $this->request->post['config_textforproducts'];
		} else {
			$this->data['config_textforproducts'] = $this->config->get('config_textforproducts');
		}
		if (isset($this->request->post['config_ocjoyajaxcart_countname'])) {
			$this->data['config_ocjoyajaxcart_countname'] = $this->request->post['config_ocjoyajaxcart_countname'];
		} else {
			$this->data['config_ocjoyajaxcart_countname'] = $this->config->get('config_ocjoyajaxcart_countname');
		}
		if (isset($this->request->post['config_ocjoyajaxcart_countdesc'])) {
			$this->data['config_ocjoyajaxcart_countdesc'] = $this->request->post['config_ocjoyajaxcart_countdesc'];
		} else {
			$this->data['config_ocjoyajaxcart_countdesc'] = $this->config->get('config_ocjoyajaxcart_countdesc');
		}
		if (isset($this->request->post['config_ocjoyajaxcart_showsku'])) {
			$this->data['config_ocjoyajaxcart_showsku'] = $this->request->post['config_ocjoyajaxcart_showsku'];
		} else {
			$this->data['config_ocjoyajaxcart_showsku'] = $this->config->get('config_ocjoyajaxcart_showsku');
		}
		if (isset($this->request->post['config_ocjoyajaxcart_showmodel'])) {
			$this->data['config_ocjoyajaxcart_showmodel'] = $this->request->post['config_ocjoyajaxcart_showmodel'];
		} else {
			$this->data['config_ocjoyajaxcart_showmodel'] = $this->config->get('config_ocjoyajaxcart_showmodel');
		}
	    if (isset($this->request->post['config_ukey_sc'])) {
	      $this->data['config_ukey_sc'] = $this->request->post['config_ukey_sc'];
	    } else {
	      $this->data['config_ukey_sc'] = $this->config->get('config_ukey_sc');
	    }
    	$this->data['UID'] = base64_encode(DIR_SYSTEM.':'.$_SERVER['SERVER_NAME']);

		$this->load->model('catalog/category');
		$categories = $this->model_catalog_category->getCategories(0);
		$this->data['categories'] = $categories;

		$this->template = 'module/ocjoyajaxcart.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
	}
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ocjoyajaxcart')) {
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
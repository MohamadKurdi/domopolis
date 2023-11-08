<?php
class ControllerModuleFullProductPath extends Controller {
	private $error = array(); 
	
	public function index() {   
		define('OC_V151', substr(VERSION, 0, 5) == '1.5.1');
		define('OC_V2', substr(VERSION, 0, 1) == 2);
		
		$data['_language'] = &$this->language;
		$data['_config'] = &$this->config;
		$data['_url'] = &$this->url;
		$data['token'] = $this->session->data['token'];


		$this->load->language('module/full_product_path');
		$this->document->setTitle(strip_tags($this->language->get('module_title')));
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('full_product_path', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			//$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else $data['success'] = '';
		
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else $data['error'] = '';
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('module_title'),
			'href'      => $this->url->link('module/full_product_path', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/full_product_path', 'token=' . $this->session->data['token']);
		
		$data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
		
		// full product path - start
		
		if (isset($this->request->post['full_product_path_mode'])) {
			$data['full_product_path_mode'] = $this->request->post['full_product_path_mode'];
		} else {
			$data['full_product_path_mode'] = $this->config->get('full_product_path_mode');
		}
    
		if (isset($this->request->post['full_product_path_depth'])) {
			$data['full_product_path_depth'] = $this->request->post['full_product_path_depth'];
		} else {
			$data['full_product_path_depth'] = $this->config->get('full_product_path_depth');
		}
    
		if (isset($this->request->post['full_product_path_breadcrumbs'])) {
			$data['full_product_path_breadcrumbs'] = $this->request->post['full_product_path_breadcrumbs'];
		} else {
			$data['full_product_path_breadcrumbs'] = $this->config->get('full_product_path_breadcrumbs');
		}
		
		if (isset($this->request->post['full_product_path_bc_mode'])) {
			$data['full_product_path_bc_mode'] = $this->request->post['full_product_path_bc_mode'];
		} else {
			$data['full_product_path_bc_mode'] = $this->config->get('full_product_path_bc_mode');
		}
		
		if (isset($this->request->post['full_product_path_bypasscat'])) {
			$data['full_product_path_bypasscat'] = (isset($this->request->post['full_product_path_bypasscat'])) ? true : false;
		} else {
			$data['full_product_path_bypasscat'] = $this->config->get('full_product_path_bypasscat');
		}
    
    	if (isset($this->request->post['full_product_path_directcat'])) {
			$data['full_product_path_directcat'] = (isset($this->request->post['full_product_path_directcat'])) ? true : false;
		} else {
			$data['full_product_path_directcat'] = $this->config->get('full_product_path_directcat');
		}
    
   		 if (isset($this->request->post['full_product_path_homelink'])) {
			$data['full_product_path_homelink'] = (isset($this->request->post['full_product_path_homelink'])) ? true : false;
		} else {
			$data['full_product_path_homelink'] = $this->config->get('full_product_path_homelink');
		}
		
		if (isset($this->request->post['full_product_path_categories'])) {
			$data['full_product_path_categories'] = $this->request->post['full_product_path_categories'];
		} else {
			$data['full_product_path_categories'] = $this->config->get('full_product_path_categories') ? $this->config->get('full_product_path_categories') : array();
		}		
		
		// categories management
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(0);
		
		// full product path - end
		
		if (OC_V2) {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('module/full_product_path.tpl', $data));
		} else {
			$data['column_left'] = '';
			$this->data = &$data;
			$this->template = 'module/full_product_path.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
					
			$this->response->setOutput($this->render());
		}
		}
	
	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('full_product_path', array('full_product_path_largest' => true));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/full_product_path')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error)
			return true;
		return false;
	}
}
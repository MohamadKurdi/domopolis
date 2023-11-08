<?php
class ControllerModuleSnowFalling extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/snow_falling');
		
		$this->document->addScript('view/javascript/jquery/colorpicker/colorpicker.js');
		$this->document->addStyle('view/stylesheet/colorpicker.css');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('snow_falling', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/snow_falling', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_min_size'] = $this->language->get('entry_min_size');
		$this->data['entry_max_size'] = $this->language->get('entry_max_size');
		$this->data['entry_flake_color'] = $this->language->get('entry_flake_color');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['min_size'])) {
			$this->data['error_min_size'] = $this->error['min_size'];
		} else {
			$this->data['error_min_size'] = '';
		}
		
		if (isset($this->error['max_size'])) {
			$this->data['error_max_size'] = $this->error['max_size'];
		} else {
			$this->data['error_max_size'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/snow_falling', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/snow_falling', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

		$this->data['modules'] = array();
		
		if (isset($this->request->post['snow_falling_module'])) {
			$this->data['modules'] = $this->request->post['snow_falling_module'];
		} elseif ($this->config->get('snow_falling_module')) { 
			$this->data['modules'] = $this->config->get('snow_falling_module');
		}				
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/snow_falling.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/snow_falling')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['snow_falling_module'])) {
			foreach ($this->request->post['snow_falling_module'] as $key => $value) {
				if (!$value['min_size']) {
					$this->error['min_size'][$key] = $this->language->get('error_min_size');
				}
				
				if (!$value['max_size']) {
					$this->error['max_size'][$key] = $this->language->get('error_max_size');
				}
				
				if (!$value['flake_color']) {
					$this->error['flake_color'][$key] = $this->language->get('error_flake_color');
				}
			}
		}		
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
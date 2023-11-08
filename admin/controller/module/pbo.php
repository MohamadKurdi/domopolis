<?php
class ControllerModulePbo extends Controller {
	private $error = array(); 
	
	public function index() {  
	
		$this->load->language('module/pbo');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pbo', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
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
			'href'      => $this->url->link('module/pbo', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/pbo', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

		$this->data['modules'] = array();
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/pbo.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		//START
		//entry
		$this->setDataLang('entry_allow_unselecting');
		
		$this->setDataLang('entry_general');
		$this->setDataLang('entry_apply_to_standard_options');
		$this->setDataLang('entry_preview');
		
		$this->setDataLang('entry_text_block');
		$this->setDataLang('entry_block_padding');
		$this->setDataLang('entry_block_border_width');
		$this->setDataLang('entry_block_border_radius');
		
		$this->setDataLang('entry_block_background_color');
		$this->setDataLang('entry_block_text_color');
		$this->setDataLang('entry_block_border_color');
		
		$this->setDataLang('entry_block_selected_background_color');
		$this->setDataLang('entry_block_selected_text_color');
		$this->setDataLang('entry_block_selected_block_border_color');		
		
		$this->setDataLang('entry_image_block');	
		$this->setDataLang('entry_block_width_height');
		
		//text
		$this->setDataLang('text_yes');
		$this->setDataLang('text_no');		
		$this->setDataLang('text_select_all');		
		$this->setDataLang('text_unselect_all');
		
		//data
		$this->load->model('module/pbo');
		$this->setData('standard_options', $this->model_module_pbo->getStandardOptions());	
		
		$this->setData('pbo_options', array());	
		$this->setData('pbo_allow_unselecting', 0);	
		$this->setData('pbo_text_block_padding', 10);
		$this->setData('pbo_text_block_border_width', 3);
		$this->setData('pbo_text_block_border_radius', 5);
		
		$this->setData('pbo_text_block_background_color', '#ffffff');
		$this->setData('pbo_text_block_text_color', '#000000');
		$this->setData('pbo_text_block_border_color', '#E7E7E7');
		
		$this->setData('pbo_text_block_selected_background_color', '#ffffff');
		$this->setData('pbo_text_block_selected_text_color', '#000000');
		$this->setData('pbo_text_block_selected_block_border_color', '#FFA500');				
				
		$this->setData('pbo_image_block_padding', 4);
		$this->setData('pbo_image_block_border_width', 2);
		$this->setData('pbo_image_block_border_radius', 5);
		$this->setData('pbo_image_block_width', 50);
		$this->setData('pbo_image_block_height', 50);
		
		$this->setData('pbo_image_block_text_color', '#000000');
		$this->setData('pbo_image_block_border_color', '#E7E7E7');
		
		$this->setData('pbo_image_block_selected_text_color', '#000000');
		$this->setData('pbo_image_block_selected_block_border_color', '#FFA500');
		//END
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/pbo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function install()
	{
		//do nothing
	}
	
	public function uninstall()
	{
		//TODO
		//convert all created Image Block Options to Image Options
		
		//convert all created Text Block Options to Text Options
	}
}
?>
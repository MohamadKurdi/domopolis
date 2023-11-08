<?php
class ControllerModulemattimeobanner extends Controller {
	private $error = array(); 
	private $_name = 'mattimeobanner';
	public function index() {   
	
		$this->load->language('module/mattimeobanner');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_setting->editSetting('mattimeobanner', $this->request->post);
					
            if ($this->request->post['buttonForm'] == 'apply') {
				$this->redirect($this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
				$this->session->data['success'] = $this->language->get('text_success');
			}
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');	
		$this->data['text_column_right'] = $this->language->get('text_column_right');	
		$this->data['text_home'] = $this->language->get('text_home');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_apply'] = $this->language->get('text_apply');		
		

		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_image_category'] = $this->language->get('entry_image_category');
		$this->data['entry_radius'] = $this->language->get('entry_radius');
		$this->data['entry_dinamic'] = $this->language->get('entry_dinamic');
		
		$this->data['item_add_tab'] = $this->language->get('item_add_tab');
		$this->data['item_heading'] = $this->language->get('item_heading');
		$this->data['item_title'] = $this->language->get('item_title');
		$this->data['item_image'] = $this->language->get('item_image');
		$this->data['item_link'] = $this->language->get('item_link');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_add_image'] = $this->language->get('button_add_image');


 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = array();
		}
		
		if (isset($this->error['category_image'])) {
			$this->data['error_category_image'] = $this->error['category_image'];
		} else {
			$this->data['error_category_image'] = array();
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
			'href'      => $this->url->link('module/mattimeobanner', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/mattimeobanner', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

		$this->data['token'] = $this->session->data['token'];
	
		//module		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['mattimeobanner_module'])) {
			$this->data['modules'] = $this->request->post['mattimeobanner_module'];
		} elseif ($this->config->get('mattimeobanner_module')) { 
			$this->data['modules'] = $this->config->get('mattimeobanner_module');
		}	
	
		$this->load->model('localisation/language');
   	$languages = $this->model_localisation_language->getLanguages();
		$this->data['languages'] = $languages;

		$array_title = array();
		$array_headingtext = array();
		
		foreach($languages as $language){
			$array_title{$language['language_id']} = 'Example text';
			$array_headingtext{$language['language_id']} = 'Heading';
		}
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);	
	
		foreach ($this->data['modules'] as &$module) {
         foreach ($module['tabs'] as &$tab ) {
   			if (!$tab['image']) {
   				$image = 'no_image.jpg';
      			$tab['image']= $image;
   			};		
         			
   			$tab['thumb']= $this->model_tool_image->resize($tab['image'], 100, 100);
         }
		}

      $this->data['modules'][0] = array(
      'group'         =>'Name module',
      'image_width'   =>'200',
      'image_height'  =>'200',
      'dinamic'       =>'1',
      'radius'        =>'0',
      'layout_id'     =>'1',
      'position'      =>'content_top',
      'status'        =>'0',
      'sort_order'    =>'2',
      'tabs'          => 
         array(0 => 
            array(
            'thumb'        => $this->model_tool_image->resize('no_image.jpg', 100, 100), 
            'image'        => 'no_image.jpg', 
            'headingtext'  => $array_headingtext, 
            'title'        => $array_title, 
            'href'         => '#'
            )
         )
      );

		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/mattimeobanner.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/mattimeobanner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['mattimeobanner_module'])) {
			foreach ($this->request->post['mattimeobanner_module'] as $key => $value) {
				
				if (!$value['image_width'] || !$value['image_height']) {
					$this->error['category_image'][$key] = $this->language->get('error_image');
				}
			}
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
		
 private function getLayoutRoute($route) {
  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db->escape($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1");


  if ($query->num_rows) {
   return $query->row['layout_id'];
  } else {
   return 0;
  }
 }
	
	public function install() 
	{
	
	}	
}

?>
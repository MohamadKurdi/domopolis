<?php
class ControllerModulemattimeocategory extends Controller {
	private $error = array(); 
	private $_name = 'mattimeocategory';
	public function index() {   
	
		$this->load->language('module/mattimeocategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_setting->editSetting('mattimeocategory', $this->request->post);		
			
			
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
		
		$this->data['entry_image_category'] = $this->language->get('entry_image_category');
		$this->data['entry_image_subcategory'] = $this->language->get('entry_image_subcategory');
		$this->data['entry_image_products'] = $this->language->get('entry_image_products');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['item_add_tab'] = $this->language->get('item_add_tab');
		$this->data['item_title'] = $this->language->get('item_title');
		$this->data['item_image'] = $this->language->get('item_image');
		$this->data['item_limit'] = $this->language->get('item_limit');
		$this->data['item_limit_v'] = $this->language->get('item_limit_v');
		$this->data['item_subcateg'] = $this->language->get('item_subcateg');
		$this->data['item_products'] = $this->language->get('item_products');
		$this->data['item_products_from'] = $this->language->get('item_products_from');
		$this->data['tab_choose_a_category'] = $this->language->get('tab_choose_a_category');
		
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
		if (isset($this->error['category'])) {
			$this->data['error_category'] = $this->error['category'];
		} else {
			$this->data['error_category'] = array();
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
		
		if (isset($this->error['numproduct'])) {
			$this->data['error_numproduct'] = $this->error['numproduct'];
		} else {
			$this->data['error_numproduct'] = array();
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
			'href'      => $this->url->link('module/mattimeocategory', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/mattimeocategory', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

		$this->data['token'] = $this->session->data['token'];
	
		// tab
		$this->data['tabs'] = array();
		
		if (isset($this->request->post['mattimeocategory_tab'])) {
			$tabs = $this->request->post['mattimeocategory_tab'];
		} elseif ($this->config->get('mattimeocategory_tab')) { 
			$tabs = $this->config->get('mattimeocategory_tab');
		}	
	
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);	
	
		foreach ($tabs as $tab) {
			if ($tab['image']) {
				$image = $tab['image'];
			} else {
				$image = false;
			}		
			
			$this->data['tabs'][] = array(
				'category_id' => $tab['category_id'],
				'image'       => $image,
				'thumb'   	  => $this->model_tool_image->resize($image, 100, 100),
				'title'       => $tab['title'],
				'subcateg'    => $tab['subcateg'],
				'showproducts'    => $tab['showproducts'],
			);
		}
		
			
		//module		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['mattimeocategory_module'])) {
			$this->data['modules'] = $this->request->post['mattimeocategory_module'];
		} elseif ($this->config->get('mattimeocategory_module')) { 
			$this->data['modules'] = $this->config->get('mattimeocategory_module');
		}	
	
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		
		$this->template = 'module/mattimeocategory.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
		
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/mattimeocategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['mattimeocategory_module'])) {
			foreach ($this->request->post['mattimeocategory_module'] as $key => $value) {
				if (!$value['limit']) {
					$this->error['numproduct'][$key] = $this->language->get('error_numproduct');
				}
				if (!$value['limit_v']) {
					$this->error['numproduct'][$key] = $this->language->get('error_numproduct');
				}
				if (!$value['image_width']) {
					
					$value['image_width'] = '0';
				}
				
				if (!$value['image_subcategory_width'] || !$value['image_subcategory_height']) {
					
					$value['image_subcategory_width'] = '';
					$value['image_subcategory_height'] = '';
				}
				if (!$value['image_width'] || !$value['image_height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
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
		$this->load->model('setting/setting');
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		$array_title0 = array();
		$array_title1 = array();	
		
		foreach($languages as $language){
			$array_title0{$language['language_id']} = 'Example text';
			$array_title1{$language['language_id']} = 'Example text';
		}
		
		$mattimeocategory = array('mattimeocategory_tab' => array ( 
        0 => array ('category_id' => 20, 
		            'subcateg' => 1, 
					'showproducts' => 1,
					'image' => 'data/mattimeo/1.jpg', 
					'title' => $array_title0),
			1 => array ('category_id' => 18, 
			            'subcateg' => 1,
						'showproducts' => 1, 
						'image' => 'data/mattimeo/2.jpg', 
						'title' => $array_title1)
		),'mattimeocategory_module' => array ( 
			0 => array ('category_width' => 0, 
						'image_subcategory_width' => 70, 
						'image_subcategory_height' => 70, 
						'image_width' => 200, 
						'image_height' => 200,
						'limit_v' => 2, 
						'limit' => 6, 
						'layout_id' => $this->getLayoutRoute('common/home'), 
						'position' => 'content_top', 
						'status' => 1, 
						'sort_order' => 3))
		);

		$this->model_setting_setting->editSetting('mattimeocategory', $mattimeocategory);		
	}	
}
?>
<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerAlbumModules extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('album/index');

		$this->document->setTitle($this->language->get('section_modules'));
		$this->document->addStyle('view/stylesheet/photo_gallery.manager.css');
		$this->load->model('setting/setting');
		$this->load->model('album/index');
		$this->data['albums'] = $this->model_album_index->getAlbums();
		if (empty($this->data['albums'])) {
			$this->session->data['error_warning'] = $this->language->get('text_error_no_albums');
			$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
		}		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('gallery_module', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success_modules');
			$this->cache->delete('gallery_album_photos');		
			$this->cache->delete('album_photos');		
			$this->cache->delete('album_gallery');		
			$this->cache->delete('album_module');		
			$this->redirect($this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('section_modules');
		
		$this->data['albums'] = $this->model_album_index->getAlbums();
		
		//GetCategoryList
		$categories = $this->model_album_index->getAllCategories();
		$this->data['categories'] = $this->getAllCategories($categories);

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$config_admin_language = $this->config->get('config_admin_language'); 
  		foreach ($this->data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$this->data['config_admin_language_id'] = $language['language_id'];
  			}
  		}
  		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_new_module_name'] = $this->language->get('text_new_module_name');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_show_album_text'] = $this->language->get('text_show_album_text');
		$this->data['text_show_galleries_text'] = $this->language->get('text_show_galleries_text');
		
		$this->data['entry_module_name'] = $this->language->get('entry_module_name');
		$this->data['entry_module_header'] = $this->language->get('entry_module_header');
		$this->data['entry_show_module_header'] = $this->language->get('entry_show_module_header');
		$this->data['entry_module_type'] = $this->language->get('entry_module_type');
		$this->data['entry_album_list'] = $this->language->get('entry_album_list');
		$this->data['entry_photo_album_list'] = $this->language->get('entry_photo_album_list');
		$this->data['module_types'] = $this->language->get('module_types');
		$this->data['entry_photos_limit'] = $this->language->get('entry_photos_limit');
		$this->data['entry_show_covers'] = $this->language->get('entry_show_covers');
		$this->data['entry_cover_size'] = $this->language->get('entry_cover_size');
		$this->data['entry_show_counter'] = $this->language->get('entry_show_counter');
		$this->data['entry_show_album_link'] = $this->language->get('entry_show_album_link');
		$this->data['entry_show_album_text'] = $this->language->get('entry_show_album_text');
		$this->data['entry_show_album_galleries_link'] = $this->language->get('entry_show_album_galleries_link');
		$this->data['entry_show_album_description'] = $this->language->get('entry_show_album_description');
		$this->data['entry_thumb_size_mod'] = $this->language->get('entry_thumb_size_mod');
		$this->data['entry_popup_size_mod'] = $this->language->get('entry_popup_size_mod');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_module_category'] = $this->language->get('entry_module_category');
		$this->data['entry_module_product'] = $this->language->get('entry_module_product');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		//Set links
		$this->data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$this->data['text_section_modules'] 	= $this->language->get('section_modules');
		$this->data['text_section_settings'] 	= $this->language->get('section_settings');

		$this->data['link_section_album_list'] 	= $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_modules'] 	= $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_settings'] 	= $this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL');
  		

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
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_modules'),
			'href'      => $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL');

  		$this->data['module_row_ids'] = $this->config->get('config_gallery_track_code');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['gallery_module'])) {
			$this->data['modules'] = $this->request->post['gallery_module'];
		} elseif ($this->config->get('gallery_module')) { 
			$this->data['modules'] = $this->config->get('gallery_module');
		}
  		
		
		$this->load->model('catalog/product');
		
		$this->data['token'] = $this->session->data['token'];

		foreach ($this->data['modules'] as $key => $value) {
			$this->data['modules'][$key]['album_list'] = (isset($value['album_list']) ? $value['album_list'] : array());
			$this->data['modules'][$key]['photo_album_list'] = (isset($value['photo_album_list']) ? $value['photo_album_list'] : array());
			$this->data['modules'][$key]['album_show_on_categories'] = (isset($value['album_show_on_categories']) ? $value['album_show_on_categories'] : array());
			$this->data['modules'][$key]['album_show_on_products'] = (isset($value['album_show_on_products']) ? $value['album_show_on_products'] : array());
			
			$album_show_on_products = array();

			foreach ($this->data['modules'][$key]['album_show_on_products'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					$album_show_on_products[$product_id] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
			$this->data['modules'][$key]['album_show_on_products'] = $album_show_on_products;
		}

		$this->data['config_gallery_module_category_layout_id'] 			= $this->config->get('config_gallery_module_category_layout_id');
		$this->data['config_gallery_module_product_layout_id'] 			= $this->config->get('config_gallery_module_product_layout_id');
				
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'album/modules.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'album/modules')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
		$output = array();

		if (array_key_exists($parent_id, $categories)) {
			if ($parent_name != '') {
				$parent_name .= $this->language->get('text_separator');
			}

			foreach ($categories[$parent_id] as $category) {
				$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
				);

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

		return $output;
	}
}
?>
<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerAlbumAlbum extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('album/index'); 
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/photo_gallery.manager.css');
		$this->load->model('album/index');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
				$this->error['warning'] = $this->language->get('text_success');
				$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
			}
		$this->data['albums'] = $this->model_album_index->getAlbums();

		// Check and Update
		$update_result = $this->model_album_index->check_and_update();
		if ($update_result) {
			$this->session->data['success'] = $this->language->get('text_success_updated');
		}
		if (!empty($this->data['albums'])) {
			foreach ($this->data['albums'] as $key => $value) {
				$value['edit_link'] = $this->url->link('album/album/edit', 'token=' . $this->session->data['token'].'&album_id='.$value['album_id'], 'SSL');
				$this->data['albums'][$key] = $value;
			}
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['heading_title'] = $this->language->get('section_album_list');

		//Set language variables

		//Album
		$this->data['text_list_empty'] = $this->language->get('text_list_empty');
		$this->data['arr_enabled'] 	= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));
		
		$this->data['album_types'] = $this->language->get('album_types');

		//Buttons
		$this->data['button_text_save'] = $this->language->get('button_text_save');
		$this->data['button_text_insert'] = $this->language->get('button_text_insert');
		$this->data['button_text_copy'] = $this->language->get('button_text_copy');
		$this->data['button_text_edit'] = $this->language->get('button_text_edit');
		$this->data['button_text_delete'] = $this->language->get('button_text_delete');
		$this->data['button_text_cancel'] = $this->language->get('button_text_cancel');
		
		//Table
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_album_type'] = $this->language->get('column_album_type');
		$this->data['column_enabled'] = $this->language->get('column_enabled');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		//Set links
		$this->data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$this->data['text_section_modules'] 	= $this->language->get('section_modules');
		$this->data['text_section_settings'] 	= $this->language->get('section_settings');

		$this->data['link_section_album_list'] 	= $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_modules'] 	= $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_settings'] 	= $this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
  		$config_admin_language = $this->config->get('config_admin_language'); 
  		foreach ($this->data['languages'] as $key => $language) {
  			if ($config_admin_language == $language['code']) {
  				$this->data['config_admin_language_id'] = $language['language_id'];
  			}
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
       		'text'      => $this->language->get('section_album_list'),
			'href'      => $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		//Set links
		$this->data['add'] = $this->url->link('album/album/add', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('album/album/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['copy'] = $this->url->link('album/album/copy', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			$this->error['warning'] = true;
			unset($this->session->data['error_warning']);
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->template = 'album/album_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	public function add() {
		$this->language->load('album/index'); 
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/photo_gallery.manager.css');
		$this->load->model('album/index');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
				$this->session->data['success'] = $this->language->get('text_success_added');
				$this->model_album_index->addAlbum($this->request->post);				
				$this->cache->delete('seo_pro');
				$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
			}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['heading_title'] = $this->language->get('section_new_album');
		//Set Default variables for album
		$this->data['album_id'] = '';
		$this->data['album_type'] = 0;
		$album_names = array();
		foreach ($this->data['languages'] as $key => $language) {
			$album_names[$language['language_id']] = $this->language->get('text_new_album');
		}
		$this->data['album_data'] = array(
			'album_name'				=> $album_names,
			'photos_limit'				=> 0,
			'show_limiter'				=> 1,
			'thumb_width'				=> 180,
			'thumb_height'				=> 120,
			'popup_width'				=> 800,
			'popup_height'				=> 600,
			'js_lib_type'				=> 0,
			'use_lazyload' 				=> 0,
			'show_album_description' 	=> 0,
			'album_categories' 			=> array(),
			'include_additional_images' => 0,
			'album_directory' 			=> '',
			'gallery_images' 			=> array(),
			'album_title' 				=> '',
			'album_title' 				=> '',
			'album_h1_title' 			=> '',
			'album_meta_keywords' 		=> '',
			'album_meta_description' 	=> '',
			'album_description' 		=> '',
		);
		//	'album_seo_url' => ''
		$this->data['enabled'] = 1;
		$this->data['sort_order'] = 0;

		$this->data['image_row'] = 0;
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		$this->data['album_data']['cover_image']['image'] = 'no_image.jpg';
		$this->data['album_data']['cover_image']['thumb'] = $this->data['no_image'];

		//GetCategoryList
		$categories = $this->model_album_index->getAllCategories();
		$this->data['categories'] = $this->getAllCategories($categories);

		//Set language variables

		$this->data['module_row_ids'] = $this->config->get('config_gallery_track_code');

		//Album
		$this->data['arr_js_lib_types'] 	= $this->language->get('arr_js_lib_types');
		$this->data['album_types'] 			= $this->language->get('album_types');
		
		$this->data['text_image_manager'] 	= $this->language->get('text_image_manager');
		
		$this->data['entry_album_name'] 	= $this->language->get('entry_album_name');
		$this->data['entry_album_seo_name'] = $this->language->get('entry_album_seo_name');
		$this->data['entry_album_type'] 	= $this->language->get('entry_album_type');
		$this->data['entry_gallery_photos_limit'] 	= $this->language->get('entry_gallery_photos_limit');
		$this->data['entry_gallery_show_limiter'] 	= $this->language->get('entry_gallery_show_limiter');
		$this->data['entry_thumb_size'] 	= $this->language->get('entry_thumb_size');
		$this->data['entry_popup_size'] 	= $this->language->get('entry_popup_size');
		$this->data['entry_category_list'] 	= $this->language->get('entry_category_list');
		$this->data['entry_include_additional_images'] 	= $this->language->get('entry_include_additional_images');
		$this->data['entry_directory'] 		= $this->language->get('entry_directory');
		$this->data['entry_galery_images'] 	= $this->language->get('entry_galery_images');
		$this->data['entry_cover_image'] 	= $this->language->get('entry_cover_image');
		$this->data['entry_show_gallery_album_description'] 	= $this->language->get('entry_show_gallery_album_description');
		$this->data['entry_use_lazyload'] 	= $this->language->get('entry_use_lazyload');
		
		$this->data['entry_title'] 			= $this->language->get('entry_title');
		$this->data['entry_h1'] 			= $this->language->get('entry_h1');
		$this->data['entry_meta_keywords'] 	= $this->language->get('entry_meta_keywords');
		$this->data['entry_meta_description'] 	= $this->language->get('entry_meta_description');
		$this->data['entry_description'] 	= $this->language->get('entry_description');
		$this->data['entry_album_status'] 	= $this->language->get('entry_album_status');
		$this->data['entry_sort_order'] 	= $this->language->get('entry_sort_order');
		$this->data['entry_js_library'] = $this->language->get('entry_js_library');
		
		// h2
		$this->data['text_album_page_settings'] = $this->language->get('text_album_page_settings');
		$this->data['text_album_data'] = $this->language->get('text_album_data');

		$this->data['text_select_all'] 	= $this->language->get('text_select_all');
		$this->data['text_unselect_all'] 	= $this->language->get('text_unselect_all');
		$this->data['text_browse'] 	= $this->language->get('text_browse');
		$this->data['text_clear'] 	= $this->language->get('text_clear');
		$this->data['text_image_manager'] 	= $this->language->get('text_image_manager');
		$this->data['text_placeholder_description'] 	= $this->language->get('text_placeholder_description');
		$this->data['text_enabled'] 	= $this->language->get('text_enabled');
		$this->data['text_disabled'] 	= $this->language->get('text_disabled');
		$this->data['text_error_check_form'] 	= $this->language->get('text_error_check_form');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		//Tabs
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');

		//Buttons
		$this->data['button_text_save'] = $this->language->get('button_text_save');
		$this->data['button_text_cancel'] = $this->language->get('button_text_cancel');
		$this->data['button_text_add_image'] = $this->language->get('button_text_add_image');
		$this->data['button_text_remove_image'] 	= $this->language->get('button_text_remove_image');
		
		//Set links
		$this->data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$this->data['text_section_modules'] 	= $this->language->get('section_modules');
		$this->data['text_section_settings'] 	= $this->language->get('section_settings');

		$this->data['link_section_album_list'] 	= $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_modules'] 	= $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_settings'] 	= $this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
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
       		'text'      => $this->language->get('section_album_list'),
			'href'      => $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_new_album'),
			'href'      => $this->url->link('album/album/add', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		//Set links
		$this->data['cancel'] = $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['save'] = $this->url->link('album/album/add', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit'] = $this->url->link('album/album/edit', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		//Set settings
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->template = 'album/album_add.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	public function edit() {
		$this->language->load('album/index'); 
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/photo_gallery.manager.css');
		$this->load->model('album/index');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
				$this->session->data['success'] = $this->language->get('text_success_edited');
				$this->model_album_index->editAlbum($this->request->post);

				$this->cache->delete('gallery_album_photos');		
				$this->cache->delete('album_photos');		
				$this->cache->delete('album_gallery');		
				$this->cache->delete('album_module');		
				$this->cache->delete('seo_pro');		
				
				$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
			}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['heading_title'] = $this->language->get('section_edit_album');
		//Set Default variables for album
		$album_query = $this->model_album_index->getAlbum((int)$this->request->get['album_id']);
		$this->data['album_id'] = $album_query['album_id'];
		$this->data['album_type'] = $album_query['album_type'];
		$this->data['enabled'] = $album_query['enabled'];
		$this->data['sort_order'] = $album_query['sort_order'];
		if (!isset($album_query['album_data']['use_lazyload'])) {
			$album_query['album_data']['use_lazyload'] = 0;
		}
		$this->data['album_data'] = $album_query['album_data'];
		if (!isset($album_query['album_data']['album_categories'])) {
			$this->data['album_data']['album_categories'] = array();
		}
		$this->data['album_data']['gallery_images'] = array();
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		if (!empty($album_query['album_data']['gallery_images'])) {
			foreach ($album_query['album_data']['gallery_images'] as $gallery_image) {
				$id_s[] = $gallery_image['id'];
				if ($gallery_image['image'] && file_exists(DIR_IMAGE . $gallery_image['image'])) {
					$image = $gallery_image['image'];
				} else {
					$image = 'no_image.jpg';
				}
				
				$this->data['album_data']['gallery_images'][] = array(
					'id'      		=> $gallery_image['id'],
					'description'	=> ((isset($gallery_image['description'])) ? $gallery_image['description'] : ''),
					'image'     	=> $image,
					'thumb'     	=> $this->model_tool_image->resize($image, 100, 100)
				);
			}
			$this->data['image_row'] = max($id_s)+1;
		}else{
			$this->data['image_row'] = 0;
		}
		if (empty($this->data['album_data']['cover_image']['image'])) {
			$this->data['album_data']['cover_image']['image'] = 'no_image.jpg';
			$this->data['album_data']['cover_image']['thumb'] = $this->data['no_image'];
		}else{
			$this->data['album_data']['cover_image']['thumb'] = $this->model_tool_image->resize($this->data['album_data']['cover_image']['image'], 100, 100);
		}

		//GetCategoryList
		$categories = $this->model_album_index->getAllCategories();
		$this->data['categories'] = $this->getAllCategories($categories);

		//Set language variables
		$this->data['module_row_ids'] = $this->config->get('config_gallery_track_code');

		//Album
		$this->data['arr_js_lib_types'] 	= $this->language->get('arr_js_lib_types');
		$this->data['album_types'] = $this->language->get('album_types');
		
		$this->data['text_image_manager'] 	= $this->language->get('text_image_manager');
		
		$this->data['entry_album_name'] 	= $this->language->get('entry_album_name');
		$this->data['entry_album_seo_name'] = $this->language->get('entry_album_seo_name');
		$this->data['entry_album_type'] 	= $this->language->get('entry_album_type');
		$this->data['entry_gallery_photos_limit'] 	= $this->language->get('entry_gallery_photos_limit');
		$this->data['entry_gallery_show_limiter'] 	= $this->language->get('entry_gallery_show_limiter');
		$this->data['entry_thumb_size'] 	= $this->language->get('entry_thumb_size');
		$this->data['entry_popup_size'] 	= $this->language->get('entry_popup_size');
		$this->data['entry_category_list'] 	= $this->language->get('entry_category_list');
		$this->data['entry_include_additional_images'] 	= $this->language->get('entry_include_additional_images');
		$this->data['entry_directory'] 		= $this->language->get('entry_directory');
		$this->data['entry_galery_images'] 	= $this->language->get('entry_galery_images');
		$this->data['entry_cover_image'] 	= $this->language->get('entry_cover_image');
		$this->data['entry_show_gallery_album_description'] 	= $this->language->get('entry_show_gallery_album_description');
		$this->data['entry_use_lazyload'] 	= $this->language->get('entry_use_lazyload');
		
		$this->data['entry_title'] 	= $this->language->get('entry_title');
		$this->data['entry_h1'] 	= $this->language->get('entry_h1');
		$this->data['entry_description'] 	= $this->language->get('entry_description');
		$this->data['entry_meta_keywords'] 	= $this->language->get('entry_meta_keywords');
		$this->data['entry_meta_description'] 	= $this->language->get('entry_meta_description');
		$this->data['entry_album_status'] 	= $this->language->get('entry_album_status');
		$this->data['entry_sort_order'] 	= $this->language->get('entry_sort_order');
		$this->data['entry_js_library'] = $this->language->get('entry_js_library');

		// h2
		$this->data['text_album_page_settings'] = $this->language->get('text_album_page_settings');
		$this->data['text_album_data'] = $this->language->get('text_album_data');
		
		$this->data['text_select_all'] 	= $this->language->get('text_select_all');
		$this->data['text_unselect_all'] 	= $this->language->get('text_unselect_all');
		$this->data['text_browse'] 	= $this->language->get('text_browse');
		$this->data['text_clear'] 	= $this->language->get('text_clear');
		$this->data['text_image_manager'] 	= $this->language->get('text_image_manager');
		$this->data['text_placeholder_description'] 	= $this->language->get('text_placeholder_description');
		$this->data['text_enabled'] 	= $this->language->get('text_enabled');
		$this->data['text_disabled'] 	= $this->language->get('text_disabled');
		$this->data['text_error_check_form'] 	= $this->language->get('text_error_check_form');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		//Tabs
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');

		//Buttons
		$this->data['button_text_save'] = $this->language->get('button_text_save');
		$this->data['button_text_cancel'] = $this->language->get('button_text_cancel');
		$this->data['button_text_add_image'] = $this->language->get('button_text_add_image');
		$this->data['button_text_remove_image'] 	= $this->language->get('button_text_remove_image');
		
		//Set links
		$this->data['text_section_album_list'] 	= $this->language->get('section_album_list');
		$this->data['text_section_modules'] 	= $this->language->get('section_modules');
		$this->data['text_section_settings'] 	= $this->language->get('section_settings');

		$this->data['link_section_album_list'] 	= $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_modules'] 	= $this->url->link('album/modules', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_section_settings'] 	= $this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL');
  		
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
       		'text'      => $this->language->get('section_album_list'),
			'href'      => $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('section_edit_album'),
			'href'      => $this->url->link('album/album/edit', 'album_id='.$this->request->get['album_id'], 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		//Set links
		$this->data['cancel'] = $this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['save'] = $this->url->link('album/album/add', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit'] = $this->url->link('album/album/edit', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		//Set settings
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->template = 'album/album_add.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	public function delete() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
			$this->language->load('album/index'); 
			
			$this->load->model('album/index');

			$this->model_album_index->deleteAlbum($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_success_deleted');

			$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
		}else{
			$this->error['warning'] = $this->language->get('error_permission');
			$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function copy() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePerms()) {
			$this->language->load('album/index'); 
			
			$this->load->model('album/index');

			$this->model_album_index->copyAlbum($this->request->post['selected'], $this->language->get('text_copy'));
			
			$this->session->data['success'] = $this->language->get('text_success_copied');

			$this->redirect($this->url->link('album/album', 'token=' . $this->session->data['token'], 'SSL'));
		}else{
			$this->error['warning'] = $this->language->get('error_permission');
			$this->redirect($this->url->link('background/pattern', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	private function validatePerms() {
		if (!$this->user->hasPermission('modify', 'album/album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
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
<?php
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
class ControllerAlbumSettings extends Controller {
	private $error = array();
	public function index() {
		$this->language->load('album/index'); 
		$this->document->setTitle($this->language->get('section_settings'));
		$this->document->addStyle('view/stylesheet/photo_gallery.manager.css');
		$this->load->model('album/index');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->load->model('setting/setting');
			$this->session->data['success'] = $this->language->get('text_success_settings');
			$this->model_setting_setting->editSetting('gallery_settings', $this->request->post);

			// Add SEO URL
			// Get seo query
			$seo_query = $this->db->query("SELECT * FROM `". DB_PREFIX ."url_alias` WHERE `query` = 'gallery/gallery' LIMIT 1");
			// (Check) and (update if exists) or (delete if exists and empty)
			if (empty($seo_query->row)) {
				if ($this->request->post['config_galleries_seo_name']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` (`query`, `keyword`) VALUES ('gallery/gallery', '" . $this->db->escape($this->request->post['config_galleries_seo_name']) . "')");
				}
			}else{
				if ((!isset($this->request->post['config_galleries_seo_name'])) || (empty($this->request->post['config_galleries_seo_name']))) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `url_alias_id` = '".$seo_query->row['url_alias_id']."'");
				}else{
					$this->db->query("UPDATE `" . DB_PREFIX . "url_alias` SET `query` = 'gallery/gallery', keyword = '" . $this->db->escape($this->request->post['config_galleries_seo_name']) . "' WHERE `url_alias_id` = '".$seo_query->row['url_alias_id']."'");
				}
			} 
			$this->cache->delete('gallery_album_photos');		
			$this->cache->delete('album_photos');		
			$this->cache->delete('album_gallery');		
			$this->cache->delete('album_module');		
			$this->cache->delete('seo_pro');
			$this->redirect($this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['heading_title'] = $this->language->get('section_settings');
		$this->data['config_gallery_modules_cache_enabled'] = $this->config->get('config_gallery_modules_cache_enabled');
		
		$this->data['config_gallery_cover_image_width'] 		= $this->config->get('config_gallery_cover_image_width');
		$this->data['config_gallery_cover_image_height'] 		= $this->config->get('config_gallery_cover_image_height');
		$this->data['config_gallery_show_counter'] 				= $this->config->get('config_gallery_show_counter');
		$this->data['config_gallery_show_description'] 			= $this->config->get('config_gallery_show_description');
		$this->data['config_gallery_include_colorbox'] 			= $this->config->get('config_gallery_include_colorbox');
		$this->data['config_gallery_include_lightbox'] 			= $this->config->get('config_gallery_include_lightbox');
		$this->data['config_gallery_include_fancybox'] 			= $this->config->get('config_gallery_include_fancybox');
		$this->data['config_gallery_include_jstabs'] 			= $this->config->get('config_gallery_include_jstabs');
		$this->data['config_gallery_include_lazyload'] 			= $this->config->get('config_gallery_include_lazyload');
		$this->data['config_galleries_seo_name'] 				= $this->config->get('config_galleries_seo_name');
		$this->data['config_galleries_include_seo_path'] 		= $this->config->get('config_galleries_include_seo_path');
		$this->data['config_gallery_module_category_layout_id'] = $this->config->get('config_gallery_module_category_layout_id');
		$this->data['config_gallery_module_product_layout_id'] 	= $this->config->get('config_gallery_module_product_layout_id');

		$this->data['config_galleries_title'] 					= $this->config->get('config_galleries_title');
		$this->data['config_galleries_h1_title'] 				= $this->config->get('config_galleries_h1_title');
		$this->data['config_galleries_meta_keywords'] 			= $this->config->get('config_galleries_meta_keywords');
		$this->data['config_galleries_meta_description'] 		= $this->config->get('config_galleries_meta_description');
		$this->data['config_galleries_description'] 			= $this->config->get('config_galleries_description');
		
		$this->data['config_gallery_track_code2'] = $this->config->get('config_gallery_track_code2');

		$this->data['text_yes'] 		= $this->language->get('text_yes');
		$this->data['text_no'] 			= $this->language->get('text_no');
		$this->data['entry_category_layout'] 	= $this->language->get('entry_category_layout');
		$this->data['entry_product_layout'] 	= $this->language->get('entry_product_layout');
		
		$this->data['entry_gallery_cover_image_dimension'] 	= $this->language->get('entry_gallery_cover_image_dimension');
		$this->data['entry_gallery_show_counter'] 			= $this->language->get('entry_gallery_show_counter');
		$this->data['entry_gallery_show_description'] 		= $this->language->get('entry_gallery_show_description');
		$this->data['entry_galleries_seo_name'] 			= $this->language->get('entry_galleries_seo_name');
		$this->data['entry_galleries_include_seo_path'] 		= $this->language->get('entry_galleries_include_seo_path');
		
		$this->data['entry_gallery_include_colorbox'] 			= $this->language->get('entry_gallery_include_colorbox');
		$this->data['entry_gallery_include_lightbox'] 			= $this->language->get('entry_gallery_include_lightbox');
		$this->data['entry_gallery_include_fancybox'] 			= $this->language->get('entry_gallery_include_fancybox');
		$this->data['entry_gallery_include_jstabs'] 			= $this->language->get('entry_gallery_include_jstabs');
		$this->data['entry_gallery_include_lazyload'] 			= $this->language->get('entry_gallery_include_lazyload');
		//meta
		$this->data['entry_title'] 			= $this->language->get('entry_title');
		$this->data['entry_h1'] 			= $this->language->get('entry_h1');
		$this->data['entry_meta_keywords'] 	= $this->language->get('entry_meta_keywords');
		$this->data['entry_meta_description'] 	= $this->language->get('entry_meta_description');
		$this->data['entry_description'] 	= $this->language->get('entry_description');
		
		//Set language variables

		//Album
		$this->data['text_list_empty'] = $this->language->get('text_list_empty');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['arr_enabled'] 	= array($this->language->get('text_disabled'),$this->language->get('text_enabled'));
		
		//Buttons
		$this->data['button_text_save'] = $this->language->get('button_text_save');
		$this->data['button_text_cancel'] = $this->language->get('button_text_cancel');

		//Settings
		$this->data['entry_enable_full_cache'] = $this->language->get('entry_enable_full_cache');
		//h2
		$this->data['text_js_library_settings'] = $this->language->get('text_js_library_settings');
		$this->data['text_albums_settings'] = $this->language->get('text_albums_settings');
		$this->data['text_general_settings'] = $this->language->get('text_general_settings');
		$this->data['text_modules_settings'] = $this->language->get('text_modules_settings');

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
  		
  		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

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
       		'text'      => $this->language->get('section_settings'),
			'href'      => $this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL'),
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
		$this->data['save'] = $this->url->link('album/settings', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		//Set settings
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		$this->template = 'album/settings.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'album/settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {return true;} else {return false;}
	}
}
?>
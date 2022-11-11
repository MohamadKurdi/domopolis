<?php
class ControllerModulencategory extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('module/ncategory');

		$this->document->setTitle($this->language->get('title_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ncategory', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_bnews_image'] = $this->language->get('text_bnews_image');
		$this->data['text_bnews_thumb'] = $this->language->get('text_bnews_thumb');
		$this->data['text_bnews_order'] = $this->language->get('text_bnews_order');
		$this->data['text_bsettings'] = $this->language->get('text_bsettings');
		$this->data['text_bwidth'] = $this->language->get('text_bwidth');
		$this->data['text_bheight'] = $this->language->get('text_bheight');
		$this->data['text_yess'] = $this->language->get('text_bysort');
		$this->data['text_noo'] = $this->language->get('text_latest');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['tab_disqus'] = $this->language->get('tab_disqus');
		$this->data['tab_other'] = $this->language->get('tab_other');
		$this->data['tab_disqus_enable'] = $this->language->get('tab_disqus_enable');
		$this->data['tab_disqus_sname'] = $this->language->get('tab_disqus_sname');
		$this->data['text_module_form'] = $this->language->get('text_module_form');
		$this->data['tab_facebook'] = $this->language->get('tab_facebook');
		$this->data['tab_facebook_status'] = $this->language->get('tab_facebook_status');
		$this->data['tab_facebook_appid'] = $this->language->get('tab_facebook_appid');
		$this->data['tab_facebook_theme'] = $this->language->get('tab_facebook_theme');
		$this->data['tab_facebook_posts'] = $this->language->get('tab_facebook_posts');
		
		
		$this->data['text_bnews_display_style'] = $this->language->get('text_bnews_display_style');
		$this->data['text_bnews_dscol'] = $this->language->get('text_bnews_dscol');
		$this->data['text_bnews_dscols'] = $this->language->get('text_bnews_dscols');
		$this->data['text_bnews_dselements'] = $this->language->get('text_bnews_dselements');
		$this->data['text_bnews_dse_image'] = $this->language->get('text_bnews_dse_image');
		$this->data['text_bnews_dse_name'] = $this->language->get('text_bnews_dse_name');
		$this->data['text_bnews_dse_da'] = $this->language->get('text_bnews_dse_da');
		$this->data['text_bnews_dse_du'] = $this->language->get('text_bnews_dse_du');
		$this->data['text_bnews_dse_author'] = $this->language->get('text_bnews_dse_author');
		$this->data['text_bnews_dse_category'] = $this->language->get('text_bnews_dse_category');
		$this->data['text_bnews_dse_desc'] = $this->language->get('text_bnews_dse_desc');
		$this->data['text_bnews_dse_button'] = $this->language->get('text_bnews_dse_button');
		$this->data['text_bnews_dse_com'] = $this->language->get('text_bnews_dse_com');
		$this->data['text_bnews_dse_custom'] = $this->language->get('text_bnews_dse_custom');
		$this->data['text_tplpick'] = $this->language->get('text_tplpick');
		$this->data['text_facebook_tags'] = $this->language->get('text_facebook_tags');
		$this->data['text_twitter_tags'] = $this->language->get('text_twitter_tags');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
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
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/ncategory', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/ncategory', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['bnews_order'])) {
			$this->data['bnews_order'] = $this->request->post['bnews_order'];
		} else {
			$this->data['bnews_order'] = $this->config->get('bnews_order');
		}
		if (isset($this->request->post['bnews_fbcom_status'])) {
			$this->data['bnews_fbcom_status'] = $this->request->post['bnews_fbcom_status'];
		} else {
			$this->data['bnews_fbcom_status'] = $this->config->get('bnews_fbcom_status');
		}
		if (isset($this->request->post['bnews_fbcom_appid'])) {
			$this->data['bnews_fbcom_appid'] = $this->request->post['bnews_fbcom_appid'];
		} else {
			$this->data['bnews_fbcom_appid'] = $this->config->get('bnews_fbcom_appid');
		}
		if (isset($this->request->post['bnews_fbcom_theme'])) {
			$this->data['bnews_fbcom_theme'] = $this->request->post['bnews_fbcom_theme'];
		} else {
			$this->data['bnews_fbcom_theme'] = $this->config->get('bnews_fbcom_theme');
		}
		if (isset($this->request->post['bnews_fbcom_posts'])) {
			$this->data['bnews_fbcom_posts'] = $this->request->post['bnews_fbcom_posts'];
		} else {
			$this->data['bnews_fbcom_posts'] = $this->config->get('bnews_fbcom_posts');
		}
		if (isset($this->request->post['bnews_disqus_status'])) {
			$this->data['bnews_disqus_status'] = $this->request->post['bnews_disqus_status'];
		} else {
			$this->data['bnews_disqus_status'] = $this->config->get('bnews_disqus_status');
		}
		if (isset($this->request->post['bnews_disqus_sname'])) {
			$this->data['bnews_disqus_sname'] = $this->request->post['bnews_disqus_sname'];
		} else {
			$this->data['bnews_disqus_sname'] = $this->config->get('bnews_disqus_sname');
		}
		if (isset($this->request->post['bnews_facebook_tags'])) {
			$this->data['bnews_facebook_tags'] = $this->request->post['bnews_facebook_tags'];
		} else {
			$this->data['bnews_facebook_tags'] = $this->config->get('bnews_facebook_tags');
		}
		if (isset($this->request->post['bnews_twitter_tags'])) {
			$this->data['bnews_twitter_tags'] = $this->request->post['bnews_twitter_tags'];
		} else {
			$this->data['bnews_twitter_tags'] = $this->config->get('bnews_twitter_tags');
		}
		if (isset($this->request->post['bnews_display_style'])) {
			$this->data['bnews_display_style'] = $this->request->post['bnews_display_style'];
		} else {
			$this->data['bnews_display_style'] = $this->config->get('bnews_display_style');
		}
		$this->data['bnews_display_elements_s'] = "";
		if (isset($this->request->post['bnews_display_elements'])) {
			$this->data['bnews_display_elements'] = $this->request->post['bnews_display_elements'];
		} elseif($this->config->get('bnews_display_elements')) {
			$this->data['bnews_display_elements'] = $this->config->get('bnews_display_elements');
		} else {
			$this->data['bnews_display_elements'] = array();
			$this->data['bnews_display_elements_s'] = "none";
		}
		if (isset($this->request->post['bnews_image_width'])) {
			$this->data['bnews_image_width'] = $this->request->post['bnews_image_width'];
		} else {
			$this->data['bnews_image_width'] = $this->config->get('bnews_image_width');
		}
		if (isset($this->request->post['bnews_image_height'])) {
			$this->data['bnews_image_height'] = $this->request->post['bnews_image_height'];
		} else {
			$this->data['bnews_image_height'] = $this->config->get('bnews_image_height');
		}
		if (isset($this->request->post['bnews_thumb_width'])) {
			$this->data['bnews_thumb_width'] = $this->request->post['bnews_thumb_width'];
		} else {
			$this->data['bnews_thumb_width'] = $this->config->get('bnews_thumb_width');
		}
		if (isset($this->request->post['bnews_thumb_height'])) {
			$this->data['bnews_thumb_height'] = $this->request->post['bnews_thumb_height'];
		} else {
			$this->data['bnews_thumb_height'] = $this->config->get('bnews_thumb_height');
		}
		if (isset($this->request->post['bnews_tplpick'])) {
			$this->data['bnews_tplpick'] = $this->request->post['bnews_tplpick'];
		} else {
			$this->data['bnews_tplpick'] = $this->config->get('bnews_tplpick');
		}
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['ncategory_module'])) {
			$this->data['modules'] = $this->request->post['ncategory_module'];
		} elseif ($this->config->get('ncategory_module')) { 
			$this->data['modules'] = $this->config->get('ncategory_module');
		}	
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/ncategory.tpl';
		$this->children = array(
			'common/header',
			'common/newspanel',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ncategory')) {
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
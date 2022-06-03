<?php
	class ControllerModuleSocialAuth extends Controller {
		private $error = array();
		
		// version 2.1.1
		
		public function index() {
			$this->load->language('module/social_auth');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/setting');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_setting_setting->editSetting('social_auth', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->response->redirect(str_replace('&amp;', '&', $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL')));
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title').' v-2.1.1';
			
			$this->data['text_edit'] = $this->language->get('text_edit');
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			
			$this->data['entry_status'] = $this->language->get('entry_status');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL')
			);
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/social_auth', 'token=' . $this->session->data['token'], 'SSL')
			);
			
			$this->data['action'] = $this->url->link('module/social_auth', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
			
			$data_fields = [
            'social_auth_facebook_app_id',
            'social_auth_facebook_secret_key',
            
            'social_auth_google_app_id',
            'social_auth_google_secret_key',
            
            'social_auth_insatagram_client_id',
            'social_auth_insatagram_secret_key',
			];
			
			foreach($data_fields as $field){
				if (isset($this->request->post[$field])) {
					$this->data[$field] = $this->request->post[$field];
					} else {
					$this->data[$field] = $this->config->get($field);
				}
			}
			
			$this->addDataTable();
			
			$this->data['social_auth_facebook_calback_href'] = HTTPS_CATALOG.'fb_login';
			
			$this->data['social_auth_google_calback_href'] = HTTPS_CATALOG.'gp_login';
			
			$this->data['social_auth_instagram_calback_href'] = HTTPS_CATALOG.'insta_login';
			
			
			$this->template = 'module/social_auth.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function addDataTable() {
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'module/social_auth/iframeFacebookLogin'");
			if(!$query->num_rows){
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET keyword = 'fb_login', query = 'module/social_auth/iframeFacebookLogin'");
				$this->cache->delete('seo_pro');
			}
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'module/social_auth/iframeGoogleLogin'");
			if(!$query->num_rows){
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET keyword = 'gp_login', query = 'module/social_auth/iframeGoogleLogin'");
				$this->cache->delete('seo_pro');
			}
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query = 'module/social_auth/iframeInstagramLogin'");
			if(!$query->num_rows){
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET keyword = 'insta_login', query = 'module/social_auth/iframeInstagramLogin'");
				$this->cache->delete('seo_pro');
			}
			
			$find_field_query = $this->db->query("SHOW COLUMNS FROM  " . DB_PREFIX . "customer");
			if(!in_array('social_id',array_column($find_field_query->rows, 'Field'))){
				$this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD social_id text COLLATE 'utf8_general_ci' NOT NULL AFTER token;");
			}
		}
		
		protected function validate() {
			if (!$this->user->hasPermission('modify', 'module/social_auth')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			return !$this->error;
		}
	}

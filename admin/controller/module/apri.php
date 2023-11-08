<?php
class ControllerModuleAPRI extends Controller {
	private $error = array(); 
	
	public function install() {
		$this->load->model('module/apri');
		
		$this->model_module_apri->createTables();
	}
	
	public function index() {   
		$this->load->language('module/apri');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('apri', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_mail'] = $this->language->get('tab_mail');
		
		$this->data['text_help_customized'] = $this->language->get('text_help_customized');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_cron_password'] = $this->language->get('entry_cron_password');
		$this->data['entry_start_date'] = $this->language->get('entry_start_date');
		$this->data['entry_days_after'] = $this->language->get('entry_days_after');
		$this->data['entry_allowed_statuses'] = $this->language->get('entry_allowed_statuses');
		$this->data['entry_use_html_email'] = $this->language->get('entry_use_html_email');
		$this->data['entry_allow_unsubscribe'] = $this->language->get('entry_allow_unsubscribe');
		$this->data['entry_log_to_admin'] = $this->language->get('entry_log_to_admin');
		$this->data['entry_mail_subject'] = $this->language->get('entry_mail_subject');
		$this->data['entry_mail_message'] = $this->language->get('entry_mail_message');
		$this->data['entry_mail_log_subject'] = $this->language->get('entry_mail_log_subject');
		$this->data['entry_mail_log_message'] = $this->language->get('entry_mail_log_message');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['cron_password'])) {
			$this->data['error_cron_password'] = $this->error['cron_password'];
		} else {
			$this->data['error_cron_password'] = '';
		}
		
		if (isset($this->error['allowed_statuses'])) {
			$this->data['error_allowed_statuses'] = $this->error['allowed_statuses'];
		} else {
			$this->data['error_allowed_statuses'] = '';
		}
		
		if (isset($this->error['use_html_email'])) {
			$this->data['error_use_html_email'] = $this->error['use_html_email'];
		} else {
			$this->data['error_use_html_email'] = '';
		}
		
		if (isset($this->error['mail_subject'])) {
			$this->data['error_mail_subject'] = $this->error['mail_subject'];
		} else {
			$this->data['error_mail_subject'] = '';
		}
		
		if (isset($this->error['mail_message'])) {
			$this->data['error_mail_message'] = $this->error['mail_message'];
		} else {
			$this->data['error_mail_message'] = '';
		}
		
		if (isset($this->error['mail_log_subject'])) {
			$this->data['error_mail_log_subject'] = $this->error['mail_log_subject'];
		} else {
			$this->data['error_mail_log_subject'] = '';
		}
		
		if (isset($this->error['mail_log_message'])) {
			$this->data['error_mail_log_message'] = $this->error['mail_log_message'];
		} else {
			$this->data['error_mail_log_message'] = '';
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
			'href'      => $this->url->link('module/apri', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/apri', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);

		if (isset($this->request->post['apri_secret_code'])){
			$this->data['apri_secret_code'] = $this->request->post['apri_secret_code'];
		} elseif ( $this->config->get('apri_secret_code')){
			$this->data['apri_secret_code'] = $this->config->get('apri_secret_code');
		} else {
			$this->data['apri_secret_code'] = '';
		}
		
		if (isset($this->request->post['apri_start_date'])){
			$this->data['apri_start_date'] = $this->request->post['apri_start_date'];
		} elseif ( $this->config->get('apri_start_date')){
			$this->data['apri_start_date'] = $this->config->get('apri_start_date');
		} else {
			$this->data['apri_start_date'] = '';
		}
		
		if (isset($this->request->post['apri_days_after'])){
			$this->data['apri_days_after'] = $this->request->post['apri_days_after'];
		} elseif ( $this->config->get('apri_days_after')){
			$this->data['apri_days_after'] = $this->config->get('apri_days_after');
		} else {
			$this->data['apri_days_after'] = '';
		}
		
		if (isset($this->request->post['apri_allowed_statuses'])){   
			$this->data['apri_allowed_statuses'] = $this->request->post['apri_allowed_statuses'];
		} elseif ( $this->config->get('apri_allowed_statuses')){
			$this->data['apri_allowed_statuses'] = $this->config->get('apri_allowed_statuses');
		} else {
			$this->data['apri_allowed_statuses'] = array();
		}
		
		if (isset($this->request->post['apri_allow_unsubscribe'])){   
			$this->data['apri_allow_unsubscribe'] = $this->request->post['apri_allow_unsubscribe'];
		} elseif ( $this->config->get('apri_allow_unsubscribe')){
			$this->data['apri_allow_unsubscribe'] = $this->config->get('apri_allow_unsubscribe');
		} else {
			$this->data['apri_allow_unsubscribe'] = 0;
		}		
		
		if (isset($this->request->post['apri_log_admin'])){   
			$this->data['apri_log_admin'] = $this->request->post['apri_log_admin'];
		} elseif ( $this->config->get('apri_log_admin')){
			$this->data['apri_log_admin'] = $this->config->get('apri_log_admin');
		} else {
			$this->data['apri_log_admin'] = 0;
		}
		
		if (isset($this->request->post['apri_use_html_email'])){   
			$this->data['apri_use_html_email'] = $this->request->post['apri_use_html_email'];
		} elseif ( $this->config->get('apri_use_html_email')){
			$this->data['apri_use_html_email'] = $this->config->get('apri_use_html_email');
		} else {
			$this->data['apri_use_html_email'] = '';
		}
		
		if (isset($this->request->post['apri_mail'])){
			$this->data['apri_mail'] = $this->request->post['apri_mail'];
		} elseif ( $this->config->get('apri_mail')){
			$this->data['apri_mail'] = $this->config->get('apri_mail');
		} else {
			$this->data['apri_mail'] = '';
		}

		$this->load->model('localisation/order_status');	
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->data['token'] = $this->session->data['token'];
						
		$this->template = 'module/apri.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
	
		if (!$this->user->hasPermission('modify', 'module/apri')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$dinamic_strlen = 'utf8_strlen';
		
		if ( !function_exists('utf8_strlen') ) {
			$dinamic_strlen = 'strlen';
		}
		
		if ($dinamic_strlen($this->request->post['apri_secret_code']) == 0){
			$this->error['cron_password'] = $this->language->get('error_cron_password');
		}
		
		if (!isset($this->request->post['apri_allowed_statuses']) || count($this->request->post['apri_allowed_statuses']) < 1){
			$this->error['allowed_statuses'] = $this->language->get('error_allowed_statuses');
		}
		
		if ($this->request->post['apri_use_html_email'] == 1 && !$this->isHTMLEmailExtensionInstalled() ) {
			$this->error['use_html_email'] = $this->language->get('error_html_email_not_installed');
			$this->error['warning'] = $this->language->get('error_html_email_not_installed');
		}

		foreach ($this->request->post['apri_mail'] as $language_id => $value) {
			if ($dinamic_strlen($value['subject']) < 1) {
        		$this->error['mail_subject'][$language_id] = $this->language->get('error_mail_subject');
      		}
			
			if ($dinamic_strlen($value['message']) < 1) {
        		$this->error['mail_message'][$language_id] = $this->language->get('error_mail_message');
      		}
			
			if ($this->request->post['apri_allow_unsubscribe'] == 0 && $dinamic_strlen($value['message']) > 1 && strpos($value['message'], "{unsubscribe_link}") !== false) {
				$this->error['mail_message'][$language_id] = $this->language->get('error_mail_message_unsubscribe');
			}
			
			if ($dinamic_strlen($value['log_subject']) < 1) {
        		$this->error['mail_log_subject'][$language_id] = $this->language->get('error_mail_log_subject');
      		}
			
			if ($dinamic_strlen($value['log_message']) < 1) {
        		$this->error['mail_log_message'][$language_id] = $this->language->get('error_mail_log_message');
      		}
		}		
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	private function isHTMLEmailExtensionInstalled() {
		$installed = false;
		
		if ($this->config->get('html_email_default_word') && file_exists(DIR_APPLICATION . 'model/tool/html_email.php') && file_exists(DIR_CATALOG . 'model/tool/html_email.php')) {
			$installed = true;	
		}
		
		return true;
	}
}
?>
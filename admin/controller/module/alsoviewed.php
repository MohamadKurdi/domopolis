<?php
class ControllerModuleAlsoviewed extends Controller {
	private $error = array(); 
	public function index() {   
		$this->language->load('module/alsoviewed');
        $this->load->model('module/alsoviewed');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$this->data['error_warning'] = '';
	
		$this->document->addScript('view/javascript/alsoviewed/bootstrap/js/bootstrap.min.js');
		$this->document->addStyle('view/javascript/alsoviewed/bootstrap/css/bootstrap.min.css');
		$this->document->addStyle('view/javascript/alsoviewed/font-awesome/css/font-awesome.min.css');
		$this->document->addStyle('view/stylesheet/alsoviewed.css');		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (!$this->user->hasPermission('modify', 'module/alsoviewed')) {
				$this->validate();
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
			}		
		
			$this->model_setting_setting->editSetting('AlsoViewed', $this->request->post);		
				
			$this->session->data['success'] = $this->language->get('text_success');
			
			
			$selectedTab = (empty($this->request->post['selectedTab'])) ? 0 : $this->request->post['selectedTab'];
			$this->redirect($this->url->link('module/alsoviewed', 'token=' . $this->session->data['token'] . '&tab='.$selectedTab, 'SSL'));
		}
		
		$alsoViewedStats = $this->db->query("SELECT * FROM `" . DB_PREFIX . "alsoviewed` ORDER BY `number` DESC LIMIT 100");

		$this->data['alsoViewedStats'] = $alsoViewedStats->rows;

		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		foreach ($this->data['alsoViewedStats'] as $k=>$v) { 				
		
		if (!empty($v['high']) && (!empty($v['low'])) ) {
			$this->data['alsoViewedStats'][$k]['highProduct'] = $this->model_catalog_product->getProduct($v['high']);	
			if(isset($this->data['alsoViewedStats'][$k]['highProduct']['image'])){
				$this->data['alsoViewedStats'][$k]['highProduct']['image'] = $this->model_tool_image->resize($this->data['alsoViewedStats'][$k]['highProduct']['image'], 80, 80); 
			}
			
			$this->data['alsoViewedStats'][$k]['lowProduct'] = $this->model_catalog_product->getProduct($v['low']);	
			if(isset($this->data['alsoViewedStats'][$k]['lowProduct']['image'])){
				$this->data['alsoViewedStats'][$k]['lowProduct']['image'] = $this->model_tool_image->resize($this->data['alsoViewedStats'][$k]['lowProduct']['image'], 80, 80); 
			}
		} else {
		 unset($this->data['alsoViewedStats'][$k]);
		}
		}
	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_load_in_selector'] = $this->language->get('text_load_in_selector');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_layout_options'] = $this->language->get('entry_layout_options');
		$this->data['entry_position_options'] = $this->language->get('entry_position_options');
		$this->data['entry_enable_disable']	= $this->language->get('entry_enable_disable');
		$this->data['entry_api'] = $this->language->get('entry_api');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_preview'] = $this->language->get('entry_preview');
		$this->data['entry_design']	= $this->language->get('entry_design');
		$this->data['entry_no_design'] = $this->language->get('entry_no_design');
		$this->data['entry_wrap_into_widget'] = $this->language->get('entry_wrap_into_widget');
		$this->data['entry_yes'] = $this->language->get('entry_yes');
		$this->data['entry_no'] = $this->language->get('entry_no');
		$this->data['entry_wrapper_title'] = $this->language->get('entry_wrapper_title');
		$this->data['entry_button_name'] = $this->language->get('entry_button_name');
		$this->data['entry_use_oc_settings'] = $this->language->get('entry_use_oc_settings');
		$this->data['entry_assign_to_cg'] = $this->language->get('entry_assign_to_cg');
		$this->data['entry_new_user_details'] = $this->language->get('entry_new_user_details');
		$this->data['entry_custom_css'] = $this->language->get('entry_custom_css');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();;
		$this->data['languages'] = $languages;
		$firstLanguage = array_shift($languages);
		$this->data['firstLanguageCode'] = $firstLanguage['code'];
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
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
			'href'      => $this->url->link('module/alsoviewed', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		$this->data['action'] = $this->url->link('module/alsoviewed', 'token=' . $this->session->data['token']);
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
		if (isset($this->request->post['AlsoViewed'])) {
			foreach ($this->request->post['AlsoViewed'] as $key => $value) {
				$this->data['data']['AlsoViewed'][$key] = $this->request->post['AlsoViewed'][$key];
			}
		} else {
			$configValue = $this->config->get('AlsoViewed');
			$this->data['data']['AlsoViewed'] = $configValue;		
		}
		
		$this->data['currenttemplate'] =  $this->config->get('config_template');
		$this->data['modules'] = array();
		if (isset($this->request->post['alsoviewed_module'])) {
			$this->data['modules'] = $this->request->post['alsoviewed_module'];
			exit;
		} elseif ($this->config->get('alsoviewed_module')) { 
			$this->data['modules'] = $this->config->get('alsoviewed_module');
		}	
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		$this->template = 'module/alsoviewed.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
	}
 	public function install()
    {
        $this->load->model('module/alsoviewed');
        $this->model_module_alsoviewed->install();
    }
    public function uninstall()
    {
        $this->load->model('module/alsoviewed');
        $this->model_module_alsoviewed->uninstall();
    }
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/alsoviewed')) {
			$this->error = true;
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
}
?>
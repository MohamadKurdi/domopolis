<?php 
class ControllerModuleMicrodata extends Controller {
	private $error = array();
	
	public function index() {
		$language = $this->language->load('module/microdata');

    $this->document->setTitle($this->language->get('heading_title_top'));
		//$this->document->addStyle('view/stylesheet/microdata.css');
		
    $this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('microdata', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');		
		
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}				
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['token'] = $this->session->data['token'];

		$this->data['tab_schemaorg'] = $this->language->get('tab_schemaorg');
		$this->data['tab_opengraph'] = $this->language->get('tab_opengraph');
		$this->data['tab_twittercard'] = $this->language->get('tab_twittercard');
		$this->data['tab_info'] = $this->language->get('tab_info');
	
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['text_island_type'] = $this->language->get('text_island_type');
		$this->data['text_island_1'] = $this->language->get('text_island_1');
		$this->data['text_island_2'] = $this->language->get('text_island_2');
		$this->data['text_island_3'] = $this->language->get('text_island_3');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_price_1'] = $this->language->get('text_price_1');
		$this->data['text_price_2'] = $this->language->get('text_price_2');
		$this->data['text_twitter_site'] = $this->language->get('text_twitter_site');
		$this->data['text_twitter_creator'] = $this->language->get('text_twitter_creator');
		$this->data['text_twitter_place'] = $this->language->get('text_twitter_place');
		
		$this->data['text_modul'] = $this->language->get('text_modul');
		$this->data['text_autor'] = $this->language->get('text_autor');
		$this->data['text_contacts'] = $this->language->get('text_contacts');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_send_email'] = $this->language->get('text_send_email');
		$this->data['text_skype'] = $this->language->get('text_skype');
		$this->data['text_send_skype'] = $this->language->get('text_send_skype');
		$this->data['text_microdata'] = $this->language->get('text_microdata');
		$this->data['text_dariygray'] = $this->language->get('text_dariygray');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');		

    $config_data = array(
			'schemaorg_status',
			'schemaorg_island',
			'schemaorg_price',
			'opengraph_status',
			'twittercard_status',
			'twitter_site',
			'twitter_creator'
    );
		
    foreach ($config_data as $conf) {
      if (isset($this->request->post[$conf])) {
        $this->data[$conf] = $this->request->post[$conf];
      } else {
        $this->data[$conf] = $this->config->get($conf);
      }
    }
		
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
			'href'      => $this->url->link('module/microdata', 'token=' . $this->session->data['token']),
      'separator' => ' :: '
   	);
		
		$this->data['action'] = $this->url->link('module/microdata', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);		

		require_once( DIR_SYSTEM . 'library/microdata/license-key.php' );
		
		$this->config->set('config_error_display', 0);
		$this->config->set('config_error_log', 0);
		
		if ($license_key != $_SERVER['HTTP_HOST']&&'www.'.$license_key != $_SERVER['HTTP_HOST']) {
			
		}
		
		$this->template = 'module/microdata.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/microdata')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install(){
		
	}
} 
?>
<?php 
class ControllerTotalCodCdekTotal extends Controller { 
	private $error = array(); 
	 
	public function index() 
	{	
		$this->load->model('module/cdek_license');
		$license = $this->model_module_cdek_license->chechLicense();
		if(!$license['status'])
		{
			$action_redirect = $this->url->link('module/cdek_integrator/showLicense', 'token=' . $this->session->data['token'], 'SSL');
			$this->response->redirect(html_entity_decode($action_redirect));
		}
		$installed = $this->model_module_cdek_license->chechInstalled('cod_cdek_total','extension/total');
		if(!$installed['status'])
		{
			$this->response->redirect(html_entity_decode($installed['redirectlink']));
		} 

		$this->load->language('total/cod_cdek_total');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('cod_cdek_total', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

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
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/cod_cdek_total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/cod_cdek_total', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['cod_cdek_total_title'])) {
			$this->data['cod_cdek_total_title'] = $this->request->post['cod_cdek_total_title'];
		} else {
			$this->data['cod_cdek_total_title'] = $this->config->get('cod_cdek_total_title');
		}

		if (isset($this->request->post['cod_cdek_total_sort_order'])) {
			$this->data['cod_cdek_total_sort_order'] = $this->request->post['cod_cdek_total_sort_order'];
		} else {
			$this->data['cod_cdek_total_sort_order'] = $this->config->get('cod_cdek_total_sort_order');
		}
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'total/cod_cdek_total.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'total/cod_cdek_total')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}

	public function chechLicense()
	{
		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getInstalled('total');
		$module_name = 'cod_cdek_total';
		if (!in_array($module_name, $extensions)) 
		{
			$this->session->data['error'] = 'Необхожимо установить модуль Наложенный платеж'; 
			$redirectlink = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
			$redirectlink = str_replace('&amp;', '&',$redirectlink);
			$this->response->redirect(html_entity_decode($redirectlink));
		}
		
		$this->load->model('setting/setting');
		$this->load->model('module/cdek_license');
		$license_status = 'fail';
		$user = 'test';
		$password = 'test';

		if(count($this->request->post))
		{
			if(isset($this->request->post['cdekLicense_user']) && isset($this->request->post['cdekLicense_password']))
			{
				$user = $this->request->post['cdekLicense_user'];
				$password = $this->request->post['cdekLicense_password'];
				$this->model_setting_setting->editSetting('cdekLicense', array('cdekLicense_user'=>$user, 'cdekLicense_password'=>$password));

				$route = 'module/cdek_integrator';
				if(isset($this->request->get['route']))
				{
					$route = $this->request->get['route'];
				}
				$redirectlink = $this->url->link($route, 'token=' . $this->session->data['token'], 'SSL');
				$redirectlink = str_replace('&amp;', '&',$redirectlink);
				$this->response->redirect(html_entity_decode($redirectlink));
			}
		}		
		
		$settings = $this->model_setting_setting->getSetting('cdekLicense');
		if(isset($settings['cdekLicense_user']) && isset($settings['cdekLicense_password']))
		{
			$user = $settings['cdekLicense_user'];
			$password = $settings['cdekLicense_password'];
			$license_status = $this->model_module_cdek_license->chechUser($user, $password);
		}
		else
		{
			$returnarray = array('status'=>false, 'message'=>'Нет лицензии! <a href="http://cdek-souz.ru/users/registrate/" target="_blank">Зарегистрироваться</a>');
			return $returnarray;
		}
		
		if($license_status == 'fail')
		{
			$returnarray = array('status'=>false, 'message'=>'Лицензия не работает! <a href="http://cdek-souz.ru/users/registrate/" target="_blank">Зарегистрироваться</a>');
			return $returnarray;
		}
		else
		{
			$returnarray = array('status'=>true, 'message'=>'Лицензия ок');
			return $returnarray;	
		}
	}
}
?>
<?php
class ControllerModuleNotifyBar extends Controller {
	private $error = array(); 

	public function index() {
		$this->data = $this->language->load('module/notify_bar');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('setting/setting');
		$this->load->model('tool/image');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('notify_bar_module', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
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
			'href'      => $this->url->link('module/notify_bar', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);

   		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['no_image'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 100, 100);

   		$this->data['action'] = $this->url->link('module/notify_bar', 'token=' . $this->session->data['token']);	
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
		$this->data['notify_bar'] = [];
		
		if (isset($this->request->post['notify_bar'])) {
			$this->data['notify_bars'] = $this->request->post['notify_bar'];
		} elseif ($this->config->get('notify_bar')) { 
			$this->data['notify_bars'] = $this->config->get('notify_bar');
		}

		foreach ($this->data['notify_bars'] as &$notify_bar){
			if ($notify_bar['image_mobile']){
				$notify_bar['thumb_mobile'] = $this->model_tool_image->resize($notify_bar['image_mobile'], 100, 100);
			} else {
				$notify_bar['thumb_mobile'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 100, 100);
			}	

			if ($notify_bar['image_pc']){
				$notify_bar['thumb_pc'] = $this->model_tool_image->resize($notify_bar['image_pc'], 100, 100);
			} else {
				$notify_bar['thumb_pc'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 100, 100);
			}	
		}

		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('design/layout');		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

   		$this->template = 'module/notify_bar.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);

 		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/notify_bar')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['notify_bar'])) {
			foreach ($this->request->post['notify_bar'] as $key => $value) {
				if (!is_numeric($value['sort_order'])) {
					$this->error['warning'] = $this->language->get('error_sort_order');
				}
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
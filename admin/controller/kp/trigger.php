<?php
	class ControllerKPTrigger extends Controller { 
		private $error = array();
		
		public function index() {
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => 'Тест триггеров',
			'href'      => $this->url->link('kp/trigger', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->document->setTitle('Тест триггеров');
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'kp/trigger.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
		}
		
		
	}
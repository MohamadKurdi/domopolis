<?php 
	class ControllerInformationPlayStore extends Controller {
		
		public function index(){
			if ($this->config->get('config_android_playstore_enable') && $this->config->get('config_android_playstore_link')){
				
				$this->response->redirect($this->config->get('config_android_playstore_link'));
				
				} else {
				$this->response->redirect($this->url->link('common/home'));
			}
		}
	}	
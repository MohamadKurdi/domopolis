<?php
	class ControllerCommonFooter extends Controller {   
		protected function index() {
			$this->language->load('common/footer');
			
			$this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
						
			if ($this->user->isLogged() && isset($this->session->data['token'])) {
				$this->data['url'] =  $this->url->link('common/home/session', 'token=' . $this->session->data['token']);
				$this->data['token'] = $this->session->data['token'];
				} else {
				$this->data['token'] = false;
			}
						
			$this->template = 'common/footer.tpl';
			
			$this->render();
		}
	}		
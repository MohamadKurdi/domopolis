<?php
	class ControllerCommonFooter extends Controller {   
		protected function index() {
			$this->language->load('common/footer');
			
			$this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
			
			if( 
			!empty( $this->request->get['route'] ) && 
			(
			$this->request->get['route']=='sale/customer/update' ||
			$this->request->get['route']=='sale/order/info' ||
			$this->request->get['route']=='sale/order/update' ||
			$this->request->get['route']=='sale/customer' ||
			$this->request->get['route']=='sale/order'
			)
			)
			{
				$this->load->model('sale/customer');
				$this->load->model('module/socnetauth2');							
				$this->data['socnet_auth_code'] = $this->model_module_socnetauth2->showData();
			}
			
			
			
			if ($this->user->isLogged() && isset($this->session->data['token'])) {
				$this->data['url'] =  $this->url->link('common/home/session', 'token=' . $this->session->data['token'], 'SSL');
				$this->data['token'] = $this->session->data['token'];
				} else {
				$this->data['token'] = false;
			}
			
			
			$this->template = 'common/footer.tpl';
			
			$this->render();
		}
	}		
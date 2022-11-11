<?php
	class ControllerModuleSubscribe extends Controller {
		private $error = array();
		
		public function index($setting = array()) {
			foreach ($this->language->loadRetranslate('account/subscribe') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			
			$this->load->model('account/customer');
			$total = $this->model_account_customer->getTotalCustomersCached();
			$this->data['text_already_subscribed'] = sprintf($this->data['text_already_subscribed'], $total);
			
			$this->data['link_to_account'] = $this->url->link('account/edit', '', 'SSL');
			
			$case = 'unsubscribed';
			
			if ($this->customer->isLogged()){		
				
				if (!filter_var($this->customer->getEmail(), FILTER_VALIDATE_EMAIL)){
					$case = 'logged_but_incorrect_email';
					
					$this->data['button_subscribe'] = $this->data['button_change_mail'];
					$this->data['text'] = $this->data['text_logged_but_incorrect_email'];
					$this->data['class'] = 'text-warning';
					
					} elseif (!$this->emailBlackList->check($this->customer->getEmail())){
					$case = 'logged_emailok_but_blacklisted';
					
					$this->data['button_subscribe'] = $this->data['button_change_mail'];
					$this->data['text'] = sprintf($this->data['text_logged_emailok_but_blacklisted'], $this->customer->getEmail());
					$this->data['class'] = 'text-warning';
					
					
					} elseif (!$this->customer->getNewsletterFull()){
					
					$case = 'logged_not_blacklisted_but_no_subscribed';
					
					$this->data['email'] = $this->customer->getEmail();
					$this->data['text'] = sprintf($this->data['text_logged_not_blacklisted_but_no_subscribed'], $this->data['link_to_account']);
					$this->data['class'] = 'text-warning';
					
					} else {
					$case = 'all_good';
					
				}	
			}
			
			$this->data['case'] = $case;
			
			if ($case == 'all_good'){
				$this->response->setOutput('');
				} else {			
				$this->template = $this->config->get('config_template') . '/template/module/subscribe.tpl';
				$this->response->setOutput($this->render());
			}
		}
		
		public function action() {
			$json = array();
			
			$this->load->model('catalog/subscribe');
			$this->load->model('account/customer');
			
			foreach ($this->language->loadRetranslate('account/subscribe') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$email = trim($this->request->post['email']);
			
			$verifierResult = $this->emailBlackList->checkVerifier($email, true);
			
			if (is_string($verifierResult)) {
				$json['error'] = sprintf($this->data['error_email_possible_verifier'], $verifierResult);
				} elseif (is_bool($verifierResult) && !$verifierResult){
				$json['error'] = $this->data['error_email_not_verifier'];
			}
			
			
			if (!$json) {
				
				if ($this->customer->isLogged()){	
					if (!filter_var($this->customer->getEmail(), FILTER_VALIDATE_EMAIL)){
					
						$this->model_catalog_subscribe->editEmail($email);
						
						} elseif (!$this->emailBlackList->check($this->customer->getEmail())){
						
						$this->model_catalog_subscribe->editEmail($email);
						
					} elseif (!$this->customer->getNewsletterFull()){
					
						$this->model_catalog_subscribe->editEmail($email);
					
					}
				} else {
					if ($this->model_account_customer->getCustomerByEmail($email)){
					
						$json['error'] = sprintf($this->data['error_email_exists'], $email, $this->url->link('account/login', '', 'SSL'));
						
					} else {
						
						$this->model_catalog_subscribe->addSubscribe($email);
						$json['success'] = $this->data['text_success'];
						
					}			
				}
				
				
			}
			
			
			$this->response->setOutput(json_encode($json));
		}
	}						
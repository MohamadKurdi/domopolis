<?php 
	class ControllerAccountSupport extends Controller {
		
		private $error = array(); 
		
		public function index() {
		
			$this->load->language('account/account');
			$this->load->language('information/contact');
		
			foreach ($this->language->loadRetranslate('account/support') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->document->setTitle($this->data['heading_title']);		
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
			/*	$template = new EmailTemplate($this->request, $this->registry);
				
				$tracking = array();
				$tracking['ip_address'] = $this->request->server['REMOTE_ADDR'];
				$tracking['user_agent'] = (isset($this->request->server['HTTP_USER_AGENT'])) ? $this->request->server['HTTP_USER_AGENT'] : '';
				$tracking['accept_language'] = (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) ? $this->request->server['HTTP_ACCEPT_LANGUAGE'] : '';
				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$tracking['remote_host'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
					} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$tracking['remote_host'] = $this->request->server['HTTP_CLIENT_IP'];
					} else {
					$tracking['remote_host'] = '';
				}
				
				$template->addData($this->request->post);
				
				$template->data['enquiry'] = html_entity_decode(str_replace("\n", "<br />", $this->request->post['enquiry']), ENT_QUOTES, 'UTF-8');
				$template->data['user_tracking'] = $tracking;
				
				$mail = new Mail($this->registry); 					
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->request->post['email']);
				$mail->setSender($this->request->post['name']);
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
				$mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
				$template->load('information.contact');
				$mail = $template->hook($mail);
				$mail->send();
				$template->sent();
				*/
				
				$this->load->model('kp/bitrixBot');	
				$this->model_kp_bitrixBot->sendMessage($message = ':!: [B] Кто-то заполнил форму на странице контактов или поддержки[/B]', 
					$attach = Array(
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					Array(
					'MESSAGE' =>  "
					[B]Имя:[/B] ". $this->request->post['name'] ."
					[B]Email:[/B] ". $this->request->post['email'] ."
					[B]Customer ID:[/B]" .  $this->customer->getID() . "
					[B]Сообщение:[/B] " . strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')),
					'COLOR' => '#FF0000',
					),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					Array(
					'MESSAGE' =>  "[B]IP:[/B] " . $_SERVER['REMOTE_ADDR'] . "
					[B]UA:[/B] " . $_SERVER['HTTP_USER_AGENT'] . "
					[B]Страница:[/B] " . $_SERVER['HTTP_REFERER'] . "
					",
					'COLOR' => '#FF0000',
					),
					Array("DELIMITER" => Array(
					'SIZE' => 200,
					'COLOR' => "#c6c6c6"
					)),
					), 
					'chat9667');
		
				
					$this->data['success'] = $this->language->get('text_thanks_for_enquiry');
				//	$this->redirect('information/contact');
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account'),        	
			'separator' => false
			);
			
			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
				} else {
				$this->data['error_name'] = '';
			}
			
			if (isset($this->error['email'])) {
				$this->data['error_email'] = $this->error['email'];
				} else {
				$this->data['error_email'] = '';
			}		
			
			if (isset($this->error['enquiry'])) {
				$this->data['error_enquiry'] = $this->error['enquiry'];
				} else {
				$this->data['error_enquiry'] = '';
			}		
			
			if (isset($this->error['captcha'])) {
				$this->data['error_captcha'] = $this->error['captcha'];
				} else {
				$this->data['error_captcha'] = '';
			}	
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['action'] = $this->url->link('account/support');
			$this->data['store'] = $this->config->get('config_name');
			$this->data['address'] = nl2br($this->config->get('config_address'));
			$this->data['telephone'] = $this->config->get('config_telephone');
			$this->data['telephone2'] = $this->config->get('config_telephone2');
			$this->data['telephone3'] = $this->config->get('config_telephone3');
			
			$this->data['contact_email'] = $this->config->get('config_display_email');
			
			//opt
			$this->data['opt_telephone'] = $this->config->get('config_opt_telephone');
			$this->data['opt_telephone2'] = $this->config->get('config_opt_telephone2');
			$this->data['opt_email'] = $this->config->get('config_opt_email');
			
			$this->data['fax'] = $this->config->get('config_fax');		
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} else {
				$this->data['name'] = $this->customer->getFirstName();
			}
			
			if (isset($this->request->post['email'])) {
				$this->data['email'] = $this->request->post['email'];
				} else {
				$this->data['email'] = $this->customer->getEmail();
			}
			
			if (isset($this->request->post['enquiry'])) {
				$this->data['enquiry'] = $this->request->post['enquiry'];
				} else {
				$this->data['enquiry'] = '';
			}
			
			if (isset($this->request->post['captcha'])) {
				$this->data['captcha'] = $this->request->post['captcha'];
				} else {
				$this->data['captcha'] = '';
			}		
	
			
			$this->template = $this->config->get('config_template') . '/template/account/support.tpl';
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validate() {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
				$this->error['name'] = $this->language->get('error_name');
			}
			
			if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$this->error['email'] = $this->language->get('error_email');
			}
			
			if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
				$this->error['enquiry'] = $this->language->get('error_enquiry');
			}
			
			if ($this->config->get('config_google_recaptcha_contact_enable')){
				$this->load->model('kp/recaptcha');
				
				if (!empty($this->request->post['g-recaptcha-response']) && $this->model_kp_recaptcha->validate($this->request->post['g-recaptcha-response'])){
					//Ок
				} else {
					$this->error['captcha'] = $this->language->get('error_captcha');
				}
				
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}  	  
		}

	}
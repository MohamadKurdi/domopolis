<?php 
	class ControllerInformationFeedback extends Controller {
		
		private $error = array(); 
		
		public function index() {
			$this->language->load('information/contact');
			
			$this->document->setTitle($this->language->get('heading_title'));
						$this->data['text_messenger_contact'] = $this->language->get('text_messenger_contact');
			
			$this->document->addScript('https://www.google.com/recaptcha/api.js');
			
			
			if (!empty($this->request->cookie['simple'])){
				if ($b64decoded = @base64_decode($this->request->cookie['simple'])){
					if ($deserialised = @unserialize($b64decoded)){
						if (!empty($deserialised['checkout_customer'])){
							if (!empty($deserialised['checkout_customer']['main_comment'])){
								$comment_from_fucken_cookie = $deserialised['checkout_customer']['main_comment'];							
							}
						}
					}
				}
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
				$template = new EmailTemplate($this->request, $this->registry);
				
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
				
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->request->post['email']);
				$mail->setSender($this->request->post['name']);
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
				$mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
				$template->load('information.contact');
				$mail = $template->hook($mail);
				$mail->send();
				$template->sent();
				
				$this->redirect($this->url->link('information/contact/success'));
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_location'] = $this->language->get('text_location');
			$this->data['text_contact'] = $this->language->get('text_contact');
			$this->data['text_address'] = $this->language->get('text_address');
			$this->data['text_h1_title_name'] = $this->language->get('h1_title_name');
			
			
			$this->data['text_telephone'] = $this->language->get('text_telephone');
			$this->data['text_fax'] = $this->language->get('text_fax');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			
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
			
			$this->data['action'] = $this->url->link('information/contact');
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
			
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
				
				$this->data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $this->error);
				
				} elseif (true || $this->config->get('config_google_captcha_status')) {
				//   $this->data['site_key'] = $this->config->get('config_google_captcha_public');
                $this->data['captcha'] = '<div class="form-group">
				<div class="">
				<div class="g-recaptcha" data-sitekey="6Le3-SYTAAAAAOthvC5udRjh2D9lgT04wuf-8Zwi"></div>';
                if ($this->data['error_captcha']){
                   /* $this->data['captcha'] .=' <div class="text-danger">'.$this->data['error_captcha'].'</div>'; */
				}
                $this->data['captcha'] .='</div></div>';
				} else {
                $this->data['captcha'] = '';
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/feedback.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/feedback.tpl';
				} else {
				$this->template = 'default/template/information/feedback.tpl';
			}
			
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
		
		public function success() {
			$this->language->load('information/contact');
			
			$this->document->setTitle($this->language->get('heading_title')); 
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_message'] = $this->language->get('text_message');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->link('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
				} else {
				$this->template = 'default/template/common/success.tpl';
			}
			
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
			
			if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$this->error['email'] = $this->language->get('error_email');
			}
			
			if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
				$this->error['enquiry'] = $this->language->get('error_enquiry');
			}
			
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
			//	$this->error['captcha'] = $this->language->get('error_captcha');
			}
			
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');
				
				if ($captcha) {
					$this->error['captcha'] = $captcha;
				}
				} elseif (true || $this->config->get('config_google_captcha_status')) {
				$recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode('6Le3-SYTAAAAAMCtwiLAMZVLdemFMBg73DRC5Sy_') . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);
				
				$recaptcha = json_decode($recaptcha, true);
				
				if (!$recaptcha['success']) {
					$this->error['captcha'] = $this->language->get('error_captcha');
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}  	  
		}
		
		public function captcha() {
			$this->load->library('captcha');
			
			$captcha = new Captcha();
			
			$this->session->data['captcha'] = $captcha->getCode();
			
			$captcha->showImage();
		}	
	}
?>

<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->language->load('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->language->load('mail/forgotten');

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

			$this->model_account_customer->editPassword($this->request->post['email'], $password);

			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

			$message  = $this->language->get('text_greeting') . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;
			
			$template = new EmailTemplate($this->request, $this->registry);
					
			$template->addData($this->request->post);

			$template->data['password'] 				= $password;
			$template->data['account_login'] 			= $this->url->link('account/login', '');
			$template->data['account_login_tracking'] 	= $template->getTracking($template->data['account_login']);

			$mail = new Mail($this->registry); 		
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_display_email'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));

			$template->load('customer.forgotten');
			$mail = $template->hook($mail);
			$mail->send();
			$template->sent();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),     	
			'separator' => $this->language->get('text_separator')
		);


		$this->data['heading_title'] 		= $this->language->get('heading_title');
		$this->data['text_your_email'] 		= $this->language->get('text_your_email');
		$this->data['text_email'] 			= $this->language->get('text_email');
		$this->data['entry_by_email'] 		= $this->language->get('entry_by_email');
		$this->data['entry_by_telephone'] 	= $this->language->get('entry_by_telephone');
		$this->data['entry_email'] 			= $this->language->get('entry_email');
		$this->data['button_continue'] 		= $this->language->get('button_continue');
		$this->data['button_back'] 			= $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] 	= $this->url->link('account/forgotten', '');
		$this->data['back'] 	= $this->url->link('account/login', '');

		$this->template = 'account/forgotten.tpl';

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
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
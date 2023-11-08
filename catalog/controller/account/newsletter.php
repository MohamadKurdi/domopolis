<?php
	class ControllerAccountNewsletter extends Controller {
		public function index() {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/newsletter', '');
				
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/newsletter');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->load->model('account/customer');
				
				$this->model_account_customer->editNewsletter($this->request->post['newsletter']);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('account/account', '', 'SSL'));
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
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', ''),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			
			$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_back'] = $this->language->get('button_back');
			
			$this->data['action'] = $this->url->link('account/newsletter', '');
			
			$this->data['newsletter'] = $this->customer->getNewsletter();
			
			$this->data['back'] = $this->url->link('account/account', '');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
				} else {
				$this->template = 'default/template/account/newsletter.tpl';
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
		
		public function unsubscribe() {
			$this->language->load_full('account/newsletter');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			if ($this->request->get['code']) {
				$this->load->model('account/customer');
				$result = $this->model_account_customer->editNewsletterUnsubscribe($this->request->get['code']);
				if($result) {
					$this->data['text_message'] = sprintf($this->language->get('success_unsubscribe'), $result['email']);
		  			} else {
					$this->data['text_message'] = $this->language->get('error_unsubscribe');
				}
			}
			
			$this->data['breadcrumbs'] = array();
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', ''),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_unsubscribe');
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
	}
?>
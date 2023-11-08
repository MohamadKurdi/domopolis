<?php
class ControllerCatalogSubscribe extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/subscribe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/subscribe');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/subscribe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/subscribe');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_subscribe->addSubscribe($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/subscribe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/subscribe');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_subscribe->editSubscribe($this->request->get['subscribe_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/subscribe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/subscribe');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $subscribe_id) {
				$this->model_catalog_subscribe->deleteSubscribe($subscribe_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'email';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['module_install'] = $this->model_catalog_subscribe->tableExists();

		if ($this->data['module_install']) {
			$this->data['send'] = $this->url->link('catalog/subscribe/send', 'token=' . $this->session->data['token'] . $url);
			$this->data['email'] = $this->url->link('catalog/subscribe/email', 'token=' . $this->session->data['token'] . $url);
			$this->data['authorization'] = $this->url->link('catalog/subscribe/authorizationEmail', 'token=' . $this->session->data['token'] . $url);
			$this->data['insert'] = $this->url->link('catalog/subscribe/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('catalog/subscribe/delete', 'token=' . $this->session->data['token'] . $url);

			$this->data['subscribers'] = array();

			$data = array(
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')
			);

			$subscribe_total = $this->model_catalog_subscribe->getTotalSubscibe();

			$results = $this->model_catalog_subscribe->getSubscribers($data);

			foreach ($results as $result) {
				$action = array();

				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('catalog/subscribe/update', 'token=' . $this->session->data['token'] . '&subscribe_id=' . $result['subscribe_id'] . $url, 'SSL')
				);

				$this->data['subscribers'][] = array(
					'email' => $result['email'],
					'status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'subscribe_id' => $result['subscribe_id'],
					'selected' => isset($this->request->post['selected']) && in_array($result['subscribe_id'], $this->request->post['selected']),
					'action' => $action
				);
			}

			$this->data['text_no_results'] = $this->language->get('text_no_results');

			$this->data['column_email'] = $this->language->get('column_email');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_action'] = $this->language->get('column_action');

			$this->data['button_subscribe'] = $this->language->get('button_subscribe');
			$this->data['button_email'] = $this->language->get('button_email');
			$this->data['button_authorization'] = $this->language->get('button_authorization');
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';
			}

			$url = '';

			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['sort_email'] = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . '&sort=email' . $url);
			$this->data['sort_status'] = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . '&sort=status' . $url);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$pagination = new Pagination();
			$pagination->total = $subscribe_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();

			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
		} else {
			$this->data['text_module_not_exists'] = $this->language->get('text_module_not_exists');
		}

		$this->template = 'catalog/subscribe_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['subscribe_id'])) {
			$this->data['action'] = $this->url->link('catalog/subscribe/insert', 'token=' . $this->session->data['token'] . $url);
		} else {
			$this->data['action'] = $this->url->link('catalog/subscribe/update', 'token=' . $this->session->data['token'] . '&subscribe_id=' . $this->request->get['subscribe_id'] . $url);
		}

		$this->data['cancel'] = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url);

		if (isset($this->request->get['subscribe_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$subscribe_info = $this->model_catalog_subscribe->getSubscribe($this->request->get['subscribe_id']);
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (!empty($subscribe_info)) {
			$this->data['email'] = $subscribe_info['email'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($subscribe_info)) {
			$this->data['status'] = $subscribe_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/subscribe_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function send() {
		$this->language->load('catalog/subscribe');

		$this->load->model('catalog/subscribe');

		$subscribers = $this->model_catalog_subscribe->getSubscribers();

		if ($this->validateSendMail($subscribers)) {
			foreach ($subscribers as $subscriber) {
				if ($subscriber['status']) {
					$subscribe_descriptions = $this->model_catalog_subscribe->getEmailDescription();
					$text_mail = $subscribe_descriptions[(int) $this->config->get('config_language_id')];
					$subject = sprintf($this->language->get('text_subject_mail'), $this->config->get('config_name'));

					$message = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $subject . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($text_mail, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";

					$mail = new Mail($this->registry); 
					$mail->setTo($subscriber['email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($this->config->get('config_mail_trigger_name_from'));
					$mail->setSubject($subject);
					$mail->setHtml($message);
					$mail->send();

					$this->session->data['success'] = $this->language->get('text_send_success');
				}
			}
		}

		$this->getList();
	}

	public function authorizationEmail() {
		$this->load->language('catalog/subscribe');

		$this->document->setTitle($this->language->get('heading_title_authorization'));

		$this->load->model('catalog/subscribe');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateAuthorizEmailForm()) {
			$this->model_catalog_subscribe->addAuthDescription($this->request->post['subscribe_authorization']);

			$this->session->data['success'] = $this->language->get('text_success_emailform');

			$this->redirect($this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title_authorization');

		$this->data['entry_text_mail_authorization'] = $this->language->get('entry_text_mail_authorization');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['error_authorization_description'])) {
			$this->data['error_authorization_description'] = $this->error['error_authorization_description'];
		} else {
			$this->data['error_authorization_description'] = array();
		}

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		if (isset($this->request->post['subscribe_authorization'])) {
			$this->data['subscribe_authorization'] = $this->request->post['subscribe_authorization'];
		} else {
			$this->data['subscribe_authorization'] = $this->model_catalog_subscribe->getAuthDescription();
		}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['action'] = $this->url->link('catalog/subscribe/authorizationEmail', 'token=' . $this->session->data['token']);

		$this->data['cancel'] = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token']);

		$this->template = 'catalog/subscribe_mail_authorization.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function email() {
		$this->load->language('catalog/subscribe');

		$this->document->setTitle($this->language->get('heading_title_mail'));

		$this->load->model('catalog/subscribe');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateEmailForm()) {
			$this->model_catalog_subscribe->addEmailDescription($this->request->post['subscribe_descriptions']);

			$this->session->data['success'] = $this->language->get('text_success_emailform');

			$this->redirect($this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title_mail');

		$this->data['entry_text_mail'] = $this->language->get('entry_text_mail');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['error_email_description'])) {
			$this->data['error_email_description'] = $this->error['error_email_description'];
		} else {
			$this->data['error_email_description'] = array();
		}

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		if (isset($this->request->post['subscribe_descriptions'])) {
			$this->data['subscribe_descriptions'] = $this->request->post['subscribe_descriptions'];
		} else {
			$this->data['subscribe_descriptions'] = $this->model_catalog_subscribe->getEmailDescription();
		}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['action'] = $this->url->link('catalog/subscribe/email', 'token=' . $this->session->data['token']);

		$this->data['cancel'] = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token']);

		$this->template = 'catalog/subscribe_mail.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/subscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (isset($this->request->get['subscribe_id'])) {
			$subscribe_id = $this->request->get['subscribe_id'];
		} else {
			$subscribe_id = 0;
		}

		$check_email = $this->model_catalog_subscribe->checkEmail($this->request->post['email'], $subscribe_id);

		if (!isset($this->error['email']) && $check_email) {
			$this->error['email'] = $this->language->get('error_check_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/subscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateSendMail($subscribers) {
		$this->error['warning'] = $this->language->get('error_send');

		foreach ($subscribers as $subscriber) {
			if ($subscriber['status']) {
				$this->error = array();
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateEmailForm() {
		if (!$this->user->hasPermission('modify', 'catalog/subscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['subscribe_descriptions'] as $language_id => $description) {
			if ((utf8_strlen($description) < 0) || (utf8_strlen($description) > 16000000)) {
				$this->error['error_email_description'][$language_id] = $this->language->get('error_email_description');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateAuthorizEmailForm() {
		if (!$this->user->hasPermission('modify', 'catalog/subscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['subscribe_authorization'] as $language_id => $description) {
			if ((utf8_strlen($description) < 0) || (utf8_strlen($description) > 16000000)) {
				$this->error['error_authorization_description'][$language_id] = $this->language->get('error_authorization_description');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

}

?>

<?php  
class ControllerUserUser extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('user/user');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		$this->load->model('user/user_group');
		
		$this->getList();
	}
	
	public function insert() {
		$this->language->load('user/user');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_user->addUser($this->request->post);
			
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
			
			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();
	}
	
	public function update() {
		$this->language->load('user/user');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->language->load('user/user');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_user->deleteUser($user_id);
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
			
			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getList();
	}
	
	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);
		
		$this->data['insert'] = $this->url->link('user/user/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] = $this->url->link('user/user/delete', 'token=' . $this->session->data['token'] . $url);
		
		$this->data['users'] = array();
		$users = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 200,
			'limit' => 200
		);
		
		$user_total = $this->model_user_user->getTotalUsers();
		
		$results = $this->model_user_user->getUsers($data);						
		
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
			);
			
			$users[] = array(
				'user_id'    		=> $result['user_id'],
				'username'   		=> $result['username'],
				'firstname'   		=> $result['firstname'],
				'lastname'   		=> $result['lastname'],
				'is_av'      		=> $result['is_av'],
				'is_mainmanager'    => $result['is_mainmanager'],
				'is_headsales'      => $result['is_headsales'],
				'unlock_orders'     => $result['unlock_orders'],
				'do_transactions'   => $result['do_transactions'],
				'dev_template'   	=> $result['dev_template'],
				'own_orders' 		=> $result['own_orders'],
				'count_worktime' 	=> $result['count_worktime'],
				'count_content' 	=> $result['count_content'],
				'edit_csi' 			=> $result['edit_csi'],
				'bitrix_id' 		=> $result['bitrix_id'],
				'internal_pbx_num' 	=> $result['internal_pbx_num'],
				'ticket'     		=> $result['ticket'],
				'group'  	 		=> $this->model_user_user_group->getUserGroupName($result['user_group_id']),
				'status'     		=> $result['status'],
				'date_added' 		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   		=> isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'     		=> $action
			);
		}
		
		foreach ($users as &$user){
			if ($user['status']) {
				$user['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled');
				$this->data['users'][$user['group']][] = $user;						
			} else {
				$user['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled');
				$this->data['users']['Уволенные'][] = $user;			
			}
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		
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
		
		$this->data['sort_username'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=username' . $url);
		$this->data['sort_status'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url);
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = 200;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url . '&page={page}');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'user/user_list.tpl';
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
		
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);
		
		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = $this->url->link('user/user/insert', 'token=' . $this->session->data['token'] . $url);
		} else {
			$this->data['action'] = $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url);
		
		if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
		}
		
		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} elseif (!empty($user_info)) {
			$this->data['username'] = $user_info['username'];
		} else {
			$this->data['username'] = '';
		}
		
		if (isset($this->request->post['is_av'])) {
			$this->data['is_av'] = $this->request->post['is_av'];
		} elseif (!empty($user_info)) {
			$this->data['is_av'] = $user_info['is_av'];
		} else {
			$this->data['is_av'] = '';
		}
		
		if (isset($this->request->post['unlock_orders'])) {
			$this->data['unlock_orders'] = $this->request->post['unlock_orders'];
		} elseif (!empty($user_info)) {
			$this->data['unlock_orders'] = $user_info['unlock_orders'];
		} else {
			$this->data['unlock_orders'] = '';
		}
		
		if (isset($this->request->post['do_transactions'])) {
			$this->data['do_transactions'] = $this->request->post['do_transactions'];
		} elseif (!empty($user_info)) {
			$this->data['do_transactions'] = $user_info['do_transactions'];
		} else {
			$this->data['do_transactions'] = '';
		}
		
		if (isset($this->request->post['is_mainmanager'])) {
			$this->data['is_mainmanager'] = $this->request->post['is_mainmanager'];
		} elseif (!empty($user_info)) {
			$this->data['is_mainmanager'] = $user_info['is_mainmanager'];
		} else {
			$this->data['is_mainmanager'] = '';
		}
		
		if (isset($this->request->post['is_headsales'])) {
			$this->data['is_headsales'] = $this->request->post['is_headsales'];
		} elseif (!empty($user_info)) {
			$this->data['is_headsales'] = $user_info['is_headsales'];
		} else {
			$this->data['is_headsales'] = '';
		}
		
		if (isset($this->request->post['own_orders'])) {
			$this->data['own_orders'] = $this->request->post['own_orders'];
		} elseif (!empty($user_info)) {
			$this->data['own_orders'] = $user_info['own_orders'];
		} else {
			$this->data['own_orders'] = '';
		}
		
		if (isset($this->request->post['count_worktime'])) {
			$this->data['count_worktime'] = $this->request->post['count_worktime'];
		} elseif (!empty($user_info)) {
			$this->data['count_worktime'] = $user_info['count_worktime'];
		} else {
			$this->data['count_worktime'] = '';
		}

		if (isset($this->request->post['count_content'])) {
			$this->data['count_content'] = $this->request->post['count_content'];
		} elseif (!empty($user_info)) {
			$this->data['count_content'] = $user_info['count_content'];
		} else {
			$this->data['count_content'] = '';
		}
		
		if (isset($this->request->post['edit_csi'])) {
			$this->data['edit_csi'] = $this->request->post['edit_csi'];
		} elseif (!empty($user_info)) {
			$this->data['edit_csi'] = $user_info['edit_csi'];
		} else {
			$this->data['edit_csi'] = '';
		}
		
		if (isset($this->request->post['bitrix_id'])) {
			$this->data['bitrix_id'] = $this->request->post['bitrix_id'];
		} elseif (!empty($user_info)) {
			$this->data['bitrix_id'] = $user_info['bitrix_id'];
		} else {
			$this->data['bitrix_id'] = '';
		}
		
		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) {
			$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($user_info)) {
			$this->data['firstname'] = $user_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		
		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($user_info)) {
			$this->data['lastname'] = $user_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (!empty($user_info)) {
			$this->data['email'] = $user_info['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['internal_pbx_num'])) {
			$this->data['internal_pbx_num'] = $this->request->post['internal_pbx_num'];
		} elseif (!empty($user_info)) {
			$this->data['internal_pbx_num'] = $user_info['internal_pbx_num'];
		} else {
			$this->data['internal_pbx_num'] = '';
		}
		
		if (isset($this->request->post['internal_auth_pbx_num'])) {
			$this->data['internal_auth_pbx_num'] = $this->request->post['internal_auth_pbx_num'];
		} elseif (!empty($user_info)) {
			$this->data['internal_auth_pbx_num'] = $user_info['internal_auth_pbx_num'];
		} else {
			$this->data['internal_auth_pbx_num'] = '';
		}
		
		if (isset($this->request->post['outbound_pbx_num'])) {
			$this->data['outbound_pbx_num'] = $this->request->post['outbound_pbx_num'];
		} elseif (!empty($user_info)) {
			$this->data['outbound_pbx_num'] = $user_info['outbound_pbx_num'];
		} else {
			$this->data['outbound_pbx_num'] = '';
		}
		
		if (isset($this->request->post['user_group_id'])) {
			$this->data['user_group_id'] = $this->request->post['user_group_id'];
		} elseif (!empty($user_info)) {
			$this->data['user_group_id'] = $user_info['user_group_id'];
		} else {
			$this->data['user_group_id'] = '';
		}
		
		if (isset($this->request->post['ticket'])) {
			$this->data['ticket'] = $this->request->post['ticket'];
		} elseif (!empty($user_info)) {
			$this->data['ticket'] = $user_info['ticket'];
		} else {
			$this->data['ticket'] = 0;
		}

		if (isset($this->request->post['dev_template'])) {
			$this->data['dev_template'] = $this->request->post['dev_template'];
		} elseif (!empty($user_info)) {
			$this->data['dev_template'] = $user_info['dev_template'];
		} else {
			$this->data['dev_template'] = 0;
		}
		
		$this->load->model('user/user_group');
		
		$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($user_info)) {
			$this->data['status'] = $user_info['status'];
		} else {
			$this->data['status'] = 0;
		}
		
		$this->template = 'user/user_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'user/user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
			$this->error['username'] = $this->language->get('error_username');
		}
		
		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);
		
		if (!isset($this->request->get['user_id'])) {
			if ($user_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		
		if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		
		if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}
			
			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'user/user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}
		
		if (!$this->error) {
			return true;
		} else { 
			return false;
		}
	}
}
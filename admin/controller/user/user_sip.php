<?php
	class ControllerUserUsersip extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('user/user');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
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
            'href'      => $this->url->link('user/user_sip', 'token=' . $this->session->data['token'] . $url),
            'separator' => ' :: '
			);
			
			
			$this->data['users'] = array();
			$users = array();
			
			$data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * 200,
            'limit' => 200,
            'internal_pbx_num' => true
			);
			
			//	$this->data['managers'] = $this->model_user_user->getUsersByGroups(array(12, 19), true);
			
			$this->data['groups'] = $this->model_user_user->getUsersTelephonyQueues();
			
			//	var_dump($this->data['groups']);
			
			$user_total = $this->model_user_user->getTotalUsers();			
			$results = $this->model_user_user->getUsers($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
                'text' => 'История звонков',
                'href' => $this->url->link('user/user_sip/history', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);
				
				$can_add = true;
				if (!in_array($this->user->getUserGroup(), array(1,23))){
					$can_add = !in_array($result['user_group_id'], array(1,23));
				}
				
				if ($can_add){
					$users[] = array(
					'user_id'    => $result['user_id'],
					'username'   => $result['username'],
					'firstname'   => $result['firstname'],
					'lastname'   => $result['lastname'],
					'call_count' => $this->model_user_user->getTotalUserCallsAzazaOlolo($result['user_id']),
					'is_av'      => $result['is_av'],
					'group'  	 => $this->model_user_user_group->getUserGroupName($result['user_group_id']),
					'status'     => $result['status'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
					'action'     => $action,
					'internal_pbx_num'     => $result['internal_pbx_num'],
					);
				}
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
			
			$this->data['sort_username'] = $this->url->link('user/user_sip', 'token=' . $this->session->data['token'] . '&sort=username' . $url);
			$this->data['sort_status'] = $this->url->link('user/user_sip', 'token=' . $this->session->data['token'] . '&sort=status' . $url);
			$this->data['sort_date_added'] = $this->url->link('user/user_sip', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url);
			
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
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('user/user_sip', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'user/user_sip_list.tpl';
			$this->children = array(
            'common/header',
            'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function history () {
			$this->load->model('user/user');
			$this->load->model('user/user_group');
			
			$this->document->setTitle('История звонков');
			
			$user_id = $this->request->get['user_id'];
			$user = $this->model_user_user->getUser($user_id);
			
			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
				} else {
				$filter_date_end = null;
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$filter_customer = $this->request->get['filter_customer'];
				} else {
				$filter_customer = null;
			}
			
			if (isset($this->request->get['filter_customer_name'])) {
				$filter_customer_name = $this->request->get['filter_customer_name'];
				} else {
				$filter_customer_name = null;
			}
			
			if (isset($this->request->get['filter_telephone'])) {
				$filter_telephone = $this->request->get['filter_telephone'];
				} else {
				$filter_telephone = null;
			}
			
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'date_end';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			
			
			$url = '';
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_name'])) {
				$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_telephone'])) {
				$url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
			}
			
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
			'text'      => 'История звонков',
			'href'      => $this->url->link('user/user_sip', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
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
			
			$this->data['heading_title'] = 'История звонков: '.$user['firstname'].' '.$user['lastname'].' (внутр. номер: '.$user['internal_pbx_num'].')';
			
			$this->data['user_calls'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit'),
			'filter_date_end' => $filter_date_end,
			'filter_customer' => $filter_customer,
			'filter_customer_name' => $filter_customer_name,
			'filter_telephone' => $filter_telephone
			);
			
			$results = $this->model_user_user->getUserCalls($user_id, $data);
			$user_calls_total = $this->model_user_user->getTotalUserCalls($user_id, $data);
			
			//main
			foreach ($results as $result){
				
				if ($result['customer_id']){
					$customer_link = $this->url->link('sale/customer/update', 'token='.$this->session->data['token'].'&customer_id='.$result['customer_id']);
					} else {
					$customer_link = false;
				}
				
				$url = '';
				if (isset($this->request->get['filter_date_end'])) {
					$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				
				$this->data['user_calls'][] = array(
				'customer_call_id' => $result['customer_call_id'],
				'date_end'         => date('d.m.Y', strtotime($result['date_end'])).'<br />'.date('H:i:s', strtotime($result['date_end'])),
				'inbound'		   => $result['inbound'],
				'length'		   => $result['length'],
				'customer_id'      => $result['customer_id'],
				'customer_id_link'      => $this->url->link('user/user_sip/history', 'user_id='.$user_id.'&token='.$this->session->data['token'].'&filter_customer='.urlencode(html_entity_decode($result['customer_id'])). $url),
				'customer_name'    => $result['customer_name'],					
				'customer_phone'    => $result['customer_phone'],
				'customer_phone_link'    => $this->url->link('user/user_sip/history', 'user_id='.$user_id.'&token='.$this->session->data['token'].'&filter_telephone='.urlencode(html_entity_decode($result['customer_phone'])). $url),
				'customer_link'	   => $customer_link,
				'internal_pbx_num' => $result['internal_pbx_num'],
				'filename'         => str_replace(SIP_REMOTE_PATH, SIP_REPLACE_PATH, $result['filelink'])
				);
			}
			
			
			$url = '';
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_name'])) {
				$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_telephone'])) {
				$url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
			}
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}		
			
			$this->data['sort_date_end'] = $this->url->link('user/user_sip/history', 'user_id='.$user_id.'&token=' . $this->session->data['token'] . '&sort=date_end' . $url);
			
			$url = '';
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_name'])) {
				$url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_telephone'])) {
				$url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}				
			
			$pagination = new Pagination();
			$pagination->total = $user_calls_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('user/user_sip/history', 'user_id='.$user_id.'&token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['token'] = $this->session->data['token'];
			$this->data['user_id'] = $user_id;
			$this->data['filter_date_end'] = $filter_date_end;
			$this->data['filter_customer_name'] = $filter_customer_name;
			$this->data['filter_customer'] = $filter_customer;
			$this->data['filter_telephone'] = $filter_telephone;
			
			$this->template = 'user/user_sip_history.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
	}
<?php 
	class ControllerLocalisationOrderStatus extends Controller { 
		private $error = array();
		
		public function index() {
			$this->language->load('localisation/order_status');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/order_status');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('localisation/order_status');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/order_status');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_order_status->addOrderStatus($this->request->post);
				
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
				
				$this->redirect($this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('localisation/order_status');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/order_status');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_order_status->editOrderStatus($this->request->get['order_status_id'], $this->request->post);
				
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
				
				$this->redirect($this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('localisation/order_status');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/order_status');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $order_status_id) {
					$this->model_localisation_order_status->deleteOrderStatus($order_status_id);
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
				
				$this->redirect($this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'name';
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
			'href'      => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('localisation/order_status/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('localisation/order_status/delete', 'token=' . $this->session->data['token'] . $url);	
			
			$this->data['order_statuses'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$order_status_total = $this->model_localisation_order_status->getTotalOrderStatuses();
			
			$results = $this->model_localisation_order_status->getOrderStatuses($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/order_status/update', 'token=' . $this->session->data['token'] . '&order_status_id=' . $result['order_status_id'] . $url, 'SSL')
				);
				
				$_linked_statuses_ids = $this->model_localisation_order_status->getLinkedOrderStatusIds($result['order_status_id']);
				$_linked_statuses = array();
				foreach ($_linked_statuses_ids as $_losi){
					$_linked_statuses[] = $this->model_localisation_order_status->getOrderStatus($_losi);			
				}
				
				$this->data['order_statuses'][] = array(
				'order_status_id' => $result['order_status_id'],
				'name'            => $result['name'] . (($result['order_status_id'] == $this->config->get('config_order_status_id')) ? $this->language->get('text_default') : null),
				'linked_order_statuses' => $_linked_statuses,
				'selected'        => isset($this->request->post['selected']) && in_array($result['order_status_id'], $this->request->post['selected']),
				'action'          => $action,
				'status_txt_color'            => $result['status_txt_color'],
				'status_bg_color'            => $result['status_bg_color'],
				'front_bg_color'            => $result['front_bg_color'],
				'status_fa_icon'            => $result['status_fa_icon']
				);
			}	
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
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
			
			$this->data['sort_name'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $order_status_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'localisation/order_status_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
				} else {
				$this->data['error_name'] = array();
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
			'href'      => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['order_status_id'])) {
				$this->data['action'] = $this->url->link('localisation/order_status/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('localisation/order_status/update', 'token=' . $this->session->data['token'] . '&order_status_id=' . $this->request->get['order_status_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'] . $url);
			
			$this->load->model('localisation/language');
			
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			$this->load->model('localisation/order_status');
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
			$this->data['linked_order_status_ids'] = $this->model_localisation_order_status->getLinkedOrderStatusIds((int)$this->request->get['order_status_id']);
			
			
			if (isset($this->request->post['order_status'])) {
				$this->data['order_status'] = $this->request->post['order_status'];
				} elseif (isset($this->request->get['order_status_id'])) {
				$this->data['order_status'] = $this->model_localisation_order_status->getOrderStatusDescriptions($this->request->get['order_status_id']);				
				} else {
				$this->data['order_status'] = array();
			}
			
			$this->template = 'localisation/order_status_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());	
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['order_status'] as $language_id => $value) {
				if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
					$this->error['name'][$language_id] = $this->language->get('error_name');
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			
			foreach ($this->request->post['selected'] as $order_status_id) {
				if ($this->config->get('config_order_status_id') == $order_status_id) {
					$this->error['warning'] = $this->language->get('error_default');
				}  
				
				if ($this->config->get('config_download_status_id') == $order_status_id) {
					$this->error['warning'] = $this->language->get('error_download');
				}  
				
				$store_total = $this->model_setting_store->getTotalStoresByOrderStatusId($order_status_id);
				
				if ($store_total) {
					$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
				}
				
				$order_total = $this->model_sale_order->getTotalOrderHistoriesByOrderStatusId($order_status_id);
				
				if ($order_total) {
					$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
				}  
			}
			
			if (!$this->error) { 
				return true;
				} else {
				return false;
			}
		}
	}
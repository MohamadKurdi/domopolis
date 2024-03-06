<?php    
	class ControllerSaleCallback extends Controller { 
		private $error = array();
		
		public function index() {
			$this->load->language('sale/callback');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/callback');
			
			$this->getList();
		}
		
		public function update() {
			$this->load->language('sale/callback');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/callback');
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST')  { 								
				$this->model_sale_callback->editCallback($this->request->get['callback_id'], $this->request->post);
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
				
				$this->redirect($this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}   
		
		public function update_batch() {
			$this->load->language('sale/callback');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/callback');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				
				if (isset($this->request->post['selected'])) {
					foreach ($this->request->post['selected'] as $callback_id) {
						$this->model_sale_callback->editCallbacks($callback_id);
					}
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
				
				$this->redirect($this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}  
		
		public function delete() {
			$this->load->language('sale/callback');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/callback');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $callback_id) {
					$this->model_sale_callback->deleteCallback($callback_id);
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
				
				$this->redirect($this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}  
		
		private function getList() {	
			$this->load->model('user/user');
			$this->load->model('sale/customer');
			$this->load->model('user/user_group');
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'call_id';
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
       		'text'      => $this->language->get($this->language->get('heading_title')),
			'href'      => $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url),
      		'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('sale/callback/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('sale/callback/delete', 'token=' . $this->session->data['token'] . $url);	
			
			$this->data['callbacks'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$callbacks_total = $this->model_sale_callback->getTotalCallbacks();			
			$results = $this->model_sale_callback->getCallbacks($data);
			
			foreach ($results as $result) {				
				$action = $this->url->link('sale/callback/update', 'token=' . $this->session->data['token'] . '&callback_id=' . $result['call_id'] . $url);
				
				if ($result['status_id'] == '0'){
					$status = $this->language->get('status_wait');
					}else{
					$status = $this->language->get('status_done');
				}
				
				if ($result['customer_id']){
					$customer_href = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id']);	
					//	$real_customer = $this->model_sale_customer->getCustomer($result['customer_id']);
					} else {
					$customer_href = false;
					//	$real_customer = false;
				}
				
				if ($result['is_missed'] && !$result['sip_queue']){
					$sip_group_name = $this->model_user_user_group->getUserGroupName(12, 'name');
					$sip_group_id = 12;
				} elseif ($result['sip_queue']){
					$sip_group_name = $this->model_user_user_group->getUserGroupBySipQueue($result['sip_queue'], 'name');
					$sip_group_id = $this->model_user_user_group->getUserGroupBySipQueue($result['sip_queue'], 'user_group_id');
				} else {
					$sip_group_name = $this->model_user_user_group->getUserGroupName(12, 'name');
					$sip_group_id = 12;
				}
				
				$real_product = false;
				if ($result['product_id']){
					$this->load->model('catalog/product');
					$this->load->model('tool/image');
					$real_product = $this->model_catalog_product->getProduct($result['product_id']);
					$real_product['image'] = $this->model_tool_image->resize($real_product['image'], 50, 50);
				}
				
				$real_customer = false;
				if (!$result['name'] && $result['customer_id']){
					$real_customer = $this->model_sale_customer->getCustomer($result['customer_id']);
				}

				if (empty($real_customer['firstname'])){
					$real_customer = [];
					$real_customer['firstname'] = $result['telephone'];
				}
				
				$this->data['callbacks'][] = array(
				'callback_id' 		=> $result['call_id'],
				'is_missed' 		=> $result['is_missed'],
				'is_cheaper' 		=> $result['is_cheaper'],
				'product_id' 		=> $result['product_id'],
				'product'           => $real_product,
				'customer_id' 		=> $result['customer_id'],
				'customer_href'     => $customer_href,
				'real_customer'     => $real_customer,
 				'name'            	=> $result['name']?$result['name']:$real_customer['firstname'],
				'sip_queue'         => $result['sip_queue'],
				'sip_group'	        => $sip_group_name,
				'sip_group_id'	    => $sip_group_id,
				'is_my_group'	    => ($sip_group_id == $this->user->getUserGroup()),
				'telephone'      	=> $result['telephone'],
				'date_added'     	=> date('d.m.Y', strtotime($result['date_added'])).'<br />'.date('H:i:s', strtotime($result['date_added'])),
				'date_modified'  	=> date('d.m.Y', strtotime($result['date_modified'])).'<br />'.date('H:i:s', strtotime($result['date_modified'])),
				'comment'        	=> $result['comment'],
				'comment_buyer'    	=> $result['comment_buyer'],
				'email_buyer'   	=> $result['email_buyer'],
				'username'			=> $this->model_user_user->getRealUserNameById($result['manager_id']),
				'action'            => $action,
				'status'            => $status,
				'selected'          => isset($this->request->post['selected']) && in_array($result['call_id'], $this->request->post['selected']),
				);
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_telephone'] = $this->language->get('column_telephone');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			
    		$this->data['text_comment'] = $this->language->get('text_comment');
    		$this->data['text_comment_buyer'] = $this->language->get('text_comment_buyer');
    		$this->data['text_email_buyer'] = $this->language->get('text_email_buyer');
    		$this->data['text_status'] = $this->language->get('text_status');
    		$this->data['text_added'] = $this->language->get('text_added');
    		$this->data['text_modified'] = $this->language->get('text_modified');
    		$this->data['text_action'] = $this->language->get('text_action');
    		$this->data['text_edit'] = $this->language->get('text_edit');
    		$this->data['text_manager'] = $this->language->get('text_manager');
			
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');
    		$this->data['status_wait'] = $this->language->get('status_wait');
    		$this->data['status_done'] = $this->language->get('status_done');
			
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
			$this->data['sort_call_id'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . '&sort=call_id' . $url);		
			$this->data['sort_name'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
			$this->data['sort_telephone'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . '&sort=telephone' . $url);
			$this->data['sort_username'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . '&sort=username' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $callbacks_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->data['update'] = $this->url->link('sale/callback/update_batch', 'token=' . $this->session->data['token'] . $url);	
			
			$this->template = 'sale/callback_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer',
			);
			
			$this->response->setOutput($this->render());
		}
		
		
		
		private function validateForm() {
			if (!$this->user->hasPermission('modify', 'sale/callback')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 64)) {
				$this->error['name'] = $this->language->get('error_name');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}    
		
		private function validateDelete() {
			if (!$this->user->hasPermission('modify', 'sale/callback')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}	
			
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}  
		}
		
		private function getForm() {
			
			$this->load->model('user/user');
			$this->load->model('sale/customer');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
    		$this->data['button_save'] = $this->language->get('button_save');
    		$this->data['button_cancel'] = $this->language->get('button_cancel');
			
    		$this->data['text_id'] = $this->language->get('text_id');
    		$this->data['text_name'] = $this->language->get('text_name');
    		$this->data['text_telephone'] = $this->language->get('text_telephone');
    		$this->data['text_comment'] = $this->language->get('text_comment');
    		$this->data['text_comment_buyer'] = $this->language->get('text_comment_buyer');
    		$this->data['text_email_buyer'] = $this->language->get('text_email_buyer');
    		$this->data['text_status'] = $this->language->get('text_status');
    		$this->data['text_added'] = $this->language->get('text_added');
    		$this->data['text_modified'] = $this->language->get('text_modified');
			$this->data['text_manager_form'] = $this->language->get('text_manager_form');
    		$this->data['status_wait'] = $this->language->get('status_wait');
    		$this->data['status_done'] = $this->language->get('status_done');
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
			);
			$url = '';
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url),
      		'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('sale/callback/update', 'token=' . $this->session->data['token'] . '&callback_id=' . $this->request->get['callback_id'] . $url);
			
			$this->data['cancel'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token'] . $url);
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['callback_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$callback_info = $this->model_sale_callback->getСallback($this->request->get['callback_id']);
			}
			
			if (isset($this->request->post['comment'])) {
				$this->data['comment'] = $this->request->post['comment'];
				} elseif (isset($callback_info)) {
				$this->data['comment'] = $callback_info['comment'];
			}
			
			if (isset($this->request->post['email_buyer'])) {
				$this->data['email_buyer'] = $this->request->post['email_buyer'];
				} elseif (isset($callback_info)) {
				$this->data['email_buyer'] = $callback_info['email_buyer'];
			} 
			
			if (isset($this->request->post['status_id'])) {
				$this->data['status_id'] = $this->request->post['status_id'];
				} elseif (isset($callback_info)) {
				$this->data['status_id'] = $callback_info['status_id'];
			} 
			
			if ($callback_info['customer_id']){
				$this->data['customer_href'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $callback_info['customer_id']);	
				$this->data['real_customer'] = $this->model_sale_customer->getCustomer($callback_info['customer_id']);
				} else {
				$this->data['customer_href'] = false;
				$this->data['real_customer'] = false;
			}
			
			$this->data['manager_id'] = $callback_info['manager_id'];
			
			$this->data['manager'] = $this->model_user_user->getRealUserNameById($callback_info['manager_id']);
			
			$this->data['customer_id'] = $callback_info['customer_id'];
			$this->data['comment_buyer'] = $callback_info['comment_buyer'];
			$this->data['callback_id'] = $callback_info['call_id'];
			$this->data['name'] = $callback_info['name'];
			$this->data['telephone'] = $callback_info['telephone'];
			$this->data['date_added'] = $callback_info['date_added'];
			$this->data['date_modified'] = $callback_info['date_modified'];
			
			$this->template = 'sale/callback_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer',
			);
			
			$this->response->setOutput($this->render());
			
			
		}
		
	}
?>

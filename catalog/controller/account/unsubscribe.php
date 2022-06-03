<?php 
	class ControllerAccountUnsubscribe extends Controller { 
		
		private function unsubscribe($email){
			$this->load->model('account/customer');			
			$customers = $this->model_account_customer->getAllCustomersByEmail($this->data['email']);
			
			foreach ($customers as $customer){
				if ($customer_id = $customer['customer_id']){
					$this->db->query("UPDATE customer SET newsletter = 0, newsletter_news = 0, newsletter_personal = 0 WHERE customer_id = '" . (int)$customer_id . "'");
					$this->db->query("UPDATE customer_simple_fields SET newsletter_news = 0, newsletter_personal = 0 WHERE customer_id = '" . (int)$customer_id . "'");
					
					$this->customer->addToEMAQueue($customer_id);
				}
			}
			
		}
		
		
		public function index() {
			
			$this->language->load('account/account');
			
			foreach ($this->language->loadRetranslate('account/unsubscribe') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->document->setTitle($this->data['heading_title']);
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['email'] = '';
			if ($this->request->server['REQUEST_METHOD'] != 'POST') {
				
				if ($this->customer->isLogged()){
				$this->data['email'] = $this->customer->getEmail();
			}
			
			if (!empty($this->request->get['unsubscribe']) && filter_var($this->request->get['unsubscribe'], FILTER_VALIDATE_EMAIL)){
				$this->data['email'] = $this->request->get['unsubscribe'];
			}
			}
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {				
				if (!empty($this->request->post['email'])){					
					
				}
				
				if (empty($this->request->post['email'])){
					$this->data['error_email'] = true;
				}
				
				if (empty($this->request->post['unsubscribe_check'])){
					$this->data['error_unsubscribe_check'] = true;
				}
				
				if (!empty($this->request->post['email'])){
					$this->data['email'] = $this->request->post['email'];
					
					$this->load->model('account/customer');			
					$customer_id = $this->model_account_customer->getCustomerIDByEmail($this->data['email']);
					
					if (!$customer_id){
						$this->data['error_email_not_exists'] = true;
						} else {
						
						if (!empty($this->request->post['unsubscribe_check'])){
							$this->unsubscribe($this->data['email']);
							$this->data['success'] = true;
						}
						
					}
					
				}
			}
			
			
			
			$this->data['action'] = $this->url->link('account/unsubscribe', '', 'SSL');
			
			$this->data['text_unsubscribe_text_2'] = sprintf($this->data['text_unsubscribe_text_2'], $this->url->link('account/account/edit', '', 'SSL'));
			
			if (!empty($this->data['email'])){
				$this->data['text_error_email_not_exists'] = sprintf($this->data['text_error_email_not_exists'], $this->data['email']);
			}
			
			if (!empty($this->data['success'])){
				$this->data['text_success'] = sprintf($this->data['text_success'], $this->data['email']);
			}
			
			
			$this->template = $this->config->get('config_template') . '/template/account/unsubscribe.tpl';
			
			$this->children = array(
			//	'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'		
			);
			
			$this->response->setOutput($this->render());
		}
	}							
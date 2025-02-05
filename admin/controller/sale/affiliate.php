<?php    
	class ControllerSaleAffiliate extends Controller { 
		private $error = [];
		
		public function index() {
			$this->language->load('sale/affiliate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/affiliate');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('sale/affiliate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/affiliate');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_affiliate->addAffiliate($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
				$this->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('sale/affiliate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/affiliate');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_affiliate->editAffiliate($this->request->get['affiliate_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
				$this->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('sale/affiliate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/affiliate');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $affiliate_id) {
					$this->model_sale_affiliate->deleteAffiliate($affiliate_id);
				}
			
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_request_payment'])) {
					$url .= '&filter_request_payment=' . $this->request->get['filter_request_payment'];
					
					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
					}
					
					if (isset($this->request->get['filter_email'])) {
						$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
					}
					
					if (isset($this->request->get['filter_status'])) {
						$url .= '&filter_status=' . $this->request->get['filter_status'];
					}
					
					if (isset($this->request->get['filter_approved'])) {
						$url .= '&filter_approved=' . $this->request->get['filter_approved'];
					}		
					
					if (isset($this->request->get['filter_date_added'])) {
						$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
					
					$this->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
			
			}	
				$this->getList();
			}
			
			public function approve() {
				$this->language->load('sale/affiliate');
				
				$this->document->setTitle($this->language->get('heading_title'));
				
				$this->load->model('sale/affiliate');	
				
				if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
					$this->error['warning'] = $this->language->get('error_permission');
					} elseif (isset($this->request->post['selected'])) {
					$approved = 0;
					
					foreach ($this->request->post['selected'] as $affiliate_id) {
						$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
						
						if ($affiliate_info && !$affiliate_info['approved']) {
							$this->model_sale_affiliate->approve($affiliate_id);
							
							$approved++;
						}
					}
					
					$this->session->data['success'] = sprintf($this->language->get('text_approved'), $approved);
					
					$url = '';
					
					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
					}
					
					if (isset($this->request->get['filter_email'])) {
						$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
					}
					
					if (isset($this->request->get['filter_status'])) {
						$url .= '&filter_status=' . $this->request->get['filter_status'];
					}
					
					if (isset($this->request->get['filter_approved'])) {
						$url .= '&filter_approved=' . $this->request->get['filter_approved'];
					}	
					
					if (isset($this->request->get['filter_date_added'])) {
						$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
					
					$this->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url, 'SSL'));					
				}
				
				$this->getList();
			} 
			
			protected function getList() {
				
				if (isset($this->request->get['filter_request_payment'])) {
					$filter_request_payment = $this->request->get['filter_request_payment'];
					} else {
					$filter_request_payment = null;
				}
				if (isset($this->request->get['filter_name'])) {
					$filter_name = $this->request->get['filter_name'];
					} else {
					$filter_name = null;
				}
				
				if (isset($this->request->get['filter_email'])) {
					$filter_email = $this->request->get['filter_email'];
					} else {
					$filter_email = null;
				}
				
				if (isset($this->request->get['filter_status'])) {
					$filter_status = $this->request->get['filter_status'];
					} else {
					$filter_status = null;
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$filter_approved = $this->request->get['filter_approved'];
					} else {
					$filter_approved = null;
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$filter_date_added = $this->request->get['filter_date_added'];
					} else {
					$filter_date_added = null;
				}	
				
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
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}	
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
				$this->data['breadcrumbs'] = [];
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),       		
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url),
				'separator' => ' :: '
				);
				
				$this->data['approve'] = $this->url->link('sale/affiliate/approve', 'token=' . $this->session->data['token'] . $url);
				$this->data['insert'] = $this->url->link('sale/affiliate/insert', 'token=' . $this->session->data['token'] . $url);
				$this->data['delete'] = $this->url->link('sale/affiliate/delete', 'token=' . $this->session->data['token'] . $url);
				
				$this->data['affiliates'] = [];
				
				$data = array(
				'filter_name'       => $filter_name, 
				'filter_email'      => $filter_email, 
				'filter_status'     => $filter_status, 
				'filter_request_payment' => $filter_request_payment,
				'filter_approved'   => $filter_approved, 
				'filter_date_added' => $filter_date_added,
				'sort'              => $sort,
				'order'             => $order,
				'start'             => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'             => $this->config->get('config_admin_limit')
				);
				
				$affiliate_total = $this->model_sale_affiliate->getTotalAffiliates($data);
				
				$results = $this->model_sale_affiliate->getAffiliates($data);
				
				foreach ($results as $result) {
					$action = [];
					
					$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('sale/affiliate/update', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $result['affiliate_id'] . $url, 'SSL')
					);
					
					$this->data['affiliates'][] = array(
					'affiliate_id' 			=> $result['affiliate_id'],
					'request_payment' 		=> $this->currency->format($result['request_payment'], $this->config->get('config_currency')),
					'request_payment_int' 	=> $result['request_payment'],
					'name'         => $result['name'],
					'code'         => $result['code'],
					'email'        => $result['email'],
					'balance'      => $this->currency->format($result['balance'], $this->config->get('config_currency')),
					'status'       => $result['status'],
					'approved'     => $result['approved'],
					'date_added'   => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'selected'     => isset($this->request->post['selected']) && in_array($result['affiliate_id'], $this->request->post['selected']),
					'action'       => $action
					);
				}	
				
				$this->data['heading_title'] = $this->language->get('heading_title');
				
				$this->data['text_enabled'] = $this->language->get('text_enabled');
				$this->data['text_disabled'] = $this->language->get('text_disabled');
				$this->data['text_yes'] = $this->language->get('text_yes');
				$this->data['text_no'] = $this->language->get('text_no');		
				$this->data['text_no_results'] = $this->language->get('text_no_results');
				
				$this->data['column_name'] = $this->language->get('column_name');
				$this->data['column_request_payment'] = $this->language->get('column_request_payment');
				$this->data['filter_request_payment'] = $filter_request_payment;
				$this->data['column_email'] = $this->language->get('column_email');
				$this->data['column_balance'] = $this->language->get('column_balance');
				$this->data['column_status'] = $this->language->get('column_status');
				$this->data['column_approved'] = $this->language->get('column_approved');
				$this->data['column_date_added'] = $this->language->get('column_date_added');
				$this->data['column_action'] = $this->language->get('column_action');		
				
				$this->data['button_approve'] = $this->language->get('button_approve');
				$this->data['button_insert'] = $this->language->get('button_insert');
				$this->data['button_delete'] = $this->language->get('button_delete');
				$this->data['button_filter'] = $this->language->get('button_filter');
				
				$this->data['token'] = $this->session->data['token'];
				
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
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}	
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if ($order == 'ASC') {
					$url .= '&order=DESC';
					} else {
					$url .= '&order=ASC';
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->data['sort_name'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
				$this->data['sort_email'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&sort=a.email' . $url);
				$this->data['sort_status'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&sort=a.status' . $url);
				$this->data['sort_approved'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&sort=a.approved' . $url);
				$this->data['sort_date_added'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . '&sort=a.date_added' . $url);
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $affiliate_total;
				$pagination->page = $page;
				$pagination->limit = $this->config->get('config_admin_limit');
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url . '&page={page}');
				
				$this->data['pagination'] = $pagination->render();
				
				$this->data['filter_name'] = $filter_name;
				$this->data['filter_email'] = $filter_email;
				$this->data['filter_status'] = $filter_status;
				$this->data['filter_approved'] = $filter_approved;
				$this->data['filter_date_added'] = $filter_date_added;
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				
				$this->template = 'sale/affiliate_list.tpl';
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
				$this->data['text_select'] = $this->language->get('text_select');
				$this->data['text_none'] = $this->language->get('text_none');
				$this->data['text_wait'] = $this->language->get('text_wait');
				$this->data['text_cheque'] = $this->language->get('text_cheque');
				$this->data['text_paypal'] = $this->language->get('text_paypal');
				$this->data['text_bank'] = $this->language->get('text_bank');
				$this->data['text_bonus'] = $this->language->get('text_bonus');
				$this->data['text_qiwi'] = $this->language->get('text_qiwi');
				$this->data['text_card'] = $this->language->get('text_card');
				$this->data['text_yandex'] = $this->language->get('text_yandex');
				$this->data['text_webmoney'] = $this->language->get('text_webmoney');
				$this->data['text_webmoneyWMZ'] = $this->language->get('text_webmoneyWMZ');
				$this->data['text_webmoneyWMU'] = $this->language->get('text_webmoneyWMU');
				$this->data['text_webmoneyWME'] = $this->language->get('text_webmoneyWME');
				$this->data['text_webmoneyWMY'] = $this->language->get('text_webmoneyWMY');
				$this->data['text_webmoneyWMB'] = $this->language->get('text_webmoneyWMB');
				$this->data['text_webmoneyWMG'] = $this->language->get('text_webmoneyWMG');
				$this->data['text_AlertPay'] = $this->language->get('text_AlertPay');
				$this->data['text_Moneybookers'] = $this->language->get('text_Moneybookers');
				$this->data['text_LIQPAY'] = $this->language->get('text_LIQPAY');
				$this->data['text_SagePay'] = $this->language->get('text_SagePay');
				$this->data['text_twoCheckout'] = $this->language->get('text_twoCheckout');
				$this->data['text_GoogleWallet'] = $this->language->get('text_GoogleWallet');
				$this->data['entry_payment_comment'] = $this->language->get('entry_payment_comment');
				$this->data['entry_qiwi'] = $this->language->get('entry_qiwi');
				$this->data['entry_card'] = $this->language->get('entry_card');
				$this->data['entry_yandex'] = $this->language->get('entry_yandex');
				$this->data['entry_webmoney'] = $this->language->get('entry_webmoney');
				$this->data['entry_webmoneyWMZ'] = $this->language->get('entry_webmoneyWMZ');
				$this->data['entry_webmoneyWMU'] = $this->language->get('entry_webmoneyWMU');
				$this->data['entry_webmoneyWME'] = $this->language->get('entry_webmoneyWME');
				$this->data['entry_webmoneyWMY'] = $this->language->get('entry_webmoneyWMY');
				$this->data['entry_webmoneyWMB'] = $this->language->get('entry_webmoneyWMB');
				$this->data['entry_webmoneyWMG'] = $this->language->get('entry_webmoneyWMG');
				$this->data['entry_AlertPay'] = $this->language->get('entry_AlertPay');
				$this->data['entry_Moneybookers'] = $this->language->get('entry_Moneybookers');
				$this->data['entry_LIQPAY'] = $this->language->get('entry_LIQPAY');
				$this->data['entry_SagePay'] = $this->language->get('entry_SagePay');
				$this->data['entry_twoCheckout'] = $this->language->get('entry_twoCheckout');
				$this->data['entry_GoogleWallet'] = $this->language->get('entry_GoogleWallet');
				$this->data['entry_firstname'] = $this->language->get('entry_firstname');
				$this->data['entry_lastname'] = $this->language->get('entry_lastname');
				$this->data['entry_email'] = $this->language->get('entry_email');
				$this->data['entry_telephone'] = $this->language->get('entry_telephone');
				$this->data['entry_fax'] = $this->language->get('entry_fax');
				$this->data['entry_company'] = $this->language->get('entry_company');
				$this->data['entry_address_1'] = $this->language->get('entry_address_1');
				$this->data['entry_address_2'] = $this->language->get('entry_address_2');
				$this->data['entry_city'] = $this->language->get('entry_city');
				$this->data['entry_postcode'] = $this->language->get('entry_postcode');
				$this->data['entry_country'] = $this->language->get('entry_country');
				$this->data['entry_zone'] = $this->language->get('entry_zone');
				$this->data['entry_code'] = $this->language->get('entry_code');
				$this->data['entry_commission'] = $this->language->get('entry_commission');
				$this->data['entry_tax'] = $this->language->get('entry_tax');
				$this->data['entry_payment'] = $this->language->get('entry_payment');
				$this->data['entry_cheque'] = $this->language->get('entry_cheque');
				$this->data['entry_paypal'] = $this->language->get('entry_paypal');
				$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
				$this->data['entry_bank_branch_number'] = $this->language->get('entry_bank_branch_number');
				$this->data['entry_bank_swift_code'] = $this->language->get('entry_bank_swift_code');
				$this->data['entry_bank_account_name'] = $this->language->get('entry_bank_account_name');
				$this->data['entry_bank_account_number'] = $this->language->get('entry_bank_account_number');
				$this->data['entry_password'] = $this->language->get('entry_password');
				$this->data['entry_confirm'] = $this->language->get('entry_confirm');
				$this->data['entry_status'] = $this->language->get('entry_status');
				$this->data['entry_amount'] = $this->language->get('entry_amount');
				$this->data['entry_description'] = $this->language->get('entry_description');
				
				$this->data['button_save'] = $this->language->get('button_save');
				$this->data['button_cancel'] = $this->language->get('button_cancel');
				$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');
				$this->data['button_remove'] = $this->language->get('button_remove');
				
				$this->data['tab_general'] = $this->language->get('tab_general');
				$this->data['tab_payment'] = $this->language->get('tab_payment');
				$this->data['tab_transaction'] = $this->language->get('tab_transaction');
				
				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
					} else {
					$this->data['error_warning'] = '';
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
				
				if (isset($this->error['email'])) {
					$this->data['error_email'] = $this->error['email'];
					} else {
					$this->data['error_email'] = '';
				}
				
				if (isset($this->error['telephone'])) {
					$this->data['error_telephone'] = $this->error['telephone'];
					} else {
					$this->data['error_telephone'] = '';
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
				
				if (isset($this->error['address_1'])) {
					$this->data['error_address_1'] = $this->error['address_1'];
					} else {
					$this->data['error_address_1'] = '';
				}
				
				if (isset($this->error['city'])) {
					$this->data['error_city'] = $this->error['city'];
					} else {
					$this->data['error_city'] = '';
				}
				
				if (isset($this->error['postcode'])) {
					$this->data['error_postcode'] = $this->error['postcode'];
					} else {
					$this->data['error_postcode'] = '';
				}
				
				if (isset($this->error['country'])) {
					$this->data['error_country'] = $this->error['country'];
					} else {
					$this->data['error_country'] = '';
				}
				
				if (isset($this->error['zone'])) {
					$this->data['error_zone'] = $this->error['zone'];
					} else {
					$this->data['error_zone'] = '';
				}
				
				if (isset($this->error['code'])) {
					$this->data['error_code'] = $this->error['code'];
					} else {
					$this->data['error_code'] = '';
				}
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_email'])) {
					$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				
				if (isset($this->request->get['filter_approved'])) {
					$url .= '&filter_approved=' . $this->request->get['filter_approved'];
				}	
				
				if (isset($this->request->get['filter_date_added'])) {
					$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
				$this->data['breadcrumbs'] = [];
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url),
				'separator' => ' :: '
				);
				
				if (!isset($this->request->get['affiliate_id'])) {
					$this->data['action'] = $this->url->link('sale/affiliate/insert', 'token=' . $this->session->data['token'] . $url);
					} else {
					$this->data['action'] = $this->url->link('sale/affiliate/update', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $this->request->get['affiliate_id'] . $url);
				}
				
				$this->data['cancel'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'] . $url);
				
				if (isset($this->request->get['affiliate_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
					$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->get['affiliate_id']);
				}
				
				$this->data['token'] = $this->session->data['token'];
				
				if (isset($this->request->post['request_payment'])) {
					$this->data['request_payment'] = $this->request->post['request_payment'];
					} elseif (!empty($affiliate_info)) {
					$this->data['request_payment'] = $affiliate_info['request_payment'];
					} else {
					$this->data['request_payment'] = '';
				}
				$this->data['config_bonus_visible'] = (bool)$this->config->get('config_bonus_visible');
				$this->data['cheque_visible'] = (bool)$this->config->get('cheque');
				$this->data['paypal_visible'] = (bool)$this->config->get('paypal');
				$this->data['bank_visible'] = (bool)$this->config->get('bank');
				$this->data['qiwi_visible'] = (bool)$this->config->get('qiwi');
				$this->data['card_visible'] = (bool)$this->config->get('card');
				$this->data['yandex_visible'] = (bool)$this->config->get('yandex');
				$this->data['webmoney_visible'] = (bool)$this->config->get('webmoney');
				$this->data['webmoney_visibleWMZ'] = (bool)$this->config->get('webmoneyWMZ');
				$this->data['webmoney_visibleWMU'] = (bool)$this->config->get('webmoneyWMU');
				$this->data['webmoney_visibleWME'] = (bool)$this->config->get('webmoneyWME');
				$this->data['webmoney_visibleWMY'] = (bool)$this->config->get('webmoneyWMY');
				$this->data['webmoney_visibleWMB'] = (bool)$this->config->get('webmoneyWMB');
				$this->data['webmoney_visibleWMG'] = (bool)$this->config->get('webmoneyWMG');
				$this->data['AlertPay_visible'] = (bool)$this->config->get('AlertPay');
				$this->data['Moneybookers_visible'] = (bool)$this->config->get('Moneybookers');
				$this->data['LIQPAY_visible'] = (bool)$this->config->get('LIQPAY');
				$this->data['SagePay_visible'] = (bool)$this->config->get('SagePay');
				$this->data['twoCheckout_visible'] = (bool)$this->config->get('twoCheckout');
				$this->data['GoogleWallet_visible'] = (bool)$this->config->get('GoogleWallet');
				
				if (isset($this->request->post['qiwi'])) {
					$this->data['qiwi'] = $this->request->post['qiwi'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['qiwi'] = $affiliate_info['qiwi'];
					} else {
					$this->data['qiwi'] = '';
				}
				if (isset($this->request->post['card'])) {
					$this->data['card'] = $this->request->post['card'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['card'] = $affiliate_info['card'];
					} else {
					$this->data['card'] = '';
				}  
				if (isset($this->request->post['yandex'])) {
					$this->data['yandex'] = $this->request->post['yandex'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['yandex'] = $affiliate_info['yandex'];
					} else {
					$this->data['yandex'] = '';
				}	    
				if (isset($this->request->post['webmoney'])) {
					$this->data['webmoney'] = $this->request->post['webmoney'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoney'] = $affiliate_info['webmoney'];
					} else {
					$this->data['webmoney'] = '';
				}
				if (isset($this->request->post['webmoneyWMZ'])) {
					$this->data['webmoneyWMZ'] = $this->request->post['webmoneyWMZ'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoneyWMZ'] = $affiliate_info['webmoneyWMZ'];
					} else {
					$this->data['webmoneyWMZ'] = '';
				}
				if (isset($this->request->post['webmoneyWMU'])) {
					$this->data['webmoneyWMU'] = $this->request->post['webmoneyWMU'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoneyWMU'] = $affiliate_info['webmoneyWMU'];
					} else {
					$this->data['webmoneyWMU'] = '';
				}
				if (isset($this->request->post['webmoneyWME'])) {
					$this->data['webmoneyWME'] = $this->request->post['webmoneyWME'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoneyWME'] = $affiliate_info['webmoneyWME'];
					} else {
					$this->data['webmoneyWME'] = '';
				}
				if (isset($this->request->post['webmoneyWMY'])) {
					$this->data['webmoneyWMY'] = $this->request->post['webmoneyWMY'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoneyWMY'] = $affiliate_info['webmoneyWMY'];
					} else {
					$this->data['webmoneyWMY'] = '';
				}
				if (isset($this->request->post['webmoneyWMB'])) {
					$this->data['webmoneyWMB'] = $this->request->post['webmoneyWMB'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoneyWMB'] = $affiliate_info['webmoneyWMB'];
					} else {
					$this->data['webmoneyWMB'] = '';
				}
				if (isset($this->request->post['webmoneyWMG'])) {
					$this->data['webmoneyWMG'] = $this->request->post['webmoneyWMG'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['webmoneyWMG'] = $affiliate_info['webmoneyWMG'];
					} else {
					$this->data['webmoneyWMG'] = '';
				}
				if (isset($this->request->post['AlertPay'])) {
					$this->data['AlertPay'] = $this->request->post['AlertPay'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['AlertPay'] = $affiliate_info['AlertPay'];
					} else {
					$this->data['AlertPay'] = '';
				}
				
				if (isset($this->request->post['Moneybookers'])) {
					$this->data['Moneybookers'] = $this->request->post['Moneybookers'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['Moneybookers'] = $affiliate_info['Moneybookers'];
					} else {
					$this->data['Moneybookers'] = '';
				}
				
				if (isset($this->request->post['LIQPAY'])) {
					$this->data['LIQPAY'] = $this->request->post['LIQPAY'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['LIQPAY'] = $affiliate_info['LIQPAY'];
					} else {
					$this->data['LIQPAY'] = '';
				}
				
				if (isset($this->request->post['SagePay'])) {
					$this->data['SagePay'] = $this->request->post['SagePay'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['SagePay'] = $affiliate_info['SagePay'];
					} else {
					$this->data['SagePay'] = '';
				}
				
				if (isset($this->request->post['twoCheckout'])) {
					$this->data['twoCheckout'] = $this->request->post['twoCheckout'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['twoCheckout'] = $affiliate_info['twoCheckout'];
					} else {
					$this->data['twoCheckout'] = '';
				}
				
				if (isset($this->request->post['GoogleWallet'])) {
					$this->data['GoogleWallet'] = $this->request->post['GoogleWallet'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['GoogleWallet'] = $affiliate_info['GoogleWallet'];
					} else {
					$this->data['GoogleWallet'] = '';
				}
				
				if (isset($this->request->get['affiliate_id'])) {
					$this->data['affiliate_id'] = $this->request->get['affiliate_id'];
					} else {
					$this->data['affiliate_id'] = 0;
				}
				
				if (isset($this->request->post['firstname'])) {
					$this->data['firstname'] = $this->request->post['firstname'];
					} elseif (!empty($affiliate_info)) { 
					$this->data['firstname'] = $affiliate_info['firstname'];
					} else {
					$this->data['firstname'] = '';
				}
				
				if (isset($this->request->post['lastname'])) {
					$this->data['lastname'] = $this->request->post['lastname'];
					} elseif (!empty($affiliate_info)) {
					$this->data['lastname'] = $affiliate_info['lastname'];
					} else {
					$this->data['lastname'] = '';
				}
				
				if (isset($this->request->post['email'])) {
					$this->data['email'] = $this->request->post['email'];
					} elseif (!empty($affiliate_info)) {
					$this->data['email'] = $affiliate_info['email'];
					} else {
					$this->data['email'] = '';
				}
				
				if (isset($this->request->post['telephone'])) {
					$this->data['telephone'] = $this->request->post['telephone'];
					} elseif (!empty($affiliate_info)) {
					$this->data['telephone'] = $affiliate_info['telephone'];
					} else {
					$this->data['telephone'] = '';
				}
				
				if (isset($this->request->post['fax'])) {
					$this->data['fax'] = $this->request->post['fax'];
					} elseif (!empty($affiliate_info)) {
					$this->data['fax'] = $affiliate_info['fax'];
					} else {
					$this->data['fax'] = '';
				}
				
				if (isset($this->request->post['company'])) {
					$this->data['company'] = $this->request->post['company'];
					} elseif (!empty($affiliate_info)) {
					$this->data['company'] = $affiliate_info['company'];
					} else {
					$this->data['company'] = '';
				}
				
				if (isset($this->request->post['address_1'])) {
					$this->data['address_1'] = $this->request->post['address_1'];
					} elseif (!empty($affiliate_info)) {
					$this->data['address_1'] = $affiliate_info['address_1'];
					} else {
					$this->data['address_1'] = '';
				}
				
				if (isset($this->request->post['address_2'])) {
					$this->data['address_2'] = $this->request->post['address_2'];
					} elseif (!empty($affiliate_info)) {
					$this->data['address_2'] = $affiliate_info['address_2'];
					} else {
					$this->data['address_2'] = '';
				}
				
				if (isset($this->request->post['city'])) {
					$this->data['city'] = $this->request->post['city'];
					} elseif (!empty($affiliate_info)) {
					$this->data['city'] = $affiliate_info['city'];
					} else {
					$this->data['city'] = '';
				}
				
				if (isset($this->request->post['postcode'])) {
					$this->data['postcode'] = $this->request->post['postcode'];
					} elseif (!empty($affiliate_info)) {
					$this->data['postcode'] = $affiliate_info['postcode'];
					} else {
					$this->data['postcode'] = '';
				}
				
				if (isset($this->request->post['country_id'])) {
					$this->data['country_id'] = $this->request->post['country_id'];
					} elseif (!empty($affiliate_info)) {
					$this->data['country_id'] = $affiliate_info['country_id'];
					} else {
					$this->data['country_id'] = '';
				}
				
				$this->load->model('localisation/country');
				
				$this->data['countries'] = $this->model_localisation_country->getCountries();
				
				if (isset($this->request->post['zone_id'])) {
					$this->data['zone_id'] = $this->request->post['zone_id'];
					} elseif (!empty($affiliate_info)) {
					$this->data['zone_id'] = $affiliate_info['zone_id'];
					} else {
					$this->data['zone_id'] = '';
				}
				
				if (isset($this->request->post['code'])) {
					$this->data['code'] = $this->request->post['code'];
					} elseif (!empty($affiliate_info)) {
					$this->data['code'] = $affiliate_info['code'];
					} else {
					$this->data['code'] = uniqid();
				}
				
				if (isset($this->request->post['commission'])) {
					$this->data['commission'] = $this->request->post['commission'];
					} elseif (!empty($affiliate_info)) {
					$this->data['commission'] = $affiliate_info['commission'];
					} else {
					$this->data['commission'] = $this->config->get('config_commission');
				}
				
				if (isset($this->request->post['tax'])) {
					$this->data['tax'] = $this->request->post['tax'];
					} elseif (!empty($affiliate_info)) {
					$this->data['tax'] = $affiliate_info['tax'];
					} else {
					$this->data['tax'] = '';
				}
				
				if (isset($this->request->post['payment'])) {
					$this->data['payment'] = $this->request->post['payment'];
					} elseif (!empty($affiliate_info)) {
					if ((bool)$this->config->get($affiliate_info['payment'])) {
						$this->data['payment'] = $affiliate_info['payment'];
						} else {
						if($this->data['config_bonus_visible']){
							$this->data['payment'] = 'bonus';
						}
						else if($this->data['cheque_visible']){
							$this->data['payment'] = 'cheque';
						}
						else if($this->data['paypal_visible']){
							$this->data['payment'] = 'paypal';
						}
						else if($this->data['bank_visible']){
							$this->data['payment'] = 'bank';
						}
						else if($this->data['qiwi_visible']){
							$this->data['payment'] = 'qiwi';
						}
						else if($this->data['card_visible']){
							$this->data['payment'] = 'card';
						}
						else if($this->data['yandex_visible']){
							$this->data['payment'] = 'yandex';
						}
						else if($this->data['webmoney_visible']){
							$this->data['payment'] = 'webmoney';
						}
						else if($this->data['webmoney_visibleWMZ']){
							$this->data['payment'] = 'webmoneyWMZ';
						}
						else if($this->data['webmoney_visibleWMU']){
							$this->data['payment'] = 'webmoneyWMU';
						}
						else if($this->data['webmoney_visibleWME']){
							$this->data['payment'] = 'webmoneyWME';
						}
						else if($this->data['webmoney_visibleWMY']){
							$this->data['payment'] = 'webmoneyWMY';
						}
						else if($this->data['webmoney_visibleWMB']){
							$this->data['payment'] = 'webmoneyWMB';
						}
						else if($this->data['webmoney_visibleWMG']){
							$this->data['payment'] = 'webmoneyWMG';
						}
						else if($this->data['AlertPay_visible']){
							$this->data['payment'] = 'AlertPay';
						}
						else if($this->data['Moneybookers_visible']){
							$this->data['payment'] = 'Moneybookers';
						}
						else if($this->data['LIQPAY_visible']){
							$this->data['payment'] = 'LIQPAY';
						}
						else if($this->data['SagePay_visible']){
							$this->data['payment'] = 'SagePay';
						}
						else if($this->data['twoCheckout_visible']){
							$this->data['payment'] = 'twoCheckout';
						}
						else if($this->data['GoogleWallet_visible']){
							$this->data['payment'] = 'GoogleWallet';
						}
					}
					} else {
					if($this->data['config_bonus_visible']){
						$this->data['payment'] = 'bonus';
					}
					else if($this->data['cheque_visible']){
						$this->data['payment'] = 'cheque';
					}
					else if($this->data['paypal_visible']){
						$this->data['payment'] = 'paypal';
					}
					else if($this->data['bank_visible']){
						$this->data['payment'] = 'bank';
					}
					else if($this->data['qiwi_visible']){
						$this->data['payment'] = 'qiwi';
					}
					else if($this->data['card_visible']){
						$this->data['payment'] = 'card';
					}
					else if($this->data['yandex_visible']){
						$this->data['payment'] = 'yandex';
					}
					else if($this->data['webmoney_visible']){
						$this->data['payment'] = 'webmoney';
					}
					else if($this->data['webmoney_visibleWMZ']){
						$this->data['payment'] = 'webmoneyWMZ';
					}
					else if($this->data['webmoney_visibleWMU']){
						$this->data['payment'] = 'webmoneyWMU';
					}
					else if($this->data['webmoney_visibleWME']){
						$this->data['payment'] = 'webmoneyWME';
					}
					else if($this->data['webmoney_visibleWMY']){
						$this->data['payment'] = 'webmoneyWMY';
					}
					else if($this->data['webmoney_visibleWMB']){
						$this->data['payment'] = 'webmoneyWMB';
					}
					else if($this->data['webmoney_visibleWMG']){
						$this->data['payment'] = 'webmoneyWMG';
					}
					else if($this->data['AlertPay_visible']){
						$this->data['payment'] = 'AlertPay';
					}
					else if($this->data['Moneybookers_visible']){
						$this->data['payment'] = 'Moneybookers';
					}
					else if($this->data['LIQPAY_visible']){
						$this->data['payment'] = 'LIQPAY';
					}
					else if($this->data['SagePay_visible']){
						$this->data['payment'] = 'SagePay';
					}
					else if($this->data['twoCheckout_visible']){
						$this->data['payment'] = 'twoCheckout';
					}
					else if($this->data['GoogleWallet_visible']){
						$this->data['payment'] = 'GoogleWallet';
					}
				}
				
				if (isset($this->request->post['cheque'])) {
					$this->data['cheque'] = $this->request->post['cheque'];
					} elseif (!empty($affiliate_info)) {
					$this->data['cheque'] = $affiliate_info['cheque'];
					} else {
					$this->data['cheque'] = '';
				}
				
				if (isset($this->request->post['paypal'])) {
					$this->data['paypal'] = $this->request->post['paypal'];
					} elseif (!empty($affiliate_info)) {
					$this->data['paypal'] = $affiliate_info['paypal'];
					} else {
					$this->data['paypal'] = '';
				}
				
				if (isset($this->request->post['bank_name'])) {
					$this->data['bank_name'] = $this->request->post['bank_name'];
					} elseif (!empty($affiliate_info)) {
					$this->data['bank_name'] = $affiliate_info['bank_name'];
					} else {
					$this->data['bank_name'] = '';
				}
				
				if (isset($this->request->post['bank_branch_number'])) {
					$this->data['bank_branch_number'] = $this->request->post['bank_branch_number'];
					} elseif (!empty($affiliate_info)) {
					$this->data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
					} else {
					$this->data['bank_branch_number'] = '';
				}
				
				if (isset($this->request->post['bank_swift_code'])) {
					$this->data['bank_swift_code'] = $this->request->post['bank_swift_code'];
					} elseif (!empty($affiliate_info)) {
					$this->data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
					} else {
					$this->data['bank_swift_code'] = '';
				}
				
				if (isset($this->request->post['bank_account_name'])) {
					$this->data['bank_account_name'] = $this->request->post['bank_account_name'];
					} elseif (!empty($affiliate_info)) {
					$this->data['bank_account_name'] = $affiliate_info['bank_account_name'];
					} else {
					$this->data['bank_account_name'] = '';
				}
				
				if (isset($this->request->post['bank_account_number'])) {
					$this->data['bank_account_number'] = $this->request->post['bank_account_number'];
					} elseif (!empty($affiliate_info)) {
					$this->data['bank_account_number'] = $affiliate_info['bank_account_number'];
					} else {
					$this->data['bank_account_number'] = '';
				}
				
				if (isset($this->request->post['status'])) {
					$this->data['status'] = $this->request->post['status'];
					} elseif (!empty($affiliate_info)) {
					$this->data['status'] = $affiliate_info['status'];
					} else {
					$this->data['status'] = 1;
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
				
				$this->template = 'sale/affiliate_form.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);
				
				$this->response->setOutput($this->render());
			}
			
			protected function validateForm() {
				if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
					$this->error['warning'] = $this->language->get('error_permission');
				}
				
				if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
					$this->error['firstname'] = $this->language->get('error_firstname');
				}
				
				if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
					$this->error['lastname'] = $this->language->get('error_lastname');
				}
				
				if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
					$this->error['email'] = $this->language->get('error_email');
				}
				
				$affiliate_info = $this->model_sale_affiliate->getAffiliateByEmail($this->request->post['email']);
				
				if (!isset($this->request->get['affiliate_id'])) {
					if ($affiliate_info) {
						$this->error['warning'] = $this->language->get('error_exists');
					}
					} else {
					if ($affiliate_info && ($this->request->get['affiliate_id'] != $affiliate_info['affiliate_id'])) {
						$this->error['warning'] = $this->language->get('error_exists');
					}
				}
				
				if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
					$this->error['telephone'] = $this->language->get('error_telephone');
				}
				
				if ($this->request->post['password'] || (!isset($this->request->get['affiliate_id']))) {
					if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
						$this->error['password'] = $this->language->get('error_password');
					}
					
					if ($this->request->post['password'] != $this->request->post['confirm']) {
						$this->error['confirm'] = $this->language->get('error_confirm');
					}
				}
				
				if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
					$this->error['address_1'] = $this->language->get('error_address_1');
				}
				
				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
					$this->error['city'] = $this->language->get('error_city');
				}
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
					$this->error['postcode'] = $this->language->get('error_postcode');
				}
				
				if ($this->request->post['country_id'] == '') {
					$this->error['country'] = $this->language->get('error_country');
				}
				
				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
					$this->error['zone'] = $this->language->get('error_zone');
				}
				
				if (!$this->request->post['code']) {
					$this->error['code'] = $this->language->get('error_code');
				}
				
				if (!$this->error) {
					return true;
					} else {
					return false;
				}
			}
			
			protected function validateDelete() {
				if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
					$this->error['warning'] = $this->language->get('error_permission');
				}
				
				if (!$this->error) {
					return true;
					} else {
					return false;
				}  
			}
			
			public function country() {
				$json = [];
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
				
				if ($country_info) {
					$this->load->model('localisation/zone');
					
					$json = array(
					'country_id'        => $country_info['country_id'],
					'name'              => $country_info['name'],
					'iso_code_2'        => $country_info['iso_code_2'],
					'iso_code_3'        => $country_info['iso_code_3'],
					'address_format'    => $country_info['address_format'],
					'postcode_required' => $country_info['postcode_required'],
					'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
					'status'            => $country_info['status']		
					);
				}
				
				$this->response->setOutput(json_encode($json));
			}
			
			public function transaction() {
				$this->language->load('sale/affiliate');
				
				$this->load->model('sale/affiliate');
				
				if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/affiliate')) { 
					$this->model_sale_affiliate->addTransaction($this->request->get['affiliate_id'], $this->request->post['description'], $this->request->post['amount']);
					
					$this->data['success'] = $this->language->get('text_success');
					} else {
					$this->data['success'] = '';
				}
				
				if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/affiliate')) {
					$this->data['error_warning'] = $this->language->get('error_permission');
					} else {
					$this->data['error_warning'] = '';
				}
				
				$this->data['text_no_results'] = $this->language->get('text_no_results');
				$this->data['text_balance'] = $this->language->get('text_balance');
				$this->data['request_payment'] = $this->currency->format($this->model_sale_affiliate->getTransactionTotal($this->request->get['affiliate_id']), $this->config->get('config_currency'));
				
				$this->data['column_date_added'] = $this->language->get('column_date_added');
				$this->data['column_description'] = $this->language->get('column_description');
				$this->data['column_amount'] = $this->language->get('column_amount');
				
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
					} else {
					$page = 1;
				}  
				
				$this->data['transactions'] = [];
				
				$results = $this->model_sale_affiliate->getTransactions($this->request->get['affiliate_id'], ($page - 1) * 10, 10);
				
				foreach ($results as $result) {
					$this->data['transactions'][] = array(
					'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
					'description' => $result['description'],
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
					);
				}
				
				$this->data['balance'] = $this->currency->format($this->model_sale_affiliate->getTransactionTotal($this->request->get['affiliate_id']), $this->config->get('config_currency'));
				
				$transaction_total = $this->model_sale_affiliate->getTotalTransactions($this->request->get['affiliate_id']);
				
				$pagination = new Pagination();
				$pagination->total = $transaction_total;
				$pagination->page = $page;
				$pagination->limit = $this->config->get('config_admin_limit');
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('sale/affiliate/transaction', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $this->request->get['affiliate_id'] . '&page={page}');
				
				$this->data['pagination'] = $pagination->render();
				
				$this->template = 'sale/affiliate_transaction.tpl';		
				
				$this->response->setOutput($this->render());
			}
			
			public function autocomplete() {
				$affiliate_data = [];
				
				if (isset($this->request->get['filter_name'])) {
					$this->load->model('sale/affiliate');
					
					$data = array(
					'filter_name' => $this->request->get['filter_name'],
					'start'       => 0,
					'limit'       => 20
					);
					
					$results = $this->model_sale_affiliate->getAffiliates($data);
					
					foreach ($results as $result) {
						$affiliate_data[] = array(
						'affiliate_id' => $result['affiliate_id'],
						'name'         => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')
						);
					}
				}
				
				$this->response->setOutput(json_encode($affiliate_data));
			}		
		}
	?>				
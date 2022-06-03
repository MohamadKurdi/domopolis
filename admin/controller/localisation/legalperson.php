<?php 
	class ControllerLocalisationLegalPerson extends Controller {
		private $error = array();
		
		public function index() {		
			$this->document->setTitle('Юрлица для выставления счетов');
			
			$this->load->model('localisation/legalperson');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('localisation/legalperson');
			
			$this->document->setTitle('Юрлица для выставления счетов');
			
			$this->load->model('localisation/legalperson');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_legalperson->addLegalPerson($this->request->post);
				
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
				
				$this->redirect($this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('localisation/legalperson');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/legalperson');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_legalperson->editLegalPerson($this->request->get['legalperson_id'], $this->request->post);
				
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
				
				$this->redirect($this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('localisation/legalperson');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/legalperson');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $legalperson_id) {
					$this->model_localisation_legalperson->deleteLegalPerson($legalperson_id);
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
				
				$this->redirect($this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('localisation/legalperson/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['delete'] = $this->url->link('localisation/legalperson/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			$this->data['legalpersons'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$language_total = $this->model_localisation_legalperson->getTotalLegalPersons();
			$results = $this->model_localisation_legalperson->getLegalPersons($data);
			
			$this->load->model('localisation/country');
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/legalperson/update', 'token=' . $this->session->data['token'] . '&legalperson_id=' . $result['legalperson_id'] . $url, 'SSL')
				);
				
				$this->data['legalpersons'][] = array(
				'legalperson_id' => $result['legalperson_id'],
				'legalperson_name'        => $result['legalperson_name'],
				'legalperson_name_1C'        => $result['legalperson_name_1C'],
				'legalperson_desc'        => $result['legalperson_desc'],
				'legalperson_print'        => $result['legalperson_print'],
				'legalperson_legal'  => $result['legalperson_legal'],
				'legalperson_country_iso'    => $this->model_localisation_country->getCountryISO2($result['legalperson_country_id']),
				'selected'   => isset($this->request->post['selected']) && in_array($result['legalperson_id'], $this->request->post['selected']),			
				'action'      => $action	
				);		
			}
			
			$this->data['heading_title'] = 'Юрлица для выставления счетов';
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_code'] = $this->language->get('column_code');
			$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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
			
			$this->data['sort_name'] = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
			$this->data['sort_code'] = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . '&sort=code' . $url, 'SSL');
			$this->data['sort_sort_order'] = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $language_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'localisation/legalperson_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = 'Юрлица для выставления счетов';
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_code'] = $this->language->get('entry_code');
			$this->data['entry_locale'] = $this->language->get('entry_locale');
			$this->data['entry_image'] = $this->language->get('entry_image');
			$this->data['entry_directory'] = $this->language->get('entry_directory');
			$this->data['entry_filename'] = $this->language->get('entry_filename');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_status'] = $this->language->get('entry_status');
			
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
				$this->data['error_name'] = '';
			}
			
			if (isset($this->error['code'])) {
				$this->data['error_code'] = $this->error['code'];
				} else {
				$this->data['error_code'] = '';
			}
			
			if (isset($this->error['locale'])) {
				$this->data['error_locale'] = $this->error['locale'];
				} else {
				$this->data['error_locale'] = '';
			}		
			
			if (isset($this->error['image'])) {
				$this->data['error_image'] = $this->error['image'];
				} else {
				$this->data['error_image'] = '';
			}	
			
			if (isset($this->error['directory'])) {
				$this->data['error_directory'] = $this->error['directory'];
				} else {
				$this->data['error_directory'] = '';
			}	
			
			if (isset($this->error['filename'])) {
				$this->data['error_filename'] = $this->error['filename'];
				} else {
				$this->data['error_filename'] = '';
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url, 'SSL'),      		
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['legalperson_id'])) {
				$this->data['action'] = $this->url->link('localisation/legalperson/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
				} else {
				$this->data['action'] = $this->url->link('localisation/legalperson/update', 'token=' . $this->session->data['token'] . '&legalperson_id=' . $this->request->get['legalperson_id'] . $url, 'SSL');
			}
			
			$this->data['cancel'] = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			$this->load->model('localisation/country');
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			if (isset($this->request->get['legalperson_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$legalperson_info = $this->model_localisation_legalperson->getLegalPerson($this->request->get['legalperson_id']);
			}
			
			
			if (isset($this->request->post['legalperson_name'])) {
				$this->data['legalperson_name'] = $this->request->post['legalperson_name'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_name'] = $legalperson_info['legalperson_name'];
				} else {
				$this->data['legalperson_name'] = '';
			}
			
			if (isset($this->request->post['legalperson_name_1C'])) {
				$this->data['legalperson_name_1C'] = $this->request->post['legalperson_name_1C'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_name_1C'] = $legalperson_info['legalperson_name_1C'];
				} else {
				$this->data['legalperson_name_1C'] = '';
			}
			
			if (isset($this->request->post['legalperson_desc'])) {
				$this->data['legalperson_desc'] = $this->request->post['legalperson_desc'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_desc'] = $legalperson_info['legalperson_desc'];
				} else {
				$this->data['legalperson_desc'] = '';
			}
			
			if (isset($this->request->post['legalperson_additional'])) {
				$this->data['legalperson_additional'] = $this->request->post['legalperson_additional'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_additional'] = $legalperson_info['legalperson_additional'];
				} else {
				$this->data['legalperson_additional'] = '';
			}
			
			if (isset($this->request->post['legalperson_print'])) {
				$this->data['legalperson_print'] = $this->request->post['legalperson_print'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_print'] = $legalperson_info['legalperson_print'];
				} else {
				$this->data['legalperson_print'] = '';
			}
			
			if (isset($this->request->post['legalperson_country_id'])) {
				$this->data['legalperson_country_id'] = $this->request->post['legalperson_country_id'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_country_id'] = $legalperson_info['legalperson_country_id'];
				} else {
				$this->data['legalperson_country_id'] = 176;
			}
			
			if (isset($this->request->post['legalperson_legal'])) {
				$this->data['legalperson_legal'] = $this->request->post['legalperson_legal'];
				} elseif (!empty($legalperson_info)) {
				$this->data['legalperson_legal'] = $legalperson_info['legalperson_legal'];
				} else {
				$this->data['legalperson_legal'] = 1;
			}
			
			$this->template = 'localisation/legalperson_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function getLegalPersonAllLimitsAjax(){
			$legalperson_id = (int)$this->request->post['legalperson_id'];
			$currency = $this->request->post['currency'];
			$result = array();
			
			$this->load->model('localisation/legalperson');	
			
			$info = $this->model_localisation_legalperson->getLegalPersonMonthlyTotals($legalperson_id);
			
			$result['total_already_paid'] = $this->currency->format($info['total_already_paid'], $currency, 1);
			$result['total_need_to_pay']  = $this->currency->format($info['total_need_to_pay'], $currency, 1);
			$result['sum']  			   = $this->currency->format($info['sum'], $currency, 1);
			
			$info = $this->model_localisation_legalperson->getLegalPerson($legalperson_id);
			$_unsdata = unserialize($info['account_info']);	
			
			if (isset($_unsdata['EndingBalance'])){
				$result['at_this_moment'] = $this->currency->format($_unsdata['EndingBalance'], $currency, 1);
				} else {
				$result['at_this_moment'] = $this->currency->format(0, $currency, 1);
			}
			
			$this->response->setOutput(json_encode($result));
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'localisation/legalperson')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((utf8_strlen($this->request->post['legalperson_name']) < 3) || (utf8_strlen($this->request->post['legalperson_name']) > 64)) {
				$this->error['legalperson_name'] = $this->language->get('error_name');
			}
			
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'localisation/legalperson')) {
				$this->error['warning'] = $this->language->get('error_permission');
			} 
			
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}	
	}		
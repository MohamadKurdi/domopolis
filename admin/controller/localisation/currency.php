<?php 
	class ControllerLocalisationCurrency extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('localisation/currency');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/currency');
			
			$this->getList();
		}
		
		public function updaterates(){
			$this->load->model('localisation/currency');
			$this->load->model('kp/bitrixBot');
			
			$this->model_localisation_currency->updateCurrencies();			
		}
		
		public function insert() {
			$this->language->load('localisation/currency');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/currency');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_currency->addCurrency($this->request->post);
				
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
				
				$this->redirect($this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('localisation/currency');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/currency');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_currency->editCurrency($this->request->get['currency_id'], $this->request->post);
				
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
				
				$this->redirect($this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('localisation/currency');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/currency');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $currency_id) {
					$this->model_localisation_currency->deleteCurrency($currency_id);
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
				
				$this->redirect($this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'title';
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
			'href'      => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('localisation/currency/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('localisation/currency/delete', 'token=' . $this->session->data['token'] . $url);
			
			$this->data['currencies'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$currency_total = $this->model_localisation_currency->getTotalCurrencies();		
			$results = $this->model_localisation_currency->getCurrencies($data);	
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/currency/update', 'token=' . $this->session->data['token'] . '&currency_id=' . $result['currency_id'] . $url, 'SSL')
				);
				
				//курс к гривне
				if ($result['code']  == 'KZT') {
					$mc = $this->currency->format(100, $result['code'], 1);
					$rc = number_format($this->currency->real_convert(100, $result['code'], 'UAH'),2,'.',' ').' грн';
					$r_rc = number_format($this->currency->real_convert(100, $result['code'], 'RUB'),2,'.',' ').' руб';
					} elseif ($result['code']  == 'UAH') {
					$mc = $this->currency->format(1, $result['code'], 1);
					$rc = number_format($this->currency->real_convert(1, $result['code'], 'UAH'),2,'.',' ').' грн';
					$r_rc = number_format($this->currency->real_convert(1, $result['code'], 'RUB'),2,'.',' ').' руб';
					} elseif($result['code']  == 'BYN') {
					$mc = $this->currency->format(1, $result['code'], 1);
					$rc =  number_format($this->currency->real_convert(1, $result['code'], 'UAH'),2,'.',' ').' грн';
					$r_rc = number_format($this->currency->real_convert(1, $result['code'], 'RUB'),2,'.',' ').' руб';
					} else {
					$mc = $this->currency->format(1, $result['code'], 1);
					$rc = number_format($this->currency->real_convert(1, $result['code'], 'UAH'),2,'.',' ').' грн';
					$r_rc = number_format($this->currency->real_convert(1, $result['code'], 'RUB'),2,'.',' ').' руб';
				}
				
				$this->data['currencies'][] = array(
				'currency_id'   		=> $result['currency_id'],
				'title'         		=> $result['title'] . (($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_default') : null),
				'code'         		 	=> $result['code'],
				'morph'          		=> $result['morph'],
				'flag'          		=> $result['flag'],
				'value'         		=> number_format($result['value'],2,'.',' '),
				'value_real'    		=> number_format($result['value_real'],2,'.',' '),
				'value_minimal'  		=> number_format($result['value_minimal'],2,'.',' '),
				'value_eur_official'  	=> number_format($result['value_eur_official'],2,'.',' '),
				'cryptopair'  			=> $result['cryptopair'],
				'cryptopair_value'  	=> number_format($result['cryptopair_value'],2,'.',' '),
				'plus_percent'  		=> $result['plus_percent'],
				'auto_percent'  		=> $result['auto_percent'],			
				'date_modified' 		=> date('Y.m.d в H:i:s', strtotime($result['date_modified'])),
				'selected'      		=> isset($this->request->post['selected']) && in_array($result['currency_id'], $this->request->post['selected']),
				'rc'    				=> $rc,
				'r_rc'    				=> $r_rc,
				'mc'  			 		=> $mc,
				'action'        		=> $action
				);
			}	
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_title'] = $this->language->get('column_title');
			$this->data['column_code'] = $this->language->get('column_code');
			$this->data['column_value'] = $this->language->get('column_value');
			$this->data['column_date_modified'] = $this->language->get('column_date_modified');
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
			
			$this->data['sort_title'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . '&sort=title' . $url);
			$this->data['sort_code'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . '&sort=code' . $url);
			$this->data['sort_value'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . '&sort=value' . $url);
			$this->data['sort_date_modified'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $currency_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			if (isset($this->request->get['ajax']) && ($this->request->get['ajax'] == 1)){
				$this->template = 'localisation/currency_list_ajax.tpl';
				} else {
				$this->template = 'localisation/currency_list.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);
			}
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			
			$this->data['entry_title'] = $this->language->get('entry_title');
			$this->data['entry_code'] = $this->language->get('entry_code');
			$this->data['entry_value'] = $this->language->get('entry_value');
			$this->data['entry_symbol_left'] = $this->language->get('entry_symbol_left');
			$this->data['entry_symbol_right'] = $this->language->get('entry_symbol_right');
			$this->data['entry_decimal_place'] = $this->language->get('entry_decimal_place');
			$this->data['entry_status'] = $this->language->get('entry_status');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['title'])) {
				$this->data['error_title'] = $this->error['title'];
				} else {
				$this->data['error_title'] = '';
			}
			
			if (isset($this->error['code'])) {
				$this->data['error_code'] = $this->error['code'];
				} else {
				$this->data['error_code'] = '';
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
			'href'      => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url),      		
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['currency_id'])) {
				$this->data['action'] = $this->url->link('localisation/currency/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('localisation/currency/update', 'token=' . $this->session->data['token'] . '&currency_id=' . $this->request->get['currency_id'] . $url);
			}
			
			$this->data['go_to_edit'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token']);
			
			$this->data['cancel'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['currency_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$currency_info = $this->model_localisation_currency->getCurrency($this->request->get['currency_id']);
			}
			
			if (isset($this->request->post['title'])) {
				$this->data['title'] = $this->request->post['title'];
				} elseif (!empty($currency_info)) {
				$this->data['title'] = $currency_info['title'];
				} else {
				$this->data['title'] = '';
			}
			
			if (isset($this->request->post['morph'])) {
				$this->data['morph'] = $this->request->post['morph'];
				} elseif (!empty($currency_info)) {
				$this->data['morph'] = $currency_info['morph'];
				} else {
				$this->data['morph'] = '';
			}
			
			if (isset($this->request->post['code'])) {
				$this->data['code'] = $this->request->post['code'];
				} elseif (!empty($currency_info)) {
				$this->data['code'] = $currency_info['code'];
				} else {
				$this->data['code'] = '';
			}
			
			if (isset($this->request->post['flag'])) {
				$this->data['flag'] = $this->request->post['flag'];
				} elseif (!empty($currency_info)) {
				$this->data['flag'] = $currency_info['flag'];
				} else {
				$this->data['flag'] = '';
			}
			
			if (isset($this->request->post['symbol_left'])) {
				$this->data['symbol_left'] = $this->request->post['symbol_left'];
				} elseif (!empty($currency_info)) {
				$this->data['symbol_left'] = $currency_info['symbol_left'];
				} else {
				$this->data['symbol_left'] = '';
			}
			
			if (isset($this->request->post['symbol_right'])) {
				$this->data['symbol_right'] = $this->request->post['symbol_right'];
				} elseif (!empty($currency_info)) {
				$this->data['symbol_right'] = $currency_info['symbol_right'];
				} else {
				$this->data['symbol_right'] = '';
			}
			
			if (isset($this->request->post['decimal_place'])) {
				$this->data['decimal_place'] = $this->request->post['decimal_place'];
				} elseif (!empty($currency_info)) {
				$this->data['decimal_place'] = $currency_info['decimal_place'];
				} else {
				$this->data['decimal_place'] = '';
			}
			
			if (isset($this->request->post['value_uah_unreal'])) {
				$this->data['value_uah_unreal'] = $this->request->post['value_uah_unreal'];
				} elseif (!empty($currency_info)) {
				$this->data['value_uah_unreal'] = $currency_info['value_uah_unreal'];
				} else {
				$this->data['value_uah_unreal'] = '';
			}
			
			if (isset($this->request->post['value_minimal'])) {
				$this->data['value_minimal'] = $this->request->post['value_minimal'];
				} elseif (!empty($currency_info)) {
				$this->data['value_minimal'] = $currency_info['value_minimal'];
				} else {
				$this->data['value_minimal'] = '';
			}
			
			if (isset($this->request->post['value_eur_official'])) {
				$this->data['value_eur_official'] = $this->request->post['value_eur_official'];
				} elseif (!empty($currency_info)) {
				$this->data['value_eur_official'] = $currency_info['value_eur_official'];
				} else {
				$this->data['value_eur_official'] = '';
			}
			
			if (isset($this->request->post['value_real'])) {
				$this->data['value_real'] = $this->request->post['value_real'];
				} elseif (!empty($currency_info)) {
				$this->data['value_real'] = $currency_info['value_real'];
				} else {
				$this->data['value_real'] = '';
			}

			
			if (isset($this->request->post['value'])) {
				$this->data['value'] = $this->request->post['value'];
				} elseif (!empty($currency_info)) {
				$this->data['value'] = $currency_info['value'];
				} else {
				$this->data['value'] = '';
			}

			if (isset($this->request->post['cryptopair'])) {
				$this->data['cryptopair'] = $this->request->post['cryptopair'];
				} elseif (!empty($currency_info)) {
				$this->data['cryptopair'] = $currency_info['cryptopair'];
				} else {
				$this->data['cryptopair'] = '';
			}

			if (isset($this->request->post['cryptopair_value'])) {
				$this->data['cryptopair_value'] = $this->request->post['cryptopair_value'];
				} elseif (!empty($currency_info)) {
				$this->data['cryptopair_value'] = $currency_info['cryptopair_value'];
				} else {
				$this->data['cryptopair_value'] = '';
			}
			
			if (isset($this->request->post['plus_percent'])) {
				$this->data['plus_percent'] = $this->request->post['plus_percent'];
				} elseif (!empty($currency_info)) {
				$this->data['plus_percent'] = $currency_info['plus_percent'];
				} else {
				$this->data['plus_percent'] = '';
			}
			
			if (isset($this->request->post['auto_percent'])) {
				$this->data['auto_percent'] = $this->request->post['auto_percent'];
				} elseif (!empty($currency_info)) {
				$this->data['auto_percent'] = $currency_info['auto_percent'];
				} else {
				$this->data['auto_percent'] = '';
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (!empty($currency_info)) {
				$this->data['status'] = $currency_info['status'];
				} else {
				$this->data['status'] = '';
			}
			
			$this->template = 'localisation/currency_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validateForm() { 
			if (!$this->user->hasPermission('modify', 'localisation/currency')) { 
				$this->error['warning'] = $this->language->get('error_permission');
			} 
			
			if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 32)) {
				$this->error['title'] = $this->language->get('error_title');
			}
			
			if (utf8_strlen($this->request->post['code']) != 3) {
				$this->error['code'] = $this->language->get('error_code');
			}
			
			if (!$this->error) { 
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'localisation/currency')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			
			foreach ($this->request->post['selected'] as $currency_id) {
				$currency_info = $this->model_localisation_currency->getCurrency($currency_id);
				
				if ($currency_info) {
					if ($this->config->get('config_currency') == $currency_info['code']) {
						$this->error['warning'] = $this->language->get('error_default');
					}
					
					$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);
					
					if ($store_total) {
						$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
					}					
				}
				
				$order_total = $this->model_sale_order->getTotalOrdersByCurrencyId($currency_id);
				
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
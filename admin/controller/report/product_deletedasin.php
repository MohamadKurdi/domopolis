<?php
	class ControllerReportProductDeletedASIN extends Controller {

		public function index() {

			$this->getList();
		}
	
		public function delete() {			
			$this->load->model('report/product');
			$url = '';			
		
			if (isset($this->request->get['filter_asin'])) {
				$url .= '&filter_asin=' . $this->request->get['filter_asin'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
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
			
			if (isset($this->request->post['selected'])) {							
			
				foreach ($this->request->post['selected'] as $asin) {
					$this->model_report_product->deleteDeletedASIN($asin);
				}
				$this->session->data['success'] = 'Успешно обновили список исключенных ASIN';
			}
			
			$this->response->redirect($this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));	
		}
		
		public function add() {
			$this->load->model('report/product');
			
			$url = '';
			
			if (isset($this->request->get['filter_asin'])) {
				$url .= '&filter_asin=' . $this->request->get['filter_asin'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
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
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['asin']))) {
				$this->model_report_product->insertDeletedASIN($this->request->post);
				$this->session->data['success'] = 'Добавили ASIN';				
			}
			
			$this->response->redirect($this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}

		
		protected function getList() {
			$this->load->language('report/product_viewed');
			
			$this->document->setTitle('Исключенные ASIN');
			
			$filter_asin = isset($this->request->get['filter_asin']) ? $this->request->get['filter_asin'] : null;
			$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_asin'])) {
				$url .= '&filter_asin=' . $this->request->get['filter_asin'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$this->data['breadcrumbs'][] = array(
			'text' => 'Исключенные ASIN',
			'href' => $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$this->load->model('report/product');
			
			$this->config->set('config_limit_admin', 100);
			
			$filter_data = array(
			'filter_asin' 	=> $filter_asin,
			'filter_name' 	=> $filter_name,
			'start' 		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 		=> $this->config->get('config_limit_admin')
			);
			
			$this->data['asins'] = array();
					
			$product_total = $this->model_report_product->getTotalProductsDeletedASIN($filter_data);			
			$results = $this->model_report_product->getProductsDeletedASIN($filter_data);
			
			$this->load->model('user/user');
			foreach ($results as $result) {	
				$result['clear_asin'] = $result['asin'];

				if (!empty($filter_asin)){
					$result['asin'] = str_replace($filter_asin, '<b>' . $filter_asin . '</b>', $result['asin']);
				}
				
				if (!empty($filter_name)){
					$result['name'] = str_replace($filter_name, '<b>' . $filter_name . '</b>', $result['name']);
				}
			
			
				$this->data['asins'][] = array(
				'clear_asin'	=> $result['clear_asin'],
				'asin'    		=> $result['asin'],
				'name'	 		=> $result['name'],
				'date_added'	=> date('Y-m-d H:i:s', strtotime($result['date_added'])),			
				'user'			=> $this->model_user_user->getRealUserNameById($result['user_id'])
				);
			}
			
			$this->data['heading_title'] = 'Исключенные ASIN';
			
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_confirm'] = $this->language->get('text_confirm');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_viewed'] = $this->language->get('column_viewed');
			$this->data['column_percent'] = $this->language->get('column_percent');
			
			$this->data['button_reset'] = $this->language->get('button_reset');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_asin'])) {
				$url .= '&filter_asin=' . $this->request->get['filter_asin'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			$this->data['delete'] 		= $this->url->link('report/product_deletedasin/delete', 'token=' . $this->session->data['token'] . $url);
			$this->data['save'] 		= $this->url->link('report/product_deletedasin/add', 'token=' . $this->session->data['token'] . $url);			
			$this->data['excluded_asins'] 	= $this->url->link('report/product_excludedasin', 'token=' . $this->session->data['token'] . $url);	
			
			if (isset($this->session->data['error'])) {
				$this->data['error_warning'] = $this->session->data['error'];
				
				unset($this->session->data['error']);
				} elseif (isset($this->error['warning'])) {
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
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('report/product_deletedasin',  'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();


			$this->data['filter_asin'] = $filter_asin;
			$this->data['filter_name'] = $filter_name;			

			$this->template = 'report/product_deletedasin.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
	}	
<?php
	class ControllerReportProductExcludedASIN extends Controller {

		public function index() {

			$this->getList();
		}
	
		public function delete() {			
			$this->load->model('report/product');
			$url = '';			
		
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_text'])) {
				$url .= '&filter_text=' . $this->request->get['filter_text'];
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
			
				foreach ($this->request->post['selected'] as $text) {
					$this->model_report_product->deleteExcludedText($text);
				}
				$this->session->data['success'] = 'Успешно обновили список исключенных слов';
			}
			
			$this->response->redirect($this->url->link('report/product_excludedasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));	
		}
		
		public function add() {
			$this->load->model('report/product');
			
			$url = '';
			
			if (isset($this->request->get['filter_text'])) {
				$url .= '&filter_text=' . $this->request->get['filter_text'];
			}

			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
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
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['text']))) {
				$this->model_report_product->insertExcludedText($this->request->post);
				$this->session->data['success'] = 'Добавили слово';				
			}
			
			$this->response->redirect($this->url->link('report/product_excludedasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}

		
		protected function getList() {
			$this->load->language('report/product_viewed');
			$this->load->model('catalog/category');
			
			$this->document->setTitle('Исключенные слова');
						
			$filter_text 		= isset($this->request->get['filter_text']) ? $this->request->get['filter_text'] : null;
			$filter_category_id = !empty($this->request->get['filter_category_id']) ? $this->request->get['filter_category_id'] : null;			
			$filter_category 	= !empty($this->request->get['filter_category_id']) ? $this->model_catalog_category->getCategory($this->request->get['filter_category_id'])['name'] : null;			

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
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_text'])) {
				$url .= '&filter_text=' . $this->request->get['filter_text'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			
			$this->load->model('report/product');
			
			$this->config->set('config_limit_admin', 100);
			
			$filter_data = array(			
			'filter_text' 			=> $filter_text,
			'filter_category_id' 	=> $filter_category_id,
			'start' 				=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 				=> $this->config->get('config_limit_admin')
			);
			
			$this->data['texts'] = array();
					
			$product_total = $this->model_report_product->getTotalProductsExcludedTexts($filter_data);			
			$results = $this->model_report_product->getProductsExcludedTexts($filter_data);
			
			$this->load->model('user/user');
			foreach ($results as $result) {	
				$result['clear_text'] = $result['text'];
				
				if (!empty($filter_text)){
					$result['text'] = str_replace($filter_text, '<b>' . $filter_text . '</b>', $result['text']);
				}				
			
				$this->data['texts'][] = array(
				'clear_text'	=> $result['clear_text'],
				'text'    		=> $result['text'],
				'category_id'	=> $result['category_id'],
				'times'			=> $result['times'],
				'category'		=> ($result['category_id'])?$this->model_catalog_category->getCategory($result['category_id']):'',
				'date_added'	=> date('Y-m-d H:i:s', strtotime($result['date_added'])),			
				'user'			=> $this->model_user_user->getRealUserNameById($result['user_id'])
				);
			}
			
			$this->data['heading_title'] = 'Исключенные слова';
			
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_none'] = $this->language->get('text_none');
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
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_text'])) {
				$url .= '&filter_text=' . $this->request->get['filter_text'];
			}
						
			$this->data['delete'] 		= $this->url->link('report/product_excludedasin/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['save'] 		= $this->url->link('report/product_excludedasin/add', 'token=' . $this->session->data['token'] . $url, 'SSL');			
			$this->data['deleted_asins'] 	= $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, 'SSL');	
			
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
			$pagination->url = $this->url->link('report/product_excludedasin',  'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();


			$this->data['filter_category_id'] 	= $filter_category_id;
			$this->data['filter_category'] 		= $filter_category;
			$this->data['filter_text'] 			= $filter_text;			

			$this->template = 'report/product_excludedasin.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
	}	
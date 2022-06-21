<?php
	class ControllerReportProductDeletedASIN extends Controller {
	
		public function delete() {			
			$this->load->model('report/product');
			$url = '';
			
		
			if (isset($this->request->get['filter_sku'])) {
				$url .= '&filter_sku=' . $this->request->get['filter_sku'];
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
			
				foreach ($this->request->post['selected'] as $sku) {
					$this->model_report_product->deleteDeletedSKU($sku);
				}
				$this->session->data['success'] = 'Успешно обновили список исключенных ASIN';
			}
			
			$this->response->redirect($this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		public function add() {
			$this->load->model('report/product');
			
			$url = '';
			
			if (isset($this->request->get['filter_sku'])) {
				$url .= '&filter_sku=' . $this->request->get['filter_sku'];
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
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['sku']))) {
				$this->model_report_product->insertDeletedSKU($this->request->post);
				$this->session->data['success'] = 'Добавили ASIN';				
			}
			
			$this->response->redirect($this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}

		
		public function index() {
			$this->load->language('report/product_viewed');
			
			$this->document->setTitle('Исключенные ASIN');
			
			$filter_sku = isset($this->request->get['filter_sku']) ? $this->request->get['filter_sku'] : null;
			$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$data['token'] = $this->session->data['token'];
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_sku'])) {
				$url .= '&filter_sku=' . $this->request->get['filter_sku'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => 'Исключенные ASIN',
			'href' => $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			
			$this->load->model('report/product');
			
			$this->config->set('config_limit_admin', 100);
			
			$filter_data = array(
			'filter_sku' 	=> $filter_sku,
			'filter_name' 	=> $filter_name,
			'start' 		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 		=> $this->config->get('config_limit_admin')
			);
			
			$data['skus'] = array();
					
			$product_total = $this->model_report_product->getTotalProductsDeletedSKU($filter_data);
			
			$results = $this->model_report_product->getProductsDeletedSKU($filter_data);
			
			foreach ($results as $result) {		
				
				$result['nfsku'] = $result['sku'];
				
				if (!empty($filter_sku)){
					$result['sku'] = str_replace($filter_sku, '<b>' . $filter_sku . '</b>', $result['sku']);
				}
				
				if (!empty($filter_name)){
					$result['name'] = str_replace($filter_name, '<b>' . $filter_name . '</b>', $result['name']);
				}

				$product = $this->model_report_product->getProductBySKU($result['sku']);
			
			
				$data['skus'][] = array(
				'nfsku'	 		=> $result['nfsku'],
				'sku'    		=> $result['sku'],
				'name'	 		=> $result['name'],
				'pname'  		=> $product?$product['name']:false,
				'parchive'  	=> $product?$product['archive']:false,
				);
			}
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_list'] = $this->language->get('text_list');
			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_confirm'] = $this->language->get('text_confirm');
			
			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_viewed'] = $this->language->get('column_viewed');
			$data['column_percent'] = $this->language->get('column_percent');
			
			$data['button_reset'] = $this->language->get('button_reset');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_sku'])) {
				$url .= '&filter_sku=' . $this->request->get['filter_sku'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			$data['reset'] = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . $url, true);
			
			
			$data['cancel'] = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'], 'SSL');	
			$data['delete'] = $this->url->link('report/product_deletedasin/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$data['save'] = $this->url->link('report/product_deletedasin/add', 'token=' . $this->session->data['token'] . $url, 'SSL');			
			
			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];
				
				unset($this->session->data['error']);
				} elseif (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
				} else {
				$data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$data['success'] = '';
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'] . '&page={page}', true);
			
			$data['filter_sku'] = $filter_sku;
			$data['filter_name'] = $filter_name;
			$data['sort'] = $sort;
			$data['order'] = $order;
			
			$data['pagination'] = $pagination->render();
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('report/product_deletedasin', $data));
		}
		
	}	
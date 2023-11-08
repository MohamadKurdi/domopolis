<?php 
	class ControllerSaleCompetitors extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('sale/competitors');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/competitors');
			$this->load->model('catalog/product');
			
			$this->getList();
		}		
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'sort_order, competitors_name';
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
			'href'      => $this->url->link('sale/competitors', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
					
			
			$this->data['competitors'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 50,
			'limit' => 50,
			'filter_manufacturer_id' => 201
			);
			
			$total = $this->model_sale_competitors->getTotalProducts($data);			
			$results = $this->model_sale_competitors->getProducts($data);
			
			foreach ($results as $result) {								
				$action = array();
				
				

				$this->data['competitors'][] = array(
				
				);		
			}
			
			$this->data['heading_title'] = 'Мониторинг цен конкурентов';
			$this->document->setTitle($this->data['heading_title']);
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
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
			
			$this->data['sort_name'] = $this->url->link('sale/competitors', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
			$this->data['sort_code'] = $this->url->link('sale/competitors', 'token=' . $this->session->data['token'] . '&sort=code' . $url);
			$this->data['sort_sort_order'] = $this->url->link('sale/competitors', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url);
			
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
			$pagination->limit = 50;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/competitors', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'sale/competitors.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		}
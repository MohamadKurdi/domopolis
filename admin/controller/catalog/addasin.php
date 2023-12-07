<?php
	class ControllerCatalogAddASIN extends Controller {

		public function index() {
			$this->getList();
		}

		public function amazon(){
			$this->load->language('report/product_viewed');

			$this->document->setTitle('Добавление товаров с Amazon');
			$this->data['heading_title'] = 'Просмотр Amazon';

			$this->data['token'] = $this->session->data['token'];

			$this->data['queue'] 	= $this->url->link('catalog/addasin',  'token=' . $this->session->data['token']);

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

			$this->template = 'catalog/addasinamazon.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			
			$this->response->setOutput($this->render());
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

			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
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
					$this->model_report_product->deleteASINFromQueue($asin);
				}
				$this->session->data['success'] = 'Удалили ASIN из очереди';
			}
			
			$this->response->redirect($this->url->link('catalog/addasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
			}

			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
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
				$this->model_report_product->insertAsinToQueue($this->request->post);
				$this->session->data['success'] = 'Добавили ASIN в очередь';				
			}
			
			$this->response->redirect($this->url->link('catalog/addasin', 'token=' . $this->session->data['token'] . $url, 'SSL'));			
		}

		protected function getList() {
			$this->load->language('report/product_viewed');
						
			$this->load->model('report/product');
			$this->load->model('catalog/product');
			$this->load->model('catalog/category');
			$this->load->model('user/user');
			$this->load->model('tool/image');
			$this->load->model('kp/product');
			
			$this->document->setTitle('Добавление товаров с Amazon');
			$this->data['heading_title'] = 'Добавление товаров с Amazon';
			
			$filter_asin 		= isset($this->request->get['filter_asin']) ? $this->request->get['filter_asin'] : null;
			$filter_name 		= isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : null;
			$filter_problems 	= isset($this->request->get['filter_problems']) ? $this->request->get['filter_problems'] : null;
			$filter_user_id 	= isset($this->request->get['filter_user_id']) ? $this->request->get['filter_user_id'] : null;
			
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

			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
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
			'text' => 'Добавление товаров с Amazon',
			'href' => $this->url->link('catalog/addasin', 'token=' . $this->session->data['token'] . $url, true)
			);			
			
			$filter_data = array(
			'filter_asin' 		=> $filter_asin,
			'filter_name' 		=> $filter_name,
			'filter_problems' 	=> $filter_problems,
			'filter_user_id' 	=> $filter_user_id,
			'start' 			=> ($page - 1) * 100,
			'limit' 			=> 100
			);
			
			$this->data['products'] = array();
					
			$product_total 	= $this->model_report_product->getTotalProductsInASINQueue($filter_data);			
			$results 		= $this->model_report_product->getProductsInASINQueue($filter_data);
			
			foreach ($results as $result) {	

				if (!empty($filter_asin)){
					$result['asin'] = str_replace($filter_asin, '<b>' . $filter_asin . '</b>', $result['asin']);
				}
				
				if (!empty($filter_name)){
					$result['name'] = str_replace($filter_name, '<b>' . $filter_name . '</b>', $result['name']);
				}
				
				$amazon_product_data = $this->model_kp_product->getProductAmazonFullData($result['asin']);
				$amazon_product_json = false;
				if ($this->config->get('config_enable_amazon_asin_file_cache')){	
					if ($amazon_product_data && file_exists(DIR_CACHE . $amazon_product_data['file'])){
						$amazon_product_json = HTTPS_CATALOG . 'system/' . DIR_CACHE_NAME . $amazon_product_data['file'];				
					}
				}
			
				$this->data['products'][] = array(
				'asin'    		=> $result['asin'],
				'name'	 		=> !empty($result['name'])?$result['name']:false,
				'date_added'	=> date('Y-m-d', strtotime($result['date_added'])),
				'time_added'	=> date('H:i:s', strtotime($result['date_added'])),
				'product_id'	=> $result['product_id'],
				'brand_logic'	=> $result['brand_logic'],
				'amazon_product_json' 	=> $amazon_product_json,
				'amazon_best_price' 	=> (float)$result['amazon_best_price'],
				'amazon_best_price_eur'	=> $this->currency->format($result['amazon_best_price'], 'EUR', 1),
				'status'		=> ($result['product_id'] > 0)?$result['status']:false,
				'edit'			=> ($result['product_id'] > 0)?$this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'):false,
				'view'			=> ($result['product_id'] > 0)?(HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id']):false,
				'date_created'	=> ($result['product_id'] > 0)?date('Y-m-d', strtotime($result['date_created'])):false,		
				'image'			=> $result['image']?$this->model_tool_image->resize($result['image'], 50, 50):false,
				'category_id'	=> $result['category_id'],
				'category'		=> $result['category_id']?$this->model_catalog_category->getCategory($result['category_id']):false,
				'user'			=> $this->model_user_user->getRealUserNameById($result['user_id'])
				);
			}

			$this->data['native_language'] = $this->registry->get('languages')[$this->config->get('config_language')];
			$this->data['amazon_language'] = $this->registry->get('languages')[$this->config->get('config_de_language')];		

			$this->data['users'] = [];
			$users = $this->model_report_product->getUsersFromAsinQueue();

			foreach ($users as $user_id){
				$this->data['users'][] = [
					'user' 		=> $this->model_user_user->getRealUserNameById($user_id),
					'user_id' 	=> $user_id
				];
			}
			
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_confirm'] = $this->language->get('text_confirm');
			$this->data['text_none'] = $this->language->get('text_none');
			
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

			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
			}
						
			$this->data['delete'] = $this->url->link('catalog/addasin/delete', 'token=' . $this->session->data['token'] . $url);
			$this->data['add'] = $this->url->link('catalog/addasin/add', 'token=' . $this->session->data['token'] . $url);			
			
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

			$url = '';
			
			if (isset($this->request->get['filter_asin'])) {
				$url .= '&filter_asin=' . $this->request->get['filter_asin'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
			}
			
			$pagination 		= new Pagination();			
			$this->data['pagination'] = $pagination->render([
				'total' 	=> 	$product_total,
				'page' 		=>	$page,
				'limit'		=> 	100,
				'text' 		=>	$this->language->get('text_pagination'),
				'url'		=> 	$this->url->link('catalog/addasin',  'token=' . $this->session->data['token'] . $url . '&page={page}'),
			]);

			$this->data['filter_asin'] 		= $filter_asin;
			$this->data['filter_name'] 		= $filter_name;		
			$this->data['filter_user_id'] 	= $filter_user_id;	

			$filter_data = array(
			'filter_problems' 	=> true
			);

			$this->data['filter_problems_count'] 	= $this->model_report_product->getTotalProductsInASINQueue($filter_data);
			$this->data['filter_problems'] 			= $filter_problems;

			$url = '';

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
			}

			$this->data['filter_problems_href'] 	= $this->url->link('catalog/addasin',  'token=' . $this->session->data['token'] . $url . '&filter_problems=1');	

			$this->data['amazon'] 	= $this->url->link('catalog/addasin/amazon',  'token=' . $this->session->data['token']);			


			$this->template = 'catalog/addasin.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}

	}
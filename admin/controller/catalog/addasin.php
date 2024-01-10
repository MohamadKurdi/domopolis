<?php
	class ControllerCatalogAddASIN extends Controller {

		public function index() {
			$this->getList();
		}

		public function report_total(){
			$this->load->model('report/product');

			$filter_date_from 	= isset($this->request->get['filter_date_from']) ? $this->request->get['filter_date_from'] : null;
			$filter_date_to 	= isset($this->request->get['filter_date_to']) ? $this->request->get['filter_date_to'] : null;

			$filter_data = [
			'filter_good' 			=> true,
			'filter_date_from' 		=> $filter_date_from,
			'filter_date_to' 		=> $filter_date_to
			];

			$total = $this->model_report_product->getTotalProductsInASINQueue($filter_data);
			$this->response->setOutput($total);
		}

		public function report(){
			$this->load->language('report/product_viewed');
						
			$this->load->model('report/product');
			$this->load->model('catalog/product');
			$this->load->model('catalog/category');
			$this->load->model('user/user');
			$this->load->model('tool/image');
			$this->load->model('kp/product');
			
			$this->document->setTitle('Отчет по добавлению товаров с Amazon');
			$this->data['heading_title'] = '✅ Отчет по добавлению товаров с Amazon';

			$filter_date_from 	= isset($this->request->get['filter_date_from']) ? $this->request->get['filter_date_from'] : null;
			$filter_date_to 	= isset($this->request->get['filter_date_to']) ? $this->request->get['filter_date_to'] : null;
			$filter_user_id 	= isset($this->request->get['filter_user_id']) ? $this->request->get['filter_user_id'] : null;
			$page 				= isset($this->request->get['page']) ? $this->request->get['page'] : 1;

			$this->data['token'] = $this->session->data['token'];

			$period_filter_data = [
			'filter_user_id' 		=> $filter_user_id,
			'filter_good' 			=> true,
			];

			$this->data['periods'] 		= [];

			$this->data['periods'][] 	= [
				'period' 			=> 'Сегодня',
				'filter_date_from' 	=> date('Y-m-d'),
				'filter_date_to' 	=> date('Y-m-d'),
				'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . '&filter_date_from=' . date('Y-m-d') . '&filter_date_to=' . date('Y-m-d')),
				'total' 			=> 'catalog/addasin/report_total&filter_date_from=' . date('Y-m-d') . '&filter_date_to=' . date('Y-m-d')
			];

			$this->data['periods'][] 	= [
				'period' 			=> 'Вчера',
				'filter_date_from' 	=> date('Y-m-d', strtotime('-1 day')),
				'filter_date_to' 	=> date('Y-m-d', strtotime('-1 day')),
				'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . '&filter_date_from=' . date('Y-m-d', strtotime('-1 day')) . '&filter_date_to=' . date('Y-m-d', strtotime('-1 day'))),
				'total' 			=> 'catalog/addasin/report_total&filter_date_from=' . date('Y-m-d', strtotime('-1 day')) . '&filter_date_to=' . date('Y-m-d', strtotime('-1 day'))
			];

			$this->data['periods'][] 	= [
				'period' 			=> 'Позавчера',
				'filter_date_from' 	=> date('Y-m-d', strtotime('-2 day')),
				'filter_date_to' 	=> date('Y-m-d', strtotime('-2 day')),
				'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . '&filter_date_from=' . date('Y-m-d', strtotime('-2 day')) . '&filter_date_to=' . date('Y-m-d', strtotime('-2 day'))),
				'total' 			=> 'catalog/addasin/report_total&filter_date_from=' . date('Y-m-d', strtotime('-2 day')) . '&filter_date_to=' . date('Y-m-d', strtotime('-2 day')),
			];

			for ($i = 3; $i <= 7; $i++) {
				$this->data['periods'][] 	= [
					'period' 			=> date('m-d', strtotime("-$i day")),
					'filter_date_from' 	=> date('Y-m-d', strtotime("-$i day")),
					'filter_date_to' 	=> date('Y-m-d', strtotime("-$i day")),
					'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . '&filter_date_from=' . date('Y-m-d', strtotime("-$i day")) . '&filter_date_to=' . date('Y-m-d', strtotime("-$i day"))),
					'total' 			=> 'catalog/addasin/report_total&filter_date_from=' . date('Y-m-d', strtotime("-$i day")) . '&filter_date_to=' . date('Y-m-d', strtotime("-$i day")),
				];
			}

			$this->data['periods'][] 	= [
				'period' 			=> 'С начала недели',
				'filter_date_from' 	=> date('Y-m-d', strtotime('last Monday')),
				'filter_date_to' 	=> date('Y-m-d'),
				'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . '&filter_date_from=' . date('Y-m-d', strtotime('last Monday')) . '&filter_date_to=' . date('Y-m-d')),
				'total' 			=> 'catalog/addasin/report_total&filter_date_from=' . date('Y-m-d', strtotime('last Monday')) . '&filter_date_to=' . date('Y-m-d'),
			];

			$this->data['periods'][] 	= [
				'period' 			=> 'За последний месяц',
				'filter_date_from' 	=> date('Y-m-d', strtotime('-1 Month')),
				'filter_date_to' 	=> date('Y-m-d'),
				'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . '&filter_date_from=' . date('Y-m-d', strtotime('-1 Month')) . '&filter_date_to=' . date('Y-m-d')),
				'total' 			=> 'catalog/addasin/report_total&filter_date_from=' . date('Y-m-d', strtotime('-1 Month')) . '&filter_date_to=' . date('Y-m-d'),
			];

			$this->data['periods'][] 	= [
				'period' 			=> 'Все',
				'filter_date_from' 	=> '',
				'filter_date_to' 	=> '',
				'href' 				=> $this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token']),
				'total' 			=> 'catalog/addasin/report_total',
			];

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_date_from'])) {
				$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
			}

			if (isset($this->request->get['filter_date_to'])) {
				$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
			}

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
			}

			$filter_data = array(
			'filter_date_from' 		=> $filter_date_from,
			'filter_date_to' 		=> $filter_date_to,
			'filter_user_id' 		=> $filter_user_id,
			'filter_good' 			=> true,
			'start' 				=> ($page - 1) * 100,
			'limit' 				=> 100
			);
			
			$this->data['products'] = [];
					
			$product_total 	= $this->model_report_product->getTotalProductsInASINQueue($filter_data);			
			$results 		= $this->model_report_product->getProductsInASINQueue($filter_data);

			foreach ($results as $result) {				
				$this->data['products'][] = [
				'asin'    				=> $result['asin'],
				'name'	 				=> !empty($result['name'])?$result['name']:false,
				'rnf_name'	 			=> !empty($result['rnf_name'])?$result['rnf_name']:false,
				'date_added'			=> date('Y-m-d', strtotime($result['date_added'])),
				'time_added'			=> date('H:i:s', strtotime($result['date_added'])),
				'product_id'			=> $result['product_id'],	

				'price'					=> $result['price'],
				'price_eur'				=> $this->currency->format($result['price'], 'EUR', 1),
				'price_national'		=> $this->currency->format($result['price'], $this->config->get('config_regional_currency')),

				'costprice'				=> $result['costprice'],
				'costprice_eur'			=> $this->currency->format($result['costprice'], 'EUR', 1),
				'costprice_national'	=> $this->currency->format($result['costprice'], $this->config->get('config_regional_currency')),

				'abs_profitability'     	=> ($result['price'] - $result['costprice']),
				'abs_profitability_eur'     => $this->currency->format($result['price'] - $result['costprice'], 'EUR', 1),
				'abs_profitability_national'=> $this->currency->format($result['price'] - $result['costprice'], $this->config->get('config_regional_currency')),

				'profitability'			=> $result['profitability'],

				'amazon_best_price'				=> $result['amazon_best_price'],
				'amazon_best_price_eur'			=> $this->currency->format($result['amazon_best_price'], 'EUR', 1),
				'amazon_best_price_national'	=> $this->currency->format($result['amazon_best_price'], $this->config->get('config_regional_currency')),


				'status'		=> ($result['product_id'] > 0)?$result['status']:false,
				'view'			=> ($result['product_id'] > 0)?(HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id']):false,
				'date_created'	=> ($result['product_id'] > 0)?date('Y-m-d', strtotime($result['date_created'])):false,		
				'image'			=> $result['image']?$this->model_tool_image->resize($result['image'], 50, 50):false,
				'category_id'	=> $result['category_id'],
				'category'		=> $result['category_id']?$this->model_catalog_category->getCategory($result['category_id']):false,
				'user'			=> $this->model_user_user->getRealUserNameById($result['user_id'])
				];
			}

			$this->data['users'] = [];
			$users = $this->model_report_product->getUsersFromAsinQueue();

			foreach ($users as $user_id){
				$this->data['users'][] = [
					'user' 		=> $this->model_user_user->getRealUserNameById($user_id),
					'user_id' 	=> $user_id
				];
			}


			$url = '';
			
			if (isset($this->request->get['filter_date_from'])) {
				$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
			}

			if (isset($this->request->get['filter_date_to'])) {
				$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
			}

			if (isset($this->request->get['filter_user_id'])) {
				$url .= '&filter_user_id=' . $this->request->get['filter_user_id'];
			}
			
			$pagination 				= new Pagination();			
			$this->data['pagination'] 	= $pagination->render([
				'total' 	=> 	$product_total,
				'page' 		=>	$page,
				'limit'		=> 	100,
				'text' 		=>	$this->language->get('text_pagination'),
				'url'		=> 	$this->url->link('catalog/addasin/report',  'token=' . $this->session->data['token'] . $url . '&page={page}'),
			]);

			$this->data['filter_date_from'] 		= $filter_date_from;
			$this->data['filter_date_to'] 			= $filter_date_to;		
			$this->data['filter_user_id'] 			= $filter_user_id;	

			$this->data['total_product_were_in_queue']			= formatLongNumber($this->model_report_product->getTotalProductsInASINQueue([]), true);
			$this->data['total_product_good_in_queue']			= formatLongNumber($this->model_report_product->getTotalProductsInASINQueue(['filter_good' => 1]), true);

			$this->data['total_product_have_offers']			= formatLongNumber($this->model_catalog_product->getTotalProductsHaveOffers(), true);
			$this->data['total_product_have_no_offers']			= formatLongNumber($this->model_catalog_product->getTotalProductsHaveNoOffers(), true);


			$this->template = 'catalog/addasinreport.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());

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
			$page 				= isset($this->request->get['page']) ? $this->request->get['page'] : 1;

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
			
			$this->data['breadcrumbs'] = [];
			
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
			
			$this->data['products'] = [];
					
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
<?php
	class ControllerModuleCdekIntegrator extends Controller {
		
		const VERSION = 1.0;
		
		private $api;
		private $error = array();
		private $time_execute;
		private $new_application;
		private $setting;
		private $limits = array(45, 60, 75);
		private $breadcrumbs_separator = ' » ';
		
		public function __construct($registry) {
			
			parent::__construct($registry);
			
			$this->init();
		}
		
		public function index() {
			
			$this->load->model('user/user');
			$this->load->model('sale/customer');
			$this->load->model('tool/image');
			$this->load->model('sale/reject_reason');
			$this->load->model('catalog/product');
			$this->load->model('localisation/country');
			$this->load->model('kp/geoip');
			$this->load->model('kp/csi');
			$this->load->model('sale/order');
			$this->load->model('kp/order');
			$this->load->model('sale/affiliate');
			$this->load->model('tool/simplecustom');
			$this->load->model('setting/setting');
			
			$this->load->model('module/cdek_license');
			$license = $this->model_module_cdek_license->chechLicense();
			if(!$license['status'])
			{
				$action_redirect = $this->url->link('module/cdek_integrator/showLicense', 'token=' . $this->session->data['token'], 'SSL');
				$this->response->redirect(html_entity_decode($action_redirect));
			}
			$installed = $this->model_module_cdek_license->chechInstalled('cdek_integrator','extension/module');
			if(!$installed['status'])
			{
				$this->response->redirect(html_entity_decode($installed['redirectlink']));
			}
			
			
			$this->load->language('module/cdek_integrator');
			$this->load->model('module/cdek_integrator');
			
			$this->data['heading_title'] = $this->language->get('heading_title_main');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['entry_layout'] = $this->language->get('entry_layout');
			$this->data['entry_position'] = $this->language->get('entry_position');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			
			$this->data['column_dispatch_number'] = $this->language->get('column_dispatch_number');
			$this->data['column_dispatch_total_orders'] = $this->language->get('column_dispatch_total_orders');
			$this->data['column_dispatch_date'] = $this->language->get('column_dispatch_date');
			
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_option'] = $this->language->get('button_option');
			$this->data['button_new_order'] = $this->language->get('button_new_order');
			
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
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
			);
			
			$title = $this->language->get('heading_title_bk_main');
			
			$new_orders = 0;
			
			if (!$this->new_application) {
				
				$data = array(
				'filter_dispatch' => true
				);
				
				if (!empty($this->setting['new_order_status_id'])) {
					$data['filter_order_status_id']	= $this->setting['new_order_status_id'];
				}
				
				if (!empty($this->setting['new_order'])) {
					$data['filter_new_order'] = $this->setting['new_order'];
				}
				
				if (!empty($this->setting['shipping_method'])) {
					$data['filter_shipping'] = $this->setting['shipping_method'];
				}
				
				if (!empty($this->setting['payment_method'])) {
					$data['filter_payment'] = $this->setting['payment_method'];
				}
				
				$new_orders = $this->model_module_cdek_integrator->getTotalOrders($data);
				
				if ($new_orders)  {
					$title .= ' (' . $new_orders . ')';
				}
				
				} else { // first load
				$this->data['attention'] = 'Для работы модуля необходимо выполнить настройку.';
			}
			
			$this->data['total'] = $new_orders;
			
			$this->document->setTitle($title);
			
			$this->data['dispatches'] = array();
			
			$data = array(
			'limit' => 50,
			'sort'	=> 'd.date',
			'order' => 'DESC'
			);
			
			$results = $this->model_module_cdek_integrator->getDispatchList($data);
			
			foreach ($results as $dispatch_info) {
				
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('module/cdek_integrator/dispatchView', 'token=' . $this->session->data['token'] . '&order_id=' . $dispatch_info['order_id'], 'SSL')
				);						
				
				
				$order = $this->model_sale_order->getOrder($dispatch_info['order_id']);
				
				$products = $this->model_sale_order->getOrderProductsList($order['order_id']);
				$order_products = array();			
				
				$_order_parties_tmp = array();
				
				foreach ($products as $product) {
					$option_data = array();
					
					$options = $this->model_sale_order->getOrderOptions($order['order_id'], $product['order_product_id']);
					
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
							);
							} else {
							$option_data[] = array(
							'name'  => $option['name'],
							'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
							'type'  => $option['type'],
							'href'  => $this->url->link('sale/order/download', 'token=' . $this->session->data['token'] . '&order_id=' . $order['order_id'] . '&order_option_id=' . $option['order_option_id'], 'SSL')
							);						
						}
					}
					
					if (mb_strlen($product['part_num']) > 1 && !in_array($product['part_num'], $_order_parties_tmp)){
						$_order_parties_tmp[]= $product['part_num'];
					}
					
					
					$order_products[] = array(
					'order_product_id' => $product['order_product_id'],
					'order_id'         => $order['order_id'],
					'product_id'       => $product['product_id'],
					'from_stock'       => $product['from_stock'],
					'from_bd_gift'     => $product['from_bd_gift'],
					'name'    	 	   => $product['name'],
					'part_num'		   => $product['part_num'],
					'lthumb'    	   => $this->model_tool_image->resize($product['image'], 150, 150),
					'thumb'    	 	   => $this->model_tool_image->resize($product['image'], 30, 30),
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],					
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order['currency_code'], $order['currency_value']),
					'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
					);
				}
				
				$_order_parties = array();
				foreach ($_order_parties_tmp as $_partie){
					
					$_order_parties []= array(
					'part_num' => $_partie,
					'href'     => $this->url->link('sale/order', 'filter_order_id=' . $_partie . '&token=' . $this->session->data['token'], 'SSL')				
					);
					
				}
				
				$this->data['dispatches'][] = array(
				'order_id'				=> $dispatch_info['order_id'],
				'order'					=> $order,
				'parties'         		=> $_order_parties,
				'dispatch_number'		=> $dispatch_info['dispatch_number'],
				'act_number'			=> $dispatch_info['act_number'],
				'date'					=> $this->formatDate($dispatch_info['date'], false),
				'city_name'				=> $dispatch_info['city_name'],
				'recipient_city_name'	=> $dispatch_info['recipient_city_name'],
				'status'				=> $dispatch_info['status_description'],
				'status_date'			=> $this->formatDate($dispatch_info['status_date'], true),
				'cost'					=> (float)$dispatch_info['delivery_cost'] ? $this->currency->format($dispatch_info['delivery_cost'], 'RUB', '1') : 0,
				'sync'					=> $this->url->link('module/cdek_integrator/dispatchSync', 'token=' . $this->session->data['token'] . '&target=list&order_id=' . $dispatch_info['order_id'], 'SSL'),
				'action'				=> $action
				);
				
			}
			
			$this->data['dispatch_list'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['order'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['option'] = $this->url->link('module/cdek_integrator/option', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->template = 'module/cdek_integrator/cdek_integrator.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function formatDate($timestamp, $time = TRUE, $correct_timestamp = TRUE) {
			
			if (!$correct_timestamp){
				$timestamp = strtotime($timestamp);
			}
			
			$send_time = date('d.m.Y', $timestamp);
			
			if (date('d.n.Y') == $send_time) {
				$date = $this->language->get('text_today');
				} elseif (date('d.n.Y', strtotime('-1 day')) == $send_time) {
				$date = $this->language->get('text_yesterday');
				} elseif (date('Y') == date('Y', $timestamp)) {
				$date = date('j', $timestamp) . ' ' . $this->getMonth(date('n', $timestamp));
				} else {
				$date = date('j', $timestamp) . ' ' . $this->getMonth(date('n', $timestamp)) . ' ' . date('Y', $timestamp);
			}
			
			if ($time) {
				$date .= ', ' . date('H:i', $timestamp);
			}
			
			return $date;
		}
		
		public function getMonth($number) {
			
			$month = '';
			
			switch ($number) {
				case 1:
				$month = 'января';
				break;
				case 2:
				$month = 'февраля';
				break;
				case 3:
				$month = 'марта';
				break;
				case 4:
				$month = 'апреля';
				break;
				case 5:
				$month = 'мая';
				break;
				case 6:
				$month = 'июня';
				break;
				case 7:
				$month = 'июля';
				break;
				case 8:
				$month = 'августа';
				break;
				case 9:
				$month = 'сентября';
				break;
				case 10:
				$month = 'октября';
				break;
				case 11:
				$month = 'ноября';
				break;
				case 12:
				$month = 'декабря';
				break;
			}
			
			return $month;
		}
		
		
		public function order() {
			
			$this->document->setTitle($this->language->get('heading_title_order'));
			
			$this->load->model('module/cdek_integrator');
			
			$this->orderList();
		}
		
		private function orderList() {
			
			if ($this->new_application) {
				$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
				} else {
				$filter_order_id = null;
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$filter_customer = $this->request->get['filter_customer'];
				} else {
				$filter_customer = null;
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
				} else {
				$filter_order_status_id = null;
			}
			
			if (!empty($this->setting['new_order_status_id']) && (!$filter_order_status_id || !in_array($filter_order_status_id, $this->setting['new_order_status_id']))) {
				$filter_order_status_id = $this->setting['new_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$filter_total = $this->request->get['filter_total'];
				} else {
				$filter_total = null;
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$filter_date_added = $this->request->get['filter_date_added'];
				} else {
				$filter_date_added = null;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'o.order_id';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit']) && in_array($this->request->get['limit'], $this->limits)) {
				$limit = $this->request->get['limit'];
				} else {
				$limit = reset($this->limits);
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_order'),
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['create'] = html_entity_decode($this->url->link('module/cdek_integrator/createOrder', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
			$this->data['cancel'] = $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['orders'] = array();
			
			$data = array(
			'filter_order_id'			=> $filter_order_id,
			'filter_customer'			=> $filter_customer,
			'filter_order_status_id'	=> $filter_order_status_id,
			'filter_total'				=> $filter_total,
			'filter_date_added'			=> $filter_date_added,
			'filter_new_order'			=> $this->setting['new_order'],
			'filter_dispatch'			=> TRUE,
			'sort'						=> $sort,
			'order'						=> $order,
			'start'						=> ($page - 1) * $limit,
			'limit'						=> $limit
			);
			
			if (!empty($this->setting['shipping_method'])) {
				$data['filter_shipping'] = $this->setting['shipping_method'];
			}
			
			if (!empty($this->setting['payment_method'])) {
				$data['filter_payment'] = $this->setting['payment_method'];
			}
			
			$results = $this->model_module_cdek_integrator->getOrders($data);
			
			$order_total = $this->model_module_cdek_integrator->getTotalOrders($data);
			
			if (!$order_total) {
				$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('button_create'),
				'href' => $this->url->link('module/cdek_integrator/createOrder', 'token=' . $this->session->data['token'] . '&orders[]=' . $result['order_id'] . $url, 'SSL')
				);
				
				$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
				);
				
				$this->data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'        => $action
				);
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title_order');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_missing'] = $this->language->get('text_missing');
			
			$this->data['column_order_id'] = $this->language->get('column_order_id');
			$this->data['column_customer'] = $this->language->get('column_customer');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['button_create'] = $this->language->get('button_create');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
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
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['sort_order'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
			$this->data['sort_customer'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
			$this->data['sort_total'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
			$this->data['sort_date_added'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			
			$this->data['limits'] = array();
			
			foreach ($this->limits as $item) {
				$this->data['limits'][$item] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . '&limit=' . $item . $url, 'SSL');
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_order_id'] = $filter_order_id;
			$this->data['filter_customer'] = $filter_customer;
			$this->data['filter_order_status_id'] = $filter_order_status_id;
			$this->data['filter_total'] = $filter_total;
			$this->data['filter_date_added'] = $filter_date_added;
			
			$this->load->model('localisation/order_status');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
			if (!empty($this->setting['new_order_status_id'])) {
				
				foreach ($this->data['order_statuses'] as $key => $order_status) {
					if (!in_array($order_status['order_status_id'], $this->setting['new_order_status_id'])) {
						unset($this->data['order_statuses'][$key]);
					}
				}
				
			}
			
			$this->data['sort'] = $sort;
			$this->data['order'] = strtolower($order);
			$this->data['limit'] = $limit;
			
			$this->template = 'module/cdek_integrator/order_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function createOrder() {
			
			if (empty($this->request->get['orders']) || !array_filter($this->request->get['orders'])) {
				$this->redirect($this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->load->model('module/cdek_integrator');
			$this->load->model('sale/order');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOrderFrom()) {
				
				$component = $this->api->loadComponent('orders');
				
				$component->setNumber($this->request->post['number']);
				
				$cdek_orders_post = $this->request->post['cdek_orders'];
				
				foreach ($cdek_orders_post as $order_id => $order_data) {
					
					$telephone = $order_data['recipient_telephone'];
					$telephone = trim($telephone);
					$telephone = preg_replace('/[^0-9+]/isu', '', $telephone);
					
					if (strpos($telephone, '8') !== 0) {
						$telephone = preg_replace('/^(?:\+7|7)/isu', '', $telephone);
						$telephone = '8' . $telephone;
					}
					
					$cdek_orders_post[$order_id]['recipient_telephone']	= $telephone;
					
					$this->request->post['cdek_orders'][$order_id]['currency'] = $this->setting['currency'];
					
					foreach ($order_data['package'] as $package_id => $package) {
						
						$additional_weight = $this->getPackingWeight($package['weight']);
						
						if ((float)$additional_weight['weight']) {
							
							if ($this->setting['packing_prefix'] == '+') {
								$cdek_orders_post[$order_id]['package'][$package_id]['weight'] += $additional_weight['weight'];
								} else {
								$cdek_orders_post[$order_id]['package'][$package_id]['weight'] -= (float)min($additional_weight['weight'], $package['weight']);
							}
							
							
						}
						
					}
					
				}
				
				$component->setOrders($cdek_orders_post);
				$response = $this->api->sendData($component);
				
				if ($this->api->error) {
					
					foreach ($this->api->error as $order_id => $order_errors) {
						
						foreach ($order_errors as $error_key => $error_message) {
							
							switch($error_key) {
								case 'ERR_ORDER_DUBL_EXISTS':
								$this->error['warning'][] = sprintf($this->language->get('error_order_dubl_exists'),$order_id);
								break;
								case 'ERR_NOT_FOUND_TARIFFTYPECODE':
								$this->error['cdek_orders'][$order_id]['tariff_id'] = $this->language->get('error_not_found_tarifftypecode');
								break;
								case 'ERR_INVALID_INTAKESERVICE_TOCITY':
								$this->error['cdek_orders'][$order_id]['add_service'] = $error_message . '!';
								break;
								case 'ERR_INVALID_INTAKESERVICE':
								$this->error['cdek_orders'][$order_id]['add_service'] = $error_message . '!';
								break;
								case 'ERR_INVALID_SERVICECODE':
								$this->error['cdek_orders'][$order_id]['add_service'] = $this->language->get('error_invalid_srvicecode');
								break;
								case 'ERR_SENDCITYCODE':
								$this->error['cdek_orders'][$order_id]['city_id'] = $this->language->get('error_sendcitycode');
								break;
								case 'ERR_DATABASE':
								$this->error['warning']['warning'] = $this->language->get('error_database');
								break;
								case 'ERR_AUTH':
								$this->error['warning']['warning'] = $this->language->get('error_auth');
								break;
								case 'ERR_CALLCOURIER_CITY':
								$this->error['cdek_orders'][$order_id]['courier']['city_id'] = $this->language->get('error_callcourier_city');
								break;
								case 'ERR_CALLCOURIER_DATETIME':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_callcourier_datetime');
								break;
								case 'ERR_CALLCOURIER_DATE_DUBL':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_callcourier_date_dubl');
								break;
								case 'ERR_CALLCOURIER_DATE_EXISTS':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_callcourier_date_exists');
								break;;
								case 'ERR_CALLCOURIER_TIME':
								$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_callcourier_time');
								break;
								case 'ERR_CALLCOURIER_TIMELUNCH':
								$this->error['cdek_orders'][$order_id]['courier']['lunch'] = $this->language->get('error_callcourier_timelunch');
								break;
								case 'ERR_CALLCOURIER_TIME_INTERVAL':
								$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_callcourier_time_interval');
								break;
								case 'ERR_CALL_DUBL':
								$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_call_dubl');
								break;
								case 'ERR_CASH_NO':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_cash_no'));
								break;
								case 'ERR_INVALID_ADDRESS_DELIVERY':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_invalid_address_delivery'));
								break;
								case 'ERR_INVALID_SIZE':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_invalid_size'));
								break;
								case 'ERR_PVZ_WEIGHT_LIMIT':
								$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $this->language->get('error_pvz_weigt_limit'));
								break;
								default:
								if ($error_message != '') {
									$this->error['warning'][] = sprintf($this->language->get('error_cdek_error'), $order_id, $error_message);
								}
							}
							
						}
					}
				}
				
				$date = '';
				$cdek_orders = array();
				
				if (isset($response->Order)) {
					
					foreach ($response->Order as $order) {
						
						$attributes = $order->attributes();
						
						if (isset($attributes->DispatchNumber)) {
							
							$order_id = (int)$attributes->Number;
							
							if (array_key_exists($order_id, $this->request->post['cdek_orders'])) {
								
								$order_info = $this->request->post['cdek_orders'][$order_id];
								
								$order_info += array(
								'dispatch_number'	=> (string)$attributes->DispatchNumber,
								);
								
								$cdek_orders[$order_id] = $order_info;
								
							}
							
							
						}
						
					}
					
					} elseif(!$this->api->error) {
					$this->error['warning'][] = 'Не удалось получить ответ от сервера СДЭК.';
				}
				
				if (count($cdek_orders)) {
					
					$count = count($cdek_orders);
					$all = ($count == count($this->request->post['cdek_orders']));
					$orders = $all ? $cdek_orders : array_intersect_key($cdek_orders, $this->request->post['cdek_orders']);
					
					if (!$date) $date = time();
					
					$default_timezone = date_default_timezone_get();
					date_default_timezone_set('UTC');
					
					$defoult_time = time();
					
					date_default_timezone_set($default_timezone);
					
					foreach ($orders as $order_id => $order_data) {
						
						if (!isset($order_data['status_id'])) {
							
							$orders[$order_id]['status_id'] = 1;
							
							$status_history = array();
							$status_history[] = array(
							'date'			=> $defoult_time,
							'status_id'		=> $orders[$order_id]['status_id'],
							'description'	=> 'Создан',
							'city_code'		=> $this->setting['city_id'],
							'city_name'		=> $this->setting['city_name']
							);
							
							$orders[$order_id]['status_history'] = $status_history;
						}
						
					}
					
					$data = array(
					'number'	=> $this->request->post['number'],
					'date'		=> $date,
					'orders'	=> $orders
					);
					
					$this->model_module_cdek_integrator->addDispatch($data);
					
					$this->session->data['success'] = 'Отгружен' . $this->declination($count, array('', 'о', 'о')) . ' ' . $count . ' заказ' . $this->declination($count, array('', 'а', 'ов')) . '!';
					
					if ($all) {
						
						$url = '';
						
						if (isset($this->request->get['filter_order_id'])) {
							$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
						}
						
						if (isset($this->request->get['filter_customer'])) {
							$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
						}
						
						if (isset($this->request->get['filter_order_status_id'])) {
							$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
						}
						
						if (isset($this->request->get['filter_total'])) {
							$url .= '&filter_total=' . $this->request->get['filter_total'];
						}
						
						if (isset($this->request->get['filter_date_added'])) {
							$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
						}
						
						if (isset($this->request->get['filter_date_modified'])) {
							$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
						
						if (isset($this->request->get['limit'])) {
							$url .= '&limit=' . $this->request->get['limit'];
						}
						
						$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}
					
				}
				
			}
			
			$this->document->setTitle($this->language->get('heading_title_new_order'));
			
			$this->data['heading_title'] = $this->language->get('heading_title_new_order');
			
			$this->data['text_order_n'] = $this->language->get('text_order_n');
			$this->data['text_city'] = $this->language->get('text_city');
			$this->data['text_order_date'] = $this->language->get('text_order_date');
			$this->data['text_order_count_items'] = $this->language->get('text_order_count_items');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_order_id'] = $this->language->get('text_order_id');
			$this->data['text_order_total'] = $this->language->get('text_order_total');
			$this->data['text_city'] = $this->language->get('text_city');
			$this->data['text_customer_shipping_method'] = $this->language->get('text_customer_shipping_method');
			$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$this->data['text_customer_shipping_address'] = $this->language->get('text_customer_shipping_address');
			$this->data['text_courier_address'] = $this->language->get('text_courier_address');
			$this->data['text_courier'] = $this->language->get('text_courier');
			$this->data['text_from'] = $this->language->get('text_from');
			$this->data['text_to'] = $this->language->get('text_to');
			$this->data['text_short_length'] = $this->language->get('text_short_length');
			$this->data['text_short_width'] = $this->language->get('text_short_width');
			$this->data['text_short_height'] = $this->language->get('text_short_height');
			$this->data['text_attention'] = $this->language->get('text_attention');
			$this->data['text_courier_day'] = $this->language->get('text_courier_day');
			$this->data['text_courier_hour_range'] = $this->language->get('text_courier_hour_range');
			$this->data['text_title_schedule'] = $this->language->get('text_title_schedule');
			$this->data['text_title_orders'] = $this->language->get('text_title_orders');
			$this->data['text_help_shedule'] = $this->language->get('text_help_shedule');
			$this->data['text_help_shedule_detail'] = $this->language->get('text_help_shedule_detail');
			$this->data['text_package_n'] = $this->language->get('text_package_n');
			$this->data['text_user_comment'] = $this->language->get('text_user_comment');
			$this->data['text_none'] = $this->language->get('text_none');
			
			$this->data['entry_tariff'] = $this->language->get('entry_tariff');
			$this->data['entry_delivery_recipient_cost'] = $this->language->get('entry_delivery_recipient_cost');
			$this->data['entry_seller_name'] = $this->language->get('entry_seller_name');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			$this->data['entry_recipient_name'] = $this->language->get('entry_recipient_name');
			$this->data['entry_recipient_telephone'] = $this->language->get('entry_recipient_telephone');
			$this->data['entry_recipient_email'] = $this->language->get('entry_recipient_email');
			$this->data['entry_recipient_city'] = $this->language->get('entry_recipient_city');
			$this->data['entry_street'] = $this->language->get('entry_street');
			$this->data['entry_house'] = $this->language->get('entry_house');
			$this->data['entry_flat'] = $this->language->get('entry_flat');
			$this->data['entry_pvz'] = $this->language->get('entry_pvz');
			$this->data['entry_brcode'] = $this->language->get('entry_brcode');
			$this->data['entry_pack'] = $this->language->get('entry_pack');
			$this->data['entry_package'] = $this->language->get('entry_package');
			$this->data['entry_order_weight'] = $this->language->get('entry_order_weight');
			$this->data['entry_courier_call'] = $this->language->get('entry_courier_call');
			$this->data['entry_courier_date'] = $this->language->get('entry_courier_date');
			$this->data['entry_courier_time'] = $this->language->get('entry_courier_time');
			$this->data['entry_courier_lunch'] = $this->language->get('entry_courier_lunch');
			$this->data['entry_courier_send_phone'] = $this->language->get('entry_courier_send_phone');
			$this->data['entry_courier_sender_name'] = $this->language->get('entry_courier_sender_name');
			$this->data['entry_add_service'] = $this->language->get('entry_add_service');
			$this->data['entry_attempt_new_address'] = $this->language->get('entry_attempt_new_address');
			$this->data['entry_attempt_recipient_name'] = $this->language->get('entry_attempt_recipient_name');
			$this->data['entry_attempt_phone'] = $this->language->get('entry_attempt_phone');
			$this->data['entry_cod'] = $this->language->get('entry_cod');
			$this->data['entry_currency'] = $this->language->get('entry_currency');
			$this->data['entry_currency_cod'] = $this->language->get('entry_currency_cod');
			
			$this->data['column_title'] = $this->language->get('column_title');
			$this->data['column_weight'] = $this->language->get('column_weight');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_payment'] = $this->language->get('column_payment');
			$this->data['column_amount'] = $this->language->get('column_amount');
			$this->data['column_cost'] = $this->language->get('column_cost');
			$this->data['column_date'] = $this->language->get('column_date');
			$this->data['column_time'] = $this->language->get('column_time');
			$this->data['column_additional'] = $this->language->get('column_additional');
			
			$this->data['tab_data'] = $this->language->get('tab_data');
			$this->data['tab_recipient'] = $this->language->get('tab_recipient');
			$this->data['tab_package'] = $this->language->get('tab_package');
			$this->data['tab_schedule'] = $this->language->get('tab_schedule');
			$this->data['tab_courier'] = $this->language->get('tab_courier');
			$this->data['tab_additional'] = $this->language->get('tab_additional');
			
			$this->data['button_send'] = $this->language->get('button_send');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_delete'] = $this->language->get('button_delete');
			$this->data['button_add_attempt'] = $this->language->get('button_add_attempt');
			
			$this->data['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = array();
			}
			
			$this->data['error'] = $this->error;
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_order'),
			'href'      => $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_new_order'),
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->data['currency_list'] = $this->getInfo()->getCurrencyList();
			
			$url = '';
			
			foreach ($this->request->get['orders'] as $order_id) {
				$url .= '&orders[]=' . $order_id;
			}
			
			$this->data['action'] = $this->url->link('module/cdek_integrator/createOrder', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['cancel'] = $this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['city_default'] = $this->setting['city_default'];
			
			if ($this->data['city_default']) {
				
				$this->data['city_id'] = $this->setting['city_id'];
				$this->data['city_name'] = $this->setting['city_name'];
				
			}
			
			$this->data['cdek_orders'] = array();
			
			foreach ($this->request->get['orders'] as $order_id) 
			{
				$order_to_sdek = $this->model_module_cdek_integrator->getOrderToSdek($order_id);
				
				$order_info = $this->model_sale_order->getOrder($order_id);
				
				if ($order_info && !$this->model_module_cdek_integrator->orderExists($order_id)) {
					
					$shipping_total = 0;
					
					$totals = $this->model_sale_order->getOrderTotals($order_id);
					
					$post_data = !empty($this->request->post['cdek_orders'][$order_id]) ? $this->request->post['cdek_orders'][$order_id] : array();
					
					if (isset($post_data['currency'])) {
						$currency = $post_data['currency'];
						} elseif (in_array($order_info['currency_code'], $this->data['currency_list'])) {
						$currency = $order_info['currency_code'];
						} else {
						$currency = $this->setting['currency'];
					}
					
					$data = array(
					'cod'					=> isset($post_data['cod']) ? $post_data['cod'] : $this->setting['cod'],
					'currency_cod'			=> isset($post_data['currency_cod']) ? $post_data['currency_cod'] : $this->setting['currency_agreement'],
					'currency'				=> $currency,
					'city_id'				=> $this->setting['city_id'],
					'city_name'				=> $this->setting['city_name'],
					'recipient_name'		=> isset($post_data['recipient_name']) ? $post_data['recipient_name'] : $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'],
					'recipient_telephone'	=> isset($post_data['recipient_telephone']) ? $post_data['recipient_telephone'] : $order_info['telephone'],
					'recipient_email'		=> isset($post_data['recipient_email']) ? $post_data['recipient_email'] : $order_info['email'],
					'shipping_address'		=> $this->fomatAddress($order_info),
					'total'					=> $this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
					'packages'				=> array(),
					'totals'				=> $totals
					);
					
					$telephone = $data['recipient_telephone'];
					$telephone = trim($telephone);
					$telephone = preg_replace('/[^0-9+]/isu', '', $telephone);
					
					if (strpos($telephone, '8') !== 0) {
						$telephone = preg_replace('/^(?:\+7|7)/isu', '', $telephone);
						$telephone = '8' . $telephone;
					}
					
					$data['recipient_telephone'] = $telephone;
					
					$packages = array(
					1 => $this->model_module_cdek_integrator->getOrderProducts($order_id)
					);
					
					foreach ($packages as $package_id => $products) {
						
						$package_post = isset($post_data['package'][$package_id]) ? $post_data['package'][$package_id] : array();
						
						$data['packages'][$package_id] = array(
						'item'				=> array(),
						'weight'			=> 0
						);
						
						foreach (array('brcode', 'pack', 'size_a', 'size_b', 'size_c') as $item) {
							$data['packages'][$package_id][$item] = isset($package_post[$item]) ? $package_post[$item] : '';
						}
						
						if (!empty($this->setting['replace_items'])) {
							
							$total_weight = 0;
							
							foreach ($products as $product_row => $order_product) {
								
								if (isset($order_product['weight'])) {
									
									if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
										$weight = $this->weight->convert($order_product['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
										} else {
										$weight = $order_product['weight'];
									}
									
									} else {
									$weight = 0;
								}
								
								$product_options = $this->model_module_cdek_integrator->getOrderProductOptions($order_product['order_product_id']);
								
								foreach ($product_options as $product_option) {
									
									if (!empty($product_option['weight'])) {
										
										if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
											$option_weight = $this->weight->convert($product_option['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
											} else {
											$option_weight = $product_option['weight'];
										}
										
										if ($product_option['weight_prefix'] == '+') {
											$weight += $option_weight;
											} else {
											$weight -= $option_weight;
										}
										
										if ($weight < 0) {
											$weight = 0;
										}
										
									}
									
								}
								
								$total_weight += $weight * $order_product['quantity'];
							}
							
							$products = array();
							$products[] = array(
							'order_product_id'	=> 1,
							'product_id'		=> 1,
							'name'				=> $this->setting['replace_item_name'],
							'model'				=> '',
							'weight'			=> $total_weight,
							'weight_class_id'	=> $this->setting['weight_class_id'],
							'option'			=> array(),
							'quantity'			=> ($this->setting['replace_item_amount'] ? $this->setting['replace_item_amount'] : 1),
							'price'				=> $this->setting['replace_item_cost'],
							'payment'			=> $this->setting['replace_item_payment'],
							'total'				=> '',
							'tax'				=> 0
							);
							
						}
						
						
						
						$total_weight = 0;
						
						foreach ($products as $product_row => $order_product) {
							
							$package_item_post = isset($package_post['item'][$product_row]) ? $package_post['item'][$product_row] : array();
							
							if (isset($order_product['weight'])) {
								
								if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
									$weight = $this->weight->convert($order_product['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
									} else {
									$weight = $order_product['weight'];
								}
								
								} else {
								$weight = 0;
							}
							
							$product_options = $this->model_module_cdek_integrator->getOrderProductOptions($order_product['order_product_id']);
							
							$product_name = $order_product['name'];
							
							$option_values = array();
							
							foreach ($product_options as $product_option) {
								
								if (!empty($product_option['weight'])) {
									
									if ($order_product['weight_class_id'] != $this->setting['weight_class_id']) {
										$option_weight = $this->weight->convert($product_option['weight'], $order_product['weight_class_id'], $this->setting['weight_class_id']);
										} else {
										$option_weight = $product_option['weight'];
									}
									
									if ($product_option['weight_prefix'] == '+') {
										$weight += $option_weight;
										} else {
										$weight -= $option_weight;
									}
									
									if ($weight < 0) {
										$weight = 0;
									}
									
								}
								
								if ($product_option['type'] != 'file') {
									$option_values[] = $product_option['name'] . ': ' . $product_option['value'];
								}
								
							}
							
							if (!empty($option_values)) {
								$product_name .= '(' . implode(', ', $option_values) . ')';
							}
							
							$total_weight += $weight * $order_product['quantity'];
							
							$item_data = array(
							'order_product_id'	=> $order_product['order_product_id'],
							'product_id'		=> $order_product['product_id'],
							'name'				=> $product_name,
							'weight'			=> isset($package_item_post['weight']) ? $package_item_post['weight'] : $weight,
							'option'			=> $this->model_sale_order->getOrderOptions($order_id, $order_product['order_product_id']),
							'quantity'			=> isset($package_item_post['amount']) ? $package_item_post['amount'] : $order_product['quantity'],
							'price'				=> isset($package_item_post['cost']) ? $package_item_post['cost'] : $order_product['price'],
							'payment'			=> isset($package_item_post['payment']) ? $package_item_post['payment'] : (isset($order_product['payment']) ? $order_product['payment'] : $order_product['price']),
							'total'				=> $order_product['total'],
							'tax'				=> $order_product['tax']
							);
							
							$item_data['total'] = $this->currency->format(((int)$item_data * $item_data['price']), $order_info['currency_code'], $order_info['currency_value']) . ' / ' . $this->currency->format(((int)$item_data * $item_data['payment']), $order_info['currency_code'], $order_info['currency_value']);
							
							$data['packages'][$package_id]['item'][] = $item_data;
							
						}
						
						if (isset($package_post['weight'])) {
							$data['packages'][$package_id]['weight'] = $package_post['weight'];
							} else {
							$data['packages'][$package_id]['weight'] = $total_weight;
						}
						
						$data['packages'][$package_id]['additional_weight'] = $this->getPackingWeight($data['packages'][$package_id]['weight']);
					}
					
					if (isset($post_data['courier'])) {
						$data['courier'] = $post_data['courier'];
						} else {
						$data['courier'] = array(
						'city_id'	=> $this->setting['city_id'],
						'city_name'	=> $this->setting['city_name'],
						'send_phone'	=> $this->config->get('config_telephone'),
						'sender_name'	=> $this->config->get('config_owner')
						);
					}
					
					if (!empty($post_data)) {
						
						foreach (array('city_id', 'city_name', 'tariff_id', 'mode_id', 'recipient_city_id', 'recipient_city_name', 'delivery_recipient_cost', 'seller_name', 'cdek_comment', 'package', 'schedule', 'add_service') as $item) {
							
							if (isset($post_data[$item])) {
								$data[$item] = $post_data[$item];
							}
							
						}
						
						foreach (array('street', 'house', 'flat', 'pvz_code') as $item) {
							
							if (isset($post_data['address'][$item])) {
								$data['address'][$item] = $post_data['address'][$item];
							}
							
						}
						
						if (!empty($data['recipient_city_id'])) {
							
							$pvz_list = $this->getPVZ($data['recipient_city_id']);
							
							if (isset($pvz_list['List'])) {
								$data['pvz_list'] = $pvz_list['List'];
							}
							
						}
						
						} elseif ($order_info['shipping_city'] != '') {
						
						if (!empty($this->setting['delivery_recipient_cost'])) {
							$data['delivery_recipient_cost'] = $this->setting['delivery_recipient_cost'];
							} elseif ($shipping_total) {
							$data['delivery_recipient_cost'] = $shipping_total;
						}
						
						if (!empty($this->setting['seller_name'])) {
							$data['seller_name'] = $this->setting['seller_name'];
						}
						
						if (!empty($this->setting['add_service'])) {
							$data['add_service'] = array_flip($this->setting['add_service']);
						}
						
						$city_info = $this->getCity($order_info['shipping_city'], $order_info['shipping_country_id'], $order_info['shipping_zone_id']);
						
						if (isset($city_info['id'])) {
							
							$data += array(
							'recipient_city_id'		=> $city_info['id'],
							'recipient_city_name'	=> $city_info['name']
							);
							
							$pvz_list = $this->getPVZ($city_info['id']);
							
							if (!empty($pvz_list['List'])) {
								$data['pvz_list'] = $pvz_list['List'];
							}
							
						}
						
						if($order_to_sdek['cityId'])
						{
							$city_info = $this->model_module_cdek_integrator->getCityById($order_to_sdek['cityId']);
							$data += array(
							'recipient_city_id'		=> $order_to_sdek['cityId'],
							'recipient_city_name'	=> $city_info['name']
							);
							
							$pvz_list = $this->getPVZ($order_to_sdek['cityId']);
							
							if (!empty($pvz_list['List'])) {
								$data['pvz_list'] = $pvz_list['List'];
							}
						}
						
						if (isset($order_info['shipping_code'])) {
							
							$parts = explode('.', $order_info['shipping_code']);
							
							if ($parts[0] == 'cdek' && !empty($parts[1])) {
								
								$tariff_parts = explode('_', $parts[1]);
								
								if (count($tariff_parts) == 3) {
									
									list(,$tariff_id, $pvz_code) = $tariff_parts;
									
									$tariff_info = $this->getInfo()->getTariffInfo($tariff_id);
									if($order_to_sdek['pvz_code'])
									{
										$pvz_code=$order_to_sdek['pvz_code'];
									}
									if ($tariff_info) {
										
										$data += array(
										'tariff_id' => $tariff_id,
										'mode_id'	=> $tariff_info['mode_id'],
										'address'	=> array(
										'pvz_code'	=> $pvz_code
										)
										);
										
									}
									
								}
								
							}
							
						}
						
					}
					
					if (!empty($data['pvz_list']) && !empty($data['address']['pvz_code'])) {
						
						foreach ($pvz_list['List'] as $pvz_info) {
							
							if ($pvz_info['Code'] == $data['address']['pvz_code']) {
								
								$data['pvz_info'] = $pvz_info;
								
								break;								
							}
							
						}
						
					}
					
					$this->data['cdek_orders'][] = $data + $order_info;
				}
				
			}
			
			$this->data['tariff_list'] = $this->getInfo()->getTariffList();
			$this->data['add_cervices'] = $this->getInfo()->getAddServices();
			
			$this->data['date'] = $this->getDateExecuted('Y-m-d');
			
			$this->data['number'] = uniqid();
			
			$this->data['login'] = $this->setting['account'];
			
			$default_timezone = date_default_timezone_get();
			date_default_timezone_set('UTC');
			
			$this->data['pass'] = md5(date('Y-m-d', time() + 10800) . '&' . $this->setting['secure_password']);
			
			date_default_timezone_set($default_timezone);
			
			$this->data['total'] = count($this->data['cdek_orders']);
			
			if (!$this->data['total']) {
				$this->redirect($this->url->link('module/cdek_integrator/order', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->template = 'module/cdek_integrator/order_form.tpl';
			
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function option() {
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOption()) {
				
				$this->load->model('setting/setting');
				$this->model_setting_setting->editSetting('cdek_integrator_setting', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				if (isset($this->request->get['redirect'])) {
					$redirect = $this->url->link('module/cdek_integrator/option', 'token=' . $this->session->data['token'], 'SSL');
					} else {
					$redirect = $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL');
				}
				
				$this->redirect($redirect);
			}
			
			$this->load->model('localisation/order_status');
			$this->load->model('localisation/weight_class');
			$this->load->model('localisation/length_class');
			
			$this->document->setTitle($this->language->get('heading_title_option'));
			
			$this->data['heading_title'] = $this->language->get('heading_title_option');
			
			$this->data['text_city_from'] = $this->language->get('text_city_from');
			$this->data['text_tokens'] = $this->language->get('text_tokens');
			$this->data['text_token_dispatch_number'] = $this->language->get('text_token_dispatch_number');
			$this->data['text_token_order_id'] = $this->language->get('text_token_order_id');
			$this->data['text_help_status_rule'] = $this->language->get('text_help_status_rule');
			$this->data['text_none'] = $this->language->get('text_none');
			
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_copy_count'] = $this->language->get('entry_copy_count');
			$this->data['entry_weight_class_id'] = $this->language->get('entry_weight_class_id');
			$this->data['entry_length_class_id'] = $this->language->get('entry_length_class_id');
			$this->data['entry_account'] = $this->language->get('entry_account');
			$this->data['entry_secure_password'] = $this->language->get('entry_secure_password');
			$this->data['entry_new_order_status_id'] = $this->language->get('entry_new_order_status_id');
			$this->data['entry_shipping_methods'] = $this->language->get('entry_shipping_methods');
			$this->data['entry_payment_methods'] = $this->language->get('entry_payment_methods');
			$this->data['entry_new_order'] = $this->language->get('entry_new_order');
			$this->data['entry_city_default'] = $this->language->get('entry_city_default');
			$this->data['entry_packing_min_weight'] = $this->language->get('entry_packing_min_weight');
			$this->data['entry_packing_additional_weight'] = $this->language->get('entry_packing_additional_weight');
			$this->data['entry_cod_default'] = $this->language->get('entry_cod_default');
			$this->data['entry_delivery_recipient_cost'] = $this->language->get('entry_delivery_recipient_cost');
			$this->data['entry_seller_name'] = $this->language->get('entry_seller_name');
			$this->data['entry_add_service'] = $this->language->get('entry_add_service');
			$this->data['entry_replace_items'] = $this->language->get('entry_replace_items');
			$this->data['entry_replace_item_name'] = $this->language->get('entry_replace_item_name');
			$this->data['entry_replace_item_cost'] = $this->language->get('entry_replace_item_cost');
			$this->data['entry_replace_item_payment'] = $this->language->get('entry_replace_item_payment');
			$this->data['entry_replace_item_amount'] = $this->language->get('entry_replace_item_amount');
			$this->data['entry_use_cron'] = $this->language->get('entry_use_cron');
			$this->data['entry_currency'] = $this->language->get('entry_currency');
			$this->data['entry_currency_agreement'] = $this->language->get('entry_currency_agreement');
			
			$this->data['column_token'] = $this->language->get('column_token');
			$this->data['column_value'] = $this->language->get('column_value');
			$this->data['column_cdek_status'] = $this->language->get('column_cdek_status');
			$this->data['column_new_status'] = $this->language->get('column_new_status');
			$this->data['column_notify'] = $this->language->get('column_notify');
			$this->data['column_comment'] = $this->language->get('column_comment');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['tab_data'] = $this->language->get('tab_data');
			$this->data['tab_auth'] = $this->language->get('tab_auth');
			$this->data['tab_order'] = $this->language->get('tab_order');
			$this->data['tab_package'] = $this->language->get('tab_additional_weight');
			$this->data['tab_status'] = $this->language->get('tab_status');
			$this->data['tab_currency'] = $this->language->get('tab_currency');
			$this->data['tab_additional'] = $this->language->get('tab_additional');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_apply'] = $this->language->get('button_apply');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));
			
			$this->data['additional_weight_mode'] = array(
			'fixed'			=> $this->language->get('text_weight_fixed'),
			'all_percent'	=> $this->language->get('text_weight_all')
			);
			
			$this->data['currency_list'] = $this->getInfo()->getCurrencyList();
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['error'] = $this->error;
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_option'),
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
			);
			
			if (isset($this->request->post['cdek_integrator_setting'])) {
				$this->data['setting'] = $this->request->post['cdek_integrator_setting'];
				} else {
				$this->data['setting'] = $this->setting;
			}
			
			if (!isset($this->data['setting']['cod'])) {
				$this->data['setting']['cod'] = 1;
			}
			
			$this->data['action'] = $this->url->link('module/cdek_integrator/option', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['cancel'] = $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			$this->data['cdek_statuses'] = $this->getInfo()->getOrderStatuses();
			
			$this->data['show_filter'] = version_compare(VERSION, '1.5.1.3', '>') || (strpos(VERSION, '1.5.1') !== FALSE);
			
			$this->data['payment_methods'] = $this->getPaymentMethods();
			$this->data['shipping_methods'] = $this->getShippingMethods();
			$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
			$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
			$this->data['add_cervices'] = $this->getInfo()->getAddServices();
			
			$this->template = 'module/cdek_integrator/option.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function dispatch() {
			
			if ($this->new_application) {
				$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->document->setTitle($this->language->get('heading_title_dispatch'));
			
			$this->load->model('module/cdek_integrator');
			
			$this->dispatchList();
			
		}
		
		private function dispatchList() {
			
			if ($this->new_application) {
				$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
				} else {
				$filter_order_id = NULL;
			}
			
			if (isset($this->request->get['filter_dispatch_number'])) {
				$filter_dispatch_number = $this->request->get['filter_dispatch_number'];
				} else {
				$filter_dispatch_number = NULL;
			}
			
			if (isset($this->request->get['filter_act_number'])) {
				$filter_act_number = $this->request->get['filter_act_number'];
				} else {
				$filter_act_number = NULL;
			}
			
			if (isset($this->request->get['filter_date'])) {
				$filter_date = $this->request->get['filter_date'];
				} else {
				$filter_date = NULL;
			}
			
			if (isset($this->request->get['filter_city_from'])) {
				$filter_city_from = $this->request->get['filter_city_from'];
				} else {
				$filter_city_from = NULL;
			}
			
			if (isset($this->request->get['filter_city_to'])) {
				$filter_city_to = $this->request->get['filter_city_to'];
				} else {
				$filter_city_to = NULL;
			}
			
			if (isset($this->request->get['filter_status_id'])) {
				$filter_status_id = $this->request->get['filter_status_id'];
				} else {
				$filter_status_id = NULL;
			}
			
			if (isset($this->request->get['filter_total'])) {
				$filter_total = $this->request->get['filter_total'];
				} else {
				$filter_total = NULL;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'd.date';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit']) && in_array($this->request->get['limit'], $this->limits)) {
				$limit = $this->request->get['limit'];
				} else {
				$limit = reset($this->limits);
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_dispatch_number'])) {
				$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
			}
			
			if (isset($this->request->get['filter_act_number'])) {
				$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
			}
			
			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}
			
			if (isset($this->request->get['filter_city_from'])) {
				$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
			}
			
			if (isset($this->request->get['filter_city_to'])) {
				$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
			}
			
			if (isset($this->request->get['filter_status_id'])) {
				$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_bk_main'),
			'href'      => $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_dispatch'),
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
			);
			
			$this->data['cancel'] = $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['dispatches'] = array();
			
			$data = array(
			'filter_order_id'			=> $filter_order_id,
			'filter_dispatch_number'	=> $filter_dispatch_number,
			'filter_act_number'			=> $filter_act_number,
			'filter_date'				=> $filter_date,
			'filter_city_from'			=> $filter_city_from,
			'filter_city_to'			=> $filter_city_to,
			'filter_status_id'			=> $filter_status_id,
			'filter_total'				=> $filter_total,
			'sort'						=> $sort,
			'order'						=> $order,
			'start'						=> ($page - 1) * $limit,
			'limit'						=> $limit
			);
			
			$results = $this->model_module_cdek_integrator->getDispatchList($data);
			
			$order_total = $this->model_module_cdek_integrator->getDispatchTotal($data);
			
			foreach ($results as $dispatch_info) {
				
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('module/cdek_integrator/dispatchView', 'token=' . $this->session->data['token'] . '&order_id=' . $dispatch_info['order_id'] . $url, 'SSL')
				);
				
				$action[] = array(
				'text'	=> $this->language->get('text_sync'),
				'href'	=> $this->url->link('module/cdek_integrator/dispatchSync', 'token=' . $this->session->data['token'] . '&target=list&order_id=' . $dispatch_info['order_id'] . $url, 'SSL'),
				'class'	=> 'js sync'
				);
				
				if ($dispatch_info['status_id'] == 1) {
					
					$action[] = array(
					'text' => $this->language->get('text_delete'),
					'href' => $this->url->link('module/cdek_integrator/dispatchDelete', 'token=' . $this->session->data['token'] . '&order_id=' . $dispatch_info['order_id'] . $url, 'SSL'),
					'class'	=> 'delete'
					);
					
				}
				
				if (is_null($dispatch_info['act_number'])) {
					$dispatch_info['act_number'] = FALSE;
				}
				
				$this->data['dispatches'][] = array(
				'order_id'				=> $dispatch_info['order_id'],
				'dispatch_number'		=> $dispatch_info['dispatch_number'],
				'act_number'			=> $dispatch_info['act_number'],
				'date'					=> $this->formatDate($dispatch_info['date'], false),
				'city_name'				=> $dispatch_info['city_name'],
				'recipient_city_name'	=> $dispatch_info['recipient_city_name'],
				'status'				=> $dispatch_info['status_description'],
				'status_date'			=> $this->formatDate($dispatch_info['status_date']),
				'cost'					=> (float)$dispatch_info['delivery_cost'] ? $this->currency->format($dispatch_info['delivery_cost'], 'RUB', '1') : '',
				'action'				=> $action
				);
				
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title_dispatch');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_missing'] = $this->language->get('text_missing');
			
			/*$this->data['column_order_id'] = $this->language->get('column_order_id');
			$this->data['column_customer'] = $this->language->get('column_customer');*/
			$this->data['column_dispatch_number'] = $this->language->get('column_dispatch_number');
			$this->data['column_dispatch_total_orders'] = $this->language->get('column_dispatch_total_orders');
			$this->data['column_dispatch_date'] = $this->language->get('column_dispatch_date');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_filter'] = $this->language->get('button_filter');
			
			if (isset($this->session->data['warning'])) {
				$this->data['error_warning'] = $this->session->data['warning'];
				
				unset($this->session->data['warning']);
				} elseif (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_dispatch_number'])) {
				$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
			}
			
			if (isset($this->request->get['filter_act_number'])) {
				$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
			}
			
			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}
			
			if (isset($this->request->get['filter_city_from'])) {
				$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
			}
			
			if (isset($this->request->get['filter_city_to'])) {
				$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
			}
			
			if (isset($this->request->get['filter_status_id'])) {
				$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['sort_order_id'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
			$this->data['sort_dispatch_number'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=d.dispatch_number' . $url, 'SSL');
			$this->data['sort_act_number'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=o.act_number' . $url, 'SSL');
			$this->data['sort_date'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=d.date' . $url, 'SSL');
			$this->data['sort_city_from'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=o.city_name' . $url, 'SSL');
			$this->data['sort_city_to'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=o.recipient_city_name' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=o.status_id' . $url, 'SSL');
			$this->data['sort_total'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&sort=o.delivery_cost' . $url, 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_dispatch_number'])) {
				$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
			}
			
			if (isset($this->request->get['filter_act_number'])) {
				$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
			}
			
			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}
			
			if (isset($this->request->get['filter_city_from'])) {
				$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
			}
			
			if (isset($this->request->get['filter_city_to'])) {
				$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
			}
			
			if (isset($this->request->get['filter_status_id'])) {
				$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			
			foreach ($this->limits as $item) {
				$this->data['limits'][$item] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . '&limit=' . $item . $url, 'SSL');
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_dispatch_number'])) {
				$url .= '&filter_dispatch_number=' . $this->request->get['filter_dispatch_number'];
			}
			
			if (isset($this->request->get['filter_act_number'])) {
				$url .= '&filter_act_number=' . $this->request->get['filter_act_number'];
			}
			
			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}
			
			if (isset($this->request->get['filter_city_from'])) {
				$url .= '&filter_city_from=' . $this->request->get['filter_city_from'];
			}
			
			if (isset($this->request->get['filter_city_to'])) {
				$url .= '&filter_city_to=' . $this->request->get['filter_city_to'];
			}
			
			if (isset($this->request->get['filter_status_id'])) {
				$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['statuses'] = $this->getInfo()->getOrderStatuses();
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_order_id'] = $filter_order_id;
			$this->data['filter_dispatch_number'] = $filter_dispatch_number;
			$this->data['filter_act_number'] = $filter_act_number;
			$this->data['filter_date'] = $filter_date;
			$this->data['filter_city_from'] = $filter_city_from;
			$this->data['filter_city_to'] = $filter_city_to;
			$this->data['filter_status_id'] = $filter_status_id;
			$this->data['filter_total'] = $filter_total;
			
			$this->data['sort'] = $sort;
			$this->data['order'] = strtolower($order);
			$this->data['limit'] = $limit;
			
			$this->template = 'module/cdek_integrator/dispatch_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function dispatchView() {
			
			$this->load->model('module/cdek_integrator');
			
			if ($this->new_application) {
				$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
				} elseif (empty($this->request->get['order_id'])) {
				$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$dispatch_info = $this->model_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);
			
			if ($dispatch_info) {
				
				$this->load->model('localisation/weight_class');
				$this->load->model('localisation/length_class');
				$this->load->model('sale/order');
				
				$order_info = $this->model_sale_order->getOrder($dispatch_info['order_id']);
				
				$this->data['heading_title'] = 'Детали заказа';
				
				$this->document->setTitle($this->data['heading_title']);
				
				$this->data['button_sync'] = $this->language->get('button_sync');
				$this->data['button_print'] = $this->language->get('button_print');
				$this->data['button_cancel'] = $this->language->get('button_cancel');
				
				$url = '';
				
				$this->data['breadcrumbs'] = array();
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_module'),
				'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->breadcrumbs_separator
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_bk_main'),
				'href'      => $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->breadcrumbs_separator
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_dispatch'),
				'href'      => $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => $this->breadcrumbs_separator
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => 'Заказ #' . $dispatch_info['order_id'],
				'href'      => '',
				'separator' => $this->breadcrumbs_separator
				);
				
				if (isset($this->session->data['warning'])) {
					$this->data['error_warning'][] = $this->session->data['warning'];
					
					unset($this->session->data['warning']);
					} elseif (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
					} else {
					$this->data['error_warning'] = '';
				}
				
				$this->data['token'] = $this->session->data['token'];
				
				if (isset($this->session->data['success'])) {
					$this->data['success'] = $this->session->data['success'];
					
					unset($this->session->data['success']);
					} else {
					$this->data['success'] = '';
				}
				
				$this->data['sync'] = $this->url->link('module/cdek_integrator/dispatchSync', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
				$this->data['print'] = $this->url->link('module/cdek_integrator/dispatchPrint', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
				$this->data['cancel'] = $this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL');
				
				if (empty($dispatch_info['currency'])) $dispatch_info['currency'] = 'RUB';
				
				$this->data['dispatch_info'] = $dispatch_info;
				
				$this->data['date'] = $this->formatDate($dispatch_info['date'], false);
				
				$this->data['last_exchange'] = $this->formatDate($dispatch_info['last_exchange'], TRUE);
				
				if ($this->config->get('config_currency') != 'RUB') {
					
					$dispatch_info['delivery_cost'] = $this->currency->convert($dispatch_info['delivery_cost'], 'RUB', $this->config->get('config_regional_currency'));
					
					if ($dispatch_info['delivery_recipient_cost']) {
						$dispatch_info['delivery_recipient_cost'] = $this->currency->convert($dispatch_info['delivery_recipient_cost'], 'RUB', $this->config->get('config_regional_currency'));
					}
				}
				
				if ((float)$dispatch_info['delivery_cost']) {
					$this->data['delivery_cost'] = $this->currency->format($dispatch_info['delivery_cost'], 'RUB', '1');
					} else {
					$this->data['delivery_cost'] = 0;
				}
				
				if ((float)$dispatch_info['delivery_recipient_cost']) {
					$this->data['delivery_recipient_cost'] = $this->currency->format($dispatch_info['delivery_recipient_cost']);
				}
				
				if ((float)$dispatch_info['cod'] > 0 || (float)$dispatch_info['cod_fact']) {
					
					$this->data['currency_cod'] = $this->getInfo()->getCurrency($dispatch_info['currency_cod']);
					
					if ((float)$dispatch_info['cod'] > 0) {
						$this->data['cod'] = $this->clearCurrencyFormat($dispatch_info['cod']);
					}
					
					if ((float)$dispatch_info['cod_fact'] > 0) {
						$this->data['cod_fact'] = $this->clearCurrencyFormat($dispatch_info['cod_fact']);
					}
					
				}
				
				if ($dispatch_info['delivery_last_change']) {
					$this->data['delivery_last_change'] = $this->formatDate($dispatch_info['delivery_last_change']);
				}
				
				if ($dispatch_info['reason_status']) {
					$this->data['reason_status'] = $dispatch_info['reason_status'];
				}
				
				$this->data['status_history'] = array();
				
				foreach($this->model_module_cdek_integrator->getStatusHistory($this->request->get['order_id']) as $status_info) {
					
					$status_description = $this->getInfo()->getOrderStatus($status_info['status_id']);
					
					if (!empty($status_description)) {
						$description = $status_description['description'];
						} else {
						$description = '';
					}
					
					$this->data['status_history'][] = array(
					'status_id'		=> $status_info['status_id'],
					'name'			=> $status_info['description'],
					'description'	=> $description,
					'date'			=> $this->formatDate($status_info['date'], false),
					'city'			=> $status_info['city_name']
					);
				}
				
				$this->data['status'] = array(
				'title'	=> isset($this->data['status_history'][0])?$this->data['status_history'][0]['name']:'',
				'date'	=> isset($this->data['status_history'][0])?$this->data['status_history'][0]['date']:''
				);
				
				if ($dispatch_info['delay_id']) {
					$this->data['delay'] = array(
					'title'	=> $dispatch_info['delay_description'],
					'date'	=> $this->formatDate($dispatch_info['delay_date'])
					);
					} else {
					$this->data['delay'] = array();
				}
				
				$this->data['delay_history'] = array();
				
				foreach($this->model_module_cdek_integrator->getDelayHistory($this->request->get['order_id']) as $delay_info) {
					
					
					$this->data['delay_history'][] = array(
					'delay_id'		=> $delay_info['delay_id'],
					'name'			=> $delay_info['description'],
					'date'			=> $this->formatDate($delay_info['date'], true, true)
					);
				}
				
				if (file_exists(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf') && is_file(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf')) {
					$this->data['pdf'] = HTTP_CATALOG . 'download/cdek/order-' . $this->request->get['order_id'] . '.pdf';
				}
				
				$tariff_info = $this->getInfo()->getTariffInfo($dispatch_info['tariff_id']);
				
				$pvz_list = array();
				
				if ($tariff_info) {
					
					$this->data['tariff'] = $tariff_info;
					
					if (in_array((int)$tariff_info['mode_id'], array(2, 4)) && !empty($dispatch_info['address_pvz_code'])) {
						
						$pvz_list = $this->getPVZ($dispatch_info['recipient_city_id']);
						
						if (!empty($pvz_list['List'])) {
							
							foreach ($pvz_list['List'] as $pvz_info) {
								
								if ($dispatch_info['address_pvz_code'] == $pvz_info['Code']) {
									
									$this->data['dispatch_info']['pvz_info'] = $pvz_info;
									
									break;								
								}
								
							}
							
						}
						
					}
					
					} else {
					$this->data['tariff'] = array('title'	=> '<span class="error">Тариф не определен!</span>');
				}
				
				$courier_call = $this->model_module_cdek_integrator->getCourierCall($this->request->get['order_id']);
				
				if ($courier_call) {
					
					$courier_call['date'] = $this->formatDate($courier_call['date'], FALSE);
					
					foreach (array('time_beg', 'time_end', 'lunch_beg', 'lunch_end') as $time_key) {
						if ($courier_call[$time_key]) $courier_call[$time_key] = date('H:i', strtotime($courier_call[$time_key]));
					}
					
					$this->data['courier'] = $courier_call;
				}
				
				$this->data['schedule'] = array();
				
				$schedule = $this->model_module_cdek_integrator->getChedule($this->request->get['order_id']);
				
				foreach ($schedule as $attempt_info) {
					
					$attempt_info['date'] = $this->formatDate($attempt_info['date'], FALSE);
					
					foreach (array('time_beg', 'time_end') as $time_key) {
						$attempt_info[$time_key] = date('H:i', strtotime($attempt_info[$time_key]));
					}
					
					if (!empty($attempt_info['address_pvz_code'])) {
						
						if (!empty($pvz_list['List'])) {
							
							foreach ($pvz_list['List'] as $pvz_info) {
								
								if ($attempt_info['address_pvz_code'] == $pvz_info['Code']) {
									
									$attempt_info['pvz_info'] = $pvz_info;
									
									break;								
								}
								
							}
							
						}
						
					}
					
					if ($attempt_info['phone'] != '' || $attempt_info['recipient_name'] != '') {
						
						$attempt_info['recipient_info'] = array();
						
						if ($attempt_info['phone'] != '') {
							$attempt_info['recipient_info']['phone'] = $attempt_info['phone'];
						}
						
						if ($attempt_info['recipient_name'] != '') {
							$attempt_info['recipient_info']['name'] = $attempt_info['recipient_name'];
						}
						
					}
					
					if ($attempt_info['address_street'] != '' && $attempt_info['address_house'] != '' || !empty($attempt_info['pvz_info'])) {
						
						$attempt_info['address_info'] = array();
						
						if ($attempt_info['address_street'] != '') {
							$attempt_info['address_info']['street'] = $attempt_info['address_street'];
						}
						
						if ($attempt_info['address_house'] != '') {
							$attempt_info['address_info']['house'] = $attempt_info['address_house'];
						}
						
						if ($attempt_info['address_flat'] != '') {
							$attempt_info['address_info']['flat'] = $attempt_info['address_flat'];
						}
						
						if (!empty($attempt_info['pvz_info'])) {
							$attempt_info['address_info']['pvz_info'] = $attempt_info['pvz_info'];
						}
						
					}
					
					$attempt_info['show_more'] = (!empty($attempt_info['recipient_info']) || !empty($attempt_info['address_info']) || $attempt_info['comment'] != '' || $attempt_info['delay'] != '');
					
					$this->data['schedule'][] = $attempt_info;
				}
				
				$this->data['call_history'] = array();
				
				$call_history_good = $this->model_module_cdek_integrator->getCallHistoryGood($this->request->get['order_id']);
				
				if (!empty($call_history_good)) {
					
					$this->data['call_history']['good'] = array();
					
					foreach($call_history_good as $call_good_info) {
						
						$this->data['call_history']['good'][] = array(
						'date'			=> $this->formatDate($call_good_info['date']),
						'date_deliv'	=> $this->formatDate($call_good_info['date_deliv'])
						);
						
					}
					
				}
				
				$call_history_fail = $this->model_module_cdek_integrator->getCallHistoryFail($this->request->get['order_id']);
				
				if (!empty($call_history_fail)) {
					
					$this->data['call_history']['fail'] = array();
					
					foreach($call_history_fail as $call_fail_info) {
						
						$this->data['call_history']['fail'][] = array(
						'fail_id'		=> (int)$call_fail_info['fail_id'],
						'date'			=> $this->formatDate($call_fail_info['date']),
						'description'	=> $call_fail_info['description']
						);
						
					}
					
				}
				
				$call_history_delay = $this->model_module_cdek_integrator->getCallHistoryDelay($this->request->get['order_id']);
				
				if (!empty($call_history_delay)) {
					
					$this->data['call_history']['delay'] = array();
					
					foreach($call_history_delay as $call_delay_info) {
						
						$this->data['call_history']['delay'][] = array(
						'date'		=> $this->formatDate($call_delay_info['date']),
						'date_next'	=> $this->formatDate($call_delay_info['date_next'])
						);
						
					}
					
				}
				
				$this->data['currency'] = $this->getInfo()->getCurrency($dispatch_info['currency']);
				
				$this->data['packages'] = array();
				
				$packages = $this->model_module_cdek_integrator->getPackages($this->request->get['order_id']);
				
				$weight_class_info = $this->model_localisation_weight_class->getWeightClass($this->config->get('config_weight_class_id'));
				
				if ($weight_class_info) {
					$this->data['weight_class'] = $weight_class_info['title'];
					} else {
					$this->data['weight_class'] = 'Граммы';
				}
				
				$length_class_info = $this->model_localisation_length_class->getLengthClass($this->config->get('config_length_class_id'));
				
				if ($length_class_info) {
					$this->data['length_class'] = $length_class_info['title'];
					} else {
					$this->data['length_class'] = 'Сантиметры';
				}
				
				$change_weight = ($this->setting['weight_class_id'] != $this->config->get('config_weight_class_id'));
				$change_length = ($this->setting['length_class_id'] != $this->config->get('config_length_class_id'));
				
				foreach ($packages as $package_info) {
					
					$items = array();
					
					if ($change_weight) {
						$package_info['weight'] = $this->weight->convert($package_info['weight'], $this->setting['weight_class_id'], $this->config->get('config_weight_class_id'));
					}
					
					if ((float)$package_info['size_a'] > 0 && (float)$package_info['size_b'] > 0 && (float)$package_info['size_c'] > 0) {
						
						$package_size = array($package_info['size_a'], $package_info['size_b'], $package_info['size_c']);
						
						foreach ($package_size as &$size_item) {
							
							if ($change_length) {
								$size_item = $this->length->convert($size_item, $this->setting['length_class_id'], $this->config->get('config_length_class_id'));
							}
							
							$size_item = (float)round($size_item, 2);
						}
						
						$package_info['package_size'] = implode(' x ', $package_size);
					}
					
					$package_items = $this->model_module_cdek_integrator->getPackageItems($package_info['package_id'], $this->request->get['order_id']);
					
					$package_info['items'] = array();
					
					if (!$order_info || !$this->currency->getId($order_info['currency_code'])) {
						$order_info['currency_code'] = 'RUB';
					}
					
					foreach ($package_items as $package_item) {
						
						if ($change_weight) {
							$package_item['weight'] = $this->weight->convert($package_item['weight'], $this->setting['weight_class_id'], $this->config->get('config_weight_class_id'));
						}
						
						$package_item['weight'] = (float)round($package_item['weight'], 4);
						
						if ($this->config->get('config_currency') != $order_info['currency_code']) {
							
							$package_item['cost'] = $this->currency->convert($package_item['cost'], $order_info['currency_code'], $this->config->get('config_currency'));
							$package_item['payment'] = $this->currency->convert($package_item['payment'], $order_info['currency_code'], $this->config->get('config_currency'));
							
						}
						
						$package_item['total'] = $this->currency->format($package_item['cost'] * $package_item['amount']);
						$package_item['cost'] = $this->currency->format($package_item['cost']);
						$package_item['payment'] = $this->currency->format($package_item['payment']);
						
						$package_info['items'][] = $package_item;
					}
					
					$this->data['packages'][] = $package_info;
					
				}
				
				$this->data['add_service_total'] = 0;
				
				$this->data['add_service'] = array();
				
				$add_service = $this->model_module_cdek_integrator->getAddService($this->request->get['order_id']);
				
				foreach ($add_service as $service_info) {
					
					$cdek_service_info = $this->getInfo()->getAddService($service_info['service_id']);
					
					if ($cdek_service_info) {
						$service_info['service_description'] = $cdek_service_info['description'];
					}
					
					if ($this->config->get('config_currency') == 'RUB') {
						$service_info['price'] = $this->currency->convert($service_info['price'], 'RUB', $this->config->get('config_currency'));
					}
					
					$this->data['add_service_total'] += $service_info['price'];
					
					$service_info['price'] = $this->currency->format($service_info['price']);
					
					$this->data['add_service'][] = $service_info;
					
				}
				
				if ($this->data['add_service_total']) {
					$this->data['add_service_total'] = $this->currency->format($this->data['add_service_total']);
				}
				
				} else {
				
				$this->data['success'] = 'Отправление #' . $this->request->get['order_id'] . ' не найдено!';
				$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
				
			}
			
			$this->template = 'module/cdek_integrator/dispatch_info.tpl';
			
			$this->renderPage(!$this->isAjax());
		}
		
		private function renderPage($render = TRUE) {
			
			if ($render) {
				
				$this->data['content'] = $this->render();
				
				$this->children = array(
				'common/header',
				'common/footer'
				);
				
				$this->template = 'module/cdek_integrator/page.tpl';
			}
			
			$this->response->setOutput($this->render());
		}
		
		public function dispatchPrint() 
		{
			if (!file_exists(DIR_DOWNLOAD . 'cdek')) {
				mkdir(DIR_DOWNLOAD . 'cdek', 0777, true);
			}
			
			if (file_exists(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf')) 
			{
				if ($this->isAjax()) 
				{
					$json['message'] = $this->session->data['success'];
					$json['file'] = HTTP_CATALOG . 'download/cdek/order-' . $this->request->get['order_id'] . '.pdf';
					$this->response->setOutput(json_encode($json));
					exit;
					} else {
					$this->redirect($this->url->link('module/cdek_integrator/dispatchView', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
				}
			}
			
			$this->load->model('module/cdek_integrator');
			
			if ($this->isAjax()) {
				
				$json = array(
				'status' => 'OK'
				);
				
				if ($this->new_application || empty($this->request->get['order_id'])) {
					$json['status'] = 'error';
					$json['message'] = 'Не удалось загрузить квитанцию.';
				}
				
				} else {
				
				if ($this->new_application) {
					$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
					} elseif (empty($this->request->get['order_id'])) {
					$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
				}
				
			}
			
			$dispatch_info = $this->model_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);
			
			if ($dispatch_info) {
				
				$component = $this->api->loadComponent('order_print');
				
				$data = array(
				'copy_count'	=> (isset($this->setting['copy_count']) ? (int)$this->setting['copy_count'] : 1),
				'order'			=> array(
				array('dispatch_number' => $dispatch_info['number'])
				)
				);
				
				$component->setData($data);
				
				$pdf = $this->api->sendData($component);
				
				if (!empty($this->api->error)) {
					
					foreach ($this->api->error as $error_key => $error_message) {
						
						switch($error_key) {
							case 'ERR_AUTH':
							$this->session->data['warning'] = $this->language->get('error_auth');
							break;
							case 'ERR_INVALID_DISPACHNUMBER':
							$this->session->data['warning'] = 'Отправление #' . $dispatch_info['number'] . ' не найдено в базе СДЭК!';
							break;
							case 'ERR_ORDER_NOTFIND':
							$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
							break;
							default:
							$this->session->data['warning'] = $error_message;
							break;
						}
						
					}
					
					} else {
					
					if ($pdf != '') {
						file_put_contents(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf', $pdf);
						$this->session->data['success'] = 'Квитанция для заказа #' . $this->request->get['order_id'] . ' успешно загружена!';
						} else {
						$this->session->data['warning'] = 'Не удалось загрузить квитанцию, попробуйте ещё!';
					}
					
				}
				
				} else {
				
				$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
				
			}
			
			if ($this->isAjax()) {
				
				if (!empty($this->session->data['warning'])) {
					
					$json['status'] = 'error';
					$json['message'] = $this->session->data['warning'];
					
					unset($this->session->data['warning']);
					
					} else {
					
					$json['message'] = $this->session->data['success'];
					$json['file'] = HTTP_CATALOG . 'download/cdek/order-' . $this->request->get['order_id'] . '.pdf';
					
					unset($this->session->data['success']);
					
				}
				
				$this->response->setOutput(json_encode($json));
				
				} else {
				$this->redirect($this->url->link('module/cdek_integrator/dispatchView', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
			}
		}
		
		public function dispatchDelete() {
			
			$this->load->model('module/cdek_integrator');
			
			if ($this->isAjax()) {
				
				$json = array(
				'status' => 'OK'
				);
				
				if ($this->new_application || empty($this->request->get['order_id'])) {
					$json['status'] = 'error';
					$json['message'] = 'Не удалось загрузить квитанцию.';
				}
				
				} else {
				
				if ($this->new_application) {
					$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
					} elseif (empty($this->request->get['order_id'])) {
					$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
				}
				
			}
			
			$dispatch_info = $this->model_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);
			
			if ($dispatch_info && $dispatch_info['status_id'] == 1) { // Удалить можно только новый заказ
				
				$forced = (isset($this->request->get['forced']));
				
				$this->api->setDate(date('Y-m-d', $dispatch_info['date']));
				$component = $this->api->loadComponent('order_delete');
				$component->setNumber($dispatch_info['dispatch_number']);
				$component->setData(array($this->request->get['order_id']));
				$this->api->sendData($component);
				
				if ($forced || empty($this->api->error)) {
					
					if (file_exists(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf') && is_file(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf')) {
						@unlink(DIR_DOWNLOAD . 'cdek/order-' . $this->request->get['order_id'] . '.pdf');
					}
					
					$this->model_module_cdek_integrator->deleteDispatch($this->request->get['order_id']);
					$this->session->data['success'] = 'Заказ #' . $dispatch_info['number'] . ' успешно удален.';
					$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
					
					} else {
					
					$error_list = reset($this->api->error);
					
					$forced_delete = $this->url->link('module/cdek_integrator/dispatchDelete', 'token=' . $this->session->data['token'] . '&order_id=' . $dispatch_info['order_id'] . '&forced', 'SSL');
					
					foreach ($error_list as $error_key => $error_message) {
						
						switch($error_key) {
							case 'ERR_AUTH':
							$this->session->data['warning'] = $this->language->get('error_auth');
							break;
							case 'ERR_INVALID_DISPACHNUMBER':
							$this->session->data['warning'] = 'Отправление #' . $dispatch_info['number'] . ' не найдено в базе СДЭК! <a href="' . $forced_delete . '">Удалить принудительно</a>?';
							break;
							case 'ERR_ORDER_NOTFIND':
							$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК! <a href="' . $forced_delete . '">Удалить принудительно</a>?';
							break;
							default:
							$this->session->data['warning'] = $error_message;
							break;
						}
						
					}
					
				}
				
				} else {
				$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
			}
			
			if ($this->isAjax()) {
				
				/*if (!empty($this->session->data['warning'])) {
					
					$json['status'] = 'error';
					$json['message'] = $this->session->data['warning'];
					
					unset($this->session->data['warning']);
					
				} else {*/
				
				$json['message'] = $this->session->data['success'];
				unset($this->session->data['success']);
				
				/*}*/
				
				$this->response->setOutput(json_encode($json));
				
				} else {
				$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		private function sync($orders = array()) {
			
			if (!$orders) return FALSE;
			
			$update = array();
			
			$component = $this->api->loadComponent('order_info');
			
			$data = array(
			'order'	=> $orders
			);
			
			$component->setData($data);
			
			$info = $this->api->sendData($component);
			
			if (!empty($this->api->error)) {
				return FALSE;
			}
			
			if (isset($info->Order)) {
				
				foreach ($info->Order as $item) {
					
					$attributes = $item->attributes();
					$city_attributes = $item->SendCity->attributes();
					$recipient_city_postcode = $item->RecCity->attributes();
					
					$order_id = (int)$attributes->Number;
					
					$update[$order_id]['delivery_cost'] = (string)$attributes->DeliverySum;
					$update[$order_id]['city_postcode'] = (string)$city_attributes->PostCode;
					$update[$order_id]['recipient_city_postcode'] = (string)$recipient_city_postcode->PostCode;
					
					$update[$order_id]['cod'] = (string)$recipient_city_postcode->CashOnDeliv;
					$update[$order_id]['cod_fact'] = (string)$recipient_city_postcode->CachOnDelivFac;
					
					if ($attributes->DateLastChange != '') {
						$update[$order_id]['delivery_last_change'] = (string)strtotime($attributes->DateLastChange);
					}
					
				}
				
			}
			
			$component = $this->api->loadComponent('order_status');
			
			$data = array(
			'show_history'	=> 1,
			'order'			=> $orders
			);
			
			$component->setData($data);
			
			$status = $this->api->sendData($component);
			
			if (isset($status->Order)) {
				
				foreach ($status->Order as $item) {					
					
					$attributes = $item->attributes();
					
					//try to guess order_number
					$try = $attributes->Number;
					
					$order_id = false;
					
					if (substr_count($try, 'R') == 2){
						$try_b = explode('R', $try);
						
						if (count($try_a = explode('-', $try_b[1])) == 2){
							
							if (array_key_exists((int)$try_a[0], $orders)){
								$order_id = (int)$try_a[0];
								} elseif (array_key_exists((int)$try_a[1], $orders)){
								$order_id = (int)$try_a[1];
							}	
						}
						
						if (count($try_a = explode('-', $try_b[2])) == 2){
							if (array_key_exists((int)$try_a[0], $orders)){
								$order_id = (int)$try_a[0];
								} elseif (array_key_exists((int)$try_a[1], $orders)){
								$order_id = (int)$try_a[1];
							}	
						}					
						} elseif (count($try_a = explode('-', $try)) >= 2){
						if (array_key_exists((int)$try_a[0], $orders)){
							$order_id = (int)$try_a[0];
							} elseif (array_key_exists((int)$try_a[1], $orders)){
							$order_id = (int)$try_a[1];
						}
						} else {
						if (array_key_exists((int)$try, $orders)){
							$order_id = (int)$try;
						}
					}		
					
					if (!array_key_exists($order_id, $orders)) {
						continue;
					}
					
					$dispatch_info = $orders[$order_id];				
					
					if ((string)$attributes->ActNumber != '') {
						$update[$order_id]['act_number'] = (string)$attributes->ActNumber;
					}
					
					if ((string)$attributes->DeliveryDate != '') {
						$update[$order_id]['delivery_date'] = (string)strtotime($attributes->DeliveryDate);
					}
					
					if ((string)$attributes->RecipientName != '') {
						$update[$order_id]['delivery_recipient_name'] = (string)$attributes->RecipientName;
					}
					
					$status_attributes = $item->Status->attributes();
					
					$status_id = (string)$status_attributes->Code;
					
					$status_attributes = $item->Status->attributes();
					
					$status_id = (string)$status_attributes->Code;
					
					if ($dispatch_info['status_id'] != $status_id) {
						
						$status_history = array();
						
						foreach ($item->Status->State as $status_info) {
							
							$status_attributes = $status_info->attributes();
							
							$status_history[] = array(
							'date'			=> (string)strtotime($status_attributes->Date),
							'status_id'		=> (int)$status_attributes->Code,
							'description'	=> (string)$status_attributes->Description,
							'city_code'		=> (string)$status_attributes->CityCode,
							'city_name'		=> (string)$status_attributes->CityName
							);
						}
						
						$status_attributes = $item->Status->attributes();
						
						$update[$order_id] += array(
						'status_id'			=> (string)$status_attributes->Code,
						'status_history'	=> $status_history
						);
						
					}
					
					$reason_attributes = $item->Reason->attributes();					
					
					if ((int)$reason_attributes->Code) {
						
						$reason_history = array();
						
						$reason_history[] = array(
						'reason_id' 	=> (int)$reason_attributes->Code,
						'date'			=> (string)strtotime($reason_attributes->Date), 
						'description'	=> (string)$reason_attributes->Description
						);
						
						$update[$order_id] += array(
						'reason_id'			=> (int)$reason_attributes->Code,
						'reason_history'	=> $reason_history
						);
					}
					
					$delay_history = array();
					
					if (isset($item->DelayReason->State)) {
						
						foreach ($item->DelayReason->State as $delay_info) {
							
							$delay_attributes = $delay_info->attributes();
							
							$delay_history[] = array(
							'date'			=> (string)strtotime($delay_attributes->Date),
							'delay_id'		=> (int)$delay_attributes->Code,
							'description'	=> (string)$delay_attributes->Description,
							);
						}
						
					}
					
					$delay_attributes = $item->DelayReason->attributes();
					
					$update[$order_id] += array(
					'delay_id'			=> (int)$delay_attributes->Code,
					'delay_history'		=> $delay_history
					);
					
					if (isset($item->Attempt)) {
						
						$update[$order_id]['attempt'] = array();
						
						foreach ($item->Attempt as $attempt_info) {
							
							$attempt_attributes = $attempt_info->attributes();
							
							$attempt_id = (int)$attributes->ID;
							
							$update[$order_id]['attempt'][] = array(
							'attempt_id'	=> $attempt_id,
							'delay_id'		=> (int)$attributes->ScheduleCode,
							'description'	=> (string)$status_attributes->ScheduleDescription			
							);
						}
						
					}
					
					if (isset($item->Call)) {
						
						$update[$order_id]['call'] = array();
						
						if (isset($item->Call->CallGood->Good)) {
							
							$update[$order_id]['call']['good'] = array();
							
							foreach ($item->Call->CallGood->Good as $call_good_info) {
								
								$call_good_attributes = $call_good_info->attributes();
								
								$update[$order_id]['call']['good'][] = array(
								'date'			=> (string)strtotime($call_good_attributes->Date),
								'date_deliv'	=> (string)strtotime($call_good_attributes->DateDeliv)
								);
								
							}
							
						}
						
						if (isset($item->Call->CallFail->Fail)) {
							
							$update[$order_id]['call']['fail'] = array();
							
							foreach ($item->Call->CallFail->Fail as $call_fail_info) {
								
								$call_fail_attributes = $call_fail_info->attributes();
								
								$update[$order_id]['call']['fail'][] = array(
								'date'			=> (string)strtotime($call_fail_attributes->Date),
								'fail_id'		=> (int)$call_fail_attributes->ReasonCode,
								'description'	=> (string)$call_fail_attributes->ReasonDescription
								);
								
							}
							
						}
						
						if (isset($item->Call->CallDelay->Delay)) {
							
							$update[$order_id]['call']['delay'] = array();
							
							foreach ($item->Call->CallDelay->Delay as $call_delay_info) {
								
								$call_delay_attributes = $call_delay_info->attributes();
								
								$update[$order_id]['call']['delay'][] = array(
								'date'		=> (string)strtotime($call_delay_attributes->Date),
								'date_next'	=> (string)strtotime($call_delay_attributes->DateNext)
								);
								
							}
							
						}
						
					}
				}
				
			}
			
			return $update;
		}
		
		public function dispatchSync() {
			
			$this->load->model('module/cdek_integrator');
			
			if ($this->isAjax()) {
				
				$json = array(
				'status' => 'OK'
				);
				
				if ($this->new_application || empty($this->request->get['order_id'])) {
					$json['status'] = 'error';
					$json['message'] = 'Не удалось загрузить квитанцию.';
				}
				
				} else {
				
				if ($this->new_application) {
					$this->redirect($this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL'));
					} elseif (empty($this->request->get['order_id'])) {
					$this->redirect($this->url->link('module/cdek_integrator/dispatch', 'token=' . $this->session->data['token'], 'SSL'));
				}
				
			}
			
			$dispatch_info = $this->model_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);
			
			if ($dispatch_info) {
				
				$orders = array();
				
				$dispatch_info['dispatch_number'] = $dispatch_info['number'];
				
				$orders[$this->request->get['order_id']] = $dispatch_info;
				
				$update = $this->sync($orders);
				
				$hits_log = new Log('hits_cdekintegration.log');
				$ret = print_r($update, TRUE);
				$hits_log->write($ret);
				
				if (!empty($this->api->error)) {
					
					$error_list = reset($this->api->error);
					
					foreach ($error_list as $error_key => $error_message) {
						
						switch($error_key) {
							case 'ERR_AUTH':
							$this->session->data['warning'] = $this->language->get('error_auth');
							break;
							case 'ERR_INVALID_DISPACHNUMBER':
							$this->session->data['warning'] = 'Отправление #' . $dispatch_info['number'] . ' не найдено в базе СДЭК!';
							break;
							case 'ERR_ORDER_NOTFIND':
							$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
							break;
							default:
							$this->session->data['warning'] = $error_message;
							break;
						}
						
					}
					
					
					} elseif (array_key_exists($this->request->get['order_id'], $update)) {
					
					$data = reset($update);
					
					$this->model_module_cdek_integrator->editDispatch($this->request->get['order_id'], $data);
					//$this->model_module_cdek_integrator->changeOrderStatusForce($dispatch_info['status_id'], $dispatch_info);
					
					
					$this->session->data['success'] = 'Заказ #' . $this->request->get['order_id'] . ' обновлен!<span class="help">Дата синхронизации: ' . $this->formatDate(time(), TRUE, FALSE) . '</span>';	
					
				}
				
				} else {
				$this->session->data['warning'] = 'Заказ #' . $this->request->get['order_id'] . ' не найден в базе СДЭК!';
			}
			
			if ($this->isAjax()) {
				
				if (!empty($this->session->data['warning'])) {
					
					$json['status'] = 'error';
					$json['message'] = $this->session->data['warning'];
					
					unset($this->session->data['warning']);
					
					} else {
					
					$json['message'] = $this->session->data['success'];
					unset($this->session->data['success']);
					
					if (isset($this->request->get['target']) && $this->request->get['target'] == 'list') {
						
						$dispatch_info = $this->model_module_cdek_integrator->getDispatchInfo($this->request->get['order_id']);
						
						$json += array(
						'order_id'				=> $dispatch_info['order_id'],
						'dispatch_number'		=> $dispatch_info['number'],
						'act_number'			=> $dispatch_info['act_number'],
						'date'					=> $this->formatDate($dispatch_info['date']),
						'city_name'				=> $dispatch_info['city_name'],
						'recipient_city_name'	=> $dispatch_info['recipient_city_name'],
						'status_title'			=> $dispatch_info['status_description'],
						'status_date'			=> $this->formatDate($dispatch_info['status_date']),
						'cost'					=> $this->currency->format($dispatch_info['delivery_cost'])
						);
						
						} else {
						$json['content'] = $this->getChild('module/cdek_integrator/dispatchView');
					}
					
				}
				
				$this->response->setOutput(json_encode($json));
				
				} else {
				$this->redirect($this->url->link('module/cdek_integrator/dispatchView', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL'));
			}
			
		}
		
		public function getPVZByCity() {
			
			$json = array();
			
			if (isset($this->request->get['city_code']) && $pvz_list = $this->getPVZ($this->request->get['city_code'])) {
				$json = $pvz_list;
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		private function getPVZ($city_code) {
			
			$pvz_list = $this->getPVZList();
			return array_key_exists($city_code, $pvz_list) ? $pvz_list[$city_code] : FALSE;
		}
		
		private function getPVZList() {
			
			$data = $this->getInfo()->getPVZData();
			
			if (!$data) {
				$this->error['warning'] = $this->language->get('error_load_pvz');
			}
			
			return $data;	
		}
		
		private function getCity($cityName, $country_id, $zone_id) {
			
			$city_info = array();
			
			if (!$cityName) return '';
			
			if (!is_numeric($zone_id)) $zone_id = 0;
			
			if (!is_numeric($country_id) || !$country_id) $country_id = $this->config->get('config_country_id');
			
			$countries = $regions = array();
			$empty_country = $empty_zone = $from_db = FALSE;
			
			if (!$country_id) {
				$empty_country = TRUE;
				} else {
				
				$this->load->model('localisation/country');
				$country_info = $this->model_localisation_country->getCountry($country_id);
				
				if ($country_info) {
					$countries = $this->prepareCountry($country_info['name']);
					
					$empty_country = $from_db = in_array('россия', $countries);
					
					} else {
					return FALSE;
				}
				
			}
			
			if (!$zone_id) {
				$empty_zone = TRUE;
				} else {
				
				$this->load->model('localisation/zone');
				$zone_info = $this->model_localisation_zone->getZone($zone_id);
				
				if ($zone_info) {
					$regions = $this->prepareRegion($zone_info['name']);
					} else {
					return FALSE;
				}
				
			}
			
			$cityName = $this->_clear($cityName);
			
			if ($cityName) {
				
				if ($from_db) {
					
					$this->load->model('module/cdek_integrator');
					$cdek_cities = $this->model_module_cdek_integrator->getCity($cityName);
					
					} else {
					$cdek_cities = $this->getInfo()->getCityByName($cityName);
				}
				
				if ($cdek_cities) {
					
					$available = array();
					
					foreach ($cdek_cities as $cdek_city) {
						
						if (!$empty_country && !in_array($this->_clear($cdek_city['countryName']), $countries)) {
							continue;
						}
						
						if (!$empty_zone) {
							
							list($region) = explode(' ', str_replace('обл.', '', trim($cdek_city['regionName'])));
							
							if (!in_array($this->_clear($region), $regions)) {
								continue;
							}
						}
						
						list($city)= explode(',', $cdek_city['name']);
						
						if (mb_strpos($this->_clear($city), $cityName) === 0) {
							$available[] = $cdek_city;
						}
						
					}
					
					if ($count = count($available)) {
						
						if ($count > 1) {
							
							$sort_order = array();
							
							foreach ($available as $key => $value) {
								$sort_order[$key] = (int)($this->_clear($value['name']) == $this->_clear($value['cityName']));
							}
							
							array_multisort($sort_order, SORT_DESC, $available);
							
							$available = array($available[0]);
						}
						
						$city_info = reset($available);
						
					}
					
					} else {
					return FALSE;
				}
				
				} else {
				return FALSE;
			}
			
			return $city_info;
		}
		
		private function prepareCountry($name = '') {
			
			$countries = array();
			
			$name = $this->_clear($name);
			
			if (in_array($name, array('российская федерация', 'россия', 'russian', 'russian federation'))) {
				$countries[] = 'россия';
				} elseif(in_array($name, array('украина', 'ukraine'))) {
				$countries[] = 'украина';
				} elseif(in_array($name, array('белоруссия', 'белоруссия (беларусь)', 'беларусь', '(беларусь)', 'belarus'))) {
				$countries[] = 'белоруссия (беларусь)';
				} elseif(in_array($name, array('казахстан', 'kazakhstan'))) {
				$countries[] = 'казахстан';
				} elseif(in_array($name, array('сша', 'соединенные штаты америки', 'соединенные штаты', 'usa', 'united states'))) {
				$countries[] = 'сша';
				} elseif(in_array($name, array('aзербайджан', 'azerbaijan'))) {
				$countries[] = 'aзербайджан';
				} elseif(in_array($name, array('узбекистан', 'uzbekistan'))) {
				$countries[] = 'узбекистан';
				} elseif(in_array($name, array('китайская народная республика', 'сhina'))) {
				$countries[] = 'китай (кнр)';
				} else {
				$countries[] = $name;
			}
			
			return $countries;
		}
		
		private function prepareRegion($name = '') {
			
			$regions = array();
			
			$parts = explode(' ', $name);
			$parts = array_map(array($this, '_clear'), $parts);
			
			if (in_array($parts[0], array('московская', 'москва'))) {
				$regions[] = 'москва';
				$regions[] = 'московская';
				} elseif (in_array($parts[0], array('ленинградская', 'санкт-петербург'))) {
				$regions[] = 'санкт-петербург';
				$regions[] = 'ленинградская';
				} elseif (mb_strpos($parts[0], 'респ') === 0) {
				$regions[] = $parts[1];
				} elseif (in_array($parts[0], array('киев', 'киевская'))) { // Украина
				$regions[] = 'киевская';
				$regions[] = 'киев';
				} elseif (in_array($parts[0], array('винница', 'винницкая'))) { // Украина
				$regions[] = 'винница';
				$regions[] = 'винницкая';
				} elseif (in_array($parts[0], array('днепропетровск', 'днепропетровская'))) { // Украина
				$regions[] = 'днепропетровск';
				$regions[] = 'днепропетровская';
				} else {
				$regions = $parts;
			}
			
			return $regions;
		}
		
		private function _clear($value) {
			$value = mb_convert_case($value, MB_CASE_LOWER, "UTF-8");
			return trim($value);
		}
		
		private function fomatAddress($data) {
			
			$address = '';
			
			if (!empty($data['shipping_lastname'])) $address .= $data['shipping_lastname'];
			
			if (!empty($data['shipping_firstname'])) {
				
				if ($address) $address .= " ";
				
				$address .= $data['shipping_firstname'];
			}
			
			if (!empty($data['shipping_company'])) $address .= ', ' . $data['shipping_company'];
			
			if (!empty($data['shipping_address_1'])) $address .= ', ' . $data['shipping_address_1'];
			
			if (!empty($data['shipping_address_2'])) $address .= ', ' . $data['shipping_address_2'];
			
			if (!empty($data['shipping_city'])) $address .= ', ' . $data['shipping_city'];
			
			if (!empty($data['shipping_zone'])) $address .= ', ' . $data['shipping_zone'];
			
			if (!empty($data['shipping_postcode'])) $address .= ', ' . $data['shipping_postcode'];
			
			if (!empty($data['shipping_country'])) $address .= ', ' . $data['shipping_country'];
			
			return $address;
		}
		
		private function getPaymentMethods() {
			
			$this->load->model('setting/extension');
			
			$payment_extensions = $this->model_setting_extension->getInstalled('payment');
			
			foreach ($payment_extensions as $key => $method) {
				if (!$this->config->get($method . '_status')) {
					unset($payment_extensions[$key]);
				}
			}
			
			$payment_methods = array();
			
			$files = glob(DIR_APPLICATION . 'controller/payment/*.php');
			
			if ($files) {
				
				foreach ($files as $file) {
					
					$method = basename($file, '.php');
					
					if (in_array($method, $payment_extensions)) {
						
						$this->load->language('payment/' . $method);
						$payment_methods[$method] = $this->language->get('heading_title');
						
					}
				}
				
			}
			
			return $payment_methods;		
		}
		
		public function getAjaxPackingWeight() {
			
			if ($this->isAjax()) {
				
				$json = array();
				$json['packing_weight'] = $this->getPackingWeight((float)$this->request->get['weight']);
				$this->response->setOutput(json_encode($json));
				
				} else {
				$this->request->get['route'] = 'error/not_found';
				return $this->forward($this->request->get['route']);
			}
			
		}
		
		private function getPackingWeight($weight) {
			
			$packing_min_weight = $this->weight->convert((float)$this->setting['packing_min_weight'], $this->setting['packing_weight_class_id'], $this->setting['weight_class_id']);
			
			$packing_weight = 0;
			
			$packing_value = (float)$this->setting['packing_value'];
			
			if ($packing_value) {
				
				switch ($this->setting['packing_mode']) {
					case 'fixed':
					$packing_weight = $packing_value;
					break;
					case 'all_percent':
					$packing_weight = ($weight / 100) * $packing_value;
					break;	
				}
				
				if ($packing_min_weight && $packing_min_weight > $packing_weight) {
					$packing_weight = $packing_min_weight;
				}
				
				} elseif ($packing_min_weight) {
				$packing_weight = $packing_min_weight;
			}
			
			return array(
			'weight'	=> $packing_weight,
			'prefix'	=> $this->setting['packing_prefix']
			);
		}
		
		private function getShippingMethods() {
			
			$this->load->model('setting/extension');
			
			$shipping_extensions = $this->model_setting_extension->getInstalled('shipping');
			
			foreach ($shipping_extensions as $key => $method) {
				if (!$this->config->get($method . '_status')) {
					unset($shipping_extensions[$key]);
				}
			}
			
			$shipping_methods = array();
			
			$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
			
			if ($files) {
				
				foreach ($files as $file) {
					
					$method = basename($file, '.php');
					
					if (in_array($method, $shipping_extensions)) {
						
						$this->load->language('shipping/' . $method);
						$shipping_methods[$method] = $this->language->get('heading_title');
						
					}
				}
				
			}
			
			return $shipping_methods;
		}
		
		private function getInfo() {
			
			static $instance;
			
			if (!$instance) {
				$instance = $this->api->loadComponent('info');
			}
			
			return $instance;
		}
		
		private function validateOption() {
			
			if (!$this->setting['edit_mode']) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$post = $this->request->post;
			
			$post = !empty($this->request->post['cdek_integrator_setting']) ? $this->request->post['cdek_integrator_setting'] : array();
			
			foreach (array('city_id', 'weight_class_id', 'length_class_id', 'account', 'secure_password') as $item) {
				
				if (empty($post[$item])) {
					$this->error['setting'][$item] = $this->language->get('error_empty');
				}
				
			}
			
			if (!empty($post['new_order'])) {
				
				if (!is_numeric($post['new_order'])) {
					$this->error['setting']['new_order'] = $this->language->get('error_numeric');
					} elseif ($post['new_order'] < 0) {
					$this->error['setting']['new_order'] = $this->language->get('error_positive_numeric');
				}
				
			}
			
			if ($post['replace_items']) {
				
				if ($post['replace_item_name'] == '') {
					$this->error['setting']['replace_item_name'] = $this->language->get('error_empty');
				}
				
				foreach (array('replace_item_cost', 'replace_item_payment', 'replace_item_amount') as $item) {
					if ($post[$item] != '' && !is_numeric($post[$item])) {
						$this->error['setting'][$item] = $this->language->get('error_numeric');
					}
				}
				
			}
			
			if ($post['delivery_recipient_cost'] != '' && !is_numeric($post['delivery_recipient_cost'])) {
				$this->error['setting']['delivery_recipient_cost'] = $this->language->get('error_numeric');
			}
			
			if ($this->error && empty($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			
			return (!$this->error);
		}
		
		private function validateOrderFrom() {
			
			if (!$this->setting['edit_mode']) {
				$this->error['warning'][] = $this->language->get('error_permission');
				} else {
				
				$post = $this->request->post;
				
				if (!empty($post['cdek_orders'])) {
					
					$attempt_exists = $courier_exists = array();
					
					$tariff_list = $this->getInfo()->getTariffList();
					
					foreach ($post['cdek_orders'] as $order_info) {
						
						$order_id = $order_info['order_id'];
						
						if (!$order_info['city_id']) {
							$this->error['cdek_orders'][$order_id]['city_id'] = $this->language->get('error_empty');
						}
						
						if (!$order_info['recipient_city_id']) {
							$this->error['cdek_orders'][$order_id]['recipient_city_id'] = $this->language->get('error_empty');
						}
						
						if (utf8_strlen($order_info['recipient_name']) < 1) {
							$this->error['cdek_orders'][$order_id]['recipient_name'] = $this->language->get('error_empty');
						}
						
						if ($order_info['recipient_telephone'] == '') {
							$this->error['cdek_orders'][$order_id]['recipient_telephone'] = $this->language->get('error_empty');
						}
						
						if ($order_info['recipient_email'] != '') {
							
							if (!filter_var($order_info['recipient_email'], FILTER_VALIDATE_EMAIL)) {
								$this->error['cdek_orders'][$order_id]['recipient_email'] = $this->language->get('error_email');
								} else {
								
								$valid = true;
								
								$domain = rtrim(substr($order_info['recipient_email'], strpos($order_info['recipient_email'],'@')+1), '>');
								
								if (function_exists('checkdnsrr')) {
									$valid = checkdnsrr($domain, 'MX');
									} elseif (function_exists('getmxrr')) {
									$valid = getmxrr($domain);
								}
								
								if (!$valid) {
									$this->error['cdek_orders'][$order_id]['recipient_email'] = sprintf($this->language->get('error_domain'), $domain);
								}
							}
							
						}
						
						if ($order_info['cod'] && $order_info['currency_cod'] != $this->setting['currency_agreement']) {
							$this->error['cdek_orders'][$order_id]['currency_cod'] = $this->language->get('error_currency_cod');
						}
						
						if ($order_info['delivery_recipient_cost'] != '' && !is_numeric($order_info['delivery_recipient_cost'])) {
							$this->error['cdek_orders'][$order_id]['delivery_recipient_cost'] = $this->language->get('error_numeric');
						}
						
						if ($order_info['cdek_comment'] != '' && utf8_strlen($order_info['cdek_comment']) > 255) {
							$this->error['cdek_orders'][$order_id]['cdek_comment'] = $this->language->get('error_maxlength_255');
						}
						
						foreach ($order_info['package'] as $package_id => $package_info) {
							
							if ($package_info['weight'] == '' || !is_numeric($package_info['weight'])) {
								$this->error['cdek_orders'][$order_id]['package'][$package_id]['weight'] = $this->language->get('error_numeric');
								} elseif ($package_info['weight'] <= 0) {
								$this->error['cdek_orders'][$order_id]['package'][$package_id]['weight'] = $this->language->get('error_positive_numeric');
							}
							
							if ($package_info['pack']) {
								
								foreach (array('size_a', 'size_b', 'size_c') as $size) {
									if ($package_info[$size] == '' || !is_numeric($package_info[$size])) {
										
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['size'] = $this->language->get('error_numeric');
										
										break;
									}
								}
								
							}
							
							if (!empty($package_info['item'])) {
								
								foreach ($package_info['item'] as $item_row => $package_item) {
									
									if ($package_item['weight'] == '' || !is_numeric($package_item['weight'])) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['weight'] = $this->language->get('error_numeric');
									}
									
									if ($package_item['cost'] == '' || !is_numeric($package_item['cost'])) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['cost'] = $this->language->get('error_numeric');
										}  elseif ($package_item['cost'] < 0) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['cost'] = $this->language->get('error_positive_numeric');
									}
									
									if (!empty($package_item['payment'])) {
										
										if (!is_numeric($package_item['payment'])) {
											$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['payment'] = $this->language->get('error_numeric');
											} elseif ($package_item['payment'] <= 0) {
											$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['payment'] = $this->language->get('error_positive_numeric');
										}
										
									}
									
									if ($package_item['amount'] == '' || !is_numeric($package_item['amount'])) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['amount'] = $this->language->get('error_numeric');
										} elseif ($package_item['amount'] <= 0) {
										$this->error['cdek_orders'][$order_id]['package'][$package_id]['item'][$item_row]['amount'] = $this->language->get('error_positive_numeric');
									}
									
								}
								
								} else {
								$this->error['cdek_orders'][$order_id]['package'][$package_id]['warning'] = 'Список вложений пуст';
							}
							
						}
						
						if (!$order_info['tariff_id'] || !isset($tariff_list[$order_info['tariff_id']])) {
							$this->error['cdek_orders'][$order_id]['tariff_id'] = $this->language->get('error_tariff_id');
							} else {
							
							$tariff_info = $tariff_list[$order_info['tariff_id']];
							
							if (in_array($tariff_info['mode_id'], array(1, 3))) { // Д-Д, С-Д
								
								if ($order_info['address']['street'] == '') {
									$this->error['cdek_orders'][$order_id]['address']['street'] = $this->language->get('error_empty');
								}
								
								if ($order_info['address']['house'] == '') {
									$this->error['cdek_orders'][$order_id]['address']['house'] = $this->language->get('error_empty');
								}
								
								if ($order_info['address']['flat'] == '') {
									$this->error['cdek_orders'][$order_id]['address']['flat'] = $this->language->get('error_empty');
								}
								
								} else { // C-C, C-Д
								
								if ($order_info['address']['pvz_code'] == '') {
									$this->error['cdek_orders'][$order_id]['address']['pvz_code'] = $this->language->get('error_empty');
								}
								
							}
							
							if (!empty($order_info['schedule'])) {
								
								$attempt_exists = array();
								
								foreach ($order_info['schedule'] as $attempt_row => $attempt_info) {
									
									if ($attempt_info['date'] == '' || !$this->validateDate($attempt_info['date'], FALSE)) {
										$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['date'] = $this->language->get('error_date');
										} elseif (!$this->validateDate($attempt_info['date'], TRUE, 'Y-m-d')) {
										$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['date'] = $this->language->get('error_date_futured');
										} else {
										
										$timestamp = strtotime(date('Y-m-d', strtotime($attempt_info['date'])));
										
										if (in_array($timestamp, $attempt_exists)) {
											$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['date'] = $this->language->get('error_attempt_date_exists');
											} else {
											$attempt_exists[] = strtotime(date('Y-m-d', strtotime($attempt_info['date'])));
										}
										
									}
									
									if ($attempt_info['time_beg'] == '' || !$this->validateTime($attempt_info['time_beg']) || $attempt_info['time_end'] == '' || !$this->validateTime($attempt_info['time_end'])) {
										$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['time'] = $this->language->get('error_time');
										} elseif ((strtotime($attempt_info['time_end']) - strtotime($attempt_info['time_beg'])) < 10800) {
										$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['time'] = $this->language->get('error_time_interval_3');
									}
									
									if ($attempt_info['comment'] != '' && utf8_strlen($attempt_info['comment']) > 255) {
										$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['comment'] = $this->language->get('error_maxlength_255');
									}
									
									if ($attempt_info['new_address']) {
										
										if (in_array($tariff_info['mode_id'], array(1, 3))) { // Д-Д, С-Д
											
											if ($attempt_info['street'] == '') {
												$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['street'] = $this->language->get('error_empty');
											}
											
											if ($attempt_info['house'] == '') {
												$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['house'] = $this->language->get('error_empty');
											}
											
											if ($attempt_info['flat'] == '') {
												$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['flat'] = $this->language->get('error_empty');
											}
											
											} else { // C-C, C-Д
											
											if ($attempt_info['pvz_code'] == '') {
												$this->error['cdek_orders'][$order_id]['schedule'][$attempt_row]['pvz_code'] = $this->language->get('error_pvz_code');
											}
											
										}
										
									}
									
								}
								
							}
							
							if ($order_info['courier']['call']) {
								
								if ($order_info['courier']['date'] == '' || !$this->validateDate($order_info['courier']['date'], FALSE)) {
									$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_date');
									} elseif (!$this->validateDate($order_info['courier']['date'], TRUE, 'Y-m-d')) {
									$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_date_futured');
									} else {
									
									$timestamp = strtotime(date('Y-m-d', strtotime($order_info['courier']['date'])));
									
									if (in_array($timestamp, $courier_exists)) {
										$this->error['cdek_orders'][$order_id]['courier']['date'] = $this->language->get('error_courier_date_exists');
										} else {
										$courier_exists[] = strtotime(date('Y-m-d', strtotime($order_info['courier']['date'])));
									}
									
								}
								
								if ($order_info['courier']['time_beg'] == '' || !$this->validateTime($order_info['courier']['time_beg']) || $order_info['courier']['time_end'] == '' || !$this->validateTime($order_info['courier']['time_end'])) {
									$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_time');
									} elseif ((strtotime($order_info['courier']['time_end']) - strtotime($order_info['courier']['time_beg'])) < 10800) {
									$this->error['cdek_orders'][$order_id]['courier']['time'] = $this->language->get('error_time_interval_3');
								}
								
								if ($order_info['courier']['lunch_beg'] != '' || $order_info['courier']['lunch_end'] != '') {
									
									if ($order_info['courier']['lunch_beg'] == '' || !$this->validateTime($order_info['courier']['lunch_beg']) || ($order_info['courier']['lunch_end'] == '' || !$this->validateTime($order_info['courier']['lunch_end']))) {
										$this->error['cdek_orders'][$order_id]['courier']['lunch'] = $this->language->get('error_time');
									}
									
								}
								
								if ($order_info['courier']['city_id'] == '') {
									$this->error['cdek_orders'][$order_id]['courier']['city_id'] = $this->language->get('error_empty');
								}
								
								if ($order_info['courier']['street'] == '') {
									$this->error['cdek_orders'][$order_id]['courier']['street'] = $this->language->get('error_empty');
								}
								
								if ($order_info['courier']['house'] == '') {
									$this->error['cdek_orders'][$order_id]['courier']['house'] = $this->language->get('error_empty');
								}
								
								if ($order_info['courier']['flat'] == '') {
									$this->error['cdek_orders'][$order_id]['courier']['flat'] = $this->language->get('error_empty');
								}
								
								if ($order_info['courier']['send_phone'] == '') {
									$this->error['cdek_orders'][$order_id]['courier']['send_phone'] = $this->language->get('error_empty');
								}
								
								if ($order_info['courier']['sender_name'] == '') {
									$this->error['cdek_orders'][$order_id]['courier']['sender_name'] = $this->language->get('error_empty');
								}
								
								if ($order_info['courier']['comment'] != '' && utf8_strlen($order_info['courier']['comment']) > 255) {
									$this->error['cdek_orders'][$order_id]['courier']['comment'] = $this->language->get('error_maxlength_255');
								}
								
							}
							
						}
					}
					
					} else {
					$this->error['warning'][] = $this->language->get('error_empty_order_list');
				}			
				
			}
			
			if ($this->error && empty($this->error['warning'])) {
				$this->error['warning'][] = $this->language->get('error_warning');
			}
			
			return (!$this->error);
		}
		
		private function validateDate($str, $current = TRUE, $format = 'Y-m-d') {
			
			$status = TRUE;
			
			if (!$str) {
				$status = FALSE;
				} elseif (date($format, strtotime($str)) != trim($str)) {
				$status = FALSE;
				} elseif ($current && strtotime($str) <= strtotime(date($format))) {
				$status = FALSE;
			}
			
			return $status;
		}
		
		private function validateTime($time, $format = 'H:i') {
			
			$status = TRUE;
			
			if (!$time) {
				$status = FALSE;
				} elseif (strtotime($time) === FALSE) {
				$status = FALSE;
			}
			
			return (bool)$status;
		}
		
		private function declination($number, $titles) {  
			$cases = array (2, 0, 1, 1, 1, 2);  
			return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];  
		}
		
		private function getDateExecuted($format = 'Y-m-d\TH:i:sP') {
			return gmdate($format, $this->time_execute);
		}
		
		private function isAjax() {
			return (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && $this->request->server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
		}
		
		private function getSetting() {
			return $this->config->get('cdek_integrator_setting');
		}
		
		private function init() {
			
			$this->setting = $this->getSetting();
			
			$this->time_execute = time();
			$this->new_application = empty($this->setting);
			
			$this->setting['edit_mode'] = true; //$this->user->hasPermission('modify', 'module/cdek_integrator');
			
			$this->load->language('module/cdek_integrator');
			
			$this->document->addStyle('view/stylesheet/cdek_integrator.css');
			$this->document->addScript('view/javascript/jquery/cdek_integrator.js');
			
			require_once DIR_SYSTEM . '/cdek_integrator/class.app.php';
			app::registry()->create($this->registry);
			
			require_once DIR_SYSTEM . '/cdek_integrator/class.cdek_integrator.php';
			$this->api = new cdek_integrator();
			
			if (!empty($this->setting['account']) && !empty($this->setting['secure_password'])) {
				$this->api->setAuth($this->setting['account'], $this->setting['secure_password']);
			}
			
		}
		
		public function install() {
			
			$this->load->model('module/cdek_integrator');
			$this->model_module_cdek_integrator->install();
			
		}
		
		public function uninstall() {
			
			$this->load->model('module/cdek_integrator');
			$this->model_module_cdek_integrator->uninstall();
			
		}
		
		public function cron()
		{
			if (empty($this->setting['use_cron'])) {
				return FALSE;
			}
			
			$this->load->model('module/cdek_integrator');
			$this->load->model('sale/order');
			
			//		$order_status_rule = $this->setting['order_status_rule'];
			
			$dispatches = $this->model_module_cdek_integrator->getDispatchesToSync();			
			
			if (!$dispatches){
				return FALSE;
			}
			
			$isset_dispatches = array();
			$orders = array();
			foreach ($dispatches as $key => $dispatch_info) 
			{
				$orders[] = array(
				'dispatch_number' => $dispatch_info['dispatch_number']
				);
				$isset_dispatches[$dispatch_info['dispatch_number']] = $dispatch_info;
			}
			
			$component = $this->api->loadComponent('order_status');
			
			$component->setData(array('order' => $orders));
			
			$status = $this->api->sendData($component);
			
			var_dump($status);
			
			if (isset($status->Order)) 
			{
				foreach ($status->Order as $key => $order) 
				{
					$update = array();				
					$status_id = (string)$order->Status->attributes()->Code;
					$updateHistory = array(
					'order_status_id' => 0,
					'notify' => 0, 
					'comment' => '');
					$dispatch_number = (string)$order->attributes()->DispatchNumber;
					$current_dispatch = $isset_dispatches[$dispatch_number];
					
					/*
						foreach ($order_status_rule as $key => $rule) 
						{
						if($status_id == $rule['cdek_status_id'])
						{
						$updateHistory = array(
						'order_status_id' => $rule['order_status_id'],
						'notify' => (int)$rule['notify'], 
						'comment' => $rule['comment']);
						break;
						}
						}
						
						if($updateHistory['order_status_id'])
						{
						$order_info = $this->model_sale_order->getOrder($current_dispatch['order_id']);
						$current_status = $order_info['order_status_id'];
						if((int)$current_status != (int)$updateHistory['order_status_id'])
						{
						$this->model_sale_order->addOrderHistory($current_dispatch['order_id'], $updateHistory);
						echo 'Статус заказа №' . $current_dispatch['order_id'] . ' обновлен.<BR>';
						}
						
						}
					*/
					
					if (true || $current_dispatch['status_id'] != $status_id) 
					{
						
						$filter_data = array(
						$current_dispatch['order_id'] => $current_dispatch
						);
						
						$update = $this->sync($filter_data);
						
						
						if (array_key_exists((int)$current_dispatch['order_id'], $update)) {
							//	$update = reset($update);
							} else {
							$update = array();
						}
						
					}
					
					if ($update) {
						$this->model_module_cdek_integrator->editDispatch($current_dispatch['order_id'], $update);
						echo 'Заказ СДЭК №' . $current_dispatch['dispatch_number'] . ' обновлен.'. PHP_EOL;
					}
					
				}
			}
		}
		
		public function cron1() {
			
			if (empty($this->setting['use_cron'])) {
				return FALSE;
			}
			
			$this->load->model('module/cdek_integrator');
			$dispatch_info = $this->model_module_cdek_integrator->getDispatchToSync();
			
			
			if ($dispatch_info) {
				
				$orders = array();
				$orders[] = array(
				'dispatch_number' => $dispatch_info['dispatch_number']
				);
				
				$component = $this->api->loadComponent('order_status');
				
				$component->setData(array('order' => $orders));
				
				$status = $this->api->sendData($component);
				
				$update = array();
				
				if (isset($status->Order)) {
					
					
					$order = $status->Order;
					
					$status_id = (string)$order->Status->attributes()->Code;
					
					if ($dispatch_info['status_id'] != $status_id) {
						
						$filter_data = array(
						$dispatch_info['order_id'] => $dispatch_info
						);
						
						$update = $this->sync($filter_data);
						
						if (array_key_exists($dispatch_info['order_id'], $update)) {
							$update = reset($update);
							} else {
							$update = array();
						}
						
					}
					
				}
				
				if ($update) {
					
					$this->model_module_cdek_integrator->editDispatch($dispatch_info['order_id'], $update);
					
					echo 'Заказ №' . $dispatch_info['dispatch_number'] . ' обновлен.';
				}
			}
			
		}
		
		private function clearCurrencyFormat($value, $decimal_place = 2, $decimal_point = '.', $thousand_point = ' ') {
			return number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);
		}
		
		public function showHelp()
		{
			
			
			$this->template = 'module/cdek_integrator/help.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function showLicense()
		{
			if(isset($this->request->post['cdekLicense_user']) && isset($this->request->post['cdekLicense_password']))
			{
				$this->load->model('setting/setting');
				$user = $this->request->post['cdekLicense_user'];
				$password = $this->request->post['cdekLicense_password'];
				$this->model_setting_setting->editSetting('cdekLicense', array('cdekLicense_user'=>$user, 'cdekLicense_password'=>$password));
			}		
			
			$this->load->model('module/cdek_license');
			
			$license = $this->model_module_cdek_license->chechLicense();
			
			$this->data['action'] = $this->url->link('module/cdek_integrator/showLicense', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['status'] = $license['status'];
			$this->data['message'] = $license['message'];
			
			$this->template = 'module/cdek_integrator/license.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
		}
	}
	
?>
<?php
	class ControllerSaleAmazon extends Controller {
		private $error = array();
		
		public function index() {
			
			
			$this->document->setTitle('Заказы поставщикам Amazon');
			
			$this->load->model('sale/amazon');
			
			$this->getList();
		}
		
		public function getStatusInfoAjax(){
			
			$status = $this->request->get['status'];
			
			$return = $this->amazonPageParser->guessAmazonStatus($status);
			
			print_r($return);
		}
		
		
		protected function getList() {
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			$this->language->load('sale/order');
			
			$_filters = array(
			'filter_amazon_id' => null,
			'filter_product_id' => null,
			'filter_supplier_id' => null,
			'filter_total' => null,
			'filter_date_added' => null,
			'filter_date_added_to' => null,
			'filter_status' => null,
			'filter_asin' => null,
			'filter_order_id' => null,
			'filter_is_problem' => null,
			'filter_is_dispatched' => null,
			'filter_is_return' => null,
			'filter_date_arriving_from' => null,
			'filter_date_arriving_to' => null,
			'filter_date_expected_from' => null,
			'filter_date_expected_to' => null,
			'filter_date_delivered_from' => null,
			'filter_date_delivered_to' => null,
			'sort' => 'date_added',
			'order' => 'DESC',
			'page'  => 1			
			);
			
			$this->data['filters'] = $_filters;
			
			foreach ($_filters as $filter => $default_value){				
				if (isset($this->request->get[$filter])) {
					${$filter} = $this->request->get[$filter];
					} else {
					${$filter} = $default_value;
				}				
			}
			
			$this->data['filtered_product'] = array(
			'name' => '',
			'product_id' => '',
			'model' => '',
			'ean' => '',
			'asin' => ''
			);
			
			$this->data['filtered_supplier'] = array(
			'name' => '',
			'supplier_id' => '',
			);
			
			if (isset($this->request->get['filter_product_id'])) {
				$filter_product_id = $this->request->get['filter_product_id'];							
				if ($filtered_product = $this->model_catalog_product->getProduct($filter_product_id)) {
					$this->data['filtered_product'] = array(
					'name' => $filtered_product['name'],
					'product_id' => $filtered_product['product_id'],
					'model' => $filtered_product['model'],
					'ean' => $filtered_product['ean'],
					'asin' => $filtered_product['asin']
					);
				}
			}
			
			if (isset($this->request->get['filter_supplier_id'])) {
				$this->load->model('sale/supplier');
				$filter_supplier_id = $this->request->get['filter_supplier_id'];
				if ($filtered_supplier = $this->model_sale_supplier->getSupplier($this->request->get['filter_supplier_id'])){
					$this->data['filtered_supplier'] = array(
					'name' => $filtered_supplier['supplier_name'],
					'supplier_id' => $filtered_supplier['supplier_id'],
					);
				}
			}
			
			$url = '';
			foreach ($_filters as $filter => $default_value){	
				if (isset($this->request->get[$filter])) {
					$url .= "&$filter=" . $this->request->get[$filter];
				}
			}
			
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
			
			$this->data['heading_title'] = '<i class="fa fa-amazon" style="display:inline-block" aria-hidden="true"></i> Заказы поставщикам Amazon.de';
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->data['heading_title'],
			'href'      => $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			
			$data = array(			
			'start'                  => ($page - 1) * 20,
			'limit'                  => 20
			);
			
			foreach ($_filters as $filter => $default_value){	
				$data[$filter] = ${$filter};
			}
			
			$order_total = $this->model_sale_amazon->getTotalOrders($data);
			$this->data['order_total'] = $order_total;
			
			$results = $this->model_sale_amazon->getOrders($data);
			$this->data['orders'] = array();
			
			foreach ($results as $result) {
				$action = array();
				
				$products = $this->model_sale_amazon->getOrderProducts($result['amazon_id'], $data);
				$deliveries = array();	
				
				foreach ($products as $product){		
					if (!isset($deliveries[$product['delivery_num']])){
						$deliveries[$product['delivery_num']] = array();
					}
					
					$deliveries[$product['delivery_num']]['products'] = array();
				
					
					foreach ($this->amazonPageParser->guessArray as $key => $value){
						$deliveries[$product['delivery_num']][$key]	= $product[$key];					
					}
						
					if ($deliveries[$product['delivery_num']]['is_problem'] && (!isset($result['is_problem']) || !$result['is_problem'])){
						$result['is_problem'] = true;
					}
					
					$deliveries[$product['delivery_num']]['delivery_status'] = $result['cancelled']?'Заказ был отменен!':($product['delivery_status']);
					$deliveries[$product['delivery_num']]['delivery_status_ru'] = $result['cancelled']?'Заказ был отменен!':($product['delivery_status_ru']);
					
					if (!$deliveries[$product['delivery_num']]['delivery_status_ru']){
						$deliveries[$product['delivery_num']]['delivery_status_ru'] = $deliveries[$product['delivery_num']]['delivery_status'];
					}									
					
				}
				
				
				
				
				foreach ($products as $product){			
					
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], 50, 50);
						} else {
						$image = $this->model_tool_image->resize('no_image.jpg', 50, 50);
					}
					
					$rp = $this->model_sale_amazon->getProductByASIN($product['asin']);
					
					$real_products = array();
					foreach ($rp as &$r){
						$r['sitelink'] = HTTPS_CATALOG . 'index.php?route=product/product&product_id='.$r['product_id'];
						$r['adminlink'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $r['product_id'], 'SSL');
						
						if ($r['image']) {
							$r['aimage'] = $this->model_tool_image->resize($r['image'], 50, 50);
							} else {
							$r['aimage'] = $this->model_tool_image->resize('no_image.jpg', 50, 50);
						}
						
						$real_products[] = $r;						
					}
					
					if ($product['asin'] && !$result['cancelled']){
						$_orders = $this->model_sale_amazon->getOrdersForProductByASIN($product['asin']);	
						} else {
						$_orders = array();
					}
					
					foreach ($_orders as &$o){
						$o['adminlink'] = $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $o['order_id'], 'SSL');
					}
					
					$do_add = true;
					$_in = true;
					if (isset($this->request->get['filter_order_id'])){						
						$_in = false;
						foreach ($_orders as $_order){
							if ($_order['order_id'] == (int)$this->request->get['filter_order_id']){
								$_in = true;
								break;
							}
						}
					}
					$do_add = $do_add & $_in;
					
					if (isset($this->request->get['filter_supplier_id'])){						
						//$do_add = $do_add & ($this->request->get['filter_supplier_id'] == $product['supplier_id']);
					}
									
					
					if ($do_add) {
						$deliveries[$product['delivery_num']]['products'][] = array(
						'asin' => $product['asin'],
						'filter_asin' => $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . '&filter_asin=' . $product['asin'], 'SSL'),
						'name' => $product['name'],
						'image' => $image,
						'supplier' => $product['supplier'],
						'filter_supplier_id' => $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . '&filter_supplier_id=' . $product['supplier_id'], 'SSL'),
						'supplier_editlink' => $this->url->link('sale/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $product['supplier_id'], 'SSL'),
						'real_products' => $real_products,
						'quantity' => $product['quantity'],
						'orders'   => $_orders,
						'price'  => number_format($product['price'], $decimals = 2 , $dec_point = "." , $thousands_sep = ","),
						'total'  => number_format($product['price'] * $product['quantity'], $decimals = 2 , $dec_point = "." , $thousands_sep = ","),
						);
					}
					
				}
				
				
				
				$this->data['orders'][] = array(
				'amazon_id' => $result['amazon_id'],
				'date_added' => date('d.m.Y', strtotime($result['date_added'])),
				'filter_date_added' => $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . '&filter_date_added=' . $result['date_added'].'&filter_date_added_to=' . $result['date_added'], 'SSL'),
				'total'      => number_format($result['total'], $decimals = 2 , $dec_point = "." , $thousands_sep = ","),
				'gift_card'      => number_format($result['gift_card'], $decimals = 2 , $dec_point = "." , $thousands_sep = ","),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),	
				'cancelled'    => $result['cancelled'],
				'is_problem'    => isset($result['is_problem'])?$result['is_problem']:false,
				'deliveries'    => $deliveries,
				);
			}
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');			
			$this->data['token'] = $this->session->data['token'];
			
			//prepare sorts
			$url = '';
			foreach ($_filters as $filter => $default_value){	
				if (isset($this->request->get[$filter]) && $filter != 'sort') {
					$url .= "&$filter=" . $this->request->get[$filter];
				}
			}
			$this->data['sort_total'] = $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . '&sort=total' . $url, 'SSL');
			$this->data['sort_date_added'] = $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');
			
			//pagination
			$url = '';
			foreach ($_filters as $filter => $default_value){	
				if (isset($this->request->get[$filter]) && $filter != 'page') {
					$url .= "&$filter=" . $this->request->get[$filter];
				}
			}
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = 50;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/amazon', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['amazon_last_sync'] = date('d.m.Y в H:i:s', strtotime($this->model_sale_amazon->getLastUpdate()));
			
			foreach ($_filters as $filter => $default_value){
				$this->data[$filter] = ${$filter};
			}
			
			$this->template = 'sale/amazon_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
			
		}
		
		public function autocomplete(){
			
			$json = array();
			
			if (isset($this->request->get['filter_amazon_id'])) {
				$filter_amazon_id = $this->request->get['filter_amazon_id'];
				} else {
				$filter_amazon_id = false;
			}
			
			if ($filter_amazon_id) {
				
				$query = $this->db->query("SELECT amazon_id FROM amazon_orders WHERE 
				REPLACE(amazon_id,'-','') LIKE ('" . $this->db->escape(str_replace('-','',$filter_amazon_id)) . "%') 
				OR REPLACE(amazon_id,'-','') LIKE ('%" . $this->db->escape(str_replace('-','',$filter_amazon_id)) . "') 
				ORDER BY date_added DESC LIMIT 0, 20");
				
				if ($query->rows){
					
					foreach ($query->rows as $row){
						$json[] = array(
						'amazon_id' => $row['amazon_id']			
						);							
					}					
				}								
			}
			
			$this->response->setOutput(json_encode($json));
			
		}
		
		
		
	}																						
<?php 
	class ControllerAccountOrder extends Controller {
		private $error = [];
		
		public function tracking_info(){
			$tracking_code = $this->request->post['tracking_code'];
			$shipping_code = $this->request->post['shipping_code'];						
			$shipping_phone = $this->request->post['shipping_phone'];			
			
			$this->response->setOutput($this->courierServices->getInfo($tracking_code, $shipping_code, $shipping_phone));						
		}
						
		private function prepareOrderList($results){
			$this->language->load('account/order');
			$this->load->model('account/order');
			$this->load->model('tool/user');
			$this->load->model('kp/info1c');
			$this->load->model('tool/image');			
			
			$orders = [];
			
			foreach ($results as $result) {			
				$order_info 		= $this->model_account_order->getOrder($result['order_id']);
				$product_total 		= $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total 		= $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);								
				$order_products 	= $this->model_account_order->getOrderProducts($result['order_id']);	
				
				if (!$order_products && $order_info['order_status_id'] == $this->config->get('config_cancelled_status_id')){
					$order_products = $this->model_account_order->getOrderProductsListNoGood($result['order_id']);
				}
				
				$products = [];
				
				foreach ($order_products as $product){
					if ($product['price_national'] > 0){
						$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');
						} else {
						$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
					}
					
					if ($product['total_national'] > 0){
						$total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');					
						} elseif ($product['price_national'] > 0) {
						$total = $this->currency->format($product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
						} else {
						$total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
					}
					
					
					if ($_image = $this->model_account_order->getProductImage($product['product_id'])) {
						$image = $this->model_tool_image->resize($_image, 100, 100);
						} else {
						$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 100, 100);
					}
					
					$products[] = array(
					'name'     		=> $product['name'],
					'product_id' 	=> $product['product_id'],
					'image'    		=> $image,
					'model'    		=> $product['model'],
					'link'     		=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'quantity' 		=> $product['quantity'],
					'price'    		=> $price,
					'price_isnull'  => max($product['price_national'], $product['price'] ) == 0,
					'total'    		=> $total,				
					);
					
				}			
				
				if ($total_total = $this->model_account_order->getOrderTotals($result['order_id'], 'total')){
					if ($total_total['value_national'] < 0){
						$total_national = false;
						} else {
						$total_national = $this->currency->format($total_total['value_national'], $order_info['currency_code'], 1);
					}
					} else {
					$total_national = false;
				}

				$receipt = $this->Fiscalisation->getOrderReceipt($result['order_id']);

				$orders[] = array(
				'order_id'   			=> $result['order_id'],
				'order_status_id'		=> $result['order_status_id'],
				'order_is_delivering' 	=> ($order_info['order_status_id'] == $this->config->get('config_delivering_status_id')),
				'tracking_code'     	=> $result['ttn'],
				'tracking_info'    		=> $this->courierServices->getTrackingCodeInfo($result['ttn']),
				'preorder'   		=> $result['preorder'],
				'name'       		=> $result['firstname'] . ' ' . $result['lastname'],
				'telephone'     	=> $order_info['telephone'],
				'status'     		=> $result['status'],
				'status_txt_color'  => $result['status_txt_color'],
				'status_bg_color'   => !empty($result['front_bg_color'])?$result['front_bg_color']:$result['status_bg_color'],
				'order_info'     	=> $order_info,
				'products'     		=> $products,				
				'manager'     		=> $this->model_tool_user->getRealUserNameByID($result['manager_id']),
				'manager_set'     	=> $result['manager_id'],
				'date_added' 		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),				
				'product_count'   	=> ($product_total + $voucher_total),
				'href'       		=> $this->url->link('account/order/info', 'order_id=' . $result['order_id']),
				'payment_code' 		=> $order_info['payment_code'], 
				'payment_method' 	=> $order_info['payment_method'],
				'shipping_method' 	=> $order_info['shipping_method'],	
				'shipping_code' 	=> $order_info['shipping_code'],
				'receipt_links'		=> $receipt?$this->Fiscalisation->getReceiptLinks($receipt['receipt_id']):[],
				'total_national'	=> $total_national
				);
			}
			
			
			return $orders;
		}		
				
		private function prepareLinks(){					
			$result = ['count' => [], 'links' => []];
			
			$this->load->model('account/order');
			
			if ($total = $this->model_account_order->getTotalFilterOrders(['filter' => ''])){
				$result['count']['total'] = $total;	
				$result['links']['total'] = $this->url->link('account/order');
			} 
			
			if ($total = $this->model_account_order->getTotalFilterOrders(['filter' => 'inprocess'])){
				$result['count']['inprocess'] = $total;	
				$result['links']['inprocess'] = $this->url->link('account/order/inprocessorderslist');			
			} 
			
			if ($total = $this->model_account_order->getTotalFilterOrders(['filter' => 'cancelled'])){
				$result['count']['cancelled'] = $total;	
				$result['links']['cancelled'] = $this->url->link('account/order/cancelledorderslist');			
			} 
			
			if ($total = $this->model_account_order->getTotalFilterOrders(['filter' => 'completed'])){
				$result['count']['completed'] = $total;	
				$result['links']['completed'] = $this->url->link('account/order/completedorderslist');			
			} 
			
			return $result;			
		}
		
		public function completedorderslist(){
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/order', '');
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/order');
			$this->load->model('account/order');
			
			foreach ($this->language->loadRetranslate('account/order') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->document->setTitle($this->data['heading_title']);
			
			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),        	
			'separator' => $this->language->get('text_separator')
			);
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$filter_data = [
			'filter' => 'completed',
			'start'	 => ($page - 1) * 10,
			'limit'	 => 10
			];
			
			$order_total = $this->model_account_order->getTotalFilterOrders($filter_data);
			$this->data['orders'] = $this->prepareOrderList($this->model_account_order->getFilterOrders($filter_data));
			
			
			$this->data['pagetype'] = 'completed';
			$this->data['pages'] = $this->prepareLinks();
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = 10;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/order/completedorderslist', 'page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['continue'] = $this->url->link('account/account', '');
			
			$this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
									
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
			);
			
			$this->response->setOutput($this->render());	
		}
		
		public function cancelledorderslist(){
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/order', '');
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/order');
			$this->load->model('account/order');
			
			foreach ($this->language->loadRetranslate('account/order') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->document->setTitle($this->data['heading_title']);
			
			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),        	
			'separator' => $this->language->get('text_separator')
			);
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$filter_data = [
			'filter' => 'cancelled',
			'start'	 => ($page - 1) * 10,
			'limit'	 => 10
			];
			
			$order_total = $this->model_account_order->getTotalFilterOrders($filter_data);
			$this->data['orders'] = $this->prepareOrderList($this->model_account_order->getFilterOrders($filter_data));
			
			$this->data['pagetype'] = 'cancelled';
			$this->data['pages'] = $this->prepareLinks();
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = 10;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/order/cancelledorderslist', 'page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['continue'] = $this->url->link('account/account', '');
			
			$this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
			);
			
			$this->response->setOutput($this->render());	
		}
		
		public function inprocessorderslist(){
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/order', '');
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/order');
			$this->load->model('account/order');
			
			foreach ($this->language->loadRetranslate('account/order') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->document->setTitle($this->data['heading_title']);
			
			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),        	
			'separator' => $this->language->get('text_separator')
			);
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$filter_data = [
			'filter' => 'inprocess',
			'start'	 => ($page - 1) * 10,
			'limit'	 => 10
			];
			
			$order_total = $this->model_account_order->getTotalFilterOrders($filter_data);
			$this->data['orders'] = $this->prepareOrderList($this->model_account_order->getFilterOrders($filter_data));
			
			
			$this->data['pagetype'] = 'inprocess';
			$this->data['pages'] = $this->prepareLinks();
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = 10;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/order/inprocessorderslist', 'page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['continue'] = $this->url->link('account/account', '');
			
			$this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
			);
			
			$this->response->setOutput($this->render());	
		}			
		
		public function index() {
			$this->load->model('account/order');
			$this->load->model('tool/user');
			$this->load->model('tool/image');
			
			$this->language->load('account/order');
			
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/order', '');
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			foreach ($this->language->loadRetranslate('account/order') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			if (isset($this->request->get['order_id'])) {
				$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
				
				if ($order_info) {
					$order_products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
					
					foreach ($order_products as $order_product) {
						$option_data = [];
						
						$order_options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
						
						foreach ($order_options as $order_option) {
							if ($order_option['type'] == 'select' || $order_option['type'] == 'radio') {
								$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
								} elseif ($order_option['type'] == 'checkbox') {
								$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
								} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
								$option_data[$order_option['product_option_id']] = $order_option['value'];	
								} elseif ($order_option['type'] == 'file') {
								$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
							}
						}
						
						$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->request->get['order_id']);
						
						$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
					}
					
					$this->redirect($this->url->link('checkout/cart'));
				}
			}
			
			$this->document->setTitle($this->data['heading_title']);
			
			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),        	
			'separator' => $this->language->get('text_separator')
			);

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$order_total = $this->model_account_order->getTotalOrders();
			$this->data['orders'] = $this->prepareOrderList($this->model_account_order->getOrders(($page - 1) * 10, 10));
			
			$this->data['pagetype'] = 'total';
			$this->data['pages'] = $this->prepareLinks();
			
			$pagination = new Pagination();
			$pagination->total = $order_total;
			$pagination->page = $page;
			$pagination->limit = 10;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/order', 'page={page}');
			
			if (isset($this->session->data['warning'])) {
				$this->data['error_warning'] = $this->session->data['warning'];
				unset($this->session->data['warning']);
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['continue'] = $this->url->link('account/account', '');
			
			$this->template = $this->config->get('config_template') . '/template/account/order_list.tpl';
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
			);
			
			$this->response->setOutput($this->render());				
		}
		
		public function invoice() {
			if(!isset($this->request->get['order_id'])) return false;
			
			$this->load->model('module/emailtemplate/invoice');
			
			$this->model_module_emailtemplate_invoice->getInvoice($this->request->get['order_id'], false);
			exit(0);
		}
		
		public function info() { 
			$this->language->load('account/transaction');
			$this->language->load('account/order');			
			$this->load->model('payment/paykeeper');
			$this->load->model('payment/pp_express');
			$this->load->model('payment/liqpay');			
			$this->load->model('payment/concardis');
			$this->load->model('account/transaction');
			$this->load->model('account/order');
			$this->load->model('tool/image');
			$this->load->model('tool/user');
			
			$this->load->model('account/order');			
			$this->load->model('kp/info1c');
									
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
				} else {
				$order_id = 0;
			}	

			foreach ($this->language->loadRetranslate('account/order') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}	

			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id);				
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$order_info = $this->model_account_order->getOrder($order_id);
			
			if ($order_info) {
				$this->document->setTitle($this->language->get('text_order_single') .' '. $order_id);
				
				$this->data['full_order_info'] = $order_info;
				
				$this->data['breadcrumbs'] = [];
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),        	
				'separator' => false
				); 
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_account'),
				'href'      => $this->url->link('account/account', ''),        	
				'separator' => $this->language->get('text_separator')
				);

				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('account/order', ''),        	
					'separator' => $this->language->get('text_separator')
				);
			
				
				$url = '';
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				
				$this->data['heading_title'] = $this->language->get('text_order_single') .' '. $order_id;
				
				$this->data['text_order_detail'] = $this->language->get('text_order_detail');
				$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
				$this->data['text_order_id'] = $this->language->get('text_order_id');
				$this->data['text_date_added'] = $this->language->get('text_date_added');
				$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
				$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
				$this->data['text_payment_method'] = $this->language->get('text_payment_method');
				$this->data['text_payment_address'] = $this->language->get('text_payment_address');
				$this->data['text_history'] = $this->language->get('text_history');
				$this->data['text_comment'] = $this->language->get('text_comment');
				
				$this->data['column_name'] = $this->language->get('column_name');
				$this->data['column_model'] = $this->language->get('column_model');
				$this->data['column_quantity'] = $this->language->get('column_quantity');
				$this->data['column_price'] = $this->language->get('column_price');
				$this->data['column_total'] = $this->language->get('column_total');
				$this->data['column_action'] = $this->language->get('column_action');
				$this->data['column_date_added'] = $this->language->get('column_date_added');
				$this->data['column_status'] = $this->language->get('column_status');
				$this->data['column_comment'] = $this->language->get('column_comment');
				
				$this->data['button_return'] = $this->language->get('button_return');
				$this->data['button_continue'] = $this->language->get('button_continue');
				$this->data['button_invoice'] = $this->language->get('button_invoice');
				
				if ($order_info['invoice_no']) {
					$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
					} else {
					$this->data['invoice_no'] = '';
				}
				
				$this->data['order_id'] = $this->request->get['order_id'];
				$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
				
				$this->data['manager'] = $this->model_tool_user->getRealUserNameByID($order_info['manager_id']);
				$this->data['manager_set'] = $order_info['manager_id'];
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
					} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
				);
				
				$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
				);
				
				$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$this->data['pay_equire'] = $order_info['pay_equire'];
				$this->data['pay_equire2'] = $order_info['pay_equire2'];
				$this->data['pay_equirePP'] = $order_info['pay_equirePP'];
				$this->data['pay_equireLQP'] = $order_info['pay_equireLQP'];
				$this->data['pay_equireCP'] = $order_info['pay_equireCP'];
				$this->data['payment_method'] = $order_info['payment_method'];
				
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
					} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
				);
				
				$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
				);
				
				$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$this->data['shipping_method'] = $order_info['shipping_method'];
				
				$this->data['products'] = [];
				
				$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);
				
				$this->load->model('kp/info1c');
				$order1c = $this->model_kp_info1c->getOrderTrackerXML($this->request->get['order_id']);
				
				$products1c = [];
				
				if (isset($order1c["Документ"])) {
					$order1c = $order1c["Документ"];
					if (isset($order1c['ОбщееСостояниеЗаказа']['Товар']['Наименование'])){
						$_tmp = $order1c['ОбщееСостояниеЗаказа']['Товар'];
						unset($order1c['ОбщееСостояниеЗаказа']['Товар']);
						$order1c['ОбщееСостояниеЗаказа']['Товар'] = array($_tmp);
					}
					$products1c = $order1c['ОбщееСостояниеЗаказа']['Товар'];
				}
				
				$general_tracker_status = [];
				
				if ($order_info['order_status_id'] == $this->config->get('config_treated_status_id')){					
					$general_tracker_status[] = 'first_step';
				}
				
				if (in_array($order_info['order_status_id'], array(2, $this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id'), $this->config->get('config_total_paid_order_status_id')))){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
				}
				
				if ($order1c){				
					$is_on_third_step = false;				
					$is_on_fourth_step = true;
					$is_on_fifth_step = true;
					$is_on_sixth_step = true;
					$is_on_seventh_step = true;
					} else {
					$is_on_third_step = false;				
					$is_on_fourth_step = false;
					$is_on_fifth_step = false;
					$is_on_sixth_step = false;
					$is_on_seventh_step = false;				
				}
				foreach ($products as $product) {					
					$tracker_status = [];					
					
					foreach ($products1c as $p1c){
						if ($p1c['Код'] == $product['product_id']){
							
							// по заказу полностью	
							//если хоть один товар уже купили - то весь заказ считаем на комплектации
							if (!$is_on_third_step) {
								if ($p1c['ЗаказаноУПоставщика'] > 0 && $p1c['ЗаказаноУПоставщика'] == $product['quantity']){
									$is_on_third_step = true;									
								}
							}
							
							//транзит заказа
							if ($p1c['ВПути'] != $product['quantity']) {
								//а если что-то лежит на С.О, а что-то уже едет, то это не неправда, а все оке
								if (((int)$p1c['ВПути'] + (int)$p1c['ОжидаютОтгрузкиПокупателю']) != (int)$product['quantity']) {
									$is_on_fourth_step = false;
								}
							}
							
							//если все товары лежат на складе в городе - получателе
							if ($p1c['ОжидаютОтгрузкиПокупателю'] != $product['quantity']) {								
								$is_on_fifth_step = false;
							}
							
							
							//все уже доставлено
							if ($p1c['ДоставленоПокупателю'] != $p1c['ЗаказаноПокупателем'] || $p1c['ДоставленоПокупателю']  != $product['quantity']) {								
								$is_on_seventh_step = false;
							}
							
							
							//по товару
							
							//заказан у поставщика
							if ($p1c['ЗаказаноУПоставщика']) {
								$tracker_status[] = 'third_step';
							}
							
							//едет из германии + лежит на
							if ($p1c['ВПути'] > 0 && ($p1c['ВПути'] == $product['quantity'] || (((int)$p1c['ВПути'] + (int)$p1c['ОжидаютОтгрузкиПокупателю']) != (int)$product['quantity']))) {
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
							}
							
							//ждет на складе
							if ($p1c['ОжидаютОтгрузкиПокупателю'] == $product['quantity']) {
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
								$tracker_status[] = 'fifth_step';													
							}
							
							//уже доставлено
							if ($p1c['ДоставленоПокупателю'] == $product['quantity']) {
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
								$tracker_status[] = 'fifth_step';
								$tracker_status[] = 'sixth_step';
								$tracker_status[] = 'seventh_step';
							}
							
							//общий статус заказа
							if ($order_info['order_status_id'] == $this->config->get('config_complete_status_id')){								
								$tracker_status[] = 'third_step';
								$tracker_status[] = 'fourth_step';
								$tracker_status[] = 'fifth_step';
								$tracker_status[] = 'sixth_step';
								$tracker_status[] = 'seventh_step';
								$tracker_status[] = 'eighth_step';								
							}
							
						}
					}
					unset($p1c);
					
					if (in_array($order_info['order_status_id'], array($this->config->get('config_cancelled_status_id'), 23, 24))){
						$tracker_status = false;
					}
					
					$option_data = [];
					
					$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
					
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
							} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);					
					}
					
					if ($product['price_national'] > 0){
						$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');
						} else {
						$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
					}
					
					if ($product['total_national'] > 0){
						$total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');					
						} elseif ($product['price_national'] > 0) {
						$total = $this->currency->format($product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
						} else {
						$total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
					}
					
					
					if ($_image = $this->model_account_order->getProductImage($product['product_id'])) {
						$image = $this->model_tool_image->resize($_image, 50, 50);
						} else {
						$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 50, 50);
					}
					
					$this->data['products'][] = array(
					'name'     		=> $product['name'],
					'product_id' 	=> $product['product_id'],
					'image'    		=> $image,
					'model'    		=> $product['model'],
					'option'   		=> $option_data,
					'link'     		=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'quantity' 		=> $product['quantity'],
					'price'    		=> $price,
					'price_isnull'  => max($product['price_national'], $product['price'] ) == 0,
					'total'    		=> $total,
					'return'   		=> $this->url->link('account/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
					);
					
					
				}
				
				//Если рубли	
				if ($order_info['currency_code'] == 'RUB') {										
					
					$this->data['paykeeper_onpay'] = $this->model_payment_paykeeper->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/paykeeper/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['pp_express_onpay'] = $this->model_payment_pp_express->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/pp_express_laterpay/checkout', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['order_to_pay'] = $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1);
					//Если другая валюта		
					
					} elseif ($order_info['currency_code'] != 'UAH') {
					
					$this->data['paykeeper_onpay'] = $this->model_payment_paykeeper->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/paykeeper/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['order_to_pay'] = $this->currency->format($this->currency->convert($order_info['total_national'], $order_info['currency_code'], 'RUB'), 'RUB', 1);				
					} elseif ($order_info['currency_code'] == 'UAH'){
					
					$this->data['liqpay_onpay'] = $this->model_payment_liqpay->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/liqpay_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					
					$this->data['order_to_pay'] = $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1);
				}
				
				
				$this->data['concardis_onpay'] = $this->model_payment_concardis->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/concardis_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';								
				
				if ($order_info['currency_code'] == 'EUR') {
					
					$this->data['order_to_pay'] = round($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 2);
					$this->data['order_to_pay'] = $this->currency->format($this->data['order_to_pay'], 'EUR', 1);
					
					} elseif ($order_info['currency_code'] == 'RUB') {	
					
					$this->data['order_to_pay'] = $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1);
					
					$this->data['concardis_onpay_cc_eur'] = $this->model_payment_concardis->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/concardis_laterpay/laterpay', sprintf('cc_code=EUR&order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					$this->data['order_to_pay_cc_eur'] = round($this->currency->makeDiscountOnNumber($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 3), 2);
					$this->data['order_to_pay_cc_eur'] = $this->currency->format($this->data['order_to_pay_cc_eur'], 'EUR', 1);
					
					} elseif ($order_info['currency_code'] == 'UAH') {
					
					$this->data['order_to_pay'] = $this->currency->format($order_info['total_national'], $order_info['currency_code'], 1);
					
					$this->data['concardis_onpay_cc_eur'] = $this->model_payment_concardis->checkLaterpay($order_info['order_id']) ? $this->url->link('payment/concardis_laterpay/laterpay', sprintf('cc_code=EUR&order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL') : '';
					$this->data['order_to_pay_cc_eur'] = round($this->currency->makeDiscountOnNumber($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 3), 2);
					$this->data['order_to_pay_cc_eur'] = $this->currency->format($this->data['order_to_pay_cc_eur'], 'EUR', 1);
					
					} else {
					
					$this->data['order_to_pay'] = round($this->currency->makeDiscountOnNumber($this->currency->real_convert($order_info['total_national'], $order_info['currency_code'], 'EUR', true), 3), 2);
					$this->data['order_to_pay'] = $this->currency->format($this->data['order_to_pay'], 'EUR', 1);
					
				}							
				
				//переход на платеж по QR
				if (isset($this->request->get['do_payment']) && $this->request->get['do_payment'] == 'explicit' && isset($this->request->get['pay_by'])){					
					if ($this->request->get['pay_by'] == 'concardis'){																	
						$this->children = [];
						if (isset($this->request->get['cc_code']) && in_array($this->request->get['cc_code'], array('EUR', 'RUB', 'UAH'))){
							$this->data['url'] = $this->url->link('payment/concardis_laterpay/laterpay', sprintf('cc_code=%s&order_id=%s&order_tt=%s&order_fl=%s', $this->request->get['cc_code'], $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])));
							} else {
							$this->data['url'] = $this->url->link('payment/concardis_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])));
						}
						
						$this->children = array(
						'common/header/simpleheader',
						'common/footer/simplefooter',
						);		
						
						$this->data['image'] = $this->model_tool_image->resize('shut_up_and_take_my_money.jpg', 400, 300);
						
						$this->document->setTitle('Оплата Concardis PayEngine');
						
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/concardis_laterpay_indx.tpl')) {
							$this->template = $this->config->get('config_template') . '/template/payment/concardis_laterpay_indx.tpl';
							} else {
							$this->template = 'default/template/payment/concardis_laterpay_indx.tpl';
						}
						
						echo($this->render());				
						die();
						
						} elseif ($this->request->get['pay_by'] == 'liqpay') {
						
						$this->redirect($this->url->link('payment/liqpay_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));
						
						} elseif ($this->request->get['pay_by'] == 'wayforpay') {
						
						$this->redirect($this->url->link('payment/wayforpay_laterpay/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));
						} elseif ($this->request->get['pay_by'] == 'mono') {
						
						$this->redirect($this->url->link('payment/mono/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));

						} elseif ($this->request->get['pay_by'] == 'pp_express'){					
						
						$this->redirect($this->url->link('payment/pp_express_laterpay/checkout', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));
						
						} elseif ($this->request->get['pay_by'] == 'paykeeper'){
						
						$this->redirect($this->url->link('payment/paykeeper/laterpay', sprintf('order_id=%s&order_tt=%s&order_fl=%s', $order_info['order_id'], $order_info['total_national'], md5($order_info['firstname'] . $order_info['lastname'])), 'SSL'));
						
					}
					
				}
				
				$this->data['currency_code'] = $order_info['currency_code'];
				if ($order_info['currency_code'] == 'KZT') {
					$this->data['my_currency'] = $this->currency->format(10, $order_info['currency_code'], 1);
					$this->data['currency_cource'] = number_format($this->currency->real_convert(10, $order_info['currency_code'], 'RUB'), 2, '.', ''). ' руб.';
					} elseif($order_info['currency_code'] == 'BYN'){
					$this->data['my_currency'] = $this->currency->format(10000, $order_info['currency_code'], 1);
					$this->data['currency_cource'] = number_format($this->currency->real_convert(10000, $order_info['currency_code'], 'RUB'), 2, '.', ''). ' руб.';				
					} else {
					$this->data['my_currency'] = $this->currency->format(1, $order_info['currency_code'], 1);
					$this->data['currency_cource'] = number_format($this->currency->real_convert(1, $order_info['currency_code'], 'RUB'), 2, '.', ''). ' руб.';
				}
				
				// Voucher
				$this->data['vouchers'] = [];
				
				$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);
				
				foreach ($vouchers as $voucher) {
					$this->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}		
				
				$this->data['totals'] = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
									
				if ($is_on_third_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';						
				}
				
				if ($is_on_fourth_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
				}
				
				if ($is_on_fifth_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
				}				
				
				if ($is_on_seventh_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$general_tracker_status[] = 'seventh_step';
				}
				
				$hstrs = $this->model_account_order->getOrderHistoriesFull($this->request->get['order_id']);
				$this->data['is_full_paid'] = false;
				foreach ($hstrs as $hstr) {		
					if ($hstr['order_status_id'] == $this->config->get('config_total_paid_order_status_id')){
						$this->data['is_full_paid'] = true;
						break;
					}
				}
				
				if ($order_info['order_status_id'] == $this->config->get('config_complete_status_id')){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$general_tracker_status[] = 'seventh_step';
					$general_tracker_status[] = 'eighth_step';
					
				}
				
				
				if ($order_info['order_status_id'] == 26){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					
				}
				
				$this->data['is_on_pickpoint'] = (mb_stripos($order_info['shipping_code'], 'pickup_advanced') !== false);
				if ($order_info['order_status_id'] == 25){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$this->data['is_on_pickpoint'] = true;
					
				}				
				
				if (in_array($order_info['order_status_id'], array($this->config->get('config_cancelled_status_id'), 23, 24))){
					$general_tracker_status = false;
				}
				
				$this->data['general_tracker_status'] = $order1c?$general_tracker_status:false;
				
				$this->data['comment'] = nl2br($order_info['comment']);
				
				$this->data['histories'] = [];
				
				$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);
				
				foreach ($results as $result) {									
					
					$this->data['histories'][] = array(
					'date_added' => date( $this->language->get('date_format_short'), strtotime($result['date_added']) ),
					'status'     => $result['status'],
					'comment'    => (EmailTemplate::isHTML($result['comment'])) ? html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8') : nl2br($result['comment'], true)
					);
				}
				
				$this->data['continue'] = $this->url->link('account/order', '');
				
				$this->data['button_invoice'] = $this->language->get('button_invoice');
				
				$this->load->model('module/emailtemplate');
								
				$config = $this->model_module_emailtemplate->getConfig(array(
				'store_id' 	  => $order_info['store_id'],
				'language_id' => $order_info['language_id']
				), true, true);
				
				if ($config['invoice_download'] && $this->config->get('config_complete_status_id') == $order_info['order_status_id']) {
					$this->data['download_invoice'] = $this->url->link('account/order/invoice', 'order_id='.$order_id);
				}
				
				if ($order_info['order_status_id'] == $this->config->get('config_confirmed_order_status_id')){
					$this->data['liqpay_payment_form'] = $this->getChild('payment/liqpay');
					} else {
					$this->data['liqpay_payment_form'] = false;
				}
				
				$results = $this->model_account_order->getOrderTtnHistory($this->request->get['order_id']);
				
				$this->data['ttns'] = [];
				foreach ($results as $result) {

					$shippingMethod = '';
					if (!empty($this->registry->get('shippingmethods')[$result['delivery_code']])){
						$shippingMethod = $this->registry->get('shippingmethods')[$result['delivery_code']]['name'];
					}
					
					$this->data['ttns'][] = array(
						'order_ttn_id' 		=> $result['order_ttn_id'],
						'delivery_company' 	=> $shippingMethod,
						'delivery_code' 	=> $result['delivery_code'],
						'date_ttn' 			=> date('d.m.Y', strtotime($result['date_ttn'])),
						'ttn'    			=> $result['ttn'],				
					);
					
				}
				
				$this->data['column_date_added'] = $this->language->get('column_date_added');
				$this->data['column_description'] = $this->language->get('column_description');
				$this->data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_regional_currency'));
				
				$this->data['transactions'] = [];
				
				$data = array(				  
				'sort'  => 'date_added',
				'order_id' => $this->request->get['order_id'],
				'order' => 'DESC',			
				);
				
				$results = $this->model_account_transaction->getTransactions($data);
				
				foreach ($results as $result) {					
					$this->data['transactions'][] = array(
					'amount'      => $this->currency->format($result['amount_national'], $this->config->get('config_regional_currency'), 1),
					'description' => $result['description'],
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),					
					);
				}	
				
				$this->template = 'account/order_info.tpl';
				
				$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'	
				);
				
				$this->response->setOutput($this->render());		
				} else {
				$this->document->setTitle($this->language->get('text_order'));
				
				$this->data['heading_title'] = $this->language->get('text_order');
				
				$this->data['text_error'] = $this->language->get('text_error');
				
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['breadcrumbs'] = [];
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_account'),
				'href'      => $this->url->link('account/account', ''),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('account/order', ''),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_order'),
				'href'      => $this->url->link('account/order/info', 'order_id=' . $order_id),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['continue'] = $this->url->link('account/order', '');
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
					} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'	
				);
				
				$this->response->setOutput($this->render());				
			}
		}				
	}							
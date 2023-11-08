<?php 
	class ControllerCatalogWaitlist extends Controller {
		private $error = array(); 
		
		public function index() {
			$this->language->load('catalog/product');
			
			$this->document->setTitle('Лист ожидания'); 
			
			$this->load->model('catalog/product');
			$this->load->model('sale/order');
			
			$this->getList();
		}
		
		public function delete() {
			$this->language->load('catalog/product');
			
			$this->document->setTitle('Лист ожидания'); 
			
			$this->load->model('catalog/product');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $product_id) {
					$this->model_catalog_product->deleteProductFromWaitList($product_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_model'])) {
					$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_price'])) {
					$url .= '&filter_price=' . $this->request->get['filter_price'];
				}
				
				if (isset($this->request->get['filter_quantity'])) {
					$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
				}	
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				
				$this->redirect($this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		public function createneworder(){
			$this->language->load('catalog/product');
			
			$this->document->setTitle('Лист ожидания'); 
			
			$this->load->model('catalog/product');
			
			if (isset($this->request->post['selected']) && $this->validateNewOrder()) {
				$new_order_id = $this->model_catalog_product->createNewOrderFromWaitlist(reset($this->request->post['selected']), $this->request->post['selected']);
				
				if ($new_order_id) {
					$order_href = $this->url->link('sale/order/update', 'order_id='.$new_order_id.'&token='.$this->session->data['token']);
					$this->session->data['success'] = 'Создали заказ, номер '.$new_order_id.' <a href="'. $order_href .'" target="_blank">Открыть в новой вкладке</a>';
					} else {
					$this->session->data['error'] = 'Произошла ошибка. Либо удален старый заказ, либо прилетело НЛО';
				}
				
				$url = '';
				
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_model'])) {
					$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['filter_price'])) {
					$url .= '&filter_price=' . $this->request->get['filter_price'];
				}
				
				if (isset($this->request->get['filter_quantity'])) {
					$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
				}
				
				if (isset($this->request->get['filter_supplier_has'])) {
					$url .= '&filter_supplier_has=' . $this->request->get['filter_supplier_has'];
				}				
				
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				
				$this->redirect($this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		public function setPreWaitListAjax(){
			$order_product_id = (int)$this->request->post['order_product_id'];
			
			if ($order_product_id) {
				
				$this->db->query("UPDATE order_product_nogood SET is_prewaitlist = NOT(is_prewaitlist) WHERE order_product_id = '" .(int)$order_product_id. "'");
				$query = $this->db->query("SELECT is_prewaitlist, model, product_id FROM order_product_nogood WHERE order_product_id = '" .(int)$order_product_id. "' LIMIT 1");
				
				$this->load->model('user/user');					
				$user_name = $this->model_user_user->getRealUserNameById($this->user->getID());							
				
				echo ($query->row['is_prewaitlist'])?'1':'0';
				} else { 
				echo '0';
			}
			
			exit();			
		}
		
		public function setSupplierHasAjax(){
			$order_product_id = (int)$this->request->post['order_product_id'];
			
			if ($order_product_id) {
				
				$this->db->query("UPDATE order_product_nogood SET supplier_has = NOT(supplier_has) WHERE order_product_id = '" .(int)$order_product_id. "'");
				$query = $this->db->query("SELECT supplier_has, model, product_id FROM order_product_nogood WHERE order_product_id = '" .(int)$order_product_id. "' LIMIT 1");
				
				$this->load->model('user/user');					
				$user_name = $this->model_user_user->getRealUserNameById($this->user->getID());
				
				if ($query->row['supplier_has']){
					
					$data = array(
					'type' => 'success',
					'text' => $user_name.': товар <b>'.$query->row['model'].'</b> из листа ожидания<br />ЕСТЬ В НАЛИЧИИ!', 
					'entity_type' => 'waitlist', 
					'entity_id' => $query->row['product_id']
					);
					
					//	$this->mAlert->insertAlertForOne(2, $data);	
					$this->mAlert->insertAlertForGroup('sales', $data);
					
					} else {
					
					$data = array(
					'type' => 'warning',
					'text' => $user_name.': товара <b>'.$query->row['model'].'</b> из листа ожидания<br />НЕТ В НАЛИЧИИ!', 
					'entity_type' => 'waitlist', 
					'entity_id' => $query->row['product_id']
					);
					
					//	$this->mAlert->insertAlertForOne(2, $data);		
					$this->mAlert->insertAlertForGroup('sales', $data);
					
					
				}
				
				echo ($query->row['supplier_has'])?'1':'0';
				} else { 
				echo '0';
			}
			
			exit();		
		}
					
		protected function getList() {
			
			$this->data['heading_title'] = 'Лист ожидания'; 
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
				} else {
				$filter_name = null;
			}
			
			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
				} else {
				$filter_model = null;
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$filter_product_id = $this->request->get['filter_product_id'];
				} else {
				$filter_product_id = null;
			}
			
			if (isset($this->request->get['filter_price'])) {
				$filter_price = $this->request->get['filter_price'];
				} else {
				$filter_price = null;
			}
			
			if (isset($this->request->get['filter_quantity'])) {
				$filter_quantity = $this->request->get['filter_quantity'];
				} else {
				$filter_quantity = null;
			}
			
			if (isset($this->request->get['filter_supplier_has'])) {
				$filter_supplier_has = $this->request->get['filter_supplier_has'];
				} else {
				$filter_supplier_has = null;
			}
			
			if (isset($this->request->get['filter_order_id'])) {
				$filter_order_id = $this->request->get['filter_order_id'];
				} else {
				$filter_order_id = null;
			}
			
			if (isset($this->request->get['filter_customer_id'])) {
				$filter_customer_id = $this->request->get['filter_customer_id'];
				} else {
				$filter_customer_id = null;
			}
			
			
			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
				} else {
				$filter_status = null;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'opn.is_prewaitlist DESC, o.date_added';
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
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}
			
			if (isset($this->request->get['filter_supplier_has'])) {
				$url .= '&filter_supplier_has=' . $this->request->get['filter_supplier_has'];
			}		
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => 'Лист ожидания',
			'href'      => $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . $url),       		
			'separator' => ' :: '
			);
			
			
			$this->data['products'] = array();
			
			$data = array(
			'filter_name'	  => $filter_name,
			'filter_order_id' => $filter_order_id, 			
			'filter_model'	  => $filter_model,
			'filter_product_id'	  => $filter_product_id,
			'filter_customer_id'	  => $filter_customer_id,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_supplier_has' => $filter_supplier_has,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
			);
			
			$this->load->model('tool/image');
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			
			$product_total = $this->model_catalog_product->getTotalProductsWaitList($data);
			$results = $this->model_catalog_product->getProductsWaitList($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
				$special = false;
				
				$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
				foreach ($product_specials  as $product_special) {
					if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
						$special = $product_special['price'];
						break;
					}					
				}
				
				$order = $this->model_sale_order->getOrder($result['order_id']);
				$this->load->model('setting/setting');
				
				if ($order){
					$order_currency = $order['currency_code'];
					$store_url = $this->model_setting_setting->getKeySettingValue('config', 'config_url', (int)$order['store_id']);
				} else {
					$order_currency = $result['currency'];
					$store_url = $this->model_setting_setting->getKeySettingValue('config', 'config_url', (int)$result['store_id']);
					
					$order = array(
						'date_added' => $result['date_added'],
						'customer_id' => $result['customer_id']
					);
				}
				
				$orders = array();
				$total_quantity = 0;
				$results_orders = $this->model_catalog_product->getProductWaitListOrders($result['product_id']);
				foreach ($results_orders as $result_order){
					$orders[] = array(
					'order_id' => $result_order['order_id']?$result_order['order_id']:'Заявка',
					'quantity' => $result_order['quantity'],
					'order_filter_href' => $result_order['order_id']?$this->url->link('catalog/waitlist', 'filter_order_id='.$result_order['order_id'].'&token=' . $this->session->data['token'],   'SSL'):false
					);
					$total_quantity += $result_order['quantity'];
				}
				
				$customer = array();
				if ($result['order_id']){
					$customer = $this->model_sale_customer->getCustomer($order['customer_id']);				
					} else {
					$customer = $this->model_sale_customer->getCustomer($result['customer_id']);
					
					
					if (!$customer){
						$customer = array(
						'telephone' => $result['telephone'],
						'firstname' => $result['firstname'],
						'customer_id' => 0,
						'lastname' => $result['lastname'],
						'customer_href' => false
						);
					}
					
				}
				
				$cdata = array(
				'filter_customer_id' => $order['customer_id']
				);			
				
				$total_customer_products =  $this->model_catalog_product->getTotalProductsWaitList($cdata);
				$total_customer_orders =  $this->model_catalog_product->getTotalOrdersWaitList($cdata);
				
				$on_stock = false;
				if (in_array($result['store_id'], array(0,2,5)) && ($result['quantity_stockM'] > 0 || $result['quantity_stock'] > 0)){
					$on_stock = true;
				}
				
				if (in_array($result['store_id'], array(1)) && ($result['quantity_stockK'] > 0 || $result['quantity_stock'] > 0)){
					$on_stock = true;
				}
				
				$this->data['products'][] = array(
				'product_id' 			=> $result['product_id'],
				'order_product_id' 		=> $result['order_product_id'],
				'name'       			=> $result['name'],
				'is_prewaitlist'      	=> $result['is_prewaitlist'],
				'model'      			=> $result['model'],
				'ean'      				=> $result['ean'],
				'asin'      			=> $result['asin'],
				'source'      			=> $result['source'],
				'price'      			=> $this->currency->format($result['price'], $this->config->get('config_currency'), 1),
				'price_national' 		=> $this->currency->format($result['price'], $order_currency),
				'special'    			=> $special?($this->currency->format($special, $this->config->get('config_currency'), 1)):false,
				'special_national' 		=> $special?($this->currency->format($special, $order_currency)):false,
				'price_in_order'	 	=> $this->currency->format($result['price_in_order'], $order_currency, 1),
				'product_filter_url' 	=> $this->url->link('catalog/waitlist', 'filter_product_id='.$result['product_id'].'&token=' . $this->session->data['token']),
				'image'      			=> $image,
				'quantity'   			=> $result['quantity'],
				'total_quantity' 		=> $total_quantity,
				'orders' 				=> $orders,
				'order' 				=> $order,
				'customer' 				=> $customer,
				'supplier_has' 			=> $result['supplier_has'],
				'on_stock' 				=> $on_stock,
				'order_id'   			=> $result['order_id'],			
				'status'     			=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'  			=> isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				
				'admin_product_url' 	=> $this->url->link('catalog/product/update', 'product_id='.$result['product_id'].'&token=' . $this->session->data['token']),
				'admin_order_url' 		=> $result['order_id']?$this->url->link('sale/order/update', 'order_id=' . $result['order_id'] . '&token=' . $this->session->data['token'], 'SSL'):false,
				'admin_filter_url' 		=> $this->url->link('catalog/waitlist', 'filter_order_id=' . $result['order_id']  .'&token=' . $this->session->data['token']),
				'admin_customer_filter_url' => $order['customer_id']?$this->url->link('catalog/waitlist', 'filter_customer_id=' . $order['customer_id'] . '&token=' . $this->session->data['token'], 'SSL'):false,
				
				'customer_total_products' 	=> $total_customer_products,
				'customer_total_orders' 	=> $total_customer_orders,
				
				'admin_customer_url' => $this->url->link('sale/customer/update', 'customer_id='.$order['customer_id'].'&token=' . $this->session->data['token']),
				);
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
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}
			
			if (isset($this->request->get['filter_supplier_has'])) {
				$url .= '&filter_supplier_has=' . $this->request->get['filter_supplier_has'];
			}		
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['delete'] = $this->url->link('catalog/waitlist/delete', 'token=' . $this->session->data['token'] . $url);
			$this->data['createneworder'] = $this->url->link('catalog/waitlist/createneworder', 'token=' . $this->session->data['token'] . $url);
			
			$this->data['button_delete'] = $this->language->get('button_delete');		
			$this->data['button_filter'] = $this->language->get('button_filter');
			
			$this->data['sort_name'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url);
			$this->data['sort_model'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url);
			$this->data['sort_price'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url);
			$this->data['sort_quantity'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url);
			$this->data['sort_status'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url);
			$this->data['sort_order'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url);
			
			$this->data['token'] = $this->session->data['token'];
			
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}
			
			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}
			
			if (isset($this->request->get['filter_supplier_has'])) {
				$url .= '&filter_supplier_has=' . $this->request->get['filter_supplier_has'];
			}		
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_name'] = $filter_name;
			$this->data['filter_order_id'] = $filter_order_id;
			$this->data['filter_customer_id'] = $filter_customer_id;
			$this->data['filter_model'] = $filter_model;
			$this->data['filter_product_id'] = $filter_product_id;
			$this->data['filter_price'] = $filter_price;
			$this->data['filter_quantity'] = $filter_quantity;
			$this->data['filter_supplier_has'] = $filter_supplier_has;
			$this->data['filter_status'] = $filter_status;
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			
			if (isset($this->request->get['ajax']) && ($this->request->get['ajax'] == 1)){
				$this->template = 'catalog/waitlist_ajax.tpl';
				} else {
				$this->template = 'catalog/waitlist.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);			
			}
			
			
			$this->response->setOutput($this->render());
		}
		
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/waitlist')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateNewOrder() {
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		
	}
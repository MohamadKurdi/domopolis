<?php  
	class ControllerSaleCoupon extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('sale/coupon');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/coupon');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('sale/coupon');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/coupon');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_coupon->addCoupon($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
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
				
				$this->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('sale/coupon');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/coupon');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_coupon->editCoupon($this->request->get['coupon_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
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
				
				$this->redirect($this->url->link('sale/coupon/update', 'coupon_id='. $this->request->get['coupon_id'] .'&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('sale/coupon');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/coupon');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $coupon_id) {
					$this->model_sale_coupon->deleteCoupon($coupon_id);
					$this->load->model('module/affiliate');
					$this->model_module_affiliate->dellTrackingCoupon($coupon_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
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
				
				$this->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'date_end';
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
			'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('sale/coupon/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('sale/coupon/delete', 'token=' . $this->session->data['token'] . $url);
			
			$this->data['coupons'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$coupon_total = $this->model_sale_coupon->getTotalCoupons();
			
			$results = $this->model_sale_coupon->getCoupons($data);
			
			$this->load->model('catalog/actiontemplate');
			$this->load->model('catalog/actions');
			$this->load->model('user/user');
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/coupon/update', 'token=' . $this->session->data['token'] . '&coupon_id=' . $result['coupon_id'] . $url, 'SSL')
				);
				
				$actiontemplate = $this->model_catalog_actiontemplate->getActionTemplateName($result['actiontemplate_id']);
				$linkedaction = $this->model_catalog_actions->getActionsName($result['action_id']);
			/*	$actiontemplate_count = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateSendCount($result['actiontemplate_id']):0;
				$actiontemplate_dates = $result['actiontemplate_id']?$this->model_catalog_actiontemplate->getActionTemplateDatesCount($result['actiontemplate_id']):0;
			*/
				
				$this->data['coupons'][] = array(
				'coupon_id'  				=> $result['coupon_id'],
				'name'       				=> $result['name'],
				'type'       				=> $result['type'],
				'currency'       			=> $result['currency'],
				'code'       				=> $result['code'],
				'random'       				=> $result['random'],
				'random_count' 				=> $result['random']?$this->couponRandom->getCouponRandomCount($result['code']):0,
				'birthday'       			=> $result['birthday'],
				'only_in_stock'       		=> $result['only_in_stock'],
				'days_from_send'       		=> $result['days_from_send'],
				'discount'   				=> $result['discount'],
				'show_in_segments'   		=> ($result['show_in_segments']),
				'manager'   				=> ($result['manager_id']?$this->model_user_user->getRealUserNameById($result['manager_id']):false),
				'promo_type'   				=> $result['promo_type'],
				'display_list'   			=> $result['display_list'],
				'display_in_account'   		=> $result['display_in_account'],
				'actiontemplate'   			=> $actiontemplate,
				'linkedaction'   			=> $linkedaction,
			//	'actiontemplate_count'   	=> $actiontemplate_count,
			//	'actiontemplate_date_from' 	=> date('Y.m.d', strtotime($actiontemplate_dates['min_date'])),
			//	'actiontemplate_date_to' 	=> date('Y.m.d', strtotime($actiontemplate_dates['max_date'])),
				'usage_good'      			=> $this->model_sale_coupon->getGoodCouponUsage($result['code']),
				'usage_bad'      			=> $this->model_sale_coupon->getBadCouponUsage($result['code']),
				'date_start' 				=> date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   				=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'status'     				=> ($result['status']),
				'selected'   				=> isset($this->request->post['selected']) && in_array($result['coupon_id'], $this->request->post['selected']),
				'action'     				=> $action
				);
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_code'] = $this->language->get('column_code');
			$this->data['column_discount'] = $this->language->get('column_discount');
			$this->data['column_date_start'] = $this->language->get('column_date_start');
			$this->data['column_date_end'] = $this->language->get('column_date_end');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_action'] = $this->language->get('column_action');		
			$this->data['button_insert_tracking'] = $this->language->get('button_insert_tracking');
			$this->data['inserttracking'] = $this->url->link('sale/coupon/inserttracking', 'token=' . $this->session->data['token'] . $url);
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');
			
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
			
			$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . '&sort=name' . $url;
			$this->data['sort_code'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . '&sort=code' . $url;
			$this->data['sort_discount'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . '&sort=discount' . $url;
			$this->data['sort_date_start'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . '&sort=date_start' . $url;
			$this->data['sort_date_end'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . '&sort=date_end' . $url;
			$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . '&sort=status' . $url;
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $coupon_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = HTTPS_SERVER . 'index.php?route=sale/coupon&token=' . $this->session->data['token'] . $url . '&page={page}';
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'sale/coupon_list.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			$this->data['text_percent'] = $this->language->get('text_percent');
			$this->data['text_amount'] = $this->language->get('text_amount');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_description'] = $this->language->get('entry_description');
			$this->data['entry_code'] = $this->language->get('entry_code');
			$this->data['entry_discount'] = $this->language->get('entry_discount');
			$this->data['entry_logged'] = $this->language->get('entry_logged');
			$this->data['entry_shipping'] = $this->language->get('entry_shipping');
			$this->data['entry_type'] = $this->language->get('entry_type');
			$this->data['entry_total'] = $this->language->get('entry_total');
			$this->data['entry_category'] = $this->language->get('entry_category');
			$this->data['entry_product'] = $this->language->get('entry_product');
			$this->data['entry_date_start'] = $this->language->get('entry_date_start');
			$this->data['entry_date_end'] = $this->language->get('entry_date_end');
			$this->data['entry_uses_total'] = $this->language->get('entry_uses_total');
			$this->data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
			$this->data['entry_status'] = $this->language->get('entry_status');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['tab_history'] = $this->language->get('tab_history');
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['coupon_id'])) {
				$this->data['coupon_id'] = $this->request->get['coupon_id'];
				} else {
				$this->data['coupon_id'] = 0;
			}
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
				} else {
				$this->data['error_name'] = '';
			}
			
			if (isset($this->error['code'])) {
				$this->data['error_code'] = $this->error['code'];
				} else {
				$this->data['error_code'] = '';
			}		
			
			if (isset($this->error['date_start'])) {
				$this->data['error_date_start'] = $this->error['date_start'];
				} else {
				$this->data['error_date_start'] = '';
			}	
			
			if (isset($this->error['date_end'])) {
				$this->data['error_date_end'] = $this->error['date_end'];
				} else {
				$this->data['error_date_end'] = '';
			}	
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['coupon_id'])) {
				$this->data['action'] = $this->url->link('sale/coupon/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('sale/coupon/update', 'token=' . $this->session->data['token'] . '&coupon_id=' . $this->request->get['coupon_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['coupon_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
				$coupon_info = $this->model_sale_coupon->getCoupon($this->request->get['coupon_id']);
			}
			
			
			$this->load->model('localisation/currency');
			$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
			
			$this->load->model('user/user');
			$this->data['managers'] = $this->model_user_user->getUsersByGroup(14, true);
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} elseif (!empty($coupon_info)) {
				$this->data['name'] = $coupon_info['name'];
				} else {
				$this->data['name'] = '';
			}
			
			if (isset($this->request->post['code'])) {
				$this->data['code'] = $this->request->post['code'];
				} elseif (!empty($coupon_info)) {
				$this->data['code'] = $coupon_info['code'];
				} else {
				$this->data['code'] = '';
			}

			if (isset($this->request->post['random'])) {
				$this->data['random'] = $this->request->post['random'];
				} elseif (!empty($coupon_info)) {
				$this->data['random'] = $coupon_info['random'];
				} else {
				$this->data['random'] = '';
			}

			if (isset($this->request->post['random_string'])) {
				$this->data['random_string'] = $this->request->post['random_string'];
				} elseif (!empty($coupon_info)) {
				$this->data['random_string'] = $coupon_info['random_string'];
				} else {
				$this->data['random_string'] = '';
			}
			
			if (isset($this->request->post['promo_type'])) {
				$this->data['promo_type'] = $this->request->post['promo_type'];
				} elseif (!empty($coupon_info)) {
				$this->data['promo_type'] = $coupon_info['promo_type'];
				} else {
				$this->data['promo_type'] = '';
			}
			
			if (isset($this->request->post['manager_id'])) {
				$this->data['manager_id'] = $this->request->post['manager_id'];
				} elseif (!empty($coupon_info)) {
				$this->data['manager_id'] = $coupon_info['manager_id'];
				} else {
				$this->data['manager_id'] = 0;
			}
			
			if (isset($this->request->post['type'])) {
				$this->data['type'] = $this->request->post['type'];
				} elseif (!empty($coupon_info)) {
				$this->data['type'] = $coupon_info['type'];
				} else {
				$this->data['type'] = '';
			}
			
			if (isset($this->request->post['discount'])) {
				$this->data['discount'] = $this->request->post['discount'];
				} elseif (!empty($coupon_info)) {
				$this->data['discount'] = $coupon_info['discount'];
				} else {
				$this->data['discount'] = '';
			}
			
			if (isset($this->request->post['discount_sum'])) {
				$this->data['discount_sum'] = $this->request->post['discount_sum'];
				} elseif (!empty($coupon_info)) {
				$this->data['discount_sum'] = $coupon_info['discount_sum'];
				} else {
				$this->data['discount_sum'] = '';
			}
			
			if (isset($this->request->post['currency'])) {
				$this->data['currency'] = $this->request->post['currency'];
				} elseif (!empty($coupon_info)) {
				$this->data['currency'] = $coupon_info['currency'];
				} else {
				$this->data['currency'] = '';
			}

			if (isset($this->request->post['min_currency'])) {
				$this->data['min_currency'] = $this->request->post['min_currency'];
				} elseif (!empty($coupon_info)) {
				$this->data['min_currency'] = $coupon_info['min_currency'];
				} else {
				$this->data['min_currency'] = '';
			}
			
			if (isset($this->request->post['logged'])) {
				$this->data['logged'] = $this->request->post['logged'];
				} elseif (!empty($coupon_info)) {
				$this->data['logged'] = $coupon_info['logged'];
				} else {
				$this->data['logged'] = '';
			}
			
			if (isset($this->request->post['shipping'])) {
				$this->data['shipping'] = $this->request->post['shipping'];
				} elseif (!empty($coupon_info)) {
				$this->data['shipping'] = $coupon_info['shipping'];
				} else {
				$this->data['shipping'] = '';
			}
			
			if (isset($this->request->post['total'])) {
				$this->data['total'] = $this->request->post['total'];
				} elseif (!empty($coupon_info)) {
				$this->data['total'] = $coupon_info['total'];
				} else {
				$this->data['total'] = '';
			}
			
			if (isset($this->request->post['coupon_product'])) {
				$products = $this->request->post['coupon_product'];
				} elseif (isset($this->request->get['coupon_id'])) {		
				$products = $this->model_sale_coupon->getCouponProducts($this->request->get['coupon_id']);
				} else {
				$products = array();
			}
			
			$this->load->model('catalog/product');
			
			$this->data['coupon_product'] = array();
			
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					$this->data['coupon_product'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
					);
				}
			}
			
			if (isset($this->request->post['coupon_category'])) {
				$categories = $this->request->post['coupon_category'];
				} elseif (isset($this->request->get['coupon_id'])) {		
				$categories = $this->model_sale_coupon->getCouponCategories($this->request->get['coupon_id']);
				} else {
				$categories = array();
			}
			
			$this->load->model('catalog/category');
			
			$this->data['coupon_category'] = array();
			
			foreach ($categories as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);
				
				if ($category_info) {
					$this->data['coupon_category'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
					);
				}
			}
			
			
			if (isset($this->request->post['coupon_collection'])) {
				$collections = $this->request->post['coupon_collection'];
				} elseif (isset($this->request->get['coupon_id'])) {		
				$collections = $this->model_sale_coupon->getCouponCollections($this->request->get['coupon_id']);
				} else {
				$collections = array();
			}
			
			$this->load->model('catalog/collection');
			
			$this->data['coupon_collection'] = array();
			
			foreach ($collections as $collection_id) {
				$collection_info = $this->model_catalog_collection->getCollection($collection_id);
				
				if ($collection_info) {
					$this->data['coupon_collection'][] = array(
					'collection_id' => $collection_info['collection_id'],
					'name'       => $collection_info['name']
					);
				}
			}
			
			if (isset($this->request->post['coupon_manufacturer'])) {
				$manufacturers = $this->request->post['coupon_manufacturer'];
				} elseif (isset($this->request->get['coupon_id'])) {		
				$manufacturers = $this->model_sale_coupon->getCouponManufacturers($this->request->get['coupon_id']);
				} else {
				$manufacturers = array();
			}
			
			$this->load->model('catalog/manufacturer');
			
			$this->data['coupon_manufacturer'] = array();
			
			foreach ($manufacturers as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				
				if ($manufacturer_info) {
					$this->data['coupon_manufacturer'][] = array(
					'manufacturer_id' => $manufacturer_info['manufacturer_id'],
					'name'       => $manufacturer_info['name']
					);
				}
			}
			
			$this->load->model('catalog/actiontemplate');
			$this->data['actiontemplates'] = $this->model_catalog_actiontemplate->getactiontemplates();
			
			$this->load->model('catalog/actions');
			$this->data['actions'] = $this->model_catalog_actions->getAllActions();
			
			if (isset($this->request->post['date_start'])) {
				$this->data['date_start'] = $this->request->post['date_start'];
				} elseif (!empty($coupon_info)) {
				$this->data['date_start'] = date('Y-m-d', strtotime($coupon_info['date_start']));
				} else {
				$this->data['date_start'] = date('Y-m-d', time());
			}
			
			if (isset($this->request->post['date_end'])) {
				$this->data['date_end'] = $this->request->post['date_end'];
				} elseif (!empty($coupon_info)) {
				$this->data['date_end'] = date('Y-m-d', strtotime($coupon_info['date_end']));
				} else {
				$this->data['date_end'] = date('Y-m-d', time());
			}
			
			if (isset($this->request->post['uses_total'])) {
				$this->data['uses_total'] = $this->request->post['uses_total'];
				} elseif (!empty($coupon_info)) {
				$this->data['uses_total'] = $coupon_info['uses_total'];
				} else {
				$this->data['uses_total'] = 1;
			}
			
			if (isset($this->request->post['uses_customer'])) {
				$this->data['uses_customer'] = $this->request->post['uses_customer'];
				} elseif (!empty($coupon_info)) {
				$this->data['uses_customer'] = $coupon_info['uses_customer'];
				} else {
				$this->data['uses_customer'] = 1;
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (!empty($coupon_info)) {
				$this->data['status'] = $coupon_info['status'];
				} else {
				$this->data['status'] = 1;
			}
			
			$this->load->model('localisation/language');		
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['coupon_description'])) {
				$this->data['coupon_description'] = $this->request->post['coupon_description'];
				} elseif (isset($this->request->get['coupon_id'])) {
				$this->data['coupon_description'] = $this->model_sale_coupon->getCouponDescriptions($this->request->get['coupon_id']);
				} else {
				$this->data['coupon_description'] = array();
			}
			
			if (isset($this->request->post['display_list'])) {
				$this->data['display_list'] = $this->request->post['display_list'];
				} elseif (!empty($coupon_info)) {
				$this->data['display_list'] = $coupon_info['display_list'];
				} else {
				$this->data['display_list'] = 0;
			}
			
			if (isset($this->request->post['display_in_account'])) {
				$this->data['display_in_account'] = $this->request->post['display_in_account'];
				} elseif (!empty($coupon_info)) {
				$this->data['display_in_account'] = $coupon_info['display_in_account'];
				} else {
				$this->data['display_in_account'] = 0;
			}
			
			
			if (isset($this->request->post['show_in_segments'])) {
				$this->data['show_in_segments'] = $this->request->post['show_in_segments'];
				} elseif (!empty($coupon_info)) {
				$this->data['show_in_segments'] = $coupon_info['show_in_segments'];
				} else {
				$this->data['show_in_segments'] = 1;
			}
			
			if (isset($this->request->post['birthday'])) {
				$this->data['birthday'] = $this->request->post['birthday'];
				} elseif (!empty($coupon_info)) {
				$this->data['birthday'] = $coupon_info['birthday'];
				} else {
				$this->data['birthday'] = 0;
			}
			
			if (isset($this->request->post['only_in_stock'])) {
				$this->data['only_in_stock'] = $this->request->post['only_in_stock'];
				} elseif (!empty($coupon_info)) {
				$this->data['only_in_stock'] = $coupon_info['only_in_stock'];
				} else {
				$this->data['only_in_stock'] = 0;
			}
			
			if (isset($this->request->post['days_from_send'])) {
				$this->data['days_from_send'] = $this->request->post['days_from_send'];
				} elseif (!empty($coupon_info)) {
				$this->data['days_from_send'] = $coupon_info['days_from_send'];
				} else {
				$this->data['days_from_send'] = 0;
			}
			
			if (isset($this->request->post['actiontemplate_id'])) {
				$this->data['actiontemplate_id'] = $this->request->post['actiontemplate_id'];
				} elseif (!empty($coupon_info)) {
				$this->data['actiontemplate_id'] = $coupon_info['actiontemplate_id'];
				} else {
				$this->data['actiontemplate_id'] = 0;
			}
			
			if (isset($this->request->post['action_id'])) {
				$this->data['action_id'] = $this->request->post['action_id'];
				} elseif (!empty($coupon_info)) {
				$this->data['action_id'] = $coupon_info['action_id'];
				} else {
				$this->data['action_id'] = 0;
			}
			
			
			$this->template = 'sale/coupon_form.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);
			
			$this->response->setOutput($this->render());		
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'sale/coupon')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
				$this->error['name'] = $this->language->get('error_name');
			}
			
			if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 25)) {
				$this->load->model('module/affiliate');
				if($this->model_module_affiliate->isAffilateCoupon($this->request->get['coupon_id'])){
					$this->error['code'] = $this->language->get('error_code');
				} 
			}
			
			$coupon_info = $this->model_sale_coupon->getCouponByCode($this->request->post['code']);
			
			if ($coupon_info) {
				if (!isset($this->request->get['coupon_id'])) {
					$this->error['warning'] = $this->language->get('error_exists');
					} elseif ($coupon_info['coupon_id'] != $this->request->get['coupon_id'])  {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'sale/coupon')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		public function history() {
			$this->language->load('sale/coupon');
			
			$this->load->model('sale/coupon');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_order_id'] = $this->language->get('column_order_id');
			$this->data['column_customer'] = $this->language->get('column_customer');
			$this->data['column_amount'] = $this->language->get('column_amount');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}  
			
			$this->data['histories'] = array();
			
			$results = $this->model_sale_coupon->getCouponHistories($this->request->get['coupon_id'], ($page - 1) * 10, 10);
			
			foreach ($results as $result) {
				$this->data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
			}
			
			$history_total = $this->model_sale_coupon->getTotalCouponHistories($this->request->get['coupon_id']);
			
			$pagination = new Pagination();
			$pagination->total = $history_total;
			$pagination->page = $page;
			$pagination->limit = 10; 
			$pagination->url = $this->url->link('sale/coupon/history', 'token=' . $this->session->data['token'] . '&coupon_id=' . $this->request->get['coupon_id'] . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'sale/coupon_history.tpl';		
			
			$this->response->setOutput($this->render());
		}
		
		public function inserttracking() {
			$this->language->load('sale/coupon');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('sale/coupon');
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->load->model('module/affiliate');
				$this->model_module_affiliate->addTrackingCoupon($this->request->post);
				$this->session->data['success'] = $this->language->get('text_success');
				
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
				
				//	$this->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getFormTracking();
		}
		
		private function getFormTracking() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			$this->data['text_percent'] = $this->language->get('text_percent');
			$this->data['text_amount'] = $this->language->get('text_amount');
			
			$this->data['entry_discount'] = $this->language->get('entry_discount');
			$this->data['entry_type'] = $this->language->get('entry_type');
			$this->data['entry_total'] = $this->language->get('entry_total');
			$this->data['entry_product'] = $this->language->get('entry_product');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['tab_coupon_history'] = $this->language->get('tab_coupon_history');
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->get['coupon_id'])) {
				$this->data['coupon_id'] = $this->request->get['coupon_id'];
				} else {
				$this->data['coupon_id'] = 0;
			}
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}	
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url),
      		'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('sale/coupon/inserttracking', 'token=' . $this->session->data['token'] . $url);
			
			
			$this->data['cancel'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['coupon_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
				$coupon_info = $this->model_sale_coupon->getCoupon($this->request->get['coupon_id']);
			}
			
			
			
			if (isset($this->request->post['type'])) {
				$this->data['type'] = $this->request->post['type'];
				} elseif (!empty($coupon_info)) {
				$this->data['type'] = $coupon_info['type'];
				} else {
				$this->data['type'] = '';
			}
			
			if (isset($this->request->post['discount'])) {
				$this->data['discount'] = $this->request->post['discount'];
				} elseif (!empty($coupon_info)) {
				$this->data['discount'] = $coupon_info['discount'];
				} else {
				$this->data['discount'] = '';
			}
			
			if (isset($this->request->post['total'])) {
				$this->data['total'] = $this->request->post['total'];
				} elseif (!empty($coupon_info)) {
				$this->data['total'] = $coupon_info['total'];
				} else {
				$this->data['total'] = '';
			}
			
			
			$this->template = 'sale/coupon_tracking.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);
			
			$this->response->setOutput($this->render());		
		}
	}
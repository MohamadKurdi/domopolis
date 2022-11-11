<?php
	class ControllerModuleCustomerMenu extends Controller {
		
		public function login(){
			
			$this->language->load('checkout/simplecheckout');
			$this->language->load('common/header');
			
			$json = array();
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				if (!empty($this->request->post['email']) && !empty($this->request->post['password']) && $this->customer->login($this->request->post['email'], $this->request->post['password'])) {
					$this->data['success'] = true;					
					} else {
					$this->data['error_try'] = true;
					$this->data['success'] = false;
					echo 'invalid';
				}
			}
			
			$this->data['action'] = $this->url->link('account/login', '', 'SSL');
			$this->data['register'] = $this->url->link('account/register', '', 'SSL');
			$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
			
			if ($this->data['success']){
				
				$this->data['link_name'] = $this->url->link('account/simpleedit', '', 'SSL');
				$this->data['text_name'] = $this->language->get('text_name');
				$this->data['user_name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
				
				$this->data['link_discount'] = $this->url->link('account/rewards', '', 'SSL');
				$this->data['text_discount'] = $this->language->get('text_discount');
				
				$this->data['text_actions'] = $this->language->get('actions');
				$this->data['text_new_products'] = $this->language->get('new_products');
				$this->data['text_my_card'] = $this->language->get('my_card');
				$this->data['text_no_discount_card'] = $this->language->get('no_discount_card');
				$this->data['text_how_get'] = $this->language->get('how_get');
				$this->data['text_login_b2b'] = $this->language->get('login_b2b');
				$this->data['home'] = $this->url->link('common/home');
				
				$this->data['discount_card'] = $this->customer->getDiscountCard();
				$this->data['get_discount_link'] = $this->url->link('information/information', 'information_id=19', 'SSL');	
				
				$this->load->model('total/shoputils_cumulative_discounts');
				$this->data['current_discount'] = $this->model_total_shoputils_cumulative_discounts->getLoggedCustomerDiscount();
				$this->data['text_current_discount'] = sprintf($this->language->get('text_current_discount'), $this->data['current_discount']['percent']);
				
				$this->load->model('account/order');
				$this->data['link_orders'] = $this->url->link('account/order', '', 'SSL');
				$count_orders = $this->model_account_order->getTotalOrders();
				$this->data['text_orders'] = sprintf($this->language->get('text_orders'), $count_orders);
				if ($count_orders == 0) {
					$this->data['text_count_orders'] = $this->language->get('text_count_no_orders');
					} else {
					$this->data['text_count_orders'] = sprintf($this->language->get('text_count_orders'), $this->model_account_order->getTotalOrders());			
				}
				
				
				$this->data['compare'] = $this->url->link('product/compare', '', 'SSL');
				$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				$this->data['text_count_compare'] = $this->language->get('text_count_compare');
				$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
				$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
				$this->data['text_count_wishlist'] = $this->language->get('text_count_wishlist');
				
				$this->load->model('catalog/viewed');
				$this->data['viewed'] = $this->url->link('account/viewed', '', 'SSL');
				$total_viewed = $this->model_catalog_viewed->getTotalViewed();
				$this->data['text_viewed'] = sprintf($this->language->get('text_viewed'), $total_viewed);
				$this->data['text_count_viewed'] = sprintf($this->language->get('text_count_viewed'), $total_viewed);
				
				$this->data['link_logout'] = $this->url->link('account/logout', '', 'SSL');
				$this->data['text_logout'] = $this->language->get('text_logout');
				
				
				$this->template = $this->config->get('config_template') . '/template/module/customermenu.tpl';
				echo $this->render();
			}
			
			
		}
		
		
		protected function index() {
			
			//МЕНЮ Аккаунта
			if ($this->customer->isLogged()){
				
				$this->language->load('checkout/simplecheckout');
				$this->language->load('common/header');
				
				$this->data['link_name'] = $this->url->link('account/simpleedit', '', 'SSL');
				$this->data['text_name'] = $this->language->get('text_name');
				$this->data['user_name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
				
				$this->data['link_discount'] = $this->url->link('account/rewards', '', 'SSL');
				$this->data['text_discount'] = $this->language->get('text_discount');
				
				$this->data['discount_card'] = $this->customer->getDiscountCard();
				$this->data['get_discount_link'] = $this->url->link('information/information', 'information_id=19', 'SSL');	
				
				$this->data['text_actions'] = $this->language->get('actions');
				$this->data['text_new_products'] = $this->language->get('new_products');
				$this->data['text_my_card'] = $this->language->get('my_card');
				$this->data['text_no_discount_card'] = $this->language->get('no_discount_card');
				$this->data['text_how_get'] = $this->language->get('how_get');
				$this->data['text_login_b2b'] = $this->language->get('login_b2b');
				$this->data['home'] = $this->url->link('common/home');
				
				$this->load->model('total/shoputils_cumulative_discounts');
				$this->data['current_discount'] = $this->model_total_shoputils_cumulative_discounts->getLoggedCustomerDiscount();
				$this->data['text_current_discount'] = sprintf($this->language->get('text_current_discount'), $this->data['current_discount']['percent']);
				
				$this->load->model('account/order');
				$this->data['link_orders'] = $this->url->link('account/order', '', 'SSL');
				$count_orders = $this->model_account_order->getTotalOrders();
				$this->data['text_orders'] = sprintf($this->language->get('text_orders'), $count_orders);
				if ($count_orders == 0) {
					$this->data['text_count_orders'] = $this->language->get('text_count_no_orders');
					} else {
					$this->data['text_count_orders'] = sprintf($this->language->get('text_count_orders'), $this->model_account_order->getTotalOrders());			
				}
				
				$this->data['compare'] = $this->url->link('product/compare', '', 'SSL');
				$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				$this->data['text_count_compare'] = $this->language->get('text_count_compare');
				$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
				$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
				$this->data['text_count_wishlist'] = $this->language->get('text_count_wishlist');
				
				$this->load->model('catalog/viewed');
				$this->data['viewed'] = $this->url->link('account/viewed', '', 'SSL');
				$total_viewed = $this->model_catalog_viewed->getTotalViewed();
				$this->data['text_viewed'] = sprintf($this->language->get('text_viewed'), $total_viewed);
				$this->data['text_count_viewed'] = sprintf($this->language->get('text_count_viewed'), $total_viewed);
				
				$this->data['link_logout'] = $this->url->link('account/logout', '', 'SSL');
				$this->data['text_logout'] = $this->language->get('text_logout');
				
			}
			
			$this->data['action'] = $this->url->link('account/login', '', 'SSL');
			$this->data['register'] = $this->url->link('account/register', '', 'SSL');
			$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
			
			$this->template = $this->config->get('config_template') . '/template/module/customermenu.tpl';
			$this->render();
			
		}
	}					
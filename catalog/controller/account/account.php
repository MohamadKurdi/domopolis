<?php 
	class ControllerAccountAccount extends Controller { 
		public function index() {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
				
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/account');
			$this->load->model('account/address');
			$this->load->model('account/customer');
			
			foreach ($this->language->loadRetranslate('account/account') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}						
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['customer_name'] = $this->customer->getFirstName();		
			$this->data['discount_card'] = $this->customer->getDiscountCard();
			
			$customer_info = $this->model_account_customer->getCustomer($this->customer->isLogged());
			
			$this->data['get_discount_link'] = $this->url->link('information/information', 'information_id=32', 'SSL');
			
			//	$this->load->model('total/shoputils_cumulative_discounts');
			//	$this->data['current_discount'] = $this->model_total_shoputils_cumulative_discounts->getLoggedCustomerDiscount();
			
			$this->data['points_active'] = $this->customer->getRewardPoints();
			$this->data['points_active_formatted'] = $this->currency->formatBonus($this->data['points_active']);
			$this->data['points_active_formatted_as_currency'] = $this->currency->format($this->data['points_active'], $this->config->get('config_regional_currency'), 1);
			
			$this->data['is_opt'] = ($this->customer->getCustomerGroupId() != $this->config->get('config_customer_group_id'));
			
			if (empty($customer_info['passport_serie'])){
				$this->data['alert_passport'] = false;
				$addresses = $this->model_account_address->getAddresses();
				
				foreach ($addresses as $address){
					if ($address['country_id'] == 176){
						$this->data['alert_passport'] = true;
						break;
						} else {
						continue;
					}
				}
				
				} else {
				$this->data['alert_passport'] = false;
			}
			
			
			$this->data['text_my_account'] = $this->language->get('text_my_account');
			$this->data['text_my_orders'] = $this->language->get('text_my_orders');
			$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
			$this->data['text_edit'] = $this->language->get('text_edit');
			$this->data['text_password'] = $this->language->get('text_password');
			$this->data['text_address'] = $this->language->get('text_address');
			$this->data['text_wishlist'] = $this->language->get('text_wishlist');
			$this->data['text_viewed'] = $this->language->get('text_viewed');
			$this->data['text_order'] = $this->language->get('text_order');
			$this->data['text_download'] = $this->language->get('text_download');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_return'] = $this->language->get('text_return');
			$this->data['text_transaction'] = $this->language->get('text_transaction');
			$this->data['text_newsletter'] = $this->language->get('text_newsletter');
			$this->data['text_recurring'] = $this->language->get('text_recurring');
			$this->data['text_reviews'] = $this->language->get('text_reviews');
			$this->data['text_hello'] = $this->language->get('hello');
			$this->data['text_discount_card_number'] = $this->language->get('discount_card_number');
			$this->data['text_no_discount'] = $this->language->get('no_discount');
			$this->data['text_how_get'] = $this->language->get('how_get');
			$this->data['text_current_discount'] = $this->language->get('current_discount');
			$this->data['text_currency_discount_system'] = $this->language->get('currency_discount_system');
			$this->data['text_many_info'] = $this->language->get('many_info');
			
			$this->data['href_sale'] = $this->url->link('product/category', 'path=6614');
			$this->data['href_actions'] = $this->url->link('information/actions');
			
			$this->data['text_retranslate_account_main_line_3'] = sprintf($this->data['text_retranslate_account_main_line_3'], $this->data['href_actions'], $this->data['href_sale']);
			
			if ($this->data['customer_name']){
				$this->data['text_retranslate_account_main_line_4'] = sprintf($this->data['text_retranslate_account_main_line_4'], $this->data['customer_name']);
			} else {
				$this->data['text_retranslate_account_main_line_4'] = $this->data['text_retranslate_account_main_line_4_noname'];
			}
			
			
			$this->data['edit'] = $this->url->link('account/edit', '', 'SSL');
			$this->data['password'] = $this->url->link('account/password', '', 'SSL');
			$this->data['address'] = $this->url->link('account/address', '', 'SSL');
			$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
			$this->data['viewed'] = $this->url->link('account/viewed', '', 'SSL');
			$this->data['order'] = $this->url->link('account/order', '', 'SSL');
			$this->data['download'] = $this->url->link('account/download', '', 'SSL');
			$this->data['return'] = $this->url->link('account/return', '', 'SSL');
			$this->data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
			$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
			$this->data['recurring'] = $this->url->link('account/recurring', '', 'SSL');
			$this->data['reviews'] = $this->url->link('account/reviews', '', 'SSL');
			$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
			
			if ($this->config->get('reward_status')) {
				$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
				} else {
				$this->data['reward'] = '';
			}
			
			$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
				} else {
				$this->template = 'default/template/account/account.tpl';
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
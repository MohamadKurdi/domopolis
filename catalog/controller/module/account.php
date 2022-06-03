<?php  
	class ControllerModuleAccount extends Controller {
		protected function index() {	
			$this->language->load('module/account');
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['logged'] = $this->customer->isLogged();
		
			$route = '';
			if (!empty($this->request->get['route'])){
				$route = $this->request->get['route'];
			}

			// Мой кабинет
			$this->data['text_account'] = $this->language->get('text_account');
			$this->data['account'] = $this->url->link('account/account', '', 'SSL');
			$this->data['account_active'] = ($route == 'account/account');
			
			// Мои заказы
			$this->data['text_address'] = $this->language->get('text_address');
			$this->data['address'] = $this->url->link('account/address', '', 'SSL');
			$this->data['address_active'] = ($route == 'account/address');
			
			// Мои заказы
			$this->data['text_order'] = $this->language->get('text_order');
			$this->data['order'] = $this->url->link('account/order', '', 'SSL');
			$this->data['order_active'] = ($route == 'account/order');
			
			// Мои бонусы
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['reward'] = $this->url->link('account/reward', '', 'SSL');
			$this->data['reward_active'] = ($route == 'account/reward');
			
			$this->data['text_transaction'] = $this->language->get('text_transaction');			
			$this->data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
			$this->data['transaction_active'] = ($route == 'account/transaction');
			
			// Мои промокоды
			$this->data['text_coupons'] = $this->language->get('text_coupons');
			$this->data['coupons'] = $this->url->link('account/promocodes', '', 'SSL');
			$this->data['coupons_active'] = ($route == 'account/promocodes');
			
			// Просмотренные
			$this->data['text_viewed'] = $this->language->get('text_viewed');
			$this->data['viewed'] = $this->url->link('account/viewed');
			$this->data['viewed_active'] = ($route == 'account/viewed');
			
			// Избранное
			$this->data['text_wishlist'] = $this->language->get('text_wishlist');
			$this->data['wishlist'] = $this->url->link('account/wishlist');
			$this->data['wishlist_active'] = ($route == 'account/wishlist');
			
			// Сравнения
			$this->data['text_compare'] = $this->language->get('text_compare');
			$this->data['compare'] = $this->url->link('product/compare');
			$this->data['compare_active'] = ($route == 'product/compare');
			
			// Мои отзывы
			$this->data['text_reviews'] = $this->language->get('text_reviews');
			$this->data['reviews'] = $this->url->link('account/reviews');
			$this->data['reviews_active'] = ($route == 'product/reviews');
			$this->data['reviews_active'] = ($route == 'account/reviews');
			
			// Поддержка
			$this->data['text_support'] = $this->language->get('text_support');
			$this->data['support'] = $this->url->link('account/support');
			$this->data['support_active'] = ($route == 'account/support');
			
			
			// Персональные данные
			$this->data['text_edit'] = $this->language->get('text_edit');
			$this->data['text_address'] = $this->language->get('text_address');
			
			$this->data['edit'] = $this->url->link('account/edit', '', 'SSL');
			$this->data['edit_active'] = ($route == 'account/edit');
			$this->data['edit_active'] = ($route == 'account/simpleedit');
			
			$this->data['password'] = $this->url->link('account/password', '', 'SSL');
			$this->data['address'] = $this->url->link('account/address', '', 'SSL');
			
			
			//Выход
			$this->data['text_logout'] = $this->language->get('text_logout');
			$this->data['logout'] = $this->url->link('account/logout', '', 'SSL');
			
			$this->data['points_active'] = $this->customer->getRewardPoints();
			$this->data['points_active_formatted'] = $this->currency->formatBonus($this->data['points_active']);
			$this->data['points_active_formatted_as_currency'] = $this->currency->format($this->data['points_active'], $this->config->get('config_regional_currency'), 1);
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/account.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/account.tpl';
				} else {
				$this->template = 'default/template/module/account.tpl';
			}
			
			$this->render();
		}
	}	
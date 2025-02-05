<?php 
	class ControllerAffiliateAccount extends Controller { 
		public function index() {
			if (!$this->affiliate->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('affiliate/account', '');
				
				$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
			}
			
			$this->language->load('affiliate/account');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', ''),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_my_account'] = $this->language->get('text_my_account');
			$this->data['text_my_tracking'] = $this->language->get('text_my_tracking');
			$this->data['text_my_transactions'] = $this->language->get('text_my_transactions');
			$this->data['text_edit'] = $this->language->get('text_edit');
			$this->data['text_password'] = $this->language->get('text_password');
			$this->data['text_payment'] = $this->language->get('text_payment');
			$this->data['text_tracking'] = $this->language->get('text_tracking');
			$this->data['text_transaction'] = $this->language->get('text_transaction');
			$this->data['text_my_orderpayment'] = $this->language->get('text_my_orderpayment');
			$this->data['text_my_statistics'] = $this->language->get('text_my_statistics');
			$this->data['text_orderpayment'] = $this->language->get('text_orderpayment');
			$this->data['text_statistics'] = $this->language->get('text_statistics');     
			$this->data['orderpayment'] = $this->url->link('affiliate/orderpayment', '');
			$this->data['statistics'] = $this->url->link('affiliate/statistics', '');
			$this->load->model('affiliate/affiliate');
			$affiliateinfo = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
			$this->load->model('affiliate/transaction');
			$balance = $this->model_affiliate_transaction->getBalance();
			$this->data['balance'] = sprintf($this->language->get('text_balance'), $this->currency->format($balance, $this->config->get('config_currency')));
			$this->data['percentage'] = sprintf($this->language->get('text_percentage'), $affiliateinfo['commission'], '%');
			$this->data['name_affiliate'] = sprintf($this->language->get('text_name_affiliate'), $affiliateinfo['firstname'] , $affiliateinfo['lastname']);
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->data['edit'] = $this->url->link('affiliate/edit', '');
			$this->data['password'] = $this->url->link('affiliate/password', '');
			$this->data['payment'] = $this->url->link('affiliate/payment', '');
			$this->data['tracking'] = $this->url->link('affiliate/tracking', '');
			$this->data['transaction'] = $this->url->link('affiliate/transaction', '');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/account.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/affiliate/account.tpl';
				} else {
				$this->template = 'default/template/affiliate/account.tpl';
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
?>
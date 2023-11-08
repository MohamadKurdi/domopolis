<?php  
class ControllerModuleAffiliate extends Controller {
	protected function index() {
		$this->language->load('module/affiliate');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');	
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_password'] = $this->language->get('text_password');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_tracking'] = $this->language->get('text_tracking');
		$this->data['text_transaction'] = $this->language->get('text_transaction');
		
		
		$this->data['text_statistics'] = $this->language->get('text_statistics');
				$this->data['text_orderpayment'] = $this->language->get('text_orderpayment');
				$this->data['statistics'] = $this->url->link('affiliate/statistics', '');
				$this->data['orderpayment'] = $this->url->link('affiliate/orderpayment', '');

		$this->data['logged'] = $this->affiliate->isLogged();
		$this->data['register'] = $this->url->link('affiliate/register', '');
		$this->data['login'] = $this->url->link('affiliate/login', '');
		$this->data['logout'] = $this->url->link('affiliate/logout', '');
		$this->data['forgotten'] = $this->url->link('affiliate/forgotten', '');
		$this->data['account'] = $this->url->link('affiliate/account', '');
		$this->data['edit'] = $this->url->link('affiliate/edit', '');
		$this->data['password'] = $this->url->link('affiliate/password', '');
		$this->data['payment'] = $this->url->link('affiliate/payment', '');
		$this->data['tracking'] = $this->url->link('affiliate/tracking', '');
		$this->data['transaction'] = $this->url->link('affiliate/transaction', '');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/affiliate.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/affiliate.tpl';
		} else {
			$this->template = 'default/template/module/affiliate.tpl';
		}

		$this->render();
	}
}
?>
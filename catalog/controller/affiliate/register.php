<?php 
	class ControllerAffiliateRegister extends Controller {
		private $error = array();
		
		public function index() {
			if ($this->affiliate->isLogged()) {
				$this->redirect($this->url->link('affiliate/account', '', 'SSL'));
			}
			
			$this->language->load('affiliate/register');
			
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
			
			$this->load->model('affiliate/affiliate');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_affiliate_affiliate->addAffiliate($this->request->post);
				
				$this->affiliate->login($this->request->post['email'], $this->request->post['password']);
				
				$this->redirect($this->url->link('affiliate/success'));
			}
			
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
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_register'),
			'href'      => $this->url->link('affiliate/register', ''),      	
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_qiwi'] = $this->language->get('text_qiwi');
			$this->data['text_card'] = $this->language->get('text_card');
			$this->data['text_yandex'] = $this->language->get('text_yandex');
			$this->data['text_webmoney'] = $this->language->get('text_webmoney');
			$this->data['text_webmoneyWMZ'] = $this->language->get('text_webmoneyWMZ');
			$this->data['text_webmoneyWMU'] = $this->language->get('text_webmoneyWMU');
			$this->data['text_webmoneyWME'] = $this->language->get('text_webmoneyWME');
			$this->data['text_webmoneyWMY'] = $this->language->get('text_webmoneyWMY');
			$this->data['text_webmoneyWMB'] = $this->language->get('text_webmoneyWMB');
			$this->data['text_webmoneyWMG'] = $this->language->get('text_webmoneyWMG');
			$this->data['text_AlertPay'] = $this->language->get('text_AlertPay');
			$this->data['text_Moneybookers'] = $this->language->get('text_Moneybookers');
			$this->data['text_LIQPAY'] = $this->language->get('text_LIQPAY');
			$this->data['text_SagePay'] = $this->language->get('text_SagePay');
			$this->data['text_twoCheckout'] = $this->language->get('text_twoCheckout');
			$this->data['text_GoogleWallet'] = $this->language->get('text_GoogleWallet');
			$this->data['entry_qiwi'] = $this->language->get('entry_qiwi');
			$this->data['entry_card'] = $this->language->get('entry_card');
			$this->data['entry_yandex'] = $this->language->get('entry_yandex');
			$this->data['entry_webmoney'] = $this->language->get('entry_webmoney');
			$this->data['entry_webmoneyWMZ'] = $this->language->get('entry_webmoneyWMZ');
			$this->data['entry_webmoneyWMU'] = $this->language->get('entry_webmoneyWMU');
			$this->data['entry_webmoneyWME'] = $this->language->get('entry_webmoneyWME');
			$this->data['entry_webmoneyWMY'] = $this->language->get('entry_webmoneyWMY');
			$this->data['entry_webmoneyWMB'] = $this->language->get('entry_webmoneyWMB');
			$this->data['entry_webmoneyWMG'] = $this->language->get('entry_webmoneyWMG');
			$this->data['entry_AlertPay'] = $this->language->get('entry_AlertPay');
			$this->data['entry_Moneybookers'] = $this->language->get('entry_Moneybookers');
			$this->data['entry_LIQPAY'] = $this->language->get('entry_LIQPAY');
			$this->data['entry_SagePay'] = $this->language->get('entry_SagePay');
			$this->data['entry_twoCheckout'] = $this->language->get('entry_twoCheckout');
			$this->data['entry_GoogleWallet'] = $this->language->get('entry_GoogleWallet');
			
			$this->data['title_qiwi'] = $this->language->get('title_qiwi');
			$this->data['title_card'] = $this->language->get('title_card');
			$this->data['title_yandex'] = $this->language->get('title_yandex');
			$this->data['title_webmoney'] = $this->language->get('title_webmoney');
			$this->data['title_webmoneyWMZ'] = $this->language->get('title_webmoneyWMZ');
			$this->data['title_webmoneyWMU'] = $this->language->get('title_webmoneyWMU');
			$this->data['title_webmoneyWME'] = $this->language->get('title_webmoneyWME');
			$this->data['title_webmoneyWMY'] = $this->language->get('title_webmoneyWMY');
			$this->data['title_webmoneyWMB'] = $this->language->get('title_webmoneyWMB');
			$this->data['title_webmoneyWMG'] = $this->language->get('title_webmoneyWMG');
			
			$this->data['text_bonus'] = $this->language->get('text_bonus');
			$this->data['config_bonus_visible'] = (bool)$this->config->get('config_bonus_visible');
			$this->data['cheque_visible'] = (bool)$this->config->get('cheque');
			$this->data['paypal_visible'] = (bool)$this->config->get('paypal');
			$this->data['bank_visible'] = (bool)$this->config->get('bank');
			$this->data['qiwi_visible'] = (bool)$this->config->get('qiwi');
			$this->data['card_visible'] = (bool)$this->config->get('card');
			$this->data['yandex_visible'] = (bool)$this->config->get('yandex');
			$this->data['webmoney_visible'] = (bool)$this->config->get('webmoney');			
			$this->data['webmoney_visibleWMZ'] = (bool)$this->config->get('webmoneyWMZ');	
			$this->data['webmoney_visibleWMU'] = (bool)$this->config->get('webmoneyWMU');	
			$this->data['webmoney_visibleWME'] = (bool)$this->config->get('webmoneyWME');	
			$this->data['webmoney_visibleWMY'] = (bool)$this->config->get('webmoneyWMY');	
			$this->data['webmoney_visibleWMB'] = (bool)$this->config->get('webmoneyWMB');	
			$this->data['webmoney_visibleWMG'] = (bool)$this->config->get('webmoneyWMG');
			$this->data['AlertPay_visible'] = (bool)$this->config->get('AlertPay');
			$this->data['Moneybookers_visible'] = (bool)$this->config->get('Moneybookers');
			$this->data['LIQPAY_visible'] = (bool)$this->config->get('LIQPAY');
			$this->data['SagePay_visible'] = (bool)$this->config->get('SagePay');
			$this->data['twoCheckout_visible'] = (bool)$this->config->get('twoCheckout');
			$this->data['GoogleWallet_visible'] = (bool)$this->config->get('GoogleWallet');
			
			if (isset($this->error['qiwi'])) {
				$this->data['error_qiwi'] = $this->error['qiwi'];
				} else {
				$this->data['error_qiwi'] = '';
			}
			
			if (isset($this->error['card'])) {
				$this->data['error_card'] = $this->error['card'];
				} else {
				$this->data['error_card'] = '';
			}
			
			if (isset($this->error['yandex'])) {
				$this->data['error_yandex'] = $this->error['yandex'];
				} else {
				$this->data['error_yandex'] = '';
			}
			
			if (isset($this->error['webmoney'])) {
				$this->data['error_webmoney'] = $this->error['webmoney'];
				} else {
				$this->data['error_webmoney'] = '';
			}
			if (isset($this->error['webmoneyWMZ'])) {
				$this->data['error_webmoneyWMZ'] = $this->error['webmoneyWMZ'];
				} else {
				$this->data['error_webmoneyWMZ'] = '';
			}
			if (isset($this->error['webmoneyWMU'])) {
				$this->data['error_webmoneyWMU'] = $this->error['webmoneyWMU'];
				} else {
				$this->data['error_webmoneyWMU'] = '';
			}
			if (isset($this->error['webmoneyWME'])) {
				$this->data['error_webmoneyWME'] = $this->error['webmoneyWME'];
				} else {
				$this->data['error_webmoneyWME'] = '';
			}
			if (isset($this->error['webmoneyWMY'])) {
				$this->data['error_webmoneyWMY'] = $this->error['webmoneyWMY'];
				} else {
				$this->data['error_webmoneyWMY'] = '';
			}
			if (isset($this->error['webmoneyWMB'])) {
				$this->data['error_webmoneyWMB'] = $this->error['webmoneyWMB'];
				} else {
				$this->data['error_webmoneyWMB'] = '';
			}
			if (isset($this->error['webmoneyWMG'])) {
				$this->data['error_webmoneyWMG'] = $this->error['webmoneyWMG'];
				} else {
				$this->data['error_webmoneyWMG'] = '';
			}
			if (isset($this->request->post['qiwi'])) {
				$this->data['qiwi'] = $this->request->post['qiwi'];
				} else {
				$this->data['qiwi'] = '';
			}
			if (isset($this->request->post['card'])) {
				$this->data['card'] = $this->request->post['card'];
				} else {
				$this->data['card'] = '';
			}
			if (isset($this->request->post['yandex'])) {
				$this->data['yandex'] = $this->request->post['yandex'];
				} else {
				$this->data['yandex'] = '';
			}
			if (isset($this->request->post['webmoney'])) {
				$this->data['webmoney'] = $this->request->post['webmoney'];
				} else {
				$this->data['webmoney'] = '';
			}
			if (isset($this->request->post['webmoneyWMZ'])) {
				$this->data['webmoneyWMZ'] = $this->request->post['webmoneyWMZ'];
				} else {
				$this->data['webmoneyWMZ'] = '';
			}
			if (isset($this->request->post['webmoneyWMU'])) {
				$this->data['webmoneyWMU'] = $this->request->post['webmoneyWMU'];
				} else {
				$this->data['webmoneyWMU'] = '';
			}
			if (isset($this->request->post['webmoneyWME'])) {
				$this->data['webmoneyWME'] = $this->request->post['webmoneyWME'];
				} else {
				$this->data['webmoneyWME'] = '';
			}
			if (isset($this->request->post['webmoneyWMY'])) {
				$this->data['webmoneyWMY'] = $this->request->post['webmoneyWMY'];
				} else {
				$this->data['webmoneyWMY'] = '';
			}
			if (isset($this->request->post['webmoneyWMB'])) {
				$this->data['webmoneyWMB'] = $this->request->post['webmoneyWMB'];
				} else {
				$this->data['webmoneyWMB'] = '';
			}
			if (isset($this->request->post['webmoneyWMG'])) {
				$this->data['webmoneyWMG'] = $this->request->post['webmoneyWMG'];
				} else {
				$this->data['webmoneyWMG'] = '';
			}
			if (isset($this->request->post['AlertPay'])) {
				$this->data['AlertPay'] = $this->request->post['AlertPay'];
				} else {
				$this->data['AlertPay'] = '';
			}
			
			if (isset($this->request->post['Moneybookers'])) {
				$this->data['Moneybookers'] = $this->request->post['Moneybookers'];
				} else {
				$this->data['Moneybookers'] = '';
			}
			
			if (isset($this->request->post['LIQPAY'])) {
				$this->data['LIQPAY'] = $this->request->post['LIQPAY'];
				} else {
				$this->data['LIQPAY'] = '';
			}
			
			if (isset($this->request->post['SagePay'])) {
				$this->data['SagePay'] = $this->request->post['SagePay'];
				} else {
				$this->data['SagePay'] = '';
			}
			
			if (isset($this->request->post['twoCheckout'])) {
				$this->data['twoCheckout'] = $this->request->post['twoCheckout'];
				} else {
				$this->data['twoCheckout'] = '';
			}
			
			if (isset($this->request->post['GoogleWallet'])) {
				$this->data['GoogleWallet'] = $this->request->post['GoogleWallet'];
				} else {
				$this->data['GoogleWallet'] = '';
			}
			
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_none'] = $this->language->get('text_none');
			$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('affiliate/login', '', 'SSL'));
			$this->data['text_signup'] = $this->language->get('text_signup');
			$this->data['text_your_details'] = $this->language->get('text_your_details');
			$this->data['text_your_address'] = $this->language->get('text_your_address');
			$this->data['text_payment'] = $this->language->get('text_payment');
			$this->data['text_your_password'] = $this->language->get('text_your_password');
			$this->data['text_cheque'] = $this->language->get('text_cheque');
			$this->data['text_paypal'] = $this->language->get('text_paypal');
			$this->data['text_bank'] = $this->language->get('text_bank');
			
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');
			$this->data['entry_company'] = $this->language->get('entry_company');
			$this->data['entry_website'] = $this->language->get('entry_website');
			$this->data['entry_address_1'] = $this->language->get('entry_address_1');
			$this->data['entry_address_2'] = $this->language->get('entry_address_2');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_tax'] = $this->language->get('entry_tax');
			$this->data['entry_payment'] = $this->language->get('entry_payment');
			$this->data['entry_cheque'] = $this->language->get('entry_cheque');
			$this->data['entry_paypal'] = $this->language->get('entry_paypal');
			$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
			$this->data['entry_bank_branch_number'] = $this->language->get('entry_bank_branch_number');
			$this->data['entry_bank_swift_code'] = $this->language->get('entry_bank_swift_code');
			$this->data['entry_bank_account_name'] = $this->language->get('entry_bank_account_name');
			$this->data['entry_bank_account_number'] = $this->language->get('entry_bank_account_number');
			$this->data['entry_password'] = $this->language->get('entry_password');
			$this->data['entry_confirm'] = $this->language->get('entry_confirm');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['firstname'])) {
				$this->data['error_firstname'] = $this->error['firstname'];
				} else {
				$this->data['error_firstname'] = '';
			}	
			
			if (isset($this->error['lastname'])) {
				$this->data['error_lastname'] = $this->error['lastname'];
				} else {
				$this->data['error_lastname'] = '';
			}		
			
			if (isset($this->error['email'])) {
				$this->data['error_email'] = $this->error['email'];
				} else {
				$this->data['error_email'] = '';
			}
			
			if (isset($this->error['telephone'])) {
				$this->data['error_telephone'] = $this->error['telephone'];
				} else {
				$this->data['error_telephone'] = '';
			}
			
			if (isset($this->error['password'])) {
				$this->data['error_password'] = $this->error['password'];
				} else {
				$this->data['error_password'] = '';
			}
			
			if (isset($this->error['confirm'])) {
				$this->data['error_confirm'] = $this->error['confirm'];
				} else {
				$this->data['error_confirm'] = '';
			}
			
			if (isset($this->error['address_1'])) {
				$this->data['error_address_1'] = $this->error['address_1'];
				} else {
				$this->data['error_address_1'] = '';
			}
			
			if (isset($this->error['city'])) {
				$this->data['error_city'] = $this->error['city'];
				} else {
				$this->data['error_city'] = '';
			}
			
			if (isset($this->error['postcode'])) {
				$this->data['error_postcode'] = $this->error['postcode'];
				} else {
				$this->data['error_postcode'] = '';
			}
			
			if (isset($this->error['country'])) {
				$this->data['error_country'] = $this->error['country'];
				} else {
				$this->data['error_country'] = '';
			}
			
			if (isset($this->error['zone'])) {
				$this->data['error_zone'] = $this->error['zone'];
				} else {
				$this->data['error_zone'] = '';
			}
			
			$this->data['action'] = $this->url->link('affiliate/register', '');
			
			if (isset($this->request->post['firstname'])) {
				$this->data['firstname'] = $this->request->post['firstname'];
				} else {
				$this->data['firstname'] = '';
			}
			
			if (isset($this->request->post['lastname'])) {
				$this->data['lastname'] = $this->request->post['lastname'];
				} else {
				$this->data['lastname'] = '';
			}
			
			if (isset($this->request->post['email'])) {
				$this->data['email'] = $this->request->post['email'];
				} else {
				$this->data['email'] = '';
			}
			
			if (isset($this->request->post['telephone'])) {
				$this->data['telephone'] = $this->request->post['telephone'];
				} else {
				$this->data['telephone'] = '';
			}
			
			if (isset($this->request->post['fax'])) {
				$this->data['fax'] = $this->request->post['fax'];
				} else {
				$this->data['fax'] = '';
			}
			
			if (isset($this->request->post['company'])) {
				$this->data['company'] = $this->request->post['company'];
				} else {
				$this->data['company'] = '';
			}
			
			if (isset($this->request->post['website'])) {
				$this->data['website'] = $this->request->post['website'];
				} else {
				$this->data['website'] = '';
			}
			
			if (isset($this->request->post['address_1'])) {
				$this->data['address_1'] = $this->request->post['address_1'];
				} else {
				$this->data['address_1'] = '';
			}
			
			if (isset($this->request->post['address_2'])) {
				$this->data['address_2'] = $this->request->post['address_2'];
				} else {
				$this->data['address_2'] = '';
			}
			
			if (isset($this->request->post['postcode'])) {
				$this->data['postcode'] = $this->request->post['postcode'];
				} else {
				$this->data['postcode'] = '';
			}
			
			if (isset($this->request->post['city'])) {
				$this->data['city'] = $this->request->post['city'];
				} else {
				$this->data['city'] = '';
			}
			
			if (isset($this->request->post['country_id'])) {
				$this->data['country_id'] = $this->request->post['country_id'];
				} else {	
				$this->data['country_id'] = $this->config->get('config_country_id');
			}
			
			if (isset($this->request->post['zone_id'])) {
				$this->data['zone_id'] = $this->request->post['zone_id'];
				} else {
				$this->data['zone_id'] = '';
			}
			
			$this->load->model('localisation/country');
			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			if (isset($this->request->post['tax'])) {
				$this->data['tax'] = $this->request->post['tax'];
				} else {
				$this->data['tax'] = '';
			}
			
			if (isset($this->request->post['payment'])) {
				$this->data['payment'] = $this->request->post['payment'];
				} else {
				if($this->data['config_bonus_visible']){
					$this->data['payment'] = 'bonus';
				}
				else if($this->data['cheque_visible']){
					$this->data['payment'] = 'cheque';
				}
				else if($this->data['paypal_visible']){
					$this->data['payment'] = 'paypal';
				}
				else if($this->data['bank_visible']){
					$this->data['payment'] = 'bank';
				}
				else if($this->data['qiwi_visible']){
					$this->data['payment'] = 'qiwi';
				}
				else if($this->data['card_visible']){
					$this->data['payment'] = 'card';
				}
				else if($this->data['yandex_visible']){
					$this->data['payment'] = 'yandex';
				}
				else if($this->data['webmoney_visible']){
					$this->data['payment'] = 'webmoney';
				}
				else if($this->data['webmoney_visibleWMZ']){
					$this->data['payment'] = 'webmoneyWMZ';
				}
				else if($this->data['webmoney_visibleWMU']){
					$this->data['payment'] = 'webmoneyWMU';
				}
				else if($this->data['webmoney_visibleWME']){
					$this->data['payment'] = 'webmoneyWME';
				}
				else if($this->data['webmoney_visibleWMY']){
					$this->data['payment'] = 'webmoneyWMY';
				}
				else if($this->data['webmoney_visibleWMB']){
					$this->data['payment'] = 'webmoneyWMB';
				}
				else if($this->data['webmoney_visibleWMG']){
					$this->data['payment'] = 'webmoneyWMG';
				}
				else if($this->data['AlertPay_visible']){
					$this->data['payment'] = 'AlertPay';
				}
				else if($this->data['Moneybookers_visible']){
					$this->data['payment'] = 'Moneybookers';
				}
				else if($this->data['LIQPAY_visible']){
					$this->data['payment'] = 'LIQPAY';
				}
				else if($this->data['SagePay_visible']){
					$this->data['payment'] = 'SagePay';
				}
				else if($this->data['twoCheckout_visible']){
					$this->data['payment'] = 'twoCheckout';
				}
				else if($this->data['GoogleWallet_visible']){
					$this->data['payment'] = 'GoogleWallet';
				}
			}
			
			if (isset($this->request->post['cheque'])) {
				$this->data['cheque'] = $this->request->post['cheque'];
				} else {
				$this->data['cheque'] = '';
			}
			
			if (isset($this->request->post['paypal'])) {
				$this->data['paypal'] = $this->request->post['paypal'];
				} else {
				$this->data['paypal'] = '';
			}
			
			if (isset($this->request->post['bank_name'])) {
				$this->data['bank_name'] = $this->request->post['bank_name'];
				} else {
				$this->data['bank_name'] = '';
			}
			
			if (isset($this->request->post['bank_branch_number'])) {
				$this->data['bank_branch_number'] = $this->request->post['bank_branch_number'];
				} else {
				$this->data['bank_branch_number'] = '';
			}
			
			if (isset($this->request->post['bank_swift_code'])) {
				$this->data['bank_swift_code'] = $this->request->post['bank_swift_code'];
				} else {
				$this->data['bank_swift_code'] = '';
			}
			
			if (isset($this->request->post['bank_account_name'])) {
				$this->data['bank_account_name'] = $this->request->post['bank_account_name'];
				} else {
				$this->data['bank_account_name'] = '';
			}
			
			if (isset($this->request->post['bank_account_number'])) {
				$this->data['bank_account_number'] = $this->request->post['bank_account_number'];
				} else {
				$this->data['bank_account_number'] = '';
			}
			
			if (isset($this->request->post['password'])) {
				$this->data['password'] = $this->request->post['password'];
				} else {
				$this->data['password'] = '';
			}
			
			if (isset($this->request->post['confirm'])) {
				$this->data['confirm'] = $this->request->post['confirm'];
				} else {
				$this->data['confirm'] = '';
			}
			
			if ($this->config->get('config_affiliate_id')) {
				$this->load->model('catalog/information');
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_affiliate_id'));
				
				if ($information_info) {
					$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_affiliate_id')), $information_info['title'], $information_info['title']);
					} else {
					$this->data['text_agree'] = '';
				}
				} else {
				$this->data['text_agree'] = '';
			}
			
			if (isset($this->request->post['agree'])) {
				$this->data['agree'] = $this->request->post['agree'];
				} else {
				$this->data['agree'] = false;
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/register.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/affiliate/register.tpl';
				} else {
				$this->template = 'default/template/affiliate/register.tpl';
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
		
		protected function validate() {
			
			if(isset($this->request->post['payment'])) {
				if ($this->request->post['payment'] == 'qiwi') {
					if (!preg_match("/^[0-9]{10}$/", $this->request->post['qiwi'])) {
						$this->error['qiwi'] = $this->language->get('error_qiwi');
					}
				}
				if ($this->request->post['payment'] == 'card') {
					if (preg_match("/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{1,4}-{0,1}[0-9]{0,3}$/", $this->request->post['card'])) {
						
						if (strlen($this->request->post['card']) == 16) {
							
							} else if (strlen($this->request->post['card']) == 19) {
							
							} else if (strlen($this->request->post['card']) == 23) {
							
							} else {
							$this->error['card'] = $this->language->get('error_card');
						}
						} else {
						$this->error['card'] = $this->language->get('error_card');
					}
				}
				if ($this->request->post['payment'] == 'yandex') {
					if (!preg_match("/^[0-9]{14,15}$/", $this->request->post['yandex'])) {
						$this->error['yandex'] = $this->language->get('error_yandex');
					}
				}
				if ($this->request->post['payment'] == 'webmoney') {
					if (!preg_match("/^R[0-9]{12}$/", $this->request->post['webmoney'])) {
						$this->error['webmoney'] = $this->language->get('error_webmoney');
					}
				}
				if ($this->request->post['payment'] == 'webmoneyWMZ') {
					if (!preg_match("/^Z[0-9]{12}$/", $this->request->post['webmoneyWMZ'])) {
						$this->error['webmoneyWMZ'] = $this->language->get('error_webmoneyWMZ');
					}
				}
				if ($this->request->post['payment'] == 'webmoneyWMU') {
					if (!preg_match("/^U[0-9]{12}$/", $this->request->post['webmoneyWMU'])) {
						$this->error['webmoneyWMU'] = $this->language->get('error_webmoneyWMU');
					}
				}
				if ($this->request->post['payment'] == 'webmoneyWME') {
					if (!preg_match("/^E[0-9]{12}$/", $this->request->post['webmoneyWME'])) {
						$this->error['webmoneyWME'] = $this->language->get('error_webmoneyWME');
					}
				}
				if ($this->request->post['payment'] == 'webmoneyWMY') {
					if (!preg_match("/^Y[0-9]{12}$/", $this->request->post['webmoneyWMY'])) {
						$this->error['webmoneyWMY'] = $this->language->get('error_webmoneyWMY');
					}
				}
				if ($this->request->post['payment'] == 'webmoneyWMB') {
					if (!preg_match("/^B[0-9]{12}$/", $this->request->post['webmoneyWMB'])) {
						$this->error['webmoneyWMB'] = $this->language->get('error_webmoneyWMB');
					}
				}
				if ($this->request->post['payment'] == 'webmoneyWMG') {
					if (!preg_match("/^G[0-9]{12}$/", $this->request->post['webmoneyWMG'])) {
						$this->error['webmoneyWMG'] = $this->language->get('error_webmoneyWMG');
					}
				}
				} else {
				$this->data['config_bonus_visible'] = (bool)$this->config->get('config_bonus_visible');
				$this->data['cheque_visible'] = (bool)$this->config->get('cheque');
				$this->data['paypal_visible'] = (bool)$this->config->get('paypal');
				$this->data['bank_visible'] = (bool)$this->config->get('bank');
				$this->data['qiwi_visible'] = (bool)$this->config->get('qiwi');
				$this->data['card_visible'] = (bool)$this->config->get('card');
				$this->data['yandex_visible'] = (bool)$this->config->get('yandex');
				$this->data['webmoney_visible'] = (bool)$this->config->get('webmoney');			
				$this->data['webmoney_visibleWMZ'] = (bool)$this->config->get('webmoneyWMZ');	
				$this->data['webmoney_visibleWMU'] = (bool)$this->config->get('webmoneyWMU');	
				$this->data['webmoney_visibleWME'] = (bool)$this->config->get('webmoneyWME');	
				$this->data['webmoney_visibleWMY'] = (bool)$this->config->get('webmoneyWMY');	
				$this->data['webmoney_visibleWMB'] = (bool)$this->config->get('webmoneyWMB');	
				$this->data['webmoney_visibleWMG'] = (bool)$this->config->get('webmoneyWMG');
				$this->data['AlertPay_visible'] = (bool)$this->config->get('AlertPay');
				$this->data['Moneybookers_visible'] = (bool)$this->config->get('Moneybookers');
				$this->data['LIQPAY_visible'] = (bool)$this->config->get('LIQPAY');
				$this->data['SagePay_visible'] = (bool)$this->config->get('SagePay');
				$this->data['twoCheckout_visible'] = (bool)$this->config->get('twoCheckout');
				$this->data['GoogleWallet_visible'] = (bool)$this->config->get('GoogleWallet');
				
				if($this->data['config_bonus_visible']){
					$this->request->post['payment'] = 'bonus';
				}
				else if($this->data['cheque_visible']){
					$this->request->post['payment'] = 'cheque';
				}
				else if($this->data['paypal_visible']){
					$this->request->post['payment'] = 'paypal';
				}
				else if($this->data['bank_visible']){
					$this->request->post['payment'] = 'bank';
				}
				else if($this->data['qiwi_visible']){
					$this->request->post['payment'] = 'qiwi';
				}
				else if($this->data['card_visible']){
					$this->request->post['payment'] = 'card';
				}
				else if($this->data['yandex_visible']){
					$this->request->post['payment'] = 'yandex';
				}
				else if($this->data['webmoney_visible']){
					$this->request->post['payment'] = 'webmoney';
				}
				else if($this->data['webmoney_visibleWMZ']){
					$this->request->post['payment'] = 'webmoneyWMZ';
				}
				else if($this->data['webmoney_visibleWMU']){
					$this->request->post['payment'] = 'webmoneyWMU';
				}
				else if($this->data['webmoney_visibleWME']){
					$this->request->post['payment'] = 'webmoneyWME';
				}
				else if($this->data['webmoney_visibleWMY']){
					$this->request->post['payment'] = 'webmoneyWMY';
				}
				else if($this->data['webmoney_visibleWMB']){
					$this->request->post['payment'] = 'webmoneyWMB';
				}
				else if($this->data['webmoney_visibleWMG']){
					$this->request->post['payment'] = 'webmoneyWMG';
				}
			}
			
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$this->error['firstname'] = $this->language->get('error_firstname');
			}
			
			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$this->error['lastname'] = $this->language->get('error_lastname');
			}
			
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$this->error['email'] = $this->language->get('error_email');
			}
			
			if ($this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->post['email'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
			
			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$this->error['telephone'] = $this->language->get('error_telephone');
			}
			
			if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$this->error['address_1'] = $this->language->get('error_address_1');
			}
			
			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
				$this->error['city'] = $this->language->get('error_city');
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
			
			if ($this->request->post['country_id'] == '') {
				$this->error['country'] = $this->language->get('error_country');
			}
			
			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
				$this->error['zone'] = $this->language->get('error_zone');
			}
			
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}
			
			if ($this->request->post['confirm'] != $this->request->post['password']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
			
			if ($this->config->get('config_affiliate_id')) {
				$this->load->model('catalog/information');
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_affiliate_id'));
				
				if ($information_info && !isset($this->request->post['agree'])) {
					$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		public function country() {
			$json = array();
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
			
			if ($country_info) {
				$this->load->model('localisation/zone');
				
				$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
				);
			}
			
			$this->response->setOutput(json_encode($json));
		}	
	}
?>
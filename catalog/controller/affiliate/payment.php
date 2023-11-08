<?php
	class ControllerAffiliatePayment extends Controller {
		private $error = array();
		
		public function index() {
			if (!$this->affiliate->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('affiliate/payment', '');
				
				$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
			}
			
			$this->language->load('affiliate/payment');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('affiliate/affiliate');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_affiliate_affiliate->editPayment($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('affiliate/account', '', 'SSL'));
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
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('affiliate/payment', ''),       	
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
			
			$this->data['entry_payment'] = $this->language->get('entry_payment');
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
			
			$this->data['text_your_payment'] = $this->language->get('text_your_payment');
			$this->data['text_cheque'] = $this->language->get('text_cheque');
			$this->data['text_paypal'] = $this->language->get('text_paypal');
			$this->data['text_bank'] = $this->language->get('text_bank');
			
			$this->data['entry_tax'] = $this->language->get('entry_tax');
			$this->data['entry_payment'] = $this->language->get('entry_payment');
			$this->data['entry_cheque'] = $this->language->get('entry_cheque');
			$this->data['entry_paypal'] = $this->language->get('entry_paypal');
			$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
			$this->data['entry_bank_branch_number'] = $this->language->get('entry_bank_branch_number');
			$this->data['entry_bank_swift_code'] = $this->language->get('entry_bank_swift_code');
			$this->data['entry_bank_account_name'] = $this->language->get('entry_bank_account_name');
			$this->data['entry_bank_account_number'] = $this->language->get('entry_bank_account_number');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_back'] = $this->language->get('button_back');
			
			$this->data['action'] = $this->url->link('affiliate/payment', '');
			
			if ($this->request->server['REQUEST_METHOD'] != 'POST') {
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
			}
			
			if (isset($this->request->post['tax'])) {
				$this->data['tax'] = $this->request->post['tax'];
				} elseif (!empty($affiliate_info)) {
				$this->data['tax'] = $affiliate_info['tax'];		
				} else {
				$this->data['tax'] = '';
			}
			
			if (isset($this->request->post['payment'])) {
				$this->data['payment'] = $this->request->post['payment'];
				} elseif (!empty($affiliate_info)) {
				if ((bool)$this->config->get($affiliate_info['payment'])) {
					$this->data['payment'] = $affiliate_info['payment'];
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
			
			if (isset($this->request->post['qiwi'])) {
				$this->data['qiwi'] = $this->request->post['qiwi'];
				} elseif (!empty($affiliate_info)) {
				$this->data['qiwi'] = $affiliate_info['qiwi'];
				} else {
				$this->data['qiwi'] = '';
			}
			
			if (isset($this->request->post['card'])) {
				$this->data['card'] = $this->request->post['card'];
				} elseif (!empty($affiliate_info)) {
				$affiliate_info_card = $affiliate_info['card'];
				if (strlen($affiliate_info_card) != 0) {
					$this->data['card'] = 'XXXX-XXXX-XXXX-' . substr($affiliate_info_card, 15, strlen($affiliate_info_card));
					} else {
					$this->data['card'] = '';
				}
				} else {
				$this->data['card'] = '';
			}
			
			if (isset($this->request->post['yandex'])) {
				$this->data['yandex'] = $this->request->post['yandex'];
				} elseif (!empty($affiliate_info)) {
				$this->data['yandex'] = $affiliate_info['yandex'];
				} else {
				$this->data['yandex'] = '';
			}
			
			if (isset($this->request->post['webmoney'])) {
				$this->data['webmoney'] = $this->request->post['webmoney'];
				} elseif (!empty($affiliate_info)) {
				$this->data['webmoney'] = $affiliate_info['webmoney'];
				} else {
				$this->data['webmoney'] = '';
			}
			if (isset($this->request->post['webmoneyWMZ'])) {
				$this->data['webmoneyWMZ'] = $this->request->post['webmoneyWMZ'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['webmoneyWMZ'] = $affiliate_info['webmoneyWMZ'];
				} else {
				$this->data['webmoneyWMZ'] = '';
			}
			if (isset($this->request->post['webmoneyWMU'])) {
				$this->data['webmoneyWMU'] = $this->request->post['webmoneyWMU'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['webmoneyWMU'] = $affiliate_info['webmoneyWMU'];
				} else {
				$this->data['webmoneyWMU'] = '';
			}
			if (isset($this->request->post['webmoneyWME'])) {
				$this->data['webmoneyWME'] = $this->request->post['webmoneyWME'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['webmoneyWME'] = $affiliate_info['webmoneyWME'];
				} else {
				$this->data['webmoneyWME'] = '';
			}
			if (isset($this->request->post['webmoneyWMY'])) {
				$this->data['webmoneyWMY'] = $this->request->post['webmoneyWMY'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['webmoneyWMY'] = $affiliate_info['webmoneyWMY'];
				} else {
				$this->data['webmoneyWMY'] = '';
			}
			if (isset($this->request->post['webmoneyWMB'])) {
				$this->data['webmoneyWMB'] = $this->request->post['webmoneyWMB'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['webmoneyWMB'] = $affiliate_info['webmoneyWMB'];
				} else {
				$this->data['webmoneyWMB'] = '';
			}
			if (isset($this->request->post['webmoneyWMG'])) {
				$this->data['webmoneyWMG'] = $this->request->post['webmoneyWMG'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['webmoneyWMG'] = $affiliate_info['webmoneyWMG'];
				} else {
				$this->data['webmoneyWMG'] = '';
			}
			if (isset($this->request->post['AlertPay'])) {
				$this->data['AlertPay'] = $this->request->post['AlertPay'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['AlertPay'] = $affiliate_info['AlertPay'];
				} else {
				$this->data['AlertPay'] = '';
			}
			
			if (isset($this->request->post['Moneybookers'])) {
				$this->data['Moneybookers'] = $this->request->post['Moneybookers'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['Moneybookers'] = $affiliate_info['Moneybookers'];
				} else {
				$this->data['Moneybookers'] = '';
			}
			
			if (isset($this->request->post['LIQPAY'])) {
				$this->data['LIQPAY'] = $this->request->post['LIQPAY'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['LIQPAY'] = $affiliate_info['LIQPAY'];
				} else {
				$this->data['LIQPAY'] = '';
			}
			
			if (isset($this->request->post['SagePay'])) {
				$this->data['SagePay'] = $this->request->post['SagePay'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['SagePay'] = $affiliate_info['SagePay'];
				} else {
				$this->data['SagePay'] = '';
			}
			
			if (isset($this->request->post['twoCheckout'])) {
				$this->data['twoCheckout'] = $this->request->post['twoCheckout'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['twoCheckout'] = $affiliate_info['twoCheckout'];
				} else {
				$this->data['twoCheckout'] = '';
			}
			
			if (isset($this->request->post['GoogleWallet'])) {
				$this->data['GoogleWallet'] = $this->request->post['GoogleWallet'];
				} elseif (!empty($affiliate_info)) { 
				$this->data['GoogleWallet'] = $affiliate_info['GoogleWallet'];
				} else {
				$this->data['GoogleWallet'] = '';
			}
			
			if (isset($this->request->post['cheque'])) {
				$this->data['cheque'] = $this->request->post['cheque'];
				} elseif (!empty($affiliate_info)) {
				$this->data['cheque'] = $affiliate_info['cheque'];			
				} else {
				$this->data['cheque'] = '';
			}
			
			if (isset($this->request->post['paypal'])) {
				$this->data['paypal'] = $this->request->post['paypal'];
				} elseif (!empty($affiliate_info)) {
				$this->data['paypal'] = $affiliate_info['paypal'];		
				} else {
				$this->data['paypal'] = '';
			}
			
			if (isset($this->request->post['bank_name'])) {
				$this->data['bank_name'] = $this->request->post['bank_name'];
				} elseif (!empty($affiliate_info)) {
				$this->data['bank_name'] = $affiliate_info['bank_name'];			
				} else {
				$this->data['bank_name'] = '';
			}
			
			if (isset($this->request->post['bank_branch_number'])) {
				$this->data['bank_branch_number'] = $this->request->post['bank_branch_number'];
				} elseif (!empty($affiliate_info)) {
				$this->data['bank_branch_number'] = $affiliate_info['bank_branch_number'];		
				} else {
				$this->data['bank_branch_number'] = '';
			}
			
			if (isset($this->request->post['bank_swift_code'])) {
				$this->data['bank_swift_code'] = $this->request->post['bank_swift_code'];
				} elseif (!empty($affiliate_info)) {
				$this->data['bank_swift_code'] = $affiliate_info['bank_swift_code'];			
				} else {
				$this->data['bank_swift_code'] = '';
			}
			
			if (isset($this->request->post['bank_account_name'])) {
				$this->data['bank_account_name'] = $this->request->post['bank_account_name'];
				} elseif (!empty($affiliate_info)) {
				$this->data['bank_account_name'] = $affiliate_info['bank_account_name'];		
				} else {
				$this->data['bank_account_name'] = '';
			}
			
			if (isset($this->request->post['bank_account_number'])) {
				$this->data['bank_account_number'] = $this->request->post['bank_account_number'];
				} elseif (!empty($affiliate_info)) {
				$this->data['bank_account_number'] = $affiliate_info['bank_account_number'];			
				} else {
				$this->data['bank_account_number'] = '';
			}
			
			$this->data['back'] = $this->url->link('affiliate/account', '');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/payment.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/affiliate/payment.tpl';
				} else {
				$this->template = 'default/template/affiliate/payment.tpl';
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
		private function validate() {
			
			if ($this->request->post['payment'] == 'qiwi') {
				if (!preg_match("/^[0-9]{10}$/", $this->request->post['qiwi'])) {
					$this->error['qiwi'] = $this->language->get('error_qiwi');
				}
			}
			if ($this->request->post['payment'] == 'card') {
				if (
				(preg_match("/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{1,4}-{0,1}[0-9]{0,3}$/", $this->request->post['card']))
				|| (preg_match("/^[X]{4}-[X]{4}-[X]{4}-[0-9]{1,4}-{0,1}[0-9]{0,3}$/", $this->request->post['card']))
				) 
				{
					$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
					if((empty($affiliate_info['card']))
					&&(preg_match("/^[X]{4}-[X]{4}-[X]{4}-[0-9]{1,4}-{0,1}[0-9]{0,3}$/", $this->request->post['card']))
					)
					{
						$this->error['card'] = $this->language->get('error_card');  
					}               
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
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
	}
?>
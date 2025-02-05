<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {		
		$this->load->model('account/customer');

		if (!empty($this->request->get['token'])) {
			$this->customer->logout();
			$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_country_id']);
			unset($this->session->data['shipping_zone_id']);
			unset($this->session->data['shipping_postcode']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address_id']);
			unset($this->session->data['payment_country_id']);
			unset($this->session->data['payment_zone_id']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);					

			if ($customer_info && $this->customer->login(['field' => 'customer_id', 'value' => $customer_info['customer_id']], false, true)) {
				$this->load->model('account/address');

				$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

				if ($address_info) {
					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_country_id'] = $address_info['country_id'];
						$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
						$this->session->data['shipping_postcode'] = $address_info['postcode'];	
					}

					if ($this->config->get('config_tax_customer') == 'payment') {
						$this->session->data['payment_country_id'] = $address_info['country_id'];
						$this->session->data['payment_zone_id'] = $address_info['zone_id'];
					}
				} else {
					unset($this->session->data['shipping_country_id']);	
					unset($this->session->data['shipping_zone_id']);	
					unset($this->session->data['shipping_postcode']);
					unset($this->session->data['payment_country_id']);	
					unset($this->session->data['payment_zone_id']);	
				}

				$this->redirect($this->url->link('account/account', '', 'SSL')); 
			}
		}		

		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->language->load('account/login');
		$this->data['text_remember_me'] = $this->language->get('text_remember_me');
		$this->data['text_save_me'] 	= $this->language->get('save_me');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->session->data['guest']);

			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

			if ($address_info) {
				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_country_id'] = $address_info['country_id'];
					$this->session->data['shipping_zone_id'] 	= $address_info['zone_id'];
					$this->session->data['shipping_postcode'] 	= $address_info['postcode'];	
				}

				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_country_id'] 	= $address_info['country_id'];
					$this->session->data['payment_zone_id'] 	= $address_info['zone_id'];
				}
			} else {
				unset($this->session->data['shipping_country_id']);	
				unset($this->session->data['shipping_zone_id']);	
				unset($this->session->data['shipping_postcode']);
				unset($this->session->data['payment_country_id']);	
				unset($this->session->data['payment_zone_id']);	
			}
				
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->redirect($this->url->link('account/account', '', 'SSL')); 
			}
		}

		$this->data['breadcrumbs'] = array();

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

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_new_customer'] 			= $this->language->get('text_new_customer');
		$this->data['text_register'] 				= $this->language->get('text_register');
		$this->data['text_register_account'] 		= sprintf($this->language->get('text_register_account'), $this->config->get('config_name'));		
		$this->data['text_register'] 				= sprintf($this->language->get('text_register'), $this->url->link('account/register'));
		$this->data['text_returning_customer'] 		= $this->language->get('text_returning_customer');
		$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$this->data['text_forgotten'] 				= $this->language->get('text_forgotten');
		$this->data['entry_email'] 					= $this->language->get('entry_email');
		$this->data['entry_password'] 				= $this->language->get('entry_password');
		$this->data['button_continue'] 				= $this->language->get('button_continue');
		$this->data['button_login'] 				= $this->language->get('button_login');
		$this->data['placeholder_email'] 			= $this->language->get('placeholder_email');
		$this->data['placeholder_password'] 		= $this->language->get('placeholder_password');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] 		= $this->url->link('account/login', '');
		$this->data['register'] 	= $this->url->link('account/register', '');
		$this->data['forgotten'] 	= $this->url->link('account/forgotten', '');
			
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$this->data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);		  	
		} else {
			$this->data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif(isset($this->request->get['email'])) {
			$this->data['email'] = $this->request->get['email'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		$this->template = 'account/login.tpl';

		$this->children = [
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		];

		$this->response->setOutput($this->render());
	}

	protected function validate() {		
		$autologin = (isset($this->request->post['autologin'])) ? true : false;
		if (!$this->customer->login($this->request->post['email'], $this->request->post['password'], false, $autologin)) {
			$this->error['warning'] = $this->language->get('error_login');
		}

		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}		
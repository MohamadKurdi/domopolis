<?php 
class ControllerAccountOTP extends Controller {
	private $error = array();

	public function index() {		
		$this->load->model('account/customer');
		$this->data = $this->language->load('account/login');	

		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account'));
		}	

		if (!$this->config->get('config_otp_enable')) {
			$this->redirect($this->url->link('account/login'));
		}

		$this->document->setTitle($this->language->get('heading_title'));		

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

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['send_code_action'] 		= $this->url->link('account/otp/send');
		$this->data['validate_code_action'] 	= $this->url->link('account/otp/code');	
		$this->data['old_login_method'] 		= $this->url->link('account/login');							

		$this->template = 'account/otp.tpl';

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

	public function send(){
		$result = [];
		$this->language->load('account/login');

		if (!$this->customer->isLogged()){
			$telephone = $this->request->post['telephone'];

			if ($this->config->get('config_google_recaptcha_contact_enable')){				
				$result['error'] = $this->language->get('error_captcha');
				if (!empty($this->request->post['g-recaptcha-response'])){
					$reCaptcha 			= new \ReCaptcha\ReCaptcha($this->config->get('config_google_recaptcha_contact_secret'));
					$reCaptchaResponse 	= $reCaptcha->setScoreThreshold(0.5)->verify($this->request->post['g-recaptcha-response']);

					if ($reCaptchaResponse->isSuccess()){
						unset($result['error']);
					} else {
						$result['error'] .= ' ' . json_encode($reCaptchaResponse->getErrorCodes());
					}
				}				
			}

			if (empty($result['error']) && !$this->phoneValidator->validate($telephone)){
				$result['error'] = $this->language->get('error_telephone');
			} elseif (empty($result['error'])) {
				if ($senderId = $this->otpLogin->sendOTPCode($telephone)){
					$result['success'] = sprintf($this->language->get('entry_code_sent_to_number'), $this->phoneValidator->format($telephone));
				} else {
					$result['error'] =  $this->language->get('error_code');
				}
			}
		}
	
		$this->response->setJSON($result);
	}

	public function code(){
		$result = [];
		$this->language->load('account/login');

		if (!$this->customer->isLogged()){
			$code = trim($this->request->post['code']);		

			// if ($this->config->get('config_google_recaptcha_contact_enable')){				
			// 	$result['error'] = $this->language->get('error_captcha');
			// 	if (!empty($this->request->post['g-recaptcha-response'])){
			// 		$reCaptcha 			= new \ReCaptcha\ReCaptcha($this->config->get('config_google_recaptcha_contact_secret'));
			// 		$reCaptchaResponse 	= $reCaptcha->setScoreThreshold(0.5)->verify($this->request->post['g-recaptcha-response']);

			// 		if ($reCaptchaResponse->isSuccess()){
			// 			unset($result['error']);
			// 		} else {
			// 			$result['error'] .= ' ' . json_encode($reCaptchaResponse->getErrorCodes());
			// 		}
			// 	}				
			// }

			if (empty($result['error'])) {
				$result['error'] = $this->language->get('error_code_validation');				

				if ($telephone = $this->otpLogin->getCurrentOTPTelephone()){					
					if ($this->otpLogin->validateOTPCode($code)){
						unset($result['error']);

						$result['success'] 		= true;
						$result['customer_id']  = $this->loginOrAddAndLogin($telephone);

					} else {
						$result['error'] = $this->language->get('error_code_incorrect');
					}							
				}												
			}
		}

		$this->response->setJSON($result);
	}

	private function loginOrAddAndLogin($telephone){
		if (!$this->customer->login(['field' => 'telephone', 'value' => $telephone], false, true, true)) {
			$this->load->model('account/customer');
			$this->language->load('account/login');

			$email = generateRandomEmail($this->config->get('config_ssl'));

			$customer_id = $this->model_account_customer->addCustomer([
				'firstname' 		=> $this->language->get('text_client'),
				'lastname' 			=> $telephone,
				'customer_group_id' => $this->config->get('config_customer_group_id'),
				'email' 			=> $email,
				'telephone' 		=> $telephone,
				'fax' 				=> '',
				'password' 			=> generateRandomString(20),
				'company' 			=> '',
				'company_id'		=> 0,
				'tax_id' 			=> 0,
				'address_1' 		=> '',
				'address_2' 		=> '',
				'city' 				=> '',
				'postcode' 			=> '',
				'country_id' 		=> $this->config->get('config_country_id'),
				'zone_id' 			=> '',
				'company' 			=> '',
			]);

			$this->customer->login(['field' => 'customer_id', 'value' => $customer_id], false, true, true);
		}

		return $this->customer->isLogged();
	}
}		
<?php 
class ControllerAccountLoginOTP extends Controller {
	private $error = array();

	public function index() {		
		$this->load->model('account/customer');
		$this->data = $this->language->load('account/login');	

		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account', '', 'SSL'));
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

		$this->data['action'] 		= $this->url->link('account/login_otp/send', '');				

		$this->template = 'account/login_otp.tpl';

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
			$this->otpLogin->sendOTPCode($telephone);
		}


		$this->response->setJSON($result);
	}

	public function code(){
		$code = $this->request->post['code'];



	}


	protected function validate() {		
		

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}		
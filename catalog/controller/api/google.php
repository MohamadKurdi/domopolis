<?php

class ControllerAPIGoogle extends Controller {	

	public function widget(){
		$this->data['logged'] 				= $this->customer->isLogged();
		$this->data['google_auth_nonce'] 	= md5($this->session->getID() . 'google_auth_nonce' . $this->config->get('config_encryption'));
		$this->template = 'blocks/google_sso_widget.tpl';
		$this->response->setOutput($this->render());
	}

	public function bad(){
		header("HTTP/1.1 401 Unauthorized");
		echo 'AUTH BAD JWT';
	}

	private function parseJWTPayloadAndLoginOrRegister($JWTPayload){
		$this->load->model('account/customer');

		$result = ['status' => false];

		if (empty($JWTPayload['given_name'])){
			$JWTPayload['given_name'] = 'Customer';
		}

		$customer = array(
			'firstname' 	=> $JWTPayload['given_name'],
			'lastname' 		=> $JWTPayload['family_name'],
			'email' 		=> $JWTPayload['email'],
			'telephone' 	=> '',
			'fax' 			=> '',
			'password' 		=> md5(time()),
			'company' 		=> '',
			'address_1' 	=> '',
			'address_2' 	=> '',
			'city' 			=> '',
			'postcode' 		=> '',
			'country_id' 	=> $this->config->get('config_country_id'),
			'zone_id' 		=> 0,
			'social_id' 	=> $JWTPayload['sub']
		);
                
        $customer_query = $this->db->ncquery("SELECT * FROM customer WHERE social_id = '" . (string)$this->db->escape($customer['social_id']) . "'");                
        $customer_info 	= $customer_query->row;

        if ($customer_info) {
        	if ($this->customer->login(['field' => 'customer_id', 'value' => $customer_info['customer_id']], false, true)) {
        		unset($this->session->data['guest']);
        		$result = ['status' => true, 'message' => 'Logged you by google_id as ' . $customer_info['email']];
        	} else {
        		$result = ['status' => false, 'message' => 'Could not login you as ' . $customer_info['email']];                            
        	}
        } else {
        	if($customer['email']){ 
        		if ($this->customer->login(['field' => 'email', 'value' => $customer['email']], false, true)) {                            
        			unset($this->session->data['guest']);                        
        			$result = ['status' => true, 'message' => 'Logged you by email as ' . $customer['email']];                            
        		} else {
        			$customer_id = $this->model_account_customer->addCustomer($customer);
        			$this->db->ncquery("UPDATE customer SET social_id = '" . (string)$this->db->escape($customer['social_id']) . "' WHERE customer_id = '" . (int)$customer_id . "'");

        			if($customer_id){                            
        				$customer_info = $this->model_account_customer->getCustomer($customer_id);

        				if ($this->customer->login($customer_info['email'], "", true)) {        					
        					$result = ['status' => true, 'message' => 'Registered you by email as ' . $customer_info['email']];
        				} else {                                
        				}
        			} else {
        				return ['status' => false, 'message' => 'Error happened while registing you by email as ' . $customer_info['email']];
        			}
        		}        
        	}
        }

		return json_encode($result);
	}

	public function login(){
		if (empty($this->request->post['credential'])){
			return $this->bad();			
		}

		try {
			$googleClient 	= new Google_Client(['client_id' => $this->config->get('social_auth_google_app_id')]);
			$JWTPayload 	= $googleClient->verifyIdToken($this->request->post['credential']);

			if (!$JWTPayload) {
				return $this->bad();
			}

			if (empty($JWTPayload['sub']) || empty($JWTPayload['email'])) {
				return $this->bad();
			}

			if ($JWTPayload['aud'] != $this->config->get('social_auth_google_app_id')) {
				return $this->bad();
			}

			if (empty($JWTPayload['nonce']) || $JWTPayload['nonce'] != md5($this->session->getID() . 'google_auth_nonce' . $this->config->get('config_encryption'))) {
				//return $this->bad();
			}

			if ($JWTPayload['iss'] != 'accounts.google.com' && $JWTPayload['iss'] != 'https://accounts.google.com') {
				return $this->bad();
			}

			if ($JWTPayload['exp'] < time()) {
				return $this->bad();
			}

			if (isset($JWTPayload['iat']) && $JWTPayload['iat'] > time() + 300) {
				return $this->bad();
			}			

			$this->response->setOutput($this->parseJWTPayloadAndLoginOrRegister($JWTPayload));

		} catch (\Exception $e){
			echo $e->getMessage();
			return $this->bad();
		}
	}

}
<?php
	class ControllerModuleSocialAuth extends Controller {
		private $error = array();
		
		public function index() {
			echo 'false';
		}
		
		public function register() {
			$this->load->language('account/edit');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('account/customer');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->register_validate()) {
				
				$customer = array(
				'firstname' => $this->request->post['firstname'],
				'lastname' 	=> $this->request->post['lastname'],
				'email' 	=> $this->request->post['email'],
				'telephone' => '',
				'fax' 		=> '',
				'password' 	=> md5(time()),
				'company' 	=> '',
				'address_1' => '',
				'address_2' => '',
				'city' 		=> '',
				'postcode' 	=> '',
				'country_id' 	=> $this->config->get('config_country_id'),
				'zone_id' 		=> 0,
				'social_id' 	=> $this->request->post['social_id'],
                );
				
				$customer_id = $this->model_account_customer->addCustomer($customer);				
				$this->db->query("UPDATE customer SET social_id = '" . (string)$customer['social_id'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
				
				if($customer_id){
					$customer_info = $this->model_account_customer->getCustomer($customer_id);
					
					if ($this->customer->login($customer_info['email'], "", true)) {
						$this->login($customer_info);
						$this->response->redirect($this->url->link('account/account', '', 'SSL'));
					}
				}
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home')
			);
			
			$this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit'),
            'href'      => $this->url->link('account/edit', '', 'SSL')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_your_details'] = $this->language->get('text_your_details');
			$this->data['text_additional'] = $this->language->get('text_additional');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_loading'] = $this->language->get('text_loading');
			
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_back'] = $this->language->get('button_back');
			$this->data['button_upload'] = $this->language->get('button_upload');
			
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
			
			$this->data['action'] = $this->url->link('module/social_auth/register', '', 'SSL');
			
			if ($this->request->server['REQUEST_METHOD'] != 'POST') {
				$customer_info = $this->session->data['social_auth'];
			}
			
			if (isset($this->request->post['firstname'])) {
				$this->data['firstname'] = $this->request->post['firstname'];
				} elseif (!empty($customer_info)) {
				$this->data['firstname'] = $customer_info['firstname'];
				} else {
				$this->data['firstname'] = '';
			}
			
			if (isset($this->request->post['lastname'])) {
				$this->data['lastname'] = $this->request->post['lastname'];
				} elseif (!empty($customer_info)) {
				$this->data['lastname'] = $customer_info['lastname'];
				} else {
				$this->data['lastname'] = '';
			}
			
			if (isset($this->request->post['email'])) {
				$this->data['email'] = $this->request->post['email'];
				} elseif (!empty($customer_info)) {
				$this->data['email'] = $customer_info['email'];
				} else {
				$this->data['email'] = '';
			}
			
			if (isset($this->request->post['telephone'])) {
				$this->data['telephone'] = $this->request->post['telephone'];
				} elseif (!empty($customer_info)) {
				$this->data['telephone'] = $customer_info['telephone'];
				} else {
				$this->data['telephone'] = '';
			}
			
			if (isset($this->request->post['social_id'])) {
				$this->data['social_id'] = $this->request->post['social_id'];
				} elseif (!empty($customer_info)) {
				$this->data['social_id'] = $customer_info['social_id'];
				} else {
				$this->data['social_id'] = '';
			}
			
			$this->data['back'] = $this->url->link('common/home', '', 'SSL');
			
			$this->data['column_left'] = $this->load->controller('common/column_left');
			$this->data['column_right'] = $this->load->controller('common/column_right');
			$this->data['content_top'] = $this->load->controller('common/content_top');
			$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
			$this->data['footer'] = $this->load->controller('common/footer');
			$this->data['header'] = $this->load->controller('common/header');
			
			$this->response->setOutput($this->load->view('module/social_auth_register', $data));
		}
		
		// validate fiels register function
		protected function register_validate() {
			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$this->error['firstname'] = $this->language->get('error_firstname');
			}
			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$this->error['lastname'] = $this->language->get('error_lastname');
			}
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match($this->config->get('config_mail_regexp'), $this->request->post['email'])) {
				$this->error['email'] = $this->language->get('error_email');
			}
			if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				//$this->error['telephone'] = $this->language->get('error_telephone');
			}
			return !$this->error;
		}
		
		// method login to Google with  oauth2
		public function iframeGoogleLogin(){
			
			$redirect_href = $this->url->link('module/social_auth/iframeGoogleLogin');
			
			$google_app_id = $this->config->get('social_auth_google_app_id');
			$google_secret_key = $this->config->get('social_auth_google_secret_key');
			
			$link = 'https://accounts.google.com/o/oauth2/v2/auth?scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&access_type=offline&include_granted_scopes=true&state=state_parameter_passthrough_value&' .
            'redirect_uri=' . urlencode($redirect_href) . '&response_type=code&client_id=' . $google_app_id . '';
			
			if (!isset($this->request->get['code'])) {
				// not find code
				$this->response->redirect($link);
				
				} else {
				
				$code = $this->request->get['code'];
				
				$data = $this->getTokenWithCurl('https://accounts.google.com/o/oauth2/token',array(
				'code' => $code,
				'client_id' => $google_app_id,
				'client_secret' => $google_secret_key,
				'redirect_uri' => $redirect_href,
				'grant_type' => 'authorization_code',
                ));
				// get token
				$token = $data->access_token;
				
				$url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token;
				
				$result = $this->getCurl($url);
				
				if(isset($result->id)){
					
					$customer = array(
                    'firstname' 	=> @$result->given_name,
                    'lastname' 		=> @$result->family_name,
                    'email' 		=> @$result->email,
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
                    'social_id' 	=> $result->id,
					);
					
					$return_data = $this->toLoginRegister($customer);
					
					if($return_data['status']){
						
						$this->response->setOutput('<script>
                        window.opener.document.location = "'. $return_data['redirect'] . '";
                        window.close();
                        </script>');
						
						} else {
						echo $return_data['text']; exit;
					}
					
					} else {
					echo 'not find user'; exit;
				}
			}
		}
		
		// method login to Facebook with  oauth2
		public function iframeFacebookLogin(){
			
			$redirect_href = $this->url->link('module/social_auth/iframeFacebookLogin');
			
			$facebook_app_id = $this->config->get('social_auth_facebook_app_id');
			$facebook_secret_key = $this->config->get('social_auth_facebook_secret_key');
			
			$link = 'https://www.facebook.com/v3.2/dialog/oauth?client_id=' . $facebook_app_id . '&redirect_uri=' . urlencode($redirect_href) . '&auth_type=rerequest&scope=email';
			
			if (!isset($this->request->get['code'])) {
				
				$this->response->redirect($link);
				
				} else {
				
				$code = $this->request->get['code'];
				
				$url_token = 'https://graph.facebook.com/v3.2/oauth/access_token?client_id='.$facebook_app_id.'&redirect_uri=' . urlencode($redirect_href) . '&client_secret='.$facebook_secret_key.'&code='.$code.'';
				
				$data = $this->getCurl($url_token);
				
				$url = 'https://graph.facebook.com/me?fields=about,first_name,last_name,email&access_token=' . $data->access_token;
				
				
				$result = $this->getCurl($url);
				
				if(isset($result->error)){
					echo @$result->error->message; exit;
				}
				
				if(isset($result->id)){
					
					$data_customer = array(
                    'firstname' => @$result->first_name,
                    'lastname' => @$result->last_name,
                    'email' => @$result->email,
                    'telephone' => '',
                    'fax' => '',
                    'password' => md5(time()),
                    'company' => '',
                    'address_1' => '',
                    'address_2' => '',
                    'city' => '',
                    'postcode' => '',
                    'country_id' => $this->config->get('config_country_id'),
                    'zone_id' => 0,
                    'social_id' => $result->id,
					);
					
					$return_data = $this->toLoginRegister($data_customer);
					
					if($return_data['status']){
						
						$this->response->setOutput('<script>
                        window.opener.document.location = "'.$return_data['redirect'].'";
                        window.close();
                        </script>');
						
						} else {
						echo $return_data['text']; exit;
					}
					} else {
					echo 'not find user'; exit;
				}
			}
		}
		
		public function iframeInstagramLogin(){
			
			$redirect_href = HTTPS_SERVER.'insta_login';
			
			$insatagram_client_id = trim($this->config->get('social_auth_insatagram_client_id'));
			$insatagram_secret_key = trim($this->config->get('social_auth_insatagram_secret_key'));
			
			$link = 'https://api.instagram.com/oauth/authorize/?client_id=' . $insatagram_client_id . '&redirect_uri=' . urlencode($redirect_href) . '&response_type=code';
			
			if (!isset($this->request->get['code'])) {
				
				$this->response->redirect($link);
				
				} else {
				
				$code = $this->request->get['code'];
				
				$data = $this->getTokenWithCurl('https://api.instagram.com/oauth/access_token',array(
				'code' => $code,
				'client_id' => $insatagram_client_id,
				'client_secret' => $insatagram_secret_key,
				'redirect_uri' => $redirect_href, //это на случай если на сайте, к которому обращаемся проверяется была ли нажата кнопка submit, а не была ли оправлена форма
				'grant_type' => 'authorization_code',
                ));
				
				$token = $data->access_token;
				
				$url = 'https://api.instagram.com/v1/users/self/?access_token=' . $token;
				
				$result = $this->getCurl($url);
				
				if(isset($result->data->id)){
					
					$customer = array(
                    'firstname' => @$result->data->username,
                    'lastname' => @$result->data->full_name,
                    'email' => '',
                    'telephone' => '',
                    'fax' => '',
                    'password' => md5(time()),
                    'company' => '',
                    'address_1' => '',
                    'address_2' => '',
                    'city' => '',
                    'postcode' => '',
                    'country_id' => 104,
                    'zone_id' => 0,
                    'social_id' => $result->data->id,
					);
					
					$return_data = $this->toLoginRegister($customer);										
					
					if($return_data['status']){
						
						$this->response->setOutput('<script>
                        window.opener.document.location = "'.$return_data['redirect'].'";
                        window.close();
                        </script>');
						
						} else {
						echo $return_data['text']; exit;
					}
					} else {
					echo 'not find user'; exit;
				}
			}
		}
		
		// login or redister user
		private function toLoginRegister($customer){
			
			if ($this->cart->hasProducts()){
				$redirect_after = $this->url->link('checkout/cart', '', 'SSL');
				} else {
				$redirect_after = $this->url->link('account/account', '', 'SSL');
			}
						
			$redirect_after_register = $this->url->link('account/simpleedit', '', 'SSL');
			//$redirect_after_register = $this->url->link('module/social_auth/register', '', 'SSL');
			
			if($customer['social_id']){
                $this->load->model('account/customer');
                
                $customer_query = $this->db->query("SELECT * FROM customer WHERE social_id = '" . (string)$this->db->escape($customer['social_id']) . "'");                
                $customer_info = $customer_query->row;
                
                if ($customer_info) {
                    if(!$customer_info['approved']){
                        return ['status' => false, 'text' => 'not approved'];
                        } else {
                        // login customer
						
						if (!trim($customer_info['email'])){
							$customer_info['email'] = $customer_info['customer_id'];
						}
						
                        if ($this->customer->login($customer_info['email'], "", true)) {
                            $this->login($customer_info);
                            
                            return ['status' => true, 'text' => 'login','redirect' => $redirect_after];
                            
                            } else {
                            return ['status' => false, 'text' => 'not login'];                            
						}
					}
                    } else {
                    // add customer
                    
                    if($customer['email']){                        
                        if ($this->customer->login($customer['email'], "", true)) {                            
                            $this->login($customer);                            
                            return ['status' => true, 'text' => 'login','redirect' => $redirect_after];                            
						}
                        
                        $customer_id = $this->model_account_customer->addCustomer($customer);
                        
                        $this->db->query("UPDATE customer SET social_id = '" . (string)$this->db->escape($customer['social_id']) . "' WHERE customer_id = '" . (int)$customer_id . "'");
                        
                        if($customer_id){                            
                            $customer_info = $this->model_account_customer->getCustomer($customer_id);
                            
                            if ($this->customer->login($customer_info['email'], "", true)) {
                                
                                $this->login($customer_info);
                                
                                return ['status' => true, 'text' => 'login','redirect' => $redirect_after];
                                
                                } else {
                                return ['status' => false, 'text' => 'not login'];
                                $this->model_account_customer->deleteLoginAttempts($customer_info['email']);
							}
                            } else {
                            return ['status' => false, 'text' => 'not register customer'];
						}
                        } else {
                        
                        $this->session->data['social_auth'] = $customer;
                        
                        return ['status' => true, 'text' => 'login','redirect' => $redirect_after_register];
					}
				}
			}
		}
		
		// method unset guest data with login 
		protected function login($customer_info){
			
			$this->load->model('account/customer');
			//	$this->model_account_customer->addLoginAttempt($customer_info['email']);
			
			// login OK
			unset($this->session->data['guest']);
			$this->load->model('account/address');
			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}
			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}			
		}
		
		
		// get curl POST
		private function getTokenWithCurl($url = '',$data = array()){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
			));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query($data)
			);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
			curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
			$data = curl_exec($ch);
			curl_close($ch);
			
			return json_decode($data);
		}
		
		// get curl GET
		protected function getCurl($url){
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
			));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data);
			
			return $data;
		}		
	}

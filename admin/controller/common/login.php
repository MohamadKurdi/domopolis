<?php  
	class ControllerCommonLogin extends Controller { 
		private $error = array();
		
		public function index() { 
			$this->language->load('common/login');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
				$this->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
				$this->session->data['token'] = md5(mt_rand());
				
				if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
					$this->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
					} else {
					$this->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}
			
			$this->data['isMobile'] = $this->mobileDetect->isMobile();
			$this->data['isAndroid'] = $this->mobileDetect->isAndroid();
			
			if ($this->config->get('config_ldap_auth_enable')){							
				$connection = @fsockopen($this->config->get('config_ldap_host'), 3268, $error, $error_msg, 3);

				if (!is_resource($connection)){
					$this->data['connection_error_message'] = $error_msg;
				} else {
					fclose($connection);
				}
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_login'] = $this->language->get('text_login');
			$this->data['text_forgotten'] = $this->language->get('text_forgotten');
			
			$this->data['entry_username'] = $this->language->get('entry_username');
			$this->data['entry_password'] = $this->language->get('entry_password');
			
			$this->data['button_login'] = $this->language->get('button_login');
			
			if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
				$this->error['warning'] = $this->language->get('error_token');
			}
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->data['action'] = $this->url->link('common/login', '');
			
			if (isset($this->request->post['username'])) {
				$this->data['username'] = $this->request->post['username'];
				} else {			
				if (!empty($this->request->cookie[AUTH_UNAME_COOKIE])){
					$this->data['username'] = base64_decode($this->request->cookie[AUTH_UNAME_COOKIE]);
					$this->data['username'] = str_replace(AUTH_PASSWDSALT_COOKIE, '', $this->data['username']);
					} else {
					$this->data['username'] = '';
				}
			}
			
			if (isset($this->request->post['password'])) {
				$this->data['password'] = $this->request->post['password'];
				} else {
				
				if (!empty($this->request->cookie[AUTH_PASSWD_COOKIE])){
					$this->data['password'] = base64_decode($this->request->cookie[AUTH_PASSWD_COOKIE]);
					$this->data['password'] = str_replace(AUTH_PASSWDSALT_COOKIE, '', $this->data['password']);
					} else {		
					$this->data['password'] = '';
				}
			}
			
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
				
				unset($this->request->get['route']);
				
				if (isset($this->request->get['token'])) {
					unset($this->request->get['token']);
				}
				
				$url = '';
				
				if ($this->request->get) {
					$url .= http_build_query($this->request->get);
				}
				
				$this->data['redirect'] = $this->url->link($route, $url);
				} else {
				$this->data['redirect'] = '';	
			}
			
			if ($this->config->get('config_password') && false) {
				$this->data['forgotten'] = $this->url->link('common/forgotten', '');
				} else {
				$this->data['forgotten'] = '';
			}
			
			$this->template = 'common/login.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validate() {
			if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');
			}
			
			if (!$this->error) {
				setcookie(AUTH_UNAME_COOKIE, base64_encode($this->request->post['username'] . AUTH_PASSWDSALT_COOKIE), time() + (1000 * 60 * 60 * 24 * 90));
				setcookie(AUTH_PASSWD_COOKIE, base64_encode($this->request->post['password'] . AUTH_PASSWDSALT_COOKIE), time() + (1000 * 60 * 60 * 24 * 90));
				return true;
				} else {
				return false;
			}
		}
	}  		
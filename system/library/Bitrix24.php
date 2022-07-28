<?php
	final class Bitrix24 { 	
		
		private $request;
		private $log;
		private $appsConfig;
		
		private $bitrixDomain = null;
		private $bitrixScope = null;
		private $CLIENT_ID = null;
		private $CLIENT_SECRET = null;
		
		public function __construct($registry) {
			
			$this->request 	= $registry->get('request');			
			$this->log 		= $registry->get('log');
			$this->config 	= $registry->get('config');

			$this->bitrixDomain		= $this->config->get('config_bitrix_bot_domain');
			$this->bitrixScope 		= $this->config->get('config_bitrix_bot_scope');
			$this->CLIENT_ID 		= $this->config->get('config_bitrix_bot_client_id');
			$this->CLIENT_SECRET 	= $this->config->get('config_bitrix_bot_client_secret');

			$appsConfig     = [];

			if ($this->bitrixDomain){
				$configFileName = DIR_SYSTEM . 'config/bitrixbot' . trim(str_replace('.', '_', $this->bitrixDomain)) . '.php';
				
			//при вызове от бота
				if (isset($this->request->request['auth'])){
					$configFileName = DIR_SYSTEM . 'config/bitrixbot' . trim(str_replace('.', '_', $this->request->request['auth']['domain'])) . '.php';
					if (file_exists($configFileName)) {
						include_once $configFileName;
					}
					
					$this->appsConfig = $appsConfig;
					
			//при вызове напрямую
				} else {
					
					if (file_exists($configFileName)) {
						include_once $configFileName;
					}
					
					$this->appsConfig = $appsConfig;
				}			
			}
		}
		
		public function checkAuth(){
			
			if (!isset($this->appsConfig[$this->request->post['auth']['application_token']])) {
				return false;
			}
			
			return true;
		}
		
		/**
			* Save application configuration.
			*
			* @param $params
			*
			* @return bool
		*/
		public function saveParams($params, $domain = '') {
		
			$_domain = '';
			if (isset($this->request->request['auth'])){
				$_domain = $this->request->request['auth']['domain'];
			} else {
				$_domain = $domain;
			}
		
			$config = "<?php\n";
			$config .= "\$appsConfig = " . var_export($params, true) . ";\n";
			$config .= "?>";
			$configFileName = DIR_SYSTEM . 'config/bitrixbot' . trim(str_replace('.', '_', $_domain)) . '.php';
			file_put_contents($configFileName, $config);
			return true;
		}
		
		/**
			* Send rest query to Bitrix24.
			*
			* @param $method - Rest method, ex: methods
			* @param array $params - Method params, ex: Array()
			* @param array $auth - Authorize data, ex: Array('domain' => 'https://test.bitrix24.com', 'access_token' => '7inpwszbuu8vnwr5jmabqa467rqur7u6')
			* @param boolean $authRefresh - If authorize is expired, refresh token
			* @return mixed
		*/
		function restCommand($method, array $params = Array(), array $auth = Array(), $authRefresh = true)
		{
			
			if (!$auth){	
				$_tmp = array_values($this->appsConfig);
				$_auth = $_tmp[0]['AUTH'];
				} else {
				$_auth = $auth;
			}
			
			$queryUrl = "https://".$_auth["domain"]."/rest/".$method;
			$queryData = http_build_query(array_merge($params, array("auth" => $_auth["access_token"])));
			
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
			));
			
			$result = curl_exec($curl);
			curl_close($curl);
			
			$result = json_decode($result, 1);		
			
			$this->log->write($result);
			
			if ($authRefresh && isset($result['error']) && in_array($result['error'], array('expired_token', 'invalid_token')))
			{
				$_auth = $this->restAuth($_auth);
				if ($_auth)
				{
					$result = $this->restCommand($method, $params, $_auth, false);
				}
			}
			
			return $result;
		}
		
		/**
			* Get new authorize data if you authorize is expire.
			*
			* @param array $auth - Authorize data, ex: Array('domain' => 'https://test.bitrix24.com', 'access_token' => '7inpwszbuu8vnwr5jmabqa467rqur7u6')
			* @return bool|mixed
		*/
		function restAuth($auth)
		{
			if (!$this->CLIENT_ID || !$this->CLIENT_SECRET)
			return false;
			
			if(!isset($auth['refresh_token']) || !isset($auth['scope']) || !isset($auth['domain']))
			return false;
			
			$queryUrl = 'https://'.$auth['domain'].'/oauth/token/';
			$queryData = http_build_query($queryParams = array(
			'grant_type' => 'refresh_token',
			'client_id' => $this->CLIENT_ID,
			'client_secret' => $this->CLIENT_SECRET,
			'refresh_token' => $auth['refresh_token'],
			'scope' => $auth['scope'],
			));						
			
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl.'?'.$queryData,
			));
			
			$result = curl_exec($curl);
			curl_close($curl);
			
			$result = json_decode($result, 1);					
			
			if (!isset($result['error']))
			{
				$appsConfig = Array();								
				$result['application_token'] = $auth['application_token'];				
				$appsConfig[$auth['application_token']]['AUTH'] = $result;
				$this->saveParams($appsConfig, $result['domain']);
			}
			else
			{
				$result = false;
			}
			
			return $result;
		}
		
		
		
	}												
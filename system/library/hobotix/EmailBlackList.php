<?
	
	namespace hobotix;
	
	
	final class EmailBlackList
	{
		
		private $db;	
		private $exclude = ['kitchen-profi', 'ims-group'];
		
		private $reacherCONFIG = [];
		
		private $passReacherResults = [
		'mail.ru',
		'ukr.net',
		'bk.ru',
		'list.ru',
		'i.ua'
		];
		
		public function __construct($registry){
			
			$this->config = $registry->get('config');
			$this->db = $registry->get('db');

			$this->reacherCONFIG = [
				'URI' 	=> 	$this->config->get('config_reacher_uri'),
				'AUTH' 	=>	$this->config->get('config_reacher_auth'),
				'KEY'	=> 	$this->config->get('config_reacher_key'),
				'FROM'	=> 	$this->config->get('config_reacher_from'),
				'HELO'	=> 	$this->config->get('config_reacher_helo')
			];
			
		}
		
		public function reacherVerify($email){

			if (!$this->config->get('config_reacher_enable')){
				return [];
			}
			$email = trim($email);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 			
			curl_setopt($ch, CURLOPT_URL, $this->reacherCONFIG['URI']);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER,[
			$this->reacherCONFIG['AUTH'] . ':' . $this->reacherCONFIG['KEY'],
			'Content-Type: application/json',
			'Connection: keep-alive',
			]);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode(
			[
			'to_email' => $email, 
			'from_email' => $this->reacherCONFIG['FROM'], 
			'hello_name' => $this->reacherCONFIG['HELO']
			]));
			
			$result = curl_exec ($ch);
			curl_close ($ch);
			
			return $result;
		}
		
		public function checkVerifier($email, $boolean = false){
			$email = trim($email);
			
			$validator = \EmailValidation\EmailValidatorFactory::create($email);
			$validationResult = $validator->getValidationResults()->asArray();
			
			$json = [
			'is_reachable'	=> 'unknown',
			'mx'			=> ['accepts_mail' => false],
			'smtp'			=> ['has_full_inbox' => false, 'is_disabled' => false, 'is_deliverable' => true]										
			];
			
			if ($validationResult['valid_format']){
				$json['is_reachable']	= 'verifier';
			}
			
			$json['mx']['accepts_mail'] = $validationResult['valid_mx_records'] && $validationResult['valid_host'];
			
			if ($boolean){							
								
				if ($validationResult['possible_email_correction']){
					return (string)$validationResult['possible_email_correction'];
				}
			
				return (bool)($validationResult['valid_format'] && $validationResult['valid_mx_records'] && $validationResult['valid_host']);
			}			
			
			return json_encode($json);
		}
		
		public function checkfull($email, $timeout = false){
			$email = trim($email);
			
			if ($timeout){
				echoLine('[REACHER] ' . $timeout, 'e');
			}
			
			$exploded = explode('@', $email);
			$domain = array_pop($exploded);
			
			if (in_array($domain, $this->passReacherResults) || !$this->config->get('config_reacher_enable')){			
				$json = $this->checkVerifier($email);
				} else {
				$json = $this->reacherVerify($email);
			}
			
			if ($json = json_decode($json, true)){
				if(php_sapi_name() == "cli"){
					echoLine('[REACHER] ' . $email . ': ' . $json['is_reachable'], 's');
				}
				
				if ($json['is_reachable'] == 'verifier'){
					if ($json['mx']['accepts_mail']){
						$this->whitelist($email, 'accepts_mail');
						return true;	
						} else {
						$this->blacklist($email, 'mx_not_accepts_mail');
						return false;
					}
				}
				
				if ($json['is_reachable'] == 'unknown'){
					print_r($json);
					
					if ($timeout){
						if ($json['mx']['accepts_mail']){
							$this->whitelist($email, 'accepts_mail');
							return true;	
							} else {
							$this->blacklist($email, 'mx_not_accepts_mail');
							return false;
						}
					}
					
					if (!$timeout){
						return $this->checkfull($email, 3);
					}					
				}
				
				if ($json['is_reachable'] == 'safe'){
					$this->whitelist($email, 'safe');
					return true;
				}
				
				if ($json['is_reachable'] == 'invalid'){
					$this->blacklist($email, 'invalid');
					return false;
				}
				
				if ($json['is_reachable'] == 'risky'){
					
					//Если полный инбокс
					if ($json['smtp']['has_full_inbox']){
						$this->blacklist($email, 'has_full_inbox');
						return false;
					}
					
					//Если отключена учетка
					if ($json['smtp']['is_disabled']){
						$this->blacklist($email, 'is_disabled');
						return false;
					}
					
					//Если не is_deliverable
					if (!$json['smtp']['is_deliverable']){
						$this->blacklist($email, 'is_not_deliverable');
						return false;
					}
					
					$this->whitelist($email, 'risky');
					return true;
				}				
				
				} else {				
				
				throw new \Exception ("REACHER FAIL: " . $json);
				die($json);
				
			}
			
		}
		
		public function check($email){
			$email = trim($email);
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				return false;
			}
			
			foreach ($this->exclude as $exclude){
				if (strpos($email, $exclude) !== false){
					return false;
				}
			}
			
			$query = $this->db->ncquery("SELECT * FROM customer_emails_blacklist WHERE email LIKE '" . $this->db->escape($email) . "'");
			
			if ($query->num_rows){
				return false;
			}
			
			
			return true;
		}
		
		
		public function whitelist($email, $status){
			$email = trim($email);
			
			$query = $this->db->ncquery("DELETE FROM customer_emails_blacklist WHERE email LIKE '" . $this->db->escape($email) . "'");
			$query = $this->db->ncquery("INSERT IGNORE INTO customer_emails_whitelist SET email = '" . $this->db->escape($email) . "', status = '" . $this->db->escape($status) . "'");
		}
		
		public function blacklist_check($email){		
			$query = $this->db->ncquery("SELECT * FROM customer_emails_blacklist WHERE email LIKE '" . $this->db->escape($email) . "'");
			
			if ($query->num_rows){
				return true;
			}
			
			return false;
		}
		
		public function whitelist_check($email){		
			$query = $this->db->ncquery("SELECT * FROM customer_emails_whitelist WHERE email LIKE '" . $this->db->escape($email) . "'");
			
			if ($query->num_rows){
				return true;
			}
			
			return false;
		}
		
		public function blacklist($email, $status){
			$email = trim($email);
			
			$query = $this->db->ncquery("DELETE FROM customer_emails_whitelist WHERE email LIKE '" . $this->db->escape($email) . "'");
			
			$query = $this->db->ncquery("INSERT IGNORE INTO customer_emails_blacklist SET email = '" . $this->db->escape($email) . "', status = '" . $this->db->escape($status) . "'");
			$query = $this->db->ncquery("UPDATE customer SET mail_status = '" . $this->db->escape($status) . "' WHERE email = '" . $this->db->escape($email) . "'");
		}
		
		
		public function blacklist_remove($email){
			$email = trim($email);
			
			$query = $this->db->ncquery("DELETE FROM customer_emails_blacklist WHERE email LIKE '" . $this->db->escape($email) . "'");
		}

		
	}											
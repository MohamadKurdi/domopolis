<?php
	class Customer {
		private $customer_id;
		private $store_id;
		private $firstname;
		private $lastname;
		private $discount_card;
		private $discount_percent;
		private $birthday;
		private $has_birthday;
		private $email;
		private $telephone;
		private $fax;
		private $newsletter;
		private $newsletter_news;
		private $newsletter_personal;
		private $customer_group_id;
		private $address_id;
		private $sendpulse_push_id;
		private $tracking;
		private $is_opt;
		private $affiliateNZ;
		private $opt_group_array = array(8,9,10,11);
		private $affiliate_paid;
		
				
		public function __construct($registry) {			
			$this->config 	= $registry->get('config');
			$this->db 		= $registry->get('db');
			$this->log 		= $registry->get('log');
			$this->request 	= $registry->get('request');
			$this->session 	= $registry->get('session');
			$this->user 	= $registry->get('user');			
			
			if (isset($this->session->data['customer_id'])) { 
				$customer_query = $this->db->ncquery("SELECT * FROM customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1' AND store_id = '" . $this->config->get('config_store_id') . "'");
				
				if ($customer_query->num_rows) {
					
					if (!$customer_query->row['tracking']){
						if (isset($this->request->cookie['tracking'])){
							$this->setTracking($this->request->cookie['tracking']);
						}					
					}
					
					$this->customer_id 				= $customer_query->row['customer_id'];			
					$this->affiliate_paid 			= $customer_query->row['affiliate_paid'];			           
					$this->firstname 				= $customer_query->row['firstname'];
					$this->lastname 				= $customer_query->row['lastname'];
					$this->discount_card 			= $customer_query->row['discount_card'];
					$this->discount_percent 		= $customer_query->row['discount_percent'];
					$this->email 					= $customer_query->row['email'];
					$this->telephone 				= $customer_query->row['telephone'];
					$this->birthday 				= $customer_query->row['birthday'];
					$this->has_birthday 			= $this->checkIfHasBirthday($customer_query->row['birthday_month'], $customer_query->row['birthday_date']);
					$this->fax 						= $customer_query->row['fax'];
					$this->newsletter 				= $customer_query->row['newsletter'];
					$this->newsletter_news 			= $customer_query->row['newsletter_news'];
					$this->newsletter_personal 		= $customer_query->row['newsletter_personal'];
					$this->customer_group_id 		= $customer_query->row['customer_group_id'];
					$this->address_id 				= $customer_query->row['address_id'];
					$this->sendpulse_push_id 		= $customer_query->row['sendpulse_push_id'];
					$this->tracking 				= $customer_query->row['tracking'];
					$this->is_opt 					= in_array($this->customer_group_id, $this->opt_group_array);
					
					$this->db->ncquery("UPDATE customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
					
					$query = $this->db->ncquery("SELECT * FROM customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
					
					if (!$query->num_rows) {
						$this->db->ncquery("INSERT INTO customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
					}
					} else {
					$this->logout();
				}
				
				} elseif (!$this->isLogged() && !empty($this->request->cookie['em']) && !empty($this->request->cookie['p'])) {
				$this->login($this->request->cookie['em'], $this->request->cookie['p']);
			}			
		}
		
		public function login($email, $password, $override = false, $autologin = false) {
			if ($override) {
				if (is_numeric($email)){					
					$customer_query = $this->db->ncquery("SELECT * FROM customer where 
					customer_id = '" . (int)$email . "' AND status = '1' AND store_id = '" . $this->config->get('config_store_id') . "'");					
					} else {
					$customer_query = $this->db->ncquery("SELECT * FROM customer where 
					(LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "') AND status = '1' AND store_id = '" . $this->config->get('config_store_id') . "'");
				}
				
				} else {
					$_IS_EMAIL = $_IS_PHONE_OR_DISCOUNT = false;
				
				if (filter_var($email, FILTER_VALIDATE_EMAIL)){
					$_IS_EMAIL = true;
					} elseif (mb_strlen(trim(preg_replace("([^0-9])", "", $email))) > 0) {
					$_IS_PHONE_OR_DISCOUNT = true;				
				}
				
				if ($_IS_EMAIL){
					$customer_query = $this->db->ncquery("SELECT * FROM customer WHERE 
					(LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "')  		
					AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '". $this->db->escape($password) ."' OR password = '" . $this->db->escape(md5($password)) . "')
					AND status = '1'
					AND approved = '1'");
					} elseif ($_IS_PHONE_OR_DISCOUNT) {
					$customer_query = $this->db->ncquery("SELECT * FROM customer WHERE 
					(REPLACE(discount_card,' ','')  LIKE '" . $this->db->escape(str_replace(' ','', $email)) . "'
					OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '". $this->db->escape(trim(preg_replace("([^0-9])", "", $email))) ."' )
					OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '". $this->db->escape(trim(preg_replace("([^0-9])", "", $email))) ."' )	
					)  		
					AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '". $this->db->escape($password) ."' OR password = '" . $this->db->escape(md5($password)) . "')
					AND status = '1'
					AND approved = '1'
					AND store_id = '" . $this->config->get('config_store_id') . "'
					");
					} else {
					$customer_query = $this->db->ncquery("SELECT * FROM customer WHERE customer_id = '-1'");				
				}
				
				
			}
			
			if ($customer_query->num_rows) {
				$this->session->data['customer_id'] = $customer_query->row['customer_id'];	
				
				if (!isset($this->session->data['cart']) || empty($this->session->data['cart'])){
					
					if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
						$cart = unserialize($customer_query->row['cart']);
						
						if (!isset($this->session->data['cart'])){
							$this->session->data['cart'] = array();
						}
						
						foreach ($cart as $key => $value) {
							if (!array_key_exists($key, $this->session->data['cart'])) {
								$this->session->data['cart'][$key] = $value;
								} else {
								$this->session->data['cart'][$key] += $value;
							}
						}			
					}
					
				}
				
				if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
					if (!isset($this->session->data['wishlist'])) {
						$this->session->data['wishlist'] = array();
					}
					
					$wishlist = unserialize($customer_query->row['wishlist']);
					
					foreach ($wishlist as $product_id) {
						if (!in_array($product_id, $this->session->data['wishlist'])) {
							$this->session->data['wishlist'][] = $product_id;
						}
					}			
				}
				
				$this->customer_id 			= $customer_query->row['customer_id'];								
				$this->affiliate_paid 		= $customer_query->row['affiliate_paid'];								
				$this->firstname 			= $customer_query->row['firstname'];
				$this->lastname 			= $customer_query->row['lastname'];
				$this->discount_card 		= $customer_query->row['discount_card'];
				$this->discount_percent 	= $customer_query->row['discount_percent'];
				$this->email 				= $customer_query->row['email'];
				$this->telephone 			= $customer_query->row['telephone'];
				$this->birthday 			= $customer_query->row['birthday'];
				$this->has_birthday 		= $this->checkIfHasBirthday($customer_query->row['birthday_month'], $customer_query->row['birthday_date']);
				$this->fax 					= $customer_query->row['fax'];
				$this->newsletter 			= $customer_query->row['newsletter'];
				$this->newsletter_news 		= $customer_query->row['newsletter_news'];
				$this->newsletter_personal 	= $customer_query->row['newsletter_personal'];
				$this->customer_group_id 	= $customer_query->row['customer_group_id'];
				$this->address_id 			= $customer_query->row['address_id'];
				$this->sendpulse_push_id 	= $customer_query->row['sendpulse_push_id'];
				$this->tracking 			= $customer_query->row['tracking'];
				$this->is_opt 				= in_array($this->customer_group_id, $this->opt_group_array);
				
				$this->db->ncquery("UPDATE customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
				
				if($autologin) {
					$salt = $this->db->ncquery("SELECT salt FROM customer WHERE customer_id = '".$customer_query->row['customer_id']."'")->row['salt'];
					$password_c = sha1($salt . sha1($salt . sha1($password)));
					setcookie('em', $email, time()+60 * 60 * 24 * 30);
					setcookie('p', $password_c, time()+60 * 60 * 24 * 30);
				}
												
				if(isset($this->affiliateNZ) && is_object($this->affiliateNZ) && !$this->affiliateNZ->isLogged() & $this->config->get('config_affiliate_login')) {
					$query = $this->db->ncquery("SELECT * FROM `affiliate` WHERE affiliate_id = '" . (int)$this->affiliate_paid . "'");
					$affiliate_info = $query->row;
					if($affiliate_info) {
						$this->affiliateNZ->login($affiliate_info['email'], $password);
						} else {
						$this->affiliateNZ->login($email, $password);
					}
				}
								
				return true;
				} else {
				return false;
			}
		}
					
		public function refrefh($registry) {
			$this->affiliateNZ = $registry->get('affiliate');
		}
				
		private function checkIfHasBirthday($month, $day){
			$today = date('Y-m-d');
			
			$dates = array();
			for ($i=-6; $i<=3; $i++){				
				$dates[] = date('m-d', strtotime("$i day"));
			}			
			
			if (in_array($month . '-' . $day, $dates) || in_array('0' . $month . '-' . $day, $dates) || in_array($month . '-' . '0' . $day, $dates) || in_array('0' . $month . '-' . '0' . $day, $dates)){
				return true;
				} else {
				return false;
			}
		}

		public function setTracking($tracking) {
			$this->tracking = $tracking;
			$this->db->ncquery("UPDATE customer SET tracking = '" . $this->db->escape($tracking) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");	
		}
		
		public function clear_cart(){
			$this->db->ncquery("UPDATE customer SET cart = '' WHERE customer_id = '" . (int)$this->customer_id . "'");
			unset($this->session->data['cart']);
		}
		
		public function logout() {
			$this->db->ncquery("UPDATE customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
			
			unset($this->session->data['customer_id']);
			
			$this->customer_id 			= '';
			$this->firstname 			= '';
			$this->lastname 			= '';
			$this->discount_card 		= '';
			$this->discount_percent 	= '';
			$this->email 				= '';
			$this->telephone 			= '';			
			$this->birthday 			= '';
			$this->fax 					= '';
			$this->newsletter 			= '';
			$this->newsletter_news 		= '';
			$this->newsletter_personal 	= '';
			$this->customer_group_id 	= '';
			$this->address_id 			= '';
			$this->sendpulse_push_id 	= '';
						
			$this->affiliate_paid = '';
			if (!empty($this->affiliateNZ) && is_object($this->affiliateNZ) && $this->affiliateNZ->isLogged()) {
				$this->affiliateNZ->logout();
			}			
            
			$this->tracking = '';
			$this->is_opt = false;
			
			setcookie('em', '');
			setcookie('p', '');
			unset($this->request->cookie['em']);
			unset($this->request->cookie['p']);
		}
		
		public function validateStore($email){
			if (is_numeric($email)){
				
				$store_query = $this->db->ncquery("SELECT store_id FROM customer where 
				customer_id = '" . (int)$email . "' AND status = '1'");
				
				} else {
				$store_query = $this->db->ncquery("SELECT store_id FROM customer where 
				(LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "') AND status = '1'");
			}
			
			$_IS_EMAIL = $_IS_PHONE_OR_DISCOUNT = false;

			if (filter_var($email, FILTER_VALIDATE_EMAIL)){
				$_IS_EMAIL = true;
				} elseif (mb_strlen(trim(preg_replace("([^0-9])", "", $email))) > 0) {
				$_IS_PHONE_OR_DISCOUNT = true;				
			}
			
			if ($_IS_EMAIL){
				$store_query = $this->db->ncquery("SELECT store_id FROM customer WHERE 
				(LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "')  							
				AND status = '1'
				AND approved = '1'");
				} elseif ($_IS_PHONE_OR_DISCOUNT) {
				$store_query = $this->db->ncquery("SELECT store_id FROM customer WHERE 
				(REPLACE(discount_card,' ','')  LIKE '" . $this->db->escape(str_replace(' ','', $email)) . "'
				OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '". $this->db->escape(trim(preg_replace("([^0-9])", "", $email))) ."' )
				OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '". $this->db->escape(trim(preg_replace("([^0-9])", "", $email))) ."' )	
				)  							
				AND status = '1'
				AND approved = '1'");
				} else {
				$store_query = $this->db->ncquery("SELECT store_id FROM customer WHERE customer_id = '-1'");				
			}
			
			if ($store_query->num_rows){
				return $store_query->row['store_id'];
			}
			
			return false;
		}
		
		public function isLogged() {
			return $this->customer_id;
		}
		
		public function isOpt() {
			return $this->is_opt;
		}
		
		public function getId() {
			return $this->customer_id;
		}
		
		public function getFirstName() {			
			return $this->firstname;
		}
		
		public function getLastName() {
			return $this->lastname;
		}
		
		public function getDiscountCard() {
			return $this->discount_card;
		}
		
		public function getDiscountPercent() {
			return $this->discount_percent;
		}
		
		public function getEmail() {
			return $this->email;
		}
		
		public function getBirthday() {
			if ($this->birthday == '0000-00-00' || $this->birthday == '1970-01-01'){
				return false;
			}
			
			return date('d.m.Y', strtotime($this->birthday));
		}
		
		public function getHasBirthday() {
			return ($this->getBirthday() && $this->has_birthday);
		}
		
		public function getTelephone() {
			return $this->telephone;
		}
		
		public function getFax() {
			return $this->fax;
		}
		
		public function getNewsletter() {
			return $this->newsletter;	
		}		
		
		public function getNewsletterNews() {
			return $this->newsletter_news;	
		}
		
		public function getNewsletterPersonal() {
			return $this->newsletter_personal;	
		}
		
		public function getNewsletterFull(){
			return ($this->newsletter && $this->newsletter_news && $this->newsletter_personal);
		}
		
		public function getCustomerGroupId() {
			return $this->customer_group_id;	
		}
		
		public function getAddressId() {
			return $this->address_id;	
		}
		
		public function getSendPulsePushID() {
			return $this->sendpulse_push_id;	
		}
		
		public function getMailWizzUid() {			
		}
		
		public function getTracking() {
			return $this->tracking;	
		}
		
		public function setPWASession(){
			$this->session->data['pwasession'] = true;
		}
		
		public function dropPWASession(){
			unset($this->session->data['pwasession']);
		}
		
		public function getPWASession(){
			if (!empty($this->session->data['pwasession'])){
				return $this->session->data['pwasession'];
			}
			
			return false;
		}
		
		public function getBalance() {
			$query = $this->db->ncquery("SELECT SUM(amount) AS total FROM customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getBalanceNational() {
			$query = $this->db->ncquery("SELECT SUM(amount_national) AS total FROM customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function addRewardToQueue($customer_id = false, $description = '', $points = 0, $order_id = 0, $reason_code = 'UNKNOWN') {
			
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			if ($reason_code == 'ORDER_COMPLETE_ADD'){
				$check_query = $this->db->ncquery("DELETE FROM customer_reward_queue WHERE order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");
				
				//на случай если у покупателя УЖЕ есть такое НАЧИСЛЕНИЕ, то мы обновляем сумму в истории и делаем пересчет трат.
				$check_history_query = $this->db->ncquery("SELECT * FROM customer_reward WHERE order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD' LIMIT 1");
				
				if ($check_history_query->num_rows){
					$this->db->ncquery("UPDATE customer_reward SET points = '" . (int)$points . "' WHERE customer_id = '" . (int)$customer_id . "' AND customer_reward_id = '" . $check_history_query->row['customer_reward_id'] . "'");
					
					$this->fixRewards($customer_id);
				}
			}
			
			$this->db->ncquery("INSERT INTO customer_reward_queue SET 
			customer_id 	= '" . (int)$customer_id . "', 
			order_id 		= '" . (int)$order_id . "', 
			points 			= '" . (int)$points . "', 
			description 	= '" . $this->db->escape($description) . "', 
			date_added 		= NOW(), 
			reason_code 	= '" . $this->db->escape($reason_code) . "',
			date_activate 	= DATE_ADD(NOW(), INTERVAL 14 DAY)");			
		}
		
		public function addApplicationReward($customer_id = false, $description = '', $points = 0, $order_id = 0, $reason_code = 'APPINSTALL_POINTS_ADD'){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			if ($customer_id){
				//validate if customer has APPINSTALL_POINTS_ADD reward
				$query = $this->db->ncquery("SELECT * FROM customer_reward WHERE reason_code = '" . $this->db->escape($reason_code) . "' AND customer_id = '" . (int)$customer_id . "' LIMIT 1");
				
				if (!$query->num_rows){
					$this->addReward($customer_id, $description, $points, $order_id, $reason_code);
				}
			}
		}
		
		public function addReward($customer_id = false, $description = '', $points = 0, $order_id = 0, $reason_code = 'UNKNOWN') {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$date_added = date('Y-m-d H:i:s');
			
			$customer_info 	= $this->getCustomer($customer_id);						
			
			if ($customer_info) {				
				//Редактирование списания по заказу
				if ($order_id && $reason_code == 'ORDER_PAYMENT'){
					//удаляем уже существующий платеж по этому заказу и добавляем по-новой
					$query = $this->db->ncquery("SELECT * FROM customer_reward WHERE reason_code = '" . $this->db->escape($reason_code) . "' AND customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id . "' LIMIT 1");
					
					if ($query->num_rows){
						$this->db->ncquery("DELETE FROM customer_reward WHERE customer_reward_id = '" . $query->row['customer_reward_id'] . "'");
						
						//Теперь нам нужно добавить это количество бонусов к текущей истории трат, чтоб компенсировать удаление
						//То есть отнять от points_paid сумму текущего начисления
						//У нас может быть вариант, что оплачено ранее меньше чем сейчас начисляется
						$points_history = $this->getAllPaidRewards($customer_id);
						
						$pointsSUMCURRENT = 0;
						$pointsSUMPREV = 0;
						$pointsLEFT = abs($query->row['points']);
						foreach ($points_history as $history){
							
							if ($history['points_paid'] > 0){
								if ($history['points_paid'] >= $pointsLEFT){
									
									$this->db->ncquery("UPDATE customer_reward SET points_paid = points_paid - '" . $pointsLEFT . "' WHERE customer_id = '" . (int)$customer_id . "' AND customer_reward_id = '" . $history['customer_reward_id'] . "'");
									
									break;
									} else {
									
									$this->db->ncquery("UPDATE customer_reward SET points_paid = 0 WHERE customer_id = '" . (int)$customer_id . "' AND customer_reward_id = '" . $history['customer_reward_id'] . "'");
									
									$pointsLEFT = abs($pointsLEFT - $history['points_paid']);
								}	
							}
						}
						unset($history);
						unset($points_history);
						
						//Оставляем описание
						$description 	= $query->row['description'];
						$date_added 	= $query->row['date_added'];
					}
				}								
				
				//Если это списание, проверяем блять цепочку
				if ($points < 0){
					$totalPoints = $this->getRewardTotal($customer_id);
					
					//Обход попытки списания больше чем есть на счету
					if ($points < 0 && abs($points) > $totalPoints){
						$points = -1 * (int)$totalPoints;
					}
					
					$points_history = $this->getAllPositiveRewards($customer_id);
					
					$pointsINT = abs($points);
					$pointsSUMCURRENT = 0;
					$pointsSUMPREV = 0;
					
					foreach ($points_history as $history){
						if ($history['points'] <> 0){
							$pointsSUMCURRENT += ($history['points'] - $history['points_paid']);															
							
							//Момент когда суммы сравниваются
							if ($pointsSUMCURRENT >= $pointsINT){
								//Списание всех предыдущих до полного списания
								$this->db->ncquery("UPDATE customer_reward SET points_paid = points,  date_paid = NOW() WHERE customer_reward_id < '" . $history['customer_reward_id'] . "' AND customer_id = '" . (int)$customer_id . "' AND points_paid <> points AND points > 0");
								
								//Списание текущего, первый вариант - сумма равна, списываем в ноль
								if ($pointsSUMCURRENT == $pointsINT){
									
									$this->db->ncquery("UPDATE customer_reward SET points_paid = points,  date_paid = NOW() WHERE customer_reward_id = '" . $history['customer_reward_id'] . "' AND customer_id = '" . (int)$customer_id . "'");
									
									} else {
									
									$this->db->ncquery("UPDATE customer_reward SET points_paid = points_paid + '" . (int)($pointsINT - $pointsSUMPREV) . "', date_paid = NOW() WHERE customer_reward_id = '" . $history['customer_reward_id'] . "' AND customer_id = '" . (int)$customer_id . "'");
									
								}
								
								break;
							}
							
							$pointsSUMPREV += ($history['points'] - $history['points_paid']);
						}
					}
				}
				
				//А это валидация нулевого добавления
				if ((int)$points != 0){
					
					$user_id = 0;
					if (is_object($this->user) && $this->user->getId()){
						$user_id = $this->user->getId();
					}
					
					$this->db->ncquery("INSERT INTO customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = '" . $this->db->escape($date_added) . "', reason_code = '" . $this->db->escape($reason_code) . "', user_id = '" . (int)$user_id . "'");
					
				}
				
				$this->fixRewards($customer_id);
			}
		}
		
		public function fixRewards($customer_id = false){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			
			//Сумма всех минусов
			$query = $this->db->ncquery("SELECT SUM(points) as total FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND points < 0");
			$total = $query->row['total'];
			
			//Обнуляем все потраченные
			$query = $this->db->ncquery("UPDATE customer_reward SET points_paid = 0 WHERE customer_id = '" . (int)$customer_id . "' AND points > 0");
			
			$points_history = $this->getAllPositiveRewards($customer_id);
			
			$pointsINT = abs($total);
			$pointsSUMCURRENT = 0;
			$pointsSUMPREV = 0;
			
			foreach ($points_history as $history){
				if ($history['points'] <> 0){
					$pointsSUMCURRENT += ($history['points'] - $history['points_paid']);															
					
					//Момент когда суммы сравниваются
					if ($pointsSUMCURRENT >= $pointsINT){
						//Списание всех предыдущих до полного списания
						$this->db->ncquery("UPDATE customer_reward SET points_paid = points,  date_paid = NOW() WHERE customer_reward_id < '" . $history['customer_reward_id'] . "' AND customer_id = '" . (int)$customer_id . "' AND points_paid <> points AND points > 0");
						
						//Списание текущего, первый вариант - сумма равна, списываем в ноль
						if ($pointsSUMCURRENT == $pointsINT){
							
							$this->db->ncquery("UPDATE customer_reward SET points_paid = points,  date_paid = NOW() WHERE customer_reward_id = '" . $history['customer_reward_id'] . "' AND customer_id = '" . (int)$customer_id . "'");
							
							} else {
							
							$this->db->ncquery("UPDATE customer_reward SET points_paid = points_paid + '" . (int)($pointsINT - $pointsSUMPREV) . "', date_paid = NOW() WHERE customer_reward_id = '" . $history['customer_reward_id'] . "' AND customer_id = '" . (int)$customer_id . "'");
							
						}
						
						break;
					}
					
					$pointsSUMPREV += ($history['points'] - $history['points_paid']);
				}
			}									
		}
		
		public function getCustomer($customer_id = false) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row;
		}		
		
		public function setBurnedByCRID($customer_reward_id){			
			$this->db->ncquery("UPDATE customer_reward SET burned = 1, points_paid = points WHERE customer_reward_id = '" . (int)$customer_reward_id . "'");
		}
		
				
		//---------------------------- Функции работы с ОЧЕРЕДЬЮ БАЛЛОВ ------------------------------------
		
		
		//Удаляет начисление из очереди начислений, если заказ отменен до 14 дней	
		public function clearRewardQueueByOrder($customer_id, $order_id){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$this->db->ncquery("DELETE FROM customer_reward_queue WHERE order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");
		}
		
		public function clearRewardQueueByCRQID($customer_reward_queue_id){			
			$this->db->ncquery("DELETE FROM customer_reward_queue WHERE customer_reward_queue_id = '" . (int)$customer_reward_queue_id . "'");
		}
		
		//Получить очередь по покупателю
		public function getRewardQueue($customer_id = false) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT * FROM customer_reward_queue WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC");
			
			return $query->rows;		
		}
		
		//Получить последнюю дату начисления из очереди
		public function getRewardMaxQueueDate($customer_id = false) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT max(date_activate) as max_date_activate FROM customer_reward_queue WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['max_date_activate'];			
		}
		
		//Получить сумму начислений в очереди
		public function getRewardTotalInQueue($customer_id = false) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT SUM(points) AS total FROM customer_reward_queue WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		//Просмотреть очередь начислений по заказу
		public function getRewardInQueueByOrder($customer_id, $order_id) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT points FROM customer_reward_queue WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");
			
			if ($query->num_rows){
				return abs($query->row['points']);
				} else {
				return 0;
			}
		}		
		
		//Обновить количество баллов в очереди по заказу
		public function updateRewardInQueueByOrder($customer_id, $order_id, $points) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("UPDATE customer_reward_queue SET points = '" . (int)$points . "' WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");			
		}
		
		
		//---------------------------- Функции работы с ТАБЛИЧКОЙ ИЛИ СЧЕТОМ БАЛЛОВ ------------------------------------
		
		//Получить сумму начислений за заказ
		public function getTotalRewardByOrder($customer_id, $order_id) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT SUM(reward) as total FROM order_product WHERE order_id = '" . $order_id . "'");
			
			return $query->row['total'];		
		}
		
		//Проверить бонусы, начисленные за заказ
		public function getRewardInTableByOrder($customer_id, $order_id) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT points FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");					
			
			if ($query->num_rows){
				return abs($query->row['points']);
				} else {
				return 0;
			}
		}
		
		//Обновить бонусы, начисленные за заказ (при возврате, например, после 14 дней)
		public function updateRewardInTableByOrder($customer_id, $order_id, $points) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("UPDATE customer_reward SET points = '" . (int)$points . "' WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");
			
			$this->fixRewards($customer_id);			
		}
		
		//Получить бонусы, зарезервированные в заказе
		public function getRewardReservedByOrder($customer_id, $order_id) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT points FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . $order_id . "' AND reason_code = 'ORDER_PAYMENT'");
			
			if ($query->num_rows){
				return abs($query->row['points']);
				} else {
				return 0;
			}
		}
				
		//Удаляет начисление из начислений, если заказ отменен после 14 дней	
		public function clearRewardTableByOrder($customer_id, $order_id){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$this->db->ncquery("DELETE FROM customer_reward WHERE order_id = '" . $order_id . "' AND reason_code = 'ORDER_COMPLETE_ADD'");
			
			$this->fixRewards($customer_id);
		}
		
		//---------------------------- Служебные функции, используемые в пересчетах ------------------------------------
		
		//Получить все транзакции клиента
		public function getAllRewards($customer_id = false){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}						
			
			$query = $this->db->ncquery("SELECT * FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}
		
		//Получить всю очередь клиента
		public function getAllQueueRewards($customer_id = false){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT * FROM customer_reward_queue WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}
		
		//Получить сумму начислений по заказу	
		public function countOrderReward($order_id){
			
			$query = $this->db->ncquery("SELECT SUM(reward) as total FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			
			if ($query->num_rows){
				return $query->row['total'];
				} else {
				return 0;
			}				
		}
				
		//Получить текущий баланс
		public function getRewardTotal($customer_id = false) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			if (defined('YANDEX_MARKET_CUSTOMER_ID') && $customer_id == YANDEX_MARKET_CUSTOMER_ID){
				$this->db->query("DELETE FROM customer_reward WHERE customer_id = '" . (int)YANDEX_MARKET_CUSTOMER_ID . "'");
				$this->db->query("DELETE FROM customer_reward_queue WHERE customer_id = '" . (int)YANDEX_MARKET_CUSTOMER_ID . "'");
			}
			
			$query = $this->db->ncquery("SELECT SUM(points) AS total FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		//Получить все позитивные начисления
		public function getAllPositiveRewards($customer_id = false){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT * FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND points > 0 ORDER BY date_added ASC");
			
			return $query->rows;			
		}
		
		//Получить все оплаченные начисления
		public function getAllPaidRewards($customer_id = false){
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT * FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND points_paid > 0 ORDER BY date_added DESC");
			
			return $query->rows;			
		}
		
		//Синоним getRewardTotal(), для совместимости
		public function getRewardPoints($customer_id = false) {
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			$query = $this->db->ncquery("SELECT SUM(points) AS total FROM customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");
			
			return $query->row['total'];	
		}
		
				
		/* MAILWIZZ EMA FUNCTIONS */		
		public function addToEMAQueue($customer_id = false){		
			if (!$customer_id){
				$customer_id = $this->customer_id;
			}
			
			if ($customer_id && $this->config->get('config_mailwizz_enable')){
				$this->db->query("INSERT IGNORE INTO mailwizz_queue SET customer_id = '" . (int)$customer_id . "'");		
			}	
		}
		
		/* Функции для обработки почтовых АПИШЕК */		
		public function updateMailStatus($customer_id, $mail_status){		
			$this->db->query("UPDATE customer SET mail_status = '" . $this->db->escape($mail_status) . "' WHERE customer_id = '" . $customer_id . "'");
			
			$updateListsStatuses = [
			'failed',
			'complained',
			'unsubscribed',
			'bounce',
			'spam_complaint',
			'out_of_band',
			'policy_rejection',
			'list_unsubscribe',
			'link_unsubscribe'
			];
			
			
			if (in_array($mail_status, $updateListsStatuses)){
				$this->addToEMAQueue($customer_id);
			}
		}
		
		public function updateClick($customer_id){		
			$this->db->query("UPDATE customer SET mail_clicked=mail_clicked+1 WHERE customer_id = '" . $customer_id . "'");			
		}
		
		public function updateOpen($customer_id){		
			$this->db->query("UPDATE customer SET mail_opened=mail_opened+1 WHERE customer_id = '" . $customer_id . "'");			
		}
		
		public function clearSubscriptions($customer_id){		
		$this->db->query("UPDATE customer SET newsletter=0, newsletter_news=0, newsletter_personal=0 WHERE customer_id = '" . $customer_id . "'");			
		}
		
		public function searchCustomer($email){
			$query = $this->db->query("SELECT customer_id FROM customer WHERE email LIKE ('" . trim($this->db->escape($email)) . "') LIMIT 1");

				if ($query->row){
					return (int)$query->row['customer_id'];
				}

				return false;
		}

	}																			
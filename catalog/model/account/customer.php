<?php
	class ModelAccountCustomer extends Model {
		public function addCustomer($data) {
			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
				} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$this->load->model('account/customer_group');
			
			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
			$this->db->non_cached_query("INSERT INTO customer SET 
				language_id 	= '" . (int)$this->config->get('config_language_id') . "', 
				store_id 		= '" . (int)$this->config->get('config_store_id') . "', 
				firstname 		= '" . $this->db->escape($data['firstname']) . "', 
				lastname 		= '" . $this->db->escape($data['lastname']) . "', 
				birthday 		= '" . $this->db->escape(date('Y-m-d',strtotime($data['birthday']))) . "', 
				birthday_date 	= '" . $this->db->escape(date('d',strtotime($data['birthday']))) . "',
				birthday_month 	= '" . $this->db->escape(date('m',strtotime($data['birthday']))) . "',
				email 			= '" . $this->db->escape($data['email']) . "', 
				passport_serie 	= '" . $this->db->escape($data['passport_serie']) . "', 
				passport_given 	= '" . $this->db->escape($data['passport_given']) . "', 
				telephone 		= '" . $this->db->escape($data['telephone']) . "', 
				fax 			= '" . $this->db->escape($data['fax']) . "', 
				salt 			= '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
				password 		= '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
				newsletter 		= '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 1) . "', 
				viber_news 		= '" . (isset($data['viber_news']) ? (int)$data['viber_news'] : 1) . "', 
				newsletter_news 		= '" . (isset($data['newsletter_news']) ? (int)$data['newsletter_news'] : 1) . "', 
				newsletter_personal 	= '" . (isset($data['newsletter_personal']) ? (int)$data['newsletter_personal'] : 1) . "', 
				customer_group_id 		= '" . (int)$customer_group_id . "', 
				ip 			= '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', 
				status 		= '1', 
				approved 	= '" . (int)!$customer_group_info['approval'] . "', 
				date_added 	= NOW()");
			
			$customer_id = $this->db->getLastId();
			
			$this->db->non_cached_query("UPDATE customer SET utoken = md5(concat(email, '" . $this->config->get('config_encryption') . "')) WHERE customer_id = '" . (int)$customer_id . "'"); 
			
			$this->db->non_cached_query("INSERT INTO address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");
			
			$address_id = $this->db->getLastId();
			
			$this->db->non_cached_query("UPDATE customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			
			$this->language->load('mail/customer');
			
			$template = new EmailTemplate($this->request, $this->registry);
			
			$template->addData($data); 
			
			$template->data['newsletter'] 				= $this->language->get((isset($data['newsletter']) && $data['newsletter'] == 1) ? 'text_yes' : 'text_no');
			$template->data['account_login'] 			= $this->url->link('account/login', 'email=' . $data['email']);
			$template->data['account_login_tracking'] 	= $template->getTracking($template->data['account_login']);
			$template->data['customer_group'] 			= (isset($customer_group_info['name'])) ? $customer_group_info['name'] : '';
			
			$this->load->model('account/address');
			$customer_address = $this->model_account_address->getAddressNotLoggedIn($address_id, $customer_id);
			
			$template->data['address'] = EmailTemplate::FormatAddress($customer_address, '', $customer_address['address_format']);
			
			if((isset($customer_group_info['approval']) && $customer_group_info['approval']) || $this->config->get('config_customer_approval')){
				$template->data['customer_text'] = $this->language->get('text_approval'); // Backwards compatible with pre OC_ver 1.5.3
				} else {
				$template->data['customer_text'] = $this->language->get('text_login');
			}
			
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));			
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
			
			if (!$customer_group_info['approval']) {
				$message .= $this->language->get('text_login') . "\n";
				} else {
				$message .= $this->language->get('text_approval') . "\n";
			}
			
			$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
			$message .= $this->language->get('text_services') . "\n\n";
			$message .= $this->language->get('text_thanks') . "\n";
			$message .= $this->config->get('config_name');
			
			$mail = new Mail($this->registry); 		
			$mail->setTo($data['email']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$template->load('customer.register');
			
			$mail = $template->hook($mail);			
			$mail->send();			
			$template->sent();
			
			if ($this->config->get('config_account_mail')) {				
				if((isset($customer_group_info['approval']) && $customer_group_info['approval']) || $this->config->get('config_customer_approval')){
					$template->data['text_approve'] = $this->language->get('text_approve');
					$template->data['account_approve'] = (defined('HTTP_ADMIN') ? HTTP_ADMIN : HTTP_SERVER.'admin/') . 'index.php?route=sale/customer&filter_approved=0';
				}
				
				$message  = $this->language->get('text_signup') . "\n\n";
				$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
				$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
				$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
				$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
				
				if ($data['company']) {
					$message .= $this->language->get('text_company') . ' '  . $data['company'] . "\n";
				}
				
				$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
				$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";
				
				$mail->setTo($this->config->get('config_email'));
				$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$template->load('customer.register_admin');
				$template->build();
				$template->fetch();
				$mail = $template->hook($mail);
				$mail->send();
				$template->sent();
				
				// Send to additional alert emails if new account email is enabled
				$emails = explode(',', $this->config->get('config_alert_emails'));
				
				foreach ($emails as $email) {
					if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}
			
			$this->customer->addToEMAQueue($customer_id);
			
			return $customer_id;
		}
		
		public function editCustomer($data) {
			
			$this->db->non_cached_query("UPDATE customer SET 
				firstname 		= '" . $this->db->escape($data['firstname']) . "', 
				lastname 		= '" . $this->db->escape($data['lastname']) . "', 
				birthday 		= '" . $this->db->escape(date('Y-m-d',strtotime($data['birthday']))) . "', 
				birthday_date 	= '" . $this->db->escape(date('d',strtotime($data['birthday']))) . "',
				birthday_month 	= '" . $this->db->escape(date('m',strtotime($data['birthday']))) . "',
				email 			= '" . $this->db->escape($data['email']) . "', 
				passport_serie 	= '" . $this->db->escape($data['passport_serie']) . "', 
				passport_given 	= '" . $this->db->escape($data['passport_given']) . "', 
				telephone 		= '" . $this->db->escape($data['telephone']) . "', 
				fax 			= '" . $this->db->escape($data['fax']) . "' 
				WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			$this->customer->addToEMAQueue();
		}
		
		public function editPassword($email, $password) {
			$this->db->non_cached_query("UPDATE customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}

		public function editPasswordByPhone($telephone, $password) {
			$this->db->non_cached_query("UPDATE customer 
				SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
				password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "'
				WHERE REGEXP_REPLACE(telephone, '[^0-9]', '') LIKE '" . $this->db->escape(preg_replace("([^0-9])", "", $telephone)) ."'");
		}
		
		public function editViberNews($viber_news) {
			$this->db->non_cached_query("UPDATE customer SET viber_news = '" . (int)$viber_news . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");			
		}
		
		public function editNewsletter($newsletter) {
			$this->db->non_cached_query("UPDATE customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			$this->customer->addToEMAQueue();
		}
		
		public function editNewsletterNews($newsletter) {
		 	$this->db->non_cached_query("UPDATE customer SET newsletter_news = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			$this->customer->addToEMAQueue();
		}
		
		public function editNewsletterPersonal($newsletter) {
			$this->db->non_cached_query("UPDATE customer SET newsletter_personal = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			$this->customer->addToEMAQueue();
		}

		public function editBirthday($birthday) {
			$this->db->non_cached_query("UPDATE customer SET birthday = '" . $this->db->escape(date('Y-m-d', strtotime($birthday))) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			$this->db->non_cached_query("UPDATE customer SET birthday_month = '" . (int)date('m', strtotime($birthday)) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			$this->db->non_cached_query("UPDATE customer SET birthday_date = '" . (int)date('d', strtotime($birthday)) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			$this->customer->addToEMAQueue();
		}
		
		public function getCustomer($customer_id) {
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row;
		}
		
		public function getCustomerByEmail($email) {	
			if (!trim($email)){
				return false;
			}				
			
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower(trim($email))) . "'");			
			return $query->row;
		}
		
		public function getCustomerIDByEmail($email) {
			if (!trim($email)){
				return false;
			}				
			
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower(trim($email))) . "'");			
			
			if ($query->num_rows){
				return $query->row['customer_id'];
			}
			
			return false;
		}
		
		public function getAllCustomersByEmail($email) {
			if (!trim($email)){
				return false;
			}				
			
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower(trim($email))) . "'");			
			
			if ($query->num_rows){
				return $query->rows;
			}
			
			return false;
		}
		
		public function getCustomerByUtoken($utoken) {
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE LOWER(utoken) = '" . $this->db->escape(utf8_strtolower($utoken)) . "'");
			
			return $query->row;
		}
		
		public function validateUtokenForCustomerID($customer_id, $utoken) {
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE LOWER(utoken) = '" . $this->db->escape(utf8_strtolower($utoken)) . "' AND customer_id = '" . (int)$customer_id . "'");
			
			if (!$query->num_rows){
				return ($utoken == md5($customer_id . $this->config->get('config_encryption')));
			}
			
			return $query->num_rows;
		}
		
		public function getCustomerIDFromOrderID($order_id) {
			$query = $this->db->non_cached_query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['customer_id'];
		}
		
		public function getCustomerByPhone($telephone){
			$telephone = preg_replace("([^0-9])", "", $telephone);
			
			if (!$telephone){
				return false;
			}
			
			$sql = "SELECT customer_id FROM `customer` WHERE ";
			$sql .= "(REGEXP_REPLACE(telephone, '[^0-9]', '') LIKE '" . $this->db->escape($telephone) . "' )";
			$sql .= " LIMIT 1";
			
			$query = $this->db->non_cached_query($sql);
			
			if ($query->num_rows){
				return $query->row['customer_id'];			
			}
			
			return false;			
		}
		
		public function getCustomerByToken($token) {
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
			
			$this->db->non_cached_query("UPDATE customer SET token = ''");
			
			return $query->row;
		}
		
		public function getCustomers($data = array()) {
			$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM customer c LEFT JOIN customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";
			
			$implode = array();
			
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
			
			if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
				$implode[] = "LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
			}
			
			if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
				$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
			}	
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
			}	
			
			if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
				$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
			}	
			
			if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
				$implode[] = "c.customer_id IN (SELECT customer_id FROM customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
			}	
			
			if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
				$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
			
			$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
				} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}		
			
			$query = $this->db->non_cached_query($sql);
			
			return $query->rows;	
		}
		
		public function editNewsletterUnsubscribe($email){
			$query = $this->db->non_cached_query("SELECT * FROM customer WHERE MD5(email) = '" . $this->db->escape($email) . "'");
			
			if ($query->num_rows) {
				$this->db->non_cached_query("UPDATE customer SET newsletter = '0' WHERE customer_id = " . (int)$query->row['customer_id'] . "");
				
				$this->customer->addToEMAQueue();
				
				return $query->row;
				} else {
				return false;
			}
		}
		
		public function getTotalCustomers() {
			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM customer WHERE 1");
			
			return $query->row['total'];
		}
		
		public function getTotalCustomersCached() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE newsletter = 1");
			
			return $query->row['total'];
		}
		
		public function getTotalCustomersByEmail($email) {
			if (!trim($email)){
				return false;
			}

			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row['total'];
		}

		public function getTotalCustomersByPhone($telephone) {
			if (!preg_replace("([^0-9])", "", $telephone)){
				return false;
			}

			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM customer WHERE REGEXP_REPLACE(telephone, '[^0-9]', '') LIKE '" . $this->db->escape(preg_replace("([^0-9])", "", $telephone)) ."'");
			
			return $query->row['total'];
		}
		
		public function getIps($customer_id) {
			$query = $this->db->non_cached_query("SELECT * FROM `customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}	
		
		public function isBanIp($ip) {
			$query = $this->db->non_cached_query("SELECT * FROM `customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");
			
			return $query->num_rows;
		}	
	}		
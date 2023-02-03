<?php
	class ModelSaleCustomer extends Model {
		
		private function buildRelativeDate($string){
			$now = date_create();
			
			if ($string[0] == '+'){
				$invert = false;
				} elseif ($string[0] == '-') {
				$invert = true;
				} else {
				return date();
			}
			
			$string = substr($string, 1);
			
			$_srd_ru = array('дней', 'дня', 'дня', 'день', 'неделя', 'недели', 'месяц', 'месяца', 'месяцев', 'полгода', 'год', 'года');
			$_srd_php = array('days', 'days', 'days', 'days', 'weeks', 'weeks', 'months', 'months', 'months', '6 month', 'years', 'years');
			
			$diff = date_interval_create_from_date_string(str_replace($_srd_ru, $_srd_php, $string));
			
			if ($invert){
				return date_format(date_sub($now, $diff), 'Y-m-d');
				} else {
				return date_format(date_add($now, $diff), 'Y-m-d');
			}
			
		}
		
		private function buildDateMagicSQL($date_from, $date_to, $param, $remove_year = false){
			
			$sql = "";
			
			$date_from = trim($date_from);
			$date_to = trim($date_to);
			//let's parse relative dates
			if (!$date_to){
				$date_to = date('Y-m-d');
			}
			
			if (!$date_from){
				$date_from = date('Y-m-d');
			}
			
			if ($date_from[0] == '+' || $date_from[0] == '-'){
				$date_from = $this->buildRelativeDate($date_from);
			}
			
			if ($date_to[0] == '+' || $date_to[0] == '-'){
				$date_to = $this->buildRelativeDate($date_to);
			}
			
			
			if ($remove_year){
				
				$date_from = date('m-d', strtotime($date_from));
				$date_to = date('m-d', strtotime($date_to));
				
				if (!empty($date_from)) {
					$sql .= " DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-".$date_from."'))";
					$sql .= " AND DATE($param) > '0000-00-00'";
					
					if (empty($date_to)){
						$sql .= " AND DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
					}
				}
				
				
				if (!empty($date_to)) {
					if ($sql){
						$and = " AND ";
					}
					$sql .= " $and DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-".$date_to."'))";
					$sql .= " AND DATE($param) > '0000-00-00'";
					
					if (empty($date_from)){
						$sql .= " AND DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
					}
				}
				
				
				} else {
				
				if (!empty($date_from)) {
					$sql .= " DATE($param) >= '$date_from'";
				}
				
				if ($sql){
					$and = " AND ";
				}
				
				if (!empty($date_to)) {
					$sql .= " $and DATE($param) <= '$date_to'";
					
					if (empty($date_from)){
						$sql .= " AND DATE($param) > '0000-00-00'";
					}
				}
				
			}
			
			return $sql;
		}
		
		
		public function addCustomer($data) {
			$this->db->query("INSERT INTO customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', store_id = '" . $this->db->escape($data['store_id']) . "', customer_comment = '" . $this->db->escape($data['customer_comment']) . "', cashless_info = '" . $this->db->escape($data['cashless_info']) . "', discount_card = '" . $this->db->escape($data['discount_card']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', passport_serie = '" . $this->db->escape($data['passport_serie']) . "', passport_given = '" . $this->db->escape($data['passport_given']) . "', birthday = '" . $this->db->escape(date('Y-m-d',strtotime($data['birthday']))) . "',  newsletter = '" . (int)$data['newsletter'] . "',  viber_news = '" . (int)$data['viber_news'] . "',  newsletter_news = '" . (int)$data['newsletter_news'] . "',  newsletter_personal = '" . (int)$data['newsletter_personal'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', mudak = '" . (int)$data['mudak'] . "', gender = '" . (int)$data['gender'] . "', cron_sent = '" . (int)$data['cron_sent'] . "', printed2912 = '" . (int)$data['printed2912'] . "'  date_added = NOW()");
			
			$customer_id = $this->db->getLastId();
			
			if (isset($data['address'])) {
				foreach ($data['address'] as $address) {
					$this->db->query("INSERT INTO address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', company_id = '" . $this->db->escape($address['company_id']) . "', tax_id = '" . $this->db->escape($address['tax_id']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', for_print = '" . (int)$address['for_print'] . "', postcode = '" . $this->db->escape($address['postcode']) . "', passport_serie = '" . $this->db->escape($address['passport_serie']) . "', passport_given = '" . $this->db->escape($address['passport_given']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
					
					if (isset($address['default'])) {
						$address_id = $this->db->getLastId();
						
						$this->db->query("UPDATE customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}										
				}
			}
			
			$this->customer->addToEMAQueue($customer_id);
			
			if(isset($data['notify'])){
				$template = new EmailTemplate($this->request, $this->registry);
				
				$template->addData($data);
				
				$template->data['newsletter'] = $this->language->get((isset($data['newsletter']) && $data['newsletter'] == 1) ? 'text_yes' : 'text_no');
				
				$template->data['account_login'] = $this->url->link('account/login', 'email=' . $data['email'], 'SSL');
				$template->data['account_login_tracking'] = $template->getTracking($template->data['account_login']);
				
				if(isset($data['customer_group_id']) && $data['customer_group_id']){
					$this->load->model('sale/customer_group');		
					$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($data['customer_group_id']);
					$template->data['customer_group'] = $customer_group_info['name'];
				}
				
				if(isset($address_id)){
					$address =  $this->getAddress($address_id);
					$template->data['address'] = EmailTemplate::FormatAddress($address, '', $address['address_format']);
				}
				
				$template->load('admin.customer_create');
				
				$template->send(); 
			} // notify
		}
		
		public function addCustomerCall($data){
			
			$this->db->query("INSERT INTO customer_calls
			SET
			customer_id = '" . (int)$data['customer_id'] . "',
			customer_phone = '" . $this->db->escape($data['customer_phone']) . "',
			date_start = '" . $this->db->escape($data['date_start']) . "',
			date_end = '" . $this->db->escape($data['date_end']) . "',
			comment = '" . $this->db->escape($data['comment']) . "',
			manager_id = '" . (int)$data['manager_id'] . "',
			filelink = '" . $this->db->escape($data['filelink']) . "',
			order_id = '" . (int)$data['order_id'] . "'
			");
			
		}
		
		public function getCustomerCallByID($customer_call_id){
			$query = $this->db->query("SELECT * FROM customer_calls WHERE customer_call_id = '" . (int)$customer_call_id . "' LIMIT 1");
			
			return $query->row;
		}
		
		public function getTotalLoyalCustomers(){
			$query = $this->db->query("SELECT COUNT(DISTINCT customer_id) as total FROM customer WHERE customer_id IN (SELECT customer_id FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 GROUP BY customer_id HAVING COUNT(order_id) > 1)");
			
			return $query->row['total'];
		}
		
		public function getTotalLoyalOrdersLastMonth(){
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 AND DATE(date_added) >= SUBDATE(CURDATE(),30) AND customer_id IN (SELECT customer_id FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 GROUP BY customer_id HAVING COUNT(order_id) > 1)");
			
			return $query->row['total'];
		}
		
		public function getTotalLoyalCustomersLastWeek(){
			$query = $this->db->query("SELECT COUNT(DISTINCT customer_id) as total FROM customer WHERE customer_id IN (SELECT customer_id FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 GROUP BY customer_id HAVING COUNT(order_id) > 1) AND customer_id IN (SELECT customer_id FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 AND DATE(date_added) >= SUBDATE(CURDATE(),7))");
			
			return $query->row['total'];
		}
		
		public function getTotalInvolvedCustomers(){
			$query = $this->db->query("SELECT COUNT(DISTINCT customer_id) as total FROM `order` WHERE order_status_id > 0");
			
			return $query->row['total'];
		}
		
		public function getTotalInvolvedCustomersLastWeek(){
			$query = $this->db->query("SELECT COUNT(DISTINCT customer_id) as total FROM `order` WHERE DATE(date_added) <= SUBDATE(CURDATE(),7) AND order_status_id > 0");
			
			return $query->row['total'];
		}
		
		public function editCustomer($customer_id, $data) {
			
			$query = $this->db->query("SELECT birthday FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
			$birthday = date('Y-m-d',strtotime($query->row['birthday']));
			
			$this->load->model('kp/work');
			$this->model_kp_work->updateFieldPlusOne('edit_customer_count');
			
			if ($data['birthday'] && mb_strlen($data['birthday']) > 2) {
				
				if ($birthday != date('Y-m-d', strtotime($data['birthday'])) && in_array($query->row['birthday'], array('', '0000-00-00', '1970-01-01', ))){				
					$this->model_kp_work->updateFieldPlusOne('edit_birthday_count');
				}
				
			}
			
			$this->db->query("UPDATE customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "',  customer_comment = '" . $this->db->escape($data['customer_comment']) . "', cashless_info = '" . $this->db->escape($data['cashless_info']) . "', discount_card = '" . $this->db->escape($data['discount_card']) . "', email = '" . $this->db->escape(trim($data['email'])) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', passport_serie = '" . $this->db->escape($data['passport_serie']) . "', passport_given = '" . $this->db->escape($data['passport_given']) . "', birthday = '" . $this->db->escape(date('Y-m-d',strtotime($data['birthday']))) . "', newsletter = '" . (int)$data['newsletter'] . "',  viber_news = '" . (int)$data['viber_news'] . "',  newsletter_news = '" . (int)$data['newsletter_news'] . "',  newsletter_personal = '" . (int)$data['newsletter_personal'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', status = '" . (int)$data['status'] . "', mudak = '" . (int)$data['mudak'] . "', gender = '" . (int)$data['gender'] . "', notify = '" . (int)$data['notify'] . "', cron_sent = '" . (int)$data['cron_sent'] . "', printed2912 = '" . (int)$data['printed2912'] . "'  WHERE customer_id = '" . (int)$customer_id . "'");
			
			$simple_data = array(
			'custom_birthday' => $this->db->escape(date('Y-m-d',strtotime($data['birthday']))),
			'custom_passport_serie' => $this->db->escape($data['passport_serie']),
			'custom_passport_given' => $this->db->escape($data['passport_given'])
			);
			
			$this->load->model('tool/simplecustom');
			$this->model_tool_simplecustom->updateData('customer', $customer_id, 'customer', $simple_data);
			
			if ((int)$data['mudak']){
				$this->db->query("UPDATE customer SET customer_group_id = '" . (int)$this->config->get('config_bad_customer_group_id') . "'  WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			if ($data['password']) {
				$this->db->query("UPDATE customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			$this->db->query("DELETE FROM address WHERE customer_id = '" . (int)$customer_id . "'");
			
			if (isset($data['address'])) {
				foreach ($data['address'] as $address) {
					$this->db->query("INSERT INTO address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', company_id = '" . $this->db->escape($address['company_id']) . "', tax_id = '" . $this->db->escape($address['tax_id']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', passport_serie = '" . $this->db->escape($address['passport_serie']) . "', passport_given = '" . $this->db->escape($address['passport_given']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', verified = '" . (int)$address['verified'] . "', for_print = '" . (int)$address['for_print'] . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
					
					if (isset($address['default'])) {
						$address_id = $this->db->getLastId();
						
						$this->db->query("UPDATE customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}
				}
			}
			
			$this->customer->addToEMAQueue($customer_id);
		}
		
		public function editToken($customer_id, $token) {
			$this->db->query("UPDATE customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}
		
		public function deleteCustomer($customer_id) {
			$this->db->query("DELETE FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
		//	$this->db->query("DELETE FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		//	$this->db->query("DELETE FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM address WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM customer_segments WHERE customer_id = '" . (int)$customer_id . "'");
		}
		
		public function getCustomer($customer_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row;
		}
		
		private function checkIfHasBirthday($month, $day){
			$today = date('Y-m-d');
			
			$dates = array();
			for ($i=-2; $i<=7; $i++){				
				$dates[] = date('m-d', strtotime("$i day"));
			}
			
			if (in_array($month . '-' . $day, $dates)){
				return true;
				} else {
				return false;
			}
		}
		
		public function customerHasBirthday($customer_id){
			$query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			if (isset($query->row['birthday_month']) && isset($query->row['birthday_date'])){
				return $this->checkIfHasBirthday($query->row['birthday_month'], $query->row['birthday_date']);
				} else {
				return false;
			}
		}
		
		public function getSources(){
			$query = $this->db->query("SELECT DISTINCT source FROM customer WHERE 1");
			
			return $query->rows;
		}
		
		public function getCustomerByEmail($email) {
			$query = $this->db->query("SELECT DISTINCT * FROM customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row;
		}
		
		public function getCustomerViews($type, $customer_id, $limit){
			$query = $this->db->query("SELECT entity_id, times FROM customer_viewed WHERE type = '" . $this->db->escape($type) . "' AND customer_id = '" .(int)$customer_id. "' ORDER BY times DESC LIMIT " . (int)$limit . "");
			
			return $query->rows;
		}
		
		public function getCustomerOrderProducts($customer_id){
			$query = $this->db->query("SELECT DISTINCT op.product_id FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.customer_id = '" .(int)$customer_id. "'");
			
			$products = array();
			foreach ($query->rows as $row){
				$products[] = $row['product_id'];
			}
			
			return $products;
			
		}
		
		public function getCustomerByPhone($phone){
			//only ints
			$phone = trim(preg_replace("([^0-9])", "", $phone));
			
			$sql = "SELECT customer_id, firstname, lastname FROM `customer` WHERE ";
			$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '" . $this->db->escape($phone) . "' )";
			$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '". $this->db->escape($phone) . "')";
			$sql .= " LIMIT 1";
			
			$query = $this->db->non_cached_query($sql);
			
			if ($query->num_rows){
				return $query->row['customer_id'];
				} else {
				return false;
			}
			
		}
		
		public function getCustomers($data = array()) {
			$sql = "SELECT DISTINCT c.*, 
			CONCAT(c.firstname, ' ', c.lastname) AS name, 
			cgd.name AS customer_group
			FROM customer c 
			LEFT JOIN customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id)";
			
			if (!empty($data['filter_last_call'])) {
				$sql .= "LEFT JOIN customer_calls cc ON (cc.customer_id = c.customer_id)";			
			}
			
			$sql .= "WHERE 1 AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$implode = array();
			$having_implode = array();				
			
			if (!empty($data['filter_order_count'])){
				$data['filter_order_count'] = trim($data['filter_order_count']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_order_count'])[0] == '<' || html_entity_decode($data['filter_order_count'])[0] == '>'){
					
					$data['filter_order_count'] = html_entity_decode($data['filter_order_count']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_order_count']);
					
					if ($data['filter_order_count'][0] == '<'){
						$implode[] = " order_count >= 0 AND order_count < '" . $num . "'";
						} elseif ($data['filter_order_count'][0] == '>') {
						$implode[] = " order_count > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_order_count'])) == 2){
					
					$nums = explode('-', $data['filter_order_count']);
					
					$implode[] = " order_count >= '" . (int)$nums[0] . "' AND order_count <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " order_count >= '" . (int)$data['filter_order_count'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_order_good_count'])){
				$data['filter_order_good_count'] = trim($data['filter_order_good_count']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_order_good_count'])[0] == '<' || html_entity_decode($data['filter_order_good_count'])[0] == '>'){
					
					$data['filter_order_good_count'] = html_entity_decode($data['filter_order_good_count']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_order_good_count']);
					
					if ($data['filter_order_good_count'][0] == '<'){
						$implode[] = " order_good_count >= 0 AND order_good_count < '" . $num . "'";
						} elseif ($data['filter_order_good_count'][0] == '>') {
						$implode[] = " order_good_count > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_order_good_count'])) == 2){
					
					$nums = explode('-', $data['filter_order_good_count']);
					
					$implode[] = " order_good_count >= '" . (int)$nums[0] . "' AND order_good_count <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " order_good_count >= '" . (int)$data['filter_order_good_count'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_total_sum'])){
				$data['filter_total_sum'] = trim($data['filter_total_sum']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_total_sum'])[0] == '<' || html_entity_decode($data['filter_total_sum'])[0] == '>'){
					
					$data['filter_total_sum'] = html_entity_decode($data['filter_total_sum']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_total_sum']);
					
					if ($data['filter_total_sum'][0] == '<'){
						$implode[] = " total_cheque >= 0 AND total_cheque < '" . $num . "'";
						} elseif ($data['filter_total_sum'][0] == '>') {
						$implode[] = " total_cheque > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_total_sum'])) == 2){
					
					$nums = explode('-', $data['filter_total_sum']);
					
					$implode[] = " total_cheque >= '" . (int)$nums[0] . "' AND total_cheque <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " total_cheque >= '" . (int)$data['filter_total_sum'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_avg_cheque'])){
				$data['filter_avg_cheque'] = trim($data['filter_avg_cheque']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_avg_cheque'])[0] == '<' || html_entity_decode($data['filter_avg_cheque'])[0] == '>'){
					
					$data['filter_avg_cheque'] = html_entity_decode($data['filter_avg_cheque']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_avg_cheque']);
					
					if ($data['filter_avg_cheque'][0] == '<'){
						$implode[] = " avg_cheque >= 0 AND avg_cheque < '" . $num . "'";
						} elseif ($data['filter_avg_cheque'][0] == '>') {
						$implode[] = " avg_cheque > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_avg_cheque'])) == 2){
					
					$nums = explode('-', $data['filter_avg_cheque']);
					
					$implode[] = " avg_cheque >= '" . (int)$nums[0] . "' AND avg_cheque <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " avg_cheque >= '" . (int)$data['filter_avg_cheque'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_name'])) {
				$implode[] = "(CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'
				OR c.customer_id = '" . (int)$data['filter_name'] . "'
				OR REPLACE(c.discount_card, ' ', '') LIKE ('". $this->db->escape(str_replace(' ','',$data['filter_name']))."%') OR c.email LIKE '%" . $this->db->escape($data['filter_name']) . "%') ";
			}
			
			if (!empty($data['filter_last_call'])){
				
				$implode[] = "DATE(cc.date_end) <= '" . $this->db->escape($data['filter_last_call']) . "' AND cc.length > 0  AND cc.manager_id = '" . (int)$this->user->getID() . "'";
				
			}
			
			if (!empty($data['filter_simple_email'])) {
				$implode[] = "c.email LIKE ('%@%')";				
			}
			
			if (!empty($data['filter_email'])) {
				
				if ($data['filter_email'][0] == '!'){
					$emails = explode(',',$data['filter_email']);
					foreach ($emails as $email){
						$implode[] = "c.email NOT LIKE '%" . $this->db->escape(str_replace('!','',$email)) . "%'";	
					}
					} else {
					$emails = explode(',',$data['filter_email']);
					$implode_OR = array();
					foreach ($emails as $email){
						$implode_OR[] = "email LIKE '%" . $this->db->escape($email) . "%'";	
					}		
					
					if ($implode_OR && count($emails) >= 1){
						$implode[] = '(' . implode(" OR ", $implode_OR) . ')';
					}
				}
			}
			
			if (!empty($data['filter_gender'])) {
				$implode[] = "c.gender = '" . (int)$data['filter_gender'] . "'";
			}
			
			if (!empty($data['filter_phone'])) {
				$data['filter_phone'] = trim(preg_replace("([^0-9])", "", $data['filter_phone']));
				$implode[] = "((TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$data['filter_phone']."%') OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$data['filter_phone']."%'))";
			}
			
			if (!empty($data['filter_source'])) {
				$implode[] = "c.source LIKE '" . $this->db->escape($data['filter_source']) . "'";
			}
			
			if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
				$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
			}
			
			if (!empty($data['filter_customer_group_id'])) {
				$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
			}
			
			if (!empty($data['filter_ip'])) {
				$implode[] = "c.customer_id IN (SELECT customer_id FROM customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
			}
			
			if (!empty($data['filter_country_id'])) {
				$implode[] = "c.country_id = '" . (int)$data['filter_country_id'] . "'";
			}			
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
			}
			
			if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
				$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
			}
			
			if (!empty($data['filter_date_added'])) {
				$implode[] = "DATE(c.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_mail_status'])) {
				$implode[] = "mail_status = '" . $this->db->escape($data['filter_mail_status']) . "'";
			}
			
			if (isset($data['filter_has_discount']) && !is_null($data['filter_has_discount'])) {
				$implode[] = " LENGTH(discount_card) > '1'";
			}
			
			if (isset($data['filter_no_discount']) && !is_null($data['filter_no_discount'])) {
				$implode[] = " LENGTH(discount_card) = 0";
			}
			
			if (isset($data['filter_no_birthday']) && !is_null($data['filter_no_birthday'])) {
				$implode[] = " (birthday = '0000-00-00' OR NOT birthday)";
			}
			
			if (!empty($data['filter_mail_checked'])) {
				$implode[] = "mail_clicked > 0";
			}
			
			if (!empty($data['filter_mail_opened'])) {
				$implode[] = "mail_opened > 0";
			}
			
			if (!empty($data['filter_push_signed'])) {
				$implode[] = "has_push = 1";
			}
			
			if (!empty($data['filter_nbt_customer'])) {
				$implode[] = "nbt_customer = 1";
			}
			
			if (!empty($data['filter_nbt_customer_exclude'])) {
				$implode[] = "nbt_customer = 0";
			}
			
			if (!empty($data['filter_birthday_to'])) {
				if ($data['filter_birthday_to'][0] == '+' || $data['filter_birthday_to'][0] == '-'){
					$data['filter_birthday_to'] = $this->buildRelativeDate($data['filter_birthday_to']);
				}
				
				$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-".$data['filter_birthday_to']."'))";
				$implode[] = "DATE(birthday) > '0000-00-00'";
				
				if (empty($data['filter_birthday_from'])){
					$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
				}
			}
			
			if (!empty($data['filter_birthday_from'])) {
				$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-".$data['filter_birthday_from']."'))";
				$implode[] = "DATE(birthday) > '0000-00-00'";
				
				if (empty($data['filter_birthday_to'])){
					$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
				}
			}
			
			
			if (!empty($data['order_first_date_from']) || !empty($data['order_first_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_first_date_from'], $data['order_first_date_to'], 'order_first_date', false);
			}	
			
			if (!empty($data['order_last_date_from']) || !empty($data['order_last_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_last_date_from'], $data['order_last_date_to'], 'order_last_date', false);
			}	
			
			if (!empty($data['campaing_id'])) {
				$implode[] = "c.customer_id IN (SELECT DISTINCT `customer_id` FROM `customer_email_campaigns` WHERE `campaign_id` = '".$data['campaing_id']."')";
			}
			
			if (!empty($data['filter_segment_id'])) {
				$data['filter_segment_id'] = explode(',',$data['filter_segment_id']);
				$data['filter_segment_id'] = array_map("intval", $data['filter_segment_id']);
				if (empty($data['filter_segment_intersection'])){
					$implode[] = "c.customer_id IN (SELECT DISTINCT `customer_id` FROM `customer_segments` WHERE `segment_id` IN ( '". implode(',',$data['filter_segment_id']) ."' ))";
					} else {
					foreach ($data['filter_segment_id'] as $_segment_id){
						$implode[] = "c.customer_id IN (SELECT DISTINCT `customer_id` FROM `customer_segments` WHERE `segment_id` = '" . $_segment_id . "' )";
					}
				}
			}
			
			if (!empty($data['filter_custom_filter'])){
				if ($data['filter_custom_filter'] == 'nodiscount')	{
					
					$implode[] = "(c.customer_id IN (SELECT DISTINCT `customer_id` FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND date_modified >= '2016-07-29') AND LENGTH(c.discount_card) < 2)";
					$implode[] = "(c.customer_id IN (SELECT DISTINCT `customer_id` FROM `address` WHERE LENGTH(address_1) > 2 AND LENGTH(city) > 2))";
					$implode[] = "(c.country_id IN (220, 176))";
					
					} elseif ($data['filter_custom_filter'] == 'nopassport') {
					
					$_statuses = '(' . (int)$this->config->get('config_delivering_status_id') . ','
					. (int)$this->config->get('config_confirmed_nopaid_order_status_id') . ','
					. (int)$this->config->get('config_prepayment_paid_order_status_id') . ','
					. (int)$this->config->get('config_total_paid_order_status_id')
					. ')';
					
					$implode[] = "(c.customer_id IN (SELECT DISTINCT `customer_id` FROM `order` WHERE order_status_id IN " . $_statuses . " AND shipping_method LIKE ('%рской%') AND shipping_country_id = 176) AND (LENGTH(c.passport_serie)<2 OR LENGTH(c.passport_given)<2))";
					
					} elseif ($data['filter_custom_filter'] == 'hasviberphone') {
					
					$implode[] = " (LENGTH(telephone) > 4)";
					$implode[] = " (viber_news = 1)";
					
					}  elseif ($data['filter_custom_filter'] == 'noverifiedaddress') {
					
					$_statuses = '(' . (int)$this->config->get('config_complete_status_id') . ')';
					
					$implode[] = " (c.customer_id IN (SELECT DISTINCT customer_id FROM `order` WHERE order_status_id IN " . $_statuses . ") AND c.customer_id NOT IN (SELECT DISTINCT customer_id FROM address WHERE verified = 1))";
					
				}  
			}
			
			
			if ($implode) {
				$sql .= " AND " . implode(" AND ", $implode);
			}
			
			$sql .= " GROUP BY c.customer_id ";
			
			if (count($having_implode) > 0){
				$sql .= " HAVING (" . implode(" AND ", $having_implode) .")";
			}
			
			$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
			);
			
			if (!empty($data['filter_custom_filter'])){
				
				if ($data['filter_custom_filter'] == 'nodiscount')	{
					
					$sql .= " ORDER BY printed2912";
					
				}
				
				} else {
				
				if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
					$sql .= " ORDER BY " . $data['sort'];
					} else {
					$sql .= " ORDER BY name";
				}
				
			}
			
			if (!empty($data['filter_custom_filter'])){
				
				if ($data['filter_custom_filter'] == 'nodiscount')	{
					
					$sql .= " DESC";
					
				}
				
				} else {
				
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC";
					} else {
					$sql .= " ASC";
				}
				
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

			$query = $this->db->query($sql);
			return $query->rows;
		}
		
		public function approve($customer_id) {
			$this->load->model('setting/setting');

			$customer_info = $this->getCustomer($customer_id);
			
			if ($customer_info) {
				$this->db->query("UPDATE customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");
				
				$this->language->load('mail/customer');
				
				$this->load->model('setting/store');
				
				$store_info = $this->model_setting_store->getStore($customer_info['store_id']);
				
				if ($store_info) {
					$store_name = $store_info['name'];
					$store_url 	= $store_info['url'] . 'index.php?route=account/login';
					} else {
					return;
				}
				
				if ($store_info) {
					$store_url = ($this->config->get('config_secure') ? $store_info['ssl'] : $store_info['url']);
					$account_login = ($this->config->get('config_secure') ? $store_info['ssl'] : $store_info['url']) . 'index.php?route=account/login';
					} else {
					$store_url = ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG);
					$account_login = ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG) . 'index.php?route=account/login';
				}
				
				$message  = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";
				$message .= $this->language->get('text_approve_login') . "\n";
				$message .= $account_login . "\n\n"; 
				$message .= $this->language->get('text_approve_services') . "\n\n";
				$message .= $this->language->get('text_approve_thanks') . "\n";
				$message .= $store_name;
				
				$mail = new Mail($this->registry); 
				$mail->setTo($customer_info['email']);
				$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$customer_info['store_id']));
				$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']));

				$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_approve_subject'), $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id'])), ENT_QUOTES, 'UTF-8'));

				$template = new EmailTemplate($this->request, $this->registry);				
				$template->addData($customer_info, 'customer');				
				$template->data['text_welcome'] = sprintf($this->language->get('text_approve_welcome'), $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']));				
				$template->data['account_login'] = $account_login;
				$template->data['account_login_tracking'] = $template->getTracking($account_login);
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$template_data = array('key' =>'admin.customer_approve');
				if(isset($customer_info['store_id'])){
					$template_data['store_id'] = $customer_info['store_id'];
				}
				if(isset($customer_info['language_id'])){
					$template_data['language_id'] = $customer_info['language_id'];
				}
				$template->load($template_data);			
				$mail = $template->hook($mail);

				$mail->setTo($customer_info['email']);
				$mail->send();
			}
		}
		
		public function getAddress($address_id) {
			$address_query = $this->db->query("SELECT * FROM address WHERE address_id = '" . (int)$address_id . "'");
			
			if ($address_query->num_rows) {
				$country_query = $this->db->query("SELECT * FROM `country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
				
				if ($country_query->num_rows) {
					$country = $country_query->row['name'];
					$iso_code_2 = $country_query->row['iso_code_2'];
					$iso_code_3 = $country_query->row['iso_code_3'];
					$address_format = $country_query->row['address_format'];
					} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';
					$address_format = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$zone = $zone_query->row['name'];
					$zone_code = $zone_query->row['code'];
					} else {
					$zone = '';
					$zone_code = '';
				}
				
				return array(
				'address_id'     => $address_query->row['address_id'],
				'customer_id'    => $address_query->row['customer_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'company_id'     => $address_query->row['company_id'],
				'tax_id'         => $address_query->row['tax_id'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'verified'       => $address_query->row['verified'],
				'for_print'      => $address_query->row['for_print'],
				'passport_serie' => $address_query->row['passport_serie'],
				'passport_given' => $address_query->row['passport_given'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
				);
			}
		}
		
		public function getAddresses($customer_id) {
			$address_data = array();
			
			$query = $this->db->query("SELECT address_id FROM address WHERE customer_id = '" . (int)$customer_id . "'");
			
			foreach ($query->rows as $result) {
				$address_info = $this->getAddress($result['address_id']);
				
				if ($address_info) {
					$address_data[$result['address_id']] = $address_info;
				}
			}
			
			return $address_data;
		}
		
		public function getMyLastCall($customer_id){
			
			$query = $this->db->query("SELECT * FROM customer_calls WHERE customer_id = '" . (int)$customer_id . "' AND manager_id = '" . (int)$this->user->getID() . "' ORDER BY date_end ASC LIMIT 1");	
			
			if ($query->num_rows){
				return $query->row;
				} else {
				return false;
			}
			
		}
		
		
		public function getTotalCustomers($data = array()) {
			$sql = "SELECT COUNT(*) AS total FROM customer c";
			
			if (!empty($data['filter_last_call'])) {
				$sql .= " LEFT JOIN customer_calls cc ON (cc.customer_id = c.customer_id)";			
			}
			
			$implode = array();
			
			if (!empty($data['filter_name'])) {
				$implode[] = "(CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'
				OR c.customer_id = '" . (int)$data['filter_name'] . "'
				OR REPLACE(c.discount_card, ' ', '') LIKE ('". $this->db->escape(str_replace(' ','',$data['filter_name']))."%') OR c.email LIKE '%" . $this->db->escape($data['filter_name']) . "%') ";
			}
			
			if (!empty($data['filter_order_count'])){
				$data['filter_order_count'] = trim($data['filter_order_count']);
				
				if (html_entity_decode($data['filter_order_count'])[0] == '<' || html_entity_decode($data['filter_order_count'])[0] == '>'){
					
					$data['filter_order_count'] = html_entity_decode($data['filter_order_count']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_order_count']);
					
					if ($data['filter_order_count'][0] == '<'){
						$implode[] = " order_count >= 0 AND order_count < '" . $num . "'";
						} elseif ($data['filter_order_count'][0] == '>') {
						$implode[] = " order_count > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_order_count'])) == 2){
					
					$nums = explode('-', $data['filter_order_count']);
					
					$implode[] = " order_count >= '" . (int)$nums[0] . "' AND order_count <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " order_count >= '" . (int)$data['filter_order_count'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_order_good_count'])){
				$data['filter_order_good_count'] = trim($data['filter_order_good_count']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_order_good_count'])[0] == '<' || html_entity_decode($data['filter_order_good_count'])[0] == '>'){
					
					$data['filter_order_good_count'] = html_entity_decode($data['filter_order_good_count']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_order_good_count']);
					
					if ($data['filter_order_good_count'][0] == '<'){
						$implode[] = " order_good_count >= 0 AND order_good_count < '" . $num . "'";
						} elseif ($data['filter_order_good_count'][0] == '>') {
						$implode[] = " order_good_count > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_order_good_count'])) == 2){
					
					$nums = explode('-', $data['filter_order_good_count']);
					
					$implode[] = " order_good_count >= '" . (int)$nums[0] . "' AND order_good_count <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " order_good_count >= '" . (int)$data['filter_order_good_count'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_total_sum'])){
				$data['filter_total_sum'] = trim($data['filter_total_sum']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_total_sum'])[0] == '<' || html_entity_decode($data['filter_total_sum'])[0] == '>'){
					
					$data['filter_total_sum'] = html_entity_decode($data['filter_total_sum']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_total_sum']);
					
					if ($data['filter_total_sum'][0] == '<'){
						$implode[] = " total_cheque >= 0 AND total_cheque < '" . $num . "'";
						} elseif ($data['filter_total_sum'][0] == '>') {
						$implode[] = " total_cheque > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_total_sum'])) == 2){
					
					$nums = explode('-', $data['filter_total_sum']);
					
					$implode[] = " total_cheque >= '" . (int)$nums[0] . "' AND total_cheque <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " total_cheque >= '" . (int)$data['filter_total_sum'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_avg_cheque'])){
				$data['filter_avg_cheque'] = trim($data['filter_avg_cheque']);
				
				//первый символ < или >
				
				if (html_entity_decode($data['filter_avg_cheque'])[0] == '<' || html_entity_decode($data['filter_avg_cheque'])[0] == '>'){
					
					$data['filter_avg_cheque'] = html_entity_decode($data['filter_avg_cheque']);
					
					$num = (int)preg_replace('/[^0-9.]+/', '', $data['filter_avg_cheque']);
					
					if ($data['filter_avg_cheque'][0] == '<'){
						$implode[] = " avg_cheque >= 0 AND avg_cheque < '" . $num . "'";
						} elseif ($data['filter_avg_cheque'][0] == '>') {
						$implode[] = " avg_cheque > '" . $num . "'";
					}
					
					} elseif (count(explode('-', $data['filter_avg_cheque'])) == 2){
					
					$nums = explode('-', $data['filter_avg_cheque']);
					
					$implode[] = " avg_cheque >= '" . (int)$nums[0] . "' AND avg_cheque <= '" . (int)$nums[1] . "'";
					
					} else {
					
					$implode[] = " avg_cheque >= '" . (int)$data['filter_avg_cheque'] . "'";
					
				} 																														
			}
			
			if (!empty($data['filter_email'])) {
				if ($data['filter_email'][0] == '!'){
					$emails = explode(',',$data['filter_email']);
					foreach ($emails as $email){
						$implode[] = "email NOT LIKE '%" . $this->db->escape(str_replace('!','',$email)) . "%'";	
					}
					} else {
					$emails = explode(',',$data['filter_email']);
					$implode_OR = array();
					foreach ($emails as $email){
						$implode_OR[] = "email LIKE '%" . $this->db->escape($email) . "%'";	
					}
					
					if ($implode_OR && count($emails) >= 1){
						$implode[] = '(' . implode(" OR ", $implode_OR) . ')';
					}
				}
			}
			
			
			
			if (!empty($data['filter_phone'])) {
				$data['filter_phone'] = trim(preg_replace("([^0-9])", "", $data['filter_phone']));
				$implode[] = "((TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$data['filter_phone']."%') OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$data['filter_phone']."%'))";
			}
			
			if (!empty($data['filter_source'])) {
				$implode[] = "c.source LIKE '" . $this->db->escape($data['filter_source']) . "'";
			}
			
			if (!empty($data['filter_gender'])) {
				$implode[] = "c.gender = '" . (int)$data['filter_gender'] . "'";
			}
			
			if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
				$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
			}
			
			if (!empty($data['filter_customer_group_id'])) {
				$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
			}
			
			if (!empty($data['filter_ip'])) {
				$implode[] = "customer_id IN (SELECT customer_id FROM customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
			}
			
			if (!empty($data['filter_country_id'])) {
				$implode[] = "country_id = '" . (int)$data['filter_country_id'] . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$implode[] = "status = '" . (int)$data['filter_status'] . "'";
			}
			
			if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
				$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
			}
			
			if (isset($data['filter_has_discount']) && !is_null($data['filter_has_discount'])) {
				$implode[] = " LENGTH(discount_card) > '1'";
			}
			
			if (isset($data['filter_no_discount']) && !is_null($data['filter_no_discount'])) {
				$implode[] = " LENGTH(discount_card) = 0";
			}
			
			if (!empty($data['filter_date_added'])) {
				$implode[] = "DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_mail_status'])) {
				$implode[] = "mail_status = '" . $this->db->escape($data['filter_mail_status']) . "'";
			}
			if (!empty($data['filter_mail_checked'])) {
				$implode[] = "mail_clicked > 0";
			}
			
			if (!empty($data['filter_mail_opened'])) {
				$implode[] = "mail_opened > 0";
			}
			
			if (!empty($data['filter_push_signed'])) {
				$implode[] = "has_push = 1";
			}
			
			if (!empty($data['filter_nbt_customer'])) {
				$implode[] = "nbt_customer = 1";
			}
			
			if (!empty($data['filter_nbt_customer_exclude'])) {
				$implode[] = "nbt_customer = 0";
			}
			
			if (isset($data['filter_no_birthday']) && !is_null($data['filter_no_birthday'])) {
				$implode[] = " (birthday = '0000-00-00' OR NOT birthday)";
			}
			
			if (!empty($data['filter_last_call'])){				
				$implode[] = "DATE(cc.date_end) <= '" . $this->db->escape($data['filter_last_call']) . "' AND cc.length > 0  AND cc.manager_id = '" . (int)$this->user->getID() . "'";				
			}
			
			if (!empty($data['filter_birthday_to'])) {
				$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-".$data['filter_birthday_to']."'))";
				$implode[] = "DATE(birthday) > '0000-00-00'";
				
				if (empty($data['filter_birthday_from'])){
					$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
				}
			}
			
			if (!empty($data['filter_birthday_from'])) {
				$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-".$data['filter_birthday_from']."'))";
				$implode[] = "DATE(birthday) > '0000-00-00'";
				
				if (empty($data['filter_birthday_to'])){
					$implode[] = "DATE_ADD(birthday, INTERVAL YEAR(CURDATE())-YEAR(birthday) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
				}
			}
			
			if (!empty($data['order_first_date_from']) || !empty($data['order_first_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_first_date_from'], $data['order_first_date_to'], 'order_first_date', false);
			}	
			
			if (!empty($data['order_last_date_from']) || !empty($data['order_last_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_last_date_from'], $data['order_last_date_to'], 'order_last_date', false);
			}	
			
			
			if (!empty($data['campaing_id'])) {
				$implode[] = "c.customer_id IN (SELECT `customer_id` FROM `customer_email_campaigns` WHERE `campaign_id` = '".$data['campaing_id']."')";
			}
			
			if (!empty($data['filter_segment_id'])) {
				$data['filter_segment_id'] = explode(',',$data['filter_segment_id']);
				$data['filter_segment_id'] = array_map("intval", $data['filter_segment_id']);
				if (empty($data['filter_segment_intersection'])){
					$implode[] = "c.customer_id IN (SELECT DISTINCT `customer_id` FROM `customer_segments` WHERE `segment_id` IN ( '". implode(',',$data['filter_segment_id']) ."' ))";
					} else {
					foreach ($data['filter_segment_id'] as $_segment_id){
						$implode[] = "c.customer_id IN (SELECT DISTINCT `customer_id` FROM `customer_segments` WHERE `segment_id` = '" . $_segment_id . "' )";
					}
				}
			}
			
			if (!empty($data['filter_custom_filter'])){
				if ($data['filter_custom_filter'] == 'nodiscount')	{
					
					$implode[] = "(c.customer_id IN (SELECT DISTINCT `customer_id` FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND date_modified >= '2016-07-29') AND LENGTH(c.discount_card)<2)";
					$implode[] = "(c.customer_id IN (SELECT DISTINCT `customer_id` FROM `address` WHERE LENGTH(address_1) > 2 AND LENGTH(city) > 2))";
					$implode[] = "(c.country_id IN (220, 176))";
					
					} elseif ($data['filter_custom_filter'] == 'nopassport') {
					
					$_statuses = '(' . (int)$this->config->get('config_delivering_status_id') . ','
					. (int)$this->config->get('config_confirmed_nopaid_order_status_id') . ','
					. (int)$this->config->get('config_prepayment_paid_order_status_id') . ','
					. (int)$this->config->get('config_total_paid_order_status_id')
					. ')';
					
					$implode[] = "(c.customer_id IN (SELECT DISTINCT `customer_id` FROM `order` WHERE order_status_id IN " . $_statuses . " AND shipping_method LIKE ('%рской%') AND shipping_country_id = 176) AND (LENGTH(c.passport_serie)<2 OR LENGTH(c.passport_given)<2))";
					
					} elseif ($data['filter_custom_filter'] == 'hasviberphone') {
					
					$implode[] = " LENGTH(telephone) > 4";
					$implode[] = " (viber_news = 1)";
					
					}  elseif ($data['filter_custom_filter'] == 'noverifiedaddress') {
					
					$_statuses = '(' . (int)$this->config->get('config_complete_status_id') . ')';
					
					$implode[] = " (c.customer_id IN (SELECT DISTINCT customer_id FROM `order` WHERE order_status_id IN " . $_statuses . ") AND c.customer_id NOT IN (SELECT DISTINCT customer_id FROM address WHERE verified = 1))";
					
				}  
			}
			
			
			if ($implode) {
				$sql .= " WHERE " . implode(" AND ", $implode);
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getTotalCustomersAwaitingApproval() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE status = '0' OR approved = '0'");
			
			return $query->row['total'];
		}
		
		public function getTotalAddressesByCustomerId($customer_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalAddressesByCountryId($country_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE country_id = '" . (int)$country_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalAddressesByZoneId($zone_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE zone_id = '" . (int)$zone_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalCustomersByCustomerGroupId($customer_group_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");
			
			return $query->row['total'];
		}
		
		public function addHistoryExtended($data = array()){
			
			if (isset($data['customer_id'])){
				$customer_id = (int)$data['customer_id'];
				} else {
				$customer_id = 0;
			}
			
			if (isset($data['comment'])){
				$comment = $this->db->escape($data['comment']);
				} else {
				$comment = '';
			}
			
			if (isset($data['order_id'])){
				$order_id = (int)($data['order_id']);
				} else {
				$order_id = 0;
			}
			
			if (isset($data['call_id'])){
				$call_id = (int)($data['call_id']);
				} else {
				$call_id = 0;
			}
			
			if (isset($data['manager_id'])){
				if ($data['manager_id'] == 0){
					$manager_id = $this->user->getID();
					} else {
					$manager_id = (int)$data['manager_id'];
				}
				} else {
				$manager_id = (int)($data['call_id']);
			}
			
			if (isset($data['need_call'])){
				if (!$data['need_call']){
					$need_call = '0000-00-00 00:00';
					} else {
					$need_call = $data['need_call'];
				}
				} else {
				$need_call = false;
			}
			
			if (isset($data['segment_id'])){
				$segment_id = (int)($data['segment_id']);
				} else {
				$segment_id = 0;
			}
			
			if (isset($data['order_status_id'])){
				$order_status_id = (int)($data['order_status_id']);
				} else {
				$order_status_id = 0;
			}
			
			if (isset($data['prev_order_status_id'])){
				$prev_order_status_id = (int)($data['prev_order_status_id']);
				} else {
				$prev_order_status_id = 0;
			}
			
			if (isset($data['is_error'])){
				$is_error = (int)($data['is_error']);
				} else {
				$is_error = false;
			}
			
			$this->addHistory($customer_id, $comment, $order_id, $call_id, $manager_id, $need_call, $segment_id, $order_status_id, $prev_order_status_id, $is_error);						
		}
		
		public function addHistory($customer_id, $comment, $order_id = 0, $call_id = 0, $manager_id = 0, $need_call = false, $segment_id = 0, $order_status_id = 0, $prev_order_status_id = 0, $is_error = false) {
			
			if ($manager_id == 0){
				$manager_id = $this->user->getID();
			}
			
			if (!$need_call){
				$need_call = '0000-00-00 00:00';
				} else {
				$need_call = $need_call;
			}
			
			$this->db->query("INSERT INTO customer_history SET 
			customer_id = '" . (int)$customer_id . "', 
			comment = '" . $this->db->escape($comment) . "', 
			order_id = '" . (int)$order_id . "', 
			call_id = '" . (int)$call_id . "', 
			manager_id = '" . (int)$manager_id . "', 
			need_call = '" . $this->db->escape($need_call) . "', 
			segment_id = '" . (int)$segment_id . "', 
			order_status_id = '" . (int)$order_status_id . "', 
			prev_order_status_id = '" . (int)$prev_order_status_id . "',
			is_error = '" . (int)$is_error . "', 
			date_added = NOW()");
			
		}
		
		public function getHistories($customer_id, $start = 0, $limit = 10) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 10;
			}
			
			$query = $this->db->query("SELECT * FROM customer_history WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getTotalHistories($customer_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer_history WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}

		public function updateTransactionFromAPI($customer_transaction_id, $data){
			
			$query = $this->db->query("UPDATE customer_transaction SET 
			customer_id 		= '" . (int)$data['customer_id'] . "', 
			order_id 			= '" . (int)$data['order_id'] . "', 
			description 		= '" . $this->db->escape($data['description']) . "', 
			amount 				= '" . (float)$data['amount'] . "', 
			amount_national 	= '" . (float)$data['amount_national'] . "', 
			currency_code 		= '" . $this->db->escape($data['currency_code']) . "', 
			date_added 			= '" . $this->db->escape($data['date_added']) . "', 				
			added_from 			= '" . $this->db->escape($data['added_from']) . "', 
			legalperson_id 		= '" . (int)$data['legalperson_id'] . "', 
			guid 				= '" . $this->db->escape($data['guid']) . "'
			WHERE customer_transaction_id = '" . (int)$customer_transaction_id . "'");

			return $customer_transaction_id;
		}
		
		public function addTransactionFromAPI($data){
			
			$query = $this->db->query("INSERT INTO customer_transaction SET 
			customer_id 		= '" . (int)$data['customer_id'] . "', 
			order_id 			= '" . (int)$data['order_id'] . "', 
			description 		= '" . $this->db->escape($data['description']) . "', 
			amount 				= '" . (float)$data['amount'] . "', 
			amount_national 	= '" . (float)$data['amount_national'] . "', 
			currency_code 		= '" . $this->db->escape($data['currency_code']) . "', 
			date_added 			= '" . $this->db->escape($data['date_added']) . "', 				
			added_from 			= '" . $this->db->escape($data['added_from']) . "', 
			legalperson_id 		= '" . (int)$data['legalperson_id'] . "', 
			guid 				= '" . $this->db->escape($data['guid']) . "'");
			
			
			
			return $this->db->getLastId();
		}
		
		public function addTransaction($customer_id, $description = '', $amount = '', $amount_national = '', $currency_code = '', $order_id = 0, $send_sms = false, $date_added = false, $added_from = '', $legalperson_id = 0, $guid = '') {
			$customer_info = $this->getCustomer($customer_id);
			
			if ($customer_info) {
				
				if ($send_sms) {
					if ($date_added) {
						$query = "INSERT INTO customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', amount_national = '" . (float)$amount_national . "', currency_code = '" . $this->db->escape($currency_code) . "', date_added = '" . $this->db->escape($date_added) . "', sms_sent = NOW(), added_from = '" . $this->db->escape($added_from) . "', legalperson_id = '" . (int)$legalperson_id . "', guid = '" . $this->db->escape($guid) . "'";
						} else {
						$query = "INSERT INTO customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', amount_national = '" . (float)$amount_national . "', currency_code = '" . $this->db->escape($currency_code) . "', date_added = NOW(), sms_sent = NOW(), added_from = '" . $this->db->escape($added_from) . "', legalperson_id = '" . (int)$legalperson_id . "', guid = '" . $this->db->escape($guid) . "'";
					}
					} else {
					if ($date_added) {
						$query = "INSERT INTO customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', amount_national = '" . (float)$amount_national . "', currency_code = '" . $this->db->escape($currency_code) . "', date_added = '" . $this->db->escape($date_added) . "', added_from = '" . $this->db->escape($added_from) . "', legalperson_id = '" . (int)$legalperson_id . "', guid = '" . $this->db->escape($guid) . "'";
						} else {
						$query = "INSERT INTO customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', amount_national = '" . (float)$amount_national . "', currency_code = '" . $this->db->escape($currency_code) . "', date_added = NOW(), added_from = '" . $this->db->escape($added_from) . "', legalperson_id = '" . (int)$legalperson_id . "', guid = '" . $this->db->escape($guid) . "'";
					}
				}
				
				
				$this->db->query($query);
				$transaction_id = $this->db->getLastId();
				
				$customer_comment_text = 'Финансовая транзакция: '.$amount_national.' '.$currency_code;
				$this->addHistory((int)$customer_id, $customer_comment_text, (int)$order_id);
				
				if ($this->user->isLogged()) {
					$this->db->query("INSERT INTO `order_save_history` SET 
					`order_id` = '" . (int)$order_id . "', 
					`user_id` = '" . (int)$this->user->getId() . "',
					`datetime` = NOW()
					");
				}
				
				$this->language->load('mail/customer');
				
				if ($customer_info['store_id']) {
					$this->load->model('setting/store');
					
					$store_info = $this->model_setting_store->getStore($customer_info['store_id']);
					
					if ($store_info) {
						$store_name = $store_info['name'];
						} else {
						$store_name = $this->config->get('config_name');
					}
					} else {
					$store_name = $this->config->get('config_name');
				}
				
				$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
				$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($customer_id)));
				
				$this->load->model('feed/exchange1c');
				$this->model_feed_exchange1c->addOrderToQueue($order_id);
				$this->model_feed_exchange1c->getOrderTransactionsXML($order_id);
				
				$mail = new Mail($this->registry); 
				$mail->setTo($customer_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($store_name);
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				//$mail->send();
			}
			
			return $transaction_id;
		}
		
		public function deleteTransaction($order_id) {
			$this->db->query("DELETE FROM customer_transaction WHERE order_id = '" . (int)$order_id . "'");
		}
		
		public function getTransactionByID($transaction_id) {
			
			$query = $this->db->query("SELECT ct.*, lp.legalperson_id, lp.legalperson_name, lp.legalperson_name_1C 
			FROM customer_transaction ct LEFT JOIN legalperson lp ON ct.legalperson_id = lp.legalperson_id
			WHERE customer_transaction_id = '" . (int)$transaction_id . "'");
			
			if ($query->num_rows){
				return $query->row;
				} else {
				return false;
			}
		}
		
		public function updateTransactionGUIDByTransactionID($transaction_id, $guid){		
			$this->db->query("UPDATE customer_transaction SET guid = '" . $this->db->escape($guid) . "' WHERE customer_transaction_id = '" . (int)$transaction_id . "'");
		}
		
		public function getTransactionByGUID($guid) {
			
			$query = $this->db->query("SELECT ct.*, lp.legalperson_id, lp.legalperson_name, lp.legalperson_name_1C 
			FROM customer_transaction ct LEFT JOIN legalperson lp ON ct.legalperson_id = lp.legalperson_id
			WHERE guid LIKE '" . $this->db->escape($guid) . "'");
			
			if ($query->num_rows){
				return $query->row;
				} else {
				return false;
			}
			
		}
		
		public function getTransactions($customer_id, $start = 0, $limit = 10, $order_id = false) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 10;
			}
			
			if ($order_id){
				$query = $this->db->query("SELECT ct.*, lp.legalperson_id, lp.legalperson_name, lp.legalperson_name_1C FROM customer_transaction ct LEFT JOIN legalperson lp ON ct.legalperson_id = lp.legalperson_id
				WHERE order_id = '" . (int)$order_id . "' ORDER BY date_added ASC LIMIT " . (int)$start . "," . (int)$limit);								
				} else {
				$query = $this->db->query("SELECT ct.*, lp.legalperson_id, lp.legalperson_name, lp.legalperson_name_1C FROM customer_transaction ct LEFT JOIN legalperson lp ON ct.legalperson_id = lp.legalperson_id
				WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
			}
			
			return $query->rows;
		}
		
		public function getTotalTransactions($customer_id, $order_id = false) {
			if ($order_id){
				$query = $this->db->query("SELECT COUNT(*) AS total  FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id ."'");
				} else {
				$query = $this->db->query("SELECT COUNT(*) AS total  FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			return $query->row['total'];
		}
		
		public function getTransactionTotal($customer_id, $order_id = false) {
			if ($order_id){
				$query = $this->db->query("SELECT SUM(amount) AS total FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id ."'");
				} else {
				$query = $this->db->query("SELECT SUM(amount) AS total FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			return $query->row['total'];
		}
		
		public function getTransactionTotalNational($customer_id, $order_id = false) {
			if ($order_id){
				$query = $this->db->query("SELECT SUM(amount_national) AS total_national FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id ."'");
				} else {
				$query = $this->db->query("SELECT SUM(amount_national) AS total_national FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			return $query->row['total_national'];
		}
		
		public function getTotalTransactionsByOrderId($order_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer_transaction WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}
		
		public function getSourcesOfFirstReferrer() {
			$query = $this->db->query("SELECT DISTINCT first_order_source FROM customer WHERE NOT ISNULL(first_order_source)");
			
			return $query->rows;
		}
		
		
		public function addReward($customer_id, $description = '', $points = 0, $order_id = 0, $reason_code = 'UNKNOWN') {
			$this->load->model('setting/setting');
			
			$customer_info 	= $this->getCustomer($customer_id);
			
			if ($customer_info){
				
				$this->customer->addReward($customer_id, $description, $points, $order_id, $reason_code);

				$this->customer->addToEMAQueue($customer_id);
				
				$this->language->load('mail/customer');
				
				if ($order_id) {
					$this->load->model('sale/order');
					
					$order_info = $this->model_sale_order->getOrder($order_id);
					
					if ($order_info) {
						$store_name = $order_info['store_name'];
						} else {
						$store_name = $this->config->get('config_mail_trigger_name_from');
					}
					} else {
					$store_name = $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']);
				}
				
				$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
				$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));
				
				$mail = new Mail($this->registry); 
				$mail->setTo($customer_info['email']);
				$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$customer_info['store_id']));
				$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']));
				
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_reward_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
				
				$template = new EmailTemplate($this->request, $this->registry);				
				$template->addData($customer_info, 'customer');			
				$template->data['reward_received'] = sprintf($this->language->get('text_reward_received'), $points);
				$template->data['reward_total'] = sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$template_data = array('key' =>'admin.customer_reward');

				if(isset($customer_info['store_id'])){
					$template_data['store_id'] = $customer_info['store_id'];
				}
				if(isset($customer_info['language_id'])){
					$template_data['language_id'] = $customer_info['language_id'];
				}				
				$template->load($template_data);				
				$mail = $template->hook($mail); 	

				$mail->setTo($customer_info['email']);			
				$mail->send();				
				$template->sent();
			}
		}
		
		public function getIsOpt($customer_id){
			$opt_group_array = array(8,9,10,11);
			
			$query = $this->db->query("SELECT customer_group_id FROM customer WHERE customer_id = '" . (int)$customer_id . "' LIMIT 1");
			
			if (!isset($query->row['customer_group_id'])){
				return false;
			}
			
			return in_array($query->row['customer_group_id'], $opt_group_array);
		}
		
		public function getIsMudak($customer_id){
			$mudak_group_id = $this->config->get('config_bad_customer_group_id');
			
			$query = $this->db->query("SELECT customer_id FROM customer WHERE customer_id = '" . (int)$customer_id . "' AND (customer_group_id = '" . (int)$mudak_group_id . "' OR mudak = 1) LIMIT 1");
			
			
			if ($query->row){
			return true;
			} else {
			return false;
		}
		}
		
		public function getCustomerSegments($customer_id){					
			$query = $this->db->query(
			"SELECT *, (DATE_ADD(cs.date_added, INTERVAL s.new_days DAY) >= DATE(NOW())) as is_new FROM customer_segments cs
			LEFT JOIN segments s ON s.segment_id = cs.segment_id
			WHERE customer_id = '" .(int)$customer_id. "'");				
			
			return $query->rows;		
		}
		
		
		
		public function setIsMudakAjax(){
			$customer_id = (int)$this->request->get['customer_id'];
			$mudak_group_id = $this->config->get('config_bad_customer_group_id');
			
			$this->db->query("UPDATE customer SET mudak = '1', customer_group_id = '" . (int)$mudak_group_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $this->getIsMudak($customer_id);
			
		}
		
		public function deleteReward($order_id) {
			$this->db->query("DELETE FROM customer_reward WHERE order_id = '" . (int)$order_id . "'");
		}
		
		public function getAllPositiveRewards($customer_id){
			$query = $this->db->query("SELECT * FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND points > 0 ORDER BY date_added ASC");
			
			return $query->rows;
			
		}
		
		public function getRewardsQueue($customer_id) {
			$query = $this->db->query("SELECT * FROM customer_reward_queue WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added ASC");
			
			return $query->rows;
		}
		
		
		public function getRewards($customer_id, $start = 0, $limit = 50) {
			$query = $this->db->query("SELECT * FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getTotalRewards($customer_id) {
		
			if (defined('YANDEX_MARKET_CUSTOMER_ID') && $customer_id == YANDEX_MARKET_CUSTOMER_ID){
				$this->db->query("DELETE FROM customer_reward WHERE customer_id = '" . (int)YANDEX_MARKET_CUSTOMER_ID . "'");
				$this->db->query("DELETE FROM customer_reward_queue WHERE customer_id = '" . (int)YANDEX_MARKET_CUSTOMER_ID . "'");
			}
		
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getRewardTotal($customer_id) {
			$query = $this->db->query("SELECT SUM(points) AS total FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrders($customer_id) {
			$query = $this->db->query("SELECT count(*) AS total FROM `order` WHERE customer_id = '" . (int)$customer_id . "' AND order_status_id > 0");
			
			return $query->row['total'];
		}
		
		public function getTotalOrdersGood($customer_id) {
			$query = $this->db->query("SELECT count(*) AS total FROM `order` WHERE customer_id = '" . (int)$customer_id . "' AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'");
			
			return $query->row['total'];
		}
		
		public function getOrderTotal($customer_id) {
			$query = $this->db->query("SELECT SUM(total_national) AS total FROM `order` WHERE customer_id = '" . (int)$customer_id . "' AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'");
			
			return $query->row['total'];
		}
		
		
		public function getTotalCalls($customer_id) {
			$query = $this->db->query("SELECT count(*) AS total FROM `customer_calls` WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getCustomerPhone($customer_id) {
			$query = $this->db->query("SELECT telephone FROM `customer` WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['telephone'];
		}
		
		public function getCustomerName($customer_id) {
			$query = $this->db->query("SELECT CONCAT(firstname, ' ', lastname) as name FROM `customer` WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['name'];
		}
		
		public function getTotalCustomerRewardsByOrderId($order_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer_reward WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}
		
		public function getIpsByCustomerId($customer_id) {
			$query = $this->db->query("SELECT * FROM customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}
		
		public function getTotalCustomersByIp($ip) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");
			
			return $query->row['total'];
		}
		
		public function addBanIp($ip) {
			$this->db->query("INSERT INTO `customer_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
		}
		
		public function removeBanIp($ip) {
			$this->db->query("DELETE FROM `customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
		}
		
		public function getTotalBanIpsByIp($ip) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
			
			return $query->row['total'];
		}
		
		public function getCustomerCalls($customer_id, $data=array()){
			
			$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) as customer_name FROM customer_calls cc LEFT JOIN customer c ON cc.customer_id = c.customer_id WHERE cc.customer_id = '". (int)$customer_id ."'";
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND TIME(cc.date_end) > TIME('" .$this->db->escape($data['filter_date_end']). "')";
			}
			
			$sql .= " ORDER BY cc.date_end DESC";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getCustomerEmailCampaigns($customer_id){
			$query = $this->db->query("SELECT *, cecn.email_campaign_name as name FROM customer_email_campaigns cec LEFT JOIN customer_email_campaigns_names cecn ON cec.campaign_id = cecn.email_campaign_mailwizz_id  WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}
		
		public function get_gender( $name ) {			
			
		}
		
		public function getMailStatus () {
			$sql = "SELECT DISTINCT (`mail_status`) FROM `customer` WHERE 1 = 1";
			$query = $this->db->query($sql);
			
			$statusArray = array();
			foreach ($query->rows as $r) {
				if ($r['mail_status']) {
					$statusArray[] = $r['mail_status'];
				}
			}
			
			return $statusArray;
		}
		
		public function getAllMailCampaings () {
			$sql = "SELECT DISTINCT(cec.`campaign_id`) as id, cecn.email_campaign_name as name FROM `customer_email_campaigns` cec LEFT JOIN `customer_email_campaigns_names` cecn ON (cec.campaign_id = cecn.email_campaign_mailwizz_id ) WHERE 1";
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
	}					
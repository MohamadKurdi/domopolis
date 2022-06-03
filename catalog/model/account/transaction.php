<?php
	class ModelAccountTransaction extends Model {	
		public function getTransactions($data = array()) {
			$sql = "SELECT * FROM `customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "'";
			
			if (isset($data['order_id']) && $data['order_id'] = $this->request->get['order_id']) {
				$sql .= " AND order_id = '" . (int)$data['order_id'] . "'";
			}
			
			$sort_data = array(
			'amount',
			'description',
			'date_added'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
				} else {
				$sql .= " ORDER BY date_added";	
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
		
		public function addTransaction($description = '', $amount = '', $amount_national = '', $currency_code = '', $order_id = 0, $send_sms = false, $added_from = '', $paykeeper_id = '', $fop_receipt_key = '', $concardis_id = '', $json = '') {
			$this->load->model('account/customer');
			
			$customer_id = (int)$this->customer->getId();
			$customer_info = $this->model_account_customer->getCustomer($customer_id);
			
			if (!$customer_info){
				if ($order_id){
					$customer_id = $this->getCustomerIdByOrderId($order_id);
					$customer_info = $this->model_account_customer->getCustomer($customer_id);
				}			
			}				
			
			if ($customer_info) { 
				if ($send_sms) {
					$query = "INSERT INTO customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', amount_national = '" . (float)$amount_national . "', currency_code = '" . $this->db->escape($currency_code) . "', date_added = NOW(), sms_sent = NOW(), added_from = '" . $this->db->escape($added_from) . "', paykeeper_id = '" . $this->db->escape($paykeeper_id) . "', f3_id = '" . $this->db->escape($fop_receipt_key) . "', concardis_id = '" . $this->db->escape($concardis_id) . "', json = '" . $this->db->escape(json_encode($json)) . "'";	
					} else {
					$query = "INSERT INTO customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', amount_national = '" . (float)$amount_national . "', currency_code = '" . $this->db->escape($currency_code) . "', date_added = NOW(), added_from = '" . $this->db->escape($added_from) . "', paykeeper_id = '" . $this->db->escape($paykeeper_id) . "', f3_id = '" . $this->db->escape($fop_receipt_key) . "', concardis_id = '" . $this->db->escape($concardis_id) . ", json = '" . $this->db->escape(json_encode($json)) . "'";	
				}
				$this->db->query($query);
			}
		}
		
		public function getF3Cheques($paykeeper_id){
			
			
			
		}
		
		public function getTransaction($transaction_id, $field_to_return = false){
			$result = $this->db->non_cached_query("SELECT * `customer_transaction` WHERE transaction_id = '". (int)$transaction_id ."' LIMIT 1");
			
			if ($query->num_rows) {
				if ($field_to_return && isset($result->row[$field_to_return])){
					return $query->row[$field_to_return];	
					} else	{
					return $query->row;
				}
				} else {
				return false;	
			}
		}
		
		public function getCustomerIdByOrderId($order_id){
			$result = $this->db->non_cached_query("SELECT customer_id FROM `order` WHERE order_id = '". (int)$order_id ."' LIMIT 1");
			
			return (int)$result->row['customer_id'];		
		}
		
		public function getTotalAmount() {
			$query = $this->db->non_cached_query("SELECT SUM(amount) AS total FROM `customer_transaction` WHERE customer_id = '" . (int)$this->customer->getId() . "' GROUP BY customer_id");
			
			if ($query->num_rows) {
				return $query->row['total'];
				} else {
				return 0;	
			}
		}
		
		public function getTotalTransactions($order_id = false) {
			$customer_id = (int)$this->customer->getId();
			if ($order_id){
				$query = $this->db->non_cached_query("SELECT COUNT(*) AS total  FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id ."'");
				} else {
				$query = $this->db->non_cached_query("SELECT COUNT(*) AS total  FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			return $query->row['total'];
		}
		
		public function getTransactionTotal($order_id = false) {
			$customer_id = (int)$this->customer->getId();
			if ($order_id){
				$query = $this->db->non_cached_query("SELECT SUM(amount) AS total FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id ."'");
				} else {	
				$query = $this->db->non_cached_query("SELECT SUM(amount) AS total FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
			}
			
			return $query->row['total'];
		}
		
		public function getTransactionTotalNational($order_id = false) {
			$customer_id = (int)$this->customer->getId();
			if ($order_id){
				$query = $this->db->non_cached_query("SELECT SUM(amount_national) AS total_national FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id ."'");
				} else {
				$query = $this->db->non_cached_query("SELECT SUM(amount_national) AS total_national FROM customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");			
			}
			
			return $query->row['total_national'];
		}
		
		public function getTotalTransactionsByOrderId($order_id) {
			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM customer_transaction WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}	
	}
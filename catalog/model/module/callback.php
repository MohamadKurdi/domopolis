<?php
	class ModelModuleCallback extends Model {	
		
		public function addWaitList($data) {
			
			if ($this->customer->isLogged()){
				$customer_id = $this->customer->isLogged();
				} else {
				$customer_id = 0;
			}
			
			if (!$customer_id && $data['phone'] != ''){
				
				$phone = trim(preg_replace("([^0-9])", "", $data['phone']));
				
				$sql = "SELECT customer_id FROM `customer` WHERE ";		
				$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
				$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
				$sql .= " LIMIT 1";		
				$tquery = $this->db->query($sql);
				
				if ($tquery->row && $tquery->row['customer_id']) {
					$customer_id = (int)$tquery->row['customer_id'];
				}
			}
			
			$this->load->model('account/customer');
			$this->load->model('catalog/product');
			
			$customer = $this->model_account_customer->getCustomer($customer_id);
			$product_info = $this->model_catalog_product->getProduct($data['product_id']);
			
			if ($customer){
				$firstname = $customer['firstname'];
				$lastname = $customer['lastname'];
				} else {
				$firstname = $lastname = '';
			}
			
			$price_national = $this->currency->convert($product_info['price'], $this->config->get('config_currency'), $this->currency->getCode());
			$price = $product_info['price'];
			if ($product_info['special']){
				$price_national = $this->currency->convert($product_info['special'], $this->config->get('config_currency'), $this->currency->getCode());
				$price = $product_info['special'];
			}
			
			//PRICE_NATIONAL
			if ($product_info['price_national'] && $product_info['price_national'] > 0 && $product_info['currency'] == $this->currency->getCode()) {
				$price_national = $this->currency->format($product_info['price_national'], $product_info['currency'], 1, false);
			}
			
			
			$query = $this->db->query("INSERT INTO order_product_nogood SET 
			order_id = '0',
			customer_id = '" . (int)$customer_id . "',
			store_id = '" . (int)$this->config->get('config_store_id') . "',
			telephone = '" . $this->db->escape($data['phone']) . "',
			firstname = '" . $this->db->escape($firstname) . "',
			lastname = '" . $this->db->escape($lastname) . "',
			product_id = '" . (int)$data['product_id'] . "',
			ao_id = '0',
			good = '0',
			taken = '0',
			name = '" . $this->db->escape($product_info['name']) . "',
			model = '" . $this->db->escape($product_info['model']) . "',
			quantity = '1',
			is_prewaitlist = '1',
			date_added = NOW(),
			currency = '" . $this->currency->getCode() . "',
			price = '" . $this->db->escape($price) . "',
			price_national = '" . $this->db->escape($price_national) . "',
			original_price_national = '" . $this->db->escape($price_national) . "',
			total = '" . $this->db->escape($price) . "',
			total_national  = '" . $this->db->escape($price_national) . "',
			tax = '0',
			reward = '0',
			waitlist = '1',
			supplier_has = '0'");
			
		}
		
		public function addCallback($data) {
			
			if ($this->customer->isLogged()){
				$customer_id = $this->customer->isLogged();
				$data['name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
				} else {
				$customer_id = 0;
			}
					
			
			if (!$customer_id && $data['phone'] != ''){
				
				$phone = trim(preg_replace("([^0-9])", "", $data['phone']));
				
				$sql = "SELECT customer_id FROM `customer` WHERE ";		
				$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
				$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
				$sql .= " LIMIT 1";		
				$tquery = $this->db->query($sql);
				
				if ($tquery->row && $tquery->row['customer_id']) {
					$customer_id = (int)$tquery->row['customer_id'];
				}
			}
			
				//try to guess customer_id by email
			if (!$customer_id && mb_strlen(trim($data['email_buyer'])) > 5 && trim(data['email_buyer']) != ''){
				$cquery = $this->db->query("SELECT customer_id FROM `customer` WHERE LENGTH(email) > 1 AND email LIKE '". trim($this->db->escape($data['email_buyer'])) ."' LIMIT 1");
				if (isset($cquery->row['customer_id'])){
					$customer_id = (int)$cquery->row['customer_id'];
				}				
			} 
			
			$is_cheaper = (isset($data['is_cheaper']) && $data['is_cheaper']);
			$product_id = (isset($data['product_id']) && $data['product_id'])?$data['product_id']:0;
						
			if (!$queue_id = $this->config->get('config_default_queue')){
				$queue_id = DEFAULT_QUEUE;
			}
			
    	  	$query = $this->db->query("INSERT INTO callback SET 
			name = '" . $this->db->escape($data['name'])  . "', 
			comment_buyer = '" . $this->db->escape($data['comment_buyer'])  . "',
			email_buyer = '" . $this->db->escape($data['email_buyer'])  . "', 
			telephone = '" . $this->db->escape($data['phone']) . "',
			customer_id = '". (int)$customer_id ."',
			product_id = '". (int)$data['product_id'] ."',
			sip_queue = '". (int)$queue_id ."',
			date_added = NOW(), 
			date_modified = NOW(), 
			status_id = '0',
			is_cheaper = '" . (int)$is_cheaper . "', 
			comment = ''"
			);		
			
			return $this->db->getLastId();
		}	
		
	}			
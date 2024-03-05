<?php
	class ModelModuleCallback extends Model {	
		
		public function addWaitList($data) {
			$this->load->model('account/customer');
			$this->load->model('catalog/product');
			
			$customer_id = false;
			if ($this->customer->isLogged()){
				$customer_id = $this->customer->isLogged();
			}
			
			if ($data['phone'] && trim($data['phone']) && !$customer_id){
				$customer_info = $this->model_account_customer->getCustomerByPhone($data['phone']);
				if ($customer_info){
					$customer_id = $customer_info['customer_id'];
				}	
			}
			
			$customer 		= $this->model_account_customer->getCustomer($customer_id);
			$product_info 	= $this->model_catalog_product->getProduct($data['product_id']);
			
			if ($customer){
				$firstname 	= $customer['firstname'];
				$lastname 	= $customer['lastname'];
				} else {
				$firstname 	= '';
				$lastname 	= '';
			}
			
			$price_national = $this->currency->convert($product_info['price'], $this->config->get('config_currency'), $this->currency->getCode());
			$price = $product_info['price'];
			if ($product_info['special']){
				$price_national = $this->currency->convert($product_info['special'], $this->config->get('config_currency'), $this->currency->getCode());
				$price 			= $product_info['special'];
			}
			
			if ($product_info['price_national'] && $product_info['price_national'] > 0 && $product_info['currency'] == $this->currency->getCode()) {
				$price_national = $this->currency->format($product_info['price_national'], $product_info['currency'], 1, false);
			}			
			
			$query = $this->db->query("INSERT INTO order_product_nogood SET 
			order_id 			= '0',
			customer_id 		= '" . (int)$customer_id . "',
			store_id 			= '" . (int)$this->config->get('config_store_id') . "',
			telephone 			= '" . $this->db->escape($data['phone']) . "',
			firstname 			= '" . $this->db->escape($firstname) . "',
			lastname 			= '" . $this->db->escape($lastname) . "',
			product_id 			= '" . (int)$data['product_id'] . "',
			ao_id 				= '0',
			good 				= '0',
			taken 				= '0',
			name 				= '" . $this->db->escape($product_info['name']) . "',
			model 				= '" . $this->db->escape($product_info['model']) . "',
			quantity 			= '1',
			is_prewaitlist 		= '1',
			date_added 			= NOW(),
			currency 			= '" . $this->currency->getCode() . "',
			price 				= '" . $this->db->escape($price) . "',
			price_national 		= '" . $this->db->escape($price_national) . "',
			original_price_national = '" . $this->db->escape($price_national) . "',
			total 				= '" . $this->db->escape($price) . "',
			total_national  	= '" . $this->db->escape($price_national) . "',
			tax 				= '0',
			reward 				= '0',
			waitlist 			= '1',
			supplier_has 		= '0'");			
		}
		
		public function addCallback($data) {
			$this->load->model('account/customer');
			$this->load->model('catalog/product');
			
			$customer_id = 0;

			if ($this->customer->isLogged()){
				$customer_id 	= $this->customer->isLogged();
				$data['name'] 	= $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
			}			

			if ($data['phone'] && trim($data['phone']) && !$customer_id){
				$customer_info = $this->model_account_customer->getCustomerByPhone($data['phone']);
				if ($customer_info){
					$customer_id = $customer_info['customer_id'];
				}	
			}

			if ($data['email'] && trim($data['email']) && !$customer_id){
				$customer_info = $this->model_account_customer->getCustomerByEmail($data['email']);
				if ($customer_info){
					$customer_id = $customer_info['customer_id'];
				}
			}

			if ($data['email_buyer'] && trim($data['email_buyer']) && !$customer_id){
				$customer_info = $this->model_account_customer->getCustomerByEmail($data['email_buyer']);
				if ($customer_info){
					$customer_id = $customer_info['customer_id'];
				}
			}
			
			$is_cheaper = (!empty($data['is_cheaper']))?1:0;
			$product_id = (!empty($data['product_id']))?$data['product_id']:0;
						
			if (!$queue_id = $this->config->get('config_default_queue')){
				$queue_id = DEFAULT_QUEUE;
			}
			
    	  	$query = $this->db->query("INSERT INTO callback SET 
			name 			= '" . $this->db->escape($data['name'])  . "', 
			comment_buyer 	= '" . $this->db->escape($data['comment_buyer'])  . "',
			email_buyer 	= '" . $this->db->escape($data['email_buyer'])  . "', 
			telephone 		= '" . $this->db->escape($data['phone']) . "',
			customer_id 	= '". (int)$customer_id ."',
			product_id 		= '". (int)$data['product_id'] ."',
			sip_queue 		= '". (int)$queue_id ."',
			date_added 		= NOW(), 
			date_modified 	= NOW(), 
			status_id 		= '0',
			is_cheaper 		= '" . (int)$is_cheaper . "', 
			comment 		= ''");		
			
			return $this->db->getLastId();
		}	
		
	}			
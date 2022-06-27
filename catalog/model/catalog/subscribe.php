<?php
	class ModelCatalogSubscribe extends Model {
		
		public function addSubscribe($email) {
			$this->load->model('account/customer');
			
			$this->model_account_customer->addCustomer([
			'firstname' 		=> $email,
			'lastname' 			=> '',
			'customer_group_id' => $this->config->get('config_customer_group_id'),
			'email' 			=> $email,
			'telephone' 		=> '',
			'fax' 				=> '',
			'password' 			=> rand(9999, 999999),
			'company' 			=> '',
			'company_id'		=> 0,
			'tax_id' 			=> 0,
			'newsletter' 		=> 1,
			'newsletter_news' 	=> 1,
			'newsletter_personal' => 1,
			'address_1' 		=> '',
			'address_2' 		=> '',
			'city' 				=> '',
			'postcode' 			=> '',
			'country_id' 		=> $this->config->get('config_country_id'),
			'zone_id' 			=> '',
			'company' 			=> ''
			]);
			
			$this->customer->login($email, false, true);
		}
		
		public function editEmail($email) {
			
			if ($this->customer->isLogged()){
				$this->db->query("UPDATE customer SET email = '" . $this->db->escape($email) . "', newsletter = 1, newsletter_news = 1, newsletter_personal = 1 WHERE customer_id = '" . $this->customer->getID() . "'");
				
				$this->customer->addToEMAQueue();
			}
			
		}
	}

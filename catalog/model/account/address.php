<?php
	class ModelAccountAddress extends Model {
		public function addAddress($data) {
			
			if (empty($data['country_id'])){
				$data['country_id'] =  $this->config->get('config_country_id');
			}
			
			$this->db->ncquery("INSERT INTO address SET customer_id = '" . (int)$this->customer->getId() . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape(isset($data['company_id']) ? $data['company_id'] : '') . "', tax_id = '" . $this->db->escape(isset($data['tax_id']) ? $data['tax_id'] : '') . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "'");
			
			$address_id = $this->db->getLastId();
			
			if (!empty($data['default'])) {
				$this->db->ncquery("UPDATE customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			}
			
			return $address_id;
		}
		
		public function editAddress($address_id, $data) {		
			if (empty($data['country_id'])){
				$data['country_id'] =  $this->config->get('config_country_id');
			}
		
			$this->db->ncquery("UPDATE address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape(isset($data['company_id']) ? $data['company_id'] : '') . "', tax_id = '" . $this->db->escape(isset($data['tax_id']) ? $data['tax_id'] : '') . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
			
			if (!empty($data['default'])) {
				$this->db->ncquery("UPDATE customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			}
		}
		
		public function deleteAddress($address_id) {
			$this->db->ncquery("DELETE FROM address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
		}	
		
		public function getAddress($address_id) {
			$address_query = $this->db->ncquery("SELECT DISTINCT * FROM address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
			
			if (!$address_query->num_rows || !$address_query->row['country_id']){
				$address_query->row['country_id'] = $this->config->get('config_country_id');
			}
			
			if ($address_query->num_rows) {
				$country_query = $this->db->ncquery("SELECT * FROM `country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
				
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
				
				$zone_query = $this->db->ncquery("SELECT * FROM `zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$zone = $zone_query->row['name'];
					$zone_code = $zone_query->row['code'];
					} else {
					$zone = '';
					$zone_code = '';
				}		
				
				
				$address_data = array(
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'company_id'     => $address_query->row['company_id'],
				'tax_id'         => $address_query->row['tax_id'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
				);
				
				return $address_data;
				} else {
				return false;	
			}
		}
		
		/*
			* If the customer is not logged in and we need a way to get the address.
		*/
		public function getAddressNotLoggedIn($address_id, $customer_id)
		{
			$address_query = $this->db->query("SELECT DISTINCT * FROM address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$customer_id . "'");
			
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
					$code = $zone_query->row['code'];
					} else {
					$zone = '';
					$code = '';
				}
				
				$address_data = array(
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
				);
				
				return $address_data;
				} else {
				return false;
			}
		}
		
		public function getAddresses() {
			$address_data = array();
			
			$query = $this->db->ncquery("SELECT * FROM address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			$query2 = $this->db->ncquery("SELECT address_id FROM customer WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			foreach ($query->rows as $result) {
				$country_query = $this->db->ncquery("SELECT * FROM `country` WHERE country_id = '" . (int)$result['country_id'] . "'");
				
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
				
				$zone_query = $this->db->ncquery("SELECT * FROM `zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$zone = $zone_query->row['name'];
					$zone_code = $zone_query->row['code'];
					} else {
					$zone = '';
					$zone_code = '';
				}		
				
				$address_data[$result['address_id']] = [
					'address_id'     => $result['address_id'],
					'default' 		 => (bool)($result['address_id'] == $query2->row['address_id']),
					'firstname'      => $result['firstname'],
					'lastname'       => $result['lastname'],
					'company'        => $result['company'],
					'company_id'     => $result['company_id'],
					'tax_id'         => $result['tax_id'],				
					'address_1'      => $result['address_1'],
					'address_2'      => $result['address_2'],
					'postcode'       => $result['postcode'],
					'city'           => $result['city'],
					'zone_id'        => $result['zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $result['country_id'],
					'country'        => $country,	
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format
				];
			}		
			
			return $address_data;
		}	
		
		public function getTotalAddresses() {
			$query = $this->db->ncquery("SELECT COUNT(*) AS total FROM address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			
			return $query->row['total'];
		}
	}
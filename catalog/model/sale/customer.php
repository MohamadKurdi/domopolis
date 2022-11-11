<?php
	class ModelSaleCustomer extends Model {

		
		public function getCustomer($customer_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row;
		}
		

	}					
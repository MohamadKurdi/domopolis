<?php 
class ModelSaleSmsSending extends Model {
        
        public function getSettings () {
		$query = $this->db->query("SELECT * FROM `oc_epochta_settings`");
	
		return $query->row;
	}
    
        public function getCustomerGroups() {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'  ORDER BY cg.sort_order, cgd.name";
			
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
        
        public function getCustomers($customer_group_id) {
		$sql = "SELECT *, CONCAT(firstname, ' ',lastname) FROM " . DB_PREFIX . "customer WHERE  customer_group_id = '" .(int)$customer_group_id. "' ORDER BY firstname";
		
		$query = $this->db->query($sql);
                
		return $query->rows;	
	}
}

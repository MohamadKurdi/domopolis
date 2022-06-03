<?php
class ModelModuleAPRI extends Model {
	
	public function getAPRIOrders(){
		$sql = "SELECT GROUP_CONCAT(order_id ORDER BY order_id DESC SEPARATOR ',') AS list_orders, customer_id, firstname, lastname, email, language_id FROM `order`
				WHERE order_status_id IN (" . implode(',', $this->config->get('apri_allowed_statuses')) . ")
				AND order_id NOT IN (SELECT order_id FROM apri) 
				AND MD5(email) NOT IN (SELECT md5_email FROM apri_unsubscribe) ";
		
		if ($this->config->get('apri_start_date')) {
			$sql .= "AND date_added >= '" . $this->config->get('apri_start_date') . "' "; 
		}
		
		if ($this->config->get('apri_days_after')) {
			$sql .= "AND DATEDIFF(NOW(),date_added) >= " . $this->config->get('apri_days_after') . " "; 
		}
		
		$sql .= "GROUP BY email";
		
		$query = $this->db->query($sql);

		return $query->rows;	
	}
	
	public function getAPRIOrderProducts($list_orders){
		$sql = "SELECT DISTINCT op.product_id, op.name, p.image FROM order_product op 
				LEFT JOIN product p ON op.product_id = p.product_id 
				WHERE op.order_id IN (" . $list_orders . ")";

		$query = $this->db->query($sql);

		return $query->rows;	
	}
	
	public function setAsNotified($orders_list) {
	
		$orders = explode(",", $orders_list);
		
		foreach ($orders as $order_id) {
		
			$sql = "INSERT INTO apri
					SET order_id   = '" . (int)$order_id . "',
						date_added = NOW()";
						
			$this->db->query($sql);
		}	
	}
	
	private function isInUnsubscribersList($md5_email) {
		$sql = "SELECT COUNT(*) AS total FROM apri_unsubscribe WHERE md5_email = '" . $md5_email . "'" ;
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function unsubscribe($md5_email) {
		if (!$this->isInUnsubscribersList($md5_email)) {
			
			$sql = "INSERT INTO apri_unsubscribe
					SET md5_email      = '" . $this->db->escape($md5_email) . "',
						date_added = NOW()";
						
			$this->db->query($sql);			
		}
	}
}
?>
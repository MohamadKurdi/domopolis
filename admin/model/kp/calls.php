<?
	class ModelKpCalls extends Model {
		
		
		public function countMissedCallsForToday($data = array()){
			
			$query = $this->db->query("SELECT COUNT(call_id) as total FROM callback WHERE DATE(date_added) = DATE(NOW()) AND is_missed = 1 AND sip_queue = '" . DEFAULT_QUEUE . "'");			
			
			return $query->row['total'];
			
		}
		
		public function countTotalCallsForToday($data){
			
			
			$query = $this->db->query("SELECT COUNT(customer_call_id) as total FROM customer_calls WHERE inbound = 1 AND length > 0 AND DATE(date_end) = DATE(NOW()) AND sip_queue = '" . $this->db->escape($data['queue']) . "'");			
			
			return $query->row['total'];
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
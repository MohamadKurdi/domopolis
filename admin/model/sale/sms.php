<?
	
	class ModelSaleSMS extends Model {
		
		public function logSms($phone, $text){
			
			$this->db->query("INSERT INTO oc_sms_log SET phone='" . $this->db->escape($phone) . "', text='" . $this->db->escape($text) . "', date_send = NOW()");
			
		}
		
		public function getLog(){
			$query = $this->db->query("SELECT * FROM oc_sms_log WHERE 1 ORDER BY id DESC");
			
			return $query->rows;
		}
		
		public function getSMSByID($sms_id, $customer_id, $order_id){
		
			$sql = "SELECT * FROM order_sms_history osh 
				WHERE sms_id = '" . (int)$sms_id . "'";
				
			$sql .= "AND (customer_id = '" . (int)$customer_id . "'";
			
			if (!empty($order_id) && $order_id){
				$sql .= " OR order_id = '" . (int)$order_id . "')";
			}
		
			$query = $this->db->query($sql);
			

			return $query->row;	
		}
		
		
		
		
		
		
		
		
	}	
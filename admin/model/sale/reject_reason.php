<?
class ModelSaleRejectReason extends Model {
	function getRejectReasons(){
		$query = $this->db->query("SELECT * FROM order_reject_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->rows;
	}
	
	function getRejectReasonName($reject_reason_id){
		$query = $this->db->query("SELECT name FROM order_reject_reason WHERE reject_reason_id = '" . $reject_reason_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
		
		return $query->row['name'];
	}
	
	
}
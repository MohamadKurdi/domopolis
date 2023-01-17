<?

class ControllerApiDeliveryServices extends Controller { 
	public function index(){
		if (!is_cli()){
			die('CLI ONLY');
		}
	}

	public function cron(){
		if ($this->config->get('config_justin_api_key')){
			$this->courierServices->updateReferences('Justin');					
		}

		if ($this->config->get('cdek_login')){
			$this->courierServices->updateReferences('Cdek');
		}

		if ($this->config->get('config_novaposhta_api_key')){
			$this->courierServices->updateReferences('NovaPoshta');
		}
	}

	public function track(){
		$codes = [];

		$query = $this->db->query("SELECT * FROM order_ttns ot LEFT JOIN `order` o ON (o.order_id = ot.order_id) WHERE ot.ttn <> '' AND o.order_status_id = '" . $this->config->get('config_delivering_status_id') . "'");

		foreach ($query->rows as $row){
			$this->db->query("UPDATE order_ttns SET tracking_status = 'UNEXISTENT' WHERE ttn = '" . $this->db->escape($row['ttn']) . "'");

			if (empty($codes[$row['delivery_code']])){
				$codes[$row['delivery_code']] = [];
			}

			$codes[$row['delivery_code']][] = $row['ttn'];
		}
		
		$result = $this->courierServices->trackMultiCodes($codes);
		
		foreach ($result as $shipping => $codes){			
			foreach ($codes as $code => $tracking_data){
				$this->db->query("UPDATE order_ttns SET 
					tracking_status = '" . $this->db->escape($tracking_data['tracking_status']) . "', 
					taken 			= '" . (int)$tracking_data['taken'] . "',
					tracking_data 	= '" . $this->db->escape(json_encode($tracking_data)) . "'
					WHERE ttn = '" . $this->db->escape($code) . "'");
			}
		}		

	}		
}				
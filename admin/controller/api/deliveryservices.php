<?

class ControllerApiDeliveryServices extends Controller { 
	public function index(){
		if (!is_cli()){
			die('CLI ONLY');
		}
	}

	public function references(){
		if ($this->config->get('config_cdek_api_login')){
			$this->courierServices->updateReferences('Cdek');
		}

		if ($this->config->get('config_novaposhta_api_key')){
			$this->courierServices->updateReferences('NovaPoshta');
		}
	}

	public function track(){
		if (!$this->config->get('config_shipping_enable_tracker_worker')){
			echoLine('[ControllerKPService::smsQueue] CRON IS DISABLED IN ADMIN', 'e');
			return;
		}

		if ($this->config->has('config_shipping_enable_tracker_worker_time_start') && $this->config->has('config_shipping_enable_tracker_worker_time_end')){
			$interval = new Interval($this->config->get('config_shipping_enable_tracker_worker_time_start') . '-' . $this->config->get('config_shipping_enable_tracker_worker_time_end'));

			if (!$interval->isNow()){
				echoLine('[ControllerApiDeliveryServices::track] NOT ALLOWED TIME', 'e');
				return;
			} else {
				echoLine('[ControllerApiDeliveryServices::track] ALLOWED TIME', 's');				
			}
		}


		$codes = [];

		$query = $this->db->query("SELECT ot.* FROM order_ttns ot LEFT JOIN `order` o ON (o.order_id = ot.order_id) WHERE 
			ot.ttn <> ''
			AND ((o.order_status_id = '" . $this->config->get('config_delivering_status_id') . "' AND ot.rejected = 0 AND ot.taken = 0)  OR ot.tracking_status = '' OR ot.waiting = 1)");

		foreach ($query->rows as $row){			
			if (empty($codes[$row['delivery_code']])){
				$codes[$row['delivery_code']] = [];
			}

			$codes[$row['delivery_code']][] = $row['ttn'];			
		}

		if ($codes){
			echoLine('[ControllerApiDeliveryServices::track] Codes to track: ' . $query->num_rows, 'i');
			$result = $this->courierServices->trackMultiCodes($codes);

			foreach ($result as $shipping => $codes){			
				foreach ($codes as $code => $tracking_data){
					$this->db->query("UPDATE order_ttns SET 
						tracking_status = '" . $this->db->escape($tracking_data['tracking_status']) . "', 
						taken 			= '" . (int)$tracking_data['taken'] . "',
						rejected 		= '" . (int)$tracking_data['rejected'] . "',
						waiting 		= '" . (int)$tracking_data['waiting'] . "',					
						tracking_data 	= '" . $this->db->escape(json_encode($tracking_data)) . "'
						WHERE ttn = '" . $this->db->escape($code) . "'");
				}
			}			
		} else {
			echoLine('[ControllerApiDeliveryServices::track] No codes to track', 'i');
		}

		$this->db->query("UPDATE order_ttns SET waiting = 0 WHERE taken = 1 OR rejected = 1");	
	}		
}				
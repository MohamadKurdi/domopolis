<?
	class ModelKpCSI extends Model {
		private $csi_fields = array(		
		'csi_mark',
		'speed_mark',
		'manager_mark',
		'quality_mark',
		'courier_mark'
		);
		
		public function canGetCSI($order_status_id){
			
			$this->load->model('localisation/order_status');
			
			return (count($this->model_localisation_order_status->getAllowedOrderStatuses($order_status_id)) == 1);
			
		}
		
		public function getCSIOrderStatuses(){
			$this->load->model('localisation/order_status');
			
			$query = $this->db->query("SELECT DISTINCT order_status_id, status_fa_icon, name FROM `order_status` WHERE order_status_id NOT IN (SELECT DISTINCT order_status_id FROM order_status_linked) AND LENGTH(status_fa_icon) > 1 AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			return $query->rows;
		}
		
	
		public function getOrdersWithoutCSI($customer_id){
			
			$query = $this->db->query("SELECT DISTINCT order_id FROM `order` WHERE 
				customer_id = '" . (int)$customer_id . "' 
				AND csi_reject = 0 
				AND csi_average = 0 
				AND DATE(date_modified) >= DATE('" . date('Y-m-d', strtotime('-1 month')) . "') 
				AND DATE(date_added) >= DATE('" . date('Y-m-d', strtotime('-2 month')) . "') 
				AND order_status_id IN (" . $this->config->get('config_complete_status_id') . "," . $this->config->get('config_cancelled_status_id') . ")");
				
			$data = array();
			if ($query->num_rows > 0){
				foreach ($query->rows as $row){
					$data[] = $row['order_id'];
				}
			}
			
			return $data;
			
		}
		
		public function getCompletedOrdersWithoutCSI($customer_id){
			
			$query = $this->db->query("SELECT DISTINCT order_id FROM `order` WHERE 
				customer_id = '" . (int)$customer_id . "' 
				AND csi_reject = 0 
				AND csi_average = 0 
				AND DATE(date_modified) >= DATE('" . date('Y-m-d', strtotime('-1 month')) . "') 
				AND DATE(date_added) >= DATE('" . date('Y-m-d', strtotime('-2 month')) . "') 
				AND order_status_id IN (" . $this->config->get('config_complete_status_id') . "," . $this->config->get('config_cancelled_status_id') . ")");
				
			$data = array();
			if ($query->num_rows > 1){
				foreach ($query->rows as $row){
					$data[] = $row['order_id'];
				}
			}
			
			return $data;
			
		}
		
		public function getCSIOrderStatusesLine(){
			$this->load->model('localisation/order_status');
			
			$query = $this->db->query("SELECT DISTINCT order_status_id, status_fa_icon, name FROM `order_status` WHERE order_status_id NOT IN (SELECT DISTINCT order_status_id FROM order_status_linked) AND LENGTH(status_fa_icon) > 1");
			
			$data = array();
			
			foreach ($query->rows as $row){
				$data[] = $row['order_status_id'];
			}
			
			return implode(',', $data);
		}
		
		public function countOrderCSI($order_id, $order_info = array()){			
			$order = array();
			$csi_average = 0;
			
			if (!$order_info){
				$this->load->model('sale/order');
				$order = $this->model_sale_order->getOrder($order_id);				
				} else {				
				$order = $order_info;			
			}
			
			//отказ вообще
			if ($order['csi_reject']){				
				return -1;
			}
			
			$has_mark = false;
			foreach ($this->csi_fields as $_cf1){
				if ($order[$_cf1] > 0){
					$has_mark = true;
					break;
				}
			}						
			
			//среднее по больнице
			if ($has_mark){			
				$counter = 0;
				$total = 0;
				
				foreach ($this->csi_fields as $_cf){					
					$total += $order[$_cf];
					if ($order[$_cf] > 0){
						$counter++;
					}					
				}
				
				$csi_average = round($total/$counter, 1);
				if (true) {
					$this->db->query("UPDATE `order` SET csi_average = '" . (float)$csi_average . "' WHERE order_id = '" . (int)$order_id . "'");
				}
				return $csi_average;
				} else {
				return 0;			
			}
		}
		
		
	}
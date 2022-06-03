<?php 
	class ModelPaymentWayforpay extends Model {
		public function getMethod($address, $total) {
			$this->load->language('payment/wayforpay');
			
			$sql = "SELECT *
			FROM zone_to_geo_zone
			WHERE geo_zone_id = '" . (int)$this->config->get('wayforpay_geo_zone_id') . "'
			AND country_id = '" . (int)$address['country_id'] . "'
			AND (zone_id = '" . $address['zone_id'] . "' OR zone_id = '0')";
			
			$query = $this->db->query($sql);
			
			if ($this->config->get('wayforpay_total') > 0 && $this->config->get('wayforpay_total') > $total) {
				$status = false;
				} elseif (!$this->config->get('wayforpay_geo_zone_id')) {
				$status = true;
				} elseif ($query->num_rows) {
				$status = true;
				} else {
				$status = false;
			}
			
			if ($status){
				$status = true;
				} else {
				$status = false;			
			}
			
			$method_data = array();
			
			if ($status) {
				$method_data = array(
				'code'       	=> 'wayforpay',
				'checkactive' 	=> true,
				'title'      	=> $this->language->get('text_title'),
				'description' 	=> $this->language->get('text_description'),
				'sort_order' 	=> $this->config->get('wayforpay_sort_order'),
				'terms' 		=> ''
				);
			}
			
			return $method_data;
		}
	}

<?php 
	class ModelPaymentConcardis extends Model {
		const METHOD_CODE = 'concardis';
		
		public function getMethod($address, $total) {
			$method_data = array();
			
			$status = false;
			
			if (($this->config->get('concardis_status'))){
				$status = true;
			}
			
			$this->language->load('payment/concardis');					
					
			$status = false;
			
			if ($status) {
				$method_data = array(
				'code'        => self::METHOD_CODE,
				'status'      => $status,
				'checkactive' => true,
				'title'      => $this->language->get('text_title'),
				'description' => $this->language->get('text_description'),
				'sort_order' => $this->config->get('concardis_sort_order')
				);
			}
			
			return $method_data;						
		}
		
		public function getOrderStatusById($order_status_id, $language_id = false) {
			if (!$language_id) {
				$language_id = (int)$this->config->get('config_language_id');
			}
			$query = $this->db->non_cached_query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . $language_id . "'");
			return $query->num_rows ? $query->row['name'] : '';
		}
		
		public function getOrder($order_id) {
			$query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			$result = $query->num_rows ? $query->row : false;
			if ($result) {
				if (!isset($result['payment_code'])) {
					$method_code = self::METHOD_CODE;
					$result['payment_code'] = strtoupper($result['payment_method']) == strtoupper(self::METHOD_CODE) ? self::METHOD_CODE : '';
				}
			}
			return $result;
		}
		
		public function checkLaterpay($order_id) {
			if (($this->config->get('concardis_status'))) {
				$order_info = $this->getOrder($order_id);
				
				if ((!$order_info)) {
					return false;
				}
				
				return $order_info['order_status_id'] == $this->config->get('concardis_pending_status_id');
			}
			return false;
		}
	}
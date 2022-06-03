<?php 
	class ModelPaymentAdyen extends Model {
		const METHOD_CODE = 'adyen';
		
		public function getMethod($address, $total) {
			
			$status = false;
			
			if (($this->config->get('paykeeper_status'))){
				$status = true;
			}
			
			$this->language->load('payment/paykeeper');
			
			if ((int)$address['country_id'] == 220 || (int)$address['country_id'] == 81) {
				$status = false;
			}
			
			//БЕЗУСЛОВНО ДЛЯ КАТАЛОГА
			$status = false;
			
			if ($status) {
				$method_data = array(
				'code'        => self::METHOD_CODE,
				'status'      => $status,
				'checkactive' => true,
				'title'      => $this->language->get('text_title'),
				'description' => $this->language->get('text_description'),
				'sort_order' => $this->config->get('paykeeper_sort_order')
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
				//Compatibles for v1.5.1.3 and low
				if (!isset($result['payment_code'])) {
					$method_code = self::METHOD_CODE;
					$result['payment_code'] = strtoupper($result['payment_method']) == strtoupper(self::METHOD_CODE) ? self::METHOD_CODE : '';
				}
			}
			return $result;
		}
		
		public function checkLaterpay($order_id) {
			if (($this->config->get('paykeeper_status'))) {
				$order_info = $this->getOrder($order_id);
				
				if ((!$order_info)) {
					return false;
				}
				
				return $order_info['order_status_id'] == $this->config->get('paykeeper_order_later_status_id');
			}
			return false;
		}
	}
?>
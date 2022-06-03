<?php
	class ModelPaymentBankTransfer extends Model {
		public function getMethod($address, $total) {
			$this->language->load('payment/bank_transfer');
			
			$status = true;
			
			if ((int)$address['country_id'] == 220) {
				$status = false;
			}
			
			$coupon_exists = false;
			if (isset($this->session->data['coupon']) AND mb_strlen($this->session->data['coupon'])>0){
				$coupon_exists = true;
			}
			
			$disable_discount = false;				
			//если нет дисконтной карты или это не скидка
			if ((bool)$this->customer->getDiscountCard()){
				$disable_discount = true;
			}
			
			//если есть купон и это скидка то не применять
			if (!$disable_discount && $coupon_exists){
				$disable_discount = true;			
			}
			
			$method_data = array();
			
			if ($status) {
				$method_data = array(
				'code'       => 'bank_transfer',
				'title'      => $this->language->get('text_title'),
				'checkactive' => true,
				'status'     => $status,
				'description' => $disable_discount?$this->language->get('text_description_nodiscount'):$this->language->get('text_description'),
				'sort_order' => $this->config->get('bank_transfer_sort_order')
				);
			}
			
			return $method_data;
		}
	}
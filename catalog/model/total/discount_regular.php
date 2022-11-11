<?php
	class ModelTotalDiscountRegular extends Model {
		
		private $discount_category = BIRTHDAY_DISCOUNT_CATEGORY;
		
		public function checkIfProductHasDiscount($product_id){
			
			if ($this->customer->getHasBirthday()){
				
				$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$this->discount_category . "'");
				
				return $query->num_rows;
				
			}
			
			return false;
		}
		
		public function getTotal(&$total_data, &$total) {					
            if ($this->customer->isLogged() && $this->customer->getHasBirthday()) {
				
				if (isset($this->session->data['coupon']) AND mb_strlen($this->session->data['coupon'])>0){
					$this->load->model('checkout/coupon');
					$_coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);					
				}
				
                $query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$this->customer->isLogged() . "'");
				
				$discount_percent = 0;
				
				if ($this->customer->getDiscountPercent() < 10){
					$discount_percent = 10;
				}
				
				if ($this->customer->getDiscountPercent() == 10){
					$discount_percent = 12;
				}
				
				$this->load->language('total/discount_regular');
				
				$discount_total = 0;
				foreach ($this->cart->getProducts() as $product) {
					
					if ($this->checkIfProductHasDiscount($product['product_id']) && !in_array($product['product_id'], $_coupon['product'])){
						$discount_total += $product['total'] / 100 * $discount_percent;
					}
					
				}
				
				$discount_total_national = $this->currency->convert($discount_total, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
				
				if ($discount_total_national) {
					$total_data[] = array( 
					'code'       => 'discount_regular',
					'title'      => $this->language->get('text_discount_regular') . ' (' . $discount_percent . '%)',
					'text'       => $this->currency->format(-$discount_total_national, $this->config->get('config_regional_currency'), '1'),
					'value_national' => -$discount_total_national,
					'value'      => -$discount_total,
					'sort_order' => $this->config->get('discount_regular_sort_order')
					);  									
					
					$total -= $total * $discount_total;
					
				}				
			} 
		}
	}						
<?php
class ModelTotalCoupon extends Model {	

	public function getTotal(&$total_data, &$total, &$taxes) {
		
		if ($this->cart->hasAdditionalOffer()){
			return;
		}
		
		if (isset($this->session->data['coupon'])) {
			$this->language->load('total/coupon');

			$this->load->model('checkout/coupon');

			$coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon_info) {
				$discount_total = 0;
				
				if (!$coupon_info['product']) {
					$sub_total 			= $this->cart->getSubTotal();
					$sub_total_national = $this->cart->getSubTotalInNationalCurrency();
				} else {
					$sub_total = 0;
					$sub_total_national = 0;

					foreach ($this->cart->getProducts() as $product) {
						if (in_array($product['product_id'], $coupon_info['product'])) {
							$sub_total 			+= $product['total'];
							$sub_total_national += $product['total_national'];
						}
					}					
				}											


				if ($coupon_info['currency'] != ''){
					if ($coupon_info['currency'] == $this->config->get('config_regional_currency')){
						$subtotal_national = $sub_total_national;
					} else {						
						$subtotal_national = $this->currency->convert($sub_total, $this->config->get('config_currency'), $coupon_info['currency']);
					}
				}

				if ($coupon_info['type'] == 'F' && (int)$coupon_info['discount'] > 0) {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total_national);
				}							

				if (isset($coupon_info['discount_sum']) && $coupon_info['discount_sum'] != ''){						
					$rates = explode(',', $coupon_info['discount_sum']);						
					if (count($rates) > 0) {
						foreach ($rates as $rate) {
							$data = explode(':', $rate);
							$data[0] = trim($data[0]);											
							if ($data[0] <= $subtotal_national) {												
								if (isset($data[1])) {
									if ($coupon_info['type'] == 'F') {
										$discount = trim($data[1]);
									} elseif ($coupon_info['type'] == 'P') {
										$discount = $subtotal_national / 100 * trim($data[1]);
									}
								}
							}
						}
					}
					$discount_total += $discount;
				}

				if ($coupon_info['type'] == 'P') {
					foreach ($this->cart->getProducts() as $product) {		
						$discount = 0;

						if ($coupon_info['currency']!=''){
							if ($coupon_info['currency'] == $this->config->get('config_regional_currency')){
								$product['total'] = $product['total'];
							} else {						
								$product['total'] = $this->currency->convert($product['total'], $this->config->get('config_currency'), $coupon_info['currency']);
							}
						} else {
							$product['total'] = $product['total'];								
						}


						if (!$coupon_info['product']) {
							$status = true;
						} else {
							if (in_array($product['product_id'], $coupon_info['product'])) {
								$status = true;
							} else {
								$status = false;
							}
						}

						if ($coupon_info['only_in_stock']){
							if (!$product['current_in_stock']){
								$status = false;
							}
						}						

						if ($status) {							
							$discount = $product['total'] / 100 * $coupon_info['discount'];
							$discount_total += $discount;

							if ($product['tax_class_id']) {
								$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

								foreach ($tax_rates as $tax_rate) {
									if ($tax_rate['type'] == 'P') {
										$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
									}
								}
							}
						}
					}
				}

				if ($coupon_info['type'] == 'F') {							
					if ($coupon_info['currency']!=''){
						if ($coupon_info['currency'] == $this->config->get('config_regional_currency')){
							$discount_total = $coupon_info['discount'];	
						} else {						
							$discount_total = $this->currency->convert($coupon_info['discount'], $coupon_info['currency'], $this->config->get('config_currency'));	
						}
					}
				}

				if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
					if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
						$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}

					$discount_total += $this->session->data['shipping_method']['cost'];				
				}

				if ($coupon_info['type'] == '3'){
					if ($this->model_checkout_coupon->countTotalActiveCartProducts($coupon_info, 3)){

						$discount_total = $this->model_checkout_coupon->getCheapestCartProductFromActive($coupon_info, 'min');

						if ($coupon_info['currency']!=''){
							$discount_total_national = $discount_total;
							$discount_total = $this->currency->convert($discount_total, $coupon_info['currency'], $this->config->get('config_currency'));					
						} else {
							$discount_total_national = $this->currency->convert($discount_total, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
						}
					}				
				}

				if ($coupon_info['type'] == '4'){
					if ($this->model_checkout_coupon->countTotalActiveCartProducts($coupon_info, 4)){

						$discount_total = $this->model_checkout_coupon->getCheapestCartProductFromActive($coupon_info, 'min');

						if ($coupon_info['currency']!=''){
							$discount_total_national = $discount_total;
							$discount_total = $this->currency->convert($discount_total, $coupon_info['currency'], $this->config->get('config_currency'));					
						} else {
							$discount_total_national = $this->currency->convert($discount_total, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
						}
					}				
				}

				if ($coupon_info['type'] == '5'){
					if ($this->model_checkout_coupon->countTotalActiveCartProducts($coupon_info, 5)){

						$discount_total = $this->model_checkout_coupon->getCheapestCartProductFromActive($coupon_info, 'min');

						if ($coupon_info['currency']!=''){
							$discount_total_national = $discount_total;
							$discount_total = $this->currency->convert($discount_total, $coupon_info['currency'], $this->config->get('config_currency'));					
						} else {
							$discount_total_national = $this->currency->convert($discount_total, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));			
						}
					}				
				}

				if ($coupon_info['currency']!=''){
					if ($coupon_info['currency'] == $this->config->get('config_regional_currency')){
						$discount_total_national = $discount_total;
						$discount_total = $this->currency->convert($discount_total, $coupon_info['currency'], $this->config->get('config_currency'));
					} else {	
						$discount_total_national = $discount_total;
						$discount_total = $this->currency->convert($discount_total, $coupon_info['currency'], $this->config->get('config_currency'));	
					}
				} else {
					$discount_total_national = $this->currency->convert($discount_total, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
				}

				$total_data[] = array(
					'code'       		=> 'coupon',
					'title'      		=> sprintf($this->language->get('text_coupon'), $this->session->data['coupon']),
					'text'       		=> $this->currency->format(-$discount_total_national, $this->config->get('config_regional_currency'), '1'),
					'value_national' 	=> -$discount_total_national,
					'value'      		=> -$discount_total,
					'sort_order' 		=> $this->config->get('coupon_sort_order')
				);

				$total -= $discount_total;
			} 
		}
	}

	public function confirm($order_info, $order_total) {
		$code = '';

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {  
			$code = substr($order_total['title'], $start, $end - $start);
		}	

		$this->load->model('checkout/coupon');

		$coupon_info = $this->model_checkout_coupon->getCoupon($code);

		if ($coupon_info) {
			$this->model_checkout_coupon->redeem($coupon_info['coupon_id'], $order_info['order_id'], $order_info['customer_id'], $order_total['value']);	
		}						
	}
}							
<?php

class ModelTotalPaymentMethodDiscounts extends Model {
	public function getTotal(&$total_data, &$total, &$taxes, $check_this_method = false, $product_price = []) {
		$current_payment_method 			= false;
		$current_secondary_payment_method 	= false;

		if ($check_this_method){
			$current_payment_method = [
				'title' => 'dummy',
				'code'  => $check_this_method
			];
		} else {
			if (isset($this->session->data['payment_method'])){
				$current_payment_method = $this->session->data['payment_method'];
			}
			
			if (isset($this->session->data['payment_secondary_method'])){
				$current_secondary_payment_method = $this->session->data['payment_secondary_method'];
			}
		}

		if(!empty($current_payment_method)){
			$discounts = $this->config->get('paymentmethoddiscounts_discount');
			if (!$discounts){
				$discounts = [];
			}

			$payment_method='';
			if (!empty($current_payment_method)) {
				$payment_method = $current_payment_method['title'];
			}

			if (!empty($current_secondary_payment_method)) {
				$payment_method = $current_secondary_payment_method['title'];
			}

			if ($check_this_method && $product_price){
				$sub_total 			= $product_price['price'];
				$sub_total_national = $product_price['price_national'];
			} else {
				$sub_total 			= $this->cart->getSubTotal();
				$sub_total_national = $this->cart->getSubTotalInNationalCurrency();
			}

			foreach ($discounts as $discount){
				$status = true;

				if (!empty($discount['deliveries'])){
					$exploded = explode(PHP_EOL, $discount['deliveries']);

					$status = false;
					foreach ($exploded as $line){
						if (trim($line) == $this->session->data['shipping_method']['code']){
							$status = true;
							break;
						}
					}
				}

				if ($status && $discount['paymentmethod'] == $current_payment_method['code'] || (!empty($current_secondary_payment_method) && $discount['paymentmethod'] == $current_secondary_payment_method['code'])) {
					$this->load->language('total/paymentmethoddiscounts');

					$number = 0;

					if($discount['znak']){
						if($discount['mode']) {
							$number 			= -$sub_total*$discount['number']/100;
							$number_national 	= -$sub_total_national*$discount['number']/100;
						} else {
							$number = -$discount['number'];
						}
					} else {
						if($discount['mode']) { 
							$number 			= $sub_total*$discount['number']/100;
							$number_national 	= $sub_total_national*$discount['number']/100;
						} else { 
							$number =  $discount['number']; 
						}
					}

					$text = '';
					$text .= ($discount['znak']?sprintf($this->language->get('text_skidka'), $discount['number'] . '%'):sprintf($this->language->get('text_nacenka'), $discount['number'] . '%'));

					if (!$check_this_method){
						$coupon_exists = false;
						if (!isset($this->session->data['coupon']) AND mb_strlen($this->session->data['coupon']) > 0){
							$coupon_exists = true;
						}

						if ($coupon_exists && $number_national < 0){
							$status = false;			
						}							
					}										

					if ($status){							
						$total_data[] = [
							'code'       		=> 'paymentmethoddiscounts',
							'title'      		=> $text,
							'sub_text' 	 		=> '',
							'text'       		=> $this->currency->format($number_national, $this->config->get('config_regional_currency'), '1'),
							'value_national'   	=> $this->currency->convert($number_national, $this->config->get('config_regional_currency'), $this->config->get('config_regional_currency')),
							'value'      		=> $number,
							'sort_order' 		=> $this->config->get('paymentmethoddiscounts_sort_order')
						];

						if ($discount['tax_class_id']) {
							$tax_rates = $this->tax->getRates($number, $discount['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if (!isset($taxes[$tax_rate['tax_rate_id']])) {
									$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
								} else {
									$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
								}
							}
						}

						$total += $number;	

						if ($check_this_method && (float)$number_national < 0){
							return $this->currency->format($number_national, $this->config->get('config_regional_currency'), '1');
						}			
					} else {
						if ($check_this_method){
							return false;
						}
					}
				}					
			}
		}	
	}
}
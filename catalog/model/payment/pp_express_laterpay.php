<?php
	class ModelPaymentPPExpressLaterPay extends Controller {			
		
		public function paymentRequestInfo($order_id) {
			$this->load->model('account/order');
			$this->load->model('localisation/country');
			
			$order_info = $this->model_account_order->getOrder($order_id);
			$order_products = $this->model_account_order->getOrderProducts($order_id);
			$order_totals = $this->model_account_order->getOrderTotals($order_id);
			$country = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
			
			$data['ADDROVERRIDE'] = true;
			$data['PAYMENTREQUEST_0_ADDROVERRIDE'] = true;
			
			$data['FIRSTNAME'] = $order_info['firstname'];
			$data['LASTNAME'] = $order_info['lastname'];
			$data['COUNTRYCODE'] = $country['iso_code_2'];
			
			$data['SHIPTONAME'] = trim($order_info['firstname'] . ' ' . $order_info['lastname']);
			$data['SHIPTOSTREET'] = trim('Заказ #' . $order_id);
			$data['SHIPTOSTREET2'] = trim($order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2']);
			$data['SHIPTOCITY'] = $order_info['shipping_city'];
			$data['SHIPTOCOUNTRYCODE'] = $country['iso_code_2'];
			$data['SHIPTOPHONENUM'] = $order_info['telephone'];
			
			$data['PAYMENTREQUEST_0_FIRSTNAME'] = $order_info['firstname'];
			$data['PAYMENTREQUEST_0_LASTNAME'] = $order_info['lastname'];
			$data['PAYMENTREQUEST_0_COUNTRYCODE'] = $country['iso_code_2'];
			
			$data['PAYMENTREQUEST_0_SHIPTONAME'] = trim($order_info['firstname'] . ' ' . $order_info['lastname']);
			$data['PAYMENTREQUEST_0_SHIPTOSTREET'] = trim('Заказ #' . $order_id);
			$data['PAYMENTREQUEST_0_SHIPTOSTREET2'] = trim($order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2']);
			$data['PAYMENTREQUEST_0_SHIPTOCITY'] = $order_info['shipping_city'];
			$data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $country['iso_code_2'];
			$data['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $order_info['telephone'];
			
			$data['PAYMENTREQUEST_0_SHIPPINGAMT'] = '';
			$data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->currency->getCode();
			$data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->config->get('pp_express_method');
			
			$i = 0;
			$item_total = 0;
			
			foreach ($order_products as $item) {
				$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';
				
				$option_count = 0;
				$__options = $this->model_account_order->getOrderOptions($order_id, $item['order_product_id']);
				foreach ($__options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];
						} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
					
					$data['L_PAYMENTREQUEST_0_DESC' . $i] .= ($option_count > 0 ? ', ' : '') . $option['name'] . ':' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value);
					
					$option_count++;
				}
				
				$data['L_PAYMENTREQUEST_0_DESC' . $i] = substr($data['L_PAYMENTREQUEST_0_DESC' . $i], 0, 126);
				
				//$item_price = $this->currency->format($item['price_national'], $this->config->get('config_regional_currency'), 1, false);
				$item_price = $item['price_national'];
				
				$data['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
				$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $item['model'];
				$data['L_PAYMENTREQUEST_0_AMT' . $i] = $item_price;
				$item_total += $item_price * $item['quantity'];
				$data['L_PAYMENTREQUEST_0_QTY' . $i] = $item['quantity'];	
				
				$data['L_PAYMENTREQUEST_0_ITEMURL' . $i] = $this->url->link('product/product', 'product_id=' . $item['product_id']);
				
				$i++;
			}
			
			$total_data = array();
			$total = 0;
			
			$dc = 0;
			
			foreach ($order_totals as $total_row) {
				if (!in_array($total_row['code'], array('total', 'sub_total', 'transfer_plus_prepayment'))) {
					if ($total_row['value_national'] != 0) {
						$item_price = $total_row['value_national'];									
						$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $total_row['code'];
						$data['L_PAYMENTREQUEST_0_NAME' . $i] = $total_row['title'];
						$data['L_PAYMENTREQUEST_0_AMT' . $i] = number_format((int)$total_row['value_national'], 2, '.', '');
						$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
						$item_total += $item_price;
						$i++;
					}
				}
			}
			
			$data['PAYMENTREQUEST_0_ITEMAMT'] = number_format($item_total, 2, '.', '');
			$data['PAYMENTREQUEST_0_AMT'] = number_format($item_total, 2, '.', '');
					
			
			return $data;
		}
	}	
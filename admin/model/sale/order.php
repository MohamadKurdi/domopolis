<?php
	class ModelSaleOrder extends Model {		
		private $marketplaces 		= ['market.yandex.ru', 'm.market.yandex.ru', 'hotline.ua', 'rozetka.com.ua'];
		private $equiring_methods 	= ['paykeeper', 'pp_express', 'liqpay', 'wayforpay', 'mono', 'concardis'];

		public function getMarketplaces(){
			return $this->marketplaces;
		}

		public function getEquiringMethods(){
			return $this->equiring_methods;
		}
					
		public function generatePaymentQR($order_id, $code = 'concardis', $currency = false){
			$paymentLink = $this->generatePaymentLink($order_id, $code, $currency);

			$_dir = DIR_EXPORT . 'payment_qr/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';

			if (!is_dir($_dir)){
				mkdir($_dir, 0775, $recursive = true);
			}

			if (!$currency){
				$currency = 'CUR';
			}							

			$qr_file = $_dir . 'payment_' . $code . '_qr_' . $order_id . $currency . '.png';
			$qr_link = HTTPS_CATALOG . DIR_EXPORT_NAME . 'payment_qr/' . date('Y') . '/' . date('m') . '/' . date('d') .'/payment_' . $code . '_qr_' . $order_id . $currency . '.png';				

			$hobotixQR = new \hobotix\QRCodeExtender;
			$hobotixQR->doQR($paymentLink, $qr_file);	

			return $qr_link;
		}

		public function generatePaymentLink($order_id, $code = 'concardis', $currency = false){
			$this->load->model('sale/customer');
			
			$order_info 	= $this->getOrder($order_id);
			$customer_info 	= $this->model_sale_customer->getCustomer($order_info['customer_id']);

			$paymentLink = rtrim($order_info['store_url'], '/') . '/index.php?route=account/order/info&order_id=' . $order_id;
			$paymentLink .= '&customer_id=' . $order_info['customer_id'] . '&utoken=' . md5($order_info['customer_id'] . $this->config->get('config_encryption'));
			$paymentLink .= '&do_payment=explicit&pay_by=' . $code;

			if ($currency && in_array($currency, array('EUR', 'RUB', 'UAH'))){
				$paymentLink .= '&cc_code=' . $currency;
			}

			return $this->shortAlias->shortenURL($paymentLink);
		}
		
		public function recountDiscountsOnProducts($order_id){
			$this->load->model('sale/return');
			$this->load->model('sale/order');
			
			$_totals = $this->model_sale_order->getOrderTotals($order_id);
			$return_filter_data = [
				'filter_order_id' => $order_id
			];

			$order_products_return = $this->model_sale_return->getReturns($return_filter_data);
			$_products = $this->model_sale_order->getOrderProducts($order_id, $with_returns = true);
		}
		
		public function getOrderProductReserves($order_product_id){
			$query = $this->db->query("SELECT * FROM order_product_reserves WHERE order_product_id = '" .(int)$order_product_id. "'");
			
			$result = array();
			foreach ($query->rows as $row){
				$result[$row['country_code']] = $row['quantity'];
			}
			
			return $result;
		}
		
		public function checkIfProductHasBirthdayDiscount($product_id, $order_id = false, $order_product_id = false){			
			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)BIRTHDAY_DISCOUNT_CATEGORY . "'");
			
			if ($query->num_rows){
				return true;
			} 
			
			if ($product_id && $order_id){
				$query = $this->db->query("SELECT from_bd_gift FROM order_product WHERE product_id = '" . (int)$product_id . "' AND order_id = '" . (int)$order_id . "' AND from_bd_gift = 1");
				
				if ($query->num_rows){
					return true;
				} 
			}
			
			if ($order_id && $order_product_id){
				$query = $this->db->query("SELECT from_bd_gift FROM order_product WHERE product_id = '" . (int)$product_id . "' AND order_id = '" . (int)$order_id . "' AND from_bd_gift = 1");
				
				if ($query->num_rows){
					return true;
				} 
			}
						
			return false;			
		}
		
		public function checkIfOrderHasBirthdayDiscount($order_id){			
			$totals = $this->getOrderTotals($order_id);
			
			foreach ($totals as $o_total){
				if ($o_total['code'] == 'discount_regular' || bool_real_stripos($o_total['title'], 'рождения')){
					return true;					
					break;
				}							
			}
			
			return false;
		}
		
		public function checkIfOrderHasBirthdayDiscountFromTotals($order_totals){
			foreach ($order_totals as $order_total) {
				if (($order_total['value_national'] < 0) && (bool_real_stripos($order_total['title'], 'рождения') && !bool_real_stripos($order_total['title'], '%') || $order_total['code'] == 'discount_regular')){
					return true;
					break;
				}
			}
			
			return false;
		}			
		
		public function getCurrentDelivery($order_id){
			
			$_status_id = $this->config->get('config_partly_delivered_status_id');
			$query = $this->db->query("SELECT COUNT(*) as total FROM `order_history` WHERE `order_id` = '" . (int)$order_id . "' AND order_status_id = '" .(int)$_status_id. "'");
			
			return (int)$query->row['total'];
			
		}
		
		public function getOrderCurrentDeliveryForClosing($order_id){
			
			$order = $this->getOrder($order_id);
			
			$_count_deliveries = 1;
			foreach ($this->getOrderProducts($order_id) as $_op){
				$_count_deliveries = max($_count_deliveries, $_op['delivery_num']);				
			}
			
			$_histories = $this->getOrderHistories($order_id);
			$_dcount = 0;
			foreach ($_histories as $_h){				
				if ($_h['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
					$_dcount++;
				}
			}
			
			if ($order['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
				return $_dcount;
				} elseif ($order['order_status_id'] == $this->config->get('config_complete_status_id')){
				return $_count_deliveries;
				} else {
				return false;
			}
			
		}
		
		public function getOrderClosingStatusCount($order_id){
			$query = $this->db->query("SELECT MAX(delivery_num) AS parties_count FROM order_product WHERE order_id = '" . (int)$order_id  ."'");
			$parties_count = $query->row['parties_count'];
			
			$query = $this->db->query("SELECT COUNT(DISTINCT order_history_id) as status_count 
			FROM order_history 
			WHERE order_status_id IN (". $this->config->get('config_partly_delivered_status_id')."," . $this->config->get('config_complete_status_id') .") AND order_id = '" . (int)$order_id  ."'");
			$status_count = $query->row['status_count'];
			
			return array(
			'parties_count' => $parties_count,
			'status_count' => $status_count,
			);
		}
		
		public function getOrderCurrentDelivery($order_id){
			
			$_count_deliveries = 1;
			foreach ($this->getOrderProducts($order_id) as $_op){
				$_count_deliveries = max($_count_deliveries, $_op['delivery_num']);				
			}
			
			$_histories = $this->getOrderHistories($order_id);
			$_dcount = 0;
			foreach ($_histories as $_h){				
				if ($_h['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
					$_dcount++;
				}
			}
			
			if ($_dcount > 0){
				//количество поставок, которое осталось
				$deliveries_left = $_count_deliveries - $_dcount;				
				//Номер поставки - это "полное количество, минус количество, которое осталось - 1"
				$_delivery_num = ($_count_deliveries - ($deliveries_left-1));
				//вдруг много лишних статусов
				if ($_delivery_num > $_count_deliveries){
					$_delivery_num = $_count_deliveries;
				}
				} else {
				$_delivery_num = 1;
			}
			
			if ($_count_deliveries == 1){
				$_delivery_num = 1;
			}
			
			
			return $_delivery_num;
			
		}
		
		public function getSumForCurrentDelivery($order_id){
			
			$_count_deliveries = 1;
			foreach ($this->model_sale_order->getOrderProducts($order_id) as $_op){
				$_count_deliveries = max($_count_deliveries, $_op['delivery_num']);				
			}
			
			$_histories = $this->getOrderHistories($order_id);
			$_dcount = 0;
			foreach ($_histories as $_h){				
				if ($_h['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
					$_dcount++;
				}
			}
			
			if ($_dcount > 0){
				//количество поставок, которое осталось
				$deliveries_left = $_count_deliveries - $_dcount;				
				//Номер поставки - это "полное количество, минус количество, которое осталось - 1"
				$_delivery_num = ($_count_deliveries - ($deliveries_left-1));
				//вдруг много лишних статусов
				if ($_delivery_num > $_count_deliveries){
					$_delivery_num = $_count_deliveries;
				}
				} else {
				$_delivery_num = 1;
			}
			
			if ($_count_deliveries == 1){
				$_delivery_num = 1;
			}
			
			$products = $this->getOrderProducts($order_id);
			
			$sub_total_for_delivery = 0;
			foreach ($products as $product){
				if ($product['delivery_num'] == $_delivery_num) {
					$sub_total_for_delivery += $product['totalwd_national'];
				}
			}
			
			return $sub_total_for_delivery;
		}
		
		public function separateOrder($order_id, $data){
			
		}
		
		function concatOrders($order_id, $data){
			
			
		}
		
		public function thisIsReorder($order_id){
			
			$query = $this->db->query("SELECT order_id FROM `return` WHERE reorder_id = '" . $order_id . "'");
			
			if ($query->row && isset($query->row['order_id']) && $query->row['order_id']){
				return $query->row['order_id'];
				} else {
				return false;
			}
			
		}
		
		public function getMaxExistentPartie($country_id){
			
			$prefixes = [220 => 'U', 176 => 'R', 109 => 'R', 20  => 'B'];
			
			$prefix = $prefixes[$country_id];
			
			$query = $this->db->query("SELECT MAX(part_num) as maxpartnum FROM order_product WHERE part_num LIKE('" . $this->db->escape($prefix) . "%')");
			
			return $query->row['maxpartnum'];			
		}
		
		public function getOrderProductLine($order_product_id){
			
			$query = $this->db->query("SELECT * FROM `order_product` WHERE order_product_id = '" . (int)$order_product_id . "' LIMIT 1");
			
			if ($query->row && isset($query->row['order_product_id']) && $query->row['order_product_id']){
				return $query->row;
				} else {
				return false;
			}			
		}
		
		public function tryToFindProductLine($data){			
			$query = $this->db->query("SELECT order_product_id FROM `order_product` WHERE order_id = '" . (int)$data['order_id'] . "' AND product_id = '" . (int)$data['product_id'] . "' LIMIT 1");
			
			if ($query->row && isset($query->row['order_product_id']) && $query->row['order_product_id']){
				return $query->row['order_product_id'];
				} else {
				return false;
			}
		}
		
		public function tryToFindProductLineExact($data){	
			$query = $this->db->query("SELECT order_product_id FROM `order_product` WHERE order_id = '" . (int)$data['order_id'] . "' AND product_id = '" . (int)$data['product_id'] . "' AND quantity = '" . (int)$data['quantity'] . "' LIMIT 1");
			
			if ($query->row && isset($query->row['order_product_id']) && $query->row['order_product_id']){
				return $query->row['order_product_id'];
				} else {
				return false;
			}
		}
		
		public function setPartiNumForProductLine($data){
			$partie = str_replace('-', $data['partie']);
			
			$this->db->query("UPDATE `order_product` SET part_num = '" . $this->db->escape($partie) . "' WHERE order_product_id = '" . (int)$data['order_product_id'] . "'");
		}
		
		public function addOrder($data) {
			$this->load->model('setting/store');
			
			$store_info = $this->model_setting_store->getStore($data['store_id']);
			
			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'];
				} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG;
			}
			
			$this->load->model('setting/setting');
			
			$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
			if (isset($setting_info['invoice_prefix'])) {
				$invoice_prefix = $setting_info['invoice_prefix'];
				} else {
				$invoice_prefix = $this->config->get('config_invoice_prefix');
			}
			
			$this->load->model('localisation/country');
			
			$this->load->model('localisation/zone');
			
			$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
			
			if ($country_info) {
				$shipping_country = $country_info['name'];
				$shipping_address_format = $country_info['address_format'];
				} else {
				$shipping_country = '';	
				$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}	
			
			$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
			
			if ($zone_info) {
				$shipping_zone = $zone_info['name'];
				} else {
				$shipping_zone = '';			
			}	
			
			$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
			
			if ($country_info) {
				$payment_country = $country_info['name'];
				$payment_address_format = $country_info['address_format'];			
				} else {
				$payment_country = '';	
				$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
			}
			
			$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
			
			if ($zone_info) {
				$payment_zone = $zone_info['name'];
				} else {
				$payment_zone = '';			
			}	
			
			$this->load->model('localisation/currency');
			
			$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$data['store_id']));
			
			if ($currency_info) {
				$currency_id = $currency_info['currency_id'];
				$currency_code = $currency_info['code'];
				$currency_value = $currency_info['value'];
				} else {
				$currency_id = 0;
				$currency_code = $this->config->get('config_currency');
				$currency_value = 1.00000;			
			}
			
			$this->db->query("INSERT INTO `order` 
			SET invoice_prefix = '" . $this->db->escape($invoice_prefix) . "',
			order_id2 = '" . $this->db->escape($data['order_id2']) . "',
			store_id = '" . (int)$data['store_id'] . "', 
			store_name = '" . $this->db->escape($store_name) . "',
			store_url = '" . $this->db->escape($store_url) . "', 
			customer_id = '" . (int)$data['customer_id'] . "',
			customer_group_id = '" . (int)$data['customer_group_id'] . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "',
			faxname = '" . $this->db->escape($data['faxname']) . "', 
			payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
			payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
			payment_company = '" . $this->db->escape($data['payment_company']) . "', 
			payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', 
			payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', 
			payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "',
			payment_address_struct = '" . $this->db->escape($data['payment_address_struct']) . "', 
			payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
			payment_city = '" . $this->db->escape($data['payment_city']) . "', 
			payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "',
			payment_country = '" . $this->db->escape($payment_country) . "', 
			payment_country_id = '" . (int)$data['payment_country_id'] . "', 
			payment_zone = '" . $this->db->escape($payment_zone) . "',
			payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
			payment_address_format = '" . $this->db->escape($payment_address_format) . "', 
			payment_method = '" . $this->db->escape($data['payment_method']) . "', 
			payment_code = '" . $this->db->escape($data['payment_code']) . "', 
			payment_secondary_method = '" . $this->db->escape($data['payment_secondary_method']) . "', 
			payment_secondary_code = '" . $this->db->escape($data['payment_secondary_code']) . "',
			shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', 
			shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "',
			shipping_passport_serie  	 =  '" . $this->db->escape($data['shipping_passport_serie']) . "',
			shipping_passport_date  	 =  '" . $this->db->escape($data['shipping_passport_date']) . "',
			shipping_passport_inn  	 	 =  '" . $this->db->escape($data['shipping_passport_inn']) . "',
			shipping_passport_given  	 =  '" . $this->db->escape($data['shipping_passport_given']) . "',			
			shipping_company = '" . $this->db->escape($data['shipping_company']) . "', 
			shipping_address_struct = '" . $this->db->escape($data['shipping_address_struct']) . "',  
			shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', 
			shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', 
			shipping_city = '" . $this->db->escape($data['shipping_city']) . "', 
			shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "',
			shipping_country = '" . $this->db->escape($shipping_country) . "', 
			shipping_country_id = '" . (int)$data['shipping_country_id'] . "', 
			shipping_zone = '" . $this->db->escape($shipping_zone) . "', 
			shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', 
			shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', 
			shipping_method = '" . $this->db->escape($data['shipping_method']) . "', 
			shipping_code = '" . $this->db->escape($data['shipping_code']) . "',
			comment = '" . $this->db->escape($data['comment']) . "', 
			order_status_id = '" . (int)$data['order_status_id'] . "', 
			affiliate_id  = '" . (int)$data['affiliate_id'] . "', 
			language_id = '" . (int)$this->config->get('config_language_id') . "', 
			currency_id = '" . (int)$currency_id . "', 
			currency_code = '" . $this->db->escape($currency_code) . "', 
			currency_value = '" . (float)$currency_value . "', 
			date_added = NOW(), 
			date_modified = NOW(),
			date_buy =  '" . $this->db->escape($data['date_buy']) . "',
			date_country =  '" . $this->db->escape($data['date_country']) . "',
			date_delivery =  '" . $this->db->escape($data['date_delivery']) . "',
			date_delivery_to =  '" . $this->db->escape($data['date_delivery_to']) . "',
			date_delivery_actual =  '" . $this->db->escape($data['date_delivery_actual']) . "',
			display_date_in_account =  '" . (int)$data['display_date_in_account'] . "',
			date_maxpay =  '" . $this->db->escape($data['date_maxpay']) . "',
			date_sent =  '" . $this->db->escape($data['date_sent']) . "', 			
			part_num = '" . $this->db->escape(trim($data['part_num'])) . "', 
			ttn = '" . $this->db->escape($data['ttn']) . "',
			bottom_text = '" . $this->db->escape($data['bottom_text']) . "'
			");
			
			$order_id = $this->db->getLastId();
			
			if (isset($data['order_product'])) {
				foreach ($data['order_product'] as $order_product) {
					$this->db->query("INSERT INTO order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', ao_id = '" . (int)$order_product['ao_id'] . "',  name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
					
					$order_product_id = $this->db->getLastId();
					
					$this->db->query("UPDATE product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
					
					if (isset($order_product['order_option'])) {
						foreach ($order_product['order_option'] as $order_option) {
							$this->db->query("INSERT INTO order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
							
							$this->db->query("UPDATE product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
						}
					}
					
					if (isset($order_product['order_download'])) {
						foreach ($order_product['order_download'] as $order_download) {
							$this->db->query("INSERT INTO order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($order_download['name']) . "', filename = '" . $this->db->escape($order_download['filename']) . "', mask = '" . $this->db->escape($order_download['mask']) . "', remaining = '" . (int)$order_download['remaining'] . "'");
						}
					}
				}
			}
			
			if (isset($data['order_voucher'])) {	
				foreach ($data['order_voucher'] as $order_voucher) {	
					$this->db->query("INSERT INTO order_voucher SET order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");
					
					$this->db->query("UPDATE voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
				}
			}
			
			// Get the total
			$total = 0;
			
			if (isset($data['order_total'])) {		
				foreach ($data['order_total'] as $order_total) {
					$this->db->query("INSERT INTO order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', text = '" . $this->db->escape($order_total['text']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
				}
				
				$total += $order_total['value'];
			}
			
			// Affiliate
			$affiliate_id = 0;
			$commission = 0;
			
			if (!empty($this->request->post['affiliate_id'])) {
				$this->load->model('sale/affiliate');
				
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);
				
				if ($affiliate_info) {
					$affiliate_id = $affiliate_info['affiliate_id']; 
					$commission = ($total / 100) * $affiliate_info['commission']; 
				}
			}
			
			// Update order total			 
			$this->db->query("UPDATE `order` SET total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE order_id = '" . (int)$order_id . "'");
			
			$this->load->model('module/affiliate');
			$this->model_module_affiliate->validate($order_id, $data, $commission);

			if ($this->config->get('config_show_profitability_in_order_list')){
				$this->registry->get('rainforestAmazon')->offersParser->PriceLogic->countOrderProfitablility($order_id);
			}
			
			return (int)$order_id;
		}
		
		public function getIfOrderClosed($order_id){			
			$check = $this->db->query("SELECT closed FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			if ($check->row['closed']){
				return true;
				} else {
				return false;
			}
		}
		
		public function editOrder($order_id, $data, $do_not_check_closed = false) {
			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$this->load->model('catalog/product');
			
			if (!$do_not_check_closed) {
				if ($this->getIfOrderClosed($order_id)){
					return $order_id;
				}
			}
			
			$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
			
			if ($country_info) {
				$shipping_country = $country_info['name'];
				$shipping_address_format = $country_info['address_format'];
				} else {
				$shipping_country = '';	
				$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}	
			
			$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
			
			if ($zone_info) {
				$shipping_zone = $zone_info['name'];
				} else {
				$shipping_zone = '';			
			}	
			
			$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
			
			if ($country_info) {
				$payment_country = $country_info['name'];
				$payment_address_format = $country_info['address_format'];			
				} else {
				$payment_country = '';	
				$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
			}
			
			$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
			
			if ($zone_info) {
				$payment_zone = $zone_info['name'];
				} else {
				$payment_zone = '';			
			}			
			
			// Restock products before subtracting the stock later on
			$order_query = $this->db->query("SELECT * FROM `order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");
			
			if ($order_query->num_rows) {
				$product_query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
				
				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");
					
					$option_query = $this->db->query("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
					
					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
			
			if (isset($data['pay_equire']) && $data['pay_equire']=='1'){
				$data['pay_equire'] = 1;
				} else {
				$data['pay_equire'] = 0;
			}
			
			if (isset($data['pay_equire2']) && $data['pay_equire2']=='1'){
				$data['pay_equire2'] = 1;
				} else {
				$data['pay_equire2'] = 0;
			}
			
			if (isset($data['pay_equirePP']) && $data['pay_equirePP']=='1'){
				$data['pay_equirePP'] = 1;
				} else {
				$data['pay_equirePP'] = 0;
			}
			
			if (isset($data['pay_equireLQP']) && $data['pay_equireLQP']=='1'){
				$data['pay_equireLQP'] = 1;
				} else {
				$data['pay_equireLQP'] = 0;
			}
			
			if (isset($data['pay_equireWPP']) && $data['pay_equireWPP']=='1'){
				$data['pay_equireWPP'] = 1;
				} else {
				$data['pay_equireWPP'] = 0;
			}

			if (isset($data['pay_equireMono']) && $data['pay_equireMono']=='1'){
				$data['pay_equireMono'] = 1;
				} else {
				$data['pay_equireMono'] = 0;
			}
			
			
			if (isset($data['pay_equireCP']) && $data['pay_equireCP']=='1'){
				$data['pay_equireCP'] = 1;
				} else {
				$data['pay_equireCP'] = 0;
			}
			
			if ($data['pay_equire'] || $data['pay_equire2'] || $data['pay_equirePP'] || $data['pay_equireLQP'] || $data['pay_equireWPP'] || $data['pay_equireMono'] || $data['pay_equireCP']){
				$data['pay_type'] = 'Банковской картой';
			}
				
			$allowed_file = ['doc','docx','xls','xlsx','txt','pdf','rtf','png','jpg','jpeg'];
			
			if (isset($_FILES['bill_file']) && isset($_FILES['bill_file']['name']) && mb_strlen($_FILES['bill_file']['name']) > 0){	
				setlocale(LC_ALL, "ru_RU.UTF-8");
				
				$log = new Log('fileupload.txt');
				$log->write(basename($_FILES['bill_file']['name']));
				
				$new_filename = '';
				
				$file_ext = array_pop(explode(".", basename($_FILES['bill_file']['name'])));
				if (in_array($file_ext, $allowed_file)){
					$new_filename = str_replace(' ','_',basename($_FILES['bill_file']['name']));
					$uploadfile = DIR_BILLS . $new_filename;			
					move_uploaded_file($_FILES['bill_file']['tmp_name'], $uploadfile);
				}						
				
				$bill_file = $new_filename;
				} else {
				$bill_file = '';
			}
			
			if (isset($_FILES['bill_file2']) && isset($_FILES['bill_file2']['name']) && mb_strlen($_FILES['bill_file2']['name']) > 0){	
				setlocale(LC_ALL, "ru_RU.UTF-8");
				
				$new_filename2 = '';
				
				$log = new Log('fileupload.txt');
				$log->write(basename($_FILES['bill_file2']['name']));
				
				$file_ext = array_pop(explode(".", basename($_FILES['bill_file2']['name'])));
				if (in_array($file_ext, $allowed_file)){
					$new_filename2 = str_replace(' ','_',basename($_FILES['bill_file2']['name']));
					$uploadfile = DIR_BILLS . $new_filename2;			
					move_uploaded_file($_FILES['bill_file2']['tmp_name'], $uploadfile);
				}
				$bill_file2 = $new_filename2;
				} else {
				$bill_file2 = '';
			}

			$data['order_status_id'] = $this->getOrderLastHistory($order_id);
			
			if (!isset($data['urgent'])){
				$data['urgent'] = 0;
			}
			
			if (!isset($data['preorder'])){
				$data['preorder'] = 0;
			}
			
			if (!isset($data['urgent_buy'])){
				$data['urgent_buy'] = 0;
			}
			
			if (!isset($data['wait_full'])){
				$data['wait_full'] = 0;
			}

			if (!isset($data['do_not_call'])){
				$data['do_not_call'] = 0;
			}
			
			if (!isset($data['ua_logistics'])){
				$data['ua_logistics'] = 0;
			}
			
			if (!isset($data['affiliate_id'])){
				$data['affiliate_id'] = '';
			}
			
			if (!isset($data['concardis_id'])){
				$data['concardis_id'] = 0;
			}
			
			$this->db->query("UPDATE `order` SET 		
			order_id2 				= '" . $this->db->escape($data['order_id2']) . "',
			firstname 				= '" . $this->db->escape(mb_ucfirst($data['firstname'])) . "', 
			lastname 				= '" . $this->db->escape(mb_ucfirst($data['lastname'])) . "', 
			email 					= '" . $this->db->escape(trim($data['email'])) . "', 
			telephone 				= '" . $this->db->escape($data['telephone']) . "',				
			fax 					= '" . $this->db->escape($data['fax']) . "', 
			faxname 				= '" . $this->db->escape($data['faxname']) . "', 
			customer_id 			= '" . (int)$data['customer_id'] . "',
			customer_group_id 		= '" . (int)$data['customer_group_id'] . "',
			payment_firstname 		= '" . $this->db->escape(mb_ucfirst($data['payment_firstname'])) . "', 
			payment_lastname 		= '" . $this->db->escape(mb_ucfirst($data['payment_lastname'])) . "', 
			payment_company 		= '" . $this->db->escape($data['payment_company']) . "', 
			payment_company_id 		= '" . $this->db->escape($data['payment_company_id']) . "', 
			payment_tax_id 			= '" . $this->db->escape($data['payment_tax_id']) . "', 
			payment_address_1 		= '" . $this->db->escape($data['payment_address_1']) . "', 
			payment_address_2 		= '" . $this->db->escape($data['payment_address_2']) . "', 
			payment_city 			= '" . $this->db->escape($data['payment_city']) . "', 
			payment_postcode 		= '" . $this->db->escape($data['payment_postcode']) . "', 
			payment_country 		= '" . $this->db->escape($payment_country) . "', 
			payment_country_id 		= '" . (int)$data['payment_country_id'] . "', 
			payment_zone 			= '" . $this->db->escape($payment_zone) . "', 
			payment_zone_id 		= '" . (int)$data['payment_zone_id'] . "', 
			payment_address_format 	= '" . $this->db->escape($payment_address_format) . "',
			payment_method 			= '" . $this->db->escape($data['payment_method']) . "', 
			payment_code 			= '" . $this->db->escape($data['payment_code']) . "', 
			payment_secondary_method 	= '" . $this->db->escape($data['payment_secondary_method']) . "', 
			payment_secondary_code 		= '" . $this->db->escape($data['payment_secondary_code']) . "',
			shipping_firstname 		= '" . $this->db->escape(mb_ucfirst($data['shipping_firstname'])) . "', 
			shipping_lastname 		= '" . $this->db->escape(mb_ucfirst($data['shipping_lastname'])) . "', 
			shipping_passport_serie  	=  '" . $this->db->escape($data['shipping_passport_serie']) . "',
			shipping_passport_date  	=  '" . $this->db->escape($data['shipping_passport_date']) . "',
			shipping_passport_inn  	 	=  '" . $this->db->escape($data['shipping_passport_inn']) . "',
			shipping_passport_given   	=  '" . $this->db->escape($data['shipping_passport_given']) . "',		
			shipping_company 			= '" . $this->db->escape($data['shipping_company']) . "', 
			shipping_address_1 		= '" . $this->db->escape($data['shipping_address_1']) . "', 
			shipping_address_2 		= '" . $this->db->escape($data['shipping_address_2']) . "',
			shipping_city 			= '" . $this->db->escape($data['shipping_city']) . "', 
			shipping_postcode 		= '" . $this->db->escape($data['shipping_postcode']) . "', 
			shipping_country 		= '" . $this->db->escape($shipping_country) . "',
			shipping_country_id 	= '" . (int)$data['shipping_country_id'] . "', 
			legalperson_id 			= '" . (int)$data['legalperson_id'] . "',
			card_id 				= '" . (int)$data['card_id'] . "', 
			shipping_zone 			= '" . $this->db->escape($shipping_zone) . "',
			shipping_zone_id 		= '" . (int)$data['shipping_zone_id'] . "', 
			shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', 
			shipping_method 		= '" . $this->db->escape($data['shipping_method']) . "', 
			shipping_code 			= '" . $this->db->escape($data['shipping_code']) . "', 
			comment 				= '" . $this->db->escape($data['comment']) . "', 
			order_status_id 		= '" . (int)$data['order_status_id'] . "', 
			affiliate_id  			= '" . (int)$data['affiliate_id'] . "', 
			date_modified 			= NOW(), 
			date_buy 				=  '" . $this->db->escape($data['date_buy']) . "',
			date_country 			=  '" . $this->db->escape($data['date_country']) . "',
			date_delivery 			=  '" . $this->db->escape($data['date_delivery']) . "',
			date_delivery_to 		=  '" . $this->db->escape($data['date_delivery_to']) . "',
			date_delivery_actual 	=  '" . $this->db->escape($data['date_delivery_actual']) . "',
			display_date_in_account =  '" . (int)$data['display_date_in_account'] . "',
			date_maxpay 			=  '" . $this->db->escape($data['date_maxpay']) . "',
			date_sent 				=  '" . $this->db->escape($data['date_sent']) . "', 			
			part_num 				= '" . $this->db->escape(trim($data['part_num'])) . "', 
			ttn 					= '" . $this->db->escape($data['ttn']) . "',
			bottom_text 			= '" . $this->db->escape($data['bottom_text']) . "',
			pay_equire 				= '" . (int)$data['pay_equire'] . "',
			pay_equire2 			= '" . (int)$data['pay_equire2'] . "',
			pay_equirePP 			= '" . (int)$data['pay_equirePP'] . "',
			pay_equireLQP 			= '" . (int)$data['pay_equireLQP'] . "',
			pay_equireWPP 			= '" . (int)$data['pay_equireWPP'] . "',
			pay_equireMono 			= '" . (int)$data['pay_equireMono'] . "',
			pay_equireCP 			= '" . (int)$data['pay_equireCP'] . "',
			pay_type 				= '" . $this->db->escape($data['pay_type']) . "',
			bill_file 				= '" . $this->db->escape($bill_file) . "',
			bill_file2 				= '" . $this->db->escape($bill_file2) . "',
			urgent   				= '" . (int)$data['urgent'] . "',
			preorder   				= '" . (int)$data['preorder'] . "',
			urgent_buy   			= '" . (int)$data['urgent_buy'] . "',
			wait_full   			= '" . (int)$data['wait_full'] . "',
			do_not_call   			= '" . (int)$data['do_not_call'] . "',
			ua_logistics   			= '" . (int)$data['ua_logistics'] . "',
			needs_checkboxua   		= '" . (int)$data['needs_checkboxua'] . "',
			concardis_id   			= '" . $this->db->escape($data['concardis_id']) . "'
			WHERE order_id 			= '" . (int)$order_id . "'");
			
			if (isset($data['customer_is_mudak']) && $data['customer_is_mudak']=='1' && $data['customer_id']){
				$mudak_group_id = $this->config->get('config_bad_customer_group_id');
				
				$this->db->query("UPDATE customer SET mudak = '1', customer_group_id = '" . (int)$mudak_group_id . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");
				$this->db->query("UPDATE `order` SET customer_group_id = '" . (int)$mudak_group_id . "' WHERE order_id = '" . (int)$order_id . "'");
				
				$this->data['customer_group_id'] = $mudak_group_id;			
				} else {
				$this->db->query("UPDATE customer SET mudak = '0', customer_group_id = '" . (int)$data['customer_group_id'] . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");
			}

			$this->customer->addToEMAQueue($data['customer_id']);
			
			$this->db->query("UPDATE `customer` SET
				passport_serie 	= '" . $this->db->escape($data['passport_serie']) . "',
				passport_date 	= '" . $this->db->escape($data['passport_date']) . "',
				passport_inn 	= '" . $this->db->escape($data['passport_inn']) . "',
				passport_given 	= '" . $this->db->escape($data['passport_given']) . "'
				WHERE customer_id = '" . (int)$data['customer_id'] . "'");
			
			if (isset($data['do_update_customer']) && $data['do_update_customer']=='1' && $data['customer_id']){
				
				$this->load->model('kp/work');
				$this->model_kp_work->updateFieldPlusOne('edit_customer_count');
				
				$this->db->query("UPDATE `customer` SET 
				firstname 			= '" . $this->db->escape($data['firstname']) . "', 
				lastname 			= '" . $this->db->escape($data['lastname']) . "',
				passport_serie 		= '" . $this->db->escape($data['passport_serie']) . "',
				passport_date 		= '" . $this->db->escape($data['passport_date']) . "',
				passport_inn 		= '" . $this->db->escape($data['passport_inn']) . "',
				passport_given 		= '" . $this->db->escape($data['passport_given']) . "',
				birthday 			= '" . $this->db->escape(date('Y-m-d', strtotime($data['birthday']))) . "',
				customer_group_id 	= '" . (int)$data['customer_group_id'] . "',
				email 				= '" . $this->db->escape($data['email']) . "', 
				telephone 			= '" . $this->db->escape($data['telephone']) . "',
				fax 				= '" . $this->db->escape($data['fax']) . "'				
				WHERE customer_id = '" . (int)$data['customer_id'] . "'");
				
				$simple_data = array(
				'custom_birthday' => $this->db->escape(date('Y-m-d',strtotime($data['birthday']))),
				'custom_passport_serie' => $this->db->escape($data['passport_serie']),
				'custom_passport_date' 	=> $this->db->escape($data['passport_date']),
				'custom_passport_inn' 	=> $this->db->escape($data['passport_inn']),
				'custom_passport_given' => $this->db->escape($data['passport_given'])
				);
				
				$this->load->model('tool/simplecustom');
				$this->model_tool_simplecustom->updateData('customer', $data['customer_id'], 'customer', $simple_data);
				
				$this->db->query("UPDATE `order` SET 
				payment_firstname = '" . $this->db->escape($data['firstname']) . "', 
				payment_lastname = '" . $this->db->escape($data['lastname']) . "',
				shipping_passport_serie = '" . $this->db->escape($data['passport_serie']) . "',
				shipping_passport_date  =  '" . $this->db->escape($data['shipping_passport_date']) . "',
				shipping_passport_inn  	=  '" . $this->db->escape($data['shipping_passport_inn']) . "',
				shipping_passport_given = '" . $this->db->escape($data['passport_given']) . "'
				WHERE order_id = '" . (int)$order_id . "'");
				
				$address_query = $this->db->query("SELECT address_id FROM `customer` WHERE customer_id = '" . (int)$data['customer_id'] . "' LIMIT 1");
				$address_id = $address_query->row['address_id'];
				
				if ($address_id > 0){
					$this->db->query("UPDATE `address` SET 
						firstname = '" . $this->db->escape($data['firstname']) . "', 
						lastname = '" . $this->db->escape($data['lastname']) . "', 
						company = '" . $this->db->escape($data['payment_company']) . "',
						address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
						address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
						city = '" . $this->db->escape($data['payment_city']) . "', 
						postcode = '" . $this->db->escape($data['payment_postcode']) . "',
						country_id = '" . (int)$data['payment_country_id'] . "',
						zone_id = '" . (int)$data['payment_zone_id'] . "' 
						WHERE address_id = '" . (int)$address_id . "'");
				} else {
					$this->db->query("INSERT INTO `address` SET 
						firstname = '" . $this->db->escape($data['firstname']) . "', 
						lastname = '" . $this->db->escape($data['lastname']) . "', 
						company = '" . $this->db->escape($data['payment_company']) . "',
						address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
						address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
						city = '" . $this->db->escape($data['payment_city']) . "', 
						postcode = '" . $this->db->escape($data['payment_postcode']) . "',
						country_id = '" . (int)$data['payment_country_id'] . "',
						zone_id = '" . (int)$data['payment_zone_id'] . "',
						customer_id = '" . (int)$data['customer_id'] . "'"
					);
					$address_id = $this->db->getLastId();
					
					$this->db->query("UPDATE `customer` SET 
						address_id = '" . (int)$address_id . "' 		
						WHERE customer_id = '" . (int)$data['customer_id'] . "'");
				}
			}
			
			$product_query_for_original_overload = $this->db->query("SELECT order_product_id, original_price_national FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			$original_prices = array();
			foreach ($product_query_for_original_overload->rows as $_pqfoo){
				$original_prices[$_pqfoo['order_product_id']] = $_pqfoo['original_price_national'];
			}
			
			$this->db->query("DELETE FROM order_product WHERE order_id = '" . (int)$order_id . "'"); 
			$this->db->query("DELETE FROM order_option WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM order_download WHERE order_id = '" . (int)$order_id . "'");
			
			//updating nogood products
			if (isset($data['order_product_nogood'])) {
				foreach ($data['order_product_nogood'] as $order_product_nogood) {
					if (isset($order_product_nogood['waitlist']) && $order_product_nogood['waitlist']==1){
						$this->db->query("UPDATE order_product_nogood SET waitlist=1 WHERE order_product_id = '".(int)$order_product_nogood['order_product_id']."'");
						} else {
						$this->db->query("UPDATE order_product_nogood SET waitlist=0 WHERE order_product_id = '".(int)$order_product_nogood['order_product_id']."'");
					}	 							
				}			
			}
			
			$this->load->model('setting/setting');
			$main_currency = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', (int)$data['store_id']);
						
			if (isset($data['order_product'])) {
				$sub_total = 0;
				$just_product_ids = array();
				
				foreach ($data['order_product'] as $order_product) {
					
					$just_product_ids[] = (int)$order_product['product_id'];

					if ($order_product['price_national'] != ''){				
						$order_product['price'] = $this->currency->convert($order_product['price_national'], $data['currency_code'], $main_currency);				
					} else {
						$order_product['price_national'] = 	$this->currency->convert($order_product['price'], $main_currency, $data['currency_code']);
					}

					if ($order_product['total_national'] != ''){	
						$order_product['total'] = $this->currency->convert($order_product['total_national'], $data['currency_code'], $main_currency);
					} else {
						$order_product['total_national'] = 	$this->currency->convert($order_product['total'], $main_currency, $data['currency_code']);
					}

					$order_product['total'] 			= $order_product['price'] * $order_product['quantity'];
					$order_product['total_national'] 	= $order_product['price_national'] * $order_product['quantity'];

					$sub_total += $order_product['total_national'];

					$_name = str_replace(array('"', "'", "&quot;", "&quot"),array("","","",""),$order_product['name']);

					if (isset($original_prices[(int)$order_product['order_product_id']])){
						$order_product['original_price_national'] = $original_prices[(int)$order_product['order_product_id']];
					}				

					if (!isset($order_product['original_price_national']) || (int)$order_product['original_price_national'] == 0 || !$order_product['original_price_national']){
						$order_product['original_price_national'] = $order_product['price_national'];
					}

					$check_stock_query = $this->db->query("SELECT stock_product_id FROM product WHERE product_id = '" . (int)$order_product['product_id'] . "'");
					if ($check_stock_query->row && isset($check_stock_query->row['stock_product_id']) && $check_stock_query->row['stock_product_id'] > 0){
						$real_product_id = $check_stock_query->row['stock_product_id'];
					} else {
						$real_product_id = (int)$order_product['product_id'];
					}

					if ($order_product['source']) {
						$sources = array();
						$source_query = $this->db->query("SELECT source FROM product WHERE product_id = '" . (int)$real_product_id . "' LIMIT 1");
						$sources = explode(PHP_EOL, $source_query->row['source']);
						$sources[] = $order_product['source'];
						foreach ($sources as &$source){
							$source = trim($source);
						}
						$sources = array_unique($sources, SORT_STRING);
						$sources = implode(PHP_EOL, $sources);
						$this->db->query("UPDATE product SET source = '" . $this->db->escape($sources) . "' WHERE product_id = '" . (int)$real_product_id . "'");
					}

						//Если на задано количество бонусов на один товар, то мы его пересчитаем
						//Если же задано, то у нас реверсивная логика, на случай изменения
					if (!empty($order_product['reward_one'])){
						$order_product['reward'] = $order_product['reward_one'] * $order_product['quantity'];
					} else {
						$order_product['reward_one'] = (int)($order_product['reward'] / $order_product['quantity']);
					}

					$this->db->query("INSERT INTO order_product SET
						order_product_id 	= '" . (int)$order_product['order_product_id'] . "', 
						order_id 			= '" . (int)$order_id . "', 
						product_id 			= '" . (int)$real_product_id . "', 
						ao_id 				= '" . (int)$order_product['ao_id'] . "', 
						ao_product_id 		= '" . (int)$order_product['ao_product_id'] . "',
						name 				= '" . $this->db->escape($_name) . "', 
						model 				= '" . $this->db->escape($order_product['model']) . "', 
						source 				= '" . $this->db->escape($order_product['source']) . "',  
						quantity 			= '" . (int)$order_product['quantity'] . "', 
						delivery_num 		= '" . (int)$order_product['delivery_num'] . "',
						part_num 			= '" . $this->db->escape(trim($order_product['part_num'])) . "', 					
						price 				= '" . (float)$order_product['price'] . "', 
						price_national 		= '" . (float)$order_product['price_national'] . "',
						original_price_national = '" . (float)$order_product['original_price_national'] . "',
						pricewd_national 		= '" . (float)$order_product['pricewd_national'] . "',
						from_stock 				= '" . (int)$order_product['from_stock'] . "',
						from_bd_gift 			= '" . (int)$order_product['from_bd_gift'] . "',
						total 					= '" . (float)$order_product['total'] . "',
						total_national 			= '" . (float)$order_product['total_national'] . "',
						totalwd_national 		= '" . (float)$order_product['totalwd_national'] . "',
						amazon_offers_type 		= '" . $this->db->escape(trim($order_product['amazon_offers_type'])) . "',
						tax 				= '" . (float)$order_product['tax'] . "', 
						reward 				= '" . (int)$order_product['reward'] . "',
						reward_one 			= '" . (int)$order_product['reward_one'] . "', 
						good 				= '" . (int)$order_product['good'] . "', 
						taken 				= '" . (int)$order_product['taken'] . "'");

					$order_product_id = $this->db->getLastId();

					$this->db->query("UPDATE product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");	

					$this->model_catalog_product->syncProductNamesFromOrders($real_product_id, $data['language_id'], $order_product['name']);

					if (isset($order_product['order_option'])) {
						foreach ($order_product['order_option'] as $order_option) {
							$this->db->query("INSERT INTO order_option SET order_option_id = '" . (int)$order_option['order_option_id'] . "', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");


							$this->db->query("UPDATE product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
						}
					}

					if (isset($order_product['order_download'])) {
						foreach ($order_product['order_download'] as $order_download) {
							$this->db->query("INSERT INTO order_download SET order_download_id = '" . (int)$order_download['order_download_id'] . "', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($order_download['name']) . "', filename = '" . $this->db->escape($order_download['filename']) . "', mask = '" . $this->db->escape($order_download['mask']) . "', remaining = '" . (int)$order_download['remaining'] . "'");
						}
					}
				}
			}
			
			$this->db->query("DELETE FROM order_set WHERE set_product_id NOT IN (SELECT product_id FROM order_product WHERE order_id = '" . (int)$order_id . "') AND (set_product_id NOT IN (SELECT product_id FROM order_product_nogood WHERE order_id = '" . (int)$order_id . "')) AND order_id = '" . (int)$order_id . "'"); 
			
			$this->db->query("DELETE FROM order_voucher WHERE order_id = '" . (int)$order_id . "'"); 
			
			if (isset($data['order_voucher'])) {	
				foreach ($data['order_voucher'] as $order_voucher) {	
					$this->db->query("INSERT INTO order_voucher SET order_voucher_id = '" . (int)$order_voucher['order_voucher_id'] . "', order_id = '" . (int)$order_id . "', voucher_id = '" . (int)$order_voucher['voucher_id'] . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)$order_voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . (float)$order_voucher['amount'] . "'");
					
					$this->db->query("UPDATE voucher SET order_id = '" . (int)$order_id . "' WHERE voucher_id = '" . (int)$order_voucher['voucher_id'] . "'");
				}
			}
			
			
			//SUPPLIES
			$this->db->query("DELETE FROM order_product_supply WHERE order_id = '" . (int)$order_id . "'");
			
			if (isset($data['order_product_supply']) && is_array($data['order_product_supply'])){
				
				
				foreach ($data['order_product_supply'] as $key => $value){
					
					$count_of_this = count($value['supplier_id']);
					for ($i=0; $i<$count_of_this; $i++){
						
						if ($value['supplier_id'][$i] > 0) {
							
							if (isset($value['order_product_supply_id'][$i]) && $value['order_product_supply_id'][$i] > 0) {
								
								$this->db->query("INSERT INTO order_product_supply SET 
								order_product_supply_id = '" . (int)$value['order_product_supply_id'][$i] . "',
								order_id = '" . (int)$order_id . "',
								order_product_id = '" . (int)$key . "',							
								product_id = '" . (int)$value['product_id'] . "',
								is_for_order = '" . (bool)$value['is_for_order'][$i] . "',
								supplier_id = '" . (int)$value['supplier_id'][$i] . "',
								price = '" . $this->db->escape($value['price'][$i]) . "',
								amount = '" . $this->db->escape($value['amount'][$i]) . "',
								currency = '" . $this->db->escape($value['currency'][$i]) . "',
								url = '" . $this->db->escape($value['url'][$i]) . "',
								comment = '" . $this->db->escape($value['comment'][$i]) . "'
								");
								} else {
								
								$this->db->query("INSERT INTO order_product_supply SET 								
								order_id = '" . (int)$order_id . "',
								order_product_id = '" . (int)$key . "',							
								product_id = '" . (int)$value['product_id'] . "',
								is_for_order = '" . (bool)$value['is_for_order'][$i] . "',
								supplier_id = '" . (int)$value['supplier_id'][$i] . "',
								price = '" . $this->db->escape($value['price'][$i]) . "',
								amount = '" . $this->db->escape($value['amount'][$i]) . "',
								currency = '" . $this->db->escape($value['currency'][$i]) . "',
								url = '" . $this->db->escape($value['url'][$i]) . "',
								comment = '" . $this->db->escape($value['comment'][$i]) . "'
								");
								
								
							}
							
						}
					}
				}
			}
			
			
			if (isset($data['order_set_supply']) && is_array($data['order_set_supply'])){
				
				foreach ($data['order_set_supply'] as $key => $value){
					
					$count_of_this = count($value['supplier_id']);
					for ($i=0; $i<$count_of_this; $i++){
						
						if ($value['supplier_id'][$i] > 0) {
							
							if (false && isset($value['order_product_supply_id'][$i]) && $value['order_product_supply_id'][$i] > 0) {
								
								$this->db->query("INSERT INTO order_product_supply SET 
								order_product_supply_id = '" . (int)$value['order_product_supply_id'][$i] . "',
								order_id = '" . (int)$order_id . "',
								order_set_id = '" . (int)$key . "',
								order_product_id = '0',									
								product_id = '" . (int)$value['product_id'] . "',
								is_for_order = '" . (bool)$value['is_for_order'][$i] . "',
								set_id = '" . (int)$value['set_id'] . "',								
								supplier_id = '" . (int)$value['supplier_id'][$i] . "',
								price = '" . $this->db->escape($value['price'][$i]) . "',
								amount = '" . $this->db->escape($value['amount'][$i]) . "',
								currency = '" . $this->db->escape($value['currency'][$i]) . "',
								url = '" . $this->db->escape($value['url'][$i]) . "',
								comment = '" . $this->db->escape($value['comment'][$i]) . "'
								");
								} else {
								$this->db->query("INSERT INTO order_product_supply SET 								
								order_id = '" . (int)$order_id . "',
								order_set_id = '" . (int)$key . "',
								order_product_id = '0',									
								product_id = '" . (int)$value['product_id'] . "',
								is_for_order = '" . (bool)$value['is_for_order'][$i] . "',
								set_id = '" . (int)$value['set_id'] . "',								
								supplier_id = '" . (int)$value['supplier_id'][$i] . "',
								price = '" . $this->db->escape($value['price'][$i]) . "',
								amount = '" . $this->db->escape($value['amount'][$i]) . "',
								currency = '" . $this->db->escape($value['currency'][$i]) . "',
								url = '" . $this->db->escape($value['url'][$i]) . "',
								comment = '" . $this->db->escape($value['comment'][$i]) . "'
								");
							}
						}
					}
				}			
			}
			
			// Get the total
			$total = 0;
			$total_national = 0;
			
			$this->db->query("DELETE FROM order_total WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($data['order_product'] as &$order_product) {
				$order_product['pricewd_national'] = $order_product['price_national'];					
				$order_product['totalwd_national'] = $order_product['total_national'];
			}
			unset($order_product);
			
			$totals_json = array();				
			foreach ($data['order_product'] as &$order_product) {
				$totals_json[$order_product['order_product_id']] = array();
			}
			unset($order_product);
			
			$has_birthday_discount_explicit = $this->checkIfOrderHasBirthdayDiscountFromTotals($data['order_total']);
			if (isset($data['order_total'])) {
				
				foreach ($data['order_total'] as $order_total) {
					
					if (!isset($order_total['for_delivery'])){
						$order_total['for_delivery'] = 0;
					}
					
					if ($order_total['code']  !=  'total' && $order_total['code']  != 'transfer_plus_prepayment') {
						//подсчет стоимости товаров	
						if ($order_total['code']  ==  'sub_total'){
							$order_total['value_national'] = $sub_total;	
							$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
							$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
							} elseif ($order_total['code']  ==  'shipping') {
							$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);	
							$order_total['text'] = $order_total['text'];
							//Это накопительная скидка! Исключаются товары определенных брендов
							} elseif ($order_total['code']  ==  'shoputils_cumulative_discounts' || bool_real_stripos($order_total['title'], 'накопительная')) {
							$this->load->model('total/scd_recount');	
							$this->load->model('catalog/product');											
							
							$scd_discount = $this->load->model_total_scd_recount->getCustomerDiscount($data['store_id'], $data['customer_group_id'], $data['language_id'], $data['customer_id']);
							
							$_scd_discount_percent = (int)preg_replace('~[^0-9]+~','', $order_total['title']);				
							if (is_numeric($_scd_discount_percent) && ($scd_discount['percent'] != $_scd_discount_percent)) {							
								$scd_discount['percent'] = $_scd_discount_percent;						
							}
							
							if (isset($scd_discount['excluded_manufacturers'])){
								$excluded_manufacturers = explode(',', str_replace(',,',',',mb_substr($scd_discount['excluded_manufacturers'],0,-1)));
								} else {
								$excluded_manufacturers = array();
							}
							
							$p_sub_total = 0;
							$p_sub_total_national = 0;
							//пересчет по товарам
							unset($order_product);
							foreach ($data['order_product'] as &$order_product) {
								
								$real_product = $this->model_catalog_product->getProduct($order_product['product_id']);	
								$manufacturer_id = $real_product['manufacturer_id'];
								
								if (!in_array($manufacturer_id, $excluded_manufacturers)){
									
									//если задано что скидка считается только для конкретной поставки и не равна нулю
									if ($order_total['for_delivery']){
										
										if ($order_product['delivery_num'] == $order_total['for_delivery']){
											$p_sub_total += $order_product['total'];
											$p_sub_total_national += $order_product['total_national'];
											
											//pricewd_national
											$order_product['pricewd_national'] = $order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($scd_discount['percent'] / 100));
											$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];		
											
											$totals_json[$order_product['order_product_id']][] = array(
											'code' => $order_total['code'],
											'title' => $order_total['title'],
											'for_delivery' => $order_total['for_delivery'],
											'discount' => (float)($order_product['price_national'] * ($scd_discount['percent'] / 100)),
											'discount_total' => (float)($order_product['price_national'] * ($scd_discount['percent'] / 100)) * $order_product['quantity']
											);
										}
										
										} else {
										//безусловное добавление	
										$p_sub_total += $order_product['total'];
										$p_sub_total_national += $order_product['total_national'];
										
										//pricewd_national
										$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($scd_discount['percent'] / 100));
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($order_product['price_national'] * ($scd_discount['percent'] / 100)),
										'discount_total' => (float)($order_product['price_national'] * ($scd_discount['percent'] / 100)) * $order_product['quantity']
										);
									}
									
								}
								
								$order_total['value_national'] = (float)round(-1 * $p_sub_total_national * ($scd_discount['percent'] / 100));							
								$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
								$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
								$order_total['code']  = $order_total['code'];
							}
							
							//Это скидка ко дню рождения чпокупателя
							} elseif (($order_total['value_national'] < 0) && ((bool_real_stripos($order_total['title'], 'рождения') && bool_real_stripos($order_total['title'], '%')) || $order_total['code'] == 'discount_regular')){
							
							$has_birthday_discount_explicit = true;
							$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);
							
							if ($percent_of_discount) {
								if ($order_total['for_delivery']){
									
									$d_sub_total = 0;
									$d_sub_total_national = 0;
									
									unset($order_product);
									foreach ($data['order_product'] as &$order_product) {
										if ($this->checkIfProductHasBirthdayDiscount($order_product['product_id'], $order_id, $order_product['order_product_id'])){
											if ($order_product['delivery_num'] == $order_total['for_delivery']){
												
												$d_sub_total += $order_product['total'];
												$d_sub_total_national += $order_product['total_national'];
												
												//pricewd_national
												$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
												$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
												
												$totals_json[$order_product['order_product_id']][] = array(
												'code' => $order_total['code'],
												'title' => $order_total['title'],
												'for_delivery' => $order_total['for_delivery'],
												'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
												'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
												);
												
											}
										}
									}
									
									$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);
									if ($percent_of_discount) {
										$amount_of_discount = (float)round((-1 * $d_sub_total_national/100*$percent_of_discount));
									}						
									
									} else {
									
									foreach ($data['order_product'] as &$order_product) {
										if ($this->checkIfProductHasBirthdayDiscount($order_product['product_id'], $order_id, $order_product['order_product_id'])){
											
											$d_sub_total += $order_product['total'];
											$d_sub_total_national += $order_product['total_national'];
											
											//pricewd_national
											$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
											$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
											
											$totals_json[$order_product['order_product_id']][] = array(
											'code' => $order_total['code'],
											'title' => $order_total['title'],
											'for_delivery' => $order_total['for_delivery'],
											'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
											'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
											);
										}
									}
									
									$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);
									if ($percent_of_discount) {
										$amount_of_discount = (float)round((-1 * $d_sub_total_national/100*$percent_of_discount));
									}	
									
								}								
								
								$order_total['value_national'] = $amount_of_discount;
								$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
								$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
								$order_total['code']  = $order_total['code'];								
								
							}
							
							//Это целочисленная скидка, мать его еби!											
							} elseif (($order_total['value_national'] < 0) && bool_real_stripos($order_total['title'], 'Скидка') && !bool_real_stripos($order_total['title'], '%')){
							
							//пересчитываем процентную скидку в случае если она считается ТОЛЬКО на поставку номер N													
							if ($order_total['for_delivery']){
								
								$d_sub_total = 0;
								$d_sub_total_national = 0;
								
								unset($order_product);
								foreach ($data['order_product'] as $order_product) {
									if ($order_product['delivery_num'] == $order_total['for_delivery']){										
										$d_sub_total += $order_product['total'];
										$d_sub_total_national += $order_product['total_national'];																				
									}
								}
								
								$percent_of_discount = abs($order_total['value_national']/$d_sub_total_national*100);
								
								unset($order_product);
								foreach ($data['order_product'] as &$order_product) {
									//pricewd_national
									if ($order_product['delivery_num'] == $order_total['for_delivery']){	
										$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];																	
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
										'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
										);
									}
								}
								
								if ($percent_of_discount) {
									$amount_of_discount = (float)(-1 * $d_sub_total_national/100*$percent_of_discount);
								}						
								
								} else {
								
								$percent_of_discount = abs($order_total['value_national']/$sub_total*100);
								if ($percent_of_discount) {
									$amount_of_discount = (float)(-1 * $sub_total/100*$percent_of_discount);																	
									
									unset($order_product);
									foreach ($data['order_product'] as &$order_product) {										
										//pricewd_national
										$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
										'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
										);
									}
								}
								
							}
							
							//$order_total['value_national'] = $amount_of_discount;
							$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
							$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
							$order_total['code']  = $order_total['code'];
							
							
							//Это бонусы
							} elseif (($order_total['value_national'] < 0) && (bool_real_stripos($order_total['title'], 'Бонус') || $order_total['code'] == 'reward')) {
							
							$max_points_to_use = (int)($sub_total * ($this->model_setting_setting->getKeySettingValue('config', 'config_reward_maxsalepercent', (int)$data['store_id']) / 100));
							
							$this->load->model('sale/customer');
							$max_points_customer_has = $this->customer->getRewardTotal($data['customer_id']) + $this->customer->getRewardReservedByOrder($data['customer_id'], $order_id);							
							
							//И нужно проверить резерв в текущем заказе
							$points_in_current_order = $this->customer->getRewardReservedByOrder($data['customer_id'], $order_id);
							
							$max_points_to_use = min($max_points_to_use, $max_points_customer_has);
							
							if (abs($order_total['value_national']) > $max_points_to_use){
								$order_total['value_national'] = -1 * $max_points_to_use;
							}
							
							$percent_of_discount = abs($order_total['value_national']/$sub_total*100);
							if ($percent_of_discount) {
								$amount_of_discount = (float)(-1 * $sub_total/100*$percent_of_discount);																	
								
								unset($order_product);
								foreach ($data['order_product'] as &$order_product) {										
									//pricewd_national
									$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
									$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
									
									$totals_json[$order_product['order_product_id']][] = array(
									'code' => $order_total['code'],
									'title' => $order_total['title'],
									'for_delivery' => $order_total['for_delivery'],
									'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
									'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
									);
								}
							}
							
							//$order_total['value_national'] = $amount_of_discount;
							$order_total['title'] = sprintf($this->language->getCatalogLanguageString($data['language_id'], 'total/reward', 'text_reward'), abs($order_total['value_national']));
							$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
							$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
							$order_total['code']  = $order_total['code'];
							
							//Добавление списания по заказу
							$this->customer->addReward($data['customer_id'], sprintf($this->language->getCatalogLanguageString($data['language_id'], 'total/reward', 'text_reward_description'), $order_id), $order_total['value_national'], $order_id, 'ORDER_PAYMENT');
							
							$order_has_reward = true;
							
							} elseif (($order_total['value_national'] < 0) && bool_real_stripos($order_total['title'], 'Скидка') && bool_real_stripos($order_total['title'], '%')){					
							
							//пересчитываем процентную скидку в случае если она считается ТОЛЬКО на поставку номер N
							$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);
							
							if ($order_total['for_delivery']){
								
								$d_sub_total = 0;
								$d_sub_total_national = 0;
								
								unset($order_product);
								foreach ($data['order_product'] as &$order_product) {
									if ($order_product['delivery_num'] == $order_total['for_delivery']){
										
										$d_sub_total += $order_product['total'];
										$d_sub_total_national += $order_product['total_national'];
										
										//pricewd_national
										$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
										'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
										);
										
									}
								}
								
								$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);
								if ($percent_of_discount) {
									$amount_of_discount = (float)round((-1 * $d_sub_total_national/100*$percent_of_discount));
								}						
								
								} else {
								
								$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);
								if ($percent_of_discount) {
									$amount_of_discount = (float)round((-1 * $sub_total/100*$percent_of_discount));
									
									foreach ($data['order_product'] as &$order_product) {
										
										//pricewd_national
										$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
										'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
										);
									}
								}
								
							}													
							
							$order_total['value_national'] = $amount_of_discount;
							$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
							$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
							$order_total['code']  = $order_total['code'];
							
							//Наценка на оплату
							} elseif (($order_total['value_national'] >= 0) && (bool_real_stripos($order_total['title'], 'Комиссия') || $order_total['code']  ==  'paymentmethoddiscounts')) {
							
							$percent_of_discount = (int)preg_replace('~[^0-9]+~','', $order_total['title']);							
							
							if ($percent_of_discount) {
								$amount_of_discount = (float)round(($sub_total/100*$percent_of_discount));
								
								foreach ($data['order_product'] as &$order_product) {
									
									//pricewd_national
									$order_product['pricewd_national'] += 1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
									$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
									
									$totals_json[$order_product['order_product_id']][] = array(
									'code' => $order_total['code'],
									'title' => $order_total['title'],
									'for_delivery' => $order_total['for_delivery'],
									'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
									'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
									);
								}
								
								$order_total['value_national'] = $amount_of_discount;
								$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);
								$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');
								$order_total['code']  = $order_total['code'];
							}
							
							} elseif (($order_total['value_national'] <= 0) && bool_real_stripos($order_total['title'], 'Промокод') && $order_total['code']  ==  'coupon'){
							
							$coupon_name = $this->getCouponName($order_total['title']);
							$coupon_query = $this->db->query("SELECT * FROM `coupon` WHERE code = '" . $this->db->escape($coupon_name) . "'");
							
							if ($coupon_query->num_rows && ($coupon_query->row['type'] == "P")){																					
								//needs to be recounted
								$active_coupon_products = $this->getCouponProducts($coupon_name, $just_product_ids, $order_id, $has_birthday_discount_explicit);
								
								$coupon_discount = 0;
								unset($order_product);
								foreach ($data['order_product'] as &$order_product){
									
									if (in_array($order_product['product_id'], $active_coupon_products)){
										$coupon_discount += ($order_product['total_national'] / 100 * $coupon_query->row['discount']);		
										
										//pricewd_national
										$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($coupon_query->row['discount'] / 100));
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($order_product['price_national'] * ($coupon_query->row['discount'] / 100)),
										'discount_total' => (float)($order_product['price_national'] * ($coupon_query->row['discount'] / 100)) * $order_product['quantity']
										);
										
									}
									
								}
								
								$coupon_discount = -1 * round($coupon_discount);
								
								$order_total['value_national'] = $coupon_discount;
								$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);	
								$order_total['text'] = $this->currency->format($coupon_discount, $data['currency_code'], '1');
								
								//re-redeem coupon
								$this->db->query("INSERT IGNORE INTO coupon_history SET
								coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "',
								order_id = '" . (int)$order_id . "',
								customer_id = '" . (int)$data['customer_id'] . "',
								amount = '" . (float)$order_total['value'] . "',
								date_added = NOW()
								ON DUPLICATE KEY UPDATE
								customer_id = '" . (int)$data['customer_id'] . "'");
								
								} elseif ($coupon_query->num_rows && ($coupon_query->row['type'] == "F")) {
								
								//пересчитываем процентную скидку в случае если она считается ТОЛЬКО на поставку номер N													
								if ($order_total['for_delivery']){
									
									$d_sub_total = 0;
									$d_sub_total_national = 0;
									
									unset($order_product);
									foreach ($data['order_product'] as $order_product) {
										if ($order_product['delivery_num'] == $order_total['for_delivery']){										
											$d_sub_total += $order_product['total'];
											$d_sub_total_national += $order_product['total_national'];																				
										}
									}
									
									$percent_of_discount = abs($order_total['value_national']/$d_sub_total_national*100);
									
									unset($order_product);
									foreach ($data['order_product'] as &$order_product) {
										//pricewd_national
										if ($order_product['delivery_num'] == $order_total['for_delivery']){
											$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
											$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
											
											
											$totals_json[$order_product['order_product_id']][] = array(
											'code' => $order_total['code'],
											'title' => $order_total['title'],
											'for_delivery' => $order_total['for_delivery'],
											'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
											'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
											);
										}
									}
									
									if ($percent_of_discount) {
										$amount_of_discount = (float)(-1 * $d_sub_total_national/100*$percent_of_discount);
									}						
									
									} else {
									
									$percent_of_discount = abs($order_total['value_national']/$sub_total*100);
									if ($percent_of_discount) {
										$amount_of_discount = (float)(-1 * $sub_total/100*$percent_of_discount);																	
										
										foreach ($data['order_product'] as &$order_product) {										
											//pricewd_national
											$order_product['pricewd_national'] += -1 * (float)($order_product['price_national'] * ($percent_of_discount / 100));
											$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
											
											$totals_json[$order_product['order_product_id']][] = array(
											'code' => $order_total['code'],
											'title' => $order_total['title'],
											'for_delivery' => $order_total['for_delivery'],
											'discount' => (float)($order_product['price_national'] * ($percent_of_discount / 100)),
											'discount_total' => (float)($order_product['price_national'] * ($percent_of_discount / 100)) * $order_product['quantity']
											);
										}
									}
									
								}
								
								
								//re-redeem coupon
								$this->db->query("INSERT IGNORE INTO coupon_history SET
								coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "',
								order_id = '" . (int)$order_id . "',
								customer_id = '" . (int)$data['customer_id'] . "',
								amount = '" . (float)$order_total['value'] . "',
								date_added = NOW()
								ON DUPLICATE KEY UPDATE
								customer_id = '" . (int)$data['customer_id'] . "'");
								
								
								} elseif ($coupon_query->num_rows && ($coupon_query->row['type'] == "3")) { 
								
								$active_coupon_products = $this->getCouponProducts($coupon_name, $just_product_ids, $order_id, $has_birthday_discount_explicit);
								
								$coupon_discount = 0;
								$coupon_discount_national = 0;
								$coupon_discounted_order_product_id = 0;
								unset($order_product);
								foreach ($data['order_product'] as &$order_product){																		
									//Ищем минимальную цену из всех на кого действует промокод
									if (in_array($order_product['product_id'], $active_coupon_products)){
										
										if ($order_product['price'] <= $coupon_discount){
											$coupon_discount 		  			= $order_product['price'];
											$coupon_discount_national 			= $order_product['price_national'];
											$coupon_discounted_order_product_id = $order_product['order_product_id'];
										}
										
										if ($coupon_discount == 0){
											$coupon_discount 					= $order_product['price'];
											$coupon_discount_national 			= $order_product['price_national'];
											$coupon_discounted_order_product_id = $order_product['order_product_id'];
										}
									}
								}
								
								
								
								unset($order_product);
								foreach ($data['order_product'] as &$order_product){
									if ($order_product['order_product_id'] == $coupon_discounted_order_product_id){
										
										$order_product['pricewd_national'] = $order_product['price_national'] - ($coupon_discount_national / $order_product['quantity']);
										$order_product['totalwd_national'] = $order_product['pricewd_national'] * $order_product['quantity'];
										
										
										$totals_json[$order_product['order_product_id']][] = array(
										'code' => $order_total['code'],
										'title' => $order_total['title'],
										'for_delivery' => $order_total['for_delivery'],
										'discount' => (float)($coupon_discount_national / $order_product['quantity']),
										'discount_total' => (float)($coupon_discount_national / $order_product['quantity']) * $order_product['quantity']
										);
									}												
								}
								
								//re-redeem coupon
								$this->db->query("INSERT IGNORE INTO coupon_history SET
								coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "',
								order_id = '" . (int)$order_id . "',
								customer_id = '" . (int)$data['customer_id'] . "',
								amount = '" . (float)$order_total['value'] . "',
								date_added = NOW()
								ON DUPLICATE KEY UPDATE
								customer_id = '" . (int)$data['customer_id'] . "'");
								
								
								} else {
								
								if ($order_total['value_national'] != 0){
									
									$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);	
									$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');												
									} else {						
									$order_total['value_national'] = $this->currency->convert($order_total['value'], $main_currency,  $data['currency_code']);
									$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');						
								}		
							}
							
							} else {
							if ($order_total['value_national'] != 0){
								$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);	
								$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');												
								} else {						
								$order_total['value_national'] = $this->currency->convert($order_total['value'], $main_currency,  $data['currency_code']);
								$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');						
							}				
						}
						
						if (!isset($order_total['for_delivery'])){
							$order_total['for_delivery'] = 0;
						}
						
						if (isset($order_total['order_total_id'])) {
							$this->db->query("INSERT INTO order_total SET 
							order_total_id = '" . (int)$order_total['order_total_id'] . "', 
							order_id = '" . (int)$order_id . "', 
							code = '" . $this->db->escape($order_total['code']) . "', 
							title = '" . $this->db->escape($order_total['title']) . "', 
							text = '" . $this->db->escape($order_total['text']) . "', 
							`value` = '" . (float)$order_total['value'] . "',
							`value_national` = '" . (float)$order_total['value_national'] . "',
							`for_delivery` = '" . (int)$order_total['for_delivery'] . "',
							sort_order = '" . (int)$order_total['sort_order'] . "'");	
							} else {
							$this->db->query("INSERT INTO order_total SET 						 
							order_id = '" . (int)$order_id . "', 
							code = '" . $this->db->escape($order_total['code']) . "', 
							title = '" . $this->db->escape($order_total['title']) . "', 
							text = '" . $this->db->escape($order_total['text']) . "', 
							`value` = '" . (float)$order_total['value'] . "',
							`value_national` = '" . (float)$order_total['value_national'] . "',
							`for_delivery` = '" . (int)$order_total['for_delivery'] . "',
							sort_order = '" . (int)$order_total['sort_order'] . "'");
							
						}
						
						$total += $order_total['value'];
						$total_national += $order_total['value_national'];
					}
				}
				
				//Обновление количества бонусов в очереди при редактировании (если заказ выполнен, но начисление находится в очереди)
				if ($this->customer->getRewardInQueueByOrder($data['customer_id'], $order_id)){					
					$this->customer->updateRewardInQueueByOrder($data['customer_id'], $order_id, $this->customer->getTotalRewardByOrder($data['customer_id'], $order_id));
				}
				
				//Обновление количества бонусов в табличке при редактировании (если заказ выполнен, но начисление находится в очереди)
				if ($this->customer->getRewardInTableByOrder($data['customer_id'], $order_id)){					
					$this->customer->updateRewardInTableByOrder($data['customer_id'], $order_id, $this->customer->getTotalRewardByOrder($data['customer_id'], $order_id));
				}
				
				//UPDATING PRICEWD
				$total_pricewd_national = 0;
				$total_total_pricewd_national = 0;
				
				unset($order_product);
				foreach ($data['order_product'] as $order_product) {			
					$total_pricewd_national += (float)$order_product['pricewd_national'];
					$total_total_pricewd_national += (float)$order_product['totalwd_national'];
				}
				
				unset($order_product);				
				$op_keys = array_keys($data['order_product']);				
				$last_not_zero_product_idx = isset($op_keys[0])?$op_keys[0]:0;
				foreach ($op_keys as $op_key){
					if ($data['order_product'][$op_key]['price_national'] > 3){
						$last_not_zero_product_idx = $op_key;
						break;
					}
				}
				
				unset($order_product);
				reset($data['order_product']);
				if (abs($total_total_pricewd_national - $total_national) > 0 && abs($total_total_pricewd_national - $total_national) < 3){				
					if ($total_total_pricewd_national > $total_national){
						$data['order_product'][$last_not_zero_product_idx]['totalwd_national'] = 
						$data['order_product'][$last_not_zero_product_idx]['totalwd_national'] - (abs($total_total_pricewd_national - $total_national));				
						} else {
						$data['order_product'][$last_not_zero_product_idx]['totalwd_national'] = 
						$data['order_product'][$last_not_zero_product_idx]['totalwd_national'] + (abs($total_total_pricewd_national - $total_national));
					}
					} elseif (abs($total_total_pricewd_national - $total_national) > 0 && abs($total_total_pricewd_national - $total_national) <= 0.5){
					
					if ($total_total_pricewd_national > $total_national){
						$data['order_product'][$last_not_zero_product_idx]['totalwd_national'] += 1;						
						} else {
						$data['order_product'][$last_not_zero_product_idx]['totalwd_national'] -= 1;
					}
					
				}
				
				unset($order_product);
				foreach ($data['order_product'] as $order_product) {													
					$this->db->query("UPDATE order_product SET
					pricewd_national = '" . (float)$order_product['pricewd_national'] . "',						
					totalwd_national = '" . (float)$order_product['totalwd_national'] . "',
					totals_json = '" . $this->db->escape(json_encode($totals_json[$order_product['order_product_id']])) . "'
					WHERE order_product_id = '" . (int)$order_product['order_product_id'] . "'");										
				}
				
				$prepayment = 0;
				$prepayment_national = 0;
				
				foreach ($data['order_total'] as $order_total) {
					//основной тотал
					if ($order_total['code']  ==  'total'){
						
						$order_total['value_national'] = $total_national;
						$order_total['value'] = $this->currency->convert($total_national, $data['currency_code'], $main_currency);	
						$order_total['text'] = $this->currency->format($total_national, $data['currency_code'], '1');							
						
						$this->db->query("INSERT INTO order_total SET 
						order_total_id = '" . (int)$order_total['order_total_id'] . "', 
						order_id = '" . (int)$order_id . "', 
						code = '" . $this->db->escape($order_total['code']) . "', 
						title = '" . $this->db->escape($order_total['title']) . "', 
						text = '" . $this->db->escape($order_total['text']) . "', 
						`value` = '" . (float)$order_total['value'] . "',
						`value_national` = '" . (float)$order_total['value_national'] . "',
						sort_order = '" . (int)$order_total['sort_order'] . "'");					
					}
					
					if ($order_total['code']  ==  'transfer_plus_prepayment'){
						
						if ($order_total['value_national'] != 0){
							$order_total['value'] = $this->currency->convert($order_total['value_national'], $data['currency_code'], $main_currency);	
							$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');												
							} else {						
							$order_total['value_national'] = $this->currency->convert($order_total['value'], $main_currency,  $data['currency_code']);
							$order_total['text'] = $this->currency->format($order_total['value_national'], $data['currency_code'], '1');						
						}				
						
						$this->db->query("INSERT INTO order_total SET 
						order_total_id = '" . (int)$order_total['order_total_id'] . "', 
						order_id = '" . (int)$order_id . "', 
						code = '" . $this->db->escape($order_total['code']) . "', 
						title = '" . $this->db->escape($order_total['title']) . "', 
						text = '" . $this->db->escape($order_total['text']) . "', 
						`value` = '" . (float)$order_total['value'] . "',
						`value_national` = '" . (float)$order_total['value_national'] . "',
						sort_order = '" . (int)$order_total['sort_order'] . "'");
						
						$prepayment = $order_total['value'];
						$prepayment_national = $order_total['value_national'];
					}
					
				}	
			}
			
			
			// Affiliate
			$affiliate_id = 0;
			$commission = 0;
			
			if (!empty($this->request->post['affiliate_id'])) {
				$this->load->model('sale/affiliate');
				
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);
				
				if ($affiliate_info) {
					$affiliate_id = $affiliate_info['affiliate_id']; 
					$commission = ($total / 100) * $affiliate_info['commission']; 
				}
			}
			
			$this->db->query("UPDATE `order` SET 
			total = '" . (float)$total . "',
			total_national = '" . (float)$total_national . "',
			prepayment = '" . (float)$prepayment . "',
			prepayment_national = '" . (float)$prepayment_national . "',
			affiliate_id = '" . (int)$affiliate_id . "', 
			commission = '" . (float)$commission . "'
			WHERE order_id = '" . (int)$order_id . "'");
			
			//Обнуление цифр в случае если товаров в заказе нет
			if (count($order_product) == 0 && $sub_total == 0){
				$this->db->query("UPDATE order_total SET value = 0, value_national = 0, text = '" . $this->currency->format(0, $data['currency_code'], '1') . "' WHERE order_id = '" . (int)$order_id . "' AND code <> 'shipping'");
				$this->db->query("UPDATE `order` SET total = '" . (float)$total . "', total_national = '" . (float)$total_national . "' WHERE order_id = '" . (int)$order_id . "'");
			}
			
			//count resaving of order
			$this->db->query("UPDATE `order` SET changed = changed+1 WHERE order_id = '" . (int)$order_id . "'");
			
			//manager
			if ($this->user->isLogged() && $this->user->getOwnOrders()){
				$manager_query = $this->db->query("SELECT manager_id FROM `order` WHERE order_id = '" . (int)$order_id . "' AND manager_id > 0");
				if (!($manager_query->rows)){						
					$this->db->query("UPDATE `order` SET manager_id = '" . (int)$this->user->getId() . "' WHERE order_id = '" . (int)$order_id . "'");		
				}
			}
			
			//related_order
			$this->db->query("DELETE FROM order_related WHERE order_id = '" . (int)$order_id . "' OR related_order_id = '" . (int)$order_id . "'");
			if ($data['related_order_id']){				
				foreach ($data['related_order_id'] as $related_order){
					if ((int)$related_order > 0) {
						$this->db->query("INSERT INTO order_related SET order_id = '" . (int)$order_id . "', related_order_id = '" . (int)$related_order . "'");
						$this->db->query("INSERT INTO order_related SET related_order_id = '" . (int)$order_id . "', order_id = '" . (int)$related_order . "'");
					}
				}
			}
			
			//UPDATE SAVE ORDER HISTORY
			if ($this->user->isLogged()) {
				$this->db->query("INSERT INTO `order_save_history` SET 
				`order_id` = '" . (int)$order_id . "', 
				`user_id` = '" . (int)$this->user->getId() . "',
				`data` = '" . $this->db->escape(base64_encode(serialize($data))) . "',
				`datetime` = NOW()
				");
			}
			
			//А вдруг в заказе были бонусы, но их удалили
			if (empty($order_has_reward)){
				$this->customer->addReward($data['customer_id'], '', 0, $order_id, 'ORDER_PAYMENT');
			}
			
			//fixing doubles
			$this->db->query("UPDATE `order_product` op SET product_id = (SELECT p.stock_product_id FROM product p WHERE p.product_id = op.product_id LIMIT 1) WHERE product_id IN (SELECT product_id FROM product WHERE stock_product_id > 0)");

			$this->registry->get('rainforestAmazon')->offersParser->PriceLogic->countOrderProfitablility($order_id);
			
			//saving orderxml for odinass
			if (in_array((int)$data['order_status_id'], $this->config->get('config_odinass_order_status_id'))){				
				$check_date_query = $this->db->query("SELECT date_added FROM `order` WHERE `order_id` = '" . (int)$order_id . "' AND (DATE(date_added) >= '2017-01-01')");								
				
				if (count($check_date_query->row)){
					$this->Fiscalisation->addOrderToQueue($order_id);
				}
			}			
		}
		
		
		public function addProduct($product, $order){
			
			if ($product['product_id']){
				
				$this->db->query("INSERT INTO order_product SET
				order_id = '" . (int)$order['order_id'] . "', 
				product_id = '" . (int)$product['product_id'] . "', 
				name = '" . $this->db->escape($product['name']) . "', 
				model = '" . $this->db->escape($product['model']) . "', 
				quantity = '" . (int)$product['quantity'] . "',
				ao_id = '" . (int)$product['ao_id'] . "', 					
				price = '" . (float)$product['price'] . "', 
				price_national = '" . (float)$product['price_national'] . "',
				original_price_national = '" . (float)$product['price_national'] . "',
				total = '" . (float)$product['total'] . "',
				total_national = '" . (float)$product['total_national'] . "',
				tax = '0', 
				reward = '" . (int)$product['reward'] . "',
				reward_one = '" . (int)($product['reward']/$product['quantity']) . "',
				good = '1', 
				taken = '0'");
				
				
				return $this->db->getLastId();
			}
		}
		
		public function addSetProduct($product, $order){
			
			if ($product['product_id']){
				
				$this->db->query("INSERT INTO order_set SET
				order_id = '" . (int)$order['order_id'] . "', 
				product_id = '" . (int)$product['product_id'] . "', 
				set_id = '" . (int)$product['set_id'] . "',
				set_product_id = '" . (int)$product['set_product_id'] . "', 					
				name = '" . $this->db->escape($product['name']) . "', 
				model = '" . $this->db->escape($product['model']) . "', 
				quantity = '" . (int)$product['quantity'] . "', 
				price = '" . (float)$product['price'] . "', 
				price_national = '" . (float)$product['price_national'] . "',
				total = '" . (float)$product['total'] . "',
				total_national = '" . (float)$product['total_national'] . "',
				tax = '0', 
				reward = '" . (int)$product['reward'] . "',
				reward_one = '" . (int)($product['reward']/$product['quantity']) . "',
				good = '1', 
				taken = '0'");
				
				
				return $this->db->getLastId();
			}
		}
		
		public function deleteOrder($order_id) {
			
			if ($this->user->getIsAV() /* || $this->user->canUnlockOrders() */){
				
				$log = new Log('orders.delete_history.txt');
				
				$order_query = $this->db->query("SELECT * FROM `order` WHERE order_status_id > '0' AND order_id = '" . (int)$order_id . "'");
				
				if ($order_query->num_rows) {
					$product_query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
					
					foreach($product_query->rows as $product) {
						$this->db->query("UPDATE `product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");
						
						$option_query = $this->db->query("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
						
						foreach ($option_query->rows as $option) {
							$this->db->query("UPDATE product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
						}
					}
				}
				
				$this->load->model('user/user');
				
				$log->write('Удален заказ ' . $order_id . '. Пользователь: ' . $this->model_user_user->getRealUserNameById($this->user->getID()));
				
				$this->db->query("DELETE FROM `order` WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_product WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_product_nogood WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_set WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_option WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_download WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_voucher WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_total WHERE order_id = '" . (int)$order_id . "'");				
				$this->db->query("DELETE FROM order_fraud WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM order_ukrcredits WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM customer_reward WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE FROM affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");
				$this->db->query("DELETE `or`, ort FROM order_recurring `or`, order_recurring_transaction ort WHERE order_id = '" . (int)$order_id . "' AND ort.order_recurring_id = `or`.order_recurring_id");
			}
		}
		
		public function getOrder($order_id) {
			$order_query = $this->db->query("SELECT *, 
			(SELECT CONCAT(c.firstname, ' ', c.lastname) FROM customer c WHERE c.customer_id = o.customer_id) AS customer,
			(SELECT nbt_csi FROM customer c2 WHERE c2.customer_id = o.customer_id) AS nbt_csi, 
			(SELECT status FROM order_courier_history och WHERE och.order_id = o.order_id ORDER BY date_added DESC LIMIT 1) as courier_status,
			(SELECT mudak FROM customer c WHERE c.customer_id = o.customer_id) AS mudak
			FROM `order` o WHERE o.order_id = '" . (int)$order_id . "'");
			
			if ($order_query->num_rows) {
				$reward = 0;
				
				$order_product_query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
				
				foreach ($order_product_query->rows as $product) {
					$reward += $product['reward'] * $product['quantity'];
				}			
				
				$country_query = $this->db->query("SELECT * FROM `country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$payment_iso_code_2 = $country_query->row['iso_code_2'];
					$payment_iso_code_3 = $country_query->row['iso_code_3'];
					} else {
					$payment_iso_code_2 = '';
					$payment_iso_code_3 = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$payment_zone_code = $zone_query->row['code'];
					} else {
					$payment_zone_code = '';
				}
				
				$country_query = $this->db->query("SELECT * FROM `country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$shipping_iso_code_2 = $country_query->row['iso_code_2'];
					$shipping_iso_code_3 = $country_query->row['iso_code_3'];
					} else {
					$shipping_iso_code_2 = '';
					$shipping_iso_code_3 = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$shipping_zone_code = $zone_query->row['code'];
					} else {
					$shipping_zone_code = '';
				}
				
				if ($order_query->row['affiliate_id']) {
					$affiliate_id = $order_query->row['affiliate_id'];
					} else {
					$affiliate_id = 0;
				}				
				
				$this->load->model('sale/affiliate');
				
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
				
				if ($affiliate_info) {
					$affiliate_firstname = $affiliate_info['firstname'];
					$affiliate_lastname = $affiliate_info['lastname'];
					} else {
					$affiliate_firstname = '';
					$affiliate_lastname = '';				
				}
				
				$this->load->model('localisation/language');
				
				$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
				
				if ($language_info) {
					$language_code 		= $language_info['code'];
					$language_filename 	= $language_info['filename'];
					$language_directory = $language_info['directory'];
					} else {
					$language_code 		= '';
					$language_filename 	= '';
					$language_directory = '';
				}

				$order_ukrcredits_query = $this->db->query("SELECT * FROM order_ukrcredits WHERE order_id = '" . (int)$order_id . "'");

				if ($order_ukrcredits_query->num_rows) {
					$ukrcredits_payment_type 	= $order_ukrcredits_query->row['ukrcredits_payment_type'];
					$ukrcredits_order_id 		= $order_ukrcredits_query->row['ukrcredits_order_id'];
					$ukrcredits_order_status 	= $order_ukrcredits_query->row['ukrcredits_order_status'];
					$ukrcredits_order_substatus = $order_ukrcredits_query->row['ukrcredits_order_substatus'];
				} else {
					$ukrcredits_payment_type 	= '';
					$ukrcredits_order_id 		= '';
					$ukrcredits_order_status 	= '';
					$ukrcredits_order_substatus = '';
				}				
				
				return array(
				'order_id'                => $order_query->row['order_id'],
				'order_id2'               => $order_query->row['order_id2'],
				'amazon_order_id'         => false,
				'pwa'               	  => $order_query->row['pwa'],	
				'yam'               	  => $order_query->row['yam'],
				'yam_id'               	  => $order_query->row['yam_id'],
				'yam_campaign_id'      	  => $order_query->row['yam_campaign_id'],
				'yam_express'      		  => $order_query->row['yam_express'],
				'yam_shipment_date'       => $order_query->row['yam_shipment_date'],
				'yam_shipment_id'         => $order_query->row['yam_shipment_id'],
				'yam_box_id'         	  => $order_query->row['yam_box_id'],
				'yam_status'              => $order_query->row['yam_status'],
				'yam_substatus'           => $order_query->row['yam_substatus'],
				'yam_fake'           	  => $order_query->row['yam_fake'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'invoice_date'            => $order_query->row['invoice_date'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'faxname'                 => $order_query->row['faxname'],
				'email'                   => $order_query->row['email'],
				'part_num'                => $order_query->row['part_num'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_company_id'      => $order_query->row['payment_company_id'],
				'payment_tax_id'          => $order_query->row['payment_tax_id'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_struct'  => $order_query->row['payment_address_struct'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'payment_secondary_method'  => $order_query->row['payment_secondary_method'],
				'payment_secondary_code'    => $order_query->row['payment_secondary_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_struct'  => $order_query->row['shipping_address_struct'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_passport_serie' => $order_query->row['shipping_passport_serie'],
				'shipping_passport_date'  => $order_query->row['shipping_passport_date'],
				'shipping_passport_inn'   => $order_query->row['shipping_passport_inn'],
				'shipping_passport_given' => $order_query->row['shipping_passport_given'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'legalperson_id'     	  => $order_query->row['legalperson_id'],
				'card_id'     			  => $order_query->row['card_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'costprice'				  => $order_query->row['costprice'],
				'profitability'			  => $order_query->row['profitability'],
				'total'                   => $order_query->row['total'],
				'total_national'          => $order_query->row['total_national'],
				'total_paid'              => $order_query->row['total_paid'],
				'total_paid_date'         => $order_query->row['total_paid_date'],
				'prepayment'              => $order_query->row['prepayment'],
				'prepayment_national'     => $order_query->row['prepayment_national'],
				'prepayment_paid'         => $order_query->row['prepayment_paid'],
				'prepayment_paid_date'    => $order_query->row['prepayment_paid_date'],
				'reward'                  => $reward,
				'status_id'               => $order_query->row['order_status_id'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'first_referrer'           => $order_query->row['first_referrer'],
				'last_referrer'            => $order_query->row['last_referrer'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'], 
				'user_agent'              => $order_query->row['user_agent'],	
				'accept_language'         => $order_query->row['accept_language'],					
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_sent'           	  => $order_query->row['date_sent'],
				'date_buy'           	 => $order_query->row['date_buy'],
				'date_country'           => $order_query->row['date_country'],
				'date_delivery'          => $order_query->row['date_delivery'],
				'date_delivery_to'       => $order_query->row['date_delivery_to'],
				'date_delivery_actual'       => $order_query->row['date_delivery_actual'],
				'display_date_in_account'    => $order_query->row['display_date_in_account'],
				'date_maxpay'            => $order_query->row['date_maxpay'],
				'manager_id'             => $order_query->row['manager_id'],
				'courier_id'             => $order_query->row['courier_id'],
				'changed'                => $order_query->row['changed'],
				'ttn'                    => $order_query->row['ttn'],
				'from_waitlist'          => $order_query->row['from_waitlist'],
				'bottom_text'			 => $order_query->row['bottom_text'],
				'pay_equire'			 => $order_query->row['pay_equire'],
				'pay_equire2'			 => $order_query->row['pay_equire2'],
				'pay_equirePP'			 => $order_query->row['pay_equirePP'],
				'pay_equireLQP'			 => $order_query->row['pay_equireLQP'],
				'pay_equireWPP'			 => $order_query->row['pay_equireWPP'],
				'pay_equireMono'		 => $order_query->row['pay_equireMono'],
				'pay_equireCP'			 => $order_query->row['pay_equireCP'],
				'ukrcredits_payment_type'     => $ukrcredits_payment_type,
				'ukrcredits_order_id'         => $ukrcredits_order_id,
				'ukrcredits_order_status'     => $ukrcredits_order_status,
				'ukrcredits_order_substatus'  => $ukrcredits_order_substatus,
				'pay_type'			     => $order_query->row['pay_type'],
				'bill_file'				 => $order_query->row['bill_file'],
				'bill_file2'		     => $order_query->row['bill_file2'],
				'probably_cancel'        => $order_query->row['probably_cancel'],
				'probably_cancel_reason' => $order_query->row['probably_cancel_reason'],
				'probably_close'       	 => $order_query->row['probably_close'],
				'probably_close_reason'	 => $order_query->row['probably_close_reason'],
				'probably_problem'		 => $order_query->row['probably_problem'],
				'probably_problem_reason'=> $order_query->row['probably_problem_reason'],
				'reject_reason_id'		 => $order_query->row['reject_reason_id'],				
				'courier_status'		 => $order_query->row['courier_status'],				
				'csi_reject'		 => $order_query->row['csi_reject'],
				'csi_average'		 => $order_query->row['csi_average'],
				'csi_mark'		 	 => $order_query->row['csi_mark'],
				'speed_mark'		 => $order_query->row['speed_mark'],
				'manager_mark'		 => $order_query->row['manager_mark'],
				'quality_mark'		 => $order_query->row['quality_mark'],
				'courier_mark'		 => $order_query->row['courier_mark'],
				'csi_comment'		 => $order_query->row['csi_comment'],
				'speed_comment'		 => $order_query->row['speed_comment'],
				'manager_comment'		 => $order_query->row['manager_comment'],
				'quality_comment'		 => $order_query->row['quality_comment'],
				'courier_comment'		 => $order_query->row['courier_comment'],
				'preorder'		 		=> $order_query->row['preorder'],				
				'nbt_csi'              	=> $order_query->row['nbt_csi'],				
				'closed'                => $order_query->row['closed'],
				'salary_paid'           => $order_query->row['salary_paid'],				
				'needs_checkboxua'      => $order_query->row['needs_checkboxua'],				
				'paid_by'      			=> $order_query->row['paid_by'],
				'urgent'				=> isset($order_query->row['urgent'])?$order_query->row['urgent']:0,
				'urgent_buy'			=> isset($order_query->row['urgent_buy'])?$order_query->row['urgent_buy']:0,
				'wait_full'				=> isset($order_query->row['wait_full'])?$order_query->row['wait_full']:0,
				'do_not_call'			=> isset($order_query->row['do_not_call'])?$order_query->row['do_not_call']:0,
				'ua_logistics'			=> isset($order_query->row['ua_logistics'])?$order_query->row['ua_logistics']:0,
				'concardis_id'			=> isset($order_query->row['concardis_id'])?$order_query->row['concardis_id']:0,
				'tracker_xml'           => $order_query->row['tracker_xml'],
				'amazon_offers_type'    => $order_query->row['amazon_offers_type'],
				'template'            	=> $order_query->row['template'],
				'customer_confirm_url'   => '&order_id='.$order_query->row['order_id'].'&confirm='.md5(sin($order_query->row['order_id']+2)).'&utm_term='.$order_query->row['email'].'&utoken='.md5($order_query->row['email'] . $this->config->get('config_encryption'))
				);
				} else {
				return false;
			}
		}

		public function getAllTrackingStatuses(){
			$query = $this->db->query("SELECT DISTINCT(tracking_status) FROM order_ttns WHERE 1");

			$result = [];
			foreach ($query->rows as $row){
				$result[] = $row['tracking_status'];
			}

			return $result;
		}
		
		public function getOrders($data = array()) {
			$sql = "SELECT DISTINCT o.order_id, o.preorder, o.do_not_call, o.pwa, o.monocheckout, o.yam, o.yam_id, o.yam_campaign_id, o.yam_express, o.yam_shipment_date, o.yam_shipment_id, o.yam_box_id, o.yam_fake, o.yam_status, o.yam_substatus, o.template, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.customer_id, o.tracker_xml, o.shipping_code, o.needs_checkboxua, o.paid_by, o.costprice, o.profitability, o.amazon_offers_type, ";

			if ($this->config->get('ukrcredits_status')){
				$sql .= " ouc.ukrcredits_order_status, ouc.ukrcredits_order_substatus, ";
			}

			$sql .= " (SELECT SUM(reward) FROM order_product WHERE order_id = o.order_id) as reward,
			(SELECT value_national FROM order_total WHERE order_id = o.order_id AND code = 'reward' LIMIT 1) as reward_used,
			(SELECT os.name FROM order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, 
			(SELECT status FROM order_courier_history och WHERE och.order_id = o.order_id ORDER BY date_added DESC LIMIT 1) as courier_status,
			(SELECT comment FROM order_courier_history och WHERE och.order_id = o.order_id ORDER BY date_added DESC LIMIT 1) as courier_comment,
			(SELECT nbt_csi FROM customer c WHERE c.customer_id = o.customer_id) as nbt_csi, 
			(SELECT os.status_bg_color FROM order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status_bg_color, 
			(SELECT os.status_txt_color FROM order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status_txt_color, 
			(SELECT os.status_fa_icon FROM order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status_fa_icon, o.currency_code, o.currency_value, o.date_added, o.date_modified, o.store_id, o.affiliate_id, o.telephone, o.fax, o.faxname, o.shipping_country, o.shipping_zone, o.shipping_address_1, o.payment_address_1, o.shipping_address_struct, o.shipping_city, o.email, o.comment, o.payment_postcode, o.payment_method, o.payment_code, o.payment_secondary_method, o.shipping_method, o.total, o.total_national, o.order_status_id, o.currency_code, o.currency_value, o.date_added, o.date_modified, o.courier_id, o.manager_id, o.store_url, o.part_num, o.pay_type, o.ttn, o.reject_reason_id, o.from_waitlist, o.urgent, o.urgent_buy, o.wait_full, o.first_referrer, o.last_referrer, o.ua_logistics, o.date_delivery_actual, o.shipping_country_id, o.probably_cancel, o.probably_cancel_reason,o.probably_close,o.probably_close_reason,o.csi_average,o.csi_reject,o.probably_problem,o.probably_problem_reason, (SELECT delivery_code FROM order_ttns WHERE order_id = o.order_id AND ttn LIKE (o.ttn) LIMIT 1) as delivery_code, changed, o.closed, o.salary_paid, orec.receipt_id, orec.serial, orec.fiscal_code, orec.is_created_offline, orec.is_sent_dps, orec.sent_dps_at, orec.fiscal_date FROM `order` o";
			
			$sql .= " LEFT JOIN order_receipt orec ON (o.order_id = orec.order_id)  ";

			if ($this->config->get('ukrcredits_status')){
				$sql .= " LEFT JOIN order_ukrcredits ouc ON (o.order_id = ouc.order_id) ";
			}

			if (!empty($data['filter_order_id']) || !empty($data['filter_product_id'])) {
				if (!is_numeric($data['filter_order_id']) || !empty($data['filter_product_id'])){				
					$sql .= " LEFT JOIN order_product op ON op.order_id = o.order_id";
				}
			}
			
			if (!empty($data['filter_customer']) || (!empty($data['filter_discount_card'])) || (!empty($data['filter_order_status_id']) && $data['filter_order_status_id'] == 'nbt_csi') || (!empty($data['filter_order_status_id']) && strpos($data['filter_order_status_id'], 'need_csi') !== false)) {
				$sql .= " LEFT JOIN customer c ON c.customer_id = o.customer_id";
			}					
			
			if (!empty($data['filter_ttn'])){
				$sql .= " LEFT JOIN order_ttns ot ON ot.order_id = o.order_id";
			}
			
			if (!empty($data['filter_courier_status'])){
				$sql .= " LEFT JOIN order_courier_history och ON och.order_id = o.order_id AND och.date_added = (SELECT MAX(date_added) FROM order_courier_history och WHERE och.order_id = o.order_id)";
			}
			
			if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
				if ($data['filter_order_status_id'] == 'all_except_success'){
					$sql .= " WHERE (o.order_status_id > '0' AND o.order_status_id != '" . (int)$this->config->get('config_complete_status_id') . "')";
					} elseif ($data['filter_order_status_id'] == 'all_except_closed') {
					$sql .= " WHERE (o.order_status_id NOT IN (17, 23, 18, 24, 0))";
					} elseif ($data['filter_order_status_id'] == 'general_problems') {
					$sql .= " WHERE (o.order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ") OR ((o.probably_cancel=1 OR o.probably_close=1 OR o.probably_problem=1) AND o.order_status_id > '0'))";
					} elseif ($data['filter_order_status_id'] == 'flow_problems') {
					$sql .= " WHERE o.order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ")";
					} elseif ($data['filter_order_status_id'] == 'probably_cancel') {
					$sql .= " WHERE (o.order_status_id > '0' AND o.probably_cancel = 1)";
					} elseif ($data['filter_order_status_id'] == 'probably_close') {
					$sql .= " WHERE (o.order_status_id > '0' AND o.probably_close = 1)";
					} elseif ($data['filter_order_status_id'] == 'probably_problem') {
					$sql .= " WHERE (o.order_status_id > '0' AND o.probably_problem = 1)";
					} elseif ($data['filter_order_status_id'] == 'need_approve') {
					$sql .= " WHERE o.order_status_id IN (" . implode(',', $this->config->get('config_toapprove_order_status_id')) . ")";
					} elseif ($data['filter_order_status_id'] == 'cancel_with_wait_for_payment') {
					$sql .= " WHERE (o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND o.order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . implode(',', array($this->config->get('config_confirmed_nopaid_order_status_id'))) . ")))";
					} elseif ($data['filter_order_status_id'] == 'cancel_with_payment') {
					$sql .= " WHERE (o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND o.order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . implode(',', array($this->config->get('config_total_paid_order_status_id'), (int)$this->config->get('config_prepayment_paid_order_status_id'))) . ")))";
					} elseif ($data['filter_order_status_id'] == 'cancel_with_process') {
					$sql .= " WHERE (o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND o.order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (2,19,25)))";
					} elseif ($data['filter_order_status_id'] == 'overdue') {
					$sql .= " WHERE (o.urgent = 0 AND ((o.date_delivery_to != '0000-00-00' AND DATE(o.date_delivery_to) < DATE(NOW())) OR (o.date_delivery_actual != '0000-00-00' AND  DATE(o.date_delivery_actual) < DATE(NOW()))) AND o.order_status_id > 0 AND o.order_status_id NOT IN (" . implode(',', array($this->config->get('config_complete_status_id'), $this->config->get('config_cancelled_status_id'), 23)) . "))";						
					} elseif ($data['filter_order_status_id'] == 'overdue_complete') {
					$sql .=	" WHERE (o.urgent = 0 AND (
					(o.date_delivery_to != '0000-00-00' AND DATE(o.date_delivery_to) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND oh2.order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1))
					OR (o.date_delivery_actual != '0000-00-00' AND  DATE(o.date_delivery_actual) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1))) 
					AND o.order_status_id > 0 AND o.order_status_id IN (" . implode(',', array($this->config->get('config_complete_status_id'))) . "))";
					} elseif ($data['filter_order_status_id'] == 'overdue_cancel') {
					$sql .=	" WHERE (o.urgent = 0 AND (
					(o.date_delivery_to != '0000-00-00' AND DATE(o.date_delivery_to) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND oh2.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' ORDER BY date_added DESC LIMIT 1))
					OR (o.date_delivery_actual != '0000-00-00' AND  DATE(o.date_delivery_actual) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND oh2.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' ORDER BY date_added DESC LIMIT 1))) 
					AND o.order_status_id > 0 AND o.order_status_id IN (" . implode(',', array($this->config->get('config_cancelled_status_id'))) . "))";
					} elseif ($data['filter_order_status_id'] == 'has_csi') {
					$sql .= " WHERE (o.order_status_id > '0' AND (o.csi_average > 0 OR o.csi_reject = 1))";
					} elseif ($data['filter_order_status_id'] == 'birthday_discount_used') {
					$sql .= " WHERE (o.order_status_id > '0' AND (o.order_id IN (SELECT DISTINCT order_id FROM order_total WHERE code = 'discount_regular' OR (title LIKE '%Рождения%' OR title LIKE '%рождения%'))))";
					} elseif ($data['filter_order_status_id'] == 'nbt_csi') {
					$sql .= " WHERE (c.nbt_csi = 1)";
					} elseif (strpos($data['filter_order_status_id'], 'need_csi') !== false) {						
					$_ss = explode('_', $data['filter_order_status_id']);
					$_oid = (int)$_ss[2];
					
					$sql .= " WHERE (o.order_status_id = '" . (int)$_oid . "' AND o.csi_average = 0 AND o.csi_reject = 0 AND c.nbt_csi = 0)";
					} else {
					$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				}
				
				
				} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}
			
			if (!empty($data['filter_discount_card']) && $data['filter_discount_card']){
				$sql .= " AND ((REPLACE(c.discount_card, ' ', '') LIKE '" . str_replace(' ', '', $this->db->escape($data['filter_discount_card'])) . "') OR c.discount_card LIKE '" . $this->db->escape($data['filter_discount_card']) . "')";
			}
			
			if (!empty($data['filter_referrer']) && $data['filter_referrer']){
				$sql .= " AND (LCASE(o.first_referrer) LIKE '%" . mb_strtolower($this->db->escape($data['filter_referrer'])) . "%' OR LCASE(o.last_referrer) LIKE '%" . mb_strtolower($this->db->escape($data['filter_referrer'])) . "%' )";
			}
			
			if (isset($data['filter_reject_reason_id']) && !is_null($data['filter_reject_reason_id'])) {
				$sql .= " AND o.reject_reason_id = '" . (int)$data['filter_reject_reason_id'] . "'";
			}
			
			if (isset($data['filter_affiliate_id']) && !is_null($data['filter_affiliate_id'])) {
				$sql .= " AND o.affiliate_id = '" . (int)$data['filter_affiliate_id'] . "'";
			}
			
			if (!empty($data['filter_courier_status'])){
				$sql .= " AND och.status = '" . $this->db->escape($data['filter_courier_status']) . "'";
			}

			if (!empty($data['filter_is_credit_order'])){
				$sql .= " AND NOT ISNULL(ouc.ukrcredits_order_status)";
			}
			
			if (!empty($data['filter_pwa'])){
				$sql .= " AND o.pwa = 1";
			}
			
			if (!empty($data['filter_yam'])){
				$sql .= " AND o.yam = 1";
			}
			
			if (!empty($data['filter_yam_id'])){
				$sql .= " AND o.yam_id = '" . $this->db->escape($data['filter_yam_id']) . "'";
			}

			if (!empty($data['filter_yam_campaign_id'])){
				$sql .= " AND o.yam_campaign_id = '" . (int)$data['filter_yam_campaign_id'] . "'";
			}

			if (!empty($data['filter_yam_express'])){
				$sql .= " AND o.yam_express = '" . (int)$data['filter_yam_express'] . "'";
			}
			
			if (!empty($data['filter_order_status_notnull'])){
				$sql .= " AND o.order_status_id > 0";
			}
			
			if (!empty($data['filter_shipping_method'])){
				$sql .= " AND o.shipping_code = '" . $this->db->escape($data['filter_shipping_method']) . "'";
			}
			
			if (!empty($data['filter_payment_method'])){
				$sql .= " AND o.payment_code = '" . $this->db->escape($data['filter_payment_method']) . "'";
			}
			
			if (isset($data['filter_order_store_id']) && !is_null($data['filter_order_store_id'])) {
				$sql .= " AND o.store_id = '" . (int)$data['filter_order_store_id'] . "'";
				} else {
				// $sql .= " AND o.store_id > '0'";
			}
			
			if (!empty($data['filter_order_id'])) {
				
				if (is_numeric($data['filter_order_id'])){
					$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
					} else {
					$sql .= " AND op.part_num LIKE ('%" . $this->db->escape($data['filter_order_id']) . "%')";
				}			
			}
			
			if (!empty($data['filter_ttn'])){
				$sql .= " AND REPLACE(REPLACE(REPLACE(ot.ttn, ' ', ''), '-', ''), '/', '')
				LIKE '" . $this->db->escape(str_replace(array(' ', '-','/',), array('','','',), $data['filter_ttn'])) . "%'";
			}
			
			
			if (!empty($data['filter_product_id'])) {
				$sql .= " AND (op.product_id = '" . (int)$data['filter_product_id'] . "'";
				$sql .= " OR op.model LIKE (SELECT pp.model FROM product pp WHERE pp.product_id = '" . (int)$data['filter_product_id'] . "' LIMIT 1))";
			}
			
			if (!empty($data['filter_customer'])) {
				if (is_numeric($data['filter_customer'])){
					/*
						$sql .= " AND (o.customer_id = '" . (int)$data['filter_customer'] . "' OR REPLACE(c.discount_card, ' ', '') LIKE '" . (int)$data['filter_customer'] ."')";
					*/
					$sql .= " AND (o.customer_id = '" . (int)$data['filter_customer'] . "')";
					} else {
					$sql .= " AND (CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%' 
					OR o.email LIKE '%" . $this->db->escape($data['filter_customer']) . "%'
					OR REPLACE(c.discount_card, ' ', '') LIKE '" . $this->db->escape(str_replace(' ','', $data['filter_customer'])) . "'
					)";
				}
			}
			
			if (!empty($data['filter_date_added'])) {
				$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_date_added_to'])) {
				$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
			}
			
			if (!empty($data['filter_date_modified'])) {
				$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
			}
			
			if (!empty($data['filter_date_delivery'])) {
				$sql .= " AND DATE(o.date_delivery) = DATE('" . $this->db->escape($data['filter_date_delivery']) . "')";
			}
			
			if (!empty($data['filter_total'])) {
				$sql .= " AND (o.total_national = '" . (float)$data['filter_total'] . "' OR order_id IN (SELECT order_id FROM order_total WHERE code = 'total' AND value_national = '" . (float)$data['filter_total'] . "'))";
			}
			
			if (!empty($data['filter_urgent'])) {
				$sql .= " AND o.urgent = '1'";
			}
			
			if (!empty($data['filter_preorder'])) {
				$sql .= " AND o.preorder = '1'";
			}
			
			if (!empty($data['filter_urgent_buy'])) {
				$sql .= " AND o.urgent_buy = '1'";
			}
			
			if (!empty($data['filter_wait_full'])) {
				$sql .= " AND o.wait_full = '1'";
			}
			
			if (!empty($data['filter_ua_logistics'])) {
				$sql .= " AND o.ua_logistics = '1'";
			}

			if (!empty($data['filter_amazon_offers_type'])) {
				$sql .= " AND o.amazon_offers_type = '" . $this->db->escape($data['filter_amazon_offers_type']) . "'";
			}
			
			if (!empty($data['filter_manager_id'])) {
				$sql .= " AND o.manager_id = '" . (int)$data['filter_manager_id'] . "'";
			}
			
			if (!empty($data['filter_courier_id'])) {
				$sql .= " AND o.courier_id = '" . (int)$data['filter_courier_id'] . "'";
			}
			
			if (!empty($data['filter_affiliate_id'])) {
				$sql .= " AND o.affiliate_id = '" . (int)$data['filter_affiliate_id'] . "'";
			}
			
			if ($this->user->isLogged() && $this->user->getManagerStores()){
				$sql .= " AND o.store_id IN (" . implode(',', $this->user->getManagerStores()) . ")";					
			}
			
			$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total',
			'o.manager_id'
			);			
			
			if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id']) && !is_numeric($data['filter_order_status_id'])) {
				if (strpos($data['filter_order_status_id'], 'need_csi') !== false){
					
					$sql .= " AND DATE(o.date_modified) >= DATE('" . date('Y-m-d', strtotime('-1 month')) . "') AND DATE(o.date_added) >= DATE('" . date('Y-m-d', strtotime('-2 month')) . "')";
					
					$data['sort'] = 'o.date_modified';
					$data['order'] = 'DESC';
					} else {
					$data['sort'] = 'o.date_modified';
					$data['order'] = 'ASC';
				}
				
				if (strpos($data['filter_order_status_id'], 'need_approve') !== false){
					$data['sort'] = 'o.date_modified';
					$data['order'] = 'DESC';
				}
				
				if (strpos($data['filter_order_status_id'], 'need_approve') !== false){
					$data['sort'] = 'o.date_modified';
					$data['order'] = 'DESC';
				}
				
				if (strpos($data['filter_order_status_id'], 'cancel_with_') !== false){
					$data['sort'] = 'o.date_modified';
					$data['order'] = 'DESC';
				}
			}
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY o.date_added";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
				} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		
		public function getOrdersSums($data = array()) {
			
			$sql = "SELECT SUM(o.total) as total, o.currency_code, o.currency_value FROM `order` o ";
			
			if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
				$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}
			
			if (isset($data['filter_reject_reason_id']) && !is_null($data['filter_reject_reason_id'])) {
				$sql .= " AND o.reject_reason_id = '" . (int)$data['filter_reject_reason_id'] . "'";
			}
			
			if (isset($data['filter_order_store_id']) && !is_null($data['filter_order_store_id'])) {
				$sql .= " AND o.store_id = '" . (int)$data['filter_order_store_id'] . "'";
			}
			
			if (!empty($data['filter_order_id'])) {
				$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
			}
			
			if (!empty($data['filter_customer'])) {
				if ((int)$data['filter_customer'] > 0){
					$sql .= " AND o.customer_id = '" . (int)$data['filter_customer'] . "'";
					} else {
					$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
				}
			}
			
			if (!empty($data['filter_date_added'])) {
				$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_date_added_to'])) {
				$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
			}
			
			if (!empty($data['filter_date_modified'])) {
				$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
			}
			
			if (!empty($data['filter_total'])) {
				$sql .= " AND (o.total_national = '" . (float)$data['filter_total'] . "' OR order_id IN (SELECT order_id FROM order_total WHERE code = 'total' AND value_national = '" . (float)$data['filter_total'] . "'))";
			}
			
			if (!empty($data['filter_manager_id'])) {
				$sql .= " AND o.manager_id = '" . (int)$data['filter_manager_id'] . "'";
			}
			
			if (!empty($data['filter_courier_id'])) {
				$sql .= " AND o.courier_id = '" . (int)$data['filter_courier_id'] . "'";
			}
			
			if (!empty($data['filter_urgent'])) {
				$sql .= " AND o.urgent = '1'";
			}
			
			if (!empty($data['filter_preorder'])) {
				$sql .= " AND o.preorder = '1'";
			}
			
			if (!empty($data['filter_urgent_buy'])) {
				$sql .= " AND o.urgent_buy = '1'";
			}
			
			if (!empty($data['filter_wait_full'])) {
				$sql .= " AND o.wait_full = '1'";
			}
			
			if (!empty($data['filter_ua_logistics'])) {
				$sql .= " AND o.ua_logistics = '1'";
			}

			if (!empty($data['filter_amazon_offers_type'])) {
				$sql .= " AND o.amazon_offers_type = '" . $this->db->escape($data['filter_amazon_offers_type']) . "'";
			}
			
			if (!empty($data['filter_affiliate_id'])) {
				$sql .= " AND o.affiliate_id = '" . (int)$data['filter_affiliate_id'] . "'";
			}
			
			$sql .= " GROUP BY o.currency_code";				
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function findOrderProductsLine($order_id, $product_id, $quantity){
			
			$query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "' AND product_id = '" . (int)$product_id . "' AND '" . (int)$quantity . "'");
			
			if ($query->row){
				return array(
				'distinct' => true,
				'row'      => $query->row
				);
				} else {
				
				$query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "' AND product_id = '" . (int)$product_id . "'");
				
				if ($query->row){
					return array(
					'distinct' => false,
					'row'      => $query->row
					);
					} else {
					
					return false;
					
				}
			}
			
		}
		
		public function getOrderUntakenProducts($order_id, $with_returns = true, $order_by = 'op.delivery_num, op.name', $delivery_num = false, $no_certificate = false) {			
			$_sql_addon = '';
			
			if ($delivery_num){				
				$_sql_addon .= " AND op.delivery_num = '" . (int)$delivery_num . "'";				
			}
			
			if (!$with_returns) {
				$_sql_addon .= " AND is_returned = '0'";				
			}
			
			if ($no_certificate) {
				$_sql_addon .= " AND (op.product_id NOT IN (SELECT product_id FROM product WHERE model LIKE ('%certificate%')))";				
			}

			$sql = "SELECT op.*,p.image, p.short_name, p.short_name_de, p.product_id, p.manufacturer_id, 
			(SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "' LIMIT 1) as de_name ";
			
			if (!empty($this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')])){
				$sql .= ", (SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id'] . "' LIMIT 1) as ua_name "; 
			}					

			$sql .= " FROM order_product_untaken op LEFT JOIN product p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' " . $_sql_addon . " ORDER BY " . $this->db->escape($order_by);
			
			$query = $this->db->query($sql);		
			
			$products = array();
			foreach ($query->rows as $row){
				$products[] = $row;
			}
			
			return $products;
		}
		
		public function getOrderProducts($order_id, $with_returns = true, $order_by = 'op.delivery_num, op.name', $delivery_num = false, $no_certificate = false) {			
			$sql = "SELECT op.*, 
			p.image, 
			p.short_name, 
			p.short_name_de, 
			p.product_id, 
			p.manufacturer_id, 
			p.ean, 
			p.asin,
			(SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "' LIMIT 1) as de_name";

			if (!empty($this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')])){
				$sql .=	" , (SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id'] . "' LIMIT 1) as ua_name ";
			}

			$add = '';			
			if ($delivery_num){				
				$add .= " AND op.delivery_num = '" . (int)$delivery_num . "'";				
			}
			
			if (!$with_returns) {
				$add .= " AND is_returned = '0'";				
			}
			
			if ($no_certificate) {
				$add .= " AND (op.product_id NOT IN (SELECT product_id FROM product WHERE model LIKE ('%certificate%')))";				
			}

			$sql .= " FROM order_product op LEFT JOIN product p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' " . $add . " ORDER BY " . $this->db->escape($order_by);	
		
			$query = $this->db->query($sql);
			
			$products = array();
			foreach ($query->rows as $row){
				if (!$row['product_id']){
					if (substr($row['model'], -1) == 'S' || substr($row['model'], -1) == 's'){
						$temp_model = substr($row['model'], 0, -1);  
						} else {
						$temp_model = $row['model'];
					}
					
					$guess_id_query = $this->db->query("SELECT product_id FROM product WHERE model LIKE('". $this->db->escape($temp_model) ."')  LIMIT 1");
					
					if ($guess_id_query->row) {
						$product_id = $guess_id_query->row['product_id'];
						$this->db->query("UPDATE order_product SET product_id = '" . (int)$product_id . "' WHERE order_product_id = '" .(int)$row['order_product_id']. "'");
						$row['product_id'] = $product_id;
					}
					
					$products[] = $row;
					} else {
					$products[] = $row;
				}
			}
			
			return $products;
		}
		
		public function getOrderProductByID($order_product_id) {
			
			$query = $this->db->query("SELECT op.* FROM order_product op WHERE order_product_id = '" . (int)$order_product_id . "' LIMIT 1");
			
			return $query->row;		
		}
		
		public function getOrderProductUntakenByID($order_product_id) {
			
			$query = $this->db->query("SELECT op.* FROM order_product_untaken op WHERE order_product_id = '" . (int)$order_product_id . "' LIMIT 1");
			
			return $query->row;
			
		}
		
		public function getOrderProductPartiesByID($order_product_id) {			
			$query = $this->db->query("SELECT op.* FROM order_product op WHERE order_product_id = '" . (int)$order_product_id . "' LIMIT 1");
			
			return $query->row;			
		}
		
		public function getOrderProductsNoGood($order_id) {			
			$sql = "SELECT op.*,p.image, p.short_name, p.product_id, p.stock_status_id, 
			(SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "' LIMIT 1) as de_name ";

			if (!empty($this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')])){
				$sql .= " ,(SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id'] . "' LIMIT 1) as ua_name ";	
			}			

			$sql .= " FROM order_product_nogood op LEFT JOIN product p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' ORDER BY op.name";


			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getOrderProductsBySet($order_id, $set_id){			
			$query = $this->db->query("SELECT op.*,p.image, p.short_name, p.product_id, 
			(SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id'] . "' LIMIT 1) as de_name,
			(SELECT name FROM product_description WHERE product_id = op.product_id AND language_id = '" . (int)$this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id'] . "' LIMIT 1) as ua_name 
			FROM order_set op LEFT JOIN product p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' AND set_id = '" . (int)$set_id . "' ORDER BY op.name");
			
			return $query->rows;
		}
		
		public function getOrderProductsWaitList($order_id) {
			$query = $this->db->query("
			SELECT 
			op.*,
			p.image, 
			p.product_id 
			FROM order_product_nogood op 
			LEFT JOIN product p 
			ON 
			op.product_id = p.product_id 
			WHERE order_id = '" . (int)$order_id . "' 
			AND waitlist = 1 ORDER BY op.name");
			
			return $query->rows;
		}
		
		public function getTotalOrderProductsWaitList($order_id) {
			$query = $this->db->query("
			SELECT 
			count(*) as total
			FROM order_product_nogood op 			
			WHERE order_id = '" . (int)$order_id . "' 
			AND waitlist = 1");
			
			return $query->row['total'];
		}					
		
		public function getOrderProductsList($order_id) {
			$query = $this->db->query("SELECT op.*, p.image, p.ean, p.`" . $this->config->get('config_warehouse_identifier_local') . "` 
				FROM order_product op 
				LEFT JOIN product p ON (op.product_id = p.product_id)
				WHERE op.order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderProductsListNoGood($order_id) {
			$query = $this->db->query("SELECT op.*, p.image, p.ean FROM order_product_nogood op LEFT JOIN product p ON op.product_id = p.product_id WHERE op.order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderOption($order_id, $order_option_id) {
			$query = $this->db->query("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");
			
			return $query->row;
		}
		
		public function getOrderOptions($order_id, $order_product_id) {
			$query = $this->db->query("SELECT oo.* FROM order_option AS oo LEFT JOIN product_option po USING(product_option_id) LEFT JOIN `option` o USING(option_id) WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "' ORDER BY o.sort_order");
			
			return $query->rows;
		}
		
		public function getOrderDownloads($order_id, $order_product_id) {
			$query = $this->db->query("SELECT * FROM order_download WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderVouchers($order_id) {
			$query = $this->db->query("SELECT * FROM order_voucher WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderVoucherByVoucherId($voucher_id) {
			$query = $this->db->query("SELECT * FROM `order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");
			
			return $query->row;
		}
		
		public function getOrderTotals($order_id, $code = false) {
			
			if ($code){
				$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' AND code = '" . $this->db->escape(trim($code)) . "' ORDER BY sort_order");
				
				return $query->row;
			}
			
			$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");			
			
			return $query->rows;
		}
		
		public function getTotalOrdersDiscounts($data = array()) {
			$sql = "SELECT SUM(ot.value_national) as sum FROM `order_total` ot";
			
			$sql .= " WHERE ot.value_national < 0 AND order_id IN ";
			
			$sql .= "(SELECT order_id FROM `order` o  WHERE preorder = 0 ";
			
			if (!empty($data['filter_date_added'])) {
				$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_date_added_to'])) {
				$sql .= " AND DATE(date_added) <= DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
			}

			if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
				if ($data['filter_order_status_id'] == 'except_cancelled') {
					$sql .= " AND (o.order_status_id > 0 AND o.order_status_id <> '" . $this->config->get('config_cancelled_status_id') . "')";	
				}					
			}
			
			if (!empty($data['filter_order_status_notnull'])){
				$sql .= " AND o.order_status_id > 0";
			}
			
			if (isset($data['filter_order_store_id']) && !is_null($data['filter_order_store_id'])) {
				$sql .= " AND o.store_id = '" . (int)$data['filter_order_store_id'] . "'";
			}
			
			$sql .= ")";
			
			$query = $this->db->query($sql);
			
			return ($query->num_rows)?$query->row['sum']:0;
		}
		
		
		public function getTotalOrders($data = array()) {
			$sql = "SELECT COUNT(DISTINCT o.order_id) AS total, SUM(o.total_national) as sum FROM `order` o";
			
			if ($this->config->get('ukrcredits_status')){
				$sql .= " LEFT JOIN order_ukrcredits ouc ON (o.order_id = ouc.order_id) ";
			}

			if (!empty($data['filter_order_id']) || !empty($data['filter_product_id'])) {
				if (!is_numeric($data['filter_order_id']) || !empty($data['filter_product_id'])){				
					$sql .= " LEFT JOIN order_product op ON op.order_id = o.order_id";
				}
			}			
			
			if (!empty($data['filter_customer']) || (!empty($data['filter_discount_card'])) || (!empty($data['filter_order_status_id']) && $data['filter_order_status_id'] == 'nbt_csi') || (!empty($data['filter_order_status_id']) && strpos($data['filter_order_status_id'], 'need_csi') !== false)) {
				$sql .= " LEFT JOIN customer c ON c.customer_id = o.customer_id";
			}
			
			if (!empty($data['filter_ttn'])){
				$sql .= " LEFT JOIN order_ttns ot ON ot.order_id = o.order_id";
			}
			
			if (!empty($data['filter_courier_status'])){
				$sql .= " LEFT JOIN order_courier_history och ON och.order_id = o.order_id AND och.date_added = (SELECT MAX(date_added) FROM order_courier_history och WHERE och.order_id = o.order_id)";
			}
			
			if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
				if ($data['filter_order_status_id'] == 'all_except_success'){
					$sql .= " WHERE (o.order_status_id > '0' AND o.order_status_id != '" . (int)$this->config->get('config_complete_status_id') . "')";
					} elseif ($data['filter_order_status_id'] == 'all_except_closed') {
					$sql .= " WHERE (o.order_status_id NOT IN (17, 23, 18, 24, 0))";
					} elseif ($data['filter_order_status_id'] == 'general_problems') {
					$sql .= " WHERE (o.order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ") OR ((o.probably_cancel=1 OR o.probably_close=1 OR o.probably_problem=1) AND o.order_status_id > '0'))";
					} elseif ($data['filter_order_status_id'] == 'flow_problems') {
					$sql .= " WHERE o.order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ")";
					} elseif ($data['filter_order_status_id'] == 'probably_cancel') {
					$sql .= " WHERE (o.order_status_id > '0' AND o.probably_cancel = 1)";
					} elseif ($data['filter_order_status_id'] == 'probably_close') {
					$sql .= " WHERE (o.order_status_id > '0' AND o.probably_close = 1)";
					} elseif ($data['filter_order_status_id'] == 'probably_problem') {
					$sql .= " WHERE (o.order_status_id > '0' AND o.probably_problem = 1)";
					} elseif ($data['filter_order_status_id'] == 'need_approve') {
					$sql .= " WHERE o.order_status_id IN (" . implode(',', $this->config->get('config_toapprove_order_status_id')) . ")";
					} elseif ($data['filter_order_status_id'] == 'overdue') {
					$sql .= " WHERE (o.urgent = 0 AND ((o.date_delivery_to != '0000-00-00' AND DATE(o.date_delivery_to) < DATE(NOW())) OR (o.date_delivery_actual != '0000-00-00' AND  DATE(o.date_delivery_actual) < DATE(NOW()))) AND o.order_status_id > 0 AND o.order_status_id NOT IN (" . implode(',', array($this->config->get('config_complete_status_id'), $this->config->get('config_cancelled_status_id'), 23)) . "))";
					} elseif ($data['filter_order_status_id'] == 'overdue_complete') {
					$sql .=	" WHERE (o.urgent = 0 AND (
					(o.date_delivery_to != '0000-00-00' AND DATE(o.date_delivery_to) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND oh2.order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1))
					OR (o.date_delivery_actual != '0000-00-00' AND  DATE(o.date_delivery_actual) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "' ORDER BY date_added DESC LIMIT 1))) 
					AND o.order_status_id > 0 AND o.order_status_id IN (" . implode(',', array($this->config->get('config_complete_status_id'))) . "))";
					} elseif ($data['filter_order_status_id'] == 'overdue_cancel') {
					$sql .=	" WHERE (o.urgent = 0 AND (
					(o.date_delivery_to != '0000-00-00' AND DATE(o.date_delivery_to) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND oh2.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' ORDER BY date_added DESC LIMIT 1))
					OR (o.date_delivery_actual != '0000-00-00' AND  DATE(o.date_delivery_actual) < (SELECT date_added FROM order_history oh2 WHERE oh2.order_id = o.order_id AND oh2.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' ORDER BY date_added DESC LIMIT 1))) 
					AND o.order_status_id > 0 AND o.order_status_id IN (" . implode(',', array($this->config->get('config_cancelled_status_id'))) . "))";
					} elseif ($data['filter_order_status_id'] == 'cancel_with_wait_for_payment') {
					$sql .= " WHERE (o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND o.order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . implode(',', array($this->config->get('config_confirmed_nopaid_order_status_id'))) . ")) AND o.order_id NOT IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . implode(',', array($this->config->get('config_total_paid_order_status_id'), (int)$this->config->get('config_prepayment_paid_order_status_id')))  . ")))";
					} elseif ($data['filter_order_status_id'] == 'cancel_with_payment') {
					$sql .= " WHERE (o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND o.order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . implode(',', array($this->config->get('config_total_paid_order_status_id'), (int)$this->config->get('config_prepayment_paid_order_status_id'))) . ")))";
					} elseif ($data['filter_order_status_id'] == 'except_cancelled') {
					$sql .= " WHERE (o.order_status_id > 0 AND o.order_status_id <> '" . $this->config->get('config_cancelled_status_id') . "')";		
					} elseif ($data['filter_order_status_id'] == 'cancel_with_process') {
					$sql .= " WHERE (o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND o.order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (2,19,25)))";				
					} elseif ($data['filter_order_status_id'] == 'birthday_discount_used') {
					$sql .= " WHERE (o.order_status_id > '0' AND (o.order_id IN (SELECT DISTINCT order_id FROM order_total WHERE code = 'discount_regular' OR (title LIKE '%Рождения%' OR title LIKE '%рождения%'))))";
					} elseif ($data['filter_order_status_id'] == 'has_csi') {
					$sql .= " WHERE (o.order_status_id > '0' AND (o.csi_average > 0 OR o.csi_reject = 1))";
					} elseif ($data['filter_order_status_id'] == 'nbt_csi') {
					$sql .= " WHERE (c.nbt_csi = 1)";
					} elseif (strpos($data['filter_order_status_id'], 'need_csi') !== false) {						
					$exploded 			= explode('_', $data['filter_order_status_id']);
					$order_id_single 	= (int)$exploded[2];
					
					$sql .= " WHERE (o.order_status_id = '" . (int)$order_id_single . "' AND o.csi_average = 0 AND o.csi_reject = 0 AND c.nbt_csi = 0)";
					} else {
					$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				}
				
				
				} else {
				$sql .= " WHERE o.order_status_id > 0";
			}
						
			if (!empty($data['filter_order_id'])) {				
				if (is_numeric($data['filter_order_id'])){
					$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
					} else {
					$sql .= " AND op.part_num LIKE ('%" . $this->db->escape($data['filter_order_id']) . "%')";
				}			
			}
			
			if (!empty($data['filter_discount_card']) && $data['filter_discount_card']){
				$sql .= " AND ((REPLACE(c.discount_card, ' ', '') LIKE '" . str_replace(' ', '', $this->db->escape($data['filter_discount_card'])) . "') OR c.discount_card LIKE '" . $this->db->escape($data['filter_discount_card']) . "')";
			}
			
			if (!empty($data['filter_ttn'])){
				$sql .= " AND REPLACE(REPLACE(REPLACE(ot.ttn, ' ', ''), '-', ''), '/', '')
				LIKE '" . $this->db->escape(str_replace(array(' ', '-','/',), array('','','',), $data['filter_ttn'])) . "%'";
			}
			
			if (!empty($data['filter_courier_status'])){
				$sql .= " AND och.status = '" . $this->db->escape($data['filter_courier_status']) . "'";
			}

			if (!empty($data['filter_is_credit_order'])){
				$sql .= " AND NOT ISNULL(ouc.ukrcredits_order_status)";
			}
			
			if (!empty($data['filter_shipping_method'])){
				$sql .= " AND o.shipping_code = '" . $this->db->escape($data['filter_shipping_method']) . "'";
			}
			
			if (!empty($data['filter_payment_method'])){
				$sql .= " AND o.payment_code = '" . $this->db->escape($data['filter_payment_method']) . "'";
			}
			
			if (!empty($data['filter_product_id'])) {
				$sql .= " AND (op.product_id = '" . (int)$data['filter_product_id'] . "'";
				$sql .= " OR op.model LIKE (SELECT pp.model FROM product pp WHERE pp.product_id = '" . (int)$data['filter_product_id'] . "' LIMIT 1))";
			}
			
			if (!empty($data['filter_referrer']) && $data['filter_referrer']){
				$sql .= " AND (LCASE(o.first_referrer) LIKE '%" . mb_strtolower($this->db->escape($data['filter_referrer'])) . "%' OR LCASE(o.last_referrer) LIKE '%" . mb_strtolower($this->db->escape($data['filter_referrer'])) . "%' )";
			}
			
			if (isset($data['filter_reject_reason_id']) && !is_null($data['filter_reject_reason_id'])) {
				$sql .= " AND o.reject_reason_id = '" . (int)$data['filter_reject_reason_id'] . "'";
			}
			
			if (isset($data['filter_affiliate_id']) && !is_null($data['filter_affiliate_id'])) {
				$sql .= " AND o.affiliate_id = '" . (int)$data['filter_affiliate_id'] . "'";
			}
			
			if (isset($data['filter_order_store_id']) && !is_null($data['filter_order_store_id'])) {
				$sql .= " AND o.store_id = '" . (int)$data['filter_order_store_id'] . "'";
			}
			
			if (!empty($data['filter_customer'])) {
				if (is_numeric($data['filter_customer'])){
					$sql .= " AND (o.customer_id = '" . (int)$data['filter_customer'] . "')";
					} else {
					$sql .= " AND (CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%' 
					OR o.email LIKE '%" . $this->db->escape($data['filter_customer']) . "%'
					OR REPLACE(c.discount_card, ' ', '') LIKE '" . $this->db->escape(str_replace(' ','', $data['filter_customer'])) . "'
					)";
				}
			}
			
			if (!empty($data['filter_date_added'])) {
				$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_date_added_to'])) {
				$sql .= " AND DATE(date_added) <= DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
			}
			
			if (!empty($data['filter_date_modified'])) {
				$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
			}
			
			if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id']) && !is_numeric($data['filter_order_status_id'])) {
				if (strpos($data['filter_order_status_id'], 'need_csi') !== false){					
					$sql .= " AND DATE(o.date_modified) >= DATE('" . date('Y-m-d', strtotime('-1 month')) . "') AND DATE(o.date_added) >= DATE('" . date('Y-m-d', strtotime('-2 month')) . "')";					
				}
			}
			
			if (!empty($data['filter_date_delivery'])) {
				$sql .= " AND DATE(date_delivery) = DATE('" . $this->db->escape($data['filter_date_delivery']) . "')";
			}
			
			if (!empty($data['filter_manager_id'])) {
				$sql .= " AND manager_id = '" . (int)$data['filter_manager_id'] . "'";
			}
			
			if (!empty($data['filter_affiliate_id'])) {
				$sql .= " AND affiliate_id = '" . (int)$data['filter_affiliate_id'] . "'";
			}
			
			if (!empty($data['filter_pwa'])){
				$sql .= " AND o.pwa = 1";
			}
			
			if (!empty($data['filter_yam'])){
				$sql .= " AND o.yam = 1";
			}
			
			if (!empty($data['filter_yam_id'])){
				$sql .= " AND o.yam_id = '" . $this->db->escape($data['filter_yam_id']) . "'";
			}

			if (!empty($data['filter_yam_express'])){
				$sql .= " AND o.yam_express = '" . (int)$data['filter_yam_express'] . "'";
			}

			if (!empty($data['filter_yam_campaign_id'])){
				$sql .= " AND o.yam_campaign_id = '" . (int)$data['filter_yam_campaign_id'] . "'";
			}
			
			if (!empty($data['filter_order_status_notnull'])){
				$sql .= " AND o.order_status_id > 0";
			}
			
			if (!empty($data['filter_urgent'])) {
				$sql .= " AND urgent = '1'";
			}
			
			if (!empty($data['filter_preorder'])) {
				$sql .= " AND o.preorder = '1'";
			}
			
			if (!empty($data['filter_not_preorder'])) {
				$sql .= " AND o.preorder = '0'";
			}
			
			if (!empty($data['filter_urgent_buy'])) {
				$sql .= " AND urgent_buy = '1'";
			}
			
			if (!empty($data['filter_wait_full'])) {
				$sql .= " AND wait_full = '1'";
			}
			
			if (!empty($data['filter_ua_logistics'])) {
				$sql .= " AND ua_logistics = '1'";
			}
			
			if (!empty($data['filter_total'])) {
				$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
			}				
			
			if ($this->user->isLogged() && $this->user->getManagerStores()){
				$sql .= " AND o.store_id IN (" . implode(',', $this->user->getManagerStores()) . ")";			
			}
			
			$query = $this->db->query($sql);
			
			if (!empty($data['return_sum'])){
				return $query->row['sum'];		
			}
			
			if (!empty($data['return_array'])){
				return array(
				'sum' => $query->row['sum'], 
				'total' => $query->row['total']
				);
			}
			
			return $query->row['total'];	
		}
		
		public function getTotalOrdersByStoreId($store_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE store_id = '" . (int)$store_id . "'");
			
			return $query->row['total'];
		}
		
		public function getCustomerIdByOrderId($order_id) {
			$query = $this->db->query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_id . "' LIMIT 1");
			
			return $query->row['customer_id'];
		}
		
		public function getOrderPhone($order_id) {
			$query = $this->db->query("SELECT telephone FROM `order` WHERE order_id = '" . (int)$order_id . "' LIMIT 1");
			
			return $query->row['telephone'];
		}
		
		public function getTotalOrdersByCustomerId($filter_customer) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE customer_id = '" . (int)$filter_customer . "' AND order_status_id > 0");
			
			return $query->row['total'];
		}
		
		public function getTotalOrdersByOrderStatusId($order_status_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrdersByLanguageId($language_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrdersByCurrencyId($currency_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");
			
			return $query->row['total'];
		}
		
		public function getTotalSales() {
			$query = $this->db->query("SELECT SUM(total) AS total FROM `order` WHERE order_status_id = '". $this->config->get('config_complete_status_id') . "'");
			
			return $query->row['total'];
		}
		
		public function getFirstOrderDate(){
			$query = $this->db->query("SELECT date_added FROM `order` WHERE order_status_id = '". $this->config->get('config_complete_status_id') . "' ORDER BY date_added ASC");
			
			if ($query->num_rows){
				return $query->row['date_added'];			
				} else {
				return date('Y-m-d');
			}
		}
		
		public function getTotalSalesByYear($year) {
			$query = $this->db->query("SELECT SUM(total) AS total FROM `order` WHERE order_status_id = '". $this->config->get('config_complete_status_id') . "' AND YEAR(date_added) = '" . (int)$year . "'");
			
			return $query->row['total'];
		}
		
		public function createInvoiceNo($order_id, $prefix = true) {
			$order_info  =  $this->getOrder((int)$order_id);
			
			if ($order_info && !$order_info['invoice_no']) {
				$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' AND shipping_country_id = '" . $order_info['shipping_country_id'] . "'");
				
				if ($query->row['invoice_no']) {
					$invoice_no = $query->row['invoice_no'] + 1;
					} else {
					$invoice_no = 1;
				}
				
				$this->db->query("UPDATE `order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "', invoice_date = NOW() WHERE order_id = '" . (int)$order_id . "'");
				
				if ($prefix) {		
					return $order_info['invoice_prefix'] . $invoice_no;				
					} else {
					return $invoice_no;				
				}
				} else {
				if ($prefix) {		
					return $order_info['invoice_prefix'] . $order_info['invoice_no'];
					} else {
					return $order_info['invoice_no'];	
				}
			}
		}
		
		public function updateLastOrderHistory($order_id, $data){
			
			$order_history_id = $this->getOrderLastHistoryID($order_id);
			
		}
		
		public function getIfProductIsOnStock($order_product_id){
			$query = $this->db->query("SELECT product_id FROM order_product WHERE order_product_id = '" . (int)$order_product_id . "' LIMIT 1");
			if ($query->row){
				$query2 = $this->db->query("SELECT product_id FROM product WHERE stock_product_id = '" . (int)$query->row['product_id'] . "' LIMIT 1");	
				
				if ($query2->row){
					
					$query3 = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$query2->row['product_id'] . "' AND category_id = '" . STOCK_CATEGORY . "'");	
					
					if ($query3->num_rows){
						return (int)$query2->row['product_id'];	
						} else {
						return false;					
					}
					
					} else {
					return false;
				}
				
				} else {
				return false;
			}										
		}
		
		public function addOrderHistory($order_id, $data) {
			$this->load->model('setting/setting');
			
			if ($this->getIfOrderClosed($order_id)){
				return false;
			}
			
			$this->db->query("UPDATE `order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
			
			$this->setOrderNoProblem($order_id);
			
			$this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape($data['comment']) . "', courier = '" . (isset($data['courier']) ? (int)$data['courier'] : 0) . "',  date_added = NOW(), user_id = '" . (int)$this->user->getId() . "'");
			
			$order_history_id = $this->db->getLastId();
			
			$to_courier = (isset($data['courier']) ? (int)$data['courier'] : 0);
			
			$order_info = $this->getOrder($order_id);
			
			$this->load->model('module/affiliate');
			$this->model_module_affiliate->validateHistory($order_id, $order_info, $data);
			
			// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $data['order_status_id']) {
				$this->load->model('sale/voucher');
				
				$results = $this->getOrderVouchers($order_id);
				
				foreach ($results as $result) {
					$this->model_sale_voucher->sendVoucher($result['voucher_id']);
				}
			}
			
			//manager
			if ($this->user->isLogged() && $this->user->getOwnOrders()){
				$manager_query = $this->db->query("SELECT manager_id FROM `order` WHERE order_id = '" . (int)$order_id . "' AND manager_id > 0");
				if (!($manager_query->rows)){						
					$this->db->query("UPDATE `order` SET manager_id = '" . (int)$this->user->getId() . "' WHERE order_id = '" . (int)$order_id . "'");		
				}
			}
			
			//reassign manager_id ON submit order
			if ($this->user->isLogged() && $this->user->getOwnOrders()){
				
				if (in_array($data['order_status_id'], array($this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id')))){
					
					$count_query = $this->db->query("SELECT count(*) as total FROM `order_history` WHERE 
					order_status_id IN (". $this->config->get('config_confirmed_order_status_id') . "," . $this->config->get('config_confirmed_nopaid_order_status_id') .") AND order_id = '" . (int)$order_id . "'");
					
					if ($count_query->row['total'] == 1){				
						$this->db->query("UPDATE `order` SET manager_id = '" . (int)$this->user->getId() . "' WHERE order_id = '" . (int)$order_id . "'");
					}
				}
				
			}
			
			if ($this->user->isLogged()) {
				$this->db->query("INSERT INTO `order_save_history` SET 
				`order_id` = '" . (int)$order_id . "', 
				`user_id` = '" . (int)$this->user->getId() . "',
				`datetime` = NOW()
				");
			}
			
			if ($data['order_status_id'] == $this->config->get('config_complete_status_id') || $data['order_status_id'] == $this->config->get('config_cancelled_status_id')) {
				
				$order_product = $this->model_sale_order->getOrderProducts($order_id);
				$sub_total = $this->getOrderTotals($order_id, 'sub_total');
				
				//Обнуление цифр в случае если товаров в заказе нет
				if (count($order_product) == 0 && $sub_total['value'] == 0){
					$this->db->query("UPDATE order_total SET value = 0, value_national = 0, text = '" . $this->currency->format(0, $data['currency_code'], '1') . "' WHERE order_id = '" . (int)$order_id . "' AND code <> 'shipping'");
					$this->db->query("UPDATE `order` SET total = '" . (float)$total . "', total_national = '" . (float)$total_national . "' WHERE order_id = '" . (int)$order_id . "'");
				}
			}
			
			//Заявка перестает быть заявкой, в случае если задан статус подтверждения
			if (in_array($data['order_status_id'], 
			[
			$this->config->get('config_complete_status_id'), 
			$this->config->get('config_confirmed_order_status_id'),
			$this->config->get('config_confirmed_nopaid_order_status_id'),
			$this->config->get('config_prepayment_paid_order_status_id'),
			$this->config->get('config_total_paid_order_status_id')
			])){
				
				$this->db->query("UPDATE `order` SET preorder = 0 WHERE order_id = '" . (int)$order_id . "'");	
			}
			
			
			
			//Бонусы
			if ($data['order_status_id'] == $this->config->get('config_complete_status_id')){			
				//Считаем сумму начисляемых бонусов
				$reward_query = $this->db->query("SELECT SUM(reward) as total FROM order_product WHERE order_id = '" . $order_id . "'");
				
				if ($reward_query->num_rows && $reward_query->row['total']){
					$description = sprintf($this->language->getCatalogLanguageString($order_info['language_id'], 'total/reward', 'text_reward_add_description'), $order_id);
					
					$this->customer->addRewardToQueue($order_info['customer_id'], $description, $reward_query->row['total'], $order_id, 'ORDER_COMPLETE_ADD');
				}
			}
			
			//Полная отмена
			if ($data['order_status_id'] == $this->config->get('config_cancelled_status_id')){
				
				//Очищаем все начисления по текущему заказу
				$this->customer->clearRewardTableByOrder($order_info['customer_id'], $order_id);
				
				//Очищаем очередь начислений, в случае отмены после выполнения
				$this->customer->clearRewardQueueByOrder($order_info['customer_id'], $order_id);
				
				//Ищем списание по данному заказу и возвращаем такую же сумму на бонусный счет покупателя
				$pointsPaidForThisOrder = $this->customer->getRewardReservedByOrder($order_info['customer_id'], $order_id);
				
				
				if ($pointsPaidForThisOrder > 0){
					$description = sprintf($this->language->getCatalogLanguageString($order_info['language_id'], 'total/reward', 'text_reward_return_description'), $order_id);
					
					$this->customer->addReward($order_info['customer_id'], $description, $pointsPaidForThisOrder, $order_id, 'ORDER_RETURN');
				}
				
			}

			$this->customer->addToEMAQueue($order_info['customer_id']);
			
			//YANDEX MARKET
			if ($order_info['yam'] && $order_info['yam_id']){
				$this->load->model('api/yamarket');
				$this->model_api_yamarket->addToQueue($order_id, $data['order_status_id']);						
			}
			
			
			//send comment to courier
			if (mb_strlen($data['comment']) > 1){
				$this->load->model('user/user');
				$this->load->model('localisation/country');
				
				$_manager_name = $this->model_user_user->getRealUserNameById($this->user->getId());
				$_country = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
				
				$is_buyer = (in_array($this->user->getUserGroup(), array(15)));
				$is_sales = (in_array($this->user->getUserGroup(), array(12)));
				
				$_subject = $_country['name'] . ' : Заказ #' . $order_id . ' : ' . $_manager_name;
				
				$_html = 'Заказ : <b>'. $order_id .'</b><br />';
				$_html .= 'Время : <b>'. date('Y-m-d') . ' в ' . date('H:i:s') .'</b><br />';
				
				$_order_status_query = $this->db->query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
				
				
				if ($_order_status_query->num_rows) {				
					$_html .= 'Статус заказа : <b>' . $_order_status_query->row['name'] . '</b><br />';
				}
				
				$_html .= 'Комментарий : <b>'. strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) .'</b><br />';			
				$_html .= 'Оставил : <b>' . $_manager_name . '</b><br />';
				
				$_log = new Log('order_courier_alerts.txt');
				$_log->write($order_id . ' : ' . $_manager_name . ' : ' . strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')));
				
				$alert_data = array(
				'type' => 'warning',
				'text' => $_manager_name.' оставил комментарий. <br />к заказу', 
				'entity_type' => 'order', 
				'entity_id'=>$order_id
				);
				
				$this->mAlert->insertAlertForGroup('sales', $alert_data);
				
				if ($to_courier) {
					
					if ($is_sales) {
						$this->mAlert->insertAlertForGroup('couriers', $alert_data);
					}
					
					if ($is_sales) {
						$_mail = new Mail($this->registry); 						
						$_mail->setTo($this->config->get('config_courier_mail_to'));
						$_mail->setFrom($this->config->get('config_courier_mail_to'));
						$_mail->setSender($this->config->get('config_courier_mail_to'));
						$_mail->setSubject(html_entity_decode($_subject, ENT_QUOTES, 'UTF-8'));
						$_mail->setHTML(html_entity_decode($_html, ENT_QUOTES, 'UTF-8'));
						$_mail->send();
					}
					
				}
				
			}
			
			
			if ($data['notify']) {
				
				$template = new EmailTemplate($this->request, $this->registry);
				
				$template->data['payment_address'] = EmailTemplate::formatAddress($order_info, 'payment', $order_info['payment_address_format']);
				
				$template->data['shipping_address'] = EmailTemplate::formatAddress($order_info, 'shipping', $order_info['shipping_address_format']);			
				if($template->data['shipping_address'] == ''){
					$template->data['shipping_address'] = $template->data['payment_address'];
				}
				
				$template->data['products'] = array();  
				if(isset($data['show_products'])){ 
					$this->load->model('tool/image');  
					$this->load->model('catalog/product');  
					$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);	
					
					foreach ($products as $product) {
						$product = array_merge($this->model_catalog_product->getProduct($product['product_id']), $product);
						
						// Product Options
						$option_data = array();	
						$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);	
						foreach ($options as $option) {
							if ($option['type'] != 'file') {
								$option_data[] = array(
								'name'  => $option['name'],
								'value' => $option['value'],
								'type'  => $option['type']
								);
								} else {
								$option_data[] = array(
								'name'  => $option['name'],
								'value' => substr($option['value'], 0, strrpos($option['value'], '.')),
								'type'  => $option['type'],
								'href'  => $this->url->link('sale/order/download', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&order_option_id=' . $option['order_option_id'], 'SSL')
								);						
							}
						}
						
						if (isset($product['image'])) {
							$image = $this->model_tool_image->resize($product['image'], 50, 50);
							} else {
							$image = '';
						}
						
						$url = $order_info['store_url'] . '?route=product/product&product_id='.$product['product_id'];
						
						if ($product['price_national'] > 0){
							$price = $this->currency->format($product['price_national'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], '1');
							} else {
							$price = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
						}
						
						if ($product['total_national'] > 0){
							$total = $this->currency->format($product['total_national'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');					
							} elseif ($product['price_national'] > 0) {
							$total = $this->currency->format($product['price_national']  * $product['quantity']  + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], '1');
							} else {
							$total = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
						}
						
						
						$template->data['products'][] = array(
						'url'     		=> $url,
						'url_tracking' 	=> $template->getTracking($url),
						'order_product_id' => $product['order_product_id'],
						'product_id'       => $product['product_id'],
						'name'    	 	   => $product['name'],
						'model'    		   => $product['model'],
						'image'    		   => $image,
						'option'   		   => $option_data,
						'quantity'		   => $product['quantity'],
						'price'    		   => $price,
						'total'    		   => $total,
						'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
						);
					}
				} // Products
				
				$template->data['vouchers'] = array();
				if(isset($data['show_vouchers'])){
					$vouchers = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);			 
					foreach ($vouchers as $voucher) {
						$template->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
						'href'        => $this->url->link('sale/voucher/update', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
						);
					}
				} // Vouchers
				
				$template->data['totals'] = array();
				if(isset($data['show_totals'])){
					$template->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
				} // Totals	
				
				$template->data['downloads'] = array();
				if(isset($data['show_downloads'])){				
					foreach ($products as $product) {
						$results = $this->model_sale_order->getOrderDownloads($this->request->get['order_id'], $product['order_product_id']);	
						foreach ($results as $result) {
							$template->data['downloads'][] = array(
							'name'      => $result['name'],
							'filename'  => $result['mask'],
							'remaining' => $result['remaining']
							);
						}
					}
				} // Downloads	
				
				$attachments = array();
				if(isset($data['attach_invoice_pdf'])){
					$this->load->model('module/emailtemplate/invoice');
					$template->data['emailtemplate_invoice_pdf'] = $this->model_module_emailtemplate_invoice->getInvoice($this->request->get['order_id'], true);
					$attachments[] = $template->data['emailtemplate_invoice_pdf'];
				}
				
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_filename']);
				$language->load('mail/order');
				
				$subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);
				
				$message  = $language->get('text_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_order_status') . "\n";
					$message .= $order_status_query->row['name'] . "\n\n";
				}
				
				if ($order_info['customer_id']) {
					$message .= $language->get('text_link') . "\n";
					$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
				}
				
				if ($data['comment']) {
					$message .= $language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}
				
				$message .= $language->get('text_footer');
				
				if (!empty($template->data['products'])) {
					$message .= "\n" . $language->get('text_products') . "\n"; 
					foreach ($template->data['products'] as $product) {
						$message .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($product['total'], ENT_NOQUOTES, 'UTF-8') . "\n";
						foreach ($product['option'] as $option) {
							$message .= chr(9) . '-' . $option['name'] . ' ' . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
						}
					}
				}
				
				if (!empty($template->data['vouchers'])) { 
					foreach ($template->data['vouchers'] as $voucher) {
						$message .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
					}
				}
				
				if (!empty($template->data['totals'])) {
					$message .= "\n" . $language->get('text_total') . "\n";	
					foreach ($template->data['totals'] as $total) {
						$message .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
					}
				}
				
				if (!empty($template->data['downloads'])) {
					$message .= "\n" . $language->get('text_download') . "\n";
					$message .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
				}
				
				$template->addData($data);
				
				$template->addData($order_info);
				
				$template->data['new_comment'] = html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8');
				$template->data['invoice'] = html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8');
				$template->data['invoice_tracking'] = $template->getTracking($template->data['invoice']);
				
				$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));
				
				if ($order_status_query->num_rows) {
					$template->data['order_status'] = $order_status_query->row['name']; 
				}
				
				$mail = new Mail($this->registry); 
				$mail->setTo($order_info['email']);
				
				$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
				$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$template_data = array('key' =>'admin.order_update');
				if(!empty($data['field_template'])){
					$template_data['emailtemplate_id'] = $data['field_template'];
				}
				if(!empty($data['order_status_id'])){
					$template_data['order_status_id'] = $data['order_status_id'];
				}
				if(!empty($data['customer_id'])){
					$template_data['customer_id'] = $data['customer_id'];
				}
				if(isset($order_info['store_id'])){
					$template_data['store_id'] = $order_info['store_id'];
				}
				if(isset($order_info['language_id'])){
					$template_data['language_id'] = $order_info['language_id'];
				}
				
				$template->load($template_data);
				
				$mail = $template->hook($mail);
				foreach($attachments as $attachment){
					$mail->addAttachment($attachment);
				} 
				
				$mail->setTo($order_info['email']);
				$mail->send();
				
				$template->sent();
			}

			if ($this->config->get('config_sms_status_use_only_settings')){
				$this->load->model('localisation/legalperson');

				$sms_data = [
					'order_status_id' 	=> $data['order_status_id'],
					'comment' 			=> '',
					'payment_info' 		=> $this->model_localisation_legalperson->getLegalPersonInfo($order_info['card_id']),
					'order_status_name' => $this->getStatusName($data['order_status_id']),
					'pickup_name' 		=> trim($order_info['shipping_method']),
				];

				if ($this->getOrderPrepayNational($order_info['order_id'])){
					$sms_data['partly'] = $this->language->get('text_payment_partly');
					$sms_data['amount'] = $this->getOrderPrepayNational($order_info['order_id']);
				} else {
					$sms_data['partly'] = '';
					$sms_data['amount'] = $order_info['total_national'];
				}

				if (in_array($order_info['payment_code'], $this->getEquiringMethods())){
					$sms_data['payment_link'] = $this->generatePaymentLink($order_info['order_id'], $order_info['payment_code']);
				} else {
					$sms_data['payment_link'] = '';
				}

				if (bool_real_stripos($order_info['shipping_code'], 'pickup_advanced')){	
					if ($order_info['store_id'] == 0){
						$sms_data['pickup_url'] = HTTP_CATALOG . 'pick' . ((int)str_replace('pickup_advanced.point_', '', $order_info['shipping_code']) + 1);
					} else {
						$sms_data['pickup_url']  = $this->model_setting_setting->getKeySettingValue('config', 'config_url', $order_info['store_id']) . 'pick' . ((int)str_replace('pickup_advanced.point_', '', $order_info['shipping_code']) + 1);
					}
				} else {
					$sms_data['pickup_url']  = '';
				}

				$this->smsAdaptor->sendStatusSMSText($order_info, $sms_data);

			} else {
				if (!empty($this->request->post['notify']) && !empty(trim($this->request->post['history_sms_text']))){
					$this->smsAdaptor->sendSMS(['to' => $order_info['telephone'], 'message' => trim($this->request->post['history_sms_text'])]);
				}
			}			
			
			
			$_total_query = $this->db->query("SELECT total_national FROM `order` WHERE `order_id` = '" . (int)$order_id . "'");
			$_total_total = $_total_query->row['total_national'];
			
			if (in_array((int)$data['order_status_id'], $this->config->get('config_odinass_order_status_id')) /* && $_total_total > 0 */){
				$check_date_query = $this->db->query("SELECT date_added FROM `order` WHERE `order_id` = '" . (int)$order_id . "' AND DATE(date_added) >= '2017-01-01'");
				
				if (count($check_date_query->row)){										
					$this->Fiscalisation->addOrderToQueue($order_id);
				}
			}
			
			//lock order
			if ($data['order_status_id'] == $this->config->get('config_complete_status_id') || $data['order_status_id'] == $this->config->get('config_cancelled_status_id')){
				$this->db->query("UPDATE `order` SET closed = 1 WHERE order_id = '" . (int)$order_id . "'");
			}
			
		}

		public function getOrderPrepayNational($order_id){
			$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
			
			foreach ($query->rows as $row){
				if ($row['code'] == 'transfer_plus_prepayment'){
					return $row['value_national'];				
				}			
			}
			return false;
		}
		
		public function getOrderPrepayNationalPercent($order_id){
			$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
			
			foreach ($query->rows as $row){
				if ($row['code'] == 'transfer_plus_prepayment'){
					$p = explode('(',$row['title']);
					$p = $p[1];
					$p = explode('%', $p);
					$p = (int)$p[0];
					
					return $p;
				}
			}
			return false;
		}
		
		public function getOrderHistoriesCommentsForCourier($order_id) {			
			$query = $this->db->query("SELECT comment FROM order_history oh WHERE oh.order_id = '" . (int)$order_id . "' AND courier = 1 ORDER BY oh.date_added ASC");					
			return $query->rows;			
		}
		
		public function getOrderHistories($order_id, $start = 0, $limit = 30) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 30;
			}	
			
			$query = $this->db->query("SELECT oh.date_added, oh.order_status_id, os.name AS status, oh.comment, oh.notify, oh.courier, oh.user_id FROM order_history oh LEFT JOIN order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getOrderLastHistory($order_id){
			
			$query = $this->db->query("SELECT order_status_id FROM order_history WHERE order_id = '" . (int)$order_id . "' ORDER BY date_added DESC LIMIT 1");
			
			if ($query->row){
				return (int)$query->row['order_status_id'];
				} else {
				return 0;
			}
			
		}
		
		public function getOrderLastHistoryID($order_id){
			
			$query = $this->db->query("SELECT order_history_id FROM order_history WHERE order_id = '" . (int)$order_id . "' ORDER BY date_added DESC LIMIT 1");
			
			if ($query->row){
				return (int)$query->row['order_history_id'];
				} else {
				return 0;
			}
			
		}
		
		
		public function getOrderLastStatusName($order_id){
			
			$query = $this->db->query("SELECT os.name FROM `order` o 
			LEFT JOIN order_status os ON o.order_status_id = os.order_status_id 
			WHERE o.order_id = '" . (int)$order_id . "' 
			AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			if ($query->row){
				return 'Заказ ' . (int)$order_id . ' ' . $query->row['name'];
				} else {
				return 'Нет такого заказа, сожалею';
			}
		}
		
		public function countOrdersTotalOnDate($date){
			
			$query = $this->db->query("SELECT count(*) as total FROM `order` WHERE DATE(date_added) = '" . $this->db->escape($date) . "' AND order_status_id <> 0");
			
			return $query->row['total'];
			
		}
		
		public function getOrderSaveHistory($order_id){
			$query = $this->db->query("SELECT * FROM order_save_history WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderInvoiceHistory($order_id){
			$query = $this->db->query("SELECT * FROM order_invoice_history WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderLastInvoiceHistory($order_id){
			$query = $this->db->query("SELECT * FROM order_invoice_history WHERE auto_gen = 0 AND order_id = '" . (int)$order_id . "' ORDER BY datetime DESC LIMIT 1");	
			
			if (!$query->num_rows){
				$query = $this->db->query("SELECT * FROM order_invoice_history WHERE auto_gen = 1 AND order_id = '" . (int)$order_id . "' ORDER BY datetime DESC LIMIT 1");
			}
			
			return $query->row;
		}
		
		
		public function getTotalOrderHistories($order_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM order_history WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}	
		
		public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM order_history WHERE order_status_id = '" . (int)$order_status_id . "'");
			
			return $query->row['total'];
		}
		
		public function getStatusName($order_status_id) {
			$query = $this->db->query(
			"SELECT name
			FROM order_status
			WHERE order_status_id = '" . (int)$order_status_id . "'
			AND language_id = '" . (int)$this->config->get('config_language_id') . "'
			LIMIT 1"
			);
			return $query->row['name'];
		}
		
		public function getEmailsByProductsOrdered($products, $start, $end) {
			$implode = array();
			
			foreach ($products as $product_id) {
				$implode[] = "op.product_id = '" . (int)$product_id . "'";
			}
			
			$query = $this->db->query("SELECT DISTINCT email, o.order_id, o.customer_id, o.store_id, o.language_id FROM `order` o LEFT JOIN order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);
			
			return $query->rows;
		}
		
		public function getTotalEmailsByProductsOrdered($products) {
			$implode = array();
			
			foreach ($products as $product_id) {
				$implode[] = "op.product_id = '" . (int)$product_id . "'";
			}
			
			$query = $this->db->query("SELECT DISTINCT email FROM `order` o LEFT JOIN order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");
			
			return $query->row['total'];
		}
		
		public function getOrderCourierAllStatuses(){
			$query = $this->db->query("SELECT DISTINCT status FROM order_courier_history WHERE 1");
			
			$data = array();
			foreach ($query->rows as $row){
				$data[] = $row['status'];
			}
			
			return $data;
		}
		
		public function getOrderCourierAllShippingMethods(){
			$query = $this->db->query("SELECT DISTINCT(shipping_code), shipping_method FROM `order` WHERE LENGTH(shipping_code)>3 AND LENGTH(shipping_method)>3 AND DATE(date_added) >= DATE_SUB(NOW(), INTERVAL 3 MONTH) AND order_status_id > 0 GROUP BY shipping_code");
			
			$data = array();
			foreach ($query->rows as $row){
				$data[] = array(
				'name' => $row['shipping_method'] . ' (' . $row['shipping_code'] . ')',
				'code' => $row['shipping_code']
				);
			}
			
			return $data;
		}
		
		public function getOrderCourierAllPaymentMethods(){
			$query = $this->db->query("SELECT DISTINCT(payment_code), payment_method FROM `order` WHERE LENGTH(payment_code)>2 AND LENGTH(payment_method)>2 AND DATE(date_added) >= DATE_SUB(NOW(), INTERVAL 3 MONTH) AND order_status_id > 0 GROUP BY payment_code");
			
			$data = array();
			foreach ($query->rows as $row){
				$data[] = array(
				'name' => $row['payment_method'] . ' (' . $row['payment_code'] . ')',
				'code' => $row['payment_code']
				);
			}
			
			return $data;
		}
		
		public function getOrdersByManager($manager_id){
			$query = $this->db->query("SELECT COUNT(*) as total FROM `order` WHERE manager_id = '". (int)$manager_id ."'");
			
			$total = $query->row['total'];		
			
			$query = $this->db->query("SELECT SUM(`total`) as total, `currency_code`, `currency_value` FROM `order` WHERE manager_id = '". (int)$manager_id ."' GROUP BY currency_code");
			
			$info = array(
			'total' => 	$total,
			'total_info' => $query->rows 
			);
			
			return $info;		
		}
		
		
		public function addOrderSmsHistory($order_id, $data, $sms_status, $sms_id, $customer_id = 0) {
			
			if ($this->getIfOrderClosed($order_id)){
				return false;
			}
			
			$this->db->query("UPDATE `order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
			
			$this->db->query("INSERT INTO `order_sms_history` SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', comment = '" . $this->db->escape(strip_tags($data['sms'])) . "', date_added = NOW(), sms_status = '" . $this->db->escape(strip_tags($sms_status)) . "', sms_id = '".$this->db->escape($sms_id)."'");
			
			if ($customer_id){
				$this->db->query("INSERT INTO `customer_history` SET
				customer_id = '" . (int)$customer_id . "',
				order_id = '" . (int)$order_id . "', 
				comment = 'Отправлено SMS',
				sms_id = '".$this->db->escape($sms_id)."',
				date_added = NOW()");				
			}
			
		}  
		
		public function addOrderSmsDeliveryHistory($order_id, $data, $sms_status, $sms_id) {			
			$query = $this->db->query("INSERT INTO `order_sms_history` SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', comment = '" . $this->db->escape(strip_tags($data['sms'])) . "', date_added = NOW(), sms_status = '" . $this->db->escape(strip_tags($sms_status)) . "', sms_id = '".$this->db->escape($sms_id)."', is_ttn = '1'");		 			
		}   
		
		public function getOrderDeliverySMS($order_id) {
			$query = $this->db->query("SELECT date_added FROM `order_sms_history` WHERE order_id = '" . (int)$order_id . "' AND is_ttn = 1 LIMIT 1");
			
			if ($query->row){
				return $query->row['date_added'];
				} else {
				return false;
			}
		}
		
		public function getOrderTtnHistory($order_id){
			$query = $this->db->query("SELECT * FROM `order_ttns` WHERE `order_id` = '" . (int)$order_id."'");
			
			return $query->rows;		
		}
		
		public function getLinkedOrders($order_id){
			$query = $this->db->query("SELECT order_id, order_id2 FROM `order` WHERE `order_id2` LIKE('" . (int)$order_id ."-%" ."')");
			
			return $query->rows;		
		}
		
		public function getRelatedOrders($order_id){
			$query = $this->db->query("SELECT related_order_id FROM `order_related` WHERE order_id = '" . (int)$order_id . "'");
			
			$_data = array();
			
			foreach ($query->rows as $row){
				$_data[] = (int)$row['related_order_id'];
			}
			
			return $_data;		
		}
		
		public function getPossibleRelatedOrders($customer_id, $order_id){
			$query = $this->db->query("SELECT order_id FROM `order` WHERE order_id <> '" . (int)$order_id . "' AND order_status_id NOT IN (0,'" . $this->config->get('config_complete_status_id') . "', '" . $this->config->get('config_cancelled_status_id') . "') AND customer_id = '". (int)$customer_id ."'");	
			
			$_data = array();
			
			foreach ($query->rows as $row){
				$_data[] = (int)$row['order_id'];
			}
			
			return $_data;	
		}
		
		
		public function getInvoiceLog($id){
			$query = $this->db->query("SELECT * FROM `order_invoice_history` WHERE `order_invoice_id` = '" . (int)$id."' LIMIT 1");
			
			return $query->row;						
		}
		
		
		public function getOrderSmsHistories($order_id, $start = 0, $limit = 10) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 10;
			}	
			
			$query = $this->db->query("SELECT 
			osh.date_added, 
			os.name AS status, 
			osh.comment, 
			osh.sms_status, 
			osh.sms_id FROM `order_sms_history` osh 
			LEFT OUTER JOIN order_status os ON osh.order_status_id = os.order_status_id 
			WHERE osh.order_id = '" . (int)$order_id . "' AND 
			(os.language_id = '" . (int)$this->config->get('config_language_id') . "' OR osh.order_status_id = 0) 
			ORDER BY osh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getOrderCourierHistories($order_id, $start = 0, $limit = 10) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 10;
			}	
			
			$query = $this->db->query("SELECT 
			* FROM `order_courier_history` och 	
			WHERE och.order_id = '" . (int)$order_id . "'		
			ORDER BY och.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		public function getTotalOrderSmsHistories($order_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `order_sms_history WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}
		
		public function deleteOrderHistory($order_id, $order_history_id) {
			$this->load->model('sale/customer');
			$this->load->model('user/user');
			
			if ($this->getIfOrderClosed($order_id)){
				return $order_id;
			}
			
			$current_status_query = $this->db->query("SELECT oh.order_status_id, os.name FROM order_history oh 
			LEFT JOIN order_status os ON (oh.order_status_id = os.order_status_id AND os.language_id = 2) 
			WHERE order_id = '" . (int)$order_id . "'");
			$current_status_id = $current_status_query->row['order_status_id'];
			$current_status_name = $current_status_query->row['name'];
			
			$this->db->query("DELETE FROM order_history WHERE order_id = '" . (int)$order_id . "' AND order_history_id='" . (int)$order_history_id . "'");
			
			$last_id_query = $this->db->query("SELECT MAX(order_history_id) as id  FROM order_history WHERE order_id = '" . (int)$order_id . "'");		
			
			$last_status_query = $this->db->query("SELECT oh.order_status_id, os.name FROM order_history oh
			LEFT JOIN order_status os ON (oh.order_status_id = os.order_status_id AND os.language_id = 2) 
			WHERE order_id = '" . (int)$order_id . "' AND order_history_id = '" . (int)$last_id_query->row['id'] . "'");			
			$last_status_id = $last_status_query->row['order_status_id'];
			$last_status_name = $last_status_query->row['name'];			
			
			$this->db->query("UPDATE `order` set order_status_id='" . (int)$last_status_id . "' WHERE order_id = '" . (int)$order_id . "'");
			
			//get customer_id
			$customer_query = $this->db->query("SELECT customer_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			$customer_comment_text = "Удален статус заказа $current_status_name, новый статус: $last_status_name";
			
			$_manager_name = $this->model_user_user->getRealUserNameById($this->user->getId());
			$_log = new Log('order_delete_history.txt');
			$_log->write($order_id . ' : ' . $_manager_name . ' : ' . " удалил историю $current_status_name, новый статус $last_status_name");
			
			$_data = array(
			'customer_id' 		      => $customer_query->row['customer_id'], 
			'comment'     		      => $customer_comment_text,
			'order_id'    		      => $order_id,
			'call_id'     		      => 0,
			'manager_id'    	      => 0,
			'need_call'    			  => 0,
			'segment_id'    		  => 0,
			'order_status_id'    	  => $last_status_id,
			'prev_order_status_id'    => $current_status_id,
			'is_error'                => true
			);
			$this->model_sale_customer->addHistoryExtended($_data);
		}
		
		public function getOrderHistoryLastStatus($order_id,$language_id) {
			$last_status_name_query=$this->db->query("SELECT os.name from `order` o inner join order_status os on (os.order_status_id=o.order_status_id) WHERE o.order_id = '" . $order_id . "' and os.language_id='" . $language_id . "'");
			if ($last_status_name_query!=null)
			return $last_status_name_query->row['name'];
		}	
		
		public function getOrderHistories2($order_id, $start = 0, $limit = 10) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 10;
			}	
			
			$query = $this->db->query("SELECT oh.order_history_id, oh.date_added, os.name AS status, os.status_bg_color, os.status_fa_icon, os.status_txt_color, oh.order_status_id, oh.yam_status, oh.yam_substatus, oh.comment, oh.courier, oh.notify, oh.user_id FROM order_history oh LEFT JOIN order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
			
			return $query->rows;
		}
		
		
		public function getProblemOrdersCount(){
			
			$query = $this->db->query("
			SELECT COUNT(DISTINCT order_id) as count FROM `order` WHERE (order_status_id IN (" . implode(',', $this->config->get('config_problem_order_status_id')) . ") OR probably_cancel=1 OR  probably_close=1 OR probably_problem=1) 
			AND manager_id = '" . $this->user->getID() . "' AND DATE(date_modified) <= SUBDATE(NOW(), INTERVAL 1 DAY) ");
			
			return $query->row['count'];
			
		}
		
		public function getToApproveOrdersCount(){
			
			$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as count FROM `order` WHERE order_status_id IN (" . implode(',', $this->config->get('config_toapprove_order_status_id')) . ") AND DATE(date_modified) <= SUBDATE(NOW(), INTERVAL 1 DAY)");
			
			return $query->row['count'];
			
		}
		
		public function getIfProductIsFromStockCategoryRU($product_id){
			
			$query = $this->db->query("SELECT category_id FROM product_to_category WHERE product_id = (SELECT product_id FROM product WHERE stock_product_id = '" . (int)$product_id . "' AND status = 1 LIMIT 1) AND category_id = 6474");
			
			return ($query->num_rows?true:false);
			
		}
		
		public function setOrderNoProblem($order_id){
			
			$probably_cancel = false;
			$probably_cancel_reason = '';
			$probably_close = false;
			$probably_close_reason = '';
			$probably_problem = false;
			$probably_problem_reason = '';
			
			$this->db->query("UPDATE `order` SET 
			probably_cancel = '" . (int)$probably_cancel .  "',
			probably_cancel_reason = '" . $this->db->escape($probably_cancel_reason) .  "',
			probably_close = '" . (int)$probably_close .  "',
			probably_close_reason = '" . $this->db->escape($probably_close_reason) .  "',
			probably_problem = '" . (int)$probably_problem .  "',
			probably_problem_reason = '" . $this->db->escape($probably_problem_reason) .  "'
			WHERE order_id = '" . $order_id . "'
			");
			
		}
		
		public function getCouponName($title){			
			$coupon_name = explode('(', $title);
			$coupon_name = $coupon_name[1];
			$coupon_name = trim(str_replace(')', '', $coupon_name));

			if ($codeRandom = $this->couponRandom->getCouponRandomParent($coupon_name)){
				$coupon_name = $codeRandom;
			}				

			return $coupon_name;							
		}
		
		public function getCouponProducts($coupon, $product_ids = array(), $order_id = false, $has_birthday_discount_explicit = false){
			
			$coupon_query = $this->db->query("SELECT * FROM `coupon` WHERE code = '" . $this->db->escape($coupon) . "'");
			
			if ($coupon_query->num_rows) {
				
				// Products
				$coupon_product_data = array();
				
				$coupon_product_query = $this->db->query("SELECT * FROM `coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_product_query->rows as $product) {
					$coupon_product_data[] = $product['product_id'];
				}
				
				// Categories
				$coupon_category_data = array();
				
				$coupon_category_query = $this->db->query("SELECT * FROM `coupon_category` cc LEFT JOIN `category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_category_query->rows as $category) {
					$coupon_category_data[] = $category['category_id'];
				}
				
				//collections
				$coupon_collection_data = array();
				
				$coupon_collection_query = $this->db->query("SELECT * FROM `coupon_collection` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_collection_query->rows as $collection) {
					$coupon_collection_data[] = $collection['collection_id'];
				}
				
				//manufacturers
				$coupon_manufacturer_data = array();
				
				$coupon_manufacturer_query = $this->db->query("SELECT * FROM `coupon_manufacturer` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_manufacturer_query->rows as $manufacturer) {
					$coupon_manufacturer_data[] = $manufacturer['manufacturer_id'];
				}
				
				$product_data = array();
				
				if ($coupon_product_data || $coupon_category_data || $coupon_manufacturer_data || $coupon_collection_data) {
					foreach ($product_ids as $product) {
						
						if (in_array($product, $coupon_product_data)) {
							$product_data[] = $product;
							
							continue;
						}
						
						foreach ($coupon_category_data as $category_id) {
							$coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `product_to_category` WHERE `product_id` = '" . (int)$product . "' AND category_id = '" . (int)$category_id . "'");
							
							if ($coupon_category_query->row['total']) {
								$product_data[] = $product;
								
								continue;
							}						
						}
						
						foreach ($coupon_manufacturer_data as $manufacturer_id) {
							$coupon_manufacturer_query = $this->db->query("SELECT COUNT(*) AS total FROM `product` WHERE `product_id` = '" . (int)$product . "' AND manufacturer_id = '" . (int)$manufacturer_id . "'");
							
							if ($coupon_manufacturer_query->row['total']) {
								$product_data[] = $product;
								
								continue;
							}						
						}
						
						foreach ($coupon_collection_data as $collection_id) {
							$coupon_collection_query = $this->db->query("SELECT COUNT(*) AS total FROM `product` WHERE `product_id` = '" . (int)$product . "' AND collection_id = '" . (int)$collection_id . "'");
							
							if ($coupon_collection_query->row['total']) {
								$product_data[] = $product;
								
								continue;
							}						
						}
					}
					} else {
					$product_data = $product_ids;
				}
				
				if ($order_id){
					
					if ($has_birthday_discount_explicit || $this->checkIfOrderHasBirthdayDiscount($order_id)){
						
						$_tmp_product_data = array();
						
						foreach ($product_data as $__product_id){
							$check_birthday_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `product_to_category` WHERE `product_id` = '" . (int)$__product_id . "' AND category_id = '" . (int)BIRTHDAY_DISCOUNT_CATEGORY . "'");
							if (!$check_birthday_category_query->row['total']) {
								$_tmp_product_data[] = $__product_id;
							}
						}
						
						$product_data = $_tmp_product_data;
					}
					
				}
				
				return $product_data;
				
				} else {
				
				return array();
				
			}	
			
		}
		
	}																		
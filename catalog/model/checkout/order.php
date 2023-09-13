<?php
class ModelCheckoutOrder extends Model {

	public function addOrder($data) {	

		if ($double_order_id = $this->validateDoubleOrder($data)){
			return $double_order_id;
		}

			//Перегрузка simple-полей в поля заказа

			//1. Доставка курьером по городам Киев, МСК, Минск, НурСултан
		if (!empty($data['shipping_code']) && ($data['shipping_code'] == 'dostavkaplus.sh1' || $data['shipping_code'] == 'dostavkaplus.sh2' || $data['shipping_code'] == 'dostavkaplus.sh7' || $data['shipping_code'] == 'dostavkaplus.sh8')){
			if (!empty($data['courier_city_shipping_address'])){
				$data['shipping_address_1'] = $data['courier_city_shipping_address'];
				$data['payment_address_1'] = $data['courier_city_shipping_address'];
			}
		}


			//2. Новая Почта отделение
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh3'){
			if (!empty($data['novaposhta_warehouse'])){
				$query = $this->db->ncquery("SELECT * FROM novaposhta_warehouses WHERE Ref = '" . $this->db->escape($data['novaposhta_warehouse']) . "' LIMIT 1");

				if ($query->row){
					if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
						$data['shipping_address_1'] = str_replace('"', '', $query->row['Description']);
						$data['payment_address_1'] 	= str_replace('"', '', $query->row['Description']);
					} else {
						$data['shipping_address_1'] = str_replace('"', '', $query->row['DescriptionRu']);
						$data['payment_address_1'] 	= str_replace('"', '', $query->row['DescriptionRu']);
					}
				}
			}
		}


			//3. СДЭК отделение
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh6'){
			if (!empty($data['cdek_warehouse'])){
				$query = $this->db->ncquery("SELECT * FROM cdek_deliverypoints WHERE code = '" . $this->db->escape($data['cdek_warehouse']) . "' LIMIT 1");

				if ($query->row){
					$data['shipping_address_1'] = $query->row['address'] . ' (' . $query->row['name'] . ')';
					$data['payment_address_1'] = $query->row['address'] . ' (' . $query->row['name'] . ')';
				}
			}
		}

			//3. JUSTIN отделение
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh15'){
			if (!empty($data['justin_warehouse'])){
				$query = $this->db->ncquery("SELECT * FROM justin_warehouses WHERE Uuid = '" . $this->db->escape($data['justin_warehouse']) . "' LIMIT 1");

				if ($query->row){
					$data['shipping_address_1'] = $query->row['DescrRU'];
					$data['payment_address_1'] = $query->row['DescrRU'];
				}
			}
		}

			//4. Новая Почта адреска
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh13'){
			if (!empty($data['novaposhta_street'])){
				$query = $this->db->ncquery("SELECT * FROM novaposhta_streets WHERE Ref = '" . $this->db->escape($data['novaposhta_street']) . "' LIMIT 1");

				if ($query->row){
					$data['shipping_address_1'] = $query->row['StreetsType'] . ' ' . $query->row['Description'];
					$data['payment_address_1'] = $query->row['StreetsType'] . ' '. $query->row['Description'];
				}


				if (!empty($data['novaposhta_house_number'])){
					$data['shipping_address_1'] .= ', д.' . $data['novaposhta_house_number'];
					$data['payment_address_1'] .= ', д.' . $data['novaposhta_house_number'];
				}

				if (!empty($data['novaposhta_flat'])){
					$data['shipping_address_1'] .= ', кв.' . $data['novaposhta_flat'];
					$data['payment_address_1'] .= ', кв.' . $data['novaposhta_flat'];
				}
			}
		}

			//5. СДЭК адреска или EMS
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh17'){
			if (!empty($data['cdek_street'])){

				if (!empty($data['cdek_street'])){
					$data['shipping_address_1'] = $data['cdek_street'];
					$data['payment_address_1'] = $data['cdek_street'];
				}


				if (!empty($data['cdek_house_number'])){
					$data['shipping_address_1'] .= ', д.' . $data['cdek_house_number'];
					$data['payment_address_1'] .= ', д.' . $data['cdek_house_number'];
				}

				if (!empty($data['cdek_flat'])){
					$data['shipping_address_1'] .= ', кв.' . $data['cdek_flat'];
					$data['payment_address_1'] .= ', кв.' . $data['cdek_flat'];
				}
			}
		}

			//5. EMS
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh5'){
			if (!empty($data['cdek_street'])){

				if (!empty($data['cdek_street'])){
					$data['shipping_address_1'] = $data['cdek_street'];
					$data['payment_address_1'] = $data['cdek_street'];
				}


				if (!empty($data['cdek_house_number'])){
					$data['shipping_address_1'] .= ', д.' . $data['cdek_house_number'];
					$data['payment_address_1'] .= ', д.' . $data['cdek_house_number'];
				}

				if (!empty($data['cdek_flat'])){
					$data['shipping_address_1'] .= ', кв.' . $data['cdek_flat'];
					$data['payment_address_1'] .= ', кв.' . $data['cdek_flat'];
				}
			}
		}

			//5. Укрпочта
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh14'){
			if (!empty($data['ukrpost_postcode'])){

				if (!empty($data['ukrpost_postcode'])){
					$data['shipping_postcode'] = $data['ukrpost_postcode'];
					$data['payment_postcode'] = $data['ukrpost_postcode'];
				}

				if (!empty($data['cdek_street'])){
					$data['shipping_address_1'] .= 'ул.' . $data['cdek_street'];
					$data['payment_address_1'] .= 'ул.' . $data['cdek_street'];
				}

				if (!empty($data['cdek_house_number'])){
					$data['shipping_address_1'] .= ', д.' . $data['cdek_house_number'];
					$data['payment_address_1'] .= ', д.' . $data['cdek_house_number'];
				}

				if (!empty($data['cdek_flat'])){
					$data['shipping_address_1'] .= ', кв.' . $data['cdek_flat'];
					$data['payment_address_1'] .= ', кв.' . $data['cdek_flat'];
				}
			}
		}

			//6. БелПочта
		if (!empty($data['shipping_code']) && $data['shipping_code'] == 'dostavkaplus.sh16'){
			if (!empty($data['ukrpost_postcode'])){

				if (!empty($data['ukrpost_postcode'])){
					$data['shipping_postcode'] = $data['ukrpost_postcode'];
					$data['payment_postcode'] = $data['ukrpost_postcode'];
				}

				if (!empty($data['cdek_street'])){
					$data['shipping_address_1'] .= 'ул.' . $data['cdek_street'];
					$data['payment_address_1'] .= 'ул.' . $data['cdek_street'];
				}

				if (!empty($data['cdek_house_number'])){
					$data['shipping_address_1'] .= ', д.' . $data['cdek_house_number'];
					$data['payment_address_1'] .= ', д.' . $data['cdek_house_number'];
				}

				if (!empty($data['cdek_flat'])){
					$data['shipping_address_1'] .= ', кв.' . $data['cdek_flat'];
					$data['payment_address_1'] .= ', кв.' . $data['cdek_flat'];
				}
			}
		}

		if (empty($data['shipping_country_id'])){
			$this->load->model('localisation/country');				
			$data['shipping_country_id'] = $this->config->get('config_country_id');
			$data['shipping_country'] = $this->model_localisation_country->getCountry($data['shipping_country_id'])['name'];
		}

		if (empty($data['payment_country_id'])){
			$this->load->model('localisation/country');				
			$data['payment_country_id'] = $this->config->get('config_country_id');
			$data['payment_country'] = $this->model_localisation_country->getCountry($data['payment_country_id'])['name'];
		}

		$this->db->ncquery("INSERT INTO `order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', payment_secondary_method = '" . $this->db->escape($data['payment_secondary_method']) . "', payment_secondary_code = '" . $this->db->escape($data['payment_secondary_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', original_comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', total_national = '" . (float)$data['total_national'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', first_referrer = '" . $this->db->escape(isset($data['first_referrer']) ? $data['first_referrer'] : "n\a") . "', last_referrer = '" . $this->db->escape(isset($data['last_referrer']) ? $data['last_referrer'] : "n\a") . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', weight = '" . (isset($data['weight']) ? floatval($data['weight']) : 0) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', preorder = '" . (isset($data['preorder']) ? (int)($data['preorder']) : 0) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_added_timestamp = UNIX_TIMESTAMP(), date_modified = NOW(), template = '" . $this->db->escape($this->config->get('config_template')) . "', pwa = '" . (int)$this->customer->getPWASession() . "'");

		$order_id = $this->db->getLastId();			

			//CREATE CUSTOMER IF CUSTOMER_ID = 0
		if (empty($data['customer_id']) || (int)$data['customer_id'] == 0){				
			$customer_id = false;

			if (!$customer_id){
				$customer_id = (int)$this->session->data['customer_id'];
			}

			if (trim($data['email'])){
				$data['email'] = str_replace('order@kitchen-profi.de', '', $data['email']);
				$data['email'] = str_replace($this->config->get('simple_empty_email'), '', $data['email']);		
			}

			if (trim($data['telephone']) && !$customer_id){
					//	$customer_id = $this->model_account_customer->getCustomerIDByEmail($data['telephone']);
			}

			if (trim($data['telephone']) && !$customer_id){
				$customer_id = $this->model_account_customer->getCustomerByPhone($data['telephone']);
			}

				//TRY TO FIND CUSTOMER
			if (trim($data['email']) && !$customer_id){
				$customer_id = $this->model_account_customer->getCustomerIDByEmail($data['email']);
			}

			if (!$customer_id){
				$customer_id = $this->createCustomer($data);
			}

			$this->db->ncquery("UPDATE `order` SET customer_id = '" . (int)$customer_id . "' WHERE order_id = '" . (int)$order_id . "'");

		} else {
			$this->load->model('account/customer');	

			if ($customer = $this->model_account_customer->getCustomer($data['customer_id'])){				
				$customer_id = $data['customer_id'];
			} else {
				$customer_id = $this->createCustomer($data);
			}
		}

		$data['order_id'] = $order_id;
		$data['customer_id'] = $customer_id;


		if ($customer_id == YANDEX_MARKET_CUSTOMER_ID){
			$this->db->ncquery("UPDATE `order` SET yam = '1', manager_id = 177 WHERE order_id = '" . (int)$order_id . "'");
		}

		if (ADMIN_SESSION_DETECTED){
			if (!empty($this->session->data['user_id']) && !empty($this->session->data['token'])){
				$this->load->model('user/user');
				$user = $this->model_user_user->getUser($this->session->data['user_id']);

				if ($user['own_orders']){					
					$this->db->ncquery("UPDATE `order` SET manager_id = '" . (int)$user['user_id'] . "' WHERE order_id = '" . (int)$order_id . "'");
				}
			}
		}

		if (!empty($data['yam_id'])){
			$this->db->ncquery("UPDATE `order` SET yam_id = '" . (int)$data['yam_id'] . "' WHERE order_id = '" . (int)$order_id . "'");
		}

		if (!empty($data['yam_fake'])){
			$this->db->ncquery("UPDATE `order` SET yam_fake = '" . (int)$data['yam_fake'] . "' WHERE order_id = '" . (int)$order_id . "'");
		}

		if (!empty($data['yam_shipment_date'])){
			$this->db->ncquery("UPDATE `order` SET yam_shipment_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['yam_shipment_date']))) . "' WHERE order_id = '" . (int)$order_id . "'");
			$this->db->ncquery("UPDATE `order` SET date_delivery_actual = '" . $this->db->escape(date('Y-m-d', strtotime($data['yam_shipment_date']))) . "' WHERE order_id = '" . (int)$order_id . "'");
		}

		if (!empty($data['yam_shipment_id'])){
			$this->db->ncquery("UPDATE `order` SET yam_shipment_id = '" . (int)$data['yam_shipment_id'] . "' WHERE order_id = '" . (int)$order_id . "'");
		}

			//	$this->session->data['customer_id'] = $data['customer_id'];

		$this->db->ncquery("UPDATE address SET 
			city = '" . $this->db->escape($data['shipping_city']) . "',  
			address_1 = '" . $this->db->escape($data['shipping_address_1']) . "',
			address_2 = '" . $this->db->escape($data['shipping_address_2']) . "',
			postcode = '" . $this->db->escape($data['postcode']) . "'
			WHERE address_id = (SELECT address_id FROM customer WHERE customer_id = '" . (int)$customer_id . "')");						

		$this->updateDaDataFields($data);


		foreach ($data['products'] as $product) {
			$this->db->ncquery("INSERT INTO order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', ao_id = '" . (int)$product['ao_id'] . "', ao_product_id = '" . (int)$product['ao_product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', price_national = '" . (float)$product['price_national'] . "', original_price_national = '" . (float)$product['price_national'] . "',  total = '" . (float)$product['total'] . "', total_national = '" . (float)$product['total_national'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', reward_one = '" . (int)($product['reward']/$product['quantity']) . "', from_stock = '" . (int)$product['from_stock'] . "'");


			$order_product_id = $this->db->getLastId();

			if ($this->config->get('discount_regular_status') && $this->customer->isLogged() && $this->customer->getHasBirthday()){
				$this->load->model('total/discount_regular');

				if ($this->model_total_discount_regular->checkIfProductHasDiscount($product['product_id'])) {
					$this->db->ncquery("UPDATE order_product SET from_bd_gift = 1 WHERE order_product_id = '" . (int)$order_product_id . "'");
				}
			}

			foreach ($product['option'] as $option) {
				$this->db->ncquery("INSERT INTO order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}

			foreach ($product['download'] as $download) {
				$this->db->ncquery("INSERT INTO order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}

				// Получаем все товары в наборе
			$this->db->ncquery("DELETE FROM `order_set` WHERE `order_id` = ".(int)$order_id);
			if ((int)$product['set']) {
				$productSet = $this->db->ncquery("SELECT ps.`set_id` as sid, s.product_id as psid, ps.product_id as pid, ps.price as prprice, ps.quantity as quantity, p.short_name as name, p.model as model FROM `product_to_set` ps LEFT JOIN `set` s ON (ps.set_id = s.set_id) LEFT JOIN `product` p ON (ps.product_id = p.product_id) WHERE ps.`set_id` = ".(int)$product['set']);
					// $productSet = $this->db->ncquery("SELECT * FROM `product_to_set` ps LEFT JOIN `set` s ON (ps.set_id = s.set_id) WHERE `set_id` = ".(int)$product['set']);
				foreach ($productSet->rows as $prSet) {
					$price_national = $this->currency->convert($prSet['prprice'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
					$quantity = $prSet['quantity'];
					$total_national = $price_national * $quantity;
					$total = $this->currency->convert($total_national, $this->config->get('config_regional_currency'), $this->config->get('config_currency'));

					$this->db->ncquery("INSERT INTO `order_set` (`order_id`, `set_id`, `set_product_id`, `price`, `price_national`, `original_price_national`, `quantity`, `total`, `total_national`, `product_id`, `name`, `model`) VALUES ('".(int)$order_id."', '".(int)$product['set']."', '".(int)$product['product_id']."', '".(float)$prSet['prprice']."', '".(float)$price_national."', '".(float)$price_national."', '".(int)$quantity."', '".(float)$total."', '".(float)$total_national."', '".$prSet['pid']."', '".$prSet['name']."', '".$prSet['model']."')");
				}
			}
		}

			//Настройка "получать офферы после заказа"
		if ($this->config->get('config_rainforest_enable_offers_after_order')){
			$asin_query = $this->db->ncquery("SELECT asin FROM product WHERE product_id = '" . (int)$product['product_id'] . "'");
			if ($asin_query->row['asin']){
				$this->db->ncquery("INSERT IGNORE INTO amzn_product_queue SET asin = '" . $this->db->escape($asin_query->row['asin']) . "', date_added = NOW()");
			}			
		}

			//Резервирование товара
		$this->db->ncquery("UPDATE product SET `" . $this->config->get('config_warehouse_identifier') . "` = (`" . $this->config->get('config_warehouse_identifier') . "` - ". (int)$product['quantity'] .") WHERE product_id = '" . (int)$product['product_id'] . "' AND `" . $this->config->get('config_warehouse_identifier') . "` > 0");

			//Постановка в очередь для яндекс-маркета
		if ($this->config->get('config_warehouse_identifier') == $this->config->get('config_warehouse_identifier')){
			if ($this->config->get('config_yam_offer_id_prefix_enable') && $this->config->get('config_yam_offer_id_prefix')){
				$yam_product_id = $this->config->get('config_yam_offer_id_prefix') . (int)$product['product_id'];
			} else {
				$yam_product_id = (int)$product['product_id'];
			}

			$query = $this->db->ncquery("SELECT `" . $this->config->get('config_warehouse_identifier') . "` as stock FROM product WHERE product_id = '" . (int)$product['product_id'] . "'");

			if ($query->num_rows){

				$this->db->ncquery("INSERT INTO yandex_stock_queue SET yam_product_id = '" . $this->db->escape($yam_product_id) . "', stock = '" . (int)$query->row['stock'] . "' ON DUPLICATE KEY UPDATE stock = '" . (int)$query->row['stock'] . "'");

			}								
		}

		foreach ($data['vouchers'] as $voucher) {
			$this->db->ncquery("INSERT INTO order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");
		}

		foreach ($data['totals'] as $total) {
			$this->db->ncquery("INSERT INTO order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', `value_national` = '" . (float)$total['value_national'] . "', sort_order = '" . (int)$total['sort_order'] . "'");

			if ($total['code'] == 'total'){
				$this->db->ncquery("UPDATE `order` SET total_national = '" . (float)$total['value_national'] . "' WHERE order_id = '" . (int)$order_id . "'");
			}
		}	

		return $order_id;
	}

	public function getLastCustomerOrder($customer_id){

		$query = $this->db->ncquery("SELECT * FROM `order` WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT 1");

		if ($query->num_rows){
			return $query->row;
		} else {
			return false;
		}
	}

	public function createCustomer($data){
		$this->load->model('account/customer');	

		$customer_id 		= $this->model_account_customer->addCustomer(array(
			'firstname' 		=> $data['firstname'],
			'lastname' 			=> $data['lastname'],
			'customer_group_id' => $this->config->get('config_customer_group_id'),
			'email' 			=> $data['email']?$data['email']:$data['telephone'],
			'telephone' 		=> $data['telephone'],
			'newsletter' 				=> 1,
			'newsletter_news' 			=> 1,
			'newsletter_personal' 		=> 1,
			'viber_news' 		=> 1,
			'fax' 				=> $data['fax'],
			'passport_serie' 	=> '', 
			'passport_given' 	=> '',
			'birtday' 			=> '0000-00-00',
			'password' 			=> rand(9999, 999999),
			'company' 			=> '',
			'company_id' 		=> 0,
			'tax_id' 			=> 0,
			'address_1' 		=> $data['shipping_address_1'],
			'address_2' 		=> $data['shipping_address_2'],
			'city' 				=> $data['shipping_city'],
			'postcode' 			=> $data['shipping_postcode'],
			'country_id' 		=> $this->config->get('config_country_id'),
			'zone_id' 			=> $data['shipping_zone_id'],
			'company' 			=> '',
		));

		return $customer_id;
	}								

	public function updateDaDataFields($data){	

		$dataArray = array (
			'payment_custom_unrestricted_value' => 
			array (
				'id' => 'payment_custom_unrestricted_value',
				'label' => 'Полный адрес',
				'value' => !empty($data['courier_city_dadata_unrestricted_value'])?$data['courier_city_dadata_unrestricted_value']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'payment_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_unrestricted_value',
			),
			'shipping_custom_unrestricted_value' => 
			array (
				'id' => 'shipping_custom_unrestricted_value',
				'label' => 'Полный адрес',
				'value' => !empty($data['courier_city_dadata_unrestricted_value'])?$data['courier_city_dadata_unrestricted_value']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'shipping_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_unrestricted_value',
			),
			'payment_custom_beltway_hit' => 
			array (
				'id' => 'payment_custom_beltway_hit',
				'label' => 'Внутри МКАД',
				'value' => !empty($data['courier_city_dadata_beltway_hit'])?$data['courier_city_dadata_beltway_hit']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'payment_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_beltway_hit',
			),
			'shipping_custom_beltway_hit' => 
			array (
				'id' => 'shipping_custom_beltway_hit',
				'label' => 'Внутри МКАД',
				'value' => !empty($data['courier_city_dadata_beltway_hit'])?$data['courier_city_dadata_beltway_hit']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'shipping_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_beltway_hit',
			),
			'payment_custom_beltway_distance' => 
			array (
				'id' => 'payment_custom_beltway_distance',
				'label' => 'Расстояние до МКАД',
				'value' => !empty($data['courier_city_dadata_beltway_distance'])?$data['courier_city_dadata_beltway_distance']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'payment_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_beltway_distance',
			),
			'shipping_custom_beltway_distance' => 
			array (
				'id' => 'shipping_custom_beltway_distance',
				'label' => 'Расстояние до МКАД',
				'value' => !empty($data['courier_city_dadata_beltway_distance'])?$data['courier_city_dadata_beltway_distance']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'shipping_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_beltway_distance',
			),
			'payment_custom_geolocation' => 
			array (
				'id' => 'payment_custom_geolocation',
				'label' => 'Геолокация адреса',
				'value' => !empty($data['courier_city_dadata_geolocation'])?$data['courier_city_dadata_geolocation']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'payment_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_geolocation',
			),
			'shipping_custom_geolocation' => 
			array (
				'id' => 'shipping_custom_geolocation',
				'label' => 'Геолокация адреса',
				'value' => !empty($data['courier_city_dadata_geolocation'])?$data['courier_city_dadata_geolocation']:'',
				'values' => 
				array (
				),
				'text' => '',
				'type' => 'text',
				'set' => 'shipping_address',
				'from' => 'checkout_customer',
				'field_id' => 'custom_geolocation',
			),
		);


		$this->db->ncquery("INSERT INTO simple_custom_data SET object_type = 1, object_id = '" . (int)$data['order_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', data = '" . $this->db->escape(serialize($dataArray)) . "' ON DUPLICATE KEY UPDATE data = '" . $this->db->escape(serialize($dataArray)) . "'");
	}

	public function validateDoubleOrder($data){
		$this->load->model('account/customer');	

		$customer_id = false;
		if (!empty($data['customer_id'])){	
			$customer_id = (int)$data['customer_id'];
		}

		if ($customer_id == YANDEX_MARKET_CUSTOMER_ID){
			return false;
		}

		if (!$customer_id){
			$customer_id = $this->customer->isLogged();
		}

		if (!$customer_id){
			$customer_id = !empty($this->session->data['customer_id'])?$this->session->data['customer_id']:(int)$this->session->data['customer_id'];
		}

		if (trim($data['email']) && !$customer_id){
			$data['email'] = str_replace('order@kitchen-profi.de', '', $data['email']);
			$data['email'] = str_replace($this->config->get('simple_empty_email'), '', $data['email']);		
		}

		if (trim($data['telephone']) && !$customer_id){
			$customer_id = $this->model_account_customer->getCustomerByEmail($data['telephone'])['customer_id'];
		}

		if (trim($data['telephone']) && !$customer_id){
			$customer_id = $this->model_account_customer->getCustomerByPhone($data['telephone'])['customer_id'];
		}

		if (trim($data['email']) && !$customer_id){
			$customer_id = $this->model_account_customer->getCustomerByEmail($data['email'])['customer_id'];
		}		

		$sql = "SELECT * FROM `order` WHERE (customer_id = '" . (int)$data['customer_id'] . "'";
		$sql .= " OR telephone = '" . $this->db->escape($data['telephone']) . "'";
		$sql .= " OR email = '" . $this->db->escape($data['email']) . "') ";
		$sql .= " AND order_status_id > 0	AND date_added_timestamp >= UNIX_TIMESTAMP(NOW() - INTERVAL 2 MINUTE) ORDER BY date_added_timestamp DESC LIMIT 1";
		
		$query = $this->db->non_cached_query($sql);
			
		$compare_fields = array(
			'firstname',
			'lastname',
			'telephone',
			'payment_city',
			'payment_code',
			'shipping_city',
			'shipping_code',
		);	
		
		$exact = true;		
		$suspected_order_id = false;
		if ($query->num_rows && $query->row){
			$suspected_order_id = $query->row['order_id'];

			foreach ($compare_fields as $field){
				if ($query->row[$field] != $data[$field]){
					$exact = false;
					break;
				}
			}			
		}
		
		if ($exact){
			$query = $this->db->non_cached_query("SELECT * FROM `order_product` WHERE order_id = '" . (int)$suspected_order_id . "'");
			
			$exact_products = true;
			$suspected_products = array();
			foreach ($query->rows as $row){
				$suspected_products[$row['product_id']] = $row['quantity'];
			}
			
			$data_products = array();
			foreach ($data['products'] as $product){
				$data_products[$product['product_id']] = $product['quantity'];
			}
			
			ksort($suspected_products);
			ksort($data_products);
		}
		
		if ($suspected_products == $data_products){
			return $suspected_order_id;
		}

		return false;
	}
		
		public function getOrder($order_id) {
			$order_query = $this->db->non_cached_query("SELECT *, (SELECT os.name FROM `order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `order` o WHERE o.order_id = '" . (int)$order_id . "'");
			
			if ($order_query->num_rows) {
				$country_query = $this->db->ncquery("SELECT * FROM `country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$payment_iso_code_2 = $country_query->row['iso_code_2'];
					$payment_iso_code_3 = $country_query->row['iso_code_3'];
				} else {
					$payment_iso_code_2 = '';
					$payment_iso_code_3 = '';				
				}
				
				$zone_query = $this->db->ncquery("SELECT * FROM `zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$payment_zone_code = $zone_query->row['code'];
				} else {
					$payment_zone_code = '';
				}			
				
				$country_query = $this->db->ncquery("SELECT * FROM `country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$shipping_iso_code_2 = $country_query->row['iso_code_2'];
					$shipping_iso_code_3 = $country_query->row['iso_code_3'];
				} else {
					$shipping_iso_code_2 = '';
					$shipping_iso_code_3 = '';				
				}
				
				$zone_query = $this->db->ncquery("SELECT * FROM `zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$shipping_zone_code = $zone_query->row['code'];
				} else {
					$shipping_zone_code = '';
				}
				
				$this->load->model('localisation/language');
				
				$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
				
				if ($language_info) {
					$language_code = $language_info['code'];
					$language_filename = $language_info['filename'];
					$language_directory = $language_info['directory'];
				} else {
					$language_code = '';
					$language_filename = '';
					$language_directory = '';
				}
				
				return [
					'order_id'                => $order_query->row['order_id'],
					'pwa'               	  => $order_query->row['pwa'],	
					'yam'               	  => $order_query->row['yam'],
					'yam_id'               	  => $order_query->row['yam_id'],
					'yam_shipment_date'       => $order_query->row['yam_shipment_date'],
					'yam_shipment_id'         => $order_query->row['yam_shipment_id'],
					'yam_box_id'         	  => $order_query->row['yam_box_id'],
					'yam_status'              => $order_query->row['yam_status'],
					'yam_substatus'           => $order_query->row['yam_substatus'],
					'yam_fake'           	  => $order_query->row['yam_fake'],
					'affiliate_id' 			  => $order_query->row['affiliate_id'],
					'commission' 			  => $order_query->row['commission'],
					'invoice_no'              => $order_query->row['invoice_no'],
					'invoice_prefix'          => $order_query->row['invoice_prefix'],
					'store_id'                => $order_query->row['store_id'],
					'store_name'              => $order_query->row['store_name'],
					'store_url'               => $order_query->row['store_url'],				
					'customer_id'             => $order_query->row['customer_id'],
					'customer_group_id' 		=> (isset($order_query->row['customer_group_id'])) ? $order_query->row['customer_group_id'] : '',
					'affiliate_id' 				=> (isset($order_query->row['affiliate_id'])) ? $order_query->row['affiliate_id'] : '',
					'weight' 					=> (isset($order_query->row['weight'])) ? $order_query->row['weight'] : 0,
					'firstname'               => $order_query->row['firstname'],
					'lastname'                => $order_query->row['lastname'],
					'telephone'               => $order_query->row['telephone'],
					'fax'                     => $order_query->row['fax'],
					'email'                   => $order_query->row['email'],
					'payment_firstname'       => $order_query->row['payment_firstname'],
					'payment_lastname'        => $order_query->row['payment_lastname'],				
					'payment_company'         => $order_query->row['payment_company'],
					'payment_company_id'      => $order_query->row['payment_company_id'],
					'payment_tax_id'          => $order_query->row['payment_tax_id'],
					'payment_address_1'       => $order_query->row['payment_address_1'],
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
					'payment_secondary_method'=> $order_query->row['payment_secondary_method'],
					'payment_secondary_code'  => $order_query->row['payment_secondary_code'],
					'concardis_id'  		  => $order_query->row['concardis_id'],
					'shipping_firstname'      => $order_query->row['shipping_firstname'],
					'shipping_lastname'       => $order_query->row['shipping_lastname'],				
					'shipping_company'        => $order_query->row['shipping_company'],
					'shipping_address_1'      => $order_query->row['shipping_address_1'],
					'shipping_address_2'      => $order_query->row['shipping_address_2'],
					'shipping_postcode'       => $order_query->row['shipping_postcode'],
					'shipping_city'           => $order_query->row['shipping_city'],
					'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
					'shipping_zone'           => $order_query->row['shipping_zone'],
					'shipping_zone_code'      => $shipping_zone_code,
					'shipping_country_id'     => $order_query->row['shipping_country_id'],
					'shipping_country'        => $order_query->row['shipping_country'],	
					'shipping_iso_code_2'     => $shipping_iso_code_2,
					'shipping_iso_code_3'     => $shipping_iso_code_3,
					'shipping_address_format' => $order_query->row['shipping_address_format'],
					'shipping_method'         => $order_query->row['shipping_method'],
					'shipping_code'           => $order_query->row['shipping_code'],
					'comment'                 => $order_query->row['comment'],
					'total'                   => $order_query->row['total'],
					'total_national'          => $order_query->row['total_national'],
					'order_status_id'         => $order_query->row['order_status_id'],
					'order_status'            => $order_query->row['order_status'],
					'language_id'             => $order_query->row['language_id'],
					'language_code'           => $language_code,
					'language_filename'       => $language_filename,
					'language_directory'      => $language_directory,
					'currency_id'             => $order_query->row['currency_id'],
					'currency_code'           => $order_query->row['currency_code'],
					'currency_value'          => $order_query->row['currency_value'],
					'ip'                      => $order_query->row['ip'],
					'forwarded_ip'            => $order_query->row['forwarded_ip'], 
					'user_agent'              => $order_query->row['user_agent'],	
					'accept_language'         => $order_query->row['accept_language'],				
					'date_modified'           => $order_query->row['date_modified'],
					'date_added'              => $order_query->row['date_added'],
					'pay_equire'			  => $order_query->row['pay_equire'],
					'customer_confirm_url'    => $this->url->link('checkout/customer_confirm', 'order_id='.$order_query->row['order_id'].'&confirm='.md5(sin($order_query->row['order_id']+2))),
					'changed'				  => $order_query->row['changed'],
				];
		} else {
			return false;	
		}
	}

public function getLegalPersonForOrder(){

	$query = $this->db->ncquery("SELECT * FROM legalperson WHERE legalperson_legal = 0 AND legalperson_desc <> '' AND legalperson_country_id = '" . $this->config->get('config_country_id') . "'");
	if ($query->num_rows){
		return $query->row['legalperson_desc'];
	} else {
		return false;
	}
}

public function setUkrcreditsOrderId($order_id, $paymenttype, $mono_order_id, $mono_order_status, $mono_order_substatus = false) {
	$order_info = $this->getOrder($order_id);

	if ($order_info && !$order_info['order_status_id']) {
		$this->db->query("INSERT INTO order_ukrcredits SET order_id = '" . (int)$order_id . "', ukrcredits_payment_type = '" . $this->db->escape($paymenttype) . "', ukrcredits_order_id = '" . $this->db->escape($mono_order_id) . "', ukrcredits_order_status = '" . $this->db->escape($mono_order_status) . "', ukrcredits_order_substatus = '" . $this->db->escape($mono_order_substatus) . "'");
	}		
}			


public function getOrderMb($mono_order_id) {
	$ordermb_query = $this->db->query("SELECT * FROM `order_ukrcredits` WHERE ukrcredits_order_id = '" . $this->db->escape($mono_order_id) . "'");
	if ($ordermb_query->num_rows) {
		return $ordermb_query->row;			
	} else {
		return false;
	}
}

public function updateUkrcreditsOrderMono($order_id, $state, $substate) {
	$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = '" . $this->db->escape($state) . "', ukrcredits_order_substatus = '" . $this->db->escape($substate) . "' WHERE order_id = '" . (int)$order_id . "'");
}	

public function updateUkrcreditsOrderPrivat($order_id, $privat_order_status) {
	$this->db->query("UPDATE order_ukrcredits SET ukrcredits_order_status = '" . $this->db->escape($privat_order_status) . "' WHERE order_id = '" . (int)$order_id . "'");
}

public function getOrderProducts($order_id) {

	$query = $this->db->ncquery("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id ."'");

	return $query->rows;
}

public function confirm($order_id, $order_status_id, $comment = '', $notify = false, $user_id = false) {

	$order_info = $this->getOrder($order_id);

	if ($order_info && !$order_info['order_status_id']) {

		$this->load->model('module/affiliate');
		$this->model_module_affiliate->validate($order_id, $order_info, $order_status_id);

		if ($this->config->get('config_fraud_detection')) {
			$this->load->model('checkout/fraud');

			$risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

			if ($risk_score > $this->config->get('config_fraud_score')) {
				$order_status_id = $this->config->get('config_fraud_status_id');
			}
		}

		$status = false;

		$this->load->model('account/customer');

		if ($order_info['customer_id']) {
			$results = $this->model_account_customer->getIps($order_info['customer_id']);

			foreach ($results as $result) {
				if ($this->model_account_customer->isBanIp($result['ip'])) {
					$status = true;

					break;
				}
			}
		} else {
			$status = $this->model_account_customer->isBanIp($order_info['ip']);
		}

		if ($status) {
			$order_status_id = $this->config->get('config_order_status_id');
		}		

		$this->db->ncquery("UPDATE `order` SET order_status_id = '" . (int)$order_status_id . "', date_added = NOW(), date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		$this->db->ncquery("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), user_id = '" . (int)$user_id . "'");

		$order_product_query = $this->db->ncquery("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");

		foreach ($order_product_query->rows as $order_product) {
			$this->db->ncquery("UPDATE product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				$order_option_query = $this->db->ncquery("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");
				foreach ($order_option_query->rows as $option) {
					$this->db->ncquery("UPDATE product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}

			$customer_group_info = array();
			if(isset($order_info['customer_group_id']) && $order_info['customer_group_id']){
				$this->load->model('account/customer_group');
				$customer_group_info = $this->model_account_customer_group->getCustomerGroup($order_info['customer_group_id']);
			}

			$affiliate_info = array();
			if(isset($order_info['affiliate_id']) && $order_info['affiliate_id']){
				$this->load->model('affiliate/affiliate');
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($order_info['affiliate_id']);
			}

			if(!isset($passArray) || empty($passArray)){ $passArray = null; }											
			$order_download_query = $this->db->ncquery("SELECT * FROM order_download WHERE order_id = '" . (int)$order_id . "'");

				// Gift Voucher
			$this->load->model('checkout/voucher');

			$order_voucher_query = $this->db->ncquery("SELECT * FROM order_voucher WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_voucher_query->rows as $order_voucher) {
				$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $order_voucher);

				$this->db->ncquery("UPDATE order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher['order_voucher_id'] . "'");
			}			

			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->model_checkout_voucher->confirm($order_id);
			}
		
			$order_total_query = $this->db->non_cached_query("SELECT * FROM `order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");

			foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/' . $order_total['code']);

				if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
					$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
				}
			}

				// Send out order confirmation mail
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');

			$order_status_query = $this->db->ncquery("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];	
			} else {
				$order_status = '';
			}

			if ($order_status_id == $this->config->get('config_order_status_id')) {

				$data = array(
					'type' => 'success',
					'text' => 'Ура! Новый заказ', 
					'entity_type' => 'order', 
					'entity_id'=> $order_id
				);

				$this->mAlert->insertAlertForGroup('sales', $data);
				$this->mAlert->insertAlertForGroup('buyers', $data);
				$this->mAlert->insertAlertForGroup('admins', $data);

			} else {
				$data = array(
					'type' => 'success',
					'text' => 'Новый статус заказа: '.$order_status, 
					'entity_type' => 'order', 
					'entity_id'=> $order_id
				);

				$this->mAlert->insertAlertForGroup('sales', $data);								
			}

			$subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);

				// HTML Mail
			$this->load->model('tool/image');
			$this->load->model('catalog/product');

			$template = new EmailTemplate($this->request, $this->registry);

			$template->addData($this->request->post, 'post_data');

			$template->addData($order_info);

			$template->data['affiliate'] = $affiliate_info;

			$template->data['customer_group'] = $customer_group_info;

			$template->data['new_order_status'] = $order_status; 

			$template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

			$template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['text_link'] = $language->get('text_new_link');
			$template->data['text_download'] = $language->get('text_new_download');
			$template->data['text_order_detail'] = $language->get('text_new_order_detail');
			$template->data['text_instruction'] = $language->get('text_new_instruction');
			$template->data['text_order_id'] = $language->get('text_new_order_id');
			$template->data['text_date_added'] = $language->get('text_new_date_added');
			$template->data['text_payment_method'] = $language->get('text_new_payment_method');	
			$template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
			$template->data['text_email'] = $language->get('text_new_email');
			$template->data['text_telephone'] = $language->get('text_new_telephone');
			$template->data['text_ip'] = $language->get('text_new_ip');
			$template->data['text_payment_address'] = $language->get('text_new_payment_address');
			$template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
			$template->data['text_product'] = $language->get('text_new_product');
			$template->data['text_model'] = $language->get('text_new_model');
			$template->data['text_quantity'] = $language->get('text_new_quantity');
			$template->data['text_price'] = $language->get('text_new_price');
			$template->data['text_total'] = $language->get('text_new_total');
			$template->data['text_footer'] = $language->get('text_new_footer');
			$template->data['text_powered'] = $language->get('text_new_powered');

				//	$template->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');		
			$template->data['store_name'] = $order_info['store_name'];
			$template->data['store_url'] = $order_info['store_url'];
			$template->data['customer_id'] = $order_info['customer_id'];
			$template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
			$template->data['link_tracking'] = $template->getTracking($template->data['link']);

			if ($order_download_query->num_rows) {
				$template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
				$template->data['download_tracking'] = $template->getTracking($template->data['download']);
			} else {
				$template->data['download'] = '';
				$template->data['download_tracking'] = '';
			}

			$template->data['order_id'] = $order_id;
			$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));    	
			$template->data['payment_method'] = $order_info['payment_method'];
			$template->data['shipping_method'] = $order_info['shipping_method'];
			$template->data['shipping_code'] = $order_info['shipping_code'];
			$template->data['payment_code'] = $order_info['payment_code'];
			$template->data['payment_secondary_code'] = $order_info['payment_secondary_code'];
			if (in_array($order_info['payment_code'], explode(',',$this->config->get('config_confirmed_delivery_payment_ids')))){
				$template->data['payment_will_be_done_on_delivery'] = true;
			} else {
					//Есть какая-либо предоплата
				$template->data['payment_will_be_done_on_delivery'] = false;
			}
			$template->data['email'] = $order_info['email'];
			$template->data['telephone'] = $order_info['telephone'];
			$template->data['ip'] = $order_info['ip'];

			if ($comment && $notify) {
				$template->data['comment'] = $comment;
			} else {
				$template->data['comment'] = '';
			}

			if ($order_info['comment']) {
				$template->data['comment'] = str_replace(array("\r\n", "\r", "\n"), "<br />", $order_info['comment']);
			} else {
				$template->data['comment'] = '';
			}

			if ($comment && $notify && $template->data['comment'] != $comment) {
				$template->data['instruction'] = str_replace(array("\r\n", "\r", "\n"), "<br />", $comment);
			} else {
				$template->data['instruction'] = '';
			}

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']  
			);

			$template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));						

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']  
			);

			$template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				// Products
			$template->data['products'] = array();

			foreach ($order_product_query->rows as $product) {
				$option_data = array();

				$order_option_query = $this->db->ncquery("SELECT oo.*, pov.* FROM order_option oo LEFT JOIN product_option_value pov ON (pov.product_option_value_id = oo.product_option_value_id) WHERE oo.order_id = '" . (int)$order_id . "' AND oo.order_product_id = '" . (int)$product['order_product_id'] . "'");

				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'price'  => $price,
						'price_prefix'  => $option['price_prefix'],
						'stock_quantity'  => $option['quantity'],
						'stock_subtract'  => $option['subtract'],
						'value' => (utf8_strlen($value) > 120 ? utf8_substr($value, 0, 120) . '..' : $value)
					);					
				}

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



				$product_data = $this->model_catalog_product->getProduct($product['product_id']);

				if (isset($product_data['image'])) {
					$image = $this->model_tool_image->resize($product_data['image'], 50, 50);
				} else {
					$image = '';
				}

				$url = $this->url->link('product/product', 'product_id='.$product['product_id'], 'SSL');

				$template->data['products'][] = array(
					'product_id'       => $product_data['product_id'],
					'url'     		   => $url,
					'url_tracking' 	   => $template->getTracking($url),
					'image'     	   => $image,
					'weight'		   => ($product_data['weight'] > 0) ? $this->weight->format($product_data['weight'], $product_data['weight_class_id']) : 0, 
					'description'      => $product_data['description'],
					'manufacturer'     => $product_data['manufacturer'],
					'sku'              => $product_data['sku'],
					'stock_status'     => $product_data['stock_status'],
					'stock_subtract'   => $product_data['subtract'],
					'stock_quantity'   => ($product_data['quantity'] - $product['quantity']),
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $price,
					'total'    => $total,
				);
			}

				// Vouchers
			$template->data['vouchers'] = array();

			foreach ($order_voucher_query->rows as $voucher) {
				$template->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$template->data['totals'] = $order_total_query->rows;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
					//	$html = $template->fetch($this->config->get('config_template') . '/template/mail/order.tpl');
			} else {
					//	$html = $template->fetch('default/template/mail/order.tpl');
			}

			$template->data['customer_id'] = $order_info['customer_id'];
			$template->data['customer_name'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
			$template->data['customer_firstname'] = $order_info['firstname'];
			$template->data['customer_lastname'] = $order_info['lastname'];

					// Text Mail
			$text = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
			$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
			$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";

			if ($comment && $notify) {
				$text .= $language->get('text_new_instruction') . "\n\n";
				$text .= $comment . "\n\n";
			}

					// Products
			$text .= $language->get('text_new_products') . "\n";

			foreach ($order_product_query->rows as $product) {
				$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

				$order_option_query = $this->db->ncquery("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

				foreach ($order_option_query->rows as $option) {
					$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
				}
			}

			foreach ($order_voucher_query->rows as $voucher) {
				$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
			}

			$text .= "\n";

			$text .= $language->get('text_new_order_total') . "\n";

			foreach ($order_total_query->rows as $total) {
				$text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}

			$text .= "\n";

			if ($order_info['customer_id']) {
				$text .= $language->get('text_new_link') . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
			}

			if ($order_download_query->num_rows) {
				$text .= $language->get('text_new_download') . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
			}

					// Comment
			if ($order_info['comment']) {
				$text .= $language->get('text_new_comment') . "\n\n";
				$text .= $order_info['comment'] . "\n\n";
			}

			$text .= $language->get('text_new_footer') . "\n\n";

			$mail = new Mail($this->registry);
			$mail->setTo($order_info['email']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$template_data = array(
				'key' => 'order.customer',
				'order_status_id' => $order_status_id
			);

			$template->load($template_data);

			$mail = $template->hook($mail); 
			if(!empty($template->data['emailtemplate']) && $template->data['emailtemplate']['attach_invoice'] && ($this->config->get('config_complete_status_id') == $order_status_id) && $order_info && $order_info['invoice_no'] == '') {
				$query = $this->db->ncquery("SELECT MAX(invoice_no) AS invoice_no FROM `order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

				if ($query->row['invoice_no']) {
					$invoice_no = $query->row['invoice_no'] + 1;
				} else {
					$invoice_no = 1;
				}

				$this->db->ncquery("UPDATE `order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

				$order_info['invoice_no'] = $invoice_no;
			}
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			$template->sent();

			$order_product_query = $this->db->ncquery("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");

			$this->smsAdaptor->sendNewOrder($order_info);

			if ($this->config->get('config_alert_mail')) {

				$template->data['order_link'] = (defined('HTTP_ADMIN') ? HTTP_ADMIN : HTTP_SERVER.'admin/') . 'index.php?route=sale/order/info&order_id=' . $order_id;					
				$template->data['order_weight'] = $this->weight->format($order_info['weight'], $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));

				$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);
				$subject = sprintf($language->get('text_new_subject_admin'), $order_id, html_entity_decode($template->data['customer_name'], ENT_QUOTES, 'UTF-8'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

					// Text 
				$text  = $language->get('text_new_received') . "\n\n";
				$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
				$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
				$text .= $language->get('text_new_products') . "\n";

				foreach ($order_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";

					$order_option_query = $this->db->ncquery("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");

					foreach ($order_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}

						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}

				foreach ($order_voucher_query->rows as $voucher) {
					$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
				}

				$text .= "\n";

				$text .= $language->get('text_new_order_total') . "\n";

				foreach ($order_total_query->rows as $total) {
					$text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
				}			

				$text .= "\n";

				if ($order_info['comment']) {
					$text .= $language->get('text_new_comment') . "\n\n";
					$text .= $order_info['comment'] . "\n\n";
				}

				$mail = new Mail($this->registry); 
				$mail->setTo($this->config->get('config_email'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
				$template->load('order.admin');
				$template->build();
				$mail = $template->hook($mail);
				$mail->send();					
				$template->sent();

				if ($this->config->get('config_alert_emails')){
					$emails = explode(',', $this->config->get('config_alert_emails'));

					foreach ($emails as $email) {
						if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
							$mail->setTo($email);
							$mail->send();
						}
					}	
				}
							
			}		
		}
	}


	public function update($order_id, $order_status_id, $comment = '', $notify = false, $user_id = false) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && $order_info['order_status_id']) {
			if ($this->config->get('config_fraud_detection')) {
				$this->load->model('checkout/fraud');

				$risk_score = $this->model_checkout_fraud->getFraudScore($order_info);

				if ($risk_score > $this->config->get('config_fraud_score')) {
					$order_status_id = $this->config->get('config_fraud_status_id');
				}
			}			

			$status = false;

			$this->load->model('account/customer');

			if ($order_info['customer_id']) {
				$results = $this->model_account_customer->getIps($order_info['customer_id']);
				foreach ($results as $result) {
					if ($this->model_account_customer->isBanIp($result['ip'])) {
						$status = true;
						break;
					}
				}
			} else {
				$status = $this->model_account_customer->isBanIp($order_info['ip']);
			}

			if ($status) {
				$order_status_id = $this->config->get('config_order_status_id');
			}		

			$this->db->ncquery("UPDATE `order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

			$this->db->ncquery("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW(), user_id = '" . (int)$user_id . "'");

				// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->load->model('checkout/voucher');

				$this->model_checkout_voucher->confirm($order_id);
			}

			$order_status_query = $this->db->ncquery("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];	
			} else {
				$order_status = '';
			}

			$data = array(
				'type' => 'success',
				'text' => 'Новый статус заказа: '.$order_status, 
				'entity_type' => 'order', 
				'entity_id'=> $order_id
			);

			$this->mAlert->insertAlertForGroup('sales', $data);		

			if ($notify) {
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_filename']);
				$language->load('mail/order');

				$subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

				$template = new EmailTemplate($this->request, $this->registry);

				$template->addData($order_info);
				$template->addData('comment', $comment);

				$template->data['order_url'] = ($order_info['customer_id']) ? ($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id) : '';
				$template->data['order_url_tracking'] = $template->getTracking($template->data['order_url']);
				$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));

				$message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

				$order_status_query = $this->db->ncquery("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

				if ($order_status_query->num_rows) {
					$message .= $language->get('text_update_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";
					$template->data['order_status'] = $order_status_query->row['name'];
				}

				if ($order_info['customer_id']) {
					$message .= $language->get('text_update_link') . "\n";
					$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
				}

				if ($comment) { 
					$message .= $language->get('text_update_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}

				$message .= $language->get('text_update_footer');

				$mail = new Mail($this->registry);			
				$mail->setTo($order_info['email']);										
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$template_data = array(
					'key' => 'order.customer_update',
					'order_status_id' => $order_status_id
				);					
				$template->load($template_data);
				$mail = $template->hook($mail);
				$mail->send();
				$template->sent();
			}
		}
	}

	public function addOrderToQueue($order_id){
		$this->db->ncquery("INSERT IGNORE INTO order_to_1c_queue SET `order_id` = '" . (int)$order_id . "'");
	}

	public function getOrderTotals($order_id, $code = false) {
		if ($code){
			$query = $this->db->ncquery("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' AND code = '" . $this->db->escape(trim($code)) . "' ORDER BY sort_order");
			return $query->row;
		}

		$query = $this->db->ncquery("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");			
		return $query->rows;
	}
}																										
<?php
class ModelCheckoutCoupon extends Model {

	public function countTotalActiveCartProducts($coupon_info, $limit = 3){
		$counter = 0;

		foreach ($this->cart->getProducts() as $product) {
			if (in_array($product['product_id'], $coupon_info['product'])) {
				$counter++;
			}
		}	

		return ($counter >= $limit);
	}

	public function getCheapestCartProductFromActive($coupon_info, $type = 'min'){
		$min = 0;
		$product_id = 0;

		foreach ($this->cart->getProducts() as $product) {
			if (in_array($product['product_id'], $coupon_info['product'])) {
				if ($product['price'] <= $min){
					$min 		= $product['price'];
					$product_id = $product['product_id'];
				}

				if ($min == 0){
					$min = $product['price'];
					$product_id = $product['product_id'];
				}
			}
		}	

		if ($type == 'product'){
			return $product_id;
		} else {
			return $min;
		}			
	}


	public function getCoupon($code) {
		$status = true;

		$coupon_query = $this->db->non_cached_query("SELECT * FROM `coupon` WHERE code = '" . $this->db->escape($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

		if ($coupon_query->num_rows) {

			$sub_total = $this->cart->getSubTotal();

			$coupon_query->row['min_currency'] = trim($coupon_query->row['min_currency']);
			if ($coupon_query->row['min_currency'] != ''){
				$subtotal_national = $this->currency->convert($sub_total, $this->config->get('config_currency'), $coupon_query->row['min_currency']);
			} else {
				$subtotal_national = $sub_total;								
			}								

			if ((float)$coupon_query->row['total'] > 0){
				if ((float)$coupon_query->row['total'] >= (float)$subtotal_national) {
					$status = false;
				}
			}					

			$coupon_history_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_total'])) {
				$status = false;
			}

			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = false;
			}									

			if ($coupon_query->row['birthday'] && !$this->customer->getId()){
				$status = false;
			}					

			if ($coupon_query->row['birthday'] && !$this->customer->getBirthday()){
				$status = false;
			}

			if ($coupon_query->row['birthday'] && $this->customer->getId()  && $this->customer->getBirthday()){								
				$coupon_history_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `coupon_history` ch WHERE 
					ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "' AND DATE(date_added) >= DATE_SUB(DATE(NOW()), INTERVAL 1 YEAR)");

				if ($coupon_history_query->row['total'] >= 1){
					$status = false;
				}	
			}						

			if (($coupon_query->row['days_from_send'] && !$coupon_query->row['actiontemplate_id']) || (!$coupon_query->row['days_from_send'] && $coupon_query->row['actiontemplate_id'])){
				$status = false;
			}

			if ($coupon_query->row['days_from_send'] && $coupon_query->row['actiontemplate_id'] && !$this->customer->getId()){
				$status = false;
			}										

			if ($coupon_query->row['days_from_send'] && $coupon_query->row['actiontemplate_id'] && $this->customer->getId()){
				$date_last_allowed_use = $this->db->non_cached_query("SELECT DATE(DATE_ADD(date_sent, INTERVAL '" . (int)$coupon_query->row['days_from_send'] . "' DAY)) as date_last_allowed_use FROM emailmarketing_logs WHERE actiontemplate_id = '" . (int)$coupon_query->row['actiontemplate_id'] . "' AND customer_id = '" . $this->customer->getId() . "' ORDER BY date_sent DESC LIMIT 1");
					
					if (!$date_last_allowed_use->num_rows || !isset($date_last_allowed_use->row['date_last_allowed_use'])){
						$status = false;
					} else {			
						
						if (date() > date(strtotime($date_last_allowed_use->row['date_last_allowed_use']))){
							$status = false;
						}
					}			
				}
				
				if ($this->customer->getId()) {
					$coupon_history_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `coupon_history` ch WHERE ch.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND ch.customer_id = '" . (int)$this->customer->getId() . "'");
					
					if ($coupon_query->row['uses_customer'] > 0 && ($coupon_history_query->row['total'] >= $coupon_query->row['uses_customer'])) {
						$status = false;
					}
				}
				
				
				$coupon_product_data = [];				
				$coupon_product_query = $this->db->non_cached_query("SELECT * FROM `coupon_product` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");				
				
				foreach ($coupon_product_query->rows as $product) {
					$coupon_product_data[] = $product['product_id'];
				}

				if (!empty($coupon_query->row['action_id'])){
					$action_product_query = $this->db->non_cached_query("SELECT * FROM `actions_to_product` WHERE actions_id = '" . (int)$coupon_query->row['action_id'] . "'");

					foreach ($action_product_query->rows as $product) {
						$coupon_product_data[] = $product['product_id'];
					}
				}

				$coupon_product_data = array_unique($coupon_product_data);
				
				$coupon_category_data = [];				
				$coupon_category_query = $this->db->non_cached_query("SELECT * FROM `coupon_category` cc LEFT JOIN `category_path` cp ON (cc.category_id = cp.path_id) WHERE cc.coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_category_query->rows as $category) {
					$coupon_category_data[] = $category['category_id'];
				}
				

				$coupon_collection_data = [];			
				$coupon_collection_query = $this->db->non_cached_query("SELECT * FROM `coupon_collection` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_collection_query->rows as $collection) {
					$coupon_collection_data[] = $collection['collection_id'];
				}
				
				$coupon_manufacturer_data = [];				
				$coupon_manufacturer_query = $this->db->non_cached_query("SELECT * FROM `coupon_manufacturer` WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");
				
				foreach ($coupon_manufacturer_query->rows as $manufacturer) {
					$coupon_manufacturer_data[] = $manufacturer['manufacturer_id'];
				}
				
				$product_data = [];
				
				if ($coupon_product_data || $coupon_category_data || $coupon_manufacturer_data || $coupon_collection_data) {
					foreach ($this->cart->getProducts() as $product) {						
						if ($coupon_query->row['only_in_stock']){
							if (!$product['current_in_stock']){
								continue;
							}
						}
						
						if (in_array($product['product_id'], $coupon_product_data)) {
							$product_data[] = $product['product_id'];							
							continue;
						}
						
						foreach ($coupon_category_data as $category_id) {
							$coupon_category_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `product_to_category` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND category_id = '" . (int)$category_id . "'");
							
							if ($coupon_category_query->row['total']) {
								$product_data[] = $product['product_id'];
								continue;
							}						
						}
						
						foreach ($coupon_manufacturer_data as $manufacturer_id) {
							$coupon_manufacturer_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `product` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND manufacturer_id = '" . (int)$manufacturer_id . "'");
							
							if ($coupon_manufacturer_query->row['total']) {
								$product_data[] = $product['product_id'];
								continue;
							}						
						}
						
						foreach ($coupon_collection_data as $collection_id) {
							$coupon_collection_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `product` WHERE `product_id` = '" . (int)$product['product_id'] . "' AND collection_id = '" . (int)$collection_id . "'");
							
							if ($coupon_collection_query->row['total']) {
								$product_data[] = $product['product_id'];
								continue;
							}						
						}
					}
				} else {
					foreach ($this->cart->getProducts() as $product) {						
						if ($coupon_query->row['only_in_stock']){
							if (!$product['current_in_stock']){
								continue;
							}
						}

						$product_data[] = $product['product_id'];
					}
				}	

				if ($this->config->get('discount_regular_status') && $this->customer->isLogged() && $this->customer->getHasBirthday() && $product_data) {
					$_tmp_product_data = [];
					foreach ($product_data as $__product_id){
						$check_birthday_category_query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `product_to_category` WHERE `product_id` = '" . (int)$__product_id . "' AND category_id = '" . (int)BIRTHDAY_DISCOUNT_CATEGORY . "'");

						if (!$check_birthday_category_query->row['total']) {
							$_tmp_product_data[] = $__product_id;
						}
					}

					$product_data = $_tmp_product_data;
				}

				if (!$product_data) {
					$status = false;
				}


			} else {
				$status = false;
			}
			
			if ($status) {
				return array(
					'coupon_id'     => $coupon_query->row['coupon_id'],
					'code'          => $coupon_query->row['code'],
					'name'          => $coupon_query->row['name'],
					'type'          => $coupon_query->row['type'],
					'discount'      => $coupon_query->row['discount'],
					'discount_sum'  => $coupon_query->row['discount_sum'],
					'only_in_stock' => $coupon_query->row['only_in_stock'],
					'currency'      => $coupon_query->row['currency'],
					'shipping'      => $coupon_query->row['shipping'],
					'total'         => $coupon_query->row['total'],
					'product'       => $product_data,
					'date_start'    => $coupon_query->row['date_start'],
					'date_end'      => $coupon_query->row['date_end'],
					'uses_total'    => $coupon_query->row['uses_total'],
					'uses_customer' => $coupon_query->row['uses_customer'],
					'status'        => $coupon_query->row['status'],
					'date_added'    => $coupon_query->row['date_added']
				);
			}
		}
		
		public function redeem($coupon_id, $order_id, $customer_id, $amount) {
			$this->db->non_cached_query("INSERT INTO `coupon_history` SET coupon_id = '" . (int)$coupon_id . "', order_id = '" . (int)$order_id . "', customer_id = '" . (int)$customer_id . "', amount = '" . (float)$amount . "', date_added = NOW()");
		}
	}		
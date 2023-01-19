<?php
	class ModelAccountOrder extends Model {
	
		public function getYamOrder($yam_id) {
		
			$order_query = $this->db->non_cached_query("SELECT * FROM `order` WHERE yam_id = '" . (int)$yam_id . "'");
			
			if ($order_query->num_rows){
				return $order_query->row['order_id'];			
			} else {
				return false;
			}
		}
		
		public function getYamOrdersByShipmentID($yam_shipment_id) {
		
			$order_query = $this->db->non_cached_query("SELECT * FROM `order` WHERE yam_shipment_id = '" . (int)$yam_shipment_id . "'");
			
			if ($order_query->num_rows){
				return $order_query->rows;			
			} else {
				return false;
			}
		}
		
		public function getOrderByBoxID($yam_box_id) {
		
			$order_query = $this->db->non_cached_query("SELECT * FROM `order` WHERE yam_box_id = '" . (int)$yam_box_id . "' ORDER BY date_added DESC LIMIT 1");
			
			if ($order_query->num_rows){
				return $order_query->row;			
			} else {
				return false;
			}
		}
		
		public function getOrderYam($order_id) {
		
			$order_query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			if ($order_query->num_rows && $order_query->row['yam_id']){
				return $order_query->row['yam_id'];			
			} else {
				return false;
			}
		}
		
		public function updateYamBox($yam_id, $yam_box_id) {
		
			$this->db->non_cached_query("UPDATE `order` SET yam_box_id = '" . (int)$yam_box_id . "' WHERE yam_id = '" . (int)$yam_id . "'");
		}
		
		public function getOrder($order_id, $no_customer = false) {
			
			if ($no_customer) {
				$order_query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "' AND order_status_id > '0'");		
				} else {
				$order_query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");
			}
			
			if ($order_query->num_rows) {
				$country_query = $this->db->non_cached_query("SELECT * FROM `country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$payment_iso_code_2 = $country_query->row['iso_code_2'];
					$payment_iso_code_3 = $country_query->row['iso_code_3'];
					} else {
					$payment_iso_code_2 = '';
					$payment_iso_code_3 = '';				
				}
				
				$zone_query = $this->db->non_cached_query("SELECT * FROM `zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$payment_zone_code = $zone_query->row['code'];
					} else {
					$payment_zone_code = '';
				}
				
				$country_query = $this->db->non_cached_query("SELECT * FROM `country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$shipping_iso_code_2 = $country_query->row['iso_code_2'];
					$shipping_iso_code_3 = $country_query->row['iso_code_3'];
					} else {
					$shipping_iso_code_2 = '';
					$shipping_iso_code_3 = '';				
				}
				
				$zone_query = $this->db->non_cached_query("SELECT * FROM `zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$shipping_zone_code = $zone_query->row['code'];
					} else {
					$shipping_zone_code = '';
				}
				
				return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],				
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
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
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_sent'           		=> $order_query->row['date_sent'],
				'date_buy'           		=> $order_query->row['date_buy'],
				'date_country'           => $order_query->row['date_country'],
				'date_delivery'          => $order_query->row['date_delivery'],
				'date_delivery_to'       => $order_query->row['date_delivery_to'],
				'date_delivery_actual'       	=> $order_query->row['date_delivery_actual'],
				'display_date_in_account'       => $order_query->row['display_date_in_account'],
				'wait_full'				 => isset($order_query->row['wait_full'])?$order_query->row['wait_full']:0,
				'urgent'				 => isset($order_query->row['urgent'])?$order_query->row['urgent']:0,
				'ip'                     => $order_query->row['ip'],
				'manager_id'             => $order_query->row['manager_id'],
				'pay_equire'			 => $order_query->row['pay_equire'],
				'pay_equire2'			 => $order_query->row['pay_equire2'],
				'pay_equirePP'			 => $order_query->row['pay_equirePP'],
				'pay_equireLQP'			 => $order_query->row['pay_equireLQP'],
				'pay_equireCP'			 => $order_query->row['pay_equireCP'],
				'preorder'			 	 => $order_query->row['preorder']
				);
				} else {
				return false;	
			}
		}
		
		public function getOrderProductsNoGood($order_id) {
			$this->load->model('localisation/language');
			$de_language_id = $this->model_localisation_language->getLanguageByCode($this->config->get('config_de_language'));
			
			$query = $this->db->query("SELECT op.*,p.image, p.short_name, p.product_id, p.stock_status_id, (SELECT name FROM " . DB_PREFIX . "product_description WHERE product_id = op.product_id AND language_id = '" . (int)$de_language_id . "' LIMIT 1) as de_name FROM " . DB_PREFIX . "order_product_nogood op LEFT JOIN " . DB_PREFIX . "product p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' ORDER BY op.name");
			
			return $query->rows;
		}
		
		public function getFilterOrders($data = array()) {		
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			
			if ($data['limit'] < 1) {
				$data['limit'] = 1;
			}	
			
			$sql = "SELECT o.order_id, 
				o.firstname, 
				o.lastname, 
				os.name as status, 
				os.status_txt_color as status_txt_color, 
				os.status_bg_color as status_bg_color, 
				os.front_bg_color as front_bg_color, 
				o.date_added, 
				o.preorder, 
				o.total, 
				o.manager_id, 
				o.total_national, 
				o.currency_code, 
				o.currency_value, 
				o.payment_method, 
				o.payment_code 
				FROM `order` o LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id) 
				WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0'";
			
			if ($data['filter'] == 'cancelled'){
				$sql .= " AND o.order_status_id = '" . $this->config->get('config_cancelled_status_id') . "'";
			}
			
			if ($data['filter'] == 'completed'){
				$sql .= " AND o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'";
			}
			
			if ($data['filter'] == 'inprocess'){
				$sql .= " AND o.order_status_id NOT IN (". implode(',', [$this->config->get('config_complete_status_id'), $this->config->get('config_cancelled_status_id')]) .")";
			}
			
			$sql .= "AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];	
		
			$query = $this->db->non_cached_query($sql);
		
			return $query->rows;				
		}
		
		public function getTotalFilterOrders($data = array()) {
			$sql =  "SELECT COUNT(*) AS total FROM `order` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0' ";
						
			if ($data['filter'] == 'cancelled'){
				$sql .= " AND order_status_id = '" . $this->config->get('config_cancelled_status_id') . "'";
			}
			
			if ($data['filter'] == 'completed'){
				$sql .= " AND order_status_id = '" . $this->config->get('config_complete_status_id') . "'";
			}
			
			if ($data['filter'] == 'inprocess'){
				$sql .= " AND order_status_id NOT IN (". implode(',', [$this->config->get('config_complete_status_id'), $this->config->get('config_cancelled_status_id')]) .")";
			}
			
			$query = $this->db->non_cached_query($sql);
			
			return $query->row['total'];
		}
		
		public function getOrders($start = 0, $limit = 20) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 1;
			}	
			
			$query = $this->db->non_cached_query("SELECT 
				o.order_id, 
				o.firstname, 
				o.lastname, 
				o.order_status_id, 
				o.ttn,
				os.name as status, 
				os.status_txt_color as status_txt_color, 
				os.status_bg_color as status_bg_color, 
				os.front_bg_color as front_bg_color, 
				o.date_added, 
				o.preorder, 
				o.total, 
				o.manager_id, 
				o.total_national, 
				o.currency_code, 
				o.currency_value, 
				o.payment_method, 
				o.payment_code FROM `order` o 
				LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id) 
				WHERE o.customer_id = '" . (int)$this->customer->getId() . "' 
				AND o.order_status_id > '0' 
				AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);							
			
			return $query->rows;
		}
		
		public function getOrderProducts($order_id) {
			$query = $this->db->non_cached_query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderProductsListNoGood($order_id) {
			$query = $this->db->non_cached_query("SELECT * FROM order_product_nogood WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getProductImage($product_id) {
			$query = $this->db->non_cached_query("SELECT image FROM product WHERE product_id = '" . (int)$product_id . "'");
			
			return isset($query->row['image'])?$query->row['image']:'';
		}
		
		public function getOrderOptions($order_id, $order_product_id) {
			$query = $this->db->non_cached_query("SELECT * FROM order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderVouchers($order_id) {
			$query = $this->db->non_cached_query("SELECT * FROM `order_voucher` WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderTotals($order_id, $code = false) {
			
			if ($code){
				$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' AND code = '" . $this->db->escape(trim($code)) . "' ORDER BY sort_order");
				
				return $query->row;
			}
			
			$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");			
			
			return $query->rows;
		}
		
		public function getOrderTotalNational($order_id){
			$query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total_national'];	
		}
		
		public function getOrderTotal($order_id){
			$query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];	
		}
		
		public function getOrderPrepay($order_id){
			$query = $this->db->non_cached_query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
			
			foreach ($query->rows as $row){
				if ($row['code'] == 'transfer_plus_prepayment'){
					return $row['value'];				
				}			
			}
			return false;
		}
		
		public function getOrderPrepayNational($order_id){
			$query = $this->db->non_cached_query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");
			
			foreach ($query->rows as $row){
				if ($row['code'] == 'transfer_plus_prepayment'){
					return $row['value_national'];				
				}			
			}
			return false;
		}
		
		public function getOrderTtnHistory($order_id){
			$query = $this->db->non_cached_query("SELECT * FROM `order_ttns` WHERE `order_id` = '" . (int)$order_id."'");
			
			return $query->rows;		
		}
		
		public function getOrderHistories($order_id) {
			$query = $this->db->non_cached_query("SELECT date_added, os.name AS status, oh.order_status_id, oh.comment, oh.notify FROM order_history oh LEFT JOIN order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND oh.notify = '1' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
			
			return $query->rows;
		}	
		
		public function getOrderHistoriesFull($order_id) {
			$query = $this->db->non_cached_query("SELECT date_added, os.name AS status, oh.order_status_id, oh.comment, oh.notify FROM order_history oh LEFT JOIN order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
			
			return $query->rows;
		}	
		
		
		public function getOrderDownloads($order_id) {
			$query = $this->db->non_cached_query("SELECT * FROM order_download WHERE order_id = '" . (int)$order_id . "' ORDER BY name");
			
			return $query->rows; 
		}	
		
		public function getTotalOrders() {
			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `order` WHERE customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrderProductsByOrderId($order_id) {
			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrderVouchersByOrderId($order_id) {
			$query = $this->db->non_cached_query("SELECT COUNT(*) AS total FROM `order_voucher` WHERE order_id = '" . (int)$order_id . "'");
			
			return $query->row['total'];
		}
	}
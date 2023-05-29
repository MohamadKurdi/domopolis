<?php
class ModelSaleReceipt extends Model {
	public function deleteReceipt($order_id) {
		$this->db->query("DELETE FROM `order_receipt` WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, o.email, o.telephone, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.order_status_id, (SELECT os.name FROM order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status, o.shipping_code, o.shipping_method, o.payment_method, o.payment_code, o.total, o.total_national, o.currency_code, o.currency_value, o.date_added, o.date_modified, orec.receipt_id, orec.serial, orec.fiscal_code, orec.is_created_offline, orec.is_sent_dps, orec.sent_dps_at, orec.fiscal_date  FROM `order` o";
		
		$sql .= " LEFT JOIN order_receipt orec ON (o.order_id = orec.order_id)  ";
		

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (!empty($data['filter_fiscal_code'])) {
			$sql .= " AND orec.fiscal_code = '" . $this->db->escape($data['filter_fiscal_code']) . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if (!empty($data['filter_date_modified_2day'])) {
            $sql .= " AND (
                DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified_2day']) . "') OR
                DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified_2day']) . "') -INTERVAL 1 DAY 
                ) ";
        }

        if (isset($data['filter_has_receipt'])) {
            if($data['filter_has_receipt']){
                // замовлення з чеками
                $sql .= " AND orec.order_id > 0 "  ;
            }else{
                $sql .= " AND orec.order_id IS NULL "  ;
            }

        }

		//20.03.21
		if (!empty($data['filter_order_payment_code'])) {
			 $sql .= " AND o.payment_code = '" . $this->db->escape($data['filter_order_payment_code']) . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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
		#de($sql);
		$query = $this->db->query($sql);
		$order_data = array();
		foreach($query->rows as $order){
			$order_data[$order['order_id'] ] = $order;
			$order_data[$order['order_id'] ]['products'] = $this->getOrderProducts($order['order_id']);
			$order_data[$order['order_id'] ]['totals'] = $this->getOrderTotals($order['order_id']);
			 
			#getOrderProducts
		}

		return $order_data;
	}
		
	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `order` o";

		$sql .= " LEFT JOIN order_receipt orec ON (o.order_id = orec.order_id)  ";
		
		
		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (!empty($data['filter_fiscal_code'])) {
			$sql .= " AND orec.fiscal_code = '" . $this->db->escape($data['filter_fiscal_code']) . "'";
		}


		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_date_modified_2day'])) {
            $sql .= " AND (
                DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified_2day']) . "') OR
                DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified_2day']) . "') -INTERVAL 1 DAY 
                ) ";
        }
        
		if (isset($data['filter_has_receipt'])) {
            if($data['filter_has_receipt']){
                // замовлення з чеками
                $sql .= " AND orec.order_id > 0 "  ;
            }else{
                $sql .= " AND orec.order_id IS NULL "  ;
            }

        }

		//20.03.21
		if (!empty($data['filter_order_payment_code'])) {
			 $sql .= " AND o.payment_code = '" . $this->db->escape($data['filter_order_payment_code']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
		
		$products = $query->rows;
		//receipt_config_language
		if($this->config->get('receipt_config_language')){
			$receipt_config_language_id = 0;		
			// Language		
			$query_language = $this->db->query("SELECT * FROM `language` WHERE code = '" . $this->db->escape($this->config->get('receipt_config_language')) . "'");		
			if ($query_language->num_rows) {
				$receipt_config_language_id = $query_language->row['language_id'];
			} 
			#de($receipt_config_language_id);
			foreach($products as $key=> $product){
				$products[$key]['name'] = $this->getProductNameByLanguageId($product['product_id'],$receipt_config_language_id,$product['name'] );
			}			
		}
		return $products;
	}
	
	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}
	
	public function getOrderReceipts() {
		$query = $this->db->query("SELECT * FROM order_receipt WHERE 1 ORDER BY order_receipt_id DESC LIMIT 0,5");
		#de($query->rows);
		return $query->rows;
	}

	public function getShifts() {
		$query = $this->db->query("SELECT * FROM shift WHERE z_report_id !='' ORDER BY id DESC LIMIT 0,5");
		#de($query->rows);
		return $query->rows;
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
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

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			
			$affiliate_firstname = '';
			$affiliate_lastname = '';
			

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}
			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => '',//json_decode($order_query->row['custom_field'], true),
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
				'payment_custom_field'    => '',//json_decode($order_query->row['payment_custom_field'], true),
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
				'shipping_custom_field'   => '',//json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'payment_info'           	=> isset($order_query->row['payment_info']) ? $order_query->row['payment_info'] : '',
				'products'           	  	=> $this->getOrderProducts($order_query->row['order_id']),
				'totals'           	  		=> $this->getOrderTotals($order_query->row['order_id']),
			);
			
		} else {
			return;
		}
	}
	
	public function getProductNameByLanguageId($product_id,$language_id, $order_product__name) {
		$query = $this->db->query("SELECT DISTINCT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$language_id . "'");
		if(isset($query->row['name'])){
			return $query->row['name'];
		}
		return $order_product__name;		
	}
	
	
	public function changeOneSettingKey($key){ 
	}

    public function getAllSystemPayments(){
        $payment_extensions = array();
        $this->load->model('setting/extension');
        $extensions = $this->model_setting_extension->getInstalled('payment');

        foreach ($extensions as $key => $value) {
            if (!file_exists(DIR_APPLICATION . 'controller/payment/' . $value . '.php')) {
                $this->model_setting_extension->uninstall('payment', $value);
                unset($extensions[$key]);
            }
        }

        $files = glob(DIR_APPLICATION . 'controller/payment/*.php');
        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');               
                if($this->config->get($extension . '_status') && in_array($extension, $extensions)){ 
                	$this->language->load('payment/' . $extension);
                    $payment_extensions[] = array(
                        'name'       => $this->language->get('heading_title'),
                        'code'       => $extension
                    );
                }
            }
        }
        return $payment_extensions;
    }

	public function getAllSystemShippings(){
        $shipping_extensions = array();
        $this->load->model('setting/extension');
        $extensions = $this->model_setting_extension->getInstalled('shipping');
        foreach ($extensions as $key => $value) {
            if (!file_exists(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
                $this->model_setting_extension->uninstall('shipping', $value);
                unset($extensions[$key]);
            }
        }

        $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');                
                if($this->config->get($extension . '_status') && in_array($extension, $extensions)){
                	$this->language->load('shipping/' . $extension);
                    $shipping_extensions[] = array(
                        'name'       => $this->language->get('heading_title'),
                        'code'       => $extension
                    );
                }
            }
        } 
        return $shipping_extensions;
    }

    public function getAllSystemTotals(){
    	$total_extensions = array();
        $no_discount = array('sub_total','tax','total');

        $this->load->model('setting/extension');
        $extensions = $this->model_setting_extension->getInstalled('total');
 
        foreach ($extensions as $key => $value) {
            if (!file_exists(DIR_APPLICATION . 'controller/total/' . $value . '.php')) {
                $this->model_setting_extension->uninstall('total', $value);
                unset($extensions[$key]);
            }
            if(in_array($value, $no_discount) ){
                unset($extensions[$key]);
            }
        }

        $files = glob(DIR_APPLICATION . 'controller/total/*.php');
        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');
                
                    if($this->config->get($extension . '_status') && in_array($extension, $extensions)){ 
                    	$this->language->load('total/' . $extension);
                        $total_extensions[] = array(
                            'name'       => $this->language->get('heading_title'). ' ['.$extension.']',
                            'code'       => $extension
                        );
                    }
            }
        }

        return $total_extensions;
    }
}
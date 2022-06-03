<?php
	/**
		* Liqpay Payment Module
		*
		* NOTICE OF LICENSE
		*
		* This source file is subject to the Open Software License (OSL 3.0)
		* that is available through the world-wide-web at this URL:
		* http://opensource.org/licenses/osl-3.0.php
		*
		* @category        Liqpay
		* @package         Payment
		* @version         3.0
		* @author          Liqpay
		* @copyright       Copyright (c) 2014 Liqpay
		* @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
		*
		* EXTENSION INFORMATION
		*
		* OpenCart         1.5.6
		* LiqPay API       https://www.liqpay.com/ru/doc
		*
	*/
	
	/**
		* Payment method liqpay model (catalog)
		*
		* @author      Liqpay <support@liqpay.com>
	*/
	class ModelPaymentLiqPay extends Model
	{
		const METHOD_CODE = 'liqpay';
		
		/**
			* Index action
			*
			* @return void
		*/
		public function getMethod($address, $total, $explicit_show = false)
		{
			$this->language->load('payment/liqpay');
			
			$tbl_zone_to_geo_zone = DB_PREFIX.'zone_to_geo_zone';
			$liqpay_geo_zone_id = (int)$this->config->get('liqpay_geo_zone_id');
			$country_id = (int)$address['country_id'];
			$zone_id = (int)$address['zone_id'];
			
			$sql = "
			SELECT *
			FROM {$tbl_zone_to_geo_zone}
			WHERE geo_zone_id = '{$liqpay_geo_zone_id}'
			AND country_id = '{$country_id}'
			AND (zone_id = '{$zone_id}' OR zone_id = '0')
			";
			
			$query = $this->db->query($sql);
			
			if ($this->config->get('liqpay_total') > 0 && $this->config->get('liqpay_total') > $total) {
				$status = false;
				} elseif (!$this->config->get('liqpay_geo_zone_id')) {
				$status = true;
				} elseif ($query->num_rows) {
				$status = true;
				} else {
				$status = false;
			}
			
			$method_data = array();
			
			/*	
				$status = false;
				if ($_SERVER['REMOTE_ADDR'] == '37.57.184.228' || $this->customer->getID() == 1499730) {
				$status = true;
				}
				
			*/
			
			if ($status){
				$status = true;
			} else {
				$status = false;			
			}
			
			if ($status) {
				$method_data = array(
				'code'       	=> 'liqpay',
				'status'      	=> $status,
				'checkactive' 	=> true,
				'title'      	=> $this->language->get('text_title'),
				'description' 	=> $this->language->get('text_description'),
				'sort_order' 	=> $this->config->get('liqpay_sort_order')
				);
			}
			
			return $method_data;
		}
		
		public function getOrderStatusById($order_status_id, $language_id = false) {
			if (!$language_id) {
				$language_id = (int)$this->config->get('config_language_id');
			}
			$query = $this->db->non_cached_query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . $language_id . "'");
			return $query->num_rows ? $query->row['name'] : '';
		}
		
		public function getOrder($order_id) {
			$query = $this->db->non_cached_query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			$result = $query->num_rows ? $query->row : false;
			if ($result) {
				//Compatibles for v1.5.1.3 and low
				if (!isset($result['payment_code'])) {
					$method_code = self::METHOD_CODE;
					$result['payment_code'] = strtoupper($result['payment_method']) == strtoupper(self::METHOD_CODE) ? self::METHOD_CODE : '';
				}
			}
			return $result;
		}
		
		public function checkLaterpay($order_id) {
			if (($this->config->get('liqpay_status'))) {
				$order_info = $this->getOrder($order_id);
				
				if ((!$order_info)) {
					return false;
				}
				
				return $order_info['order_status_id'] == $this->config->get('config_confirmed_nopaid_order_status_id');
			}
			return false;
		}
	}

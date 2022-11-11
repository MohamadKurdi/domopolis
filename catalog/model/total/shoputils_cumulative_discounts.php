<?php
	
	class ModelTotalShoputilsCumulativeDiscounts extends Model {
		private $_tablename = 'shoputils_cumulative_discounts';
		private $_tablename_cmsdata = 'shoputils_cumulative_discounts_cmsdata';
		private $_tablename_to_store = 'shoputils_cumulative_discounts_to_store';
		private $_tablename_description = 'shoputils_cumulative_discounts_description';
		private $_tablename_to_customer_group = 'shoputils_cumulative_discounts_to_customer_group';
		private $_tablename_to_manufacturer = 'shoputils_cumulative_discounts_to_manufacturer';
		private $NOW;
		
		public function __construct($registry) {
			$this->NOW = date('Y-m-d H:i') . ':00';
			parent::__construct($registry);
		}
		
		public function getTotal(&$total_data, &$total, &$taxes) {
			if (!$this->config->get('shoputils_cumulative_discounts_status')){
				return;
			}
			
			if (!$this->customer->isLogged()) {
				return;
			}
			
			if (isset($this->session->data['coupon']) AND mb_strlen($this->session->data['coupon'])>0){
				return;
			}
			
			//проверяем не день ли у нас рожденьица..
			if ($this->config->get('discount_regular_status') && $this->customer->isLogged() && $this->customer->getHasBirthday()) {
				return;
			}
			
			if ($discount = $this->getLoggedCustomerDiscount()) {
				$this->language->load('total/shoputils_cumulative_discounts');
				$excluded_manufacturers = explode(',', str_replace(',,',',',mb_substr($discount['excluded_manufacturers'],0,-1)));
				
				$products_total = $this->cart->getSubTotalExcludeManufacturers($excluded_manufacturers);
				$products_total_national = $this->cart->getSubTotalInNationalCurrencyExcludeManufacturers($excluded_manufacturers);
				
				$discount_total = $products_total * ($discount['percent'] / 100);
				$discount_total_national = $products_total_national * ($discount['percent'] / 100);
				
				if ($discount_total > 0) {
					$total_data[] = array(
                    'code'       => 'shoputils_cumulative_discounts',
                    'title'      => sprintf($this->language->get('text_cumulative_discounts'), $discount['percent']),
                    'text'       => $this->currency->format(-$discount_total_national, $this->config->get('config_regional_currency'), '1'),
					'value_national' => -$discount_total_national,
                    'value'      => -$discount_total,
                    'sort_order' => $this->config->get('shoputils_cumulative_discounts_sort_order')
					);
					$total -= $discount_total;
				}
				}
			}
			
			public function getDiscountsCMSData($language_id) {
				$query = $this->db->non_cached_query("SELECT
				*
				FROM
				" . DB_PREFIX . $this->_tablename_cmsdata . "
				WHERE
				language_id='" . $language_id . "' AND store_id = '".$this->config->get('config_store_id')."'");
				if (isset($query->rows[0])) {
					$rows = $query->rows[0];
					} else {
					$rows = array();
				}
				return $rows;
			}
			
			public function getDiscounts($store_id, $customer_group_id, $language_id, $sort_order = 'ASC') {
				$sql = 'SELECT
				d.discount_id,
				d.days,
				d.summ,
				d.currency,
				d.percent,
				d.products_special,
				d.first_order,
				dd.description,
				(SELECT GROUP_CONCAT(m.manufacturer_id, ",") FROM '.DB_PREFIX . $this->_tablename_to_manufacturer . ' m WHERE m.discount_id = d.discount_id GROUP BY m.discount_id ) as excluded_manufacturers
                FROM
				'.DB_PREFIX . $this->_tablename . ' d
                LEFT JOIN
				'.DB_PREFIX . $this->_tablename_description . ' dd ON (d.discount_id = dd.discount_id)
                LEFT JOIN
				'.DB_PREFIX . $this->_tablename_to_store . ' d2s ON (d.discount_id = d2s.discount_id)
                LEFT JOIN
				'.DB_PREFIX . $this->_tablename_to_customer_group . ' d2cg ON (d.discount_id = d2cg.discount_id)
				LEFT JOIN
				'.DB_PREFIX . $this->_tablename_to_manufacturer . ' d2m ON (d.discount_id = d2m.discount_id)
                WHERE
				d2s.store_id="' . $store_id . '" AND
				d2cg.customer_group_id="' . $customer_group_id . '" AND
				dd.language_id="' . $language_id . '"
                ORDER BY 
				d.percent '. $sort_order;
				
				$query = $this->db->non_cached_query($sql);
				
				return $query->rows;
			}
			
			public function getCustomerDiscount($store_id, $customer_group_id, $language_id, $customer_id) {
				if (!$this->config->get('shoputils_cumulative_discounts_statuses')) {
					return false;
				}
				$discounts = $this->getDiscounts($store_id, $customer_group_id, $language_id, 'DESC');
				
				foreach ($discounts as $discount){
					
					//Search order
					$time = time() - $discount['days'] * 24 * 60 * 60;
					if ($time < 0) $time = 0;
					$date = date('Y-m-d H:i:s', $time);
					
					if ($discount['products_special']) {								
						
						$cumulative_summ = $this->getSumDiscountWithSpecial($customer_group_id, $customer_id, $store_id, $date);
						
						} else {                
						$sql = "SELECT 
						SUM((op.price + op.tax) * op.quantity) as summ, 
						SUM((op.price_national) * op.quantity) as summ_national 
						FROM `order_product` op, `". DB_PREFIX . "order` o 
						WHERE
						o.order_id = op.order_id AND
						o.customer_id='" . $customer_id . "' AND                    
						(
                        order_status_id IN (".$this->config->get('shoputils_cumulative_discounts_statuses').") AND
                        (date_added >= '" . $date . "' OR date_modified >= '" . $date . "')
						)
						
						GROUP BY o.customer_id";
						
						//	var_dump($sql);
						
						$query = $this->db->non_cached_query($sql);
						$cumulative_summ = isset($query->rows[0]['summ']) ? $query->rows[0]['summ'] : 0;
						$cumulative_summ_national = isset($query->rows[0]['summ_national']) ? $query->rows[0]['summ_national'] : 0;
						
					}
					
					
					/*
						НЕ ИСПОЛЬЗУЕТСЯ!
						if ($discount['first_order']) {
						
						if ($discount['products_special']) {
						foreach ($this->cart->getProducts() as $product) {
                        $product_id = isset($product['product_id']) ? $product['product_id'] : 0;
                        $product_info = $this->db->non_cached_query("
						SELECT price, (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= '" . $this->NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
                        if ((!$product_info->num_rows) || (!$product_info->rows[0]['discount'] && !$product_info->rows[0]['special'])) {
						$cumulative_summ += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
                        }
						}
						} else {
						$cumulative_summ += $discount['first_order'] ? $this->cart->getTotal() : 0;
						}
						
						}
					*/		
					
					if ($discount['currency']!=''){
						$discount['summ'] = $this->currency->convert($discount['summ'], $discount['currency'], $this->config->get('config_currency'));
						$cumulative_summ = $this->currency->convert($cumulative_summ_national, $discount['currency'], $this->config->get('config_currency'));
						} else {
						$discount['summ'] = $discount['summ'];
						$cumulative_summ = $cumulative_summ;
					}
					
					if ($cumulative_summ > $discount['summ']) {
						$discount['cumulative_summ'] = $cumulative_summ;
						$discount['cumulative_summ_national'] = $cumulative_summ_national;
						return $discount;
					}
				}
				return false;
			}
			
			public function getLoggedCustomerDiscount() {
				
				return $this->getCustomerDiscount(
				(int)$this->config->get('config_store_id'),
				$this->customer->getCustomerGroupId() ? $this->customer->getCustomerGroupId() : $this->config->get('config_customer_group_id'),
				(int)$this->config->get('config_language_id'),
				$this->customer->getId()
				);
				
			}
			
			public function getCurrentCustomerPreCountedDiscountInfo(){
				
				
			}
			
			public function getCurrentCustomerPreCountedDiscountPercent(){
				$query = $this->db->query("SELECT discount_percent FROM customer WHERE customer_id = '" . (int)$this->customer->getId() . "' LIMIT 1");
				
				if ($query->num_rows && isset($query->row['discount_percent'])){
					return $query->row['discount_percent'];
					} else {
					return false;
				}
				
				
				
			}
			
			protected function getSumDiscountWithSpecial($customer_group_id, $customer_id, $store_id, $date) {
				$cumulative_summ = 0;
				
				$sql = "SELECT
                op.product_id,
                op.quantity,
                op.price,
                op.total,
                op.tax,
                o.date_added
                FROM `order_product` op, `". DB_PREFIX . "order` o WHERE
                o.order_id = op.order_id AND
                o.customer_id='" . $customer_id . "' AND                
                (
				order_status_id IN (".$this->config->get('shoputils_cumulative_discounts_statuses').") AND
				(date_added >= '" . $date . "' OR date_modified >= '" . $date . "')
                )";
				$orders = $this->db->non_cached_query($sql);
				
				if ($orders->num_rows) {
					foreach ($orders->rows as $order) {
						$product_info = $this->db->non_cached_query("SELECT price, (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $order['date_added'] . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $order['date_added'] . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $order['date_added'] . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $order['date_added'] . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM product p LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$order['product_id'] . "' AND p.date_available <= '" . $order['date_added'] . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
						
						if ((!$product_info->num_rows) || (!$product_info->rows[0]['discount'] && !$product_info->rows[0]['special'])) {
							//Если товар удален или скидочной или акционной цены нет на момент времени заказа - плюсуем покупателю в накопленную сумму
							$cumulative_summ += ($order['price'] + $order['tax']) * $order['quantity'];
							//$cumulative_summ += $order['total'];
						}
					}
				}
				return $cumulative_summ;
			}
		}
	?>	
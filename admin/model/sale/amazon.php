<?
	class ModelSaleAmazon extends Model {
		
		
		public function getLastUpdate(){
			$query = $this->db->query("SELECT date_modified FROM temp WHERE `key` LIKE('amazon_last_sync') LIMIT 1");
			
			return $query->row['date_modified'];
		}
		
		private function buildRelativeDate($string){
			$now = date_create();
			
			if ($string[0] == '+'){
				$invert = false;
				} elseif ($string[0] == '-') {
				$invert = true;
				} else {
				return date();
			}
			
			$string = substr($string, 1);
			
			$_srd_ru = array('дней', 'дня', 'дня', 'день', 'неделя', 'недели', 'месяц', 'месяца', 'месяцев', 'полгода', 'год', 'года');
			$_srd_php = array('days', 'days', 'days', 'days', 'weeks', 'weeks', 'months', 'months', 'months', '6 month', 'years', 'years');
			
			$diff = date_interval_create_from_date_string(str_replace($_srd_ru, $_srd_php, $string));
			
			if ($invert){
				return date_format(date_sub($now, $diff), 'Y-m-d');
				} else {
				return date_format(date_add($now, $diff), 'Y-m-d');
			}
			
		}
		
		public function getTotalOrders($data){
			
			$sql = "SELECT COUNT(DISTINCT amazon_orders.amazon_id) as total FROM amazon_orders";
			
			if (!empty($data['filter_supplier_id'])){
				$sql .= " LEFT JOIN amazon_orders_products ON amazon_orders.amazon_id = amazon_orders_products.amazon_id WHERE 1";
				} else {
				$sql .= " WHERE 1";
			}
			
			if (!empty($data['filter_amazon_id'])){
				
				$sql .= " AND amazon_id LIKE ('". $this->db->escape($data['filter_amazon_id']) ."')";
			}
			
			if (!empty($data['filter_date_added'])) {
				if ($data['filter_date_added'][0] == '+' || $data['filter_date_added'][0] == '-'){
					$data['filter_date_added'] = $this->buildRelativeDate($data['filter_date_added']);
				}
				$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_date_added_to'])) {
				$data['filter_date_added_to'] = html_entity_decode($data['filter_date_added_to']);
				if ($data['filter_date_added_to'][0] == '+' || $data['filter_date_added_to'][0] == '-'){
					$data['filter_date_added_to'] = $this->buildRelativeDate($data['filter_date_added_to']);
				}
				$sql .= " AND DATE(date_added) <= DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
			}
			
						if (!empty($data['filter_date_delivered_from'])) {			
				if ($data['filter_date_delivered_from'][0] == '+' || $data['filter_date_delivered_from'][0] == '-'){
					$data['filter_date_delivered_from'] = $this->buildRelativeDate($data['filter_date_delivered_from']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_delivered) >= DATE('" . $this->db->escape($data['filter_date_delivered_from']) . "'))";
			}
			
			if (!empty($data['filter_date_delivered_to'])) {
				if ($data['filter_date_delivered_to'][0] == '+' || $data['filter_date_delivered_to'][0] == '-'){
					$data['filter_date_delivered_to'] = $this->buildRelativeDate($data['filter_date_delivered_to']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_delivered) <= DATE('" . $this->db->escape($data['filter_date_delivered_to']) . "')";
				
				if (empty($data['filter_date_delivered_from'])) {	
					$sql .= " AND DATE(date_delivered) >= DATE(NOW())";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_date_expected_from'])) {			
				if ($data['filter_date_expected_from'][0] == '+' || $data['filter_date_expected_from'][0] == '-'){
					$data['filter_date_expected_from'] = $this->buildRelativeDate($data['filter_date_expected_from']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_expected) >= DATE('" . $this->db->escape($data['filter_date_expected_from']) . "'))";
			}
			
			if (!empty($data['filter_date_expected_to'])) {
				if ($data['filter_date_expected_to'][0] == '+' || $data['filter_date_expected_to'][0] == '-'){
					$data['filter_date_expected_to'] = $this->buildRelativeDate($data['filter_date_expected_to']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_expected) <= DATE('" . $this->db->escape($data['filter_date_expected_to']) . "')";
				
				if (empty($data['filter_date_expected_from'])) {	
					$sql .= " AND DATE(date_expected) >= DATE(NOW())";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_is_return'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE is_return = 1)";
			}
			
			if (!empty($data['filter_is_problem'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE is_problem = 1)";
			}
			
			if (!empty($data['filter_is_dispatched'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE is_dispatched = 1)";
			}
			
			if (!empty($data['filter_asin'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE asin LIKE ('" . $this->db->escape($data['filter_asin']) . "'))";
			}
			
			if (!empty($data['filter_product_id'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE asin = (SELECT asin FROM product WHERE product_id = '" . (int)$data['filter_product_id'] . "' AND stock_product_id = 0 ORDER BY product_id LIMIT 1))";
			}
			
			if (!empty($data['filter_supplier_id'])){
				$sql .= " AND amazon_orders_products.supplier_id = ('" . (int)$data['filter_supplier_id'] . "')";
			}
			
			if (!empty($data['filter_order_id'])){
				$sql .= " AND amazon_id IN (SELECT DISTINCT amazon_id FROM amazon_orders_products WHERE TRIM(asin) IN 
				(SELECT TRIM(p.asin) FROM order_product op
				LEFT JOIN product p ON (p.product_id = op.product_id)
				WHERE op.order_id = '" . (int)$data['filter_order_id'] . "' AND 
				op.order_id IN 
				(SELECT o.order_id FROM `order` o WHERE o.order_id = '" . (int)$data['filter_order_id'] . "' AND 
				o.order_status_id IN ('" . implode(',', $this->config->get('config_amazonlist_order_status_id')). "'))))";
				
				$sql .= " AND DATE(date_added) >= (SELECT date_added FROM `order` WHERE order_id = '" . (int)$data['filter_order_id'] . "' LIMIT 1) ";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		
		public function getOrders($data){
			
			$sql = "SELECT * FROM amazon_orders";
			
			if (!empty($data['filter_supplier_id'])){
				$sql .= " LEFT JOIN amazon_orders_products ON amazon_orders.amazon_id = amazon_orders_products.amazon_id WHERE 1";
				} else {
				$sql .= " WHERE 1";
			}
			
			
			if (!empty($data['filter_amazon_id'])){
				$sql .= " AND amazon_id LIKE ('". $this->db->escape($data['filter_amazon_id']) ."')";
			}
			
			if (!empty($data['filter_date_added'])) {
				if ($data['filter_date_added'][0] == '+' || $data['filter_date_added'][0] == '-'){
					$data['filter_date_added'] = $this->buildRelativeDate($data['filter_date_added']);
				}
				$sql .= " AND DATE(date_added) >= DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			}
			
			if (!empty($data['filter_date_added_to'])) {
				if ($data['filter_date_added_to'][0] == '+' || $data['filter_date_added_to'][0] == '-'){
					$data['filter_date_added_to'] = $this->buildRelativeDate($data['filter_date_added_to']);
				}
				$sql .= " AND DATE(date_added) <= DATE('" . $this->db->escape($data['filter_date_added_to']) . "')";
			}
			
			if (!empty($data['filter_date_delivered_from'])) {			
				if ($data['filter_date_delivered_from'][0] == '+' || $data['filter_date_delivered_from'][0] == '-'){
					$data['filter_date_delivered_from'] = $this->buildRelativeDate($data['filter_date_delivered_from']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_delivered) >= DATE('" . $this->db->escape($data['filter_date_delivered_from']) . "'))";
			}
			
			if (!empty($data['filter_date_delivered_to'])) {
				if ($data['filter_date_delivered_to'][0] == '+' || $data['filter_date_delivered_to'][0] == '-'){
					$data['filter_date_delivered_to'] = $this->buildRelativeDate($data['filter_date_delivered_to']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_delivered) <= DATE('" . $this->db->escape($data['filter_date_delivered_to']) . "')";
				
				if (empty($data['filter_date_delivered_from'])) {	
					$sql .= " AND DATE(date_delivered) >= DATE(NOW())";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_date_expected_from'])) {			
				if ($data['filter_date_expected_from'][0] == '+' || $data['filter_date_expected_from'][0] == '-'){
					$data['filter_date_expected_from'] = $this->buildRelativeDate($data['filter_date_expected_from']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_expected) >= DATE('" . $this->db->escape($data['filter_date_expected_from']) . "'))";
			}
			
			if (!empty($data['filter_date_expected_to'])) {
				if ($data['filter_date_expected_to'][0] == '+' || $data['filter_date_expected_to'][0] == '-'){
					$data['filter_date_expected_to'] = $this->buildRelativeDate($data['filter_date_expected_to']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE DATE(date_expected) <= DATE('" . $this->db->escape($data['filter_date_expected_to']) . "')";
				
				if (empty($data['filter_date_expected_from'])) {	
					$sql .= " AND DATE(date_expected) >= DATE(NOW())";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_date_arriving_from'])) {			
				if ($data['filter_date_arriving_from'][0] == '+' || $data['filter_date_arriving_from'][0] == '-'){				
					$data['filter_date_arriving_from'] = $this->buildRelativeDate($data['filter_date_arriving_from']);
				}
				
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE (
						DATE(date_arriving_exact) >= DATE('" . $this->db->escape($data['filter_date_arriving_from']) . "')))";
			}
			
			if (!empty($data['filter_date_arriving_to'])) {
				if ($data['filter_date_arriving_to'][0] == '+' || $data['filter_date_arriving_to'][0] == '-'){
					$data['filter_date_arriving_to'] = $this->buildRelativeDate($data['filter_date_arriving_to']);
				}
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE (
					(DATE(date_arriving_exact) <= DATE('" . $this->db->escape($data['filter_date_arriving_to']) . "') AND DATE(date_arriving_exact) > '0000-00-00')))";
				
				if (empty($data['filter_date_arriving_from'])) {	
				//	$sql .= " AND DATE(date_arriving_exact) >= DATE(NOW())";
				}
			
			}
			
			if (!empty($data['filter_is_return'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE is_return = 1)";
			}
			
			if (!empty($data['filter_is_problem'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE is_problem = 1)";
			}
			
			if (!empty($data['filter_is_dispatched'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE is_dispatched = 1)";
			}
			
			if (!empty($data['filter_asin'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE asin LIKE ('" . $this->db->escape($data['filter_asin']) . "'))";
			}
			
			if (!empty($data['filter_product_id'])){
				$sql .= " AND amazon_id IN (SELECT amazon_id FROM amazon_orders_products WHERE asin = (SELECT asin FROM product WHERE product_id = '" . (int)$data['filter_product_id'] . "' AND stock_product_id = 0 ORDER BY product_id LIMIT 1))";
			}
			
			if (!empty($data['filter_supplier_id'])){
				$sql .= " AND amazon_orders_products.supplier_id = ('" . (int)$data['filter_supplier_id'] . "')";
			}
			
			if (!empty($data['filter_order_id'])){
				$sql .= " AND amazon_id IN (SELECT DISTINCT amazon_id FROM amazon_orders_products WHERE TRIM(asin) IN 
				(SELECT TRIM(p.asin) FROM order_product op
				LEFT JOIN product p ON (p.product_id = op.product_id)
				WHERE op.order_id = '" . (int)$data['filter_order_id'] . "' AND 
				op.order_id IN 
				(SELECT o.order_id FROM `order` o WHERE o.order_id = '" . (int)$data['filter_order_id'] . "' AND 
				o.order_status_id IN ('" . implode(',', $this->config->get('config_amazonlist_order_status_id')). "'))))";
				
				$sql .= " AND DATE(date_added) >= (SELECT date_added FROM `order` WHERE order_id = '" . (int)$data['filter_order_id'] . "' LIMIT 1) ";
			}
			
			$sort_data = array(
			'amazon_id',			
			'status',
			'date_added',			
			'total',
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY date_added";
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
				} else {
				$sql .= " DESC";
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
		
		public function getOrderProducts($amazon_id, $data){
			
			$sql = "SELECT * FROM amazon_orders_products WHERE amazon_id LIKE ('" . $this->db->escape($amazon_id) . "') ";
			
			if (!empty($data['filter_is_return'])){
				$sql .= " AND is_return = 1";
			}
			
			if (!empty($data['filter_is_problem'])){
				$sql .= " AND is_problem = 1";
			}
			
			if (!empty($data['filter_is_dispatched'])){
				$sql .= " AND is_dispatched = 1";
			}
			
			if (!empty($data['filter_asin'])){
				$sql .= " AND asin LIKE ('" . $this->db->escape($data['filter_asin']) . "')";
			}
			
			if (!empty($data['filter_asin'])){
				$sql .= " AND asin LIKE ('" . $this->db->escape($data['filter_asin']) . "')";
			}
			
			if (!empty($data['filter_product_id'])){
				$sql .= " AND asin = (SELECT asin FROM product WHERE product_id = '" . (int)$data['filter_product_id'] . "' AND stock_product_id = 0 ORDER BY product_id LIMIT 1)";
			}
			
			if (!empty($data['filter_supplier_id'])){
				$sql .= " AND supplier_id = ('" . (int)$data['filter_supplier_id'] . "')";
			}
			
			if (!empty($data['filter_date_delivered_from'])) {			
				if ($data['filter_date_delivered_from'][0] == '+' || $data['filter_date_delivered_from'][0] == '-'){
					$data['filter_date_delivered_from'] = $this->buildRelativeDate($data['filter_date_delivered_from']);
				}
				$sql .= " AND DATE(date_delivered) >= DATE('" . $this->db->escape($data['filter_date_delivered_from']) . "')";
			}
			
			if (!empty($data['filter_date_delivered_to'])) {
				if ($data['filter_date_delivered_to'][0] == '+' || $data['filter_date_delivered_to'][0] == '-'){
					$data['filter_date_delivered_to'] = $this->buildRelativeDate($data['filter_date_delivered_to']);
				}
				$sql .= " AND DATE(date_delivered) <= DATE('" . $this->db->escape($data['filter_date_delivered_to']) . "')";
				
				if (empty($data['filter_date_delivered_from'])) {	
					$sql .= " AND DATE(date_delivered) >= DATE(NOW())";
				}
			}
			
			if (!empty($data['filter_date_expected_from'])) {			
				if ($data['filter_date_expected_from'][0] == '+' || $data['filter_date_expected_from'][0] == '-'){
					$data['filter_date_expected_from'] = $this->buildRelativeDate($data['filter_date_expected_from']);
				}
				$sql .= " AND DATE(date_expected) >= DATE('" . $this->db->escape($data['filter_date_expected_from']) . "')";
			}
			
			if (!empty($data['filter_date_expected_to'])) {
				if ($data['filter_date_expected_to'][0] == '+' || $data['filter_date_expected_to'][0] == '-'){
					$data['filter_date_expected_to'] = $this->buildRelativeDate($data['filter_date_expected_to']);
				}
				$sql .= " AND DATE(date_expected) <= DATE('" . $this->db->escape($data['filter_date_expected_to']) . "')";
				
				if (empty($data['filter_date_expected_from'])) {	
					$sql .= " AND DATE(date_expected) >= DATE(NOW())";
				}
			}
			
			if (!empty($data['filter_date_arriving_from'])) {			
				if ($data['filter_date_arriving_from'][0] == '+' || $data['filter_date_arriving_from'][0] == '-'){				
					$data['filter_date_arriving_from'] = $this->buildRelativeDate($data['filter_date_arriving_from']);
				}
				
				$sql .= " AND (DATE(date_arriving_exact) >= DATE('" . $this->db->escape($data['filter_date_arriving_from']) . "'))";
			}
			
			if (!empty($data['filter_date_arriving_to'])) {
				if ($data['filter_date_arriving_to'][0] == '+' || $data['filter_date_arriving_to'][0] == '-'){
					$data['filter_date_arriving_to'] = $this->buildRelativeDate($data['filter_date_arriving_to']);
				}
				$sql .= " AND (DATE(date_arriving_exact) <= DATE('" . $this->db->escape($data['filter_date_arriving_to']) . "') AND DATE(date_arriving_exact) > '0000-00-00')";
				
				if (empty($data['filter_date_arriving_from'])) {	
				//	$sql .= " AND DATE(date_arriving_exact) >= DATE(NOW())";
				}
			
			}
			
			$sql .= " ORDER BY delivery_num ASC";
								
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		
		public function getProductByASIN($asin){
			
			$query = $this->db->query("SELECT p.product_id, pd.name, p.model, p.image FROM product p 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . $this->config->get('config_language_id') . "') 
			WHERE TRIM(asin) LIKE ('" . $this->db->escape(trim($asin)) . "') 
			AND stock_product_id = 0");
			
			return $query->rows;
			
		}
		
		
		public function getOrdersForProductByASIN($asin){
			
			$query = $this->db->query("SELECT 
			DISTINCT op.order_id, 
			op.quantity,
			os.status_txt_color,
			os.status_bg_color,
			os.name as status
			FROM 
			`order_product` op 
			LEFT JOIN `order` o ON o.order_id = op.order_id
			LEFT JOIN `order_status` os ON (o.order_status_id = os.order_status_id AND os.language_id = 2)
			WHERE product_id IN (SELECT product_id FROM product WHERE TRIM(asin) LIKE ('" . $this->db->escape(trim($asin)) . "'))
			AND o.order_status_id IN ('" . implode(',', $this->config->get('config_amazonlist_order_status_id')). "')
			");
			
			return $query->rows;
			
			
			
		}
		
		
		
		
		
		
		
		
		
	}
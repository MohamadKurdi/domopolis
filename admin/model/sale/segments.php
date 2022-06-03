<?php
	class ModelSaleSegments extends Model {
		/*
			'order_count' +
			'order_good_count', +
			'order_bad_count', +
			'total_cheque', +
			'avg_cheque', +
			'city',
			'gender', +
			'order_first_date_from',
			'order_first_date_to',
			'order_last_date_from',
			'order_last_date_to',
			'order_good_first_date_from',
			'order_good_first_date_to',
			'order_good_last_date_from',
			'order_good_last_date_to',
			'date_added_from',
			'date_added_to',
			'birthday_from',
			'birthday_to',
			'birthday_year',
			'mail_opened',+
			'total_calls',+
			'avg_calls_duration',+
			
			'country_id',+
			'manufacturer_view',
			'manufacturer_bought',
			'category_view',
			'first_order_source',
			'source', +
			'customer_group' +
		*/
		
		public function getCustomersNowInSegment($segment_id){
			$query = $this->db->query("SELECT customer_id FROM customer_segments WHERE segment_id = '" . (int)$segment_id . "'");
			
			$result = array();
			foreach ($query->rows as $row){
				$result[] = $row['customer_id'];
			}
			
			return $result;
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
		
		
		private function buildDateMagicSQL($date_from, $date_to, $param, $remove_year = false){
			
			$sql = "";
			
			$date_from = trim($date_from);
			$date_to = trim($date_to);
			//let's parse relative dates
			if ($date_from[0] == '+' || $date_from[0] == '-'){
				$date_from = $this->buildRelativeDate($date_from);
			}
			
			if ($date_to[0] == '+' || $date_to[0] == '-'){
				$date_to = $this->buildRelativeDate($date_to);
			}
			
			
			if ($remove_year){
				
				$date_from = date('m-d', strtotime($date_from));
				$date_to = date('m-d', strtotime($date_to));
				
				if (!empty($date_from)) {
					$sql .= " DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-".$date_from."'))";
					$sql .= " AND DATE($param) > '0000-00-00'";
					
					if (empty($date_to)){
						$sql .= " AND DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
					}
				}
				
				
				if (!empty($date_to)) {
					if ($sql){
						$and = " AND ";
					}
					$sql .= " $and DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) <= DATE(CONCAT(YEAR(CURDATE()),'-".$date_to."'))";
					$sql .= " AND DATE($param) > '0000-00-00'";
					
					if (empty($date_from)){
						$sql .= " AND DATE_ADD($param, INTERVAL YEAR(CURDATE())-YEAR($param) YEAR) >= DATE(CONCAT(YEAR(CURDATE()),'-01-01'))";
					}
				}
				
				
				} else {
				
				if (!empty($date_from)) {
					$sql .= " DATE($param) >= '$date_from'";
				}
				
				if ($sql){
					$and = " AND ";
				}
				
				if (!empty($date_to)) {
					$sql .= " $and DATE($param) <= '$date_to'";
					
					if (empty($date_from)){
						$sql .= " AND DATE($param) > '0000-00-00'";
					}
				}
				
			}
			
			return $sql;
		}
		
		private function buildLikeArrayOrSQL($array, $param){
			
			$sql = "(";
			
			$or = '';
			foreach ($array as $part){
				$sql .= " $or TRIM($param) LIKE TRIM('" . $this->db->escape($part) . "') ";						
				$or = " OR ";
			}
			$sql .= ")";
			
			return $sql;
		}
		
		private function buildLikeNonStrictArrayOrSQL($array, $param){
			
			$sql = "(";
			
			$or = '';
			foreach ($array as $part){
				$sql .= " $or $param LIKE '%" . $this->db->escape($part) . "%' ";						
				$or = " OR ";
			}
			$sql .= ")";
			
			return $sql;
		}
		
		private function buildLikeArrayANDSQL($array, $param){
			
			$sql = "(";
			
			$and = '';
			foreach ($array as $part){
				$sql .= " $and TRIM($param) LIKE TRIM('" . $this->db->escape($part) . "') ";						
				$and = " AND ";
			}
			$sql .= ")";
			
			return $sql;
		}
		
		private function buildComplexLikeArrayOrSQL($array, $param){
			
			if (!is_array($array)){
				$array = explode(PHP_EOL, $array);
			}
			
			$yes_array = array();
			$no_array = array();
			foreach ($array as $part){
				$part = trim($part);
				if ($part[0] == '!'){
					$no_array[] = substr($part, 1);
					} else {
					$yes_array[] = trim($part);
				}
			}
			
			
			$sql = '';
			
			if (count($yes_array)){
				$sql .= $this->buildLikeArrayOrSQL($yes_array, $param);
			}
			
			if (count($no_array)){
				if (count($yes_array)){
					$sql .= " AND ";
				}
				$sql .= " NOT " . $this->buildLikeArrayORSQL($no_array, $param);
			}
			
			return $sql;
			
		}
		
		private function buildInArraySQL($array, $param){			
			$sql = " $param IN (".implode(',', $array) . ")";			
			return $sql;
		}
		
		private function buildNumericWithRangesSQL($string, $param){		
			$string = trim($string);
			
			if (html_entity_decode($string)[0] == '<' || html_entity_decode($string)[0] == '>'){
				
				$string = html_entity_decode($string);
				
				$num = (float)preg_replace('/[^0-9.]+/', '', $string);
				
				if ($string[0] == '<'){
					$sql = " $param >= 0 AND $param < '" . $num . "'";
					} elseif ($string[0] == '>') {
					$sql =  " $param > '" . $num . "'";
				}
				
				} elseif (count(explode('-', $string)) == 2){
				
				$nums = explode('-', $string);
				
				$sql = " $param >= '" . (float)$nums[0] . "' AND $param <= '" . (float)$nums[1] . "'";
				
				} else {
				
				$sql = " $param = '" . (float)$string . "'";
				
			} 					
			
			return $sql;
		}
		
		public function getSegmentDynamics($segment_id, $range = 'month'){
			
			$data = array();
			
			$data['customer_count'] = array();
			$data['total_cheque'] = array();
			$data['avg_cheque'] = array();
			$data['order_good_count'] = array();
			$data['order_bad_count'] = array();
			$data['xaxis'] = array();
			
			$data['customer_count']['label'] = 'Количество клиентов';
			$data['total_cheque']['label'] = 'Всего потрачено денег, €';
			$data['avg_cheque']['label'] = 'Средний чек, €';
			$data['order_good_count']['label'] = 'Выполненных заказов';
			$data['order_bad_count']['label'] = 'Отмененных заказов';
			
			switch ($range) {
				
				case 'week':
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));
					
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "segments_dynamics` WHERE segment_id = '" . (int)$segment_id . "' AND (DATE(date_added) = '" . $this->db->escape($date) . "') ORDER BY date_added DESC LIMIT 1");
					
					if ($query->num_rows) {
						$data['customer_count']['data'][] = array($i, (int)$query->row['customer_count']);
						$data['total_cheque']['data'][] = array($i, (int)$query->row['total_cheque']);
						$data['avg_cheque']['data'][] = array($i, (int)$query->row['avg_cheque']);
						$data['order_good_count']['data'][] = array($i, (int)$query->row['order_good_count']);
						$data['order_bad_count']['data'][] = array($i, (int)$query->row['order_bad_count']);
						} else {
						$data['customer_count']['data'][] = array($i, 0);
						$data['total_cheque']['data'][] = array($i, 0);
						$data['avg_cheque']['data'][] = array($i, 0);
						$data['order_good_count']['data'][] = array($i, 0);
						$data['order_bad_count']['data'][] = array($i, 0);
					}	
					
					setlocale(LC_TIME, "ru_RU.UTF8");
					$data['xaxis'][] = array($i, strftime("%a", strtotime($date)));
				}
				
				break;
				
				default:				
				case 'month':
				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;
					
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "segments_dynamics` WHERE segment_id = '" . (int)$segment_id . "' AND (DATE(date_added) = '" . $this->db->escape($date) . "') ORDER BY date_added DESC LIMIT 1");
					
					if ($query->num_rows) {
						$data['customer_count']['data'][] = array($i, (int)$query->row['customer_count']);
						$data['total_cheque']['data'][] = array($i, (int)$query->row['total_cheque']);
						$data['avg_cheque']['data'][] = array($i, (int)$query->row['avg_cheque']);
						$data['order_good_count']['data'][] = array($i, (int)$query->row['order_good_count']);
						$data['order_bad_count']['data'][] = array($i, (int)$query->row['order_bad_count']);
						} else {
						$data['customer_count']['data'][] = array($i, 0);
						$data['total_cheque']['data'][] = array($i, 0);
						$data['avg_cheque']['data'][] = array($i, 0);
						$data['order_good_count']['data'][] = array($i, 0);
						$data['order_bad_count']['data'][] = array($i, 0);
					}	
					
					setlocale(LC_TIME, "ru_RU.UTF8");
					$data['xaxis'][] = array($i, date('j', strtotime($date)));
				}
				
				break;							
				
				
				case 'year':
				for ($i = 1; $i <= 12; $i++) {
					$query = $this->db->query("SELECT AVG(customer_count) as customer_count, AVG(total_cheque) as total_cheque, AVG(avg_cheque) as avg_cheque, AVG(order_good_count) as order_good_count, AVG(order_bad_count) as order_bad_count FROM `" . DB_PREFIX . "segments_dynamics` WHERE segment_id = '" . (int)$segment_id . "' AND YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");
					
					if ($query->num_rows) {
						$data['customer_count']['data'][] = array($i, (int)$query->row['customer_count']);
						$data['total_cheque']['data'][] = array($i, (int)$query->row['total_cheque']);
						$data['avg_cheque']['data'][] = array($i, (int)$query->row['avg_cheque']);
						$data['order_good_count']['data'][] = array($i, (int)$query->row['order_good_count']);
						$data['order_bad_count']['data'][] = array($i, (int)$query->row['order_bad_count']);
						} else {
						$data['customer_count']['data'][] = array($i, 0);
						$data['total_cheque']['data'][] = array($i, 0);
						$data['avg_cheque']['data'][] = array($i, 0);
						$data['order_good_count']['data'][] = array($i, 0);
						$data['order_bad_count']['data'][] = array($i, 0);
					}	
					
					setlocale(LC_TIME, "ru_RU.UTF8");
					$data['xaxis'][] = array($i, strftime("%b", mktime(0, 0, 0, $i, 1, date('Y'))));
				}			
				break;
				
				case 'last100':
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "segments_dynamics` WHERE segment_id = '" . (int)$segment_id . "' ORDER BY date_added DESC LIMIT 100");
				
				if ($query->num_rows) {
					$i = 0;
					foreach ($query->rows as $row){							
						$data['customer_count']['data'][] = array($i, (int)$row['customer_count']);
						$data['total_cheque']['data'][] = array($i, (int)$row['total_cheque']);
						$data['avg_cheque']['data'][] = array($i, (int)$row['avg_cheque']);
						$data['order_good_count']['data'][] = array($i, (int)$row['order_good_count']);
						$data['order_bad_count']['data'][] = array($i, (int)$row['order_bad_count']);
						
						setlocale(LC_TIME, "ru_RU.UTF8");
						$data['xaxis'][] = array($i, $i);	
						
						$i++;
					}
				}	
				
				
				break;
				
			}
			
			return $data;
		}
		
		public function getCustomersBySegment($data = array(), $ids_only = false, $insert = false, $debug_sql = false){
			
			if ($ids_only) {
				
				if ($insert) {
					$this->db->query("DELETE FROM customer_segments WHERE segment_id = '" . (int)$data['segment_id'] . "'");
					
					$sql = "INSERT IGNORE INTO customer_segments (customer_id, segment_id) ";	
					$sql .= "SELECT SQL_CALC_FOUND_ROWS DISTINCT customer_id, ". (int)$data['segment_id'] ."
					FROM customer 			
					WHERE 1 ";
					} else {
					
					$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT customer_id
					FROM customer 			
					WHERE 1 ";
					
				}
				
				} else {
				$sql = "SELECT SQL_CALC_FOUND_ROWS *,
				CONCAT(firstname, ' ', lastname) as name,
				cgd.name AS customer_group
				FROM customer 
				LEFT JOIN customer_group_description cgd ON (customer.customer_group_id = cgd.customer_group_id)			
				WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			}
			
			$implode = array();
			$having_implode = array();
			
			if (!empty($data['order_count'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['order_count'], 'order_count');
			}
			
			if (!empty($data['order_good_count'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['order_good_count'], 'order_good_count');																										
			}
			
			if (!empty($data['order_bad_count'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['order_bad_count'], 'order_bad_count');																											
			}
			
			if (!empty($data['total_cheque']) && $data['total_cheque']){
				
				if (!empty($data['total_cheque_only_selected'])){
					if (!empty($data['manufacturer_bought'])){
						$tmp = $this->buildInArraySQL($data['manufacturer_bought'], 'p.manufacturer_id');
						$tmp2 = $this->buildNumericWithRangesSQL($data['total_cheque'], 'SUM(op.total_national)');
						
						$implode[] = " customer_id IN 
						(SELECT DISTINCT o.customer_id FROM `order` o						
						LEFT JOIN order_product op ON o.order_id = op.order_id
						LEFT JOIN product p ON p.product_id = op.product_id 
						WHERE o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'
						AND $tmp
						GROUP BY o.customer_id
						HAVING ($tmp2)						
						)";
					}
					
					if (!empty($data['category_bought'])){
						$tmp = $this->buildInArraySQL($data['category_bought'], 'p2c.category_id');
						
						
						$implode[] = " customer_id IN 
						(SELECT DISTINCT o.customer_id FROM `order` o						
						LEFT JOIN order_product op ON o.order_id = op.order_id						
						LEFT JOIN product_to_category p2c ON p2c.product_id = op.product_id 
						WHERE o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'
						AND $tmp
						GROUP BY o.customer_id
						HAVING ($tmp2)						
						)";
					}
					
					} else {			
					$implode[] = $this->buildNumericWithRangesSQL($data['total_cheque'], 'total_cheque');																										
				}
			}
			
			if (!empty($data['avg_cheque'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['avg_cheque'], 'avg_cheque');																												
			}
			
			if (!empty($data['total_calls'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['total_calls'], 'total_calls');																												
			}
			
			if (!empty($data['avg_calls_duration'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['avg_calls_duration'], 'avg_calls_duration');																												
			}
			
			if (!empty($data['mail_opened'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['mail_opened'], 'mail_opened');																												
			}
			
			if (!empty($data['avg_csi'])){
				$implode[] = $this->buildNumericWithRangesSQL($data['avg_csi'], 'csi_average');																												
			}
			
			if (!empty($data['gender'])) {
				$implode[] = "gender = '" . (int)$data['gender'] . "'";
			}
			
			if (!empty($data['has_push'])) {
				$implode[] = "has_push = '" . (int)$data['has_push'] . "'";
			}
			
			if (!empty($data['source']) && count($data['source'])) {								
				$implode[] = $this->buildLikeArrayOrSQL($data['source'], 'source');
			}
			
			if (!empty($data['first_order_source']) && count($data['first_order_source'])) {								
				$implode[] = $this->buildLikeArrayOrSQL($data['first_order_source'], 'first_order_source');
			}
			
			if (!empty($data['customer_group_id']) && count($data['customer_group_id'])) {
				$implode[] = $this->buildInArraySQL($data['customer_group_id'], 'customer.customer_group_id');
			}
			
			if (!empty($data['country_id'])) {
				$implode[] = $this->buildInArraySQL($data['country_id'], 'country_id');
			}	
			
			if (!empty($data['city'])) {
				$implode[] = "LENGTH(city) > 1";
				$implode[] = $this->buildComplexLikeArrayOrSQL($data['city'], 'city');
			}	
			
			if (!empty($data['date_added'])) {
				$implode[] = "DATE(date_added) >= DATE('" . $this->db->escape($data['date_added']) . "')";
			}
			
			if (!empty($data['date_added_from']) || !empty($data['date_added_to'])) {
				if (empty($data['added_year'])){
					$data['added_year'] = false;
				}
				$implode[] = $this->buildDateMagicSQL($data['date_added_from'], $data['date_added_to'], 'date_added', $data['added_year']);
			}
			
			if (!empty($data['birthday_from']) || !empty($data['birthday_to'])) {
				if (empty($data['birthday_year'])){
					$data['birthday_year'] = false;
				}
				$implode[] = " (" . $this->buildDateMagicSQL($data['birthday_from'], $data['birthday_to'], 'birthday', $data['birthday_year']) . ") ";
			}		
			
			if (!empty($data['order_first_date_from']) || !empty($data['order_first_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_first_date_from'], $data['order_first_date_to'], 'order_first_date', false);
			}	
			
			if (!empty($data['order_last_date_from']) || !empty($data['order_last_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_last_date_from'], $data['order_last_date_to'], 'order_last_date', false);
			}	
			
			if (!empty($data['order_good_first_date_from']) || !empty($data['order_good_first_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_good_first_date_from'], $data['order_good_first_date_to'], 'order_good_first_date', false);
			}
			
			if (!empty($data['order_good_last_date_from']) || !empty($data['order_good_last_date_to'])) {				
				$implode[] = $this->buildDateMagicSQL($data['order_good_last_date_from'], $data['order_good_last_date_to'], 'order_good_last_date', false);
			}
			
			if (!empty($data['coupon'])){
				$tmp = $this->buildLikeNonStrictArrayOrSQL($data['coupon'], 'title');
				$implode[] = " customer_id IN (SELECT DISTINCT customer_id FROM `order` WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' AND order_id IN (SELECT DISTINCT order_id FROM order_total WHERE $tmp))";
			}
			
			if (!empty($data['manufacturer_view'])){
				$tmp = $this->buildInArraySQL($data['manufacturer_view'], 'entity_id');
				$implode[] = " customer_id IN (SELECT DISTINCT customer_id FROM customer_viewed WHERE type = 'm' AND $tmp)";
			}
			
			if (empty($data['total_cheque']) || !$data['total_cheque']){				
				if (!empty($data['manufacturer_bought'])){
					$tmp = $this->buildInArraySQL($data['manufacturer_bought'], 'p.manufacturer_id');
					$implode[] = " customer_id IN (SELECT DISTINCT o.customer_id FROM `order` o 
					LEFT JOIN order_product op ON o.order_id = op.order_id 
					LEFT JOIN product p ON op.product_id = op.product_id
					WHERE o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'
					AND $tmp)";
				}
			}
			
			if (!empty($data['category_view'])){
				$tmp = $this->buildInArraySQL($data['category_view'], 'entity_id');
				$implode[] = " customer_id IN (SELECT DISTINCT customer_id FROM customer_viewed WHERE type = 'c' AND $tmp)";
			}
			
			
			if (empty($data['total_cheque']) || !$data['total_cheque']){
				if (!empty($data['category_bought'])){
					$tmp = $this->buildInArraySQL($data['category_bought'], 'p2c.category_id');
					$implode[] = " customer_id IN (SELECT DISTINCT o.customer_id FROM `order` o 
					LEFT JOIN order_product op ON o.order_id = op.order_id 
					LEFT JOIN product_to_category p2c ON op.product_id = p2c.product_id				
					WHERE o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'
					AND $tmp)";
				}
			}
			
			
			if ($implode) {
				$sql .= " AND " . implode(" AND ", $implode);
			}
			
			if (count($having_implode) > 0){
				
				$sql .= " GROUP BY customer_id ";						
				$sql .= " HAVING (" . implode(" AND ", $having_implode) .")";
				
			}
			
			$sql .= " ORDER BY firstname";
			$sql .= " DESC";
			
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			if ($debug_sql){			
				$this->log->debugsql($sql, true);
			}
			
			$query = $this->db->query($sql);
			
			if ($insert) {
				$_count = $this->db->countAffected();
				} else {
				$query2 = $this->db->query("Select FOUND_ROWS() as count");
				$_count = $query2->row['count'];
			}
			
			if ($insert) {
				$_results = array();	
				} else {
				$_results = $query->rows;
			}
			
			
			if ($ids_only && !$insert){
				$__array_results = array();	
				foreach ($query->rows as $__row){
					$__array_results[] = (int)$__row['customer_id'];
				}
				return $__array_results;
			}
			
			return array('results' => $_results, 'count' => $_count);
			
			
		}	
		
	}	
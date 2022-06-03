<?php
	class ModelLocalisationlegalperson extends Model {
		public function addLegalPerson($data) {
			$this->db->query("INSERT INTO legalperson SET 
			legalperson_name = '" . $this->db->escape($data['legalperson_name']) . "', 
			legalperson_name_1C = '" . $this->db->escape($data['legalperson_name_1C']) . "', 
			legalperson_desc = '" . $this->db->escape($data['legalperson_desc']) . "', 
			legalperson_additional = '" . $this->db->escape($data['legalperson_additional']) . "',
			legalperson_print = '" . $this->db->escape($data['legalperson_print']) . "', 
			legalperson_legal = '" . (int)$data['legalperson_legal'] . "', 
			legalperson_country_id = '" . (int)$data['legalperson_country_id'] . "'");
			
		}
		
		public function editlegalperson($legalperson_id, $data) {
			$this->db->query("UPDATE legalperson SET legalperson_name = '" . $this->db->escape($data['legalperson_name']) . "', 
			legalperson_name_1C = '" . $this->db->escape($data['legalperson_name_1C']) . "', 
			legalperson_desc = '" . $this->db->escape($data['legalperson_desc']) . "',
			legalperson_additional = '" . $this->db->escape($data['legalperson_additional']) . "',
			legalperson_print = '" . $this->db->escape($data['legalperson_print']) . "', 
			legalperson_legal = '" . (int)$data['legalperson_legal'] . "', 
			legalperson_country_id = '" . (int)$data['legalperson_country_id'] . "' WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
		}
		
		public function deletelegalperson($legalperson_id) {
			$this->db->query("DELETE FROM legalperson WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
		}
		
		public function getlegalperson($legalperson_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM legalperson WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
			
			
			return $query->row;
		}
		
		public function getLegalPersonParsed($legalperson_id) {
			
			if (!$legalperson_id) return array();
			
			$query = $this->db->query("SELECT DISTINCT * FROM legalperson WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
			$result = array();
			foreach ($query->row as $key => $value){
				
				$result[$key] = $value;
				
				if ($key == 'legalperson_additional'){
					
					$result[$key] = $value;
					
					$info = explode(PHP_EOL, $value);
					foreach ($info as $string){
						$line = explode(':', $string);
						if (isset($line[0]) && isset($line[1])){
							$result[$line[0]] = $line[1];
						}
					}
				}
				
			}
			
			return $result;
		}
		
		public function getLegalPersonMonthlyTotals($legalperson_id){
			//ищем сумму по заказам, в которых есть оплата с указанием юрлица
			$q = $this->db->query("SELECT SUM(ct.amount_national) as total_already_paid FROM customer_transaction ct 
			LEFT JOIN `order` o ON o.order_id = ct.order_id 
			WHERE (ct.description LIKE ('Оплата б/н%') OR added_from = 'paykeeper' OR added_from = 'psb_bank') AND ct.order_id > 0 AND ct.amount_national > 0 AND o.legalperson_id = '" . (int)$legalperson_id . "' AND ct.date_added >= DATE_ADD(DATE_ADD(LAST_DAY(NOW()), INTERVAL 1 DAY), INTERVAL - 1 MONTH)");
			$total_already_paid = $q->row['total_already_paid'];
			
			$q = $this->db->query("SELECT SUM(o.total_national) as total_need_to_pay FROM `order` o WHERE				
			o.order_id NOT IN (SELECT DISTINCT order_id FROM customer_transaction ct WHERE ct.amount_national > 0) 
			AND o.order_status_id = '" . (int)$this->config->get('config_confirmed_nopaid_order_status_id') . "'
			AND (o.legalperson_id = '" . (int)$legalperson_id . "' OR pay_equire = 1 OR pay_equire2 = 1 OR pay_equirePP = 1)
			");
			
			$total_need_to_pay = $q->row['total_need_to_pay'];
			
			return array(
			'total_already_paid' => $total_already_paid,
			'total_need_to_pay'  => $total_need_to_pay,
			'sum'                => ($total_already_paid + $total_need_to_pay)
			);
		}
		
		
		public function getlegalpersonName($legalperson_id) {
			$query = $this->db->query("SELECT name FROM legalperson WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
			return $query->row['name'];
		}
		
		public function getLegalPersonInfo($legalperson_id) {
			$query = $this->db->query("SELECT legalperson_desc FROM legalperson WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
			if ($query->num_rows) {			
				return $query->row['legalperson_desc'];
				} else {
				return '';
			}
		}
		
		public function getLegalPersonAdditional($legalperson_id) {
			$query = $this->db->query("SELECT legalperson_additional FROM legalperson WHERE legalperson_id = '" . (int)$legalperson_id . "'");
			
			if ($query->num_rows) {			
				return $query->row['legalperson_additional'];
				} else {
				return '';
			}
		}
		
		public function getLegalPersonsByCountryID($country_id, $simple = false) {
			
			if ($simple) {		
				$sql = "SELECT * FROM legalperson WHERE legalperson_country_id = '" . (int)$country_id . "' AND legalperson_legal = 0";
				} else {
				$sql = "SELECT * FROM legalperson WHERE legalperson_country_id = '" . (int)$country_id . "' AND legalperson_legal = 1";
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}		
		
		public function getAllLegalPersonsByCountryID($country_id) {
			$sql = "SELECT * FROM legalperson WHERE legalperson_country_id = '" . (int)$country_id . "'";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getLegalPersons() {
			$sql = "SELECT * FROM legalperson ORDER BY legalperson_country_id DESC, legalperson_id ASC";
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}
		
		public function getLegalPersonsSimpleOrNot($simple = false) {
			if ($simple) {		
				$sql = "SELECT * FROM legalperson WHERE legalperson_legal = 0 ORDER BY legalperson_country_id DESC, legalperson_id ASC";
				} else {
				$sql = "SELECT * FROM legalperson WHERE legalperson_legal = 1 ORDER BY legalperson_country_id DESC, legalperson_id ASC";
			}
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}
		
		public function getTotalLegalPersons() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM legalperson");
			
			return $query->row['total'];
		}	
	}	
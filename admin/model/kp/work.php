<?
	class ModelKpWork extends Model {
		
		
		public function init(){
			$this->db->query("INSERT IGNORE INTO user_worktime (`user_id`, `date`) VALUES ('" . (int)$this->user->getID() . "', NOW())");		
		}
		
		
		public function updateFieldPlusOne($field){
			if (!$field) return false;
			
			$this->init();
			$this->db->query("UPDATE user_worktime SET `" . $this->db->escape($field) . "` = `" . $this->db->escape($field) . "`+1 WHERE date = DATE(NOW()) AND user_id = '" . (int)$this->user->getID() . "'");	
			
		}
		
		public function getAllUserStatsForDate($date = false){
			
			if (!$date){
				$date = date('Y-m-d', strtotime("-$i day"));
			}
			
			$query = $this->db->query("SELECT uw.*, u.user_id as uid, ug.name as group_name, ug.user_group_id as user_group_id FROM user_worktime uw LEFT JOIN user u ON uw.user_id = u.user_id LEFT JOIN user_group ug ON ug.user_group_id = u.user_group_id WHERE DATE(uw.date) = '" . $this->db->escape($date) . "' AND u.status = 1 AND u.count_worktime = 1");		
			
			return $query->rows;
			
		}	
		
		
		public function countDays($user_id, $month, $year){
			
			$query = $this->db->query("SELECT COUNT(*) as total FROM user_worktime WHERE MONTH(date) = '" . (int)$month . "' AND YEAR(date) = '" . (int)$year . "' AND user_id = '" . (int)$user_id . "' AND NOT (ISNULL(worktime_start))");
			
			return $query->row['total'];
			
		}
		
		public function countFieldSum($user_id, $field, $month, $year){
			
			if ($user_id){			
				$query = $this->db->query("SELECT SUM(`" . $this->db->escape($field) . "`) as total FROM user_worktime WHERE MONTH(date) = '" . (int)$month . "' AND YEAR(date) = '" . (int)$year . "' AND user_id = '" . (int)$user_id . "' GROUP BY user_id");
				} else {
				$query = $this->db->query("SELECT SUM(`" . $this->db->escape($field) . "`) as total FROM user_worktime WHERE MONTH(date) = '" . (int)$month . "' AND YEAR(date) = '" . (int)$year . "' AND user_id > 0");
			}
			
			if (!$query->num_rows){
				return 0;
			}			
			
			if (empty($query->row['total'])){
				return 0;
			}
			
			return $query->row['total'];
			
		}
		
		public function countTimeDiffSum($user_id, $month, $year){
			
			$query = $this->db->query("SELECT SUM(TIME_TO_SEC(TIMEDIFF(worktime_finish, worktime_start))) as total FROM user_worktime WHERE MONTH(date) = '" . (int)$month . "' AND YEAR(date) = '" . (int)$year . "' AND user_id = '" . (int)$user_id . "' GROUP BY user_id");
			
			
			if (!$query->num_rows){
				return 0;
			}			
			
			if (empty($query->row['total'])){
				return 0;
			}
			
			return $query->row['total'];
			
		}
		
		public function getPromoCodes($promo_type, $manager_id){
			
			$query = $this->db->query("SELECT * FROM coupon WHERE promo_type = '" . $this->db->escape($promo_type) . "' AND manager_id = '" . (int)$manager_id . "'");
			
			return $query->rows;
		}
		
		public function getCountClosedOrdersForMonth($manager_id, $month, $year){
			
			if ($manager_id){
				
				$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order_history` 
				WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
				AND order_id IN (SELECT order_id FROM `order` WHERE manager_id = '" . (int)$manager_id . "') ORDER BY order_id ASC");
				
				} else {
				
				$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order_history` 
				WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
				AND order_id IN (SELECT order_id FROM `order` WHERE manager_id > 0) ORDER BY order_id ASC");
				
			}
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getCountCancelledOrdersForMonth($manager_id, $month, $year){
			
			if ($manager_id){
				
				$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order_history` 
				WHERE order_status_id = '" . (int)$this->config->get('config_cancelled_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
				AND order_id IN (SELECT order_id FROM `order` WHERE manager_id = '" . (int)$manager_id . "') ORDER BY order_id ASC");
				
				} else {
				
				$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order_history` 
				WHERE order_status_id = '" . (int)$this->config->get('config_cancelled_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
				AND order_id IN (SELECT order_id FROM `order` WHERE manager_id > 0) ORDER BY order_id ASC");
				
			}
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getCountConfirmedToProcessOrdersForMonth($manager_id, $month, $year){
			
			$sql = "SELECT COUNT(DISTINCT order_id) as total FROM `order` o WHERE ";
			
			//присвоенные менеджеру
			if ($manager_id) {
				$sql .= " o.manager_id = '" . (int)$manager_id . "'";
				} else {
				$sql .= " o.manager_id > 0";
			}
			
			//с ненулевым текущим статусом (на всякий случай)
			$sql .= " AND o.order_status_id > 0";
			
			//дата оформления в периоде
			$sql .= " AND MONTH(o.date_added) = '" . (int)$month . "' AND YEAR(o.date_added) = '" . (int)$year . "'";
			
			//установка статуса "выполняется" в течении "начало месяца - конец месяца плюс пять дней"
			$sql .= " AND o.order_id IN (SELECT oh2.order_id FROM order_history oh2 WHERE (oh2.order_status_id = 2 OR oh2.order_status_id = 14) ";
			//начало месяца
			$sql .= " AND DATE(date_added) >= DATE('". (int)$year .'-'. (int)$month . "-01" ."')";
			//конец периода +5 дней
			$sql .= " AND DATE(date_added) <= DATE('" . date('Y-m-d', strtotime('+5 day', strtotime((int)$year .'-'. (int)$month . '-' . date('t', strtotime((int)$year .'-'. (int)$month . '-01'))))) . "'))";					
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getTotalManagerOrdersForMonth($manager_id, $month, $year){
			
			if ($manager_id){
				
				$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order` 
				WHERE manager_id = '" . (int)$manager_id . "' 
				AND order_status_id > 0 
				AND order_status_id <> 23
				AND MONTH(date_added) = '" . (int)$month . "' 
				AND YEAR(date_added) = '" . (int)$year . "' ");
				
				} else {
				
				$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order` 
				WHERE manager_id > 0
				AND order_status_id > 0 
				AND order_status_id <> 23
				AND MONTH(date_added) = '" . (int)$month . "' 
				AND YEAR(date_added) = '" . (int)$year . "' ");
				
			}
			
			return $query->row['total'];
		}
		
		public function getClosedOrdersForMonth($manager_id, $month, $year){
			
			if ($manager_id) {
				
				$query = $this->db->query("SELECT DISTINCT order_id, date_added FROM `order_history` 			
				WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
				AND order_id IN (SELECT order_id FROM `order` WHERE manager_id = '" . (int)$manager_id . "') ORDER BY order_id ASC");
				
				} else {
				
				$query = $this->db->query("SELECT DISTINCT order_id, date_added FROM `order_history` 			
				WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
				AND order_id IN (SELECT order_id FROM `order` WHERE manager_id > 0) ORDER BY order_id ASC");
			}
			
			return $query->rows;
		}
		
		public function getManagersWhoClosedOrdersForMonth($month, $year, $add_headsales = true){
			$this->load->model('user/user');
			
			$query = $this->db->query("SELECT 
			DISTINCT `order`.manager_id FROM `order` LEFT JOIN user ON user.user_id = `order`.manager_id WHERE order_id IN (
			SELECT DISTINCT order_id FROM `order_history` 			
			WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' 
			AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "' 
			ORDER BY order_id ASC) AND `order`.manager_id > 0 ORDER BY user.is_headsales ASC");
			
			
			$managers = array();
			$array_has_headsales = false;
			if ($query->num_rows){
				foreach ($query->rows as $row){
					
					if ($this->model_user_user->getUserISHeadSales($row['manager_id'])){
						$array_has_headsales = true;	
					}
					
					if (!$add_headsales){
						if (!$this->model_user_user->getUserISHeadSales($row['manager_id'])){
							$managers[] = $this->model_user_user->getUser($row['manager_id']);
						}
					} else {
						$managers[] = $this->model_user_user->getUser($row['manager_id']);
					}
				}
			}
			
			if (!$array_has_headsales && $add_headsales){
				if ($headsales_user_id = $this->model_user_user->getHeadSalesUserID()){
					$managers[] = $this->model_user_user->getUser($headsales_user_id);
				}
			}
			
		//	var_dump($managers);
			
			return $managers;
		}
		
		
		public function getAVGCSIByOrdersForMonth($manager_id, $month, $year){
			
			$this->load->model('kp/csi');
			
			$query = $this->db->query("SELECT 
			AVG(csi_average) as total
			FROM `order` 			
			WHERE 
			csi_average > 0 AND
			order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . $this->model_kp_csi->getCSIOrderStatusesLine() . ") AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "') 
			AND order_id IN (SELECT order_id FROM `order` WHERE manager_id = '" . (int)$manager_id . "')");
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getAVGCSIByManagerForMonth($manager_id, $month, $year){
			
			$this->load->model('kp/csi');
			
			$query = $this->db->query("SELECT 
			AVG(manager_mark) as total
			FROM `order` 			
			WHERE 
			manager_mark > 0 AND
			order_id IN (SELECT order_id FROM `order_history` WHERE order_status_id IN (" . $this->model_kp_csi->getCSIOrderStatusesLine() . ") AND MONTH(date_added) = '" . (int)$month . "' AND YEAR(date_added) = '" . (int)$year . "') 
			AND order_id IN (SELECT order_id FROM `order` WHERE manager_id = '" . (int)$manager_id . "')");
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		
		public function getCurrentCountOrderStatusForManager($manager_id, $order_status_id){
			
			$query = $this->db->query("SELECT COUNT(DISTINCT order_id) AS total FROM `order` WHERE manager_id = '" . (int)$manager_id . "' AND order_status_id = '" . $order_status_id . "'");			
			
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getCountOrderStatusForManager($manager_id, $order_status_id){
			
			$query = $this->db->query("SELECT COUNT(DISTINCT order_id) AS total FROM `order`
			WHERE manager_id = '" . (int)$manager_id . "' AND order_status_id = '" . $order_status_id . "'");			
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getCountOrderStatusForManagerForDate($manager_id, $order_status_id, $date){
			
			$query = $this->db->query("SELECT `count` AS total FROM `manager_order_status_dynamics`
			WHERE manager_id = '" . (int)$manager_id . "' AND order_status_id = '" . $order_status_id . "' AND date = '" . $this->db->escape($date) . "'");			
			
			if ($query->num_rows && isset($query->row['total'])){
				return $query->row['total'];
				} else {
				return 0;
			}
		}
		
		public function getLastOrderAdditionDate($manager_id, $order_status_id){
			
			$query = $this->db->query("SELECT MIN(date_added) as mindate FROM `order` WHERE manager_id = '" . (int)$manager_id . "' AND order_status_id = '" . $order_status_id . "'");
			
			if ($query->num_rows && isset($query->row['mindate'])){
				return $query->row['mindate'];
				} else {
				return 0;
			}
			
		}
		
		public function addtCurrentCountOrderStatusForManagerDynamics($manager_id, $order_status_id, $count){
			
			$query = $this->db->query("
			INSERT IGNORE INTO manager_order_status_dynamics
			SET manager_id = '" . (int)$manager_id . "',
			date = DATE(NOW()),
			order_status_id = '" . (int)$order_status_id . "',
			count = '" . (int)$count . "'				
			");
			
			
			
		}
		
		public function addManagerKPIHistory($manager_id, $data){
			
			$query = $this->db->query("
			INSERT INTO manager_kpi
			(manager_id, date_added, kpi_json)
			VALUES ('" . (int)$manager_id . "', DATE(NOW()), '" . $this->db->escape(json_encode($data)) . "')
			ON DUPLICATE KEY UPDATE
			kpi_json = '" . $this->db->escape(json_encode($data)) . "'");
			
			
			
		}
		
		public function getManagerLastKPI($manager_id){
			$query = $this->db->query("SELECT kpi_json FROM manager_kpi WHERE manager_id = '" . (int)$manager_id . "' ORDER BY date_added DESC LIMIT 1");
			
			if ($query->num_rows && isset($query->row['kpi_json'])){
				return json_decode($query->row['kpi_json'], true);
				} else {
				return false;
			}
		}
		
		public function getManagerOnDateKPI($manager_id, $date){
			$query = $this->db->query("SELECT kpi_json FROM manager_kpi WHERE manager_id = '" . (int)$manager_id . "' AND DATE(date_added) = '" . $this->db->escape($date) . "' LIMIT 1");
			
			if ($query->num_rows && isset($query->row['kpi_json'])){
				return json_decode($query->row['kpi_json'], true);
				} else {
				return false;
			}
		}
		
		public function syncUserDBCron(){
			
			
			
			
			
			
			
			
			
			
		}
		
	}									
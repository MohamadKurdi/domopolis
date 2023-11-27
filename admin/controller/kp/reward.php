<?
	
	class ControllerKPReward extends Controller {
		protected $error = array();

		public function reminder(){
			if (!$this->config->get('rewardpoints_reminder_enable')){
				echoLine('[ControllerKPReward::smscron] SMS Reminder is disabled in cron', 'e');
				exit;
			}

			$sql = "SELECT DISTINCT(c.customer_id), c.firstname, c.lastname, c.telephone, ";
			$sql .= " (SELECT SUM(points) as total FROM customer_reward crt WHERE customer_id = c.customer_id) AS points_amount, ";
			$sql .= " (SELECT " . (int)$this->config->get('config_reward_lifetime') . " - DATEDIFF(NOW(), MAX(date_added)) as points_days_left FROM customer_reward crdl WHERE points > 0  AND customer_id = c.customer_id) AS points_days_left ";
			$sql .= " FROM customer_reward cr LEFT JOIN customer c ON (cr.customer_id = c.customer_id) "; 
			$sql .= " WHERE ";				
			$sql .= " customer_reward_id IN ";
			$sql .= " (SELECT customer_reward_id FROM customer_reward WHERE points > 0 AND DATE(date_added) = DATE(DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('rewardpoints_reminder_days_noactive') . " DAY))) ";
		//	$sql .= " AND customer_reward_id NOT IN ";
		//	$sql .= " (SELECT customer_reward_id FROM customer_reward WHERE DATE(date_added) > DATE(DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('rewardpoints_reminder_days_noactive') . " DAY)) AND reason_code = 'ORDER_PAYMENT') ";				
			$sql .= " AND (SELECT SUM(points) AS total FROM customer_reward crt WHERE customer_id = c.customer_id) >= " . (int)$this->config->get('rewardpoints_reminder_min_amount') . "";		

			$query = $this->db->query($sql);
			
			if ($query->num_rows){
				echoLine('[ControllerKPReward::smscron] Have total ' . $query->num_rows . ' customers', 'i');

				foreach ($query->rows as $row){				
					echoLine('[ControllerKPReward::smscron] Sending reminder to ' . $row['customer_id'] . ' with phone ' . $row['telephone'], 's');
					$this->smsAdaptor->sendRewardReminder($row, ['points_amount' => $row['points_amount'], 'points_days_left' => $row['points_days_left']]);
				}
			} else {
				echoLine('[ControllerKPReward::smscron] Have no customers', 'e');
			}		

		}
		
		public function cron() {			
			$log = new Log('reward_queue.log');
			
			$this->load->model('setting/setting');
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			
			echoLine('[ControllerKPReward::cron] Burning process started', 'i');
			$query = $this->db->query("SELECT cr.*, c.language_id FROM customer_reward cr LEFT JOIN customer c ON (cr.customer_id = c.customer_id) WHERE burned = 0 AND DATE(cr.date_added) <= DATE_SUB(NOW(), INTERVAL " . $this->config->get('config_reward_lifetime') . " DAY) AND points > 0 AND points > points_paid ORDER BY customer_reward_id ASC");
			
			if ($query->num_rows){
				foreach ($query->rows as $row){
					$points_left = $row['points'] - $row['points_paid'];

					$string = '[ControllerKPReward::cron] Points ' . $row['customer_id'] . ' with reason ' . $row['reason_code'] . ', where added ' . $row['date_added'] . ', left ' . $points_left;
					echoLine($string, 'e');					
					$log->write($string);
					
					$description = sprintf($this->language->getCatalogLanguageString($row['language_id'], 'account/reward', 'text_reward_burn'), date('d.m.Y', strtotime($row['date_added'])), $row['description']);
					
					$this->customer->setBurnedByCRID($row['customer_reward_id']);
					$this->customer->addReward($row['customer_id'], $description, (-1)*$points_left, 0, 'POINTS_BURNED_BY_TIME');
				}
			}
			
			
			echoLine('[ControllerKPReward::cron] Reward process started', 'i');			
			$query = $this->db->query("SELECT * FROM customer_reward_queue WHERE DATE(date_activate) <= DATE(NOW()) ORDER BY customer_reward_queue_id ASC");
			
			if ($query->num_rows){
				foreach ($query->rows as $row){
					if ($this->customer->getRewardInTableByOrder($row['customer_id'], $row['order_id'])){
						//Это типа нештатная какая-то ситуация, несколько начислений, такого быть не должно
						//Разве что заказ несколько раз отменен а потом выполнен, в таком случае редактируем запись
						$string = '[ControllerKPReward::cron] Updating rewards for customer ' . $row['customer_id'] . ' for order ' . $row['order_id'];
						echoLine($string, 'w');						
						$log->write($string);
												
						$this->customer->updateRewardInTableByOrder($row['customer_id'], $row['order_id'], $row['points']);						
					} else {						
						$string = '[ControllerKPReward::cron] Adding rewards for customer ' . $row['customer_id'] . ' for order ' . $row['order_id'];
						echoLine($string, 's');						
						$log->write($string);
						
						$this->customer->addReward($row['customer_id'], $row['description'], $row['points'], $row['order_id'], $row['reason_code']);						
					}
										
					$this->customer->clearRewardQueueByCRQID($row['customer_reward_queue_id']);					
				}
			}

			if ($this->config->get('rewardpoints_review')){
				echoLine('[ControllerKPReward::cron] Reward for rewiews started', 'i');		
				$sql = "SELECT rw.*,					
					c.language_id as language_id
					FROM review rw 
					LEFT JOIN customer c ON (rw.customer_id = c.customer_id) 
					WHERE rw.customer_id > 0 
					AND rw.status = 1 
					AND DATE(rw.date_approved) >= DATE(DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('rewardpoints_review_days') . " DAY)) 
					AND rw.rewarded = 0";

				$query = $this->db->query($sql);

				foreach ($query->rows as $row){
					echoLine('[ControllerKPReward::cron] Review ' . $row['review_id'] . ', checking...', 'i');

					if ($this->config->get('rewardpoints_needs_purchased')){
						if ($this->customer->validateIfProductWasPurchased($row['product_id'], $row['customer_id'])){
							echoLine('[ControllerKPReward::cron] Product was bought by customer, ok', 's');
						} else {
							echoLine('[ControllerKPReward::cron] Product was not bought by customer, skip', 'e');
							continue;
						}
					}

					if ($this->config->get('rewardpoints_review_min_length')){
						$length = mb_strlen($row['text']) + mb_strlen($row['bads']) + mb_strlen($row['good']);

						if ($length >= (int)$this->config->get('rewardpoints_review_min_length')){
							echoLine('[ControllerKPReward::cron] Review length is ' . $length . ', ok', 's');
						} else {
							echoLine('[ControllerKPReward::cron] Review length is ' . $length . ', skip', 'e');
							continue;
						}
					}

					if ($this->config->get('rewardpoints_review_need_image')){
						if ($row['addimage']){
							echoLine('[ControllerKPReward::cron] Review has image, ok', 's');
						} else {
							echoLine('[ControllerKPReward::cron] Review has no image, skip', 'e');
							continue;
						}
					}

					echoLine('[ControllerKPReward::cron] Review passed all conditions, going on', 's');

					$points 		= (int)$this->model_setting_setting->getKeySettingValue('config', 'rewardpoints_review', (int)$row['store_id']);
					$description 	= $this->language->getCatalogLanguageString($row['language_id'], 'account/reward', 'text_review_add');

					$this->customer->addReward($row['customer_id'], $description, $points, 0, 'REVIEW_WRITTEN_REWARD');
					$this->db->query("UPDATE review SET rewarded = 1 WHERE review_id = '" . (int)$row['review_id'] . "'");
				}
			}			

			if ($this->config->get('rewardpoints_birthday')){
				echoLine('[ControllerKPReward::cron] Birthday greeting started', 'i');
				$this->db->query("UPDATE customer SET birthday_date = DAY(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");
				$this->db->query("UPDATE customer SET birthday_month = MONTH(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");			
				
				$sql = "SELECT DISTINCT(customer_id), store_id, language_id, firstname, lastname, telephone, email FROM customer WHERE birthday_month = '" . (int)date('n', strtotime('+1 day')) . "' AND birthday_date = '" . (int)date('j', strtotime('+1 day')) . "'";
				$query = $this->db->query($sql);
				
				if ($query->num_rows){
					foreach ($query->rows as $row){
						$validate_query = $this->db->query("SELECT * FROM customer_reward WHERE customer_id = '" . $row['customer_id'] . "' AND reason_code = 'BIRTHDAY_GREETING_REWARD' AND DATE(date_added) >= '" . date('Y-m-d', strtotime('-11 month')) . "'");

						if (!$validate_query->num_rows){
							$string = '[ControllerKPReward::cron] Customer ' . $row['customer_id'] . ' (' . $row['email'] . ') has no birthday greeting rewards for 365 days';
							echoLine($string, 's');						
							$log->write($string);

							$points 		= (int)$this->model_setting_setting->getKeySettingValue('config', 'rewardpoints_birthday', (int)$row['store_id']);
							$description 	= $this->language->getCatalogLanguageString($row['language_id'], 'account/reward', 'text_birthday_add');

							$string = '[ControllerKPReward::cron] Adding points for customer ' . $row['customer_id'] . ' (' . $row['email'] . ') for birthday';
							echoLine($string, 'e');						
							$log->write($string);

							$this->customer->addReward($row['customer_id'], $description, $points, 0, 'BIRTHDAY_GREETING_REWARD');

							$this->smsAdaptor->sendBirthdayGreeting($row, []);

						} else {
							$string = '[ControllerKPReward::cron] Customer ' . $row['customer_id'] . ' (' . $row['email'] . ') has a birthday greeting rewards for 365 days';
							echoLine($string, 'e');						
							$log->write($string);					
						}					
					}
				}
			}		
		}				
	}							
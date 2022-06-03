<?
	
	class ControllerKPReward extends Controller {
		protected $error = array();
		
		public function cron() {
			
			$log = new Log('reward_queue.log');
			
			$this->load->model('setting/setting');
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			
			echoLine('[REWARD] Начали сгорание бонусов');
			$query = $this->db->query("SELECT cr.*, c.language_id FROM customer_reward cr LEFT JOIN customer c ON (cr.customer_id = c.customer_id) WHERE burned = 0 AND DATE(cr.date_added) <= DATE_SUB(NOW(), INTERVAL " . $this->config->get('config_reward_lifetime') . " DAY) AND points > 0 AND points > points_paid ORDER BY customer_reward_id ASC");
			
			if ($query->num_rows){
				foreach ($query->rows as $row){
					$points_left = $row['points'] - $row['points_paid'];
					echoLine('Бонусы ' . $row['customer_id'] . ' по причине ' . $row['reason_code'] . ', были добавлены ' . $row['date_added'] . ', из них осталось ' . $points_left);
					
					$log->write('Сгорание: Бонусы ' . $row['customer_id'] . ' по причине ' . $row['reason_code'] . ', были добавлены ' . $row['date_added'] . ', из них осталось ' . $points_left);
					
					$description = sprintf($this->language->getCatalogLanguageString($row['language_id'], 'account/reward', 'text_reward_burn'), date('d.m.Y', strtotime($row['date_added'])), $row['description']);
					
					$this->customer->setBurnedByCRID($row['customer_reward_id']);
					$this->customer->addReward($row['customer_id'], $description, (-1)*$points_left, 0, 'POINTS_BURNED_BY_TIME');
				}
			}
			
			
			echoLine('[REWARD] Начали начисление бонусов');
			
			$query = $this->db->query("SELECT * FROM customer_reward_queue WHERE DATE(date_activate) <= DATE(NOW()) ORDER BY customer_reward_queue_id ASC");
			
			if ($query->num_rows){
				foreach ($query->rows as $row){
					//Проверка существования записи
					
					if ($this->customer->getRewardInTableByOrder($row['customer_id'], $row['order_id'])){
						//Это типа нештатная какая-то ситуация, несколько начислений, такого быть не должно
						//Разве что заказ несколько раз отменен а потом выполнен, в таком случае редактируем запись
						
						echoLine('Обновляем баллы покупателю ' . $row['customer_id'] . ' за заказ ' . $row['order_id']);
						
						$log->write('Обновляем баллы покупателю ' . $row['customer_id'] . ' за заказ ' . $row['order_id']);
						
						
						$this->customer->updateRewardInTableByOrder($row['customer_id'], $row['order_id'], $row['points']);
						} else {
						
						echoLine('Начисляем баллы покупателю ' . $row['customer_id'] . ' за заказ ' . $row['order_id']);
						$log->write('Начисляем баллы покупателю ' . $row['customer_id'] . ' за заказ ' . $row['order_id']);
						
						$this->customer->addReward($row['customer_id'], $row['description'], $row['points'], $row['order_id'], $row['reason_code']);
						
					}
					
					
					$this->customer->clearRewardQueueByCRQID($row['customer_reward_queue_id']);
					
				}
			}
			
			echoLine('[REWARD] Начали начисление бонусов c днем рождения');
			$this->db->query("UPDATE customer SET birthday_date = DAY(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");
			$this->db->query("UPDATE customer SET birthday_month = MONTH(DATE(birthday)) WHERE LENGTH(birthday) > 4 AND birthday <> '0000-00-00';");
			
			$query = $this->db->query("SELECT DISTINCT(customer_id), store_id, language_id FROM customer WHERE birthday_month = '" . (int)date('n', strtotime('+1 day')) . "' AND birthday_date = '" . (int)date('j', strtotime('+1 day')) . "'");
			
			if ($query->num_rows){
				foreach ($query->rows as $row){
					//Валидация нет ли случайно уже такой записи за последние 365 дней
					
					$validate_query = $this->db->query("SELECT * FROM customer_reward WHERE customer_id = '" . $row['customer_id'] . "' AND reason_code = 'BIRTHDAY_GREETING_REWARD' AND DATE(date_added) >= '" . date('Y-m-d', strtotime('-1 year')) . "'");
					
					if (!$validate_query->num_rows){
						
						echoLine('[REWARD] У клиента ' . $row['customer_id'] . ' нету начисления за 365 дней');
						
						$points = (int)$this->model_setting_setting->getKeySettingValue('config', 'rewardpoints_birthday', (int)$row['store_id']);
						$description = $this->language->getCatalogLanguageString($row['language_id'], 'account/reward', 'text_birthday_add');
						
						echoLine('Начисляем ' . $points . ' баллы покупателю ' . $row['customer_id'] . ' за день рождения ');
						$log->write('Начисляем ' . $points . ' баллы покупателю ' . $row['customer_id'] . ' за день рождения ');
						
						$this->customer->addReward($row['customer_id'], $description, $points, 0, 'BIRTHDAY_GREETING_REWARD');
						
					
					} else {
						
						echoLine('[REWARD] У клиента ' . $row['customer_id'] . ' уже есть начисление за 365 дней');
					
					}
					
				}
			}
			
		}
		
		
	}							
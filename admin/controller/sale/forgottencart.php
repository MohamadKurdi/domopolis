<?

class ControllerSaleForgottenCart extends Controller {
	private $error = array();

	public function index() {
		
			
	}
	
	public function sendforgottencarts(){
		
			
		$this->load->model('sale/order');
		$this->load->model('module/emailtemplate');
		$this->load->model('setting/setting');
		
		$managers_alert_simple = array();
		$managers_alert_now = array();
		$mail_alert = array();
		$customers_alert = array();
		
		$old_orders_query = $this->db->non_cached_query("SELECT order_id, customer_id, date_added FROM `order` WHERE order_status_id = 0 AND date_added < CAST(NOW() - INTERVAL 1 MONTH AS DATE) ORDER BY date_added DESC");
		foreach ($old_orders_query->rows as $row){
			$this->model_sale_order->deleteOrder($row['order_id']);
			echo '>>> Удалили старый заказ ' . $row['order_id'] . PHP_EOL;
		}
		
		  
		//выбираем все заказы недооформленные ВЧЕРА
		$all_order_query = $this->db->non_cached_query("SELECT order_id, customer_id, date_added FROM `order` WHERE order_status_id = 0 AND date_added < CAST(NOW() - INTERVAL 1 DAY AS DATE) ORDER BY date_added DESC");
		
		foreach ($all_order_query->rows as $row){									
			$customer_id = $row['customer_id'];
			$order_id = $row['order_id'];
			$date_added = $row['date_added'];
			
			echo '- START ' . $order_id . PHP_EOL;
			
			//есть оформленные заказы 
			$after_this_order_query = $this->db->non_cached_query("SELECT count(order_id) as total FROM `order` WHERE customer_id = '" . $customer_id . "' AND order_status_id <> 0 AND date_modified > '" . $date_added . "'");
			
			if ($customer_id == 1377565){
			
				echo '-- CUSTOMER_ID : ' . $customer_id . PHP_EOL;
				echo "SELECT count(order_id) as total FROM `order` WHERE customer_id = '" . $customer_id . "' AND order_status_id <> 0 AND date_added > '" . $date_added . "'";
			}
			
			if ($after_this_order_query->row['total'] > 0){
				$this->model_sale_order->deleteOrder($order_id);				
				echo '- CUSTOMER HAS ' . $after_this_order_query->row['total'] . ' ORDERS AFTER : DELETED ' . $order_id . PHP_EOL;
				
				//нет далее оформленных заказов - проверяем, первый ли это заказ человека ДО
			} else {
				
				$before_this_order_query = $this->db->non_cached_query("SELECT count(order_id) as total FROM `order` WHERE customer_id = '" . $customer_id . "' AND order_status_id <> 0 AND date_added < '" . $date_added . "'");
				
				if ($before_this_order_query->row['total'] > 0) {					
					//добавляем в заказы, которые пойдут в уведомление менеджеру, на звонок
					
					//ЕСЛИ У ЧЕЛОВЕКА ИХ МНОГО
					$total_known_customer_nonended_order_query = $this->db->non_cached_query("SELECT count(order_id) as total FROM `order` WHERE customer_id = '" . $customer_id . "' AND order_status_id = 0 AND order_id <> '" . $order_id . "'");
					
					if ($total_known_customer_nonended_order_query->row['total'] > 0) {
						
						$managers_alert_now[$order_id] = 'У известного нам покупателя есть <b>'. ($total_known_customer_nonended_order_query->row['total'] + 1) .'</b> незавершенных заказов';
						echo '- CUSTOMER HAS '. $total_known_customer_nonended_order_query->row['total']  .' NONENDED ORDERS NOW : CALL NOW! ' . $order_id . PHP_EOL;
						
						$customers_alert[$customer_id] = $total_known_customer_nonended_order_query->row['total'] + 1;
						
					} else {
					
						$managers_alert_simple[$order_id] = 'Это не первый заказ. У покупателя есть уже '.$before_this_order_query->row['total'].' заказов, и 1 незавершенный';			
						echo '- CUSTOMER HAS ORDERS BEFORE : CALL ' . $order_id . PHP_EOL;
					
					}
					
					//нет у человека далее оформленных заказов ни ранее ни далее - проверяем количество недооформленных вообще
				} else {
					
					$total_customer_nonended_order_query = $this->db->non_cached_query("SELECT count(order_id) as total FROM `order` WHERE customer_id = '" . $customer_id . "' AND order_status_id = 0 AND order_id <> '" . $order_id . "'");
					
					if ($total_customer_nonended_order_query->row['total'] > 0){
						$managers_alert_now[$order_id] = 'У покупателя есть <b>'. ($total_customer_nonended_order_query->row['total'] + 1) .'</b> незавершенных заказов';	
						echo '- CUSTOMER HAS '. $total_customer_nonended_order_query->row['total']  .' NONENDED ORDERS NOW : CALL NOW! ' . $order_id . PHP_EOL;
												
						$customers_alert[$customer_id] = $total_customer_nonended_order_query->row['total'] + 1;
						
						//и вот мі приходим к моменту единственного недооформленного заказу..
					} else {
						$mail_alert[$order_id] = 'У клиента единственный недооформленный заказ!';
						echo '- CUSTOMER HAS SINGLE NONENDED ORDERS NOW : MAIL ' . $order_id . PHP_EOL;
					}
					
				}
				
			}
			
		}
						
		
		array_unique($customers_alert);
		$data = array(
			'type' => 'error',
			'text' => 'У '. count($customers_alert) .' клиентов более одного незавершенного заказа!', 
			'entity_type' => 'forgottencart', 
			'entity_id' => ''
		);
						
		//$this->mAlert->insertAlertForGroup('sales', $data);
		//$this->mAlert->insertAlertForGroup('admins', $data);
		
		
		
				
//подготовка текста для уведомления менеджерам...
		$html = 'Проверка незавершенных заказов '.date('Y.m.d H:i:s') .". Всего <b style='color:#cf4a61'>". (count($managers_alert_now) + count($mail_alert)) .'</b> заказов.<br /><br />'. PHP_EOL;
		$html .= "<table style='border:0px; width:100%' class='list'>" . PHP_EOL;
		$html .= '<tr>';
		$html .= '<td style="padding:3px; width:1px;">Заказ</td>';
		$html .= '<td style="padding:3px; width:1px;">Покупатель</td>';
		$html .= '<td style="padding:3px; width:1px; wrap-whitespace:none; white-space:nowrap;">Покупатель: id</td>';
		$html .= '<td style="padding:3px; width:1px; wrap-whitespace:none; white-space:nowrap;">Дата оформления</td>';
		$html .= '<td style="padding:3px; wrap-whitespace:none; white-space:nowrap;">Итого</td>';
		$html .= '<td style="padding:3px; wrap-whitespace:none; white-space:nowrap;">Рекомендация</td>';
		$html .= '<td style="padding:3px; wrap-whitespace:none; white-space:nowrap;">Пояснение</td>';
		$html .= '<td style="padding:3px; wrap-whitespace:none; white-space:nowrap;">Комментарий CRM</td>';
		$html .= '<td style="padding:3px; wrap-whitespace:none; white-space:nowrap;">Посмотреть</td>';
		$html .= '</tr>';
		
		foreach ($managers_alert_now as $now_order_id => $explain) {
			
			$order = $this->model_sale_order->getOrder($now_order_id);
			
			$comment_query = $this->db->non_cached_query("SELECT * FROM customer_history WHERE customer_id = '" . $order['customer_id'] . "' AND call_id = 0 AND order_id = 0 AND segment_id = 0 AND email_id = 0 AND sms_id = 0 ORDER BY date_added DESC LIMIT 1");
			if ($comment_query->num_rows) {
				$last_comment = $comment_query->row['comment'];
			} else {
				$last_comment = '';
			}
			
						
			$html .= '<tr>';
			$html .= '<td style="padding:5px;"><b><order>'.$order['order_id'].'</order></b></td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">'. $order['firstname'].' '. $order['lastname'] . '</td>';	
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><b><customer>'.$order['customer_id'].'</customer></b></td>';	
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">'.date('d.m.Y H:i:s', strtotime($order['date_added'])).'</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">' . round($order['total_national']) . ' ' . $order['currency_code'] .'</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><span class="status_color" style="background-color:#cf4a61; color:white;">Немедленно перезвонить!</span></td>';
			$html .= '<td style="padding:5px; font-size:10px;">' . $explain . '</td>';
			$html .= '<td style="padding:5px; font-size:10px;">' . $last_comment . '</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><b><filter>'.$order['customer_id'].'</filter></b></td>';
			$html .= '</tr>';
			
		}
		
		foreach ($managers_alert_simple as $s_order_id => $explain) {
			
			$order = $this->model_sale_order->getOrder($s_order_id);
			
				$comment_query = $this->db->non_cached_query("SELECT * FROM customer_history WHERE customer_id = '" . $order['customer_id'] . "' AND call_id = 0 AND order_id = 0 AND segment_id = 0 AND email_id = 0 AND sms_id = 0 ORDER BY date_added DESC LIMIT 1");
			if ($comment_query->num_rows) {
				$last_comment = $comment_query->row['comment'];
			} else {
				$last_comment = '';
			}
						
			
			$html .= '<tr>';
			$html .= '<td style="padding:5px;"><b><order>'.$order['order_id'].'</order></b></td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">'. $order['firstname'].' '. $order['lastname'] . '</td>';	
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><b><customer>'.$order['customer_id'].'</customer></b></td>';	
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">'.date('d.m.Y H:i:s', strtotime($order['date_added'])).'</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">' . round($order['total_national']) . ' ' . $order['currency_code'] .'</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><span style="padding:3px; background-color:#e4c25a; color:white;">Перезвонить!</span></td>';
			$html .= '<td style="padding:5px; font-size:10px;">' . $explain . '</td>';
			$html .= '<td style="padding:5px; font-size:10px;">' . $last_comment . '</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><b><filter>'.$order['customer_id'].'</filter></b></td>';
			$html .= '</tr>';
			
		}
		
		foreach ($mail_alert as $m_order_id => $explain) {						
			$order = $this->model_sale_order->getOrder($m_order_id);
			
				$comment_query = $this->db->non_cached_query("SELECT * FROM customer_history WHERE customer_id = '" . $order['customer_id'] . "' AND call_id = 0 AND order_id = 0 AND segment_id = 0 AND email_id = 0 AND sms_id = 0 ORDER BY date_added DESC LIMIT 1");
			if ($comment_query->num_rows) {
				$last_comment = $comment_query->row['comment'];
			} else {
				$last_comment = '';
			}
			
			$html .= '<tr>';
			$html .= '<td style="padding:5px;"><b><order>'.$order['order_id'].'</order></b></td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">'. $order['firstname'].' '. $order['lastname'] . '</td>';	
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><b><customer>'.$order['customer_id'].'</customer></b></td>';	
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">'.date('d.m.Y H:i:s', strtotime($order['date_added'])).'</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;">' . round($order['total_national']) . ' ' . $order['currency_code'] .'</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><span style="padding:3px; background-color:#4ea24e; color:white;">Отправлено письмо...</span></td>';
			$html .= '<td style="padding:5px; font-size:10px;">'. $explain .'</td>';
			$html .= '<td style="padding:5px; font-size:10px;">' . $last_comment . '</td>';
			$html .= '<td style="padding:5px; wrap-whitespace:none; white-space:nowrap;"><b><filter>'.$order['customer_id'].'</filter></b></td>';
			$html .= '</tr>';
			
		}
		
		$html .= '</table>';
		
		$this->db->non_cached_query("UPDATE `temp` SET `value` = '" . $this->db->escape($html) . "', date_modified = NOW() WHERE `key` = 'orders_result'");
	
/*	
//подготовка шаблона для отправки писем		
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		$data = array(
				'store_id' => $order_info['store_id'],
				'language_id' => $order_info['language_id'],
				'emailtemplate_key' => 'customer.cart_reminder',
		);
		
		$template = $this->model_module_emailtemplate->getCompleteOrderEmail($order_id, $data);
						
		$mail = new Mail($this->registry); 
		$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
		$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
		$mail->setSubject($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
		$template->load('customer.cart_reminder');
		$template->build();
		$mail = $template->hook($mail);			
		$mail->setText($template->getPlainText());					
		$mail->setTo($order_info['email']);
		$mail->send();
		
	*/	
		
		
		
		
		
		
	}
	
}
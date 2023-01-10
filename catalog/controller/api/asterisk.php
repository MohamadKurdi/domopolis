<?php

class ControllerAPIAsterisk extends Controller {

	public function index(){
		$callLog = new Log('asterisk.txt');

		$input = $this->request->post;

		if (isset($input['inbound']) && $input['inbound'] == 1){
			$callLog->write(serialize($input));

		//check integrity  
		if (isset($input['callerid']) && isset($input['checksum']) && true /*(md5($input['callerid'].'+kitchen-profi.ru') == $input['checksum'])*/){
			$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
			
			$sql = "SELECT customer_id, firstname, lastname FROM `customer` WHERE ";		
			$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
			$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
			$sql .= " LIMIT 1";
			
			$query = $this->db->query($sql);
			
			$alert_namespace = 'sales';
			if (isset($input['queue_id']) && $input['queue_id']){
				$find_group = $this->db->query("SELECT alert_namespace FROM user_group WHERE sip_queue = '" . $this->db->escape($input['queue_id']) . "' LIMIT 1");
				if (isset($find_group->row['alert_namespace']) && $find_group->row['alert_namespace']){
					$alert_namespace = $find_group->row['alert_namespace'];
				} else {
					$alert_namespace = 'sales';
				}
			}
			
			if ($query->row) {
				
				$data = array(
					'type' => 'success',
					'text' => "Общий входящий звонок: <br /><br /><span style='font-size:16px;'>".$query->row['firstname'] . ' ' . $query->row['lastname'].'</span>', 
					'entity_type' => 'inboundcall', 
					'entity_id' => $query->row['customer_id']
				);
				
				$this->mAlert->insertAlertForGroup($alert_namespace, $data);		
				
				die ($query->row['firstname'] . ' ' . $query->row['lastname'] . ' ('.$query->row['customer_id'].')');
			} else {
				
				$data = array(
					'type' => 'success',
					'text' => "Общий входящий звонок: <br /><br /><span style='font-size:16px;'>".$input['callerid']."</span>", 
					'entity_type' => 'inboundcall', 
					'entity_id' => 0
				);
				
				$this->mAlert->insertAlertForGroup($alert_namespace, $data);
				
				die ($input['callerid']);
			}
			
		} else {
			die ('no_checksum');
		}
	} elseif (isset($input['inboundto']) && $input['inboundto'] == 1) {
		$callLog->write(serialize($input));
		
		if (isset($input['callerid']) && isset($input['destination'])) {
			
			$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
			$dest = trim(preg_replace("([^0-9])", "", $input['destination']));
			
			$sql = "SELECT customer_id, firstname, lastname FROM `customer` WHERE ";		
			$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
			$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
			$sql .= " LIMIT 1";
			
			$query = $this->db->query($sql);
			
			if ($query->row) {
				$customer_name = $query->row['firstname'] . ' ' . $query->row['lastname'] . ' ('.$query->row['customer_id'].')';
				$customer_id = $query->row['customer_id'];
			} else {
				$customer_name = $phone;
				$customer_id = 0;
			}
			
			$sql = "SELECT user_id FROM user WHERE internal_pbx_num = '" . $this->db->escape($dest) . "'";
			$query = $this->db->query($sql);
			
			if ($query->row) {
				
				$data = array(
					'type' => 'success',
					'text' => "Входящий звонок: <br /><br /><span style='font-size:16px;'>".$customer_name.'</span>', 
					'entity_type' => 'inboundcall', 
					'entity_id' => $customer_id
				);
				
				$this->mAlert->insertAlertForOne($query->row['user_id'], $data);					
			}
			
		} else {
			die ('no_checksum');
		}
		
	} elseif (isset($input['tomanager']) && $input['tomanager'] == 1) {
		$callLog->write(serialize($input));
		
		if (isset($input['callerid'])) {
			$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
			
			if (strpos($phone, '420') === 0){
				echo trim('210');
				die();
			}
			
			$sql = "SELECT u.user_id, u.internal_pbx_num FROM `order` o ";
			$sql .= "LEFT JOIN user u ON u.user_id=o.manager_id WHERE u.internal_pbx_num > 0 AND ";		
			$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
			$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
			$sql .= " ORDER BY o.date_added DESC LIMIT 1";
			
			$query = $this->db->query($sql);					
			
			if ($query->row && $this->cache->get('sip_managers_online') && in_array($query->row['internal_pbx_num'], $this->cache->get('sip_managers_online'))) {
				//	echo trim($query->row['internal_pbx_num']);
				echo 'nope';
				die;			
			} else {
				echo 'nope';
				die;
			}
		} else {
			echo 'nope';
			die;
		}
		
	} elseif (isset($input['missedcall']) && $input['missedcall'] == 1) {
		
		$callLog->write(serialize($input));
		
		if (isset($input['callerid']) && isset($input['dialedtime']) && isset($input['destination'])){
			
			
			if (isset($input['from']) && $input['from'] == 'macrohangup'){				
				$from_hangup = true;								
			} else {
				$from_hangup = false;
			}	
			
			if (isset($input['from']) && $input['from'] == 'sendmissedcall'){				
				$from_sendmissedcall = true;								
			} else {
				$from_sendmissedcall = false;
			}
			
			if (isset($input['direction']) && $input['direction'] == 'INBOUND'){	
				$real_inbound_call = true;
			} else {
				$real_inbound_call = false;
			}
			
			//incoming missed call!	
			if (mb_strlen($input['callerid'], 'UTF8') >= 4){
				
				$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
				$inbound = 1;
				
				//discover customer
				$sql = "SELECT customer_id, firstname, lastname, telephone FROM `customer` WHERE ";		
				$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
				$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
				$sql .= " LIMIT 1";		
				$query = $this->db->query($sql);
				
				//checking queue
				$queue_id = 0;
				//from missedcall
				if (isset($input['queue_id']) && $input['queue_id']){
					$queue_id = $input['queue_id'];
				}
				
				//from macrohangup
				if (isset($input['nodest']) && $input['nodest']){
					$queue_id = $input['nodest'];
				}
				
				$alert_namespace = 'sales';
				if ($queue_id){
					$find_group = $this->db->query("SELECT alert_namespace FROM user_group WHERE sip_queue = '" . $this->db->escape($queue_id) . "' LIMIT 1");
					if (isset($find_group->row['alert_namespace']) && $find_group->row['alert_namespace']){
						$alert_namespace = $find_group->row['alert_namespace'];
					} else {
						$alert_namespace = 'sales';
					}
				}
				
				if ($query->row && $query->row['customer_id']) {
					
					if ($real_inbound_call || $from_sendmissedcall) {
						
						$data = array(
							'type' => 'warning',
							'text' => "Пропущенный звонок: <br /><br /><span style='font-size:16px;'>".$query->row['firstname'] . ' ' . $query->row['lastname'].'</span>', 
							'entity_type' => 'missedcall', 
							'entity_id' => $query->row['customer_id']
						);
						
						$this->mAlert->insertAlertForGroup($alert_namespace, $data);							
						
					}
					
					$customer_id = (int)$query->row['customer_id'];
					$name = $query->row['firstname'].' '.$query->row['lastname'];
					
					$sql = ("INSERT IGNORE INTO `customer_calls` SET 
						customer_id = '" .(int)$customer_id. "', 
						inbound = '" .(int)$inbound. "',
						customer_phone = '" . $this->db->escape($phone). "',
						length = 0,					
						date_end = NOW(), 
						comment = '',
						sip_queue = '" . $this->db->escape($queue_id) . "',
						internal_pbx_num = '" . $this->db->escape($internal) . "',
						manager_id = '" . (int)$manager_id . "',
						filelink = '',
						order_id = 0
						");
					
					$this->db->query($sql);
					$n = $this->db->getLastId();
					
					if ($n) {
						$comment = 'Пропущенный входящий звонок.';										
						
						$sql = ("INSERT IGNORE INTO `customer_history` 
							SET customer_id = '" . (int)$customer_id . "', 
							comment = '" . $this->db->escape($comment) . "', 
							call_id = '" . (int)$n . "',
							manager_id = '" . (int)$manager_id . "',
							date_added = NOW()");
						
						$this->db->query($sql);
					}
					
					
				} else {
					
					if ($real_inbound_call) {
						
						$data = array(
							'type' => 'warning',
							'text' => "Пропущенный звонок: <br /><br /><span style='font-size:16px;'>".$this->db->escape($phone).'</span>', 
							'entity_type' => 'missedcall', 
							'entity_id' => 0
						);
						
						$this->mAlert->insertAlertForGroup($alert_namespace, $data);				
						
						$customer_id = 0;
						$name = 'Неизвестно';
						
					}
				}		
				
				if ($real_inbound_call || $from_sendmissedcall) {
					
					$query = $this->db->query("INSERT INTO callback SET 
						name = '" . $this->db->escape($name)  . "', 
						comment_buyer = '" . $this->db->escape('Пропущенный звонок')  . "',
						email_buyer = '" . $this->db->escape('')  . "', 
						telephone = '" . $this->db->escape($phone) . "',
						customer_id = '". (int)$customer_id ."',
						sip_queue = '" . $this->db->escape($queue_id) . "',
						date_added = NOW(), 
						date_modified = NOW(), 
						status_id = '0',
						is_missed = '1',
						comment = ''"
					);
					
				}
				
				die('ok');
				
			} else {
				//outgoing missed call!	
				$phone = trim(preg_replace("([^0-9])", "", $input['destination']));
				$inbound = 0;
				$internal = trim(preg_replace("([^0-9])", "", $input['callerid']));
				
				$sql = "SELECT customer_id, firstname, lastname, telephone FROM `customer` WHERE ";		
				$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
				$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
				$sql .= " LIMIT 1";	
				
				$query = $this->db->query($sql);
				
				if ($query->row && $query->row['customer_id']) {
					$customer_id = (int)$query->row['customer_id'];
				} else {
					$customer_id = 0;
				}
				
				//checking manager_id
				$sql = "SELECT user_id FROM user WHERE internal_pbx_num = '" . trim($this->db->escape($internal)) . "' LIMIT 1";
				$query = $this->db->query($sql);
				
				if ($query->row && $query->row['user_id']) {
					$manager_id = (int)$query->row['user_id'];
				} else {
					$manager_id = 0;
				}
				
				$sql = ("INSERT IGNORE INTO `customer_calls` SET 
					customer_id = '" .(int)$customer_id. "', 
					inbound = '" .(int)$inbound. "',
					customer_phone = '" . $this->db->escape($phone). "',
					length = 0,					
					date_end = NOW(), 
					comment = '', 
					sip_queue = '0',
					internal_pbx_num = '" . $this->db->escape($internal) . "',
					manager_id = '" . (int)$manager_id . "',
					filelink = '',
					order_id = 0
					");
				
				$this->db->query($sql);
				$n = $this->db->getLastId();
				
				if ($n) {
					$comment = 'Пропущенный исходящий звонок.';										
					
					$sql = ("INSERT IGNORE INTO `customer_history` 
						SET customer_id = '" . (int)$customer_id . "', 
						comment = '" . $this->db->escape($comment) . "', 
						call_id = '" . (int)$n . "',
						manager_id = '" . (int)$manager_id . "',
						date_added = NOW()");
					
					$this->db->query($sql);
				}
				
				if ($n) {
					die ('ok');
				} else {
					die ('nok');
				}													
			}
			
		} else {
			die;
		}
		
	} elseif (isset($input['callend']) && $input['callend'] == 1){
		
		$callLog->write(serialize($input));	
		
	if (isset($input['callerid']) && isset($input['checksum']) && true /*(md5($input['callerid'].'+kitchen-profi.ru') == $input['checksum'])*/) {				
			//outbound or inbound

		if (mb_strlen($input['callerid'], 'UTF8') <= 4){
			$inbound = 0;
			$phone = trim(preg_replace("([^0-9])", "", $input['destination']));
			$internal = trim(preg_replace("([^0-9])", "", $input['callerid']));
		} else {
			$inbound = 1;
			$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
			$internal = trim(preg_replace("([^0-9])", "", $input['destination']));
		}

			//checking customer_id
		$sql = "SELECT customer_id FROM `customer` WHERE ";		
		$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
		$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
		$sql .= " LIMIT 1";		
		$query = $this->db->query($sql);

		if ($query->row && $query->row['customer_id']) {
			$customer_id = (int)$query->row['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (mb_strlen($phone, 'UTF8') <= 4){
			$customer_id = 0;
		}

			//checking manager_id
		$sql = "SELECT user_id FROM user WHERE internal_pbx_num = '" . trim($this->db->escape($internal)) . "' LIMIT 1";
		$query = $this->db->query($sql);

		if ($query->row && $query->row['user_id']) {
			$manager_id = (int)$query->row['user_id'];
		} else {
			$manager_id = 0;
		}


		$sql = ("INSERT IGNORE INTO `customer_calls` SET 
			customer_id = '" .(int)$customer_id. "', 
			inbound = '" .(int)$inbound. "',
			customer_phone = '" . $this->db->escape($phone). "',
			length = '" . (int)$this->db->escape($input['dialedtime']) . "',
			date_end = NOW(), 
			comment = '', 
			internal_pbx_num = '" . $this->db->escape($internal) . "',
			manager_id = '" . (int)$manager_id . "',
			filelink = '" . $this->db->escape($input['recordname']) . "',
			order_id = 0
			");

		$this->db->query($sql);
		$n = $this->db->getLastId();

		if ($n) {
			if (!$input['dialedtime']){
				$comment = 'Пропущенный исходящий звонок.';						
			} else {
				$comment = 'Звонок, общение с покупателем.';						
			}
			$sql = ("INSERT IGNORE INTO `customer_history` 
				SET customer_id = '" . (int)$customer_id . "', 
				comment = '" . $this->db->escape($comment) . "', 
				call_id = '" . (int)$n . "',
				manager_id = '" . (int)$manager_id . "',
				date_added = NOW()");

			$this->db->query($sql);
		}

		if ($n) {
			die ('ok');
		} else {
			die ('nok');
		}				

	} else {
		die ('no_checksum');
	}
}

die('no_input');


}


}
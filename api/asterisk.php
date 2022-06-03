<?php
	
	$allowed_ips = array('5.9.143.113', '162.158.88.227', '37.57.184.228');
	
	/*
		if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)){
		die($_SERVER['REMOTE_ADDR']);
		die('invalid addr');	
		}
	*/
	
	//Инициализация движка Опенкарта 
	error_reporting(E_ALL);
	// Версия скрипта
	define('VERSION', '1.0.0');
	// Берем конфиг административной части, поскольку интеграция производится в нее
	require_once(dirname(__FILE__).'/../admin/config.php');
	
	require_once(DIR_SYSTEM . '../vendor/autoload.php');	
	require_once(DIR_SYSTEM . 'startup.php');
	require_once(DIR_SYSTEM . 'library/currency.php');
	require_once(DIR_SYSTEM . 'library/customer.php');
	require_once(DIR_SYSTEM . 'library/user.php');
	require_once(DIR_SYSTEM . 'library/weight.php');
	require_once(DIR_SYSTEM . 'library/length.php');
	require_once(DIR_SYSTEM . 'library/cart.php');
	require_once(DIR_SYSTEM . 'library/smsQueue.php');
	require_once(DIR_SYSTEM . 'library/mAlert.php');
	require_once(DIR_SYSTEM . 'library/pushQueue.php');
	require_once(DIR_SYSTEM . 'library/Bitrix24.php');
	require_once(DIR_SYSTEM . 'library/shortAlias.php');
	require_once(DIR_SYSTEM . 'library/sessionDBHandler.php');
	require_once(DIR_SYSTEM . 'library/PageCache.php');
	require_once(DIR_SYSTEM . 'library/hobotix/EmailBlackList.php');
	require_once(DIR_SYSTEM . 'library/hobotix/RainforestAmazon.php');
	require_once(DIR_SYSTEM . 'library/hobotix/PricevaAdaptor.php');
	
	// Registry
	$registry = new Registry();
	
	// Loader
	$loader = new Loader($registry);
	$registry->set('load', $loader);
	
	// Config
	$config = new Config();
	$registry->set('config', $config);
	
	// Database
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$registry->set('db', $db);
	
	// Settings
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
	
	foreach ($query->rows as $setting) {
		if (!$setting['serialized']) {
			$config->set($setting['key'], $setting['value']);
			} else {
			$config->set($setting['key'], $setting['value']?unserialize($setting['value']):false);
		}
	}
	
	// Log 
	$log = new Log('calls_error.txt');
	$registry->set('log', $log);
	
	// Error Handler
	function error_handler($errno, $errstr, $errfile, $errline) {
		global $config, $log;
		
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
			$error = 'Notice';
			break;
			case E_WARNING:
			case E_USER_WARNING:
			$error = 'Warning';
			break;
			case E_ERROR:
			case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
			default:
			$error = 'Unknown';
			break;
		}
		
		if (true) {
			//	echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		}
		
		if (true) {
			$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
		}
		
		return TRUE;
	}
	
	// Error Handler
	set_error_handler('error_handler');
	
	
	// Request
	$request = new Request();
	$registry->set('request', $request);
	
	// Response
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->set('response', $response); 
	
	// Session
	$registry->set('session', new Session());
	
	// Cache
	$cache = new Cache();
	$registry->set('cache', $cache);
	
	// Document
	$registry->set('document', new Document());
	
	// Language
	$languages = array();
	
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 
	
	foreach ($query->rows as $result) {
		$languages[$result['code']] = array(
		'language_id'	=> $result['language_id'],
		'name'		=> $result['name'],
		'code'		=> $result['code'],
		'locale'	=> $result['locale'],
		'directory'	=> $result['directory'],
		'filename'	=> $result['filename']
		);
	}
	$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
	
	$language = new Language($languages[$config->get('config_admin_language')]['directory']);
	$language->load($languages[$config->get('config_admin_language')]['filename']);	
	$registry->set('language', $language);
	
	$registry->set('currency', new Currency($registry));
	$registry->set('weight', new Weight($registry));
	$registry->set('length', new Length($registry));
	$registry->set('user', new User($registry));
	
	$mAlert = new mAlert($registry);
	$registry->set('mAlert', $mAlert);
	
	$controller = new Front($registry);
	
	$call_log = new Log('calls_log.txt');
	$input = $request->post;
	
	if (isset($input['inbound']) && $input['inbound'] == 1){
		$call_log->write(serialize($input));
		
		//check integrity  
		if (isset($input['callerid']) && isset($input['checksum']) && true /*(md5($input['callerid'].'+kitchen-profi.ru') == $input['checksum'])*/){
			$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
			
			$sql = "SELECT customer_id, firstname, lastname FROM `customer` WHERE ";		
			$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
			$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
			$sql .= " LIMIT 1";
			
			$query = $db->query($sql);
			
			$alert_namespace = 'sales';
			if (isset($input['queue_id']) && $input['queue_id']){
				$find_group = $db->query("SELECT alert_namespace FROM user_group WHERE sip_queue = '" . $db->escape($input['queue_id']) . "' LIMIT 1");
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
				
				$mAlert->insertAlertForGroup($alert_namespace, $data);		
				
				die ($query->row['firstname'] . ' ' . $query->row['lastname'] . ' ('.$query->row['customer_id'].')');
				} else {
				
				$data = array(
				'type' => 'success',
				'text' => "Общий входящий звонок: <br /><br /><span style='font-size:16px;'>".$input['callerid']."</span>", 
				'entity_type' => 'inboundcall', 
				'entity_id' => 0
				);
				
				$mAlert->insertAlertForGroup($alert_namespace, $data);
				
				die ($input['callerid']);
			}
			
			} else {
			die ('no_checksum');
		}
		} elseif (isset($input['inboundto']) && $input['inboundto'] == 1) {
		$call_log->write(serialize($input));
		
		if (isset($input['callerid']) && isset($input['destination'])) {
			
			$phone = trim(preg_replace("([^0-9])", "", $input['callerid']));
			$dest = trim(preg_replace("([^0-9])", "", $input['destination']));
			
			$sql = "SELECT customer_id, firstname, lastname FROM `customer` WHERE ";		
			$sql .= "(TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."' )";
			$sql .= "OR (TRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(fax,' ',''), '+', ''), '-', ''), '(', ''), ')', '')) LIKE '%".$phone."')";
			$sql .= " LIMIT 1";
			
			$query = $db->query($sql);
			
			if ($query->row) {
				$customer_name = $query->row['firstname'] . ' ' . $query->row['lastname'] . ' ('.$query->row['customer_id'].')';
				$customer_id = $query->row['customer_id'];
				} else {
				$customer_name = $phone;
				$customer_id = 0;
			}
			
			$sql = "SELECT user_id FROM user WHERE internal_pbx_num = '" . $db->escape($dest) . "'";
			$query = $db->query($sql);
			
			if ($query->row) {
				
				$data = array(
				'type' => 'success',
				'text' => "Входящий звонок: <br /><br /><span style='font-size:16px;'>".$customer_name.'</span>', 
				'entity_type' => 'inboundcall', 
				'entity_id' => $customer_id
				);
				
				$mAlert->insertAlertForOne($query->row['user_id'], $data);					
			}
			
			} else {
			die ('no_checksum');
		}
		
		} elseif (isset($input['tomanager']) && $input['tomanager'] == 1) {
		$call_log->write(serialize($input));
		
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
			
			$query = $db->query($sql);					
			
			if ($query->row && $cache->get('sip_managers_online') && in_array($query->row['internal_pbx_num'], $cache->get('sip_managers_online'))) {
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
		
		$call_log->write(serialize($input));
		
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
				$query = $db->query($sql);
				
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
					$find_group = $db->query("SELECT alert_namespace FROM user_group WHERE sip_queue = '" . $db->escape($queue_id) . "' LIMIT 1");
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
						
						$mAlert->insertAlertForGroup($alert_namespace, $data);							
						
					}
					
					$customer_id = (int)$query->row['customer_id'];
					$name = $query->row['firstname'].' '.$query->row['lastname'];
					
					$sql = ("INSERT IGNORE INTO `customer_calls` SET 
					customer_id = '" .(int)$customer_id. "', 
					inbound = '" .(int)$inbound. "',
					customer_phone = '" . $db->escape($phone). "',
					length = 0,					
					date_end = NOW(), 
					comment = '',
					sip_queue = '" . $db->escape($queue_id) . "',
					internal_pbx_num = '" . $db->escape($internal) . "',
					manager_id = '" . (int)$manager_id . "',
					filelink = '',
					order_id = 0
					");
					
					$db->query($sql);
					$n = $db->getLastId();
					
					if ($n) {
						$comment = 'Пропущенный входящий звонок.';										
						
						$sql = ("INSERT IGNORE INTO `customer_history` 
						SET customer_id = '" . (int)$customer_id . "', 
						comment = '" . $db->escape($comment) . "', 
						call_id = '" . (int)$n . "',
						manager_id = '" . (int)$manager_id . "',
						date_added = NOW()");
						
						$db->query($sql);
					}
					
					
					} else {
					
					if ($real_inbound_call) {
						
						$data = array(
						'type' => 'warning',
						'text' => "Пропущенный звонок: <br /><br /><span style='font-size:16px;'>".$db->escape($phone).'</span>', 
						'entity_type' => 'missedcall', 
						'entity_id' => 0
						);
						
						$mAlert->insertAlertForGroup($alert_namespace, $data);				
						
						$customer_id = 0;
						$name = 'Неизвестно';
						
					}
				}		
				
				if ($real_inbound_call || $from_sendmissedcall) {
					
					$query = $db->query("INSERT INTO " . DB_PREFIX . "callback SET 
					name = '" . $db->escape($name)  . "', 
					comment_buyer = '" . $db->escape('Пропущенный звонок')  . "',
					email_buyer = '" . $db->escape('')  . "', 
					telephone = '" . $db->escape($phone) . "',
					customer_id = '". (int)$customer_id ."',
					sip_queue = '" . $db->escape($queue_id) . "',
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
				
				$query = $db->query($sql);
				
				if ($query->row && $query->row['customer_id']) {
					$customer_id = (int)$query->row['customer_id'];
					} else {
					$customer_id = 0;
				}
				
				//checking manager_id
				$sql = "SELECT user_id FROM user WHERE internal_pbx_num = '" . trim($db->escape($internal)) . "' LIMIT 1";
				$query = $db->query($sql);
				
				if ($query->row && $query->row['user_id']) {
					$manager_id = (int)$query->row['user_id'];
					} else {
					$manager_id = 0;
				}
				
				$sql = ("INSERT IGNORE INTO `customer_calls` SET 
				customer_id = '" .(int)$customer_id. "', 
				inbound = '" .(int)$inbound. "',
				customer_phone = '" . $db->escape($phone). "',
				length = 0,					
				date_end = NOW(), 
				comment = '', 
				sip_queue = '0',
				internal_pbx_num = '" . $db->escape($internal) . "',
				manager_id = '" . (int)$manager_id . "',
				filelink = '',
				order_id = 0
				");
				
				$db->query($sql);
				$n = $db->getLastId();
				
				if ($n) {
					$comment = 'Пропущенный исходящий звонок.';										
					
					$sql = ("INSERT IGNORE INTO `customer_history` 
					SET customer_id = '" . (int)$customer_id . "', 
					comment = '" . $db->escape($comment) . "', 
					call_id = '" . (int)$n . "',
					manager_id = '" . (int)$manager_id . "',
					date_added = NOW()");
					
					$db->query($sql);
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
		
		$call_log->write(serialize($input));	
		
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
			$query = $db->query($sql);
			
			if ($query->row && $query->row['customer_id']) {
				$customer_id = (int)$query->row['customer_id'];
				} else {
				$customer_id = 0;
			}
			
			if (mb_strlen($phone, 'UTF8') <= 4){
				$customer_id = 0;
			}
			
			//checking manager_id
			$sql = "SELECT user_id FROM user WHERE internal_pbx_num = '" . trim($db->escape($internal)) . "' LIMIT 1";
			$query = $db->query($sql);
			
			if ($query->row && $query->row['user_id']) {
				$manager_id = (int)$query->row['user_id'];
				} else {
				$manager_id = 0;
			}
			
			
			$sql = ("INSERT IGNORE INTO `customer_calls` SET 
			customer_id = '" .(int)$customer_id. "', 
			inbound = '" .(int)$inbound. "',
			customer_phone = '" . $db->escape($phone). "',
			length = '" . (int)$db->escape($input['dialedtime']) . "',
			date_end = NOW(), 
			comment = '', 
			internal_pbx_num = '" . $db->escape($internal) . "',
			manager_id = '" . (int)$manager_id . "',
			filelink = '" . $db->escape($input['recordname']) . "',
			order_id = 0
			");
			
			$db->query($sql);
			$n = $db->getLastId();
			
			if ($n) {
				if (!$input['dialedtime']){
					$comment = 'Пропущенный исходящий звонок.';						
					} else {
					$comment = 'Звонок, общение с покупателем.';						
				}
				$sql = ("INSERT IGNORE INTO `customer_history` 
				SET customer_id = '" . (int)$customer_id . "', 
				comment = '" . $db->escape($comment) . "', 
				call_id = '" . (int)$n . "',
				manager_id = '" . (int)$manager_id . "',
				date_added = NOW()");
				
				$db->query($sql);
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
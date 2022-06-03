<?php
	error_reporting(E_ALL);
	ini_set('memory_limit', '512M');
	define('VERSION', '1.0.0');
	
	require_once(dirname(__FILE__).'/../admin/config.slave.php');
	
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
	
	$registry = new Registry();
	$loader = new Loader($registry);
	$registry->set('load', $loader);
	$config = new Config();
	$registry->set('config', $config);
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$registry->set('db', $db);
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
	foreach ($query->rows as $setting) {
		if (!$setting['serialized']) {
			$config->set($setting['key'], $setting['value']);
			} else {
			$config->set($setting['key'], $setting['value']?unserialize($setting['value']):false);
		}
	}
	
	$log = new Log('odinass.slave.api.input.txt');
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
			echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		}
		
		if (true) {
			$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
		}
		
		return TRUE;
	}
	set_error_handler('error_handler');
	$request = new Request();
	$registry->set('request', $request);
	$response = new Response();
	$response->addHeader('Content-Type: text/html; charset=utf-8');
	$registry->set('response', $response); 
	$registry->set('session', new Session());
	$cache = new Cache();
	$registry->set('cache', $cache);
	$registry->set('document', new Document());
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
	if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == '1' || $_SERVER['HTTPS'])) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'))) {	
		define('IS_HTTPS', true);			
		} else {
		define('IS_HTTPS', false);
	}
	$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
	$language = new Language($languages[$config->get('config_admin_language')]['directory']);
	$language->load($languages[$config->get('config_admin_language')]['filename']);	
	$registry->set('language', $language);
	$registry->set('currency', new Currency($registry));
	$registry->set('weight', new Weight($registry));
	$registry->set('length', new Length($registry));
	$registry->set('user', new User($registry));
	$Bitrix24 = new Bitrix24($registry);
	$registry->set('Bitrix24', $Bitrix24);
	// Url
	$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);	
	$registry->set('url', $url);
	// smsQueue
	$smsQueue = new smsQueue($registry);
	$registry->set('smsQueue', $smsQueue);
	$controller = new Front($registry);
	
	header("Access-Control-Allow-Origin:*");
	header("Cache-Control: public");
	
	if (!(in_array($request->server['REMOTE_ADDR'], array('178.63.49.17', '37.57.184.228', '77.37.246.72', '144.76.42.244', '45.147.196.247', '185.41.249.201', '95.67.113.206', '31.43.104.37')))){
		die('not valid ip');
	}
	
	$input = array_merge($request->post, $request->get);
	$input = array_map("trim", $input);
	
	if (!isset($input['mode']) || !isset($input['type'])) {		
		die('no_input');	
	}
	
	
	if ($input['mode'] == 'product_list'){
		
		$params = array(
		'sku_list'				
		);
		
		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}
		
		if ($input['type'] == 'getFullProductsBySKU') {
			$params = array(
			'sku_list'		
			);
			
			if (!json_decode(html_entity_decode($input['sku_list'])) && !json_decode($input['sku_list'])){
				$constants = get_defined_constants(true);
				$json_errors = array();
				foreach ($constants["json"] as $name => $value) {
					if (!strncmp($name, "JSON_ERROR_", 11)) {
						$json_errors[$value] = $name;
					}
				}
				
				die($json_errors[json_last_error()]);
			}
			
			$data = json_decode(html_entity_decode($input['sku_list']), true);
			if (!$data){
				$data = json_decode($input['sku_list'], true);
			}
			
			$action = new Action('api/info1c/getFullProductsBySKU', array('sku_list' => $data));
			
		}
		
		
		} elseif ($input['mode'] == 'product'){
		
		$params = array(
		'product_id'				
		);
		
		foreach ($params as $param) {
			if (!isset($input[$param]) || !is_numeric($input[$param])){
				die("no $param");
			}
		}
		
		if ($input['type'] == 'getFullProduct'){
			$action = new Action('api/info1c/getFullProduct', array('product_id' => $input['product_id']));		
			
			} elseif ($input['type'] == 'getProductPrices') {
			$action = new Action('api/info1c/getProductPrices', array('product_id' => $input['product_id']));						
			
			} elseif ($input['type'] == 'getProductImage') {
			
			if (isset($input['param_width'])){
				$width = (int)$input['param_width'];
				} else {
				$width = 150;
			}
			
			if (isset($input['param_height'])){
				$height = (int)$input['param_height'];
				} else {
				$height = 150;
			}
			
			$action = new Action('api/info1c/getProductImage', array('product_id' => $input['product_id'], 'width' => $width, 'height' => $height));		
		}
		} elseif ($input['mode'] == 'waitlist'){
		if ($input['type'] == 'getFullWaitlist'){
			
			$action = new Action('api/info1c/getFullWaitlist');
		}
		
		} elseif ($input['mode'] == 'order'){		
		
		if ($input['type'] == 'getOrderSales'){
			$params = array(
			'order_id',
			'delivery_num'				
			);
			
			foreach ($params as $param) {
				if (!isset($input[$param])){
					die("no $param");
				}
			}
			
			if (isset($input['comment'])){
				$comment = (int)$input['comment'];
				} else {
				$comment = '';
			}
			
			$action = new Action('api/info1c/getOrderSales', array('order_id' => $input['order_id'], 'delivery_num' => $input['delivery_num']));			
		}	
		
		if ($input['type'] == 'getOrderCurrentStatus'){
			$params = array(
			'orders'		
			);
			
			foreach ($params as $param) {
				if (!isset($input[$param]) || (!explode(',', $input[$param]))){
					die("no $param");
				}
			}
			
			$action = new Action('api/info1c/getOrderCurrentStatus', array('order' => $input['orders']));
		}
		
		if ($input['type'] == 'getOrdersInCourierService'){
			
			$action = new Action('api/info1c/getOrdersInCourierService');
		}
	}
	
	
	if (isset($action)) {
		$controller->dispatch($action, new Action('error/not_found'));
		} else {
		die('no_mode_no_type');	
	}
	
	
	// Output
$response->output();	
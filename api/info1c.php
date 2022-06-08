<?php
error_reporting(E_ALL);
ini_set('memory_limit', '512M');
define('VERSION', '1.0.0');
define('IS_DEBUG', false);
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

$log = new Log('odinass_api_input.txt');
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
$language = new Language($languages[$config->get('config_admin_language')]['directory'], $registry);
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

		// Customer
$customer = new Customer($registry);
$registry->set('customer', new Customer($registry));

$controller = new Front($registry);

header("Access-Control-Allow-Origin:*");
header("Cache-Control: public");

	// Add new IPv4 for acs ess 
	// 46.4.103.135 By Ruslan.S 22.11.2020
if (!(in_array($request->server['REMOTE_ADDR'], array('8.40.107.104', '135.181.195.119', '91.218.192.226', '95.67.113.206', '46.4.103.135', '178.63.49.17', '77.37.246.72', '144.76.42.244', '45.147.196.247', '185.41.249.201', '46.219.229.172', '116.203.221.58')))){
	die('not valid ip');
}

$input = array_merge($request->post, $request->get);
//	$input = array_map("trim", $input);

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

	if ($input['type'] == 'describeProductFields'){
		$action = new Action('api/info1c/describeProductFields');	
	} 

	if ($input['type'] == 'editProductFields'){
		$params = array(
			'product_id',
			'fields'
		);


		foreach ($params as $param) {
			if (!isset($input[$param]) || empty($input[$param])){
				die("no $param");
			}
		}

		if (!json_decode(html_entity_decode($input['fields'])) && !json_decode($input['fields'])){
			$constants = get_defined_constants(true);
			$json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$json_errors[$value] = $name;
				}
			}

			die($json_errors[json_last_error()]);
		}

		$jsonFields = json_decode(html_entity_decode($input['fields']), true);
		if (!$jsonFields){
			$jsonFields = json_decode($input['fields'], true);
		}

		$jsonResultFields = array();
		foreach ($jsonFields as $field => $value){

			$forbidden_array = array(
				'product_id',
			);

			if (in_array($field, $forbidden_array)){
				die("You can not change $field in PRODUCT table");
			}

			$check = $db->query("SHOW FULL COLUMNS FROM `product` LIKE '" . $db->escape($field) . "'");

			if (!$check->num_rows){			
				die("no $field in PRODUCT table");
			}


			if (mb_stripos($check->row['Type'], 'int') !== false){

				if (!is_numeric($value)){
					die("invalid data type for $field, must be integer");
				}

				$type = 'int';
			}

			if (mb_stripos($check->row['Type'], 'varchar') !== false){
				$type = 'varchar';
			}

			if (mb_stripos($check->row['Type'], 'text') !== false){
				$type = 'varchar';
			}

			if (mb_stripos($check->row['Type'], 'decimal') !== false){

				if (!is_numeric($value)){
					die("invalid type for $field, must be float");
				}

				$type = 'decimal';
			}

			if (mb_stripos($check->row['Type'], 'datetime') !== false){

				if (date('Y-m-d H:i:s', strtotime($value)) != $value){
					die("invalid data type for $field, must be datetime Y-m-d H:i:s");
				}

				$type = 'datetime';
			}

			if (mb_stripos($check->row['Type'], 'date') !== false){

				if (date('Y-m-d', strtotime($value)) != $value){
					die("invalid data type for $field, must be date Y-m-d");
				}

				$type = 'date';
			}

			$jsonResultFields[] = array(
				'name' 	=> $check->row['Field'],
				'value'	=> $value,
				'type'	=> $type
			);
		}

		$action = new Action('api/info1c/editProductFields', array('product_id' => $input['product_id'], 'fields' => $jsonResultFields));	

	}

	if ($input['type'] == 'getFullProduct'){

		$params = array(
			'product_id'				
		);
		
		foreach ($params as $param) {
			if (!isset($input[$param]) || !is_numeric($input[$param])){
				die("no $param");
			}
		}

		$action = new Action('api/info1c/getFullProduct', array('product_id' => $input['product_id']));		
	} 

	if ($input['type'] == 'getProductPrices') {

		$params = array(
			'product_id'				
		);
		
		foreach ($params as $param) {
			if (!isset($input[$param]) || !is_numeric($input[$param])){
				die("no $param");
			}
		}

		$action = new Action('api/info1c/getProductPrices', array('product_id' => $input['product_id']));

	} 

	if ($input['type'] == 'setProductPrices') {

		$params = array(
			'product_id',
			'prices'
		);

		foreach ($params as $param) {
			if (!isset($input[$param]) || !$input[$param]){
				die("no $param");
			}
		}

		if (!json_decode(html_entity_decode($input['prices'])) && !json_decode($input['prices'])){
			$constants = get_defined_constants(true);
			$json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$json_errors[$value] = $name;
				}
			}

			die($json_errors[json_last_error()]);
		}

		$data = json_decode(html_entity_decode($input['prices']), true);
		if (!$data){
			$data = json_decode($input['prices'], true);
		}

		$action = new Action('api/info1c/setProductPrices', array('product_id' => $input['product_id'], 'prices' => $data));

	} 

	if ($input['type'] == 'getProductImage') {

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

	if ($input['type'] == 'updateProductField')  {

		$params = array(
			'product_id',
			'field',
			'value'
		);


		foreach ($params as $param) {
			if (!isset($input[$param]) || !$input[$param]){
				die("no $param");
			}
		}

		$action = new Action('api/info1c/updateProductField', array('product_id' => $input['product_id'], 'field' => $input['field'], 'value' => $input['value']));
	} 

	if ($input['type'] == 'updateProductEANExplicit')  {

		$params = array(
			'product_id',
			'ean',
			'sku',
			'user',
			'cleanup'
		);


		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}

		$action = new Action('api/info1c/updateProductEANExplicit', array('product_id' => $input['product_id'], 'ean' => $input['ean'], 'sku' => $input['sku'], 'user' => $input['user'], 'cleanup' => $input['cleanup']));


	}
} elseif ($input['mode'] == 'waitlist'){
	if ($input['type'] == 'getFullWaitlist'){

		$action = new Action('api/info1c/getFullWaitlist');
	}

} elseif ($input['mode'] == 'vouchers'){

	if ($input['type'] == 'getAllVouchersList'){

		$action = new Action('api/info1c/getAllVouchersList');

	}

} elseif ($input['mode'] == 'order'){

	if ($input['type'] == 'getOrderJSON'){

		$params = array(
			'order_id'
		);

		foreach ($params as $param) {
			if (!isset($input[$param]) || empty($input[$param])){
				die("no $param");
			}
		}


		$action = new Action('api/info1c/getOrderJSON', array('order_id' => $input['order_id']));	
	}

	if ($input['type'] == 'describeOrderFields'){
		$action = new Action('api/info1c/describeOrderFields');	
	}

	if ($input['type'] == 'addTTNHistory'){
		$params = array(
			'order_id',
			'ttn',
			'couriercode'
		);

		foreach ($params as $param) {
			if (!isset($input[$param]) || empty($input[$param])){
				die("no $param");
			}
		}

		$action = new Action('api/info1c/addTTNHistory', array('order_id' => $input['order_id'], 'ttn' => $input['ttn'], 'courierCode' => $input['couriercode']));	
	}

	if ($input['type'] == 'editOrderFields'){
		$params = array(
			'order_id',
			'fields'
		);


		foreach ($params as $param) {
			if (!isset($input[$param]) || empty($input[$param])){
				die("no $param");
			}
		}

		if (!json_decode(html_entity_decode($input['fields'])) && !json_decode($input['fields'])){
			$constants = get_defined_constants(true);
			$json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$json_errors[$value] = $name;
				}
			}

			die($json_errors[json_last_error()]);
		}

		$jsonFields = json_decode(html_entity_decode($input['fields']), true);
		if (!$jsonFields){
			$jsonFields = json_decode($input['fields'], true);
		}

		$jsonResultFields = array();
		foreach ($jsonFields as $field => $value){

			$forbidden_array = array(
				'order_id',
				'ttn'
			);

			if (in_array($field, $forbidden_array)){
				die("You can not change $field in ORDER table");
			}

			$check = $db->query("SHOW FULL COLUMNS FROM `order` LIKE '" . $db->escape($field) . "'");

			if (!$check->num_rows){			
				die("no $field in ORDER table");
			}

			if ($check->row['Field'] == 'courier_id'){
				$check->row['Type'] = 'varchar';
			}

			if (mb_stripos($check->row['Type'], 'int') !== false){

				if (!is_numeric($value)){
					die("invalid data type for $field, must be integer");
				}

				$type = 'int';
			}

			if (mb_stripos($check->row['Type'], 'varchar') !== false){
				$type = 'varchar';
			}

			if (mb_stripos($check->row['Type'], 'text') !== false){
				$type = 'varchar';
			}

			if (mb_stripos($check->row['Type'], 'decimal') !== false){

				if (!is_numeric($value)){
					die("invalid type for $field, must be float");
				}

				$type = 'decimal';
			}

			if (mb_stripos($check->row['Type'], 'datetime') !== false){

				if (date('Y-m-d H:i:s', strtotime($value)) != $value){
					die("invalid data type for $field, must be datetime Y-m-d H:i:s");
				}

				$type = 'datetime';
			}

			if (mb_stripos($check->row['Type'], 'date') !== false){

				if (date('Y-m-d', strtotime($value)) != $value){
					die("invalid data type for $field, must be date Y-m-d");
				}

				$type = 'date';
			}

			$jsonResultFields[] = array(
				'name' 	=> $check->row['Field'],
				'value'	=> $value,
				'type'	=> $type
			);
		}

		$action = new Action('api/info1c/editOrderFields', array('order_id' => $input['order_id'], 'fields' => $jsonResultFields));	

	}

	if ($input['type'] == 'addOrderHistory'){
		$params = array(
			'order_id',
			'order_status_id',
			'notify'
		);

		foreach ($params as $param) {
			if (!isset($input[$param]) || !is_numeric($input[$param])){
				die("no $param");
			}
		}

		if (!empty($input['comment'])){
			$comment = $input['comment'];
		} else {
			$comment = '';
		}

		if (!empty($input['notify'])){
			$notify = 1;
		} else {
			$notify = 0;
		}

		$action = new Action('api/info1c/addOrderHistory', array('order_id' => $input['order_id'], 'order_status_id' => $input['order_status_id'], $comment, $notify));			
	}	

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

	if ($input['type'] == 'updateOrdersInCourierService'){
		$params = array(
			'json_data'		
		);

		foreach ($params as $param) {
			if (!isset($input[$param]) || (!explode(',', $input[$param]))){
				die("no $param");
			}
		}

		if (!json_decode(html_entity_decode($input['json_data'])) && !json_decode($input['json_data'])){
			$constants = get_defined_constants(true);
			$json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$json_errors[$value] = $name;
				}
			}

			die($json_errors[json_last_error()]);
		}

		$data = json_decode(html_entity_decode($input['json_data']), true);
		if (!$data){
			$data = json_decode($input['json_data'], true);
		}


		$action = new Action('api/info1c/updateOrdersInCourierService', array('json_data' => $data));
	}

	if ($input['type'] == 'putOrderTrackingInfo'){
		$params = array(
			'json_data'		
		);

			//	$_log = new Log('odinass_tracker_info_pre.txt');
			//	$_log->write(serialize($input));

		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}

		if (!json_decode(html_entity_decode($input['json_data'])) && !json_decode($input['json_data'])){
			$constants = get_defined_constants(true);
			$json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$json_errors[$value] = $name;
				}
			}

			die($json_errors[json_last_error()]);
		}

		$data = json_decode(html_entity_decode($input['json_data']), true);
		if (!$data){
			$data = json_decode($input['json_data'], true);
		}

		$action = new Action('api/info1c/putOrderTrackingInfo', array('json_data' => $data));

	}

} elseif ($input['mode'] == 'transaction'){

	if ($input['type'] == 'transactionSync'){

		$params = array(
			'action',
			'transaction_data'					
		);
		

		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}

		if (!empty($input['action']) && in_array($input['action'], array('get','set'))){
			$action = $input['action'];
		} else {
			die('no action, action must be get or set');
		}

		if (!json_decode(html_entity_decode($input['transaction_data'])) && !json_decode($input['transaction_data'])){
			$constants = get_defined_constants(true);
			$json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$json_errors[$value] = $name;
				}
			}

			die($json_errors[json_last_error()]);
		}

		$data = json_decode(html_entity_decode($input['transaction_data']), true);
		if (!$data){
			$data = json_decode($input['transaction_data'], true);
		}

		if ($action == 'get'){
			if (empty($data['order_id']) && empty($data['customer_id'])){
				die('action is get, but no order_id or customer_id');
			}
		}


		if ($action == 'get'){
			$action = new Action('api/info1c/transactionSyncGet', array('transaction_data' => $data));
		} else {
			$action = new Action('api/info1c/transactionSyncSet', array('transaction_data' => $data));
		}



	}

	if ($input['type'] == 'legalpersonList'){
		
		
		
		
		$action = new Action('api/info1c/legalpersonList');
		
		
	}

} elseif ($input['mode'] == 'crutchproxy'){

	if ($input['type'] == 'passWebServiceProxy'){

		$params = array(
			'function',
			'param_name',
			'string_data'		
		);

		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}
		
		if (isset($input['function']) && !empty($input['function'])){
			$function = $input['function'];
		} else {
			die('no function');
		}
		
		if (isset($input['param_name']) && !empty($input['param_name'])){
			$param_name = $input['param_name'];
		} else {
			die('no param name');
		}
		
		if (isset($input['string_data'])){
			$string_data = $input['string_data'];
		} else {
			$string_data = '';
		}
		
		$action = new Action('api/info1c/passWebServiceProxy', array('function' => $function, 'param_name' => $param_name, 'string_data' => $string_data));
	}

} elseif ($input['mode'] == 'bitrix24bot'){

	if ($input['type'] == 'sendMessageToUser'){

		$params = array(
			'message_type',
			'user_id',
			'message'			
		);

		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}

		$action = new Action('api/bitrixbot/sendMessageToUser', array('data' => $input));

	}


	if ($input['type'] == 'sendMessageToGroup'){
		$params = array(
			'message_type',
			'group_id',
			'message'			
		);

		foreach ($params as $param) {
			if (!isset($input[$param])){
				die("no $param");
			}
		}

		$action = new Action('api/bitrixbot/sendMessageToGroup', array('data' => $input));

	}
}  elseif ($input['mode'] == 'getmanagerkpi'){

	$params = array(
		'info_type',
		'month',
		'year'
	);

	foreach ($params as $param) {
		if (!isset($input[$param])){
			die("no $param");
		}
	}

	$action = new Action('kp/salary/getManagerKPIFor1C', array('info_type' => $info_type, 'month' => $month, 'year' => $year));

} elseif ($input['mode'] == 'getbmreport'){

	$params = array(
		'date_from',
		'date_to'		
	);

	foreach ($params as $param) {
		if (!isset($input[$param])){
			die("no $param");
		}
	}

	$action = new Action('kp/salary/getBrandManagerReport', array('data' => $input));

}


if (isset($action)) {
	$controller->dispatch($action, new Action('error/not_found'));
} else {
	die('no_mode_no_type');	
}


	// Output
$response->output();	

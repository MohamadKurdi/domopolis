<?php

ini_set('session.name', ini_get('session.name') . 'A');

define('VERSION', '1.5.6.4');
define('IS_HTTPS', true);
define('IS_ADMIN', true);
header('X-ENGINE-ENTRANCE: INDEX-ADMIN');

require_once(dirname(__FILE__) . '/../system/jsonconfig.php');

$loaderConfig = loadJsonConfig('loader');
if (!empty($loaderConfig['preload'])){
	foreach ($loaderConfig['preload'] as $preloadFile){
		require_once(dirname(__FILE__) . '/../' . $preloadFile . '.php');
	}
}

$FPCTimer = new \hobotix\FPCTimer();

$httpHOST 			= str_replace('www.', '', $_SERVER['HTTP_HOST']);
$configFiles 		= loadJsonConfig('configs');
$domainRedirects 	= loadJsonConfig('domainredirect');

if (isset($domainRedirects[$httpHOST])){		
	$newLocation = 'https://' . $domainRedirects[$httpHOST] . $_SERVER['REQUEST_URI'];
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: " . $newLocation); 
	exit(); 
}

if (isset($configFiles[$httpHOST])){		
	if (file_exists($configFiles[$httpHOST])) {
		$configFile = $configFiles[$httpHOST];
		require_once($configFiles[$httpHOST]);
	} else {
		die ('no config file!');
	}

} else {
	die ('ho config file assigned to host');
}	

if ($httpHOST != parse_url(HTTPS_CATALOG, PHP_URL_HOST)){
	die('sorry');
}

require_once(DIR_SYSTEM . '../vendor/autoload.php');

require_once(DIR_SYSTEM . 'startup.php');	
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');
require_once(DIR_SYSTEM . 'library/template.php');
require_once(DIR_SYSTEM . 'library/hobotix/Bitrix24.php');
require_once(DIR_SYSTEM . 'library/hobotix/SmsQueue.php');
require_once(DIR_SYSTEM . 'library/hobotix/PushQueue.php');
require_once(DIR_SYSTEM . 'library/hobotix/mAlert.php');
require_once(DIR_SYSTEM . 'library/hobotix/ShortAlias.php');
require_once(DIR_SYSTEM . 'library/hobotix/SmsAdaptor.php');
require_once(DIR_SYSTEM . 'library/hobotix/CustomerExtended.php');
require_once(DIR_SYSTEM . 'library/hobotix/SessionDBHandler.php');
require_once(DIR_SYSTEM . 'library/hobotix/PageCache.php');
require_once(DIR_SYSTEM . 'library/hobotix/CourierServices.php');
require_once(DIR_SYSTEM . 'library/hobotix/EmailBlackList.php');
require_once(DIR_SYSTEM . 'library/hobotix/RainforestAmazon.php');
require_once(DIR_SYSTEM . 'library/hobotix/PricevaAdaptor.php');
require_once(DIR_SYSTEM . 'library/hobotix/OpenAIAdaptor.php');
require_once(DIR_SYSTEM . 'library/hobotix/SimpleProcess.php');
require_once(DIR_SYSTEM . 'library/hobotix/TranslateAdaptor.php');
require_once(DIR_SYSTEM . 'library/hobotix/CheckBoxUA.php');		
require_once(DIR_SYSTEM . 'library/hobotix/Fiscalisation.php');
require_once(DIR_SYSTEM . 'library/hobotix/SupplierAdaptor.php');
require_once(DIR_SYSTEM . 'library/hobotix/QRCodeExtender.php');

$registry 	= new Registry();
$loader 	= new Loader($registry);
$registry->set('load', $loader);

$config 	= new Config();
$registry->set('config', $config);

$log 		= new Log('php-errors-admin.log');
$registry->set('log', $log);

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', 			$db);
$registry->set('dbmain', 		$db);
$registry->set('current_db', 	'dbmain');

if (defined('DB_CONTENT_SYNC') && DB_CONTENT_SYNC){
	$dbcs = new DB(DB_CONTENT_SYNC_DRIVER, DB_CONTENT_SYNC_HOSTNAME, DB_CONTENT_SYNC_USERNAME, DB_CONTENT_SYNC_PASSWORD, DB_CONTENT_SYNC_DATABASE);
	$registry->set('dbcs', $dbcs);

	$syncConfig = loadJsonConfig('sync');
	if (!empty($syncConfig['sync'])){
		$registry->set('sync', $syncConfig['sync']);
	}
} else {
	$registry->set('dbcs', false);
}

$query = $db->query("SELECT * FROM setting WHERE store_id = '0'");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {		
		$config->set($setting['key'], $setting['value']?unserialize($setting['value']):false);
	}
}

$configFilesPrefix = '';
if (count($configFileExploded = explode('.', $configFile)) == 3){
	if (mb_strlen($configFileExploded[1]) == 2){
		$configFilesPrefix = trim($configFileExploded[1]);
	}
}
$registry->get('config')->set('config_config_file_prefix', $configFilesPrefix);

$request = new Request();
$registry->set('request', $request);

$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

$cache = new Cache(CACHE_DRIVER);
$registry->set('cache', $cache); 

$registry->set('PageCache', new \hobotix\PageCache(false)); 

if ($registry->get('config')->get('config_seo_url_from_id')){
	$short_url_mapping = loadJsonConfig('shorturlmap');
	$short_uri_queries = $short_uri_keywords = [];

	if (is_array($short_url_mapping)){
		foreach ($short_url_mapping as $query => $keyword){
			$short_uri_queries[$query] = $keyword;
			$short_uri_keywords[$keyword] = $query;
		}
	}

	$registry->set('short_uri_queries', $short_uri_queries);
	$registry->set('short_uri_keywords', $short_uri_keywords);
}

$registry->set('shippingmethods', loadJsonConfig('shippingmethods'));

$languages = [];
$languages_id_code_mapping = [];
$query = $registry->get('db')->query("SELECT * FROM `language` WHERE status = '1'"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
	$languages_id_code_mapping[$result['language_id']] = $result['code'];
}

$registry->set('languages', $languages);
$registry->set('languages_all', $languages);
$registry->set('languages_id_code_mapping', $languages_id_code_mapping);
$registry->get('config')->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
$registry->get('config')->set('config_rainforest_source_language_id', $languages[$registry->get('config')->get('config_rainforest_source_language')]['language_id']);

$language = new Language($languages[$config->get('config_admin_language')]['directory'], $registry);
$language->load($languages[$config->get('config_admin_language')]['filename']);	
$registry->set('language', $language);

$sorts = loadJsonConfig('sorts');
if (!empty($sorts['sorts'])){
	$registry->set('sorts', $sorts['sorts']);
}

if (!empty($sorts['sorts_available'])){
	$registry->set('sorts_available', $sorts['sorts_available']);
}

if ($registry->get('config')->get('config_sort_default')){
	$registry->get('config')->set('sort_default', $registry->get('config')->get('config_sort_default'));
} elseif (!empty($sorts['sort_default'])){
	$registry->get('config')->set('sort_default', $sorts['sort_default']);
}

if ($registry->get('config')->get('config_order_default')){
	$registry->get('config')->set('order_default', $registry->get('config')->get('config_order_default'));
} elseif (!empty($sorts['order_default'])){
	$registry->get('config')->set('order_default', $sorts['order_default']);
}

$registry->set('customer_group_id', $registry->get('config')->get('config_customer_group_id'));

$registry->set('url',  				new Url(HTTPS_SERVER, $registry));
$registry->set('session', 			new Session()); 
$registry->set('mobileDetect', 		new \Detection\MobileDetect);
$registry->set('document', 			new Document());
$registry->set('templateLib',		new Template());
$registry->set('currency', 			new Currency($registry));
$registry->set('smsQueue', 			new hobotix\smsQueue($registry)); 	
$registry->set('smsAdaptor', 		new hobotix\SmsAdaptor($registry));
$registry->set('customer', 			new hobotix\CustomerExtended($registry));			
$registry->set('weight', 			new Weight($registry));
$registry->set('length', 			new Length($registry));
$registry->set('user', 				new User($registry));
$registry->set('cart', 				new Cart($registry));
$registry->set('encryption', 		new Encryption($registry->get('config')->get('config_encryption')));
$registry->set('Bitrix24', 			new hobotix\Bitrix24($registry));
$registry->set('pushQueue', 		new hobotix\pushQueue($registry));
$registry->set('shortAlias', 		new hobotix\shortAlias($registry));
$registry->set('simpleProcess', 	new hobotix\simpleProcess());
$registry->set('mAlert', 			new hobotix\mAlert($registry));
$registry->set('courierServices', 	new hobotix\CourierServices($registry));
$registry->set('emailBlackList', 	new hobotix\EmailBlackList($registry));
$registry->set('openaiAdaptor', 	new hobotix\OpenAIAdaptor($registry));
$registry->set('translateAdaptor', 	new hobotix\TranslateAdaptor($registry));
$registry->set('pricevaAdaptor', 	new hobotix\PricevaAdaptor($registry));
$registry->set('checkBoxUA', 		new hobotix\CheckBoxUA($registry));
$registry->set('Fiscalisation',		new hobotix\Fiscalisation($registry));
$registry->set('supplierAdaptor',	new hobotix\SupplierAdaptor($registry));

if (!$registry->get('config')->get('config_enable_amazon_specific_modes')){
	$registry->set('bypass_rainforest_caches_and_settings', true);
}
$registry->set('rainforestAmazon', 	new hobotix\RainforestAmazon($registry));

$controller = new Front($registry);

$controller->addPreAction(new Action('common/home/login'));
$controller->addPreAction(new Action('common/home/permission'));

/* We can need this to get front URLs in admin while getting some ajax templates */
if (!empty($request->request['use_seo_urls'])){
	$controller->addPreAction(new Action('common/seo_pro'));
}

if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

$controller->dispatch($action, new Action('error/not_found'));

$response->output();
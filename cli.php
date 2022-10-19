#!/usr/bin/env php7.4

<?php			
define('VERSION', '1.5.6.4');
define('IS_HTTPS', true);
define('CLI_SESSION', true);
ini_set('memory_limit', '4G');

require_once(dirname(__FILE__) . '/system/jsonconfig.php');

	//Загрузка скриптов, в которых еще не определены константы конфига
$loaderConfig 	= loadJsonConfig('loader');
$stores 		= loadJsonConfig('stores');
$configs 		= loadJsonConfig('configs');

if (!empty($loaderConfig['preload'])){
	foreach ($loaderConfig['preload'] as $preloadFile){
		require_once(dirname(__FILE__) . $preloadFile . '.php');
	}
}

if (!is_cli()){
	echoLine('THIS IS CLI ONLY POINT');
	die();
}

echoLine('[CLI] Йобані очі, це ж ми в CLI. Версия PHP: ' . phpversion() . ', время ' . date('Y-m-d H:i:s'));	

	//Первый параметр: admin, catalog, для выбора приложения
if (!isset($argv[1])){
	echoLine('[CLI] Нет первого параметра, должен быть или admin, или catalog');
	die();
}

if (!isset($argv[2])){
	echoLine('[CLI] Нет второго параметра, должен быть конфигурационный файл');
	die();
}

if (!isset($argv[3])){
	echoLine('[CLI] Нет третьего параметра, должен быть исполняемый контроллер');
	die();
}

$application = trim($argv[1]);
if (!in_array($application, ['admin', 'catalog'])){
	echoLine('[CLI] Первый параметр должен быть или admin, или catalog');
	die();
}

if ($application == 'admin'){
	$applicationLocation = dirname(__FILE__) . '/admin/';
} else {
	$applicationLocation = dirname(__FILE__) . '/';
}

$configFile = trim($argv[2]) . '.php';	
$existentConfigFiles = glob($applicationLocation . 'config*.php');
if (!in_array($applicationLocation . $configFile, $existentConfigFiles)){
	echoLine('[CLI] Доступные файлы конфигураций: ' . implode(', ', $existentConfigFiles));
	die();
}

$route = trim($argv[3]);

$functionArguments = [];
$allArguments	   = [];
for ($i=4; $i<=20; $i++){
	if (isset($argv[$i])){
		$argv[$i] = trim($argv[$i]);

		if (strpos($argv[$i], 'store_id=') !== false){
			$store_id = getCliParamValue($argv[$i]);
			$allArguments[] = $argv[$i];
			echoLine('[CLI] Мы работаем в магазине: ' . $store_id);
		} elseif (strpos($argv[$i], 'language_code=') !== false){
			$language_code = getCliParamValue($argv[$i]);
			$allArguments[] = $argv[$i];
			echoLine('[CLI] Мы работаем с языком: ' . $language_code);
		} else {
			$functionArguments[] = trim($argv[$i]);
			$allArguments[] = trim($argv[$i]);
		}
	}
}

	//Вроде всё окей
echoLine('[CLI] Запускаем ' . $route . ' в приложении ' . $application . ' с конфигом ' . $configFile);
echoLine('[CLI] Заданы параметры: ' . implode(', ', $functionArguments));

require_once($applicationLocation . $configFile);	

if (!empty($loaderConfig['startup'])){
	foreach ($loaderConfig['startup'] as $startupFile){
		require_once(DIR_SYSTEM . $startupFile . '.php');
	}
}

	//Из директории library, с уже определенной константой DIR_SYSTEM
if (!empty($loaderConfig['libraries'])){
	foreach ($loaderConfig['libraries'] as $libraryFile){
		require_once(DIR_SYSTEM . 'library/' . $libraryFile . '.php');
	}		
}

   //Пейджкеш
$PageCache = new PageCache();

$registry = new Registry();
$registry->set('load', 		new Loader($registry));	
$registry->set('config', 	new Config()); $config = $registry->get('config');
$registry->set('db', 		new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));	
$registry->set('cache', 	new Cache());
$registry->set('request', 	new Request());
$registry->set('session', 	new Session($registry)); $session = $registry->get('session');
$registry->set('log', 		new Log('php-errors-cli.log'));

if (empty($store_id)){
	$store_id = 0;
}

$registry->get('config')->set('config_store_id', $store_id);	
$settings = $registry->get('cache')->get('settings.structure'.(int)$registry->get('config')->get('config_store_id'));
if (!$settings) {	
	$query = $registry->get('db')->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$registry->get('config')->get('config_store_id') . "' ORDER BY store_id ASC");		
	$settings = $query->rows;
	$registry->get('cache')->set('settings.structure'.(int)$registry->get('config')->get('config_store_id'), $settings);
}

foreach ($settings as $setting) {
	if (!$setting['serialized']) {
		$registry->get('config')->set($setting['key'], $setting['value']);
	} else {
		$registry->get('config')->set($setting['key'], $setting['value']?unserialize($setting['value']):false);
	}
}	

if (!$store_id) {
	$registry->get('config')->set('config_url', 				HTTP_SERVER);
	$registry->get('config')->set('config_ssl', 				HTTPS_SERVER);			
	$registry->get('config')->set('config_img_url', 			HTTP_IMG_SERVER);
	$registry->get('config')->set('config_img_ssl', 			HTTPS_IMG_SERVER);		
	$registry->get('config')->set('config_img_urls', 			HTTP_IMG_SERVERS);
	$registry->get('config')->set('config_img_ssls', 			HTTPS_IMG_SERVERS);			
	$registry->get('config')->set('config_img_server_count', 	HTTPS_IMG_SERVERS_COUNT);			
	$registry->get('config')->set('config_static_subdomain', 	HTTPS_STATIC_SUBDOMAIN);
}

$registry->set('url', new Url($registry->get('config')->get('config_ssl'), $registry));

	//Stores main language config to registry (need for ElasticSearch and some other)
$stores_to_main_language_mapping = [];
$query = $registry->get('db')->query("SELECT store_id, value FROM `setting` WHERE `key` = 'config_language'");
foreach ($query->rows as $result) {
	$stores_to_main_language_mapping[$result['store_id']] = $result['value'];
}

$registry->set('stores_to_main_language_mapping', $stores_to_main_language_mapping);			

	//Определение языка
$languages = $languages_front = $languages_all = [];
$languages_id_code_mapping = [];
$query = $registry->get('db')->query("SELECT * FROM `language` WHERE status = '1'"); 

foreach ($query->rows as $result) {
	$languages_all[$result['code']] = $result;	
	$languages[$result['code']] = $result;
	
	if ($result['front']){
		$languages_front[$result['code']] = $result;
		$languages_id_code_mapping[$result['language_id']] = $result['code'];
	}
}

	//ALL LANGUAGES TO REGISTRY
$registry->set('languages', $languages);
$registry->set('languages_front', $languages_front);
$registry->set('languages_id_code_mapping', $languages_id_code_mapping);
$registry->get('config')->set('config_supported_languages', [$registry->get('config')->get('config_language'), $registry->get('config')->get('config_second_language')]);
$registry->get('config')->set('config_rainforest_source_language_id', $languages_all[$registry->get('config')->get('config_rainforest_source_language')]['language_id']);	

if (!empty($language_code)){
	$registry->get('config')->set('config_language', $language_code);
}

$registry->get('config')->set('config_language', 			$languages[$registry->get('config')->get('config_language')]['code']);
$registry->get('config')->set('config_language_id', 		$languages[$registry->get('config')->get('config_language')]['language_id']);	
$registry->get('config')->set('config_language_hreflang', 	$languages[$registry->get('config')->get('config_language')]['hreflang']);

	//RNF MAIN LANGUAGE
$registry->get('config')->set('config_rainforest_source_language_id', $languages_all[$registry->get('config')->get('config_rainforest_source_language')]['language_id']);

	//Stores and languages mapping to registry
$stores = [0];
$query = $registry->get('db')->query("SELECT * FROM store WHERE 1");
foreach ($query->rows as $store){
	$stores[] = $store['store_id'];
}

	//All stores to registry
$registry->set('stores', $stores);

$supported_language_codes 	= [];
$supported_language_ids		= [];
foreach ($stores as $store_id){
	$supported_language_codes[$store_id] 	= [];
	$supported_language_ids[$store_id] 		= [];
	$query = $registry->get('db')->query("SELECT `key`, `value` FROM setting WHERE (`key` = 'config_language' OR `key` = 'config_second_language') AND store_id = '" . (int)$store_id . "'");

	foreach ($query->rows as $row){
		if ($row['value']){
			$supported_language_codes[$store_id][] 	= $row['value'];
			$supported_language_ids[$store_id][] 	= $languages_all[$row['value']]['language_id'];
		}

		if ($row['key'] == 'config_second_language' && $row['value']){
			$registry->set('excluded_language_id', 		$languages_all[$row['value']]['language_id']);
			$registry->set('excluded_language_code', 	$row['value']);
		}
	}
}	

$registry->set('supported_language_codes', $supported_language_codes);
$registry->set('supported_language_ids', $supported_language_ids);

	//Setting Language
$language = new Language($languages[$registry->get('config')->get('config_language')]['directory'], $registry);
$language->load($languages[$registry->get('config')->get('config_language')]['filename']);	
$registry->set('language', $language);


	//Сортировки
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


	//Библиотека респонса
$response = new Response($registry);
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($registry->get('config')->get('config_compression'));
$registry->set('response', $response);	

	//Остальные библиотеки	
$registry->set('document', 			new Document()); 				
$registry->set('emailBlackList', 	new hobotix\EmailBlackList($registry));
$registry->set('elasticSearch', 	new ElasticSearch($registry));		
$registry->set('mAlert', 			new mAlert($registry));
$registry->set('smsQueue', 			new smsQueue($registry));	
$registry->set('shortAlias', 		new shortAlias($registry));	
$registry->set('Bitrix24', 			new Bitrix24($registry));
$registry->set('customer', 			new Customer($registry));
$registry->set('affiliate', 		new Affiliate($registry));
$registry->set('currency', 			new Currency($registry));
$registry->set('tax', 				new Tax($registry));
$registry->set('weight', 			new Weight($registry));
$registry->set('length', 			new Length($registry));
$registry->set('cart', 				new Cart($registry));
$registry->set('user', 				new User($registry));
$registry->set('openbay', 			new Openbay($registry));
$registry->set('encryption', 		new Encryption($registry->get('config')->get('config_encryption')));	
$registry->set('yandexTranslator', 	new hobotix\YandexTranslator($registry));
$registry->set('rainforestAmazon', 	new hobotix\RainforestAmazon($registry));
$registry->set('pricevaAdaptor', 	new hobotix\PricevaAdaptor($registry));
$registry->set('CourierServices', 	new CourierServices());
$registry->set('simpleProcess', 	new hobotix\simpleProcess(['route' => $route, 'config' => $configFile, 'args' => $allArguments]));

$registry->set('customer_group_id', (int)$registry->get('config')->get('config_customer_group_id'));

	//AMAZON_CONFIG FROM JSON. Очень важная настройка, в БД какого-то чёрта иногда сбоит
$amazonConfig = loadJsonConfig('amazon');

if (!empty($amazonConfig['domain_1'])){
	$registry->get('config')->set('config_rainforest_api_domain_1', $amazonConfig['domain_1']);
}

if (!empty($amazonConfig['zipcode_1'])){			
	$registry->get('config')->set('config_rainforest_api_zipcode_1', $amazonConfig['zipcode_1']);
}

$controller = new Front($registry);

if ($application != 'admin'){
	$controller->addPreAction(new Action('common/seo_pro'));
}

$registry->get('simpleProcess')->startProcess();

if ($functionArguments){
	$action = new Action($route, $functionArguments);
} else {
	$action = new Action($route);
}

if (isset($action)){
	if ($action->getFile()){
		echoLine('[CLI] Action файл найден: ' . $action->getFile());
		$controller->dispatch($action, new Action('kp/errorreport/error'));
	} else {
		$registry->get('simpleProcess')->dropProcess();
		echoLine('[CLI] Action файл не найден, це пізда');			
	}	
} else {
	$registry->get('simpleProcess')->dropProcess();
	echoLine('[CLI] Action не определена, це пізда');
}

$registry->get('simpleProcess')->stopProcess();
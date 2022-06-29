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
	for ($i=4; $i<=20; $i++){
		if (isset($argv[$i])){
			$argv[$i] = trim($argv[$i]);

			if (strpos($argv[$i], 'store_id=') !== false){
				$store_id = getCliParamValue($argv[$i]);
				echoLine('[CLI] Мы работаем в магазине: ' . $store_id);
			} elseif (strpos($argv[$i], 'language_code=') !== false){
				$language_code = getCliParamValue($argv[$i]);
				echoLine('[CLI] Мы работаем с языком: ' . $language_code);
			} else {
				$functionArguments[] = trim($argv[$i]);
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

	$registry = new Registry();
	$registry->set('load', 		new Loader($registry));	
	$registry->set('config', 	new Config()); $config = $registry->get('config');
	$registry->set('db', 		new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));	
	$registry->set('bcache', 	new BCache($registry));		
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

	$registry->set('url', new Url($registry->get('config')->get('config_url'), $registry->get('config')->get('config_ssl')));				
				
	//Определение языка
	$languages = array();
	$query = $registry->get('db')->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'"); 

	foreach ($query->rows as $result) {
		$languages[$result['code']] = $result;
	}	

	//ALL LANGUAGES TO REGISTRY
	$registry->set('languages', $languages);
	$registry->get('config')->set('config_supported_languages', [$registry->get('config')->get('config_language'), $registry->get('config')->get('config_second_language')]);

	if (!empty($language_code)){
		$registry->get('config')->set('config_language', $language_code);
	}

	$registry->get('config')->set('config_language', 			$languages[$registry->get('config')->get('config_language')]['code']);
	$registry->get('config')->set('config_language_id', 		$languages[$registry->get('config')->get('config_language')]['language_id']);	
	$registry->get('config')->set('config_language_hreflang', 	$languages[$registry->get('config')->get('config_language')]['hreflang']);

	//RNF MAIN LANGUAGE
	$registry->get('config')->set('config_rainforest_source_language_id', $languages[$registry->get('config')->get('config_rainforest_source_language')]['language_id']);
	
	$language = new Language($languages[$registry->get('config')->get('config_language')]['directory'], $registry);
	$language->load($languages[$registry->get('config')->get('config_language')]['filename']);	
	$registry->set('language', $language);

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
	$registry->set('simpleProcess', 	new hobotix\simpleProcess(['route' => $route, 'config' => $configFile, 'args' => $functionArguments]));

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
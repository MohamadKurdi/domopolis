<?php			
	define('VERSION', '1.5.6.4');
	define('IS_HTTPS', true);
	header('X-ENGINE-ENTRANCE: INDEX'); 

	require_once(dirname(__FILE__) . '/system/jsonconfig.php');

	$apisConfig = loadJsonConfig('api');

	if (!empty($apisConfig['routes'])){
		if (!empty($_GET['_route_']) && in_array($_GET['_route_'], $apisConfig['routes'])){
			header('HTTP/1.1 403 Forbidden');
			die('NOT AVAILABLE FROM MAIN ROUTER');
		}
	}

	//Загрузка скриптов, в которых еще не определены константы конфига
	$loaderConfig = loadJsonConfig('loader');

	if (!empty($loaderConfig['preload'])){
		foreach ($loaderConfig['preload'] as $preloadFile){
			require_once(dirname(__FILE__) . $preloadFile . '.php');
		}
	}

	$FPCTimer = new FPCTimer();
	
	//find http host
	$httpHOST 			= str_replace('www.', '', $_SERVER['HTTP_HOST']);
	$storesConfig		= loadJsonConfig('stores');
	$configFiles 		= loadJsonConfig('configs');
	$domainRedirects 	= loadJsonConfig('domainredirect');
	
	
	//Редиректы доменов, из конфига domainredirect
	if (isset($domainRedirects[$httpHOST])){		
		$newLocation = 'https://' . $domainRedirects[$httpHOST] . $_SERVER['REQUEST_URI'];
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: " . $newLocation); 
		exit(); 
	}
	
	//Конфигурационный файл	основной выбор с переназначением
	$currentConfigFile = false;
	if (isset($configFiles[$httpHOST])){
		if (file_exists($configFiles[$httpHOST])) {
			$currentConfigFile = ($configFiles[$httpHOST]);
			} else {
			die ('no config file!');
		}	
		} else {
		die ('ho config file assigned to host');
	}
	
	//Перезагрузка конфигурационного файла для определенных AJAX контроллеров
	//!!!ЗДЕСЬ НЕ УЧТЕНА ПРИВЯЗКА К ДОМЕНУ, это TODO
	if (thisIsAjax() && !empty($_GET['route'])){
		$dbRouter = loadJsonConfig('dbrouting');
		if (!empty($dbRouter[$_GET['route']]) && file_exists(dirname(__FILE__) . '/' . $dbRouter[$_GET['route']])){
			$currentConfigFile = $dbRouter[$_GET['route']];
		}		
	}
	
	//ЗАГРУЗКА ОСНОВНОГО КОНФИГ-ФАЙЛА
	//header('X-CONFIG-USED: ' . $currentConfigFile);
	require_once($currentConfigFile);
	
	if (isset($storesConfig[$httpHOST])){
		$store_id = $storesConfig[$httpHOST];	
		} else {
		die ('we do not serve this shit');
	}
	
	//Пейджкеш		
	$PageCache = new PageCache();	
	if ($PageCache->validateOther() && $PageCache->validateIfToCache() && $output = $PageCache->getCache()){
		if (!headers_sent()) {
			header('Content-Type: text/html; charset=utf-8');
		}
		
		header('X-FPC-MODE: TRUE');
		header('X-FPC-TIME: ' . $FPCTimer->getTime());
		
		echo $output;		
		exit();
	}		

	//Загрузка
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
	$registry->set('log', 		new Log('php-errors-catalog.log'));
	
	
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


	if ($registry->get('config')->get('config_no_access_enable')){
		if (!defined('ADMIN_SESSION_DETECTED') || !ADMIN_SESSION_DETECTED){
			$ipsConfig  = loadJsonConfig('ips');

			if (!in_array($_SERVER['REMOTE_ADDR'], $ipsConfig['whitelist'])){
				header('HTTP/1.1 403 No Access Enabled');
				die('RESOURCE IN DEVELOPMENT');
			}
		}
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
	
	//FROM URL
	if ($registry->get('config')->get('config_second_language')){					
		$language_from_url = explode("/", $registry->get('request')->server['REQUEST_URI']);
		
		$code_from_url = $registry->get('config')->get('config_language');
		foreach($language_from_url as $lang){			
			unset($value);
			$break = false;
			foreach ($languages as $key => $value) {
				if ($value['urlcode'] && $value['urlcode'] == $lang){
					$code_from_url = $key;
					$break = true;
					break;
				}
			}
			if ($break) break;
		}		
	}		
	
	if (thisIsAjax($registry->get('request')) || thisIsUnroutedURI($registry->get('request'))){
		$code_from_url = false;
	}
	
	$detect = '';
	
	if (isset($registry->get('request')->server['HTTP_ACCEPT_LANGUAGE']) && $registry->get('request')->server['HTTP_ACCEPT_LANGUAGE']) { 
		$browser_languages = explode(',', $registry->get('request')->server['HTTP_ACCEPT_LANGUAGE']);
		
		foreach ($browser_languages as $browser_language) {
			foreach ($languages as $key => $value) {
				if ($value['status']) {
					$locale = explode(',', $value['locale']);
					
					if (in_array($browser_language, $locale)) {
						$detect = $key;
					}
				}
			}
		}
	}
	
	if (!empty($code_from_url) && array_key_exists($code_from_url, $languages)){
		$code = $code_from_url;
		} elseif (isset($registry->get('session')->data['language']) && array_key_exists($registry->get('session')->data['language'], $languages) && $languages[$registry->get('session')->data['language']]['status']) {
		$code = $registry->get('session')->data['language'];
		} elseif (isset($registry->get('request')->cookie['language']) && array_key_exists($registry->get('request')->cookie['language'], $languages) && $languages[$registry->get('request')->cookie['language']]['status']) {
		$code = $registry->get('request')->cookie['language'];
		} elseif ($detect) {
		$code = $detect;
		} else {
		$code = $registry->get('config')->get('config_language');
	}
	
	
	if (!$registry->get('config')->get('config_second_language')){
		$code = $registry->get('config')->get('config_language');
	}
	
	if (!isset($registry->get('session')->data['language']) || ($registry->get('session')->data['language'] != $code && !doNotSetLanguageCookieSession($registry->get('request')))) {
		$registry->get('session')->data['language'] = $code;
	}
	
	if (!isset($registry->get('request')->cookie['language']) || ($registry->get('request')->cookie['language'] != $code && !doNotSetLanguageCookieSession($registry->get('request')))) {	  
		setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $registry->get('request')->server['HTTP_HOST']);
	}			
	
	if ($registry->get('config')->get('config_second_language') && $code == $registry->get('config')->get('config_second_language')){
		define('GOOGLE_ID_ENDFIX', '-' . $languages[$code]['language_id']);
		} else {
		define('GOOGLE_ID_ENDFIX', '');
	}
	
	if (thisIsAjax($registry->get('request'))){
		define('IS_AJAX_REQUEST', true);
		} else {
		define('IS_AJAX_REQUEST', false);
	}
	
	$registry->get('config')->set('config_language_id', 		$languages[$code]['language_id']);
	$registry->get('config')->set('config_language', 			$languages[$code]['code']);
	$registry->get('config')->set('config_language_hreflang', 	$languages[$code]['hreflang']);
	
	if (!($registry->get('config')->get('config_warehouse_identifier'))){		
		$registry->get('config')->set('config_warehouse_identifier', 'quantity_stock');
	}
	
	//Язык	
	$language = new Language($languages[$code]['directory']);
	$language->load($languages[$code]['filename']);	
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
	$registry->set('openbay', 			new Openbay($registry));
	$registry->set('encryption', 		new Encryption($registry->get('config')->get('config_encryption')));	


	if ($registry->get('customer')->getTracking()) {
		setcookie('tracking', $registry->get('customer')->getTracking(), time() + 3600 * 24 * 1000, '/');
		} elseif (isset($registry->get('request')->get['tracking'])) {
		setcookie('tracking', $registry->get('request')->get['tracking'], time() + 3600 * 24 * 1000, '/');
	}

	if (isset($registry->get('request')->get['coupon']) && $registry->get('request')->get['coupon']){
		if (!isset($registry->get('session')->data['coupon'])){
			$registry->get('session')->data['coupon'] = trim($registry->get('request')->get['coupon']);
		}
	}
	
	// Front Controller 
	$controller = new Front($registry);
	
	$routes = loadJsonConfig('routes');	
	if (!empty($registry->get('request')->get["_route_"]) && !empty($routes[$registry->get('request')->get["_route_"]])){		
		$registry->get('request')->get['route'] = $registry->get('request')->get['_route_'] = $routes[$registry->get('request')->get["_route_"]];
		unset($registry->get('request')->get['_route_']);
	}
	
	$controller->addPreAction(new Action('common/seo_pro'));		
	
	// Router
	if (isset($registry->get('request')->get['route'])) {
		$action = new Action($registry->get('request')->get['route']);
		} else {
		$action = new Action('common/home');
	}
	
	// Dispatch
	$controller->dispatch($action, new Action('error/not_found'));
	
	if ($PageCache->validateIfToCache()){
		$PageCache->setMinifier(new \voku\helper\HtmlMin());		
		$PageCache->writeCache($response->returnOutput());
	}
		
	header('X-FPC-MODE: FALSE');
	header('X-NO-FPC-TIME: ' . $FPCTimer->getTime());
	
	// Output
	$response->output();
<?php
define('VERSION', '1.5.6.4');
define('IS_HTTPS', true);
header('X-ENGINE-ENTRANCE: INDEX');

require_once(dirname(__FILE__) . '/system/jsonconfig.php');

$apisConfig = loadJsonConfig('api');

if (!empty($apisConfig['routes'])) {
    if (!empty($_GET['_route_']) && in_array($_GET['_route_'], $apisConfig['routes'])) {
        header('HTTP/1.1 403 Forbidden');
        die('NOT AVAILABLE FROM MAIN ROUTER');
    }
}

//Загрузка скриптов, в которых еще не определены константы конфига
$loaderConfig = loadJsonConfig('loader');

if (!empty($loaderConfig['preload'])) {
    foreach ($loaderConfig['preload'] as $preloadFile) {
        require_once(dirname(__FILE__) . $preloadFile . '.php');
    }
}

$FPCTimer = new \hobotix\FPCTimer();

//find http host
$httpHOST = str_replace('www.', '', $_SERVER['HTTP_HOST']);
$storesConfig = loadJsonConfig('stores');
$configFiles = loadJsonConfig('configs');
$domainRedirects = loadJsonConfig('domainredirect');

//Echo
if (isset($storesConfig[$httpHOST]) && !is_numeric($storesConfig[$httpHOST])) {
    if (file_exists(dirname(__FILE__) . '/' . trim($storesConfig[$httpHOST]))) {
        $content = file_get_contents(dirname(__FILE__) . '/' . trim($storesConfig[$httpHOST]));
        if ($_SERVER['REQUEST_URI'] == '/') {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 410 Gone');
        }
        echo $content;
    } else {
        header('HTTP/1.1 410 Gone');
    }
    exit();
}

//Редиректы доменов, из конфига domainredirect
if (isset($domainRedirects[$httpHOST])) {
    $newLocation = 'https://' . $domainRedirects[$httpHOST] . $_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $newLocation);
    exit();
}

//Конфигурационный файл	основной выбор с переназначением
$currentConfigFile = false;
if (!empty($configFiles[$httpHOST])) {
    if (file_exists($configFiles[$httpHOST])) {
        $currentConfigFile = ($configFiles[$httpHOST]);
    } else {
        die ('no config file!');
    }
} else {
    die ('ho config file assigned to host');
}

$configFilesPrefix = false;
if (count($configFileExploded = explode('.', $currentConfigFile)) == 3) {
    if (mb_strlen($configFileExploded[1]) == 2) {
        $configFilesPrefix = trim($configFileExploded[1]);
    }
}

//Перезагрузка конфигурационного файла для определенных AJAX контроллеров
if (thisIsAjax() && !empty($_GET['route'])) {
    $dbRouter = loadJsonConfig('dbrouting');
    if (!empty($dbRouter[$_GET['route']]) && file_exists(dirname(__FILE__) . '/' . $dbRouter[$_GET['route']])) {
        $currentConfigFile = $dbRouter[$_GET['route']];

        if ($configFilesPrefix && !strpos($currentConfigFile, ('.' . $configFilesPrefix . '.'))) {
            $currentConfigFile = str_replace('config.', ('config.' . $configFilesPrefix . '.'), $currentConfigFile);
        }
    }
}

//ЗАГРУЗКА ОСНОВНОГО КОНФИГ-ФАЙЛА
//header('X-CONFIG-USED: ' . $currentConfigFile);
require_once($currentConfigFile);

if (isset($storesConfig[$httpHOST])) {
    $store_id = $storesConfig[$httpHOST];
} else {
    die ('we do not serve this shit');
}

//Пейджкеш
$PageCache = new \hobotix\PageCache();
if ($PageCache->validateOther() && $PageCache->validateIfToCache() && $output = $PageCache->getCache()) {
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }

    header('X-FPC-MODE: TRUE');
    header('X-FPC-TIME: ' . $FPCTimer->getTime());
    header('X-FPC-PHP-MEMUSED: ' . size_convert(memory_get_usage(false)));
    header('X-FPC-SYS-MEMUSED: ' . size_convert(memory_get_usage(true)));

    echo $output;
    exit();
}

//Загрузка
if (!empty($loaderConfig['startup'])) {
    foreach ($loaderConfig['startup'] as $startupFile) {
        require_once(DIR_SYSTEM . $startupFile . '.php');
    }
}

//Из директории library, с уже определенной константой DIR_SYSTEM
if (!empty($loaderConfig['libraries'])) {
    foreach ($loaderConfig['libraries'] as $libraryFile) {
        require_once(DIR_SYSTEM . 'library/' . $libraryFile . '.php');
    }
}


$registry = new Registry();
$registry->set('load', new Loader($registry));
$registry->set('config', new Config());
$config = $registry->get('config');
$registry->set('db', new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));
$registry->set('cache', new Cache(CACHE_DRIVER));
$registry->set('request', new Request());
$registry->set('session', new Session($registry));
$session = $registry->get('session');
$registry->set('log', new Log('php-errors-catalog.log'));


$registry->get('config')->set('config_store_id', $store_id);
$settings = $registry->get('cache')->get('settings.structure' . (int)$registry->get('config')->get('config_store_id'));
if (!$settings) {
    if ($registry->get('config')->get('config_store_id') == 0) {
        $query = $registry->get('db')->query("SELECT * FROM setting WHERE store_id = '0'");
    } else {
        $query = $registry->get('db')->query("SELECT * FROM setting WHERE store_id IN (0, " . (int)$registry->get('config')->get('config_store_id') . ") ORDER BY store_id ASC");
    }

    $settings = $query->rows;
    $registry->get('cache')->set('settings.structure' . (int)$registry->get('config')->get('config_store_id'), $settings);
}

foreach ($settings as $setting) {
    if (!$setting['serialized']) {
        $registry->get('config')->set($setting['key'], $setting['value']);
    } else {
        $registry->get('config')->set($setting['key'], $setting['value'] ? unserialize($setting['value']) : false);
    }
}

if ($store_id == 0 || !$store_id) {
    $registry->get('config')->set('config_url', HTTPS_SERVER);
    $registry->get('config')->set('config_ssl', HTTPS_SERVER);
    $registry->get('config')->set('config_img_url', HTTPS_IMG_SERVER);
    $registry->get('config')->set('config_img_ssl', HTTPS_IMG_SERVER);
    $registry->get('config')->set('config_img_urls', HTTP_IMG_SERVERS);
    $registry->get('config')->set('config_img_ssls', HTTPS_IMG_SERVERS);
    $registry->get('config')->set('config_img_server_count', HTTPS_IMG_SERVERS_COUNT);
    $registry->get('config')->set('config_static_subdomain', HTTPS_STATIC_SUBDOMAIN);
}

if ($registry->get('config')->get('config_no_access_enable')) {
    if (!defined('ADMIN_SESSION_DETECTED') || !ADMIN_SESSION_DETECTED) {
        $ipsConfig = loadJsonConfig('ips');

        if (!in_array($_SERVER['REMOTE_ADDR'], $ipsConfig['whitelist'])) {
            header('HTTP/1.1 403 No Access Enabled');

            $csFile = dirname(__FILE__) . '/ep/';
            if (!empty($configFilesPrefix)) {
                $csFile .= ($configFilesPrefix . '/');
            }
            $csFile .= 'coming/index.html';

            if (file_exists($csFile)) {
                die(file_get_contents($csFile));
            } else {
                die('RESOURCE IN DEVELOPMENT');
            }
        }
    }
}

if ($registry->get('config')->get('config_enable_highload_admin_mode')) {
    define('BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED', true);
    header('X-FPC-BYPASS-ALL-ENABLED: 1');
} else {
    define('BYPASS_CACHE_FOR_LOGGED_ADMIN_ENABLED', false);
    header('X-FPC-BYPASS-ALL-ENABLED: 0');
}

//Very fast seo-url logic
if ($registry->get('config')->get('config_seo_url_from_id')) {
    $short_url_mapping = loadJsonConfig('shorturlmap');
    $short_uri_queries = $short_uri_keywords = [];

    if (is_array($short_url_mapping)) {
        foreach ($short_url_mapping as $query => $keyword) {
            $short_uri_queries[$query] = $keyword;
            $short_uri_keywords[$keyword] = $query;
        }
    }

    $registry->set('short_uri_queries', $short_uri_queries);
    $registry->set('short_uri_keywords', $short_uri_keywords);
}

$registry->set('url', new Url($registry->get('config')->get('config_ssl'), $registry));

//Stores main language config to registry (need for ElasticSearch and some other)
$stores_to_main_language_mapping = [];
$query = $registry->get('db')->query("SELECT store_id, value FROM setting WHERE `key` = 'config_language'");
foreach ($query->rows as $result) {
    $stores_to_main_language_mapping[$result['store_id']] = $result['value'];
}

$registry->set('stores_to_main_language_mapping', $stores_to_main_language_mapping);

//Определение языка
$languages = $languages_all = $languages_id_mapping = $languages_id_code_mapping = $languages_all_id_code_mapping = [];
$languages_id_code_mapping = [];
$query = $registry->get('db')->query("SELECT * FROM `language` WHERE status = '1'");

foreach ($query->rows as $result) {
    $languages_all[$result['code']] = $result;
    $languages_all_id_code_mapping[$result['language_id']] = $result['code'];

    if ($result['front']) {
        $languages[$result['code']] = $result;
        $languages_id_mapping[$result['language_id']] = $result;
        $languages_id_code_mapping[$result['language_id']] = $result['code'];
    }
}

//ALL LANGUAGES TO REGISTRY
$registry->set('languages', $languages);
$registry->set('languages_all', $languages_all);
$registry->set('languages_id_mapping', $languages_id_mapping);
$registry->set('languages_id_code_mapping', $languages_id_code_mapping);
$registry->set('languages_all_id_code_mapping', $languages_all_id_code_mapping);
$registry->get('config')->set('config_supported_languages', [$registry->get('config')->get('config_language'), $registry->get('config')->get('config_second_language')]);
$registry->get('config')->set('config_rainforest_source_language_id', $languages_all[$registry->get('config')->get('config_rainforest_source_language')]['language_id']);

$registry->set('shippingmethods', loadJsonConfig('shippingmethods'));

//FROM URL
if ($registry->get('config')->get('config_second_language')) {
    $language_from_url = explode("/", $registry->get('request')->server['REQUEST_URI']);

    $code_from_url = $registry->get('config')->get('config_language');
    foreach ($language_from_url as $lang) {
        unset($value);
        $break = false;
        foreach ($languages as $key => $value) {
            if ($value['urlcode'] && $value['urlcode'] == $lang) {
                $code_from_url = $key;
                $break = true;
                break;
            }
        }
        if ($break) break;
    }
}

if (thisIsAjax($registry->get('request')) || thisIsUnroutedURI($registry->get('request'))) {
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

if (!empty($code_from_url) && array_key_exists($code_from_url, $languages)) {
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

/*
	if (isset($registry->get('session')->data['language']) && array_key_exists($registry->get('session')->data['language'], $languages) && $languages[$registry->get('session')->data['language']]['status']) {
		$code = $registry->get('session')->data['language'];
		} elseif (isset($registry->get('request')->cookie['language']) && array_key_exists($registry->get('request')->cookie['language'], $languages) && $languages[$registry->get('request')->cookie['language']]['status']) {
		$code = $registry->get('request')->cookie['language'];
		} elseif (!empty($code_from_url) && array_key_exists($code_from_url, $languages)){
		$code = $code_from_url;		
		} elseif ($detect) {
		$code = $detect;
		} else {
		$code = $registry->get('config')->get('config_language');
	}*/

//REDIRECTION FOR UKRAINIAN LAWS IF PREVIOUS CONFIG WAS BAD, LIKE (/ua, /uk)
if ((!defined('CRAWLER_SESSION_DETECTED') || !CRAWLER_SESSION_DETECTED) && (!defined('PAGESPEED_SESSION_DETECTED') || !PAGESPEED_SESSION_DETECTED)) {
    if ($registry->get('config')->get('config_second_language') && $registry->get('config')->get('config_do_redirection_to_second_language')) {

        //ONLY FIRST CUSTOMER COMING, NO SESSION AND NO COOKIE
        if ((empty($registry->get('session')->data['language'])) && empty($registry->get('request')->cookie['language'])) {
            //MAIN LANGUAGE SET NOW AND IT ISN'T SECOND LANGUAGE
            if ($code != $registry->get('config')->get('config_second_language')) {
                $registry->set('perform_redirect_to_second_language', true);
            }
        }
    }
}

if (!$registry->get('config')->get('config_second_language')) {
    $code = $registry->get('config')->get('config_language');
}

if (!isset($registry->get('session')->data['language']) || ($registry->get('session')->data['language'] != $code && !doNotSetLanguageCookieSession($registry->get('request')))) {
    $registry->get('session')->data['language'] = $code;
}

if (!isset($registry->get('request')->cookie['language']) || ($registry->get('request')->cookie['language'] != $code && !doNotSetLanguageCookieSession($registry->get('request')))) {
    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $registry->get('request')->server['HTTP_HOST']);
}

if ($registry->get('config')->get('config_second_language') && $code == $registry->get('config')->get('config_second_language')) {
    define('GOOGLE_ID_ENDFIX', '-' . $languages[$code]['language_id']);
} else {
    define('GOOGLE_ID_ENDFIX', '');
}

if (thisIsAjax($registry->get('request'))) {
    define('IS_AJAX_REQUEST', true);
} else {
    define('IS_AJAX_REQUEST', false);
}

$registry->get('config')->set('config_language_id', $languages[$code]['language_id']);
$registry->get('config')->set('config_language', $languages[$code]['code']);
$registry->get('config')->set('config_language_hreflang', $languages[$code]['hreflang']);

if (!($registry->get('config')->get('config_warehouse_identifier'))) {
    $registry->get('config')->set('config_warehouse_identifier', 'quantity_stock');
}

//Hack to overload any system config variable for one ip
$overloadConfig = loadJsonConfig('overload');
if (!empty($overloadConfig[$registry->get('request')->server['REMOTE_ADDR']])) {
    foreach ($overloadConfig[$registry->get('request')->server['REMOTE_ADDR']] as $ovkey => $ovvalue) {
        $registry->get('config')->set($ovkey, $ovvalue);
        header('X-CONFIG-' . $ovkey . '-OVERLOADED: true');
    }
}

//Language
$language = new Language($languages[$code]['directory'], $registry);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);

//Response lib
$response = new Response($registry);
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($registry->get('config')->get('config_compression'));
$registry->set('response', $response);


//Other libraries
$registry->set('document', new Document());
$registry->set('affiliate', new Affiliate($registry));
$registry->set('currency', new Currency($registry));
$registry->set('smsQueue', new hobotix\SmsQueue($registry));
$registry->set('phoneValidator', new hobotix\phoneValidator($registry));
$registry->set('smsAdaptor', new hobotix\SmsAdaptor($registry));
$registry->set('otpLogin', new hobotix\OTP($registry));
$registry->set('emailBlackList', new hobotix\EmailBlackList($registry));
$registry->set('customer', new hobotix\CustomerExtended($registry));
$registry->set('tax', new Tax($registry));
$registry->set('weight', new Weight($registry));
$registry->set('length', new Length($registry));
$registry->set('cart', new Cart($registry));
$registry->set('encryption', new Encryption($registry->get('config')->get('config_encryption')));
$registry->set('Bitrix24', new hobotix\Bitrix24($registry));
$registry->set('mAlert', new hobotix\mAlert($registry));
$registry->set('shortAlias', new hobotix\shortAlias($registry));
$registry->set('elasticSearch', new hobotix\ElasticSearch($registry));
$registry->set('courierServices', new hobotix\CourierServices($registry));
$registry->set('openaiAdaptor', new hobotix\OpenAIAdaptor($registry));
$registry->set('Fiscalisation', new hobotix\Fiscalisation($registry));
$registry->set('couponRandom', new hobotix\CouponRandom($registry));
$registry->set('bypass_rainforest_caches_and_settings', true);
$registry->set('rainforestAmazon', new hobotix\RainforestAmazon($registry));

//Preauth by utoken logic
if (!empty($registry->get('request')->get['utoken']) && !empty($registry->get('request')->get['utm_term'])) {
    $registry->get('request')->get['utoken'] = checkIfGetParamIsArray($registry->get('request')->get['utoken']);
    $registry->get('request')->get['utm_term'] = checkIfGetParamIsArray($registry->get('request')->get['utm_term']);
    $registry->get('request')->get['utoken'] = preg_replace("/[^a-zA-Z0-9]/", "", $registry->get('request')->get['utoken']);

    if ($registry->get('request')->get['utoken']) {
        $customer_query = $registry->get('db')->ncquery("SELECT * FROM customer WHERE utoken = '" . $registry->get('db')->escape($registry->get('request')->get['utoken']) . "'");

        if ($customer_query->row) {
            if (trim($customer_query->row['email']) && md5(trim($customer_query->row['email']) . $registry->get('config')->get('config_encryption')) == $registry->get('request')->get['utoken']) {
                $registry->get('customer')->login(trim($customer_query->row['email']), '', true);
            }
        }
    }
}

//Preauth by customer_id logic
if (!empty($registry->get('request')->get['utoken']) && !empty($registry->get('request')->get['customer_id'])) {
    $registry->get('request')->get['utoken'] = checkIfGetParamIsArray($registry->get('request')->get['utoken']);
    $registry->get('request')->get['customer_id'] = checkIfGetParamIsArray($registry->get('request')->get['customer_id']);
    $registry->get('request')->get['utoken'] = trim(preg_replace("/[^a-zA-Z0-9]/", "", $registry->get('request')->get['utoken']));
    $registry->get('request')->get['customer_id'] = trim(preg_replace("/[^0-9]/", "", $registry->get('request')->get['customer_id']));

    if ($registry->get('request')->get['utoken']) {
        $customer_query = $registry->get('db')->ncquery("SELECT * FROM customer WHERE customer_id = '" . (int)$registry->get('request')->get['customer_id'] . "'");

        if ($customer_query->row) {
            if (trim($customer_query->row['customer_id']) && md5(trim($customer_query->row['customer_id']) . $registry->get('config')->get('config_encryption')) == $registry->get('request')->get['utoken']) {
                $registry->get('customer')->login(trim($customer_query->row['customer_id']), '', true);
            }
        }
    }
}

if ($registry->get('customer')->getTracking()) {
    setcookie('tracking', $registry->get('customer')->getTracking(), time() + 3600 * 24 * 1000, '/');
} elseif (isset($registry->get('request')->get['tracking'])) {
    setcookie('tracking', $registry->get('request')->get['tracking'], time() + 3600 * 24 * 1000, '/');
}

if ($registry->get('customer')->isLogged()) {
    $registry->set('customer_group_id', $registry->get('customer')->getCustomerGroupId());
} else {
    $registry->set('customer_group_id', (int)$registry->get('config')->get('config_customer_group_id'));
}

if (isset($registry->get('request')->get['coupon']) && $registry->get('request')->get['coupon']) {
    if (!isset($registry->get('session')->data['coupon'])) {
        $registry->get('session')->data['coupon'] = trim($registry->get('request')->get['coupon']);
    }
}


//megafilter implementation moved from engine/controller
if (!empty($registry->get('request')->get['mfp'])) {
    preg_match('/path\[([^]]*)\]/', $registry->get('request')->get['mfp'], $mf_matches);

    if (!empty($mf_matches[1])) {
        $registry->get('request')->get['path'] = $mf_matches[1];

        if (isset($registry->get('request')->get['category_id']) || (isset($registry->get('request')->get['route']) && in_array($registry->get('request')->get['route'], ['product/search', 'product/special', 'product/manufacturer/info']))) {
            $mf_matches = explode('_', $mf_matches[1]);
            $registry->get('request')->get['category_id'] = end($mf_matches);
        }
    }

    unset($mf_matches);
}

// Front Controller
$controller = new Front($registry);

$routes = loadJsonConfig('routes');
if (!empty($registry->get('request')->get["_route_"]) && !empty($routes[$registry->get('request')->get["_route_"]])) {
    $registry->get('request')->get['route'] = $registry->get('request')->get['_route_'] = $routes[$registry->get('request')->get["_route_"]];
    unset($registry->get('request')->get['_route_']);
}

//Sorts
$sorts = loadJsonConfig('sorts');

if (!empty($sorts['sorts'])) {
    $registry->set('sorts', $sorts['sorts']);
}

if (!empty($sorts['sorts_available'])) {
    $registry->set('sorts_available', $sorts['sorts_available']);
}

if ($registry->get('config')->get('config_sort_default')) {
    $registry->get('config')->set('sort_default', $registry->get('config')->get('config_sort_default'));
} elseif (!empty($sorts['sort_default'])) {
    $registry->get('config')->set('sort_default', $sorts['sort_default']);
}

if ($registry->get('config')->get('config_order_default')) {
    $registry->get('config')->set('order_default', $registry->get('config')->get('config_order_default'));
} elseif (!empty($sorts['order_default'])) {
    $registry->get('config')->set('order_default', $sorts['order_default']);
}

//Default city reloading
if ($registry->get('config')->get('config_default_city_' . $registry->get('config')->get('config_language'))) {
    $registry->get('config')->set('config_default_city', $registry->get('config')->get('config_default_city_' . $registry->get('config')->get('config_language')));
}

//dev template reloading if user is logged to admin
if (ADMIN_SESSION_DETECTED) {
    if (!empty($registry->get('request')->cookie[ini_get('session.name') . 'A'])) {
        if (defined('DB_SESSION_HOSTNAME') && class_exists('Hobotix\SessionHandler\SessionHandler')) {
            $handler = new \Hobotix\SessionHandler\SessionHandler();
            $handler->setDbDetails(DB_SESSION_HOSTNAME, DB_SESSION_USERNAME, DB_SESSION_PASSWORD, DB_SESSION_DATABASE);
            $handler->setDbTable(DB_SESSION_TABLE);

            if ($adminSessionData = $handler->read($registry->get('request')->cookie[ini_get('session.name') . 'A'])) {
                $adminSessionData = \Hobotix\SessionHandler\SessionHandler::unserialize($adminSessionData);

                if ($adminSessionData && !empty($adminSessionData['user_id'])) {
                    $user_query = $registry->get('db')->ncquery("SELECT status, username, dev_template FROM user WHERE user_id = '" . (int)$adminSessionData['user_id'] . "'");

                    if ((int)$user_query->row['dev_template'] == 1) {
                        header('X-USING-DEV-TEMPLATE: TRUE');
                        header('X-FRONTEND-DEV: ' . $user_query->row['username']);

                        if (is_dir(DIR_TEMPLATE . $registry->get('config')->get('config_template') . '_dev/')) {
                            $registry->get('config')->set('config_template', $registry->get('config')->get('config_template') . '_dev');
                        }

                        $assets_configs = [
                            'config_header_min_scripts',
                            'config_header_excluded_scripts',
                            'config_header_min_styles',
                            'config_header_excluded_styles',
                            'config_footer_min_scripts',
                            'config_footer_min_styles',
                            'config_footer_excluded_scripts',
                            'config_footer_excluded_styles'
                        ];

                        foreach ($assets_configs as $asset_config) {
                            if ($registry->get('config')->has($asset_config . '_dev')) {
                                $registry->get('config')->set($asset_config, $registry->get('config')->get($asset_config . '_dev'));
                            }
                        }
                    }
                }
            }
        }
    }
}


//Implementation of different redirect modes and|or modules
$controller->addPreAction(new Action('common/hoboseo/preSeoPro'));
$controller->addPreAction(new Action('common/seo_pro'));

// Router
if (isset($registry->get('request')->get['route'])) {
    $action = new Action($registry->get('request')->get['route']);
} else {
    $action = new Action('common/home');
}

//Dispatch
$controller->dispatch($action, new Action('error/not_found'));

if ($PageCache->validateIfToCache()) {
    $PageCache->setMinifier(new \voku\helper\HtmlMin());
    $PageCache->writeCache($response->returnOutput());
}


header('X-FPC-MODE: FALSE');
header('X-NO-FPC-TIME: ' . $FPCTimer->getTime());
header('X-FPC-PHP-MEMUSED: ' . size_convert(memory_get_usage(false)));
header('X-FPC-SYS-MEMUSED: ' . size_convert(memory_get_usage(true)));

$response->output();
<?php
define('VERSION',               '1.5.6.4');
define('IS_HTTPS',              true);
define('APPLICATION_DIRECTORY', dirname(__FILE__));
define('CONFIG_FILE',           'config.api.php');
define('API_SESSION',           true);
header('X-ENGINE-ENTRANCE: API');

require_once(dirname(__FILE__) . '/system/jsonconfig.php');

$ipsConfig  = loadJsonConfig('ips');
$apisConfig = loadJsonConfig('api');

if (empty($_GET['_route_']) && !empty($_GET['route'])) {
    $_GET['_route_'] = $_GET['route'];
} elseif (empty($_GET['_route_'])){
    $_GET['_route_'] = 'route/empty';
}

$_GET['_route_'] = ltrim($_GET['_route_'], '/');
if (strpos($_GET['_route_'], 'api/') !== 0) {
    $_GET['_route_'] = 'api/' . $_GET['_route_'];
}

$whitelisted = false;
if (!empty($apisConfig['whitelist'])) {
    if (!empty($_GET['_route_']) && in_array($_GET['_route_'], $apisConfig['whitelist'])) {
        $whitelisted = true;
    }
}

if (!empty($ipsConfig['whitelist'])) {
    if (!$whitelisted && !in_array($_SERVER['REMOTE_ADDR'], $ipsConfig['whitelist'])) {
        header('HTTP/1.1 403 Forbidden');
        die('NOT VALID IP');
    }
}

if (!empty($apisConfig['routes'])) {
    if (empty($_GET['_route_']) || !in_array($_GET['_route_'], $apisConfig['routes'])) {
        header('HTTP/1.1 403 Forbidden');
        die('NOT AN API ROUTE');
    }
}

$loaderConfig = loadJsonConfig('loader');

if (!empty($loaderConfig['preload'])) {
    foreach ($loaderConfig['preload'] as $preloadFile) {
        require_once(dirname(__FILE__) . $preloadFile . '.php');
    }
}

$FPCTimer = new \hobotix\FPCTimer();

$httpHOST           = str_replace('www.', '', $_SERVER['HTTP_HOST']);
$storesConfig       = loadJsonConfig('stores');
$domainRedirects    = loadJsonConfig('redirect');

if (empty($storesConfig[$httpHOST])) {
    header("HTTP/1.1 500 Not served HTTP_HOST");
    die('Not served HTTP_HOST');
} else {
    $configFilesPrefix = false;
    if (count($configFileExploded = explode('.', $storesConfig[$httpHOST]['config'])) == 3) {
        if (mb_strlen($configFileExploded[1]) == 2) {
            $configFilesPrefix = trim($configFileExploded[1]);
        }
    }
}

if (isset($domainRedirects[$httpHOST])) {
    $newLocation = 'https://' . $domainRedirects[$httpHOST] . $_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $newLocation);
    exit();
}

if (defined('CONFIG_FILE') && file_exists(APPLICATION_DIRECTORY . '/' . CONFIG_FILE)) {
    $currentConfigFile = CONFIG_FILE;
}

if (!empty($apisConfig['configs']) && !empty($apisConfig['configs'][0])) {
    foreach ($apisConfig['configs'][0] as $apiRoute => $apiConfig) {
        if ($_GET['_route_'] == $apiRoute) {
            $currentConfigFile = $apiConfig;
            break;
        }
    }
}

if ($configFilesPrefix) {
    $currentConfigFile = str_replace('config.', 'config.' . $configFilesPrefix . '.', $currentConfigFile);
}

if (!file_exists(APPLICATION_DIRECTORY . '/' . $currentConfigFile)) {
    $currentConfigFile = $storesConfig[$httpHOST]['config'];
}

header('X-CONFIG-FILE: ' . $currentConfigFile);

require_once(APPLICATION_DIRECTORY . '/' . $currentConfigFile);

if (isset($storesConfig[$httpHOST])) {
    $store_id = (int)$storesConfig[$httpHOST]['store_id'];
} else {
    die('we do not serve this shit');
}

$PageCache = new \hobotix\PageCache();

if (!empty($loaderConfig['startup'])) {
    foreach ($loaderConfig['startup'] as $startupFile) {
        require_once(DIR_SYSTEM . $startupFile . '.php');
    }
}

if (!empty($loaderConfig['libraries'])) {
    foreach ($loaderConfig['libraries'] as $libraryFile) {
        require_once(DIR_SYSTEM . 'library/' . $libraryFile . '.php');
    }
}

$registry = new Registry();
$registry->set('load', new Loader($registry));
$registry->set('config', new Config());
$config = $registry->get('config');

$registry->set('db',        new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));
$registry->set('cache',     new Cache(CACHE_DRIVER));
$registry->set('request',   new Request());
$registry->set('session',   new Session($registry));
$session = $registry->get('session');

$registry->set('log',       new Log('php-errors-api.log'));

$registry->get('config')->set('config_store_id', $store_id);
$settings = $registry->get('cache')->get('settings.structure'.(int)$registry->get('config')->get('config_store_id'));
if (!$settings) {
    $query = $registry->get('db')->query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . (int)$registry->get('config')->get('config_store_id') . "' ORDER BY store_id ASC");
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
    $registry->get('config')->set('config_url', HTTP_SERVER);
    $registry->get('config')->set('config_ssl', HTTPS_SERVER);
    $registry->get('config')->set('config_img_url', HTTP_IMG_SERVER);
    $registry->get('config')->set('config_img_ssl', HTTPS_IMG_SERVER);
    $registry->get('config')->set('config_img_urls', HTTP_IMG_SERVERS);
    $registry->get('config')->set('config_img_ssls', HTTPS_IMG_SERVERS);
    $registry->get('config')->set('config_img_server_count', HTTPS_IMG_SERVERS_COUNT);
    $registry->get('config')->set('config_static_subdomain', HTTPS_STATIC_SUBDOMAIN);
}

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

$registry->set('url', new Url($registry->get('config')->get('config_ssl'), $registry));

$stores_to_main_language_mapping = [];
$query = $registry->get('db')->query("SELECT store_id, value FROM `setting` WHERE `key` = 'config_language'");
foreach ($query->rows as $result) {
    $stores_to_main_language_mapping[$result['store_id']] = $result['value'];
}

$registry->set('stores_to_main_language_mapping', $stores_to_main_language_mapping);

$languages = $languages_front = $languages_all = $languages_id_mapping = $languages_id_code_mapping = $languages_all_id_code_mapping = [];
$languages_id_code_mapping = [];
$query = $registry->get('db')->query("SELECT * FROM `language` WHERE status = '1'"); 

foreach ($query->rows as $result) {
    $languages[$result['code']]                             = $result;
    $languages_all[$result['code']]                         = $result;      
    $languages_all_id_code_mapping[$result['language_id']]  = $result['code'];
    
    if ($result['front']){
        $languages_front[$result['code']]                   = $result;
        $languages_id_mapping[$result['language_id']]       = $result;
        $languages_id_code_mapping[$result['language_id']]  = $result['code'];
    }
}

$registry->set('languages',                     $languages);
$registry->set('languages_all',                 $languages_all);
$registry->set('languages_id_mapping',          $languages_id_mapping);
$registry->set('languages_id_code_mapping',     $languages_id_code_mapping);
$registry->set('languages_all_id_code_mapping', $languages_all_id_code_mapping);
$registry->get('config')->set('config_supported_languages', [$registry->get('config')->get('config_language'), $registry->get('config')->get('config_second_language')]);
$registry->get('config')->set('config_rainforest_source_language_id', $languages_all[$registry->get('config')->get('config_rainforest_source_language')]['language_id']);   

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
        if ($break) {
            break;
        }
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

$registry->set('shippingmethods', loadJsonConfig('shippingmethods'));

$language = new Language($languages[$code]['directory'], $registry);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);

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

if ($apiRoute && $apiRoute == 'api/rainforest/getProducts'){
    foreach ($languages as $language){
        $registry->get('config')->set('config_rainforest_enable_language_' . $language['code'],  $registry->get('config')->get('config_rainforest_external_enable_language_' . $language['code']));
    }
}

$response = new Response($registry);
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($registry->get('config')->get('config_compression'));
$registry->set('response', $response);

foreach ($loaderConfig['global_libraries'] as $global_library => $global_library_config){
    if (in_array('api', $global_library_config['load'])){
        if ($global_library_config['registry']){
            $registry->set($global_library, new $global_library_config['class']($registry));
        } else {
            $registry->set($global_library, new $global_library_config['class']());
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

$controller = new Front($registry);

$routes = loadJsonConfig('routes');
if (!empty($registry->get('request')->get["_route_"]) && !empty($routes[$registry->get('request')->get["_route_"]])) {
    $registry->get('request')->get['route'] = $registry->get('request')->get['_route_'] = $routes[$registry->get('request')->get["_route_"]];
    unset($registry->get('request')->get['_route_']);
}

if (strpos($currentConfigFile, 'admin') === false) {
    $controller->addPreAction(new Action('common/seo_pro'));
}

$inputData = array_merge($registry->get('request')->post, $registry->get('request')->get);
$inputData = atrim_array($inputData);

$requiredParams = false;
if (!empty($apisConfig['params']) && !empty($apisConfig['params'][0])) {
    foreach ($apisConfig['params'][0] as $apiRoute => $apiParams) {
        if ($_GET['_route_'] == $apiRoute) {
            $requiredParams = $apiParams;
            break;
        }
    }
}

unset($apiRoute);
unset($apiParams);

$additionalParams = false;
if (!empty($apisConfig['additional']) && !empty($apisConfig['additional'][0])) {
    foreach ($apisConfig['additional'][0] as $apiRoute => $apiParams) {
        if ($_GET['_route_'] == $apiRoute) {
            $additionalParams = $apiParams;
            break;
        }
    }
}

unset($apiRoute);
unset($apiParams);

$paramsValues = false;
if (!empty($apisConfig['paramvalues']) && !empty($apisConfig['paramvalues'][0])) {
    foreach ($apisConfig['paramvalues'][0] as $apiRoute => $apiParams) {
        if ($_GET['_route_'] == $apiRoute && !empty($apiParams[0])) {
            $paramsValues = $apiParams[0];
            break;
        }
    }
}

$apiParams = [];
if (!empty($requiredParams)) {
    foreach ($requiredParams as $param) {
        $paramType = false;
        if (count($exploded = explode(':', $param)) == 2) {
            $paramType = trim($exploded[1]);
            $param      = trim($exploded[0]);
        }

        if (empty($inputData[$param])) {
            header('HTTP/1.1 403 Error No Parameter');
            die('NO REQUIRED PARAM FOR ' . $_GET['_route_'] . ': ' . $param);
        } else {
            if (!empty($paramsValues[$param])) {
                if (!in_array($inputData[$param], $paramsValues[$param])) {
                    header('HTTP/1.1 403 Error No Parameter');
                    die('INCORRECT PARAM VALUE FOR ' . $_GET['_route_'] . ': ' . $param .', MUST BE ONE OF: ' . implode(',', $paramsValues[$param]));
                }
            }

            if ($paramType == 'int') {
                $inputData[$param] = (int)$inputData[$param];
            }

            if ($paramType == 'string') {
                $inputData[$param] = (string)$inputData[$param];
            }

            if ($paramType == 'date') {
                $inputData[$param] = date('Y-m-d', strtotime($inputData[$param]));
            }

            if ($paramType == 'float') {
                $inputData[$param] = (float)$inputData[$param];
            }
            
            if ($paramType == 'json') {
                if (!json_decode(html_entity_decode($inputData[$param])) && !json_decode($inputData[$param])) {
                    $constants = get_defined_constants(true);
                    $json_errors = array();
                    foreach ($constants["json"] as $name => $value) {
                        if (!strncmp($name, "JSON_ERROR_", 11)) {
                            $json_errors[$value] = $name;
                        }
                    }

                    header('HTTP/1.1 403 Error JSON DECODE ERROR');
                    die('JSON DECODE ERROR FOR ' . $_GET['_route_'] . ': ' . $param . ': ' . $json_errors[json_last_error()]);
                }


                $inputData[$param] = json_decode(html_entity_decode($inputData[$param]), true);

                if (!$inputData[$param]) {
                    $inputData[$param] = json_decode($param, true);
                }
            }

            $apiParams[$param] = $inputData[$param];
        }
    }
}

unset($param);

if (!empty($additionalParams)) {
    foreach ($additionalParams as $param) {
        if (!empty($inputData[$param])) {
            $apiParams[$param] = $inputData[$param];
        }
    }
}

if (isset($registry->get('request')->get['route'])) {
    if ($apiParams) {
        $action = new Action($registry->get('request')->get['route'], $apiParams);
    } else {
        $action = new Action($registry->get('request')->get['route']);
    }
} else {
    $action = new Action('common/home');
}

$controller->dispatch($action, new Action('error/not_found'));

header('X-FPC-MODE: FALSE');
header('X-NO-FPC-TIME: ' . $FPCTimer->getTime());

$response->output();

<?php

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) && $_SERVER["HTTP_CF_CONNECTING_IP"]) {
	$_SERVER["REMOTE_ADDR"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

if (is_cli()){
	define('CLI_MODE', true);
} else {
	define('CLI_MODE', false);
}

$ipsConfig = loadJsonConfig('ips');

$_HEADERS = getallheaders();
if (isset($_HEADERS['X-Hello']) && $_HEADERS['X-Hello'] == 'world'){
	header('X-DEBUG-REASON: HEADER hello=world');
	define('IS_DEBUG', 			true);
	define('DEV_ENVIRONMENT', 	true);
	define('DEBUGSQL', 			true);
} elseif ((isset($_GET['hello']) && $_GET['hello'] == 'world')){
	header('X-DEBUG-REASON: GET hello=world');
	define('IS_DEBUG', 			true);
	define('DEV_ENVIRONMENT', 	true);
	define('DEBUGSQL', 			true);
} else {
	if (is_cli()) {
		header('X-DEBUG-REASON: is_cli');
		define('IS_DEBUG', 			true);
		define('DEV_ENVIRONMENT', 	false);
	} elseif (!empty($ipsConfig['debug']) && !empty($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $ipsConfig['debug'])) {
		header('X-DEBUG-REASON: ipsConfig');
		define('IS_DEBUG', 			true);
		define('DEV_ENVIRONMENT', 	false);
	} elseif (thisIsAjax()){
		header('X-DEBUG-REASON-FALSE: thisIsAjax');
		define('IS_DEBUG', 			false);
		define('DEV_ENVIRONMENT', 	false);
	} else {
		header('X-DEBUG-REASON-FALSE: All passed');
		define('IS_DEBUG', 			false);
		define('DEV_ENVIRONMENT', 	false);		
	}

	if (isset($_GET['hello']) && $_GET['hello'] == 'justsql'){
		define('DEBUGSQL', true);
	} else {
		define('DEBUGSQL', false);
	}
}

$imageConfig 	= loadJsonConfig('image');
$imageQualities = $imageConfig['quality'];

if (isset($_SERVER['HTTP_ACCEPT']) && isset($_SERVER['HTTP_USER_AGENT'])) {
	if( strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false && (function_exists('imagewebp') || extension_loaded('imagick')) && (int)$imageQualities['config_image_webp_quality'] > 0) {
		header('X-IMAGE-WEBP: TRUE');	
		define('WEBPACCEPTABLE', true);	
	} else {
		define('WEBPACCEPTABLE', false);	
	}
} else {
	define('WEBPACCEPTABLE', false);
}

if (isset($_SERVER['HTTP_ACCEPT']) && isset($_SERVER['HTTP_USER_AGENT'])) {
	if( strpos( $_SERVER['HTTP_ACCEPT'], 'image/avif' ) !== false && (extension_loaded('imagick')) && (int)$imageQualities['config_image_avif_quality'] > 0) {
		header('X-IMAGE-AVIF: TRUE');
		define('AVIFACCEPTABLE', true);	
	} else {
		define('AVIFACCEPTABLE', false);	
	}
} else {
	define('AVIFACCEPTABLE', false);
}

//FALLBACK IMAGES
if (!empty($imageConfig['fallback'])){
	define('IMAGE_CONVERT_FALLBACK', $imageConfig['fallback']);
} else {
	define('IMAGE_CONVERT_FALLBACK', []);
}

define('IMAGE_JPEG_QUALITY', $imageQualities['config_image_jpeg_quality']);
define('IMAGE_WEBP_QUALITY', $imageQualities['config_image_webp_quality']);
define('IMAGE_AVIF_QUALITY', $imageQualities['config_image_avif_quality']);

if (AVIFACCEPTABLE){
	define('IMAGE_QUALITY', IMAGE_AVIF_QUALITY);	
} elseif (WEBPACCEPTABLE){
	define('IMAGE_QUALITY', IMAGE_WEBP_QUALITY);
} else {
	define('IMAGE_QUALITY', IMAGE_JPEG_QUALITY);
}

if (IS_DEBUG){
	error_reporting (E_ALL);	
	ini_set('display_errors', 1);
	} else {
	error_reporting (0);	
	ini_set('display_errors', 0);
}		
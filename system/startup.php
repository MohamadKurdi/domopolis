<?php
if (version_compare(PHP_VERSION, '8.1.0', '>')){
	if( !class_exists('Composer\\Autoload\\ClassLoader') ){
    	require_once(DIR_SYSTEM . '../vendor/autoload.php');
	}
} else {
	header('X-AUTOLOAD-SKIP', PHP_VERSION);
}

if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[clean($key)] = clean($value);
			}
		} else {
			$data = stripslashes($data);
		}

		return $data;
	}			

	$_GET 		= clean($_GET);
	$_POST 		= clean($_POST);
	$_REQUEST 	= clean($_REQUEST);
	$_COOKIE 	= clean($_COOKIE);
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) { 
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) { 
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1); 

	if (isset($_SERVER['QUERY_STRING'])) { 
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
	} 
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

$loaderConfig = loadJsonConfig('loader');

if (!empty($loaderConfig['helper'])){
	foreach ($loaderConfig['helper'] as $helperFile){
		if (file_exists(DIR_SYSTEM . '/helper/' . $helperFile . '.php')){
			require_once(DIR_SYSTEM . '/helper/' . $helperFile . '.php');
		}			
	}
}

if (!empty($loaderConfig['startup_libraries'])){
	foreach ($loaderConfig['startup_libraries'] as $sLibFile){
		if (file_exists(DIR_SYSTEM . '/' . $sLibFile . '.php')){
			require_once(DIR_SYSTEM . '/' . $sLibFile . '.php');
		}			
	}
}

if (ini_get('register_globals')) {
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset(${$key}); 
		}
	}
}
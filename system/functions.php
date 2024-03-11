<?php

function mkdir_with_echo($dir){
    echoLine('[install::mkdir]' . $dir, 'w');

    if (!is_dir($dir)){
        mkdir($dir, 0755, true);
    } else {
        echoLine('[install::mkdir] directory exists!', 's');
    }
}

function install(): void{
    if (!is_file(dirname(__FILE__) . '/config.parts/INSTALLED')) {
        echoLine('Not found install file, running directory creating', 'w');

        if (is_dir(dirname(__FILE__) . '/config.parts/')){
            if (defined('DIR_PIDS')){
                mkdir_with_echo(DIR_PIDS);
            }

            if (defined('DIR_LOGS')){
                mkdir_with_echo(DIR_LOGS);
            }

            if (defined('DIR_CACHE')){
                mkdir_with_echo(DIR_CACHE);
            }

            if (defined('DIR_CACHE') && defined('PAGECACHE_DIR')){
                mkdir_with_echo(DIR_CACHE . PAGECACHE_DIR);
            }

            if (defined('DIR_IMAGECACHE')){
                mkdir_with_echo(DIR_IMAGECACHE);
            }

            if (defined('DIR_IMAGE')){
                mkdir_with_echo(DIR_IMAGE);
            }

            if (defined('DIR_MINIFIED')){
                mkdir_with_echo(DIR_MINIFIED);
            }

            if (defined('DIR_REFEEDS')){
                mkdir_with_echo(DIR_REFEEDS);
            }

            if (defined('DIR_SITEMAPS_CACHE')){
                mkdir_with_echo(DIR_SITEMAPS_CACHE);
            }

            if (defined('DIR_EXPORT')){
                mkdir_with_echo(DIR_EXPORT);
            }

            touch(dirname(__FILE__) . '/config.parts/INSTALLED');

        } else {
            echoLine('Not found configuration files, exiting', 'e');
        }
    }

    echoLine('Do not forget to run composer install and npm install', 'w');
}

if (!function_exists('is_cli')){
	function is_cli(): bool{
		return (php_sapi_name() == 'cli');		
	}
}

function size_convert($size)
{
	$unit = array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function getCliParamValue($string){
	$exploded = explode('=', $string);
	return $exploded[1];
}

if (!function_exists('loadJsonConfig')){
	function loadJsonConfig($config){
        $json = null;

		if (defined('DIR_SYSTEM') && @file_exists(DIR_SYSTEM . 'config/' . $config . '.json')){

			$json = file_get_contents(DIR_SYSTEM . 'config/' . $config . '.json');
			
		} elseif (@file_exists(dirname(__FILE__) . '/config/' . $config . '.json')) {

			$json = file_get_contents(dirname(__FILE__) . '/config/' . $config . '.json'); 

		} elseif (@file_exists(dirname(__FILE__) . '/system/config/' . $config . '.json')){

			$json = file_get_contents(dirname(__FILE__) . '/system/config/' . $config . '.json'); 

		}
		
		if ($json){
			return json_decode($json, true);
		} else {
			return [];
		}
	}
}

if (!function_exists('getallheaders')) {
    function getallheaders(){
       $headers = [];
       foreach ($_SERVER as $name => $value){

           if (substr($name, 0, 5) == 'HTTP_'){
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }

       return $headers;
    }
}

if (!function_exists('echoLine')){
	function echoLine($line, $type = 'l'){
		if (php_sapi_name() === 'cli'){
			switch ($type) {
				case 'e':
				echo "\033[31m$line \033[0m" . PHP_EOL;
				break;
				case 's':
				echo "\033[32m$line \033[0m" . PHP_EOL;
				break;
				case 'w':
				echo "\033[33m$line \033[0m" . PHP_EOL;
				break;  
				case 'i':
				echo "\033[36m$line \033[0m" . PHP_EOL;
				break;
				case 'd':
				echo "\033[35m$line \033[0m" . PHP_EOL;
				break;        
				case 'l':
				echo $line . PHP_EOL;
				break;  
				default:
				echo $line . PHP_EOL;
				break;
			}
		}
	}
}

if (!function_exists('echoSimple')){
	function echoSimple($line, $type = 'l'){
		if (php_sapi_name() === 'cli'){
			switch ($type) {
				case 'e':
				echo "\033[31m$line \033[0m" . ' ';
				break;
				case 's':
				echo "\033[32m$line \033[0m". ' ';
				break;
				case 'w':
				echo "\033[33m$line \033[0m". ' ';
				break;  
				case 'i':
				echo "\033[36m$line \033[0m". ' ';
				break;    
				case 'l':
				echo $line . ' ';
				break;  
				default:
				echo $line . ' ';
				break;
			}
		}
	}
}

function thisIsAjax($request = false){
	if (!$request){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		}	
	}

	if (isset($request->server['HTTP_X_REQUESTED_WITH']) && strtolower($request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}

	return false;
}


function isFriendlyURL($url){
	if (stripos($url, 'index.php?route=') !== false){
		return false;
	}

	return true;
}

function thisIsUnroutedURI($request = false){	
	if (!$request){
		if (isset($_SERVER['REQUEST_URI'])){
			if (stripos($_SERVER['REQUEST_URI'], 'index.php?route=') !== false){
				return true;
			}
		}	
	}

	if (isset($request->server['REQUEST_URI'])){
		if (stripos($request->server['REQUEST_URI'], 'index.php?route=') !== false){
			return true;
		}
	}

	return false;
}

function doNotSetLanguageCookieSession($request = false){
	return (thisIsAjax($request) || thisIsUnroutedURI($request));
}

function error_handler($errno, $errstr, $errfile, $errline) {
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

	if (defined('IS_DEBUG') && IS_DEBUG) {
		if (is_cli()){
			echoLine('[ERR] ' . $error . ': ' . $errstr);
			echoLine('' . $errfile . ': ' . $errline);
			echoLine('');
		} else {
			echo '<pre style="white-space: pre-wrap"><b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b></pre><br />';
		}
	}

	return true;
}

set_error_handler('error_handler');
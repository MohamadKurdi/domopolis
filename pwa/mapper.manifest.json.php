<?php
	
	function loadJsonConfig($config){		
		$json = file_get_contents(dirname(__FILE__) . '/../system/config/' . $config . '.json');							
		return json_decode($json, true);		
	}
	
	header('Content-Type: application/json');
	
	$httpHOST = str_replace('www.', '', $_SERVER['HTTP_HOST']);
	
	$stores = loadJsonConfig('stores');
	$manifests = loadJsonConfig('manifest');
	
	if (isset($manifests[$httpHOST])){
		
		$manifestFile = $manifests[$httpHOST];
		
		if (!empty($_COOKIE['language']) && $_COOKIE['language'] == 'uk'){
			if (isset($manifests[$httpHOST  . '/ua'])){
				$manifestFile = $manifests[$httpHOST . '/ua'];
			}
		}
		
		if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ua/ua') !== false){
			if (isset($manifests[$httpHOST  . '/ua'])){
				$manifestFile = $manifests[$httpHOST . '/ua'];
			}
		}

		if (!empty($_COOKIE['language']) && $_COOKIE['language'] == 'ru'){
			if (isset($manifests[$httpHOST  . '/ru'])){
				$manifestFile = $manifests[$httpHOST . '/ru'];
			}
		}
		
		if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'ua/ru') !== false){
			if (isset($manifests[$httpHOST  . '/ru'])){
				$manifestFile = $manifests[$httpHOST . '/ru'];
			}
		}
		
		$manifestContent = file_get_contents(dirname(__FILE__) . '/manifest/' . $manifestFile);		
		die($manifestContent);
		
		} else {
		die ('we do not serve this shit');
	}
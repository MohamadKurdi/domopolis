<?php
	
	function loadJsonConfig($config){		
		$json = file_get_contents(dirname(__FILE__) . '/../system/config/' . $config . '.json');							
		return json_decode($json, true);		
	}
	
	header('Content-Type: application/json');
	
	$httpHOST = str_replace('www.', '', $_SERVER['HTTP_HOST']);
	
	$stores = loadJsonConfig('stores');
	$assetlinks = loadJsonConfig('assetlinks');
	
	if (isset($assetlinks[$httpHOST])){
		
		$assetlinkFile = $assetlinks[$httpHOST];
			
		
		$assetlinkContent = file_get_contents(dirname(__FILE__) . '/assetlinks/' . $assetlinkFile);		
		die($assetlinkContent);
		
		} else {
		die ('we do not serve this shit');
	}
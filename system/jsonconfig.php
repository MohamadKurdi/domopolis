<?php

function loadJsonConfig($config){
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
<?php

include_once (FindConfigPHP_Path()."config.php");

function FindConfigPHP_Path()
{
	$ret_path = "";
	$dir = dirname(__FILE__);
	$arr = explode(DIRECTORY_SEPARATOR, $dir);
	
	foreach($arr as $arr_item)
	{
		if (strtolower($arr_item) == "admin")
		{
			return $ret_path;
		}
		$ret_path .= $arr_item.DIRECTORY_SEPARATOR;
	}
	
	return $ret_path;
}

$db_visitors_host = 'localhost';
$db_visitors_username = DB_USERNAME;
$db_visitors_password = DB_PASSWORD;
$db_visitors_database = DB_DATABASE;
$db_visitors_table_prefix = DB_PREFIX;

?>



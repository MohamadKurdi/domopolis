<?php
class ModelModuleWeDoWebUtil extends Model {
	//src: http://stackoverflow.com/questions/3395798/mysql-check-if-a-column-exists-in-a-table-with-sql
	public function existColumn($table_name, $column_name)
	{
		$result = mysql_query("SHOW COLUMNS FROM " . DB_PREFIX . $table_name ." LIKE '" . $column_name . "'");
		return (mysql_num_rows($result)) ? true : false;
	}
	
	//src:http://stackoverflow.com/questions/9008299/check-if-mysql-table-exists-or-not
	public function existTable($table_name)
	{
		$result = mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . DB_PREFIX . $table_name . "'"));
		return (mysql_num_rows($result)) ? true : false;
	}
	
	public function removeColumn($table_name, $column_name)
	{
		$sql .= "ALTER TABLE " . DB_PREFIX . $table_name ." DROP " . $column_name;
	}
}
?>
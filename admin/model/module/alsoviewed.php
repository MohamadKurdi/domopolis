<?php 
class ModelModuleAlsoviewed extends Model {
	public function __construct($register) {
		if (!defined('IMODULE_ROOT')) define('IMODULE_ROOT', substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/');
		if (!defined('IMODULE_SERVER_NAME')) define('IMODULE_SERVER_NAME', substr((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER), 7, strlen((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER)) - 8));
		parent::__construct($register);
	}
	public function install(){
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `alsoviewed`
	 (`id` INT(11) NOT NULL AUTO_INCREMENT, 
	 `low` INT(11) NULL DEFAULT '0',
	 `high` INT(11) NULL DEFAULT '0',
	 `number` INT(11) NULL DEFAULT '0',
	 `date_added` DATETIME  NOT NULL DEFAULT '0000-00-00 00:00:00', 
	  PRIMARY KEY (`id`));");	
	}
	public function uninstall()	{
		  $this->db->query("DROP TABLE IF EXISTS `alsoviewed`");
	}

}
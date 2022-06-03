<?php
class ModelModuleAPRI extends Model {
	
	public function createTables(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "apri` (
				  `order_id` int(11) NOT NULL,
				  `date_added` datetime NOT NULL,
				  PRIMARY KEY (`order_id`)
				);";
		
		$query = $this->db->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "apri_unsubscribe` (
				  `md5_email` varchar(255) NOT NULL,
				  `date_added` datetime NOT NULL
				) DEFAULT CHARSET=utf8 ;";
		
		$query = $this->db->query($sql);
	}
}	
?>
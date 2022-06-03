<?php
class ModelModuleCategoryreviews extends Model {

	public function createFields() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_review` (`categoryreview_id` int(11) NOT NULL AUTO_INCREMENT, `category_id` int(11) NOT NULL, `customer_id` int(11) NOT NULL, `author` varchar(64) NOT NULL, `text` text NOT NULL, `rating` int(1) NOT NULL, `status` tinyint(1) NOT NULL DEFAULT '0', `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`categoryreview_id`), KEY `category_id` (`category_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
	}

}
?>

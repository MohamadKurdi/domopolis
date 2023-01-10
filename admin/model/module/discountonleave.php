<?php 
class ModelModuleDiscountOnLeave extends Model {

  	public function getSetting($group, $store_id = 0) {
	    $data = array(); 
	    $query = $this->db->query("SELECT * FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
	    foreach ($query->rows as $result) {
	      if (!$result['serialized']) {
	        $data[$result['key']] = $result['value'];
	      } else {
	        $data[$result['key']] = unserialize($result['value']);
	      }
	    } 
	    return $data;
	}
  
  	public function editSetting($group, $data, $store_id = 0) {
	    $this->db->query("DELETE FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
	    foreach ($data as $key => $value) {
	      if (!is_array($value)) {
	        $this->db->query("INSERT INTO setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
	      } else {
	        $this->db->query("INSERT INTO setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
	      }
	    }
	}
	
  	public function install($moduleName) {
			  $this->db->query("CREATE TABLE IF NOT EXISTS `oc_feedback` (
		`feedback_id` int(11) NOT NULL AUTO_INCREMENT,
		`sort_order` int(3) NOT NULL DEFAULT '0',
		`status` tinyint(1) NOT NULL,
		`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY (`feedback_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=5");
  	} 
  
  	public function uninstall($moduleName) {
		
  	}
	
  }
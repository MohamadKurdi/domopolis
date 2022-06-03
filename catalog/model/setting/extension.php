<?php
class ModelSettingExtension extends Model {
	function getExtensions($type) {
		$extension_data = $this->cache->get('extension.structure.'. $this->db->escape($type));
        // $extension_data = false;
		
		if (!$extension_data){
			$query = $this->db->query("SELECT * FROM extension WHERE `type` = '" . $this->db->escape($type) . "'");
			
			$extension_data = $query->rows;
			$this->cache->set('extension.structure.'. $this->db->escape($type), $extension_data);
        }

		return $extension_data;
	}
}
<?php
class ModelCataloglandingpage extends Model {
	public function getlandingpage($landingpage_id) {
		
		$query = $this->db->query("SELECT DISTINCT * FROM landingpage i LEFT JOIN landingpage_description id ON (i.landingpage_id = id.landingpage_id) LEFT JOIN landingpage_to_store i2s ON (i.landingpage_id = i2s.landingpage_id) WHERE i.landingpage_id = '" . (int)$landingpage_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'");
	
		return $query->row;
	}
	
	public function getlandingpages($data = array()) {
		$sql = "SELECT * FROM landingpage i LEFT JOIN landingpage_description id ON (i.landingpage_id = id.landingpage_id) LEFT JOIN landingpage_to_store i2s ON (i.landingpage_id = i2s.landingpage_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['igroup'])){
			$sql .= " AND i.igroup LIKE('" . $this->db->escape($data['igroup']) . "')";
		}
		
		$sql .= " AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC";

		$query = $this->db->non_cached_query($sql);
		
		return $query->rows;
	}
	
	public function getlandingpageLayoutId($landingpage_id) {
		$query = $this->db->query("SELECT * FROM landingpage_to_layout WHERE landingpage_id = '" . (int)$landingpage_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}	
}
?>
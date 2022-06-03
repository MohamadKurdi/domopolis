<?php
class ModelDesignBanner extends Model {	
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT *, bi.link as link, bid.link as link_o FROM banner_image bi 
		LEFT JOIN banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) 
		WHERE bi.banner_id = '" . (int)$banner_id . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as &$row){
			if (!empty($row['overload_image'])){
				$row['image'] = $row['overload_image'];
			}
			
			if (!empty($row['overload_image_sm'])){
				$row['image_sm'] = $row['overload_image_sm'];
			}
		}
		
		return $query->rows;
	}
}
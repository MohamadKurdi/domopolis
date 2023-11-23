<?php
class ModelDesignBanner extends Model {	
	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT *, bi.link as link, bid.link as link_o 
			FROM banner_image bi 
			LEFT JOIN banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) 
			WHERE bi.banner_id = '" . (int)$banner_id . "'
			AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY sort_order ASC");
		
		foreach ($query->rows as &$row){
			if (!empty($row['link_o'])){
				$row['link'] = $row['link_o'];
			}

			if (!empty($row['overload_image'])){
				$row['image'] = $row['overload_image'];
			}
			
			if (!empty($row['overload_image_sm'])){
				$row['image_sm'] = $row['overload_image_sm'];
			}
		}
		
		return $query->rows;
	}

	public function getBannersInfo($banner_ids) {
		$banner_ids = array_map('intval', $banner_ids);

		if ($banner_ids){
			$sql = "SELECT * FROM banner WHERE banner_id IN (" . implode(',', $banner_ids) . ") AND status = 1 ORDER BY sort_order DESC";
			$query = $this->db->query($sql);
			return $query->rows;
		}

		return [];
	}

	public function getBanners($banner_ids) {
		$data = [];

		$banners = $this->getBannersInfo($banner_ids);

		foreach ($banners as $banner){

			$data[$banner['banner_id']]= [
				'banner_id' => $banner['banner_id'],
				'class'  	=> $banner['class'],
				'class_sm'  => $banner['class_sm'],
				'images' 	=> $this->getBanner($banner['banner_id']),
			];
		}

		return $data;
	}
}
<?php
class ModelDesignBanner extends Model {
	public function addBanner($data) {
		$this->db->query("INSERT INTO banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

		$banner_id = $this->db->getLastId();

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {

				$banner_image['width'] 		= 0;
				$banner_image['height'] 	= 0;
				$banner_image['width_sm'] 	= 0;
				$banner_image['height_sm'] 	= 0;
				$banner_image['class'] 		= '';
				$banner_image['class_sm'] 	= '';
				$banner_image['block'] 		= '';
				$banner_image['block_sm'] 	= '';

				$this->db->query("INSERT INTO banner_image SET 
					banner_id 	= '" . (int)$banner_id . "', 
					link 		= '" .  $this->db->escape($banner_image['link']) . "', 
					image 		= '" .  $this->db->escape($banner_image['image']) . "', 
					image_sm 	= '" .  $this->db->escape($banner_image['image_sm']) . "',
					width 		= '" . (int)$banner_image['width'] . "',
					height 		= '" . (int)$banner_image['height'] . "',
					width_sm 	= '" . (int)$banner_image['width_sm'] . "',
					height_sm 	= '" . (int)$banner_image['height_sm'] . "',
					class 		= '" . $this->db->escape($banner_image['class'])  . "',
					class_sm 	= '" . $this->db->escape($banner_image['class_sm'])  . "',
					block 		= '" . (int)$banner_image['block'] . "',
					block_sm 		= '" . (int)$banner_image['block_sm'] . "'
					");

				$banner_image_id = $this->db->getLastId();

				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {				
					$this->db->query("INSERT INTO banner_image_description SET 
						banner_image_id 	= '" . (int)$banner_image_id . "', 
						language_id 		= '" . (int)$language_id . "', 
						banner_id 			= '" . (int)$banner_id . "', 
						title 				= '" .  $this->db->escape($banner_image_description['title']) . "', 
						link 				= '" .  $this->db->escape($banner_image_description['link']) . "'");
				}
			}
		}	

		return $banner_id;	
	}

	public function editBanner($banner_id, $data) {
		$this->db->query("UPDATE banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");

		$this->db->query("DELETE FROM banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$this->db->query("INSERT INTO banner_image SET 
					banner_id 	= '" . (int)$banner_id . "', 
					link 		= '" .  $this->db->escape($banner_image['link']) . "', 
					image 		= '" .  $this->db->escape($banner_image['image']) . "', 
					image_sm 	= '" .  $this->db->escape($banner_image['image_sm']) . "',
					width 		= '" . (int)$banner_image['width'] . "',
					height 		= '" . (int)$banner_image['height'] . "',
					width_sm 	= '" . (int)$banner_image['width_sm'] . "',
					height_sm 	= '" . (int)$banner_image['height_sm'] . "',
					class 		= '" . $this->db->escape($banner_image['class'])  . "',
					class_sm 	= '" . $this->db->escape($banner_image['class_sm'])  . "',
					block 		= '" . (int)$banner_image['block'] . "',
					block_sm 		= '" . (int)$banner_image['block_sm'] . "'
					");

				$banner_image_id = $this->db->getLastId();

				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {				
					$this->db->query("INSERT INTO banner_image_description SET 
						banner_image_id 	= '" . (int)$banner_image_id . "', 
						language_id 		= '" . (int)$language_id . "', 
						banner_id 			= '" . (int)$banner_id . "', 
						title 				= '" .  $this->db->escape($banner_image_description['title']) . "', 
						link 				= '" .  $this->db->escape($banner_image_description['link']) . "', 
						block_text 			= '', 
						button_text 		= '', 
						overload_image 		= '" .  $this->db->escape($banner_image_description['overload_image']) . "', 
						overload_image_sm 	= '" .  $this->db->escape($banner_image_description['overload_image_sm']) . "'");
				}
			}
		}			
	}

	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM banner WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
	}

	public function getBanner($banner_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM banner WHERE banner_id = '" . (int)$banner_id . "'");

		return $query->row;
	}

	public function getBanners($data = array()) {
		$sql = "SELECT * FROM banner";

		$sort_data = array(
			'name',
			'status'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}					

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getBannerImages($banner_id) {
		$banner_image_data = array();

		$banner_image_query = $this->db->query("SELECT * FROM banner_image WHERE banner_id = '" . (int)$banner_id . "'");

		foreach ($banner_image_query->rows as $banner_image) {
			$banner_image_description_data = array();

			$banner_image_description_query = $this->db->query("SELECT * FROM banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");

			foreach ($banner_image_description_query->rows as $banner_image_description) {			
				$banner_image_description_data[$banner_image_description['language_id']] = array(
					'title' 			=> $banner_image_description['title'],
					'link' 				=> $banner_image_description['link'],
					'block_text' 		=> $banner_image_description['block_text'],
					'button_text' 		=> $banner_image_description['button_text'],
					'overload_image' 	=> $banner_image_description['overload_image'],
					'overload_image_sm' => $banner_image_description['overload_image_sm'],
				);
			}

			$banner_image_data[] = array(
				'banner_image_description' => $banner_image_description_data,
				'link'                     => $banner_image['link'],
				'image'                    => $banner_image['image'],
				'image_sm'                 => $banner_image['image_sm'],
				'width' 					=> $banner_image['width'],
				'height' 					=> $banner_image['height'],
				'width_sm' 					=> $banner_image['width_sm'],
				'height_sm' 				=> $banner_image['height_sm'],
				'class' 					=> $banner_image['class'],
				'class_sm' 					=> $banner_image['class_sm'],
				'block' 					=> $banner_image['block'],
				'block_sm' 					=> $banner_image['block_sm'],
			);
		}

		return $banner_image_data;
	}

	public function getTotalBanners() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM banner");

		return $query->row['total'];
	}	
}
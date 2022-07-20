<?php
class ModelToolPathManager extends Model {

	public function getFullCategoryPath($category_id, $breadcrumbs_mode = false){		
		$path = [];

		$category = $this->db->query("SELECT category_id, parent_id FROM category WHERE category_id = '" . $category_id. "'")->row;
			
		while ($category['parent_id']){		
			array_unshift($path, $category['parent_id']);
			$category = $this->db->query("SELECT category_id, parent_id FROM category WHERE category_id = '" . $category['parent_id']. "'")->row;
		}

		if (!$path || !count($path)) return null;

		if($breadcrumbs_mode) {
			return implode('_', $path);
		} else {
			return array('path' => $path);
		}

	}

 
	public function getFullProductPath($product_id, $breadcrumbs_mode = false) {
		$path_mode = 'full_product_path_mode';
		if($breadcrumbs_mode) {
			$path_mode = 'full_product_path_bc_mode';
		}
		
		if (!$this->config->get($path_mode))
			return null;
		if ($this->config->get($path_mode) == '3') {
			$manufacturer_id = $this->db->query("SELECT manufacturer_id FROM product WHERE product_id = '" . (int)$product_id . "'")->rows[0]['manufacturer_id'];
			
			if($manufacturer_id) {
				if($breadcrumbs_mode) {
					return array('manufacturer_id' => $manufacturer_id);
					return $manufacturer_id;
				} else {
					return array('manufacturer_id' => $manufacturer_id);
				}
			}
		}
		
		$path = array();
		$categories = $this->db->query("SELECT c.category_id, c.parent_id 
			FROM product_to_category p2c 
			LEFT JOIN category c ON (p2c.category_id = c.category_id) 
			LEFT JOIN category_to_store c2s ON (p2c.category_id = c2s.category_id)
			WHERE 
			c2s.store_id = '" . (int)$this->config->get('config_store_id') ."' 
			AND product_id = '" . (int)$product_id . "'")->rows;
		
		foreach($categories as $key => $category)
		{
			$path[$key] = '';
			if (!$category) continue;
			$path[$key] = $category['category_id'];
			
			while ($category['parent_id']){
				$path[$key] = $category['parent_id'] . '_' . $path[$key];
				$category = $this->db->query("SELECT category_id, parent_id FROM category WHERE category_id = '" . $category['parent_id']. "'")->row;
			}
			
			$path[$key] = $path[$key];
			$banned_cats = $this->config->get('full_product_path_categories');
			
			if(count($banned_cats) && (count($categories) > 1))
			{
				//if(preg_match('#[_=](\d+)&$#', $path[$key], $cat))
				if(preg_match('#[_=](\d+)$#', $path[$key], $cat))
				{
					if(in_array($cat[1], $banned_cats))
						unset($path[$key]);
				}
			}
		}
		
		if (!count($path)) return null;

		// wich one is the largest ?
		$whichone = array_map('strlen', $path);
		asort($whichone);
		$whichone = array_keys($whichone);
		
		if ($this->config->get($path_mode) == '2')
			$whichone = array_pop($whichone);
			
		else $whichone = array_shift($whichone);
		
		$path = $path[$whichone];
		
		if ((int) $this->config->get('full_product_path_depth')) {
			$path_parts  = explode('_', $path);
			while (count($path_parts) > (int) $this->config->get('full_product_path_depth')) {
				array_pop($path_parts);
			}
			$path = implode('_', $path_parts);
		}
		
		if($breadcrumbs_mode) {
			return $path;
		} else {
			return array('path' => $path);
		}
	}

}
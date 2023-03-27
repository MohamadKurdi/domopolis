<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		if (!$category_data = $this->cache->get($this->registry->createCacheQueryStringData(__METHOD__, [$category_id]))){

			$sql = "SELECT DISTINCT *, IFNULL(cd.menu_name, cd.name) as name FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) "; 
			}

			$sql .= " WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND c.status = '1'";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}

			$query = $this->db->query($sql);

			$category_data = $query->row;

			$this->cache->set($this->registry->createCacheQueryStringData(__METHOD__, [$category_id]), $category_data);
		}

		return $category_data;
	}

	public function getMostViewedCategories($limit){
		if ((int)$limit < 0){
			$limit = 0;
		} else {
			$limit = (int)$limit;
		}

		$sql = "SELECT *, IFNULL(cd.menu_name, cd.name) as name 
		FROM category c 
		LEFT JOIN category_description cd ON (c.category_id = cd.category_id) ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) ";
		}

		$sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " 	AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
		}

		$sql .= " AND c.status = '1' ";
		$sql .= " AND ((c.viewed > 0 AND c.final = 1) OR c.homepage = 1)";		
		if (defined('PRESENT_UA')){
			$sql .= " AND c.category_id <> " . PRESENT_UA;
		}		
		$sql .= " ORDER BY (c.homepage = 1) DESC, c.viewed DESC";
		
		if ($limit){
			$sql .= " LIMIT 0, $limit";
		}

		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getCategoriesIntersections($category_id, $parent_id = 0){				

		$sql = "SELECT DISTINCT cp1.path_id, c1.*, cd1.*";
		$sql .= " FROM  category_path cp1 ";			
		$sql .= " LEFT JOIN category c1 ON (cp1.path_id = c1.category_id)";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " LEFT JOIN category_to_store c2s ON (cp1.path_id = c2s.category_id)";
		}

		$sql .= " LEFT JOIN category_description cd1 ON (cp1.path_id = cd1.category_id)";
		$sql .= " WHERE cp1.category_id IN ( ";
		$sql .= " SELECT category_id FROM product_to_category p2c WHERE product_id IN ( ";
			$sql .= " SELECT
			DISTINCT p.product_id
			FROM
			category_path cp
			LEFT JOIN product_to_category p2c ON (cp.category_id = p2c.category_id)
			LEFT JOIN product p ON (p2c.product_id = p.product_id)
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) ";
			}

			$sql .= " WHERE
			pd.language_id = '2'
			AND p.status = '1'
			AND p.date_available <= NOW() ";

			if (!$this->config->get('config_single_store_enable')){
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			}

			$sql .= " AND cp.path_id = '" . (int)$category_id . "'";

			if ($this->config->get('config_special_category_id') && (int)$category_id == (int)$this->config->get('config_special_category_id')) {
				$sql .= "  AND p.product_id IN ( ";
				$sql .= " (SELECT product_id FROM product_special ps WHERE ps.price < p.price AND ps.price > 0 AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND (store_id = '" . (int)$this->config->get('config_store_id') . "' OR store_id = -1))";
				$sql .= " )";	 	
			}		

			$sql .= " GROUP BY product_id	
		) ";			

		$sql .= "  ) ";
		$sql .= "  AND cp1.path_id NOT IN (SELECT path_id FROM category_path cp3 WHERE category_id = '" . (int)$category_id . "')";
		$sql .= "  AND c1.parent_id = '" . (int)$parent_id . "'";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= "  AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
		}

		$sql .= "  AND cp1.category_id <> '" . (int)$category_id . "'";
		$sql .= "  AND c1.status = 1";
		$sql .= "  AND c1.exclude_from_intersections <> 1";
		$sql .= "  AND c1.intersections = 0";
		$sql .= "  AND cd1.language_id = 2";
		$sql .= "  ORDER BY c1.sort_order ASC, LCASE(cd1.name) DESC";					

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAllCategoriesEvenDisabled() {
		$sql = "SELECT *, cd.name FROM category c 
		LEFT JOIN category_description cd ON (c.category_id = cd.category_id) ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) ";
		}

		$sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
		}

		$sql .= " ORDER BY LCASE(cd.name)";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAllCategories() {
		$sql = "SELECT *, cd.name FROM category c 
		LEFT JOIN category_description cd ON (c.category_id = cd.category_id) ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) ";
		}

		$sql .= " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$sql .= " AND c.status = 1 ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
		}
		
		$sql .= " ORDER BY LCASE(cd.name)";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategories($parent_id = 0, $limit = 0) {

		if ($parent_id == 0){
			$virtual_parent_id = -3;
		} else {
			$virtual_parent_id = $parent_id;
		}

		$sql = "SELECT *, IFNULL(cd.menu_name, cd.name) as name 
		FROM category c 
		LEFT JOIN category_description cd ON (c.category_id = cd.category_id) ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) ";
		}

		$sql .= " WHERE (c.parent_id = '" . (int)$parent_id . "' OR c.virtual_parent_id = '" . (int)$virtual_parent_id . "') 
		AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		if (!$this->config->get('config_single_store_enable')){
			$sql .= " 	AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
		}

		$sql .= " AND c.status = '1' ";
		$sql .= "ORDER BY c.sort_order ASC, LCASE(cd.name)";
		
		if ((int)$limit < 0){
			$limit = 0;
		} else {
			$limit = (int)$limit;
		}
		
		if ($limit){
			$sql .= " LIMIT 0, $limit";
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}				
	
	public function getCategoryFilters($category_id) {
		$implode = array();
		
		$query = $this->db->query("SELECT filter_id FROM category_filter WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}
		
		
		$filter_group_data = array();
		
		if ($implode) {
			$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM filter f LEFT JOIN filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");
			
			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();
				
				$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM filter f LEFT JOIN filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");
				
				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']			
					);
				}
				
				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);	
				}
			}
		}
		
		return $filter_group_data;
	}
	
	public function getCategoryActions($category_id) {						
		$sql = "SELECT a.actions_id, a.*, ad.* FROM category_to_actions c2a
		LEFT JOIN actions a ON (a.actions_id = c2a.actions_id)
		LEFT JOIN actions_description ad ON (a.actions_id = ad.actions_id)
		LEFT JOIN actions_to_store a2s ON (a2s.actions_id = a.actions_id)
		WHERE category_id = '" . (int)$category_id . "' 
		AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
		AND a.status = 1
		AND a.date_start <= UNIX_TIMESTAMP()
		AND a.date_end >= UNIX_TIMESTAMP()";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}
	
	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM category c LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM category c LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
	
	public function getFullProductPath($product_id) {
		$path = '';
		$category = $this->db->query("SELECT c.category_id, c.parent_id FROM product_to_category p2c LEFT JOIN category c ON (p2c.category_id = c.category_id) WHERE product_id = '" . (int)$product_id . "'")->row;
		if (!$category) return '';
		$path = $category['category_id'];
		while ($category['parent_id']){
			$path = $category['parent_id'] . '_' . $path;
			$category = $this->db->query("SELECT category_id, parent_id FROM category WHERE category_id = '" . $category['parent_id']. "'")->row;
		}
		return $path;
	}
	
	public function getFullCategoryPath($category_id) {
		$path = '';
		$category = $this->db->query("SELECT category_id, parent_id FROM category WHERE category_id = '" . (int)$category_id . "'")->row;
		if (!$category) return '';
		$path = $category['category_id'];
		while ($category['parent_id']){
			$path = $category['parent_id'] . '_' . $path;
			$category = $this->db->query("SELECT category_id, parent_id FROM category WHERE category_id = '" . $category['parent_id']. "'")->row;
		}
		return $path;
	}
	
	public function getAttributesByCategory($category_id) {
		$result = array();
		$query = $this->db->query("SELECT attribute_id FROM attributes_category  WHERE category_id=" . $category_id);
		foreach($query->rows as $row) {
			$result[] = $row['attribute_id'];
		}
		return $result;
	}		
	
	public function getAttributesSimilarByCategory($category_id) {
		$result = array();
		$query = $this->db->query("SELECT attribute_id FROM attributes_similar_category  WHERE category_id=" . $category_id);
		foreach($query->rows as $row) {
			$result[] = $row['attribute_id'];
		}
		return $result;
	}

	public function getAttributesToProduct($category_ids) {
		$result = array();
		$query = $this->db->query("SELECT DISTINCT attribute_id FROM attributes_category  WHERE category_id IN (" . implode(", ", $category_ids) . ")");
		foreach ($query->rows as $row) {
			$attr_id = $row['attribute_id'];
			$sub_query = $this->db->query("SELECT attribute_id, name FROM attribute_description WHERE attribute_id= '" . (int)$attr_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
			foreach($sub_query->rows as $row2) {
				$result[] = array('attribute_id' => $row2['attribute_id'], 'name' => $row2['name']);	
			}			
		}
		return $result;
	}	

	public function getRelatedCategories($category_id, $limit) {
		$query = $this->db->query("SELECT DISTINCT(cr.related_category_id), cd.* FROM category_related cr LEFT JOIN category c ON (c.category_id = cr.related_category_id) LEFT JOIN category_to_store c2s ON (c2s.category_id = cr.related_category_id) LEFT JOIN category_description cd ON (cd.category_id = cr.related_category_id) WHERE cr.category_id = '" . (int)$category_id . "' AND cr.related_category_id <> '" . (int)$category_id . "' AND c.status = 1 AND c2s.store_id = '" . $this->config->get('config_store_id') . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT " . $limit);

		return $query->rows;
	}
	
	public function getMenuContentByCategoryId ($categoryId, $data = array()) {
		$sql = "SELECT * FROM `category_menu_content` WHERE `category_id` = ".(int)$categoryId;
		if (isset($data['language_id']) && $data['language_id']) {
			$sql .= " AND `language_id` = ".(int)$data['language_id'];
		}
		
		if (isset($data['sort_order']) && $data['sort_order']) {
			if ($data['sort_order'] == 'ASC' || $data['sort_order'] == 'DESC') {
				$sql .= " ORDER BY `sort_order` ".$data['sort_order'];
			}
		}
		
		$r = $this->db->query($sql);
		return $r->rows;
	}
}		
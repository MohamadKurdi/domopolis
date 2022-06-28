<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->db->query("INSERT INTO category SET 
			parent_id 				= '" . (int)$data['parent_id'] . "', 
			virtual_parent_id 		= '" . (int)$data['virtual_parent_id'] . "', 
			`top` 					= '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', 
			`column` 				= '" . (int)$data['column'] . "', 
			sort_order 				= '" . (int)$data['sort_order'] . "', 
			status 					= '" . (int)$data['status'] . "', 
			tnved 					= '" . $this->db->escape($data['tnved']) . "', 
			menu_icon 				= '" . $this->db->escape($data['menu_icon']) . "', 
			overprice 				= '" . $this->db->escape($data['overprice']) . "', 
			google_category_id 		= '" . (int)$data['google_category_id'] . "', 
			separate_feeds 			= '" . (int)$data['separate_feeds'] . "', 
			no_general_feed 		= '" . (int)$data['no_general_feed'] . "', 
			deletenotinstock 		= '" . (int)$data['deletenotinstock'] . "', 
			intersections 			= '" . (int)$data['intersections'] . "', 
			default_weight 			= '" . (float)$data['default_weight'] . "', 
			default_weight_class_id = '" . (int)$data['default_weight_class_id'] . "', 
			default_length 			= '" . (float)$data['default_length'] . "', 
			default_width 			= '" . (float)$data['default_width'] . "', 
			default_height 			= '" . (float)$data['default_height'] . "', 
			default_length_class_id = '" . (int)$data['default_length_class_id'] . "', 
			priceva_enable 			= '" . (int)$data['priceva_enable'] . "', 
			submenu_in_children 	= '" . (int)$data['submenu_in_children'] . "', 
			amazon_sync_enable 		= '" . (int)$data['amazon_sync_enable'] . "', 
			amazon_last_sync 		= '" . $this->db->escape($data['amazon_last_sync']) . "', 
			amazon_category_id 		= '" . $this->db->escape($data['amazon_category_id']) . "', 
			amazon_category_name 	= '" . $this->db->escape($data['amazon_category_name']) . "', 
			amazon_parent_category_id 		= '" . $this->db->escape($data['amazon_parent_category_id']) . "', 
			amazon_parent_category_name 	= '" . $this->db->escape($data['amazon_parent_category_name']) . "',
			amazon_final_category 			= '" . (int)$data['amazon_final_category'] . "',
			amazon_can_get_full 			= '" . (int)$data['amazon_can_get_full'] . "',
			yandex_category_name 	= '" . $this->db->escape($data['yandex_category_name']) . "',
			amazon_overprice_rules 	= '" . $this->db->escape($data['amazon_overprice_rules']) . "', 
			date_modified 			= NOW(), 
			date_added 				= NOW()");
		
		$category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', alternate_name = '" . $this->db->escape($value['alternate_name']) . "', menu_name = '" . $this->db->escape($value['menu_name']) . "', all_prefix = '" . $this->db->escape($value['all_prefix']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', seo_title = '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', seo_h1 = '" . ((isset($value['seo_h1']))?($this->db->escape($value['seo_h1'])):'') . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
			// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;
		
		$query = $this->db->query("SELECT * FROM `category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");
		
		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
			
			$level++;
		}
		
		$this->db->query("INSERT INTO `category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
		
		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}
		
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (isset($data['category_action'])) {
			foreach ($data['category_action'] as $action_id) {
				$this->db->query("INSERT INTO category_to_actions SET category_id = '" . (int)$category_id . "', actions_id = '" . (int)$action_id . "'");
			}
		}
		
			// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		if ($data['keyword']) {
			foreach ($data['keyword'] as $language_id => $keyword) {
				if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
			}
		}
		
		if (isset($data['related_category'])) {
			foreach ($data['related_category'] as $related_category) {
				$this->db->query(("INSERT INTO category_related (related_category_id, category_id) VALUES  (" . (int)$related_category . ", " . (int)$category_id . ")"));
			}
		}
		
		if (isset($data['attributes_category'])) {
			foreach ($data['attributes_category'] as $attributes_category) {
				$this->db->query(("INSERT INTO attributes_category VALUES (" . (int)$attributes_category . ", " . (int)$category_id . ")"));
			}
		}
		
		if (isset($data['attributes_similar_category'])) {
			foreach ($data['attributes_similar_category'] as $attributes_similar_category) {
				$this->db->query(("INSERT INTO attributes_similar_category VALUES (" . (int)$attributes_similar_category . ", " . (int)$category_id . ")"));
			}
		}

		$this->load->model('kp/reward');
		$this->model_kp_reward->editReward($category_id, 'c', $data);
		
		
		
		return $category_id;
	}
	
	public function editCategory($category_id, $data) {
		$this->db->query("UPDATE category SET 
			parent_id 			= '" . (int)$data['parent_id'] . "', 
			virtual_parent_id 	= '" . (int)$data['virtual_parent_id'] . "',  
			`top` 				= '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', 
			`column` 			= '" . (int)$data['column'] . "', 
			sort_order 			= '" . (int)$data['sort_order'] . "', 
			status 				= '" . (int)$data['status'] . "', 
			tnved 				= '" . $this->db->escape($data['tnved']) . "', 
			menu_icon 			= '" . $this->db->escape($data['menu_icon']) . "', 
			overprice 			= '" . $this->db->escape($data['overprice']) . "', 
			google_category_id 	= '" . (int)$data['google_category_id'] . "', 
			separate_feeds 		= '" . (int)$data['separate_feeds'] . "', 
			no_general_feed 	= '" . (int)$data['no_general_feed'] . "', 
			deletenotinstock 	= '" . (int)$data['deletenotinstock'] . "', 
			intersections 		= '" . (int)$data['intersections'] . "', 
			default_weight 		= '" . (float)$data['default_weight'] . "', 
			default_weight_class_id = '" . (int)$data['default_weight_class_id'] . "', 
			default_length 			= '" . (float)$data['default_length'] . "', 
			default_width 			= '" . (float)$data['default_width'] . "', 
			default_height 			= '" . (float)$data['default_height'] . "', 
			default_length_class_id = '" . (int)$data['default_length_class_id'] . "', 
			priceva_enable 			= '" . (int)$data['priceva_enable'] . "', 
			submenu_in_children 	= '" . (int)$data['submenu_in_children'] . "',
			amazon_sync_enable 		= '" . (int)$data['amazon_sync_enable'] . "', 
			amazon_last_sync 		= '" . $this->db->escape($data['amazon_last_sync']) . "', 
			amazon_category_id 		= '" . $this->db->escape($data['amazon_category_id']) . "', 
			amazon_category_name 	= '" . $this->db->escape($data['amazon_category_name']) . "', 
			yandex_category_name 	= '" . $this->db->escape($data['yandex_category_name']) . "',
			amazon_overprice_rules	= '" . $this->db->escape($data['amazon_overprice_rules']) . "', 
			amazon_parent_category_id 		= '" . $this->db->escape($data['amazon_parent_category_id']) . "', 			
			amazon_final_category 			= '" . (int)$data['amazon_final_category'] . "',
			amazon_can_get_full 			= '" . (int)$data['amazon_can_get_full'] . "',
			date_modified = NOW() 
			WHERE
			category_id = '" . (int)$category_id . "'");
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		
		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', alternate_name = '" . $this->db->escape($value['alternate_name']) . "', menu_name = '" . $this->db->escape($value['menu_name']) . "', all_prefix = '" . $this->db->escape($value['all_prefix']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', seo_title = '" . ((isset($value['seo_title']))?($this->db->escape($value['seo_title'])):'') . "', seo_h1 = '" . ((isset($value['seo_h1']))?($this->db->escape($value['seo_h1'])):'') . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		
		$this->db->query("DELETE FROM category_menu_content WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['copyrussian']) && $data['copyrussian'] == 1){
			
			$rucontent = $data['category_menu_content'][2];
			$lquery = $this->db->query("SELECT DISTINCT language_id FROM language WHERE language_id <> 26");
			
			$lcodes = array();
			foreach ($lquery->rows as $lrow){
				$lcodes[] = $lrow['language_id'];
			} 
			
			foreach ($lcodes as $language_id) {
				
				foreach ($rucontent as $value){
					
					$this->db->query("INSERT INTO category_menu_content SET 
						category_id = '" . (int)$category_id . "', 
						language_id = '" . (int)$language_id . "', 
						title = '" . $this->db->escape($value['title']) . "', 
						content = '" . $this->db->escape($value['content']) . "',
						href = '" . $this->db->escape($value['href']) . "', 
						image = '" . $this->db->escape($value['image']) . "', 
						width = '" . (int)$value['width'] . "', 
						height = '" . (int)$value['height'] . "', 
						standalone = '" . (int)$value['standalone'] . "', 
						sort_order = '" . (int)$value['sort_order'] . "'");
					
				}
				
			}
			
			
		} else {
			
			foreach ($data['category_menu_content'] as $language_id => $contents) {
				
				foreach ($contents as $value){
					
					$this->db->query("INSERT INTO category_menu_content SET 
						category_id = '" . (int)$category_id . "', 
						language_id = '" . (int)$language_id . "', 
						title = '" . $this->db->escape($value['title']) . "', 
						content = '" . $this->db->escape($value['content']) . "',
						href = '" . $this->db->escape($value['href']) . "', 
						image = '" . $this->db->escape($value['image']) . "', 
						width = '" . (int)$value['width'] . "', 
						height = '" . (int)$value['height'] . "', 
						standalone = '" . (int)$value['standalone'] . "', 
						sort_order = '" . (int)$value['sort_order'] . "'");
					
				}
				
			}
			
			
		}
			// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");
		
		if ($query->rows) {
			foreach ($query->rows as $category_path) {
					// Delete the path below the current one
				$this->db->query("DELETE FROM `category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");
				
				$path = array();
				
					// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
				
				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}
				
					// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");
				
				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}
				
					// Combine the paths with a new level
				$level = 0;
				
				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");
					
					$level++;
				}
			}
		} else {
				// Delete the path below the current one
			$this->db->query("DELETE FROM `category_path` WHERE category_id = '" . (int)$category_id . "'");
			
				// Fix for records with no paths
			$level = 0;
			
			$query = $this->db->query("SELECT * FROM `category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
			
			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
				
				$level++;
			}
			
			$this->db->query("REPLACE INTO `category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}
		
		$this->db->query("DELETE FROM category_filter WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}		
		}
		
		$this->db->query("DELETE FROM category_to_store WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_store'])) {		
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM category_to_actions WHERE category_id = '" . (int)$category_id . "'");
		if (isset($data['category_action'])) {
			foreach ($data['category_action'] as $action_id) {
				$this->db->query("INSERT INTO category_to_actions SET category_id = '" . (int)$category_id . "', actions_id = '" . (int)$action_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
		
		if ($data['keyword']) {
			foreach ($data['keyword'] as $language_id => $keyword) {
				if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
			}
		}
		
		$this->db->query("DELETE FROM category_related WHERE category_id=" . (int)$category_id);	
		
		if (isset($data['related_category'])) {
			foreach ($data['related_category'] as $related_category) {
				$this->db->query(("INSERT INTO category_related (related_category_id, category_id) VALUES  (" . (int)$related_category . ", " . (int)$category_id . ")"));
			}
		}
		
		$this->db->query("DELETE FROM attributes_category WHERE category_id=" . (int)$category_id);	
		if (isset($data['attributes_category'])) {
			foreach ($data['attributes_category'] as $attributes_category) {
				$this->db->query(("INSERT INTO attributes_category VALUES (" . (int)$attributes_category . ", " . (int)$category_id . ")"));
			}
		}
		
		$this->db->query("DELETE FROM attributes_similar_category WHERE category_id=" . (int)$category_id);	
		if (isset($data['attributes_similar_category'])) {
			foreach ($data['attributes_similar_category'] as $attributes_similar_category) {
				$this->db->query(("INSERT INTO attributes_similar_category VALUES (" . (int)$attributes_similar_category . ", " . (int)$category_id . ")"));
			}
		}
		
		$this->load->model('kp/reward');
		$this->model_kp_reward->editReward($category_id, 'c', $data);
		
		

		return (int)$category_id;
	}

	public function quickEditCategory($category_id, $column, $value, $lang_id=null, $data=null) {
		$editable = array('image', 'name', 'column', 'top', 'status', 'sort_order');
		$result = false;
		if (in_array($column, $editable)) {
			if (in_array($column, array('column', 'top', 'sort_order', 'status')))
				$result = $this->db->query("UPDATE category SET `" . $column . "` = '" . (int)$value . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");
			else if ($column == "image")
				$result = $this->db->query("UPDATE category SET `" . $column . "` = '" . $this->db->escape($value) . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");
			else if ($column == "name")
				$result = $this->db->query("UPDATE category_description SET name = '" . $this->db->escape($value) . "' WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$lang_id . "'");
		} else if ($column == 'seo') {
			$this->db->query("DELETE FROM url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
			if (!empty($value))
				$result = $this->db->query("INSERT INTO url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($value) . "'");
			else
				$result = 1;
		} else if ($column == 'filter') {
			$this->db->query("DELETE FROM category_filter WHERE category_id = '" . (int)$category_id . "'");

			if (isset($data['i_f'])) {
				foreach ((array)$data['i_f'] as $filter_id) {
					$this->db->query("INSERT INTO category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
			$result = 1;
		} else if ($column == 'store') {
			$this->db->query("DELETE FROM category_to_store WHERE category_id = '" . (int)$category_id . "'");

			if (isset($data['i_s'])) {
				foreach ((array)$data['i_s'] as $store_id) {
					$this->db->query("INSERT INTO category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
			$result = 1;
		}
		
		return $result;
	}

	public function urlAliasExists($category_id, $keyword) {
		$query = $this->db->query("SELECT 1 FROM url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND query <> 'category_id=" . (int)$category_id . "'");

		if ($query->row) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM category_path WHERE category_id = '" . (int)$category_id . "'");
		
		$query = $this->db->query("SELECT * FROM category_path WHERE path_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {	
			$this->deleteCategory($result['category_id']);
		}
		
		$this->db->query("DELETE FROM category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_menu_content WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_related WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM attributes_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM attributes_similar_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
		
		
		
		if(!$this->config->get('sphinx_config_index_type')) {
			return;
		}
		
		$this->load->model('catalog/sphinxsearch');
		$this->model_catalog_sphinxsearch->delete('categories', $category_id);
		
		
	} 
	
	public function getRelatedCategories($category_id) {
		$query = $this->db->query("SELECT * FROM category_related WHERE category_id = '" . (int)$category_id . "'");
		
		$related_categories = array();
		
		foreach ($query->rows as $row)
		{
			$related_categories[] = $row['related_category_id'];
		}
		
		return $related_categories;		
	}
	
	public function getFeeds($category_id){
		$query = $this->db->query("SELECT * FROM yandex_feeds WHERE entity_type = 'c' AND entity_id = '" . (int)$category_id . "'");
		
		if ($query->num_rows){
			return $query->rows;
		} else {
			return false;
		}
	}
	
	public function repairAmazonTree(){

		$this->db->query();

	}
	
		// Function to repair any erroneous categories that are not in the category path table.
	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM category WHERE parent_id = '" . (int)$parent_id . "'");
		
		foreach ($query->rows as $category) {
				// Delete the path below the current one
			$this->db->query("DELETE FROM `category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");
			
				// Fix for records with no paths
			$level = 0;
			
			$query = $this->db->query("SELECT * FROM `category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");
			
			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
				
				$level++;
			}
			
			$this->db->query("REPLACE INTO `category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");
			
			$this->repairCategories($category['category_id']);
		}
	}
	
	public function getCategoriesTree() {
		$query = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1'");
		
		return $query->rows;
	}
	
	public function getAllCategories() {
		$query = $this->db->query("SELECT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ORDER BY c.parent_id, c.sort_order, cd.name");
		
		$category_data = array();
		foreach ($query->rows as $row) {
			$category_data[$row['parent_id']][$row['category_id']] = $row;
		}
		
		return $category_data;
	}
	
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			(SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM category_path cp LEFT JOIN category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT cd1.name FROM category_description cd1 WHERE cd1.category_id = c.virtual_parent_id AND cd1.language_id = '2' ) AS virtual_path FROM category c LEFT JOIN category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");				
			
			return $query->row;
		} 
		
		public function getKeyWords($category_id) {
			$keywords = array();
			
			$query = $this->db->query("SELECT * FROM url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$keywords[$result['language_id']] = $result['keyword'];					
			}
			
			return $keywords;
		}
		
		public function getCategories($data) {
			$sql = "SELECT cp.category_id AS category_id, c.tnved, c.priceva_enable, c.deletenotinstock, c.intersections, c.google_category_id, cd2.menu_name, cd2.alternate_name, (SELECT menu_icon FROM category c4 WHERE c4.category_id = cp.category_id) as menu_icon, (SELECT image FROM category c5 WHERE c5.category_id = cp.category_id) as image, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM category_path cp LEFT JOIN category c ON (cp.path_id = c.category_id) LEFT JOIN category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			}

			if (isset($data['filter_parent_id'])) {
				$sql .= " AND c.parent_id = '" . (int)$data['filter_parent_id'] . "'";
			}
			
			$sql .= " GROUP BY cp.category_id ORDER BY name";
			
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
		
		public function getCategoryDescriptions($category_id) {
			$category_description_data = array();
			
			$query = $this->db->query("SELECT * FROM category_description WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$category_description_data[$result['language_id']] = array(
					'name'             => $result['name'],
					'alternate_name'             => $result['alternate_name'],
					'menu_name'        => $result['menu_name'],
					'all_prefix'        => $result['all_prefix'],
					'meta_keyword'     => $result['meta_keyword'],
					'seo_title'        => $result['seo_title'],
					'seo_h1' => $result['seo_h1'],
					'meta_description' => $result['meta_description'],
					'description'      => $result['description']
				);
			}
			
			return $category_description_data;
		}	
		
		public function getCategoryMenuContent($category_id) {
			$category_menu_content_data = array();
			
			$query = $this->db->query("SELECT * FROM category_menu_content WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				
				$category_menu_content_data[$result['language_id']][$result['category_menu_content_id']] = $result;
				
			}
			
			return $category_menu_content_data;
		}	
		
		
		public function getGoogleCategoryByID($google_base_category_id){
			$query = $this->db->query("SELECT * FROM google_base_category WHERE google_base_category_id = '" . (int)$google_base_category_id . "'");
			
			if ($query->num_rows){
				return $query->row['name'];
			} else {
				return '';
			}
			
		}
		
		public function getGoogleCategoryByName($filter){
			$query = $this->db->query("SELECT * FROM google_base_category WHERE LOWER(name) LIKE ('%" . $this->db->escape(mb_strtolower($filter)) . "%') ORDER BY name DESC LIMIT 30");
			
			return $query->rows;			
			
		}
		
		
		public function getCategoryFilters($category_id) {
			$category_filter_data = array();
			
			$query = $this->db->query("SELECT * FROM category_filter WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$category_filter_data[] = $result['filter_id'];
			}
			
			return $category_filter_data;
		}
		
		public function getCategoryActions($category_id) {
			$category_actions_data = array();
			
			$query = $this->db->query("SELECT * FROM category_to_actions WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$category_actions_data[] = $result['actions_id'];
			}
			
			return $category_actions_data;
		}
		
		public function getCategoryStores($category_id) {
			$category_store_data = array();
			
			$query = $this->db->query("SELECT * FROM category_to_store WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$category_store_data[] = $result['store_id'];
			}
			
			return $category_store_data;
		}
		
		public function getCategoryLayouts($category_id) {
			$category_layout_data = array();
			
			$query = $this->db->query("SELECT * FROM category_to_layout WHERE category_id = '" . (int)$category_id . "'");
			
			foreach ($query->rows as $result) {
				$category_layout_data[$result['store_id']] = $result['layout_id'];
			}
			
			return $category_layout_data;
		}
		
		public function getTotalCategories() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM category");
			
			return $query->row['total'];
		}	

		public function getTotalCategoriesAmazonFinal() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM category WHERE amazon_final_category = 1");
			
			return $query->row['total'];
		}

		public function getTotalCategoriesEnableLoad() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM category WHERE amazon_sync_enable = 1");
			
			return $query->row['total'];
		}

		public function getTotalCategoriesEnableFullLoad() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM category WHERE amazon_can_get_full = 1 AND status = 1");
			
			return $query->row['total'];
		}
		
		public function getTotalProductInCategory($category_id) {
			$query = $this->db->query("SELECT COUNT(DISTINCT product_id) AS total FROM product_to_category WHERE category_id = '" .(int)$category_id. "'");
			
			return $query->row['total'];
		}	

		public function getTotalFilledProductInCategory($category_id) {
			$query = $this->db->query("SELECT COUNT(DISTINCT p2c.product_id) AS total FROM product_to_category p2c LEFT JOIN product p ON (p2c.product_id = p.product_id) WHERE p.filled_from_amazon = 1 AND category_id = '" .(int)$category_id. "'");
			
			return $query->row['total'];
		}	
		
		public function getTotalCategoriesByImageId($image_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM category WHERE image_id = '" . (int)$image_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalCategoriesByLayoutId($layout_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
			
			return $query->row['total'];
		}	
		
		public function getCategoriesByParentId($parent_id = 0) {
			$query = $this->db->query("SELECT *, 
				(SELECT COUNT(parent_id) FROM category WHERE parent_id = c.category_id) AS children,
				(SELECT COUNT(path_id) FROM category_path WHERE category_id = c.category_id) AS level,
				(SELECT name FROM category_description cd3 WHERE cd3.category_id = c.parent_id AND cd3.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1) as parent_name
				FROM category c
				LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' 
				AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ((SELECT COUNT(parent_id) FROM category WHERE parent_id = c.category_id) > 0) DESC, cd.name ASC");
				return $query->rows;
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
			
			public function getYandexCategories($name){	
				$query = $this->db->query("SELECT * FROM category_yam_tree WHERE LOWER(name) LIKE '%" . $this->db->escape(mb_strtolower($name)) . "%' ORDER BY final_category DESC");
				
				return $query->rows;
			}
			
			public function getCategories_MF($data) {
				if( version_compare( VERSION, '1.5.5', '>=' ) ) {
					$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM category_path cp LEFT JOIN category c ON (cp.path_id = c.category_id) LEFT JOIN category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
					
					if( ! empty( $data['filter_name'] ) ) {
						$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
					} 
					
					$sql .= " GROUP BY cp.category_id ORDER BY name";
				} else {
					$sql = "SELECT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
					
					if( ! empty( $data['filter_name'] ) ) {
						$sql .= " AND LOWER(cd.name) LIKE '" . $this->db->escape( function_exists( 'mb_strtolower' ) ? mb_strtolower( $data['filter_name'], 'utf-8' ) : $data['filter_name'] ) . "%'";
					}
					
					$sql .= " GROUP BY c.category_id ORDER BY name";
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
			
		}
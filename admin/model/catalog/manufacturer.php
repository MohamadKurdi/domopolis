<?php
	class ModelCatalogManufacturer extends Model {
		public function addManufacturer($data) {
			$this->db->query("INSERT INTO manufacturer SET name = '" . $this->db->escape($data['name']) . "', tip = '" . $this->db->escape($data['tip']) . "', menu_brand = '" . (int)$data['menu_brand'] . "', show_goods = '" . (int)$data['show_goods'] . "', priceva_enable = '" . (int)$data['priceva_enable'] . "', priceva_feed = '" . $this->db->escape($data['priceva_feed']) . "',  sort_order = '" . (int)$data['sort_order'] . "', banner_width = '" . (int)$data['banner_width'] . "', banner_height = '" . (int)$data['banner_height'] . "'");
			
			$manufacturer_id = $this->db->getLastId();
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE manufacturer SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			}
			
			if (isset($data['banner'])) {
				$this->db->query("UPDATE manufacturer SET banner = '" . $this->db->escape(html_entity_decode($data['banner'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			}
			
			if (isset($data['back_image'])) {
				$this->db->query("UPDATE manufacturer SET back_image = '" . $this->db->escape(html_entity_decode($data['back_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			}
			
			$this->db->query("DELETE FROM manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
    		if (isset($data['manufacturer_layout'])) {
    			foreach ($data['manufacturer_layout'] as $store_id => $layout) {
    				if ($layout['layout_id']) {
    					$this->db->query("INSERT INTO manufacturer_to_layout SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
			
			$this->db->query("DELETE FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', location = '" . $this->db->escape($value['location']) . "', short_description = '" . $this->db->escape($value['short_description']) . "',
				alternate_name = '" . $this->db->escape($value['alternate_name']) . "',
				products_title = '" . $this->db->escape($value['products_title']) . "',
				products_meta_description = '" . $this->db->escape($value['products_meta_description']) . "',
				collections_title = '" . $this->db->escape($value['collections_title']) . "',
				collections_meta_description = '" . $this->db->escape($value['collections_meta_description']) . "',
				categories_title = '" . $this->db->escape($value['categories_title']) . "',
				categories_meta_description = '" . $this->db->escape($value['categories_meta_description']) . "',
				articles_title = '" . $this->db->escape($value['articles_title']) . "',
				articles_meta_description = '" . $this->db->escape($value['articles_meta_description']) . "',
				newproducts_title = '" . $this->db->escape($value['newproducts_title']) . "',
				newproducts_meta_description = '" . $this->db->escape($value['newproducts_meta_description']) . "',
				special_title = '" . $this->db->escape($value['special_title']) . "',
				special_meta_description = '" . $this->db->escape($value['short_description']) . "'");
			}
			
			if (isset($data['manufacturer_store'])) {
				foreach ($data['manufacturer_store'] as $store_id) {
					$this->db->query("INSERT INTO manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
			
			if ($this->url->checkIfGenerate('manufacturer_id')){
				if ($data['keyword']) {
					foreach ($data['keyword'] as $language_id => $keyword) {
						if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
					}
				}
			}
			
			$this->load->model('kp/reward');
			$this->model_kp_reward->editReward($manufacturer_id, 'm', $data);

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'add', 'entity_type' => 'manufacturer', 'entity_id' => $manufacturer_id]);
			
			return (int)$manufacturer_id;
		}
		
		public function editManufacturer($manufacturer_id, $data) {
			$this->db->query("UPDATE manufacturer SET name = '" . $this->db->escape($data['name']) . "', tip = '" . $this->db->escape($data['tip']) . "', menu_brand = '" . (int)$data['menu_brand'] . "', show_goods = '" . (int)$data['show_goods'] . "', sort_order = '" . (int)$data['sort_order'] . "', priceva_enable = '" . (int)$data['priceva_enable'] . "', priceva_feed = '" . $this->db->escape($data['priceva_feed']) . "', banner_width = '" . (int)$data['banner_width'] . "', banner_height = '" . (int)$data['banner_height'] . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			if (isset($data['image'])) {
				$this->db->query("UPDATE manufacturer SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			}
			
			if (isset($data['banner'])) {
				$this->db->query("UPDATE manufacturer SET banner = '" . $this->db->escape(html_entity_decode($data['banner'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			}
			
			if (isset($data['back_image'])) {
				$this->db->query("UPDATE manufacturer SET back_image = '" . $this->db->escape(html_entity_decode($data['back_image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			}
			
			$this->db->query("DELETE FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language_id . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "',  meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', location = '" . $this->db->escape($value['location']) . "', short_description = '" . $this->db->escape($value['short_description']) . "',
				alternate_name = '" . $this->db->escape($value['alternate_name']) . "',
				products_title = '" . $this->db->escape($value['products_title']) . "',
				products_meta_description = '" . $this->db->escape($value['products_meta_description']) . "',
				collections_title = '" . $this->db->escape($value['collections_title']) . "',
				collections_meta_description = '" . $this->db->escape($value['collections_meta_description']) . "',
				categories_title = '" . $this->db->escape($value['categories_title']) . "',
				categories_meta_description = '" . $this->db->escape($value['categories_meta_description']) . "',
				articles_title = '" . $this->db->escape($value['articles_title']) . "',
				articles_meta_description = '" . $this->db->escape($value['articles_meta_description']) . "',
				newproducts_title = '" . $this->db->escape($value['newproducts_title']) . "',
				newproducts_meta_description = '" . $this->db->escape($value['newproducts_meta_description']) . "',
				special_title = '" . $this->db->escape($value['special_title']) . "',
				special_meta_description = '" . $this->db->escape($value['short_description']) . "'");
			}
			
			
			
			$this->db->query("DELETE FROM manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
    		if (isset($data['manufacturer_layout'])) {
    			foreach ($data['manufacturer_layout'] as $store_id => $layout) {
    				if ($layout['layout_id']) {
    					$this->db->query("INSERT INTO manufacturer_to_layout SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
					}
				}
			}
			
			$this->db->query("DELETE FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			if (isset($data['manufacturer_store'])) {
				foreach ($data['manufacturer_store'] as $store_id) {
					$this->db->query("INSERT INTO manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
			
			
			$this->db->query("DELETE FROM manufacturer_page_content WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			if (isset($data['copyrussian']) && $data['copyrussian'] == 1){
				
				$rucontent = $data['manufacturer_page_content'][2];
				$lquery = $this->db->query("SELECT DISTINCT language_id FROM language WHERE language_id <> 26");
				
				$lcodes = array();
				foreach ($lquery->rows as $lrow){
					$lcodes[] = $lrow['language_id'];
				} 
				
				foreach ($lcodes as $language_id) {
					
					foreach ($rucontent as $value){
						
						if (!isset($value['products']) or !is_array($value['products'])){
							$value['products'] = array();
						}
						
						if (!isset($value['collections']) or !is_array($value['collections'])){
							$value['collections'] = array();
						}
						
						if (!isset($value['categories']) or !is_array($value['categories'])){
							$value['categories'] = array();
						}
						
						$this->db->query("INSERT INTO manufacturer_page_content SET 
						manufacturer_id = '" . (int)$manufacturer_id . "', 
						language_id = '" . (int)$language_id . "', 
						title = '" . $this->db->escape($value['title']) . "',
						products = '" . $this->db->escape(implode(',', $value['products'])) . "', 
						collections = '" . $this->db->escape(implode(',', $value['collections'])) . "',
						categories = '" . $this->db->escape(implode(',', $value['categories'])) . "',
						type = '" . $this->db->escape($value['type']) . "', 
						sort_order = '" . (int)$value['sort_order'] . "'");
						
					}
					
				}
				
				
				} else {
				
				foreach ($data['manufacturer_page_content'] as $language_id => $contents) {
					
					foreach ($contents as $value){
						
						if (!isset($value['products']) or !is_array($value['products'])){
							$value['products'] = array();
						}
						
						if (!isset($value['collections']) or !is_array($value['collections'])){
							$value['collections'] = array();
						}
						
						if (!isset($value['categories']) or !is_array($value['categories'])){
							$value['categories'] = array();
						}
						
						$this->db->query("INSERT INTO manufacturer_page_content SET 
						manufacturer_id = '" . (int)$manufacturer_id . "', 
						language_id = '" . (int)$language_id . "', 
						title = '" . $this->db->escape($value['title']) . "',
						products = '" . $this->db->escape(implode(',', $value['products'])) . "', 
						collections = '" . $this->db->escape(implode(',', $value['collections'])) . "',
						categories = '" . $this->db->escape(implode(',', $value['categories'])) . "',
						type = '" . $this->db->escape($value['type']) . "', 
						sort_order = '" . (int)$value['sort_order'] . "'");	
						
					}
					
				}
				
			}
			
			if ($this->url->checkIfGenerate('manufacturer_id')){
				$this->db->query("DELETE FROM url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");

				if ($data['keyword']) {
					foreach ($data['keyword'] as $language_id => $keyword) {
						if ($keyword) {$this->db->query("INSERT INTO url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "', language_id = " . $language_id);}
					}
				}
			}
			
			$this->load->model('kp/reward');
			$this->model_kp_reward->editReward($manufacturer_id, 'm', $data);

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'edit', 'entity_type' => 'manufacturer', 'entity_id' => $manufacturer_id]);

			return (int)$manufacturer_id;
		}
		
		public function deleteManufacturer($manufacturer_id) {
			$this->db->query("DELETE FROM manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			$this->db->query("DELETE FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			$this->db->query("DELETE FROM url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");
			$this->db->query("DELETE FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

			$this->load->model('kp/content');
			$this->model_kp_content->addContent(['action' => 'delete', 'entity_type' => 'manufacturer', 'entity_id' => $manufacturer_id]);					
		}	
		
		public function getManufacturer($manufacturer_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			return $query->row;
		}
		
		public function getKeyWords($manufacturer_id) {
			$keywords = array();

			if ($keywords = $this->url->linkfromid('manufacturer_id', $manufacturer_id)){
				return $keywords;
			}

			
			$query = $this->db->query("SELECT * FROM url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				$keywords[$result['language_id']] = $result['keyword'];					
			}
			
			return $keywords;
		}
		
		public function getManufacturerLayouts($manufacturer_id) {
			$manufacturer_layout_data = array();
			
			$query = $this->db->query("SELECT * FROM manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				$manufacturer_layout_data[$result['store_id']] = $result['layout_id'];
			}
			
			return $manufacturer_layout_data;
		}
		
		public function getManufacturerDescriptions($manufacturer_id) {
			$manufacturer_description_data = array();
			
			$query = $this->db->query("SELECT * FROM manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				$manufacturer_description_data[$result['language_id']] = array(
				'meta_keyword'     				=> $result['meta_keyword'],
				'meta_description' 				=> $result['meta_description'],
				'short_description' 			=> $result['short_description'],
				'location' 						=> $result['location'],
				'description'      				=> $result['description'],
				'seo_title'     				=> $result['seo_title'],
				'seo_h1'    	 				=> $result['seo_h1'],
				'alternate_name'    	 		=> $result['alternate_name'],
				'products_title'    	 		=> $result['products_title'],
				'products_meta_description'    	=> $result['products_meta_description'],
				'collections_title'    	 		=> $result['collections_title'],
				'collections_meta_description'  => $result['collections_meta_description'],
				'categories_title'    	 		=> $result['categories_title'],
				'categories_meta_description'   => $result['categories_meta_description'],
				'articles_title'    	 		=> $result['articles_title'],
				'articles_meta_description'    	=> $result['articles_meta_description'],
				'newproducts_title'    	 		=> $result['newproducts_title'],
				'newproducts_meta_description'  => $result['newproducts_meta_description'],
				'special_title'    	 			=> $result['special_title'],
				'special_meta_description'    	=> $result['special_meta_description']				
				);
			}
			
			return $manufacturer_description_data;
		}
		
		public function getFeeds($manufacturer_id){
			$query = $this->db->query("SELECT * FROM yandex_feeds WHERE entity_type = 'm' AND entity_id = '" . (int)$manufacturer_id . "'");
			
			if ($query->num_rows){
				return $query->rows;
				} else {
				return false;
			}
		}
		
		public function getManufacturers($data = array()) {
			$sql = "SELECT * FROM manufacturer WHERE 1";
			
			if (!empty($data['filter_name'])) {
				$sql .= " AND name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			}
			
			if (!empty($data['filter_yandex_in_stock'])) {
				$sql .= " AND manufacturer_id IN (SELECT manufacturer_id FROM product WHERE quantity_stockM > 0 AND status = 1)";
			}

			if (!empty($data['filter_priceva']) && isset($data['filter_priceva_store_id'])) {
				$sql .= " AND manufacturer_id IN (SELECT manufacturer_id FROM product WHERE product_id IN (SELECT product_id FROM priceva_data WHERE store_id = '" . (int)$data['filter_priceva_store_id'] . "'))";
			}
			
			$sort_data = array(
			'name',
			'sort_order'
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
		
		public function getManufacturerStores($manufacturer_id) {
			$manufacturer_store_data = array();
			
			$query = $this->db->query("SELECT * FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				$manufacturer_store_data[] = $result['store_id'];
			}
			
			return $manufacturer_store_data;
		}
		
		public function getTotalManufacturersByImageId($image_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM manufacturer WHERE image_id = '" . (int)$image_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalManufacturers() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM manufacturer");
			
			return $query->row['total'];
		}	
		
		public function getTotalProductsByManufacturer($manufacturer_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getManufacturerPageContent($manufacturer_id) {
			$manufacturer_page_content_data = array();
			
			$query = $this->db->query("SELECT * FROM manufacturer_page_content WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			
			foreach ($query->rows as $result) {
				
				$manufacturer_page_content_data[$result['language_id']][$result['manufacturer_page_content_id']] = $result;
				
			}
			
			return $manufacturer_page_content_data;
		}
	}
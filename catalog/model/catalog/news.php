<?php
	class ModelCatalogNews extends Model {
		public function getNewsStory($news_id) {
			$query = $this->db->query("SELECT DISTINCT *, nau.name as author, n.image as image, nau.image as nimage FROM news n LEFT JOIN news_description nd ON (n.news_id = nd.news_id) LEFT JOIN news_to_store n2s ON (n.news_id = n2s.news_id) LEFT JOIN nauthor nau ON (n.nauthor_id = nau.nauthor_id) WHERE n.news_id = '" . (int)$news_id . "'  AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.date_pub < NOW()");
			if ($query->num_rows) {
				return array(
				'news_id'          => $query->row['news_id'],
				'title'            => $query->row['title'],
				'ctitle'           => $query->row['ctitle'],
				'description'      => $query->row['description'],
				'description2'     => $query->row['description2'],
				'recipe'     	   => $query->row['recipe'],
				'meta_desc'        => $query->row['meta_desc'],
				'meta_key'         => $query->row['meta_key'],
				'ntags'            => $query->row['ntags'],
				'cfield1'          => $query->row['cfield1'],
				'cfield2'          => $query->row['cfield2'],
				'cfield3'          => $query->row['cfield3'],
				'cfield4'          => $query->row['cfield4'],
				'image'            => $query->row['image'],
				'image2'           => $query->row['image2'],
				'nimage'           => $query->row['nimage'],
				'acom'             => $query->row['acom'],
				'author'           => $query->row['author'],
				'nauthor_id'       => $query->row['nauthor_id'],
				'date_added'       => $query->row['date_added'],
				'date_updated'     => $query->row['date_updated'],
				'sort_order'       => $query->row['sort_order'],
				'gal_thumb_w'      => $query->row['gal_thumb_w'],
				'gal_thumb_h'      => $query->row['gal_thumb_h'],
				'gal_popup_w'      => $query->row['gal_popup_w'],
				'gal_popup_h'      => $query->row['gal_popup_h'],
				'gal_slider_h'     => $query->row['gal_slider_h'],
				'gal_slider_w'     => $query->row['gal_slider_w'],
				'gal_slider_t'     => $query->row['gal_slider_t'],
				'viewed'     		=> $query->row['viewed'],
				);
				} else {
				return false;
			}
		}
		
		public function getTags(){
			$query = $this->db->query("SELECT ntags FROM news_description nd LEFT JOIN news n ON (n.news_id = nd.news_id) LEFT JOIN news_to_store n2s ON (n.news_id = n2s.news_id) 
			WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.date_pub < NOW() 
			AND LENGTH(ntags) > 1
			");
			
			$all_tags = array();
			foreach ($query->rows as $row){
				$tags = explode(',', $row['ntags']);
				
				foreach ($tags as $_tag){
					if (!isset($all_tags[trim($_tag)])){
						$all_tags[trim($_tag)] = 1;
						} else {
						$all_tags[trim($_tag)] += 1;
					}
				}
			}
			
			arsort($all_tags);
			
			return $all_tags;
		}
		
		public function getNews($data = array()) {
			$sql = "SELECT n.news_id FROM news n LEFT JOIN news_description nd ON (n.news_id = nd.news_id) LEFT JOIN news_to_store n2s ON (n.news_id = n2s.news_id) LEFT JOIN nauthor nau ON (n.nauthor_id = nau.nauthor_id) ";
			if (!empty($data['filter_ncategory_id'])) {
				$sql .= " LEFT JOIN news_to_ncategory n2n ON (n.news_id = n2n.news_id)";			
			}
			$sql .= " WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.date_pub < NOW()";	
			
			if (!empty($data['filter_ncategory_id'])) {
				if (!empty($data['filter_sub_ncategory'])) {
					$implode_data = array();
					
					$implode_data[] = "n2n.ncategory_id = '" . (int)$data['filter_ncategory_id'] . "'";
					
					$this->load->model('catalog/ncategory');
					
				$ncategories = $this->model_catalog_ncategory->getncategoriesByParentId($data['filter_ncategory_id']);
				
				foreach ($ncategories as $ncategory_id) {
					$implode_data[] = "n2n.ncategory_id = '" . (int)$ncategory_id . "'";
				}
				
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
				$sql .= " AND n2n.ncategory_id = '" . (int)$data['filter_ncategory_id'] . "'";
			}
		}		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			
			if (!empty($data['filter_name'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
				
				foreach ($words as $word) {
					$implode[] = "LOWER(nd.title) LIKE '%" . $this->db->escape($word) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
				
				if (!empty($data['filter_description'])) {
					$sql .= " OR nd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$sql .= "LOWER(nd.ntags) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_tag'])) . "%'";
			}
			
			$sql .= ")";
		}
		
		if (!empty($data['filter_author_id'])) {
			$sql .= " AND n.nauthor_id = '" . (int)$data['filter_author_id'] . "'";
		}
		
		if (!$this->config->get('bnews_order')) {
			$sql .= " ORDER BY n.date_added DESC ";
			} else {
			$sql .= " ORDER BY n.sort_order";	
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
		
		$articles_data = array();
		
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$articles_data[$result['news_id']] = $this->getNewsStory($result['news_id']);
		}
		
		return $articles_data;
	}
	
	public function updateViewed($news_id) {
		$this->db->query("UPDATE news SET viewed = viewed+1 WHERE news_id = '" . (int)$news_id . "'");	
	}
	
	public function getNewsLayoutId($news_id) {
		$query = $this->db->query("SELECT * FROM news_to_layout WHERE news_id = '" . (int)$news_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
			} else {
			return  $this->config->get('config_layout_news');
		}
	}
	public function getProductRelated($news_id) {
		$product_data = array();
		
		$query = $this->db->query("SELECT * FROM news_related nr LEFT JOIN news n ON (nr.news_id = n.news_id) LEFT JOIN product_to_store p2s ON (nr.product_id = p2s.product_id) WHERE nr.news_id = '" . (int)$news_id . "' AND n.status = '1' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		$this->load->model('catalog/product');
		foreach ($query->rows as $result) { 
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}
		
		return $product_data;
	}	
	public function getNewsRelated($news_id) {
		$news_related_data = array();
		
		$query = $this->db->query("SELECT * FROM news_related WHERE news_id = '389657" . (int)$news_id . "' LIMIT 5");
		$query2 = $this->db->query("SELECT * FROM news_related WHERE product_id = '" . (int)$news_id . "' AND news_id LIKE '389657%' LIMIT 5");
		
		foreach ($query->rows as $result) {
			$news_related_data[$result['product_id']] = $this->getNewsStory($result['product_id']);
		}
		foreach ($query2->rows as $result2) {
			if (!in_array(str_replace("389657", "", $result2['news_id']), $news_related_data)) {
				$news_related_data[str_replace("389657", "", $result2['news_id'])] = $this->getNewsStory(str_replace("389657", "", $result2['news_id']));
			}
		}
		
		return $news_related_data;
	}
	public function getTotalNews($data = array()) {
		$sql = "SELECT COUNT(DISTINCT n.news_id) AS total FROM news n LEFT JOIN news_description nd ON (n.news_id = nd.news_id) LEFT JOIN news_to_store n2s ON (n.news_id = n2s.news_id) LEFT JOIN nauthor nau ON (n.nauthor_id = nau.nauthor_id) ";
		if (!empty($data['filter_ncategory_id'])) {
			$sql .= " LEFT JOIN news_to_ncategory n2n ON (n.news_id = n2n.news_id)";			
		}
		$sql .= " WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.date_pub < NOW()";	
		
		if (!empty($data['filter_ncategory_id'])) {
			if (!empty($data['filter_sub_ncategory'])) {
				$implode_data = array();
				
				$implode_data[] = "n2n.ncategory_id = '" . (int)$data['filter_ncategory_id'] . "'";
				
				$this->load->model('catalog/ncategory');
				
				$ncategories = $this->model_catalog_ncategory->getncategoriesByParentId($data['filter_ncategory_id']);
				
				foreach ($ncategories as $ncategory_id) {
					$implode_data[] = "n2n.ncategory_id = '" . (int)$ncategory_id . "'";
				}
				
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
				$sql .= " AND n2n.ncategory_id = '" . (int)$data['filter_ncategory_id'] . "'";
			}
		}	
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			
			if (!empty($data['filter_name'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
				
				foreach ($words as $word) {
					$implode[] = "LOWER(nd.title) LIKE '%" . $this->db->escape($word) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
				
				if (!empty($data['filter_description'])) {
					$sql .= " OR nd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$sql .= "LOWER(nd.ntags) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_tag'])) . "%'";
			}
			
			$sql .= ")";
		}		
		
		if (!empty($data['filter_author_id'])) {
			$sql .= " AND n.nauthor_id = '" . (int)$data['filter_author_id'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	public function getNcategoriesbyNewsId($news_id) {
		$query = $this->db->query("SELECT ncategory_id FROM news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");
		
		return $query->rows;
	}
	public function getNauthor($nauthor_id) {
		$query = $this->db->query("SELECT * FROM nauthor WHERE nauthor_id = '" . (int)$nauthor_id . "'");
		
		return $query->row;
	}
	public function getNauthorDescriptions($nauthor_id) {
		$nauthor_description_data = array();
		
		$query = $this->db->query("SELECT * FROM nauthor_description WHERE nauthor_id = '" . (int)$nauthor_id . "'");
		
		foreach ($query->rows as $result) {
			$nauthor_description_data[$result['language_id']] = array(
			'ctitle'           => $result['ctitle'],
			'meta_keyword'     => $result['meta_keyword'],
			'meta_description' => $result['meta_description'],
			'description'      => $result['description']
			);
		}
		
		return $nauthor_description_data;
	}
	public function getArticleGallery($news_id) {
		$query = $this->db->query("SELECT * FROM news_gallery WHERE news_id = '" . (int)$news_id . "' ORDER BY sort_order ASC");
		
		return $query->rows;
	}
	public function getArticleVideos($news_id) {
		$query = $this->db->query("SELECT * FROM news_video WHERE news_id = '" . (int)$news_id . "' ORDER BY sort_order ASC");
		
		return $query->rows;
	}
}
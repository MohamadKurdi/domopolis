<?
	class ModelCatalogSuperSitemap extends Model {
		
		public function getProducts($start = 0, $limit = null) {
			
			$sql = "SELECT 
			p.product_id, 
			p.date_added, 
			p.date_modified, 
			p.image, 
			pd.name,
			(SELECT GROUP_CONCAT(pi5.image SEPARATOR ':') FROM product_image pi5 WHERE pi5.product_id = p.product_id GROUP BY pi5.product_id) as images,
			(SELECT GROUP_CONCAT(pv5.video SEPARATOR ':') FROM product_video pv5 WHERE pv5.product_id = p.product_id GROUP BY pv5.product_id) as videos
			FROM product p 
			LEFT JOIN product_description pd ON ( p.product_id = pd.product_id ) 
			JOIN product_to_store p2s ON (p.product_id = p2s.product_id)
			WHERE 
				p.status = 1 
				AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
				AND p.stock_product_id = 0 
				AND p.date_available <= '". date(MYSQL_NOW_DATE_FORMAT) ."' 
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			$sql .= " ORDER BY p.product_id";	
			$sql .= " ASC";
			
			if ($limit) {			
				$sql .= " LIMIT " . $start . ", " . $limit;			
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}
		
		public function getProductsFacebook($start = 0, $limit = null) {
			
			$sql = "SELECT 
			p.product_id, 
			p.date_added, 
			p.date_modified, 
			p.image, 
			pd.name,
			p.model,
			p.quantity,
			pd.description,
			p.price,
			(SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < '". date(MYSQL_NOW_DATE_FORMAT) ."') AND (ps.date_end = '0000-00-00' OR ps.date_end > '". date(MYSQL_NOW_DATE_FORMAT) ."')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,
			m.name AS manufacturer
			FROM product p 
			LEFT JOIN product_description pd ON ( p.status = '1' AND p.date_available <= '". date(MYSQL_NOW_DATE_FORMAT) ."' AND p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
			LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "') "; 
			
			$sql .= " ORDER BY p.product_id";	
			$sql .= " ASC";
			
			if ($limit) {			
				$sql .= " LIMIT " . $start . ", " . $limit;			
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
			
		}
		
		
		public function getTotalProducts() {
			
		    $sql = "SELECT COUNT(p.product_id) as total 
			FROM product p 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id ) WHERE 
			p.status = '1'
			AND p.stock_product_id = 0
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.date_available <= '". date(MYSQL_NOW_DATE_FORMAT) ."'
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			$query = $this->db->query($sql);
			
			$result = $query->row;
			
			return $result['total'];
		}
		
		public function getCategories($parent_id, $current_path = '') {
			$output = '';
			
			$results = $this->model_catalog_category->getCategories($parent_id);
			
			foreach ($results as $result) {
				if (!$current_path) {
					$new_path = $result['category_id'];
					} else {
					$new_path = $current_path . '_' . $result['category_id'];
				}
				
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('product/category', 'path=' . $result['category_id']) . ']]></loc>';
				$output .= '<changefreq>daily</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
				
				$output .= $this->getCategories($result['category_id'], $new_path);
			}
			
			return $output;
		}
		
		public function getNcategories($parent_id, $current_path = '') {
			$output = '';
			
			$results = $this->model_catalog_ncategory->getncategories($parent_id);
			
			foreach ($results as $result) {
				if (!$current_path) {
					$new_path = $result['ncategory_id'];
					} else {
					$new_path = $current_path . '_' . $result['ncategory_id'];
				}
				
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('news/ncategory', 'ncat=' . $new_path) . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';         
				
				$articles = $this->model_catalog_news->getNews(array('filter_ncategory_id' => $result['ncategory_id']));
				
				foreach ($articles as $article) {
					$output .= '<url>';
					$output .= '<loc><![CDATA[' . $this->url->link('news/article', 'ncat=' . $new_path . '&news_id=' . $article['news_id']) . ']]></loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '</url>';   
				}   
				
				$output .= $this->getNcategories($result['ncategory_id'], $new_path);
			}
			
			return $output;
		}
	}
<?php
	
	class ModelModuleSeogen extends Model {
		public $keywords = false;
		private $manufr_desc = false;
		
		public function __construct($registry) {
			parent::__construct($registry);
			
			require_once(DIR_SYSTEM . 'library/urlify.php');
			
			$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
			$this->manufr_desc = $query->num_rows;
		}
		
		public function loadKeywords() {
			$this->keywords = array();
			$query = $this->db->query("SELECT `query`, LOWER(`keyword`) as 'keyword' FROM " . DB_PREFIX . "url_alias");
			foreach($query->rows as $row) {
				$this->keywords[$row['query']] = $row['keyword'];
			}
			return $query;
		}
		
		public function getKeywords(){
			return $this->keywords;
		}
		
		public function urlifyCategory($category_id) {
			$category = $this->getCategories($category_id);
			$this->generateCategory($category[0], $this->config->get('seogen'));
		}
		
		public function urlifyProduct($product_id) {
			$seogen = $this->config->get('seogen');
			
			$product = $this->getProducts($seogen, $product_id);
			if (count($product)) {
				$this->generateProduct($product[0], $seogen);
			}
		}
		
		public function urlifyManufacturer($manufacturer_id) {
			$manufacturer = $this->getManufacturers($manufacturer_id);
			$this->generateManufacturer($manufacturer[0], $this->config->get('seogen'));
		}
		
		public function urlifyCollection($collection_id) {
			$collection = $this->getCollections($collection_id);
			$this->generateCollection($collection[0], $this->config->get('seogen'));
		}
		
		public function urlifyInformation($information_id) {
			$information = $this->getInformations($information_id);
			$this->generateInformation($information[0], $this->config->get('seogen'));
		}
		
		public function generateCategories($data, $language_id) {		
			if(!empty($data['categories_template'])) {
				if(isset($data['categories_overwrite'])) {
					//	$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('category_id=%');");
				}
				$this->loadKeywords();
			}
			
			if ($language_id > 0 && is_array($data[$language_id])){	
				foreach ($data[$language_id] as $key=>$value){
					$data[$key]	= $value;
				}			
				unset($data[$language_id]);
				$data['language_id'] = $language_id;
			}
			
			if (!isset($data['language_id'])){
				foreach($this->getCategories() as $category) {
					$this->generateCategory($category, $data);
				}		
				} else {
				foreach($this->getCategories(false, $data['language_id']) as $category) {
					$this->generateCategory($category, $data);
				}		
			}
			
			
		}
		
		public function generateProducts($data, $language_id) {
			if(!empty($data['products_template'])) {
				if(isset($data['products_overwrite'])) {
					if(isset($data['only_categories']) && count($data['only_categories'])) {
						/*	$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` IN " .
							"(SELECT concat('product_id=', product_id) FROM `" . DB_PREFIX . "product_to_category` WHERE category_id IN (" . implode(",", $data['only_categories']) . ") )");
						*/
						} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('product_id=%');");
					}
				}
				
				$this->loadKeywords();
			}
			
			if ($language_id > 0 && is_array($data[$language_id])){	
				foreach ($data[$language_id] as $key=>$value){
					$data[$key]	= $value;
				}			
				unset($data[$language_id]);
				$data['language_id'] = $language_id;
			}
			
			foreach($this->getProducts($data) as $product) {
				$this->generateProduct($product, $data);
			}
		}
		
		public function generateManufacturers($data, $language_id) {
			if(!empty($data['manufacturers_template'])) {
				if(isset($data['manufacturers_overwrite'])) {
					//	$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('manufacturer_id=%');");
				}
				$this->loadKeywords();
			}
			
			if ($language_id > 0 && is_array($data[$language_id])){	
				foreach ($data[$language_id] as $key=>$value){
					$data[$key]	= $value;
				}			
				unset($data[$language_id]);
				$data['language_id'] = $language_id;
			}
			
			if (!isset($data['language_id'])){
				foreach($this->getManufacturers() as $manufacturer) {
					$this->generateManufacturer($manufacturer, $data);
				} 	
				} else {
				foreach($this->getManufacturers(false, $data['language_id']) as $manufacturer) {
					$this->generateManufacturer($manufacturer, $data);
				}
			}
		}
		
		public function generateCollections($data, $language_id) {
			if(!empty($data['collections_template'])) {
				if(isset($data['collections_overwrite'])) {
					//	$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('collection_id=%');");
				}
				$this->loadKeywords();
			}
			
			if ($language_id > 0 && is_array($data[$language_id])){	
				foreach ($data[$language_id] as $key=>$value){
					$data[$key]	= $value;
				}			
				unset($data[$language_id]);
				$data['language_id'] = $language_id;
			}
			
			if (!isset($data['language_id'])){
				foreach($this->getCollections() as $collection) {
					$this->generateCollection($collection, $data);
				}		
				} else {
				foreach($this->getCollections(false, $data['language_id']) as $collection) {
					$this->generateCollection($collection, $data);
				}
			}
		}
		
		public function generateInformations($data, $language_id) {
			if(!empty($data['informations_template'])) {
				if(isset($data['informations_overwrite'])) {
					//	$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('information_id=%');");
				}
				$this->loadKeywords();
			}
			
			if ($language_id > 0 && is_array($data[$language_id])){	
				foreach ($data[$language_id] as $key=>$value){
					$data[$key]	= $value;
				}			
				unset($data[$language_id]);
				$data['language_id'] = $language_id;
			}
			
			if (!isset($data['language_id'])){
				foreach($this->getInformations() as $information) {
					$this->generateInformation($information, $data);
				}	
				} else {
				foreach($this->getInformations(false, $data['language_id']) as $information) {
					$this->generateInformation($information, $data);
				}				
			}
			
			
		}
		
		private function generateCategory($category, $data) {
			$tags = array(
			'[category_name]' => $category['name'],
			'[category_description]' => strip_tags(html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8')),
			);
			
			if (isset($data['language_id'])){
				$updates = array();
				if(isset($category['seo_h1']) && (isset($data['categories_h1_overwrite']) || (strlen(trim($category['seo_h1']))) == 0)) {
					$h1 = trim(strtr($data['categories_h1_template'], $tags));
					$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
				}
				if(isset($category['seo_title']) && (isset($data['categories_title_overwrite']) || (strlen(trim($category['seo_title']))) == 0)) {
					$title = trim(strtr($data['categories_title_template'], $tags));
					$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
				}
				if(isset($category['meta_keyword']) && (isset($data['categories_meta_keyword_overwrite']) || (strlen(trim($category['meta_keyword']))) == 0)) {
					$meta_keyword = trim(strtr($data['categories_meta_keyword_template'], $tags));
					$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
				}
				if(isset($category['meta_description']) && (isset($data['categories_meta_description_overwrite']) || (strlen(trim($category['meta_description']))) == 0)) {
					$categories_meta_description_template = $data['categories_meta_description_template'];
					
					if (isset($data['categories_use_expressions'])) {
						$categories_meta_description_template = $this->parseTemplate($categories_meta_description_template);
					}
					$meta_description = trim(strtr($categories_meta_description_template, $tags));
					if (isset($data['categories_meta_description_limit']) && (int)$data['categories_meta_description_limit']) {
						$meta_description = mb_substr($meta_description, 0, (int)$data['categories_meta_description_limit']);
					}
					
					$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
				}
				if(isset($category['description']) && (isset($data['categories_description_overwrite']) || (strlen(trim($category['description']))) == 0)) {
					$categories_description_template = $data['categories_description_template'];
					
					if (isset($data['categories_use_expressions'])) {
						$categories_description_template = $this->parseTemplate($categories_description_template);
					}
					$description = trim(strtr($categories_description_template, $tags));
					$updates[] = "`description`='" . $this->db->escape($description) . "'";
				}
				
				if(count($updates) && $data['language_id'] > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "category_description`" .
					" SET " . implode(", ", $updates) .
					" WHERE category_id='" . (int)$category['category_id'] . "' AND language_id='" . (int)$data['language_id'] . "'");
				}
			}
		}
		
		
		public function generateProduct($product, $data, $return = false) {
			
			$tags = array(
			'[product_name]' => $product['name'],
			'[product_description]' => strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')),
			'[model_name]' => $product['model'],
			'[manufacturer_name]' => $product['manufacturer']?$product['manufacturer'].'.':'',
			'[category_name]' => $product['category'],
			'[location]' => $product['location'],
			'[sku]' => $product['sku'],
			'[madeof]' => $product['madeof']?$product['madeof'].', ':'',
			'[type]' => $product['type']?$product['type']:'',
			'[price]' => $this->currency->format($product['price']),
			);
			
			if (isset($data['language_id'])){
				
				$updates = array();
				if($return || (isset($product['seo_h1']) && (isset($data['products_h1_overwrite']) || (strlen(trim($product['seo_h1']))) == 0))) {
					$h1 = trim(strtr($data['products_h1_template'], $tags));
					$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
				}
				if($return || (isset($product['seo_title']) && (isset($data['products_title_overwrite']) || (strlen(trim($product['seo_title']))) == 0))) {
					$products_title_template = $data['products_title_template'];
					
					if (isset($data['products_use_expressions'])) {
						$products_title_template = $this->parseTemplate($products_title_template);
					}
					
					$title = trim(strtr($products_title_template, $tags));
					$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
				}
				if($return || (isset($product['meta_keyword']) && (isset($data['products_meta_keyword_overwrite']) || (strlen(trim($product['meta_keyword']))) == 0))) {
					$meta_keyword = trim(strtr($data['products_meta_keyword_template'], $tags));
					
					$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
				}
				if($return || (isset($product['meta_description']) && (isset($data['products_meta_description_overwrite']) || (strlen(trim($product['meta_description']))) == 0))) {
					$products_meta_description_template = $data['products_meta_description_template'];
					if (isset($data['products_use_expressions'])) {
						$products_meta_description_template = $this->parseTemplate($products_meta_description_template);
					}
					
					$meta_description = trim(strtr($products_meta_description_template, $tags));
					$meta_description = str_replace('  ', ' ', $meta_description);
					if (isset($data['products_meta_description_limit']) && (int)$data['products_meta_description_limit']) {
						$meta_description = mb_substr($meta_description, 0, (int)$data['products_meta_description_limit']);
					}
					$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
				}
				
				if($return || (isset($product['description']) && (isset($data['products_description_overwrite']) || (strlen(trim($product['description']))) == 0))) {
					$products_description_template = $data['products_description_template'];
					if (isset($data['products_use_expressions'])) {
						$products_description_template = $this->parseTemplate($products_description_template);
					}
					$description = trim(strtr($products_description_template, $tags));
					$updates[] = "`description`='" . $this->db->escape($description) . "'";
				}
			}
			
			
			if ($return){
				echo ${$return};				
				exit();
			}
			
			
			if(isset($product['model']) && (isset($data['products_model_overwrite']) || (strlen(trim($product['model']))) == 0)) {
				$products_model_template = trim(strtr($data['products_model_template'], $tags));
				$this->db->query("UPDATE `" . DB_PREFIX . "product`" .
				" SET `model`='" . $this->db->escape($products_model_template) . "' WHERE product_id='" . (int)$product['product_id'] . "'");
			}
			
			if(count($updates)  && $data['language_id'] > 0) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product_description`" .
				" SET " . implode(", ", $updates) .
				" WHERE product_id='" . (int)$product['product_id'] . "' AND language_id='" . (int)$data['language_id'] . "'");
			}
		}
		
		
		private function generateCollection($collection, $data) {
			$tags = array(
			'[collection_name]' => $collection['name'],
			'[manufacturer_name]' => $collection['mname']
			);
			
			
			if (isset($data['language_id'])){
				$updates = array();
				if(isset($collection['seo_h1']) && (isset($data['collections_h1_overwrite']) || (strlen(trim($collection['seo_h1']))) == 0)) {
					$h1 = trim(strtr($data['collections_h1_template'], $tags));
					$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
				}
				if(isset($collection['seo_title']) && (isset($data['collections_title_overwrite']) || (strlen(trim($collection['seo_title']))) == 0)) {
					
					$collection_title_template = $data['collections_title_template'];
					
					if (isset($data['collections_use_expressions'])) {
						$collection_title_template = $this->parseTemplate($collection_title_template);
					}
					
					$title = trim(strtr($collection_title_template, $tags));				
					$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
				}
				if(isset($collection['meta_keyword']) && (isset($data['collections_meta_keyword_overwrite']) || (strlen(trim($collection['meta_keyword']))) == 0)) {
					$meta_keyword = trim(strtr($data['collections_meta_keyword_template'], $tags));
					$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
				}
				if(isset($collection['meta_description']) && (isset($data['collections_meta_description_overwrite']) || (strlen(trim($collection['meta_description']))) == 0)) {				
					$collection_meta_description_template = $data['collections_meta_description_template'];
					
					if (isset($data['collections_use_expressions'])) {
						$collection_meta_description_template = $this->parseTemplate($collection_meta_description_template);
					}
					
					$meta_description = trim(strtr($collection_meta_description_template, $tags));
					$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
				}
				if(isset($collection['description']) && (isset($data['collections_description_overwrite']) || (strlen(trim($collection['description']))) == 0)) {
					$collections_description_template = $data['collections_description_template'];
					
					if (isset($data['collections_use_expressions'])) {
						$collections_description_template = $this->parseTemplate($collections_description_template);
					}
					
					$description = trim(strtr($collections_description_template, $tags));
					$updates[] = "`description`='" . $this->db->escape($description) . "'";
				}
				
				if(count($updates)  && $data['language_id'] > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "collection_description`" .
					" SET " . implode(", ", $updates) .
					" WHERE collection_id='" . (int)$collection['collection_id'] . "' AND language_id='" . (int)$data['language_id'] . "'");			
				}
			}
		}
		
		private function generateManufacturer($manufacturer, $data) {
			$tags = array('[manufacturer_name]' => $manufacturer['name']);	
			
			if (isset($data['language_id'])){
				$updates = array();
				if(isset($manufacturer['seo_h1']) && (isset($data['manufacturers_h1_overwrite']) || (strlen(trim($manufacturer['seo_h1']))) == 0)) {
					$h1 = trim(strtr($data['manufacturers_h1_template'], $tags));
					$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
				}
				if(isset($manufacturer['seo_title']) && (isset($data['manufacturers_title_overwrite']) || (strlen(trim($manufacturer['seo_title']))) == 0)) {
					$title = trim(strtr($data['manufacturers_title_template'], $tags));
					$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
				}
				if(isset($manufacturer['meta_keyword']) && (isset($data['manufacturers_meta_keyword_overwrite']) || (strlen(trim($manufacturer['meta_keyword']))) == 0)) {
					$meta_keyword = trim(strtr($data['manufacturers_meta_keyword_template'], $tags));
					$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
				}
				if(isset($manufacturer['meta_description']) && (isset($data['manufacturers_meta_description_overwrite']) || (strlen(trim($manufacturer['meta_description']))) == 0)) {
					$manufacturers_meta_description_template = $data['manufacturers_meta_description_template'];
					
					if (isset($data['manufacturers_use_expressions'])) {
						$manufacturers_meta_description_template = $this->parseTemplate($manufacturers_meta_description_template);
					}
					
					$meta_description = trim(strtr($manufacturers_meta_description_template, $tags));
					$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
					
				}
				
				foreach (array('products_title', 'products_meta_description', 'collections_title', 'collections_meta_description', 'categories_title', 'categories_meta_description', 'articles_title', 'articles_meta_description', 'newproducts_title', 'newproducts_meta_description', 'special_title', 'special_meta_description') as $__field){
					
					if(isset($manufacturer[$__field]) && (isset($data['manufacturers_'. $__field .'_overwrite']) || (strlen(trim($manufacturer[$__field]))) == 0)) {
						${'manufacturers_' . $__field . '_template'} = $data['manufacturers_'. $__field .'_template'];
						
						if (isset($data['manufacturers_use_expressions'])) {
							${'manufacturers_' . $__field . '_template'} = $this->parseTemplate(${'manufacturers_' . $__field . '_template'});
						}
						
						${$__field} = trim(strtr(${'manufacturers_' . $__field . '_template'}, $tags));
						$updates[] = "`" . $__field . "`='" . $this->db->escape(${$__field}) . "'";
						
					}
					
				}
				
				if(isset($manufacturer['description']) && (isset($data['manufacturers_description_overwrite']) || (strlen(trim($manufacturer['description']))) == 0)) {
					$manufacturers_description_template = $data['manufacturers_description_template'];
					
					if (isset($data['manufacturers_use_expressions'])) {
						$manufacturers_description_template = $this->parseTemplate($manufacturers_description_template);
					}
					
					$description = trim(strtr($manufacturers_description_template, $tags));
					$updates[] = "`description`='" . $this->db->escape($description) . "'";
				}

				if(count($updates)  && $data['language_id'] > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer_description`" .
					" SET " . implode(", ", $updates) .
					" WHERE manufacturer_id='" . (int)$manufacturer['manufacturer_id'] . "' AND language_id='" . (int)$data['language_id'] . "'");
				}
			}
		}
		
		public function generateInformation($information, $data) {
			$tags = array('[information_title]' => $information['title']);
			
			if (isset($data['language_id'])){
				$updates = array();
				if(isset($information['seo_h1']) && (isset($data['informations_h1_overwrite']) || (strlen(trim($information['seo_h1']))) == 0)) {
					$h1 = trim(strtr($data['informations_h1_template'], $tags));
					$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
				}
				if(isset($information['seo_title']) && (isset($data['informations_title_overwrite']) || (strlen(trim($information['seo_title']))) == 0)) {
					$title = trim(strtr($data['informations_title_template'], $tags));
					$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
				}
				if(isset($information['meta_keyword']) && (isset($data['informations_meta_keyword_overwrite']) || (strlen(trim($information['meta_keyword']))) == 0)) {
					$meta_keyword = trim(strtr($data['informations_meta_keyword_template'], $tags));
					$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
				}
				if(isset($information['meta_description']) && (isset($data['informations_meta_description_overwrite']) || (strlen(trim($information['meta_description']))) == 0)) {
					$meta_description = trim(strtr($data['informations_meta_description_template'], $tags));
					$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
				}
				
				if(count($updates)  && $data['language_id'] > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "information_description`" .
					" SET " . implode(", ", $updates) .
					" WHERE information_id='" . (int)$information['information_id'] . "' AND language_id='" . (int)$data['language_id'] . "'");
				}
			}
		}
		
		
		private function getCategories($category_id = false, $language_id = 2) {
			$query = $this->db->query("SELECT cd.*, u.keyword FROM " . DB_PREFIX . "category_description cd" .
			" LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('category_id=', cd.category_id) = u.query)" .
			" WHERE cd.language_id = '" . (int)$language_id . "'" .
			($category_id ? " AND cd.category_id='" . (int)$category_id . "'" : "") .
			" ORDER BY cd.category_id");
			return $query->rows;
		}
		
		public function getProducts($seogen, $product_id = false) {
			
			$conf_seogen = $this->config->get('seogen');
			
			$only_categories = false;
			if (isset($seogen['only_categories']) && count($seogen['only_categories'])) {
				$only_categories = implode(",", $seogen['only_categories']);
			}
			
			if (!isset($seogen['language_id'])){
				$seogen['language_id'] = 2;			
			}
			
			$query = $this->db->query("SELECT pd.*, m.name as manufacturer, p.model as model, p.sku, p.price, p.location, " .
			"(SELECT cd.name FROM `" . DB_PREFIX . "category_description` cd " .
			" LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (cd.category_id = p2c.category_id)".
			" WHERE p2c.product_id = p.product_id".
			" AND cd.language_id ='" . (int)$seogen['language_id'] . "'".
			" ORDER BY cd.category_id DESC LIMIT 1) AS category," .						
			"(SELECT text FROM product_attribute pa 
			WHERE pa.product_id = p.product_id 
			AND pa.language_id ='" . (int)$seogen['language_id'] . "' AND pa.attribute_id = 4) as madeof," .	
			"(SELECT text FROM product_attribute pa 
			WHERE pa.product_id = p.product_id 
			AND pa.language_id ='" . (int)$seogen['language_id'] . "' AND pa.attribute_id = 2) as type" .	
			" FROM `" . DB_PREFIX . "product` p" .			
			" INNER JOIN `" . DB_PREFIX . "product_description` pd ON ( pd.product_id = p.product_id )" .
			" LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON ( m.manufacturer_id = p.manufacturer_id )" .
			($only_categories ? " LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p2c.product_id=p.product_id)" : "") .
			" WHERE pd.language_id ='" . (int)$seogen['language_id'] . "'" .
			($only_categories ? " AND p2c.category_id IN (" . $only_categories . ")" : "") .
			($product_id ? " AND p.product_id='" . (int)$product_id . "'" : "") .
			" ORDER BY p.product_id");
			if ($product_id) {
				$query_keyword = $this->db->query("SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` WHERE `query`='product_id=". $query->rows[0]['product_id']."' LIMIT 1");
				$query->rows[0]['keyword'] = $query_keyword->num_rows ? $query_keyword->row['keyword'] : null;
				} else if($this->keywords !== false) {
				foreach($query->rows as &$row) {
					$row['keyword'] = isset($this->keywords["product_id=" . $row['product_id']]) ? $this->keywords["product_id=" . $row['product_id']] : null;
				}
			}
			
			return $query->rows;
		}
		
		private function getManufacturers($manufacturer_id = false, $language_id = 2) {
			if($this->manufr_desc) {
				$query = $this->db->query("SELECT md.*, u.keyword, m.name" .
				" FROM `" . DB_PREFIX . "manufacturer` m" .
				" LEFT JOIN `" . DB_PREFIX . "manufacturer_description` md ON (m.manufacturer_id=md.manufacturer_id)" .
				" LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('manufacturer_id=', m.manufacturer_id) = u.query)" .
				" WHERE md.language_id='" . (int)$language_id . "'" .
				($manufacturer_id ? " AND m.manufacturer_id='" . (int)$manufacturer_id . "'" : "") .
				" ORDER BY m.manufacturer_id");
				} else {
				$query = $this->db->query("SELECT manufacturer_id, name, u.keyword" .
				" FROM `" . DB_PREFIX . "manufacturer` m" .
				" LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('manufacturer_id=', m.manufacturer_id) = u.query)" .
				($manufacturer_id ? " WHERE m.manufacturer_id='" . (int)$manufacturer_id . "'" : "") .
				" ORDER BY m.manufacturer_id");
			}
			return $query->rows;
		}
		
		private function getCollections($collection_id = false, $language_id = 2) {
			$query = $this->db->query("SELECT cd.*, u.keyword, c.name, m.name as mname" .
			" FROM `" . DB_PREFIX . "collection` c" .
			" LEFT JOIN `" . DB_PREFIX . "collection_description` cd ON (c.collection_id=cd.collection_id)" .
			" LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (c.manufacturer_id=m.manufacturer_id)" .
			" LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('collection_id=', m.manufacturer_id) = u.query)" .
			" WHERE cd.language_id='" . (int)$language_id . "'" .
			($collection_id ? " AND m.collection_id='" . (int)$collection_id . "'" : "") .
			" ORDER BY c.collection_id");		
			return $query->rows;
		}
		
		private function getInformations($information_id = false, $language_id = 2) {
			$query = $this->db->query("SELECT id.*, u.keyword FROM " . DB_PREFIX . "information_description id" .
			" LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('information_id=', id.information_id) = u.query)" .
			" WHERE id.language_id = '" . (int)$language_id . "'" .
			($information_id ? " AND id.information_id='" . (int)$information_id . "'" : "") .
			" ORDER BY id.information_id");
			return $query->rows;
		}
		
		
		private function checkDuplicate(&$keyword) {
			$counter = 0;
			$k = $keyword;
			if($this->keywords !== false) {
				while(in_array($keyword, $this->keywords)) {
					$keyword = $k . '-' . ++$counter;
				}
				$this->keywords[] = $keyword;
				} else {
				do {
					$query = $this->db->query("SELECT url_alias_id FROM " . DB_PREFIX . "url_alias WHERE keyword ='" . $this->db->escape($keyword) . "'");
					if($query->num_rows > 0) {
						$keyword = $k . '-' . ++$counter;
					}
				} while($query->num_rows > 0);
			}
			
			for ($i=1000; $i>=0; $i--){
				$keyword = str_replace('.html-'.$i, '-'.$i.'.html', $keyword);			
			}
		}
		
		private function rms($st)
		{
			$st = str_replace(',','',$st);
			$st = str_replace('’','',$st);
			$st = str_replace(' ','-',$st);
			$st = str_replace('"','',$st);
			$st = str_replace(')','',$st);
			$st = str_replace('(','',$st);
			$st = str_replace('.','',$st);
			$st = str_replace('+','',$st);
			$st = str_replace('*','',$st);
			$st = str_replace('“','',$st);
			$st = str_replace('”','',$st);
			$st = str_replace('&quot;','-',$st);
			$st = str_replace('&amp;','-and-',$st);
			$st = str_replace('&','-and-',$st);
			$st = str_replace('«','',$st);
			$st = str_replace('»','',$st);
			$st = str_replace('.','',$st);
			$st = str_replace('/','-',$st);
			$st = str_replace('\\','-',$st);
			$st = str_replace('%','-',$st);
			$st = str_replace('№','-',$st);
			$st = str_replace('#','-',$st);
			$st = str_replace('_','-',$st);
			$st = str_replace('–','-',$st);
			$st = str_replace('---','-',$st);
			$st = str_replace('--','-',$st);
			$st = str_replace('\'','',$st);
			$st = str_replace('!','',$st);
			$st = str_replace('Ø','',$st);
			return $st;
		}
		
		public function urlify($template, $tags) {
			$keyword = strtr($template, $tags);
			$keyword = trim(html_entity_decode($this->rms($keyword), ENT_QUOTES, "UTF-8"));
			$urlify = URLify::filter($keyword);
			$this->checkDuplicate($urlify);
			return $urlify;
		}
		
		private function parseTemplate($template) {
			while(preg_match('/\\{rand:(.*?)\\}/', $template, $matches)){
				$arr = explode(",", $matches[1]);
				$rand = array_rand($arr);
				$template = str_replace($matches[0], trim($arr[$rand]), $template);
			}
			return $template;
		}
	}			
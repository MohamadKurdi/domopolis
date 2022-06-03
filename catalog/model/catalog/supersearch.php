<?php
	class ModelCatalogSuperSearch extends Model {
		private $limit = 10;
		
		function correctString ($string, $reverse = false)
		{
			
			$string = utf8_strtolower($string);
		
			$ru = array(
			"й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			"ф","ы","в","а","п","р","о","л","д","ж","э",
			"я","ч","с","м","и","т","ь","б","ю"
			);
			$en = array(
			"q","w","e","r","t","y","u","i","o","p","[","]",
			"a","s","d","f","g","h","j","k","l",";","'",
			"z","x","c","v","b","n","m",",","."
			);
			
			if ($reverse){
				return str_replace($en, $ru, $string);
			} else {
				return str_replace($ru, $en, $string);
			}
		}
		
		public function search($keyword){
			
			$result = $this->_search($keyword);
			if (!count($result)){
				$result = $this->_search($this->correctString($keyword));
			}
			if (!count($result)){
				$result = $this->_search($this->correctString($keyword, true));
			}
			
			return $result;
			
		}
		
		public function _search($keyword){
			
			$culimit = 0;
			$result = array();
			
			//Бренды
			$query = $this->db->query("
			SELECT * FROM manufacturer m LEFT JOIN manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) 
			WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND LCASE(m.name) LIKE '".$this->db->escape(utf8_strtolower($keyword))."%' LIMIT 20
			");
			
			$m = array();
			foreach ($query->rows as $row){
				$result[] = array(
				'type' => 'm',
				'id'   => $row['manufacturer_id']
				);	
				$m[] = $row['manufacturer_id'];	
			}
			
			//Категории
			$culimit = $this->limit - count($result);
			
			$sql = "SELECT * FROM category c 
			LEFT JOIN category_description cd ON (c.category_id = cd.category_id) 
			LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) 
			WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND c.status = '1' 
			AND (LCASE(cd.name) LIKE '".$this->db->escape(utf8_strtolower($keyword))."%'
			OR MATCH (cd.name) AGAINST ('" . $this->db->escape(utf8_strtolower($keyword)) . "' IN BOOLEAN MODE))
			ORDER BY c.sort_order, LCASE(cd.name)
			LIMIT " . (int)$culimit;
			
			$query = $this->db->query($sql);											
			
			$c = array();
			foreach ($query->rows as $row){
				$result[] = array(
				'type' => 'c',
				'id'   => $row['category_id']
				);
				$c[] = $row['category_id'];	
			}
			
			//Товары
			//проверяем на соответствие числовому артикулу
			
			$culimit = $this->limit - count($result);
			$art = preg_replace("([^0-9])", "", $keyword);
			//ИЩЕМ ИСКЛЮЧИТЕЛЬНО
			$sql = "SELECT DISTINCT p.product_id FROM product p 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE
			((REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '') = '" .$this->db->escape($art). "'			
			AND LENGTH(p.model)>1)
			OR p.product_id = '" . (int)$keyword .  "') 
			AND p.status = '1' AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			LIMIT " . (int)$culimit . "";
			
			$query = $this->db->query($sql);
			
			$found_pids = array();
			foreach ($query->rows as $row){
				$result[] = array(
				'type' => 'p',
				'id'   => $row['product_id']
				);
				$found_pids[] = $row['product_id'];
			}
			
			$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $keyword)));
			$culimit = $this->limit - count($result);			
			$sql = "
			SELECT DISTINCT IF(p.is_option_for_product_id > 0, p.is_option_for_product_id, p.product_id) as product_id FROM product p 
			LEFT JOIN product_description pd ON (p.product_id = pd.product_id) 
			LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) 
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.status = '1' AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND (";
			$implode = array();
			foreach ($words as $word) {
				$implode[] = "(pd.name LIKE '%" . $this->db->escape($word) . "%'						
				OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($word)) . "'
				OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($word)) . "'
				OR LCASE(((REPLACE(REPLACE(REPLACE(REPLACE(p.model,' ',''), '.', ''), '/', ''), '-', '')))) = '" . $this->db->escape(utf8_strtolower(str_replace(array(' ', '.', '/', '-'), '', $word))) . "'
				OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($word)) . "'
				OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($word)) . "'
				OR LCASE(p.asin) = '" . $this->db->escape(utf8_strtolower($word)) . "'
				OR LCASE(p.product_id) = '" . $this->db->escape(utf8_strtolower($word)) . "')";						
			}
			$sql .= " " . implode(" AND ", $implode) . "";
			
			$sql .= ")";
			
			if (count($found_pids)>0){
				$sql .= " AND NOT p.product_id IN (".implode(',', $found_pids).")";
			}
			
			$sql .= " ORDER BY p.stock_status_id, p.sort_order, LCASE(pd.name) LIMIT " . (int)$culimit . "";
			
			$query = $this->db->query($sql); 
			
			foreach ($query->rows as $row){
				$result[] = array(
				'type' => 'p',
				'id'   => $row['product_id']
				);				
			}

			return $result;
			
		}
		
	}			
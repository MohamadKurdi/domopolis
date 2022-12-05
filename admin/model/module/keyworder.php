<?php
class ModelModuleKeyworder extends Model {
	protected $cound_added = 0;
	protected $cound_deleted = 0;

	public function getKeyworder($data = array()) {
		$sql = "SELECT k.keyworder_id, k.manufacturer_id, k.category_id FROM keyworder k LEFT JOIN manufacturer m ON (m.manufacturer_id = k.manufacturer_id) LEFT JOIN category_description cd ON (cd.category_id = k.category_id) WHERE";

		if (!empty($data['filter_category_id'])) {
			$sql .= " k.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if ((!empty($data['filter_category_id'])) && (!empty($data['filter_manufacturer_id']))) {
			$sql .= " AND";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " k.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if ((!empty($data['filter_category_id'])) || (!empty($data['filter_manufacturer_id']))) {
			$sql .= " AND";
		}

		$sql .= " cd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY m.name, cd.name";

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

		$datas = array();

		$values = array();

		foreach ($query->rows as $row) {
			$rem = $this->getDescriptions($row['keyworder_id']);

			if ($rem) {
				$datas['dat'][$row['keyworder_id']] = $rem;
			}
		}

		$values['val'] = $query->rows;

		$result = array_merge($values, $datas);

		return $result;
	}

	public function getDescriptions($keyworder_id) {
		$query = $this->db->query("SELECT * FROM keyworder_description WHERE keyworder_id = '" . (int)$keyworder_id . "'");

		return $query->rows;
	}

	public function getTotalKeyworder($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM keyworder";

		if ((!empty($data['filter_category_id'])) || (!empty($data['filter_manufacturer_id']))) {
			$sql .= " WHERE ";
		}

		if (!empty($data['filter_category_id'])) {
			$sql .= " category_id = '" . (int)$data['filter_category_id'] . "' ";
		}
		
		if ((!empty($data['filter_category_id'])) && (!empty($data['filter_manufacturer_id']))) {
			$sql .= " AND ";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "' ";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function saveKeyworder($data) {
		$this->db->query("DELETE FROM keyworder_description WHERE keyworder_id = '" . (int)$data['keyworder_id'] . "'");

		foreach ($data['infos'] as $language_id => $value) {
			$this->db->query("INSERT INTO keyworder_description SET 
				keyworder_id = '" . (int)$data['keyworder_id'] . "', 
				language_id = '" . (int)$language_id . "', 
				seo_h1 = '" .  $this->db->escape($value['seo_h1']) . "', 
				seo_title = '" .  $this->db->escape($value['seo_title']) . "', 
				meta_keyword = '" .  $this->db->escape($value['meta_keyword']) . "', 
				meta_description = '" .  $this->db->escape($value['meta_description']) . "', 
				description = '" .  $this->db->escape($value['description']) . "',
				image = '" .  $this->db->escape($value['image']) . "', 				
				category_status = '" .  (int)$value['category_status'] . "', 
				keyworder_status = '" .  (int)$value['keyworder_status'] . "'
			");
		}
	}

	public function getAllKeyworders() {
		$sql = $this->db->query("SELECT manufacturer_id, category_id FROM keyworder");

		return $sql->rows;
	}
	
	public function getAllKeywordersWithKWid() {
		$sql = $this->db->query("SELECT manufacturer_id, category_id, keyworder_id FROM keyworder WHERE 1");

		return $sql->rows;
	}
	
	public function generateKeyworders($data){			
		//получаем все кейвордеры
		$all_keyworders = $this->getAllKeywordersWithKWid();
						
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		$i = 1;
		foreach ($all_keyworders as $keyworder_one){
			foreach ($languages as $language){
				$keyworder_template = $data[$language['language_id']];
				
				$category_info = $this->getCategory($keyworder_one['category_id'], $keyworder_one['manufacturer_id'], $language['language_id']);	
											
				
				$input = array(
					'{category_name}',
					'{category_h1}',
					'{category_title}',
					'{alt_image}',
					'{title_image}',
					'{category_meta_keyword}',
					'{category_meta_description}',
					'{category_description}',
					'{manufacturer_name}',
					'{manufacturer_h1}',
					'{manufacturer_title}',
					'{manufacturer_meta_keyword}',
					'{manufacturer_meta_description}',
					'{manufacturer_description}'
				);
				$output = array(
					'category_name'           	    	=> isset($category_info['name']) ? $category_info['name'] : null,
					'category_h1'           	    	=> isset($category_info['seo_h1']) ? $category_info['seo_h1'] : null,
					'category_title'             		=> isset($category_info['seo_title']) ? $category_info['seo_title'] : null,
					'alt_image'             			=> isset($category_info['alt_image']) ? $category_info['alt_image'] : null,
					'title_image'             			=> isset($category_info['title_image']) ? $category_info['title_image'] : null,
					'category_meta_keyword'             => isset($category_info['meta_keyword']) ? $category_info['meta_keyword'] : null,
					'category_meta_description'         => isset($category_info['meta_description']) ? $category_info['meta_description'] : null,
					'category_description'              => isset($category_info['description']) ? $category_info['description'] : null,
					'manufacturer_name'              	=> isset($category_info['manufacturer_name']) ? $category_info['manufacturer_name'] : null,
					'manufacturer_h1'              		=> isset($category_info['manufacturer_seo_h1']) ? $category_info['manufacturer_seo_h1'] : null,
					'manufacturer_title'           		=> isset($category_info['manufacturer_seo_title']) ? $category_info['manufacturer_seo_title'] : null,
					'manufacturer_meta_keyword'         => isset($category_info['manufacturer_meta_keyword']) ? $category_info['manufacturer_meta_keyword'] : null,
					'manufacturer_meta_description'     => isset($category_info['manufacturer_meta_description']) ? $category_info['manufacturer_meta_description'] : null,
					'manufacturer_description'          => isset($category_info['manufacturer_description']) ? $category_info['manufacturer_description'] : null
				);
							

				//если стоит галка "перезаписать"				
				if (isset($keyworder_template['seo_h1_overwrite'])){
					//если шаблон не пустой
					if (!empty($keyworder_template['seo_h1'])) {
					//перезаписали 	
						$category_info['seo_h1'] = str_replace($input, $output, $keyworder_template['seo_h1']);					
					} else {
						$category_info['seo_h1'] = '';
					}
				//нет галки "перезаписать"
				} else {
					if (!empty($category_info['new_h1'])) {
					//непустой старый	
						$category_info['seo_h1'] = $category_info['new_h1'];						
					} else {
						if (!empty($keyworder_template['seo_h1'])) {
						//перезаписали 	
							$category_info['seo_h1'] = str_replace($input, $output, $keyworder_template['seo_h1']);					
						} else {
							$category_info['seo_h1'] = '';
						}				
					}					
				}
							
				if (isset($keyworder_template['seo_title_overwrite'])){
					if (!empty($keyworder_template['seo_title'])) {
						$category_info['seo_title'] = str_replace($input, $output, $keyworder_template['seo_title']);					
					} else {
						$category_info['seo_title'] = '';
					}
				} else {
					if (!empty($category_info['new_title'])) {
						$category_info['seo_title'] = $category_info['new_title'];						
					} else {
						if (!empty($keyworder_template['seo_title'])) {
							$category_info['seo_title'] = str_replace($input, $output, $keyworder_template['seo_title']);					
						} else {
							$category_info['seo_title'] = '';
						}						
					}					
				}

				if (isset($keyworder_template['meta_keyword_overwrite'])){
					if (!empty($keyworder_template['meta_keyword'])) {
						$category_info['meta_keyword'] = str_replace($input, $output, $keyworder_template['meta_keyword']);					
					} else {
						$category_info['meta_keyword'] = '';
					}
				} else {
					if (!empty($category_info['new_meta_keyword'])) {
						$category_info['meta_keyword'] = $category_info['new_meta_keyword'];						
					} else {
						if (!empty($keyworder_template['meta_keyword'])) {
							$category_info['meta_keyword'] = str_replace($input, $output, $keyworder_template['meta_keyword']);					
						} else {
							$category_info['meta_keyword'] = '';
						}					
					}					
				}
				
				if (isset($keyworder_template['meta_description_overwrite'])){
					if (!empty($keyworder_template['meta_description'])) {
						$category_info['meta_description'] = str_replace($input, $output, $keyworder_template['meta_description']);					
					} else {
						$category_info['meta_description'] = '';
					}
				} else {
					if (!empty($category_info['new_meta_description'])) {
						$category_info['meta_description'] = $category_info['new_meta_description'];						
					} else {
						if (!empty($keyworder_template['meta_description'])) {
							$category_info['meta_description'] = str_replace($input, $output, $keyworder_template['meta_description']);					
						} else {
							$category_info['meta_description'] = '';
						}						
					}					
				}
				
				if (isset($keyworder_template['description_overwrite'])){
					if (!empty($keyworder_template['description'])) {
						$category_info['description'] = str_replace($input, $output, $keyworder_template['description']);					
					} else {
						$category_info['description'] = '';
					}
				} else {
					if (!empty($category_info['new_description'])) {
						$category_info['description'] = $category_info['new_description'];						
					} else {
						if (!empty($keyworder_template['description'])) {
							$category_info['description'] = str_replace($input, $output, $keyworder_template['description']);					
						} else {
							$category_info['description'] = '';
						}						
					}					
				}

//закончили обработку
				$this->db->query("UPDATE keyworder_description SET
					seo_h1 = '" . $this->db->escape(trim($category_info['seo_h1'])) . "',
					seo_title = '" . $this->db->escape(trim($category_info['seo_title'])) . "',
					meta_keyword = '" . $this->db->escape(trim($category_info['meta_keyword'])) . "',
					meta_description = '" . $this->db->escape(trim($category_info['meta_description'])) . "',
					description = '" . $this->db->escape(trim($category_info['description'])) . "',
					category_status = 1,
					keyworder_status = 1
					WHERE keyworder_id = '" . (int)$keyworder_one['keyworder_id'] . "'
					AND language_id = '" . (int)$language['language_id'] . "'");
			}
		}		
	}
	
	public function getCategory($category_id, $manufacturer_id, $language_id) {
		$query = $this->db->query("
			SELECT DISTINCT 
				c.category_id, 
				c.image, 
				cd.name, 
				cd.description, 
				cd.seo_h1, 
				cd.seo_title, 
				cd.alt_image, 
				cd.title_image, 
				cd.meta_keyword, 
				cd.meta_description, 
				kd.description AS new_description, 
				kd.seo_h1 AS new_h1, 
				kd.seo_title AS new_title, 
				kd.meta_keyword AS new_meta_keyword, 
				kd.meta_description AS new_meta_description, 
				m.name AS manufacturer_name, 
				md.description AS manufacturer_description, 
				md.seo_h1 AS manufacturer_seo_h1, 
				md.seo_title AS manufacturer_seo_title, 
				md.meta_keyword AS manufacturer_meta_keyword, 
				md.meta_description AS manufacturer_meta_description, 
				m.image AS manufacturer_image, 
				kd.keyworder_status 
			FROM category c
			LEFT JOIN category_description cd ON (c.category_id = cd.category_id) 
			LEFT JOIN keyworder k ON (k.manufacturer_id = '" . (int)$manufacturer_id . "' AND k.category_id = '" . (int)$category_id . "')
			LEFT JOIN keyworder_description kd ON (kd.keyworder_id = k.keyworder_id)
			LEFT JOIN manufacturer m ON (m.manufacturer_id = '" . (int)$manufacturer_id . "') 
			LEFT JOIN manufacturer_description md ON (md.manufacturer_id = m.manufacturer_id) 
			WHERE c.category_id = '" . (int)$category_id . "' 
			AND cd.language_id = '" . (int)$language_id . "' 
			AND m.manufacturer_id = '" . (int)$manufacturer_id . "' 
			AND md.language_id = '" . (int)$language_id . "'
			AND kd.language_id = '" . (int)$language_id . "'");
		
		
		return $query->row;;
	}

	public function scanKeyworder() {
		$sql1 = "SELECT 
			m.manufacturer_id, 
			cp.path_id as category_id
			FROM product p 
			LEFT JOIN product_to_category ptc 
			ON (p.product_id = ptc.product_id) 
			LEFT JOIN category_path cp 
			ON (ptc.category_id = cp.category_id) 
			LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
			GROUP BY m.manufacturer_id, cp.path_id 
			HAVING COUNT(*) >= 1 ORDER BY m.name ASC";
			
		$sql2 = "SELECT manufacturer_id, category_id FROM keyworder";
						
		$query1 = $this->db->query($sql1);
		
		$query2 = $this->db->query($sql2);

		$arr1 = array();
		foreach ($query1->rows as $rows) {					
			
			$sql3 = "SELECT '" . $rows['manufacturer_id'] . "', parent_id FROM category
				WHERE category_id = '" . $rows['category_id'] . "' LIMIT 1";
			$query3 = $this->db->query($sql3);
			
			if ($query3->row && $query3->row['parent_id']>0){
				$arr1[] = array(implode('_', $query3->row));				
			}
				
			$arr1[] = array(implode('_', $rows));
		}

		
		
		$arr2 = array();
		foreach ($query2->rows as $rows) {
			$arr2[] = array(implode('_', $rows));
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($arr1 as $arr) {
			$key = array_search($arr, $arr2);

			if (!is_int($key) == true) {
				$result = explode('_', (string)(implode('', $arr)));
				
				if ((($result[0]) != 0) && (($result[1]) != 0)) {
					$this->db->query("INSERT INTO keyworder SET 
						manufacturer_id = '" . (int)$result[0] . "', 
						category_id = '" . (int)$result[1] . "' 
					");

					$keyworder_id = $this->db->getLastId();

					foreach ($languages as $language) {
						$this->db->query("INSERT INTO keyworder_description SET 
							keyworder_id = '" . (int)$keyworder_id . "', 
							language_id = '" . (int)$language['language_id'] . "',							
							category_status = '1',
							keyworder_status = '1'						
						");
					}
					
					// подсчитываем добавленные
					$this_cound_added[] = array($keyworder_id);
				}
			}
		}

		// выводим количество добавленных
		if (!empty($this_cound_added)) {
			$this->cound_added = count($this_cound_added);
		}

		// удаляем запись если реальной пары id больше нет
		foreach ($arr1 as $ar) {
			$all_search_values[$ar[0]] = 0;
		}

		foreach ($this->getAllKeyworders() as $arr) {
			$values_indb = implode('_', $arr);

			if (!array_key_exists($values_indb, $all_search_values)) {
				$delete_values = list($manufacturer_id, $category_id) = explode('_', $values_indb);

				// подсчитываем удаленные
				$this_cound_deleted[] = array($delete_values[0]);

				$this->db->query("DELETE k, kd FROM keyworder k LEFT JOIN keyworder_description kd ON (k.keyworder_id = kd.keyworder_id) WHERE k.manufacturer_id = '" . (int)$delete_values[0] . "' AND k.category_id = '" . (int)$delete_values[1] . "'");
			}
		}
		
		// выводим количество удаленных
		if (!empty($this_cound_deleted)) {
			$this->cound_deleted = count($this_cound_deleted);
		}
	}

	public function getCountAdded() {
		return $this->cound_added;
	}

	public function getCountDeleted() {
		return $this->cound_deleted;
	}

	public function loadKeyworder($datas) {
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($datas as $data) {
			if ((($data['manufacturer_id']) != 0) && (($data['category_id']) != 0)) {
				$this->db->query("INSERT INTO keyworder SET 
					manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
					category_id = '" . (int)$data['category_id'] . "' 
				");

				$keyworder_id = $this->db->getLastId();

				foreach ($languages as $language) {
					$this->db->query("INSERT INTO keyworder_description SET 
						keyworder_id = '" . (int)$keyworder_id . "', 
						language_id = '" . (int)$language['language_id'] . "',
						category_status = '1',
						keyworder_status = '1'						
					");
				}
			}
		}

		return true;
	}
	

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS keyworder (
			keyworder_id int(11) NOT NULL AUTO_INCREMENT, 
			manufacturer_id int(11) NOT NULL , 
			category_id int(11) NOT NULL , 
			PRIMARY KEY (keyworder_id) 
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS keyworder_description (
			keyworder_id int(11) NOT NULL, 
			language_id int(11) NOT NULL, 
			seo_h1 varchar(255) NOT NULL , 
			seo_title varchar(255) NOT NULL , 
			meta_keyword varchar(255) NOT NULL , 
		    meta_description varchar(255) NOT NULL , 
			description text NOT NULL , 
			category_status tinyint(1) NOT NULL DEFAULT '1', 
			keyworder_status tinyint(1) NOT NULL DEFAULT '1', 
			PRIMARY KEY (keyworder_id,language_id) 
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$sql = "SELECT m.manufacturer_id, ptc.category_id FROM product p LEFT JOIN product_to_category ptc ON (p.product_id = ptc.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) GROUP BY m.manufacturer_id, ptc.category_id HAVING COUNT(*) >= 1 ORDER BY m.name ASC";
		
		$query = $this->db->query($sql);
		
		$this->loadKeyworder($query->rows);
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS keyworder");
		$this->db->query("DROP TABLE IF EXISTS keyworder_description");
	}
}
?>
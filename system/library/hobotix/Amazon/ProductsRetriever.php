<?
	
	namespace hobotix\Amazon;
	
	class ProductsRetriever extends RainforestRetriever
	{
		
		const CLASS_NAME = 'hobotix\\Amazon\\ProductsRetriever';	
		
		public function getProducts(){
			
			$result = [];
			$sql = "SELECT p.*, pd.name FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)  WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' AND added_from_amazon = 1 AND p.product_id NOT IN (SELECT product_id FROM product_amzn_data) AND (NOT ISNULL(p.asin) OR p.asin <> '')";
			
			$query = $this->db->ncquery($sql);
			
			foreach ($query->rows as $row){
				$result[] = [
				'product_id' 			=> $row['product_id'],
				'asin' 					=> $row['asin']								
				];
			}
			
			return $result;
			
		}	
		
		public function getProductsByAsin($asin){
			$results = [];
			$query = $this->db->query("SELECT product_id FROM product WHERE asin LIKE ('" . $this->db->escape($asin) . "')");
			
			foreach ($query->rows as $row){
				$results[] = $row['product_id'];
			}
			
			return $results;
		}
		
		public function getManufacturer($name){
			$query = $this->db->query("SELECT manufacturer_id FROM manufacturer WHERE name LIKE ('" . $this->db->escape($name) . "')");
			
			if ($query->num_rows){
				return (int)$query->row['manufacturer_id'];
				} else {
				return false;
			}
		}
		
		public function addManufacturer($name){
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($name) . "'");
			$manufacturer_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			$this->db->query("INSERT INTO manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
			foreach ($this->registry->get('languages') as $language_code => $language) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language['language_id'] . "', seo_title = '" . $this->db->escape($name) . "'");
			}
			
			return (int)$manufacturer_id;
		}
		
		public function editProductFields($product_id, $fields){
			$sql = "UPDATE `product` SET ";
			
			foreach ($fields as $field){
				
				if ($field['type'] == 'int'){
					$implode[] = " `" . $field['name'] . "` = '" . (int)$field['value'] . "'";
				}
				
				if ($field['type'] == 'decimal'){
					$implode[] = " `" . $field['name'] . "` = '" . (float)$field['value'] . "'";
				}
				
				if ($field['type'] == 'varchar'){
					$implode[] = " `" . $field['name'] . "` = '" . $this->db->escape($field['value']) . "'";
				}
				
				if ($field['type'] == 'date'){
					$implode[] = " `" . $field['name'] . "` = '" . date('Y-m-d', strtotime($field['value'])) . "'";
				}
				
				if ($field['type'] == 'datetime'){
					$implode[] = " `" . $field['name'] . "` = '" . date('Y-m-d H:i:s', strtotime($field['value'])) . "'";
				}				
			}
			
			$implode[] = " `date_modified` = NOW() ";
			
			
			$sql .= implode(',', $implode);
			
			$sql .= " WHERE product_id = '" . (int)$product_id . "'";
			
			$this->db->query($sql);
			
		}

		public function editProductImages($product_id, $data){
			$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data as $product_image) {
				if ($product_image['image']){
					$this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
				}
			}

		}

		public function editProductVideos($product_id, $data){
			$this->db->query("DELETE FROM product_video WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("DELETE FROM product_video_description WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data as $product_video) {
				if ($product_video['video']){
					$this->db->query("INSERT INTO product_video SET product_id = '" . (int)$product_id . "', video = '" . $this->db->escape(html_entity_decode($product_video['video'], ENT_QUOTES, 'UTF-8')) . "', image = '" . $this->db->escape(html_entity_decode($product_video['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_video['sort_order'] . "'");

					$product_video_id = $this->db->getLastId();

					foreach ($product_video['product_video_description'] as $language_id => $value) {
						$this->db->query("INSERT INTO product_video_description SET 
							product_video_id 	= '" . (int)$product_video_id . "',
							language_id 		= '" . (int)$language_id . "',
							product_id 			= '" . (int)$product_id . "',
							title 				= '" . $this->db->escape($value['title']) . "'");
					}
				}
			}			

		}
		
		public function editProductAttributes($product_id, $data){
			
			
			
		}
		
		public function editProductDescriptions($product_id, $data){			
			foreach ($data as $language_id => $value) {
				$this->db->query("UPDATE product_description SET 
				description = '" . $this->db->escape($value['description']) . "',
				translated = '" . (int)$value['translated'] . "'
				WHERE  product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
			}
		}
		
		public function getAttribute($name){
			$query = $this->db->query("SELECT attribute_id FROM attribute_description WHERE name LIKE ('" . $this->db->escape($name) . "') AND language_id = '" . $this->config->get('config_rainforest_source_language_id') . "'");
			
			if ($query->num_rows){
				return $query->row['attribute_id'];
				} else {
				return false;
			}
			
		}
		
		
		
		
		public function editFullProduct($product_id, $product){
			//Load Library model/catalog/product
			require_once(DIR_APPLICATION . '../admin/model/catalog/product.php');
			$this->model_catalog_product = new \ModelCatalogProduct($this->registry);
			
			//Product Link
			$this->editProductFields($product_id, [['name' => 'amazon_product_link', 'type' => 'varchar', 'value' => $product['link']]]);
			//Product Link End
			
			//Бренд
			if (!empty($product['brand'])){
				echoLine('[editFullProduct] Бренд: ' . $product['brand']);
				$manufacturer_id = $this->getManufacturer($product['brand']);			
				if (!$manufacturer_id){
					echoLine('[editFullProduct] Бренд не существует, добавляем:' . $product['brand']);
					$manufacturer_id = $this->addManufacturer($product['brand']);
				}
				$this->editProductFields($product_id, [['name' => 'manufacturer_id', 'type' => 'int', 'value' => $manufacturer_id]]);
			}	
			//Бренд END
			
			//Описание
			if (!empty($product['description'])){
				$product_description = [];			
				foreach ($this->registry->get('languages') as $language_code => $language) {
					if ($language_code == $this->config->get('config_rainforest_source_language')){
						$description = $product['description'];	
						$translated = true;
						} else {
						if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
							$description = $this->yandexTranslator->translate($product['description'], $this->config->get('config_rainforest_source_language'), $language_code, true);
							$translated = true;
							} else {
							$description = $product['description'];
							$translated = false;
						}
					}
					
					
					$product_description[$language['language_id']] = [
					'description' => $description,
					'translated'  => $translated
					];
				}
				
				$this->editProductDescriptions($product_id, $product_description);
			}
			//Описание END

			//Картинки
			if (!empty($product['images'])){
				$images = [];
				$sort_order = 0;
				foreach ($product['images'] as $image){
					if ($image['variant'] == 'MAIN'){
						$this->editProductFields($product_id, [['name' => 'image', 'type' => 'varchar', 'value' => $this->getImage($image['link'])]]);
					} else {				
						$images[] = [
							'image' 		=> $this->getImage($image['link']),
							'sort_order' 	=> $sort_order
						];

						$sort_order++;
					}
				}
				if ($images){
					$this->editProductImages($product_id, $images);
				}
			}
			//Картинки END

			//Видео
			if (!empty($product['videos'])){
				$videos = [];
				$sort_order = 0;


				$product_video_description = [];
				foreach ($product['videos'] as $video){
					foreach ($this->registry->get('languages') as $language_code => $language) {
						if ($language_code == $this->config->get('config_rainforest_source_language')){
							$title = $video['title'];	
							$translated = true;
						} else {
							if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
								$title = $this->yandexTranslator->translate($video['title'], $this->config->get('config_rainforest_source_language'), $language_code, true);
							} else {
								$title = $video['title'];
								$translated = false;
							}
						}


						$product_video_description[$language['language_id']] = [
							'title' => $title,
						];
					}

					if ($video['link']){
						$videos[] = [
							'video' 					=> $this->getImage($video['link']),
							'image'						=> $video['thumbnail']?$this->getImage($video['thumbnail']):'',
							'sort_order'				=> $sort_order,
							'product_video_description' => $product_video_description
						];						
					}

					$this->editProductVideos($product_id, $videos);

					$sort_order++;

				}

			}
			
			
			die();
		}
		
		
		public function addSimpleProductWithOnlyAsin($data) {			
			$this->db->query("INSERT INTO product SET 
			model 				= '" . $this->db->escape($data['asin']) . "', 
			asin 				= '" . $this->db->escape($data['asin']) . "', 
			image           	= '" . $this->db->escape($data['image']) . "', 
			added_from_amazon 	= '1', 
			stock_status_id 	= '" . $this->config->get('config_stock_status_id') . "',
			quantity 			= '0',
			status 				= '0',
			date_added 			= NOW()");
			
			$product_id = $this->db->getLastId();
			
			$this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
			
			$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['category_id'] . "', main_category = 1");
			
			$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($this->registry->get('languages') as $language_code => $language) {
				
				if ($language_code == $this->config->get('config_rainforest_source_language')){
					$name = $data['name'];	
					$translated = true;				
					} else {
					if ($this->config->get('config_rainforest_enable_translation') && $this->config->get('config_rainforest_enable_language_' . $language['code'])){
						$name = $this->yandexTranslator->translate($data['name'], $this->config->get('config_rainforest_source_language'), $language_code, true);
						$translated = true;
						} else {
						$name = $data['name'];
						$translated = false;
					}
				}
				
				$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product_id . "', translated = '" . (int)$translated . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($name) . "'");
			}
			
			return $product_id;
		}
		
		
		
		
		
		
		
		
	}	
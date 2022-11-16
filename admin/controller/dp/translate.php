<?
	/*
	TRANSLATING 2 -> 6
	*/
	
	class ControllerDPTranslate extends Controller {
		protected $error = array();
		
		public function translateReviewsRU(){

			if (!$this->config->get('config_yandex_translate_api_enable')){
				return;
			}

			$this->load->model('kp/translate');	
			
			$query = $this->db->query("SELECT review_id FROM review WHERE review_id NOT IN (SELECT review_id FROM review_description)");
			
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			
			foreach ($query->rows as $row){
				
				foreach ($languages as $language){
					
					echoLine('[TR] Языковая запись ' . (int)$row['review_id']);
					$this->db->query("INSERT IGNORE INTO review_description SET review_id = '" . (int)$row['review_id'] . "', language_id = '" . $language['language_id'] . "', text = '', answer = '', good = '', bads = ''");
					
				}
				
			}
			
			$query = $this->db->query("SELECT review_id, text, good, bads, answer FROM review WHERE review_id IN (SELECT review_id FROM review_description WHERE text = '' AND language_id = 6) LIMIT 1");
			
			if ($query->num_rows){
				$count = count($query->rows);
				$i = 0;
				
				$start_time = time();
				$working_time = 0;
				$sleep_counter = 1;
				$count_symbols = 0;
				
				echoLine('[TR] Всего отзывов ' . $count);
				
				foreach ($query->rows as $row){
					$fields = array('text', 'good', 'bads', 'answer');
					
					echoLine($row['review_id'] . ':' . $i . '/' . $count);
					
					foreach ($fields as $field){
						
						if (!empty($row[$field])){
							$translation = $this->model_kp_translate->translateYandex($row[$field], 'uk', 'ru');	
							
							$json = json_decode($translation, true);
							if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
								$translated = $json['translations'][0]['text'] = htmlspecialchars_decode($json['translations'][0]['text'], ENT_QUOTES);
							}
							
							if ($translated){
								echoLine('[TR UATR] ' . trim(str_replace(PHP_EOL, '', substr(strip_tags($translated), 0, 100))));	
								$this->model_kp_translate->updateReviewTranslation($row['review_id'], '6', $translated, $field);								
								} else {
								echoLine('[TR ERROR] Что-то пошло не так');					
							}
							
							$counter++;
							
							$count_symbols += mb_strlen($row[$field]);
							
							$working_time = time() - $start_time;
							$average_count = (int)((3600/$working_time) * $count_symbols);
							
							if ($average_count > 8000000){
								echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
								$sleep_counter += 1;	
								} else {
								if ($sleep_counter >= 1){
									$sleep_counter -= 1;
								}
								echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
							}
							
							echoLine('[TR STAT] Время выполнения ' . $working_time . ' секунд.');
							echoLine('[TR STAT] Среднее количество символов в час: ' . $average_count);
							
							sleep($sleep_counter);
							
							
							echoLine('');
						}
						
					}
					
					$i++;
				}
				
			}
		}
		
		public function cronAttributesRU(){

			if (!$this->config->get('config_yandex_translate_api_enable')){
				return;
			}
			
			$this->load->model('kp/translate');	
			
			$tempTranslations = array(
			'Стекло' => 'Скло'
			);
			$query = $this->db->query("SELECT product_id, attribute_id FROM product_attribute WHERE language_id = 6 AND LENGTH(text) = 0");
			
			if ($query->num_rows){
				$count = count($query->rows);
				$i = 0;

				$start_time = time();
				$working_time = 0;
				$sleep_counter = 1;
				$count_symbols = 0;
				
				foreach ($query->rows as $row){
					$translated = false;
					
					$ruquery = $this->db->query("SELECT text FROM product_attribute WHERE product_id = '" . $row['product_id'] . "' AND attribute_id = '" . $row['attribute_id'] . "' AND language_id = 2 AND LENGTH(text) > 0");
					
					echoLine($i . '/' . $count);
					
					if ($ruquery->num_rows){						
						$text = $ruquery->row['text'];												
						
						if (is_numeric($text)){
							$translated = $text;
						}
						
						if (!$translated && !empty($tempTranslations[$text])){
							$translated = $tempTranslations[$text];
							echoLine('[TR CACHE] Из кэша переводчика');
						}
						
						if (!$translated){
							
							$translation_ruua = $this->model_kp_translate->translateYandex($text, 'uk', 'ru');	
							
							$json = json_decode($translation_ruua, true);
							if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
								$translated = $json['translations'][0]['text'] = htmlspecialchars_decode($json['translations'][0]['text'], ENT_QUOTES);
							}
							
							if ($translated){
								echoLine('[TR UATR] ' . trim(str_replace(PHP_EOL, '', substr(strip_tags($translated), 0, 100))));	
								$tempTranslations[$text] = $translated;								
								} else {
								echoLine('[TR ERROR] Что-то пошло не так');					
							}
							
						}
						
						$this->model_kp_translate->updateAttributeTranslation($row['product_id'], $row['attribute_id'], '6', $translated);
						
						echoLine($text . ' -> ' . $translated);												
						$count_symbols += mb_strlen($text);
						
						$working_time = time() - $start_time;
						$average_count = (int)((3600/$working_time) * $count_symbols);
						
						if ($average_count > 800000){
							echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
							$sleep_counter += 1;	
							} else {
							if ($sleep_counter >= 1){
								$sleep_counter -= 1;
							}
							echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
						}
						
						echoLine('[TR STAT] Время выполнения ' . $working_time . ' секунд.');
						echoLine('[TR STAT] Среднее количество символов в час: ' . $average_count);
						
						sleep($sleep_counter);
					}
					
					$i++;
					
				}
				
			}
		}	
		
		public function cronProductsRU(){

			if (!$this->config->get('config_yandex_translate_api_enable')){
				return;
			}

			$this->load->model('kp/translate');	
			
			$sql = "SELECT product_id FROM `product_description` WHERE product_id IN 
			(SELECT product_id FROM product_description pd2 WHERE pd2.language_id = 2)
			AND product_id NOT IN (SELECT product_id FROM product_description pd3 WHERE pd3.language_id = 6)
			AND language_id = 2";
			
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $row){				
				echoLine('[TR] Языковая запись ' . (int)$row['product_id']);
				$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$row['product_id'] . "', language_id = '6', name = '', short_name_d = '', name_of_option = '', meta_keyword = '', seo_title = '', seo_h1 = '', meta_description = '', description = '', tag = '', markdown_appearance = '', markdown_condition = '', markdown_pack = '', markdown_equipment = '', translated = '0'");				
			}
			
			//Названия
			$sql = "SELECT product_id, 
			name as name_ruua FROM product_description 
			WHERE product_id IN (SELECT product_id FROM product_description pd2 WHERE pd2.language_id = 2 AND LENGTH(pd2.name) > 2 AND pd2.name REGEXP '[а-яА-Я]') 
			AND product_id IN (SELECT product_id FROM product_description pd3 WHERE pd3.language_id = 6 AND LENGTH(pd3.name) <= 5 AND translated = 0)
			AND language_id = 2";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows){
				echoLine('[TR] Всего товаров ' . count($query->rows));
				
				$total_symbols = 0;
				foreach ($query->rows as $row){
					$total_symbols += mb_strlen($row['name_ruua']);
				}
				unset($row);
				
				echoLine('[TR] Всего символов ' . $total_symbols);
				
				$counter = 1;
				$count_symbols = 0;
				$start_time = time();
				$working_time = 0;
				$sleep_counter = 1;
				foreach ($query->rows as $row){
					$count_symbols += mb_strlen($row['name_ruua']);
					
					echoLine('[TR PROD] Товар ' . $counter . ' из ' . count($query->rows));
					echoLine('[TR STAT] Символы ' . $count_symbols . ' из ' . $total_symbols);
					echoLine('[TR PROD] Переводим товар ' . $row['name_ruua'] . ', ' . $row['product_id']);
					
					$translation_ruua = false;
					$translated = $this->model_kp_translate->translateYandex($row['name_ruua'], 'uk', 'ru');	
					
					$json = json_decode($translated, true);
					if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
						$translation_ruua = $json['translations'][0]['text'] = htmlspecialchars_decode($json['translations'][0]['text'], ENT_QUOTES);
					}
					
					if ($translation_ruua){
						echoLine('[TR UATR] ' . trim(str_replace(PHP_EOL, '', substr(strip_tags($translation_ruua), 0, 100))));					
						
						$this->model_kp_translate->updateNameTranslation($row['product_id'], '6', $translation_ruua);
						$this->model_kp_translate->setTranslationMarker($row['product_id'], '6', true);
						
						} else {
						echoLine('[TR ERROR] Что-то пошло не так');					
					}
					
					$counter++;
					
					$working_time = time() - $start_time;
					$average_count = (int)((3600/$working_time) * $count_symbols);
					
					if ($average_count > 8000000){
						echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
						$sleep_counter += 1;	
						} else {
						if ($sleep_counter >= 1){
							$sleep_counter -= 1;
						}
						echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
					}
					
					echoLine('[TR STAT] Время выполнения ' . $working_time . ' секунд.');
					echoLine('[TR STAT] Среднее количество символов в час: ' . $average_count);
					
					sleep($sleep_counter);
					
					
					echoLine('');
					
				}
				
			}

			die();
			
			
			//SELECTING PRODUCTS
			$sql = "SELECT p.product_id, pd.name, pd.description as description_ruua FROM `product` p
			LEFT JOIN product_description pd ON (pd.language_id = 2 AND pd.product_id = p.product_id)
			LEFT JOIN product_to_store p2s ON (p2s.product_id = p.product_id)
			WHERE p.stock_status_id NOT IN (" . $this->config->get('config_not_in_stock_status_id') . ")
			AND p.product_id IN (SELECT product_id FROM product_description pd3 WHERE language_id = 2 AND LENGTH(pd3.description) > 100)
			AND p.product_id IN (SELECT product_id FROM product_description pd4 WHERE language_id = 6 AND LENGTH(pd4.description) < 50)
			AND pd.language_id = 2
			AND p2s.store_id = 1
			AND p.status = 1
			AND p.price > 0";
			
			$query = $this->db->query($sql);			
			
			if ($query->num_rows){
				echoLine('[TR] Всего товаров ' . count($query->rows));
				
				$total_symbols = 0;
				foreach ($query->rows as $row){
					$total_symbols += mb_strlen($row['description_ruua']);
				}
				unset($row);
				
				echoLine('[TR] Всего символов ' . $total_symbols);
				
				$counter = 1;
				$count_symbols = 0;
				$start_time = time();
				$working_time = 0;
				$sleep_counter = 1;
				foreach ($query->rows as $row){
					$count_symbols += mb_strlen($row['description_ruua']);
					
					echoLine('[TR PROD] Товар ' . $counter . ' из ' . count($query->rows));
					echoLine('[TR STAT] Символы ' . $count_symbols . ' из ' . $total_symbols);
					echoLine('[TR PROD] Переводим товар ' . $row['name'] . ', ' . $row['product_id']);
					
					$translation_ruua = false;
					$translated = $this->model_kp_translate->translateYandex($row['description_ruua'], 'uk', 'ru');	
					
					$json = json_decode($translated, true);
					if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
						$translation_ruua = $json['translations'][0]['text'] = htmlspecialchars_decode($json['translations'][0]['text'], ENT_QUOTES);
					}
					
					if ($translation_ruua){
						echoLine('[TR UATR] ' . trim(str_replace(PHP_EOL, '', substr(strip_tags($translation_ruua), 0, 100))));					
						$this->model_kp_translate->updateDescriptionTranslation($row['product_id'], '6', $translation_ruua);
						} else {
						echoLine('[TR ERROR] Что-то пошло не так');					
					}
					
					$counter++;
					
					$working_time = time() - $start_time;
					$average_count = (int)((3600/$working_time) * $count_symbols);
					
					if ($average_count > 8000000){
						echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
						$sleep_counter += 1;	
						} else {
						if ($sleep_counter >= 1){
							$sleep_counter -= 1;
						}
						echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
					}
					
					echoLine('[TR STAT] Время выполнения ' . $working_time . ' секунд.');
					echoLine('[TR STAT] Среднее количество символов в час: ' . $average_count);
					
					sleep($sleep_counter);					
					
					echoLine('');
				}
				
				
				} else {
				
				echoLine('[TR] Нет товаров для перевода:)');
				
			}
		}	
		
		public function cronCollectionsUA(){

			if (!$this->config->get('config_yandex_translate_api_enable')){
				return;
			}
			
			$this->load->model('kp/translate');			
			
			//SELECTING COLLECTIONS
			$query = $this->db->query("SELECT c.collection_id, c.name, cd.description as description_ruua FROM `collection` c
			LEFT JOIN collection_description cd ON (cd.language_id = 2 AND cd.collection_id = c.collection_id)
			LEFT JOIN collection_to_store c2s ON (c2s.collection_id = c.collection_id)
			WHERE  c.collection_id IN (SELECT collection_id FROM collection_description pd3 WHERE language_id = 2 AND LENGTH(pd3.description) > 100)
			AND c.collection_id IN (SELECT collection_id FROM collection_description pd4 WHERE language_id = 6 AND LENGTH(pd4.description) < 50)
			AND cd.language_id = 2
			AND c2s.store_id = 1");			
			
			if ($query->num_rows){
				echoLine('[TR] Всего коллекций ' . count($query->rows));
				
				$total_symbols = 0;
				foreach ($query->rows as $row){
					$total_symbols += mb_strlen($row['description_ruua']);
				}
				unset($row);
				
				echoLine('[TR] Всего символов ' . $total_symbols);
				
				$counter = 1;
				$count_symbols = 0;
				$start_time = time();
				$working_time = 0;
				$sleep_counter = 1;
				foreach ($query->rows as $row){
					$count_symbols += mb_strlen($row['description_ruua']);
					
					echoLine('[TR COLL] Коллекция ' . $counter . ' из ' . count($query->rows));
					echoLine('[TR STAT] Символы ' . $count_symbols . ' из ' . $total_symbols);
					echoLine('[TR COLL] Переводим коллекцию ' . $row['name'] . ', ' . $row['collection_id']);
					
					$translation_ruua = false;
					$translated = $this->model_kp_translate->translateYandex($row['description_ruua'], 'uk', 'ru');	
					
					$json = json_decode($translated, true);
					if (!empty($json['translations']) && !empty($json['translations'][0]) && !empty($json['translations'][0]['text'])){
						$translation_ruua = $json['translations'][0]['text'] = htmlspecialchars_decode($json['translations'][0]['text'], ENT_QUOTES);
					}
					
					if ($translation_ruua){
						echoLine('[TR UATR] ' . trim(str_replace(PHP_EOL, '', substr(strip_tags($translation_ruua), 0, 100))));					
						$this->model_kp_translate->updateCollectionDescriptionTranslation($row['collection_id'], '6', $translation_ruua);
						} else {
						echoLine('[TR ERROR] Что-то пошло не так');					
					}
					
					$counter++;
					
					$working_time = time() - $start_time;
					$average_count = (int)((3600/$working_time) * $count_symbols);
					
					if ($average_count > 800000){
						echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
						$sleep_counter += 1;	
						} else {
						if ($sleep_counter >= 1){
							$sleep_counter -= 1;
						}
						echoLine('[TR DYNC] Динамическая корректировка паузы, пауза: ' . $sleep_counter);
					}
					
					echoLine('[TR STAT] Время выполнения ' . $working_time . ' секунд.');
					echoLine('[TR STAT] Среднее количество символов в час: ' . $average_count);
					
					sleep($sleep_counter);
					
					
					echoLine('');
				}
				
				
				} else {
				
				echoLine('[TR] Нет товаров для перевода:)');
				
			}
		}
		
	}																								
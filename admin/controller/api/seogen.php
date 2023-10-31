<?

class ControllerApiSeogen extends Controller {

	private $error = array();	

	public function fullcron(){

		if (!$this->config->get('config_enable_seogen_cron')){
			echoLine('[ControllerApiSeogen::fullcron] CRON IS DISABLED IN ADMIN');
			return;
		}

		$this->load->language('module/seogen');
		$this->load->model('module/seogen');
		$this->load->model('localisation/language');

		$seogen = $this->config->get('seogen');			
		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language){
			if ($language['front']){
				echoLine('[SEOGEN CLI] Начинаем язык ' . $language['code']);
				echoLine('[SEOGEN CLI], Товары');
				$this->model_module_seogen->generateProducts($seogen, $language['language_id']);

				echoLine('[SEOGEN CLI], Категории');
				$this->model_module_seogen->generateCategories($seogen, $language['language_id']);

				echoLine('[SEOGEN CLI], Бренды');
				$this->model_module_seogen->generateManufacturers($seogen, $language['language_id']);

				echoLine('[SEOGEN CLI], Коллекции');
				$this->model_module_seogen->generateCollections($seogen, $language['language_id']);

				echoLine('[SEOGEN CLI], Статьи');
				$this->model_module_seogen->generateInformations($seogen, $language['language_id']);
			}
		}

		$this->cron();			
	}

	public function cron(){
		$this->load->model('localisation/language');
		$languages 	= $this->model_localisation_language->getLanguages();		
		$config 	= $this->config->get('seogen');

		$this->load->model('module/seogen');		

		foreach ($languages as $language){			
			$language_id = (int)$language['language_id'];
			$language_code = $language['code'];		

			$store_id_query = $this->db->query("SELECT store_id FROM setting WHERE (`key` = 'config_language' AND `value` = '" .$language_code. "')
				OR (`key` = 'config_second_language' AND `value` = '" .$language_code. "')");

				//store foreach
			foreach ($store_id_query->rows as $store_row) {

				$store_id = $store_row['store_id'];
				if ($store_id == 16){
					$store_id = 0;
				}

				echo 'START LANGUAGE ' . $language['code'] . ' AND SHOP '. $store_id . PHP_EOL;

				if ($this->url->checkIfGenerate('news_id')){
					echo '-- START BLOGS'.PHP_EOL;
					$news_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'news_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($news_url_exists_query->rows as $row){
						$tmp = str_replace('news_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_news_query = $this->db->query("SELECT n.news_id, nd.title 
						FROM news n 
						JOIN news_description nd ON n.news_id = nd.news_id 
						JOIN news_to_store n2s ON n.news_id = n2s.news_id
						WHERE nd.language_id = '". $language_id ."'
						AND n2s.store_id = '" . $store_id . "'
						AND LENGTH(nd.title) > 0");

					unset($row);
					foreach ($all_news_query->rows as $row){				
						if (!isset($cae[$row['news_id']])){
							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('n' . $row['news_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['title']), 80, $language['code']);
							}


							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['news_id'].'-'.$keyword;
								echo '---- NEWS '.$row['news_id'].' : '.$row['title'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- NEWS '.$row['news_id'].' : '.$row['title'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['news_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='news_id=". (int)$row['news_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");				

						} else {
							echo '---- NEWS '.$row['title'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('category_id')){
					echo '-- START CATEGORIES'.PHP_EOL;
					$categories_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'category_id=%' AND language_id = '". $language_id ."'");

					$cae = array();			
					foreach ($categories_url_exists_query->rows as $row){
						$tmp = str_replace('category_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_categories_query = $this->db->query("SELECT c.category_id, cd.name 
						FROM category c 
						JOIN category_description cd ON c.category_id = cd.category_id
						JOIN category_to_store c2s ON c.category_id = c2s.category_id
						WHERE cd.language_id = '". $language_id ."' 
						AND LENGTH(cd.name)>0
						AND c2s.store_id = '" . $store_id . "'
						AND c.status = 1");

					foreach ($all_categories_query->rows as $row){				
						if (!isset($cae[$row['category_id']])){
							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('c' . $row['category_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}

							//check for duplicate
							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['category_id'].'-'.$keyword;
								echo '---- CAT '.$row['category_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- CAT '.$row['category_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['category_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='category_id=". (int)$row['category_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");

						} else {
							echo '---- CAT '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('manufacturer_id')){
					echo '-- START MANUFACTURERS'.PHP_EOL;
					$manufacturers_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'manufacturer_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($manufacturers_url_exists_query->rows as $row){
						$tmp = str_replace('manufacturer_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_manufacturers_query = $this->db->query("SELECT m.manufacturer_id, m.name 
						FROM manufacturer m 
						LEFT JOIN manufacturer_description md ON m.manufacturer_id = md.manufacturer_id 
						JOIN manufacturer_to_store m2s ON m.manufacturer_id = m2s.manufacturer_id
						WHERE m2s.store_id = '" . $store_id . "'
						AND md.language_id = '". $language_id ."' 
						AND LENGTH(m.name)>0");

					unset($row);
					foreach ($all_manufacturers_query->rows as $row){				
						if (!isset($cae[$row['manufacturer_id']])){						
							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('m' . $row['manufacturer_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}

							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['manufacturer_id'].'-'.$keyword;
								echo '---- MAN '.$row['manufacturer_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- MAN '.$row['manufacturer_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['manufacturer_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='manufacturer_id=". (int)$row['manufacturer_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");

						} else {
							echo '---- MAN '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('information_id')){
					echo '-- START INFORMATIONS'.PHP_EOL;
					$informations_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'information_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($informations_url_exists_query->rows as $row){
						$tmp = str_replace('information_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_informations_query = $this->db->query("SELECT i.information_id, id.title as name 
						FROM information i 
						JOIN information_description id ON i.information_id = id.information_id 
						JOIN information_to_store i2s ON i.information_id = i2s.information_id 
						WHERE id.language_id = '". $language_id ."'
						AND i2s.store_id = '" . $store_id . "'
						AND LENGTH(id.title)>0 
						AND i.status = 1");

					unset($row);
					foreach ($all_informations_query->rows as $row){				
						if (!isset($cae[$row['information_id']])){

							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('i' . $row['information_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}


							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['information_id'].'-'.$keyword;
								echo '---- INFO '.$row['information_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- INFO '.$row['information_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['information_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='information_id=". (int)$row['information_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");

						} else {
							echo '---- INFO '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('landingpage_id')){
					echo '-- START LANDINGPAGES'.PHP_EOL;
					$landingpages_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'landingpage_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($landingpages_url_exists_query->rows as $row){
						$tmp = str_replace('landingpage_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_landingpages_query = $this->db->query("SELECT i.landingpage_id, id.title as name 
						FROM landingpage i 
						JOIN landingpage_description id ON i.landingpage_id = id.landingpage_id 
						JOIN landingpage_to_store i2s ON i.landingpage_id = i2s.landingpage_id 
						WHERE id.language_id = '". $language_id ."'
						AND i2s.store_id = '" . $store_id . "'
						AND LENGTH(id.title)>0 
						AND i.status = 1");	

					unset($row);
					foreach ($all_landingpages_query->rows as $row){				
						if (!isset($cae[$row['landingpage_id']])){

							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('lp' . $row['landingpage_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}

							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['landingpage_id'].'-'.$keyword;
								echo '---- INFO '.$row['landingpage_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- INFO '.$row['landingpage_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['landingpage_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='landingpage_id=". (int)$row['landingpage_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");

						} else {
							echo '---- INFO '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('information_attribute_id')){
					echo '-- START INFORMATIONS ATTRIBUTES'.PHP_EOL;
					$informations_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'information_attribute_id=%' AND language_id = '". $language_id ."'");
					
					$cae = array();
					unset($row);
					foreach ($informations_url_exists_query->rows as $row){
						$tmp = str_replace('information_attribute_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}
					
					$all_informations_query = $this->db->query("SELECT i.information_attribute_id, id.title as name 
						FROM information_attribute i 
						JOIN information_attribute_description id ON i.information_attribute_id = id.information_attribute_id
						JOIN information_attribute_to_store i2s ON i.information_attribute_id = i2s.information_attribute_id 
						WHERE id.language_id = '". $language_id ."'
						AND i2s.store_id = '" . $store_id . "'
						AND LENGTH(id.title)>0 
						AND i.status = 1");
					
					unset($row);
					foreach ($all_informations_query->rows as $row){				
						if (!isset($cae[$row['information_attribute_id']])){
							
							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('ia' . $row['information_attribute_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}

							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['information_attribute_id'].'-'.$keyword;
								echo '---- INFOATT '.$row['information_attribute_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- INFOATT '.$row['information_attribute_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}
							
							$cae[$row['information_attribute_id']] = $keyword;
							
							$this->db->query("INSERT INTO url_alias SET query='information_attribute_id=". (int)$row['information_attribute_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");
							
						} else {
							echo '---- INFOATT '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('actions_id')){
					echo '-- START ACTIONS'.PHP_EOL;
					$action_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'actions_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($action_url_exists_query->rows as $row){
						$tmp = str_replace('actions_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_actions_query = $this->db->query("SELECT a.*, ad.caption
						FROM actions a
						LEFT JOIN actions_description ad ON a.actions_id = ad.actions_id
						WHERE ad.language_id = '". $language_id ."'				
						AND LENGTH(ad.caption)>0 
						AND a.status = 1");

					unset($row);
					foreach ($all_actions_query->rows as $row){				
						if (!isset($cae[$row['actions_id']])){

							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('a' . $row['actions_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['caption']), 80, $language['code']);
							}

							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['actions_id'].'-'.$keyword;
								echo '---- ACTION '.$row['actions_id'].' : '.$row['caption'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- ACTION '.$row['actions_id'].' : '.$row['caption'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$this->db->query("INSERT INTO url_alias SET query='actions_id=". (int)$row['actions_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");

						} else {
							echo '---- ACTION '.$row['caption'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('collection_id')){
					echo '-- START COLLECTIONS'.PHP_EOL;
					$collection_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'collection_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($collection_url_exists_query->rows as $row){
						$tmp = str_replace('collection_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}

					$all_collection_query = $this->db->query("SELECT c.collection_id, c.name 
						FROM collection c 
						JOIN collection_description cd ON c.collection_id = cd.collection_id 
						JOIN collection_to_store c2s ON c.collection_id = c2s.collection_id
						WHERE cd.language_id = '". $language_id ."'
						AND c2s.store_id = '" . $store_id . "'
						AND LENGTH(c.name)>0");

					unset($row);
					foreach ($all_collection_query->rows as $row){				
						if (!isset($cae[$row['collection_id']])){
							//now urlify
							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('co' . $row['collection_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}


							//check for duplicate
							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['collection_id'].'-'.$keyword;
								echo '---- COLLECTIONS '.$row['collection_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- COLLECTIONS '.$row['collection_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['collection_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='collection_id=". (int)$row['collection_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");				

						} else {
							echo '---- COLLECTIONS '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}

				if ($this->url->checkIfGenerate('product_id')){
					echo '-- START PRODUCTS'.PHP_EOL;
					$products_url_exists_query = $this->db->query("SELECT query, keyword FROM url_alias WHERE query LIKE 'product_id=%' AND language_id = '". $language_id ."'");

					$cae = array();
					unset($row);
					foreach ($products_url_exists_query->rows as $row){
						$tmp = str_replace('product_id=', '', $row['query']);
						$cae[(int)$tmp] = $row['keyword'];
					}				

					$all_products_query = $this->db->query("SELECT p.product_id, pd.name 
						FROM product p 
						JOIN product_description pd ON p.product_id = pd.product_id 
						JOIN product_to_store p2s ON p.product_id = p2s.product_id 
						WHERE pd.language_id = '". $language_id ."'
						AND p2s.store_id = '" . $store_id . "'
						AND LENGTH(pd.name)>0 
						AND p.status = 1");

					unset($row);
					foreach ($all_products_query->rows as $row){				
						if (!isset($cae[$row['product_id']])){
							//now urlify
							if ($this->config->get('config_seo_url_from_id')){
								$keyword = URLify::slug(simple_rms('p' . $row['product_id']), 80, $language['code']);
							} else {
								$keyword = URLify::slug(simple_rms($row['name']), 80, $language['code']);
							}

							//check for duplicate
							if (in_array($keyword, $cae)){						
								$keyword = substr(md5(time()), 0, 3).'-'.(int)$row['product_id'].'-'.$keyword;
								echo '---- PRODUCT '.$row['product_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							} else {
								echo '---- PRODUCT '.$row['product_id'].' : '.$row['name'].' NEW KEY: ' . $keyword . PHP_EOL;
							}

							$cae[$row['product_id']] = $keyword;

							$this->db->query("INSERT INTO url_alias SET query='product_id=". (int)$row['product_id'] ."', keyword = '" . $this->db->escape($keyword) . "', language_id = '". $language_id ."'");				

						} else {
							echo '---- PRODUCT '.$row['name'].' EXISTS!'.PHP_EOL;
						}
					}
				}
			}	
		}
	}	
}	
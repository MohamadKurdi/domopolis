<?php
	class ControllerModuleSeogen extends Controller {
		
		private $error = array();
		
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
		
		public function index() {
			
			
			
			$this->data += $this->load->language('module/seogen');
			
			$this->document->setTitle(strip_tags($this->language->get('heading_title')));
			
			$this->load->model('setting/setting');
			
			$this->data['token'] = $this->session->data['token'];
			
			if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->request->post['seogen_status'] = isset($this->request->post['seogen_status']) ? 1 : 0;
				
				if (!isset($this->request->post['seogen']['seogen_overwrite'])){
					$this->request->post['seogen']['seogen_overwrite'] = 0;
				}
				$this->model_setting_setting->editSetting('seogen', $this->request->post);
				
				//	var_dump($this->request->post['seogen']);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('module/seogen', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if(isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->load->model('localisation/language');
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/seogen', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('module/seogen', 'token=' . $this->session->data['token']);
			$this->data['generate'] = $this->url->link('module/seogen/generate', 'token=' . $this->session->data['token']);
			$this->data['generate_test'] = $this->url->link('module/seogen/generate_test', 'token=' . $this->session->data['token']);
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
			
			$this->load->model('design/layout');
			$this->data['layouts'] = $this->model_design_layout->getLayouts();
			
			$this->load->model('catalog/category');
			
			// function from 1.5.4.1
			$categories = $this->model_catalog_category_getAllCategories();
			
			$this->data['categories'] = $this->getAllCategories($categories);
			
			if(isset($this->request->post['seogen'])) {
				$this->data['seogen'] = $this->request->post['seogen'];
				} elseif($this->config->get('seogen')) {
				$this->data['seogen'] = $this->config->get('seogen');
			}
			
			foreach ($this->data['languages'] as $language) {
				if (!isset($this->data['seogen'][$language['language_id']]['only_categories'])){
					$this->data['seogen'][$language['language_id']]['only_categories'] = array();
				}
			}
			
			if (!isset($this->data['seogen']['only_categories'])){
				$this->data['seogen']['only_categories'] = array();
			}
			
			$default_tags = $this->getDefaultTags();
			
			foreach($default_tags['seogen'] as $k => $v) {
				foreach ($this->data['languages'] as $language) {
					if(!isset($this->data['seogen'][$language['language_id']][$k])) {											
						$this->data['seogen'][$language['language_id']][$k] = $v;
					}
				}
			}
		
			$this->data['seogen_status'] = $this->config->get('seogen_status');
			if(isset($this->request->post['seogen_status'])) {
				$this->data['seogen_status'] = $this->request->post['seogen_status'];
			}
			
			$this->template = 'module/seogen.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function getDefaultTags(){
			$this->load->model('localisation/language');
			$ls = $this->model_localisation_language->getLanguages();	
			
			$seogen = array('seogen_status' => 1,
			'seogen' => array(
			'seogen_overwrite' => 1,
			'categories_template' => $this->language->get('text_categories_tags'),
			'products_template' => $this->language->get('text_products_tags'),
			'manufacturers_template' => $this->language->get('text_manufacturers_tags'),
			'collections_template' => $this->language->get('text_collections_tags'),
			'informations_template' => $this->language->get('text_informations_tags'),
			));
			foreach ($ls as $l) {
				
				$seogen['seogen'][$l['language_id']] = array(
				
				'categories_description_template' => $this->language->get('text_categories_description_tags'),
				'categories_meta_description_limit' => 160,						
				'products_model_template' => $this->language->get('text_products_model_tags'),
				'products_description_template' => $this->language->get('text_products_description_tags'),
				'products_meta_description_limit' => 160,
				'products_img_alt_template' => $this->language->get('text_products_img_alt'),
				'products_img_title_template' => $this->language->get('text_products_img_title'),						
				'manufacturers_description_template' => $this->language->get('text_manufacturers_description_tags'),
				'manufacturers_products_title_template' => $this->language->get('text_manufacturers_description_tags'),
				'manufacturers_products_meta_description_template' => $this->language->get('text_manufacturers_description_tags'),				
				'collections_description_template' => $this->language->get('text_collections_description_tags'),
				
				);					
			}		
			
			$query = $this->db->query("DESC `" . DB_PREFIX . "category_description`");
			foreach($query->rows as $row) {
				foreach ($ls as $l) {
					if($row['Field'] == "seo_title") {
						$seogen['seogen'][$l['language_id']]['categories_title_template'] = $this->language->get('text_categories_title_tags');				
						} elseif($row['Field'] == "seo_h1") {
						$seogen['seogen'][$l['language_id']]['categories_h1_template'] = $this->language->get('text_categories_h1_tags');
						} elseif($row['Field'] == "meta_description") {
						$seogen['seogen'][$l['language_id']]['categories_meta_keyword_template'] = $this->language->get('text_categories_meta_keyword_tags');
						} elseif($row['Field'] == "meta_keyword") {
						$seogen['seogen'][$l['language_id']]['categories_meta_description_template'] = $this->language->get('text_categories_meta_description_tags');
					}
				}			
			}				
			
			$query = $this->db->query("DESC `" . DB_PREFIX . "product_description`");
			foreach($query->rows as $row) {
				foreach ($ls as $l) {
					if($row['Field'] == "seo_title") {
						$seogen['seogen'][$l['language_id']]['products_title_template'] = $this->language->get('text_products_title_tags');
						} elseif($row['Field'] == "seo_h1") {
						$seogen['seogen'][$l['language_id']]['products_h1_template'] = $this->language->get('text_products_h1_tags');
						} elseif($row['Field'] == "meta_description") {
						$seogen['seogen'][$l['language_id']]['products_meta_keyword_template'] = $this->language->get('text_products_meta_keyword_tags');
						} elseif($row['Field'] == "meta_keyword") {
						$seogen['seogen'][$l['language_id']]['products_meta_description_template'] = $this->language->get('text_products_meta_description_tags');
					}
				}
			}
			
			$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
			if($query->num_rows) {
				$query = $this->db->query("DESC `" . DB_PREFIX . "manufacturer_description`");
				foreach ($ls as $l) {
					foreach($query->rows as $row) {
						if($row['Field'] == "seo_title") {
							$seogen['seogen'][$l['language_id']]['manufacturers_title_template'] = $this->language->get('text_manufacturers_title_tags');
							} elseif($row['Field'] == "seo_h1") {
							$seogen['seogen'][$l['language_id']]['manufacturers_h1_template'] = $this->language->get('text_manufacturers_h1_tags');
							} elseif($row['Field'] == "meta_description") {
							$seogen['seogen'][$l['language_id']]['manufacturers_meta_keyword_template'] = $this->language->get('text_manufacturers_meta_keyword_tags');
							} elseif($row['Field'] == "meta_keyword") {
							$seogen['seogen'][$l['language_id']]['manufacturers_meta_description_template'] = $this->language->get('text_manufacturers_meta_description_tags');
							} elseif(in_array($row['Field'], array('products_title', 'products_meta_description', 'collections_title', 'collections_meta_description', 'categories_title', 'categories_meta_description', 'articles_title', 'articles_meta_description', 'newproducts_title', 'newproducts_meta_description', 'special_title', 'special_meta_description'))) {
							
							
							$seogen['seogen'][$l['language_id']]['manufacturers_'. $row['Field'] .'_template'] = $this->language->get('text_manufacturers_meta_description_tags');
							
						}
					}
				}
			}
			
			$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "collection_description'");
			if($query->num_rows) {
				$query = $this->db->query("DESC `" . DB_PREFIX . "collection_description`");
				foreach ($ls as $l) {
					foreach($query->rows as $row) {
						if($row['Field'] == "seo_title") {
							$seogen['seogen'][$l['language_id']]['collections_title_template'] = $this->language->get('text_collections_title_tags');
							} elseif($row['Field'] == "seo_h1") {
							$seogen['seogen'][$l['language_id']]['collections_h1_template'] = $this->language->get('text_collections_h1_tags');
							} elseif($row['Field'] == "meta_description") {
							$seogen['seogen'][$l['language_id']]['collections_meta_keyword_template'] = $this->language->get('text_collections_meta_keyword_tags');
							} elseif($row['Field'] == "meta_keyword") {
							$seogen['seogen'][$l['language_id']]['collections_meta_description_template'] = $this->language->get('text_collections_meta_description_tags');
						}
					}
				}
			}
			
			$query = $this->db->query("DESC `" . DB_PREFIX . "information_description`");
			foreach($query->rows as $row) {
				foreach ($ls as $l) {
					if($row['Field'] == "seo_title") {
						$seogen['seogen'][$l['language_id']]['informations_title_template'] = $this->language->get('text_informations_title_tags');
						} elseif($row['Field'] == "seo_h1") {
						$seogen['seogen'][$l['language_id']]['informations_h1_template'] = $this->language->get('text_informations_h1_tags');
						} elseif($row['Field'] == "meta_description") {
						$seogen['seogen'][$l['language_id']]['informations_meta_keyword_template'] = $this->language->get('text_informations_meta_keyword_tags');
						} elseif($row['Field'] == "meta_keyword") {
						$seogen['seogen'][$l['language_id']]['informations_meta_description_template'] = $this->language->get('text_informations_meta_description_tags');
					}
				}
			}
			return $seogen;
		}
		
		public function install() {
			$this->load->language('module/seogen');
			$this->load->model('setting/setting');
			$seogen = $this->getDefaultTags();		
			
			$this->model_setting_setting->editSetting('seogen', $seogen);
		}
		
		public function testProductDescription(){
			$this->load->model('module/seogen');
			
			$query = $this->db->query("SELECT product_id FROM product_description WHERE LENGTH(name) > 5 AND LENGTH(description) > 1 ORDER BY RAND() LIMIT 1");
			
			$product_id = $query->row['product_id'];
			
		}
		
		public function generate_test() {
			if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['name'])) {
				$this->load->language('module/seogen');
				$this->load->model('module/seogen');
				$name = $this->request->post['name'];
				$language_id = $this->request->post['language_id'];
																
				$data = $this->request->post['seogen'][$this->request->post['language_id']];
				$data['language_id'] = $language_id;
				
				if($name == 'categories') {
					
					} elseif($name == 'products') {
					$query = $this->db->query("SELECT product_id FROM product_description WHERE LENGTH(name) > 5 AND LENGTH(description) > 1 ORDER BY RAND() LIMIT 1");			
					$product_id = $query->row['product_id'];
					
					$this->model_module_seogen->generateProduct($this->model_module_seogen->getProducts($data, $product_id)[0], $data, $this->request->post['field']);
					} elseif($name == 'manufacturers') {
					
					} elseif($name == 'collections') {
					
					} elseif($name == 'informations') {
					
				}
				
			}
			
		}

		public function cron(){			
			$this->load->language('module/seogen');
			$this->load->model('module/seogen');
			$this->load->model('localisation/language');

			$seogen = $this->config->get('seogen');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language){
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
		
		public function generate() {
			if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['name']) && $this->validate()) {
				$this->load->language('module/seogen');
				$this->load->model('module/seogen');
				$name = $this->request->post['name'];
				$language_id = $this->request->post['language_id'];
				
				if($name == 'categories') {
					$this->model_module_seogen->generateCategories($this->request->post['seogen'], $language_id);
					} elseif($name == 'products') {
					$this->model_module_seogen->generateProducts($this->request->post['seogen'], $language_id);
					} elseif($name == 'manufacturers' || $name == 'manufacturers-tags') {
					$this->model_module_seogen->generateManufacturers($this->request->post['seogen'], $language_id);
					} elseif($name == 'collections') {
					$this->model_module_seogen->generateCollections($this->request->post['seogen'], $language_id);
					} elseif($name == 'informations') {
					$this->model_module_seogen->generateInformations($this->request->post['seogen'], $language_id);
				}
				$this->response->setOutput($this->language->get('text_success_generation'));
				//	$this->saveSettings($this->request->post['seogen']);
			}
		}
		
		
		private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
			$output = array();
			
			if (array_key_exists($parent_id, $categories)) {
				if ($parent_name != '') {
					$parent_name .= $this->language->get('text_separator');
				}
				
				foreach ($categories[$parent_id] as $category) {
					$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
					);
					
					$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
				}
			}
			
			return $output;
		}
		
		public function model_catalog_category_getAllCategories() {
			$category_data = $this->cache->get('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
			
			if (!$category_data || !is_array($category_data)) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ORDER BY c.parent_id, c.sort_order, cd.name");
				
				$category_data = array();
				foreach ($query->rows as $row) {
					$category_data[$row['parent_id']][$row['category_id']] = $row;
				}
				
				$this->cache->set('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $category_data);
			}
			
			return $category_data;
		}
		
		private function saveSettings($data) {
			$seogen_status = $this->config->get('seogen_status');
			$seogen = $this->config->get('seogen');
			foreach($data as $key => $val) {
				if(in_array($key, array_keys($seogen))) {
					$seogen[$key] = $val;
				}
			}
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('seogen', array('seogen' => $seogen, 'seogen_status' => $seogen_status));
		}
		
		private function validate() {
			if(!$this->user->hasPermission('modify', 'module/seogen')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if(!$this->error) {
				return true;
				} else {
				return false;
			}
		}
	}
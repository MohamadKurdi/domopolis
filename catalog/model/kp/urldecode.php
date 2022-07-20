<?
	class ModelKpUrlDecode extends Model {
		private $store_id = 0;
		
		private function validateProduct($product_id){
			return $this->db->query("SELECT * FROM product_to_store WHERE store_id = '" . $this->store_id . "' AND product_id = '" . (int)$product_id . "'")->num_rows;	
		}
		
		private function validateCategory($category_id){
			return $this->db->query("SELECT * FROM category_to_store WHERE store_id = '" . $this->store_id . "' AND category_id = '" . (int)$category_id . "'")->num_rows;				
		}
		
		private function validateManufacturer($manufacturer_id){
			return $this->db->query("SELECT * FROM manufacturer_to_store WHERE store_id = '" . $this->store_id . "' AND manufacturer_id = '" . (int)$manufacturer_id . "'")->num_rows;			
		}
		
		private function validateCollection($collection_id){
			return $this->db->query("SELECT * FROM collection_to_store WHERE store_id = '" . $this->store_id . "' AND collection_id = '" . (int)$collection_id . "'")->num_rows;			
		}
		
		private function validateInformation($information_id){
			return $this->db->query("SELECT * FROM information_to_store WHERE store_id = '" . $this->store_id . "' AND information_id = '" . (int)$information_id . "'")->num_rows;			
		}
		
		private function validateIDParam($idParam){
			
			if ($idParam['key'] == 'product_id'){
				return $this->validateProduct($idParam['value']);
			}
			
			if ($idParam['key'] == 'manufacturer_id'){
				return $this->validateManufacturer($idParam['value']);
			}
			
			if ($idParam['key'] == 'information_id'){
				return $this->validateInformation($idParam['value']);
			}
			
			if ($idParam['key'] == 'collection_id'){
				return $this->validateCollection($idParam['value']);
			}

			if ($idParam['key'] == 'intersection_id'){
				return $this->validateCategory($idParam['value']);
			}
			
			if ($idParam['key'] == 'path'){
				$parts = explode('_', (string)$idParam['value']);
				$category_id = (int)array_pop($parts);
				
				return $this->validateCategory($category_id);
			}
			
		}
		
		private function rebuildURI($uri){
			$params = array(
			'page',
			'sort',
			'order',
			'limit',
			'mfp'
			);
			
			$data = array();
			
			foreach ($params as $param){
				if (!empty($this->request->get[$param])){
					$data[$param] = $this->request->get[$param];
				}			
			}
			
			if ($data){
				return $uri . '?' . http_build_query($data);
			}
			
			return $uri;
		}
		
		private function findIDparam(){
			
			$idParams = [];		
			
			foreach ($this->request->get as $key => $value){	
				if (stripos($key, '_id')){
					$idParams[] = array(
					'key' 	=> $key,
					'value' => $value
					);
				}			
			}
			
			foreach ($this->request->get as $key => $value){				
				if (stripos($key, 'path') !== false){
					$idParams[] = array(
					'key' 	=> $key,
					'value' => $value
					);
				}			
			}
			
			return array_reverse($idParams);
		}
		
		private function getStoresByLanguageID($code){
			$stores = array();
			
			$query = $this->db->query("SELECT store_id FROM setting WHERE (`key` = 'config_language' AND `value` = '" .$code. "')
			OR (`key` = 'config_second_language' AND `value` = '" . $code . "')");
			
			foreach ($query->rows as $row){
				$stores[] = $row['store_id'];
			}
			
			return $stores;
		}
		
		private function thisIsUnroutedURI($link){	
			if (stripos($link, 'index.php') !== false){
				return true;
			}
			
			return false;
		}
		
		private function buildCacheString(){
			
			$request_uri_md5  	= md5($this->request->server['REQUEST_URI']);
			$store_id    		= $this->config->get('config_store_id');
			$language_id 		= $this->config->get('config_language_id');
			
			return 'hreflangs.' . $request_uri_md5 . '.' . $store_id . '.' . $language_id;
			
		}
		
		public function decodeURI(){
			
			$links = $this->cache->get($this->buildCacheString());

			if (!$links){
				
				$links = array();
				$this->load->model('localisation/language');
				$this->load->model('setting/setting');
				
				$tmp_config_ssl = $this->config->get('config_ssl');
				$current_language = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
				$current_urlcode = $current_language['urlcode'];
				
				foreach ($this->model_localisation_language->getLanguages() as $language){	
					
					if ($language['language_id'] == 26){
						continue;
					}
					
					$stores = $this->getStoresByLanguageID($language['code']);
					
					foreach ($stores as $store_id){
						$this->store_id = $store_id;
						$link = false;
						
						$store_url = $this->model_setting_setting->getKeyValue('config_ssl', $store_id);
						
						$urlcode = '';
						if ($language['urlcode']){
							$urlcode = $language['urlcode'] . '/';
							$store_url = str_replace('/' . $urlcode, '', $store_url);
						}
						
						
						$store_url = trim($store_url, '/');
						
						$this->config->set('config_ssl', $store_url .'/'. $urlcode);
						
						if ($idParams = $this->findIDparam()){

							$validatedParams = [];
							foreach ($idParams as $idParam){
								if ($this->validateIDParam($idParam)){
									$validatedParams[] = ($idParam['key'] . '=' . $idParam['value']);
								}
							}

							if (count($validatedParams) == 1){
								$link = $this->url->link($this->request->get['route'], $validatedParams[0], 'SSL', $language['language_id']);
							} else {
								$link = $this->url->link($this->request->get['route'], implode('&', $validatedParams), 'SSL', $language['language_id']);
							}


							} else {
							$link = $this->url->link($this->request->get['route'], '', 'SSL', $language['language_id']);
						}
						
						
						if ($current_urlcode){
							$link = str_replace($store_url . '/' . $current_urlcode, $store_url, $link);				
						}
						
						if ($link && !$this->thisIsUnroutedURI($link)){
							$links[$language['language_id']] = array(
							'link' 			=> trim($this->rebuildURI($link), '/'),
							'code'			=> $language['code'],
							'hreflang'		=> $language['hreflang'],
							'language_id' 	=> $language['language_id'] 
							);
						}
			
					}
				}
				
				$this->cache->set($this->buildCacheString(), $links);
				
				$this->config->set('config_ssl', $tmp_config_ssl);
				
			}
			return $links;
		}
	}																				
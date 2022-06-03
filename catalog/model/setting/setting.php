<?php 
	class ModelSettingSetting extends Model {

		public function loadSettings($store_id){
			
			$query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");				
			foreach ($query->rows as $setting) {
				if (!$setting['serialized']) {
					$this->config->set($setting['key'], $setting['value']);
					} else {
					$this->config->set($setting['key'], unserialize($setting['value']));
				}
			}
			
			$query = $this->db->non_cached_query("SELECT * FROM language"); 			
			foreach ($query->rows as $result) {
				$languages[$result['code']] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'locale'      => $result['locale'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
				);
			}
			
			$this->config->set('config_store_id', $store_id);
			$this->config->set('config_language_id', $languages[$this->config->get('config_language')]['language_id']);
			$this->currency->set($this->config->get('config_regional_currency'));						
			
			return $this;
			
		}

		
		public function getSetting($group, $store_id = 0) {
			
			$data = $this->cache->get('data.structure.'. $this->db->escape($group) .'.'. (int)$store_id);
			
			if (!$data) {
				
				$data = array(); 
				
				$query = $this->db->query("SELECT * FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
				
				foreach ($query->rows as $result) {
					if (!$result['serialized']) {
						$data[$result['key']] = $result['value'];
						} else {
						$data[$result['key']] = unserialize($result['value']);
					}
				}
				
				$this->cache->set('data.structure.'. $this->db->escape($group) .'.'. (int)$store_id, $data);
				
			}
			return $data;
		}
		
		
		public function getKeySettingValue($group, $key, $store_id = 0) {
			$data = array(); 
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' LIMIT 1");		
			$result = $query->row;
			
			if ($result) {
				if (!$result['serialized']){
					return $result['value'];			
					} else {
					return 	unserialize($result['value']);
				}
				} else {
				return false;
			}
		}
		
		public function getKeyValue($key, $store_id = 0) {
			$data = array(); 
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "' LIMIT 1");		
			$result = $query->row;
			
			if ($result) {
				if (!$result['serialized']){
					return $result['value'];			
					} else {
					return 	unserialize($result['value']);
				}
				} else {
				return false;
			}
		}
	}	
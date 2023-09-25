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
		$data = array(); 
		
		$query = $this->db->query("SELECT * FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
		
		foreach ($query->rows as $result) {

			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	
	public function getRewardPlus($store_id = 0){
		
		$query = $this->db->query("SELECT SUM(points) as value FROM `customer_reward` WHERE points > 0 AND customer_id IN (SELECT customer_id FROM customer WHERE store_id = '" . (int)$store_id . "')");
		
		return $query->row['value'];
	
	}
	
	public function getRewardMinus($store_id = 0){
		
		$query = $this->db->query("SELECT SUM(points) as value FROM `customer_reward` WHERE points < 0 AND customer_id IN (SELECT customer_id FROM customer WHERE store_id = '" . (int)$store_id . "')");
		
		return $query->row['value'];
	
	}
	
	public function getRewardTotal($store_id = 0){
		
		$query = $this->db->query("SELECT SUM(points) as value FROM `customer_reward` WHERE customer_id IN (SELECT customer_id FROM customer WHERE store_id = '" . (int)$store_id . "')");
		
		return $query->row['value'];
	
	}
	
	public function getRewardOrders($store_id = 0){
		
		$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as value FROM `order` WHERE store_id = '" . (int)$store_id . "' AND order_id IN (SELECT order_id FROM order_total WHERE code = 'reward')");
		
		return $query->row['value'];
	
	}
	
	public function getCounter($counter, $store_id = 0){
		
		$query = $this->db->query("SELECT value FROM `counters` WHERE counter = '". $this->db->escape($counter) ."' AND store_id = '" . (int)$store_id . "'");
		
		return $query->row['value'];
	
	}
	
	public function getCounterTotal($counter){
		$query = $this->db->query("SELECT SUM(value) as total FROM `counters` WHERE counter = '". $this->db->escape($counter) ."' GROUP BY counter");
		
		return $query->row['total'];
	}
	
	public function getPWAOrders($store_id){		
		$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order` WHERE pwa = 1 AND store_id = '" . (int)$store_id . "' AND order_status_id > 0");
		
		return $query->row['total'];
	}
	
	public function getPWAOrdersTotal(){		
		$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order` WHERE pwa = 1 AND order_status_id > 0");
		
		return $query->row['total'];
	}
	
	public function getYAMTotalOrders(){
		$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as total FROM `order` WHERE yam = 1 AND order_status_id > 0 AND store_id = 0 AND order_status_id <> '" . $this->config->get('config_cancelled_status_id') . "' AND yam_fake = 0");
		
		return $query->row['total'];
	}
	
	public function getYAMOrdersTotal(){		
		$query = $this->db->query("SELECT SUM(total_national) as total FROM `order` WHERE yam = 1 AND order_status_id > 0 AND store_id = 0 AND order_status_id <> '" . $this->config->get('config_cancelled_status_id') . "'  AND yam_fake = 0");
		
		return $query->row['total'];	
	}

	public function getTranslatedTotal(){
		return $this->db->query("SELECT SUM(amount) as total FROM translate_stats")->row['total'];
	}

	public function getTranslatedTotalToday(){
		return $this->db->query("SELECT SUM(amount) as total FROM translate_stats WHERE DATE(time) = DATE(NOW())")->row['total'];
	}

	public function getTranslatedTotalHour(){
		return $this->db->query("SELECT SUM(amount) as total FROM translate_stats WHERE  time >= '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'")->row['total'];
	}

	public function getTranslatedTotalYesterday(){
		return $this->db->query("SELECT SUM(amount) as total FROM translate_stats WHERE  DATE(time) = '" . date('Y-m-d', strtotime('-1 day')) . "'")->row['total'];
	}

	public function getTranslatedTotalWeek(){
		return $this->db->query("SELECT SUM(amount) as total FROM translate_stats WHERE  time >= '" . date('Y-m-d H:i:s', strtotime('-1 week')) . "'")->row['total'];
	}

	public function getTranslatedTotalMonth(){
		return $this->db->query("SELECT SUM(amount) as total FROM translate_stats WHERE  time >= '" . date('Y-m-d H:i:s', strtotime('-1 month')) . "'")->row['total'];
	}
	
	
	public function getKeySettingValue($group, $key, $store_id = 0) {
		$data = array(); 
		
		$query = $this->db->query("SELECT * FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' LIMIT 1");		
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
		
		$query = $this->db->query("SELECT * FROM setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "' LIMIT 1");		
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
	
	public function editSetting($group, $data, $store_id = 0) {
		$this->db->query("DELETE FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$this->db->query("INSERT INTO setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			} else {
				$this->db->query("INSERT INTO setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
			}
		}
	}
	
	public function deleteSetting($group, $store_id = 0) {
		$this->db->query("DELETE FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
	}
	
	public function editSettingValue($group = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->db->query("UPDATE setting SET `value` = '" . $this->db->escape($value) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->db->query("UPDATE setting SET `value` = '" . $this->db->escape(serialize($value)) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "', serialized = '1'");
		}
	}	
}
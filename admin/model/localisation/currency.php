<?php
	class ModelLocalisationCurrency extends Model {
		public function addCurrency($data) {
			$this->db->query("INSERT INTO currency SET 
			title = '" . $this->db->escape($data['title']) . "',
			morph = '" . $this->db->escape($data['morph']) . "', 
			code = '" . $this->db->escape($data['code']) . "',
			flag = '" . $this->db->escape($data['flag']) . "',
			symbol_left = '" . $this->db->escape($data['symbol_left']) . "', 
			symbol_right = '" . $this->db->escape($data['symbol_right']) . "',
			decimal_place = '" . $this->db->escape($data['decimal_place']) . "', 
			value = '" . $this->db->escape($data['value']) . "',
			value_real = '" . $this->db->escape($data['value_real']) . "',
			value_uah_unreal = '" . (float)$data['value_uah_unreal'] . "',
			value_minimal = '" . (float)$data['value_minimal'] . "',
			value_eur_official = '" . (float)$data['value_eur_official'] . "',
			cryptopair = '" . $this->db->escape($data['cryptopair']) . "',
			cryptopair_value = '" . (float)$data['cryptopair_value'] . "',
			plus_percent = '" . $this->db->escape($data['plus_percent']) . "',
			auto_percent = '" . (int)$data['auto_percent'] . "', 
			status = '" . (int)$data['status'] . "',
			date_modified = NOW()");
			
			if ($this->config->get('config_currency_auto')) {
				$this->updateCurrencies(true);
			}
			
			$this->cache->delete('currency');
		}
		
		public function editCurrency($currency_id, $data) {
			$this->db->query("UPDATE currency SET 
			title = '" . $this->db->escape($data['title']) . "', 
			morph = '" . $this->db->escape($data['morph']) . "', 
			code = '" . $this->db->escape($data['code']) . "', 
			flag = '" . $this->db->escape($data['flag']) . "', 
			symbol_left = '" . $this->db->escape($data['symbol_left']) . "',
			symbol_right = '" . $this->db->escape($data['symbol_right']) . "',
			decimal_place = '" . $this->db->escape($data['decimal_place']) . "',
			value = '" . $this->db->escape($data['value']) . "',
			value_real = '" . $this->db->escape($data['value_real']) . "',
			value_minimal = '" . (float)$data['value_minimal'] . "',
			value_uah_unreal = '" . (float)$data['value_uah_unreal'] . "',
			value_eur_official = '" . (float)$data['value_eur_official'] . "',
			cryptopair = '" . $this->db->escape($data['cryptopair']) . "',
			cryptopair_value = '" . (float)$data['cryptopair_value'] . "',
			plus_percent = '" . $this->db->escape($data['plus_percent']) . "', 
			auto_percent = '" . (int)$data['auto_percent'] . "', 
			status = '" . (int)$data['status'] . "',
			date_modified = NOW() WHERE currency_id = '" . (int)$currency_id . "'");
			
			$this->cache->delete('currency');
		}
		
		public function deleteCurrency($currency_id) {
			$this->db->query("DELETE FROM currency WHERE currency_id = '" . (int)$currency_id . "'");
			
			$this->cache->delete('currency');
		}
		
		public function getCurrency($currency_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM currency WHERE currency_id = '" . (int)$currency_id . "'");
			
			return $query->row;
		}
		
		public function getCurrencyCodeById($currency_id) {
			$query = $this->db->query("SELECT code FROM currency WHERE currency_id = '" . (int)$currency_id . "'");
			
			return $query->row['code'];
		}
		
		public function getCurrencyByCode($currency) {
			$query = $this->db->query("SELECT DISTINCT * FROM currency WHERE code = '" . $this->db->escape($currency) . "'");
			
			return $query->row;
		}

		public function getCurrencySignByCode($currency) {
			$query = $this->db->query("SELECT DISTINCT * FROM currency WHERE code = '" . $this->db->escape($currency) . "'");
			
			return $query->row['symbol_left'];
		}
		
		public function getCurrencyNameByCode($currency) {
			$query = $this->db->query("SELECT title as name FROM currency WHERE code = '" . $this->db->escape($currency) . "'");
			
			return $query->row['name'];
		}
		
		public function getCurrencies($data = array()) {
			if ($data) {
				$sql = "SELECT * FROM currency";
				
				$sort_data = array(
				'title',
				'code',				
				'value',
				'date_modified'
				);
				
				if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
					$sql .= " ORDER BY " . $data['sort'];
					} else {
					$sql .= " ORDER BY title";
				}
				
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC";
					} else {
					$sql .= " ASC";
				}
				
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
				
				return $query->rows;
				} else {
				
					$currency_data = array();
					
					$query = $this->db->query("SELECT * FROM currency ORDER BY title ASC");
					
					foreach ($query->rows as $result) {
						$currency_data[$result['code']] = $result;
					}
				
				return $currency_data;
			}
		}
		
		public function updateCurrencies() {
			$this->currency->updateCryptoPairs();			

			$data = [];			
			$current_currencies = $this->currency->getCurrencies();
			$current_values = [];
			
			if ($fixerio = $this->currency->getFixerIoRates()) {								
				foreach ($fixerio as $currency_code => $rate){					
					$this->currency->updateCurrencyField($currency_code, 'value_real', $rate);
					$this->currency->updateCurrencyField($currency_code, 'value_eur_official', $rate);
				}
			}				
							
			if ($this->currency->getCurrency('KZT') && $value_real = $this->currency->getHalykbankRates()){
				$this->currency->updateCurrencyField('KZT', 'value_real', $value_real);
				
				$recalculated = $this->currency->recalculate('KZT', $value_real);

				if ($this->config->get('config_currency_auto') && $recalculated){
					$this->currency->updateCurrencyField('KZT', 'value', $recalculated['new_value']);					
				}				
			}

			if ($this->currency->getCurrency('UAH') && $value_real = $this->currency->getPrivatbankRates()){
				$this->currency->updateCurrencyField('UAH', 'value_real', $value_real);
				
				$recalculated = $this->currency->recalculate('UAH', $value_real);

				if ($this->config->get('config_currency_auto') && $recalculated){
					$this->currency->updateCurrencyField('UAH', 'value', $recalculated['new_value']);					
				}			
			}
			

			if ($this->currency->getCurrency('BYN') && $value_real = $this->currency->getAlfabankByRates()){				
				$this->currency->updateCurrencyField('BYN', 'value_real', $value_real);
				
				$recalculated = $this->currency->recalculate('BYN', $value_real);

				if ($this->config->get('config_currency_auto') && $recalculated){
					$this->currency->updateCurrencyField('BYN', 'value', $recalculated['new_value']);					
				}	
			}

			if ($this->currency->getCurrency('RUB') && $value_real = $this->currency->getRsbRates()){				
				$this->currency->updateCurrencyField('RUB', 'value_real', $value_real);
				
				$recalculated = $this->currency->recalculate('RUB', $value_real);

				if ($this->config->get('config_currency_auto') && $recalculated){
					$this->currency->updateCurrencyField('RUB', 'value', $recalculated['new_value']);					
				}	
			}
			
			$this->currency->updateCurrencyField($this->config->get('config_currency'), 'value_real', 1.00);
			$this->db->query("UPDATE currency SET old_value_real = value_real WHERE 1");
			$this->db->query("UPDATE currency SET old_value 	 = value WHERE 1");
		}
		
		public function getTotalCurrencies() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM currency");
			
			return $query->row['total'];
		}
	}											
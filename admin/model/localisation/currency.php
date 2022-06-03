<?php
	class ModelLocalisationCurrency extends Model {
		public function addCurrency($data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "currency SET 
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
			$this->db->query("UPDATE " . DB_PREFIX . "currency SET 
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
			plus_percent = '" . $this->db->escape($data['plus_percent']) . "', 
			auto_percent = '" . (int)$data['auto_percent'] . "', 
			status = '" . (int)$data['status'] . "',
			date_modified = NOW() WHERE currency_id = '" . (int)$currency_id . "'");
			
			$this->cache->delete('currency');
		}
		
		public function deleteCurrency($currency_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");
			
			$this->cache->delete('currency');
		}
		
		public function getCurrency($currency_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");
			
			return $query->row;
		}
		
		public function getCurrencyCodeById($currency_id) {
			$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");
			
			return $query->row['code'];
		}
		
		public function getCurrencyByCode($currency) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");
			
			return $query->row;
		}

		public function getCurrencySignByCode($currency) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");
			
			return $query->row['symbol_left'];
		}
		
		public function getCurrencyNameByCode($currency) {
			$query = $this->db->query("SELECT title as name FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");
			
			return $query->row['name'];
		}
		
		public function getCurrencies($data = array()) {
			if ($data) {
				$sql = "SELECT * FROM " . DB_PREFIX . "currency";
				
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
				$currency_data = $this->cache->get('currency');
				
				if (!$currency_data) {
					$currency_data = array();
					
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
					
					foreach ($query->rows as $result) {
						$currency_data[$result['code']] = array(
						'currency_id'   => $result['currency_id'],
						'title'         => $result['title'],
						'morph'         => $result['morph'],
						'code'          => $result['code'],
						'flag'          => $result['flag'],
						'symbol_left'   => $result['symbol_left'],
						'symbol_right'  => $result['symbol_right'],
						'decimal_place' => $result['decimal_place'],
						'value'         => $result['value'],
						'value_real'    => $result['value_real'],
						'value_uah_unreal' => $result['value_uah_unreal'],
						'plus_percent'  => $result['plus_percent'],
						'status'        => $result['status'],
						'date_modified' => $result['date_modified']
						);
					}
					
					$this->cache->set('currency', $currency_data);
				}
				
				return $currency_data;
			}
		}
		
		public function updateCurrencies($force = false, $cron = false, $bot = false, $return = false, $do_autoupdate = false) {
			
			if ($bot){
				$this->load->model('kp/bitrixBot');
			}
			
			
			$data = array();
			
			$force = true;
			
			if ($force) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "'");
				} else {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < '" .  $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");
			}
			
			$current_values_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE 1");
			$current_values = array();
			
			foreach ($current_values_query->rows as $cvq){
				
				if ($cvq['plus_percent'][0] == '+' || $cvq['plus_percent'][0] == 'p'){
					$pp = '+'.$cvq['plus_percent'];
					} elseif ($cvq['plus_percent'][0] == '-' || $cvq['plus_percent'][0] == 'm') {
					$pp = '-'.$cvq['plus_percent'];
					} else {
					$pp = '+'.$cvq['plus_percent'];
				}
				
				$current_values[$cvq['code']] = array(
				'value' => $cvq['value'],
				'old_value' => $cvq['old_value_real'],
				'plus_percent' => $pp,
				);
			}
			
			//fixer.io
			
			if ($bot){
				
				if ($do_autoupdate) {
					$message = 'Доброго утречка, господа. Давайте взглянем на курсы валют.';
					} else {
					$message = 'Добрый вечер, уважаемые. Перед сном проверим курсы валют.';
				}			
				
			}
			
			if ($cron){ echo PHP_EOL; }			
			if ($cron){ echo '[Курсы ЕЦБ, FIXER.IO API]'.PHP_EOL; }			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key=abfbaa76c3702a5bdd9ba42765858ee3&base=EUR&symbols=USD,RUB,KZT,UAH,BYN');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$content = curl_exec($curl);
			curl_close($curl);
			
			$json_array = json_decode($content, true);	
			
			if ($json_array && isset($json_array['success']) && $json_array['success']) {				
				
				if ($bot){									
					
					$attach = array(
					array(
					"MESSAGE" => "[B]Европейский Центральный Банк[/B]"
					)
					);
					
					$grid = array();
					
				}
				
				foreach ($json_array['rates'] as $currency_code => $rate){
					if ($cron){ echo '-> Курс ЕЦБ ' . $currency_code .' - EUR: ' . (float)$rate . PHP_EOL; }
					
					if ($bot) {
						$grid[] = array(
						"NAME" => 'EUR - ' . $currency_code,
						"VALUE" => number_format((float)$rate, 2),
						"DISPLAY" => "COLUMN",
						"WIDTH" => "100"
						);
					}
					
					if ($currency_code == 'BYN'){
						$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$rate . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('BYN') . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_eur_official = '" . (float)$rate . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('BYN') . "'");
						} else {
						$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$rate . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency_code) . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_eur_official = '" . (float)$rate . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency_code) . "'");
					}
				}
				
				if ($bot && $grid) {
					$attach[] = array("GRID" => $grid);
				}
				} else {
				
				$attach[] =	array(
				"MESSAGE" => " :?: :!: Я не могу получить курс ЕЦБ. Помогите, пожалуйста."
				);
				
			}
			
			if ($bot) {
				$attach[] = Array("DELIMITER" => Array(
				'SIZE' => 200,
				'COLOR' => "#c6c6c6"
				));
			}
			
			
			if ($cron){ echo PHP_EOL; }			
			if ($cron){ echo '[Курс KZT - EUR, Халикбанк хуяпи]'.PHP_EOL; }				
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://back.halykbank.kz/common/currency-history');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$content = curl_exec($curl);
			curl_close($curl);
			
			$content = json_decode($content, true);						
			$c = $content['data']['currencyHistory'][0]['cards']['EUR/KZT']['sell'];			
			
			if ((float)$c){
				
				$c = number_format((float)$c, 2);
				$value_real = $c;
				
				if ($cron){ echo '-> Реальный курс KZT - EUR: ' . (float)$c . PHP_EOL; }
				
				$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$value_real . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('KZT') . "'");
				
				//ТЕКУЩИЕ ЗНАЧЕНИЯ
				$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('KZT') . "' LIMIT 1");
				$old_value_real = $real_query->row['old_value_real'];
				
				$diff = false;
				if (round(($old_value_real - $value_real), 4) <> 0){	
					$diff = round(($old_value_real - $value_real), 4);
					if ($cron){ echo '-> Реальный курс изменился на ' . $diff . PHP_EOL; }
					} else {
					if ($cron){ echo '-> Реальный курс не изменился ' . $diff . PHP_EOL; }
				}
				
				//ПОДСЧЕТ КУРСА ВИТРИНЫ
				//1. логика автодобавления процента к курсу
				$new_value = ($value_real + $value_real / 100 * $real_query->row['auto_percent']);
				if ($cron){ echo '-> С автодобавлением процента новый курс витрины: ' . $new_value . PHP_EOL; }
				
				//2. логика заданного минимума
				if ($real_query->row['value_minimal'] && $real_query->row['value_minimal'] > 0){
					if ($cron){ echo '-> Задан минимум курса: ' . $real_query->row['value_minimal'] . PHP_EOL; }
					
					//старый курс - новый курс > 0 -> значит курс уменьшился
					if ($diff && $diff > 0 && abs($diff) > 0.1){
						//Если реальный курс уменьшился, то мы ничего не делаем:)
					}
					
					//старый курс - новый курс < 0 -> значит курс увеличился
					if (!($diff === false) && $diff < 0 && abs($diff) > 0.1){								
						//курс увеличился, значит мы добавим к значению старого курса разницу, но при этом проверим, не меньше ли она минимума и не меньше ли она значения с автопроцентом
						if ($cron){ echo '-> Курс увеличился на: ' . abs($diff) . PHP_EOL; }
						
						//если старое значение + разница в курсе больше минимума, то добавляем к старому значению разницу, переназначаем
						if (($real_query->row['old_value'] + abs($diff)) > $real_query->row['value_minimal']){
							$new_value = ($real_query->row['old_value'] + abs($diff));
							if ($cron){ echo '-> После увеличения реального курса новое значение курса витрины: ' . $new_value . PHP_EOL; }
						}
						
						//если старое значение + разница в курсе больше значения с автопроцентом и минимума, то переназначаем
						if (($real_query->row['old_value'] + abs($diff)) > $new_value){
							$new_value = ($real_query->row['old_value'] + abs($diff));
							if ($cron){ echo '-> Автопроцент больше, новое значение курса витрины: ' . $new_value . PHP_EOL; }
						}
						
						//ну и окончательная проверка, если где-то раньше что-то пошло не так
						if ($new_value < $real_query->row['value_minimal']){
							$new_value = $real_query->row['value_minimal'];
							if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
						}
					}
					
					if ($new_value < $real_query->row['value_minimal']){
						$new_value = $real_query->row['value_minimal'];
						if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
					}
					
				}
				
				if ($do_autoupdate){
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$new_value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('KZT') . "'");
				}
				
				$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('KZT') . "' LIMIT 1");
				$current_values['KZT']['value'] = $real_query->row['value'];
				
				
				if ($bot) {
					$attach[] =	array(
					"MESSAGE" => "[B]Казахстан, центральный банк[/B]" . " [I](авто +" . $real_query->row['auto_percent'] . "%)[/I]"
					);									
					
					$our = number_format($current_values['KZT']['value'], 2);
					if ((int)$current_values['KZT']['plus_percent']){
						$our .= ' ' . $current_values['KZT']['plus_percent'] . '%';	
					}
					
					$color = '#000000';
					$mdfr  = '';
					
					if ((float)$c > (float)number_format($current_values['KZT']['old_value'],2)){
						$mdfr = '↑';
						$color = '#ff0000';
					}
					
					if ((float)$c < (float)$current_values['KZT']['old_value']){
						$mdfr = '↓';
						$color = '#00ff00';
					}
					
					$txt = number_format((float)$c, 2) . ' ' . $mdfr . ' (' . $our . ')';
					
					$attach[] =	array(
					"GRID" => array(
					array(
					"NAME" => 'EUR - KZT',
					"VALUE" => $txt,
					"DISPLAY" => "COLUMN",
					"COLOR"  => $color,
					"WIDTH" => "100"
					)
					)
					);
				}
				
				} else {
				
				if ($bot) {
					
					$attach[] =	array(
					"MESSAGE" => " :?: :!: Витя, я не могу вытащить курс KZT. Помоги, пожалуйста."
					);
					
				}
				
			}
			
			if ($cron){ echo PHP_EOL; }			
			if ($cron){ echo '[Курс UAH - EUR, Приватбанк API]'.PHP_EOL; }			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$content = curl_exec($curl);
			curl_close($curl);
			
			$json_array = json_decode($content, true);
			foreach ($json_array as &$json){
				if ($json['base_ccy'] == 'UAH' && $json['ccy'] == 'EUR'){
					if ((float)$json['sale']) {
						
						$json['sale'] = number_format((float)$json['sale'], 2);						
						$value_real = $json['sale'];
						
						if ($cron){ echo '-> Реальный курс UAH - EUR: ' . (float)$value_real . PHP_EOL; }
						
						$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$value_real . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('UAH') . "'");
						
						//ТЕКУЩИЕ ЗНАЧЕНИЯ
						$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('UAH') . "' LIMIT 1");
						$old_value_real = $real_query->row['old_value_real'];
						
						$diff = false;
						if (round(($old_value_real - $value_real), 4) <> 0){	
							$diff = round(($old_value_real - $value_real), 4);
							if ($cron){ echo '-> Реальный курс изменился на ' . $diff . PHP_EOL; }
							} else {
							if ($cron){ echo '-> Реальный курс не изменился ' . $diff . PHP_EOL; }
						}
						
						//ПОДСЧЕТ КУРСА ВИТРИНЫ
						//1. логика автодобавления процента к курсу
						$new_value = ($value_real + $value_real / 100 * $real_query->row['auto_percent']);
						if ($cron){ echo '-> С автодобавлением процента новый курс витрины: ' . $new_value . PHP_EOL; }
						
						//2. логика заданного минимума
						if ($real_query->row['value_minimal'] && $real_query->row['value_minimal'] > 0){
							if ($cron){ echo '-> Задан минимум курса: ' . $real_query->row['value_minimal'] . PHP_EOL; }
							
							//старый курс - новый курс > 0 -> значит курс уменьшился
							if ($diff && $diff > 0 && abs($diff) > 0.1){
								//Если реальный курс уменьшился, то мы ничего не делаем:)
							}
							
							//старый курс - новый курс < 0 -> значит курс увеличился
							if (!($diff === false) && $diff < 0 && abs($diff) > 0.1){								
								//курс увеличился, значит мы добавим к значению старого курса разницу, но при этом проверим, не меньше ли она минимума и не меньше ли она значения с автопроцентом
								if ($cron){ echo '-> Курс увеличился на: ' . abs($diff) . PHP_EOL; }
								
								//если старое значение + разница в курсе больше минимума, то добавляем к старому значению разницу, переназначаем
								if (($real_query->row['old_value'] + abs($diff)) > $real_query->row['value_minimal']){
									$new_value = ($real_query->row['old_value'] + abs($diff));
									if ($cron){ echo '-> После увеличения реального курса новое значение курса витрины: ' . $new_value . PHP_EOL; }
								}
								
								//если старое значение + разница в курсе больше значения с автопроцентом и минимума, то переназначаем
								if (($real_query->row['old_value'] + abs($diff)) > $new_value){
									$new_value = ($real_query->row['old_value'] + abs($diff));
									if ($cron){ echo '-> Автопроцент больше, новое значение курса витрины: ' . $new_value . PHP_EOL; }
								}
								
								//ну и окончательная проверка, если где-то раньше что-то пошло не так
								if ($new_value < $real_query->row['value_minimal']){
									$new_value = $real_query->row['value_minimal'];
									if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
								}
							}
							
							if ($new_value < $real_query->row['value_minimal']){
								$new_value = $real_query->row['value_minimal'];
								if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
							}
							
						}
						
						if ($do_autoupdate){
							$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$new_value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('UAH') . "'");
						}
						
						$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('UAH') . "' LIMIT 1");
						$current_values['UAH']['value'] = $real_query->row['value'];
						
						if ($bot) {
							$attach[] =	array(
							"MESSAGE" => "[B]ПриватБанк Украина[/B]" . " [I](авто +" . $real_query->row['auto_percent'] . "%)[/I]"
							);
							
							
							$our = number_format($current_values['UAH']['value'], 2);
							if ((int)$current_values['UAH']['plus_percent']){
								$our .= ' ' . $current_values['UAH']['plus_percent'] . '%';	
							}
							
							$color = '#000000';
							$mdfr  = '';
							
							if ((float)$json['sale'] > (float)$current_values['UAH']['old_value']){
								$mdfr = '↑';
								$color = '#ff0000';
							}
							
							if ((float)$json['sale'] < (float)$current_values['UAH']['old_value']){
								$mdfr = '↓';
								$color = '#00ff00';
							}
							
							$txt = number_format((float)$json['sale'], 2) . ' ' . $mdfr . ' (' . $our . ')';
							
							$attach[] =	array(
							"GRID" => array(
							array(
							"NAME" => 'EUR - UAH',
							"VALUE" => $txt,
							"DISPLAY" => "COLUMN",
							"COLOR"  => $color,
							"WIDTH" => "100"
							)
							)
							);
						}
						
						
						
						}  else {
						
						if ($bot) {
							
							$attach[] =	array(
							"MESSAGE" => " :?: :!: Я не могу вытащить курс UAH. Помогите, пожалуйста."
							);
							
						}
						
					}
				}
			}
			
			if ($cron){ echo PHP_EOL; }			
			if ($cron){ echo '[Курс BYR - EUR, Альфа-хуяльфа паблик API]'.PHP_EOL; }					
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://developerhub.alfabank.by:8273/partner/1.0.1/public/rates');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$content = curl_exec($curl);
			curl_close($curl);
			
		/*	libxml_use_internal_errors(true);
			$f = new DOMDocument("1.0", "utf-8");
			$f->loadHTML($content);
			libxml_clear_errors();
			$finder = new DomXPath($f);
			
			$nodes = $finder->query( '/html/body/div[3]/div/div[1]/section/div/' );
			
			foreach ($nodes as $textNode) {
				echo $textNode->nodeValue;
			}
		*/
			$json_array = json_decode($content, true);
			$c = false;
			
			foreach ($json_array['rates'] as $json){				
				if ($json['sellIso'] == 'EUR' && $json['buyIso'] == 'BYN'){
					$c = $json['buyRate'];
					break;
				}
			}

			if ((float)$c){
				
				$c = number_format((float)$c, 2);
				$value_real = $c;
				
				if ($cron){ echo '-> Реальный курс BYR - EUR: ' . (float)$c . PHP_EOL; }
				
				$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$value_real . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('BYN') . "'");
				
				//ТЕКУЩИЕ ЗНАЧЕНИЯ
				$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('BYN') . "' LIMIT 1");
				$old_value_real = $real_query->row['old_value_real'];
				
				$diff = false;
				if (round(($old_value_real - $value_real), 4) <> 0){	
					$diff = round(($old_value_real - $value_real), 4);
					if ($cron){ echo '-> Реальный курс изменился на ' . $diff . PHP_EOL; }
					} else {
					if ($cron){ echo '-> Реальный курс не изменился ' . $diff . PHP_EOL; }
				}
				
				//ПОДСЧЕТ КУРСА ВИТРИНЫ
				//1. логика автодобавления процента к курсу
				$new_value = ($value_real + $value_real / 100 * $real_query->row['auto_percent']);
				if ($cron){ echo '-> С автодобавлением процента новый курс витрины: ' . $new_value . PHP_EOL; }
				
				//2. логика заданного минимума
				if ($real_query->row['value_minimal'] && $real_query->row['value_minimal'] > 0){
					if ($cron){ echo '-> Задан минимум курса: ' . $real_query->row['value_minimal'] . PHP_EOL; }
					
					//старый курс - новый курс > 0 -> значит курс уменьшился
					if ($diff && $diff > 0 && abs($diff) > 0.1){
						//Если реальный курс уменьшился, то мы ничего не делаем:)
					}
					
					//старый курс - новый курс < 0 -> значит курс увеличился
					if (!($diff === false) && $diff < 0 && abs($diff) > 0.1){								
						//курс увеличился, значит мы добавим к значению старого курса разницу, но при этом проверим, не меньше ли она минимума и не меньше ли она значения с автопроцентом
						if ($cron){ echo '-> Курс увеличился на: ' . abs($diff) . PHP_EOL; }
						
						//если старое значение + разница в курсе больше минимума, то добавляем к старому значению разницу, переназначаем
						if (($real_query->row['old_value'] + abs($diff)) > $real_query->row['value_minimal']){
							$new_value = ($real_query->row['old_value'] + abs($diff));
							if ($cron){ echo '-> После увеличения реального курса новое значение курса витрины: ' . $new_value . PHP_EOL; }
						}
						
						//если старое значение + разница в курсе больше значения с автопроцентом и минимума, то переназначаем
						if (($real_query->row['old_value'] + abs($diff)) > $new_value){
							$new_value = ($real_query->row['old_value'] + abs($diff));
							if ($cron){ echo '-> Автопроцент больше, новое значение курса витрины: ' . $new_value . PHP_EOL; }
						}
						
						//ну и окончательная проверка, если где-то раньше что-то пошло не так
						if ($new_value < $real_query->row['value_minimal']){
							$new_value = $real_query->row['value_minimal'];
							if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
						}
					}
					
					if ($new_value < $real_query->row['value_minimal']){
						$new_value = $real_query->row['value_minimal'];
						if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
					}
					
				}
				
				if ($do_autoupdate){
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$new_value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('BYN') . "'");
				}
				
				$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('BYN') . "' LIMIT 1");
				$current_values['BYN']['value'] = $real_query->row['value'];
				
				if ($bot) {
					$attach[] =	array(
					"MESSAGE" => "[B]NBRB Белоруссия[/B]" . " [I](авто +" . $real_query->row['auto_percent'] . "%)[/I]"
					);									
					
					$our = number_format($current_values['BYN']['value'], 2);
					if ((int)$current_values['BYN']['plus_percent']){
						$our .= ' ' . $current_values['BYN']['plus_percent']. '%';	
					}
					
					$color = '#000000';
					$mdfr  = '';
					
					if ((float)$c > (float)$current_values['BYN']['old_value']){
						$mdfr = '↑';
						$color = '#ff0000';
					}
					
					if ((float)$c < (float)$current_values['BYN']['old_value']){
						$mdfr = '↓';
						$color = '#00ff00';
					}
					
					$txt = number_format((float)$c, 2) . ' ' . $mdfr . ' (' . $our . ')';
					
					$attach[] =	array(
					"GRID" => array(
					array(
					"NAME" => 'EUR - BYR',
					"VALUE" => $txt,
					"DISPLAY" => "COLUMN",
					"COLOR"  => $color,
					"WIDTH" => "100"
					)
					)
					);
				}
				
				}  else {
				
				if ($bot) {
					
					$attach[] =	array(
					"MESSAGE" => " :?: :!: Я не могу вытащить курс BYR. Помогите, пожалуйста."
					);
					
				}
				
			}
			
			
			if ($cron){ echo PHP_EOL; }			
			if ($cron){ echo '[Курс RUB - EUR, RSB Парсер]'.PHP_EOL; }			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://www.rsb.ru/local/ajax/getcoursemass1.php?callback=test2&'.date('d.m.Y').'&course_type=cards&currency=eur&_=1596197030' . mt_rand(100,999));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			//	curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: www.rsb.ru', 'Referer: https://www.rsb.ru/courses/'));
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0");
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			//	curl_setopt($curl, CURLOPT_VERBOSE, true);
			$json = curl_exec($curl);
			curl_close($curl);
			
			
			/*	
				libxml_use_internal_errors(true);
				$f = new DOMDocument("1.0", "utf-8");
				$f->loadHTML($content);
				libxml_clear_errors();
				$finder = new DomXPath($f);
			*/
			
			//	$json = file_get_contents('https://www.rsb.ru/local/ajax/getcoursemass1.php?callback=test2&date='.date('d.m.Y').'&course_type=cash&currency=eur&_=15');
			$json = str_replace(array('test2(', ');'), '', $json);							
			
			$json = json_decode($json, true);
			
			$c = str_replace(',', '.',$json['currentItem']['sell']);
			
			if ((float)$c){
				
				$c = number_format((float)$c, 2);
				$value_real = $c;
				
				if ($cron){ echo '-> Реальный курс RUB - EUR: ' . (float)$c . PHP_EOL; }
				
				$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$value_real . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('RUB') . "'");
				
				//ТЕКУЩИЕ ЗНАЧЕНИЯ
				$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('RUB') . "' LIMIT 1");
				$old_value_real = $real_query->row['old_value_real'];
				
				$diff = false;
				if (round(($old_value_real - $value_real), 4) <> 0){	
					$diff = round(($old_value_real - $value_real), 4);
					if ($cron){ echo '-> Реальный курс изменился на ' . $diff . PHP_EOL; }
					} else {
					if ($cron){ echo '-> Реальный курс не изменился ' . $diff . PHP_EOL; }
				}
				
				//ПОДСЧЕТ КУРСА ВИТРИНЫ
				//1. логика автодобавления процента к курсу
				$new_value = ($value_real + $value_real / 100 * $real_query->row['auto_percent']);
				if ($cron){ echo '-> С автодобавлением процента новый курс витрины: ' . $new_value . PHP_EOL; }
				
				//2. логика заданного минимума
				if ($real_query->row['value_minimal'] && $real_query->row['value_minimal'] > 0){
					if ($cron){ echo '-> Задан минимум курса: ' . $real_query->row['value_minimal'] . PHP_EOL; }
					
					//старый курс - новый курс > 0 -> значит курс уменьшился
					if ($diff && $diff > 0 && abs($diff) > 0.1){
						//Если реальный курс уменьшился, то мы ничего не делаем:)
					}
					
					//старый курс - новый курс < 0 -> значит курс увеличился
					if (!($diff === false) && $diff < 0 && abs($diff) > 0.1){								
						//курс увеличился, значит мы добавим к значению старого курса разницу, но при этом проверим, не меньше ли она минимума и не меньше ли она значения с автопроцентом
						if ($cron){ echo '-> Курс увеличился на: ' . abs($diff) . PHP_EOL; }
						
						//если старое значение + разница в курсе больше минимума, то добавляем к старому значению разницу, переназначаем
						if (($real_query->row['old_value'] + abs($diff)) > $real_query->row['value_minimal']){
							$new_value = ($real_query->row['old_value'] + abs($diff));
							if ($cron){ echo '-> После увеличения реального курса новое значение курса витрины: ' . $new_value . PHP_EOL; }
						}
						
						//если старое значение + разница в курсе больше значения с автопроцентом и минимума, то переназначаем
						if (($real_query->row['old_value'] + abs($diff)) > $new_value){
							$new_value = ($real_query->row['old_value'] + abs($diff));
							if ($cron){ echo '-> Автопроцент больше, новое значение курса витрины: ' . $new_value . PHP_EOL; }
						}
						
						//ну и окончательная проверка, если где-то раньше что-то пошло не так
						if ($new_value < $real_query->row['value_minimal']){
							$new_value = $real_query->row['value_minimal'];
							if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
						}
					}
					
					if ($new_value < $real_query->row['value_minimal']){
						$new_value = $real_query->row['value_minimal'];
						if ($cron){ echo '-> После всех проверок курс меньше минимума, переназначаем, новый курс витрины: ' . $new_value . PHP_EOL; }
					}
					
				}
				
				if ($do_autoupdate){
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$new_value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('RUB') . "'");
				}
				
				$real_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape('RUB') . "' LIMIT 1");
				$current_values['RUB']['value'] = $real_query->row['value'];
				
				if ($bot) {
					$attach[] =	array(
					"MESSAGE" => "[B]RSB Россия[/B]" . " [I](авто +" . $real_query->row['auto_percent'] . "%)[/I]"
					);
					
					
					$our = number_format($current_values['RUB']['value'], 2);
					if ((int)$current_values['RUB']['plus_percent']){
						$our .= ' ' . $current_values['RUB']['plus_percent'] . '%';	
					}
					
					$color = '#000000';
					$mdfr  = '';
					
					if ((float)$c > (float)$current_values['RUB']['old_value']){
						$mdfr = '↑';
						$color = '#ff0000';
					}
					
					if ((float)$c < (float)$current_values['RUB']['old_value']){
						$mdfr = '↓';
						$color = '#00ff00';
					}
					
					$txt = number_format((float)$c, 2) . ' ' . $mdfr . ' (' . $our . ')';
					
					$attach[] =	array(
					"GRID" => array(
					array(
					"NAME" => 'EUR - RUB',
					"VALUE" => $txt,
					"DISPLAY" => "COLUMN",
					"COLOR"  => $color,
					"WIDTH" => "100"
					)
					)
					);
				}
				
				$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '" . (float)$c . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape('RUB') . "'");
				}  else {
				
				if ($bot) {
					
					$attach[] =	array(
					"MESSAGE" => " :?: :!: Я не могу вытащить курс RUB. Помогите, пожалуйста."
					);
					
				}
				
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "currency SET value_real = '1.00000', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");
			$this->db->query("UPDATE " . DB_PREFIX . "currency SET old_value_real = value_real WHERE 1");
			$this->db->query("UPDATE " . DB_PREFIX . "currency SET old_value = value WHERE 1");
			
			$attach[] = Array("DELIMITER" => Array(
			'SIZE' => 200,
			'COLOR' => "#c6c6c6"
			));
			$attach[] =	array(
			"MESSAGE" => "[I]в скобках - текущий курс на витрине магазина[/I]"
			);
			
			if ($do_autoupdate) {
				$attach[] = Array("DELIMITER" => Array(
				'SIZE' => 200,
				'COLOR' => "#c6c6c6"
				));
				$attach[] =	array(
				"MESSAGE" => " :!: я изменил курс на витрине!"
				);
			}
			
			
			$this->cache->delete('currency');
			
			if ($return){
				return array(
				'reply' => $message,
				'attach'  => $attach
				);
				
			}
			
			if ($bot){
				$result =  $this->model_kp_bitrixBot->sendMessage($message, $attach, 'chat21675');			
				//	print_r($result);
			}
		}
		
		public function getTotalCurrencies() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");
			
			return $query->row['total'];
		}
	}											
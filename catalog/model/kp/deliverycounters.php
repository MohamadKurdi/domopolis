<?
	class ModelKpDeliveryCounters extends Model {		
		private $length_class_id = 1;
		private $weight_class_id = 1;
		private $cdekStep = 5;
		private $defaultProducts = '{"2489512::::": {"key": "2489512::::","product_id": "2489512","from_stock": "","current_in_stock": "1","fully_in_stock": "1","amount_in_stock": "7","is_special_offer": "","is_special_offer_present": "","ao_id": "0","name": "Товар какой-то","model": "1282409964","manufacturer_id": "202","manufacturer": "WMF","shipping": "0","image": "","option": {},"download": {},"quantity": "1","minimum": "1","points_only_purchase": "0","subtract": "1","stock": "1","currency": "","price_old": "119.0000","saving": "-34","price": "78.936842105263","price_national": "5000","total": "78","total_national": "5000","reward": "0","points": "0","tax_class_id": "0","weight": "300","weight_class_id": "8","length": "1382.00000000","width": "976.00000000","height": "409.00000000","length_class_id": "1","profile_id": "0","profile_name": "","recurring": "","recurring_frequency": "0","recurring_price": "0","recurring_cycle": "0","recurring_duration": "0","recurring_trial": "0","recurring_trial_frequency": "0","recurring_trial_price": "0","recurring_trial_cycle": "0","recurring_trial_duration": "0","set": "0","childProductArray": {},"set_name": ""  }}';
		
		
		
		private function _normalizeDate($value = '') {
			return str_replace('-', '.', $value);
		}
		
		private function _clear($value) {
			$value = mb_convert_case($value, MB_CASE_LOWER, "UTF-8");
			return trim($value);
		}
		
		private function declination($number, $titles) {  
			$cases = array (2, 0, 1, 1, 1, 2);  
			return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];  
		}
		
		private function _getVolume($data, $length_class_id) {
			
			if ($length_class_id != $this->length_class_id) {
				array_walk($data, array($this, '_convertItem'), $length_class_id);
			}
			
			$p = 1;
			
			foreach ($data as $value) {
				$p *= (float)$value;
			}
			
			return $p / 1000000;
		}
		
		private function updateNovaPoshtaDeliveryTermsInDatabase($novaposhta_city_guid){
			$result = array();
			
			$this->load->library('hobotix/Shipping/NovaPoshta');
			$novaPoshta = new \hobotix\shipping\NovaPoshta($this->registry);
			
			$deliveryPeriod = $novaPoshta->getDeliveryDate($novaposhta_city_guid, date('y.m.d'));
			
			if ($deliveryPeriod){
				$query = $this->db->query("UPDATE  novaposhta_cities_ww  SET deliveryPeriod = '" . (int)$deliveryPeriod . "' WHERE Ref = '" . $this->db->escape($novaposhta_city_guid) . "'");
			}
			
			return $deliveryPeriod;
		}
		
		//Новая Почта
		public function getNovaPoshtaDeliveryTerms($novaposhta_city_guid){

			$deliveryPeriod = false;
			
			$query = $this->db->query("SELECT deliveryPeriod FROM novaposhta_cities_ww WHERE Ref = '" . $this->db->escape($novaposhta_city_guid) . "'");
			
			if ($query->num_rows && $query->row['deliveryPeriod']){
				$deliveryPeriod = $query->row['deliveryPeriod'];					
			}

			//Если в БД нету, либо 10% вероятность, то обновляем информацию в базе
			if (!$deliveryPeriod || mt_rand(0,10) == 5){
				$deliveryPeriod = $this->updateNovaPoshtaDeliveryTermsInDatabase($novaposhta_city_guid);
			}	
			
			return $deliveryPeriod;
			
		}
		
		
		//СДЭКё
		private function _convertItem(&$value, $key, $length_class_id) {
			$value = $this->length->convert($value, $length_class_id, $this->length_class_id);
		}
		
		private function getResult(CalculatePriceDeliveryCdek &$calc, $total = 0) {
			
			$result = FALSE;						
			
			if ($calc->calculate() === true) {
				
				$response = $calc->getResult();		
				
				if (!$this->config->get('cdek_cache_on_delivery') || !array_key_exists('cashOnDelivery', $response['result']) || ($this->config->get('cdek_cache_on_delivery') && (float)$response['result']['cashOnDelivery'] && $total >= (float)$response['result']['cashOnDelivery'])) {
					$result = $response['result'];
				}
				
				} else {
				
				$error = $calc->getError();
				
				if (isset($error['error']) && !empty($error) && $this->config->get('cdek_log')) {
					foreach($error['error'] as $error_info) {
						$this->log->debug('СДЭК: ' . $error_info['text']);
					}
				}
				
			}
			
			$this->log->debug($result);
			
			return $result;
		}
		
		private function updateCDEKDeliveryTermsInDatabase($cdek_city_code){
			$result = array();
			$deliveryPeriodMin = false;
			$deliveryPeriodMax = false;
			
			$tariffs = $this->guessCDEKPriceAjax($cdek_city_code, 4, true);
			
			foreach ($tariffs as $tariff){
				if (!empty($tariff['deliveryPeriodMin'])){
					$deliveryPeriodMin = $tariff['deliveryPeriodMin'];					
				}
				
				if (!empty($tariff['deliveryPeriodMax'])){
					$deliveryPeriodMax = $tariff['deliveryPeriodMax'];					
				}
				
				if ($deliveryPeriodMin && $deliveryPeriodMax){
					break;
				}
			}
			
			$query = $this->db->query("UPDATE cdek_cities SET deliveryPeriodMin = '" . (int)$deliveryPeriodMin . "', deliveryPeriodMax = '" . (int)$deliveryPeriodMax . "' WHERE code = '" . (int)$cdek_city_code . "'");
			
			return array(
			'deliveryPeriodMin' => $deliveryPeriodMin,
			'deliveryPeriodMax' => $deliveryPeriodMax
			);
		}
		
		//ФУНКЦИИ СДЭК
		public function getCDEKDeliveryTerms($cdek_city_code){
			$result = array();
			
			if ($cdek_city_code){
				$query = $this->db->query("SELECT deliveryPeriodMin, deliveryPeriodMax FROM cdek_cities WHERE code = '" . (int)$cdek_city_code . "'");
				
				if ($query->num_rows && $query->row['deliveryPeriodMin'] && $query->row['deliveryPeriodMax']){
					$result = array(
					'deliveryPeriodMin' => $query->row['deliveryPeriodMin'],
					'deliveryPeriodMax' => $query->row['deliveryPeriodMax'],
					);										
				}
				
				//Если в БД нету, либо 10% вероятность, то обновляем информацию в базе
				if (!$result || mt_rand(0,10) == 5){
					$result = $this->updateCDEKDeliveryTermsInDatabase($cdek_city_code);
				}								
			}
			
			return $result;
		}
		
		
		public function guessCDEKPriceAjax($cdek_city_code, $type, $return_full_data = false){
			$output = array('success' => false);
			
			$city_from_id = $this->config->get('cdek_city_from_id');
			
			//	$cdek_city_code = !empty($this->request->get['cdek_city_code'])?$this->request->get['cdek_city_code']:'';
			//	$type = !empty($this->request->get['type'])?$this->request->get['type']:'';
			
			if (!$cdek_city_code || !$type){
				
				} else {
				
				$status = true;
				
				require_once(DIR_APPLICATION . 'model/shipping/CalculatePriceDeliveryCdek.php');			
				$calc = new CalculatePriceDeliveryCdek();
				
				$calc->setSenderCityId($city_from_id);
				$calc->setReceiverCityId($cdek_city_code);
				
				$calc->setModeDeliveryId($type);
				
				$day = (is_numeric($this->config->get('cdek_append_day'))) ? trim($this->config->get('cdek_append_day')) : 0;
				$date = date('Y-m-d', strtotime('+' . (float)$day . ' day'));
				$calc->setDateExecute($date);
				
				if ($this->config->get('cdek_login') != '' && $this->config->get('cdek_password') != '') {
					$calc->setAuth($this->config->get('cdek_login'), $this->config->get('cdek_password'));
				}
				
				$geo_zones = array();
				$query = $this->db->query("SELECT DISTINCT geo_zone_id FROM zone_to_geo_zone WHERE country_id = '" . (int)$this->config->get('config_country_id') . "'");
				
				if ($query->num_rows) {
					foreach ($query->rows as $row) {
						$geo_zones[$row['geo_zone_id']] = $row['geo_zone_id'];
					}
					
				}
				
				$products = $this->cart->getProducts();
				
				//А тут прикольная заглушка, на случай если товаров нету)))
				if (!$products){
					$products = json_decode($this->defaultProducts, true);
				}
				
				
				$cdek_default_weight = $this->config->get('cdek_default_weight');
				
				$weight = 0;
				
				if (count($this->cart->getProducts()) >= $this->cdekStep){
					$cdek_default_weight['value'] = 8;					
				}
				
				if ($cdek_default_weight['use']) {
					
					$default_weight = (float)$this->weight->convert($cdek_default_weight['value'], $this->weight_class_id, $this->config->get('config_weight_class_id'));
					
					switch ($cdek_default_weight['work_mode']) {
						case 'order':
						$weight = $default_weight;
						break;
						case 'all':
						case 'optional':
						
						foreach ($products as $product) {
							
							if ($cdek_default_weight['work_mode'] == 'all') {
								$weight += $default_weight;
								} else {
								
								if ((float)$product['weight'] > 0) {
									$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
									} else {
									$weight += $default_weight;
								}
								
							}
							
						}
						
						break;
					}
					
					} else {
					$weight = $this->cart->getWeight();
				}
				
				if ($this->config->get('config_weight_class_id') != $this->weight_class_id) {
					$weight = $this->weight->convert($weight, $this->config->get('config_weight_class_id'), $this->weight_class_id);
				}
				
				$packing_min_weight = $this->weight->convert((float)$this->config->get('cdek_packing_min_weight'), $this->config->get('cdek_packing_weight_class_id'), $this->weight_class_id);
				$packing_value = $this->weight->convert((float)$this->config->get('cdek_packing_value'), $this->config->get('cdek_packing_weight_class_id'), $this->weight_class_id);
				
				if ($packing_value) {
					
					$packing_weight = 0;
					
					switch ($this->config->get('cdek_packing_mode')) {
						case 'fixed':
						$packing_weight = $packing_value;
						break;
						case 'all_percent':
						$packing_weight = ($weight / 100) * $packing_value;
						break;	
					}
					
					if ($packing_min_weight && $packing_min_weight > $packing_weight) {
						$packing_weight = $packing_min_weight;
					}
					
					if ($this->config->get('cdek_packing_prefix') == '+') {
						$weight += $packing_weight;
						} else {
						$weight -= (float)min($packing_weight, $weight);
					}
					
					} elseif ($packing_min_weight) {
					$weight += $packing_min_weight;
				}
				
				$cdek_default_size = $this->config->get('cdek_default_size');
				
				if (count($this->cart->getProducts()) >= $this->cdekStep){
					$cdek_default_size['size_a'] = 43;
					$cdek_default_size['size_b'] = 29;	
					$cdek_default_size['size_c'] = 35;	
				}
				
				if ($cdek_default_size['use']) {
					
					$default_volume = 0;
					
					switch ($cdek_default_size['type']) {
						case 'volume':
						$default_volume = (float)$cdek_default_size['volume'];
						break;
						case 'size':
						$default_volume = $this->_getVolume(array($cdek_default_size['size_a'], $cdek_default_size['size_b'], $cdek_default_size['size_c']), $this->length_class_id);
						break;
					}
					
					switch ($cdek_default_size['work_mode']) {
						case 'order':
						$volume = $default_volume;
						break;
						case 'all':
						case 'optional':
						
						foreach ($products as $product) {
							
							$product_volume = 0;
							
							if ($cdek_default_size['work_mode'] == 'all') {
								$product_volume = $default_volume;
								} else {
								
								$product_volume = $this->_getVolume(array($product['length'], $product['width'], $product['height']), $product['length_class_id']);
								
								if (!$product_volume) {
									$product_volume = $default_volume;
								}
								
							}
							
							$volume += $product['quantity'] * (float)$product_volume;
							
						}
						
						break;
					}
					
					} else {
					
					foreach ($products as $product) {
						
						$product_volume = $this->_getVolume(array($product['length'], $product['width'], $product['height']), $product['length_class_id']);
						
						if (!$product_volume) {
							$product_volume = 0;
						}
						
						$volume += $product['quantity'] * $product_volume;
					}
					
				}
				
				if ($this->config->get('cdek_log')) {
					$this->log->debug('СДЭК: объем ' . $volume);
				}
				
				if (!$volume) {
					
					if ($this->config->get('cdek_log')) {
						$this->log->debug('СДЭК: не удалось рассчитать объем, возможно не заданы размеры товара или не установлено значение по умолчанию в настройках модуля!');
					}
					
					$status = FALSE;
				}
				
				if ($status) {
					
					$calc->addGoodsItemByVolume($weight, $volume);
					
					if ($this->customer->isLogged()) {
						$customer_group_id = $this->customer->getCustomerGroupId();
						} else {
						$customer_group_id = $this->config->get('config_customer_group_id');
					}
					
					$cdek_tariff_list = $this->config->get('cdek_tariff_list');
					
					$results = $tariff_list = array();
					
					foreach ($this->config->get('cdek_custmer_tariff_list') as $key => $tariff_info) {
						
						if (empty($cdek_tariff_list[$tariff_info['tariff_id']])) continue;
						
						$tariff_title = !empty($tariff_info['title'][$this->config->get('config_language_id')]) ? $tariff_info['title'][$this->config->get('config_language_id')] : $cdek_tariff_list[$tariff_info['tariff_id']]['title'];
						
						if (!empty($tariff_info['customer_group_id']) && !in_array($customer_group_id, $tariff_info['customer_group_id'])) continue;
						
						$min_weight = (float)$tariff_info['min_weight'];
						$max_weight = (float)$tariff_info['max_weight'];
						
						if (($min_weight > 0 && $weight < $min_weight) || ($max_weight > 0 && $weight > $max_weight)) {
							
							if ($this->config->get('cdek_log')) {
								$this->log->write('СДЭК: Тариф «' . $tariff_title . '» превышены ограничения по весу!');
							}
							
							continue;
						}
						
						$min_total = (float)$tariff_info['min_total'];
						$max_total = (float)$tariff_info['max_total'];
						
						if (($min_total > 0 && $total < $min_total) || ($max_total > 0 && $total > $max_total)) {
							
							if ($this->config->get('cdek_log')) {
								$this->log->write('СДЭК: Тариф «' . $tariff_title . '» превышены ограничения по стоимости!');
							}
							
							continue;
						}
						
						if (!empty($tariff_info['geo_zone'])) {
							
							$intersect = array_intersect($tariff_info['geo_zone'], $geo_zones);
							
							if (!$intersect) {
								continue;
							}
							
							} else {
							$key = 'all';
						}
						
						$tariff_list[$tariff_info['tariff_id']][$key] = $tariff_info;
					}
					
					if (!$tariff_list) {
						
						if ($this->config->get('cdek_log')) {
							$this->log->debug('СДЭК: Не сформирован список тарифов для текущей географической зоны!');
						}
						
						$status = FALSE;
					}
					
					foreach ($tariff_list as $tariff_id => &$items) {
						
						if (count($items) > 1) {
							
							if (array_key_exists('all', $items)) unset($items['all']);
							
							$sort_order = array();
							
							foreach ($items as $key => $item) {
								$sort_order[$key] = $item['sort_order'];
							}
							
							array_multisort($sort_order, SORT_ASC, $items);
							
							$items = reset($items);
							
							} elseif (count($items) == 1)  {
							$items = reset($items);
							} else {
							continue;
						}
						
						if ($this->config->get('cdek_work_mode') == 'single') {
							$calc->addTariffPriority($tariff_id, $items['sort_order']);
						}
					}
					
					$total = $this->cart->getTotalInNationalCurrency();
					
					if ($this->config->get('cdek_work_mode') == 'single') {
						
						$exTariffList = array();
						$exTariffList['dver'] = array();
						$exTariffList['sklad'] = array();
						
						foreach ($tariff_list as $tariff_info) 
						{
							
							$calc->setTariffId($tariff_info['tariff_id']);
							
							if ($result = $this->getResult($calc, $total)) 
							{
								if($tariff_info['mode_id'] == 1 || $tariff_info['mode_id'] == 3)
								$exTariffList['dver'][] = $result;
								else
								$exTariffList['sklad'][] = $result;
							}
							
						}
						
						$set_dver = false;
						foreach ($exTariffList['dver'] as $mtfkey => $mtfvalue) 
						{
							if(!$set_dver || (float)$mtfvalue['priceByCurrency'] < (float)$set_dver['priceByCurrency'])
							$set_dver = $mtfvalue;
						}
						
						$set_sklad = false;
						foreach ($exTariffList['sklad'] as $mtfkey => $mtfvalue) 
						{
							if(!$set_sklad || (float)$mtfvalue['priceByCurrency'] < (float)$set_sklad['priceByCurrency'])
							$set_sklad = $mtfvalue;
						}
						
						if($set_dver)
						$results[] = $set_dver;
						if($set_sklad)
						$results[] = $set_sklad;				
						
						} else {
						
						foreach ($tariff_list as $tariff_info) {
							
							$calc->setTariffId($tariff_info['tariff_id']);
							
							if ($result = $this->getResult($calc, $total)) {
								$results[] = $result;
							}
							
						}
						
					}
				}
				
			}
			
			unset($tariff);
			$min_WD = PHP_INT_MAX;
			$min_WW = PHP_INT_MAX;
			
			$sub_total = $this->cart->getSubTotal();
			
			//$this->log->debug($tariff_list);
			
			$returnTariffs = array();
			
			if ($results && is_array($results)){
				foreach ($results as &$tariff){
					
					$discounts = $this->getCDEKDiscount($sub_total, $tariff['tariffId'], $geo_zones);
					
					foreach ($discounts as $discount_info) { 
						
						$markup = (float)$discount_info['value'];
						
						switch ($discount_info['mode']) {
							case 'percent':
							$markup = ($sub_total / 100) * $markup;
							break;
							case 'percent_shipping':
							$markup = ($shipping_price / 100) * $markup;
							break;
							case 'percent_cod':
							$markup = ($sub_total + $price / 100) * $markup;
							break;
						}
						
						if ($discount_info['prefix'] == '+') {
							$tariff['priceByCurrency'] += (float)$markup;
							} else {
							$tariff['priceByCurrency'] -= (float)min($markup, $price);
						}
						
					}
					
					$tariff['priceByCurrency'] = round($tariff['priceByCurrency']);
					
					
					$tariff['cdek_tariff'] = $this->getCDEKTariff($tariff['tariffId']);
					
					if ($tariff['cdek_tariff']){
						
						if (!empty($tariff['priceByCurrency']) && $tariff['cdek_tariff']['mode_id'] == 3 && $tariff['priceByCurrency'] < $min_WD){
							$min_WD = $tariff['priceByCurrency'];
						}
						
						if (!empty($tariff['priceByCurrency']) && $tariff['cdek_tariff']['mode_id'] == 4  && $tariff['priceByCurrency'] < $min_WW){
							$min_WW = $tariff['priceByCurrency'];
						}
						
					}
					
					$returnTariffs[] = $tariff;
					
					//$this->log->debug($tariff);
				}
			}
			
			if ($return_full_data){
				return $returnTariffs;
			}
			
			
			if ($min_WD > 0 && $min_WD != PHP_INT_MAX){
				$output['success'] = true;				
				
				if ($this->session->data['currency'] != 'RUB'){					
					$min_WD = $this->currency->format($this->currency->real_convert((float)$min_WD, 'RUB', $this->session->data['currency']), $this->session->data['currency'], 1);
					} else {
					$min_WD = $this->currency->format($min_WD, 'RUB', 1);
				}
				
				$output['min_WD'] = $min_WD;
			}
			
			if ($min_WW > 0 && $min_WW != PHP_INT_MAX){
				$output['success'] = true;				
				
				if ($this->session->data['currency'] != 'RUB'){					
					$min_WW = $this->currency->format($this->currency->real_convert((float)$min_WW, 'RUB', $this->session->data['currency']), $this->session->data['currency'], 1);
					} else {
					$min_WW = $this->currency->format((float)$min_WW, 'RUB', 1);
				}
				
				$output['min_WW'] = $min_WW;
			}
			
			return json_encode($output);
			
		}
		
		private function getCDEKDiscount($total, $tariff_Id = 0, $geo_zones = array()) {
			
			$discounts = array();
			
			$cdek_discounts = $this->config->get('cdek_discounts');
			
			if (!empty($cdek_discounts)) {
				
				if ($this->customer->isLogged()) {
					$customer_group_id = $this->customer->getCustomerGroupId();
					} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}
				
				foreach ($cdek_discounts as $key => $discount_info) {									
					
					$item_status = TRUE;
					
					if ((!empty($discount_info['customer_group_id']) && !in_array($customer_group_id, $discount_info['customer_group_id']))) {
						$item_status = FALSE;
					}
					
					if (!empty($discount_info['geo_zone'])) {
						
						$intersect = array_intersect($discount_info['geo_zone'], $geo_zones);
						
						if (!$intersect) {
							$item_status = FALSE;
						}
						
					}
					
					if (!isset($discount_info['tariff_id'])) $discount_info['tariff_id'] = array();
					
					
					//	$this->log->debug($discount_info['tariff_id']);
					
					if ($item_status && (float)$discount_info['total'] <= $total && (!$discount_info['tariff_id'] || in_array($tariff_Id, $discount_info['tariff_id']))) {
						$discounts[$discount_info['prefix'] . '_' . $discount_info['mode']] = $discount_info;
					}
					
					
					
				}
				
			}
			
			return $discounts;
		}
		
		/**
			* Устанавливаем режим доставки (дверь-дверь=1, дверь-склад=2, склад-дверь=3, склад-склад=4)
		*/
		private function getCDEKTariff($tariff_id){
			$cdek_tariff_list = $this->config->get('cdek_tariff_list');
			
			if (!empty($cdek_tariff_list[$tariff_id])){
				return $cdek_tariff_list[$tariff_id];
			}
			
			return false;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}				
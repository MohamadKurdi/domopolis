<?php
	class ModelShippingDostavkaPlus extends Model {
		private $type = 'shipping';
		private $name = 'dostavkaplus';
		
		public function getQuote($address, $code = '') {
			$this->language->load($this->type . '/' . $this->name);
			
			$method_data = array();
			
			if ($this->config->get($this->name.'_status') == true) {
				$quote_data = array();
				
				$arr_lock = array();
				$arr_unlock = array();												
				
				if (is_array($this->config->get($this->name.'_module')) and count($this->config->get($this->name.'_module')) > 0) {
					foreach($this->config->get($this->name.'_module') as $key => $module) {
						$error = '';
						
						if ($module['status'] == 1) {
							
							if (isset($module['store']) and is_array($module['store']) and in_array((int)$this->config->get('config_store_id'), $module['store'])) {
								
								$status = true;					
								$price_changed = false;
								$description_good = '';
								$description_alert = '';
								
								
								$total = $this->cart->getSubTotal();
																
								if ($module['curr']){
									$total_national = $this->currency->convert($this->cart->getSubTotal(), $this->config->get('config_currency'), $module['curr']);
									} else {
									$total_national = $total;								
								}																												
								
								$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $module['weight_class_id']);
								
								if (isset($module['geo_zone']) and is_array($module['geo_zone']) and count($module['geo_zone']) > 0 ) {
									
									$query = $this->db->query("SELECT * FROM zone_to_geo_zone
                                    WHERE geo_zone_id IN (" . implode(',', $module['geo_zone']) . ") AND country_id = " . (int)$address['country_id'] . "
                                    AND (zone_id = " . (int)$address['zone_id'] . " OR zone_id = 0)");
									if ($query->num_rows) {
										$status = true;
									}
									else {
										$status = false;
										
										if ($this->config->get($this->name.'_show_error_text') == 1) {
											$error = 'Не доступно в выбранном регионе.';
										}
									}
								}
								
								if ($module['applytoallcountries']){
									if (!isset($address['country_id']) || !$address['country_id'] || $address['country_id'] != $this->config->get('config_country_id') || !isset($address['city']) || $address['city'] == ''){
										$status = true;								
										} else {
										$status = false;
									}							
								}								
								
								if (!isset($module['min_weight'])) {
									$module['min_weight'] = 0;
								}
								
								if (!isset($module['max_weight'])) {
									$module['max_weight'] = 0;
								}
								
								$module['min_weight'] = (int)$module['min_weight'];
								$module['max_weight'] = (int)$module['max_weight'];
								
								if (($status == true or ($status == false and $error != '')) and
                                (
								($module['min_weight'] > 0 and $module['max_weight'] > 0 and $weight >= $module['min_weight'] and $weight < $module['max_weight']) or
								($module['min_weight'] > 0 and $module['max_weight'] == 0 and $weight >= $module['min_weight']) or
								($module['max_weight'] > 0 and $module['min_weight'] == 0 and $weight < $module['max_weight']) or
								($module['max_weight'] == 0 and $module['min_weight'] == 0)
                                )
								) {
									if ($status == true) {
										$status = true;
									}
									else {
										$status = false;
									}
								}
								else {
									$status = false;
								}
								
								
								if (!isset($module['min_total'])) {
									$module['min_total'] = 0;
								}
								
								if (!isset($module['max_total'])) {
									$module['max_total'] = 0;
								}
								
								$module['min_total'] = (int)$module['min_total'];
								$module['max_total'] = (int)$module['max_total'];
								
								if ($status == true and
                                (																
								($module['min_total'] > 0 and $module['max_total'] > 0 and $total_national >= $module['min_total'] and $total_national < $module['max_total']) or
								($module['min_total'] > 0 and $module['max_total'] == 0 and $total_national >= $module['min_total']) or
								($module['max_total'] > 0 and $module['min_total'] == 0 and $total_national < $module['max_total']) or
								($module['max_total'] == 0 and $module['min_total'] == 0)
                                )
								) {
									$description_alert = '';
								}
								else {
									$arr_lock[] = $key;
									if ($module['curr']){
										$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total'], $module['curr'], '1'));
										} else {
										$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total']));
									}
								}															
								
								$price_changed_distance = false;
								
								//НП СКЛАД
								if ($status == true && $key == 3 && !empty($address['novaposhta_city_guid'])){
									$npWCQuery = $this->db->query("SELECT WarehouseCount FROM novaposhta_cities_ww WHERE Ref = '" . $this->db->escape($address['novaposhta_city_guid']) . "' LIMIT 1");
									
									if (!$npWCQuery->num_rows || ($npWCQuery->num_rows && $npWCQuery->row['WarehouseCount'] == 0)){
										//$status = false;
									}
								}
								
								//НП Адресная
								if ($status == true && $key == 13 && !empty($address['novaposhta_city_guid'])){
									$npWCQuery = $this->db->query("SELECT COUNT(*) as StreetCount FROM novaposhta_streets WHERE CityRef = '" . $this->db->escape($address['novaposhta_city_guid']) . "' LIMIT 1");
									
									if (!$npWCQuery->num_rows || ($npWCQuery->num_rows && $npWCQuery->row['StreetCount'] == 0)){
										$status = false;
									}
								}
								
								//Отключаем Укрпочту, если доступна НП
								if ($status == true && $key == 14 && !empty($address['novaposhta_city_guid'])){
									$npWCQuery = $this->db->query("SELECT WarehouseCount FROM novaposhta_cities_ww WHERE Ref = '" . $this->db->escape($address['novaposhta_city_guid']) . "' LIMIT 1");

									if ($npWCQuery->num_rows && $npWCQuery->row['WarehouseCount'] > 0){
										$status = false;
									}
								}
								
								//Джастин
								if ($status == true && $key == 15 && !empty($address['city'])){
									$justinCity = trim(mb_strtolower($address['city']));
									
									$npWCQuery = $this->db->query("SELECT WarehouseCount FROM justin_cities WHERE LOWER(Descr) LIKE '" . $this->db->escape($justinCity) . "' OR  LOWER(DescrRU) LIKE '" . $this->db->escape($justinCity) . "' LIMIT 1");
									
									if (!$npWCQuery->num_rows || ($npWCQuery->num_rows && $npWCQuery->row['WarehouseCount'] == 0)){
										$status = false;
									}
								}
								
								//СДЭК для всех стран, если нету ПВЗ, то отключаем СДЭК ПВЗ
								if ($status == true && $key == 6 && !empty($address['cdek_city_guid'])){
									$npWCQuery = $this->db->query("SELECT WarehouseCount FROM cdek_cities WHERE code = '" . (int)$address['cdek_city_guid'] . "' LIMIT 1");
									
									if (!$npWCQuery->num_rows || ($npWCQuery->num_rows && $npWCQuery->row['WarehouseCount'] == 0)){
										$status = false;
									}
								}
								
								//Отключить  ЕМС по Московской области
								if ($status == true && $key == 5 && !empty($address['cdek_city_guid'])){
									
									$npWCQuery = $this->db->query("SELECT code FROM cdek_cities WHERE code = '" . (int)$address['cdek_city_guid'] . "' AND region_code IN (9,81) LIMIT 1");
																		
									if ($npWCQuery->num_rows){
										$status = false;
									}
									
									$npWCQuery = $this->db->query("SELECT WarehouseCount FROM cdek_cities WHERE code = '" . (int)$address['cdek_city_guid'] . "'  AND region_code IN (9,81) LIMIT 1");
									
									if ($npWCQuery->num_rows && $npWCQuery->row['WarehouseCount'] > 0){
										$status = false;
									}
									
								}
								
								//Самовывоз или доставка курьером по Москве
								if ($status == true && ($key == 9 || $key == 1) && !empty($address['cdek_city_guid'])){
									
									$maxDistance = 0;																		
									$rates = explode(',', $module['rate']);		
									
									if (!$rates){
										$maxDistance = PHP_INT_MAX;
									}
									
									if (count($rates) > 0) {
										foreach ($rates as $rate) {
											$data = explode(':', $rate);
											$data[0] = trim($data[0]);
											
											if ((int)$data[0] > $maxDistance){
												$maxDistance = $data[0];
											}
										}
									}
									
									$npWCQuery = $this->db->query("SELECT dadata_BELTWAY_HIT, dadata_BELTWAY_DISTANCE FROM cdek_cities WHERE code = '" . (int)$address['cdek_city_guid'] . "' AND region_code IN (9,81) LIMIT 1");
									
									
									if ($npWCQuery->num_rows){
										$status = true;
										
										if ($npWCQuery->row['dadata_BELTWAY_HIT'] == 'NOT_FOUND'){
											$status = false;
										}
										
										if ($npWCQuery->row['dadata_BELTWAY_HIT'] == 'OUT_MKAD' && (int)$npWCQuery->row['dadata_BELTWAY_DISTANCE'] > $maxDistance){
											$status = false;
										}
										
										
										//Цена при выборе города
										if ($npWCQuery->row['dadata_BELTWAY_HIT'] == 'OUT_MKAD' || $npWCQuery->row['dadata_BELTWAY_HIT'] == 'IN_MKAD'){
											
											
											if (empty($address['courier_city_dadata_beltway_hit']) || $address['courier_city_dadata_beltway_hit'] == ''){
												$address['courier_city_dadata_beltway_hit'] = $npWCQuery->row['dadata_BELTWAY_HIT'];
											}									
											
											if (empty($address['courier_city_dadata_beltway_distance']) || $address['courier_city_dadata_beltway_distance'] == '0'){
												$address['courier_city_dadata_beltway_distance'] = $npWCQuery->row['dadata_BELTWAY_DISTANCE'];
											}
										}
										
										
										
										} else {
										
										$status = false;
										
									}
									
								}
								
								
								//Доставка по Минску отключается НЕ для Минска
								if ($status == true && ($key == 7) && !empty($address['cdek_city_guid'])){									
									if ($address['cdek_city_guid'] == 9220) {
										$status == true;
									} else {
										$status = false;
									}
								}

								//Доставка по Алматы отключается НЕ для Алматы
								if ($status == true && ($key == 8) && !empty($address['cdek_city_guid'])){									
									if ($address['cdek_city_guid'] == 4756) {
										$status == true;
									} else {
										$status = false;
									}
								}
								
								if ($status == true) {
									$price = $module['price'];								
									
									if (!empty($address['courier_city_dadata_beltway_hit']) && $address['courier_city_dadata_beltway_hit'] == 'OUT_MKAD'){
										if (!empty($address['courier_city_dadata_beltway_distance']) && $module['rate'] != '') {

											$rates = explode(',', $module['rate']);
											
											if (count($rates) > 0) {
												foreach ($rates as $rate) {
													$data = explode(':', $rate);
													
													$data[0] = trim($data[0]);
													
													if ($data[0] < (int)$address['courier_city_dadata_beltway_distance']) {
														if (isset($data[1])) {
															if ($data[1] == 'FAIL'){
																
																$status == false;
																
																} else {
																
																$price = trim($data[1]);
																$price_changed = true;
																$price_changed_distance = true;
																
															}
														}
														
														break;
													}
												}
											}
										}	
									}
									
									if ($module['sumrate'] != '' && !$price_changed_distance) {
										$rates = explode(',', $module['sumrate']);
										
										if (count($rates) > 0) {
											foreach ($rates as $rate) {
												$data = explode(':', $rate);
												
												$data[0] = trim($data[0]);
												
												if ($module['cod'] == true){
													if (isset($this->session->data['payment_method']['code']) and $this->session->data['payment_method']['code'] == 'cod') {
														//если в модуле выставлена настройка и все совпадает, то у нас все бесплатно
														if ($data[0] <= $total_national) {												
															if (isset($data[1])) {
																$price = trim($data[1]);
																$price_changed = true;
															}                                            
														}												
													}
													} else {
													//московская обработка
													if ($data[0] <= $total_national) {												
														if (isset($data[1])) {
															$price = trim($data[1]);
															$price_changed = true;															
														}                                            
														} else {
														if (isset($data[1]) && $data[1] == 0) {
															$free_from = (int)$data[0];
														}													
													}
													
												}
												
											}
										}
									}	
									
									if ($module['city_rate'] != '' and isset($address['city']) and $address['city'] != '') {
										$rates = explode(',', $module['city_rate']);
										
										if (count($rates) > 0) {
											foreach ($rates as $rate) {
												$data = trim($rate);
												
												if (mb_strtolower($data, 'UTF-8') == mb_strtolower(trim($address['city']), 'UTF-8')) {
													$arr_lock[] = $key;
												}
											}
										}
									}
									
									
									if ($module['city_rate2'] != '' and isset($address['city']) and $address['city'] != '') {
										$rates = explode(';', $module['city_rate2']);
										
										if (count($rates) == 1){
											$rates = explode(',', $module['city_rate2']);
										}
										
										if (count($rates) > 0) {
											foreach ($rates as $rate) {
												$data = trim($rate);
												
												if (mb_stripos($address['city'], $data) !== false) {
													$arr_unlock[] = $key;
												}
											}
										}
									}
									
									
									if (isset($module['cost']) and $module['cost'] != '') {
										$price = $price + (int)$module['cost'];
										$price_changed = true;
									}
									
									if (!isset($module['image'])) {
										$module['image'] = '';
									}
									
									$title = html_entity_decode($module['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
									
									if (isset($module['info'][$this->config->get('config_language_id')])) {
										$description = html_entity_decode($module['info'][$this->config->get('config_language_id')]);
									}
									else {
										$description = '';
									}
									
									$additional_description = '';
									if ($price_changed_distance && !empty($address['custom_beltway_distance'])){
										$additional_description .= '<b><i class="fas fa-info-circle"></i> расстояние от МКАД до указанного адреса составляет ' . (int)$address['custom_beltway_distance'] . ' км.</b>';
									}
									
									if ($module['curr']){
										$price_national = $price;
										$price = $this->currency->convert($price, $module['curr'], $this->config->get('config_currency'));									
										} else {
										$price_national = $price;
									}
									
									
									//Есть текстовое значение	
									if ($module['txtprice'][$this->config->get('config_language_id')]){
										//Если цена была изменена в течении модуля - у нас возможно два варианта - она 0 и больше нуля
										if ($price_changed){
											if ($price == 0) {
												$text = $this->language->get('text_free');
												} else {
												if ($module['curr']){
													$text = $this->currency->format($price_national, $module['curr'], 1);
													} else {
													$text = $this->currency->format($price);
												}
												
											}								
											} else {
											//Цена не изменялась - значит она считается по тарифу - неважно, какая она 
											$text = $module['txtprice'][$this->config->get('config_language_id')];	
											
										}
										
										
										} else {
										//нет текстового значения - выводим тупо цену		
										if ($price == 0) {
											$text = $this->language->get('text_free');
											} else {
											if ($module['curr']){
												$text = $this->currency->format($price_national, $module['curr'], 1);
												} else {
												$text = $this->currency->format($price);
											}
											
											if ($module['price'] > 0){
												if (isset($free_from)){
													if ($module['curr']){
														$description_good = sprintf($this->language->get('text_free_from'), $this->currency->format($free_from, $module['curr'], 1));
														} else {
														$description_good = sprintf($this->language->get('text_free_from'), $this->currency->format($free_from));
													}
												}
											}																						
										}
									}
									
									//NP ALERT								
									if ($key == '3'){										
										//$description_alert = 'Адресная доставка Новой Почтой временно осуществляется только по предоплате.';
									}
									
									if (($code == '' or $code == 'sh'.$key) and !in_array($key, $arr_lock)
                                    and (empty($module['city_rate2']) or (!empty($module['city_rate2']) and in_array($key, $arr_unlock)))
									) {
										
										$quote_data['sh'.$key] = array(
                                        'code'            				=> $this->name.'.sh'.$key,
                                        'title'           				=> $title,
                                        'image'           				=> $module['image'],
                                        'cost'            				=> $price,
										'cost_national'   				=> $price_national,
                                        'description'     				=> $description,
										'additional_description'     	=> $additional_description,
                                        'tax_class_id'    				=> '',
                                        'sort_order'      				=> $module['sort_order'],
                                        'text'            	=> $text,
                                        'error'           	=> $error,
										'status'          	=> true,
										'description_alert' => $description_alert,
										'description_good' => $price_changed_distance?false:$description_good,
										'checkactive'	   => $module['checkactive'],
										);
										} else {
										/*	$quote_data['sh'.$key] = array(
											'code'            => $this->name.'.sh'.$key,
											'title'           => $title,
											'image'           => $module['image'],
											'cost'            => $price,
											'cost_national'   => $price_national,
											'additional_description'     => $additional_description,
											'description'     => $description,
											'tax_class_id'    => '',
											'sort_order'      => $module['sort_order'],
											'text'            => $text,
											'error'           => $error,
											'status'          => false,
											'description_alert' => $description_alert,
											'description_good' => $price_changed_distance?false:$description_good,
											'checkactive'	  => $module['checkactive'],
											);
										*/
										
									}
								}
							}
						}
					}
				}
				
				
				if (isset($quote_data) and count($quote_data) > 0) {
					$sort_by = array();
					foreach ($quote_data as $key => $value) $sort_by[$key] = $value['sort_order'];
					array_multisort($sort_by, SORT_ASC, $quote_data);
					
					foreach ($quote_data as $q_data) {
						$error = $q_data['error'];
						break;
					}
				}
				
				if (!empty($this->config->get($this->name.'_name'))){
					$title = html_entity_decode($this->config->get($this->name.'_name'), ENT_QUOTES, 'UTF-8');
				} else {
					$title = '';
				}
				
				if ((isset($quote_data) and count($quote_data) > 0) or $error != '') {
					$method_data = array(
					'code'       => $this->name,
					'title'      => $title,
					'quote'      => $quote_data,
					'error'      => $error,
					'sort_order' => $error ? ($this->config->get($this->name.'_sort_order') + 100) : $this->config->get($this->name.'_sort_order'),
					);
				}
			}
			
			return $method_data;
		}
	}													
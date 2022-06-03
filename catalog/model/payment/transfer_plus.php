<?php 
	class ModelPaymentTransferPlus extends Model {
		private $type = 'payment';
		private $name = 'transfer_plus';
		
		public function getMethod($address, $total) {
			$this->language->load($this->type . '/' . $this->name);
			
			$method_data = array();
			
			if ($total === false){
				if ($this->config->get($this->name.'_status') == true) {
					
					if (is_array($this->config->get($this->name.'_module')) and count($this->config->get($this->name.'_module')) > 0) {
						foreach($this->config->get($this->name.'_module') as $key => $module) {
							
							if ($module['status'] == 1) {
								
								if (isset($module['store']) and is_array($module['store']) and in_array((int)$this->config->get('config_store_id'), $module['store'])) {
									
									if (isset($module['geo_zone']) and is_array($module['geo_zone']) and count($module['geo_zone']) > 0 ) {
										$query = $this->db->query("SELECT * FROM zone_to_geo_zone
										WHERE geo_zone_id IN (" . implode(',', $module['geo_zone']) . ") AND country_id = " . (int)$address['country_id'] . "
										AND (zone_id = " . (int)$address['zone_id'] . " OR zone_id = 0)");
										if ($query->num_rows) {
											$status = true;
											$country_status = true;
											} else {
											$status = false;
											$country_status = false;
										}
									}																					
									
									if ($module['other_rate'] != '') {
										$rates = explode(',', $module['other_rate']);
										
										if (count($rates) > 0) {
											foreach ($rates as $rate) {
												$data = explode(':', $rate);
												
												if (isset($data[1])) {															
													$percent = trim($data[1]);
													break;
												}
											}
										}
									}							
									
									
									if ($module['isprepay']) {
										$title = $module['title'][$this->config->get('config_language_id')] . ' ('.$percent.'%)';									
										} else {
										$title = $module['title'][$this->config->get('config_language_id')];										
									}
									
									if ($country_status) {																	
										
										$quote_data[$key] = array(
										'code'            => $this->name.'.'.$key,
										'title'           => $title,
										'image'           => $module['image'],
										//	'tip'			  => $tip,
										'sort_order'      => $module['sort_order'],
										//	'status'		  => $status,
										'is_prepay'       => $module['isprepay'],
										'description'     => '',
										'checkactive'	  => $module['checkactive'],
										'prepay_sum'      => isset($prepay_sum_num)?$prepay_sum_num:false,
										);
									}
									
								}
							}
						}
					}
					
					if (isset($quote_data) and count($quote_data) > 0) {
						$sort_by = array();
						foreach ($quote_data as $key => $value) $sort_by[$key] = $value['sort_order'];
						array_multisort($sort_by, SORT_ASC, $quote_data);
						
						$method_data = array(
						'code'       => $this->name,
						'quote'      => $quote_data,
						'sort_order' => $this->config->get($this->name.'_sort_order'),
						);
					}
				}
				
				
				
				} else {
				
				if ($this->config->get($this->name.'_status') == true) {
					$quote_data = array();
					
					if (is_array($this->config->get($this->name.'_module')) and count($this->config->get($this->name.'_module')) > 0) {
						foreach($this->config->get($this->name.'_module') as $key => $module) {
							
							if ($module['status'] == 1) {
								
								if (isset($module['store']) and is_array($module['store']) and in_array((int)$this->config->get('config_store_id'), $module['store'])) {
									
									$status = true;
									$description_alert = '';
									
									if (isset($module['geo_zone']) and is_array($module['geo_zone']) and count($module['geo_zone']) > 0 ) {
										$query = $this->db->query("SELECT * FROM zone_to_geo_zone
										WHERE geo_zone_id IN (" . implode(',', $module['geo_zone']) . ") AND country_id = " . (int)$address['country_id'] . "
										AND (zone_id = " . (int)$address['zone_id'] . " OR zone_id = 0)");
										if ($query->num_rows) {
											$status = true;
											$country_status = true;
										}
										else {
											$status = false;
											$country_status = false;
										}
									}							
									
									if ($module['curr']){
										if ($this->cart->getSubTotal() > 0){
											$total_module = $this->currency->convert($this->cart->getSubTotal(), $this->config->get('config_currency'), $module['curr']);
											} else {
											$total_module = $this->currency->convert($total, $this->config->get('config_currency'), $module['curr']);
										}								
										} else {
										if ($this->cart->getSubTotal() > 0){
											$total_module = $this->cart->getSubTotal();	
											} else {
											$total_module = $total;
										}
									}
									
									$city_status = false;
									//проверка города для ОДИНОЧНОГО ЗАКАЗА. Если города нет - то фолс	
									if ($module['main_city'] != '' and isset($address['city']) and $address['city'] != '') {																								
										$rates = explode(',', $module['main_city']);
										if (count($rates) > 0) {
											
											if (!$module['isprepay']) {											
												$status = false;
											}
											
											foreach ($rates as $rate) {
												$data = trim($rate);
												if (mb_strtolower($data, 'UTF-8') == mb_strtolower(trim($address['city']), 'UTF-8')) {
													
													if (!$module['isprepay']) {											
														$status = true;
														} else {
														$city_status = true;											
													}
													
													break;
													
												}
											}
											
											if($rates[0] == '!'){
												$status = !$status;
												$city_status = !$city_status;											
											}
										}
									}
									
									//считаем процент предоплаты. если процент = 0, или 100, то мы не показываем частичную предоплату
									if ($module['isprepay']){								
										//Этот модуль - предоплата
										if ($city_status){
											if ($module['main_city_rate']=='donotuse' && false) { $status = false; $percent = 0; } 
											else {	
												if ($module['main_city_rate'] != '') {
													$rates = explode(',', $module['main_city_rate']);
													
													if (count($rates) > 0) {
														foreach ($rates as $rate) {
															$data = explode(':', $rate);
															$data[0] = trim($data[0]);
															
															
															if ((float)$data[0] >= $total_module) {
																if (isset($data[1])) {															
																	$percent = trim($data[1]);
																	break;
																}
															}
														}
													}
												}
											}
											} else {
											if ($module['other_rate'] != '') {
												$rates = explode(',', $module['other_rate']);
												
												if (count($rates) > 0) {
													foreach ($rates as $rate) {
														$data = explode(':', $rate);
														$data[0] = trim($data[0]);
														
														
														if ((float)$data[0] >= $total_module) {
															if (isset($data[1])) {															
																$percent = trim($data[1]);
																break;
															}
														}
													}
												}
											}																					
										}							
									}
									
									if (!isset($percent)) { $percent = 0; }
									if ($module['isprepay'] && ($percent == 0 or $percent == 100)){
										$status = false;
										$country_status = false;
									}
									
									if ($module['ship_code']){
										$ship_codes = explode(',',$module['ship_code']);
										
										
										if (isset($this->session->data['shipping_method']['code']) and !in_array($this->session->data['shipping_method']['code'], $ship_codes)){							
											$status = false;
											$country_status = false;
										}
										
									}
									
									$module['min_total'] = (int)$module['min_total'];
									$module['max_total'] = (int)$module['max_total'];
									
									$this_is_first_order = true;
									if ($this->customer->isLogged()){
										$order_count_query = $this->db->non_cached_query("SELECT COUNT(DISTINCT order_id) as order_count FROM `order` WHERE customer_id = '" .(int)$this->customer->getId(). "' AND order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'");
										
										if (isset($order_count_query->row['order_count'])){
											$this_is_first_order = ($order_count_query->row['order_count'] > 0);
											} else {
											$this_is_first_order = false;
										}
									}
									
									if ($status == true){
										
										if ($module['max_total'] == 0 and $module['min_total'] == 0){
											//Значения не заданы, все ок, продолжаем-с
											$status = true;									
											} elseif ($module['min_total'] > 0 and $module['max_total'] > 0) {
											//оба значения больше нуля
											if ($module['min_sum_for_first_order']){
												//ТОЛЬКО ДЛЯ ПЕРВОГО - ВКЛЮЧЕНО
												if ($this_is_first_order){
													//это первый заказ!
													$status = (($total_module < $module['max_total']) && ($total_module >= $module['min_total']));
													
													if (!$status) {
														if ($module['curr']){
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total'], $module['curr'], '1'));
															} else {
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total']));
														}
													}
													
													} else {
													$status = ($total_module < $module['max_total']);
													
													if (!$status) {
														if ($module['curr']){
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total'], $module['curr'], '1'));
															} else {
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total']));
														}
													}
												}
												} else {
												//ТОЛЬКО ДЛЯ ПЕРВОГО - ВЫКЛЮЧЕНО! Обычное сравнение!
												$status = (($total_module < $module['max_total']) && ($total_module >= $module['min_total']));
												
												if (!$status) {
														if ($module['curr']){
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total'], $module['curr'], '1'));
															} else {
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total']));
														}
													}
											}																
											} elseif ($module['min_total'] > 0 and $module['max_total'] == 0) {	
											//Задана только минимальная сумма. Должно работать только если включена галочка "ТОЛЬКО ДЛЯ ПЕРВОГО ЗАКАЗА"
											if ($module['min_sum_for_first_order']){
												//ТОЛЬКО ДЛЯ ПЕРВОГО - ВКЛЮЧЕНО
												if ($this_is_first_order){
													//обработка только в случае того, что это первый заказ. Иначе не обрабатываем ничего.
													$status = ($total_module >= $module['min_total']);
													
													if (!$status) {
														if ($module['curr']){
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total'], $module['curr'], '1'));
															} else {
															$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total']));
														}
													}
													
													
													} else {
													$status = true;
												}									
												} else {
												//ТОЛЬКО ДЛЯ ПЕРВОГО - ВЫКЛЮЧЕНО! Обычное сравнение!
												$status = ($total_module >= $module['min_total']);	
												
												if (!$status) {
													if ($module['curr']){
														$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total'], $module['curr'], '1'));
														} else {
														$description_alert = sprintf($this->language->get('text_min_price'), $this->currency->format($module['min_total']));
													}
												}
												
											}
											
											} elseif ($module['max_total'] > 0 and $module['min_total'] == 0) {
											//Максимальное значение ЕСТЬ, минимального нет. Статус = СУММА > МАКС ЗНАЧЕНИЯ
											$status = ($total_module < $module['max_total']);								
										}
										
									}
									
									if ($country_status == true)
									{
										if ($module['isprepay']) {
											if ($this->cart->getSubTotal()) {
												$prepay_sum = (int)($this->cart->getSubTotal() / 100 * $percent);
												} else {
												$prepay_sum = (int)($total / 100 * $percent);
											}
											
											if ($status){
												$this->session->data['prepay_perc'] = $percent;																		
												$this->session->data['prepay_sum'] = $prepay_sum;
												$this->session->data['customer_prepay'] = 1;										
												} else {
												$this->session->data['customer_prepay'] = 0;										
											}
											
											$prepay_sum_num = $prepay_sum;																	
											$prepay_sum = $this->currency->format($prepay_sum);									
											} else {
											$this->session->data['customer_prepay'] = 0;
										}
										
										
										if ($module['isprepay']) {
											$tip = sprintf($this->language->get('text_prepay'), $module['min_total'], $percent, $prepay_sum, $this->url->link('information/information', 'information_id=' .  30));
											} elseif ($module['tip'][$this->config->get('config_language_id')]){
											if (mb_strlen($this->language->get('text_delivery')) > 0) {
												$tip = $module['tip'][$this->config->get('config_language_id')] . sprintf($this->language->get('text_delivery'), $this->url->link('information/information', 'information_id=' .  30));		
												} else {
												$tip = $module['tip'][$this->config->get('config_language_id')];
											}
											} else {
											$tip = '';									
										}
										
										if ($module['isprepay']) {
											$title = $module['title'][$this->config->get('config_language_id')] . ' ('.$percent.'%)';									
											} else {
											$title = $module['title'][$this->config->get('config_language_id')];										
										}
										
										
										$quote_data[$key] = array(
										'code'            => $this->name.'.'.$key,
										'title'           => $title,
										'image'           => $module['image'],
										'tip'			  => $tip,
										'sort_order'      => $module['sort_order'],
										'status'		  => $status,
										'is_prepay'       => $module['isprepay'],
										'description'     => $module['tip'][$this->config->get('config_language_id')],
										'description_alert' => $description_alert,
										'checkactive'	  => $module['checkactive'],
										'prepay_sum'      => isset($prepay_sum_num)?$prepay_sum_num:false,
										);
									}
								}
							}
						}
					}
					
					
					if (isset($quote_data) and count($quote_data) > 0) {
						$sort_by = array();
						foreach ($quote_data as $key => $value) $sort_by[$key] = $value['sort_order'];
						array_multisort($sort_by, SORT_ASC, $quote_data);
						
						$method_data = array(
						'code'       => $this->name,
						'quote'      => $quote_data,
						'sort_order' => $this->config->get($this->name.'_sort_order'),
						);
					}
				}
			}
			
			return $method_data;
		}
	}
?>
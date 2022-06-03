<?php
	class Cart {
		private $config;
		private $db;
		private $data = array();
		private $data_recurring = array();
		
		public function __construct($registry) {
			if ($registry->get('customer')) {
				$registry->get('customer')->refrefh($registry);
			}
			$this->config = $registry->get('config');
			$this->customer = $registry->get('customer');
			$this->language = $registry->get('language');
			$this->session = $registry->get('session');
			$this->db = $registry->get('db');
			$this->log = $registry->get('log');
			$this->tax = $registry->get('tax');
			$this->weight = $registry->get('weight');
			$this->currency = $registry->get('currency');
			
			$this->registry = $registry;
			$this->load = $this->registry->get('load');
			
			if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
				$this->session->data['cart'] = array();
			}
		}
		
		public function getProducts() {
			if (!$this->data) {
				
				//Массив исключительно кодов товаров. Да, придется пройти массив 2 раза. Иначе как мы поймем, что основной товар для спецпредложения есть в корзине.
				$just_products_ids = array();
				foreach ($this->session->data['cart'] as $key => $quantity) {
					$product = explode(':', $key);
					$just_products_ids[$product[0]] = $quantity;
				}
				
				if ($this->customer->isLogged()) {
					$customer_group_id = $this->customer->getCustomerGroupId();
					} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}
				
				//Массив ВАЛИДНЫХ спецпредложений
				$all_ao_ids = array();
				$main_product_to_ao_ids = array();
				$amount_of_aos_for_one_main_product_id = array();
				foreach ($this->session->data['cart'] as $key => $quantity) {
					$product = explode(':', $key);
					
					if (!empty($product[4])) {
						$product_additional_offer_id = (int)$product[4];
						$product_id = (int)$product[0];
						
						$ao_query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "' AND ao_product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
						
						//	var_dump("SELECT * FROM product_additional_offer WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "' AND ao_product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
						
						if ($ao_query->row){
							$ao_main_product_id = $ao_query->row['product_id'];
							
							if (!isset($amount_of_aos_for_one_main_product_id[$ao_query->row['product_id']])){
								$amount_of_aos_for_one_main_product_id[$ao_query->row['product_id']] = 1;
								} else {
								$amount_of_aos_for_one_main_product_id[$ao_query->row['product_id']]++;
							}
							
							if (isset($just_products_ids[$ao_main_product_id])){
								
								if (isset($main_product_to_ao_ids[$ao_main_product_id])){
									$main_product_to_ao_ids[$ao_main_product_id][] = $product_additional_offer_id;
									} else {
									$main_product_to_ao_ids[$ao_main_product_id] = array($product_additional_offer_id);
								}
								
								$all_ao_ids[$product_additional_offer_id] = $quantity;
							}
						}
					}
				}
				
				//	var_dump($amount_of_aos_for_one_main_product_id);
				
				//var_dump($main_product_to_ao_ids);				
				
				foreach ($this->session->data['cart'] as $key => $quantity) {
					$product = explode(':', $key);
					$product_id = $product[0];
					$stock = true;
					
					// Options
					if (!empty($product[1])) {
						$temp = explode('-', $product[1]);
						$options = unserialize(base64_decode($temp[0]));
						} else {
						$options = array();
					}
					
					// Profile
					if (!empty($product[2])) {
						$profile_id = $product[2];
						} else {
						$profile_id = 0;
					}
					
					
					// Комплект
					if (!empty($product[3])) {
						$set_id = $product[3];
						
						// Получаем товар, родительский в наборе
						
						} else {
						$set_id = 0;
					}
					
					//product stock overload id
					$stock_tmp_query = $this->db->query("SELECT stock_product_id FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
					
					$real_product_id = false;
					if ($stock_tmp_query->num_rows){
						//реальный товар
						if ($stock_tmp_query->row['stock_product_id']){
							$real_product_id = $stock_tmp_query->row['stock_product_id'];
						}
					}
					
					
					$product_query = $this->db->query(
					"SELECT *, p.image as image,				
					(SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as store_overload_price,
					(SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) as store_overload_price_national,
					m.name as manufacturer
					FROM product p 
					LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
					LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
					WHERE p.product_id = '" . (int)$product_id . "' 
					AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
					AND p.date_available <= NOW() AND p.status = '1'");
					
				//	var_dump($product_query->row);
					
					if ($product_query->num_rows) {
						
						//overloading price
						if (isset($product_query->row['store_overload_price']) && $product_query->row['store_overload_price']){
							$product_query->row['price']  = $product_query->row['store_overload_price'];
						}
						
						//second overloading price
						$do_percent = true;
						$overload_price_national = false;
						if (isset($product_query->row['store_overload_price_national']) && $product_query->row['store_overload_price_national']){
							$product_query->row['price'] = $this->currency->convert($product_query->row['store_overload_price_national'], $this->config->get('config_regional_currency'), $this->config->get('config_currency'), false, false);	$overload_price_national = $product_query->row['price'];									
							$do_percent = false;							
						}
						
						$option_price = 0;
						$option_points = 0;
						$option_weight = 0;
						
						$option_data = array();
						
						
						//Q: Options Boost - overwrite product image with option image for cart (pt1)
						$option_price_only = false;
						$prod_image = $product_query->row['image'];											
						
						foreach ($options as $product_option_id => $option_value) {
							$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM product_option po LEFT JOIN `option` o ON (po.option_id = o.option_id) LEFT JOIN option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
							
							if ($option_query->num_rows) {
								if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'block' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
									$option_value_query = $this->db->query("SELECT *
									FROM product_option_value pov 
									LEFT JOIN option_value ov ON (pov.option_value_id = ov.option_value_id) 
									LEFT JOIN option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
									WHERE 
									pov.product_option_value_id = '" . (int)$option_value . "' AND 
									pov.product_option_id = '" . (int)$product_option_id . "' AND 
									ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
									
									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
											} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}
										
										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
											} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}
										
										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
											} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}
										
										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}
										//Q: Options Boost - Support additional price prefixes
										if (!empty($option_value_query)) {
											if ($option_value_query->row['price_prefix'] == '%') {
												$option_price = $option_price + ($price * ($option_value_query->row['price']/100));
												$option_value_query->row['price_prefix'] = '+';
												} elseif ($option_value_query->row['price_prefix'] == '=') {
												$option_price = $option_value_query->row['price'];
												$option_value_query->row['price_prefix'] = '';
												$option_price_only = true;
												} elseif ($option_value_query->row['price_prefix'] == '*') {
												$option_price += $option_value_query->row['price'] * $price;
												$option_value_query->row['price_prefix'] = '+';
												} elseif ($option_value_query->row['price_prefix'] == '1') {
												$option_price += $option_value_query->row['price'];
												$option_value_query->row['price_prefix'] = '1';
											}
											
											// Add sku to to option name ONLY if not overriding the main product model
											if (!empty($option_value_query->row['ob_sku']) && !empty($option_value_query->row['ob_sku_override']) && $option_value_query->row['ob_sku_override']) {
												$product_query->row['model'] = $option_value_query->row['ob_sku'];
												} else { // leave as part of option name
												$option_value_query->row['name'] = ($option_value_query->row['ob_sku']) ? ($option_value_query->row['name'] . ' ['.$option_value_query->row['ob_sku'].']') : $option_value_query->row['name'];
											}
										}
										//
										
										//Q: Options Boost - overwrite product image with option image for cart (pt2)
										if (isset($option_value_query->row['ob_image']) && $option_value_query->row['ob_image']) {
											$prod_image = $option_value_query->row['ob_image'];
										}//
										
										$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $option_value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'option_value'            => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
										);
									}
									} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
									foreach ($option_value as $product_option_value_id) {
										$option_value_query = $this->db->query("SELECT * FROM product_option_value pov LEFT JOIN option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
										
										
										if ($option_value_query->num_rows) {
											if ($option_value_query->row['price_prefix'] == '+') {
												$option_price += $option_value_query->row['price'];
												} elseif ($option_value_query->row['price_prefix'] == '-') {
												$option_price -= $option_value_query->row['price'];
											}
											
											if ($option_value_query->row['points_prefix'] == '+') {
												$option_points += $option_value_query->row['points'];
												} elseif ($option_value_query->row['points_prefix'] == '-') {
												$option_points -= $option_value_query->row['points'];
											}
											
											if ($option_value_query->row['weight_prefix'] == '+') {
												$option_weight += $option_value_query->row['weight'];
												} elseif ($option_value_query->row['weight_prefix'] == '-') {
												$option_weight -= $option_value_query->row['weight'];
											}
											
											if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
												$stock = false;
											}
											
											$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'option_value'            => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
											'quantity'                => $option_value_query->row['quantity'],
											'subtract'                => $option_value_query->row['subtract'],
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'points'                  => $option_value_query->row['points'],
											'points_prefix'           => $option_value_query->row['points_prefix'],
											'weight'                  => $option_value_query->row['weight'],
											'weight_prefix'           => $option_value_query->row['weight_prefix']
											);
										}
									}
									} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
									$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'option_value'            => $option_value,
									'type'                    => $option_query->row['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',
									'weight'                  => '',
									'weight_prefix'           => ''
									);
								}
							}
						}
						
						
						$this->load->model('catalog/group_price');
						$model_catalog_group_price = $this->registry->get('model_catalog_group_price');
						
						$price = $product_query->row['price'];
						$price_old = false;
						$points = $product_query->row['points'];
						
						// Product Discounts
						$discount_quantity = 0;
						
						foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
							$product_2 = explode(':', $key_2);
							
							if ($product_2[0] == $product_id) {
								$discount_quantity += $quantity_2;
							}
						}
						
						$product_discount_query = $this->db->query("SELECT price, points FROM product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
						
						if ($product_discount_query->num_rows) {
							$price_old = $price;
							$price = $product_discount_query->row['price'];
							$points = (isset($product_discount_query->row['points']) && $product_discount_query->row['points'] > 0) ? $product_discount_query->row['points'] : $points;
						}
						
						$product_special_query = $this->db->query("SELECT price, points_special, currency_scode FROM product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND (store_id = '" . (int)$this->config->get('config_store_id') . "' OR store_id = -1) ORDER BY store_id DESC, priority ASC LIMIT 1");
						
						if ($product_special_query->num_rows) {
							
							$special = $product_special_query->row['price'];
							
							if ($product_special_query->row['currency_scode'] && $product_special_query->row['currency_scode'] != $this->config->get('config_currency')){
								$special = $this->currency->convert($special, $product_special_query->row['currency_scode'], $this->config->get('config_currency'), false, false);
							}
							
							$special_tmp = false;
							if ($this->currency->percent){						
								if ($this->currency->plus){
									$special_tmp = $special + ($special/100*(int)$this->currency->percent);																				
									} else {
									$special_tmp = $special - ($special/100*(int)$this->currency->percent);								
								}
							}																
							
							if ($overload_price_national){
								if ($special_tmp && $price > $special_tmp){
									$price = $special_tmp;							
									} else {
									if ($price > $special){
										$price_old = $price;
										$price = $special;							
									}	
								}							
								
								} else {
								if ($price > $special){
									$price_old = $price;
									$price = $special;							
								}	
							}
							
							$points = ($product_special_query->row['points_special'] > 0) ? $product_special_query->row['points_special'] : $points;
							} else {
							$special = false;
						}	
						
						// Reward Points
						/*
							$product_reward_query = $this->db->query("SELECT points FROM product_reward WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND store_id = '" . $this->config->get('config_store_id') . "'");
							
							if ($product_reward_query->num_rows) {
							$reward = $product_reward_query->row['points'];
							} else {
							$reward = 0;
							}
						*/
						$this->load->model('catalog/product');				
						$reward = $this->getCurrentProductReward($this->registry->get('model_catalog_product')->getProduct($product_id));
						
						
						$this_is_special_offer = false;
						$this_is_special_offer_present = false;					
						//Спецпредложения. Блять, а как проверить, есть ли исходный товар в корзине-то, а?) Решение найдено, в самом начале этой функции.
						if (!empty($product[4])) {
							
							$product_additional_offer_id = (int)$product[4];
							
							//Код спецпредложения - 4 элемент. Понимаем, есть ли основной товар в корзине, его количество. А также запросом выясним, не истек ли срок спецпредложения, и валидна ли группа. Если все валидно - подменим цену и поставим на вывод флаг спецпредложения!
							$ao_query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "' AND ao_product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
							
							//Такой код спецпредложения есть и валиден, продолжаем...
							if ($ao_query->row){
								$ao_main_product_id = $ao_query->row['product_id'];
								
								//и код основного товара есть в корзине
								if (isset($just_products_ids[$ao_main_product_id])){
									//количество = количеству основного товара
									$quantity = $just_products_ids[$ao_main_product_id] * $ao_query->row['quantity'];
									//ставим флаги, цену перечитаем потом
									$this_is_special_offer = true;
									$this_is_special_offer_present = ($ao_query->row['percent'] == 100);	
									
									
									//пересчитываем цену
									if ($ao_query->row['price'] && $ao_query->row['price'] > 0 ){
																	
										//Считаем процентное соотношение цены акционной и обычной
										$percent = ($ao_query->row['price'] / $price) * 100;
										$reward = ($reward) / 100 * $percent;
									
										$price = $ao_query->row['price'];
										
										} else {
										if ($this_is_special_offer_present){
											$price = 0;
											} else {
											$price = $price - ($price * $ao_query->row['percent'] / 100);
											$reward = $reward - ($reward * $ao_query->row['percent'] / 100);
										}
										
										
									}
									
									} else {
									//Вроде бы это и спецпредложение, но оно, одначе, невалидно на данном этапе. Соответственно, хуй на него дальше.	
									
								}
								} else {
								//Вроде бы это и спецпредложение, но оно, одначе, невалидно на данном этапе. Соответственно, хуй на него дальше.	
								
							}						
						}
						
						//OVERLOADING REWARD TO ZERO
						if ($price == 0){
							$reward = 0;
						}
						
						// Downloads
						$download_data = array();
						
						$download_query = $this->db->query("SELECT * FROM product_to_download p2d LEFT JOIN download d ON (p2d.download_id = d.download_id) LEFT JOIN download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
						
						foreach ($download_query->rows as $download) {
							$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask'],
							'remaining'   => $download['remaining']
							);
						}
						
						// Stock
						if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
							$stock = false;
						}
						
						$recurring = false;
						$recurring_frequency = 0;
						$recurring_price = 0;
						$recurring_cycle = 0;
						$recurring_duration = 0;
						$recurring_trial_status = 0;
						$recurring_trial_price = 0;
						$recurring_trial_cycle = 0;
						$recurring_trial_duration = 0;
						$recurring_trial_frequency = 0;
						$profile_name = '';
						
						if ($profile_id) {
							$profile_info = $this->db->query("SELECT * FROM `profile` `p` JOIN `product_profile` `pp` ON `pp`.`profile_id` = `p`.`profile_id` AND `pp`.`product_id` = " . (int)$product_query->row['product_id'] . " JOIN `profile_description` `pd` ON `pd`.`profile_id` = `p`.`profile_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`profile_id` = " . (int)$profile_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$customer_group_id)->row;
							
							if ($profile_info) {
								$profile_name = $profile_info['name'];
								
								$recurring = true;
								$recurring_frequency = $profile_info['frequency'];
								$recurring_price = $profile_info['price'];
								$recurring_cycle = $profile_info['cycle'];
								$recurring_duration = $profile_info['duration'];
								$recurring_trial_frequency = $profile_info['trial_frequency'];
								$recurring_trial_status = $profile_info['trial_status'];
								$recurring_trial_price = $profile_info['trial_price'];
								$recurring_trial_cycle = $profile_info['trial_cycle'];
								$recurring_trial_duration = $profile_info['trial_duration'];
							}
						}
						
						//Остальные обработки назначения цены ТОЛЬКО В СЛУЧАЕ если этот товар - не спецпредложение
						if (!$this_is_special_offer){
							$price = $model_catalog_group_price->updatePrice($product_query->row['product_id'], $price);
						}
						
						if (!$this_is_special_offer){
							if ($this->currency->percent){						
								if ($this->currency->plus){
									if ($do_percent){
										$price = $price + ($price/100*(int)$this->currency->percent);									
									}									
									} else {
									if ($do_percent){
										$price = $price - ($price/100*(int)$this->currency->percent);								
									}
								}						
							}			
						}
						
						//И сюда тоже исключаем спецпредложение
						if ($product_query->row['price_national'] && $product_query->row['price_national'] > 0 && $product_query->row['currency'] == $this->currency->getCode() && !$this_is_special_offer){
							$price_national = $model_catalog_group_price->updatePrice($product_query->row['product_id'], $product_query->row['price_national']);
							} else {
							$price_national = $this->currency->convert($price + $option_price , $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
						}
						
						$total_national = $price_national * $quantity;
						$total = $this->currency->convert($total_national, $this->config->get('config_regional_currency'), $this->config->get('config_currency'));
						
						$priceOptional = ($price + $option_price);
						if (isset($this->data['set_'.$set_id])) {
							if (isset($this->data['set_'.$set_id]['price'])) {
								$this->data['set_'.$set_id]['price'] += ($priceOptional * $quantity);
								} else {
								$this->data['set_'.$set_id]['price'] = ($priceOptional * $quantity);
							}
							// $priceOptional = 0;
							
							if (isset($this->data['set_'.$set_id]['quantity'])) {
								// $this->data['set_'.$set_id]['quantity'] += 1;
								} else {
								// $this->data['set_'.$set_id]['quantity'] = 1;
							}
						}
						
						/*if ($set_id && isset($this->session->data['cart']['set_'.$set_id]) && $this->session->data['cart']['set_'.$set_id]) {
							$quantity = $quantity * $this->session->data['cart']['set_'.$set_id];
						}*/
						
						$childProductArray = array();
						
						if ($set_id) {
							$tmp = $this->db->query("SELECT ps.quantity, p.short_name FROM product_to_set ps RIGHT JOIN product p ON (ps.product_id = p.product_id) WHERE ps.set_id = '" . (int)$set_id."'");
							if ($tmp->num_rows) {
								foreach ($tmp->rows as $p) {
									$childProductArray[] = array(
									'name' => $p['short_name'],
									'quantity' => $quantity * $p['quantity'],
									);
								}                            
							}
						}
						
						$saving = false;
						if ($price_old){
							$saving = round((($priceOptional - $price_old)/($price_old + 0.01))*100, 0);
						}
						
						$stock_field_identifier = 'quantity_stock';
						
						if ($this->config->get('config_warehouse_identifier')){
							$stock_field_identifier = trim($this->config->get('config_warehouse_identifier'));
						}
						
						$this->data[$key] = array(
						'key'                       => $key,
						'product_id'                => ($real_product_id)?$real_product_id:$product_query->row['product_id'],
						'from_stock'				=> ($real_product_id)?true:false,
						'current_in_stock'		    => (int)($product_query->row[$stock_field_identifier]>0),
						'fully_in_stock'			=> (int)($product_query->row[$stock_field_identifier] >= $quantity),
						'amount_in_stock'			=> (int)($product_query->row[$stock_field_identifier]),
						'is_certificate'          	=> (strpos($product_query->row['location'], 'certificate') !== false),
						'is_special_offer'          => $this_is_special_offer,
						'is_special_offer_present'  => $this_is_special_offer_present,
						'ao_id'						=> ($this_is_special_offer)?$product_additional_offer_id:0,
						'ao_product_id'				=> ($this_is_special_offer)?$ao_main_product_id:0,
						'name'                      => $product_query->row['name'],
						'model'                     => $product_query->row['model'],
						'manufacturer_id'           => $product_query->row['manufacturer_id'],
						'manufacturer'           	=> $product_query->row['manufacturer'],
						'shipping'                  => $product_query->row['shipping'],
						'image'       				=> $prod_image, //Q: Options Boost - overwrite product image with option image for cart (pt3)
						'option'                    => $option_data,
						'download'                  => $download_data,
						'quantity'                  => $quantity,
						'minimum'                   => $product_query->row['minimum'],
						'points_only_purchase'		=> $product_query->row['points_only_purchase'],
						'subtract'                  => $product_query->row['subtract'],
						'stock'                     => $stock,
						'currency'                  => $product_query->row['currency'],
						'price_old'					=> $price_old,
						'saving'					=> $saving,
						'price'                     => $priceOptional,
						'price_national'            => $price_national,
						'total'                     => ($price + $option_price) * $quantity,
						'total_national'            => $total_national,
						'reward'                    => $reward * $quantity,
						'points'                    => $reward * $quantity,
						'tax_class_id'              => $product_query->row['tax_class_id'],
						'weight'                    => ($product_query->row['weight'] + $option_weight) * $quantity,
						'weight_class_id'           => $product_query->row['weight_class_id'],
						'length'                    => $product_query->row['length'],
						'width'                     => $product_query->row['width'],
						'height'                    => $product_query->row['height'],
						'length_class_id'           => $product_query->row['length_class_id'],
						'profile_id'                => $profile_id,
						'profile_name'              => $profile_name,
						'recurring'                 => $recurring,
						'recurring_frequency'       => $recurring_frequency,
						'recurring_price'           => $recurring_price,
						'recurring_cycle'           => $recurring_cycle,
						'recurring_duration'        => $recurring_duration,
						'recurring_trial'           => $recurring_trial_status,
						'recurring_trial_frequency' => $recurring_trial_frequency,
						'recurring_trial_price'     => $recurring_trial_price,
						'recurring_trial_cycle'     => $recurring_trial_cycle,
						'recurring_trial_duration'  => $recurring_trial_duration,
						'set'                       => $set_id,
						'childProductArray'         => $childProductArray,
						);
						if (!isset($this->data[$key]['set_name'])) {
							$this->data[$key]['set_name'] = '';
						}
						} else {
						//                    if (strpos($product_id, 'set_') === false) {
						$this->remove($key);
						//              }
					}
				}
			}
			
			ksort($this->data);
			
			return ($this->data);
		}
		
		public function getRecurringProducts() {
			$recurring_products = array();
			
			foreach ($this->getProducts() as $key => $value) {
				if ($value['recurring']) {
					$recurring_products[$key] = $value;
				}
			}
			
			return $recurring_products;
		}
		
		public function add($product_id, $qty = 1, $option, $profile_id = '', $set_id = false, $ao_id = false) {
			// TODO Нужно тут убрать ф-ция формирования ключа, и вызвать метод makeCartKey(...)
			// $key = $this->makeCartKey($product_id, $option, $profile_id, $set_id, $ao_id);
			$key = (int)$product_id . ':';
			
			if ($option) {
				$key .= base64_encode(serialize($option)) . ':';
				}  else {
				$key .= ':';
			}
			
			if ($profile_id) {
				$key .= (int)$profile_id. ':';
				} else {
				$key .= ':';
			}
			
			if ($set_id) {
				$key .= (int)$set_id;
				} else {
				$key .= ':';
			}
			
			if ($ao_id) {
				$key .= (int)$ao_id;
				} else {
				
			}
			
			$minQuery = $this->db->query("SELECT minimum FROM product WHERE product_id = '" . $product_id . "'");
			
			if ($minQuery->num_rows && !empty($minQuery->row['minimum'])){
				$minimum = $minQuery->row['minimum'];
				
				if ($qty < $minimum){					
					$qty = $minimum;
				}
			}
			
			if ((int)$qty && ((int)$qty > 0)) {
				if (!isset($this->session->data['cart'][$key])) {
					$this->session->data['cart'][$key] = (int)$qty;
					} else {
					$this->session->data['cart'][$key] += (int)$qty;
				}
			}
			
			$this->data = array();
		}
		
		public function makeCartKey ($product_id, $option, $profile_id, $set_id, $ao_id) {
			$key = (int)$product_id . ':';
			
			if ($option) {
				$key .= base64_encode(serialize($option)) . ':';
				}  else {
				$key .= ':';
			}
			
			if ($profile_id) {
				$key .= (int)$profile_id. ':';
				} else {
				$key .= ':';
			}
			
			if ($set_id) {
				$key .= (int)$set_id;
				} else {
				$key .= ':';
			}
			
			if ($ao_id) {
				$key .= (int)$ao_id;
				} else {
				
			}
			return $key;
		}
		
		public function update($key, $qty) {
			
			$product = explode(':', $key);
			$product_id = $product[0];
			
			$m_query = $this->db->query("SELECT minimum FROM product WHERE product_id = '" . $product_id . "'");
			
			$minimum = 1;
			if ($m_query->num_rows && !empty($m_query->row['minimum'])){
				$minimum = (int)$m_query->row['minimum'];
			}
			
			if ($qty > 0 && $qty < $minimum){
				$qty = $minimum;
			}
			
			if ($qty > 0 && $qty % $minimum <> 0){
				$qty = $qty + ($qty % $minimum);
			}
			
			if ((int)$qty && ((int)$qty > 0)) {
				if (strpos($key, 'set_') !== false) {
					$this->session->data['cart'][$key] = $qty;
					} else {
					$this->session->data['cart'][$key] = (int)$qty;
					$this->data = array();
				}
				} else {
				$this->remove($key);
				$this->data = array();
			}
			
		}
		
		public function remove($key) {
			if (isset($this->session->data['cart'][$key])) {
				/*if (strpos($key, "set_") !== false) {
					$products = $this->getProducts();
					foreach ($products as $k => $v) {
					if ($v['set'] == str_replace('set_', '', $key)) {
					unset($this->session->data['cart'][$k]);
					}
					}
				}*/
				
				unset($this->session->data['cart'][$key]);
				
			}
			
			$this->data = array();
		}
		
		public function clear() {
			unset($this->session->data['cart']);
			$this->session->data['cart'] = array();
			$this->data = array();
			if ($this->customer->isLogged()){
				$this->db->non_cached_query("UPDATE customer SET cart = '' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			}
		}
		
		public function getWeight() {
			$weight = 0;
			
			foreach ($this->getProducts() as $product) {
				if ($product['shipping']) {
					$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
				}
			}
			
			return $weight;
		}
		
		public function getSubTotal() {
			$total = 0;
			$total_national = 0;
			
			foreach ($this->getProducts() as $product) {
				
				if ($product['total'] == 0 && $product['total_national'] > 0){
					$total_national += $product['total_national'];
					} else {
					$total_national += $this->currency->convert($product['total'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
				}
				$total += $product['total'];
			}
			
			$total = $this->currency->convert($total_national, $this->config->get('config_regional_currency'), $this->config->get('config_currency'));
			
			return $total;
		}
		
		public function getSubTotalExcludeManufacturers($exclude = array()) {
			$total = 0;
			$total_national = 0;
			
			foreach ($this->getProducts() as $product) {
				if (!in_array($product['manufacturer_id'], $exclude)) {
					$total_national += $this->currency->convert($product['total'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
					$total += $product['total'];
				}
			}
			
			$total = $this->currency->convert($total_national, $this->config->get('config_regional_currency'), $this->config->get('config_currency'));
			
			return $total;
		}
		
		public function getSubTotalInNationalCurrency() {
			$total_national = 0;
			
			foreach ($this->getProducts() as $product) {
				$total_national += $product['total_national'];
			}
			
			return $total_national;
		}
		
		public function getSubTotalInNationalCurrencyExcludeManufacturers($exclude = array()) {
			$total_national = 0;
			
			foreach ($this->getProducts() as $product) {
				
				if (!in_array($product['manufacturer_id'], $exclude)) {
					$total_national += $product['total_national'];
				}
			}
			
			return $total_national;
		}
		
		public function getTaxes() {
			$tax_data = array();
			
			foreach ($this->getProducts() as $product) {
				if ($product['tax_class_id']) {
					$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);
					
					foreach ($tax_rates as $tax_rate) {
						if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
							$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
							} else {
							$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
						}
					}
				}
			}
			
			return $tax_data;
		}
		
		public function getTotal() {
			$total = 0;
			
			foreach ($this->getProducts() as $product) {
				$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
			}
			
			return $total;
		}
		
		public function getTotalInNationalCurrency() {
			$total_national = 0;
			
			foreach ($this->getProducts() as $product) {
				if (isset($product['total_national'])){
					$total_national += $product['total_national'];	
					//$total_national += $product['price'] *  $product['quantity']
					} else {
					$total_national += $this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'), $this->config->get('config_regional_currency')) * $product['quantity'];
				}
			}
			
			return $total_national;
		}
		
		public function countProducts() {
			$product_total = 0;
			
			$products = $this->getProducts();
			
			foreach ($products as $product) {
				$product_total += $product['quantity'];
			}
			
			return $product_total;
		}
		
		public function countProductsNoQuantity() {
			return count($this->getProducts());
		}
		
		public function hasAdditionalOffer() {
			
			$products = $this->getProducts();
			
			foreach ($products as $product) {
				if ($product['price'] == 0 || $product['is_special_offer'] || $product['is_special_offer_present']){
					return true;
				} else {
					continue;
				}
			}
			
			return false;
		}
		
		public function hasProducts() {
			return count($this->session->data['cart']);
		}
		
		public function hasRecurringProducts(){
			return count($this->getRecurringProducts());
		}
		
		public function canUseRewards(){
			if (!$this->customer->isLogged()){
				return false;
			}	
			
			if (!empty($this->session->data['coupon'])){
				return false;
			}
			
			foreach ($this->getProducts() as $product){
				if ($product['ao_id']){
					return false;
				}
			}
			
			foreach ($this->getProducts() as $product){
				if ($product['is_certificate']){
					return false;
				}
			}
			
			return true;
		}
		
		public function getEntityReward($entity_type, $entity_id, $store_id = false){
		
			if (!$store_id){
				$store_id = $this->config->get('config_store_id');
			}
			
			$query = $this->db->query("SELECT points, percent FROM entity_reward er WHERE 
			entity_id = '" . (int)$entity_id . "'
			AND entity_type = '" . $this->db->escape($entity_type) . "'
			AND (er.store_id = '-1' OR er.store_id = '" . (int)$store_id . "')
			AND ((er.date_start = '0000-00-00' OR er.date_start <= NOW()) AND (er.date_end = '0000-00-00' OR er.date_end >= NOW()))
			ORDER BY er.store_id DESC LIMIT 1");
			
			if ($query->num_rows){
				return ['points' => $query->row['points'], 'percent' => $query->row['percent']];
				} else {
				return ['points' => 0, 'percent' => 0];
			}
		}
		
		public function getCurrentProductReward($product, $store_id = false, $currency = false, $customer_group_id = false){

			if (!$this->config->get('reward_status')){
				return false;
			}
		
			if (!$store_id){
				$store_id = $this->config->get('config_store_id');
			}
			
			if ($store_id != 2){
			//	return false;
			}
			
			if (!$currency){
				$currency = $this->config->get('config_regional_currency');
			}
			
			if (!$customer_group_id && is_object($this->customer) && $this->customer->isLogged()) {
                $customer_group_id = $this->customer->getCustomerGroupId();
				} else {
                $customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$price = $product['price'];
			if ($product['special']){
				$price = $product['special'];
			}
			
			$price = $this->currency->convert($price, $this->config->get('config_currency'), $currency);
			
			$points = 0;
			$percent = 0;
			
			//Прямое назначение в товаре
			$query = $this->db->query("SELECT points, percent FROM product_reward pr WHERE 
			pr.product_id = '" . (int)$product['product_id'] . "' 
			AND customer_group_id = '" . (int)$customer_group_id . "' 
			AND ((pr.date_start = '0000-00-00' OR pr.date_start <= NOW()) AND (pr.date_end = '0000-00-00' OR pr.date_end >= NOW()))
			AND (pr.store_id = '-1' OR pr.store_id = '" . (int)$store_id . "') 
			ORDER BY pr.store_id DESC LIMIT 1");						
			
			if ($query->num_rows) {				
				$points = $query->row['points'];
				$percent = $query->row['percent'];				
			}
			
			//Коллекция
			if (!$points && !$percent && $product['collection_id']){
				$entityReward = $this->getEntityReward('co', $product['collection_id'], $store_id);
				
				if ($entityReward['points'] || $entityReward['percent']) {				
					$points = $entityReward['points'];
					$percent = $entityReward['percent'];				
				}
			}
			
			//Бренд
			if (!$points && !$percent && $product['manufacturer_id']){
				$entityReward = $this->getEntityReward('m', $product['manufacturer_id'], $store_id);
				
				if ($entityReward['points'] || $entityReward['percent']) {				
					$points = $entityReward['points'];
					$percent = $entityReward['percent'];				
				}
			}
			
			
			//Категория
			if (!$points && !$percent && $product['main_category_id']){
				$entityReward = $this->getEntityReward('c', $product['main_category_id'], $store_id);
				
				if ($entityReward['points'] || $entityReward['percent']) {				
					$points = $entityReward['points'];
					$percent = $entityReward['percent'];				
				}
			}
			
			if (!$points && !$percent){
				$points = 0;
				$percent = $this->config->get('rewardpoints_pointspercent');
			}
			
			if ($percent){
				$bonus = (int)(($price/100)*$percent);							
			}
			
			if ($points){
				$bonus = (int)$points;
			}			
			
			if ($bonus){
				return $bonus;								
			}
			
			return false;
		}
		
		public function hasStock() {
			$stock = true;
			
			foreach ($this->getProducts() as $product) {
				if (!$product['stock']) {
					$stock = false;
				}
			}
			
			return $stock;
		}
		
		public function hasShipping() {
			//****
			// Изменена функция для игнорирования настройки "необходима доставка" товара
			//****
			/*
				$shipping = false;
				
				foreach ($this->getProducts() as $product) {
				if ($product['shipping']) {
				$shipping = true;
				
				break;
				}
				}
				
				return $shipping;
			*/
			return true;
		}
		
		public function hasDownload() {
			$download = false;
			
			foreach ($this->getProducts() as $product) {
				if ($product['download']) {
					$download = true;
					
					break;
				}
			}
			
			return $download;
		}
	}							
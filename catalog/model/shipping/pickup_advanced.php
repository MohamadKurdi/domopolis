<?php
	class ModelShippingPickupAdvanced extends Model
	{
		function getQuote($address)
		{
			$points      = $this->config->get('pickup_advanced_module');
			$settings    = $this->config->get('pickup_advanced_settings');
			$language_id = $this->config->get('config_language_id');
			
			if ($points)
			{
				foreach ($points as $i => $point)
				{
					if (!$point['status'] && empty($address['bypass_point_status']))
					{
						continue;
					}
					if (!$point['geo_zone_id'])
					{
						$status = true;
					}
					else
					{
						$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$point['geo_zone_id'] . "'" .
						" AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
						if ($query->num_rows)
						{
							$points[$i]['geozone_status'] = true;
							$status = true;
						}
						else
						{
							$points[$i]['geozone_status'] = false;
						}
					}
				}
			}
			
			if ($points && $this->config->get('pickup_advanced_status'))
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
			
			$method_data = array();
			
			if ($status)
			{
				$quote_data = array();
				
				foreach ($points as $i => $point)
				{
					if (!$point['status']  && empty($address['bypass_point_status']))
					{
						continue;
					}
					
					$title = '';
					
					if (!empty($point['image']))
					{
						$server = $this->config->get('config_ssl');
						
						$title .= '<img src="' . $server . 'image/' . $point['image'] . '" style="margin-right: 10px; margin-bottom: 1px; vertical-align: middle;" alt="" />';
					}
					
					if (isset($point[$language_id]['tip'])) {
						$tip = html_entity_decode($point[$language_id]['tip']);
						} else {
						$tip = '';
					}
					
					$title .= $point[$language_id]['description'];
					
					if ($point['link_status'] == 1)
					{
						if (!empty($point[$language_id]['link']) && !empty($point[$language_id]['link_text']))
						{
							$data_link = urlencode($point[$language_id]['link']);						
						}
						} else {
						$data_link = false;
					}
					
					$cart_total  = $this->cart->getTotal();
					$cart_weight = $this->cart->getWeight();
					
					if ($point['percentage'] && $point['cost'] > 0)
					{
						$cost = ($cart_total / 100) * $point['cost'];
					}
					elseif ($point['relation'])
					{
						$cost = 0;
						
						$rates = explode(',', $point['cost']);
						
						foreach ($rates as $rate)
						{
							$data = explode(':', $rate);
							
							if ($data[0] >= $cart_total)
							{
								if (isset($data[1]))
								{
									$cost = $data[1];
								}
								
								break;
							}
						}
					}
					elseif ($point['weight'])
					{
						$cost = 0;
						
						$rates = explode(',', $point['cost']);
						
						foreach ($rates as $rate)
						{
							$data = explode(':', $rate);
							
							if ($data[0] >= $cart_weight)
							{
								if (isset($data[1]))
								{
									$cost = $data[1];
								}
								
								break;
							}
						}
					}
					else
					{
						$cost = $point['cost'];
					}
					
					if ($cost > 0)
					{
						$text = $this->currency->format($cost);
					}
					else
					{
						if ($this->config->get('pickup_advanced_null_cost'))
						{
							$text = $this->currency->format($cost);
						}
						else
						{
							if ($settings[$language_id]['null_cost'])
							{
								$text = $settings[$language_id]['null_cost'];
								if ($cost == 0){
									$cost = $settings[$language_id]['null_cost'];
								}
							}
							else
							{
								$text = '';
							}
						}
					}
					
					if ($point['display_threshold'])
					{
						$minmax = explode(',', $point['display_threshold']);
						
						$min = isset($minmax[0]) ? $minmax[0] : 0;
						$max = isset($minmax[1]) ? $minmax[1] : PHP_INT_MAX;
						
						if ($max <= 0)
						{
							$max = PHP_INT_MAX;
						}
						
						if ($min >= $cart_total || $max <= $cart_total)
						{
							$point['status'] = false;
						}
					}
					
					
					if (isset($address['do_not_check_city']) && $address['do_not_check_city']){
						
						$point['city_status'] = true;
						
						} else {
						
						if (isset($point['city_name']) && $point['city_name']){
							$rates = explode(',', $point['city_name']);
							
							$point['city_status'] = false;
							
							if (count($rates) > 0) {
								foreach ($rates as $rate) {
									$data = trim($rate);
									
									if (mb_strtolower($data, 'UTF-8') == mb_strtolower(trim($address['city']), 'UTF-8')) {
										$point['city_status'] = true;
										break;
									}
								}
							}
							
							} else {
							$point['city_status'] = true;
						}
						
					}
					
					if ($status == true && ($i == 0) && !empty($address['cdek_city_guid'])){
						
						$npWCQuery = $this->db->query("SELECT dadata_BELTWAY_HIT, dadata_BELTWAY_DISTANCE FROM cdek_cities WHERE code = '" . (int)$address['cdek_city_guid'] . "' AND region_code IN (9,81) LIMIT 1");
						
						if ($npWCQuery->num_rows){
							$point['city_status'] = false;
							
							if ($npWCQuery->row['dadata_BELTWAY_HIT'] == 'IN_MKAD'){
								$point['city_status'] = true;								
							}
							
							if ($npWCQuery->row['dadata_BELTWAY_HIT'] == 'NOT_FOUND'){
								$point['city_status'] = true;								
							}
							
							if ($npWCQuery->row['dadata_BELTWAY_HIT'] == 'OUT_MKAD' && (int)$npWCQuery->row['dadata_BELTWAY_DISTANCE'] < 50){
								$point['city_status'] = true;	
							}
							
							} else {
							
							$point['city_status'] = false;
							
						}
					} 
					
					if ($status == true && ($i == 1) && !empty($address['novaposhta_city_guid'])){
						
						$npWCQuery = $this->db->query("SELECT WarehouseCount FROM novaposhta_cities_ww WHERE Ref = '" . $this->db->escape($address['novaposhta_city_guid']) . "' AND Area = '71508131-9b87-11de-822f-000c2965ae0e' LIMIT 1");
						
						if ($npWCQuery->num_rows){
							
							$point['city_status'] = true;
							
							} else {
							
							$point['city_status'] = false;
							
						}
					} 
					
					if (!empty($address['bypass_point_status'])){
						$point['status'] = true;
					}
					
					if ($point['status'] && $point['city_status'] && $point['geozone_status'])
					{
						$quote_data['point_' . $i] = array
						(
						'code'         => 'pickup_advanced.point_' . $i,
						'title'        => $title,
						'cost'         => $cost,
						'cost_national' => $cost,
						'description' => $tip,
						'status'       => $point['status'],
						'checkactive'  => true, //CHANGE
						'tax_class_id' => 0,
						'text'         => $text,
						'remove_title' => isset($point[$language_id]['remove_title'])?$point[$language_id]['remove_title']:false,
						'data-yandex-link'    => $data_link
						);
						
						$sort_order[$i] = $point['sort_order'];
					}
				}
				
				if (isset($sort_order))
				{
					array_multisort($sort_order, SORT_ASC, $quote_data);
				}
				
				if (count($quote_data) > 0)
				{
					$method_data = array
					(
					'code'       => 'pickup_advanced',
					'title'      => $settings[$language_id]['title'],
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('pickup_advanced_sort_order'),
					'error'      => false,
					'status'     => true //CHANGE
					);
				}
			}
			
			return $method_data;
		}
	}			
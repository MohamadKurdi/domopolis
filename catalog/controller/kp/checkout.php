<?php
	
	class ControllerKPCheckout extends Controller {				
		public function index(){			
		}
		
		public function guessCDEKPriceAjax(){		
			$cdek_city_code = !empty($this->request->get['cdek_city_code'])?$this->request->get['cdek_city_code']:'';
			$type = !empty($this->request->get['type'])?$this->request->get['type']:'';
			
			$this->load->model('kp/deliverycounters');
			$json = $this->model_kp_deliverycounters->guessCDEKPriceAjax($cdek_city_code, $type);
						
			$this->response->setOutput($json);
		}		
		
		public function getCitiesListAjax(){
			foreach ($this->language->loadRetranslate('common/header') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->data['cities'] = $this->getDefaultCities(12)['results'];
			
			$this->load->model('localisation/country');
			$this->data['country_code'] = $this->model_localisation_country->getCountryISO2($this->config->get('config_country_id'));
			
			$this->template = $this->config->get('config_template') . '/template/structured/city_suggest.tpl';
			$this->response->setOutput($this->render());
		}
		
		public function setCustomerCityAjax(){
			$json = array('success' => false);
			$this->load->model('tool/simpleapicustom');		
			
			$city = trim($this->request->post['city']);
			$id = trim($this->request->post['id']);
			
			if ($result = $this->model_tool_simpleapicustom->setCustomerCityToSession($city, $id)){
				$json = array('success' => true);
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function setCustomerCityWithNoIDAjax(){
			$json = array('success' => false);
			$this->load->model('tool/simpleapicustom');		
			
			$city = trim($this->request->post['city']);
			
			if ($result = $this->model_tool_simpleapicustom->setCustomerCityWithNoIDToSession($city)){
				$json = array('success' => true);
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function getAddressByGeoCoordinates(){
			$lat = !empty($this->request->post['lat'])?$this->request->post['lat']:'';
			$lon = !empty($this->request->post['lon'])?$this->request->post['lon']:'';
			
			$result = array('success' => false);
			
			if ($lat && $lon){
				$this->load->library('hobotix/Shipping/Cdek');
				$cdekAPI = new \hobotix\shipping\Cdek($this->registry);
				$result = $cdekAPI->suggestAddressByLocation(array('lat' => $lat, 'lon' => $lon), 100, 1);
			}
			
			
			$this->response->setOutput(json_encode($result));									
		}
		
		public function getDefaultCities($limit = 24){
			$results = array('results' => array());
			
			if ($this->config->get('config_country_id') == 220){
				
				$query = $this->db->query("SELECT nc.* FROM novaposhta_cities_ww nc ORDER BY WarehouseCount DESC LIMIT $limit");
				
				if ($query->num_rows){
					
					foreach ($query->rows as $row){
						
						$name = $row['DescriptionRu'];
						if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
							$name = $row['Description'];
						}
						
						
						$results['results'][] = array(
						'id' => $row['Ref'],
						'text' => $name						
						);						
					}
					
				}
				} else {
				
				$query = $this->db->query("SELECT cc.* FROM cdek_cities cc WHERE country_id = '" . (int)$this->config->get('config_country_id') . "' ORDER BY WarehouseCount DESC LIMIT $limit");
				
				if ($query->num_rows){
					
					foreach ($query->rows as $row){														
						
						$results['results'][] = array(
						'id' => $row['code'],
						'text' => $row['city']						
						);						
					}
					
				}
			}
			
			
			return $results;	
		}
				
		public function guessCitiesIDWhenNOTSET(){
			
			$query = !empty($this->request->get['query'])?$this->request->get['query']:'';
			$query = trim(mb_strtolower($query));
			$result = array('city' => '');
						
			if ($this->config->get('config_country_id') == 220){
				
				$query = $this->db->query("SELECT nc.* FROM novaposhta_cities_ww nc WHERE LOWER(nc.Description) LIKE '" . $this->db->escape($query) . "' OR LOWER(nc.DescriptionRu) LIKE '" . $this->db->escape($query) . "' LIMIT 1");
				
				if ($query->num_rows){
					$result = array(
					'city' => $query->row['Ref']
					);
				}
				
				} else {
				
				$query = $this->db->query("SELECT cc.* FROM cdek_cities cc WHERE LOWER(cc.city) LIKE '" . $this->db->escape($query) . "' AND country_id = '" . (int)$this->config->get('config_country_id') . "' LIMIT 1");
				
				if ($query->num_rows){
					$result = array(
					'city' => $query->row['code']
					);
				}
				
			}
			
			$this->response->setOutput(json_encode($result));
		}
		
		public function suggestPostCode(){
			$query = !empty($this->request->get['query'])?$this->request->get['query']:'';
			$query = trim($query);
			$result = array();
			
			if (!$query){
				$this->response->setOutput(json_encode($result));
				} else {
				
				$dbQuery = $this->db->query("SELECT nc.* FROM novaposhta_cities nc WHERE Ref = '" . $this->db->escape($query) . "' LIMIT 1");
				
				if ($dbQuery->num_rows){
					$row = $dbQuery->row;
					
					$name = $row['Index1'] . ', ' . $row['AreaDescriptionRu'] . ', ' . $row['SettlementTypeDescriptionRu'] .' '. $row['DescriptionRu'];
					if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
						$name = $row['Index1'] . ', ' . $row['AreaDescription'] . ', ' . $row['SettlementTypeDescription'] .' '. $row['Description'];
					}
					
					$result = array(
					'postcode' 		=> $row['Index1'],
					'fulladdress'	=> $name,
					);
					
				}
			}
			
			$this->response->setOutput(json_encode($result));
		}
		
		public function suggestCities(){
			$query = !empty($this->request->get['query'])?$this->request->get['query']:'';
			$json = !empty($this->request->get['json'])?true:false;
			$limit = !empty($this->request->get['limit'])?(int)$this->request->get['limit']:20;
			$query = trim(mb_strtolower($query));
			$results = array('results' => array());
			
			if ($limit < 0){
				$limit = 10;
			}
			
			if ($limit >= 20){
				$limit = 20;
			}
			
			if (mb_strlen($query) < 2){
				$results = $this->getDefaultCities();
				$this->response->setOutput(json_encode($results, true));
				} else {
				
				if ($this->config->get('config_country_id') == 220){
					
					$dbQuery = $this->db->query("SELECT nc.Ref, nc.DescriptionRu, nc.Description FROM novaposhta_cities_ww nc WHERE LOWER(nc.Description) LIKE '" . $this->db->escape($query) . "%' OR LOWER(nc.DescriptionRu) LIKE '" . $this->db->escape($query) . "%' ORDER BY WarehouseCount DESC LIMIT $limit");
					
					if ($dbQuery->num_rows){
						
						foreach ($dbQuery->rows as $row){
							
							$name = $row['DescriptionRu'];
							if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id'] && $row['Description']){
								$name = $row['Description'];
							}
							
							
							$results['results'][] = array(
							'id' => $row['Ref'],
							'text' => $name,
							'text_short' => $name
							);						
						}
						
						} else {						
						
						$dbQuery = $this->db->query("SELECT nc.Ref, nc.DescriptionRu, nc.AreaDescriptionRu, nc.Description, nc.AreaDescription FROM novaposhta_cities nc WHERE LOWER(nc.Description) LIKE '" . $this->db->escape($query) . "%' OR LOWER(nc.DescriptionRu) LIKE '" . $this->db->escape($query) . "%' LIMIT $limit");
						
						if ($dbQuery->num_rows){
							
							foreach ($dbQuery->rows as $row){
								
								$name = $row['DescriptionRu'] . ' (' . $row['AreaDescriptionRu'] . ')';
								if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id'] && $row['Description']){
									$name = $row['Description'] . ' (' . $row['AreaDescription'] . ')';
								}
								
								
								$results['results'][] = array(
								'id' => $row['Ref'],
								'text' => $name,
								'text_short' => $name
								);						
							}
							
						}
						
					}
					} else {
					
					$dbQuery = $this->db->query("SELECT cc.* FROM cdek_cities cc WHERE LOWER(cc.city) LIKE '" . $this->db->escape($query) . "%' AND country_id = '" . (int)$this->config->get('config_country_id') . "' ORDER BY WarehouseCount DESC LIMIT $limit");
					
					if ($dbQuery->num_rows){
						
						foreach ($dbQuery->rows as $row){														
							
							$name = $row['city'];
							
							if ($row['region'] && $row['region'] != $row['city']){
								$name .= ', ' . $row['region'];
							}
							
							
							
							$results['results'][] = array(
							'id'   		=> $row['code'],
							'fias' 		 => $row['fias_guid'],
							'text' 		 => $name,	
							'text_short' => $row['city']
							);						
						}
						
					}
				}
				
				if (!$results['results']){
					$results['results'][] = array(                   
					'id'                 => false,
					'text' 				 => 'Nothing found',
					'text_short'         => 'Nothing found',
					);
				}
				
				if ($json){
					$results = $results['results'];
				}
				
				$this->response->setOutput(json_encode($results));
			}			
		}
		
		public function suggestFiasGuid(){
			$cdek_city_code = !empty($this->request->get['cdek_city_code'])?$this->request->get['cdek_city_code']:'';
			
			$this->response->setOutput($this->getFiasGuid($cdek_city_code));
		}
		
		public function getFiasGuid($cityID){
			if (!trim($cityID)){
				return false;
			}
			
			$query = $this->db->query("SELECT fias_guid FROM cdek_cities WHERE code = '" . (int)$cityID . "' LIMIT 1");
			
			if ($query->num_rows && !empty($query->row['fias_guid'])){
				return $query->row['fias_guid'];
			}
			
			return false;
		}
		
		public function getCurrentCity(){
			$this->load->model('tool/simpleapicustom');
			$json = array();
			
			$query = !empty($this->request->get['city'])?$this->request->get['city']:'';
			
			if ($city = $this->model_tool_simpleapicustom->getCityIdByName($query)){
				if ($city['id']){
					
					$json = array(
					'city' 		=> $city['city'],
					'id'   		=> $city['id'],
					'guessed' 	=> true,						
					'found'		=> true,
					);
					
					} else {
					
					$this->load->model('localisation/country');
					if ($result['countryCode'] && $result['countryCode'] == $this->model_localisation_country->getCountryISO2($this->config->get('config_country_id'))){								
						$json = array(
						'city' 		=> $city['city'],
						'id'   		=> $city['id'],
						'guessed' 	=> true,						
						'found'		=> false,
						);
					}
					
				}
			}
			
			$this->session->data['customer_location_city'] = $json;
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function suggestCurrentCity(){
			$this->load->model('tool/simpleapicustom');	
			
			$json = array(
			'city' 		=> $this->config->get('config_default_city'),
			'id'   		=> $this->model_tool_simpleapicustom->getDefaultCityGuid($this->config->get('config_default_city')),
			'guessed' 	=> false,
			'found'		=> false,
			);
			
			$ipapi = new maciejkrol\ipapicom\ipapi ($this->config->get('config_ip_api_key'));
			$result = $ipapi->locate($this->request->server['REMOTE_ADDR']);

			if ($result){
				if (!empty($result['city'])){													
					if ($city = $this->model_tool_simpleapicustom->getCityIdByName($result['city'])){
						
						if ($city['id']){
							
							$json = array(
							'city' 		=> $city['city'],
							'id'   		=> $city['id'],
							'guessed' 	=> true,						
							'found'		=> true,
							);
							
							} else {
							
							$this->load->model('localisation/country');
							if ($result['countryCode'] && $result['countryCode'] == $this->model_localisation_country->getCountryISO2($this->config->get('config_country_id'))){								
								$json = array(
								'city' 		=> $city['city'],
								'id'   		=> $city['id'],
								'guessed' 	=> true,						
								'found'		=> false,
								);
							}
							
						}
					}
				}
			}
			
			
			$this->model_tool_simpleapicustom->setCustomerCityToSession($json['city'], $json['id']);	
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function suggestLocation(){
			$this->load->library('hobotix/Shipping/Cdek');
			$cdekAPI = new \hobotix\shipping\Cdek($this->registry);
			
			$lat = '55.888796';
			$lon = '37.430328';
			
			$cdekAPI->suggestAddressByLocation(array('lat' => $lat, 'lon' => $lon));
		}		
		
		public function suggestAddress(){
			$this->load->library('hobotix/Shipping/Cdek');
			$cdekAPI = new \hobotix\shipping\Cdek($this->registry);
			
			$query = !empty($this->request->get['query'])?$this->request->get['query']:'';
			$cdek_city_code = !empty($this->request->get['cdek_city_code'])?$this->request->get['cdek_city_code']:'';
			$exact = !empty($this->request->get['exact'])?true:false;
			$result = array();
			
			if (mb_strlen($query) < 3){
				$this->response->setOutput(json_encode($result, true));
			}
			
			$this->load->model('localisation/country');
			$this->data['current_country_id'] = $this->config->get('config_country_id');
			$this->data['current_country']    = $this->model_localisation_country->getCountry($this->data['current_country_id']);
			
			if ($exact && !mb_strpos($query, ', кв')){
				$query .= ', кв 1';
			}						
			
			$request = array(
			'query'             => $query,			
			'restrict_value'    => true,
			'locations'         => array(
			'country_iso_code' => $this->data['current_country']['iso_code_2']
			)
			);
			
			if ($cdek_city_code && $fias_id = $this->getFiasGuid($cdek_city_code)){				
				$request['locations']['city_fias_id'] = $fias_id;			
			}
			
			$results = $cdekAPI->suggestAddressByDadata($request);
			
			if ($exact){
				$results = $results[0];
			}
			
			if (!$results){
				$results[] = array(                   
				'value'              => 'Ничего не найдено, введите, пожалуйста, вручную',
				'unrestricted_value' => 'Ничего не найдено, введите, пожалуйста, вручную',
				'city'               => 'Не задан город',
				'postal_code'        => '',
				);
			}
			
			$this->response->setOutput(json_encode($results));
		}

	}																																																	
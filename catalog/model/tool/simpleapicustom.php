<?php
    /*
        @author Dmitriy Kubarev
        @link   http://www.simpleopencart.com
	*/
    
    class ModelToolSimpleApiCustom extends Model {
        public function example($filterFieldValue) {
            $values = array();
            
            $values[] = array(
            'id'   => 'my_id',
            'text' => 'my_text'
            );
            
            return $values;
		}
		
		public function checkIfUseUAServices(){
			
			return ($this->config->get('config_country_id') == 220);
			
		}
		
		public function checkIfUseRUKZBYServices(){
			
			return ($this->config->get('config_country_id') != 220);
			
		}
		
		
		public function getCustomerAddressGeneralField(){		
			
			if ($customer_id = $this->customer->isLogged()){
				$query = $this->db->query("SELECT address_1 FROM address WHERE LENGTH(address_1) > 1 AND customer_id = '" . (int)$customer_id . "' LIMIT 1");
				
				if ($query->num_rows){
					return $query->row['address_1'];
				}
			}
			
			return '';
			
		}
        
        public function getDefaultCityGuid($cityName){
            $cityName = trim($cityName);
			
			if (!$this->customer->isLogged() && !empty($this->session->data['customer_location_city']) && !empty($this->session->data['customer_location_city']['id'])){
				
				if ($city = $this->model_tool_simpleapicustom->getCityNameByID($this->session->data['customer_location_city']['id'])){
					return $this->session->data['customer_location_city']['id'];
				}
				
				if (!empty($this->session->data['customer_location_city_with_no_id'])){
					return '';
				}
			}
            
            if ($cityName == $this->config->get('config_default_city')){
                $array = array(
                '220' => '8d5a980d-391c-11dd-90d9-001a92567626',
                '176' => '44',
                '109' => '4961',
                '20'  => '9220'
                );
                
                if (!empty($array[$this->config->get('config_country_id')])){
                    return $array[$this->config->get('config_country_id')];
				}
                
                
                } else {
				
				if ($cityID = $this->getCityIdByName($cityName)){
					return $cityID;
				}
                
			}
            
            return '';
		}
        
        public function getDefaultCityFias(){
            $array = array(            
            '176' => '44',
            '109' => '4961',
            '20'  => '9220'
            );
            
            if ($this->config->get('config_country_id') == 220){
                return false;
			}
			
			if (!empty($this->session->data['customer_location_city']) && !empty($this->session->data['customer_location_city']['id'])){
				
				if ($city = $this->model_tool_simpleapicustom->getCityNameByID($this->session->data['customer_location_city']['id'])){
					return $this->getCDEKFias($this->session->data['customer_location_city']['id']);
				}	
			}
            
            return $this->getCDEKFias($array[$this->config->get('config_country_id')]);
		}
        
        public function checkCaptcha($value, $filter) {
            if (isset($this->session->data['captcha']) && $this->session->data['captcha'] != $value) {
                return false;
			}
            
            return true;
		}
        
        public function getYesNo($filter = '') {
            return array(
            array(
            'id'   => '1',
            'text' => $this->language->get('text_yes')
            ),
            array(
            'id'   => '0',
            'text' => $this->language->get('text_no')
            )
            );
		}
        
        public function setCustomerCityToSession($city, $id){
            if ($id){
				$result = $this->getCityNameByID($id);
				
				if ($result){
					
					$this->session->data['customer_location_city'] = array(
					'city' => $result['city'],
					'id' => $id
					);
					
					return true;
				}
			}
            
            return false;
            
		}
		
		public function setCustomerCityWithNoIDToSession($city){
            if ($city){
				$this->session->data['customer_location_city_with_no_id'] = array(
				'city' => $result['city'],
				'id' => ''
				);
				
				return true;
			}
            
            return false;
            
		}
		
		
		public function getDefaultCustomerCityStructForDeliveryGuessing(){
			
			$city = $this->getCityNameByID($this->getDefaultCityGuid($this->config->get('config_default_city')));
			
			return $customer_city = array(
			'city' 	=> $city['city'],
			'id' 	=> $city['id'],
			);
		}
		
        
        public function getAndCheckCurrentCity(){
            $customer_city = false;
            
			//unset($this->session->data['customer_location_city']);
			unset($this->session->data['customer_location_city_with_no_id']);
			if (!CRAWLER_SESSION_DETECTED){
				if (!empty($this->session->data['customer_location_city']) && !empty($this->session->data['customer_location_city']['city']) && !is_null($this->session->data['customer_location_city']['city'])){
					
                    $customer_city_tmp = $this->session->data['customer_location_city'];
					
					} else {		
					$this->load->model('checkout/order');
					
					if ($this->customer->isLogged() && $order = $this->model_checkout_order->getLastCustomerOrder($this->customer->isLogged())){
						
						if ($this->config->get('config_country_id') == 220){                            
                            $cityGuidFieldID = 'novaposhta_city_guid';
                            } else {                            
                            $cityGuidFieldID = 'cdek_city_guid';                            
						}
                        
						$query = $this->db->query("SELECT `$cityGuidFieldID` FROM order_simple_fields WHERE order_id = '" . $order['order_id'] . "' LIMIT 1");
						
						if ($query->num_rows){                                                
							$customer_city_tmp = array(
							'city' => $order['shipping_city'],
							'id'   => $query->row[$cityGuidFieldID]
							);
							} else {
							
							if ($guessed_city = $this->getCityIdByName($order['shipping_city'])){
								
								$customer_city_tmp = array(
								'city' => $guessed_city['city'],
								'id'   => $guessed_city['id']
								);
								
							}
							
						}
						
					}						
				}
				
				if (empty($customer_city_tmp)){
					$customer_city_tmp = array(
					'city' => false,
					'id'   => false
					);
				}
				
                if ($city = $this->getCityNameByID($customer_city_tmp['id'])){
					
                    $customer_city = array(
                    'city' => $city['city'],
                    'id' => $customer_city_tmp['id']
                    );
					
                    } else {
                    unset($this->session->data['customer_location_city']);
				}
			}
            
            return $customer_city;
            
		}
		
		public function getJustCityIdByName($cityName){  
			
            $cityName = trim($cityName);
            $cityName = mb_strtolower($cityName);
			
			if ($this->config->get('config_country_id') == 220){                            
				$cityGuidFieldID = 'novaposhta_city_guid';
				} else {                            
				$cityGuidFieldID = 'cdek_city_guid';                            
			}
			
			
			if (empty($this->session->data['simple']['shipping_address'][$cityGuidFieldID])){
				if (!empty($this->session->data['customer_location_city']['id'])){
					return $this->session->data['customer_location_city']['id'];
				}
			}
			
			if (!$cityName){
				return $this->getDefaultCityGuid($cityName);
			}
			
			$city = $this->getCityIdByName($cityName);
			
			return $city['id'];
			
		}
		
		public function getCityIdByName($cityName){  
			$cityName = trim($cityName);
			$cityName = mb_strtolower($cityName);
			
			if (!$cityName){
				return array('city' => $cityName, 'id' => false);
			}
			
			//Украина - справочник городов Новой Почты
			if ($this->config->get('config_country_id') == 220){
				
				$query = $this->db->query("SELECT nc.Ref, nc.Description, nc.DescriptionRu FROM novaposhta_cities_ww nc WHERE LOWER(nc.Description) LIKE '" . $this->db->escape($cityName) . "' OR LOWER(nc.DescriptionRu) LIKE '" . $this->db->escape($cityName) . "' ORDER BY WarehouseCount DESC LIMIT 1");
				
				if ($query->num_rows){
					$name = $query->row['DescriptionRu']?$query->row['DescriptionRu']:$query->row['Description'];
					if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
						$name = $query->row['Description']?$query->row['Description']:$query->row['DescriptionRu'];
					}
					
					return array('city' => $name,
					'id' =>$query->row['Ref']);
					
					} else {
					
					$query = $this->db->query("SELECT nc.Ref, nc.Description, nc.DescriptionRu FROM novaposhta_cities nc WHERE LOWER(nc.Description) LIKE '" . $this->db->escape($cityName) . "' OR LOWER(nc.DescriptionRu) LIKE '" . $this->db->escape($cityName) . "' LIMIT 1");
					
					if ($query->num_rows){
						$name = $query->row['DescriptionRu']?$query->row['DescriptionRu']:$query->row['Description'];
						if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
							$name = $query->row['Description']?$query->row['Description']:$query->row['DescriptionRu'];
						}
						
						return array('city' => $name,
						'id' =>$query->row['Ref']);
					}
					
				}
				} else {
				
				$query = $this->db->query("SELECT cc.city_id, cc.code, cc.city FROM cdek_cities cc WHERE LOWER(cc.city) LIKE '" . $this->db->escape($cityName) . "' AND country_id = '" . (int)$this->config->get('config_country_id') . "' LIMIT 1");
				
				if ($query->num_rows){
					
					return array('city' => $query->row['city'],
					'id' => $query->row['code']);
					
				}
			}
			
			return array('city' => $cityName, 'id' => false);
			
		}
		
		public function getCityNameByID($cityID){
			$result = array();
			
			if ($this->config->get('config_country_id') == 220){
				
				$query = $this->db->query("SELECT nc.* FROM novaposhta_cities_ww nc WHERE Ref LIKE '" . $this->db->escape($cityID) . "' LIMIT 1");
				
				if ($query->num_rows){                    
					$name = $query->row['DescriptionRu']?$query->row['DescriptionRu']:$query->row['Description'];
					if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
						$name = $query->row['Description']?$query->row['Description']:$query->row['DescriptionRu'];
					}
					
					return $result = array('city' => $name, 'id' => $cityID);
				}
				
				$query = $this->db->query("SELECT nc.Ref, nc.DescriptionRu, nc.AreaDescriptionRu, nc.Description, nc.AreaDescription FROM novaposhta_cities nc WHERE Ref LIKE '" . $this->db->escape($cityID) . "' LIMIT 1");
				
				if ($query->num_rows){                    
					$name = $query->row['DescriptionRu']?$query->row['DescriptionRu']:$query->row['Description'];
					if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
						$name = $query->row['Description']?$query->row['Description']:$query->row['DescriptionRu'];
					}
					
					return $result = array('city' => $name, 'id' => $cityID);
				}
				
				} else {
				
				$query = $this->db->query("SELECT cc.city FROM cdek_cities cc WHERE code = '" . (int)$cityID . "' LIMIT 1");
				
				if ($query->num_rows){                    
					$name = $query->row['city'];
					
					return $result = array('city' => $name, 'id' => $cityID);
				}
			}
			
			return $result;
		}
		
		public function getCDEKFias($cityID){
			
			if (!trim($cityID)){
				return '';
			}
			
			$query = $this->db->query("SELECT fias_guid FROM cdek_cities WHERE code = '" . (int)$cityID . "' LIMIT 1");
			
			if ($query->num_rows && !empty($query->row['fias_guid'])){
				return $query->row['fias_guid'];
			}
			
			return '';			
		}
		
		public function getJustinWarehouses($CityName){
			
			if (!$this->checkIfUseUAServices()){
				return array();
			}
			
			$CityName = trim(mb_strtolower($CityName));
			
			$values = array();
			
			$query = $this->db->query("SELECT * FROM justin_warehouses WHERE LOWER(CityDescr) LIKE '" . $this->db->escape($CityName) . "' OR LOWER(CityDescrRU) LIKE '" . $this->db->escape($CityName) . "' ORDER BY departNumber ASC");
			
			foreach ($query->rows as $row){
				
				$name = $row['DescrRU'];
				if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_justin_ua_language')]['language_id']){
					$name = $row['Descr'];
				}
				
				$values[] = array(
				'id'   => $row['Uuid'],
				'text' => $name
				);
			}
			
			return $values;   
		}
		
		public function getCDEKWarehouses($CityID){
			
			if (!$this->checkIfUseRUKZBYServices()){
				return array();
			}
			
			$values = array();
			
			$query = $this->db->query("SELECT * FROM cdek_deliverypoints WHERE city_code = '" . (int)$CityID . "'");
			
			foreach ($query->rows as $row){
				
				//    $name = $row['name'] . ' (' . $row['address'] . ')';
				$name = $row['address'];
				
				if ($row['nearest_metro_station']){
					$name .= ' (м. ' . $row['nearest_metro_station'] . ')';
				}
				
				$values[] = array(
				'id'   => $row['code'],
				'text' => $name
				);
			}
			
			//Заглушка для Москвы, добавим сюда все региональные отделения, потому что понаехавшие тупые
			if ($CityID == 44){
				$query = $this->db->query("SELECT * FROM cdek_deliverypoints WHERE city_code <> '" . (int)$CityID . "' AND region_code = 81");
				
				foreach ($query->rows as $row){
					
					//    $name = $row['name'] . ' (' . $row['address'] . ')';
					$name = $row['city'] . ', ';
					
					$name .=$row['address'];
					
					if ($row['nearest_metro_station']){
						$name .= ' (м. ' . $row['nearest_metro_station'] . ')';
					}
					
					$values[] = array(
					'id'   => $row['code'],
					'text' => $name
					);
				}
			}
			
			return $values; 
		}                
		
		public function getNovaPoshtaWarehouses($CityRef){
			
			if (!$this->checkIfUseUAServices()){
				return array();
			}
			
			$values = array();
			
			$query = $this->db->query("SELECT * FROM novaposhta_warehouses WHERE CityRef = '" . $this->db->escape($CityRef) . "' ORDER BY number ASC");
			
			foreach ($query->rows as $row){
				
				$name = $row['DescriptionRu'];
				if ($this->config->get('config_language_id') == $this->registry->get('languages')[$this->config->get('config_novaposhta_ua_language')]['language_id']){
					$name = $row['Description'];
				}
				
				$values[] = array(
				'id'   => $row['Ref'],
				'text' => $name
				);
			}
			
			return $values; 
		}
		
		public function getNovaPoshtaStreets($CityRef){
			
			if (!$this->checkIfUseUAServices()){
				return array();
			}
			
			$values = array();
			
			$query = $this->db->query("SELECT * FROM novaposhta_streets WHERE CityRef = '" . $this->db->escape($CityRef) . "'");
			
			if (!$query->num_rows){
				require_once(DIR_SYSTEM . 'library/deliveryapis/NovaPoshta.php');	
				$novaPoshta = new NovaPoshta($this->registry);		
				$novaPoshta->getRealTimeStreets($CityRef);
				
				$query = $this->db->non_cached_query("SELECT * FROM novaposhta_streets WHERE CityRef = '" . $this->db->escape($CityRef) . "'");
			}
			
			foreach ($query->rows as $row){
				$name = $row['Description'] . ', ' . $row['StreetsType'];
				
				$values[] = array(
				'id'   => $row['Ref'],
				'text' => $name
				);
			}
			
			return $values; 
		}
}                                            							
<?php
	
	namespace hobotix\shipping;
	
	class Cdek {
		private $db;
		private $config;
		private $registry;
		private $client;
		
		private $apiLogin = null;
		private $apiKey = null;
		
		private $countryMapping = array(
		'RU' => 176,
		'KZ' => 109,
		'BY' => 20,
		);
		
		
		public function __construct($registry) {			
			$this->registry = $registry;
			$this->config = $this->registry->get('config');
			$this->db = $this->registry->get('db');

			$this->apiLogin = $this->config->get('cdek_login');
			$this->apiKey = $this->config->get('cdek_password');
			
			$this->client = new \CdekSDK\CdekClient($this->apiLogin, $this->apiKey);
		}
		
		public function updateBeltWayHits(){
			
			$query = $this->db->query("SELECT * FROM cdek_cities WHERE country_id = '176' AND region_code IN (9,81) AND (ISNULL(dadata_BELTWAY_HIT) OR dadata_BELTWAY_HIT = '') AND dadata_BELTWAY_DISTANCE = 0 AND LENGTH(latitude) > 0 AND LENGTH(longitude) > 0");
			
			$total = $query->num_rows;
			$count = 1;			
			foreach ($query->rows as $row){							
				$result = $this->suggestAddressByLocation(array('lat' => (float)$row['latitude'], 'lon' => (float)$row['longitude']), 50, 1);
				
				$radius = 50;
				while (empty($result['beltway_hit'])){								
					$result = $this->suggestAddressByLocation(array('lat' => (float)$row['latitude'], 'lon' => (float)$row['longitude']), $radius, 1);
					echoLine('[DD] ' . $count . '/' . $total . ' Город ' . $row['city'] . ', увеличиваем радиус, ' . $radius);					
					$radius += 50;
					
					if ($radius > 1000){
						$result['beltway_hit'] = 'NOT_FOUND';
						$result['beltway_distance'] = '0';
					}
				}
				
				if (!empty($result['beltway_hit'])){
					echoLine('[DD] ' . $count . '/' . $total . ' Город ' . $row['city'] . ', всё ок, результат: ' . $result['beltway_hit'] . ', расстояние: ' . (int)$result['beltway_distance']);
					
					$this->db->query("UPDATE cdek_cities SET dadata_BELTWAY_HIT = '" . $this->db->escape($result['beltway_hit']) . "', dadata_BELTWAY_DISTANCE = '" . (int)$result['beltway_distance'] . "' WHERE city_id = '" . (int)$row['city_id'] . "'");
					
					
					} else {
					echoLine('[DD] ' . $count . '/' . $total . ' Город ' . $row['city'] . ', нет нормального ответа, ');
					print_r($result);
				}
				
				$count++;
			}
			
			
			
			return $this;			
		}
		
		public function suggestAddressByLocation($location, $radius = 100, $count = false){		
			$key = $this->config->get('config_dadata_api_key');
			$results = array();
			
			$request = array(
			'lat' 			=> $location['lat'],
			'lon' 			=> $location['lon'],
			'radius_meters' => $radius,
			'count'			=> $count?$count:1
			);
			
			if ($ch = curl_init("https://suggestions.dadata.ru/suggestions/api/4_1/rs/geolocate/address")) {
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Origin: ' . HTTPS_SERVER,
				'Referer: ' . HTTPS_SERVER,
				'Content-Type: application/json',
				'Accept: application/json',
				'Authorization: Token ' . $key,
				));
				curl_setopt($ch, CURLOPT_POST, 1);            
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
				$curlResult = curl_exec($ch);             
				curl_close($ch);
			}
			
			if ($curlResult && $decodedResult = json_decode($curlResult, true)){                            
				foreach ($decodedResult['suggestions'] as $suggestion){
					$results[] = array(                   
					'value'              => $suggestion['value'],
					'unrestricted_value' => $suggestion['unrestricted_value'],
					'city'               => $suggestion['data']['city'],
					'postal_code'        => $suggestion['data']['postal_code'],
					'lat'        		 => $suggestion['data']['geo_lat'],
					'lon'				 => $suggestion['data']['geo_lon'],
					'beltway_hit'		 => $suggestion['data']['beltway_hit'],
					'beltway_distance'	 => $suggestion['data']['beltway_distance'],
					'house'				 => $suggestion['data']['house'],
					'house_type'		 => $suggestion['data']['house_type'],
					'flat'				 => $suggestion['data']['flat'],
					);
				}        
			}
			
			if ($count == 1 && !empty($results[0])){
				$results = $results[0];
			}
			
			return $results;
		}
		
		public function suggestAddressByDadata($request){		
			$key = $this->config->get('config_dadata_api_key');
			
			$results = array();
			
			if ($ch = curl_init("https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address")) {
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Origin: ' . HTTPS_SERVER,
				'Referer: ' . HTTPS_SERVER,
				'Content-Type: application/json',
				'Accept: application/json',
				'Authorization: Token ' . $key,
				));
				curl_setopt($ch, CURLOPT_POST, 1);            
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
				$curlResult = curl_exec($ch);             
				curl_close($ch);
			}
			
			if ($curlResult && $decodedResult = json_decode($curlResult, true)){                            
				foreach ($decodedResult['suggestions'] as $suggestion){
					$results[] = array(                   
					'value'              => $suggestion['value'],
					'unrestricted_value' => $suggestion['unrestricted_value'],
					'city'               => $suggestion['data']['city'],
					'postal_code'        => $suggestion['data']['postal_code'],
					'geolocation'        => $suggestion['data']['geo_lat'] . ',' . $suggestion['data']['geo_lon'],
					'beltway_hit'		 => $suggestion['data']['beltway_hit'],
					'beltway_distance'	 => $suggestion['data']['beltway_distance'],
					'house'				 => $suggestion['data']['house'],
					'house_type'		 => $suggestion['data']['house_type'],
					'flat'				 => $suggestion['data']['flat'],
					);
				}        
			}
			
			return $results;				
		}

		public function checkStatus(){
			$request = new \CdekSDK\Requests\RegionsRequest();
			$request->setSize(5);
			$request->setPage(0);
			$request->setCountryCode('RU');
			$response = $this->client->sendRegionsRequest($request);

			if (!empty($response)){
				foreach ($response as $region){
					if ($region->getUuid() && $region->getCountryName()){
						return mb_strtolower($region->getCountryName());
					} else {
						return false;
					}
				}
			}

			return false;
		}
		
		
		public function updateZones($countryCode){
			
			$request = new \CdekSDK\Requests\RegionsRequest();
			
			$page = 0;
			$emptyResponse = false;
			while (!$emptyResponse){
				
				echoLine('[CD] Страница ' . $page);
				
				$request->setSize(1000);
				$request->setPage($page);
				$request->setCountryCode($countryCode);
				$response = $this->client->sendRegionsRequest($request);
				
				$emptyResponse = true;
				foreach ($response as $region){
					if ($region->getUuid()){
						$emptyResponse = false;
					}
					
					if ($region->getUuid()){			
						echoLine('[CD] Регион ' . $region->getName());
						
						$this->db->query("INSERT INTO cdek_zones SET 
						region_uuid = '" . $this->db->escape($region->getUuid()) . "',
						country_id = '" . $this->db->escape($this->countryMapping[$countryCode]) . "',
						country_code = '" . $this->db->escape($region->getCountryCodeISO()) . "',
						country = '" . $this->db->escape($region->getCountryName()) . "',
						region = '" . $this->db->escape($region->getName()) . "',
						prefix = '" . $this->db->escape($region->getPrefix()) . "',
						region_code = '" . $this->db->escape($region->getCode()) . "',
						fias_region_guid = '" . $this->db->escape($region->getFiasGuid()) . "'
						ON DUPLICATE KEY UPDATE
						country_id = '" . $this->db->escape($this->countryMapping[$countryCode]) . "',
						country_code = '" . $this->db->escape($region->getCountryCodeISO()) . "',
						country = '" . $this->db->escape($region->getCountryName()) . "',
						region = '" . $this->db->escape($region->getName()) . "',
						prefix = '" . $this->db->escape($region->getPrefix()) . "',
						region_code = '" . $this->db->escape($region->getCode()) . "',
						fias_region_guid = '" . $this->db->escape($region->getFiasGuid()) . "'");
					}
				}
				
				$page++;
			}
			
			
			
			
			return $this;
		}
		
		
		public function updateCities(){
			
			$request = new \CdekSDK\Requests\CitiesRequest();
			
			$query = $this->db->query("SELECT * FROM cdek_zones WHERE 1");
			
			foreach ($query->rows as $row){
				
				echoLine('[CD] Страна ' . $row['country'] . ', регион ' . $row['region']);
				
				$page = 0;
				$emptyResponse = false;
				while (!$emptyResponse){
					$request->setCountryCode($row['country_code']);
					$request->setRegionCode($row['region_code']);
					
					echoLine('[CD] Страница ' . $page);
					$request->setSize(1000);
					$request->setPage($page);
					
					$response = $this->client->sendCitiesRequest($request);
					
					$emptyResponse = true;
					foreach ($response as $city){
						if ($city->getCityUuid()){
							$emptyResponse = false;
						}

						try{
							$region = $city->getRegion();
						} catch (TypeError  $e){
							echoLine($e->getMessage());
							$region = $row['region'];
						}
						
						echoLine('[CD]	 Город ' . $city->getCityName());
						
						$this->db->query("INSERT INTO cdek_cities SET 
						city_uuid = '" . $this->db->escape($city->getCityUuid()) . "',
						code = '" . $this->db->escape($city->getCityCode()) . "',
						city = '" . $this->db->escape($city->getCityName()) . "',
						fias_guid = '" . $this->db->escape($city->getFiasGuid()) . "',
						kladr_code = '" . $this->db->escape($city->getKladr()) . "',
						country_code = '" . $this->db->escape($city->getCountryCodeISO()) . "',
						country = '" . $this->db->escape($city->getCountry()) . "',
						country_id = '" . $this->db->escape($this->countryMapping[$row['country_code']]) . "',
						region = '" . $this->db->escape($region) . "',
						region_code = '" . $this->db->escape($row['region_code']) . "',												
						sub_region = '" . $this->db->escape($city->getSubRegion()) . "',
						postal_codes = '" . $this->db->escape($city->getFiasGuid()) . "',
						longitude = '" . $this->db->escape($city->getLongitude()) . "',
						latitude = '" . $this->db->escape($city->getLatitude()) . "',
						time_zone = '" . $this->db->escape($city->getTimeZone()) . "',
						payment_limit = '" . $this->db->escape($city->getPaymentLimit()) . "'
						ON DUPLICATE KEY UPDATE
						city = '" . $this->db->escape($city->getCityName()) . "',
						fias_guid = '" . $this->db->escape($city->getFiasGuid()) . "',
						kladr_code = '" . $this->db->escape($city->getKladr()) . "',
						country_code = '" . $this->db->escape($city->getCountryCodeISO()) . "',
						country = '" . $this->db->escape($city->getCountry()) . "',
						country_id = '" . $this->db->escape($this->countryMapping[$row['country_code']]) . "',
						region = '" . $this->db->escape($region) . "',
						region_code = '" . $this->db->escape($row['region_code']) . "',												
						sub_region = '" . $this->db->escape($city->getSubRegion()) . "',
						postal_codes = '" . $this->db->escape($city->getFiasGuid()) . "',
						longitude = '" . $this->db->escape($city->getLongitude()) . "',
						latitude = '" . $this->db->escape($city->getLatitude()) . "',
						time_zone = '" . $this->db->escape($city->getTimeZone()) . "',
						payment_limit = '" . $this->db->escape($city->getPaymentLimit()) . "'");
						
						
					}
					
					$page++;
				}	
			}	
			
			return $this;
			
		}			
		
		
		
		public function updateDeliveryPoints(){
			
			$request = new \CdekSDK\Requests\PvzListRequest();
			
			$query = $this->db->query("SELECT * FROM cdek_cities WHERE 1");
			
			foreach ($query->rows as $row){
				
				echoLine('[CD] Страна ' . $row['country'] . ', регион ' . $row['region'] . ', город ' . $row['city']);
				
				$request->setCityID($row['code']);
				$request->setType(\CdekSDK\Requests\PvzListRequest::TYPE_ALL);
				
				$response = $this->client->sendPvzListRequest($request);
				foreach ($response as $pvz){
					echoLine('[CD]	 ПВЗ ' . $pvz->Name);
					
					$OfficeImages = array();
					if (!is_null($pvz->OfficeImages)){
						foreach ($pvz->OfficeImages as $image) {
							$OfficeImages[] = $image->getUrl();
						}
					}
					
					$WorkTimes = array();
					if (!is_null($pvz->workTimes)){
						foreach ($pvz->workTimes as $worktime) {
							$WorkTimes[$worktime->getDay()] = $worktime->getPeriods();
						}
					}
					
					$weightMin = (!is_null($pvz->WeightLimit) && is_object($pvz->WeightLimit))?(int)$pvz->WeightLimit->getWeightMin():'0';
					$weightMax = (!is_null($pvz->WeightLimit) && is_object($pvz->WeightLimit))?(int)$pvz->WeightLimit->getWeightMax():'0';
					
					$sql = "INSERT INTO cdek_deliverypoints SET 
					code = '" . $this->db->escape($pvz->Code) . "',
					name = '" . $this->db->escape($pvz->Name) . "',
					country_code = '" . $this->db->escape($pvz->CountryCodeISO) . "',
					region_code = '" . $this->db->escape($pvz->RegionCode) . "',
					region = '" . $this->db->escape($pvz->RegionName) . "',
					city_code = '" . $this->db->escape($row['code']) . "',
					city = '" . $this->db->escape($pvz->City) . "',
					postal_сode = '" . $this->db->escape($pvz->PostalCode) . "',
					longitude = '" . $this->db->escape($pvz->coordX) . "',
					latitude = '" . $this->db->escape($pvz->coordY) . "',												
					address = '" . $this->db->escape($pvz->Address) . "',
					address_full = '" . $this->db->escape($pvz->FullAddress) . "',
					address_comment = '" . $this->db->escape($pvz->AddressComment) . "',
					nearest_station = '" . $this->db->escape($pvz->NearestStation) . "',
					nearest_metro_station = '" . $this->db->escape($pvz->MetroStation) . "',
					work_time = '" . $this->db->escape($pvz->WorkTime) . "',
					phones = '" . $this->db->escape(json_encode($pvz->phoneDetails)) . "',
					email = '" . $this->db->escape($pvz->Email) . "',
					note = '" . $this->db->escape($pvz->Note) . "',
					type = '" . $this->db->escape($pvz->Type) . "',
					owner_сode = '" . (int)($pvz->ownerCode) . "',
					take_only = '" . (int)($pvz->TakeOnly) . "',
					is_handout = '" . (int)($pvz->IsHandout) . "',
					is_reception = '" . (int)($pvz->IsReception) . "',
					is_dressing_room = '" . (int)($pvz->IsDressingRoom) . "',
					have_cashless = '" . (int)($pvz->HaveCashless) . "',
					have_cash = '" . (int)($pvz->HaveCash) . "',
					allowed_cod = '" . (int)($pvz->AllowedCod) . "',
					site = '" . $this->db->escape($pvz->Site) . "',
					office_image_list = '" . $this->db->escape(json_encode($OfficeImages)) . "',					
					work_time_list = '" . $this->db->escape(json_encode($WorkTimes)) . "',									
					weight_min  = '" . $weightMin . "',
					weight_max  = '" . $weightMax . "'
					ON DUPLICATE KEY UPDATE
					name = '" . $this->db->escape($pvz->Name) . "',
					country_code = '" . $this->db->escape($pvz->CountryCodeISO) . "',
					region_code = '" . $this->db->escape($pvz->RegionCode) . "',
					region = '" . $this->db->escape($pvz->RegionName) . "',
					city_code = '" . $this->db->escape($row['code']) . "',
					city = '" . $this->db->escape($pvz->City) . "',
					postal_сode = '" . $this->db->escape($pvz->PostalCode) . "',
					longitude = '" . $this->db->escape($pvz->coordX) . "',
					latitude = '" . $this->db->escape($pvz->coordY) . "',												
					address = '" . $this->db->escape($pvz->Address) . "',
					address_full = '" . $this->db->escape($pvz->FullAddress) . "',
					address_comment = '" . $this->db->escape($pvz->AddressComment) . "',
					nearest_station = '" . $this->db->escape($pvz->NearestStation) . "',
					nearest_metro_station = '" . $this->db->escape($pvz->MetroStation) . "',
					work_time = '" . $this->db->escape($pvz->WorkTime) . "',
					phones = '" . $this->db->escape(json_encode($pvz->phoneDetails)) . "',
					email = '" . $this->db->escape($pvz->Email) . "',
					note = '" . $this->db->escape($pvz->Note) . "',
					type = '" . $this->db->escape($pvz->Type) . "',
					owner_сode = '" . (int)($pvz->ownerCode) . "',
					take_only = '" . (int)($pvz->TakeOnly) . "',
					is_handout = '" . (int)($pvz->IsHandout) . "',
					is_reception = '" . (int)($pvz->IsReception) . "',
					is_dressing_room = '" . (int)($pvz->IsDressingRoom) . "',
					have_cashless = '" . (int)($pvz->HaveCashless) . "',
					have_cash = '" . (int)($pvz->HaveCash) . "',
					allowed_cod = '" . (int)($pvz->AllowedCod) . "',
					site = '" . $this->db->escape($pvz->Site) . "',
					office_image_list = '" . $this->db->escape(json_encode($OfficeImages)) . "',					
					work_time_list = '" . $this->db->escape(json_encode($WorkTimes)) . "',								
					weight_min  = '" . $weightMin . "',
					weight_max  = '" . $weightMax . "'";
					
					$this->db->query($sql);	
				}
				
			}
			
			$this->db->query("UPDATE cdek_cities SET WarehouseCount = (SELECT COUNT(cdek_deliverypoints.deliverypoint_id) FROM cdek_deliverypoints WHERE cdek_deliverypoints.city_code = cdek_cities.code) ");
			
			return $this;
		}			
		
	}										
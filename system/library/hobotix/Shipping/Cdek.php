<?php
	
	namespace hobotix\shipping;

	use AntistressStore\CdekSDK2;
	use AntistressStore\CdekSDK2\Entity\Requests\Check;
	use AntistressStore\CdekSDK2\Entity\Requests\{Agreement, Barcode, DeliveryPoints, Intakes, Invoice, Location, Order, Tariff, Webhooks};
	use AntistressStore\CdekSDK2\Entity\Responses\{AgreementResponse, CitiesResponse, DeliveryPointsResponse, EntityResponse, IntakesResponse, OrderResponse, PrintResponse, RegionsResponse, TariffListResponse, TariffResponse};
	use AntistressStore\CdekSDK2\Entity\Responses\{CheckResponse, PaymentResponse};
	use AntistressStore\CdekSDK2\Exceptions\{CdekV2AuthException, CdekV2RequestException};
	
	class Cdek {
		private $db;
		private $config;
		private $registry;
		private $CdekClient;
		
		private $apiLogin = null;
		private $apiKey = null;
		
		private $countryMapping = array(
		'RU' => 176,
		'KZ' => 109,
		'BY' => 20,
		);
		
		
		public function __construct($registry) {			
			$this->registry = $registry;
			$this->config 	= $this->registry->get('config');
			$this->db 		= $this->registry->get('db');

			$this->apiLogin = $this->config->get('config_cdek_api_login');
			$this->apiKey 	= $this->config->get('config_cdek_api_key');
			
			$this->CdekClient = new \AntistressStore\CdekSDK2\CdekClientV2($this->apiLogin, $this->apiKey);
		}

		private function getTrackingStatus($info){
			$status = 'NOT_SET';

			if (!empty($info->getStatuses()[0]) && ($info->getStatuses()[0])->getName()){
				$status = ($info->getStatuses()[0])->getName();
			}

			if ($status){
				echoLine('[Cdek::getTrackingStatus] Parsel status: ' . $status, 'i');
			}

			return $status;
		}

		private function prepareTrackingStatuses($info){
			$result = [];

			foreach ($info->getStatuses() as $cdekStatus){
				$result[] = [
					'code' => $cdekStatus->getCode(),
					'name' => $cdekStatus->getName(),
					'code' => $cdekStatus->getCity(),
					'time' => date('d.m.Y H:i', strtotime($cdekStatus->getDateTime())),


				];
			}

			return $result;
		}

		private function checkIfTaken($info){
			$status = false;

			if (!empty($info->getStatuses()[0]) && in_array(($info->getStatuses()[0])->getCode(), [
				'POSTOMAT_RECEIVED',
				'DELIVERED'				
			])){
				$status = true;
			}

			if ($status){
				echoLine('[Cdek::checkIfTaken] Parsel is taken: ' . $info->getCdekNumber(), 's');
			}

			return $status;
		}

		private function checkIfWaiting($info){
			$status = false;

			if (!empty($info->getStatuses()[0]) && in_array(($info->getStatuses()[0])->getCode(), [
				'ACCEPTED_AT_PICK_UP_POINT',
				'SENT_TO_RECIPIENT_CITY',
				'ACCEPTED_IN_TRANSIT_CITY',
				'TAKEN_BY_TRANSPORTER_FROM_TRANSIT_CITY',
				'READY_FOR_SHIPMENT_IN_TRANSIT_CITY',
				'RECEIVED_AT_SENDER_WAREHOUSE',
				'ACCEPTED_AT_TRANSIT_WAREHOUSE',
				'POSTOMAT_POSTED',
				'POSTOMAT_SEIZED',
				'TAKEN_BY_COURIER'				
			])){
				$status = true;
			}

			if ($status){
				echoLine('[Cdek::checkIfWaiting] Parsel is waiting: ' . $info->getCdekNumber(), 'w');
			}

			return $status;
		}

		private function checkIfRejected($info){
			$status = false;

			if ($info->getIsReturn()){
				$status = true;
			}

			if ($info->getIsReverse()){
				$status = true;
			}

			if (!empty($info->getStatuses()[0]) && in_array(($info->getStatuses()[0])->getCode(), [
				'NOT_DELIVERED',
				'RETURNED_TO_RECIPIENT_CITY_WAREHOUSE',
				'INVALID'				
			])){
				$status = true;
			}

			if ($status){
				echoLine('[Cdek::checkIfWaiting] Parsel is rejected: ' . $info->getCdekNumber(), 'e');
			}

			return $status;
		}

		private function checkIfUnexistent($message){
			$status =  strpos($message, 'Order not found by cdek_number') !== false;

			if ($status){				
				echoLine('[Cdek::checkIfWaiting] Parsel is unexistent!', 'e');
			}

			return $status;
		}

		public function trackMultiCodes($tracking_codes){
			$result = [];			

			if (empty($tracking_codes)){
				return $result;
			}

			foreach ($tracking_codes as $tracking_code){
				$code = [];

				echoLine('[Cdek::checkIfWaiting] Starting parcel: ' . $tracking_code);

				try{					
					if ($info = $this->CdekClient->getOrderInfoByCdekNumber($tracking_code)){
							$code['taken'] 				= $this->checkIfTaken($info);
							$code['waiting'] 			= $this->checkIfWaiting($info);
							$code['rejected'] 			= $this->checkIfRejected($info);
							$code['tracking_status'] 	= $this->getTrackingStatus($info);
							$code['tracking_data'] 		= $this->prepareTrackingStatuses($info);

							$result[$tracking_code] = $code;
					}
				}  catch (CdekV2RequestException  $e){
					echoLine('[Cdek::trackMultiCodes] ' . $tracking_code . ' ' .  $e->getMessage(), 'e');					
					
					if ($this->checkIfUnexistent($e->getMessage())){								
						$code['taken'] 				= false;
						$code['waiting'] 			= false;
						$code['rejected'] 			= true;
						$code['tracking_status']	= 'UNEXSISTENT';
						$code['tracking_data']		= ['error' => $e->getMessage()];

						$result[$tracking_code] = $code;
					}
				} 
			}

			return $result;
		}

		public function trackAndFormat($tracking_code, $unused = ''){
			try{

				if ($info = $this->CdekClient->getOrderInfoByCdekNumber($tracking_code)){
					return $this->prettyFormatTrackingInfo($info);
				}

			}  catch (CdekV2RequestException  $e){
				echo $e->getMessage();					
				return false;
			} 

			return false;
		}

		public function prettyFormatTrackingInfo($info){
			$status = '';

			foreach ($info->getStatuses() as $cdekStatus){
				$status .= '<b>' . $cdekStatus->getName() . '</b>';
				break;
			}

			if (!$info->getDeliveryProblem()){
				$status .= '&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:green"><i class="fa fa-check"></i> проблем с доставкой нет</b>';
			}

			$status .= '<br />';
			$status .= '<br />';
			$status .= "<table class='list'>";

			foreach ($info->getStatuses() as $cdekStatus){
				$status .= '<tr>';
				$status .= '<td class="left">';
				$status .= '<i class="fa fa-check"></i>';
				$status .= '</td>';
				$status .= '<td class="left">';
				$status .= $cdekStatus->getCode();
				$status .= '</td>';
				$status .= '<td class="left">';
				$status .= $cdekStatus->getName();
				$status .= '</td>';
				$status .= '<td class="left">';
				$status .= $cdekStatus->getCity();
				$status .= '</td>';
				$status .= '<td class="left">';
				$status .= date('d.m.Y H:i', strtotime($cdekStatus->getDateTime()));
				$status .= '</td>';
				$status .= '</tr>';
			}

			$status .= '</table>';


			return $status;	
		}

		public function getDeliveryTerms($city_code){
			$result = [];

			try{
				$query = $this->db->query("SELECT deliveryPeriodMin, deliveryPeriodMax, min_WW, min_WD FROM cdek_cities WHERE code = '" . (int)$city_code . "'");

				if ($query->num_rows && $query->row['deliveryPeriodMin'] && $query->row['deliveryPeriodMax'] && $query->row['min_WW'] && $query->row['min_WD']){
					$result = [
						'success' 			=> true,
						'min_WD'  			=> $query->row['min_WD'],
						'min_WW'  			=> $query->row['min_WW'],
						'deliveryPeriodMin' => $query->row['deliveryPeriodMin'],
						'deliveryPeriodMax' => $query->row['deliveryPeriodMax'],
					];				
				}

				if (!$result || mt_rand(0,10) == 5){
					$tariff_WW = (new Tariff())->setTariffCode($this->config->get('config_cdek_api_default_tariff_warehouse'))->setCityCodes($this->config->get('config_cdek_api_city_sender_id'), (int)$city_code)->setPackageWeight(500); 
					$tariff_WD = (new Tariff())->setTariffCode($this->config->get('config_cdek_api_default_tariff_doors'))->setCityCodes($this->config->get('config_cdek_api_city_sender_id'), (int)$city_code)->setPackageWeight(500);

					$tariff_WW_info = $this->CdekClient->calculateTariff($tariff_WW);
					$tariff_WD_info = $this->CdekClient->calculateTariff($tariff_WD);

					if ($tariff_WW_info && $tariff_WD_info){
						$result = [
							'success' 			=> true,
							'min_WD'  			=> $tariff_WD_info->getDeliverySum(),
							'min_WW'  			=> $tariff_WW_info->getDeliverySum(),
							'deliveryPeriodMin' => $tariff_WW_info->getPeriodMin(),
							'deliveryPeriodMax' => $tariff_WW_info->getPeriodMax(),
						];

						$this->db->query("UPDATE cdek_cities SET 
							min_WD 				= '" . (float)$tariff_WD_info->getDeliverySum() . "', 
							min_WW 				= '" . (float)$tariff_WW_info->getDeliverySum() . "',
							deliveryPeriodMin 	= '" . (int)$tariff_WW_info->getPeriodMin() . "', 
							deliveryPeriodMax 	= '" . (int)$tariff_WW_info->getPeriodMax() . "'
							WHERE code = '" . (int)$city_code . "'");
					}
				}

			} catch (\CdekV2RequestException  $e){						
					return ['success' => false];
			}

			return $result;
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
			$request = (new Location())->setCountryCodes('RU')->setSize(5);
			$response = $this->CdekClient->getRegions($request);

			if (!empty($response)){
				foreach ($response as $region){
					if ($region->getRegionCode() && $region->getCountry()){
						return mb_strtolower($region->getCountry());
					} else {
						return false;
					}
				}
			}

			return false;
		}
		
		public function updateReferences(){					
			$this->updateZones('RU')->updateZones('KZ')->updateZones('BY')->updateCities()->updateDeliveryPoints()->updateBeltWayHits();
		}

		public function updateReferencesOneTime(){					
			$this->updateBeltWayHits();
		}
		
		public function updateZones($countryCode){						
			echoLine('[CD] Страница 0');		
			$request = (new Location());
			$request->setSize(1000);				
			$request->setCountryCodes($countryCode);
			$response = $this->CdekClient->getRegions($request);		
			
			foreach ($response as $region){
				if ($region->getRegionCode()){			
					echoLine('[CD] Регион ' . $region->getRegion());

					$this->db->query("INSERT INTO cdek_zones SET 						
						country_id 				= '" . $this->db->escape($this->countryMapping[$countryCode]) . "',
						country_code 			= '" . $this->db->escape($region->getCountryCode()) . "',
						country 				= '" . $this->db->escape($region->getCountry()) . "',
						region 					= '" . $this->db->escape($region->getRegion()) . "',						
						region_code 			= '" . $this->db->escape($region->getRegionCode()) . "'					
						ON DUPLICATE KEY UPDATE
						country_id 				= '" . $this->db->escape($this->countryMapping[$countryCode]) . "',
						country_code 			= '" . $this->db->escape($region->getCountryCode()) . "',
						country 				= '" . $this->db->escape($region->getCountry()) . "',
						region 					= '" . $this->db->escape($region->getRegion()) . "',						
						region_code 			= '" . $this->db->escape($region->getRegionCode()) . "'");				
				}
			}

			
			return $this;
		}
				
		public function updateCities(){			
			$request = (new Location());			
			$query = $this->db->query("SELECT * FROM cdek_zones WHERE 1");
			
			foreach ($query->rows as $row){				
				echoLine('[CD] Страна ' . $row['country'] . ', регион ' . $row['region'], 'i');
				
				$page = 0;
				$emptyResponse = false;
				try {
					$request->setCountryCodes($row['country_code']);
					$request->setRegionCode($row['region_code']);

					echoLine('[CD] Страница ' . $page);
					$request->setSize(1000);
					$request->setPage($page);

					$response = $this->CdekClient->getCities($request);

					$emptyResponse = true;
					foreach ($response as $city){
						if ($city->getCode()){
							$emptyResponse = false;
						}

						try{
							$region = $city->getRegion();
						} catch (TypeError  $e){
							echoLine($e->getMessage());
							$region = $row['region'];
						}

						echoLine('[CD]	 Город ' . $city->getCity());

						$this->db->query("INSERT INTO cdek_cities SET 						
							code 				= '" . $this->db->escape($city->getCode()) . "',
							city 				= '" . $this->db->escape($city->getCity()) . "',
							fias_guid 			= '" . $this->db->escape($city->getFiasGuid()) . "',
							kladr_code 			= '" . $this->db->escape($city->getKladrCode()) . "',
							country_code 		= '" . $this->db->escape($city->getCountryCode()) . "',
							country 			= '" . $this->db->escape($row['country']) . "',
							country_id 			= '" . $this->db->escape($this->countryMapping[$row['country_code']]) . "',
							region 				= '" . $this->db->escape($region) . "',
							region_code 		= '" . $this->db->escape($row['region_code']) . "',												
							sub_region 			= '" . $this->db->escape($city->getSubRegion()) . "',
							postal_codes 		= '" . $this->db->escape($city->getPostalCode()) . "',
							longitude 			= '" . $this->db->escape($city->getLongitude()) . "',
							latitude 			= '" . $this->db->escape($city->getLatitude()) . "',
							time_zone 			= '" . $this->db->escape($city->getTimeZone()) . "'						
							ON DUPLICATE KEY UPDATE
							city 				= '" . $this->db->escape($city->getCity()) . "',
							fias_guid 			= '" . $this->db->escape($city->getFiasGuid()) . "',
							kladr_code 			= '" . $this->db->escape($city->getKladrCode()) . "',
							country_code 		= '" . $this->db->escape($city->getCountryCode()) . "',
							country 			= '" . $this->db->escape($row['country']) . "',
							country_id 			= '" . $this->db->escape($this->countryMapping[$row['country_code']]) . "',
							region 				= '" . $this->db->escape($region) . "',
							region_code 		= '" . $this->db->escape($row['region_code']) . "',												
							sub_region 			= '" . $this->db->escape($city->getSubRegion()) . "',
							postal_codes 		= '" . $this->db->escape($city->getPostalCode()) . "',
							longitude 			= '" . $this->db->escape($city->getLongitude()) . "',
							latitude 			= '" . $this->db->escape($city->getLatitude()) . "',
							time_zone 			= '" . $this->db->escape($city->getTimeZone()) . "'");												
					}

					$page++;
				} catch (\CdekV2RequestException  $e){
					echoLine('Страница ' . $page . ' '. $e->getMessage(), 'e');						
					continue;
				}
			}	
			
			return $this;			
		}			
						
		public function updateDeliveryPoints(){
			
			$request 	= (new DeliveryPoints());			
			$query 		= $this->db->query("SELECT * FROM cdek_cities WHERE parsed = 0 ORDER BY city_id ASC");
			
			foreach ($query->rows as $row){
				
				try{
					echoLine('[CD] Страна ' . $row['country'] . ', регион ' . $row['region'] . ', город ' . $row['city']);

					$request->setCityCode($row['code']);			
					$response = $this->CdekClient->getDeliveryPoints($request);

					foreach ($response as $pvz){
						echoLine('[CD]	 ПВЗ ' . $pvz->getName());

						$OfficeImages = array();
						if (!is_null($pvz->getOfficeImageList())){
							foreach ($pvz->getOfficeImageList() as $image) {
								$OfficeImages[] = $image->getUrl();
							}
						}

						$WorkTimes = array();
						if (!is_null($pvz->getWorkTimeList())){
							foreach ($pvz->getWorkTimeList() as $worktime) {
								$WorkTimes[$worktime->getDay()] = $worktime->getTime();
							}
						}


						$sql = "INSERT INTO cdek_deliverypoints SET 
						code 					= '" . $this->db->escape($pvz->getCode()) . "',
						name 					= '" . $this->db->escape($pvz->getName()) . "',
						country_code 			= '" . $this->db->escape($row['country_code']) . "',
						region_code 			= '" . $this->db->escape($pvz->getLocation()->getRegionCode()) . "',
						region 					= '" . $this->db->escape($pvz->getLocation()->getRegion()) . "',
						city_code 				= '" . $this->db->escape($pvz->getLocation()->getCityCode()) . "',
						city 					= '" . $this->db->escape($pvz->getLocation()->getCity()) . "',
						postal_сode 			= '" . $this->db->escape($pvz->getLocation()->getPostalCode()) . "',
						longitude 				= '" . $this->db->escape($pvz->getLocation()->getLongitude()) . "',
						latitude 				= '" . $this->db->escape($pvz->getLocation()->getLatitude()) . "',												
						address 				= '" . $this->db->escape($pvz->getLocation()->getAddress()) . "',
						address_full 			= '" . $this->db->escape($pvz->getLocation()->getAddressFull()) . "',
						address_comment 		= '" . $this->db->escape($pvz->getAddressComment()) . "',
						nearest_station 		= '" . $this->db->escape($pvz->getNearestStation()) . "',
						nearest_metro_station 	= '" . $this->db->escape($pvz->getNearestMetroStation()) . "',
						work_time 				= '" . $this->db->escape(json_encode($WorkTimes)) . "',
						phones 					= '" . $this->db->escape(json_encode($pvz->getPhones())) . "',
						email 					= '" . $this->db->escape($pvz->getEmail()) . "',
						note 					= '" . $this->db->escape($pvz->getNote()) . "',
						type 					= '" . $this->db->escape($pvz->getType()) . "',
						owner_сode 				= '" . (int)($pvz->getOwnerCode()) . "',
						take_only 				= '" . (int)($pvz->getTakeOnly()) . "',
						is_handout 				= '" . (int)($pvz->getIsHandout()) . "',
						is_reception 			= '" . (int)($pvz->getIsReception()) . "',
						is_dressing_room 		= '" . (int)($pvz->getIsDressingRoom()) . "',
						have_cashless 			= '" . (int)($pvz->getHaveCashless()) . "',
						have_cash 				= '" . (int)($pvz->getHaveCash()) . "',
						allowed_cod 			= '" . (int)($pvz->getAllowedCod()) . "',
						site 					= '" . $this->db->escape($pvz->getSite()) . "',
						office_image_list 		= '" . $this->db->escape(json_encode($OfficeImages)) . "',					
						work_time_list 			= '" . $this->db->escape(json_encode($WorkTimes)) . "',									
						weight_min  			= '" . $pvz->getWeightMin() . "',
						weight_max 				= '" . $pvz->getWeightMax() . "'
						ON DUPLICATE KEY UPDATE
						name 					= '" . $this->db->escape($pvz->getName()) . "',
						country_code 			= '" . $this->db->escape($row['country_code']) . "',
						region_code 			= '" . $this->db->escape($pvz->getLocation()->getRegionCode()) . "',
						region 					= '" . $this->db->escape($pvz->getLocation()->getRegion()) . "',
						city_code 				= '" . $this->db->escape($pvz->getLocation()->getCityCode()) . "',
						city 					= '" . $this->db->escape($pvz->getLocation()->getCity()) . "',
						postal_сode 			= '" . $this->db->escape($pvz->getLocation()->getPostalCode()) . "',
						longitude 				= '" . $this->db->escape($pvz->getLocation()->getLongitude()) . "',
						latitude 				= '" . $this->db->escape($pvz->getLocation()->getLatitude()) . "',												
						address 				= '" . $this->db->escape($pvz->getLocation()->getAddress()) . "',
						address_full 			= '" . $this->db->escape($pvz->getLocation()->getAddressFull()) . "',
						address_comment 		= '" . $this->db->escape($pvz->getAddressComment()) . "',
						nearest_station 		= '" . $this->db->escape($pvz->getNearestStation()) . "',
						nearest_metro_station 	= '" . $this->db->escape($pvz->getNearestMetroStation()) . "',
						work_time 				= '" . $this->db->escape(json_encode($WorkTimes)) . "',
						phones 					= '" . $this->db->escape(json_encode($pvz->getPhones())) . "',
						email 					= '" . $this->db->escape($pvz->getEmail()) . "',
						note 					= '" . $this->db->escape($pvz->getNote()) . "',
						type 					= '" . $this->db->escape($pvz->getType()) . "',
						owner_сode 				= '" . (int)($pvz->getOwnerCode()) . "',
						take_only 				= '" . (int)($pvz->getTakeOnly()) . "',
						is_handout 				= '" . (int)($pvz->getIsHandout()) . "',
						is_reception 			= '" . (int)($pvz->getIsReception()) . "',
						is_dressing_room 		= '" . (int)($pvz->getIsDressingRoom()) . "',
						have_cashless 			= '" . (int)($pvz->getHaveCashless()) . "',
						have_cash 				= '" . (int)($pvz->getHaveCash()) . "',
						allowed_cod 			= '" . (int)($pvz->getAllowedCod()) . "',
						site 					= '" . $this->db->escape($pvz->getSite()) . "',
						office_image_list 		= '" . $this->db->escape(json_encode($OfficeImages)) . "',					
						work_time_list 			= '" . $this->db->escape(json_encode($WorkTimes)) . "',									
						weight_min  			= '" . $pvz->getWeightMin() . "',
						weight_max 				= '" . $pvz->getWeightMax() . "'";

						$this->db->query($sql);	
					}

				}  catch (CdekV2RequestException  $e){
					echoLine($e->getMessage(), 'e');
					$this->db->query("UPDATE cdek_cities SET parsed = 1 WHERE city_id = '" . (int)$row['city_id'] . "'");
					continue;
				} 

				$this->db->query("UPDATE cdek_cities SET parsed = 1 WHERE city_id = '" . (int)$row['city_id'] . "'");
				
			}
			
			$this->db->query("UPDATE cdek_cities SET WarehouseCount = (SELECT COUNT(cdek_deliverypoints.deliverypoint_id) FROM cdek_deliverypoints WHERE cdek_deliverypoints.city_code = cdek_cities.code) ");
			$this->db->query("UPDATE cdek_cities SET parsed = 0");
			
			return $this;
		}			
		
	}										
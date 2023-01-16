<?php
	
	class NovaPoshta {
		private $db;
		private $config;
		private $registry;
		private $host = 'https://api.novaposhta.ua/v2.0/json/';
		
		
		private $apiKey = null;
		private $defaultCityGuid = null;
		
		
		public function __construct($registry) {
			
				$this->registry = $registry;
				$this->config 	= $this->registry->get('config');
				$this->db 		= $this->registry->get('db');
				
				$this->apiKey = $this->config->get('config_novaposhta_api_key');
				$this->defaultCityGuid = $this->config->get('config_novaposhta_default_city_guid');
		}
		
		private function validateResult($result){			
			if (!$result || mb_strlen($result) < 5 || !($json = json_decode($result, true)) || empty($json['success']) || !$json['success']){				
				throw new Exception('Erroneous response from NovaPoshta API, possibly wrong invoice number! Or problems with the API ' . serialize($result));
			}
			
			return $json;			
		}		
		
		private function doRequest($data){			
			$json = array(			
			'apiKey' 			=> $this->apiKey			
			);
			
			foreach ($data as $key => $value){
				$json[$key] = $value;
			}

			echoLine('[NP] Запрос ' . $data['modelName'] . '/' . $data['calledMethod']);
			
			$curl = curl_init($this->host);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($json));

			$headers[] = 'Host: api.novaposhta.ua';
			$headers[] = 'Origin: https://tracking.novaposhta.ua';
			$headers[] = 'Referer: https://tracking.novaposhta.ua/';
			$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0';
			$headers[] = 'Accept: application/json;q=0.9,*/*;q=0.8';
			$headers[] = 'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3';
			$headers[] = 'Encoding: gzip, deflate, br';
			$headers[] = 'Cookie: forceDesktop=deleted; expires=Thu, 01-Jan-1970 00:00:01 GMT; Max-Age=0; path=/';
			$headers[] = 'Connection: keep-alive';
			$headers[] = 'Cache-Control: max-age=0';
			$headers[] = 'Content-Type: application/json; charset=utf-8';
			$headers[] = 'Content-Length: '. mb_strlen(json_encode($json));

			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 100);	
			curl_setopt($curl, CURLOPT_TIMEOUT, 500);			
			
			$result = curl_exec($curl);			
			curl_close($curl);
			
			try{				
				$result = $this->validateResult($result);
				return $result;
				
				} catch (Exception $e){
				
				echoLine('ОШИБКА ОБРАБОТКИ');
				echoLine($e->getMessage());
				return false;
			}	
		}


		public function trackAndReturn($tracking_code, $phone = ''){

		}

		public function trackAndFormat($tracking_code, $phone = ''){
			$data = [			
				"language"  		=> "uk",
				"system" 			=> "Tracking", 	
				"modelName"			=> "InternetDocument",
				"calledMethod"  	=> "getDocumentsEWMovement",
				"methodProperties" 	=> [ 
					"NewFormat" => 1,
					"Number"   	=> $tracking_code
				]
			];

			if ($info = $this->doRequest($data)){
				return $this->prettyFormatTrackingInfo($info);
			}

			return false;
		}

		public function prettyFormatTrackingInfo($json){
			$status = '<b>' . $json['data'][0]['status']['description'] . '</b>, ' . $json['data'][0]['status']['addressDescription'] . '';
			$status .= '<br />';
			$status .= '<br />';
			$status .= "<table class='list'>";

			if (!empty($json['data'][0]['movement']['passed'])){
				$status .= '<tr><td colspan="4" style="padding:5px 0px;"><b>Виконано:</b></td></tr>';
			}

			foreach ($json['data'][0]['movement']['passed'] as $movement){
				if (!empty($movement['Date'])){
					$time = $movement['Date'];
				}
				
				if (!empty($movement['ArrivalTime'])){
					$time = $movement['ArrivalTime'];
				}

				if (!empty($movement['EventDescription'])){
					$status .= '<tr>';
					$status .= '<td class="left">';
					$status .= '<i class="fa fa-check"></i>';
					$status .= '</td>';
					$status .= '<td class="left">';
					$status .= $movement['EventDescription'];
					$status .= '</td>';
					$status .= '<td class="left">';
					$status .= $movement['WarehouseDescription'];
					$status .= '</td>';
					$status .= '<td class="left">';
					$status .= date('d.m.Y H:i', strtotime($time));
					$status .= '</td>';
					$status .= '</tr>';
				}
			}

			if (!empty($json['data'][0]['movement']['now'])){
				$status .= '</table><table class="list">';
				$status .= '<tr><td colspan="4" style="padding:5px 0px;"><b>Поточний стан:</b></td></tr>';
			}

			foreach ($json['data'][0]['movement']['now'] as $movement){
				if (!empty($movement['Date'])){
					$time = $movement['Date'];
				}
				
				if (!empty($movement['ArrivalTime'])){
					$time = $movement['ArrivalTime'];
				}

				if (!empty($movement['EventDescription'])){
					$status .= '<tr>';
					$status .= '<td class="left">';
					$status .= '<i class="fa fa-check-circle"></i>';
					$status .= '</td>';
					$status .= '<td class="left"><b>';
					$status .= $movement['EventDescription'];
					$status .= '</b></td>';
					$status .= '<td class="left"><b>';
					$status .= $movement['WarehouseDescription'];
					$status .= '</b></td>';
					$status .= '<td class="left"><b>';
					$status .= date('d.m.Y H:i', strtotime($time));
					$status .= '</b></td>';
					$status .= '</tr>';
				}
			}

			if (!empty($json['data'][0]['movement']['future'])){
				$status .= '</table><table class="list">';
				$status .= '<tr><td colspan="4" style="padding:5px 0px;"><b>Доктор Стрендж передбачує:</b></td></tr>';
			}

			foreach ($json['data'][0]['movement']['future'] as $movement){
				if (!empty($movement['Date'])){
					$time = $movement['Date'];
				}
				
				if (!empty($movement['ArrivalTime'])){
					$time = $movement['ArrivalTime'];
				}

				if (!empty($movement['EventDescription'])){
					$status .= '<tr>';
					$status .= '<td class="left">';
					$status .= '<i class="fa fa-spinner"></i>';
					$status .= '</td>';
					$status .= '<td class="left">';
					$status .= $movement['EventDescription'];
					$status .= '</td>';
					$status .= '<td class="left">';
					$status .= $movement['WarehouseDescription'];
					$status .= '</td>';
					$status .= '<td class="left">';
					$status .= date('d.m.Y H:i', strtotime($time));
					$status .= '</td>';
					$status .= '</tr>';
				}
			}

			$status .= '</table>';


			return $status;	
		}
		
		public function getDeliveryPrice(){
		}
		
		public function getDeliveryDate($city, $date){
			$data = array(
			'modelName' 		=> 'InternetDocument',
			'calledMethod' 		=> 'getDocumentDeliveryDate',	
			'methodProperties'  => array(
			'DateTime' 		=> date('d.m.Y', strtotime($date)),
			'ServiceType'	=> 'WarehouseWarehouse',
			'CitySender' 	=> $this->defaultCityGuid,
			'CityRecipient' => $city
			)
			);
						
			$result = $this->doRequest($data);

			if (!empty($result['success']) && $result['success']){
				$datetime1 = date_create(date('d.m.Y'));
				$datetime2 = date_create($result['data'][0]['DeliveryDate']['date']);
				
				$interval = date_diff($datetime1, $datetime2);
				if ($interval->days == 0){
					return 1;
				} else {
					return $interval->days;				
				}
			}
			
			return false;			
		}
		
		public function checkStatus(){
			$data = array(
			'modelName' 		=> 'Address',
			'calledMethod' 		=> 'getAreas',		
			);
						
			$result = $this->doRequest($data);


			if (!empty($result['data'])){
				foreach ($result['data'] as $line){					
					if (!empty($line['DescriptionRu'])){
						if ($line['DescriptionRu'] != 'АРК'){
							return $line['DescriptionRu'];
						}
					}
				}
			}

			return false;
		}
		
		public function updateZones(){
			$data = array(
				'modelName' 		=> 'Address',
				'calledMethod' 		=> 'getAreas',		
			);
			
			
			$result = $this->doRequest($data);
			
			foreach ($result['data'] as $line){

				echoLine('[NP] Область ' . $line['Description']);

				$this->db->query("INSERT INTO novaposhta_zones SET 
					Ref = '" . $this->db->escape($line['Ref']) . "',
					Description = '" . $this->db->escape($line['Description']) . "',
					DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
					AreasCenter = '" . $this->db->escape($line['AreasCenter']) . "'
					ON DUPLICATE KEY UPDATE
					Description = '" . $this->db->escape($line['Description']) . "',
					DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
					AreasCenter = '" . $this->db->escape($line['AreasCenter']) . "'");
			}

			return $this;
		}
		
		public function updateCities(){
			$data = array(
			'modelName' 		=> 'AddressGeneral',
			'calledMethod' 		=> 'getSettlements',		
			);
			
			$result = $this->doRequest($data);
			$totalCount = (int)$result['info']['totalCount'];
			$maxPage = ceil($totalCount / 150);
			
			for ($page = 1; $page <= ($maxPage + 1); $page++){
				
				echoLine('[NP] Страница ' . $page . '/' . $maxPage);
				
				$data['methodProperties']['Page'] = $page;
				
				$result = $this->doRequest($data);
				
				
				
				foreach ($result['data'] as $line){
					
					echoLine('[NP] Город ' . $line['Description']);
					
					$this->db->query("INSERT INTO novaposhta_cities SET 
					Ref = '" . $this->db->escape($line['Ref']) . "',
					Description = '" . $this->db->escape($line['Description']) . "',
					DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
					Area = '" . $this->db->escape($line['Area']) . "',
					SettlementType = '" . $this->db->escape($line['SettlementType']) . "',
					SettlementTypeDescription = '" . $this->db->escape($line['SettlementTypeDescription']) . "',
					SettlementTypeDescriptionRu = '" . $this->db->escape($line['SettlementTypeDescriptionRu']) . "',
					RegionsDescription  = '" . $this->db->escape($line['RegionsDescription']) . "',
					RegionsDescriptionRu = '" . $this->db->escape($line['RegionsDescriptionRu']) . "',
					AreaDescription = '" . $this->db->escape($line['AreaDescription']) . "',
					AreaDescriptionRu = '" . $this->db->escape($line['AreaDescriptionRu']) . "',
					Index1 = '" . $this->db->escape($line['Index1']) . "',
					Index2 = '" . $this->db->escape($line['Index2']) . "',
					Delivery1 = '" . (int)$line['Delivery1'] . "',
					Delivery2 = '" . (int)$line['Delivery2'] . "',
					Delivery3 = '" . (int)$line['Delivery3'] . "',
					Delivery4 = '" . (int)$line['Delivery4'] . "',
					Delivery5 = '" . (int)$line['Delivery5'] . "',
					Delivery6 = '" . (int)$line['Delivery6'] . "',
					Delivery7 = '" . (int)$line['Delivery7'] . "',
					Latitude = '" . $this->db->escape($line['Latitude']) . "',
					Longitude = '" . $this->db->escape($line['Longitude']) . "',
					SpecialCashCheck = '" . (int)$line['SpecialCashCheck'] . "',
					Warehouse = '" . (int)$line['Warehouse'] . "'
					ON DUPLICATE KEY UPDATE
					Description = '" . $this->db->escape($line['Description']) . "',
					DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
					Area = '" . $this->db->escape($line['Area']) . "',
					SettlementType = '" . $this->db->escape($line['SettlementType']) . "',
					SettlementTypeDescription = '" . $this->db->escape($line['SettlementTypeDescription']) . "',
					SettlementTypeDescriptionRu = '" . $this->db->escape($line['SettlementTypeDescriptionRu']) . "',
					RegionsDescription  = '" . $this->db->escape($line['RegionsDescription']) . "',
					RegionsDescriptionRu = '" . $this->db->escape($line['RegionsDescriptionRu']) . "',
					AreaDescription = '" . $this->db->escape($line['AreaDescription']) . "',
					AreaDescriptionRu = '" . $this->db->escape($line['AreaDescriptionRu']) . "',
					Index1 = '" . $this->db->escape($line['Index1']) . "',
					Index2 = '" . $this->db->escape($line['Index2']) . "',
					Delivery1 = '" . (int)$line['Delivery1'] . "',
					Delivery2 = '" . (int)$line['Delivery2'] . "',
					Delivery3 = '" . (int)$line['Delivery3'] . "',
					Delivery4 = '" . (int)$line['Delivery4'] . "',
					Delivery5 = '" . (int)$line['Delivery5'] . "',
					Delivery6 = '" . (int)$line['Delivery6'] . "',
					Delivery7 = '" . (int)$line['Delivery7'] . "',
					Latitude = '" . $this->db->escape($line['Latitude']) . "',
					Longitude = '" . $this->db->escape($line['Longitude']) . "',
					SpecialCashCheck = '" . (int)$line['SpecialCashCheck'] . "',
					Warehouse = '" . (int)$line['Warehouse'] . "'");
				}
				
				
			}
			return $this;
		}
		
		public function updateCitiesWW(){
			$data = array(
			'modelName' 		=> 'Address',
			'calledMethod' 		=> 'getCities',		
			);
			
			$result = $this->doRequest($data);
			$totalCount = (int)$result['info']['totalCount'];
			
			foreach ($result['data'] as $line){
				
				echoLine('[NP] Город ' . $line['Description']);
				
				$this->db->query("INSERT INTO novaposhta_cities_ww SET 
				Ref = '" . $this->db->escape($line['Ref']) . "',
				Description = '" . $this->db->escape($line['Description']) . "',
				DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
				Area = '" . $this->db->escape($line['Area']) . "',									
				Delivery1 = '" . (int)$line['Delivery1'] . "',
				Delivery2 = '" . (int)$line['Delivery2'] . "',
				Delivery3 = '" . (int)$line['Delivery3'] . "',
				Delivery4 = '" . (int)$line['Delivery4'] . "',
				Delivery5 = '" . (int)$line['Delivery5'] . "',
				Delivery6 = '" . (int)$line['Delivery6'] . "',
				Delivery7 = '" . (int)$line['Delivery7'] . "',
				SettlementType = '" . $this->db->escape($line['SettlementType']) . "',
				SettlementTypeDescription = '" . $this->db->escape($line['SettlementTypeDescription']) . "',
				SettlementTypeDescriptionRu = '" . $this->db->escape($line['SettlementTypeDescriptionRu']) . "'
				ON DUPLICATE KEY UPDATE
				Description = '" . $this->db->escape($line['Description']) . "',
				DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
				Area = '" . $this->db->escape($line['Area']) . "',										
				Delivery1 = '" . (int)$line['Delivery1'] . "',
				Delivery2 = '" . (int)$line['Delivery2'] . "',
				Delivery3 = '" . (int)$line['Delivery3'] . "',
				Delivery4 = '" . (int)$line['Delivery4'] . "',
				Delivery5 = '" . (int)$line['Delivery5'] . "',
				Delivery6 = '" . (int)$line['Delivery6'] . "',
				Delivery7 = '" . (int)$line['Delivery7'] . "',
				SettlementType = '" . $this->db->escape($line['SettlementType']) . "',
				SettlementTypeDescription = '" . $this->db->escape($line['SettlementTypeDescription']) . "',
				SettlementTypeDescriptionRu = '" . $this->db->escape($line['SettlementTypeDescriptionRu']) . "'");			
			}
			
			return $this;
		}
				
		public function updateWareHouses(){
			
			$data = array(
			'modelName' 		=> 'Address',
			'calledMethod' 		=> 'getWarehouseTypes',		
			);
			
			$result = $this->doRequest($data);
			
			$typeOfWarehouse = array();
			foreach ($result['data'] as $line){
				$typeOfWarehouse[$line['Ref']] = array(
				'TypeOfWarehouse' => $line['Description'],
				'TypeOfWarehouseRu' => $line['DescriptionRu']
				);
			}
			
			$data = array(
			'modelName' 		=> 'Address',
			'calledMethod' 		=> 'getWarehouses',		
			);
			
			$result = $this->doRequest($data);
			$totalCount = (int)$result['info']['totalCount'];
			$maxPage = ceil($totalCount / 400);
			
			for ($page = 1; $page <= ($maxPage + 1); $page++){
				
				echoLine('[NP] Страница ' . $page . '/' . $maxPage);
				$data['methodProperties']['Page'] = $page;
				
				$result = $this->doRequest($data);
				
				foreach ($result['data'] as $line){
					
					
					echoLine('[NP] 	Отделение ' . $line['Description']);
					
					$this->db->query("INSERT INTO novaposhta_warehouses SET 
					Ref = '" . $this->db->escape($line['Ref']) . "',
					SiteKey = '" . (int)$line['SiteKey'] . "',
					Number = '" . (int)$line['Number'] . "',
					TotalMaxWeightAllowed = '" . (int)$line['TotalMaxWeightAllowed'] . "',
					PlaceMaxWeightAllowed = '" . (int)$line['PlaceMaxWeightAllowed'] . "',
					Description = '" . $this->db->escape($line['Description']) . "',
					DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
					TypeOfWarehouseRef = '" . $this->db->escape($line['TypeOfWarehouse']) . "',
					TypeOfWarehouse = '" . $this->db->escape($typeOfWarehouse[$line['TypeOfWarehouse']]['TypeOfWarehouse']) . "',
					TypeOfWarehouseRu = '" . $this->db->escape($typeOfWarehouse[$line['TypeOfWarehouse']]['TypeOfWarehouseRu']) . "',
					CityRef = '" . $this->db->escape($line['CityRef']) . "',
					CityDescription = '" . $this->db->escape($line['CityDescription']) . "',
					CityDescriptionRu = '" . $this->db->escape($line['CityDescriptionRu']) . "',
					Longitude = '" . $this->db->escape($line['Longitude']) . "',
					Latitude = '" . $this->db->escape($line['Latitude']) . "',
					PostFinance = '" . (int)$line['PostFinance'] . "',
					POSTerminal = '" . (int)$line['POSTerminal'] . "',
					Reception = '" . json_encode($line['Reception']) . "',
					Delivery = '" . json_encode($line['Delivery']) . "',
					Schedule = '" . json_encode($line['Schedule']) . "'
					ON DUPLICATE KEY UPDATE
					SiteKey = '" . (int)$line['SiteKey'] . "',
					Number = '" . (int)$line['Number'] . "',
					TotalMaxWeightAllowed = '" . (int)$line['TotalMaxWeightAllowed'] . "',
					PlaceMaxWeightAllowed = '" . (int)$line['PlaceMaxWeightAllowed'] . "',
					Description = '" . $this->db->escape($line['Description']) . "',
					DescriptionRu = '" . $this->db->escape($line['DescriptionRu']) . "',
					TypeOfWarehouseRef = '" . $this->db->escape($line['TypeOfWarehouse']) . "',
					TypeOfWarehouse = '" . $this->db->escape($typeOfWarehouse[$line['TypeOfWarehouse']]['TypeOfWarehouse']) . "',
					TypeOfWarehouseRu = '" . $this->db->escape($typeOfWarehouse[$line['TypeOfWarehouse']]['TypeOfWarehouseRu']) . "',
					CityRef = '" . $this->db->escape($line['CityRef']) . "',
					CityDescription = '" . $this->db->escape($line['CityDescription']) . "',
					CityDescriptionRu = '" . $this->db->escape($line['CityDescriptionRu']) . "',
					Longitude = '" . $this->db->escape($line['Longitude']) . "',
					Latitude = '" . $this->db->escape($line['Latitude']) . "',
					PostFinance = '" . (int)$line['PostFinance'] . "',
					POSTerminal = '" . (int)$line['POSTerminal'] . "',
					Reception = '" . json_encode($line['Reception']) . "',
					Delivery = '" . json_encode($line['Delivery']) . "',
					Schedule = '" . json_encode($line['Schedule']) . "'");		
					
				}
			}
			
			//Обновление количества отделений
			$this->db->query("UPDATE novaposhta_cities_ww SET WarehouseCount = (SELECT COUNT(Ref) FROM novaposhta_warehouses WHERE CityRef = novaposhta_cities_ww.Ref)");
			
			return $this;			
		}
		
		public function getRealTimeStreets($CityRef){
			if (trim($CityRef)){
				
				$data = array(
				'modelName' 		=> 'Address',
				'calledMethod' 		=> 'getStreet',		
				);
				
				$data['methodProperties']['CityRef'] = $CityRef;
				
				$result = $this->doRequest($data);			
				$totalCount = $result['info']['totalCount'];
				
				if ($totalCount <= 500){
					foreach ($result['data'] as $line){	
						$this->db->query("INSERT INTO novaposhta_streets SET 
						Ref = '" . $this->db->escape($line['Ref']) . "',
						CityRef = '" . $this->db->escape($row['Ref']) . "',
						Description = '" . $this->db->escape($line['Description']) . "',				
						StreetsTypeRef  = '" . $this->db->escape($line['StreetsTypeRef']) . "',
						StreetsType = '" . $this->db->escape($line['StreetsType']) . "'
						ON DUPLICATE KEY UPDATE
						CityRef = '" . $this->db->escape($row['Ref']) . "',
						Description = '" . $this->db->escape($line['Description']) . "',				
						StreetsTypeRef  = '" . $this->db->escape($line['StreetsTypeRef']) . "',
						StreetsType = '" . $this->db->escape($line['StreetsType']) . "'");
					}
				}
			}
		}
		
		public function updateStreets(){
			
			$data = array(
			'modelName' 		=> 'Address',
			'calledMethod' 		=> 'getStreet',		
			);
			
			
			$query = $this->db->query("SELECT Ref, Description FROM novaposhta_cities_ww WHERE 1 ORDER BY WarehouseCount DESC");
			
			foreach ($query->rows as $row){
				
				$data['methodProperties']['CityRef'] = $row['Ref'];
				
				$result = $this->doRequest($data);
				$totalCount = $result['info']['totalCount'];
				echoLine('[NP] Город ' . $row['Description'] .': '. $totalCount);
				
				$maxPage = ceil($totalCount / 490);
				
				for ($page = 1; $page <= ($maxPage + 1); $page++){
					
					echoLine('[NP] Страница ' . $page . '/' . $maxPage);
					$data['methodProperties']['Page'] = $page;
					
					$result = $this->doRequest($data);
					
					foreach ($result['data'] as $line){
						
						echoLine('[NP] 	Улица ' . $line['Description']);
						
						$this->db->query("INSERT INTO novaposhta_streets SET 
						Ref = '" . $this->db->escape($line['Ref']) . "',
						CityRef = '" . $this->db->escape($row['Ref']) . "',
						Description = '" . $this->db->escape($line['Description']) . "',				
						StreetsTypeRef  = '" . $this->db->escape($line['StreetsTypeRef']) . "',
						StreetsType = '" . $this->db->escape($line['StreetsType']) . "'
						ON DUPLICATE KEY UPDATE
						CityRef = '" . $this->db->escape($row['Ref']) . "',
						Description = '" . $this->db->escape($line['Description']) . "',				
						StreetsTypeRef  = '" . $this->db->escape($line['StreetsTypeRef']) . "',
						StreetsType = '" . $this->db->escape($line['StreetsType']) . "'");		
						
					}
				}
			}
		}
	}																									
<?
	
	class CourierServices {
		private $ttn;
		private $_log;
		private $registry;
		private $config;
		private $db;
		
		private $services = array(
		'dostavkaplus.sh3' 	=> 'NewPost',
		'dostavkaplus.sh13' => 'NewPost',
		'dostavkaplus.sh4' 	=> 'InTime',
		'dostavkaplus.sh5' 	=> 'EMSRussia',
		'dostavkaplus.sh6' 	=> 'TKSDEK',
		'dostavkaplus.sh17' => 'TKSDEK',
		);
		
		private $_proxies = array(        
		'144.76.214.96:8076',
		'144.76.214.97:8077',
		'144.76.214.98:8078',
		'144.76.214.99:8079',
		'144.76.214.100:8090',
		'144.76.214.101:8089',
		'144.76.214.102:8082',
		);
		
		public function __construct($registry = false) {
			
			if ($registry){
				$this->registry = $registry;
				$this->config = $registry->get('config');
				$this->db = $registry->get('db');
			}
			
			$this->_log = new Log('courier_services_errors.txt');
		}
		
		private function xml2array ( $xmlObject, $out = array () )
		{
			foreach ( (array) $xmlObject as $index => $node )
			$out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
			
			return $out;
		}
		
		public function getInfo($ttn, $code, $do_echo = true) {
			
			$result = 'Не получилось получить информацию по накладной.';
			
			if ($ttn && $code){
				if (isset($this->services[$code])){								
					$this->ttn = trim(str_replace(' ', '', $ttn));
					$result = $this->{'get'.$this->services[$code]}();
				}			
			}
			
			if ($do_echo){
				echo $result;
				} else {
				return $result;
			}
			return false;
		}	
		
		function is_hex($hex) {
			// regex is for weenies
			$hex = strtolower(trim(ltrim($hex,"0")));
			if (empty($hex)) { $hex = 0; };
			$dec = hexdec($hex);
			return ($hex == dechex($dec));
		}
		
		function http_chunked_decode($chunk) {
			$pos = 0;
			$len = strlen($chunk);
			$dechunk = null;
			
			while(($pos < $len)
            && ($chunkLenHex = substr($chunk,$pos, ($newlineAt = strpos($chunk,"\n",$pos+1))-$pos)))
			{
				if (! $this->is_hex($chunkLenHex)) {
					trigger_error('Value is not properly chunk encoded', E_USER_WARNING);
					return $chunk;
				}
				
				$pos = $newlineAt + 1;
				$chunkLen = hexdec(rtrim($chunkLenHex,"\r\n"));
				$dechunk .= substr($chunk, $pos, $chunkLen);
				$pos = strpos($chunk, "\n", $pos + $chunkLen) + 1;
			}
			return $dechunk;
		}
		
		public function guessIfTaken($result, $code){
			
			if ($code == 'dostavkaplus.sh3' || $code == 'dostavkaplus.sh13'){
				//Новая Почта		
				return (mb_stripos($result, 'Посилка видана', 0, 'UTF-8') !== false) || (mb_stripos($result, 'Відправлення отримано', 0, 'UTF-8') !== false) || (mb_stripos($result, 'забрав відправлення', 0, 'UTF-8')  !== false);					
				} elseif ($code == 'dostavkaplus.sh4') {
				//ИнТайм
				return mb_stripos($result, 'груз выдан', 0, 'UTF-8');	
				
				} elseif ($code == 'dostavkaplus.sh5') {
				//ЕМС Россия
				return (mb_stripos($result, 'Вручено', 0, 'UTF-8') || (mb_stripos($result, 'Получено адресатом', 0, 'UTF-8')));
				
				} elseif ($code == 'dostavkaplus.sh6' ) {
				//ТК СДЭК		
				return (mb_stripos($result, 'current_status_id:4', 0, 'UTF-8'));
				return mb_stripos($result, 'Выдан', 0, 'UTF-8');
				
				} elseif ($code == 'dostavkaplus.sh6') {
				//ТК СДЭК		
				return (mb_stripos($result, 'current_status_id:4', 0, 'UTF-8'));
				
				} elseif ($code == 'dostavkaplus.sh9') {
				//VOZIM по бульбаляндии
				
			}
			
			return false;
			
			
		}
		
		public function guessIfTryReject($result, $code){
			
			if ($code == 'dostavkaplus.sh5') {
				
				return (mb_stripos($result, 'Неудачная попытка', 0, 'UTF-8'))?'Неудачное вручение!':false;
				
				} 	elseif ($code == 'dostavkaplus.sh3' || $code == 'dostavkaplus.sh13'){
				//Новая Почта		
				return (mb_stripos($result, 'відмовився', 0, 'UTF-8'))?'Отказ от посылки!':false;
				
				} elseif ($code == 'dostavkaplus.sh6'){
				//ТК СДЭК
				if (mb_stripos($result, 'current_status_id:4', 0, 'UTF-8')){
					return false;
				}
				
				if (mb_stripos($result, 'current_status_id:4', 0, 'UTF-8')){
					return false;
				}
				
				if (mb_stripos($result, 'current_status_id:2', 0, 'UTF-8')){
					return 'Накладная удалена из базы СДЭК';
				}
				
				if (mb_stripos($result, 'current_status_id:5', 0, 'UTF-8')){
					return 'Заказ не вручен, возврат';
				}
				
				if (mb_stripos($result, 'current_status_id:16', 0, 'UTF-8')){
					return ' Возвращен на склад отправителя';
				}
				
				if (mb_stripos($result, 'е вручен', 0, 'UTF-8')){
					return 'Посылка не вручена!';
				}
				
				return false;
			}
			
		}
		
		public function guessIfIncorrect($result, $code){		
			
			if (($code == 'dostavkaplus.sh11')) {
				
				return (mb_stripos($result, 'еверный номер', 0, 'UTF-8') || mb_stripos($result, 'грузы не найдены', 0, 'UTF-8'));
				
				} elseif ($code == 'dostavkaplus.sh6') {		
				
				return (mb_stripos($result, 'аказ не найден', 0, 'UTF-8'));
				
			}
			
		}
		
		
		private function getNewPost() {
			$ttn = str_replace(" ", "", $this->ttn);
			$url = "https://api.novaposhta.ua/v2.0/json/";
			
			$result = false;
			
			$payload =  '{"system":"Tracking","apiKey":"' . $this->config->get('config_novaposhta_api_key') . '","modelName":"InternetDocument","calledMethod":"getDocumentsEWMovement","language":"uk","methodProperties":{"Number":"'. $ttn .'"}}';
			
			if( $curl = curl_init() ) {
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
				
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
				
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				
				$result = curl_exec($curl);
				curl_close($curl);
				
				libxml_use_internal_errors(TRUE);
				
				if (!$result || mb_strlen($result) < 5 || !($json = json_decode($result, true)) || !$json['success']){
					return "Пустой ответ от НП, возможно неверный номер накладной! Либо проблемы с API.";
				}						
				
				/*	print('<pre>');
					print_r($json['data']);
					print('</pre>');
				*/
				
				$status = 'Статус: <b>' . $json['data'][0]['status']['description'] . '</b>, ' . $json['data'][0]['status']['addressDescription'] . '';
				$status .= '<br />';
				$status .= '<br />';
				$status .= "<table class='list'>";
				
				$json['data'][0]['movement'] = array_reverse($json['data'][0]['movement']);
				
				$status .= '<tr>';
				$status .= '<td class="left"><b>Подія</b></td>';
				$status .= '<td class="left"><b>Місто</b></td>';
				$status .= '<td class="left"><b>Час</b></td>';
				$status .= '</tr>';
				
				foreach ($json['data'][0]['movement'] as $movement){
					if (!empty($movement['MovementFact']['EventDescription'])){
						$status .= '<tr>';
						$status .= '<td class="left">';
						$status .= $movement['MovementFact']['EventDescription'];
						$status .= '</td>';
						$status .= '<td class="left">';
						$status .= $movement['MovementFact']['CityDescription'];
						$status .= '</td>';
						$status .= '<td class="left">';
						$status .= date('d.m.Y H:i', strtotime($movement['MovementFact']['Date']));
						$status .= '</td>';
						$status .= '</tr>';
					}
				}
				$status .= '</table>';
				
				
				return $status;
			}
			
			return false;
		}
		
		private function getInTime(){
			$url = "http://mytime.intime.ua/modules/global_functions.php?command=get_info_for_ttn&ttn=".$this->ttn;
			
			$result = file_get_contents($url);
			
			return $result;		
		}
		
		private function getEMSRussia(){
			$url = 'https://www.pochta.ru/tracking?p_p_id=trackingPortlet_WAR_portalportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=getList&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_pos=1&p_p_col_count=2&barcodeList='.$this->ttn.'&postmanAllowed=true&_=15251212';
			
			
			if( $curl = curl_init() ) {
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, false);
				
				$headers = array();
				$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
				$headers[] = 'Accept-Encoding: none';
				$headers[] = 'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3';
				$headers[] = 'Cache-Control: no-cache';
				$headers[] = 'Content-Type: application/json; charset=utf-8';
				$headers[] = 'Host: www.pochta.ru';
				
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				
				$result = curl_exec($curl);				
				curl_close($curl);
			}
			
			$result = json_decode($result, true);

			if (!isset($result['list'][0]['trackingItem']) || !isset($result['list'][0]['trackingItem']['commonStatus'])){
				return "Сбой разбора данных EMS";
				} else {
				$str = '<br />';
				$str .= $result['list'][0]['trackingItem']['commonStatus'];
				$str .= '<br />';
				
				$str .= "<table class='list'>";
				
				$_r = array_reverse($result['list'][0]['trackingItem']['trackingHistoryItemList']);
				
				foreach ($_r as $_track){
					$str .= '<tr>';
					$str .= '<td class="left">';
					$str .= $_track['humanStatus'];
					$str .= '</td>';
					$str .= '<td class="left">';
					$str .= $_track['cityName'] . ', ' . $_track['countryName'];
					$str .= '</td>';
					$str .= '<td class="left">';
					$str .= date('d.m.Y', strtotime($_track['date']));
					$str .= '</td>';
					$str .= '</tr>';
				}
				$str .= '</table>';
			}
			
			return $str;
		}
		
		private function getTKPEK($once = true){
			$url = 'http://pecom.ru/ajax/_status.php?code[]='.$this->ttn;
			$result_code = file_get_contents($url);
			
			$code = json_decode($result_code, true);
			$code = $code['0'];
			
			$url2 = 'http://pecom.ru/ajax/lazy_answer.php?type=status&id='.$code;
			
			$result = file_get_contents($url2);
			$result = json_decode($result, true);
			
			$status = (isset($result['status'])) ? $result['status'] : false;
			
			/*
				if ($status == 'not processed' || !$status) {
				return "Что-то пошло не так. Данные не получены.";
				} else {
				$result = $result['html'];
				
				$result = explode('fromsms', $result);
				$result = $result[0];
				
				$result = explode('modal-title', $result);
				$result = $result[1];
				$result = substr($result, 2);
				
				return $result;
				}
				
				
			*/
			
			if (!isset($result['html'])){
				//retry one more time
				if ($once){
					return $this->getTKPEK(false);				
					} else {
					$result = 'Не могу получить данные! Скорее всего неверный номер ТТН';
					$not_got = true;
				}
			}
			
			
			if (!$result){
				$result = 'Неверный номер ТТН!';
				} elseif (!isset($not_got) || !$not_got) {
				$tmp_result = $result;
				$result = $result['html'];				
				
				$result = explode('fromsms', $result);
				$result = $result[0];
				
				$result = explode('modal-title', $result);
				if (isset($result[1])) {
					$result = $result[1];
					$result = substr($result, 2);
					} else {												
					
					if (isset($tmp_result['html'])){
						$result = $tmp_result['html'];
						} else {
						$result = 'Неверный номер ТТН / Не могу получить инфо!';
					}
				}
				
			}
			
            return $result;
		}
		
		private function getTKSDEKFromSite(){
			
			
			
			
			
			
			
		}
		
		private function getTKSDEK(){
			require_once DIR_SYSTEM . '/cdek_integrator/class.app.php';
			app::registry()->create($this->registry);
			
			require_once DIR_SYSTEM . '/cdek_integrator/class.cdek_integrator.php';
			$cdek_api = new cdek_integrator();
			
			$settings = $this->config->get('cdek_integrator_setting');
			
			if (!empty($settings['account']) && !empty($settings['secure_password'])) {
				$cdek_api->setAuth($settings['account'], $settings['secure_password']);
			}
			
			$component = $cdek_api->loadComponent('order_info');
			
			$data = array(
			'show_history'	=> 1,
			'order'	=> array(array(
			'dispatch_number' => $this->ttn
			))
			);
			
			$component->setData($data);			
			$info = $cdek_api->sendData($component);
			
			$component = $cdek_api->loadComponent('order_status');
			$component->setData($data);			
			$status = $cdek_api->sendData($component);
			
			$no_error = true;
			if (isset($status->Order)){
				foreach ($status->Order as $item) 
				{
					$status_attributes = $item->attributes();
					if ((string)$status_attributes->ErrorCode){
						$no_error = false;
						return 'Ошибка' . ': ' . (string)$status_attributes->ErrorCode;
					}					
				}
			}
			
			if (isset($status->Order) && $no_error) 
			{
				
				//добавим в модуль сдэка
				$check_order = $this->db->query("SELECT order_id, dispatch_number, dispatch_id FROM cdek_order WHERE order_id = (SELECT order_id FROM order_ttns WHERE ttn LIKE ('" . $this->ttn . "') LIMIT 1)");
				if ($check_order->num_rows){
					$this->db->query("UPDATE cdek_order SET dispatch_number = '" . $this->ttn . "' WHERE order_id = '" . $check_order->row['order_id'] . "'");
					$this->db->query("UPDATE cdek_dispatch SET dispatch_number = '" . $this->ttn . "' WHERE dispatch_id = '" . $check_order->row['dispatch_id'] . "'");
					} else {
					$ttn_query = $this->db->query("SELECT * FROM order_ttns WHERE ttn LIKE ('" . $this->ttn . "') LIMIT 1");
					//insert dispatch
					$this->db->query("INSERT INTO `cdek_dispatch` SET `dispatch_number` = '" . $this->db->escape($this->ttn) . "', `date` = '" . $this->db->escape(strtotime($ttn_query->row['date_ttn'])) . "', `server_date` = '" . $this->db->escape(time()) . "'");
					$dispatch_id = $this->db->getLastId();
					
					//order_query
					$order_query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" .  $ttn_query->row['order_id'] . "' LIMIT 1");
					$order_info = $order_query->row;
					
					$sql  = "INSERT INTO `cdek_order` SET ";
					$sql .= "`order_id` = " . $ttn_query->row['order_id'] . ", ";
					$sql .= "`dispatch_id` = " . (int)$dispatch_id . ", ";
					$sql .= "`dispatch_number` = '" . $this->ttn . "', ";
					$sql .= "`city_id` = '44', ";
					$sql .= "`city_name` = '" . $this->db->escape('Москва') . "', ";
					if (!empty($order_info['shipping_postcode'])) {
						$sql .= "`city_postcode` = '" . $this->db->escape($order_info['shipping_postcode']) . "', ";
					}
					$sql .= "`recipient_city_name` = '" . $this->db->escape($order_info['shipping_city']) . "', ";
					$sql .= "`recipient_name` = '" . $this->db->escape($order_info['shipping_firstname']) . "', ";
					$sql .= "`recipient_email` = '" . $this->db->escape($order_info['email']) . "', ";
					$sql .= "`phone` = '" . $this->db->escape($order_info['telephone']) . "', ";
					$sql .= "`last_exchange` = '0'";
					$this->db->query($sql);
				}
				
				
				
				foreach ($status->Order as $item) 
				{
					
					$status_attributes = $item->Status->attributes();					
					$status_id = (string)$status_attributes->Code;
					$status_history = array();
					
					foreach ($item->Status->State as $status_info) {
						
						$status_attributes = $status_info->attributes();
						
						$status_history[] = array(
						'date'			=> (string)date('Y.m.d', strtotime($status_attributes->Date)),
						'status_id'		=> (int)$status_attributes->Code,
						'description'	=> (string)$status_attributes->Description,
						'city_code'		=> (string)$status_attributes->CityCode,
						'city_name'		=> (string)$status_attributes->CityName
						);
					}
					
					$status_attributes = $item->Status->attributes();
					
				}
				} else {
				return 'Не найден номер накладной';
			}
			
			$r = array();
			$r [] = "<div style='display:none;'>current_status_id:$status_id</div>";
			if (isset($status_history)){
				$r[] = "<table class='list'>";		
				foreach ($status_history as $v) {
					$r[] = "<tr>";         
					$r[] = "<td class='left'><b>{$v['description']}</b></td>";
					$r[] = "<td class='left'><b>{$v['city_name']}</b></td>";
					$r[] = "<td class='left'><b>{$v['date']}</b></td>";               
					$r[] = "</tr>";
				}
				$r[] = "</table>";
				
				return implode("\n", $r);
			}
			
			
		}
		
		
		
	}				
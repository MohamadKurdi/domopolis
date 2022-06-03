<?
	
	class ControllerKPCustomer extends Controller {
		protected $error = array();		
		
		public function index() {

		}
		
		public function dadataCustomFields(){
			$id = $this->request->get['id'];
			$type = $this->request->get['type'];
			$set = $this->request->get['set'];
			
			$this->load->model('tool/simplecustom');
			
			$this->model_tool_simplecustom->updateData($type, $id, $set, $this->request->post);
		}
		
		public function getFullAddressAjax(){
			$key = $this->config->get('config_dadata_api_key');
			$query = !empty($this->request->get['query'])?$this->request->get['query']:'';
			$exact = !empty($this->request->get['exact'])?true:false;
			$country_iso = !empty($this->request->get['iso'])?$this->request->get['iso']:'';
			$result = array();
			
			if (mb_strlen($query) < 3){
				$this->response->setOutput(json_encode($result, true));
			}
			
			if ($exact && !mb_strpos($query, ', кв')){
				$query .= ', кв 1';
			}
			
			$request = array(
            'query'             => $query,
            'restrict_value'    => true,
            'locations'         => array(
			'country_iso_code'  => $country_iso
            )
			);
			
			
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
			
			$decodedResult = array();
			
			if ($curlResult && $decodedResult = json_decode($curlResult, true)){
				if(!empty($decodedResult['suggestions']) && !empty($decodedResult['suggestions'][0]) && !empty($decodedResult['suggestions'][0]['data'])){				
					
					if ($exact){
						$decodedResult = $decodedResult['suggestions'][0];
					}  
				}
			}
			
			$this->response->setOutput(json_encode($decodedResult));
		}
		
		
		public function autocompleteaddress(){
			$api_key = $this->config->get('config_dadata_api_key');
			
			
			if ($api_key) {
			$request_name = $this->request->get['name'];
			$value = $this->request->get['suggest'];
			require_once(DIR_SYSTEM . 'library/dadata.php');
			$dadata = new Dadata;
			$result = $dadata->suggest($api_key, $request_name, array('query' => $value));
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($result));
			}
			}
		
		public function sendBirthDayGreetings(){
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');
			
			$_log = new Log('customer_birthday.txt');
			
			$month = date('m', strtotime("+2 days"));
			$day = date('d', strtotime("+2 days"));
			
			$customers_query = $this->db->query("SELECT DISTINCT(customer_id) FROM customer WHERE birthday_month = '" . (int)$month . "' AND birthday_date = '" . (int)$day . "'");
			
			echo '[i] Всего клиентов с ДР: ' . $customers_query->num_rows . PHP_EOL;
			
			$customers = array();
			foreach ($customers_query->rows as $row){
				$customer = $this->model_sale_customer->getCustomer($row['customer_id']);
				$customers[] = $customer;
				
				echo '[с] Клиент: ' . $customer['firstname'] . ', день рождения ' . $customer['birthday_date'] . '.' . $customer['birthday_month'] . ', скидка ' . $customer['discount_percent'] .'%'. PHP_EOL;
				$_log->write('Клиент: ' . $customer['firstname'] . ', день рождения ' . $customer['birthday_date'] . '.' . $customer['birthday_month'] . ', скидка ' . $customer['discount_percent'] .'%'. PHP_EOL);
				
				$discount_percent = 10;
				
				if ($customer['discount_percent'] == 10){
					$discount_percent = 12;
				}
				
				$base_url = $this->model_setting_setting->getKeySettingValue('config', 'config_url', $customer['store_id']);
				if ($customer['store_id'] == 0){
					$base_url = HTTPS_CATALOG;
				}
				$article_url = $base_url . 'bd';
				
				$options = array(
				'to'       => $customer['telephone'],						
				'from'     => $this->model_setting_setting->getKeySettingValue('config', 'config_sms_sign', $customer['store_id']),				
				'message'  => trim($customer['firstname']) . ', KitchenProfi дарит вам скидку ' . $discount_percent . '% к предстоящему Дню Рождения. Получить: ' . $article_url
				);
				
				echo '[textdebug] ' . $customer['telephone'] . ' Отправляем смс: ' . $options['message'] . PHP_EOL . PHP_EOL;
				
				$sms_id = $this->smsQueue->queue($options);
			}
			
			$this->load->model('kp/bitrixBot');
			
			$message = ':idea: Привет! Только что мы отправили смски с поздравлениями';
			
			$_date = date('m.d', strtotime(date('Y') . "-" . $customer['birthday_month'] . "-" . $customer['birthday_date']));
			
			$attach = Array();
			foreach ($customers as $customer){
				$attach[] = Array("MESSAGE" => "[B]". $customer['firstname'] . "  " . $customer['lastname'] ."[/B] " . $customer['customer_id'] . "[BR] День рождения " . $_date . "[BR]" . $customer['telephone']);
				$attach[] = Array("DELIMITER" => Array(
				'SIZE' => 200,
				'COLOR' => "#c6c6c6"
				));
			}
			
			$result = $this->model_kp_bitrixBot->sendMessageToUser(
			$message,  
			$attach,
			21663);
			
			die(json_encode($result));
			
		}
		
		
	}						
<?
	
	class ControllerKPCustomer extends Controller {
		protected $error = array();		
		
		public function index() {
		}
		
		public function dadataCustomFields(){
			$id 	= $this->request->get['id'];
			$type 	= $this->request->get['type'];
			$set 	= $this->request->get['set'];
			
			$this->load->model('tool/simplecustom');
			
			$this->model_tool_simplecustom->updateData($type, $id, $set, $this->request->post);
		}
		
		public function getFullAddressAjax(){
			$key 			= $this->config->get('config_dadata_api_key');
			$query 			= !empty($this->request->get['query'])?$this->request->get['query']:'';
			$exact 			= !empty($this->request->get['exact'])?true:false;
			$country_iso 	= !empty($this->request->get['iso'])?$this->request->get['iso']:'';
			$result = array();
			
			if (mb_strlen($query) < 3){
				$this->response->setOutput(json_encode($result, true));
			}
			
			if ($exact && !mb_strpos($query, ', кв')){
				$query .= ', кв 1';
			}
			
			$request = [
            'query'             => $query,
            'restrict_value'    => true,
            'locations'         => ['country_iso_code'  => $country_iso]
			];
						
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


		public function unique_telephones(){
			$this->load->model('sale/customer');

			$query = $this->db->query("SELECT DISTINCT(telephone) FROM customer WHERE telephone <> '' GROUP BY telephone HAVING COUNT(customer_id) > 1");

			foreach ($query->rows as $row){								
				echoLine('[ControllerKPCustomer::unique_telephones] Working with telephone: ' . $row['telephone'], 'i');

				$customers_query = $this->db->query("SELECT *, (SELECT COUNT(order_id) FROM `order` WHERE customer_id = customer.customer_id AND order_status_id > 0) as order_count FROM customer WHERE telephone LIKE '" . $this->db->escape($row['telephone']) . "'");

				$customers = [];
				foreach ($customers_query->rows as $row){
					$customers[] = $row;
				}

				echoLine('[ControllerKPCustomer::unique_telephones] Got customers: ' . count($customers), 'e');

				$best_customer_id = $customers[0]['customer_id'];
				$max_order_count  = 0;
				foreach ($customers as $customer){
					if ($customer['order_count'] > 0 && $customer['order_count'] > $max_order_count){
						$max_order_count  = $customer['order_count'];
						$best_customer_id = $customer['customer_id'];
						echoLine('[ControllerKPCustomer::unique_telephones] Customer ' . $customer['customer_id'] . ' has ' . $customer['order_count'] . ' orders, making him good', 's');
					}				
				}

				foreach ($customers as $customer){
					if ($best_customer_id != $customer['customer_id']){
						echoLine('[ControllerKPCustomer::unique_telephones] Updating customer in tables: ' . $customer['customer_id'] . ' -> ' . $best_customer_id, 'w');
						$this->model_sale_customer->replaceCustomer($customer['customer_id'], $best_customer_id);

						echoLine('[ControllerKPCustomer::unique_telephones] DELETING OLD CUSTOMER: ' . $customer['customer_id'] . '', 'e');
						$this->model_sale_customer->deleteCustomer($customer['customer_id']);
					}

				}

				echoLine('');
			}
		}

		
		public function sendBirthDayGreetings(){
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');
			
			$month = date('m', strtotime("+2 days"));
			$day = date('d', strtotime("+2 days"));
			
			$customers_query = $this->db->query("SELECT DISTINCT(customer_id) FROM customer WHERE birthday_month = '" . (int)$month . "' AND birthday_date = '" . (int)$day . "'");
			
			echo '[i] Всего клиентов с ДР: ' . $customers_query->num_rows . PHP_EOL;
			
			$customers = array();
			foreach ($customers_query->rows as $row){
				$customer = $this->model_sale_customer->getCustomer($row['customer_id']);
				$customers[] = $customer;								
			}					
		}				
	}						
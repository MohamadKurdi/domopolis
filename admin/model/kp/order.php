<?
	class ModelKpOrder extends Model {
		
		public function getConcardisOrder($cc_order_id, $string = false){
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.payengine.de/v1/orders/" . $cc_order_id . "");
			curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('concardis_email').':'.$this->config->get('concardis_secret'));  
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Content-Type: application/json')); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2);			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br");			
			$response = curl_exec($ch);
			curl_close($ch);
			
		//	$this->log->debug('GETTING ORDER ' . $response);
		//	$this->log->debug(json_decode($response, true));
			
			if ($string){
				return $response;
			}
			
			return json_decode($response, true);
			
		}
		
		public function checkIfOrderHasPayments($order_id, $payment_code, $sum = false, $substring = ''){
			$sql = "SELECT * FROM customer_transaction WHERE order_id = '" . (int)$order_id . "' AND added_from = '" . $this->db->escape($payment_code) . "' AND amount_national > 0 ";
			
			if ($sum || $substring){
				$sql .= ' AND (';
				
				if ($sum && $substring){
					$sql .= " amount_national = '" . (float)$sum . "' OR LOWER(description) LIKE ('%" . $this->db->escape(mb_strtolower($substring)) . "%') ";
				} elseif ($sum){
					$sql .= " amount_national = '" . (float)$sum . "' ";
				} elseif ($substring){
					$sql .= " LOWER(description) LIKE ('%" . $this->db->escape($substring) . "%')";
				}
				
				$sql .= ')';
			}
		
			$query = $this->db->query($sql);
		
			return $query->rows;
		}
		
		public function validateConcardisPaid($order_id){
			
		
		}
		
		public function parseTracker($order_id, $tracker_xml){
			$this->load->model('sale/order');
			
			if (!$tracker_xml){
				return false;
			}
			
			$order_info = $this->model_sale_order->getOrder($order_id);
			$order1c = json_decode($tracker_xml, true);
			
			if (!$order1c || !$order_info){
				return false;
			}
			
			$products = $this->model_sale_order->getOrderProducts($order_id);				
			$products1c = array();
			
			if (isset($order1c["Документ"])) {
					$order1c = $order1c["Документ"];
					if (isset($order1c['ОбщееСостояниеЗаказа']['Товар']['Наименование'])){
						$_tmp = $order1c['ОбщееСостояниеЗаказа']['Товар'];
						unset($order1c['ОбщееСостояниеЗаказа']['Товар']);
						$order1c['ОбщееСостояниеЗаказа']['Товар'] = array($_tmp);
					}
					$products1c = $order1c['ОбщееСостояниеЗаказа']['Товар'];
				}
				
				$general_tracker_status = array();
				
				if ($order_info['order_status_id'] == $this->config->get('config_treated_status_id')){					
					$general_tracker_status[] = 'first_step';
				}
				
				if (in_array($order_info['order_status_id'], array(2, $this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id'), $this->config->get('config_total_paid_order_status_id')))){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
				}											
				
				$is_on_third_step = false;				
				$is_on_fourth_step = true;
				$is_on_fifth_step = true;
				$is_on_seventh_step = true;
				foreach ($products as $product) {					
					$tracker_status = '';
					foreach ($products1c as $p1c){
						if ($p1c['Код'] == $product['product_id']){
							
							//если хоть один товар уже купили - то весь заказ считаем на комплектации
							if (!$is_on_third_step) {
								if ($p1c['ЗаказаноУПоставщика'] > 0 && $p1c['ЗаказаноУПоставщика'] == $product['quantity']){
									$is_on_third_step = true;
								}
							}
							
							//транзит заказа
							if ($p1c['ВПути'] != $product['quantity']) {
								//а если что-то лежит на С.О, а что-то уже едет, то это не неправда, а все оке
								if (((int)$p1c['ВПути'] + (int)$p1c['ОжидаютОтгрузкиПокупателю']) != (int)$product['quantity']) {
									$is_on_fourth_step = false;
								}
							}
							
							
							//если все товары лежат на складе в городе - получателе
							if ($p1c['ОжидаютОтгрузкиПокупателю'] != $product['quantity']) {								
								$is_on_fifth_step = false;
							}
							
							//все уже доставлено
							if ($p1c['ДоставленоПокупателю'] != $p1c['ЗаказаноПокупателем'] || $p1c['ДоставленоПокупателю']  != $product['quantity']) {								
								$is_on_seventh_step = false;
							}
							
						}
					}
					unset($p1c);
				}
				
				if ($is_on_third_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';						
				}
				
				if ($is_on_fourth_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
				}
				
				if ($is_on_fifth_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
				}	
				
				if ($is_on_seventh_step){
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$general_tracker_status[] = 'seventh_step';
				}
				
				if ($order_info['order_status_id'] == $this->config->get('config_complete_status_id')){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$general_tracker_status[] = 'seventh_step';
					$general_tracker_status[] = 'eighth_step';
					
				}
				
				
				if ($order_info['order_status_id'] == 26){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					
				}
				
				$is_on_pickpoint = (mb_stripos($order_info['shipping_code'], 'pickup_advanced') !== false);
				if ($order_info['order_status_id'] == 25){
					
					$general_tracker_status[] = 'first_step';
					$general_tracker_status[] = 'second_step';
					$general_tracker_status[] = 'third_step';
					$general_tracker_status[] = 'fourth_step';
					$general_tracker_status[] = 'fifth_step';
					$general_tracker_status[] = 'sixth_step';
					$is_on_pickpoint = true;
					
				}				
				
				if (in_array($order_info['order_status_id'], array($this->config->get('config_cancelled_status_id'), 23, 24))){
					$general_tracker_status = false;
				}
			
			
			
				return $general_tracker_status;
		}
		

	}
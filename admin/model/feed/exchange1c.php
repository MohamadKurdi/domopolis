<?
	class ModelFeedExchange1c extends Model {
		private $error = []; 
		
		private $stocks = array(
		'quantity_stock' => 'Германия',
		'quantity_stockK' => 'Украина',
		'quantity_stockM' => 'Россия',
		'quantity_stockMN' => 'Белоруссия',
		'quantity_stockAS' => 'Казахстан',
		);
		
		private function parseDaDataFullJSON($json){
			$result = [];
			
			if ($decoded = json_decode(html_entity_decode($json), true)){
				
				if (!empty($decoded['unrestricted_value']) && !empty($decoded['value']) && !empty($decoded['data'])){
					
					$result = array(
					'ПолныйАдрес' 		=> $decoded['unrestricted_value'],
					'КороткийАдрес' 	=> $decoded['value'],
					'Индекс'	  		=> $decoded['data']['postal_code'],
					'ФедеральныйРегион'	=> $decoded['data']['federal_district'],
					'Регион'		  	=> $decoded['data']['region'],	
					'РегионТип'	  		=> $decoded['data']['region_type_full'],
					'РегионТипКратко'	=> $decoded['data']['region_type'],
					'РегионПолностью'	=> $decoded['data']['region_with_type'],
					'Город'	  			=> $decoded['data']['city'],
					'ГородТип'	  		=> $decoded['data']['city_type_full'],
					'ГородТипКратко'	=> $decoded['data']['city_type'],
					'ГородПолностью'	=> $decoded['data']['city_with_type'],
					'ЗонаГорода'  		=> $decoded['data']['city_area'],
					'Район'		  		=> $decoded['data']['city_district'],
					'РайонТип'		  	=> $decoded['data']['city_district_type_full'],
					'РайонТипКратко'  	=> $decoded['data']['city_district_type'],
					'РайонПолностью'  	=> $decoded['data']['city_district_with_type'],
					'Улица'			  	=> $decoded['data']['street'],
					'УлицаТип'		  	=> $decoded['data']['street_type_full'],
					'УлицаТипКратко'  	=> $decoded['data']['street_type'],
					'УлицаПолностью'  	=> $decoded['data']['street_with_type'],
					'Дом'		  		=> $decoded['data']['house'],
					'ДомТип'			=> $decoded['data']['house_type_full'],
					'Корпус'		  	=> $decoded['data']['block'],
					'КорпусТип'		  	=> $decoded['data']['block_type_full'],
					'Квартира'		  	=> $decoded['data']['flat'],
					'КвартираТип'		=> $decoded['data']['flat_type_full'],
					'КвартираТипКратко'	=> $decoded['data']['flat_type'],
					'ГеолокацияШирота'  => $decoded['data']['geo_lat'],
					'ГеолокацияДолгота' => $decoded['data']['geo_lon'],
					'ВнутриМКАД' 		=> $decoded['data']['beltway_hit'],
					'РасстояниеДоМКАД'	=> $decoded['data']['beltway_distance'],
					);
					
				}
				return $result;
			}
			
			return 'Ложь';
		}
		
		public function index() {
		}
		
		public function processTimes($order_id){
			$this->load->model('sale/order');
			$order = $this->model_sale_order->getOrder($order_id);
			$order_histories = $this->model_sale_order->getOrderHistories($order_id, 0, 200);
			
			$date_accepted = false;
			$date_closed = false;
			$date_cancelled = false;
			foreach ($order_histories as $history){
				if (in_array($history['order_status_id'], array($this->config->get('config_confirmed_order_status_id'), $this->config->get('config_confirmed_nopaid_order_status_id')))){
					if (!$date_accepted){
						$date_accepted = $history['date_added'];
					}
				}
				
				if ($history['order_status_id'] == $this->config->get('config_complete_status_id')){	
					if (!$date_closed){
						$date_closed = $history['date_added'];
					}
				}
				
				if ($history['order_status_id'] == $this->config->get('config_cancelled_status_id')){
					if (!$date_cancelled){
						$date_cancelled = $history['date_added'];
					}
				}
			}
			
			return array(
			'date_added'    => $order['date_added'],
			'date_accepted' => $date_accepted,
			'date_closed'   => $date_closed,
			'date_cancelled'   => $date_cancelled
			);			
		}		
		
		public function addOrderToQueue($order_id){
			$this->Fiscalisation->addOrderToQueue($order_id);
		}
		
		public function removeOrderFromQueue($order_id){
			$this->Fiscalisation->addOrderToQueue($order_id);
		}
		
		public function getCategoriesTree($do_echo = false){
			$this->load->model('catalog/category');
			
			$categories = $this->model_catalog_category->getCategoriesTree();
			
			$document['Документ'] = array(
			'Роль'  => 'Справочник'
			,'ТипИнформации'  => 'ДеревоКатегорий'
			);	
			
			$category_counter = 0;
			foreach ($categories as $category){
				
				$document['Документ']['ДеревоКатегорий']['Категория' . $category_counter] = array(
				'ИД'   => $category['category_id'],
				'РодительИД'   => $category['parent_id'],
				'Наименование'   => $category['name']
				);
				
				$category_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/structures/categories.xml', $content);	
		}
		
		public function getDimensions($do_echo){
			$this->load->model('localisation/weight_class');
			$this->load->model('localisation/length_class');
			
			$document['Документ'] = array(
			'Роль'  => 'Справочник'
			,'ТипИнформации'  => 'ЕдиницыИзмерения'
			);	
			
			$weight_classes = $this->model_localisation_weight_class->getWeightClasses();
			$length_classes = $this->model_localisation_length_class->getLengthClasses();
			
			$weight_counter = 0;
			foreach ($weight_classes as $weight_class) {
				
				$document['Документ']['СписокЕдиницИзмеренияВеса']['ЕдиницаИзмеренияВеса' . $weight_counter] = array(
				'ИД'   						=>  $weight_class['weight_class_id']
				,'Наименование'	 			=>	$weight_class['title']
				,'КодАмазона'	 			=>	$weight_class['amazon_key']
				,'УсловноеОбозначение'		=>	$weight_class['unit']
				,'ЭтоЕдиницаПоУмолчанию' 	=>	(($weight_class['weight_class_id'] == $this->config->get('config_weight_class_id'))?'Истина':'Ложь')
				,'Значение'			    	=>  number_format($weight_class['value'],2,'.',' ')								
				);
				
				$weight_counter++;
			}
			
			$length_counter = 0;
			foreach ($length_classes as $length_class) {
				
				$document['Документ']['СписокЕдиницИзмеренияРазмера']['ЕдиницаИзмеренияРазмера' . $length_counter] = array(
				'ИД'   						=>  $length_class['length_class_id']
				,'Наименование'	 			=>	$length_class['title']
				,'КодАмазона'	 			=>	$length_class['amazon_key']
				,'УсловноеОбозначение'		=>	$length_class['unit']
				,'ЭтоЕдиницаПоУмолчанию' 	=>	(($length_class['length_class_id'] == $this->config->get('config_length_class_id'))?'Истина':'Ложь')
				,'Значение'			    	=>  number_format($weight_class['value'],2,'.',' ')								
				);
				
				$length_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/structures/dimensions.xml', $content);
		}
				
		public function getCurrencies($do_echo){
			$this->load->model('localisation/currency');
			
			$currencies = $this->model_localisation_currency->getCurrencies();
			
			$document['Документ'] = array(
			'Роль'  => 'Справочник'
			,'ТипИнформации'  => 'КурсыВалют'
			);		
			
			$currency_counter = 0;
			foreach ($currencies as $currency) {
				
				$document['Документ']['СписокВалют']['Валюта' . $currency_counter] = array(
				'ИД'   						=> $currency['currency_id']
				,'КодISO' 					=> $currency['code']
				,'Наименование' 			=> $currency['title']
				,'КриптоПара'         		=> (($currency['cryptopair'])?$currency['cryptopair']:'Ложь')
				,'ЭтоВалютаПоУмолчанию' 	=> (($currency['code'] == $this->config->get('config_currency'))?'Истина':'Ложь')
				,'КурсВнутренний'         	=> number_format($currency['value'],2,'.',' ')
				,'КурсБанковский'    		=> number_format($currency['value_real'],2,'.',' ')				
				,'КурсКрипто'         		=> (($currency['cryptopair'])?number_format($currency['cryptopair_value'],4,'.',' '):'Ложь')
				,'ДатаИзменения' 			=> date('Y.m.d H:i:s', strtotime($currency['date_modified']))
				);
				
				$currency_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/structures/currencies.xml', $content);		
		}
		
		public function getDeliveriesAndShippings($do_echo){
			$this->load->model('setting/setting');
			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$this->load->model('setting/extension');
			
			$document = [];
			$document['Документ'] = array(
			'Роль'  => 'Справочник'
			,'ТипИнформации'  => 'ВозможныеCпособыДоставкиИОплаты'
			);
			
			$dp = $this->config->get('dostavkaplus_module');
			$i = 1;
			foreach ($dp as $mdd){
				$shippings[] = array(
				'code' =>  'dostavkaplus.sh'.$i,	
				'title' =>  $mdd['title'][$this->config->get('config_language_id')]	
				);
				$i++;
			}
			
			
			$pa = $this->config->get('pickup_advanced_module');
			$i = 0;
			foreach ($pa as $mdp){
				$shippings[] = array(				
				'code' =>  'pickup_advanced.point_'.$i,	
				'title' =>  $mdp[$this->config->get('config_language_id')]['description']
				);
				$i++;
			}
			
			$shipping_counter = 0;		
			foreach ($shippings as $shipping){
				$document['Документ']['СпособыДоставки']['СпособДоставки' . $shipping_counter] = array(				
				'Код' => $shipping['code']
				,'Наименование' =>	$shipping['title']				
				);
				
				$shipping_counter++;
			}
			
			
			//Оплаты
			$opp = $this->config->get('transfer_plus_module');
			$i = 1;
			foreach ($opp as $oppm){
				
				$_percent = explode(':',$oppm['other_rate']);
				if (isset($_percent[1])){
					$_ptxt = ' '.(int)$_percent[1].'%';
					} else {
					$_ptxt = '';
				}
				
				
				$payments[] = array(
				'code' =>  'transfer_plus.'.$i,	
				'title' =>  $oppm['title'][$this->config->get('config_language_id')] . $_ptxt,
				'currency' => $oppm['curr'],
				'isprepay' => (isset($oppm['isprepay']) && $oppm['isprepay'])
				);
				$i++;
			}
			
			$payments[] = array(
			'code' =>  'cod',	
			'title' =>  'Полная предоплата',
			'isprepay' => false
			);
			
			$payment_counter = 0;
			foreach ($payments as $payment){			
				
				$document['Документ']['СпособыОплаты']['СпособОплаты' . $payment_counter] = array(
				'Код' => $payment['code']
				,'Наименование' =>	$payment['title']
				,'ВалютаСтоимости' => (isset($payment['currency']))?$payment['currency']:''
				,'ЭтоЧастичнаяПредоплата' => ($payment['isprepay'])?'Истина':'Ложь'
				);
				
				$payment_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/structures/deliveries_and_payments_list.xml', $content);		
		}
		
		public function getOrderStatusCodes($do_echo){
			$this->load->model('localisation/order_status');
			
			$data = array(
			'start' => 0,
			'limit' => 100
			);
			$order_statuses = $this->model_localisation_order_status->getOrderStatuses($data);								
			
			$document = [];
			$document['Документ'] = array(
			'Роль'  => 'Справочник'
			,'ТипИнформации'  => 'Статусы заказа'
			);
			
			$order_statuses_counter = 0;
			
			foreach ($order_statuses as $order_status){
				$document['Документ']['СтатусыЗаказа']['СтатусЗаказа' . $order_statuses_counter] = array(
				'ИД' 			=> $order_status['order_status_id']
				,'Наименование' => $order_status['name']
				,'РазрешенКВыгрузкеВ1С' => (in_array($order_status['order_status_id'], $this->config->get('config_odinass_order_status_id')))?'Истина':'Ложь'
				,'ЭтоСтатусНовогоЗаказаПоУмолчанию' => ($order_status['order_status_id'] == $this->config->get('config_order_status_id'))?'Истина':'Ложь'
				,'ЭтоСтатусЗавершенногоЗаказа' => ($order_status['order_status_id'] == $this->config->get('config_complete_status_id'))?'Истина':'Ложь'
				,'ЭтоСтатусОтмененногоЗаказа' => ($order_status['order_status_id'] == $this->config->get('config_cancelled_status_id'))?'Истина':'Ложь'
				,'ЭтоСтатусПодтвержденногоПокупателемЗаказа' => ($order_status['order_status_id'] == $this->config->get('config_confirmed_order_status_id'))?'Истина':'Ложь'
				);
				
				$order_statuses_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/structures/order_status_list.xml', $content);		
		}	
		
		public function initiateOrdersFromDateToDateXML(){
			if (isset($this->request->get['date_from'])){
				$date_from = date('' ,strtotime($this->request->get['date_from']));
			}			
		}
				
		public function getOrderSuppliesXML($order_id = 0, $do_echo = false, $dir = false){			
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('sale/return');		
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/collection');
			$this->load->model('localisation/order_status');
			$this->load->model('localisation/country');
			$this->load->model('localisation/currency');
			$this->load->model('localisation/return_reason');
			$this->load->model('localisation/return_action');
			$this->load->model('localisation/return_status');
			$this->load->model('localisation/weight_class');
			$this->load->model('sale/supplier');
			$this->load->model('user/user');
			$this->load->model('setting/setting');
			
			$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "'");
			
			$document = [];
			$document_counter = 0;
			
			if ($query->num_rows) {
				
				foreach ($query->rows as $orders_data) {
					$order = $this->model_sale_order->getOrder($orders_data['order_id']);
					$order_status = $this->model_localisation_order_status->getOrderStatus($order['order_status_id']);
					
					$country_id = $this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $order['store_id']);
					$country_name = $this->model_localisation_country->getCountryName($country_id);
					
					$date = date('Y-m-d', strtotime($order['date_added']));
					$time = date('H:i:s', strtotime($order['date_added']));
					
					$document['Документ' . $document_counter] = array(
					'ИД'                   				    => $order['order_id']
					,'НомерЗаказа'                			=> $order['order_id']					
					,'Дата'                				 	=> $date
					,'Время'                				=> $time
					,'Валюта'               				=> $order['currency_code']
					,'ВалютаНаименование'      				=> $this->model_localisation_currency->getCurrencyNameByCode($order['currency_code'])
					,'ОсновнаяВалюта'       				=> $this->config->get('config_currency')
					,'ОсновнаяВалютаНаименование'			=> $this->model_localisation_currency->getCurrencyNameByCode($this->config->get('config_currency'))
					,'КурсКОсновнойВалюте'  				=> $order['currency_value']						
					);
					
					
					// Товары
					$products = $this->model_sale_order->getOrderProducts($orders_data['order_id']);				
					
					$product_counter = 0;
					$product_supplies_counter = 0;
					foreach ($products as $product) {					
						$is_set = $this->model_catalog_product->getThisProductIsSet($product['product_id']);				
						
						$product_supplies = $this->model_sale_supplier->getOPSupply($product['order_product_id']);
						
						foreach ($product_supplies as $product_supply){
							
							$real_supplier = $this->model_sale_supplier->getSupplier($product_supply['supplier_id']);
							$_currency_id = $this->model_localisation_currency->getCurrencyByCode($product_supply['currency']);
							
							$document['Документ' . $document_counter]['Поступления']['Поступление' . $product_supplies_counter] = array(
							'ИДОперации' 				=> $product_supply['order_product_supply_id'],
							'ИДЗаказа'  				=> $product_supply['order_id'],
							'ИДТовара'  				=> $product_supply['product_id'],
							'ИДЗаписиЗаказТовар'   	 	=> $product_supply['order_product_id'],
							'ЭтоТоварВходящийВНабор' 	=> 'Ложь',
							'ПоставщикИД'    			=> $product_supply['supplier_id'],
							'ПоставщикНаименование' 	=> rms($real_supplier['supplier_name']),
							'ПоставщикТип' 				=> $real_supplier['supplier_type'],
							'Количество' 		    	=> $product_supply['amount'],
							'ЦенаЗаЕдиницу'		    	=> $product_supply['price'],
							'ВалютаЗакупки'	        	=> $product_supply['currency'],
							'ВалютаЗакупкиИД'	    	=> $_currency_id['currency_id'],
							'ВалютаЗаказа'         	 	=> $order['currency_code'],
							'КурсКОсновнойВалюте'  	 	=> $order['currency_value'],
							'ЭтоОбеспечениеЗаказа'  	=> ($product_supply['is_for_order'])?'Истина':'Ложь'
							);
							
							
							$product_supplies_counter++;
						}
						
						if ($is_set){
							$set_id = (int)$is_set['set_id'];
							$set_products_results = $this->model_sale_order->getOrderProductsBySet($order_id, $set_id);
							
							foreach ($set_products_results as $set_product){								
								$set_supplies = $this->model_sale_supplier->getOPSupplyForSet($set_product['order_set_id']);
								
								foreach ($set_supplies  as $set_supply){
									
									$real_supplier = $this->model_sale_supplier->getSupplier($set_supply['supplier_id']);
									
									$document['Документ' . $document_counter]['Поступления']['Поступление' . $product_supplies_counter] = array(
									'ИДОперации' 				=> $set_supply['order_product_supply_id'],
									'ИДЗаказа'  				=> $set_supply['order_id'],
									'ИДТовара'  				=> $set_supply['product_id'],
									'ИДНабора'				 	=> $set_id,
									'ИДЗаписиЗаказНабор'    	=> $set_supply['order_set_id'],
									'ЭтоТоварВходящийВНабор' 	=> 'Истина',
									'ПоставщикИД'    			=> $set_supply['supplier_id'],
									'ПоставщикНаименование' 	=> rms($real_supplier['supplier_name']),
									'ПоставщикТип' 				=> $real_supplier['supplier_type'],
									'Количество' 		    	=> $set_supply['amount'],
									'ЦенаЗаЕдиницу'		    	=> $set_supply['price'],
									'ВалютаЗакупки'	        	=> $set_supply['currency'],
									'ВалютаЗаказа'          	=> $order['currency_code'],
									'КурсКОсновнойВалюте'  	 	=> $order['currency_value'],
									'ЭтоОбеспечениеЗаказа'  	=> ($set_supply['is_for_order'])?'Истина':'Ложь'
									);
									
									
									$product_supplies_counter++;
								}																											
							}
						}
					}
					
					if ($product_supplies_counter) {
						$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
						$oXML = new SimpleXMLElement($root);
						$xml = array_to_xml($document, $oXML);
						
						$content = $xml->asXML();
						
						if ($do_echo) {
							header("Content-Type: text/xml");
							print_r($content);
						}
						
						$_fname = 'supplies_' . (int)$order['order_id']; 
						
						file_put_contents(DIR_EXPORT . 'odinass/supplies/' . $_fname . '.xml', $content);
						chmod(DIR_EXPORT . 'odinass/supplies/' . $_fname . '.xml', 0777);
					}
				}
			}
		}
		
		public function getOrderTransactionsXML($order_id = 0, $do_echo = false, $dir = false){
			
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('sale/return');		
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/collection');
			$this->load->model('localisation/order_status');
			$this->load->model('localisation/country');
			$this->load->model('localisation/currency');
			$this->load->model('localisation/return_reason');
			$this->load->model('localisation/return_action');
			$this->load->model('localisation/return_status');
			$this->load->model('localisation/weight_class');
			$this->load->model('localisation/legalperson');
			$this->load->model('sale/supplier');
			$this->load->model('user/user');
			$this->load->model('setting/setting');
			
			$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "'");
			
			$document = [];
			$document_counter = 0;
			
			if ($query->num_rows) {
				
				foreach ($query->rows as $orders_data) {
					$order = $this->model_sale_order->getOrder($orders_data['order_id']);
					$order_status = $this->model_localisation_order_status->getOrderStatus($order['order_status_id']);
					
					$country_id = $this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $order['store_id']);
					$country_name = $this->model_localisation_country->getCountryName($country_id);
					
					$date = date('Y-m-d', strtotime($order['date_added']));
					$time = date('H:i:s', strtotime($order['date_added']));
					
					$document['Документ' . $document_counter] = array(
					'ИД'                   				    => $order['order_id']
					,'НомерЗаказа'                			=> $order['order_id']					
					,'Дата'                				 	=> $date
					,'Время'                				=> $time
					,'Валюта'               				=> $order['currency_code']
					,'ВалютаНаименование'      				=> $this->model_localisation_currency->getCurrencyNameByCode($order['currency_code'])
					,'ОсновнаяВалюта'       				=> $this->config->get('config_currency')
					,'ОсновнаяВалютаНаименование'			=> $this->model_localisation_currency->getCurrencyNameByCode($this->config->get('config_currency'))
					,'КурсКОсновнойВалюте'  				=> $order['currency_value']						
					);
					
					if ($order['legalperson_id']){
						if ($legalperson = $this->model_localisation_legalperson->getLegalPerson($order['legalperson_id'])){																			
							$document['Документ' . $document_counter]['ЮридическоеЛицо'] = array(
							'ИД'                   					=> $legalperson['legalperson_id']
							,'Наименование'                			=> $legalperson['legalperson_name']
							,'ЭтоЮрЛицоДляВыставленияСчетов'    	=> $legalperson['legalperson_legal']?'Истина':'Ложь'
							,'РеквизитыПолучателя'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_desc']))
							,'РеквизитыПолучателя2'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_additional']))
							,'ПривязкаКСтранеИД' 				    => $legalperson['legalperson_country_id']
							,'ПривязкаКСтранеИмя' 				    => $country_name = $this->model_localisation_country->getCountryName($legalperson['legalperson_country_id'])
							);
							
							if ($legalperson['legalperson_additional']){
								$unpacked_struct = [];
								$info = explode(PHP_EOL, $legalperson['legalperson_additional']);
								foreach ($info as $string){
									$line = explode(':', $string);
									if (isset($line[0]) && isset($line[1])){
										$unpacked_struct[removeSpaces($line[0])] = trim($line[1]);
									}
								}
								
								foreach ($unpacked_struct as $uns => $unsval){
									$document['Документ' . $document_counter]['ЮридическоеЛицо']['РеквизитыСтруктурa'][rms($uns)] = rms($unsval);								
								}
								
							}
						}						
					}
					
					if ($order['card_id']){
						if ($legalperson = $this->model_localisation_legalperson->getLegalPerson($order['card_id'])){																			
							$document['Документ' . $document_counter]['КартаДляОплаты'] = array(
							'ИД'                   					=> $legalperson['legalperson_id']
							,'Наименование'                			=> $legalperson['legalperson_name']
							,'ЭтоЮрЛицоДляВыставленияСчетов'    	=> $legalperson['legalperson_legal']?'Истина':'Ложь'
							,'РеквизитыПолучателя'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_desc']))
							,'РеквизитыПолучателя2'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_additional']))
							,'ПривязкаКСтранеИД' 				    => $legalperson['legalperson_country_id']
							,'ПривязкаКСтранеИмя' 				    => $country_name = $this->model_localisation_country->getCountryName($legalperson['legalperson_country_id'])
							);
							
							if ($legalperson['legalperson_additional']){
								$unpacked_struct = [];
								$info = explode(PHP_EOL, $legalperson['legalperson_additional']);
								foreach ($info as $string){
									$line = explode(':', $string);
									if (isset($line[0]) && isset($line[1])){
										$unpacked_struct[removeSpaces($line[0])] = trim($line[1]);
									}																
								}
								
								foreach ($unpacked_struct as $uns => $unsval){
									$document['Документ' . $document_counter]['КартаДляОплаты']['РеквизитыСтруктурa'][rms($uns)] = rms($unsval);								
								}
								
							}
						}						
					}
					
					$order_transactions = $this->model_sale_customer->getTransactions($order['customer_id'], 0, 10000, $orders_data['order_id']);	
					
					$transactions_counter = 0;
					foreach ($order_transactions as $order_transaction){
						$ot_date = date('Y-m-d', strtotime($order_transaction['date_added']));
						$ot_time = date('H:i:s', strtotime($order_transaction['date_added']));
						
						$document['Документ' . $document_counter]['ФинансовыеТранзакцииЗаказа']['ФинансоваяТранзакцияЗаказа'.$transactions_counter] = array(
						'ИД'                    		=> $order_transaction['customer_transaction_id']
						,'ГУИД'                    		=> !empty($order_transaction['guid'])?$order_transaction['guid']:'Ложь'
						,'ИДПокупателя'        			=> $order['customer_id']
						,'ИДЗаказа'             		=> $order['order_id']	
						,'Дата'             			=> $ot_date
						,'Время'             			=> $ot_time		
						,'Описание'             		=> $order_transaction['description']						
						,'Валюта'              			=> $order_transaction['currency_code']
						,'Сумма'                		=> $order_transaction['amount_national']
						,'СуммаВОсновнойВалюте' 		=> $order_transaction['amount']
						,'КодИсточника'					=> $order_transaction['added_from']	
						,'КодКассы'						=> $order_transaction['legalperson_id']?$order_transaction['legalperson_id']:'Ложь'
						,'НаименованиеКассы'			=> $order_transaction['legalperson_name_1C']?$order_transaction['legalperson_name_1C']:'Ложь'
						,'НаименованиеКассы2'			=> $order_transaction['legalperson_name']?$order_transaction['legalperson_name']:'Ложь'
						,'ЭтоРучноеВнесение'			=> ($order_transaction['added_from'] == 'manual_admin')?'Истина':'Ложь'
						,'ЭтоАвтоСписаниеПоВыполнен'	=> ($order_transaction['added_from'] == 'auto_order_close')?'Истина':'Ложь'
						,'JSONПлатежнойСистемы'			=> $order_transaction['json']
						);
						
						$transactions_counter++;
					}
					
					$order_total_transaction_national = $this->model_sale_customer->getTransactionTotalNational($order['customer_id'], $orders_data['order_id']);	
					$order_total_transaction = $this->model_sale_customer->getTransactionTotal($order['customer_id'], $orders_data['order_id']);	
					
					$document['Документ' . $document_counter]['ФинансовыеТранзакцииЗаказа']['ФинансоваяЗадолженностьПоЗаказуНаДанныйМомент'] = array(		
					'Валюта'               => $order['currency_code']
					,'Сумма'                => $order_total_transaction_national?$order_total_transaction_national:'0.0000'
					,'СуммаВОсновнойВалюте' => $order_total_transaction?$order_total_transaction:'0.0000'
					
					);
					
				}
				
				if ($transactions_counter) {
					
					$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
					$oXML = new SimpleXMLElement($root);
					$xml = array_to_xml($document, $oXML);
					
					$content = $xml->asXML();
					
					if ($do_echo) {
						header("Content-Type: text/xml");
						print_r($content);
					}
					
					$_fname = 'transactions_' . (int)$order['order_id']; 
					
					file_put_contents(DIR_EXPORT . 'odinass/transactions/' . $_fname . '.xml', $content);
					chmod(DIR_EXPORT . 'odinass/transactions/' . $_fname . '.xml', 0777);
					
					file_put_contents(DIR_EXPORT . 'odinass/transactions2/' . $_fname . '.xml', $content);
					chmod(DIR_EXPORT . 'odinass/transactions2/' . $_fname . '.xml', 0777);
					
				}
				
			}
		}
		
		public function getOrderReturnsXML($order_id = 0, $do_echo = false, $dir = false){			
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('sale/return');		
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/collection');
			$this->load->model('localisation/order_status');
			$this->load->model('localisation/country');
			$this->load->model('localisation/currency');
			$this->load->model('localisation/return_reason');
			$this->load->model('localisation/return_action');
			$this->load->model('localisation/return_status');
			$this->load->model('localisation/weight_class');
			$this->load->model('sale/supplier');
			$this->load->model('user/user');
			$this->load->model('setting/setting');
			
			$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "'");
			
			$document = [];
			$document_counter = 0;
			
			if ($query->num_rows) {
				
				foreach ($query->rows as $orders_data) {
					$order = $this->model_sale_order->getOrder($orders_data['order_id']);
					$order_status = $this->model_localisation_order_status->getOrderStatus($order['order_status_id']);
					
					$country_id = $this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $order['store_id']);
					$country_name = $this->model_localisation_country->getCountryName($country_id);
					
					$date = date('Y-m-d', strtotime($order['date_added']));
					$time = date('H:i:s', strtotime($order['date_added']));
					
					$document['Документ' . $document_counter] = array(
					'ИД'                   				    => $order['order_id']
					,'НомерЗаказа'                			=> $order['order_id']					
					,'Дата'                				 	=> $date
					,'Время'                				=> $time
					,'Валюта'               				=> $order['currency_code']
					,'ВалютаНаименование'      				=> $this->model_localisation_currency->getCurrencyNameByCode($order['currency_code'])
					,'ОсновнаяВалюта'       				=> $this->config->get('config_currency')
					,'ОсновнаяВалютаНаименование'			=> $this->model_localisation_currency->getCurrencyNameByCode($this->config->get('config_currency'))
					,'КурсКОсновнойВалюте'  				=> $order['currency_value']						
					);
					
					$return_filter_data = array(
					'filter_order_id' => $orders_data['order_id']
					);
					
					$order_returns = $this->model_sale_return->getReturns($return_filter_data);
					$returns_counter = 0;
					
					foreach ($order_returns as $order_return){
						$or_date = date('Y-m-d', strtotime($order_return['date_added']));
						$or_time = date('H:i:s', strtotime($order_return['date_added']));
						
						$document['Документ' . $document_counter]['ВозвратыПоЗаказу']['Возврат'.$returns_counter] = array(
						'ИД'                    			=> $order_return['return_id']
						,'ИДПокупателя'               		=> $order['customer_id']
						,'ИДЗаказа'               		    => $order['order_id']	
						,'Дата'             				=> (isset($ot_date))?$ot_date:$date
						,'Время'			             	=> (isset($ot_time))?$ot_time:$time
						,'ТоварИД'							=> $order_return['product_id']
						,'ТоварАртикул'						=> $order_return['model']							
						,'ТоварАртикулНормализованный'		=> normalizeSKU($order_return['model'])
						,'Описание'             			=> $order_return['comment']						
						,'Валюта'              				=> $order['currency_code']
						,'ЦенаЗаЕдиницу'  					=> $order_return['price_national']
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  	=> $order_return['price']
						,'Количество'     					=> $order_return['quantity']
						,'Сумма'          					=> $order_return['total_national']
						,'СуммаВОсновнойВалюте' 			=> $order_return['total']
						,'СтатусВозвратаИД'  				=> $order_return['return_status_id']
						,'ПричинаВозвратаИД'				=> $order_return['return_reason_id']
						,'СтатусВозврата'   				=> $order_return['status']
						,'ПричинаВозврата'					=> $order_return['reason']
						,'ЭтоВозвратПоставщику'  			=> ($order_return['to_supplier']==1)?'Истина':'Ложь'
						,'ЭтоВозвратКлиенту'  			    => ($order_return['to_supplier']==0)?'Истина':'Ложь'
						,'ЭтоОтказДоОтгрузки'  			    => ($order_return['to_supplier']==2)?'Истина':'Ложь'
						
						);
						
						
						$returns_counter++;
					}
				}
				
				if ($returns_counter) {
					
					$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
					$oXML = new SimpleXMLElement($root);
					$xml = array_to_xml($document, $oXML);
					
					$content = $xml->asXML();
					
					if ($do_echo) {
						header("Content-Type: text/xml");
						print_r($content);
					}
					
					$_fname = 'returns_' . (int)$order['order_id']; 
					
					file_put_contents(DIR_EXPORT . 'odinass/returns/' . $_fname . '.xml', $content);
					chmod(DIR_EXPORT . 'odinass/returns/' . $_fname . '.xml', 0777);
					
				}
				
			}
		}
				
		public function makeSalesResultXML($order_id = 0, $do_echo = false, $dir = false, $do_iconv = false, $explicit_delivery_num = false){
			$this->load->model('sale/order');
			$this->load->model('catalog/product');
			$this->load->model('catalog/parties');
			$this->load->model('user/user');
			$this->load->model('localisation/country');
			$this->load->model('sale/supplier');
			$this->load->model('localisation/currency');
			
			$order = $this->model_sale_order->getOrder($order_id);
			
			if ($explicit_delivery_num || ($order['order_status_id'] == $this->config->get('config_partly_delivered_status_id') || $order['order_status_id'] == $this->config->get('config_complete_status_id'))) {							
				$current_delivery = $this->model_sale_order->getOrderCurrentDeliveryForClosing($order_id);
				
				if ($explicit_delivery_num){
					$current_delivery = (int)$explicit_delivery_num;
				}
				
				if ($explicit_delivery_num == 'current'){
					$current_delivery = $this->model_sale_order->getOrderCurrentDeliveryForClosing($order_id);
				}
				
				if ($explicit_delivery_num == 'full'){
					$current_delivery = 'full';
				}
				
				$date = date('Y-m-d', strtotime($order['date_added']));
				$time = date('H:i:s', strtotime($order['date_added']));
				
				$date_now = date('Y-m-d');
				$time_now = date('H:i:s');
				
				$document['Документ'] = array(
				'ИД'                   				    => $order['order_id']
				,'НомерЗаказа'                			=> $order['order_id']	
				,'НомерРеализации'                		=> $order['order_id'] . '-' . $current_delivery
				,'ДатаЗаказа'          				 	=> $date
				,'ВремяЗаказа'             				=> $time
				,'ДатаРеализации'          				=> $date_now
				,'ВремяРеализации'             			=> $time_now
				,'Валюта'               				=> $order['currency_code']
				,'ВалютаНаименование'      				=> $this->model_localisation_currency->getCurrencyNameByCode($order['currency_code'])
				,'ОсновнаяВалюта'       				=> $this->config->get('config_currency')
				,'ОсновнаяВалютаНаименование'			=> $this->model_localisation_currency->getCurrencyNameByCode($this->config->get('config_currency'))
				,'КурсКОсновнойВалюте'  				=> $order['currency_value']	
				,'НомерРеализацииДопоставки'            => $current_delivery
				,'ЭтоЗакрытиеОднойИзПоставок'           => ($order['order_status_id'] == $this->config->get('config_partly_delivered_status_id'))?'Истина':'Ложь'
				,'ЭтоПолноеЗакрытиеЗаказа'              => ($order['order_status_id'] == $this->config->get('config_complete_status_id'))?'Истина':'Ложь'
				);
				
				// Товары
				$products = $this->model_sale_order->getOrderProducts($order['order_id'], true, 'op.price_national ASC');				
				
				$product_counter = 0;
				foreach ($products as $product) {						
					if ($product['delivery_num'] == $current_delivery){
						$document['Документ']['ЧастичнаяРеализация']['Товары']['Товар' . $product_counter] = array(
						'ИД'            						=> $product['product_id']
						,'ИДЗаписиЗаказТовар'					=> $product['order_product_id']
						,'Артикул'		 						=> $product['model']
						,'АртикулНормализованный'				=> normalizeSKU($product['model'])
						,'Наименование'   						=> rms($product['name'])	
						,'НаименованиеУКР'   					=> !empty($product['ua_name'])?rms($product['ua_name']):''
						,'ЦенаЗаЕдиницу'  						=> $product['price_national']
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  		=> $product['price']						
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  		=> $product['price']
						,'Количество'     						=> $product['quantity']
						,'НомерПоставки'     					=> $product['delivery_num']
						,'НомерПартии'     					    => $product['part_num']
						,'Количество'     						=> $product['quantity']
						,'БонусовЗаЕдиницу'     				=> (int)($product['reward']/$product['quantity'])
						,'Бонусов'     							=> $product['reward']
						,'Сумма'          						=> $product['total_national']
						,'СуммаВОсновнойВалюте' 				=> $product['total']
						,'ЦенаЗаЕдиницуСУчетомСкидки'    		=> $product['pricewd_national']
						,'СуммаСУчетомСкидки'    		    	=> $product['totalwd_national']						
						,'ЭтотТоварСоСклада'				    => ($product['from_stock'])?'Истина':'Ложь'
						,'КоличествоТовараСоСклада'				=> (int)$product['quantity_from_stock']
						);
						
						$product_counter++;
					}
				}
				
				if ($current_delivery == 'full' || $order['order_status_id'] == $this->config->get('config_complete_status_id')) {
					unset($product);
					
					$product_counter = 0;
					foreach ($products as $product) {												
						$document['Документ']['ПолнаяРеализация']['Товары']['Товар' . $product_counter] = array(
						'ИД'            						=> $product['product_id']
						,'ИДЗаписиЗаказТовар'					=> $product['order_product_id']
						,'Артикул'		 						=> $product['model']
						,'АртикулНормализованный'				=> normalizeSKU($product['model'])
						,'Наименование'   						=> rms($product['name'])	
						,'НаименованиеУКР'   					=> !empty($product['ua_name'])?rms($product['ua_name']):''
						,'ЦенаЗаЕдиницу'  						=> $product['price_national']
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  		=> $product['price']						
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  		=> $product['price']
						,'Количество'     						=> $product['quantity']
						,'НомерПоставки'     					=> $product['delivery_num']
						,'НомерПартии'     					    => $product['part_num']
						,'Количество'     						=> $product['quantity']
						,'Сумма'          						=> $product['total_national']
						,'БонусовЗаЕдиницу'     				=> (int)($product['reward']/$product['quantity'])
						,'Бонусов'     							=> $product['reward']
						,'СуммаВОсновнойВалюте' 				=> $product['total']
						,'ЦенаЗаЕдиницуСУчетомСкидки'    		=> $product['pricewd_national']
						,'СуммаСУчетомСкидки'    		    	=> $product['totalwd_national']						
						,'ЭтотТоварСоСклада'				    => ($product['from_stock'])?'Истина':'Ложь'
						,'КоличествоТовараСоСклада'				=> (int)$product['quantity_from_stock']
						);
						
						$product_counter++;
					}					
				}

				$receipt = $this->Fiscalisation->getOrderReceipt($order['order_id']);

				if ($receipt){
					$receipt_links = $this->Fiscalisation->getReceiptLinks($receipt['receipt_id']);

					$document['Документ']['ПолнаяРеализация']['ФискальныеЧеки']['Чек0'] = array(
						'КодЧекаФискальный'         => $receipt['fiscal_code']
						,'КодЧекаАпи'				=> $receipt['receipt_id']
						,'СистемаАпи'		 		=> $receipt['api']
						,'Статус'	 				=> $receipt['status']
						,'ДатаФискализации'	 		=> $receipt['fiscal_date']
						,'СозданОффлайн'	 		=> $receipt['is_created_offline']?'Истина':'Ложь'
						,'ОтправленВФС'	 			=> $receipt['is_sent_dps']?'Истина':'Ложь'
						,'ДатаОтправкиВФС'	 		=> $receipt['sent_dps_at']?'Истина':'Ложь'
						,'ТипЧека'	 				=> $receipt['type']
						,'СсылкаАпиHTML'      		=> !empty($receipt_links['HTML'])?$receipt_links['HTML']['link']:'Ложь'
						,'СсылкаАпиPDF'      		=> !empty($receipt_links['PDF'])?$receipt_links['PDF']['link']:'Ложь'
						,'СсылкаАпиText'      		=> !empty($receipt_links['TEXT'])?$receipt_links['TEXT']['link']:'Ложь'
						,'СсылкаАпиPNG'      		=> !empty($receipt_links['PNG'])?$receipt_links['PNG']['link']:'Ложь'
						,'СсылкаАпиQRCODE'     		=> !empty($receipt_links['QRCODE'])?$receipt_links['QRCODE']['link']:'Ложь'
					);
				}
				
				$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
				$oXML = new SimpleXMLElement($root);
				$xml = array_to_xml($document, $oXML);
				
				$content = $xml->asXML();
				
				if ($do_echo) {
					header("Content-Type: text/xml");
					
					if ($do_iconv){
						ini_set('mbstring.substitute_character', "none"); 
						$content = mb_convert_encoding($content, 'CP1251', 'UTF-8'); 
					} 
					
					print_r($content);
				}
				
				$_fname = (int)$order_id.'-'.$current_delivery; 
				
				file_put_contents(DIR_EXPORT . 'odinass/sales/' . $_fname . '.xml', $content);
				chmod(DIR_EXPORT . 'odinass/sales/' . $_fname . '.xml', 0777);				
			}
		}
		
		public function getCustomerRewardsHistoryXML($customer_id = 0, $do_echo = false, $dir = false, $do_iconv = false){			
			$this->load->model('sale/customer');
			$this->load->model('sale/order');
		}
				
		public function getOrderXML($order_id = 0, $do_echo = false, $dir = false, $order_array = false, $do_iconv = false) {
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('sale/return');		
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/collection');
			$this->load->model('catalog/parties');
			$this->load->model('localisation/order_status');
			$this->load->model('localisation/country');
			$this->load->model('localisation/currency');
			$this->load->model('localisation/return_reason');
			$this->load->model('localisation/return_action');
			$this->load->model('localisation/return_status');
			$this->load->model('localisation/weight_class');
			$this->load->model('sale/supplier');
			$this->load->model('user/user');
			$this->load->model('setting/setting');
			$this->load->model('tool/image');
			$this->load->model('localisation/legalperson');
			
			if ($order_array && is_array($order_array) && count($order_array)>0) {				
				$orders_string = implode(',', $order_array);
				$query = $this->db->query("SELECT DISTINCT order_id FROM `" . DB_PREFIX . "order` WHERE `order_id` IN (" . $this->db->escape($orders_string) . ")");				
				} else {				
				$query = $this->db->query("SELECT DISTINCT order_id FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "'");				
			}
			
			
			$document = [];
			$document_counter = 0;
			
			if ($query->num_rows) {
				
				foreach ($query->rows as $orders_data) {
					
					$order = $this->model_sale_order->getOrder($orders_data['order_id']);				
					$customer = $this->model_sale_customer->getCustomer($order['customer_id']);
					$order_status = $this->model_localisation_order_status->getOrderStatus($order['order_status_id']);
					
					$country_id = $this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $order['store_id']);
					$country_name = $this->model_localisation_country->getCountryName($country_id);
					
					$last_cheque = $this->model_catalog_parties->getOrderLastCheque($order['order_id']);
					if (!$last_cheque){
						$last_cheque = array(
						'filename' => false
						);
					}
					
					$date = date('Y-m-d', strtotime($order['date_added']));
					$time = date('H:i:s', strtotime($order['date_added']));
					
					$order_histories = $this->model_sale_order->getOrderHistories2($order_id, 0, 100);
					$_dcount = 0;
					foreach ($order_histories as $_h){				
						if ($_h['order_status_id'] == $this->config->get('config_partly_delivered_status_id')){
							$_dcount++;
						}
					}
					
					$processTimes = $this->processTimes($order['order_id']);
					
					$document['Документ' . $document_counter] = array(
					'ИД'                   					=> $order['order_id']
					,'Номер'                				=> $order['order_id']
					,'ВиртуальныйМагазинИд' 				=> $order['store_id']
					,'ВиртуальныйМагазинИмя'				=> $order['store_name']
					,'ВиртуальныйМагазинГруппаМагазинов'	=> SITE_NAMESPACE
					,'ВиртуальныйМагазинСтранаИД'			=> $country_id
					,'ВиртуальныйМагазинСтранаНаименование' => $country_name
					,'Дата'                				 	=> $date
					,'Время'                				=> $time
					,'Валюта'               				=> $order['currency_code']
					,'ВалютаНаименование'      				=> $this->model_localisation_currency->getCurrencyNameByCode($order['currency_code'])
					,'ОсновнаяВалюта'       				=> $this->config->get('config_currency')
					,'ОсновнаяВалютаНаименование'			=> $this->model_localisation_currency->getCurrencyNameByCode($this->config->get('config_currency'))
					,'Курс'                 				=> 1
					,'КурсКОсновнойВалюте'  				=> $order['currency_value']
					,'ХозОперация'          				=> 'Заказ товара'
					,'Роль'                 				=> 'Продавец'
					,'Комментарий'          				=> $order['comment']
					,'ПоследнийЧекФайлПДФ'          		=> $last_cheque['filename']?'/'.$last_cheque['filename']:'Ложь'
					,'Сумма'                				=> $order['total_national']
					,'СуммаВОсновнойВалюте' 				=> $order['total']
					,'Комментарий' 							=> $order['comment']
					
					/*Яндекс Маркет*/
					,'ЭтоЗаказЯндексМаркета' 				=> $order['yam']?'Истина':'Ложь'
					,'ЭтоТестовыйЗаказЯндексМаркета' 		=> $order['yam_fake']?'Истина':'Ложь'
					,'ЯндексМаркетИД' 						=> $order['yam_id']?$order['yam_id']:'Ложь'
					,'ЯндексМаркетДатаОжидаемойОтгрузки'	=> (trim($order['yam_shipment_date']) == '0000-00-00' || !$order['yam_shipment_date'])?'':date('Y-m-d', strtotime($order['yam_shipment_date']))
					,'ЯндексМаркетСтатусЗаказа' 			=> $order['yam_status']?$order['yam_status']:'Ложь'
					,'ЯндексМаркетПодСтатусЗаказа' 			=> $order['yam_substatus']?$order['yam_substatus']:'Ложь'
					,'ЯндексМаркетОтгрузкаИД' 				=> $order['yam_shipment_id']?$order['yam_shipment_id']:'Ложь'
					,'ЯндексМаркетГрузовоеМестоИД' 			=> $order['yam_box_id']?$order['yam_box_id']:'Ложь'
					//Ccылка на стикер заказа
					,'ЯндексМаркетСтикерЗаказПДФ'			=> $order['yam_box_id']?(HTTPS_CATALOG . 'yamarket-partner-api/order/label?order_id=' . $order['order_id']):'Ложь'
					//Ссылка на стикер коробки
					,'ЯндексМаркетСтикерКоробкаПДФ'			=> $order['yam_box_id']?(HTTPS_CATALOG . 'yamarket-partner-api/box/label?box_id=' . $order['yam_box_id']):'Ложь'
					//Ссылка на стикера всей отгрузки
					,'ЯндексМаркетСтикерОтгрузкаПДФ'		=> $order['yam_shipment_id']?(HTTPS_CATALOG . 'yamarket-partner-api/shipment/label?shipment_id=' . $order['yam_shipment_id']):'Ложь'
					,'ЯндексМаркетАктПриемаПередачиНаСегодняПДФ' 	=> (trim($order['yam_shipment_date']) == '0000-00-00' || !$order['yam_shipment_date'])?'':(HTTPS_CATALOG . 'yamarket-partner-api/acts/receptiontransferact')
					
					/* Даты заказа */	
					,'ДатаЗаказаУПоставщика' 						=> (trim($order['date_buy']) == '0000-00-00')?'':date('Y-m-d', strtotime($order['date_buy']))
					,'ДатаПрибытияВСтрануОриентировочно'			=> (trim($order['date_country']) == '0000-00-00')?'':date('Y-m-d', strtotime($order['date_country']))
					,'ДатаДоставкиОтОриентировочно'					=> (trim($order['date_delivery']) == '0000-00-00')?'':date('Y-m-d', strtotime($order['date_delivery']))
					,'ДатаДоставкиДоОриентировочно' 				=> (trim($order['date_delivery_to']) == '0000-00-00')?'':date('Y-m-d', strtotime($order['date_delivery_to']))
					,'ФактическаяДатаДоставкиНаКоторуюДоговорились' => (trim($order['date_delivery_actual']) == '0000-00-00')?'':date('Y-m-d', strtotime($order['date_delivery_actual']))
					,'ДатаОплатыДляДействительностиУсловий'			=> (trim($order['date_maxpay']) == '0000-00-00')?'':date('Y-m-d', strtotime($order['date_maxpay']))
					/* Даты заказа */
					
					/* Даты заказа2 */
					,'ДатаЗаказаОформлен' 					=> (trim($processTimes['date_added']) == '0000-00-00')?'':date('Y-m-d', strtotime($processTimes['date_added']))
					,'ДатаЗаказаПодтвержден'				=> $processTimes['date_accepted'] ? ( (trim($processTimes['date_accepted']) == '0000-00-00')?'':date('Y-m-d', strtotime($processTimes['date_accepted'])) ):'Ложь'
					,'ДатаЗаказаВыполнен'			   		=> $processTimes['date_closed'] ?( (trim($processTimes['date_closed']) == '0000-00-00')?'':date('Y-m-d', strtotime($processTimes['date_closed'])) ):'Ложь'
					,'ДатаЗаказаОтменен'			   		=> $processTimes['date_cancelled'] ? ( (trim($processTimes['date_cancelled']) == '0000-00-00')?'':date('Y-m-d', strtotime($processTimes['date_cancelled'])) ):'Ложь'
					/* Даты заказа */	
					
					
					,'СпособДоставкиКод'   					=> $order['shipping_code']
					,'СпособДоставкиНаименование'   		=> $order['shipping_method']
					,'СпособОплатыКод'   					=> $order['payment_code']
					,'СпособОплатыНаименование'   	    	=> $order['payment_method']
					,'ТипОплатыНаименование'	   	    	=> ($order['pay_type'])?$order['pay_type']:'Неизвестно'
					,'СтатусЗаказаИД' 	    				=> $order['order_status_id']
					,'СтатусЗаказаНаименование'   			=> $order_status['name']
					,'ЭтотЗаказОтгруженПолностью'  			=> ($order['order_status_id'] == $this->config->get('config_complete_status_id'))?'Истина':'Ложь'
					,'ЭтотЗаказОтгруженЧастично'  			=> ($order['order_status_id'] != $this->config->get('config_complete_status_id') && $_dcount > 0)?'Истина':'Ложь'
					,'КоличествоЧастичныхОтгрузок'  		=> $_dcount
					,'ЭтотЗаказБылОтменен'   				=> (($order['order_status_id'] == $this->config->get('config_cancelled_status_id')) || ($order['order_status_id'] == $this->config->get('config_cancelled_after_status_id')))?'Истина':'Ложь'
					,'ЭтотЗаказБылОтмененДоОтгрузки'   		=> ($order['order_status_id'] == $this->config->get('config_cancelled_status_id'))?'Истина':'Ложь'
					,'ЭтотЗаказБылОтмененПослеОтгрузки'   	=> ($order['order_status_id'] == $this->config->get('config_cancelled_after_status_id'))?'Истина':'Ложь'
					,'ЭтоПартнерскийЗаказ'					=> $order['affiliate_id']?'Истина':'Ложь'
					,'ПартнерИД'							=> $order['affiliate_id']?$order['affiliate_id']:'Ложь'
					,'ПартнерНаименование'					=> ($order['affiliate_id'] ? $order['affiliate_firstname'] . ' ' . $order['affiliate_lastname'] : 'Ложь')
					,'ЭтоСрочныйЗаказ'					    => $order['urgent']?'Истина':'Ложь'
					,'ЛогистикаВРоссиюЧерезУкраину'			=> $order['ua_logistics']?'Истина':'Ложь'
					,'ЭтоЗаказCПриоритетомВЗакупке'		    => $order['urgent_buy']?'Истина':'Ложь'
					,'ЭтотЗаказЖдетПолнойКомплектации'		=> $order['wait_full']?'Истина':'Ложь'
					);
					
					//Логика отдачи ссылок на оплату
					$document['Документ' . $document_counter]['СсылкиДляQRНаОплату'] = [];
					$payment_links_counter = 0;
					
					//Пейкипер если 'RUB', 'BYN', 'KZT'
					if (in_array($order['currency_code'], array('RUB', 'BYN', 'KZT'))){
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'paykeeper',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'paykeeper')
						);
						$payment_links_counter++;						
					}
					
					//Пейпал если 'RUB', 'EUR'
					if (in_array($order['currency_code'], array('RUB', 'EUR'))) {
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'pp_express',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'pp_express')
						);
						$payment_links_counter++;
					}
					
					//ЛикПей если 'UAH'
					if (in_array($order['currency_code'], array('UAH'))) {
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'liqpay',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'liqpay')
						);
						$payment_links_counter++;
					}
					
					//Моно если 'UAH'
					if (in_array($order['currency_code'], array('UAH'))) {
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'mono',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'mono')
						);
						$payment_links_counter++;
					}

					//ЛикПей если 'UAH'
					if (in_array($order['currency_code'], array('UAH'))) {
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'wayforpay',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'wayforpay')
						);
						$payment_links_counter++;
					}
					
					
					//Конкардис в национальной валюте
					if (true) {
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'concardis',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'concardis')
						);
						$payment_links_counter++;
					}
					
					//Не помню нахуя, но конкардис в Евро для РФ и Укр (а, вспомнил, это (с)АВ, "курсовая скидка")
					if (in_array($order['currency_code'], array('RUB', 'UAH'))) {
						$document['Документ' . $document_counter]['СсылкиДляQRНаОплату']['СсылкаДляQRНаОплату' . $payment_links_counter] = array(
						'КодПлатежнойСистемы' => 'concardiseur',
						'КороткаяСсылка'	  => $this->model_sale_order->generatePaymentLink($order_id, 'concardis', 'EUR')
						);
						$payment_links_counter++;
					}
					
					if ($order['legalperson_id']){
						if ($legalperson = $this->model_localisation_legalperson->getLegalPerson($order['legalperson_id'])){																			
							$document['Документ' . $document_counter]['ЮридическоеЛицо'] = array(
							'ИД'                   					=> $legalperson['legalperson_id']
							,'Наименование'                			=> $legalperson['legalperson_name']
							,'ЭтоЮрЛицоДляВыставленияСчетов'    	=> $legalperson['legalperson_legal']?'Истина':'Ложь'
							,'РеквизитыПолучателя'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_desc']))
							,'РеквизитыПолучателя2'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_additional']))
							,'ПривязкаКСтранеИД' 				    => $legalperson['legalperson_country_id']
							,'ПривязкаКСтранеИмя' 				    => $country_name = $this->model_localisation_country->getCountryName($legalperson['legalperson_country_id'])
							);
							
							if ($legalperson['legalperson_additional']){
								$unpacked_struct = [];
								$info = explode(PHP_EOL, $legalperson['legalperson_additional']);
								foreach ($info as $string){
									$line = explode(':', $string);
									if (isset($line[0]) && isset($line[1])){
										$unpacked_struct[removeSpaces($line[0])] = trim($line[1]);
									}
								}
								
								foreach ($unpacked_struct as $uns => $unsval){
									$document['Документ' . $document_counter]['ЮридическоеЛицо']['РеквизитыСтруктурa'][rms($uns)] = rms($unsval);						
								}
								
							}
						}						
					}
					
					if ($order['card_id']){
						if ($legalperson = $this->model_localisation_legalperson->getLegalPerson($order['card_id'])){																			
							$document['Документ' . $document_counter]['КартаДляОплаты'] = array(
							'ИД'                   					=> $legalperson['legalperson_id']
							,'Наименование'                			=> $legalperson['legalperson_name']
							,'ЭтоЮрЛицоДляВыставленияСчетов'    	=> $legalperson['legalperson_legal']?'Истина':'Ложь'
							,'РеквизитыПолучателя'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_desc']))
							,'РеквизитыПолучателя2'  	         	=> rms(reparseEOLSToSlash($legalperson['legalperson_additional']))
							,'ПривязкаКСтранеИД' 				    => $legalperson['legalperson_country_id']
							,'ПривязкаКСтранеИмя' 				    => $country_name = $this->model_localisation_country->getCountryName($legalperson['legalperson_country_id'])
							);
							
							if ($legalperson['legalperson_additional']){
								$unpacked_struct = [];
								$info = explode(PHP_EOL, $legalperson['legalperson_additional']);
								foreach ($info as $string){
									$line = explode(':', $string);
									if (isset($line[0]) && isset($line[1])){
										$unpacked_struct[removeSpaces($line[0])] = trim($line[1]);
									}
								}
								
								foreach ($unpacked_struct as $uns => $unsval){
									$document['Документ' . $document_counter]['КартаДляОплаты']['РеквизитыСтруктурa'][rms($uns)] = rms($unsval);						
								}
								
							}
						}						
					}
					
					
					$related_orders = $this->model_sale_order->getRelatedOrders($orders_data['order_id']);
					$related_orders_counter = 0;
					
					foreach ($related_orders as $related_order){
						$document['Документ' . $document_counter]['ЗаказыНадоДоставитьВместеСДаннымЗаказом']['ЗаказНадоДоставитьВместе'.$related_orders_counter] = array(
						'ИД' => $related_order
						,'ЭтотЗаказСуществует' => $this->model_sale_order->getOrder($related_order)?'Истина':'Ложь'
						);	
						
						$related_orders_counter++;
					}
					
					//ТТНКИ
					if ($ttns = $this->model_sale_order->getOrderTtnHistory($order['order_id'])){						
						$ttn_counter = 0;
						foreach ($ttns as $ttn){
							$document['Документ' . $document_counter]['ТоварноТранспортныеНакладные']['ТТН'.$ttn_counter] = array(
							'ТоварноТранспортнаяНакладная' 	=> $ttn['ttn'],
							'ДатаОтправки'					=> $ttn['date_ttn'],
							'КодСлужбыОтправки'				=> $ttn['delivery_code'],
							'НаверноеЗабранаНоЭтоНеТочно'	=> $ttn['taken'],
							);
							
							$ttn_counter++;
						}												
					}
					
					
					
					$order_totals = $this->model_sale_order->getOrderTotals($orders_data['order_id']);				
					
					$totals_counter = 0;
					foreach ($order_totals as $order_total){
						
						$voucher = (bool_real_stripos($order_total['title'], 'сертификат') || $order_total['code'] == 'voucher');
						
						$document['Документ' . $document_counter]['ИтогиЗаказа']['Итог'.$totals_counter] = array(
						'ИД'                    	=> $order_total['order_total_id']
						,'Наименование'         	=> $order_total['title']
						,'Код'                  	=> $order_total['code']
						,'Валюта'               	=> $order['currency_code']
						,'Сумма'                	=> $order_total['value_national']
						,'СуммаВОсновнойВалюте' 	=> $order_total['value']
						,'ТекстовоеЗначение'        => $order_total['text']
						,'ЭтоСкидка'             	=> ($order_total['value_national'] < 0)?'Истина':'Ложь'
						,'ЭтоСертификат'    		=> $voucher?'Истина':'Ложь'
						,'КодСертификатаБезПробелов'=> $voucher?(preg_replace('/\D/', '', $order_total['title'])?preg_replace('/\D/', '', $order_total['title']):'Ложь'):'Ложь'
						,'ЭтоСтоимостьДоставки'    	=> (bool_real_stripos($order_total['title'], 'Доставка') || $order_total['code'] == 'shipping')?'Истина':'Ложь'
						,'ЭтоСуммаПредоплаты'		=> (bool_real_stripos($order_total['title'], 'Предоплата') || $order_total['code'] == 'transfer_plus_prepayment')?'Истина':'Ложь'
						,'ЭтоСуммаПолнойПредоплаты'	=>	'Ложь'
						//	,'ЭтоПроцентнаяСкидка'      => ($order_total['value_national'] < 0)?'Истина':'Ложь'
						,'ЭтоПодИтог'            	=> ($order_total['code'] == 'sub_total')?'Истина':'Ложь'
						,'ЭтоОкончательныйИтог'  	=> ($order_total['code'] == 'total')?'Истина':'Ложь'
						,'ЭтоИспользованиеБонусов'  => ($order_total['code'] == 'reward')?'Истина':'Ложь'
						
						);
						
						if ($order_total['code'] == 'total'){
							$full_prepay_total = $order_total;
						}
						
						$totals_counter++;
					}
					
					if ($order['payment_code'] == 'cod') {
						//виртуальная полная предоплата
						$document['Документ' . $document_counter]['ИтогиЗаказа']['Итог'.$totals_counter] = array(
						'ИД'                    	=> 'virtual_total'
						,'Наименование'         	=> 'Сумма полной предоплаты'
						,'Код'                  	=> 'cod'
						,'Валюта'               	=> $order['currency_code']
						,'Сумма'                	=> $full_prepay_total['value_national']
						,'СуммаВОсновнойВалюте' 	=> $full_prepay_total['value']
						,'ЭтоСуммаПолнойПредоплаты'	=> 'Истина'
						,'ЭтоСкидка'             	=> 'Ложь'
						,'ЭтоСтоимостьДоставки'    	=> 'Ложь'
						,'ЭтоСуммаПредоплаты'		=> 'Истина'
						,'ЭтоПодИтог'            	=> 'Ложь'
						,'ЭтоОкончательныйИтог'  	=> 'Ложь'
						);
					}
					
					$order_histories = $this->model_sale_order->getOrderHistories2($order_id, 0, 100);
					
					$order_histories_counter = 0;
					foreach ($order_histories as $order_history){
						$oh_date = date('Y-m-d', strtotime($order_history['date_added']));
						$oh_time = date('H:i:s', strtotime($order_history['date_added']));
						
						$document['Документ' . $document_counter]['ИсторияСменыСтатусовЗаказа']['СменаСтатусаЗаказа'.$order_histories_counter] = array(
						'ИД'                   		 	=> $order_history['order_history_id']
						,'Дата'             		 	=> $oh_date
						,'Время'             		 	=> $oh_time		
						,'Комментарий'          	 	=> $order_history['comment']
						,'ЭтоКомментарийВЧекДляКурьера' => $order_history['courier']?'Истина':'Ложь'
						,'СтатусЗаказаИД'	 		 	=> $order_history['order_status_id']								
						,'СтатусЗаказаНаименование'	 	=> $order_history['status']	
						
						);
						
						$order_histories_counter++;
					}															
					
					$order_transactions = $this->model_sale_customer->getTransactions($order['customer_id'], 0, 10000, $orders_data['order_id']);	
					
					$transactions_counter = 0;
					foreach ($order_transactions as $order_transaction){
						$ot_date = date('Y-m-d', strtotime($order_transaction['date_added']));
						$ot_time = date('H:i:s', strtotime($order_transaction['date_added']));
						
						
						$document['Документ' . $document_counter]['ФинансовыеТранзакцииЗаказа']['ФинансоваяТранзакцияЗаказа'.$transactions_counter] = array(
						'ИД'                    		=> $order_transaction['customer_transaction_id']
						,'ГУИД'                    		=> !empty($order_transaction['guid'])?$order_transaction['guid']:'Ложь'
						,'Дата'             			=> $ot_date
						,'Время'             			=> $ot_time		
						,'Описание'             		=> $order_transaction['description']						
						,'Валюта'               		=> $order_transaction['currency_code']
						,'Сумма'                		=> $order_transaction['amount_national']
						,'СуммаВОсновнойВалюте'			=> $order_transaction['amount']
						,'КодИсточника'					=> $order_transaction['added_from']
						,'КодКассы'						=> $order_transaction['legalperson_id']?$order_transaction['legalperson_id']:'Ложь'
						,'НаименованиеКассы'			=> $order_transaction['legalperson_name_1C']?$order_transaction['legalperson_name_1C']:'Ложь'
						,'НаименованиеКассы2'			=> $order_transaction['legalperson_name']?$order_transaction['legalperson_name']:'Ложь'
						,'ЭтоРучноеВнесение'			=> ($order_transaction['added_from'] == 'manual_admin')?'Истина':'Ложь'
						,'ЭтоАвтоСписаниеПоВыполнен'	=> ($order_transaction['added_from'] == 'auto_order_close')?'Истина':'Ложь'
						,'JSONПлатежнойСистемы'			=> $order_transaction['json']
						);
						
						$transactions_counter++;
					}
					
					$order_total_transaction_national = $this->model_sale_customer->getTransactionTotalNational($order['customer_id'], $orders_data['order_id']);	
					$order_total_transaction = $this->model_sale_customer->getTransactionTotal($order['customer_id'], $orders_data['order_id']);	
					
					$document['Документ' . $document_counter]['ФинансовыеТранзакцииЗаказа']['ФинансоваяЗадолженностьПоЗаказуНаДанныйМомент'] = array(		
					'Валюта'               => $order['currency_code']
					,'Сумма'                => $order_total_transaction_national?$order_total_transaction_national:'0.0000'
					,'СуммаВОсновнойВалюте' => $order_total_transaction?$order_total_transaction:'0.0000'
					
					);													
					
					$return_filter_data = array(
					'filter_order_id' => $orders_data['order_id']
					);
					$order_returns = $this->model_sale_return->getReturns($return_filter_data);
					$returns_counter = 0;
					
					foreach ($order_returns as $order_return){
						$or_date = date('Y-m-d', strtotime($order_return['date_added']));
						$or_time = date('H:i:s', strtotime($order_return['date_added']));
						
						$document['Документ' . $document_counter]['ВозвратыПоЗаказу']['Возврат'.$returns_counter] = array(
						'ИД'                    			=> $order_return['return_id']							
						,'Дата'             				=> (isset($ot_date))?$ot_date:$date
						,'Время'			             	=> (isset($ot_time))?$ot_time:$time
						,'ТоварИД'							=> $order_return['product_id']
						,'ТоварАртикул'						=> $order_return['model']
						,'ТоварАртикулНормализованный'		=> normalizeSKU($order_return['model'])
						,'Описание'             			=> $order_return['comment']						
						,'Валюта'              				=> $order['currency_code']
						,'ЦенаЗаЕдиницу'  					=> $order_return['price_national']
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  	=> $order_return['price']
						,'Количество'     					=> $order_return['quantity']
						,'Сумма'          					=> $order_return['total_national']
						,'СуммаВОсновнойВалюте' 			=> $order_return['total']
						,'СтатусВозвратаИД'  				=> $order_return['return_status_id']
						,'ПричинаВозвратаИД'				=> $order_return['return_reason_id']
						,'СтатусВозврата'   				=> $order_return['status']
						,'ПричинаВозврата'					=> $order_return['reason']
						,'ЭтоВозвратПоставщику'  			=> ($order_return['to_supplier']==1)?'Истина':'Ложь'
						,'ЭтоВозвратКлиенту'  			    => ($order_return['to_supplier']==0)?'Истина':'Ложь'
						,'ЭтоОтказДоОтгрузки'  			    => ($order_return['to_supplier']==2)?'Истина':'Ложь'
						
						);
						
						
						$returns_counter++;
					}
					
					
					
					$document['Документ' . $document_counter]['Менеджеры']['Менеджер'] = array(
					'ИД'     			  =>  $order['manager_id']
					,'Роль'               => 'Менеджер по продажам'
					,'Наименование'		  =>  $this->model_user_user->getRealUserNameById($order['manager_id'])
					,'НаименованиеКороткое'	  =>  $this->model_user_user->getUserNameById($order['manager_id'])
					);
					
					if ($order['payment_address_struct']){
						
					}
					
					$document['Документ' . $document_counter]['Контрагенты']['Контрагент'] = array(
					'ИД'                 	=> $order['customer_id']
					,'Роль'               	=> 'Покупатель'
					,'Наименование'		  	=> $order['lastname'] . ' ' . $order['firstname']
					,'ПолноеНаименование' 	=> $order['lastname'] . ' ' . $order['firstname']
					,'Имя'			      	=> $order['firstname']
					,'Фамилия'            	=> $order['lastname']
					,'ДисконтнаяКарта'    	=> $customer['discount_card']
					,'Контакты' 			=> array(
						'Контакт1' 	=> array(
						'Тип' 		=> 'ТелефонРабочий'
						,'Значение'	=> $order['telephone']
						)
					,'Контакт2' 			=> array(
						'Тип' 		=> 'ТелефонДополнительный'
						,'Значение'	=> $order['fax']
						)
					,'Контакт2'				=> array(
						'Тип' => 'Почта'
						,'Значение'	=> $order['email']
						)
					)										
					
					
					,'ИмяОплаты'		=> $order['payment_firstname']
					,'ФамилияОплаты'    => $order['payment_lastname']
					,'ТелефонОплаты' 	=> $order['telephone']
					,'АдресОплаты' 		=> array(
					'Представление'		=> (($order['payment_address_1'])?$order['payment_address_1'].', ':'') . (($order['payment_address_2'])?$order['payment_address_2'].', ':'') . $order['payment_city'] . ', ' . (($order['payment_postcode'])?$order['payment_postcode'].', ':'') . (($order['payment_zone'])?$order['payment_zone'].', ':'') .$order['payment_country']
					,'АдресДетально'   	=> $this->parseDaDataFullJSON($order['payment_address_struct'])
					,'АдреснаяСтрока1' 	=> $order['payment_address_1']
					,'АдреснаяСтрока2' 	=> $order['payment_address_2']
					,'Город' 			=> $order['payment_city']
					,'Страна' 			=> $order['payment_country']	
					,'Область' 			=> $order['payment_zone']
					)
					
					,'ИмяДоставки'		  	=> $order['shipping_firstname']
					,'ФамилияДоставки'    	=> $order['shipping_lastname']
					,'ТелефонДоставки' 	  	=> $order['fax']
					,'АдресДоставки' 		=> array(
					'Представление'			=> (($order['shipping_address_1'])?$order['shipping_address_1'].', ':'') . (($order['shipping_address_2'])?$order['shipping_address_2'].', ':'') . $order['shipping_city'].', '. (($order['shipping_postcode'])?$order['shipping_postcode'].', ':'') . (($order['shipping_zone'])?$order['shipping_zone'].', ':'') . $order['shipping_country']
					,'АдресДетально'   	=> $this->parseDaDataFullJSON($order['shipping_address_struct'])
					,'АдреснаяСтрока1' 	=> $order['shipping_address_1']
					,'АдреснаяСтрока2' 	=> $order['shipping_address_2']
					,'Город' 			=> $order['shipping_city']
					,'Страна' 			=> $order['shipping_country']
					,'Область' 			=> $order['shipping_zone']
					)
					
					);
					
					$customer_rewards = $this->customer->getAllRewards($order['customer_id']);	
					$customer_rewards_counter = 0;
					foreach ($customer_rewards as $customer_reward){
						$ct_date = date('Y-m-d', strtotime($customer_reward['date_added']));
						$ct_time = date('H:i:s', strtotime($customer_reward['date_added']));
						
						$document['Документ' . $document_counter]['Контрагенты']['Контрагент']['БонусныеТранзакцииКонтрагента']['БонуснаяТранзакцияКонтрагента'.$customer_rewards_counter] = array(
						'ИД'                    		=> $customer_reward['customer_reward_id']						
						,'Дата'             			=> $ct_date
						,'Время'             			=> $ct_time
						,'Описание'             		=> $customer_reward['description']						
						,'КодИсточника'               	=> $customer_reward['reason_code']
						,'НомерЗаказа'                	=> $customer_reward['order_id']
						,'Бонусы'				 		=> $customer_reward['points']
						,'ИзНихИспользовано'	 		=> $customer_reward['points_paid']
						,'ИзНихАктивно'			 		=> ($customer_reward['points'] - $customer_reward['points_paid'])
						,'ЭтоРучноеВнесение'	 		=> $customer_reward['reason_code'] == 'MANUAL'?'Истина':'Ложь'
						,'ЭтоПлатежПоЗаказу'	 		=> $customer_reward['reason_code'] == 'ORDER_PAYMENT'?'Истина':'Ложь'	
						,'ЭтоНачислениеПоЗаказу'	 	=> $customer_reward['reason_code'] == 'ORDER_COMPLETE_ADD'?'Истина':'Ложь'
						,'ЭтоВозвратПриПолнойОтмене'	=> $customer_reward['reason_code'] == 'ORDER_RETURN'?'Истина':'Ложь'
						);
						
						$customer_rewards_counter++;
					}
					
					$customer_rewards_total = $this->customer->getRewardTotal($order['customer_id']);	
					
					$document['Документ' . $document_counter]['Контрагенты']['Контрагент']['БонусныеТранзакцииКонтрагента']['БонусовНаСчетуКлиентаВТекущийМомент'] = array(		
					'Бонусы'               => $customer_rewards_total
					);	
					
					
					$customer_transactions = $this->model_sale_customer->getTransactions($order['customer_id'], 0, 10000);	
					
					$customer_transactions_counter = 0;
					foreach ($customer_transactions as $customer_transaction){
						$ct_date = date('Y-m-d', strtotime($customer_transaction['date_added']));
						$ct_time = date('H:i:s', strtotime($customer_transaction['date_added']));
						
						$document['Документ' . $document_counter]['Контрагенты']['Контрагент']['ФинансовыеТранзакцииКонтрагента']['ФинансоваяТранзакцияКонтрагента'.$customer_transactions_counter] = array(
						'ИД'                    		=> $customer_transaction['customer_transaction_id']
						,'ГУИД'                    		=> !empty($customer_transaction['guid'])?$customer_transaction['guid']:'Ложь'
						,'Дата'             			=> $ct_date
						,'Время'             			=> $ct_time
						,'Описание'             		=> $customer_transaction['description']						
						,'Валюта'               		=> $customer_transaction['currency_code']
						,'Сумма'                		=> $customer_transaction['amount_national']
						,'СуммаВОсновнойВалюте' 		=> $customer_transaction['amount']
						,'КодИсточника'					=> $customer_transaction['added_from']
						,'КодКассы'						=> $customer_transaction['legalperson_id']?$customer_transaction['legalperson_id']:'Ложь'
						,'НаименованиеКассы'			=> $customer_transaction['legalperson_name_1C']?$customer_transaction['legalperson_name_1C']:'Ложь'
						,'НаименованиеКассы2'			=> $customer_transaction['legalperson_name']?$customer_transaction['legalperson_name']:'Ложь'
						,'ЭтоРучноеВнесение'			=> ($customer_transaction['added_from'] == 'manual_admin')?'Истина':'Ложь'
						,'ЭтоАвтоСписаниеПоВыполнен'	=> ($customer_transaction['added_from'] == 'auto_order_close')?'Истина':'Ложь'	
						,'JSONПлатежнойСистемы'			=> $customer_transaction['json']
						);
						
						$customer_transactions_counter++;
					}
					
					$customer_total_transaction_national = $this->model_sale_customer->getTransactionTotalNational($order['customer_id'], false);	
					$customer_total_transaction = $this->model_sale_customer->getTransactionTotal($order['customer_id'], false);	
					
					$document['Документ' . $document_counter]['Контрагенты']['Контрагент']['ФинансовыеТранзакцииКонтрагента']['ФинансоваяЗадолженностьКонтрагентаНаДанныйМомент'] = array(		
					'Валюта'               => $order['currency_code']
					,'Сумма'                => $customer_total_transaction_national?$customer_total_transaction_national:'0.0000'
					,'СуммаВОсновнойВалюте' => $customer_total_transaction?$customer_total_transaction:'0.0000'
					
					);		
					
					// Товары
					$products = $this->model_sale_order->getOrderProducts($orders_data['order_id'], true, 'op.price_national ASC');		
					
					$product_counter = 0;
					foreach ($products as $product) {
						$real_product 				= $this->model_catalog_product->getProduct($product['product_id']);
						$real_product_descriptions 	= $this->model_catalog_product->getProductDescriptions($product['product_id']);
						$weight_class 				= $this->model_localisation_weight_class->getWeightClass($real_product['weight_class_id']);
						$weight_full_class 			= $this->model_localisation_weight_class->getWeightClass($real_product['pack_weight_class_id']);
						$manufacturer 				= $this->model_catalog_manufacturer->getManufacturer($real_product['manufacturer_id']);						
						$is_set 					= $this->model_catalog_product->getThisProductIsSet($product['product_id']);
						
						if (empty($product['quantity'])){
							$product['quantity'] = 1;
						}

						if (!$manufacturer || !isset($manufacturer['name'])){
							$manufacturer['name'] = 'Неизвестно';
						}
						
						if ($product['ao_id']){
							$ao_info = $this->model_catalog_product->getAdditionalOfferById($product['ao_id'], $product['product_id']);
							
							if ($ao_info && count($ao_info)>0) {
								$ao_main_product = $this->model_catalog_product->getProduct($ao_info['product_id']);
								$ao_main_product_id = $ao_main_product['product_id'];
								$ao_main_product_model = $ao_main_product['model'];
								$ao_quantity = $ao_info['quantity'];
								} else {
								$ao_main_product_id = false;
								$ao_main_product_model = false;
								$ao_quantity = false;
							}
							} else {
							$ao_main_product_id = false;
							$ao_main_product_model = false;
							$ao_quantity = false;
						}
						
						if (!$real_product['tnved'] || mb_strlen($real_product['tnved']) < 2){
							$real_product['tnved'] = $this->model_catalog_product->getProductTNVEDByCategory($product['product_id']);
						}

						//short_name new logic, 06.01.2023
						$short_name = $short_name_de = '';
						if (!empty($real_product_descriptions[$this->registry->get('languages')[$this->config->get('config_language')]['language_id']])){
							$short_name = $real_product_descriptions[$this->registry->get('languages')[$this->config->get('config_language')]['language_id']]['short_name_d'];
						}

						if (!empty($real_product_descriptions[$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id']])){
							$short_name_de = $real_product_descriptions[$this->registry->get('languages')[$this->config->get('config_de_language')]['language_id']]['short_name_d'];
						}

						if (!$short_name){
							$short_name = $product['short_name'];
						}

						if (!$short_name_de){
							$short_name_de = $product['short_name_de'];
						}

						if (!$short_name){
							$short_name = $product['name'];
						}
										
						
						$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter] = array(
						'ИД'            						=> $product['product_id']
						,'ИДЗаписиЗаказТовар'					=> $product['order_product_id']
						,'Артикул'		 						=> $product['model']
						,'АртикулНормализованный'				=> normalizeSKU($product['model'])
						,'Наименование'   						=> rms($product['name'])	
						,'НаименованиеУКР'   					=> !empty($product['ua_name'])?rms($product['ua_name']):''
						,'ВесТовара'							=> $real_product['weight']
						,'ВесТовараВКилограммах'				=> $this->weight->convert($real_product['weight'], $real_product['weight_class_id'], 1)
						,'ВесТовараЕдиницаИД'   				=> $real_product['weight_class_id']
						,'ВесТовараЕдиницаНаименование'			=> isset($weight_class['title'])?$weight_class['title']:'Ложь'
						,'ВесТовараЕдиницаОбозначение'			=> isset($weight_class['unit'])?$weight_class['unit']:'Ложь'
						,'ВесТовараПолный'						=> $real_product['pack_weight']
						,'ВесТовараПолныйВКилограммах'			=> $this->weight->convert($real_product['pack_weight'], $real_product['pack_weight_class_id'], 1)
						,'ВесТовараПолныйЕдиницаИД'   			=> $real_product['pack_weight_class_id']
						,'ВесТовараПолныйЕдиницаНаименование'	=> isset($weight_full_class['title'])?$weight_full_class['title']:'Ложь'
						,'ВесТовараПолныйЕдиницаОбозначение'	=> isset($weight_full_class['unit'])?$weight_full_class['unit']:'Ложь'
						,'НаименованиеКороткое'   				=> rms($short_name)
						,'НаименованиеНаЯзыкеБренда'			=> rms($product['de_name'])
						,'НаименованиеКороткоеНаЯзыкеБренда'	=> rms($short_name_de)
						,'МинимумКЗаказу'  						=> $real_product['minimum']
						,'КоличествоВУпаковке'					=> $real_product['package']
						,'БрендИД'       						=> $real_product['manufacturer_id']
						,'БрендНаименование'        			=> rms($manufacturer['name'])
						,'БрендНаименованиеMD5Хэш' 				=> md5($manufacturer['name'])
						,'БрендНаименованиеНормализованноеMD5Хэш' => md5(rms($manufacturer['name']))
						,'ЦенаЗаЕдиницу'  						=> $product['price_national']
						,'ЦенаЗаЕдиницуВОсновнойВалюте'  		=> $product['price']											
						,'Количество'     						=> $product['quantity']
						,'НомерПоставки'     					=> $product['delivery_num']
						,'НомерПартии'     					    => $product['part_num']
						,'Количество'     						=> $product['quantity']
						,'БонусовЗаЕдиницу'     				=> (int)($product['reward']/$product['quantity'])
						,'Бонусов'     							=> $product['reward']
						,'Сумма'          						=> $product['total_national']
						,'СуммаВОсновнойВалюте' 				=> $product['total']
						,'ЦенаЗаЕдиницуСУчетомСкидки'    		=> $product['pricewd_national']
						,'СуммаСУчетомСкидки'    		    	=> $product['totalwd_national']						
						,'ИзображениеСсылка'					=> $this->model_tool_image->resize($real_product['image'], 150, 150)
						,'ЭтоCпецПредложение' 					=> ($product['ao_id'])?'Истина':'Ложь'
						,'СпецПредложениеДляИД'					=> ($ao_main_product_id)?$ao_main_product_id:'Ложь'
						,'СпецПредложениеДляАртикул'			=> ($ao_main_product_model)?$ao_main_product_model:'Ложь'
						,'СпецПредложениеСоотношениеКоличества'	=> ($ao_quantity)?('1:'.$ao_quantity):'Ложь'
						,'ЭтоНаборТоваров' 						=> ($is_set)?'Истина':'Ложь'
						,'НаборТоваровИД'						=> ($is_set)?$is_set['set_id']:'Ложь'
						,'ЭтотТоварБылВозвращенПолностью'		=> ($product['is_returned'])?'Истина':'Ложь'
						,'ЭтотТоварСоСклада'				    => ($product['from_stock'])?'Истина':'Ложь'
						,'КоличествоТовараСоСклада'				=> (int)$product['quantity_from_stock']
						,'ИсточникПокупкиУказанныйЗакупщиком'   => rms(htmlentities($product['source']))
						,'МониторингЦенВключен'    		    	=> $real_product['priceva_enable']?'Истина':'Ложь'
						,'МониторингЦенВыключен'    		    => $real_product['priceva_disable']?'Истина':'Ложь'
						,'ЯМаркетТоварВФиде'    		    	=> $real_product['yam_in_feed']?'Истина':'Ложь'		
						,'ЯМаркетТоварСкрыт'    		    	=> $real_product['yam_hidden']?'Истина':'Ложь'		
						,'ЯМаркетТоварНеСоздан'    		    	=> $real_product['yam_not_created']?'Истина':'Ложь'		
						,'ЭтоНеликид'    		    			=> $real_product['is_illiquid']?'Истина':'Ложь'
						,'АмазонДобавлен'    		    		=> $real_product['added_from_amazon']?'Истина':'Ложь'			
						,'АмазонНеверныйASIN'    		    	=> $real_product['amzn_invalid_asin']?'Истина':'Ложь'				
						,'АмазонТоварНеНайден'    		    	=> $real_product['amzn_not_found']?'Истина':'Ложь'		
						,'АмазонДатаПоследнегоПоиска'    		=> $real_product['amzn_last_search']		
						,'АмазонНетПредложений'    		    	=> $real_product['amzn_no_offers']?'Истина':'Ложь'	
						,'АмазонДатаПоследнихПредложений'   	=> $real_product['amzn_last_offers']
						,'АмазонИгнорировать'    		    	=> $real_product['amzn_ignore']?'Истина':'Ложь'	
						,'АмазонЛучшаяЦенаЗначение'  		    => $real_product['amazon_best_price']
						
						);
						
						if ($product['totals_json'] && json_decode($product['totals_json']) && is_array(json_decode($product['totals_json'], true))){
							
							$discount_counter = 0;							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['СкидкиДействующиеНаТовар'] = [];
							
							
							$discounts_array = json_decode($product['totals_json'], true);
							
							$discount_sums_array = [];
							$discount_total_sums_array = [];
							
							foreach ($discounts_array as $discount_field){															
								
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['СкидкиДействующиеНаТовар']['Скидка' . $discount_counter] = array(
								'Код' 						=> $discount_field['code'],
								'Наименование' 				=> $discount_field['title'],
								'ДействуетНаПоставкуНомер' 	=> $discount_field['for_delivery']?$discount_field['for_delivery']:'Ложь',
								'СкидкаНаОдинТовар' 		=> $discount_field['discount'],
								'СкидкаНаВсеТовары' 		=> $discount_field['discount_total']
								);
								
								$discount_sums_array[] = $discount_field['discount'];
								$discount_total_sums_array[] = $discount_field['discount_total'];
								
								$discount_counter ++;
							}
							
							$_ppnlleft = $product['price_national'];
							$_ptnlleft = $product['total_national'];
							foreach ($discount_sums_array as $_dsa){
								$_ppnlleft -= (float)$_dsa;																
							}
							
							foreach ($discount_total_sums_array as $_dtsa){
								$_ptnlleft -= (float)$_dtsa;
							}
							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['СкидкиДействующиеНаТоварПроверкаСумм'] = array(
							'СуммаОдногоТовараРазница' => (round($_ppnlleft) == round($product['pricewd_national']))?'Истина':(round($_ppnlleft) - round($product['pricewd_national'])),
							'СуммаВсехТоваровРазница' => (round($_ptnlleft) == round($product['totalwd_national']))?'Истина':(round($_ptnlleft) - round($product['totalwd_national']))
							);
							
						}
						
						if ($this->config->get('config_rainforest_enable_api') && $real_product['asin']){
							$this->load->model('kp/product');
							$amazon_offers_counter = 0;
							$amazon_offers = $this->model_kp_product->getProductAmazonOffers($real_product['asin']);
							
							if ($amazon_offers){
								
								foreach ($amazon_offers as $amazon_offer){
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ПредложенияAmazon']['ПредложениеAmazon' . $amazon_offers_counter] = array(
									'ASIN'					=> $real_product['asin'],
									'ПредложенияВалюта'		=> $amazon_offer['priceCurrency'],
									'ПредложенияЦена'		=> $amazon_offer['priceAmount'],
									'ПошлиныВалюта'			=> $amazon_offer['importFeeCurrency'],
									'ПошлиныЦена'			=> $amazon_offer['importFeeAmount'],
									'ДоставкиВалюта'		=> $amazon_offer['deliveryCurrency'],
									'ДоставкиЦена'			=> $amazon_offer['deliveryAmount'],
									'ДоставкаБесплатна'		=> $amazon_offer['deliveryIsFree']?'Истина':'Ложь',
									'ДоставкаСиламиАмазона'	=> $amazon_offer['deliveryIsFba']?'Истина':'Ложь',
									'ДоставкаЗаГраницу'		=> $amazon_offer['deliveryIsShippedCrossBorder']?'Истина':'Ложь',
									'ДоставкаКомментарий'	=> $amazon_offer['deliveryComments'],
									'СостояниеЭтоНовыйТовар'=> $amazon_offer['conditionIsNew']?'Истина':'Ложь',
									'СостояниеЗаголовок'	=> $amazon_offer['conditionTitle'],
									'СостояниеКомментарий'	=> $amazon_offer['conditionComments'],
									'ПродавецНаименование'	=> rms($amazon_offer['sellerName']),
									//	'ПродавецСсылка'			=> $amazon_offer['sellerLink'],
									'ПродавецРейтинг'				=> $amazon_offer['sellerRating50'],
									'ПродавецПозитивныхОтзывов'		=> $amazon_offer['sellerPositiveRatings100'],
									'ПредложенияДатаПолучения'		=> $amazon_offer['date_added'],
									'ЭтоМинимальнаяЦена'			=> $amazon_offer['is_min_price']?'Истина':'Ложь',
									'ЭтоПраймПредложение'			=> $amazon_offer['isPrime']?'Истина':'Ложь',
									'ЭтоЛучшееПредложение'			=> $amazon_offer['isBestOffer']?'Истина':'Ложь',
									'ВнутреннийРейтингПредложения'	=> $amazon_offer['offerRating']?'Истина':'Ложь'
									);
									
									$amazon_offers_counter++;
								}
							}
						}
						
						if ($real_product['source'] || $real_product['asin']){
							$source_counter = 0;
							if ($real_product['asin']){
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ИсточникиПокупки']['ИсточникПокупки' . $source_counter] = array(
								'type'    => 'AmazonAsinListing',
								'explain' => 'Листинг товаров с ASIN в Amazon.de',
								'link'    => rms(htmlentities("https://www.amazon.de/gp/offer-listing/".$real_product['asin']."/ref=olp_f_primeEligible&f_primeEligible=true&f_new=true"), true)
								
								);
								$source_counter++;
								
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ИсточникиПокупки']['ИсточникПокупки' . $source_counter] = array(
								'type'    => 'AmazonDirectAsinLink',
								'explain' => 'Прямая ссылка на ASIN на Amazon',
								'link'    => rms(htmlentities("https://www.amazon.de/dp/".$real_product['asin']), true)
								
								);
								$source_counter++;
							}
							
							$source = explode("\n", $real_product['source']);
							if (!is_array($source) && $source) {
								$source = array($source);
							}
							
							if ($source){
								foreach ($source as $_s){
									
									if (!(strpos($_s, 'amazon.de')===false)){
										$_source_type = 'AmazonDirectLink';
										} else {
										$_source_type = 'ManufacturerUrl';
									}
									
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ИсточникиПокупки']['ИсточникПокупки' . $source_counter] = array(
									'type'    => $_source_type,
									'explain' => 'Ссылка на товар на сайте поставщика',
									'link'    => rms(htmlentities(trim($_s)))
									
									);
									$source_counter++;	
									
								}																
							}
						}
						
						
						$local_suppliers = $this->model_sale_supplier->getSupplierInfo($product['product_id']);
						$lsp_counter = 0;
						if ($local_suppliers){
							foreach ($local_suppliers as $lsp){
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ДанныеПоРегиональнымПоставщикам']['РегиональныйПоставщик'.$lsp_counter] = array(
								'ПоставщикИД' => $lsp['supplier_id']
								,'ПоставщикНаименование' => $lsp['supplier_name']
								,'КодТовараУПоставщика' => $lsp['supplier_product_id']
								);
							}
						}
						
						$collection = $this->model_catalog_collection->getCollection($real_product['collection_id']);
						if ($collection){							
							if ($collection['parent_id']){
								$parent_collection = $this->model_catalog_collection->getCollection($collection['parent_id']);
								} else {
								$parent_collection = array('name' => 'Ложь');
							}
							
							if (!isset($parent_collection['name'])){
								$parent_collection['name'] = 'Ложь';
							}
							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['Коллекция'] = array(
							'КоллекцияИД'   						=> $collection['collection_id']
							,'КоллекцияНаименование'   				=> $collection['name']
							,'КоллекцияРодительИД'   				=> ($collection['parent_id'])?$collection['parent_id']:'Ложь'
							,'КоллекцияРодительНаименование'   		=> ($collection['parent_id'])?$parent_collection['name']:'Ложь'
							);
							} else {
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['Коллекция'] = array(
							'КоллекцияИД'   						=> 'Ложь'
							,'КоллекцияНаименование'   				=> 'Ложь'
							,'КоллекцияРодительИД'   				=> 'Ложь'
							,'КоллекцияРодительНаименование'   		=> 'Ложь'
							);
						}
						
						//Атрибуты
						$attributes = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product['product_id'], 2);
						
						$attribute_counter = 1;
						foreach ($attributes as $attribute){
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['Характеристики']['Характеристика' . $attribute_counter] = array(
							'ХарактеристикаНаименование' =>  rms($attribute['name']),
							'ХарактеристикаЗначение'     =>	 rms($attribute['text'])
							);
							
							$attribute_counter++;
						}
						
						$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ШтрихКоды'] = array(
						'ASIN'   								=> $real_product['asin']						
						,'ТНВЭД'   								=> $real_product['tnved']
						,'EAN13'								=> $real_product['ean']
						);		
						
						//EAN 13
						$ean_counter = 0;
						$ean_array = [];											
						$suppliers_eans = $this->model_sale_supplier->getSupplierEANCodes($product['product_id']);						
						if ($suppliers_eans) {
							foreach ($suppliers_eans as $_ean){																							
								$ean_array[] = $_ean['product_ean'];
							}
						}
						
						$ean_array = array_unique($ean_array);
						foreach ($ean_array as $product_ean){
							if ($product_ean){
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ШтрихКодыПоставщиков']['EAN' . $ean_counter] = $product_ean;	
								$ean_counter++;
							}
						}
						
						$product_supplies = $this->model_sale_supplier->getOPSupply($product['order_product_id']);
						
						$product_supplies_counter = 0;
						foreach ($product_supplies as $product_supply){
							
							$real_supplier = $this->model_sale_supplier->getSupplier($product_supply['supplier_id']);
							$_currency_id = $this->model_localisation_currency->getCurrencyByCode($product_supply['currency']);
							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ИнформацияОЗакупке']['Закупка' . $product_supplies_counter] = array(
							'ИДОперации' 			=> $product_supply['order_product_supply_id'],
							'ИДЗаказа'  			=> $product_supply['order_id'],
							'ИДТовара'  			=> $product_supply['product_id'],
							'ИДЗаписиЗаказТовар'    => $product_supply['order_product_id'],
							'ПоставщикИД'    		=> $product_supply['supplier_id'],
							'ПоставщикНаименование' => $real_supplier['supplier_name'],
							'ПоставщикТип' 			=> $real_supplier['supplier_type'],
							'Количество' 		    => $product_supply['amount'],
							'ЦенаЗаЕдиницу'		    => $product_supply['price'],
							'ВалютаЗакупки'	        => $product_supply['currency'],
							'ВалютаЗакупкиИД'	    => $_currency_id['currency_id'],
							'ВалютаЗаказа'          => $order['currency_code'],
							'ЭтоОбеспечениеЗаказа'  => ($product_supply['is_for_order'])?'Истина':'Ложь'
							);
														
							$product_supplies_counter++;
						}


						$product_suppliers = $this->model_sale_supplier->getProductSuppliers($product['product_id']);
						
						$product_suppliers_counter = 0;
						foreach ($product_suppliers as $product_supplier){
							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['НаличиеУПоставщиков']['Поставщик' . $product_suppliers_counter] = array(							
							'ПоставщикИД'    		=> $product_supplier['supplier_id'],
							'ПоставщикИмя' 		    => $product_supplier['supplier_name'],
							'ПоставщикSKU' 		    => $product_supplier['sku'],
							'ПоставщикНаличие'		=> ($product_supplier['stock'])?'Истина':'Ложь',
							'ПоставщикКоличество'	=> ($product_supplier['quantity']),
							'ПоставщикЦена'		    => $product_supplier['price'],
							'ПоставщикВалюта'       => $product_supplier['currency'],
							//'ПоставщикRawData'      => $product_supplier['raw'],
							);
														
							$product_suppliers_counter++;
						}
																		
						if ($is_set){
							$set_id = (int)$is_set['set_id'];
							$set_products_results = $this->model_sale_order->getOrderProductsBySet($order_id, $set_id);
							
							$set_products_counter = 0;							
							foreach ($set_products_results as $set_product){
								$real_set_product = $this->model_catalog_product->getProduct($set_product['product_id']);
								$weight_class = $this->model_localisation_weight_class->getWeightClass($real_set_product['weight_class_id']);
								$weight_full_class = $this->model_localisation_weight_class->getWeightClass($real_set_product['pack_weight_class_id']);
								$manufacturer = $this->model_catalog_manufacturer->getManufacturer($real_set_product['manufacturer_id']);								
								
								if (!$manufacturer || !isset($manufacturer['name'])){
									$manufacturer['name'] = 'Неизвестно';
								}
								
								if (!$real_set_product['tnved'] || mb_strlen($real_set_product['tnved']) < 2){
									$real_set_product['tnved'] = $this->model_catalog_product->getProductTNVEDByCategory($set_product['product_id']);
								}
								
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter] = array(
								'ИД' 								=> $set_product['product_id']
								,'Артикул'		 					=> $set_product['model']
								,'АртикулНормализованный'			=> normalizeSKU($set_product['model'])
								,'Наименование'   					=> rms($set_product['name'])							
								,'ВесТовара'						=> $real_set_product['weight']								
								,'ВесТовараЕдиницаИД'   			=> $real_set_product['weight_class_id']
								,'ВесТовараЕдиницаНаименование'		=> isset($weight_class['title'])?$weight_class['title']:'Ложь'
								,'ВесТовараЕдиницаОбозначение'		=> isset($weight_class['unit'])?$weight_class['unit']:'Ложь'
								,'ВесТовараПолный'						=> $real_set_product['pack_weight']
								,'ВесТовараПолныйЕдиницаИД'   			=> $real_set_product['pack_weight_class_id']
								,'ВесТовараПолныйЕдиницаНаименование'	=> isset($weight_full_class['title'])?$weight_full_class['title']:'Ложь'
								,'ВесТовараПолныйЕдиницаОбозначение'	=> isset($weight_full_class['unit'])?$weight_full_class['unit']:'Ложь'
								,'БрендИД'       					=> $real_set_product['manufacturer_id']
								,'ИзображениеСсылка'				=> $this->model_tool_image->resize($real_set_product['image'], 150, 150)
								,'БрендНаименование'        		=> rms($manufacturer['name'])
								,'БрендНаименованиеMD5Хэш' 			=> md5($manufacturer['name'])
								,'БрендНаименованиеНормализованноеMD5Хэш' => md5(rms($manufacturer['name']))
								,'ОбычнаяЦенаЗаЕдиницу'				=> $this->currency->convert($real_set_product['price'], $this->config->get('config_currency'), $order['currency_code'])
								,'ОбычнаяЦенаЗаЕдиницуВОсновнойВалюте'	=> $real_set_product['price']
								,'ЦенаЗаЕдиницу'  					=> $set_product['price_national']
								,'ЦенаЗаЕдиницуВОсновнойВалюте'  	=> $set_product['price']
								,'Количество'     					=> $set_product['quantity']
								,'Сумма'          					=> $set_product['total_national']
								,'СуммаВОсновнойВалюте' 			=> $set_product['total']							
								);
								
								$collection = $this->model_catalog_collection->getCollection($real_set_product['collection_id']);
								if ($collection){							
									if ($collection['parent_id']){
										$parent_collection = $this->model_catalog_collection->getCollection($collection['parent_id']);
										} else {
										$parent_collection = false;
									}
									
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['Коллекция'] = array(
									'КоллекцияИД'   						=> $collection['collection_id']
									,'КоллекцияНаименование'   				=> $collection['name']
									,'КоллекцияРодительИД'   				=> ($collection['parent_id'])?$collection['parent_id']:'Ложь'
									,'КоллекцияРодительНаименование'   		=> ($collection['parent_id'])?$parent_collection['name']:'Ложь'
									);
									} else {
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['Коллекция'] = array(
									'КоллекцияИД'   						=> 'Ложь'
									,'КоллекцияНаименование'   				=> 'Ложь'
									,'КоллекцияРодительИД'   				=> 'Ложь'
									,'КоллекцияРодительНаименование'   		=> 'Ложь'
									);
								}
								
								
								$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['ШтрихКоды'] = array(
								'ASIN'   								=> $real_set_product['asin']								
								,'ТНВЭД'   								=> $real_set_product['tnved']
								,'EAN13'									=> $real_set_product['ean']
								);							
								
								$ean_counter 	= 0;
								$ean_array 		= [];											
								$suppliers_eans = $this->model_sale_supplier->getSupplierEANCodes($set_product['product_id']);						
								if ($suppliers_eans) {
									foreach ($suppliers_eans as $ean){																							
										$ean_array[] = $ean['product_ean'];
									}
								}
								
								$ean_array = array_unique($ean_array);
								foreach ($ean_array as $product_ean){
									if ($product_ean){
										$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['ШтрихКодыПоставщиков']['EAN' . $ean_counter] = $product_ean;	
										$ean_counter++;
									}
								}
								
								$set_supplies 			= $this->model_sale_supplier->getOPSupplyForSet($set_product['order_set_id']);								
								$set_supplies_counter 	= 0;
								foreach ($set_supplies  as $set_supply){
									
									$real_supplier = $this->model_sale_supplier->getSupplier($set_supply['supplier_id']);
									
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['ИнформацияОЗакупке']['Закупка' . $set_supplies_counter] = array(
									'ИДОперации' 			=> $set_supply['order_product_supply_id'],
									'ИДЗаказа'  			=> $set_supply['order_id'],
									'ИДТовара'  			=> $set_supply['product_id'],
									'ИДЗаписиЗаказНабор'    => $set_supply['order_set_id'],
									'ПоставщикИД'    		=> $set_supply['supplier_id'],
									'ПоставщикНаименование' => $real_supplier['supplier_name'],
									'ПоставщикТип' 			=> $real_supplier['supplier_type'],
									'Количество' 		    => $set_supply['amount'],
									'ЦенаЗаЕдиницу'		    => $set_supply['price'],
									'ВалютаЗакупки'	        => $set_supply['currency'],
									'ВалютаЗаказа'          => $order['currency_code'],
									'ЭтоОбеспечениеЗаказа'  => ($set_supply['is_for_order'])?'Истина':'Ложь'
									);
									
									
									$set_supplies_counter++;
								}
								
								$set_stock_counter = 0;
								foreach ($this->stocks as $key => $value) {									
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['Остатки']['Остаток' . $set_stock_counter] = array(
									'НаименованиеСклада' => $value
									,'КодСклада'      => $key
									,'Количество'     => (int)$real_set_product[$key]
									);
									
									$set_stock_counter++;
								}
								
								$set_categories =  $this->model_catalog_product->getProductCategories($set_product['product_id']);
								
								$set_categorie_counter = 0;
								foreach ($set_categories as $set_category) {
									$set_real_category = $this->model_catalog_category->getCategory($set_category);	
									$set_parent_category = $this->model_catalog_category->getCategory($set_real_category['parent_id']);
									
									$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['ТоварыВходящиеВНабор']['ТоварВходящийВНабор' . $set_products_counter]['КатегорииТовара']['Категория' . $set_categorie_counter] = array(
									'ИД'             => $set_real_category['category_id']		
									,'Наименование'   => $set_real_category['name']						
									,'РодительскаяКатегорияИД'   => $set_real_category['parent_id']
									,'РодительскаяКатегорияНаименование'   => (isset($set_parent_category['name']))?$set_parent_category['name']:'Ложь'			
									);			
									
									$set_categorie_counter++;
								}

								$set_products_counter++;
							}
							
						}
						
						//ОСТАТКИ
						$stock_counter = 0;
						foreach ($this->stocks as $key => $value) {
							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['Остатки']['Остаток' . $stock_counter] = array(
							'НаименованиеСклада' => $value
							,'КодСклада'      => $key
							,'Количество'     => (int)$real_product[$key]
							);
							
							$stock_counter++;
						}
						
						
						$categories =  $this->model_catalog_product->getProductCategories($product['product_id']);
						
						$categorie_counter = 0;
						foreach ($categories as $category) {
							$real_category = $this->model_catalog_category->getCategory($category);	
							$parent_category = $this->model_catalog_category->getCategory($real_category['parent_id']);
							
							$document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['КатегорииТовара']['Категория' . $categorie_counter] = array(
							'ИД'             => $real_category['category_id']		
							,'Наименование'   => $real_category['name']						
							,'РодительскаяКатегорияИД'   => $real_category['parent_id']
							,'РодительскаяКатегорияНаименование'   => (isset($parent_category['name']))?$parent_category['name']:'Ложь'			
							);			
							
							$categorie_counter++;
						}
						
						$product_counter++;
					}					
					
					//Товары, которые не_в_наличии_и_вейтлисте
					$products = [];
					$categories = [];
					$product_counter = 0;
					$categorie_counter = 0;
					
					$products = $this->model_sale_order->getOrderProductsNoGood($orders_data['order_id']);				
					
					$product_counter = 0;
					foreach ($products as $product) {
						$real_product = $this->model_catalog_product->getProduct($product['product_id']);						
						$weight_class = isset($real_product['weight_class_id'])?$this->model_localisation_weight_class->getWeightClass($real_product['weight_class_id']):false;
						$weight_full_class = isset($real_product['pack_weight_class_id'])?$this->model_localisation_weight_class->getWeightClass($real_product['pack_weight_class_id']):false;
						$manufacturer = isset($real_product['pack_weight_class_id'])?$this->model_catalog_manufacturer->getManufacturer($real_product['manufacturer_id']):false;
						
						if (!$manufacturer || !isset($manufacturer['name'])){
							$manufacturer['name'] = 'Неизвестно';
						}
						
						if (!isset($real_product['tnved']) || !$real_product['tnved'] || mb_strlen($real_product['tnved']) < 2){
							$real_product['tnved'] = $this->model_catalog_product->getProductTNVEDByCategory($product['product_id']);
						}
						
						if ($product['waitlist']) {							
							$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter] = array(
							'ИД'            					=> $product['product_id']
							,'Артикул'		 					=> $product['model']
							,'АртикулНормализованный'			=> normalizeSKU($product['model'])
							,'Наименование'   					=> rms($product['name'])
							,'НаименованиеУКР'   				=> !empty($product['ua_name'])?rms($product['ua_name']):''
							,'ВесТовара'						=> isset($real_product['weight'])?$real_product['weight']:'Ложь'
							,'ВесТовараЕдиницаИД'   			=> isset($real_product['weight_class_id'])?$real_product['weight_class_id']:'Ложь'
							,'ВесТовараЕдиницаНаименование'		=> isset($weight_class['title'])?$weight_class['title']:'Ложь'
							,'ВесТовараЕдиницаОбозначение'		=> isset($weight_class['unit'])?$weight_class['unit']:'Ложь'
							,'ВесТовараПолный'						=> isset($real_product['pack_weight'])?$real_product['pack_weight']:'Ложь'
							,'ВесТовараПолныйЕдиницаИД'   			=> isset($real_product['pack_weight_class_id'])?$real_product['pack_weight_class_id']:'Ложь'					
							,'ВесТовараПолныйЕдиницаНаименование'	=> isset($weight_full_class['title'])?$weight_full_class['title']:'Ложь'
							,'ВесТовараПолныйЕдиницаОбозначение'	=> isset($weight_full_class['unit'])?$weight_full_class['unit']:'Ложь'
							,'МинимумКЗаказу'  						=> $real_product['minimum']
							,'КоличествоВУпаковке'					=> $real_product['package']
							,'БрендИД'       					=> isset($real_product['manufacturer_id'])?$real_product['manufacturer_id']:'Ложь'
							,'БрендНаименование'        		=> rms($manufacturer['name'])
							,'БрендНаименованиеMD5Хэш' 			=> md5($manufacturer['name'])
							,'БрендНаименованиеНормализованноеMD5Хэш' => md5(rms($manufacturer['name']))
							,'ЦенаЗаЕдиницу'  					=> $product['price_national']
							,'ЦенаЗаЕдиницуВОсновнойВалюте'  	=> $product['price']
							,'Количество'     					=> $product['quantity']
							,'Сумма'          					=> $product['total_national']
							,'СуммаВОсновнойВалюте' 			=> $product['total']
							,'ВЛистеОжидания' 			        => 'Истина'
							);
							
							$collection = $this->model_catalog_collection->getCollection($real_product['collection_id']);
							if ($collection){							
								if ($collection['parent_id']){
									$parent_collection = $this->model_catalog_collection->getCollection($collection['parent_id']);
									} else {
									$parent_collection = false;
								}
								
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter]['Коллекция'] = array(
								'КоллекцияИД'   						=> $collection['collection_id']
								,'КоллекцияНаименование'   				=> $collection['name']
								,'КоллекцияРодительИД'   				=> ($collection['parent_id'])?$collection['parent_id']:'Ложь'
								,'КоллекцияРодительНаименование'   		=> ($collection['parent_id'])?$parent_collection['name']:'Ложь'
								);
								} else {
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter]['Коллекция'] = array(
								'КоллекцияИД'   						=> 'Ложь'
								,'КоллекцияНаименование'   				=> 'Ложь'
								,'КоллекцияРодительИД'   				=> 'Ложь'
								,'КоллекцияРодительНаименование'   		=> 'Ложь'
								);
							}
							
							//Атрибуты
							$attributes = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product['product_id'], 2);
							
							$attribute_counter = 1;
							foreach ($attributes as $attribute){
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter]['Характеристики']['Характеристика' . $attribute_counter] = array(
								'ХарактеристикаНаименование' =>  rms($attribute['name']),
								'ХарактеристикаЗначение'     =>	 rms($attribute['text'])
								);
								
								$attribute_counter++;
							}
							
							$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter]['ШтрихКоды'] = array(
							'ASIN'   								=> $real_product['asin']							
							,'ТНВЭД'   								=> $real_product['tnved']
							,'EAN13'								=> $real_product['ean']
							);		
							
							//EAN 13
							$ean_counter = 0;
							$ean_array = [];											
							$suppliers_eans = $this->model_sale_supplier->getSupplierEANCodes($product['product_id']);						
							if ($suppliers_eans) {
								foreach ($suppliers_eans as $_ean){																							
									$ean_array[] = $_ean['product_ean'];
								}
							}
							
							$ean_array = array_unique($ean_array);
							foreach ($ean_array as $product_ean){
								if ($product_ean){
									$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter]['ШтрихКодыПоставщиков']['EAN' . $ean_counter] = $product_ean;	
									$ean_counter++;
								}
							}
							
							$categories =  $this->model_catalog_product->getProductCategories($product['product_id']);
							
							$categorie_counter = 0;
							foreach ($categories as $category) {
								$real_category = $this->model_catalog_category->getCategory($category);	
								$parent_category = $this->model_catalog_category->getCategory($real_category['parent_id']);
								
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИВЛистеОжидания']['Товар' . $product_counter]['КатегорииТовара']['Категория' . $categorie_counter] = array(
								'ИД'             => $real_category['category_id']		
								,'Наименование'   => $real_category['name']						
								,'РодительскаяКатегорияИД'   => $real_category['parent_id']
								,'РодительскаяКатегорияНаименование'   => isset($parent_category['name'])?$parent_category['name']:''							
								);
								
								
								$categorie_counter++;
							}
							
							} else {
							
							$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter] = array(
							'ИД'            					=> $product['product_id']
							,'Артикул'		 					=> $product['model']
							,'АртикулНормализованный'			=> normalizeSKU($product['model'])
							,'Наименование'   					=> rms($product['name'])
							,'НаименованиеУКР'   				=> !empty($product['ua_name'])?rms($product['ua_name']):''
							,'ASIN'   							=> isset($real_product['asin'])?$real_product['asin']:'Ложь'
							,'EAN'   							=> isset($real_product['ean'])?$real_product['ean']:'Ложь'
							,'ТНВЭД'   							=> isset($real_product['tnved'])?$real_product['tnved']:'Ложь'
							,'ВесТовара'						=> isset($real_product['weight'])?$real_product['weight']:'Ложь'
							,'ВесТовараЕдиницаИД'   			=> isset($real_product['weight_class_id'])?$real_product['weight_class_id']:'Ложь'
							,'ВесТовараЕдиницаНаименование'			=> isset($weight_class['title'])?$weight_class['title']:'Ложь'
							,'ВесТовараЕдиницаОбозначение'			=> isset($weight_class['unit'])?$weight_class['unit']:'Ложь'
							,'ВесТовараПолный'						=> isset($real_product['weight'])?$real_product['pack_weight']:'Ложь'
							,'ВесТовараПолныйЕдиницаИД'   			=> isset($real_product['weight'])?$real_product['pack_weight_class_id']:'Ложь'
							,'ВесТовараПолныйЕдиницаНаименование'	=> isset($weight_full_class['title'])?$weight_full_class['title']:'Ложь'
							,'ВесТовараПолныйЕдиницаОбозначение'	=> isset($weight_full_class['unit'])?$weight_full_class['unit']:'Ложь'
							,'МинимумКЗаказу'  						=> $real_product['minimum']
							,'КоличествоВУпаковке'					=> $real_product['package']
							,'БрендИД'       					=> isset($real_product['manufacturer_id'])?$real_product['manufacturer_id']:'Ложь'
							,'БрендНаименование'        		=> rms($manufacturer['name'])
							,'БрендНаименованиеMD5Хэш' 			=> md5($manufacturer['name'])
							,'БрендНаименованиеНормализованноеMD5Хэш' => md5(rms($manufacturer['name']))
							,'ЦенаЗаЕдиницу'  					=> $product['price_national']
							,'ЦенаЗаЕдиницуВОсновнойВалюте'  	=> $product['price']
							,'Количество'     					=> $product['quantity']
							,'Сумма'          					=> $product['total_national']
							,'СуммаВОсновнойВалюте' 			=> $product['total']
							,'ВЛистеОжидания' 			        => 'Ложь'
							);
							
							$collection = isset($real_product['collection_id'])?$this->model_catalog_collection->getCollection($real_product['collection_id']):false;
							if ($collection){							
								if ($collection['parent_id']){
									$parent_collection = $this->model_catalog_collection->getCollection($collection['parent_id']);
									} else {
									$parent_collection = false;
								}
								
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter]['Коллекция'] = array(
								'КоллекцияИД'   						=> $collection['collection_id']
								,'КоллекцияНаименование'   				=> $collection['name']
								,'КоллекцияРодительИД'   				=> ($collection['parent_id'])?$collection['parent_id']:'Ложь'
								,'КоллекцияРодительНаименование'   		=> ($collection['parent_id'])?$parent_collection['name']:'Ложь'
								);
								} else {
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter]['Коллекция'] = array(
								'КоллекцияИД'   						=> 'Ложь'
								,'КоллекцияНаименование'   				=> 'Ложь'
								,'КоллекцияРодительИД'   				=> 'Ложь'
								,'КоллекцияРодительНаименование'   		=> 'Ложь'
								);
							}
							
							//Атрибуты
							$attributes = $this->model_catalog_product->getProductAttributesNamesValuesByLanguage($product['product_id'], 2);
							
							$attribute_counter = 1;
							foreach ($attributes as $attribute){
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter]['Характеристики']['Характеристика' . $attribute_counter] = array(
								'ХарактеристикаНаименование' =>  rms($attribute['name']),
								'ХарактеристикаЗначение'     =>	 rms($attribute['text'])
								);
								
								$attribute_counter++;
							}
							
							$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter]['ШтрихКоды'] = array(
							'ASIN'   								=> isset($real_product['asin'])?$real_product['asin']:'Ложь'							
							,'ТНВЭД'   								=> isset($real_product['tnved'])?$real_product['tnved']:'Ложь'
							,'EAN13'   								=> isset($real_product['ean'])?$real_product['ean']:'Ложь'
							);
							
							//EAN 13
							$ean_counter = 0;					
							$ean_array[] = isset($real_product['ean'])?$real_product['ean']:false;						
							$suppliers_eans = $this->model_sale_supplier->getSupplierEANCodes($product['product_id']);						
							if ($suppliers_eans) {
								foreach ($suppliers_eans as $_ean){																							
									$ean_array[] = $_ean['product_ean'];
								}
							}
							
							$ean_array = array_unique($ean_array);
							
							foreach ($ean_array as $product_ean){
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter]['ШтрихКодыПоставщиков']['EAN' . $ean_counter] = $product_ean;	
								$ean_counter++;
							}
							
							$categories =  $this->model_catalog_product->getProductCategories($product['product_id']);
							
							$categorie_counter = 0;
							foreach ($categories as $category) {
								$real_category = $this->model_catalog_category->getCategory($category);	
								$parent_category = $this->model_catalog_category->getCategory($real_category['parent_id']);
								
								$document['Документ' . $document_counter]['ТоварыНетУПоставщикаИНеВЛистеОжидания']['Товар' . $product_counter]['КатегорииТовара']['Категория' . $categorie_counter] = array(
								'ИД'             => $real_category['category_id']		
								,'Наименование'   => $real_category['name']						
								,'РодительскаяКатегорияИД'   => $real_category['parent_id']
								,'РодительскаяКатегорияНаименование'   => isset($parent_category['name'])?$parent_category['name']:''							
								);
								
								
								$categorie_counter++;
							}
							
						}
												
						$product_counter++;
					}
					
					$receipt = $this->Fiscalisation->getOrderReceipt($order['order_id']);

					if ($receipt){
						$receipt_links = $this->Fiscalisation->getReceiptLinks($receipt['receipt_id']);

						$document['Документ' . $document_counter]['ФискальныеЧеки']['Чек0'] = array(
							'КодЧекаФискальный'         => $receipt['fiscal_code']
							,'КодЧекаАпи'				=> $receipt['receipt_id']
							,'СистемаАпи'		 		=> $receipt['api']
							,'Статус'	 				=> $receipt['status']
							,'ДатаФискализации'	 		=> $receipt['fiscal_date']
							,'СозданОффлайн'	 		=> $receipt['is_created_offline']?'Истина':'Ложь'
							,'ОтправленВФС'	 			=> $receipt['is_sent_dps']?'Истина':'Ложь'
							,'ДатаОтправкиВФС'	 		=> $receipt['sent_dps_at']?'Истина':'Ложь'
							,'ТипЧека'	 				=> $receipt['type']
							,'СсылкаАпиHTML'      		=> !empty($receipt_links['HTML'])?$receipt_links['HTML']['link']:'Ложь'
							,'СсылкаАпиPDF'      		=> !empty($receipt_links['PDF'])?$receipt_links['PDF']['link']:'Ложь'
							,'СсылкаАпиText'      		=> !empty($receipt_links['TEXT'])?$receipt_links['TEXT']['link']:'Ложь'
							,'СсылкаАпиPNG'      		=> !empty($receipt_links['PNG'])?$receipt_links['PNG']['link']:'Ложь'
							,'СсылкаАпиQRCODE'     		=> !empty($receipt_links['QRCODE'])?$receipt_links['QRCODE']['link']:'Ложь'
						);
					}
					
					
					$document_counter++;
				}						
				} else {
				die('no order');
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {				
				if ($do_iconv){
					ini_set('mbstring.substitute_character', "none"); 
					$content = mb_convert_encoding($content, 'CP1251', 'UTF-8'); 
				} 
				
				$this->response->setXML($content);				
			}
			
			if (!$dir) {
				
				if ($order_id) {
					$_fname = (int)$order_id; 
					} elseif ($order_array) {
					$_fname = md5(serialize($order_array)) . '_' . date('Y_m_d_H_i_s'); 
				}						
				
				file_put_contents(DIR_EXPORT . 'odinass/orders/' . $_fname . '.xml', $content);
				chmod(DIR_EXPORT . 'odinass/orders/' . $_fname . '.xml', 0777);
				
				file_put_contents(DIR_EXPORT . 'odinass/orders2/' . $_fname . '.xml', $content);
				chmod(DIR_EXPORT . 'odinass/orders2/' . $_fname . '.xml', 0777);

				if (!is_dir(DIR_EXPORT . 'odinass/orders_backup/' . date('Y') . '/' . date('m') . '/' . date('d'))){
					mkdir(DIR_EXPORT . 'odinass/orders_backup/' . date('Y') . '/' . date('m') . '/' . date('d'), 0775, true);
				}
				
				file_put_contents(DIR_EXPORT . 'odinass/orders_backup/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . (int)$order['order_id'] . '_' . date('H_i_s') .'.xml', $content);
				
				$odinass_log = new Log('export2odinass.log.txt');
				$odinass_log->write('Экспортирован заказ '.(int)$order['order_id'] . ', статус: ' .  $order_status['name'] . '(' . $order_status['order_status_id'] . ')');
				
				} else {
				
				if ($order_id) {
					$_fname = (int)$order_id . '_' . date('Y_m_d_H_i_s'); 
					} elseif ($order_array) {
					$_fname = md5(serialize($order_array)) . '_' . date('Y_m_d_H_i_s'); 
				}
				
				file_put_contents($dir .'/'. $_fname .'.xml', $content);
				
				$odinass_log = new Log('export2odinass.log.txt');
				$odinass_log->write('Экспортирован заказ '.(int)$order['order_id'] . ', статус: ' .  $order_status['name'] . '(' . $order_status['order_status_id'] . ')');
			}
		}
		
		public function exportCollectionsXML($do_echo = false){
			$this->load->model('catalog/collection');
			
			$collections = $this->model_catalog_collection->getCollections($data = array());	
			
			$collection_counter = 0;
			foreach ($collections as $collection) {
				$real_collection = $this->model_catalog_collection->getCollection($collection['collection_id']);	
				$parent_collection = $this->model_catalog_collection->getCollection($collection['parent_id']);
				
				$document['Каталог']['Коллекции']['Коллекция' . $collection_counter] = array(
				'ИД'             					=> $real_collection['collection_id']
				,'БрендИД'							=> $real_collection['manufacturer_id']				
				,'БрендНаименование'				=> $real_collection['manufacturer']
				,'Наименование'   					=> $real_collection['name']						
				,'РодительскаяКатегорияИД'   		=> $real_collection['parent_id']
				,'РодительскаяКатегорияНаименование'   => (isset($parent_collection['name']))?$parent_collection['name']:'Ложь'			
				);			
				
				$collection_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				
				if ($do_iconv){
					ini_set('mbstring.substitute_character', "none"); 
					$content = mb_convert_encoding($content, 'CP1251', 'UTF-8'); 
				} 
				
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/catalog/collections.xml', $content);
			chmod(DIR_EXPORT . 'odinass/catalog/collections.xml', 0777);			
		}
				
		public function exportCategoriesXML($do_echo = false){
			$this->load->model('catalog/category');
			
			$categories = $this->model_catalog_category->getCategories($data = array());	
			
			$categorie_counter = 0;
			foreach ($categories as $category) {
				$real_category = $this->model_catalog_category->getCategory($category['category_id']);	
				$parent_category = $this->model_catalog_category->getCategory($real_category['parent_id']);
				
				$document['Каталог']['Категории']['Категория' . $categorie_counter] = array(
				'ИД'             => $real_category['category_id']		
				,'Наименование'   => $real_category['name']						
				,'РодительскаяКатегорияИД'   => $real_category['parent_id']
				,'РодительскаяКатегорияНаименование'   => (isset($parent_category['name']))?$parent_category['name']:'Ложь'			
				);			
				
				$categorie_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				
				if ($do_iconv){
					ini_set('mbstring.substitute_character', "none"); 
					$content = mb_convert_encoding($content, 'CP1251', 'UTF-8'); 
				} 
				
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/catalog/categories.xml', $content);
			chmod(DIR_EXPORT . 'odinass/catalog/categories.xml', 0777);			
		}
		
		public function exportManufacturersXML($do_echo = false){
			$this->load->model('catalog/manufacturer');
			
			$manufacturers = $this->model_catalog_manufacturer->getManufacturers($data = array('start' => 0, 'limit' => 10000));	
			
			$manufacturer_counter = 0;
			foreach ($manufacturers as $manufacturer) {
				$real_manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer['manufacturer_id']);	
				
				$document['Каталог']['Бренды']['Бренд' . $manufacturer_counter] = array(
				'ИД'             => $real_manufacturer['manufacturer_id']		
				,'Наименование'   => rms($real_manufacturer['name'])
				,'НаименованиеMD5Хэш' => md5($real_manufacturer['name'])
				,'НаименованиеНормализованноеMD5Хэш' => md5(rms($real_manufacturer['name']))
				);			
				
				$manufacturer_counter++;
			}
			
			$root = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
			$oXML = new SimpleXMLElement($root);
			$xml = array_to_xml($document, $oXML);
			
			$content = $xml->asXML();
			
			if ($do_echo) {
				header("Content-Type: text/xml");
				
				if ($do_iconv){
					ini_set('mbstring.substitute_character', "none"); 
					$content = mb_convert_encoding($content, 'CP1251', 'UTF-8'); 
				} 
				
				print_r($content);
			}
			
			file_put_contents(DIR_EXPORT . 'odinass/catalog/manufacturers.xml', $content);
			chmod(DIR_EXPORT . 'odinass/catalog/manufacturers.xml', 0777);			
		}		
	}										
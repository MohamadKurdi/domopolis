<?
	class ModelApiYamarket extends Model {
		private $error = array(); 
		

		public function addToQueueQuery($data){
		
			$sql = "INSERT INTO yandex_queue SET 
				order_id = '" . (int)$data['order_id'] . "', 
				status = '" . $this->db->escape($data['status']) . "', 
				substatus = '" . $this->db->escape($data['substatus']) . "'
				ON DUPLICATE KEY UPDATE
				status = '" . $this->db->escape($data['status']) . "', 
				substatus = '" . $this->db->escape($data['substatus']) . "'
				";
				
			$this->db->query($sql);				
			
		}
		
		public function getOrderShipmentID($order_id){
			$shipment_query = $this->db->query("SELECT yam_shipment_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			return $shipment_query->row['yam_shipment_id'];
		}
		
		public function getAllOrdersByShipmentID($order_id){
			$orders = [];
			
			$shipment_query = $this->db->query("SELECT yam_shipment_id FROM `order` WHERE order_id = '" . (int)$order_id . "'");
			
			if (!empty($shipment_query->row['yam_shipment_id'])){
				$orders_query = $this->db->query("SELECT order_id, order_status_id FROM `order` WHERE yam_shipment_id = '" . (int)$shipment_query->row['yam_shipment_id'] . "'");
				
				foreach ($orders_query->rows as $row){
					$orders[$row['order_id']] = $row['order_status_id'];
				}
			}
			
			return $orders;
			
		}
		
		public function addToQueue($order_id, $order_status_id){
			
			//Это старая логика
			//Проверяем все заказы из поставки, и если они все находятся в "отправлен", то отправляем уведомление о успешной поставке
			/*
			if ($order_status_id == $this->config->get('config_delivering_status_id')){
				
				$orders = $this->getAllOrdersByShipmentID($order_id);
				
				$add = true;
				foreach ($orders as $key => $value){
					if ($value != $this->config->get('config_delivering_status_id')){
						$add = false;
						break;
					}
				}
				
				if ($add && $shipment_id = $this->getOrderShipmentID($order_id)){
					$this->addToQueueQuery(array('order_id' => $shipment_id, 'status' => 'SHIPMENTFULLFILLED', 'substatus' => implode(':', array_keys($orders))));
				}
			}
			*/
			
			//Готов к доставке
			if ($order_status_id == $this->config->get('config_ready_to_delivering_status_id')){
				
				$this->addToQueueQuery(array('order_id' => $order_id, 'status' => 'PROCESSING', 'substatus' => 'READY_TO_SHIP'));
				
			}
			
			//Отправлен курьерской службой, или в пункте самовывоза
			if ($order_status_id == $this->config->get('config_delivering_status_id') || $order_status_id == $this->config->get('config_in_pickup_status_id')){
				
				$this->addToQueueQuery(array('order_id' => $order_id, 'status' => 'PROCESSING', 'substatus' => 'SHIPPED'));
				
			}
			
			
			
			
			if ($order_status_id == $this->config->get('config_cancelled_status_id')){
				
				$this->addToQueueQuery(array('order_id' => $order_id, 'status' => 'CANCELLED', 'substatus' => 'SHOP_FAILED'));
				
			}

		}
		
		
		
		
		
		
		
	}
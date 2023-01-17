<?

class CourierServices {
	private $ttn;	
	private $registry;
	private $config;
	private $db;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');		
	}

	public function getInfo($ttn, $shipping_code, $phone = '') {				
		if (!empty($this->registry->get('shippingmethods')[$shipping_code]) && !empty($this->registry->get('shippingmethods')[$shipping_code]['class'])){
			$deliveryClass = $this->registry->get('shippingmethods')[$shipping_code]['class'];

			if (file_exists(DIR_SYSTEM . '/library/deliveryapis/' . $deliveryClass . '.php')){
				require_once (DIR_SYSTEM . '/library/deliveryapis/' . $deliveryClass . '.php');
				$deliveryObject = new $deliveryClass($this->registry);

				if (method_exists($deliveryObject, 'trackAndFormat')){
					$result = $deliveryObject->trackAndFormat($ttn, $phone);
				}
			}			
		}

		if (empty($result)){
			$result = 'Could not get information about tracking code';
		}			

		return $result;
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
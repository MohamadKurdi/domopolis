<?

class ControllerKPForgotten extends Controller {
	private $error = [];

	public function __construct($registry){
		parent::__construct($registry);

		$this->load->model('sale/order');
	}

	private function updateReminder($entity_id, $type = 'order'){
		if ($type == 'order'){
			$this->db->query("UPDATE `order` SET forgotten_cart_reminder = forgotten_cart_reminder + 1, forgotten_cart_sent = NOW() WHERE order_id = '" . (int)$entity_id . "'");
		} elseif ($type == 'simple'){
			$this->db->query("UPDATE `simple_cart` SET reminder = reminder + 1, reminder_sent = NOW() WHERE simple_cart_id = '" . (int)$entity_id . "'");
		}	
	}

	private function uniqueCarts($carts){
		$results = [];

		$unique['customer_id'] 	= [];
		$unique['telephone']	= [];
		$unique['email']		= [];

		foreach ($carts as $cart){			
			if (!empty($cart['customer_id'])){
				if (!in_array($cart['customer_id'], $unique['customer_id'])){
					$unique['customer_id'][] 					= $cart['customer_id'];
					$results[$cart['type'].$cart['cart_id']]	= $cart;
				}
			}

			if (!empty($cart['customer_id'])){
				if (!in_array($cart['telephone'], $unique['telephone'])){
					$unique['telephone'][] 	= $cart['telephone'];
					$results[$cart['type'].$cart['cart_id']]	= $cart;
				}
			}

			if (!empty($cart['email'])){
				if (!in_array($cart['email'], $unique['email'])){
					$unique['email'][] 	= $cart['email'];
					$results[$cart['type'].$cart['cart_id']]	= $cart;
				}
			}

		}
		
		return $results;
	}

	private function validateCart($cart){
		if (!empty($cart['customer_id'])){
			$query = $this->db->query("SELECT order_id FROM `order` WHERE order_status_id > 0 AND customer_id = '" . (int)$cart['customer_id'] . "' AND date_added > '" . $this->db->escape($cart['date_added']) . "'");
			if ($query->num_rows){
				echoLine('[ControllerKPForgotten::validateCart] Found later order for cart by customer_id: ' . $cart['customer_id'] . ', ' . $query->row['order_id'], 'e');
				return false;
			}
		}

		if (!empty($cart['email'])){
			$query = $this->db->query("SELECT order_id FROM `order` WHERE order_status_id > 0 AND email = '" . $this->db->escape($cart['email']) . "' AND date_added > '" . $this->db->escape($cart['date_added']) . "'");
			if ($query->num_rows){
				echoLine('[ControllerKPForgotten::validateCart] Found later order for cart by email: ' . $cart['email'] . ', ' . $query->row['order_id'], 'e');
				return false;
			}
		}

		if (!empty($cart['telephone'])){
			$query = $this->db->query("SELECT order_id FROM `order` WHERE order_status_id > 0 AND REGEXP_REPLACE(telephone, '[^0-9]', '') = '" . $this->db->escape(preg_replace("([^0-9])", "", $cart['telephone'])) . "' AND date_added > '" . $this->db->escape($cart['date_added']) . "'");
			if ($query->num_rows){
				echoLine('[ControllerKPForgotten::validateCart] Found later order for cart by telephone: ' . $cart['telephone'] . ', ' . $query->row['order_id'], 'e');
				return false;
			}
		}		

		echoLine('[ControllerKPForgotten::validateCart] Not later orders found for cart with id ' . $cart['cart_id'] . ' and type ' . $cart['type'], 's');

		return true;
	}

	private function parseCart($cart, $type = 'order'){
		$result = [
			'cart_id' 		=> null,
			'date_added'	=> $cart['date_added'],
			'customer_id'	=> $cart['customer_id'],
			'telephone'		=> $cart['telephone'],
			'email'			=> null,
			'firstname'		=> $cart['firstname'],
			'lastname'		=> $cart['lastname'],
			'type'    		=> $type,
			'products' 		=> [],
			'total' 		=> 0,			
		];

		if (!$this->emailBlackList->native($cart['email'])){
			$result['email'] = $cart['email'];
		}

		if ($type == 'order'){	
			$result['cart_id'] 	= $cart['order_id'];
			$products 			= $this->model_sale_order->getOrderProducts($cart['order_id']);

		} elseif ($type == 'simple'){			
			$result['cart_id'] 	= $cart['simple_cart_id'];
			$products 			= json_decode($cart['products'], true);
		}

		foreach ($products as $product){
			$result['products'][] = [
				'product_id' 	=> $product['product_id'],
				'name' 			=> $product['name'],
				'model'			=> $product['model'],
				'quantity'		=> $product['quantity'],
				'price'			=> (!empty($product['price_national'])?$product['price_national']:extractNumeric($product['price']))
			];
		}

		return $result;
	}

	private function sendCart($cart){










	}

	public function cron() {			
		if (!$this->config->get('config_forgottencart_send_enable')){
			echoLine('[ControllerKPForgotten::cron] FORGOTTENCART LOGIC IS DISABLED IN ADMIN', 'e');
			return;
		}

		$this->load->library('hobotix/FPCTimer');

		if ($this->config->has('config_forgottencart_send_time_start') && $this->config->has('config_forgottencart_send_time_end')){
			$interval = new \hobotix\Interval($this->config->get('config_forgottencart_send_time_start') . '-' . $this->config->get('config_forgottencart_send_time_end'));

			if (!$interval->isNow()){
				echoLine('[ControllerKPForgotten::cron] NOT ALLOWED TIME', 'e');
				return;
			} else {
				echoLine('[ControllerKPForgotten::cron] ALLOWED TIME', 's');				
			}
		}

		foreach ([1,2] as $iteration){
			if ($this->config->get('config_forgottencart_send_something_' . $iteration)){
				$carts = [];
				echoLine('[ControllerKPForgotten::cron] Working in iteration ' . $iteration, 'i');

				if ($this->config->get('config_forgottencart_use_simplecheckout_carts_' . $iteration)){
					$sql = "SELECT * FROM simple_cart WHERE reminder = '" . (int)($iteration - 1) . "' ";
						
					if ($iteration == 1){
						$sql .= " AND date_added <= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_min_hours_' . $iteration) . " HOUR) 
						AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_max_hours_' . $iteration) . " HOUR)";
					} else {
						$sql .= " AND reminder_sent <= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_min_hours_' . $iteration) . " HOUR) 
						AND reminder_sent >= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_max_hours_' . $iteration) . " HOUR)";
					}						
						
					$sql .= " ORDER BY date_added DESC";

					$query = $this->db->query($sql);

					foreach ($query->rows as $row){						
						$carts[] = $this->parseCart($row, 'simple');
					}
				}

				if ($this->config->get('config_forgottencart_use_zerostatus_orders_' . $iteration)){
					$sql = "SELECT * FROM `order` WHERE forgotten_cart_reminder = '" . (int)($iteration - 1) . "' AND order_status_id = 0 ";
					
					if ($iteration == 1){
						$sql .= " AND date_added <= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_min_hours_' . $iteration) . " HOUR) 
						AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_max_hours_' . $iteration) . " HOUR)";
					} else {
						$sql .= " AND forgotten_cart_sent <= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_min_hours_' . $iteration) . " HOUR) 
						AND forgotten_cart_sent >= DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('config_forgottencart_time_max_hours_' . $iteration) . " HOUR)";
					}						
						
					$sql .= " ORDER BY date_added DESC";


					$query = $this->db->query($sql);

					foreach ($query->rows as $row){						
						$carts[] = $this->parseCart($row, 'order');
					}
				}

				echoLine('[ControllerKPForgotten::cron] Now we have ' . count($carts) . ' carts', 'i');
				
				$tmp_carts = [];
				foreach ($carts as $cart){
					if ($this->validateCart($cart)){
						$tmp_carts[] = $cart;
					}
				}
				$carts = $tmp_carts;
				$carts = $this->uniqueCarts($carts);

				echoLine('[ControllerKPForgotten::cron] After checking for uniqueness we have ' . count($carts) . ' carts', 'i');

				foreach ($carts as $cart){
					$this->sendCart($cart);
				}

			} else {
				echoLine('[ControllerKPForgotten::cron] iteration ' . $iteration . ' is disabled!', 'e');
			}
		}
	}
}
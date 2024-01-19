<?

class ControllerKPForgotten extends Controller {
	private $log = null;	

	public function __construct($registry){
		parent::__construct($registry);

		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('catalog/actiontemplate');
		$this->load->model('catalog/actiontemplate_functions');

		$this->log = new \Log('forgotten-cart-reminders.log');
	}

	private function updateReminder($cart){
		if ($cart['type'] == 'order'){
			$this->db->query("UPDATE `order` SET forgotten_cart_reminder = forgotten_cart_reminder + 1, forgotten_cart_sent = NOW() WHERE order_id = '" . (int)$cart['cart_id'] . "'");
		} elseif ($cart['type'] == 'simple'){
			$this->db->query("UPDATE `simple_cart` SET reminder = reminder + 1, reminder_sent = NOW() WHERE simple_cart_id = '" . (int)$cart['cart_id'] . "'");
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
			'store_id'		=> $cart['store_id'],
			'email'			=> null,
			'firstname'		=> $cart['firstname'],
			'lastname'		=> $cart['lastname'],
			'type'    		=> $type,
			'products' 		=> [],
			'total' 		=> 0,			
		];

		if ($cart['email'] && !$this->emailBlackList->native($cart['email']) && $this->emailBlackList->checkSubscription($cart['email'], 'newsletter_personal')){
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
			$product_info = $this->model_catalog_product->getProduct($product['product_id']);

			$price = (!empty($product['price_national'])?$product['price_national']:extractNumeric($product['price']));
			$price = $this->currency->format($price, $this->config->get('config_regional_currency'), 1);

			$result['products'][] = [
				'product_id' 	=> $product['product_id'],
				'name' 			=> normalizeForGoogleV2($product['name']),
				'model'			=> $product['model'],
				'quantity'		=> $product['quantity'],
				'image'			=> $this->model_tool_image->resize($product_info['image'], 250, 250),
				'href' 			=> $this->url->frontlink('product/product', 'product_id=' . $product['product_id']),
				'price'			=> $price
			];
		}

		$result['products'] = array_slice($result['products'], 0, 3);

		return $result;
	}

	private function sendCart($cart, $iteration){
		if (($cart['telephone'] && $this->config->get('config_forgottencart_sms_enable_' . $iteration)) || ($cart['email'] && $this->config->get('config_forgottencart_email_enable_' . $iteration))){	
				$cart['coupon'] = $this->couponRandom->getCouponRandom($this->config->get('config_forgottencart_promocode_' . $iteration));
		}

		if ($cart['telephone'] && $this->config->get('config_forgottencart_sms_enable_' . $iteration)){			
			$data['iteration'] = $iteration;

			$forgotten_cart 				= [];
			$forgotten_cart['order_id'] 	= $cart['cart_id'];
			$forgotten_cart['customer_id'] 	= $cart['customer_id'];
			$forgotten_cart['telephone'] 	= $cart['telephone'];
			$forgotten_cart['firstname'] 	= $cart['firstname'];
			$forgotten_cart['lastname'] 	= $cart['lastname'];
			$forgotten_cart['promocode']  = $cart['coupon'];

			$forgotten_cart['product'] 		= '';

			foreach ($cart['products'] as $product){
				$forgotten_cart['product'] .= $product['name'];
				$forgotten_cart['product'] .= '( ' . $product['price'] . ')';
				$forgotten_cart['product'] .= PHP_EOL;
			}

			if ($this->config->get('config_viber_forgottencart_' . $iteration . '_image_product')){
				if ($this->config->get('config_viber_forgottencart_' . $iteration . '_image_product_if_single')){
					if (count($cart['products']) == 1){
						$forgotten_cart['product_image'] = $cart['products'][0]['image'];						
					}
				} else {
					$product = $this->model_catalog_product->getProduct($cart['products'][0]['product_id']);
					if ($product && $product['image']){
						$forgotten_cart['product_image'] = $cart['products'][0]['image'];	
					}
				}
			}

			echoLine('[ControllerKPForgotten::sendCart] Sending SMS/Viber reminder to ' . $cart['telephone'], 'i');
			$this->log->write('[ControllerKPForgotten::sendCart] Sent to phone: ' . json_encode($forgotten_cart));

			$this->smsAdaptor->sendSMSForgottenCartPromo($forgotten_cart, $data);
			$this->updateReminder($cart);
		}

		if ($cart['email'] && $this->config->get('config_forgottencart_email_enable_' . $iteration)){	
			$this->load->model('catalog/actiontemplate');
			$actionTemplate = $this->model_catalog_actiontemplate->getActionTemplate($this->config->get('config_forgottencart_email_actiontemplate_' . $iteration));

			if (!empty($actionTemplate['file_template'])){
				$this->template = 'sale/actiontemplates/' . $actionTemplate['file_template'] . '.tpl';
			} else {
				$this->template = $actionTemplate['description'];
			}

			$this->log->write('[ControllerKPForgotten::sendCart] Sent to mail: ' . json_encode($cart));

			$this->data = $cart;			
			$cart['html'] = $this->render();

			if ($this->config->get('config_forgottencart_email_actiontemplate_tracking_code_' .  $iteration)){
				$cart['html'] = addTrackingToHTML($cart['html'], $this->config->get('config_forgottencart_email_actiontemplate_tracking_code_' .  $iteration));
			}

			$mail_info = [
				'mail_from' 		=> $this->config->get('config_mail_trigger_name_from'),
				'mail_from_email' 	=> $this->config->get('config_mail_trigger_mail_from'),
				'mail_to' 			=> trim($cart['firstname'] . ' ' . $cart['lastname']),
				'mail_to_email' 	=> $cart['email'],
				'store_id'    		=> $cart['store_id'],
				'customer_id'    	=> $cart['customer_id'],
				'template_id'    	=> (int)$this->config->get('config_forgottencart_email_actiontemplate_' . $iteration)
			];

			$data = array_merge($cart, $actionTemplate, $mail_info);	

			echoLine('[ControllerKPForgotten::sendCart] Sending Email reminder to ' . $cart['email'], 'i');

			$mail = new Mail($this->registry); 
			$mail->setProtocol($this->config->get('config_mail_protocol'));
			$mail->setEmailTemplate(new EmailTemplate($this->request, $this->registry));
			$mail->setFrom($this->config->get('config_mail_trigger_mail_from'));
			$mail->setSender($this->config->get('config_mail_trigger_name_from'));
			$mail->setIsMarketing(true);
			$mail->setSubject($actionTemplate['seo_title']);
			$mail->setTo($cart['email']);
			$mail->setHTML($cart['html']);
			$transmission_id = $mail->send(true, $data, true);


			$this->updateReminder($cart);
		}
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
					$this->sendCart($cart, $iteration);
				}

			} else {
				echoLine('[ControllerKPForgotten::cron] iteration ' . $iteration . ' is disabled!', 'e');
			}
		}
	}
}
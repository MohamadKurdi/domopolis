<?
	
 	class ControllerKPTriggers extends Controller {
		private $error = array();
		private $products = array();
		
		//MAIN
		private $tracking = '5f6de726297fc';
		private $html = '';
		private $title = '';
		private $to = '';
		
		//CURRENT
		private $triggerType = '';
		private $store_id = 0;
		private $order = array();
		private $order_id = 0;
		private $customer_id = 0;
		private $language_id = 0;
		
		
		public function index() {
			$this->load->model('catalog/actiontemplate');
			$this->load->model('account/customer');
			$this->load->model('setting/setting');
		}
		
		//MAIL SETTING FUNCTIONS
		private function setHTML($html){
			$this->html = $html;
		}
		
		private function setTitle($title){
			$this->title = $title;
		}
		
		private function setTo($to){
			$this->to = $to;
		}
		
		private function setTriggerType($triggerType){
			$this->triggerType = $triggerType;
		}
		
		//DATA SETTING FUNCTIONS
		private function setOrderID($order_id){
			$this->order_id = $order_id;
		}
		
		private function setOrder($order){
			$this->order = $order;
		}
		
		private function setCustomerID($customer_id){
			$this->customer_id = $customer_id;
		}
		
		private function setStoreID($store_id){
			$this->store_id = $store_id;
		}
		
		private function setLanguageID($language_id){
			$this->language_id = $language_id;
		}
		
		
		//SERVICE FUNCTIONS	
		private function echoLine($line){
			if(php_sapi_name()==="cli"){
				echo $line . PHP_EOL;
			}
		}
		
		private function prepareProduct($result){
			
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], 180, 180);
				$image_mime = $this->model_tool_image->getMime($result['image']);
				$image_webp = $this->model_tool_image->resize_webp($result['image'], 180, 180);
				} else {
				$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 180, 180);
				$image_mime = $this->model_tool_image->getMime($this->config->get('config_noimage'));
				$image_webp = $this->model_tool_image->resize_webp($this->config->get('config_noimage'), 180, 180);
			}
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
				$price = false;
			}
			
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
				$special = false;
			}	
			
			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
				$tax = false;
			}		
			
			if (mb_strlen($result['description']) > 10){
				$_description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 128) . '..';
				} else {
				$_description = '';
			}
			
			if (isset($result['display_price_national']) && $result['display_price_national'] && $result['display_price_national'] > 0 && $result['currency'] == $this->currency->getCode()){
				$price = $this->currency->format($this->tax->calculate($result['display_price_national'], $result['tax_class_id'], $this->config->get('config_tax')), $result['currency'], 1);
			}
			
			$stock_data = $this->model_catalog_product->parseProductStockData($result);
			
			$href = $this->makeURL($this->url->link('product/product', 'product_id=' . $result['product_id']));
			
			return array(
			'new'         		=> $result['new'],					
			'show_action' 		=> $result['additional_offer_count'],
			'product_id'  		=> $result['product_id'],
			'stock_type'  		=> $stock_data['stock_type'],						
			'show_delivery_terms' => $stock_data['show_delivery_terms'],
			'thumb'       => $image,
			'thumb_mime'  => $image_mime,
			'thumb_webp'  => $image_webp,
			'is_set' 	  => $result['set_id'],
			'name'        => $result['name'],
			'description' => $_description,
			'price'       => $price,
			'special'     => $special,
			'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
			'tax'         => $tax,
			'rating'      => $result['rating'],
			'count_reviews' => $result['reviews'],
			'sku'      	  => $result['model']?$result['model']:$result['sku'],
			'sort_order'  => $result['sort_order'],
			'can_not_buy' => ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
			'need_ask_about_stock' => ($result['stock_status_id'] == $this->config->get('config_partly_in_stock_status_id')),
			'has_child'  => $result['has_child'],
			'stock_status'  => $result['stock_status'],
			'location'      => $result['location'],
			'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
			'href'        => $href
			);
			
		}
		
		
		
		private function addHistory($data){	
			$this->db->non_cached_query("INSERT INTO trigger_history SET
			order_id = '" . (int)(!empty($this->order_id)?$this->order_id:0) . "',
			customer_id = '" . (int)(!empty($this->customer_id)?$this->customer_id:0) . "',		
			type = '" . $this->db->escape(!empty($this->triggerType)?$this->triggerType:0) . "',
			date_added = NOW()");
			
			$this->db->query("INSERT INTO emailtemplate_logs SET		
			emailtemplate_log_sent = '" . date('Y-m-d H:i:s') . "',
			emailtemplate_log_type = 'MARKETING',
			emailtemplate_log_to = '" . $this->db->escape($this->to) .  "',
			emailtemplate_log_from = '" . $this->db->escape($this->config->get('config_mail_trigger_mail_from')) .  "',
			emailtemplate_log_sender = '" . $this->db->escape($this->config->get('config_mail_trigger_name_from')) .  "',
			emailtemplate_log_subject = '" . $this->db->escape($this->title) .  "',
			emailtemplate_log_text = '',
			emailtemplate_log_html = '" . $this->db->escape($this->html) .  "',
			emailtemplate_log_content = '',
			emailtemplate_id = 'trigger.trigger',
			customer_id = '" . (int)$this->customer_id .  "',
			store_id = '" . (int)$this->store_id .  "',
			order_id = '" . (int)$this->order_id .  "',
			transmission_id = '" . (int)$data['transmission_id'] .  "',
			mail_status = 'injection',
			marketing = '0'
			");
			$emailtemplate_id = $this->db->getLastID();
			
			$this->db->non_cached_query("INSERT INTO customer_history SET
			order_id = '" . (int)(!empty($this->order_id)?$this->order_id:0) . "',
			customer_id = '" . (int)(!empty($this->customer_id)?$this->customer_id:0) . "',
			email_id = '" . (int)$emailtemplate_id . "',
			date_added = NOW()");
		}
		
		private function sendTriggerMail(){
			$mail = new Mail($this->registry);

			if ($this->config->get('config_mail_trigger_protocol') == 'sparkpost'){
				$mail->protocol 	= 'sparkpost';
				$mail->hostname 	= $this->config->get('config_sparkpost_api_url');
				$mail->username 	= $this->config->get('config_mail_trigger_mail_from');
				$mail->password 	= $this->config->get('config_sparkpost_api_key');
			}
				
			
			$mail->setTo($this->to);
			$mail->setFrom($this->config->get('config_mail_trigger_mail_from'));
			$mail->setSender($this->order['store_name']);
			$mail->setSubject(html_entity_decode($this->title, ENT_QUOTES, 'UTF-8'));
			$mail->setText('');
			$mail->setHtml(html_entity_decode($this->html, ENT_QUOTES, 'UTF-8'));
			
			//var_dump($mail->send());

			$this->addHistory(array('transmission_id' => $mail->send()));
		}
		
		public function echoData($input = array()){
			$this->load->model('account/order');
			
			if (!$input && $this->request->server['REQUEST_METHOD'] === 'GET'){
				$input = $this->request->get;
			}
			
			if (!$input && $this->request->server['REQUEST_METHOD'] === 'POST'){
				$input = $this->request->post;
			}
			
			if (empty($input['order_id'])){
				$this->response->redirect($this->url->link('common/home'));
			}
			
			if (!$this->getOrderFast($input['order_id'])){
				$this->response->redirect($this->url->link('common/home'));
			}
			
			if ($input['tpl'] == 'trigger.cancelled.tpl'){
				$this->response->setOutput($this->cancelledOrders($input['order_id'], true));				
				} elseif ($input['tpl'] == 'trigger.cancelledbyterms.tpl') {
				$this->response->setOutput($this->cancelledByTerms($input['order_id'], true));
				} elseif ($input['tpl'] == 'trigger.completed.tpl') {
				$this->response->setOutput($this->completed($input['order_id'], true));
				} else {
				$this->response->redirect($this->url->link('common/home'));
			}
			
		}
		
		private function validateEmail($email){
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return false;
			}
			
			return true;
		}
		
		private function getStoreURL(){
			
			$this->load->model('setting/setting');
			$store_url = ($this->store_id==0)?HTTPS_SERVER:$this->model_setting_setting->getKeySettingValue('config', 'config_url' , $this->store_id);
			
			return $store_url;
		}
		
		private function makeURL($url){
			$this->load->model('account/customer');
			$customer = $this->model_account_customer->getCustomer($this->customer_id);
			
			$host =  parse_url($url, PHP_URL_HOST);
			$path =  parse_url($url, PHP_URL_PATH);
			$query = parse_url($url, PHP_URL_QUERY);						
			
			if ($query){
				$query = '?utm_source=Email&utm_medium=Trigger&utm_campaign=' . $this->triggerType . '&utm_term='. $customer['email'] . '&tracking=' . $this->tracking . '&utoken='.md5(md5($customer['email'].$customer['email'])) . '&' . $query;
				} else {
				$query = '?utm_source=Email&utm_medium=Trigger&utm_campaign=' . $this->triggerType . '&utm_term='. $customer['email'] . '&tracking=' . $this->tracking . '&utoken='.md5(md5($customer['email'].$customer['email']));
			}
			
			
			
			$url = $this->getStoreURL($this->store_id) . ltrim($path, '/') . $query; 
			
			return $this->shortAlias->shortenURL($url);
			
		}
		
		private function selectEmail($data){
			
			if ($this->validateEmail($data['email'])){
				return $data['email'];
			}
			
			if ($this->validateEmail($data['cemail'])){
				return $data['cemail'];
			}
			
			return false;
		}
		
		private function getOrderFast($order_id){
			$query = $this->db->non_cached_query("SELECT o.order_id FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
			WHERE order_status_id > 0 AND order_id = '" . (int)$order_id . "' AND o.store_id = '" . (int)$this->config->get('config_store_id'). "'");												
			
			return $query->num_rows;
			
		}
		
		public function cancelledOrders($order_id = false, $return = false){
			
			ini_set('error_reporting', E_ALL);
			error_reporting(E_ALL);
			
			$this->setTriggerType('cancelled');
			
			$this->load->model('catalog/product');
			$this->load->model('kp/product');
			$this->load->model('catalog/category');
			$this->load->model('account/order');
			$this->load->model('account/customer');
			$this->load->model('tool/image');
			
			//Отобрать покупателей
			$query = $this->db->non_cached_query("SELECT o.order_id, o.email, c.email as cemail  FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
			WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "'
			AND reject_reason_id IN (2)
			AND order_id IN
			(SELECT order_id FROM order_history WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' 
			AND date_added > DATE_SUB(NOW(), INTERVAL 10 DAY)
			)
			AND order_id NOT IN 
			(SELECT order_id FROM trigger_history WHERE type = '" . $this->triggerType . "')");
			
			if ($order_id){
				$query = $this->db->non_cached_query("SELECT o.order_id, o.email, c.email as cemail  FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
				WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "'
				AND reject_reason_id IN (2)
				AND order_id = '" . (int)$order_id . "'");
			}					
			
			foreach ($query->rows as $row){
				
				
				$this->setOrderID($row['order_id']);
				
				
				//Подбор товаров
				$order = $this->model_account_order->getOrder($this->order_id, true);
				
				$this->setOrder($order);
				$this->setStoreID($order['store_id']);
				$this->setCustomerID($order['customer_id']);
				$this->setLanguageID($order['language_id']);
				
				$this->data['store_id'] = $this->store_id;
				
				$this->echoLine('[TRGR] Заказ ' . $this->order_id . ', покупатель ' . $row['email']);
				
				if (!$to = $this->selectEmail($row)){
					$this->echoLine('[TRGR] Мейла нет, пропускаем');
					continue;
				}
				
				if (!$customer = $this->model_account_customer->getCustomer($this->customer_id)){
					$this->echoLine('[TRGR] Покупателя нет, пропускаем');
					continue;
				}
				
				if (!$customer['newsletter']){
					$this->echoLine('[TRGR] Покупатель отписан, пропускаем');
					continue;
				}
				
				$products = $this->model_account_order->getOrderProductsNoGood($this->order_id);
				
				if (!$products){
					$products = $this->model_account_order->getOrderProducts($this->order_id);
				}
				
				$exclude = array();
				$this->data['products'] = array();
				foreach ($products as $product) {
					$this->echoLine('	[TRGR] Подбираем товары');
					
					$this->echoLine('		[TRGR] Товар ' . $product['name']);
					
					$this->data['products'][$product['product_id']] = array(
					'info' 		=> $this->prepareProduct($this->model_catalog_product->getProduct($product['product_id'])), 
					'products' 	=> array()
					);
					
					//var_dump($this->data['products'][$result['product_id']]);
					
					$similar_products = $this->model_kp_product->getReplaceProducts($product['product_id'], $this->order_id, $exclude);
					foreach ($similar_products as $key => $result){
						$exclude[] = $result['product_id'];
						$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);		
						$this->data['products'][$product['product_id']]['products'][] = $this->prepareProduct($result);
					}
				}
				
				$this->data['firstname'] 	= $order['firstname'];
				$this->data['store_url'] 	= $this->makeURL($this->getStoreURL());
				$this->data['new_url'] 		= $this->makeURL($this->url->link('product/product_new'));
				$this->data['brand_url'] 	= $this->makeURL($this->url->link('product/manufacturer'));
				$this->data['otpipiska_url'] = $this->makeURL($this->url->link('account/newsletter'));
				$this->data['sale_url'] 	= $this->makeURL($this->config->get('config_main_redirect_domain') . '/rasprodazha');
				$this->data['blog_url'] 	= $this->makeURL($this->config->get('config_main_redirect_domain') . '/blog');
				
				$this->data['browser_url'] = $this->makeURL($this->url->link('kp/triggers/echoData') . '?tpl=trigger.cancelled.tpl&order_id=' . (int)$this->order_id);
				
				$this->setTitle($this->data['title'] = trim($order['firstname']) . ', не пропустите товары которые могут Вас заинтересовать');
				
				$this->template = $this->config->get('config_template') . '/template/triggers/trigger.cancelled.tpl';
				$html = $this->render();	
				
				$this->setTo($row['email']);
				$this->setHTML($html);
				
				if (!$return){
					$this->sendTriggerMail();					
				}
				
				if ($return == 'html'){
					return $html;
				}
				
				if ($return == 'data'){
					return $this->data;
				}
				
				$this->document->setTitle($this->title);
				$this->response->setOutput($html);
				
				$this->echoLine('');
				
			}	
		}
		
		/* 
			Причина отмены заказа (reject_reason_id) => Сроки доставки не подходят (1)
		*/
		public function cancelledByTerms($order_id = false, $return = false){
			
			ini_set('error_reporting', E_ALL);
			error_reporting(E_ALL);
			
			//$this->setTriggerType('cancelled');
			$this->setTriggerType('cancelledbyterms');
			
			$this->load->model('catalog/product');
			$this->load->model('kp/product');
			$this->load->model('catalog/category');
			$this->load->model('account/order');
			$this->load->model('account/customer');
			$this->load->model('tool/image');
			
			//Отобрать покупателей
			$query = $this->db->non_cached_query("SELECT o.order_id, o.email, c.email as cemail  FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
			WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "'
			AND reject_reason_id IN (1)
			AND order_id IN
			(SELECT order_id FROM order_history WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' 
			AND date_added > DATE_SUB(NOW(), INTERVAL 10 DAY)
			)
			AND order_id NOT IN 
			(SELECT order_id FROM trigger_history WHERE type = '" . $this->triggerType . "')");
			
			if ($order_id){
				$query = $this->db->non_cached_query("SELECT o.order_id, o.email, c.email as cemail  FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
				WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "'
				AND reject_reason_id IN (1)
				AND order_id = '" . (int)$order_id . "'");
			}			
			
			foreach ($query->rows as $row){
				
				$this->setOrderID($row['order_id']);
				
				//Подбор товаров
				$order = $this->model_account_order->getOrder($this->order_id, true);
				
				$this->setOrder($order);
				$this->setStoreID($order['store_id']);
				$this->setCustomerID($order['customer_id']);
				$this->setLanguageID($order['language_id']);
				
				$this->data['store_id'] = $this->store_id;
				
				$this->echoLine('[TRGR] Заказ ' . $this->order_id . ', покупатель ' . $row['email']);
				
				
				if (!$to = $this->selectEmail($row)){
					//echo 'Мейла нет, пропускаем';
					$this->echoLine('[TRGR] Мейла нет, пропускаем');
					continue;
				}
				
				if (!$customer = $this->model_account_customer->getCustomer($this->customer_id)){
					//echo 'Покупателя нет, пропускаем';
					$this->echoLine('[TRGR] Покупателя нет, пропускаем');
					continue;
				}
				
				if (!$customer['newsletter']){
					//echo 'Покупатель отписан, пропускаем';
					$this->echoLine('[TRGR] Покупатель отписан, пропускаем');
					continue;
				}
				
				$products = $this->model_account_order->getOrderProductsNoGood($this->order_id);
				
				if (!$products){
					$products = $this->model_account_order->getOrderProducts($this->order_id);
				}
				
				$exclude = array();
				$this->data['products'] = array();
				foreach ($products as $product) {
					// Нужно проверить, если основной товар есть на складе в текущей стране, то исключить его из выдачи
					$is_stock = $this->model_kp_product->checkIfProductIsInWarehouseInCurrentCountry($product['product_id'], $this->store_id);
					
					if (!$is_stock) {
						$this->echoLine('	[TRGR] Подбираем товары');
						$this->echoLine('		[TRGR] Товар ' . $product['name']);
						$this->data['products'][$product['product_id']] = array(
						'info' 		=> $this->prepareProduct($this->model_catalog_product->getProduct($product['product_id'])), 
						'products' 	=> array()
						);
						
						$similar_products = $this->model_kp_product->getReplaceProducts($product['product_id'], $this->order_id, $exclude, true);
						
						if ($similar_products) {
							foreach ($similar_products as $key => $result){
								$exclude[] = $result['product_id'];
								$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);		
								$this->data['products'][$product['product_id']]['products'][] = $this->prepareProduct($result);
							}
							} else {
							//удалить из масива товар для которого нет похожих товаров (SIMILAR PRODUCTS)
							unset($this->data['products'][$product['product_id']]);
						}
						
					}
				}
				
				$this->data['firstname'] 	= $order['firstname'];
				$this->data['store_url'] 	= $this->makeURL($this->getStoreURL());
				$this->data['new_url'] 		= $this->makeURL($this->url->link('product/product_new'));
				$this->data['brand_url'] 	= $this->makeURL($this->url->link('product/manufacturer'));
				$this->data['otpipiska_url'] = $this->makeURL($this->url->link('account/newsletter'));
				$this->data['sale_url'] 	= $this->makeURL($this->config->get('config_main_redirect_domain') . '/rasprodazha');
				$this->data['blog_url'] 	= $this->makeURL($this->config->get('config_main_redirect_domain') . '/blog');
				
				$this->data['browser_url'] = $this->makeURL($this->url->link('kp/triggers/echoData') . '?tpl=trigger.cancelledbyterms.tpl&order_id=' . (int)$this->order_id);
				
				$this->setTitle($this->data['title'] = trim($order['firstname']) . ', товары с экспресс доставкой, специально для Вас!');
				
				$this->template = $this->config->get('config_template') . '/template/triggers/trigger.cancelledbyterms.tpl';
				
				// Если нет товаров то нет что отправлять
				if (empty($this->data['products'])) {				
					$this->echoLine('[TRGR] Не нашли товары, пропускаем');
					continue;
				}
				
				$html = $this->render();	
				
				$this->setTo($row['email']);
				$this->setHTML($html);
				
				if (!$return){
					$this->sendTriggerMail();					
				}
				
				if ($return == 'html'){
					return $html;
				}
				
				if ($return == 'data'){
					return $this->data;
				}
				
				$this->document->setTitle($this->title);
				$this->response->setOutput($html);
				
				$this->echoLine('');
				
			}	
		}
		
		/**
			* Отбор выполненных заказов, за 14 дней. Подбор сопутствуйющих товаров.
		*/
		public function completed($order_id = false, $return = false) {
			ini_set('error_reporting', E_ALL);
			error_reporting(E_ALL);
			
			$this->setTriggerType('completed');
			
			$this->load->model('catalog/product');
			$this->load->model('kp/product');
			$this->load->model('catalog/category');
			$this->load->model('account/order');
			$this->load->model('account/customer');
			$this->load->model('tool/image');

			$this->load->model('setting/setting');

			//Отобрать покупателей
			$query = $this->db->non_cached_query("SELECT o.order_id, o.email, c.email as cemail FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
			WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "'
			AND order_id IN
			(SELECT order_id FROM order_history WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "' 
			AND date_added > DATE_SUB(NOW(), INTERVAL 15 DAY) AND date_added < DATE_SUB(NOW(), INTERVAL 14 DAY)
			)
			AND order_id NOT IN 
			(SELECT order_id FROM trigger_history WHERE type = '" . $this->triggerType . "')");
			
			if ($order_id) {
				$query = $this->db->non_cached_query("SELECT o.order_id, o.email, c.email as cemail FROM `order` o LEFT JOIN customer c ON (o.customer_id = c.customer_id)			
				WHERE order_status_id = '" . $this->config->get('config_complete_status_id') . "'
				AND order_id = '" . (int)$order_id . "'");
			}	
			
			foreach ($query->rows as $row) {
				
				$this->setOrderID($row['order_id']);
				
				//Подбор товаров
				$order = $this->model_account_order->getOrder($this->order_id, true);

				// id товаров в заказе, добавлено ниже
				$order_products = array();

				$this->setOrder($order);
				$this->setStoreID($order['store_id']);
				$this->setCustomerID($order['customer_id']);
				$this->setLanguageID($order['language_id']);
				
				$this->data['store_id'] = $this->store_id;
				
				$this->echoLine('[TRGR] Заказ ' . $this->order_id . ', покупатель ' . $row['email']);
				
				
				if (!$to = $this->selectEmail($row)) {
					//echo 'Мейла нет, пропускаем';
					$this->echoLine('[TRGR] Мейла нет, пропускаем');
					continue;
				}
				
				if (!$customer = $this->model_account_customer->getCustomer($this->customer_id)) {
					//echo 'Покупателя нет, пропускаем';
					$this->echoLine('[TRGR] Покупателя нет, пропускаем');
					continue;
				}
				
				if (!$customer['newsletter']) {
					//echo 'Покупатель отписан, пропускаем';
					$this->echoLine('[TRGR] Покупатель отписан, пропускаем');
					continue;
				}
				
				$products = $this->model_account_order->getOrderProductsNoGood($this->order_id);
				
				if (!$products) {
					$products = $this->model_account_order->getOrderProducts($this->order_id);
				}
				
				$order_products = array();
				$categories = array();
				
				if ($products) {
					foreach ($products as $product) {
						$cat = $this->model_catalog_product->getProductCategories($product['product_id']);
						
						// Добавить заказанные товары в массив
						$order_products[] = $product['product_id'];
						
						foreach ($cat as $category) {
							$e = $this->model_catalog_category->getCategory($category);
							// $e = проверяем доступна ли категория в стране о.О
							if ($e) {
								if (!in_array($e['category_id'], $categories)) {
									$categories[] = $e['category_id'];
								}
							}
						}
					}
				}
				
				$this->data['products'] = array();
				$this->data['products']['similar'] = array();
				$results = array();

				// Исключенные товары
				$exclude = $order_products;

				$this->model_kp_product->setOrderID($order_id);

				$limit = 9;
				$this->echoLine('	[TRGR] Подбираем товары по рекомендуемых');

				// Подбор рекомендуемых товаров по всех товарах в заказе
				$results = $this->model_kp_product->getProductRelated($order_products, $limit, $categories, $this->store_id, false, $order_id);

				if ($results) {
					foreach ($results as $key => $result) {
						$exclude[] = $result['product_id'];
						$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
						$this->data['products']['similar'][] = $this->prepareProduct($result);
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);

					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям с учетом бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCategory($order_products, $limit, $exclude, $this->store_id, true);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);

					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям без учета бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCategory($order_products, $limit, $exclude, $this->store_id);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);


					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям без учета бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCollection($order_products, $limit, $exclude, $this->store_id, true);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);


					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям без учета бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCollection($order_products, $limit, $exclude, $this->store_id);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				// ===== Нехватает? Вот тебе без учета склада в стране =====
				// Подбор рекомендуемых товаров по всех товарах в заказе
				$results = $this->model_kp_product->getProductRelated($order_products, $limit, $categories, $this->store_id, true, $order_id);

				if ($results) {
					foreach ($results as $key => $result) {
						$exclude[] = $result['product_id'];
						$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
						$this->data['products']['similar'][] = $this->prepareProduct($result);
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);

					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям с учетом бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCategory($order_products, $limit, $exclude, $this->store_id, true, true);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);

					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям без учета бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCategory($order_products, $limit, $exclude, $this->store_id, false, true);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);


					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям без учета бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCollection($order_products, $limit, $exclude, $this->store_id, true, true);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}

				if (count($this->data['products']['similar']) <= 9) {
					$limit = 9 - count($this->data['products']['similar']);


					$this->echoLine('	[TRGR] Подбираем товары по связанным категориям с учетом бренда производителя');
					// Подбираем товары по связанным категориям без учета бренда производителя
					$results = $this->model_kp_product->getProductRelatedByCollection($order_products, $limit, $exclude, $this->store_id, false, true);

					if ($results) {
						foreach ($results as $key => $result) {
							$exclude[] = $result['product_id'];
							$this->echoLine('		[SIMILAR] Товар ' . $key  . '  '. $result['name']);
							$this->data['products']['similar'][] = $this->prepareProduct($result);
						}
					}
				}
				// ===== Конец без учета склада в стране =====


				$this->data['firstname'] 		= $order['firstname'];
				$this->data['store_url'] 		= $this->makeURL($this->getStoreURL());
				$this->data['new_url'] 			= $this->makeURL($this->url->link('product/product_new'));
				$this->data['brand_url'] 		= $this->makeURL($this->url->link('product/manufacturer'));
				$this->data['otpipiska_url']	= $this->makeURL($this->url->link('account/newsletter'));
				$this->data['sale_url'] 		= $this->makeURL($this->config->get('config_main_redirect_domain') . '/rasprodazha');
				$this->data['blog_url'] 		= $this->makeURL($this->config->get('config_main_redirect_domain') . '/blog');
				
				$this->data['browser_url'] 		= $this->makeURL($this->url->link('kp/triggers/echoData') . '?tpl=trigger.completed.tpl&order_id=' . (int)$this->order_id);
				
				$this->setTitle($this->data['title'] = trim($order['firstname']) . ', не пропустите рекомендации к Вашей покупке! ');
				
				$this->template = $this->config->get('config_template') . '/template/triggers/trigger.completed.tpl';

				// Если нет товаров то нет что отправлять
				if (empty($this->data['products']['similar'])) {				
					$this->echoLine('[TRGR] Не нашли товары, пропускаем');
					continue;
				}
				
				$html = $this->render();	
				
				$this->setTo($row['email']);
				$this->setHTML($html);
				
				if (!$return){
					$this->sendTriggerMail();					
				}
				
				if ($return == 'html'){
					return $html;
				}
				
				if ($return == 'data'){
					return $this->data;
				}
				
				$this->document->setTitle($this->title);
				$this->response->setOutput($html);
				
				$this->echoLine('');
				
			}	
		}
	}	
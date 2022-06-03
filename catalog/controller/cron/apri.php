<?php   
class ControllerCronAPRI extends Controller {
	public function index() {
		
		$this->load->model('module/apri');		
		
		if (!isset($this->request->get['secret_code'])){
			echo "You forgot secret code";
			exit;
		}
		
		if ($this->request->get['secret_code'] != $this->config->get('apri_secret_code')){
			echo "Access Denied: Wrong secret code";
			exit;
		}
		
		$mail = new Mail(); 
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port 	= $this->config->get('config_smtp_port');
		$mail->timeout  = $this->config->get('config_smtp_timeout');	
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		
		$log_apri_mail = $this->config->get('apri_mail');
		$log_review_mail = $log_apri_mail[$this->config->get('config_language_id')];
		
		$log_mail_subject = html_entity_decode($log_review_mail['log_subject'], ENT_QUOTES, 'UTF-8');
		
		$orders = $this->model_module_apri->getAPRIOrders();
		
		if ($orders){
			
				$recipients_list = '';
			
				foreach($orders as $customer_order){
					$find = array(
						'{firstname}',
						'{lastname}',
						'{store_name}',
						'{store_email}',
						'{store_telephone}'
					);
					
					$replace = array(
						'firstname'        		   => $customer_order['firstname'],
						'lastname'         		   => $customer_order['lastname'],
						'store_name'               => $this->config->get('config_name'),
						'store_email'              => $this->config->get('config_email'),
						'store_telephone'          => $this->config->get('config_telephone')
					);
					
					$apri_mail = $this->config->get('apri_mail');
					
					$used_language = $customer_order['language_id'];
					
					if (!isset($apri_mail[$used_language])){
						$used_language = $this->config->get('config_language_id');
					}
					
					$review_mail = $apri_mail[$used_language];
					
					$subject = str_replace($find, $replace, html_entity_decode($review_mail['subject'], ENT_QUOTES, 'UTF-8'));
					
					$html = $this->getInvitationHtml($customer_order);
					
					if ($this->config->get('apri_use_html_email') && $this->isHTMLEmailExtensionInstalled()) {
						
						$this->load->model('tool/html_email');
						
						$html = $this->model_tool_html_email->getHTMLEmail($used_language, $subject, $html, 'html');
					} 	
					
					$mail->setSubject($subject);
					$mail->setTo($customer_order['email']);
					$mail->setHtml($html);
					$mail->send();
					
					$this->model_module_apri->setAsNotified($customer_order['list_orders']);
					
					echo "Sending to " . $customer_order['email'] . " ... DONE" . "<br />";
					$recipients_list .= $customer_order['email'] . "<br />";
					
				}
				
				$log_mail_message = str_replace('{recipients_list}', $recipients_list, html_entity_decode($log_review_mail['log_message'], ENT_QUOTES, 'UTF-8'));
				
		} else {
		
			echo "No customer need to be invited yet.";
			$log_mail_message = "No customer need to be invited yet.";
		}

		if ($this->config->get('apri_log_admin')) {
			
			$mail->setSubject($log_mail_subject);
			$mail->setTo($this->config->get('config_email'));
			$mail->setHtml($log_mail_message);
			$mail->send();
		}		
	}

	private function getInvitationHtml($customer_order){
		$this->load->model('module/apri');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
		$find = array(
			'{firstname}',
			'{lastname}',
			'{purchased_products_table}',
			'{store_name}',
			'{store_email}',
			'{store_telephone}',
			'{unsubscribe_link}'
		);
		
		$replace = array(
			'firstname'        		   => $customer_order['firstname'],
			'lastname'         		   => $customer_order['lastname'],
			'purchased_products_table' => $this->getPurchasedProductsHTMLTable($this->getOrdersProducts($customer_order['list_orders'])),
			'store_name'               => $this->config->get('config_name'),
			'store_email'              => $this->config->get('config_email'),
			'store_telephone'          => $this->config->get('config_telephone'),
			'unsubscribe_link'         => $this->url->link('cron/apri/unsubscribe', 'unsubscribe=' . md5(strtolower($customer_order['email'])))
		);
		
		$apri_mail = $this->config->get('apri_mail');
		
		$used_language = $customer_order['language_id'];
		
		if (!isset($apri_mail[$used_language])){
			$used_language = $this->config->get('config_language_id');
		}
		
		$review_mail = $apri_mail[$used_language];
		
		$subject = str_replace($find, $replace, html_entity_decode($review_mail['subject'], ENT_QUOTES, 'UTF-8'));
		$message = str_replace($find, $replace, html_entity_decode($review_mail['message'], ENT_QUOTES, 'UTF-8'));
		
		$template = new Template();
		
		$template->data['logo'] = $server . 'image/' . $this->config->get('config_logo');		
		$template->data['store_name'] = $this->config->get('config_name');	
		$template->data['store_url'] = $this->config->get('config_use_ssl') ? $this->config->get('config_ssl') : $this->config->get('config_url');
		$template->data['message'] = $message;
		
		if ($this->config->get('apri_use_html_email') && $this->isHTMLEmailExtensionInstalled()) {
			$html = $message;
		} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/apri.tpl')) {
			$html = $template->fetch($this->config->get('config_template') . '/template/mail/apri.tpl');
		} else {
			$html = $template->fetch('default/template/mail/apri.tpl');
		}
		
		return $html;
	}

	private function getOrdersProducts($list_orders){
		$this->load->model('module/apri');
		$this->load->model('tool/image');
		
		$products_data = array();
		
		$results = $this->model_module_apri->getAPRIOrderProducts($list_orders);
		
		if ($results){
			foreach($results as $result){
				
				$image = $this->model_tool_image->resize('no_image.jpg', 40,40);
				
				if (isset($result['image'])) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], 40,40);
					}
				}	
				
				$products_data[] = array( 
					'name'     => $result['name'],
					'href'     => $this->url->link('product/product', 'product_id=' . $result['product_id'], 'SSL'),
					'image'    => $image
				);
			}
		}
		
		return $products_data;
	}

	private function getPurchasedProductsHTMLTable($products){
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
		$template = new Template();
		
		$template->data['products'] = $products;
		$template->data['add_review_image'] = $server . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/apri/add-review.png';
		
		if ($this->config->get('apri_use_html_email') && $this->isHTMLEmailExtensionInstalled()) {
			
			$template->data['table_border_color'] = $this->config->get('html_email_main_table_border_color');		
			$template->data['table_body_bg'] = $this->config->get('html_email_main_table_body_bg');		
			$template->data['table_body_text_color'] = $this->config->get('html_email_main_table_body_text_color');		
			
		} else {
		
			$template->data['table_border_color'] = '#DDDDDD';		
			$template->data['table_body_bg'] = '#FFFFFF';		
			$template->data['table_body_text_color'] = '#000000';		
		
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/apri_products_table.tpl')) {
			$html = $template->fetch($this->config->get('config_template') . '/template/mail/apri_products_table.tpl');
		} else {
			$html = $template->fetch('default/template/mail/apri_products_table.tpl');
		}
		
		return $html;
	}

	private function isHTMLEmailExtensionInstalled() {
		$installed = false;
		
		if ($this->config->get('html_email_default_word') && file_exists(DIR_APPLICATION . 'model/tool/html_email.php')) {
			$installed = true;	
		}
		
		return $installed;
	}
	
	public function unsubscribe() {
		$this->load->model('module/apri');
	
		if ($this->config->get('apri_allow_unsubscribe') && isset($this->request->get['unsubscribe'])) {	
			$this->model_module_apri->unsubscribe($this->request->get['unsubscribe']);
		}
		
		$this->redirect($this->url->link('common/home')); 
	}	
}
?>
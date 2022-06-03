<?php
class ModelAffiliateAffiliate extends Model {
	public function addAffiliate($data) {
		$this->db->query("INSERT INTO affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape(uniqid()) . "', commission = '" . (float)$this->config->get('config_commission') . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '1', date_added = NOW()");
		
		$this->language->load('mail/affiliate');
		
		$query_id = $this->db->query("SELECT affiliate_id FROM `affiliate` WHERE email = '" . $this->db->escape($data['email']) . "'"); $affiliate_id = $query_id->row['affiliate_id'];
		$affiliate_add = (bool)$this->config->get('affiliate_add');
		$this->db->query("UPDATE `affiliate` SET qiwi = '" . $this->db->escape($data['qiwi']) . "', card = '" . $this->db->escape($data['card']) . "', yandex = '" . $this->db->escape($data['yandex']) . "', webmoney = '" . $this->db->escape($data['webmoney']) . "', webmoneyWMZ = '" . $this->db->escape($data['webmoneyWMZ']) . "', webmoneyWMU = '" . $this->db->escape($data['webmoneyWMU']) . "', webmoneyWME = '" . $this->db->escape($data['webmoneyWME']) . "', webmoneyWMY = '" . $this->db->escape($data['webmoneyWMY']) . "', webmoneyWMB = '" . $this->db->escape($data['webmoneyWMB']) . "', webmoneyWMG = '" . $this->db->escape($data['webmoneyWMG']) . "', AlertPay = '" . $this->db->escape($data['AlertPay']) .  "', Moneybookers = '" . $this->db->escape($data['Moneybookers']) .  "', LIQPAY = '" . $this->db->escape($data['LIQPAY']) . "', SagePay = '" . $this->db->escape($data['SagePay']) .  "', twoCheckout = '" . $this->db->escape($data['twoCheckout']) . "', GoogleWallet = '" . $this->db->escape($data['GoogleWallet']) . "', approved = '" . (int)$affiliate_add . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		if($this->config->get('config_affiliate_number_tracking')) {
			$code = 1000 + (int)$affiliate_id;
			$this->db->query("UPDATE `affiliate` SET code = '" . $code . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		}
		
		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		
		$message  = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
		if($affiliate_add){
			$message .= $this->language->get('text_approve_login') . "\n";
		}else{
			$message .= $this->language->get('text_approval') . "\n";
		}
		$message .= $this->url->link('affiliate/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$template = new EmailTemplate($this->request, $this->registry);
		$template->data['url_affiliate_login'] = $this->url->link('affiliate/login', '', 'SSL');
		$template->data['url_affiliate_login_tracking'] = $template->getTracking($template->data['url_affiliate_login']);
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($this->request->post['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));			
		$template->load('affiliate.register');
		$mail = $template->hook($mail);
		$mail->send();		
		$template->sent();

		$template->addData($data);
		
		$template->load('affiliate.register_admin');			
		
		$template->build();
		
		$mail = $template->hook($mail);
		
		$mail->send();
		
		$template->sent();
		
		if($affiliate_add){
			$message = $this->language->get('text_add') . "\n";
		}else{
			$message = $this->language->get('text_add_aplove') . "\n";
		}
		
		$message .= $this->db->escape($data['firstname']) . "\n";
		$message .= $this->db->escape($data['lastname']) . "\n";
		$message .= $this->db->escape($data['email']);
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}
	
	public function editAffiliate($data) {
		$this->db->query("UPDATE affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "' WHERE affiliate_id = '" . (int)$this->affiliate->getId() . "'");
	}
	
	public function editPayment($data) {
		
		$this->db->query("UPDATE `affiliate` SET qiwi = '" . $this->db->escape($data['qiwi']) . "', card = '" . $this->db->escape($data['card']) . "', yandex = '" . $this->db->escape($data['yandex']) . "', webmoney = '" . $this->db->escape($data['webmoney']) . "', webmoneyWMZ = '" . $this->db->escape($data['webmoneyWMZ']) . "', webmoneyWMU = '" . $this->db->escape($data['webmoneyWMU']) . "', webmoneyWME = '" . $this->db->escape($data['webmoneyWME']) . "', webmoneyWMY = '" . $this->db->escape($data['webmoneyWMY']) . "', webmoneyWMB = '" . $this->db->escape($data['webmoneyWMB']) . "', webmoneyWMG = '" . $this->db->escape($data['webmoneyWMG']) .  "', AlertPay = '" . $this->db->escape($data['AlertPay']) .  "', Moneybookers = '" . $this->db->escape($data['Moneybookers']) .  "', LIQPAY = '" . $this->db->escape($data['LIQPAY']) .  "', SagePay = '" . $this->db->escape($data['SagePay']) .  "', twoCheckout = '" . $this->db->escape($data['twoCheckout']) . "', GoogleWallet = '" . $this->db->escape($data['GoogleWallet']) . "' WHERE affiliate_id = '" . (int)$this->affiliate->getId() . "'");
		
		$this->db->query("UPDATE affiliate SET tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "' WHERE affiliate_id = '" . (int)$this->affiliate->getId() . "'");
	}
	
	public function editPassword($email, $password) {
		$this->db->query("UPDATE affiliate SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}
	
	public function getAffiliate($affiliate_id) {
		$query = $this->db->query("SELECT * FROM affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		
		return $query->row;
	}
	
	public function getAffiliateByEmail($email) {
		$query = $this->db->query("SELECT * FROM affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		
		return $query->row;
	}
	
	public function getAffiliateByCode($code) {
		$query = $this->db->query("SELECT * FROM affiliate WHERE code = '" . $this->db->escape($code) . "'");
		
		return $query->row;
	}
	
	public function getTotalAffiliatesByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM affiliate WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		
		return $query->row['total'];
	}
}
?>
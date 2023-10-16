<?php
class ControllerSaleFirstOrderAction extends Controller {
	public function cron(){
		if (!$this->config->get('config_firstorder_send_promocode')){
			echoLine('[ControllerFirstOrderAction] Action is disabled!', 'e');
			exit();
		}		

		$this->db->query("UPDATE customer c SET order_good_count = (SELECT COUNT(*) FROM `order` WHERE customer_id = c.customer_id AND order_status_id = '" . $this->config->get('config_complete_status_id') . "')");

		$sql = "SELECT o.* FROM `order_history` oh 
		LEFT JOIN `order` o ON (oh.order_id = o.order_id) 
		LEFT JOIN `customer` c ON (o.customer_id = c.customer_id)
		WHERE oh.order_status_id = '" . $this->config->get('config_complete_status_id') . "' 
		AND o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'
		AND DATE(oh.date_added) = '" . date('Y-m-d', strtotime('-1 day')) . "'
		AND c.order_good_count = 1";
		
		$query = $this->db->query($sql);

		if ($query->num_rows){
			if ($this->config->get('config_firstorder_send_promocode') == '1'){
				foreach ($query->rows as $order_info){
					echoLine('[ControllerFirstOrderAction] Working with order ' . $order_info['order_id'], 'i');

					if ($this->config->get('config_firstorder_email_enable')){
						if ($this->emailBlackList->check($order_info['email'])){
							echoLine('[ControllerFirstOrderAction] Mail ' . $order_info['email'] . ' is ok, sending', 's');
							$template = new EmailTemplate($this->request, $this->registry);
							$template->addData($order_info);

							$template_data = [
								'key' => $this->config->get('config_firstorder_email_template')								
							];

							$template->load($template_data);

							$mail = new Mail($this->registry);
							$mail->setIsMarketing(true);
							$mail->setTo($order_info['email']);
							$mail->setFrom($this->config->get('config_mail_trigger_mail_from'));

							$mail = $template->hook($mail); 
							$mail->send();
							$template->sent();
						}
					}

					$this->smsAdaptor->sendSMSFirstOrderPromo($order_info, []);
				}
			}
		}
	}
}
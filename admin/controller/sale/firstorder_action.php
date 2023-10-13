<?php
	class ControllerSaleFirstOrderAction extends Controller {



		public function cron(){
			if (!$this->config->get('config_firstorder_send_promocode')){
				echoLine('[ControllerFirstOrderAction] Action is disabled!', 'e');
				exit();
			}

			$query = $this->db->query("SELECT o.* FROM `order_history` oh 
				LEFT JOIN `order` o ON (oh.order_id = o.order_id) 
				WHERE oh.order_status_id = '" . $this->config->get('config_complete_status_id') . "' 
				AND o.order_status_id = '" . $this->config->get('config_complete_status_id') . "'
				AND oh.date_added = '" . date('Y-m-d', strtotime('-1 day')) . "'");


			if ($query->num_rows){
				foreach ($query->rows as $order){
					echoLine('[ControllerFirstOrderAction] Working with order ' . $order['order_id'], 'i');

					if ($this->config->get('config_firstorder_email_enable')){
						$template = new EmailTemplate($this->request, $this->registry);

					}
				}
			}









		}

	}
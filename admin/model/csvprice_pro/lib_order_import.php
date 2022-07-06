<?php

class ModelCSVPriceProLibOrderImport extends Model {

	//-------------------------------------------------------------------------
	// Constructor
	//-------------------------------------------------------------------------
	public function __construct($registry) {
		parent::__construct($registry);

		// Load Model
		//-------------------------------------------------------------------------
		$this->load->model('sale/order');
		$this->load->model('sale/customer');
	}

	public function addOrderHistory($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int) $data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int) $order_id . "', order_status_id = '" . (int) $data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int) $data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($data['notify']) {
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);

			$message = $language->get('text_order') . ' ' . $order_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $data['order_status_id'] . "' AND language_id = '" . (int) $order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$message .= $language->get('text_order_status') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}

			if ($order_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}

			if ($data['comment']) {
				$message .= $language->get('text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $language->get('text_footer');

			$this->load->model('setting/setting');

			$mail = new Mail($this->registry); 
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$order_info['store_id']));
			$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$order_info['store_id']));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0, $amount_notify = 0) {
		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");

			$this->language->load('mail/customer');

			if ($customer_info['store_id']) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
			} else {
				$store_name = $this->config->get('config_name');
			}

			if ($amount_notify) {
				$message = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
				$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->model_sale_customer->getTransactionTotal($customer_id)));

				$mail = new Mail($this->registry); 
				$mail->setTo($customer_info['email']);
				$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$customer_info['store_id']));
				$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id']));
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer_info['store_id'])), ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
			}
		}
	}

}
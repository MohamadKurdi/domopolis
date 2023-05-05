<?php

class ModelPaymentMono extends Model
{
    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `mono_orders` (
				`Id` int NOT NULL AUTO_INCREMENT,
				`InvoiceId` varchar(50) DEFAULT NULL,
				`OrderId` int(10) DEFAULT NULL,
				`SecretKey` varchar(51) DEFAULT NULL,
				`is_refunded` int(10) DEFAULT 0,
				`amount_refunded` decimal(15,4) DEFAULT 0.0000,
				`refund_status` varchar(51) DEFAULT NULL,
				`is_hold` int(10) DEFAULT 0,
				PRIMARY KEY (Id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		// $this->load->model('extension/event');
		// $this->model_extension_event->addEvent('payment_mono', 'admin/view/sale/order_info/before', 'payment/mono/order_info');
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `mono_orders`;");
    }

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `mono_orders` WHERE `Orderid` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			return $order;
		} else {
			return false;
		}
	}


	public function addRefund($data, $amount_refunded, $refund_status){
		$invId = $data;
		$this->db->query("UPDATE `mono_orders` SET `is_refunded` = 1, `amount_refunded` = '" . $amount_refunded . "', `refund_status` = '" . $refund_status . "'  WHERE InvoiceId = '" . $invId . "'");
	}

	public function addRefundHistory($order_id){
		$this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '11', notify = '0', comment = 'Monobank: Refund', date_added = NOW()");
	}

	public function addHoldHistory($order_id){
		$this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '2', notify = '0', comment = 'Monobank: confirm hold', date_added = NOW()");
		$this->db->query("UPDATE `mono_orders` SET `is_hold` = 1 WHERE OrderId = '" . (int)$order_id . "'");
	}
}
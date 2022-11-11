<?php
class ModelCatalogCronjob extends Model {
	public function getOrders($settings = null, $id = 0) {
		$orders = array();

		if ($settings) {
			foreach ($settings as $store_id => $setting) {
				if ($setting['review_booster_status']) {
					if ($setting['review_booster_status_order_id'] && is_array($setting['review_booster_status_order_id'])) {
						$statuses = "";

						foreach ($setting['review_booster_status_order_id'] as $status_id) {
							$statuses .= "o.order_status_id = '" . (int)$status_id . "' OR ";
						}

						$statuses = rtrim($statuses, ' OR ');
					} else {
						$statuses = "o.order_status_id = '0'";
					}

					if (isset($setting['review_booster_previous_customer']) && $setting['review_booster_previous_customer']) {
						$query = $this->db->query("SELECT o.email, o.date_added, CONCAT(o.firstname, ' ', o.lastname) AS client, o.language_id, o.order_id, o.store_id, (SELECT s.value FROM `setting` s WHERE s.key = 'config_url' AND s.store_id = o.store_id) AS store_url, (SELECT s.value FROM `setting` s WHERE s.key = 'config_email' AND s.store_id = o.store_id) AS owner_email, (SELECT s.value FROM `setting` s WHERE s.key = 'config_name' AND s.store_id = o.store_id) AS store_name, (SELECT s.value FROM `setting` s WHERE s.key = 'config_template' AND s.store_id = o.store_id) AS config_template FROM `order` o WHERE (" . $statuses . ") AND o.review_alert = '0' AND o.store_id = '" . (int)$store_id . "' AND DATE(o.date_added) <= (CURDATE() - INTERVAL " . (int)$setting['review_booster_day'] . " DAY)");
					} else {
						$query = $this->db->query("SELECT o.email, o.date_added, CONCAT(o.firstname, ' ', o.lastname) AS client, o.language_id, o.order_id, o.store_id, (SELECT s.value FROM `setting` s WHERE s.key = 'config_url' AND s.store_id = o.store_id) AS store_url, (SELECT s.value FROM `setting` s WHERE s.key = 'config_email' AND s.store_id = o.store_id) AS owner_email, (SELECT s.value FROM `setting` s WHERE s.key = 'config_name' AND s.store_id = o.store_id) AS store_name, (SELECT s.value FROM `setting` s WHERE s.key = 'config_template' AND s.store_id = o.store_id) AS config_template FROM `order` o WHERE (" . $statuses . ") AND o.review_alert = '0' AND o.store_id = '" . (int)$store_id . "' AND DATE(o.date_added) = (CURDATE() - INTERVAL " . (int)$setting['review_booster_day'] . " DAY)");
					}

					foreach ($query->rows AS $order) {
						$orders[] = $order;
					}
				}
			}
		} else {
			$query = $this->db->query("SELECT o.email, o.date_added, CONCAT(o.firstname, ' ', o.lastname) AS client, o.language_id, o.order_id, o.store_id, (SELECT s.value FROM `setting` s WHERE s.key = 'config_url' AND s.store_id = o.store_id) AS store_url, (SELECT s.value FROM `setting` s WHERE s.key = 'config_email' AND s.store_id = o.store_id) AS owner_email, (SELECT s.value FROM `setting` s WHERE s.key = 'config_name' AND s.store_id = o.store_id) AS store_name, (SELECT s.value FROM `setting` s WHERE s.key = 'config_template' AND s.store_id = o.store_id) AS config_template FROM `order` o WHERE o.store_id = '" . (int)$id . "' ORDER BY o.order_id DESC LIMIT 1");	

			$orders = $query->rows;
		}
		
		return $orders;
	}

	public function getSetting() {
		$data = array();

		$query = $this->db->query("SELECT * FROM setting WHERE `group` = 'review_booster'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['store_id']][$result['key']] = $result['value'];
			} else {
				$data[$result['store_id']][$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	
	public function getProducts($order_id){
		$sql = "SELECT DISTINCT A.*, B.name, B.description, (SELECT price FROM product_special ps WHERE ps.product_id = A.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM product A INNER JOIN product_description B ON (A.product_id=B.product_id) INNER JOIN order_product C ON (A.product_id = C.product_id) WHERE A.product_id = C.product_id AND C.order_id = '" . (int)$order_id . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCoupons() {
		$coupons = array();

		$query = $this->db->query("SELECT * FROM `coupon` WHERE ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = '1'");

		if ($query->num_rows) {
			foreach ($query->rows AS $result) {
				$coupons[$result['coupon_id']] = $result;
			}
		}

		return $coupons;
	}
}
?>
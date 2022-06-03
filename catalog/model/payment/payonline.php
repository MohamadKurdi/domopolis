<?php 
/**
 * e-mail: s-m-o-k@list.ru
 */
class ModelPaymentPayonline extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/payonline');

		$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payonline_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payonline_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payonline_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'payonline',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('payonline_sort_order')
			);
		}

		return $method_data;
	}
}

?>
<?php 
class ModelPaymentCOD extends Model {
	public function getMethod($address, $total) {
		$this->language->load('payment/cod');

		$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('cod_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('cod_total') > 0 && $this->config->get('cod_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('cod_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$discount_sum = $this->currency->format(($this->cart->getSubTotal() / 100 * $this->config->get('shoputils_payment_discounts_percent')));

		$method_data = array();

		if ($status || true) {  
					
			$method_data = array(
				'code'       => 'cod',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('cod_sort_order'),
				/*'tip'        => (!isset($this->session->data['coupon']) or mb_strlen($this->session->data['coupon'])==0)?sprintf($this->language->get('text_tip'), $this->config->get('shoputils_payment_discounts_percent'), $discount_sum):sprintf($this->language->get('text_tip_coupon'), $this->config->get('shoputils_payment_discounts_percent')),*/
				'tip' => $this->language->get('text_tip'),
				'is_prepay'       => true,
				'status'     => $status,
				'checkactive' => true
			);
		}

		return $method_data;
	}
}
?>
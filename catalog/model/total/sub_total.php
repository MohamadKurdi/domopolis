<?php
class ModelTotalSubTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/sub_total');
		
		$sub_total = $this->cart->getSubTotal();
		$sub_total_national = $this->cart->getSubTotalInNationalCurrency();
		
		if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
				$sub_total_national += $this->currency->convert($voucher['amount'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
			}
		}
		
		$total_data[] = array( 
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($sub_total_national, $this->config->get('config_regional_currency'), '1'),
			'value_national' => $sub_total_national,
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);
		
		$total += $sub_total;
	}
}
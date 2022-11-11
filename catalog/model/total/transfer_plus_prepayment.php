<?

class ModelTotalTransferPlusPrePayment extends Model {
	
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/prepayment_detailed');
						
		$prepayment=false;
				
		$payment_method = isset($this->session->data['payment_method']['code']) ? $this->session->data['payment_method']['code'] : '';
		$payment_methods = explode(',', $this->config->get('config_confirmed_prepay_payment_ids'));
				
		if (isset($this->session->data['payment_method']) && 
			in_array($payment_method, $payment_methods)
			&& isset($this->session->data['prepay_sum'])
			&& isset($this->session->data['prepay_perc'])
			)
			{
					$prepayment = $this->session->data['prepay_sum'];
					$perc = $this->session->data['prepay_perc'];
					
					$value = $prepayment;
					$value_national = $this->currency->convert($value, $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
			} else {
				return;			
			}
			
		
		
		if ($prepayment > 0) {		
			$total_data[] = array( 
					'code'       => 'transfer_plus_prepayment',
					'title'      => sprintf($this->language->get('text_custom_prepayment'), $perc),
					'text'       => $this->currency->format($value_national, $this->config->get('config_national_currency'), '1'),
					'value'      => $value,
					'value_national' => $value_national,
					'sort_order' => 9,
					//'comment'	 => $this->comment
				);				
		}
	}
}
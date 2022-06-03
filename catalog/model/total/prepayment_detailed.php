<?php
class ModelTotalPrepaymentDetailed extends Model {
	
	private $comment;
	
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/prepayment_detailed');
		
		$prepayment = $this->getPrepayment($total_data, $taxes);
		
		if (!is_array($total_data) || count($total_data) == 0) {
			return;
		}
		
		if ($prepayment > 0) {
			$total_data[] = array( 
					'code'       => 'prepayment_detailed',
					'title'      => $this->language->get('text_prepayment'),
					'text'       => $this->currency->format($prepayment),
					'value'      => $prepayment,
					'sort_order' => $this->config->get('prepayment_detailed_sort_order'),
					'comment'	 => $this->comment
				);
				
			$total = $prepayment;
		}
	}
	
	private function getPrepayment($total_data, $taxes) {
		
		$shipping_code = null;
		$payment_method_code = null;
		
		if (isset($this->session->data['shipping_method']['code'])) {
			$shipping_code = $this->session->data['shipping_method']['code'];
			$shipping_code = substr($shipping_code, 0, strpos($shipping_code, "."));
		}
		
		if (isset($this->session->data['payment_method']['code'])) {
			$payment_method_code = $this->session->data['payment_method']['code'];
		}
		
		return $this->calculatePrepayment($shipping_code, $payment_method_code, $total_data);
	}
	
	private function calculatePrepayment($shipping_code, $payment_method_code, $total_data) {
	
		$filter = $this->getPrepaymentFilter($shipping_code, $payment_method_code, $total_data);
		
		if ($filter != null) {
			if ($filter["amount_percent_fixed_option"] == "fixed") {
				return $filter["amount_fixed"];
			} else if ($filter["amount_percent_fixed_option"] == "percent") {
			
				$result = 0;
				
				foreach ($total_data as $total) {
					if (is_numeric($filter["amount_percent_" . $total["code"]])) {
						$result += $filter["amount_percent_" . $total["code"]] / 100 * $total["value"];
					}
				}
				
				return $result;
			}
		}
		
		return 0;
	}
	
	public function getPrepaymentFilter($shipping_code, $payment_method_code, $total_data) {
		
		$sub_total_amount = 0;
		
		foreach ($total_data as $total) {
			if ($total["code"] == "sub_total") {
				$sub_total_amount = $total["value"];
				break;
			}
		}
		
		$filters = $this->config->get('prepayment_detailed_filter');
		
		if (!is_array($filters) || empty($filters)) {
			return null;
		}
		
		foreach ($filters as $filter) {
			
			if (isset($filter["shipping_method"]) && !array_key_exists($shipping_code, $filter["shipping_method"])) {
				continue;
			}
			
			if (!isset($filter["payment_method"]) || !array_key_exists($payment_method_code, $filter["payment_method"])) {
				continue;
			}
						
			$min_price_passes = false;
			if (	(is_numeric($filter["price_from"]) && $filter["price_from"] <= $sub_total_amount) 
				||	!is_numeric($filter["price_from"])) {
				$min_price_passes = true;
			}
			
			$max_price_passes = false;
			if (	(is_numeric($filter["price_to"]) && $filter["price_to"] >= $sub_total_amount) 
				||	!is_numeric($filter["price_to"])) {
				$max_price_passes = true;
			}
			
			
			if ($min_price_passes && $max_price_passes) {
				$this->comment = $filter['comment'];
				return $filter;
			}
		}
		
		return null;
	}
}
?>
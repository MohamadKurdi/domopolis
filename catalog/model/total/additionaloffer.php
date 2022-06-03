<?php
class ModelTotalAdditionaloffer extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/additionaloffer');
		
		$additionaloffer_total = 0;
		
		if(isset($this->session->data['additionaloffer'])) {
		  foreach($this->session->data['additionaloffer'] as $key=>$price) {
		      if(!isset($this->session->data['not_additionaloffer'][$key])) {
		      $additionaloffer_total = $additionaloffer_total + $price;
              }
		  }
		}
		
		$total_data[] = array( 
			'code'       => 'additionaloffer',
			'title'      => $this->language->get('text_additionaloffer'),
			'text'       => $this->currency->format($additionaloffer_total),
			'value'      => $additionaloffer_total,
			'value_national'  => $additionaloffer_total,
			'sort_order' => $this->config->get('additionaloffer_sort_order')
		);
		
		$total += $additionaloffer_total;
	}
}
?>
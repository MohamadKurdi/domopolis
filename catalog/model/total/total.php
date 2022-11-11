<?php
	class ModelTotalTotal extends Model {
		public function getTotal(&$total_data, &$total, &$taxes) {
			$this->language->load('total/total');
			
			$total_national = 0;
			
			foreach ($total_data as $one_data){
				if (isset($one_data['value_national']) && is_numeric($one_data['value_national'])){
					$total_national += $one_data['value_national'];			
					} else {
					if (is_numeric($one_data['value'])){
						$total_national += $this->currency->convert($one_data['value'], $this->config->get('config_currency'), $this->config->get('config_regional_currency'));		
					}
				}		
			}
			
			$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'text'       => $this->currency->format(max(0, $total_national), $this->config->get('config_regional_currency'), 1),
			'value'      => $this->currency->convert($total_national, $this->config->get('config_regional_currency'), $this->config->get('config_currency')),//max(0, $total),
			'value_national' => $total_national,
			'sort_order' => $this->config->get('total_sort_order')
			);
		}
	}
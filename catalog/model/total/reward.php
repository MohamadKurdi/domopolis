<?php
	class ModelTotalReward extends Model {
		public function getTotal(&$total_data, &$total, &$taxes) {
			
			
			
			if ($this->cart->canUseRewards() && isset($this->session->data['reward'])) {
				
				$this->session->data['reward'] = abs((int)$this->session->data['reward']);
				
				$this->language->load('total/reward');
				
				$points = $this->customer->getRewardPoints();
				
				
				$sub_total = $this->cart->getSubTotalInNationalCurrency();
				$max_points_to_use = (int)($sub_total * ($this->config->get('config_reward_maxsalepercent') / 100));
				$points = ($points > $max_points_to_use) ? $max_points_to_use : $points;
				
				if ($this->session->data['reward'] > $points){
					$this->session->data['reward'] = $points;					
				}
				
				if ($this->session->data['reward'] <= $points) {									
					$points = $this->session->data['reward'];
					
					$discount_total_national = $points;
					$discount_total = $this->currency->convert($discount_total_national, $this->config->get('config_regional_currency'), $this->config->get('config_currency'));
					
					if ($points){
						$total_data[] = array(
						'code'       		=> 'reward',
						'title'      		=> sprintf($this->language->get('text_reward'), $points),
						'text'       		=> $this->currency->format(-$discount_total_national, $this->config->get('config_regional_currency'), '1'),
						'value_national'    => -$discount_total_national,
						'value'   			=> -$discount_total,
						'sort_order' 		=> $this->config->get('reward_sort_order')
						);
						
						$total -= $discount_total;
					}
				} 
			}
		}
		
		public function getAllPositiveRewards($customer_id){
			$query = $this->db->query("SELECT * FROM customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND points > 0 ORDER BY date_added ASC");
			
			return $query->rows;
			
		}
		
		public function confirm($order_info, $order_total) {
			
			if ($order_total['code'] == 'reward'){
				$this->language->load('total/reward');
				
				$points = 0;
				
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');
				
				if ($start && $end) {  
					$points = substr($order_total['title'], $start, $end - $start);
				}	
				
				if ((int)$points) {			
					$this->customer->addReward(false, $this->db->escape(sprintf($this->language->get('text_reward_description'), (int)$order_info['order_id'])), (float)-$points, (int)$order_info['order_id'], $reason_code = 'ORDER_PAYMENT');	
				}
			}
			
		}		
	}						
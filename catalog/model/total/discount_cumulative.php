<?php
class ModelTotalDiscountCumulative extends Model {
	public function getTotal(&$total_data, &$total) {
            $customer_group_id = -1;
            if ($this->customer->isLogged()) {
                //$query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$this->customer->isLogged() . "'");
                //$customer_group_id = $query->row['customer_group_id'];       
                $customer_group_id = $this->customer->getCustomerGroupId();
            } 
            
            
            if ($this->customer->isLogged() && ($customer_group_id == $this->config->get('discount_cumulative_group') || $this->config->get('discount_cumulative_group') == 0) && $this->config->get('discount_cumulative_status') > 0) {                         
       
                $this->load->language('total/discount_cumulative');
                $this->load->model('setting/setting');
                $today = date('Y-m-d');
              
                //Инициализация массива скидок $discount_data[cumulative][proc]
                $discount_cumulative_rows_num = $this->config->get('discount_cumulative_rows_num');
                for ($num = 1; $num <= $discount_cumulative_rows_num; $num++) {  
                //   $discount_cumulative_row_name = 'discount_cumulative_row' . $num . '_name'; 
                //   $discount_cumulative_row_color = 'discount_cumulative_row' . $num . '_color'; 
                    $discount_cumulative_row_cumulative = 'discount_cumulative_row' . $num . '_cumulative';
                    $discount_cumulative_row_proc = 'discount_cumulative_row' . $num . '_proc';
                    $discount_cumulative_row_start = 'discount_cumulative_row' . $num . '_start'; 
                    $discount_cumulative_row_end = 'discount_cumulative_row' . $num . '_end'; 
                    $discount_cumulative_row_sum = 'discount_cumulative_row' . $num . '_sum'; 
              
                    if ((int)$this->config->get($discount_cumulative_row_cumulative) > 0 && (int)$this->config->get($discount_cumulative_row_proc) > 0
                            && ($today >=  $this->config->get($discount_cumulative_row_start || $this->config->get($discount_cumulative_row_start) == '0000-00-00'))
                            && ($today <= $this->config->get($discount_cumulative_row_end) || $this->config->get($discount_cumulative_row_end) == '0000-00-00')) {
                        
                        //Накопленная сумма
                        $sum_total = 0;
                        if ($this->config->get($discount_cumulative_row_sum) == 0 || $this->config->get($discount_cumulative_row_start) == '0000-00-00' || $this->config->get($discount_cumulative_row_end) == '0000-00-00') { 
                            $query = $this->db->query("SELECT sum(total) as total FROM `order` WHERE customer_id = '" . $this->customer->isLogged() . "' AND order_status_id = '" . $this->config->get('discount_cumulative_order_status') . "'");
                            $sum_total = $query->row['total'];
                        } else {
                            $query = $this->db->query("SELECT sum(total) as total FROM `order` WHERE customer_id = '" . $this->customer->isLogged() . "' AND order_status_id = '" . $this->config->get('discount_cumulative_order_status') . "' AND date_added > '" . $this->config->get($discount_cumulative_row_start) . "' AND date_added <= '" . $this->config->get($discount_cumulative_row_end) . " 23:59:59'");
                            $sum_total = $query->row['total'];
                        }
                            
                            
                        $discount_data[] = array( 
                       //    'discount_cumulative_row_name'       => $this->config->get($discount_cumulative_row_name),
                       //    'discount_cumulative_row_color'      => $this->config->get($discount_cumulative_row_color),
                            'discount_cumulative_row_cumulative' => $this->config->get($discount_cumulative_row_cumulative),
                            'discount_cumulative_row_proc'       => $this->config->get($discount_cumulative_row_proc),
                       //    'discount_cumulative_row_start'      => $this->config->get($discount_cumulative_row_start),
                       //    'discount_cumulative_row_end'       => $this->config->get($discount_cumulative_row_end),
                            'discount_cumulative_row_sum'        => $sum_total
                        );
                    }
                }
                     
                
                
                //Проверка условия скидки
                $discount_proc = 0;
                if (isset($discount_data)) {
                    $discount_data = $this->sort_array($discount_data, 'discount_cumulative_row_proc');
                    foreach ($discount_data as $disc)    
                        if ($disc['discount_cumulative_row_sum'] >= $disc['discount_cumulative_row_cumulative']) {
                            $discount_proc = $disc['discount_cumulative_row_proc'];  
                            $discount_cumulative = $disc['discount_cumulative_row_cumulative']; 
                        }              
                }
                
                //Если скидка есть
                if ($discount_proc > 0) {
                    $discount = $discount_proc / 100;
                    $total_data[] = array( 
                            'code'       => 'discount_cumulative',
                            'title'      => $this->language->get('text_discount_cumulative') . ' (' . $discount_proc . '%)',
                            'text'       => $this->currency->format(-$total * $discount),
                            'discount'   => $discount,
                            'value'      => -$total * $discount,
                            'sort_order' => $this->config->get('discount_cumulative_sort_order')
                    );         
                    $total -= $total * $discount;
                    $this->load->model('total/discount_select');
                    $this->model_total_discount_select->getSelection($total_data, $total);
                }   
            }
       }
       
       private function sort_array($array, $by) {
            $result = array();  
            foreach ($array as $val) {
                if (!is_array($val) || !key_exists($by, $val)) {
                    continue;
                }
                end($result);
                $current = current($result);
                while ($current[$by] > $val[$by]) {
                    $result[key($result)+1] = $current;
                    prev($result);
                    $current = current($result);
                }
                $result[key($result)+1] = $val;
            }
            return $result;
        }
}
?>
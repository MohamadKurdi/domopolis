<?php
class ModelTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total) {
            
            if ($this->customer->isLogged()) {
                $customer_id = $this->customer->isLogged();
                $customer_group_id = -1;
                $query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$this->customer->isLogged() . "'");
                $customer_group_id = $query->row['customer_group_id'];             
            
                //Инициализация массива скидок $discount_data[group][proc]
                $discount_rows_num = $this->config->get('discount_rows_num');
                for ($num = 1; $num <= $discount_rows_num; $num++) {  
                    $discount_row_group = 'discount_group_row' . $num;
                    $discount_row_proc = 'discount_row' . $num . '_proc';
                    $discount_row_customer_list = 'discount_row' . $num . '_customer_list';

                    if ((int)$this->config->get($discount_row_group) >= 0 && (int)$this->config->get($discount_row_proc) > 0) {
                        $discount_data[] = array( 
                            'discount_row_group' => $this->config->get($discount_row_group),
                            'discount_row_proc'  => $this->config->get($discount_row_proc),
                            'discount_row_list'  => $this->config->get($discount_row_customer_list)
                        );
                    }    
                }
                
                
                //Проверка условия скидки
                $discount_proc = 0;
                if (isset($discount_data)) {
                    $discount_data = $this->sort_array($discount_data, 'discount_row_proc');  
                    foreach ($discount_data as $disc)    
                        if ($customer_group_id == $disc['discount_row_group'] || $disc['discount_row_group'] == 0) {
                            $discount_proc = $disc['discount_row_proc'];  
                        } else if (($disc['discount_row_group'] == 777) && is_array($disc['discount_row_list'])) {
                            if (in_array($customer_id, $disc['discount_row_list']))
                                $discount_proc = $disc['discount_row_proc'];  
                        } 
                }

                if ($discount_proc > 0) {  
                        $this->load->language('total/discount');   
                        $discount = $discount_proc / 100;
                        $total_data[] = array( 
                                'code'       => 'discount',
                                'title'      => $this->language->get('text_discount') . ' (' . $discount_proc . '%)',
                                'text'       => $this->currency->format(-$total * $discount),
                                'discount'   => $discount,
                                'value'      => -$total * $discount,
                                'sort_order' => $this->config->get('discount_sort_order')
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
<?php
class ModelTotalDiscountSum extends Model {
	public function getTotal(&$total_data, &$total) {
            
            $this->load->model('setting/setting');
            $customer_group_id = -1;
            if ($this->customer->isLogged()) {
                $query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$this->customer->isLogged() . "'");
                $customer_group_id = $query->row['customer_group_id'];             
            } 
                   
            if ($this->config->get('discount_sum_status') > 0 && ($customer_group_id == $this->config->get('discount_sum_group') || $this->config->get('discount_sum_group') == 0 || ($this->config->get('discount_sum_group') == 777 && $this->customer->isLogged()))) {   
               
                $this->load->language('total/discount_sum');
              
                //Инициализация массива скидок $discount_data[count][proc]
                $discount_sum_rows_num = $this->config->get('discount_sum_rows_num');
                for ($num = 1; $num <= $discount_sum_rows_num; $num++) {  
                    $discount_sum_row_count = 'discount_sum_row' . $num . '_count';
                    $discount_sum_row_proc = 'discount_sum_row' . $num . '_proc';
              
                    if ((int)$this->config->get($discount_sum_row_count) > 0 && (int)$this->config->get($discount_sum_row_proc) > 0) {
                        $discount_data[] = array( 
                            'discount_sum_row_count' => $this->config->get($discount_sum_row_count),
                            'discount_sum_row_proc'  => $this->config->get($discount_sum_row_proc)
                        );
                    }
                }
                    
                //Общая сумма заказа
                $sub_total = $this->cart->getSubTotal();
                
                //Проверка количества под условия скидки
                $discount_proc = 0;
                if (isset($discount_data)) {
                    $discount_data = $this->sort_array($discount_data, 'discount_sum_row_proc');
                    foreach ($discount_data as $disc)    
                        if ($sub_total >= $disc['discount_sum_row_count']) {
                            $discount_proc = $disc['discount_sum_row_proc'];  
                            $discount_sum = $disc['discount_sum_row_count']; 
                        }              
                }
                
                //Если скидка есть
                if ($discount_proc > 0) {
                    $discount = $discount_proc / 100;
                    $total_data[] = array( 
                            'code'       => 'discount_sum',
                            'title'      => $this->language->get('text_discount_sum') . ' (' . $discount_proc . '%)',
                            'text'       => $this->currency->format(-$total * $discount),
                            'discount'   => $discount,
                            'value'      => -$total * $discount,
                            'sort_order' => $this->config->get('discount_sum_sort_order')
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
<?php
class ModelTotalDiscountCount extends Model {
	public function getTotal(&$total_data, &$total) {
            
            $this->load->model('setting/setting');
            $customer_group_id = -1;
            $product1 = $this->config->get('discount_count_product1'); 
            $product2 = $this->config->get('discount_count_product2'); 
            $product3 = $this->config->get('discount_count_product3');
            if ($this->customer->isLogged()) {
                $query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$this->customer->isLogged() . "'");
                $customer_group_id = $query->row['customer_group_id'];             
            } 
                   
            if ($this->config->get('discount_count_status') > 0 && ($customer_group_id == $this->config->get('discount_count_group') || $this->config->get('discount_count_group') == 0 || ($this->config->get('discount_count_group') == 777 && $this->customer->isLogged()))) {   
               
                $this->load->language('total/discount_count');
              
                //Инициализация массива скидок $discount_data[count][proc]
                $discount_count_rows_num = $this->config->get('discount_count_rows_num');
                for ($num = 1; $num <= $discount_count_rows_num; $num++) {  
                    $discount_count_row_count = 'discount_count_row' . $num . '_count';
                    $discount_count_row_proc = 'discount_count_row' . $num . '_proc';
              
                    if ((int)$this->config->get($discount_count_row_count) > 0 && (int)$this->config->get($discount_count_row_proc) > 0) {
                        $discount_data[] = array( 
                            'discount_count_row_count' => $this->config->get($discount_count_row_count),
                            'discount_count_row_proc'  => $this->config->get($discount_count_row_proc)
                        );
                    }
                }
                  
                
                //Подсчет количества
                $quantity = 0;
                $products = $this->cart->getProducts();
                foreach ($products as $product)   
                    $quantity += $product['quantity'];
                
                if ($this->config->get('discount_count_sum') == 1) {
                    $query = $this->db->query("SELECT value FROM setting WHERE `group` = 'discount_product' AND `key` = 'discount_count_action'");
                    if (isset($query->row['value']) && $query->row['value'] == 1) {
                            $array_not_apply_id = $this->config->get('discount_count');

                            if(isset($array_not_apply_id)) 
                                foreach ($products as $product) {
                                    if (in_array($product['product_id'], $array_not_apply_id)) { 
                                        $quantity -= $product['quantity']; 
                                    } else if ($product1 == 1) {
                                        $product_special_query = $this->db->query("SELECT price FROM product_special WHERE product_id = '" . (int)$product['product_id']. "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                                        if ($product_special_query->num_rows) $quantity -= $product['quantity'];  
                                    } 
                                    else if ($product2 == 1) {
                                        $product_discount_query = $this->db->query("SELECT price FROM product_discount WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
                                        if ($product_discount_query->num_rows) $quantity -= $product['quantity']; 
                                    } else if ($product3 == 1) {
                                        $product_option_query = $this->db->query("SELECT product_id FROM product_option WHERE product_id = '" . (int)$product['product_id'] . "'");
                                        if ($product_option_query->num_rows) $quantity -= $product['quantity'];    
                                    }
                                }
                    }
                } 

                
                //Проверка количества под условия скидки
                $discount_proc = 0;
                if (isset($discount_data)) {
                    $discount_data = $this->sort_array($discount_data, 'discount_count_row_proc');
                    foreach ($discount_data as $disc)    
                        if ($quantity >= $disc['discount_count_row_count']) {
                            $discount_proc = $disc['discount_count_row_proc'];  
                            $discount_count = $disc['discount_count_row_count']; 
                        }              
                }
                
                //Если скидка есть
                if ($discount_proc > 0) {
                    $discount = $discount_proc / 100;
                    $total_data[] = array( 
                            'code'       => 'discount_count',
                            'title'      => $this->language->get('text_discount_count') . ' ' . $discount_count . $this->language->get('text_discount_count_unit') . ' (' . $discount_proc . '%)',
                            'text'       => $this->currency->format(-$total * $discount),
                            'discount'   => $discount,
                            'value'      => -$total * $discount,
                            'sort_order' => $this->config->get('discount_count_sort_order')
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
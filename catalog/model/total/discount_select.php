<?php
class ModelTotalDiscountSelect extends Model {
        public function getSelection(&$total_data, &$total) {        
            $code_discount = '';
            $title_discount = '';
            $max_discount = 0;
            $sort_order_discount = 0;
            $total_minus = 0;

            foreach ($total_data as $total_array) {
                if (isset($total_array['discount']) && $total_array['discount'] > $max_discount) {
                        $code_discount = $total_array['code'];             
                        $title_discount = $total_array['title'];
                        $max_discount = $total_array['discount'];
                        $sort_order_discount = $total_array['sort_order'];                                                    
                }
            }

            $index = 0;
            if ($max_discount > 0 && $code_discount <> '') {
                foreach ($total_data as $total_array) {
                    if ($total_array['code'] == 'discount' || $total_array['code'] == 'discount_regular' || $total_array['code'] == 'discount_count' || $total_array['code'] == 'discount_cumulative' || $total_array['code'] == 'discount_sum') {
                        $total -= $total_array['value'];
                        unset($total_data[$index]);
                    }
                    $index++;    
                }
            }

            $total_data = array_values($total_data);

            if ($max_discount > 0 && $code_discount <> '') {
                $query = $this->db->query("SELECT value FROM setting WHERE `group` = 'discount_product' AND `key` = 'discount_product_status'");
                if(isset($query->row['value']) && $query->row['value'] == 1) {
                        $this->load->model('total/discount_select');
                        $total_minus = $this->model_total_discount_product->getDiscountProduct($code_discount);               
                }

                if (($total - $total_minus) > 0) {
                    $total_data[] = array( 
                            'code'       => $code_discount,
                            'title'      => $title_discount,
                            'text'       => $this->currency->format(($total_minus - $total) * $max_discount),
                            'discount'   => $max_discount,
                            'value'      => ($total_minus - $total) * $max_discount,
                            'sort_order' => $sort_order_discount
                    ); 

                    $total -= ($total - $total_minus) * $max_discount;
                }
            }
        }
}   
?>
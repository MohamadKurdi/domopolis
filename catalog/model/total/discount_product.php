<?php
class ModelTotalDiscountProduct extends Model {        
        public function getDiscountProduct($discount_id) {
            $total_minus = 0; 
            $product1 = $this->config->get($discount_id . '_product1'); 
            $product2 = $this->config->get($discount_id . '_product2'); 
            $product3 = $this->config->get($discount_id . '_product3');
            $discount_action = $discount_id . '_action';
            $query = $this->db->query("SELECT value FROM setting WHERE `group` = 'discount_product' AND `key` = '" . $discount_action . "'");
            
            if (isset($query->row['value']) && $query->row['value'] == 1) {
                    $array_not_apply_id = $this->config->get($discount_id); 
                    $apply = 0;
                    if(isset($array_not_apply_id)) $apply = 1;    
                    $products = $this->cart->getProducts();

                    foreach ($products as $product) {                        
                        if ($apply == 1 && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (in_array($product['product_id'], $array_not_apply_id))) {
                            $total_minus += $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
                        } else if ($product1 == 1) {
                            $product_special_query = $this->db->query("SELECT price FROM product_special WHERE product_id = '" . (int)$product['product_id']. "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                            if ($product_special_query->num_rows) $total_minus += $product['total']; 
                        } else if ($product2 == 1) {
                            $product_discount_query = $this->db->query("SELECT price FROM product_discount WHERE product_id = '" . (int)$product['product_id'] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
                            if ($product_discount_query->num_rows) $total_minus += $product['total'];  
                        } 
                        
                        if ($product3 == 1) 
                            foreach ($product['option'] as $option) 
                                $total_minus += $option['price'];   
                    }            
            }
            
            return $total_minus;
        }
        
        public function getTotal(&$total_data, &$total) {     
        }
}
?>
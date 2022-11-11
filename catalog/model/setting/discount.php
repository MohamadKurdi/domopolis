<?php
class ModelSettingDiscount extends Model {
        public function getSumTotal($discount_id) { 
            $sum_total = 0;
            if ($this->customer->isLogged()) {
                $discount_sum_status = $discount_id . '_order_status';
                $query = $this->db->query("SELECT sum(total) as total FROM `order` WHERE customer_id = '" . $this->customer->isLogged() . "' AND order_status_id = '" . $this->config->get($discount_sum_status) . "'");
                $sum_total = $query->row['total']; 
            } 
            return $sum_total;
        }
        
        public function getCustomerGroupId($customer_id) { 
                $query = $this->db->query("SELECT DISTINCT * FROM customer WHERE customer_id = '" . (int)$customer_id . "'");
                return $query->row['customer_group_id'];     
        }   
}
?>
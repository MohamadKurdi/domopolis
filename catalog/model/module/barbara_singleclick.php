<?php
class ModelModulebarbaraSingleclick extends Model {
    public function add($data) {
        $time = time();
        $sql = "INSERT INTO `" . DB_PREFIX . "barbara_singleclick` SET product_id = '" . (int)$data['product_id'] . "', product = '" . $this->db->escape($data['product_name']) . "', model = '" . $this->db->escape($data['product_model']) . "', email = '" . $this->db->escape($data['customer_email']) . "', price = '" . $this->db->escape($data['product_price']) . "', name = '" . $this->db->escape($data['customer_name']) . "', phone = '" . $this->db->escape($data['customer_phone']) . "', message = '" . $this->db->escape($data['customer_message']) . "', order_status_id = '" . $this->db->escape($data['order_status_id']) . "', date = NOW(), date_modified = NOW()";
        $query = $this->db->query($sql);
    }
}
?>
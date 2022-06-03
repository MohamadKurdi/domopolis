<?php

class ModelModuleAffiliate extends Model {

    public function createAffiliate() {
        $this->load->model('module/createtable');
        $this->model_module_createtable->EditTableVer1();
        $this->model_module_createtable->EditTableVer2();
        $this->model_module_createtable->EditTableVer3();
        $this->model_module_createtable->EditTableVer4();
		$this->model_module_createtable->EditTableVer5();
		$this->model_module_createtable->EditTableVer6();
		$this->model_module_createtable->EditTableVer7();
    }

    public function valuePlayment($affiliate_info) {
        $this->load->language('sale/affiliate');
        return $this->language->get('text_' . $affiliate_info['payment']);
    }

    public function deleteAffiliate($affiliate_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate` WHERE affiliate_id = '" . (int) $affiliate_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE affiliate_id = '" . (int) $affiliate_id . "'");
    }
	
	public function validate($order_id, $data, $commission, $total = 0) {
        $affiliatestatus = (int) $this->config->get('affiliate_order_status_id');
        if (
                ((int) $data['affiliate_id'] > 0)
                &
                ((int) $data['order_status_id'] == $affiliatestatus)
                &
                ($order_id != 0)
        ) {
            $query_affiliate = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . (int) $order_id . "'");
            $query_affiliate_bool = $query_affiliate->num_rows;
            if (!$query_affiliate_bool) {
				$this->load->language('sale/order');
                $this->load->model('sale/affiliate');
                $this->model_sale_affiliate->addTransaction((int) $data['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, (float) $commission, $order_id);
            }
        }
    }
	
    public function validateHistory($order_id, $order_info, $data) {
        $affiliatestatus = (int) $this->config->get('affiliate_order_status_id');
        if (
                ((int) $order_info['affiliate_id'] > 0)
                &
                ((int) $data['order_status_id'] == $affiliatestatus)
                &
                ($order_id != 0)
        ) {
            $query_affiliate = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_transaction` WHERE order_id = '" . (int) $order_id . "'");
            $query_affiliate_bool = $query_affiliate->num_rows;
            if (!$query_affiliate_bool) {
				$this->load->language('sale/order');
                $this->load->model('sale/affiliate');
                $this->model_sale_affiliate->addTransaction((int) $order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, (float) $order_info['commission'], $order_id);
            }
        }
    }

    public function addTrackingCoupon($data = array()) {
        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "affiliate` WHERE NOT coupon");			
        $data['logged'] = 0;
        $data['uses_total'] = 0;
        $data['uses_customer'] = 0;
        $data['status'] = 1;
        $data['shipping'] = 0;
        $data['date_start'] = date("Y-m-d H:i:s");
        $data['date_end'] = date('Y-m-d H:i:s', PHP_INT_MAX);
        $data['uses_total'] = 0;
        $data['uses_customer'] = 0;
        $data['status'] = 1;
        $this->load->model('sale/coupon');
        foreach ($query->rows as $row) {
            $data['name'] = $row['firstname'] . ' ' . $row['lastname'];
            $data['code'] = $row['code'];
            $this->model_sale_coupon->addCoupon($data);
            $idcoupon = $this->db->getLastId();
            $this->db->query("UPDATE `" . DB_PREFIX . "affiliate` SET coupon = '" . (int)$idcoupon . "' WHERE affiliate_id = '" . (int) $row['affiliate_id'] . "'");
            $this->db->query("UPDATE `" . DB_PREFIX . "coupon` SET affiliate_id = '" . (int)$row['affiliate_id'] . "' WHERE coupon_id = '" . (int) $idcoupon . "'");
        }				
    }
     public function dellTrackingCoupon($coupon) {
            $this->db->query("UPDATE `" . DB_PREFIX . "affiliate` set coupon = NULL WHERE coupon ='" . $coupon . "'");
     }
     public function updateMaxDate() {
            $this->db->query("UPDATE `" . DB_PREFIX . "coupon` set date_end = ".date('Y-m-d H:i:s', PHP_INT_MAX)." WHERE affiliate_id != 0");
     }
     public function isAffilateCoupon($coupon_id) {
            $query = $this->db->query("SELECT * from `" . DB_PREFIX . "coupon` WHERE (coupon_id ='" . $coupon_id . "' and affiliate_id = 0)");
            return $query->num_rows;
     }
}
?>
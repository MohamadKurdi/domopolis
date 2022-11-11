<?php

class ModelReportAffiliateStatistics extends Model {

    public function getAffiliatesName($data = array()) {

        $sql = "SELECT affiliate_id, CONCAT(firstname, ' ', lastname) AS affiliate, email FROM `" . DB_PREFIX . "affiliate` ";
        $implode = array();
        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(date_added) <= '" . $data['filter_date_end'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " order by 2 LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getAffiliatesCount($data = array(), $affiliate_id = 0) {
        $sql = "SELECT SUM(count_transitions) as transitions FROM `" . DB_PREFIX . "affiliate_statistics` ";
        $rezult_data = $this->end($data, $sql, $affiliate_id);
        return $rezult_data;
    }

    public function getAffiliatesOrders($data = array(), $affiliate_id = 0) {
        $sql = "SELECT count(total) as orders, SUM(total) as total FROM `" . DB_PREFIX . "order` ";
        $rezult_data = $this->end($data, $sql, $affiliate_id, 0);
        return $rezult_data;
    }

    public function getAffiliatesShopping($data = array(), $affiliate_id = 0) {
        $sql = "SELECT count(total) as shopping, SUM(total) as totals FROM `" . DB_PREFIX . "order` ";
        $rezult_data = $this->end($data, $sql, $affiliate_id, (int) $this->config->get('config_complete_status_id'));
        return $rezult_data;
    }

    public function getAffiliatesSum($data = array(), $affiliate_id = 0) {

        $sql = "SELECT SUM(case when amount > 0 then amount else 0 end) AS commission, SUM(case when amount < 0 then amount else 0 end) AS paid FROM `" . DB_PREFIX . "affiliate_transaction` ";
        $rezult_data = $this->end($data, $sql, $affiliate_id);
        return $rezult_data;
    }

    public function end($data, $sql, $affiliate_id = 0, $order_status_id = -1) {
        $implode = array();
        if ($order_status_id != -1) {
            if ($order_status_id == 0) {
                $implode[] = "order_status_id != '" . $order_status_id . "'";
            } else {
                $implode[] = "order_status_id = '" . $order_status_id . "'";
            }
        }

        if ($affiliate_id != 0) {
            $implode[] = " affiliate_id = " . $affiliate_id . " ";
        }
        else {$implode[] = " affiliate_id != 0 ";}

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(date_added) >= '" . $data['filter_date_start'] . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(date_added) <= '" . $data['filter_date_end'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }
        if (isset($data['limit'])) {

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT 0," . (int) $data['limit'];
        }
        $query = $this->db->query($sql);
        $result = $query->row;
        foreach ($result as $key => $value) {
            if (empty($result[$key])) {
                $result[$key] = 0.00;
            }
        }
        return $result; //$query->row;
    }

    public function getTotalCommission($data = array()) {
        $sql = "SELECT COUNT(DISTINCT affiliate_id) AS total FROM `" . DB_PREFIX . "affiliate`";

        $implode = array();
        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(date_added) <= '" . $data['filter_date_end'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }
        if (isset($data['limit'])) {

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT 0," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}

?>
<?php
class ModelModuleStatistics extends Model {
    public function validate() {
        $tracking = '';
        if (isset($this->request->get['tracking'])) {
            $tracking = $this->request->get['tracking'];
        } else {
            $tracking = '';
        }
        if ($tracking != '') {
            $query = $this->db->query("SELECT affiliate_id FROM `affiliate` WHERE code = '" . $tracking . "'");
            if ($query->num_rows) {
                //setcookie('tracking', $tracking);
                    $ip_name = $this->request->server["REMOTE_ADDR"] . ':' . $this->request->server["HTTP_USER_AGENT"];
                    $query_ip = $this->db->query("SELECT affiliate_id FROM `affiliate_statistics` WHERE affiliate_ip_name = '" . $ip_name . "' and affiliate_id = '".(int)$query->row['affiliate_id']. "' and date_added = cast(NOW() as date)");
                    if (!$query_ip->num_rows) {
                        return $query->row['affiliate_id'];
                    }
            }
        }
        return 0;
    }

    public function validateTransitions() {
        $affiliate_id = $this->validate();
        if ($affiliate_id != 0) {
            $this->addTransitions($affiliate_id);
        }
    }

    public function ISEXISTS($table_name) {
        $query = $this->db->query("select * from  information_schema.columns where TABLE_SCHEMA = '" . DB_DATABASE . "' and table_name = '" . DB_PREFIX . $table_name . "'");
        return $query->num_rows;
    }

    private function addTransitions($affiliate_id) {
        $ip_name = $this->request->server["REMOTE_ADDR"] . ':' . $this->request->server["HTTP_USER_AGENT"];
        $query = $this->db->query("SELECT count_transitions FROM `affiliate_statistics` WHERE affiliate_ip_name = '" . $ip_name . "' and affiliate_id = '" . (int) $affiliate_id . "' and date_added = cast(NOW() as date)");
        if (!$query->num_rows) {
            $this->db->query("INSERT INTO `affiliate_statistics` SET affiliate_id = '" . $affiliate_id . "', count_transitions = 1, date_added = cast(NOW() as date), affiliate_ip_name = '" . $ip_name . "'");
			
			$queryclear = $this->db->query("SELECT as1.affiliate_id AS affiliate_id, as1.date_added AS date_added, sum(as1.count_transitions) / (SELECT count(as3.date_added) FROM  `affiliate_statistics` AS as3 WHERE as3.date_added = as1.date_added) AS count_transitions FROM `affiliate_statistics` AS as1 INNER JOIN `affiliate_statistics` AS as2 ON (as1.affiliate_id = as2.affiliate_id AND as1.date_added = as2.date_added) WHERE as1.count_transitions = 1 AND as1.date_added != cast(now() AS DATE) AND as2.date_added != cast(now() AS DATE) GROUP BY as1.date_added");
			
			foreach ($queryclear->rows as $result) {
				if((int)$result['count_transitions'] != 1) {
					$this->db->query("INSERT INTO `affiliate_statistics` SET affiliate_id = '" . (int)$result['affiliate_id'] . "', count_transitions = '" . (int)$result['count_transitions'] . "', date_added = '" . $result['date_added'] . "'");
					
					$this->db->query("DELETE FROM `affiliate_statistics` WHERE affiliate_id = '" . (int)$result['affiliate_id'] . "' and count_transitions = 1 and date_added = '" . $result['date_added'] . "'");
				}
			}
        }
    }

    public function GetStatistics($affiliate_id, $data) {
       
            $sql = "SELECT SUM(count_transitions) as count_transitions FROM `affiliate_statistics` ";
            $rezult_data = $this->end($affiliate_id, $data, $sql);
            return $rezult_data;

        $rezult_data['count_transitions'] = -1;
        return $rezult_data;
    }

    public function GetStatisticsOrders($affiliate_id, $data) {
        $sql = "SELECT count(ot.value) as count_orders, SUM(ot.value) as sum_orders FROM `order` as o join `" . DB_PREFIX . 
			"order_total` as ot on (o.order_id = ot.order_id and ot.sort_order = 1) ";
        $rezult_data = $this->end($affiliate_id, $data, $sql, 0);
        return $rezult_data;
    }

    public function GetStatisticsShopping($affiliate_id, $data) {
        $sql = "SELECT count(ot.value) as count_shopping, SUM(ot.value) as sum_shopping FROM `" . DB_PREFIX . 
			"order` as o join `" . DB_PREFIX . 
			"order_total` as ot on (o.order_id = ot.order_id and ot.sort_order = 1) ";
        $rezult_data = $this->end($affiliate_id, $data, $sql, (int) $this->config->get('config_complete_status_id'));
        return $rezult_data;
    }

    public function GetStatisticsSum($affiliate_id, $data) {

        $sql = "SELECT SUM(case when amount > 0 then amount else 0 end) AS commission, SUM(case when amount < 0 then amount else 0 end) AS paid FROM `affiliate_transaction` ";
        $rezult_data = $this->end($affiliate_id, $data, $sql);
        return $rezult_data;
    }

    private function end($affiliate_id, $data, $sql, $order_status_id = -1) {

        $implode[] = "affiliate_id = '" . (int) $affiliate_id . "'";

        if ($order_status_id != -1) {
            if ($order_status_id == 0) {
                $implode[] = "order_status_id != '" . $order_status_id . "'";
            } else {
                $implode[] = "order_status_id = '" . $order_status_id . "'";
            }
        }

        if (!empty($data['filter_date_start'])) {
            $implode[] = "DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
        }

        if (!empty($data['filter_date_end'])) {
            $implode[] = "DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        if ($query->num_rows)
            return $query->row;
        $rezult_data['count_transitions'] = 0;
        return $rezult_data;
    }
}
?>
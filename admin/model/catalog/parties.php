<?php
class ModelCatalogParties extends Model
{
    
    public function getJustParties($data)
    {
        $sql = "SELECT DISTINCT TRIM(part_num) as part_num FROM order_product op";
        $sql .= " WHERE LENGTH(TRIM(part_num)) > 2";
        
        if (isset($data['filter_partie']) && $data['filter_partie']) {
            $sql .= " AND part_num LIKE('" . $this->db->escape($data['filter_partie']) . "%')";
        }
                
        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p.quantity',
            'opn.order_id',
            'p.status',
            'p.sort_order',
            'o.date_added',
            'op.part_num'
        );
        
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY op.part_num";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 2;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $query = $this->db->query($sql);

        return $query->rows;
    }
    
    public function getTotalAllParties($data)
    {
        $sql = "SELECT count(DISTINCT TRIM(part_num)) as total FROM order_product";
        $sql .= " WHERE LENGTH(TRIM(part_num)) > 2";
        
        if (isset($data['filter_partie']) && $data['filter_partie']) {
            $sql .= " AND part_num LIKE('" . $this->db->escape($data['filter_partie']) . "%')";
        }
        
        $query = $this->db->query($sql);
        
        return $query->row['total'];
    }
    
    public function getPartieOrders($part_num)
    {
        $sql = "SELECT DISTINCT order_id FROM order_product WHERE part_num = '" . $this->db->escape($part_num) . "'";
        
        $query = $this->db->query($sql);
        
        return $query->rows;
    }
    
    public function getOrderProducts($order_id)
    {
            $query = $this->db->query("SELECT op.*, p.image, p.product_id, p.ean FROM order_product op LEFT JOIN product p ON op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "' ORDER BY op.delivery_num ASC, op.name");

        return $query->rows;
    }
    
    
    public function getOrderLastCheque($order_id)
    {
        
            $query = $this->db->query("SELECT * FROM `order_invoice_history` WHERE `order_id` = '" . (int)$order_id."' AND auto_gen = 0 ORDER BY datetime DESC LIMIT 1");
            
        if ($query->row) {
            return $query->row;
        } else {
            $query = $this->db->query("SELECT * FROM `order_invoice_history` WHERE `order_id` = '" . (int)$order_id."' AND auto_gen = 1 ORDER BY datetime DESC LIMIT 1");
                
            return $query->row;
        }
    }
}

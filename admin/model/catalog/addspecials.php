<?php
class ModelCatalogAddSpecials extends Model
{
        
        
    public function AddAdditionalOffer($product_id, $product_additional_offer)
    {
        if (!empty($product_additional_offer)) {
            $this->db->query("INSERT INTO product_additional_offer SET 
                product_id = '" . (int)$product_id . "', 
                customer_group_id = '" . (int)$product_additional_offer['customer_group_id'] . "', 
                priority = '" . (int)$product_additional_offer['priority'] . "', 
                ao_group = '" . (int)$product_additional_offer['ao_group'] . "',
                quantity = '" . (int)$product_additional_offer['quantity'] . "', 
                ao_product_id = '" . (int)$product_additional_offer['ao_product_id'] . "', 
                price = '" . (float)$product_additional_offer['price'] . "', 
                percent = '" . (int)$product_additional_offer['percent'] . "', 
                date_start = '" . $this->db->escape($product_additional_offer['date_start']) . "', 
                date_end = '" . $this->db->escape($product_additional_offer['date_end']) . "', 
                image = '" . $this->db->escape($product_additional_offer['image']) . "', 
                description = '" . $this->db->escape($product_additional_offer['description']) . "'");
                
            $product_additional_offer_id = $this->db->getLastId();
                
            $this->db->query("DELETE FROM product_additional_offer_to_store WHERE product_additional_offer_id = '" . (int)$product_additional_offer_id . "'");
                
            foreach ($product_additional_offer['store_id'] as $product_additional_offer_store_id) {
                $this->db->query("INSERT INTO product_additional_offer_to_store SET product_additional_offer_id = '" . (int)$product_additional_offer_id . "', store_id = '" . $product_additional_offer_store_id . "'");
            }
        }
    }
        
    public function DelSpecial($product_id, $product_del)
    {
        if (!empty($product_del)) {
            if ($product_del['additional_offer']) {
                $query = $this->db->query("SELECT * FROM product_additional_offer WHERE product_id = '" . (int)$product_id . "'");
                    
                foreach ($query->rows as $row) {
                    $this->db->query("DELETE FROM product_additional_offer_to_store WHERE product_additional_offer_id = '" . (int)$row['product_additional_offer_id'] . "'");
                }
                    
                $this->db->query("DELETE FROM product_additional_offer WHERE product_id = '" . (int)$product_id . "'");
            }
        }
    }
        
        
    public function getStores($data = array())
    {
            
        $query = $this->db->query("SELECT * FROM store ORDER BY url");
        foreach ($query->rows as $row) {
            $store_data[$row['store_id']] = $row;
        }
            
        return $store_data;
    }
        
    public function getProducts($data = array())
    {
        $sql = "SELECT pd.name, p.model, p.sku, p.product_id, p.price, p.quantity FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
            
        if (!empty($data['filter_category_id'])) {
            $sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";
        }
            
        if (!empty($data['filter_store_id'])) {
            $sql .= " RIGHT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)";
        }
            
        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
            
        if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND manufacturer_id = " . (int)$data['filter_manufacturer_id'] . " ";
        }
            
        if (!empty($data['filter_ao_group'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM product_additional_offer WHERE ao_group = '" . $this->db->escape($data['filter_ao_group']) . "')";
        }
            
        if (!empty($data['filter_ao_product_id'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM product_additional_offer WHERE ao_product_id = '" . $this->db->escape($data['filter_ao_product_id']) . "')";
        }
            
        if (!empty($data['filter_name'])) {
            $sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }
            
        if (!empty($data['filter_model'])) {
            $sql .= " AND LCASE(p.model) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_model'])) . "%'";
        }
            
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }
            
        if (!empty($data['filter_category_id'])) {
            if ($data['filter_sub_category']) {
                $implode_data = array();
                    
                $implode_data[] = "category_id = '" . (int)$data['filter_category_id'] . "'";
                    
                $this->load->model('catalog/category');
                    
                $categories = $this->getCategories($data['filter_category_id']);
                    
                foreach ($categories as $category) {
                    $implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
                }
                    
                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        }
            
        if (!empty($data['filter_store_id'])) {
            $sql .= " AND p2s.store_id = " . (int)$data['filter_store_id'];
        }
            
        $sql .= " GROUP BY p.product_id";
            
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
                
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
                
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
            
        $query = $this->db->query($sql);
            
        $product_data = array();
            
        $stores = $this->getStores();
        $stores[0] = array('store_id' => 0, 'name' => 'def');
            
        if (!empty($data['filter_store_id'])) {
            $stores = array($stores[$data['filter_store_id']]);
        }
            
        foreach ($query->rows as $row) {
            $row['name'] = $row['name'] . ' (<b>' . $row['sku'] . '</b>)';
            $product_data[$row['product_id']] = $row;
        }
            
        return $product_data;
    }
        
    public function getTotalProducts($data = array())
    {
        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";
            
        if (!empty($data['filter_category_id'])) {
            $sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";
        }
        if (!empty($data['filter_store_id'])) {
            $sql .= " RIGHT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)";
        }
            
        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
            
        if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND manufacturer_id = " . (int)$data['filter_manufacturer_id'] . " ";
        }
            
        if (!empty($data['filter_ao_group'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM product_additional_offer WHERE ao_group = '" . $this->db->escape($data['filter_ao_group']) . "')";
        }
            
        if (!empty($data['filter_ao_product_id'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM product_additional_offer WHERE ao_product_id = '" . $this->db->escape($data['filter_ao_product_id']) . "')";
        }
            
        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $data['filter_name'] . "%'";
        }
            
        if (!empty($data['filter_model'])) {
            $sql .= " AND LCASE(p.model) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_model'])) . "%'";
        }
            
        if (!empty($data['filter_price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
        }
            
        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
        }
            
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }
            
        if (!empty($data['filter_category_id'])) {
            if ($data['filter_sub_category']) {
                $implode_data = array();
                    
                $implode_data[] = "category_id = '" . (int)$data['filter_category_id'] . "'";
                    
                $this->load->model('catalog/category');
                    
                $categories = $this->getCategories($data['filter_category_id']);
                    
                foreach ($categories as $category) {
                    $implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
                }
                    
                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
        }
            
        if (!empty($data['filter_store_id'])) {
            $sql .= " AND p2s.store_id = " . (int)$data['filter_store_id'];
        }
            
        $query = $this->db->query($sql);
        return $query->row['total'];
    }
}

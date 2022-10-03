<?php

class ModelModuleAlsopurchased extends Model
{

    public function getProductOrders($product_id){
        $sql = "SELECT order_id FROM order_product WHERE product_id = '" . (int)$product_id . "'"; 
        $query = $this->db->query($sql);

        $orders = [];
        foreach ($query->rows as $result){
            $orders[] = $result['order_id'];
        }     

        return $orders;   
    }

    public function getAlsoPurchasedSimple($product_id, $limit){        
        $this->load->model('catalog/product');

        
        $orders_id = implode(',', $this->getProductOrders($product_id));
        if (!$orders_id)
        {
            $orders_id = 0;
        }

        $sql = " SELECT DISTINCT(op.product_id), COUNT(*) AS total FROM order_product op LEFT JOIN `order` o ON (op.order_id = o.order_id) LEFT JOIN `product` p ON (op.product_id = p.product_id)";
        $sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1'";
        $sql .= " AND p.stock_status_id <> '" . (int)$this->config->get('config_not_in_stock_status_id') ."'";
        $sql .= " AND p.date_available <= NOW() AND op.order_id IN (" . $orders_id . ") AND op.product_id <> '" . (int)$product_id . "'";

        if ($this->config->get('config_no_zeroprice')){
            $sql .= " AND (p.price > 0 OR p.price_national > 0)";
        } else {
            $sql .= " AND (";
            $sql .= " p.price > 0 OR p.price_national > 0";
            $sql .= " OR (SELECT price FROM product_price_to_store pp2s WHERE pp2s.product_id = p.product_id AND price > 0 AND pp2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
            $sql .= " OR (SELECT price FROM product_price_national_to_store ppn2s WHERE ppn2s.product_id = p.product_id AND price > 0 AND ppn2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1) > 0";
            $sql .= ")";
        }

        if ($this->config->get('config_enable_amazon_specific_modes') && $this->config->get('config_rainforest_show_only_filled_products_in_catalog')){
            $sql .= " AND ((p.added_from_amazon = 0) OR (p.added_from_amazon = 1 AND p.filled_from_amazon = 1))";   
        }

        $sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        $sql .= " AND p.quantity > 0 ";
        $sql .= " GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;

        $query = $this->db->query($sql);

        $product_data = [];
        foreach ($query->rows as $result){
            $product_data[$result['product_id']] = $this->model_catalog_product->getProduct( $result['product_id'] );
        }

        return $product_data;
    }


    public function getAlsoPurchasedCategories($product_id, $limit){
        $this->load->model('catalog/category');

        $sql = "SELECT DISTINCT(category_id) FROM product_to_category WHERE product_id IN (SELECT product_id FROM product_also_bought WHERE product_id = '" . (int)$product_id . "') AND main_category = 1";
        $query = $this->db->query($sql);

        $category_data = [];
        foreach ($query->rows as $row){
            $category_data[$row['category_id']] = $this->model_catalog_category->getCategory($row['category_id']);
        }

        return $category_data;
    }
}
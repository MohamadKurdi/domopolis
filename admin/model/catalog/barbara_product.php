<?php
class ModelCatalogbarbaraProduct extends Model
{

    public function getProducts($product_ids)
    {
        $result = array();
        foreach ($product_ids as $product_id) {
            if ((int)$product_id != 0) {
                $result[] = $product_id;
            }
        }
        if (count($result)) {
            $query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id IN (" . implode(',', $result) . ") AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
            return $query->rows;
        } else {
            return array();
        }
    }

    public function getProductsByManufacturersId($manufacturer_ids)
    {
        if (!$manufacturer_ids) {
            return array();
        }
        $result = array();
        foreach ($manufacturer_ids as $manufacturer_id) {
            if ((int)$manufacturer_id != 0) {
                $result[] = $manufacturer_id;
            }
        }
        
        if (!$result) {
            return false;
        }

        $query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.manufacturer_id IN (" . implode(',', $result) . ") AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        return $query->rows;
    }

    public function updateProductsCategory($product_ids, $category_id)
    {
        $products = implode(',', $product_ids);
        $query = $this->db->query("DELETE FROM ".DB_PREFIX . "product_to_category WHERE category_id = " . (int)$category_id);
        foreach ($product_ids as $product_id) {
            $this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
        }
    }
}

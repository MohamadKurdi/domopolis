<?php
class ModelCatalogProductExt extends Model {
    protected $productCount = 0;

    public function getProducts($data = array()) {
        $cp_cols = $this->config->get('aqe_catalog_products');

        if (!is_array($cp_cols)) {
            $columns = array('image', 'name', 'model', 'price', 'quantity', 'status');
        } else {
            $columns = array();
            foreach($cp_cols as $column => $attr) {
                if ($attr['display'])
                    $columns[] = $column;
            }
        }

        $sql = "SELECT p.*, pd.*";

        if (in_array("seo", $columns)) {
            $sql .= ", (SELECT keyword FROM url_alias WHERE query = CONCAT('product_id=', p.product_id)) AS seo";
        }

        if (in_array("product_offers_count", $columns)) {
            $sql .= ", (SELECT COUNT(o.order_id) FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.order_status_id > 0 AND op.product_id = p.product_id) AS product_offers_count";
        }

        if (in_array("product_warehouse_count", $columns)) {
            $sql .= ", (`" . $this->config->get('config_warehouse_identifier') . "` + `" . $this->config->get('config_warehouse_identifier') . "_onway`) as product_warehouse_count";
        }

        if (in_array("manufacturer", $columns)) {
            $sql .= ", m.name AS manufacturer";
        }

        if (in_array("tax_class", $columns)) {
            $sql .= ", tc.title AS tax_class";
        }

        if (in_array("stock_status", $columns)) {
            $sql .= ", ss.name AS stock_status";
        }

        if (in_array("length_class", $columns)) {
            $sql .= ", lcd.title AS length_class";
        }

        if (in_array("weight_class", $columns)) {
            $sql .= ", wcd.title AS weight_class";
        }

        $sql .= " FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";

        if (!empty($data['filter_price_special']) && in_array($data['filter_price_special'], array("active", "expired", "future"))) {
            $sql .= " LEFT JOIN product_special ps ON (ps.product_id = p.product_id)";
        }

        if (in_array("manufacturer", $columns)) {
            $sql .= " LEFT JOIN manufacturer m ON (m.manufacturer_id = p.manufacturer_id)";
        }

        if (in_array("filter", $columns)) {
            $sql .= " LEFT JOIN product_filter p2f ON (p.product_id = p2f.product_id) LEFT JOIN filter_description fd ON (fd.filter_id = p2f.filter_id AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
        }

        if (in_array("download", $columns)) {
            $sql .= " LEFT JOIN product_to_download p2d ON (p.product_id = p2d.product_id) LEFT JOIN download_description dd ON (dd.download_id = p2d.download_id AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
        }

        if (in_array("tax_class", $columns)) {
            $sql .= " LEFT JOIN tax_class tc ON (tc.tax_class_id = p.tax_class_id)";
        }

        if (in_array("stock_status", $columns)) {
            $sql .= " LEFT JOIN stock_status ss ON (ss.stock_status_id = p.stock_status_id)";
        }

        if (in_array("length_class", $columns)) {
            $sql .= " LEFT JOIN length_class lc ON (lc.length_class_id = p.length_class_id) LEFT JOIN length_class_description lcd ON (lcd.length_class_id = lc.length_class_id)";
        }

        if (in_array("weight_class", $columns)) {
            $sql .= " LEFT JOIN weight_class wc ON (wc.weight_class_id = p.weight_class_id) LEFT JOIN weight_class_description wcd ON (wcd.weight_class_id = wc.weight_class_id)";
        }

        if (!empty($data['filter_category'])) {
            $sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        if (isset($data['filter_store'])) {
            $sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)";
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";        

        if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']) {
            if (empty($data['filter_id']) && empty($data['filter_asin'])){
                $sql .= " AND (p.main_variant_id = '0' OR ISNULL(p.main_variant_id))";
            }
        }

        if (in_array("length_class", $columns)) {
            $sql .= " AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        }

        if (in_array("weight_class", $columns)) {
            $sql .= " AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        }

        $int_filters = array(
            'tax_class'             => 'p.tax_class_id',
            'length_class'          => 'p.length_class_id',
            'weight_class'          => 'p.weight_class_id',
            'manufacturer'          => 'p.manufacturer_id',
            'stock_status'          => 'p.stock_status_id',
            'subtract'              => 'p.subtract',
            'id'                    => 'p.product_id',
            'main_variant_id'       => 'p.main_variant_id',
            'added_from_supplier'   => 'p.added_from_supplier',
            'status'                => 'p.status',
            'fill_from_amazon'      => 'p.fill_from_amazon',
            'filled_from_amazon'    => 'p.filled_from_amazon',
            'amzn_no_offers'        => 'p.amzn_no_offers',
            'requires_shipping'     => 'p.shipping',
            );

        foreach ($int_filters as $key => $value) {
            if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                $sql .= " AND $value = '" . (int)$data["filter_$key"] . "'";
            }
        }

        $date_filters = array(
            'date_available'    => 'p.date_available',
            'date_modified'     => 'p.date_modified',
            'date_added'        => 'p.date_added',
            );

        foreach ($date_filters as $key => $value) {
            if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                if ($this->config->get('aqe_interval_filter')) {
                    $sql .= " AND " . $this->filterInterval($this->db->escape($data["filter_$key"]), $value, true);
                } else {
                    $sql .= " AND DATE($value) = DATE('" . $this->db->escape($data["filter_$key"]) . "')";
                }
            }
        }

        $float_interval_filters = array(
            'length'            => 'p.length',
            'width'             => 'p.width',
            'height'            => 'p.height',
            'weight'            => 'p.weight',
            'price'             => 'p.price',
            'price_delayed'     => 'p.price_delayed',
            'cost'              => 'p.cost',
            'amazon_best_price' => 'p.amazon_best_price',
            'costprice'         => 'p.costprice',
            'amzn_offers_count' => 'p.amzn_offers_count'
            );

        foreach ($float_interval_filters as $key => $value) {
            if ($key == "price" && !empty($data['filter_price_special']) && in_array($data['filter_price_special'], array("active", "expired", "future"))) {
                if ($data['filter_price_special'] == "active") {
                    $sql .= " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";
                } elseif ($data['filter_price_special'] == "expired") {
                    $sql .= " AND (ps.date_end != '0000-00-00' AND ps.date_end < NOW())";
                } elseif ($data['filter_price_special'] == "future") {
                    $sql .= " AND (ps.date_start > NOW() AND ps.date_start != '0000-00-00')";
                }
            } else {
                if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                    if ($this->config->get('aqe_interval_filter')) {
                        $sql .= " AND " . $this->filterInterval($data["filter_$key"], $value);
                    } else {
                        $sql .= " AND $value = '" . $this->db->escape($data["filter_$key"]) . "%'";
                    }
                }
            }
        }

        $int_interval_filters = array(
            'quantity'      => 'p.quantity',
            'minimum'       => 'p.minimum',
            'points'        => 'p.points',
            'sort_order'    => 'p.sort_order',
            );

        foreach ($int_interval_filters as $key => $value) {
            if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                if ($this->config->get('aqe_interval_filter')) {
                    $sql .= " AND " . $this->filterInterval($data["filter_$key"], $value);
                } else {
                    $sql .= " AND $value = '" . (int)$data["filter_$key"] . "'";
                }
            }
        }

        if (isset($data["filter_product_offers_count"]) && !is_null($data["filter_product_offers_count"])) {
            if ($data["filter_product_offers_count"] == 1){
                $sql .= " AND (SELECT COUNT(o.order_id) FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.order_status_id > 0 AND op.product_id = p.product_id) > 0";
            } else {
                $sql .= " AND (SELECT COUNT(o.order_id) FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.order_status_id > 0 AND op.product_id = p.product_id) = 0";
            }          
        }

        if (isset($data["filter_product_warehouse_count"]) && !is_null($data["filter_product_warehouse_count"])) {
            if ($data["filter_product_warehouse_count"] == 1){
                $sql .= " AND (`" . $this->config->get('config_warehouse_identifier') . "` + `" . $this->config->get('config_warehouse_identifier') . "_onway`) > 0";
            } else {
                $sql .= " AND (`" . $this->config->get('config_warehouse_identifier') . "` + `" . $this->config->get('config_warehouse_identifier') . "_onway`) = 0";
            }          
        }

        $anywhere_filters = array(
            'sku'       => 'p.sku',
            'asin'      => 'p.asin',
            'upc'       => 'p.upc',
            'ean'       => 'p.ean',
            'jan'       => 'p.jan',
            'isbn'      => 'p.isbn',
            'mpn'       => 'p.mpn',
            'location'  => 'p.location',
            'name'      => 'pd.name',
            'model'     => 'p.model',
            'download'  => 'dd.name',
            'filter'    => 'fd.name'
            );

        foreach ($anywhere_filters as $key => $value) {
            if (!empty($data["filter_$key"])) {
                if ($this->config->get('aqe_match_anywhere')) {
                    $sql .= " AND LCASE($value) LIKE '%" . $this->db->escape(utf8_strtolower($data["filter_$key"])) . "%'";
                } else {
                    $sql .= " AND LCASE($value) LIKE '" . $this->db->escape(utf8_strtolower($data["filter_$key"])) . "%'";
                }
            }
        }

        if (!empty($data['filter_amazon_offers_type'])) {
            $sql .= " AND p.amazon_offers_type = '" . $this->db->escape($data['filter_amazon_offers_type']) . "'";
        }

        if (!empty($data['filter_amazon_seller_quality'])) {
            $sql .= " AND p.amazon_seller_quality = '" . $this->db->escape($data['filter_amazon_seller_quality']) . "'";
        }

        if (!empty($data['filter_seo'])) {
            if ($this->config->get('aqe_match_anywhere')) {
                $sql .= " AND (SELECT LCASE(keyword) FROM url_alias WHERE query = CONCAT('product_id=', p.product_id)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_seo'])) . "%'";
            } else {
                $sql .= " AND (SELECT LCASE(keyword) FROM url_alias WHERE query = CONCAT('product_id=', p.product_id)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_seo'])) . "%'";
            }
        }

        if (!empty($data['filter_tag'])) {
            $sql .= " AND LCASE(pd.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
        }

        if (isset($data['filter_store'])) {
            if ($data['filter_store'] == '*')
                $sql .= " AND p2s.store_id IS NULL";
            else
                $sql .= " AND p2s.store_id = '" . (int)$data['filter_store'] . "'";
        }

        if (!empty($data['filter_category'])) {
            if (!empty($data['filter_sub_category'])) {
                $implode_data = array();

                if ($data['filter_category'] == '*')
                    $implode_data[] = "p2c.category_id IS NULL";
                else
                    $implode_data[] = "p2c.category_id IN (SELECT category_id FROM category_path WHERE path_id = '" . (int)$data['filter_category'] . "')";
                

                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                if ($data['filter_category'] == '*')
                    $sql .= " AND p2c.category_id IS NULL";
                else
                    $sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
            }
        }

        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'p.product_id',
            'p.main_variant_id',
            'tc.title',
            'p.minimum',
            'p.subtract',
            'ss.name',
            'p.shipping',
            'p.date_available',
            'p.date_modified',
            'lc.title',
            'wc.title',
            'p.points',
            'p.length',
            'p.width',
            'p.height',
            'p.weight',
            'p.sku',
            'p.upc',
            'p.ean',
            'p.jan',
            'p.isbn',
            'p.mpn',
            'p.location',
            'm.name',
            'seo',
            'pd.name',
            'p.model',
            'p.price',
            'p.price_delayed',
            'p.cost',
            'p.amazon_best_price',
            'p.costprice',
            'p.amzn_offers_count',
            'p.amzn_no_offers',
            'p.quantity',
            'p.status',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY p.product_id";
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
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProducts($data = array()) {
        $cp_cols = $this->config->get('aqe_catalog_products');

        if (!is_array($cp_cols)) {
            $columns = array('image', 'name', 'model', 'price', 'quantity', 'status');
        } else {
            $columns = array();
            foreach($cp_cols as $column => $attr) {
                if ($attr['display'])
                    $columns[] = $column;
            }
        }

        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total ";

        $sql .= " FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id)";

        if (!empty($data['filter_price_special']) && in_array($data['filter_price_special'], array("active", "expired", "future"))) {
            $sql .= " LEFT JOIN product_special ps ON (ps.product_id = p.product_id)";
        }

        if (in_array("manufacturer", $columns) && !empty($data['filter_manufacturer'])) {
            $sql .= " LEFT JOIN manufacturer m ON (m.manufacturer_id = p.manufacturer_id)";
        }

        if (in_array("filter", $columns)) {
            $sql .= " LEFT JOIN product_filter p2f ON (p.product_id = p2f.product_id) LEFT JOIN filter_description fd ON (fd.filter_id = p2f.filter_id AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
        }

        if (in_array("download", $columns)) {
            $sql .= " LEFT JOIN product_to_download p2d ON (p.product_id = p2d.product_id) LEFT JOIN download_description dd ON (dd.download_id = p2d.download_id AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "')";
        }

        if (in_array("tax_class", $columns)) {
            $sql .= " LEFT JOIN tax_class tc ON (tc.tax_class_id = p.tax_class_id)";
        }

        if (in_array("stock_status", $columns) && !empty($data['filter_stock_status'])) {
            $sql .= " LEFT JOIN stock_status ss ON (ss.stock_status_id = p.stock_status_id)";
        }

        if (in_array("length_class", $columns)) {
            $sql .= " LEFT JOIN length_class lc ON (lc.length_class_id = p.length_class_id) LEFT JOIN length_class_description lcd ON (lcd.length_class_id = lc.length_class_id)";
        }

        if (in_array("weight_class", $columns)) {
            $sql .= " LEFT JOIN weight_class wc ON (wc.weight_class_id = p.weight_class_id) LEFT JOIN weight_class_description wcd ON (wcd.weight_class_id = wc.weight_class_id)";
        }

        if (!empty($data['filter_category'])) {
            $sql .= " LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id)";
        }

        if (isset($data['filter_store'])) {
            $sql .= " LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id)";
        }

        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";        

        if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']) {
            if (empty($data['filter_id']) && empty($data['filter_asin'])){
                $sql .= " AND (p.main_variant_id = '0' OR ISNULL(p.main_variant_id))";
            }
        }

        if (in_array("length_class", $columns)) {
            $sql .= " AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        }

        if (in_array("weight_class", $columns)) {
            $sql .= " AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        }

        $int_filters = array(
            'tax_class'             => 'p.tax_class_id',
            'length_class'          => 'p.length_class_id',
            'weight_class'          => 'p.weight_class_id',
            'manufacturer'          => 'p.manufacturer_id',
            'stock_status'          => 'p.stock_status_id',
            'subtract'              => 'p.subtract',
            'id'                    => 'p.product_id',
            'main_variant_id'       => 'p.main_variant_id',
            'added_from_supplier'   => 'p.added_from_supplier',
            'status'                => 'p.status',
            'fill_from_amazon'      => 'p.fill_from_amazon',
            'filled_from_amazon'    => 'p.filled_from_amazon',
            'amzn_no_offers'        => 'p.amzn_no_offers',
            'requires_shipping'     => 'p.shipping',
            );

        foreach ($int_filters as $key => $value) {
            if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                $sql .= " AND $value = '" . (int)$data["filter_$key"] . "'";
            }
        }

        $date_filters = array(
            'date_available'    => 'p.date_available',
            'date_modified'     => 'p.date_modified',
            'date_added'        => 'p.date_added',
            );

        foreach ($date_filters as $key => $value) {
            if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                if ($this->config->get('aqe_interval_filter')) {
                    $sql .= " AND " . $this->filterInterval($this->db->escape($data["filter_$key"]), $value, true);
                } else {
                    $sql .= " AND DATE($value) = DATE('" . $this->db->escape($data["filter_$key"]) . "')";
                }
            }
        }

        $float_interval_filters = array(
            'length'            => 'p.length',
            'width'             => 'p.width',
            'height'            => 'p.height',
            'weight'            => 'p.weight',
            'price'             => 'p.price',
            'price_delayed'     => 'p.price_delayed',
            'cost'              => 'p.cost',
            'amazon_best_price' => 'p.amazon_best_price',
            'costprice'         => 'p.costprice',
            'amzn_offers_count' => 'p.amzn_offers_count'
            );

        foreach ($float_interval_filters as $key => $value) {
            if ($key == "price" && !empty($data['filter_price_special']) && in_array($data['filter_price_special'], array("active", "expired", "future"))) {
                if ($data['filter_price_special'] == "active") {
                    $sql .= " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))";
                } elseif ($data['filter_price_special'] == "expired") {
                    $sql .= " AND (ps.date_end != '0000-00-00' AND ps.date_end < NOW())";
                } elseif ($data['filter_price_special'] == "future") {
                    $sql .= " AND (ps.date_start > NOW() AND ps.date_start != '0000-00-00')";
                }
            } else {
                if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                    if ($this->config->get('aqe_interval_filter')) {
                        $sql .= " AND " . $this->filterInterval($data["filter_$key"], $value);
                    } else {
                        $sql .= " AND $value = '" . $this->db->escape($data["filter_$key"]) . "%'";
                    }
                }
            }
        }

        $int_interval_filters = array(
            'quantity'      => 'p.quantity',
            'minimum'       => 'p.minimum',
            'points'        => 'p.points',
            'sort_order'    => 'p.sort_order',
            );

        foreach ($int_interval_filters as $key => $value) {
            if (isset($data["filter_$key"]) && !is_null($data["filter_$key"])) {
                if ($this->config->get('aqe_interval_filter')) {
                    $sql .= " AND " . $this->filterInterval($data["filter_$key"], $value);
                } else {
                    $sql .= " AND $value = '" . (int)$data["filter_$key"] . "'";
                }
            }
        }

        if (isset($data["filter_product_offers_count"]) && !is_null($data["filter_product_offers_count"])) {
            if ($data["filter_product_offers_count"] == 1){
                $sql .= " AND (SELECT COUNT(o.order_id) FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.order_status_id > 0 AND op.product_id = p.product_id) > 0";
            } else {
                $sql .= " AND (SELECT COUNT(o.order_id) FROM order_product op LEFT JOIN `order` o ON o.order_id = op.order_id WHERE o.order_status_id > 0 AND op.product_id = p.product_id) = 0";
            }          
        }

        if (isset($data["filter_product_warehouse_count"]) && !is_null($data["filter_product_warehouse_count"])) {
            if ($data["filter_product_warehouse_count"] == 1){
                $sql .= " AND (`" . $this->config->get('config_warehouse_identifier') . "` + `" . $this->config->get('config_warehouse_identifier') . "_onway`) > 0";
            } else {
                $sql .= " AND (`" . $this->config->get('config_warehouse_identifier') . "` + `" . $this->config->get('config_warehouse_identifier') . "_onway`) = 0";
            }          
        }

        $anywhere_filters = array(
            'sku'       => 'p.sku',
            'asin'      => 'p.asin',
            'upc'       => 'p.upc',
            'ean'       => 'p.ean',
            'jan'       => 'p.jan',
            'isbn'      => 'p.isbn',
            'mpn'       => 'p.mpn',
            'location'  => 'p.location',
            'name'      => 'pd.name',
            'model'     => 'p.model',
            'download'  => 'dd.name',
            'filter'    => 'fd.name'
            );

        foreach ($anywhere_filters as $key => $value) {
            if (!empty($data["filter_$key"])) {
                if ($this->config->get('aqe_match_anywhere')) {
                    $sql .= " AND LCASE($value) LIKE '%" . $this->db->escape(utf8_strtolower($data["filter_$key"])) . "%'";
                } else {
                    $sql .= " AND LCASE($value) LIKE '" . $this->db->escape(utf8_strtolower($data["filter_$key"])) . "%'";
                }
            }
        }

        if (!empty($data['filter_amazon_offers_type'])) {
            $sql .= " AND p.amazon_offers_type = '" . $this->db->escape($data['filter_amazon_offers_type']) . "'";
        }

        if (!empty($data['filter_amazon_seller_quality'])) {
            $sql .= " AND p.amazon_seller_quality = '" . $this->db->escape($data['filter_amazon_seller_quality']) . "'";
        }

        if (!empty($data['filter_seo'])) {
            if ($this->config->get('aqe_match_anywhere')) {
                $sql .= " AND (SELECT LCASE(keyword) FROM url_alias WHERE query = CONCAT('product_id=', p.product_id)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_seo'])) . "%'";
            } else {
                $sql .= " AND (SELECT LCASE(keyword) FROM url_alias WHERE query = CONCAT('product_id=', p.product_id)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_seo'])) . "%'";
            }
        }

        if (!empty($data['filter_tag'])) {
            $sql .= " AND LCASE(pd.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
        }

        if (isset($data['filter_store'])) {
            if ($data['filter_store'] == '*')
                $sql .= " AND p2s.store_id IS NULL";
            else
                $sql .= " AND p2s.store_id = '" . (int)$data['filter_store'] . "'";
        }

        if (!empty($data['filter_category'])) {
            if (!empty($data['filter_sub_category'])) {
                $implode_data = array();

                if ($data['filter_category'] == '*')
                    $implode_data[] = "p2c.category_id IS NULL";
                else
                    $implode_data[] = "p2c.category_id IN (SELECT category_id FROM category_path WHERE path_id = '" . (int)$data['filter_category'] . "')";
                

                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                if ($data['filter_category'] == '*')
                    $sql .= " AND p2c.category_id IS NULL";
                else
                    $sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
            }
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalProducts2() {
        return $this->productCount;
    }

    public function quickEditProduct($product_id, $column, $value, $lang_id=null, $data=null) {
        $this->load->model('catalog/product');

        $editable = array('manufacturer', 'image', 'name', 'tag', 'model', 'sku', 'asin', 'upc', 'ean', 'jan', 'mpn', 'isbn', 'location', 'quantity', 'price', 'price_delayed', 'cost', 'weight', 'status', 'fill_from_amazon', 'sort_order', 'tax_class', 'minimum', 'subtract', 'stock_status', 'shipping', 'date_available', 'length', 'width', 'height', 'length_class', 'weight_class', 'points');
        $result = false;
        if (in_array($column, $editable)) {


            if ($column == 'status'){
                if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){                    
                    $this->db->query("UPDATE product SET status     = '" . (int)$value . "' WHERE main_variant_id = '" . (int)$product_id . "'");                                 
                }
            }

            if ($column == 'minimum'){
                if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){                    
                    $this->db->query("UPDATE product SET minimum     = '" . (int)$value . "' WHERE main_variant_id = '" . (int)$product_id . "'");                                 
                }
            }

            if ($column == 'shipping'){
                if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){                    
                    $this->db->query("UPDATE product SET shipping     = '" . (int)$value . "' WHERE main_variant_id = '" . (int)$product_id . "'");                                 
                }
            }

            if (in_array($column, array('image', 'model', 'sku', 'upc', 'asin', 'ean', 'jan', 'mpn', 'isbn', 'location', 'date_available')))
                $result = $this->db->query("UPDATE product SET " . $column . " = '" . $this->db->escape($value) . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
            else if (in_array($column, array('quantity', 'sort_order', 'status', 'minimum', 'subtract', 'shipping', 'points')))
                $result = $this->db->query("UPDATE product SET " . $column . " = '" . (int)$value . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
            else if (in_array($column, array('manufacturer', 'tax_class', 'stock_status', 'length_class', 'weight_class')))
                $result = $this->db->query("UPDATE product SET " . $column . "_id = '" . (int)$value . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
            else if (in_array($column, array('name', 'tag')))
                $result = $this->db->query("UPDATE product_description SET " . $column . " = '" . $this->db->escape($value) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$lang_id . "'");

                if ($column == 'name'){
                    $this->model_catalog_product->syncProductNamesInOrders($product_id, $lang_id, $value);
                }

            else
                $result = $this->db->query("UPDATE product SET " . $column . " = '" . (float)$value . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
        } else if ($column == 'seo') {
            $this->db->query("DELETE FROM url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
            if (!empty($value))
                $result = $this->db->query("INSERT INTO url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($value) . "'");
            else
                $result = 1;
        } else if ($column == 'category') {
            $this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['p_c'])) {
                if (!is_array($data['p_c'])){
                   $data['p_c'] = [$data['p_c']];
                }

                if (count($data['p_c']) == 1){
                    $this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$data['p_c'][0] . "', main_category = 1");
                } else {
                    foreach ($data['p_c'] as $category_id) {
                        $this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
                    }                
                }
            }
            $result = 1;
        } else if ($column == 'store') {
            $this->db->query("DELETE FROM product_to_store WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['p_s'])) {
                foreach ((array)$data['p_s'] as $store_id) {
                    $this->db->query("INSERT INTO product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
                }
            }
            $result = 1;
        } else if ($column == 'filter') {
            $this->db->query("DELETE FROM product_filter WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['p_f'])) {
                foreach ((array)$data['p_f'] as $filter_id) {
                    $this->db->query("INSERT INTO product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
                }
            }
            $result = 1;
        } else if ($column == 'download') {
            $this->db->query("DELETE FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['p_d'])) {
                foreach ((array)$data['p_d'] as $download_id) {
                    $this->db->query("INSERT INTO product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
                }
            }
            $result = 1;
        } else if ($column == 'attributes') {
            $this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "'");

            if (!empty($data['product_attribute'])) {
                foreach ((array)$data['product_attribute'] as $product_attribute) {
                   if ($product_attribute['attribute_id']) {
                        $this->db->query("DELETE FROM product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
                        
                        $copy_product_attribute_description = [];
                        foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {               
                            $this->db->query("INSERT INTO product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");

                            if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode']){
                                if ($language_id == $this->config->get('config_rainforest_source_language_id')){
                                    $rainforest_source_text = $product_attribute_description['text'];
                                } else {
                                    $copy_product_attribute_description[$language_id] = [
                                        'text' => $product_attribute_description['text']
                                    ];
                                }
                            }
                        }

                        if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_translate_edition_mode'] && !empty($rainforest_source_text)){
                            foreach ($copy_product_attribute_description as $language_id => $copy_attribute_description) {  
                                $sql = "UPDATE product_attribute SET text = '" . $this->db->escape($copy_attribute_description['text']) . "' 
                                    WHERE product_id IN 
                                    (SELECT p2.product_id FROM product_attribute p2 WHERE p2.attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND p2.language_id = '" . $this->config->get('config_rainforest_source_language_id') . "' AND p2.text LIKE ('" . $this->db->escape($rainforest_source_text) . "')) 
                                    AND attribute_id = '" . (int)(int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'";

                                $this->db->query($sql);
                            }
                        }

                    }   
                }
            }
            $result = 1;
        } else if ($column == 'discounts') {
            $this->db->query("DELETE FROM product_discount WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['product_discount'])) {
                foreach ((array)$data['product_discount'] as $product_discount) {
                    $this->db->query("INSERT INTO product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
                }
            }
            $result = 1;
        } else if ($column == 'images') {
            $this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['product_image'])) {
                foreach ((array)$data['product_image'] as $product_image) {
                    $this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
                }
            }
            $result = 1;
        } else if ($column == 'options') {
            $this->db->query("DELETE FROM product_option WHERE product_id = '" . (int)$product_id . "'");
            $this->db->query("DELETE FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['product_option'])) {
                foreach ((array)$data['product_option'] as $product_option) {
                    if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                        $this->db->query("INSERT INTO product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

                        $product_option_id = $this->db->getLastId();

                        if (isset($product_option['product_option_value'])) {
                            foreach ((array)$product_option['product_option_value'] as $product_option_value) {
                                $this->db->query("INSERT INTO product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
                            }
                        }
                    } else {
                        $this->db->query("INSERT INTO product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
                    }
                }
            }
            $result = 1;
        } else if ($column == 'profiles') {
            $this->db->query("DELETE FROM product_profile WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['product_profiles'])) {
                foreach ((array)$data['product_profiles'] as $product_profile) {
                    $this->db->query("INSERT INTO product_profile SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_profile['customer_group_id'] . "', profile_id = '" . (int)$product_profile['profile_id'] . "'");
                }
            }
            $result = 1;
        } else if ($column == 'specials') {
            $this->db->query("DELETE FROM product_special WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['product_special'])) {
                foreach ((array)$data['product_special'] as $product_special) {
                    $this->db->query("INSERT INTO product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
                }
            }
            $result = 1;
        } else if ($column == 'filters') {
            $this->db->query("DELETE FROM product_filter WHERE product_id = '" . (int)$product_id . "'");

            if (isset($data['product_filters'])) {
                foreach ((array)$data['product_filters'] as $filter_id) {
                    $this->db->query("INSERT INTO product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
                }
            }
            $result = 1;
        } else if ($column == 'related') {
            $this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "'");
            $this->db->query("DELETE FROM product_related WHERE related_id = '" . (int)$product_id . "'");

            if (isset($data['product_related'])) {
                foreach ((array)$data['product_related'] as $related_id) {
                    $this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
                    $this->db->query("INSERT INTO product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
                    $this->db->query("DELETE FROM product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
                    $this->db->query("INSERT INTO product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
                }
            }
            $result = 1;
        } else if ($column == 'descriptions') {
            foreach ((array)$data['product_description'] as $language_id => $value) {
                $this->db->query("UPDATE product_description SET description = '" . $this->db->escape($value['description']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");

                if ($this->config->get('config_enable_amazon_specific_modes') && $this->session->data['config_rainforest_variant_edition_mode']){
                    $variants = $this->model_catalog_product->getProductVariantsIds($product_id);
                    if ($variants){
                    $this->db->query("UPDATE product_description SET
                        description = '" . $this->db->escape($value['description']) . "'                      
                        WHERE product_id IN (" . implode(',', $variants) . ") AND language_id = '" . (int)$language_id . "'");
                    }
                }

            }
            $result = 1;
        }        

        $this->load->model('kp/content');
        $this->model_kp_content->addContent(['action' => 'edit', 'entity_type' => 'product', 'entity_id' => $product_id]);

        return $result;
    }

    public function urlAliasExists($product_id, $keyword) {
        $query = $this->db->query("SELECT 1 FROM url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND query <> 'product_id=" . (int)$product_id . "'");

        if ($query->row) {
            return true;
        } else {
            return false;
        }
    }

    public function filterInterval($filter, $field, $date=false) {
        if ($date) {
            if (preg_match('/^(!=|<>)\s*(\d{2,4}-\d{1,2}-\d{1,2})$/', html_entity_decode(trim($filter)), $matches) && count($matches) == 3) {
                return "DATE($field) <> DATE('" . $matches[2] . "')";
            } else if (preg_match('/^(\d{2,4}-\d{1,2}-\d{1,2})\s*(<|<=)\s*(\d{2,4}-\d{1,2}-\d{1,2})$/', html_entity_decode(trim($filter)), $matches) && count($matches) == 4 && strtotime($matches[1]) <= strtotime($matches[3])) {
                return "DATE('" . $matches[1] . "') ${matches[2]} DATE($field) AND DATE($field) ${matches[2]} DATE('" . $matches[3] . "')";
            } else if (preg_match('/^(\d{2,4}-\d{1,2}-\d{1,2})\s*(>|>=)\s*(\d{2,4}-\d{1,2}-\d{1,2})$/', html_entity_decode(trim($filter)), $matches) && count($matches) == 4 && strtotime($matches[1]) >= strtotime($matches[3])) {
                return "DATE('" . $matches[1] . "') ${matches[2]} DATE($field) AND DATE($field) ${matches[2]} DATE('" . $matches[3] . "')";
            } else if (preg_match('/^(<|<=|>|>=)\s*(\d{2,4}-\d{1,2}-\d{1,2})$/', html_entity_decode(trim($filter)), $matches) && count($matches) == 3) {
                return "DATE($field) ${matches[1]} DATE('" . $matches[2] . "')";
            } else if (preg_match('/^(\d{2,4}-\d{1,2}-\d{1,2})\s*(>|>=|<|<=)$/', html_entity_decode(trim($filter)), $matches) && count($matches) == 3) {
                return "DATE('" . $matches[1] . "') ${matches[2]} DATE($field)";
            } else {
                return "DATE(${field}) = DATE('${filter}')";
            }
        } else {
            if (preg_match('/^(!=|<>)\s*(-?\d+\.?\d*)$/', html_entity_decode(trim(str_replace(",", ".", $filter))), $matches) && count($matches) == 3) {
                return "$field <> '" . (float)$matches[2] . "'";
            } else if (preg_match('/^(-?\d+\.?\d*)\s*(<|<=)\s*(-?\d+\.?\d*)$/', html_entity_decode(trim(str_replace(",", ".", $filter))), $matches) && count($matches) == 4 && (float)$matches[1] <= (float)$matches[3]) {
                return "'" . (float)$matches[1] . "' ${matches[2]} $field AND $field ${matches[2]} '" . (float)$matches[3] . "'";
            } else if (preg_match('/^(-?\d+\.?\d*)\s*(>|>=)\s*(-?\d+\.?\d*)$/', html_entity_decode(trim(str_replace(",", ".", $filter))), $matches) && count($matches) == 4 && (float)$matches[1] >= (float)$matches[3]) {
                return "'" . (float)$matches[1] . "' ${matches[2]} $field AND $field ${matches[2]} '" . (float)$matches[3] . "'";
            } else if (preg_match('/^(<|<=|>|>=)\s*(-?\d+\.?\d*)$/', html_entity_decode(trim(str_replace(",", ".", $filter))), $matches) && count($matches) == 3) {
                return "$field ${matches[1]} '" . (float)$matches[2] . "'";
            } else if (preg_match('/^(-?\d+\.?\d*)\s*(>|>=|<|<=)$/', html_entity_decode(trim(str_replace(",", ".", $filter))), $matches) && count($matches) == 3) {
                return "'" . (float)$matches[1] . "' ${matches[2]} $field";
            } else {
                return $field . " = '" . (int)$filter . "'";
            }
        }
    }
}
<?php
class ModelCatalogbarbaraStickers extends Model {

    public function getStickers($product_id) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_sticker WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
      $result = array();
      foreach ($query->rows as $sticker) {
        $result[] = array(
            'langdata'    => unserialize($sticker['langdata']),
            'image'       => $sticker['image'],
            'priority'    => $sticker['priority'],
            'foncolor'    => $sticker['foncolor'],
            'available'   => $sticker['available'],
            'type'        => $sticker['type'],
            'sort_order'  => $sticker['sort_order']
        );
      }
      return $result;
    }

    public function getList() {
        return $this->db->query('SELECT * FROM '.DB_PREFIX.'barbara_stickers ORDER BY sort_order')->rows;
    }

    public function getGroupStickers($stickers_id) {
        return $this->db->query('SELECT * FROM '.DB_PREFIX.'barbara_stickers WHERE stickers_id = "' . (int)$stickers_id . '"')->row;
    }

    public function addStickers($data) {
        $sql = "
            INSERT INTO
                " . DB_PREFIX . "barbara_stickers
            SET
                langdata = '" . $this->db->escape(serialize($data['langdata'])) . "',
                image = '" . $this->db->escape($data['image']) . "',
                priority = '" . (int)$data['priority'] . "',
                foncolor = '" . $this->db->escape($data['foncolor']) . "',
                available = '" . (int)$data['available'] . "',
                type = '" . (int)$data['type'] . "',
                objects_type = '" . (int)$data['objects_type'] . "',
                categories = '" . $this->db->escape(trim($data['categories'], ', ')) . "',
                products = '" . $this->db->escape(trim($data['products'], ', ')) . "',
                manufacturers = '" . $this->db->escape(implode(',', isset($data['manufacturers']) ? $data['manufacturers'] : array() )) . "',
                enabled = '" . (int)$data['enabled'] . "',
                sort_order = '" . (int)$data['sort_order'] ."'";
        $this->db->query($sql);
    }

    public function editStickers($stickers_id, $data) {
        $sql = "
            UPDATE
                " . DB_PREFIX . "barbara_stickers
            SET
                langdata = '" . $this->db->escape(serialize($data['langdata'])) . "',
                image = '" . $this->db->escape($data['image']) . "',
                priority = '" . (int)$data['priority'] . "',
                foncolor = '" . $this->db->escape($data['foncolor']) . "',
                available = '" . (int)$data['available'] . "',
                type = '" . (int)$data['type'] . "',
                objects_type = '" . (int)$data['objects_type'] . "',
                categories = '" . $this->db->escape(trim($data['categories'], ', ')) . "',
                products = '" . $this->db->escape(trim($data['products'], ', ')) . "',
                manufacturers = '" . $this->db->escape(implode(',', isset($data['manufacturers']) ? $data['manufacturers'] : array() )) . "',
                enabled = '" . (int)$data['enabled'] . "',
                sort_order = '" . (int)$data['sort_order'] ."'
            WHERE
                stickers_id = '" . (int)$stickers_id . "'";
        $this->db->query($sql);
    }

    public function deleteStickers($stickers_id) {
        $sql = "DELETE FROM ". DB_PREFIX . "barbara_stickers WHERE stickers_id = '" . (int)$stickers_id . "'";
        $this->db->query($sql);
    }

    public function applyStickers() {
        $cnt = 0;

        $sql = "DELETE FROM ". DB_PREFIX . "product_sticker WHERE priority >= 0";
        $this->db->query($sql);

        $sql = 'SELECT * FROM '.DB_PREFIX.'barbara_stickers WHERE enabled = 1 ORDER BY sort_order';
        $query = $this->db->query($sql);

        $this->load->model('catalog/product');

        foreach ($query->rows as $sticker) {
            $products = array();
            if ($sticker['objects_type'] == 1) {
                $category_ids = explode(',', $sticker['categories']);
                foreach ($category_ids as $category_id){
                    $products_category = $this->model_catalog_product->getProductsByCategoryId($category_id);
                    $products = array_udiff($products, $products_category, array($this, 'compareProductIds')); //Отсекаем от основного массива дубли товаров
                    $products = array_merge($products, $products_category); //Добавляем товары из новой категории
                }
            } else if ($sticker['objects_type'] == 2) {
                $this->load->model('catalog/barbara_product');
                $products = $this->model_catalog_barbara_product->getProducts(explode(',', $sticker['products']));
            } else if ($sticker['objects_type'] == 3) {
                $this->load->model('catalog/barbara_product');
                $products = $this->model_catalog_barbara_product->getProductsByManufacturersId(explode(',', $sticker['manufacturers']));
            } else {
                $products = $this->getAllProducts();
            }

            $cnt += count($products);

            if (is_array($products)) {
                foreach ($products as $product){
                    $sql = "INSERT INTO " .
                                DB_PREFIX . "product_sticker
                            SET
                                product_id = '" . (int)$product['product_id'] . "',
                                langdata = '" . $this->db->escape($sticker['langdata']) . "',
                                image = '" . $this->db->escape($sticker['image']) . "',
                                priority = '" . (int)$sticker['priority'] . "',
                                foncolor = '" . $this->db->escape($sticker['foncolor']) . "',
                                available = '" . (int)$sticker['available'] . "',
                                type = '" . (int)$sticker['type'] . "',
                                sort_order = '" . (int)$sticker['sort_order'] . "'";
                    $this->db->query($sql);
                }
            }
        }

        $this->cache->delete('product');
        $this->cache->delete('store');
        $this->cache->delete('category');

        return $cnt;
    }

    public function makeInstall() {
       $this->install();
       $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_stickers` (
          `stickers_id` int(11) NOT NULL AUTO_INCREMENT,
          `langdata` TEXT NOT NULL,
          `image` varchar(255) NOT NULL,
          `foncolor` varchar(255) NOT NULL DEFAULT 0,
          `priority` int(5) NOT NULL,
          `available` int(5) NOT NULL DEFAULT 0,
          `type` int(1) NOT NULL,
          `objects_type` int(1) NOT NULL,
          `categories` text NOT NULL,
          `products` text NOT NULL,
          `manufacturers` text NOT NULL,
          `enabled` int(1) NOT NULL DEFAULT 1,
          `sort_order` int(6) NOT NULL,
          PRIMARY KEY (`stickers_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        $query = $this->db->query('SHOW INDEXES FROM '.DB_PREFIX.'product');
        $found = false;
        foreach ($query->rows as $row){
            if ($row['Column_name'] == 'manufacturer_id'){
                $found = true;
                break;
            }
        }
        if (!$found){
            $this->db->query('ALTER TABLE '.DB_PREFIX.'product ADD INDEX IDX_manufacturer_id (manufacturer_id)');
        }
    }

    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_sticker` (
                          `product_sticker_id` int(11) NOT NULL auto_increment,
                          `product_id` int(11) NOT NULL,
                          `langdata` TEXT NOT NULL,
                          `image` VARCHAR(255) NOT NULL,
                          `foncolor` VARCHAR(255) NOT NULL DEFAULT 0,
                          `priority` int(5) NOT NULL,
                          `available` int(5) NOT NULL,
                          `type` int(1) NOT NULL,
                          `sort_order` int(6) NOT NULL DEFAULT 0,
                          PRIMARY KEY  (`product_sticker_id`),
                          KEY `product_id` (`product_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");
    }

    protected function getAllProducts() {
        return $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product`")->rows;
    }

    function compareProductIds($a, $b) {
        return ($a['product_id'] - $b['product_id']);
    }
}
?>
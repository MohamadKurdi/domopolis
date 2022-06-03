<?php
class ModelExtensionbarbaraBlogSetting extends Model {

    private $affected_row = 0;

    public function total_settings() {
      $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "barbara_blog_setting");
      return $query->row['total'];
    }

    public function setting_general($where = array(), $order = '', $start = 0, $limit = 20) {
      if ($start < 0) {
        $start = 0;
      }

      if ($limit < 1) {
        $limit = 20;
      }
      $sql = "SELECT ";
      $sql .= " * ";
      $sql .= "FROM " . DB_PREFIX . "barbara_blog_setting_general ";
      $inc = 1;
      if(is_array($where) && !empty($where)) {
        $sql .= "WHERE ";
        foreach ($where as $key => $value) {
          if($inc == 1) {
            $sql .= $key . $value;
          } else {
            $sql .= " AND " . $key . $value;
          }
          $inc++;
        }
      }
      if($order) {
        $sql .= " ORDER BY " . $order;
      }
      if($limit) {
        $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
      }
      // return $sql;
      $query = $this->db->query($sql);
      if ($query->num_rows) {
        return $query->rows;
      } else {
        //return false;
        return array();
      }
    }


    public function settings($where = array(), $order = '', $start = 0, $limit = 20) {
      if ($start < 0) {
        $start = 0;
      }

      if ($limit < 1) {
        $limit = 20;
      }
      $sql = "SELECT ";
      $sql .= " * ";
      $sql .= "FROM " . DB_PREFIX . "barbara_blog_setting ";
      $inc = 1;
      if(is_array($where) && !empty($where)) {
        $sql .= "WHERE ";
        foreach ($where as $key => $value) {
          if($inc == 1) {
            $sql .= $key . $value;
          } else {
            $sql .= " AND " . $key . $value;
          }
          $inc++;
        }
      }
      if($order) {
        $sql .= " ORDER BY " . $order;
      }
      if($limit) {
        $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
      }
      // return $sql;
      $query = $this->db->query($sql);
      if ($query->num_rows) {
        return $query->rows;
      } else {
        //return false;
        return array();
      }
    }

    public function setting($where = array(), $order = '', $start = 0, $limit = 20) {
      if ($start < 0) {
        $start = 0;
      }

      if ($limit < 1) {
        $limit = 20;
      }
      $sql = "SELECT ";
      $sql .= " * ";
      $sql .= "FROM " . DB_PREFIX . "barbara_blog_setting ";
      $inc = 1;
      if(is_array($where) && !empty($where)) {
        $sql .= "WHERE ";
        foreach ($where as $key => $value) {
          if($inc == 1) {
            $sql .= $key . $value;
          } else {
            $sql .= " AND " . $key . $value;
          }
          $inc++;
        }
      }
      if($order) {
        $sql .= " ORDER BY " . $order;
      }
      if($limit) {
        $sql .= " LIMIT " . (int)$start . "," . (int)$limit;
      }
      // return $sql;
      $query = $this->db->query($sql);
      if ($query->num_rows) {
        return $query->row;
      } else {
        return false;
      }
    }

    public function editSetting($data) {
      $this->event->trigger('pre.admin.setting.edit', $data);

      if(is_array($data['general_setting']) && !empty($data)) {
        foreach ($data['general_setting'] as $key => $value) {
          foreach ($value as $k => $v) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "barbara_blog_setting_general WHERE language_id = '" . (int)$key . "' AND setting_name = '" . $this->db->escape($k) . "'");
            if(!$query->num_rows > 0) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_setting_general SET language_id = '" . (int)$key . "', setting_name = '" . $this->db->escape($k) . "', setting_value = '" . $this->db->escape($v) . "'");
            } else {
              $this->db->query("UPDATE " . DB_PREFIX . "barbara_blog_setting_general SET setting_value = '" . $this->db->escape($v) . "' WHERE language_id = '" . (int)$key . "' AND setting_name = '" . $this->db->escape($k) . "' LIMIT 1");
              if($this->db->countAffected()) {
                $this->affected_row ++;
              } else {
                $this->affected_row = $this->affected_row;
              }
            }
          }
        }
      }

      if(is_array($data["setting"]) && !empty($data)) {
        foreach ($data["setting"] as $key => $value) {
          // print_r($value['name']); die();
          // $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "barbara_blog_setting WHERE setting_name = '" . $k . "'");
          // if(!$query->num_rows > 0) {
          // 	$this->db->query("INSERT INTO " . DB_PREFIX . "barbara_blog_setting SET language_id = '" . (int)$key . "', setting_name = '" . $k . "', setting_value = '" . $this->db->escape($v) . "'");
          // } else {
            $this->db->query("UPDATE " . DB_PREFIX . "barbara_blog_setting SET setting_value = '" . $this->db->escape($value['name']) . "', position = '" . (int)$value['position'] . "' WHERE setting_id = '" . (int)$key . "' LIMIT 1");
            if($this->db->countAffected()) {
              $this->affected_row ++;
            } else {
              $this->affected_row = $this->affected_row;
            }
          // }
        }
      }
      return $this->affected_row;
    }

    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_category` (
                            `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                            `status` varchar(11) NOT NULL DEFAULT 'publish',
                            `sort_order` int(3) NOT NULL,
                            `parent_id` int(11) NOT NULL,
                            `image` varchar(255) NOT NULL,
                            `date_added` datetime NOT NULL,
                            `date_modified` datetime NOT NULL,
                            PRIMARY KEY (`category_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_category_description` (
                            `category_id` int(11) unsigned NOT NULL,
                            `language_id` int(11) unsigned NOT NULL,
                            `name` varchar(255) CHARACTER SET utf8 NOT NULL,
                            `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
                            `description` longtext CHARACTER SET utf8 NOT NULL,
                            `meta_description` varchar(255) CHARACTER SET utf8 NOT NULL,
                            `meta_keyword` varchar(255) CHARACTER SET utf8 NOT NULL,
                            PRIMARY KEY (`category_id`,`language_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_category_filter` (
                            `category_id` int(11) NOT NULL,
                            `filter_id` int(11) NOT NULL,
                            PRIMARY KEY (`category_id`,`filter_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_category_path` (
                            `category_id` int(11) NOT NULL,
                            `path_id` int(11) NOT NULL,
                            `level` int(11) NOT NULL,
                            PRIMARY KEY (`category_id`,`path_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_category_to_layout` (
                            `category_id` int(11) NOT NULL,
                            `store_id` int(11) NOT NULL,
                            `layout_id` int(11) NOT NULL,
                            PRIMARY KEY (`category_id`,`store_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_category_to_store` (
                            `category_id` int(11) unsigned NOT NULL,
                            `store_id` int(11) unsigned NOT NULL DEFAULT '0',
                            PRIMARY KEY (`category_id`,`store_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_post` (
                            `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                            `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
                            `sort_order` int(11) NOT NULL,
                            `post_status` varchar(20) NOT NULL DEFAULT 'publish',
                            `view` int(11) NOT NULL DEFAULT '0',
                            `post_thumb` varchar(255) DEFAULT NULL,
                            `date_available` date NOT NULL,
                            `date_added` datetime NOT NULL,
                            `date_modified` datetime NOT NULL,
                            PRIMARY KEY (`ID`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_postmeta` (
                            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                            `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
                            `meta_key` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
                            `meta_value` longtext CHARACTER SET utf8,
                            `sort_order` int(11) NOT NULL,
                            PRIMARY KEY (`meta_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_post_description` (
                            `post_id` int(11) unsigned NOT NULL,
                            `language_id` int(11) unsigned NOT NULL,
                            `title` varchar(255) NOT NULL,
                            `content` longtext NOT NULL,
                            `excerpt` text NOT NULL,
                            `meta_description` varchar(255) NOT NULL,
                            `meta_keyword` varchar(255) NOT NULL,
                            `tag` text NOT NULL,
                            PRIMARY KEY (`post_id`,`language_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_post_filter` (
                            `post_id` int(11) NOT NULL,
                            `filter_id` int(11) NOT NULL,
                            PRIMARY KEY (`post_id`,`filter_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_post_to_category` (
                            `post_id` int(11) NOT NULL,
                            `category_id` int(11) NOT NULL,
                            PRIMARY KEY (`post_id`,`category_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_post_to_layout` (
                            `post_id` int(11) NOT NULL,
                            `store_id` int(11) NOT NULL,
                            `layout_id` int(11) NOT NULL,
                            PRIMARY KEY (`post_id`,`store_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_post_to_store` (
                            `post_id` int(11) unsigned NOT NULL,
                            `store_id` int(11) unsigned NOT NULL DEFAULT '0',
                            PRIMARY KEY (`post_id`,`store_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_related_product` (
                            `post_id` int(11) NOT NULL,
                            `product_id` int(11) NOT NULL,
                            PRIMARY KEY (`post_id`,`product_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_setting` (
                            `setting_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                            `setting_keyword` varchar(255) NOT NULL,
                            `setting_name` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
                            `setting_value` longtext CHARACTER SET utf8 NOT NULL,
                            `position` int(11) NOT NULL,
                            PRIMARY KEY (`setting_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "barbara_blog_setting_general` (
                            `setting_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                            `language_id` int(11) unsigned NOT NULL,
                            `setting_name` varchar(255) NOT NULL,
                            `setting_value` longtext NOT NULL,
                            PRIMARY KEY (`setting_id`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");
    
        if (!$this->db->query("SELECT * FROM `" . DB_PREFIX . "barbara_blog_setting` LIMIT 1")->row) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "barbara_blog_setting`
                                (`setting_id`, `setting_keyword`, `setting_name`, `setting_value`, `position`) VALUES
                                  (3, 'option', 'post_limit_front', '8', 0),
                                  (4, 'option', 'post_limit_admin', '20', 0),
                                  (6, 'option', 'category_limit_admin', '10', 0),
                                  (7, 'option', 'word_limit_in_post', '200', 0),
                                  (8, 'option', 'word_limit_in_related_post', '100', 0),
                                  (12, 'image', 'post_thumbnail_image_size', '260x200', 0),
                                  (13, 'image', 'related_post_image_size', '130x100', 0),
                                  (15, 'option', 'status_date', '1', 1),
                                  (18, 'image', 'post_thumbnail_position', 'left', 0),
                                  (20, 'image', 'post_thumbnail_visibility', '1', 0),
                                  (24, 'image', 'logo_image_size', '160x160', 0),
                                  (25, 'image', 'icon_image_size', '75x75', 0),
                                  (28, 'color', 'CSS_filename', 'myblog.css', 0);
            ");
        }

        if (!$this->db->query("SELECT * FROM `" . DB_PREFIX . "barbara_blog_setting_general` LIMIT 1")->row) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "barbara_blog_setting_general`
                                (`setting_id`, `language_id`, `setting_name`, `setting_value`) VALUES
                                  ('1', '1', 'name', 'Блог'),
                                  ('2', '1', 'name2', 'Похожие записи'),
                                  ('3', '1', 'relproduct', 'Сопутствующие товары'),
                                  ('4', '1', 'title', 'Блог'),
                                  ('5', '1', 'meta_description', ''),
                                  ('6', '1', 'meta_keyword', ''),
                                  ('7', '2', 'name', 'Blog'),
                                  ('8', '2', 'name2', 'Related Blogs'),
                                  ('9', '2', 'relproduct', 'Related Products'),
                                  ('10', '2', 'title', 'Blog'),
                                  ('11', '2', 'meta_description', ''),
                                  ('12', '2', 'meta_keyword', '');
            ");
        }
    }

    public function uninstall() {
        $this->_deleteTable(array(
            'barbara_blog_category',
            'barbara_blog_category_description',
            'barbara_blog_category_filter',
            'barbara_blog_category_path',
            'barbara_blog_category_to_layout',
            'barbara_blog_category_to_store',
            'barbara_blog_post',
            'barbara_blog_postmeta',
            'barbara_blog_post_description',
            'barbara_blog_post_filter',
            'barbara_blog_post_to_category',
            'barbara_blog_post_to_layout',
            'barbara_blog_post_to_store',
            'barbara_blog_related_product',
            'barbara_blog_setting',
            'barbara_blog_setting_general'
        ));
    }

    protected function _deleteTable($tables) {
        foreach ($tables as $table) {
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . $table . "`");
        }
    }
}
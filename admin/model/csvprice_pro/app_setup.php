<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
define("CSVPRICEPRO_SETUP_VERSION", "3.4.1.0");
class ModelCSVPriceProAppSetup extends Model
{
    private $error = [];
    public function Install()
    {
        $this->add_user_permissions();
        $this->install_table("csvprice_pro");
        $this->install_table("csvprice_pro_profiles");
        $this->install_table("csvprice_pro_crontab");
        $this->install_table("csvprice_pro_images");
        $this->init_version();
        $this->default_product_setting("setting");
        $this->default_product_setting("export");
        $this->default_product_setting("import");
        $this->default_category_setting("export");
        $this->default_category_setting("import");
        $this->default_manufacturer_setting("export");
        $this->default_manufacturer_setting("import");
        $this->default_product_profile();
        $this->init_core_type();
        if (file_exists(DIR_CATALOG . "../vqmod/xml/csvprice_pro.xml_")) {
            @rename(DIR_CATALOG . "../vqmod/xml/csvprice_pro.xml_", DIR_CATALOG . "../vqmod/xml/csvprice_pro.xml");
        }
        $this->clear_global_cache();
    }
    public function Uninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro_profiles`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro_crontab`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro_images`");
        unset($this->session->data["driver"]);
        unset($this->session->data["tabs"]);
        if (file_exists(DIR_CATALOG . "../vqmod/xml/csvprice_pro.xml")) {
            @rename(DIR_CATALOG . "../vqmod/xml/csvprice_pro.xml", DIR_CATALOG . "../vqmod/xml/csvprice_pro.xml_");
        }
        $this->clear_global_cache();
    }
    public function UpdateDB($version)
    {
        if ($version == "") {
            $this->Install();
            return true;
        }
        $_obfuscated_0D352A1A3F06172C34035C1E2B2E1D341826230E2B3822_ = explode(".", $version);
        $version = (int) ($_obfuscated_0D352A1A3F06172C34035C1E2B2E1D341826230E2B3822_[0] . $_obfuscated_0D352A1A3F06172C34035C1E2B2E1D341826230E2B3822_[1] . sprintf("%'.02d\n", $_obfuscated_0D352A1A3F06172C34035C1E2B2E1D341826230E2B3822_[2]));
        if ($version < 3300) {
            $this->UpdateDB_3211_to_3300();
        }
        $this->db->query("UPDATE `" . DB_PREFIX . "csvprice_pro` SET value = '" . CSVPRICEPRO_SETUP_VERSION . "' WHERE `key` = 'version';");
    }
    private function init_version()
    {
        $result = $this->db->query("SELECT value FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = 'version' LIMIT 1;");
        if ($result->num_rows != 1) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro` SET `key` = 'version', value = '" . CSVPRICEPRO_SETUP_VERSION . "', `serialized` = 0;");
        }
    }
    private function init_core_type()
    {
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_ = [];
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_to_category` LIKE 'main_category'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MAIN_CATEGORY"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'ean'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["EAN"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'jan'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["JAN"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'isbn'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ISBN"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'mpn'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MPN"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_description` LIKE 'seo_title'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["SEO_TITLE"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_description` LIKE 'seo_h1'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["SEO_H1"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "category_description` LIKE 'seo_title'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["CATEGORY_SEO_TITLE"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "category_description` LIKE 'seo_h1'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["CATEGORY_SEO_H1"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_description` LIKE 'tag'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_TAG"] = 0 < $result->num_rows ? true : false;
        if (in_array(VERSION, ["1.5.1.3", "1.5.1.3.1", "1.5.2", "1.5.2.1", "1.5.3.1"])) {
            $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_TAG_OLD"] = 1;
        } else {
            $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_TAG_OLD"] = 0;
        }
        $result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MANUFACTURER_DESCRIPTION"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "filter'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_FILTER"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "customer_group_description'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["CUSTOMER_DESC"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "option_value_description` LIKE 'links'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_OPTION_LINKS"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'payment_company_id'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ORDER_PAYMENT_COMPANY_ID"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'payment_code'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ORDER_PAYMENT_CODE"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'shipping_code'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ORDER_SHIPPING_CODE"] = 0 < $result->num_rows ? true : false;
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_product` LIKE 'product_reward'");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ORDER_PRODUCT_REWARD"] = 0 < $result->num_rows ? true : false;
        $this->load->model("catalog/category");
        if (method_exists($this->model_catalog_category, "repairCategories")) {
            $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["REPAIR_CATEGORIES"] = true;
        } else {
            $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["REPAIR_CATEGORIES"] = false;
        }
        $this->editSetting("CoreType", $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_);
        unset($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_);
    }
    private function install_table($table)
    {
        switch ($table) {
            case "csvprice_pro":
                $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro`;");
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "csvprice_pro` (`setting_id` int(11) NOT NULL AUTO_INCREMENT,`key` varchar(64) NOT NULL,`value` text,`serialized` tinyint(1) NOT NULL default '1',PRIMARY KEY (`setting_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
                break;
            case "csvprice_pro_profiles":
                $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro_profiles`;");
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "csvprice_pro_profiles` (`profile_id` int(11) NOT NULL AUTO_INCREMENT,`key` varchar(64) NOT NULL,`name` varchar(128) NOT NULL,`value` text,`serialized` tinyint(1) NOT NULL default '1',PRIMARY KEY (`profile_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
                break;
            case "csvprice_pro_crontab":
                $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro_crontab`;");
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "csvprice_pro_crontab` (`job_id` int(11) NOT NULL AUTO_INCREMENT,`profile_id` int(11) NOT NULL,`job_key` varchar(64) NOT NULL,`job_type` ENUM(\"import\", \"export\"),`job_file_location` ENUM(\"dir\", \"web\", \"ftp\"),`job_time_start` datetime NOT NULL,`job_offline` tinyint(1) NOT NULL default '0',`job_data` text,`status` tinyint(1) NOT NULL default '0',`serialized` tinyint(1) NOT NULL default '0',PRIMARY KEY (`job_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
                break;
            case "csvprice_pro_images":
                $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "csvprice_pro_images`;");
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "csvprice_pro_images` (`catalog_id` int(11) NOT NULL,`image_key` char(32) NOT NULL,`image_path` varchar(255) NOT NULL,PRIMARY KEY (`catalog_id`, `image_key`),KEY `image_key` (`image_key`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
                break;
        }
    }
    public function defaultProductImportSetting()
    {
        $this->default_product_setting("setting");
    }
    private function default_product_setting($key)
    {
        if ($key == "setting") {
            $data = ["to_store" => [0], "status" => 1, "tax_class_id" => 0, "minimum" => 1, "sort_order" => 100, "subtract" => 1, "stock_status_id" => (int) $this->config->get("config_stock_status_id"), "shipping" => 0, "length_class_id" => (int) $this->config->get("config_length_class_id"), "weight_class_id" => (int) $this->config->get("config_weight_class_id"), "option_type" => "select", "option_required" => 0, "option_value" => "", "option_quantity" => 0, "option_subtract_stock" => 0, "option_price_prefix" => "+", "option_points_prefix" => "+", "option_points_default" => 0, "option_weight_prefix" => "+", "option_weight_default" => 0];
            $this->deleteSetting("ProductSetting");
            $this->editSetting("ProductSetting", $data);
        }
        if ($key == "export") {
            $data = ["file_encoding" => "UTF-8", "csv_delimiter" => ";", "language_id" => (int) $this->config->get("config_language_id"), "from_store" => [], "product_category" => 0, "export_category" => 0, "export_related" => 0, "delimiter_category" => "|", "product_manufacturer" => 0, "image_url" => 0, "limit_start" => 0, "limit_end" => 1000, "product_filter" => 0, "filter_name" => "", "filter_sku" => "", "filter_location" => "", "filter_price_prefix" => 1, "filter_price" => "", "filter_price_start" => "", "filter_price_end" => "", "filter_quantity_prefix" => 1, "filter_quantity" => "", "filter_stock_status" => 0, "filter_status" => 3, "fields_set" => ["_NAME_" => 1, "_ID_" => 1]];
            $this->deleteSetting("ProductExport");
            $this->editSetting("ProductExport", $data);
        }
        if ($key == "import") {
            $data = ["file_encoding" => "UTF-8", "csv_delimiter" => ";", "csv_import" => 0, "mode" => 2, "calc_mode" => 0, "language_id" => (int) $this->config->get("config_language_id"), "key_field" => "_ID_", "image_download" => 0, "skip_import_store" => 1, "skip_manufacturer" => 1, "skip_main_category" => 1, "skip_category" => 1, "iter_limit" => 0, "delimiter_category" => "|", "fill_category" => 0, "top" => 1, "column" => 1];
            $this->deleteSetting("ProductImport");
            $this->editSetting("ProductImport", $data);
        }
    }
    private function default_category_setting($key)
    {
        if ($key == "export") {
            $data = ["file_encoding" => "UTF-8", "csv_delimiter" => ";", "from_store" => [0], "language_id" => (int) $this->config->get("config_language_id"), "fields_set" => [], "category_parent" => 0, "delimiter_category" => "|"];
            $this->editSetting("CategoryExport", $data);
            unset($data);
        }
        if ($key == "import") {
            $data = ["file_encoding" => "UTF-8", "csv_delimiter" => ";", "to_store" => [0], "mode" => 2, "language_id" => (int) $this->config->get("config_language_id"), "key_field" => "_ID_", "delimiter_category" => "|", "category_disable" => 0, "sort_order" => 1, "status" => 1];
            $this->editSetting("CategoryImport", $data);
            unset($data);
        }
    }
    private function default_manufacturer_setting($key)
    {
        if ($key == "export") {
            $data = ["file_encoding" => "UTF-8", "csv_delimiter" => ";", "from_store" => [0], "language_id" => (int) $this->config->get("config_language_id"), "fields_set" => []];
            $this->editSetting("ManufacturerExport", $data);
            unset($data);
        }
        if ($key == "import") {
            $data = ["file_encoding" => "UTF-8", "csv_delimiter" => ";", "to_store" => [0], "mode" => 2, "language_id" => (int) $this->config->get("config_language_id"), "key_field" => "_ID_", "sort_order" => 1];
            $this->editSetting("ManufacturerImport", $data);
            unset($data);
        }
    }
    private function default_product_profile()
    {
        $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "csvprice_pro_profiles`");
        $_obfuscated_0D2835191314350E3B1A050D33332B4029392224191201_ = $this->getSetting("ProductSetting");
        $_obfuscated_0D14071C1F0623055C11055B021D1D01162E40363C1C22_ = $this->getSetting("ProductImport");
        $_obfuscated_0D22331B10242A302F3040090725083E2912360A3C2201_ = $this->getSetting("ProductExport");
        $data = [];
        $data["csv_setting"] = $_obfuscated_0D2835191314350E3B1A050D33332B4029392224191201_;
        $data["csv_import"] = $_obfuscated_0D14071C1F0623055C11055B021D1D01162E40363C1C22_;
        $data = base64_encode(json_encode($data));
        $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro_profiles` SET `key` = 'profile_import', `name` = 'Default import', `value` = '" . $this->db->escape($data) . "'");
        $data = [];
        $data["csv_setting"] = $_obfuscated_0D2835191314350E3B1A050D33332B4029392224191201_;
        $data["csv_export"] = $_obfuscated_0D22331B10242A302F3040090725083E2912360A3C2201_;
        $data = base64_encode(json_encode($data));
        $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro_profiles` SET `key` = 'profile_export', `name` = 'Default export', `value` = '" . $this->db->escape($data) . "'");
    }
    private function editSetting($key, $value)
    {
        $value = base64_encode(json_encode($value));
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = '" . $key . "' LIMIT 1;");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            $_obfuscated_0D32293B23132709051307241F0A0706061328113B0B22_ = $result->row["setting_id"];
            $this->db->query("UPDATE `" . DB_PREFIX . "csvprice_pro` SET `key` = '" . $key . "', `value` = '" . $this->db->escape($value) . "' WHERE `setting_id` = '" . $_obfuscated_0D32293B23132709051307241F0A0706061328113B0B22_ . "';");
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro` SET `key` = '" . $key . "', `value` = '" . $this->db->escape($value) . "';");
        }
    }
    private function getSetting($key)
    {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = '" . $key . "' LIMIT 1;");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return json_decode(base64_decode($result->row["value"]), true);
        }
        return false;
    }
    private function deleteSetting($key)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = '" . $key . "'");
    }
    private function add_user_permissions()
    {
        $_obfuscated_0D08393030325B3F17011B1F150C3C2B0D161413052901_ = ["csvprice_pro/app_product", "csvprice_pro/app_category", "csvprice_pro/app_manufacturer", "csvprice_pro/app_crontab", "csvprice_pro/app_customer", "csvprice_pro/app_order", "csvprice_pro/app_about"];
        $this->load->model("user/user_group");
        foreach ($_obfuscated_0D08393030325B3F17011B1F150C3C2B0D161413052901_ as $value) {
            $this->model_user_user_group->addPermission($this->user->getId(), "access", $value);
            $this->model_user_user_group->addPermission($this->user->getId(), "modify", $value);
        }
    }
    private function clear_global_cache()
    {
        $_obfuscated_0D09272B1F3240352529371E1E3711051508161B393D22_ = DIR_CACHE;
        foreach (glob($_obfuscated_0D09272B1F3240352529371E1E3711051508161B393D22_ . "*.*") as $_obfuscated_0D1F2C2D170D2B030518302819121E1E5C1A3C0D092711_) {
            if ($_obfuscated_0D1F2C2D170D2B030518302819121E1E5C1A3C0D092711_ != DIR_CACHE . "index.html") {
                @unlink($_obfuscated_0D1F2C2D170D2B030518302819121E1E5C1A3C0D092711_);
            }
        }
        $_obfuscated_0D0E09010F33170339162233263237100B012F080A3C01_ = DIR_CATALOG . "../vqmod/vqcache/";
        foreach (glob($_obfuscated_0D0E09010F33170339162233263237100B012F080A3C01_ . "*.*") as $_obfuscated_0D2F051F232612131F171A292F37170A38403E0E2E2532_) {
            @unlink($_obfuscated_0D2F051F232612131F171A292F37170A38403E0E2E2532_);
        }
    }
    private function UpdateDB_3211_to_3300()
    {
        $this->load->model("csvprice_pro/app_setting");
        $_obfuscated_0D3C1C3F373502162C210B341017351B065C2B090D3F32_ = $this->model_csvprice_pro_app_setting->getLicenseKey();
        $this->Uninstall();
        $this->Install();
        $this->model_csvprice_pro_app_setting->addLicenseKey($_obfuscated_0D3C1C3F373502162C210B341017351B065C2B090D3F32_);
    }
}

?>
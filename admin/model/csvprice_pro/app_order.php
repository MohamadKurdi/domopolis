<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppOrder extends Model
{
    private $CSV_SEPARATOR = ";";
    private $CSV_ENCLOSURE = "\"";
    private $field_caption;
    private $CoreType = [];
    private $ExportConfig = [];
    private $ImportConfig = [];
    private $ExportSetting = [];
    private $ImportOrderField = [];
    private $FileHandle;
    private $FileTell = 0;
    public function OrderExport()
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->ExportConfig = $this->model_csvprice_pro_app_setting->getSetting("OrderExport");
        $this->ExportSetting = $this->model_csvprice_pro_app_setting->getSetting("OrderSetting");
        if (empty($this->ExportSetting)) {
            return ["error" => "error_export_empty_rows"];
        }
        $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ExportSetting["delimiter"]);
        $_obfuscated_0D295B0A10141A23381421325C14152F14113E32403811_ = 0;
        $query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language l1 WHERE l1.code = '" . $this->config->get("config_admin_language") . "'");
        if (isset($query->row["language_id"])) {
            $_obfuscated_0D295B0A10141A23381421325C14152F14113E32403811_ = $query->row["language_id"];
        }
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["order_id"] = ["field" => "o.order_id", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["invoice_no"] = ["field" => "CONCAT(o.invoice_prefix, o.invoice_no)", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["invoice_date"] = ["field" => "o.date_added AS invoice_date", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["store_id"] = ["field" => "o.store_id", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["store_name"] = ["field" => "o.store_name", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["store_url"] = ["field" => "o.store_url", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["customer_id"] = ["field" => "o.customer_id", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["customer_group_id"] = ["field" => "o.customer_group_id", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["firstname"] = ["field" => "o.firstname", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["lastname"] = ["field" => "o.lastname", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["firstname_lastname"] = ["field" => "CONCAT_WS(' ',o.firstname, o.lastname)", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["email"] = ["field" => "o.email", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["telephone"] = ["field" => "o.telephone", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["fax"] = ["field" => "o.fax", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_firstname"] = ["field" => "o.payment_firstname", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_lastname"] = ["field" => "o.payment_lastname", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_company"] = ["field" => "o.payment_company", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_company_id"] = ["field" => "o.payment_company_id", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_address_1"] = ["field" => "o.payment_address_1", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_address_2"] = ["field" => "o.payment_address_2", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_city"] = ["field" => "o.payment_city", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_postcode"] = ["field" => "o.payment_postcode", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_country"] = ["field" => "o.payment_country", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_zone"] = ["field" => "o.payment_zone", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_method"] = ["field" => "o.payment_method", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["payment_code"] = ["field" => "o.payment_code", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_firstname"] = ["field" => "o.shipping_firstname", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_lastname"] = ["field" => "o.shipping_lastname", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_company"] = ["field" => "o.shipping_company", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_address_1"] = ["field" => "o.shipping_address_1", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_address_2"] = ["field" => "o.shipping_address_2", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_city"] = ["field" => "o.shipping_city", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_postcode"] = ["field" => "o.shipping_postcode", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_country"] = ["field" => "o.shipping_country", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_zone"] = ["field" => "o.shipping_zone", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_method"] = ["field" => "o.shipping_method", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["shipping_code"] = ["field" => "o.shipping_code", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["comment"] = ["field" => "o.comment", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["total"] = ["field" => "o.total", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["order_status"] = ["field" => "os.name", "where" => "os.language_id = " . $_obfuscated_0D295B0A10141A23381421325C14152F14113E32403811_, "join" => "LEFT JOIN `" . DB_PREFIX . "order_status` os ON (os.order_status_id = o.order_status_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["affiliate_id"] = ["field" => "o.affiliate_id", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["commission"] = ["field" => "o.commission", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["currency_code"] = ["field" => "o.currency_code", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["ip"] = ["field" => "o.ip", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["date_added"] = ["field" => "o.date_added", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["date_modified"] = ["field" => "o.date_modified", "where" => "", "join" => ""];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_id"] = ["field" => "op.product_id AS product_id", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_name"] = ["field" => "op.name AS product_name", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_options"] = ["field" => "op.order_product_id", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_model"] = ["field" => "op.model AS product_model", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_sku"] = ["field" => "p1.sku AS product_sku", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "product` p1 ON (op.product_id = p1.product_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_quantity"] = ["field" => "op.quantity AS product_quantity", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_price"] = ["field" => "op.price AS product_price", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_total"] = ["field" => "op.total AS product_total", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_tax"] = ["field" => "op.tax AS product_tax", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_["product_reward"] = ["field" => "op.reward AS product_reward", "where" => "", "join" => "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)"];
        $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = "";
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_ = [];
        $_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_ = [];
        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_ = [];
        $_obfuscated_0D052D313D2704290A13151A13100C0D030E5C26151201_ = [];
        if ($this->ExportConfig["filter_order_id"] != "") {
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "o.order_id = " . (int) $this->ExportConfig["filter_order_id"];
        } else {
            if ($this->ExportConfig["filter_customer"] != "") {
                $this->load->model("sale/customer");
                $_obfuscated_0D350B390E2D3340082725021D32262132062D25013F11_ = ["filter_name" => $this->ExportConfig["filter_customer"], "start" => 0, "limit" => 1];
                $results = $this->model_sale_customer->getCustomers($_obfuscated_0D350B390E2D3340082725021D32262132062D25013F11_);
                if (0 < count($results) && isset($results[0]["customer_id"])) {
                    $_obfuscated_0D1D3E2F040702293F2F290908382805072C4009181B11_ = $results[0]["customer_id"];
                } else {
                    $_obfuscated_0D1D3E2F040702293F2F290908382805072C4009181B11_ = 0;
                }
                if ($_obfuscated_0D1D3E2F040702293F2F290908382805072C4009181B11_) {
                    $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "o.customer_id = " . (int) $_obfuscated_0D1D3E2F040702293F2F290908382805072C4009181B11_;
                }
            }
            if ($this->ExportConfig["filter_order_status_id"] != "*") {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "o.order_status_id = " . (int) $this->ExportConfig["filter_order_status_id"];
            }
            if ($this->ExportConfig["filter_date_added_start"] != "") {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "DATE(o.date_added) >= DATE('" . $this->db->escape($this->ExportConfig["filter_date_added_start"]) . "')";
            }
            if ($this->ExportConfig["filter_date_added_end"] != "") {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "DATE(o.date_added) <= DATE('" . $this->db->escape($this->ExportConfig["filter_date_added_end"]) . "')";
            }
            if ($this->ExportConfig["filter_date_modified_start"] != "") {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "DATE(o.date_modified) >= DATE('" . $this->db->escape($this->ExportConfig["filter_date_modified_start"]) . "')";
            }
            if ($this->ExportConfig["filter_date_modified_end"] != "") {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "DATE(o.date_modified) <= DATE('" . $this->db->escape($this->ExportConfig["filter_date_modified_end"]) . "')";
            }
            if ($this->ExportConfig["filter_total_sum"] != "") {
                $sum = str_replace(",", ".", $this->ExportConfig["filter_total_sum"]);
                $this->db->escape($this->ExportConfig["filter_total_prefix"]);
                switch ((int) $this->db->escape($this->ExportConfig["filter_total_prefix"])) {
                    case 1:
                        $_obfuscated_0D1B38373E1E333B072E1B111E403F3B3F0C1E061D1922_ = "=";
                        break;
                    case 2:
                        $_obfuscated_0D1B38373E1E333B072E1B111E403F3B3F0C1E061D1922_ = ">=";
                        break;
                    case 3:
                        $_obfuscated_0D1B38373E1E333B072E1B111E403F3B3F0C1E061D1922_ = "<=";
                        break;
                    case 4:
                        $_obfuscated_0D1B38373E1E333B072E1B111E403F3B3F0C1E061D1922_ = "<>";
                        break;
                    default:
                        $_obfuscated_0D1B38373E1E333B072E1B111E403F3B3F0C1E061D1922_ = "=";
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = "o.total " . $_obfuscated_0D1B38373E1E333B072E1B111E403F3B3F0C1E061D1922_ . " " . (double) $sum;
                }
            }
        }
        $_obfuscated_0D130B32021E3E23013310165C0F3B2C1D360C31332332_ = 0;
        foreach ($this->ExportSetting["fields_set"] as $key => $value) {
            if ($value["status"] == 1) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["field"];
                $_obfuscated_0D052D313D2704290A13151A13100C0D030E5C26151201_[] = $value["caption"];
                if (!in_array($_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["where"], $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_) && $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["where"] != "") {
                    $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["where"];
                }
                if (!in_array($_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["join"], $_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_) && $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["join"] != "") {
                    $_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_[] = $_obfuscated_0D150533110321230C1E40142215110B27270B09082B11_[$key]["join"];
                }
                $_obfuscated_0D130B32021E3E23013310165C0F3B2C1D360C31332332_++;
            }
        }
        $_obfuscated_0D0C1E3F400C302705125C1A0E2D2F0B29192B2F113201_ = "LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id)";
        if (($key = array_search($_obfuscated_0D0C1E3F400C302705125C1A0E2D2F0B29192B2F113201_, $_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_)) !== false) {
            unset($_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_[$key]);
            array_unshift($_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_, $_obfuscated_0D0C1E3F400C302705125C1A0E2D2F0B29192B2F113201_);
        }
        if (!empty($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_)) {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = " WHERE " . implode(" AND ", $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_);
        } else {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = "";
        }
        if (0 < count($_obfuscated_0D1E321E28291423332B160931072B3731220416340322_)) {
            $sql = "SELECT " . implode(",", $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_) . " FROM `" . DB_PREFIX . "order` o " . implode(" ", $_obfuscated_0D3C16161E2F2713173E2A5C2402303203391F153F1C01_) . $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ . " ORDER BY o.order_id DESC";
            $query = $this->db->query($sql);
            if (count($query->rows) < 1) {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = ["error" => "error_export_empty_rows"];
                return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
            }
            ini_set("default_charset", "UTF-8");
            $_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_ = $this->model_csvprice_pro_app_setting->getTmpDir();
            if (!$_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_) {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"] = $this->language->get("error_directory_not_available");
                return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
            }
            $file = $_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_ . "/" . uniqid() . ".csv";
            if (($handle = fopen($file, "w")) !== false) {
                if ($this->ExportSetting["csv_title"] == 1) {
                    fputcsv($handle, $_obfuscated_0D052D313D2704290A13151A13100C0D030E5C26151201_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
                }
                foreach ($query->rows as $_obfuscated_0D05343333043F38061F12062F5C2636032A2708292211_) {
                    if (isset($_obfuscated_0D05343333043F38061F12062F5C2636032A2708292211_["order_product_id"])) {
                        $_obfuscated_0D2112262C042C131F091C17240F0D243E16185B2E2E32_ = $this->getOrderProductOption($_obfuscated_0D05343333043F38061F12062F5C2636032A2708292211_["order_product_id"]);
                        $_obfuscated_0D05343333043F38061F12062F5C2636032A2708292211_["order_product_id"] = $_obfuscated_0D2112262C042C131F091C17240F0D243E16185B2E2E32_;
                    }
                    fputcsv($handle, $_obfuscated_0D05343333043F38061F12062F5C2636032A2708292211_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
                }
                fclose($handle);
                if (($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = file_get_contents($file)) !== false) {
                    unlink($file);
                    return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
                }
                return "";
            } else {
                return "";
            }
        } else {
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = ["error" => "error_export_empty_rows"];
            return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
        }
    }
    public function OrderImport($file_name)
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->load->model("csvprice_pro/lib_order_import");
        $this->ImportConfig = $this->model_csvprice_pro_app_setting->getSetting("OrderImport");
        $this->ImportOrderField = isset($this->ImportConfig["fields"]) ? $this->ImportConfig["fields"] : [];
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        if (isset($this->ImportConfig["csv_delimiter"]) && $this->ImportConfig["csv_delimiter"]) {
            $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ImportConfig["csv_delimiter"]);
        }
        if (isset($this->ImportConfig["csv_text_delimiter"]) && $this->ImportConfig["csv_text_delimiter"]) {
            $this->CSV_ENCLOSURE = trim(htmlspecialchars_decode($this->ImportConfig["csv_text_delimiter"]));
        }
        if (($this->FieldCaption = $this->getFieldCaption($file_name)) === NULL) {
            $result["error_import"] = $this->language->get("error_import_field_caption");
            return $result;
        }
        $count = ["total" => 0, "update" => 0, "error" => 0];
        $result = [];
        if (($this->FileHandle = fopen($file_name, "r")) !== false) {
            fseek($this->FileHandle, $this->FileTell);
            while (($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_ = fgetcsv($this->FileHandle, 0, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== false) {
                if (substr($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0], 0, 3) == pack("CCC", 239, 187, 191)) {
                    $_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0] = substr($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0], 3);
                }
                $count["total"]++;
                $_obfuscated_0D322C1C1D3D0D0C2A28352F3036025C02241326251711_ = false;
                if (count($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_) == count($this->FieldCaption)) {
                    if (isset($this->FieldCaption["order_id"]) && isset($this->FieldCaption["order_status_id"])) {
                        $_obfuscated_0D322C1C1D3D0D0C2A28352F3036025C02241326251711_ = $this->updateOrderStatus($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_);
                    }
                    if (isset($this->FieldCaption["order_id"]) && isset($this->FieldCaption["customer_id"]) && isset($this->FieldCaption["amount"])) {
                        $_obfuscated_0D322C1C1D3D0D0C2A28352F3036025C02241326251711_ = $this->updateOrderTransaction($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_);
                    }
                    if ($_obfuscated_0D322C1C1D3D0D0C2A28352F3036025C02241326251711_) {
                        $count["update"]++;
                    } else {
                        $count["error"]++;
                    }
                } else {
                    $count["error"]++;
                }
            }
            fclose($this->FileHandle);
            $result["total"] = $count["total"];
            $result["update"] = $count["update"];
            $result["error"] = $count["error"];
        }
        return $result;
    }
    private function updateOrderTransaction($data)
    {
        if (!isset($this->FieldCaption["order_id"]) || !isset($this->FieldCaption["customer_id"]) || !isset($this->FieldCaption["amount"])) {
            return false;
        }
        if (isset($this->FieldCaption["amount"])) {
            $_obfuscated_0D0231361C31041F3C2A031E0637301F2C153D101F2732_ = $data[$this->FieldCaption["amount"]];
        } else {
            $_obfuscated_0D0231361C31041F3C2A031E0637301F2C153D101F2732_ = 0;
        }
        if (isset($this->FieldCaption["amount_notify"])) {
            $_obfuscated_0D343C241D11212C291C0A273E220411373D072C061911_ = $data[$this->FieldCaption["amount_notify"]];
        } else {
            $_obfuscated_0D343C241D11212C291C0A273E220411373D072C061911_ = 0;
        }
        if (isset($this->FieldCaption["description"])) {
            $_obfuscated_0D0B270C25364033223933062F2D2F222D3C0B24231A22_ = $data[$this->FieldCaption["description"]];
        } else {
            $_obfuscated_0D0B270C25364033223933062F2D2F222D3C0B24231A22_ = "";
        }
        $_obfuscated_0D18220A2D2F032314072F331F262B0422384039041932_ = (int) $data[$this->FieldCaption["order_id"]];
        $_obfuscated_0D1D3E2F040702293F2F290908382805072C4009181B11_ = (int) $data[$this->FieldCaption["customer_id"]];
        $this->model_csvprice_pro_lib_order_import->addTransaction($_obfuscated_0D1D3E2F040702293F2F290908382805072C4009181B11_, $_obfuscated_0D0B270C25364033223933062F2D2F222D3C0B24231A22_, $_obfuscated_0D0231361C31041F3C2A031E0637301F2C153D101F2732_, $_obfuscated_0D18220A2D2F032314072F331F262B0422384039041932_, $_obfuscated_0D343C241D11212C291C0A273E220411373D072C061911_);
        return true;
    }
    private function updateOrderStatus($data)
    {
        if (!isset($this->FieldCaption["order_id"]) || !isset($this->FieldCaption["order_status_id"])) {
            return false;
        }
        if (isset($this->FieldCaption["comment"])) {
            $_obfuscated_0D13193D283B021A3C3F350B2E07062F35012E16343311_ = $data[$this->FieldCaption["comment"]];
        } else {
            $_obfuscated_0D13193D283B021A3C3F350B2E07062F35012E16343311_ = "";
        }
        if (isset($this->FieldCaption["notify"])) {
            $_obfuscated_0D2C12323728372E24022409390C1C023433031C2A2E11_ = (int) $data[$this->FieldCaption["notify"]];
        } else {
            $_obfuscated_0D2C12323728372E24022409390C1C023433031C2A2E11_ = 0;
        }
        $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_ = ["order_id" => (int) $data[$this->FieldCaption["order_id"]], "order_status_id" => (int) $data[$this->FieldCaption["order_status_id"]], "comment" => $_obfuscated_0D13193D283B021A3C3F350B2E07062F35012E16343311_, "notify" => $_obfuscated_0D2C12323728372E24022409390C1C023433031C2A2E11_];
        $this->model_csvprice_pro_lib_order_import->addOrderHistory($_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_["order_id"], $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_);
        return true;
    }
    private function getOrderProductOption($order_product_id = 0)
    {
        $query = $this->db->query("SELECT op.name, op.value FROM `" . DB_PREFIX . "order_option` op WHERE op.order_product_id = '" . $order_product_id . "';");
        if ($query->num_rows) {
            $_obfuscated_0D071E07192434072F2B0E311D390A2C042E5B191C1211_ = [];
            foreach ($query->rows as $result) {
                $_obfuscated_0D071E07192434072F2B0E311D390A2C042E5B191C1211_[] = $result["name"] . ": " . $result["value"];
            }
            if (!empty($_obfuscated_0D071E07192434072F2B0E311D390A2C042E5B191C1211_)) {
                return implode(", ", $_obfuscated_0D071E07192434072F2B0E311D390A2C042E5B191C1211_);
            }
        }
        return "";
    }
    private function getFieldCaption($file_name)
    {
        $result = NULL;
        if (!empty($this->ImportOrderField)) {
            $_obfuscated_0D2517120E1D0216243F31271109091822223D3F0E0D32_ = 1;
        } else {
            $_obfuscated_0D2517120E1D0216243F31271109091822223D3F0E0D32_ = 0;
        }
        $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_ = [];
        if (($this->FileHandle = fopen($file_name, "r")) !== false) {
            if (($field_caption = fgetcsv($this->FileHandle, 0, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== false) {
                if (substr($field_caption[0], 0, 3) == pack("CCC", 239, 187, 191)) {
                    $field_caption[0] = substr($field_caption[0], 3);
                }
                for ($i = 0; $i < count($field_caption); $i++) {
                    if ($_obfuscated_0D2517120E1D0216243F31271109091822223D3F0E0D32_ && ($_obfuscated_0D1F060836251015141F112F2312282A01141C3E092932_ = array_search($field_caption[$i], $this->ImportOrderField)) !== false) {
                        $field_caption[$i] = $_obfuscated_0D1F060836251015141F112F2312282A01141C3E092932_;
                    }
                    $field_caption[$i] = trim($field_caption[$i], " \t\n\r");
                    if (empty($field_caption[$i]) || in_array($field_caption[$i], $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_)) {
                        $field_caption[$i] = "undefine_" . $i;
                    } else {
                        $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_[] = $field_caption[$i];
                    }
                }
                $result = array_flip($field_caption);
                $this->FileTell = ftell($this->FileHandle);
            }
            fclose($this->FileHandle);
        }
        return $result;
    }
}

?>
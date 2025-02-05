<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppProduct extends Model
{
    private $CSV_SEPARATOR = ";";
    private $CSV_ENCLOSURE = "\"";
    private $FieldCaption;
    private $CoreType = [];
    private $PathNameByCategory = [];
    private $CategoryMaxLevel = 10;
    private $LanguageID;
    private $CustomerGroup = [];
    private $Categories = [];
    private $CategoriesString = "";
    private $AttributeCache = [];
    private $CustomFields = [];
    private $StockStatus = [];
    private $ImagePath;
    private $ImageURL = "";
    private $SeoURL = "";
    private $RelatedCaption = "";
    private $ProductRelatedSQL = "";
    private $ImportSetting = [];
    private $ImportConfig = [];
    private $ExportConfig = [];
    private $CountLanguages = 1;
    private $FileHandle;
    private $FileTell = 0;
    public function ProductExport($profile_id = 0)
    {
        $this->load->model("csvprice_pro/app_setting");
        if ($profile_id) {
            $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_ = $this->getProfile($profile_id);
            $this->ExportConfig = $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_export"];
        } else {
            $this->ExportConfig = $this->model_csvprice_pro_app_setting->getSetting("ProductExport");
        }
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->LanguageID = $this->ExportConfig["language_id"];
        if ($this->ExportConfig["image_url"] && (defined("HTTP_CATALOG") || defined("HTTPS_CATALOG"))) {
            $_obfuscated_0D342E030A1A320A3605110A071F143C2C095B18083922_ = $this->config->get("config_secure") ? HTTP_CATALOG : HTTPS_CATALOG;
            $this->ImageURL = $_obfuscated_0D342E030A1A320A3605110A071F143C2C095B18083922_ . "image/";
        }
        if (isset($this->ExportConfig["csv_delimiter"]) && $this->ExportConfig["csv_delimiter"]) {
            $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ExportConfig["csv_delimiter"]);
        }
        if (isset($this->ExportConfig["csv_text_delimiter"]) && $this->ExportConfig["csv_text_delimiter"]) {
            $this->CSV_ENCLOSURE = trim(htmlspecialchars_decode($this->ExportConfig["csv_text_delimiter"]));
        }
        $this->CountLanguages = $this->getCountLanguages();
        if (empty($this->ExportConfig["product_qty"])) {
            $this->ExportConfig["product_qty"] = NULL;
        }
        if (empty($this->ExportConfig["product_category"])) {
            $this->ExportConfig["product_category"] = NULL;
        }
        if (empty($this->ExportConfig["from_store"])) {
            $this->ExportConfig["from_store"] = NULL;
        }
        if (empty($this->ExportConfig["product_manufacturer"])) {
            $this->ExportConfig["product_manufacturer"] = NULL;
        }
        if (isset($this->ExportConfig["fields_set"]["_OPTIONS_"])) {
            $this->load->model("csvprice_pro/lib_product_option");
            $this->model_csvprice_pro_lib_product_option->setLanguageId($this->LanguageID);
        }
        if (isset($this->ExportConfig["fields_set"]["_ATTRIBUTES_"])) {
            $this->load->model("csvprice_pro/lib_product_attribute");
            $this->model_csvprice_pro_lib_product_attribute->setLanguageId($this->LanguageID);
        }
        if (isset($this->ExportConfig["fields_set"]["_SPECIAL_"])) {
            $this->load->model("csvprice_pro/lib_product_special");
        }
        if (isset($this->ExportConfig["fields_set"]["_DISCOUNT_"])) {
            $this->load->model("csvprice_pro/lib_product_discount");
        }
        $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = "";
        $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_ = [];
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_ = [];
        $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_ = [];
        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_ = [];
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.product_id";
        $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ID_";
        if (isset($this->ExportConfig["fields_set"]["_MAIN_CATEGORY_"])) {
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_MAIN_CATEGORY_";
        }
        if ($this->ExportConfig["export_category"] == 1) {
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_CATEGORY_ID_";
        } else {
            if ($this->ExportConfig["export_category"] == 2) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_CATEGORY_";
            }
        }
        if (isset($this->ExportConfig["fields_set"]["_NAME_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.name";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_NAME_";
        }
        if (isset($this->ExportConfig["fields_set"]["_MODEL_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.model";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_MODEL_";
        }
        if (isset($this->ExportConfig["fields_set"]["_SKU_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.sku";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SKU_";
        }
        if ($this->CoreType["EAN"] && isset($this->ExportConfig["fields_set"]["_EAN_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.ean";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_EAN_";
        }
        if ($this->CoreType["JAN"] && isset($this->ExportConfig["fields_set"]["_JAN_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.jan";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_JAN_";
        }
        if ($this->CoreType["ISBN"] && isset($this->ExportConfig["fields_set"]["_ISBN_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.isbn";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ISBN_";
        }
        if ($this->CoreType["MPN"] && isset($this->ExportConfig["fields_set"]["_MPN_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.mpn";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_MPN_";
        }
        if (isset($this->ExportConfig["fields_set"]["_UPC_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.upc";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_UPC_";
        }
        if (isset($this->ExportConfig["fields_set"]["_MANUFACTURER_"])) {
            $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_[] = " LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON (p.manufacturer_id = m.manufacturer_id) ";
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "m.name AS manufacturer";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_MANUFACTURER_";
        }
        if (isset($this->ExportConfig["fields_set"]["_SHIPPING_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.shipping";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SHIPPING_";
        }
        if (isset($this->ExportConfig["fields_set"]["_LOCATION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.location";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_LOCATION_";
        }
        if (isset($this->ExportConfig["fields_set"]["_PRICE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "TRUNCATE(p.price, 2) AS price";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_PRICE_";
        }
        if (isset($this->ExportConfig["fields_set"]["_POINTS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.points";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_POINTS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_REWARD_POINTS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.points AS reward_points";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_REWARD_POINTS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_QUANTITY_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.quantity";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_QUANTITY_";
        }
        if (isset($this->ExportConfig["fields_set"]["_STOCK_STATUS_ID_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.stock_status_id";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STOCK_STATUS_ID_";
        }
        if (isset($this->ExportConfig["fields_set"]["_STOCK_STATUS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "ss.name AS stock_status_name";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["ss"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["ss"] = " LEFT JOIN `" . DB_PREFIX . "stock_status` ss ON (p.stock_status_id = ss.stock_status_id AND ss.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STOCK_STATUS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_LENGTH_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.length";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_LENGTH_";
        }
        if (isset($this->ExportConfig["fields_set"]["_WIDTH_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.width";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_WIDTH_";
        }
        if (isset($this->ExportConfig["fields_set"]["_HEIGHT_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.height";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_HEIGHT_";
        }
        if (isset($this->ExportConfig["fields_set"]["_WEIGHT_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.weight";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_WEIGHT_";
        }
        if ($this->CoreType["SEO_TITLE"] && isset($this->ExportConfig["fields_set"]["_META_TITLE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.seo_title";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_TITLE_";
        }
        if ($this->CoreType["SEO_H1"] && isset($this->ExportConfig["fields_set"]["_HTML_H1_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.seo_h1";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_HTML_H1_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_KEYWORDS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.meta_keyword";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_KEYWORDS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_DESCRIPTION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.meta_description";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_DESCRIPTION_";
        }
        if (isset($this->ExportConfig["fields_set"]["_DESCRIPTION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.description";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_DESCRIPTION_";
        }
        if ($this->CoreType["PRODUCT_TAG"] && isset($this->ExportConfig["fields_set"]["_PRODUCT_TAG_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd.tag";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_PRODUCT_TAG_";
        }
        if (isset($this->ExportConfig["fields_set"]["_IMAGE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.image";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_IMAGE_";
        }
        if (isset($this->ExportConfig["fields_set"]["_SORT_ORDER_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.sort_order";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SORT_ORDER_";
        }
        if (isset($this->ExportConfig["fields_set"]["_STATUS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p.status";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STATUS_";
        }
        $_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_ = $this->model_csvprice_pro_app_setting->getMacros();
        if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product"])) {
            foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->ExportConfig["fields_set"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "p." . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"];
                    $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"];
                }
            }
        }
        if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product_description"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product_description"])) {
            foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->ExportConfig["fields_set"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "pd." . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"];
                    if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                        $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int) $this->LanguageID . ") ";
                    }
                    $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"];
                }
            }
        }
        if ($this->ExportConfig["product_filter"] == 1) {
            if (!empty($this->ExportConfig["filter_name"])) {
                $this->ExportConfig["filter_name"] = strtolower(str_replace("'", "%", $this->ExportConfig["filter_name"]));
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_name"] = " (LOWER(pd.name) LIKE '%" . $this->ExportConfig["filter_name"] . "%' OR LOWER(pd.name) LIKE '" . $this->db->escape($this->ExportConfig["filter_name"]) . "%') ";
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["pd"] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int) $this->LanguageID . "') ";
                }
            }
            if (!empty($this->ExportConfig["filter_sku"])) {
                $this->ExportConfig["filter_sku"] = strtolower(str_replace("'", "%", $this->ExportConfig["filter_sku"]));
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_sku"] = " (LOWER(p.sku) LIKE '%" . $this->ExportConfig["filter_sku"] . "%' OR LOWER(p.sku) LIKE '" . $this->db->escape($this->ExportConfig["filter_sku"]) . "%') ";
            }
            if (!empty($this->ExportConfig["filter_location"])) {
                $this->ExportConfig["filter_location"] = strtolower(str_replace("'", "%", $this->ExportConfig["filter_location"]));
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_location"] = " (LOWER(p.location) LIKE '%" . $this->ExportConfig["filter_location"] . "%' OR LOWER(p.location) LIKE '" . $this->db->escape($this->ExportConfig["filter_location"]) . "%') ";
            }
            if ((int) $this->ExportConfig["filter_stock_status"] != 0) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_stock_status"] = " p.stock_status_id = " . (int) $this->ExportConfig["filter_stock_status"] . " ";
            }
            if ((int) $this->ExportConfig["filter_status"] != 3) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_status"] = " p.status = " . (int) $this->ExportConfig["filter_status"] . " ";
            }
            if (!empty($this->ExportConfig["filter_price"])) {
                switch ($this->ExportConfig["filter_price_prefix"]) {
                    case "1":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_price"] = " p.price = " . (int) $this->ExportConfig["filter_price"] . " ";
                        break;
                    case "2":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_price"] = " p.price >= " . (int) $this->ExportConfig["filter_price"] . " ";
                        break;
                    case "3":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_price"] = " p.price <= " . (int) $this->ExportConfig["filter_price"] . " ";
                        break;
                    case "4":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_price"] = " p.price <> " . (int) $this->ExportConfig["filter_price"] . " ";
                        break;
                }
            }
            if (!empty($this->ExportConfig["filter_price_start"]) && !empty($this->ExportConfig["filter_price_end"])) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_price_range"] = " (p.price >= " . (int) $this->ExportConfig["filter_price_start"] . " AND p.price <= " . (int) $this->ExportConfig["filter_price_end"] . ") ";
            }
            if (!empty($this->ExportConfig["filter_quantity"])) {
                switch ($this->ExportConfig["filter_quantity_prefix"]) {
                    case "1":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_quantity"] = " p.quantity = " . (int) $this->ExportConfig["filter_quantity"] . " ";
                        break;
                    case "2":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_quantity"] = " p.quantity >= " . (int) $this->ExportConfig["filter_quantity"] . " ";
                        break;
                    case "3":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_quantity"] = " p.quantity <= " . (int) $this->ExportConfig["filter_quantity"] . " ";
                        break;
                    case "4":
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["filter_quantity"] = " p.quantity <> " . (int) $this->ExportConfig["filter_quantity"] . " ";
                        break;
                }
            }
        }
        if (!empty($this->ExportConfig["product_manufacturer"])) {
            $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_ = implode(",", $this->ExportConfig["product_manufacturer"]);
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " (p.manufacturer_id IN (" . $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_ . ")) ";
        }
        if (!empty($this->ExportConfig["from_store"])) {
            $_obfuscated_0D2A5C1112352A131C031B1D19291E2D2F2B043F353C11_ = implode(",", $this->ExportConfig["from_store"]);
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " (p2s.store_id IN (" . $_obfuscated_0D2A5C1112352A131C031B1D19291E2D2F2B043F353C11_ . ")) ";
            $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_[] = " LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p.product_id = p2s.product_id) ";
        }
        if (!empty($this->ExportConfig["product_category"])) {
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " (p2c.category_id IN (" . implode(",", $this->ExportConfig["product_category"]) . ")) ";
            $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_[] = " LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p.product_id = p2c.product_id) ";
        }
        if ((int) $this->ExportConfig["limit_end"] != 0) {
            $_obfuscated_0D331D280A0412391A182340232E2E2812181E14371D32_ = " LIMIT " . (int) $this->ExportConfig["limit_start"] . ", " . (int) $this->ExportConfig["limit_end"];
        } else {
            $_obfuscated_0D331D280A0412391A182340232E2E2812181E14371D32_ = "";
        }
        if (0 < count($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_)) {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = " WHERE " . implode("AND", $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_) . " ";
        } else {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = "";
        }
        $sql = "SELECT DISTINCT " . implode(",", $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_) . " FROM " . DB_PREFIX . "product p " . implode(" ", $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_) . $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ . " " . $_obfuscated_0D331D280A0412391A182340232E2E2812181E14371D32_;
        $query = $this->db->query($sql);
        if (count($query->rows) < 1) {
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = ["error" => "error_export_empty_rows"];
            return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
        }
        $_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_ = $this->model_csvprice_pro_app_setting->getTmpDir();
        if (!$_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_) {
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"] = $this->language->get("error_directory_not_available");
            return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
        }
        ini_set("default_charset", "UTF-8");
        $file = $_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_ . "/" . uniqid() . ".csv";
        if (($this->FileHandle = fopen($file, "w")) !== false) {
            if (isset($this->ExportConfig["fields_set"]["_SEO_KEYWORD_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SEO_KEYWORD_";
            }
            if (isset($this->ExportConfig["fields_set"]["_DISCOUNT_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_DISCOUNT_";
            }
            if (isset($this->ExportConfig["fields_set"]["_SPECIAL_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SPECIAL_";
            }
            if (isset($this->ExportConfig["fields_set"]["_OPTIONS_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_OPTIONS_";
            }
            if (isset($this->ExportConfig["fields_set"]["_FILTERS_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_FILTERS_";
            }
            if (isset($this->ExportConfig["fields_set"]["_ATTRIBUTES_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ATTRIBUTES_";
            }
            if (isset($this->ExportConfig["fields_set"]["_IMAGES_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_IMAGES_";
            }
            if (isset($this->ExportConfig["export_related"]) && !empty($this->ExportConfig["export_related"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = $this->ExportConfig["export_related"];
            }
            if (isset($this->ExportConfig["fields_set"]["_PRODUCT_TAG_"]) && $this->CoreType["PRODUCT_TAG_OLD"]) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_PRODUCT_TAG_";
            }
            if (isset($this->ExportConfig["fields_set"]["_STORE_ID_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STORE_ID_";
            }
            if (isset($this->ExportConfig["fields_set"]["_URL_"])) {
                $this->SeoURL = new Url(HTTP_CATALOG, HTTP_CATALOG);
                if ($this->config->get("config_seo_url") && file_exists(DIR_CATALOG . "controller/common/seo_url.php")) {
                    require_once DIR_CATALOG . "controller/common/seo_url.php";
                    $_obfuscated_0D400A212E060B2E3B08071C151C5B2F10271639330C01_ = new ControllerCommonSeoUrl($this->registry);
                    $this->SeoURL->addRewrite($_obfuscated_0D400A212E060B2E3B08071C151C5B2F10271639330C01_);
                }
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_URL_";
            }
            fputcsv($this->FileHandle, $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
            foreach ($query->rows as $_obfuscated_0D2D1D0A10131210281F025B0134290A5B16212E2A3422_) {
                $this->fputcsvProductData($_obfuscated_0D2D1D0A10131210281F025B0134290A5B16212E2A3422_);
            }
            fclose($this->FileHandle);
            if (($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = file_get_contents($file)) !== false) {
                unlink($file);
                return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
            }
            return "";
        } else {
            return "";
        }
    }
    private function fputcsvProductData($product)
    {
        if ($this->ExportConfig["export_category"] == 1 || $this->ExportConfig["export_category"] == 2) {
            $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_ = $this->getCategory($product["product_id"]);
            $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_ = htmlspecialchars_decode($_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_);
            $product = array_merge(["category" => (string) $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_], $product);
        }
        if (isset($this->ExportConfig["fields_set"]["_MAIN_CATEGORY_"])) {
            $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_ = $this->getMainCategoryNameByProductID($product["product_id"]);
            $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_ = htmlspecialchars_decode($_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_);
            $product = array_merge(["main_category" => (string) $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_], $product);
        }
        if (isset($product["name"])) {
            $product["name"] = htmlspecialchars_decode($product["name"]);
        }
        if (isset($product["sku"])) {
            $product["sku"] = htmlspecialchars_decode($product["sku"]);
        }
        if (isset($product["ean"])) {
            $product["ean"] = htmlspecialchars_decode($product["ean"]);
        }
        if (isset($product["jan"])) {
            $product["jan"] = htmlspecialchars_decode($product["jan"]);
        }
        if (isset($product["isbn"])) {
            $product["isbn"] = htmlspecialchars_decode($product["isbn"]);
        }
        if (isset($product["mpn"])) {
            $product["mpn"] = htmlspecialchars_decode($product["mpn"]);
        }
        if (isset($product["upc"])) {
            $product["upc"] = htmlspecialchars_decode($product["upc"]);
        }
        if (isset($product["location"])) {
            $product["location"] = htmlspecialchars_decode($product["location"]);
        }
        if (isset($product["image"])) {
            $product["image"] = $this->ImageURL . $product["image"];
        }
        if (isset($product["manufacturer"])) {
            $product["manufacturer"] = htmlspecialchars_decode($product["manufacturer"]);
        }
        if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product"])) {
            foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($product[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $product[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]] = htmlspecialchars_decode($product[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]);
                }
            }
        }
        if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product_description"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product_description"])) {
            foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "product_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($product[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $product[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]] = htmlspecialchars_decode($product[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]);
                }
            }
        }
        if (isset($product["reward_points"])) {
            $product["reward_points"] = $this->getProductRewardPoints($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_SEO_KEYWORD_"])) {
            $product["seo_url"] = $this->getProductSeoURL($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_DISCOUNT_"])) {
            $product["discount"] = $this->model_csvprice_pro_lib_product_discount->getProductDiscount($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_SPECIAL_"])) {
            $product["special"] = $this->model_csvprice_pro_lib_product_special->getProductSpecial($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_OPTIONS_"])) {
            $product["options"] = $this->model_csvprice_pro_lib_product_option->getProductOptions($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_ATTRIBUTES_"])) {
            $this->load->model("csvprice_pro/lib_product_attribute");
            $this->model_csvprice_pro_lib_product_attribute->setLanguageId($this->LanguageID);
        }
        if (isset($this->ExportConfig["fields_set"]["_FILTERS_"])) {
            $product["filters"] = $this->getProductFilters($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_ATTRIBUTES_"])) {
            $product["attribute"] = $this->model_csvprice_pro_lib_product_attribute->getProductAttribute($product["product_id"], (int) $this->LanguageID);
        }
        if (isset($this->ExportConfig["fields_set"]["_IMAGES_"])) {
            $product["images"] = $this->getProductImages($product["product_id"]);
        }
        if (isset($this->ExportConfig["export_related"]) && !empty($this->ExportConfig["export_related"])) {
            $product["relation"] = $this->getProductRelated($product["product_id"]);
        }
        if (isset($product["description"]) && $product["description"] != "") {
            $product["description"] = htmlspecialchars_decode($product["description"]);
        }
        if (isset($product["meta_description"]) && $product["meta_description"] != "") {
            $product["meta_description"] = htmlspecialchars_decode($product["meta_description"]);
        }
        if (isset($product["meta_keyword"]) && $product["meta_keyword"] != "") {
            $product["meta_keyword"] = htmlspecialchars_decode($product["meta_keyword"]);
        }
        if (isset($product["seo_title"]) && $product["seo_title"] != "") {
            $product["seo_title"] = htmlspecialchars_decode($product["seo_title"]);
        }
        if (isset($product["seo_h1"]) && $product["seo_h1"] != "") {
            $product["seo_h1"] = htmlspecialchars_decode($product["seo_h1"]);
        }
        if (isset($product["tag"]) && $product["tag"] != "") {
            $product["tag"] = htmlspecialchars_decode($product["tag"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_PRODUCT_TAG_"]) && $this->CoreType["PRODUCT_TAG_OLD"]) {
            $product["tag"] = $this->getProductTag($product["product_id"]);
            $product["tag"] = htmlspecialchars_decode($product["tag"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_STORE_ID_"])) {
            $product["store_id"] = $this->getProductStoresID($product["product_id"]);
        }
        if (isset($this->ExportConfig["fields_set"]["_URL_"])) {
            $product["url"] = str_replace("&amp;", "&", $this->SeoURL->link("product/product", "product_id=" . $product["product_id"]));
            if (strpos($product["url"], "http") === false) {
                $product["url"] = HTTP_CATALOG . $product["url"];
            }
        }
        $_obfuscated_0D380A151213345B1808232B3D0B11062B2A23083B2211_ = $product["product_id"];
        unset($product["product_id"]);
        $product = array_merge(["product_id" => $_obfuscated_0D380A151213345B1808232B3D0B11062B2A23083B2211_], $product);
        @fputcsv($this->FileHandle, $product, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
    }
    private function getProductCatNames($product_id, $category_id = 0)
    {
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
        if ($category_id != 0) {
            $_obfuscated_0D1D0D190C14181105333C1A1F26352D16300D10180B22_ = " AND p2c.category_id = '" . $category_id . "' ";
        } else {
            $_obfuscated_0D1D0D190C14181105333C1A1F26352D16300D10180B22_ = "";
        }
        $query = $this->db->query("SELECT p2c.category_id, cd.name FROM " . DB_PREFIX . "product_to_category p2c \n\t\t\tLEFT JOIN " . DB_PREFIX . "category_description cd ON (p2c.category_id = cd.category_id)\n\t\t\tWHERE p2c.product_id = '" . (int) $product_id . "'" . $_obfuscated_0D1D0D190C14181105333C1A1F26352D16300D10180B22_ . " AND cd.language_id = '" . (int) $this->LanguageID . "' ORDER BY cd.name");
        foreach ($query->rows as $row) {
            if ($this->ExportConfig["export_category"] == 1) {
                $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[] = $row["category_id"];
            } else {
                $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[] = $row["name"];
            }
        }
        if ($this->ExportConfig["export_category"] == 1) {
            return implode(",", $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
        }
        return implode("\n", $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
    }
    private function getCategory($product_id)
    {
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
        if (isset($this->ExportConfig["category_parent"]) && $this->ExportConfig["category_parent"] == 0) {
            return $this->getProductCatNames($product_id);
        }
        $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = 0;
        if ($this->CoreType["MAIN_CATEGORY"]) {
            $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_ = " ORDER BY p2c.main_category ";
        } else {
            $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_ = "";
        }
        $sql = "SELECT CONCAT_WS(','";
        $i = $this->CategoryMaxLevel - 1;
        while (0 <= $i) {
            $sql .= ", t" . $i . ".category_id";
            --$i;
        }
        $sql .= ") AS path FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category t0 ON (t0.category_id = p2c.category_id)";
        for ($i = 1; $i < $this->CategoryMaxLevel; $i++) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "category t" . $i . " ON (t" . $i . ".category_id = t" . ($i - 1) . ".parent_id)";
        }
        $sql .= " WHERE p2c.product_id = '" . (int) $product_id . "' " . $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_;
        $result = $this->db->query($sql);
        if (0 < $result->num_rows) {
            foreach ($result->rows as $_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_) {
                if (!empty($_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"])) {
                    if ($this->ExportConfig["export_category"] == 1) {
                        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[] = $_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"];
                    } else {
                        if (!isset($this->PathNameByCategory[$_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"]])) {
                            $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = $this->getPathNameByCategory($_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"]);
                            if ($_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_) {
                                $this->PathNameByCategory[$_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"]] = $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_;
                                $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[] = $this->PathNameByCategory[$_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"]];
                            }
                        } else {
                            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[] = $this->PathNameByCategory[$_obfuscated_0D3304391D1C12080B1B271B2C1C0A0D35091109315B22_["path"]];
                        }
                    }
                }
            }
        }
        if (empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
            return "";
        }
        $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = implode($this->ExportConfig["export_category"] == 1 ? "," : "\n", $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
        if ($this->ExportConfig["export_category"] == 1) {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode(",", $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_);
            $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = array_pop($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = array_unique($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[] = $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_;
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = implode(",", $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
        }
        return $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_;
    }
    private function getMainCategoryNameByProductID($product_id)
    {
        $sql = "SELECT category_id FROM " . DB_PREFIX . "product_to_category p2c WHERE p2c.main_category = 1 AND p2c.product_id = '" . (int) $product_id . "' LIMIT 1";
        $result = $this->db->query($sql);
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $result->row["category_id"];
            if (isset($this->ExportConfig["category_parent"]) && $this->ExportConfig["category_parent"] == 0) {
                return $this->getProductCatNames($product_id, $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_);
            }
            $sql = "SELECT CONCAT_WS(','";
            $i = $this->CategoryMaxLevel - 1;
            while (0 <= $i) {
                $sql .= ", t" . $i . ".category_id";
                --$i;
            }
            $sql .= ") AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category t0 ON (t0.category_id = c.category_id)";
            for ($i = 1; $i < $this->CategoryMaxLevel; $i++) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "category t" . $i . " ON (t" . $i . ".category_id = t" . ($i - 1) . ".parent_id)";
            }
            $sql .= " WHERE c.category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "' LIMIT 1";
            $result = $this->db->query($sql);
            if (0 < $result->num_rows && isset($result->row["path"])) {
                $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = $this->getPathNameByCategory($result->row["path"]);
                return $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_;
            }
            return "";
        }
        return "";
    }
    private function getPathNameByCategory($path)
    {
        $_obfuscated_0D342B1C330631135B03340C310B2F3203252B5B092E01_ = [];
        $result = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id IN (" . $path . ") AND language_id = '" . (int) $this->LanguageID . "' ORDER BY Field(category_id, " . $path . ")");
        if (0 < $result->num_rows) {
            foreach ($result->rows as $_obfuscated_0D2336212C2B5C38341814110E1B2302263C5C07052C11_) {
                $_obfuscated_0D342B1C330631135B03340C310B2F3203252B5B092E01_[] = $_obfuscated_0D2336212C2B5C38341814110E1B2302263C5C07052C11_["name"];
            }
        }
        if (empty($_obfuscated_0D342B1C330631135B03340C310B2F3203252B5B092E01_)) {
            return false;
        }
        return implode($this->ExportConfig["delimiter_category"], $_obfuscated_0D342B1C330631135B03340C310B2F3203252B5B092E01_);
    }
    private function getProductImages($product_id)
    {
        $_obfuscated_0D1107400E0D18151C243C2C0406251E162A2F262E1B11_ = [];
        $query = $this->db->query("SELECT pi.image FROM `" . DB_PREFIX . "product_image` pi WHERE pi.product_id = " . (int) $product_id . " ORDER BY `sort_order`;");
        foreach ($query->rows as $result) {
            $_obfuscated_0D1107400E0D18151C243C2C0406251E162A2F262E1B11_[] = $this->ImageURL . $result["image"];
        }
        return implode(",", $_obfuscated_0D1107400E0D18151C243C2C0406251E162A2F262E1B11_);
    }
    private function getProductSeoURL($product_id)
    {
        $result = $this->db->query("SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` ua WHERE ua.query = '" . "product_id=" . $product_id . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["keyword"];
        }
        return "";
    }
    private function getProductRewardPoints($product_id)
    {
        $_obfuscated_0D2F011033051107402C5B263D2D2526281E3516171E32_ = [];
        if ($this->CoreType["CUSTOMER_DESC"]) {
            $sql = "SELECT cgd.name, pr.points FROM `" . DB_PREFIX . "product_reward` pr LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (pr.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . $this->LanguageID . "') WHERE pr.product_id = '" . (int) $product_id . "'";
        } else {
            $sql = "SELECT cg.name, pr.points FROM `" . DB_PREFIX . "product_reward` pr LEFT JOIN `" . DB_PREFIX . "customer_group` cg ON (pr.customer_group_id = cg.customer_group_id) WHERE pr.product_id = '" . (int) $product_id . "'";
        }
        $_obfuscated_0D5B0E393E272418070F182B211F3D060D1B21230E3511_ = $this->db->query($sql);
        if ($_obfuscated_0D5B0E393E272418070F182B211F3D060D1B21230E3511_->num_rows == 0) {
            return "";
        }
        foreach ($_obfuscated_0D5B0E393E272418070F182B211F3D060D1B21230E3511_->rows as $_obfuscated_0D5C0C29292A0E3B17165B262F2A1C3110181119262B32_) {
            $_obfuscated_0D2F011033051107402C5B263D2D2526281E3516171E32_[] = $_obfuscated_0D5C0C29292A0E3B17165B262F2A1C3110181119262B32_["name"] . ":" . $_obfuscated_0D5C0C29292A0E3B17165B262F2A1C3110181119262B32_["points"];
        }
        if (0 < count($_obfuscated_0D2F011033051107402C5B263D2D2526281E3516171E32_)) {
            return implode(",", $_obfuscated_0D2F011033051107402C5B263D2D2526281E3516171E32_);
        }
        return "";
    }
    private function getProductAttribute($product_id)
    {
        $_obfuscated_0D19053C1D402F25281D0C0A1C132A270A2A1E34303322_ = [];
        if (1 < $this->CountLanguages) {
            $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_ = ", patt.attribute_id";
        } else {
            $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_ = "";
        }
        $sql = "SELECT CONCAT_WS('|'" . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_ . ", attgd.name, attd.name, patt.text) AS p_attribute FROM `" . DB_PREFIX . "product_attribute` patt\n\t\t  \tLEFT JOIN `" . DB_PREFIX . "attribute_description` attd ON (attd.attribute_id = patt.attribute_id)\n\t\t  \tLEFT JOIN `" . DB_PREFIX . "attribute` att ON (attd.attribute_id = att.attribute_id) \n\t\t  \tLEFT JOIN `" . DB_PREFIX . "attribute_group_description` attgd ON (attgd.attribute_group_id = att.attribute_group_id)\n\t\t  \tWHERE patt.product_id = " . (int) $product_id . " AND attgd.language_id = '" . (int) $this->LanguageID . "' AND attd.language_id = '" . (int) $this->LanguageID . "' AND patt.language_id = '" . (int) $this->LanguageID . "'";
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $_obfuscated_0D19053C1D402F25281D0C0A1C132A270A2A1E34303322_[] = htmlspecialchars_decode($result["p_attribute"]);
        }
        return implode("\n", $_obfuscated_0D19053C1D402F25281D0C0A1C132A270A2A1E34303322_);
    }
    private function getProductRelated($product_id)
    {
        if ($this->ProductRelatedSQL == "") {
            switch ($this->ExportConfig["export_related"]) {
                case "_RELATED_":
                case "_RELATED_ID_":
                    $this->ProductRelatedSQL = "SELECT related_id AS product_related FROM `" . DB_PREFIX . "product_related` WHERE product_id = '%s'";
                    break;
                case "_RELATED_MODEL_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT p.model AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.related_id = p.product_id) WHERE pr.product_id = '%s'";
                    break;
                case "_RELATED_SKU_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT p.sku AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.related_id = p.product_id) WHERE pr.product_id = '%s'";
                    break;
                case "_RELATED_EAN_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT p.ean AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.related_id = p.product_id) WHERE pr.product_id = '%s'";
                    break;
                case "_RELATED_JAN_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT p.jan AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.related_id = p.product_id) WHERE pr.product_id = '%s'";
                    break;
                case "_RELATED_ISBN_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT p.isbn AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.related_id = p.product_id) WHERE pr.product_id = '%s'";
                    break;
                case "_RELATED_MPN_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT p.mpn AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.related_id = p.product_id) WHERE pr.product_id = '%s'";
                    break;
                case "_RELATED_NAME_":
                    $this->ProductRelatedSQL = "SELECT DISTINCT pd.name AS product_related FROM `" . DB_PREFIX . "product_related` pr LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pr.related_id = pd.product_id AND pd.language_id = '" . (int) $this->LanguageID . "') WHERE pr.product_id = '%s'";
                    break;
            }
        }
        $_obfuscated_0D1E3710211D3D07080812122A1B032B1E23013D392B22_ = "";
        if (!$this->ProductRelatedSQL) {
            return "";
        }
        $sql = sprintf($this->ProductRelatedSQL, $product_id);
        $result = $this->db->query($sql);
        if (0 < $result->num_rows) {
            foreach ($result->rows as $row) {
                $_obfuscated_0D1E3710211D3D07080812122A1B032B1E23013D392B22_ .= "," . htmlspecialchars_decode($row["product_related"]);
            }
        }
        return trim($_obfuscated_0D1E3710211D3D07080812122A1B032B1E23013D392B22_, ",");
    }
    private function getProductTag($product_id)
    {
        $_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ = [];
        $query = $this->db->query("SELECT tag FROM `" . DB_PREFIX . "product_tag` WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'");
        foreach ($query->rows as $result) {
            $_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_[] = $result["tag"];
        }
        return implode(",", $_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_);
    }
    private function getProductFilters($product_id)
    {
        $_obfuscated_0D05352C0E041A5B0B023B3E062A3D04103732391A5B01_ = [];
        $sql = "SELECT CONCAT(fgd.name, '|', fd.name) AS p_filters\n\t\t\tFROM `" . DB_PREFIX . "product_filter` pf\n\t\t\tLEFT JOIN `" . DB_PREFIX . "filter_description` fd ON (pf.filter_id = fd.filter_id AND fd.language_id = '" . (int) $this->LanguageID . "')\n\t\t\tLEFT JOIN `" . DB_PREFIX . "filter_group_description` fgd ON (fd.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int) $this->LanguageID . "')\n\t\t  \tWHERE pf.product_id = " . (int) $product_id;
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $_obfuscated_0D05352C0E041A5B0B023B3E062A3D04103732391A5B01_[] = $result["p_filters"];
        }
        return implode("\n", $_obfuscated_0D05352C0E041A5B0B023B3E062A3D04103732391A5B01_);
    }
    private function deleteProduct($product_id)
    {
        $this->model_catalog_product->deleteProduct($product_id);
    }
    public function ProductImport($data_a, $profile_id = 0)
    {
        $this->load->model("csvprice_pro/app_setting");
        if ($profile_id) {
            $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_ = $this->getProfile($profile_id);
            $this->ImportConfig = $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_import"];
            $this->ImportSetting = $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_setting"];
            unset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_);
            $this->ImportConfig["iter_limit"] = 0;
            $data_a["ftell"] = 0;
        } else {
            $this->ImportConfig = $this->model_csvprice_pro_app_setting->getSetting("ProductImport");
            $this->ImportSetting = $this->model_csvprice_pro_app_setting->getSetting("ProductSetting");
            if (!isset($data_a["ftell"])) {
                $data_a["ftell"] = 0;
            }
        }
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        if (isset($this->ImportConfig["img_path"])) {
            $this->ImportConfig["img_path"] = trim($this->ImportConfig["img_path"]);
        } else {
            $this->ImportConfig["img_path"] = "";
        }
        $this->ImportSetting["language_id"] = $this->ImportConfig["language_id"];
        $this->LanguageID = $this->ImportSetting["language_id"];
        if (isset($this->ImportConfig["csv_delimiter"]) && $this->ImportConfig["csv_delimiter"]) {
            $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ImportConfig["csv_delimiter"]);
        }
        if (isset($this->ImportConfig["csv_text_delimiter"]) && $this->ImportConfig["csv_text_delimiter"]) {
            $this->CSV_ENCLOSURE = trim(htmlspecialchars_decode($this->ImportConfig["csv_text_delimiter"]));
        }
        if ($this->ImportConfig["exclude_filter"]) {
            $this->ImportConfig["exclude_filter_name"] = trim(htmlspecialchars_decode($this->ImportConfig["exclude_filter_name"]), " \n\r\t\r");
            $this->ImportConfig["exclude_filter_desc"] = trim(htmlspecialchars_decode($this->ImportConfig["exclude_filter_desc"]), " \n\r\t\r");
            $this->ImportConfig["exclude_filter_attr"] = trim(htmlspecialchars_decode($this->ImportConfig["exclude_filter_attr"]), " \n\r\t\r");
        }
        $this->CustomFields = $this->model_csvprice_pro_app_setting->getMacros("ProductMacros");
        if (($this->FieldCaption = $this->getFieldCaption($data_a["file_name"])) === NULL) {
            $result["error_import"] = $this->language->get("error_import_field_caption");
            return $result;
        }
        $getProductID = "getProductID";
        if ($this->ImportConfig["key_field"] == "_ID_" && isset($this->FieldCaption["_ID_"])) {
            $getProductID = "getProductID_Id";
        } else {
            if ($this->ImportConfig["key_field"] == "_SKU_" && isset($this->FieldCaption["_SKU_"])) {
                $getProductID = "getProductID_SKU";
            } else {
                if ($this->ImportConfig["key_field"] == "_EAN_" && isset($this->FieldCaption["_EAN_"])) {
                    $getProductID = "getProductID_EAN";
                } else {
                    if ($this->ImportConfig["key_field"] == "_JAN_" && isset($this->FieldCaption["_JAN_"])) {
                        $getProductID = "getProductID_JAN";
                    } else {
                        if ($this->ImportConfig["key_field"] == "_ISBN_" && isset($this->FieldCaption["_ISBN_"])) {
                            $getProductID = "getProductID_ISBN";
                        } else {
                            if ($this->ImportConfig["key_field"] == "_MPN_" && isset($this->FieldCaption["_MPN_"])) {
                                $getProductID = "getProductID_MPN";
                            } else {
                                if ($this->ImportConfig["key_field"] == "_MODEL_" && isset($this->FieldCaption["_MODEL_"])) {
                                    $getProductID = "getProductID_Model";
                                } else {
                                    if ($this->ImportConfig["key_field"] == "_NAME_" && isset($this->FieldCaption["_NAME_"])) {
                                        $getProductID = "getProductID_Name";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (isset($this->FieldCaption["_RELATED_ID_"])) {
            $this->RelatedCaption = "_RELATED_ID_";
        } else {
            if (isset($this->FieldCaption["_RELATED_MODEL_"])) {
                $this->RelatedCaption = "_RELATED_MODEL_";
            } else {
                if (isset($this->FieldCaption["_RELATED_SKU_"])) {
                    $this->RelatedCaption = "_RELATED_SKU_";
                } else {
                    if (isset($this->FieldCaption["_RELATED_EAN_"])) {
                        $this->RelatedCaption = "_RELATED_EAN_";
                    } else {
                        if (isset($this->FieldCaption["_RELATED_JAN_"])) {
                            $this->RelatedCaption = "_RELATED_JAN_";
                        } else {
                            if (isset($this->FieldCaption["_RELATED_ISBN_"])) {
                                $this->RelatedCaption = "_RELATED_ISBN_";
                            } else {
                                if (isset($this->FieldCaption["_RELATED_MPN_"])) {
                                    $this->RelatedCaption = "_RELATED_MPN_";
                                } else {
                                    if (isset($this->FieldCaption["_RELATED_NAME_"])) {
                                        $this->RelatedCaption = "_RELATED_NAME_";
                                    } else {
                                        $this->RelatedCaption = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (isset($this->FieldCaption["_FILTERS_"])) {
            $this->load->model("csvprice_pro/lib_product_filter");
        }
        if (isset($this->FieldCaption["_OPTIONS_"])) {
            $this->load->model("csvprice_pro/lib_product_option");
            $this->model_csvprice_pro_lib_product_option->setLanguageId($this->LanguageID);
            $this->model_csvprice_pro_lib_product_option->setProfileSettings($this->ImportSetting);
        }
        if (isset($this->FieldCaption["_ATTRIBUTES_"])) {
            $this->load->model("csvprice_pro/lib_product_attribute");
            $this->model_csvprice_pro_lib_product_attribute->setLanguageId($this->LanguageID);
        }
        if (isset($this->FieldCaption["_SPECIAL_"])) {
            $this->load->model("csvprice_pro/lib_product_special");
        }
        if (isset($this->FieldCaption["_DISCOUNT_"])) {
            $this->load->model("csvprice_pro/lib_product_discount");
        }
        if (isset($this->FieldCaption["_STOCK_STATUS_"])) {
            $result = $this->db->query("SELECT stock_status_id, name FROM `" . DB_PREFIX . "stock_status` WHERE  `language_id` = " . (int) $this->LanguageID);
            if (0 < $result->num_rows) {
                foreach ($result->rows as $row) {
                    $this->StockStatus[$row["name"]] = $row["stock_status_id"];
                }
            }
            unset($result);
        }
        if (0 < $this->ImportConfig["iter_limit"] && $data_a["ftell"] != 0) {
            $this->FileTell = $data_a["ftell"];
        }
        if (isset($this->ImportConfig["product_disable"]) && $this->ImportConfig["product_disable"] == 1 && $data_a["ftell"] == 0) {
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET status = 0");
        }
        if (isset($this->ImportConfig["quantity_reset"]) && $this->ImportConfig["quantity_reset"] == 1 && $data_a["ftell"] == 0) {
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = 0");
        }
        $count = ["total" => 0, "update" => 0, "insert" => 0, "delete" => 0, "error" => 0];
        $result = [];
        if ($this->ImportConfig["mode"] == 10) {
            $this->load->model("catalog/product");
        }
        if (($this->FileHandle = fopen($data_a["file_name"], "r")) !== false) {
            fseek($this->FileHandle, $this->FileTell);
            while (($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_ = fgetcsv($this->FileHandle, 0, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== false) {
                if (substr($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0], 0, 3) == pack("CCC", 239, 187, 191)) {
                    $_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0] = substr($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0], 3);
                }
                $count["total"]++;
                if (count($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_) == count($this->FieldCaption)) {
                    if ($this->ImportConfig["mode"] == 4 && !$this->{$getProductID}($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                        if ($this->addProduct($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                            $count["insert"]++;
                        } else {
                            $count["error"]++;
                        }
                    } else {
                        if ($this->ImportConfig["mode"] != 3 && ($product_id = $this->{$getProductID}($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) !== false) {
                            if ($this->ImportConfig["mode"] == 10) {
                                $this->deleteProduct($product_id);
                                $count["delete"]++;
                            } else {
                                if ($this->ImportConfig["mode"] == 1 || $this->ImportConfig["mode"] == 2) {
                                    if ($this->updateProduct($product_id, $_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                                        $count["update"]++;
                                    } else {
                                        $count["error"]++;
                                    }
                                }
                            }
                        } else {
                            if ($this->ImportConfig["mode"] == 1 || $this->ImportConfig["mode"] == 3) {
                                if ($this->addProduct($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                                    $count["insert"]++;
                                } else {
                                    $count["error"]++;
                                }
                            } else {
                                $count["error"]++;
                            }
                        }
                    }
                } else {
                    $count["error"]++;
                }
                if ($count["total"] == $this->ImportConfig["iter_limit"] && 0 < $this->ImportConfig["iter_limit"]) {
                    $result["ftell"] = ftell($this->FileHandle);
                }
            }
            fclose($this->FileHandle);
            $result["total"] = $count["total"];
            $result["update"] = $count["update"];
            $result["insert"] = $count["insert"];
            $result["delete"] = $count["delete"];
            $result["error"] = $count["error"];
            if (isset($this->FieldCaption["_CATEGORY_"]) || isset($this->FieldCaption["_MAIN_CATEGORY_"])) {
                $this->cache->delete("category");
                if ($this->CoreType["REPAIR_CATEGORIES"]) {
                    $this->load->model("catalog/category");
                    $this->model_catalog_category->repairCategories();
                }
            }
        }
        return $result;
    }
    private function addProduct(&$data_a)
    {
        if (!isset($this->FieldCaption["_NAME_"]) || trim($this->FieldCaption["_NAME_"]) == "") {
            return false;
        }
        if ((isset($this->FieldCaption["_IMAGE_"]) || isset($this->FieldCaption["_IMAGES_"])) && $this->ImportConfig["image_download"]) {
            $this->ImagePath = $this->model_csvprice_pro_app_setting->getRandDir();
        }
        $sql = "";
        if (isset($this->FieldCaption["_ID_"]) && (int) $this->ImportConfig["import_id"] == 1 && !$this->getProductID_Id($data_a)) {
            $id = (int) trim($data_a[$this->FieldCaption["_ID_"]]);
            if (!empty($id)) {
                $sql .= " `product_id` = '" . $this->db->escape($id) . "',";
            }
        }
        if (isset($this->FieldCaption["_MODEL_"])) {
            $sql .= " `model` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_MODEL_"]])) . "',";
        }
        if (isset($this->FieldCaption["_SKU_"])) {
            $sql .= " `sku` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_SKU_"]])) . "',";
        }
        if ($this->CoreType["EAN"] && isset($this->FieldCaption["_EAN_"])) {
            $sql .= " `ean` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_EAN_"]])) . "',";
        }
        if ($this->CoreType["JAN"] && isset($this->FieldCaption["_JAN_"])) {
            $sql .= " `jan` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_JAN_"]])) . "',";
        }
        if ($this->CoreType["ISBN"] && isset($this->FieldCaption["_ISBN_"])) {
            $sql .= " `isbn` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_ISBN_"]])) . "',";
        }
        if ($this->CoreType["MPN"] && isset($this->FieldCaption["_MPN_"])) {
            $sql .= " `mpn` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_MPN_"]])) . "',";
        }
        if (isset($this->FieldCaption["_UPC_"])) {
            $sql .= " upc = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_UPC_"]])) . "',";
        }
        if (isset($this->FieldCaption["_LOCATION_"])) {
            $sql .= " location = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_LOCATION_"]])) . "',";
        }
        if (isset($this->FieldCaption["_QUANTITY_"])) {
            $sql .= " quantity = '" . (int) $data_a[$this->FieldCaption["_QUANTITY_"]] . "',";
        }
        if (isset($this->FieldCaption["_STOCK_STATUS_ID_"])) {
            $sql .= " stock_status_id = '" . (int) $data_a[$this->FieldCaption["_STOCK_STATUS_ID_"]] . "',";
        } else {
            if (isset($this->FieldCaption["_STOCK_STATUS_"])) {
                $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ = $this->addStockStatus($data_a[$this->FieldCaption["_STOCK_STATUS_"]]);
                $sql .= " stock_status_id = '" . (int) $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ . "',";
            }
        }
        if (isset($this->FieldCaption["_SHIPPING_"])) {
            $sql .= " shipping = '" . $this->db->escape($data_a[$this->FieldCaption["_SHIPPING_"]]) . "',";
        }
        if (isset($this->FieldCaption["_PRICE_"])) {
            $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $this->validateNumberFloat($data_a[$this->FieldCaption["_PRICE_"]]);
            switch ((int) $this->ImportConfig["calc_mode"]) {
                case 0:
                case 1:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ * (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ * (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 2:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ / (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ / (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 3:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ + (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ + (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 4:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ - (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ - (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                default:
                    $sql .= " price = '" . number_format((double) $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_, 4, ".", "") . "',";
            }
        }
        if (isset($this->FieldCaption["_SORT_ORDER_"])) {
            $sql .= " sort_order = '" . (int) $data_a[$this->FieldCaption["_SORT_ORDER_"]] . "',";
        } else {
            $sql .= " sort_order = '" . (int) $this->ImportSetting["sort_order"] . "',";
        }
        if (isset($this->FieldCaption["_POINTS_"])) {
            $sql .= " points = '" . (int) $data_a[$this->FieldCaption["_POINTS_"]] . "',";
        }
        if (isset($this->FieldCaption["_WEIGHT_"])) {
            $sql .= " weight = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_WEIGHT_"]]) . "',";
        }
        if (isset($this->FieldCaption["_LENGTH_"])) {
            $sql .= " length = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_LENGTH_"]]) . "',";
        }
        if (isset($this->FieldCaption["_WIDTH_"])) {
            $sql .= " width = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_WIDTH_"]]) . "',";
        }
        if (isset($this->FieldCaption["_HEIGHT_"])) {
            $sql .= " height = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_HEIGHT_"]]) . "',";
        }
        if (isset($this->FieldCaption["_STATUS_"])) {
            $sql .= " status = '" . (int) $data_a[$this->FieldCaption["_STATUS_"]] . "',";
        } else {
            $sql .= " status = '" . (int) $this->ImportSetting["status"] . "',";
        }
        $sql .= " tax_class_id = '" . (int) $this->ImportSetting["tax_class_id"] . "',";
        $sql .= " subtract = '" . (int) $this->ImportSetting["subtract"] . "',";
        $_obfuscated_0D11090114145C090A162A163D01253B0D1D3E0D100732_ = true;
        if (!isset($this->FieldCaption["_STOCK_STATUS_ID_"]) && !isset($this->FieldCaption["_STOCK_STATUS_"])) {
            $sql .= " stock_status_id = '" . (int) $this->ImportSetting["stock_status_id"] . "',";
        }
        if (!isset($this->FieldCaption["_SHIPPING_"])) {
            $sql .= " shipping = '" . (int) $this->ImportSetting["shipping"] . "',";
        }
        $sql .= " weight_class_id = '" . (int) $this->ImportSetting["weight_class_id"] . "',";
        $sql .= " length_class_id = '" . (int) $this->ImportSetting["length_class_id"] . "',";
        $sql .= " date_added = NOW(),";
        $sql .= " date_available = DATE_FORMAT(NOW(),'%Y-%m-%d'),";
        if (isset($this->CustomFields[DB_PREFIX . "product"]) && 0 < count($this->CustomFields[DB_PREFIX . "product"])) {
            foreach ($this->CustomFields[DB_PREFIX . "product"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if ($_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] == "minimum") {
                    $_obfuscated_0D11090114145C090A162A163D01253B0D1D3E0D100732_ = false;
                }
                if (isset($this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if ($_obfuscated_0D11090114145C090A162A163D01253B0D1D3E0D100732_) {
            $sql .= " minimum = '" . (int) $this->ImportSetting["minimum"] . "',";
        }
        if (isset($this->FieldCaption["_MANUFACTURER_"])) {
            $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ = $this->getManufacturer($data_a[$this->FieldCaption["_MANUFACTURER_"]]);
            $sql .= " manufacturer_id = '" . (int) $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ . "',";
        } else {
            $sql .= " manufacturer_id = '" . (int) $this->ImportConfig["product_manufacturer"] . "',";
        }
        if (!empty($sql)) {
            $sql = "INSERT INTO `" . DB_PREFIX . "product` SET " . mb_substr($sql, 0, -1) . ";";
            $this->db->query($sql);
        }
        $product_id = $this->db->getLastId();
        if (!$product_id) {
            return false;
        }
        if (isset($this->FieldCaption["_IMAGE_"])) {
            if ($this->ImportConfig["image_download"] && !empty($data_a[$this->FieldCaption["_IMAGE_"]])) {
                $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->FieldCaption["_IMAGE_"]], $this->ImagePath, $product_id);
            } else {
                if (!empty($this->ImportConfig["img_path"]) && !empty($data_a[$this->FieldCaption["_IMAGE_"]])) {
                    $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $this->ImportConfig["img_path"] . $data_a[$this->FieldCaption["_IMAGE_"]];
                } else {
                    $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $data_a[$this->FieldCaption["_IMAGE_"]];
                }
            }
            $this->updateProductImages($product_id, "", $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_);
        }
        if (isset($this->FieldCaption["_STORE_ID_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
            $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ = explode(",", $data_a[$this->FieldCaption["_STORE_ID_"]]);
            foreach ($_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
            }
        } else {
            if (isset($this->ImportConfig["to_store"])) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
                foreach ($this->ImportConfig["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                }
            } else {
                if (isset($this->ImportSetting["to_store"])) {
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
                    foreach ($this->ImportSetting["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                    }
                }
            }
        }
        if (isset($this->FieldCaption["_REWARD_POINTS_"])) {
            $this->updateProductRewardPoints($product_id, $data_a[$this->FieldCaption["_REWARD_POINTS_"]]);
        }
        if (isset($this->FieldCaption["_DISCOUNT_"])) {
            $this->model_csvprice_pro_lib_product_discount->addProductDiscount($product_id, $data_a[$this->FieldCaption["_DISCOUNT_"]]);
        }
        if (isset($this->FieldCaption["_SPECIAL_"])) {
            $this->model_csvprice_pro_lib_product_special->addProductSpecial($product_id, $data_a[$this->FieldCaption["_SPECIAL_"]]);
        }
        $sql = "";
        if (isset($this->FieldCaption["_NAME_"])) {
            $sql .= " name = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_NAME_"]])) . "',";
        }
        if ($this->CoreType["SEO_TITLE"] && isset($this->FieldCaption["_META_TITLE_"])) {
            $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_TITLE_"]])) . "',";
        }
        if ($this->CoreType["SEO_H1"] && isset($this->FieldCaption["_HTML_H1_"])) {
            $sql .= " seo_h1 = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_HTML_H1_"]])) . "',";
        }
        if (isset($this->FieldCaption["_META_KEYWORDS_"])) {
            $sql .= " meta_keyword = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_KEYWORDS_"]])) . "',";
        }
        if (isset($this->FieldCaption["_META_DESCRIPTION_"])) {
            $sql .= " meta_description = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_DESCRIPTION_"]])) . "',";
        }
        if (isset($this->FieldCaption["_DESCRIPTION_"])) {
            $sql .= " description = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_DESCRIPTION_"]])) . "',";
        }
        if ($this->CoreType["PRODUCT_TAG"] && isset($this->FieldCaption["_PRODUCT_TAG_"])) {
            $sql .= " tag = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "product_description"]) && 0 < count($this->CustomFields[DB_PREFIX . "product_description"])) {
            foreach ($this->CustomFields[DB_PREFIX . "product_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $sql = "INSERT INTO `" . DB_PREFIX . "product_description` SET " . $sql . " product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->LanguageID . "'";
            $this->db->query($sql);
        }
        if ($this->CoreType["PRODUCT_TAG_OLD"] && isset($this->FieldCaption["_PRODUCT_TAG_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_tag` WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'");
            if (!empty($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) {
                $_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ = explode(",", $data_a[$this->FieldCaption["_PRODUCT_TAG_"]]);
                foreach ($_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ as $_obfuscated_0D38132323011D131804250B2E190530210E0E30352701_) {
                    $sql = "INSERT INTO `" . DB_PREFIX . "product_tag` SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->LanguageID . "', tag = '" . $this->db->escape(htmlspecialchars(trim($_obfuscated_0D38132323011D131804250B2E190530210E0E30352701_))) . "'";
                    $this->db->query($sql);
                }
            }
        }
        $sql = "";
        if (isset($this->FieldCaption["_SEO_KEYWORD_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'product_id=" . (int) $product_id . "'");
            if (!empty($data_a[$this->FieldCaption["_SEO_KEYWORD_"]])) {
                $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($data_a[$this->FieldCaption["_SEO_KEYWORD_"]]) . "'";
                $this->db->query($sql);
            }
        }
        if (isset($this->FieldCaption["_IMAGES_"])) {
            $this->updateProductImages($product_id, $data_a[$this->FieldCaption["_IMAGES_"]]);
        }
        $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = 0;
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
        if (isset($this->FieldCaption["_CATEGORY_ID_"]) && !empty($data_a[$this->FieldCaption["_CATEGORY_ID_"]])) {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode(",", $data_a[$this->FieldCaption["_CATEGORY_ID_"]]);
        } else {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $this->ImportConfig["product_category"];
        }
        if ($this->CoreType["MAIN_CATEGORY"]) {
            $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $this->ImportConfig["main_category_id"];
        }
        if (isset($this->FieldCaption["_CATEGORY_"]) && !empty($data_a[$this->FieldCaption["_CATEGORY_"]])) {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $this->addProductCategory($data_a[$this->FieldCaption["_CATEGORY_"]]);
            $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = 0;
        }
        if (isset($this->FieldCaption["_MAIN_CATEGORY_"]) && !empty($data_a[$this->FieldCaption["_MAIN_CATEGORY_"]])) {
            $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_ = $this->addProductCategory($data_a[$this->FieldCaption["_MAIN_CATEGORY_"]]);
            if (!empty($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_)) {
                $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = end($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
                if (isset($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_) && !empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
                    $_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_ = array_merge($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_, $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
                    $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = array_unique($_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_);
                } else {
                    $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_;
                }
                unset($_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_);
            }
            unset($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
        }
        if (!empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "'");
            foreach ($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ as $category_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
            }
            if ($_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ == 0) {
                $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $category_id;
            }
        }
        if ($this->CoreType["MAIN_CATEGORY"] && $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ != 0) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE (product_id = '" . (int) $product_id . "' AND category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "') OR (product_id = '" . (int) $product_id . "' AND main_category = 1)");
            $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "', main_category = 1");
        }
        if (isset($this->FieldCaption["_OPTIONS_"])) {
            $this->model_csvprice_pro_lib_product_option->addProductOptions($product_id, $data_a[$this->FieldCaption["_OPTIONS_"]]);
        }
        if (isset($this->FieldCaption["_FILTERS_"])) {
            $this->model_csvprice_pro_lib_product_filter->addProductFilters($product_id, $data_a[$this->FieldCaption["_FILTERS_"]], $this->LanguageID);
        }
        if (isset($this->FieldCaption["_ATTRIBUTES_"])) {
            $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = [];
            $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = explode("\n", $data_a[$this->FieldCaption["_ATTRIBUTES_"]]);
            if (!empty($_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_)) {
                $this->model_csvprice_pro_lib_product_attribute->updateProductAttribute($product_id, $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_);
            }
        }
        if ($this->RelatedCaption) {
            $this->addProductRelated($product_id, $data_a[$this->FieldCaption[$this->RelatedCaption]]);
        }
        return true;
    }
    private function updateProduct(&$product_id, &$data_a)
    {
        if ($this->ImportConfig["empty_field"]) {
            return $this->updateProductEmptyField($product_id, $data_a);
        }
        if ((isset($this->FieldCaption["_IMAGE_"]) || isset($this->FieldCaption["_IMAGES_"])) && $this->ImportConfig["image_download"]) {
            $this->ImagePath = $this->model_csvprice_pro_app_setting->getRandDir();
        }
        $sql = "";
        if (isset($this->FieldCaption["_MODEL_"])) {
            $sql .= " `model` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_MODEL_"]])) . "',";
        }
        if (isset($this->FieldCaption["_SKU_"])) {
            $sql .= " `sku` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_SKU_"]])) . "',";
        }
        if ($this->CoreType["EAN"] && isset($this->FieldCaption["_EAN_"])) {
            $sql .= " `ean` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_EAN_"]])) . "',";
        }
        if ($this->CoreType["JAN"] && isset($this->FieldCaption["_JAN_"])) {
            $sql .= " `jan` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_JAN_"]])) . "',";
        }
        if ($this->CoreType["ISBN"] && isset($this->FieldCaption["_ISBN_"])) {
            $sql .= " `isbn` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_ISBN_"]])) . "',";
        }
        if ($this->CoreType["MPN"] && isset($this->FieldCaption["_MPN_"])) {
            $sql .= " `mpn` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_MPN_"]])) . "',";
        }
        if (isset($this->FieldCaption["_UPC_"])) {
            $sql .= " upc = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_UPC_"]])) . "',";
        }
        if (isset($this->FieldCaption["_LOCATION_"])) {
            $sql .= " location = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_LOCATION_"]])) . "',";
        }
        if (isset($this->FieldCaption["_QUANTITY_"])) {
            $sql .= " quantity = '" . (int) $data_a[$this->FieldCaption["_QUANTITY_"]] . "',";
        }
        if (isset($this->FieldCaption["_STOCK_STATUS_ID_"])) {
            $sql .= " stock_status_id = '" . (int) $data_a[$this->FieldCaption["_STOCK_STATUS_ID_"]] . "',";
        } else {
            if (isset($this->FieldCaption["_STOCK_STATUS_"])) {
                $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ = $this->addStockStatus($data_a[$this->FieldCaption["_STOCK_STATUS_"]]);
                $sql .= " stock_status_id = '" . (int) $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ . "',";
            } else {
                if (isset($this->FieldCaption["_QUANTITY_"]) && (int) $data_a[$this->FieldCaption["_QUANTITY_"]] == 0) {
                    $sql .= " stock_status_id = '" . (int) $this->config->get("config_stock_status_id") . "',";
                }
            }
        }
        if (isset($this->FieldCaption["_SHIPPING_"])) {
            $sql .= " shipping = '" . $this->db->escape($data_a[$this->FieldCaption["_SHIPPING_"]]) . "',";
        }
        if (isset($this->FieldCaption["_PRICE_"])) {
            $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $this->validateNumberFloat($data_a[$this->FieldCaption["_PRICE_"]]);
            switch ((int) $this->ImportConfig["calc_mode"]) {
                case 0:
                case 1:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ * (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ * (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 2:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ / (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ / (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 3:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ + (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ + (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 4:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ - (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ - (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                default:
                    $sql .= " price = '" . number_format((double) $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_, 4, ".", "") . "',";
            }
        }
        if (isset($this->FieldCaption["_DISCOUNT_"])) {
            $this->model_csvprice_pro_lib_product_discount->addProductDiscount($product_id, $data_a[$this->FieldCaption["_DISCOUNT_"]]);
        }
        if (isset($this->FieldCaption["_SPECIAL_"])) {
            $this->model_csvprice_pro_lib_product_special->addProductSpecial($product_id, $data_a[$this->FieldCaption["_SPECIAL_"]]);
        }
        if (isset($this->FieldCaption["_SORT_ORDER_"])) {
            $sql .= " sort_order = '" . (int) $data_a[$this->FieldCaption["_SORT_ORDER_"]] . "',";
        }
        if (isset($this->FieldCaption["_POINTS_"])) {
            $sql .= " points = '" . (int) $data_a[$this->FieldCaption["_POINTS_"]] . "',";
        }
        if (isset($this->FieldCaption["_WEIGHT_"])) {
            $sql .= " weight = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_WEIGHT_"]]) . "',";
        }
        if (isset($this->FieldCaption["_LENGTH_"])) {
            $sql .= " length = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_LENGTH_"]]) . "',";
        }
        if (isset($this->FieldCaption["_WIDTH_"])) {
            $sql .= " width = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_WIDTH_"]]) . "',";
        }
        if (isset($this->FieldCaption["_HEIGHT_"])) {
            $sql .= " height = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_HEIGHT_"]]) . "',";
        }
        if (isset($this->FieldCaption["_IMAGE_"])) {
            if ($this->ImportConfig["image_download"] && !empty($data_a[$this->FieldCaption["_IMAGE_"]])) {
                $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->FieldCaption["_IMAGE_"]], $this->ImagePath, $product_id);
            } else {
                if (!empty($this->ImportConfig["img_path"]) && !empty($data_a[$this->FieldCaption["_IMAGE_"]])) {
                    $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $this->ImportConfig["img_path"] . $data_a[$this->FieldCaption["_IMAGE_"]];
                } else {
                    $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $data_a[$this->FieldCaption["_IMAGE_"]];
                }
            }
            $sql .= " image = '" . $this->db->escape(html_entity_decode($_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_, ENT_QUOTES, "UTF-8")) . "',";
        }
        if ($this->ImportConfig["product_disable"] == 1) {
            $sql .= " status = 1,";
        } else {
            if (isset($this->FieldCaption["_STATUS_"])) {
                $sql .= " status = '" . (int) $data_a[$this->FieldCaption["_STATUS_"]] . "',";
            }
        }
        if (isset($this->CustomFields[DB_PREFIX . "product"]) && 0 < count($this->CustomFields[DB_PREFIX . "product"])) {
            foreach ($this->CustomFields[DB_PREFIX . "product"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!$this->ImportConfig["skip_manufacturer"]) {
            if (isset($this->FieldCaption["_MANUFACTURER_"])) {
                $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ = $this->getManufacturer($data_a[$this->FieldCaption["_MANUFACTURER_"]]);
                $sql .= " manufacturer_id = '" . (int) $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ . "',";
            } else {
                $sql .= " manufacturer_id = '" . (int) $this->ImportConfig["product_manufacturer"] . "',";
            }
        }
        $sql .= " date_modified = NOW(),";
        if (!empty($sql)) {
            $sql = "UPDATE `" . DB_PREFIX . "product` SET " . mb_substr($sql, 0, -1) . " WHERE product_id = '" . (int) $product_id . "'";
            $this->db->query($sql);
        }
        $sql = "";
        $product = [];
        if (isset($this->FieldCaption["_NAME_"]) && (!$this->ImportConfig["exclude_filter"] || empty($this->ImportConfig["exclude_filter_name"]) || !$this->regexProduct($product_id, $this->ImportConfig["exclude_filter_name"], "name"))) {
            $sql .= " name = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_NAME_"]])) . "',";
        }
        if (isset($this->FieldCaption["_DESCRIPTION_"]) && (!$this->ImportConfig["exclude_filter"] || empty($this->ImportConfig["exclude_filter_desc"]) || !$this->regexProduct($product_id, $this->ImportConfig["exclude_filter_desc"], "desc"))) {
            $sql .= " description = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_DESCRIPTION_"]])) . "',";
        }
        unset($product);
        if ($this->CoreType["SEO_TITLE"] && $this->CoreType["SEO_H1"]) {
            if (isset($this->FieldCaption["_META_TITLE_"])) {
                $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_TITLE_"]])) . "',";
            }
            if (isset($this->FieldCaption["_HTML_H1_"])) {
                $sql .= " seo_h1 = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_HTML_H1_"]])) . "',";
            }
        }
        if (isset($this->FieldCaption["_META_KEYWORDS_"])) {
            $sql .= " meta_keyword = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_KEYWORDS_"]])) . "',";
        }
        if (isset($this->FieldCaption["_META_DESCRIPTION_"])) {
            $sql .= " meta_description = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_DESCRIPTION_"]])) . "',";
        }
        if ($this->CoreType["PRODUCT_TAG"] && isset($this->FieldCaption["_PRODUCT_TAG_"])) {
            $sql .= " tag = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "product_description"]) && 0 < count($this->CustomFields[DB_PREFIX . "product_description"])) {
            foreach ($this->CustomFields[DB_PREFIX . "product_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $result = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "product_description` WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'");
            if (0 < $result->num_rows) {
                $sql = "UPDATE `" . DB_PREFIX . "product_description` SET " . mb_substr($sql, 0, -1) . " WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'";
            } else {
                $sql = "INSERT INTO `" . DB_PREFIX . "product_description` SET " . $sql . " product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->LanguageID . "'";
            }
            $this->db->query($sql);
        }
        if ($this->CoreType["PRODUCT_TAG_OLD"] && isset($this->FieldCaption["_PRODUCT_TAG_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_tag` WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'");
            if (!empty($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) {
                $_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ = explode(",", $data_a[$this->FieldCaption["_PRODUCT_TAG_"]]);
                foreach ($_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ as $_obfuscated_0D38132323011D131804250B2E190530210E0E30352701_) {
                    $sql = "INSERT INTO `" . DB_PREFIX . "product_tag` SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->LanguageID . "', tag = '" . $this->db->escape(htmlspecialchars(trim($_obfuscated_0D38132323011D131804250B2E190530210E0E30352701_))) . "'";
                    $this->db->query($sql);
                }
            }
        }
        $sql = "";
        if (isset($this->FieldCaption["_SEO_KEYWORD_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'product_id=" . (int) $product_id . "'");
            if (!empty($data_a[$this->FieldCaption["_SEO_KEYWORD_"]])) {
                $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($data_a[$this->FieldCaption["_SEO_KEYWORD_"]]) . "'";
                $this->db->query($sql);
            }
        }
        if (isset($this->FieldCaption["_IMAGES_"])) {
            $this->updateProductImages($product_id, $data_a[$this->FieldCaption["_IMAGES_"]]);
        }
        if (!$this->ImportConfig["skip_import_store"]) {
            if (isset($this->FieldCaption["_STORE_ID_"])) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
                $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ = explode(",", $data_a[$this->FieldCaption["_STORE_ID_"]]);
                foreach ($_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                }
            } else {
                if (isset($this->ImportConfig["to_store"])) {
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
                    foreach ($this->ImportConfig["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                    }
                }
            }
        }
        if (isset($this->FieldCaption["_REWARD_POINTS_"])) {
            $this->updateProductRewardPoints($product_id, $data_a[$this->FieldCaption["_REWARD_POINTS_"]]);
        }
        $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = 0;
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
        if (!$this->ImportConfig["skip_category"]) {
            if (isset($this->FieldCaption["_CATEGORY_ID_"]) && !empty($data_a[$this->FieldCaption["_CATEGORY_ID_"]])) {
                $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode(",", $data_a[$this->FieldCaption["_CATEGORY_ID_"]]);
            } else {
                if (isset($this->FieldCaption["_CATEGORY_"]) && !empty($data_a[$this->FieldCaption["_CATEGORY_"]])) {
                    $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $this->addProductCategory($data_a[$this->FieldCaption["_CATEGORY_"]]);
                } else {
                    $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $this->ImportConfig["product_category"];
                }
            }
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "'");
        }
        if ($this->CoreType["MAIN_CATEGORY"]) {
            if (!$this->ImportConfig["skip_main_category"] && !empty($this->ImportConfig["main_category_id"])) {
                $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $this->ImportConfig["main_category_id"];
            }
            if (!$this->ImportConfig["skip_main_category"] && isset($this->FieldCaption["_MAIN_CATEGORY_"]) && !empty($data_a[$this->FieldCaption["_MAIN_CATEGORY_"]])) {
                $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_ = $this->addProductCategory($data_a[$this->FieldCaption["_MAIN_CATEGORY_"]]);
                if (!empty($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_)) {
                    $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = end($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
                    if (isset($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_) && !empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
                        $_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_ = array_merge($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_, $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
                        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = array_unique($_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_);
                    } else {
                        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_;
                    }
                    unset($_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_);
                }
                unset($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
            }
        }
        if (!empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
            if (isset($this->FieldCaption["_CATEGORY_"]) || !$this->ImportConfig["skip_category"]) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "'");
            }
            foreach ($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ as $category_id) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "' AND category_id = '" . (int) $category_id . "'");
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
            }
            if ($_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ == 0) {
                $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $category_id;
            }
        }
        if ($this->CoreType["MAIN_CATEGORY"] && !$this->ImportConfig["skip_main_category"] && $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ != 0) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE (product_id = '" . (int) $product_id . "' AND category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "') OR (product_id = '" . (int) $product_id . "' AND main_category = 1)");
            $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "', main_category = 1");
        }
        if (isset($this->FieldCaption["_OPTIONS_"])) {
            $this->model_csvprice_pro_lib_product_option->addProductOptions($product_id, $data_a[$this->FieldCaption["_OPTIONS_"]]);
        }
        if (isset($this->FieldCaption["_FILTERS_"])) {
            $this->model_csvprice_pro_lib_product_filter->addProductFilters($product_id, $data_a[$this->FieldCaption["_FILTERS_"]], $this->LanguageID);
        }
        if (isset($this->FieldCaption["_ATTRIBUTES_"])) {
            $_obfuscated_0D3B11081C213C28363D121812342836150E2B31342411_ = 1;
            if ($this->ImportConfig["exclude_filter"] && !empty($this->ImportConfig["exclude_filter_attr"]) && $this->regexProduct($product_id, $this->ImportConfig["exclude_filter_attr"], "attr")) {
                $_obfuscated_0D3B11081C213C28363D121812342836150E2B31342411_ = 0;
            }
            if ($_obfuscated_0D3B11081C213C28363D121812342836150E2B31342411_) {
                $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = [];
                $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = explode("\n", $data_a[$this->FieldCaption["_ATTRIBUTES_"]]);
                $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = array_unique($_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_, SORT_STRING);
                if (!empty($_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_)) {
                    $this->model_csvprice_pro_lib_product_attribute->updateProductAttribute($product_id, $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_);
                }
            }
        }
        if ($this->RelatedCaption) {
            $this->addProductRelated($product_id, $data_a[$this->FieldCaption[$this->RelatedCaption]]);
        }
        return true;
    }
    private function getProductStoresID(&$product_id)
    {
        $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ = [];
        $query = $this->db->query("SELECT store_id FROM `" . DB_PREFIX . "product_to_store` p2s WHERE p2s.product_id = " . (int) $product_id);
        foreach ($query->rows as $result) {
            $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_[] = $result["store_id"];
        }
        return implode(",", $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_);
    }
    private function updateProductImages($product_id, $images_data, $image_data = "")
    {
        if ($image_data) {
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = '" . $this->db->escape(html_entity_decode($image_data, ENT_QUOTES, "UTF-8")) . "' WHERE product_id = '" . (int) $product_id . "'");
            return true;
        }
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_image` WHERE product_id = '" . (int) $product_id . "'");
        $_obfuscated_0D1107400E0D18151C243C2C0406251E162A2F262E1B11_ = explode(",", $images_data);
        if (empty($_obfuscated_0D1107400E0D18151C243C2C0406251E162A2F262E1B11_)) {
            return NULL;
        }
        $_obfuscated_0D3B4039102A142505084030132B1F082F170338261A22_ = 1;
        foreach ($_obfuscated_0D1107400E0D18151C243C2C0406251E162A2F262E1B11_ as $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_) {
            if (!empty($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_)) {
                if ($this->ImportConfig["image_download"]) {
                    $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $this->model_csvprice_pro_app_setting->downloadImage($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_, $this->ImagePath, $product_id);
                } else {
                    if (!empty($this->ImportConfig["img_path"])) {
                        $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $this->ImportConfig["img_path"] . $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_;
                    }
                }
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_image` SET sort_order = '" . $_obfuscated_0D3B4039102A142505084030132B1F082F170338261A22_ . "', product_id = '" . (int) $product_id . "', image = '" . $this->db->escape(html_entity_decode($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_, ENT_QUOTES, "UTF-8")) . "'");
                $_obfuscated_0D3B4039102A142505084030132B1F082F170338261A22_++;
            }
        }
    }
    private function updateProductEmptyField(&$product_id, &$data_a)
    {
        $sql = "";
        if ((isset($this->FieldCaption["_IMAGE_"]) || isset($this->FieldCaption["_IMAGES_"])) && $this->ImportConfig["image_download"]) {
            $this->ImagePath = $this->model_csvprice_pro_app_setting->getRandDir();
        }
        if (isset($this->FieldCaption["_MODEL_"]) && !empty($data_a[$this->FieldCaption["_MODEL_"]])) {
            $sql .= " `model` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_MODEL_"]])) . "',";
        }
        if (isset($this->FieldCaption["_SKU_"]) && !empty($data_a[$this->FieldCaption["_SKU_"]])) {
            $sql .= " `sku` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_SKU_"]])) . "',";
        }
        if ($this->CoreType["EAN"] && isset($this->FieldCaption["_EAN_"]) && !empty($data_a[$this->FieldCaption["_EAN_"]])) {
            $sql .= " `ean` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_EAN_"]])) . "',";
        }
        if ($this->CoreType["JAN"] && isset($this->FieldCaption["_JAN_"]) && !empty($data_a[$this->FieldCaption["_JAN_"]])) {
            $sql .= " `jan` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_JAN_"]])) . "',";
        }
        if ($this->CoreType["ISBN"] && isset($this->FieldCaption["_ISBN_"]) && !empty($data_a[$this->FieldCaption["_ISBN_"]])) {
            $sql .= " `isbn` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_ISBN_"]])) . "',";
        }
        if ($this->CoreType["MPN"] && isset($this->FieldCaption["_MPN_"]) && !empty($data_a[$this->FieldCaption["_MPN_"]])) {
            $sql .= " `mpn` = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_MPN_"]])) . "',";
        }
        if (isset($this->FieldCaption["_UPC_"]) && !empty($data_a[$this->FieldCaption["_UPC_"]])) {
            $sql .= " upc = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_UPC_"]])) . "',";
        }
        if (isset($this->FieldCaption["_LOCATION_"]) && !empty($data_a[$this->FieldCaption["_LOCATION_"]])) {
            $sql .= " location = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_LOCATION_"]])) . "',";
        }
        if (isset($this->FieldCaption["_QUANTITY_"]) && $data_a[$this->FieldCaption["_QUANTITY_"]] != "") {
            $sql .= " quantity = '" . (int) $data_a[$this->FieldCaption["_QUANTITY_"]] . "',";
        }
        if (isset($this->FieldCaption["_STOCK_STATUS_ID_"]) && $data_a[$this->FieldCaption["_STOCK_STATUS_ID_"]] != "") {
            $sql .= " stock_status_id = '" . (int) $data_a[$this->FieldCaption["_STOCK_STATUS_ID_"]] . "',";
        } else {
            if (isset($this->FieldCaption["_STOCK_STATUS_"]) && $data_a[$this->FieldCaption["_STOCK_STATUS_"]] != "") {
                $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ = $this->addStockStatus($data_a[$this->FieldCaption["_STOCK_STATUS_"]]);
                $sql .= " stock_status_id = '" . (int) $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ . "',";
            }
        }
        if (isset($this->FieldCaption["_SHIPPING_"]) && $data_a[$this->FieldCaption["_SHIPPING_"]] != "") {
            $sql .= " shipping = '" . $this->db->escape($data_a[$this->FieldCaption["_SHIPPING_"]]) . "',";
        }
        if (isset($this->FieldCaption["_PRICE_"]) && $data_a[$this->FieldCaption["_PRICE_"]] != "") {
            $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $this->validateNumberFloat($data_a[$this->FieldCaption["_PRICE_"]]);
            switch ((int) $this->ImportConfig["calc_mode"]) {
                case 0:
                case 1:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ * (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ * (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 2:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ / (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ / (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 3:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ + (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ + (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                case 4:
                    if (0 < (double) $this->ImportConfig["calc_value"][0]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ - (double) $this->ImportConfig["calc_value"][0];
                    }
                    if (0 < (double) $this->ImportConfig["calc_value"][1]) {
                        $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ = $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_ - (double) $this->ImportConfig["calc_value"][1];
                    }
                    break;
                default:
                    $sql .= " price = '" . number_format((double) $_obfuscated_0D2F051B34042B022E34081A245B3810191335182A1A01_, 4, ".", "") . "',";
            }
        }
        if (isset($this->FieldCaption["_DISCOUNT_"]) && !empty($data_a[$this->FieldCaption["_DISCOUNT_"]])) {
            $this->model_csvprice_pro_lib_product_discount->addProductDiscount($product_id, $data_a[$this->FieldCaption["_DISCOUNT_"]]);
        }
        if (isset($this->FieldCaption["_SPECIAL_"]) && !empty($data_a[$this->FieldCaption["_SPECIAL_"]])) {
            $this->model_csvprice_pro_lib_product_special->addProductSpecial($product_id, $data_a[$this->FieldCaption["_SPECIAL_"]]);
        }
        if (isset($this->FieldCaption["_SORT_ORDER_"]) && $data_a[$this->FieldCaption["_SORT_ORDER_"]] != "") {
            $sql .= " sort_order = '" . (int) $data_a[$this->FieldCaption["_SORT_ORDER_"]] . "',";
        }
        if (isset($this->FieldCaption["_POINTS_"]) && $data_a[$this->FieldCaption["_POINTS_"]] != "") {
            $sql .= " points = '" . (int) $data_a[$this->FieldCaption["_POINTS_"]] . "',";
        }
        if (isset($this->FieldCaption["_WEIGHT_"]) && $data_a[$this->FieldCaption["_WEIGHT_"]] != "") {
            $sql .= " weight = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_WEIGHT_"]]) . "',";
        }
        if (isset($this->FieldCaption["_LENGTH_"]) && $data_a[$this->FieldCaption["_LENGTH_"]] != "") {
            $sql .= " length = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_LENGTH_"]]) . "',";
        }
        if (isset($this->FieldCaption["_WIDTH_"]) && $data_a[$this->FieldCaption["_WIDTH_"]] != "") {
            $sql .= " width = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_WIDTH_"]]) . "',";
        }
        if (isset($this->FieldCaption["_HEIGHT_"]) && $data_a[$this->FieldCaption["_HEIGHT_"]] != "") {
            $sql .= " height = '" . $this->validateNumberFloat($data_a[$this->FieldCaption["_HEIGHT_"]]) . "',";
        }
        if (isset($this->FieldCaption["_IMAGE_"]) && !empty($data_a[$this->FieldCaption["_IMAGE_"]])) {
            if ($this->ImportConfig["image_download"]) {
                $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->FieldCaption["_IMAGE_"]], $this->ImagePath, $product_id);
            } else {
                if (!empty($this->ImportConfig["img_path"]) && !empty($data_a[$this->FieldCaption["_IMAGE_"]])) {
                    $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $this->ImportConfig["img_path"] . $data_a[$this->FieldCaption["_IMAGE_"]];
                } else {
                    $_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_ = $data_a[$this->FieldCaption["_IMAGE_"]];
                }
            }
            $sql .= " image = '" . $this->db->escape(html_entity_decode($_obfuscated_0D3604033307110C050D0510071E2C390B1D1326193922_, ENT_QUOTES, "UTF-8")) . "',";
        }
        if ($this->ImportConfig["product_disable"] == 1) {
            $sql .= " status = 1,";
        } else {
            if (isset($this->FieldCaption["_STATUS_"]) && $data_a[$this->FieldCaption["_STATUS_"]] != "") {
                $sql .= " status = '" . (int) $data_a[$this->FieldCaption["_STATUS_"]] . "',";
            }
        }
        if (isset($this->CustomFields[DB_PREFIX . "product"]) && 0 < count($this->CustomFields[DB_PREFIX . "product"])) {
            foreach ($this->CustomFields[DB_PREFIX . "product"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]) && $data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]] != "") {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!$this->ImportConfig["skip_manufacturer"]) {
            if (isset($this->FieldCaption["_MANUFACTURER_"])) {
                if (!empty($data_a[$this->FieldCaption["_MANUFACTURER_"]])) {
                    $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ = $this->getManufacturer($data_a[$this->FieldCaption["_MANUFACTURER_"]]);
                    $sql .= " manufacturer_id = '" . (int) $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ . "',";
                }
            } else {
                $sql .= " manufacturer_id = '" . (int) $this->ImportConfig["product_manufacturer"] . "',";
            }
        }
        $sql .= " date_modified = NOW(),";
        if (!empty($sql)) {
            $sql = "UPDATE `" . DB_PREFIX . "product` SET " . mb_substr($sql, 0, -1) . " WHERE product_id = '" . (int) $product_id . "'";
            $this->db->query($sql);
        }
        $sql = "";
        $product = [];
        if (isset($this->FieldCaption["_NAME_"]) && !empty($data_a[$this->FieldCaption["_NAME_"]]) && (!$this->ImportConfig["exclude_filter"] || empty($this->ImportConfig["exclude_filter_name"]) || !$this->regexProduct($product_id, $this->ImportConfig["exclude_filter_name"], "name"))) {
            $sql .= " name = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_NAME_"]])) . "',";
        }
        if (isset($this->FieldCaption["_DESCRIPTION_"]) && !empty($data_a[$this->FieldCaption["_DESCRIPTION_"]]) && (!$this->ImportConfig["exclude_filter"] || empty($this->ImportConfig["exclude_filter_desc"]) || !$this->regexProduct($product_id, $this->ImportConfig["exclude_filter_desc"], "desc"))) {
            $sql .= " description = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_DESCRIPTION_"]])) . "',";
        }
        unset($product);
        if ($this->CoreType["SEO_TITLE"] && $this->CoreType["SEO_H1"]) {
            if (isset($this->FieldCaption["_META_TITLE_"]) && !empty($data_a[$this->FieldCaption["_META_TITLE_"]])) {
                $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_TITLE_"]])) . "',";
            }
            if (isset($this->FieldCaption["_HTML_H1_"]) && !empty($data_a[$this->FieldCaption["_HTML_H1_"]])) {
                $sql .= " seo_h1 = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_HTML_H1_"]])) . "',";
            }
        }
        if (isset($this->FieldCaption["_META_KEYWORDS_"]) && !empty($data_a[$this->FieldCaption["_META_KEYWORDS_"]])) {
            $sql .= " meta_keyword = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_KEYWORDS_"]])) . "',";
        }
        if (isset($this->FieldCaption["_META_DESCRIPTION_"]) && !empty($data_a[$this->FieldCaption["_META_DESCRIPTION_"]])) {
            $sql .= " meta_description = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_META_DESCRIPTION_"]])) . "',";
        }
        if ($this->CoreType["PRODUCT_TAG"] && isset($this->FieldCaption["_PRODUCT_TAG_"]) && !empty($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) {
            $sql .= " tag = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "product_description"]) && 0 < count($this->CustomFields[DB_PREFIX . "product_description"])) {
            foreach ($this->CustomFields[DB_PREFIX . "product_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]) && $data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]] != "") {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $result = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "product_description` WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'");
            if (0 < $result->num_rows) {
                $sql = "UPDATE `" . DB_PREFIX . "product_description` SET " . mb_substr($sql, 0, -1) . " WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'";
            } else {
                $sql = "INSERT INTO `" . DB_PREFIX . "product_description` SET " . $sql . " product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->LanguageID . "'";
            }
            $this->db->query($sql);
        }
        if ($this->CoreType["PRODUCT_TAG_OLD"] && isset($this->FieldCaption["_PRODUCT_TAG_"]) && !empty($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_tag` WHERE product_id = '" . (int) $product_id . "' AND language_id = '" . (int) $this->LanguageID . "'");
            if (!empty($data_a[$this->FieldCaption["_PRODUCT_TAG_"]])) {
                $_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ = explode(",", $data_a[$this->FieldCaption["_PRODUCT_TAG_"]]);
                foreach ($_obfuscated_0D0F18130F3E3807051A2507123C3E103D280D1B011D01_ as $_obfuscated_0D38132323011D131804250B2E190530210E0E30352701_) {
                    $sql = "INSERT INTO `" . DB_PREFIX . "product_tag` SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->LanguageID . "', tag = '" . $this->db->escape(htmlspecialchars(trim($_obfuscated_0D38132323011D131804250B2E190530210E0E30352701_))) . "'";
                    $this->db->query($sql);
                }
            }
        }
        $sql = "";
        if (isset($this->FieldCaption["_SEO_KEYWORD_"]) && !empty($data_a[$this->FieldCaption["_SEO_KEYWORD_"]])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'product_id=" . (int) $product_id . "'");
            if (!empty($data_a[$this->FieldCaption["_SEO_KEYWORD_"]])) {
                $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($data_a[$this->FieldCaption["_SEO_KEYWORD_"]]) . "'";
                $this->db->query($sql);
            }
        }
        if (isset($this->FieldCaption["_IMAGES_"]) && !empty($data_a[$this->FieldCaption["_IMAGES_"]])) {
            $this->updateProductImages($product_id, $data_a[$this->FieldCaption["_IMAGES_"]]);
        }
        if (!$this->ImportConfig["skip_import_store"]) {
            if (isset($this->FieldCaption["_STORE_ID_"]) && !empty($data_a[$this->FieldCaption["_STORE_ID_"]])) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
                $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ = explode(",", $data_a[$this->FieldCaption["_STORE_ID_"]]);
                foreach ($_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                }
            } else {
                if (isset($this->ImportConfig["to_store"]) && !empty($this->ImportConfig["to_store"])) {
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = '" . (int) $product_id . "';");
                    foreach ($this->ImportConfig["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                    }
                }
            }
        }
        if (isset($this->FieldCaption["_REWARD_POINTS_"]) && $data_a[$this->FieldCaption["_REWARD_POINTS_"]] != "") {
            $this->updateProductRewardPoints($product_id, $data_a[$this->FieldCaption["_REWARD_POINTS_"]]);
        }
        $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = 0;
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
        if (!$this->ImportConfig["skip_category"]) {
            if (isset($this->FieldCaption["_CATEGORY_ID_"]) && !empty($data_a[$this->FieldCaption["_CATEGORY_ID_"]])) {
                $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode(",", $data_a[$this->FieldCaption["_CATEGORY_ID_"]]);
            } else {
                if (isset($this->FieldCaption["_CATEGORY_"]) && !empty($data_a[$this->FieldCaption["_CATEGORY_"]])) {
                    $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $this->addProductCategory($data_a[$this->FieldCaption["_CATEGORY_"]]);
                } else {
                    $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $this->ImportConfig["product_category"];
                }
            }
            if (!empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "'");
            }
        }
        if ($this->CoreType["MAIN_CATEGORY"]) {
            if (!$this->ImportConfig["skip_main_category"] && !empty($this->ImportConfig["main_category_id"])) {
                $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $this->ImportConfig["main_category_id"];
            }
            if (!$this->ImportConfig["skip_main_category"] && isset($this->FieldCaption["_MAIN_CATEGORY_"]) && !empty($data_a[$this->FieldCaption["_MAIN_CATEGORY_"]])) {
                $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_ = $this->addProductCategory($data_a[$this->FieldCaption["_MAIN_CATEGORY_"]]);
                if (!empty($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_)) {
                    $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = end($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
                    if (isset($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_) && !empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
                        $_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_ = array_merge($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_, $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
                        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = array_unique($_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_);
                    } else {
                        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = $_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_;
                    }
                    unset($_obfuscated_0D1B362523120A07140704250B2B0B2A08323E2E141511_);
                }
                unset($_obfuscated_0D163D260603181C1E5C35103932393F2C1F23383C1B11_);
            }
        }
        if (!empty($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_)) {
            if (isset($this->FieldCaption["_CATEGORY_"]) || !$this->ImportConfig["skip_category"]) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "'");
            }
            foreach ($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ as $category_id) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int) $product_id . "' AND category_id = '" . (int) $category_id . "'");
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
            }
            if ($_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ == 0) {
                $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ = $category_id;
            }
        }
        if ($this->CoreType["MAIN_CATEGORY"] && !$this->ImportConfig["skip_main_category"] && $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ != 0) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE (product_id = '" . (int) $product_id . "' AND category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "') OR (product_id = '" . (int) $product_id . "' AND main_category = 1)");
            $this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $_obfuscated_0D12010915400D2C1C3B2D361E2740270714272C1A0522_ . "', main_category = 1");
        }
        if (isset($this->FieldCaption["_OPTIONS_"]) && !empty($data_a[$this->FieldCaption["_OPTIONS_"]])) {
            $this->model_csvprice_pro_lib_product_option->addProductOptions($product_id, $data_a[$this->FieldCaption["_OPTIONS_"]]);
        }
        if (isset($this->FieldCaption["_FILTERS_"]) && !empty($data_a[$this->FieldCaption["_FILTERS_"]])) {
            $this->model_csvprice_pro_lib_product_filter->addProductFilters($product_id, $data_a[$this->FieldCaption["_FILTERS_"]], $this->LanguageID);
        }
        if (isset($this->FieldCaption["_ATTRIBUTES_"]) && !empty($data_a[$this->FieldCaption["_ATTRIBUTES_"]])) {
            $_obfuscated_0D3B11081C213C28363D121812342836150E2B31342411_ = 1;
            if ($this->ImportConfig["exclude_filter"] && !empty($this->ImportConfig["exclude_filter_attr"]) && $this->regexProduct($product_id, $this->ImportConfig["exclude_filter_attr"], "attr")) {
                $_obfuscated_0D3B11081C213C28363D121812342836150E2B31342411_ = 0;
            }
            if ($_obfuscated_0D3B11081C213C28363D121812342836150E2B31342411_) {
                $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = [];
                $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = explode("\n", $data_a[$this->FieldCaption["_ATTRIBUTES_"]]);
                $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_ = array_unique($_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_, SORT_STRING);
                if (!empty($_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_)) {
                    $this->model_csvprice_pro_lib_product_attribute->updateProductAttribute($product_id, $_obfuscated_0D221C113C371D383E18380308053E3D38273406330722_);
                }
            }
        }
        if ($this->RelatedCaption && !empty($data_a[$this->FieldCaption[$this->RelatedCaption]])) {
            $this->addProductRelated($product_id, $data_a[$this->FieldCaption[$this->RelatedCaption]]);
        }
        return true;
    }
    private function getManufacturer(&$name)
    {
        $name = trim($name, " \t\n");
        if (empty($name)) {
            return 0;
        }
        $result = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "manufacturer` WHERE LOWER(name) = LOWER('" . $this->db->escape(htmlspecialchars($name)) . "') LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["manufacturer_id"];
        }
        $this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer` SET name = '" . $this->db->escape(htmlspecialchars($name)) . "', sort_order = 0");
        $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ = $this->db->getLastId();
        if (isset($this->ImportSetting["to_store"])) {
            foreach ($this->ImportSetting["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET manufacturer_id = '" . (int) $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
            }
        }
        if ($this->CoreType["MANUFACTURER_DESCRIPTION"]) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_description` SET manufacturer_id = '" . (int) $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_ . "', language_id = '" . (int) $this->LanguageID . "'");
        }
        return $_obfuscated_0D1114015C5B2E5B0C220A393721383F1A21275B2C3C22_;
    }
    private function updateProductRewardPoints($product_id, $reward_points)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_reward` WHERE  product_id = '" . (int) $product_id . "'");
        $reward_points = trim($reward_points, " \n\t\r");
        if (empty($reward_points)) {
            return NULL;
        }
        $data_a = explode(",", $reward_points);
        if (empty($data_a)) {
            return NULL;
        }
        foreach ($data_a as $_obfuscated_0D265B1E0B0C332330030F195B3330180A2C032F270F01_) {
            $_obfuscated_0D013714363538310D1B3B29153C193125042E151F0332_ = explode(":", $_obfuscated_0D265B1E0B0C332330030F195B3330180A2C032F270F01_);
            if (!(empty($_obfuscated_0D013714363538310D1B3B29153C193125042E151F0332_) && count($_obfuscated_0D013714363538310D1B3B29153C193125042E151F0332_) < 2)) {
                $_obfuscated_0D2C3C0B401B22181B11190109333D051310081F371532_ = $this->getCustomerGroupIdByName($_obfuscated_0D013714363538310D1B3B29153C193125042E151F0332_[0]);
                if (!empty($_obfuscated_0D2C3C0B401B22181B11190109333D051310081F371532_)) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "product_reward` SET `product_id` = '" . (int) $product_id . "', `customer_group_id` = '" . (int) $_obfuscated_0D2C3C0B401B22181B11190109333D051310081F371532_ . "', `points` = '" . (int) $_obfuscated_0D013714363538310D1B3B29153C193125042E151F0332_[1] . "'");
                }
            }
        }
    }
    private function getCustomerGroupIdByName($name)
    {
        if (isset($this->CustomerGroup[$name])) {
            return $this->CustomerGroup[$name];
        }
        if ($this->CoreType["CUSTOMER_DESC"]) {
            $result = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "customer_group_description` WHERE `name` = '" . $this->db->escape($name) . "' AND language_id = '" . (int) $this->LanguageID . "' LIMIT 1");
        } else {
            $result = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "customer_group` WHERE `name` = '" . $this->db->escape($name) . "' LIMIT 1");
        }
        if (isset($result->row["customer_group_id"])) {
            $this->CustomerGroup[$name] = $result->row["customer_group_id"];
            return $result->row["customer_group_id"];
        }
        return 0;
    }
    public function validateNumberFloat($number)
    {
        $number = trim($number, " \n\t\r.,");
        $number = preg_replace("/,/iu", ".", $number);
        $number = preg_replace("/[^0-9\\.]/", "", $number);
        $number = filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return $number;
    }
    private function addStockStatus($data_a)
    {
        $data_a = trim($data_a);
        if (isset($this->StockStatus[$data_a])) {
            return $this->StockStatus[$data_a];
        }
        $this->db->query("INSERT INTO `" . DB_PREFIX . "stock_status` SET `name` = '" . $this->db->escape($data_a) . "', `language_id` = " . (int) $this->LanguageID);
        $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_ = $this->db->getLastId();
        $this->StockStatus[$data_a] = $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_;
        return $_obfuscated_0D2C3227111D1B312A0829083139151130355C12372D22_;
    }
    private function addProductCategory($data_a)
    {
        if (empty($data_a)) {
            return 0;
        }
        $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_ = [];
        $_obfuscated_0D1A2B101F2A28080B0A0133193D0A17103E193C2E1832_ = explode("\n", $data_a);
        if (count($_obfuscated_0D1A2B101F2A28080B0A0133193D0A17103E193C2E1832_) == 1) {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode($this->ImportConfig["delimiter_category"], trim($data_a, " \n\t\r"));
            if (count($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_) == 1) {
                $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[0] = trim($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[0], " \n\t\r");
                $result = $this->db->query("SELECT cd.category_id FROM `" . DB_PREFIX . "category_description` cd LEFT JOIN `" . DB_PREFIX . "category` c ON (c.category_id = cd.category_id) WHERE LOWER(cd.name) = LOWER('" . $this->db->escape(htmlspecialchars($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[0])) . "') LIMIT 1");
                if (0 < $result->num_rows) {
                    $category_id = $result->row["category_id"];
                } else {
                    $_obfuscated_0D345B2B2A2D401A340223021C235B171C353C2D0D0611_ = $this->ImportConfig["column"];
                    $_obfuscated_0D0E18253216273929322C031514170D343B33262A0A22_ = $this->ImportConfig["top"];
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET parent_id = 0, `top` = " . $_obfuscated_0D0E18253216273929322C031514170D343B33262A0A22_ . ", `column` = " . $_obfuscated_0D345B2B2A2D401A340223021C235B171C353C2D0D0611_ . ", sort_order = 1, status = 1, date_modified =  NOW(), date_added = NOW()");
                    $category_id = $this->db->getLastId();
                    $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $this->LanguageID . "', " . " name = '" . $this->db->escape(htmlspecialchars($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[0])) . "'");
                    if (isset($this->ImportSetting["to_store"]) && !empty($this->ImportSetting["to_store"])) {
                        foreach ($this->ImportSetting["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                        }
                    }
                }
                $_obfuscated_0D1A2B101F2A28080B0A0133193D0A17103E193C2E1832_ = [];
                $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_[] = $category_id;
            }
        }
        foreach ($_obfuscated_0D1A2B101F2A28080B0A0133193D0A17103E193C2E1832_ as $data_a) {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode($this->ImportConfig["delimiter_category"], $data_a);
            $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ = 0;
            foreach ($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ as $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_) {
                $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = trim($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_, " \n\t\r");
                $result = $this->db->query("SELECT cd.category_id FROM `" . DB_PREFIX . "category_description` cd LEFT JOIN `" . DB_PREFIX . "category` c ON (c.category_id = cd.category_id) WHERE LOWER(cd.name) = LOWER('" . $this->db->escape(htmlspecialchars($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_)) . "') AND c.parent_id = '" . $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ . "' LIMIT 1");
                if (isset($result->num_rows) && 0 < $result->num_rows) {
                    $category_id = $result->row["category_id"];
                } else {
                    if ($_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ == 0) {
                        $_obfuscated_0D345B2B2A2D401A340223021C235B171C353C2D0D0611_ = $this->ImportConfig["column"];
                        $_obfuscated_0D0E18253216273929322C031514170D343B33262A0A22_ = $this->ImportConfig["top"];
                    } else {
                        $_obfuscated_0D345B2B2A2D401A340223021C235B171C353C2D0D0611_ = 0;
                        $_obfuscated_0D0E18253216273929322C031514170D343B33262A0A22_ = 0;
                    }
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET parent_id = " . $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ . ", `top` = " . (int) $_obfuscated_0D0E18253216273929322C031514170D343B33262A0A22_ . ", `column` = " . $_obfuscated_0D345B2B2A2D401A340223021C235B171C353C2D0D0611_ . ", sort_order = 1, status = 1, date_modified =  NOW(), date_added = NOW()");
                    $category_id = $this->db->getLastId();
                    $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $this->LanguageID . "', " . " name = '" . $this->db->escape(htmlspecialchars($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_)) . "'");
                    if (isset($this->ImportSetting["to_store"]) && !empty($this->ImportSetting["to_store"])) {
                        foreach ($this->ImportSetting["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                        }
                    }
                }
                $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ = $category_id;
                if ($this->ImportConfig["fill_category"]) {
                    $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_[] = $category_id;
                }
            }
            if (!$this->ImportConfig["fill_category"]) {
                $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_[] = $category_id;
            }
        }
        return array_unique($_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_);
    }
    private function regexProduct($product_id, $regx, $type = "name")
    {
        switch ($type) {
            case "name":
                $query = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "product_description` pd WHERE pd.product_id = " . (int) $product_id . " AND pd.name REGEXP '" . $this->db->escape($regx) . "' AND pd.language_id = '" . (int) $this->LanguageID . "'");
                if ($query->num_rows) {
                    return true;
                }
                break;
            case "desc":
                $query = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "product_description` pd WHERE pd.product_id = " . (int) $product_id . " AND pd.description REGEXP '" . $this->db->escape($regx) . "' AND pd.language_id = '" . (int) $this->LanguageID . "'");
                if ($query->num_rows) {
                    return true;
                }
                break;
            case "attr":
                $query = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "product_attribute` pa\n\t\t\t\tLEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (pa.attribute_id = ad.attribute_id AND ad.language_id = '" . (int) $this->LanguageID . "')\n\t\t\t\tWHERE pa.product_id = " . (int) $product_id . " AND ad.name REGEXP '" . $this->db->escape($regx) . "'");
                if ($query->num_rows) {
                    return true;
                }
                break;
            default:
                return false;
        }
    }
    private function addProductRelated($product_id, $product_related)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE product_id = '" . (int) $product_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE related_id = '" . (int) $product_id . "'");
        $product_related = explode(",", trim($product_related));
        if (!empty($product_related)) {
            foreach ($product_related as $_obfuscated_0D1E3710211D3D07080812122A1B032B1E23013D392B22_) {
                $_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_ = $this->getProductRelatedId($_obfuscated_0D1E3710211D3D07080812122A1B032B1E23013D392B22_);
                if (!empty($_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_) && (int) $_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_ != (int) $product_id) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' AND related_id = '" . (int) $_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_ . "'");
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_ . "'");
                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_ . "' AND related_id = '" . (int) $product_id . "'");
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $_obfuscated_0D40382E3B2E2408030E3E0918070904211930280A2122_ . "', related_id = '" . (int) $product_id . "'");
                }
            }
        }
    }
    private function getCountLanguages()
    {
        $result = $this->db->query("SELECT COUNT(`language_id`) AS count_languages FROM `" . DB_PREFIX . "language` WHERE `status` = 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["count_languages"];
        }
        return 1;
    }
    private function getFieldCaption($file_name)
    {
        $result = NULL;
        $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_ = [];
        if (($this->FileHandle = fopen($file_name, "r")) !== false) {
            if (($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_ = fgetcsv($this->FileHandle, 0, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== false) {
                if (substr($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[0], 0, 3) == pack("CCC", 239, 187, 191)) {
                    $_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[0] = substr($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[0], 3);
                }
                for ($i = 0; $i < count($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_); $i++) {
                    $_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[$i] = trim($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[$i], " \t\n\r");
                    if (empty($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[$i]) || in_array($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[$i], $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_)) {
                        $_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[$i] = "undefine_" . $i;
                    } else {
                        $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_[] = $_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_[$i];
                    }
                }
                $result = array_flip($_obfuscated_0D1F3D5C2E320738355B1D0F140E1910262B1F103C2B32_);
                $this->FileTell = ftell($this->FileHandle);
            }
            fclose($this->FileHandle);
        }
        return $result;
    }
    private function getProductID(&$data_a)
    {
        return false;
    }
    private function getProductID_Name(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_NAME_"]])) {
            return false;
        }
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product_description` WHERE language_id = '" . (int) $this->LanguageID . "' AND LOWER(name) = LOWER('" . $this->db->escape(htmlspecialchars($data_a[$this->FieldCaption["_NAME_"]])) . "') LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_Id(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_ID_"]])) {
            return false;
        }
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int) $data_a[$this->FieldCaption["_ID_"]] . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_Model(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_MODEL_"]])) {
            return false;
        }
        $key = trim($data_a[$this->FieldCaption["_MODEL_"]], " \n\t\r");
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `model` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_SKU(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_SKU_"]])) {
            return false;
        }
        $key = trim($data_a[$this->FieldCaption["_SKU_"]], " \n\t\r");
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `sku` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_EAN(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_EAN_"]])) {
            return false;
        }
        $key = trim($data_a[$this->FieldCaption["_EAN_"]], " \n\t\r");
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `ean` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_JAN(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_JAN_"]])) {
            return false;
        }
        $key = trim($data_a[$this->FieldCaption["_JAN_"]], " \n\t\r");
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `jan` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_ISBN(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_ISBN_"]])) {
            return false;
        }
        $key = trim($data_a[$this->FieldCaption["_ISBN_"]], " \n\t\r");
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `isbn` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductID_MPN(&$data_a)
    {
        if (empty($data_a[$this->FieldCaption["_MPN_"]])) {
            return false;
        }
        $key = trim($data_a[$this->FieldCaption["_MPN_"]], " \n\t\r");
        $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `mpn` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["product_id"];
        }
        return false;
    }
    private function getProductRelatedId($key)
    {
        $key = trim($key, " \n\t\r");
        if (empty($key) || empty($this->RelatedCaption)) {
            return false;
        }
        switch ($this->RelatedCaption) {
            case "_RELATED_":
            case "_RELATED_ID_":
                return $key;
                break;
            case "_RELATED_MODEL_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `model` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
                break;
            case "_RELATED_SKU_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `sku` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
                break;
            case "_RELATED_EAN_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `ean` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
                break;
            case "_RELATED_JAN_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `jan` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
                break;
            case "_RELATED_ISBN_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `isbn` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
                break;
            case "_RELATED_MPN_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE `mpn` = '" . $this->db->escape(htmlspecialchars($key)) . "' LIMIT 1");
                break;
            case "_RELATED_NAME_":
                $result = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product_description` WHERE language_id = '" . (int) $this->LanguageID . "' AND LOWER(name) = LOWER('" . $this->db->escape(htmlspecialchars($key)) . "') LIMIT 1");
                if (isset($result->num_rows) && 0 < $result->num_rows) {
                    return $result->row["product_id"];
                }
                return false;
                break;
            default:
                return false;
        }
    }
    public function deleteProfile($profile_id)
    {
        $result = $this->db->query("SELECT profile_id FROM `" . DB_PREFIX . "csvprice_pro_crontab` WHERE `profile_id` = " . (int) $profile_id . " LIMIT 1");
        if ($result->num_rows) {
            return NULL;
        }
        $this->db->query("DELETE FROM `" . DB_PREFIX . "csvprice_pro_profiles` WHERE `profile_id` = " . (int) $profile_id . " AND `profile_id` > 2");
    }
    public function addProfile($data_a)
    {
        $data_a["value"] = base64_encode(json_encode($data_a["value"]));
        $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro_profiles` SET `key` = '" . $data_a["key"] . "', `name` = '" . $this->db->escape($data_a["name"]) . "', `value` = '" . $this->db->escape($data_a["value"]) . "';");
        $id = $this->db->getLastId();
        return $id;
    }
    public function editProfile($data)
    {
        $data["value"] = base64_encode(json_encode($data["value"]));
        $this->db->query("UPDATE `" . DB_PREFIX . "csvprice_pro_profiles` SET `value` = '" . $this->db->escape($data["value"]) . "' WHERE `profile_id` = " . (int) $data["profile_id"] . " AND `profile_id` > 2");
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "csvprice_pro_profiles` WHERE `profile_id` = " . (int) $data["profile_id"] . " LIMIT 1;");
        if ($result->num_rows) {
            return $result->row;
        }
        return false;
    }
    public function getProfile($profile_id = 0, $key = "")
    {
        if ($profile_id == 0) {
            $result = $this->db->query("SELECT profile_id, name  FROM `" . DB_PREFIX . "csvprice_pro_profiles` WHERE `key` = '" . $key . "';");
            return $result->rows;
        }
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "csvprice_pro_profiles` WHERE `profile_id` = " . (int) $profile_id . " LIMIT 1;");
        if ($result->num_rows) {
            return json_decode(base64_decode($result->row["value"]), true);
        }
        return "";
    }
}

?>
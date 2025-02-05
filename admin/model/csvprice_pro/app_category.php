<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppCategory extends Model
{
    private $CSV_SEPARATOR = ";";
    private $CSV_ENCLOSURE = "\"";
    private $field_caption;
    private $CategoryMaxLevel = 10;
    private $CoreType = [];
    private $LanguageID;
    private $ImportConfig = [];
    private $ExportConfig = [];
    private $ImageURL = "";
    private $f_tell = 0;
    public function CategoryExport()
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->ExportConfig = $this->model_csvprice_pro_app_setting->getSetting("CategoryExport");
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->LanguageID = $this->ExportConfig["language_id"];
        if (isset($this->ExportConfig["csv_delimiter"]) && $this->ExportConfig["csv_delimiter"]) {
            $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ExportConfig["csv_delimiter"]);
        }
        if (isset($this->ExportConfig["csv_text_delimiter"]) && $this->ExportConfig["csv_text_delimiter"]) {
            $this->CSV_ENCLOSURE = trim(htmlspecialchars_decode($this->ExportConfig["csv_text_delimiter"]));
        }
        if ($this->ExportConfig["image_url"] && defined("HTTP_CATALOG")) {
            $this->ImageURL = HTTP_CATALOG . "image/";
        } else {
            $this->ImageURL = "";
        }
        $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = "";
        $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_ = [];
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_ = [];
        $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_ = [];
        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_ = [];
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.category_id";
        $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ID_";
        if (isset($this->ExportConfig["fields_set"]["_PARENT_ID_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.parent_id";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_PARENT_ID_";
        }
        if (isset($this->ExportConfig["fields_set"]["_NAME_"]) && $this->ExportConfig["category_parent"] == 1) {
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_NAME_";
        } else {
            if (isset($this->ExportConfig["fields_set"]["_NAME_"]) && $this->ExportConfig["category_parent"] == 0) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd.name";
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["cd"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["cd"] = " LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (c.category_id = cd.category_id) ";
                }
                if (!isset($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["cd"])) {
                    $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["cd"] = " cd.language_id = '" . (int) $this->LanguageID . "' ";
                }
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_NAME_";
            }
        }
        if (isset($this->ExportConfig["fields_set"]["_SEO_KEYWORD_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "ua.keyword";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["ua"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["ua"] = " LEFT JOIN `" . DB_PREFIX . "url_alias` ua ON (CONCAT('category_id=', c.category_id) = ua.query) ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SEO_KEYWORD_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_TITLE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd.seo_title";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_TITLE_";
        }
        if (isset($this->ExportConfig["fields_set"]["_HTML_H1_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd.seo_h1";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_HTML_H1_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_KEYWORDS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd.meta_keyword";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_KEYWORDS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_DESCRIPTION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd.meta_description";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_DESCRIPTION_";
        }
        if (isset($this->ExportConfig["fields_set"]["_DESCRIPTION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd.description";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_DESCRIPTION_";
        }
        if (isset($this->ExportConfig["fields_set"]["_IMAGE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.image";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_IMAGE_";
        }
        if (isset($this->ExportConfig["fields_set"]["_TOP_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.top";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_TOP_";
        }
        if (isset($this->ExportConfig["fields_set"]["_COLUMN_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.column";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_COLUMN_";
        }
        if (isset($this->ExportConfig["fields_set"]["_SORT_ORDER_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.sort_order";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SORT_ORDER_";
        }
        if (isset($this->ExportConfig["fields_set"]["_STATUS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.status";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STATUS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_TITLE_"]) || isset($this->ExportConfig["fields_set"]["_HTML_H1_"]) || isset($this->ExportConfig["fields_set"]["_META_KEYWORDS_"]) || isset($this->ExportConfig["fields_set"]["_META_DESCRIPTION_"]) || isset($this->ExportConfig["fields_set"]["_DESCRIPTION_"])) {
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["cd"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["cd"] = " LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (c.category_id = cd.category_id) ";
            }
            if (!isset($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["cd"])) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["cd"] = " cd.language_id = '" . (int) $this->LanguageID . "' ";
            }
        }
        $_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_ = $this->model_csvprice_pro_app_setting->getMacros("CategoryMacros");
        if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category"])) {
            foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->ExportConfig["fields_set"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c." . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"];
                    $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"];
                }
            }
        }
        if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category_description"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category_description"])) {
            foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->ExportConfig["fields_set"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cd." . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"];
                    if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["cd"])) {
                        $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["cd"] = " LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (c.category_id = cd.category_id) ";
                    }
                    if (!isset($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["cd"])) {
                        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["cd"] = " cd.language_id = '" . (int) $this->LanguageID . "' ";
                    }
                    $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"];
                }
            }
        }
        if (!empty($this->ExportConfig["from_category"])) {
            $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = implode(",", $this->ExportConfig["from_category"]);
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " (c.category_id IN (" . $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ . ")) ";
        }
        if (!empty($this->ExportConfig["from_store"])) {
            $_obfuscated_0D2A5C1112352A131C031B1D19291E2D2F2B043F353C11_ = implode(",", $this->ExportConfig["from_store"]);
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " (c2s.store_id IN (" . $_obfuscated_0D2A5C1112352A131C031B1D19291E2D2F2B043F353C11_ . ")) ";
            $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_[] = " LEFT JOIN `" . DB_PREFIX . "category_to_store` c2s ON (c.category_id = c2s.category_id) ";
        }
        if (0 < count($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_)) {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = " WHERE " . implode("AND", $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_) . " ";
        } else {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = "";
        }
        $sql = "SELECT DISTINCT " . implode(",", $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_) . " FROM " . DB_PREFIX . "category c " . implode(" ", $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_) . $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ . " ORDER BY c.category_id ";
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
        $_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_ = ini_get("default_charset");
        ini_set("default_charset", "UTF-8");
        $file = $_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_ . "/" . uniqid() . ".csv";
        if (($handle = fopen($file, "w")) !== false) {
            if (isset($this->ExportConfig["fields_set"]["_FILTERS_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_FILTERS_";
            }
            if (isset($this->ExportConfig["fields_set"]["_STORE_ID_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STORE_ID_";
            }
            fputcsv($handle, $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
            foreach ($query->rows as $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_) {
                if (isset($this->ExportConfig["fields_set"]["_NAME_"]) && $this->ExportConfig["category_parent"] == 1) {
                    $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_ = $this->getCategoryNameByID($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["category_id"]);
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = array_merge(["name" => (string) $_obfuscated_0D050C1C06091D223D381424213C092D074012063E2E11_], $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_);
                }
                $_obfuscated_0D013F0633052B2B2A3B2706120D1C150A302213283F32_ = $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["category_id"];
                unset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["category_id"]);
                if (isset($this->ExportConfig["fields_set"]["_PARENT_ID_"])) {
                    $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ = $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["parent_id"];
                    unset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["parent_id"]);
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = array_merge(["parent_id" => $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_], $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_);
                }
                $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = array_merge(["category_id" => $_obfuscated_0D013F0633052B2B2A3B2706120D1C150A302213283F32_], $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_);
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["name"]) && $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["name"] != "") {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["name"] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["name"]);
                }
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["description"]) && $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["description"] != "") {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["description"] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["description"]);
                }
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_description"]) && $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_description"] != "") {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_description"] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_description"]);
                }
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_keyword"]) && $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_keyword"] != "") {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_keyword"] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["meta_keyword"]);
                }
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_title"]) && $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_title"] != "") {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_title"] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_title"]);
                }
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_h1"]) && $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_h1"] != "") {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_h1"] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["seo_h1"]);
                }
                if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category"])) {
                    foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                        if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                            $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]);
                        }
                    }
                }
                if (isset($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category_description"]) && 0 < count($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category_description"])) {
                    foreach ($_obfuscated_0D061C13212D1F222D0A38100D220A31292C2C09132211_[DB_PREFIX . "category_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                        if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                            $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]] = htmlspecialchars_decode($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]);
                        }
                    }
                }
                if (isset($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["image"]) && !empty($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["image"])) {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["image"] = $this->ImageURL . $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["image"];
                }
                if (isset($this->ExportConfig["fields_set"]["_FILTERS_"])) {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["filters"] = $this->getCategoryFilters($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["category_id"]);
                }
                if (isset($this->ExportConfig["fields_set"]["_STORE_ID_"])) {
                    $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["stores"] = $this->getCategoryStoresID($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_["category_id"]);
                }
                fputcsv($handle, $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
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
    }
    private function getCategoryNameByID(&$category_id)
    {
        $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_ = " LIMIT 1";
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = [];
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
        $sql .= " WHERE c.category_id = '" . (int) $category_id . "' " . $_obfuscated_0D0502182C290411290412031B252C2A24113D02052F32_;
        $result = $this->db->query($sql);
        if (0 < $result->num_rows && isset($result->row["path"])) {
            $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = $this->getPathNameByCategory($result->row["path"]);
            return $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_;
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
    private function getCategoryFilters($catgeory_id)
    {
        $_obfuscated_0D1521211B312D192C282440011E1D0A5B2D1C1B370101_ = [];
        $sql = "SELECT CONCAT(fgd.name, '|', fd.name) AS c_filters\n\t\t\tFROM `" . DB_PREFIX . "category_filter` cf\n\t\t\tLEFT JOIN `" . DB_PREFIX . "filter_description` fd ON (cf.filter_id = fd.filter_id AND fd.language_id = '" . (int) $this->LanguageID . "')\n\t\t\tLEFT JOIN `" . DB_PREFIX . "filter_group_description` fgd ON (fd.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int) $this->LanguageID . "')\n\t\t  \tWHERE cf.category_id = " . (int) $catgeory_id;
        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $_obfuscated_0D1521211B312D192C282440011E1D0A5B2D1C1B370101_[] = $result["c_filters"];
        }
        return implode("\n", $_obfuscated_0D1521211B312D192C282440011E1D0A5B2D1C1B370101_);
    }
    private function getCategoryStoresID($catgeory_id)
    {
        $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ = [];
        $query = $this->db->query("SELECT store_id FROM `" . DB_PREFIX . "category_to_store` c2s WHERE c2s.category_id = " . (int) $catgeory_id);
        foreach ($query->rows as $result) {
            $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_[] = $result["store_id"];
        }
        return implode(",", $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_);
    }
    public function CategoryImport($data_a)
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->ImportConfig = $this->model_csvprice_pro_app_setting->getSetting("CategoryImport");
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->LanguageID = $this->ImportConfig["language_id"];
        if (isset($this->ImportConfig["csv_delimiter"]) && $this->ImportConfig["csv_delimiter"]) {
            $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ImportConfig["csv_delimiter"]);
        }
        if (isset($this->ImportConfig["csv_text_delimiter"]) && $this->ImportConfig["csv_text_delimiter"]) {
            $this->CSV_ENCLOSURE = trim(htmlspecialchars_decode($this->ImportConfig["csv_text_delimiter"]));
        }
        $this->CustomFields = $this->model_csvprice_pro_app_setting->getMacros("CategoryMacros");
        if (($this->field_caption = $this->getFieldCaption($data_a["file_name"])) === NULL) {
            $result["error_import"] = $this->language->get("error_import_field_caption");
            return $result;
        }
        if (isset($this->field_caption["_FILTERS_"])) {
            $this->load->model("csvprice_pro/lib_product_filter");
        }
        $this->ImportConfig["fill_category"] = 0;
        $this->ImportConfig["column"] = 0;
        $this->ImportConfig["top"] = 0;
        $getCategoryID = "getCategoryID";
        if ($this->ImportConfig["key_field"] == "_ID_" && isset($this->field_caption["_ID_"])) {
            $getCategoryID = "getCategoryID_Id";
        } else {
            if ($this->ImportConfig["key_field"] == "_NAME_" && isset($this->field_caption["_NAME_"])) {
                $getCategoryID = "getCategoryID_Name";
            }
        }
        if ($this->ImportConfig["category_disable"] == 1) {
            $this->db->query("UPDATE `" . DB_PREFIX . "category` SET status = 0");
        }
        $_obfuscated_0D102331393633303B250530165C3D1D15250C211F1401_ = 0;
        $_obfuscated_0D3C25332410131B3F37131E2922290A2E093E12395C01_ = 0;
        $_obfuscated_0D24140B3E1B160D102C04350A171D190C1A4011151D11_ = 0;
        $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_ = 0;
        $result = NULL;
        if (($handle = fopen($data_a["file_name"], "r")) !== false) {
            fseek($handle, $this->f_tell);
            while (($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_ = fgetcsv($handle, 0, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== false) {
                if (substr($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0], 0, 3) == pack("CCC", 239, 187, 191)) {
                    $_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0] = substr($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_[0], 3);
                }
                $_obfuscated_0D102331393633303B250530165C3D1D15250C211F1401_++;
                if (count($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_) == count($this->field_caption)) {
                    if ($this->ImportConfig["mode"] != 3 && ($category_id = $this->{$getCategoryID}($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) !== false) {
                        if ($this->ImportConfig["mode"] == 1 || $this->ImportConfig["mode"] == 2) {
                            if ($this->updateCategory($category_id, $_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                                $_obfuscated_0D3C25332410131B3F37131E2922290A2E093E12395C01_++;
                            } else {
                                $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_++;
                            }
                        }
                    } else {
                        if ($this->ImportConfig["mode"] == 1 || $this->ImportConfig["mode"] == 3) {
                            if ($this->addCategory($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                                $_obfuscated_0D24140B3E1B160D102C04350A171D190C1A4011151D11_++;
                            } else {
                                $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_++;
                            }
                        } else {
                            $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_++;
                        }
                    }
                } else {
                    $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_++;
                }
            }
            fclose($handle);
            $result["total"] = $_obfuscated_0D102331393633303B250530165C3D1D15250C211F1401_;
            $result["update"] = $_obfuscated_0D3C25332410131B3F37131E2922290A2E093E12395C01_;
            $result["insert"] = $_obfuscated_0D24140B3E1B160D102C04350A171D190C1A4011151D11_;
            $result["error"] = $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_;
        }
        if ($this->CoreType["REPAIR_CATEGORIES"]) {
            $this->load->model("catalog/category");
            $this->model_catalog_category->repairCategories();
        }
        return $result;
    }
    private function updateCategory($category_id, &$data_a)
    {
        if (empty($category_id)) {
            return false;
        }
        $sql = "";
        if (isset($this->field_caption["_PARENT_ID_"])) {
            $sql .= " `parent_id` = '" . (int) $data_a[$this->field_caption["_PARENT_ID_"]] . "',";
        }
        if (isset($this->field_caption["_IMAGE_"])) {
            if ($this->ImportConfig["image_download"]) {
                $path = $this->model_csvprice_pro_app_setting->getRandDir();
                $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->field_caption["_IMAGE_"]], $path, $category_id);
            } else {
                $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $data_a[$this->field_caption["_IMAGE_"]];
            }
            $sql .= " `image` = '" . $this->db->escape($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_) . "',";
        }
        if (isset($this->field_caption["_COLUMN_"])) {
            $sql .= " `column` = '" . (int) $data_a[$this->field_caption["_COLUMN_"]] . "',";
        }
        if (isset($this->field_caption["_TOP_"])) {
            $sql .= " `top` = '" . (int) $data_a[$this->field_caption["_TOP_"]] . "',";
        }
        if (isset($this->field_caption["_STATUS_"])) {
            $sql .= " `status` = '" . (int) $data_a[$this->field_caption["_STATUS_"]] . "',";
        }
        if (isset($this->field_caption["_SORT_ORDER_"])) {
            $sql .= " `sort_order` = '" . (int) $data_a[$this->field_caption["_SORT_ORDER_"]] . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "category"]) && 0 < count($this->CustomFields[DB_PREFIX . "category"])) {
            foreach ($this->CustomFields[DB_PREFIX . "category"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " `" . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . "` = '" . $this->db->escape($data_a[$this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]]) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $sql = "UPDATE `" . DB_PREFIX . "category` SET " . mb_substr($sql, 0, -1) . " WHERE category_id = " . (int) $category_id;
            $this->db->query($sql);
        }
        $result = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "category_description` WHERE category_id = '" . (int) $category_id . "' AND language_id = '" . (int) $this->LanguageID . "';");
        if ($result->num_rows == 0) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET category_id = '" . (int) $category_id . "',  language_id = '" . (int) $this->LanguageID . "';");
        }
        $sql = "";
        if (isset($this->field_caption["_NAME_"]) && !empty($data_a[$this->field_caption["_NAME_"]])) {
            $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode($this->ImportConfig["delimiter_category"], $data_a[$this->field_caption["_NAME_"]]);
            $_obfuscated_0D142C2B3731370D050D1B3B19033C15301B3636053411_ = end($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_);
            $sql .= " name = '" . $this->db->escape(htmlspecialchars($_obfuscated_0D142C2B3731370D050D1B3B19033C15301B3636053411_)) . "',";
        }
        if ($this->CoreType["CATEGORY_SEO_TITLE"] && isset($this->field_caption["_META_TITLE_"])) {
            $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_TITLE_"]])) . "',";
        }
        if ($this->CoreType["CATEGORY_SEO_H1"] && isset($this->field_caption["_HTML_H1_"])) {
            $sql .= " seo_h1 = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_HTML_H1_"]])) . "',";
        }
        if (isset($this->field_caption["_META_KEYWORDS_"])) {
            $sql .= " meta_keyword = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_KEYWORDS_"]])) . "',";
        }
        if (isset($this->field_caption["_META_DESCRIPTION_"])) {
            $sql .= " meta_description = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_DESCRIPTION_"]])) . "',";
        }
        if (isset($this->field_caption["_DESCRIPTION_"])) {
            $sql .= " description = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_DESCRIPTION_"]])) . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "category_description"]) && 0 < count($this->CustomFields[DB_PREFIX . "category_description"])) {
            foreach ($this->CustomFields[DB_PREFIX . "category_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $sql = "UPDATE `" . DB_PREFIX . "category_description` SET " . mb_substr($sql, 0, -1) . " WHERE category_id = '" . (int) $category_id . "' AND language_id = '" . (int) $this->LanguageID . "'";
            $this->db->query($sql);
        }
        if (isset($this->field_caption["_SEO_KEYWORD_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'category_id=" . (int) $category_id . "'");
            if (!empty($data_a[$this->field_caption["_SEO_KEYWORD_"]])) {
                $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape($data_a[$this->field_caption["_SEO_KEYWORD_"]]) . "'";
                $this->db->query($sql);
            }
        }
        if (isset($this->field_caption["_STORE_ID_"]) && $data_a[$this->field_caption["_STORE_ID_"]] != "") {
            $_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ = explode(",", $data_a[$this->field_caption["_STORE_ID_"]]);
        } else {
            $_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ = $this->ImportConfig["to_store"];
        }
        if (!empty($_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int) $category_id . "'");
            foreach ($_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
            }
        }
        if (isset($this->field_caption["_FILTERS_"])) {
            $this->model_csvprice_pro_lib_product_filter->addCategoryFilters($category_id, $data_a[$this->field_caption["_FILTERS_"]], $this->LanguageID);
        }
        return true;
    }
    private function addCategory(&$data_a)
    {
        if (!isset($data_a[$this->field_caption["_NAME_"]]) || trim($data_a[$this->field_caption["_NAME_"]]) == "") {
            return false;
        }
        $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_ = $this->addProductCategory($data_a[$this->field_caption["_NAME_"]]);
        if (empty($_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_)) {
            return false;
        }
        $category_id = end($_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_);
        $sql = "";
        if (isset($this->field_caption["_PARENT_ID_"])) {
            $sql .= " `parent_id` = '" . (int) $data_a[$this->field_caption["_PARENT_ID_"]] . "',";
        }
        if (isset($this->field_caption["_IMAGE_"])) {
            if ($this->ImportConfig["image_download"]) {
                $path = $this->model_csvprice_pro_app_setting->getRandDir();
                $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->field_caption["_IMAGE_"]], $path, $category_id);
            } else {
                $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $data_a[$this->field_caption["_IMAGE_"]];
            }
            $sql .= " `image` = '" . $this->db->escape($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_) . "',";
        }
        if (isset($this->field_caption["_COLUMN_"])) {
            $sql .= " `column` = '" . (int) $data_a[$this->field_caption["_COLUMN_"]] . "',";
        }
        if (isset($this->field_caption["_TOP_"])) {
            $sql .= " `top` = '" . (int) $data_a[$this->field_caption["_TOP_"]] . "',";
        }
        if (isset($this->field_caption["_STATUS_"])) {
            $sql .= " `status` = '" . (int) $data_a[$this->field_caption["_STATUS_"]] . "',";
        } else {
            $sql .= " `status` = '" . (int) $this->ImportConfig["status"] . "',";
        }
        if (isset($this->field_caption["_SORT_ORDER_"])) {
            $sql .= " `sort_order` = '" . (int) $data_a[$this->field_caption["_SORT_ORDER_"]] . "',";
        } else {
            $sql .= " `sort_order` = '" . (int) $this->ImportConfig["sort_order"] . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "category"]) && 0 < count($this->CustomFields[DB_PREFIX . "category"])) {
            foreach ($this->CustomFields[DB_PREFIX . "category"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " `" . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . "` = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $sql = "UPDATE `" . DB_PREFIX . "category` SET " . mb_substr($sql, 0, -1) . " WHERE category_id = " . (int) $category_id;
            $this->db->query($sql);
        }
        $sql = "";
        if ($this->CoreType["CATEGORY_SEO_TITLE"] && isset($this->field_caption["_META_TITLE_"])) {
            $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_TITLE_"]])) . "',";
        }
        if ($this->CoreType["CATEGORY_SEO_H1"] && isset($this->field_caption["_HTML_H1_"])) {
            $sql .= " seo_h1 = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_HTML_H1_"]])) . "',";
        }
        if (isset($this->field_caption["_META_KEYWORDS_"])) {
            $sql .= " meta_keyword = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_KEYWORDS_"]])) . "',";
        }
        if (isset($this->field_caption["_META_DESCRIPTION_"])) {
            $sql .= " meta_description = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_DESCRIPTION_"]])) . "',";
        }
        if (isset($this->field_caption["_DESCRIPTION_"])) {
            $sql .= " description = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_DESCRIPTION_"]])) . "',";
        }
        if (isset($this->CustomFields[DB_PREFIX . "category_description"]) && 0 < count($this->CustomFields[DB_PREFIX . "category_description"])) {
            foreach ($this->CustomFields[DB_PREFIX . "category_description"] as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                if (isset($this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]])) {
                    $sql .= " " . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"] . " = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]]])) . "',";
                }
            }
        }
        if (!empty($sql)) {
            $sql = "UPDATE `" . DB_PREFIX . "category_description` SET " . mb_substr($sql, 0, -1) . " WHERE category_id = '" . (int) $category_id . "' AND language_id = '" . (int) $this->LanguageID . "'";
            $this->db->query($sql);
        }
        if (isset($this->field_caption["_SEO_KEYWORD_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'category_id=" . (int) $category_id . "'");
            if (!empty($data_a[$this->field_caption["_SEO_KEYWORD_"]])) {
                $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape($data_a[$this->field_caption["_SEO_KEYWORD_"]]) . "'";
                $this->db->query($sql);
            }
        }
        if (isset($this->field_caption["_FILTERS_"])) {
            $this->model_csvprice_pro_lib_product_filter->addCategoryFilters($category_id, $data_a[$this->field_caption["_FILTERS_"]], $this->LanguageID);
        }
        return true;
    }
    private function addProductCategory($data_a)
    {
        if (empty($data_a)) {
            return 0;
        }
        $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_ = [];
        if (isset($this->ImportConfig["to_store"]) && !empty($this->ImportConfig["to_store"])) {
            $_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ = $this->ImportConfig["to_store"];
        } else {
            $_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ = [];
        }
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
                    foreach ($_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
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
                    foreach ($_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
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
    private function getCategoryID(&$data_a)
    {
        return false;
    }
    private function getCategoryID_Id(&$data_a)
    {
        if (empty($data_a[$this->field_caption["_ID_"]])) {
            return false;
        }
        $result = $this->db->query("SELECT category_id FROM `" . DB_PREFIX . "category` WHERE category_id = '" . (int) $data_a[$this->field_caption["_ID_"]] . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["category_id"];
        }
        return false;
    }
    private function getCategoryID_Name(&$data_a)
    {
        if (empty($data_a[$this->field_caption["_NAME_"]])) {
            return false;
        }
        $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = $data_a[$this->field_caption["_NAME_"]];
        $_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ = explode($this->ImportConfig["delimiter_category"], $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_);
        if (count($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_) == 1) {
            $result = $this->db->query("SELECT cd.category_id FROM `" . DB_PREFIX . "category_description` cd WHERE LOWER(cd.name) = LOWER('" . $this->db->escape(htmlspecialchars(trim($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_[0], " \n\t\r"))) . "') LIMIT 1");
            if (isset($result->num_rows) && 0 < $result->num_rows) {
                return $result->row["category_id"];
            }
            return false;
        }
        $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ = 0;
        $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_ = [];
        foreach ($_obfuscated_0D01405C031C011517380324341A2F2A141A1624013432_ as $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_) {
            $_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_ = trim($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_, " \n\t\r");
            $result = $this->db->query("SELECT cd.category_id FROM `" . DB_PREFIX . "category_description` cd LEFT JOIN `" . DB_PREFIX . "category` c ON (c.category_id = cd.category_id) WHERE LOWER(cd.name) = LOWER('" . $this->db->escape(htmlspecialchars($_obfuscated_0D273F092512010308083D13022713311C1840380A0E22_)) . "') AND c.parent_id = '" . $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ . "' LIMIT 1");
            if (isset($result->num_rows) && 0 < $result->num_rows) {
                $_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_[] = $result->row["category_id"];
                $_obfuscated_0D3E1A181F3640371F0308192A1C371A5B393E40120A22_ = $result->row["category_id"];
            } else {
                return false;
            }
        }
        if (!empty($_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_)) {
            return end($_obfuscated_0D0F0E0D0B062A1B24273B26290436073C28313E3E2D01_);
        }
        return false;
    }
    private function getFieldCaption($file_name)
    {
        $result = NULL;
        $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_ = [];
        if (($handle = fopen($file_name, "r")) !== false) {
            if (($field_caption = fgetcsv($handle, 0, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE)) !== false) {
                if (substr($field_caption[0], 0, 3) == pack("CCC", 239, 187, 191)) {
                    $field_caption[0] = substr($field_caption[0], 3);
                }
                for ($i = 0; $i < count($field_caption); $i++) {
                    $field_caption[$i] = trim($field_caption[$i], " \t\n\r");
                    if (empty($field_caption[$i]) || in_array($field_caption[$i], $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_)) {
                        $field_caption[$i] = "undefine_" . $i;
                    } else {
                        $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_[] = $field_caption[$i];
                    }
                }
                $result = array_flip($field_caption);
                $this->f_tell = ftell($handle);
            }
            fclose($handle);
        }
        return $result;
    }
}

?>
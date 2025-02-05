<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppManufacturer extends Model
{
    private $CSV_SEPARATOR = ";";
    private $CSV_ENCLOSURE = "\"";
    private $field_caption;
    private $CoreType = [];
    private $LanguageID;
    private $ImportConfig = [];
    private $ExportConfig = [];
    private $ImageURL = "";
    private $f_tell = 0;
    public function ManufacturerExport()
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->ExportConfig = $this->model_csvprice_pro_app_setting->getSetting("ManufacturerExport");
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
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "m.manufacturer_id";
        $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ID_";
        if (isset($this->ExportConfig["fields_set"]["_NAME_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "m.name";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_NAME_";
        }
        if (isset($this->ExportConfig["fields_set"]["_SEO_KEYWORD_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "ua.keyword";
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["ua"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["ua"] = " LEFT JOIN `" . DB_PREFIX . "url_alias` ua ON (CONCAT('manufacturer_id=', m.manufacturer_id) = ua.query) ";
            }
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SEO_KEYWORD_";
        }
        if (isset($this->ExportConfig["fields_set"]["_HTML_H1_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "md.seo_h1";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_HTML_H1_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_KEYWORDS_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "md.meta_keyword";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_KEYWORDS_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_TITLE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "md.seo_title";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_TITLE_";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_DESCRIPTION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "md.meta_description";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_META_DESCRIPTION_";
        }
        if (isset($this->ExportConfig["fields_set"]["_DESCRIPTION_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "md.description";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_DESCRIPTION_";
        }
        if (isset($this->ExportConfig["fields_set"]["_IMAGE_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "m.image";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_IMAGE_";
        }
        if (isset($this->ExportConfig["fields_set"]["_SORT_ORDER_"])) {
            $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "m.sort_order";
            $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_SORT_ORDER_";
        }
        if (!empty($this->ExportConfig["from_store"])) {
            $_obfuscated_0D2A5C1112352A131C031B1D19291E2D2F2B043F353C11_ = implode(",", $this->ExportConfig["from_store"]);
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " (m2s.store_id IN (" . $_obfuscated_0D2A5C1112352A131C031B1D19291E2D2F2B043F353C11_ . ")) ";
            $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_[] = " LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` m2s ON (m.manufacturer_id = m2s.manufacturer_id) ";
        }
        if (!empty($this->ExportConfig["product_manufacturer"])) {
            $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " m.manufacturer_id IN (" . implode(",", $this->ExportConfig["product_manufacturer"]) . ") ";
        }
        if (isset($this->ExportConfig["fields_set"]["_META_TITLE_"]) || isset($this->ExportConfig["fields_set"]["_HTML_H1_"]) || isset($this->ExportConfig["fields_set"]["_META_KEYWORDS_"]) || isset($this->ExportConfig["fields_set"]["_META_DESCRIPTION_"]) || isset($this->ExportConfig["fields_set"]["_DESCRIPTION_"])) {
            if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["md"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["md"] = " LEFT JOIN `" . DB_PREFIX . "manufacturer_description` md ON (m.manufacturer_id = md.manufacturer_id) ";
            }
            if (!isset($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["md"])) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["md"] = " md.language_id = '" . (int) $this->LanguageID . "' ";
            }
        }
        if (0 < count($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_)) {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = " WHERE " . implode("AND", $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_) . " ";
        } else {
            $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = "";
        }
        $sql = "SELECT DISTINCT " . implode(",", $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_) . " FROM " . DB_PREFIX . "manufacturer m " . implode(" ", $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_) . $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ . " ORDER BY m.manufacturer_id ";
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
            if (isset($this->ExportConfig["fields_set"]["_STORE_ID_"])) {
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_STORE_ID_";
            }
            fputcsv($handle, $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
            foreach ($query->rows as $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_) {
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["name"]) && $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["name"] != "") {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["name"] = htmlspecialchars_decode($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["name"]);
                }
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["description"]) && $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["description"] != "") {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["description"] = htmlspecialchars_decode($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["description"]);
                }
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_description"]) && $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_description"] != "") {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_description"] = htmlspecialchars_decode($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_description"]);
                }
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_keyword"]) && $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_keyword"] != "") {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_keyword"] = htmlspecialchars_decode($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["meta_keyword"]);
                }
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_title"]) && $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_title"] != "") {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_title"] = htmlspecialchars_decode($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_title"]);
                }
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_h1"]) && $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_h1"] != "") {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_h1"] = htmlspecialchars_decode($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["seo_h1"]);
                }
                if (isset($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["image"]) && !empty($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["image"])) {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["image"] = $this->ImageURL . $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["image"];
                }
                if (isset($this->ExportConfig["fields_set"]["_STORE_ID_"])) {
                    $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["stores"] = $this->getManufacturerStoresID($_obfuscated_0D1034193E5C391108392735290429213B382C30222722_["manufacturer_id"]);
                }
                fputcsv($handle, $_obfuscated_0D1034193E5C391108392735290429213B382C30222722_, $this->CSV_SEPARATOR, $this->CSV_ENCLOSURE);
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
    private function getManufacturerStoresID($manufacturer_id)
    {
        $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_ = [];
        $query = $this->db->query("SELECT store_id FROM `" . DB_PREFIX . "manufacturer_to_store` m2s WHERE m2s.manufacturer_id = " . (int) $manufacturer_id);
        foreach ($query->rows as $result) {
            $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_[] = $result["store_id"];
        }
        return implode(",", $_obfuscated_0D15321723272E25320A3D1510103F21152F3212293E22_);
    }
    public function ManufacturerImport($data_a)
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->ImportConfig = $this->model_csvprice_pro_app_setting->getSetting("ManufacturerImport");
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->LanguageID = $this->ImportConfig["language_id"];
        if (isset($this->ImportConfig["csv_delimiter"]) && $this->ImportConfig["csv_delimiter"]) {
            $this->CSV_SEPARATOR = htmlspecialchars_decode($this->ImportConfig["csv_delimiter"]);
        }
        if (isset($this->ImportConfig["csv_text_delimiter"]) && $this->ImportConfig["csv_text_delimiter"]) {
            $this->CSV_ENCLOSURE = trim(htmlspecialchars_decode($this->ImportConfig["csv_text_delimiter"]));
        }
        if (($this->field_caption = $this->getFieldCaption($data_a["file_name"])) === NULL) {
            $result["error_import"] = $this->language->get("error_import_field_caption");
            return $result;
        }
        $getManufacturerID = "getManufacturerID";
        if ($this->ImportConfig["key_field"] == "_ID_" && isset($this->field_caption["_ID_"])) {
            $getManufacturerID = "getManufacturerID_Id";
        } else {
            if ($this->ImportConfig["key_field"] == "_NAME_" && isset($this->field_caption["_NAME_"])) {
                $getManufacturerID = "getManufacturerID_Name";
            }
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
                    if ($this->ImportConfig["mode"] != 3 && ($manufacturer_id = $this->{$getManufacturerID}($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) !== false) {
                        if ($this->ImportConfig["mode"] == 1 || $this->ImportConfig["mode"] == 2) {
                            if ($this->updateManufacturer($manufacturer_id, $_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
                                $_obfuscated_0D3C25332410131B3F37131E2922290A2E093E12395C01_++;
                            } else {
                                $_obfuscated_0D23083E240A072C061D2F1B18303E090B1A12375B3D11_++;
                            }
                        }
                    } else {
                        if ($this->ImportConfig["mode"] == 1 || $this->ImportConfig["mode"] == 3) {
                            if ($this->addManufacturer($_obfuscated_0D28132D2810393C24143F102B0F3C22252231110D0F01_)) {
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
        return $result;
    }
    private function updateManufacturer($manufacturer_id, &$data_a)
    {
        $sql = "";
        if (isset($this->field_caption["_NAME_"])) {
            $sql .= " `name` = '" . $this->db->escape(htmlspecialchars(trim($data_a[$this->field_caption["_NAME_"]], " \n\t\r"))) . "',";
        }
        if (isset($this->field_caption["_IMAGE_"])) {
            if ($this->ImportConfig["image_download"]) {
                $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = $this->model_csvprice_pro_app_setting->getRandDir();
                $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->field_caption["_IMAGE_"]], $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_, $manufacturer_id);
            } else {
                $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $data_a[$this->field_caption["_IMAGE_"]];
            }
            $sql .= " `image` = '" . $this->db->escape($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_) . "',";
        }
        if (isset($this->field_caption["_SORT_ORDER_"])) {
            $sql .= " `sort_order` = '" . (int) $data_a[$this->field_caption["_SORT_ORDER_"]] . "',";
        } else {
            $sql .= " `sort_order` = '" . (int) $this->ImportConfig["sort_order"] . "',";
        }
        if (!empty($sql)) {
            $this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET " . mb_substr($sql, 0, -1) . " WHERE manufacturer_id = '" . (int) $manufacturer_id . "';");
        }
        if ($this->CoreType["MANUFACTURER_DESCRIPTION"] && !empty($manufacturer_id)) {
            $result = $this->db->query("SELECT 1 FROM `" . DB_PREFIX . "manufacturer_description` WHERE manufacturer_id = '" . (int) $manufacturer_id . "' AND language_id = '" . (int) $this->LanguageID . "';");
            if ($result->num_rows == 0) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_description` SET manufacturer_id = '" . (int) $manufacturer_id . "',  language_id = '" . (int) $this->LanguageID . "';");
            }
            $sql = "";
            if (isset($this->field_caption["_META_TITLE_"])) {
                $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_TITLE_"]])) . "',";
            }
            if (isset($this->field_caption["_HTML_H1_"])) {
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
            if (!empty($sql)) {
                $this->db->query("UPDATE `" . DB_PREFIX . "manufacturer_description` SET " . mb_substr($sql, 0, -1) . " WHERE manufacturer_id = '" . (int) $manufacturer_id . "' AND language_id = '" . (int) $this->LanguageID . "';");
            }
        }
        if (isset($this->field_caption["_SEO_KEYWORD_"])) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'manufacturer_id=" . (int) $manufacturer_id . "'");
            if (!empty($data_a[$this->field_caption["_SEO_KEYWORD_"]])) {
                $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'manufacturer_id=" . (int) $manufacturer_id . "', keyword = '" . $this->db->escape($data_a[$this->field_caption["_SEO_KEYWORD_"]]) . "'";
                $this->db->query($sql);
            }
        }
        if (isset($this->field_caption["_STORE_ID_"]) && $data_a[$this->field_caption["_STORE_ID_"]] != "") {
            $_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ = explode(",", $data_a[$this->field_caption["_STORE_ID_"]]);
            $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int) $manufacturer_id . "';");
            foreach ($_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
            }
        } else {
            if (isset($this->ImportConfig["to_store"]) && !empty($this->ImportConfig["to_store"])) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int) $manufacturer_id . "';");
                foreach ($this->ImportConfig["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                }
            }
        }
        return true;
    }
    private function addManufacturer(&$data_a)
    {
        if (!isset($data_a[$this->field_caption["_NAME_"]]) || trim($data_a[$this->field_caption["_NAME_"]], " \n\t\r") == "") {
            return false;
        }
        $sql = "";
        if (isset($this->field_caption["_ID_"]) && (int) $this->ImportConfig["import_id"] == 1 && !$this->getManufacturerID_Id($data_a)) {
            $id = (int) trim($data_a[$this->field_caption["_ID_"]]);
            if (!empty($id)) {
                $sql .= " `manufacturer_id` = '" . $this->db->escape($id) . "',";
            }
        }
        if (isset($this->field_caption["_NAME_"])) {
            $sql .= " `name` = '" . $this->db->escape(htmlspecialchars(trim($data_a[$this->field_caption["_NAME_"]], " \n\t\r"))) . "',";
        }
        if (isset($this->field_caption["_SORT_ORDER_"])) {
            $sql .= " `sort_order` = '" . (int) $data_a[$this->field_caption["_SORT_ORDER_"]] . "',";
        } else {
            $sql .= " `sort_order` = '" . (int) $this->ImportConfig["sort_order"] . "',";
        }
        if (!empty($sql)) {
            $sql = "INSERT INTO `" . DB_PREFIX . "manufacturer` SET " . mb_substr($sql, 0, -1) . ";";
            $this->db->query($sql);
            $manufacturer_id = $this->db->getLastId();
            if (isset($this->field_caption["_IMAGE_"])) {
                if ($this->ImportConfig["image_download"]) {
                    $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = $this->model_csvprice_pro_app_setting->getRandDir();
                    $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $this->model_csvprice_pro_app_setting->downloadImage($data_a[$this->field_caption["_IMAGE_"]], $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_, $manufacturer_id);
                } else {
                    $_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_ = $data_a[$this->field_caption["_IMAGE_"]];
                }
                $this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET  `image` = '" . $this->db->escape($_obfuscated_0D272A0B223D161D092E31232101033B1A26022E1B1532_) . "' WHERE manufacturer_id = '" . (int) $manufacturer_id . "'");
            }
            if ($this->CoreType["MANUFACTURER_DESCRIPTION"] && !empty($manufacturer_id)) {
                $sql = " manufacturer_id = '" . (int) $manufacturer_id . "',";
                $sql .= " language_id = '" . (int) $this->LanguageID . "',";
                if (isset($this->field_caption["_META_TITLE_"])) {
                    $sql .= " seo_title = '" . $this->db->escape(htmlspecialchars($data_a[$this->field_caption["_META_TITLE_"]])) . "',";
                }
                if (isset($this->field_caption["_HTML_H1_"])) {
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
                $sql = "INSERT INTO `" . DB_PREFIX . "manufacturer_description` SET " . mb_substr($sql, 0, -1);
                $this->db->query($sql);
            }
            if (isset($this->field_caption["_SEO_KEYWORD_"])) {
                $this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE query = 'manufacturer_id=" . (int) $manufacturer_id . "'");
                if (!empty($data_a[$this->field_caption["_SEO_KEYWORD_"]])) {
                    $sql = "INSERT INTO `" . DB_PREFIX . "url_alias` SET query = 'manufacturer_id=" . (int) $manufacturer_id . "', keyword = '" . $this->db->escape($data_a[$this->field_caption["_SEO_KEYWORD_"]]) . "'";
                    $this->db->query($sql);
                }
            }
            if (isset($this->field_caption["_STORE_ID_"]) && !empty($data_a[$this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["_STORE_ID_"]]])) {
                $_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ = explode(",", $data_a[$this->field_caption[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["_STORE_ID_"]]]);
                foreach ($_obfuscated_0D33162D04315B1006123B10260303381C17331E1D1832_ as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                }
            } else {
                if (isset($this->ImportConfig["to_store"]) && !empty($this->ImportConfig["to_store"])) {
                    foreach ($this->ImportConfig["to_store"] as $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '" . (int) $_obfuscated_0D390114191A1A14323D110E0C3C112901391631065B32_ . "'");
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
    private function getManufacturerID(&$data_a)
    {
        return false;
    }
    private function getManufacturerID_Id(&$data_a)
    {
        if (empty($data_a[$this->field_caption["_ID_"]])) {
            return false;
        }
        $result = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "manufacturer` WHERE manufacturer_id = '" . (int) $data_a[$this->field_caption["_ID_"]] . "' LIMIT 1");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            return $result->row["manufacturer_id"];
        }
        return false;
    }
    private function getManufacturerID_Name(&$data_a)
    {
        $_obfuscated_0D393E3F372A3D3439121B170E0C213E29390C0E073932_ = trim($data_a[$this->field_caption["_NAME_"]], " \t\n");
        if (empty($_obfuscated_0D393E3F372A3D3439121B170E0C213E29390C0E073932_)) {
            return 0;
        }
        $result = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "manufacturer` WHERE LOWER(name) = LOWER('" . $this->db->escape(htmlspecialchars($_obfuscated_0D393E3F372A3D3439121B170E0C213E29390C0E073932_)) . "') LIMIT 1");
        if (0 < $result->num_rows) {
            return $result->row["manufacturer_id"];
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
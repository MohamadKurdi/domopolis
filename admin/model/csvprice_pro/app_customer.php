<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppCustomer extends Model
{
    private $CSV_SEPARATOR = ";";
    private $CSV_ENCLOSURE = "\"";
    private $field_caption;
    private $CoreType = [];
    private $LanguageID;
    private $ExportConfig = [];
    public function CustomerExport()
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->ExportConfig = $this->model_csvprice_pro_app_setting->getSetting("CustomerExport");
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = "";
        $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_ = [];
        $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_ = [];
        $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_ = [];
        $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_ = [];
        if ($this->ExportConfig["file_format"] == "csv") {
            if (isset($this->ExportConfig["fields_set"]["_FIRST_NAME_"])) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.firstname";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_FIRST_NAME_";
            }
            if (isset($this->ExportConfig["fields_set"]["_LAST_NAME_"])) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.lastname";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_LAST_NAME_";
            }
            if (isset($this->ExportConfig["fields_set"]["_NAME_"])) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "CONCAT_WS(' ',c.firstname,c.lastname) AS full_name";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_NAME_";
            }
            if (isset($this->ExportConfig["fields_set"]["_EMAIL_"])) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.email";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_EMAIL_";
            }
            if (isset($this->ExportConfig["fields_set"]["_TELEPHONE_"])) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.telephone";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_TELEPHONE_";
            }
            if (isset($this->ExportConfig["fields_set"]["_FAX_"])) {
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "c.fax";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_FAX_";
            }
            if (isset($this->ExportConfig["fields_set"]["_COMPANY_"])) {
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["address"] = " LEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) ";
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "a.company";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_COMPANY_";
            }
            if (isset($this->ExportConfig["fields_set"]["_COUNTRY_"])) {
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["address"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["a"] = " LEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) ";
                }
                $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["country"] = " LEFT JOIN `" . DB_PREFIX . "country` cry ON (a.country_id = cry.country_id) ";
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "cry.name AS country";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_COUNTRY_";
            }
            if (isset($this->ExportConfig["fields_set"]["_CITY_"])) {
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["address"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["a"] = " LEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) ";
                }
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "a.city";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_CITY_";
            }
            if (isset($this->ExportConfig["fields_set"]["_POSTCODE_"])) {
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["address"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["a"] = " LEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) ";
                }
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "a.postcode";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_POSTCODE_";
            }
            if (isset($this->ExportConfig["fields_set"]["_ADDRESS_1_"])) {
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["address"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["a"] = " LEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) ";
                }
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "a.address_1";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ADDRESS_1_";
            }
            if (isset($this->ExportConfig["fields_set"]["_ADDRESS_2_"])) {
                if (!isset($_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["address"])) {
                    $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_["a"] = " LEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) ";
                }
                $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_[] = "a.address_2";
                $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_[] = "_ADDRESS_2_";
            }
            if (isset($this->ExportConfig["date_start"]) && !empty($this->ExportConfig["date_start"]) && isset($this->ExportConfig["date_end"]) && !empty($this->ExportConfig["date_end"])) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["date_start"] = " c.date_added BETWEEN STR_TO_DATE('" . $this->ExportConfig["date_start"] . ":00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('" . $this->ExportConfig["date_end"] . ":00', '%Y-%m-%d %H:%i:%s') ";
            }
            if (isset($this->ExportConfig["customer_group_id"]) && $this->ExportConfig["customer_group_id"] != 0) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["customer_group_id"] = " c.customer_group_id = " . $this->ExportConfig["customer_group_id"];
            }
            if (isset($this->ExportConfig["newsletter"]) && $this->ExportConfig["newsletter"] != 2) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["newsletter"] = " c.newsletter = " . (int) $this->ExportConfig["newsletter"];
            }
            if (isset($this->ExportConfig["status"]) && $this->ExportConfig["status"] != 2) {
                $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_["status"] = " c.status = " . (int) $this->ExportConfig["status"];
            }
            $_obfuscated_0D331D280A0412391A182340232E2E2812181E14371D32_ = "";
            if (0 < count($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_)) {
                $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = " WHERE " . implode(" AND ", $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_) . " ";
            } else {
                $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = "";
            }
            if (empty($_obfuscated_0D1E321E28291423332B160931072B3731220416340322_)) {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = ["error" => "error_fields_not_selected"];
                return $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_;
            }
            $sql = "SELECT DISTINCT " . implode(",", $_obfuscated_0D1E321E28291423332B160931072B3731220416340322_) . " FROM `" . DB_PREFIX . "customer` c " . implode(" ", $_obfuscated_0D122106041C5C282C1905265B1B070C0821102E3C3822_) . $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ . " ORDER BY c.date_added " . $_obfuscated_0D331D280A0412391A182340232E2E2812181E14371D32_;
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
                fputcsv($handle, $_obfuscated_0D082D2D1F353E1307261619150B1332070D1C182A3411_, $this->ExportConfig["csv_delimiter"], $this->CSV_ENCLOSURE);
                foreach ($query->rows as $row) {
                    fputcsv($handle, $row, $this->ExportConfig["csv_delimiter"], $this->CSV_ENCLOSURE);
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
            if ($this->ExportConfig["file_format"] == "vcf") {
                $sql = "SELECT DISTINCT c.firstname,c.lastname,c.email,c.telephone,a.company,cry.name AS country,a.city,a.postcode,a.address_1,a.address_2 \n\t\t\t\t\tFROM `" . DB_PREFIX . "customer` c \n\t\t\t\t\tLEFT JOIN `" . DB_PREFIX . "address` a ON (c.address_id = a.address_id) \n\t\t\t\t\tLEFT JOIN `" . DB_PREFIX . "country` cry ON (a.country_id = cry.country_id)\n\t\t\t\t";
                if (isset($this->ExportConfig["date_start"]) && !empty($this->ExportConfig["date_start"]) && isset($this->ExportConfig["date_end"]) && !empty($this->ExportConfig["date_end"])) {
                    $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " c.date_added BETWEEN STR_TO_DATE('" . $this->ExportConfig["date_start"] . ":00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('" . $this->ExportConfig["date_end"] . ":00', '%Y-%m-%d %H:%i:%s') ";
                }
                if (isset($this->ExportConfig["customer_group_id"]) && $this->ExportConfig["customer_group_id"] != 0) {
                    $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_[] = " c.customer_group_id = " . $this->ExportConfig["customer_group_id"];
                }
                if (0 < count($_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_)) {
                    $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = " WHERE " . implode("AND", $_obfuscated_0D06133D04071106403C5B5C1435190809091705043001_) . " ";
                } else {
                    $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ = "";
                }
                $sql .= $_obfuscated_0D262B2F0C1D3F310C041204333617292125031F370101_ . " ORDER BY c.date_added";
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
                $file = $_obfuscated_0D1709192E5C03131D2310312E371B0B1C3D0C09043311_ . "/" . uniqid() . ".vcf";
                if (($handle = fopen($file, "w")) !== false) {
                    foreach ($query->rows as $row) {
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ = "BEGIN:VCARD\n";
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "VERSION:3.0\n";
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "FN:" . $row["firstname"] . " " . $row["lastname"] . "\n";
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "N:" . str_replace(" ", ";", trim($row["lastname"])) . ";" . $row["firstname"] . "\n";
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "ADR;TYPE=dom, home, postal, parcel:;;" . str_replace(",", " ", $row["address_1"]) . ";" . $row["city"] . ";;" . $row["postcode"] . ";" . $row["country"] . "\n";
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "EMAIL;TYPE=INTERNET:" . $row["email"] . "\n";
                        if (!empty($row["telephone"])) {
                            $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "TEL;TYPE=work, voice, pref, msg:" . $row["telephone"] . "\n";
                        }
                        if (!empty($row["company"])) {
                            $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "ORG:" . $row["company"] . "\n";
                        }
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "REV:" . date("Y-m-d") . "\n";
                        $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_ .= "END:VCARD\n";
                        fwrite($handle, $_obfuscated_0D14190603170D151D045C125B260814373202030D2122_);
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
        }
    }
}

?>
<?php

define("CSVPRICEPRO_VERSION", "3.4.1.0");
class ModelCSVPriceProAppSetting extends Model
{
    private $TmpDirectory = "temp/csvprice_pro/";
    private $error = "";
    private $ImageDataDir = "data/";
    private $FileExtAllowed = [];
    private $DeleteBom = 0;
    public function getCharsets()
    {
        $arr = $this->getSetting("Charsets");
        if (empty($arr)) {
            $arr = ["ISO-8859-1" => "ISO-8859-1 (Western Europe)", "ISO-8859-5" => "ISO-8859-5 (Cyrillc, DOS)", "KOI8-R" => "KOI8-R (Cyrillic, Unix)", "UTF-16LE" => "UNICODE (MS Excel text format)", "UTF-8" => "UTF-8", "windows-1250" => "windows-1250 (Central European languages)", "windows-1251" => "windows-1251 (Cyrillc)", "windows-1252" => "windows-1252 (Western languages)", "windows-1253" => "windows-1253 (Greek)", "windows-1254" => "windows-1254 (Turkish)", "windows-1255" => "windows-1255 (Hebrew)", "windows-1256" => "windows-1256 (Arabic)", "windows-1257" => "windows-1257 (Baltic languages)", "windows-1258" => "windows-1258 (Vietnamese)", "CP932" => "CP932 (Japanese)"];
        }
        return $arr;
    }
    public function getRandDir($length = 16)
    {
        $_obfuscated_0D5C0F401B1E032C3238081F3802053B36022B1D1C1011_ = "abcdefghijklmnopqrstuvwxyz";
        $string = "i/" . $_obfuscated_0D5C0F401B1E032C3238081F3802053B36022B1D1C1011_[mt_rand(0, $length - 1)] . $_obfuscated_0D5C0F401B1E032C3238081F3802053B36022B1D1C1011_[mt_rand(0, $length - 1)] . "/" . $_obfuscated_0D5C0F401B1E032C3238081F3802053B36022B1D1C1011_[mt_rand(0, $length - 1)] . $_obfuscated_0D5C0F401B1E032C3238081F3802053B36022B1D1C1011_[mt_rand(0, $length - 1)];
        return $string;
    }
    public function downloadImage($url, $path = "", $catalog_id = 0)
    {
        $url = trim($url, " \n\t\r");
        if (empty($this->FileExtAllowed)) {
            $this->FileExtAllowed = ["jpg", "jpeg", "jpe", "ico", "tiff", "tif", "svg", "svgz", "eps", "png", "gif", "bmp"];
        }
        if (empty($url)) {
            return "";
        }
        if (!function_exists("curl_version")) {
            return "";
        }
        if (preg_match("/(http|https):\\/\\/(.*?)\$/i", $url) === false) {
            return "";
        }
        $_obfuscated_0D380D061B0D392D24151428193C021D03193B121E2632_ = basename($url);
        $url = $this->getUrlEncode($url);
        if ($catalog_id) {
            $result = $this->db->query("SELECT image_path FROM `" . DB_PREFIX . "csvprice_pro_images` WHERE `image_key` = '" . $this->db->escape(md5($url)) . "' LIMIT 1");
            if (0 < $result->num_rows) {
                if (is_file(DIR_IMAGE . $result->row["image_path"])) {
                    return $result->row["image_path"];
                }
                $this->db->query("DELETE FROM `" . DB_PREFIX . "csvprice_pro_images` WHERE `image_path` = '" . $this->db->escape($result->row["image_path"]) . "'");
            }
            $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_[] = "Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg";
            $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_[] = "Connection: Keep-Alive";
            $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_[] = "Content-type: application/x-www-form-urlencoded;charset=UTF-8";
            $_obfuscated_0D1D2B120D34041F5C0D3726172B110D1B291517280822_ = "php";
            $_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_ = curl_init($url);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_HTTPHEADER, $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_HEADER, 0);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_USERAGENT, $_obfuscated_0D1D2B120D34041F5C0D3726172B110D1B291517280822_);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_TIMEOUT, 30);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_FOLLOWLOCATION, 1);
            $data = curl_exec($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_);
            $_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_ = curl_getinfo($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLINFO_HTTP_CODE);
            curl_close($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_);
            if ($_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_ < 199 || 300 < $_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_) {
                return "";
            }
            $_obfuscated_0D3315055B3E36145C0A03302F120303160D1A0F0D3C22_ = $this->ImageDataDir . trim($path, " /") . "/";
            if (!is_dir(DIR_IMAGE . $_obfuscated_0D3315055B3E36145C0A03302F120303160D1A0F0D3C22_)) {
                @mkdir(DIR_IMAGE . $_obfuscated_0D3315055B3E36145C0A03302F120303160D1A0F0D3C22_, 511, true);
            }
            $_obfuscated_0D26012B0B0F2C03101B321C27042617390D132A393701_ = md5(microtime()) . "." . strtolower(@pathinfo($url, PATHINFO_EXTENSION));
            @file_put_contents(DIR_IMAGE . $_obfuscated_0D3315055B3E36145C0A03302F120303160D1A0F0D3C22_ . $_obfuscated_0D26012B0B0F2C03101B321C27042617390D132A393701_, $data);
            if ($catalog_id) {
                $result = $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro_images` SET `image_path` = '" . $this->db->escape($_obfuscated_0D3315055B3E36145C0A03302F120303160D1A0F0D3C22_ . $_obfuscated_0D26012B0B0F2C03101B321C27042617390D132A393701_) . "', `catalog_id` = '" . (int) $catalog_id . "', `image_key` = '" . $this->db->escape(md5($url)) . "'");
            }
            return $_obfuscated_0D3315055B3E36145C0A03302F120303160D1A0F0D3C22_ . $_obfuscated_0D26012B0B0F2C03101B321C27042617390D132A393701_;
        }
        return "";
    }
    public function getTmpDir()
    {
        if (!is_dir(DIR_SYSTEM . $this->TmpDirectory)){
            mkdir(DIR_SYSTEM . $this->TmpDirectory, 0755, true);
        }

        $_obfuscated_0D331B3C3C3F39052502102930023F2929353E241C3511_ = DIR_SYSTEM . $this->TmpDirectory . "tmp.file";
        if (touch($_obfuscated_0D331B3C3C3F39052502102930023F2929353E241C3511_)) {
            if (file_exists($_obfuscated_0D331B3C3C3F39052502102930023F2929353E241C3511_)) {
                @unlink($_obfuscated_0D331B3C3C3F39052502102930023F2929353E241C3511_);
            }
            return DIR_SYSTEM . $this->TmpDirectory;
        }
        return false;
    }
    public function getMacros($key = NULL)
    {
        if ($key) {
            $result = $this->getSetting($key);
        } else {
            $result = $this->getSetting("ProductMacros");
        }
        if (empty($result)) {
            return false;
        }
        $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = [];
        if (0 < count($result)) {
            foreach ($result as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_[$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["tbl_name"]][] = ["field_name" => $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"], "csv_name" => $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"]];
            }
        }
        return $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_;
    }
    public function getDbColumn($table)
    {
        $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = [];
        $results = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`");
        if (0 < $results->num_rows) {
            foreach ($results->rows as $result) {
                $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_[] = $result["Field"];
            }
        }
        return $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_;
    }
    public function addLicenseKey($value)
    {
        $this->editSetting("LicenseKey", $value, 0);
    }
    public function getLicenseKey()
    {
        return $this->getSetting("LicenseKey");
    }
    public function editSetting($key, $value, $serialized = 1)
    {
        if ($serialized) {
            $value = base64_encode(json_encode($value));
        }
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = '" . $key . "' LIMIT 1;");
        if (isset($result->num_rows) && 0 < $result->num_rows) {
            $_obfuscated_0D32293B23132709051307241F0A0706061328113B0B22_ = $result->row["setting_id"];
            $this->db->query("UPDATE `" . DB_PREFIX . "csvprice_pro` SET `serialized` = '" . (int) $serialized . "', `key` = '" . $key . "', `value` = '" . $this->db->escape($value) . "' WHERE `setting_id` = '" . $_obfuscated_0D32293B23132709051307241F0A0706061328113B0B22_ . "';");
        } else {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "csvprice_pro` SET `serialized` = '" . (int) $serialized . "', `key` = '" . $key . "', `value` = '" . $this->db->escape($value) . "';");
        }
    }
    public function getSetting($key)
    {
        $result = $this->db->query("SELECT value, serialized FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = '" . $key . "' LIMIT 1;");
        if (isset($result->row["value"])) {
            if ($result->row["serialized"]) {
                return json_decode(base64_decode($result->row["value"]), true);
            }
            return $result->row["value"];
        }
        return false;
    }
    public function getVersion()
    {
        $result = $this->db->query("SELECT value FROM " . DB_PREFIX . "csvprice_pro WHERE `key` = 'version' LIMIT 1");
        if (0 < $result->num_rows) {
            return $result->row["value"];
        }
        return "";
    }
    public function checkInstall($table = "csvprice_pro")
    {
        if (!$this->checkDbTable($table)) {
            $this->load->model("csvprice_pro/app_setup");
            $this->model_csvprice_pro_app_setup->Install();
        }
    }
    public function checkDBUpdate()
    {
        $_obfuscated_0D213714362340341C1140070E1F182F2515282C3F0232_ = $this->getVersion();
        if ($_obfuscated_0D213714362340341C1140070E1F182F2515282C3F0232_ != CSVPRICEPRO_VERSION) {
            $this->load->model("csvprice_pro/app_setup");
            $this->model_csvprice_pro_app_setup->UpdateDB($_obfuscated_0D213714362340341C1140070E1F182F2515282C3F0232_);
        }
    }
    public function testDBTableInstall()
    {
        $_obfuscated_0D0F0B223B270D2627232B115C3D153D0522150F1C1F22_ = $this->testDBFieldInstall();
        if ($_obfuscated_0D0F0B223B270D2627232B115C3D153D0522150F1C1F22_) {
            return $_obfuscated_0D0F0B223B270D2627232B115C3D153D0522150F1C1F22_;
        }
        $this->session->data["driver"] = "About";
        if ($this->error) {
            $this->session->data["error_license"] = $this->error;
        }
        $this->goRedirect($this->url->link("csvprice_pro/app_about", "token=" . $this->session->data["token"], "SSL"));
    }
    public function testDBFieldInstall()
    {
        return true;

        $a = 0;
        if (!extension_loaded("openssl") || !function_exists("openssl_decrypt")) {
            $this->error = "Your PHP installation appears to be missing the OpenSSL extension which is require!";
            return false;
        }
        $_obfuscated_0D362136170C1A1E39152E0D5C022F29351513235C0D32_ = $this->getLicenseKey();
        if (!empty($_obfuscated_0D362136170C1A1E39152E0D5C022F29351513235C0D32_)) {
            $_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_ = explode(":", base64_decode(urldecode(trim($_obfuscated_0D362136170C1A1E39152E0D5C022F29351513235C0D32_, " \n\t\r"))));
            $_obfuscated_0D362136170C1A1E39152E0D5C022F29351513235C0D32_ = @openssl_decrypt($_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[0], "aes-256-cbc", "_4e56DOKA460af638c67_DOW", false, $_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[1]);
            $_obfuscated_0D0309403710105B28280302351208310E0832392B0322_ = explode("|", $_obfuscated_0D362136170C1A1E39152E0D5C022F29351513235C0D32_);
            if (is_array($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_) && count($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_) == 4 && !empty($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[2]) && !empty($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[3])) {
                if (strtotime($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[0] . " 00:00:00") < strtotime("now")) {
                    $this->error = "The license has expired!";
                } else {
                    if (!$this->is_STDIN() && preg_match("/" . preg_quote($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[1]) . "/i", $_SERVER["SERVER_NAME"]) && preg_match("/" . preg_quote($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[1]) . "/i", $_SERVER["HTTP_HOST"]) && preg_match("/" . preg_quote($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[1]) . "/i", HTTP_SERVER)) {
                        return true;
                    }
                    if ($this->is_STDIN() && preg_match("/" . preg_quote($_obfuscated_0D0309403710105B28280302351208310E0832392B0322_[1]) . "/i", HTTP_SERVER)) {
                        return true;
                    }
                }
            }
        }
        $this->db->query("DELETE FROM `" . DB_PREFIX . "csvprice_pro` WHERE `key` = 'LicenseKey';");
        if (!$this->error) {
            $this->error = "The license key is not valid!";
        }
        return false;
    }
    public function checkDbTable($table)
    {
        $result = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
        return $result->num_rows != "1" ? false : true;
    }
    public function checkDbColumn($table, $column)
    {
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "'");
        return $result->num_rows ? true : false;
    }
    public function clearCache($file)
    {
        $_obfuscated_0D183B2F153213333E2B350F40360C213D2C2D1B072511_ = glob(DIR_CACHE . "cache." . $file);
        if ($_obfuscated_0D183B2F153213333E2B350F40360C213D2C2D1B072511_) {
            foreach ($_obfuscated_0D183B2F153213333E2B350F40360C213D2C2D1B072511_ as $_obfuscated_0D5C3E3C1D12350528183F260C07351117113D31401211_) {
                if (file_exists($_obfuscated_0D5C3E3C1D12350528183F260C07351117113D31401211_)) {
                    @unlink($_obfuscated_0D5C3E3C1D12350528183F260C07351117113D31401211_);
                }
            }
        }
    }
    public function goRedirect($url, $status = 302)
    {
        $this->editSetting("Session", $this->session->data);
        header("Status: " . $status);
        header("Location: " . str_replace(["&amp;", "\n", "\r"], ["&", "", ""], $url));
        exit;
    }
    public function encodingFileToUTF8($filename, $charset)
    {
        ob_start();
        $_obfuscated_0D13073105361B2F2F0B3F37373430310E052A303E1401_ = 8388608;
        if ($_obfuscated_0D13073105361B2F2F0B3F37373430310E052A303E1401_ <= filesize($filename)) {
            $_obfuscated_0D221C142E050B2F0D15290130275B171F300C07070E22_ = $filename . $charset;
            if (!copy($filename, $_obfuscated_0D221C142E050B2F0D15290130275B171F300C07070E22_)) {
                @unlink($filename);
                return ["error" => "error_copy_uploaded_file"];
            }
            @ini_set("auto_detect_line_endings", 1);
            $_obfuscated_0D3F3C043F292B191832073E0623055B282E0E251A0201_ = fopen($_obfuscated_0D221C142E050B2F0D15290130275B171F300C07070E22_, "r");
            $_obfuscated_0D173B2938152C5C0F2F173215253717082615010D0101_ = fopen($filename, "w");
            $_obfuscated_0D2D2711245C38191E2F222C170C5C1D1819332A111232_ = "";
            if ($_obfuscated_0D3F3C043F292B191832073E0623055B282E0E251A0201_) {
                $_obfuscated_0D092B3607221F3403362717112D5C2C04253B26242D11_ = pack("H*", "EFBBBF");
                while (($_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_ = fgets($_obfuscated_0D3F3C043F292B191832073E0623055B282E0E251A0201_, 40960)) !== false) {
                    $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_ = str_replace("\r", "", $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_);
                    if (!$this->DeleteBom) {
                        $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_ = preg_replace("/^" . $_obfuscated_0D092B3607221F3403362717112D5C2C04253B26242D11_ . "/", "", $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_);
                        $this->DeleteBom = 1;
                    }
                    $_obfuscated_0D2D2711245C38191E2F222C170C5C1D1819332A111232_ = @iconv($charset, "UTF-8//TRANSLIT//IGNORE", $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_);
                    fputs($_obfuscated_0D173B2938152C5C0F2F173215253717082615010D0101_, $_obfuscated_0D2D2711245C38191E2F222C170C5C1D1819332A111232_);
                }
                fclose($_obfuscated_0D3F3C043F292B191832073E0623055B282E0E251A0201_);
            }
            fclose($_obfuscated_0D173B2938152C5C0F2F173215253717082615010D0101_);
            @unlink($_obfuscated_0D221C142E050B2F0D15290130275B171F300C07070E22_);
        } else {
            $_obfuscated_0D053D1626082627312E161C05015C3122363928400E32_ = file_get_contents($filename);
            $_obfuscated_0D053D1626082627312E161C05015C3122363928400E32_ = @iconv($charset, "UTF-8//TRANSLIT//IGNORE", $_obfuscated_0D053D1626082627312E161C05015C3122363928400E32_);
            file_put_contents($filename, $_obfuscated_0D053D1626082627312E161C05015C3122363928400E32_);
            unset($_obfuscated_0D053D1626082627312E161C05015C3122363928400E32_);
        }
        ob_end_clean();
        return true;
    }
    public function replace_file_EOL($file_name, $EOL = 0)
    {
        if ($EOL == 0) {
            $EOL = $this->language->get("setting_replace_eol");
        }
        if ($EOL != "1") {
            return NULL;
        }
        $dirname = dirname($file_name);
        $_obfuscated_0D0B0E103B152A2B1E260D2D3C133222360806401D1111_ = $dirname . "/" . uniqid("import_", true);
        $_obfuscated_0D2F1C2F1716263C1D145B303F1F0207302A2140231D11_ = @fopen($file_name, "r");
        $_obfuscated_0D3135140A2E2B1F35163318391D2B231D5B3F04341B01_ = @fopen($_obfuscated_0D0B0E103B152A2B1E260D2D3C133222360806401D1111_, "w");
        if ($_obfuscated_0D2F1C2F1716263C1D145B303F1F0207302A2140231D11_ && $_obfuscated_0D3135140A2E2B1F35163318391D2B231D5B3F04341B01_) {
            while (($_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_ = @fgets($_obfuscated_0D2F1C2F1716263C1D145B303F1F0207302A2140231D11_, 40960)) !== false) {
                $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_ = str_replace(["\r\n", "\r", "\n"], "\n", $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_);
                @fputs($_obfuscated_0D3135140A2E2B1F35163318391D2B231D5B3F04341B01_, $_obfuscated_0D28220525231C2F222F271B1B0A5B132632401E1E0411_);
            }
            @fclose($_obfuscated_0D2F1C2F1716263C1D145B303F1F0207302A2140231D11_);
            @fclose($_obfuscated_0D3135140A2E2B1F35163318391D2B231D5B3F04341B01_);
        }
        if (is_readable($_obfuscated_0D0B0E103B152A2B1E260D2D3C133222360806401D1111_) && 1 < filesize($_obfuscated_0D0B0E103B152A2B1E260D2D3C133222360806401D1111_)) {
            @unlink($file_name);
            @rename($_obfuscated_0D0B0E103B152A2B1E260D2D3C133222360806401D1111_, $file_name);
        }
    }
    public function is_STDIN()
    {
        if (defined("STDIN")) {
            return true;
        }
        if ((!isset($_SERVER["REMOTE_ADDR"]) || empty($_SERVER["REMOTE_ADDR"])) && !isset($_SERVER["HTTP_USER_AGENT"]) && 0 < count($_SERVER["argv"])) {
            return true;
        }
        return false;
    }
    public function addDocumentStyle($href)
    {
        if (method_exists($this->document, "addStyle")) {
            $this->document->addStyle($href);
        } else {
            if (method_exists($this->document, "addStyle_header")) {
                $this->document->addStyle_header($href);
            }
        }
    }
    public function getUrlEncode($url)
    {
        $url = str_replace(" ", "%20", $url);
        $_obfuscated_0D062217331630232B3C3B09080F333D0D0F370E0D2811_ = ["%21", "%2A", "%27", "%28", "%29", "%3B", "%3A", "%40", "%26", "%3D", "%2B", "%24", "%2C", "%2F", "%3F", "%25", "%23", "%5B", "%5D", "&amp;"];
        $_obfuscated_0D081501033633391D320E08220917333B3936381D2501_ = ["!", "*", "'", "(", ")", ";", ":", "@", "&", "=", "+", "\$", ",", "/", "?", "%", "#", "[", "]", "&"];
        return str_replace($_obfuscated_0D062217331630232B3C3B09080F333D0D0F370E0D2811_, $_obfuscated_0D081501033633391D320E08220917333B3936381D2501_, urlencode($url));
    }
}
<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.4
 * @ Decoder version: 1.0.2
 * @ Release: 10/08/2022
 */

// Decoded file for php version 72.
class ModelCSVPriceProAppCLI extends Model
{
    private $AppVersion;
    private $TmpDir;
    private $JobType;
    private $JobFileLocation;
    private $JobFileName;
    private $JobTempFileName;
    private $JobFilePath;
    private $ProfileID;
    private $JobKey;
    private $JobResult;
    private $FTP_Host;
    private $FTP_User;
    private $FTP_Password;
    private $FTP_Conn;
    private $DateTimeStart;
    private $errorMessage = "";
    private $Mod = true;
    private $DebugLog = 0;
    public function index()
    {
        exit;
    }
    public function crontab()
    {
        $this->setTempDirectory(DIR_SYSTEM . "csvprice_pro/");
        $this->JobKey = "";
        $this->DebugLog = CSVPRICE_PRO_DEBUG;
        $this->DateTimeStart = sprintf("2015-01-01 %s:%s:00", date("H"), date("i"));
        $this->load->model("csvprice_pro/app_setting");
        $this->load->model("csvprice_pro/app_product");
        $this->load->language("csvprice_pro/app_crontab");
        if (!$this->validate()) {
            exit("Cannot be run");
        }
        $this->AppVersion = $this->model_csvprice_pro_app_setting->getVersion();
        $this->fwrite_log("CSV Price Pro import/export: Version " . $this->AppVersion);
        $this->Mod;
        if (!$this->testDBTableInstall()) {
            $this->fwrite_log("CSV Price Pro import/export: Done");
            return false;
        }
        if (!empty($this->JobKey)) {
            $this->initJob();
            if (file_exists($this->JobTempFileName)) {
                @unlink($this->JobTempFileName);
            }
        } else {
            $_obfuscated_0D3F09061823042C2B0D2B3F1B0728322A1811192D5C11_ = $this->getJobsByTime();
            if ($_obfuscated_0D3F09061823042C2B0D2B3F1B0728322A1811192D5C11_) {
                foreach ($_obfuscated_0D3F09061823042C2B0D2B3F1B0728322A1811192D5C11_ as $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_) {
                    $this->JobKey = $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"];
                    $this->initJob();
                    if (file_exists($this->JobTempFileName)) {
                        @unlink($this->JobTempFileName);
                    }
                }
            }
        }
        $this->fwrite_log("CSV Price Pro import/export: Done");
        return true;
    }
    private function initJob()
    {
        if (!$this->getJobConfig()) {
            $this->endJob();
            return false;
        }
        $this->startJob();
        $this->JobResult = "";
        $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_ = $this->model_csvprice_pro_app_product->getProfile($this->ProfileID);
        if ($this->JobType == "import") {
            switch ($this->JobFileLocation) {
                case "ftp":
                    if (!$this->downloadFileFrom_ftp()) {
                        $this->endJob();
                        return false;
                    }
                    break;
                case "web":
                    if (!$this->downloadFileFrom_web()) {
                        $this->endJob();
                        return false;
                    }
                    break;
                case "dir":
                    if (!$this->downloadFileFrom_dir()) {
                        $this->endJob();
                        return false;
                    }
                    $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ = $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_import"]["file_encoding"];
                    unset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_);
                    if ($_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ != "UTF-8") {
                        $result = $this->model_csvprice_pro_app_setting->encodingFileToUTF8($this->JobTempFileName, $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_);
                        if (isset($result["error"])) {
                            $this->errorMessage = $result["error"];
                            $this->endJob();
                            return false;
                        }
                    }
                    $this->model_csvprice_pro_app_setting->replace_file_EOL($this->JobTempFileName, 1);
                    $data = [];
                    $data["file_name"] = $this->JobTempFileName;
                    $this->JobResult = $this->model_csvprice_pro_app_product->ProductImport($data, $this->ProfileID);
                    if (isset($this->JobResult["total"]) && !isset($this->JobResult["error_import"])) {
                        $_obfuscated_0D0A3905161C2D12042F29272B2B312D39315B23081332_ = "Total: %s Update: %s Insert: %s Error: %s Delete: %s";
                        $_obfuscated_0D0A3905161C2D12042F29272B2B312D39315B23081332_ = sprintf($_obfuscated_0D0A3905161C2D12042F29272B2B312D39315B23081332_, $this->JobResult["total"], $this->JobResult["update"], $this->JobResult["insert"], $this->JobResult["error"], $this->JobResult["delete"]);
                        $this->JobResult = "";
                        $this->fwrite_log($_obfuscated_0D0A3905161C2D12042F29272B2B312D39315B23081332_);
                        $this->clear_global_cache();
                        $this->endJob();
                        return true;
                    }
                    if (isset($this->JobResult["error_import"])) {
                        $this->errorMessage = $this->JobResult["error_import"];
                    }
                    $this->JobResult = "";
                    break;
                default:
                    $this->errorMessage = "cli_error_file_location_unknown";
                    $this->endJob();
                    return false;
            }
        }
        if ($this->JobType == "export") {
            if (!@touch($this->JobFilePath) || !@is_writable($this->JobFilePath)) {
                $this->errorMessage = "cli_error_file_not_writable";
                $this->fwrite_log();
                $this->endJob();
                return false;
            }
            $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ = $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_export"]["file_encoding"];
            unset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_);
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = $this->model_csvprice_pro_app_product->ProductExport($this->ProfileID);
            if (is_array($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_) && isset($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"])) {
                $this->errorMessage = $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"];
                $this->fwrite_log();
                $this->endJob();
                return false;
            }
            if ($_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ != "UTF-8") {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = @iconv("UTF-8", $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ . "//TRANSLIT//IGNORE", $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
            }
            @file_put_contents($this->JobFilePath, $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
            $this->endJob();
            return true;
        }
        $this->endJob();
        return false;
    }
    private function getJobsByTime()
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "csvprice_pro_crontab` WHERE `job_offline` = 1 AND `status` = 1\n                        AND UNIX_TIMESTAMP('" . $this->DateTimeStart . "') >=  UNIX_TIMESTAMP(`job_time_start`)                        AND UNIX_TIMESTAMP('" . $this->DateTimeStart . "') <= UNIX_TIMESTAMP(`job_time_start` + INTERVAL 290 SECOND)";
        $result = $this->db->query($sql);
        if (0 < $result->num_rows) {
            return $result->rows;
        }
        return "";
    }
    private function getJobConfig($job_key = "")
    {
        if ($job_key) {
            $this->JobKey = $job_key;
        }
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "csvprice_pro_crontab` WHERE `job_key` = '" . $this->db->escape($this->JobKey) . "' AND `status` = 1 LIMIT 1");
        if (!$result->num_rows) {
            $this->errorMessage = "cli_error_profile_not_found";
            return false;
        }
        $this->ProfileID = $result->row["profile_id"];
        $this->JobType = $result->row["job_type"];
        $this->JobFileLocation = $result->row["job_file_location"];
        $data = json_decode(base64_decode($result->row["job_data"]), true);
        $this->FTP_Host = isset($data["ftp_host"]) && $data["ftp_host"] ? $data["ftp_host"] : "";
        $this->FTP_User = isset($data["ftp_user"]) && $data["ftp_user"] ? $data["ftp_user"] : "";
        $this->FTP_Password = isset($data["ftp_passwd"]) && $data["ftp_passwd"] ? $data["ftp_passwd"] : "";
        $this->JobFilePath = $data["file_path"];
        $this->JobTempFileName = $this->TmpDir . "/" . uniqid("tmp_file_", true);
        if (!$this->JobFilePath) {
            $this->errorMessage = "cli_error_path_not_found";
            return false;
        }
        return true;
    }
    private function testDBTableInstall()
    {
        if (!$this->model_csvprice_pro_app_setting->testDBFieldInstall()) {
            $this->fwrite_log("ERROR: The license key is not valid");
            return false;
        }
        return true;
    }
    public function setTempDirectory($dir_name)
    {
        $this->TmpDir = $dir_name;
    }
    private function startJob()
    {
        $this->fwrite_log("START: Job " . $this->JobKey);
    }
    private function endJob()
    {
        if ($this->errorMessage) {
            $this->fwrite_log();
        }
        $this->fwrite_log("END: Job " . $this->JobKey);
    }
    private function downloadFileFrom_dir()
    {
        if (is_file($this->JobFilePath)) {
            copy($this->JobFilePath, $this->JobTempFileName);
            return true;
        }
        $this->fwrite_log(sprintf($this->language->get("cli_error_copy_file"), $this->JobFilePath));
        return false;
    }
    private function downloadFileFrom_ftp()
    {
        if (!$this->FTP_Host || !$this->FTP_User || !$this->FTP_Password) {
            $this->errorMessage = "cli_error_login_failure";
            return false;
        }
        $this->FTP_Conn = ftp_connect($this->FTP_Host);
        if (!@ftp_login($this->FTP_Conn, $this->FTP_User, $this->FTP_Password)) {
            $this->errorMessage = "cli_error_ftp_connect";
            return false;
        }
        if (!@ftp_get($this->FTP_Conn, $this->JobTempFileName, $this->JobFilePath, FTP_BINARY)) {
            $this->errorMessage = "cli_error_ftp_download";
            return false;
        }
        ftp_close($this->FTP_Conn);
        return true;
    }
    private function downloadFileFrom_web()
    {
        if (empty($this->JobFilePath) || !preg_match("/(http|https):\\/\\/(.*?)\$/i", $this->JobFilePath)) {
            $this->errorMessage = "cli_error_url_entered";
            return false;
        }
        if (!function_exists("curl_version")) {
            $this->errorMessage = "cli_error_curl_not_installed";
            return false;
        }
        $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_[] = "Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg";
        $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_[] = "Connection: Keep-Alive";
        $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_[] = "Content-type: application/x-www-form-urlencoded;charset=UTF-8";
        $_obfuscated_0D1D2B120D34041F5C0D3726172B110D1B291517280822_ = "php";
        $_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_ = curl_init($this->JobFilePath);
        curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_HTTPHEADER, $_obfuscated_0D22355B0211395C30341233343733021B2B2F28241311_);
        curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_HEADER, 0);
        curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_USERAGENT, $_obfuscated_0D1D2B120D34041F5C0D3726172B110D1B291517280822_);
        curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_TIMEOUT, 30);
        curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLOPT_FOLLOWLOCATION, 1);
        $_obfuscated_0D07365C2430283F17122F3B2A253E3D2C0A02245B2301_ = curl_exec($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_);
        $_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_ = curl_getinfo($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_, CURLINFO_HTTP_CODE);
        curl_close($_obfuscated_0D5C24162A13043E011C282C09103B2C0D2201333E1A22_);
        if ($_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_ < 199 || 300 < $_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_) {
            $this->fwrite_log(sprintf($this->language->get("cli_error_curl_download"), $this->JobFilePath, $_obfuscated_0D0632401D1428142A1B3C3E04152C012E212C1A131422_));
            return false;
        }
        $_obfuscated_0D0E273E1E3C0512161F2D231D0702331630171F090F11_ = @file_put_contents($this->JobTempFileName, $_obfuscated_0D07365C2430283F17122F3B2A253E3D2C0A02245B2301_);
        if (!$_obfuscated_0D0E273E1E3C0512161F2D231D0702331630171F090F11_) {
            $this->errorMessage = "cli_error_download_file";
            return false;
        }
        return true;
    }
    private function clear_global_cache()
    {
        $this->cache->delete("manufacturer");
        $this->cache->delete("category");
        $this->cache->delete("product");
        $this->cache->delete("stock_status");
        $this->cache->delete("seo_pro");
        $this->cache->delete("seo_url");
        $this->cache->delete("seo");
        $this->fwrite_log($this->language->get("cli_clear_cache"));
    }
    private function fwrite_log($message = "")
    {
        if (!(int) $this->DebugLog) {
            return NULL;
        }
        if ($message) {
            $this->log->write($message);
        }
        if (is_array($this->errorMessage) && !empty($this->errorMessage)) {
            foreach ($this->errorMessage as $message) {
                $this->log->write("ERROR: " . $this->language->get($message));
            }
            $this->errorMessage = "";
        }
        if ($this->errorMessage) {
            $this->log->write("ERROR: " . $this->language->get($this->errorMessage));
            $this->errorMessage = "";
        }
    }
    protected function validate()
    {
        $_obfuscated_0D3B3B09190D153F5C2E01360B0209093F273F2B1A2A32_ = getopt("k:");
        if (isset($_obfuscated_0D3B3B09190D153F5C2E01360B0209093F273F2B1A2A32_["k"]) && !empty($_obfuscated_0D3B3B09190D153F5C2E01360B0209093F273F2B1A2A32_["k"])) {
            $this->JobKey = $_obfuscated_0D3B3B09190D153F5C2E01360B0209093F273F2B1A2A32_["k"];
            return true;
        }
        if (isset($_GET["cron_key"]) && !empty($_GET["cron_key"])) {
            $this->JobKey = $_GET["cron_key"];
            return true;
        }
        if ($this->model_csvprice_pro_app_setting->is_STDIN()) {
            $this->JobKey = "";
            return true;
        }
        return false;
    }
}

?>
<?php

class ControllerCSVPriceProAppCrontab extends Controller
{
    private $error = [];
    private $AppName;
    private $CLIpath;
    private $CLIpath_HTTP;
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->AppName = "app_crontab";
        $this->load->model("csvprice_pro/app_setting");
        $this->load->model("csvprice_pro/app_crontab");
        $this->model_csvprice_pro_app_setting->checkInstall();
        $this->model_csvprice_pro_app_setting->checkDBUpdate();
        $this->session->data["driver"] = "Crontab";
        $this->language->load("csvprice_pro/" . $this->AppName);
        if (isset($this->session->data["token"]) && $this->session->data["token"]) {
            $this->data["token"] = $this->session->data["token"];
        }
        $this->RootDirectory = str_replace("/system", "", DIR_SYSTEM);
    }
    public function index()
    {
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->getList();
    }
    protected function getList()
    {
        $this->language->load("csvprice_pro/app_header");
        $_obfuscated_0D2218391914321C3F31071831071C2931321E1C2E3B11_ = $this->language->get("mod_demo");
        if ($_obfuscated_0D2218391914321C3F31071831071C2931321E1C2E3B11_ == "mod_demo") {
            $_obfuscated_0D2218391914321C3F31071831071C2931321E1C2E3B11_ = 0;
        }
        $this->data["Template"] = "list";
        $this->CLIpath = $this->getCLIpath();
        if (!file_exists($this->CLIpath) || !is_file($this->CLIpath)) {
            $this->CLIpath = "";
            $this->CLIpath_HTTP = "";
        } else {
            $this->CLIpath_HTTP = str_replace($this->RootDirectory, HTTP_CATALOG, $this->CLIpath);
        }
        $this->data["insert"] = $this->url->link("csvprice_pro/app_crontab/insert", "token=" . $this->session->data["token"], "SSL");
        $this->data["delete"] = $this->url->link("csvprice_pro/app_crontab/delete", "token=" . $this->session->data["token"], "SSL");
        $this->data["button_insert"] = $this->language->get("button_insert");
        $this->data["button_delete"] = $this->language->get("button_delete");
        $this->data["button_view"] = $this->language->get("button_view");
        $this->data["jobs"] = [];
        $_obfuscated_0D3F09061823042C2B0D2B3F1B0728322A1811192D5C11_ = $this->model_csvprice_pro_app_crontab->getJobsList();
        foreach ($_obfuscated_0D3F09061823042C2B0D2B3F1B0728322A1811192D5C11_ as $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_) {
            if (!empty($this->CLIpath)) {
                $_obfuscated_0D071818212218070C0801310819272B29283E361F0411_ = " cd " . dirname($this->CLIpath) . "; php " . $this->CLIpath . " -k " . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"] . " > /dev/null 2>&1";
            } else {
                $_obfuscated_0D071818212218070C0801310819272B29283E361F0411_ = "";
            }
            $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["action"] = ["edit" => ["text" => $this->language->get("text_edit"), "href" => $this->url->link("csvprice_pro/" . $this->AppName . "/insert", "token=" . $this->session->data["token"] . "&job_id=" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_id"], "SSL")], "delete" => ["text" => $this->language->get("text_delete"), "href" => $this->url->link("csvprice_pro/" . $this->AppName . "/delete", "token=" . $this->session->data["token"] . "&job_id=" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_id"], "SSL")]];
            $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_type"] = $this->language->get("text_job_type_" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_type"]);
            $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["status"] = $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["status"] ? $this->language->get("text_enabled") : $this->language->get("text_disabled");
            $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_file_location"] = $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_file_location"] == "dir" ? "Directory" : ($_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_file_location"] == "ftp" ? "FTP" : "Web");
            $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_cli"] = $_obfuscated_0D071818212218070C0801310819272B29283E361F0411_;
            if ($_obfuscated_0D2218391914321C3F31071831071C2931321E1C2E3B11_) {
                $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_cli"] = " cd /home/user/www/example.com/cli/; php /home/user/www/yoursite/cli/csvprice_pro_cli.php -k " . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"] . " > /dev/null 2>&amp;1";
                $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_curl"] = "curl -s http://www.example.com/cli/csvprice_pro_cli.php?cron_key=" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"] . " > /dev/null 2>&amp;1";
                $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_wget"] = "wget -O - -q -t 1  http://www.example.com/cli/csvprice_pro_cli.php?cron_key=" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"] . " > /dev/null 2>&amp;1";
            } else {
                if (!empty($this->CLIpath_HTTP)) {
                    $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_curl"] = "curl -s " . $this->CLIpath_HTTP . "?cron_key=" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"] . " > /dev/null 2>&amp;1";
                    $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_wget"] = "wget -O - -q -t 1 " . $this->CLIpath_HTTP . "?cron_key=" . $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["job_key"] . " > /dev/null 2>&amp;1";
                } else {
                    $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_curl"] = "";
                    $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_["cron_wget"] = "";
                }
            }
            $this->data["jobs"][] = $_obfuscated_0D0A4035063E3319090C3B0D335C34181927273F400B11_;
        }
        $this->data["text_no_results"] = $this->language->get("text_no_results");
        $this->data["column_job_id"] = $this->language->get("column_job_id");
        $this->data["column_job_type"] = $this->language->get("column_job_type");
        $this->data["column_profile_name"] = $this->language->get("column_profile_name");
        $this->data["column_job_file_location"] = $this->language->get("column_job_file_location");
        $this->data["column_job_time_start"] = $this->language->get("column_job_time_start");
        $this->data["column_status"] = $this->language->get("column_status");
        $this->outputTemplate();
    }
    protected function getForm()
    {
        $this->data["Template"] = "form";
        $url = "";
        if (isset($this->request->get["job_id"])) {
            $this->data["job_id"] = $_obfuscated_0D250532340A063832213C2702320E3D23062826121701_ = (int) $this->request->get["job_id"];
            $url .= "&job_id=" . $_obfuscated_0D250532340A063832213C2702320E3D23062826121701_;
        } else {
            $this->data["job_id"] = $_obfuscated_0D250532340A063832213C2702320E3D23062826121701_ = 0;
        }
        $this->data["action"] = $this->url->link("csvprice_pro/" . $this->AppName . "/insert", "token=" . $this->session->data["token"] . $url, "SSL");
        $this->data["heading_title"] = $this->language->get("heading_title");
        if (isset($this->error["warning"])) {
            $this->data["error_warning"] = $this->error["warning"];
        } else {
            $this->data["error_warning"] = "";
        }
        if ($_obfuscated_0D250532340A063832213C2702320E3D23062826121701_) {
            $this->data["job"] = $this->model_csvprice_pro_app_crontab->getJobById($_obfuscated_0D250532340A063832213C2702320E3D23062826121701_);
        } else {
            $this->data["job"] = ["job_id" => 0, "profile_id" => 1, "job_key" => time(), "job_type" => "import", "job_file_location" => "dir", "job_time_start" => ["H" => "00", "i" => "00"], "job_offline" => 0, "status" => 0, "ftp_host" => "", "ftp_user" => "", "ftp_passwd" => "", "file_path" => DIR_SYSTEM . "csvprice_pro/file_data.csv"];
        }
        $this->data["job_key"] = $this->data["job"]["job_key"];
        $_obfuscated_0D14401C3E1315083D0817310B1C401704260715331A22_ = $this->getCLIpath();
        if (file_exists($_obfuscated_0D14401C3E1315083D0817310B1C401704260715331A22_) && is_file($_obfuscated_0D14401C3E1315083D0817310B1C401704260715331A22_)) {
            $this->data["cli"] = " cd " . dirname($_obfuscated_0D14401C3E1315083D0817310B1C401704260715331A22_) . "; php " . $_obfuscated_0D14401C3E1315083D0817310B1C401704260715331A22_ . " -k " . $this->data["job_key"] . " > /dev/null 2>&1";
        } else {
            $this->data["cli"] = "";
        }
        $this->data["datetime"] = $this->model_csvprice_pro_app_crontab->getDateOptions();
        $this->data["profile_import"] = $this->model_csvprice_pro_app_crontab->getProfilesByType("import");
        $this->data["profile_export"] = $this->model_csvprice_pro_app_crontab->getProfilesByType("export");
        $this->data["save"] = $this->url->link("csvprice_pro/app_crontab/save", "token=" . $this->session->data["token"], "SSL");
        $this->data["cancel"] = $this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL");
        $this->data["button_save"] = $this->language->get("button_save");
        $this->data["button_cancel"] = $this->language->get("button_cancel");
        $this->data["entry_job_id"] = $this->language->get("entry_job_id");
        $this->data["entry_job_type"] = $this->language->get("entry_job_type");
        $this->data["entry_profile"] = $this->language->get("entry_profile");
        $this->data["entry_file_location"] = $this->language->get("entry_file_location");
        $this->data["entry_time_start"] = $this->language->get("entry_time_start");
        $this->data["entry_ftp_host"] = $this->language->get("entry_ftp_host");
        $this->data["entry_ftp_user"] = $this->language->get("entry_ftp_user");
        $this->data["entry_ftp_passwd"] = $this->language->get("entry_ftp_passwd");
        $this->data["entry_file_path"] = $this->language->get("entry_file_path");
        $this->data["entry_status"] = $this->language->get("entry_status");
        $this->data["entry_job_offline"] = $this->language->get("entry_job_offline");
        $this->data["text_job_type_import"] = $this->language->get("text_job_type_import");
        $this->data["text_job_type_export"] = $this->language->get("text_job_type_export");
        $this->data["text_enabled"] = $this->language->get("text_enabled");
        $this->data["text_disabled"] = $this->language->get("text_disabled");
        $this->outputTemplate();
    }
    public function insert()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->model_csvprice_pro_app_crontab->addJob($this->request->post);
            $this->session->data["success"] = $this->language->get("text_success");
            $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
        }
        $this->getForm();
    }
    public function delete()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if (isset($this->request->post["selected"]) && $this->validate()) {
            foreach ($this->request->post["selected"] as $_obfuscated_0D250532340A063832213C2702320E3D23062826121701_) {
                $this->model_csvprice_pro_app_crontab->deleteJob($_obfuscated_0D250532340A063832213C2702320E3D23062826121701_);
            }
            $this->session->data["success"] = $this->language->get("text_success");
        }
        if (isset($this->request->get["job_id"]) && $this->validate()) {
            $this->model_csvprice_pro_app_crontab->deleteJob($this->request->get["job_id"]);
            $this->session->data["success"] = $this->language->get("text_success");
        }
        $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
    }
    private function getCLIpath()
    {
        $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = $this->model_csvprice_pro_app_setting->getSetting("CLI_path");
        if (empty($_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_) || !file_exists($_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_) || !is_file($_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_)) {
            $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_ = dirname(DIR_APPLICATION) . "/cli/csvprice_pro_cli.php";
            $this->model_csvprice_pro_app_setting->editSetting("CLI_path", $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_, 0);
        }
        return $_obfuscated_0D0810222A15072A345C4009081B14250F16310B402601_;
    }
    private function outputTemplate()
    {
        $this->model_csvprice_pro_app_setting->addDocumentStyle("view/stylesheet/stylesheet_csvpricepro.css");
        $this->model_csvprice_pro_app_setting->editSetting("Session", $this->session->data);
        $this->document->setTitle($this->language->get("heading_title_normal"));
        $this->data["breadcrumbs"] = [];
        $this->data["breadcrumbs"][] = ["text" => $this->language->get("text_home"), "href" => $this->url->link("common/home", "token=" . $this->session->data["token"], "SSL"), "separator" => false];
        $this->data["breadcrumbs"][] = ["text" => $this->language->get("text_module"), "href" => $this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL"), "separator" => " :: "];
        $this->data["breadcrumbs"][] = ["text" => $this->language->get("heading_title_normal"), "href" => $this->url->link("module/csvprice_pro", "token=" . $this->session->data["token"], "SSL"), "separator" => " :: "];
        if (isset($this->session->data["warning"])) {
            $this->data["warning"] = $this->session->data["warning"];
            unset($this->session->data["warning"]);
        } else {
            if (isset($this->session->data["error"])) {
                $this->data["warning"] = $this->session->data["error"];
                unset($this->session->data["error"]);
            } else {
                $this->data["warning"] = "";
            }
        }
        if (isset($this->session->data["success"])) {
            $this->data["success"] = $this->session->data["success"];
            unset($this->session->data["success"]);
        } else {
            $this->data["success"] = "";
        }
        $this->template = "csvprice_pro/app_crontab.tpl";
        $this->children = ["common/header", "common/footer", "csvprice_pro/app_header", "csvprice_pro/app_footer"];
        $this->response->setOutput($this->render());
    }
    private function goRedirect($url)
    {
        $this->model_csvprice_pro_app_setting->editSetting("Session", $this->session->data);
        $this->redirect($url);
    }
    protected function validate()
    {
        if (!$this->user->hasPermission("modify", "csvprice_pro/" . $this->AppName)) {
            $this->error["warning"] = $this->language->get("error_permission");
        }
        if (!$this->error) {
            return true;
        }
        return false;
    }
}
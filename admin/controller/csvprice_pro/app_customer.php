<?php

class ControllerCSVPriceProAppCustomer extends Controller
{
    private $error = [];
    private $TempDirectory = "";
    private $Mod = true;
    private $AppName;
    private $DriverName;
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->AppName = "app_customer";
        $this->DriverName = "Customer";
        $this->load->model("csvprice_pro/app_setting");
        $this->load->model("csvprice_pro/" . $this->AppName);
        $this->model_csvprice_pro_app_setting->checkInstall();
        $this->model_csvprice_pro_app_setting->checkDBUpdate();
        $this->language->load("csvprice_pro/" . $this->AppName);
        $this->session->data["driver"] = $this->DriverName;
        $session = $this->model_csvprice_pro_app_setting->getSetting("Session");
        if (isset($this->session->data["tabs"][$this->DriverName])) {
            $this->data["tab_selected"] = $this->session->data["tabs"][$this->DriverName];
        } else {
            if (isset($session["tabs"][$this->DriverName])) {
                $this->session->data["tabs"] = $session["tabs"];
                $this->data["tab_selected"] = $session["tabs"][$this->DriverName];
            } else {
                $this->session->data["tabs"][$this->DriverName] = "tab_export";
                $this->data["tab_selected"] = $this->session->data["tabs"][$this->DriverName];
            }
        }
        unset($session);
    }
    public function index()
    {
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->data["action"] = $this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL");
        $this->data["text_select_all"] = $this->language->get("text_select_all");
        $this->data["text_unselect_all"] = $this->language->get("text_unselect_all");
        $this->data["text_all"] = $this->language->get("text_all");
        $this->data["text_hide_all"] = $this->language->get("text_hide_all");
        $this->data["text_show_all"] = $this->language->get("text_show_all");
        $this->data["text_enabled"] = $this->language->get("text_enabled");
        $this->data["text_disabled"] = $this->language->get("text_disabled");
        $this->data["tab_export"] = $this->language->get("tab_export");
        $this->data["entry_customer_group"] = $this->language->get("entry_customer_group");
        $this->data["entry_file_format"] = $this->language->get("entry_file_format");
        $this->data["entry_csv_delimiter"] = $this->language->get("entry_csv_delimiter");
        $this->data["entry_date_start"] = $this->language->get("entry_date_start");
        $this->data["entry_date_end"] = $this->language->get("entry_date_end");
        $this->data["entry_file_encoding"] = $this->language->get("entry_file_encoding");
        $this->data["entry_newsletter"] = $this->language->get("entry_newsletter");
        $this->data["entry_status"] = $this->language->get("entry_status");
        $this->data["button_export"] = $this->language->get("button_export");
        $this->data["js_datepicker_regional"] = $this->language->get("js_datepicker_regional");
        $this->data["csv_export"] = $this->model_csvprice_pro_app_setting->getSetting("CustomerExport");
        if (empty($this->data["csv_export"])) {
            $this->data["csv_export"] = ["fields_set" => ["_FIRST_NAME_" => "1", "_EMAIL_" => "1"], "customer_group_id" => 0, "file_format" => "csv", "file_encoding" => "UTF-8", "csv_delimiter" => "", "date_start" => date("Y-m-d H:i"), "date_end" => date("Y-m-d H:i"), "newsletter" => "2", "status" => "2"];
        }
        $this->data["csv_export"]["fields_set_data"] = [];
        $a =& $this->data["csv_export"]["fields_set_data"];
        $a[] = ["uid" => "_FIRST_NAME_", "name" => "First Name"];
        $a[] = ["uid" => "_LAST_NAME_", "name" => "Last Name"];
        $a[] = ["uid" => "_NAME_", "name" => "Name"];
        $a[] = ["uid" => "_EMAIL_", "name" => "Email"];
        $a[] = ["uid" => "_TELEPHONE_", "name" => "Telephone"];
        $a[] = ["uid" => "_FAX_", "name" => "Fax"];
        $a[] = ["uid" => "_COMPANY_", "name" => "Company"];
        $a[] = ["uid" => "_COUNTRY_", "name" => "Country"];
        $a[] = ["uid" => "_CITY_", "name" => "City"];
        $a[] = ["uid" => "_POSTCODE_", "name" => "Postcode"];
        $a[] = ["uid" => "_ADDRESS_1_", "name" => "Address 1"];
        $a[] = ["uid" => "_ADDRESS_2_", "name" => "Address 2"];
        foreach ($this->data["csv_export"]["fields_set_data"] as $value) {
            if (isset($value["caption"])) {
                $this->data["fields_set_help"][$value["uid"]] = $value["caption"];
            } else {
                $this->data["fields_set_help"][$value["uid"]] = $this->language->get($value["uid"]);
            }
        }
        $this->data["date_start"] = date("Y-m-d H:i");
        $this->data["action_export"] = $this->url->link("csvprice_pro/app_customer/export", "token=" . $this->session->data["token"] . "&format=raw", "SSL");
        $this->load->model("sale/customer_group");
        $this->data["customer_groups"] = $this->model_sale_customer_group->getCustomerGroups();
        $this->data["charsets"] = $this->model_csvprice_pro_app_setting->getCharsets();
        $this->outputTemplate();
    }
    public function export()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->session->data["tabs"]["Customer"] = "tab_export";
            $this->model_csvprice_pro_app_setting->editSetting("CustomerExport", $this->request->post["csv_export"]);
            $_obfuscated_0D082F041E013E130310311F1B3C0F5B29142A192D0211_ = $this->request->post["csv_export"]["file_format"];
            $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ = $this->request->post["csv_export"]["file_encoding"];
            unset($this->request->post["csv_export"]);
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = $this->model_csvprice_pro_app_customer->CustomerExport();
            if (is_array($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_) && isset($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"])) {
                $this->session->data["error"] = $this->language->get($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"]);
                $this->goRedirect($this->url->link("csvprice_pro/app_customer", "token=" . $this->session->data["token"], "SSL"));
            }
            if ($_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ != "UTF-8") {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = @iconv("UTF-8", $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ . "//TRANSLIT//IGNORE", $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
            }
            $_obfuscated_0D380D061B0D392D24151428193C021D03193B121E2632_ = "customer_" . (string) date("Y-m-d-Hi") . "." . $_obfuscated_0D082F041E013E130310311F1B3C0F5B29142A192D0211_;
            $this->response->addheader("Pragma: public");
            $this->response->addheader("Connection: Keep-Alive");
            $this->response->addheader("Expires: 0");
            $this->response->addheader("Content-Description: File Transfer");
            $this->response->addheader("Content-Type: application/octet-stream");
            $this->response->addheader("Content-Disposition: attachment; filename=" . $_obfuscated_0D380D061B0D392D24151428193C021D03193B121E2632_);
            $this->response->addheader("Content-Transfer-Encoding: binary");
            $this->response->setOutput($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
        } else {
            return $this->forward("error/permission");
        }
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
        $this->template = "csvprice_pro/" . $this->AppName . ".tpl";
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
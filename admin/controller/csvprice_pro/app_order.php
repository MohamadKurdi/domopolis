<?php

class ControllerCSVPriceProAppOrder extends Controller
{
    private $error = [];
    private $TempDirectory = "";
    private $Mod = true;
    private $AppName;
    private $DriverName;
    private $CoreType;
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->AppName = "app_order";
        $this->DriverName = "Order";
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
                $this->session->data["tabs"][$this->DriverName] = "tab_setting";
                $this->data["tab_selected"] = $this->session->data["tabs"][$this->DriverName];
            }
        }
        unset($session);
    }
    public function index()
    {
        if (isset($this->request->post["csv_setting"])) {
            $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
            if ($this->validate()) {
                $this->model_csvprice_pro_app_setting->editSetting("OrderSetting", $this->request->post["csv_setting"]);
                $this->session->data["tabs"][$this->DriverName] = "tab_setting";
                $this->session->data["success"] = $this->language->get("text_success_setting");
                $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
            } else {
                return $this->forward("error/permission");
            }
        }
        $this->CoreType = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->data["text_select_all"] = $this->language->get("text_select_all");
        $this->data["text_unselect_all"] = $this->language->get("text_unselect_all");
        $this->data["text_yes"] = $this->language->get("text_yes");
        $this->data["text_no"] = $this->language->get("text_no");
        $this->data["text_export"] = $this->language->get("text_export");
        $this->data["text_missing"] = $this->language->get("text_missing");
        $this->data["text_help"] = $this->language->get("text_help");
        $this->data["entry_order_id"] = $this->language->get("entry_order_id");
        $this->data["entry_customer"] = $this->language->get("entry_customer");
        $this->data["entry_order_status"] = $this->language->get("entry_order_status");
        $this->data["entry_date_added"] = $this->language->get("entry_date_added");
        $this->data["entry_date_modified"] = $this->language->get("entry_date_modified");
        $this->data["entry_total_sum"] = $this->language->get("entry_total_sum");
        $this->data["entry_customer"] = $this->language->get("entry_customer");
        $this->data["entry_include_csv_title"] = $this->language->get("entry_include_csv_title");
        $this->data["entry_file_format"] = $this->language->get("entry_file_format");
        $this->data["entry_csv_delimiter"] = $this->language->get("entry_csv_delimiter");
        $this->data["entry_csv_delimiter_text"] = $this->language->get("entry_csv_delimiter_text");
        $this->data["entry_fields_set"] = $this->language->get("entry_fields_set");
        $this->data["entry_file_encoding"] = $this->language->get("entry_file_encoding");
        $this->data["entry_gzcompress"] = $this->language->get("entry_gzcompress");
        $this->data["entry_import_file"] = $this->language->get("entry_import_file");
        $this->data["tab_export"] = $this->language->get("tab_export");
        $this->data["tab_import"] = $this->language->get("tab_import");
        $this->data["tab_setting"] = $this->language->get("tab_setting");
        $this->data["button_export"] = $this->language->get("button_export");
        $this->data["button_import"] = $this->language->get("button_import");
        $this->data["button_save"] = $this->language->get("button_save");
        $this->data["error_export_product_category"] = $this->language->get("error_export_product_category");
        $this->data["error_export_fields_set"] = $this->language->get("error_export_fields_set");
        $this->data["help_export_file_encoding"] = $this->language->get("help_export_file_encoding");
        $this->data["js_datepicker_regional"] = $this->language->get("js_datepicker_regional");
        $this->load->model("localisation/language");
        $_obfuscated_0D1701222514122426151A101A0A24241B5B3D17330C32_ = $this->model_localisation_language->getLanguages();
        foreach ($_obfuscated_0D1701222514122426151A101A0A24241B5B3D17330C32_ as $language) {
            if (isset($this->error["code" . $language["language_id"]])) {
                $this->data["error_code" . $language["language_id"]] = $this->error["code" . $language["language_id"]];
            } else {
                $this->data["error_code" . $language["language_id"]] = "";
            }
        }
        $this->data["languages"] = $_obfuscated_0D1701222514122426151A101A0A24241B5B3D17330C32_;
        $_obfuscated_0D302B022415363E333E2B332C270A391B2A07040C3611_ = ["order_id", "invoice_no", "invoice_date", "store_id", "store_name", "store_url", "customer_id", "customer_group_id", "firstname", "lastname", "firstname_lastname", "email", "telephone", "fax", "payment_firstname", "payment_lastname", "payment_company", "payment_company_id", "payment_address_1", "payment_address_2", "payment_city", "payment_postcode", "payment_country", "payment_zone", "payment_method", "payment_code", "shipping_firstname", "shipping_lastname", "shipping_company", "shipping_address_1", "shipping_address_2", "shipping_city", "shipping_postcode", "shipping_country", "shipping_zone", "shipping_method", "shipping_code", "comment", "total", "order_status", "affiliate_id", "commission", "currency_code", "ip", "date_added", "date_modified", "product_id", "product_name", "product_options", "product_model", "product_sku", "product_quantity", "product_price", "product_total", "product_tax", "product_reward"];
        $_obfuscated_0D1118233913323C370F353C40120D30341C09263E0722_ = [];
        if (!$this->CoreType["ORDER_PAYMENT_COMPANY_ID"]) {
            $_obfuscated_0D1118233913323C370F353C40120D30341C09263E0722_[] = "payment_company_id";
        }
        if (!$this->CoreType["ORDER_PAYMENT_CODE"]) {
            $_obfuscated_0D1118233913323C370F353C40120D30341C09263E0722_[] = "payment_code";
        }
        if (!$this->CoreType["ORDER_SHIPPING_CODE"]) {
            $_obfuscated_0D1118233913323C370F353C40120D30341C09263E0722_[] = "shipping_code";
        }
        if (!$this->CoreType["ORDER_PRODUCT_REWARD"]) {
            $_obfuscated_0D1118233913323C370F353C40120D30341C09263E0722_[] = "product_reward";
        }
        $_obfuscated_0D302B022415363E333E2B332C270A391B2A07040C3611_ = array_diff($_obfuscated_0D302B022415363E333E2B332C270A391B2A07040C3611_, $_obfuscated_0D1118233913323C370F353C40120D30341C09263E0722_);
        $this->data["csv_setting"] = $this->model_csvprice_pro_app_setting->getSetting("OrderSetting");
        if (!isset($this->data["csv_setting"]["fields_set"])) {
            foreach ($_obfuscated_0D302B022415363E333E2B332C270A391B2A07040C3611_ as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                $this->data["csv_setting"]["fields_set"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_] = ["caption" => $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_, "status" => 0];
            }
            $this->data["csv_setting"]["fields_set"]["order_id"]["status"] = 1;
            $this->data["csv_setting"]["fields_set"]["date_added"]["status"] = 1;
            $this->data["csv_setting"]["fields_set"]["total"]["status"] = 1;
        }
        if (!isset($this->data["csv_setting"]["file_encoding"])) {
            $this->data["csv_setting"]["file_encoding"] = "UTF-8";
        }
        if (!isset($this->data["csv_setting"]["gzcompress"])) {
            $this->data["csv_setting"]["gzcompress"] = 0;
        }
        if (!isset($this->data["csv_setting"]["delimiter"])) {
            $this->data["csv_setting"]["delimiter"] = ";";
        }
        if (!isset($this->data["csv_setting"]["delimiter_text"])) {
            $this->data["csv_setting"]["delimiter_text"] = "\"";
        }
        if (!isset($this->data["csv_setting"]["fields_set"])) {
            $this->model_csvprice_pro_app_setting->editSetting("OrderSetting", $this->data["csv_setting"]);
        }
        $this->data["fields_set_help"] = [];
        foreach ($_obfuscated_0D302B022415363E333E2B332C270A391B2A07040C3611_ as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
            $this->data["fields_set_help"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_] = $this->language->get("text_" . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_);
        }
        $this->data["filter_order_id"] = isset($this->request->post["filter_order_id"]) ? $this->request->post["filter_order_id"] : "";
        $this->data["filter_customer"] = isset($this->request->post["filter_customer"]) ? $this->request->post["filter_customer"] : "";
        $this->data["filter_order_status_id"] = isset($this->request->post["filter_order_status_id"]) ? $this->request->post["filter_order_status_id"] : "";
        $this->data["filter_date_added_start"] = isset($this->request->post["filter_date_added_start"]) ? $this->request->post["filter_date_added_start"] : "";
        $this->data["filter_date_added_end"] = isset($this->request->post["filter_date_added_end"]) ? $this->request->post["filter_date_added_end"] : "";
        $this->data["filter_date_modified_start"] = isset($this->request->post["filter_date_modified_start"]) ? $this->request->post["filter_date_modified_start"] : "";
        $this->data["filter_date_modified_end"] = isset($this->request->post["filter_date_modified_end"]) ? $this->request->post["filter_date_modified_end"] : "";
        $this->data["filter_total_prefix"] = isset($this->request->post["filter_total_prefix"]) ? $this->request->post["filter_total_prefix"] : "";
        $this->data["filter_total_sum"] = isset($this->request->post["filter_total_prefix"]) ? $this->request->post["filter_total_sum"] : "";
        $this->load->model("localisation/order_status");
        $this->data["order_statuses"] = $this->model_localisation_order_status->getOrderStatuses();
        $_obfuscated_0D1D1D2623100F3E04291A370E1A3F2914061A3D235C01_ = ["order_id", "order_status_id", "customer_id", "comment", "notify", "description", "amount", "amount_notify"];
        $this->data["fields_import_help"] = [];
        foreach ($_obfuscated_0D1D1D2623100F3E04291A370E1A3F2914061A3D235C01_ as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
            $this->data["fields_import_help"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_] = $this->language->get("text_" . $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_);
        }
        if (!isset($this->data["csv_import"])) {
            $this->data["csv_import"] = [];
            foreach ($_obfuscated_0D1D1D2623100F3E04291A370E1A3F2914061A3D235C01_ as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                $this->data["csv_import"]["fields"][$_obfuscated_0D2409273113361F0515280D13341607042605371A0222_] = $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_;
            }
            $this->data["csv_import"]["file_encoding"] = "UTF-8";
        }
        $this->data["charsets"] = $this->model_csvprice_pro_app_setting->getCharsets();
        $this->data["action"] = $this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL");
        $this->data["action_export"] = $this->url->link("csvprice_pro/" . $this->AppName . "/export", "token=" . $this->session->data["token"] . "&format=raw", "SSL");
        $this->data["action_import"] = $this->url->link("csvprice_pro/" . $this->AppName . "/import", "token=" . $this->session->data["token"] . "&format=raw", "SSL");
        $this->data["token"] = $this->session->data["token"];
        $this->data["charsets"] = $this->model_csvprice_pro_app_setting->getCharsets();
        $this->outputTemplate();
    }
    public function export()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->session->data["tabs"]["Order"] = "tab_export";
            $this->model_csvprice_pro_app_setting->editSetting("OrderExport", $this->request->post);
            $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_ = $this->model_csvprice_pro_app_setting->getSetting("OrderSetting");
            $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ = $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"];
            unset($this->request->post);
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = $this->model_csvprice_pro_app_order->OrderExport();
            if (is_array($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_) && isset($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"])) {
                $this->session->data["error"] = $this->language->get($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"]);
                $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
            }
            if ($_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ != "UTF-8") {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = @iconv("UTF-8", $_obfuscated_0D345C293C2F3327110D0805012E1524361829091E0601_ . "//TRANSLIT//IGNORE", $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
            }
            $_obfuscated_0D380D061B0D392D24151428193C021D03193B121E2632_ = "order_" . (string) date("Y-m-d-Hi") . ".csv";
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
            $this->data["warning"] = "";
        }
        if (isset($this->session->data["success"])) {
            $this->data["success"] = $this->session->data["success"];
            unset($this->session->data["success"]);
        } else {
            $this->data["success"] = "";
        }
        if (isset($this->session->data["error"])) {
            $this->data["warning"] = $this->session->data["error"];
            unset($this->session->data["error"]);
        } else {
            $this->data["warning"] = "";
        }
        $this->template = "csvprice_pro/" . $this->AppName . ".tpl";
        $this->children = ["common/header", "common/footer", "csvprice_pro/app_header", "csvprice_pro/app_footer"];
        $this->response->setOutput($this->render());
    }
    public function import()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if (!$this->validate()) {
            return $this->forward("error/permission");
        }
        $this->TempDirectory = $this->model_csvprice_pro_app_setting->getTmpDir();
        $this->session->data["tabs"]["Order"] = "tab_import";
        $count = ["total" => 0, "update" => 0, "error" => 0];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && isset($this->request->post["csv_import"])) {
            $this->model_csvprice_pro_app_setting->editSetting("OrderImport", $this->request->post["csv_import"]);
            $_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_ = $this->request->post["csv_import"]["file_encoding"];
            if (!$this->TempDirectory) {
                $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
            }
            $_obfuscated_0D1A190E0B1C1A140A0F0F2B263F2B3F2339350B231E01_ = $this->TempDirectory . time();
            if (!is_uploaded_file($this->request->files["import"]["tmp_name"])) {
                $this->session->data["error"] = $this->language->get("error_uploaded_file");
                $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
            }
            if (!move_uploaded_file($this->request->files["import"]["tmp_name"], $_obfuscated_0D1A190E0B1C1A140A0F0F2B263F2B3F2339350B231E01_)) {
                $this->session->data["error"] = $this->language->get("error_move_uploaded_file");
                $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
            }
            if ($_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_ != "UTF-8") {
                $status = $this->model_csvprice_pro_app_setting->encodingFileToUTF8($_obfuscated_0D1A190E0B1C1A140A0F0F2B263F2B3F2339350B231E01_, $_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_);
                if (is_array($status) && isset($status["error"])) {
                    $this->session->data["error"] = $this->language->get($status["error"]);
                    $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
                }
            }
            $this->model_csvprice_pro_app_setting->replace_file_EOL($_obfuscated_0D1A190E0B1C1A140A0F0F2B263F2B3F2339350B231E01_);
        } else {
            $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
        }
        $result = $this->model_csvprice_pro_app_order->OrderImport($_obfuscated_0D1A190E0B1C1A140A0F0F2B263F2B3F2339350B231E01_);
        $count["total"] += $result["total"];
        $count["update"] += $result["update"];
        $count["error"] += $result["error"];
        unlink($_obfuscated_0D1A190E0B1C1A140A0F0F2B263F2B3F2339350B231E01_);
        if (empty($result) || isset($result["import_error"])) {
            if (isset($result["import_error"])) {
                $this->session->data["error"] = $result["import_error"];
            } else {
                $this->session->data["error"] = $this->language->get("error_import");
            }
        } else {
            $this->session->data["success"] = sprintf($this->language->get("text_success_import"), (int) $count["total"], (int) $count["update"], (int) $count["error"]);
        }
        $this->cache->delete("stock_status");
        $this->goRedirect($this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"));
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
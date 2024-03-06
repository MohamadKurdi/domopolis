<?php

class ControllerCSVPriceProAppAbout extends Controller
{
    private $error = [];
    private $TempDirectory = "";
    private $Mod = true;
    private $AppName;
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->AppName = "app_about";
        $this->load->model("csvprice_pro/app_setting");
        $this->model_csvprice_pro_app_setting->checkInstall();
        $this->model_csvprice_pro_app_setting->checkDBUpdate();
        $this->language->load("csvprice_pro/" . $this->AppName);
        $this->session->data["driver"] = "About";
    }
    public function index()
    {
        if (isset($this->request->post["license_key"]) && !empty($this->request->post["license_key"])) {
            if ($this->validate()) {
                $this->model_csvprice_pro_app_setting->addLicenseKey($this->request->post["license_key"]);
                $this->model_csvprice_pro_app_setting->testDBTableInstall();
                $this->session->data["success"] = $this->language->get("text_success_license_key");
                $this->goRedirect($this->url->link("csvprice_pro/app_about", "token=" . $this->session->data["token"], "SSL"));
            } else {
                return $this->forward("error/permission");
            }
        }
        $this->data["app_version"] = $this->model_csvprice_pro_app_setting->getVersion();
        $this->data["app_name"] = "CSV Price Pro import/export 3";
        $this->data["license_key"] = $this->model_csvprice_pro_app_setting->getLicenseKey();
        $this->data["home_page"] = $this->language->get("home_page");
        $this->data["support_email"] = $this->language->get("support_email");
        if (!$this->data["license_key"]) {
            $this->data["entry_license_key"] = $this->language->get("entry_license_key");
            $this->data["button_save"] = $this->language->get("button_save");
            $this->data["license_key"] = $this->language->get("text_empty_license");
        } else {
            $this->data["license_key"] = $this->data["license_key"];
        }
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->data["text_app_version"] = $this->language->get("text_app_version");
        $this->data["text_license_key"] = $this->language->get("text_license_key");
        $this->data["text_app_name"] = $this->language->get("text_app_name");
        $this->data["text_home_page"] = $this->language->get("text_home_page");
        $this->data["text_author"] = $this->language->get("text_author");
        $this->data["text_support_email"] = $this->language->get("text_support_email");
        $this->data["text_show"] = $this->language->get("text_show");
        $this->data["action"] = $this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL");
        $this->data["token"] = $this->session->data["token"];
        $this->outputTemplate();
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
        if (isset($this->session->data["error_license"])) {
            $this->data["warning"] = $this->session->data["error_license"];
            unset($this->session->data["error_license"]);
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
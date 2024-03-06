<?php
class ControllerCSVPriceProAppCategory extends Controller
{
    private $error = [];
    private $AppName = "app_category";
    private $TempDirectory = "";
    private $Mod = true;
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model("csvprice_pro/app_setting");
        $this->load->model("csvprice_pro/" . $this->AppName);
        $this->model_csvprice_pro_app_setting->checkInstall();
        $this->model_csvprice_pro_app_setting->checkDBUpdate();
        $this->session->data["driver"] = "Category";
        $session = $this->model_csvprice_pro_app_setting->getSetting("Session");
        if (isset($this->session->data["tabs"]["Category"])) {
            $this->data["tab_selected"] = $this->session->data["tabs"]["Category"];
        } else {
            if (isset($session["tabs"]["Category"])) {
                $this->session->data["tabs"] = $session["tabs"];
                $this->data["tab_selected"] = $session["tabs"]["Category"];
            } else {
                $this->session->data["tabs"]["Category"] = "tab_export";
                $this->data["tab_selected"] = $this->session->data["tabs"]["Category"];
            }
        }
        unset($session);
    }
    public function index()
    {
        $this->language->load("csvprice_pro/" . $this->AppName);
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->data["action"] = $this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL");
        if (isset($this->request->post["form_macros_status"])) {
            $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
            if ($this->validate()) {
                if (!isset($this->request->post["category_macros"])) {
                    $this->model_csvprice_pro_app_setting->editSetting("CategoryMacros", []);
                } else {
                    $this->model_csvprice_pro_app_setting->editSetting("CategoryMacros", $this->request->post["category_macros"]);
                }
                $this->session->data["tabs"]["Category"] = "tab_macros";
                $this->session->data["success"] = $this->language->get("text_success_macros");
                $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
            } else {
                return $this->forward("error/permission");
            }
        }
        $this->data["db_table"] = ["category", "category_description"];
        $_obfuscated_0D041723351D390B061B3F073917022A073631093E5C32_ = [];
        foreach ($this->data["db_table"] as $_obfuscated_0D1601262C320A3B012E3F0625072E35231117372D3311_) {
            $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_ = $this->model_csvprice_pro_app_setting->getDbColumn($_obfuscated_0D1601262C320A3B012E3F0625072E35231117372D3311_);
            $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_ = "['" . implode("','", $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_) . "']";
            $_obfuscated_0D041723351D390B061B3F073917022A073631093E5C32_[] = $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_;
        }
        $this->data["db_table_fields"] = "[" . implode(",", $_obfuscated_0D041723351D390B061B3F073917022A073631093E5C32_) . "]";
        $result = $this->model_csvprice_pro_app_setting->getSetting("CategoryMacros");
        if (!empty($result)) {
            $this->data["category_macros"] = $result;
        } else {
            $this->data["category_macros"] = NULL;
        }
        unset($result);
        $this->data["action_export"] = $this->url->link("csvprice_pro/app_category/export", "token=" . $this->session->data["token"], "SSL");
        $this->data["csv_export"] = $this->model_csvprice_pro_app_setting->getSetting("CategoryExport");
        $this->data["core_type"] = $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_ = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->data["csv_export"]["fields_set_data"] = [];
        $a =& $this->data["csv_export"]["fields_set_data"];
        $a[] = ["uid" => "_ID_", "name" => "ID"];
        $a[] = ["uid" => "_PARENT_ID_", "name" => "Parent ID"];
        $a[] = ["uid" => "_NAME_", "name" => "Name"];
        $a[] = ["uid" => "_SEO_KEYWORD_", "name" => "SEO Keyword"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["SEO_TITLE"]) {
            $a[] = ["uid" => "_META_TITLE_", "name" => "HTML Title"];
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["SEO_H1"]) {
            $a[] = ["uid" => "_HTML_H1_", "name" => "HTML H1"];
        }
        $a[] = ["uid" => "_META_KEYWORDS_", "name" => "Meta Keywords"];
        $a[] = ["uid" => "_META_DESCRIPTION_", "name" => "Meta Description"];
        $a[] = ["uid" => "_DESCRIPTION_", "name" => "Description"];
        $a[] = ["uid" => "_IMAGE_", "name" => "Image"];
        $a[] = ["uid" => "_TOP_", "name" => "Top Menu"];
        $a[] = ["uid" => "_COLUMN_", "name" => "Column"];
        $a[] = ["uid" => "_SORT_ORDER_", "name" => "Sort Order"];
        $a[] = ["uid" => "_STATUS_", "name" => "Status"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_FILTER"]) {
            $a[] = ["uid" => "_FILTERS_", "name" => "Filters"];
        }
        $a[] = ["uid" => "_STORE_ID_", "name" => "id Stores"];
        $_obfuscated_0D2312151201172E0A1802042C0F152E18091C3B232211_ =& $this->data["category_macros"];
        if (!empty($_obfuscated_0D2312151201172E0A1802042C0F152E18091C3B232211_) && 0 < count($_obfuscated_0D2312151201172E0A1802042C0F152E18091C3B232211_)) {
            foreach ($_obfuscated_0D2312151201172E0A1802042C0F152E18091C3B232211_ as $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_) {
                $a[] = ["uid" => $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_name"], "name" => $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["field_name"], "caption" => $_obfuscated_0D2409273113361F0515280D13341607042605371A0222_["csv_caption"]];
            }
        }
        foreach ($this->data["csv_export"]["fields_set_data"] as $value) {
            if (isset($value["caption"])) {
                $this->data["fields_set_help"][$value["uid"]] = $value["caption"];
            } else {
                $this->data["fields_set_help"][$value["uid"]] = $this->language->get($value["uid"]);
            }
        }
        $this->data["action_import"] = $this->url->link("csvprice_pro/app_category/import", "token=" . $this->session->data["token"], "SSL");
        $this->data["csv_import"] = $this->model_csvprice_pro_app_setting->getSetting("CategoryImport");
        $this->data["csv_import_key_fields"] = ["_ID_" => "Category ID", "_NAME_" => "Category NAME"];
        $this->data["tab_export"] = $this->language->get("tab_export");
        $this->data["tab_import"] = $this->language->get("tab_import");
        $this->data["tab_macros"] = $this->language->get("tab_macros");
        $this->data["tab_help"] = $this->language->get("tab_help");
        $this->data["entry_file_encoding"] = $this->language->get("entry_file_encoding");
        $this->data["entry_csv_delimiter"] = $this->language->get("entry_csv_delimiter");
        $this->data["entry_csv_text_delimiter"] = $this->language->get("entry_csv_text_delimiter");
        $this->data["entry_languages"] = $this->language->get("entry_languages");
        $this->data["entry_category"] = $this->language->get("entry_category");
        $this->data["entry_store"] = $this->language->get("entry_store");
        $this->data["entry_category_delimiter"] = $this->language->get("entry_category_delimiter");
        $this->data["entry_category_parent"] = $this->language->get("entry_category_parent");
        $this->data["entry_table"] = $this->language->get("entry_table");
        $this->data["entry_field_name"] = $this->language->get("entry_field_name");
        $this->data["entry_csv_name"] = $this->language->get("entry_csv_name");
        $this->data["entry_caption"] = $this->language->get("entry_caption");
        $this->data["entry_import_mode"] = $this->language->get("entry_import_mode");
        $this->data["entry_key_field"] = $this->language->get("entry_key_field");
        $this->data["entry_sort_order"] = $this->language->get("entry_sort_order");
        $this->data["entry_status"] = $this->language->get("entry_status");
        $this->data["entry_import_file"] = $this->language->get("entry_import_file");
        $this->data["entry_import_category_disable"] = $this->language->get("entry_import_category_disable");
        $this->data["entry_import_img_download"] = $this->language->get("entry_import_img_download");
        $this->data["entry_image_url"] = $this->language->get("entry_image_url");
        $this->data["text_default"] = $this->language->get("text_default");
        $this->data["text_hide_all"] = $this->language->get("text_hide_all");
        $this->data["text_show_all"] = $this->language->get("text_show_all");
        $this->data["text_select_all"] = $this->language->get("text_select_all");
        $this->data["text_unselect_all"] = $this->language->get("text_unselect_all");
        $this->data["text_enabled"] = $this->language->get("text_enabled");
        $this->data["text_disabled"] = $this->language->get("text_disabled");
        $this->data["text_none"] = $this->language->get("text_none");
        $this->data["text_no_results"] = $this->language->get("text_no_results");
        $this->data["text_yes"] = $this->language->get("text_yes");
        $this->data["text_no"] = $this->language->get("text_no");
        $this->data["text_import_mode_update"] = $this->language->get("text_import_mode_update");
        $this->data["text_import_mode_insert"] = $this->language->get("text_import_mode_insert");
        $this->data["text_import_mode_both"] = $this->language->get("text_import_mode_both");
        $this->data["text_show_help"] = $this->language->get("text_show_help");
        $this->data["text_select"] = $this->language->get("text_select");
        $this->data["button_insert"] = $this->language->get("button_insert");
        $this->data["button_remove"] = $this->language->get("button_remove");
        $this->data["button_save"] = $this->language->get("button_save");
        $this->data["button_export"] = $this->language->get("button_export");
        $this->data["button_import"] = $this->language->get("button_import");
        $this->data["prop_descr"] = $this->language->get("prop_descr");
        $this->load->model("catalog/category");
        $this->data["categories"] = $this->model_catalog_category->getCategories(0);
        $this->load->model("localisation/language");
        $this->data["languages"] = $this->model_localisation_language->getLanguages();
        foreach ($this->data["languages"] as $language) {
            if (isset($this->error["code" . $language["language_id"]])) {
                $this->data["error_code" . $language["language_id"]] = $this->error["code" . $language["language_id"]];
            } else {
                $this->data["error_code" . $language["language_id"]] = "";
            }
        }
        $this->load->model("setting/store");
        $this->data["stores"] = $this->model_setting_store->getStores();
        $this->data["charsets"] = $this->model_csvprice_pro_app_setting->getCharsets();
        $this->outputTemplate();
    }
    public function export()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->session->data["tabs"]["Category"] = "tab_export";
            $this->model_csvprice_pro_app_setting->editSetting("CategoryExport", $this->request->post["csv_export"]);
            $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"] = $this->request->post["csv_export"]["file_encoding"];
            unset($this->request->post["csv_export"]);
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = $this->model_csvprice_pro_app_category->CategoryExport();
            if (is_array($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_) && isset($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"])) {
                $this->load->language("csvprice_pro/" . $this->AppName);
                $this->session->data["error"] = $this->language->get($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"]);
                $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
            }
            if ($_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"] != "UTF-8") {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = @iconv("UTF-8", $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"] . "//TRANSLIT//IGNORE", $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
            }
            $_obfuscated_0D380D061B0D392D24151428193C021D03193B121E2632_ = "category_export_" . (string) date("Y-m-d-Hi") . ".csv";
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
    public function import()
    {
        $this->language->load("csvprice_pro/" . $this->AppName);
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->session->data["tabs"]["Category"] = "tab_import";
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = ["total" => 0, "update" => 0, "insert" => 0, "error" => 0];
            if (isset($this->request->post["csv_import"])) {
                $this->model_csvprice_pro_app_setting->editSetting("CategoryImport", $this->request->post["csv_import"]);
                $this->TempDirectory = $this->model_csvprice_pro_app_setting->getTmpDir();
                if (!$this->TempDirectory) {
                    $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
                }
                if (is_uploaded_file($this->request->files["import"]["tmp_name"])) {
                    $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"] = $this->TempDirectory . time();
                    if (!move_uploaded_file($this->request->files["import"]["tmp_name"], $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"])) {
                        $this->session->data["error"] = $this->language->get("error_move_uploaded_file");
                        $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
                    } else {
                        if ($this->request->post["csv_import"]["file_encoding"] != "UTF-8") {
                            $status = $this->model_csvprice_pro_app_setting->encodingFileToUTF8($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"], $this->request->post["csv_import"]["file_encoding"]);
                            if (is_array($status) && isset($status["error"])) {
                                $this->session->data["error"] = $this->language->get($status["error"]);
                                $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
                            }
                        }
                        $this->model_csvprice_pro_app_setting->replace_file_EOL($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"]);
                    }
                } else {
                    $this->session->data["error"] = $this->language->get("error_uploaded_file");
                    $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
                }
            }
            $result = $this->model_csvprice_pro_app_category->CategoryImport($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_);
            unlink($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"]);
            if (empty($result) || isset($result["import_error"])) {
                if (isset($result["import_error"])) {
                    $this->session->data["error"] = $result["error_import"];
                } else {
                    $this->session->data["error"] = $this->language->get("error_import");
                }
            } else {
                $this->session->data["success"] = sprintf($this->language->get("text_success_import"), (int) $result["total"], (int) $result["update"], (int) $result["insert"], (int) $result["error"]);
            }
            $this->cache->delete("category");
            $this->cache->delete("seo_url");
            $this->cache->delete("seo_pro");
            $this->goRedirect($this->url->link("csvprice_pro/" . $this->AppName, "token=" . $this->session->data["token"], "SSL"));
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
        $this->template = "csvprice_pro/app_category.tpl";
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
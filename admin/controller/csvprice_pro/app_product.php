<?php

class ControllerCSVPriceProAppProduct extends Controller
{
    private $error = [];
    private $TempDirectory = "";
    private $Mod = true;
    private $SessionKey = "";
    private $AppName = "app_product";
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model("csvprice_pro/app_setting");
        $this->load->model("csvprice_pro/app_product");
        $this->model_csvprice_pro_app_setting->checkInstall();
        $this->model_csvprice_pro_app_setting->checkDBUpdate();
        $this->session->data["driver"] = "Product";
        $this->language->load("csvprice_pro/" . $this->AppName);
        $session = $this->model_csvprice_pro_app_setting->getSetting("Session");
        if (isset($this->session->data["tabs"]["Product"])) {
            $this->data["tab_selected"] = $this->session->data["tabs"]["Product"];
        } else {
            if (isset($session["tabs"]["Product"])) {
                $this->session->data["tabs"] = $session["tabs"];
                $this->data["tab_selected"] = $session["tabs"]["Product"];
            } else {
                $this->session->data["tabs"]["Product"] = "tab_export";
                $this->data["tab_selected"] = $this->session->data["tabs"]["Product"];
            }
        }
        unset($session);
        $this->TempDirectory = $this->model_csvprice_pro_app_setting->getTmpDir();
    }
    public function index()
    {
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->data["action"] = $this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL");
        if (isset($this->request->post["form_product_setting_status"])) {
            $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
            if ($this->validate()) {
                if ($this->request->post["form_product_setting_status"] == 0) {
                    $this->load->model("csvprice_pro/app_setup");
                    $this->model_csvprice_pro_app_setup->defaultProductImportSetting();
                } else {
                    $this->model_csvprice_pro_app_setting->editSetting("ProductSetting", $this->request->post["csv_setting"]);
                }
                $this->session->data["tabs"]["Product"] = "tab_setting";
                $this->session->data["success"] = $this->language->get("text_success_setting");
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            } else {
                return $this->forward("error/permission");
            }
        }
        if (isset($this->request->post["form_macros_status"])) {
            $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
            if ($this->validate()) {
                if (!isset($this->request->post["product_macros"])) {
                    $this->model_csvprice_pro_app_setting->editSetting("ProductMacros", []);
                } else {
                    $this->model_csvprice_pro_app_setting->editSetting("ProductMacros", $this->request->post["product_macros"]);
                }
                $this->session->data["tabs"]["Product"] = "tab_macros";
                $this->session->data["success"] = $this->language->get("text_success_macros");
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            } else {
                return $this->forward("error/permission");
            }
        }
        $this->data["db_table"] = ["product", "product_description"];
        $_obfuscated_0D041723351D390B061B3F073917022A073631093E5C32_ = [];
        foreach ($this->data["db_table"] as $_obfuscated_0D1601262C320A3B012E3F0625072E35231117372D3311_) {
            $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_ = $this->model_csvprice_pro_app_setting->getDbColumn($_obfuscated_0D1601262C320A3B012E3F0625072E35231117372D3311_);
            $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_ = "['" . implode("','", $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_) . "']";
            $_obfuscated_0D041723351D390B061B3F073917022A073631093E5C32_[] = $_obfuscated_0D5C340204083E2E0313140E01283F37352C3234253201_;
        }
        $this->data["db_table_fields"] = "[" . implode(",", $_obfuscated_0D041723351D390B061B3F073917022A073631093E5C32_) . "]";
        $result = $this->model_csvprice_pro_app_setting->getSetting("ProductMacros");
        if (!empty($result)) {
            $this->data["product_macros"] = $result;
        } else {
            $this->data["product_macros"] = NULL;
        }
        unset($result);
        if (isset($this->request->get["profile_id"])) {
            $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_ = $this->model_csvprice_pro_app_product->getProfile($this->request->get["profile_id"]);
            if ($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_) {
                $this->data["tab_selected"] = "tab_import";
                $this->session->data["tabs"]["Product"] = $this->data["tab_selected"];
                if (isset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_import"])) {
                    $this->model_csvprice_pro_app_setting->editSetting("ProductImport", $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_import"]);
                }
                if (isset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_setting"])) {
                    $this->model_csvprice_pro_app_setting->editSetting("ProductSetting", $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_setting"]);
                }
                if (isset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_export"])) {
                    $this->model_csvprice_pro_app_setting->editSetting("ProductExport", $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["csv_export"]);
                    $this->data["tab_selected"] = "tab_export";
                    $this->session->data["tabs"]["Product"] = $this->data["tab_selected"];
                }
                if (!isset($this->session->data["success"])) {
                    $this->session->data["success"] = $this->language->get("text_success_load_profile");
                }
                $this->session->data["profile_id"] = $this->request->get["profile_id"];
            }
            unset($_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_);
            $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
        }
        if (isset($this->session->data["profile_id"])) {
            $this->data["profile_id"] = $this->session->data["profile_id"];
            unset($this->session->data["profile_id"]);
        }
        $this->data["csv_setting"] = $this->model_csvprice_pro_app_setting->getSetting("ProductSetting");
        $this->data["caption_product_setting"] = $this->language->get("caption_product_setting");
        $this->data["help_setting_minimum"] = $this->language->get("help_setting_minimum");
        $this->data["help_setting_stock_status"] = $this->language->get("help_setting_stock_status");
        $this->data["text_default"] = $this->language->get("text_default");
        $this->data["text_none"] = $this->language->get("text_none");
        $this->data["text_enabled"] = $this->language->get("text_enabled");
        $this->data["text_disabled"] = $this->language->get("text_disabled");
        $this->data["text_default_options_setting"] = $this->language->get("text_default_options_setting");
        $this->data["text_confirm_delete"] = $this->language->get("text_confirm_delete");
        $this->data["entry_store"] = $this->language->get("entry_store");
        $this->data["entry_tax_class"] = $this->language->get("entry_tax_class");
        $this->data["entry_minimum"] = $this->language->get("entry_minimum");
        $this->data["entry_subtract"] = $this->language->get("entry_subtract");
        $this->data["entry_stock_status"] = $this->language->get("entry_stock_status");
        $this->data["entry_shipping"] = $this->language->get("entry_shipping");
        $this->data["entry_length_class"] = $this->language->get("entry_length_class");
        $this->data["entry_weight_class"] = $this->language->get("entry_weight_class");
        $this->data["entry_status"] = $this->language->get("entry_status");
        $this->data["entry_sort_order"] = $this->language->get("entry_sort_order");
        $this->data["entry_export_related"] = $this->language->get("entry_export_related");
        $this->data["entry_option_type"] = $this->language->get("entry_option_type");
        $this->data["entry_option_required"] = $this->language->get("entry_option_required");
        $this->data["entry_option_value"] = $this->language->get("entry_option_value");
        $this->data["entry_option_quantity"] = $this->language->get("entry_option_quantity");
        $this->data["entry_option_subtract_stock"] = $this->language->get("entry_option_subtract_stock");
        $this->data["entry_option_price_prefix"] = $this->language->get("entry_option_price_prefix");
        $this->data["entry_option_points_default"] = $this->language->get("entry_option_points_default");
        $this->data["entry_option_points_prefix"] = $this->language->get("entry_option_points_prefix");
        $this->data["entry_option_weight_prefix"] = $this->language->get("entry_option_weight_prefix");
        $this->data["entry_option_weight_default"] = $this->language->get("entry_option_weight_default");
        $this->data["entry_option_template"] = $this->language->get("entry_option_template");
        $this->data["action_export"] = $this->url->link("csvprice_pro/app_product/export", "token=" . $this->session->data["token"], "SSL");
        $this->data["csv_export"] = $this->model_csvprice_pro_app_setting->getSetting("ProductExport");
        $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_ = $this->model_csvprice_pro_app_setting->getSetting("CoreType");
        $this->data["csv_export"]["fields_set_data"] = [];
        $a =& $this->data["csv_export"]["fields_set_data"];
        $a[] = ["uid" => "_ID_", "name" => "ID"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MAIN_CATEGORY"]) {
            $a[] = ["uid" => "_MAIN_CATEGORY_", "name" => "Main Category"];
        }
        $a[] = ["uid" => "_NAME_", "name" => "Name"];
        $a[] = ["uid" => "_MODEL_", "name" => "Model"];
        $a[] = ["uid" => "_SKU_", "name" => "SKU"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["EAN"]) {
            $a[] = ["uid" => "_EAN_", "name" => "EAN"];
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["JAN"]) {
            $a[] = ["uid" => "_JAN_", "name" => "JAN"];
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ISBN"]) {
            $a[] = ["uid" => "_ISBN_", "name" => "ISBN"];
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MPN"]) {
            $a[] = ["uid" => "_MPN_", "name" => "MPN"];
        }
        $a[] = ["uid" => "_UPC_", "name" => "UPC"];
        $a[] = ["uid" => "_MANUFACTURER_", "name" => "Manufacturer"];
        $a[] = ["uid" => "_LOCATION_", "name" => "Location"];
        $a[] = ["uid" => "_PRICE_", "name" => "Price"];
        $a[] = ["uid" => "_DISCOUNT_", "name" => "Discount"];
        $a[] = ["uid" => "_SPECIAL_", "name" => "Special"];
        $a[] = ["uid" => "_OPTIONS_", "name" => "Options"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["PRODUCT_FILTER"]) {
            $a[] = ["uid" => "_FILTERS_", "name" => "Filters"];
        }
        $a[] = ["uid" => "_POINTS_", "name" => "Points"];
        $a[] = ["uid" => "_REWARD_POINTS_", "name" => "Reward Points"];
        $a[] = ["uid" => "_QUANTITY_", "name" => "Quantity"];
        $a[] = ["uid" => "_STOCK_STATUS_", "name" => "Stock status"];
        $a[] = ["uid" => "_STOCK_STATUS_ID_", "name" => "Stock status ID"];
        $a[] = ["uid" => "_SHIPPING_", "name" => "Shipping"];
        $a[] = ["uid" => "_LENGTH_", "name" => "Length"];
        $a[] = ["uid" => "_WIDTH_", "name" => "Width"];
        $a[] = ["uid" => "_HEIGHT_", "name" => "Height"];
        $a[] = ["uid" => "_WEIGHT_", "name" => "Weight"];
        $a[] = ["uid" => "_SEO_KEYWORD_", "name" => "SEO Keyword"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["SEO_H1"]) {
            $a[] = ["uid" => "_HTML_H1_", "name" => "HTML H1"];
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["SEO_TITLE"]) {
            $a[] = ["uid" => "_META_TITLE_", "name" => "HTML Title"];
        }
        $a[] = ["uid" => "_META_KEYWORDS_", "name" => "Meta Keywords"];
        $a[] = ["uid" => "_META_DESCRIPTION_", "name" => "Meta Description"];
        $a[] = ["uid" => "_DESCRIPTION_", "name" => "Description"];
        $a[] = ["uid" => "_ATTRIBUTES_", "name" => "Attributes"];
        $a[] = ["uid" => "_PRODUCT_TAG_", "name" => "Product Tags"];
        $a[] = ["uid" => "_IMAGE_", "name" => "Image"];
        $a[] = ["uid" => "_IMAGES_", "name" => "Images"];
        $a[] = ["uid" => "_SORT_ORDER_", "name" => "Sort Order"];
        $a[] = ["uid" => "_STATUS_", "name" => "Status"];
        $a[] = ["uid" => "_STORE_ID_", "name" => "id Stores"];
        $a[] = ["uid" => "_URL_", "name" => "Url"];
        $this->data["csv_export_key_related"] = ["_RELATED_ID_" => "_RELATED_ID_", "_RELATED_NAME_" => "_RELATED_NAME_", "_RELATED_MODEL_" => "_RELATED_MODEL_", "_RELATED_SKU_" => "_RELATED_SKU_"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["EAN"]) {
            $this->data["csv_export_key_related"]["_RELATED_EAN_"] = "_RELATED_EAN_";
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["JAN"]) {
            $this->data["csv_export_key_related"]["_RELATED_JAN_"] = "_RELATED_JAN_";
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ISBN"]) {
            $this->data["csv_export_key_related"]["_RELATED_ISBN_"] = "_RELATED_ISBN_";
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MPN"]) {
            $this->data["csv_export_key_related"]["_RELATED_MPN_"] = "_RELATED_MPN_";
        }
        $_obfuscated_0D2312151201172E0A1802042C0F152E18091C3B232211_ =& $this->data["product_macros"];
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
        $this->data["action_import"] = $this->url->link("csvprice_pro/app_product/import", "token=" . $this->session->data["token"], "SSL");
        $this->data["csv_import"] = $this->model_csvprice_pro_app_setting->getSetting("ProductImport");
        $this->data["core_type"] = $_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_;
        $this->data["csv_import_key_fields"] = ["_ID_" => "Product ID", "_MODEL_" => "Product Model", "_SKU_" => "Product SKU"];
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["EAN"]) {
            $this->data["csv_import_key_fields"]["_EAN_"] = "Product EAN";
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["JAN"]) {
            $this->data["csv_import_key_fields"]["_JAN_"] = "Product JAN";
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["ISBN"]) {
            $this->data["csv_import_key_fields"]["_ISBN_"] = "Product ISBN";
        }
        if ($_obfuscated_0D353E155C400A1432282C0E1C0C0B371012160C5C1C32_["MPN"]) {
            $this->data["csv_import_key_fields"]["_MPN_"] = "Product MPN";
        }
        $this->data["csv_import_key_fields"]["_NAME_"] = "Product Name";
        $this->data["entry_import_mode"] = $this->language->get("entry_import_mode");
        $this->data["entry_key_field"] = $this->language->get("entry_key_field");
        $this->data["entry_import_id"] = $this->language->get("entry_import_id");
        $this->data["entry_import_delimiter_category"] = $this->language->get("entry_import_delimiter_category");
        $this->data["entry_import_fill_category"] = $this->language->get("entry_import_fill_category");
        $this->data["entry_import_product_disable"] = $this->language->get("entry_import_product_disable");
        $this->data["entry_import_quantity_reset"] = $this->language->get("entry_import_quantity_reset");
        $this->data["entry_import_calc_mode"] = $this->language->get("entry_import_calc_mode");
        $this->data["entry_import_calc_value"] = $this->language->get("entry_import_calc_value");
        $this->data["entry_import_iter_limit"] = $this->language->get("entry_import_iter_limit");
        $this->data["entry_import_file"] = $this->language->get("entry_import_file");
        $this->data["entry_import_manufacturer"] = $this->language->get("entry_import_manufacturer");
        $this->data["entry_import_main_category"] = $this->language->get("entry_import_main_category");
        $this->data["entry_import_category"] = $this->language->get("entry_import_category");
        $this->data["entry_import_category_column"] = $this->language->get("entry_import_category_column");
        $this->data["entry_import_category_top"] = $this->language->get("entry_import_category_top");
        $this->data["entry_import_empty_field"] = $this->language->get("entry_import_empty_field");
        $this->data["entry_import_exclude_filter"] = $this->language->get("entry_import_exclude_filter");
        $this->data["entry_import_exclude_filter_name"] = $this->language->get("entry_import_exclude_filter_name");
        $this->data["entry_import_exclude_filter_desc"] = $this->language->get("entry_import_exclude_filter_desc");
        $this->data["entry_import_exclude_filter_attr"] = $this->language->get("entry_import_exclude_filter_attr");
        $this->data["entry_import_image_path"] = $this->language->get("entry_import_image_path");
        $this->data["entry_csv_delimiter"] = $this->language->get("entry_csv_delimiter");
        $this->data["entry_csv_text_delimiter"] = $this->language->get("entry_csv_text_delimiter");
        $this->data["entry_import_img_download"] = $this->language->get("entry_import_img_download");
        $this->data["entry_image_url"] = $this->language->get("entry_image_url");
        $this->data["prop_descr"] = $this->language->get("prop_descr");
        $this->data["text_show_help"] = $this->language->get("text_show_help");
        $this->data["text_profile_created"] = $this->language->get("text_profile_created");
        $this->data["text_profile_load"] = $this->language->get("text_profile_load");
        $this->data["text_profile_update_success"] = $this->language->get("text_profile_update_success");
        $this->data["text_import_mode_both"] = $this->language->get("text_import_mode_both");
        $this->data["text_import_mode_update"] = $this->language->get("text_import_mode_update");
        $this->data["text_import_mode_insert"] = $this->language->get("text_import_mode_insert");
        $this->data["text_import_mode_supplement"] = $this->language->get("text_import_mode_supplement");
        $this->data["text_import_mode_delete"] = $this->language->get("text_import_mode_delete");
        $this->data["text_import_create_profile"] = $this->language->get("text_import_create_profile");
        $this->data["text_import_calc_mode_off"] = $this->language->get("text_import_calc_mode_off");
        $this->data["text_import_calc_mode_multiply"] = $this->language->get("text_import_calc_mode_multiply");
        $this->data["text_import_calc_mode_divide"] = $this->language->get("text_import_calc_mode_divide");
        $this->data["text_import_calc_mode_pluse"] = $this->language->get("text_import_calc_mode_pluse");
        $this->data["text_import_calc_mode_minus"] = $this->language->get("text_import_calc_mode_minus");
        $this->data["text_import_skip"] = $this->language->get("text_import_skip");
        $this->load->model("catalog/category");
        $this->data["categories"] = $this->model_catalog_category->getCategories(0);
        $this->load->model("catalog/manufacturer");
        $this->data["manufacturers"] = $this->model_catalog_manufacturer->getManufacturers();
        $this->load->model("localisation/language");
        $this->data["languages"] = $this->model_localisation_language->getLanguages();
        foreach ($this->data["languages"] as $language) {
            if (isset($this->error["code" . $language["language_id"]])) {
                $this->data["error_code" . $language["language_id"]] = $this->error["code" . $language["language_id"]];
            } else {
                $this->data["error_code" . $language["language_id"]] = "";
            }
        }
        $this->load->model("localisation/tax_class");
        $this->data["tax_classes"] = $this->model_localisation_tax_class->getTaxClasses();
        $this->load->model("localisation/stock_status");
        $this->data["stock_statuses"] = $this->model_localisation_stock_status->getStockStatuses();
        $this->load->model("localisation/weight_class");
        $this->data["weight_classes"] = $this->model_localisation_weight_class->getWeightClasses();
        $this->load->model("localisation/length_class");
        $this->data["length_classes"] = $this->model_localisation_length_class->getLengthClasses();
        $this->load->model("setting/store");
        $this->data["stores"] = $this->model_setting_store->getStores();
        $this->data["text_all"] = $this->language->get("text_all");
        $this->data["text_yes"] = $this->language->get("text_yes");
        $this->data["text_no"] = $this->language->get("text_no");
        $this->data["text_hide_all"] = $this->language->get("text_hide_all");
        $this->data["text_show_all"] = $this->language->get("text_show_all");
        $this->data["text_no_results"] = $this->language->get("text_no_results");
        $this->data["text_select_all"] = $this->language->get("text_select_all");
        $this->data["text_unselect_all"] = $this->language->get("text_unselect_all");
        $this->data["text_product_filter"] = $this->language->get("text_product_filter");
        $this->data["text_as"] = $this->language->get("text_as");
        $this->data["help_export_category"] = $this->language->get("help_export_category");
        $this->data["help_export_category_by"] = $this->language->get("help_export_category_by");
        $this->data["help_export_delimiter_category"] = $this->language->get("help_export_delimiter_category");
        $this->data["help_export_only_main_category"] = $this->language->get("help_export_only_main_category");
        $this->data["help_export_file_encoding"] = $this->language->get("help_export_file_encoding");
        $this->data["help_export_manufacturer"] = $this->language->get("help_export_manufacturer");
        $this->data["help_export_last_qty"] = $this->language->get("help_export_last_qty");
        $this->data["help_export_limit"] = $this->language->get("help_export_limit");
        $this->data["tab_export"] = $this->language->get("tab_export");
        $this->data["tab_import"] = $this->language->get("tab_import");
        $this->data["tab_setting"] = $this->language->get("tab_setting");
        $this->data["tab_macros"] = $this->language->get("tab_macros");
        $this->data["tab_help"] = $this->language->get("tab_help");
        $this->data["entry_file_encoding"] = $this->language->get("entry_file_encoding");
        $this->data["entry_languages"] = $this->language->get("entry_languages");
        $this->data["entry_table"] = $this->language->get("entry_table");
        $this->data["entry_field_name"] = $this->language->get("entry_field_name");
        $this->data["entry_csv_name"] = $this->language->get("entry_csv_name");
        $this->data["entry_caption"] = $this->language->get("entry_caption");
        $this->data["entry_remove"] = $this->language->get("entry_remove");
        $this->data["entry_category"] = $this->language->get("entry_category");
        $this->data["entry_manufacturer"] = $this->language->get("entry_manufacturer");
        $this->data["entry_export_category"] = $this->language->get("entry_export_category");
        $this->data["entry_category_delimiter"] = $this->language->get("entry_category_delimiter");
        $this->data["entry_category_parent"] = $this->language->get("entry_category_parent");
        $this->data["entry_export_limit"] = $this->language->get("entry_export_limit");
        $this->data["entry_product_filter"] = $this->language->get("entry_product_filter");
        $this->data["entry_filter_quantity"] = $this->language->get("entry_filter_quantity");
        $this->data["entry_filter_name"] = $this->language->get("entry_filter_name");
        $this->data["entry_filter_sku"] = $this->language->get("entry_filter_sku");
        $this->data["entry_filter_location"] = $this->language->get("entry_filter_location");
        $this->data["entry_filter_price"] = $this->language->get("entry_filter_price");
        $this->data["entry_filter_price_range"] = $this->language->get("entry_filter_price_range");
        $this->data["entry_filter_status"] = $this->language->get("entry_filter_status");
        $this->data["button_insert"] = $this->language->get("button_insert");
        $this->data["button_remove"] = $this->language->get("button_remove");
        $this->data["button_save"] = $this->language->get("button_save");
        $this->data["button_export"] = $this->language->get("button_export");
        $this->data["button_import"] = $this->language->get("button_import");
        $this->data["button_load"] = $this->language->get("button_load");
        $this->data["button_delete"] = $this->language->get("button_delete");
        $this->data["button_default"] = $this->language->get("button_default");
        $this->data["help_products_driver"] = $this->language->get("help_products_driver");
        $this->data["text_select"] = $this->language->get("text_select");
        $this->data["charsets"] = $this->model_csvprice_pro_app_setting->getCharsets();
        $this->data["action_add_profile"] = $this->url->link("csvprice_pro/app_product/add_profile", "token=" . $this->session->data["token"], "SSL");
        $this->data["action_edit_profile"] = $this->url->link("csvprice_pro/app_product/edit_profile", "token=" . $this->session->data["token"], "SSL");
        $this->data["action_get_profile"] = $this->url->link("csvprice_pro/app_product/get_profile", "token=" . $this->session->data["token"], "SSL");
        $this->data["action_del_profile"] = $this->url->link("csvprice_pro/app_product/del_profile", "token=" . $this->session->data["token"], "SSL");
        $this->outputTemplate();
    }
    public function export()
    {
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->session->data["tabs"]["Product"] = "tab_export";
            $this->model_csvprice_pro_app_setting->editSetting("ProductExport", $this->request->post["csv_export"]);
            $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"] = $this->request->post["csv_export"]["file_encoding"];
            $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["limit_start"] = $this->request->post["csv_export"]["limit_start"];
            $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["limit_end"] = $this->request->post["csv_export"]["limit_end"];
            unset($this->request->post["csv_export"]);
            $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = $this->model_csvprice_pro_app_product->ProductExport();
            if (is_array($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_) && isset($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"])) {
                $this->load->language("csvprice_pro/app_product");
                $this->session->data["error"] = $this->language->get($_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_["error"]);
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            }
            if ($_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"] != "UTF-8") {
                $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_ = @iconv("UTF-8", $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["file_encoding"] . "//TRANSLIT//IGNORE", $_obfuscated_0D301434263D07352B1E351507182E111E0C1A0C0A0832_);
            }
            $_obfuscated_0D380D061B0D392D24151428193C021D03193B121E2632_ = "product_export_" . $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["limit_start"] . "-" . $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["limit_end"] . "_" . (string) date("Y-m-d-Hi") . ".csv";
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
        $this->Mod;
        $this->data["fields"] = $this->model_csvprice_pro_app_setting->testDBTableInstall();
        if (!$this->validate()) {
            return $this->forward("error/permission");
        }
        $this->session->data["tabs"]["Product"] = "tab_import";
        $count = ["total" => 0, "update" => 0, "insert" => 0, "delete" => 0, "error" => 0];
        if ($this->request->server["REQUEST_METHOD"] == "POST" && isset($this->request->post["csv_import"])) {
            if (empty($this->request->post["csv_import"]["iter_limit"]) || $this->request->post["csv_import"]["iter_limit"] < 100) {
                $this->request->post["csv_import"]["iter_limit"] = 0;
            }
            $this->model_csvprice_pro_app_setting->editSetting("ProductImport", $this->request->post["csv_import"]);
            $_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_ = $this->request->post["csv_import"]["file_encoding"];
            if (!$this->TempDirectory) {
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            }
            if (!is_uploaded_file($this->request->files["import"]["tmp_name"])) {
                $this->session->data["error"] = $this->language->get("error_uploaded_file");
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            }
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"] = time();
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"] = $this->TempDirectory . $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"];
            $this->SessionKey = "k" . $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"];
            if (!move_uploaded_file($this->request->files["import"]["tmp_name"], $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"])) {
                $this->session->data["error"] = $this->language->get("error_move_uploaded_file");
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            }
            if ($_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_ != "UTF-8") {
                $status = $this->model_csvprice_pro_app_setting->encodingFileToUTF8($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"], $_obfuscated_0D121D2615232C1A0E0F2623271E37180E312F02230F22_);
                if (is_array($status) && isset($status["error"])) {
                    $this->session->data["error"] = $this->language->get($status["error"]);
                    $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
                }
            }
            $this->model_csvprice_pro_app_setting->replace_file_EOL($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"]);
        } else {
            if (isset($this->request->get["ftime"])) {
                $this->SessionKey = "k" . $this->request->get["ftime"];
                $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"] = $this->request->get["ftime"];
                if (isset($this->session->data[$this->SessionKey])) {
                    $count["total"] = $this->session->data[$this->SessionKey]["total"];
                    $count["update"] = $this->session->data[$this->SessionKey]["update"];
                    $count["insert"] = $this->session->data[$this->SessionKey]["insert"];
                    $count["error"] = $this->session->data[$this->SessionKey]["error"];
                    $count["delete"] = $this->session->data[$this->SessionKey]["delete"];
                    unset($this->session->data[$this->SessionKey]);
                }
            } else {
                $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
            }
        }
        $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_ = $this->model_csvprice_pro_app_setting->getSetting("ProductImport");
        $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftell"] = 0;
        if (0 < $_obfuscated_0D321D032E3B313D151C3C2C2611050A161A031D3B1C11_["iter_limit"] && isset($this->request->get["ftell"])) {
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"] = $this->request->get["ftime"];
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftell"] = $this->request->get["ftell"];
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"] = $this->TempDirectory . $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"];
        }
        $result = $this->model_csvprice_pro_app_product->ProductImport($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_);
        $count["total"] += $result["total"];
        $count["update"] += $result["update"];
        $count["insert"] += $result["insert"];
        $count["error"] += $result["error"];
        $count["delete"] += $result["delete"];
        if (isset($result["ftell"])) {
            $this->session->data[$this->SessionKey] = ["total" => $count["total"], "update" => $count["update"], "insert" => $count["insert"], "delete" => $count["delete"], "error" => $count["error"]];
            $url = $this->url->link("csvprice_pro/app_product/import", "token=" . $this->session->data["token"] . "&ftell=" . $result["ftell"] . "&ftime=" . $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"], "SSL");
            header("Status: 200");
            header("Location: " . str_replace(["&amp;", "\n", "\r"], ["&", "", ""], $url));
            exit;
        }
        unlink($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["file_name"]);
        if (empty($result) || isset($result["import_error"])) {
            if (isset($result["import_error"])) {
                $this->session->data["error"] = $result["import_error"];
            } else {
                $this->session->data["error"] = $this->language->get("error_import");
            }
        } else {
            $this->session->data["success"] = sprintf($this->language->get("text_success_import"), (int) $count["total"], (int) $count["update"], (int) $count["insert"], (int) $count["delete"], (int) $count["error"]);
        }
        $this->cache->delete("manufacturer");
        $this->cache->delete("category");
        $this->cache->delete("product");
        $this->cache->delete("stock_status");
        $this->cache->delete("seo_pro");
        $this->cache->delete("seo_url");
        unset($this->session->data[$_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["ftime"]]);
        $this->goRedirect($this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"));
    }
    public function add_profile()
    {
        $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_ = [];
        if (isset($this->request->post["profile_import_name"])) {
            $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = ["name" => $this->request->post["profile_import_name"], "key" => "profile_import", "value" => ["csv_setting" => $this->request->post["csv_setting"], "csv_import" => $this->request->post["csv_import"]]];
        } else {
            if (isset($this->request->post["profile_export_name"])) {
                $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_ = ["name" => $this->request->post["profile_export_name"], "key" => "profile_export", "value" => ["csv_export" => $this->request->post["csv_export"]]];
            } else {
                return $this->response->setOutput(json_encode($_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_));
            }
        }
        $id = $this->model_csvprice_pro_app_product->addProfile($_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_);
        $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_ = ["id" => $id, "name" => $_obfuscated_0D343202123626222E2A2F103212190615150D130C0132_["name"]];
        $this->response->setOutput(json_encode($_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_));
    }
    public function edit_profile()
    {
        $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_ = [];
        if (isset($this->request->post["profile_import_select"])) {
            $data = ["profile_id" => $this->request->post["profile_import_select"], "value" => ["csv_setting" => $this->request->post["csv_setting"], "csv_import" => $this->request->post["csv_import"]]];
        } else {
            if (isset($this->request->post["profile_export_select"])) {
                $data = ["profile_id" => $this->request->post["profile_export_select"], "value" => ["csv_export" => $this->request->post["csv_export"]]];
            } else {
                return $this->response->setOutput(json_encode($_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_));
            }
        }
        $this->session->data["success"] = $this->language->get("text_success_setting");
        $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_ = $this->model_csvprice_pro_app_product->editProfile($data);
        $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_ = ["id" => $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["profile_id"], "name" => $_obfuscated_0D13143438021E11291B223E19400E02082E0628101922_["name"]];
        $this->response->setOutput(json_encode($_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_));
    }
    public function del_profile()
    {
        $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_ = [];
        if (isset($this->request->post["profile_id"])) {
            $results = $this->model_csvprice_pro_app_product->deleteProfile($this->request->post["profile_id"]);
        }
        $this->response->setOutput(json_encode($_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_));
    }
    public function get_profile()
    {
        $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_ = [];
        if (isset($this->request->get["key"])) {
            $results = $this->model_csvprice_pro_app_product->getProfile(0, $this->request->get["key"]);
            foreach ($results as $result) {
                $_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_[] = ["id" => $result["profile_id"], "name" => $result["name"]];
            }
        }
        $this->response->setOutput(json_encode($_obfuscated_0D362C3C2B0A0F0B282231120909382C291A240D403511_));
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
        $this->template = "csvprice_pro/app_product.tpl";
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
        if (!$this->user->hasPermission("modify", "csvprice_pro/app_product")) {
            $this->error["warning"] = $this->language->get("error_permission");
        }
        if (!$this->error) {
            return true;
        }
        return false;
    }
}
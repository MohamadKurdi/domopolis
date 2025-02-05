<?php

class ControllerCSVPriceProAppHeader extends Controller
{
    private $AppName = "app_header";
    public function __construct($registry)
    {
        parent::__construct($registry);
    }
    protected function index()
    {
        $this->language->load("csvprice_pro/app_header");
        $this->data["heading_title"] = $this->language->get("heading_title");
        $_obfuscated_0D15210C0138332C2B0C071E0C0222281F1602093D2B01_ = $this->language->get("mod_demo");
        if ($_obfuscated_0D15210C0138332C2B0C071E0C0222281F1602093D2B01_ == "1") {
            $this->data["mdemo_title"] = $this->language->get("mdemo_title");
        }
        $this->data["top_menu"]["product"] = ["url" => $this->url->link("csvprice_pro/app_product", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_product"), "active" => 0];
        $this->data["top_menu"]["category"] = ["url" => $this->url->link("csvprice_pro/app_category", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_category"), "active" => 0];
        $this->data["top_menu"]["manufacturer"] = ["url" => $this->url->link("csvprice_pro/app_manufacturer", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_manufacturer"), "active" => 0];
        $this->data["top_menu"]["crontab"] = ["url" => $this->url->link("csvprice_pro/app_crontab", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_crontab"), "active" => 0];
        $this->data["top_menu"]["customer"] = ["url" => $this->url->link("csvprice_pro/app_customer", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_customer"), "active" => 0];
        $this->data["top_menu"]["order"] = ["url" => $this->url->link("csvprice_pro/app_order", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_order"), "active" => 0];
        $this->data["top_menu"]["about"] = ["url" => $this->url->link("csvprice_pro/app_about", "token=" . $this->session->data["token"], "SSL"), "text" => $this->language->get("text_menu_about"), "active" => 0];
        $_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_ = explode("/", trim(utf8_strtolower($this->request->get["route"]), "/"));
        if (isset($_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[0]) && isset($_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[1])) {
            $_obfuscated_0D162D030F3236393308012C3909400A11243713313432_ = $_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[1];
        } else {
            $_obfuscated_0D162D030F3236393308012C3909400A11243713313432_ = "";
        }
        switch ($_obfuscated_0D162D030F3236393308012C3909400A11243713313432_) {
            case "app_product":
                $this->data["top_menu"]["product"]["active"] = 1;
                break;
            case "app_category":
                $this->data["top_menu"]["category"]["active"] = 1;
                break;
            case "app_manufacturer":
                $this->data["top_menu"]["manufacturer"]["active"] = 1;
                break;
            case "app_customer":
                $this->data["top_menu"]["customer"]["active"] = 1;
                break;
            case "app_order":
                $this->data["top_menu"]["order"]["active"] = 1;
                break;
            case "app_about":
                $this->data["top_menu"]["about"]["active"] = 1;
                break;
            case "app_crontab":
                $this->data["top_menu"]["crontab"]["active"] = 1;
                break;
            default:
                $this->data["top_menu"]["product"]["active"] = 1;
                break;
        }

        $this->template = "csvprice_pro/app_header.tpl";
        $this->render();
    }
}
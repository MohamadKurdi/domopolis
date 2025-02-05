<?php

class ControllerCSVPriceProAppFooter extends Controller
{
    protected function index()
    {
        $this->load->model("csvprice_pro/app_setting");
        $this->load->language("csvprice_pro/app_footer");
        $this->data["text_confirm_delete"] = $this->language->get("text_confirm_delete");
        $this->data["text_select"] = $this->language->get("text_select");
        $this->data["text_show_help"] = $this->language->get("text_show_help");
        $this->data["text_show_help_note"] = $this->language->get("text_show_help_note");
        $_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_ = explode("/", trim(utf8_strtolower($this->request->get["route"]), "/"));
        if (isset($_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[0]) && isset($_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[1])) {
            $_obfuscated_0D162D030F3236393308012C3909400A11243713313432_ = $_obfuscated_0D19272D3E2B2426170F0E362A292F14070B132B2D2A01_[1];
        } else {
            $_obfuscated_0D162D030F3236393308012C3909400A11243713313432_ = "";
        }
        switch ($_obfuscated_0D162D030F3236393308012C3909400A11243713313432_) {
            case "app_product":
                $this->data["note_status"] = 1;
                break;
            case "app_category":
                $this->data["note_status"] = 1;
                break;
            case "app_manufacturer":
                $this->data["note_status"] = 1;
                break;
            default:
                $this->data["note_status"] = 0;
                $this->data["copy"] = "<div class=\"f-text-center\">" . $this->language->get("heading_title") . "</div><div class=\"f-text-center\">" . sprintf($this->language->get("text_copy"), date("Y")) . "</div>";
               break;
        }
        
        $this->template = "csvprice_pro/app_footer.tpl";
        $this->render();
    }
}
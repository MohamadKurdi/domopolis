<?php
class ControllerModuleCSVPricePro extends Controller
{
    public function __construct($registry)
    {
        parent::__construct($registry);
        if (isset($this->request->get["route"])) {
            $route = $this->request->get["route"];
            if (preg_match("/uninstall/i", $route) || preg_match("/install/i", $route)) {
                return NULL;
            }
        }
        $this->load->model("csvprice_pro/app_setting");
        $session = $this->model_csvprice_pro_app_setting->checkInstall();
    }
    public function index()
    {
        $session = $this->model_csvprice_pro_app_setting->getSetting("Session");
        if (isset($this->session->data["driver"])) {
            $session["driver"] = $this->session->data["driver"];
        } else {
            if (!isset($session["driver"])) {
                $session["driver"] = "About";
            }
        }
        switch ($session["driver"]) {
            case "Product":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_product";
                break;
            case "Category":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_category";
                break;
            case "Manufacturer":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_manufacturer";
                break;
            case "Crontab":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_crontab";
                break;
            case "Customer":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_customer";
                break;
            case "Order":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_order";
                break;
            case "Tools":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_tools";
                break;
            case "About":
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_about";
                break;
            default:
                $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_ = "app_about";
                break;               
        }
         $this->redirect($this->url->link("csvprice_pro/" . $_obfuscated_0D0A04230C2D1713151E095B2A25040710162128073911_, "token=" . $this->session->data["token"], "SSL"));
    }
    public function install()
    {
        $this->load->model("csvprice_pro/app_setup");
        $this->model_csvprice_pro_app_setup->Install();
    }
    public function uninstall()
    {
        $this->load->model("csvprice_pro/app_setup");
        $this->model_csvprice_pro_app_setup->Uninstall();
    }
}
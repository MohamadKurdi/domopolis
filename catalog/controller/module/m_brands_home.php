<?

class ControllerModuleMBrandsHome extends Controller {


    public function index() {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/m_brands_home.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/m_brands_home.tpl';
        } else {
            $this->template = 'default/template/module/m_brands_home.tpl';
        }

        $this->render();
    }
}
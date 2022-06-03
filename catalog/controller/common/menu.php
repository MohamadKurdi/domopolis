<?php
class ControllerCommonMenu extends Controller {
    protected function index() {


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/menu.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/menu.tpl';
        } else {
            $this->template = 'default/template/common/menu.tpl';
        }

        $this->render();
    }
}
?>
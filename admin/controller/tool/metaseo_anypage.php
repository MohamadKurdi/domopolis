<?php

class ControllerToolMetaSeoAnyPage extends Controller
{
    private $error = array();
    public function index()
    {
        $this->load->language("tool/metaseo_anypage");
        $this->document->setTitle($this->language->get("heading_title"));
		
        $this->load->model("setting/setting");
        if ($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) {
            $this->model_setting_setting->editSetting("metaseo_anypage", $this->request->post);
            $this->session->data["success"] = $this->language->get("text_success");
			
            $this->redirect($this->url->link('tool/metaseo_anypage', 'token=' . $this->session->data['token'], 'SSL'));
        }
		
		
        if (isset($this->error["warning"])) {
            $this->data["error_warning"] = $this->error["warning"];
        } else {
            if (isset($this->session->data["error"])) {
                $this->data["error_warning"] = $this->session->data["error"];
                unset($this->session->data["error"]);
            } else {
                $this->data["error_warning"] = "";
            }
        }
		
		
        $this->data["breadcrumbs"] = array();
      
		if (isset($this->request->post["metaseo_anypage_routes"])) {
            $this->data["metaseo_anypage_routes"] = $this->request->post["metaseo_anypage_routes"];
        } else {
            $this->data["metaseo_anypage_routes"] = (array) $this->config->get("metaseo_anypage_routes");
        }
		
        if (isset($this->request->post["metaseo_anypage_status"])) {
            $this->data["metaseo_anypage_status"] = $this->request->post["metaseo_anypage_status"];
        } else {
            $this->data["metaseo_anypage_status"] = $this->config->get("metaseo_anypage_status");
        }
		
        $this->data["action"] = $this->url->link("tool/metaseo_anypage", "token=" . $this->session->data["token"], true);
        $this->data["cancel"] = $this->url->link("common/home", "token=" . $this->session->data["token"], true);
        $this->data["heading_title"] = $this->language->get("heading_title");
        $this->data["yes"] = $this->language->get("text_yes");
        $this->data["no"] = $this->language->get("text_no");
        $this->data["entry_status"] = $this->language->get("entry_status");
        $this->data["tab_settings"] = $this->language->get("tab_settings");
        $this->data["tab_help"] = $this->language->get("tab_help");
        $this->data["button_save"] = $this->language->get("button_save");
        $this->data["button_cancel"] = $this->language->get("button_cancel");
        $this->data["text_edit"] = $this->language->get("text_edit");
        $this->data["text_support"] = $this->language->get("text_support");
        $this->load->model("localisation/language");

		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'tool/metaseo_anypage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());		
    }
	
    private function validate()
    {
        if (!$this->user->hasPermission("modify", "tool/metaseo_anypage")) {
            $this->error["warning"] = $this->language->get("text_error_access");
        }
        if (!$this->error) {
            return true;
        }
        return false;
    }
	
    public function install()
    {

    }
    public function uninstall()
    {

    }

}
<?php
/**======================================================================= *
 * =====================Suggest Help Dadata=============================== *
 * ======================================================================= *
 * = @license Commercial                                                 = *
 * = @author Pimur <borodatimur@gmail.com>                               = *
 * = @link https://opencartforum.com/profile/689478-pimur/               = *
 * = @site https://pimur.ru                                              = *
 * ======================================================================= *
 * =======================================================================**/
class ControllerModuleSuggestHelp extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('module/suggest_help');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('view/javascript/suggest_help/bootstrap/css/bootstrap.min.css');
        $this->document->addStyle('view/javascript/suggest_help/bootstrap/css/bootstrap-theme.min.css');

        $this->document->addStyle('view/javascript/suggest_help/codemirror/lib/codemirror.css');
        $this->document->addStyle('view/javascript/suggest_help/codemirror/theme/material.css');
        $this->document->addScript('view/javascript/suggest_help/codemirror/lib/codemirror.js');
        $this->document->addScript('view/javascript/suggest_help/codemirror/mode/javascript/javascript.js');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('suggest_help', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_copyright'] = $this->language->get('text_copyright');
        $this->data['text_edit'] = $this->language->get('text_edit');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['entry_key'] = $this->language->get('entry_key');
        $this->data['entry_status'] = $this->language->get('entry_status');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/suggest_help', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('module/suggest_help', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['suggest_help_key'])) {
            $this->data['suggest_help_key'] = $this->request->post['suggest_help_key'];
        } else {
            $this->data['suggest_help_key'] = $this->config->get('suggest_help_key');
        }

        if (isset($this->request->post['suggest_help_status'])) {
            $this->data['suggest_help_status'] = $this->request->post['suggest_help_status'];
        } else {
            $this->data['suggest_help_status'] = $this->config->get('suggest_help_status');
        }


        $this->data['form_code'] = array();

        // get setting code JavaScript
        if (isset($this->request->post['suggest_help_module'])) {
            $code_info = $this->request->post['suggest_help_module'];
        } else {
            $code_info = $this->config->get('suggest_help_module');
        }

        if ($code_info) {
            foreach ($code_info as $code) {

                if (!isset($code['route'])) {
                    $code['route'] = '';
                }

                if (!isset($code['layout_id'])) {
                    $code['layout_id'] = 0;
                }

                $this->data['form_code'][] = array(
                    'route' => $code['route'],
                    'layout_id' => $code['layout_id'],
                    'data' => $code['data'],
                    'init' => $code['init']
                );
            }
        }


        $this->load->model('design/layout');
        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        $this->template = 'module/suggest_help.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function layout_route()
    {
        $json = array();
        $this->load->model('design/layout');

        if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate()) {
            if (preg_match('/^\+?\d+$/', $this->request->get['layout_id'])) {
                $json['layout_routes'] = $this->model_design_layout->getLayoutRoutes($this->request->get['layout_id']);
            }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/suggest_help')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        return !$this->error;
    }
}
?>
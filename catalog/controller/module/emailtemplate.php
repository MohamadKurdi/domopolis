<?php
class ControllerModuleEmailtemplate extends Controller {

    public function index() {
		$this->view();
    }

    public function view() {
    	if(!empty($this->request->get['id']) && !empty($this->request->get['enc'])){

    		$this->load->model('module/emailtemplate');

    		$log = $this->model_module_emailtemplate->getTemplateLog(array(
    			'emailtemplate_log_id' => $this->request->get['id'],
    			'emailtemplate_log_enc' => $this->request->get['enc']
    		), true);

    		$template = $this->model_module_emailtemplate->getTemplate($log['emailtemplate_id'], true, true);

    		if($template['view_browser_theme']){
    			$this->_view_browser_theme($log, $template);
    		} else {
    			echo $log['html'];
    			exit;
    		}
    	}
    }

    public function record() {
    	if(!empty($this->request->get['id']) && !empty($this->request->get['enc'])){

    		// Skip read for logged in admins
    		/* $this->load->library('user');
    		$this->user = new User($this->registry);
    		if ($this->user->isLogged()) {
    			exit;
    		} */

    		$this->load->model('module/emailtemplate');
    		$this->model_module_emailtemplate->readTemplateLog($this->request->get['id'], $this->request->get['enc']);

    		if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/image/blank.gif')){
    			$src = DIR_TEMPLATE . $this->config->get('config_template') . '/image/blank.gif';
    		} elseif(file_exists(DIR_TEMPLATE . 'default/image/blank.gif')) {
    			$src = DIR_TEMPLATE . 'default/image/blank.gif';
    		} else {
				exit;
    		}

    		header('Content-Type: image/gif');
    		header('Pragma: public');
    		header('Expires: 0');
    		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    		header('Cache-Control: private',false);
    		header('Content-Disposition: attachment; filename="blank.gif"');
    		header('Content-Transfer-Encoding: binary');
    		header('Content-Length: '.filesize($src));
    		readfile($src);

    		exit;
    	}
    }

    private function _view_browser_theme($log, $template){
    	$this->language->load_full('information/information');

    	$this->data['breadcrumbs'] = array();

    	$this->data['breadcrumbs'][] = array(
    		'text'      => $this->language->get('text_home'),
    		'href'      => $this->url->link('common/home'),
    		'separator' => false
    	);

    	$this->document->setTitle($log['subject']);

    	$this->data['heading_title'] = $log['subject'];

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['description'] = html_entity_decode($log['content'], ENT_QUOTES, 'UTF-8');

    	$this->data['continue'] = $this->url->link('common/home');

    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
    		$this->template = $this->config->get('config_template') . '/template/information/information.tpl';
    	} else {
    		$this->template = 'default/template/information/information.tpl';
    	}

    	$this->children = array(
    		'common/column_left',
    		'common/column_right',
    		'common/content_top',
    		'common/content_bottom',
    		'common/footer',
    		'common/header'
    	);

    	$this->response->setOutput($this->render());
    }
}
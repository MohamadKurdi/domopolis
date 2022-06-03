<?php

class ControllerModuleSEOPAGE extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('module/seopage');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('seopage', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
        
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['entry_google'] = $this->language->get('entry_google');
		$this->data['help_google'] = $this->language->get('help_google');
		$this->data['entry_yandex'] = $this->language->get('entry_yandex');
		$this->data['help_yandex'] = $this->language->get('help_yandex');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_nofollow'] = $this->language->get('text_nofollow');
		$this->data['text_follow'] = $this->language->get('text_follow');
		$this->data['entry_follow'] = $this->language->get('entry_follow');
		$this->data['help_follow'] = $this->language->get('help_follow');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['help_description'] = $this->language->get('help_description');
		$this->data['entry_page'] = $this->language->get('entry_page');
		$this->data['help_page'] = $this->language->get('help_page');
		$this->data['header_1'] = $this->language->get('header_1');
		$this->data['header_2'] = $this->language->get('header_2');
		$this->data['entry_pageh1'] = $this->language->get('entry_pageh1');
		$this->data['help_pageh1'] = $this->language->get('help_pageh1');
		$this->data['entry_h1'] = $this->language->get('entry_h1');
		$this->data['help_h1'] = $this->language->get('help_h1');
		$this->data['entry_span'] = $this->language->get('entry_span');
		$this->data['help_span'] = $this->language->get('help_span');		
		$this->data['help_style'] = $this->language->get('help_style');		
		$this->data['entry_style'] = $this->language->get('entry_style');
		$this->data['entry_pattern'] = $this->language->get('entry_pattern');
		$this->data['help_pattern'] = $this->language->get('help_pattern');
		$this->data['text_pattern'] = $this->language->get('text_pattern');
		$this->data['entry_metapattern'] = $this->language->get('entry_metapattern');
		$this->data['text_metapattern'] = $this->language->get('text_metapattern');
		$this->data['help_301'] = $this->language->get('help_301');
		$this->data['entry_301'] = $this->language->get('entry_301');
		$this->data['error_robots'] = $this->language->get('error_robots');
		
		$robots = file_get_contents ('../robots_all.txt');
		$result = strpos ($robots, 'page=');
		if ($result) {
			$this->error['warning'] = $this->language->get('error_robots');
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
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
			'href'      => $this->url->link('module/seopage', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('module/seopage', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];
		
		
    	if (isset($this->request->post['seopage_google'])) {
			$this->data['seopage_google'] = $this->request->post['seopage_google'];
		} else if($this->config->get('seopage_google') !== null)  {
			$this->data['seopage_google'] = $this->config->get('seopage_google');
		}		
        else {
        	$this->data['seopage_google'] = 1;
        }
        
		if (isset($this->request->post['seopage_follow'])) {
			$this->data['seopage_follow'] = $this->request->post['seopage_follow'];
		} elseif($this->config->get('seopage_follow') !== null)  {
			$this->data['seopage_follow'] = $this->config->get('seopage_follow');
		}		
        else {
        	$this->data['seopage_follow'] = 0;
        }
		
		if (isset($this->request->post['seopage_yandex'])) {
			$this->data['seopage_yandex'] = $this->request->post['seopage_yandex'];
		} else if($this->config->get('seopage_yandex') !== null) {
			$this->data['seopage_yandex'] = $this->config->get('seopage_yandex');
		}
        else {
        	$this->data['seopage_yandex'] = 0;
        }
		
		if (isset($this->request->post['seopage_description'])) {
			$this->data['seopage_description'] = $this->request->post['seopage_description'];
		} else if($this->config->get('seopage_description') !== null) {
			$this->data['seopage_description'] = $this->config->get('seopage_description');
		}
        else {
        	$this->data['seopage_description'] = 0;
        }
		
		if (isset($this->request->post['seopage_301'])) {
			$this->data['seopage_301'] = $this->request->post['seopage_301'];
		} else if($this->config->get('seopage_301') !== null) {
			$this->data['seopage_301'] = $this->config->get('seopage_301');
		}
        else {
        	$this->data['seopage_301'] = 0;
        }

		if (isset($this->request->post['seopage_page'])) {
			$this->data['seopage_page'] = $this->request->post['seopage_page'];
		} else if($this->config->get('seopage_page') !== null) {
			$this->data['seopage_page'] = $this->config->get('seopage_page');
		}
        else {
        	$this->data['seopage_page'] = 1;
        }
		
		if (isset($this->request->post['seopage_pageh1'])) {
			$this->data['seopage_pageh1'] = $this->request->post['seopage_pageh1'];
		} else if($this->config->get('seopage_pageh1') !== null) {
			$this->data['seopage_pageh1'] = $this->config->get('seopage_pageh1');
		}
        else {
        	$this->data['seopage_pageh1'] = 0;
        }
		
		if (isset($this->request->post['seopage_h1'])) {
			$this->data['seopage_h1'] = $this->request->post['seopage_h1'];
		} else if($this->config->get('seopage_h1') !== null) {
			$this->data['seopage_h1'] = $this->config->get('seopage_h1');
		}
        else {
        	$this->data['seopage_h1'] = 0;
        }

		if (isset($this->request->post['seopage_span'])) {
			$this->data['seopage_span'] = $this->request->post['seopage_span'];
		} else if($this->config->get('seopage_span') !== null) {
			$this->data['seopage_span'] = $this->config->get('seopage_span');
		}
        else {
        	$this->data['seopage_span'] = 0;
        }		
		
		if (isset($this->request->post['seopage_pattern'])) {
			$this->data['seopage_pattern'] = $this->request->post['seopage_pattern'];
		} else if($this->config->get('seopage_pattern') !== null) {
			$this->data['seopage_pattern'] = $this->config->get('seopage_pattern');
		}
        else {
        	$this->data['seopage_pattern'] = '[h1] ([page] [n])';
        }			
		
		if (isset($this->request->post['seopage_metapattern'])) {
			$this->data['seopage_metapattern'] = $this->request->post['seopage_metapattern'];
		} else if($this->config->get('seopage_metapattern') !== null) {
			$this->data['seopage_metapattern'] = $this->config->get('seopage_metapattern');
		}
        else {
        	$this->data['seopage_metapattern'] = '[desc] ([page] [n])';
        }		
		
		if (isset($this->request->post['seopage_style'])) {
			$this->data['seopage_style'] = $this->request->post['seopage_style'];
		} else if($this->config->get('seopage_style') !== null) {
			$this->data['seopage_style'] = $this->config->get('seopage_style');
		}
        else {
        	$this->data['seopage_style'] = 'color:#636E75;margin-top:0;margin-bottom:20px;font-size:32px;';
        }		
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/seopage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
    }
    

    protected function validate() {
    	if (!$this->user->hasPermission('modify', 'module/seopage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}

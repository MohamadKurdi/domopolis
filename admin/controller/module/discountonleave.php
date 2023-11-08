<?php
class ControllerModuleDiscountOnLeave extends Controller {
	// Module Unifier
	private $moduleName = 'DiscountOnLeave';
	private $moduleNameSmall = 'discountonleave';
	private $moduleData_module = 'discountonleave_module';
	private $moduleModel = 'model_module_discountonleave';
	
	private $error = array();
	// Module Unifier

    public function index() { 
		// Module Unifier
		$this->data['moduleName'] = $this->moduleName;
		$this->data['moduleNameSmall'] = $this->moduleNameSmall;
		$this->data['moduleData_module'] = $this->moduleData_module;
		$this->data['moduleModel'] = $this->moduleModel;
		// Module Unifier
	 
        $this->load->language('module/'.$this->moduleNameSmall);
        $this->load->model('module/'.$this->moduleNameSmall);
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('design/layout');
		
		$this->document->setTitle($this->language->get('heading_title'));
	
        $catalogURL = $this->getCatalogURL();
 		
 		$this->document->addScript('view/javascript/'.$this->moduleNameSmall.'/bootstrap/js/bootstrap.min.js');
        $this->document->addStyle('view/javascript/'.$this->moduleNameSmall.'/bootstrap/css/bootstrap.min.css');
        $this->document->addStyle('view/stylesheet/'.$this->moduleNameSmall.'/font-awesome/css/font-awesome.min.css');
        $this->document->addStyle('view/stylesheet/'.$this->moduleNameSmall.'/summernote.css');
       	$this->document->addStyle('view/stylesheet/'.$this->moduleNameSmall.'/'.$this->moduleNameSmall.'.css');
		$this->document->addScript('view/javascript/'.$this->moduleNameSmall.'/nprogress.js');
		$this->document->addScript('view/javascript/'.$this->moduleNameSmall.'/summernote.min.js');
       
        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }
	
        $store = $this->getCurrentStore($this->request->get['store_id']);
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		/*
			if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleName]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }
            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleName]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }
		*/
			$store = $this->getCurrentStore($this->request->post['store_id']);	
            
			if (!$this->user->hasPermission('modify', 'module/'.$this->moduleNameSmall)) {
                $this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
            }

			$this->session->data['success'] = $this->language->get('text_success');
			
			if(!isset($this->request->post[$this->moduleData_module])) {
				$this->request->post[$this->moduleData_module] = array();
			}

			/*
			$layouts = $this->model_design_layout->getLayouts();
			for($i=0;$i<count($layouts);$i++) {
				$this->request->post[$this->moduleData_module][] = $layouts[$i];
				$this->request->post[$this->moduleData_module][$i]['position'] = 'content_bottom';
				$this->request->post[$this->moduleData_module][$i]['status'] = '1';
				$this->request->post[$this->moduleData_module][$i]['sort_order'] = '0';
			}
			*/ 
			$layout = $this->model_design_layout->getLayout(18);
			$layouts = array($layout);
			for($i=0;$i<count($layouts);$i++) {
				$this->request->post[$this->moduleData_module][] = $layouts[$i];
				$this->request->post[$this->moduleData_module][$i]['position'] = 'content_bottom';
				$this->request->post[$this->moduleData_module][$i]['status'] = '1';
				$this->request->post[$this->moduleData_module][$i]['sort_order'] = '0';
			}
			
			$this->model_module_discountonleave->editSetting($this->moduleNameSmall, $this->request->post[$this->moduleData_module], $this->request->post['store_id']);
			$this->model_module_discountonleave->editSetting($this->moduleNameSmall, $this->request->post, $this->request->post['store_id']);
			
			$this->redirect($this->url->link('module/'.$this->moduleNameSmall,  'store_id='.$this->request->post['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
        }

        if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        $this->data['breadcrumbs']   = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/'.$this->moduleNameSmall, 'token=' . $this->session->data['token']),
        );

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

        $languageVariables = array(
		    // Main
			'heading_title',
			'error_permission',
			'text_success',
			'text_enabled',
			'text_disabled',
			'button_cancel',
			'save_changes',
			'text_default',
			'text_module',
			// Control panel
            'entry_code',
			'entry_code_help',      
            'entry_popup_options',  
			'entry_action_options',
            'button_add_module',
            'button_remove',
			'text_url',
			'entry_content',
			'entry_size',
			'text_show_on',
			'text_window_load',
			'text_page_load',
			'text_body_click'
        );
       
        foreach ($languageVariables as $languageVariable) {
            $this->data[$languageVariable] = $this->language->get($languageVariable);
        }
 
        $this->data['stores'] = array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $this->data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
        $this->data['error_warning']          = '';  
        $this->data['languages']              = $this->model_localisation_language->getLanguages();
        $this->data['store']                  = $store;
        $this->data['token']                  = $this->session->data['token'];
        $this->data['action']                 = $this->url->link('module/'.$this->moduleNameSmall, 'token=' . $this->session->data['token']);
        $this->data['cancel']                 = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
        $this->data['data']                   = $this->{$this->moduleModel}->getSetting($this->moduleNameSmall, $store['store_id']);
        $this->data['modules']				= $this->{$this->moduleModel}->getSetting($this->moduleData_module, $store['store_id']);
        $this->data['layouts']                = $this->model_design_layout->getLayouts();
        $this->data['catalog_url']			= $catalogURL;
		
		if (isset($this->data['data'][$this->moduleName])) {
		// Module Unifier
		$this->data['moduleData'] = $this->data['data'][$this->moduleName];
		// Module Unifier
		}
		
		$this->template = 'module/'.$this->moduleNameSmall.'.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

        $this->response->setOutput($this->render());

    }
	
	public function get_popupwindow_settings() {
        $this->load->model('module/'.$this->moduleNameSmall);
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
		$this->data['currency']			   = $this->config->get('config_currency');
		$this->data['languages']              = $this->model_localisation_language->getLanguages();
		$this->data['popup']['id']		    = $this->request->get['popup_id'];
		$store_id							 = $this->request->get['store_id'];
		$this->data['token']                  = $this->session->data['token'];
		$this->data['data']                   = $this->{$this->moduleModel}->getSetting($this->moduleNameSmall, $store_id);
		$this->data['moduleName']			 = $this->moduleName;
		$this->data['moduleData']			 = (isset($this->data['data'][$this->moduleName])) ? $this->data['data'][$this->moduleName] : array();
		$this->data['newAddition']			= true;


		$this->template = 'module/'.$this->moduleNameSmall.'/tab_popuptab.tpl';
		$this->response->setOutput($this->render());
	}

    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }
    
    public function install() {
	    $this->load->model('module/'.$this->moduleNameSmall);
	    $this->{$this->moduleModel}->install($this->moduleNameSmall);
	}
    
    public function uninstall() {
    	$this->load->model('setting/setting');
		
		$this->load->model('setting/store');
		$this->model_setting_setting->deleteSetting($this->moduleData_module,0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting($this->moduleData_module, $store['store_id']);
		}
		
        $this->load->model('module/'.$this->moduleNameSmall);
        $this->{$this->moduleModel}->uninstall($this->moduleNameSmall);
    }
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/'.$this->moduleNameSmall)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}

?>
<?php
class ControllerModuleCustomTemplate extends Controller {
	private $error = array(); 

	public function index() {   
		$this->load->language('module/custom_template');
		$this->document->setTitle($this->language->get('module_heading_title'));
		
		$this->load->model('setting/setting');
		
		if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }
		
		if (isset($this->request->post['store_id'])){
			$store_id = $this->request->post['store_id'];
			unset($this->request->post['store_id']);
		} else {
			$store_id = 0;			
		}

		
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('custom_template_module', $this->request->post, $store_id);		
				
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/custom_template', 'store_id=' . $this->data['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('module_heading_title');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		//Module types
		$this->data['module_types'] = $this->language->get('module_types');
		
		//Entry
		$this->data['entry_module_type'] 	= $this->language->get('entry_module_type');
		$this->data['entry_category'] 		= $this->language->get('entry_category');
		$this->data['entry_information'] 	= $this->language->get('entry_information');
		$this->data['entry_manufacturer'] 	= $this->language->get('entry_manufacturer');
		$this->data['entry_product'] 		= $this->language->get('entry_product');
		$this->data['entry_template'] 		= $this->language->get('entry_template');
		
		$this->data['text_empty_field'] 	= $this->language->get('text_empty_field');
		$this->data['text_file_success'] 	= $this->language->get('text_file_success');
		$this->data['text_file_failed'] 	= $this->language->get('text_file_failed');
		$this->data['text_file_demo'] 		= $this->language->get('text_file_demo');

		//Load data from models (product, category, inforamtion)
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('catalog/product');
		$this->load->model('setting/store');

        $this->data['stores'] = $this->model_setting_store->getStores();
        $this->data['stores'][] = array('store_id' => 0, 'name' => 'По умолчанию');

       
		$this->data['action_without_store'] = $this->url->link('module/custom_template', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		$this->data['informations'] = $this->model_catalog_information->getInformations();
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		

		$modules = array();

		if (isset($this->request->post['custom_template_module'])) {
			$modules = $this->request->post['custom_template_module'];
		} elseif ($this->model_setting_setting->getSetting('custom_template_module', $this->data['store_id'])) {
			$modules = $this->model_setting_setting->getSetting('custom_template_module', $this->data['store_id']);
			if ($do = isset($modules['custom_template_module'])){
				$modules = $modules['custom_template_module'];				
			}			
		}
		
		if (isset($do)) {
		foreach ($modules as $key => $module) {
			if (!isset($modules[$key]['categories'])) {
				$modules[$key]['categories'] = array();
			}
			if (!isset($modules[$key]['informations'])) {
				$modules[$key]['informations'] = array();
			}
			if (!isset($modules[$key]['manufacturers'])) {
				$modules[$key]['manufacturers'] = array();
			}
			if (!isset($modules[$key]['product_categories'])) {
				$modules[$key]['product_categories'] = array();
			}
			$modules[$key]['parsed_products'] = array();

			if (isset($this->request->post['custom_template_module'][$key]['products'])) {
				$products = explode(',', $this->request->post['custom_template_module'][$key]['products']);
			} else {
				$products = explode(',', $module['products']);
			}
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$modules[$key]['parsed_products'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}			
		}

			$this->data['modules'] = $modules;
		} else {
			$this->data['modules'] = array();			
		}

		$this->data['check_rights'] = $this->url->link('module/custom_template/check_file', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['template_dir'] = DIR_CATALOG.'view'.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$this->config->get('config_template').DIRECTORY_SEPARATOR;
		$this->data['js_entry_template'] = $this->jsAddSlashes(sprintf($this->data['entry_template'], $this->data['template_dir']));

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

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
       		'text'      => $this->language->get('module_heading_title'),
			'href'      => $this->url->link('module/custom_template', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/custom_template' ,'store_id=' . $this->data['store_id'] . '&token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
				
		$this->template = 'module/custom_template.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	private function jsAddSlashes($str) {
	    $pattern = array(
	        "/\\\\/"  , "/\n/"    , "/\r/"    , "/\"/"    ,
	        "/\'/"    , "/&/"     , "/</"     , "/>/"
	    );
	    $replace = array(
	        "\\\\\\\\", "\\n"     , "\\r"     , "\\\""    ,
	        "\\'"     , "\\x26"   , "\\x3C"   , "\\x3E"
	    );
	    return preg_replace($pattern, $replace, $str);
	}
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/custom_template')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
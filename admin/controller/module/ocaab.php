<?php
      //===========================================//
     // Advanced Banners                          //
    // Author: Joel Reeds                        //
   // Company: OpenCart Addons                  //
  // Website: http://opencartaddons.com        //
 // Contact: webmaster@opencartaddons.com     //
//===========================================//

class ControllerModuleOCAAB extends Controller { 
	private $error = array();
	private $version = '3.1';
	private $extension = 'ocaab';	
	private $extensionType = 'module';
	private $stylesheet = 'oca2';
	
	private function getVersion() {
		if (defined('VERSION') && VERSION < 1.5) {
			$oc = 140;
		} elseif (defined('VERSION') && strpos(VERSION, '1.5.0') === 0) {
			$oc = 150;
		} elseif (defined('VERSION') && strpos(VERSION, '1.5.1') === 0) {
			$oc = 151;
		} else {
			$oc = '';
		}
		if (defined('VERSION') && VERSION >= 1.5 && !$oc) {
			$oc = 152;
		}
		return $oc;
	}
	
	public function index() { 
		
		$this->data['extension'] = $this->extension;
		$this->data['ocversion'] = $this->getVersion();
		$this->data['extensiontype'] = $this->extensionType;
		
		$this->data = array_merge($this->data, $this->load->language($this->extensionType . '/' . $this->extension));
		$this->document->addStyle('view/stylesheet/' . $this->stylesheet . '.css');
		$this->document->addScript('view/javascript/oca/jquery.ticker.js');
		
		$this->data['text_contact'] = sprintf($this->data['text_contact'], $this->version);
			
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$post_data = $this->request->post;
			if ($this->getVersion() <= 150) {
				foreach ($post_data as $key => $value) {
					if (is_array($value)) {
						$post_data[$key] = serialize($value);
					}
				}
			}
			$this->model_setting_setting->editSetting($this->extension, $post_data);

			$this->cache->delete($this->extension);

			if (isset($this->request->get['apply'])) {
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->getLink($this->extensionType, $this->extension)); 
			} else {
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->getLink('extension', $this->extensionType));
			}
		}
		
		$this->data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';
		unset($this->session->data['success']);
		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

  		$breadcrumbs = array();

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->getLink('common', 'home'),
      		'separator' => false
   		);

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_' . $this->extensionType),
			'href'      => $this->getLink('extension', $this->extensionType),
      		'separator' => ' :: '
   		);
		
   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_name'),
			'href'      => $this->getLink($this->extensionType, $this->extension),
      		'separator' => ' :: '
   		);
		
		$fields = array('module');
		
		foreach ($fields as $field) {
			$key = $this->extension . '_' . $field;
			$value = isset($this->request->post[$key]) ? $this->request->post[$key] : $this->config->get($key);
			if ($value) {
				$this->data[$key] = $this->getField($value);
			} else {
				$this->data[$key] = '';
			}
		}
		
		$this->data['tips'] = array();
		$tips = array();
		$tips = explode('~', $this->data['text_tips']);
		foreach ($tips as $tip) {
			if ($tip) {
				$this->data['tips'][] = array(
					'text'	=> $tip
				);
			}
		}
		
		$options = array('display_types');
		
		foreach ($options as $option) {
			$x = 0;
			$this->data[$option] = array();
			while (isset($this->data[$option . '_' . $x])) {
				$this->data[$option][] = array(
					'id'	=> $x,
					'name'	=> $this->data[$option . '_' . $x]
				);
				$x++;
			}
		}
		
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
			
		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('design/banner');
		$this->data['banners'] = $this->model_design_banner->getBanners();
		
		$this->data['positions'] 			= array(array('id' => 'content_top', 'name' => $this->data['positions_ct']), array('id' => 'content_bottom', 'name' => $this->data['positions_cb']), array('id' => 'column_left', 'name' => $this->data['positions_cl']), array('id' => 'column_right', 'name' => $this->data['positions_cr']));
		$this->data['floats'] 				= array('none', 'left', 'right');
		$this->data['clears'] 				= array('none', 'left', 'right', 'both');
		$this->data['banner_effects'] 		= array('fade', 'fadeZoom', 'blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'growX', 'growY', 'none', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert', 'shuffle', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover', 'wipe', 'zoom');
		$this->data['slideshow_effects'] 	= array('random', 'sliceDown', 'sliceDownLeft', 'sliceUp', 'sliceUpLeft', 'sliceUpDown', 'sliceUpDownLeft', 'fold', 'fade', 'slideInRight', 'slideInLeft', 'boxRandom', 'boxRain', 'boxRainReverse', 'boxRainGrow', 'boxRainGrowReverse');		
		
		$this->template = $this->extensionType . '/' . $this->extension . '.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		
		$this->data['action'] = $this->getLink($this->extensionType, $this->extension);
		$this->data['cancel'] = $this->getLink('extension', $this->extensionType); 
		
		if ($this->getVersion() < 150) {
			$this->document->title = $this->language->get('text_name');
			$this->document->breadcrumbs = $breadcrumbs;
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
		} else {
			$this->document->setTitle($this->language->get('text_name'));
			$this->data['breadcrumbs'] = $breadcrumbs;
			$this->response->setOutput($this->render());
		}
	}
	
	private function getLink($a, $b) {
		$route = $a . '/' . $b;
		if ($this->getVersion() >= 150) {
			return $this->url->link($route, 'token=' . $this->session->data['token']);
		} else {
			return HTTPS_SERVER . 'index.php?route=' . $route . '&token=' . $this->session->data['token']; 
		}
	}
	
	private function getField($value) {
		if (is_string($value) && strpos($value, 'a:') === 0) {
			$value = unserialize($value);
		}
		
		return $value;
	}
		
	public function install() {
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` MODIFY value LONGTEXT");
	}
	
	public function uninstall() {
	
	}
		
	private function validate() {		
		if (!$this->user->hasPermission('modify', $this->extensionType . '/' . $this->extension)) {
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
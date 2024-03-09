<?php  
class ControllerCommonContentProductMiddle extends Controller {
	protected function index() {
		$this->load->model('design/layout');		
		$this->load->model('catalog/product');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		
		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = array();

		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getExtensions('module');					

		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');

			if ($modules) {
				foreach ($modules as $module) {
					if (($module['layout_id'] == $layout_id) && $module['position'] == 'content_product_middle' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);
					}
				}
			}
		}

		$sort_order = array(); 

		foreach ($module_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $module_data);

		$this->data['modules'] = array();			

		foreach ($module_data as $module) {				
			$module = $this->getChild('module/' . $module['code'], $module['setting']);

			if ($module) {
				$this->data['modules'][] = $module;
			}
		}				

		$this->template = 'common/content_product_middle.tpl';

		$this->render();
	}
}
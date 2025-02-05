<?php  
class ControllerCommonContentBottom extends Controller {
	protected function index() {
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/information');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/actions');
		$this->load->model('setting/extension');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$path = explode('_', (string)$this->request->get['path']);
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));			
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if ($route == 'news/article' && isset($this->request->get['news_id'])) {
			$layout_id = $this->model_catalog_news->getNewsLayoutId($this->request->get['news_id']);
		}
		if ($route == 'news/ncategory' && isset($this->request->get['ncat'])) {
			$ncat = explode('_', (string)$this->request->get['ncat']);

			$layout_id = $this->model_catalog_ncategory->getncategoryLayoutId(end($ncat));			
		}

		if ($route == 'information/actions' && isset($this->request->get['actions_id'])) {
			$layout_id = $this->model_catalog_actions->getActionsLayoutId($this->request->get['actions_id']);
		}

		if ($route == 'product/manufacturer/info' && isset($this->request->get['manufacturer_id'])) {
			$layout_id = $this->model_catalog_manufacturer->getManufacturerLayoutId($this->request->get['manufacturer_id']);
		}     

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = [];

		$extensions 			= $this->model_setting_extension->getExtensions('module');		
		$all_pages_layout_id 	= $this->model_design_layout->getLayoutForAllPages();			

		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');

			if ($modules) {
				foreach ($modules as $module) {
					if (($module['layout_id'] == $layout_id || $module['layout_id'] == $all_pages_layout_id) && $module['position'] == 'content_bottom' && $module['status']) {
						$module_data[] = [
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						];
					}
				}
			}
		}

		$sort_order = []; 

		foreach ($module_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $module_data);

		$this->data['modules'] = [];			

		foreach ($module_data as $module) {				
			$module = $this->getChild('module/' . $module['code'], $module['setting']);

			if ($module) {
				$this->data['modules'][] = $module;
			}
		}				

		$this->template = 'common/content_bottom.tpl';

		$this->render();
	}
}
<?php  
	class ControllerModuleManufacturerSimple extends Controller {
		protected function index($setting) {
			$this->load->model('catalog/manufacturer');
			$this->load->model('tool/image');
			
			
			// Нужно получить все бренды
			$results = $this->model_catalog_manufacturer->getManufacturers(array('sort' => 'm.sort_order', 'order' => 'ASC', 'menu_brand' => 1));
			foreach ($results as $result) {
				
				$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
				'thumb'			  => $this->model_tool_image->resize($result['image'], 150, 100)
				);
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturerSimple.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/manufacturerSimple.tpl';
				} else {
				$this->template = 'default/template/module/manufacturerSimple.tpl';
			}
		
			$this->render();
		}
	}
?>
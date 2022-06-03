<?php  
class ControllerModuleManufacturerabc extends Controller {
	protected function index($setting) {

		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/brandabc.css');
		
				
		$this->load->model('catalog/manufacturer');
				
		$results = $this->model_catalog_manufacturer->getManufacturers();
	
		foreach ($results as $result) {
			if (is_numeric(utf8_substr($result['name'], 0, 1))) {
				$key = '0 - 9';
			} else {
				$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
			}
						
			$this->data['categories'][$key]['name'] = $key;
			
		}
		$this->data['href'] = $this->url->link('product/manufacturer');
	



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturerabc.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/manufacturerabc.tpl';
		} else {
			$this->template = 'default/template/module/manufacturerabc.tpl';
		}
		
		$this->render();
  	}
}
?>
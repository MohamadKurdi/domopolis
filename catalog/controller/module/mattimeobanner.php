<?php
class ControllerModuleMattimeobanner extends Controller {
	protected function index($setting) {
		static $numMod = 0; 
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['template'] = $this->config->get('config_template');
		$this->load->model('tool/image');
		$mod = $setting;

		// tab
		if (isset($mod['tabs'])) {
			foreach ($mod['tabs'] as &$tab) {
				if ($tab['image']) {
					$tab['thumb'] = $this->model_tool_image->resize($tab['image'], 180, 65, '', 80);
					$tab['image'] = $this->model_tool_image->resize($tab['image'], $mod['image_width'], $mod['image_height'], '', 80);					
				} else {
					$tab['thumb'] = $this->model_tool_image->resize('no_image.jpg', 180, 65, '', 80);
					$tab['image'] =  $this->model_tool_image->resize('no_image.jpg', $mod['image_width'], $mod['image_height'], '', 80);				
				}
			}
		}
		
		$this->data['height'] = (int)$mod['image_height'];
		
		// end tab

		$dinamic = 0;
		foreach ($mod as $mod_key => $modVal){
			if ($mod_key == 'dinamic') {
				$dinamic = $modVal;
			}
			$this->data[$mod_key] = $modVal;
		}
		$this->data['lang'] = $this->config->get('config_language_id');

		$this->data['module'] = $numMod;

		if ($dinamic == 5) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mattimeobanner_hotline.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/mattimeobanner_hotline.tpl';
			}
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mattimeobanner.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/mattimeobanner.tpl';
			}
		}

		if (!$this->template) {
			$this->template = 'default/template/module/mattimeobanner.tpl';
		}

		$this->render();
		$numMod++;
	}
}
?>
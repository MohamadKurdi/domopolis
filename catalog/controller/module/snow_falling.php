<?php
class ControllerModuleSnowFalling extends Controller {
	protected function index($setting) {
		$this->document->addScript('catalog/view/javascript/jquery/jquery.snow.min.1.0.js');
		
		$this->data['min_size'] = $setting['min_size'];
		$this->data['max_size'] = $setting['max_size'];
		$this->data['flake_color'] = $setting['flake_color'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/snow_falling.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/snow_falling.tpl';
		} else {
			$this->template = 'default/template/module/snow_falling.tpl';
		}

		$this->render();
	}
}
?>
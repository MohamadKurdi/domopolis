<?php
class ControllerModuleProductList extends Controller {
	protected function index($settings) {
		
		$title = $settings['title'];
		$list = $settings['list'];
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/set');
		$this->load->model('tool/image');
		
		$this->data['title'] = $title;
		$this->data['module_id'] = $module_id;
		$this->data['products'] = array();

		$this->data['product_dimensions'] = array(
			'w' => $this->config->get('config_image_product_width'),
			'h' => $this->config->get('config_image_product_height')
		);

		$results = [];
		foreach ($list as $product_id) {
			$results[] = $this->model_catalog_product->getProduct($product_id);
		}

		$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);

		$this->template = 'product/product_list';
		
		$this->render();
	}
}
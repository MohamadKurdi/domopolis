<?php  
class ControllerModuleSelectedCategory extends Controller {
	protected function index($setting) {
		$this->language->load('module/category');

		$this->data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}		
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = null;
		}
		
		

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		if ($this->config->get('selected_categories'))  {
				
				$this->load->model('catalog/category');
				
				 foreach ($this->config->get('selected_categories') as $id) {
							
						$category = $this->model_catalog_category->getCategory($id);
						
						if ($category['image']) {
							$image=	$category['image'];
							} else {
								$image = 'no_image.jpg';
							}
							
						$image = $this->model_tool_image->resize($image, 80, 80);
						

						
						$this->data['categories'][] = array (
							'name'        => $category['name'],
							'thumb'       => $image,
							'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])			
						);
						
				 };
			};

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/selected_categories.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/selected_categories.tpl';
		} else {
			$this->template = 'default/template/module/selected_categories.tpl';
		}

		$this->render();
	}
}
?>
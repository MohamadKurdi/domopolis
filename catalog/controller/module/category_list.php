<?php
class ControllerModuleCategoryList extends Controller {
	protected function index($args) {
		
		$title = $args['title'];
		$list = $args['list'];
		$manufacturer_id = $args['manufacturer_id'];
		$image_width = $args['image_width'];
		$image_height = $args['image_height'];
		
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		
		$this->data['title'] = $title;
		$this->data['collections'] = array();
		

		foreach ($list as $category_id) {

			$real_category = $this->model_catalog_category->getCategory($category_id);

			if ($real_category['image']) {
				$image = $this->model_tool_image->resize($real_category['image'], $image_width , $image_height);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $image_width , $image_height);
			}

			if ($manufacturer_id){
				$_href = $this->url->link('product/category', 'path=' . $real_category['category_id'] . '&manufacturer_id=' . $manufacturer_id);		
			} else {
				$_href = $this->url->link('product/category', 'path=' . $real_category['category_id']);		
			}

			$this->data['categories'][] = array(
				'category_id'     => $real_category['category_id'],
				'name'       	    => $real_category['name'],					
				'thumb'       	  	=> $image,
				'href'              => $_href
			);
		}
		
		$this->language->load('product/category');

		$this->template = 'product/category_list';
		
		$this->render();
	}
}
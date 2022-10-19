<?php
class ControllerModuleCollectionList extends Controller {
	protected function index($settings) {
		
		$title = $settings['title'];
		$list = $settings['list'];
		$image_width = $settings['image_width'];
		$image_height = $settings['image_height'];

		$this->load->model('catalog/collection');
		$this->load->model('tool/image');
		
		$this->data['title'] = $title;
		$this->data['collections'] = array();
		
		foreach ($list as $cid) {

			$real_collection = $this->model_catalog_collection->getCollection($cid);
			$manufacturer_id = $real_collection['manufacturer_id'];


			if ($real_collection['image']) {
				$image = $this->model_tool_image->resize($real_collection['image'], $image_width , $image_height);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $image_width , $image_height);
			}

			$this->data['collections'][] = array(
				'collection_id'     => $real_collection['collection_id'],
				'name'       	    => $real_collection['name'],
				'short_description' => $real_collection['short_description'],
				'thumb'       	  	=> $image,
				'href'              => $this->url->link('product/collection', 'manufacturer_id=' . $manufacturer_id . '&collection_id=' . $real_collection['collection_id'])
			);
		}
		
		$this->language->load('product/manufacturer');
		$this->data['text_open_collection'] = $this->language->get('open_collection');

		$this->template = 'product/collection_list';
		
		$this->render();		
	}	
}
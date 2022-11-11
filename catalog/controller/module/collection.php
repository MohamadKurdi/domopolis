<?php
class ControllerModuleCollection extends Controller {
	protected function index($setting) {
		$this->load->model('catalog/collection');

		if (isset($this->request->get['manufacturer_id']) && $this->request->get['manufacturer_id']) {
			$manufacturer_id  = (int)$this->request->get['manufacturer_id'];			
		} else {
			$manufacturer_id  = false;
		}

		if (isset($this->request->get['collection_id'])){
			$this_collection = $this->model_catalog_collection->getCollection($this->request->get['collection_id']);
			if ($this_collection['manufacturer_id']){
				$manufacturer_id  = (int)$this_collection['manufacturer_id'];
			}
		}

		$limit = $setting['limit'];

		$this->load->language('module/collection');

		$this->load->model('catalog/collection');
		$results = $this->model_catalog_collection->getCollectionsByManufacturerNoVirtualForShowAll($manufacturer_id, $limit);

		$this->load->model('catalog/manufacturer');
		$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $manufacturer['name']);

		$this->data['collections'] = array();
		foreach ($results as $result){

			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}

			$this->data['collections'][] = array(
				'name' =>  $result['name'],
				'thumb' => $image,
				'href' =>	$this->url->link('product/collection', 'manufacturer_id=' . $result['manufacturer_id'] . '&collection_id=' . $result['collection_id'])
			);

		}

		$this->template = 'module/collection';

		$this->render();
	}
}
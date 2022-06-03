<?php
class ControllerModuleCollectionList extends Controller {
	protected function index($args) {
		
		$title = $args['title'];
		$list = $args['list'];
		$image_width = $args['image_width'];
		$image_height = $args['image_height'];
		
		
		$current_store = (int)$this->config->get('config_store_id');
		$current_lang  = (int)$this->config->get('config_language_id');
		$current_curr  = (int)$this->currency->getId();
		
		$this->bcache->SetFile('collections_list.' . $current_lang. $current_curr . md5(serialize($args)) . '.tpl', 'collection_list'.$current_store);
				
		if ($this->bcache->CheckFile()) {		
		
			$out = $this->bcache->ReturnFileContent();						
			$this->setBlockCachedOutput($out);
		
		} else {
		
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
				
			$this->template = $this->config->get('config_template') . '/template/product/collection_list.tpl';
		
			$out = $this->render();
			$this->bcache->WriteFile($out);
		}
	}
	
}
?>
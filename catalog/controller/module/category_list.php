<?php
class ControllerModuleCategoryList extends Controller {
	protected function index($args) {
		
		$title = $args['title'];
		$list = $args['list'];
		$manufacturer_id = $args['manufacturer_id'];
		$image_width = $args['image_width'];
		$image_height = $args['image_height'];
		
		
		$current_store = (int)$this->config->get('config_store_id');
		$current_lang  = (int)$this->config->get('config_language_id');
		$current_curr  = (int)$this->currency->getId();
		
		$this->bcache->SetFile('categories_list.' . $current_lang. $current_curr . md5(serialize($args)) . '.tpl', 'categories_list'.$current_store);
				
		if ($this->bcache->CheckFile()) {		
		
			$out = $this->bcache->ReturnFileContent();						
			$this->setBlockCachedOutput($out);
		
		} else {
		
			$this->load->model('catalog/category');
			$this->load->model('tool/image');
		
			$this->data['title'] = $title;
			$this->data['collections'] = array();
		
				
			foreach ($list as $cid) {
							
					$real_category = $this->model_catalog_category->getCategory($cid);

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
				
			$this->template = $this->config->get('config_template') . '/template/product/category_list.tpl';
		
			$out = $this->render();
			$this->bcache->WriteFile($out);
		}
	}
	
}
?>
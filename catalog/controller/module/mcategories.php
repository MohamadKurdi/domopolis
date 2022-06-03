<?php
	class ControllerModuleMCategories extends Controller {
		
		
		public function index() {	
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();		
			
			if (isset($this->request->get['manufacturer_id']) && $this->request->get['manufacturer_id'] > 0){
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				
				$this->bcache->SetFile('module.'.$language_id.$currency_id.$manufacturer_id.'.tpl', 'mcategories_module'.$store_id);
				
				if ($this->bcache->CheckFile()) {
					
					$out = $this->bcache->ReturnFileContent();
					$this->setBlockCachedOutput($out);
					
					} else {
					
					$this->load->model('catalog/manufacturer');
					$this->load->model('catalog/collection');
					$this->load->model('module/keyworder');
					$this->load->model('catalog/product');
					$this->load->model('tool/image');
					
					$results_parent_categories  = 	$this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id);
					
					$this->data['manufacturer'] = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
					
					$categories = array();
					foreach ($results_parent_categories as $parent_category){
						$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $parent_category['category_id']);
						$children = array();
						foreach ($results_children as $children_category){
							$children[] = array(
							'name' => $children_category['name'].' '.$this->data['manufacturer']['name'],
							'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
							);
						}
						
						$h1_name = $this->model_module_keyworder->getKeyworderName($parent_category['category_id'], $manufacturer_id);
						if ($h1_name){
							$name = $h1_name;
							} else {
							$name =  $parent_category['name'].' '.$this->data['manufacturer']['name'];
						}
						
						
						$categories[] = array(
						'thumb' => $this->model_tool_image->resize($parent_category['image'], 40, 40),
						'name' => $name,
						'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'&manufacturer_id=' . $manufacturer_id ),
						'children' => $children
						);
					}
					$this->data['categories'] = $categories;
					
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mcategories.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/mcategories.tpl';
						} else {
						$this->template = 'default/template/module/mcategories.tpl';
					}
					
					$out = $this->render();
					$this->bcache->WriteFile($out);
				}
				
				} else {
				
			}
			
		}
	}
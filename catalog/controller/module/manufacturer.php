<?php  
class ControllerModuleManufacturer extends Controller {
	protected function index($setting) {
		
		$store_id = $this->config->get('config_store_id');
		$language_id = $this->config->get('config_language_id');
		$currency_id = $this->currency->getId();
		$position = $setting['position'];
		
		
		if (isset($this->request->get['manufacturer_id']) && $this->request->get['manufacturer_id'] > 0){
			$manufacturer_id = (int)$this->request->get['manufacturer_id'];
			
			$this->data['do_not_show_manufacturer_list'] = (isset($this->request->get['path']) && $this->request->get['path']);
			$category_id = 0;
			if (isset($this->request->get['path']) && $this->request->get['path']){
				$parts = explode('_', (string)$this->request->get['path']);				
				$category_id = (int)array_pop($parts);
			}						
			

			$this->language->load('module/manufacturer');		
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_all_collection'] = $this->language->get('all_collection');


			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/collection');
			$this->load->model('module/keyworder');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			
			$this->data['manufacturer'] = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			$this->data['category_id'] = $category_id;

			$results_parent_categories  = 	$this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id);

			$this->data['categories'] = array();
			foreach ($results_parent_categories as $parent_category){
				$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $parent_category['category_id']);
				$children = array();
				
				$is_current = false;
				foreach ($results_children as $children_category){
					if ($children_category['category_id'] == $category_id){
						$is_current = true;
						break;
					}
				}
				
				unset($children_category);
				foreach ($results_children as $children_category){
					$children[] = array(
						'category_id' => $children_category['category_id'],
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
				
				if (!$is_current){
					$is_current = ($parent_category['category_id'] == $category_id);
				}
				
				$this->data['categories'][] = array(
					'thumb' => $this->model_tool_image->resize($parent_category['image'], 40, 40),
					'name' => $name,
					'is_current' => $is_current,
					'category_id' => $parent_category['category_id'],
					'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'&manufacturer_id=' . $manufacturer_id ),
					'children' => $children
				);
			}
			
			
			$this->data['manufacturers'] = array();					
			$data = array(
				'sort' => 'sort_order'
			);		
			//all manufacturers
			$results = $this->model_catalog_manufacturer->getManufacturers($data);
			foreach($results as $result)
			{	
				
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}
				
				if ($result['manufacturer_id'] != $manufacturer_id) {
					if ($result['sort_order'] != -1) {
						$this->data['manufacturers'][] = array(
							'thumb' => $this->model_tool_image->resize($image, $setting['image_width'], $setting['image_height']),
							'small_thumb' => $this->model_tool_image->resize($image, 50, 50),
							'manufacturer_id' => $result['manufacturer_id'],					
							'name'        => $result['name'],					
							'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
						);
					}
				}
			}
			
			$this->template = 'module/manufacturer_single';
			$this->render();	

		} elseif (!isset($this->request->get['route']) || (isset($this->request->get['route']) && $this->request->get['route'] != 'product/category'  && $this->request->get['route'] != 'product/manufacturer' && !isset($this->request->get['manufacturer_id']))) {

			$this->language->load('module/manufacturer');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_all_collection'] = $this->language->get('all_collection').' ';


			if (isset($this->request->get['path'])) {
				$parts = explode('_', (string)$this->request->get['path']);
			} else {
				$parts = array();
			}
			
			if (isset($parts[0])) {
				$this->data['manufacturer_id'] = $parts[0];
			} else {
				$this->data['manufacturer_id'] = 0;
			}
			
			if (isset($parts[1])) {
				$this->data['child_id'] = $parts[1];
			} else {
				$this->data['child_id'] = 0;
			}
			
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/collection');
			$this->load->model('module/keyworder');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');

			$this->data['manufactureres'] = array();
			
			$data = array(
				'sort' => 'm.sort_order',
				'menu_brand' => 1
			);			
			
			$results = $this->model_catalog_manufacturer->getManufacturers($data);
			foreach($results as $result)
			{
				
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}
				
				$results_parent_categories  = 	$this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($result['manufacturer_id']);
				
				if (is_array($results_parent_categories) && count($results_parent_categories) == 1){
					
					$categories = array();
					foreach ($results_parent_categories as $parent_category){
						
						
						$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($result['manufacturer_id'], $parent_category['category_id']);
						$children = array();
						foreach ($results_children as $children_category){
							
							$categories[] = array(
								'thumb' => $this->model_tool_image->resize($children_category['image'], 40, 40),
								'name' => $children_category['name'].' '.$result['name'],
								'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $result['manufacturer_id']),		
								'children' => false,
							);
						}							
					}
					
					
				} else {
					
					$categories = array();
					foreach ($results_parent_categories as $parent_category){
						
						
						$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($result['manufacturer_id'], $parent_category['category_id']);
						$children = array();
						foreach ($results_children as $children_category){
							$children[] = array(
								'name' => $children_category['name'].' '.$result['name'],
								'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $result['manufacturer_id']),
							);
						}
						
						$h1_name = $this->model_module_keyworder->getKeyworderName($parent_category['category_id'], $result['manufacturer_id']);
						if (isset($h1_name) && $h1_name){
							$name = $h1_name;
						} else {
							$name =  $parent_category['name'].' '.$result['name'];
						}
						
						
						$categories[] = array(
							'thumb' => $this->model_tool_image->resize($parent_category['image'], 40, 40),
							'name' => $name,
							'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'&manufacturer_id=' . $result['manufacturer_id']),
							'children' => $children
						);
					}
					
					
				}
				
				$result_collections = $this->model_catalog_collection->getCollectionsByManufacturer($result['manufacturer_id'], 20);
				$collections = array();
				
				foreach ($result_collections as $collection){					
					$collections[] = array(					
						'name' => $result['name'].' '.$collection['name'],
						'href' => $this->url->link('product/collection', 'manufacturer_id='.$result['manufacturer_id'].'&collection_id='.$collection['collection_id']),
					);
				}
				
				
				if ($result['sort_order'] != -1) {
					$this->data['manufactureres'][] = array(
						'thumb' => $this->model_tool_image->resize($image, $setting['image_width'], $setting['image_height']),
						'small_thumb' => $this->model_tool_image->resize($image, 50, 50),
						'manufacturer_id' => $result['manufacturer_id'],
						'categories'	=> $categories,
						'collections'	=> $collections,
						'name'        => $result['name'],
						'tip'        => $result['tip'],
						'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])			
					);
				}
			}

			$this->template = 'module/manufacturer';
			$this->render();
		}
		
	}
}
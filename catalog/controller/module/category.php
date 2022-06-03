<?php  
	class ControllerModuleCategory extends Controller {
	
		private function getIfToShowFullMenu($category_id){
		
			$query = $this->db->query("SELECT * FROM category WHERE submenu_in_children = 1 AND category_id IN (SELECT path_id FROM category_path WHERE category_id = '" . (int)$category_id . "' AND path_id <> 0)");
		
			return $query->num_rows;
		}
	
		private function getRootCategoryForThis($category_id){
			$query = $this->db->query("SELECT path_id FROM category_path WHERE category_id = '" . (int)$category_id . "' AND path_id <> 0 ORDER BY level ASC LIMIT 1");
		
			if ($query->num_rows){
				return $query->row['path_id'];
			}			
		
			return false;
		}
	
		protected function index($setting) {
			$this->language->load('module/category');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			if (isset($this->request->get['path'])) {
				$parts = explode('_', (string)$this->request->get['path']);
				} else {
				$parts = array();
			}
			
			$this->data['category_id'] = (int)array_pop($parts);			
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$this->load->model('catalog/category');
			
			$store_id = (int)$this->config->get('config_store_id');
			$language_id  = (int)$this->config->get('config_language_id');
			
			$this->bcache->SetFile('module_'.$language_id.$this->data['category_id'].$manufacturer_id.md5(serialize($setting)). '.tpl', 'categories_module'.$store_id);
			
			if ($this->bcache->CheckFile()) {		
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
				$this->data['categories'] = array();
				
				if ($manufacturer_id){
					$this->load->model('catalog/manufacturer');
					$results = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $this->data['category_id']);
					$manufacturer = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
					
					foreach ($results as $result) {					
						if ($result['menu_name']){
							$result['name'] = $result['menu_name'];
						}
						
						$url_s = '';
						if (isset($this->request->get['manufacturer_id'])) {
							$url_s .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
						}		
						
						if (isset($this->request->get['path'])){
							$_href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url_s);
							} else {
							$_href = $this->url->link('product/category', 'path=' . $result['category_id'] . $url_s);
						}
						
						$this->data['categories'][] = array(
						'category_id' => $result['category_id'],
						'name'  => $result['name'],
						'href'  => $_href
						);					
					}
					
					} else {
					
					$this_category = $this->model_catalog_category->getCategory($this->data['category_id']);
					
					$root = $this->data['category_id'];
					if ($this->getIfToShowFullMenu($this->data['category_id'])){
						if ($root_category = $this->getRootCategoryForThis($this->data['category_id'])){
							$root = $root_category;
						}
					}
					
					$categories = $this->model_catalog_category->getCategories($root);				
					
					foreach ($categories as $category) {
						
						$children_data = array();
						/*	
							if ($this_category && isset($this_category['category_id']) && (($category['category_id'] == $this_category['category_id']) || (in_array($category['category_id'], $parts)))){
							$children = $this->model_catalog_category->getCategories($category['category_id']);
							
							foreach ($children as $child) {
							
							$children_data[] = array(
							'category_id' => $child['category_id'],
							'name'        => $child['name'],
							'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
							);		
							}
							}
						*/
						
						if ($this_category && isset($this_category['category_id']) && (($category['category_id'] == $this_category['category_id']) || (in_array($category['category_id'], $parts)))){
							$this->data['opened_category_id'] = (int)$category['category_id'];
						}
						
						$children = $this->model_catalog_category->getCategories($category['category_id']);
						
						foreach ($children as $child) {
							
							$children_data[] = array(
							'category_id' => $child['category_id'],
							'name'        => $child['name'],
							'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
							);		
						}
						
						
						$this->data['categories'][] = array(
						'category_id' => $category['category_id'],
						'name'        => $category['name'],
						'children'    => $children_data,
						'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
						);	
					}
					
				}
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
					} else {
					$this->template = 'default/template/module/category.tpl';
				}
				
				$out = $this->render();
				$this->bcache->WriteFile($out);
			}
		}
	}	
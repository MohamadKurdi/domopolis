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

		private function getParentCategoryForThis($category_id){
			$query = $this->db->query("SELECT path_id FROM category_path WHERE category_id = '" . (int)$category_id . "' AND path_id <> 0 ORDER BY level ASC LIMIT 1");
		
			if ($query->num_rows){
				return $query->row['path_id'];
			}			
		
			return false;
		}
	
		protected function index($setting) {
			$this->language->load('module/category');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$parts = false;
			if (isset($this->request->get['path'])) {
				$parts = explode('_', (string)$this->request->get['path']);
			}
			
			$this->data['category_id'] = $category_id = $parts?(int)array_pop($parts):0;			
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$this->load->model('catalog/category');
			$options = [$manufacturer_id, $category_id];

			$out = $this->cache->get($this->createCacheQueryString(get_class($this), $setting, $options));

			if ($out) {		

				$this->setCachedOutput($out);

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

						if ($this->config->get('config_product_count')){
							$product_count = $this->model_catalog_product->getTotalProducts(['filter_category_id'  => $result['category_id'], 'filter_manufacturer_id' => $this->request->get['manufacturer_id']]);	
						}	
						
						if (isset($this->request->get['path'])){
							$_href = $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url_s);
							} else {
							$_href = $this->url->link('product/category', 'path=' . $result['category_id'] . $url_s);
						}
						
						$this->data['categories'][] = array(
						'category_id' 	=> $result['category_id'],
						'name'  		=> $result['name'],
						'href'  		=> $_href,
						'product_count' => $product_count
						);					
					}
					
					} else {
					
					$currentCategory = $this->model_catalog_category->getCategory($this->data['category_id']);

					$currentRootCategory 		= $this->data['category_id'];
					$currentZeroRootCategory 	= false;
					if ($this->getIfToShowFullMenu($this->data['category_id'])){
						$currentParentCategory 		= $currentCategory['parent_id'];
						$currentZeroRootCategory 	= $this->getRootCategoryForThis($this->data['category_id']);

						if ($setting['only_parent_tree'] && !empty($currentCategory['parent_id'])){
							$currentRootCategory = $currentParentCategory;
						} else {
							$currentRootCategory = $currentZeroRootCategory;
						}
					}
					
					$categories = $this->model_catalog_category->getCategories($currentRootCategory);	

					if 	(count($categories) < $setting['min_for_zero_parent']  && $currentZeroRootCategory && $currentRootCategory!=$currentZeroRootCategory){
						$categories = $this->model_catalog_category->getCategories($currentZeroRootCategory);	
					}
					
					foreach ($categories as $category) {
						if ($currentCategory && isset($currentCategory['category_id']) && (($category['category_id'] == $currentCategory['category_id']) || (in_array($category['category_id'], $parts)))){
							$this->data['opened_category_id'] = (int)$category['category_id'];
						}
						
						$children = $this->model_catalog_category->getCategories($category['category_id']);
						
						$children_data = array();
						foreach ($children as $child) {							
							$children_data[] = array(
							'category_id' 	=> $child['category_id'],
							'name'        	=> $child['name'],
							'href'        	=> $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
							'product_count'	=> $this->config->get('config_product_count')?$child['product_count']:false
							);		
						}
						
						
						$this->data['categories'][] = array(
						'category_id' 	=> $category['category_id'],
						'name'        	=> $category['name'],
						'children'    	=> $children_data,
						'href'        	=> $this->url->link('product/category', 'path=' . $category['category_id']),
						'product_count'	=> $this->config->get('config_product_count')?$category['product_count']:false
						);	
					}					
				}
				
				$this->template = 'module/category';
				
				$out = $this->render();
				$this->cache->set($this->createCacheQueryString(get_class($this), $setting, $options), $out);
			}
		}
	}	
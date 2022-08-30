<?php
class ControllerModuleCategoryWall extends Controller {
	
		//  menu 3rd level
	private function getChildrenData( $ctg_id, $path_prefix )
	{
		$children_data = array();
		$children = $this->model_catalog_category->getCategories($ctg_id);
		
		foreach ($children as $child) {
			$data = array(
				'filter_category_id'  => $child['category_id'],
				'filter_sub_category' => true
			);
			
				//$product_total = $this->model_catalog_product->getTotalProducts($data);
			$product_total = 0;
			
			$children_data[] = array(
				'name'  => $child['name'] .($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
				'href'  => $this->url->link('product/category', 'path=' . $path_prefix . '_' . $child['category_id'])
			);
		}
		return $children_data;
	} 	 	
	
	
	protected function index($setting) {
		static $module = 1;

		$out = $this->cache->get($this->createCacheQueryString(get_class($this), $setting));

		if ($out) {		

			$this->setCachedOutput($out);
			
		} else {
			
			$this->language->load('module/category_wall');		
			
			if (!empty($setting['menu_title'][$this->config->get('config_language_id')])) {
				$this->data['heading_title'] = $setting['menu_title'][$this->config->get('config_language_id')];
			} else {
				$this->data['heading_title'] = $this->language->get('heading_title');
			}
			
			if (!empty($setting['title_status'][$this->config->get('config_language_id')])) {
				$this->data['title_status'] = $setting['title_status'][$this->config->get('config_language_id')];
			} else {
				$this->data['title_status'] = '';
			}
			
			$this->data['design'] = isset($setting['design']) ? $setting['design'] : 'accordion';
			$this->data['view'] = isset($setting['view']) ? $setting['view'] : 'grid';
			
			$this->data['description_status'] = $setting['description_status'];
			$this->data['description_limit'] = !empty($setting['description_limit']) ? $setting['description_limit'] : '-1';
			
			$this->data['image_width'] = !empty($setting['image_width']) ? $setting['image_width'] : '170';
			$this->data['image_height'] = !empty($setting['image_height']) ? $setting['image_height'] : '100';
			
			$this->data['child_image_width'] = !empty($setting['child_image_width']) ? $setting['child_image_width'] : '170';
			$this->data['child_image_height'] = !empty($setting['child_image_height']) ? $setting['child_image_height'] : '100';
			
			$this->data['sub_image'] = isset($setting['sub_image']) ? $setting['sub_image'] : '1';
			
			$this->data['parent_column'] = !empty($setting['parent_column']) ? $setting['parent_column'] : '4';
			$this->data['child_column'] = !empty($setting['child_column']) ? $setting['child_column'] : '4';
			
			$this->data['child_limit'] = !empty($setting['child_limit']) ? $setting['child_limit'] : '12';
			$this->data['child2_limit'] = !empty($setting['child2_limit']) ? $setting['child2_limit'] : '12';
			
			$this->data['box_class'] = !empty($setting['box_class']) ? $setting['box_class'] : 'box';
			$this->data['tag'] = isset($setting['tag']) ? $setting['tag'] : 'div';
			$this->data['heading_class'] = !empty($setting['heading_class']) ? $setting['heading_class'] : 'box-heading';
			$this->data['content_class'] = !empty($setting['content_class']) ? $setting['content_class'] : 'box-content';
			
			if (isset($setting['category_selected'])) {
				$this->data['category_selected'] = $setting['category_selected'];
			} else {
				$this->data['category_selected'] = array();
			}
			
			if (isset($setting['featured_categories']) == 'featured') {
				$categories = $this->data['category_selected'];
			}
			
			if (isset($setting['manufacturer_selected'])) {
				$manufacturers = $setting['manufacturer_selected'];
			} else {
				$manufacturers = '';
			}
			
			if (isset($setting['custom_item'])) {
				$custom_items = $setting['custom_item'];
			} else {
				$custom_items = '';
			}
			
			if (!isset($setting['store_id']) || !in_array($this->config->get('config_store_id'), $setting['store_id'])) {
				return;
			}
			
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
				$this->data['child_id'] = 0;
			}
			
			if (isset($parts[2])) {
				$this->data['child2_id'] = $parts[2];
			} else {
				$this->data['child2_id'] = 0;
			}
			
			$this->load->model('catalog/category_wall');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$this->data['manufacturers'] = array();
			
			if($manufacturers) {
				
				foreach ($manufacturers as $manufacturer_id) {
					$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
					
					if($manufacturer_info) {
						$this->data['manufacturers'][] = array(
							'manufacturer_id' => $manufacturer_info['manufacturer_id'],
							'name'            => $manufacturer_info['name'],
							'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id']),
							'thumb'           => $this->model_tool_image->resize(($manufacturer_info['image']=='' ? 'no_image.jpg' : $manufacturer_info['image']), $this->data['image_width'], $this->data['image_height'])
						);
					}
				}
			}
			
			$this->data['custom_items'] = array();
			
			if($custom_items) {
				
				foreach ($custom_items as $custom_item) {
					
					$this->data['custom_items'][] = array(
						'item_title' => $custom_item['item_title'][$this->config->get('config_language_id')],
						'href'       => $custom_item['href'][$this->config->get('config_language_id')],
						'ciid'       => $custom_item['ciid'],
						'thumb'      => $this->model_tool_image->resize(($custom_item['image']=='' ? 'no_image.jpg' : $custom_item['image']), $this->data['image_width'], $this->data['image_height']),
						'sort_order' => $custom_item['sort_order']
					);
					
					$sort_order = array();
					
					foreach ($this->data['custom_items'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
					
					array_multisort($sort_order, SORT_ASC, $this->data['custom_items']);
				}
			}
			
			$this->data['categories'] = array();
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			if ($setting['featured_categories'] == 'all') {
				
				$this->data['categories'] = array();
				$categories = $this->model_catalog_category->getCategories(0);
				
				foreach ($categories as $category) {
					if ($category['top']) {
							// Level 2
						$children_data = array();
						
						$children = $this->model_catalog_category->getCategories($category['category_id'], 5);
						
						foreach ($children as $child) {
							$data = array(
								'filter_category_id'  => $child['category_id'],
								'filter_sub_category' => true
							);
							
								//$product_total = $this->model_catalog_product->getTotalProducts($data);
							$product_total = 0;
							
							$children_data[] = array(
								'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
								'children' => $this->getChildrenData($child['category_id'], $category['category_id'] . '_' . $child['category_id']),	// menu 3rd level
								'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
							);
							
							
						}
						
						
							// Level 1
						$this->data['categories'][] = array(
							'name'     => $category['name'],
							'img'      => $this->model_tool_image->resize($category['image'], 100,100),
							'children' => $children_data,
							'menu_icon'=> html_entity_decode($category['menu_icon'], ENT_QUOTES, 'UTF-8'),
							'column'   => $category['column'] ? $category['column'] : 1,
							'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
						);
					}
				}
				
			} else {
				
				foreach ($categories as $category_id) {
					$category = $this->model_catalog_category_wall->getCategoryWallCategory($category_id);
					
					if ($category) {
						$children_data = array();
						$children = $this->model_catalog_category_wall->getCategoryWallCategories($category['category_id'], $this->data['child_limit']);
						
						foreach ($children as $child) {
							$children2_data = array();
							$children2 = $this->model_catalog_category_wall->getCategoryWallCategories($child['category_id'], $this->data['child2_limit']);
							
							foreach ($children2 as $child2) {
								$data = array(
									'filter_category_id'  => $child2['category_id'],
									'filter_sub_category' => true
								);
								
								$children2_data[] = array(
									'category_id'  => $child2['category_id'],
									'name'         => $child2['name'] . ($this->config->get('config_product_count') && $setting['count'] ? ' (' . $this->model_catalog_product->getTotalProducts($data) . ')' : ''),
									'active'       => in_array($child2['category_id'], $parts),
									'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id']),
									'thumb'        => $this->model_tool_image->resize(($child2['image']=='' ? 'no_image.jpg' : $child2['image']), $this->data['child_image_width'], $this->data['child_image_height'])
								);
							}
							
							$data = array(
								'filter_category_id'  => $child['category_id'],
								'filter_sub_category' => true
							);
							
							$children_data[] = array(
								'category_id'  => $child['category_id'],
								'name'         => $child['name'] . ($this->config->get('config_product_count') && $setting['count'] ? ' (' . $this->model_catalog_product->getTotalProducts($data) . ')' : ''),
								'title'        => $child['name'],
								'description'  => utf8_substr(strip_tags(html_entity_decode($child['description'], ENT_QUOTES, 'UTF-8')), 0, $this->data['description_limit']) . ($child['description'] && $this->data['description_limit'] > 0 ? '...' : ''),						
								'active'       => in_array($child['category_id'], $parts),
								'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
								'child2_id'    => $children2_data,
								'thumb'        => $this->model_tool_image->resize(($child['image']=='' ? 'no_image.jpg' : $child['image']), $this->data['child_image_width'], $this->data['child_image_height'])
							);
						}
						
						$data = array(
							'filter_category_id'  => $category['category_id'],
							'filter_sub_category' => true
						);
						
						$this->data['categories'][] = array(
							'category_id'  => $category['category_id'],
							'name'         => $category['name'] . ($this->config->get('config_product_count') && $setting['count'] ? ' (' . $this->model_catalog_product->getTotalProducts($data) . ')' : ''),
							'title'        => $category['name'],
							'description'  => utf8_substr(strip_tags(html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8')), 0, $this->data['description_limit']) . ($category['description'] && $this->data['description_limit'] > 0 ? '...' : ''),
							'active'       => in_array($category['category_id'], $parts),
							'href'         => $this->url->link('product/category', 'path=' . $category['category_id']),
							'sort_order'   => $category['sort_order'],
							'children'     => $children_data,
							'thumb'        => $this->model_tool_image->resize(($category['image']=='' ? 'no_image.jpg' : $category['image']), $this->data['image_width'], $this->data['image_height'])
						);
					}
					
					$sort_order = array();
					
					foreach ($this->data['categories'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
					
					array_multisort($sort_order, SORT_ASC, $this->data['categories']);
				}
			}
			
			
			$this->data['module'] = $module++;
			
			if ($setting['featured_categories'] == 'all'){
				$tpl = 'category_wall_allcat_home.tpl';
			} else {
				$tpl = 'category_wall.tpl';
			}
			
			$this->template = 'module/' . $tpl;	

			$out = $this->render();
			$this->cache->set($this->createCacheQueryString(get_class($this), $setting), $out);

		}
	}
}
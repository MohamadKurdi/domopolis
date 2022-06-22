<?
	
	class ControllerModuleMMenuLeft extends Controller {
		
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
		
		public function index() {
			
			if (isset($this->request->get['route'])) {
				$this->data['show'] = false;
				$route = (string)$this->request->get['route'];
				} else {
				$this->data['show'] = true;
				$route = 'common/home';
			}
			
			if (true){
				if ($route == 'common/home'){
					$this->data['show'] = true;
					} else {
					$this->data['show'] = false;
				}
			}
			
			
			$store_id = $this->config->get('config_store_id');
			$language_id = $this->config->get('config_language_id');
			$currency_id = $this->currency->getId();
			
			
			$this->bcache->SetFile('menu_left.'.$store_id.$language_id.$currency_id.(int)$this->data['show'].'.tpl', 'main_menu');
			
			if ($this->bcache->CheckFile()) {
				
				$out = $this->bcache->ReturnFileContent();
				$this->setBlockCachedOutput($out);
				
				} else {
				
				$this->load->model('catalog/category');
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				$this->data['categories'] = array();
				$categories = $this->model_catalog_category->getCategories(0);
				
				foreach ($categories as $category) {
					if ($category['top']) {
						// Level 2
						$children_data = array();
						
						$children = $this->model_catalog_category->getCategories($category['category_id']);
						
						foreach ($children as $child) {
							$data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
							);
							
							//$product_total = $this->model_catalog_product->getTotalProducts($data);
							//$product_total = 0;
							
							$children_data[] = array(
							'name'  => $child['name'],
							'children' => $this->getChildrenData($child['category_id'], $category['category_id'] . '_' . $child['category_id']),	// menu 3rd level
							'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
							);
						}
						
						$results = $this->model_catalog_product->getBestSellerProductsForCategoryByTIME(3, $category['category_id'], 3);
						
						$products_data = $this->model_catalog_product->prepareProductToArray($results);
						
					//	$this->log->debug($products_data);
						
						//getCategoryManufacturers
						$this->load->model('module/keyworder');
						
						$results = $this->model_module_keyworder->getPopularManufacturersByCategories($category['category_id']);
						
						$manufacturers_data = array();
						
						foreach ($results as $result) {
							if ((!empty($result['image']))) {
									$image = $this->model_tool_image->resize($result['image'], 50, 50);
									} else {
									$image = $this->model_tool_image->resize('no_image.jpg', 50, 50);
							}

							if ($result['sort_order'] != -1) {
								$manufacturers_data[] = array(
								'manufacturer_id' => $result['manufacturer_id'],
								'name'       	  => $result['name'],
								'image'       	  => $image,
								'href'            => $this->url->link('product/category', 'path=' . $category['category_id'] . '&manufacturer_id=' . $result['manufacturer_id'])
								);
							}
						}
						
						// Level 1
						$catalogContent = $this->model_catalog_category->getMenuContentByCategoryId($category['category_id'], array('language_id' => $language_id, 'sort_order' => 'ASC'));
						$catalogContentArray = array();
						
						foreach ($catalogContent as $_cc) {
							$cc = $_cc;
							
							$k = $cc['standalone'] == '1' ? 'standalone' : 'not_standalone';
							if ($cc['title']) {
								$catalogContentArray[$k]['name'] = $cc['title'];
							}
							
							if ($_cc['image'] && $_cc['width'] && $_cc['height']){
								$cc['image'] 	  = $this->model_tool_image->resize($_cc['image'], $_cc['width'], $_cc['height'], '', 80);							
							}
							
							$catalogContentArray[$k]['items'][] = $cc;
						}
						
						$this->data['categories'][] = array(
						'name'     => $category['name'],
						'menu_icon'=> html_entity_decode($category['menu_icon'], ENT_QUOTES, 'UTF-8'),
						'img'      => $this->model_tool_image->resize($category['image'], 100,100),
						'children' => $children_data,
						'manufacturers' => $manufacturers_data,
						'products' => $products_data,
						'column'   => $category['column'] ? $category['column'] : 1,
						'href'     => $this->url->link('product/category', 'path=' . $category['category_id']),
						'menu_content' => $catalogContentArray
						);
						
					}
				}
				
				if (true /*$this->config->get('gen_m_brand') == '1' && !($this->config->get('config_monobrand') > 0) */) {
					$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
					$this->data['manufacturers'] = array();
					if($manufacturers){
						foreach($manufacturers as $manufacturer){
							
							$dimensions = array(
								'w' => 50,
								'h' => 50
								);
							
							/*$dimensions = array(
								'w' => 50,
								'h' => 50
							);*/
							
							if ($manufacturer['image']) {
								$img = $this->model_tool_image->resize($manufacturer['image'],  $dimensions['w'], $dimensions['h']);
								} else {
								$img = $this->model_tool_image->resize('no_image.png',  $dimensions['w'], $dimensions['h']);
							};
							
							
							if ($manufacturer['sort_order'] != -1) {
								$this->data['manufacturers'][] = array(
								'name' => $manufacturer['name'],
								'image' => $img,
								'width' => $dimensions['w'],
								'height' => $dimensions['h'],
								'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
								);
							}
						}}
				}
				
				if ($this->config->get('config_monobrand') > 0) {
					$this->load->model('catalog/collection');
					$collections = $this->model_catalog_collection->getCollectionsByManufacturer($this->config->get('config_monobrand'), 50);
					$man = $this->model_catalog_manufacturer->getManufacturer($this->config->get('config_monobrand'));
					
					$this->data['monomanufacturer'] = array(
					'name' => 'Бренд '.$man['name'],
					'href' =>  $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $man['manufacturer_id'])
					);
					
					$this->data['collections'] = array();
					if($collections){
						foreach($collections as $collection){
							
							if ($collection['image']) {
								$img = $this->model_tool_image->resize($collection['image'],  50, 50);
								} else {
								$img = 'catalog/view/theme/mattimeo/image/img_not_found.png';
							};
							
							$this->data['collections'][] = array(
							'name' => $collection['name'],
							'image' => $img,
							'href' => $this->url->link('product/collection', 'collection_id=' . $collection['collection_id'])
							);
						}
					}
				}
				
				$brands = $this->model_catalog_manufacturer->getManufacturers(array('sort' => 'm.sort_order', 'order' => 'ASC', 'limit' => 20, 'menu_brand' => 1));
				foreach ($brands as $k => $b) {
					$brands[$k]['thumb'] = $this->model_tool_image->resize($b['image'], 150, 150);
				}

				$this->data['brands'] = $brands;
				
				$this->data['text_special'] = $this->language->get('text_special');
				$this->data['special'] = $this->url->link('product/special');
				
				$this->load->language('product/manufacturer');
				$this->data['text_manufacturers'] = str_replace(":", "", $this->language->get('text_manufacturers'));
				$this->data['href_manufacturer'] = $this->url->link('product/manufacturer');
				
				
				$this->load->model('design/layout');
				$layout_id = $this->model_design_layout->getLayout('module/mmenu_left');
				if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
					
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template)) {
						$this->template = $this->config->get('config_template') . '/template/' . $template;
						} else {
						$this->template = 'default/template/' . $template;
					}
					} else {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mmenu_left.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/module/mmenu_left.tpl';
						} else {
						$this->template = 'default/template/module/mmenu_left.tpl';
					}
				}
				
				$out = $this->render();
				$this->bcache->WriteFile($out, false, false);
			}
		}
	}	
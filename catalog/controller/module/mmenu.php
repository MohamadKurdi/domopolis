<?

class ControllerModuleMMenu extends Controller {
		
	private function getChildrenData( $ctg_id, $path_prefix )
	{
		$children_data = array();
		$children = $this->model_catalog_category->getCategories($ctg_id);

		foreach ($children as $child) {			
			$children_data[] = array(
				'name'  		=> $child['name'],
				'href'  		=> $this->url->link('product/category', 'path=' . $path_prefix . '_' . $child['category_id']),
				'product_count'	=> $this->config->get('config_product_count')?$child['product_count']:false
			);
		}
		return $children_data;
	} 	 	

	public function index() {


		$out = $this->cache->get($this->createCacheQueryString(get_class($this)));

		if ($out) {		

			$this->setCachedOutput($out);
			
		} else {

			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');

			$this->load->model('tool/image');
			$this->data['categories'] = array();
			$categories = $this->model_catalog_category->getCategories(0);

			foreach ($categories as $category) {
				if ($category['top']) {						
					$children_data = array();

					$children = $this->model_catalog_category->getCategories($category['category_id']);

					foreach ($children as $child) {
						$children_data[] = array(
							'name'  		=> $child['name'],
							'children' 		=> $this->getChildrenData($child['category_id'], $category['category_id'] . '_' . $child['category_id']),
							'href'  		=> $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
							'product_count'	=> $this->config->get('config_product_count')?$child['product_count']:false
						);
					}

					$bestsellerLimit = 3;
					if (count($children) > 15){
						$bestsellerLimit = 3;
					}

					if (count($children) > 30){
						$bestsellerLimit = 1;
					}

					$results = $this->model_catalog_product->getBestSellerProductsForCategoryByTIME($bestsellerLimit, $category['category_id'], 3);

					$this->data['dimensions'] = array(
						'w' => $this->config->get('config_image_product_width'),
						'h' => $this->config->get('config_image_product_height')
					);

					$products_data = $this->model_catalog_product->prepareProductToArray($results);												


					$this->load->model('module/keyworder');

					$results = $this->model_module_keyworder->getPopularManufacturersByCategories($category['category_id']);
					$manufacturers_data = array();

					foreach ($results as $result) {
						if ((!empty($result['image']))) {
							$image = $this->model_tool_image->resize($result['image'], 300, 300);
						} else {
							$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 300, 300);
						}
						if ($result['sort_order'] != -1) {
							$manufacturers_data[] = array(
								'manufacturer_id' 	=> $result['manufacturer_id'],
								'name'       	  	=> $result['name'],
								'image' 			=> $image,
								'href'            	=> $this->url->link('product/category', 'path=' . $category['category_id'] . '&manufacturer_id=' . $result['manufacturer_id'])
							);
						}
					}

					$this->data['categories'][] = array(
						'name'     		=> $category['name'],
						'img'      		=> $this->model_tool_image->resize($category['image'], 100,100),
						'children' 		=> $children_data,
						'menu_icon'		=> html_entity_decode($category['menu_icon'], ENT_QUOTES, 'UTF-8'),
						'manufacturers' => $manufacturers_data,
						'products' 		=> $products_data,
						'column'   		=> $category['column'] ? $category['column'] : 1,
						'href'     		=> $this->url->link('product/category', 'path=' . $category['category_id']),
						'product_count'	=> $this->config->get('config_product_count')?$category['product_count']:false						
					);


				}
			}			

			$this->data['text_special'] = $this->language->get('text_special');
			$this->data['special'] = $this->url->link('product/special');

			$this->load->language('product/manufacturer');
			$this->data['text_manufacturers'] = $this->language->get('text_manufacturers');
			$this->data['text_all_manufacturers'] = $this->language->get('text_all_manufacturers');
			$this->data['text_popular_products'] = $this->language->get('text_popular_products');
			$this->data['href_manufacturer'] = $this->url->link('product/manufacturer');

			$this->data['brands'] = [];

			if ($this->config->get('config_brands_in_mmenu')){
				$brands = $this->model_catalog_manufacturer->getManufacturers(array('sort' => 'm.sort_order', 'order' => 'ASC', 'limit' => 20, 'menu_brand' => 1));
				foreach ($brands as $k => $b) {
					$brands[$k]['thumb'] = $this->model_tool_image->resize($b['image'], 150, 100);
					$brands[$k]['url'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $b['manufacturer_id']);
				}

				$this->data['brands'] = $brands;
			}

			$this->load->model('design/layout');
			$layout_id = $this->model_design_layout->getLayout('module/mmenu');
			if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
				$this->template = $template;
			} else {
				$this->template = 'module/mmenu.tpl';
			}

			$out = $this->render();
			$this->cache->set($this->createCacheQueryString(get_class($this)), $out);

		}
	}
}				
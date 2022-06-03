<?php
class ControllerModulemattimeomenucategory extends Controller {
	protected function index($setting) {
		static $numMod = 1; // blanc first template module
		
	//	$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/mattimeomenucategory.css');
        $this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		

		
		// tab
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->data['tabs'] = array();
		
		$tabs = array();
		$tabs = $this->config->get('mattimeomenucategory_tab');
		
		if (isset($tabs)) {
			foreach ($tabs as $tab) {
				
				$data = array(
					'filter_category_id' => $tab['category_id'],
					'sort'  => 'pd.name',
					'order' => 'ASC',
					'start' => 0,
					'limit' => $setting['limit'],
				);
				
				//Latest Product
				$results = array();
				$results = $this->model_catalog_product->getProducts($data);
				
				$products = array();
                	foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
						$this->data['widthimg']   = $setting['image_width'];
						
						$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
                      
					    }

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}
							
					if ((float)$result['special']) { 
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}
					
					$products[] = array(
						'product_id' => $result['product_id'],
						'quickview'        => $this->url->link('product/quickview', 'product_id=' . $result['product_id']),
						'thumb'   	 => $image,
						'name'    	 => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 120) . '..',
						'price'   	 => $price,
						'special' 	 => $special,
						'saving'	=> round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
						'rating'     => $rating,
						'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					);
				}
				
				
				
               if ($tab['image']) {
					$image = $this->model_tool_image->resize($tab['image'], $setting['image_category_width'], $setting['image_category_height']);
				} else {
					$image = false;
				}

				
			$catagory_name = $this->model_catalog_category->getCategory($tab['category_id']);
			if (isset($catagory_name['name'])) {
					$catagory_name['name'] = $catagory_name['name'];
				} else {
					$catagory_name['name'] = false;
				}
				
			$children_data = array();

			$children = $this->model_catalog_category->getCategories($tab['category_id']);


			foreach ($children as $child) {
				$data = array(
					'filter_category_id'  => $child['category_id'],
					'filter_sub_category' => true
				);
               
			   if ($child['image']) {
				   if (($setting['image_subcategory_width'] =='') || ($setting['image_subcategory_height'] =='')){
					 $imagechild = false;  
					 } else {  
					$imagechild = $this->model_tool_image->resize($child['image'], $setting['image_subcategory_width'], $setting['image_subcategory_height']);
				   }
				} else {
					$imagechild = false;
				}

				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'        => $child['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($child['description'], ENT_QUOTES, 'UTF-8')), 0, 100),
					'image'       => $imagechild,
					'href'        => $this->url->link('product/category', 'path=' . $tab['category_id'] . '_' . $child['category_id']),
					'children' => $this->getChildrenData($child['category_id'], $child['category_id']),	// rb, 2011-09-03: menu 3rd level	
				);		
			}
				
				$this->data['tabs'][] = array(
					'image' 		 => $image,
					'name'			 => $catagory_name['name'],
					'href'           => $this->url->link('product/category', 'path=' . $tab['category_id']),
					'title'	 		 =>	$tab['title'][$this->config->get('config_language_id')],
					'subcateg'	 	 =>	$tab['subcateg'],
					'column'	 	 =>	$tab['column'],
					'showproducts'	  =>	$tab['showproducts'],
					'children'        => $children_data,
					'products'       => $products,
				);
			}
		}
		// end tab
		
		$this->data['module'] =  $numMod++;
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mattimeomenucategory.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/mattimeomenucategory.tpl';
		} else {
			$this->template = 'default/template/module/mattimeomenucategory.tpl';
		}

        $this->data['position'] = $setting['position'];
		$this->render();
	}
	private function getChildrenData( $ctg_id, $path_prefix )
	{
		$children_data = array();
		$children = $this->model_catalog_category->getCategories($ctg_id);

		foreach ($children as $child) {
			$data = array(
				'filter_category_id'  => $child['category_id'],
				'filter_sub_category' => true
			);

			$product_total = $this->model_catalog_product->getTotalProducts($data);

			$children_data[] = array(
				'name'  => $child['name'] .($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
				'href'  => $this->url->link('product/category', 'path=' . $path_prefix . '_' . $child['category_id'])
			);
		}
		return $children_data;
	} 	
	
	
}
?>
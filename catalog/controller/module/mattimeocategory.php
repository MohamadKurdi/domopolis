<?php
class ControllerModulemattimeocategory extends Controller {
	protected function index($setting) {
		static $numMod = 1; // blanc first template module
		
		$this->language->load('module/mattimeocategory');
 
      	$this->data['heading_latest'] = $this->language->get('heading_latest');

		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/mattimeocategory.css');
        $this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['text_show'] = $this->language->get('text_show');
		$this->data['template'] = $this->config->get('config_template');
		
		// tab
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->data['tabs'] = array();
		
		$tabs = array();
		$tabs = $this->config->get('mattimeocategory_tab');
		$this->data['limit_v'] 	     = $setting['limit_v'];
		$this->data['category_width'] = $setting['category_width'];
		
		if (isset($tabs)) {
			foreach ($tabs as $tab) {
				
				$data = array(
					'filter_category_id' => $tab['category_id'],
					'sort'  => 'pd.name',
					'order' => 'ASC',
					'start' => 0,
					'limit' => $setting['limit'],
					'limit_v' => $setting['limit_v'],
					'category_width' => $setting['category_width'],
				);
				
				//Latest Product
				$results = array();
				$results = $this->model_catalog_product->getProducts($data);
				
				$products = array();
                	foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
						$this->data['widthimg']   = $setting['image_width'];
						
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
				
				
				
				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
		 	    $way = $this->config->get('config_ssl') . 'image/';
		        } else {
			    $way = $this->config->get('config_url') . 'image/';
		       }	
				
				
				if ($tab['image']) {
					$image = $way.$tab['image'];
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
					'href'        => $this->url->link('product/category', 'path=' . $tab['category_id'] . '_' . $child['category_id'])	
				);		
			}
				
				$this->data['tabs'][] = array(
					'image' 		 => $image,
					'limit_v' 	     => $setting['limit_v'],
					'category_width' => $setting['category_width'],
					'name'			 => $catagory_name['name'],
					'href'           => $this->url->link('product/category', 'path=' . $tab['category_id']),
					'title'	 		 =>	$tab['title'][$this->config->get('config_language_id')],
					'subcateg'	 	 =>	$tab['subcateg'],
					'showproducts'	  =>	$tab['showproducts'],
					'children'        => $children_data,
					'products'       => $products,
				);
			}
		}
		// end tab
		
		$this->data['module'] =  $numMod++;
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mattimeocategory.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/mattimeocategory.tpl';
		} else {
			$this->template = 'default/template/module/mattimeocategory.tpl';
		}

		$this->render();
	}
}
?>
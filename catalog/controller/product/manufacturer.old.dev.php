<?php
	class ControllerProductManufacturer extends Controller {  
		public function index() {
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('tool/image');		
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_index'] = $this->language->get('text_index');
			$this->data['text_empty'] = $this->language->get('text_empty');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_brand'),
			'href'      => $this->url->link('product/manufacturer'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['categories'] = array();
			
			$current_store = (int)$this->config->get('config_store_id');
			$current_lang  = (int)$this->config->get('config_language_id');
			$current_curr  = (int)$this->currency->getId();
			
			$this->bcache->SetFile('all_manufacturers.'.$current_store.$current_lang.$current_curr.'.tpl', 'all_manufacturers');
			if ($this->bcache->CheckFile()) {
				$this->data['categories'] = $this->bcache->ReturnFileContent(true);
				} else {	
				
				$results = $this->model_catalog_manufacturer->getManufacturers();
				
				foreach ($results as $result) {
					if ($result['sort_order'] != -1) {
						
						if (is_numeric(utf8_substr($result['name'], 0, 1))) {
							$key = '0 - 9';
							} else {
							$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
						}
						
						
						if (!isset($this->data['manufacturers'][$key])) {
							$this->data['categories'][$key]['name'] = $key;
						}
						
						$description = $this->model_catalog_manufacturer->getManufacturerDescriptions($result['manufacturer_id']);
						$result_mcategories = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($result['manufacturer_id']);
						
						$mcategories = array();
						foreach ($result_mcategories as $mcategory){
							$mcategories[] = array(
							'thumb' => $this->model_tool_image->resize($mcategory['image'], 40, 40),
							'name' => $mcategory['name'].' '.$result['name'],
							'href' => $this->url->link('product/category', 'path='.$mcategory['category_id'].'&manufacturer_id=' . $result['manufacturer_id']),
							);
						}
						
						$mcategories = array_slice($mcategories, 0, 5);
						
						
						if (SITE_NAMESPACE == 'HAUSGARTEN'){
							$image = $this->model_tool_image->resize($result['image'], 200, 200);
							$image_webp = $this->model_tool_image->resize_webp($result['image'], 200, 200);
							$image_mime = $this->model_tool_image->getMime($result['image'], 200, 200);
							} else {
							$image = $this->model_tool_image->resize($result['image'], 300, 300);
							$image_webp = $this->model_tool_image->resize_webp($result['image'], 300, 300);
							$image_mime = $this->model_tool_image->getMime($result['image']);
							
							$back_image = $this->model_tool_image->resize($result['back_image'], 300, 300);
							$back_image_webp = $this->model_tool_image->resize_webp($result['back_image'], 300, 300);
							$back_image_mime = $this->model_tool_image->getMime($result['back_image']);
						}
						
						$this->data['categories'][$key]['manufacturer'][] = array(
						'name' => $result['name'],
						'mcategories' => $mcategories,
						'image' => $image,
						'image_webp' => $image_webp,
						'image_mime' => $image_mime,
						'back_image' => $back_image,
						'back_image_webp' => $image_webp,
						'back_image_mime' => $image_mime,
						'short_description' => strip_tags($description[$this->config->get('config_language_id')]['short_description']),
						'location' => $description[$this->config->get('config_language_id')]['location'],
						'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
						);
					}
				}
				
				$this->bcache->WriteFile($this->data['categories'] ,true);
				
			}
			
			$this->data['continue'] = $this->url->link('common/home');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer_list.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/manufacturer_list.tpl';
				} else {
				$this->template = 'default/template/product/manufacturer_list.tpl';
			}			
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			$this->response->setOutput($this->render());										
		}
		
		private function getActiveLinks($manufacturer_id){
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');	
			$this->load->model('catalog/product');
			$this->load->model('catalog/collection');	
			$this->load->model('catalog/news');
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			
			$results = $this->model_catalog_collection->getCollectionsByManufacturerNoVirtualForShowAll($manufacturer_id, 1);
			if (!$results || $manufacturer_info['show_goods']){
				$this->data['collections_link'] = false;
			}
			
			$results = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id);
			if (!$results){
				$this->data['categories_link'] = false;
			}
			
			
			
			$filter_data = array(
			'filter_manufacturer_id' 	=> $manufacturer_id         
			);						
			if (!$this->model_catalog_product->getTotalProductSpecials($filter_data)){
				$this->data['special_link'] = false;
			}
			
			
			$filter_data = array(
			'filter_manufacturer_id' => $manufacturer_id, 
			'no_child'      	=> true, 
			);
			
			if (!$this->model_catalog_product->getTotalProducts($filter_data)){
				$this->data['products_link'] = false;
			}
			
			
			
			$filter_data = array(
            'newlong'                	=> 1,
			'filter_manufacturer_id' 	=> $manufacturer_id,           
			);
			
			$product_total = $this->model_catalog_product->getTotalProducts($data);
			
			if (!$this->model_catalog_product->getTotalProducts($filter_data)){
				$this->data['newproducts_link'] = false;
			}
			
			
			
			$filter_data = array(
			'start'           => 0,
			'limit'           => 100,
			'filter_tag'      => $manufacturer_info['name'],
			);
			if (!$this->model_catalog_news->getNews($filter_data)){
				$this->data['articles_link'] = false;
			}
			
		}
		
		public function special(){
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('catalog/collection');				
			
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'stock_status_id ASC, p.sort_order';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'ASC';
			} 
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
				} else {
				$limit = $this->config->get('config_catalog_limit');
			}
			
			$this->data['page_type'] = 'manufacturer';
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $this->language->get('text_brand'),
			'href'      => $this->url->link('product/manufacturer'),
			'separator' => $this->language->get('text_separator')
			);
			
			
			$data = array(
			'filter_manufacturer_id' 	=> $manufacturer_id, 
            'sort'                	 	=> $sort,
            'order'               		=> $order,
            'start'               		=> ($page - 1) * $limit,
            'limit'               		=> $limit
			);
			
			$product_total = $this->model_catalog_product->getTotalProductSpecials($data);
			$results = $this->model_catalog_product->getProductSpecials($data);
			
			foreach ($results as $result) {
				
				$this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
				
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
					$image_webp = $this->model_tool_image->resize_webp($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
					$image_mime = $this->model_tool_image->getMime($result['image']);
					} else {
					$image = false;
					$image_webp = false;
					$image_mime = false;
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
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
					} else {
					$tax = false;
				}				
				
				if ($result['price_national'] && $result['price_national'] > 0 && $result['currency'] == $this->currency->getCode()){
					$price = $this->currency->format($this->tax->calculate($result['price_national'], $result['tax_class_id'], $this->config->get('config_tax')), $result['currency'], 1);
				}
				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
					} else {
					$rating = false;
				}
				
				$_description = '';
				$is_not_certificate = (strpos($result['location'], 'certificate') === false);
				if ($is_not_certificate){
					
					if (mb_strlen($result['description']) > 10){
						$_description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 128) . '..';
					}
					
					} else {
					$_description = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
				}
				
				//НАЛИЧИЕ 
				$stock_data = $this->model_catalog_product->parseProductStockData($result);
				
				$this->data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'thumb_webp' => $image_webp,
				'thumb_mime' => $image_mime,
				'is_set' 	  => $result['set_id'],
				'name'        => $result['name'],
				'description' => $_description,
				'price'       => $price,
				'special'     => $special,
				'colors'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductColorsByGroup($result['product_id'], $result['color_group']):false,
				'options'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductOptionsForCatalog($result['product_id']):false,
				'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
				'tax'         => $tax,
				'rating'      => $result['rating'],
				'can_not_buy' => ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
				'has_child'  => $result['has_child'],
				'stock_status'  => $result['stock_status'],
				'stock_type'  => $stock_data['stock_type'],						
				'show_delivery_terms' => $stock_data['show_delivery_terms'],
				'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'        => $this->url->link('product/product', '&manufacturer_id=' . $result['manufacturer_id'] . '&goods=all&product_id=' . $result['product_id'] . $url),
				'quickview'        => $this->url->link('product/quickview', '&manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'] . $url),
				);
			}
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/manufacturer/special','manufacturer_id=' . $this->request->get['manufacturer_id'] .  $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
			
			$this->data['current_sort'] = $this->language->get('text_default');
			
			foreach ($this->data['sorts'] as $_sort){
				if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
					$this->data['current_sort'] = $_sort['text'];
				}
			}
			
			
			if ($manufacturer_info['image']){
				$this->data['manufacturer_logo'] = $this->model_tool_image->resize($manufacturer_info['image'], 150, 150, '', 100);
				} else {
				$this->data['manufacturer_logo'] = false;
			}								
			
			if ($manufacturer_info['banner']){
				
				if (!$manufacturer_info['banner_width'] || !$manufacturer_info['banner_height']){
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100, true);
					} else {
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100);
				}
				} else {
				$this->data['banner'] = false;
			}		
			
			$this->data['banner_width'] = $manufacturer_info['banner_width'];
			$this->data['banner_height'] = $manufacturer_info['banner_height'];
			
			$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			
			$this->getActiveLinks($this->request->get['manufacturer_id']);
			
			$this->document->addLink($this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('index, follow');
			
			($manufacturer_info['seo_title'] == '')?$this->document->setTitle($manufacturer_info['name']):$this->document->setTitle($manufacturer_info['seo_title']);
			
			$this->data['active_button'] = 'special';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer/sales.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/manufacturer/sales.tpl';
				} else {
				$this->template = 'default/template/product/manufacturer/sales.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			
			$this->response->setOutput($this->render());	
		}
		
		public function newproducts(){
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('catalog/collection');				
			
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'p.date_added';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'ASC';
			} 
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
				} else {
				$limit = $this->config->get('config_catalog_limit');
			}
			
			$this->data['page_type'] = 'manufacturer';
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $this->language->get('text_brand'),
			'href'      => $this->url->link('product/manufacturer'),
			'separator' => $this->language->get('text_separator')
			);
			
			
			
			$data = array(
            'newlong'                	=> 1,
			'filter_manufacturer_id' 	=> $manufacturer_id, 
            'sort'                	 	=> $sort,
            'order'               		=> $order,
            'start'               		=> ($page - 1) * $limit,
            'limit'               		=> $limit
			);
			
			$product_total = $this->model_catalog_product->getTotalProducts($data);
			$results = $this->model_catalog_product->getProducts($data);
			
			
			foreach ($results as $result) {
				
				$this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
				
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
					$image_webp = $this->model_tool_image->resize_webp($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
					$image_mime = $this->model_tool_image->getMime($result['image']);
					} else {
					$image = false;
					$image_webp = false;
					$image_mime = false;
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
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
					} else {
					$tax = false;
				}				
				
				if ($result['price_national'] && $result['price_national'] > 0 && $result['currency'] == $this->currency->getCode()){
					$price = $this->currency->format($this->tax->calculate($result['price_national'], $result['tax_class_id'], $this->config->get('config_tax')), $result['currency'], 1);
				}
				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
					} else {
					$rating = false;
				}
				
				$_description = '';
				$is_not_certificate = (strpos($result['location'], 'certificate') === false);
				if ($is_not_certificate){
					
					if (mb_strlen($result['description']) > 10){
						$_description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 128) . '..';
					}
					
					} else {
					$_description = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
				}
				
				//НАЛИЧИЕ 
				$stock_data = $this->model_catalog_product->parseProductStockData($result);
				
				$this->data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'thumb_webp' => $image_webp,
				'thumb_mime' => $image_mime,
				'is_set' 	  => $result['set_id'],
				'name'        => $result['name'],
				'description' => $_description,
				'price'       => $price,
				'special'     => $special,
				'colors'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductColorsByGroup($result['product_id'], $result['color_group']):false,
				'options'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductOptionsForCatalog($result['product_id']):false,
				'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
				'tax'         => $tax,
				'rating'      => $result['rating'],
				'can_not_buy' => ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
				'has_child'  => $result['has_child'],
				'stock_status'  => $result['stock_status'],
				'stock_type'  => $stock_data['stock_type'],						
				'show_delivery_terms' => $stock_data['show_delivery_terms'],
				'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'        => $this->url->link('product/product', '&manufacturer_id=' . $result['manufacturer_id'] . '&goods=all&product_id=' . $result['product_id'] . $url),
				'quickview'        => $this->url->link('product/quickview', '&manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'] . $url),
				);
			}
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/manufacturer/newproducts','manufacturer_id=' . $this->request->get['manufacturer_id'] .  $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
			
			$this->data['current_sort'] = $this->language->get('text_default');
			
			foreach ($this->data['sorts'] as $_sort){
				if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
					$this->data['current_sort'] = $_sort['text'];
				}
			}
			
			
			if ($manufacturer_info['image']){
				$this->data['manufacturer_logo'] = $this->model_tool_image->resize($manufacturer_info['image'], 150, 150, '', 100);
				} else {
				$this->data['manufacturer_logo'] = false;
			}								
			
			if ($manufacturer_info['banner']){
				
				if (!$manufacturer_info['banner_width'] || !$manufacturer_info['banner_height']){
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100, true);
					} else {
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100);
				}
				} else {
				$this->data['banner'] = false;
			}		
			
			$this->data['banner_width'] = $manufacturer_info['banner_width'];
			$this->data['banner_height'] = $manufacturer_info['banner_height'];
			
			$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			
			$this->getActiveLinks($this->request->get['manufacturer_id']);
			
			$this->document->addLink($this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('index, follow');
			
			($manufacturer_info['seo_title'] == '')?$this->document->setTitle($manufacturer_info['name']):$this->document->setTitle($manufacturer_info['seo_title']);
			
			$this->data['active_button'] = 'newproducts';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer/newproducts.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/manufacturer/newproducts.tpl';
				} else {
				$this->template = 'default/template/product/manufacturer/newproducts.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			
			$this->response->setOutput($this->render());	
		}
		
		public function collections(){
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('catalog/collection');				
			
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			
			$current_store = (int)$this->config->get('config_store_id');
			$current_lang  = (int)$this->config->get('config_language_id');
			$current_curr  = (int)$this->currency->getId();
			$manufacturer_id  = (int)$this->request->get['manufacturer_id'];
			
			$this->bcache->SetFile('collections.'.$manufacturer_id.$current_store.$current_lang.$current_curr.'.tpl', 'manufacturer');
			if ($this->bcache->CheckFile()) {
				
				$this->data['collections'] = $this->bcache->ReturnFileContent(true);
				
				} else {		
				
				$this->load->model('catalog/collection');	
				$results = $this->model_catalog_collection->getCollectionsByManufacturerNoVirtualForShowAll($manufacturer_id, 300);
				$this->data['collections'] = array();			
				
				if (count($results)>0){
					foreach ($results as $result){
						
						if (is_numeric(utf8_substr($result['name'], 0, 1))) {
							$key = '0 - 9';
							} else {
							$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
						}
						
						$this->data['collections'][$key]['name'] = $key;
						
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], 500, 500);
							} else {
							$image = false;
						}
						
						if (!$result['no_brand']) {
							
							$this->data['collections'][$key]['collection'][] = array(
							'collection_id'     	=> $result['collection_id'],
							'name'       	  		=> $result['name'],
							'short_description'     => $result['short_description'],
							'thumb'       	  		=> $image,
							'href'            		=> $this->url->link('product/collection', 'collection_id=' . $result['collection_id'])
							);	
							
						}
					}							
				}
				$this->bcache->WriteFile($this->data['collections'], true);
			}
			
			
			if ($manufacturer_info['image']){
				$this->data['manufacturer_logo'] = $this->model_tool_image->resize($manufacturer_info['image'], 150, 150, '', 100);
				} else {
				$this->data['manufacturer_logo'] = false;
			}								
			
			if ($manufacturer_info['banner']){
				
				if (!$manufacturer_info['banner_width'] || !$manufacturer_info['banner_height']){
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100, true);
					} else {
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100);
				}
				} else {
				$this->data['banner'] = false;
			}		
			
			$this->data['banner_width'] = $manufacturer_info['banner_width'];
			$this->data['banner_height'] = $manufacturer_info['banner_height'];
			
			$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			
			$this->getActiveLinks($this->request->get['manufacturer_id']);
			
			$this->document->addLink($this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('index, follow');
			
			($manufacturer_info['seo_title'] == '')?$this->document->setTitle($manufacturer_info['name']):$this->document->setTitle($manufacturer_info['seo_title']);
			
			$this->data['active_button'] = 'collections';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer/collections.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/manufacturer/collections.tpl';
				} else {
				$this->template = 'default/template/product/manufacturer/collections.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			
			$this->response->setOutput($this->render());	
			
		}		
		
		public function categories(){
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('catalog/category');				
			
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			
			$current_store = (int)$this->config->get('config_store_id');
			$current_lang  = (int)$this->config->get('config_language_id');
			$current_curr  = (int)$this->currency->getId();
			$manufacturer_id  = (int)$this->request->get['manufacturer_id'];
			
			$this->bcache->SetFile('categories.'.$manufacturer_id.$current_lang.$current_curr.'.tpl', 'manufacturer_categories'.$current_store);
			if ($this->bcache->CheckFile()) {
				$this->data['categories'] = $this->bcache->ReturnFileContent(true);
				} else {
				$this->load->model('module/keyworder');
				$this->data['categories'] = array();
				$results_parent_categories = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id);								
				
				if (is_array($results_parent_categories) && (count($results_parent_categories) == 1)){
					foreach ($results_parent_categories as $parent_category){
						$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $parent_category['category_id']);
						$image = $this->model_module_keyworder->getKeyworderImage($parent_category['category_id'], $manufacturer_id);
						if ($image){
							$parent_category['image'] = $image;						
						}
						
						$children = array();
						foreach ($results_children as $children_category){
							$image = $this->model_module_keyworder->getKeyworderImage($children_category['category_id'], $manufacturer_id);
							if ($image){
								$children_category['image'] = $image;						
							}
							
							$this->data['categories'][] = array(
							'thumb' => $this->model_tool_image->resize( $children_category['image'], 260, 260),
							'name' => $children_category['name'].' '.$manufacturer_info['name'],
							'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
							);
						}													
						
					}										
					} else {
					
					foreach ($results_parent_categories as $parent_category){
						
						$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $parent_category['category_id']);
						$image = $this->model_module_keyworder->getKeyworderImage($parent_category['category_id'], $manufacturer_id);
						$h1_name = $this->model_module_keyworder->getKeyworderName($parent_category['category_id'], $manufacturer_id);
						
						if ($image){
							$parent_category['image'] = $image;						
						}
						
						if ($h1_name){
							$name = $h1_name;
							} else {
							$name =  $parent_category['name'].' '.$manufacturer_info['name'];
						}
						
						$children = array();
						foreach ($results_children as $children_category){
							$image = $this->model_module_keyworder->getKeyworderImage($children_category['category_id'], $manufacturer_id);
							
							
							if ($image){
								$children_category['image'] = $image;						
							}
							
							$children[] = array(
							'thumb' => $this->model_tool_image->resize( $children_category['image'], 100, 100),
							'name' => $children_category['name'].' '.$manufacturer_info['name'],
							'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
							);
						}
						
						$this->data['categories'][] = array(
						'thumb' => $this->model_tool_image->resize($parent_category['image'], 260, 260),
						'name' => $name,
						'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
						'children' => $children
						);					
					}
					
				}
				
				$this->bcache->WriteFile($this->data['categories'], true);
			}
			
			$this->log->debug($this->data['categories']);
			
			if ($manufacturer_info['image']){
				$this->data['manufacturer_logo'] = $this->model_tool_image->resize($manufacturer_info['image'], 150, 150, '', 100);
				} else {
				$this->data['manufacturer_logo'] = false;
			}								
			
			if ($manufacturer_info['banner']){
				
				if (!$manufacturer_info['banner_width'] || !$manufacturer_info['banner_height']){
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100, true);
					} else {
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100);
				}
				} else {
				$this->data['banner'] = false;
			}		
			
			$this->data['banner_width'] = $manufacturer_info['banner_width'];
			$this->data['banner_height'] = $manufacturer_info['banner_height'];
			
			$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			
			$this->getActiveLinks($this->request->get['manufacturer_id']);
			
			$this->document->addLink($this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('index, follow');
			
			($manufacturer_info['seo_title'] == '')?$this->document->setTitle($manufacturer_info['name']):$this->document->setTitle($manufacturer_info['seo_title']);
			
			$this->data['active_button'] = 'categories';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer/categories.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/manufacturer/categories.tpl';
				} else {
				$this->template = 'default/template/product/manufacturer/categories.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			
			$this->response->setOutput($this->render());	
			
		}
		
		public function articles(){
			
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('catalog/category');	
			$this->language->load('module/news');
			$this->load->model('catalog/news');
			$this->load->model('catalog/ncomments');
			$this->load->model('catalog/ncategory');
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			$data = array(
			'start'           => 0,
			'limit'           => 100,
			'filter_tag'      => $manufacturer_info['name'],
			);
			$results = $this->model_catalog_news->getNews($data);	
			
			$bbwidth = ($this->config->get('bnews_image_width')) ? $this->config->get('bnews_image_width') : 80;
			$bbheight = ($this->config->get('bnews_image_height')) ? $this->config->get('bnews_image_height') : 80;
			
			if($this->config->get('bnews_display_elements')) {
				$elements = $this->config->get('bnews_display_elements');
				} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
			}
			
			foreach ($results as $result) {
				$name = (in_array("name", $elements) && $result['title']) ? $result['title'] : '';
				$da = (in_array("da", $elements)) ? date('d.m.Y', strtotime($result['date_added'])) : '';
				$du = (in_array("du", $elements) && $result['date_updated'] && $result['date_updated'] != $result['date_added']) ? date('d M Y', strtotime($result['date_updated'])) : '';
				$button = (in_array("button", $elements)) ? true : false;
				$custom1 = (in_array("custom1", $elements) && $result['cfield1']) ? html_entity_decode($result['cfield1'], ENT_QUOTES, 'UTF-8') : '';
				$custom2 = (in_array("custom2", $elements) && $result['cfield2']) ? html_entity_decode($result['cfield2'], ENT_QUOTES, 'UTF-8') : '';
				$custom3 = (in_array("custom3", $elements) && $result['cfield3']) ? html_entity_decode($result['cfield3'], ENT_QUOTES, 'UTF-8') : '';
				$custom4 = (in_array("custom4", $elements) && $result['cfield4']) ? html_entity_decode($result['cfield4'], ENT_QUOTES, 'UTF-8') : '';
				if (in_array("image", $elements) && ($result['image'] || $result['image2'])) {
					if ($result['image2']) {
						
						$image = $this->model_tool_image->resize($result['image2'], $bbwidth, $bbheight);
						$image_mime = $this->model_tool_image->getMime($result['image2']);
						$image_webp = $this->model_tool_image->resize_webp($result['image2'], $bbwidth, $bbheight);
						
						} else {
						$image = $this->model_tool_image->resize($result['image'], $bbwidth, $bbheight);
						$image_mime = $this->model_tool_image->getMime($result['image']);
						$image_webp = $this->model_tool_image->resize_webp($result['image'], $bbwidth, $bbheight);
					}
					} else {
					$image = false;
					$image_mime = false;
					$image_webp = false;
				}
				if (in_array("author", $elements) && $result['author']) {
					$author = $result['author'];
					$author_id = $result['nauthor_id'];
					$author_link = $this->url->link('news/ncategory', 'author=' . $result['nauthor_id']);
					} else {
					$author = '';
					$author_id = '';
					$author_link = '';
				}
				if (in_array("desc", $elements) && ($result['description'] || $result['description2'])) {
					if($result['description2']) {
						$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 600) . '..';
					}
					} else {
					$desc = '';
				}
				if (in_array("com", $elements) && $result['acom']) {
					$com = $this->model_catalog_ncomments->getTotalNcommentsByNewsId($result['news_id']);
					if (!$com) {
						$com = " 0 ";
					}
					} else {
					$com = '';
				}
				$path = '0';
				if (in_array("category", $elements)) {
					$category = "";
					$cats = $this->model_catalog_news->getNcategoriesbyNewsId($result['news_id']);
					if ($cats) {
						$comma = 0;
						foreach($cats as $catid) {
							$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
							$path = $this->model_catalog_ncategory->getncategorypath($catinfo['ncategory_id']);
							
							if ($catinfo) {
								if ($comma) {
									$category .= ', <a href="'.$this->url->link('news/ncategory', 'ncat=' . $path).'">'.$catinfo['name'].'</a>';						
									} else {
									$category .= '<a href="'.$this->url->link('news/ncategory', 'ncat=' . $path).'">'.$catinfo['name'].'</a>';																	
								}
								$comma++;
							}
						}
					}
					} else {
					$category = '';
					$path = '0';
				}				
				
				$href = $this->url->link('news/article', 'ncat='.$path.'&news_id=' . $result['news_id']);
				
				$this->data['article'][] = array(
				'article_id'  => $result['news_id'],
				'name'        => $name,
				'thumb'       => $image,
				'thumb_mime'  => $image_mime,
				'thumb_webp'  => $image_webp,
				'date_added'  => $da,
				'du'          => $du,
				'author'      => $author,
				'author_id'   => $author_id,
				'author_link' => $author_link,
				'description' => $desc,
				'button'      => $button,
				'custom1'     => $custom1,
				'custom2'     => $custom2,
				'custom3'     => $custom3,
				'custom4'     => $custom4,
				'category'    => $category,
				'href'        => $href,
				'total_comments' => $com,
				'viewed' => $result['viewed'],
				);
			}
			
			if ($manufacturer_info['image']){
				$this->data['manufacturer_logo'] = $this->model_tool_image->resize($manufacturer_info['image'], 150, 150, '', 100);
				} else {
				$this->data['manufacturer_logo'] = false;
			}								
			
			if ($manufacturer_info['banner']){
				
				if (!$manufacturer_info['banner_width'] || !$manufacturer_info['banner_height']){
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100, true);
					} else {
					$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100);
				}
				} else {
				$this->data['banner'] = false;
			}		
			
			$this->data['banner_width'] = $manufacturer_info['banner_width'];
			$this->data['banner_height'] = $manufacturer_info['banner_height'];
			
			$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
			
			$this->getActiveLinks($this->request->get['manufacturer_id']);
			
			$this->document->addLink($this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('index, follow');
			
			($manufacturer_info['seo_title'] == '')?$this->document->setTitle($manufacturer_info['name']):$this->document->setTitle($manufacturer_info['seo_title']);
			
			$this->data['active_button'] = 'articles';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer/articles.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/manufacturer/articles.tpl';
				} else {
				$this->template = 'default/template/product/manufacturer/articles.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			
			$this->response->setOutput($this->render());	
		}
		
		public function info() {
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('catalog/collection');				
			
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
			}
			
			$this->data['monobrand'] = ($this->config->get('config_monobrand')>0);
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'stock_status_id ASC, p.sort_order';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'ASC';
			} 
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
				} else {
				$limit = $this->config->get('config_catalog_limit');
			}
			
			$this->data['page_type'] = 'manufacturer';
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $this->language->get('text_brand'),
			'href'      => $this->url->link('product/manufacturer'),
			'separator' => $this->language->get('text_separator')
			);
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
			
			if ($manufacturer_info) {
				
				$this->data['show_goods'] = (isset($this->request->get['goods']) && $this->request->get['goods']=='all');
				
				if (isset($this->request->get['page']) && $this->request->get['page'] > 0){
					$this->data['show_goods'] = true;
				}
				
				if (SITE_NAMESPACE == 'HAUSGARTEN'){
					$this->data['show_goods'] = true;
				}
				
				if (!$this->data['show_goods']) {
					$this->data['show_collections'] = (isset($this->request->get['collections']) && $this->request->get['collections']=='all');
					} else {
					$this->data['show_collections'] = false;
				}
				
				if ($manufacturer_info['show_goods']){
					$this->data['show_goods'] = true;
				}
				
				
				if ($this->config->get('config_show_goods_overload')){
					$this->data['show_goods'] = true;
				}
				
				if ($manufacturer_info['image']){
					$this->data['manufacturer_logo'] = $this->model_tool_image->resize($manufacturer_info['image'], 150, 150, '', 100);
					} else {
					$this->data['manufacturer_logo'] = false;
				}								
				
				if ($manufacturer_info['banner']){
					
					if (!$manufacturer_info['banner_width'] || !$manufacturer_info['banner_height']){
						$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100, true);
						} else {
						$this->data['banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], $manufacturer_info['banner_width'], $manufacturer_info['banner_height'], '', 100);
					}
					} else {
					$this->data['banner'] = false;
				}		
				
				$this->data['banner_width'] = $manufacturer_info['banner_width'];
				$this->data['banner_height'] = $manufacturer_info['banner_height'];
				
				$data = array(
				'filter_manufacturer_id' => $manufacturer_id, 
				'sort'                   => $sort,
				//'no_child'      	     => true, 
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
				);
				
				
				$this->data['products_total'] = $this->model_catalog_product->getTotalProducts($data);
				
				
				$this->document->setKeywords($manufacturer_info['meta_keyword']);
				$this->document->setDescription($manufacturer_info['meta_description']);
				$this->data['description'] = html_entity_decode($manufacturer_info['description'], ENT_QUOTES, 'UTF-8');
				$this->data['short_description'] = html_entity_decode($manufacturer_info['short_description'], ENT_QUOTES, 'UTF-8');
				
				// Нужно получить контент на странице бренда
				
				if (!$this->data['show_goods'] && !$this->data['show_collections']) {			
					$additionalContent = $this->model_catalog_manufacturer->getManufacturerContentByManufacturerId($manufacturer_id);			
					} else {
					$additionalContent = false;
				}
				//ЖЕСТКАЯ ПЕРЕЗАГРУЗКА
				$additionalContent = false;
				
				if ($additionalContent) {
					foreach ($additionalContent as $k =>  $c) {
						// Получаем товары
						if ($c['type'] == 'products') {
							$this->load->model('catalog/product');
							
							$pArray = explode(",", $c['products']);
							
							foreach ($pArray as $pid) {
								
								$real_product = $this->model_catalog_product->getProduct($pid);
								
								$this->data['product_dimensions'] = array(
								'w' => $this->config->get('config_image_product_width'),
								'h' => $this->config->get('config_image_product_height')
								);
								
								if ($real_product['image']) {
									$image = $this->model_tool_image->resize($real_product['image'], $this->data['product_dimensions']['w'], $this->data['product_dimensions']['h']);
									$image_webp = $this->model_tool_image->resize_webp($real_product['image'], $this->data['product_dimensions']['w'], $this->data['product_dimensions']['h']);
									$image_mime = $this->model_tool_image->getMime($real_product['image']);
									} else {
									$image = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->data['product_dimensions']['w'], $this->data['product_dimensions']['h']);
									$image_webp = $this->model_tool_image->resize_webp($this->config->get('config_noimage'), $this->data['product_dimensions']['w'], $this->data['product_dimensions']['h']);
									$image_mime = $this->model_tool_image->getMime($this->config->get('config_noimage'));
								}
								
								if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
									$_price = $this->currency->format($this->tax->calculate($real_product['price'], $real_product['tax_class_id'], $this->config->get('config_tax')));
									} else {
									$_price = false;
								}
								
								if ((float)$real_product['special']) {
									$_special = $this->currency->format($this->tax->calculate($real_product['special'], $real_product['tax_class_id'], $this->config->get('config_tax')));
									} else {
									$_special = false;
								}	
								
								if ($this->config->get('config_tax')) {
									$tax = $this->currency->format((float)$real_product['special'] ? $real_product['special'] : $real_product['price']);
									} else {
									$tax = false;
								}		
								
								if (isset($real_product['display_price_national']) && $real_product['display_price_national'] && $real_product['display_price_national'] > 0 && $real_product['currency'] == $this->currency->getCode()){
									$_price = $this->currency->format($this->tax->calculate($real_product['display_price_national'], $real_product['tax_class_id'], $this->config->get('config_tax')), $real_product['currency'], 1);
								}
								
								if ($this->config->get('config_review_status')) {
									$rating = (int)$real_product['rating'];
									} else {
									$rating = false;
								}
								
								$is_not_certificate = (strpos($real_product['location'], 'certificate') === false);
								
								//НАЛИЧИЕ 
								$stock_data = $this->model_catalog_product->parseProductStockData($real_product);
								
								$additionalContent[$k]['product_array'][] = array(
								//'new'         => $real_product['new'],
								'show_action' => $real_product['additional_offer_count'],
								'product_id'  => $real_product['product_id'],
								'thumb'       => $image,
								'thumb_webp' 	=> $image_webp,
								'thumb_mime' => $image_mime,
								'stock_type'  => $stock_data['stock_type'],						
								'show_delivery_terms' => $stock_data['show_delivery_terms'],
								'is_set' 	  => $this->model_catalog_set->isSetExist($real_product['product_id']),
								'name'        => $real_product['name'],
								'description' => $is_not_certificate?(utf8_substr(strip_tags(html_entity_decode($real_product['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..'):html_entity_decode($real_product['description'], ENT_QUOTES, 'UTF-8'),
								'price'       => $_price,
								'special'     => $_special,
								'saving'      => round((($real_product['price'] - $real_product['special'])/($real_product['price'] + 0.01))*100, 0),
								'tax'         => $tax,
								'rating'      => $real_product['rating'],
								'has_child'  => $real_product['has_child'],
								'sort_order'  => $real_product['sort_order'],
								'can_not_buy' => ($real_product['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
								'stock_status'  => $real_product['stock_status'],
								'location'      => $real_product['location'],
								'reviews'     => sprintf($this->language->get('text_reviews'), (int)$real_product['reviews']),
								'href'        => $this->url->link('product/product', 'product_id=' . $real_product['product_id'])
								);
								
							}
							
							
							} elseif ($c['type'] == 'collections') {
							$this->load->model('catalog/collection');
							
							$cArray = explode(",", $c['collections']);
							
							foreach ($cArray as $cid) {
								
								$real_collection = $this->model_catalog_collection->getCollection($cid);
								
								if ($real_collection) {
									
									if (isset($real_collection['image']) && $real_collection['image']) {
										$image = $this->model_tool_image->resize($real_collection['image'], 242, 242);
										} else {
										$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 242, 242);
									}
									
									$additionalContent[$k]['collections_array'][] = array(
									'collection_id'     => $real_collection['collection_id'],
									'name'       	    => $real_collection['name'],
									'short_description' => $real_collection['short_description'],
									'thumb'       	  	=> $image,
									'href'              => $this->url->link('product/collection','collection_id=' . $real_collection['collection_id'])
									);
								}
							}
							
							} elseif ($c['type'] == 'categories') {
							$this->load->model('catalog/category');
							$this->load->model('module/keyworder');
							
							$caArray = explode(",", $c['categories']);
							
							foreach ($caArray as $caid) {
								
								if ($real_category = $this->model_catalog_category->getCategory($caid)) {
									//check if keyworder has image && name
									
									if ($_key_image = $this->model_module_keyworder->getKeyworderImage($caid, $manufacturer_id)){
										$image = $this->model_tool_image->resize($_key_image, 242, 242);								
										} else {
										if ($real_category['image']) {
											$image = $this->model_tool_image->resize($real_category['image'], 242, 242);
											} else {
											$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 242, 242);
										}
									}
									
									if ($_key_name = $this->model_module_keyworder->getKeyworderName($caid, $manufacturer_id)){
										$_name = $_key_name;
										} else {
										$_name = $real_category['name'];
									}			
									
									$additionalContent[$k]['categories_array'][] = array(
									'category_id'       => $real_category['category_id'],
									'name'       	    => $_name,
									'short_description' => $real_category['name'] . ' ' . $manufacturer_info['name'],
									'thumb'       	  	=> $image,
									'href'              => $this->url->link('product/category', 'manufacturer_id=' . $manufacturer_id . '&path=' . $real_category['category_id'])
									);
								}
							}
							
							} else {
							unset($additionalContent[$k]);
						}
					}
					
					
					$virtual_collections = $this->model_catalog_collection->getCollectionsByManufacturerOnlyVirtual($manufacturer_id, 300);
					$this->data['virtual_collections'] = array();
					
					switch (count($virtual_collections)){					
						case 0:
						$this->data['vc_cols'] = 0;
						$coef  = 1;
						break;
						case 2:
						$this->data['vc_cols'] = 12;
						$coef  = 2;
						break;
						case 3:
						$this->data['vc_cols'] = 8;
						$coef  = 1;
						break;
						case 4:
						$this->data['vc_cols'] = 6;
						$coef  = 1;
						break;
						default:	
						$this->data['vc_cols'] = 6;
						$coef  = 1;
						break;
					}
					
					
					
					foreach ($virtual_collections as $collection) {
						
						if ($collection['image']) {
							$image = $this->model_tool_image->resize($collection['image'], 330 * $coef, 160 * $coef, '', 100);
							$image_zoom = $this->model_tool_image->resize($collection['image'], 430 * $coef, 208 * $coef, '', 100);
							} else {
							$image = false;
							$image_zoom = false;
						}
						
						if (!$collection['no_brand']) {
							
							$this->data['virtual_collections'][] = array(
							'collection_id'      => $collection['collection_id'],
							'name'       	     => $collection['name'],
							'short_description'  => $collection['short_description'],
							'thumb'       	  	=> $image,
							'thumb_zoom'       	=> $image_zoom,
							'href'            	=> $this->url->link('product/collection', 'collection_id=' . $collection['collection_id'])
							);	
							
						}
					}
					
				}
				
				
				$this->data['additional_content'] = $additionalContent;
				
				
				if ($this->config->get('config_google_remarketing_type') == 'ecomm') {
					
					$this->data['google_tag_params'] = array(
					'ecomm_prodid' => '',
					'ecomm_pagetype' => 'category',
					'ecomm_totalvalue' => 0
					);
					
				} 
				
				($manufacturer_info['seo_title'] == '')?$this->document->setTitle($manufacturer_info['name']):$this->document->setTitle($manufacturer_info['seo_title']);
				$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				/*
					if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
					}	
				*/
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				
				$this->data['this_href'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url);
				
				$this->data['heading_title'] = $manufacturer_info['name'];
				$this->data['seo_h1'] = ($manufacturer_info['seo_h1'] == '')?$manufacturer_info['name']:$manufacturer_info['seo_h1'];
				
				$this->data['text_empty'] = $this->language->get('text_empty');
				$this->data['text_quantity'] = $this->language->get('text_quantity');
				$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
				$this->data['text_model'] = $this->language->get('text_model');
				$this->data['text_price'] = $this->language->get('text_price');
				$this->data['text_tax'] = $this->language->get('text_tax');
				$this->data['text_points'] = $this->language->get('text_points');
				$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				$this->data['text_display'] = $this->language->get('text_display');
				$this->data['text_list'] = $this->language->get('text_list');
				$this->data['text_grid'] = $this->language->get('text_grid');			
				$this->data['text_sort'] = $this->language->get('text_sort');
				$this->data['text_limit'] = $this->language->get('text_limit');
				$this->data['text_show_all_products'] = $this->language->get('show_all_products');
				$this->data['text_all_collection'] = $this->language->get('all_collection');
				$this->data['text_open_collection'] = $this->language->get('open_collection');
				
				$this->data['button_cart'] = $this->language->get('button_cart');
				$this->data['button_wishlist'] = $this->language->get('button_wishlist');
				$this->data['button_compare'] = $this->language->get('button_compare');
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['compare'] = $this->url->link('product/compare');
				
				$this->load->model('catalog/superstat');		
				$this->model_catalog_superstat->addToSuperStat('m', $manufacturer_id);			
				
				
				$this->data['products'] = array();
				
				$data = array(
				'filter_manufacturer_id' => $manufacturer_id, 
				'sort'                   => $sort,
				'no_child'      	=> true, 
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
				);
				
				$this->data['ma_categories'] = array();
				$this->data['m_categories'] = array();
				
				$product_total = $this->model_catalog_product->getTotalProducts($data);
				if ($product_total < 60){
					$this->data['show_goods'] = true;
				}
				
				//AC
				if ((!$additionalContent && !$this->data['show_goods']) || !$this->data['show_collections']) {
					
					if (!$this->data['show_goods'] || $this->data['show_collections'])	{			
						$current_store = (int)$this->config->get('config_store_id');
						$current_lang  = (int)$this->config->get('config_language_id');
						$current_curr  = (int)$this->currency->getId();
						$manufacturer_id  = (int)$this->request->get['manufacturer_id'];
						
						$this->bcache->SetFile('collections.'.$manufacturer_id.$current_store.$current_lang.$current_curr.'.tpl', 'manufacturer');
						if ($this->bcache->CheckFile()) {
							
							$this->data['m_categories'] = $this->bcache->ReturnFileContent(true);
							
							} else {		
							
							$this->load->model('catalog/collection');	
							$coresults = $this->model_catalog_collection->getCollectionsByManufacturerNoVirtualForShowAll($manufacturer_id, 300);
							$this->data['collections'] = array();			
							
							if (count($coresults)>0){
								foreach ($coresults as $coresult){
									
									if (is_numeric(utf8_substr($coresult['name'], 0, 1))) {
										$key = '0 - 9';
										} else {
										$key = utf8_substr(utf8_strtoupper($coresult['name']), 0, 1);
									}
									
									/*
										
										if (mb_strlen(trim($coresult['type'])) == 0) {
										
										if (is_numeric(utf8_substr($coresult['name'], 0, 1))) {
										$key = '0 - 9';
										} else {
										$key = utf8_substr(utf8_strtoupper($coresult['name']), 0, 1);
										}
										
										} else {
										$key = trim($coresult['type']);
										}
										
									*/
									
									$this->data['m_categories'][$key]['name'] = $key;
									
									if ($coresult['image']) {
										$image = $this->model_tool_image->resize($coresult['image'], 242, 242);
										} else {
										$image = false;
									}
									
									if (!$coresult['no_brand']) {
										
										$this->data['m_categories'][$key]['collection'][] = array(
										'collection_id'     => $coresult['collection_id'],
										'name'       	  => $coresult['name'],
										'short_description'       	  => $coresult['short_description'],
										'thumb'       	  => $image,
										'href'            => $this->url->link('product/collection', 'collection_id=' . $coresult['collection_id'])
										);	
										
									}
								}							
							}
							$this->bcache->WriteFile($this->data['m_categories'], true);
						}
						
						$this->bcache->SetFile('categories.'.$manufacturer_id.$current_lang.$current_curr.'.tpl', 'manufacturer_categories'.$current_store);
						if ($this->bcache->CheckFile()) {
							$this->data['ma_categories'] = $this->bcache->ReturnFileContent(true);
							} else {
							$this->load->model('module/keyworder');
							$this->data['ma_categories'] = array();
							$results_parent_categories = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id);								
							
							//var_dump($results_parent_categories);
							
							if (is_array($results_parent_categories) && (count($results_parent_categories) == 1)){
								foreach ($results_parent_categories as $parent_category){
									$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $parent_category['category_id']);
									$image = $this->model_module_keyworder->getKeyworderImage($parent_category['category_id'], $manufacturer_id);
									if ($image){
										$parent_category['image'] = $image;						
									}
									
									$children = array();
									foreach ($results_children as $children_category){
										$image = $this->model_module_keyworder->getKeyworderImage($children_category['category_id'], $manufacturer_id);
										if ($image){
											$children_category['image'] = $image;						
										}
										
										$this->data['ma_categories'][] = array(
										'thumb' => $this->model_tool_image->resize( $children_category['image'], 260, 260),
										'name' => $children_category['name'].' '.$manufacturer_info['name'],
										'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
										);
									}													
									
								}										
								} else {
								
								foreach ($results_parent_categories as $parent_category){
									
									$results_children = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($manufacturer_id, $parent_category['category_id']);
									$image = $this->model_module_keyworder->getKeyworderImage($parent_category['category_id'], $manufacturer_id);
									$h1_name = $this->model_module_keyworder->getKeyworderName($parent_category['category_id'], $manufacturer_id);
									
									if ($image){
										$parent_category['image'] = $image;						
									}
									
									if ($h1_name){
										$name = $h1_name;
										} else {
										$name =  $parent_category['name'].' '.$manufacturer_info['name'];
									}
									
									$children = array();
									foreach ($results_children as $children_category){
										$image = $this->model_module_keyworder->getKeyworderImage($children_category['category_id'], $manufacturer_id);
										
										
										if ($image){
											$children_category['image'] = $image;						
										}
										
										$children[] = array(
										'thumb' => $this->model_tool_image->resize( $children_category['image'], 100, 100),
										'name' => $children_category['name'].' '.$manufacturer_info['name'],
										'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'_'.$children_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
										);
									}
									
									$this->data['ma_categories'][] = array(
									'thumb' => $this->model_tool_image->resize($parent_category['image'], 260, 260),
									'name' => $name,
									'href' => $this->url->link('product/category', 'path='.$parent_category['category_id'].'&manufacturer_id=' . $manufacturer_id),
									'children' => $children
									);					
								}
								
							}
							
							$this->bcache->WriteFile($this->data['ma_categories'], true);
						}
					}
					
					if ((count($this->data['m_categories']) + count($this->data['ma_categories']))==0 || ($this->data['show_goods'] && !$this->data['show_collections'])) {
						$product_total = $this->model_catalog_product->getTotalProducts($data);						
						
						$results = $this->model_catalog_product->getProducts($data);
						
						foreach ($results as $result) {
							
							$this->data['dimensions'] = array(
							'w' => $this->config->get('config_image_product_width'),
							'h' => $this->config->get('config_image_product_height')
							);
							
							if ($result['image']) {
								$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
								$image_webp = $this->model_tool_image->resize_webp($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
								$image_mime = $this->model_tool_image->getMime($result['image']);
								} else {
								$image = false;
								$image_webp = false;
								$image_mime = false;
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
							
							if ($this->config->get('config_tax')) {
								$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
								} else {
								$tax = false;
							}				
							
							if ($result['price_national'] && $result['price_national'] > 0 && $result['currency'] == $this->currency->getCode()){
								$price = $this->currency->format($this->tax->calculate($result['price_national'], $result['tax_class_id'], $this->config->get('config_tax')), $result['currency'], 1);
							}
							
							
							
							if ($this->config->get('config_review_status')) {
								$rating = (int)$result['rating'];
								} else {
								$rating = false;
							}
							
							$_description = '';
							$is_not_certificate = (strpos($result['location'], 'certificate') === false);
							if ($is_not_certificate){
								
								if (mb_strlen($result['description']) > 10){
									$_description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 128) . '..';
								}
								
								} else {
								$_description = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
							}
							
							//НАЛИЧИЕ 
							$stock_data = $this->model_catalog_product->parseProductStockData($result);
							
							$this->data['products'][] = array(
							'product_id'  => $result['product_id'],
							'thumb'       => $image,
							'thumb_webp' => $image_webp,
							'thumb_mime' => $image_mime,
							'is_set' 	  => $result['set_id'],
							'name'        => $result['name'],
							'description' => $_description,
							'price'       => $price,
							'special'     => $special,
							'colors'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductColorsByGroup($result['product_id'], $result['color_group']):false,
							'options'	  => (SITE_NAMESPACE == 'HAUSGARTEN')?$this->model_catalog_product->getProductOptionsForCatalog($result['product_id']):false,
							'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
							'tax'         => $tax,
							'rating'      => $result['rating'],
							'can_not_buy' => ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
							'has_child'  => $result['has_child'],
							'stock_status'  => $result['stock_status'],
							'stock_type'  => $stock_data['stock_type'],						
							'show_delivery_terms' => $stock_data['show_delivery_terms'],
							'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
							'href'        => $this->url->link('product/product', '&manufacturer_id=' . $result['manufacturer_id'] . '&goods=all&product_id=' . $result['product_id'] . $url),
							'quickview'        => $this->url->link('product/quickview', '&manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'] . $url),
							);
						}
						
						$url = '';
						
						if (isset($this->request->get['limit'])) {
							$url .= '&limit=' . $this->request->get['limit'];
						}
						
						$url .= "&goods=all";
						
						$this->data['sorts'] = array();
						$this->data['sorts'][] = array(
						'text'  => $this->language->get('text_default'),
						'value' => 'p.sort_order-ASC',
						'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.sort_order&order=ASC' . $url)
						);
						$this->data['sorts'][] = array(
						'text'  => $this->language->get('price_asc'),
						'value' => 'p.price-ASC',
						'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=ASC' . $url)
						);
						$this->data['sorts'][] = array(
						'text'  => $this->language->get('price_desc'),
						'value' => 'p.price-DESC',
						'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=DESC' . $url)
						);
						$this->data['sorts'][] = array(
						'text'  => $this->language->get('populars'),
						'value' => 'p.viewed-DESC',
						'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.viewed&order=DESC' . $url)
						);
						
						
						$url = '';
						
						if (isset($this->request->get['sort'])) {
							$url .= '&sort=' . $this->request->get['sort'];
						}	
						
						if (isset($this->request->get['order'])) {
							$url .= '&order=' . $this->request->get['order'];
						}
						
						$this->data['limits'] = array();
						
						$limits = array_unique(array($this->config->get('config_catalog_limit'), 36, 48));
						
						sort($limits);
						
						foreach($limits as $value){
							$this->data['limits'][] = array(
							'text'  => $value,
							'value' => $value,
							'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&goods=all&limit=' . $value)
							);
						}
						
						$url = '';
						
						if (isset($this->request->get['sort'])) {
							$url .= '&sort=' . $this->request->get['sort'];
						}	
						
						if (isset($this->request->get['order'])) {
							$url .= '&order=' . $this->request->get['order'];
						}
						
						if (isset($this->request->get['limit'])) {
							$url .= '&limit=' . $this->request->get['limit'];
						}
						
						$pagination = new Pagination();
						$pagination->total = $product_total;
						$pagination->page = $page;
						$pagination->limit = $limit;
						$pagination->text = $this->language->get('text_pagination');
						$pagination->url = $this->url->link('product/manufacturer/info','manufacturer_id=' . $this->request->get['manufacturer_id'] .  $url . '&goods=all&page={page}');
						
						$this->data['pagination'] = $pagination->render();
						
						$this->data['sort'] = $sort;
						$this->data['order'] = $order;
						$this->data['limit'] = $limit;
						
						$this->data['current_sort'] = $this->language->get('text_default');
						
						foreach ($this->data['sorts'] as $_sort){
							if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
								$this->data['current_sort'] = $_sort['text'];
							}
						}
					}
					$this->data['continue'] = $this->url->link('common/home');
					
					$_pre = '';
					if ($this->data['show_goods']){
						$_pre = '&goods=all';
					}
					
					if (isset($product_total)) {
						$num_pages = ceil($product_total / $limit);
						if ($page == 1) {
							$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
							$this->document->setRobots('index, follow');
							} else {
							$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $_pre .  "&page=" . $page), 'canonical');
							
							$this->document->setTitle(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getTitle());
							$this->document->setDescription(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getDescription());
							
							$this->document->setRobots("noindex, follow");
						}
						if ($page < $num_pages) {
							$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $_pre . '&page=' . ($page + 1)), 'next');
						}
						if ($page > 1) {							
							if ($page == 2) {
								$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $_pre), 'prev');
								} else {
								$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $_pre . '&page=' . ($page - 1)), 'prev');
							}
						}
						} else {
						$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
					}
					
				}
				
				
				$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				
				$this->getActiveLinks($this->request->get['manufacturer_id']);
				
				$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
				$this->document->setRobots('index, follow');
				
				require_once(DIR_SYSTEM . 'library/microdata/opengraph/manufacturer.php');
				require_once(DIR_SYSTEM . 'library/microdata/twittercard/manufacturer.php');
				
				
				$this->bcache->SetFile('templ.man.'.$manufacturer_id.'.tpl', 'templ_man'.$this->config->get('config_store_id'));
				if ($this->bcache->CheckFile()) {		
					$this->template = $this->bcache->ReturnFileContent();
					} else {
					
					$this->load->model('design/layout');
					$layout_id = $this->model_catalog_manufacturer->getManufacturerLayoutId($manufacturer_id);
					if (!$layout_id){				
						$layout_id = $this->model_design_layout->getLayout('product/manufacturer');				
					}	
					
					if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template)) {
							$this->template = $this->config->get('config_template') . '/template/' . $template;
							} else {
							$this->template = 'default/template/' . $template;
						}				
						} else {
						$template_overload = false;
						$this->load->model('setting/setting');
						$custom_template_module = $this->model_setting_setting->getSetting('custom_template_module', $this->config->get('config_store_id'));
						if(!empty($custom_template_module['custom_template_module'])){
							foreach ($custom_template_module['custom_template_module'] as $key => $module) {
								if (($module['type'] == 3) && !empty($module['manufacturers'])) {
									if (in_array($manufacturer_id, $module['manufacturers'])) {
										if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') .'/'. $module['template_name'])) {
											$this->template = $this->config->get('config_template') .'/'. $module['template_name'];
											} else {
											$this->template = DIR_TEMPLATE . 'default' .'/'. $module['template_name'];
										}
										$template_overload = true;
									}
								}
							}
						}
						
						if (!$template_overload) {
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer/info.tpl')) {
								$this->template = $this->config->get('config_template') . '/template/product/manufacturer/info.tpl';
								} else {
								$this->template = 'default/template/product/manufacturer/info.tpl';
							}
						}
					}
					
					$this->bcache->WriteFile($this->template);
				}
				
				$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
				);							
				
				$this->response->setOutput($this->render());
				} else {
				
				$url = '';
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->document->setTitle($this->language->get('text_error'));
				
				$this->data['heading_title'] = $this->language->get('text_error');
				
				$this->data['text_error'] = $this->language->get('text_error');
				
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['continue'] = $this->url->link('common/home');
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
					} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
				);
				
				$this->response->setOutput($this->render());
			}
			
		}
	}

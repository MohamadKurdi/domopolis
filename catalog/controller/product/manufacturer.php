<?php
	class ControllerProductManufacturer extends Controller {  
		
		public function return404(){
			$this->language->load('product/manufacturer');
			
			$url = '';

			if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
			
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
			$this->data['hb_snippets_bc_enable'] = $this->config->get('hb_snippets_bc_enable');
			
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
		
		
		public function index() {
			$this->language->load('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('tool/image');		
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			if ($this->language->get('text_title_' . $this->config->get('config_language_id')) != 'text_title_' . $this->config->get('config_language_id')){
				$this->document->setTitle($this->language->get('text_title_' . $this->config->get('config_language_id')));
				} else {			
				$this->document->setTitle($this->language->get('heading_title'));
			}
			
			if ($this->language->get('text_meta_' . $this->config->get('config_language_id')) != 'text_meta_' . $this->config->get('config_language_id')){
				$this->document->setDescription($this->language->get('text_meta_' . $this->config->get('config_language_id')));
				} else {			
				$this->document->setDescription($this->language->get('heading_title'));
			}
			
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

			$this->log->debug($this->language->get('text_brand'));
			
			$this->data['this_link'] = $this->url->link('product/manufacturer');
			$this->data['categories'] = array();
			
			$current_store = (int)$this->config->get('config_store_id');
			$current_lang  = (int)$this->config->get('config_language_id');
			$current_curr  = (int)$this->currency->getId();
			
			
			//Страницы стран
			$this->data['countrybrands'] = [];			
			$results = $this->model_catalog_manufacturer->getAllCountryBrands();
			
			foreach ($results as $result){
				
				$this->data['countrybrands'][] = [
					'name'		=> $result['name'],
					'flag'		=> $result['flag'],
					'href'		=> $this->url->link('product/countrybrand', 'countrybrand_id=' . $result['countrybrand_id']),
				];
			
			}
			
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
					
					$image = $this->model_tool_image->resize($result['image'], 300, 300);
					$image_webp = $this->model_tool_image->resize_webp($result['image'], 300, 300);
					$image_mime = $this->model_tool_image->getMime($result['image']);
						
					$back_image = $this->model_tool_image->resize($result['back_image'], 300, 300);
					$back_image_webp = $this->model_tool_image->resize_webp($result['back_image'], 300, 300);
					$back_image_mime = $this->model_tool_image->getMime($result['back_image']);
					
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
			'filter_manufacturer_id' 	=> $manufacturer_id,
			'filter_not_bad' => true
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
			'filter_not_bad'			=> true,
			'filter_manufacturer_id' 	=> $manufacturer_id,           
			);
			
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
			
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
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
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
				$sort = $this->registry->get('sort_default');
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
			
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $manufacturer_info['name'],
			'href'      => $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id),
			'separator' => $this->language->get('text_separator')
			);
			
			$data = array(
			'filter_manufacturer_id' 	=> $manufacturer_id, 
			'filter_not_bad' 			=> true,
            'sort'                	 	=> $sort,
            'order'               		=> $order,
            'start'               		=> ($page - 1) * $limit,
            'limit'               		=> $limit					
			);
			
			$this->load->model('catalog/actions');								
			$actions = $this->model_catalog_actions->getManufacturerActions($manufacturer_id);
			
			$this->data['actions'] = array();
			
			if (count($actions) == 1){
				$size = array(550, 860);
				} elseif (count($actions) % 2 == 0){
				$size = array(508, 860);
			} 
			
			foreach ($actions as $action){
				
				
				if ($action['image']) {
					$image = $this->model_tool_image->resize($action['image'], 407, 491);
					$image_mime = $this->model_tool_image->getMime($action['image']);
					$image_webp = $this->model_tool_image->resize_webp($action['image'], 407, 491);
					} else {
					$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 407, 491);
					$image_mime = $this->model_tool_image->getMime($this->config->get('config_noimage'));
					$image_webp = $this->model_tool_image->resize_webp($this->config->get('config_noimage'), 407, 491);
				}
				
				$this->data['actions'][] = array(
				'is_inserted_action' => true,
				'thumb'       => $image,
				'thumb_mime'  => $image_mime,
				'thumb_webp'  => $image_webp,
				'title' => $action['title'],
				'href' => $this->url->link('information/actions','actions_id=' . $action['actions_id'])
				);
			}
			
			$product_total = $this->model_catalog_product->getTotalProductSpecials($data);
			$results = $this->model_catalog_product->getProductSpecials($data);
			
			$this->data['dimensions'] = array(
			'w' => $this->config->get('config_image_product_width'),
			'h' => $this->config->get('config_image_product_height')
			);
			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
			
			$url = '';

			if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
			
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
			$this->data['pagination_text'] = $pagination->render_text();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
			
			$this->data['current_sort'] = $this->language->get('text_default');
			
			
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
			
			//	$this->document->addLink($this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			//	$this->document->setRobots('index, follow');
			
			$this->document->addLink($this->url->link('product/manufacturer', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('noindex, follow');
			
			if ($manufacturer_info['special_title']){
				$this->document->setTitle($manufacturer_info['special_title']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setTitle($manufacturer_info['seo_title']);
				} else {
				$this->document->setTitle($manufacturer_info['name']);
			}
			
			if ($manufacturer_info['special_meta_description']){
				$this->document->setDescription($manufacturer_info['special_meta_description']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setDescription($manufacturer_info['meta_description']);
				} else {
				$this->document->setDescription($manufacturer_info['name']);
			}
			
			$this->data['active_button'] = 'special';
			
			//REWARD TEXT
			if ($this->config->get('rewardpoints_appinstall')){
				$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
			}
			
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
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
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
			
			if (!$manufacturer_info){
				$this->return404();
			}
			
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'p.date_added';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			} 
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
				} else {
				$limit = 20;
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
			
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $manufacturer_info['name'],
			'href'      => $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id),
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
			
			
			$this->data['dimensions'] = array(
			'w' => $this->config->get('config_image_product_width'),
			'h' => $this->config->get('config_image_product_height')
			);
			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
			
			
			$url = '';

			if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
			
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
			$this->data['pagination_text'] = $pagination->render_text();
			
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
			
			//$this->document->addLink($this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			//$this->document->setRobots('index, follow');
			
			$this->document->addLink($this->url->link('product/manufacturer', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('noindex, follow');
			
			
			if ($manufacturer_info['newproducts_title']){
				$this->document->setTitle($manufacturer_info['newproducts_title']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setTitle($manufacturer_info['seo_title']);
				} else {
				$this->document->setTitle($manufacturer_info['name']);
			}
			
			if ($manufacturer_info['newproducts_meta_description']){
				$this->document->setDescription($manufacturer_info['newproducts_meta_description']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setDescription($manufacturer_info['meta_description']);
				} else {
				$this->document->setDescription($manufacturer_info['name']);
			}
			
			
			$this->data['active_button'] = 'newproducts';
			
			//REWARD TEXT
			if ($this->config->get('rewardpoints_appinstall')){
				$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
			}
			
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
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
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
			
			if (!$manufacturer_info){
				$this->return404();
			}
			
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
			
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $manufacturer_info['name'],
			'href'      => $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id),
			'separator' => $this->language->get('text_separator')
			);
			
			
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
			
			//$this->document->addLink($this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			//$this->document->setRobots('index, follow');
			
			$this->document->addLink($this->url->link('product/manufacturer', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('noindex, follow');
			
			if ($manufacturer_info['collections_title']){
				$this->document->setTitle($manufacturer_info['collections_title']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setTitle($manufacturer_info['seo_title']);
				} else {
				$this->document->setTitle($manufacturer_info['name']);
			}
			
			if ($manufacturer_info['collections_meta_description']){
				$this->document->setDescription($manufacturer_info['collections_meta_description']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setDescription($manufacturer_info['meta_description']);
				} else {
				$this->document->setDescription($manufacturer_info['name']);
			}
			
			
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
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
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
			
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $manufacturer_info['name'],
			'href'      => $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id),
			'separator' => $this->language->get('text_separator')
			);
			
			
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
							'thumb' => $this->model_tool_image->resize( $children_category['image'], 486, 300),
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
			
			//$this->document->addLink($this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			//$this->document->setRobots('index, follow');
			
			$this->document->addLink($this->url->link('product/manufacturer', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('noindex, follow');
			
			if ($manufacturer_info['categories_title']){
				$this->document->setTitle($manufacturer_info['categories_title']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setTitle($manufacturer_info['seo_title']);
				} else {
				$this->document->setTitle($manufacturer_info['name']);
			}
			
			if ($manufacturer_info['categories_meta_description']){
				$this->document->setDescription($manufacturer_info['categories_meta_description']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setDescription($manufacturer_info['meta_description']);
				} else {
				$this->document->setDescription($manufacturer_info['name']);
			}
			
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
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
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
			
			
			$this->data['breadcrumbs'][] = array( 
			'text'      => $manufacturer_info['name'],
			'href'      => $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id),
			'separator' => $this->language->get('text_separator')
			);
			
			
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
			
			//$this->document->addLink($this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			//$this->document->setRobots('index, follow');
			
			$this->document->addLink($this->url->link('product/manufacturer', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			$this->document->setRobots('noindex, follow');
			
			if ($manufacturer_info['articles_title']){
				$this->document->setTitle($manufacturer_info['articles_title']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setTitle($manufacturer_info['seo_title']);
				} else {
				$this->document->setTitle($manufacturer_info['name']);
			}
			
			if ($manufacturer_info['articles_meta_description']){
				$this->document->setDescription($manufacturer_info['articles_meta_description']);
				} elseif ($manufacturer_info['seo_title']){
				$this->document->setDescription($manufacturer_info['meta_description']);
				} else {
				$this->document->setDescription($manufacturer_info['name']);
			}
			
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
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
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
				
				
				$this->document->setKeywords($manufacturer_info['meta_keyword']);
				$this->document->setDescription($manufacturer_info['meta_description']);
				$this->data['description'] = html_entity_decode($manufacturer_info['description'], ENT_QUOTES, 'UTF-8');
				$this->data['short_description'] = html_entity_decode($manufacturer_info['short_description'], ENT_QUOTES, 'UTF-8');
				
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

				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
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
				$this->data['manufacturer_location'] = $manufacturer_info['location'];
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
				
				$additionalContent = $this->model_catalog_manufacturer->getManufacturerContentByManufacturerId($manufacturer_id);
				
				if ($additionalContent) {
					foreach ($additionalContent as $k =>  $c) {
						if ($c['type'] == 'collections') {
							$this->load->model('catalog/collection');
							
							$cArray = explode(",", $c['collections']);
							
							$additionalContent[$k]['virtual'] = false;
							foreach ($cArray as $cid) {
								
								$real_collection = $this->model_catalog_collection->getCollection($cid);
								
								if ($real_collection) {
									
									$additionalContent[$k]['width'] = 500;
									$additionalContent[$k]['height'] = 500;
									
									if ($real_collection['virtual']){
										$additionalContent[$k]['virtual'] = true;
										
										$additionalContent[$k]['width'] = 745;
										$additionalContent[$k]['height'] = 300;
									}
									
									if (isset($real_collection['image']) && $real_collection['image']) {
										$image = $this->model_tool_image->resize($real_collection['image'], $additionalContent[$k]['width'], $additionalContent[$k]['height']);
										} else {
										$image = $this->model_tool_image->resize($this->config->get('config_noimage'), $additionalContent[$k]['width'], $additionalContent[$k]['height']);
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
										$image = $this->model_tool_image->resize($_key_image, 500, 500);								
										} else {
										if ($real_category['image']) {
											$image = $this->model_tool_image->resize($real_category['image'], 500, 500);
											} else {
											$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 500, 500);
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
									'href'              => $this->url->link('product/category', 'path=' . $real_category['category_id'] . '&manufacturer_id=' . $manufacturer_id)
									);
								}
							}
							
						}
					}
				}
				
				$this->data['additionalContent'] = $additionalContent;
				
				
				$product_total = $this->data['products_total'] = $this->model_catalog_product->getTotalProducts($data);						
				
				$results = $this->model_catalog_product->getProducts($data);
				
				$this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
				
				$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
				
				
				$this->load->model('catalog/actions');								
				$actions = $this->model_catalog_actions->getManufacturerActions($manufacturer_id);
				
				if ($actions && count($actions)){
					if (count($actions) == 1){
						if ($page == 1 && $this->data['products'] && count($this->data['products']) > 10){
							
							$action = array_shift($actions);
							if ($action['image_to_cat']) {
								$image = $this->model_tool_image->resize($action['image_to_cat'], 407, 491);
								$image_mime = $this->model_tool_image->getMime($action['image_to_cat']);
								$image_webp = $this->model_tool_image->resize_webp($action['image_to_cat'], 407, 491);
								} else {
								$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 407, 491);
								$image_mime = $this->model_tool_image->getMime($this->config->get('config_noimage'));
								$image_webp = $this->model_tool_image->resize_webp($this->config->get('config_noimage'), 407, 491);
							}
							
							$action_data = array(
							'is_inserted_action' => true,
							'thumb'       => $image,
							'thumb_mime'  => $image_mime,
							'thumb_webp'  => $image_webp,
							'title' => $action['title'],
							'href' => $this->url->link('information/actions','actions_id=' . $action['actions_id'])
							);
							
							$counter = 1;
							$tmp_products = array();
							foreach ($this->data['products'] as $tmp_product){							
								$tmp_products[] = $tmp_product;
								
								if ($counter == 5){
									$tmp_products[] = $action_data;
								}
								
								$counter++;
							}
							$this->data['products'] = $tmp_products;
							
						}					
						} elseif (count($actions) > 1) {	
						if ($page <= count($actions)){									
							
							shuffle ($actions);
							$action = array_shift($actions);
							
							if ($action['image_to_cat']) {
								$image = $this->model_tool_image->resize($action['image_to_cat'], 407, 491);
								$image_mime = $this->model_tool_image->getMime($action['image_to_cat']);
								$image_webp = $this->model_tool_image->resize_webp($action['image_to_cat'], 407, 491);
								} else {
								$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 407, 491);
								$image_mime = $this->model_tool_image->getMime($this->config->get('config_noimage'));
								$image_webp = $this->model_tool_image->resize_webp($this->config->get('config_noimage'), 407, 491);
							}
							
							$action_data = array(
							'is_inserted_action' => true,
							'thumb'       => $image,
							'thumb_mime'  => $image_mime,
							'thumb_webp'  => $image_webp,
							'title' => $action['title'],
							'href' => $this->url->link('information/actions','actions_id=' . $action['actions_id'])
							);
							
							$counter = 1;
							$tmp_products = array();
							foreach ($this->data['products'] as $tmp_product){							
								$tmp_products[] = $tmp_product;
								
								if ($counter == 5){
									$tmp_products[] = $action_data;
								}
								
								$counter++;
							}
							$this->data['products'] = $tmp_products;
						}
						
					}
				}
				
				$url = '';

				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$this->data['sorts'] = array();

				foreach ($this->registry->get('sorts') as $sortConfig){
					if ($sortConfig['visible']){
						$this->data['sorts'][] = array(
                			'text'  => $this->language->get($sortConfig['text_variable']),
                			'value' => ($sortConfig['field'] . '-' . $sortConfig['order']),
                			'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=' . $sortConfig['field'] . '&order='. $sortConfig['order'] . $url)
						);
					}
				}	
				
				
				$url = '';

				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$this->data['limits'] = array();
				
				$limits = array_unique(array($this->config->get('config_catalog_limit'), $this->config->get('config_catalog_limit') * 2, $this->config->get('config_catalog_limit') * 3));
				
				sort($limits);
				
				foreach($limits as $value){
					$this->data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&limit=' . $value)
					);
				}
				
				$url = '';

				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
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
				$pagination->url = $this->url->link('product/manufacturer/info','manufacturer_id=' . $this->request->get['manufacturer_id'] .  $url . '&page={page}');
				
				$this->data['text_show_more'] = $this->language->get('text_show_more');
				$this->data['pagination'] = $pagination->render();
				$this->data['pagination_text'] = $pagination->render_text();
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				$this->data['limit'] = $limit;
				
				$this->data['current_sort'] = $this->language->get('text_default');
				
				foreach ($this->data['sorts'] as $_sort){
					if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
						$this->data['current_sort'] = $_sort['text'];
					}
				}
				
				$this->data['continue'] = $this->url->link('common/home');
				
				if (isset($product_total)) {
					$num_pages = ceil($product_total / $limit);
					if ($page == 1) {
						$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
						$this->document->setRobots('index, follow');
						} else {
						$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . "&page=" . $page), 'canonical');
						
						$this->document->setTitle(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getTitle());
						$this->document->setDescription(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getDescription());
						
						$this->document->setRobots("noindex, follow");
					}
					if ($page < $num_pages) {
						$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . ($page + 1)), 'next');
					}
					if ($page > 1) {							
						if ($page == 2) {
							$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'prev');
							} else {
							$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . ($page - 1)), 'prev');
						}
					}
					} else {
					$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
				}
				
				
				$this->data['main_link'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['products_link'] = $this->url->link('product/manufacturer/products', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['collections_link'] = $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['categories_link'] = $this->url->link('product/manufacturer/categories', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['articles_link'] = $this->url->link('product/manufacturer/articles', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['newproducts_link'] = $this->url->link('product/manufacturer/newproducts', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				$this->data['special_link'] = $this->url->link('product/manufacturer/special', 'manufacturer_id=' . $this->request->get['manufacturer_id']);
				
				$this->getActiveLinks($this->request->get['manufacturer_id']);
				
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
				
				//REWARD TEXT
				if ($this->config->get('rewardpoints_appinstall')){
					$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
				}
				
				$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
				);							


				if( isset( $this->request->get['mfilterAjax'] ) ) {
					$settings	= $this->config->get('mega_filter_settings');
					$baseTypes	= array( 'stock_status', 'manufacturers', 'rating', 'attributes', 'price', 'options', 'filters' );
		
					if( isset( $this->request->get['mfilterBTypes'] ) ) {
						$baseTypes = explode( ',', $this->request->get['mfilterBTypes'] );
					}
					
					if( ! empty( $settings['calculate_number_of_products'] ) || in_array( 'categories:tree', $baseTypes ) ) {
						if( empty( $settings['calculate_number_of_products'] ) ) {
							$baseTypes = array( 'categories:tree' );
						}
				
						$this->load->model( 'module/mega_filter' );

						$idx = 0;
		
						if( isset( $this->request->get['mfilterIdx'] ) )
							$idx = (int) $this->request->get['mfilterIdx'];
						
						$this->data['mfilter_json'] = json_encode( MegaFilterCore::newInstance( $this, NULL )->getJsonData($baseTypes, $idx) );
					}
				
					foreach( $this->children as $mf_child ) {
						$mf_child = explode( '/', $mf_child );
						$mf_child = array_pop( $mf_child );
						$this->data[$mf_child] = '';
					}
						
					$this->children=array();
					$this->data['header'] = $this->data['column_left'] = $this->data['column_right'] = $this->data['content_top'] = $this->data['content_bottom'] = $this->data['footer'] = '';
				}
				
				if( ! empty( $this->data['breadcrumbs'] ) && ! empty( $this->request->get['mfp'] ) ) {
					
					foreach( $this->data['breadcrumbs'] as $mfK => $mfBreadcrumb ) {
						$mfReplace = preg_replace( '/path\[[^\]]+\],?/', '', $this->request->get['mfp'] );
						$mfFind = ( mb_strpos( $mfBreadcrumb['href'], '?mfp=', 0, 'utf-8' ) !== false ? '?mfp=' : '&mfp=' );
						
						$this->data['breadcrumbs'][$mfK]['href'] = str_replace(array(
							$mfFind . $this->request->get['mfp'],
							'&amp;mfp=' . $this->request->get['mfp'],
							$mfFind . urlencode( $this->request->get['mfp'] ),
							'&amp;mfp=' . urlencode( $this->request->get['mfp'] )
						), $mfReplace ? $mfFind . $mfReplace : '', $mfBreadcrumb['href'] );
					}
				}
				
				
				$this->response->setOutput($this->render());
				} else {
				
				$url = '';

				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
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

<?
	class ControllerProductCollection extends Controller {  
		
		public function index() {
			$this->language->load('product/collection');		
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			
			$this->load->model('catalog/collection');
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['collection_id'])) {
				$collection_id = (int)$this->request->get['collection_id'];
				} else {
				$collection_id = 0;
			} 
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {			
				$sort = $this->config->get('sort_default');
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = $this->config->get('order_default');
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
			
			$this->data['do_not_show_left_aside_in_list'] = true;
			$this->data['page_type'] = 'collection';
			
			$collection_info = $this->model_catalog_collection->getCollection($collection_id);
			
			if (!isset($collection_info['manufacturer_id']) || !$collection_info['manufacturer_id']){
				if ($this->config->get('config_monobrand')>0){
					$collection_info['manufacturer_id'] = $this->config->get('config_monobrand');					
				}			
			}
			
			//производитель
			$this->load->model('catalog/manufacturer');				
			$manufacturer = $this->model_catalog_manufacturer->getManufacturer($collection_info['manufacturer_id']);
			
			
			if ($collection_info && $manufacturer) {
				$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
				$this->document->addScript('/catalog/view/theme/kp/js/cloud-zoom-new/cloudzoom.js');
				$this->document->addStyle('/catalog/view/theme/kp/js/cloud-zoom-new/cloudzoom.css');
				if ($this->config->get('config_google_remarketing_type') == 'ecomm') {
					
					$this->data['google_tag_params'] = array(
					'ecomm_prodid' => '',
					'ecomm_pagetype' => 'category',
					'ecomm_totalvalue' => 0
					);
					
				} 
				
				
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $manufacturer['name'],
				'href'      => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->data['breadcrumbs'][] = array(
				'text'      => 'Коллекции ' . $manufacturer['name'],
				'href'      => $this->url->link('product/manufacturer/collections', 'manufacturer_id=' . $manufacturer['manufacturer_id']),
				'separator' => $this->language->get('text_separator')
				);
				
				if ($collection_info['parent_id']){
					if ($parent_collection = $this->model_catalog_collection->getCollection($collection_info['parent_id'])){
						
						
						if (isset($parent_collection['parent_id']) && $parent_collection['parent_id']){
							$parent2_collection = $this->model_catalog_collection->getCollection($parent_collection['parent_id']);
							
							if (isset($parent2_collection['name'])) {
								$this->data['breadcrumbs'][] = array(
								'text'      => $parent2_collection['name'],
								'href'      => $this->url->link('product/collection', 'collection_id=' . $parent2_collection['collection_id']),
								'separator' => $this->language->get('text_separator')
								);			
							}
							
						}								
						
						$this->data['breadcrumbs'][] = array(
						'text'      => $parent_collection['name'],
						'href'      => $this->url->link('product/collection', 'collection_id=' . $parent_collection['collection_id']),
						'separator' => $this->language->get('text_separator')
						);
						
					}	
				}
				
				$this->document->setKeywords($collection_info['meta_keyword']);
				$this->document->setDescription($collection_info['meta_description']);
				$this->data['description'] = html_entity_decode($collection_info['description'], ENT_QUOTES, 'UTF-8');
				
				($collection_info['seo_title'] == '')?$this->document->setTitle($collection_info['name']):$this->document->setTitle($collection_info['seo_title']);
				
				if ($collection_info['seo_h1'] != '') {
					$this->data['heading_title'] = $collection_info['seo_h1'];	
					} else {
					$this->data['heading_title'] = $collection_info['name'];		
				}
				
				$this->document->addLink($this->url->link('product/collection', 'collection_id=' . $collection_id), 'canonical');
				
				$url = '';
				
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
				
				/*
					if ($collection_info['virtual'] == 0) {
					
					$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('collection') .' '. $collection_info['name'],
					'href'      => $this->url->link('product/collection', 'collection_id=' . $collection_id . $url),
					'separator' => $this->language->get('text_separator')
					);
					
					} else {
					
					$this->data['breadcrumbs'][] = array(
					'text'      => $collection_info['name'],
					'href'      => $this->url->link('product/collection', 'collection_id=' . $collection_id . $url),
					'separator' => $this->language->get('text_separator')
					);
					
					}
				*/
				
				//child collections
				$data = array('filter_parent_id' => $collection_id);
				$results = $this->model_catalog_collection->getCollections($data);
				
				$this->data['children'] = array();
				
				foreach ($results as $result){
					
					if (is_numeric(utf8_substr($result['name'], 0, 1))) {
						$key = '0 - 9';
						} else {
						$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
					}
					
					$this->data['children'][$key]['name'] = $key;
					$this->data['children'][$key]['anchor'] = $this->url->link('product/collection', 'collection_id=' . $collection_id).'#'.$key;
					
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], 500, 500, '', 90);
						} else {
						$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 500, 500, '', 90);
					}
					
					if (!$result['no_brand']) {
						$this->data['children'][$key]['collection'][] = array(				
						'name' 			=> $result['name'],						
						'thumb'       	=> $image,
						'short_description' => $result['short_description'],
						'collection_id' => $result['collection_id'],
						'href'          => $this->url->link('product/collection', 'collection_id=' . $result['collection_id'])									
						);	
						
					}
				}
				
				
				
				$this->data['text_empty'] = $this->language->get('text_empty');
				$this->data['text_quantity'] = $this->language->get('text_quantity');
				$this->data['text_collection'] = $this->language->get('text_collection');
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
				
				$this->data['button_cart'] = $this->language->get('button_cart');
				$this->data['button_wishlist'] = $this->language->get('button_wishlist');
				$this->data['button_compare'] = $this->language->get('button_compare');
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				if ($collection_info['banner']) {
					$this->data['banner'] = $this->model_tool_image->resize($collection_info['banner'], 276, 276, '', 100, true); 	
					$this->data['banner_ohuevshiy'] = $this->model_tool_image->resize($collection_info['banner'], 1000, 1000, '', 100, true); 		
					} else {
					$this->data['banner_ohuevshiy'] = $this->data['banner'] = false;
				}
				
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($collection_info['manufacturer_id']);
				
                if ($manufacturer_info) {
					$this->data['manufacturer_img'] = $this->model_tool_image->resize($manufacturer_info['image'],50, 50);
					$this->data['manufacturer_img_big'] = $this->model_tool_image->resize($manufacturer_info['image'],	100, 100);
					$this->data['manufacturer_img_260'] = $this->model_tool_image->resize($manufacturer_info['image'],	300, 100);
										
					$this->data['manufacturer_name'] = $manufacturer_info['name'];
					$this->data['manufacturer_location'] = $manufacturer_info['location'];
					$this->data['manufacturer_href'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id']);
					} else {
					$this->data['manufacturer_location'] = false;
					$this->data['manufacturer_img'] = false;
					$this->data['manufacturer_name'] = false;
					$this->data['manufacturer_href'] = false;
				}
				
				$this->data['compare'] = $this->url->link('product/compare');
				
				if ($collection_info['image']) {
					$this->data['popup_ohuevshiy'] = $this->model_tool_image->resize($collection_info['image'], 1000, 1000);	
					} else {
					$this->data['popup_ohuevshiy'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 1000, 1000);
				}
				
				if ($collection_info['image']) {
					$this->data['popup'] = $this->model_tool_image->resize($collection_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));							
					} else {
					$this->data['popup'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
				}
				
				if ($collection_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($collection_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));							
					} else {
					$this->data['thumb'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				}
				
				if ($collection_info['image']) {
					$this->data['smallimg'] = $this->model_tool_image->resize($collection_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));			
					} else {
					$this->data['smallimg'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_additional_width'),	$this->config->get('config_image_additional_height'));
				}
				
				
				$this->data['images'] = array();
				
				$results = $this->model_catalog_collection->getCollectionImages($this->request->get['collection_id']);
				
				foreach ($results as $result) {
					$this->data['images'][] = array(				
					'popup_ohuevshiy'  	=> $this->model_tool_image->resize($result['image'],	1000, 1000),				
					'popup' 	 		=> $this->model_tool_image->resize($result['image'],	$this->config->get('config_image_popup_width'),	$this->config->get('config_image_popup_height')),			
					'middle' 			=> $this->model_tool_image->resize($result['image'],  	$this->config->get('config_image_thumb_width'),	$this->config->get('config_image_thumb_height')),			
					'thumb'  			=> $this->model_tool_image->resize($result['image'],   	$this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
					);
				}
				
				$this->data['products'] = array();
				
				$this->data['image_sizes'] = $this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
				
				$data = array(
				'filter_collection_id' 	 => $collection_id, 
				'no_child'      		 => true, 
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
				);
				
				
				$product_total = $this->model_catalog_product->getTotalProducts($data);

				$bestseller_limit = (int)($product_total / 4);			
				$bestsellers = $this->model_catalog_product->getBestSellerProductsForCollection($bestseller_limit, $collection_id, false, true);									
					
				$results = $this->model_catalog_product->getProducts($data);		
					
				$this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
					
				$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results, $bestsellers);
				
				
				$url = '';
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$this->data['sorts'] = array();

				foreach ($this->registry->get('sorts') as $sortConfig){
					if ($sortConfig['visible']){
						$this->data['sorts'][] = array(
                			'text'  => $this->language->get($sortConfig['text_variable']),
                			'value' => ($sortConfig['field'] . '-' . $sortConfig['order']),
                			'href'  => $this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id'] . '&sort=' . $sortConfig['field'] . '&order='. $sortConfig['order'] . $url)
						);
					}
				}	
				
				$url = '';
				
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
					'href'  => $this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id'] . $url . '&limit=' . $value)
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
				$pagination->url = $this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id'] .  $url . '&page={page}');
				
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
				$this->data['hb_snippets_bc_enable'] = $this->config->get('hb_snippets_bc_enable');
				
				$num_pages = ceil($product_total / $limit);
				if ($page == 1) {
					$this->document->addLink($this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id']), 'canonical');
					$this->document->setRobots('index, follow');
					} else {
					$this->document->addLink($this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id'] . '&page=' . $page), 'canonical');
					
					$this->document->setTitle(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getTitle());
					$this->document->setDescription(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getDescription());
					
					$this->document->setRobots("noindex, follow");
				}
				if ($page < $num_pages) {
					$this->document->addLink($this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id'] . '&page=' . ($page + 1)), 'next');
				}
				if ($page > 1) {
					// Remove page duplicate
					if ($page == 2) {
						$this->document->addLink($this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id']), 'prev');
						} else {
						$this->document->addLink($this->url->link('product/collection', 'collection_id=' . $this->request->get['collection_id'] . '&page=' . ($page - 1)), 'prev');
					}
				}
				
				//REWARD TEXT
				if ($this->config->get('rewardpoints_appinstall')){
					$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
				}
				
				
				if ($product_total){
					$this->template = 'product/collection.tpl';
					} else {
					$this->template = 'product/collection_parent.tpl';
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
				
				if (isset($this->request->get['collection_id'])) {
					$url .= '&collection_id=' . $this->request->get['collection_id'];
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
	
?>
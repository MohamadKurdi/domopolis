<?
	class ControllerProductCountrybrand extends Controller {  
		
		public function index() {
			$this->language->load('product/countrybrand');
			
			
			$this->load->model('catalog/countrybrand');
			$this->load->model('catalog/manufacturer');			
			$this->load->model('tool/image'); 
			
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			foreach ($this->language->loadRetranslate('product/listing') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			
			if (isset($this->request->get['countrybrand_id'])) {
				$countrybrand_id = (int)$this->request->get['countrybrand_id'];
				} else {
				$countrybrand_id = 0;
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
			$this->data['page_type'] = 'countrybrand';
			
			$countrybrand_info = $this->model_catalog_countrybrand->getCountrybrand($countrybrand_id);
			
			if ($countrybrand_info) {
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
				
				
				$this->document->setKeywords($countrybrand_info['meta_keyword']);
				$this->document->setDescription($countrybrand_info['meta_description']);
				$this->data['description'] = html_entity_decode($countrybrand_info['description'], ENT_QUOTES, 'UTF-8');
				
				($countrybrand_info['seo_title'] == '')?$this->document->setTitle($countrybrand_info['name']):$this->document->setTitle($countrybrand_info['seo_title']);
				
				if ($countrybrand_info['seo_h1'] != '') {
					$this->data['heading_title'] = $countrybrand_info['seo_h1'];	
					} else {
					$this->data['heading_title'] = $countrybrand_info['name'];		
				}
				
				$filter_data = array(				
					'filter_country' => $countrybrand_info['name'],					
					'start'			 => 0,
					'limit'			 => 9999
				);
				
				$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

				$this->log->debug($results);
				
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
						$back_image = $this->model_tool_image->resize($result['back_image'], 300, 300);

						
						$this->data['categories'][$key]['manufacturer'][] = array(
						'name' => $result['name'],
						'mcategories' => $mcategories,
						'image' => $image,
						'back_image' => $back_image,
						'short_description' => strip_tags($description[$this->config->get('config_language_id')]['short_description']),
						'location' => $description[$this->config->get('config_language_id')]['location'],
						'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
						);
					}
				}
				
				
				$this->document->addLink($this->url->link('product/countrybrand', 'countrybrand_id=' . $countrybrand_id), 'canonical');
				
				
				$this->data['text_empty'] = $this->language->get('text_empty');
				$this->data['text_quantity'] = $this->language->get('text_quantity');
				$this->data['text_countrybrand'] = $this->language->get('text_countrybrand');
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
				
				if ($countrybrand_info['banner']) {
					$this->data['banner'] = $this->model_tool_image->resize($countrybrand_info['banner'], 276, 276, '', 100, true); 	
					$this->data['banner_ohuevshiy'] = $this->model_tool_image->resize($countrybrand_info['banner_ohuevshiy'], 1000, 1000, '', 100, true); 		
					} else {
					$this->data['banner_ohuevshiy'] = $this->data['banner'] = false;
				}
				
				
				$this->data['compare'] = $this->url->link('product/compare');
				
				if ($countrybrand_info['image']) {
					$this->data['popup_ohuevshiy'] = $this->model_tool_image->resize($countrybrand_info['image'], 1000, 1000);											
					} else {
					$this->data['popup_ohuevshiy'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 1000, 1000);
				}
				
				if ($countrybrand_info['image']) {
					$this->data['popup'] = $this->model_tool_image->resize($countrybrand_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));			
					} else {
					$this->data['popup'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
				}
				
				if ($countrybrand_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($countrybrand_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));						
					} else {
					$this->data['thumb'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
				}
				
				if ($countrybrand_info['image']) {
					$this->data['smallimg'] = $this->model_tool_image->resize($countrybrand_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));										
					} else {
					$this->data['smallimg'] = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_additional_width'),	$this->config->get('config_image_additional_height'));
				}
				
				
				
				$this->data['images'] = array();
				$results = $this->model_catalog_countrybrand->getCountrybrandImages($this->request->get['countrybrand_id']);
				
				foreach ($results as $result) {
					$this->data['images'][] = array(					
					'popup_ohuevshiy'  => $this->model_tool_image->resize($result['image'],	1000, 1000),
					'popup'  => $this->model_tool_image->resize($result['image'],	$this->config->get('config_image_popup_width'),	$this->config->get('config_image_popup_height')),
					'middle' => $this->model_tool_image->resize($result['image'],  $this->config->get('config_image_thumb_width'),	$this->config->get('config_image_thumb_height')),
					'thumb'  => $this->model_tool_image->resize($result['image'],   $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
					);
				}
				
				
				$this->data['image_sizes'] = $this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
				
				
				$this->template = $this->config->get('config_template') . '/template/countrybrand/default.tpl';
				
				if ($countrybrand_info['template']){
					$this->template = $this->config->get('config_template') . '/template/countrybrand/' . $countrybrand_info['template'] . '.tpl';
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
				
				if (isset($this->request->get['countrybrand_id'])) {
					$url .= '&countrybrand_id=' . $this->request->get['countrybrand_id'];
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

<?php 
	class ControllerProductCategory extends Controller {
		
		
		private function return404(){
			
			$this->language->load('product/category');
			
			$url = '';
			
			if( ! empty( $this->request->get['mfp'] ) ) {
				$url .= '&mfp=' . $this->request->get['mfp'];
			}
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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
				
		private function renderSubcategories(){
			
			$this->language->load('product/category');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/manufacturer');
		}
		
		
		public function index($args = array()) {						
			$this->language->load('product/category');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			$this->load->model('tool/path_manager');
			
			$this->load->model('tool/image'); 
			
			if (isset($this->request->get['filter'])) {
				$filter = $this->request->get['filter'];
				} else {
				$filter = '';
			}
			
			if (isset($this->request->get['filter_ocfilter'])) {
				$filter_ocfilter = $this->request->get['filter_ocfilter'];
				} else {
				$filter_ocfilter = '';
			}
			
			if (isset($this->request->get['intersection_id'])) {
				$intersection_id = $this->request->get['intersection_id'];
				} else {
				$intersection_id = '';
			}
			
			if (isset($this->request->get['filterinstock'])) {
				$filterinstock = $this->request->get['filterinstock'];
				} else {
				$filterinstock = '';
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
				$limit = (int)$this->request->get['limit'];
				} else {
				$limit = $this->config->get('config_catalog_limit');
			}
			
			$this->data['page_type'] = 'category';
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			if (isset($this->request->get['path'])) {
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
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
				
				$path = '';
				
				$parts = explode('_', (string)$this->request->get['path']);
				
				$category_id = (int)array_pop($parts);
			}
			
			$this->load->model('catalog/superstat');				
			if (!empty($this->request->get['manufacturer_id'])){
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
				
				if (!$manufacturer_info){
					return $this->return404();
					//$this->response->redirect($this->url->link('product/category', 'path=' . $this->request->get['path']));			
				}
			}
			
			if (!empty($this->request->get['manufacturer_id']) && $manufacturer_info) {
				$this->load->model('module/keyworder');
				
				$new_result = $this->model_module_keyworder->getCategory($category_id,  $this->request->get['manufacturer_id']);
				
				$this->model_catalog_superstat->addToSuperStat('m', $this->request->get['manufacturer_id']);	
				
				$category_info = $new_result['data'];	
				
				if ($category_info) {
					$keyworder_template = $new_result['template'][$this->config->get('config_language_id')];
					
					$input = array(
					'{category_name}',
					'{category_h1}',
					'{category_title}',
					'{alt_image}',
					'{title_image}',
					'{category_meta_keyword}',
					'{category_meta_description}',
					'{category_description}',
					'{manufacturer_name}',
					'{manufacturer_h1}',
					'{manufacturer_title}',
					'{manufacturer_meta_keyword}',
					'{manufacturer_meta_description}',
					'{manufacturer_description}'
					);
					
					$output = array(
					'category_name'           	    	=> $category_info['name'],
					'category_h1'           	    	=> $category_info['seo_h1'],
					'category_title'             		=> $category_info['seo_title'],
					'alt_image'             			=> $category_info['alt_image'],
					'title_image'             			=> $category_info['title_image'],
					'category_meta_keyword'             => $category_info['meta_keyword'],
					'category_meta_description'         => $category_info['meta_description'],
					'category_description'              => $category_info['description'],
					'manufacturer_name'              	=> isset($category_info['manufacturer_name']) ? $category_info['manufacturer_name'] : '',
					'manufacturer_h1'              		=> $category_info['manufacturer_seo_h1'],
					'manufacturer_title'           		=> $category_info['manufacturer_seo_title'],
					'manufacturer_meta_keyword'         => $category_info['manufacturer_meta_keyword'],
					'manufacturer_meta_description'     => $category_info['manufacturer_meta_description'],
					'manufacturer_description'          => $category_info['manufacturer_description']
					);
					
					if (!empty($category_info['new_h1'])) {
						$category_info['seo_h1'] = $category_info['new_h1'];
						} elseif (!empty($keyworder_template['seo_h1'])) {
						$category_info['seo_h1'] = str_replace($input, $output, $keyworder_template['seo_h1']);
						} else {
						$category_info['seo_h1'] = $category_info['name'];					
					}		
					
					if (!empty($category_info['new_title'])) {
						$category_info['seo_title'] = $category_info['new_title'];
						} elseif (!empty($keyworder_template['seo_title'])) {
						$category_info['seo_title'] = str_replace($input, $output, $keyworder_template['seo_title']);
						} else {
						$category_info['seo_title'] = $category_info['name'];
					}
					
					if (!empty($category_info['new_meta_keyword'])) {
						$category_info['meta_keyword'] = $category_info['new_meta_keyword'];
						} elseif (!empty($keyworder_template['meta_keyword'])) {
						$category_info['meta_keyword'] = str_replace($input, $output, $keyworder_template['meta_keyword']);
						} else {
						$category_info['meta_keyword'] = null;
					}
					
					if (!empty($category_info['new_meta_description'])) {
						$category_info['meta_description'] = $category_info['new_meta_description'];
						} elseif (!empty($keyworder_template['meta_description'])) {
						$category_info['meta_description'] = str_replace($input, $output, $keyworder_template['meta_description']);
						} else {
						$category_info['meta_description'] = null;
					}
					
					if (!empty($category_info['new_description'])) {
						$category_info['description'] = $category_info['new_description'];
						} elseif (!empty($keyworder_template['description'])) {
						$category_info['description'] = str_replace($input, $output, $keyworder_template['description']);
						} else {
						$category_info['description'] = null;
					}
					
					$category_info['description_bottom'] = null;
					} else {
					$category_info = $this->model_catalog_category->getCategory($category_id);				
				}
				} else {
				$category_info = $this->model_catalog_category->getCategory($category_id);
			}			
			
			if (isset($this->request->get['manufacturer_id'])){
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];	
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
				);
				
				if (!empty($category_info['manufacturer_name'])){
					$manufacturer_name = $category_info['manufacturer_name'];
					} else {
					$manufacturer_name = $manufacturer_info['name'];
				}
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $manufacturer_name,
				'href'      => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']),
				'separator' => $this->language->get('text_separator')
				);
			}
			
			
			//overloading for getChild
			if (isset($args['path']) && $args['path']){
				$this->request->get['path'] = $args['path'];
			}
			
			if (isset($this->request->get['path'])) {
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
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
				
				$path = '';
				
				$parts = explode('_', (string)$this->request->get['path']);				
				$category_id = (int)array_pop($parts);
								
								
				$parts = explode('_', (string)$this->model_tool_path_manager->getFullCategoryPath($category_id, true));	
				foreach ($parts as $path_id) {
					if (!$path) {
						$path = (int)$path_id;
						} else {
						$path .= '_' . (int)$path_id;
					}
					
					$_category_info = $this->model_catalog_category->getCategory($path_id);
					
					if ($_category_info) {
						$this->data['breadcrumbs'][] = array(
						'text'      => $_category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path . $url),
						'separator' => $this->language->get('text_separator')
						);
					}
				}
				} else {
				$category_id = 0;
			}
			
			if ($category_info) {
				if (!empty($this->request->get['intersection_id'])){
					$intersection_info = $this->model_catalog_category->getCategory($this->request->get['intersection_id']);
					if ($intersection_info){					
						$category_info['seo_h1'] 			= str_replace($category_info['name'], ($category_info['name'] . ' - ' . $intersection_info['name'] ), $category_info['seo_h1']);
						$category_info['meta_description'] 	= str_replace($category_info['name'], ($category_info['name'] . ' - ' . $intersection_info['name'] ), $category_info['meta_description']);
						$category_info['name'] 				= ($category_info['name'] . ' - ' . $intersection_info['name']);	
					}
				}
			

				if (isset($this->request->get['manufacturer_id'])) {	
					$this->document->setTitle($category_info['seo_title']);
					} else {
					($category_info['seo_title'] == '')?$this->document->setTitle($category_info['name']):$this->document->setTitle($category_info['seo_title']);			
				}
				
				$this->data['category_id'] = $category_info['category_id'];
				
				if ($this->config->get('config_google_remarketing_type') == 'ecomm') {
					
					$this->data['google_tag_params'] = array(
					'ecomm_prodid' => '',
					'ecomm_pagetype' => 'category',
					'ecomm_totalvalue' => 0
					);
					
				}
				
				$this->model_catalog_superstat->addToSuperStat('c', $category_id);							
				
				$this->document->setDescription($category_info['meta_description']);
				$this->document->setKeywords($category_info['meta_keyword']);
				
				if ($this->config->get('hb_snippets_og_enable') == '1'){
					$hb_snippets_ogc = $this->config->get('hb_snippets_ogc');
					if (strlen($hb_snippets_ogc) > 4){
						$ogc_name = $category_info['name'];
						$hb_snippets_ogc = str_replace('{name}',$ogc_name,$hb_snippets_ogc);
						}else{
						$hb_snippets_ogc = $category_info['name'];
					}
					
					$this->document->addOpenGraph('og:title', $hb_snippets_ogc);
					$this->document->addOpenGraph('og:type', 'website');
					
					$this->document->addOpenGraph('og:image', HTTP_SERVER . 'image/' . $category_info['image']);
					$this->document->addOpenGraph('og:url', $this->url->link('product/category', 'path=' . $this->request->get['path']));
					$this->document->addOpenGraph('og:description', $category_info['meta_description']);
					//$this->document->addOpenGraph('article:publisher', $this->config->get('hb_snippets_fb_page'));
				}
				
				$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
				
				$this->data['heading_title'] = (isset($category_info['seo_h1']) && mb_strlen($category_info['seo_h1']) >= 1)?$category_info['seo_h1']:$category_info['name'];
				
				$this->data['text_refine'] = $this->language->get('text_refine');
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
				
				$this->data['button_cart'] = $this->language->get('button_cart');
				$this->data['button_wishlist'] = $this->language->get('button_wishlist');
				$this->data['button_compare'] = $this->language->get('button_compare');
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				// Set the last category breadcrumb		
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
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
								
				
				if ($category_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
					} else {
					$this->data['thumb'] = '';
				}
				
				if ($page == 1){
					$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
					} else {
					$this->data['description'] = '';
				}
				$this->data['compare'] = $this->url->link('product/compare');
				
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}	
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
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
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				$current_manufacturer = (isset($this->request->get['manufacturer_id']))?$this->request->get['manufacturer_id']:0;
				
				$fmSettings = $this->config->get('mega_filter_settings');
				
				if( ! empty( $fmSettings['not_remember_filter_for_subcategories'] ) && false !== ( $mfpPos = strpos( $url, '&mfp=' ) ) ) {
					$mfUrlBeforeChange = $url;
					$mfSt = mb_strpos( $url, '&', $mfpPos+1, 'utf-8');
					$url = $mfSt === false ? '' : mb_substr($url, $mfSt, mb_strlen( $url, 'utf-8' ), 'utf-8');
				}
				
				$this->data['categories'] = array();

				if (!$current_manufacturer && (!$category_info['parent_id'] || $this->config->get('config_display_subcategory_in_all_categories'))){
					
					$this->data['categories'] = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, ['subcategories'], [$current_manufacturer, $category_id]));

					if (!$this->data['categories']) {	
						
						$fmSettings = $this->config->get('mega_filter_settings');
						
						if( ! empty( $fmSettings['not_remember_filter_for_subcategories'] ) && false !== ( $mfpPos = strpos( $url, '&mfp=' ) ) ) {
							$mfUrlBeforeChange = $url;
							$mfSt = mb_strpos( $url, '&', $mfpPos+1, 'utf-8');
							$url = $mfSt === false ? '' : mb_substr($url, $mfSt, mb_strlen( $url, 'utf-8' ), 'utf-8');
						}
						
						$this->data['dimensions'] = array(
						'w' => $this->config->get('config_image_subcategory_width'),
						'h' => $this->config->get('config_image_subcategory_height')
						);
						
						
						$this->data['categories'] = array();
						if (!empty($this->request->get['manufacturer_id'])){						
							$results = $this->model_catalog_manufacturer->getTreeCategoriesByManufacturer($this->request->get['manufacturer_id'], $category_id);
							$manufacturer = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
							
							foreach ($results as $result) {
								if ($result['image']) {
									$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
									} else {
									$image = false;
								}
								
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
								
								$this->data['categories'][] = [
								'thumb'   	 	=> $image,
								'name'  		=> $result['name'] . ' ' . $manufacturer['name'],
								'href'  		=> $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url_s),
								'product_count' => $product_count
								];					
							}
							
							} else {
							
							$results = $this->model_catalog_category->getCategories($category_id);

							foreach ($results as $result) {								

								if ($result['menu_name']){
									$result['name'] = $result['menu_name'];
								}
								
								if ($result['image']) {
									$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);									
									} else {
									$image = $this->model_tool_image->resize('no_image.png', $this->data['dimensions']['w'], $this->data['dimensions']['h']);									
								}

								$children = [];
								if ($this->config->get('config_second_level_subcategory_in_categories')){
									$child_results = $this->model_catalog_category->getCategories($result['category_id'], $this->config->get('config_subcategories_limit'));

									foreach ($child_results as $child_result) {								
										if ($child_result['menu_name']){
											$child_result['name'] = $child_result['menu_name'];
										}

										if ($child_result['image']) {
											$child_image = $this->model_tool_image->resize($child_result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);									
										} else {
											$child_image = $this->model_tool_image->resize('no_image.png', $this->data['dimensions']['w'], $this->data['dimensions']['h']);								
										}

										$children[] = [
											'thumb'   		=> $child_image,
											'name'  		=> $child_result['name'],
											'href'  		=> $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . '_' . $child_result['category_id']),
											'product_count'	=> $this->config->get('config_product_count')?$child_result['product_count']:false
										];
									}
								}						
								
								$this->data['categories'][] = [
								'thumb'   		=> $image,								
								'children'		=> $children,
								'name'  		=> $result['name'],
								'href'  		=> $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id']),
								'product_count'	=> $this->config->get('config_product_count')?$result['product_count']:false
								];					
							}
							
						}
						$this->cache->set($this->registry->createCacheQueryString(__METHOD__, ['subcategories'], [$current_manufacturer, $category_id]), $this->data['categories']);
					}
				}
				
				if( isset( $mfUrlBeforeChange ) ) {
					$url = $mfUrlBeforeChange;
					unset( $mfUrlBeforeChange );
				}
				
				
				$top_actions = $this->model_catalog_category->getCategoryActions($category_id);				
				$this->data['top_actions'] = array();				
				foreach ($top_actions as $top_action){
					
					if ($top_action['image']) {
						$image = $this->model_tool_image->resize($top_action['image'], 790, 325);
						} else {
						$image = false;
						
					}
					
					$this->data['top_actions'][] = array(						
					'thumb'       	=> $image,
					'name'	 		=> $top_action['caption'],
					'href' 			=> $this->url->link('information/actions','actions_id=' . $top_action['actions_id'])
					);
				}

				
				if ($this->config->get('config_special_category_id') && (int)$category_id == (int)$this->config->get('config_special_category_id')) {					
					$this->model_catalog_product->fillSpecialCategory();
				}
				
				$this->data['products'] = array();
				
				$ifToDisplayProductsFromSubCategories = true;
				if (!$this->config->get('config_disable_filter_subcategory')){
					$ifToDisplayProducts = false;

					if ($this->config->get('config_disable_filter_subcategory_only_for_main')){
						if ($category_info['parent_id'] > 0){
							$ifToDisplayProducts = true;
						}
					}
				}

				$data = array(
				'filter_category_id' 			=> $category_id,
				'filter_sub_category' 			=> $ifToDisplayProductsFromSubCategories,
				'filter_filter'      			=> $filter,
				'filter_ocfilter'    			=> $filter_ocfilter,
				'filter_category_id_intersect' 	=> $intersection_id,
				'filter_sub_category_intersect' => true,
				'filterinstock' 				=> $filterinstock,
				'no_child'      				=> true, 
				'sort'               			=> $sort,
				'order'              			=> $order,
				'start'              			=> ($page - 1) * $limit,
				'limit'              			=> $limit
				);
				
				if ($category_info['category_id'] == GENERAL_MARKDOWN_CATEGORY) {
					$data['filter_enable_markdown'] = true;
				}				
				
				if (!empty($category_info['deletenotinstock'])) {
					$data['filter_current_in_stock'] = true;
					
					if (mt_rand(0, 10) <= 3){
						//	$this->model_catalog_product->cleanupCategoryWithCurrentStockData($category_id);
					}
				}
				
				if (isset($this->request->get['manufacturer_id'])) {
					$data['filter_manufacturer_id'] = $this->request->get['manufacturer_id'];
				}
				
				$fmSettings = $this->config->get('mega_filter_settings');
				
				if( ! empty( $fmSettings['show_products_from_subcategories'] ) ) {
					if( ! empty( $fmSettings['level_products_from_subcategories'] ) ) {
						$fmLevel = (int) $fmSettings['level_products_from_subcategories'];
						$fmPath = explode( '_', empty( $this->request->get['path'] ) ? '' : $this->request->get['path'] );
						
						if( $fmPath && count( $fmPath ) >= $fmLevel ) {
							$data['filter_sub_category'] = '1';
						}
						} else {
						$data['filter_sub_category'] = $ifToDisplayProductsFromSubCategories;
					}
				}
				
				$data['filter_sub_category'] = $ifToDisplayProductsFromSubCategories;
				
				if( ! empty( $this->request->get['manufacturer_id'] ) ) {
					$data['filter_manufacturer_id'] = (int) $this->request->get['manufacturer_id'];
				}
				
				if( ! empty( $fmSettings['in_stock_default_selected'] ) ) {
					$this->data['column_left'] = $this->getChild('common/column_left');
					$this->data['column_right'] = $this->getChild('common/column_right');
					$this->data['content_top'] = $this->getChild('common/content_top');
					$this->data['mfp_column_left'] = true;
					$this->data['mfp_column_right'] = true;
					$this->data['mfp_content_top'] = true;
				}
				
				$product_total = $this->model_catalog_product->getTotalProducts($data); 	
				
				$bestseller_limit = (int)($product_total / 10);
				if( ! empty( $this->request->get['manufacturer_id'] ) ) {
					$manufacturer_id = $this->request->get['manufacturer_id'];
					} else {
					$manufacturer_id = false;
				}
				
				$bestsellers = $this->model_catalog_product->getBestSellerProductsForCategory($bestseller_limit, $category_id, $manufacturer_id, true);
				
				if (isset($this->request->get['manufacturer_id'])){
					if ($product_total == 0){
						if ($this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id'])){
							$this->redirect($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 301);
							} else {
							$this->redirect($this->url->link('product/category', 'path=' . $this->request->get['path']), 301);
						}
					}
				}
				
				$results = $this->model_catalog_product->getProducts($data);
				
				$this->data['dimensions'] = array(
				'w' => $this->config->get('config_image_product_width'),
				'h' => $this->config->get('config_image_product_height')
				);
				
				$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results, $bestsellers);
				
				$this->load->model('catalog/actions');								
				$actions = $this->model_catalog_actions->getCategoryActions($category_id);
				
				if ($actions && count($actions)){
					if (count($actions) == 1){
						if ($page == 1 && count($this->data['products']) > 10){
							
							$action = array_shift($actions);
							if ($action['image_to_cat']) {
								$image = $this->model_tool_image->resize($action['image_to_cat'], 407, 491);								
								} else {
								$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 407, 491);
							}
							
							$action_data = array(
							'is_inserted_action' => true,
							'thumb'       => $image,
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
								} else {
								$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 407, 491);
							}
							
							$action_data = array(
							'is_inserted_action' 	=> true,
							'thumb'       			=> $image,
							'title' 				=> $action['title'],
							'href' 					=> $this->url->link('information/actions','actions_id=' . $action['actions_id'])
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
				
				
				$this->data['intersections'] = array();
				$this->data['intersections_l2'] = array();
				if ($category_info['intersections']){
					$results = $this->model_catalog_category->getCategoriesIntersections($category_info['category_id']);
					
					foreach ($results as $result){
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
							} else {
							$image = false;
						}
						
						if ($result['menu_name']){
							$result['name'] = $result['menu_name'];
						}
						
						//	$product_total = $this->model_catalog_product->getTotalProducts($data);				
						
						if (!empty($this->request->get['intersection_id']) && $result['category_id'] == $this->request->get['intersection_id']){
							$intersection_active = true;
							} else {
							$intersection_active = false;
						}
						
						$this->data['intersections'][] = array(
						'thumb' => $image,
						'active' => $intersection_active,
						'name'  => $result['name'],
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&intersection_id=' . $result['category_id'])
						);					
						
					}
					
					if (!empty($this->request->get['intersection_id'])){
						$results = $this->model_catalog_category->getCategoriesIntersections($category_info['category_id'], $this->request->get['intersection_id']);
						
						if (!$results){
							$intersection_info = $this->model_catalog_category->getCategory($this->request->get['intersection_id']);
							
							if ($intersection_info['parent_id']){
								$results = $this->model_catalog_category->getCategoriesIntersections($category_info['category_id'], $intersection_info['parent_id']);
							}
						}
						
						foreach ($results as $result){
							if ($result['image']) {
								$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
								} else {
								$image = false;
							}
							
							if ($result['menu_name']){
								$result['name'] = $result['menu_name'];
							}
							
							//	$product_total = $this->model_catalog_product->getTotalProducts($data);				
							
							if (!empty($this->request->get['intersection_id']) && $result['category_id'] == $this->request->get['intersection_id']){
								$intersection_active = true;
								} else {
								$intersection_active = false;
							}
							
							$this->data['intersections_l2'][] = array(
							'thumb' => $image,
							'active' => $intersection_active,
							'name'  => $result['name'],
							'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&intersection_id=' . $result['category_id'])
							);					
							
						}
						
					}				
				}
				
				if (isset($this->request->get['manufacturer_id'])){
					unset($data['filter_manufacturer_id']);
					$data['exclude_manufacturer_id'] = (int)$this->request->get['manufacturer_id'];
					$data['limit'] = 20;
					$data['start'] = 0;
					
					$results = $this->model_catalog_product->getProducts($data);
					unset($result);
					
					$this->data['additional_products'] = array();
					$this->data['category_name'] = mb_strtolower($category_info['name']);
					
					$this->data['category_link'] = $this->url->link('product/category', 'path=' . $this->request->get['path']);
					
					foreach ($results as $result) {
						
						$this->data['dimensions'] = array(
						'w' => $this->config->get('config_image_product_width'),
						'h' => $this->config->get('config_image_product_height')
						);
						
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->data['dimensions']['w'], $this->data['dimensions']['h']);
							} else {
							$image = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->data['dimensions']['w'], $this->data['dimensions']['h']);
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
						
						if (isset($result['display_price_national']) && $result['display_price_national'] && $result['display_price_national'] > 0 && $result['currency'] == $this->currency->getCode()){
							$price = $this->currency->format($this->tax->calculate($result['display_price_national'], $result['tax_class_id'], $this->config->get('config_tax')), $result['currency'], 1);
						}
						
						if (false){
							
							if ($option_prices = $this->model_catalog_product->getProductOptionPrices($result['product_id'])){
								if (isset($option_prices['special']) && $option_prices['special']){
									$special = $option_prices['special'];
									} else {
									$special = false;
								}
								
								if (isset($option_prices['price']) && $option_prices['price']){
									$price = $option_prices['price'];
								}
								
								if ($option_prices['result']){
									$result['price'] = $option_prices['result']['price'];
									$result['special'] = $option_prices['result']['special'];
								}
							}
							
						}
						
						if ($this->config->get('config_review_status')) {
							$rating = (int)$result['rating'];
							} else {
							$rating = false;
						}
						
						$is_not_certificate = (strpos($result['location'], 'certificate') === false);
						
						$_url = '';
						if (isset($this->request->get['filter'])) {
							$_url .= '&filter=' . $this->request->get['filter'];
						}	
						
						if (isset($this->request->get['filter_ocfilter'])) {
							$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
						}
						
						if (isset($this->request->get['sort'])) {
							$_url .= '&sort=' . $this->request->get['sort'];
						}	
						
						if (isset($this->request->get['order'])) {
							$_url .= '&order=' . $this->request->get['order'];
						}	
						
						if (isset($this->request->get['limit'])) {
							$_url .= '&limit=' . $this->request->get['limit'];
						}
						
						
						
						$_description = '';
						if ($is_not_certificate){
							
							if (mb_strlen($result['description']) > 10){
								$_description = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 128) . '..';
							}
							
							} else {
							$_description = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
						}
						
						
						
						$this->data['additional_products'][] = array(
						'new'         => $result['new'],
						'show_action' => $result['additional_offer_count'],
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
						'is_set' 	  => $result['set_id'],
						'name'        => $result['name'],
						'description' => $_description,
						'price'       => $price,
						'special'     => $special,
						'colors'	  => $this->model_catalog_product->getProductColorsByGroup($result['product_id'], $result['color_group']),
						'options'	  => $this->model_catalog_product->getProductOptionsForCatalog($result['product_id']),
						'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
						'tax'         => $tax,
						'rating'      => $result['rating'],
						'sort_order'  => $result['sort_order'],
						'can_not_buy' => ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),
						'stock_status'  => $result['stock_status'],
						'location'      => $result['location'],
						'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'quickview'        => $this->url->link('product/quickview', 'product_id=' . $result['product_id']),
						'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $_url)
						);
					}
				}							
				
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
				}
				
				if( ! empty( $this->request->get['intersection_id'] ) ) {
					$url .= '&intersection_id=' . $this->request->get['intersection_id'];
				}
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
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
                			'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=' . $sortConfig['field'] . '&order='. $sortConfig['order'] . $url)
						);
					}
				}			
				
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
				}
				
				if( ! empty( $this->request->get['intersection_id'] ) ) {
					$url .= '&intersection_id=' . $this->request->get['intersection_id'];
				}
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
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
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
					);
				}
				
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}				
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
				}
				
				if( ! empty( $this->request->get['intersection_id'] ) ) {
					$url .= '&intersection_id=' . $this->request->get['intersection_id'];
				}
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['filter_ocfilter'])) {
					$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
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
				
				if ((isset($this->request->get['manufacturer_id'])) && (isset($keyworder_template['image_description']) && $keyworder_template['image_description'] == 1) && ($category_info['manufacturer_image'])) {
					$this->data['thumb'] = $this->model_tool_image->resize($category_info['manufacturer_image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
					/*	$this->document->setOgImage($this->data['thumb']); */
				}												
				
				$pagination = new Pagination($this->registry);
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
				
				$this->data['text_show_more'] = $this->language->get('text_show_more');
				$this->data['pagination'] = $pagination->render();
				$this->data['pagination_text'] = $pagination->render_text();
				
				$this->data['sort']  = $sort;
				$this->data['order'] = $order;
				$this->data['limit'] = $limit;
				
				$this->data['current_sort'] = $this->language->get('text_default');				
				foreach ($this->data['sorts'] as $_sort){
					if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
						$this->data['current_sort'] = $_sort['text'];
					}
				}
				
				$this->data['continue'] = $this->url->link('common/home');
				
				if (isset($this->request->get['manufacturer_id'])){
					$additional_url = "&manufacturer_id=".(int)$this->request->get['manufacturer_id'];
					} else {
					$additional_url = '';
				}
				$num_pages = ceil($product_total / $limit);
				
				// Canonical
				if ($page == 1) {
					$this->document->addLink($this->url->link('product/category', 'path=' . $this->request->get['path'] . $additional_url) , 'canonical');
					$this->document->setRobots('index, follow');
					} else {
					$this->document->addLink($this->url->link('product/category', 'path=' . $this->request->get['path'] . $additional_url . '&page=' . ($page)) , 'canonical');
					
					$this->document->setTitle(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getTitle());
					$this->document->setDescription(sprintf($this->language->get('text_page'), (int)$page) . $this->document->getDescription());

					if ($this->config->get('config_index_category_pages')){
						$this->document->setRobots("index, follow");
					} else {
						$this->document->setRobots("noindex, follow");
					}
				}										
				
				
				// Next
				if ($page < $num_pages) {				
					$this->data['next_page'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $additional_url . '&page=' . ($page + 1));					
					$this->document->addLink($this->data['next_page'], 'next');
					} else {
					$this->data['next_page'] = false;
				}
				
				// Prev
				if ($page > 1) {
					if ($page == 2) {
						$this->data['prev_page'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $additional_url);
						$this->document->addLink($this->data['prev_page'], 'prev');
						} else {
						$this->data['prev_page'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $additional_url . '&page=' . ($page - 1));
						$this->document->addLink($this->data['prev_page'], 'prev');
					}
					} else {
					$this->data['prev_page'] = false;
				}
				
				//OCFILTER
				// OCFilter Start
				$ocfilter_page_info = $this->getChild('module/ocfilter/getPageInfo');
				
				if ($ocfilter_page_info) {
					$this->document->setTitle($ocfilter_page_info['meta_title']);
					
					if ($ocfilter_page_info['meta_description']) {
						$this->document->setDescription($ocfilter_page_info['meta_description']);
					}
					
					if ($ocfilter_page_info['meta_keyword']) {
						$this->document->setKeywords($ocfilter_page_info['meta_keyword']);
					}
					
					$this->data['heading_title'] = $ocfilter_page_info['title'];
					
					if ($ocfilter_page_info['description'] && !isset($this->request->get['page']) && !isset($this->request->get['sort']) && !isset($this->request->get['order']) && !isset($this->request->get['search']) && !isset($this->request->get['limit'])) {
						$this->data['description'] = html_entity_decode($ocfilter_page_info['description'], ENT_QUOTES, 'UTF-8');
					}
					} else {
					$meta_title = $this->document->getTitle();
					$meta_description = $this->document->getDescription();
					$meta_keyword = $this->document->getKeywords();
					
					$filter_title = $this->getChild('module/ocfilter/getSelectedsFilterTitle');
					
					if ($filter_title) {
						if (false !== strpos($meta_title, '{filter}')) {
							$meta_title = trim(str_replace('{filter}', '(' . $filter_title . ')', $meta_title));
							} else {
							$meta_title .= ' (' . $filter_title . ')';
						}
						
						$this->document->setTitle($meta_title);
						
						if ($meta_description) {
							if (false !== strpos($meta_description, '{filter}')) {
								$meta_description = trim(str_replace('{filter}', '(' . $filter_title . ')', $meta_description));
								} else {
								$meta_description .= ' (' . $filter_title . ')';
							}
							
							$this->document->setDescription($meta_description);
						}
						
						if ($meta_keyword) {
							if (false !== strpos($meta_keyword, '{filter}')) {
								$meta_keyword = trim(str_replace('{filter}', '(' . $filter_title . ')', $meta_keyword));
								} else {
								$meta_keyword .= ' (' . $filter_title . ')';
							}
							
							$this->document->setKeywords($meta_keyword);
						}
						
						$heading_title = $this->data['heading_title'];
						
						if (false !== strpos($heading_title, '{filter}')) {
							$heading_title = trim(str_replace('{filter}', '(' . $filter_title . ')', $heading_title));
							} else {
							$heading_title .= ' (' . $filter_title . ')';
						}
						
						$this->data['heading_title'] = $heading_title;
						
						$this->data['description'] = '';
						} else {
						$this->document->setTitle(trim(str_replace('{filter}', '', $meta_title)));
						$this->document->setDescription(trim(str_replace('{filter}', '', $meta_description)));
						$this->document->setKeywords(trim(str_replace('{filter}', '', $meta_keyword)));
						
						$this->data['heading_title'] = trim(str_replace('{filter}', '', $this->data['heading_title']));
					}
				}
				//OCFILTER END
				
				
				require_once(DIR_SYSTEM . 'library/microdata/opengraph/category.php');
				require_once(DIR_SYSTEM . 'library/microdata/twittercard/category.php');
				
				//REWARD TEXT
				if ($this->config->get('rewardpoints_appinstall')){
					$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
				}
				
				//Нужно определить текущий layout, а потом получить его шаблон!
				//В первую очередь - если назначен лэйаут для категории

				$this->load->model('design/layout');
				$layout_id = $this->model_catalog_category->getCategoryLayoutId($category_id);
				if (!$layout_id){				
					$layout_id = $this->model_design_layout->getLayout('product/category');				
				}						

				if ($template = $this->model_design_layout->getLayoutTemplateByLayoutId($layout_id)) {
					$this->template = $template;
				} else {
						//Если нет переназначения Layout, то проверяем переназначение конкретной категории
					$template_overload = false;
					$this->load->model('setting/setting');
					$custom_template_module = $this->model_setting_setting->getSetting('custom_template_module', $this->config->get('config_store_id'));				
					if(!empty($custom_template_module['custom_template_module'])){
						foreach ($custom_template_module['custom_template_module'] as $key => $module) {
							if (($module['type'] == 0) && !empty($module['categories'])) {
								if (in_array($category_id, $module['categories'])) {
									$this->template = $module['template_name'];
									$template_overload = true;
								}
							}
						}
					}					
					if (!$template_overload) {
						$this->template = 'product/category.tpl';				
					}
				}
				
				$no_shop = (isset($args['no_shop']) && $args['no_shop']);
				
				
				if (!$no_shop) {
					
					$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
					);
					} else {
					$this->data['header'] = '';
					$this->data['footer'] = '';
					$this->data['content_top'] = '';
					$this->data['content_bottom'] = '';
				}
				
				$this->response->setOutput($this->render());										
				} else {
				$url = '';
				
				if( ! empty( $this->request->get['mfp'] ) ) {
					$url .= '&mfp=' . $this->request->get['mfp'];
				}
				
				if( ! empty( $this->request->get['filterinstock'] ) ) {
					$url .= '&filterinstock=' . $this->request->get['filterinstock'];
				}
				
				if (isset($this->request->get['path'])) {
					$url .= '&path=' . $this->request->get['path'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
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
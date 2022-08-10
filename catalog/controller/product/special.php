<?php 
	class ControllerProductSpecial extends Controller { 	
		public function index() { 
			$this->language->load('product/special');
			
			$this->load->model('catalog/product');
			
			$this->load->model('tool/image');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = $this->registry->get('sort_default');
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = $this->registry->get('order_default');
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
			
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
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
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/special', $url),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
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
			
			$this->data['compare'] = $this->url->link('product/compare');
			
			$this->data['products'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'no_child'  => true, 
			);
			
			if( isset( $this->request->get['category_id'] ) ) {
				$data['filter_category_id'] = (int) $this->request->get['category_id'];
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
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['sorts'] = array();

			foreach ($this->registry->get('sorts') as $sortConfig){
				if ($sortConfig['visible']){
					$this->data['sorts'][] = array(
						'text'  => $this->language->get($sortConfig['text_variable']),
						'value' => ($sortConfig['field'] . '-' . $sortConfig['order']),
						'href'  => $this->url->link('product/special', '&sort=' . $sortConfig['field'] . '&order='. $sortConfig['order'] . $url)
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
				'href'  => $this->url->link('product/special', $url . '&limit=' . $value)
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
			$pagination->url = $this->url->link('product/special', $url . '&page={page}');
			
			$this->data['text_show_more'] = $this->language->get('text_show_more');
			$this->data['pagination'] = $pagination->render();
			$this->data['pagination_text'] = $pagination->render_text();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
			
			$num_pages = ceil($product_total / $limit);
			if ($page == 1) {
				$this->document->addLink($this->url->link('product/special'), 'canonical');
				$this->document->setRobots("index, follow");
				} else {
				$this->document->addLink($this->url->link('product/special', '&page=' . $page), 'canonical');
				$this->document->setRobots("noindex, follow");
			}
			if ($page < $num_pages) {
				$this->document->addLink($this->url->link('product/special', '&page=' . ($page + 1)), 'next');
			}
			if ($page > 1) {
				// Remove page duplicate
				if ($page == 2) {
					$this->document->addLink($this->url->link('product/special'), 'prev');
					} else {
					$this->document->addLink($this->url->link('product/special', '&page=' . ($page - 1)), 'prev');
				}
			}
			
			$this->data['current_sort'] = $this->language->get('text_default');
			
			foreach ($this->data['sorts'] as $_sort){
				if ($this->data['sort'] . '-'. $this->data['order'] == $_sort['value']){
					$this->data['current_sort'] = $_sort['text'];
				}
			}
			
			//REWARD TEXT
			if ($this->config->get('rewardpoints_appinstall')){
				$this->data['text_retranslate_app_block'] = sprintf($this->data['text_retranslate_app_block_reward'], $this->currency->format($this->config->get('rewardpoints_appinstall'), $this->config->get('config_currency_national'), 1));
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/special.tpl';
				} else {
				$this->template = 'default/template/product/special.tpl';
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
		}
	}		
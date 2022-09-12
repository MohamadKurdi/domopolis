<?php
	class Controllerproductproductnew extends Controller {
		public function index() {
			$this->language->load('product/search');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');			
			$this->load->model('tool/image');
			
			
			
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
			
			$this->document->setTitle("Новинки");
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
			);
			
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
			
			
			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_critea'] = $this->language->get('text_critea');
			$this->data['text_search'] = $this->language->get('text_search');
			$this->data['text_keyword'] = $this->language->get('text_keyword');
			$this->data['text_category'] = $this->language->get('text_category');
			$this->data['text_sub_category'] = $this->language->get('text_sub_category');
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
			$this->data['text_new_products'] = $this->language->get('new_products');
			
			$this->data['entry_search'] = $this->language->get('entry_search');
			$this->data['entry_description'] = $this->language->get('entry_description');
			
			$this->data['button_search'] = $this->language->get('button_search');
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			
			$this->data['compare'] = $this->url->link('product/compare');
			
			$this->load->model('catalog/category');
			
			$this->data['products'] = array();
			
			$data = array(
            'new'                 => 1,
            'sort'                => $sort,
            'order'               => $order,
            'start'               => ($page - 1) * $limit,
            'limit'               => $limit
			);
			
			$product_total = $this->model_catalog_product->getTotalProducts($data);			
			$results = $this->model_catalog_product->getProducts($data);
			
			$this->data['dimensions'] = array(
			'w' => $this->config->get('config_image_product_width'),
			'h' => $this->config->get('config_image_product_height')
			);
			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
			
			
			$url = '';
			
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$this->data['sorts'] = array();
			
            $this->data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('product/product_new', 'sort=p.sort_order&order=ASC' . $url)
            );
            $this->data['sorts'][] = array(
			'text'  => $this->language->get('price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('product/product_new', 'sort=p.price&order=ASC' . $url)
            );
            $this->data['sorts'][] = array(
			'text'  => $this->language->get('price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('product/product_new', 'sort=p.price&order=DESC' . $url)
            );
            $this->data['sorts'][] = array(
			'text'  => $this->language->get('populars'),
			'value' => 'p.viewed-DESC',
			'href'  => $this->url->link('product/product_new', 'sort=p.viewed&order=DESC' . $url)
            );
			
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
                'href'  => $this->url->link('product/product_new', $url . '&limit=' . $value)
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
			$pagination->url = $this->url->link('product/product_new', $url . '&page={page}');
			
			$this->data['text_show_more'] = $this->language->get('text_show_more');
			$this->data['pagination'] = $pagination->render();
			$this->data['pagination_text'] = $pagination->render_text();
			
			$num_pages = ceil($product_total / $limit);
			if ($page == 1) {
				$this->document->addLink($this->url->link('product/product_new'), 'canonical');
				} else {
				$this->document->addLink($this->url->link('product/product_new', '&page=' . $page), 'canonical');
				$this->document->addRobotsMeta("noindex, follow");
			}
			if ($page < $num_pages) {
				$this->document->addLink($this->url->link('product/product_new', '&page=' . ($page + 1)), 'next');
			}
			if ($page > 1) {
				// Remove page duplicate
				if ($page == 2) {
					$this->document->addLink($this->url->link('product/product_new'), 'prev');
					} else {
					$this->document->addLink($this->url->link('product/product_new', '&page=' . ($page - 1)), 'prev');
				}
			}
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
			
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
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/new_products.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/new_products.tpl';
				} else {
				$this->template = 'default/template/product/new_products.tpl';
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
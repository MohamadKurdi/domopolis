<?php
	class ControllerModuleViewed extends Controller {
								
		public function viewed(){
			$viewed_products = array();
			
			$this->load->model('catalog/viewed');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$limit = $this->config->get('viewed_count');
			
			if ($this->model_catalog_viewed->getTotalViewed()>0) {
				$products = $this->model_catalog_viewed->getListViewed($limit);			
				} else {
				$products = array();
			}
			
			$this->language->load('module/viewed'); 
			$this->data['heading_title'] = $this->language->get('heading_title');		
			$this->data['button_cart'] = $this->language->get('button_cart');
			
			$this->data['viewed_href'] = $this->url->link('account/viewed');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$results = array();
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					$results[$product_id] = $product_info;
				}
			}
			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
			
			if ($this->data['products']){
				$page_type = $this->request->get['x'];
				$tabs = $this->request->get['y'];
				
				if ($tabs){
					$this->template = $this->config->get('config_template') . '/template/blocks/viewed.tpl';
					} elseif ($page_type == 'product') {
					$this->template = $this->config->get('config_template') . '/template/blocks/viewed_notabs_product.tpl';
					} else {
					$this->template = $this->config->get('config_template') . '/template/blocks/viewed_notabs.tpl';
				}
				
				$this->response->setOutput($this->render());	
				} else {
				$this->response->setOutput('');			
			}
			
			
			
		}
		
		protected function index($setting) {
			
			$viewed_products = array();
			
			$this->load->model('catalog/viewed');
			$this->load->model('tool/image');
			
			$limit = $this->config->get('viewed_count');
			
			
			$this->language->load('module/viewed'); 
			$this->data['heading_title'] = $this->language->get('heading_title');		
			$this->data['button_cart'] = $this->language->get('button_cart');
			
			$this->data['viewed_href'] = $this->url->link('account/viewed');
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->data['products'] = array();
			
			$this->data['dimensions'] = array(
			'w' => $setting['image_width'],
			'h' => $setting['image_height']
			);
			$this->data['position'] = $setting['position'];
			
			
			if (empty($setting['tabs'])){
				$setting['tabs'] = false;
			}
			
			$tabs = $setting['tabs'];
			
			//ДОПОЛНИТЕЛЬНЫЕ ТАБЫ
			if (isset($setting['product_block'])){
				$indexes = array_map('intval', explode(',', $setting['product_block']));
				} else {
				$indexes = array(1);
			}
			
			$this->data['tabs'] = array();
			foreach ($indexes as $idx) {
				
				if (!$this->config->get('blockviewed_titles_' . $idx . '_' . $this->config->get('config_language_id'))){
					continue;
				}
				
				$products = explode(',', $this->config->get('blockviewed_product_' . $idx));
				
				if (!$products){
					continue;
				}
				
				$this->data['tabs'][$idx] = array(
				'title' => $this->config->get('blockviewed_titles_' . $idx . '_' . $this->config->get('config_language_id')),
				'href' => $this->config->get('blockviewed_hrefs_' . $idx),
				'products' => array()
				);				
				
				if ($banner = $this->config->get('blockviewed_images_' . $idx)){
					$this->data['tabs'][$idx]['banner'] = $this->model_tool_image->resize($banner, 280 , 491);					
					} else {
					$this->data['tabs'][$idx]['banner'] = false;
				}
				
				if (empty($setting['limit'])) {
					$setting['limit'] = 10;
				}								
				
				$this->data['position'] = $setting['position'];
				$this->data['dimensions'] = array(
				'w' => $setting['image_width'],
				'h' => $setting['image_height']
				);
				
				$products = array_slice($products, 0, (int)$setting['limit']);
				$results = array();
				
				foreach ($products as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					
					if ($product_info) {
						$results[$product_id] = $product_info;
					}
				}
				
				$this->data['tabs'][$idx]['products'] = $this->model_catalog_product->prepareProductToArray($results);
				
			}
			
			$this->data['page_type'] = '';
			if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product'){
				$this->data['page_type'] = 'product';
			}
			
			if ($tabs){
				$this->template = $this->config->get('config_template') . '/template/module/viewed.tpl';
				} elseif ($this->data['page_type'] == 'product') {
				$this->template = $this->config->get('config_template') . '/template/module/viewed_notabs_product.tpl';
				} else {
				$this->template = $this->config->get('config_template') . '/template/module/viewed_notabs.tpl';
			}
			
			$this->render();
		}
	}								
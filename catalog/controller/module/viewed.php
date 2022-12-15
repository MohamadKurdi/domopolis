<?php
class ControllerModuleViewed extends Controller {

	public function viewed(){
		$viewed_products = array();

		$this->load->model('catalog/viewed');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$limit = $this->config->get('viewed_count');

		if ($this->model_catalog_viewed->getTotalViewed() >0 ) {
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
				$this->template = 'blocks/viewed';
			} elseif ($page_type == 'product') {
				$this->template = 'blocks/viewed_notabs_product';
			} else {
				$this->template = 'blocks/viewed_notabs';
			}

			$this->response->setOutput($this->render());	
		} else {
			$this->response->setOutput('');			
		}
	}

	protected function index($setting) {

		$out = $this->cache->get($this->registry->createCacheQueryString(__METHOD__, $setting));

		if ($out) {		

			$this->setCachedOutput($out);
			
		} else {

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

			if (isset($setting['product_block'])){
				$indexes = array_map('intval', explode(',', $setting['product_block']));
			} else {
				$indexes = array(1);
			}


			if (empty($setting['limit'])) {
				$setting['limit'] = 10;
			}	

			$this->data['tabs'] = array();
			foreach ($indexes as $idx) {
				$products = [];			
				if ($this->config->get('blockviewed_product_' . $idx)){
					$productIDS = explode(',', $this->config->get('blockviewed_product_' . $idx));
					$productIDS = array_slice($productIDS, 0, (int)$setting['limit']);

					$products = $this->model_catalog_product->getProductsByIDS($productIDS, ['filter_quantity' => true]);
				}

				if (!$products) {				
					if (!empty($this->config->get('blockviewed_empty_type_' . $idx))){
						if ($this->config->get('blockviewed_empty_type_' . $idx) == 'top-viewed'){

							$filter_data = [								
								'sort'                => 'p.viewed',
								'filter_only_viewed'  => true, 
							//	'filter_with_variants'=> true,
								'order'               => 'DESC',
								'start'               => 0,
								'limit'               => $setting['limit']
							];

							$products = $this->model_catalog_product->getProducts($filter_data);
							

						} elseif($this->config->get('blockviewed_empty_type_' . $idx) == 'new') {

							$filter_data = [								
								'sort'                			=> 'p.date_added',
								'filter_different_categories' 	=> true,
							//	'filter_with_variants'=> true,
								'order'               			=> 'DESC',
								'start'               			=> 0,
								'limit'               			=> $setting['limit']
							];

							$products = $this->model_catalog_product->getProducts($filter_data);
						}

					}
				}


				if (!$products){
					continue;
				}

				if (!$this->config->get('blockviewed_titles_' . $idx . '_' . $this->config->get('config_language_id'))){
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

				$this->data['position'] = $setting['position'];
				$this->data['dimensions'] = array(
					'w' => $setting['image_width'],
					'h' => $setting['image_height']
				);

				$this->data['tabs'][$idx]['products'] = $this->model_catalog_product->prepareProductToArray($products);

			}

			$this->data['page_type'] = '';
			if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product'){
				$this->data['page_type'] = 'product';
			}

			if ($this->data['tabs']){
				$this->template = 'module/viewed';
			} elseif ($this->data['page_type'] == 'product') {
				$this->template = 'module/viewed_notabs_product';
			} else {
				$this->template = 'module/viewed_notabs';
			}

			$out = $this->render();
			$this->cache->set($this->registry->createCacheQueryString(__METHOD__, $setting), $out);			
		}
	}
}								
<?php
	class ControllerShortCodeProductListInBlog extends Controller {
		protected function index($data) {			
			$this->load->model('catalog/product');
			
			$list = $data['list'];
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}

			$results = array();
			foreach ($list as $product_id){
				if ($product = $this->model_catalog_product->getProduct($product_id)){
					$results[] = $product;
				}
			}
			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);

			$this->template = $this->config->get('config_template') . '/template/shortcodes/product_blog.tpl';
			
			$this->render();
		}		
	}
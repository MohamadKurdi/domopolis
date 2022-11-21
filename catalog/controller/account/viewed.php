<?php 
class ControllerAccountViewed extends Controller { 	
	public function index() { 

		$this->language->load('account/viewed');

		$this->document->setTitle($this->language->get('text_viewed'));
		$this->data['heading_title'] = $this->language->get('text_viewed');

		$this->language->load('product/special');
		$this->load->model('catalog/viewed');									
		$this->load->model('tool/image'); 		
		$this->load->model('catalog/product');


		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);


		$this->data['image_sizes'] = array(
			'w' => $this->config->get('config_image_product_width'),
			'h' => $this->config->get('config_image_product_height')
		);

		foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
			$this->data[$translationСode] = $translationText;
		}

		if ($this->model_catalog_viewed->getTotalViewed() > 0){
			$viewed = $this->model_catalog_viewed->getListViewed();
			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');

			$results = array();

			foreach ($viewed as $product_id) {
				if ($product = $this->model_catalog_product->getProduct($product_id)){
					$results[] = $product;
				}
			}

			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);

			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_go_back'] = $this->language->get('go_back');

			$this->data['button_cart'] = $this->language->get('button_cart');	
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');

			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));		

			$this->data['compare'] = $this->url->link('product/compare');

			$this->template = $this->config->get('config_template') . '/template/account/viewed.tpl';			

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
			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_error'] = $this->language->get('text_empty');						

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home');
			$this->template = 'account/viewed.tpl';			
			
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
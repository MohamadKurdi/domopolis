<?php 
	class ControllerAccountWishList extends Controller {
		public function index() {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/wishlist', '');
				
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->language->load('account/wishlist');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			
			$this->load->model('tool/image');
			
			if (!isset($this->session->data['wishlist'])) {
				$this->session->data['wishlist'] = array();
			}
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}

			foreach ($this->language->loadRetranslate('account/account') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}

			
			if (isset($this->request->get['remove'])) {
				$key = array_search($this->request->get['remove'], $this->session->data['wishlist']);
				
				if ($key !== false) {
					unset($this->session->data['wishlist'][$key]);
				}
				
				$this->session->data['success'] = $this->language->get('text_remove');
				
				$this->redirect($this->url->link('account/wishlist'));
			}
			
			$this->document->setTitle($this->language->get('heading_title'));	
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', ''),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');	
			
			$this->data['text_empty'] = $this->language->get('text_empty');
			
			$this->data['column_image'] = $this->language->get('column_image');
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_stock'] = $this->language->get('column_stock');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_remove'] = $this->language->get('button_remove');
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$results = [];
			
			foreach ($this->session->data['wishlist'] as $key => $product_id) {								
				if ($product_info = $this->model_catalog_product->getProduct($product_id)) { 
					$results[] = $product_info; 			
					} else {
					unset($this->session->data['wishlist'][$key]);
				}
			}

			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);				
			$this->data['remove_href'] = $this->url->link('account/wishlist', 'remove=');
			$this->data['continue'] = $this->url->link('account/account', '');
			
			$this->template = 'account/wishlist.tpl';
			
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
		
		public function add() {
			$this->language->load('account/wishlist');
			
			$json = array();
			
			if (!isset($this->session->data['wishlist'])) {
				$this->session->data['wishlist'] = array();
			}
			
			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if (!in_array($this->request->post['product_id'], $this->session->data['wishlist'])) {	
					$this->session->data['wishlist'][] = $this->request->post['product_id'];
				}
				
				if ($this->customer->isLogged()) {			
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));				
					} else {
					$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', ''), $this->url->link('account/register', ''), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));				
				}
				
				$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			}	
			
			$this->response->setOutput(json_encode($json));
		}	
	}
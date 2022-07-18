<?php
	
	class ControllerModuleAlsoPurchased extends Controller
	{
		protected function index( )
		{
			$setting = $this->config->get('alsopurchased_module');
			$setting = $setting[0];
			
			$this->language->load( 'module/alsopurchased' );
			
			$this->data['heading_title'] = $this->language->get( 'heading_title' );
			
			$this->data['button_cart'] = $this->language->get( 'button_cart' );
			
			$this->load->model( 'module/alsopurchased' );
			
			$this->load->model( 'tool/image' );
			
			$this->data['products'] = array();
			
			$this->data['position'] = $setting['position'];
			
			$ajaxrequest = true;//!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';		
			
			if ($ajaxrequest == false) {
				$this->data['product_id'] = (isset($this->request->get['product_id'])) ? $this->request->get['product_id'] : 0;
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsopurchased.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/alsopurchased.tpl';
					} else {
					$this->template = 'default/template/module/alsopurchased.tpl';
				}
				
				$this->response->setOutput($this->render());
				return false;
				} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/alsopurchased/alsopurchased.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/alsopurchased/alsopurchased.tpl';
					} else {
					$this->template = 'default/template/module/alsopurchased/alsopurchased.tpl';
				}
				
			}
			
			if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
				} else {
				$product_id = 0;
			}
			
			$results = $this->model_module_alsopurchased->getPurchasedProductsPerProductId( $product_id, $setting['limit'] );
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);						
			
			$this->response->setOutput($this->render());
		}
		
		public function getindex() {
			echo($this->index());
		}
	}		
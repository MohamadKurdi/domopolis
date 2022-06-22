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
			
			foreach ( $results as $result )
			{
				if ( $result['image'] )
				{
					$image = $this->model_tool_image->resize( $result['image'], $setting['image_width'],
                    $setting['image_height'] );
				}
				else
				{
					$image = false;
				}
				
				if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->
                isLogged() ) || !$this->config->get( 'config_customer_price' ) )
				{
					$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'],
                    $this->config->get( 'config_tax' ) ) );
				}
				else
				{
					$price = false;
				}
				
				if ( ( float )$result['special'] )
				{
					$special = $this->currency->format( $this->tax->calculate( $result['special'], $result['tax_class_id'],
                    $this->config->get( 'config_tax' ) ) );
				}
				else
				{
					$special = false;
				}
				
				if ( $this->config->get( 'config_review_status' ) )
				{
					$rating = $result['rating'];
				}
				else
				{
					$rating = false;
				}
				
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
				
				$this->data['products'][] = array(				
                'product_id' 	=> $result['product_id'],
				'bestseller' 	=> false,
				'new'         	=> $result['new'],
                'thumb' 		=> $image,
				'active_coupon'	=> $this->model_catalog_product->recalculateCouponPrice($result, $this->model_catalog_product->getProductActiveCoupons($result['product_id'])),
                'name' 			=> $result['name'],
                'price' 		=> $price,				
                'special' 		=> $special,				
                'rating' 		=> $rating,				
				'saving'	 	=> round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
				'colors'	  	=> $this->model_catalog_product->getProductColorsByGroup($product_info['product_id'], $product_info['color_group']),
				'options'	  	=> $this->model_catalog_product->getProductOptionsForCatalog($product_info['product_id']),				
				'can_not_buy' 	=> ($result['stock_status_id'] == $this->config->get('config_not_in_stock_status_id')),				
				'stock_status'  => $result['stock_status'],								
                'reviews' 		=> sprintf( $this->language->get( 'text_reviews' ), ( int )$result['reviews'] ),				
                'href' 			=> $this->url->link( 'product/product', 'product_id=' . $result['product_id'] ),
                );
				
			}
			
			$this->response->setOutput($this->render());
		}
		
		public function getindex() {
			echo($this->index());
		}
	}		
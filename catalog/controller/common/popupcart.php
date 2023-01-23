<?php
	
	class ControllerCommonPopupCart extends Controller {
		
		public function coupon(){
			
			if (isset($this->request->request['remove'])) {
				
				
			}
		}
		
		private function validateIfIsCheckoutPage(){
			$checkout_page = $this->url->link('checkout/simplecheckout');
			
			if (!empty($this->request->server['HTTP_REFERER']) && $parsedReferer = parse_url($this->request->server['HTTP_REFERER'])){
				
				if (($parsedReferer['scheme'] . '://' . $parsedReferer['host'] . $parsedReferer['path']) == $checkout_page){
					return true;
				}
			}
			
			return false;
		}
		
		public function index () {
			
			if (isset($this->request->request['remove'])) {
				$this->cart->remove($this->request->request['remove']);
				unset($this->session->data['vouchers'][$this->request->request['remove']]);
			}

			if (isset($this->request->request['update'])) {
				
				if ($this->request->request['update'] == 'explicit'){
									
					if (is_array($this->request->request['explicit'])){
						foreach ($this->request->request['explicit'] as $update_line){
							$exploded = explode('_qt_', $update_line);
							
							$this->cart->update($exploded[0], $exploded[1]);	
						}
					}	
							
				} else {
					$this->cart->update($this->request->request['update'], $this->request->request['qty']);				
				}
				$this->response->setOutput('update ' . $this->request->request['update'] . ':' . $this->request->request['qty'] . ' success');
				} else {
				
				foreach ($this->language->loadRetranslate('common/popupcart') as $translationСode => $translationText){
					$this->data[$translationСode] = $translationText;
				}
				
				foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
					$this->data[$translationСode] = $translationText;
				}
				
				
				
				// Totals
				$this->load->model('setting/extension');
				
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array();
					
					$results = $this->model_setting_extension->getExtensions('total');
					
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					
					array_multisort($sort_order, SORT_ASC, $results);
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);
							
							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
						
						$sort_order = array();
						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
						
						array_multisort($sort_order, SORT_ASC, $total_data);
					}
				}
				
				$this->data['totals'] = $total_data;
				
				$this->data['heading_title'] = $this->language->get('heading_title');
				
				$this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
				$this->data['text_empty'] = $this->language->get('text_empty');
				$this->data['text_cart'] = $this->language->get('text_cart');
				$this->data['text_checkout'] = $this->language->get('text_checkout');
				
				$this->data['config_ocjoyajaxcart_countname'] = $this->config->get('config_ocjoyajaxcart_countname');
				$this->data['config_ocjoyajaxcart_countdesc'] = $this->config->get('config_ocjoyajaxcart_countdesc');
				$this->data['config_ocjoyajaxcart_showmodel'] = $this->config->get('config_ocjoyajaxcart_showmodel');
				
				$this->data['button_remove'] = $this->language->get('button_remove');
				
				$this->load->model('tool/image');
				
				$this->data['products'] = array();			
				
				foreach ($this->cart->getProducts() as $product) {
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], 400, 400);
						} else {
						$image = '';
					}
					
					$option_data = array();
					
					foreach ($product['option'] as $option) {
						if ($option['type'] != 'file') {
							$value = $option['option_value'];
							} else {
							$filename = $this->encryption->decrypt($option['option_value']);
							
							$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
						}
						
						$option_data[] = array('name' => $option['name'], 'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value), 'type' => $option['type']);
					}
					
					// Display prices
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
						} else {
						$price = false;
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price_old = $this->currency->format($this->tax->calculate($product['price_old'], $product['tax_class_id'], $this->config->get('config_tax')));
						} else {
						$price_old = false;
					}
					
					if ($product['price_national'] && $product['price_national'] > 0 && $product['currency'] == $this->currency->getCode()) {
						$price = $this->currency->format($this->tax->calculate($product['price_national'], $product['tax_class_id'], $this->config->get('config_tax')), $product['currency'], 1);
					}
					
					// Display prices
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$total = $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')));
						$total_national = $this->currency->format($product['total_national'], $this->config->get('config_regional_currency'), '1');
						
						} else {
						$total = false;
						$total_national = false;
					}
					
					$this->data['products'][] = array(
					'product_id' 				=> $product['product_id'],
					'is_certificate' 			=> $product['is_certificate'],
					'key' 						=> $product['key'],
					'thumb' 					=> $image,
					'name' 						=> $product['name'],
					'manufacturer' 				=> $product['manufacturer'],
					'is_special_offer' 			=> $product['is_special_offer'],
					'is_special_offer_present' 	=> $product['is_special_offer_present'],
					'model' 					=> $product['model'],
					'points' 					=> $this->currency->formatBonus($product['reward'], true),
					'option' 					=> $option_data,
					'minimum' 					=> $product['minimum'],
					'quantity' 					=> $product['quantity'],
					'current_in_stock'		    => $product['current_in_stock'],
					'fully_in_stock'			=> $product['fully_in_stock'],
					'amount_in_stock'			=> $product['amount_in_stock'],
					'price_old' 				=> $price_old,
					'saving' 					=> $product['saving'],
					'price' 					=> $price,
					'total' 					=> $total,
					'total_national' 			=> $total_national,
					'href' 						=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'childProductArray' 		=> $product['childProductArray']
					);
				}
				
				$this->load->model('catalog/product');
				$this->data['in_stock_text_h4'] = $this->language->get('text_in_stock_' . $this->config->get('config_country_id'));
				$this->data['in_stock_text_h4']  .= ' ' . $this->model_catalog_product->parseStockTerm($this->config->get('config_delivery_instock_term'));				
				$this->data['text_not_in_stock_delivery_term'] = $this->model_catalog_product->parseStockTerm($this->config->get('config_delivery_outstock_term'));
				$this->data['text_in_stock_delivery_term'] = $this->model_catalog_product->parseStockTerm($this->config->get('config_delivery_instock_term'));		
				$this->data['text_line_delivery'] = $this->language->get('text_line_delivery');
				
				// Gift Voucher
				$this->data['vouchers'] = array();
				
				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $key => $voucher) {
						$this->data['vouchers'][] = array('key' => $key, 'description' => $voucher['description'], 'amount' => $this->currency->format($voucher['amount']));
					}
				}
				
				$this->data['coupon'] = false;
				if (!empty($this->session->data['coupon'])) {
					$this->data['coupon'] = $this->session->data['coupon'];
				}			
				
				$this->data['mask'] = $this->config->get('config_phonemask');
				$this->data['country'] = $this->config->get('config_countryname');
				if ($this->customer->isLogged()) {      
					$this->data['email'] = $this->customer->getEmail();
					$this->data['telephone'] = $this->customer->getTelephone();
					} else {      
					$this->data['email'] = '';
					$this->data['telephone'] = '';
				}
				
				$this->data['cart'] = $this->url->link('checkout/cart');
				
				$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
				
				$this->load->model('catalog/product');
				$this->data['ajaxcartproducts'] = array();
				
				$data = array('sort' => 'p.date_added', 'order' => 'DESC', 'start' => 0);
				
				$this->data['button_cart'] = $this->language->get('button_cart');
				$this->data['button_wishlist'] = $this->language->get('button_wishlist');
				$this->data['button_compare'] = $this->language->get('button_compare');
				
				$this->data['text_ajaxcart_head'] = $this->language->get('text_ajaxcart_head');
				$this->data['text_ajaxcart_empty'] = $this->language->get('text_ajaxcart_empty');
				$this->data['text_ajaxcart_continue'] = $this->language->get('text_ajaxcart_continue');
				$this->data['text_gotoorder'] = $this->language->get('text_gotoorder');
				$this->data['text_gotoshipping'] = $this->language->get('text_gotoshipping');
				
				$this->data['column_image'] = $this->language->get('column_image');
				$this->data['column_name'] = $this->language->get('column_name');
				$this->data['column_delete'] = $this->language->get('column_delete');
				$this->data['column_quantity'] = $this->language->get('column_quantity');
				$this->data['column_price'] = $this->language->get('column_price');
				$this->data['column_subtotal'] = $this->language->get('column_subtotal');
				
				
				$this->data['this_is_checkout_page'] = $this->validateIfIsCheckoutPage();
				
				if ($this->data['this_is_checkout_page']){
					$this->template = 'common/popup_cart_checkout.tpl';
					} else {
					$this->template = 'common/popup_cart.tpl';
				}
				$this->response->setOutput($this->render());
			}
			
		}
	}			
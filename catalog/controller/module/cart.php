<?php 
	class ControllerModuleCart extends Controller {
		public function index() {
			$this->language->load('module/cart');
			
			if (isset($this->request->get['remove'])) {
				$this->cart->remove($this->request->get['remove']);
				
				unset($this->session->data['vouchers'][$this->request->get['remove']]);
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
			$this->data['count_items'] = $this->cart->countProducts();

			$this->data['text_in_cart'] = $this->language->get('text_in_cart');

			if ($this->config->get('config_language') == 'uk'){
				$this->data['products_pluralized'] = getUkrainianPluralWord($this->cart->countProducts(), $this->language->get('text_products_pluralized'));			
			} else {
				$this->data['products_pluralized'] = morphos\Russian\NounPluralization::pluralize($this->cart->countProducts(), $this->language->get('text_product'));
			}
			
			//$this->data['text_items'] = (int)($this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
			$this->data['text_empty'] = $this->language->get('text_empty');
			$this->data['text_cart'] = $this->language->get('text_cart');
			$this->data['text_checkout'] = $this->language->get('text_checkout');
			$this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
			$this->data['text_gift'] = $this->language->get('gift');
			$this->data['text_special_offer'] = $this->language->get('special_offer');
			$this->data['text_product_in_set'] = $this->language->get('product_in_set');
			$this->data['text_text_empty'] = $this->language->get('text_empty');
			
			$this->data['text_view_sales'] = sprintf($this->language->get('text_view_sales'), $this->url->link('product/category', 'path=6614'));
			
			$this->data['button_remove'] = $this->language->get('button_remove');
			
			$this->load->model('tool/image');
			
			$this->data['products'] = array();
			
			$this->data['total_quantity'] = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
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
					
					$option_data[] = array(								   
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
					);
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
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
					//	$price_national = $this->currency->convert($product['price'] , $this->config->get('config_currency'), $this->config->get('config_regional_currency'));
					$price_national = $product['price_national'];
					$total_national = $this->currency->format($price_national * $product['quantity'], $this->config->get('config_regional_currency'), '1');
					if ($product['price_old']){
					$total_old_national = $this->currency->format($this->tax->calculate($product['price_old'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
					} else {
					$total_old_national = false;
					}
					} else {
					$total = false;
					$price_national = false;
					$total_national = false;
					$total_old_national = false;
				}
				
				$this->data['total_quantity'] += $product['quantity'];
				
				$this->data['products'][] = array(
				'key'       				=> $product['key'],
				'thumb'     				=> $image,
				'name'      				=> $product['name'],
				'is_special_offer'          => $product['is_special_offer'],
				'is_special_offer_present'  => $product['is_special_offer_present'],
				'model'     				=> $product['model'], 
				'option'    				=> $option_data,
				'quantity'  				=> $product['quantity'],
				'price'     				=> $price,
				'price_national'     		=> $price_national,				
				'total'     				=> $total,
				'saving' 					=> $product['saving'],
				'total_old_national'  		=> $total_old_national,	
				'total_national'  			=> $total_national,	
				'href'      				=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
				'recurring' 				=> $product['recurring'],
				'profile'   				=> $product['profile_name'],
                'childProductArray'  		=> $product['childProductArray']
				);
			}
			
			// Gift Voucher
			$this->data['vouchers'] = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					
					$this->data['total_quantity'] += 1;
					
					$this->data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}
			
			$this->data['route'] = 'general';
			if (!empty($this->request->data['route']) && $this->request->data['route'] == 'checkout/simplecheckout'){
				$this->data['route'] = 'checkout';
			}
			
			
			
			$this->data['cart'] = $this->url->link('checkout/cart');
			$this->data['popupcart'] = $this->url->link('common/popupcart');
			
			$this->data['checkout'] = $this->url->link('checkout/checkout', '');			
			$this->template = 'module/cart.tpl';
			
			$this->response->setOutput($this->render());		
		}
	}	
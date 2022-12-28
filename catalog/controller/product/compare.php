<?php  
	class ControllerProductCompare extends Controller {
		public function index() { 
			$this->language->load('account/account');
			$this->language->load('product/compare');
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			
			
			$this->load->model('tool/image');
			
			if (!isset($this->session->data['compare'])) {
				$this->session->data['compare'] = array();
			}	
			
			foreach ($this->language->loadRetranslate('product/single') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			if (isset($this->request->get['remove'])) {
				$key = array_search($this->request->get['remove'], $this->session->data['compare']);
				
				if ($key !== false) {
					unset($this->session->data['compare'][$key]);
				}
				
				$this->session->data['success'] = $this->language->get('text_remove');
				
				$this->redirect($this->url->link('product/compare'));
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
			'href'      => $this->url->link('account/account'),			
			'separator' => $this->language->get('text_separator')
			);

			$this->data['logged'] = $this->customer->isLogged();

			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_product'] = $this->language->get('text_product');
			$this->data['text_name'] = $this->language->get('text_name');
			$this->data['text_image'] = $this->language->get('text_image');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_availability'] = $this->language->get('text_availability');
			$this->data['text_rating'] = $this->language->get('text_rating');
			$this->data['text_summary'] = $this->language->get('text_summary');
			$this->data['text_weight'] = $this->language->get('text_weight');
			$this->data['text_dimension'] = $this->language->get('text_dimension');
			$this->data['text_empty'] = $this->language->get('text_empty');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_remove'] = $this->language->get('button_remove');
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$this->data['review_status'] = $this->config->get('config_review_status');
			
			$this->data['products'] = array();			
			$this->data['attribute_groups'] = array();	
			
			$results = array();
			
			foreach ($this->session->data['compare'] as $key => $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					
					$results[$product_id] = $product_info;
					
					$attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);
					
					foreach ($attribute_groups as $attribute_group) {
						$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];
						
						foreach ($attribute_group['attribute'] as $attribute) {
							$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
						}
					}
					
					}  else {
					unset($this->session->data['compare'][$key]);
				}
				
			}
			
			$this->data['products'] = $this->model_catalog_product->prepareProductToArray($results);
			
			$tmp = array();
			
			foreach ($this->data['products'] as &$product){
				
				$attribute_data = array();
				$attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);
				
				foreach ($attribute_groups as $attribute_group) {
					foreach ($attribute_group['attribute'] as $attribute) {
						$attribute_data[$attribute['attribute_id']] = $attribute['text'];
					}
				}
								
				$product['remove'] = $this->url->link('product/compare', 'remove=' . $product['product_id']);

				$product['attribute'] = $attribute_data;
				$tmp[$product['product_id']] = $product;
			}
			
			$this->data['products'] = $tmp;
			
			$this->data['continue'] = $this->url->link('common/home');
			
			$this->template = 'product/compare.tpl';
			
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
			$this->language->load('product/compare');
			
			$json = array();
			
			if (!isset($this->session->data['compare'])) {
				$this->session->data['compare'] = array();
			}
			
			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			$this->load->model('catalog/set');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if (!in_array($this->request->post['product_id'], $this->session->data['compare'])) {	
					if (count($this->session->data['compare']) >= 4) {
						array_shift($this->session->data['compare']);
					}
					
					$this->session->data['compare'][] = $this->request->post['product_id'];
				}
				
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('product/compare'));				
				
				$json['total'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			}	
			
			$this->response->setOutput(json_encode($json));
		}
	}
?>
<?php 
	class ControllerAffiliateTracking extends Controller { 
		public function index() {
			if (!$this->affiliate->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('affiliate/tracking', '', 'SSL');
				
				$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
			}
			
			$this->language->load('affiliate/tracking');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('affiliate/tracking', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->load->model('module/affiliate');
			$this->data['coupon'] = $this->model_module_affiliate->getTrackingCoupon($this->affiliate->getId());
			$this->data['text_coupon'] = sprintf($this->language->get('text_coupon'), $this->model_module_affiliate->getTrackingCoupon($this->affiliate->getId()));
			$this->data['category_visible'] = (bool)$this->config->get('category_visible');
			if($this->data['category_visible']) {
				$categoryID = $this->model_module_affiliate->getCategoriesID();
				$category[] = $this->model_module_affiliate->getCategories($this->affiliate->getCode());
				foreach ($categoryID as $value) {
					$idCat = (int) $value['category_id'];
					$category[] = $this->model_module_affiliate->getCategories($this->affiliate->getCode(), $idCat);
				}
				foreach ($category as $key1 => $cat1) {
					foreach ($cat1 as $key2 => $cat2) {
						if (empty($category[$key1][$key2]['products'])) {
							unset($category[$key1][$key2]);
						}
					}
				}
				$this->data['category'] = $category;
			}
			$this->data['button_vk'] = $this->language->get('button_vk');
			$this->data['home'] = $this->model_module_affiliate->getHomeUrl();
			$this->data['text_home_url'] = $this->language->get('text_home_url');
			$this->data['name'] = $this->config->get('config_name');
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = DIR_IMAGE;
				} else {
				$server = DIR_IMAGE;
			}
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . $this->config->get('config_logo');
				} else {
				$this->data['logo'] = '';
			}
			
			$this->data['text_description'] = sprintf($this->language->get('text_description'), $this->config->get('config_name'));
			$this->data['text_code'] = $this->language->get('text_code');
			$this->data['text_generator'] = $this->language->get('text_generator');
			$this->data['text_link'] = $this->language->get('text_link');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['code'] = $this->affiliate->getCode();
			
			$this->data['continue'] = $this->url->link('affiliate/account', '', 'SSL');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/tracking.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/affiliate/tracking.tpl';
				} else {
				$this->template = 'default/template/affiliate/tracking.tpl';
			}
			
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
		
		public function autocomplete() {
			$json = array();
			
			if (isset($this->request->get['filter_name'])) {
				$this->load->model('catalog/product');
				
				$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
				);
				
				$results = $this->model_catalog_product->getProducts($data);
				
				foreach ($results as $result) {
					$json[] = array(
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'link' => str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $result['product_id'] . '&tracking=' . $this->affiliate->getCode()))			
					);	
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
	}
?>
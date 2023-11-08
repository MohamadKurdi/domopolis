<?php
	class ControllerModuleViewed extends Controller {
		private $error = array(); 
		
		private $product_layout_id = 0;
        
		public function index() {   
			$this->load->language('module/viewed');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('setting/setting');
			
			$this->load->model('design/layout');
			
			$layouts = $this->model_design_layout->getLayouts();
			
			$product_layout_name = "";
			
			foreach ($layouts as $layout) {
				$routes = $this->model_design_layout->getLayoutRoutes($layout['layout_id']);
				
				foreach ($routes as $route) {
					if ($route['route'] == 'product/product') {
						$this->product_layout_id = $layout['layout_id'];
						$product_layout_name = $layout['name'];
						break;
					}
				}
				
				if ($this->product_layout_id) {
					break;
				}
			}
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
				$this->model_setting_setting->editSetting('viewed', $this->request->post);		
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_content_top'] = $this->language->get('text_content_top');
			$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
			$this->data['text_column_left'] = $this->language->get('text_column_left');
			$this->data['text_column_right'] = $this->language->get('text_column_right');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			$this->data['text_attention'] = $this->language->get('text_attention');
			
			$this->data['entry_count'] = $this->language->get('entry_count');
			$this->data['entry_image'] = $this->language->get('entry_image');
			$this->data['entry_layout'] = $this->language->get('entry_layout');
			$this->data['entry_position'] = $this->language->get('entry_position');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_show_on_product'] = $this->language->get('entry_show_on_product');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_module'] = $this->language->get('button_add_module');
			$this->data['button_remove'] = $this->language->get('button_remove');
			
			$this->data['product_layout_name'] = $product_layout_name;
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['image'])) {
				$this->data['error_image'] = $this->error['image'];
				} else {
				$this->data['error_image'] = array();
			}
			
			if (isset($this->error['layout'])) {
				$this->data['error_layout'] = $this->error['layout'];
				} else {
				$this->data['error_layout'] = array();
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
			);
			
			$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/viewed', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
			);
			
			$this->data['action'] = $this->url->link('module/viewed', 'token=' . $this->session->data['token']);
			
			$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
			
			$this->data['token'] = $this->session->data['token'];
			
			if (isset($this->request->post['viewed_count'])) {
				$this->data['viewed_count'] = $this->request->post['viewed_count'];
				} else {
				$this->data['viewed_count'] = $this->config->get('viewed_count');
			}	
			
			$this->data['viewed_count'] = isset($this->data['viewed_count']) && !is_null($this->data['viewed_count']) ? $this->data['viewed_count'] : 5;
			
			if (isset($this->request->post['show_on_product'])) {
				$this->data['show_on_product'] = $this->request->post['show_on_product'];
				} else {
				$this->data['show_on_product'] = $this->config->get('show_on_product');
			}	
			
			$this->data['show_on_product'] = isset($this->data['show_on_product']) && !is_null($this->data['show_on_product']) ? $this->data['show_on_product'] : 1;
			
			$this->load->model('tool/image');
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			
			$this->load->model('localisation/language');
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			for ($i=1; $i<=5; $i++) {
				if (isset($this->request->post['blockviewed_product_' . $i])) {
					$this->data['blockviewed_product_' . $i] = $this->request->post['blockviewed_product_' . $i];
					} else {
					$this->data['blockviewed_product_' . $i] = $this->config->get('blockviewed_product_' . $i);
				}	
				
				if (isset($this->request->post['blockviewed_titles_' . $i])) {
					$this->data['blockviewed_titles_' . $i] = $this->request->post['blockviewed_titles_' . $i];
					} else {
					$this->data['blockviewed_titles_' . $i] = $this->config->get('blockviewed_titles_' . $i);
				}
				
				foreach ($this->data['languages'] as $language){
					
					if (isset($this->request->post['blockviewed_titles_' . $i . '_' . $language['language_id']])) {
						$this->data['blockviewed_titles_' . $i . '_' . $language['language_id']] = $this->request->post['blockviewed_titles_' . $i. '_' . $language['language_id']];
						} else {
						$this->data['blockviewed_titles_' . $i . '_' . $language['language_id']] = $this->config->get('blockviewed_titles_' . $i. '_' . $language['language_id']);
					}
					
				}
				
				if (isset($this->request->post['blockviewed_hrefs_' . $i])) {
					$this->data['blockviewed_hrefs_' . $i] = $this->request->post['blockviewed_hrefs_' . $i];
					} else {
					$this->data['blockviewed_hrefs_' . $i] = $this->config->get('blockviewed_hrefs_' . $i);
				}

				if (isset($this->request->post['blockviewed_empty_type_' . $i])) {
					$this->data['blockviewed_empty_type_' . $i] = $this->request->post['blockviewed_empty_type_' . $i];
					} else {
					$this->data['blockviewed_empty_type_' . $i] = $this->config->get('blockviewed_empty_type_' . $i);
				}

				if (isset($this->request->post['blockviewed_threshold_' . $i])) {
					$this->data['blockviewed_threshold_' . $i] = $this->request->post['blockviewed_threshold_' . $i];
					} else {
					$this->data['blockviewed_threshold_' . $i] = $this->config->get('blockviewed_threshold_' . $i);
				}
				
				if (isset($this->request->post['blockviewed_images_' . $i])) {
					$this->data['blockviewed_images_' . $i] = $this->request->post['blockviewed_images_' . $i];
					} else {
					$this->data['blockviewed_images_' . $i] = $this->config->get('blockviewed_images_' . $i);
				}
				
				if ($this->data['blockviewed_images_' . $i]){
					$this->data['blockviewed_images_' . $i . '_image'] = $this->model_tool_image->resize($this->data['blockviewed_images_' . $i], 100, 100);
					} else {
					$this->data['blockviewed_images_' . $i . '_image'] = $this->data['no_image'];
				}
				
				
				$this->load->model('catalog/product');
				
				if (isset($this->request->post['blockviewed_product_' . $i])) {
					${'products' . $i} = explode(',', $this->request->post['blockviewed_product_' . $i]);
					} else {		
					${'products' . $i} = explode(',', $this->config->get('blockviewed_product_' . $i));
				}
				
				$this->data['products' . $i] = array();
				
				unset($product_id);
				foreach (${'products' . $i} as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					
					if ($product_info) {
						$this->data['products' . $i][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
						);
					}
				}	
			}
			
			$this->data['modules'] = array();
			
			if (isset($this->request->post['viewed_module'])) {
				$this->data['modules'] = $this->request->post['viewed_module'];
				} elseif ($this->config->get('viewed_module')) { 
				$this->data['modules'] = $this->config->get('viewed_module');
			}		
  
			$this->data['layouts'] = $layouts;
			
			$this->template = 'module/viewed.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function validate() {
			if (!$this->user->hasPermission('modify', 'module/viewed')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}	
		}
	}
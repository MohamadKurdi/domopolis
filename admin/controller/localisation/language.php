<?php 
	class ControllerLocalisationLanguage extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('localisation/language');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/language');
			
			$this->getList();
		}
		
		public function createlanguagemanual() {
			$language_id = 6;
			
			// Attribute 
			
			echo '[L] Атрибуты ' . PHP_EOL; 
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $attribute) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "'");
			}
			
			echo '[L] Группы атрибутов ' . PHP_EOL; 
			
			// Attribute Group
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $attribute_group) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "'");
			}
			
			$this->cache->delete('attribute');
			
			echo '[L] Баннер ' . PHP_EOL;
			
			// Banner
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $banner_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image['banner_image_id'] . "', banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape('') . "'");
			}
			
			$this->cache->delete('banner');
			
			echo '[L] Категория ' . PHP_EOL;
			
			// Category
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $category) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "', meta_description = '" . $this->db->escape('') . "', meta_keyword = '" . $this->db->escape('') . "', description = '" . $this->db->escape('') . "'");
			}
			
			$this->cache->delete('category');
			
			echo '[L] Кастомер груп ' . PHP_EOL;
			
			// Customer Group
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $customer_group) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($customer_group['name']) . "', description = '" . $this->db->escape($customer_group['description']) . "'");
			}
			
			// Download
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
			}
			
			// Filter
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $filter) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter['filter_group_id'] . "', name = '" . $this->db->escape($filter['name']) . "'");
			}
			
			// Filter Group
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $filter_group) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group['filter_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($filter_group['name']) . "'");
			}
			
			echo '[L] Инфо ' . PHP_EOL;
			
			// Information
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $information) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape('') . "', description = '" . $this->db->escape('') . "'");
			}		
			
			$this->cache->delete('information');
			
			// Length
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $length) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($length['title']) . "', unit = '" . $this->db->escape($length['unit']) . "'");
			}	
			
			$this->cache->delete('length_class');
			
			// Option 
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "'");
			}
			
			// Option Value
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $option_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->db->escape('') . "'");
			}
			
			// Order Status
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $order_status) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($order_status['name']) . "'");
			}	
			
			$this->cache->delete('order_status');
			
			echo '[L] Товар ' . PHP_EOL;
			
			// Product
			$total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			$total_products = $total->row['total'];
			
			$index = (int)($total_products / 500);	
			for ($i=0; $i<=$index; $i++){
				$start = $i * 500;
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT ". $start .", 500");
				
				foreach ($query->rows as $product) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape('') . "', meta_description = '" . $this->db->escape('') . "', meta_keyword = '" . $this->db->escape('') . "', description = '" . $this->db->escape('') . "', tag = '" . $this->db->escape('') . "'");
				}
			}
			
			$this->cache->delete('product');
			
			echo '[L] Значения атрибутов ' . PHP_EOL;
			
			// Product Attribute 
			$total = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			$total_products = $total->row['total'];
			
			$total_products = $total->row['total'];
			
			$index = (int)($total_products / 500);	
			for ($i=0; $i<=$index; $i++){
				$start = $i * 500;
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT ". $start .", 500");
				
				foreach ($query->rows as $product_attribute) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape('') . "'");
				}
			}
			
			// Return Action 
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $return_action) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_action['name']) . "'");
			}
			
			// Return Reason 
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $return_reason) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_reason['name']) . "'");
			}
			
			// Return Status
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $return_status) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_status['name']) . "'");
			}
			
			// Stock Status
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $stock_status) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($stock_status['name']) . "'");
			}
			
			$this->cache->delete('stock_status');
			
			// Voucher Theme
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $voucher_theme) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($voucher_theme['name']) . "'");
			}
			
			// Weight Class
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $weight_class) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($weight_class['title']) . "', unit = '" . $this->db->escape($weight_class['unit']) . "'");
			}
			
			$this->cache->delete('weight_class');
			
			// Profile
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "profile_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			foreach ($query->rows as $profile) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "profile_description SET profile_id = '" . (int)$profile['profile_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($profile['name']));
			}		
		}
		
		public function insert() {
			$this->language->load('localisation/language');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/language');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_language->addLanguage($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('localisation/language');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/language');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_localisation_language->editLanguage($this->request->get['language_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('localisation/language');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('localisation/language');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $language_id) {
					$this->model_localisation_language->deleteLanguage($language_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'name';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('localisation/language/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('localisation/language/delete', 'token=' . $this->session->data['token'] . $url);
			
			$this->data['languages'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$language_total = $this->model_localisation_language->getTotalLanguages();
			
			$results = $this->model_localisation_language->getLanguages($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/language/update', 'token=' . $this->session->data['token'] . '&language_id=' . $result['language_id'] . $url, 'SSL')
				);
				
				$this->data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
				'code'        => $result['code'],
				'urlcode'     => $result['urlcode'],
				'hreflang'    => $result['hreflang'],
				'sort_order'  => $result['sort_order'],
				'status'	  => $result['status'],
				'front'	  	  => $result['front'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['language_id'], $this->request->post['selected']),
				'action'      => $action	
				);		
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_code'] = $this->language->get('column_code');
			$this->data['column_sort_order'] = $this->language->get('column_sort_order');
			$this->data['column_action'] = $this->language->get('column_action');
			
			$this->data['button_insert'] = $this->language->get('button_insert');
			$this->data['button_delete'] = $this->language->get('button_delete');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$url = '';
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['sort_name'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
			$this->data['sort_code'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . '&sort=code' . $url);
			$this->data['sort_sort_order'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $language_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'localisation/language_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_code'] = $this->language->get('entry_code');
			$this->data['entry_locale'] = $this->language->get('entry_locale');
			$this->data['entry_image'] = $this->language->get('entry_image');
			$this->data['entry_directory'] = $this->language->get('entry_directory');
			$this->data['entry_filename'] = $this->language->get('entry_filename');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_status'] = $this->language->get('entry_status');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$this->data['error_name'] = $this->error['name'];
				} else {
				$this->data['error_name'] = '';
			}
			
			if (isset($this->error['code'])) {
				$this->data['error_code'] = $this->error['code'];
				} else {
				$this->data['error_code'] = '';
			}
			
			if (isset($this->error['locale'])) {
				$this->data['error_locale'] = $this->error['locale'];
				} else {
				$this->data['error_locale'] = '';
			}		
			
			if (isset($this->error['image'])) {
				$this->data['error_image'] = $this->error['image'];
				} else {
				$this->data['error_image'] = '';
			}	
			
			if (isset($this->error['directory'])) {
				$this->data['error_directory'] = $this->error['directory'];
				} else {
				$this->data['error_directory'] = '';
			}	
			
			if (isset($this->error['filename'])) {
				$this->data['error_filename'] = $this->error['filename'];
				} else {
				$this->data['error_filename'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),      		
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url),      		
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['language_id'])) {
				$this->data['action'] = $this->url->link('localisation/language/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('localisation/language/update', 'token=' . $this->session->data['token'] . '&language_id=' . $this->request->get['language_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['language_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$language_info = $this->model_localisation_language->getLanguage($this->request->get['language_id']);
			}
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} elseif (!empty($language_info)) {
				$this->data['name'] = $language_info['name'];
				} else {
				$this->data['name'] = '';
			}
			
			if (isset($this->request->post['code'])) {
				$this->data['code'] = $this->request->post['code'];
				} elseif (!empty($language_info)) {
				$this->data['code'] = $language_info['code'];
				} else {
				$this->data['code'] = '';
			}
			
			if (isset($this->request->post['hreflang'])) {
				$this->data['hreflang'] = $this->request->post['hreflang'];
				} elseif (!empty($language_info)) {
				$this->data['hreflang'] = $language_info['hreflang'];
				} else {
				$this->data['hreflang'] = '';
			}

			if (isset($this->request->post['switch'])) {
				$this->data['switch'] = $this->request->post['switch'];
				} elseif (!empty($language_info)) {
				$this->data['switch'] = $language_info['switch'];
				} else {
				$this->data['switch'] = '';
			}
			
			if (isset($this->request->post['urlcode'])) {
				$this->data['urlcode'] = $this->request->post['urlcode'];
				} elseif (!empty($language_info)) {
				$this->data['urlcode'] = $language_info['urlcode'];
				} else {
				$this->data['urlcode'] = '';
			}
			
			if (isset($this->request->post['locale'])) {
				$this->data['locale'] = $this->request->post['locale'];
				} elseif (!empty($language_info)) {
				$this->data['locale'] = $language_info['locale'];
				} else {
				$this->data['locale'] = '';
			}
			
			if (isset($this->request->post['image'])) {
				$this->data['image'] = $this->request->post['image'];
				} elseif (!empty($language_info)) {
				$this->data['image'] = $language_info['image'];
				} else {
				$this->data['image'] = '';
			}
			
			if (isset($this->request->post['directory'])) {
				$this->data['directory'] = $this->request->post['directory'];
				} elseif (!empty($language_info)) {
				$this->data['directory'] = $language_info['directory'];
				} else {
				$this->data['directory'] = '';
			}
			
			if (isset($this->request->post['filename'])) {
				$this->data['filename'] = $this->request->post['filename'];
				} elseif (!empty($language_info)) {
				$this->data['filename'] = $language_info['filename'];
				} else {
				$this->data['filename'] = '';
			}
			
			if (isset($this->request->post['sort_order'])) {
				$this->data['sort_order'] = $this->request->post['sort_order'];
				} elseif (!empty($language_info)) {
				$this->data['sort_order'] = $language_info['sort_order'];
				} else {
				$this->data['sort_order'] = '';
			}
			
			if (isset($this->request->post['fasttranslate'])) {
				$this->data['fasttranslate'] = $this->request->post['fasttranslate'];
				} elseif (!empty($language_info)) {
				$this->data['fasttranslate'] = $language_info['fasttranslate'];
				} else {
				$this->data['fasttranslate'] = '';
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (!empty($language_info)) {
				$this->data['status'] = $language_info['status'];
				} else {
				$this->data['status'] = 1;
			}

			if (isset($this->request->post['front'])) {
				$this->data['front'] = $this->request->post['front'];
				} elseif (!empty($language_info)) {
				$this->data['front'] = $language_info['front'];
				} else {
				$this->data['front'] = 1;
			}
			
			$this->template = 'localisation/language_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'localisation/language')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
				$this->error['name'] = $this->language->get('error_name');
			}
			
			if (utf8_strlen($this->request->post['code']) < 2) {
				$this->error['code'] = $this->language->get('error_code');
			}
			
			if (!$this->request->post['locale']) {
				$this->error['locale'] = $this->language->get('error_locale');
			}
			
			if (!$this->request->post['directory']) { 
				$this->error['directory'] = $this->language->get('error_directory'); 
			}
			
			if (!$this->request->post['filename']) {
				$this->error['filename'] = $this->language->get('error_filename');
			}
			
			if ((utf8_strlen($this->request->post['image']) < 3) || (utf8_strlen($this->request->post['image']) > 32)) {
				$this->error['image'] = $this->language->get('error_image');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'localisation/language')) {
				$this->error['warning'] = $this->language->get('error_permission');
			} 
			
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			
			foreach ($this->request->post['selected'] as $language_id) {
				$language_info = $this->model_localisation_language->getLanguage($language_id);
				
				if ($language_info) {
					if ($this->config->get('config_language') == $language_info['code']) {
						$this->error['warning'] = $this->language->get('error_default');
					}
					
					if ($this->config->get('config_admin_language') == $language_info['code']) {
						$this->error['warning'] = $this->language->get('error_admin');
					}	
					
					$store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);
					
					if ($store_total) {
						$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
					}
				}
				
				$order_total = $this->model_sale_order->getTotalOrdersByLanguageId($language_id);
				
				if ($order_total) {
					$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
				}		
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}	
	}	
<?php
	class ControllerCatalogOCFilter extends Controller {
		private $error = array();
		private $get = array(
		'filter_name' => null,
		'filter_type' => null,
		'filter_status' => null,
		'filter_category_id' => null,
		'page' => 1,
		'sort' => 'ood.name',
		'order' => 'ASC'
		);
		
		public function index() {
			$this->data = array_merge($this->data, $this->load->language('catalog/ocfilter'));
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter');
			
			$this->data['language_id'] = $this->config->get('config_language_id');
			
			$this->getList();
		}
		
		public function insert() {
			$this->data = array_merge($this->data, $this->load->language('catalog/ocfilter'));
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ocfilter->addOption($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				foreach ($this->get as $key => $value) {
					if (isset($this->request->get[$key])) $url .= '&' . $key .'=' . $this->request->get[$key];
				}
				
				$this->redirect($this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->data = array_merge($this->data, $this->load->language('catalog/ocfilter'));
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ocfilter->editOption($this->request->get['option_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				foreach ($this->get as $key => $value) {
					if (isset($this->request->get[$key])) $url .= '&' . $key .'=' . $this->request->get[$key];
				}
				
				$this->redirect($this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->data = array_merge($this->data, $this->load->language('catalog/ocfilter'));
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter');
			
			if (isset($this->request->post['selected'])) {
				
				foreach ($this->request->post['selected'] as $option_id) {
					$this->model_catalog_ocfilter->deleteOption($option_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				foreach ($this->get as $key => $value) {
					if (isset($this->request->get[$key])) $url .= '&' . $key .'=' . $this->request->get[$key];
				}
				
				$this->redirect($this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		public function page() {
			$this->load->language('catalog/ocfilter');
			
			$this->document->setTitle('SEO Страницы OCFilter');
			
			$this->load->model('catalog/ocfilter');
			
			$this->getPageList();
		}
		
		public function insertPage() {
			$this->load->language('catalog/ocfilter');
			
			$this->document->setTitle('SEO Страницы OCFilter');
			
			$this->load->model('catalog/ocfilter');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				$this->model_catalog_ocfilter->addPage($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				foreach ($this->get as $key => $value) {
					if (isset($this->request->get[$key])) {
						$url .= '&' . $key . '=' . $this->request->get[$key];
					}
				}
				
				$this->redirect($this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getPageForm();
		}
		
		public function updatePage() {
			$this->load->language('catalog/ocfilter');
			
			$this->document->setTitle('SEO Страницы OCFilter');
			
			$this->load->model('catalog/ocfilter');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ocfilter->editPage($this->request->get['ocfilter_page_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				foreach ($this->get as $key => $value) {
					if (isset($this->request->get[$key])) {
						$url .= '&' . $key . '=' . $this->request->get[$key];
					}
				}
				
				$this->redirect($this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getPageForm();
		}
		
		public function deletePage() {
			$this->load->language('catalog/ocfilter');
			
			$this->document->setTitle('SEO Страницы OCFilter');
			
			$this->load->model('catalog/ocfilter');
			
			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $ocfilter_page_id) {
					$this->model_catalog_ocfilter->deletePage($ocfilter_page_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				foreach ($this->get as $key => $value) {
					if (isset($this->request->get[$key])) {
						$url .= '&' . $key . '=' . $this->request->get[$key];
					}
				}
				
				$this->redirect($this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		private function getList() {
			$this->document->addStyle('view/stylesheet/ocfilter/ocfilter.css');
			$this->document->addScript('view/javascript/ocfilter/ocfilter.js');
			
			foreach ($this->get as $key => $value) {
				if (isset($this->request->get[$key])) {
					$$key = $this->request->get[$key];
					} else {
					$$key = $value;
				}
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
			);
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
			);
			
			$url = '';
			
			foreach ($this->get as $key => $value) {
				if (isset($this->request->get[$key])) $url .= '&' . $key .'=' . $this->request->get[$key];
			}
			
			$this->data['insert'] = $this->url->link('catalog/ocfilter/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('catalog/ocfilter/delete', 'token=' . $this->session->data['token'] . $url);
			$this->data['ocfilter_page']  = $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token']);
			
			$this->data['options'] = array();
			
			# Set arguments data array
			$data = array();
			
			foreach ($this->get as $key => $value) {
				$data[$key] = $$key;
			}
			
			$data['start'] = ($page - 1) * 25;
			$data['limit'] = 25;
			
			$option_total = $this->model_catalog_ocfilter->getTotalOptions($data);
			
			$results = $this->model_catalog_ocfilter->getOptions($data);
			
			$visible = 5;
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/ocfilter/update', 'token=' . $this->session->data['token'] . '&option_id=' . $result['option_id'] . $url, 'SSL')
				);
				
				$values = array();
				foreach ($result['values'] as $value) { $values[] = $value['name']; }
				
				if ($values) {
					if (count($values) > $visible) {
						$values = array_slice($values, 0, $visible);
						
						$values[$visible-1] .= $result['postfix'] . sprintf($this->language->get('text_and_more'), (count($result['values']) - $visible));
						} else {
						$values[count($values)-1] .= $result['postfix'];
					}
				}
				
				$categories = array();
				foreach ($result['categories'] as $category) { $categories[] = $category['name']; }
				
				if (count($categories) > $visible) {
					$categories = array_slice($categories, 0, $visible);
					
					$categories[$visible-1] .= sprintf($this->language->get('text_and_more'), (count($result['categories']) - $visible));
				}
				
				$this->data['options'][] = array(
				'option_id'     => $result['option_id'],
				'name'          => $result['name'],
				'type'          => $result['type'],
				'sort_order'    => $result['sort_order'],
				'selected'      => isset($this->request->post['selected']) && in_array($result['option_id'], $this->request->post['selected']),
				'values'        => html_entity_decode(implode(' &bull; ', $values), ENT_QUOTES, 'UTF-8'),
				'categories'    => implode(' &bull; ', $categories),
				'status'        => $result['status'],
				'selectbox'     => $result['selectbox'],
				'action'        => $action
				);
			}
			
			$this->data['token'] = $this->session->data['token'];
			
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
			
			foreach ($this->get as $key => $value) {
				if (isset($this->request->get[$key]) && $key != 'sort' && $key != 'order') { $url .= '&' . $key .'=' . $this->request->get[$key]; }
			}
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			$this->data['sort_name'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . '&sort=ood.name' . $url);
			$this->data['sort_type'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . '&sort=oo.type' . $url);
			$this->data['sort_category_id'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . '&sort=oo2c.category_id' . $url);
			$this->data['sort_status'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . '&sort=oo.status' . $url);
			$this->data['sort_order'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . '&sort=oo.sort_order' . $url);
			
			$url = '';
			
			foreach ($this->get as $key => $value) {
				if (isset($this->request->get[$key]) && $key != 'page') { $url .= '&' . $key .'=' . $this->request->get[$key]; }
			}
			
			$pagination = new Pagination();
			$pagination->total = $option_total;
			$pagination->page = $page;
			$pagination->limit = 25;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			# Set variables
			
			foreach ($this->get as $key => $value) {
				$this->data[$key] = $$key;
			}
			
			$this->data['types'] = array(
			'checkbox' 				=> $this->language->get('text_checkbox'),
			'radio'    				=> $this->language->get('text_radio'),
			'select'   				=> $this->language->get('text_select'),
			'slide'   				=> $this->language->get('text_slide'),
			'slide_dual'  		=> $this->language->get('text_slide_dual'),
			'text'            => $this->language->get('text_text')
			);
			
			# Set filter variables for js
			$this->data['filter_get'] = array();
			
			foreach ($this->get as $key => $value) {
				if ($key != 'sort' && $key != 'order' && $key != 'page') {
					$this->data['filter_get'][] = $key;
				}
			}
			
			$this->data['categories'] = $this->model_catalog_ocfilter->getCategories(0);
			
			$this->template = 'catalog/ocfilter_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function getForm() {
			$this->document->addStyle('view/stylesheet/ocfilter/ocfilter.css');
			$this->document->addScript('view/javascript/ocfilter/ocfilter.js');
			
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
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
			);
			
			$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
			);
			
			$url = '';
			
			foreach ($this->get as $key => $value) {
				if (isset($this->request->get[$key])) $url .= '&' . $key .'=' . $this->request->get[$key];
			}
			
			if (!isset($this->request->get['option_id'])) {
				$this->data['action'] = $this->url->link('catalog/ocfilter/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('catalog/ocfilter/update', 'token=' . $this->session->data['token'] . '&option_id=' . $this->request->get['option_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'] . $url);
			
			$this->load->model('localisation/language');
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->get['option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$option_info = $this->model_catalog_ocfilter->getOption($this->request->get['option_id']);
			}
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} elseif (isset($this->request->get['option_id'])) {
				$option_description = $this->model_catalog_ocfilter->getOptionDescriptions($this->request->get['option_id']);
				
				$this->data['name'] = $option_description;
				
				$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('catalog/ocfilter/update', 'token=' . $this->session->data['token'] . '&option_id=' . $this->request->get['option_id']),
				'text'      => $option_description[$this->config->get('config_language_id')]['name'],
				'separator' => ' :: '
				);
				} else {
				$this->data['name'] = array();
			}
			
			if (isset($this->request->post['option_values'])) {
				$this->data['ocfilter_option_values'] = $this->request->post['option_values'];
				} elseif (isset($this->request->get['option_id'])) {
				$this->data['ocfilter_option_values'] = $this->model_catalog_ocfilter->getOptionValues($this->request->get['option_id']);
				} else {
				$this->data['ocfilter_option_values'] = array();
			}
			
			$this->load->model('tool/image');
			
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 22, 22);
			
			foreach ($this->data['ocfilter_option_values'] as $key => $value) {
				$this->data['ocfilter_option_values'][$key]['thumb'] = $this->model_tool_image->resize($value['image'], 22, 22);
			}
			
			if (isset($this->request->post['sort_order'])) {
				$this->data['sort_order'] = $this->request->post['sort_order'];
				} elseif (isset($option_info)) {
				$this->data['sort_order'] = $option_info['sort_order'];
				} else {
				$this->data['sort_order'] = 0;
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (isset($option_info)) {
				$this->data['status'] = $option_info['status'];
				} else {
				$this->data['status'] = 1;
			}
			
			$this->data['types'] = array(
			'checkbox' 				=> $this->language->get('text_checkbox'),
			'radio'    				=> $this->language->get('text_radio'),
			'select'   				=> $this->language->get('text_select'),
			'slide'   				=> $this->language->get('text_slide'),
			'slide_dual'  		=> $this->language->get('text_slide_dual'),
			'text'            => $this->language->get('text_text')
			);
			
			if (isset($this->request->post['type'])) {
				$this->data['type'] = $this->request->post['type'];
				} elseif (isset($option_info)) {
				$this->data['type'] = $option_info['type'];
				} else {
				$this->data['type'] = '';
			}
			
			if (isset($this->request->post['grouping'])) {
				$this->data['grouping'] = $this->request->post['grouping'];
				} elseif (isset($option_info)) {
				$this->data['grouping'] = $option_info['grouping'];
				} else {
				$this->data['grouping'] = '';
			}
			
			if (isset($this->request->post['selectbox'])) {
				$this->data['selectbox'] = $this->request->post['selectbox'];
				} elseif (isset($option_info)) {
				$this->data['selectbox'] = $option_info['selectbox'];
				} else {
				$this->data['selectbox'] = 0;
			}
			
			if (isset($this->request->post['color'])) {
				$this->data['color'] = $this->request->post['color'];
				} elseif (isset($option_info)) {
				$this->data['color'] = $option_info['color'];
				} else {
				$this->data['color'] = 0;
			}
			
			if (isset($this->request->post['image'])) {
				$this->data['image'] = $this->request->post['image'];
				} elseif (isset($option_info)) {
				$this->data['image'] = $option_info['image'];
				} else {
				$this->data['image'] = 0;
			}
			
			$this->data['categories'] = $this->model_catalog_ocfilter->getCategories(0);
			
			if (isset($this->request->post['option_categories'])) {
				$this->data['option_categories'] = $this->request->post['option_categories'];
				} elseif (isset($option_info)) {
				$this->data['option_categories'] = $this->model_catalog_ocfilter->getOptionCategories($this->request->get['option_id']);
				} else {
				$this->data['option_categories'] = array();
			}
			
			$this->load->model('setting/store');
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($this->request->post['option_store'])) {
				$this->data['option_store'] = $this->request->post['option_store'];
				} elseif (isset($this->request->get['option_id'])) {
				$this->data['option_store'] = $this->model_catalog_ocfilter->getOptionStores($this->request->get['option_id']);
				} else {
				$this->data['option_store'] = array(0);
			}
			
			$this->template = 'catalog/ocfilter_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function getPageList() {
			$this->data = $this->load->language('catalog/ocfilter');
			
			foreach ($this->get as $key => $value) {
				if (isset($this->request->get[$key])) {
					$$key = $this->request->get[$key];
					} else {
					$$key = $value;
				}
			}
			
			if ($order == 'ood.name') {
				$order = 'op.title';
			}
			
			$this->data['breadcrumbs']   = array();
			
			$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text' => $this->language->get('text_home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token']),
			'text' => 'Фильтр товаров OCFilter',
			'separator' => ' :: '
			);
			
			$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token']),
			'text' => 'SEO Страницы OCFilter',
			'separator' => ' :: '
			);
			
			$this->data['insert']  = $this->url->link('catalog/ocfilter/insertPage', 'token=' . $this->session->data['token']);
			$this->data['delete']  = $this->url->link('catalog/ocfilter/deletePage', 'token=' . $this->session->data['token']);
			$this->data['cancel'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token']);
			
			$this->data['pages'] = array();
			
			$filter_data['start'] = ($page - 1) * 20;
			$filter_data['limit'] = 20;
			
			$pages_total         = $this->model_catalog_ocfilter->getTotalPages();
			$results              = $this->model_catalog_ocfilter->getPages($filter_data);
			
			foreach ($results as $result) {
				$action   = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/ocfilter/updatePage', 'token=' . $this->session->data['token'] . '&ocfilter_page_id=' . $result['ocfilter_page_id'], 'SSL')
				);
				
				$this->data['pages'][] = array(
				'ocfilter_page_id' => $result['ocfilter_page_id'],
				'title' => $result['title'],
				'category' => $result['category'],
				'ocfilter_params' => $result['ocfilter_params'],
				'selected' => isset($this->request->post['selected']) && in_array($result['option_id'], $this->request->post['selected']),
				'status' => $result['status'],
				'action' => $action
				);
			}
			
			$this->data['token'] = $this->session->data['token'];
			
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
			
			$pagination         = new Pagination();
			$pagination->total  = $pages_total;
			$pagination->page   = $page;
			$pagination->limit  = 20;
			$pagination->text   = $this->language->get('text_pagination');
			$pagination->url    = $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token'] . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->template = 'catalog/ocfilter_page_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		private function getPageForm() {
			$this->data = $this->load->language('catalog/ocfilter');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			$this->data['breadcrumbs']   = array();
			
			$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text' => $this->language->get('text_home'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token']),
			'text' => 'Фильтр товаров OCFilter',
			'separator' => ' :: '
			);
			
			$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token']),
			'text' => 'SEO Страницы OCFilter',
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['ocfilter_page_id'])) {
				$this->data['action'] = $this->url->link('catalog/ocfilter/insertPage', 'token=' . $this->session->data['token']);
				} else {
				$this->data['action'] = $this->url->link('catalog/ocfilter/updatePage', 'token=' . $this->session->data['token'] . '&ocfilter_page_id=' . $this->request->get['ocfilter_page_id']);
			}
			
			$this->data['cancel'] = $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token']);
			
			if (isset($this->request->get['ocfilter_page_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$page_info = $this->model_catalog_ocfilter->getPage($this->request->get['ocfilter_page_id']);
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (isset($page_info)) {
				$this->data['status'] = $page_info['status'];
				} else {
				$this->data['status'] = 1;
			}
			
			if (isset($this->request->post['ocfilter_params'])) {
				$this->data['ocfilter_params'] = $this->request->post['ocfilter_params'];
				} elseif (isset($page_info)) {
				$this->data['ocfilter_params'] = $page_info['ocfilter_params'];
				} else {
				$this->data['ocfilter_params'] = '';
			}
			
			if (isset($this->request->post['meta_keyword'])) {
				$this->data['meta_keyword'] = $this->request->post['meta_keyword'];
				} elseif (isset($page_info)) {
				$this->data['meta_keyword'] = $page_info['meta_keyword'];
				} else {
				$this->data['meta_keyword'] = '';
			}
			
			if (isset($this->request->post['meta_description'])) {
				$this->data['meta_description'] = $this->request->post['meta_description'];
				} elseif (isset($page_info)) {
				$this->data['meta_description'] = $page_info['meta_description'];
				} else {
				$this->data['meta_description'] = '';
			}
			
			if (isset($this->request->post['meta_title'])) {
				$this->data['meta_title'] = $this->request->post['meta_title'];
				} elseif (isset($page_info)) {
				$this->data['meta_title'] = $page_info['meta_title'];
				} else {
				$this->data['meta_title'] = '';
			}
			
			if (isset($this->request->post['title'])) {
				$this->data['title'] = $this->request->post['title'];
				} elseif (isset($page_info)) {
				$this->data['title'] = $page_info['title'];
				} else {
				$this->data['title'] = '';
			}
			
			if (isset($this->request->post['keyword'])) {
				$this->data['keyword'] = $this->request->post['keyword'];
				} elseif (isset($page_info)) {
				$this->data['keyword'] = $page_info['keyword'];
				} else {
				$this->data['keyword'] = '';
			}
			
			if (isset($this->request->post['description'])) {
				$this->data['description'] = $this->request->post['description'];
				} elseif (isset($page_info)) {
				$this->data['description'] = $page_info['description'];
				} else {
				$this->data['description'] = '';
			}
			
			$this->data['categories'] = $this->model_catalog_ocfilter->getCategories(0);
			
			if (isset($this->request->post['category_id'])) {
				$this->data['category_id'] = $this->request->post['category_id'];
				} elseif (isset($page_info)) {
				$this->data['category_id'] = $page_info['category_id'];
				} else {
				$this->data['category_id'] = 0;
			}
			
			if (isset($this->request->post['category'])) {
				$this->data['category'] = $this->request->post['category'];
				} elseif (isset($page_info)) {
				$this->data['category'] = $page_info['category'];
				} else {
				$this->data['category'] = '';
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'catalog/ocfilter_page_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function callback() {
			$json = array();
			
			$this->load->language('catalog/ocfilter');
			$this->load->model('catalog/ocfilter');
			$this->load->model('localisation/language');
			
			$languages = $this->model_localisation_language->getLanguages();
			
			$json['message'] = '';
			$json['options'] = array();
			
			if (isset($this->request->get['category_id'])) {
				if (isset($this->request->get['product_id'])) {
					$product_values = $this->model_catalog_ocfilter->getProductValues($this->request->get['product_id']);
					} else {
					$product_values = array();
				}
				
				if ($results = $this->model_catalog_ocfilter->getOptionsByCategoryId($this->request->get['category_id'])) {
					foreach(array_values($results) as $key => $option) {
						$values = array();
						
						$description = array();
						
						foreach ($languages as $language) {
							$description[$language['language_id']] = array(
							'description' => ''
							);
						}
						
						if ($option['type'] != 'slide' && $option['type'] != 'slide_dual' && $option['type'] != 'text') {
							foreach ($option['values'] as $_key => $value) {
								$values[$_key] = array(
								'value_id'  			=> (string)$value['value_id'],
								'name'      			=> $value['name'],
								'description' 		=> $description,
								'selected'  			=> (bool)false
								);
								
								if (isset($product_values[$option['option_id']][$value['value_id']])) {
									$values[$_key]['selected'] = (bool)true;
									$values[$_key]['description'] = $product_values[$option['option_id']][$value['value_id']]['description'];
								}
							}
						}
						
						$json['options'][$key] = array(
						'option_id' 			=> (int)$option['option_id'],
						'name'      			=> $option['name'],
						'postfix'   			=> $option['postfix'],
						'status'    			=> (int)$option['status'],
						'type'      			=> $option['type'],
						'slide_value_min' => '',
						'slide_value_max' => '',
						'description'     => $description,
						'values'    			=> $values
						);
						
						if (isset($product_values[$option['option_id']][0])) {
							$product_value = array_shift($product_values[$option['option_id']]);
							
							$json['options'][$key]['description'] = $product_value['description'];
							$json['options'][$key]['slide_value_min'] = ((float)$product_value['slide_value_min'] ? preg_replace('!(0+?$)|(\.0+?$)!', '', $product_value['slide_value_min']) : '');
							$json['options'][$key]['slide_value_max'] = ((float)$product_value['slide_value_max'] ? preg_replace('!(0+?$)|(\.0+?$)!', '', $product_value['slide_value_max']) : '');
						}
					}
					} else {
					$json['message'] = $this->language->get('text_no_options');
				}
				} else {
				$json['message'] = $this->language->get('text_select_category');
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function edit() {
			$json = array();
			
			if (isset($this->request->post['option_id']) && isset($this->request->post['field']) && isset($this->request->post['value'])) {
				if ($this->request->post['field'] == 'name') {
					$this->db->query("UPDATE " . DB_PREFIX . "ocfilter_option_description SET name = '" . $this->db->escape(urldecode($this->request->post['value'])) . "' WHERE option_id = '" . (int)$this->request->post['option_id'] . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
					} else {
					$this->db->query("UPDATE " . DB_PREFIX . "ocfilter_option SET `" . $this->db->escape($this->request->post['field']) . "` = '" . $this->db->escape($this->request->post['value']) . "' WHERE option_id = '" . (int)$this->request->post['option_id'] . "'");
				}
				
				$this->cache->delete('ocfilter');
				
				$json['status'] = true;
				} else {
				$json['status'] = false;
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		private function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/ocfilter')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				if (!isset($this->error['warning'])) {
					$this->error['warning'] = $this->language->get('error_required_data');
				}
				
				return false;
			}
		}
	}
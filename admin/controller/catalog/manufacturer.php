<?php    
class ControllerCatalogManufacturer extends Controller { 
	private $error = array();
	
	public function index() {
		$this->language->load('catalog/manufacturer');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/manufacturer');
		
		$this->getList();
	}
	
	public function insert() {
		$this->language->load('catalog/manufacturer');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/manufacturer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_manufacturer->addManufacturer($this->request->post);
			
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
			
			$this->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();
	}
	
	public function update() {
		$this->language->load('catalog/manufacturer');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/manufacturer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_manufacturer->editManufacturer($this->request->get['manufacturer_id'], $this->request->post);
			
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
			
			if(isset($this->request->post['apply']) and $this->request->post['apply'])
				$this->redirect($this->url->link('catalog/manufacturer/update', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url, 'SSL'));
			else
				$this->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->language->load('catalog/manufacturer');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/manufacturer');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $manufacturer_id) {
				$this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
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
			
			$this->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		
		$this->data['insert'] = $this->url->link('catalog/manufacturer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/manufacturer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		
		$this->data['manufacturers'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();
		$results = $this->model_catalog_manufacturer->getManufacturers($data);
		
		$this->load->model('localisation/language');
		$this->load->model('tool/image');
		$this->load->model('setting/store');
		
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/manufacturer/update', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $result['manufacturer_id'] . $url, 'SSL')
			);
			
			if ($result['image']){
				$image = $this->model_tool_image->resize($result['image'], 50, 50);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 50, 50);
			}

			$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'descriptions'	  => $this->model_catalog_manufacturer->getManufacturerDescriptions($result['manufacturer_id']),
				'seo_urls'	  	  => $this->model_catalog_manufacturer->getKeyWords($result['manufacturer_id']),
				'stores'		  => $this->model_catalog_manufacturer->getManufacturerStores($result['manufacturer_id']),
				'total_products'  => $this->model_catalog_manufacturer->getTotalProductsByManufacturer($result['manufacturer_id']),
				'name'            => $result['name'],
				'image'			  => $image,
				'menu_brand'      => $result['menu_brand'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['manufacturer_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
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
		
		$this->data['sort_name'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $manufacturer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/manufacturer_list.tpl';
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
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['tab_design'] = $this->language->get('tab_design');
		
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);
		
		if (!isset($this->request->get['manufacturer_id'])) {
			$this->data['action'] = $this->url->link('catalog/manufacturer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/manufacturer/update', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['manufacturer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
		}
		
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['manufacturer_description'])) {
			$this->data['manufacturer_description'] = $this->request->post['manufacturer_description'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->data['manufacturer_description'] = $this->model_catalog_manufacturer->getManufacturerDescriptions($this->request->get['manufacturer_id']);
		} else {
			$this->data['manufacturer_description'] = array();
		}
		
		$this->load->model('kp/reward');
		if (isset($this->request->post['reward'])) {
			$this->data['rewards'] = $this->request->post['reward'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->data['rewards'] = $this->model_kp_reward->getEntityRewards($this->request->get['manufacturer_id'], 'm');
		} else {
			$this->data['rewards'] = array();
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['name'] = $manufacturer_info['name'];
		} else {	
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['manufacturer_page_content'])) {
			$this->data['manufacturer_page_content'] = $this->request->post['manufacturer_page_content'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->data['manufacturer_page_content'] = $this->model_catalog_manufacturer->getManufacturerPageContent($this->request->get['manufacturer_id']);
		} else {
			$this->data['manufacturer_page_content'] = array();
		}
		
		if (isset($this->request->post['priceva_enable'])) {
			$this->data['priceva_enable'] = $this->request->post['priceva_enable'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['priceva_enable'] = $manufacturer_info['priceva_enable'];
		} else {
			$this->data['priceva_enable'] = 0;
		}
		
		if (isset($this->request->post['priceva_feed'])) {
			$this->data['priceva_feed'] = $this->request->post['priceva_feed'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['priceva_feed'] = $manufacturer_info['priceva_feed'];
		} else {	
			$this->data['priceva_feed'] = '';
		}
		
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/collection');
		$this->load->model('catalog/category');
		
		foreach ($this->data['languages'] as $language){
			if (isset($this->data['manufacturer_page_content'][$language['language_id']])){
				foreach ($this->data['manufacturer_page_content'][$language['language_id']] as &$content){						
					
					if ($content['products']){
						
						$content['real_products'] = array();						
						$p_array = explode(',', $content['products']);						
						foreach ($p_array as $product_id){
							$content['real_products'][] = $this->model_catalog_product->getProduct($product_id);
						}
						
					} else {
						$content['real_products'] = array();
					}
					
					if ($content['collections']) {
						
						$content['real_collections'] = array();						
						$p_array = explode(',', $content['collections']);						
						foreach ($p_array as $product_id){
							$content['real_collections'][] = $this->model_catalog_collection->getCollectionById($product_id);
						}
						
					} else {
						$content['real_collections'] = array();	
					}
					
					if ($content['categories']) {
						
						$content['real_categories'] = array();						
						$p_array = explode(',', $content['categories']);						
						foreach ($p_array as $product_id){
							$content['real_categories'][] = $this->model_catalog_category->getCategory($product_id);
						}
						
					} else {
						$content['real_categories'] = array();	
					}
					
				}									
			}
		}		
		
		if (isset($this->request->post['tip'])) {
			$this->data['tip'] = $this->request->post['tip'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['tip'] = $manufacturer_info['tip'];
		} else {	
			$this->data['tip'] = '';
		}
		
		if (isset($this->request->post['menu_brand'])) {
			$this->data['menu_brand'] = $this->request->post['menu_brand'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['menu_brand'] = $manufacturer_info['menu_brand'];
		} else {
			$this->data['menu_brand'] = 0;
		}
				
		if (isset($this->request->post['show_goods'])) {
			$this->data['show_goods'] = $this->request->post['show_goods'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['show_goods'] = $manufacturer_info['show_goods'];
		} else {
			$this->data['show_goods'] = 0;
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['manufacturer_store'])) {
			$this->data['manufacturer_store'] = $this->request->post['manufacturer_store'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->data['manufacturer_store'] = $this->model_catalog_manufacturer->getManufacturerStores($this->request->get['manufacturer_id']);
		} else {
			$this->data['manufacturer_store'] = array(0);
		}	
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['keyword'] = $this->model_catalog_manufacturer->getKeyWords($this->request->get['manufacturer_id']);
		} else {
			$this->data['keyword']= array();
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['image'] = $manufacturer_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		if (isset($this->request->post['back_image'])) {
			$this->data['back_image'] = $this->request->post['back_image'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['back_image'] = $manufacturer_info['back_image'];
		} else {
			$this->data['back_image'] = '';
		}
		
		if (isset($this->request->post['banner'])) {
			$this->data['banner'] = $this->request->post['banner'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['banner'] = $manufacturer_info['banner'];
		} else {
			$this->data['banner'] = '';
		}
		
		if (isset($this->request->post['banner_width'])) {
			$this->data['banner_width'] = $this->request->post['banner_width'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['banner_width'] = $manufacturer_info['banner_width'];
		} else {
			$this->data['banner_width'] = '';
		}
		
		if (isset($this->request->post['banner_height'])) {
			$this->data['banner_height'] = $this->request->post['banner_height'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['banner_height'] = $manufacturer_info['banner_height'];
		} else {
			$this->data['banner_height'] = '';
		}
		
		if (isset($this->request->post['manufacturer_layout'])) {
			$this->data['manufacturer_layout'] = $this->request->post['manufacturer_layout'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->data['manufacturer_layout'] = $this->model_catalog_manufacturer->getManufacturerLayouts($this->request->get['manufacturer_id']);
		} else {
			$this->data['manufacturer_layout'] = array();
		}
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();		
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($manufacturer_info) && $manufacturer_info['image']) {
			$this->data['thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['back_image'])) {
			$this->data['back_thumb'] = $this->model_tool_image->resize($this->request->post['back_image'], 100, 100);
		} elseif (!empty($manufacturer_info) && $manufacturer_info['back_image']) {
			$this->data['back_thumb'] = $this->model_tool_image->resize($manufacturer_info['back_image'], 100, 100);
		} else {
			$this->data['back_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['banner'])) {
			$this->data['thumb_banner'] = $this->model_tool_image->resize($this->request->post['banner'], 400, 120);
		} elseif (!empty($manufacturer_info) && $manufacturer_info['banner']) {
			$this->data['thumb_banner'] = $this->model_tool_image->resize($manufacturer_info['banner'], 400, 120);
		} else {
			$this->data['thumb_banner'] = $this->model_tool_image->resize('no_image.jpg', 400, 120);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($manufacturer_info)) {
			$this->data['sort_order'] = $manufacturer_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->template = 'catalog/manufacturer_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}  
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (utf8_strlen($this->request->post['name']) < 1 || (utf8_strlen($this->request->post['name']) > 128)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('catalog/product');
		
		foreach ($this->request->post['selected'] as $manufacturer_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);
			
			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}	
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}  
	}
	
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/manufacturer');
			
			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);
			
			$results = $this->model_catalog_manufacturer->getManufacturers($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'manufacturer_id' => $result['manufacturer_id'], 
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}
		
		$sort_order = array();
		
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
		
		$this->response->setOutput(json_encode($json));
	}	
}
?>
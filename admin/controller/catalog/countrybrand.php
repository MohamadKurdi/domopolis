<?
	class ControllerCatalogCountrybrand extends Controller { 
		private $error = array();
		
		public function index() {
			
			$this->language->load('catalog/countrybrand');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/countrybrand');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('catalog/countrybrand');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/countrybrand');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_countrybrand->addCountrybrand($this->request->post);
				if (isset($this->request->post['continue']) && $this->request->post['continue'] == '1') {
					$countrybrand = $this->session->data['new_countrybrand_id'];
					if ($countrybrand_id) {
						unset($this->session->data['new_countrybrand_id']);
						$this->session->data['success'] = $this->language->get('text_success');	
						$this->redirect($this->url->link('catalog/countrybrand/update', 'token='.$this->session->data['token'].'&countrybrand_id='.$countrybrand_id, 'SSL'));
					}
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
				
				$this->redirect($this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		
		public function update() {
			$this->language->load('catalog/countrybrand');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/countrybrand');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_countrybrand->editCountrybrand($this->request->get['countrybrand_id'], $this->request->post);
				if (isset($this->request->post['continue']) && $this->request->post['continue'] == '1') {
					$countrybrand_id = $this->request->get['countrybrand_id'];
					$this->session->data['success'] = $this->language->get('text_success');	
					$this->redirect($this->url->link('catalog/countrybrand/update', 'token='.$this->session->data['token'].'&countrybrand_id='.$countrybrand_id, 'SSL'));
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
				
				//	$this->redirect($this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				$this->redirect($this->url->link('catalog/countrybrand/update', 'token='.$this->session->data['token'].'&countrybrand_id='.$this->request->get['countrybrand_id'], 'SSL'));
			}
			
			$this->getForm();
		}
		
		
		public function delete() {
			$this->language->load('catalog/countrybrand');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/countrybrand');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $countrybrand_id) {
					$this->model_catalog_countrybrand->deleteCountrybrand($countrybrand_id);
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
				
				$this->redirect($this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {			
			$this->load->model('catalog/manufacturer');
			$this->data['brandArray'] = $this->model_catalog_manufacturer->getManufacturers();
			$this->data['control_brand'] = isset($_GET['manufacturer_id']) ? $_GET['manufacturer_id'] : 0; 
			
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
			'href'      => $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('catalog/countrybrand/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('catalog/countrybrand/delete', 'token=' . $this->session->data['token'] . $url);	
			
			$this->data['countrybrands'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit'),			
			);
			
			$this->load->model('localisation/language');
			$this->load->model('tool/image');
			$this->load->model('setting/store');
			
			
			$this->data['stores'] = $this->model_setting_store->getStores();
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			$countrybrand_total = $this->model_catalog_countrybrand->getTotalCountrybrands($data);
			$results = $this->model_catalog_countrybrand->getCountrybrands($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/countrybrand/update', 'token=' . $this->session->data['token'] . '&countrybrand_id=' . $result['countrybrand_id'] . $url, 'SSL')
				);
				
				
				
				$this->data['countrybrands'][] = array(
				'countrybrand_id' => $result['countrybrand_id'],
				'descriptions' 	  => $this->model_catalog_countrybrand->getCountrybrandDescriptions($result['countrybrand_id']),
				'seo_urls'		  => $this->model_catalog_countrybrand->getKeyWords($result['countrybrand_id']),
				'stores'		  => $this->model_catalog_countrybrand->getCountrybrandStores($result['countrybrand_id']),
				'total_brands'    => $this->model_catalog_countrybrand->getTotalManufacturersByCountryBrand($result['countrybrand_id']),
				'name'            => $result['name'],
				'alternate_name'  => $result['alternate_name'],
				'image'			  => $this->model_tool_image->resize($result['image'], 50, 50),
				'banner'		  => $this->model_tool_image->resize($result['banner'], 50, 50),
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['countrybrand_id'], $this->request->post['selected']),
				'action'          => $action,
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
			
			$this->data['sort_name'] = $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . '&sort=name' . $url);
			$this->data['sort_sort_order'] = $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $countrybrand_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'catalog/countrybrand_list.tpl';
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
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['countrybrand_id'])) {
				$this->data['action'] = $this->url->link('catalog/countrybrand/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('catalog/countrybrand/update', 'token=' . $this->session->data['token'] . '&countrybrand_id=' . $this->request->get['countrybrand_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['countrybrand_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$countrybrand_info = $this->model_catalog_countrybrand->getCountrybrand($this->request->get['countrybrand_id']);
			}
			
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('localisation/language');		
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			$this->data['locations'] = [];
			foreach ($this->data['languages'] as $language){
				$this->data['locations'][$language['language_id']] = $this->model_catalog_countrybrand->getAllCountriesFromManufacturer($language['language_id']);				
			}
			
			if (isset($this->request->post['countrybrand_description'])) {
				$this->data['countrybrand_description'] = $this->request->post['countrybrand_description'];
				} elseif (isset($this->request->get['countrybrand_id'])) {
				$this->data['countrybrand_description'] = $this->model_catalog_countrybrand->getCountrybrandDescriptions($this->request->get['countrybrand_id']);
				} else {
				$this->data['countrybrand_description'] = array();
			}
			
			$this->load->model('kp/reward');
			if (isset($this->request->post['reward'])) {
				$this->data['rewards'] = $this->request->post['reward'];
				} elseif (isset($this->request->get['countrybrand_id'])) {
				$this->data['rewards'] = $this->model_kp_reward->getEntityRewards($this->request->get['countrybrand_id'], 'co');
				} else {
				$this->data['rewards'] = array();
			}
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['name'] = $countrybrand_info['name'];
				} else {	
				$this->data['name'] = '';
			}
			
			if (isset($this->request->post['template'])) {
				$this->data['template'] = $this->request->post['template'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['template'] = $countrybrand_info['template'];
				} else {	
				$this->data['template'] = '';
			}
			
			if (isset($this->request->post['flag'])) {
				$this->data['flag'] = $this->request->post['flag'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['flag'] = $countrybrand_info['flag'];
				} else {	
				$this->data['flag'] = '';
			}
			
			
			$this->load->model('setting/store');
			
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($this->request->post['countrybrand_store'])) {
				$this->data['countrybrand_store'] = $this->request->post['countrybrand_store'];
				} elseif (isset($this->request->get['countrybrand_id'])) {
				$this->data['countrybrand_store'] = $this->model_catalog_countrybrand->getCountrybrandStores($this->request->get['countrybrand_id']);
				} else {
				$this->data['countrybrand_store'] = array(0);
			}	
			
			if (isset($this->request->post['keyword'])) {
				$this->data['keyword'] = $this->request->post['keyword'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['keyword'] = $this->model_catalog_countrybrand->getKeyWords($this->request->get['countrybrand_id']);
				} else {
				$this->data['keyword'] = array();
			}
			
				$this->load->model('tool/image');
			
			// Images
			if (isset($this->request->post['countrybrand_image'])) {
				$countrybrand_images = $this->request->post['countrybrand_image'];
				} elseif (isset($this->request->get['countrybrand_id'])) {
				$countrybrand_images = $this->model_catalog_countrybrand->getCountrybrandImages($this->request->get['countrybrand_id']);
				} else {
				$countrybrand_images = array();
			}
			
			$this->data['countrybrand_images'] = array();
			
			foreach ($countrybrand_images as $countrybrand_image) {
				$this->data['countrybrand_images'][] = array(
				'image'      => $countrybrand_image['image'],
				'thumb'      => $this->model_tool_image->resize($countrybrand_image['image'], 100, 100),
				'sort_order' => $countrybrand_image['sort_order']
				);
			}
			
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			
			if (isset($this->request->post['image'])) {
				$this->data['image'] = $this->request->post['image'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['image'] = $countrybrand_info['image'];
				} else {
				$this->data['image'] = '';
			}
			
			if (isset($this->request->post['banner'])) {
				$this->data['banner'] = $this->request->post['banner'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['banner'] = $countrybrand_info['banner'];
				} else {
				$this->data['banner'] = '';
			}					
			
			if (isset($this->request->post['image'])) {
				$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
				} elseif (!empty($countrybrand_info) && $countrybrand_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($countrybrand_info['image'], 100, 100);
				} else {
				$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			if (isset($this->request->post['banner'])) {
				$this->data['banner_thumb'] = $this->model_tool_image->resize($this->request->post['banner'], 100, 100);
				} elseif (!empty($countrybrand_info) && $countrybrand_info['banner']) {
				$this->data['banner_thumb'] = $this->model_tool_image->resize($countrybrand_info['banner'], 100, 100);
				} else {
				$this->data['banner_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			
			if (isset($this->request->post['sort_order'])) {
				$this->data['sort_order'] = $this->request->post['sort_order'];
				} elseif (!empty($countrybrand_info)) {
				$this->data['sort_order'] = $countrybrand_info['sort_order'];
				} else {
				$this->data['sort_order'] = '';
			}
			
			$this->template = 'catalog/countrybrand_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}  
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/countrybrand')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 128)) {
				$this->error['name'] = $this->language->get('error_name');
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
				$this->load->model('catalog/countrybrand');
				$this->load->model('catalog/manufacturer');
				
				$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
				);
				
				$results = $this->model_catalog_countrybrand->getCountrybrands($data);
				
				foreach ($results as $result) {
					
					$manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
					
					if ($manufacturer){
						$mname = $manufacturer['name'];
					}
					
					$json[] = array(
					'countrybrand_id' => $result['countrybrand_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'].' / '.$mname, ENT_QUOTES, 'UTF-8'))
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
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/countrybrand')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$this->load->model('catalog/product');
			
			foreach ($this->request->post['selected'] as $countrybrand_id) {

			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}  
		}

	}
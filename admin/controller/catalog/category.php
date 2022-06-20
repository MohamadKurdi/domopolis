<?php 
class ControllerCatalogCategory extends Controller { 
	private $error = array();	
	private $category_id = 0;
	private $path = array();
	private $levels = [
		'#34913e','#ff7815','#64a1e1','#7f00ff','#3276c2','#24a4c1','#f91c02'
	];

	public function index() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->addCategory($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->request->post['apply']) and $this->request->post['apply'])
				$this->redirect($this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'] . $url, 'SSL'));
			else
				$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function rollup() {
		if (empty($this->session->data['category_rollup']) || $this->session->data['category_rollup'] == 0){
			$this->session->data['category_rollup'] = 1;
		} else {
			$this->session->data['category_rollup'] = 0;
		}

		$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function rollup_all() {
		if (!empty($this->session->data['path'])){
			unset($this->session->data['path']);
		}

		$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function repair() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		if ($this->validateRepair()) {
			$this->model_catalog_category->repairCategories();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();	
	}

	private function getCategories($parent_id, $parent_path = '', $indent = 0) {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		static $href_category = null;
		static $href_action = null;

		if ($href_category === null) {
			$href_category = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&path=', 'SSL');
			$href_action = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=', 'SSL');
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (!empty($this->session->data['category_rollup'])){
			$category_id = array_shift($this->path);
			$output = array();

			$results = $this->model_catalog_category->getCategoriesByParentId($parent_id);
			//$results = $this->model_catalog_category->getCategories(['filter_parent_id' => $parent_id]);

		} else {
			$filter = array(
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')				
			);
			
			$results = $this->model_catalog_category->getCategories($filter);
		}		

		$data = [];
		foreach ($results as $result) {
			$path = $parent_path . $result['category_id'];
			$href = ($result['children']) ? $href_category . $path : '';			

			if (!empty($this->session->data['category_rollup']) && $category_id == $result['category_id']) {				
				$this->data['breadcrumbs'][] = array(
					'text'      => $result['name'],
					'href'      => $href,
					'separator' => ' :: '
				);

				$href = '';
			}

			$real_category = $this->model_catalog_category->getCategory($result['category_id']);

			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('aqe_list_view_image_width'), $this->config->get('aqe_list_view_image_height'));
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('aqe_list_view_image_width'), $this->config->get('aqe_list_view_image_height'));
			}

			$yandex_category_name = false;
			if ($real_category['yandex_category_name']){
				$yandex_category_name = $real_category['yandex_category_name'];
			}			

			$mark = false;
			if (!empty($this->session->data['category_rollup'])) {
				if ($result['parent_name']){
					$name = $result['parent_name'] . ' > ' . $result['name'];				
				} else {
					$name = $result['name'];
				}				

				if ($category_id == $result['category_id']){
					$name = '<b>' . $name . '</b>';	
					$mark = true;
				}
			} else {
				$name = $result['name'];
			}

			$data[$result['category_id']] = array(
				'category_id' 				=> $result['category_id'],
				'href'						=> $href,
				'name'        				=> $name,
				'indent'					=> $indent,
				'mark'						=> $mark,
				'level'						=> (!empty($result['level']))?($result['level'] - 2):false,		
				'alternate_name' 			=> $result['alternate_name'],
				'image'       				=> $image,
				'menu_name'   				=> $result['menu_name'],
				'menu_icon'   				=> html_entity_decode($result['menu_icon'], ENT_QUOTES, 'UTF-8'),
				'sort_order'  				=> $result['sort_order'],
				'amazon_category_name'  	=> $real_category['amazon_category_id']?$real_category['amazon_category_name']:false,
				'amazon_category_id'  		=> $real_category['amazon_category_id'],
				'amazon_sync_enable'  		=> $real_category['amazon_sync_enable'],
				'amazon_final_category'  	=> $real_category['amazon_final_category'],
				'amazon_category_link'  	=> $real_category['amazon_category_link'],
				'yandex_category_name'  	=> $yandex_category_name,
				'google_category' 			=> $this->model_catalog_category->getGoogleCategoryByID($real_category['google_category_id']),
				'tnved'						=> $real_category['tnved'],
				'deletenotinstock'			=> $real_category['deletenotinstock'],
				'priceva_enable'			=> $real_category['priceva_enable'],
				'submenu_in_children'		=> $real_category['submenu_in_children'],
				'intersections'				=> $real_category['intersections'],
				'default_length'			=> $real_category['default_length'],
				'default_width'				=> $real_category['default_width'],
				'default_height'			=> $real_category['default_height'],
				'default_weight'			=> $real_category['default_weight'],
				'intersections'				=> $real_category['intersections'],
				'status'					=> $real_category['status'],
				'selected'    				=> isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      				=> $action
			);

			if (!empty($this->session->data['category_rollup']) && $category_id == $result['category_id']) {
				$data += $this->getCategories($result['category_id'], $path . '_', ($indent + 25));
			}
		}

		return $data;
	}


	protected function getList() {
		$this->load->model('tool/image');

		$this->data['levels'] = $this->levels;

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['repair'] = $this->url->link('catalog/category/repair', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['rollup'] = $this->url->link('catalog/category/rollup', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['rollup_all'] = $this->url->link('catalog/category/rollup_all', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['rollup_enabled'] = !empty($this->session->data['category_rollup']);

		if (isset($this->request->get['path'])) {
			if ($this->request->get['path'] != '') {
				$this->path = explode('_', $this->request->get['path']);
				$this->category_id = end($this->path);
				$this->session->data['path'] = $this->request->get['path'];
			} else {
				unset($this->session->data['path']);
			}
		} elseif (isset($this->session->data['path'])) {
			$this->path = explode('_', $this->session->data['path']);
			$this->category_id = end($this->path);
		}

		$this->data['categories'] = $this->getCategories(0);		

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_repair'] = $this->language->get('button_repair');

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

		if (!$this->session->data['category_rollup']){

			$category_total = $this->model_catalog_category->getTotalCategories();

			$pagination = new Pagination();
			$pagination->total = $category_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

			$this->data['pagination'] = $pagination->render();

		} else {
			$this->data['pagination'] = '';
		}

		$this->template = 'catalog/category_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_seo_title'] = $this->language->get('entry_seo_title');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_seo_h1'] = $this->language->get('entry_seo_h1');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_column'] = $this->language->get('entry_column');		
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['categoryocshop'] = $this->url->link('catalog/categoryocshop', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);			
		}

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description'])) {
			$this->data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$this->data['category_description'] = array();
		}

		if (isset($this->data['category_description'][$this->config->get('config_language_id')])){
			$this->data['heading_title'] = $this->data['category_description'][$this->config->get('config_language_id')]['name'];
		}

		if (isset($this->request->post['category_menu_content'])) {
			$this->data['category_menu_content'] = $this->request->post['category_menu_content'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_menu_content'] = $this->model_catalog_category->getCategoryMenuContent($this->request->get['category_id']);
		} else {
			$this->data['category_menu_content'] = array();
		}

		$this->load->model('kp/reward');
		if (isset($this->request->post['reward'])) {
			$this->data['rewards'] = $this->request->post['reward'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['rewards'] = $this->model_kp_reward->getEntityRewards($this->request->get['category_id'], 'c');
		} else {
			$this->data['rewards'] = array();
		}

		$this->load->model('tool/image');

		foreach ($this->data['languages'] as $language){
			if (isset($this->data['category_menu_content'][$language['language_id']])){
				foreach ($this->data['category_menu_content'][$language['language_id']] as &$content){

					if (isset($content['image']) && $content['image'] && file_exists(DIR_IMAGE . $content['image'])) {
						$content['thumb'] = $this->model_tool_image->resize($content['image'], 100, 100);													
					} else {											
						$content['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
					}
				}									
			}
		}


		if (isset($this->request->post['path'])) {
			$this->data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$this->data['path'] = $category_info['path'];
		} else {
			$this->data['path'] = '';
		}

		if (isset($this->request->post['tnved'])) {
			$this->data['tnved'] = $this->request->post['tnved'];
		} elseif (!empty($category_info)) {
			$this->data['tnved'] = $category_info['tnved'];
		} else {
			$this->data['tnved'] = '';
		}

		if (isset($this->request->post['amazon_sync_enable'])) {
			$this->data['amazon_sync_enable'] = $this->request->post['amazon_sync_enable'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_sync_enable'] = $category_info['amazon_sync_enable'];
		} else {
			$this->data['amazon_sync_enable'] = false;
		}

		if (isset($this->request->post['amazon_final_category'])) {
			$this->data['amazon_final_category'] = $this->request->post['amazon_final_category'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_final_category'] = $category_info['amazon_final_category'];
		} else {
			$this->data['amazon_final_category'] = false;
		}

		if (isset($this->request->post['amazon_category_id'])) {
			$this->data['amazon_category_id'] = $this->request->post['amazon_category_id'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_category_id'] = $category_info['amazon_category_id'];
		} else {
			$this->data['amazon_category_id'] = '';
		}

		if (isset($this->request->post['amazon_category_name'])) {
			$this->data['amazon_category_name'] = $this->request->post['amazon_category_name'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_category_name'] = $category_info['amazon_category_name'];
		} else {
			$this->data['amazon_category_name'] = '';
		}

		if (isset($this->request->post['amazon_parent_category_id'])) {
			$this->data['amazon_parent_category_id'] = $this->request->post['amazon_parent_category_id'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_parent_category_id'] = $category_info['amazon_parent_category_id'];
		} else {
			$this->data['amazon_parent_category_id'] = '';
		}

		if (!empty($category_info)){
			$this->data['amazon_parent_category_name'] = $category_info['amazon_parent_category_name'];
		} else {
			$this->data['amazon_parent_category_name'] = '';
		}

		if (!empty($category_info)){
			$this->data['amazon_category_link'] = $category_info['amazon_category_link'];
		} else {
			$this->data['amazon_category_link'] = '';
		}

		if (isset($this->request->post['yandex_category_name'])) {
			$this->data['yandex_category_name'] = $this->request->post['yandex_category_name'];
		} elseif (!empty($category_info)) {
			$this->data['yandex_category_name'] = $category_info['yandex_category_name'];
		} else {
			$this->data['yandex_category_name'] = '';
		}

		if (isset($this->request->post['amazon_last_sync'])) {
			$this->data['amazon_last_sync'] = $this->request->post['amazon_last_sync'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_last_sync'] = $category_info['amazon_last_sync'];
		} else {
			$this->data['amazon_last_sync'] = '0000-00-00';
		}

		if (isset($this->request->post['amazon_overprice_rules'])) {
			$this->data['amazon_overprice_rules'] = $this->request->post['amazon_overprice_rules'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_overprice_rules'] = $category_info['amazon_overprice_rules'];
		} else {
			$this->data['amazon_overprice_rules'] = '';
		}

		if (isset($this->request->post['overprice'])) {
			$this->data['overprice'] = $this->request->post['overprice'];
		} elseif (!empty($category_info)) {
			$this->data['overprice'] = $category_info['overprice'];
		} else {
			$this->data['overprice'] = '';
		}

		if (isset($this->request->post['virtual_path'])) {
			$this->data['virtual_path'] = $this->request->post['virtual_path'];
		} elseif (!empty($category_info)) {
			$this->data['virtual_path'] = $category_info['virtual_path'];
		} else {
			$this->data['virtual_path'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}

		if (isset($this->request->post['google_category_id'])) {
			$this->data['google_category_id'] = $this->request->post['google_category_id'];
			$this->data['google_category'] = $this->request->post['google_category'];
		} elseif (!empty($category_info)) {
			$this->data['google_category_id'] = $category_info['google_category_id'];
			$this->data['google_category'] = $this->model_catalog_category->getGoogleCategoryByID($category_info['google_category_id']);
		} else {
			$this->data['google_category_id'] = 0;
			$this->data['google_category'] = '';
		}

		if (isset($this->request->post['separate_feeds'])) {
			$this->data['separate_feeds'] = $this->request->post['separate_feeds'];
		} elseif (!empty($category_info)) {
			$this->data['separate_feeds'] = $category_info['separate_feeds'];
		} else {
			$this->data['separate_feeds'] = 0;
		}

		if (isset($this->request->post['no_general_feed'])) {
			$this->data['no_general_feed'] = $this->request->post['no_general_feed'];
		} elseif (!empty($category_info)) {
			$this->data['no_general_feed'] = $category_info['no_general_feed'];
		} else {
			$this->data['no_general_feed'] = 0;
		}

		if (isset($this->request->post['deletenotinstock'])) {
			$this->data['deletenotinstock'] = $this->request->post['deletenotinstock'];
		} elseif (!empty($category_info)) {
			$this->data['deletenotinstock'] = $category_info['deletenotinstock'];
		} else {
			$this->data['deletenotinstock'] = 0;
		}

		if (isset($this->request->post['intersections'])) {
			$this->data['intersections'] = $this->request->post['intersections'];
		} elseif (!empty($category_info)) {
			$this->data['intersections'] = $category_info['intersections'];
		} else {
			$this->data['intersections'] = 0;
		}

		if (isset($this->request->post['virtual_parent_id'])) {
			$this->data['virtual_parent_id'] = $this->request->post['virtual_parent_id'];
		} elseif (!empty($category_info)) {
			$this->data['virtual_parent_id'] = $category_info['virtual_parent_id'];
		} else {
			$this->data['virtual_parent_id'] = '-1';
		}

		$this->load->model('catalog/filter');

		if (isset($this->request->post['category_filter'])) {
			$filters = $this->request->post['category_filter'];
		} elseif (isset($this->request->get['category_id'])) {		
			$filters = $this->model_catalog_category->getCategoryFilters($this->request->get['category_id']);
		} else {
			$filters = array();
		}

		$this->data['category_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['category_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}	

		$this->load->model('catalog/actions');

		if (isset($this->request->post['category_actions'])) {
			$actions = $this->request->post['category_actions'];
		} elseif (isset($this->request->get['category_id'])) {		
			$actions = $this->model_catalog_category->getCategoryActions($this->request->get['category_id']);
		} else {
			$actions = array();
		}

		$this->data['category_actions'] = array();

		foreach ($actions as $actions_id) {
			$actions_info = $this->model_catalog_actions->getActions($actions_id);

			if ($actions_info) {
				$this->data['category_actions'][] = array(
					'actions_id' => $actions_info['actions_id'],
					'name'       => $actions_info['caption']
				);
			}
		}	

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$this->data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
		} else {
			$this->data['category_store'] = array(0);
		}			

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($category_info)) {
			$this->data['keyword'] = $this->model_catalog_category->getKeyWords($this->request->get['category_id']);
		} else {
			$this->data['keyword'] = array();
		}

		if (isset($this->request->post['menu_icon'])) {
			$this->data['menu_icon'] = $this->request->post['menu_icon'];
		} elseif (!empty($category_info)) {
			$this->data['menu_icon'] = $category_info['menu_icon'];
		} else {
			$this->data['menu_icon'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$this->data['image'] = $category_info['image'];
		} else {
			$this->data['image'] = '';
		}

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['top'])) {
			$this->data['top'] = $this->request->post['top'];
		} elseif (!empty($category_info)) {
			$this->data['top'] = $category_info['top'];
		} else {
			$this->data['top'] = 0;
		}

		if (isset($this->request->post['column'])) {
			$this->data['column'] = $this->request->post['column'];
		} elseif (!empty($category_info)) {
			$this->data['column'] = $category_info['column'];
		} else {
			$this->data['column'] = 1;
		}

		if (isset($this->request->post['submenu_in_children'])) {
			$this->data['submenu_in_children'] = $this->request->post['submenu_in_children'];
		} elseif (!empty($category_info)) {
			$this->data['submenu_in_children'] = $category_info['submenu_in_children'];
		} else {
			$this->data['submenu_in_children'] = 0;
		}

		if (isset($this->request->post['priceva_enable'])) {
			$this->data['priceva_enable'] = $this->request->post['priceva_enable'];
		} elseif (!empty($category_info)) {
			$this->data['priceva_enable'] = $category_info['priceva_enable'];
		} else {
			$this->data['priceva_enable'] = 0;
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = 1;
		}

			//PACK DIMENSIONS

		$this->load->model('localisation/weight_class');			
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$this->load->model('localisation/length_class');
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['default_weight'])) {
			$this->data['default_weight'] = $this->request->post['default_weight'];
		} elseif (!empty($category_info)) {
			$this->data['default_weight'] = $category_info['default_weight'];
		} else {
			$this->data['default_weight'] = '';
		}

		if (isset($this->request->post['default_weight_class_id'])) {
			$this->data['default_weight_class_id'] = $this->request->post['default_weight_class_id'];
		} elseif (!empty($category_info)) {
			$this->data['default_weight_class_id'] = $category_info['default_weight_class_id'];
		} else {
			$this->data['default_weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['default_length'])) {
			$this->data['default_length'] = $this->request->post['default_length'];
		} elseif (!empty($category_info)) {
			$this->data['default_length'] = $category_info['default_length'];
		} else {
			$this->data['default_length'] = '';
		}

		if (isset($this->request->post['default_width'])) {
			$this->data['default_width'] = $this->request->post['default_width'];
		} elseif (!empty($category_info)) {	
			$this->data['default_width'] = $category_info['default_width'];
		} else {
			$this->data['default_width'] = '';
		}

		if (isset($this->request->post['default_height'])) {
			$this->data['default_height'] = $this->request->post['default_height'];
		} elseif (!empty($category_info)) {
			$this->data['default_height'] = $category_info['default_height'];
		} else {
			$this->data['default_height'] = '';
		}

		if (isset($this->request->post['default_length_class_id'])) {
			$this->data['default_length_class_id'] = $this->request->post['default_length_class_id'];
		} elseif (!empty($category_info)) {
			$this->data['default_length_class_id'] = $category_info['default_length_class_id'];
		} else {
			$this->data['default_length_class_id'] = $this->config->get('config_length_class_id');
		}

		if (isset($this->request->post['category_layout'])) {
			$this->data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$this->data['category_layout'] = array();
		}

		$this->load->model('catalog/attribute');

		$attribute_total = $this->model_catalog_attribute->getTotalAttributes();

		$results = $this->model_catalog_attribute->getAttributes();

		foreach ($results as $result) { 
			$this->data['attributes'][] = array(
				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'attribute_group' => $result['attribute_group'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['attribute_id'], $this->request->post['selected']),
			);
		}

		$this->data['attributes_category'] = array();
		if (isset($this->request->get['category_id'])) {
			$this->data['attributes_category'] = $this->model_catalog_category->getAttributesByCategory($this->request->get['category_id']);
		}

		$this->data['attributes_similar_category'] = array();
		if (isset($this->request->get['category_id'])) {
			$this->data['attributes_similar_category'] = $this->model_catalog_category->getAttributesSimilarByCategory($this->request->get['category_id']);
		}

		$this->load->model('catalog/category');

		if (isset($this->request->post['related_category'])) {
			$related_categories = $this->request->post['related_category'];
		} elseif (isset($this->request->get['category_id'])) {		
			$related_categories = $this->model_catalog_category->getRelatedCategories($this->request->get['category_id']);
		} else {
			$related_categories = array();
		}

		$this->data['related_categories'] = array();

		foreach ($related_categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$this->data['related_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}		

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'catalog/category_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$temp_name = '';
		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if (utf8_strlen($value['name']) > 1){
				$temp_name = $value['name'];
				break;
			}			
		}

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {				
				$this->request->post['category_description'][$language_id]['name'] = $temp_name;
					//	$this->error['warning'] = 'Выполнена автоподмена. Но сохранение прошло! Валерчик, привет!:)';
					//	$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}

	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}

	public function google_autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) && mb_strlen($this->request->get['filter_name']) > 2) {
			$this->load->model('catalog/category');
			$results = $this->model_catalog_category->getGoogleCategoryByName($this->request->get['filter_name']);

			foreach ($results as $result) {
				$json[] = array(
					'google_base_category_id' => $result['google_base_category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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

	public function yandex_autocomplete() {	
		$this->load->model('catalog/category');

		$json = array();

		$results = $this->model_catalog_category->getYandexCategories($this->request->get['filter_name']);

		foreach ($results as $result){
			
			if ($result['final_category']){
				$labeled_name= '[FINAL] ' . $result['full_name'];
			} else {
				$labeled_name = '[TREE] ' . $result['full_name'];
			}
			
			$json[] = [
				'name'  => $labeled_name,
				'name2' => $result['full_name'],
				'id'	=> $result['category_id']
			];
			
		}

		array_unshift($json, ['name' => 'Не назначать', 'id' => 0]);

		$this->response->setOutput(json_encode($json));
	}				

	public function getAmazonCategoriesCSV(){
		
		header( 'Content-Type: text/csv charset=utf-8' );
		header("Content-Disposition: attachment; filename=amazon_categories_". date('Y_m_d') .".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$file = fopen('php://output', 'w');

		if ($this->config->get('config_rainforest_category_model') == 'standard'){
			$query = $this->db->query("SELECT * FROM category_amazon_tree WHERE 1");
		}

		if ($this->config->get('config_rainforest_category_model') == 'bestsellers'){
			$query = $this->db->query("SELECT * FROM category_amazon_bestseller_tree WHERE 1");
		}
		
		foreach ($query->rows as $row){

			fputcsv($file, $row);
			
		}

		fclose($file);
		
	}

	public function amazon_autocomplete() {				

		$json = array();

		if ($this->config->get('config_rainforest_enable_api') && isset($this->request->get['filter_name']) && mb_strlen($this->request->get['filter_name']) > 3) {

			$queryString = http_build_query([
				'api_key' 		=> $this->config->get('config_rainforest_api_key'),
				'amazon_domain' => $this->config->get('config_rainforest_api_domain_1'),
				'search_term'	=> $this->request->get['filter_name'],
				'type'			=> $this->config->get('config_rainforest_category_model')
			]);

			$ch = curl_init('https://api.rainforestapi.com/categories?' . $queryString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_VERBOSE, true);

			$json = curl_exec($ch);
			curl_close($ch);

			$encoded = json_decode($json, true);

			if (!empty($encoded['categories'])){
				$json = $encoded['categories'];
			} else {
				$json = [['path' => $encoded['request_info']['message'], 'id' => 0]];
			}
		}

		array_unshift($json, ['path' => 'Не синхронизировать', 'id' => 0]);

		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_catalog_category->getCategories($data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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
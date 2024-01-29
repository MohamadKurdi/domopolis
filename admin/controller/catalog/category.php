<?php 
class ControllerCatalogCategory extends Controller { 
	private $error = [];	
	private $category_id = 0;
	private $path = [];
	private $levels = [
		'#34913e','#ff7815','#64a1e1','#7f00ff','#3276c2','#24a4c1','#f91c02'
	];

	public function index() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/category');

		$this->getList();
	}

	public function descriptionbyai(){
		$request = $this->request->post['request'];

		if ($result = $this->openaiAdaptor->categoryDescription($request)){
			$this->response->setOutput($result);
		} else {
			$this->response->setOutput('');
		}
	}

	public function alternatenamesbyai(){
		$request = $this->request->post['request'];

		if ($result = $this->openaiAdaptor->alternateNames($request)){
			$this->response->setOutput($result);
		} else {
			$this->response->setOutput('');
		}
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

	public function hidedisabled() {
		if (empty($this->session->data['category_hidedisabled']) || $this->session->data['category_hidedisabled'] == 0){
			$this->session->data['category_hidedisabled'] = 1;
		} else {
			$this->session->data['category_hidedisabled'] = 0;
		}

		$this->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

	public function simpleview() {
		if (empty($this->session->data['simpleview']) || $this->session->data['simpleview'] == 0){
			$this->session->data['simpleview'] = 1;
		} else {
			$this->session->data['simpleview'] = 0;
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
			$href_category = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&path=');
			$href_action = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=');
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (!empty($this->session->data['category_rollup'])){
			$category_id = array_shift($this->path);
			$output = [];

			$results = $this->model_catalog_category->getCategoriesByParentId($parent_id);
			//$results = $this->model_catalog_category->getCategories(['filter_parent_id' => $parent_id]);

		} else {
			$filter = array(
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit'),				
			);

			if (!empty($this->session->data['category_hidedisabled'])){
				$filter['filter_status'] = 1;
			}
			
			$results = $this->model_catalog_category->getCategories($filter);
		}		

		$data = [];
		foreach ($results as $result) {
			$path = $parent_path . $result['category_id'];
			$href = (!empty($result['children'])) ? $href_category . $path : '';			

			if (!empty($this->session->data['category_rollup']) && $category_id == $result['category_id']) {				
				$this->data['breadcrumbs'][] = array(
					'text'      => $result['name'],
					'href'      => $href,
					'separator' => ' :: '
				);

				$href = '';
			}

			$real_category = $this->model_catalog_category->getCategory($result['category_id']);
			$action = [];

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL')
			);

			$image = $this->model_tool_image->resize($result['image'], $this->config->get('aqe_list_view_image_width'), $this->config->get('aqe_list_view_image_height'));

			$yandex_category_name = false;
			if ($real_category['yandex_category_name']){
				$yandex_category_name = $real_category['yandex_category_name'];
			}	

			$hotline_category_name = false;
			if ($real_category['hotline_category_name']){
				$hotline_category_name = $real_category['hotline_category_name'];
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

			if (!empty($this->session->data['category_hidedisabled']) && !$result['status']){
				continue;
			} else {

				$data[$result['category_id']] = array(
					'category_id' 				=> $result['category_id'],
					'href'						=> $href,
					'name'        				=> $name,
					'indent'					=> $indent,
					'mark'						=> $mark,
					'count'       				=> $this->model_catalog_category->getTotalProductInCategoryWithSubcategories($result['category_id']),
					'filled'       				=> $this->model_catalog_category->getTotalFilledProductInCategoryWithSubcategories($result['category_id']),
					'has_price'       			=> $this->model_catalog_category->getTotalProductNoZeroPriceInCategoryWithSubcategories($result['category_id']),
					'enabled'       			=> $this->model_catalog_category->getTotalProductNoZeroPriceAndEnabledInCategoryWithSubcategories($result['category_id']),
					'category_overprice_rules'  => $this->model_catalog_category->countCategoryOverpriceRules($result['category_id']),
					'overload_max_wc_multiplier' 		=> $result['overload_max_wc_multiplier'],
					'overload_max_multiplier' 			=> $result['overload_max_multiplier'],
					'overload_ignore_volumetric_weight' => $result['overload_ignore_volumetric_weight'],
					'need_reprice' 						=> $result['need_reprice'],
					'last_reprice' 						=> ($result['last_reprice'] != '0000-00-00 00:00:00')?date('m-d', strtotime($result['last_reprice'])):'',
					'filter'					=> $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&filter_category=' . $result['category_id']),
					'filter_filled'				=> $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&filter_filled_from_amazon=1&filter_category=' . $result['category_id']),
					'filter_has_price'			=> $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&filter_price=>0&filter_category=' . $result['category_id']),
					'filter_enabled'			=> $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&filter_status=1&filter_category=' . $result['category_id']),
					'level'						=> (!empty($result['level']))?($result['level'] - 2):false,		
					'alternate_name' 			=> $result['alternate_name'],
					'image'       				=> $image,
					'menu_name'   				=> $result['menu_name'],
					'menu_icon'   				=> html_entity_decode($result['menu_icon'], ENT_QUOTES, 'UTF-8'),
					'sort_order'  				=> $result['sort_order'],
					'amazon_category_name'  	=> $real_category['amazon_category_id']?$real_category['amazon_category_name']:false,
					'amazon_category_id'  		=> $real_category['amazon_category_id'],
					'amazon_sync_enable'  		=> $real_category['amazon_sync_enable'],
					'amazon_last_sync'  		=> $real_category['amazon_last_sync'],
					'amazon_synced'  			=> $real_category['amazon_synced'],
					'amazon_final_category'  	=> $real_category['amazon_final_category'],
					'amazon_can_get_full'  		=> $real_category['amazon_can_get_full'],
					'amazon_category_link'  	=> $real_category['amazon_category_link'],
					'yandex_category_name'  	=> $yandex_category_name,
					'hotline_category_name'  	=> $hotline_category_name,
					'hotline_enable'			=> $real_category['hotline_enable'],
					'google_category' 			=> $this->model_catalog_category->getGoogleCategoryByID($real_category['google_category_id']),
					'no_general_feed'			=> $real_category['no_general_feed'],
					'tnved'						=> $real_category['tnved'],
					'deletenotinstock'			=> $real_category['deletenotinstock'],
					'priceva_enable'			=> $real_category['priceva_enable'],
					'submenu_in_children'		=> $real_category['submenu_in_children'],
					'intersections'					=> $real_category['intersections'],
					'exclude_from_intersections'	=> $real_category['exclude_from_intersections'],
					'default_length'			=> $real_category['default_length'],
					'default_width'				=> $real_category['default_width'],
					'default_height'			=> $real_category['default_height'],
					'default_weight'			=> $real_category['default_weight'],					
					'status'					=> $real_category['status'],
					'homepage'					=> $real_category['homepage'],
					'special'					=> $real_category['special'],
					'popular'					=> $real_category['popular'],
					'selected'    				=> isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
					'action'      				=> $action
				);

				if (!empty($this->session->data['category_rollup']) && $category_id == $result['category_id']) {
					$data += $this->getCategories($result['category_id'], $path . '_', ($indent + 25));
				}
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

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		$this->data['insert'] 		= $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] 		= $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . $url);
		$this->data['repair'] 		= $this->url->link('catalog/category/repair', 'token=' . $this->session->data['token'] . $url);
		$this->data['rollup'] 		= $this->url->link('catalog/category/rollup', 'token=' . $this->session->data['token'] . $url);
		$this->data['hidedisabled'] = $this->url->link('catalog/category/hidedisabled', 'token=' . $this->session->data['token'] . $url);
		$this->data['simpleview'] 	= $this->url->link('catalog/category/simpleview', 'token=' . $this->session->data['token'] . $url);
		$this->data['rollup_all'] 	= $this->url->link('catalog/category/rollup_all', 'token=' . $this->session->data['token'] . $url);

		$this->data['rollup_enabled'] 		= !empty($this->session->data['category_rollup']);
		$this->data['hidedisabled_enabled'] = !empty($this->session->data['category_hidedisabled']);
		$this->data['simpleview_enabled'] 	= !empty($this->session->data['simpleview']);

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

		if (empty($this->session->data['category_rollup']) || !$this->session->data['category_rollup']){

			$category_total = $this->model_catalog_category->getTotalCategories();

			$pagination 		= new Pagination();
			$pagination->total 	= $category_total;
			$pagination->page 	= $page;
			$pagination->limit 	= $this->config->get('config_admin_limit');
			$pagination->text 	= $this->language->get('text_pagination');
			$pagination->url 	= $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}');

			$this->data['pagination'] = $pagination->render();

		} else {
			$this->data['pagination'] = '';
		}

		if ($this->data['simpleview_enabled']){
			$this->template = 'catalog/category_list_simpleview.tpl';
		} else {
			$this->template = 'catalog/category_list.tpl';
		}
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

		$this->data['categoryocshop'] = $this->url->link('catalog/categoryocshop', 'token=' . $this->session->data['token']);

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = [];
		}

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token']);
		} else {
			$this->data['action'] = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id']);
		}

		$this->data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token']);

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
			$this->data['category_description'] = [];
		}

		if (isset($this->data['category_description'][$this->config->get('config_language_id')])){
			$this->data['heading_title'] = $this->data['category_description'][$this->config->get('config_language_id')]['name'];
		}

		if (isset($this->request->post['category_search_words'])) {
			$this->data['category_search_words'] = $this->request->post['category_search_words'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_search_words'] = $this->model_catalog_category->getCategorySearchWords($this->request->get['category_id']);
		} else {
			$this->data['category_search_words'] = [];
		}

		if (isset($this->request->post['category_menu_content'])) {
			$this->data['category_menu_content'] = $this->request->post['category_menu_content'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['category_menu_content'] = $this->model_catalog_category->getCategoryMenuContent($this->request->get['category_id']);
		} else {
			$this->data['category_menu_content'] = [];
		}

		$this->load->model('kp/reward');
		if (isset($this->request->post['reward'])) {
			$this->data['rewards'] = $this->request->post['reward'];
		} elseif (isset($this->request->get['category_id'])) {
			$this->data['rewards'] = $this->model_kp_reward->getEntityRewards($this->request->get['category_id'], 'c');
		} else {
			$this->data['rewards'] = [];
		}

		$this->load->model('tool/image');

		foreach ($this->data['languages'] as $language){
			if (isset($this->data['category_menu_content'][$language['language_id']])){
				foreach ($this->data['category_menu_content'][$language['language_id']] as &$content){
					$content['thumb'] = $this->model_tool_image->resize($content['image'], 100, 100);
				}									
			}
		}

		$this->data['openai_category_descripion_requests'] = [];
		$this->data['openai_alternatenames_requests'] = [];
		
		foreach ($this->data['languages'] as $language){
			if (isset($this->data['category_description'][$language['language_id']])){
				if ($this->config->get('config_openai_category_descriptions_query_' . $language['code'])){
					${'config_openai_category_descriptions_query_' . $language['code']} = prepareEOLArray($this->config->get('config_openai_category_descriptions_query_' . $language['code']));
					$this->data['openai_category_descripion_requests'][$language['code']] = [];

					foreach (${'config_openai_category_descriptions_query_' . $language['code']} as $line){
						$this->data['openai_category_descripion_requests'][$language['code']][] = sprintf($line, $this->data['category_description'][$language['language_id']]['name'], mb_strtoupper($language['code']));
					}					 					
				}

				if ($this->config->get('config_openai_category_alternatenames_query_' . $language['code'])){
					${'config_openai_category_alternatenames_query_' . $language['code']} = prepareEOLArray($this->config->get('config_openai_category_alternatenames_query_' . $language['code']));
					$this->data['openai_alternatenames_requests'][$language['code']] = [];

					foreach (${'config_openai_category_alternatenames_query_' . $language['code']} as $line){
						$this->data['openai_alternatenames_requests'][$language['code']][] = sprintf($line, $this->data['category_description'][$language['language_id']]['name'], mb_strtoupper($language['code']));
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

		if (isset($this->request->post['amazon_can_get_full'])) {
			$this->data['amazon_can_get_full'] = $this->request->post['amazon_can_get_full'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_can_get_full'] = $category_info['amazon_can_get_full'];
		} else {
			$this->data['amazon_can_get_full'] = false;
		}

		$this->data['amazon_category_full_information'] = false;

		if (isset($this->request->post['amazon_category_id'])) {
			$this->data['amazon_category_id'] = $this->request->post['amazon_category_id'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_category_id'] 				= $category_info['amazon_category_id'];
			$this->data['amazon_category_full_information'] = $this->rainforestAmazon->categoryParser->getAmazonCategoryInfo($category_info['amazon_category_id']);
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

		if (isset($this->request->post['hotline_category_name'])) {
			$this->data['hotline_category_name'] = $this->request->post['hotline_category_name'];
		} elseif (!empty($category_info)) {
			$this->data['hotline_category_name'] = $category_info['hotline_category_name'];
		} else {
			$this->data['hotline_category_name'] = '';
		}

		if (isset($this->request->post['hotline_enable'])) {
			$this->data['hotline_enable'] = $this->request->post['hotline_enable'];
		} elseif (!empty($category_info)) {
			$this->data['hotline_enable'] = $category_info['hotline_enable'];
		} else {
			$this->data['hotline_enable'] = '0';
		}

		if (isset($this->request->post['amazon_last_sync'])) {
			$this->data['amazon_last_sync'] = $this->request->post['amazon_last_sync'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_last_sync'] = $category_info['amazon_last_sync'];
		} else {
			$this->data['amazon_last_sync'] = '0000-00-00';
		}

		if (isset($this->request->post['amazon_synced'])) {
			$this->data['amazon_synced'] = $this->request->post['amazon_synced'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_synced'] = $category_info['amazon_synced'];
		} else {
			$this->data['amazon_synced'] = '0';
		}

		if (isset($this->request->post['amazon_overprice_rules'])) {
			$this->data['amazon_overprice_rules'] = $this->request->post['amazon_overprice_rules'];
		} elseif (!empty($category_info)) {
			$this->data['amazon_overprice_rules'] = $category_info['amazon_overprice_rules'];
		} else {
			$this->data['amazon_overprice_rules'] = '';
		}

		if (isset($this->request->post['overload_max_wc_multiplier'])) {
			$this->data['overload_max_wc_multiplier'] = $this->request->post['overload_max_wc_multiplier'];
		} elseif (!empty($category_info)) {
			$this->data['overload_max_wc_multiplier'] = $category_info['overload_max_wc_multiplier'];
		} else {
			$this->data['overload_max_wc_multiplier'] = '0';
		}

		if (isset($this->request->post['overload_max_multiplier'])) {
			$this->data['overload_max_multiplier'] = $this->request->post['overload_max_multiplier'];
		} elseif (!empty($category_info)) {
			$this->data['overload_max_multiplier'] = $category_info['overload_max_multiplier'];
		} else {
			$this->data['overload_max_multiplier'] = '0';
		}

		if (isset($this->request->post['overload_ignore_volumetric_weight'])) {
			$this->data['overload_ignore_volumetric_weight'] = $this->request->post['overload_ignore_volumetric_weight'];
		} elseif (!empty($category_info)) {
			$this->data['overload_ignore_volumetric_weight'] = $category_info['overload_ignore_volumetric_weight'];
		} else {
			$this->data['overload_ignore_volumetric_weight'] = '0';
		}

		if (isset($this->request->post['overprice'])) {
			$this->data['overprice'] = $this->request->post['overprice'];
		} elseif (!empty($category_info)) {
			$this->data['overprice'] = $category_info['overprice'];
		} else {
			$this->data['overprice'] = '';
		}

		if (isset($this->request->post['need_reprice'])) {
			$this->data['need_reprice'] = $this->request->post['need_reprice'];
		} elseif (!empty($category_info)) {
			$this->data['need_reprice'] = $category_info['need_reprice'];
		} else {
			$this->data['need_reprice'] = '';
		}

		if (isset($this->request->post['need_reprice'])) {
			$this->data['need_reprice'] = $this->request->post['need_reprice'];
		} elseif (!empty($category_info)) {
			$this->data['need_reprice'] = $category_info['need_reprice'];
		} else {
			$this->data['need_reprice'] = '';
		}

		if (isset($this->request->post['need_special_reprice'])) {
			$this->data['need_special_reprice'] = $this->request->post['need_special_reprice'];
		} elseif (!empty($category_info)) {
			$this->data['need_special_reprice'] = $category_info['need_special_reprice'];
		} else {
			$this->data['need_special_reprice'] = '';
		}

		if (isset($this->request->post['special_reprice_plus'])) {
			$this->data['special_reprice_plus'] = $this->request->post['special_reprice_plus'];
		} elseif (!empty($category_info)) {
			$this->data['special_reprice_plus'] = $category_info['special_reprice_plus'];
		} else {
			$this->data['special_reprice_plus'] = '';
		}

		if (isset($this->request->post['special_reprice_minus'])) {
			$this->data['special_reprice_minus'] = $this->request->post['special_reprice_minus'];
		} elseif (!empty($category_info)) {
			$this->data['special_reprice_minus'] = $category_info['special_reprice_minus'];
		} else {
			$this->data['special_reprice_minus'] = '';
		}

		if (isset($this->request->post['last_reprice'])) {
			$this->data['last_reprice'] = $this->request->post['last_reprice'];
		} elseif (!empty($category_info)) {
			$this->data['last_reprice'] = $category_info['last_reprice'];
		} else {
			$this->data['last_reprice'] = '';
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

		if (isset($this->request->post['exclude_from_intersections'])) {
			$this->data['exclude_from_intersections'] = $this->request->post['exclude_from_intersections'];
		} elseif (!empty($category_info)) {
			$this->data['exclude_from_intersections'] = $category_info['exclude_from_intersections'];
		} else {
			$this->data['exclude_from_intersections'] = 0;
		}

		if (isset($this->request->post['virtual_parent_id'])) {
			$this->data['virtual_parent_id'] = $this->request->post['virtual_parent_id'];
		} elseif (!empty($category_info)) {
			$this->data['virtual_parent_id'] = $category_info['virtual_parent_id'];
		} else {
			$this->data['virtual_parent_id'] = '-1';
		}

        $this->data['config_rainforest_main_formula_count'] = $this->config->get('config_rainforest_main_formula_count');
        for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){
            	$this->data['config_rainforest_main_formula_overload_' . $crmfc] 	= $this->config->get('config_rainforest_main_formula_overload_' . $crmfc);
             	$this->data['config_rainforest_main_formula_min_' . $crmfc] 		= $this->config->get('config_rainforest_main_formula_min_' . $crmfc);
             	$this->data['config_rainforest_main_formula_max_' . $crmfc] 		= $this->config->get('config_rainforest_main_formula_max_' . $crmfc);
             	$this->data['config_rainforest_main_formula_default_' . $crmfc] 	= $this->config->get('config_rainforest_main_formula_default_' . $crmfc);           
             	$this->data['config_rainforest_main_formula_multiplier_' . $crmfc] 	= $this->rainforestAmazon->offersParser->PriceLogic->getMainMultiplier($this->config->get('config_rainforest_main_formula_overload_' . $crmfc));           
        }

		if (isset($this->request->post['category_overprice_rules'])) {
			$this->data['category_overprice_rules'] = $this->request->post['category_overprice_rules'];
		} elseif (isset($this->request->get['category_id'])) {		
			$this->data['category_overprice_rules'] = $this->model_catalog_category->getCategoryOverpriceRules($this->request->get['category_id']);
		} else {
			$this->data['category_overprice_rules'] = [];
		}

		$this->load->model('catalog/filter');

		if (isset($this->request->post['category_filter'])) {
			$filters = $this->request->post['category_filter'];
		} elseif (isset($this->request->get['category_id'])) {		
			$filters = $this->model_catalog_category->getCategoryFilters($this->request->get['category_id']);
		} else {
			$filters = [];
		}

		$this->data['category_filters'] = [];

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
			$actions = [];
		}

		$this->data['category_actions'] = [];

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
			$this->data['keyword'] = [];
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

		if (isset($this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && $category_info['image']) {
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

		if (isset($this->request->post['homepage'])) {
			$this->data['homepage'] = $this->request->post['homepage'];
		} elseif (!empty($category_info)) {
			$this->data['homepage'] = $category_info['homepage'];
		} else {
			$this->data['homepage'] = 0;
		}

		if (isset($this->request->post['special'])) {
			$this->data['special'] = $this->request->post['special'];
		} elseif (!empty($category_info)) {
			$this->data['special'] = $category_info['special'];
		} else {
			$this->data['special'] = 0;
		}

		if (isset($this->request->post['popular'])) {
			$this->data['popular'] = $this->request->post['popular'];
		} elseif (!empty($category_info)) {
			$this->data['popular'] = $category_info['popular'];
		} else {
			$this->data['popular'] = 0;
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
			$this->data['category_layout'] = [];
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

		$this->data['attributes_category'] = [];
		if (isset($this->request->get['category_id'])) {
			$this->data['attributes_category'] = $this->model_catalog_category->getAttributesByCategory($this->request->get['category_id']);
		}

		$this->data['attributes_similar_category'] = [];
		if (isset($this->request->get['category_id'])) {
			$this->data['attributes_similar_category'] = $this->model_catalog_category->getAttributesSimilarByCategory($this->request->get['category_id']);
		}

		$this->load->model('catalog/category');

		if (isset($this->request->post['related_category'])) {
			$related_categories = $this->request->post['related_category'];
		} elseif (isset($this->request->get['category_id'])) {		
			$related_categories = $this->model_catalog_category->getRelatedCategories($this->request->get['category_id']);
		} else {
			$related_categories = [];
		}

		$this->data['related_categories'] = [];

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


	public function getAmazonCategoriesCSV(){
		
		header( 'Content-Type: text/csv charset=utf-8' );
		header("Content-Disposition: attachment; filename=amazon_categories_". date('Y_m_d') .".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$file = fopen('php://output', 'w');

		if ($this->config->get('config_rainforest_category_model') == 'standard'){
			$query = $this->db->query("SELECT * FROM category_amazon_tree ORDER BY full_name ASC");
		}

		if ($this->config->get('config_rainforest_category_model') == 'bestsellers'){
			$query = $this->db->query("SELECT * FROM category_amazon_bestseller_tree ORDER BY full_name ASC");
		}
		
		foreach ($query->rows as $row){
			fputcsv($file, $row);			
		}

		fclose($file);
		
	}

	public function google_autocomplete() {
		$json = [];

		if (isset($this->request->get['filter_name']) && mb_strlen($this->request->get['filter_name']) > 2) {
			$this->load->model('catalog/category');
			$results = $this->model_catalog_category->getGoogleCategoryByName(trim($this->request->get['filter_name']));

			foreach ($results as $result) {
				$json[] = array(
					'google_base_category_id' => $result['google_base_category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}		

	public function amazon_info() {	
		$this->load->model('catalog/category');

		$json = [];

		$result = $this->model_catalog_category->getCategory($this->request->get['category_id']);

		if (!empty($result['amazon_category_id'])){
			$json = ['amazon_category_id' => $result['amazon_category_id']];
		}
		
		$this->response->setOutput(json_encode($json));
	}			

	public function amazon_info_by_amazon_id() {	
		$this->load->model('catalog/category');

		$json = [];

		$category_id = $this->model_catalog_category->getCategoryIdByAmazonCategoryId($this->request->get['amazon_category_id']);

		if ($category_id){
			$data = array(
				'filter_category_id' 	=> $category_id,
				'start'       			=> 0,
				'limit'       			=> 1
			);

			$results = $this->model_catalog_category->getCategories($data);

			if (!empty($results[0])){
				$json = [
					'category_id' 	=> $results[0]['category_id'], 
					'name' 			=> strip_tags(html_entity_decode($results[0]['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}	

	public function yandex_autocomplete() {	
		$this->load->model('catalog/category');

		$json = [];

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

	public function hotline_autocomplete() {	
		$this->load->model('catalog/category');

		$json = [];

		$results = $this->model_catalog_category->getHotlineCategories($this->request->get['filter_name']);

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


	public function amazon_autocomplete() {				
		$json = [];

		if ($this->config->get('config_rainforest_enable_api') && isset($this->request->get['filter_name']) && mb_strlen($this->request->get['filter_name']) > 3) {

			if (!empty($this->request->get['type'])){
				$type = $this->request->get['type'];
			} else {
				$type = $this->config->get('config_rainforest_category_model');
			}

			$queryString = http_build_query([
				'api_key' 		=> $this->config->get('config_rainforest_api_key'),
				'amazon_domain' => $this->config->get('config_rainforest_api_domain_1'),
				'search_term'	=> $this->request->get['filter_name'],
				'type'			=> $type
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

	public function autocomplete_final() {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$data = array(
				'filter_name' 	=> trim($this->request->get['filter_name']),
				'filter_final' 	=> true,
				'start'       	=> 0,
				'limit'       	=> 30
			);

			$results = $this->model_catalog_category->getCategories($data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}	

	public function autocomplete() {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$data = array(
				'filter_name' => trim($this->request->get['filter_name']),
				'start'       => 0,
				'limit'       => 30
			);

			$results = $this->model_catalog_category->getCategories($data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}		


	public function productsAutocomplete(){
		$json=array();

		if(isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])){

			$this->load->model('catalog/product');

			if(isset($this->request->get['filter_name'])){
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if(isset($this->request->get['filter_model'])){
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if(isset($this->request->get['filter_category_id'])){
				$filter_category=$this->request->get['filter_category_id'];
			} else {
				$filter_category='';
			}

			if(isset($this->request->get['limit'])){
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5000;
			}

			$filter_data=array(
				'filter_category_id'=>$filter_category,
				'filter_name'=>$filter_name,
				'filter_model'=>$filter_model,
				'start'=>0,
				'limit'=>$limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach($results as $result){
				$json[]=array(
					'product_id'=>$result['product_id'],
					'name'=>strip_tags(html_entity_decode($result['name'],ENT_QUOTES,'UTF-8')),
					'model'=>$result['model'],
					'price'=>$result['price']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}	
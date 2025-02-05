<?php


class ControllerModuleSet extends Controller {
	private $error = array();
	private $products_from_error = array();
    
    private $_name = 'set';

	public function index() {
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/set');
		} else {
			$this->load->language('module/set');
		}
	
		$this->load->model('catalog/set');
	
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
	
		$this->load->model('setting/setting');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('set', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
		}
	
		$this->getModule();
	}

	public function insert() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/set');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_set->addSet($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/set/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->getForm();
	}

	public function update() {
        $this->load->model('catalog/set');


        if (isset($this->request->post['copy_set']) && $this->request->post['copy_set']) {
            $setId = $this->request->get['set_id'];
            $personsCount = $this->request->post['copy_set_persones'];

            $prId = $this->model_catalog_set->addNewSetProduct($this->request->post);
            $newSetId = $this->model_catalog_set->copySet($setId, $personsCount, $prId);
            $this->redirect($this->url->link('module/set/update', 'token=' . $this->session->data['token']."&set_id=".$newSetId, 'SSL'));
        }


		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	

		$this->document->setTitle($this->language->get('heading_title'));
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {



			$this->model_catalog_set->editSet($this->request->get['set_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/set/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->getForm();
	}
	
	public function delete() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/set');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $set_id) {
				$this->model_catalog_set->deleteSet($set_id);
			}
		
			$this->session->data['success'] = $this->language->get('text_success');
		
			$this->redirect($this->url->link('module/set/listing', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->getList();
	}

	public function listing() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
	
		$this->getList();
	}

	private function getModule() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/set');
	
		$this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_place'] = $this->language->get('entry_place');
        $this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
        $this->data['entry_checkcategory'] = $this->language->get('entry_checkcategory');
		$this->data['entry_position'] = $this->language->get('entry_position');
        $this->data['entry_quantityshow'] = $this->language->get('entry_quantityshow');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_image_size_product_page'] = $this->language->get('entry_image_size_product_page');
        $this->data['entry_image_size_product_page_card'] = $this->language->get('entry_image_size_product_page_card');
	    
        
        $this->data['text_before_tabs'] = $this->language->get('text_before_tabs');
        $this->data['text_in_tabs'] = $this->language->get('text_in_tabs');   
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_module_settings'] = $this->language->get('text_module_settings');
	
		$this->data['button_sets'] = $this->language->get('button_sets');
		$this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_save_stay'] = $this->language->get('button_save_stay');
        $this->data['button_save_go'] = $this->language->get('button_save_go');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
	
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
			'href'      => $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
	
		$this->data['sets'] = $this->url->link('module/set/listing', 'token=' . $this->session->data['token']);
	
		$this->data['action'] = $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token']);
	
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
	
		$this->data['modules'] = array();
	
		if (isset($this->request->post[$this->_name . '_module'])) {
			$this->data['modules'] = $this->request->post[$this->_name . '_module'];
		} elseif ($this->config->get($this->_name . '_module')) { 
			$this->data['modules'] = $this->config->get($this->_name . '_module');
		}


		if (isset($this->request->post[$this->_name . '_place_product_page'])) {
			$this->data['set_place_product_page'] = $this->request->post[$this->_name . '_place_product_page'];
		} elseif ($this->config->get($this->_name . '_place_product_page')) { 
			$this->data['set_place_product_page'] = $this->config->get($this->_name . '_place_product_page');
		} else {
		    $this->data['set_place_product_page'] = ''; 
		}
        
		if (isset($this->request->post[$this->_name . '_product_page_image_width'])) {
			$this->data['set_product_page_image_width'] = $this->request->post[$this->_name . '_product_page_image_width'];
		} elseif ($this->config->get($this->_name . '_product_page_image_width')) { 
			$this->data['set_product_page_image_width'] = $this->config->get($this->_name . '_product_page_image_width');
		} else {
		    $this->data['set_product_page_image_width'] = 110;   
		}
		if (isset($this->request->post[$this->_name . '_product_page_image_height'])) {
			$this->data['set_product_page_image_height'] = $this->request->post[$this->_name . '_product_page_image_height'];
		} elseif ($this->config->get($this->_name . '_product_page_image_height')) { 
			$this->data['set_product_page_image_height'] = $this->config->get($this->_name . '_product_page_image_height');
		} else {
		    $this->data['set_product_page_image_height'] = 110;
		}
        
		if (isset($this->request->post[$this->_name . '_product_page_card_image_width'])) {
			$this->data['set_product_page_card_image_width'] = $this->request->post[$this->_name . '_product_page_card_image_width'];
		} elseif ($this->config->get($this->_name . '_product_page_card_image_width')) { 
			$this->data['set_product_page_card_image_width'] = $this->config->get($this->_name . '_product_page_card_image_width');
		} else {
		    $this->data['set_product_page_card_image_width'] = 110;  
		}
		if (isset($this->request->post[$this->_name . '_product_page_card_image_height'])) {
			$this->data['set_product_page_card_image_height'] = $this->request->post[$this->_name . '_product_page_card_image_height'];
		} elseif ($this->config->get($this->_name . '_product_page_card_image_height')) { 
			$this->data['set_product_page_card_image_height'] = $this->config->get($this->_name . '_product_page_card_image_height');
		} else {
		    $this->data['set_product_page_card_image_height'] = 110;
		}                        
        
	
		$this->load->model('design/layout');
	
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
	
		$this->template = 'module/set.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
		$this->response->setOutput($this->render());
	}

	private function getList() {
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/set');
	   

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = null;
		}        
		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = null;
		}         

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sd.name';
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
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;        
        
        $url = '';       

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
        
		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
        
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}                
	
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
            	
        if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}       
       
       
       
		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
			
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_product_in_set'] = $this->language->get('column_product_in_set');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
	
		$this->data['button_module'] = $this->language->get('button_module');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_filter'] = $this->language->get('button_filter');
        $this->data['button_clearfilter'] = $this->language->get('button_clearfilter');
        
        
        
        
	
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
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
        
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
           
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);                   
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/set/listing', 'token=' . $this->session->data['token'] . $url),
			'text'      => $this->language->get('heading_listing'),
			'separator' => ' :: '
		);
	
		$this->data['module'] = $this->url->link('module/set', 'token=' . $this->session->data['token']);
		$this->data['insert'] = $this->url->link('module/set/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] = $this->url->link('module/set/delete', 'token=' . $this->session->data['token'] . $url);
        $this->data['token']  = $this->session->data['token'];
        
        $url = '';       

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
        
		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
        
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}                
	
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
            	
        if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}        
                
        $this->data['sort_name'] = $this->url->link('module/set/listing', 'token=' . $this->session->data['token'] . '&sort=sd.name' . $url);       
	
		$this->load->model('tool/image');
	
		$this->data['sets'] = array();
        
        $data = array(
			'filter_name'	      => $filter_name,
            'filter_product_name' => $filter_product, 
			'filter_product_id'	  => $filter_product_id, 
			'filter_status'       => $filter_status,
			'sort'                => $sort,
			'order'               => $order,        
			'start'           	  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           	  => $this->config->get('config_admin_limit')        
        );
        
        $set_total = $this->model_catalog_set->getTotalSets($data);	
		$results = $this->model_catalog_set->getSets($data);
	
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/set/update', 'token=' . $this->session->data['token'] . $url .  '&set_id=' . $result['set_id'], 'SSL')
			);
		
			$this->data['sets'][] = array(
				'set_id'     	=> $result['set_id'],
				'title'       	=> $result['name'],
                'price'         => $result['price'],
                'products'      => $this->model_catalog_set->getProductsInSets($result['set_id']),
				'date_added'  	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'     	=> ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'    	=> isset($this->request->post['selected']) && in_array($result['news_id'], $this->request->post['selected']),
				'action'      	=> $action,
				'count_p'       => $result['count_persone']
			);
		}

        $url = '';       

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
        
		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . $this->request->get['filter_product'];
		}
        
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}                
	
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $set_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/set/listing', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
        
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_product'] = $filter_product;
		$this->data['filter_product_id'] = $filter_product_id;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;        
        
        
	
		$this->template = 'module/set/list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
		$this->response->setOutput($this->render());
	}

	private function getForm() { 
	
		if ((VERSION == '1.5.5') || (substr(VERSION, 0, -2) == '1.5.5')) {
			$this->language->load('module/' . $this->_name);
		} else {
			$this->load->language('module/' . $this->_name);
		}
	
		$this->load->model('catalog/set');
	
		$this->data['heading_title'] = $this->language->get('heading_title');
	
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_default'] = $this->language->get('text_default');
        $this->data['text_total_set'] = $this->language->get('text_total_set');
        $this->data['text_remove'] = $this->language->get('text_remove');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_enable_productcard'] = $this->language->get('text_enable_productcard');
        $this->data['text_coption'] = $this->language->get('text_coption');
        $this->data['text_qoption'] = $this->language->get('text_qoption');
        $this->data['text_selected'] = $this->language->get('text_selected');
	       
        $this->data['entry_setimage'] = $this->language->get('entry_setimage');
        $this->data['entry_setcategory'] = $this->language->get('entry_setcategory');   
		$this->data['entry_setname'] = $this->language->get('entry_setname');
		$this->data['entry_setdescription'] = $this->language->get('entry_setdescription');
        $this->data['entry_category'] = $this->language->get('entry_category');
        $this->data['entry_product_set'] = $this->language->get('entry_product_set');
        $this->data['entry_product_set_search'] = $this->language->get('entry_product_set_search'); 
		$this->data['entry_choosecat'] = $this->language->get('entry_choosecat');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_store'] = $this->language->get('entry_store');
        $this->data['entry_tax_class'] = $this->language->get('entry_tax_class');

        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_current_price'] = $this->language->get('column_current_price');
        $this->data['column_price_in_set'] = $this->language->get('column_price_in_set');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_present'] = $this->language->get('column_present');
        $this->data['column_show_in_product'] = $this->language->get('column_show_in_product');
        $this->data['column_sort_order'] = $this->language->get('column_sort_order');
        $this->data['column_remove'] = $this->language->get('column_remove');
	
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_cost_set'] = $this->language->get('button_cost_set');
        $this->data['button_add'] = $this->language->get('button_add');
        
        $this->data['tab_set'] = $this->language->get('tab_set');
        $this->data['tab_product'] = $this->language->get('tab_product');
        
        
	    $this->data['error_add'] = $this->language->get('error_add');
        $this->data['error_select'] = $this->language->get('error_select');
        $this->data['error_count'] = $this->language->get('error_count');
        $this->data['error_price'] = $this->language->get('error_price');
        $this->data['error_quantity'] = $this->language->get('error_quantity');
        $this->data['error_total'] = $this->language->get('error_total');
        
               
        $this->data['info_set_product'] = $this->language->get('info_set_product');
        $this->data['info_set_name'] = $this->language->get('info_set_name');
        $this->data['info_set_description'] = $this->language->get('info_set_description');
        $this->data['info_sort_order'] = $this->language->get('info_sort_order');
        
           
		$this->data['token'] = $this->session->data['token'];
	
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
            $this->data['error_warning'] = $this->language->get('error_formarea');
		} else {
			$this->data['error_name'] = '';
		}
        
		if (isset($this->error['productname'])) {
			$this->data['error_productname'] = $this->error['productname'];
            $this->data['error_warning'] = $this->language->get('error_formarea');
		} else {
			$this->data['error_productname'] = '';
		}        

		if (isset($this->error['error_total'])) {
			$this->data['error_total'] = $this->error['error_total'];
		} else {
			$this->data['error_total'] = '';
		}

		if (isset($this->error['error_no_products'])) {
			$this->data['error_no_products'] = $this->error['error_no_products'];
		} else {
			$this->data['error_no_products'] = '';
		}

        $url = '';       
		
        if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
           
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/' . $this->_name, 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);                   
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/set/listing', 'token=' . $this->session->data['token'] . $url),
			'text'      => $this->language->get('heading_listing'),
			'separator' => ' :: '
		);
        
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('module/set/listing', 'token=' . $this->session->data['token'] . $url),
			'text'      => $this->language->get('heading_setsedit'),
			'separator' => ' :: '
		);
                
	
		if (!isset($this->request->get['set_id'])) {
			$this->data['action'] = $this->url->link('module/set/insert', 'token=' . $this->session->data['token'] . $url);
		} else {
			$this->data['action'] = $this->url->link('module/set/update', 'token=' . $this->session->data['token'] . $url . '&set_id=' . $this->request->get['set_id']);
		}
	
		$this->data['cancel'] = $this->url->link('module/set/listing', 'token=' . $this->session->data['token'] . $url);
	
		if ((isset($this->request->get['set_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$set_info = $this->model_catalog_set->getSet($this->request->get['set_id']);
		}
	
		$this->load->model('localisation/language');
	
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
	
		if (isset($this->request->post['set_description'])) {
			$this->data['set_description'] = $this->request->post['set_description'];
		} elseif (isset($this->request->get['set_id'])) {
			$this->data['set_description'] = $this->model_catalog_set->getSetDescriptions($this->request->get['set_id']);
		} else {
			$this->data['set_description'] = array();
		}

		$this->load->model('catalog/category');
				
		$categories = $this->model_catalog_set->getAllCategories();

		$this->data['categories'] = $this->getAllCategories($categories);
        
		if (isset($this->request->post['set_category'])) {
			$this->data['set_categories'] = $this->request->post['set_category'];
		} elseif (isset($this->request->get['set_id'])) {		
			$this->data['set_categories'] = $this->model_catalog_set->getSetCategories($this->request->get['set_id']);
		} else {
			$this->data['set_categories'] = array();
		}
        
        if($this->products_from_error){
            $products = $this->products_from_error; 
		} elseif (isset($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (isset($this->request->get['set_id'])) {
			$products = $this->model_catalog_set->getProductsInSets($this->request->get['set_id']);
		} else {
			$products = array();
		} 
        
        $this->data['products'] = array();

        $factPrice = 0;

        foreach($products as $product) {
            $this->data['products'][] = array(
                'product_id'      => $product['product_id'],
                'name'            => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
                'price'           => $product['price'],
                'options'         => isset($product['options']) ? $product['options'] : array(),
                'price_in_set'    => $product['price_in_set'],
                'present'         => isset($product['present']) ? $product['present'] : 0,
                'show_in_product' => isset($product['show_in_product']) ? $product['show_in_product'] : 0,
                'quantity'        => $product['quantity'],
                'sort_order'      => $product['sort_order'],
                'error_price_in_set' => isset($product['error_price_in_set']) ? $product['error_price_in_set'] : false,
                'error_quantity'  =>  isset($product['error_quantity']) ? $product['error_quantity'] : false
            );

            $factPrice += ($product['price_in_set'] * $product['quantity']);
        }

		if (isset($this->request->post['total_set'])) {
			$this->data['total_set'] = $this->request->post['total_set'];
		} elseif (isset($set_info)) {
			$this->data['total_set'] = $set_info['price'];
		} else {
			$this->data['total_set'] = '';
		}

		if (isset($this->request->post['percent'])) {
			$this->data['percent'] = $this->request->post['percent'];
		} elseif (isset($set_info)) {
			$this->data['percent'] = $set_info['percent'];
		} else {
			$this->data['percent'] = '';
		}

        $this->data['factPrice'] = $factPrice;


		if (isset($this->request->post['set_image'])) {
			$this->data['set_image'] = $this->request->post['set_image'];
		} elseif (isset($set_info)) {
			$this->data['set_image'] = $set_info['image'];
		} else {
			$this->data['set_image'] = '';
		}
		$this->load->model('tool/image');
		
		if (isset($this->request->post['set_image'])) {
			$this->data['set_thumb'] = $this->model_tool_image->resize($this->request->post['set_image'], 100, 100);
		} elseif (!empty($set_info) && $set_info['image']) {
			$this->data['set_thumb'] = $this->model_tool_image->resize($set_info['image'], 100, 100);
		} else {
			$this->data['set_thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}        
        
                
		if (isset($this->request->post['enable_productcard'])) {
			$this->data['enable_productcard'] = $this->request->post['enable_productcard'];
		} elseif (isset($set_info)) {
			$this->data['enable_productcard'] = $set_info['enable_productcard'];
		} else {
			$this->data['enable_productcard'] = '';
		}        
        	
		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (isset($set_info)) {
			$this->data['product_id'] = $set_info['product_id'];
		} else {
			$this->data['product_id'] = 0;
		}
	
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($set_info)) {
			$this->data['status'] = $set_info['status'];
		} else {
			$this->data['status'] = -1;
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($set_info)) {
			$this->data['sort_order'] = $set_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}


        if (isset($this->request->post['count_persone'])) {
			$this->data['count_persone'] = $this->request->post['count_persone'];
		} elseif (isset($set_info)) {
			$this->data['count_persone'] = $set_info['count_persone'];
		} else {
			$this->data['count_persone'] = '';
		}

        if (isset($this->request->post['set_group'])) {
			$this->data['set_group'] = $this->request->post['set_group'];
		} elseif (isset($set_info)) {
			$this->data['set_group'] = $set_info['set_group'];
		} else {
			$this->data['set_group'] = '';
		}






		if (isset($this->request->post['set_store'])) {
			$this->data['set_store'] = $this->request->post['set_store'];
		} elseif (isset($this->request->get['set_id'])) {
			$this->data['set_store'] = $this->model_catalog_set->getSetStores($this->request->get['set_id']);
		} else {
			$this->data['set_store'] = array(0);
		}
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
    	
		if (isset($this->request->post['tax_class_id'])) {
      		$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
    	} elseif (!empty($set_info)) {
			$this->data['tax_class_id'] = $set_info['tax_class_id'];
		} else {
      		$this->data['tax_class_id'] = 0;
    	}
        
//productcard
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_main_category'] = $this->language->get('entry_main_category');
		$this->data['entry_seo_title'] = $this->language->get('entry_seo_title');
		$this->data['entry_seo_h1'] = $this->language->get('entry_seo_h1');
				
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
        
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
    	$this->data['tab_image'] = $this->language->get('tab_image');		
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_design'] = $this->language->get('tab_design');        


		if ($this->data['product_id'] && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->data['product_id']);
    	}
		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif ($this->data['product_id']) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->data['product_id']);
		} else {
			$this->data['product_description'] = array();
		}
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$this->data['keyword'] = $product_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['price'])) {
			$this->data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$this->data['price'] = $product_info['price'];
		} else {
			$this->data['price'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$this->data['image'] = $product_info['image'];
		} else {
			$this->data['image'] = 'no_image.jpg';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && $product_info['image']) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
        if (isset($this->request->post['date_available'])) {
       		$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}
        
		if (isset($this->request->post['product_sort_order'])) {
      		$this->data['product_sort_order'] = $this->request->post['product_sort_order'];
    	} elseif (!empty($product_info)) {
      		$this->data['product_sort_order'] = $product_info['sort_order'];
    	} else {
			$this->data['product_sort_order'] = 1;
		}

		$this->load->model('catalog/manufacturer');
		
    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

    	if (isset($this->request->post['manufacturer_id'])) {
      		$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
      		$this->data['manufacturer_id'] = 0;
    	} 

		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = $this->request->post['product_category'];
		} elseif ($this->data['product_id']) {
			$this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->data['product_id']);
		} else {
			$this->data['product_category'] = array();
		}
		
		if (isset($this->request->post['product_related'])) {
			$products_rel = $this->request->post['product_related'];
		} elseif (isset($this->request->get['set_id'])) {		
			$products_rel = $this->model_catalog_product->getProductRelated($this->request->get['set_id']);
		} else {
			$products_rel = array();
		}
		$this->data['product_related'] = array();
		foreach ($products_rel as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);
			if ($related_info) {
				$this->data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif ($this->data['product_id']) {
			$product_images = $this->model_catalog_product->getProductImages($this->data['product_id']);
		} else {
			$product_images = array();
		}
		$this->data['product_images'] = array();
		foreach ($product_images as $product_image) {
			$this->data['product_images'][] = array(
				'image'      => $product_image['image'],
				'thumb'      => $this->model_tool_image->resize($product_image['image'], 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['product_layout'])) {
			$this->data['product_layout'] = $this->request->post['product_layout'];
		} elseif ($this->data['product_id']) {
			$this->data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->data['product_id']);
		} else {
			$this->data['product_layout'] = array();
		}
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();                
	
		$this->template = 'module/set/form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
	
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/set')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/set')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		foreach ($this->request->post['set_description'] as $language_id => $value) {
			if ((strlen($value['name']) < 3) || (strlen($value['name']) > 250)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
        if(isset($this->request->post['enable_productcard'])){
    		foreach ($this->request->post['product_description'] as $language_id => $value) {
    			if ((strlen($value['name']) < 3) || (strlen($value['name']) > 250)) {
    				$this->error['productname'][$language_id] = $this->language->get('error_name');
    			}
    		}            
        }
        
        
        if(!$this->request->post['total_set']){
            $this->error['error_total'] = $this->language->get('error_total');
        }        
        
        
        if(!isset($this->request->post['product'])||count($this->request->post['product'])<2){
            $this->error['error_no_products'] = $this->language->get('error_no_products');
        } else {
            $i = 0;
            foreach($this->request->post['product'] as $product){
                if(((float)$product['price_in_set']<=0&&!isset($product['present'])) || ((float)$product['price_in_set']>(float)$product['price'])){
                    $this->error['product_price_in_set'][$i] = true;
                    $product_price_in_set_error = true;
                } else {
                    $product_price_in_set_error = false;
                }
                if(!(int)$product['quantity']){
                    $this->error['product_quantity'][$i] = true;
                }
                
                $this->products_from_error[] = array(
                    'product_id'         => $product['product_id'],
                    'name'               => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
                    'price'              => $product['price'],
                    'price_in_set'       => $product['price_in_set'],
                    'present'            => isset($product['present']) ? $product['present'] : 0,
                    'show_in_product'    => isset($product['show_in_product']) ? $product['show_in_product'] : 0,
                    'quantity'           => $product['quantity'],
                    'sort_order'         => $product['sort_order'],
                    'error_price_in_set' => $product_price_in_set_error ? true : false,
                    'error_quantity'     => !(int)$product['quantity'] ? true : false            
                );
                $i++;
            }    
        }
	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/set')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function category() {
		$this->load->model('catalog/product');
        $this->load->model('catalog/set');
		
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}
		
		$product_data = array();
		
		$results = $this->model_catalog_product->getProductsByCategoryId($category_id);
		
		foreach ($results as $result) {
		  
            if($this->model_catalog_set->getSetByProduct($result['product_id'])){continue;}
		    
			$product_data[] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model']
			);
		}
		
		$this->response->setOutput(json_encode($product_data));
	}
    
	public function addproducts() {
		$this->load->model('catalog/product');
        $this->load->model('catalog/set');
		$this->load->model('tool/image');
		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
        
        $options_product = array();
        
        $product_options = $this->model_catalog_set->getProductOptions($product_id);
			
		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
				
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
                        'name'                    => $product_option_value['name'],
                        'image'                   => $this->model_tool_image->resize($product_option_value['image'], 40, 40),
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']	
					);						
				}
				
				$options_product[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'required'             => $product_option['required']
				);				
			} else {
			    //continue;
                
				$options_product[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);
                				
			}
		}
       
        
		
		$product_data = array(
				'product_id' => $product_info['product_id'],
				'name'       => $product_info['name'],
                'price'      => $product_info['price'],
                'options'    => $options_product
		);
		
		$this->response->setOutput(json_encode($product_data));
	}

	
	public function related() {
		$this->load->model('catalog/product');
		
		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} else {
			$products = array();
		}
	
		$product_data = array();
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$product_data[] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name'],
					'model'      => $product_info['model']
				);
			}
		}		
		
		$this->response->setOutput(json_encode($product_data));
	}  

	private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
		$output = array();

		if (array_key_exists($parent_id, $categories)) {
			if ($parent_name != '') {
				$parent_name .= $this->language->get('text_separator');
			}

			foreach ($categories[$parent_id] as $category) {
				$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
				);

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

		return $output;
	}


	public function chooseoptions() {
		
        $this->language->load('module/set');
		$json = array();
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/product');
        $this->load->model('catalog/set');
        
		$product_info = $this->model_catalog_product->getProduct($product_id);
		$product_info_special = $this->model_catalog_product->getProductSpecials($product_id);
        
		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}
			$product_options = $this->model_catalog_set->getProductOptions($this->request->post['product_id']);
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
			if (!$json) {
				$json['new_product_id'] = (int)$product_id . ':' . base64_encode(serialize($option));
                $option_data = array();
                $json['new_price'] = 0;
                if(isset($product_info_special[0]['price'])){
                	$base_price = $product_info_special[0]['price'];	
                } else {
                	$base_price = $product_info['price'];
                }
                
                $option_price = 0;
				
                foreach ($option as $product_option_id => $option_value_id) {
                    $option_info = $this->model_catalog_set->getOptionInfo($product_option_id, $option_value_id);
                    $option_data[] = $option_info['option_name'] . ': ' . $option_info['option_value_name'];
                    $option_price += $this->model_catalog_set->getOptionInfoPrice($option_value_id);
                }
                $o_str = implode('<br /> - ', $option_data);
                $json['new_product_name'] = $product_info['name'] . '<br />- <small>' . $o_str . '</small>';
                if($option_price){
                	$json['new_price'] = $base_price + $option_price;
                }

			}
		}
		$this->response->setOutput(json_encode($json));
        		
	}
    
	
	public function upload() {
		$this->language->load('product/product');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
			
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	
			
			$allowed = array();
			
			$filetypes = explode(',', $this->config->get('config_upload_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}	
						
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(mt_rand());								
				$json['file'] = $this->encryption->encrypt($file);
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			}
						
			$json['success'] = $this->language->get('text_upload');
		}	
		
		$this->response->setOutput(json_encode($json));		
	}
    
    
	public function autocomplete() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/set');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$results = $this->model_catalog_set->getSets($data);
			
			foreach ($results as $result) {
					
				$json[] = array(
					'set_id'     => $result['set_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}    
    
    
    
	public function install() { 

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "set` (
                          `set_id` int(11) NOT NULL AUTO_INCREMENT,
                          `product_id` int(11) DEFAULT NULL,
                          `image` varchar(255) DEFAULT NULL,
                          `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
                          `sort_order` int(11) NOT NULL DEFAULT '1',
                          `enable_productcard` tinyint(1) DEFAULT NULL,
                          `status` tinyint(1) NOT NULL DEFAULT '1',
                          `tax_class_id` int(11) NOT NULL,
                          `date_added` datetime NOT NULL,
                          PRIMARY KEY (`set_id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set_description`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "set_description` (
                          `set_id` int(11) NOT NULL,
                          `language_id` int(11) NOT NULL,
                          `name` varchar(255) NOT NULL,
                          `description` text,
                          PRIMARY KEY (`set_id`,`language_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"); 

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set_to_store`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "set_to_store` (
                          `set_id` int(11) NOT NULL,
                          `store_id` int(11) NOT NULL,
                          PRIMARY KEY (`set_id`,`store_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"); 

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set_to_category`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "set_to_category` (
                          `set_id` int(11) NOT NULL,
                          `category_id` int(11) NOT NULL,
                          PRIMARY KEY (`set_id`,`category_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
                        
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_to_set`");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_set` (
                          `set_id` int(11) NOT NULL,
                          `product_id` longtext NOT NULL,
                          `clean_product_id` int(11) DEFAULT NULL,
                          `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
                          `price_in_set` decimal(15,4) NOT NULL DEFAULT '0.0000',
                          `quantity` int(4) NOT NULL,
                          `present` int(2) DEFAULT NULL,
                          `show_in_product` int(2) DEFAULT NULL,
                          `sort_order` int(11) NOT NULL
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");                        
    }

	public function uninstall() { 
		$this->cache->delete('set');
        
        $this->load->model('catalog/set');
        $data = array('no_limit'=>true);
	    $sets = $this->model_catalog_set->getSets();
        
        foreach($sets as $set_id) {
            $this->model_catalog_set->deleteSet($set_id);
        }
           
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_to_set`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set_to_store`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set_to_category`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "set_description`");
        

    }
}
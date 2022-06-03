<?
	
	class ControllerSaleSegments extends Controller {
		private $error = array();
		
		private $keys = array(
		'order_count',
		'order_good_count',
		'order_bad_count',
		'total_cheque',
		'avg_cheque',
		'city',
		'gender',
		'order_first_date_from',
		'order_first_date_to',
		'order_last_date_from',
		'order_last_date_to',
		'order_good_first_date_from',
		'order_good_first_date_to',
		'order_good_last_date_from',
		'order_good_last_date_to',
		'date_added_from',
		'date_added_to',
		'birthday_from',
		'birthday_to',
		'birthday_year',
		'mail_opened',
		'has_push',
		'total_calls',
		'avg_calls_duration',
		'avg_csi',
		'csi_reject',
		'total_cheque_only_selected',
		);
		
		private $keys_explain = array(
		'order_count' => 'Заказов',
		'order_good_count' => 'Выполненных',
		'order_bad_count' => 'Отмен',
		'total_cheque' => 'Сумма',
		'avg_cheque' => 'Ср.чек',
		'city' => 'Город',
		'gender' => 'Пол',
		'order_first_date_from' => 'Дата 1 заказа от',
		'order_first_date_to' => 'Дата 1 заказа до',
		'order_last_date_from' => 'Дата посл. заказа от',
		'order_last_date_to' => 'Дата посл. заказа до',
		'order_good_first_date_from' => 'Дата 1 вып. заказа от',
		'order_good_first_date_to' => 'Дата 1 вып. заказа от',
		'order_good_last_date_from' => 'Дата посл. вып. заказа от',
		'order_good_last_date_to' => 'Дата посл. вып. заказа до',
		'date_added_from' => 'Добавлен от',
		'date_added_to' => 'Добвлен до',
		'birthday_from' => 'ДР от',
		'birthday_to' => 'ДР до',
		'birthday_year' => 'ДР игнор. год',
		'mail_opened' => 'Открытий писем',
		'has_push' => 'push подписка',
		'total_calls' => 'Звонков',
		'avg_calls_duration' => 'Длит. звонка',
		'avg_csi' => 'CSI',
		'csi_reject' => 'Отказ CSI',
		'total_cheque_only_selected' => 'Только выбр.',
		);
		
		private	$keys_a = array(
		
		'country_id',
		'manufacturer_view',
		'manufacturer_bought',
		'category_view',
		'category_bought',
		'first_order_source',
		'source',
		'coupon',
		'customer_group_id'
		
		);
		
		private	$keys_a_explain = array(
		
		'country_id' => 'Страна',
		'manufacturer_view' => 'Бренд просм.',
		'manufacturer_bought' => 'Бренд куплен',
		'category_view' => 'Категория просм.',
		'category_bought' => 'Категория куплена',
		'first_order_source' => 'Пришел из',
		'source' => 'Источник',
		'coupon' => 'Купон',
		'customer_group_id'  => 'Группа',
		
		);
		
		public function index() {
			$this->language->load('sale/customer_group');
			
			$this->document->setTitle('Настройки сегментации');
			
			$this->load->model('sale/customer_group');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('sale/customer_group');
			
			$this->document->setTitle('Настройки сегментации');
			
			$this->load->model('sale/customer_group');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_customer_group->addCustomerSegment($this->request->post);
				
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
				
				$this->redirect($this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('sale/customer_group');
			
			$this->document->setTitle('Настройки сегментации');
			
			$this->load->model('sale/customer_group');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_sale_customer_group->editCustomerSegment($this->request->get['segment_id'], $this->request->post);
				
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
				
				$this->redirect($this->url->link('sale/segments/update', 'segment_id='.$this->request->get['segment_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		
		public function delete() { 
			$this->language->load('sale/customer_group');
			
			$this->document->setTitle('Настройки сегментации');
			
			$this->load->model('sale/customer_group');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $segment_id) {
					$this->model_sale_customer_group->deleteCustomerSegment($segment_id);	
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
				
				$this->redirect($this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		
		public function deletestats() { 
			$this->language->load('sale/customer_group');
			
			$this->document->setTitle('Настройки сегментации');
			
			$this->load->model('sale/customer_group');
			
			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $segment_id) {
					$this->model_sale_customer_group->clearCustomerSegmentDynamics($segment_id);	
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
				
				//	$this->redirect($this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'sort_order ASC, name';
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
			'text'      => 'Настройки сегментации',
			'href'      => $this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('sale/segments/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['delete'] = $this->url->link('sale/segments/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
			$this->data['deletestats'] = $this->url->link('sale/segments/deletestats', 'token=' . $this->session->data['token'] . $url, 'SSL');	
			
			$this->data['segments'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$segment_total = $this->model_sale_customer_group->getTotalCustomerSegments();
			
			$results = $this->model_sale_customer_group->getCustomerSegments($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => "<i class='fa fa-list' aria-hidden='true'></i>",
				'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_segment_id=' . $result['segment_id'], 'SSL')
				);
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/segments/update', 'token=' . $this->session->data['token'] . '&segment_id=' . $result['segment_id'] . $url, 'SSL')
				);
				
				$_determination = unserialize($result['determination']);
				
				$segment_determination = array();
				foreach ($_determination as $key => $value){
					if (isset($this->keys_explain[$key]) && $value){
						$segment_determination[$this->keys_explain[$key]] = $value;
					}					
				}
				
				foreach ($_determination as $key => $value){
					if (isset($this->keys_a_explain[$key]) && $value && is_array($value)){
						if ($key == 'country_id'){
							$_vtmp = array();
							$this->load->model('localisation/country');
							foreach ($value as $_country_id){
								$_vtmp[] = $this->model_localisation_country->getCountryName($_country_id);
							}
							$segment_determination[$this->keys_a_explain[$key]] = implode(', ', $_vtmp);
						}	
						
						if ($key == 'coupon'){
							$segment_determination[$this->keys_a_explain[$key]] = implode(', ', $value);							
						}
						
						if ($key == 'first_order_source'){
							$segment_determination[$this->keys_a_explain[$key]] = implode(', ', $value);							
						}
						
						if ($key == 'source'){
							$segment_determination[$this->keys_a_explain[$key]] = implode(', ', $value);							
						}
					}					
				}
				
				if (trim($result['group'])){
					$_group = trim($result['group']);
				} else {
					$_group = 'Без группы';
				}
				
				
				
				if (!isset($this->data['segments'][$_group])){
					$this->data['segments'][$_group] = array();
				}
				
				$this->data['segments'][$_group][] = array(
				'segment_id' 	    => $result['segment_id'],
				'name'              => $result['name'],
				'bg_color'          => $result['bg_color'],				
				'txt_color'         => $result['txt_color']?$result['txt_color']:'000',
				'determination'     => $segment_determination,
				'fa_icon'           => $result['fa_icon'],
				'customer_count'    => $result['customer_count'],
				'dynamics_counter'  => $this->model_sale_customer_group->countCustomerSegmentDynamics($result['segment_id']),
				'order_good_count'  => $result['order_good_count'],
				'order_bad_count'   => $result['order_bad_count'],
				'order_good_to_bad' => $result['order_good_to_bad'],
				'avg_csi'			=> $result['avg_csi'],
				'total_cheque'      => number_format($result['total_cheque'], 0, '.', ','),
				'avg_cheque'        => number_format($result['avg_cheque'], 0, '.', ','),
				'enabled'           => $result['enabled']?'Включен':'Выключен',
				'sort_order'        => $result['sort_order'],
				'selected'          => isset($this->request->post['selected']) && in_array($result['segment_id'], $this->request->post['selected']),
				'action'            => $action
				);
			}	
			
			$this->data['heading_title'] = 'Настройки сегментации';
			
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
			
			$this->data['sort_name'] = $this->url->link('sale/segments', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
			$this->data['sort_sort_order'] = $this->url->link('sale/segments', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $segment_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();				
			
			$this->data['sort'] = $sort; 
			$this->data['order'] = $order;
			
			$this->template = 'sale/segment_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = 'Настройки сегментации';
			$this->document->setTitle('Настройки сегментации');
			
			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_description'] = $this->language->get('entry_description');
			$this->data['entry_approval'] = $this->language->get('entry_approval');
			$this->data['entry_company_id_display'] = $this->language->get('entry_company_id_display');
			$this->data['entry_company_id_required'] = $this->language->get('entry_company_id_required');
			$this->data['entry_tax_id_display'] = $this->language->get('entry_tax_id_display');
			$this->data['entry_tax_id_required'] = $this->language->get('entry_tax_id_required');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			
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
				$this->data['error_name'] = array();
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
			'text'      => 'Настройки сегментации',
			'href'      => $this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			
			if (!isset($this->request->get['segment_id'])) {
				$this->data['action'] = $this->url->link('sale/segments/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
				} else {
				$this->data['action'] = $this->url->link('sale/segments/update', 'token=' . $this->session->data['token'] . '&segment_id=' . $this->request->get['segment_id'] . $url, 'SSL');
			}
			
			$this->data['cancel'] = $this->url->link('sale/segments', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			
			
			if (isset($this->request->get['segment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$segment_info = $this->model_sale_customer_group->getCustomerSegment($this->request->get['segment_id']);
				
				$this->data['segment_id'] = (int)$this->request->get['segment_id'];
				
				if ($segment_info['determination']){
					
					$this->data['determination'] = unserialize($segment_info['determination']);			
					
				}
				
				$this->data['view_link'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_segment_id=' . $this->data['segment_id'], 'SSL');
				
			}
			
			$this->load->model('localisation/country');
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			$this->load->model('catalog/manufacturer');
			$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
			
			$this->load->model('catalog/category');
			$this->data['categories'] = $this->model_catalog_category->getCategories(0);
			
			$this->load->model('sale/customer');
			$this->data['sources'] = $this->model_sale_customer->getSources();
			$this->data['first_order_sources'] = $this->model_sale_customer->getSourcesOfFirstReferrer();
			
			$this->load->model('sale/customer_group');
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(array('start'=> 0, 'limit' => 50));
			
			$this->load->model('sale/coupon');
			$this->data['coupons'] = $this->model_sale_coupon->getCoupons(array('start'=> 0, 'limit' => 100, 'filter_show_in_segments' => 1, 'filter_status' => 1));			
			
			$this->load->model('localisation/language');
			
			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
				} elseif (!empty($segment_info)) {
				$this->data['name'] = $segment_info['name'];
				} else {
				$this->data['name'] = '';
			}		
			
			if (isset($this->request->post['description'])) {
				$this->data['description'] = $this->request->post['description'];
				} elseif (!empty($segment_info)) {
				$this->data['description'] = $segment_info['description'];
				} else {
				$this->data['description'] = '';
			}	
			
			if (isset($this->request->post['txt_color'])) {
				$this->data['txt_color'] = $this->request->post['txt_color'];
				} elseif (!empty($segment_info)) {
				$this->data['txt_color'] = $segment_info['txt_color'];
				} else {
				$this->data['txt_color'] = '';
			}	
			
			if (isset($this->request->post['bg_color'])) {
				$this->data['bg_color'] = $this->request->post['bg_color'];
				} elseif (!empty($segment_info)) {
				$this->data['bg_color'] = $segment_info['bg_color'];
				} else {
				$this->data['bg_color'] = '';
			}	
			
			if (isset($this->request->post['fa_icon'])) {
				$this->data['fa_icon'] = $this->request->post['fa_icon'];
				} elseif (!empty($segment_info)) {
				$this->data['fa_icon'] = $segment_info['fa_icon'];
				} else {
				$this->data['fa_icon'] = '';
			}	
			
			if (isset($this->request->post['enabled'])) {
				$this->data['enabled'] = $this->request->post['enabled'];
				} elseif (!empty($segment_info)) {
				$this->data['enabled'] = $segment_info['enabled'];
				} else {
				$this->data['enabled'] = '';
			}	
			
			if (isset($this->request->post['new_days'])) {
				$this->data['new_days'] = $this->request->post['new_days'];
				} elseif (!empty($segment_info)) {
				$this->data['new_days'] = $segment_info['new_days'];
				} else {
				$this->data['new_days'] = '';
			}	
			
			if (isset($this->request->post['group'])) {
				$this->data['group'] = $this->request->post['group'];
				} elseif (!empty($segment_info)) {
				$this->data['group'] = $segment_info['group'];
				} else {
				$this->data['group'] = '';
			}	
			
			if (isset($this->request->post['sort_order'])) {
				$this->data['sort_order'] = $this->request->post['sort_order'];
				} elseif (!empty($segment_info)) {
				$this->data['sort_order'] = $segment_info['sort_order'];
				} else {
				$this->data['sort_order'] = '';
			}
			
			if (isset($this->data['determination'])) {
				foreach ($this->data['determination'] as $key=>$value){					
					if (!isset($this->data[$key])){
						$this->data[$key] = $value;
					}	
				}
			}
			
			
			foreach ($this->keys as $key){
				
				if (!isset($this->data[$key])){
					$this->data[$key] = '';
				}	
				
			}
			
			foreach ($this->keys_a as $key){
				
				if (!isset($this->data[$key])){
					$this->data[$key] = array();
				}	
				
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'sale/segment_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render()); 
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'sale/segments')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}					
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'sale/segments')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$this->load->model('setting/store');
			$this->load->model('sale/customer_group');
			
			foreach ($this->request->post['selected'] as $segment_id) {
				
				$customer_total = $this->model_sale_customer_group->getTotalCustomersBySegmentId($segment_id);
				
				if ($customer_total) {
				//	$this->error['warning'] = sprintf('В этом сегменте находится %s клиентов', $customer_total);
				}
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		public function getSegmentDynamicsChart(){						
			$this->load->model('sale/segments');
		
			$segment_id = (int)$this->request->get['segment_id'];
			$range = $this->request->get['range'];

			$data = $this->model_sale_segments->getSegmentDynamics($segment_id, $range);
			
			$this->response->setOutput(json_encode($data));
			
		}
		
		public function updateCustomerSegmentsCron(){
			$this->load->model('sale/customer_group');
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');	
			$this->load->model('sale/segments');	
			
			$segments = $this->model_sale_customer_group->getCustomerSegments(array('start' => 0, 'limit' => 1000));
			
			foreach ($segments as $segment){
				if ($segment['enabled']) {
					echo ">>> Сегмент " . $segment['name'] . PHP_EOL;
					$_determination = unserialize($segment['determination']);
					$determination = array();			
					
					foreach ($this->keys as $key){				
						if (isset($_determination[$key]) && $_determination[$key]){
							$determination[$key] = $_determination[$key];
						}
					}
					
					foreach ($this->keys_a as $key){				
						if (isset($_determination[$key]) && count($_determination[$key])){
							$determination[$key] = $_determination[$key];
						}
					}
					
					$determination['segment_id'] = $segment['segment_id'];
					
					
					//те, которых нужно добавить в сегмент
					$will_customers = $this->model_sale_segments->getCustomersBySegment($determination, true, false);
					$count = count($will_customers);
					
					//те, которые есть на текущий момент в сегменте
					$now_customers = $this->model_sale_segments->getCustomersNowInSegment($determination['segment_id']);
					
					//не измененные
					$not_changed = array_intersect($now_customers, $will_customers);
					echo ">>>> Отбор завершен, найдено $count покупателей" . PHP_EOL;
					
					//новые, которых надо добавить. это те, которые есть в will, но их нету в now
					$to_add = array_diff( $will_customers, $now_customers);
					$count_to_add = count($to_add);
					echo ">>>> Из них $count_to_add новых покупателей" . PHP_EOL;
					
					if ($segment['segment_id'] == 9){
						//die();
					}
							
					foreach ($to_add as $customer_id){						
						$_comment = "Клиент добавлен в сегмент " . $segment['name'];
					//	$this->model_sale_customer->addHistory($customer_id, $this->db->escape($_comment), 0, 0, 70, false, (int)$segment['segment_id']);
						$this->db->query("INSERT IGNORE INTO customer_segments (customer_id, segment_id, date_added) VALUES ('" . (int)$customer_id . "', '" . (int)$segment['segment_id'] . "', NOW())");
						echo ">>>>> Клиент $customer_id добавлен в сегмент " . $segment['name'] . PHP_EOL;
					}
					
					//которых надо удалить. это те, которые есть в now, но нету в will
					$to_remove = array_diff( $now_customers, $will_customers);
					$count_to_remove = count($to_remove);
					echo ">>>> Из них $count_to_remove удаляемых покупателей" . PHP_EOL;
					
					$this->db->query("DELETE FROM customer_segments WHERE segment_id = '" . (int)$segment['segment_id'] . "' AND customer_id IN ('" . implode(',', $to_remove) . "')");
					
					foreach ($to_remove as $customer_id){
						$_comment = "Клиент удален из сегмента " . $segment['name'];
					//	$this->model_sale_customer->addHistory($customer_id, $this->db->escape($_comment), 0, 0, 70, false, (int)$segment['segment_id']);
						echo ">>>>> Клиент $customer_id удален из сегмента " . $segment['name'] . PHP_EOL;
					}
					
					
					
					
					
					} else {
					echo ">>> Сегмент " . $segment['name'] . " - неактивен, пропускаем" . PHP_EOL;
				}
				
				echo PHP_EOL;	
				
			}
			
			
			
		}
		
		public function preloadSegmentCustomersAjax(){
			
			$segment_id = (int)$this->request->get['segment_id'];
			
			$debug_sql = isset($this->request->get['debug_sql'])?(int)$this->request->get['debug_sql']:false;
					
			
			$this->load->model('sale/customer_group');
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');	
			$this->load->model('sale/segments');	
			$segment = $this->model_sale_customer_group->getCustomerSegment($segment_id);
			
			$_determination = unserialize($segment['determination']);
			
			
			$determination = array();			
			
			foreach ($this->keys as $key){				
				if (isset($_determination[$key]) && $_determination[$key]){
					$determination[$key] = $_determination[$key];
				}
			}
			
			foreach ($this->keys_a as $key){				
				if (isset($_determination[$key]) && count($_determination[$key])){
					$determination[$key] = $_determination[$key];
				}
			}
			
			$determination['start'] = 0;
			$determination['limit'] = 5;
			
			$this->load->model('localisation/country');
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			$tmp = array();
			foreach ($this->data['countries'] as $c){
				$tmp[$c['country_id']] = $c;
			}
			$this->data['countries'] = $tmp;
			
			if ($debug_sql){
				$this->model_sale_segments->getCustomersBySegment($determination, false, false, true);
				die();
			}
			
			$results = $this->model_sale_segments->getCustomersBySegment($determination);
			$this->data['count'] = $results['count'];
			$results = $results['results'];
			
			
			
			$this->data['customers'] = array();
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => '<i class="fa fa-edit"></i>',
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL')
				);
				
				if ($result['mail_status']){
					if ($result['mail_status'] == 'delivery'){
						$mail_status = "<span style='color:white; padding:3px; background:#4ea24e;'>".$result['mail_status']."</span>";
						} else {
						$mail_status = "<span style='color:white; padding:3px; background:#cf4a61;'>".$result['mail_status']."</span>";
					}
					} else {
					$mail_status = "<span style='color:white; padding:3px; background:#e4c25a;'>unknown</span>";
				}
				
				if ($result['mail_opened']){
					$mail_opened = "<span style='color:white; padding:3px; background:#4ea24e;'>".$result['mail_opened']."</span>";
					} else {
					$mail_opened = "<span style='color:white; padding:3px; background:#e4c25a;'>".$result['mail_opened']."</span>";
				}
				
				if ($result['mail_clicked']){
					$mail_clicked = "<span style='color:white; padding:3px; background:#4ea24e;'>".$result['mail_clicked']."</span>";
					} else {
					$mail_clicked = "<span style='color:white; padding:3px; background:#e4c25a;'>".$result['mail_clicked']."</span>";
				}									
				
				
				$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'store_id'       => $result['store_id'],
				'name'           => $result['name'],
				'is_mudak'		 => $this->model_sale_customer->getIsMudak($result['customer_id']),
				'segments'		 => $this->model_sale_customer->getCustomerSegments($result['customer_id']),
				'gender'		 => $result['gender'],
				'source'         => $result['source'],
				'order_count'    => $result['order_count'],
				'total_cheque'   => $result['total_cheque'],
				'avg_cheque'     => $result['avg_cheque'],
				'order_good_count'  => $result['order_good_count'],
				'total_calls'    => $result['total_calls'],
				'currency'       => $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$result['store_id']),
				'country'        => $result['country_id']?$this->data['countries'][$result['country_id']]['iso_code_2']:false,
				'city'           => $result['city'],
				'discount_card'  => $result['discount_card'],
				'birthday'       => $result['birthday']?date($this->language->get('date_format_short'), strtotime($result['birthday'])):'',
				'email'          => $result['email'],
				'phone'          => $result['telephone'],
				'fax'            => $result['fax'],
				'customer_group' => $result['customer_group'],
				'customer_comment' => $result['customer_comment'],
				'status'         => ($result['status'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'mail_status'    => $mail_status,
				'mail_opened'    => $mail_opened,
				'mail_clicked'   => $mail_clicked,
				'has_push'       => $result['has_push'],
				'action'         => $action
				);
				
			}
			
			$this->data['token'] = $this->session->data['token'];				
			$this->template = 'sale/customer_list_ajax.tpl';
			
			$this->response->setOutput($this->render()); 
			
		}
	}
<?php
	class ControllerCatalogActionTemplate extends Controller { 
		private $error = array();
		
		public function index() {		
			$this->language->load('catalog/actiontemplate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/actiontemplate');
			$this->load->model('catalog/actiontemplate_functions');
			
			$this->getList();
		}
		
		public function insert() {
			$this->language->load('catalog/actiontemplate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/actiontemplate');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_actiontemplate->addActionTemplate($this->request->post);
				
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
				
				$this->redirect($this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('catalog/actiontemplate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/actiontemplate');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_actiontemplate->editActionTemplate($this->request->get['actiontemplate_id'], $this->request->post);
				
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
				
				$this->redirect($this->url->link('catalog/actiontemplate/update', 'actiontemplate_id='.(int)$this->request->get['actiontemplate_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->language->load('catalog/actiontemplate');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/actiontemplate');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $actiontemplate_id) {
					$this->model_catalog_actiontemplate->deleteActionTemplate($actiontemplate_id);
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
				
				$this->redirect($this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList(){
			
			$this->load->model('tool/image');
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'id.title';
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
			'href'      => $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('catalog/actiontemplate/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('catalog/actiontemplate/delete', 'token=' . $this->session->data['token'] . $url);	
			
			$this->data['actiontemplates'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$actiontemplate_total = $this->model_catalog_actiontemplate->getTotalActionTemplates();			
			$results = $this->model_catalog_actiontemplate->getActionTemplates($data);
			
			foreach ($results as $result) {									
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/actiontemplate/update', 'token=' . $this->session->data['token'] . '&actiontemplate_id=' . $result['actiontemplate_id'] . $url, 'SSL')
				);
				
				$this->data['actiontemplates'][] = array(
				'actiontemplate_id' => $result['actiontemplate_id'],
				'title'          	=> $result['title'],
				'image'			 	=> $this->model_tool_image->resize($result['image'], 50, 50),
				'sort_order'     	=> $result['sort_order'],
				'use_for_manual' 	=> $result['use_for_manual'],
				'data_function' 	=> $result['data_function'],
				'file_template' 	=> $result['file_template'],
				'coupons'			=> $this->model_catalog_actiontemplate->getActionTemplateCoupons($result['actiontemplate_id']),
				'viewed'         	=> $result['viewed'],
				'selected'       	=> isset($this->request->post['selected']) && in_array($result['actiontemplate_id'], $this->request->post['selected']),
				'action'         	=> $action
				);
			}	
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_title'] = $this->language->get('column_title');
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
			
			$this->data['sort_title'] = $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url);
			$this->data['sort_sort_order'] = $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $actiontemplate_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'catalog/actiontemplate_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_default'] = $this->language->get('text_default');
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			
			$this->data['entry_seo_title'] = $this->language->get('entry_seo_title');
			$this->data['entry_title'] = $this->language->get('entry_title');
			$this->data['entry_description'] = $this->language->get('entry_description');
			$this->data['entry_store'] = $this->language->get('entry_store');
			$this->data['entry_keyword'] = $this->language->get('entry_keyword');
			$this->data['entry_bottom'] = $this->language->get('entry_bottom');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_layout'] = $this->language->get('entry_layout');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['tab_general'] = $this->language->get('tab_general');
			$this->data['tab_data'] = $this->language->get('tab_data');
			$this->data['tab_design'] = $this->language->get('tab_design');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['title'])) {
				$this->data['error_title'] = $this->error['title'];
				} else {
				$this->data['error_title'] = array();
			}
			
			if (isset($this->error['description'])) {
				$this->data['error_description'] = $this->error['description'];
				} else {
				$this->data['error_description'] = array();
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
			'href'      => $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['actiontemplate_id'])) {
				$this->data['action'] = $this->url->link('catalog/actiontemplate/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('catalog/actiontemplate/update', 'token=' . $this->session->data['token'] . '&actiontemplate_id=' . $this->request->get['actiontemplate_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['actiontemplate_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$actiontemplate_info = $this->model_catalog_actiontemplate->getactiontemplate($this->request->get['actiontemplate_id']);
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('localisation/language');
			
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['actiontemplate_description'])) {
				$this->data['actiontemplate_description'] = $this->request->post['actiontemplate_description'];
				} elseif (isset($this->request->get['actiontemplate_id'])) {
				$this->data['actiontemplate_description'] = $this->model_catalog_actiontemplate->getactiontemplateDescriptions($this->request->get['actiontemplate_id']);
				} else {
				$this->data['actiontemplate_description'] = array();
			}
			
			if ($this->data['actiontemplate_description']){
				$this->data['heading_title'] .= ' : ' . $this->data['actiontemplate_description'][$this->config->get('config_language_id')]['title'];
			}
			
			
			if (isset($this->request->post['keyword'])) {
				$this->data['keyword'] = $this->request->post['keyword'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['keyword'] = $this->model_catalog_actiontemplate->getKeyWords($this->request->get['actiontemplate_id']);
				} else {
				$this->data['keyword']= array();
			}
			
			if (isset($this->request->post['bottom'])) {
				$this->data['bottom'] = $this->request->post['bottom'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['bottom'] = $actiontemplate_info['bottom'];
				} else {
				$this->data['bottom'] = 0;
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['status'] = $actiontemplate_info['status'];
				} else {
				$this->data['status'] = 1;
			}
			
			if (isset($this->request->post['image'])) {
				$this->data['image'] = $this->request->post['image'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['image'] = $actiontemplate_info['image'];
				} else {
				$this->data['image'] = '';
			}

			$this->data['functions'] = [];
			$this->load->model('catalog/actiontemplate_functions');

			$classMethods = get_class_methods('ModelCatalogActionTemplateFunctions');
			foreach ($classMethods as $classMethod){
				if (!in_array($classMethod, ['__construct', '__get', '__set'])){
					$this->data['functions'][] = $classMethod;
				}
			}

			if (isset($this->request->post['data_function'])) {
				$this->data['data_function'] = $this->request->post['data_function'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['data_function'] = $actiontemplate_info['data_function'];
				} else {
				$this->data['data_function'] = '';
			}

			if (isset($this->request->post['use_for_manual'])) {
				$this->data['use_for_manual'] = $this->request->post['use_for_manual'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['use_for_manual'] = $actiontemplate_info['use_for_manual'];
				} else {
				$this->data['use_for_manual'] = '';
			}	

			$this->data['file_templates'] = [];
			$file_templates = glob(DIR_APPLICATION . '/view/template/sale/actiontemplates/*.tpl');        
        	foreach ($file_templates as $file_template) {
            	$this->data['file_templates'][] = pathinfo($file_template,  PATHINFO_FILENAME);
        	}
			
			if (isset($this->request->post['sort_order'])) {
				$this->data['sort_order'] = $this->request->post['sort_order'];
				} elseif (!empty($actiontemplate_info)) {
				$this->data['sort_order'] = $actiontemplate_info['sort_order'];
				} else {
				$this->data['sort_order'] = '';
			}
			
			$this->load->model('tool/image');
			
			if (isset($this->request->post['image'])) {
				$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
				} elseif (!empty($actiontemplate_info) && $actiontemplate_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($actiontemplate_info['image'], 100, 100);
				} else {
				$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			
			$this->load->model('design/layout');
			
			$this->data['layouts'] = $this->model_design_layout->getLayouts();
			
			$this->template = 'catalog/actiontemplate_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}

		public function loadTemplateV2($return_html = false){
			$this->load->model('catalog/actiontemplate');
			$this->load->model('catalog/actiontemplate_functions');

			$data 		= $this->request->post + $this->request->get;			
			$this->data = $data;

			$actionTemplate = $this->model_catalog_actiontemplate->getActionTemplate($data['actiontemplate_id']);

			if (!empty($actionTemplate['data_function']) && (method_exists($this->model_catalog_actiontemplate_functions, $actionTemplate['data_function']))){
				$this->data += $this->model_catalog_actiontemplate_functions->{$actionTemplate['data_function']}($data);
			}

			$this->data['config_customer_manual_test_mode'] = $this->config->get('config_customer_manual_test_mode');

			if (!empty($actionTemplate['file_template'])){
				$this->template = 'sale/actiontemplates/' . $actionTemplate['file_template'] . '.tpl';
			} else {
				$this->template = $actionTemplate['description'];
			}

			$html = $this->render();

			if ($this->config->get('config_customer_manual_tracking_code')){
				$html = addTrackingToHTML($html, $this->config->get('config_customer_manual_tracking_code'));
			}

			if ($return_html){
				return $html;
			} else {
				$this->response->setOutput($html);
			}
		}

		public function sendMailV2(){
			$this->load->model('catalog/actiontemplate');
			$this->load->model('catalog/actiontemplate_functions');
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');			
			$this->load->model('kp/work');

			$data 				= $this->request->post;
			$customer 			= $this->model_sale_customer->getCustomer($data['customer_id']);
			$actionTemplate   	= $this->model_catalog_actiontemplate->getActionTemplate($data['actiontemplate_id'], $customer['language_id']);			

			$mail_info = [
				'mail_from' 		=> $this->config->get('config_mail_trigger_name_from'),
				'mail_from_email' 	=> $this->config->get('config_mail_trigger_mail_from'),
				'mail_to' 			=> $customer['firstname'] . ' ' . $customer['lastname'],
				'mail_to_email' 	=> $customer['email'],
				'store_id'    		=> $customer['store_id'],
				'customer_id'    	=> $customer['customer_id'],
				'template_id'    	=> (int)$data['actiontemplate_id']
			];

			$data['html'] = $this->loadTemplateV2(true);

			$data = array_merge($data, $actionTemplate, $customer, $mail_info);					
			
			$mail = new Mail($this->registry); 
			$mail->setProtocol($this->config->get('config_mail_protocol'));
			$mail->setEmailTemplate(new EmailTemplate($this->request, $this->registry));
			$mail->setFrom($this->config->get('config_mail_trigger_mail_from'));
			$mail->setSender($this->config->get('config_mail_trigger_name_from'));
			$mail->setIsMarketing(true);
			$mail->setSubject($actionTemplate['seo_title']);
			$mail->setTo($customer['email']);
			$mail->setHTML($data['html']);
			$transmission_id = $mail->send(true, $data, true);

			if (!$this->config->get('config_customer_manual_test_mode')){
				$this->model_sale_customer->setSentManualLetter($data['customer_id'], true);
			}

			$this->load->model('kp/work');
			$this->model_kp_work->updateFieldPlusOne('customer_manual_count');
			
			$json = [
					'transmission_id' => $transmission_id,
					'date_sent'       => date('d.m.Y')
				];

			$this->response->setOutput(json_encode($json));				
		}
				
		public function loadTemplate($customer_id = false, $template_id = false, $do_return = false){
			
			if (!$do_return){
				$customer_id = $this->request->get['customer_id'];
				$template_id = $this->request->get['template_id'];
			}
			
			$this->load->model('catalog/actiontemplate');
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');
						
			$customer 	= $this->model_sale_customer->getCustomer($customer_id);
			$template 	= $this->model_catalog_actiontemplate->getactiontemplateDescription($template_id, $customer['language_id']);
			$title 		= $this->model_catalog_actiontemplate->getactiontemplateTitle($template_id, $customer['language_id']);

			$tags = array(
			'{customer_name}' 		=> 'customer-firstname',
			'{customer_lastname}' 	=> 'customer-lastname',
			'{customer_email}' 		=> 'customer-email',
			'{customer_utoken}' 	=> 'customer-utoken',
			'{store_url}'       	=> $customer['store_id']==0?HTTPS_CATALOG:$this->model_setting_setting->getKeySettingValue('config', 'config_url', (int)$customer['store_id']),
			);
			
			foreach ($tags as $key => $value){
				$tag = explode('-', $value);
				if ($tag && isset(${$tag[0]}) && isset(${$tag[0]}[$tag[1]])){
					$template = str_replace($key, ${$tag[0]}[$tag[1]], $template);
					$title = str_replace($key, ${$tag[0]}[$tag[1]], $title);
				} else {
					$template = str_replace($key, $value, $template);
					$title = str_replace($key, $value, $title);
				}				
			}
			
			function makedate($match){
				$s = $match[0];
				$s = explode(' ', $s);
				if ($s[1] == 'plus'){
					$return = date_add(date_create(), date_interval_create_from_date_string($s[2] . ' ' . $s[3]));
				} elseif ($s[1] == 'minus') {
					$return = date_sub(date_create(), date_interval_create_from_date_string($s[2] . ' ' . $s[3]));
				}
				
				return date_format($return, 'd.m.Y');
			}
			
			$template = preg_replace_callback('/\{date (plus|minus) (\d+) days\}/', "makedate", $template);
			
			$response = [
				'title' => $title,
				'html'  => html_entity_decode($template)			
			];
			
			if (!$do_return){
				$this->response->setOutput(json_encode($response));
				} else {
				return $responce;
			}
		}
		
		public function sendMailWithTemplate(){
			$this->load->model('catalog/actiontemplate');
			$this->load->model('sale/customer');
			$this->load->model('setting/setting');
			
			$this->load->model('kp/work');
			$this->model_kp_work->updateFieldPlusOne('sent_mail_count');
		
			$data 		= $this->request->post;
			$template 	= $this->loadTemplate($data['customer_id'], $data['template_id'], true);
			$customer 	= $this->model_sale_customer->getCustomer($data['customer_id']);
					
			$mail_info = [
				'mail_from' 		=> $this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer['store_id']),
				'mail_from_email' 	=> $this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$customer['store_id']),
				'mail_to' 			=> $customer['firstname'] . ' ' . $customer['lastname'],
				'mail_to_email' 	=> $customer['email'],
				'store_id'    		=> $customer['store_id'],
				'customer_id'    	=> $customer['customer_id'],
				'template_id'    	=> (int)$data['template_id']
			];
			$data = array_merge($data, $template, $customer, $mail_info);			
			
			$mail = new Mail($this->registry); 
			$mail->setFrom($this->model_setting_setting->getKeySettingValue('config', 'config_email', (int)$customer['store_id']));
			$mail->setSender($this->model_setting_setting->getKeySettingValue('config', 'config_name', (int)$customer['store_id']));
			$mail->setSubject($template['seo_title']);
			$mail->setTo($customer['email']);
			$mail->setHTML($template['html']);
			$transmission_id = $mail->send(true, $data, true);
			
			if ($transmission_id){
				$responce = [
					'transmission_id' => $transmission_id,
					'date_sent'       => date('d.m.Y')
				];
				$this->response->setOutput(json_encode($responce));				
			}
											
		}
						
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/actiontemplate')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['actiontemplate_description'] as $language_id => $value) {
				if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
					$this->error['title'][$language_id] = $this->language->get('error_title');
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
			if (!$this->user->hasPermission('modify', 'catalog/actiontemplate')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			$this->load->model('setting/store');
			
			foreach ($this->request->post['selected'] as $actiontemplate_id) {
				
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
	}
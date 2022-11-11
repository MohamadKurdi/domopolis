<?php
class ControllerModuleFAQSystem extends Controller {
	private $error = array(); 
	
	
	public function index() {   
		$this->load->language('module/faq_system');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('tool/image');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('faq_system', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/faq_system', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['category_form_title'] = $this->language->get('category_form_title');
		$this->data['question_form_title'] = $this->language->get('question_form_title');
		
		$this->data['tab_setting']     = $this->language->get('tab_setting');
		$this->data['tab_category']    = $this->language->get('tab_category');
		$this->data['tab_question']    = $this->language->get('tab_question');
		$this->data['tab_proposal']    = $this->language->get('tab_proposal');
		$this->data['tab_help']        = $this->language->get('tab_help');
		
		$this->data['text_expanded'] = $this->language->get('text_expanded');
		$this->data['text_collapsed'] = $this->language->get('text_collapsed');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['text_info_save'] = $this->language->get('text_info_save');
		
		$this->data['entry_category_initial_status'] = $this->language->get('entry_category_initial_status');
		$this->data['entry_allow_propose'] = $this->language->get('entry_allow_propose');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = array();
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/faq_system', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/faq_system', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
		
		
		
		if (isset($this->request->post['faq_system_category_initial_status'])) {
			$this->data['faq_system_category_initial_status'] = $this->request->post['faq_system_category_initial_status'];
		} elseif ($this->config->get('faq_system_category_initial_status')) { 
			$this->data['faq_system_category_initial_status'] = $this->config->get('faq_system_category_initial_status');
		} else {
			$this->data['faq_system_category_initial_status'] = '';
		}
		
		if (isset($this->request->post['faq_system_allow_propose'])) {
			$this->data['faq_system_allow_propose'] = $this->request->post['faq_system_allow_propose'];
		} elseif ($this->config->get('faq_system_allow_propose')) { 
			$this->data['faq_system_allow_propose'] = $this->config->get('faq_system_allow_propose');
		} else {
			$this->data['faq_system_allow_propose'] = '';
		}
		
		$this->data['token'] = $this->session->data['token'];
				
		$this->template = 'module/faq_system.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function getCategories(){
		
		$this->language->load('module/faq_system');
		$this->load->model('module/faq_system');
		
		$this->data['insert'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['categories'] = array();
		
		$results = $this->model_module_faq_system->getCategories();

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'onclick' => 'getCategoryForm(' . $result['category_id'] . ')'
			);
					
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'status'      => $result['status'],
				'status_text' => ($result['status'] == 1)? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'action'      => $action
			);
		}


		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		$this->template = 'module/faq_category_list.tpl';
				
		$this->response->setOutput($this->render());
	}
	
	public function getCategoryForm(){
		
		$this->language->load('module/faq_system');
		
		$this->load->model('module/faq_system');
		$this->load->model('localisation/language');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				
				if (isset($this->request->post['category_description'])){
					foreach($this->request->post['category_description'] as $key => $value){
						if (!utf8_strlen($value['name'])){
							$json['error']['warning'] = $this->language->get('error_category_name');
						}
					}
				}
				
				if (!$this->user->hasPermission('modify', 'module/faq_system')) {
					$json['error']['warning'] = $this->language->get('error_permission');
				}

			}
			
			if (!$json) {
				if (!$this->request->post['category_id']){
					$this->model_module_faq_system->addCategory($this->request->post);
				} else {
					$this->model_module_faq_system->editCategory($this->request->post['category_id'], $this->request->post);
				}				
					
				$json['success'] = $this->language->get('text_success_category');
			}
		
		} else {
			
			$this->data['text_enabled']     = $this->language->get('text_enabled');
			$this->data['text_disabled']    = $this->language->get('text_disabled');
			
			$this->data['entry_name']       = $this->language->get('entry_name');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_status']     = $this->language->get('entry_status');
			
			$this->data['button_save'] = $this->language->get('button_save');
			
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->get['category_id'])){
				$category_id = $this->request->get['category_id'];
				$category_info = $this->model_module_faq_system->getCategory($category_id);
			} else {
				$category_id = 0;
			}
			
			$this->data['category_id'] = $category_id; 
			
			if (isset($category_info)){
				$this->data['category_description'] = $this->model_module_faq_system->getCategoryDescriptions($category_id);
			} else {
				$this->data['category_description'] = array();
			}
			
			if (isset($this->request->post['keyword'])) {
				$this->data['keyword'] = $this->request->post['keyword'];
				} elseif (!empty($category_info)) {
				$this->data['keyword'] = $this->model_module_faq_system->getKeyWords($category_id);
				} else {
				$this->data['keyword']= array();
			}
			
			if (isset($category_info)){
				$this->data['sort_order'] = $category_info['sort_order'];
			} else {
				$this->data['sort_order'] = '';
			}
			
			if (isset($category_info)){
				$this->data['status'] = $category_info['status'];
			} else {
				$this->data['status'] = 1;
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'module/faq_category_form.tpl';
					
			$json['output'] = $this->render();
		}
		
		$this->response->setOutput(json_encode($json));	
	}
	
	public function deleteCategories() {
    	$this->language->load('module/faq_system');
		
		$this->load->model('module/faq_system');
		
		$json = array();
		
		if (!$json){
			if (!$this->user->hasPermission('modify', 'module/faq_system')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}
		}	
		
		if (!$json) {				
			foreach($this->request->post['selected'] as $category_id){
				$this->model_module_faq_system->deleteCategory($category_id);
			}
			
			$json['success'] = $this->language->get('text_success_delete');
		}
		
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function getQuestions(){
		
		$this->language->load('module/faq_system');
		$this->load->model('module/faq_system');
		
		$this->data['insert'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['questions'] = array();
		
		$filter_status = isset($this->request->get['status'])? 0 : 1;
		$this->data['filter_status'] = $filter_status;
		
		$data = array(
			'filter_status' => $filter_status
		);
		
		$results = $this->model_module_faq_system->getQuestions($data);

		if ($results){
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'onclick' => 'getQuestionForm(' . $result['question_id'] . ')'
				);
						
				$this->data['questions'][] = array(
					'question_id' => $result['question_id'],
					'title'       => $result['title'],
					'status'      => $result['status'],
					'status_text' => ($result['status'] == 1)? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order'  => $result['sort_order'],
					'category'    => $result['name'],
					'action'      => $action
				);
			}
		}	


		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_question'] = $this->language->get('column_question');
		$this->data['column_category'] = $this->language->get('column_category');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		$this->template = 'module/faq_question_list.tpl';
				
		$this->response->setOutput($this->render());
	}
	
	public function getQuestionForm(){
		
		$this->language->load('module/faq_system');
		
		$this->load->model('module/faq_system');
		$this->load->model('localisation/language');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				
				if (utf8_strlen($this->request->post['category_id']) < 1 || (int)$this->request->post['category_id'] == 0 ){
					$json['error']['warning'] = $this->language->get('error_question_category');
				}
				
				if (isset($this->request->post['question_description'])){
					foreach($this->request->post['question_description'] as $key => $value){
						if (!utf8_strlen($value['description'])){
							$json['error']['warning'] = $this->language->get('error_question_description');
						}
						
						if (!utf8_strlen($value['title'])){
							$json['error']['warning'] = $this->language->get('error_question_title');
						}
					}
				}
				
				if (!$this->user->hasPermission('modify', 'module/faq_system')) {
					$json['error']['warning'] = $this->language->get('error_permission');
				}
			}
			
			if (!$json) {
				if (!$this->request->post['question_id']){
					$this->model_module_faq_system->addQuestion($this->request->post);
				} else {
					$this->model_module_faq_system->editQuestion($this->request->post['question_id'], $this->request->post);
				}				
					
				$json['success'] = $this->language->get('text_success_question');
			}
		
		} else {
			
			$this->data['text_enabled']     = $this->language->get('text_enabled');
			$this->data['text_disabled']    = $this->language->get('text_disabled');
			
			$this->data['entry_question']   = $this->language->get('entry_question');
			$this->data['entry_answer']     = $this->language->get('entry_answer');
			$this->data['entry_category']   = $this->language->get('entry_category');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_status']     = $this->language->get('entry_status');
			
			$this->data['button_save'] = $this->language->get('button_save');
			
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			$this->data['categories'] = $this->model_module_faq_system->getCategories();
			
			if (isset($this->request->get['question_id'])){
				$question_id = $this->request->get['question_id'];
				$question_info = $this->model_module_faq_system->getQuestion($question_id);
			} else {
				$question_id = 0;
			}
			
			$this->data['question_id'] = $question_id; 
			
			if (isset($question_info)){
				$this->data['question_description'] = $this->model_module_faq_system->getQuestionDescriptions($question_id);
			} else {
				$this->data['question_description'] = array();
			}
			
			if (isset($question_info)){
				$this->data['category_id'] = $question_info['category_id'];
			} else {
				$this->data['category_id'] = '';
			}
			
			if (isset($question_info)){
				$this->data['sort_order'] = $question_info['sort_order'];
			} else {
				$this->data['sort_order'] = '';
			}
			
			if (isset($question_info)){
				$this->data['status'] = $question_info['status'];
			} else {
				$this->data['status'] = 1;
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->template = 'module/faq_question_form.tpl';
					
			$json['output'] = $this->render();
		}
		
		$this->response->setOutput(json_encode($json));	
	}
	
	public function deleteQuestions() {
    	$this->language->load('module/faq_system');
		
		$this->load->model('module/faq_system');
		
		$json = array();
		
		if (!$json){
			if (!$this->user->hasPermission('modify', 'module/faq_system')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}
		}
		
		if (!$json) {				
			foreach($this->request->post['selected'] as $question_id){
				$this->model_module_faq_system->deleteQuestion($question_id);
			}
			
			$json['success'] = $this->language->get('text_success_delete');
		}
		
		
		$this->response->setOutput(json_encode($json));
	}
	
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/faq_system')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
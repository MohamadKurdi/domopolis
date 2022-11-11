<?php 
	class ControllerInformationFAQSystem extends Controller {
		public function index() {  
			$this->language->load('information/faq_system');
			
			$this->load->model('catalog/faq_system');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/faq_system.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/faq_system.css');
				} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/faq_system.css');
			}
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
			);
			
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_info'] = sprintf($this->language->get('text_info'), $this->url->link('information/contact', '', 'SSL'));
			
			$this->data['entry_question'] = $this->language->get('entry_question');
			$this->data['entry_captcha']  = $this->language->get('entry_captcha');
			
			$this->data['faq'] = array();
			
			$categories = $this->model_catalog_faq_system->getCategories();
			
			if (!empty($this->request->get['faq_category_id'])){
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_faq_system'),
				'href'      => $this->url->link('information/faq_system'),
				'separator' => false
				);
				
				$this->data['single'] = true;
				
				$category_info = $this->model_catalog_faq_system->getCategory($this->request->get['faq_category_id']);
				
				$this->data['heading_title'] = $category_info['name'];
				$this->document->setTitle($category_info['name'] . ' - ' . $this->language->get('text_faq_system'));
				
				$questions = $this->model_catalog_faq_system->getQuestionsByCategoryId($category_info['category_id']);
				
				if ($questions){
					$qa_data = array();
					
					foreach($questions as $question) {
						$qa_data[] = array(
						'question_id' => $question['question_id'],
						'question'    => html_entity_decode($question['title'], ENT_QUOTES, 'UTF-8'),
						'answer'      => html_entity_decode($question['description'], ENT_QUOTES, 'UTF-8')
						);
					}
					
					$this->data['faq'][] = array(
					'category_id'   => $category_info['category_id'],
					'href'			=> $this->url->link('information/faq_system', 'faq_category_id=' . $category_info['category_id']),
					'category_name' => html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8'),	
					'questions'     => $qa_data
					);
				}
				
				} else {
				
				$this->data['single'] = false;
				
				if ($categories){
					foreach($categories as $category){
						$questions = $this->model_catalog_faq_system->getQuestionsByCategoryId($category['category_id']);
						
						if ($questions){
							$qa_data = array();
							
							foreach($questions as $question) {
								$qa_data[] = array(
								'question_id' => $question['question_id'],
								'question'    => html_entity_decode($question['title'], ENT_QUOTES, 'UTF-8'),
								'answer'      => html_entity_decode($question['description'], ENT_QUOTES, 'UTF-8')
								);
							}
							
							$this->data['faq'][] = array(
							'category_id'   => $category['category_id'],
							'href'			=> $this->url->link('information/faq_system', 'faq_category_id=' . $category['category_id']),
							'category_name' => html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'),	
							'questions'     => $qa_data
							);
						}
					}
				}
			}
			
			$this->data['category_initial_status'] = $this->config->get('faq_system_category_initial_status');
			$this->data['allow_propose'] = $this->config->get('faq_system_allow_propose');
			$this->data['text_propose'] = $this->language->get('text_propose');
			
			$this->data['button_close_dialog'] = $this->language->get('button_close_dialog');
			$this->data['button_save_dialog'] = $this->language->get('button_save_dialog');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/faq_system.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/faq_system.tpl';
				} else {
				$this->template = 'default/template/information/faq_system.tpl';
			}
			
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
			);
			
			$this->response->setOutput($this->render());
			
		}
		
		public function propose() {
			
			$this->language->load('information/faq_system');
			
			$this->load->model('catalog/faq_system');
			
			$json = array();
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
					$json['error'] = $this->language->get('error_captcha');
				}
				
				if (utf8_strlen($this->request->post['question']) < 10) {
					$json['error'] = $this->language->get('error_question');
				}
				
				if (!isset($json['error'])) {
					$this->model_catalog_faq_system->proposeQuestion($this->request->post);
					
					$json['success'] = $this->language->get('text_success');
				}
			}
			
			$this->response->setOutput(json_encode($json));
		}
	}
?>
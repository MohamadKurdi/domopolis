<?php
	class ControllerCatalogReview extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('catalog/review');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/review');
			
			$this->getList();
		} 
		
		public function insert() {
			$this->language->load('catalog/review');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/review');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_review->addReview($this->request->post);
				
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
				
				$this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function update() {
			$this->language->load('catalog/review');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/review');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);
				
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
				
				$this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() { 
			$this->language->load('catalog/review');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/review');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $review_id) {
					$this->model_catalog_review->deleteReview($review_id);
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
				
				$this->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'r.date_added';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
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
			'href'      => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['insert'] = $this->url->link('catalog/review/insert', 'token=' . $this->session->data['token'] . $url);
			$this->data['delete'] = $this->url->link('catalog/review/delete', 'token=' . $this->session->data['token'] . $url);	
			
			$this->data['reviews'] = array();
			
			$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$this->load->model('tool/image');
			
			$review_total = $this->model_catalog_review->getTotalReviews();			
			$results = $this->model_catalog_review->getReviews($data);
			
			foreach ($results as $result) {
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/review/update', 'token=' . $this->session->data['token'] . '&review_id=' . $result['review_id'] . $url, 'SSL')
				);
				
				$this->data['reviews'][] = array(
				'review_id'  			=> $result['review_id'],
				'purchased'     		=> $result['purchased'],
			    'addimage'      		=> $result['addimage'],
				'image'					=> ($result['addimage'])?$this->model_tool_image->resize($result['addimage'], 50, 50):false,
                'html_status'   		=> $result['html_status'],
				'customer_link'       	=> ($result['customer_id'])?$this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL'):false,
				'name'       			=> $result['name'],
				'author'     			=> $result['author'],
				'rating'     			=> $result['rating'],
				'good'     				=> $result['good'],
				'bads'     				=> $result['bads'],
				'text'     				=> $result['text'],
				'status'     			=> $result['status'],
				'rewarded'     			=> $result['rewarded'],
				'date_added' 			=> checkDateIsGood($result['date_added'])?date('Y-m-d', strtotime($result['date_added'])):false,
				'time_added' 			=> checkDateIsGood($result['date_added'])?date('H:i:s', strtotime($result['date_added'])):false,
				'date_modified' 		=> checkDateIsGood($result['date_modified'])?date('Y-m-d', strtotime($result['date_modified'])):false,
				'time_modified' 		=> checkDateIsGood($result['date_modified'])?date('H:i:s', strtotime($result['date_modified'])):false,
				'date_approved' 		=> checkDateIsGood($result['date_approved'])?date('Y-m-d', strtotime($result['date_approved'])):false,
				'time_approved' 		=> checkDateIsGood($result['date_approved'])?date('H:i:s', strtotime($result['date_approved'])):false,
				'selected'   			=> isset($this->request->post['selected']) && in_array($result['review_id'], $this->request->post['selected']),
				'action'     			=> $action
				);
			}	
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_author'] = $this->language->get('column_author');
			$this->data['column_rating'] = $this->language->get('column_rating');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
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
			
			$this->data['sort_product'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url);
			$this->data['sort_author'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.author' . $url);
			$this->data['sort_rating'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.rating' . $url);
			$this->data['sort_status'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.status' . $url);
			$this->data['sort_date_added'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url);
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $review_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'catalog/review_list.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function getForm() {
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['entry_texts'] = $this->language->get('entry_texts');
			$this->data['entry_bads'] = $this->language->get('entry_bads');
			$this->data['entry_goods'] = $this->language->get('entry_goods');
			$this->data['entry_answer'] = $this->language->get('entry_answer');
			$this->data['entry_purchased'] = $this->language->get('entry_purchased');
			$this->data['entry_addimage'] = $this->language->get('entry_addimage');
			$this->data['entry_html_status'] = $this->language->get('entry_html_status');
			$this->data['entry_date_added'] = $this->language->get('entry_date_added');
			
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['text_none'] = $this->language->get('text_none');
			$this->data['text_select'] = $this->language->get('text_select');
			
			$this->data['entry_product'] = $this->language->get('entry_product');
			$this->data['entry_author'] = $this->language->get('entry_author');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_text'] = $this->language->get('entry_text');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->error['product'])) {
				$this->data['error_product'] = $this->error['product'];
				} else {
				$this->data['error_product'] = '';
			}
			
			if (isset($this->error['author'])) {
				$this->data['error_author'] = $this->error['author'];
				} else {
				$this->data['error_author'] = '';
			}
			
			if (isset($this->error['text'])) {
				$this->data['error_text'] = $this->error['text'];
				} else {
				$this->data['error_text'] = '';
			}
			
			if (isset($this->error['rating'])) {
				$this->data['error_rating'] = $this->error['rating'];
				} else {
				$this->data['error_rating'] = '';
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
			'href'      => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			if (!isset($this->request->get['review_id'])) { 
				$this->data['action'] = $this->url->link('catalog/review/insert', 'token=' . $this->session->data['token'] . $url);
				} else {
				$this->data['action'] = $this->url->link('catalog/review/update', 'token=' . $this->session->data['token'] . '&review_id=' . $this->request->get['review_id'] . $url);
			}
			
			$this->data['cancel'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url);
			
			if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$review_info = $this->model_catalog_review->getReview($this->request->get['review_id']);
			}
			
			$this->load->model('localisation/language');
			$this->data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['review_description'])) {
				$this->data['review_description'] = $this->request->post['review_description'];
				} elseif (isset($this->request->get['review_id'])) {
				$this->data['review_description'] = $this->model_catalog_review->getReviewDescriptions($this->request->get['review_id']);
				} else {
				$this->data['review_description'] = array();
			}
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('catalog/product');
			
			if (isset($this->request->post['product_id'])) {
				$this->data['product_id'] = $this->request->post['product_id'];
				} elseif (!empty($review_info)) {
				$this->data['product_id'] = $review_info['product_id'];
				} else {
				$this->data['product_id'] = '';
			}
			
			if (isset($this->request->post['product'])) {
				$this->data['product'] = $this->request->post['product'];
				} elseif (!empty($review_info)) {
				$this->data['product'] = $review_info['product'];
				} else {
				$this->data['product'] = '';
			}
			
			if (isset($this->request->post['author'])) {
				$this->data['author'] = $this->request->post['author'];
				} elseif (!empty($review_info)) {
				$this->data['author'] = $review_info['author'];
				} else {
				$this->data['author'] = '';
			}
			
			if (isset($this->request->post['text'])) {
				$this->data['text'] = $this->request->post['text'];
				} elseif (!empty($review_info)) {
				$this->data['text'] = $review_info['text'];
				} else {
				$this->data['text'] = '';
			}
			
			if (isset($this->request->post['rating'])) {
				$this->data['rating'] = $this->request->post['rating'];
				} elseif (!empty($review_info)) {
				$this->data['rating'] = $review_info['rating'];
				} else {
				$this->data['rating'] = '';
			}
			
			if (isset($this->request->post['good'])) {
				$this->data['good'] = $this->request->post['good'];
				} elseif (!empty($review_info)) {
				$this->data['good'] = $review_info['good'];
				} else {
				$this->data['good'] = '';
			}
			
			if (isset($this->request->post['bads'])) {
				$this->data['bads'] = $this->request->post['bads'];
				} elseif (!empty($review_info)) {
				$this->data['bads'] = $review_info['bads'];
				} else {
				$this->data['bads'] = '';
			}
			
			if (isset($this->request->post['answer'])) {
				$this->data['answer'] = $this->request->post['answer'];
				} elseif (!empty($review_info)) {
				$this->data['answer'] = $review_info['answer'];
				} else {
				$this->data['answer'] = '';
			}

			if (isset($this->request->post['purchased'])) {
				$this->data['purchased'] = $this->request->post['purchased'];
				} elseif (!empty($review_info)) {
				$this->data['purchased'] = $review_info['purchased'];
				} else {
				$this->data['purchased'] = '';
			}

			if (isset($this->request->post['date_added'])) {
				$this->data['date_added'] = $this->request->post['date_added'];
				} elseif (!empty($review_info)) {
				$this->data['date_added'] = $review_info['date_added'];
				} else {
				$this->data['date_added'] = '';
			}

			if (isset($this->request->post['addimage'])) {
				$this->data['addimage'] = $this->request->post['addimage'];
				} elseif (!empty($review_info)) {
				$this->data['addimage'] = $review_info['addimage'];
				} else {
				$this->data['addimage'] = '';
			}
			
			if ($this->data['addimage'] && mb_strlen($this->data['addimage']) > 32){
				if (filter_var($this->data['addimage'] , FILTER_VALIDATE_URL)){
					$_addimage = $this->data['addimage'];
					} else {
					$size = getimagesize(DIR_IMAGE . $this->data['addimage']);
					$this->load->model('tool/image');
					$_addimage = $this->model_tool_image->resize($this->data['addimage'], $size[0], $size[1]);
				}
				} else {
				$_addimage = '';
			}			
			$this->data['addimage_url'] = $_addimage;
			
			if (isset($this->request->post['html_status'])) {
				$this->data['html_status'] = $this->request->post['html_status'];
				} elseif (!empty($review_info)) {
				$this->data['html_status'] = $review_info['html_status'];
				} else {
				$this->data['html_status'] = '';
			}
			
			if (isset($this->request->post['status'])) {
				$this->data['status'] = $this->request->post['status'];
				} elseif (!empty($review_info)) {
				$this->data['status'] = $review_info['status'];
				} else {
				$this->data['status'] = '';
			}
			
			$this->template = 'catalog/review_form.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/review')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->request->post['product_id']) {
				$this->error['product'] = $this->language->get('error_product');
			}
			
			if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
				$this->error['author'] = $this->language->get('error_author');
			}
			
			if (utf8_strlen($this->request->post['text']) < 1) {
				$this->error['text'] = $this->language->get('error_text');
			}
			
			if (!isset($this->request->post['rating'])) {
				$this->error['rating'] = $this->language->get('error_rating');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/review')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}	
	}
?>
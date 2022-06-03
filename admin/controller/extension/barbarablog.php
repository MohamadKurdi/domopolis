<?php
class ControllerExtensionbarbarablog extends Controller {
	
	private $data = array();
	private $error = array();
	private $pid = '';
	private $cid = '';

	private $post_limit = 20;
	private $cat_limit  = 20;

  public function __construct($registry) {
    parent::__construct($registry);
    //$this->load->model('extension/barbarablog/setting');
    //$this->model_extension_barbarablog_setting->install();
    $this->document->addStyle('view/stylesheet/adminbarbara.css');
  }

	public function index() {

		$this->load->language('extension/barbarablog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getPostList();
		

	}

	protected function getPostList() {

		$this->load->helper('barbara_blog');
		$this->load->model('extension/barbarablog/setting');
		$settings = $this->model_extension_barbarablog_setting->settings();
		$config = setting($settings);

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = null;
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.ID';
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

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		// Buttons URL
		$data['post_create_link'] = $this->url->link('extension/barbarablog/post_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['post_edit_link'] = $this->url->link('extension/barbarablog/post_edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['post_delete_multiple'] = $this->url->link('extension/barbarablog/post_delete_multiple', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['post_delete_link'] = $this->url->link('extension/barbarablog/post_delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		

		$limit = isset($config['post_limit_admin']) ? $config['post_limit_admin'] : $this->post_limit;	

		$filter_data = array(
			'filter_title'	  => $filter_title,
			'filter_author'	  => $filter_author,
			'filter_status'   => $filter_status,
			'sort' 			  => $sort,
			'order' 		  => $order,
			'start' 		  => ($page - 1) * $limit,
			'limit'			  => $limit
		);

		$this->load->model('tool/image');

		$this->load->model('extension/barbarablog/post');

		$total_post = $this->model_extension_barbarablog_post->getTotalPost($filter_data);
		$posts = $this->model_extension_barbarablog_post->getPosts($filter_data);

		if(is_array($posts) && count($posts) > 0) {
			foreach ($posts as $post) {
				if (is_file(DIR_IMAGE . $post['post_thumb'])) {
					$image = $this->model_tool_image->resize($post['post_thumb'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}

				$data['posts'][] = array(
					'ID' 		 	=> $post['ID'],
					'image'      	=> $image,
					'title'    		=> $post['title'],
					'date_added'    => $post['date_added'],
					'post_author'   => $post['post_author'],
					'post_status'   => $post['post_status'],
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		// Top Menubar Label
		$data['post_menu'] 		= $this->language->get('post');
		$data['category_menu'] 	= $this->language->get('category');
		$data['setting_menu'] 	= $this->language->get('setting');
		$data['help_menu'] 		= $this->language->get('help');

		// Button Label
		$data['create_btn'] = $this->language->get('create_btn');
		$data['delete_btn'] = $this->language->get('delete_btn');
		$data['create_btn_tooltip'] = $this->language->get('create_btn_tooltip');
		$data['delete_btn_tooltip'] = $this->language->get('delete_btn_tooltip');
		$data['button_filter'] = $this->language->get('button_filter');

		// Filter box text
		$data['filter_text_title'] = $this->language->get('filter_text_title');
		$data['filter_text_author'] = $this->language->get('filter_text_author');
		$data['filter_text_status'] = $this->language->get('filter_text_status');
		$data['filter_text_publish'] = $this->language->get('filter_text_publish');
		$data['filter_text_unpublish'] = $this->language->get('filter_text_unpublish');

		// Table Column Label
		$data['col_thumb'] = $this->language->get('col_thumb');
		$data['col_postTitle'] = $this->language->get('col_postTitle');
		$data['col_author'] = $this->language->get('col_author');
		$data['col_date'] = $this->language->get('col_date');
		$data['col_status'] = $this->language->get('col_status');
		$data['col_action'] = $this->language->get('col_action');

		// Text
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_not_found'] = $this->language->get('text_not_found');

		$data['token'] = $this->session->data['token']; 
		
       $data['category_link'] =$this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL');
		$data['posts_link'] =$this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL');
		$data['setting_link'] =$this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL');
		
		// Alert Message
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}	

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . $this->request->get['filter_author'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . '&sort=pd.title' . $url, 'SSL');
		$data['sort_date'] = $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
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

		// Pagination
		$pagination = new Pagination();
		$pagination->total = $total_post;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_post) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_post - $limit)) ? $total_post : ((($page - 1) * $limit) + $limit), $total_post, ceil($total_post / $limit));
		
		$data['filter_title'] = $filter_title;
		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

    $data['header']       = $this->load->controller('common/header');
    $data['topmenu']      = $this->load->controller('module/barbaratheme/topmenu');
    $data['column_left']  = $this->load->controller('common/column_left');
    $data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/barbarablog/post.tpl', $data));
	}

	public function post_create() {
		$this->load->language('extension/barbarablog/post_form');

		$this->document->setTitle($this->language->get('heading_title_create'));
		$this->data['heading_title'] = $this->language->get('heading_title_create');

		// Button Text
		$this->data['save_btn'] = $this->language->get('save_btn_create');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_create');

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_create'),
			'href' => $this->url->link('extension/barbarablog/post_create', 'token=' . $this->session->data['token'], 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/postmodify');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$post_id = $this->model_extension_barbarablog_postmodify->createPost($this->request->post);
			if($post_id) {
				$post_review_link = '<a target="_blank" href="' . HTTP_CATALOG . 'index.php?route=barbarablog/single&barbara_post_id=' . $post_id . '">View</a>';
				$this->session->data['success'] = $this->language->get('text_success_create') . '&nbsp;|&nbsp;'.$post_review_link;
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_create');
			}	
		
			$this->response->redirect($this->url->link('extension/barbarablog/post_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/barbarablog/post_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->getForm();
	}

	protected function check_post_id() {
		if(!isset($this->request->get['barbara_post_id'])) {
			$this->response->redirect($this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL'));
		}

		return true;
	}

	public function post_edit() {

		$this->check_post_id();

		$this->pid = $this->request->get['barbara_post_id'];

		$this->load->language('extension/barbarablog/post_form');

		$this->document->setTitle($this->language->get('heading_title_edit'));
		$this->data['heading_title'] = $this->language->get('heading_title_edit');

		$this->data['save_btn'] = $this->language->get('save_btn_edit');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_edit');

		// Breadcrumb
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_edit'),
			'href' => $this->url->link('extension/barbarablog/post_edit', 'token=' . $this->session->data['token'] . '&barbara_post_id='. $this->pid, 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/postmodify');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_extension_barbarablog_postmodify->editPost($this->pid, $this->request->post);

			$post_review_link = '<a target="_blink" href="' . HTTP_CATALOG . 'index.php?route=barbarablog/single&barbara_post_id=' . $this->pid . '">View</a>';
			$this->session->data['success'] = $this->language->get('text_success_edit') . '&nbsp;|&nbsp;'.$post_review_link;
		
			$this->response->redirect($this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/barbarablog/post_edit', 'token=' . $this->session->data['token'] . '&barbara_post_id=' . $this->pid. $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . '&barbara_post_id=' . $this->pid . $url, 'SSL');

		$this->getForm();
	}

	public function post_delete() {

		$this->check_post_id();

		$this->pid = $this->request->get['barbara_post_id'];

		$this->load->language('extension/barbarablog/post');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/postmodify');
		if ($this->pid && $this->validateDelete()) {
			if($this->model_extension_barbarablog_postmodify->deletePost($this->pid)) {
				$this->session->data['success'] = $this->language->get('text_success_delete');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_delete');
			}		

			$this->response->redirect($this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . '&barbara_post_id=' . $this->pid . $url, 'SSL'));
		}	

		$this->getPostList();
	}

	public function post_delete_multiple() {

		$this->load->language('extension/barbarablog/post');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/postmodify');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$success = $this->language->get('text_success_delete') . ' Post ID = ';
			$error = $this->language->get('text_error_delete') . ' Post ID = ';
			$inc = 1;
			foreach ($this->request->post['selected'] as $post_id) {
				// $this->model_catalog_post->deletepost($post_id);
				$this->pid = $post_id;
				if($this->model_extension_barbarablog_postmodify->deletePost($this->pid)) {
					if($inc == 1) {
						$success .= $this->pid;
					} else {
						$success .= ', ' . $this->pid;
					}
				} else {
					if($inc == 1) {
						$error .= $this->pid . ' ';
					} else {
						$error .= ', ' . $this->pid;
					}
				}	
				$inc++;
			}
			$this->session->data['success'] = $success;
			$this->session->data['error_warning'] = $error;

			$this->response->redirect($this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'] . '&barbara_post_id=' . $this->pid . $url, 'SSL'));
		}		

		$this->getPostList();
	}

	protected function getForm() {

		$this->load->model('extension/barbarablog/post');

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

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}
		
		if (isset($this->error['keyword'])) {
			$this->data['error_keyword'] = $this->error['keyword'];
		} else {
			$this->data['error_keyword'] = '';
		}

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Tab Label
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_relation'] = $this->language->get('tab_relation');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_design'] = $this->language->get('tab_design');


		// Button
		$this->data['cancel_btn'] = $this->language->get('cancel_btn');
		$this->data['cancel_btn_tooltip'] = $this->language->get('cancel_btn_tooltip');
		$this->data['image_add'] = $this->language->get('image_add');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['text_default'] = $this->language->get('text_default');

		// Form Label
		$this->data['entry_postTitle'] = $this->language->get('entry_postTitle');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_category_default'] = $this->language->get('entry_category_default');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_date_available'] = $this->language->get('entry_date_available');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_content'] = $this->language->get('entry_content');
		$this->data['entry_excerpt'] = $this->language->get('entry_excerpt');
		$this->data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$this->data['entry_meta_key'] = $this->language->get('entry_meta_key');
		$this->data['entry_thumb'] = $this->language->get('entry_thumb');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_related'] = $this->language->get('entry_related');
		

		// Table column text
		$this->data['col_image'] = $this->language->get('col_image');
		$this->data['col_order'] = $this->language->get('col_order');
		$this->data['col_action'] = $this->language->get('col_action');
		$this->data['col_store'] = $this->language->get('col_store');
		$this->data['col_layout'] = $this->language->get('col_layout');

		$this->data['help_tags'] = $this->language->get('help_tags');
		$this->data['help_keyword'] = $this->language->get('help_keyword');
		$this->data['help_autocomplete'] = $this->language->get('help_autocomplete');

    $this->data['header']       = $this->load->controller('common/header');
    $this->data['topmenu']      = $this->load->controller('module/barbaratheme/topmenu');
    $this->data['column_left']  = $this->load->controller('common/column_left');
    $this->data['footer']       = $this->load->controller('common/footer');

		if ($this->pid && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$post_info = $this->model_extension_barbarablog_post->getPost($this->pid);
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['ckeditor'] = $this->config->get('config_editor_default');
		
      $this->data['category_link'] =$this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['posts_link'] =$this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['setting_link'] =$this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['post_description'])) {
			$this->data['post_description'] = $this->request->post['post_description'];
		} elseif ($this->pid) {
			$this->data['post_description'] = $this->model_extension_barbarablog_post->getPostDescriptions($this->pid);
		} else {
			$this->data['post_description'] = array();
		}

		$this->load->model('extension/barbarablog/category');

		if (isset($this->request->post['post_category'])) {
			$categories = $this->request->post['post_category'];
		} elseif ($this->pid) {
			$categories = $this->model_extension_barbarablog_post->getPostCategories($this->pid);
		} else {
			$categories = array();
		}

		$this->data['post_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_extension_barbarablog_category->getCategory($category_id);
			if ($category_info) {
				$this->data['post_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		$this->load->model('catalog/filter');

		if (isset($this->request->post['post_filter'])) {
			$filters = $this->request->post['post_filter'];
		} elseif ($this->pid) {
			$filters = $this->model_extension_barbarablog_post->getPostFilters($this->pid);
		} else {
			$filters = array();
		}

		$this->data['post_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['post_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($post_info)) {
			$this->data['keyword'] = $post_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($post_info)) {
			$this->data['date_available'] = ($post_info['date_available'] != '0000-00-00') ? $post_info['date_available'] : '';
		} else {
			$this->data['date_available'] = date('Y-m-d');
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($post_info)) {
			$this->data['sort_order'] = $post_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$this->data['status'] = $post_info['post_status'];
		} else {
			$this->data['status'] = '';
		}

		// Post Layout
		if (isset($this->request->post['post_layout'])) {
			$this->data['post_layout'] = $this->request->post['post_layout'];
		} elseif ($this->pid) {
			$this->data['post_layout'] = $this->model_extension_barbarablog_post->getPostLayouts($this->pid);
		} else {
			$this->data['post_layout'] = array();
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();		

		// Post Store
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['post_store'])) {
			$this->data['post_store'] = $this->request->post['post_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['post_store'] = $this->model_extension_barbarablog_post->getPostStores($this->pid);
		} else {
			$this->data['post_store'] = array(0);
		}

		// Related product
		if (isset($this->request->post['related_product'])) {
			$products = $this->request->post['related_product'];
		} elseif (isset($this->pid) && $this->pid) {
			$products = $this->model_extension_barbarablog_post->productByPostid(array('post_id'=>$this->pid));
		} else {
			$products = array();
		}

		$this->data['related_products'] = array();

		$this->load->model('catalog/product');
		if(!empty($products)) {
			for ($i =0; $i < count($products); $i++) {
				$related_product = $this->model_catalog_product->getProduct($products[$i]);
				if ($related_product) {
					$this->data['related_products'][] = array(
						'product_id' => $related_product['product_id'],
						'name'       => $related_product['name']
					);
				}
			}
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($post_info)) {
			$this->data['image'] = $post_info['post_thumb'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($post_info) && is_file(DIR_IMAGE . $post_info['post_thumb'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($post_info['post_thumb'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['post_image'])) {
			$post_images = $this->request->post['post_image'];
		} elseif ($this->pid) {
			$post_images = $this->model_extension_barbarablog_post->getPostImages($this->pid,'image');
		} else {
			$post_images = array();
		}

		$this->data['post_images'] = array();
		$this->load->model('tool/image');
		if(count($post_images) > 0) {
			foreach ($post_images as $post_image) {
				if (is_file(DIR_IMAGE . $post_image['meta_value'])) {
					$image = $post_image['meta_value'];
					$thumb = $post_image['meta_value'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}

				$this->data['post_images'][] = array(
					'image'      => $image,
					'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
					'sort_order' => $post_image['sort_order']
				);
			}
		}
		
		$this->data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->response->setOutput($this->load->view('extension/barbarablog/post_form.tpl', $this->data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/barbarablog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->data['form_error'] = array();

		foreach ($this->request->post['post_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 255)) {
				$this->data['form_error'][$language_id]['title'] = $this->language->get('error_title');
			}

			if (utf8_strlen(strip_tags(html_entity_decode($value['content']))) < 10) {
				$this->data['form_error'][$language_id]['content'] = $this->language->get('error_content');
			}
		}

		if ((!isset($this->request->post['post_category']) || empty($this->request->post['post_category']))) {
			$this->data['form_error']['category'] = $this->language->get('error_category');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['barbara_post_id']) && $url_alias_info['query'] != 'barbara_post_id=' . $this->request->get['barbara_post_id']) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}

			if ($url_alias_info && !isset($this->request->get['barbara_post_id'])) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}
		}

		if ($this->data['form_error'] && !isset($this->error['warning'])) {
      $this->error['warning'] = $this->language->get('error_warning');
      foreach ($this->data['form_error'] as $errors) {
          if (is_array($errors)) {
              foreach ($errors as $error){
                  $this->error['warning'] .= '<br />' . $error;
              }
          } else {
              $this->error['warning'] .= '<br />' . $errors;
          }
      }
		}

		return !$this->error;
	}


	/**
	 * Category Section
	 *=================================================
	 */
	public function category() {

		$this->load->language('extension/barbarablog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getCatList();
	}

	protected function getCatList() {
		$this->load->helper('barbara_blog');
		$this->load->model('extension/barbarablog/setting');
		$settings = $this->model_extension_barbarablog_setting->settings();
		$config = setting($settings);

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c1.date_added';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		// Breadcrumb
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		// Buttons
		$data['category_create_link'] = $this->url->link('extension/barbarablog/category_create', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_edit_link'] = $this->url->link('extension/barbarablog/category_edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_delete_multiple'] = $this->url->link('extension/barbarablog/category_delete_multiple', 'token=' . $this->session->data['token'], 'SSL');
		$data['category_delete_link'] = $this->url->link('extension/barbarablog/category_delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];

		$limit = isset($config['category_limit_admin']) ? $config['category_limit_admin'] : $this->cat_limit;

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_status'   => $filter_status,
			'sort' 			  => $sort,
			'order' 		  => $order,
			'start' 		  => ($page - 1) * $limit,
			'limit'			  => $limit
		);

		$this->load->model('tool/image');

		$this->load->model('extension/barbarablog/category');

		$total_category = $this->model_extension_barbarablog_category->getTotalCategories($filter_data);
		$categories = $this->model_extension_barbarablog_category->getCategories($filter_data);

		if(is_array($categories) && count($categories) > 0) {
			foreach ($categories as $category) {
				
				if (is_file(DIR_IMAGE . $category['image'])) {
					$image = $this->model_tool_image->resize($category['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}

				$data['categories'][] = array(
					'category_id' 		 	=> $category['category_id'],
					'image'      	=> $image,
					'category_name'    => $category['name'],
					'status'   => $category['status'],
					'sort_order'   => $category['sort_order'],
				);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		// Menubar Label
		$data['post_menu'] = $this->language->get('post');
		$data['category_menu'] = $this->language->get('category');
		$data['setting_menu'] = $this->language->get('setting');
		$data['help_menu'] = $this->language->get('help');

		// Button Label
		$data['create_btn'] = $this->language->get('create_btn');
		$data['delete_btn'] = $this->language->get('delete_btn');
		$data['create_btn_tooltip'] = $this->language->get('create_btn_tooltip');
		$data['delete_btn_tooltip'] = $this->language->get('delete_btn_tooltip');
		$data['button_filter'] = $this->language->get('button_filter');

		// Filter box text
		$data['filter_text_name'] = $this->language->get('filter_text_name');
		$data['filter_text_status'] = $this->language->get('filter_text_status');
		$data['filter_text_publish'] = $this->language->get('filter_text_publish');
		$data['filter_text_unpublish'] = $this->language->get('filter_text_unpublish');

		// Table Column Label
		$data['col_title'] = $this->language->get('col_title');
		$data['col_thumb'] = $this->language->get('col_thumb');
		$data['col_status'] = $this->language->get('col_status');
		$data['col_order'] = $this->language->get('col_order');
		$data['col_action'] = $this->language->get('col_action');

		// Text
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_not_found'] = $this->language->get('text_not_found');
		
		$data['category_link'] =$this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL');
		$data['posts_link'] =$this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL');
		$data['setting_link'] =$this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL');
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . '&sort=cd1.name' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . '&sort=c1.sort_order' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . '&sort=c1.status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

		// Pagination
		$pagination = new Pagination();
		$pagination->total = $total_category;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_category) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_category - $limit)) ? $total_category : ((($page - 1) * $limit) + $limit), $total_category, ceil($total_category / $limit));
		
		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

    $data['header']       = $this->load->controller('common/header');
    $data['topmenu']      = $this->load->controller('module/barbaratheme/topmenu');
    $data['column_left']  = $this->load->controller('common/column_left');
    $data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/barbarablog/category.tpl', $data));
	}

	public function category_create() {
		$this->load->language('extension/barbarablog/category_form');
		$this->document->setTitle($this->language->get('heading_title_create'));
		$this->data['heading_title'] = $this->language->get('heading_title_create');

		$this->data['save_btn'] = $this->language->get('save_btn_create');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_create');

		$this->load->model('extension/barbarablog/post');

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_create'),
			'href' => $this->url->link('extension/barbarablog/category_create', 'token=' . $this->session->data['token'], 'SSL')
		);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatecatForm()) {
			$this->load->model('extension/barbarablog/catmodify');
			$cid = $this->model_extension_barbarablog_catmodify->createCategory($this->request->post);
			if($cid) {
				$this->session->data['success'] = $this->language->get('text_success_create');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_create');
			}	

			$this->response->redirect($this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/barbarablog/category_create', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->getcategoryForm();
	}

	protected function check_category_id() {
		if(!isset($this->request->get['cid'])) {
			$this->response->redirect($this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL'));
		}

		return true;
	}

	public function category_edit() {

		$this->check_category_id();

		$this->cid = (int)$this->request->get['cid'];

		$this->load->language('extension/barbarablog/category_form');
		$this->document->setTitle($this->language->get('heading_title_edit'));
		$this->data['heading_title'] = $this->language->get('heading_title_edit');

		$this->data['save_btn'] = $this->language->get('save_btn_edit');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip_edit');

		// Breadcrumb
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('base_title'),
			'href' => $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_edit'),
			'href' => $this->url->link('extension/barbarablog/category_edit', 'token=' . $this->session->data['token'] . '&cid=' . $this->cid, 'SSL')
		);
		

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/catmodify');
         
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatecatForm()) {

			$this->model_extension_barbarablog_catmodify->editCategory($this->cid, $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success_edit');
				
			$this->response->redirect($this->url->link('extension/barbarablog/category_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action'] = $this->url->link('extension/barbarablog/category_edit', 'token=' . $this->session->data['token'] . '&cid=' . $this->cid . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . '&cid=' . $this->cid . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->getcategoryForm();
	}

	public function category_delete() {

		$this->check_category_id();

		$this->cid = $this->request->get['cid'];

		$this->load->language('extension/barbarablog/category');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/catmodify');
		if ($this->cid && $this->validateDelete()) {
			if($this->model_extension_barbarablog_catmodify->deleteCategory($this->cid)) {
				$this->session->data['success'] = $this->language->get('text_success_delete');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error_delete');
			}

			$this->response->redirect($this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}			
		
		$this->getCatList();
	}

	public function category_delete_multiple() {

		$this->load->language('extension/barbarablog/category');
		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
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

		$this->load->model('extension/barbarablog/catmodify');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			$success = $this->language->get('text_success_delete') . ' Category ID = ';
			$error = $this->language->get('text_error_delete') . ' Category ID = ';

			$inc = 1;
			foreach ($this->request->post['selected'] as $category_id) {
				$this->cid = $category_id;
				if($this->model_extension_barbarablog_catmodify->deleteCategory($this->cid)) {
					if($inc == 1) {
						$success .= $this->cid;
					} else {
						$success .= ', ' . $this->cid;
					}
				} else {
					if($inc == 1) {
						$error .= $this->cid . ' ';
					} else {
						$error .= ', ' . $this->cid;
					}
				}	
				$inc++;
			}

			$this->session->data['success'] = $success;
			$this->session->data['error_warning'] = $error;

			$this->response->redirect($this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}			
		
		$this->getCatList();
	}

	public function category_autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/barbarablog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort' => 'cd1.name',
				'order' => 'ASC',
				'start' => 0,
				'limit' => 20
			);

			$results = $this->model_extension_barbarablog_category->getCategories($filter_data);

			if($results) {
				foreach ($results as $result) {
					$json[] = array(
						'category_id' 		=> $result['category_id'],
						'name'     => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					);
				}
			}
			
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function getcategoryForm() {
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

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}
		
		if (isset($this->error['keyword'])) {
			$this->data['error_keyword'] = $this->error['keyword'];
		} else {
			$this->data['error_keyword'] = '';
		}

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Text
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_none'] = $this->language->get('text_none');

		// Tab Label
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

		// Button
		$this->data['cancel_btn'] = $this->language->get('cancel_btn');
		$this->data['cancel_btn_tooltip'] = $this->language->get('cancel_btn_tooltip');

		// Form Label
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_slug'] = $this->language->get('entry_slug');
		$this->data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_thumb'] = $this->language->get('entry_thumb');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_date'] = $this->language->get('entry_date');

		$this->data['col_store'] = $this->language->get('col_store');
		$this->data['col_layout'] = $this->language->get('col_layout');

		$this->data['help_autocomplete'] = $this->language->get('help_autocomplete');
		$this->data['help_keyword'] = $this->language->get('help_keyword');

		$this->load->model('extension/barbarablog/category');
		if ($this->cid && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->model_extension_barbarablog_category->getCategory($this->cid);	
		} 

		$this->data['token'] = $this->session->data['token'];
		$this->data['ckeditor'] = $this->config->get('config_editor_default');
		
		$this->data['category_link'] =$this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['posts_link'] =$this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['setting_link'] =$this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL');
		

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['cat_description'])) {
			$this->data['cat_description'] = $this->request->post['cat_description'];
		} elseif (isset($this->request->get['cid'])) {
			$this->data['cat_description'] = $this->model_extension_barbarablog_category->getCatDescriptions($this->cid);
		} else {
			$this->data['cat_description'] = array();
		}

		if (isset($this->request->post['path'])) {
			$this->data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$this->data['path'] = $category_info['path'];
		} else {
			$this->data['path'] = '';
		}

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
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
			$this->data['status'] = '';
		}

		// Category Layout
		if (isset($this->request->post['category_layout'])) {
			$this->data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->cid)) {
			$this->data['category_layout'] = $this->model_extension_barbarablog_category->getCategoryLayouts($this->cid);
		} else {
			$this->data['category_layout'] = array();
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('catalog/filter');

		if (isset($this->request->post['category_filter'])) {
			$filters = $this->request->post['category_filter'];
		} elseif ($this->cid) {
			$filters = $this->model_extension_barbarablog_category->getCategoryFilters($this->cid);
		} else {
			$filters = array();
		}

		// Category filters
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

		// Category store
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['category_store'])) {
			$this->data['category_store'] = $this->request->post['category_store'];
		} elseif ($this->cid) {
			$this->data['category_store'] = $this->model_extension_barbarablog_category->getCategoryStores($this->cid);
		} else {
			$this->data['category_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($category_info)) {
			$this->data['keyword'] = $category_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		// print_r($this->data['keyword']); die();

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$this->data['image'] = $category_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		 $this->data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

    $this->data['header']       = $this->load->controller('common/header');
    $this->data['topmenu']      = $this->load->controller('module/barbaratheme/topmenu');
    $this->data['column_left']  = $this->load->controller('common/column_left');
    $this->data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/barbarablog/category_form.tpl', $this->data));
	}

	protected function validatecatForm() {
		if (!$this->user->hasPermission('modify', 'extension/barbarablog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->data['form_error'] = array();

		foreach ($this->request->post['cat_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 50)) {
				$this->data['form_error'][$language_id]['name'] = $this->language->get('error_name');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);
		
			if ($url_alias_info && isset($this->request->get['cid']) && $url_alias_info['query'] != 'barbara_blog_category_id=' . $this->request->get['cid']) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}

			if ($url_alias_info && !isset($this->request->get['cid'])) {
				$this->data['form_error']['keyword'] = $this->language->get('error_keyword');
			}
		}		

		if ($this->data['form_error'] && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}



	

	/**
	 * Setting
	 *==========================================================================================
	 */
	public function setting() {

		$this->load->language('extension/barbarablog/setting');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		// Menubar Label
		$this->data['post_menu'] = $this->language->get('post');
		$this->data['category_menu'] = $this->language->get('category');
		$this->data['setting_menu'] = $this->language->get('setting');
		$this->data['help_menu'] = $this->language->get('help');

		// Button Label
		$this->data['save_btn'] = $this->language->get('save_btn');
		$this->data['save_btn_tooltip'] = $this->language->get('save_btn_tooltip');
		$this->data['button_reinstall'] = $this->language->get('button_reinstall');
		$this->data['button_uninstall'] = $this->language->get('button_uninstall');

		// Tab lebel
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_color'] = $this->language->get('tab_color');
		$this->data['tab_seo'] = $this->language->get('tab_seo');
		$this->data['tab_install'] = $this->language->get('tab_install');

		// Table Column Label
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_name2'] = $this->language->get('entry_name2');
		$this->data['entry_relproduct'] = $this->language->get('entry_relproduct');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_date'] = $this->language->get('entry_date');
		
		$this->data['col_settingName'] = $this->language->get('col_settingName');
		$this->data['col_settingContent'] = $this->language->get('col_settingContent');
		$this->data['col_settingSortOrder'] = $this->language->get('col_settingSortOrder');

		
		$this->data['sett'] = array();
		$this->data['sett'][0] = $this->language->get('setting_0');
		$this->data['sett'][1] = $this->language->get('setting_1');
		$this->data['sett'][2] = $this->language->get('setting_2');
		$this->data['sett'][3] = $this->language->get('setting_3');
		$this->data['sett'][4] = $this->language->get('setting_4');
		$this->data['sett'][5] = $this->language->get('setting_5');
		
		$this->data['sett2'] = array();
		$this->data['sett2'][0] = $this->language->get('setting_10');
		$this->data['sett2'][1] = $this->language->get('setting_11');
		$this->data['sett2'][2] = $this->language->get('setting_12');
		$this->data['sett2'][3] = $this->language->get('setting_13');
		$this->data['sett2'][4] = $this->language->get('setting_14');
		$this->data['sett2'][5] = $this->language->get('setting_15');
		
		

		// Breadcrumb
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatesetForm()) {

			// print_r($this->request->post); die();
			$this->load->model('extension/barbarablog/setting');

			if($this->model_extension_barbarablog_setting->editSetting($this->request->post)) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['error_warning'] = $this->language->get('text_error');
			}			

			$this->response->redirect($this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// Buttons
		$this->data['action'] = $this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'] . '&action=edit', 'SSL');
		$this->data['cancel'] = $this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'] . '&action=cancel', 'SSL');
		$this->data['reinstall'] = $this->url->link('extension/barbarablog/reinstall', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['uninstall'] = $this->url->link('extension/barbarablog/uninstall', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['category_link'] =$this->url->link('extension/barbarablog/category', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['posts_link'] =$this->url->link('extension/barbarablog', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['setting_link'] =$this->url->link('extension/barbarablog/setting', 'token=' . $this->session->data['token'], 'SSL');

		// Alert Message
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

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();


		$this->load->model('extension/barbarablog/setting');
		$total_setting = $this->model_extension_barbarablog_setting->total_settings();

		$general_setting = $this->model_extension_barbarablog_setting->setting_general();
		// $general = array();
		foreach ($general_setting as $key => $setting) {
			$this->data['general_setting'][$setting['language_id']][$setting['setting_name']] = $setting['setting_value'];
		}

		$this->data['setting_option'] = $this->model_extension_barbarablog_setting->settings(array("setting_keyword"=>"='option'"),"position");
		$this->data['setting_image'] = $this->model_extension_barbarablog_setting->settings(array("setting_keyword"=>"='image'"),"position");
		$this->data['setting_color'] = $this->model_extension_barbarablog_setting->settings(array("setting_keyword"=>"='color'"),"position");

		foreach ($this->data['setting_image'] as $image) {
			
			if($image['setting_name'] == 'logo_image_size') {
				$logo_size = $image['setting_value'];
				$size = explode('x', $logo_size);
				$logo_width = isset($size[0]) ? $size[0] : 100;
				$logo_height = isset($size[1]) ? $size[1] : 100;
			}	

			if($image['setting_name'] == 'icon_image_size') {
				$icon_size = $image['setting_value'];
				$size = explode('x', $icon_size);
				$icon_width = isset($size[0]) ? $size[0] : 30;
				$icon_height = isset($size[1]) ? $size[1] : 30;
			}
		}


		$this->load->model('tool/image');

		
    $this->data['header']       = $this->load->controller('common/header');
    $this->data['topmenu']      = $this->load->controller('module/barbaratheme/topmenu');
    $this->data['column_left']  = $this->load->controller('common/column_left');
    $this->data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/barbarablog/setting.tpl', $this->data));
	}

    public function install() {              
        $this->load->model('extension/barbarablog/setting');
        $this->model_extension_barbarablog_setting->install();
    }

    public function reinstall() {              
        $this->load->language('extension/barbarablog/setting');
        if (!$this->user->hasPermission('modify', 'extension/barbarablog')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $json['success'] = $this->language->get('text_reinstall_ok');
            $this->load->model('extension/barbarablog/setting');
            $this->model_extension_barbarablog_setting->uninstall();
            $this->model_extension_barbarablog_setting->install();
        }
                                  
      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    }

    public function uninstall() {              
        $this->load->language('extension/barbarablog/setting');
        if (!$this->user->hasPermission('modify', 'extension/barbarablog')) {
            $json['error'] = $this->language->get('error_permission');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        } else {
            $json['success'] = $this->language->get('text_uninstall_ok');
            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
            $this->load->model('extension/barbarablog/setting');
            $this->model_extension_barbarablog_setting->uninstall();
        }
      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    }

	protected function validatesetForm() {
		if (!$this->user->hasPermission('modify', 'extension/barbarablog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/barbarablog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
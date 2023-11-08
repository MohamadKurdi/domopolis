<?php
class ControllerCatalogCategoryreview extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/categoryreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/categoryreview');

		$this->getList();
	} 

	public function insert() {
		$this->language->load('catalog/categoryreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/categoryreview');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_categoryreview->addCategoryreview($this->request->post);

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

			$this->redirect($this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/categoryreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/categoryreview');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_categoryreview->editCategoryreview($this->request->get['categoryreview_id'], $this->request->post);

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

			$this->redirect($this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->language->load('catalog/categoryreview');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/categoryreview');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $categoryreview_id) {
				$this->model_catalog_categoryreview->deleteCategoryreview($categoryreview_id);
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

			$this->redirect($this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cr.date_added';
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
			'href'      => $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/categoryreview/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] = $this->url->link('catalog/categoryreview/delete', 'token=' . $this->session->data['token'] . $url);

		$this->data['enabled'] = $this->url->link('catalog/categoryreview/enable', 'token=' . $this->session->data['token'] . $url);
        $this->data['disabled'] = $this->url->link('catalog/categoryreview/disable', 'token=' . $this->session->data['token'] . $url);

		$this->data['categoryreviews'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$categoryreview_total = $this->model_catalog_categoryreview->getTotalCategoryreviews();

		$results = $this->model_catalog_categoryreview->getCategoryreviews($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/categoryreview/update', 'token=' . $this->session->data['token'] . '&categoryreview_id=' . $result['categoryreview_id'] . $url, 'SSL')
			);

			$this->data['categoryreviews'][] = array(
				'categoryreview_id'  => $result['categoryreview_id'],
				'name'       => $result['name'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['categoryreview_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_category'] = $this->language->get('column_category');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_rating'] = $this->language->get('column_rating');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		$this->data['button_enable'] = $this->language->get('button_enable');
        $this->data['button_disable'] = $this->language->get('button_disable');

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

		$this->data['sort_category'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . '&sort=сd.name' . $url);
		$this->data['sort_author'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . '&sort=cr.author' . $url);
		$this->data['sort_rating'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . '&sort=cr.rating' . $url);
		$this->data['sort_status'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . '&sort=cr.status' . $url);
		$this->data['sort_date_added'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . '&sort=cr.date_added' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $categoryreview_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/categoryreview_list.tpl';
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
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['entry_date_added'] = $this->language->get('entry_date_added');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['category'])) {
			$this->data['error_category'] = $this->error['category'];
		} else {
			$this->data['error_category'] = '';
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
			'href'      => $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['categoryreview_id'])) { 
			$this->data['action'] = $this->url->link('catalog/categoryreview/insert', 'token=' . $this->session->data['token'] . $url);
		} else {
			$this->data['action'] = $this->url->link('catalog/categoryreview/update', 'token=' . $this->session->data['token'] . '&categoryreview_id=' . $this->request->get['categoryreview_id'] . $url);
		}

		$this->data['cancel'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url);

		if (isset($this->request->get['categoryreview_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$categoryreview_info = $this->model_catalog_categoryreview->getCategoryreview($this->request->get['categoryreview_id']);
		}

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('catalog/category');

		if (isset($this->request->post['category_id'])) {
			$this->data['category_id'] = $this->request->post['category_id'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['category_id'] = $categoryreview_info['category_id'];
		} else {
			$this->data['category_id'] = '';
		}

		if (isset($this->request->post['category'])) {
			$this->data['category'] = $this->request->post['category'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['category'] = $categoryreview_info['category'];
		} else {
			$this->data['category'] = '';
		}

		if (isset($this->request->post['author'])) {
			$this->data['author'] = $this->request->post['author'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['author'] = $categoryreview_info['author'];
		} else {
			$this->data['author'] = '';
		}
		
		if (isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['date_added'] = $categoryreview_info['date_added'];
		} else {
			$this->data['date_added'] = date('Y-m-d H:00:00');
		}

		if (isset($this->request->post['text'])) {
			$this->data['text'] = $this->request->post['text'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['text'] = $categoryreview_info['text'];
		} else {
			$this->data['text'] = '';
		}

		if (isset($this->request->post['rating'])) {
			$this->data['rating'] = $this->request->post['rating'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['rating'] = $categoryreview_info['rating'];
		} else {
			$this->data['rating'] = '';
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($categoryreview_info)) {
			$this->data['status'] = $categoryreview_info['status'];
		} else {
			$this->data['status'] = '';
		}

		$this->template = 'catalog/categoryreview_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/categoryreview')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['category_id']) {
			$this->error['category'] = $this->language->get('error_category');
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
	
		public function enable() {
        $this->load->language('catalog/categoryreview');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/categoryreview');

        if (isset($this->request->post['selected'])) {

            foreach ($this->request->post['selected'] as $categoryreview_id) {
                $data = array();

                $result = $this->model_catalog_categoryreview->getCategoryreview($categoryreview_id);

                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }

                $data['status'] = 1;

                $this->model_catalog_categoryreview->editCategoryreview($categoryreview_id, $data);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->redirect($this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function disable() {
        $this->load->language('catalog/categoryreview');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/categoryreview');

        if (isset($this->request->post['selected'])) {

            foreach ($this->request->post['selected'] as $categoryreview_id) {
                $data = array();

                $result = $this->model_catalog_categoryreview->getCategoryreview($categoryreview_id);

                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }

                $data['status'] = 0;

                $this->model_catalog_categoryreview->editCategoryreview($categoryreview_id, $data);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $this->redirect($this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/categoryreview')) {
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
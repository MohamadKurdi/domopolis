<?php
class ControllerLocalisationProductGroups extends Controller {

	public function index() {
		$this->language->load('localisation/product_groups');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/product_groups');


		$this->getList();
	}

	public function insert() {
		$this->data = $this->language->load('localisation/product_groups');
		$this->load->model('localisation/product_groups');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_product_groups->addProductGroup($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('localisation/product_groups', 'token=' . $this->session->data['token']));

		}

		$this->getForm();
	}

	public function update() {
		$this->data = $this->language->load('localisation/product_groups');
		$this->load->model('localisation/product_groups');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_product_groups->editProductGroup($this->request->get['product_group_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('localisation/product_groups', 'token=' . $this->session->data['token']));

		}

		$this->getForm();
	}

	public function delete() {
		$this->data = $this->language->load('localisation/product_groups');
		$this->load->model('localisation/product_groups');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_group_id) {
				$this->model_localisation_product_groups->deleteProductGroup($product_group_id);  
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('localisation/product_groups', 'token=' . $this->session->data['token']));

		}

		$this->getList();
	}

	protected function getList() {
		$this->data = $this->language->load('localisation/product_groups');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

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

		$this->data['insert'] = $this->url->link('localisation/product_groups/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] = $this->url->link('localisation/product_groups/delete', 'token=' . $this->session->data['token'] . $url);	


		$data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$product_group_total = $this->model_localisation_product_groups->getTotalProductGroups();

		$results = $this->model_localisation_product_groups->getProductGroups($data);

		$this->data['product_groups'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/product_groups/update', 'token=' . $this->session->data['token'] . '&product_group_id=' . $result['product_group_id'])
			);

			$this->data['product_groups'][] = array(
				'product_group_id' 			=> $result['product_group_id'],
				'product_group_name'    	=> $result['product_group_name'],
				'product_group_feed'       	=> $result['product_group_feed'],
				'product_group_feed_file'  	=> $result['product_group_feed_file'],
				'product_group_text_color'  => $result['product_group_text_color'],
				'product_group_bg_color'    => $result['product_group_bg_color'],
				'product_group_fa_icon'    	=> $result['product_group_fa_icon'],
				'selected'   		=> isset($this->request->post['selected']) && in_array($result['product_group_id'], $this->request->post['selected']),
				'action'     		=> $action
			);
		}

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

		$pagination 		= new Pagination();
		$pagination->total 	= $product_group_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $this->config->get('config_admin_limit');
		$pagination->text 	= $this->language->get('text_pagination');
		$pagination->url 	= $this->url->link('localisation/product_groups', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'localisation/product_groups_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data = $this->language->load('localisation/product_groups');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
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

		if (!isset($this->request->get['product_group_id'])) {
			$this->data['action'] = $this->url->link('localisation/product_groups/insert', 'token=' . $this->session->data['token'] . $url);
		} else {
			$this->data['action'] = $this->url->link('localisation/product_groups/update', 'token=' . $this->session->data['token'] . '&product_group_id=' . $this->request->get['product_group_id'] . $url);
		}

		$this->data['cancel'] = $this->url->link('localisation/product_groups', 'token=' . $this->session->data['token'] . $url);

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home') 
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'), 
			'href' => $this->url->link('localisation/product_groups')
		);

		$this->load->model('localisation/product_groups');

		if (!empty($this->request->get['product_group_id'])){
			$product_group_info = $this->model_localisation_product_groups->getProductGroup($this->request->get['product_group_id']);
		}

		if (isset($this->request->post['product_group_name'])) {
			$this->data['product_group_name'] = $this->request->post['product_group_name'];
		} elseif (!empty($product_group_info)) {
			$this->data['product_group_name'] = $product_group_info['product_group_name'];
		} else {
			$this->data['product_group_name'] = '';
		}

		if (isset($this->request->post['product_group_feed'])) {
			$this->data['product_group_feed'] = $this->request->post['product_group_feed'];
		} elseif (!empty($product_group_info)) {  
			$this->data['product_group_feed'] = $product_group_info['product_group_feed'];
		} else {
			$this->data['product_group_feed'] = '';
		}

		if (isset($this->request->post['product_group_feed_file'])) {
			$this->data['product_group_feed_file'] = $this->request->post['product_group_feed_file'];
		} elseif (!empty($product_group_info)) {
			$this->data['product_group_feed_file'] = $product_group_info['product_group_feed_file'];
		} else {
			$this->data['product_group_feed_file'] = '';
		}

		if (isset($this->request->post['product_group_text_color'])) {
			$this->data['product_group_text_color'] = $this->request->post['product_group_text_color'];
		} elseif (!empty($product_group_info)) {
			$this->data['product_group_text_color'] = $product_group_info['product_group_text_color'];
		} else {
			$this->data['product_group_text_color'] = '';
		}

		if (isset($this->request->post['product_group_bg_color'])) {
			$this->data['product_group_bg_color'] = $this->request->post['product_group_bg_color'];
		} elseif (!empty($product_group_info)) {
			$this->data['product_group_bg_color'] = $product_group_info['product_group_bg_color'];
		} else {
			$this->data['product_group_bg_color'] = '';
		}

		if (isset($this->request->post['product_group_fa_icon'])) {
			$this->data['product_group_fa_icon'] = $this->request->post['product_group_fa_icon'];
		} elseif (!empty($product_group_info)) {
			$this->data['product_group_fa_icon'] = $product_group_info['product_group_fa_icon'];
		} else {
			$this->data['product_group_fa_icon'] = '';
		}

		$this->template = 'localisation/product_groups_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/product_groups')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}	

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/product_groups')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else { 
			return false;
		}
	}
}
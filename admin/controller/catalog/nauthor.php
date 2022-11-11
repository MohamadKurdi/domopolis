<?php    
class ControllerCatalogNauthor extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		$url = '';
			$this->model_catalog_nauthor->addAuthor($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		$url = '';
			$this->model_catalog_nauthor->editAuthor($this->request->get['nauthor_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
		$url = '';
			foreach ($this->request->post['selected'] as $nauthor_id) {
				$this->model_catalog_nauthor->deleteAuthor($nauthor_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

		$url = '';
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/nauthor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/nauthor/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['nauthors'] = array();

		$results = $this->model_catalog_nauthor->getAuthors();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/nauthor/update', 'token=' . $this->session->data['token'] . '&nauthor_id=' . $result['nauthor_id'] . $url, 'SSL')
			);

			$this->data['nauthors'][] = array(
				'nauthor_id' => $result['nauthor_id'],
				'name'            => $result['name'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['nauthor_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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

		$this->template = 'catalog/nauthor_list.tpl';
		$this->children = array(
			'common/header',
			'common/newspanel',	
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$url = '';
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');		

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_adminid'] = $this->language->get('entry_adminid');
		$this->data['entry_ctitle'] = $this->language->get('entry_ctitle');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['nauthor_id'])) {
			$this->data['action'] = $this->url->link('catalog/nauthor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/nauthor/update', 'token=' . $this->session->data['token'] . '&nauthor_id=' . $this->request->get['nauthor_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['nauthor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$nauthor_info = $this->model_catalog_nauthor->getAuthor($this->request->get['nauthor_id']);
		}

		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['nauthor_description'])) {
			$this->data['nauthor_description'] = $this->request->post['nauthor_description'];
		} elseif (isset($this->request->get['nauthor_id'])) {
			$this->data['nauthor_description'] = $this->model_catalog_nauthor->getNauthorDescriptions($this->request->get['nauthor_id']);
		} else {
			$this->data['nauthor_description'] = array();
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($nauthor_info)) {
			$this->data['name'] = $nauthor_info['name'];
		} else {	
			$this->data['name'] = '';
		}

		if (isset($this->request->post['adminid'])) {
			$this->data['adminid'] = $this->request->post['adminid'];
		} elseif (!empty($nauthor_info)) {
			$this->data['adminid'] = $nauthor_info['adminid'];
		} else {	
			$this->data['adminid'] = 0;
		}
		
		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($nauthor_info)) {
			$this->data['keyword'] = $nauthor_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($nauthor_info)) {
			$this->data['image'] = $nauthor_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($nauthor_info) && $nauthor_info['image'] && file_exists(DIR_IMAGE . $nauthor_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($nauthor_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->template = 'catalog/nauthor_form.tpl';
		$this->children = array(
			'common/header',
			'common/newspanel',	
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}  

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/nauthor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/nauthor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/news');

		foreach ($this->request->post['selected'] as $nauthor_id) {
			$news_total = $this->model_catalog_news->getTotalNewsByAuthorId($nauthor_id);

			if ($news_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $news_total);
			}	
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}  
	}
}
?>
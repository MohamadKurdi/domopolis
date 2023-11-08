<?php
class ControllerCatalogShortNames extends Controller {

	public function index() {
		$this->getList();
	}

	public function shortbyai(){
		$name 			= $this->request->post['name'];
		$language_code 	= $this->request->post['language_code'];

		if ($result = $this->openaiAdaptor->shortenName($name, $language_code)){
			$this->response->setOutput($result);
		} else {
			$this->response->setOutput('');
		}
	}

	public function exportbyai(){
		$name 			= $this->request->post['name'];
		$language_code 	= $this->request->post['language_code'];

		if ($result = $this->openaiAdaptor->exportName($name, $language_code)){
			$this->response->setOutput($result);
		} else {
			$this->response->setOutput('');
		}
	}

	protected function getList() {
		$this->load->language('report/product_viewed');			
		$this->document->setTitle('Редактор экспортных наименований товаров');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['token'] = $this->session->data['token'];


		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$this->data['breadcrumbs'][] = array(
			'text' => 'Редактор экспортных наименований товаров',
			'href' => $this->url->link('catalog/shortnames', 'token=' . $this->session->data['token'], true)
		);


		$this->load->model('report/product');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('user/user');
		$this->load->model('tool/image');

		$this->config->set('config_limit_admin', 100);

		$filter_data = array(
			'start' 			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 			=> $this->config->get('config_limit_admin')
		);

		$this->data['products'] = [];

		$product_total 	= $this->model_report_product->getTotalProductsWithNoShortNames($filter_data);			
		$results 		= $this->model_report_product->getProductsWithNoShortNames($filter_data);

		foreach ($results as $result) {	
			
			$this->data['products'][] = array(
				'asin'    			=> $result['asin'],
				'product_id'		=> $result['product_id'],
				'native_name'		=> $result['native_name'],
				'amazon_name'		=> $result['amazon_name'],
				'native_short_name'	=> $result['native_short_name'],
				'amazon_short_name'	=> $result['amazon_short_name'],
				'status'			=> ($result['product_id'] > 0)?$result['status']:false,
				'edit'				=> ($result['product_id'] > 0)?$this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'):false,
				'view'				=> ($result['product_id'] > 0)?(HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id']):false,					
			);
		}

		$this->data['heading_title'] 	= 'Редактор экспортных наименований товаров';
		$this->data['text_list'] 		= $this->language->get('text_list');
		$this->data['text_no_results'] 	= $this->language->get('text_no_results');
		$this->data['text_confirm'] 	= $this->language->get('text_confirm');
		$this->data['text_none'] 		= $this->language->get('text_none');
		$this->data['column_name'] 		= $this->language->get('column_name');
		$this->data['column_model'] 	= $this->language->get('column_model');
		$this->data['column_viewed'] 	= $this->language->get('column_viewed');
		$this->data['column_percent'] 	= $this->language->get('column_percent');

		$this->data['button_reset'] = $this->language->get('button_reset');

		$this->data['native_language'] = $this->registry->get('languages')[$this->config->get('config_language')];
		$this->data['amazon_language'] = $this->registry->get('languages')[$this->config->get('config_de_language')];

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];				
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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
		$pagination->total 	= $product_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $this->config->get('config_admin_limit');
		$pagination->text 	= $this->language->get('text_pagination');
		$pagination->url 	= $this->url->link('catalog/shortnames',  'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'catalog/shortnames.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function write(){		
		$this->load->model('module/seogen');
		$this->load->model('catalog/product');

		if ($this->user->hasPermission('modify', 'catalog/product')) {
			if ($this->request->method == 'POST'){

				$languages_to_edit_simultanelously = [];
				foreach ($this->config->get('config_edit_simultaneously') as $language_code){
					$languages_to_edit_simultanelously[] = $this->registry->get('languages')[$language_code]['language_id'];
				}

				if ($this->request->data['name'] == 'full'){

					$this->db->query("UPDATE product_description SET name = '" . $this->db->escape($this->request->data['text']) . "' WHERE product_id = '" . (int)$this->request->data['product_id'] . "' AND language_id = '" . (int)$this->request->data['language_id'] . "'");

					$this->model_catalog_product->syncProductNamesInOrders($this->request->data['product_id'], $this->request->data['language_id'], $this->request->data['text']);

					$this->model_module_seogen->urlifyProduct($this->request->data['product_id'], $this->request->data['language_id']);

					if ($this->config->get('config_edit_simultaneously')){						
						if (in_array($this->request->data['language_id'], $languages_to_edit_simultanelously)){
							foreach ($languages_to_edit_simultanelously as $language_id){
								if ($this->request->data['language_id'] != $language_id){
									$this->db->query("UPDATE product_description SET name = '" . $this->db->escape($this->request->data['text']) . "' WHERE product_id = '" . (int)$this->request->data['product_id'] . "' AND language_id = '" . (int)$language_id . "'");

									$this->model_module_seogen->urlifyProduct($this->request->data['product_id'], $language_id);
								}
							}
						}						
					}					

				} elseif ($this->request->data['name'] == 'short') {

					$this->db->query("UPDATE product_description SET short_name_d = '" . $this->db->escape($this->request->data['text']) . "' WHERE product_id = '" . (int)$this->request->data['product_id'] . "' AND language_id = '" . (int)$this->request->data['language_id'] . "'");

					if ($this->config->get('config_edit_simultaneously')){						
						if (in_array($this->request->data['language_id'], $languages_to_edit_simultanelously)){
							foreach ($languages_to_edit_simultanelously as $language_id){
								if ($this->request->data['language_id'] != $language_id){
									$this->db->query("UPDATE product_description SET short_name_d = '" . $this->db->escape($this->request->data['text']) . "' WHERE product_id = '" . (int)$this->request->data['product_id'] . "' AND language_id = '" . (int)$language_id . "'");
								}
							}
						}						
					}

					if ($this->request->data['language_id'] == $this->registry->get('languages')[$this->config->get('config_language')]['language_id']){
						$this->db->query("UPDATE product SET short_name = '" . $this->db->escape($this->request->data['text']) . "' WHERE product_id = '" . (int)$this->request->data['product_id'] . "'");
					}

					if ($this->request->data['language_id'] == $this->registry->get('languages')[$this->config->get('config_de_language')]['language_id']){
						$this->db->query("UPDATE product SET short_name_de = '" . $this->db->escape($this->request->data['text']) . "' WHERE product_id = '" . (int)$this->request->data['product_id'] . "'");
					}			
				}	

				$this->load->model('kp/content');
				$this->model_kp_content->addContent(['action' => 'edit', 'entity_type' => 'product', 'entity_id' => $this->request->data['product_id']]);
			}
		}
	}
}
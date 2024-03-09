<?php
class ControllerCatalogShortNames2 extends Controller {

	public function index() {
		$this->getList();
	}

	protected function getList() {
		$this->load->language('report/product_viewed');			
		$this->document->setTitle('Редактор наименований заказанных товаров');

		$filters = [
			'filter_date_from' 	=> date('Y-m-d'),
			'filter_date_to' 	=> date('Y-m-d'),
		];

		if (date('N') == 1){
			$filters['filter_date_from'] = date('Y-m-d', strtotime('-2 day'));
		}

		$data = [];

		foreach ($filters as $filter => $default_value){
			if (isset($this->request->get[$filter])) {
				${$filter} = $this->request->get[$filter];
			} else {
				${$filter} = $default_value;
			}

			$this->data[$filter] = ${$filter};
			$data[$filter] = ${$filter};
		}

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
			'text' => 'Редактор наименований заказанных товаров',
			'href' => $this->url->link('catalog/shortnames2', 'token=' . $this->session->data['token'], true)
		);


		$this->load->model('report/product');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('user/user');
		$this->load->model('tool/image');

		$data['start'] = ($page - 1) * $this->limit;
		$data['limit'] = $this->limit;

		$this->data['products'] = [];

		$product_total 	= $this->model_report_product->getTotalProductsOrderedForDates($data);			
		$results 		= $this->model_report_product->getProductsOrderedForDates($data);

		foreach ($results as $result) {	
			
			$this->data['products'][] = array(
				'asin'    			=> $result['asin'],
				'product_id'		=> $result['product_id'],
				'native_name'		=> $result['native_name'],
				'amazon_name'		=> $result['amazon_name'],
				'native_short_name'	=> $result['native_short_name'],
				'amazon_short_name'	=> $result['amazon_short_name'],
				'image'				=> $this->model_tool_image->resize($result['image'], 100, 100),
				'status'			=> ($result['product_id'] > 0)?$result['status']:false,
				'edit'				=> ($result['product_id'] > 0)?$this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'):false,
				'view'				=> ($result['product_id'] > 0)?(HTTP_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id']):false,					
			);
		}

		$this->data['heading_title'] 	= 'Редактор наименований заказанных товаров';
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

		$url = '';			
			foreach ($filters as $filter => $default_value){
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
				}
			}						
			

		$pagination 		= new Pagination();
		$pagination->total 	= $product_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $this->config->get('config_admin_limit');
		$pagination->text 	= $this->language->get('text_pagination');
		$pagination->url 	= $this->url->link('catalog/shortnames2',  'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'catalog/shortnames2.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
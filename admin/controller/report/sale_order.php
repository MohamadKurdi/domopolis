<?php
class ControllerReportSaleOrder extends Controller { 

	public function xlsx(){
		$this->index(true);
	}

	public function index($xlsx = false) {  
		$this->language->load('report/sale_order');
		$this->load->model('catalog/category');		
		$this->load->model('report/sale');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}

		if (isset($this->request->get['filter_divide_by_categories'])) {
			$filter_divide_by_categories = $this->request->get['filter_divide_by_categories'];
		} else {
			$filter_divide_by_categories = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_divide_by_categories'])) {
			$url .= '&filter_divide_by_categories=' . $this->request->get['filter_divide_by_categories'];
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
			'href'      => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		if (empty($filter_divide_by_categories)){
			$this->data['orders'] = [];
			$data = [
				'filter_date_start'	     	=> $filter_date_start, 
				'filter_date_end'	     	=> $filter_date_end, 
				'filter_group'           	=> $filter_group,
				'filter_order_status_id' 	=> $filter_order_status_id,
				'filter_category_id' 		=> $filter_category_id,
			];

			$results 		= $this->model_report_sale->getOrders($data);

			foreach ($results as $result) {
				$amazon_offers_types = [];
				if ($this->config->get('config_enable_amazon_specific_modes')){
					foreach (\hobotix\RainforestAmazon::amazonOffersType as $field){
						$amazon_offers_types[$field] = round((float)$result['pct_' . $field], 1);
					}
				}

				$this->data['orders'][] = [
					'date_start' 			=> date($this->language->get('date_format_short'), strtotime($result['date_start'])),
					'date_end'   			=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),
					'orders'     			=> $result['orders'],
					'products'   			=> $result['products'],
					'avg_profitability'		=> round($result['avg_profitability'], 2),
					'min_profitability'		=> round($result['min_profitability'], 2),
					'max_profitability'		=> round($result['max_profitability'], 2),
					'amazon_offers_types' 	=> $amazon_offers_types,
					'avg_total'				=> $this->currency->format($result['avg_total'], $this->config->get('config_currency')),
					'avg_total_national'	=> $this->currency->format($result['avg_total'], $this->config->get('config_regional_currency')),
					'total'      			=> $this->currency->format($result['total'], $this->config->get('config_currency')),
					'total_national'      	=> $this->currency->format($result['total'], $this->config->get('config_regional_currency'))
				];
			}
		} else {
			$this->data['categories'] = [];

			$categories = $this->model_report_sale->getFinalSubcategories($filter_category_id);

			foreach($categories as $category){
				$category_info = $this->model_catalog_category->getCategory($category['category_id']);
				$this->data['categories'][$category['category_id']] = [
					'orders' 	=> [],
					'category' 	=> ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				];

				$data = [
					'filter_date_start'	     	=> $filter_date_start, 
					'filter_date_end'	     	=> $filter_date_end, 
					'filter_group'           	=> $filter_group,
					'filter_order_status_id' 	=> $filter_order_status_id,
					'filter_category_id' 		=> $category['category_id'],
				];

				$results 		= $this->model_report_sale->getOrders($data);

				foreach ($results as $result) {
					$amazon_offers_types = [];
					if ($this->config->get('config_enable_amazon_specific_modes')){
						foreach (\hobotix\RainforestAmazon::amazonOffersType as $field){
							$amazon_offers_types[$field] = round((float)$result['pct_' . $field], 1);
						}
					}

					$this->data['categories'][$category['category_id']]['orders'][] = [
						'date_start' 			=> date($this->language->get('date_format_short'), strtotime($result['date_start'])),
						'date_end'   			=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),
						'orders'     			=> $result['orders'],
						'products'   			=> $result['products'],
						'avg_profitability'		=> round($result['avg_profitability'], 2),
						'min_profitability'		=> round($result['min_profitability'], 2),
						'max_profitability'		=> round($result['max_profitability'], 2),
						'amazon_offers_types' 	=> $amazon_offers_types,
						'avg_total'				=> $this->currency->format($result['avg_total'], $this->config->get('config_currency')),
						'avg_total_national'	=> $this->currency->format($result['avg_total'], $this->config->get('config_regional_currency')),
						'total'      			=> $this->currency->format($result['total'], $this->config->get('config_currency')),
						'total_national'      	=> $this->currency->format($result['total'], $this->config->get('config_regional_currency'))
					];
				}
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');

		$this->data['column_date_start'] 	= $this->language->get('column_date_start');
		$this->data['column_date_end'] 		= $this->language->get('column_date_end');
		$this->data['column_orders'] 		= $this->language->get('column_orders');
		$this->data['column_products'] 		= $this->language->get('column_products');
		$this->data['column_tax'] 			= $this->language->get('column_tax');
		$this->data['column_total'] 		= $this->language->get('column_total');

		$this->data['entry_date_start'] 	= $this->language->get('entry_date_start');
		$this->data['entry_date_end'] 		= $this->language->get('entry_date_end');
		$this->data['entry_group'] 			= $this->language->get('entry_group');	
		$this->data['entry_status'] 		= $this->language->get('entry_status');
		$this->data['text_none'] 			= $this->language->get('text_none');

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['groups'] = array();

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_divide_by_categories'])) {
			$url .= '&filter_divide_by_categories=' . $this->request->get['filter_divide_by_categories'];
		}	

		$this->data['download_xlsx'] = $this->url->link('report/sale_order/xlsx', 'token=' . $this->session->data['token'] . $url);

		$this->data['filter_date_start'] 			= $filter_date_start;
		$this->data['filter_date_end'] 				= $filter_date_end;		
		$this->data['filter_group'] 				= $filter_group;
		$this->data['filter_order_status_id'] 		= $filter_order_status_id;
		$this->data['filter_category_id'] 			= $filter_category_id;
		$this->data['filter_divide_by_categories'] 	= $filter_divide_by_categories;

		$this->data['filter_category_path'] = '';
		if (!empty($filter_category_id)){
			$category_info = $this->model_catalog_category->getCategory($filter_category_id);
			$this->data['filter_category_path'] = ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name'];
		}

		if ($xlsx){
			$this->template = 'report/sale_order_xlsx.tpl';
			$out = $this->render();

			$this->response->setXLSX($out, 'file.xlsx');

		} else {
			$this->template = 'report/sale_order.tpl';
			$this->children = [
				'common/header',
				'common/footer'
			];

			$this->response->setOutput($this->render());
		}		
	}
}
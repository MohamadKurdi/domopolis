<?php
	class ControllerReportBuyAnalyze extends Controller {
		
		private $good_warehouses = array(
		'quantity_stock',
		'quantity_stockK',
		'quantity_stockM',
		);
		
		public function index($return_data = false) {
			$this->load->model('tool/image');
			
			$this->language->load('report/buyanalyze');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
				} else {
				$filter_date_start = '2017-01-01';
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
				} else {
				$filter_date_end = date('Y-m-d');
			}
			
			if (isset($this->request->get['filter_store_id'])) {
				$filter_store_id = $this->request->get['filter_store_id'];
				} else {
				$filter_store_id = null;
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
				} else {
				$filter_manufacturer_id = null;
			}
			
			if (isset($this->request->get['filter_problems'])) {
				$filter_problems = $this->request->get['filter_problems'];
				} else {
				$filter_problems = null;
			}
			
			if (isset($this->request->get['filter_not_set'])) {
				$filter_not_set = $this->request->get['filter_not_set'];
				} else {
				$filter_not_set = null;
			}
			
			if (isset($this->request->get['filter_set'])) {
				$filter_set = $this->request->get['filter_set'];
				} else {
				$filter_set = null;
			}
			
			if (isset($this->request->get['filter_debugsql'])) {
				$filter_debugsql = $this->request->get['filter_debugsql'];
				} else {
				$filter_debugsql = null;
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
				} else {
				$filter_category_id = null;
			}
			
			if (isset($this->request->get['filter_dynamics'])) {
				$filter_dynamics = $this->request->get['filter_dynamics'];
				} else {
				$filter_dynamics = 'month';
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'SUM(sv.times)';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}
			
			if (isset($this->request->get['filter_not_set'])) {
				$url .= '&filter_not_set=' . $this->request->get['filter_not_set'];
			}
			
			if (isset($this->request->get['filter_set'])) {
				$url .= '&filter_set=' . $this->request->get['filter_set'];
			}
			
			if (isset($this->request->get['filter_debugsql'])) {
				$url .= '&filter_debugsql=' . $this->request->get['filter_debugsql'];
			}
			
			if (isset($this->request->get['filter_dynamics'])) {
				$url .= '&filter_dynamics=' . $this->request->get['filter_dynamics'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/buyanalyze', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
			
			$this->load->model('catalog/manufacturer');
			$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
			
			$this->load->model('catalog/category');
			$this->data['categories'] = $this->model_catalog_category->getCategories(0);
			
			$this->load->model('setting/store');
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			foreach ($this->data['stores'] as $idx => $store){
				if (in_array($store['store_id'], array(17, 16))){
					unset($this->data['stores'][$idx]);
				}				
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->load->model('report/product');
			$this->load->model('catalog/product');
			$this->load->model('localisation/country');	
			$this->load->model('kp/product');
			$this->load->model('setting/setting');
			
			$countries = $this->model_localisation_country->getCountriesStructuredByID();
			$stocks = array();
			
			foreach ($this->data['stores'] as $store){
				$country_id = $this->model_setting_setting->getKeySettingValue('config', 'config_country_id', $store['store_id']);
				
				if (isset($countries[$country_id])){
					$stocks[$countries[$country_id]['warehouse_identifier']] = array(
					'country_id' => $country_id,
					'name'       => $countries[$country_id]['name'],
					'iso_code_2' => mb_strtolower($countries[$country_id]['iso_code_2']),
					'flag'       => 'view/image/flags/' . mb_strtolower($countries[$country_id]['iso_code_2']) . '.png',
					'warehouse'  => $countries[$country_id]['warehouse_identifier']
					);
				}
			}
			
			$this->data['stocks'] = $stocks;
			
			$data = array(
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'filter_store_id' => $filter_store_id,
			'filter_manufacturer_id' => $filter_manufacturer_id,
			'filter_category_id' => $filter_category_id,	
			'filter_problems' => $filter_problems,
			'filter_not_set' => $filter_not_set,
			'filter_set' => $filter_set,
			'filter_debugsql' => $filter_debugsql,
			'sort' => $sort,
			'order' => $order,
			'start' => $return_data?0:(($page - 1) * $this->config->get('config_admin_limit')),
			'limit' => $return_data?300:$this->config->get('config_admin_limit')
			);
			
			$this->load->model('localisation/language');			
			$de_language_id = $this->model_localisation_language->getLanguageByCode($this->config->get('config_de_language'));
			
			$buyanalyze_total = $this->model_report_product->getTotalBought($data); 
			
			$this->data['products'] = array();			
			$results = $this->model_report_product->getBought($data);
			
			foreach ($results as &$result) {
				
				$_oldprice = false;
				if ($result['special'] > 0){
					$_oldprice = $result['price'];
					$result['price'] = $result['special'];
				}
				
				$pricediff = $result['price'] - $result['actual_cost'];
				
				if ($pricediff >= $result['actual_cost'] * 0.8) {
					$clr = 'red';
					} elseif ($pricediff > $result['actual_cost'] * 0.6 && $pricediff < $result['actual_cost'] * 0.8){
					$clr = 'green';
					} elseif ($pricediff <= $result['actual_cost'] * 0.6 && $pricediff > $result['actual_cost'] * 0.4) {
					$clr = 'orange';
					} else {
					$clr = 'black';
				}
				
				/* куплено за текущий месяц */	
				$statdata = array(
				'filter_date_start' 	=> date('Y-m') . '-01',
				'filter_date_end'   	=> date('Y-m-d'),
				'product_id' 			=> $result['product_id'],
				'filter_store_id' 		=> $filter_store_id,
				);
				$bought_last_month = $this->model_report_product->getTotalBoughtByProductID($statdata);
				/* куплено за текущий месяц */	
				
				/* куплено за текущий год */	
				$statdata = array(
				'filter_date_start' 	=> date('Y') . '-01-01',
				'filter_date_end'   	=> date('Y-m-d'),
				'product_id' 			=> $result['product_id'],
				'filter_store_id' 		=> $filter_store_id,
				);
				$bought_last_year = $this->model_report_product->getTotalBoughtByProductID($statdata);
				/* куплено за текущий год */	
				
				/* в среднем за неделю */	
				$statdata = array(
				'filter_date_start' 	=> $filter_date_start,
				'filter_date_end'   	=> $filter_date_end,
				'product_id' 			=> $result['product_id'],
				'filter_store_id' 		=> $filter_store_id,
				'period'                => 'week'
				);
				$bought_avg_week = $this->model_report_product->getAverageBoughtByProductID($statdata);
				/* в среднем за неделю */	
				
				/* в среднем за неделю */	
				$statdata = array(
				'filter_date_start' 	=> $filter_date_start,
				'filter_date_end'   	=> $filter_date_end,
				'product_id' 			=> $result['product_id'],
				'filter_store_id' 		=> $filter_store_id,
				'period'                => 'month'
				);
				$bought_avg_month = $this->model_report_product->getAverageBoughtByProductID($statdata);
				/* в среднем за неделю */	
				
				/* в среднем в заказе */	
				$statdata = array(
				'filter_date_start' 	=> $filter_date_start,
				'filter_date_end'   	=> $filter_date_end,
				'product_id' 			=> $result['product_id'],
				'filter_store_id' 		=> $filter_store_id,
				);
				$bought_avg_in_order = $this->model_report_product->getAverageInOrderByProductID($statdata);
				/* в среднем в заказе */
				
				/* дата крайнего заказа */	
				$statdata = array(
				'filter_date_start' 	=> $filter_date_start,
				'filter_date_end'   	=> $filter_date_end,
				'product_id' 			=> $result['product_id'],
				'filter_store_id' 		=> $filter_store_id,
				);
				$bought_last_order = $this->model_report_product->getLastDateOrderByProductID($statdata);		
				
				$product_stocks = array();
				foreach ($this->data['stocks'] as $warehouse => $country_info){
					
					if (isset($result[$warehouse]) && in_array($warehouse, $this->good_warehouses)){
						$product_stocks[$warehouse] = $result[$warehouse];
					}
					
				}
				
				$stock_waits = $this->model_report_product->getProductStockWaits($result['product_id']);
				
				$product_stocks_waits = array();
				if ($stock_waits){
					foreach ($this->data['stocks'] as $warehouse => $country_info){
						
						if (isset($stock_waits[$warehouse]) && in_array($warehouse, $this->good_warehouses)){
							$product_stocks_waits[$warehouse] = $stock_waits[$warehouse];
						} else {
							$product_stocks_waits[$warehouse] = 0;
						}
					}					
				}
				
				
				
				$product_stock_limits = $this->model_catalog_product->getProductStockLimits($result['product_id']);
				
				$product_stock_limits_structured = array();
				
				unset($store_id);
				unset($store);
				if ($product_stock_limits) {
					foreach ($product_stock_limits as $store_id => $stock_limits){
						$store_warehouse_identifier = $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', $store_id);
						
						if (!in_array($store_id, array(17, 16))){
							$product_stock_limits_structured[$store_warehouse_identifier] = array(
							'store_id' => $store_id,
							'min_stock' => $stock_limits['min_stock'],
							'rec_stock' => $stock_limits['rec_stock']
							);
						}	
					}
					} else {
					unset($store);
					foreach ($this->data['stores'] as $store){
						$store_warehouse_identifier = $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', $store['store_id']);
						
						if (!in_array($store['store_id'], array(17, 16))){
							$product_stock_limits_structured[$store_warehouse_identifier] = array(
							'store_id' => $store['store_id'],
							'min_stock' => 0,
							'rec_stock' => 0
							);
						}					
					}
				}
				
				unset($store);
				foreach ($this->data['stores'] as $store){
					$store_warehouse_identifier = $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', $store['store_id']);	
					if (!in_array($store['store_id'], array(17, 16))){
						
						if (!isset($product_stock_limits_structured[$store_warehouse_identifier])){
							$product_stock_limits_structured[$store_warehouse_identifier] = array(
							'store_id' => $store['store_id'],
							'min_stock' => 0,
							'rec_stock' => 0
							);
						}
						
					}					
				}
				
				
				unset($warehouse_identifier);
				foreach ($product_stock_limits_structured as $warehouse_identifier => &$stock_limits_info){
					$stock_limits_info['cls'] = 'white';
					
					//меньше минимального
					if ($result[$warehouse_identifier] <= $stock_limits_info['min_stock']){
						$stock_limits_info['cls'] = 'black';
					}
					
					//приближается к минимальному
					if ($result[$warehouse_identifier] > $stock_limits_info['min_stock'] && $result[$warehouse_identifier] < $stock_limits_info['min_stock']*0.3){
						$stock_limits_info['cls'] = 'red';
					}
					
					//больше минимального и меньше половины рекомендуемого
					if ($result[$warehouse_identifier] > $stock_limits_info['min_stock'] && $result[$warehouse_identifier] < $stock_limits_info['rec_stock']*0.5){
						$stock_limits_info['cls'] = 'orange';
					}
					
					//больше рекомендуемого * 0.7
					if ($result[$warehouse_identifier] > $stock_limits_info['rec_stock']*0.7){
						$stock_limits_info['cls'] = 'green';
					}
					
					if ($stock_limits_info['rec_stock'] == 0 || $stock_limits_info['min_stock'] == 0){
						$stock_limits_info['cls'] = 'white';
					}
				}
				
				
				//динамика
				$chart_data = array();
				$chart_data['viewed'] = array();
				$chart_data['xaxis'] = array();
				
				$diff = (strtotime($filter_date_end) - strtotime ($filter_date_start))/(60*60*24);
				if ($filter_dynamics == 'month'){
					$ts1 = strtotime($filter_date_start);
					$ts2  = strtotime($filter_date_end);
					$year1 = date('Y', $ts1);
					$year2 = date('Y', $ts2);					
					$month1 = date('m', $ts1);
					$month2 = date('m', $ts2);					
					$diff_month = (($year2 - $year1) * 12) + ($month2 - $month1);
					
					for ($i = 0; $i <= $diff_month; $i++) {
						$current = date('Y-m-d', strtotime("+$i month", strtotime($filter_date_start)));
						$current_to = date('Y-m-d', strtotime("+1 month", strtotime($current)));
						
						/* куплено за текущий месяц */	
						$statdata = array(
						'filter_date_start' 	=> $current,
						'filter_date_end'   	=> $current_to,
						'product_id' 			=> $result['product_id'],
						'filter_store_id' 		=> $filter_store_id,
						);
						$bought_this_month = $this->model_report_product->getTotalBoughtByProductID($statdata);
						
						$chart_data['viewed']['data'][]  = array($i, (int)$bought_this_month);
						$chart_data['xaxis'][] = array($i, date('m.Y', strtotime($current)));
						
					}
					
					$chart['chart_data'] = $chart_data;
					
				}
				
				
				$this->data['products'][] = array(
				'product_id'     => $result['product_id'],
				'name'    		 => $result['name'],
				'de_name'    	 => $this->model_catalog_product->getProductDeName($result['product_id']),
				'manufacturer'   => $result['manufacturer'],					
				'model'          => $result['model'],
				'image'    	     => $this->model_tool_image->resize($result['image'], 50, 50),
				'ean'            => $result['ean'],	
				'actual_cost'      => $this->currency->format($result['actual_cost'], 'EUR', 1),
				'actual_cost_date' => ($result['actual_cost_date']!='0000-00-00')?date('Y-m-d', strtotime($result['actual_cost_date'])):'',
				'price'          => $this->currency->format($result['price'], 'EUR', 1),
				'oldprice'       => $_oldprice?$this->currency->format($_oldprice, 'EUR', 1):false,
				'diff'           => $result['actual_cost']>0?$this->currency->format($pricediff, 'EUR', 1):false,
				'diff_percent'   => $result['actual_cost']>0?number_format($pricediff / $result['actual_cost'] * 100, 1) . ' %':false,
				'diff_clr'       => $clr,
				'total_bought'   => $result['total_bought'],
				'bought_last_month' => $bought_last_month,
				'bought_last_year'  => $bought_last_year,
				'bought_avg_week' 	=> $bought_avg_week,
				'bought_avg_month'  => $bought_avg_month,
				'bought_avg_in_order' => $bought_avg_in_order,
				'bought_last_order' => $bought_last_order,
				'product_stocks'   => $product_stocks,
				'product_stocks_waits' => $product_stocks_waits,
				'chart'         => $chart,
				'product_stock_limits_structured' => $product_stock_limits_structured,
				'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'),
				'filter_orders'        => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_product_id=' . $result['product_id'], 'SSL'),
				);
				
				
			}
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_viewed'] = $this->language->get('column_viewed');
			$this->data['column_percent'] = $this->language->get('column_percent');
			
			$this->data['button_reset'] = $this->language->get('button_reset');
			
			$url = '';		
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['reset'] = $this->url->link('report/buyanalyze/reset', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['token'] = $this->session->data['token'];
			
			
			//sorts
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}
			
			
			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}
			
			if (isset($this->request->get['filter_not_set'])) {
				$url .= '&filter_not_set=' . $this->request->get['filter_not_set'];
			}
			
			if (isset($this->request->get['filter_set'])) {
				$url .= '&filter_set=' . $this->request->get['filter_set'];
			}
			
			if (isset($this->request->get['filter_debugsql'])) {
				$url .= '&filter_debugsql=' . $this->request->get['filter_debugsql'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_dynamics'])) {
				$url .= '&filter_dynamics=' . $this->request->get['filter_dynamics'];
			}
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			$this->data['sort_cart'] = $this->url->link('report/buyanalyze', 'token=' . $this->session->data['token'] . '&sort=cart' . $url, 'SSL');
			$this->data['sort_bought'] = $this->url->link('report/buyanalyze', 'token=' . $this->session->data['token'] . '&sort=bought' . $url, 'SSL');
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
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
			
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_problems'])) {
				$url .= '&filter_problems=' . $this->request->get['filter_problems'];
			}
			
			if (isset($this->request->get['filter_not_set'])) {
				$url .= '&filter_not_set=' . $this->request->get['filter_not_set'];
			}
			
			if (isset($this->request->get['filter_set'])) {
				$url .= '&filter_set=' . $this->request->get['filter_set'];
			}
			
			if (isset($this->request->get['filter_debugsql'])) {
				$url .= '&filter_debugsql=' . $this->request->get['filter_debugsql'];
			}
			
			if (isset($this->request->get['filter_dynamics'])) {
				$url .= '&filter_dynamics=' . $this->request->get['filter_dynamics'];
			}
			
			if (isset($this->request->get['filter_set'])) {
				$url .= '&filter_set=' . $this->request->get['filter_set'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $buyanalyze_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('report/buyanalyze',  'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_date_start'] = $filter_date_start;
			$this->data['filter_date_end'] = $filter_date_end;
			$this->data['filter_store_id'] = $filter_store_id;
			$this->data['filter_manufacturer_id'] = $filter_manufacturer_id;
			$this->data['filter_category_id'] = $filter_category_id;
			$this->data['filter_problems'] = $filter_problems;
			$this->data['filter_not_set'] = $filter_not_set;
			$this->data['filter_set'] = $filter_set;
			$this->data['filter_debugsql'] = $filter_debugsql;
			$this->data['filter_dynamics'] = $filter_dynamics;
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			if ($return_data){
				return $this->data;
			}
			
			$this->template = 'report/buyanalyze.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		
		public function saveDataToCSV(){
			
			$data = $this->index(true);
			
			set_time_limit(100);
			header( 'Content-Type: text/csv charset=utf-8' );
			header("Content-Disposition: attachment; filename=ПотребностьВЗакупке.".date('Y_m_d').".csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$file = fopen('php://output', 'w');
			$header = array(
			'Наименование товара',
			'Наименование производителя',
			'Код товара',
			'Артикул товара',			
			'EAN товара',
			'Склад / страна',
			'Минимально',
			'Рекомендуется',
			'Наличие',
			'В пути на свободные',
			'Надо купить',
			);
			
			fputcsv($file, $header);
			
			foreach ($data['products'] as $product){
				
				foreach ($product['product_stocks'] as $warehouse => $quantity) {
					
					if ($product['product_stock_limits_structured'][$warehouse]['min_stock'] > 0 && $product['product_stock_limits_structured'][$warehouse]['rec_stock'] > 0 ){
						
						$ontheway = 0;
						if ((isset($product['product_stocks_waits'][$warehouse]) && $product['product_stocks_waits'][$warehouse])){
							$ontheway = $product['product_stocks_waits'][$warehouse];
						}
						
						$need = ($product['product_stock_limits_structured'][$warehouse]['rec_stock'] - $quantity - $ontheway);
												
						$line = array(
							$product['name'],
							$product['de_name'],
							$product['product_id'],	
							$product['model'],							
							$product['ean'],
							$data['stocks'][$warehouse]['name'],
							$product['product_stock_limits_structured'][$warehouse]['min_stock'],
							$product['product_stock_limits_structured'][$warehouse]['rec_stock'],
							$quantity,
							$ontheway,
							$need,											
						);
						
						if ($need){
							fputcsv($file, $line);
						}
					}
				}
			}
			

			
			fclose($file);
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}							
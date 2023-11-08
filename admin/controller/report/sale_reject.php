<?php
	class ControllerReportSaleReject extends Controller { 
		public function index() {  
			$this->language->load('report/sale_order');
			
			
			$this->load->model('report/sale');
			$this->load->model('sale/reject_reason');
			$this->load->model('sale/order');
			$this->load->model('setting/store');
			
			$this->document->setTitle('Анализ причин отмен');
			
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
				$filter_group = '';
			}
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$filter_reject_reason_id = $this->request->get['filter_reject_reason_id'];
				} else {
				$filter_reject_reason_id = 0;
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
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$url .= '&filter_reject_reason_id=' . $this->request->get['filter_reject_reason_id'];
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
			'href'      => $this->url->link('report/sale_reject', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->data['reject_reasons'] = $this->model_sale_reject_reason->getRejectReasons();
			$this->data['order_stores']   = $this->model_setting_store->getStores(['exclude_de' => true]);
			
			$this->data['orders'] = array();
			
			foreach ($this->data['order_stores'] as &$store){	
				$currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$store['store_id']);
				
				$filter_data = array(
				'filter_order_store_id' 	=> $store['store_id'],
				'filter_date_added'			=> $filter_date_start,
				'filter_date_added_to'		=> $filter_date_end,
				);
				
				$store['total_orders'] = $this->model_sale_order->getTotalOrders($filter_data);
				
				$filter_data['return_sum'] = true;
				$store['sum_total_orders'] = $this->model_sale_order->getTotalOrders($filter_data);
				$store['sum_total_orders_txt'] = $this->currency->format($store['sum_total_orders'], $currency, 1);
				
				
				//Отмены
				$filter_data = array(
				'filter_order_status_id' 	=> $this->config->get('config_cancelled_status_id'),
				'filter_order_store_id' 	=> $store['store_id'],
				'filter_date_added'			=> $filter_date_start,
				'filter_date_added_to'		=> $filter_date_end,
				);
				
				$store['total_cancelled'] = $this->model_sale_order->getTotalOrders($filter_data);
				$store['percent_cancelled'] = round(($store['total_cancelled'] / $store['total_orders']) * 100, 2);
				
				$filter_data['return_sum'] = true;
				$store['sum_total_cancelled'] = $this->model_sale_order->getTotalOrders($filter_data);
				$store['sum_total_cancelled_txt'] = $this->currency->format($store['sum_total_cancelled'], $currency, 1);
				
				$store['sum_percent_cancelled'] = round(($store['sum_total_cancelled'] / $store['sum_total_orders']) * 100, 2);				
				
			}
			
			
			foreach ($this->data['reject_reasons'] as &$reject_reason){
				$reject_reason['store_data'] = array();
				
				unset($store);
				foreach ($this->data['order_stores'] as $store){									
					$reject_reason['store_data'][$store['store_id']] = array();
					
					$filter_data = array(
					'filter_order_status_id' 	=> $this->config->get('config_cancelled_status_id'),
					'filter_reject_reason_id' 	=> $reject_reason['reject_reason_id'],
					'filter_order_store_id' 	=> $store['store_id'],
					'filter_date_added'			=> $filter_date_start,
					'filter_date_added_to'		=> $filter_date_end,
					);
					
					$total_cancelled = $this->model_sale_order->getTotalOrders($filter_data);
					
					
					$filter_data['return_sum'] = true;
					$sum_cancelled = $this->model_sale_order->getTotalOrders($filter_data);
					
					$currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$store['store_id']);
					
					$reject_reason['store_data'][$store['store_id']] = array(
					'total_cancelled' 		=> $total_cancelled,
					'percent_cancelled' 	=> round(($total_cancelled / $store['total_cancelled']) * 100, 2),
					'sum_cancelled'			=> $this->currency->format($sum_cancelled, $currency, 1),
					'percent_sum_cancelled' => round(($sum_cancelled / $store['sum_total_cancelled']) * 100, 2),
					
					);
					
				}
				
			}
			
			$this->data['heading_title'] = 'Отчет по причинам отмен заказов';
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			$this->data['text_all_status'] = $this->language->get('text_all_status');
			
			$this->data['column_date_start'] = $this->language->get('column_date_start');
			$this->data['column_date_end'] = $this->language->get('column_date_end');
			$this->data['column_orders'] = $this->language->get('column_orders');
			$this->data['column_products'] = $this->language->get('column_products');
			$this->data['column_tax'] = $this->language->get('column_tax');
			$this->data['column_total'] = $this->language->get('column_total');
			
			$this->data['entry_date_start'] = $this->language->get('entry_date_start');
			$this->data['entry_date_end'] = $this->language->get('entry_date_end');
			$this->data['entry_group'] = $this->language->get('entry_group');	
			$this->data['entry_status'] = $this->language->get('entry_status');
			
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
			
			$this->data['button_filter'] = $this->language->get('button_filter');
			
			$this->data['token'] = $this->session->data['token'];
			
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
			
			if (isset($this->request->get['filter_reject_reason_id'])) {
				$url .= '&filter_reject_reason_id=' . $this->request->get['filter_reject_reason_id'];
			}
			
			$this->data['filter_date_start'] = $filter_date_start;
			$this->data['filter_date_end'] = $filter_date_end;		
			$this->data['filter_group'] = $filter_group;
			$this->data['filter_reject_reason_id'] = $filter_reject_reason_id;
			
			$this->template = 'report/sale_reject.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
	}			
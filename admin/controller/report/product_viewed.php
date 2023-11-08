<?php
	class ControllerReportProductViewed extends Controller {
		public function index() {
			$this->load->model('tool/image');
			
			$this->language->load('report/product_viewed');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['filter_date_from'])) {
				$filter_date_from = $this->request->get['filter_date_from'];
				} else {
				$filter_date_from = '2018-05-17';
			}
			
			if (isset($this->request->get['filter_date_to'])) {
				$filter_date_to = $this->request->get['filter_date_to'];
				} else {
				$filter_date_to = date('Y-m-d');
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
			
			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
				} else {
				$filter_category_id = null;
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
			
			if (isset($this->request->get['filter_date_from'])) {
				$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
			}
			
			if (isset($this->request->get['filter_date_to'])) {
				$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
				
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
			);
			
			$this->load->model('report/product');
			
			$data = array(
			'filter_date_from' => $filter_date_from,
			'filter_date_to' => $filter_date_to,
			'filter_store_id' => $filter_store_id,
			'filter_manufacturer_id' => $filter_manufacturer_id,
			'filter_category_id' => $filter_category_id,	
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
			);
			
			$this->load->model('catalog/manufacturer');
			$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
			
			$this->load->model('catalog/category');
			$this->data['categories'] = $this->model_catalog_category->getCategories(0);
			
			$this->load->model('setting/store');
			$this->data['stores'] = $this->model_setting_store->getStores();
			array_unshift($this->data['stores'], array('store_id' => 0, 'name' => $this->config->get('config_name')));
			
			$product_viewed_total = $this->model_report_product->getTotalProductsViewed($data); 
			
			$this->data['products'] = array();			
			$results = $this->model_report_product->getProductsViewed($data);
			
			foreach ($results as &$result) {
				
				$chart = false;
				
				if ($filter_date_from && $filter_date_to){
					$diff = (strtotime($filter_date_to) - strtotime ($filter_date_from))/(60*60*24);
					
					if ($diff <= 30){
						//chart by days
						$chart = array('type' => 'daily');
						$date_start = strtotime($filter_date_from);
						
						$chart_data = array();
						$chart_data['viewed'] = array();
						$chart_data['xaxis'] = array();
						
					//	$chart_data['viewed']['label'] = 'Просмотров';
						
						for ($i = 0; $i <= $diff; $i++) {
							$current = date('Y-m-d', strtotime("+$i days", strtotime($filter_date_from)));
							
							$pvdata = array(
								'product_id' => $result['product_id'],
								'date_from'  => $current,
								'date_to'    => $current
							);
							$daily_views = $this->model_report_product->getProductViewedByDays($pvdata);						
							
							$chart_data['viewed']['data'][]  = array($i, (int)$daily_views);
							$chart_data['xaxis'][] = array($i, $current);
						}
						$chart['chart_data'] = $chart_data;
						
					} elseif ($diff <= 140){
						//chart by weeks
						$chart = array('type' => 'weekly');
						$date_start = strtotime($filter_date_from);
						
						$chart_data = array();
						$chart_data['viewed'] = array();
						$chart_data['xaxis'] = array();
						
						for ($i = 0; $i <= $diff/7; $i++) {
							$current = date('Y-m-d', strtotime("+$i week", strtotime($filter_date_from)));
							$current_to = date('Y-m-d', strtotime("+1 week", strtotime($current)));
																				
							$pvdata = array(
								'product_id' => $result['product_id'],
								'date_from'  => $current,
								'date_to'    => $current_to
							);
							$week_views = $this->model_report_product->getProductViewedByDays($pvdata);						
							
							$chart_data['viewed']['data'][]  = array($i, (int)$week_views);
							$chart_data['xaxis'][] = array($i, $current);
						}												
		
						$chart['chart_data'] = $chart_data;
						
					
					} elseif ($diff > 140){
						//chart by month
						
					
					}
				}
				
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
				
				$this->data['products'][] = array(
					'product_id'     => $result['product_id'],
					'name'    		 => $result['name'],
					'manufacturer'   => $result['manufacturer'],					
					'model'          => $result['model'],
					'actual_cost'      => $this->currency->format($result['actual_cost'], 'EUR', 1),
					'actual_cost_date' => ($result['actual_cost_date']!='0000-00-00')?date('Y-m-d', strtotime($result['actual_cost_date'])):'',
					'chart'          => $chart,
					'cart'           => $result['cart']?$result['cart']:0,
					'cart_pi'        => number_format($result['cart'] / $result['viewed'], 2),
					'bought'         => $result['bought']?$result['bought']:0,
					'bought_pi'      => number_format($result['bought'] / $result['viewed'], 2),
					'image'    	     => $this->model_tool_image->resize($result['image'], 50, 50),
					'ean'            => $result['ean'],	
					'price'          => $this->currency->format($result['price'], 'EUR', 1),
					'oldprice'       => $_oldprice?$this->currency->format($_oldprice, 'EUR', 1):false,
					'diff'           => $result['actual_cost']>0?$this->currency->format($pricediff, 'EUR', 1):false,
					'diff_percent'   => $result['actual_cost']>0?number_format($pricediff / $result['actual_cost'] * 100, 1) . ' %':false,
					'diff_clr'       => $clr,
					'viewed'         => $result['viewed'],
					'adminlink'        => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id']),
				);
				
			}
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_no_results'] = $this->language->get('text_no_results');
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_viewed'] = $this->language->get('column_viewed');
			$this->data['column_percent'] = $this->language->get('column_percent');
			
			$this->data['button_reset'] = $this->language->get('button_reset');
			
			$url = '';		
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->data['reset'] = $this->url->link('report/product_viewed/reset', 'token=' . $this->session->data['token'] . $url);
			$this->data['token'] = $this->session->data['token'];
			
			
			//sorts
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_date_from'])) {
				$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
			}
			
			if (isset($this->request->get['filter_date_to'])) {
				$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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
							
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			$this->data['sort_cart'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'] . '&sort=cart' . $url);
			$this->data['sort_bought'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'] . '&sort=bought' . $url);
			
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
			
			if (isset($this->request->get['filter_date_from'])) {
				$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
			}
			
			if (isset($this->request->get['filter_date_to'])) {
				$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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
			
			$pagination = new Pagination();
			$pagination->total = $product_viewed_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('report/product_viewed',  'token=' . $this->session->data['token'] . $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['filter_date_from'] = $filter_date_from;
			$this->data['filter_date_to'] = $filter_date_to;
			$this->data['filter_store_id'] = $filter_store_id;
			$this->data['filter_manufacturer_id'] = $filter_manufacturer_id;
			$this->data['filter_category_id'] = $filter_category_id;
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			
			$this->template = 'report/product_viewed.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
		
		public function reset() {
			$this->language->load('report/product_viewed');
			
			$this->load->model('report/product');
			
			$this->model_report_product->reset();
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
?>
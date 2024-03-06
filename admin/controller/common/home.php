<?php   
	class ControllerCommonHome extends Controller {  
		
		public function session(){
			if ($this->user->isLogged() && isset($this->session->data['token'])) {				
				$this->data['token'] = $this->session->data['token'];
				} else {
				$this->data['token'] = false;
			}
			
			$json = json_encode(array('token' => $this->data['token']));
			
			$this->response->setOutput($json);
		}
		
		public function cat(){
			$this->response->setOutput(\hobotix\CatLoader::getRandomCatGif(300));
		}

		public function loadNotifications(){
			$this->data['token'] = $this->session->data['token'];
			$this->language->load('common/header');
			
			
			$this->data['text_new_customer'] = $this->language->get('text_new_customer');
			$this->data['text_pending_customer'] = $this->language->get('text_pending_customer');
			$this->data['text_new_order'] = $this->language->get('text_new_order');
			$this->data['text_pending_order'] = $this->language->get('text_pending_order');
			$this->data['text_pending_review'] = $this->language->get('text_pending_review');
			$this->data['text_pending_affiliate'] = $this->language->get('text_pending_affiliate');
			$this->data['text_notification'] = $this->language->get('text_notification');
			$this->data['text_stockout'] = $this->language->get('text_stockout');
			$this->data['text_return'] = $this->language->get('text_return');
			
			$this->load->model('sale/customer');
			$customer_total_data = array('filter_date_added' => date('Y-m-d')); // 2013-10-10
			$this->data['total_new_customer'] = $this->model_sale_customer->getTotalCustomers($customer_total_data);
			$this->data['total_customer_approval'] = $this->model_sale_customer->getTotalCustomersAwaitingApproval();			
			
			
			$this->load->model('sale/order');
			$total_order_data = array('filter_date_added' => date('Y-m-d')); // 2013-10-10
			$this->data['total_new_order'] = $this->model_sale_order->getTotalOrders($total_order_data);
			$this->data['total_pending_order'] = $this->model_sale_order->getTotalOrdersByOrderStatusId($this->config->get('config_order_status_id'));
			
			$this->load->model('sale/return');
			$total_return_data = array('filter_date_added' => date('Y-m-d')); // 2013-10-10
			$this->data['total_new_return'] = $this->model_sale_return->getTotalReturns($total_return_data);

			$this->data['total_stockout'] = 0;
			
			$this->load->model('sale/callback');
			$this->data['total_callbacks'] = $this->model_sale_callback->getOpenedCallBacks();
			
			$this->data['callback'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token']);
			$this->data['text_callback'] = $this->language->get('text_callback');
			
			$this->load->model('catalog/review');
			$this->data['total_review_approval'] = $this->model_catalog_review->getTotalReviewsAwaitingApproval();
			
			$this->load->model('sale/affiliate');
			$this->data['total_affiliate_approval'] = $this->model_sale_affiliate->getTotalAffiliatesAwaitingApproval();
			
			$this->data['total_notification'] = $this->data['total_new_return'] + $this->data['total_stockout'] + $this->data['total_new_customer'] + $this->data['total_customer_approval'] + $this->data['total_new_order'] + $this->data['total_pending_order'] + $this->data['total_review_approval'] + $this->data['total_affiliate_approval'];

			$this->load->model('catalog/product');
			$this->data['total_waitlist_ready'] = $this->model_catalog_product->getTotalProductsWaitList(array('filter_supplier_has' => 1));
			$this->data['total_waitlist_prewaits'] = $this->model_catalog_product->getProductsWaitListTotalPreWaits();
			
			$this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token']);
			if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status') && isset($this->session->data['token'])) {
				$this->data['product'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token']);
			}
			$this->data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token']);
			$this->data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token']);
			$this->data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token']);
			$this->data['waitlist'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token']);
			$this->data['stocks'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token']);
			$this->data['waitlist_ready'] = $this->url->link('catalog/waitlist', 'filter_supplier_has=1&token=' . $this->session->data['token']);
			$this->data['waitlist_pre'] = $this->url->link('catalog/waitlist', 'filter_prewait=1&token=' . $this->session->data['token']);
			$this->data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token']);
			$this->data['affiliate'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token']);	
			$this->data['shortnames'] = $this->url->link('catalog/shortnames', 'token=' . $this->session->data['token']);
			
			$this->template = 'homestats/headernotifications.tpl';
			
			$this->response->setOutput($this->render());		
		}

		public function getRainForestStats(){
			$result = $this->rainforestAmazon->checkIfPossibleToMakeRequest(true, true);

			if ($result['status'] == true){
				$this->data['success'] 	= true;
				$this->data['answer'] 	= $result['answer'];
			} else {
				$this->data['success'] 	= false;
				$this->data['message'] 	= $result['message'];
				$this->data['answer']  	= $result['answer'];
				$this->data['debug'] 	= $result['debug'];
			}

			$zipcodes = $this->rainforestAmazon->zipcodesManager->checkZipCodes();			
			$this->data['active_zipcodes'] 	= $this->rainforestAmazon->zipcodesManager->getUsedZipCodes();

			$this->data['zipcodes'] = [];

			foreach ($zipcodes['zipcodes'] as $domain => $zipcodes){
				foreach ($zipcodes as $zipcode){
					$this->data['zipcodes'][$domain][] = [
						'zipcode' 	=> $zipcode['zipcode'],
						'status' 	=> $zipcode['status'],
						'info' 		=> $this->rainforestAmazon->zipcodesManager->getZipCodeInfo($zipcode['zipcode'])
					];
				}
			}

			$this->template = 'homestats/rnf.tpl';			
			$this->response->setOutput($this->render());
		}

		public function getCronStats(){
			$cronSettings = $this->simpleProcess->getCronConfig();

			$this->data['processes'] = [];
			foreach ($cronSettings as $cronRoute => $cronParams){
				if ($cronParams['main']){

					if ($this->config->get('config_config_file_prefix')){
						$cronParams['config'] = str_replace('config.', 'config.' . $this->config->get('config_config_file_prefix') . '.', $cronParams['config']);
					}

					$result = $this->simpleProcess->getProcess(['route' => $cronRoute, 'config' => $cronParams['config'], 'args' => $cronParams['args']]);
					

					$this->data['processes'][] = [
						'name' 				=> $cronParams['name'],
						'status' 			=> isset($result['status'])?$result['status']:'',
						'running'			=> isset($result['running'])?$result['running']:'',
						'never'				=> isset($result['never'])?$result['never']:'',
						'finished'			=> isset($result['finished'])?$result['finished']:'',
						'failed'			=> isset($result['failed'])?$result['failed']:'',
						'start'				=> isset($result['start'])?$result['start']:'',
						'stop'				=> isset($result['stop'])?$result['stop']:'',
					];
				}
			}

			$this->template = 'homestats/cronstats.tpl';
			$this->response->setOutput($this->render());
		}
		
		public function getLastTwentyOrders(){
			$this->data['token'] = $this->session->data['token'];
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('sale/affiliate');
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('module/referrer');
			$this->load->model('kp/price');
			$this->load->model('localisation/country');						
			$this->load->model('setting/setting');
			$this->load->model('sale/affiliate');
			
			$this->data['orders'] = []; 
			
			$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 30
			);
			
			$results = $this->model_sale_order->getOrders($data);		
			
			foreach ($results as $result) {
				$action = [];
				
				$action[] = array(
				'text' => 'Открыть заказ',
				'href' => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
				);
				
				if ($result['total_national'] > 0){
					$total = $this->currency->format($result['total_national'], $result['currency_code'],'1');			
					} else {
					$total = $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']);
				}	
				
				$products = $this->model_sale_order->getOrderProductsList($result['order_id']);
				$order_products = [];
				
				$zero_price_products = [];
				foreach ($products as $product) {
					if ($product['price'] == 0){
						$zero_price_products[$product['product_id']] = $product['quantity'];
					}
				}
				
				$total_orders = $this->model_sale_order->getTotalOrdersByCustomerId($result['customer_id']);
				$totals = $this->model_sale_order->getOrderTotals($result['order_id']);
				
				$totals2 = [];
				$total_discount = 0;
				$sub_total = 0;
				foreach ($totals as $tmp_total){
					$tmp_total['value_national_formatted'] = $this->currency->format($tmp_total['value_national'], $result['currency_code'], '1');
					$totals2[] = $tmp_total;
					
					if ($tmp_total['value_national'] < 0){
						$total_discount += $tmp_total['value_national'];
					}
					
					if ($tmp_total['code'] == 'sub_total'){
						$sub_total = $tmp_total['value_national'];
					}
				}
				
				if ($zero_price_products){
					foreach ($zero_price_products as $zero_price_product_id => $zero_price_product_quantity){
						$zero_product_current_price = $this->model_kp_price->getProductResultPriceByStore($zero_price_product_id, $result['store_id']);
						
						if ($zero_product_current_price['special']){
							$zero_product_current_price['price'] = $zero_product_current_price['special'];
						}
						
						$zero_product_current_total = -1 * ($zero_product_current_price['price'] * $zero_price_product_quantity);
						$zero_product_current_total_national = $zero_product_current_total;//$this->currency->convert($zero_product_current_total, $this->config->get('config_currency'), $result['currency_code']);
						
						$total_discount += $zero_product_current_total_national;
						$sub_total += -1 * $zero_product_current_total_national;
						
						array_insert2($totals2, 1, [
						'value_national'			=> $zero_product_current_total_national,
						'value_national_formatted'  => $this->currency->format($zero_product_current_total_national, $result['currency_code'], 1),
						'code'						=> 'additionaloffer'
						]);
					}	
				}	
				
				if ($result['preorder']){
					$totals2 		= [];
					$total_discount = false;
				}

				$decoded_referrer = parse_url($this->model_module_referrer->simple_decode( $result['first_referrer']), PHP_URL_HOST);
				$first_referrer = $decoded_referrer?trim(str_replace('www.', '', $decoded_referrer)):'Direct Hit';
				$decoded_referrer = parse_url($this->model_module_referrer->simple_decode( $result['last_referrer']), PHP_URL_HOST);
				$last_referrer = $decoded_referrer?trim(str_replace('www.', '', $decoded_referrer)):'Direct Hit';
				
				$this->data['orders'][] = [
				'order_id'   				=> $result['order_id'],
				'customer'   				=> $result['customer'],
				'email'      				=> $result['email'],
				'email_bad'					=> !$this->emailBlackList->check($result['email']),
				'status'     				=> $result['status'],
				'pwa'     					=> $result['pwa'],
				'monocheckout'     			=> $result['monocheckout'],
				'preorder'     				=> $result['preorder'],
				'ukrcredits_order_status'      => !empty($result['ukrcredits_order_status'])?$result['ukrcredits_order_status']:false,
				'ukrcredits_order_substatus'   => !empty($result['ukrcredits_order_status'])?$result['ukrcredits_order_substatus']:false,
				'yam'     					=> $result['yam'],				
				'yam_express'     			=> $result['yam_express'],
				'yam_comission'				=> ($result['yam'])?$this->currency->format(-1 * ($sub_total/100*12), $result['currency_code'], '1'):false,
				'reward'     				=> $this->currency->formatBonus($result['reward'], true),
				'reward_used'   			=> $result['reward_used']?$this->currency->formatNegativeBonus($result['reward_used'], true):false,
				'status_id' 				=> $result['order_status_id'],
				'costprice'					=> $this->currency->format($result['costprice'], $this->config->get('config_currency'), 1),
				'costprice_national'	  	=> $this->currency->format($this->currency->convert($result['costprice'], $this->config->get('config_currency'), $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $result['store_id'])), $this->config->get('config_regional_currency'), 1),
				'profitability'			  	=> $result['profitability'],
				'amazon_offers_type'		=> $result['amazon_offers_type'],
				'do_not_call'				=> $result['do_not_call'],
				'flag'      				=> $this->model_setting_setting->getKeySettingValue('config', 'config_language', $result['store_id']),
				'affiliate' 				=> $this->model_sale_affiliate->getAffiliate($result['affiliate_id']),
				'totals'         			=> $totals2,
				'total_discount'         	=> ($total_discount<0)?$this->currency->format($total_discount, $result['currency_code'], '1'):false,
				'total_discount_percent'    => ($total_discount<0)?round(($total_discount/$sub_total) * 100, 2):false,
				'customer_segments' 		=> $this->model_sale_customer->getCustomerSegments($result['customer_id']),	
				'total_customer_orders' 	=> $total_orders,
				'total_customer_orders_txt' => morphos\Russian\NounPluralization::pluralize($total_orders, 'заказ'),
				'first_referrer'   			=> $first_referrer,
				'last_referrer'   			=> $last_referrer,
				'status_txt_color' 			=> isset($result['status_txt_color']) ? $result['status_txt_color'] : '',
				'status_bg_color' 			=> isset($result['status_bg_color']) ? $result['status_bg_color'] : '',
				'status_fa_icon' 			=> isset($result['status_fa_icon']) ? $result['status_fa_icon'] : '',
				'date_added' 				=> date('d.m', strtotime($result['date_added'])),
				'total'      				=> $total,
				'action'     				=> $action
				];
			}
								
			$this->template = 'homestats/lasttwentyorders.tpl';
			
			$this->response->setOutput($this->render());
		}
		
		public function loadChartStats(){
			$this->data['token'] = $this->session->data['token'];			
			$this->template = 'homestats/homecharts.tpl';			
			$this->response->setOutput($this->render());			
		}		
		
		public function loadPrettyStats(){
			$this->data['token'] = $this->session->data['token'];
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			
			$data = array(
			'filter_date_added' 			=> date('Y-m-d', strtotime("-1 month")),
			'filter_date_added_to' 			=> date('Y-m-d'),
			'filter_order_status_notnull' 	=> true
			);
			$this->data['orders_now'] = $this->model_sale_order->getTotalOrders($data);
			
			$data = array(
			'filter_date_added' 			=> date('Y-m-d', strtotime("-13 month")),
			'filter_date_added_to' 			=> date('Y-m-d', strtotime('-1 year')),
			'filter_order_status_notnull' 	=> true
			);
			
			$this->data['orders_last_year'] = $this->model_sale_order->getTotalOrders($data);			
			$this->data['orders_diff'] = ($this->data['orders_now'] - $this->data['orders_last_year']);
			
			$order_total = $this->model_sale_order->getTotalSales();
			$this->data['order_total'] = formatLongNumber($order_total);
			
			$date_first_order = $this->model_sale_order->getFirstOrderDate();
			$this->data['date_first_order'] = date('d.m.Y', strtotime($date_first_order));			
			
			$this->data['customers_now'] = $this->model_sale_customer->getTotalInvolvedCustomers();
			$this->data['customers_yesterday'] = $this->model_sale_customer->getTotalInvolvedCustomersLastWeek();
			$this->data['customers_diff'] = abs($this->data['customers_now'] - $this->data['customers_yesterday']);
			
			$this->data['loyal_customers'] = $this->model_sale_customer->getTotalLoyalCustomers();
			$this->data['loyal_customers_last_week'] = $this->model_sale_customer->getTotalLoyalCustomersLastWeek();
			$this->data['loyal_customers_diff'] = abs($this->data['loyal_customers_last_week']);
			
			$this->data['loyal_orders_last_month'] = $this->model_sale_customer->getTotalLoyalOrdersLastMonth();
			$this->data['loyal_orders_percent'] = $this->data['orders_now']?round((($this->data['loyal_orders_last_month'] / $this->data['orders_now']) * 100), 1):0;
			
			
			$this->data['total_installs'] = $this->model_setting_setting->getCounterTotal('pwainstall');
			$this->data['total_pwa_orders'] = $this->model_setting_setting->getPWAOrdersTotal();
			
			$data = array(
			'filter_date_added' 			=> date('Y-m-d', strtotime("-1 month")),
			'filter_date_added_to' 			=> date('Y-m-d'),
			'filter_pwa'					=> true,
			'filter_order_status_notnull' 	=> true
			);
			$this->data['orders_pwa_now'] = $this->model_sale_order->getTotalOrders($data);
			$this->data['pwa_percent_for_last_month'] = $this->data['orders_now']?round((($this->data['orders_pwa_now'] / $this->data['orders_now']) * 100), 1):0;

			if ($this->config->get('config_country_id') == 176) {
				$this->data['total_yam_orders'] = $this->model_setting_setting->getYAMTotalOrders();

				$data = array(
					'filter_date_added' 			=> date('Y-m-d', strtotime("-1 month")),
					'filter_date_added_to' 			=> date('Y-m-d'),
					'filter_yam'					=> true,
					'filter_order_status_notnull' 	=> true,
					'filter_not_preorder'			=> true,
					'filter_order_store_id'			=> 0
				);
				$this->data['orders_yam_now'] = $this->model_sale_order->getTotalOrders($data);

				$data = array(
					'filter_date_added' 			=> date('Y-m-d', strtotime("-1 month")),
					'filter_date_added_to' 			=> date('Y-m-d'),
					'filter_order_status_notnull' 	=> true,
					'filter_not_preorder'			=> true,
					'filter_order_store_id'			=> 0
				);
				$this->data['orders_ru_now'] = $this->model_sale_order->getTotalOrders($data);
				$this->data['yam_percent_for_last_month'] = $this->data['orders_ru_now']?round((($this->data['orders_yam_now'] / $this->data['orders_ru_now']) * 100), 1):0;

				$this->data['yam_orders_total'] = $this->model_setting_setting->getYAMOrdersTotal();
				$this->data['yam_comission'] = ($this->data['yam_orders_total'] / 100) * 12;
				$this->data['yam_comission'] = formatLongNumber($this->data['yam_comission']);

			//$this->data['yam_comission1'] = $this->currency->format($this->data['yam_comission'], 'RUB', '1');
				$this->data['yam_orders_total'] = $this->currency->format($this->data['yam_orders_total'], 'RUB', '1');
				$this->data['yam_orders_total'] = formatLongNumber($this->data['yam_orders_total']);
			}
			
			
			$this->template = 'homestats/prettystats.tpl';
			
			$this->response->setOutput($this->render());
		}

		public function loadProductStats(){
			$this->load->model('catalog/product');
			$this->load->model('report/product');
			$this->load->model('catalog/category');
			$this->load->model('setting/setting');

			$format = true;
			if (!empty($this->request->get['long']) && $this->request->get['long'] == true){
				$format = false;
			}

			$this->data['translated_total_hour']			= formatLongNumber($this->model_setting_setting->getTranslatedTotalHour(), $format);
			$this->data['translated_total_today']			= formatLongNumber($this->model_setting_setting->getTranslatedTotalToday(), $format);
			$this->data['translated_total_yesterday']		= formatLongNumber($this->model_setting_setting->getTranslatedTotalYesterday(), $format);
			$this->data['translated_total_week']			= formatLongNumber($this->model_setting_setting->getTranslatedTotalWeek(), $format);
			$this->data['translated_total_month']			= formatLongNumber($this->model_setting_setting->getTranslatedTotalMonth(), $format);

			$this->data['total_products_double_asin']			= $this->model_catalog_product->getTotalProductsWithDoubleAsin();

			$this->data['total_products_invalid_asin']			= $this->model_catalog_product->getTotalProductsWithInvalidAsin();
			$this->data['filter_total_products_invalid_asin'] 	= $this->url->link('catalog/product_ext', 'filter_asin=INVALID&token=' . $this->session->data['token']);

			$this->data['total_products'] 					= formatLongNumber($this->model_catalog_product->getTotalProductsSimple(), $format);
			$this->data['total_products_no_variants']		= formatLongNumber($this->model_catalog_product->getTotalProductsSimpleNoVariants(), $format);

			$this->data['total_product_enabled'] 			= formatLongNumber($this->model_catalog_product->getTotalProducts(['filter_status' => 1]));
			$this->data['filter_total_products_enabled'] 	= $this->url->link('catalog/product_ext', 'filter_status=1&token=' . $this->session->data['token']);


			$this->data['total_product_parsed'] 			= formatLongNumber($this->model_catalog_product->getTotalProductsFilled(), $format);
			$this->data['total_product_need_to_be_parsed'] 	= formatLongNumber($this->model_catalog_product->getTotalProductsNeedToBeFilled(), $format);
			$this->data['filter_total_product_parsed'] 		= $this->url->link('catalog/product_ext', 'filter_filled_from_amazon=1&token=' . $this->session->data['token']);

			$this->data['total_products_in_tech'] 			= $this->model_catalog_product->getTotalProducts(['filter_category_id' => $this->config->get('config_rainforest_default_technical_category_id')]);
			$this->data['filter_total_products_in_tech'] 	= $this->url->link('catalog/product_ext', 'filter_category=' . $this->config->get('config_rainforest_default_technical_category_id') . '&token=' . $this->session->data['token']);

			$this->data['total_product_in_queue']				= $this->model_report_product->getCountWaitingInASINQueue();
			$this->data['total_product_in_variants_queue']		= $this->model_report_product->getCountWaitingInVariantsQueue();
			$this->data['total_products_in_queue_today']		= $this->model_report_product->getCountAddedTodayInASINQueue();
			$this->data['filter_product_in_queue']				= $this->url->link('catalog/addasin', 'token=' . $this->session->data['token']);

			$this->data['total_product_got_offers']				= formatLongNumber($this->model_catalog_product->getTotalProductsGotOffers(), $format);
			
			//$this->data['total_product_to_get_offers']			= formatLongNumber($this->registry->get('rainforestAmazon')->offersParser->getTotalProductsToGetOffers(), $format);
			//$this->data['total_product_got_offers_today']		= formatLongNumber($this->model_catalog_product->getTotalProductsGotOffersByDate(date('Y-m-d')), $format);					
			$this->data['total_product_to_get_offers']			= $this->registry->get('rainforestAmazon')->offersParser->getTotalProductsToGetOffers();
			$this->data['total_product_to_get_offers_in_queue']	= $this->registry->get('rainforestAmazon')->offersParser->getTotalProductsAmazonOffersInQueue();

			$this->data['total_product_got_offers_today']		= $this->model_catalog_product->getTotalProductsGotOffersByDate(date('Y-m-d'));

			$this->data['total_product_got_offers_yesterday']	= formatLongNumber($this->model_catalog_product->getTotalProductsGotOffersByDate(date('Y-m-d', strtotime('-1 day'))), $format);

			$this->data['total_product_have_offers']			= formatLongNumber($this->model_catalog_product->getTotalProductsHaveOffers(), $format);
			$this->data['filter_total_product_have_offers'] 	= $this->url->link('catalog/product_ext', 'filter_stock_status=' . $this->config->get('config_stock_status_id') . '&token=' . $this->session->data['token']);
			$this->data['total_product_have_no_offers']			= formatLongNumber($this->model_catalog_product->getTotalProductsHaveNoOffers(), $format);
			$this->data['filter_total_product_have_no_offers'] 	= $this->url->link('catalog/product_ext', 'filter_stock_status=' . $this->config->get('config_rainforest_nooffers_status_id') . '&token=' . $this->session->data['token']);

			$this->data['total_products_added_today'] 			= formatLongNumber($this->model_catalog_product->getTotalProductsAdded(date('Y-m-d')), $format);
			$this->data['filter_total_products_added_today'] 	= $this->url->link('catalog/product_ext', 'filter_date_added=' . date('Y-m-d') . '&token=' . $this->session->data['token']);

			$this->data['total_products_added_yesterday'] 			= formatLongNumber($this->model_catalog_product->getTotalProductsAdded(date('Y-m-d', strtotime('-1 day'))), $format);
			$this->data['filter_total_products_added_yesterday'] 	= $this->url->link('catalog/product_ext', 'filter_date_added=' . date('Y-m-d', strtotime('-1 day')) . '&token=' . $this->session->data['token']);

			$this->data['total_products_added_week'] 				= formatLongNumber($this->model_catalog_product->getTotalProductsAdded(['from' => date('Y-m-d', strtotime('-1 week')), 'to' => date('Y-m-d')]), $format);
			$this->data['total_products_added_month'] 				= formatLongNumber($this->model_catalog_product->getTotalProductsAdded(['from' => date('Y-m-d', strtotime('-1 month')), 'to' => date('Y-m-d')]), $format);

			$this->data['total_categories'] 						= $this->model_catalog_category->getTotalCategories();
			$this->data['total_categories_final'] 					= $this->model_catalog_category->getTotalCategoriesAmazonFinal();
			$this->data['total_categories_enable_load'] 			= $this->model_catalog_category->getTotalCategoriesEnableLoad();
			$this->data['total_categories_enable_full_load'] 		= $this->model_catalog_category->getTotalCategoriesEnableFullLoad();

			$this->template = 'homestats/productstats.tpl';
			if (!empty($this->request->get['tpl']) && $this->request->get['tpl'] == 'rnf'){
				$this->template = 'setting/rnfstats.tpl';				
			}

			$this->response->setOutput($this->render());
		}

		private function getOrderData($period, $store, $filter_addon = []) {
			$currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']);

			switch ($period) {
				case 'today':
				$start_date = date('Y-m-d'); 
				$end_date = date('Y-m-d');
				break;

				case 'yesterday':
				$start_date = date('Y-m-d', strtotime('-1 day'));
				$end_date = date('Y-m-d', strtotime('-1 day'));
				break;

				case 'week':
				$start_date = date('Y-m-d', strtotime('-1 week'));  
				$end_date = date('Y-m-d');
				break;

				case 'month':
				$start_date = date('Y-m-d', strtotime('-1 month'));
				$end_date = date('Y-m-d');
				break;

				case 'year':
				$start_date = date('Y-m-d', strtotime('-1 year'));
				$end_date = date('Y-m-d');
				break;
			}

			$filter_data = [
				'filter_order_store_id' 		=> $store['store_id'],
				'filter_date_added'  			=> $start_date,
				'filter_date_added_to' 			=> $end_date,
				'filter_order_status_notnull' 	=> true,  
				'filter_not_preorder' 			=> true,
				'return_array' 					=> true  
			];

			$filter_data = array_merge($filter_data, $filter_addon);	

			$orders 			= $this->model_sale_order->getTotalOrders($filter_data);			
			$orders['discount'] = $this->model_sale_order->getTotalOrdersDiscounts($filter_data); 
			$orders['percent'] 	= $orders['sum']?round(($orders['discount'] / $orders['sum'])*100, 2):0;

			$orders['sum'] 		= $this->currency->format($orders['sum'], $currency,'1');				
			$orders['discount'] = $this->currency->format($orders['discount'], $currency,'1');

			return $orders;
		}
		
		public function loadOrderStats(){			
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('sale/order');
			$this->load->model('localisation/country');
			
			$this->data['order_filters'] = [];
			$this->data['fast_counters'] = [];

			$this->data['stores_count'] = count($this->model_setting_store->getStores());

			foreach ($this->model_setting_store->getStores() as $store){								
				if ($store['store_id'] == 18){
					continue;
				}
				
				$currency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store['store_id']);
				
				$this->data['fast_counters'][$store['store_id']] = array(
				'store_id' 		=> $store['store_id'],
				'pwainstall'	=> $this->model_setting_setting->getCounter('pwainstall', $store['store_id']),
				'pwasession'	=> $this->model_setting_setting->getCounter('pwasession', $store['store_id']),
				'pwaorders'		=> $this->model_setting_setting->getPWAOrders($store['store_id']),
				'rewardplus'	=> $this->currency->format($this->model_setting_setting->getRewardPlus($store['store_id']), $currency,'1'),
				'rewardminus'	=> $this->currency->format($this->model_setting_setting->getRewardMinus($store['store_id']), $currency,'1'),
				'rewardtotal'	=> $this->currency->format($this->model_setting_setting->getRewardTotal($store['store_id']), $currency,'1'),
				'rewardorders'	=> $this->model_setting_setting->getRewardOrders($store['store_id'])
				);								
				
				foreach (['today', 'yesterday', 'week', 'month', 'year'] as $period){
					${$period . '_orders'} = $this->getOrderData($period, $store);
				}

				foreach (['today', 'yesterday', 'week', 'month', 'year'] as $period){
					${$period . '_ok_orders'} = $this->getOrderData($period, $store, ['filter_order_status_id' => 'except_cancelled']);
				}
				
				$this->data['order_filters'][] = [
				'store_id' 		   => $store['store_id'],
				'name' 		   	   => $store['name'],
				'today_orders'	   => $today_orders,
				'yesterday_orders' => $yesterday_orders,
				'week_orders' 	   => $week_orders,
				'month_orders' 	   => $month_orders,
				'year_orders' 	   => $year_orders,		
				'today_ok_orders'	  => $today_ok_orders,
				'yesterday_ok_orders' => $yesterday_ok_orders,
				'week_ok_orders' 	  => $week_ok_orders,
				'month_ok_orders' 	  => $month_ok_orders,
				'year_ok_orders' 	  => $year_ok_orders,		
				'language'		   => $this->model_setting_setting->getKeySettingValue('config', 'config_language', $store['store_id']),
				'filter_href'	   => $this->url->link('sale/order', 'filter_order_store_id=' . $store['store_id'] . '&token=' . $this->session->data['token'], 'SSL')
				];
			}
			
			$this->template = 'homestats/orderstats.tpl';
			
			$this->response->setOutput($this->render());			
		}
		
		public function index() {
			$this->language->load('common/home');
			$this->load->model('setting/store');

			$this->document->setTitle($this->language->get('heading_title'));
			
			if ($this->user->getUserGroup() == 26 && !isset($this->request->get['no_redirect'])){
				$this->redirect($this->url->link('sale/courier2', 'token=' . $this->session->data['token'], 'SSL'));				
			}
			
			$this->data['heading_title'] 			= $this->language->get('heading_title');
			$this->data['stores_count'] 			= count($this->model_setting_store->getStores());			
			$this->data['text_overview'] 			= $this->language->get('text_overview');
			$this->data['text_statistics'] 			= $this->language->get('text_statistics');
			$this->data['text_latest_10_orders'] 	= $this->language->get('text_latest_10_orders');
			$this->data['text_total_sale'] 			= $this->language->get('text_total_sale');
			$this->data['text_total_sale_year'] 	= $this->language->get('text_total_sale_year');
			$this->data['text_total_order'] 		= $this->language->get('text_total_order');
			$this->data['text_total_customer'] = $this->language->get('text_total_customer');
			$this->data['text_total_customer_approval'] = $this->language->get('text_total_customer_approval');
			$this->data['text_total_review_approval'] 	= $this->language->get('text_total_review_approval');
			$this->data['text_total_affiliate'] 		= $this->language->get('text_total_affiliate');
			$this->data['text_total_affiliate_approval'] = $this->language->get('text_total_affiliate_approval');
			$this->data['text_day'] 		= $this->language->get('text_day');
			$this->data['text_week'] 		= $this->language->get('text_week');
			$this->data['text_month'] 		= $this->language->get('text_month');
			$this->data['text_year'] 		= $this->language->get('text_year');
			$this->data['text_no_results'] 	= $this->language->get('text_no_results');
			
			$this->data['column_order'] 		= $this->language->get('column_order');
			$this->data['column_customer'] 		= $this->language->get('column_customer');
			$this->data['column_status'] 		= $this->language->get('column_status');
			$this->data['column_date_added'] 	= $this->language->get('column_date_added');
			$this->data['column_total'] 		= $this->language->get('column_total');
			$this->data['column_firstname'] 	= $this->language->get('column_firstname');
			$this->data['column_lastname'] 		= $this->language->get('column_lastname');
			$this->data['column_action'] 		= $this->language->get('column_action');			
			$this->data['entry_range'] 			= $this->language->get('entry_range');

			$this->data['breadcrumbs'] = [];
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
			);
			
			$this->data['token'] = $this->session->data['token'];
			
			$this->load->model('user/user');
			$this->data['managers'] = $this->model_user_user->getUsersByGroups(array(12, 19), true);		
			$this->data['order_url'] = $this->url->link('sale/order', 'token=' . $this->session->data['token']);
			$this->data['fucked_link_url'] = $this->url->link('sale/order', 'filter_order_status_id=0&token=' . $this->session->data['token']);
			
			$this->load->model('setting/store');
			$this->load->model('sale/order');
			$this->load->model('localisation/country');					
			
			$this->data['waitlist_url'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token']);
			$this->data['parties_url'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token']);
			$this->data['stocks_url'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token']);
			$this->data['sale_return_url'] = $this->url->link('sale/return', 'token=' . $this->session->data['token']);
			$this->data['sale_coupon_url'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token']);
			$this->data['customer_url'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token']);
			$this->data['customer_group_url'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token']);
			$this->data['callback_url'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token']);
			$this->load->model('sale/callback');
			$this->data['total_callbacks'] = $this->model_sale_callback->getOpenedCallBacks();
			
			$this->load->model('catalog/product');
			$this->data['total_waitlist_ready'] = $this->model_catalog_product->getProductsWaitListTotalReady();
			
			$this->load->model('sale/order');
			if (true || $this->user->getOwnOrders()) {
				$this->data['total_problem_orders'] = $this->model_sale_order->getProblemOrdersCount();
				$this->data['total_toapprove_orders'] = $this->model_sale_order->getToApproveOrdersCount();
				} else {
				$this->data['total_problem_orders'] = 0;
				$this->data['total_toapprove_orders'] = 0;
			}
			
			$this->data['keyworder_link'] = $this->url->link('module/keyworder', 'token=' . $this->session->data['token']);
			$this->data['category_link'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token']);		
			$this->data['batch_editor_link'] = $this->url->link('module/batch_editor', 'token=' . $this->session->data['token']);
			$this->data['banner_url'] = $this->url->link('module/mattimeobanner', 'token=' . $this->session->data['token']);
			$this->data['csv_pro_url'] = $this->url->link('module/csvprice_pro', 'token=' . $this->session->data['token']);
			$this->data['information_link'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token']);
			$this->data['lp_link'] = $this->url->link('catalog/landingpage', 'token=' . $this->session->data['token']);
			$this->data['faq_url'] = $this->url->link('module/faq_system', 'token=' . $this->session->data['token']);
			$this->data['shortnames'] = $this->url->link('catalog/shortnames', 'token=' . $this->session->data['token']);

			$this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token']);
            if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status') && isset($this->session->data['token'])) {
                $this->data['product'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token']);
            }
			$this->data['addasin'] = $this->url->link('catalog/addasin', 'token=' . $this->session->data['token']);
			$this->data['product_deletedasin'] = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token']);
			
			// Модули товаров
			$this->data['module_product_alsopurchased'] = $this->url->link('module/alsopurchased', 'token=' . $this->session->data['token']);
			$this->data['module_product_bestseller'] = $this->url->link('module/bestseller', 'token=' . $this->session->data['token']);
			$this->data['module_product_featured'] = $this->url->link('module/featured', 'token=' . $this->session->data['token']);
			$this->data['module_product_latest'] = $this->url->link('module/latest', 'token=' . $this->session->data['token']);
			$this->data['module_product_faproduct'] = $this->url->link('module/faproduct', 'token=' . $this->session->data['token']);
			$this->data['module_product_featuredreview'] = $this->url->link('module/featuredreview', 'token=' . $this->session->data['token']);
			
			$this->data['mreport_ttnscan'] = $this->url->link('report/mreports', 'report=ttnscan&token=' . $this->session->data['token']);
			$this->data['mreport_needtocall'] = $this->url->link('report/mreports', 'report=needtocall&token=' . $this->session->data['token']);
			$this->data['mreport_nopaid'] = $this->url->link('report/mreports', 'report=nopaid&token=' . $this->session->data['token']);
			$this->data['mreport_minusscan'] = $this->url->link('report/mreports', 'report=minusscan&token=' . $this->session->data['token']);
			$this->data['mreport_forgottencart'] = $this->url->link('report/mreports', 'report=forgottencart&token=' . $this->session->data['token']);
			
			
			$this->template = 'common/home.tpl';
			if ($template_prefix = $this->user->getTemplatePrefix()) {
             	$this->template = 'common/homes/home' . $template_prefix . '.tpl';
        	}

			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
		}
						
		public function getTTNScanResult(){
			$query = $this->db->query("SELECT * FROM `temp` WHERE `key` = 'ttnscan_result'");
			
			$html = $query->row['value'];			
			$html = preg_replace("!<order>(.*?)</order>!si","<a href='?token=".$this->session->data['token']."&route=sale/order/update&order_id=\\1' target='_blank'>\\1</a>", $html);			
			$html = preg_replace("!<ttn>(.*?)</ttn>!si","<span class='get_ttn_info' data-ttn='\\1'>\\1</span>&nbsp;&nbsp;<span style='display:none;'></span>", $html);
			
			echo $html;
		}
		
		public function getMinusScanResult(){
			$query = $this->db->query("SELECT * FROM `temp` WHERE `key` = 'minusscan_result'");
			
			$html = $query->row['value'];			
			$html = preg_replace("!<order>(.*?)</order>!si","<a href='?token=".$this->session->data['token']."&route=sale/order/update&order_id=\\1' target='_blank'>\\1</a>", $html);
			
			echo $html;
		}
		
		public function getOrdersResult(){
			$query = $this->db->query("SELECT * FROM `temp` WHERE `key` = 'orders_result'");
			
			$html = $query->row['value'];			
			$html = preg_replace("!<order>(.*?)</order>!si","<a href='?token=".$this->session->data['token']."&route=sale/order/update&order_id=\\1' target='_blank'>\\1</a>", $html);			
			$html = preg_replace("!<customer>(.*?)</customer>!si","<a href='?token=".$this->session->data['token']."&route=sale/customer/update&customer_id=\\1' target='_blank'>\\1</a>", $html);			
			$html = preg_replace("!<filter>(.*?)</filter>!si","<a href='?token=".$this->session->data['token']."&route=sale/order&filter_order_status_id=0&filter_customer=\\1' target='_blank'>фильтр</a>", $html);			
			
			echo $html;
		}
		
		public function getNeedToCallResult(){			
			$query = $this->db->query("SELECT * FROM `temp` WHERE `key` = 'callscan_result'");
			
			$html = $query->row['value'];			
			$html = preg_replace("!<order>(.*?)</order>!si","<a href='?token=".$this->session->data['token']."&route=sale/order/update&order_id=\\1' target='_blank'>\\1</a>", $html);			
			$html = preg_replace("!<customer>(.*?)</customer>!si","<a href='?token=".$this->session->data['token']."&route=sale/customer/update&customer_id=\\1' target='_blank'>\\1</a>", $html);			
			$html = preg_replace("!<filter>(.*?)</filter>!si","<a href='?token=".$this->session->data['token']."&route=sale/order&filter_order_status_id=0&filter_customer=\\1' target='_blank'>фильтр</a>", $html);
			
			
			echo $html;			
		}
		
		public function getNoPaidResult(){
			$query = $this->db->query("SELECT * FROM `temp` WHERE `key` = 'nopaid_result'");
			
			$html = $query->row['value'];		
			$html = preg_replace("!<order>(.*?)</order>!si","<a href='?token=".$this->session->data['token']."&route=sale/order/update&order_id=\\1' target='_blank'>\\1</a>", $html);
			$html = preg_replace("!<customer>(.*?)</customer>!si","<a href='?token=".$this->session->data['token']."&route=sale/customer/update&customer_id=\\1' target='_blank'>\\1</a>", $html);
			$html = preg_replace("!<filter>(.*?)</filter>!si","<a href='?token=".$this->session->data['token']."&route=sale/order&filter_order_status_id=0&filter_customer=\\1' target='_blank'>фильтр</a>", $html);
			
			echo $html;			
		}
		
		public function cancelchart() {
			$this->language->load('common/home');
			
			$data = [];
			
			$data['order'] = [];
			$data['customer'] = [];
			$data['xaxis'] = [];
			
			$data['order']['label'] = $this->language->get('text_order');
			$data['customer']['label'] = $this->language->get('text_customer');
			
			if (isset($this->request->get['range'])) {
				$range = $this->request->get['range'];
				} else {
				$range = 'month';
			}
			
			switch ($range) {				
				case 'week_orders':
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				$data['cancelled_order'] = [];
				$data['cancelled_order']['label'] = 'Отмененные за день заказы';
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order_history` 
					WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' 
					AND DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");
					
					
					if ($query->num_rows) {
						$data['cancelled_order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['cancelled_order']['data'][] = array($i, 0);
					}
					
					setlocale(LC_TIME, "ru_RU.UTF8");
					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}
				
				break;
			} 
			
			$this->response->setOutput(json_encode($data));
		}
		
		public function loyalitychart(){
			
			$this->language->load('common/home');
			
			$data = [];
			
			$data['order'] = [];									
			$data['loyal_order'] = [];
			$data['xaxis'] = [];
			
			$data['order']['label'] = $this->language->get('text_order');
			$data['loyal_order']['label'] = 'Из них повторных заказов';
			
			
			if (isset($this->request->get['range'])) {
				$range = $this->request->get['range'];
				} else {
				$range = 'weekloyality';
			}
			
			switch ($range) {				
				case 'weekloyality':
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");
					
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][] = array($i, 0);
					}		
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 AND DATE(date_added) = DATE('" . $this->db->escape($date) . "') AND customer_id IN (SELECT customer_id FROM `order` WHERE order_status_id > '0' AND order_status_id <> 23 GROUP BY customer_id HAVING COUNT(order_id) > 1) GROUP BY DATE(date_added)");
					
					
					if ($query->num_rows) {
						$data['loyal_order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['loyal_order']['data'][] = array($i, 0);
					}
					
					setlocale(LC_TIME, "ru_RU.UTF8");
					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}				
			} 
			
			$this->response->setOutput(json_encode($data));
		}
		
		public function chart() {
			$this->language->load('common/home');
			
			$data = [];
			
			$data['order'] = [];
			$data['customer'] = [];
			$data['xaxis'] = [];
			
			$data['order']['label'] = $this->language->get('text_order');
			$data['customer']['label'] = $this->language->get('text_customer');
			
			if (isset($this->request->get['range'])) {
				$range = $this->request->get['range'];
				} else {
				$range = 'month';
			}
			
			switch ($range) {
				case 'day':
				for ($i = 0; $i < 24; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND (DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "') GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					
					if ($query->num_rows) {
						$data['order']['data'][]  = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][]  = array($i, 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "' GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['customer']['data'][] = array($i, 0);
					}
					
					$data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
				}					
				break;
				case 'week':
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', strtotime("-$i day"));
					
					$query = $this->db->query("SELECT COUNT(order_id) AS total FROM `order` WHERE order_status_id > '0' AND DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][] = array($i, 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `customer` WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['customer']['data'][] = array($i, 0);
					}
					
					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}
				
				break;
				
				case 'week_orders':
				$date_start = strtotime('-' . date('w') . ' days'); 
				
				$data['cancelled_order'] = [];
				$data['cancelled_order']['label'] = 'Из них отменено заказов';
				
				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");
					
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][] = array($i, 0);
					}		
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id = '" . $this->config->get('config_cancelled_status_id') . "' AND DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");
					
					
					if ($query->num_rows) {
						$data['cancelled_order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['cancelled_order']['data'][] = array($i, 0);
					}
					
					setlocale(LC_TIME, "ru_RU.UTF8");
					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}
				
				break;
				
				default:
				case 'month':
				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND (DATE(date_added) = '" . $this->db->escape($date) . "') GROUP BY DAY(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][] = array($i, 0);
					}	
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DAY(date_added)");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['customer']['data'][] = array($i, 0);
					}	
					
					$data['xaxis'][] = array($i, date('j', strtotime($date)));
				}
				break;
				case 'year':
				for ($i = 1; $i <= 12; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][] = array($i, 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM customer WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");
					
					if ($query->num_rows) { 
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['customer']['data'][] = array($i, 0);
					}
					
					$data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
				}			
				break;	
				case 'years':
				for ($i = 2014; $i <= date('Y'); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `order` WHERE order_status_id > '0' AND YEAR(date_added) = '". (int)$i ."' GROUP BY YEAR(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array($i, (int)$query->row['total']);
						} else {
						$data['order']['data'][] = array($i, 0);
					}
					
					
					$data['xaxis'][] = array($i, date('Y', strtotime("01.01.$i")));
				}			
				break;
			} 
			
			$this->response->setOutput(json_encode($data));
		}
		
		public function login() {
			$route = '';
			
			$this->secureURL($this->request->get);
			
			if (isset($this->request->get['route'])) {
				$part = explode('/', $this->request->get['route']);
				
				if (isset($part[0])) {
					$route .= $part[0];
				}
				
				if (isset($part[1])) {
					$route .= '/' . $part[1];
				}
			}
			
			$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
			);	
			
			if (!$this->user->isLogged() && !in_array($route, $ignore)) {
				return $this->forward('common/login');
			}
			
			if (isset($this->request->get['route'])) {
				$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
				);
				
				$config_ignore = [];
				
				if ($this->config->get('config_token_ignore')) {
					$config_ignore = unserialize($this->config->get('config_token_ignore'));
				}
				
				$ignore = array_merge($ignore, $config_ignore);
				
				if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
					return $this->forward('common/login');
				}
				} else {
				if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
					return $this->forward('common/login');
				}
			}
		}			
		
		public function secureURL($getRequestVar) {
			
			$valid = FALSE;
			if ( $this->config->get('secure_status') &&  $this->config->get('secure_status') == 1 ) {
				$db_skey    =  $this->config->get('secure_key');
				$db_sval    =  $this->config->get('secure_value');
				
				$session_skey = "" ; 
				$session_sval = "" ; 
				If( isset($this->session->data[$db_skey]) ) {
					
					$k = explode("_",$this->session->data[$db_skey]);
					$session_skey = $k[0];
					$session_sval = $k[1];				
				}
				
				if(!empty($getRequestVar)){					
					if(isset($getRequestVar[$db_skey] )){
						$getlink = array( $db_skey => $getRequestVar[$db_skey] );
						
					}}
					
					$url_skey = "" ;
					$url_sval = "" ;
					
					if(isset($getlink) ){
						foreach ( $getlink as $key =>$value ){
							$url_skey =$key;
							$url_sval = $value; 
						}
					}					
					
					if($db_skey && $db_sval && $url_skey && $url_sval ){						
						if ( $db_skey === $url_skey && $db_sval === $url_sval ) {
							$this->session->data[$db_skey] = $db_skey."_".$db_sval  ;
							$valid = TRUE;	
						}						
					}
					
					if ( $session_skey && $session_sval && $session_skey === $db_skey  && $session_sval === $db_sval ) {						
						$valid = TRUE;				
					}
					
					if (!$valid ) {						
						header('Location: ' . HTTPS_CATALOG);
						exit;
					}					
				}
		}	
		
		public function getCustomersOnlineAjax(){
			$data = [];
			$this->load->model('report/online');
			echo ($this->model_report_online->getTotalCustomersOnlineNotBotsFast());
		}
		
		public function getCustomersOnlineAppAjax(){
			$data = [];
			$this->load->model('report/online');
			echo ($this->model_report_online->getTotalCustomersOnlineAppSessionsNotBotsFast());
		}
		
		public function permission() {
			if (isset($this->request->get['route'])) {
				$route = '';
				
				$part = explode('/', $this->request->get['route']);
				
				if (isset($part[0])) {
					$route .= $part[0];
				}
				
				if (isset($part[1])) {
					$route .= '/' . $part[1];
				}
				
				$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'		
				);			
				
				if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
					return $this->forward('error/permission');
				}
			}
		}	
	}
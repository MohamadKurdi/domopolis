<?php
static $config = NULL;
static $log = NULL;

// Error Handler
function error_handler_for_export($errno, $errstr, $errfile, $errline) {
	global $config;
	global $log;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
		
	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

function fatal_error_shutdown_handler_for_export() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

class ControllerReportAdvSaleOrder extends Controller { 
	public function index() {
		$this->load->language('report/adv_sale_order');

		if (!$this->IsInstalled()) {		
			$this->session->data['error'] = $this->language->get('error_installed');
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));		
		}
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('report/adv_sale_order');
		
		if (isset($this->request->post['filter_date_start'])) {
			$filter_date_start = $this->request->post['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->post['filter_date_end'])) {
			$filter_date_end = $this->request->post['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		$this->data['ranges'] = array();
		
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_custom'),
			'value' => 'custom',
			'style' => 'color:#666',
		);			
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_today'),
			'value' => 'today',
			'style' => 'color:#090',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_yesterday'),
			'value' => 'yesterday',
			'style' => 'color:#090',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_week'),
			'value' => 'week',
			'style' => 'color:#090',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_month'),
			'value' => 'month',
			'style' => 'color:#090',
		);					
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_quarter'),
			'value' => 'quarter',
			'style' => 'color:#090',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_year'),
			'value' => 'year',
			'style' => 'color:#090',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_week'),
			'value' => 'current_week',
			'style' => 'color:#06C',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_month'),
			'value' => 'current_month',
			'style' => 'color:#06C',
		);	
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_quarter'),
			'value' => 'current_quarter',
			'style' => 'color:#06C',
		);			
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_current_year'),
			'value' => 'current_year',
			'style' => 'color:#06C',
		);			
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_week'),
			'value' => 'last_week',
			'style' => 'color:#F90',
		);
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_month'),
			'value' => 'last_month',
			'style' => 'color:#F90',
		);	
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_quarter'),
			'value' => 'last_quarter',
			'style' => 'color:#F90',
		);			
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_last_year'),
			'value' => 'last_year',
			'style' => 'color:#F90',
		);			
		$this->data['ranges'][] = array(
			'text'  => $this->language->get('stat_all_time'),
			'value' => 'all_time',
			'style' => 'color:#F00',
		);
		
		if (isset($this->request->post['filter_range'])) {
			$filter_range = $this->request->post['filter_range'];
		} else {
			$filter_range = 'current_year'; //show Current Year in Statistical Range by default
		}

		$this->data['report'] = array();
		
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_sales_summary'),
			'value' => 'sales_summary',
		);			
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_day_of_week'),
			'value' => 'day_of_week',
		);		
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_hour'),
			'value' => 'hour',
		);	
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_store'),
			'value' => 'store',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_customer_group'),
			'value' => 'customer_group',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_country'),
			'value' => 'country',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_postcode'),
			'value' => 'postcode',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_region_state'),
			'value' => 'region_state',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_city'),
			'value' => 'city',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_payment_method'),
			'value' => 'payment_method',
		);
		$this->data['report'][] = array(
			'text'  => $this->language->get('text_shipping_method'),
			'value' => 'shipping_method',
		);
		
		if (isset($this->request->post['filter_report'])) {
			$filter_report = $this->request->post['filter_report'];
		} else {
			$filter_report = 'sales_summary'; //show Sales Summary in Report By default
		}
		
		$this->data['groups'] = array();
		
		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_quarter'),
			'value' => 'quarter',
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
		
		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_order'),
			'value' => 'order',
		);
		
		if (isset($this->request->post['filter_group']) && (isset($this->request->post['filter_report']) && $this->request->post['filter_report'] == 'sales_summary')) {
			$filter_group = $this->request->post['filter_group'];
		} elseif (isset($this->request->post['filter_report']) && $this->request->post['filter_report'] != 'sales_summary') {
			$filter_group = '';	
		} elseif (!isset($this->request->post['filter_group']) && (isset($this->request->post['filter_report']) && $this->request->post['filter_report'] == 'sales_summary')) {
			$filter_group = 'month'; //show Month in Group Report by default		
		} elseif (!isset($this->request->post['filter_group']) && !isset($this->request->post['filter_report'])) {
			$filter_group = 'month'; //show Month in Group Report by default
		}			

		if (isset($this->request->post['filter_sort'])) {
			$filter_sort = $this->request->post['filter_sort'];
		} else {
			$filter_sort = 'type';
		}	

		if (isset($this->request->post['filter_details'])) {
			$filter_details = $this->request->post['filter_details'];
		} else {
			$filter_details = 0;
		}	

		if (isset($this->request->post['filter_limit'])) {
			$filter_limit = $this->request->post['filter_limit'];
		} else {
			$filter_limit = 25;
		}

		if (isset($this->request->post['filter_status_date_start'])) {
			$filter_status_date_start = $this->request->post['filter_status_date_start'];
		} else {
			$filter_status_date_start = '';
		}

		if (isset($this->request->post['filter_status_date_end'])) {
			$filter_status_date_end = $this->request->post['filter_status_date_end'];
		} else {
			$filter_status_date_end = '';
		}
		
		$this->data['order_statuses'] = $this->model_report_adv_sale_order->getOrderStatuses(); 			
		if (isset($this->request->post['filter_order_status_id']) && is_array($this->request->post['filter_order_status_id'])) {
			$filter_order_status_id = array_flip($this->request->post['filter_order_status_id']);
		} else {
			$filter_order_status_id = '';
		}

		if (isset($this->request->post['filter_order_id_from'])) {
			if (is_numeric(trim($this->request->post['filter_order_id_from']))) {
				$filter_order_id_from = trim($this->request->post['filter_order_id_from']);
			} else {
				$filter_order_id_from = '';
			}
		} else {
			$filter_order_id_from = '';
		}
		
		if (isset($this->request->post['filter_order_id_to'])) {
			if (is_numeric(trim($this->request->post['filter_order_id_to']))) {
				$filter_order_id_to = trim($this->request->post['filter_order_id_to']);
			} else {
				$filter_order_id_to = '';
			}
		} else {
			$filter_order_id_to = '';
		}
		
		$this->data['stores'] = $this->model_report_adv_sale_order->getOrderStores();						
		if (isset($this->request->post['filter_store_id']) && is_array($this->request->post['filter_store_id'])) {
			$filter_store_id = array_flip($this->request->post['filter_store_id']);
		} else {
			$filter_store_id = '';			
		}
		
		$this->data['currencies'] = $this->model_report_adv_sale_order->getOrderCurrencies();	
		if (isset($this->request->post['filter_currency']) && is_array($this->request->post['filter_currency'])) {
			$filter_currency = array_flip($this->request->post['filter_currency']);
		} else {
			$filter_currency = '';		
		}

		$this->data['taxes'] = $this->model_report_adv_sale_order->getOrderTaxes();					
		if (isset($this->request->post['filter_taxes']) && is_array($this->request->post['filter_taxes'])) {
			$filter_taxes = array_flip($this->request->post['filter_taxes']);
		} else {
			$filter_taxes = '';		
		}

		$this->data['tax_classes'] = $this->model_report_adv_sale_order->getOrderTaxClasses();					
		if (isset($this->request->post['filter_tax_classes']) && is_array($this->request->post['filter_tax_classes'])) {
			$filter_tax_classes = array_flip($this->request->post['filter_tax_classes']);
		} else {
			$filter_tax_classes = '';		
		}
		
		$this->data['geo_zones'] = $this->model_report_adv_sale_order->getOrderGeoZones();					
		if (isset($this->request->post['filter_geo_zones']) && is_array($this->request->post['filter_geo_zones'])) {
			$filter_geo_zones = array_flip($this->request->post['filter_geo_zones']);
		} else {
			$filter_geo_zones = '';		
		}
		
		$this->data['customer_groups'] = $this->model_report_adv_sale_order->getOrderCustomerGroups();		
		if (isset($this->request->post['filter_customer_group_id']) && is_array($this->request->post['filter_customer_group_id'])) {
			$filter_customer_group_id = array_flip($this->request->post['filter_customer_group_id']);
		} else {
			$filter_customer_group_id = '';
		}
		
		if (isset($this->request->post['filter_customer_name'])) {
			$filter_customer_name = $this->request->post['filter_customer_name'];
		} else {
			$filter_customer_name = '';
		}

		if (isset($this->request->post['filter_customer_email'])) {
			$filter_customer_email = $this->request->post['filter_customer_email'];
		} else {
			$filter_customer_email = '';
		}

		if (isset($this->request->post['filter_customer_telephone'])) {
			$filter_customer_telephone = $this->request->post['filter_customer_telephone'];
		} else {
			$filter_customer_telephone = '';
		}

		if (isset($this->request->post['filter_ip'])) {
			$filter_ip = $this->request->post['filter_ip'];
		} else {
			$filter_ip = '';
		}
		
		if (isset($this->request->post['filter_payment_company'])) {
			$filter_payment_company = $this->request->post['filter_payment_company'];
		} else {
			$filter_payment_company = '';
		}
		
		if (isset($this->request->post['filter_payment_address'])) {
			$filter_payment_address = $this->request->post['filter_payment_address'];
		} else {
			$filter_payment_address = '';
		}

		if (isset($this->request->post['filter_payment_city'])) {
			$filter_payment_city = $this->request->post['filter_payment_city'];
		} else {
			$filter_payment_city = '';
		}
		
		if (isset($this->request->post['filter_payment_zone'])) {
			$filter_payment_zone = $this->request->post['filter_payment_zone'];
		} else {
			$filter_payment_zone = '';
		}
		
		if (isset($this->request->post['filter_payment_postcode'])) {
			$filter_payment_postcode = $this->request->post['filter_payment_postcode'];
		} else {
			$filter_payment_postcode = '';
		}

		if (isset($this->request->post['filter_payment_country'])) {
			$filter_payment_country = $this->request->post['filter_payment_country'];
		} else {
			$filter_payment_country = '';
		}

		$this->data['payment_methods'] = $this->model_report_adv_sale_order->getOrderPaymentMethods();	
		if (isset($this->request->post['filter_payment_method']) && is_array($this->request->post['filter_payment_method'])) {
			$filter_payment_method = array_flip($this->request->post['filter_payment_method']);
		} else {
			$filter_payment_method = '';		
		}
		
		if (isset($this->request->post['filter_shipping_company'])) {
			$filter_shipping_company = $this->request->post['filter_shipping_company'];
		} else {
			$filter_shipping_company = '';
		}
		
		if (isset($this->request->post['filter_shipping_address'])) {
			$filter_shipping_address = $this->request->post['filter_shipping_address'];
		} else {
			$filter_shipping_address = '';
		}

		if (isset($this->request->post['filter_shipping_city'])) {
			$filter_shipping_city = $this->request->post['filter_shipping_city'];
		} else {
			$filter_shipping_city = '';
		}
		
		if (isset($this->request->post['filter_shipping_zone'])) {
			$filter_shipping_zone = $this->request->post['filter_shipping_zone'];
		} else {
			$filter_shipping_zone = '';
		}
		
		if (isset($this->request->post['filter_shipping_postcode'])) {
			$filter_shipping_postcode = $this->request->post['filter_shipping_postcode'];
		} else {
			$filter_shipping_postcode = '';
		}

		if (isset($this->request->post['filter_shipping_country'])) {
			$filter_shipping_country = $this->request->post['filter_shipping_country'];
		} else {
			$filter_shipping_country = '';
		}

		$this->data['shipping_methods'] = $this->model_report_adv_sale_order->getOrderShippingMethods();			
		if (isset($this->request->post['filter_shipping_method']) && is_array($this->request->post['filter_shipping_method'])) {
			$filter_shipping_method = array_flip($this->request->post['filter_shipping_method']);
		} else {
			$filter_shipping_method = '';		
		}
		
		$this->data['categories'] = $this->model_report_adv_sale_order->getProductsCategories(0);	
		if (isset($this->request->post['filter_category']) && is_array($this->request->post['filter_category'])) {
			$filter_category = array_flip($this->request->post['filter_category']);
		} else {
			$filter_category = '';
		}
		
		$this->data['manufacturers'] = $this->model_report_adv_sale_order->getProductsManufacturers(); 
		if (isset($this->request->post['filter_manufacturer']) && is_array($this->request->post['filter_manufacturer'])) {
			$filter_manufacturer = array_flip($this->request->post['filter_manufacturer']);
		} else {
			$filter_manufacturer = '';
		}
		
		if (isset($this->request->post['filter_sku'])) {
			$filter_sku = $this->request->post['filter_sku'];
		} else {
			$filter_sku = '';
		}

		if (isset($this->request->post['filter_product_id'])) {
			$filter_product_id = $this->request->post['filter_product_id'];
		} else {
			$filter_product_id = '';
		}
		
		if (isset($this->request->post['filter_model'])) {
			$filter_model = $this->request->post['filter_model'];
		} else {
			$filter_model = '';
		}

		$this->data['order_options'] = $this->model_report_adv_sale_order->getOrderOptions();
		if (isset($this->request->post['filter_option']) && is_array($this->request->post['filter_option'])) {
			$filter_option = array_flip($this->request->post['filter_option']);
		} else {
			$filter_option = '';
		}

		$this->data['attributes'] = $this->model_report_adv_sale_order->getProductAttributes();
		if (isset($this->request->post['filter_attribute']) && is_array($this->request->post['filter_attribute'])) {
			$filter_attribute = array_flip($this->request->post['filter_attribute']);
		} else {
			$filter_attribute = '';
		}
		
		$this->data['locations'] = $this->model_report_adv_sale_order->getProductLocations();			
		if (isset($this->request->post['filter_location']) && is_array($this->request->post['filter_location'])) {
			$filter_location = array_flip($this->request->post['filter_location']);
		} else {
			$filter_location = '';		
		}
		
		$this->data['affiliate_names'] = $this->model_report_adv_sale_order->getOrderAffiliates();
		if (isset($this->request->post['filter_affiliate_name']) && is_array($this->request->post['filter_affiliate_name'])) {
			$filter_affiliate_name = array_flip($this->request->post['filter_affiliate_name']);
		} else {
			$filter_affiliate_name = '';
		}

		$this->data['affiliate_emails'] = $this->model_report_adv_sale_order->getOrderAffiliates();
		if (isset($this->request->post['filter_affiliate_email']) && is_array($this->request->post['filter_affiliate_email'])) {
			$filter_affiliate_email = array_flip($this->request->post['filter_affiliate_email']);
		} else {
			$filter_affiliate_email = '';
		}

		$this->data['coupon_names'] = $this->model_report_adv_sale_order->getOrderCouponNames();
		if (isset($this->request->post['filter_coupon_name']) && is_array($this->request->post['filter_coupon_name'])) {
			$filter_coupon_name = array_flip($this->request->post['filter_coupon_name']);
		} else {
			$filter_coupon_name = '';
		}

		if (isset($this->request->post['filter_coupon_code'])) {
			$filter_coupon_code = $this->request->post['filter_coupon_code'];
		} else {
			$filter_coupon_code = '';
		}

		if (isset($this->request->post['filter_voucher_code'])) {
			$filter_voucher_code = $this->request->post['filter_voucher_code'];
		} else {
			$filter_voucher_code = '';
		}
		
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/adv_sale_order', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['orders'] = array();
		
		$data = array(
			'filter_date_start'	     		=> $filter_date_start, 
			'filter_date_end'	     		=> $filter_date_end, 
			'filter_range'           		=> $filter_range,
			'filter_report'           		=> $filter_report,
			'filter_group'           		=> $filter_group,
			'filter_status_date_start'		=> $filter_status_date_start, 
			'filter_status_date_end'		=> $filter_status_date_end, 			
			'filter_order_status_id'		=> $filter_order_status_id,
			'filter_order_id_from'			=> $filter_order_id_from,
			'filter_order_id_to'			=> $filter_order_id_to,			
			'filter_store_id'				=> $filter_store_id,
			'filter_currency'				=> $filter_currency,
			'filter_taxes'					=> $filter_taxes,
			'filter_tax_classes'			=> $filter_tax_classes,
			'filter_geo_zones'				=> $filter_geo_zones,			
			'filter_customer_group_id'		=> $filter_customer_group_id,
			'filter_customer_name'	 	 	=> $filter_customer_name,			
			'filter_customer_email'			=> $filter_customer_email,
			'filter_customer_telephone'		=> $filter_customer_telephone,
			'filter_ip' 	 				=> $filter_ip,			
			'filter_payment_company'		=> $filter_payment_company,
			'filter_payment_address'		=> $filter_payment_address,
			'filter_payment_city'			=> $filter_payment_city,
			'filter_payment_zone'			=> $filter_payment_zone,			
			'filter_payment_postcode'		=> $filter_payment_postcode,
			'filter_payment_country'		=> $filter_payment_country,
			'filter_payment_method'  		=> $filter_payment_method,
			'filter_shipping_company'		=> $filter_shipping_company,
			'filter_shipping_address'		=> $filter_shipping_address,
			'filter_shipping_city'			=> $filter_shipping_city,
			'filter_shipping_zone'			=> $filter_shipping_zone,			
			'filter_shipping_postcode'		=> $filter_shipping_postcode,
			'filter_shipping_country'		=> $filter_shipping_country,
			'filter_shipping_method'  		=> $filter_shipping_method,
			'filter_category'				=> $filter_category,
			'filter_manufacturer'			=> $filter_manufacturer,
			'filter_sku' 	 				=> $filter_sku,
			'filter_product_id'				=> $filter_product_id,
			'filter_model' 	 				=> $filter_model,
			'filter_option'  				=> $filter_option,
			'filter_attribute' 	 		 	=> $filter_attribute,
			'filter_location'  				=> $filter_location,
			'filter_affiliate_name'			=> $filter_affiliate_name,
			'filter_affiliate_email'		=> $filter_affiliate_email,
			'filter_coupon_name'			=> $filter_coupon_name,
			'filter_coupon_code'			=> $filter_coupon_code,
			'filter_voucher_code'			=> $filter_voucher_code,			
			'filter_sort'  					=> $filter_sort,
			'filter_details'  				=> $filter_details,
			'filter_limit'  				=> $filter_limit
		);

		if ($filter_details != 4) {
		$results = $this->model_report_adv_sale_order->getSaleOrders($data);

			foreach ($results as $result) {
				$this->data['orders'][] = array(
				'year'		       				=> $result['year'],
				'quarter'		       			=> 'Q' . $result['quarter'],	
				'year_quarter'		       		=> 'Q' . $result['quarter']. ' ' . $result['year'],
				'month'		       				=> $result['month'],
				'year_month'		       		=> substr($result['month'],0,3) . ' ' . $result['year'],			
				'date_start' 					=> date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   					=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),	
				'day_of_week'   				=> $result['day_of_week'],
				'hour'   						=> number_format($result['hour'], 2, ':', ''),
				'store_name'   					=> html_entity_decode($result['store_name']),
				'customer_group'   				=> html_entity_decode($result['customer_group']),
				'payment_country'   			=> $result['payment_country'],
				'payment_postcode'   			=> $result['payment_postcode'],
				'payment_zone'   				=> $result['payment_zone']. ', ' . $result['payment_country'],
				'payment_city'   				=> $result['payment_city']. ', ' . $result['payment_country'],
				'payment_method'   				=> preg_replace('~\(.*?\)~', '', $result['payment_method']),
				'shipping_method'   			=> preg_replace('~\(.*?\)~', '', $result['shipping_method']),				
				'order_id'   					=> $result['order_id'],	
				'orders'     					=> $result['orders'],
				'customers'   					=> $result['customers'],				
				'products'   					=> $result['products'],	
				'sub_total'        				=> $this->currency->format($result['sub_total'], $this->config->get('config_currency')),	
				'handling'        				=> $this->currency->format($result['handling'], $this->config->get('config_currency')),
				'low_order_fee'        			=> $this->currency->format($result['low_order_fee'], $this->config->get('config_currency')),
				'shipping'        				=> $this->currency->format($result['shipping'], $this->config->get('config_currency')),				
				'reward'      					=> $this->currency->format($result['reward'], $this->config->get('config_currency')),
				'coupon'      					=> $this->currency->format($result['coupon'], $this->config->get('config_currency')),
				'tax'        					=> $this->currency->format($result['tax'], $this->config->get('config_currency')),
				'credit'      					=> $this->currency->format($result['credit'], $this->config->get('config_currency')),
				'voucher'        				=> $this->currency->format($result['voucher'], $this->config->get('config_currency')),
				'commission'      				=> $this->currency->format('-' . ($result['commission']), $this->config->get('config_currency')),				
				'total'      					=> $this->currency->format($result['total'], $this->config->get('config_currency')),	
				'gtotal'   						=> round($result['total'], 2),
				'order_ord_id'     				=> $filter_details == 1 ? $result['order_ord_id'] : '',
				'order_ord_idc'     			=> $filter_details == 1 ? $result['order_ord_idc'] : '',					
				'order_order_date'    			=> $filter_details == 1 ? $result['order_order_date'] : '',
				'order_inv_no'     				=> $filter_details == 1 ? $result['order_inv_no'] : '',
				'order_name'   					=> $filter_details == 1 ? $result['order_name'] : '',
				'order_email'   				=> $filter_details == 1 ? $result['order_email'] : '',
				'order_group'   				=> $filter_details == 1 ? $result['order_group'] : '',
				'order_shipping_method' 		=> $filter_details == 1 ? strip_tags($result['order_shipping_method'], '<br>') : '',
				'order_payment_method'  		=> $filter_details == 1 ? strip_tags($result['order_payment_method'], '<br>') : '',
				'order_status'  				=> $filter_details == 1 ? $result['order_status'] : '',
				'order_store'      				=> $filter_details == 1 ? $result['order_store'] : '',	
				'order_currency' 				=> $filter_details == 1 ? $result['order_currency'] : '',				
				'order_products' 				=> $filter_details == 1 ? $result['order_products'] : '',
				'order_sub_total'  				=> $filter_details == 1 ? $result['order_sub_total'] : '',				
				'order_shipping'  				=> $filter_details == 1 ? $result['order_shipping'] : '',
				'order_tax'  					=> $filter_details == 1 ? $result['order_tax'] : '',					
				'order_value'  					=> $filter_details == 1 ? $result['order_value'] : '',			
				'product_ord_id'  				=> $filter_details == 2 ? $result['product_ord_id'] : '',
				'product_ord_idc'  				=> $filter_details == 2 ? $result['product_ord_idc'] : '',
				'product_order_date'    		=> $filter_details == 2 ? $result['product_order_date'] : '',
				'product_inv_no'     			=> $filter_details == 2 ? $result['product_inv_no'] : '',					
				'product_pid'  					=> $filter_details == 2 ? $result['product_pid'] : '',	
				'product_pidc'  				=> $filter_details == 2 ? $result['product_pidc'] : '',	
				'product_sku'  					=> $filter_details == 2 ? $result['product_sku'] : '',
				'product_model'  				=> $filter_details == 2 ? $result['product_model'] : '',				
				'product_name'  				=> $filter_details == 2 ? $result['product_name'] : '',	
				'product_option'  				=> $filter_details == 2 ? $result['product_option'] : '',					
				'product_attributes'  			=> $filter_details == 2 ? $result['product_attributes'] : '',
				'product_manu'  				=> $filter_details == 2 ? $result['product_manu'] : '',
				'product_category'  			=> $filter_details == 2 ? $result['product_category'] : '',				
				'product_currency'  			=> $filter_details == 2 ? $result['product_currency'] : '',
				'product_price'  				=> $filter_details == 2 ? $result['product_price'] : '',
				'product_quantity'  			=> $filter_details == 2 ? $result['product_quantity'] : '',
				'product_total_excl_vat'  		=> $filter_details == 2 ? $result['product_total_excl_vat'] : '',				
				'product_tax'  					=> $filter_details == 2 ? $result['product_tax'] : '',
				'product_total_incl_vat'  		=> $filter_details == 2 ? $result['product_total_incl_vat'] : '',
				'customer_ord_id' 				=> $filter_details == 3 ? $result['customer_ord_id'] : '',	
				'customer_order_date' 			=> $filter_details == 3 ? $result['customer_order_date'] : '',
				'customer_inv_no' 				=> $filter_details == 3 ? $result['customer_inv_no'] : '',
				'customer_cust_id' 				=> $filter_details == 3 ? $result['customer_cust_id'] : '',	
				'customer_cust_idc' 			=> $filter_details == 3 ? $result['customer_cust_idc'] : '',	
				'billing_name' 					=> $filter_details == 3 ? $result['billing_name'] : '',
				'billing_company' 				=> $filter_details == 3 ? $result['billing_company'] : '',
				'billing_address_1' 			=> $filter_details == 3 ? $result['billing_address_1'] : '',
				'billing_address_2' 			=> $filter_details == 3 ? $result['billing_address_2'] : '',
				'billing_city' 					=> $filter_details == 3 ? $result['billing_city'] : '',
				'billing_zone' 					=> $filter_details == 3 ? $result['billing_zone'] : '',
				'billing_postcode' 				=> $filter_details == 3 ? $result['billing_postcode'] : '',	
				'billing_country' 				=> $filter_details == 3 ? $result['billing_country'] : '',
				'customer_telephone' 			=> $filter_details == 3 ? $result['customer_telephone'] : '',
				'shipping_name' 				=> $filter_details == 3 ? $result['shipping_name'] : '',
				'shipping_company' 				=> $filter_details == 3 ? $result['shipping_company'] : '',
				'shipping_address_1' 			=> $filter_details == 3 ? $result['shipping_address_1'] : '',
				'shipping_address_2' 			=> $filter_details == 3 ? $result['shipping_address_2'] : '',
				'shipping_city' 				=> $filter_details == 3 ? $result['shipping_city'] : '',
				'shipping_zone' 				=> $filter_details == 3 ? $result['shipping_zone'] : '',
				'shipping_postcode' 			=> $filter_details == 3 ? $result['shipping_postcode'] : '',	
				'shipping_country' 				=> $filter_details == 3 ? $result['shipping_country'] : '',				
				'orders_total'      			=> $result['orders_total'],	
				'customers_total'      			=> $result['customers_total'],
				'products_total'      			=> $result['products_total'],				
				'sub_total_total'      			=> $this->currency->format($result['sub_total_total'], $this->config->get('config_currency')),
				'handling_total'      			=> $this->currency->format($result['handling_total'], $this->config->get('config_currency')),
				'low_order_fee_total'      		=> $this->currency->format($result['low_order_fee_total'], $this->config->get('config_currency')),
				'reward_total'      			=> $this->currency->format($result['reward_total'], $this->config->get('config_currency')),
				'shipping_total'      			=> $this->currency->format($result['shipping_total'], $this->config->get('config_currency')),
				'coupon_total'      			=> $this->currency->format($result['coupon_total'], $this->config->get('config_currency')),
				'tax_total'      				=> $this->currency->format($result['tax_total'], $this->config->get('config_currency')),
				'credit_total'      			=> $this->currency->format($result['credit_total'], $this->config->get('config_currency')),
				'voucher_total'      			=> $this->currency->format($result['voucher_total'], $this->config->get('config_currency')),
				'commission_total'      		=> $this->currency->format('-' . ($result['commission_total']), $this->config->get('config_currency')),					
				'total_total'      				=> $this->currency->format($result['total_total'], $this->config->get('config_currency'))
				);
			}

		} elseif ($filter_details == 4) {
			$this->load->model('report/adv_sale_order_export_all');
    		$results = $this->model_report_adv_sale_order_export_all->getSaleOrdersExportAll($data);
			
		foreach ($results as $result) {
			
			$this->data['orders'][] = array(
				'order_id'   					=> $result['order_id'],	
				'order_ord_id'     				=> $result['order_ord_id'],
				'order_ord_idc'     			=> $result['order_ord_idc'],					
				'order_order_date'    			=> $result['order_order_date'],
				'order_inv_no'     				=> $result['order_inv_no'],
				'order_name'   					=> $result['order_name'],
				'order_email'   				=> $result['order_email'],
				'order_group'   				=> $result['order_group'],
				'order_shipping_method' 		=> strip_tags($result['order_shipping_method'], '<br>'),
				'order_payment_method'  		=> strip_tags($result['order_payment_method'], '<br>'),
				'order_status'  				=> $result['order_status'],
				'order_store'      				=> $result['order_store'],	
				'order_currency' 				=> $result['order_currency'],				
				'order_products' 				=> $result['order_products'],
				'order_sub_total'  				=> $result['order_sub_total'],				
				'order_shipping'  				=> $result['order_shipping'],
				'order_tax'  					=> $result['order_tax'],					
				'order_value'  					=> $result['order_value'],		
				'product_pid'  					=> $result['product_pid'],	
				'product_pidc'  				=> $result['product_pidc'],	
				'product_sku'  					=> $result['product_sku'],
				'product_model'  				=> $result['product_model'],				
				'product_name'  				=> $result['product_name'],	
				'product_option'  				=> $result['product_option'],					
				'product_attributes'  			=> $result['product_attributes'],
				'product_manu'  				=> $result['product_manu'],
				'product_category'  			=> $result['product_category'],
				'product_price'  				=> $result['product_price'],
				'product_quantity'  			=> $result['product_quantity'],
				'product_total_excl_vat'  		=> $result['product_total_excl_vat'],				
				'product_tax'  					=> $result['product_tax'],
				'product_total_incl_vat'  		=> $result['product_total_incl_vat'],
				'customer_cust_id' 				=> $result['customer_cust_id'],	
				'customer_cust_idc' 			=> $result['customer_cust_idc'],	
				'billing_name' 					=> $result['billing_name'],
				'billing_company' 				=> $result['billing_company'],
				'billing_address_1' 			=> $result['billing_address_1'],
				'billing_address_2' 			=> $result['billing_address_2'],
				'billing_city' 					=> $result['billing_city'],
				'billing_zone' 					=> $result['billing_zone'],
				'billing_postcode' 				=> $result['billing_postcode'],	
				'billing_country' 				=> $result['billing_country'],
				'customer_telephone' 			=> $result['customer_telephone'],
				'shipping_name' 				=> $result['shipping_name'],
				'shipping_company' 				=> $result['shipping_company'],
				'shipping_address_1' 			=> $result['shipping_address_1'],
				'shipping_address_2' 			=> $result['shipping_address_2'],
				'shipping_city' 				=> $result['shipping_city'],
				'shipping_zone' 				=> $result['shipping_zone'],
				'shipping_postcode' 			=> $result['shipping_postcode'],	
				'shipping_country' 				=> $result['shipping_country']		
			);
		}
		
		}
		
		$this->data['text_no_details'] = $this->language->get('text_no_details');
		$this->data['text_order_list'] = $this->language->get('text_order_list');
		$this->data['text_product_list'] = $this->language->get('text_product_list');
		$this->data['text_customer_list'] = $this->language->get('text_customer_list');	
		$this->data['text_all_details'] = $this->language->get('text_all_details');			
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_all_status'] = $this->language->get('text_all_status');		
		$this->data['text_all_stores'] = $this->language->get('text_all_stores');
		$this->data['text_all_currencies'] = $this->language->get('text_all_currencies');
		$this->data['text_all_taxes'] = $this->language->get('text_all_taxes');	
		$this->data['text_all_tax_classes'] = $this->language->get('text_all_tax_classes');			
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');			
		$this->data['text_all_groups'] = $this->language->get('text_all_groups');
		$this->data['text_all_payment_methods'] = $this->language->get('text_all_payment_methods');	
		$this->data['text_all_shipping_methods'] = $this->language->get('text_all_shipping_methods');
		$this->data['text_all_categories'] = $this->language->get('text_all_categories');
		$this->data['text_all_manufacturers'] = $this->language->get('text_all_manufacturers');		
		$this->data['text_all_options'] = $this->language->get('text_all_options');
		$this->data['text_all_attributes'] = $this->language->get('text_all_attributes');
		$this->data['text_all_locations'] = $this->language->get('text_all_locations');	
		$this->data['text_all_affiliate_names'] = $this->language->get('text_all_affiliate_names');
		$this->data['text_all_affiliate_emails'] = $this->language->get('text_all_affiliate_emails');
		$this->data['text_all_coupon_names'] = $this->language->get('text_all_coupon_names');
		$this->data['text_none_selected'] = $this->language->get('text_none_selected');
		$this->data['text_selected'] = $this->language->get('text_selected');		
		$this->data['text_detail'] = $this->language->get('text_detail');
		$this->data['text_export_no_details'] = $this->language->get('text_export_no_details');
		$this->data['text_export_order_list'] = $this->language->get('text_export_order_list');
		$this->data['text_export_product_list'] = $this->language->get('text_export_product_list');	
		$this->data['text_export_customer_list'] = $this->language->get('text_export_customer_list');
		$this->data['text_export_all_details'] = $this->language->get('text_export_all_details');				
		$this->data['text_filter_total'] = $this->language->get('text_filter_total');	
		$this->data['text_filtering_options'] = $this->language->get('text_filtering_options');
		$this->data['text_column_settings'] = $this->language->get('text_column_settings');		
		$this->data['text_mv_columns'] = $this->language->get('text_mv_columns');		
		$this->data['text_ol_columns'] = $this->language->get('text_ol_columns');	
		$this->data['text_pl_columns'] = $this->language->get('text_pl_columns');	
		$this->data['text_cl_columns'] = $this->language->get('text_cl_columns');
		$this->data['text_all_columns'] = $this->language->get('text_all_columns');		
		$this->data['text_export_note'] = $this->language->get('text_export_note');
		$this->data['text_export_notice1'] = $this->language->get('text_export_notice1');
		$this->data['text_export_notice2'] = $this->language->get('text_export_notice2');		
		$this->data['text_export_limit'] = $this->language->get('text_export_limit');
		$this->data['text_pagin_page'] = $this->language->get('text_pagin_page');
		$this->data['text_pagin_of'] = $this->language->get('text_pagin_of');
		$this->data['text_pagin_results'] = $this->language->get('text_pagin_results');			
		
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
    	$this->data['column_orders'] = $this->language->get('column_orders');
    	$this->data['column_customers'] = $this->language->get('column_customers');		
		$this->data['column_products'] = $this->language->get('column_products');		
		$this->data['column_sub_total'] = $this->language->get('column_sub_total');
		$this->data['column_handling'] = $this->language->get('column_handling');	
		$this->data['column_loworder'] = $this->language->get('column_loworder');
		$this->data['column_shipping'] = $this->language->get('column_shipping');
		$this->data['column_reward'] = $this->language->get('column_reward');
		$this->data['column_coupon'] = $this->language->get('column_coupon');
		$this->data['column_coupon_code'] = $this->language->get('column_coupon_code');
		$this->data['column_tax'] = $this->language->get('column_tax');		
		$this->data['column_credit'] = $this->language->get('column_credit');	
		$this->data['column_voucher'] = $this->language->get('column_voucher');	
		$this->data['column_voucher_code'] = $this->language->get('column_voucher_code');
		$this->data['column_commission'] = $this->language->get('column_commission');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_order_date_added'] = $this->language->get('column_order_date_added');
		$this->data['column_order_order_id'] = $this->language->get('column_order_order_id');
		$this->data['column_order_inv_date'] = $this->language->get('column_order_inv_date');
		$this->data['column_order_inv_no'] = $this->language->get('column_order_inv_no');
		$this->data['column_order_customer_name'] = $this->language->get('column_order_customer_name');
		$this->data['column_order_customer'] = $this->language->get('column_order_customer');
		$this->data['column_order_email'] = $this->language->get('column_order_email');		
		$this->data['column_order_customer_group'] = $this->language->get('column_order_customer_group');		
		$this->data['column_order_shipping_method'] = $this->language->get('column_order_shipping_method');
		$this->data['column_order_payment_method'] = $this->language->get('column_order_payment_method');		
		$this->data['column_order_status'] = $this->language->get('column_order_status');
		$this->data['column_order_store'] = $this->language->get('column_order_store');
		$this->data['column_order_currency'] = $this->language->get('column_order_currency');		
		$this->data['column_order_quantity'] = $this->language->get('column_order_quantity');	
		$this->data['column_order_sub_total'] = $this->language->get('column_order_sub_total');	
		$this->data['column_order_shipping'] = $this->language->get('column_order_shipping');
		$this->data['column_order_tax'] = $this->language->get('column_order_tax');		
		$this->data['column_order_value'] = $this->language->get('column_order_value');	
		$this->data['column_prod_order_id'] = $this->language->get('column_prod_order_id');		
		$this->data['column_prod_date_added'] = $this->language->get('column_prod_date_added');	
		$this->data['column_prod_inv_no'] = $this->language->get('column_prod_inv_no');			
		$this->data['column_prod_id'] = $this->language->get('column_prod_id');
		$this->data['column_prod_sku'] = $this->language->get('column_prod_sku');		
		$this->data['column_prod_model'] = $this->language->get('column_prod_model');		
		$this->data['column_prod_name'] = $this->language->get('column_prod_name');	
		$this->data['column_prod_option'] = $this->language->get('column_prod_option');	
		$this->data['column_prod_attributes'] = $this->language->get('column_prod_attributes');			
		$this->data['column_prod_manu'] = $this->language->get('column_prod_manu');
		$this->data['column_prod_category'] = $this->language->get('column_prod_category');		
		$this->data['column_prod_currency'] = $this->language->get('column_prod_currency');
		$this->data['column_prod_price'] = $this->language->get('column_prod_price');
		$this->data['column_prod_quantity'] = $this->language->get('column_prod_quantity');
		$this->data['column_prod_total_excl_vat'] = $this->language->get('column_prod_total_excl_vat');
		$this->data['column_prod_tax'] = $this->language->get('column_prod_tax');
		$this->data['column_prod_total_incl_vat'] = $this->language->get('column_prod_total_incl_vat');	
		$this->data['column_customer_order_id'] = $this->language->get('column_customer_order_id');
		$this->data['column_customer_date_added'] = $this->language->get('column_customer_date_added');
		$this->data['column_customer_inv_no'] = $this->language->get('column_customer_inv_no');
		$this->data['column_customer_cust_id'] = $this->language->get('column_customer_cust_id');
		$this->data['column_billing_name'] = $this->language->get('column_billing_name');
		$this->data['column_billing_company'] = $this->language->get('column_billing_company');
		$this->data['column_billing_address_1'] = $this->language->get('column_billing_address_1');
		$this->data['column_billing_address_2'] = $this->language->get('column_billing_address_2');
		$this->data['column_billing_city'] = $this->language->get('column_billing_city');
		$this->data['column_billing_zone'] = $this->language->get('column_billing_zone');
		$this->data['column_billing_postcode'] = $this->language->get('column_billing_postcode');		
		$this->data['column_billing_country'] = $this->language->get('column_billing_country');
		$this->data['column_customer_telephone'] = $this->language->get('column_customer_telephone');
		$this->data['column_shipping_name'] = $this->language->get('column_shipping_name');
		$this->data['column_shipping_company'] = $this->language->get('column_shipping_company');
		$this->data['column_shipping_address_1'] = $this->language->get('column_shipping_address_1');
		$this->data['column_shipping_address_2'] = $this->language->get('column_shipping_address_2');
		$this->data['column_shipping_city'] = $this->language->get('column_shipping_city');
		$this->data['column_shipping_zone'] = $this->language->get('column_shipping_zone');
		$this->data['column_shipping_postcode'] = $this->language->get('column_shipping_postcode');		
		$this->data['column_shipping_country'] = $this->language->get('column_shipping_country');
		$this->data['column_order_comment'] = $this->language->get('column_order_comment');
		
		$this->data['column_year'] = $this->language->get('column_year');
		$this->data['column_quarter'] = $this->language->get('column_quarter');
		$this->data['column_month'] = $this->language->get('column_month');
		$this->data['column_day_of_week'] = $this->language->get('column_day_of_week');
		$this->data['column_hour'] = $this->language->get('column_hour');
		$this->data['column_store'] = $this->language->get('column_store');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_country'] = $this->language->get('column_country');
		$this->data['column_postcode'] = $this->language->get('column_postcode');
		$this->data['column_region_state'] = $this->language->get('column_region_state');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_payment_method'] = $this->language->get('column_payment_method');
		$this->data['column_shipping_method'] = $this->language->get('column_shipping_method');
			
		$this->data['entry_order_created'] = $this->language->get('entry_order_created');
		$this->data['entry_status_changed'] = $this->language->get('entry_status_changed');	
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_range'] = $this->language->get('entry_range');	
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_order_id'] = $this->language->get('entry_order_id');
		$this->data['entry_order_id_from'] = $this->language->get('entry_order_id_from');
		$this->data['entry_order_id_to'] = $this->language->get('entry_order_id_to');		
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_currency'] = $this->language->get('entry_currency');	
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_tax_classes'] = $this->language->get('entry_tax_classes');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');		
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_customer_name'] = $this->language->get('entry_customer_name');		
		$this->data['entry_customer_email'] = $this->language->get('entry_customer_email'); 
		$this->data['entry_customer_telephone'] = $this->language->get('entry_customer_telephone');
		$this->data['entry_ip'] = $this->language->get('entry_ip');
		$this->data['entry_payment_company'] = $this->language->get('entry_payment_company');
		$this->data['entry_payment_address'] = $this->language->get('entry_payment_address');
		$this->data['entry_payment_city'] = $this->language->get('entry_payment_city');
		$this->data['entry_payment_zone'] = $this->language->get('entry_payment_zone');		
		$this->data['entry_payment_postcode'] = $this->language->get('entry_payment_postcode');
		$this->data['entry_payment_country'] = $this->language->get('entry_payment_country');		
		$this->data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$this->data['entry_shipping_company'] = $this->language->get('entry_shipping_company');
		$this->data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$this->data['entry_shipping_city'] = $this->language->get('entry_shipping_city');
		$this->data['entry_shipping_zone'] = $this->language->get('entry_shipping_zone');		
		$this->data['entry_shipping_postcode'] = $this->language->get('entry_shipping_postcode');
		$this->data['entry_shipping_country'] = $this->language->get('entry_shipping_country');
		$this->data['entry_shipping_method'] = $this->language->get('entry_shipping_method');		
		$this->data['entry_category'] = $this->language->get('entry_category'); 
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_attributes'] = $this->language->get('entry_attributes');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_affiliate_name'] = $this->language->get('entry_affiliate_name');
		$this->data['entry_affiliate_email'] = $this->language->get('entry_affiliate_email');
		$this->data['entry_coupon_name'] = $this->language->get('entry_coupon_name');
		$this->data['entry_coupon_code'] = $this->language->get('entry_coupon_code');
		$this->data['entry_voucher_code'] = $this->language->get('entry_voucher_code');		

		$this->data['entry_report'] = $this->language->get('entry_report');
		$this->data['entry_group'] = $this->language->get('entry_group');		
		$this->data['entry_sort_by'] = $this->language->get('entry_sort_by');
		$this->data['entry_show_details'] = $this->language->get('entry_show_details');	
		$this->data['entry_limit'] = $this->language->get('entry_limit');	

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_chart'] = $this->language->get('button_chart');		
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_settings'] = $this->language->get('button_settings');
		$this->data['button_documentation'] = $this->language->get('button_documentation');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_version'] = $this->language->get('heading_version');		
		
		$this->data['token'] = $this->session->data['token'];
			
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_range'] = $filter_range;
		$this->data['filter_report'] = $filter_report;
		$this->data['filter_group'] = $filter_group;		
		$this->data['filter_sort'] = $filter_sort;	
		$this->data['filter_details'] = $filter_details;
		$this->data['filter_limit'] = $filter_limit;		
		$this->data['filter_status_date_start'] = $filter_status_date_start;
		$this->data['filter_status_date_end'] = $filter_status_date_end;
		$this->data['filter_order_status_id'] = $filter_order_status_id;		
		$this->data['filter_order_id_from'] = $filter_order_id_from;
		$this->data['filter_order_id_to'] = $filter_order_id_to;
		$this->data['filter_store_id'] = $filter_store_id;
		$this->data['filter_currency'] = $filter_currency;
		$this->data['filter_taxes'] = $filter_taxes;
		$this->data['filter_tax_classes'] = $filter_tax_classes;		
		$this->data['filter_geo_zones'] = $filter_geo_zones;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_customer_name'] = $filter_customer_name; 
		$this->data['filter_customer_email'] = $filter_customer_email; 		
		$this->data['filter_customer_telephone'] = $filter_customer_telephone;
		$this->data['filter_ip'] = $filter_ip;
		$this->data['filter_payment_company'] = $filter_payment_company; 
		$this->data['filter_payment_address'] = $filter_payment_address; 
		$this->data['filter_payment_city'] = $filter_payment_city; 
		$this->data['filter_payment_postcode'] = $filter_payment_postcode; 
		$this->data['filter_payment_zone'] = $filter_payment_zone; 
		$this->data['filter_payment_country'] = $filter_payment_country; 
		$this->data['filter_payment_method'] = $filter_payment_method; 		
		$this->data['filter_shipping_company'] = $filter_shipping_company; 
		$this->data['filter_shipping_address'] = $filter_shipping_address; 
		$this->data['filter_shipping_city'] = $filter_shipping_city; 
		$this->data['filter_shipping_postcode'] = $filter_shipping_postcode; 
		$this->data['filter_shipping_zone'] = $filter_shipping_zone; 
		$this->data['filter_shipping_country'] = $filter_shipping_country; 
		$this->data['filter_shipping_method'] = $filter_shipping_method; 
		$this->data['filter_manufacturer'] = $filter_manufacturer; 
		$this->data['filter_category'] = $filter_category; 
		$this->data['filter_sku'] = $filter_sku; 
		$this->data['filter_product_id'] = $filter_product_id; 
		$this->data['filter_model'] = $filter_model; 
		$this->data['filter_option'] = $filter_option;
		$this->data['filter_attribute'] = $filter_attribute;
		$this->data['filter_location'] = $filter_location;
		$this->data['filter_affiliate_name'] = $filter_affiliate_name; 
		$this->data['filter_affiliate_email'] = $filter_affiliate_email; 
		$this->data['filter_coupon_name'] = $filter_coupon_name; 
		$this->data['filter_coupon_code'] = $filter_coupon_code; 
		$this->data['filter_voucher_code'] = $filter_voucher_code;
		
		$this->template = 'report/adv_sale_order.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());

    	if (isset($this->request->post['export']) && $this->request->post['export'] == 1) { // export_xls
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);		
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xls.inc.php');

		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 2) { // export_xls_order_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xls_order_list.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 3) { // export_xls_product_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xls_product_list.inc.php');

		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 4) { // export_xls_customer_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xls_customer_list.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 5) { // export_xls_all_details
			$this->load->model('report/adv_sale_order_export_all');
    		$rows = $this->model_report_adv_sale_order_export_all->getSaleOrdersExportAllExcel($data);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xls_all_details.inc.php');
				
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 6) { // export_html
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_html.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 7) { // export_html_order_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_html_order_list.inc.php');
				
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 8) { // export_html_product_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_html_product_list.inc.php');
							
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 9) { // export_html_customer_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_html_customer_list.inc.php');

		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 10) { // export_html_all_details
			$this->load->model('report/adv_sale_order_export_all');
    		$results = $this->model_report_adv_sale_order_export_all->getSaleOrdersExportAll($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_html_all_details.inc.php');

		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 11) { // export_pdf
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_pdf.inc.php');
		
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 12) { // export_pdf_order_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_pdf_order_list.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 13) { // export_pdf_product_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_pdf_product_list.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 14) { // export_pdf_customer_list
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_pdf_customer_list.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 15) { // export_pdf_all_details
			$this->load->model('report/adv_sale_order_export_all');
    		$results = $this->model_report_adv_sale_order_export_all->getSaleOrdersExportAll($data);
			require_once(DIR_SYSTEM . 'library/dompdf/dompdf_config.inc.php');
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_pdf_all_details.inc.php');			
		
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 16) { // export_csv
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_csv.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 17) { // export_csv_all_details
			$this->load->model('report/adv_sale_order_export_all');
    		$rows = $this->model_report_adv_sale_order_export_all->getSaleOrdersExportAllExcel($data);		
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_csv_all_details.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 18) { // export_xlsx
			$this->load->model('report/adv_sale_order_export');
    		$results = $this->model_report_adv_sale_order_export->getSaleOrdersExport($data);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xlsx.inc.php');
			
		} elseif (isset($this->request->post['export']) && $this->request->post['export'] == 19) { // export_xlsx_all_details
			$this->load->model('report/adv_sale_order_export_all');
    		$rows = $this->model_report_adv_sale_order_export_all->getSaleOrdersExportAllExcel($data);			
			include(DIR_APPLICATION . 'controller/report/adv_reports/so_export_xlsx_all_details.inc.php');
		}	
	}
	
	public function customer_autocomplete() {
		$json = array();

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_customer_name']) or isset($this->request->get['filter_customer_email']) or isset($this->request->get['filter_customer_telephone']) or isset($this->request->get['filter_ip']) or isset($this->request->get['filter_payment_company']) or isset($this->request->get['filter_payment_address']) or isset($this->request->get['filter_payment_city']) or isset($this->request->get['filter_payment_zone']) or isset($this->request->get['filter_payment_postcode']) or isset($this->request->get['filter_payment_country']) or isset($this->request->get['filter_shipping_company']) or isset($this->request->get['filter_shipping_address']) or isset($this->request->get['filter_shipping_city']) or isset($this->request->get['filter_shipping_zone']) or isset($this->request->get['filter_shipping_postcode']) or isset($this->request->get['filter_shipping_country'])) {
			
		$this->load->model('report/adv_sale_order');
		
		if (isset($this->request->get['filter_customer_name'])) {
			$filter_customer_name = $this->request->get['filter_customer_name'];
		} else {
			$filter_customer_name = '';
		}

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = '';
		}	

		if (isset($this->request->get['filter_customer_telephone'])) {
			$filter_customer_telephone = $this->request->get['filter_customer_telephone'];
		} else {
			$filter_customer_telephone = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}
		
		if (isset($this->request->get['filter_payment_company'])) {
			$filter_payment_company = $this->request->get['filter_payment_company'];
		} else {
			$filter_payment_company = '';
		}
		
		if (isset($this->request->get['filter_payment_address'])) {
			$filter_payment_address = $this->request->get['filter_payment_address'];
		} else {
			$filter_payment_address = '';
		}

		if (isset($this->request->get['filter_payment_city'])) {
			$filter_payment_city = $this->request->get['filter_payment_city'];
		} else {
			$filter_payment_city = '';
		}
		
		if (isset($this->request->get['filter_payment_zone'])) {
			$filter_payment_zone = $this->request->get['filter_payment_zone'];
		} else {
			$filter_payment_zone = '';
		}
		
		if (isset($this->request->get['filter_payment_postcode'])) {
			$filter_payment_postcode = $this->request->get['filter_payment_postcode'];
		} else {
			$filter_payment_postcode = '';
		}

		if (isset($this->request->get['filter_payment_country'])) {
			$filter_payment_country = $this->request->get['filter_payment_country'];
		} else {
			$filter_payment_country = '';
		}
		
		if (isset($this->request->get['filter_shipping_company'])) {
			$filter_shipping_company = $this->request->get['filter_shipping_company'];
		} else {
			$filter_shipping_company = '';
		}
		
		if (isset($this->request->get['filter_shipping_address'])) {
			$filter_shipping_address = $this->request->get['filter_shipping_address'];
		} else {
			$filter_shipping_address = '';
		}

		if (isset($this->request->get['filter_shipping_city'])) {
			$filter_shipping_city = $this->request->get['filter_shipping_city'];
		} else {
			$filter_shipping_city = '';
		}
		
		if (isset($this->request->get['filter_shipping_zone'])) {
			$filter_shipping_zone = $this->request->get['filter_shipping_zone'];
		} else {
			$filter_shipping_zone = '';
		}
		
		if (isset($this->request->get['filter_shipping_postcode'])) {
			$filter_shipping_postcode = $this->request->get['filter_shipping_postcode'];
		} else {
			$filter_shipping_postcode = '';
		}

		if (isset($this->request->get['filter_shipping_country'])) {
			$filter_shipping_country = $this->request->get['filter_shipping_country'];
		} else {
			$filter_shipping_country = '';
		}
		
		$data = array(		
			'filter_customer_name' 	 		=> $filter_customer_name,
			'filter_customer_email' 	 	=> $filter_customer_email,			
			'filter_customer_telephone' 	=> $filter_customer_telephone,
			'filter_ip' 					=> $filter_ip,			
			'filter_payment_company' 		=> $filter_payment_company,
			'filter_payment_address' 		=> $filter_payment_address,
			'filter_payment_city' 			=> $filter_payment_city,
			'filter_payment_zone' 			=> $filter_payment_zone,			
			'filter_payment_postcode' 		=> $filter_payment_postcode,
			'filter_payment_country' 		=> $filter_payment_country,			
			'filter_shipping_company' 		=> $filter_shipping_company,
			'filter_shipping_address' 		=> $filter_shipping_address,
			'filter_shipping_city' 			=> $filter_shipping_city,
			'filter_shipping_zone' 			=> $filter_shipping_zone,			
			'filter_shipping_postcode' 		=> $filter_shipping_postcode,
			'filter_shipping_country' 		=> $filter_shipping_country
		);
						
		$results = $this->model_report_adv_sale_order->getCustomerAutocomplete($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'     		=> $result['customer_id'],				
					'cust_name'     		=> html_entity_decode($result['cust_name'], ENT_QUOTES, 'UTF-8'),
					'cust_email'     		=> $result['cust_email'],
					'cust_telephone'     	=> $result['cust_telephone'],
					'cust_ip'     			=> $result['cust_ip'],
					'payment_company'     	=> html_entity_decode($result['payment_company'], ENT_QUOTES, 'UTF-8'),	
					'payment_address'     	=> html_entity_decode($result['payment_address'], ENT_QUOTES, 'UTF-8'),	
					'payment_city'     		=> html_entity_decode($result['payment_city'], ENT_QUOTES, 'UTF-8'),	
					'payment_zone'     		=> html_entity_decode($result['payment_zone'], ENT_QUOTES, 'UTF-8'),						
					'payment_postcode'     	=> $result['payment_postcode'],
					'payment_country'     	=> html_entity_decode($result['payment_country'], ENT_QUOTES, 'UTF-8'),					
					'shipping_company'     	=> html_entity_decode($result['shipping_company'], ENT_QUOTES, 'UTF-8'),	
					'shipping_address'     	=> html_entity_decode($result['shipping_address'], ENT_QUOTES, 'UTF-8'),
					'shipping_city'     	=> html_entity_decode($result['shipping_city'], ENT_QUOTES, 'UTF-8'),
					'shipping_zone'     	=> html_entity_decode($result['shipping_zone'], ENT_QUOTES, 'UTF-8'),					
					'shipping_postcode'     => $result['shipping_postcode'],
					'shipping_country'     	=> html_entity_decode($result['shipping_country'], ENT_QUOTES, 'UTF-8')			
				);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function product_autocomplete() {
		$json = array();

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_sku']) or isset($this->request->get['filter_product_id']) or isset($this->request->get['filter_model'])) {
		
		$this->load->model('report/adv_sale_order');
					
		if (isset($this->request->get['filter_sku'])) {
			$filter_sku = $this->request->get['filter_sku'];
		} else {
			$filter_sku = '';
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}
		
		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}
		
		$data = array(				
			'filter_sku' 	 			=> $filter_sku,
			'filter_product_id' 	 	=> $filter_product_id,
			'filter_model' 	 			=> $filter_model	
		);
						
		$results = $this->model_report_adv_sale_order->getProductAutocomplete($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'product_id'     		=> $result['product_id'],
					'prod_sku'     			=> html_entity_decode($result['prod_sku'], ENT_QUOTES, 'UTF-8'),					
					'prod_name'     		=> html_entity_decode($result['prod_name'], ENT_QUOTES, 'UTF-8'),
					'prod_model'     		=> html_entity_decode($result['prod_model'], ENT_QUOTES, 'UTF-8')				
				);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}

	public function coupon_autocomplete() {
		$json = array();

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_coupon_code'])) {
			
		$this->load->model('report/adv_sale_order');

		if (isset($this->request->get['filter_coupon_code'])) {
			$filter_coupon_code = $this->request->get['filter_coupon_code'];
		} else {
			$filter_coupon_code = '';
		}
		
		$data = array(		
			'filter_coupon_code' 	 		=> $filter_coupon_code			
		);
						
		$results = $this->model_report_adv_sale_order->getCouponAutocomplete($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'coupon_id'     		=> $result['coupon_id'],
					'coupon_code'     		=> html_entity_decode($result['coupon_code'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}

	public function voucher_autocomplete() {
		$json = array();

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['filter_voucher_code'])) {
			
		$this->load->model('report/adv_sale_order');

		if (isset($this->request->get['filter_voucher_code'])) {
			$filter_voucher_code = $this->request->get['filter_voucher_code'];
		} else {
			$filter_voucher_code = '';
		}
		
		$data = array(		
			'filter_voucher_code' 	 		=> $filter_voucher_code			
		);
						
		$results = $this->model_report_adv_sale_order->getVoucherAutocomplete($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'voucher_id'     		=> $result['voucher_id'],
					'voucher_code'     		=> html_entity_decode($result['voucher_code'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function IsInstalled() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE code = 'adv_reports'");
		if (empty($query->num_rows)) {
			return false;
		}
		return true;
	}
	
	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}	
}
?>
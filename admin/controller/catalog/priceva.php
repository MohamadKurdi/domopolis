<?php
class ControllerCatalogPriceva extends Controller {
	private $modelCatalogProduct;


	public function index() {
		$this->language->load('catalog/product');

		$this->load->model('kp/yandex');
		$this->load->model('kp/priceva');
		$this->load->model('catalog/product');
		$this->load->model('catalog/group_price');
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->model('tool/image');			
		$this->load->model('catalog/manufacturer');
		$this->load->model('localisation/currency');

		$this->document->setTitle('Мониторинг цен конкурентов'); 

			//Это блядь лайфхак от бога, просто			
		loadAndRenameCatalogModels('model/catalog/product.php', 'ModelCatalogProduct', 'ModelCatalogProductCatalog');
		$this->modelCatalogProduct = new ModelCatalogProductCatalog($this->registry);



		$this->getList();
	}

	public function setAsIlliqud(){




	}

	public function reloadFrontPrice(){




	}

	public function updateProductFieldAjax(){

		if (!$this->user->hasPermission('modify', 'catalog/priceva')) {
				die('no_permission');
			}
			
			if (!$this->user->hasPermission('modify', 'catalog/priceva')) {
				die('no_permission');
			}
			
			
			$data = $this->request->post;
			
			
			
			if (isset($data['product_id']) && isset($data['field']) && isset($data['value'])){



			}


	}


	protected function getList() {				

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = 0;
		}

		if (isset($this->request->get['filter_yam_hidden'])) {
			$filter_yam_hidden = $this->request->get['filter_yam_hidden'];
		} else {
			$filter_yam_hidden = null;
		}

		if (isset($this->request->get['filter_is_illiquid'])) {
			$filter_is_illiquid = $this->request->get['filter_is_illiquid'];
		} else {
			$filter_is_illiquid = null;
		}

		if (isset($this->request->get['filter_competitors'])) {
			$filter_competitors = $this->request->get['filter_competitors'];
		} else {
			$filter_competitors = null;
		}

		if (isset($this->request->get['filter_show_without_links'])) {
			$filter_show_without_links = $this->request->get['filter_show_without_links'];
		} else {
			$filter_show_without_links = false;
		}

		if (isset($this->request->get['filter_links_only_selected'])) {
			$filter_links_only_selected = $this->request->get['filter_links_only_selected'];
		} else {
			$filter_links_only_selected = null;
		}

		if (isset($this->request->get['filter_competitor_stock'])) {
			$filter_competitor_stock = $this->request->get['filter_competitor_stock'];
		} else {
			$filter_competitor_stock = null;
		}

		if (isset($this->request->get['filter_competitor_not_stock'])) {
			$filter_competitor_not_stock = $this->request->get['filter_competitor_not_stock'];
		} else {
			$filter_competitor_not_stock = null;
		}

		if (isset($this->request->get['filter_competitor_stock_all'])) {
			$filter_competitor_stock_all = $this->request->get['filter_competitor_stock_all'];
		} else {
			$filter_competitor_stock_all = null;
		}

		if (isset($this->request->get['filter_competitor_not_stock_all'])) {
			$filter_competitor_not_stock_all = $this->request->get['filter_competitor_not_stock_all'];
		} else {
			$filter_competitor_not_stock_all = null;
		}

		if (isset($this->request->get['filter_kitchenprofi_stock'])) {
			$filter_kitchenprofi_stock = $this->request->get['filter_kitchenprofi_stock'];
		} else {
			$filter_kitchenprofi_stock = null;
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stock'])) {
			$filter_kitchenprofi_not_stock = $this->request->get['filter_kitchenprofi_not_stock'];
		} else {
			$filter_kitchenprofi_not_stock = null;
		}

		if (isset($this->request->get['filter_kitchenprofi_stockwait'])) {
			$filter_kitchenprofi_stockwait = $this->request->get['filter_kitchenprofi_stockwait'];
		} else {
			$filter_kitchenprofi_stockwait = null;
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stockwait'])) {
			$filter_kitchenprofi_not_stockwait = $this->request->get['filter_kitchenprofi_not_stockwait'];
		} else {
			$filter_kitchenprofi_not_stockwait = null;
		}

		if (isset($this->request->get['filter_dates_periods'])) {
			$filter_dates_periods = $this->request->get['filter_dates_periods'];
		} else {
			$filter_dates_periods = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (!empty($this->request->get['filter_priceva_category'])) {
			$filter_priceva_category = $this->request->get['filter_priceva_category'];
		} else {
			$filter_priceva_category = null;
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $filter_store_id;
		}	

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}	

		if (isset($this->request->get['filter_priceva_category'])) {
			$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
		}

		if (isset($this->request->get['filter_yam_hidden'])) {
			$url .= '&filter_yam_hidden=' . $this->request->get['filter_yam_hidden'];
		}

		if (isset($this->request->get['filter_is_illiquid'])) {
			$url .= '&filter_is_illiquid=' . $this->request->get['filter_is_illiquid'];
		}	

		if (isset($this->request->get['filter_competitors'])) {
			$url .= '&filter_competitors=' . $this->request->get['filter_competitors'];
		}

		if (isset($this->request->get['filter_show_without_links'])) {
			$url .= '&filter_show_without_links=' . $this->request->get['filter_show_without_links'];
		}	

		if (isset($this->request->get['filter_links_only_selected'])) {
			$url .= '&filter_links_only_selected=' . $this->request->get['filter_links_only_selected'];
		}	

		if (isset($this->request->get['filter_competitor_stock'])) {
			$url .= '&filter_competitor_stock=' . $this->request->get['filter_competitor_stock'];
		}

		if (isset($this->request->get['filter_competitor_not_stock'])) {
			$url .= '&filter_competitor_not_stock=' . $this->request->get['filter_competitor_not_stock'];
		}

		if (isset($this->request->get['filter_competitor_stock_all'])) {
			$url .= '&filter_competitor_stock_all=' . $this->request->get['filter_competitor_stock_all'];
		}

		if (isset($this->request->get['filter_competitor_not_stock_all'])) {
			$url .= '&filter_competitor_not_stock_all=' . $this->request->get['filter_competitor_not_stock_all'];
		}

		if (isset($this->request->get['filter_kitchenprofi_stock'])) {
			$url .= '&filter_kitchenprofi_stock=' . $this->request->get['filter_kitchenprofi_stock'];
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stock'])) {
			$url .= '&filter_kitchenprofi_not_stock=' . $this->request->get['filter_kitchenprofi_not_stock'];
		}

		if (isset($this->request->get['filter_kitchenprofi_stockwait'])) {
			$url .= '&filter_kitchenprofi_stockwait=' . $this->request->get['filter_kitchenprofi_stockwait'];
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stockwait'])) {
			$url .= '&filter_kitchenprofi_not_stockwait=' . $this->request->get['filter_kitchenprofi_not_stockwait'];
		}

		if (isset($this->request->get['filter_dates_periods'])) {
			$url .= '&filter_dates_periods=' . $this->request->get['filter_dates_periods'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['products'] = array();

		$defaultCurrency = $this->data['defaultCurrency'] = $this->model_setting_setting->getKeySettingValue('config', 'config_currency', (int)$filter_store_id);
		$catalogCurrency = $this->data['catalogCurrency'] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$filter_store_id);
		$catalogStockField = $this->data['catalogStockField'] = $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', (int)$filter_store_id);

		//Currency signs
		$this->data['defaultCurrencySign'] = $this->model_localisation_currency->getCurrencySignByCode($defaultCurrency);
		$this->data['catalogCurrencySign'] = $this->model_localisation_currency->getCurrencySignByCode($catalogCurrency);

		$filter_data = array(
			'filter_name'	  			=> $filter_name, 
			'filter_store_id'	  		=> $filter_store_id, 
			'filter_is_illiquid'   		=> $filter_is_illiquid,
			'filter_competitors'   		=> $filter_competitors,
			'filter_show_without_links' => $filter_show_without_links,
			'filter_yam_hidden'   		=> $filter_yam_hidden,
			'filter_links_only_selected'		=> $filter_links_only_selected,
			'filter_competitor_stock'			=> $filter_competitor_stock,
			'filter_competitor_not_stock'		=> $filter_competitor_not_stock,
			'filter_competitor_stock_all'		=> $filter_competitor_stock_all,
			'filter_competitor_not_stock_all'	=> $filter_competitor_not_stock_all,
			'filter_kitchenprofi_stock'			=> $filter_kitchenprofi_stock,
			'filter_kitchenprofi_not_stock'		=> $filter_kitchenprofi_not_stock,
			'filter_kitchenprofi_stockwait'			=> $filter_kitchenprofi_stockwait,
			'filter_kitchenprofi_not_stockwait'		=> $filter_kitchenprofi_not_stockwait,
			'filter_dates_periods'					=> $filter_dates_periods,
			'filter_current_stock_field' 			=> $catalogStockField,
			'filter_manufacturer_id'   	=> $filter_manufacturer_id,
			'filter_priceva_category'   => $filter_priceva_category,
			'filter_status'   		 	=> true,			
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true,
			'sort'            		 	=> $sort,
			'order'           			=> $order,
			'start'           			=> ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           			=> $this->config->get('config_admin_limit')
		);

			//DATA
		$this->data['priceva_categories'] 	= $this->model_kp_priceva->getPricevaCategories(['filter_priceva_store_id' => $filter_store_id]);
		$this->data['manufacturers'] 		= $this->model_catalog_manufacturer->getManufacturers(['filter_priceva' => true, 'filter_priceva_store_id' => $filter_store_id]);

		$competitor_filter = [
			'filter_manufacturer_id'   	=> $filter_manufacturer_id,
			'filter_priceva_category'   => $filter_priceva_category,
			'filter_store_id'	  		=> $filter_store_id
		];
		$this->data['competitors'] 			= $this->model_kp_priceva->getPricevaCompetitors($competitor_filter);

		$this->data['stores'] = [];
		$stores = $this->model_setting_store->getStores();
		foreach ($stores as $store){

			if ($this->config->get('config_priceva_api_key_' . $store['store_id'])){
				$this->data['stores'][] = $store;					
			}

		}

		$product_total = $this->model_kp_priceva->getTotalProducts($filter_data);
		$results = $this->model_kp_priceva->getProducts($filter_data);
		

		foreach ($results as $result) {
			$action = array();

			$image = $this->model_tool_image->resize($result['image'], 60, 60);
			$product_info = $this->modelCatalogProduct->getUncachedProductForStore($result['product_id'], $filter_store_id);
			$product_info_admin = $this->model_catalog_product->getProduct($result['product_id'], false);

			$price_admin_in_national 			 = $this->currency->format_with_left($product_info['price'], $catalogCurrency);
			$price_national_to_store_unformatted = $this->model_catalog_product->getProductStorePriceNational($result['product_id'], $filter_store_id);

			$price = $final_price = $this->currency->format_with_left($product_info['price'], $catalogCurrency);
			$price_in_eur = $this->currency->format_with_left($product_info['price'], $defaultCurrency, 1);

			$special = false;
			if ((float)$product_info['special']) {
				$special = $final_price = $this->currency->format_with_left($product_info['special'], $catalogCurrency);
				$price_in_eur = $this->currency->format_with_left($product_info['special'], $defaultCurrency, 1);
			}

			$price_to_store_unformatted = $this->model_catalog_product->getProductStorePrice($result['product_id'], $filter_store_id);

			$problem = false;

			//А вот тут мы играемся с себестоимостью	
			$costs = $this->model_kp_product->getProductCostsForStore($result['product_id'], $filter_store_id);
			$cost = false;
			$cost_in_eur	= false;
			$cost_formatted = false;	
			$cost_percent_diff = false;

			if (!empty($costs['cost'])){
				$cost_in_eur = $this->currency->format_with_left($costs['cost'], 'EUR', 1);
				$cost = $this->currency->real_convert($costs['cost'], 'EUR', $catalogCurrency, true);

				if ($cost > 0) {
					$cost_formatted = $this->currency->format_with_left($cost, $catalogCurrency, 1);					
				}
			}

			$min_sale_price = false;
			$min_sale_price_formatted = false;
			$min_sale_price_percent_diff = false;
			$min_sale_price_in_eur	= false;

			if (!empty($costs['min_sale_price'])){
				$min_sale_price_in_eur = $this->currency->format_with_left($costs['min_sale_price'], 'EUR', 1);
				$min_sale_price = $this->currency->real_convert($costs['min_sale_price'], 'EUR', $catalogCurrency, true);

				if ($min_sale_price > 0) {
					$min_sale_price_formatted = $this->currency->format_with_left($min_sale_price, $catalogCurrency, 1);						
				}
			}

			$competitors_tmp_data = $this->pricevaAdaptor->getPricevaData($filter_store_id, $result['product_id']);
			$competitors_data 	= [];
			$competitors_stock 	= 0;
			foreach ($competitors_tmp_data as $competitors_tmp_data_line){

				$competitors_stock += $competitors_tmp_data_line['in_stock'];

				$final_competitor_price = false;
				if ($competitors_tmp_data_line['price']){
					$final_competitor_price = (float)$competitors_tmp_data_line['price'];					
				}

				if ($competitors_tmp_data_line['discount']){
					$final_competitor_price = (float)$competitors_tmp_data_line['discount'];	
				}

				$competitor_price 		= false;
				$competitor_discount 	= false;
				if ($competitors_tmp_data_line['price'] && (float)$competitors_tmp_data_line['price'] > 0){
					$competitor_price = (float)$competitors_tmp_data_line['price'];					
				}

				if ($competitors_tmp_data_line['discount'] && (float)$competitors_tmp_data_line['discount'] > 0){
					$competitor_discount = (float)$competitors_tmp_data_line['discount'];					
				}

				$competitors_data[] = [
					'company_name' 				=> $competitors_tmp_data_line['company_name'],
					'url' 						=> $competitors_tmp_data_line['url'],
					'price'		 				=> $competitor_price?$this->currency->format_with_left($competitor_price, $catalogCurrency, 1):false,
					'special'					=> $competitor_discount?$this->currency->format_with_left($competitor_discount, $catalogCurrency, 1):false,
					'in_stock'					=> $competitors_tmp_data_line['in_stock'],
					'final_competitor_price' 	=> $final_competitor_price,
					'last_check_date' 			=> date('Y-m-d', strtotime($competitors_tmp_data_line['last_check_date'])),
					'relevance_status'			=> $this->pricevaAdaptor->getPricevaRelevance()[$competitors_tmp_data_line['relevance_status']]
				];

			}

			if ($filter_dates_periods){
				$bf_month 		= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], '30day', $filter_store_id);
				$bf_3month 		= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], '90day', $filter_store_id);
				$bf_halfyear 	= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], '180day', $filter_store_id);
				$bf_year 		= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], '365day', $filter_store_id);

			} else {
				$bf_month 		= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], 'month', $filter_store_id);
				$bf_3month 		= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], '3month', $filter_store_id);
				$bf_halfyear 	= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], 'halfyear', $filter_store_id);
				$bf_year 		= $this->model_kp_priceva->getCountProductsInOrders($result['product_id'], 'year', $filter_store_id);
			}

			$quantity_stockwait =  $this->model_kp_priceva->getProductStockWaits($result['product_id'], $catalogStockField);


			//Проблемы
			$problem 				= false;
			$problem_sales 			= false;
			$problem_stock 			= false;

			$warning 				= false;
			$warning_small_sales 	= false;

			if ($bf_month + $bf_3month + $bf_halfyear + $bf_year == 0){
				$problem 		= true;
				$problem_sales 	= true;
			}

			if ($competitors_stock && !$result[$catalogStockField] && !$quantity_stockwait){
				$problem 		= true;
				$problem_stock  = true;
			}


			if ($bf_year <= 10){
				$warning 				= true;
				$warning_small_sales 	= true;
			}

			$product = array(
				'product_id' 						=> $result['product_id'],
				'competitors_data'					=> $competitors_data,

				'problem'							=> $problem,
				'problem_sales'						=> $problem_sales,
				'problem_stock'						=> $problem_stock,

				'warning'							=> $warning,
				'warning_small_sales'				=> $warning_small_sales,	

				'bf_month'							=> $bf_month,
				'bf_3month'							=> $bf_3month,
				'bf_halfyear'						=> $bf_halfyear,
				'bf_year'							=> $bf_year,

				'min_sale_price_formatted'			=> $min_sale_price_formatted,
				'min_sale_price_in_eur'				=> $min_sale_price_in_eur,
				
				'cost_formatted'					=> $cost_formatted,
				'cost_in_eur'						=> $cost_in_eur,

				'name'       						=> $product_info['name'],
				'priceva_category_name' 			=> $result['category_name'],
				'model'      						=> $result['model'],
				'ean'      	 						=> $result['ean'],
				'asin'      	 					=> $result['asin'],
				
				'manufacturer'						=> $product_info['manufacturer'],

				'is_illiquid' 						=> $result['is_illiquid'],
				
				'price'      						=> $price,
				'final_price'						=> $final_price,
				'price_in_eur'						=> $price_in_eur,
				'special'      						=> $special,	

				'price_admin_unformatted'				=> $product_info_admin['price'],														
				'price_to_store_unformatted'			=> $price_to_store_unformatted,
				'price_national_to_store_unformatted'	=> $price_national_to_store_unformatted,

				'price_admin_in_national'						=> $this->currency->format_with_left($product_info_admin['price'], $catalogCurrency),
				'price_admin_to_store_in_national'				=> $this->currency->format_with_left($price_to_store_unformatted, $catalogCurrency),
				'price_admin_national_to_store_in_eur'			=> $this->currency->format_with_left($this->currency->convert($price_national_to_store_unformatted, $catalogCurrency, $defaultCurrency), $defaultCurrency, 1),

				
				'image'      						=> $image,

				'quantity_stock'   					=> $result[$catalogStockField],
				'quantity_stockwait'   				=> $quantity_stockwait,
				
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'edit' 		 => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
				'view'		 => HTTPS_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id'], 
				
			);


			$this->data['products'][] = $product;
		}

		$this->data['heading_title'] = 'Мониторинг конкурентов';	


		$this->data['token'] = $this->session->data['token'];

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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $filter_store_id;
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}	

		if (isset($this->request->get['filter_priceva_category'])) {
			$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
		}

		if (isset($this->request->get['filter_yam_hidden'])) {
			$url .= '&filter_yam_hidden=' . $this->request->get['filter_yam_hidden'];
		}

		if (isset($this->request->get['filter_is_illiquid'])) {
			$url .= '&filter_is_illiquid=' . $this->request->get['filter_is_illiquid'];
		}	

		if (isset($this->request->get['filter_competitors'])) {
			$url .= '&filter_competitors=' . $this->request->get['filter_competitors'];
		}

		if (isset($this->request->get['filter_show_without_links'])) {
			$url .= '&filter_show_without_links=' . $this->request->get['filter_show_without_links'];
		}	

		if (isset($this->request->get['filter_links_only_selected'])) {
			$url .= '&filter_links_only_selected=' . $this->request->get['filter_links_only_selected'];
		}	

		if (isset($this->request->get['filter_competitor_stock'])) {
			$url .= '&filter_competitor_stock=' . $this->request->get['filter_competitor_stock'];
		}

		if (isset($this->request->get['filter_competitor_not_stock'])) {
			$url .= '&filter_competitor_not_stock=' . $this->request->get['filter_competitor_not_stock'];
		}

		if (isset($this->request->get['filter_competitor_stock_all'])) {
			$url .= '&filter_competitor_stock_all=' . $this->request->get['filter_competitor_stock_all'];
		}

		if (isset($this->request->get['filter_competitor_not_stock_all'])) {
			$url .= '&filter_competitor_not_stock_all=' . $this->request->get['filter_competitor_not_stock_all'];
		}

		if (isset($this->request->get['filter_kitchenprofi_stock'])) {
			$url .= '&filter_kitchenprofi_stock=' . $this->request->get['filter_kitchenprofi_stock'];
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stock'])) {
			$url .= '&filter_kitchenprofi_not_stock=' . $this->request->get['filter_kitchenprofi_not_stock'];
		}

		if (isset($this->request->get['filter_kitchenprofi_stockwait'])) {
			$url .= '&filter_kitchenprofi_stockwait=' . $this->request->get['filter_kitchenprofi_stockwait'];
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stockwait'])) {
			$url .= '&filter_kitchenprofi_not_stockwait=' . $this->request->get['filter_kitchenprofi_not_stockwait'];
		}

		if (isset($this->request->get['filter_dates_periods'])) {
			$url .= '&filter_dates_periods=' . $this->request->get['filter_dates_periods'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&sort=p.quantity_stockM' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}			

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $filter_store_id;
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}	

		if (isset($this->request->get['filter_priceva_category'])) {
			$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
		}

		if (isset($this->request->get['filter_yam_hidden'])) {
			$url .= '&filter_yam_hidden=' . $this->request->get['filter_yam_hidden'];
		}

		if (isset($this->request->get['filter_is_illiquid'])) {
			$url .= '&filter_is_illiquid=' . $this->request->get['filter_is_illiquid'];
		}	

		if (isset($this->request->get['filter_competitors'])) {
			$url .= '&filter_competitors=' . $this->request->get['filter_competitors'];
		}

		if (isset($this->request->get['filter_show_without_links'])) {
			$url .= '&filter_show_without_links=' . $this->request->get['filter_show_without_links'];
		}	

		if (isset($this->request->get['filter_links_only_selected'])) {
			$url .= '&filter_links_only_selected=' . $this->request->get['filter_links_only_selected'];
		}	

		if (isset($this->request->get['filter_competitor_stock'])) {
			$url .= '&filter_competitor_stock=' . $this->request->get['filter_competitor_stock'];
		}

		if (isset($this->request->get['filter_competitor_not_stock'])) {
			$url .= '&filter_competitor_not_stock=' . $this->request->get['filter_competitor_not_stock'];
		}

		if (isset($this->request->get['filter_competitor_stock_all'])) {
			$url .= '&filter_competitor_stock_all=' . $this->request->get['filter_competitor_stock_all'];
		}

		if (isset($this->request->get['filter_competitor_not_stock_all'])) {
			$url .= '&filter_competitor_not_stock_all=' . $this->request->get['filter_competitor_not_stock_all'];
		}

		if (isset($this->request->get['filter_kitchenprofi_stock'])) {
			$url .= '&filter_kitchenprofi_stock=' . $this->request->get['filter_kitchenprofi_stock'];
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stock'])) {
			$url .= '&filter_kitchenprofi_not_stock=' . $this->request->get['filter_kitchenprofi_not_stock'];
		}

		if (isset($this->request->get['filter_kitchenprofi_stockwait'])) {
			$url .= '&filter_kitchenprofi_stockwait=' . $this->request->get['filter_kitchenprofi_stockwait'];
		}

		if (isset($this->request->get['filter_kitchenprofi_not_stockwait'])) {
			$url .= '&filter_kitchenprofi_not_stockwait=' . $this->request->get['filter_kitchenprofi_not_stockwait'];
		}

		if (isset($this->request->get['filter_dates_periods'])) {
			$url .= '&filter_dates_periods=' . $this->request->get['filter_dates_periods'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$url = '';
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $filter_store_id;
		}
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}
		if (isset($this->request->get['filter_priceva_category'])) {
			$url .= '&filter_priceva_category=' . $this->request->get['filter_priceva_category'];
		}
		if (isset($this->request->get['filter_dates_periods'])) {
			$url .= '&filter_dates_periods=' . $this->request->get['filter_dates_periods'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->data['href_filter_is_illiquid'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&filter_is_illiquid=1' . $url, 'SSL');
		$this->data['href_filter_show_without_links'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&filter_show_without_links=1' . $url, 'SSL');
		$this->data['href_competitor_stock_kitchenprofi_not_stock'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&filter_competitor_stock_all=1&filter_kitchenprofi_not_stock=1' . $url, 'SSL');
		$this->data['href_any_competitor_stock_kitchenprofi_not_stock'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'] . '&filter_competitor_stock=1&filter_kitchenprofi_not_stock=1' . $url, 'SSL');

		$this->data['filter_name'] 						= $filter_name;
		$this->data['filter_store_id'] 					= $filter_store_id;
		$this->data['filter_priceva_category'] 			= $filter_priceva_category;
		$this->data['filter_manufacturer_id'] 			= $filter_manufacturer_id;
		$this->data['filter_is_illiquid'] 				= $filter_is_illiquid;
		$this->data['filter_competitors'] 				= $filter_competitors;
		$this->data['filter_show_without_links']		= $filter_show_without_links;
		$this->data['filter_links_only_selected'] 		= $filter_links_only_selected;
		$this->data['filter_competitor_stock'] 			= $filter_competitor_stock;
		$this->data['filter_competitor_not_stock'] 		= $filter_competitor_not_stock;
		$this->data['filter_competitor_stock_all'] 		= $filter_competitor_stock_all;
		$this->data['filter_competitor_not_stock_all'] 	= $filter_competitor_not_stock_all;
		$this->data['filter_kitchenprofi_stock'] 		= $filter_kitchenprofi_stock;
		$this->data['filter_kitchenprofi_not_stock'] 	= $filter_kitchenprofi_not_stock;
		$this->data['filter_kitchenprofi_stockwait'] 		= $filter_kitchenprofi_stockwait;
		$this->data['filter_kitchenprofi_not_stockwait'] 	= $filter_kitchenprofi_not_stockwait;
		$this->data['filter_dates_periods'] 				= $filter_dates_periods;


			//Посчитаем неликвид
		$data = array(			
			'filter_is_illiquid'   		=> true,
			'filter_show_without_links' => false,
			'filter_count_explicit' 	=> null,
			'filter_status'   		 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true,
			'filter_store_id'	  		=> $filter_store_id
		);

		$this->data['total_not_liquid'] = $this->model_kp_priceva->getTotalProducts($data);

		$data = array(			
			'filter_current_stock_field' 	=> $catalogStockField,
			'filter_store_id'	  			=> $filter_store_id,
			'filter_is_illiquid'   		=> null,
			'filter_show_without_links' => true,
			'filter_count_explicit' 	=> null,
			'filter_status'   		 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
		);

		$this->data['total_without_links'] = $this->model_kp_priceva->getTotalProducts($data);

		$data = array(
			'filter_current_stock_field' 	=> $catalogStockField,
			'filter_store_id'	  			=> $filter_store_id,			
			'filter_is_illiquid'   		=> null,
			'filter_show_without_links' => null,
			'filter_count_explicit' 	=> true,
			'filter_status'   		 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true
		);
		$this->data['total_total_in_db'] = $this->model_kp_priceva->getTotalProducts($data);

		$data = array(		
			'filter_current_stock_field' 	=> $catalogStockField,
			'filter_store_id'	  			=> $filter_store_id,	
			'filter_is_illiquid'   		=> null,
			'filter_show_without_links' => null,
			'filter_count_explicit' 	=> null,
			'filter_status'   		 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true,
			'filter_competitor_stock_all' => true,
			'filter_kitchenprofi_not_stock' => true,
		);
		$this->data['total_competitor_stock_kitchenprofi_not_stock'] = $this->model_kp_priceva->getTotalProducts($data);

		$data = array(	
			'filter_current_stock_field' 	=> $catalogStockField,
			'filter_store_id'	  			=> $filter_store_id,	
			'filter_is_illiquid'   		=> null,
			'filter_show_without_links' => null,
			'filter_count_explicit' 	=> null,
			'filter_status'   		 	=> true,
			'filter_not_markdown' 		=> true,
			'filter_not_virtual' 		=> true,
			'filter_competitor_stock' 	=> true,
			'filter_kitchenprofi_not_stock' => true,			
		);
		$this->data['total_any_competitor_stock_kitchenprofi_not_stock'] = $this->model_kp_priceva->getTotalProducts($data);



		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/priceva.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
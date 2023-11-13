<?php
class ControllerReportProductPurchased extends Controller { 
	private $modelCatalogProduct = null;

	public function index() {   	

		$this->load->model('report/product');
		$this->load->model('catalog/category');		
		$this->load->model('tool/image');
		$this->load->model('localisation/order_status');

		$this->data = $this->load->language('report/product_purchased');

		loadAndRenameCatalogModels('model/catalog/product.php', 'ModelCatalogProduct', 'ModelCatalogProductCatalog');
		$this->modelCatalogProduct = new ModelCatalogProductCatalog($this->registry);

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
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

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = null;
		}

		if (isset($this->request->get['filter_amazon_offers_type'])) {
			$filter_amazon_offers_type = $this->request->get['filter_amazon_offers_type'];
		} else {
			$filter_amazon_offers_type 	= null;
		}

		if (isset($this->request->get['filter_amazon_seller_quality'])) {
			$filter_amazon_seller_quality = $this->request->get['filter_amazon_seller_quality'];
		} else {
			$filter_amazon_seller_quality 	= null;
		}

		if (isset($this->request->get['filter_sort'])) {
			$filter_sort = $this->request->get['filter_sort'];
		} else {
			$filter_sort = null;
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

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_amazon_offers_type'])) {
			$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
		}

		if (isset($this->request->get['filter_amazon_seller_quality'])) {
			$url .= '&filter_amazon_seller_quality=' . $this->request->get['filter_amazon_seller_quality'];
		}

		if (isset($this->request->get['filter_sort'])) {
			$url .= '&filter_sort=' . $this->request->get['filter_sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'] . $url),
			'separator' => ' :: '
		);

		$data = [
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_status_id' => $filter_order_status_id,
			'filter_category_id' 	 		=> $filter_category_id,
			'filter_manufacturer_id' 	 	=> $filter_manufacturer_id,
			'filter_amazon_offers_type'		=> $filter_amazon_offers_type,
			'filter_amazon_seller_quality' 	=> $filter_amazon_seller_quality,
			'filter_sort' 	 		 => $filter_sort,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		];		

		if (!empty($this->request->get['filter_download_csv'])){
			unset($data['start']);
			unset($data['limit']);
		}

		$product_total 	= $this->model_report_product->getTotalPurchased($data);
		$results 		= $this->model_report_product->getPurchased($data);

		$this->data['products'] = [];
		foreach ($results as $result) {
			$product_info 			= $this->modelCatalogProduct->getProduct($result['product_id'], false);

			if ($product_info){
				$result['front_price'] 	= $this->currency->format_with_left($product_info['price'], $this->config->get('config_regional_currency'));

				$result['front_special'] = false;
				if ((float)$product_info['special']) {
					$result['front_special'] = $this->currency->format_with_left($product_info['special'], $this->config->get('config_regional_currency'));
				}
			} else {
				$result['front_price'] 		= false;
				$result['front_special'] 	= false;
			}		

			if (!empty($this->request->get['filter_download_csv'])){
				$this->data['products'][] = [	
					'product_id' 	=> $result['product_id'],
					'asin'       	=> $result['asin'],
					'name'       	=> $result['name'],
					'de_name'  		=> $result['de_name'],
					'manufacturer'  => $result['manufacturer_name'],
					'status'       	=> $result['status'],
					'ean'        	=> $result['ean'],
					'quantity'   	=> $result['quantity'],
					'orders'   		=> $result['orders'],

					'profitability' => $result['profitability'],				

					'amazon_offers_type' 	=> $result['amazon_offers_type'],
					'amazon_seller_quality' => $result['amazon_seller_quality'],

					'front_price'	=> $result['front_price'],
					'front_special' => $result['front_special'],

					'cost'			=> $this->currency->format($result['costprice'], $this->config->get('config_currency'), 1),
					'cost_national'	=> $this->currency->format($result['costprice'], $this->config->get('config_regional_currency')),

					'total'			=> $this->currency->format($result['total'], $this->config->get('config_currency'), 1),
					'total_national'=> $this->currency->format($result['total'], $this->config->get('config_regional_currency')),

					'amazon_best_price'				=> $this->currency->format($result['amazon_best_price'], $this->config->get('config_currency'), 1),
					'amazon_best_price_national' 	=> $this->currency->format($result['amazon_best_price'], $this->config->get('config_regional_currency'))
				];


			} else {
				$category_info = $this->model_catalog_category->getCategory($result['main_category_id']);

				if ($category_info){
					$category_path = ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name'];		
				} else {
					$category_path = false;
				}	

				$this->data['products'][] = [	
					'product_id' 	=> $result['product_id'],
					'asin'       	=> $result['asin'],
					'name'       	=> $result['name'],
					'de_name'  		=> $result['de_name'],
					'manufacturer'  => $result['manufacturer_name'],
					'status'       	=> $result['status'],
					'model'      	=> $result['model'],
					'ean'        	=> $result['ean'],
					'sku'       	=> $result['sku'],
					'image'		 	=> $this->model_tool_image->resize($result['image'], 50, 50),
					'category_path' => $category_path,
					'quantity'   	=> $result['quantity'],
					'orders'   		=> $result['orders'],

					'profitability' => $result['profitability'],				

					'amazon_offers_type' 	=> $result['amazon_offers_type'],
					'amazon_seller_quality' => $result['amazon_seller_quality'],

					'front_price'	=> $result['front_price'],
					'front_special' => $result['front_special'],

					'cost'			=> $this->currency->format($result['costprice'], $this->config->get('config_currency'), 1),
					'cost_national'	=> $this->currency->format($result['costprice'], $this->config->get('config_regional_currency')),

					'total'					=> $this->currency->format($result['total'], $this->config->get('config_currency'), 1),
					'total_national'      	=> $this->currency->format($result['total'], $this->config->get('config_regional_currency')),

					'amazon_best_price'				=> $this->currency->format($result['amazon_best_price'], $this->config->get('config_currency'), 1),
					'amazon_best_price_national' 	=> $this->currency->format($result['amazon_best_price'], $this->config->get('config_regional_currency'))
				];
			}			
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_manufacturer_id'])) {
			$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
		}

		if (isset($this->request->get['filter_amazon_offers_type'])) {
			$url .= '&filter_amazon_offers_type=' . $this->request->get['filter_amazon_offers_type'];
		}

		if (isset($this->request->get['filter_amazon_seller_quality'])) {
			$url .= '&filter_amazon_seller_quality=' . $this->request->get['filter_amazon_seller_quality'];
		}

		if (isset($this->request->get['filter_sort'])) {
			$url .= '&filter_sort=' . $this->request->get['filter_sort'];
		}

		$pagination 		= new Pagination();
		$pagination->total 	= $product_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $this->config->get('config_admin_limit');
		$pagination->text 	= $this->language->get('text_pagination');
		$pagination->url 	= $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] 		= $filter_date_start;
		$this->data['filter_date_end'] 			= $filter_date_end;		
		$this->data['filter_order_status_id'] 	= $filter_order_status_id;
		$this->data['filter_category_id'] 			= $filter_category_id;
		$this->data['filter_manufacturer_id'] 		= $filter_manufacturer_id;
		$this->data['filter_amazon_offers_type'] 	= $filter_amazon_offers_type;
		$this->data['filter_amazon_seller_quality'] = $filter_amazon_seller_quality;
		$this->data['filter_sort'] 				= $filter_sort;

		$this->data['filter_category_path'] = '';
		if (!empty($filter_category_id)){
			$this->load->model('catalog/category');

			$category_info = $this->model_catalog_category->getCategory($filter_category_id);
			$this->data['filter_category_path'] = ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name'];
		}

		$this->data['filter_manufacturer'] = '';
		if (!empty($filter_manufacturer_id)){
			$this->load->model('catalog/manufacturer');

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($filter_manufacturer_id);
			$this->data['filter_manufacturer'] = $manufacturer_info['name'];
		}

		$this->template = 'report/product_purchased.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		if (!empty($this->request->get['filter_download_csv'])){
			$this->response->outputCSV($this->data['products'], [], 'products_purchased');
		} else {
			$this->response->setOutput($this->render());
		}		
	}	
}